@extends('layouts.team')

@section('title', 'My Job History | 1Dollar Team Portal')
@section('page_heading', 'My Job History')
@section('page_subheading', $isFreelance ? 'Your completed jobs, earnings, fines, and payment status.' : 'Your completed jobs and fine record.')

@section('content')
    <section class="card">
        <div class="card-body">
            <form method="get" action="{{ url('/team/my-jobs') }}" class="toolbar" style="align-items:flex-end;">
                <div class="field">
                    <label for="month">Month</label>
                    <select id="month" name="month">
                        <option value="">All Time</option>
                        @foreach ($months as $key => $label)
                            <option value="{{ $key }}" @selected($selectedMonth === $key)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="field" style="min-width:auto;">
                    <label>&nbsp;</label>
                    <button type="submit">Filter</button>
                </div>
                <div class="field" style="min-width:auto;">
                    <label>&nbsp;</label>
                    <a class="badge" href="{{ url('/team/my-jobs?'.http_build_query(array_filter(['month' => $selectedMonth, 'export' => 'csv']))) }}">Download CSV</a>
                </div>
            </form>
        </div>
    </section>

    @php
        $totalJobs = $orders->count();
        $totalStitches = 0;
        $totalGrossPkr = 0;
        $totalFinePkr = 0;
        $totalNetPkr = 0;
        $totalFineRs = 0;
        foreach ($orders as $order) {
            if (is_numeric($order->stitches)) $totalStitches += (float) $order->stitches;
            $fine = $finesByOrder[$order->order_id] ?? null;
            $fineAmt = $fine ? (float) $fine->amount : 0;
            if ($isFreelance) {
                $quote = $quotesByOrder[$order->order_id] ?? null;
                $gross = $quote ? (float) $quote->quoted_price : 0;
                $totalGrossPkr += $gross;
                $totalFinePkr += $fineAmt;
                $totalNetPkr += max(0, $gross - $fineAmt);
            } else {
                $totalFineRs += $fineAmt;
            }
        }
    @endphp

    @if ($isFreelance)
    <div class="stats" style="margin-bottom:18px;">
        <article class="stat"><span class="muted">Total Jobs</span><strong style="font-size:1.15rem;">{{ $totalJobs }}</strong></article>
        <article class="stat"><span class="muted">Gross (PKR)</span><strong style="font-size:1.15rem;">{{ number_format($totalGrossPkr, 2) }}</strong></article>
        <article class="stat"><span class="muted">Fines (PKR)</span><strong style="font-size:1.15rem;color:#b42318;">{{ $totalFinePkr > 0 ? number_format($totalFinePkr, 2) : '—' }}</strong></article>
        <article class="stat"><span class="muted">Net (PKR)</span><strong style="font-size:1.15rem;color:#1e6a57;">{{ number_format($totalNetPkr, 2) }}</strong></article>
    </div>
    @else
    <div class="stats" style="margin-bottom:18px;">
        <article class="stat"><span class="muted">Total Jobs</span><strong style="font-size:1.15rem;">{{ $totalJobs }}</strong></article>
        <article class="stat"><span class="muted">Total Stitches</span><strong style="font-size:1.15rem;">{{ number_format($totalStitches) }}</strong></article>
        <article class="stat"><span class="muted">Total Fines (Rs.)</span><strong style="font-size:1.15rem;color:#b42318;">{{ $totalFineRs > 0 ? number_format($totalFineRs, 2) : '—' }}</strong></article>
    </div>
    @endif

    <section class="card">
        <div class="card-body">
            <div class="table-wrap">
                <table>
                    <thead>
                    <tr>
                        <th>Date</th>
                        <th>Order #</th>
                        <th>Design Name</th>
                        <th>{{ $isFreelance ? 'Stitches / Hours' : 'Stitches / Hours' }}</th>
                        @if ($isFreelance)
                            <th>Price (PKR)</th>
                            <th>Fine (PKR)</th>
                            <th>Net (PKR)</th>
                            <th>Payment</th>
                        @else
                            <th>Fine (Rs.)</th>
                        @endif
                        <th>Status</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if ($orders->isEmpty())
                        <tr><td colspan="{{ $isFreelance ? 9 : 6 }}" class="muted">No completed jobs found{{ $selectedMonth ? ' for the selected month' : '' }}.</td></tr>
                    @else
                    @foreach ($orders as $order)
                        @php
                            $fine = $finesByOrder[$order->order_id] ?? null;
                            $fineAmt = $fine ? (float) $fine->amount : 0;
                            $statusLabel = $order->status === 'done' ? 'Completed' : 'Pending Review';
                            $statusStyle = $order->status === 'done' ? 'background:#e6f4ee;color:#1e6a57;' : 'background:#fef3e2;color:#92500a;';
                        @endphp
                        <tr>
                            <td>{{ $order->vender_complete_date ?: '—' }}</td>
                            <td>{{ $order->order_id }}</td>
                            <td>{{ $order->design_name ?: '—' }}</td>
                            <td>{{ $order->stitches ?: '—' }}</td>
                            @if ($isFreelance)
                                @php
                                    $quote = $quotesByOrder[$order->order_id] ?? null;
                                    $gross = $quote ? (float) $quote->quoted_price : 0;
                                    $net = max(0, $gross - $fineAmt);
                                @endphp
                                <td>{{ $gross > 0 ? number_format($gross, 2) : '—' }}</td>
                                <td>
                                    @if ($fineAmt > 0)
                                        <span style="color:#b42318;font-weight:600;" title="{{ $fine->reason }}">{{ number_format($fineAmt, 2) }}</span>
                                    @else
                                        —
                                    @endif
                                </td>
                                <td><strong>{{ number_format($net, 2) }}</strong></td>
                                <td>
                                    @if ($order->freelance_payment_request_id)
                                        <span class="badge" style="background:#1e6a57;color:#fff;">Paid</span>
                                    @else
                                        <span class="badge badge-muted">Unpaid</span>
                                    @endif
                                </td>
                            @else
                                <td>
                                    @if ($fineAmt > 0)
                                        <span style="color:#b42318;font-weight:600;" title="{{ $fine->reason }}">Rs. {{ number_format($fineAmt, 2) }}</span>
                                    @else
                                        —
                                    @endif
                                </td>
                            @endif
                            <td><span class="badge" style="{{ $statusStyle }}">{{ $statusLabel }}</span></td>
                        </tr>
                    @endforeach
                    @endif
                    </tbody>
                </table>
            </div>
        </div>
    </section>
@endsection
