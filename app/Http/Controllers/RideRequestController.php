<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RideRequest;
use App\Models\Resource;

class RideRequestController extends Controller
{
    public function store(Request $request, $id)
    {
        $resource = Resource::findOrFail($id);

        $request->validate([
            'pickup_location' => 'required|string',
            'destination' => 'required|string',
        ]);

        RideRequest::create([
            'user_id' => auth()->id(),
            'resource_id' => $resource->id,
            'pickup_location' => $request->pickup_location,
            'destination' => $request->destination,
            'status' => 'pending',
        ]);

        return back()->with('success', 'Ride request sent successfully!');
    }

    public function accept($id)
    {
        $rideRequest = RideRequest::findOrFail($id);

        $rideRequest->update([
            'status' => 'accepted',
        ]);

        return back()->with('success', 'Ride request accepted successfully!');
    }
}