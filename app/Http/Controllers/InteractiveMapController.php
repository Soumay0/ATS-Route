<?php

namespace App\Http\Controllers;

use App\Models\AtsRoute;
use App\Models\NavigationalAid;
use App\Models\Waypoint;

class InteractiveMapController extends Controller
{
    public function index()
    {
        return view('map.index');
    }

    public function data()
    {
        $waypoints = Waypoint::select('_id', 'name', 'latitude', 'longitude', 'type')->get()->map(function ($waypoint) {
            return [
                'id' => (string) ($waypoint->_id ?? $waypoint->id),
                'name' => $waypoint->name,
                'lat' => (float) $waypoint->latitude,
                'lng' => (float) $waypoint->longitude,
                'type' => $waypoint->type,
            ];
        });

        $aids = NavigationalAid::select('_id', 'aid_name', 'latitude', 'longitude', 'aid_type', 'frequency')->get()->map(function ($aid) {
            return [
                'id' => (string) ($aid->_id ?? $aid->id),
                'name' => $aid->aid_name,
                'lat' => (float) $aid->latitude,
                'lng' => (float) $aid->longitude,
                'type' => strtolower($aid->aid_type),
                'frequency' => $aid->frequency,
            ];
        });

        $routes = AtsRoute::select('_id', 'route_name')->with(['waypoints' => function($q) {
            $q->select('_id', 'latitude', 'longitude');
        }])->get()->map(function ($route) {
            return [
                'id' => (string) ($route->_id ?? $route->id),
                'name' => $route->route_name,
                'points' => $route->waypoints->map(function ($wp) {
                    return [
                        'lat' => (float) $wp->latitude,
                        'lng' => (float) $wp->longitude,
                    ];
                })->values()->toArray(),
            ];
        });

        return response()->json([
            'waypoints' => $waypoints,
            'aids' => $aids,
            'routes' => $routes,
        ]);
    }
}