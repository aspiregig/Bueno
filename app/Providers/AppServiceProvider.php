<?php

namespace App\Providers;

use App\Models\Day;
use App\Models\Kitchen;
use App\Models\Setting;
use App\Models\Timing;
use Carbon\Carbon;
use View;
use App\Models\Area;
use Validator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // areas
        $kitchens = Kitchen::where('status', 1)->get();
        
        $areas = collect([]);

        foreach($kitchens as $kitchen)
        {
          $areas = $areas->merge($kitchen->areas->where('status', 1));
          $areas = $areas->sortBy(function($area){
            return $area->name;
          });
        }

      $master_switch = Setting::where('key', 'master_switch')->first();

      if($master_switch->value == 1)
      {
        $isOpen = 1;
      }
      else
      {
        $isOpen = 0;
      }

      $timings = Timing::all();

      $now = (int) Carbon::now()->format('Hi');

      $today = (int)  Carbon::now()->format('w');

      if(!in_array($today + 1, Day::where('status', 1)->get()->pluck('id')->toArray()) || $isOpen == 0)
      {
        $isOpen = 0;
      }
      else
      {
        $isOpen = 1;
      }

      if($isOpen)
      {
        if($timings->count()==0)
        $isOpen = 1;
        else
        $isOpen = 0;
        foreach ($timings as $timing)
        {

          $timing_from = (int) Carbon::createFromFormat('H:i',$timing->from)->format('Hi');

          $timing_to = (int) Carbon::createFromFormat('H:i',$timing->to)->format('Hi');

          if($timing_from<=$timing_to)
          {
            if( $now >= $timing_from && $now <= $timing_to )
            {
              $isOpen = $isOpen || 1;
            }
            else{
              $isOpen = $isOpen || 0;
            }
          }
          else
          {
            if( ($now <= $timing_to && $now >= 0) || ($now >= $timing_from && $now <= 2359) )
            {
              $isOpen = $isOpen || 1;
            }
            else
            {
              $isOpen = $isOpen || 0;
            }
          }
        }
      }

        View::share('areas', $areas);
        View::share('master_switch', $master_switch);
        View::share('is_open', $isOpen);

      
        Validator::extend('duplicate_entry', 'Bueno\Validations\CustomValidator@validateDupicateEntry');
        Validator::extend('image_min_size', 'Bueno\Validations\CustomValidator@validateImageMinSize');
        Validator::extend('image_ratio', 'Bueno\Validations\CustomValidator@validateImageRation');
        Validator::extend('image_max_size', 'Bueno\Validations\CustomValidator@validateImageMaxSize');
        Validator::extend('file_size_in_between', 'Bueno\Validations\CustomValidator@validateImageFileSizeInBetween');
        Validator::extend('alpha_spaces','Bueno\Validations\CustomValidator@validateAlphaOnly');
        Validator::extend('disallow_future_date', 'Bueno\Validations\CustomValidator@disallowFutureDate');
        Validator::extend('min_quantity','Bueno\Validations\CustomValidator@validateMinQuantity');
        Validator::extend('at_least_one_zero','Bueno\Validations\CustomValidator@validateAtLeastOneZero');
        Validator::extend('unique_area_of_city','Bueno\Validations\CustomValidator@validateUniqueAreaOfCity');
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
