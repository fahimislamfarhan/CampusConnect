<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Resource;

class RideRequest extends Model
{
    protected $fillable = [
        'user_id',
        'resource_id',
        'pickup_location',
        'destination',
        'status',
    ];

    // 🔥 requester
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // 🔥 resource linked
    public function resource()
    {
        return $this->belongsTo(Resource::class);
    }
}