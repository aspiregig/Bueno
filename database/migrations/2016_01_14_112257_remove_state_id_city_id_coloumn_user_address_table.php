<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveStateIdCityIdColoumnUserAddressTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
  public function up()
  {
    Schema::table('address_user', function ($table) {
      $table->dropColumn('city_id');
      $table->dropColumn('state_id');
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
      $table->dropColumn('city_id');
      $table->dropColumn('state_id');
    });
  }
}
