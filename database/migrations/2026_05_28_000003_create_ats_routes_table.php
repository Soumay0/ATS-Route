<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ats_routes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('route_name');
            $table->decimal('distance', 8, 2)->nullable();
            $table->string('direction');
            $table->text('description')->nullable();
            $table->string('status')->default('active');
            $table->timestamps();

            $table->index(['route_name', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ats_routes');
    }
};