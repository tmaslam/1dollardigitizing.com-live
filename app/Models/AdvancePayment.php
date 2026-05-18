<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdvancePayment extends Model
{
    protected $table = 'advancepayment';

    protected $primaryKey = 'id';

    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'order_id',
        'advance_pay',
        'status',
        'date_added',
        'end_date',
        'deleted_by',
    ];
}
