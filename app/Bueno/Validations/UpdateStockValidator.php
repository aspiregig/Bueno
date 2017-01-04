<?php namespace Bueno\Validations;

class UpdateStockValidator extends Validator{

  /**
   * validation rules for create city form
   *
   * @return array
   */
  public function rules()
  {
    return [
        'value' => 'required|numeric|min:0'
    ];
  }
}
