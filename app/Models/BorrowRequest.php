<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Resource;

class BorrowRequest extends Model
{
    protected $fillable = [
        'user_id',
        'resource_id',
        'message',
        'status',
        'pickup_date',
        'return_date'
    ];

    // 🔥 who requested
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // 🔥 which resource
    public function resource()
    {
        return $this->belongsTo(Resource::class);
    }
}