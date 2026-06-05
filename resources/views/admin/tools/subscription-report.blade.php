@extends('layouts.admin')

@section('title', 'Subscription Report | 1Dollar Admin')
@section('page_heading', 'Subscription Report')
@section('page_subheading', 'Customers with active subscription plans and their monthly recurring amounts.')

@section('content')
    <section class="card">
        <div class="card-body">
            <div style="display:flex;justify-content:space-between;gap:12px;align-items:center;flex-wrap:wrap;margin-bottom:18px;">
                <div class="stats">
                    <article class="stat">
                        <span class="muted">Total MRR</span>
                        <strong style="font-size:1.35rem;color:#3c9e6a;">${{ number_format($totalMrr, 2) }}</strong>
                    </article>
                    <article class="stat">
                        <span class="muted">Subscribers</span>
                        <strong style="font-size:1.35rem;">{{ $subscribers->total() }}</strong>
                    </article>
                </div>
                <a class="button secondary" href="{{ url('/v/subscription-report.php') }}?{{ http_build_query(array_filter(['plan' => request('plan'), 'status' => request('status'), 'txtUserID' => request('txtUserID'), 'txtUserName' => request('txtUserName'), 'export' => 'csv'])) }}">Download CSV</a>
            </div>

            <form method="get" action="{{ url('/v/subscription-report.php') }}" class="toolbar">
                <div class="field">
                    <label for="txtUserID">User ID</label>
                    <input id="txtUserID" type="text" name="txtUserID" value="{{ request('txtUserID') }}">
                </div>
                <div class="field">
                    <label for="txtUserName">Customer Name / Email</label>
                    <input id="txtUserName" type="text" name="txtUserName" value="{{ request('txtUserName') }}">
                </div>
                <div class="field">
                    <label for="plan">Plan</label>
                    <select id="plan" name="plan">
                        <option value="">All Plans</option>
                        @foreach (['growth' => 'Growth ($90)', 'studio' => 'Studio ($170)', 'production' => 'Production ($320)', 'enterprise' => 'Enterprise ($700)', 'corporate' => 'Corporate ($1,200)'] as $key => $label)
                            <option value="{{ $key }}" @selected(request('plan') === $key)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="field">
                    <label for="status">Status</label>
                    <select id="status" name="status">
                        <option value="">All Statuses</option>
                        @foreach (['active' => 'Active', 'past_due' => 'Past Due', 'canceled' => 'Canceled', 'paused' => 'Paused'] as $key => $label)
                            <option value="{{ $key }}" @selected(request('status') === $key)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="field" style="min-width:auto;">
                    <label>&nbsp;</label>
                    <button type="submit">Filter</button>
                </div>
            </form>
        </div>
    </section>

    <section class="card">
        <div class="card-body">
            <div class="table-wrap">
                <table>
                    <thead>
                    <tr>
                        <th>Action</th>
                        <th>User ID</th>
                        <th>Customer</th>
                        <th>Email</th>
                        <th>Plan</th>
                        <th>Monthly Amount</th>
                        <th>Status</th>
                        <th>Renews At</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if ($subscribers->isEmpty())
                        <tr><td colspan="8" class="muted">No subscribers found for the current filters.</td></tr>
                    @else
                    @foreach ($subscribers as $user)
                        @php
                            $plan   = strtolower(trim((string) $user->subscription_plan));
                            $price  = $planPrices[$plan] ?? 0;
                            $status = $user->subscription_status ?? 'active';
                            $statusColor = match ($status) {
                                'active'   => '#3c9e6a',
                                'past_due' => '#b42318',
                                'canceled' => '#888',
                                default    => '#b26a2a',
                            };
                        @endphp
                        <tr>
                            <td><a class="badge" href="{{ url('/v/customer-detail.php?uid='.$user->user_id) }}">View</a></td>
                            <td>{{ $user->user_id }}</td>
                            <td>{{ $user->display_name }}</td>
                            <td>{{ $user->user_email ?: '-' }}</td>
                            <td>{{ ucfirst($plan) }}</td>
                            <td>${{ number_format((float) $price, 2) }}</td>
                            <td><span style="color:{{ $statusColor }};font-weight:600;">{{ ucfirst(str_replace('_', ' ', $status)) }}</span></td>
                            <td>{{ $user->subscription_renews_at ?? '-' }}</td>
                        </tr>
                    @endforeach
                    @endif
                    </tbody>
                </table>
            </div>

            @if ($subscribers->hasPages())
                <div style="margin-top:18px;">{{ $subscribers->links() }}</div>
            @endif
        </div>
    </section>
@endsection
