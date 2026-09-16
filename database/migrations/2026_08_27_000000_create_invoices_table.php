<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            // Nullable at insert time — the real value is backfilled right after
            // creation from the row's own id (see InvoiceService::create()).
            $table->string('invoice_number')->nullable()->unique();

            $table->foreignId('gym_id')->constrained()->cascadeOnDelete();
            $table->foreignId('gym_member_id')->nullable()->constrained('gym_members')->nullOnDelete();
            $table->foreignId('booking_id')->nullable()->constrained('bookings')->nullOnDelete();

            $table->enum('source', ['booking', 'manual']);

            // Snapshots at issue time — a receipt shouldn't change if the gym/member is edited later
            $table->string('gym_name_snapshot');
            $table->string('gym_address_snapshot')->nullable();
            $table->string('member_name');
            $table->string('member_phone')->nullable();
            $table->string('member_email')->nullable();
            $table->string('plan_label')->nullable();

            // Rupees, matches Booking::amount convention. Null when a manual add has no amount recorded.
            $table->unsignedInteger('amount')->nullable();

            // Booking invoices are always 'paid' (only issued after Razorpay activation succeeds).
            // Manual-add invoices reflect whatever the owner confirms — never assumed.
            $table->enum('payment_status', ['paid', 'unpaid'])->default('unpaid');

            $table->date('issued_date');

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('invoices');
    }
};
