<?php

namespace App\Http\Controllers;

use App\Models\AtsRoute;
use App\Models\Waypoint;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class AtsRouteController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->string('search')->toString();

        $routes = AtsRoute::with('waypoints')
            ->when($search, function ($query) use ($search) {
                $query->where('route_name', 'like', "%{$search}%")
                    ->orWhere('direction', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('ats-routes.index', compact('routes', 'search'));
    }

    public function create()
    {
        return view('ats-routes.create', [
            'waypoints' => Waypoint::orderBy('name')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'route_name' => ['required', 'string', 'max:255'],
            'direction' => ['required', 'string', 'max:50'],
            'distance' => ['nullable', 'numeric', 'min:0'],
            'description' => ['nullable', 'string'],
            'status' => ['nullable', 'in:active,inactive,planned'],
            'waypoints' => ['required', 'array', 'min:2'],
            'waypoints.*' => ['integer', 'exists:waypoints,id'],
        ]);

        $route = AtsRoute::create([
            'user_id' => $request->user()->id,
            'route_name' => $data['route_name'],
            'direction' => $data['direction'],
            'distance' => $data['distance'] ?? null,
            'description' => $data['description'] ?? null,
            'status' => $data['status'] ?? 'active',
        ]);

        $this->syncRouteWaypoints($route, $data['waypoints']);

        return redirect()->route('ats-routes.index')->with('status', 'ATS route created successfully.');
    }

    public function edit(AtsRoute $atsRoute)
    {
        return view('ats-routes.edit', [
            'routeModel' => $atsRoute->load('waypoints'),
            'waypoints' => Waypoint::orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, AtsRoute $atsRoute): RedirectResponse
    {
        $data = $request->validate([
            'route_name' => ['required', 'string', 'max:255'],
            'direction' => ['required', 'string', 'max:50'],
            'distance' => ['nullable', 'numeric', 'min:0'],
            'description' => ['nullable', 'string'],
            'status' => ['nullable', 'in:active,inactive,planned'],
            'waypoints' => ['required', 'array', 'min:2'],
            'waypoints.*' => ['integer', 'exists:waypoints,id'],
        ]);

        $atsRoute->update([
            'route_name' => $data['route_name'],
            'direction' => $data['direction'],
            'distance' => $data['distance'] ?? null,
            'description' => $data['description'] ?? null,
            'status' => $data['status'] ?? 'active',
        ]);

        $this->syncRouteWaypoints($atsRoute, $data['waypoints']);

        return redirect()->route('ats-routes.index')->with('status', 'ATS route updated successfully.');
    }

    public function destroy(AtsRoute $atsRoute): RedirectResponse
    {
        $atsRoute->delete();

        return redirect()->route('ats-routes.index')->with('status', 'ATS route deleted successfully.');
    }

    private function syncRouteWaypoints(AtsRoute $route, array $waypointIds): void
    {
        $syncData = [];

        foreach (array_values($waypointIds) as $index => $waypointId) {
            $syncData[$waypointId] = ['waypoint_order' => $index + 1];
        }

        $route->waypoints()->sync($syncData);
    }
}