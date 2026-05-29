<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use MongoDB\Laravel\Eloquent\Model;

class RouteWaypoint extends Model
{
    use HasFactory;

    protected $table = 'route_waypoints';

    protected $fillable = [
        'ats_route_id',
        'waypoint_id',
        'waypoint_order',
    ];

    public function route()
    {
        return $this->belongsTo(AtsRoute::class, 'ats_route_id');
    }

    public function waypoint()
    {
        return $this->belongsTo(Waypoint::class);
    }
}