<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Global amenities list
        Schema::create('amenities', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('icon')->default('🏋️');
            $table->boolean('is_default')->default(false);
            $table->timestamps();
        });

        // Pivot — which gym has which amenity selected
        Schema::create('gym_amenity', function (Blueprint $table) {
            $table->foreignId('gym_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('amenity_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->primary(['gym_id', 'amenity_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('gym_amenity');
        Schema::dropIfExists('amenities');
    }
};