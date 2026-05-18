@extends('layouts.customer-guest')

@section('title', $siteContext->displayLabel().' Login')

@section('content')
<style>
#legacy-login-btn {
    display: flex;
    width: 100%;
    justify-content: center;
    background-image:
        url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='400' height='44' preserveAspectRatio='none'%3E%3Cpath d='M140,10 C148,16 145,26 155,30 C162,33 168,26 178,30 C185,34 183,40 192,38' stroke='%231a0800' stroke-opacity='0.72' stroke-width='1.3' fill='none' stroke-linecap='round'/%3E%3Cpath d='M155,30 C160,24 168,18 178,16 C186,14 190,20 200,18 C208,16 212,10 222,12' stroke='%231a0800' stroke-opacity='0.62' stroke-width='1.1' fill='none' stroke-linecap='round'/%3E%3Cpath d='M178,30 C185,34 190,30 198,34 C205,37 208,32 218,34 C225,36 228,42 238,40' stroke='%231a0800' stroke-opacity='0.55' stroke-width='0.95' fill='none' stroke-linecap='round'/%3E%3Cpath d='M178,16 C180,10 178,4 184,8' stroke='%231a0800' stroke-opacity='0.42' stroke-width='0.7' fill='none' stroke-linecap='round'/%3E%3Cpath d='M198,34 C200,39 197,44 205,42' stroke='%231a0800' stroke-opacity='0.4' stroke-width='0.65' fill='none' stroke-linecap='round'/%3E%3Cpath d='M222,12 C228,8 232,14 240,10 C246,7 248,14 256,12' stroke='%231a0800' stroke-opacity='0.5' stroke-width='0.85' fill='none' stroke-linecap='round'/%3E%3Cpath d='M236,12 C240,18 238,24 245,22' stroke='%231a0800' stroke-opacity='0.38' stroke-width='0.6' fill='none' stroke-linecap='round'/%3E%3Cpath d='M40,20 C48,14 52,24 60,20 C66,17 65,26 74,24' stroke='%231a0800' stroke-opacity='0.35' stroke-width='0.8' fill='none' stroke-linecap='round'/%3E%3Cpath d='M60,20 C58,26 56,32 62,36' stroke='%231a0800' stroke-opacity='0.3' stroke-width='0.6' fill='none' stroke-linecap='round'/%3E%3Cpath d='M272,6 C280,10 277,2 287,8 C294,12 292,4 302,8' stroke='%231a0800' stroke-opacity='0.35' stroke-width='0.7' fill='none' stroke-linecap='round'/%3E%3Cpath d='M287,8 C289,14 286,20 292,18' stroke='%231a0800' stroke-opacity='0.28' stroke-width='0.55' fill='none' stroke-linecap='round'/%3E%3Cpath d='M318,22 C326,16 328,28 338,24 C346,20 344,30 354,26' stroke='%231a0800' stroke-opacity='0.3' stroke-width='0.7' fill='none' stroke-linecap='round'/%3E%3C/svg%3E"),
        linear-gradient(110deg, #9a5208 0%, #c87818 16%, #f0aa28 32%, #f8c840 48%, #e09018 62%, #c87818 76%, #a05e10 90%, #d08c14 100%) !important;
    background-size: 100% 100%, 100% 100% !important;
    color: #fff8e0 !important;
    border: 2px solid #7a4008 !important;
    border-radius: 4px 6px 5px 3px / 5px 3px 6px 4px !important;
    box-shadow: inset 0 1px 3px rgba(255,220,80,.25), inset 0 -2px 6px rgba(80,30,0,.65), 0 2px 10px rgba(0,0,0,.5) !important;
    text-shadow: 0 1px 3px rgba(0,0,0,.75) !important;
    font-weight: 700 !important;
    letter-spacing: .06em !important;
}
#legacy-login-btn:hover { filter: brightness(1.1); }
</style>
    <div class="container guest-shell">
        <div class="auth-layout auth-layout-solo">
            <section class="panel form-panel auth-panel">
                <h2>Sign In</h2>
                <p class="muted">Use your email or username to access your orders, quotes, billing, and downloads.</p>

                <div style="margin-bottom:20px; padding-bottom:16px; border-bottom:1px solid #e2e8f0; text-align:center;">
                    <p class="muted" style="margin-bottom:10px; font-size:0.85rem;">Looking for the old portal?</p>
                    <a class="button ghost" href="https://legacy.1dollardigitizing.com/login.php" id="legacy-login-btn">Legacy Sign In</a>
                </div>

                @if (session('success'))
                    <div class="alert success">{{ session('success') }}</div>
                @endif

                @if ($errors->any())
                    <div class="alert">{{ $errors->first() }}</div>
                @endif

                <form method="post" action="{{ url('/login.php') }}" data-validate-form novalidate>
                    @csrf
                    <label class="form-field" data-form-field>
                        <span class="field-label">Email or User Name <span class="field-meta required" aria-hidden="true">*</span></span>
                        <input type="text" name="user_id" value="{{ old('user_id') }}" autocomplete="username" required>
                        <span class="field-help">You can use either the account email or the customer username tied to your account.</span>
                        <span class="field-error" data-field-error aria-live="polite"></span>
                    </label>

                    <label class="form-field" data-form-field>
                        <span class="field-label">Password <span class="field-meta required" aria-hidden="true">*</span></span>
                        <input type="password" name="user_psw" autocomplete="current-password" required>
                        <span class="field-error" data-field-error aria-live="polite"></span>
                    </label>

                    <label class="form-field" data-form-field style="display:flex; align-items:center; gap:10px; font-weight:400;">
                        <input type="checkbox" name="remember_me" value="1" @checked(old('remember_me')) style="width:auto; min-height:auto;">
                        <span class="field-label" style="font-weight:400;">Remember me on this device</span>
                    </label>

                    @include('shared.turnstile')

                    <div class="actions">
                        <button type="submit">Sign In</button>
                        <a class="button secondary" href="{{ url('/sign-up.php') }}">Create Account</a>
                    </div>
                </form>

                <p class="muted" style="margin-top:16px;">
                    <a href="{{ url('/forget-password.php') }}">Forgot your password?</a><br>
                    <a href="{{ url('/resend-verification.php') }}">Need a new verification email?</a><br>
                    Need help? <a href="{{ url('/contact-us.php') }}">Contact Us</a>.
                </p>
            </section>
        </div>
    </div>
@endsection
