@extends('layouts.customer')

@section('title', 'Credit Activity - '.$siteContext->displayLabel())
@section('hero_title', 'Credit Activity')
@section('hero_text', 'A full record of every credit added to your balance and every payment deducted against an order.')

@section('content')
    <section class="content-card">
        <div class="section-head">
            <div>
                <h3>Available Balance</h3>
                <p>Your current usable credit balance.</p>
            </div>
            <a class="button secondary" href="{{ url('/view-billing.php') }}">← Back to Billing</a>
        </div>
        <div class="portal-stat-grid">
            <div class="portal-stat">
                <span>Available Balance</span>
                <strong style="color:{{ $depositBalance > 0 ? '#3c9e6a' : '#526071' }};">{{ number_format($depositBalance, 2) }} cr</strong>
            </div>
        </div>
    </section>

    <section class="content-card">
        <div class="section-head">
            <div>
                <h3>Transaction History</h3>
                <p>Credits received and payments applied, newest first.</p>
            </div>
        </div>

        @if ($entries->isEmpty())
            <div class="empty-state">No credit activity recorded yet.</div>
        @else
            <div class="table-wrap responsive-stack">
                <table class="responsive-table">
                    <thead>
                        <tr>
                            <th>Date &amp; Time</th>
                            <th>Type</th>
                            <th>Amount</th>
                            <th>Reference / Order</th>
                            <th>Note</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($entries as $entry)
                            @php
                                $amt      = (float) $entry->amount;
                                $isCredit = $amt > 0;
                                $typeLabel = match($entry->entry_type) {
                                    'payment'     => 'Credit Added',
                                    'overpayment' => 'Credit Added',
                                    'applied'     => 'Order Payment',
                                    'adjustment'  => 'Balance Adjustment',
                                    default       => ucfirst((string) $entry->entry_type),
                                };
                            @endphp
                            <tr>
                                <td data-label="Date &amp; Time" style="white-space:nowrap;font-size:0.84rem;">
                                    {{ $entry->date_added ?: '—' }}
                                </td>
                                <td data-label="Type">
                                    <span class="status {{ $isCredit ? 'active' : 'warning' }}">{{ $typeLabel }}</span>
                                </td>
                                <td data-label="Amount" style="font-weight:700;color:{{ $isCredit ? '#2a7d4f' : '#9d2d17' }};">
                                    {{ $isCredit ? '+' : '-' }}${{ number_format(abs($amt), 2) }}
                                </td>
                                <td data-label="Reference / Order" style="font-size:0.84rem;">
                                    @if ($entry->order)
                                        <a href="{{ url('/view-order-detail.php?order_id='.$entry->order->order_id) }}">
                                            Order #{{ $entry->order->order_id }}{{ $entry->order->design_name ? ' — '.$entry->order->design_name : '' }}
                                        </a>
                                    @elseif ($entry->reference_no)
                                        <span style="color:#526071;word-break:break-all;">{{ $entry->reference_no }}</span>
                                    @else
                                        —
                                    @endif
                                </td>
                                <td data-label="Note" style="font-size:0.84rem;color:#526071;">
                                    {{ $entry->notes ?: '—' }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="pagination">
                {{ $entries->links() }}
            </div>
        @endif
    </section>
@endsection
