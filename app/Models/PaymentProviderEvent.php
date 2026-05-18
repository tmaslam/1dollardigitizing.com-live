<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentProviderEvent extends Model
{
    protected $table = 'payment_provider_events';

    public $timestamps = false;

    protected $fillable = [
        'site_id',
        'payment_transaction_id',
        'provider',
        'event_type',
        'event_reference',
        'status',
        'payload',
        'received_at',
        'processed_at',
    ];

    public function site()
    {
        return $this->belongsTo(Site::class, 'site_id');
    }

    public function paymentTransaction()
    {
        return $this->belongsTo(PaymentTransaction::class, 'payment_transaction_id');
    }
}
