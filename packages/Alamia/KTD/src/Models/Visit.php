<?php

namespace Alamia\KTD\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\Lead\Models\LeadProxy;
use Webkul\User\Models\UserProxy;

class Visit extends Model
{
    protected $table = 'ktd_visits';

    protected $fillable = [
        'title',
        'description',
        'lead_id',
        'user_id',
        'latitude',
        'longitude',
        'photo_path',
        'visit_time',
    ];

    protected $casts = [
        'visit_time' => 'datetime',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
    ];

    public function user()
    {
        return $this->belongsTo(UserProxy::modelClass());
    }

    public function lead()
    {
        return $this->belongsTo(LeadProxy::modelClass());
    }
}
