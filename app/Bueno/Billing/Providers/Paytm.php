<?php namespace Bueno\Billing\Providers;

use Bueno\Billing\BillingInterface;
use Bueno\Repositories\DbOrderRepository;

// ID - 5
class Paytm implements BillingInterface{

  /**
   *  stores all the form inputs from cart page
   *
   * @var array
   */
  protected $inputs = [];

  /**
   * @var string
   */
  protected  $PAYTM_REFUND_URL;

  /**
   * @var string
   */
  protected $PAYTM_STATUS_QUERY_URL;

  /**
   * @var string
   */
  protected $PAYTM_TXN_URL;

  protected $industry_type_id;

  protected $channel_id;

  /**
   * set some defaults values
   */
  function __construct()
  {
    // setting credentials for paytm
    $this->industry_type_id = env('APP_ENV') == 'production' ? config('bueno.paytm.industry_type_id') : config('bueno.paytm_testing.industry_type_id');
    $this->channel_id = env('APP_ENV') == 'production' ? config('bueno.paytm.channel_id') : config('bueno.paytm_testing.channel_id');
    $this->merchant_website = env('APP_ENV') == 'production' ? config('bueno.paytm.merchant_website') : config('bueno.paytm_testing.merchant_website');
    $this->merchant_mid = env('APP_ENV') == 'production' ? config('bueno.paytm.merchant_mid') : config('bueno.paytm_testing.merchant_mid');
    $this->domain = env('APP_ENV') == 'production' ? config('bueno.paytm.domain') : config('bueno.paytm_testing.domain');
    $this->environment = env('APP_ENV') == 'production' ? config('bueno.paytm.environment') : config('bueno.paytm_testing.environment');
    $this->merchant_key = env('APP_ENV') == 'production' ? config('bueno.paytm.merchant_key') : config('bueno.paytm_testing.merchant_key');
    $this->post_url = env('APP_ENV') == 'production' ? config('bueno.paytm.post_url') : config('bueno.paytm_testing.post_url');

    $this->PAYTM_REFUND_URL = 'https://' . $this->domain . '/oltp/HANDLER_INTERNAL/REFUND';
    $this->PAYTM_STATUS_QUERY_URL = 'https://' . $this->domain . '/oltp/HANDLER_INTERNAL/TXNSTATUS';
    $this->PAYTM_TXN_URL = 'https://' .  $this->domain . '/oltp-web/processTransaction';
  }


  /** creates an unique hash to be used by payment gateway
   *
   * @param $inputs
   * @param $salt
   * @return string
   */

  public function buildHash($inputs)
  {
    $paramList = $this->buildParamList($inputs);

    $hash = $this->getChecksumFromArray($paramList, $this->merchant_key);

    return $hash;
  }

  /**
   * builds parameter list for checksum
   *
   * @return array
   */
  public function buildParamList()
  {
    return [
      'MID' => $this->inputs['merchant_mid'],
      'ORDER_ID'  => $this->inputs['order_id'],
      'CUST_ID' => $this->inputs['customer_id'],
      'INDUSTRY_TYPE_ID'  => $this->inputs['industry_type_id'],
      'CHANNEL_ID'  => $this->inputs['channel_id'],
      'TXN_AMOUNT'  => $this->inputs['amount'],
      'WEBSITE' => $this->inputs['merchant_website'],
    ];
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
      'amount'            => (Float) $order->paymentInfo->amount,
      'order_id'          => $order->id,
      'customer_id'       => $order->user_id,
      'industry_type_id'  => $this->industry_type_id,
      'channel_id'        => $this->channel_id,
      'merchant_website'  => $this->merchant_website,
      'merchant_mid'      => $this->merchant_mid,
      'phone'             => $order->user->phone,
      'full_name'         => $order->user->full_name,
      'email'             => $order->user->email
    ];

    return $this->inputs;
  }

  /**
   * @param $order
   * @param array $inputs
   */
  public function buildFormAndSubmit($order, $inputs = [])
  {
    $this->setInputs($order, $inputs);

    //Paytm form submit for payment
    echo '<form action="'. $this->post_url .'" name="f1" method="post">';
    echo '<input type="hidden" name="MID" value="' . $this->inputs['merchant_mid'] . '">';
    echo '<input type="hidden" name="ORDER_ID" value="' . $this->inputs['order_id'] . '">';
    echo '<input type="hidden" name="CUST_ID" value="' .  $this->inputs['customer_id'] . '">';
    echo '<input type="hidden" name="INDUSTRY_TYPE_ID" value="' .  $this->inputs['industry_type_id'] . '">';
    echo '<input type="hidden" name="CHANNEL_ID" value="' .  $this->inputs['channel_id'] . '">';
    echo '<input type="hidden" name="TXN_AMOUNT" value="' .  $this->inputs['amount'] . '">';
    echo '<input type="hidden" name="WEBSITE" value="' .  $this->inputs['merchant_website'] . '">';
    echo '<input type="hidden" name="CHECKSUMHASH" value="' . $this->buildHash($this->inputs) . '">';
    echo '</form>';
    echo '<script>document.f1.submit(); </script>';
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

    $order = $orderRepo->updateOrderPayment($inputs['ORDERID'], $response);

    return view('orders.checkout_success', compact('order'));
  }

  /**
   * checks for hash value after the success callback
   * @param $inputs
   * @return mixed
   */
  public function checkSuccessHash($inputs)
  {
    $paramList = $inputs;

    $paytmChecksum = isset($inputs["CHECKSUMHASH"]) ? $inputs["CHECKSUMHASH"] : ""; //Sent by Paytm pg

    $isValidChecksum = $this->verifychecksum_e($paramList, $this->merchant_key, $paytmChecksum); //will return TRUE or FALSE string.

    if ($isValidChecksum != TRUE) return false;

    return true;
  }

  /**
   * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
   */
  public function handleFailure()
  {
    return view('orders.checkout_failure');
  }

  // code after this line does not make any sense to me.

  /**
   * @param $input
   * @param $ky
   * @return string
   */
  function encrypt_e($input, $ky) {
    $key = $ky;
    $size = mcrypt_get_block_size(MCRYPT_RIJNDAEL_128, 'cbc');
    $input = $this->pkcs5_pad_e($input, $size);
    $td = mcrypt_module_open(MCRYPT_RIJNDAEL_128, '', 'cbc', '');
    $iv = "@@@@&&&&####$$$$";
    mcrypt_generic_init($td, $key, $iv);
    $data = mcrypt_generic($td, $input);
    mcrypt_generic_deinit($td);
    mcrypt_module_close($td);
    $data = base64_encode($data);
    return $data;
  }

  /**
   * @param $crypt
   * @param $ky
   * @return string
   */
  function decrypt_e($crypt, $ky) {
    $crypt = base64_decode($crypt);
    $key = $ky;
    $td = mcrypt_module_open(MCRYPT_RIJNDAEL_128, '', 'cbc', '');
    $iv = "@@@@&&&&####$$$$";
    mcrypt_generic_init($td, $key, $iv);
    $decrypted_data = mdecrypt_generic($td, $crypt);
    mcrypt_generic_deinit($td);
    mcrypt_module_close($td);
    $decrypted_data = $this->pkcs5_unpad_e($decrypted_data);
    $decrypted_data = rtrim($decrypted_data);
    return $decrypted_data;
  }

  /**
   * @param $text
   * @param $blocksize
   * @return string
   */
  function pkcs5_pad_e($text, $blocksize) {
    $pad = $blocksize - (strlen($text) % $blocksize);
    return $text . str_repeat(chr($pad), $pad);
  }

  /**
   * @param $text
   * @return bool|string
   */
  function pkcs5_unpad_e($text) {
    $pad = ord($text{strlen($text) - 1});
    if ($pad > strlen($text))
      return false;
    return substr($text, 0, -1 * $pad);
  }

  /**
   * @param $length
   * @return string
   */
  function generateSalt_e($length) {
    $random = "";
    srand((double) microtime() * 1000000);
    $data = "AbcDE123IJKLMN67QRSTUVWXYZ";
    $data .= "aBCdefghijklmn123opq45rs67tuv89wxyz";
    $data .= "0FGH45OP89";
    for ($i = 0; $i < $length; $i++) {
      $random .= substr($data, (rand() % (strlen($data))), 1);
    }
    return $random;
  }

  /**
   * @param $value
   * @return string
   */
  function checkString_e($value) {
    $myvalue = ltrim($value);
    $myvalue = rtrim($myvalue);
    if ($myvalue == 'null')
      $myvalue = '';
    return $myvalue;
  }

  /**
   * @param $arrayList
   * @param $key
   * @param int $sort
   * @return mixed
   */

  function getChecksumFromArray($arrayList, $key, $sort=1) {
    if ($sort != 0) {
      ksort($arrayList);
    }
    $str = $this->getArray2Str($arrayList);
    $salt = $this->generateSalt_e(4);
    $finalString = $str . "|" . $salt;
    $hash = hash("sha256", $finalString);
    $hashString = $hash . $salt;
    $checksum = $this->encrypt_e($hashString, $key);
    return $checksum;
  }

  /**
   * @param $arrayList
   * @param $key
   * @param $checksumvalue
   * @return bool
   */

  function verifychecksum_e($arrayList, $key, $checksumvalue) {
    $arrayList = $this->removeCheckSumParam($arrayList);
    ksort($arrayList);
    $str = $this->getArray2Str($arrayList);
    $paytm_hash = $this->decrypt_e($checksumvalue, $key);
    $salt = substr($paytm_hash, -4);
    $finalString = $str . "|" . $salt;
    $website_hash = hash("sha256", $finalString);
    $website_hash .= $salt;
    $validFlag = FALSE;
    if ($website_hash == $paytm_hash) {
      $validFlag = TRUE;
    } else {
      $validFlag = FALSE;
    }
    return $validFlag;
  }

  /**
   * @param $arrayList
   * @return string
   */
  function getArray2Str($arrayList) {
    $paramStr = "";
    $flag = 1;
    foreach ($arrayList as $key => $value) {
      if ($flag) {
        $paramStr .= $this->checkString_e($value);
        $flag = 0;
      } else {
        $paramStr .= "|" . $this->checkString_e($value);
      }
    }
    return $paramStr;
  }

  /**
   * @param $paramList
   * @param $key
   */
  function redirect2PG($paramList, $key) {
    $hashString = $this->getchecksumFromArray($paramList);
    $checksum = $this->encrypt_e($hashString, $key);
  }

  /**
   * @param $arrayList
   * @return mixed
   */
  function removeCheckSumParam($arrayList) {
    if (isset($arrayList["CHECKSUMHASH"])) {
      unset($arrayList["CHECKSUMHASH"]);
    }
    return $arrayList;
  }

  /**
   * @param $requestParamList
   * @return mixed
   */
  function getTxnStatus($requestParamList) {
    return $this->callAPI($this->PAYTM_STATUS_QUERY_URL, $requestParamList);
  }

  /**
   * @param $requestParamList
   * @return mixed
   */
  function initiateTxnRefund($requestParamList) {
    $CHECKSUM = $this->getChecksumFromArray($requestParamList,$this->merchant_key,0);
    $requestParamList["CHECKSUM"] = $CHECKSUM;
    return $this->callAPI($this->PAYTM_REFUND_URL, $requestParamList);
  }

  /**
   * @param $apiURL
   * @param $requestParamList
   * @return array|mixed
   */
  function callAPI($apiURL, $requestParamList) {

    $jsonResponse = "";
    $responseParamList = array();
    $JsonData = json_encode($requestParamList);
    $postData = 'JsonData=' . urlencode($JsonData);
    $ch = curl_init($apiURL);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen($postData))
    );
    $jsonResponse = curl_exec($ch);
    $responseParamList = json_decode($jsonResponse, true);
    return $responseParamList;
  }

  /**
   * @param $inputs
   * @return \Illuminate\Http\JsonResponse
   */
  public function buildChecksumAPI($inputs)
  {
    $checkSum = "";

    $checkSum = $this->getChecksumFromArray($inputs, $this->merchant_key);

    return response()->json([
        "CHECKSUMHASH" => $checkSum,
        "ORDER_ID" => $inputs["ORDER_ID"],
        "payt_STATUS" => "1"
    ]);
  }

  /**
   * builds checksum for old api
   *
   * @param $inputs
   * @return bool
   */
  public function verifyChecksumAPI($inputs)
  {
    $paytmChecksum = "";
    $paramList = array();
    $isValidChecksum = FALSE;

    $paramList = $inputs;
    $return_array = $inputs;
    $paytmChecksum = isset($inputs["CHECKSUMHASH"]) ? $inputs["CHECKSUMHASH"] : ""; //Sent by Paytm pg

    $isValidChecksum = $this->verifychecksum_e($paramList, $this->merchant_key, $paytmChecksum); //will return TRUE or FALSE string.

    $return_array["IS_CHECKSUM_VALID"] = $isValidChecksum ? "Y" : "N";
    unset($return_array["CHECKSUMHASH"]);
    $encoded_json = htmlentities(json_encode($return_array));

    return view('payments.api_paytm_response', compact('encoded_json'));
  }

  /**
   * handles payment in api
   *
   * @param $inputs
   * @return mixed
   */
  public function handlePaymentAPI($order, $inputs)
  {
    $checkSum = $this->getChecksumFromArray($inputs, $this->merchant_key);

    return response()->json([
        "CHECKSUMHASH" => $checkSum,
        "ORDER_ID" => $inputs["ORDER_ID"],
        "payt_STATUS" => "1"
    ]);
  }

  /**
   * @param $order
   * @param $inputs
   */
  public function handleSuccessAPI($order, $inputs)
  {
    $paytmChecksum = "";
    $paramList = array();
    $isValidChecksum = FALSE;

    $paramList = $inputs;
    $return_array = $inputs;
    $paytmChecksum = isset($inputs["CHECKSUMHASH"]) ? $inputs["CHECKSUMHASH"] : ""; //Sent by Paytm pg

    $isValidChecksum = $this->verifychecksum_e($paramList, $this->merchant_key, $paytmChecksum); //will return TRUE or FALSE string.

    $return_array["IS_CHECKSUM_VALID"] = $isValidChecksum ? "Y" : "N";
    unset($return_array["CHECKSUMHASH"]);
    $encoded_json = htmlentities(json_encode($return_array));

    return view('payments.api_paytm_response', compact('encoded_json'));
  }
}