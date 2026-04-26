<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Resource;
use App\Models\BorrowRequest;
use App\Models\Review;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function resources()
    {
        return $this->hasMany(Resource::class);
    }

    public function borrowRequests()
    {
        return $this->hasMany(BorrowRequest::class);
    }

    // Reviews given by this user
    public function reviewsGiven()
    {
        return $this->hasMany(Review::class, 'user_id');
    }

    // Reviews received as owner
    public function reviewsReceived()
    {
        return $this->hasMany(Review::class, 'owner_id');
    }

    // Owner credibility score
    public function credibilityScore()
    {
        return round($this->reviewsReceived()->avg('rating') ?? 0, 1);
    }
}