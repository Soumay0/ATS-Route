<?php

namespace Database\Seeders;

use App\Models\AtsRoute;
use App\Models\NavigationalAid;
use App\Models\User;
use App\Models\Waypoint;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $controller = User::updateOrCreate(
            ['email' => 'controller@aeroroute.test'],
            [
                'name' => 'Aviation Controller',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );

        $waypoints = collect([
            ['name' => 'DELTA', 'latitude' => 28.613939, 'longitude' => 77.209023, 'description' => 'Primary departure waypoint for northern sector.', 'type' => 'FIX'],
            ['name' => 'ECHO', 'latitude' => 28.756048, 'longitude' => 77.220560, 'description' => 'Crosswind corridor waypoint.', 'type' => 'INTERSECTION'],
            ['name' => 'FOXTROT', 'latitude' => 28.932200, 'longitude' => 77.098000, 'description' => 'Navigation fix near the approach sector.', 'type' => 'FIX'],
            ['name' => 'GOLF', 'latitude' => 29.001200, 'longitude' => 77.310000, 'description' => 'Holding fix for inbound traffic management.', 'type' => 'HOLDING'],
        ])->map(function (array $waypoint) use ($controller) {
            return Waypoint::updateOrCreate(
                ['name' => $waypoint['name']],
                $waypoint + ['user_id' => $controller->id, 'is_active' => true]
            );
        });

        $aids = collect([
            ['aid_name' => 'DEL VOR', 'aid_type' => 'VOR', 'frequency' => '113.80', 'latitude' => 28.556, 'longitude' => 77.100, 'description' => 'Primary VOR reference for the corridor.'],
            ['aid_name' => 'MUM NDB', 'aid_type' => 'NDB', 'frequency' => '365', 'latitude' => 19.089, 'longitude' => 72.865, 'description' => 'Legacy NDB for regional tracking.'],
            ['aid_name' => 'GOL DME', 'aid_type' => 'DME', 'frequency' => '117.20', 'latitude' => 28.900, 'longitude' => 77.240, 'description' => 'Distance measuring equipment anchor.'],
        ])->map(function (array $aid) use ($controller) {
            return NavigationalAid::updateOrCreate(
                ['aid_name' => $aid['aid_name']],
                $aid + ['user_id' => $controller->id]
            );
        });

        $route = AtsRoute::updateOrCreate(
            ['route_name' => 'DEL-NORTH CORRIDOR'],
            [
                'user_id' => $controller->id,
                'direction' => 'Northbound',
                'distance' => 185.40,
                'description' => 'Sample ATS airway linking major corridor waypoints.',
                'status' => 'active',
            ]
        );

        $route->waypoints()->sync([
            $waypoints[0]->id => ['waypoint_order' => 1],
            $waypoints[1]->id => ['waypoint_order' => 2],
            $waypoints[2]->id => ['waypoint_order' => 3],
            $waypoints[3]->id => ['waypoint_order' => 4],
        ]);

        AtsRoute::updateOrCreate(
            ['route_name' => 'MUM-WEST LINK'],
            [
                'user_id' => $controller->id,
                'direction' => 'Westbound',
                'distance' => 210.75,
                'description' => 'Secondary route for route density visualization.',
                'status' => 'planned',
            ]
        );
    }
}
