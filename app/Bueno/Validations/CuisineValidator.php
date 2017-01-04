<?php namespace Bueno\Validations;

use Request;
use App\Models\Cuisine;

class CuisineValidator extends Validator{

  /**
   * validation rules for create city form
   *
   * @return array
   */
  public function rules()
  {
    $request = Request::instance();

    $rules =  [
        'sequence' => 'required|numeric|min:0',
        'status' => 'required|numeric|min:0|max:1'

    ];


    switch($request->method())
    {
      case 'POST':

        $rules['name'] = 'required|unique:cuisines,name';

        break;

      case 'PATCH':

        $cuisine = Cuisine::find($request->input('id'));

        $rules['name'] =  'required|unique:cuisines,name,' . $cuisine->id . ',';

        break;

      default:break;
    }


    return $rules;
  }

}
