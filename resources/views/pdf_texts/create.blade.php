<!DOCTYPE html>
<html>
<head>
    <title>PDF to Text</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-900 text-white p-8">

<div class="max-w-xl mx-auto bg-gray-800 p-6 rounded-xl border border-purple-500">
    <h1 class="text-3xl font-bold text-purple-300 mb-6">📝 Handwritten PDF to Text</h1>

    @if($errors->any())
        <div class="mb-4 text-red-400">
            @foreach($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <form action="{{ route('pdf-texts.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <label class="block mb-2">Upload PDF / Image</label>
        <input type="file" name="pdf_file" required
               class="w-full p-2 bg-gray-900 border border-purple-500 rounded mb-4">

        <button type="submit" class="bg-purple-600 px-4 py-2 rounded hover:bg-purple-700">
            Convert to Text
        </button>
    </form>
</div>

</body>
</html>