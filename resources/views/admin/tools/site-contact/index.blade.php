@extends('layouts.admin')

@section('title', 'Site Contact Details')
@section('page_title', 'Site Contact Details')
@section('page_subtitle', 'Update the phone number and company address shown across all public and customer-facing pages for each site.')

@section('content')
    <section class="content-card stack">
        <div class="section-head">
            <div>
                <h3>Phone &amp; Address</h3>
                <p>Changes here update the phone number and address shown in the site header, footer, contact page, terms page, and all customer-facing layouts. Save each site individually.</p>
            </div>
        </div>

        @if (session('success'))
            <div class="alert success">{{ session('success') }}</div>
        @endif

        @if (collect($sites)->isEmpty())
            <div class="alert">No active sites found. Contact details are currently served from the application config.</div>
        @endif

        @foreach ($sites as $site)
            @php
                $siteContext = \App\Support\SiteResolver::fromLegacyKey((string) $site->legacy_key);
            @endphp
            <div class="content-card" style="margin-top:20px; border:1px solid rgba(22,159,230,0.15); border-radius:14px; padding:22px 24px;">
                <div style="display:flex; align-items:center; gap:12px; margin-bottom:18px;">
                    @if ($site->is_primary)
                        <span class="badge" style="background:#169fe6;color:#fff;font-size:.72rem;">Primary</span>
                    @endif
                    <strong style="font-size:1rem;">{{ $site->brand_name ?: $site->name ?: $site->legacy_key }}</strong>
                    <span class="muted" style="font-size:.82rem;">{{ $site->legacy_key }}</span>
                </div>

                <form method="post" action="{{ url('/v/site-contact/'.$site->id.'/edit') }}">
                    @csrf
                    <div style="display:grid; gap:16px;">

                        <div style="display:grid; grid-template-columns: 1fr 1fr; gap:16px;">
                            <label style="display:flex; flex-direction:column; gap:6px;">
                                <span style="font-size:.84rem; font-weight:600; color:#374151;">US Phone Number</span>
                                <input
                                    type="text"
                                    name="phone_number"
                                    value="{{ old('phone_number', $site->phone_number ?: $siteContext->phoneNumber) }}"
                                    placeholder="e.g. +1 (206) 312-6446"
                                    maxlength="100"
                                >
                            </label>
                            <label style="display:flex; flex-direction:column; gap:6px;">
                                <span style="font-size:.84rem; font-weight:600; color:#374151;">US Address</span>
                                <textarea
                                    name="company_address"
                                    rows="2"
                                    placeholder="e.g. 46494 Mission Blvd, Fremont, CA 94539"
                                    maxlength="500"
                                    style="resize:vertical;"
                                >{{ old('company_address', $site->company_address ?: $siteContext->companyAddress) }}</textarea>
                            </label>
                        </div>

                        <div style="display:grid; grid-template-columns: 1fr 1fr; gap:16px;">
                            <label style="display:flex; flex-direction:column; gap:6px;">
                                <span style="font-size:.84rem; font-weight:600; color:#374151;">UK Phone Number</span>
                                <input
                                    type="text"
                                    name="uk_phone_number"
                                    value="{{ old('uk_phone_number', $site->uk_phone_number) }}"
                                    placeholder="e.g. +44 777 888 2231"
                                    maxlength="100"
                                >
                            </label>
                            <label style="display:flex; flex-direction:column; gap:6px;">
                                <span style="font-size:.84rem; font-weight:600; color:#374151;">UK Address</span>
                                <textarea
                                    name="uk_address"
                                    rows="2"
                                    placeholder="e.g. 32 Cyprus House, 183 Townmead Road, London SW6 2JX"
                                    maxlength="500"
                                    style="resize:vertical;"
                                >{{ old('uk_address', $site->uk_address) }}</textarea>
                            </label>
                        </div>

                        <div style="display:grid; grid-template-columns: 1fr 1fr; gap:16px;">
                            <label style="display:flex; flex-direction:column; gap:6px;">
                                <span style="font-size:.84rem; font-weight:600; color:#374151;">Pakistan Phone Number</span>
                                <input
                                    type="text"
                                    name="pk_phone_number"
                                    value="{{ old('pk_phone_number', $site->pk_phone_number) }}"
                                    placeholder="e.g. +92 336 888 2231"
                                    maxlength="100"
                                >
                            </label>
                            <label style="display:flex; flex-direction:column; gap:6px;">
                                <span style="font-size:.84rem; font-weight:600; color:#374151;">Pakistan Address</span>
                                <textarea
                                    name="pk_address"
                                    rows="2"
                                    placeholder="e.g. Office 11, 2nd Floor, Glamour One Plaza, Township, Lahore, Pakistan"
                                    maxlength="500"
                                    style="resize:vertical;"
                                >{{ old('pk_address', $site->pk_address) }}</textarea>
                            </label>
                        </div>

                        <div style="display:flex; align-items:center; gap:14px; padding-top:4px;">
                            <button class="button primary" type="submit">Save Changes</button>
                        </div>
                    </div>
                </form>
            </div>
        @endforeach

        @if ($sites->isNotEmpty())
            @php
                $previewSite = $sites->first();
                $previewCtx = \App\Support\SiteResolver::fromLegacyKey((string) $previewSite->legacy_key);
                $usPhone = $previewSite->phone_number ?: $previewCtx->phoneNumber ?: '+1 206-312-6446';
                $usAddr = $previewSite->company_address ?: $previewCtx->companyAddress ?: '46494 Mission Blvd, Fremont, California 94539';
                $ukPhone = $previewSite->uk_phone_number ?: '+44 777 888 2231';
                $ukAddr = $previewSite->uk_address ?: '32 Cyprus House, 183 Townmead Road, London SW6 2JX';
                $pkPhone = $previewSite->pk_phone_number ?: '+92 336 888 2231';
                $pkAddr = $previewSite->pk_address ?: 'Office 11, 2nd Floor, Glamour One Plaza, Township, Lahore, Pakistan';
            @endphp
            <div class="content-card" style="margin-top:24px; border:1px solid rgba(22,159,230,0.12); border-radius:14px; padding:22px 24px;">
                <h4 style="margin:0 0 16px; font-size:.95rem;">Live Preview — Office Cards</h4>
                <div style="display:grid; gap:12px; max-width:520px;">
                    <div style="display:flex; align-items:flex-start; gap:12px; padding:14px 16px; background:#fff; border:1px solid #e2e8f0; border-radius:12px; box-shadow:0 1px 4px rgba(0,0,0,0.04);">
                        <span style="font-size:1.4rem;">🇺🇸</span>
                        <div>
                            <strong style="font-size:.92rem; color:#0f172a;">US Office — 24/7 Support</strong>
                            <div style="color:#169fe6; font-weight:700; font-size:.95rem; margin-top:4px;">{{ $usPhone }}</div>
                            <div style="color:#64748b; font-size:.85rem; margin-top:2px;">{{ $usAddr }}</div>
                        </div>
                    </div>
                    <div style="display:flex; align-items:flex-start; gap:12px; padding:14px 16px; background:#fff; border:1px solid #e2e8f0; border-radius:12px; box-shadow:0 1px 4px rgba(0,0,0,0.04);">
                        <span style="font-size:1.4rem;">🇬🇧</span>
                        <div>
                            <strong style="font-size:.92rem; color:#0f172a;">UK Office</strong>
                            <div style="color:#169fe6; font-weight:700; font-size:.95rem; margin-top:4px;">{{ $ukPhone }}</div>
                            <div style="color:#64748b; font-size:.85rem; margin-top:2px;">{{ $ukAddr }}</div>
                        </div>
                    </div>
                    <div style="display:flex; align-items:flex-start; gap:12px; padding:14px 16px; background:#fff; border:1px solid #e2e8f0; border-radius:12px; box-shadow:0 1px 4px rgba(0,0,0,0.04);">
                        <span style="font-size:1.4rem;">🇵🇰</span>
                        <div>
                            <strong style="font-size:.92rem; color:#0f172a;">Pakistan Office</strong>
                            <div style="color:#169fe6; font-weight:700; font-size:.95rem; margin-top:4px;">{{ $pkPhone }}</div>
                            <div style="color:#64748b; font-size:.85rem; margin-top:2px;">{{ $pkAddr }}</div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <div class="content-card" style="margin-top:24px; background:rgba(22,159,230,0.04); border:1px solid rgba(22,159,230,0.12); border-radius:12px; padding:16px 20px;">
            <h4 style="margin:0 0 8px; font-size:.9rem;">Where these values appear</h4>
            <ul style="margin:0; padding-left:18px; font-size:.83rem; color:#526071; line-height:1.8;">
                <li>Public site header (top bar call link)</li>
                <li>Public site footer (Contact column)</li>
                <li>Contact Us page — phone, address block</li>
                <li>About Us page — location section</li>
                <li>Terms &amp; Conditions — contact section</li>
                <li>Customer checkout / plan purchase page</li>
                <li>Customer portal guest layout footer</li>
                <li>Structured data (JSON-LD schema) for SEO</li>
            </ul>
        </div>
    </section>
@endsection
