<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('gym_members', function (Blueprint $table) {
            $table->id();

            // Which gym this member belongs to
            $table->foreignId('gym_id')->constrained()->cascadeOnDelete();

            // Linked traveler account, when this member came through a booking
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();

            // Most recent day-pass booking tied to this member, if any
            $table->foreignId('last_booking_id')->nullable()->constrained('bookings')->nullOnDelete();

            $table->string('name');
            $table->string('phone');
            $table->string('email')->nullable();

            // How they first joined — never changed after creation
            $table->enum('source', ['manual', 'booking']);

            $table->date('start_date');
            $table->date('due_date');

            // Display label, e.g. "Monthly", "7 Day Pass", "Custom (45 days)"
            $table->string('plan_label')->nullable();

            $table->text('notes')->nullable();
            $table->timestamp('last_reminder_sent_at')->nullable();

            $table->timestamps();

            // One member row per phone number, per gym — repeat bookings/renewals
            // update the existing row instead of creating a duplicate.
            $table->unique(['gym_id', 'phone']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('gym_members');
    }
};
