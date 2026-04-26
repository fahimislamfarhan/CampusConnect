<!DOCTYPE html>
<html>
<head>
    <title>Admin - Reports</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-900 text-white p-6">

<h1 class="text-3xl font-bold mb-6 text-red-400">🚨 Admin Panel - Resource Reports</h1>

@if(session('success'))
    <p class="mb-4 text-green-400">{{ session('success') }}</p>
@endif

<div class="mb-6">
    <a href="/dashboard" class="text-purple-300 hover:underline">← Back to Dashboard</a>
</div>

@forelse($reports as $report)
    <div class="bg-gray-800 p-5 rounded-xl border border-red-500 mb-4">

        <p><b>Reporter:</b> {{ $report->reporter->name }}</p>
        <p><b>Resource:</b> {{ $report->resource->title }}</p>

        @if($report->borrowRequest && $report->borrowRequest->user)
            <p><b>Borrower:</b> {{ $report->borrowRequest->user->name }}</p>
        @endif

        <p><b>Issue:</b> 
            <span class="text-red-400">{{ ucfirst($report->issue_type) }}</span>
        </p>

        <p><b>Description:</b> {{ $report->description ?? 'N/A' }}</p>

        <p><b>Status:</b> 
            <span class="
                @if($report->status == 'pending') text-yellow-400
                @elseif($report->status == 'approved') text-green-400
                @elseif($report->status == 'rejected') text-red-400
                @endif">
                {{ ucfirst($report->status) }}
            </span>
        </p>

        @if($report->evidence_path)
            <div class="mt-2">
                <a href="{{ asset('storage/' . $report->evidence_path) }}" target="_blank"
                   class="text-blue-400 underline">
                    📎 View Evidence
                </a>
            </div>
        @endif

        @if($report->status == 'pending')
            <div class="flex gap-3 mt-4">

                <form action="{{ route('admin.reports.approve', $report->id) }}" method="POST">
                    @csrf
                    <button class="bg-green-500 px-4 py-2 rounded hover:bg-green-600">
                        ✅ Approve
                    </button>
                </form>

                <form action="{{ route('admin.reports.reject', $report->id) }}" method="POST">
                    @csrf
                    <button class="bg-red-500 px-4 py-2 rounded hover:bg-red-600">
                        ❌ Reject
                    </button>
                </form>

            </div>
        @endif

    </div>

@empty
    <p class="text-gray-400">No reports found.</p>
@endforelse

</body>
</html>