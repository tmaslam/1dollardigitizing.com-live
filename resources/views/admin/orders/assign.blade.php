@extends('layouts.admin')

@section('title', 'Assign Order '.$order->order_id.' | 1Dollar Admin')
@section('page_heading', 'Assign Order '.$order->order_id)
@section('page_subheading', 'Assign work to a team group, share files, and send handoff notes.')

@section('content')
    @php
        $customerCommentModeValue = old('customer_comment_mode', $customerCommentMode);
        $sharedCustomerCommentValue = old('shared_customer_comment', $existingSharedCustomerText !== '' ? $existingSharedCustomerText : $customerSubmissionText);
        $handoffCommentValue = old('handoff_comment', $existingHandoffText);
        $currentGroup = old('group', $order->assigned_group ?? '');
    @endphp

    @if ($errors->any())
        <div class="alert">{{ $errors->first() }}</div>
    @endif

    <section class="card">
        <div class="card-body">
            <form method="post" action="{{ url('/v/assign-order.php') }}">
                @csrf
                <input type="hidden" name="design_id" value="{{ $order->order_id }}">
                <input type="hidden" name="page" value="{{ $page }}">
                <input type="hidden" name="status" value="{{ $order->status }}">
                <input type="hidden" name="back" value="{{ $backQueue }}">

                <div class="toolbar">
                    <div class="field">
                        <label>Assign To Group</label>
                        <div style="display:flex;gap:24px;align-items:center;padding:10px 0;">
                            <label style="display:flex;align-items:center;gap:8px;font-weight:600;cursor:pointer;">
                                <input type="radio" name="group" value="inhouse" @checked($currentGroup === 'inhouse' || $currentGroup === '') style="width:auto;min-height:auto;">
                                In-House
                            </label>
                            <label style="display:flex;align-items:center;gap:8px;font-weight:600;cursor:pointer;">
                                <input type="radio" name="group" value="freelance" @checked($currentGroup === 'freelance') style="width:auto;min-height:auto;">
                                Freelance
                            </label>
                        </div>
                        <p class="muted" style="margin:4px 0 0;font-size:0.85rem;">In-house members self-claim jobs. Freelancers submit price quotes for your review.</p>
                    </div>

                    <div class="field" style="min-width:280px;">
                        <label>Handoff Comment</label>
                        <textarea name="handoff_comment" rows="4">{{ $handoffCommentValue }}</textarea>
                    </div>

                    <div class="field" style="min-width:260px;">
                        <label>Customer Note Sharing</label>
                        <select name="customer_comment_mode">
                            <option value="original" @selected($customerCommentModeValue === 'original')>Send Original Customer Notes</option>
                            <option value="edited" @selected($customerCommentModeValue === 'edited')>Edit Before Sharing</option>
                            <option value="none" @selected($customerCommentModeValue === 'none')>Do Not Share Customer Notes</option>
                        </select>
                    </div>
                </div>

                <div class="card" style="margin-top:18px;">
                    <div class="card-body">
                        <h3 style="margin:0 0 12px;font-size:1.05rem;">Share Attachments With Team</h3>
                        <p class="muted" style="margin:0 0 14px;">Select only the source files you want the assigned group to see.</p>
                        <div class="action-row" style="padding: 0 0 12px;">
                            <button type="button" class="badge" id="select-all-attachments">Select All</button>
                            <button type="button" class="badge badge-muted" id="clear-all-attachments">Clear All</button>
                        </div>
                        <div class="table-wrap">
                            <table>
                                <thead>
                                <tr>
                                    <th>Select</th>
                                    <th>File</th>
                                    <th>Source</th>
                                    <th>Added</th>
                                </tr>
                                </thead>
                                <tbody>
                                @if (collect($shareableAttachments)->isEmpty())
                                    <tr><td colspan="4" class="muted">No source attachments are available to share.</td></tr>
                                @else
                                @foreach ($shareableAttachments as $attachment)
                                    <tr>
                                        <td><input type="checkbox" name="attachment_ids[]" value="{{ $attachment->id }}" data-attachment-checkbox @checked(in_array((int) $attachment->id, $defaultSelectedAttachmentIds, true))></td>
                                        <td>{{ $attachment->file_name_with_order_id ?: $attachment->file_name }}</td>
                                        <td>{{ $attachment->file_source }}</td>
                                        <td>{{ $attachment->date_added ?: '-' }}</td>
                                    </tr>
                                @endforeach
                                @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div style="display:flex;justify-content:space-between;align-items:center;gap:12px;flex-wrap:wrap;margin-top:18px;padding:14px 16px;border:1px solid rgba(24, 34, 45, 0.1);border-radius:18px;background:rgba(255,255,255,0.62);">
                    <div>
                        <strong style="display:block;">Quick Assign</strong>
                        <span class="muted">Job will be visible to all active members of the selected group.</span>
                    </div>
                    <div style="display:flex;gap:10px;flex-wrap:wrap;">
                        <button type="submit">Assign To Group</button>
                        <a class="badge" href="{{ url('/v/orders/'.$order->order_id.'/detail/'.$page.'?back='.rawurlencode($backQueue)) }}">Back to Order Detail</a>
                    </div>
                </div>

                <div class="card" style="margin-top:18px;">
                    <div class="card-body">
                        <h3 style="margin:0 0 6px;font-size:1.05rem;">Order Snapshot</h3>
                        <p class="muted" style="margin:0 0 14px;">Current assignment, status, and customer before reassignment.</p>
                        <div class="stats">
                            <article class="stat"><span class="muted">Status</span><strong style="font-size:1.15rem;">{{ $order->status ?: '-' }}</strong></article>
                            <article class="stat"><span class="muted">Group</span><strong style="font-size:1.15rem;">{{ $order->assigned_group ? ucfirst($order->assigned_group) : '—' }}</strong></article>
                            <article class="stat"><span class="muted">Accepted By</span><strong style="font-size:1.15rem;">{{ $order->assignee_name ?: '—' }}</strong></article>
                            <article class="stat"><span class="muted">Customer</span><strong style="font-size:1.15rem;">{{ $order->customer_name }}</strong></article>
                        </div>
                    </div>
                </div>

                <div class="card" style="margin-top:18px;">
                    <div class="card-body">
                        <h3 style="margin:0 0 12px;font-size:1.05rem;">Customer Submission Review</h3>
                        <div class="field" style="min-width:100%;">
                            <label>Original Customer Notes</label>
                            <textarea rows="6" readonly>{{ $customerSubmissionText !== '' ? $customerSubmissionText : 'No customer note text was found for this order.' }}</textarea>
                        </div>
                        <div class="field" style="min-width:100%;margin-top:14px;">
                            <label>Customer Notes To Share Downstream</label>
                            <textarea name="shared_customer_comment" rows="6">{{ $sharedCustomerCommentValue }}</textarea>
                        </div>
                        <p class="muted" style="margin:12px 0 0;">Only the shared text above is sent downstream. Raw customer notes are not exposed automatically.</p>
                    </div>
                </div>

                <div class="card" style="margin-top:18px;">
                    <div class="card-body">
                        <h3 style="margin:0 0 12px;font-size:1.05rem;">Existing Handoff Notes</h3>
                        <div class="table-wrap">
                            <table>
                                <thead>
                                <tr>
                                    <th>Comment</th>
                                    <th>Updated</th>
                                </tr>
                                </thead>
                                <tbody>
                                @if (collect($handoffComments)->isEmpty())
                                    <tr><td colspan="2" class="muted">No handoff notes yet.</td></tr>
                                @else
                                @foreach ($handoffComments as $comment)
                                    <tr>
                                        <td>{{ $comment->comments }}</td>
                                        <td>{{ $comment->date_modified ?: $comment->date_added ?: '-' }}</td>
                                    </tr>
                                @endforeach
                                @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div style="display:flex;gap:10px;flex-wrap:wrap;margin-top:18px;">
                    <button type="submit">Save Assignment</button>
                    <a class="badge" href="{{ url('/v/orders/'.$order->order_id.'/detail/'.$page.'?back='.rawurlencode($backQueue)) }}">Back to Order Detail</a>
                </div>
            </form>
        </div>
    </section>

    {{-- Freelance Quotes Panel --}}
    @if ($order->assigned_group === 'freelance' && ((int) $order->assign_to === 0 || !$order->assign_to))
    <section class="card" style="margin-top:24px;">
        <div class="card-body">
            <h3 style="margin:0 0 6px;font-size:1.05rem;">Freelance Quotes</h3>
            <p class="muted" style="margin:0 0 16px;">Freelancers who have submitted a price quote for this order. Accept one to assign the job.</p>

            @if (session('success'))
                <div class="alert success" style="margin-bottom:14px;">{{ session('success') }}</div>
            @endif

            @if ($freelanceQuotes->isEmpty())
                <p class="muted">No quotes submitted yet. Freelancers will see this job in their queue and can submit quotes.</p>
            @else
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
                                    <span class="badge badge-muted">Rejected</span>
                                @else
                                    <span class="badge" style="background:#b26a2a;color:#fff;">Pending</span>
                                @endif
                            </td>
                            <td>
                                @if ($quote->status === 'pending')
                                <form method="post" action="{{ url('/v/orders/'.$order->order_id.'/accept-quote') }}" style="display:inline;">
                                    @csrf
                                    <input type="hidden" name="quote_id" value="{{ $quote->id }}">
                                    <button type="submit" style="background:linear-gradient(135deg,#1e6a57,#114439);">Accept Quote</button>
                                </form>
                                @else
                                    —
                                @endif
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            @endif
        </div>
    </section>
    @endif

    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const attachmentCheckboxes = Array.from(document.querySelectorAll('[data-attachment-checkbox]'));
        const selectAllAttachments = document.getElementById('select-all-attachments');
        const clearAllAttachments = document.getElementById('clear-all-attachments');

        selectAllAttachments?.addEventListener('click', function () {
            attachmentCheckboxes.forEach((checkbox) => { checkbox.checked = true; });
        });

        clearAllAttachments?.addEventListener('click', function () {
            attachmentCheckboxes.forEach((checkbox) => { checkbox.checked = false; });
        });
    });
    </script>
@endsection
