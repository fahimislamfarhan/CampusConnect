<!DOCTYPE html>
<html>
<head>
    <title>Edit Text</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-900 text-white p-8">

<div class="max-w-3xl mx-auto bg-gray-800 p-6 rounded-xl border border-purple-500">

    <h1 class="text-3xl font-bold text-purple-300 mb-6">✍️ Edit Extracted Text</h1>

    @if(session('success'))
        <p class="text-green-400 mb-4">{{ session('success') }}</p>
    @endif

    <form action="{{ route('pdf-texts.update', $pdfText->id) }}" method="POST">
        @csrf
        @method('PUT')

        <textarea name="extracted_text" rows="18"
                  class="w-full p-3 bg-gray-900 border border-purple-500 rounded text-white">{{ $pdfText->extracted_text }}</textarea>

        <div class="mt-4 flex gap-3">
            <button class="bg-green-600 px-4 py-2 rounded hover:bg-green-700">
                Save Edited Text
            </button>

            <a href="{{ route('pdf-texts.download', $pdfText->id) }}"
               class="bg-pink-600 px-4 py-2 rounded hover:bg-pink-700">
                Download TXT
            </a>
        </div>
    </form>

    <div class="mt-5">
        <a href="/resources" class="text-purple-300 hover:underline">← Back</a>
    </div>
</div>

</body>
</html>