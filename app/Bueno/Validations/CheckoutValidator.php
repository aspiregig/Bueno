<?php namespace Bueno\Validations;

class CheckoutValidator extends Validator{

  /**
   * validation rules for create city form
   *
   * @return array
   */
  public function rules()
  {
    return [
      'payment_mode_id' => 'required',
      'address_id'  => 'required',
      'area_id' => 'required',
      'items' => 'required'
    ];
  }
}
