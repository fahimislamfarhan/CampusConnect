<!DOCTYPE html>
<html>
<head>
    <title>Edit Resource</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gradient-to-br from-[#0f0c29] via-[#302b63] to-[#24243e] text-white">

<div class="max-w-xl mx-auto mt-12 bg-gray-900 p-8 rounded-2xl shadow-lg border border-purple-500">

    <h1 class="text-3xl font-bold mb-6 text-purple-300 text-center">
        ✏️ Edit Resource
    </h1>

    <form action="{{ route('resources.update', $resource->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <label class="block mb-1 text-sm text-purple-300">Title</label>
        <input type="text" name="title" value="{{ $resource->title }}" required
               class="w-full bg-gray-800 border border-purple-500 p-2 rounded mb-4">

        <label class="block mb-1 text-sm text-purple-300">Description</label>
        <textarea name="description"
                  class="w-full bg-gray-800 border border-purple-500 p-2 rounded mb-4">{{ $resource->description }}</textarea>

        <label class="block mb-1 text-sm text-purple-300">Category</label>
        <select name="category" required class="w-full bg-gray-800 border border-purple-500 p-2 rounded mb-4">
            <option value="textbooks" {{ $resource->category == 'textbooks' ? 'selected' : '' }}>Textbooks</option>
            <option value="notes" {{ $resource->category == 'notes' ? 'selected' : '' }}>Notes</option>
            <option value="lab_equipment" {{ $resource->category == 'lab_equipment' ? 'selected' : '' }}>Lab Equipment</option>
            <option value="electronics" {{ $resource->category == 'electronics' ? 'selected' : '' }}>Electronics</option>
        </select>

        <label class="block mb-1 text-sm text-purple-300">Condition</label>
        <select name="condition" required class="w-full bg-gray-800 border border-purple-500 p-2 rounded mb-4">
            <option value="new" {{ $resource->condition == 'new' ? 'selected' : '' }}>New</option>
            <option value="good" {{ $resource->condition == 'good' ? 'selected' : '' }}>Good</option>
            <option value="used" {{ $resource->condition == 'used' ? 'selected' : '' }}>Used</option>
        </select>

        <label class="block mb-1 text-sm text-purple-300">Availability</label>
        <select name="available" required class="w-full bg-gray-800 border border-purple-500 p-2 rounded mb-4">
            <option value="1" {{ $resource->available ? 'selected' : '' }}>Available</option>
            <option value="0" {{ !$resource->available ? 'selected' : '' }}>Unavailable</option>
        </select>

        <label class="block mb-1 text-sm text-purple-300">Sharing Type</label>
        <select name="type" required class="w-full bg-gray-800 border border-purple-500 p-2 rounded mb-4">
            <option value="free" {{ $resource->type == 'free' ? 'selected' : '' }}>Free Lending</option>
            <option value="exchange" {{ $resource->type == 'exchange' ? 'selected' : '' }}>Exchange Based</option>
        </select>

        <label class="block mb-1 text-sm text-purple-300">Duration (days)</label>
        <input type="number" name="duration" value="{{ $resource->duration }}"
               class="w-full bg-gray-800 border border-purple-500 p-2 rounded mb-4">

        <label class="block mb-1 text-sm text-purple-300">Replace File (optional)</label>
        <input type="file" name="file"
               class="w-full bg-gray-800 border border-purple-500 p-2 rounded mb-6">

        <button type="submit"
                class="w-full bg-gradient-to-r from-purple-500 to-pink-500 text-white py-2 rounded-lg hover:scale-105 transition">
            Update Resource
        </button>
    </form>

    <a href="{{ route('resources.show', $resource->id) }}" class="block text-center mt-4 text-purple-300 hover:underline">
        ← Back to Details
    </a>
</div>

</body>
</html>