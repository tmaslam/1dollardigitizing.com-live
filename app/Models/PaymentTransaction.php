<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentTransaction extends Model
{
    protected $table = 'payment_transactions';

    public $timestamps = false;

    protected $fillable = [
        'site_id',
        'user_id',
        'order_id',
        'billing_id',
        'legacy_website',
        'provider',
        'provider_transaction_id',
        'merchant_reference',
        'payment_scope',
        'status',
        'currency',
        'requested_amount',
        'confirmed_amount',
        'redirect_url',
        'return_url',
        'failure_reason',
        'provider_payload',
        'reconciled_at',
        'created_at',
        'updated_at',
    ];

    public function site()
    {
        return $this->belongsTo(Site::class, 'site_id');
    }

    public function customer()
    {
        return $this->belongsTo(AdminUser::class, 'user_id', 'user_id');
    }

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'order_id');
    }

    public function items()
    {
        return $this->hasMany(PaymentTransactionItem::class, 'payment_transaction_id');
    }
}
