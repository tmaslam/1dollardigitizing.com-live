<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FreelancePaymentRequest extends Model
{
    protected $table = 'freelance_payment_requests';

    protected $fillable = [
        'freelancer_id',
        'status',
        'requested_at',
        'paid_at',
        'paid_by',
        'amount_pkr',
        'notes',
    ];

    protected $casts = [
        'requested_at' => 'datetime',
        'paid_at'      => 'datetime',
        'amount_pkr'   => 'decimal:2',
    ];

    public function freelancer(): BelongsTo
    {
        return $this->belongsTo(AdminUser::class, 'freelancer_id', 'user_id');
    }

    public function paidBy(): BelongsTo
    {
        return $this->belongsTo(AdminUser::class, 'paid_by', 'user_id');
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }
}
