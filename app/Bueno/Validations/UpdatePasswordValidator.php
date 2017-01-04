<?php namespace Bueno\Validations;

use App\Models\User;

class UpdatePasswordValidator extends Validator{

  /**
   * validation rules for create city form
   *
   * @return array
   */
  public function rules()
  {
    $rules = [
        'old_password'            =>  'required',
        'new_password'            =>  'required|min:6',
        'new_password_confirm'    =>  'required|same:new_password',
    ];

    return $rules;
  }
}
