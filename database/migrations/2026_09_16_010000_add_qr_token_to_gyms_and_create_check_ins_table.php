<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('gyms', function (Blueprint $table) {
            // Stable, printable QR token — deliberately not the gym's own id
            // (a sequential integer would be trivially enumerable). Generated
            // lazily on first request, stays constant until the owner
            // explicitly regenerates it.
            $table->string('qr_token')->nullable()->unique()->after('upi_id');
        });

        Schema::create('check_ins', function (Blueprint $table) {
            $table->id();

            // One row per scan event — a multi-day/monthly pass holder checks
            // in on several different days, so this can't be a single
            // timestamp column on Booking.
            $table->foreignId('booking_id')->constrained()->cascadeOnDelete();
            $table->foreignId('gym_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            $table->timestamp('scanned_at');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('check_ins');

        Schema::table('gyms', function (Blueprint $table) {
            $table->dropColumn('qr_token');
        });
    }
};
