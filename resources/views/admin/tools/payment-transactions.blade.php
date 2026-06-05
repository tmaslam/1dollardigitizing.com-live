@extends('layouts.admin')

@section('title', 'Payment Transactions | 1Dollar Admin')
@section('page_heading', 'Payment Transactions')
@section('page_subheading', 'All successful payments received from day 1 to date.')

@section('content')
    <section class="card">
        <div class="card-body">
            <div class="stats" style="margin-bottom:18px;">
                <article class="stat">
                    <span class="muted">Total Received (All Time)</span>
                    <strong style="font-size:1.35rem;color:#3c9e6a;">${{ number_format($totalReceivedAllTime, 2) }}</strong>
                </article>
                <article class="stat">
                    <span class="muted">Transactions on This Page</span>
                    <strong style="font-size:1.15rem;">{{ $transactions->count() }}</strong>
                </article>
                <article class="stat">
                    <span class="muted">Total Transactions</span>
                    <strong style="font-size:1.15rem;">{{ $transactions->total() }}</strong>
                </article>
            </div>

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
                        @if ($transactions->isEmpty())
                            <tr><td colspan="6" class="muted">No payment transactions found.</td></tr>
                        @else
                            @foreach ($transactions as $tx)
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
                        @endif
                    </tbody>
                </table>
            </div>

            @if ($transactions->hasPages())
                <div style="margin-top:18px;">{{ $transactions->links() }}</div>
            @endif
        </div>
    </section>
@endsection
