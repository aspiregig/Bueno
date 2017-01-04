<?php namespace Bueno\Validations;

use Carbon;
use App\Models\Membership;
use App\Models\Area;

/**
* Class CustomValidator
* @package Bueno\Validations
*/
class CustomValidator{

 /**
  * custom validator for checking timings value
  * @param $attribute
  * @param $value
  * @param $parameters
  * @param $validator
  */
 public function validateDupicateEntry($attribute, $value, $parameters, $validator)
 {
   $is_validated = true;
   $item = [];
   $counter = 0;
   $item[0] = 0;
   foreach ($value as $key => $val) {
    
    if(in_array($val['id'], $item))
    {
      return false;
    }
     $item[$counter++] = $val['id'];
    }
   
   return true;
 }

  public function validateMinQuantity($attribute, $value, $parameters, $validator)
  {
    $item = [];
    foreach ($value as $key => $val) {

      if($val['quantity'] > $parameters[0])
      {
        return false;
      }

    }
    return true;
  }

  /**
   * custom validator for checking timings value
   *
   * @param $attribute
   * @param $value
   * @param $parameters
   * @param $validator
   */
  public function checkTiming($attribute, $value, $parameters, $validator)
  {
    foreach($value as $key => $timings)
    {
      foreach($timings as $timing)
      {
        // if timing is empty
        if($timing['from'] == "" || $timing['to'] == "")
        {
          return false;
        }

        // if timing-from is greater then timing-to
        $timing['from'] =  (int) Carbon\Carbon::createFromFormat('H:i',$timing['from'])->format('Hi');
        $timing['to']   =  (int) Carbon\Carbon::createFromFormat('H:i',$timing['to'])->format('Hi');

        if( $timing['from'] > $timing['to'])
        {
          return false;
        }
      }
    }

    // timings are ok. return true
    return true;
  }

 public function validateImageMinSize($attribute, $value, $parameters, $validator)
 {
    $image_info = getimagesize($value);
   $image_width = $image_info[0];
    $image_height = $image_info[1];
    if( (isset($parameters[0]) && $parameters[0] != 0) && $image_width < $parameters[0]) return false;
    if( (isset($parameters[1]) && $parameters[1] != 0) && $image_height < $parameters[1] ) return false;
    return true;
 }

  public function validateImageRation($attribute, $value, $parameters, $validator)
  {
    $image_info = getimagesize($value);
    $result = $parameters[1] * $image_info[0] - $parameters[0] * $image_info[1];
    $result = abs($result);
    if(isset($parameters[2]))
    {
      if($parameters[2]!=0)
      {
        if($result<=$parameters[2]||$result>=$parameters[2])
          return true;
        else
          return false;
      }
      else
      {
        if($result!=0)
          return false;
      }

    }
    elseif($result!=0)
    {
      return false;
    }

    return true;
  }

  public function validateImageMaxSize($attribute, $value, $parameters, $validator)
  {
    $image_info = getimagesize($value);
    $image_width = $image_info[0];
    $image_height = $image_info[1];
    if( (isset($parameters[0]) && $parameters[0] != 0) && $image_width > $parameters[0]) return false;
    if( (isset($parameters[1]) && $parameters[1] != 0) && $image_height > $parameters[1] ) return false;
    return true;
  }

  public function validateImageFileSizeInBetween($attribute, $value, $parameters, $validator)
  {
    $image_size= filesize($value);
    $min_size = $parameters[0]*1000;
    $max_size = $parameters[1]*1000;
    if($image_size<$min_size || $image_size > $max_size)
    return false;
    else
    return true;
  }

 public function validateAlphaOnly($attribute, $value, $parameters, $validator)
 {
   return preg_match('/^[\pL\s]+$/u', $value) ? true : false;
 }

  /**
   * custom validator for disallowing future dates
   *
   * @param $attribute
   * @param $value
   * @param $parameters
   * @param $validator
   * @return bool
   */
  public function disallowFutureDate($attribute, $value, $parameters, $validator)
  {
    $input_date = date('Y-m-d', strtotime($value));

    $today_date = date('Y-m-d');

    if($input_date > $today_date) return false;

    return true;
  }


  public function validateAtLeastOneZero($attribute, $value, $parameters, $validator)
  {
    if($value==0)
      return true;
    else
    {
      $membership = Membership::where('id','!=',$parameters[0])->where('min',0)->get();

      if($membership->count()==0)
      {
        return false;
      }
      else
      {
        return true;
      }
    }
  }

  public function validateUniqueAreaOfCity($attribute, $value, $parameters, $validator)
  {
    if(isset($parameters[1]))
    $areas = Area::where('name',$value)->where('city_id',$parameters[0])->where('id','!=',$parameters[1])->get();
    else
    $areas = Area::where('name',$value)->where('city_id',$parameters[0])->get();

    if($areas->count()>0)
      return false;
    else
      return true;
  }


}