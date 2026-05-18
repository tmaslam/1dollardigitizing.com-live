@extends('layouts.customer')

@section('title', 'My Billing - '.$siteContext->displayLabel())
@section('hero_title', 'Billing')
@section('hero_text', 'See what is due, use available balance, and complete payments in one place.')

@section('content')
    @if (session('success'))
        <section class="content-card">
            <div class="alert alert-success">{{ session('success') }}</div>
        </section>
    @endif

    @if ($errors->has('credit'))
        <section class="content-card">
            <div class="alert alert-error">{{ $errors->first('credit') }}</div>
        </section>
    @endif

    @if ($errors->has('payment'))
        <section class="content-card">
            <div class="alert alert-error">{{ $errors->first('payment') }}</div>
        </section>
    @endif

    <section class="content-card">
        <div class="section-head">
            <div>
                <h3>Billing Overview</h3>
                <p>Your current outstanding balance and available credits at a glance.</p>
            </div>
        </div>
        <div class="portal-stat-grid">
            <div class="portal-stat">
                <span>Outstanding Total</span>
                <strong>${{ number_format($outstandingTotal, 2) }}</strong>
            </div>
            <div class="portal-stat">
                <span>Available Credits</span>
                <strong style="color:{{ $totalUsableBalance >= $outstandingTotal ? '#3c9e6a' : '#e07b20' }}">
                    {{ number_format($totalUsableBalance, 2) }} credits
                </strong>
            </div>
            <div class="portal-stat">
                <span>Open Invoices</span>
                <strong>{{ $billingSummary['invoice_count'] }}</strong>
            </div>
            @if ($totalUsableBalance >= $outstandingTotal && $outstandingTotal > 0)
                <div class="portal-stat" style="color:#3c9e6a; font-size:0.84rem; display:flex; align-items:center;">
                    <span>Your balance covers all outstanding invoices.</span>
                </div>
            @endif
        </div>
    </section>

    <section class="content-card">
        <div class="section-head">
            <div>
                <h3>Outstanding Invoices</h3>
                <p>Pay with your credit balance instantly, or use a payment provider. Largest invoice: {{ number_format($billingSummary['largest_invoice'], 2) }} credits.</p>
            </div>
            @if ($billingRows->count())
                <div style="display:flex; gap:10px; flex-wrap:wrap; align-items:center;">
                    @if ($totalUsableBalance >= $outstandingTotal && $outstandingTotal > 0)
                        <form method="post" action="{{ url('/view-billing.php/pay-all-credit') }}">
                            @csrf
                            <button type="submit" class="button">Pay All with Credits</button>
                        </form>
                    @endif
                    <form method="post" action="{{ url('/view-billing.php/pay-all') }}">
                        @csrf
                        @include('customer.payments.provider-buttons', [
                            'paymentProviders' => $paymentProviders,
                            'buttonPrefix' => 'Pay All With',
                        ])
                    </form>
                </div>
            @endif
        </div>

        @if ($billingRows->count())
            <div class="table-wrap responsive-stack">
                <table class="responsive-table">
                    <thead>
                    <tr>
                        <th>Invoice</th>
                        <th>Order</th>
                        <th>Approved</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($billingRows as $billing)
                        @php
                            $rowAmount = (float) preg_replace('/[^0-9.\-]/', '', (string) $billing->amount);
                            $canPayWithCredit = $totalUsableBalance >= $rowAmount - 0.001;
                        @endphp
                        <tr>
                            <td data-label="Invoice">INV-{{ $billing->bill_id }}</td>
                            <td data-label="Order">
                                @if ($billing->order)
                                    <a href="{{ url('/view-order-detail.php?order_id=' . $billing->order->order_id . '&origin=billing') }}">Order #{{ $billing->order->order_id }} - {{ $billing->order->design_name ?: 'Design' }}</a>
                                @else
                                    -
                                @endif
                            </td>
                            <td data-label="Approved">{{ $billing->approve_date ?: '-' }}</td>
                            <td data-label="Amount">${{ number_format($rowAmount, 2) }}</td>
                            <td data-label="Status"><span class="status warning">Payment Due</span></td>
                            <td data-label="Action">
                                <div style="display:flex; gap:8px; flex-wrap:wrap; align-items:center;">
                                    @if ($canPayWithCredit)
                                        <form method="post" action="{{ url('/view-billing.php/' . $billing->bill_id . '/pay-credit') }}">
                                            @csrf
                                            <button type="submit" class="button">Pay with Credits</button>
                                        </form>
                                    @else
                                        <button type="button" class="button secondary" disabled style="opacity:0.45;cursor:not-allowed;" title="Insufficient credits ({{ number_format($totalUsableBalance, 2) }} available, {{ number_format($rowAmount, 2) }} required)">Pay with Credits</button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>

            <div class="pagination">
                {{ $billingRows->links() }}
            </div>
        @else
            <div class="empty-state">No unpaid invoices are currently open.</div>
        @endif
    </section>

@endsection
