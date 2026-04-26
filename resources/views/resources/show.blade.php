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

    @if(session('error'))
        <p class="mb-4 text-red-400">{{ session('error') }}</p>
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

    {{-- OWNER PROFILE + CREDIBILITY + HISTORY --}}
    <div class="bg-gray-800 p-4 rounded-lg border border-purple-700 mb-5">
        <h2 class="text-xl font-bold text-pink-400 mb-3">Owner Profile</h2>

        <p><b>Owner:</b> {{ $resource->user->name }}</p>

        <p>
            <b>Credibility Score:</b>
            ⭐ {{ $resource->user->credibilityScore() }}/5
            <span class="text-gray-400">
                ({{ $resource->user->reviewsReceived->count() }} reviews)
            </span>
        </p>

        <div class="mt-4">
            <h3 class="font-bold text-purple-300">Past Transaction History</h3>

            @if($resource->borrowRequests->count() > 0)
                <ul class="list-disc list-inside text-gray-300 mt-1">
                    <li>Total Requests: {{ $resource->borrowRequests->count() }}</li>
                    <li>Approved: {{ $resource->borrowRequests->where('status', 'approved')->count() }}</li>
                    <li>Pending: {{ $resource->borrowRequests->where('status', 'pending')->count() }}</li>
                    <li>Rejected: {{ $resource->borrowRequests->where('status', 'rejected')->count() }}</li>
                </ul>
            @else
                <p class="text-gray-400 mt-1">No transaction history yet</p>
            @endif
        </div>

        <div class="mt-4">
            <h3 class="font-bold text-purple-300">Previous Borrowers</h3>

            @if($resource->borrowRequests->count() > 0)
                <ul class="list-disc list-inside text-gray-300 mt-1">
                    @foreach($resource->borrowRequests as $request)
                        <li>
                            {{ $request->user->name ?? 'Unknown User' }} -
                            <span class="text-sm text-gray-400">{{ ucfirst($request->status) }}</span>
                            <br>
                            <span class="text-sm text-gray-400">
                                Pickup: {{ $request->pickup_date ?? 'N/A' }} |
                                Return: {{ $request->return_date ?? 'N/A' }}
                            </span>
                        </li>
                    @endforeach
                </ul>
            @else
                <p class="text-gray-400 mt-1">No borrowers yet</p>
            @endif
        </div>
    </div>

    {{-- REVIEWS FROM PREVIOUS BORROWERS --}}
    <div class="bg-gray-800 p-4 rounded-lg border border-yellow-500 mb-5">
        <h2 class="text-xl font-bold text-yellow-400 mb-3">⭐ Reviews from Previous Borrowers</h2>

        @if($resource->reviews->count() > 0)
            <div class="space-y-3">
                @foreach($resource->reviews as $review)
                    <div class="bg-gray-900 p-3 rounded border border-yellow-600">
                        <p>
                            <b>{{ $review->user->name ?? 'Unknown User' }}</b>
                            rated {{ $review->rating }}/5 ⭐
                        </p>
                        <p class="text-gray-300">
                            {{ $review->comment ?? 'No comment' }}
                        </p>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-gray-400">No reviews yet.</p>
        @endif
    </div>

    {{-- RESOURCE INFO --}}
    <div class="bg-gray-800 p-4 rounded-lg border border-purple-700 mb-5">
        <h2 class="text-xl font-bold text-purple-300 mb-3">Resource Information</h2>

        <p><b>Category:</b> {{ $resource->category }}</p>
        <p><b>Condition:</b> {{ $resource->condition }}</p>
        <p><b>Status:</b> {{ $resource->available ? 'Available' : 'Unavailable' }}</p>
        <p><b>Type:</b> {{ $resource->type }}</p>
        <p><b>Duration:</b> {{ $resource->duration ?? 'N/A' }} days</p>
        <p><b>Pickup Location:</b> {{ $resource->location_name ?? 'Not added' }}</p>
    </div>

    {{-- PDF / FILE VIEW --}}
    @if($resource->file_path)
        <div class="mt-6 bg-gray-800 p-4 rounded border border-pink-500">
            <h2 class="text-xl font-bold text-pink-400 mb-3">📄 Resource File</h2>

            <a href="{{ asset('storage/' . $resource->file_path) }}" target="_blank"
               class="inline-block bg-pink-500 px-4 py-2 rounded hover:bg-pink-600 mb-4">
                Open File / PDF
            </a>

            <iframe src="{{ asset('storage/' . $resource->file_path) }}"
                    class="w-full h-96 rounded border border-pink-500 bg-white">
            </iframe>
        </div>
    @endif

    {{-- MAP --}}
    @if($resource->latitude && $resource->longitude)
        <div class="mt-6">
            <h2 class="text-xl font-bold text-purple-300 mb-3">📍 Pickup Point</h2>
            <div id="map" class="w-full h-64 rounded border border-purple-500"></div>
        </div>
    @endif

    {{-- BORROW --}}
    @if($resource->user_id != auth()->id())
        <form action="{{ route('borrow.store', $resource->id) }}" method="POST" class="mt-6 bg-gray-800 p-4 rounded border border-green-500">
            @csrf

            <h2 class="text-green-400 font-bold mb-3">📩 Borrow Request</h2>

            <label class="block text-sm text-purple-300 mb-1">Pickup Date</label>
            <input type="text" name="pickup_date" placeholder="YYYY-MM-DD" required
                class="w-full p-2 mb-2 bg-gray-900 border border-green-500 rounded">

            <label class="block text-sm text-purple-300 mb-1">Return Date</label>
            <input type="text" name="return_date" placeholder="YYYY-MM-DD" required
                class="w-full p-2 mb-2 bg-gray-900 border border-green-500 rounded">

            <label class="block text-sm text-purple-300 mb-1">Message</label>
            <textarea name="message" placeholder="Write a message to owner (optional)"
                class="w-full p-2 mb-2 bg-gray-900 border border-green-500 rounded"></textarea>

            <button class="bg-green-500 px-4 py-2 rounded hover:bg-green-600">
                📩 Request Borrow
            </button>
        </form>
    @endif

    {{-- REVIEW FORM --}}
    @if($resource->user_id != auth()->id())
        <div class="mt-6 bg-gray-800 p-4 rounded border border-yellow-500">
            <h2 class="text-yellow-400 font-bold mb-3">⭐ Write a Review</h2>

            <form action="{{ route('review.store', $resource->id) }}" method="POST">
                @csrf

                <label class="block text-sm text-purple-300 mb-1">Rating</label>
                <select name="rating" required
                    class="w-full p-2 mb-2 bg-gray-900 border border-yellow-500 rounded">
                    <option value="">Select Rating</option>
                    <option value="5">5 - Excellent</option>
                    <option value="4">4 - Good</option>
                    <option value="3">3 - Average</option>
                    <option value="2">2 - Poor</option>
                    <option value="1">1 - Bad</option>
                </select>

                <label class="block text-sm text-purple-300 mb-1">Review</label>
                <textarea name="comment" placeholder="Write your transaction experience"
                    class="w-full p-2 mb-2 bg-gray-900 border border-yellow-500 rounded"></textarea>

                <button class="bg-yellow-500 text-black px-4 py-2 rounded hover:bg-yellow-400">
                    Submit Review
                </button>
            </form>
        </div>
    @endif

    {{-- RIDE SHARING --}}
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

                <button class="bg-blue-500 px-4 py-2 rounded hover:bg-blue-600">
                    Request Ride
                </button>
            </form>
        </div>
    @endif

    {{-- REPORT FEATURE --}}
    @if($resource->user_id != auth()->id())
        <div class="mt-6 bg-gray-800 p-4 rounded border border-red-500">
            <h2 class="text-red-400 font-bold mb-2">🚨 Report Issue</h2>

            <form action="{{ route('reports.store', $resource->id) }}" method="POST" enctype="multipart/form-data">
                @csrf

                <select name="issue_type" required
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

                <button class="bg-red-500 px-4 py-2 rounded hover:bg-red-600">
                    Submit Report
                </button>
            </form>
        </div>
    @endif

    <div class="mt-6">
        <a href="/resources" class="text-purple-300 hover:underline">← Back</a>
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