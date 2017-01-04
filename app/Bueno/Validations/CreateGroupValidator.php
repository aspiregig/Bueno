<?php namespace Bueno\Validations;

use Request;
use App\Models\Group;

class CreateGroupValidator extends Validator{

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


     switch($request->method())
     {
       case 'POST':

         $rules['name'] = 'required|unique:groups,name';

         break;

       case 'PATCH':

         $group = Group::find($request->input('id'));

         $rules['name'] =  'required|unique:groups,name,' . $group->id . ',';

         break;

       default:break;
     }


      return $rules;
  }
  
}
