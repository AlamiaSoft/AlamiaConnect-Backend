<?php

namespace Alamia\RestApi\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\User\Models\User;
use Webkul\Lead\Models\Lead;

class SalesVisit extends Model
{
    protected $table = 'sales_visits';

    protected $fillable = [
        'user_id',
        'lead_id',
        'visit_at',
        'check_in_at',
        'check_out_at',
        'outcome',
        'notes',
        'gps_lat',
        'gps_lng',
        'photo_url',
    ];

    protected $casts = [
        'visit_at' => 'datetime',
        'check_in_at' => 'datetime',
        'check_out_at' => 'datetime',
    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function lead()
    {
        return $this->belongsTo(Lead::class);
    }
}

