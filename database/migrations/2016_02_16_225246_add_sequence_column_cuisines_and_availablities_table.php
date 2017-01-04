<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSequenceColumnCuisinesAndAvailablitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
  {
    Schema::table('cuisines', function ($table) {
      $table->integer('status');
      $table->integer('sequence');
    });
    Schema::table('availabilities', function ($table) {
      $table->integer('status');
      $table->integer('sequence');
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::table('cuisines', function ($table) {
      $table->dropColumn('status');
      $table->dropColumn('sequence');
    });
    Schema::table('availabilities', function ($table) {
      $table->dropColumn('status');
      $table->dropColumn('sequence');
    });
  }
}
