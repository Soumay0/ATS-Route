<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use MongoDB\Laravel\Eloquent\Model;

class Waypoint extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'latitude',
        'longitude',
        'description',
        'type',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'latitude' => 'decimal:7',
            'longitude' => 'decimal:7',
            'is_active' => 'boolean',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function atsRoutes()
    {
        return $this->belongsToMany(AtsRoute::class, 'route_waypoints')
            ->withPivot('waypoint_order')
            ->withTimestamps()
            ->orderByPivot('waypoint_order');
    }
}