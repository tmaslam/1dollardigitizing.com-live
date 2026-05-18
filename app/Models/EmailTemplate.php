<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmailTemplate extends Model
{
    protected $table = 'email_templates';

    public $timestamps = false;

    protected $fillable = [
        'site_id',
        'template_name',
        'subject',
        'body',
        'is_active',
        'created_by',
        'updated_by',
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
