<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupervisorTeamMember extends Model
{
    protected $table = 'supervisor_team_members';

    protected $primaryKey = 'id';

    public $timestamps = false;

    protected $fillable = [
        'supervisor_user_id',
        'member_user_id',
        'date_added',
        'end_date',
        'deleted_by',
    ];

    public function scopeActive($query)
    {
        return $query->whereNull('end_date');
    }
}
