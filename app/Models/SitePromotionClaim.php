<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SitePromotionClaim extends Model
{
    protected $table = 'site_promotion_claims';

    public $timestamps = false;

    protected $fillable = [
        'site_id',
        'site_promotion_id',
        'user_id',
        'website',
        'status',
        'verification_required',
        'verified_at',
        'payment_required',
        'required_payment_amount',
        'credit_amount',
        'first_order_flat_amount',
        'offer_snapshot_json',
        'payment_transaction_id',
        'payment_reference',
        'paid_at',
        'redeemed_order_id',
        'redeemed_at',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'verification_required' => 'bool',
        'payment_required' => 'bool',
    ];

    public function site()
    {
        return $this->belongsTo(Site::class, 'site_id');
    }

    public function promotion()
    {
        return $this->belongsTo(SitePromotion::class, 'site_promotion_id');
    }

    public function customer()
    {
        return $this->belongsTo(AdminUser::class, 'user_id', 'user_id');
    }

    public function paymentTransaction()
    {
        return $this->belongsTo(PaymentTransaction::class, 'payment_transaction_id');
    }

    public function redeemedOrder()
    {
        return $this->belongsTo(Order::class, 'redeemed_order_id', 'order_id');
    }
}
