<?php

namespace App\Models;

use App\Support\LegacyDate;
use Illuminate\Database\Eloquent\Model;

class Attachment extends Model
{
    protected $table = 'attach_files';

    protected $primaryKey = 'id';

    public $timestamps = false;

    protected $fillable = [
        'order_id',
        'file_name',
        'file_name_with_date',
        'file_name_with_order_id',
        'file_source',
        'date_added',
        'end_date',
        'deleted_by',
    ];

    public function getDateAddedAttribute(mixed $value): ?string
    {
        return LegacyDate::normalize($value);
    }
}
