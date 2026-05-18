@extends('public.layout')

@section('title', 'Terms and Conditions | '.$siteContext->displayLabel())
@section('meta_description', 'Terms and Conditions for 1 Dollar Digitizing. Covers order acceptance, payment, turnaround, revisions, file ownership, refunds, and limitations of liability.')

@section('content')
    <section class="page-header">
        <div class="container">
            <div>
                <span class="theme-badge">{{ $siteContext->displayLabel() }}</span>
                <h1>Terms And <span>Conditions</span></h1>
                <p>These terms govern your use of this website and any services you order from us. Please read them before placing an order. Using the site means you accept these terms.</p>
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

                    <p class="terms-effective">Last updated: May 2025. These terms apply to all orders, accounts, and use of this website.</p>

                    <h2>1. Acceptance of Terms</h2>
                    <p>By accessing this website, creating an account, submitting a quote request, uploading artwork, or placing an order, you confirm that you have read and agree to these Terms and Conditions in full. If you do not agree, you may not use this website or purchase any services from us.</p>
                    <p>These terms form a binding agreement between you ("Customer") and 1 Dollar Digitizing ("we," "us," or "our"), operated from Fremont, California, United States. We reserve the right to update these terms at any time. Changes take effect immediately upon posting to this page. Continued use of the site after changes constitutes acceptance of the revised terms.</p>

                    <h2>2. Eligibility and Account Registration</h2>
                    <p>You must be at least 18 years old to create an account or place an order. By using this site, you represent that you meet this requirement and that all information you provide is accurate, current, and complete.</p>
                    <p>You are responsible for maintaining the confidentiality of your account login credentials. All activity performed through your account is your responsibility. If you believe your account has been accessed without your authorization, contact us immediately at <a href="{{ url('/contact-us.php') }}">our contact page</a>. We are not liable for losses resulting from unauthorized account access that occurs through no fault of our own.</p>
                    <p>We reserve the right to decline account registrations, suspend accounts, or refuse service at our discretion, including where we have reason to believe submitted information is false or where an account has been associated with prior abuse of our services.</p>

                    <h2>3. Services</h2>
                    <p>1 Dollar Digitizing provides the following services:</p>
                    <ul>
                        <li><strong>Embroidery Digitizing</strong> — converting customer-supplied artwork into machine-readable stitch files in formats including DST, PES, EXP, VP3, JEF, XXX, HUS, SEW, and others.</li>
                        <li><strong>3D Puff Embroidery Digitizing</strong> — digitizing files designed for foam-raised embroidery on structured garments and caps.</li>
                        <li><strong>Applique Embroidery Digitizing</strong> — digitizing files with placement, tack-down, and border stitch sequences for fabric-on-fabric embroidery.</li>
                        <li><strong>Chain Stitch Embroidery Digitizing</strong> — stitch files formatted for single-needle chain stitch machines.</li>
                        <li><strong>Photo Digitizing</strong> — conversion of photographs and detailed artwork into embroidery-ready stitch files.</li>
                        <li><strong>Vector Art Conversion</strong> — manual redraw of raster images, logos, and sketches into professional vector files in AI, EPS, SVG, or PDF format.</li>
                    </ul>
                    <p>All services are provided on a per-order basis. Purchasing one service does not entitle you to any other service without a separate order and payment.</p>

                    <h2>4. Quote Process and Order Acceptance</h2>
                    <p>Submitting a quote request does not constitute a purchase. A quote is an estimate based on the artwork, size, machine type, and turnaround options you provide. Quotes are typically issued within a few hours during business hours.</p>
                    <p>An order is accepted and work begins only after you have approved the quote and payment has been successfully processed. If the artwork you submit differs materially from what was described in your quote request — different complexity, size, stitch type, or format requirements — we reserve the right to revise the quoted price before beginning work. You will be notified of any change before we proceed.</p>
                    <p>Quotes are valid for 7 days from the date issued. After that period, a new quote may be required if pricing or workload conditions have changed.</p>

                    <h2>5. Pricing</h2>
                    <p>Our standard pricing is as follows:</p>
                    <ul>
                        <li><strong>Standard digitizing:</strong> $1.00 per 1,000 stitches — minimum charge $6.00</li>
                        <li><strong>Priority (12-hour) digitizing:</strong> $1.50 per 1,000 stitches — minimum charge $9.00</li>
                        <li><strong>Super Rush (8-hour) digitizing:</strong> $2.00 per 1,000 stitches — minimum charge $12.00</li>
                        <li><strong>Vector art conversion:</strong> $6.00 per hour (standard), higher rates apply for Priority and Super Rush</li>
                        <li><strong>Extra setups</strong> (additional format, slight size change of an existing file): $5.00 per setup</li>
                        <li><strong>3D Puff digitizing:</strong> included at standard rate — no extra charge</li>
                        <li><strong>Chain stitch digitizing:</strong> $1.50 per 1,000 stitches — minimum $6.00</li>
                    </ul>
                    <p>Prices are listed in US dollars and are subject to change. The price applicable to your order is the price quoted and agreed upon at time of order approval. No additional charges will be added after you have approved a quote without your explicit consent.</p>

                    <h2>6. Payment</h2>
                    <p>Payment is due at the time of order placement and must be received before work begins. We accept Visa, MasterCard, American Express, Discover, and PayPal. Payments are processed through third-party providers including Stripe Checkout and 2Checkout. We do not store full credit card details on our servers.</p>
                    <p>All transactions are in US dollars. Your card issuer or PayPal may apply currency conversion fees if your account is held in a different currency — these charges are outside our control and are not refundable by us.</p>
                    <p>If a payment fails or is reversed after work has been completed and files have been delivered, we reserve the right to suspend your account and pursue recovery of the outstanding amount through appropriate channels.</p>
                    <p>We do not offer credit terms or deferred billing. Payment in full is required prior to file delivery in all cases.</p>

                    <h2>7. Turnaround Times</h2>
                    <p>Turnaround times are measured from the point at which payment is confirmed and all necessary artwork, specifications, and instructions have been received — not from initial contact or quote request.</p>
                    <ul>
                        <li><strong>Standard:</strong> 24 hours</li>
                        <li><strong>Priority:</strong> 12 hours</li>
                        <li><strong>Super Rush:</strong> 8 hours</li>
                    </ul>
                    <p>These turnaround times represent our standard commitment and are met on the overwhelming majority of orders. However, they are not guaranteed delivery windows. Factors outside our control — including incomplete or unclear artwork, unusually high order volume, or technical issues — may affect delivery timing. In such cases, we will notify you promptly and provide an updated estimate.</p>
                    <p>Rush surcharges are non-refundable if a delay is caused by late or incomplete submission of artwork, unclear instructions, or failure to respond to clarification requests in a timely manner.</p>

                    <h2>8. File Delivery and Formats</h2>
                    <p>Completed files are delivered to your account or via email in the format(s) you specified at time of order. Standard delivery formats include DST, PES, EXP, VP3, JEF, XXX, HUS, SEW, and others on request. Vector art is delivered in AI, EPS, SVG, or PDF as specified.</p>
                    <p>A stitch simulation or preview may be provided for review before the final file is released. You are responsible for reviewing the preview and raising any concerns before approving the file for final delivery. Approval of the preview constitutes acceptance of the design as shown.</p>
                    <p>We store your completed design files for a period of one year from the order date. After this period, files may be removed without notice. We do not guarantee permanent storage of any uploaded or completed files.</p>
                    <p>We are not responsible for files that are corrupted, lost, or inaccessible due to issues with your email provider, spam filters, or local storage. If a file is not received, contact us and we will resend it at no charge provided the original order is still within the one-year retention window.</p>

                    <h2>9. Revisions Policy</h2>
                    <p>We offer free revisions on all orders where the delivered file does not match the approved specification, or where a production issue can be attributed to an error in our digitizing. To request a revision, provide:</p>
                    <ul>
                        <li>A description of the specific issue</li>
                        <li>The area of the design where the issue occurs</li>
                        <li>A photograph of the failed stitch-out, if available</li>
                        <li>Your machine type and fabric details</li>
                    </ul>
                    <p>Revisions are not free in the following situations:</p>
                    <ul>
                        <li>You have changed the design, size, color count, or format requirements after approval</li>
                        <li>The issue is caused by machine settings, needle condition, thread quality, or stabilizer choice</li>
                        <li>The original artwork was low resolution or unclear, and the result reflects reasonable interpretation of that artwork</li>
                        <li>You are requesting a style change rather than a correction</li>
                    </ul>
                    <p>Revision requests must be submitted within 30 days of the original delivery date. After 30 days, revisions will be treated as new orders and quoted accordingly.</p>

                    <h2>10. Refund and Cancellation Policy</h2>
                    <p>Because our services involve skilled labor applied to your specific artwork from the moment an order is confirmed, we do not offer refunds once work has begun.</p>
                    <p>If you cancel an order before any work has started, you may receive a full refund. To cancel, contact us immediately after placing the order — cancellations are only possible before a digitizer has been assigned to your file.</p>
                    <p>If a file is delivered and you believe it is fundamentally unusable due to an error on our part, contact us. We will review the case and, at our sole discretion, may offer a credit, a re-digitize at no charge, or a partial refund. Refunds are not issued where the issue is caused by incorrect specifications provided by the customer, machine settings, or production conditions outside the file itself.</p>
                    <p>Refunds, where applicable, are issued to the original payment method within 5–10 business days.</p>

                    <h2>11. Intellectual Property and Artwork Ownership</h2>
                    <p>By submitting artwork to us, you represent and warrant that:</p>
                    <ul>
                        <li>You own the submitted artwork, or have explicit written permission from the copyright holder to reproduce it in embroidery form</li>
                        <li>The artwork does not infringe any third-party intellectual property rights, including trademarks, copyrights, or design rights</li>
                        <li>You have the legal right to use any logos, characters, imagery, or branding included in the artwork</li>
                    </ul>
                    <p>We do not verify ownership of submitted artwork. Responsibility for ensuring artwork is cleared for use rests entirely with the customer. We will not be held liable for any intellectual property claims arising from artwork submitted by customers.</p>
                    <p>The digitized stitch file we create from your artwork is prepared for your use only. You may not resell, redistribute, or sub-license our digitized output without our express written consent. The file format and stitch data remain our proprietary work product; the underlying design rights remain with the artwork owner.</p>
                    <p>We reserve the right to decline any order where we have reasonable cause to believe the submitted artwork infringes a third-party's intellectual property rights.</p>

                    <h2>12. Customer Responsibilities</h2>
                    <p>You are responsible for:</p>
                    <ul>
                        <li>Providing clear, complete, and accurate artwork and order specifications</li>
                        <li>Specifying the correct machine format, garment type, and placement dimensions before work begins</li>
                        <li>Reviewing preview files promptly and communicating any required changes before approving final delivery</li>
                        <li>Ensuring your machine, thread, stabilizer, and fabric are appropriate for the design and garment being embroidered</li>
                        <li>Testing delivered files on a sample garment before committing to a full production run</li>
                    </ul>
                    <p>We are not responsible for production losses, wasted garments, or missed deadlines that result from failure to test files before production, or from incorrect specifications provided at time of order.</p>

                    <h2>13. Prohibited Use</h2>
                    <p>You may not use this website or our services to:</p>
                    <ul>
                        <li>Submit artwork that infringes copyright, trademark, or other intellectual property rights</li>
                        <li>Upload malicious files, viruses, or content intended to damage our systems or those of other users</li>
                        <li>Attempt to gain unauthorized access to any account, system, or data</li>
                        <li>Use automated tools to scrape, access, or interact with this site in ways not intended for normal user access</li>
                        <li>Submit false information, fraudulent payment details, or impersonate another person or entity</li>
                        <li>Use our services for any purpose that is unlawful under applicable federal, state, or local law</li>
                    </ul>
                    <p>Violation of these prohibitions may result in immediate account termination, reporting to relevant authorities, and pursuit of any available legal remedies.</p>

                    <h2>14. Limitation of Liability</h2>
                    <p>To the fullest extent permitted by applicable law, 1 Dollar Digitizing and its operators, employees, and contractors shall not be liable for:</p>
                    <ul>
                        <li>Any indirect, incidental, consequential, or punitive damages arising from use of our services</li>
                        <li>Loss of profits, production time, business opportunity, or revenue resulting from file delivery delays, production failures, or design issues</li>
                        <li>Damage to garments, materials, or equipment resulting from use of our files</li>
                        <li>Any loss or damage arising from unauthorized account access not caused by our negligence</li>
                    </ul>
                    <p>Our total liability to you for any claim arising out of or related to these terms or any order shall not exceed the amount you paid for the specific order giving rise to the claim.</p>
                    <p>Some jurisdictions do not allow the exclusion or limitation of certain liabilities. Where applicable law prevents full exclusion, our liability is limited to the greatest extent permitted.</p>

                    <h2>15. Dispute Resolution</h2>
                    <p>If you have a complaint about a service or order, contact us first. Most issues can be resolved quickly through our revision and support process. Send a description of the issue to our support email and we will respond within one business day.</p>
                    <p>If a dispute cannot be resolved through direct communication, the parties agree to attempt resolution through informal mediation before initiating any legal proceedings. These terms are governed by the laws of the State of California, United States, without regard to conflict of law principles. Any legal proceedings shall be conducted in the appropriate courts located in Alameda County, California.</p>

                    <h2>16. Privacy</h2>
                    <p>Our collection and use of personal information is governed by our <a href="{{ url('/privacy-policy.php') }}">Privacy Policy</a>, which is incorporated into these terms by reference. By using this site, you also agree to the terms of our Privacy Policy.</p>

                    <h2>17. Severability</h2>
                    <p>If any provision of these terms is found to be unenforceable or invalid under applicable law, that provision will be limited or removed to the minimum extent necessary, and the remaining terms will continue in full force and effect.</p>

                    <h2>18. Entire Agreement</h2>
                    <p>These Terms and Conditions, together with our Privacy Policy and any order-specific quotes or agreements, constitute the entire agreement between you and 1 Dollar Digitizing with respect to your use of this website and our services. They supersede all prior understandings, communications, or agreements on the same subject matter.</p>

                    <h2>19. Contact</h2>
                    <p>Questions about these terms or your account can be sent to us at:</p>
                    <ul>
                        @if ($siteContext->companyAddress)<li><strong>Address:</strong> {{ $siteContext->companyAddress }}</li>@endif
                        @if ($siteContext->phoneNumber)<li><strong>Phone:</strong> <a href="tel:{{ $siteContext->phoneForTel() }}">{{ $siteContext->phoneNumber }}</a></li>@endif
                        @if ($siteContext->supportEmail)
                            <li><strong>Email:</strong> <a href="mailto:{{ $siteContext->supportEmail }}">{{ $siteContext->supportEmail }}</a></li>
                        @endif
                    </ul>

                </div>
            </div>
        </div>
    </section>

    <style>
        .professional-prose h2 {
            color: #182a3e;
            margin-top: 28px;
            margin-bottom: 10px;
            font-size: 1.14rem;
            padding-bottom: 8px;
            border-bottom: 1px solid rgba(22, 159, 230, 0.12);
        }

        .professional-prose h2:first-of-type {
            margin-top: 8px;
        }

        .professional-prose p {
            color: #526071;
            line-height: 1.78;
            margin: 0 0 10px;
        }

        .professional-prose ul {
            margin: 6px 0 14px;
            padding-left: 22px;
            color: #526071;
            line-height: 1.78;
        }

        .professional-prose ul li {
            margin-bottom: 5px;
        }

        .professional-prose a {
            color: #169fe6;
            text-decoration: none;
        }

        .professional-prose a:hover {
            text-decoration: underline;
        }

        .professional-prose {
            display: grid;
            gap: 4px;
        }

        .terms-effective {
            font-size: 0.86rem;
            color: #7a8fa6;
            padding: 10px 14px;
            background: rgba(22, 159, 230, 0.05);
            border-left: 3px solid rgba(22, 159, 230, 0.30);
            border-radius: 4px;
            margin-bottom: 8px;
        }
    </style>
@endsection
