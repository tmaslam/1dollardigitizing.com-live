<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SitePricingProfile extends Model
{
    protected $table = 'site_pricing_profiles';

    public $timestamps = false;

    protected $fillable = [
        'site_id',
        'profile_name',
        'work_type',
        'turnaround_code',
        'pricing_mode',
        'fixed_price',
        'per_thousand_rate',
        'minimum_charge',
        'included_units',
        'overage_rate',
        'package_name',
        'config_json',
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
}
