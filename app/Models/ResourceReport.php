<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Resource;
use App\Models\BorrowRequest;

class ResourceReport extends Model
{
    protected $fillable = [
        'reporter_id',
        'resource_id',
        'borrow_request_id',
        'issue_type',
        'description',
        'evidence_path',
        'status',
    ];

    // 🔥 Reporter (who reported)
    public function reporter()
    {
        return $this->belongsTo(User::class, 'reporter_id');
    }

    // 🔥 Resource
    public function resource()
    {
        return $this->belongsTo(Resource::class);
    }

    // 🔥 Borrow request (optional)
    public function borrowRequest()
    {
        return $this->belongsTo(BorrowRequest::class);
    }
}
