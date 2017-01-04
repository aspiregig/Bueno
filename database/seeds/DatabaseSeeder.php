<?php

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\AdText;
use App\Models\User;
use App\Models\Availability;
use App\Models\Cuisine;
use App\Models\Kitchen;
use App\Models\Item;
use App\Models\Stock;
use Illuminate\Database\Eloquent\Model;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

//
//        Category::truncate();
//        Availability::truncate();
//        Cuisine::truncate();
//        AdText::truncate();
//
//
//        DB::table('categories')->insert([
//            [
//                'name' => 'Burger'
//            ],
//            [
//                'name' => 'Sandwich'
//            ],
//            [
//                'name' => 'Wraps'
//            ],
//            [
//                'name' => 'Biryanis'
//            ],
//            [
//                'name' => 'Rice'
//            ],
//            [
//                'name' => 'Snacks'
//            ],
//            [
//                'name' => 'Curries'
//            ],
//            [
//                'name' => 'Desserts'
//            ]
//        ]);
//
//        DB::table('cuisines')->insert([
//            [
//                'name' => 'Indian'
//            ],
//            [
//                'name' => 'Italian'
//            ],
//            [
//                'name' => 'Mexican'
//            ],
//            [
//                'name' => 'Chinese'
//            ],
//            [
//                'name' => 'Mughlai'
//            ]
//        ]);
//
//        DB::table('availabilities')->insert([
//            [
//                'name' => 'Breakfast'
//            ],
//            [
//                'name' => 'Brunch'
//            ],
//            [
//                'name' => 'Lunch'
//            ],
//            [
//                'name' => 'Snacks'
//            ],
//            [
//                'name' => 'Dinner'
//            ]
//        ]);
//
//        DB::table('availabilities')->insert([
//            [
//                'name' => 'Breakfast'
//            ],
//            [
//                'name' => 'Brunch'
//            ],
//            [
//                'name' => 'Lunch'
//            ],
//            [
//                'name' => 'Snacks'
//            ],
//            [
//                'name' => 'Dinner'
//            ]
//        ]);
//
//
//
//
//        factory('App\Models\AdText',10)->create();
//
//        factory('App\Models\Payment',9)->create();

          $kitchens = Kitchen::all();

          foreach($kitchens as $kitchen)
          {
            $items = Item::all();

            $meals = $items->filter(function($item){
              return $item->itemable_type == 'App\Models\Meal';
            });

            foreach($meals as $meal)
            {
              Stock::create([
                'kitchen_id' => $kitchen->id,
                'item_id'   => $meal->id
              ]);
            }
          }

    }
}
