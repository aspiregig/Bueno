<?php namespace Bueno\Validations;

use Request;
use App\Models\User;

class CreateUserValidator extends Validator{

  /**
   * validation rules for create city form
   *
   * @return array
   */
  public function rules()
  {
    $request = Request::instance();

    $rules =  [];


    // email rule of state
    switch($request->method())
    {
     case 'POST':

      $rules = [
          'first_name'        =>  'required|min:2',
          'email'             =>  'email|min:5',
          'phone'             =>  'required|digits:10|numeric|unique:users,phone|min:3000000000',
          'password'          =>  'required|min:6',
          'confirm_password'  =>  'required|same:password'
      ];

       break;

     case 'PATCH':

       $user = User::find($request->get('id'));

        $rules = [
            'first_name'        =>  'required|min:2',
            'email'             =>  'email|min:5',
            'phone'             =>  'required|digits:10|numeric|min:3000000000|unique:users,phone,' . $user->id ,
            'date_of_birth'     =>  'disallow_future_date',
        ];

       break;

     default:break;
    }

      return $rules;
  }
}
