<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteDomain extends Model
{
    protected $table = 'site_domains';

    public $timestamps = false;

    protected $fillable = [
        'site_id',
        'host',
        'is_primary',
        'is_active',
        'created_at',
        'updated_at',
    ];

    public function site()
    {
        return $this->belongsTo(Site::class, 'site_id');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', 1);
    }

    public function scopePrimary($query)
    {
        return $query->where('is_primary', 1);
    }
}
