<?php namespace Bueno\Validations;

use Request;
use App\Models\DeliveryBoy;

class CreateDeliveryBoyValidator extends Validator{

  /**
   * validation rules for create city form
   *
   * @return array
   */
  public function rules()
  {

    $request = Request::instance();

    $rules =  [
        'full_name' => 'required|max:15',
        'kitchen_id' => 'required',
    ];
    switch($request->method())
     {
       case 'POST':

         $rules['phone'] = 'required|digits:10|numeric|unique:delivery_boys,phone|min:3000000000';

         break;

       case 'PATCH':

         $delivery_boy = DeliveryBoy::find($request->input('id'));

         $rules['phone'] =  'required|digits:10|numeric|min:3000000000|unique:delivery_boys,phone,' . $delivery_boy->id . ',';

         break;

       default:break;
     }
     return $rules;
  }
}
