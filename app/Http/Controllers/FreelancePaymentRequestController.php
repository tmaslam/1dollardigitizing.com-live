<?php

namespace App\Http\Controllers;

use App\Models\AdminUser;
use App\Models\FreelancePaymentRequest;
use App\Models\FreelanceQuote;
use App\Models\Order;
use App\Models\TeamFine;
use App\Support\AdminNavigation;
use App\Support\PortalMailer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FreelancePaymentRequestController extends Controller
{
    public function store(Request $request)
    {
        $teamUser = $request->attributes->get('teamUser');

        if (! $teamUser->isFreelance()) {
            abort(403);
        }

        $userId = $teamUser->user_id;

        $hasPending = FreelancePaymentRequest::where('freelancer_id', $userId)
            ->where('status', 'pending')
            ->exists();

        if ($hasPending) {
            return redirect()->route('team.dashboard')
                ->with('payment_error', 'You already have a pending payment request.');
        }

        $unpaidAmount = $this->computeUnpaidAmount($userId);

        if ($unpaidAmount <= 0) {
            return redirect()->route('team.dashboard')
                ->with('payment_error', 'No unpaid earnings available to withdraw.');
        }

        FreelancePaymentRequest::create([
            'freelancer_id' => $userId,
            'status'        => 'pending',
            'requested_at'  => now(),
            'amount_pkr'    => $unpaidAmount,
        ]);

        $this->notifyAdminsPaymentRequested($teamUser->display_name, $unpaidAmount);

        return redirect()->route('team.dashboard')
            ->with('payment_success', 'Payment request submitted — admin will process it shortly.');
    }

    public function adminIndex(Request $request)
    {
        $adminUser = $request->attributes->get('adminUser');

        $requests = FreelancePaymentRequest::with('freelancer', 'paidBy')
            ->orderByRaw("FIELD(status, 'pending', 'paid')")
            ->orderByDesc('requested_at')
            ->get();

        return view('admin.people.freelance-payments', [
            'adminUser'  => $adminUser,
            'navCounts'  => AdminNavigation::counts(),
            'requests'   => $requests,
        ]);
    }

    public function pay(Request $request, FreelancePaymentRequest $paymentRequest)
    {
        if ($paymentRequest->status !== 'pending') {
            return back()->with('error', 'This request has already been processed.');
        }

        $freelancerId = $paymentRequest->freelancer_id;

        DB::transaction(function () use ($paymentRequest, $freelancerId, $request) {
            $adminUser = $request->attributes->get('adminUser');

            $totalNet = $this->computeUnpaidAmount($freelancerId);

            $paymentRequest->update([
                'status'     => 'paid',
                'paid_at'    => now(),
                'paid_by'    => $adminUser->user_id,
                'amount_pkr' => $totalNet,
            ]);

            Order::query()
                ->active()
                ->where('assign_to', $freelancerId)
                ->where('status', 'done')
                ->whereNull('freelance_payment_request_id')
                ->update(['freelance_payment_request_id' => $paymentRequest->id]);
        });

        $freelancer = AdminUser::find($freelancerId);
        if ($freelancer && $freelancer->user_email) {
            $this->notifyFreelancerPaid($freelancer, $paymentRequest->fresh());
        }

        return back()->with('success', 'Payment marked as paid and freelancer notified.');
    }

    private function computeUnpaidAmount(int $userId): float
    {
        $doneOrders = Order::query()
            ->active()
            ->where('assign_to', $userId)
            ->where('status', 'done')
            ->whereNull('freelance_payment_request_id')
            ->get(['order_id']);

        $orderIds = $doneOrders->pluck('order_id')->all();

        if (empty($orderIds)) {
            return 0.0;
        }

        $quotes = FreelanceQuote::query()
            ->where('team_user_id', $userId)
            ->where('status', 'accepted')
            ->whereIn('order_id', $orderIds)
            ->get(['order_id', 'quoted_price'])
            ->keyBy('order_id');

        $fines = TeamFine::query()
            ->where('team_user_id', $userId)
            ->whereIn('order_id', $orderIds)
            ->get(['order_id', 'amount'])
            ->keyBy('order_id');

        $total = 0.0;
        foreach ($orderIds as $orderId) {
            $gross = (float) ($quotes[$orderId]?->quoted_price ?? 0);
            $fine  = (float) ($fines[$orderId]?->amount ?? 0);
            $total += max(0, $gross - $fine);
        }

        return $total;
    }

    private function notifyAdminsPaymentRequested(string $freelancerName, float $amount): void
    {
        $adminEmail = (string) config('mail.admin_alert_address', '');
        if ($adminEmail === '') {
            return;
        }

        $subject = 'Freelancer Payment Withdrawal Request — '.$freelancerName;
        $body = view('admin.emails.freelance-payment-requested', [
            'freelancerName' => $freelancerName,
            'amountPkr'      => $amount,
            'reviewUrl'      => url('/v/freelance-payments.php'),
        ])->render();

        PortalMailer::sendHtml($adminEmail, $subject, $body);
    }

    private function notifyFreelancerPaid(AdminUser $freelancer, FreelancePaymentRequest $paymentRequest): void
    {
        $email = $freelancer->user_email ?? '';
        if (PortalMailer::normalizeRecipient($email) === null) {
            return;
        }

        $subject = 'Your Payment Has Been Processed — PKR '.number_format((float) $paymentRequest->amount_pkr, 2);
        $body = view('admin.emails.freelance-payment-confirmed', [
            'freelancerName' => $freelancer->display_name,
            'amountPkr'      => (float) $paymentRequest->amount_pkr,
            'paidAt'         => $paymentRequest->paid_at?->format('M j, Y'),
        ])->render();

        PortalMailer::sendHtml($email, $subject, $body);
    }
}
