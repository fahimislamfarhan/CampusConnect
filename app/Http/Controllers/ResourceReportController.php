<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ResourceReport;
use App\Models\Resource;
use App\Models\BorrowRequest;

class ResourceReportController extends Controller
{
    public function store(Request $request, $id)
    {
        $resource = Resource::findOrFail($id);

        $request->validate([
            'issue_type' => 'required|in:lost,damaged,unreturned',
            'description' => 'nullable|string',
            'evidence' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        $evidencePath = null;

        if ($request->hasFile('evidence')) {
            $evidencePath = $request->file('evidence')->store('reports', 'public');
        }

        $borrowRequest = BorrowRequest::where('resource_id', $resource->id)
            ->where('status', 'approved')
            ->latest()
            ->first();

        ResourceReport::create([
            'reporter_id' => auth()->id(),
            'resource_id' => $resource->id,
            'borrow_request_id' => $borrowRequest?->id,
            'issue_type' => $request->issue_type,
            'description' => $request->description,
            'evidence_path' => $evidencePath,
            'status' => 'pending',
        ]);

        return back()->with('success', 'Report submitted successfully for admin review.');
    }

    public function adminIndex()
    {
        $reports = ResourceReport::with(['reporter', 'resource', 'borrowRequest.user'])
            ->latest()
            ->get();

        return view('admin.reports.index', compact('reports'));
    }

    public function approve($id)
    {
        $report = ResourceReport::with('borrowRequest.user')->findOrFail($id);

        $report->update([
            'status' => 'approved',
        ]);

        return back()->with('success', 'Report approved. Borrower may receive penalty.');
    }

    public function reject($id)
    {
        $report = ResourceReport::findOrFail($id);

        $report->update([
            'status' => 'rejected',
        ]);

        return back()->with('success', 'Report rejected.');
    }
}
