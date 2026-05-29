<?php

namespace App\Http\Controllers;

use App\Models\AtsRoute;
use App\Models\NavigationalAid;
use App\Models\RouteWaypoint;
use App\Models\Waypoint;

class InteractiveMapController extends Controller
{
    public function index()
    {
        return view('map.index');
    }

    public function data()
    {
        $waypoints = Waypoint::select('id', 'name', 'latitude', 'longitude', 'type')->get()->map(function ($waypoint) {
            return [
                'id' => (string) $waypoint->id,
                'name' => $waypoint->name,
                'lat' => (float) $waypoint->latitude,
                'lng' => (float) $waypoint->longitude,
                'type' => $waypoint->type,
            ];
        });

        $aids = NavigationalAid::select('id', 'aid_name', 'latitude', 'longitude', 'aid_type', 'frequency')->get()->map(function ($aid) {
            return [
                'id' => (string) $aid->id,
                'name' => $aid->aid_name,
                'lat' => (float) $aid->latitude,
                'lng' => (float) $aid->longitude,
                'type' => strtolower($aid->aid_type),
                'frequency' => $aid->frequency,
            ];
        });

        $routes = AtsRoute::select('id', 'route_name')->get()->map(function ($route) {
            $routeWaypointIds = RouteWaypoint::where('ats_route_id', $route->id)
                ->orderBy('waypoint_order')
                ->pluck('waypoint_id')
                ->values();

            $waypointsById = Waypoint::whereIn('id', $routeWaypointIds)->get()->keyBy('id');

            return [
                'id' => (string) $route->id,
                'name' => $route->route_name,
                'points' => $routeWaypointIds->map(function ($waypointId) use ($waypointsById) {
                    $wp = $waypointsById->get($waypointId);

                    if (! $wp) {
                        return null;
                    }

                    return [
                        'lat' => (float) $wp->latitude,
                        'lng' => (float) $wp->longitude,
                    ];
                })->filter()->values()->toArray(),
            ];
        });

        return response()->json([
            'waypoints' => $waypoints,
            'aids' => $aids,
            'routes' => $routes,
        ]);
    }
}