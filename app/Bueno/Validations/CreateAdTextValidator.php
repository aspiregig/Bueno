<?php namespace Bueno\Validations;

class CreateAdTextValidator extends Validator{

  /**
   * validation rules for create city form
   *
   * @return array
   */
  public function rules()
  {
    return [
        'name' => 'required',
        'html_content' => 'required'
    ];
  }
}
