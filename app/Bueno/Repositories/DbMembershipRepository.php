<?php namespace Bueno\Repositories;

use App\Models\Membership;
use App\Models\User;
use Bueno\Loggers\DebugLogger;


class DbMembershipRepository  {

  public function getAllMemberships()
  {
    return Membership::all();
  }

  public function getMembershipById($id)
  {
    return Membership::find($id);
  }

  public function newMembership($inputs)
  {
    return Membership::create($inputs);
  }

  public function updateMembershipById($id,$inputs)
  {
    $membership = Membership::find($id);
    if($membership->min != $inputs['min'])
      {
        $membership->update($inputs);
        // $this->updateAllUserMembership();
      }
      else
        $membership->update($inputs);
    return $membership;
  }

  public function deleteMemberShipById($id)
  {
    $membership = Membership::find($id);
    if($membership->members->count()==0)
    {
      $membership->delete();
      return true;
    }
    else
    {
      return false;
    }
  }

  public function giveLoyaltyPoints($order)
  {
    $user = User::find($order->user_id);
    $user->points += $user->membership->lotalty_points;
    $user->save();
  }

  public function updateUserMembership($user)
  {
    $order_count = $user->confirmedOrders()->count();
    $memberships = Membership::orderBy('min')->get();
    foreach($memberships as $membership)
    {
      if($membership->min<=$order_count)
      {
        $membership_id = $membership->id;
      }
    }
    return $membership_id;
  }

  public function updateAllUserMembership()
  {
    $logger = new DebugLogger;
    $users = User::chunk(500,function($users) use($logger){
      foreach ($users as $user) {
        $previous_id = $user->membership_id;
      $user->membership_id = $this->updateUserMembership($user);
      $user->save();
      $logger->log('Update Membership User ID : '.$user->id.' From : '.$previous_id.' To : '.$user->membership_id);
      }
    });
    
  }

}