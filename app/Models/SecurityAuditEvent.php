<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SecurityAuditEvent extends Model
{
    protected $table = 'security_audit_events';

    public $timestamps = false;

    protected $fillable = [
        'event_type',
        'severity',
        'portal',
        'site_legacy_key',
        'actor_user_id',
        'actor_login',
        'ip_address',
        'user_agent',
        'request_path',
        'request_method',
        'message',
        'details_json',
        'created_at',
    ];

    protected function casts(): array
    {
        return [
            'details_json' => 'array',
        ];
    }
}
