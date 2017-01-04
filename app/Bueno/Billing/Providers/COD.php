<?php namespace Bueno\Billing\Providers;

use Bueno\Billing\BillingInterface;
use Bueno\Repositories\DbOrderRepository;

class COD implements BillingInterface{

  // id = 2
  protected $inputs = [];

  /**
   * builds hash for the billing providers
   *
   * @param $inputs
   * @return mixed
   */
  public function buildHash($inputs){}

  /**
   * builds the form and submits it
   *
   * @param $inputs
   * @return mixed
   */
  public function buildFormAndSubmit($order, $inputs)
  {
    $orderRepo = new DbOrderRepository;

    $response = json_encode($inputs);

    $order = $orderRepo->updateOrderPayment($order->id, $response);

    return redirect(route('checkout.success.get', 2) . '?order_id=' . $order->id);
  }

  /**
   * @param $order
   * @array $inputs
   * @return mixed
   */
  public function setInputs($order, $inputs)
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
    $orderRepo = new DbOrderRepository;

    $order = $orderRepo->getOrderById($inputs['order_id']);

    return view('orders.checkout_success', compact('order'));
  }

  /**
   * handles failure event
   *
   * @return mixed
   */
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