<?php namespace Bueno\Validations;

class UpdateBuenoSettingValidator extends Validator{

  /**
   * validation rules for create city form
   *
   * @return array
   */
  public function rules()
  {
    return [
        'referral_points' => 'required|numeric|min:0|max:1000',
        'referrer_points' => 'required|numeric|min:0|max:1000',
    ];
  }
}
