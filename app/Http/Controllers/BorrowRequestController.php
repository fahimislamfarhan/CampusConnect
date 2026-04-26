<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BorrowRequest;
use App\Models\Resource;
use Illuminate\Support\Facades\Http;

class BorrowRequestController extends Controller
{
    public function store(Request $request, $id)
    {
        $resource = Resource::with('user')->findOrFail($id);

        if ($resource->user_id == auth()->id()) {
            return back()->with('error', 'You cannot request your own resource.');
        }

        $exists = BorrowRequest::where('user_id', auth()->id())
            ->where('resource_id', $id)
            ->exists();

        if ($exists) {
            return back()->with('error', 'You already requested this resource.');
        }

        $request->validate([
            'pickup_date' => 'required|date',
            'return_date' => 'required|date|after_or_equal:pickup_date',
            'message' => 'nullable|string'
        ]);

        $borrowRequest = BorrowRequest::create([
            'user_id' => auth()->id(),
            'resource_id' => $id,
            'message' => $request->message,
            'pickup_date' => $request->pickup_date,
            'return_date' => $request->return_date,
            'status' => 'pending'
        ]);

        $smsMessage = 'CampusConnect: New borrow request for "' . $resource->title . '" from ' . auth()->user()->name .
            '. Pickup: ' . $request->pickup_date .
            ', Return: ' . $request->return_date . '.';

        if (env('SMS_ENABLED') == 'true' && env('SMS_API_URL') && env('SMS_API_KEY')) {
            Http::post(env('SMS_API_URL'), [
                'api_key' => env('SMS_API_KEY'),
                'msg' => $smsMessage,
                'to' => $resource->user->phone,
            ]);

            return back()->with('success', 'Request sent successfully! SMS notification sent to owner.');
        }

        return back()->with('success', 'Request sent successfully! SMS notification simulated for owner: ' . $smsMessage);
    }

    public function approve($id)
    {
        $borrowRequest = BorrowRequest::with('resource')->findOrFail($id);

        if ($borrowRequest->resource->user_id != auth()->id()) {
            abort(403);
        }

        $borrowRequest->update([
            'status' => 'approved'
        ]);

        $borrowRequest->resource->update([
            'available' => false
        ]);

        return back()->with('success', 'Borrow request approved successfully!');
    }

    public function reject($id)
    {
        $borrowRequest = BorrowRequest::with('resource')->findOrFail($id);

        if ($borrowRequest->resource->user_id != auth()->id()) {
            abort(403);
        }

        $borrowRequest->update([
            'status' => 'rejected'
        ]);

        return back()->with('success', 'Borrow request rejected successfully!');
    }
}