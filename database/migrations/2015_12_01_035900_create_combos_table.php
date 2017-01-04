<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCombosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('combos', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('slug');
            $table->integer('serves');
            $table->string('display_pic_url');
            $table->string('display_pic_mime');
            $table->integer('weight');
            $table->float('original_price');
            $table->float('discount_price')->nullable();
            $table->string('description');
            $table->integer('status');
            $table->integer('is_xprs');
            $table->integer('is_hot_deal');
            $table->integer('is_sellable');
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
        Schema::drop('combos');
    }
}
