<?php namespace Bueno\Validations;

use Request;
use Page;

class CreatePageValidator extends Validator{

  /**
   * validation rules for create city form
   *
   * @return array
   */
  public function rules()
  {
    $request = Request::instance();

      $rules =  [
        'meta_title' => 'required|min:4',
        'meta_description' => 'required|min:20',
        
      ];

       switch($request->method())
     {
       case 'POST':

         $rules['slug'] = 'required|unique:pages,slug';

         break;

       case 'PATCH':

         $state = Page::find($request->input('id'));

         $rules['slug'] =  'required|unique:pages,slug,' . $state->id . ',';

         break;

       default:break;
     }


      return $rules;
    
  }
}
