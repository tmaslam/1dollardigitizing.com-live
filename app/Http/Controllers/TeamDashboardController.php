<?php

namespace App\Http\Controllers;

use App\Models\FreelancePaymentRequest;
use App\Models\FreelanceQuote;
use App\Models\Order;
use App\Models\TeamFine;
use App\Support\TeamNavigation;
use App\Support\TeamWorkQueues;
use Illuminate\Http\Request;

class TeamDashboardController extends Controller
{
    public function index(Request $request)
    {
        $teamUser = $request->attributes->get('teamUser');
        $navCounts = TeamNavigation::counts($teamUser->user_id, (int) $teamUser->usre_type_id);

        $freelanceEarnings = null;
        if ($teamUser->isFreelance()) {
            $freelanceEarnings = $this->computeFreelanceEarnings($teamUser->user_id);
        }

        return view('team.dashboard', [
            'teamUser'          => $teamUser,
            'navCounts'         => $navCounts,
            'queueNavigation'   => TeamWorkQueues::navigation($navCounts),
            'currentQueueKey'   => null,
            'freelanceEarnings' => $freelanceEarnings,
        ]);
    }

    private function computeFreelanceEarnings(int $userId): array
    {
        $doneOrders = Order::query()
            ->active()
            ->where('assign_to', $userId)
            ->where('status', 'done')
            ->get(['order_id', 'freelance_payment_request_id']);

        $orderIds = $doneOrders->pluck('order_id')->all();

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

        $totalJobs = $doneOrders->count();
        $totalEarned = 0;
        $pendingAmount = 0;
        $paidAmount = 0;

        foreach ($doneOrders as $order) {
            $gross = (float) ($quotes[$order->order_id]?->quoted_price ?? 0);
            $fine = (float) ($fines[$order->order_id]?->amount ?? 0);
            $net = max(0, $gross - $fine);
            $totalEarned += $net;
            if ($order->freelance_payment_request_id) {
                $paidAmount += $net;
            } else {
                $pendingAmount += $net;
            }
        }

        $hasPendingRequest = FreelancePaymentRequest::where('freelancer_id', $userId)
            ->where('status', 'pending')
            ->exists();

        return [
            'total_jobs'          => $totalJobs,
            'total_earned_pkr'    => $totalEarned,
            'pending_payment_pkr' => $pendingAmount,
            'paid_pkr'            => $paidAmount,
            'has_pending_request' => $hasPendingRequest,
            'can_request_payment' => $pendingAmount > 0 && ! $hasPendingRequest,
        ];
    }
}
