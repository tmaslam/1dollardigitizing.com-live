@extends('layouts.admin')

@section('title', 'Customer Credit Inventory | 1Dollar Admin')
@section('page_heading', 'Customer Credit Inventory')
@section('page_subheading', 'Track active customers who currently have available credit that can be applied to future invoices.')

@section('content')
    @unless ($hasCustomerBalanceTable)
        <div class="alert">The `topup` column was not found in the `users` table, so customer balance tracking is unavailable here.</div>
    @else
        <section class="card">
            <div class="card-body">
                <div style="display:flex;justify-content:space-between;gap:12px;align-items:center;flex-wrap:wrap;margin-bottom:18px;">
                    <div class="muted">This page lists only customers with positive credit balance (Advance Deposit) that can be used on future invoices.</div>
                    <div style="display:flex;gap:8px;align-items:center;flex-wrap:wrap;">
                        <a class="button secondary" href="{{ url('/v/customer-payment-inventory.php') }}?{{ http_build_query(array_filter(['txtUserID' => request('txtUserID'), 'txtUserName' => request('txtUserName'), 'export' => 'csv'])) }}">Download Summary</a>
                        <a class="badge" href="{{ url('/v/pay-now.php') }}">New Payment</a>
                    </div>
                </div>
                <div class="stats" style="margin-bottom:18px;">
                    <article class="stat"><span class="muted">Total Available Balance</span><strong style="font-size:1.35rem;color:#3c9e6a;">${{ number_format($totalBalance, 2) }}</strong></article>
                    <article class="stat"><span class="muted">Total Current Due</span><strong style="font-size:1.35rem;color:#b26a2a;">${{ number_format($totalDue, 2) }}</strong></article>
                    <article class="stat"><span class="muted">Net Position</span><strong style="font-size:1.35rem;color:{{ $totalBalance >= $totalDue ? '#3c9e6a' : '#b42318' }};">${{ number_format($totalBalance - $totalDue, 2) }}</strong></article>
                </div>
                <form method="get" action="{{ url('/v/customer-payment-inventory.php') }}" class="toolbar">
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
                            <th>Available Balance</th>
                            <th>Current Due</th>
                        </tr>
                        </thead>
                        <tbody>
                        @if (collect($balances)->isEmpty())
                            <tr><td colspan="6" class="muted">No customer balances were found for the current filters.</td></tr>
                        @else
                        @foreach ($balances as $balance)
                            @php
                                $dueRow = $dueAmounts[$balance->user_id] ?? null;
                            @endphp
                            <tr>
                                <td style="white-space:nowrap;">
                                    <a class="badge" href="{{ url('/v/payment-due-detail.php?uid='.$balance->user_id) }}">Manage</a>
                                    <a class="badge" href="{{ url('/v/pay-now.php?user_id='.$balance->user_id) }}">Add Payment</a>
                                </td>
                                <td>{{ $balance->user_id }}</td>
                                <td>{{ $balance->customer->display_name }}</td>
                                <td>{{ $balance->customer->user_email ?: '-' }}</td>
                                <td>{{ number_format((float) $balance->balance_total, 2) }}</td>
                                <td>{{ number_format((float) ($dueRow->due_total ?? 0), 2) }}</td>
                            </tr>
                        @endforeach
                        @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    @endunless
@endsection
