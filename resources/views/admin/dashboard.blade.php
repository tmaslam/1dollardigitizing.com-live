@extends('layouts.admin')

@section('title', 'Dashboard | 1Dollar Admin')
@section('page_heading', 'Dashboard')
@section('page_subheading', 'A cleaner control center for workload, approvals, payments, and account health.')

@section('content')
    <section class="stats" style="margin-bottom:6px;">
        <a class="stat-link" href="{{ \App\Support\AdminOrderQueues::url('new-orders') }}">
            <article class="stat">
                <span class="muted">New Orders</span>
                <strong>{{ $navCounts['new_orders'] }}</strong>
            </article>
        </a>
        <a class="stat-link" href="{{ \App\Support\AdminOrderQueues::url('designer-orders') }}">
            <article class="stat">
                <span class="muted">Assigned Orders</span>
                <strong>{{ $navCounts['designer_orders'] }}</strong>
            </article>
        </a>
        <a class="stat-link" href="{{ \App\Support\AdminOrderQueues::url('designer-completed') }}">
            <article class="stat">
                <span class="muted">Designer Completed</span>
                <strong>{{ $navCounts['designer_completed_orders'] }}</strong>
            </article>
        </a>
        <a class="stat-link" href="{{ \App\Support\AdminOrderQueues::url('disapproved-orders') }}">
            <article class="stat">
                <span class="muted">Disapproved Orders</span>
                <strong>{{ $navCounts['disapproved_orders'] }}</strong>
            </article>
        </a>
    </section>

    <section class="stats" style="margin-bottom:6px;">
        <a class="stat-link" href="{{ \App\Support\AdminOrderQueues::url('new-quotes') }}">
            <article class="stat">
                <span class="muted">New Quotes</span>
                <strong>{{ $navCounts['new_quotes'] }}</strong>
            </article>
        </a>
        <a class="stat-link" href="{{ \App\Support\AdminOrderQueues::url('assigned-quotes') }}">
            <article class="stat">
                <span class="muted">Assigned Quotes</span>
                <strong>{{ $navCounts['assigned_quotes'] }}</strong>
            </article>
        </a>
        <a class="stat-link" href="{{ \App\Support\AdminOrderQueues::url('designer-completed-quotes') }}">
            <article class="stat">
                <span class="muted">Designer Completed Quotes</span>
                <strong>{{ $navCounts['designer_completed_quotes'] }}</strong>
            </article>
        </a>
        <a class="stat-link" href="{{ \App\Support\AdminOrderQueues::url('quote-negotiations') }}">
            <article class="stat">
                <span class="muted">Quote Negotiations</span>
                <strong>{{ $navCounts['quote_negotiations'] }}</strong>
            </article>
        </a>
    </section>

    <section class="stats" style="margin-bottom:22px;">
        <a class="stat-link" href="{{ url('/v/customer-approvals.php') }}">
            <article class="stat">
                <span class="muted">Pending Customers</span>
                <strong>{{ $navCounts['pending_customer_approvals'] }}</strong>
            </article>
        </a>
        <a class="stat-link" href="{{ url('/v/customer_list.php') }}">
            <article class="stat">
                <span class="muted">Active Customers</span>
                <strong>{{ $navCounts['customers'] }}</strong>
            </article>
        </a>
        <a class="stat-link" href="{{ url('/v/block-customer_list.php') }}">
            <article class="stat">
                <span class="muted">Inactive Customers</span>
                <strong>{{ $navCounts['blocked_customers'] }}</strong>
            </article>
        </a>
        <a class="stat-link" href="{{ url('/v/show-all-teams.php') }}">
            <article class="stat">
                <span class="muted">Teams</span>
                <strong>{{ $navCounts['teams'] }}</strong>
            </article>
        </a>
    </section>

    <style>
        .snapshot-section .stat {
            padding: 24px;
            background: #fff;
            border: 1px solid rgba(24, 34, 45, 0.12);
            border-radius: 22px;
        }
        .snapshot-section .stat .view-link {
            display: block;
            margin-top: 12px;
            font-size: 0.82rem;
            font-weight: 700;
            color: #0f5f66;
        }
    </style>

    <section class="card snapshot-section">
        <div class="card-body">
            <div class="section-head">
                <div>
                    <h3>Financial Snapshot</h3>
                    <p class="section-copy">Payment pressure and money movement at a glance.</p>
                </div>
                <a href="{{ url('/v/payment-due-report.php') }}" class="badge">Open Payment Reports</a>
            </div>
            <div class="stats">
                <a class="stat-link" href="{{ url('/v/payment-recieved-report.php') }}">
                    <article class="stat">
                        <span class="muted">Received Amount</span>
                        <strong>${{ number_format($financialSnapshot['total_received_all_time'], 2) }}</strong>
                        <div class="muted">Total payments received from day 1 to date.</div>
                        <span class="view-link">View: Payment Received Report</span>
                    </article>
                </a>
                <a class="stat-link" href="{{ url('/v/payment-due-report.php') }}">
                    <article class="stat">
                        <span class="muted">Due Amount</span>
                        <strong>${{ number_format($financialSnapshot['due_amount'], 2) }}</strong>
                        <div class="muted">Across {{ $financialSnapshot['due_invoices'] }} unpaid approved invoice rows.</div>
                        <span class="view-link">View: Payment Due Report</span>
                    </article>
                </a>
                @if ($hasCreditLedger)
                <a class="stat-link" href="{{ url('/v/customer-payment-inventory.php') }}">
                    <article class="stat">
                        <span class="muted">Available Customer Credit</span>
                        <strong>${{ number_format($financialSnapshot['customer_balance'] ?? 0, 2) }}</strong>
                        <div class="muted">Across {{ $financialSnapshot['customers_with_credit'] }} active customers with credit ready to apply to future invoices.</div>
                        <span class="view-link">View: Customer Credit Inventory</span>
                    </article>
                </a>
                @endif
                <a class="stat-link" href="{{ url('/v/subscription-report.php') }}">
                    <article class="stat">
                        <span class="muted">Total Subscription Amount</span>
                        <strong>${{ number_format($financialSnapshot['subscription_mrr'], 2) }}</strong>
                        <div class="muted">Across {{ $financialSnapshot['subscription_count'] }} active subscribers.</div>
                        <span class="view-link">View: Subscription Report</span>
                    </article>
                </a>
            </div>
        </div>
    </section>

    <section class="card">
        <div class="card-body">
            <div class="section-head">
                <div>
                    <h3>Recent Payment Transactions</h3>
                    <p class="section-copy">Latest successful payments with customer and method details.</p>
                </div>
                <a href="{{ url('/v/payment-recieved-report.php') }}" class="badge">View All</a>
            </div>
            @if ($paymentTransactions->isEmpty())
                <div class="empty-state">No payment transactions found.</div>
            @else
                <div class="table-wrap">
                    <table>
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Amount</th>
                                <th>Customer ID</th>
                                <th>Customer Name</th>
                                <th>Source</th>
                                <th>Method</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($paymentTransactions as $tx)
                                @php
                                    $payload = json_decode((string) $tx->provider_payload, true) ?: [];
                                    $planType = $payload['plan_type'] ?? '';
                                    $methodLabel = match((string) $tx->payment_scope) {
                                        'credit_purchase' => match($planType) {
                                            'credit'       => 'Credit Pack',
                                            'subscription' => 'Subscription',
                                            'custom'       => 'Custom Amount',
                                            default        => 'Credit Purchase',
                                        },
                                        'signup_offer'        => 'Signup Offer',
                                        'outstanding_balance' => 'Invoice Payment',
                                        'single_invoice'      => 'Invoice Payment',
                                        default               => ucfirst(str_replace('_', ' ', (string) $tx->payment_scope)),
                                    };
                                    $sourceLabel = match((string) $tx->payment_scope) {
                                        'credit_purchase'     => 'Credit Purchase',
                                        'signup_offer'        => 'Signup Offer',
                                        'outstanding_balance' => 'Invoice Payment',
                                        'single_invoice'      => 'Invoice Payment',
                                        default               => ucfirst(str_replace('_', ' ', (string) $tx->payment_scope)),
                                    };
                                @endphp
                                <tr>
                                    <td style="font-size:0.8rem;white-space:nowrap;">{{ $tx->created_at }}</td>
                                    <td style="font-weight:700;">${{ number_format((float) ($tx->confirmed_amount ?: $tx->requested_amount), 2) }}</td>
                                    <td>{{ $tx->user_id }}</td>
                                    <td>{{ $tx->customer?->display_name ?: '-' }}</td>
                                    <td><span class="badge">{{ $sourceLabel }}</span></td>
                                    <td>{{ $methodLabel }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </section>

    <section class="card snapshot-section">
        <div class="card-body">
            <div class="section-head">
                <div>
                    <h3>Operations Snapshot</h3>
                    <p class="section-copy">Customer, team, and queue health in one place.</p>
                </div>
                <a href="{{ url('/v/customer_list.php') }}" class="badge">Open Account Management</a>
            </div>
            <div class="stats">
                <a class="stat-link" href="{{ url('/v/customer_list.php') }}">
                    <article class="stat">
                        <span class="muted">Active Customers</span>
                        <strong>{{ $operationsSnapshot['active_customers'] }}</strong>
                        <div class="muted">Current active customer accounts.</div>
                        <span class="view-link">View: Customer List</span>
                    </article>
                </a>
                <a class="stat-link" href="{{ url('/v/block-customer_list.php') }}">
                    <article class="stat">
                        <span class="muted">Inactive Customers</span>
                        <strong>{{ $operationsSnapshot['blocked_customers'] }}</strong>
                        <div class="muted">Previously active customer accounts that are currently inactive or blocked.</div>
                        <span class="view-link">View: Inactive Customers</span>
                    </article>
                </a>
                <a class="stat-link" href="{{ url('/v/show-all-teams.php') }}">
                    <article class="stat">
                        <span class="muted">Team / Supervisors</span>
                        <strong>{{ $operationsSnapshot['team_accounts'] }} / {{ $operationsSnapshot['supervisors'] }}</strong>
                        <div class="muted">Active production accounts and supervisor accounts.</div>
                        <span class="view-link">View: Show All Team Accounts</span>
                    </article>
                </a>
                <a class="stat-link" href="{{ \App\Support\AdminOrderQueues::url('new-orders') }}">
                    <article class="stat">
                        <span class="muted">All Open Work</span>
                        <strong>{{ $operationsSnapshot['all_open_work'] }}</strong>
                        <div class="muted">Active items still in the working pipeline.</div>
                        <span class="view-link">View: All Orders</span>
                    </article>
                </a>
            </div>
        </div>
    </section>

    @if ($securityWatch['available'])
        <section class="card">
            <div class="card-body">
                <div class="section-head">
                    <div>
                        <h3>Security Watch</h3>
                        <p class="section-copy">A rolling {{ $securityWatch['window_hours'] }} hour view of suspicious activity, denied access, and risky upload attempts.</p>
                    </div>
                    <a href="{{ url('/v/security-events.php') }}" class="badge">Open Security Events</a>
                </div>

                <div class="stats">
                    <a class="stat-link" href="{{ url('/v/security-events.php?txtSeverity=warning') }}">
                        <article class="stat">
                            <span class="muted">Action Required</span>
                            <strong>{{ $securityWatch['actionable_events'] }}</strong>
                            <div class="muted" style="margin-top:8px;">Warnings or higher that deserve admin review.</div>
                        </article>
                    </a>
                    <a class="stat-link" href="{{ url('/v/security-events.php?txtEventType=auth.login') }}">
                        <article class="stat">
                            <span class="muted">Failed Logins</span>
                            <strong>{{ $securityWatch['failed_logins'] }}</strong>
                            <div class="muted" style="margin-top:8px;">Failed, blocked, rate-limited, or locked login attempts.</div>
                        </article>
                    </a>
                    <a class="stat-link" href="{{ url('/v/security-events.php?txtEventType=files.upload_rejected') }}">
                        <article class="stat">
                            <span class="muted">Upload Rejections</span>
                            <strong>{{ $securityWatch['upload_rejections'] }}</strong>
                            <div class="muted" style="margin-top:8px;">Rejected file uploads that may indicate risky or invalid input.</div>
                        </article>
                    </a>
                    <a class="stat-link" href="{{ url('/v/security-events.php?txtEventType=bot.turnstile_failed') }}">
                        <article class="stat">
                            <span class="muted">Bot Checks Failed</span>
                            <strong>{{ $securityWatch['turnstile_failures'] }}</strong>
                            <div class="muted" style="margin-top:8px;">Turnstile failures that may indicate scripted probing.</div>
                        </article>
                    </a>
                </div>


            </div>
        </section>
    @endif

    <section class="card">
        <div class="card-body">
            <div class="section-head">
                <div>
                    <h3>Workflow Focus</h3>
                    <p class="section-copy">What needs attention right now across the production pipeline.</p>
                </div>
            </div>
            <div class="stats">
                <a class="stat-link" href="{{ \App\Support\AdminOrderQueues::url('designer-completed') }}">
                    <article class="stat">
                        <span class="muted">Review Ready</span>
                        <strong>{{ $workflowFocus['review_ready'] }}</strong>
                        <div class="muted" style="margin-top:8px;">Designer completed orders and quotes awaiting admin review.</div>
                    </article>
                </a>
                <a class="stat-link" href="{{ \App\Support\AdminOrderQueues::url('approval-waiting') }}">
                    <article class="stat">
                        <span class="muted">Approval Waiting</span>
                        <strong>{{ $workflowFocus['approval_waiting'] }}</strong>
                        <div class="muted" style="margin-top:8px;">Orders reviewed by admin and waiting for customer approval.</div>
                    </article>
                </a>
                <a class="stat-link" href="{{ \App\Support\AdminOrderQueues::url('new-orders') }}">
                    <article class="stat">
                        <span class="muted">New Work</span>
                        <strong>{{ $workflowFocus['new_work'] }}</strong>
                        <div class="muted" style="margin-top:8px;">Fresh orders and quotes submitted by customers.</div>
                    </article>
                </a>
                <a class="stat-link" href="{{ \App\Support\AdminOrderQueues::url('designer-orders') }}">
                    <article class="stat">
                        <span class="muted">Assigned Work</span>
                        <strong>{{ $workflowFocus['assigned_work'] }}</strong>
                        <div class="muted" style="margin-top:8px;">Orders and quotes currently with designers in progress.</div>
                    </article>
                </a>
            </div>
        </div>
    </section>
@endsection
