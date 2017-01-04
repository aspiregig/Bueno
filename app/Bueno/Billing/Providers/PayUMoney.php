<?php namespace Bueno\Billing\Providers;

use Bueno\Billing\BillingInterface;
use Bueno\Repositories\DbOrderRepository;

class PayUMoney implements BillingInterface{

  /**
   * @var array
   */
  protected $inputs = [];

  function __construct()
  {
    $this->merchant_mid = env('APP_ENV') == 'production' ? config('bueno.pay_u_money.merchant_mid') : config('bueno.pay_u_money_testing.merchant_mid');
    $this->merchant_key = env('APP_ENV') == 'production' ? config('bueno.pay_u_money.merchant_key') : config('bueno.pay_u_money_testing.merchant_key');
    $this->salt = env('APP_ENV') == 'production' ? config('bueno.pay_u_money.salt') : config('bueno.pay_u_money_testing.salt');
    $this->base_url = env('APP_ENV') == 'production' ? config('bueno.pay_u_money.base_url') : config('bueno.pay_u_money_testing.base_url');
  }

  /** creates an unique hash to be used by payment gateway
   *
   * @param $inputs
   * @param $salt
   * @return string
   */



  public function buildHash($inputs)
  {
    $hashSequence = "key|txnid|amount|productinfo|firstname|email|udf1|udf2|udf3|udf4|udf5|udf6|udf7|udf8|udf9|udf10";

    $hashVarsSeq = explode('|', $hashSequence);

    $hash_string = '';

    foreach($hashVarsSeq as $hash_var)
    {
      $hash_string .= isset($inputs[$hash_var]) ? $inputs[$hash_var] : '';

      $hash_string .= '|';
    }

    $hash_string .= $this->salt;

    $hash = strtolower(hash('sha512', $hash_string));

    return $hash;
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
        'key' => $this->merchant_key,
        'txnid' => $order->id,
        'productinfo' => 'Bueno'
      ];

      return $this->inputs;
  }


  /**
   * @param array $inputs
   */
  public function buildFormAndSubmit($order, $inputs = [])
  {
    $inputs = $this->setInputs($order, $inputs);

    echo '<form action="' . $this->base_url . '" method="post" name="payuForm">';
    echo '<input type="hidden" name="txnid" value="' . $inputs['txnid'] . '" />';
    echo '<input type="hidden" name="amount" value="' . (Float) $inputs['amount'] . '" />';
    echo '<input type="hidden" name="key" value="' . $inputs['key'] . '" />';
    echo '<input type="hidden" name="productinfo" value="'. $inputs['productinfo'] .'"/>';
    echo '<input type="hidden" name="firstname" value="' . $inputs['firstname'] . '" />';
    echo '<input type="hidden" name="email" value="' . $inputs['email'] . '" />';
    echo '<input type="hidden" name="hash" value="' . $this->buildHash($inputs) . '"/>';
    echo '<input type="hidden" name="phone" value="' . $inputs['phone'] . '" />';
    echo '<input type="hidden" name="surl" value="'. route('checkout.success.post', 1) .'" />';
    echo '<input type="hidden" name="furl" value="'. route('checkout.failure.post', 1) .'" />';
    echo '<input type="hidden" name="service_provider" value="payu_paisa" />';
    //echo '<input type="submit" name="submit" value="submit" value="Pay"/>';
    echo '</form>';
    echo '<script>var payuForm = document.forms.payuForm;payuForm.submit();</script>';
  }

  /**
   * checks for hash value after the success callback
   *
   * @param $inputs
   * @return mixed
   */
  public function checkSuccessHash($inputs)
  {
    $hash = $this->buildSuccessHash($inputs);

    if($hash != $inputs['hash']) return false;

    return true;
  }

  /**
   * builds the hash for success event
   *
   * @param $inputs
   * @return string
   */
  public function buildSuccessHash($inputs)
  {
    if(isset($inputs["additionalCharges"]))
    {
      $additionalCharges = $inputs["additionalCharges"];

      $retHashSeq = $additionalCharges.'|'. $this->salt .'|'. $inputs['status'] .'|||||||||||'. $inputs['email'] .'|'. $inputs['firstname'].'|'.$inputs['productinfo'].'|'.$inputs['amount'].'|'.$inputs['txnid'].'|'. $this->merchant_key;
    }
    else
    {
      $retHashSeq = $this->salt .'|'. $inputs['status'] .'|||||||||||'. $inputs['email'] .'|'. $inputs['firstname'].'|'.$inputs['productinfo'].'|'.$inputs['amount'].'|'.$inputs['txnid'].'|'. $this->merchant_key;
    }

    $hash = hash("sha512", $retHashSeq);

    return $hash;
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

    $response = json_encode($inputs);

    $orderRepo = new DbOrderRepository;

    $order = $orderRepo->updateOrderPayment($inputs['txnid'], $response);

    return view('orders.checkout_success', compact('order'));
  }


  /**
   *
   */
  public function handleFailure()
  {
    return view('orders.checkout_failure');
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
        'order_id' => $order->id
    ]);
  }

  public function handleSuccessAPI($order, $inputs)
  {

  }
}