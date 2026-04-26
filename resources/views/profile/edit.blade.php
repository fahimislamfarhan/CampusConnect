<!DOCTYPE html>
<html>
<head>
    <title>Profile</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gradient-to-br from-[#0f0c29] via-[#302b63] to-[#24243e] text-white p-6">

<h1 class="text-3xl font-bold mb-6 text-green-400">👤 My Profile</h1>

<div class="mb-6">
    <a href="/dashboard" class="text-purple-300 hover:underline">← Back to Dashboard</a>
</div>

<!-- Profile Info -->
<div class="bg-gray-900 p-6 rounded-xl border border-purple-500 mb-6">
    <h2 class="text-xl font-bold text-purple-300 mb-4">Profile Information</h2>

    <form method="POST" action="{{ route('profile.update') }}">
        @csrf
        @method('patch')

        <label class="block mb-2">Name</label>
        <input type="text" name="name" value="{{ auth()->user()->name }}"
            class="w-full p-2 rounded bg-gray-800 border border-purple-500 mb-4">

        <label class="block mb-2">Email</label>
        <input type="email" value="{{ auth()->user()->email }}"
            class="w-full p-2 rounded bg-gray-800 border border-purple-500 mb-4" disabled>

        <button class="bg-green-500 px-4 py-2 rounded hover:bg-green-600">
            Save Changes
        </button>
    </form>
</div>

<!-- Change Password -->
<div class="bg-gray-900 p-6 rounded-xl border border-pink-500">
    <h2 class="text-xl font-bold text-pink-300 mb-4">Update Password</h2>

    <form method="POST" action="{{ route('password.update') }}">
        @csrf
        @method('put')

        <label class="block mb-2">Current Password</label>
        <input type="password" name="current_password"
            class="w-full p-2 rounded bg-gray-800 border border-pink-500 mb-4">

        <label class="block mb-2">New Password</label>
        <input type="password" name="password"
            class="w-full p-2 rounded bg-gray-800 border border-pink-500 mb-4">

        <label class="block mb-2">Confirm Password</label>
        <input type="password" name="password_confirmation"
            class="w-full p-2 rounded bg-gray-800 border border-pink-500 mb-4">

        <button class="bg-pink-500 px-4 py-2 rounded hover:bg-pink-600">
            Update Password
        </button>
    </form>
</div>

</body>
</html>
