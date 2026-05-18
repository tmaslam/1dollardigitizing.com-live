<?php

namespace App\Models;

use App\Support\LegacyDate;
use Illuminate\Database\Eloquent\Model;

class OrderComment extends Model
{
    protected $table = 'comments';

    protected $primaryKey = 'id';

    public $timestamps = false;

    protected $fillable = [
        'order_id',
        'comment_source',
        'comments',
        'date_added',
        'end_date',
        'deleted_by',
    ];

    public function getDateAddedAttribute(mixed $value): ?string
    {
        return LegacyDate::normalize($value);
    }

    public function getDateModifiedAttribute(mixed $value): ?string
    {
        return LegacyDate::normalize($value);
    }
}
