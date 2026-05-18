<?php

namespace App\Models;

use App\Support\LegacyDate;
use App\Support\LegacyQuerySupport;
use Illuminate\Database\Eloquent\Model;

class CustomerCreditLedger extends Model
{
    protected $table = 'customer_credit_ledger';

    protected $primaryKey = 'id';

    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'billing_id',
        'order_id',
        'website',
        'site_id',
        'entry_type',
        'amount',
        'reference_no',
        'notes',
        'created_by',
        'date_added',
        'end_date',
        'deleted_by',
    ];

    public function site()
    {
        return $this->belongsTo(Site::class, 'site_id');
    }

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'order_id');
    }

    public function scopeActive($query)
    {
        return LegacyQuerySupport::applyActiveEndDate($query, $this->getTable());
    }

    public function scopeForWebsite($query, ?string $website)
    {
        if (! $website) {
            return $query;
        }

        $website = trim((string) $website);
        $primaryWebsite = (string) config('sites.primary_legacy_key', '1dollar');

        return $query->where(function ($siteQuery) use ($website, $primaryWebsite) {
            $siteQuery->where('website', $website);

            if (strcasecmp($website, $primaryWebsite) === 0) {
                $siteQuery
                    ->orWhereNull('website')
                    ->orWhere('website', '');
            }
        });
    }

    public function getDateAddedAttribute(mixed $value): ?string
    {
        return LegacyDate::normalize($value);
    }
}
