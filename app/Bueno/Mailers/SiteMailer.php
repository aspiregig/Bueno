<?php namespace Bueno\Mailers;

use App\Models\User;
use App\Models\Order;

class SiteMailer extends Mailer{

  public function contact($inputs)
  {
    $view = 'emails.site.contact';

    $data = ['inputs' => $inputs];

    $subject = 'Contact Query/ Feedback';

    $this->sendTo('info@bueno.kitchen', $subject, $view, $data);
  }

  public function catering($inputs)
  {
    $view = 'emails.site.catering';

    $data = ['inputs' => $inputs];

    $subject = 'Catering Query';

    $this->sendTo('info@bueno.kitchen', $subject, $view, $data);
  }

  public function lowStockAlert($stocks)
  {
    $view = 'emails.site.low_stock_alert';

    $data = ['stocks' => $stocks];

    $subject = 'Low Stock Alert';

    $this->sendTo([
      'rohan@bueno.kitchen',
      'puneet@bueno.kitchen',
      'neeraj@bueno.kitchen'
    ], $subject, $view, $data);
  }

}