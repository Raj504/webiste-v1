<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMediaColumnsToGymsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('gyms', function (Blueprint $table) {
            // Cover photo — single object, two possible sources: upload | unsplash
            // Structure: { url, cloudinary_id, source }
            $table->json('cover_photo')->nullable()->after('status');
 
            // Gym photos — array of objects, max 10
            // Structure: [{ id, url, cloudinary_id, source, sort_order }]
            $table->json('photos')->default('[]')->after('cover_photo');
 
            // Videos — object with three optional keys
            // Structure: { youtube, instagram, upload: { url, cloudinary_id } }
            $table->json('videos')->default('{}')->after('photos');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('gyms', function (Blueprint $table) {
            $table->dropColumn(['cover_photo', 'photos', 'videos']);
        });
    }
}
