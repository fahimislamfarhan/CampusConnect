<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Resource;
use App\Models\BorrowRequest;
use App\Models\Review;
use App\Models\RideRequest;
use Carbon\Carbon;

class LeaderboardController extends Controller
{
    public function index()
    {
        $startOfMonth = Carbon::now()->startOfMonth();

        $users = User::all()->map(function ($user) use ($startOfMonth) {

            $resourcesPosted = Resource::where('user_id', $user->id)
                ->where('created_at', '>=', $startOfMonth)
                ->count();

            $itemsLent = BorrowRequest::where('status', 'approved')
                ->where('created_at', '>=', $startOfMonth)
                ->whereHas('resource', function ($q) use ($user) {
                    $q->where('user_id', $user->id);
                })
                ->count();

            $positiveReviews = Review::where('owner_id', $user->id)
                ->where('rating', '>=', 4)
                ->where('created_at', '>=', $startOfMonth)
                ->count();

            $rideEngagement = RideRequest::where('user_id', $user->id)
                ->where('created_at', '>=', $startOfMonth)
                ->count();

            $points =
                ($resourcesPosted * 5) +
                ($itemsLent * 25) +
                ($positiveReviews * 15) +
                ($rideEngagement * 5);

            if ($points >= 100) {
                $badge = '🏆 Gold Sharer';
            } elseif ($points >= 60) {
                $badge = '🥈 Silver Sharer';
            } elseif ($points >= 30) {
                $badge = '🥉 Bronze Sharer';
            } else {
                $badge = '🌱 New Contributor';
            }

            $user->leaderboard_points = $points;
            $user->resources_posted = $resourcesPosted;
            $user->items_lent = $itemsLent;
            $user->positive_reviews = $positiveReviews;
            $user->ride_engagement = $rideEngagement;
            $user->badge = $badge;

            return $user;
        })->sortByDesc('leaderboard_points')->values();

        return view('leaderboard.index', compact('users'));
    }
}
