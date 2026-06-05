@extends('layouts.admin')

@section('title', 'Settled Credits Report | 1Dollar Admin')
@section('page_heading', 'Settled Credits Report')
@section('page_subheading', 'All customers with fully paid invoices — grouped by customer with totals and last settlement date.')

@section('content')
    <section class="card">
        <div class="card-body">
            <div style="display:flex;justify-content:space-between;gap:12px;align-items:center;flex-wrap:wrap;margin-bottom:18px;">
                <div class="stats">
                    <article class="stat">
                        <span class="muted">Total Settled</span>
                        <strong style="font-size:1.35rem;color:#3c9e6a;">${{ number_format($grandTotal, 2) }}</strong>
                    </article>
                    <article class="stat">
                        <span class="muted">Total Paid Orders</span>
                        <strong style="font-size:1.35rem;">{{ number_format($grandOrderCount) }}</strong>
                    </article>
                    <article class="stat">
                        <span class="muted">Customers</span>
                        <strong style="font-size:1.35rem;">{{ $groups->total() }}</strong>
                    </article>
                </div>
                <a class="button secondary" href="{{ url('/v/settled-credits-report.php') }}?{{ http_build_query(array_filter(['txtUserID' => request('txtUserID'), 'txtUserName' => request('txtUserName'), 'export' => 'csv'])) }}">Download CSV</a>
            </div>

            <form method="get" action="{{ url('/v/settled-credits-report.php') }}" class="toolbar">
                <div class="field">
                    <label for="txtUserID">User ID</label>
                    <input id="txtUserID" type="text" name="txtUserID" value="{{ request('txtUserID') }}">
                </div>
                <div class="field">
                    <label for="txtUserName">Customer Name / Email</label>
                    <input id="txtUserName" type="text" name="txtUserName" value="{{ request('txtUserName') }}">
                </div>
                <div class="field" style="min-width:auto;">
                    <label>&nbsp;</label>
                    <button type="submit">Filter</button>
                </div>
                @if (request('txtUserID') || request('txtUserName'))
                    <div class="field" style="min-width:auto;">
                        <label>&nbsp;</label>
                        <a class="button secondary" href="{{ url('/v/settled-credits-report.php') }}">Clear</a>
                    </div>
                @endif
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
                        <th style="text-align:right;">Paid Orders</th>
                        <th style="text-align:right;">Total Settled</th>
                        <th>Last Settlement</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if ($groups->isEmpty())
                        <tr><td colspan="7" class="muted">No settled billing records found for the current filters.</td></tr>
                    @else
                    @foreach ($groups as $group)
                        @php
                            $customer = $customers->get($group->user_id);
                        @endphp
                        <tr>
                            <td style="white-space:nowrap;">
                                <a class="badge" href="{{ url('/v/customer-detail.php?uid='.$group->user_id) }}">Profile</a>
                                <a class="badge" href="{{ url('/v/payment-recieved-detail.php?uid='.$group->user_id) }}">Invoices</a>
                            </td>
                            <td>{{ $group->user_id }}</td>
                            <td>{{ $customer?->display_name ?: '—' }}</td>
                            <td>{{ $customer?->user_email ?: '—' }}</td>
                            <td style="text-align:right;font-weight:600;">{{ number_format((int) $group->order_count) }}</td>
                            <td style="text-align:right;font-weight:600;color:#3c9e6a;">${{ number_format((float) $group->total_amount, 2) }}</td>
                            <td>{{ $group->last_settled_at ? \Carbon\Carbon::parse($group->last_settled_at)->format('M j, Y') : '—' }}</td>
                        </tr>
                    @endforeach
                    <tr style="border-top:2px solid rgba(24,34,45,0.15);font-weight:700;">
                        <td colspan="4" style="text-align:right;padding-right:12px;">Page Total</td>
                        <td style="text-align:right;">{{ number_format($groups->getCollection()->sum('order_count')) }}</td>
                        <td style="text-align:right;color:#3c9e6a;">${{ number_format($groups->getCollection()->sum(fn($g) => (float) $g->total_amount), 2) }}</td>
                        <td></td>
                    </tr>
                    @if ($groups->total() > $groups->count())
                    <tr style="background:rgba(22,159,230,0.04);">
                        <td colspan="4" style="text-align:right;padding-right:12px;color:#526071;">Grand Total (all pages)</td>
                        <td style="text-align:right;color:#526071;">{{ number_format($grandOrderCount) }}</td>
                        <td style="text-align:right;color:#526071;">${{ number_format($grandTotal, 2) }}</td>
                        <td></td>
                    </tr>
                    @endif
                    @endif
                    </tbody>
                </table>
            </div>

            @if ($groups->hasPages())
                <div style="margin-top:18px;">{{ $groups->links() }}</div>
            @endif
        </div>
    </section>
@endsection
