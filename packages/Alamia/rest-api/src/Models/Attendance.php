<?php

namespace Alamia\RestApi\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\User\Models\User;

class Attendance extends Model
{
    protected $table = 'attendance_logs';

    protected $fillable = [
        'user_id',
        'check_in',
        'check_out',
        'gps_lat',
        'gps_lng',
        'photo_url',
    ];

    protected $casts = [
        'check_in'  => 'datetime',
        'check_out' => 'datetime',
    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

