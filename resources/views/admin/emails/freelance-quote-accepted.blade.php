<p style="margin-top:0;">Hello {{ $freelancerName }},</p>
<p>Great news! Your price quote has been accepted. The order has been assigned to you and is ready to start.</p>
<p><strong>Order ID:</strong> {{ $orderId }}</p>
<p><strong>Accepted Price:</strong> PKR {{ $quotedPrice }}</p>
<p style="margin:24px 0;">
    <a href="{{ $detailUrl }}" style="display:inline-block;padding:12px 20px;background:#0f5f66;color:#ffffff !important;border-radius:8px;text-decoration:none;font-weight:700;">Open Order</a>
</p>
<p style="margin:0 0 18px;">
    If the button does not work, open this link in your browser:<br>
    <a href="{{ $detailUrl }}" style="word-break:break-all;">{{ $detailUrl }}</a>
</p>
<p style="margin-bottom:0;">Please log in to your team portal and complete the work as soon as possible.</p>
