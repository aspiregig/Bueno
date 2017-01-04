<?php namespace Bueno\Validations;

use Request;

class ReportsValidator extends Validator{

  /**
   * validation rules for create city form
   *
   * @return array
   */
  public function rules()
  {
    $request = Request::instance();

    $rules =  [
        'start' => 'required|numeric|min:0',
        'end' => 'required|numeric|min:0|max:1'
    ];

    return $rules;
  }

}
