<?php

namespace App\Http\Controllers;

use App\Models\AdminUser;
use App\Models\Billing;
use App\Models\PaymentTransaction;
use App\Support\AdminNavigation;
use App\Support\CustomerBalance;
use App\Support\SecurityAlertSummary;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class AdminDashboardController extends Controller
{
    public function index(Request $request)
    {
        $navCounts = AdminNavigation::counts();
        $hasCreditLedger = true;
        $customerCreditInventory = CustomerBalance::balances();

        $planPrices = ['growth' => 90, 'studio' => 170, 'production' => 320, 'enterprise' => 700, 'corporate' => 1200];
        $subscribers = AdminUser::query()->customers()
            ->whereNotNull('subscription_plan')
            ->whereNotIn('subscription_plan', [''])
            ->get(['user_id', 'subscription_plan', 'subscription_status']);
        $subscriptionMrr = $subscribers->sum(fn ($u) => (float) ($planPrices[strtolower(trim((string) $u->subscription_plan))] ?? 0));

        $totalReceivedAllTime = (float) PaymentTransaction::query()
            ->whereIn('status', ['verified', 'success'])
            ->sum(\Illuminate\Support\Facades\DB::raw('CAST(confirmed_amount AS DECIMAL(12,2))'));

        $financialSnapshot = [
            'due_invoices' => Billing::query()->active()->where('approved', 'yes')->where('payment', 'no')->count(),
            'due_amount' => (float) Billing::query()->active()->where('approved', 'yes')->where('payment', 'no')->sum(\Illuminate\Support\Facades\DB::raw('CAST(amount AS DECIMAL(12,2))')),
            'received_invoices' => Billing::query()->active()->where('approved', 'yes')->where('payment', 'yes')->count(),
            'received_amount' => (float) Billing::query()->active()->where('approved', 'yes')->where('payment', 'yes')->sum(\Illuminate\Support\Facades\DB::raw('CAST(amount AS DECIMAL(12,2))')),
            'total_received_all_time' => $totalReceivedAllTime,
            'customer_balance' => (float) $customerCreditInventory->sum(fn ($row) => (float) $row->balance_total),
            'customers_with_credit' => $customerCreditInventory->count(),
            'subscription_mrr' => $subscriptionMrr,
            'subscription_count' => $subscribers->count(),
        ];

        $operationsSnapshot = [
            'active_customers' => $navCounts['customers'],
            'blocked_customers' => $navCounts['blocked_customers'],
            'team_accounts' => AdminUser::query()->teams()->active()->where('is_active', 1)->count(),
            'supervisors' => AdminUser::query()->supervisors()->active()->where('is_active', 1)->count(),
            'all_open_work' => $navCounts['all_orders'],
        ];

        $workflowFocus = [
            'review_ready' => ($navCounts['designer_completed_orders'] ?? 0) + ($navCounts['designer_completed_quotes'] ?? 0),
            'approval_waiting' => $navCounts['approval_waiting_orders'] ?? 0,
            'new_work' => ($navCounts['new_orders'] ?? 0) + ($navCounts['new_quotes'] ?? 0),
            'assigned_work' => ($navCounts['designer_orders'] ?? 0) + ($navCounts['assigned_quotes'] ?? 0),
        ];

        $securityWatch = SecurityAlertSummary::summary();

        return view('admin.dashboard', [
            'adminUser' => $request->attributes->get('adminUser'),
            'navCounts' => $navCounts,
            'financialSnapshot' => $financialSnapshot,
            'operationsSnapshot' => $operationsSnapshot,
            'workflowFocus' => $workflowFocus,
            'securityWatch' => $securityWatch,
            'hasCreditLedger' => $hasCreditLedger,
        ]);
    }
}
