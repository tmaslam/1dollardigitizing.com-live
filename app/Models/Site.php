<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Site extends Model
{
    protected $table = 'sites';

    public $timestamps = false;

    protected $fillable = [
        'legacy_key',
        'slug',
        'name',
        'brand_name',
        'primary_domain',
        'website_address',
        'active_payment_provider',
        'support_email',
        'from_email',
        'timezone',
        'pricing_strategy',
        'is_primary',
        'is_active',
        'settings_json',
        'created_at',
        'updated_at',
        'phone_number',
        'company_address',
        'uk_phone_number',
        'uk_address',
        'pk_phone_number',
        'pk_address',
    ];

    public function domains()
    {
        return $this->hasMany(SiteDomain::class, 'site_id');
    }

    public function pricingProfiles()
    {
        return $this->hasMany(SitePricingProfile::class, 'site_id');
    }

    public function promotions()
    {
        return $this->hasMany(SitePromotion::class, 'site_id');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', 1);
    }

    public function scopePrimary($query)
    {
        return $query->where('is_primary', 1);
    }

    public function scopeLegacyKey($query, string $legacyKey)
    {
        return $query->where('legacy_key', $legacyKey);
    }
}
