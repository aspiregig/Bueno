<?php namespace Bueno\Validations;

use Request;
use App\Models\State;

class CreateStateValidator extends Validator{

  /**
   * validation rules for create city form
   *
   * @return array
   */
  public function rules()
  {
    $request = Request::instance();

      $rules =  [
        
      ];


// email rule of state
     switch($request->method())
     {
       case 'POST':

         $rules['name'] = 'required|unique:states,name';

         break;

       case 'PATCH':

         $state = State::find($request->input('id'));

         $rules['name'] =  'required|unique:states,name,' . $state->id . ',';

         break;

       default:break;
     }


      return $rules;
  }
}
