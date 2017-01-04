<?php namespace Bueno\Validations;

use Request;
use App\Models\Kitchen;

class CreateKitchenValidator extends Validator{

  /**
   * validation rules for create city form
   *
   * @return array
   */
  public function rules()
  {
    $request = Request::instance();

      $rules =  [
          'delivery_charge' => 'required|numeric|min:0',
          'packaging_charge' => 'required|numeric|min:0',
          'vat' => 'required|numeric|min:0|max:100',
          'service_tax' => 'required|numeric|min:0|max:100',
          'service_charge' => 'required|numeric|min:0',
          'jooleh_username' => 'required|min:2',
          'jooleh_token' => 'required|min:2',
      ];


// email rule of state
     switch($request->method())
     {
       case 'POST':

         $rules['name'] = 'required|unique:kitchens,name';
         $rules['managers'] = 'required|unique:kitchen_managers,user_id';

         break;

       case 'PATCH':

         $kitchen = Kitchen::find($request->input('id'));

         $rules['name'] =  'required|unique:kitchens,name,' . $kitchen->id . ',';

         break;

       default:break;
     }


      return $rules;
  }
}
