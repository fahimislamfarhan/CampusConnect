<!DOCTYPE html>
<html>
<head>
    <title>Resources</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gradient-to-br from-[#0f0c29] via-[#302b63] to-[#24243e] text-white">

<div class="max-w-6xl mx-auto mt-10 p-6">

    <h1 class="text-3xl font-bold mb-6 text-purple-300">📚 Campus Resources</h1>

    <a href="/resources/create"
       class="inline-block bg-gradient-to-r from-purple-500 to-pink-500 text-white px-5 py-2 rounded-lg mb-6 shadow-lg hover:scale-105 transition">
        + Upload Resource
    </a>

    <form method="GET" action="/resources" class="mb-8 flex gap-4 flex-wrap">
        <select name="category" class="bg-gray-900 border border-purple-500 p-2 rounded">
            <option value="">All Category</option>
            <option value="textbooks" {{ request('category') == 'textbooks' ? 'selected' : '' }}>Textbooks</option>
            <option value="notes" {{ request('category') == 'notes' ? 'selected' : '' }}>Notes</option>
            <option value="lab_equipment" {{ request('category') == 'lab_equipment' ? 'selected' : '' }}>Lab Equipment</option>
            <option value="electronics" {{ request('category') == 'electronics' ? 'selected' : '' }}>Electronics</option>
        </select>

        <select name="condition" class="bg-gray-900 border border-purple-500 p-2 rounded">
            <option value="">All Condition</option>
            <option value="new" {{ request('condition') == 'new' ? 'selected' : '' }}>New</option>
            <option value="good" {{ request('condition') == 'good' ? 'selected' : '' }}>Good</option>
            <option value="used" {{ request('condition') == 'used' ? 'selected' : '' }}>Used</option>
        </select>

        <select name="available" class="bg-gray-900 border border-purple-500 p-2 rounded">
            <option value="">All Status</option>
            <option value="1" {{ request('available') === '1' ? 'selected' : '' }}>Available</option>
            <option value="0" {{ request('available') === '0' ? 'selected' : '' }}>Unavailable</option>
        </select>

        <button class="bg-purple-600 px-4 py-2 rounded hover:bg-purple-700 transition">
            Filter
        </button>
    </form>

    <div class="grid md:grid-cols-2 gap-6">
        @foreach($resources as $resource)
            <div class="bg-gray-900 border border-purple-500 p-5 rounded-xl shadow-lg hover:scale-105 transition">

                <h2 class="text-xl font-bold text-purple-300">{{ $resource->title }}</h2>

                <p class="text-gray-400 mt-2">{{ $resource->description }}</p>

                <div class="mt-3 text-sm text-gray-300 space-y-1">
                    <p><b>Owner:</b> {{ $resource->user->name ?? 'Unknown' }}</p>
                    <p><b>Credibility:</b> ⭐⭐⭐⭐☆</p>
                    <p><b>Category:</b> {{ $resource->category }}</p>
                    <p><b>Condition:</b> {{ $resource->condition }}</p>
                    <p><b>Status:</b> {{ $resource->available ? 'Available' : 'Unavailable' }}</p>
                    <p><b>Type:</b> {{ $resource->type }}</p>
                    <p><b>Duration:</b> {{ $resource->duration ?? 'N/A' }} days</p>
                </div>

                <div class="mt-4 flex gap-4 flex-wrap items-center">
                    <a href="{{ route('resources.show', $resource->id) }}"
                       class="text-pink-400 hover:underline">
                        🔍 View Details
                    </a>

                    @if($resource->user_id == auth()->id())
                        <a href="{{ route('resources.edit', $resource->id) }}"
                           class="text-yellow-400 hover:underline">
                            ✏️ Edit
                        </a>

                        <form action="{{ route('resources.destroy', $resource->id) }}" method="POST">
                            @csrf
                            @method('DELETE')

                            <button onclick="return confirm('Delete this resource?')"
                                    class="text-red-400 hover:underline">
                                🗑 Delete
                            </button>
                        </form>
                    @endif
                </div>

            </div>
        @endforeach
    </div>

</div>

</body>
</html>