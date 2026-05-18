@extends('public.layout')

@section('title', 'Privacy Policy | '.$siteContext->displayLabel())
@section('meta_description', 'Privacy Policy for 1 Dollar Digitizing. GDPR & US law compliant. We never sell your data, share your files, or use them for any purpose beyond completing your order.')

@section('content')
    <section class="page-header">
        <div class="container">
            <div>
                <span class="theme-badge">{{ $siteContext->displayLabel() }}</span>
                <h1>Privacy <span>Policy</span></h1>
                <p>We respect your privacy and are committed to protecting your personal data. This policy explains what we collect, why, and your rights under European and United States law.</p>
                <p style="font-size:0.9rem;opacity:0.75;margin-top:0.5rem;">Effective Date: May 1, 2025 &nbsp;&middot;&nbsp; Last Updated: May 2025</p>
                <div class="theme-header-actions">
                    <a class="button secondary" href="{{ url('/contact-us.php') }}">Contact Us</a>
                </div>
            </div>
        </div>
    </section>

    <section class="section">
        <div class="container">
            <div class="card">
                <div class="card-body prose professional-prose">

                    <h2>1. Who We Are</h2>
                    <p><strong>1 Dollar Digitizing</strong> ("we," "us," or "our") is a professional embroidery digitizing and vector art service operating globally, with offices in the United States, United Kingdom, and Pakistan. Our principal place of business is:</p>
                    <address style="font-style:normal;background:#f8fafc;border-left:3px solid #169fe6;padding:0.9rem 1.1rem;border-radius:0 8px 8px 0;font-size:0.95rem;color:#374151;margin:0.5rem 0 1rem;">
                        1 Dollar Digitizing<br>
                        46494 Mission Blvd, Fremont, California 94539, USA<br>
                        Email: {{ $siteContext->supportEmail ?: 'support@1dollardigitizing.com' }}<br>
                        Phone: +1 206-312-6446
                    </address>
                    <p>For the purposes of the General Data Protection Regulation (GDPR), we act as the <strong>Data Controller</strong> for personal data collected through this website and our services.</p>

                    <h2>2. What Information We Collect</h2>
                    <p>We collect information you provide directly and information generated automatically when you use our services.</p>

                    <h3>2.1 Information You Provide</h3>
                    <ul>
                        <li><strong>Account Information:</strong> Name, email address, phone number, company name, and password when you register.</li>
                        <li><strong>Order Information:</strong> Design specifications, garment type, machine format, stitch preferences, and any instructions you provide with an order or quote request.</li>
                        <li><strong>Artwork Files:</strong> Logo files, images, and design files you upload for digitizing or vector conversion.</li>
                        <li><strong>Billing Information:</strong> Billing address and payment method details. Card numbers are handled exclusively by our payment processors (Stripe and 2Checkout) and are never stored on our servers.</li>
                        <li><strong>Communications:</strong> Messages, emails, and support requests you send us.</li>
                    </ul>

                    <h3>2.2 Information Collected Automatically</h3>
                    <ul>
                        <li><strong>Usage Data:</strong> Pages visited, orders placed, files downloaded, login timestamps, and in-app activity.</li>
                        <li><strong>Device and Technical Data:</strong> IP address, browser type, operating system, and referring URLs — collected for security and fraud prevention.</li>
                        <li><strong>Cookies and Session Tokens:</strong> See Section 8 for full details.</li>
                    </ul>

                    <h2>3. Legal Basis for Processing (GDPR)</h2>
                    <p>If you are located in the European Economic Area (EEA) or United Kingdom, we process your personal data on the following legal grounds:</p>
                    <ul>
                        <li><strong>Contract Performance (Art. 6(1)(b) GDPR):</strong> Processing necessary to fulfill your orders, deliver completed files, process payments, and manage your account.</li>
                        <li><strong>Legitimate Interests (Art. 6(1)(f) GDPR):</strong> Fraud prevention, security monitoring, platform integrity, and improving our service — where these interests do not override your rights.</li>
                        <li><strong>Legal Obligation (Art. 6(1)(c) GDPR):</strong> Retaining transaction records to comply with tax, accounting, and financial regulations.</li>
                        <li><strong>Consent (Art. 6(1)(a) GDPR):</strong> Where we send optional marketing communications — you may withdraw consent at any time.</li>
                    </ul>

                    <h2>4. How We Use Your Information</h2>
                    <ul>
                        <li>To process orders, deliver digitized or vector files, and manage your account.</li>
                        <li>To communicate about quotes, order status, revisions, and support requests.</li>
                        <li>To process payments and issue receipts or invoices.</li>
                        <li>To detect and prevent fraud, abuse, and unauthorized access.</li>
                        <li>To comply with legal and regulatory obligations.</li>
                        <li>To improve our platform and service quality based on aggregate usage patterns.</li>
                        <li>To send transactional emails (order confirmations, file delivery, revision notices). We do not send unsolicited marketing without your explicit consent.</li>
                    </ul>

                    <h2>5. Ownership of Files and Intellectual Property</h2>
                    <p>This section is important. Please read it carefully.</p>

                    <h3>5.1 Your Files Remain Your Property</h3>
                    <p>All artwork files you upload, and all digitized or vector files we produce for you, <strong>remain your exclusive property</strong>. We claim no ownership, license, or intellectual property rights over your designs, logos, or the finished files we deliver to you. Uploading artwork to our platform does not transfer any rights to us.</p>

                    <h3>5.2 We Do Not Reproduce or Resell Your Files</h3>
                    <p>We will <strong>never reproduce, resell, redistribute, sublicense, or share your files</strong> with any third party for any commercial or non-commercial purpose. Your completed digitizing and vector files are created exclusively for your use. No file you upload or receive from us will be used in any sample library, portfolio (without your written consent), training dataset, or any other secondary use.</p>

                    <h3>5.3 Limited Internal Use Only</h3>
                    <p>We retain copies of your files solely to: (a) deliver your order, (b) process revision requests, (c) fulfill reorder requests, and (d) provide customer support. Files are stored securely and are accessible only to team members directly working on your account.</p>

                    <h3>5.4 Your Responsibility for Artwork Rights</h3>
                    <p>By submitting artwork to us for digitizing or vector conversion, you represent and warrant that:</p>
                    <ul>
                        <li>You are the owner of the artwork, or you have the lawful right, license, or authorization from the rights holder to reproduce and use the artwork for embroidery or printing purposes.</li>
                        <li>The artwork does not infringe any third-party copyright, trademark, or other intellectual property right.</li>
                        <li>You are legally permitted to use any logos, brand marks, or trademarked designs included in the artwork in the jurisdiction where the embroidered goods will be produced and sold.</li>
                    </ul>
                    <p>We are a digitizing service provider. We process files as instructed. We are not responsible for determining whether a submitted logo is licensed for use, and we cannot be held liable for infringement arising from artwork submitted by you. If you are unsure whether you have the right to use a particular logo or design, please consult the rights holder or a legal professional before submitting.</p>

                    <h2>6. Payments and Financial Data</h2>
                    <p>Payments are processed by <strong>Stripe</strong> and <strong>2Checkout (Verifone)</strong>. These processors are PCI-DSS compliant. We never receive, store, or transmit full credit card numbers. We retain transaction records (order amount, date, payment method type, last four digits if provided by the processor) for accounting and dispute resolution purposes, in accordance with applicable financial regulations.</p>

                    <h2>7. How We Share Your Information</h2>
                    <p>We do not sell your personal data. We share data only in limited circumstances:</p>
                    <ul>
                        <li><strong>Payment Processors:</strong> Stripe and 2Checkout receive billing information necessary to process your payment.</li>
                        <li><strong>Email Service Providers:</strong> We use SMTP email services to deliver transactional messages. These providers process your email address only to deliver messages on our behalf.</li>
                        <li><strong>Legal Requirements:</strong> We may disclose information if required by law, court order, or government authority, or to protect the rights, property, or safety of our users or the public.</li>
                        <li><strong>Business Transfers:</strong> If 1 Dollar Digitizing is involved in a merger, acquisition, or asset sale, your data may be transferred. We will notify you before this occurs and before your data becomes subject to a different privacy policy.</li>
                    </ul>
                    <p>We do not share your data with advertising networks, data brokers, or any third party for marketing purposes.</p>

                    <h2>8. Cookies and Tracking</h2>
                    <p>We use the following types of cookies:</p>
                    <ul>
                        <li><strong>Strictly Necessary Cookies:</strong> Session cookies that keep you logged in, protect form submissions from cross-site request forgery (CSRF), and enable core platform functionality. These cannot be disabled without breaking the site.</li>
                        <li><strong>Persistent Login Cookies:</strong> If you select "Remember Me," a longer-lived authentication token is stored in your browser. You can clear this at any time through your browser settings or by logging out.</li>
                        <li><strong>Security Cookies:</strong> Short-lived tokens used for two-factor authentication and device trust verification.</li>
                    </ul>
                    <p>We do not use advertising cookies, third-party tracking pixels, or behavioral profiling tools. You may clear all cookies at any time through your browser settings; doing so will log you out of the platform.</p>

                    <h2>9. Data Retention</h2>
                    <ul>
                        <li><strong>Account Data:</strong> Retained for the lifetime of your account plus 3 years after closure, or as required by applicable law.</li>
                        <li><strong>Order and Transaction Records:</strong> Retained for 7 years to comply with tax, accounting, and financial reporting obligations in the US, UK, and EU.</li>
                        <li><strong>Uploaded Artwork and Completed Files:</strong> Retained for a minimum of 12 months to support revision requests and reorders. You may request earlier deletion (subject to active order obligations) by contacting us.</li>
                        <li><strong>Security Logs:</strong> Login and access logs are retained for 12 months for fraud detection and incident response.</li>
                        <li><strong>Support Communications:</strong> Retained for 3 years to maintain a complete account history.</li>
                    </ul>

                    <h2>10. Data Security</h2>
                    <p>We implement appropriate technical and organizational measures to protect your personal data, including:</p>
                    <ul>
                        <li>HTTPS/TLS encryption for all data in transit.</li>
                        <li>Access controls limiting file and account access to authorized team members only.</li>
                        <li>Login rate limiting, brute-force detection, and IP-based blocking.</li>
                        <li>Optional two-factor authentication (TOTP) for all accounts.</li>
                        <li>Security event logging and anomaly detection.</li>
                    </ul>
                    <p>No method of transmission over the internet is 100% secure. In the event of a data breach that affects your personal data, we will notify you as required under applicable law (within 72 hours to supervisory authorities under GDPR, and as required by applicable US state breach notification laws).</p>

                    <h2>11. Your Rights — European Users (GDPR)</h2>
                    <p>If you are located in the EEA or United Kingdom, you have the following rights under the GDPR and UK GDPR:</p>
                    <ul>
                        <li><strong>Right of Access (Art. 15):</strong> Request a copy of the personal data we hold about you.</li>
                        <li><strong>Right to Rectification (Art. 16):</strong> Request correction of inaccurate or incomplete data.</li>
                        <li><strong>Right to Erasure / "Right to be Forgotten" (Art. 17):</strong> Request deletion of your personal data, subject to our legal retention obligations.</li>
                        <li><strong>Right to Restriction of Processing (Art. 18):</strong> Request that we limit how we use your data in certain circumstances.</li>
                        <li><strong>Right to Data Portability (Art. 20):</strong> Receive your data in a structured, machine-readable format and transfer it to another controller.</li>
                        <li><strong>Right to Object (Art. 21):</strong> Object to processing based on legitimate interests or for direct marketing purposes.</li>
                        <li><strong>Right to Withdraw Consent:</strong> Where processing is based on consent, withdraw it at any time without affecting the lawfulness of prior processing.</li>
                        <li><strong>Right to Lodge a Complaint:</strong> You have the right to lodge a complaint with your local data protection authority. In the UK: the Information Commissioner's Office (ICO) at ico.org.uk. In the EU: your national supervisory authority.</li>
                    </ul>

                    <h2>12. Your Rights — California and US Users (CCPA/CPRA)</h2>
                    <p>If you are a California resident, the California Consumer Privacy Act (CCPA) and California Privacy Rights Act (CPRA) grant you the following rights:</p>
                    <ul>
                        <li><strong>Right to Know:</strong> Request disclosure of the categories and specific pieces of personal information we have collected about you, the sources, the business purpose, and the categories of third parties with whom we share it.</li>
                        <li><strong>Right to Delete:</strong> Request deletion of personal information we have collected, subject to certain exceptions (e.g., completing transactions, legal obligations).</li>
                        <li><strong>Right to Correct:</strong> Request correction of inaccurate personal information.</li>
                        <li><strong>Right to Opt-Out of Sale or Sharing:</strong> We do not sell or share personal information for cross-context behavioral advertising. No opt-out is required, but you may contact us to confirm.</li>
                        <li><strong>Right to Limit Use of Sensitive Personal Information:</strong> We do not use sensitive personal information beyond what is necessary to provide our services.</li>
                        <li><strong>Right to Non-Discrimination:</strong> We will not discriminate against you for exercising any of your CCPA rights.</li>
                    </ul>
                    <p>Residents of other US states with applicable privacy laws (Virginia CDPA, Colorado CPA, Connecticut CTDPA, etc.) may exercise equivalent rights by contacting us directly.</p>

                    <h2>13. International Data Transfers</h2>
                    <p>1 Dollar Digitizing operates across the United States, United Kingdom, and Pakistan. If you are located in the EEA or UK, your personal data may be transferred to and processed in countries outside the EEA/UK, including the United States and Pakistan, which may not have equivalent data protection laws. When we transfer data internationally, we rely on:</p>
                    <ul>
                        <li>Standard Contractual Clauses (SCCs) approved by the European Commission, where applicable.</li>
                        <li>The UK International Data Transfer Agreement (IDTA), where applicable.</li>
                        <li>Appropriate organizational safeguards with our team members and processors.</li>
                    </ul>

                    <h2>14. Children's Privacy</h2>
                    <p>Our services are intended for business and commercial users. We do not knowingly collect personal data from anyone under the age of 16. If we become aware that a minor has provided us with personal information, we will delete it promptly. If you believe a child has submitted data to us, please contact us immediately.</p>

                    <h2>15. How to Exercise Your Rights</h2>
                    <p>To exercise any of the rights described in this policy, or to ask questions about how your data is handled, contact us by any of the following methods:</p>
                    <ul>
                        <li><strong>Email:</strong> {{ $siteContext->supportEmail ?: 'support@1dollardigitizing.com' }}</li>
                        <li><strong>Phone:</strong> +1 206-312-6446</li>
                        <li><strong>Mail:</strong> 1 Dollar Digitizing, 46494 Mission Blvd, Fremont, California 94539, USA</li>
                        <li><strong>Online:</strong> <a href="{{ url('/contact-us.php') }}" class="inline-link">Contact Form</a></li>
                    </ul>
                    <p>We will respond to verifiable requests within 30 days (or within the timeframe required by applicable law). We may need to verify your identity before processing a request.</p>

                    <h2>16. Changes to This Policy</h2>
                    <p>We may update this Privacy Policy from time to time. When we make material changes, we will update the "Last Updated" date at the top of this page and notify registered users by email. Continued use of our services after the effective date of any update constitutes acceptance of the revised policy. We encourage you to review this page periodically.</p>

                </div>
            </div>
        </div>
    </section>

    <style>
        .professional-prose h2 {
            color: #182a3e;
            margin-top: 1.75rem;
            margin-bottom: 0.4rem;
            font-size: 1.18rem;
            border-bottom: 1px solid #e2e8f0;
            padding-bottom: 0.4rem;
        }

        .professional-prose h3 {
            color: #1e3a5f;
            font-size: 1rem;
            margin-top: 1rem;
            margin-bottom: 0.25rem;
        }

        .professional-prose p,
        .professional-prose li {
            color: #526071;
            line-height: 1.8;
        }

        .professional-prose ul {
            padding-left: 1.4rem;
            margin: 0.5rem 0 1rem;
            display: flex;
            flex-direction: column;
            gap: 0.35rem;
        }

        .professional-prose {
            display: grid;
            gap: 6px;
        }

        .professional-prose > h2:first-child {
            margin-top: 0;
        }

        address {
            display: block;
        }
    </style>
@endsection
