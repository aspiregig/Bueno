<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveAvailabilityMealTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
  public function up()
  {
    Schema::drop('availability_meal');
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::create('availability_meal', function (Blueprint $table) {
      $table->increments('id');
      $table->integer('meal_id');
      $table->integer('availability_id');
    });
  }
}
