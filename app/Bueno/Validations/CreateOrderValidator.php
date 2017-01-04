<?php namespace Bueno\Validations;

class CreateOrderValidator extends Validator{

  /**
   * validation rules for create city form
   *
   * @return array
   */
  public function rules()
  {
    return [
        'first_name' => 'required',
        'phone' => 'required|numeric|digits:10|min:3000000000',
        'email' => 'email|min:5',
        'address' => 'required|min:3',
        'area_id' => 'required',
        'meals' => 'duplicate_entry',
        'combos' => 'duplicate_entry',
        'total_items_quantity' => 'required|numeric|min:1|max:10000',
    ];
  }
}
