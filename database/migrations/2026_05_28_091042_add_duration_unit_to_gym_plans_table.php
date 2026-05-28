<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class AddDurationUnitToGymPlansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table('gym_plans', function (Blueprint $table) {
            $table->unsignedInteger('duration')->after('name')->default(1);
            $table->enum('unit', ['day', 'month'])->after('duration')->default('day');
            $table->boolean('is_default')->after('unit')->default(false);
            $table->boolean('is_enabled')->after('is_default')->default(true);
        });
 
        // Update existing seeded plans to match new structure
        DB::table('gym_plans')->where('type', 'day')->update([
            'duration'   => 1,
            'unit'       => 'day',
            'is_default' => true,
            'name'       => '1 Day Pass',
        ]);
        DB::table('gym_plans')->where('type', '3_day')->update([
            'duration'   => 3,
            'unit'       => 'day',
            'is_default' => true,
            'name'       => '3 Day Pass',
        ]);
        DB::table('gym_plans')->where('type', '7_day')->update([
            'duration'   => 7,
            'unit'       => 'day',
            'is_default' => true,
            'name'       => '7 Day Pass',
        ]);
        DB::table('gym_plans')->where('type', 'monthly')->update([
            'duration'   => 1,
            'unit'       => 'month',
            'is_default' => true,
            'name'       => '1 Month Pass',
        ]);
    }

    public function down(): void
    {
        Schema::table('gym_plans', function (Blueprint $table) {
            $table->dropColumn(['duration', 'unit', 'is_default', 'is_enabled']);
        });
    }
}
