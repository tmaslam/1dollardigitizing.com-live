<?php

namespace App\Http\Controllers;

use App\Models\FreelanceQuote;
use App\Models\Order;
use App\Models\TeamFine;
use App\Support\TeamNavigation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class TeamJobHistoryController extends Controller
{
    public function index(Request $request)
    {
        $teamUser = $request->attributes->get('teamUser');
        $month = $request->query('month', '');

        $query = Order::query()
            ->active()
            ->where('assign_to', $teamUser->user_id)
            ->whereIn('status', ['done', 'Ready'])
            ->orderByDesc('vender_complete_date')
            ->orderByDesc('order_id');

        if ($month !== '' && preg_match('/^\d{4}-\d{2}$/', $month)) {
            $query->whereRaw("DATE_FORMAT(vender_complete_date, '%Y-%m') = ?", [$month]);
        }

        $orders = $query->get();

        $orderIds = $orders->pluck('order_id')->all();

        $finesByOrder = TeamFine::query()
            ->where('team_user_id', $teamUser->user_id)
            ->whereIn('order_id', $orderIds)
            ->get()
            ->keyBy('order_id');

        $isFreelance = $teamUser->isFreelance();
        $quotesByOrder = [];
        if ($isFreelance) {
            $quotesByOrder = FreelanceQuote::query()
                ->where('team_user_id', $teamUser->user_id)
                ->where('status', 'accepted')
                ->whereIn('order_id', $orderIds)
                ->get()
                ->keyBy('order_id');
        }

        if ($request->query('export') === 'csv') {
            return $this->csvExport($orders, $finesByOrder, $quotesByOrder, $isFreelance, $teamUser->user_name);
        }

        $months = $this->availableMonths($teamUser->user_id);

        return view('team.my-jobs', [
            'teamUser'      => $teamUser,
            'navCounts'     => TeamNavigation::counts($teamUser->user_id, (int) $teamUser->usre_type_id),
            'orders'        => $orders,
            'finesByOrder'  => $finesByOrder,
            'quotesByOrder' => $quotesByOrder,
            'isFreelance'   => $isFreelance,
            'selectedMonth' => $month,
            'months'        => $months,
        ]);
    }

    private function availableMonths(int $userId): array
    {
        return Order::query()
            ->active()
            ->where('assign_to', $userId)
            ->whereIn('status', ['done', 'Ready'])
            ->whereNotNull('vender_complete_date')
            ->where('vender_complete_date', '!=', '')
            ->selectRaw("DATE_FORMAT(vender_complete_date, '%Y-%m') as month_key, DATE_FORMAT(vender_complete_date, '%M %Y') as month_label")
            ->groupBy('month_key', 'month_label')
            ->orderByDesc('month_key')
            ->limit(24)
            ->pluck('month_label', 'month_key')
            ->all();
    }

    private function csvExport($orders, $finesByOrder, $quotesByOrder, bool $isFreelance, string $memberName)
    {
        $filename = 'job-history-'.now()->format('Ymd-His').'.csv';

        $headers = ['Date', 'Order #', 'Design Name', 'Stitches / Hours', 'Status'];
        if ($isFreelance) {
            $headers[] = 'Accepted Price (PKR)';
            $headers[] = 'Fine (PKR)';
            $headers[] = 'Net (PKR)';
            $headers[] = 'Payment';
        } else {
            $headers[] = 'Fine (Rs.)';
        }

        $handle = fopen('php://temp', 'r+');
        fputcsv($handle, $headers);

        $totalJobs = 0;
        $totalStitches = 0;
        $totalNetPkr = 0;
        $totalFines = 0;

        foreach ($orders as $order) {
            $fine = $finesByOrder[$order->order_id] ?? null;
            $fineAmount = $fine ? (float) $fine->amount : 0.0;
            $status = $order->status === 'done' ? 'Completed' : 'Pending Review';

            $row = [
                $order->vender_complete_date ?: '—',
                $order->order_id,
                $order->design_name ?: '—',
                $order->stitches ?: '—',
                $status,
            ];

            if ($isFreelance) {
                $quote = $quotesByOrder[$order->order_id] ?? null;
                $grossPkr = $quote ? (float) $quote->quoted_price : 0.0;
                $netPkr = max(0, $grossPkr - $fineAmount);
                $paymentStatus = $order->freelance_payment_request_id ? 'Paid' : 'Unpaid';
                $row[] = number_format($grossPkr, 2);
                $row[] = $fineAmount > 0 ? number_format($fineAmount, 2) : '—';
                $row[] = number_format($netPkr, 2);
                $row[] = $paymentStatus;
                $totalNetPkr += $netPkr;
            } else {
                $row[] = $fineAmount > 0 ? number_format($fineAmount, 2) : '—';
                $totalFines += $fineAmount;
            }

            fputcsv($handle, $row);
            $totalJobs++;
            $totalStitches += is_numeric($order->stitches) ? (float) $order->stitches : 0;
        }

        // Totals row
        $totals = ['TOTALS', 'Jobs: '.$totalJobs, '', 'Stitches: '.$totalStitches, ''];
        if ($isFreelance) {
            $totals[] = '';
            $totals[] = '';
            $totals[] = 'Net Total: PKR '.number_format($totalNetPkr, 2);
            $totals[] = '';
        } else {
            $totals[] = 'Fines: Rs. '.number_format($totalFines, 2);
        }
        fputcsv($handle, $totals);

        rewind($handle);
        $csv = stream_get_contents($handle) ?: '';
        fclose($handle);

        return response($csv, Response::HTTP_OK, [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="'.$filename.'"',
        ]);
    }
}
