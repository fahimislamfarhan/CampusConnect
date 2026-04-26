<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Resource;

class ResourceController extends Controller
{
    public function index(Request $request)
    {
        $query = Resource::query();

        if ($request->category) {
            $query->where('category', $request->category);
        }

        if ($request->condition) {
            $query->where('condition', $request->condition);
        }

        if ($request->available !== null && $request->available !== '') {
            $query->where('available', $request->available);
        }

        $resources = $query
            ->with('user')
            ->orderBy('available', 'desc')
            ->latest()
            ->get();

        return view('resources.index', compact('resources'));
    }

    public function create()
    {
        return view('resources.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'file' => 'required|file',
            'category' => 'required',
            'condition' => 'required',
            'available' => 'required',
            'type' => 'required',
            'location_name' => 'nullable|string',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
        ]);

        $path = $request->file('file')->store('resources', 'public');

        Resource::create([
            'title' => $request->title,
            'description' => $request->description,
            'file_path' => $path,
            'category' => $request->category,
            'condition' => $request->condition,
            'available' => $request->available,
            'type' => $request->type,
            'duration' => $request->duration,
            'location_name' => $request->location_name,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'user_id' => auth()->id()
        ]);

        return redirect()->route('resources.index');
    }

    public function show($id)
    {
        $resource = Resource::with([
            'user.reviewsReceived',
            'borrowRequests.user',
            'reviews.user'
        ])->findOrFail($id);

        return view('resources.show', compact('resource'));
    }

    public function edit($id)
    {
        $resource = Resource::findOrFail($id);

        if ($resource->user_id != auth()->id()) {
            abort(403);
        }

        return view('resources.edit', compact('resource'));
    }

    public function update(Request $request, $id)
    {
        $resource = Resource::findOrFail($id);

        if ($resource->user_id != auth()->id()) {
            abort(403);
        }

        $request->validate([
            'title' => 'required',
            'category' => 'required',
            'condition' => 'required',
            'available' => 'required',
            'type' => 'required',
            'location_name' => 'nullable|string',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
        ]);

        if ($request->hasFile('file')) {
            $path = $request->file('file')->store('resources', 'public');
            $resource->file_path = $path;
        }

        $resource->update([
            'title' => $request->title,
            'description' => $request->description,
            'category' => $request->category,
            'condition' => $request->condition,
            'available' => $request->available,
            'type' => $request->type,
            'duration' => $request->duration,
            'location_name' => $request->location_name,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
        ]);

        return redirect()->route('resources.show', $resource->id);
    }

    public function destroy($id)
    {
        $resource = Resource::findOrFail($id);

        if ($resource->user_id != auth()->id()) {
            abort(403);
        }

        $resource->delete();

        return redirect()->route('resources.index')->with('success', 'Resource deleted successfully!');
    }
}