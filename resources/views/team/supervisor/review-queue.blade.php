@extends('layouts.team')

@section('title', 'Review Queue | 1Dollar Team Portal')
@section('page_heading', 'Review Queue')
@section('page_subheading', 'Approve or disapprove completed work before it reaches admin. You may also take any open pool job.')

@section('content')
    @if (session('success'))
        <div class="alert success" style="margin-bottom:16px;">{{ session('success') }}</div>
    @endif
    @if ($errors->any())
        <div class="alert" style="margin-bottom:16px;">{{ $errors->first() }}</div>
    @endif

    <section class="card">
        <div class="card-body">
            <form method="get" action="{{ url('/team/review-queue.php') }}" class="toolbar">
                <div class="field">
                    <label for="txtUserID">Team Member</label>
                    <select id="txtUserID" name="txtUserID">
                        <option value="">All Members</option>
                        @foreach ($members as $member)
                            <option value="{{ $member->user_id }}" @selected((string) request('txtUserID') === (string) $member->user_id)>{{ $member->user_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="field">
                    <label for="txtOrderID">Order ID</label>
                    <input id="txtOrderID" type="text" name="txtOrderID" value="{{ request('txtOrderID') }}">
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
                        <th>Order ID</th>
                        <th>Assigned To</th>
                        <th>Group</th>
                        <th>Design Name</th>
                        <th>Work Type</th>
                        <th>Schedule</th>
                        <th>Completed</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if ($orders->isEmpty())
                        <tr><td colspan="8" class="muted">No completed work is waiting in the review queue.</td></tr>
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
                            $acceptedQuotePrice = $isFreelance
                                ? \App\Models\FreelanceQuote::where('order_id', $order->order_id)->where('status', 'accepted')->value('quoted_price')
                                : null;
                        @endphp
                        <tr>
                            <td><a href="{{ $detailUrl($order) }}" class="badge">{{ $order->order_id }}</a></td>
                            <td>{{ $order->assignee_name ?: '—' }}</td>
                            <td>{{ $isFreelance ? 'Freelance' : 'In-House' }}</td>
                            <td>{{ $order->design_name ?: '—' }}</td>
                            <td>{{ $order->work_type_label }}</td>
                            <td>
                                <span class="badge" style="{{ $scheduleBadgeStyle }}">{{ $order->turnaround_status_label ?: '—' }}</span>
                            </td>
                            <td>{{ $order->vender_complete_date ?: '—' }}</td>
                            <td>
                                <div class="action-row" style="flex-wrap:wrap;gap:8px;">
                                    {{-- Approve button + inline form --}}
                                    <button type="button" class="badge" style="background:#1e6a57;color:#fff;" onclick="togglePanel('approve-{{ $order->order_id }}')">Approve</button>
                                    {{-- Disapprove button + inline form --}}
                                    <button type="button" class="badge badge-muted" onclick="togglePanel('disapprove-{{ $order->order_id }}')">Disapprove</button>
                                </div>

                                {{-- Approve panel --}}
                                <div id="approve-{{ $order->order_id }}" style="display:none;margin-top:10px;padding:12px;border:1px solid rgba(30,106,87,0.2);border-radius:10px;background:rgba(30,106,87,0.04);">
                                    <form method="post" action="{{ url('/team/supervisor/orders/'.$order->order_id.'/approve') }}">
                                        @csrf
                                        <p style="margin:0 0 8px;font-weight:600;font-size:0.9rem;">Approve Order #{{ $order->order_id }}</p>
                                        <label style="display:flex;align-items:center;gap:8px;margin-bottom:10px;font-size:0.88rem;cursor:pointer;">
                                            <input type="checkbox" id="add-fine-approve-{{ $order->order_id }}" onchange="toggleFine('fine-approve-{{ $order->order_id }}', this.checked)" style="width:auto;min-height:auto;">
                                            Add Fine / Penalty
                                        </label>
                                        <div id="fine-approve-{{ $order->order_id }}" style="display:none;margin-bottom:10px;">
                                            <div style="display:flex;gap:10px;flex-wrap:wrap;align-items:flex-start;">
                                                <div>
                                                    <label style="font-size:0.82rem;">Fine Amount ({{ $isFreelance ? 'PKR' : 'Rs.' }}{{ $isFreelance && $acceptedQuotePrice ? ', max PKR '.number_format((float)$acceptedQuotePrice,2) : ', max Rs. 200' }})</label>
                                                    <input type="number" name="fine_amount" min="0.01" step="0.01" max="{{ $isFreelance ? ($acceptedQuotePrice ?? 0) : 200 }}" placeholder="Amount" style="width:140px;">
                                                </div>
                                                <div style="flex:1;min-width:200px;">
                                                    <label style="font-size:0.82rem;">Fine Note <span style="color:#b42318;">*required</span></label>
                                                    <textarea name="fine_reason" rows="2" placeholder="Reason for fine (required)" style="min-width:0;width:100%;"></textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <button type="submit" style="background:linear-gradient(135deg,#1e6a57,#114439);">Confirm Approve</button>
                                        <button type="button" class="badge badge-muted" onclick="togglePanel('approve-{{ $order->order_id }}')">Cancel</button>
                                    </form>
                                </div>

                                {{-- Disapprove panel --}}
                                <div id="disapprove-{{ $order->order_id }}" style="display:none;margin-top:10px;padding:12px;border:1px solid rgba(180,35,24,0.2);border-radius:10px;background:rgba(180,35,24,0.04);">
                                    <form method="post" action="{{ url('/team/supervisor/orders/'.$order->order_id.'/disapprove') }}">
                                        @csrf
                                        <p style="margin:0 0 8px;font-weight:600;font-size:0.9rem;">Disapprove Order #{{ $order->order_id }}</p>
                                        <div style="margin-bottom:10px;">
                                            <label style="font-size:0.82rem;">Reason <span style="color:#b42318;">*required</span></label>
                                            <textarea name="reason" rows="3" placeholder="Explain what needs to be fixed" required style="width:100%;"></textarea>
                                        </div>
                                        <label style="display:flex;align-items:center;gap:8px;margin-bottom:10px;font-size:0.88rem;cursor:pointer;">
                                            <input type="checkbox" id="add-fine-disapprove-{{ $order->order_id }}" onchange="toggleFine('fine-disapprove-{{ $order->order_id }}', this.checked)" style="width:auto;min-height:auto;">
                                            Add Fine / Penalty
                                        </label>
                                        <div id="fine-disapprove-{{ $order->order_id }}" style="display:none;margin-bottom:10px;">
                                            <div style="display:flex;gap:10px;flex-wrap:wrap;align-items:flex-start;">
                                                <div>
                                                    <label style="font-size:0.82rem;">Fine Amount ({{ $isFreelance ? 'PKR' : 'Rs.' }}{{ $isFreelance && $acceptedQuotePrice ? ', max PKR '.number_format((float)$acceptedQuotePrice,2) : ', max Rs. 200' }})</label>
                                                    <input type="number" name="fine_amount" min="0.01" step="0.01" max="{{ $isFreelance ? ($acceptedQuotePrice ?? 0) : 200 }}" placeholder="Amount" style="width:140px;">
                                                </div>
                                                <div style="flex:1;min-width:200px;">
                                                    <label style="font-size:0.82rem;">Fine Note <span style="color:#b42318;">*required</span></label>
                                                    <textarea name="fine_reason" rows="2" placeholder="Reason for fine (required)" style="min-width:0;width:100%;"></textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <button type="submit" style="background:linear-gradient(135deg,#b42318,#7a1810);">Confirm Disapprove</button>
                                        <button type="button" class="badge badge-muted" onclick="togglePanel('disapprove-{{ $order->order_id }}')">Cancel</button>
                                    </form>
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

    <script>
    function togglePanel(id) {
        const el = document.getElementById(id);
        if (el) el.style.display = el.style.display === 'none' ? 'block' : 'none';
    }
    function toggleFine(id, show) {
        const el = document.getElementById(id);
        if (el) el.style.display = show ? 'block' : 'none';
    }
    </script>
@endsection
