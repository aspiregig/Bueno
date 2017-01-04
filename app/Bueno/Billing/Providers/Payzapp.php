<?php namespace Bueno\Billing\Providers;

use Bueno\Billing\BillingInterface;

class Payzapp implements BillingInterface{

  /**
   * @var array
   */
  protected $inputs = [];

  /** creates an unique hash to be used by payment gateway
   *
   * @param $inputs
   * @param $salt
   * @return string
   */

  public function buildHash($inputs)
  {

  }

  /**
   * creates an unique transaction ID
   *
   * @return string
   */
  public function buildTransactionID()
  {
    return substr(hash('sha256', mt_rand() . microtime()), 0, 20);
  }

  /**
   * @return array
   */
  public function setInputs($order, $inputs)
  {
    $this->inputs = [
        'amount'  => (Float) 1,
        'firstname' => auth()->user()->first_name,
        'email' => auth()->user()->email,
        'phone' => auth()->user()->phone,
        'key' => config('bueno.pay_u_money.merchant_key'),
        'address' => 'Sqareboat squareboat, squareboat',
        'city' => 'Gurgaon',
        'ship_city' => 'Gurgaon',
        'postal_code' => 110046,
        'order_id' => $order->id,
        'state' => 'Haryana'
    ];

    return $this->inputs;
  }


  /**
   * @param array $inputs
   */
  public function buildFormAndSubmit($order, $inputs = [])
  {
    $inputs = $this->setInputs($order, $inputs);

    //EBS form submit for payment
    echo '<form action="'. config('bueno.ebs.action_url') .'" name="payment" method="post">';
    echo '<input type="hidden" value="2499" name="page_id"/>';
    echo '<input type="hidden" value="' . config('bueno.ebs.account_id') . '" name="account_id"/>';
    echo '<input type="hidden" value="' . $this->inputs['address'] . '" name="address"/>';
    echo '<input type="hidden" value="' . $this->inputs['amount'] . '" name="amount"/>';
    echo '<input type="hidden" value="' . config('bueno.ebs.channel') . '" name="channel"/>';
    echo '<input type="hidden" value="' . $this->inputs['city'] . '" name="city"/>';
    echo '<input type="hidden" value="IN" name="country"/>';
    echo '<input type="hidden" value="INR" name="currency"/>';

    #description value is being verified Pages#order_confirm_process_ebs method. If it's change payment will not be verified. If you want to change description, need to calculate secure_hash in order_confirm_process_ebs method in pages controller.
    echo '<input type="hidden" value="' . $this->buildHash($this->inputs) . '" name="description"/>';
    echo '<input type="hidden" value="' . $this->inputs['email'] . '" name="email"/>';
    echo '<input type="hidden" value="' . config('bueno.ebs.mode') . '" name="mode"/>';
    echo '<input type="hidden" value="' . $this->inputs['firstname'] . '" name="name"/>';
    echo '<input type="hidden" value="' . $this->inputs['phone'] . '" name="phone"/>';
    echo '<input type="hidden" value="' . $this->inputs['postal_code'] . '" name="postal_code"/>';
    echo '<input type="hidden" value="' . $order->id . '" name="reference_no"/>';
    echo '<input type="hidden" value="' . config('bueno.ebs.return_url') . '" name="return_url"/>';
    echo '<input type="hidden" value="' . $this->inputs['state'] . '" name="state"/>'; // not mandatory
    echo '<input type="hidden" value="' . $this->buildHash($this->inputs) . '" name="secure_hash"/>';
    echo '<input type="hidden" value="' . $this->inputs['ship_city'] . '" name="ship_city"/>';
    //echo '<input type="hidden" value="3" name="payment_mode"/>';
    echo '</form>';
    echo '<script>document.payment.submit(); </script>';
  }

  /**
   * handle success event
   *
   * @param $inputs
   * @return mixed
   */
  public function handleSuccess($inputs)
  {
    // TODO: Implement handleSuccess() method.
  }

  /**
   * handles failure event
   *
   * @return mixed
   */
  public function handleFailure()
  {
    // TODO: Implement handleFailure() method.
  }

  /**
   * checks for hash value after the success callback
   * @param $inputs
   * @return mixed
   */
  public function checkSuccessHash($inputs)
  {
    // TODO: Implement checkSuccessHash() method.
  }

  /**
   * handles payment in api
   *
   * @param $inputs
   * @return mixed
   */
  public function handlePaymentAPI($order, $inputs)
  {
    // TODO: Implement handlePaymentAPI() method.
  }

  public function handleSuccessAPI($order, $inputs)
  {
    // TODO: Implement handleSuccessAPI() method.
  }
}