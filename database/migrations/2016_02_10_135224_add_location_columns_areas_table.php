<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddLocationColumnsAreasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
  public function up()
  {
    Schema::table('areas', function ($table) {
      $table->string('longitude')->nullable();
      $table->string('latitude')->nullable();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::table('areas', function ($table) {
      $table->dropColumn('longitude');
      $table->dropColumn('latitude');
    });
  }
}
