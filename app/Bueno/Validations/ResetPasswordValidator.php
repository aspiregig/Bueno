<?php namespace Bueno\Validations;

class ResetPasswordValidator extends Validator{

  /**
   * validation rules for create city form
   *
   * @return array
   */
  public function rules()
  {
    return [
        'phone'             =>  'required|digits:10|numeric|min:3000000000',
        'code'              => 'required',
        'password'          =>  'required|min:6',
        'confirm_password'  =>  'required|same:password'
    ];
  }
}
