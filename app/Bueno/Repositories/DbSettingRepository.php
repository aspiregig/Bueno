<?php namespace Bueno\Repositories;

use Carbon;
use App\Models\Setting;
use App\Models\Day;
use App\Models\Timing;
use App\Models\PaymentMode;

class DbSettingRepository  {

  public function getAllSettings()
  {
    $setting['refresh_rate']=$this->getDashboardRefershRate();
    $setting['invoice_message']=$this->getInvoiceMessage();
    return $setting;
  }

  public function update($inputs)
  {
    $this->setInvoiceMessage(isset($inputs['invoice_message']) ? $inputs['invoice_message'] : null);
    $this->setOperationalDays($inputs['days']);
    $this->setDashboardRefershRate($inputs['refresh_rate']);
    $this->setMasterSwitch($inputs['master_switch']);
    if(isset($inputs['timing']))
    $this->setTiming($inputs['timing']);
    else
    $this->clearTimings();
    $this->updatePaymentMode($inputs['payment_mode']);
  }

  public function setMasterSwitch($value)
  {
    $master_switch = Setting::whereKey('master_switch')->first();
    $master_switch->value = $value;
    $master_switch->save();
  }

  public function getMasterSwitch()
  {
    $master_switch = Setting::whereKey('master_switch')->first();
    return $master_switch->value;
  }

  public function setInvoiceMessage($text)
  {
    $message = Setting::whereKey('invoice_message')->first();
    $message->value = $text;
    $message->save();
    return $message;
  }

  public function getInvoiceMessage()
  {
    $message = Setting::whereKey('invoice_message')->first();
    return $message->value;
  }

  public function setTiming($timings)
  {

    Timing::truncate();

    if($timings)
    foreach($timings as $timing)
    {
      Timing::insert([
          'from'     =>  (int) Carbon\Carbon::createFromFormat('H:i',$timing['from'])->format('Hi'),
          'to'       => (int) Carbon\Carbon::createFromFormat('H:i',$timing['to'])->format('Hi')
      ]);
    }
  }

  public function clearTimings()
  {
    Timing::truncate();
  }

  public function getAllTimings()
  {
    return Timing::all();
  }

  public function setDashboardRefershRate($value)
  {
    $refresh_rate = Setting::whereKey('refresh_rate')->first();
    $refresh_rate->value = $value;
    $refresh_rate->save();
  }

  public function getDashboardRefershRate()
  {
    $refresh_rate = Setting::whereKey('refresh_rate')->first();
    return $refresh_rate->value;
  }

  //Days

  public function getAllDays()
  {
    $days = Day::get();
    return $days;
  }

  public function setOperationalDays($days)
  { 
    if(is_null($days))
      $days=[0];

    Day::whereIn('id',$days)->update(['status'=>'1']);
    Day::whereNotIn('id',$days)->update(['status'=>'0']);

  }

  //Bueno Points

  public function getAllBuenoSettings()
  {
    $setting['referral_points']=$this->getReferralPoints();
    $setting['referrer_points']=$this->getReferrerPoints();
    return $setting;
  }

  public function updateBuenoPoint($inputs)
  {
    $this->setReferralPoints($inputs->get('referral_points'));
    $this->setReferrerPoints($inputs->get('referrer_points'));
  }

  public function setReferralPoints($value)
  {
    $referral_points = Setting::whereKey('referral_points')->first();
    $referral_points->value = $value;
    $referral_points->save();
  }


  public function setReferrerPoints($value)
  {
    $referrer_points = Setting::whereKey('referrer_points')->first();
    $referrer_points->value = $value;
    $referrer_points->save();
  }

  public function getReferralPoints()
  {
    $referral_commision = Setting::whereKey('referral_points')->first();
    return $referral_commision->value;
  }
  public function getReferrerPoints()
  {
    $referral_commision = Setting::whereKey('referrer_points')->first();
    return $referral_commision->value;
  }

  public function setReferralMaxEarning($value)
  {
    $referral_max_earning = Setting::whereKey('referral_max_earning')->first();
    $referral_max_earning->value = $value;
    $referral_max_earning->save();
  }

  public function getReferralMaxEarning()
  {
    $referral_max_earning = Setting::whereKey('referral_max_earning')->first();
    return $referral_max_earning->value;
  }

  public function setLoyalityPointRatio($value)
  {
    $loyality_point_ratio = Setting::whereKey('loyality_point_ratio')->first();
    $loyality_point_ratio->value = $value;
    $loyality_point_ratio->save();
  }

  public function getLoyalityPointRatio()
  {
    $loyality_point_ratio = Setting::whereKey('loyality_point_ratio')->first();
    return $loyality_point_ratio->value;
  }

  public function setCashbackUpto($value)
  {
    $cashback_upto = Setting::whereKey('max_cashback')->first();
    $cashback_upto->value = $value;
    $cashback_upto->save();
  }

  public function getCashbackUpto()
  {
    $cashback_upto = Setting::whereKey('max_cashback')->first();
    return $cashback_upto->value;
  }

  public function setCashbackPercent($value)
  {
    $cashback_upto = Setting::whereKey('cashback_percent')->first();
    $cashback_upto->value = $value;
    $cashback_upto->save();
  }

  public function getCashbackPercent()
  {
    $cashback_upto = Setting::whereKey('cashback_percent')->first();
    return $cashback_upto->value;
  }

  public function setRedeemLimit($value)
  {
    $redeem_limit = Setting::whereKey('redeem_limit')->first();
    $redeem_limit->value = $value;
    $redeem_limit->save();
  }

  public function getRedeemLimit()
  {
    $redeem_limit = Setting::whereKey('redeem_limit')->first();
    return $redeem_limit->value;
  }
  public function getPaymentModes()
  {
    $payment_modes = PaymentMode::all();
    return [
    'payU' => $payment_modes->where('id',1)->first()->status,
    'cod' => $payment_modes->where('id',2)->first()->status,
    'mobikwik' => $payment_modes->where('id',3)->first()->status,
    'payTm' => $payment_modes->where('id',5)->first()->status,
    'ebs' => $payment_modes->where('id',7)->first()->status,
    'razor' => $payment_modes->where('id',8)->first()->status,
    ];
  }

  public function updatePaymentMode($payment_modes)
  {
    PaymentMode::where('status',1)->update(['status'=>0]);
    foreach ($payment_modes as $key => $value) {
      $mode = PaymentMode::find($key);
      $mode->status = $value;
      $mode->save();
    }
  }

}