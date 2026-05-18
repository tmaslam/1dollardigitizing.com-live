@extends('layouts.admin')

@section('title', 'Freelance Payments | 1Dollar Admin')
@section('page_heading', 'Freelance Payments')
@section('page_subheading', 'Review and settle freelancer payment withdrawal requests.')

@section('content')
    @if (session('success'))
        <div class="alert success" style="margin-bottom:14px;">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="alert" style="margin-bottom:14px;">{{ session('error') }}</div>
    @endif

    <section class="card">
        <div class="card-body">
            <div style="display:flex;justify-content:space-between;gap:16px;align-items:center;flex-wrap:wrap;margin-bottom:18px;">
                <div>
                    <h3 style="margin:0 0 6px;font-size:1.15rem;">Payment Requests</h3>
                    <p class="muted" style="margin:0;">Pending requests are listed first. Marking paid covers all unpaid completed jobs for the freelancer.</p>
                </div>
            </div>

            <div class="table-wrap">
                <table>
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Freelancer</th>
                        <th>Requested</th>
                        <th>Amount (PKR)</th>
                        <th>Status</th>
                        <th>Paid At</th>
                        <th>Paid By</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse ($requests as $req)
                        <tr>
                            <td>{{ $req->id }}</td>
                            <td>{{ $req->freelancer?->display_name ?? 'ID '.$req->freelancer_id }}</td>
                            <td>{{ $req->requested_at?->format('M j, Y g:i A') ?? '—' }}</td>
                            <td>{{ $req->amount_pkr !== null ? number_format((float) $req->amount_pkr, 2) : '—' }}</td>
                            <td>
                                @if ($req->status === 'pending')
                                    <span class="badge" style="background:#fef3e2;color:#92500a;">Pending</span>
                                @else
                                    <span class="badge" style="background:#e6f4ee;color:#1e6a57;">Paid</span>
                                @endif
                            </td>
                            <td>{{ $req->paid_at?->format('M j, Y') ?? '—' }}</td>
                            <td>{{ $req->paidBy?->display_name ?? '—' }}</td>
                            <td>
                                @if ($req->status === 'pending')
                                    <form method="post" action="{{ url('/v/freelance-payments/'.$req->id.'/pay') }}" onsubmit="return confirm('Mark this request as paid? This will update all unpaid completed jobs for this freelancer.');">
                                        @csrf
                                        <button type="submit" style="background:linear-gradient(135deg,#1e6a57,#114439);padding:6px 14px;font-size:0.875rem;">Mark as Paid</button>
                                    </form>
                                @else
                                    <span class="muted" style="font-size:0.875rem;">Settled</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="8" class="muted">No payment requests found.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </section>
@endsection
