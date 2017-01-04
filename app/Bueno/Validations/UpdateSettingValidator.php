<?php namespace Bueno\Validations;

class UpdateSettingValidator extends Validator{

  /**
   * validation rules for create city form
   *
   * @return array
   */
  public function rules()
  {
    return [
        'refresh_rate' => 'required|numeric|min:1',
    ];
  }
}
