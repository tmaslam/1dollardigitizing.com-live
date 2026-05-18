<p style="margin-top:0;">Hello {{ $teamMemberName }},</p>
<p>Your completed work on Order #{{ $orderId }} has been reviewed and sent back for revision by the supervisor.</p>
<p><strong>Order ID:</strong> {{ $orderId }}</p>
<p><strong>Design Name:</strong> {{ $designName }}</p>
<p><strong>Reason:</strong></p>
<blockquote style="margin:8px 0 16px;padding:10px 16px;border-left:3px solid #e2e8f0;color:#4a5568;">{{ $reason }}</blockquote>
<p style="margin:24px 0;">
    <a href="{{ $detailUrl }}" style="display:inline-block;padding:12px 20px;background:#0f5f66;color:#ffffff !important;border-radius:8px;text-decoration:none;font-weight:700;">Open Order</a>
</p>
<p style="margin:0 0 18px;">
    If the button does not work, open this link in your browser:<br>
    <a href="{{ $detailUrl }}" style="word-break:break-all;">{{ $detailUrl }}</a>
</p>
<p style="margin-bottom:0;">Please make the necessary corrections and resubmit the work.</p>
