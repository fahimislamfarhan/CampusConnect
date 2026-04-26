<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\BorrowRequest;
use App\Models\Review;

class Resource extends Model
{
    protected $fillable = [
        'title',
        'description',
        'file_path',
        'category',
        'condition',
        'available',
        'type',
        'duration',
        'user_id',
        'location_name',
        'latitude',
        'longitude'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function borrowRequests()
    {
        return $this->hasMany(BorrowRequest::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
}