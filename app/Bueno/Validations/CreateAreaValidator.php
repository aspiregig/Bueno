<?php namespace Bueno\Validations;

use Request;
use App\Models\Area;

class CreateAreaValidator extends Validator{

  /**
   * validation rules for create city form
   *
   * @return array
   */
  public function rules()
  {
    $request = Request::instance();

    $rules =  [
        'city_id' => 'required',
        'min_order_amount' => 'required|numeric|min:0',
        'delivery_time' => 'required|numeric|min:1',
        'longitude' => 'required|numeric',
        'latitude' => 'required|numeric',
      ];


// email rule of state
     switch($request->method())
     {
       case 'POST':

         $rules['name'] = 'required|unique_area_of_city:'.$request->input('city_id');

         break;

       case 'PATCH':


         $rules['name'] =  'required|unique_area_of_city:'.$request->input('city_id').',' . $request->input('id');

         break;

       default:break;
     }


      return $rules;
  }
}
