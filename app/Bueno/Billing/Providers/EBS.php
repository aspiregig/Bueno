<?php namespace Bueno\Billing\Providers;

use Bueno\Billing\BillingInterface;
use Bueno\Repositories\DbOrderRepository;

class EBS implements BillingInterface{

  /**
   * @var array
   */
  protected $inputs = [];

  protected $return_url;

  function __construct()
  {
    $this->return_url = route('checkout.success.get', 7);
  }


  /** creates an unique hash to be used by payment gateway
   *
   * @param $inputs
   * @param $salt
   * @return string
   */

  public function buildHash($inputs)
  {
    $hash =  config('bueno.ebs.secret_key') ."|".urlencode(config('bueno.ebs.account_id'))."|".urlencode($inputs['amount'])."|".urlencode($inputs['order_id'])."|". $this->return_url ."|".urlencode(config('bueno.ebs.mode'));

    $secure_hash = md5($hash);

    return $secure_hash;
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
        'amount'  => (Float) $order->paymentInfo->amount,
        'firstname' => auth()->user()->first_name,
        'email' => auth()->user()->email,
        'phone' => auth()->user()->phone,
        'key' => config('bueno.pay_u_money.merchant_key'),
        'address' => $order->delivery_address,
        'city' => $order->area->name,
        'ship_city' => $order->area->name,
        'postal_code' => 110046,
        'order_id' => $order->id,
        'state' => $order->area->city->state->name
    ];

    return $this->inputs;
  }


  /**
   * builds and submit to the ebs payment
   *
   * @param array $inputs
   */
  public function buildFormAndSubmit($order, $inputs = [])
  {
    $this->setInputs($order, $inputs);

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
    echo '<input type="hidden" value="' . $this->return_url . '" name="return_url"/>';
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
    if(!$this->checkSuccessHash($inputs)) return $this->handleFailure();

    if($inputs['ResponseCode'] != 0) return $this->handleFailure();

    $orderRepo = new DbOrderRepository;

    $response = json_encode($inputs);

    $order = $orderRepo->updateOrderPayment($inputs['MerchantRefNo'], $response);

    return view('orders.checkout_success', compact('order'));
  }

  public function handleFailure()
  {
    return view('orders.checkout_failure');
  }

  /**
   * checks for hash value after the success callback
   *
   * @param $inputs
   * @return mixed
   */
  public function checkSuccessHash($inputs)
  {
    $hash = $this->buildHash([
      'amount' => (Float) $inputs['Amount'],
      'order_id'  => $inputs['MerchantRefNo']
    ]);

    if($hash != $inputs['Description']) return false;

    return true;
  }


  /**
   * handles payment in api
   *
   * @param $inputs
   * @return mixed
   */
  public function handlePaymentAPI($order, $inputs)
  {
    return response()->json([
        'data'  => [
            'order_id' => $order->id
        ]
    ]);
  }

  public function handleSuccessAPI($order, $inputs)
  {
    // TODO: Implement handleSuccessAPI() method.
  }
}