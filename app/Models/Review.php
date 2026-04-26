<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Resource;

class Review extends Model
{
    protected $fillable = [
        'user_id',
        'resource_id',
        'owner_id',
        'rating',
        'comment'
    ];

    // 🔥 reviewer
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // 🔥 resource
    public function resource()
    {
        return $this->belongsTo(Resource::class);
    }

    // 🔥 owner (jake rating deya hocche)
    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }
}