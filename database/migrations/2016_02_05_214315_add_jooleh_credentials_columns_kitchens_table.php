<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddJoolehCredentialsColumnsKitchensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
  public function up()
  {
    Schema::table('kitchens', function ($table) {
      $table->string('jooleh_username');
      $table->string('jooleh_token');
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::table('kitchens', function ($table) {
      $table->dropColumn('jooleh_username');
      $table->dropColumn('jooleh_token');
    });
  }
}
