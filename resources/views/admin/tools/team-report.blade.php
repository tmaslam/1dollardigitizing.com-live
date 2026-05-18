@extends('layouts.admin')

@php
    $currentColumn = request('column_name', 'completion_date');
    $currentDirection = strtolower(request('sort', 'desc'));
    $nextDirection = fn ($column) => $currentColumn === $column && $currentDirection === 'asc' ? 'desc' : 'asc';
@endphp

@section('title', 'Team Report | 1Dollar Admin')
@section('page_heading', 'Team Report')
@section('page_subheading', 'Review completed work by team member and month. Fine and earnings columns shown for freelance rows.')

@section('content')
    <section class="card">
        <div class="card-body">
            <form method="get" action="{{ url('/v/monthly-reports.php') }}" class="toolbar">
                <div class="field">
                    <label for="group">Group</label>
                    <select id="group" name="group">
                        <option value="">All Groups</option>
                        <option value="inhouse" @selected($groupFilter === 'inhouse')>In-House</option>
                        <option value="freelance" @selected($groupFilter === 'freelance')>Freelance</option>
                    </select>
                </div>
                <div class="field">
                    <label for="team">Select Member</label>
                    <select id="team" name="team">
                        <option value="">All Members</option>
                        @foreach ($teams as $team)
                            <option value="{{ $team->user_id }}" @selected((string) request('team') === (string) $team->user_id)>{{ $team->user_name }}{{ $team->is_supervisor ? ' (Supervisor)' : ($team->isFreelance() ? ' (Freelance)' : ' (In-House)') }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="field">
                    <label for="month">Select Month</label>
                    <select id="month" name="month">
                        <option value="">All / This Year</option>
                        @foreach ($months as $month)
                            @if ($month && $month !== '0000-00')
                                <option value="{{ $month }}" @selected(request('month') === $month)>{{ $month }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
                <div class="field" style="min-width:auto;">
                    <label>&nbsp;</label>
                    <button type="submit">Search</button>
                </div>
            </form>
            @include('shared.admin-report-export', [
                'copy' => 'Download the current team report.',
                'label' => 'Download Report',
                'show' => $orders->count() > 0,
                'marginTop' => '14px',
                'marginBottom' => '0',
            ])
        </div>
    </section>

    <section class="card">
        <div class="card-body">
            <div style="display:flex;justify-content:space-between;gap:16px;align-items:center;flex-wrap:wrap;">
                <div>
                    <h3 style="margin:0 0 6px;font-size:1.15rem;">Completed Orders</h3>
                    <p class="muted" style="margin:0;">{{ $summary['total_orders'] }} records match the current filters.</p>
                </div>
                <span class="badge">team report</span>
            </div>

            <div class="stats" style="margin-top:18px;">
                <article class="stat"><span class="muted">Completed Designs</span><strong>{{ $summary['total_orders'] }}</strong></article>
                <article class="stat"><span class="muted">Supervisor Checked</span><strong>{{ $summary['supervisor_checked'] }}</strong></article>
                <article class="stat"><span class="muted">Total Amount</span><strong>{{ number_format((float) $summary['total_amount'], 2) }}</strong></article>
                @if ($summary['total_net_pkr'] > 0)
                    <article class="stat"><span class="muted">Freelance Net (PKR)</span><strong style="color:#1e6a57;">{{ number_format((float) $summary['total_net_pkr'], 2) }}</strong></article>
                @endif
            </div>

            <div class="table-wrap" style="margin-top:18px;">
                <table>
                    <thead>
                    <tr>
                        <th><a href="{{ request()->fullUrlWithQuery(['column_name' => 'order_id', 'sort' => $nextDirection('order_id')]) }}">Order ID</a></th>
                        <th><a href="{{ request()->fullUrlWithQuery(['column_name' => 'order_type', 'sort' => $nextDirection('order_type')]) }}">Design Type</a></th>
                        <th><a href="{{ request()->fullUrlWithQuery(['column_name' => 'design_name', 'sort' => $nextDirection('design_name')]) }}">Design Name</a></th>
                        <th>Assigned To</th>
                        <th>Group</th>
                        <th>Sup. Checked</th>
                        <th><a href="{{ request()->fullUrlWithQuery(['column_name' => 'stitches', 'sort' => $nextDirection('stitches')]) }}">Stitches</a></th>
                        <th><a href="{{ request()->fullUrlWithQuery(['column_name' => 'total_amount', 'sort' => $nextDirection('total_amount')]) }}">Total Amount</a></th>
                        <th>Price (PKR)</th>
                        <th>Fine (PKR/Rs.)</th>
                        <th>Net (PKR)</th>
                        <th><a href="{{ request()->fullUrlWithQuery(['column_name' => 'completion_date', 'sort' => $nextDirection('completion_date')]) }}">Completion Date</a></th>
                    </tr>
                    </thead>
                    <tbody>
                    @if (collect($orders)->isEmpty())
                        <tr><td colspan="12" class="muted">No team report rows found.</td></tr>
                    @else
                    @foreach ($orders as $order)
                        @php $isF = (bool) $order->is_freelance_order; @endphp
                        <tr class="{{ $order->work_type_label === 'Vector' ? 'row-vector' : 'row-digitizing' }}">
                            <td>{{ $order->order_id }}</td>
                            <td>{{ $order->work_type_label }}</td>
                            <td>{{ $order->design_name ?: '-' }}</td>
                            <td>{{ $order->assignee?->user_name ?: '-' }}</td>
                            <td>
                                @if ($isF)
                                    <span class="badge" style="background:#fef3e2;color:#92500a;">Freelance</span>
                                @else
                                    <span class="badge badge-muted">In-House</span>
                                @endif
                            </td>
                            <td>{{ $order->supervisor_checked_flag ? 'Yes' : 'No' }}</td>
                            <td>{{ $order->stitches ?: '-' }}</td>
                            <td>{{ is_numeric($order->total_amount) ? number_format((float) $order->total_amount, 2) : ($order->total_amount ?: '0.00') }}</td>
                            <td>
                                @if ($isF && (float) $order->freelance_gross_pkr > 0)
                                    {{ number_format((float) $order->freelance_gross_pkr, 2) }}
                                @else
                                    —
                                @endif
                            </td>
                            <td>
                                @if ($isF && (float) $order->freelance_fine_pkr > 0)
                                    <span style="color:#b42318;font-weight:600;">{{ number_format((float) $order->freelance_fine_pkr, 2) }}</span>
                                @elseif (! $isF && (float) $order->inhouse_fine_rs > 0)
                                    <span style="color:#b42318;font-weight:600;">Rs. {{ number_format((float) $order->inhouse_fine_rs, 2) }}</span>
                                @else
                                    —
                                @endif
                            </td>
                            <td>
                                @if ($isF)
                                    <strong>{{ number_format((float) $order->freelance_net_pkr, 2) }}</strong>
                                @else
                                    —
                                @endif
                            </td>
                            <td>{{ $order->completion_date ?: '-' }}</td>
                        </tr>
                    @endforeach
                    @endif
                    </tbody>
                </table>
            </div>
        </div>
    </section>
@endsection
