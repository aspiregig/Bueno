<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddStatusAndSequenceColumnsCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
  public function up()
  {
    Schema::table('categories', function ($table) {
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
    Schema::table('categories', function ($table) {
      $table->dropColumn('status');
      $table->dropColumn('sequence');
    });
  }
}
