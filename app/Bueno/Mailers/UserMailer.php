<?php namespace Bueno\Mailers;

use App\Models\User;
use App\Models\Order;

class UserMailer extends Mailer{

	public function welcome(User $user)
	{
		$view = 'emails.users.welcome';

		$data = ['user' => $user];

		$subject = 'Welcome to Bueno';

    if($user->email!=null && $user->group->name==config('bueno.groups.2'))
		return $this->sendTo($user->email, $subject, $view, $data);
    else
      return "No Email Id";
	}


  public function welcomeAndVerify(User $user)
  {
    $view = 'emails.users.welcome_verify';

    $data = ['user' => $user];

    $subject = 'Welcome to Bueno';

    return $this->sendTo($user->email, $subject, $view, $data);
  }

  public function orderConfirmed(Order $order)
  {
    $view = 'emails.users.order_confirm';

    $data = ['order' => $order];

    $subject = 'Order Placed !';

    return $this->sendTo($order->user->email, $subject, $view, $data);
  }

  public function orderDispatched(Order $order)
  {
    $view = 'emails.users.order_dispatched';

    $data = ['order' => $order];

    $subject = 'Your order has been dispatched';

    return $this->sendTo($order->user->email, $subject, $view, $data);
  }

  public function sendInvite(User $user, $inputs)
  {
    $view = 'emails.users.invite';

    $data = ['user' => $user, 'inputs' => $inputs];

    $subject = 'Join Bueno';

    return $this->sendTo($inputs['email'], $subject, $view, $data);
  }


  public function sendOrderExport($email,$subject,$view,$data,$path)
  {
    return $this->sendTo($email,$subject,$view,$data,$path);
  }

  public function testMail($email)
  {
    $view = 'emails.admins.all_orders';

    $data = [];

    $path = null;
    return $this->sendTo($email,'Test',$view,$data,$path);
  }


}