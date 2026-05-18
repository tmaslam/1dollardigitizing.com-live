@extends('layouts.customer')

@section('title', 'Payment Status — '.$siteContext->displayLabel())
@section('hero_class', 'hero-compact')
@section('hero_title', 'Payment Status')
@section('hero_text', $siteContext->displayLabel())

@section('content')
    @php
        $type     = $plan['type']    ?? null;
        $details  = $plan['plan']    ?? null;
        $label    = $details['label'] ?? 'Your selected plan';
        $price    = $details['price'] ?? null;
        $fullPrice = $details['full_price'] ?? null;
        $isSub    = $type === 'subscription';
        $isCustom = $type === 'custom';
        $isCredit = $type === 'credit';
    @endphp

    <section class="content-card" style="max-width:620px; margin-inline:auto;">

        <div style="text-align:center; padding: 8px 0 24px;">
            <div style="font-size:3rem; line-height:1; margin-bottom:14px;">⏳</div>
            <h2 style="margin:0 0 8px; color:#182a3e;">Waiting for Stripe Confirmation</h2>
            <p style="color:#526071; margin:0;">We did not receive a session reference from Stripe. Your payment may still be processing. Please check your dashboard in a few minutes, or contact support if your credits don't appear shortly.</p>
        </div>

        @if ($details)
            <div style="border:1.5px solid rgba(22,159,230,0.18); border-radius:14px; overflow:hidden; margin-bottom:24px;">
                <div style="background:rgba(22,159,230,0.05); padding:14px 18px; border-bottom:1px solid rgba(22,159,230,0.10);">
                    <span style="font-size:0.70rem; font-weight:700; text-transform:uppercase; letter-spacing:0.08em; color:#0d6ea3; background:rgba(22,159,230,0.10); padding:3px 10px; border-radius:999px;">
                        {{ $isCustom ? 'Custom Top-Up' : ($isSub ? 'Monthly Subscription' : 'Credit Pack') }}
                    </span>
                    <h3 style="margin:8px 0 0; font-size:1.1rem; color:#182a3e;">{{ $label }}</h3>
                </div>
                <div style="padding:14px 18px; display:grid; gap:8px;">
                    @if ($price !== null)
                        <div style="display:flex; justify-content:space-between; font-size:0.85rem; color:#526071;">
                            <span>Amount paid</span>
                            <strong style="color:#182a3e;">${{ $price == floor($price) ? number_format($price) : number_format($price, 2) }}{{ $isSub ? ' / month' : '' }}</strong>
                        </div>
                    @endif
                    @if ($isCredit && $fullPrice !== null)
                        <div style="display:flex; justify-content:space-between; font-size:0.85rem; color:#526071;">
                            <span>Credits to be added</span>
                            <strong style="color:#3c9e6a;">{{ number_format($fullPrice) }} credits</strong>
                        </div>
                    @endif
                </div>
            </div>
        @endif

        <div style="display:flex; gap:12px; flex-wrap:wrap; justify-content:center;">
            <a href="{{ url('/dashboard.php') }}" class="button">Go to Dashboard →</a>
            <a href="{{ url('/contact-us.php') }}" class="button secondary">Contact Support</a>
        </div>

    </section>
@endsection
