<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BlockIp extends Model
{
    protected $table = 'block_ip';

    protected $primaryKey = 'id';

    public $timestamps = false;

    protected $fillable = [
        'ipaddress',
        'date_added',
        'end_date',
        'deleted_by',
    ];
}
