<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-900 text-white p-6">

<!-- HEADER + LOGOUT -->
<div class="flex justify-between items-center mb-6">
    <h1 class="text-3xl font-bold">📊 My Dashboard</h1>

    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button class="bg-red-500 px-4 py-2 rounded hover:bg-red-600">
            🚪 Logout
        </button>
    </form>
</div>

@if(session('success'))
    <p class="mb-4 text-green-400">{{ session('success') }}</p>
@endif

<!-- QUICK ACCESS -->
<div class="mb-10 bg-gray-800 border border-purple-500 p-5 rounded-xl">
    <h2 class="text-xl font-bold text-purple-300 mb-4">⚡ Quick Access</h2>

    <div class="grid md:grid-cols-3 gap-4">
        <a href="/resources" class="bg-purple-600 p-4 rounded-lg text-center hover:bg-purple-700">📚 Browse Resources</a>
        <a href="/resources/create" class="bg-pink-600 p-4 rounded-lg text-center hover:bg-pink-700">📤 Post Resource</a>
        <a href="/pdf-texts/create" class="bg-blue-600 p-4 rounded-lg text-center hover:bg-blue-700">📝 PDF / Image to Text</a>
        <a href="/leaderboard" class="bg-yellow-600 p-4 rounded-lg text-center hover:bg-yellow-700">🏆 Leaderboard</a>
        <a href="/admin/reports" class="bg-red-600 p-4 rounded-lg text-center hover:bg-red-700">🚨 Admin Reports</a>
        <a href="/profile" class="bg-green-600 p-4 rounded-lg text-center hover:bg-green-700">👤 Profile</a>
    </div>
</div>

<!-- CONTRIBUTION STATS -->
<div class="mb-10">
    <h2 class="text-xl font-bold text-pink-400 mb-4">🌱 My Contribution Statistics</h2>

    <div class="grid md:grid-cols-4 gap-4">
        <div class="bg-gray-800 border border-purple-500 p-5 rounded-xl">
            <p class="text-gray-400 text-sm">Total Items Lent</p>
            <h3 class="text-3xl font-bold text-green-400">{{ $totalItemsLent }}</h3>
        </div>

        <div class="bg-gray-800 border border-blue-500 p-5 rounded-xl">
            <p class="text-gray-400 text-sm">Resources Borrowed</p>
            <h3 class="text-3xl font-bold text-blue-400">{{ $totalResourcesBorrowed }}</h3>
        </div>

        <div class="bg-gray-800 border border-yellow-500 p-5 rounded-xl">
            <p class="text-gray-400 text-sm">Resources Saved</p>
            <h3 class="text-3xl font-bold text-yellow-400">{{ $resourcesSavedFromPurchasing }}</h3>
        </div>

        <div class="bg-gray-800 border border-pink-500 p-5 rounded-xl">
            <p class="text-gray-400 text-sm">Karma Points</p>
            <h3 class="text-3xl font-bold text-pink-400">{{ $karmaPoints }}</h3>
        </div>
    </div>

    <div class="mt-4 bg-gray-800 border border-green-500 p-4 rounded-xl">
        <p class="text-green-300 font-bold">Environmental Impact</p>
        <p class="text-gray-300">
            You helped save <b>{{ $resourcesSavedFromPurchasing }}</b> resources.
        </p>
    </div>
</div>

<!-- MY BORROW -->
<div class="mb-10">
    <h2 class="text-xl font-bold text-green-400 mb-3">My Borrow Requests</h2>

    @forelse($myRequests as $req)
        <div class="p-4 mb-3 rounded border bg-gray-800 border-gray-600">
            <p><b>{{ $req->resource->title }}</b></p>
            <p>Status: {{ ucfirst($req->status) }}</p>
        </div>
    @empty
        <p class="text-gray-400">No borrowing requests yet.</p>
    @endforelse
</div>

<!-- INCOMING -->
<div class="mb-10">
    <h2 class="text-xl font-bold text-blue-400 mb-3">Requests for My Resources</h2>

    @forelse($incomingRequests as $req)
        <div class="p-4 mb-3 rounded border bg-gray-800 border-gray-600">
            <p><b>{{ $req->user->name }}</b> requested {{ $req->resource->title }}</p>

            @if($req->status == 'pending')
                <div class="flex gap-3 mt-2">
                    <form action="{{ route('borrow.approve', $req->id) }}" method="POST">
                        @csrf
                        <button class="bg-green-500 px-3 py-1 rounded">Approve</button>
                    </form>

                    <form action="{{ route('borrow.reject', $req->id) }}" method="POST">
                        @csrf
                        <button class="bg-red-500 px-3 py-1 rounded">Reject</button>
                    </form>
                </div>
            @endif
        </div>
    @empty
        <p class="text-gray-400">No incoming requests yet.</p>
    @endforelse
</div>

<!-- RIDE -->
<div class="mb-10">
    <h2 class="text-xl font-bold text-cyan-400 mb-3">🚗 My Ride Requests</h2>

    @forelse($myRideRequests as $ride)
        <div class="p-4 mb-3 bg-gray-800 rounded">
            {{ $ride->resource->title }} → {{ $ride->destination }}
        </div>
    @empty
        <p class="text-gray-400">No ride requests yet.</p>
    @endforelse
</div>

<!-- INCOMING RIDES -->
<div>
    <h2 class="text-xl font-bold text-purple-400 mb-3">🚕 Ride Requests for My Resources</h2>

    @forelse($incomingRideRequests as $ride)
        <div class="p-4 mb-3 bg-gray-800 rounded">
            {{ $ride->user->name }} → {{ $ride->destination }}

            @if($ride->status == 'pending')
                <form action="{{ route('ride.accept', $ride->id) }}" method="POST" class="mt-2">
                    @csrf
                    <button class="bg-green-500 px-3 py-1 rounded">
                        Accept
                    </button>
                </form>
            @endif
        </div>
    @empty
        <p class="text-gray-400">No incoming ride requests yet.</p>
    @endforelse
</div>

</body>
</html>

