<!DOCTYPE html>
<html>
<head>
    <title>Leaderboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-900 text-white p-6">

<h1 class="text-3xl font-bold mb-6 text-yellow-400">🏆 Monthly Leaderboard</h1>

<div class="mb-6 flex gap-4">
    <a href="/dashboard" class="text-purple-300 hover:underline">← Back to Dashboard</a>
    <a href="/resources" class="text-blue-300 hover:underline">Resources</a>
</div>

<div class="bg-gray-800 border border-yellow-500 rounded-xl overflow-hidden">
    <table class="w-full text-left">
        <thead class="bg-gray-700 text-yellow-300">
            <tr>
                <th class="p-3">Rank</th>
                <th class="p-3">User</th>
                <th class="p-3">Badge</th>
                <th class="p-3">Points</th>
                <th class="p-3">Lent</th>
                <th class="p-3">Positive Reviews</th>
                <th class="p-3">Resources Posted</th>
                <th class="p-3">Ride Engagement</th>
            </tr>
        </thead>

        <tbody>
            @forelse($users as $index => $user)
                <tr class="border-t border-gray-700 hover:bg-gray-700">
                    <td class="p-3 font-bold">
                        #{{ $index + 1 }}
                    </td>

                    <td class="p-3">
                        {{ $user->name }}
                    </td>

                    <td class="p-3">
                        {{ $user->badge }}
                    </td>

                    <td class="p-3 font-bold text-pink-400">
                        {{ $user->leaderboard_points }}
                    </td>

                    <td class="p-3">
                        {{ $user->items_lent }}
                    </td>

                    <td class="p-3">
                        {{ $user->positive_reviews }}
                    </td>

                    <td class="p-3">
                        {{ $user->resources_posted }}
                    </td>

                    <td class="p-3">
                        {{ $user->ride_engagement }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="p-4 text-gray-400">
                        No leaderboard data found.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-6 bg-gray-800 border border-purple-500 rounded-xl p-4">
    <h2 class="text-xl font-bold text-purple-300 mb-2">📌 Point System</h2>
    <p>Resource posted: +5 points</p>
    <p>Approved lending: +25 points</p>
    <p>Positive review received: +15 points</p>
    <p>Ride engagement: +5 points</p>
</div>

</body>
</html>