<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();

            // Who booked
            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();

            // Which gym
            $table->foreignId('gym_id')
                ->constrained()
                ->cascadeOnDelete();

            // Which plan was booked
            $table->foreignId('gym_plan_id')
                ->constrained('gym_plans')
                ->cascadeOnDelete();

            // Booking details
            $table->string('booking_ref')->unique(); // e.g. GP-2025-4982
            $table->date('start_date');
            $table->date('end_date');

            // Payment info
            $table->string('razorpay_order_id')->nullable()->index();
            $table->string('razorpay_payment_id')->nullable()->index();
            $table->string('razorpay_signature')->nullable();

            // Amount paid by user (in paise for Razorpay, stored as rupees here)
            $table->unsignedInteger('amount'); // ₹ full amount user paid

            // Status
            $table->enum('status', [
                'pending',    // order created, payment not done yet
                'paid',       // payment verified
                'cancelled',  // user cancelled or payment failed
                'expired',    // pass expired
            ])->default('pending');

            // QR pass
            $table->string('qr_code')->nullable()->unique(); // unique token for QR scan

            $table->timestamps();
        });

        Schema::create('settlements', function (Blueprint $table) {
            $table->id();

            // Linked booking
            $table->foreignId('booking_id')
                ->constrained()
                ->cascadeOnDelete();

            // Gym receiving the payout
            $table->foreignId('gym_id')
                ->constrained()
                ->cascadeOnDelete();

            // Money breakdown (all in ₹)
            $table->unsignedInteger('gross_amount');      // what user paid e.g. ₹100
            $table->unsignedInteger('commission_amount'); // our cut e.g. ₹10
            $table->unsignedInteger('payout_amount');     // gym gets e.g. ₹90
            $table->decimal('commission_pct', 5, 2);     // commission % at time of booking e.g. 10.00

            // Gym's UPI at time of booking (snapshot — important if they change it later)
            $table->string('gym_upi_id');

            // Payout status
            $table->enum('payout_status', [
                'pending',   // not yet paid to gym
                'paid',      // manually transferred
            ])->default('pending');

            $table->timestamp('paid_at')->nullable(); // when we marked it as paid

            // Razorpay settlement reference (for when we automate payouts later)
            $table->string('razorpay_payout_id')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('settlements');
        Schema::dropIfExists('bookings');
    }
};