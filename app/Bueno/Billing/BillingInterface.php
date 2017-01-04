<?php namespace Bueno\Billing;

interface BillingInterface
{
  /**
   * builds hash for the billing providers
   *
   * @param $inputs
   * @return mixed
   */
  public function buildHash($inputs);

  /**
   * builds the form and submits it
   *
   * @param $inputs
   * @return mixed
   */
  public function buildFormAndSubmit($order, $inputs);

  /**
   * @param $order
   * @array $inputs
   * @return mixed
   */
  public function setInputs($order, $inputs);

  /**
   * handle success event
   *
   * @param $inputs
   * @return mixed
   */
  public function handleSuccess($inputs);

  /**
   * handles failure event
   *
   * @return mixed
   */
  public function handleFailure();

  /**
   * checks for hash value after the success callback
   * @param $inputs
   * @return mixed
   */
  public function checkSuccessHash($inputs);


  /**
   * handles payment in api
   *
   * @param $inputs
   * @return mixed
   */
  public function handlePaymentAPI($order, $inputs);

  public function handleSuccessAPI($order, $inputs);
}