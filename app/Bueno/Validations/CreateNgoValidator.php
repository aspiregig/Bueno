<?php namespace Bueno\Validations;

use Request;
use App\Models\Ngo;

class CreateNgoValidator extends Validator{

  /**
   * validation rules for create city form
   *
   * @return array
   */
  public function rules()
  {
    $request = Request::instance();

      $rules =  [
      'default_donation_amount' => 'required|numeric|min:1',
      'display_image_url' => 'image_min_size:109,54,file_size_in_between:30,100,image_max_size:218,108,'
      ];


// email rule of Ngo
     switch($request->method())
     {
       case 'POST':

         $rules['name'] = 'required|unique:ngos,name';

         break;

       case 'PATCH':

         $ngo = Ngo::find($request->input('id'));

         $rules['name'] =  'required|unique:ngos,name,' . $ngo->id . ',';
         break;

       default:break;
     }


      return $rules;
  }
}
