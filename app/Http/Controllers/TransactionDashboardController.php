<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BorrowRequest;
use App\Models\RideRequest;
use App\Models\Resource;

class TransactionDashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $myRequests = BorrowRequest::with('resource')
            ->where('user_id', $user->id)
            ->latest()
            ->get();

        $incomingRequests = BorrowRequest::with(['user', 'resource'])
            ->whereHas('resource', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })
            ->latest()
            ->get();

        $myRideRequests = RideRequest::with('resource')
            ->where('user_id', $user->id)
            ->latest()
            ->get();

        $incomingRideRequests = RideRequest::with(['user', 'resource'])
            ->whereHas('resource', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })
            ->latest()
            ->get();

        // 📊 Contribution Statistics
        $totalItemsPosted = Resource::where('user_id', $user->id)->count();

        $totalItemsLent = BorrowRequest::where('status', 'approved')
            ->whereHas('resource', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })
            ->count();

        $totalResourcesBorrowed = BorrowRequest::where('user_id', $user->id)
            ->where('status', 'approved')
            ->count();

        // Simple impact formula:
        // every approved lending/borrowing means one purchase avoided
        $resourcesSavedFromPurchasing = $totalItemsLent + $totalResourcesBorrowed;

        // Karma formula
        // post = 5 points, lend = 20 points, borrow responsibly = 10 points
        $karmaPoints = ($totalItemsPosted * 5)
            + ($totalItemsLent * 20)
            + ($totalResourcesBorrowed * 10);

        return view('dashboard', compact(
            'myRequests',
            'incomingRequests',
            'myRideRequests',
            'incomingRideRequests',
            'totalItemsPosted',
            'totalItemsLent',
            'totalResourcesBorrowed',
            'resourcesSavedFromPurchasing',
            'karmaPoints'
        ));
    }
}