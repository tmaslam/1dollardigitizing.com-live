<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TeamFine extends Model
{
    protected $table = 'team_fines';

    protected $fillable = [
        'order_id',
        'team_user_id',
        'imposed_by',
        'amount',
        'reason',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'order_id', 'order_id');
    }

    public function teamUser(): BelongsTo
    {
        return $this->belongsTo(AdminUser::class, 'team_user_id', 'user_id');
    }

    public function imposedBy(): BelongsTo
    {
        return $this->belongsTo(AdminUser::class, 'imposed_by', 'user_id');
    }
}
