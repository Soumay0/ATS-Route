<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Slot extends Model
{
    use HasFactory;

    protected $fillable = [
        'slot_id',
        'airline',
        'flight',
        'time',
        'block_time',
        'status',
    ];
}
