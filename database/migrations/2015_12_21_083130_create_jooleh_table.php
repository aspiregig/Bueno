<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJoolehTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('jooleh_log', function (Blueprint $table) {
      $table->increments('id');
      $table->integer('order_id');
      $table->string('oid');
      $table->longText('response');
      $table->timestamp('delivered_on')->nullable();
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::drop('jooleh_log');
  }
}
