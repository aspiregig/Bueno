<?php namespace Bueno\Validations;

use App\Models\Membership;
use Request;
use App\Models\Membershio;

class CreateMembershipValidator extends Validator{

  /**
   * validation rules for create city form
   *
   * @return array
   */
  public function rules()
  {
    $request = Request::instance();

    $rules =  [
      'text_color' => 'required|min:6|different:bg_color',
      'loyalty_points' => 'required|numeric|min:0',
      'description' => 'required|min:20|max:400'
    ];


    switch($request->method())
    {
      case 'POST':

        $rules['name'] = 'required|unique:memberships,name';
        $rules['min'] = 'required|numeric|min:0|unique:memberships,min';
        $rule['bg_color'] = 'required|min:7|max:12|unique:memberships,bg_color';


        break;

      case 'PATCH':

        $membership = Membership::find($request->input('id'));

        $rules['name'] =  'required|unique:memberships,name,' . $membership->id . ',';
        $rules['min'] =  'required|at_least_one_zero:' . $membership->id .'|unique:memberships,min,' . $membership->id . ',';
        $rule['bg_color'] = 'required|min:7|max:12|unique:memberships,bg_color,' . $membership->id . ',' ;


        break;

      default:break;
    }


    return $rules;
  }

}
