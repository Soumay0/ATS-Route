<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('navigational_aids', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('aid_name');
            $table->string('aid_type');
            $table->string('frequency');
            $table->decimal('latitude', 10, 7);
            $table->decimal('longitude', 10, 7);
            $table->text('description')->nullable();
            $table->timestamps();

            $table->index(['aid_type', 'aid_name']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('navigational_aids');
    }
};