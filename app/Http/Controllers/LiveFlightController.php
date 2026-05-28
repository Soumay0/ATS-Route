<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class LiveFlightController extends Controller
{
    public function index(Request $request)
    {
        // Global bounds - removing lamin/lomin/lamax/lomax fetches global data
        $cacheKey = "opensky_flights_global";

        // Cache for 60 seconds to avoid API rate limits
        $flights = Cache::remember($cacheKey, 60, function () {
            $response = Http::timeout(15)->get('https://opensky-network.org/api/states/all');

            if ($response->successful()) {
                $data = $response->json();
                $states = $data['states'] ?? [];
                
                // Format the data to be more frontend-friendly
                $formattedFlights = [];
                // Limit to 1500 flights to keep the frontend map performant while showing global distribution
                foreach (array_slice($states, 0, 1500) as $state) { 
                    $formattedFlights[] = [
                        'icao24' => $state[0],
                        'callsign' => trim($state[1]),
                        'origin_country' => $state[2],
                        'longitude' => $state[5],
                        'latitude' => $state[6],
                        'altitude' => $state[7] ?? $state[13], // baro_altitude or geo_altitude
                        'velocity' => $state[9], // m/s
                        'true_track' => $state[10], // degrees
                        'vertical_rate' => $state[11], // m/s
                        'on_ground' => $state[8],
                    ];
                }
                return $formattedFlights;
            }

            return [];
        });

        return response()->json($flights);
    }
}
