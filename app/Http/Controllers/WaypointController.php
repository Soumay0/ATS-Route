<?php

namespace App\Http\Controllers;

use App\Models\Waypoint;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class WaypointController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->string('search')->toString();

        $waypoints = Waypoint::query()
            ->when($search, function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('type', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('waypoints.index', compact('waypoints', 'search'));
    }

    public function create()
    {
        return view('waypoints.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'latitude' => ['required', 'numeric', 'between:-90,90'],
            'longitude' => ['required', 'numeric', 'between:-180,180'],
            'description' => ['nullable', 'string'],
            'type' => ['required', 'string', 'max:100'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $data['user_id'] = $request->user()->id;
        $data['is_active'] = $request->boolean('is_active');

        Waypoint::create($data);

        return redirect()->route('waypoints.index')->with('status', 'Waypoint created successfully.');
    }

    public function edit(Waypoint $waypoint)
    {
        return view('waypoints.edit', compact('waypoint'));
    }

    public function update(Request $request, Waypoint $waypoint): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'latitude' => ['required', 'numeric', 'between:-90,90'],
            'longitude' => ['required', 'numeric', 'between:-180,180'],
            'description' => ['nullable', 'string'],
            'type' => ['required', 'string', 'max:100'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $data['is_active'] = $request->boolean('is_active');

        $waypoint->update($data);

        return redirect()->route('waypoints.index')->with('status', 'Waypoint updated successfully.');
    }

    public function destroy(Waypoint $waypoint): RedirectResponse
    {
        $waypoint->delete();

        return redirect()->route('waypoints.index')->with('status', 'Waypoint deleted successfully.');
    }
}