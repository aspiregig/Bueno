<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddThumbnailPicUrlColumnMealsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
  public function up()
  {
    Schema::table('meals', function ($table) {
      $table->string('thumbnail_pic_url');
      $table->string('thumbnail_pic_mime');
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::table('meals', function ($table) {
      $table->dropColumn('thumbnail_pic_url');
      $table->dropColumn('thumbnail_pic_mime');
    });
  }
}
