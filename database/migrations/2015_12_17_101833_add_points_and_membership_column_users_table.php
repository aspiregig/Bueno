<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPointsAndMembershipColumnUsersTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::table('users', function ($table) {
      $table->float('points')->nullable();
      $table->integer('membership_id')->nullable();
    });
  }
  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::table('users', function ($table) {
      $table->dropColumn('points')->nullable();
      $table->dropColumn('membership_id')->nullable();
    });
  }
}
