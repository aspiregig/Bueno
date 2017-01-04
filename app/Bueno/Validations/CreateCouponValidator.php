<?php namespace Bueno\Validations;

use Request;
use App\Models\Coupon;

class CreateCouponValidator extends Validator{

  /**
   * validation rules for create city form
   *
   * @return array
   */
  public function rules()
  {
    $request = Request::instance();

    $rules =  [
        'categories'  => 'duplicate_entry',
        'cuisines'  => 'duplicate_entry',
        'goodies'  => 'duplicate_entry',
        'give_meals'  => 'duplicate_entry',
        'give_combos'  => 'duplicate_entry',
        'meals'  => 'duplicate_entry',
        'combos'  => 'duplicate_entry',
        'discount_percent_value' => 'numeric|min:1|max:100',
        'offer_text'  => 'required|min:5'
    ];


// email rule of state
    switch($request->method())
    {
      case 'POST':

        $rules['coupon_code'] = 'required|min:3|unique:coupons,code';

        break;

      case 'PATCH':

        $coupon = Coupon::find($request->input('id'));


        break;

      default:break;
    }


    return $rules;
  }
}
