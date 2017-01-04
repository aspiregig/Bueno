<?php namespace Bueno\Validations;

class CreateCaterLeadsValidator extends Validator{

  /**
   * validation rules for create city form
   *
   * @return array
   */
  public function rules()
  {
    return [
        'full_name' => 'required',
        'phone' => 'required',
        'email' => 'required',
        'query' => 'required|max:400'
    ];
  }
}
