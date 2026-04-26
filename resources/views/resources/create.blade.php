<!DOCTYPE html>
<html>
<head>
    <title>Post Resource</title>
    <script src="https://cdn.tailwindcss.com"></script>

    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
</head>

<body class="bg-gradient-to-br from-[#0f0c29] via-[#302b63] to-[#24243e] text-white">

<div class="max-w-xl mx-auto mt-12 bg-gray-900 p-8 rounded-2xl shadow-lg border border-purple-500">

    <h1 class="text-3xl font-bold mb-6 text-purple-300 text-center">
        📤 Post a Resource
    </h1>

    <form action="{{ route('resources.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <label class="block mb-1 text-sm text-purple-300">Title</label>
        <input type="text" name="title" required
               class="w-full bg-gray-800 border border-purple-500 p-2 rounded mb-4">

        <label class="block mb-1 text-sm text-purple-300">Description</label>
        <textarea name="description"
                  class="w-full bg-gray-800 border border-purple-500 p-2 rounded mb-4"></textarea>

        <label class="block mb-1 text-sm text-purple-300">Category</label>
        <select name="category" required class="w-full bg-gray-800 border border-purple-500 p-2 rounded mb-4">
            <option value="textbooks">Textbooks</option>
            <option value="notes">Notes</option>
            <option value="lab_equipment">Lab Equipment</option>
            <option value="electronics">Electronics</option>
        </select>

        <label class="block mb-1 text-sm text-purple-300">Condition</label>
        <select name="condition" required class="w-full bg-gray-800 border border-purple-500 p-2 rounded mb-4">
            <option value="new">New</option>
            <option value="good">Good</option>
            <option value="used">Used</option>
        </select>

        <label class="block mb-1 text-sm text-purple-300">Availability</label>
        <select name="available" required class="w-full bg-gray-800 border border-purple-500 p-2 rounded mb-4">
            <option value="1">Available</option>
            <option value="0">Unavailable</option>
        </select>

        <label class="block mb-1 text-sm text-purple-300">Sharing Type</label>
        <select name="type" required class="w-full bg-gray-800 border border-purple-500 p-2 rounded mb-4">
            <option value="free">Free Lending</option>
            <option value="exchange">Exchange Based</option>
        </select>

        <label class="block mb-1 text-sm text-purple-300">Duration (days)</label>
        <input type="number" name="duration"
               class="w-full bg-gray-800 border border-purple-500 p-2 rounded mb-4">

        <label class="block mb-1 text-sm text-purple-300">Pickup Location Name</label>
        <input type="text" name="location_name" placeholder="Example: BRAC University Library"
               class="w-full bg-gray-800 border border-purple-500 p-2 rounded mb-4">

        <label class="block mb-2 text-sm text-purple-300">Select Pickup Location on Map</label>
        <div id="map" class="w-full h-64 rounded mb-4"></div>

        <input type="hidden" name="latitude" id="latitude">
        <input type="hidden" name="longitude" id="longitude">

        <label class="block mb-1 text-sm text-purple-300">Upload File</label>
        <input type="file" name="file" required
               class="w-full bg-gray-800 border border-purple-500 p-2 rounded mb-6">

        <button type="submit"
                class="w-full bg-gradient-to-r from-purple-500 to-pink-500 text-white py-2 rounded-lg hover:scale-105 transition">
            🚀 Post Resource
        </button>
    </form>
</div>

<script>
    var map = L.map('map').setView([23.7806, 90.4074], 13);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap'
    }).addTo(map);

    var marker;

    map.on('click', function(e) {
        var lat = e.latlng.lat;
        var lng = e.latlng.lng;

        document.getElementById('latitude').value = lat;
        document.getElementById('longitude').value = lng;

        if (marker) {
            map.removeLayer(marker);
        }

        marker = L.marker([lat, lng]).addTo(map)
            .bindPopup("Pickup location selected")
            .openPopup();
    });
</script>

</body>
</html>