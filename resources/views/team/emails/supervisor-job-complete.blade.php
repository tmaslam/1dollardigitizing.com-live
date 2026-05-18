<p style="margin-top:0;">Hello {{ $supervisorName }},</p>
<p>A team member has completed a job and it is now waiting for your review and approval before it goes to admin.</p>
<p><strong>Completed By:</strong> {{ $teamMemberName }}</p>
<p><strong>Order ID:</strong> {{ $orderId }}</p>
<p><strong>Design Name:</strong> {{ $designName }}</p>
<p style="margin:24px 0;">
    <a href="{{ $reviewUrl }}" style="display:inline-block;padding:12px 20px;background:#0f5f66;color:#ffffff !important;border-radius:8px;text-decoration:none;font-weight:700;">Open Review Queue</a>
</p>
<p style="margin:0 0 18px;">
    If the button does not work, open this link in your browser:<br>
    <a href="{{ $reviewUrl }}" style="word-break:break-all;">{{ $reviewUrl }}</a>
</p>
<p style="margin-bottom:0;">Please log in to your supervisor portal to approve, disapprove, or issue a fine.</p>
