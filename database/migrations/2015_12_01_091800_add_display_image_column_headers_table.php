<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDisplayImageColumnHeadersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('headers', function ($table) {
            $table->string('display_image_url');
            $table->string('display_image_mime');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('headers', function ($table) {
            $table->dropColumn('display_image_url');
            $table->dropColumn('display_image_mime');
        });
    }
}
