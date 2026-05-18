<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderWorkflowMeta extends Model
{
    protected $table = 'order_workflow_meta';

    protected $primaryKey = 'id';

    public $timestamps = false;

    protected $fillable = [
        'order_id',
        'site_id',
        'created_source',
        'historical_backfill',
        'suppress_customer_notifications',
        'delivery_override',
        'order_credit_limit',
        'created_by_user_id',
        'created_by_name',
        'date_added',
        'date_modified',
        'end_date',
        'deleted_by',
    ];

    public function scopeActive($query)
    {
        return $query->whereNull('end_date');
    }
}
