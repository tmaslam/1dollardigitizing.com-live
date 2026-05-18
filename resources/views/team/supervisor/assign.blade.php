@extends('layouts.team')

@section('title', 'Assign Work #'.$order->order_id.' | 1Dollar Team Portal')
@section('page_heading', 'Assign Work #'.$order->order_id)
@section('page_subheading', 'Assign this job to the in-house or freelance group.')

@section('content')
    <section class="card">
        <div class="card-body">
            <div style="display:flex;justify-content:space-between;gap:16px;align-items:center;flex-wrap:wrap;">
                <div>
                    <h3 style="margin:0 0 6px;font-size:1.15rem;">Work Assignment</h3>
                    <p class="muted" style="margin:0;">{{ $order->design_name ?: 'Order '.$order->order_id }} | {{ $order->work_type_label }}</p>
                </div>
                <a class="badge" href="{{ $backUrl }}">Back</a>
            </div>

            <div class="stats" style="margin-top:18px;">
                <article class="stat"><span class="muted">Customer</span><strong>{{ $order->customer_name ?: '-' }}</strong></article>
                <article class="stat"><span class="muted">Status</span><strong>{{ $order->status ?: '-' }}</strong></article>
                <article class="stat"><span class="muted">Turnaround</span><strong>{{ $turnaround['label_with_timing'] }}</strong></article>
                <article class="stat"><span class="muted">Schedule</span><strong>{{ $turnaround['status_label'] }}</strong></article>
            </div>

            <div class="table-wrap" style="margin-top:18px;">
                <table>
                    <tbody>
                    <tr><th>Order ID</th><td>{{ $order->order_id }}</td><th>Submitted</th><td>{{ $order->submit_date ?: '-' }}</td></tr>
                    <tr><th>Design Name</th><td>{{ $order->design_name ?: '-' }}</td><th>Format</th><td>{{ $order->format ?: '-' }}</td></tr>
                    <tr><th>Size</th><td>{{ trim(($order->width ?? '').' x '.($order->height ?? '').' '.($order->measurement ?? '')) ?: '-' }}</td><th>Colors</th><td>{{ $order->no_of_colors ?: '-' }}</td></tr>
                    <tr><th>Fabric Type</th><td>{{ $order->fabric_type ?: '-' }}</td><th>Current Assignee</th><td>{{ $order->assignee_name ?: '-' }}</td></tr>
                    <tr><th>Schedule Status</th><td>{{ $turnaround['status_label'] }}</td><th>Time Remaining</th><td>{{ $turnaround['remaining_label'] }}</td></tr>
                    </tbody>
                </table>
            </div>

            <div class="card subcard" style="margin-top:16px;">
                <div class="card-body">
                    <h4 style="margin:0 0 10px;">Source Artwork</h4>
                    <div class="table-wrap">
                        <table>
                            <thead>
                            <tr>
                                <th>File</th>
                                <th>Source</th>
                                <th>Added</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if (collect($shareableAttachments)->isEmpty())
                                <tr><td colspan="3" class="muted">No source artwork is attached to this order yet.</td></tr>
                            @else
                            @foreach ($shareableAttachments as $attachment)
                                <tr>
                                    <td>{{ $attachment->file_name_with_order_id ?: $attachment->file_name }}</td>
                                    <td>{{ $attachment->file_source ?: '-' }}</td>
                                    <td>{{ $attachment->date_added ?: '-' }}</td>
                                </tr>
                            @endforeach
                            @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <form method="post" action="{{ url('/team/assign-order.php') }}" style="margin-top:18px;">
                @csrf
                <input type="hidden" name="design_id" value="{{ $order->order_id }}">
                <input type="hidden" name="page" value="{{ $page }}">

                <div class="toolbar">
                    <div class="field">
                        <label>Assign To Group</label>
                        <div style="display:flex;gap:24px;align-items:center;padding:10px 0;">
                            <label style="display:flex;align-items:center;gap:8px;font-weight:600;cursor:pointer;">
                                <input type="radio" name="group" value="inhouse" @checked(old('group', $order->assigned_group ?? '') === 'inhouse' || (!$order->assigned_group && !old('group'))) style="width:auto;min-height:auto;">
                                In-House
                            </label>
                            <label style="display:flex;align-items:center;gap:8px;font-weight:600;cursor:pointer;">
                                <input type="radio" name="group" value="freelance" @checked(old('group', $order->assigned_group ?? '') === 'freelance') style="width:auto;min-height:auto;">
                                Freelance
                            </label>
                        </div>
                        <p class="muted" style="margin:4px 0 0;font-size:0.85rem;">In-house members self-claim jobs. Freelancers submit price quotes for review.</p>
                    </div>
                    <div class="field" style="max-width:none;">
                        <label for="handoff_comment">Handoff Comment</label>
                        <textarea id="handoff_comment" name="handoff_comment" rows="5">{{ old('handoff_comment') }}</textarea>
                    </div>
                </div>

                <div style="margin-top:16px;display:flex;gap:10px;flex-wrap:wrap;">
                    <button type="submit">Save Assignment</button>
                    <a class="badge" href="{{ $backUrl }}">Cancel</a>
                </div>
            </form>

            @if ($isFreelancePool && $freelanceQuotes && $freelanceQuotes->isNotEmpty())
            <div class="card" style="margin-top:24px;">
                <div class="card-body">
                    <h3 style="margin:0 0 6px;font-size:1.05rem;">Freelance Quotes</h3>
                    <p class="muted" style="margin:0 0 16px;">Review submitted price quotes and accept one to assign the job.</p>

                    @if (session('success'))
                        <div class="alert success" style="margin-bottom:14px;">{{ session('success') }}</div>
                    @endif

                    <div class="table-wrap">
                        <table>
                            <thead>
                            <tr>
                                <th>Freelancer</th>
                                <th>Quoted Price</th>
                                <th>Notes</th>
                                <th>Submitted</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($freelanceQuotes as $quote)
                                <tr>
                                    <td>{{ $quote->teamUser?->display_name ?: $quote->teamUser?->user_name ?: '—' }}</td>
                                    <td><strong>PKR {{ number_format((float) $quote->quoted_price, 2) }}</strong></td>
                                    <td>{{ $quote->notes ?: '—' }}</td>
                                    <td>{{ $quote->created_at?->format('M d, Y g:i a') ?: '—' }}</td>
                                    <td>
                                        @if ($quote->status === 'accepted')
                                            <span class="badge" style="background:#1e6a57;color:#fff;">Accepted</span>
                                        @elseif ($quote->status === 'rejected')
                                            <span class="badge" style="background:rgba(20,32,43,0.1);color:#64707d;">Rejected</span>
                                        @else
                                            <span class="badge" style="background:#b26a2a;color:#fff;">Pending</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($quote->status === 'pending')
                                            <form method="post" action="{{ url('/team/supervisor/orders/'.$order->order_id.'/accept-quote') }}" style="display:inline;">
                                                @csrf
                                                <input type="hidden" name="quote_id" value="{{ $quote->id }}">
                                                <button type="submit" style="background:linear-gradient(135deg,#1e6a57,#114439);">Accept Quote</button>
                                            </form>
                                        @else
                                            <span class="muted">—</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div style="margin-top:18px;display:flex;gap:10px;flex-wrap:wrap;">
                        <form method="post" action="{{ url('/team/supervisor/orders/'.$order->order_id.'/accept') }}" style="display:inline;">
                            @csrf
                            <button type="submit" style="background:linear-gradient(135deg,#0d6ea3,#094e75);">Ignore All & Take Job Myself</button>
                        </form>
                    </div>
                </div>
            </div>
            @elseif ($isFreelancePool)
            <div class="card" style="margin-top:24px;">
                <div class="card-body">
                    <h3 style="margin:0 0 6px;font-size:1.05rem;">Freelance Quotes</h3>
                    <p class="muted" style="margin:0 0 16px;">No quotes submitted yet. Freelancers will see this job in their queue and can submit quotes.</p>
                    <form method="post" action="{{ url('/team/supervisor/orders/'.$order->order_id.'/accept') }}" style="display:inline;">
                        @csrf
                        <button type="submit" style="background:linear-gradient(135deg,#0d6ea3,#094e75);">Take Job Myself</button>
                    </form>
                </div>
            </div>
            @endif

            @if ((int) $order->assign_to > 0)
            <div class="card" style="margin-top:24px;">
                <div class="card-body">
                    <h3 style="margin:0 0 6px;font-size:1.05rem;">Pull Back Job</h3>
                    <p class="muted" style="margin:0 0 16px;">This job is currently assigned to <strong>{{ $order->assignee_name ?: '—' }}</strong>. You can pull it back to the group pool to reassign or accept quotes.</p>
                    <form method="post" action="{{ url('/team/supervisor/orders/'.$order->order_id.'/pull-back') }}" style="display:inline;" onsubmit="return confirm('Pull back this job to the pool?');">
                        @csrf
                        <button type="submit" style="background:linear-gradient(135deg,#b42318,#7a1810);">Pull Back to Pool</button>
                    </form>
                </div>
            </div>
            @endif
        </div>
    </section>
@endsection
