<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyAddressFieldDataType extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::table('orders', function ($table) {
        $table->longText('delivery_address')->change();
      });
      Schema::table('address_user', function ($table) {
        $table->longText('address')->change();
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      Schema::table('orders', function ($table) {
        $table->string('delivery_address')->change();
      });
      Schema::table('address_user', function ($table) {
        $table->string('address')->change();
      });
    }
}
