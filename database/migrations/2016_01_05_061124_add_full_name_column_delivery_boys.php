<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFullNameColumnDeliveryBoys extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::table('delivery_boys', function ($table) {
        $table->dropColumn('first_name');
        $table->dropColumn('last_name');
        $table->string('full_name');
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      Schema::table('delivery_boys', function ($table) {
        $table->string('first_name');
        $table->string('last_name');
        $table->dropColumn('full_name');
      });
    }
}
