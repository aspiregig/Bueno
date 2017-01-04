<?php namespace App\Models;

class Ngo extends BaseModel{

  protected $fillable = ['name','description','default_donation_amount','status']; 

  protected $appends = ['status_text'];

  protected $repo = 'Bueno\Repositories\DbCommonRepository';

  public function getStatusTextattribute()
  {
    if($this->status)
      return 'Active';
    else
      return 'Disabled';
  }
  public function donation()
  {
    return $this->hasMany('App\Models\Donation');
  }

}