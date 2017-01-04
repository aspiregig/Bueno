<?php namespace Bueno\Validations;

class PressValidator extends Validator{

  /**
   * validation rules for create city form
   *
   * @return array
   */
  public function rules()
  {
    $request = request()->instance();

    $rules =  [
        'image_url' => 'required',
        'title'     => 'required',
        'url'       => 'required'
    ];

    return $rules;
  }

}
