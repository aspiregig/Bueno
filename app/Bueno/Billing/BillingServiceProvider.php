<?php namespace Bueno\Billing;

use Bueno\Billing\Providers\COD;
use Bueno\Billing\Providers\EBS;
use Bueno\Billing\Providers\Paytm;
use Bueno\Billing\Providers\Mobikwik;
use Bueno\Billing\Providers\Razorpay;
use Bueno\Billing\Providers\PayUMoney;

class BillingServiceProvider{

  protected $payment;
  /**
   * resolves the BillingInterface to corresponding Billing Providers
   *
   * @return void
   */
  public function resolve()
  {
    $request = request()->instance();

    $payment_mode_id = $request->get('payment_mode_id');

    if(!$payment_mode_id) $payment_mode_id = $request->route()->getParameter('payment_mode_id');

    $payment_mode_id = (Integer) $payment_mode_id;

    switch($payment_mode_id)
    {
      case 7:
        $this->payment = new EBS;
        break;

      case 1:
        $this->payment = new PayUMoney;
        break;

      case 3:
        $this->payment = new Mobikwik;
        break;

      case 5:
        $this->payment = new Paytm;
        break;

      case 2 :
        $this->payment = new COD;
        break;

      case 8:
        $this->payment = new Razorpay;
        break;

      default:
        $this->payment = new EBS;
    }

    return $this->payment;
  }


}