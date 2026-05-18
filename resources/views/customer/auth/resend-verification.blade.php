@extends('layouts.customer-guest')

@section('title', $siteContext->displayLabel().' Resend Verification')

@section('content')
    <div class="container guest-shell" style="max-width:560px;">
        <section class="panel form-panel">
            <h2>Send verification again</h2>
            <p class="muted">If the email does not land in your inbox, please check spam or junk.</p>

            @if ($errors->any())
                <div class="alert">{{ $errors->first() }}</div>
            @endif

            <form method="post" action="{{ url('/resend-verification.php') }}" data-validate-form novalidate>
                @csrf
                <label class="form-field" data-form-field>
                    <span class="field-label">Email or User Name <span class="field-meta required" aria-hidden="true">*</span></span>
                    <input type="text" name="identity" value="{{ old('identity') }}" required>
                    <span class="field-help">Use the same email address or username you used during signup.</span>
                    <span class="field-error" data-field-error aria-live="polite"></span>
                </label>

                @include('shared.turnstile')

                <div class="actions">
                    <button type="submit">Send Verification Email</button>
                    <a class="button secondary" href="{{ url('/login.php') }}">Back To Login</a>
                </div>
            </form>
        </section>
    </div>
@endsection
