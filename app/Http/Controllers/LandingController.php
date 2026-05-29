<?php

namespace App\Http\Controllers;

use App\Models\AtsRoute;
use App\Models\NavigationalAid;
use App\Models\Waypoint;
use Illuminate\Support\Facades\Route;

class LandingController extends Controller
{
    public function __invoke()
    {
        return view('welcome', [
            'canLogin' => Route::has('login'),
            'canRegister' => Route::has('register'),
            'stats' => [
                'waypoints' => Waypoint::count(),
                'routes' => AtsRoute::count(),
                'aids' => NavigationalAid::count(),
            ],
            'featuredWaypoints' => Waypoint::latest()->limit(6)->get(),
            'featuredRoutes' => AtsRoute::with('waypoints')->latest()->limit(3)->get(),
        ]);
    }
}