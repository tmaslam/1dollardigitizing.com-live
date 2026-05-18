@extends('layouts.team')

@section('title', 'Summary | 1Dollar Team Portal')
@section('page_heading', 'Summary')
@section('page_subheading', ($teamUser->is_supervisor ?? false) ? 'Track team queues, review work, and move jobs forward without jumping through legacy screens.' : 'Jump into the right queue quickly and keep assigned work moving.')

@section('content')
    <section class="card">
        <div class="card-body">
            <div class="stats">
                @foreach ($queueNavigation as $queue)
                    @if ($queue['key'] === 'quotes' && $teamUser->isFreelance())
                        @continue
                    @endif
                    <a class="stat" href="{{ $queue['url'] }}">
                        <span class="muted">{{ $queue['label'] }}</span>
                        <strong>{{ $queue['count'] }}</strong>
                    </a>
                @endforeach
                @if ($teamUser->is_supervisor ?? false)
                    <a class="stat" href="{{ url('/team/review-queue.php') }}">
                        <span class="muted">Ready For Review</span>
                        <strong>{{ $navCounts['ready_review'] ?? 0 }}</strong>
                    </a>
                    <a class="stat" href="{{ url('/team/review-queue.php') }}">
                        <span class="muted">Verified Jobs</span>
                        <strong>{{ $navCounts['verified_jobs'] ?? 0 }}</strong>
                    </a>
                    <a class="stat" href="{{ url('/team/manage-team.php') }}">
                        <span class="muted">Team Members</span>
                        <strong>{{ $navCounts['team_members'] ?? 0 }}</strong>
                    </a>
                @endif
            </div>
        </div>
    </section>

    @if ($freelanceEarnings !== null)
    <section class="card">
        <div class="card-body">
            <h3 style="margin:0 0 6px;font-size:1.15rem;">My Earnings</h3>
            <p class="muted" style="margin:0 0 18px;">Summary of completed jobs and PKR earnings. Fines are already deducted.</p>

            @if (session('payment_success'))
                <div class="alert success" style="margin-bottom:14px;">{{ session('payment_success') }}</div>
            @endif
            @if (session('payment_error'))
                <div class="alert" style="margin-bottom:14px;">{{ session('payment_error') }}</div>
            @endif

            <div class="stats" style="margin-bottom:18px;">
                <article class="stat"><span class="muted">Completed Jobs</span><strong style="font-size:1.15rem;">{{ $freelanceEarnings['total_jobs'] }}</strong></article>
                <article class="stat"><span class="muted">Total Earned (PKR)</span><strong style="font-size:1.15rem;">{{ number_format($freelanceEarnings['total_earned_pkr'], 2) }}</strong></article>
                <article class="stat"><span class="muted">Pending Payment (PKR)</span><strong style="font-size:1.15rem;color:#92500a;">{{ number_format($freelanceEarnings['pending_payment_pkr'], 2) }}</strong></article>
                <article class="stat"><span class="muted">Paid (PKR)</span><strong style="font-size:1.15rem;color:#1e6a57;">{{ number_format($freelanceEarnings['paid_pkr'], 2) }}</strong></article>
            </div>

            @if ($freelanceEarnings['can_request_payment'])
            <form method="post" action="{{ url('/team/request-payment') }}" style="display:inline;">
                @csrf
                <button type="submit" style="background:linear-gradient(135deg,#1e6a57,#114439);">Request Funds Withdrawal</button>
            </form>
            <span class="muted" style="margin-left:12px;font-size:0.88rem;">
                PKR {{ number_format($freelanceEarnings['pending_payment_pkr'], 2) }} will be requested.
            </span>
            @elseif ($freelanceEarnings['has_pending_request'])
            <div class="alert success" style="display:inline-block;padding:10px 16px;">
                Payment request submitted — admin will process it shortly.
            </div>
            @elseif ($freelanceEarnings['pending_payment_pkr'] <= 0)
            <p class="muted">No unpaid earnings available to withdraw right now.</p>
            @endif
        </div>
    </section>
    @endif

    <section class="card">
        <div class="card-body">
            <h3 style="margin:0 0 6px;font-size:1.15rem;">Queue Shortcuts</h3>
            <p class="muted" style="margin:0 0 18px;">Each queue now has its own stable route so the list, detail, and back actions stay in sync.</p>

            <div class="stats">
                @foreach ($queueNavigation as $queue)
                    @if ($queue['key'] === 'quotes' && $teamUser->isFreelance())
                        @continue
                    @endif
                    <article class="stat" style="align-items:flex-start;">
                        <span class="muted">{{ $queue['label'] }}</span>
                        <strong>{{ $queue['count'] }}</strong>
                        <p class="muted" style="margin:8px 0 0;">{{ $queue['summary'] }}</p>
                        <a class="badge" href="{{ $queue['url'] }}" style="margin-top:12px;">Open Queue</a>
                    </article>
                @endforeach
            </div>
        </div>
    </section>
@endsection
