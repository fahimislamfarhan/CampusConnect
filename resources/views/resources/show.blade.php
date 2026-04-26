<!DOCTYPE html>
<html>
<head>
    <title>Resource Details</title>
    <script src="https://cdn.tailwindcss.com"></script>

    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
</head>

<body class="bg-gradient-to-br from-[#0f0c29] via-[#302b63] to-[#24243e] text-white">

<div class="max-w-3xl mx-auto mt-10 p-6 bg-gray-900 rounded-xl border border-purple-500 shadow-lg">

    @if(session('success'))
        <p class="mb-4 text-green-400">{{ session('success') }}</p>
    @endif

    @if($errors->any())
        <div class="mb-4 text-red-400">
            @foreach($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <h1 class="text-3xl font-bold text-purple-300 mb-4">
        {{ $resource->title }}
    </h1>

    <p class="text-gray-300 mb-5">
        {{ $resource->description }}
    </p>

    <div class="bg-gray-800 p-4 rounded-lg border border-purple-700 mb-5">
        <p><b>Owner:</b> {{ $resource->user->name }}</p>
        <p><b>Category:</b> {{ $resource->category }}</p>
        <p><b>Status:</b> {{ $resource->available ? 'Available' : 'Unavailable' }}</p>
    </div>

    {{-- MAP --}}
    @if($resource->latitude && $resource->longitude)
        <div class="mt-6">
            <h2 class="text-xl font-bold text-purple-300 mb-3">📍 Pickup Point</h2>
            <div id="map" class="w-full h-64 rounded border border-purple-500"></div>
        </div>
    @endif

    {{-- BORROW --}}
    @if($resource->user_id != auth()->id())
        <form action="{{ route('borrow.store', $resource->id) }}" method="POST" class="mt-6">
            @csrf

            <input type="text" name="pickup_date" placeholder="Pickup Date"
                class="w-full p-2 mb-2 bg-gray-800 border border-purple-500 rounded">

            <input type="text" name="return_date" placeholder="Return Date"
                class="w-full p-2 mb-2 bg-gray-800 border border-purple-500 rounded">

            <button class="bg-green-500 px-4 py-2 rounded">
                📩 Request Borrow
            </button>
        </form>
    @endif

    {{-- 🚗 RIDE SHARING --}}
    @if($resource->user_id != auth()->id())
        <div class="mt-6 bg-gray-800 p-4 rounded border border-blue-500">
            <h2 class="text-blue-400 font-bold mb-2">🚗 Ride Sharing</h2>

            <form action="{{ route('ride.store', $resource->id) }}" method="POST">
                @csrf

                <input type="text" name="pickup_location"
                    value="{{ $resource->location_name }}"
                    class="w-full p-2 mb-2 bg-gray-900 border border-blue-500 rounded">

                <input type="text" name="destination"
                    placeholder="Your destination"
                    class="w-full p-2 mb-2 bg-gray-900 border border-blue-500 rounded">

                <button class="bg-blue-500 px-4 py-2 rounded">
                    Request Ride
                </button>
            </form>
        </div>
    @endif

    {{-- 🚨 REPORT FEATURE --}}
    @if($resource->user_id != auth()->id())
        <div class="mt-6 bg-gray-800 p-4 rounded border border-red-500">
            <h2 class="text-red-400 font-bold mb-2">🚨 Report Issue</h2>

            <form action="{{ route('reports.store', $resource->id) }}" method="POST" enctype="multipart/form-data">
                @csrf

                <select name="issue_type"
                    class="w-full p-2 mb-2 bg-gray-900 border border-red-500 rounded">
                    <option value="">Select Issue</option>
                    <option value="lost">Lost</option>
                    <option value="damaged">Damaged</option>
                    <option value="unreturned">Unreturned</option>
                </select>

                <textarea name="description"
                    placeholder="Describe the issue"
                    class="w-full p-2 mb-2 bg-gray-900 border border-red-500 rounded"></textarea>

                <input type="file" name="evidence"
                    class="w-full p-2 mb-2 bg-gray-900 border border-red-500 rounded">

                <button class="bg-red-500 px-4 py-2 rounded">
                    Submit Report
                </button>
            </form>
        </div>
    @endif

    <div class="mt-6">
        <a href="/resources" class="text-purple-300">← Back</a>
    </div>

</div>

@if($resource->latitude && $resource->longitude)
<script>
    var map = L.map('map').setView([{{ $resource->latitude }}, {{ $resource->longitude }}], 15);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);

    L.marker([{{ $resource->latitude }}, {{ $resource->longitude }}]).addTo(map);
</script>
@endif

</body>
</html>