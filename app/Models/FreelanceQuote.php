<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FreelanceQuote extends Model
{
    protected $table = 'freelance_quotes';

    protected $fillable = [
        'order_id',
        'team_user_id',
        'quoted_price',
        'notes',
        'status',
        'reviewed_by',
        'reviewed_at',
    ];

    protected $casts = [
        'quoted_price' => 'decimal:2',
        'reviewed_at'  => 'datetime',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'order_id', 'order_id');
    }

    public function teamUser(): BelongsTo
    {
        return $this->belongsTo(AdminUser::class, 'team_user_id', 'user_id');
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isAccepted(): bool
    {
        return $this->status === 'accepted';
    }
}
