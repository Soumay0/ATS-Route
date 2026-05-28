<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class WeatherController extends Controller
{
    public function index(Request $request)
    {
        $stations = $request->query('stations', 'EGLL,OMDB,VIDP,VABB,KJFK,WSSS'); // Default to major hubs from mock data
        $cacheKey = "weather_metar_{$stations}";

        // METARs update roughly every hour, caching for 10 minutes is very safe
        $weather = Cache::remember($cacheKey, 600, function () use ($stations) {
            $response = Http::timeout(10)->get('https://aviationweather.gov/api/data/metar', [
                'ids' => $stations,
                'format' => 'json',
            ]);

            if ($response->successful()) {
                return $response->json();
            }

            return [];
        });

        return response()->json($weather);
    }
}
