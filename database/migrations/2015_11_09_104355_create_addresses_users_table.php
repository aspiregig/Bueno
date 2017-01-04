<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAddressesUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('address_user', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->string('address_name');
            $table->string('address');
            $table->string('pincode');
            $table->integer('area_id');
            $table->integer('city_id');
            $table->integer('state_id');
            $table->integer('is_default');
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
        Schema::drop('address_user');
    }
}
