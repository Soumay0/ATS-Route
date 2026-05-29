<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use MongoDB\Laravel\Eloquent\Model;

class AtsRoute extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'route_name',
        'distance',
        'direction',
        'description',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'distance' => 'decimal:2',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function waypoints()
    {
        return $this->belongsToMany(Waypoint::class, 'route_waypoints')
            ->withPivot('waypoint_order')
            ->withTimestamps()
            ->orderByPivot('waypoint_order');
    }
}