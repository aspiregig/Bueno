<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddComboAttributesCombosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
  public function up()
  {
    Schema::table('combos', function (Blueprint $table) {
      $table->integer('type');
      $table->integer('spice_level');
      $table->integer('cuisine_id');
      $table->integer('category_id');
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::table('combos', function ($table) {
      $table->dropColumn('type');
      $table->dropColumn('spice_level');
      $table->dropColumn('cuisine_id');
      $table->dropColumn('category_id');
    });
  }
}
