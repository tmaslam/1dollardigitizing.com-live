@extends('layouts.team')

@section('title', 'Assignment Monitor | 1Dollar Team Portal')
@section('page_heading', 'Assignment Monitor')
@section('page_subheading', 'Monitor and manage in-house and freelance job assignments.')

@section('content')
    @if (session('success'))
        <div class="alert success" style="margin-bottom:16px;">{{ session('success') }}</div>
    @endif
    @if ($errors->any())
        <div class="alert" style="margin-bottom:16px;">{{ $errors->first() }}</div>
    @endif

    <section class="card">
        <div class="card-body">
            <div class="stats">
                <a class="stat" href="{{ url('/team/supervisor/assignments?group=inhouse') }}" @if ($groupFilter === 'inhouse') style="border-color:rgba(30,106,87,0.36);background:rgba(223,241,234,0.72);" @endif>
                    <span class="muted">In-House Jobs</span>
                    <strong>{{ $orders->where('assigned_group', 'inhouse')->count() }}</strong>
                </a>
                <a class="stat" href="{{ url('/team/supervisor/assignments?group=freelance') }}" @if ($groupFilter === 'freelance') style="border-color:rgba(30,106,87,0.36);background:rgba(223,241,234,0.72);" @endif>
                    <span class="muted">Freelance Jobs</span>
                    <strong>{{ $orders->where('assigned_group', 'freelance')->count() }}</strong>
                </a>
                <a class="stat" href="{{ url('/team/supervisor/assignments') }}" @if ($groupFilter === '') style="border-color:rgba(30,106,87,0.36);background:rgba(223,241,234,0.72);" @endif>
                    <span class="muted">All Jobs</span>
                    <strong>{{ $orders->count() }}</strong>
                </a>
            </div>
        </div>
    </section>

    <section class="card">
        <div class="card-body">
            <div style="display:flex;justify-content:space-between;gap:12px;align-items:center;flex-wrap:wrap;margin-bottom:16px;">
                <div>
                    <h3 style="margin:0 0 6px;">
                        @if ($groupFilter === 'inhouse')
                            In-House Assignments
                        @elseif ($groupFilter === 'freelance')
                            Freelance Assignments
                        @else
                            All Active Assignments
                        @endif
                    </h3>
                    <p class="muted" style="margin:0;">Underprocess jobs currently assigned or in group pools.</p>
                </div>
            </div>

            <div class="table-wrap">
                <table>
                    <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Group</th>
                        <th>Design Name</th>
                        <th>Work Type</th>
                        <th>Assigned To</th>
                        <th>Schedule</th>
                        <th>Quotes</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if ($orders->isEmpty())
                        <tr><td colspan="8" class="muted">No active assignments found.</td></tr>
                    @else
                    @foreach ($orders as $order)
                        @php
                            $scheduleTone = (string) ($order->turnaround_status_tone ?? '');
                            $scheduleBadgeStyle = match ($scheduleTone) {
                                'danger'  => 'background:rgba(180,35,24,0.12);color:#b42318;border-color:rgba(180,35,24,0.18);',
                                'warning' => 'background:rgba(197,107,34,0.12);color:#9a5a16;border-color:rgba(197,107,34,0.18);',
                                default   => 'background:rgba(34,139,94,0.12);color:#1f7a53;border-color:rgba(34,139,94,0.18);',
                            };
                            $isFreelance = $order->assigned_group === 'freelance';
                            $isPool = (int) $order->assign_to === 0 || $order->assign_to === null || $order->assign_to === '';
                        @endphp
                        <tr>
                            <td><a href="{{ $detailUrl($order) }}" class="badge">{{ $order->order_id }}</a></td>
                            <td>
                                @if ($isFreelance)
                                    <span class="badge" style="background:rgba(234,132,36,0.12);color:#a35a0d;border-color:rgba(234,132,36,0.22);">Freelance</span>
                                @else
                                    <span class="badge" style="background:rgba(34,139,94,0.12);color:#1f7a53;border-color:rgba(34,139,94,0.24);">In-House</span>
                                @endif
                            </td>
                            <td>{{ $order->design_name ?: '—' }}</td>
                            <td>{{ $order->work_type_label }}</td>
                            <td>{{ $order->assignee_name ?: ($isPool ? 'Pool' : '—') }}</td>
                            <td>
                                <span class="badge" style="{{ $scheduleBadgeStyle }}">{{ $order->turnaround_status_label ?: '—' }}</span>
                            </td>
                            <td>
                                @if ($isFreelance && $isPool)
                                    <span class="badge" style="background:rgba(22,159,230,0.12);color:#0d6ea3;border-color:rgba(22,159,230,0.22);">{{ $order->pending_quote_count }} Quote{{ $order->pending_quote_count === 1 ? '' : 's' }}</span>
                                @elseif ($isFreelance && $order->accepted_quote)
                                    <span class="muted">PKR {{ number_format((float) $order->accepted_quote->quoted_price, 2) }}</span>
                                @else
                                    <span class="muted">—</span>
                                @endif
                            </td>
                            <td>
                                <div class="action-row" style="flex-wrap:wrap;gap:8px;">
                                    @php
                                        $page = in_array($order->order_type, ['quote', 'digitzing', 'q-vector', 'qcolor'], true) ? 'quote' : (in_array($order->order_type, ['vector', 'color'], true) ? 'vector' : 'order');
                                    @endphp
                                    <a class="badge" href="{{ url('/team/assign-order.php?design_id='.$order->order_id.'&page='.$page) }}">Assign</a>
                                    @if (! $isPool)
                                        <form method="post" action="{{ url('/team/supervisor/orders/'.$order->order_id.'/pull-back') }}" style="display:inline;" onsubmit="return confirm('Pull back Order #{{ $order->order_id }} to the pool?');">
                                            @csrf
                                            <button type="submit" class="badge" style="background:rgba(180,35,24,0.12);color:#b42318;border-color:rgba(180,35,24,0.18);">Pull Back</button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    @endif
                    </tbody>
                </table>
            </div>
        </div>
    </section>
@endsection
