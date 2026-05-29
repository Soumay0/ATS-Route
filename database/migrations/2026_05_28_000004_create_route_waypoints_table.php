<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('route_waypoints', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ats_route_id')->constrained('ats_routes')->cascadeOnDelete();
            $table->foreignId('waypoint_id')->constrained()->cascadeOnDelete();
            $table->unsignedInteger('waypoint_order');
            $table->timestamps();

            $table->unique(['ats_route_id', 'waypoint_id']);
            $table->index(['ats_route_id', 'waypoint_order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('route_waypoints');
    }
};