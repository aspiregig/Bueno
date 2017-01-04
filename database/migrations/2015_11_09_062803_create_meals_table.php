<?php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMealsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('meals', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('slug');
            $table->integer('serves');
            $table->integer('type');
            $table->integer('spice_level');
            $table->integer('cuisine_id');
            $table->integer('category_id');
            $table->integer('stock');
            $table->string('display_pic_url');
            $table->integer('weight');
            $table->float('original_price');
            $table->float('discount_price')->nullable();
            $table->string('description');
            $table->integer('status');
            $table->integer('is_xprs');
            $table->integer('is_hot_deal');
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
        Schema::drop('meals');
    }
}
