<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

$factory->define('App\Models\User', function (Faker\Generator $faker) {
    return [
        'group_id' => $faker->numberBetween(1,4),
        'first_name' => $faker->firstName,
        'last_name' => $faker->lastName,
        'phone' => $faker->phoneNumber,
        'email' => $faker->email,
        'password' => bcrypt(str_random(10)),
        'remember_token' => str_random(10),
    ];
});

$factory->define('App\Models\Order', function (Faker\Generator $faker) {
    return [
        'user_id' => $faker->numberBetween(1,5),
        'address_id'=>$faker->numberBetween(1,4),
        'source_id' => $faker->numberBetween(1,4),
        'payment_mode_id'=>$faker->numberBetween(1,4),
        'instruction' => $faker->sentence,
        'status' => $faker->numberBetween(1,5),
        'coupon_id'=>$faker->numberBetween(0,0),
        'created_at'=>$faker->dateTimeThisMonth,
        'updated_at'=>$faker->dateTimeThisMonth,

    ];
});


$factory->define('App\Models\Payment', function (Faker\Generator $faker) {
    return [
        'order_id' => factory('App\Models\Order')->create()->id,
        'status'=>1,
        'amount' => $faker->randomFloat(2,380,2100),
        'payment_mode_id'=>$faker->numberBetween(1,4),
        'transaction_id' => $faker->bothify('BUE??####'),
        'payment_mode_id'=>$faker->numberBetween(1,4),
        'status' => 1,
        'comment' => $faker->sentence,
        'created_at'=>$faker->dateTimeThisMonth,
        'updated_at'=>$faker->dateTimeThisMonth,

    ];
});

$factory->define('App\Models\State', function (Faker\Generator $faker) {
    return [
        'name' => $faker->locale,
    ];
});



$factory->define('App\Models\City', function (Faker\Generator $faker) {
    return [
        'name' => $faker->locale,
        'state_id' => factory('App\Models\State')->create()->id,
    ];
});

$factory->define('App\Models\Area', function (Faker\Generator $faker) {
    return [
        'name' => $faker->locale,
        'city_id' => factory('App\Models\City')->create()->id,
    ];
});


$factory->define('App\Models\AdText', function (Faker\Generator $faker) {
    return [
        'name' => $faker->sentence,
        'left_column' => $faker->paragraph,
        'right_column' => $faker->paragraph,
    ];
});





