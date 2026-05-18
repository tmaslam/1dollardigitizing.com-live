<?php

namespace App\Models;

use App\Support\LegacyDate;
use Illuminate\Database\Eloquent\Model;

class LoginHistory extends Model
{
    protected $table = 'login_history';

    protected $primaryKey = 'id';

    public $timestamps = false;

    protected $fillable = [
        'IP_Address',
        'Login_Name',
        'Password',
        'Status',
        'Date_Added',
    ];

    public function getDateAddedAttribute(mixed $value): ?string
    {
        return LegacyDate::normalize($value);
    }
}
