<?php namespace Bueno\Validations;

class CreateCityValidator extends Validator{

  /**
   * validation rules for create city form
   *
   * @return array
   */
  public function rules()
  {
    return [
        'name' => 'required'
    ];
  }
}
