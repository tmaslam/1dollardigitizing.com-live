<p style="margin-top:0;">Hello Admin,</p>
<p>A freelancer has submitted a payment withdrawal request. Please log in to review and process it.</p>
<p><strong>Freelancer:</strong> {{ $freelancerName }}</p>
<p><strong>Requested Amount:</strong> PKR {{ number_format($amountPkr, 2) }}</p>
<p style="margin:24px 0;">
    <a href="{{ $reviewUrl }}" style="display:inline-block;padding:12px 20px;background:#0f5f66;color:#ffffff !important;border-radius:8px;text-decoration:none;font-weight:700;">Review Payment Request</a>
</p>
<p style="margin:0 0 18px;">
    If the button does not work, open this link in your browser:<br>
    <a href="{{ $reviewUrl }}" style="word-break:break-all;">{{ $reviewUrl }}</a>
</p>
