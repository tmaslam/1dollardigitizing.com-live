@extends('layouts.admin')

@section('title', 'Verify Password Change | 1Dollar Admin')
@section('page_heading', 'Verify Password Change')
@section('page_subheading', 'Enter the 6-digit code sent to your registered email to confirm the password change.')

@section('content')
    <style>
        .code-input {
            font-size: 1.6rem;
            letter-spacing: 0.2em;
            font-family: monospace;
            text-align: center;
            max-width: 220px;
        }
        .verify-card {
            max-width: 520px;
        }
    </style>

    @if ($errors->has('code'))
        <div class="alert" style="margin-bottom:18px;">{{ $errors->first('code') }}</div>
    @endif

    @if (session('success'))
        <div class="alert" style="margin-bottom:18px;background:rgba(21,115,71,0.08);color:#1d6f46;border-color:rgba(21,115,71,0.16);">{{ session('success') }}</div>
    @endif

    <section class="card verify-card">
        <div class="card-body">
            <div style="display:flex;gap:14px;align-items:flex-start;margin-bottom:18px;">
                <div style="width:44px;height:44px;border-radius:14px;background:rgba(15,95,102,0.1);display:grid;place-items:center;flex-shrink:0;">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#0f5f66" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                </div>
                <div>
                    <h3 style="margin:0 0 4px;font-size:1.1rem;">Two-Step Verification</h3>
                    <p class="muted" style="margin:0;">A <strong>10-digit</strong> code was sent to <strong>{{ $adminUser->user_email ?: 'your registered email' }}</strong>. It expires in 10 minutes and can only be used once.</p>
                </div>
            </div>

            <form method="post" action="{{ url('/v/change-password-verify.php') }}">
                @csrf
                <div class="field">
                    <label for="code">Verification Code</label>
                    <input id="code" type="text" name="code" class="code-input" inputmode="numeric" pattern="[0-9]{10}" maxlength="10" placeholder="0000000000" autocomplete="one-time-code" autofocus required>
                </div>
                <div style="display:flex;gap:12px;align-items:center;margin-top:18px;">
                    <button type="submit">Verify &amp; Update Password</button>
                    <a href="{{ url('/v/change-password.php') }}" class="badge" style="background:transparent;color:var(--muted);border:1px solid var(--line);">Cancel</a>
                </div>
            </form>
        </div>
    </section>
@endsection
