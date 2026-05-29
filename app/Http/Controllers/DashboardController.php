<?php

namespace App\Http\Controllers;

use App\Models\AtsRoute;
use App\Models\NavigationalAid;
use App\Models\Waypoint;
use App\Models\User;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class DashboardController extends Controller
{
    public function index()
    {
        $days = CarbonPeriod::create(now()->subDays(6), now())->toArray();
        $labels = [];
        $values = [];

        foreach ($days as $day) {
            $date = Carbon::parse($day);
            $labels[] = $date->format('D');
            $values[] = AtsRoute::whereDate('created_at', $date)->count();
        }

        $mongoStatus = 'Disconnected';
        try {
            \Illuminate\Support\Facades\DB::connection('mongodb')->getMongoClient()->listDatabases();
            $mongoStatus = 'Connected';
        } catch (\Exception $e) {
            $mongoStatus = 'Error';
        }

        return view('dashboard.index', [
            'mongoStatus' => $mongoStatus,
            'waypoints' => Waypoint::count(),
            'routes' => AtsRoute::count(),
            'aids' => NavigationalAid::count(),
            'users' => User::whereNotNull('email_verified_at')->count(),
            'chartLabels' => $labels,
            'chartValues' => $values,
            'recentRoutes' => AtsRoute::with('waypoints')->latest()->limit(5)->get(),
            'recentWaypoints' => Waypoint::latest()->limit(5)->get(),
            'recentAids' => NavigationalAid::latest()->limit(5)->get(),
        ]);
    }
}