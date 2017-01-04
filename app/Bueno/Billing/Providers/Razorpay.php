<?php namespace Bueno\Billing\Providers;

use Razorpay\Api\Api;
use Bueno\Billing\BillingInterface;
use Bueno\Repositories\DbOrderRepository;

class Razorpay implements BillingInterface{


  /**
   * @var array
   */
  protected $inputs = [];

  function __construct()
  {
    $this->key = env('APP_ENV') == 'production' ? config('bueno.razorpay.key') : config('bueno.razorpay_testing.key');
    $this->secret = env('APP_ENV') == 'production' ? config('bueno.razorpay.secret') : config('bueno.razorpay_testing.secret');
  }

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
   * @return array
   */
  public function setInputs($order, $inputs)
  {
    $this->inputs = [
      'full_name' => $order->user->full_name,
      'email' => $order->user->email,
      'phone' => $order->user->phone,
      'order_id'  => $order->id,
      'amount'  => $order->paymentInfo->amount * 100
    ];

    return $this->inputs;
  }


  /**
   * @param array $inputs
   */
  public function buildFormAndSubmit($order, $inputs = [])
  {
    $inputs = $this->setInputs($order, $inputs);

    $form = '
    <style>
    input.razorpay-payment-button {  display: none;  }
    .payment-buttons {
    height: 100%;
    text-align: center;
    margin-top: 10%;
}

button#pay-now {
    border: none;
    padding: 10px;
    border-radius: 4px;
    color: white;
    background: #2AB900;
}

button#cancel-pay {
    border: none;
    padding: 10px;
    border-radius: 4px;
    color: white;
    background: #D40505;
}
    </style>
    <form action="'. route('checkout.charge.post', 8) . '" name="paymentForm" method="POST">
      <script
        src="https://checkout.razorpay.com/v1/checkout.js"
        data-key="'.$this->key.'"
        data-amount="'.  $this->inputs['amount']. '"
        data-name="'.  $this->inputs['full_name']. '"
        data-description="Bueno"
        data-image="http://bueno.kitchen/photo/user"
        data-netbanking="true"
        data-description="Bueno"
        data-prefill.name="'.  $this->inputs['full_name']. '"
        data-prefill.email="'.  $this->inputs['email']. '"
        data-prefill.contact="'.  $this->inputs['phone']. '"
        data-notes.shopping_order_id="'.  $this->inputs['order_id']. '">
      </script>
      <!-- Any extra fields to be submitted with the form but not sent to Razorpay -->
      <input type="hidden" name="shopping_order_id" value="'.  $this->inputs['order_id']. '">
    </form>
      <input type="hidden" name="cancel_url" id="cancel_url" value="' . route("users.cart.get") . '">
    <div class="payment-buttons">
    <p>Processing...Please wait</p>
    </div>
    <script src="https://code.jquery.com/jquery-1.12.0.min.js"></script>
    <script>
      $(document).ready(function(){
        $(".razorpay-payment-button").trigger("click");
      });
    </script>';

    return $form;
  }

  public function chargePayment($inputs)
  {
    $orderRepo = new DbOrderRepository;

    $order = $orderRepo->getOrderById($inputs['shopping_order_id']);

    $api = new Api($this->key, $this->secret);

    //This is submited by the checkout form
    if (isset($inputs['razorpay_payment_id']) === false)
    {
      return $this->handleFailure();
    }

    $id = $inputs['razorpay_payment_id'];

    $payment = $api->payment->fetch($id)->capture([
      'amount' => $order->paymentInfo->amount * 100
    ]);

    $response = json_encode($payment->toArray());

    $order = $orderRepo->updateOrderPayment($inputs['shopping_order_id'], $response);

    return view('orders.checkout_success', compact('order'));


  }
  /**
   * checks for hash value after the success callback
   *
   * @param $inputs
   * @return mixed
   */
  public function checkSuccessHash($inputs)
  {

  }

  /**
   * builds the hash for success event
   *
   * @param $inputs
   * @return string
   */
  public function buildSuccessHash($inputs)
  {

  }

  /**
   * handle success event
   *
   * @param $inputs
   * @return mixed
   */
  public function handleSuccess($inputs)
  {

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
    // TODO: Implement handlePaymentAPI() method.
  }

  public function handleSuccessAPI($order, $inputs)
  {
    // TODO: Implement handleSuccessAPI() method.
  }
}