<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMobileAndLandmarkColumnsAddressUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
  public function up()
  {
    Schema::table('address_user', function ($table) {
      $table->string('mobile')->nullable();
      $table->string('landmark')->nullable();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::table('address_user', function ($table) {
      $table->dropColumn('mobile');
      $table->dropColumn('landmark');
    });
  }
}
