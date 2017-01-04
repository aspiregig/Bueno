<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddGiveGroupColumnCheckGiveawayTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::table('coupon_giveaway', function ($table) {
      $table->integer('group');
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::table('coupon_giveaway', function ($table) {
      $table->dropColumn('group');
    });
  }
}
