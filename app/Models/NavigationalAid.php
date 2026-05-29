<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use MongoDB\Laravel\Eloquent\Model;

class NavigationalAid extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'aid_name',
        'aid_type',
        'frequency',
        'latitude',
        'longitude',
        'description',
    ];

    protected function casts(): array
    {
        return [
            'latitude' => 'decimal:7',
            'longitude' => 'decimal:7',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}