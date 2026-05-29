<?php

namespace App\Http\Controllers;

use App\Models\AtsRoute;
use App\Models\NavigationalAid;
use App\Models\Waypoint;

class ReportController extends Controller
{
    public function index()
    {
        $waypoints = Waypoint::all();
        $waypointTypes = $waypoints->groupBy('type')->map(function ($group, $key) {
            return (object) ['type' => $key, 'total' => $group->count()];
        })->sortByDesc('total')->values();

        $aids = NavigationalAid::all();
        $aidTypes = $aids->groupBy('aid_type')->map(function ($group, $key) {
            return (object) ['aid_type' => $key, 'total' => $group->count()];
        })->sortByDesc('total')->values();

        $routes = AtsRoute::all();
        $routeStatuses = $routes->groupBy('status')->map(function ($group, $key) {
            return (object) ['status' => $key, 'total' => $group->count()];
        })->sortByDesc('total')->values();

        return view('reports.index', [
            'waypointTypes' => $waypointTypes,
            'aidTypes' => $aidTypes,
            'routeStatuses' => $routeStatuses,
            'recentRoutes' => AtsRoute::latest()->limit(8)->get(),
        ]);
    }
}