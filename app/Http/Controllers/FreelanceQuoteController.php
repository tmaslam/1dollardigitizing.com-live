<?php

namespace App\Http\Controllers;

use App\Models\AdminUser;
use App\Models\FreelanceQuote;
use App\Models\Order;
use App\Support\PortalMailer;
use Illuminate\Http\Request;

class FreelanceQuoteController extends Controller
{
    public function submit(Request $request, Order $order)
    {
        $teamUser = $request->attributes->get('teamUser');

        abort_unless($order->assigned_group === 'freelance', 403);
        abort_unless((int) $order->assign_to === 0 || $order->assign_to === null || $order->assign_to === '', 403);
        abort_unless($teamUser->isFreelance(), 403);

        $validated = $request->validate([
            'quoted_price' => ['required', 'numeric', 'min:0.01'],
            'notes'        => ['nullable', 'string', 'max:1000'],
        ]);

        $isNew = ! FreelanceQuote::query()
            ->where('order_id', $order->order_id)
            ->where('team_user_id', $teamUser->user_id)
            ->exists();

        FreelanceQuote::query()->updateOrCreate(
            ['order_id' => $order->order_id, 'team_user_id' => $teamUser->user_id],
            [
                'quoted_price' => $validated['quoted_price'],
                'notes'        => $validated['notes'] ?? null,
                'status'       => 'pending',
            ]
        );

        // Send notification only on first submission
        if ($isNew) {
            $this->notifyAdmin($order, $teamUser, (float) $validated['quoted_price']);
        }

        return redirect()->back()->with('quote_success', 'Your quote has been submitted successfully.');
    }

    private function notifyAdmin(Order $order, AdminUser $freelancer, float $price): void
    {
        $adminEmails = AdminUser::query()
            ->admins()
            ->active()
            ->where('is_active', 1)
            ->whereNotNull('user_email')
            ->where('user_email', '!=', '')
            ->pluck('user_email')
            ->all();

        $supervisorEmails = AdminUser::query()
            ->supervisors()
            ->active()
            ->where('is_active', 1)
            ->whereNotNull('user_email')
            ->where('user_email', '!=', '')
            ->pluck('user_email')
            ->all();

        $allEmails = array_unique(array_merge($adminEmails, $supervisorEmails));

        if (empty($allEmails)) {
            return;
        }

        $freelancerName = trim((string) ($freelancer->display_name ?: $freelancer->user_name));
        $subject = "Freelancer quote received — Order #{$order->order_id}";

        $body = view('admin.emails.freelance-quote-notification', [
            'freelancerName' => $freelancerName,
            'orderId'        => $order->order_id,
            'quotedPrice'    => number_format($price, 2),
            'reviewUrl'      => url('/v/assign-order.php?design_id='.$order->order_id.'&page=order'),
        ])->render();

        foreach ($allEmails as $email) {
            PortalMailer::sendHtml($email, $subject, $body);
        }
    }
}
