<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddHtmlContentColumnAdTextsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ad_texts', function ($table) {
            $table->longText('html_content');
            $table->dropColumn('left_column');
            $table->dropColumn('right_column');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ad_texts', function ($table) {
            $table->dropColumn('html_content');
            $table->longText('left_column');
            $table->longText('right_column');
        });
    }
}
