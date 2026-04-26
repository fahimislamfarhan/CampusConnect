<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Resource;
use App\Models\BorrowRequest;
use App\Models\Review;

class ReviewController extends Controller
{
    public function store(Request $request, $id)
    {
        $resource = Resource::findOrFail($id);

        // owner nijer resource review dite parbe na
        if ($resource->user_id == auth()->id()) {
            return back()->with('error', 'You cannot review your own resource.');
        }

        // only approved borrower review dite parbe
        $approvedRequest = BorrowRequest::where('resource_id', $id)
            ->where('user_id', auth()->id())
            ->where('status', 'approved')
            ->first();

        if (!$approvedRequest) {
            return back()->with('error', 'Only approved borrowers can review this resource.');
        }

        // duplicate review block
        $exists = Review::where('resource_id', $id)
            ->where('user_id', auth()->id())
            ->exists();

        if ($exists) {
            return back()->with('error', 'You already reviewed this resource.');
        }

        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string'
        ]);

        Review::create([
            'user_id' => auth()->id(),
            'resource_id' => $resource->id,
            'owner_id' => $resource->user_id,
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        return back()->with('success', 'Review submitted successfully!');
    }
}