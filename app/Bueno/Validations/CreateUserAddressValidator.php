<?php namespace Bueno\Validations;

class CreateUserAddressValidator extends Validator{

  /**
   * validation rules for create city form
   *
   * @return array
   */
  public function rules()
  {
    return [
        'address_name'  => 'required|min:4|max:15',
        'address'       => 'required|min:10',
        'area_id'       => 'required'
    ];
  }
}
