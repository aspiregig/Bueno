<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>welcome to bueno</title>
    <style type="text/css">
        <!--
        body {
            margin-left: 0px;
            margin-top: 0px;
            margin-right: 0px;
            margin-bottom: 0px;
        }
        -->
    </style></head>

<body>

<table align="center" border="0" cellpadding="0" cellspacing="0" width="700">

  <tbody><tr>

    <td style="background-color:#231f20"><table border="0" cellpadding="0" cellspacing="0" width="100%">

      <tbody><tr>

          <td valign="middle" style="padding:20px;"> <a href="{{ route('pages.index') }}"><img src="{{ asset('images/email_logo.jpg')}}"/></a></td>

        <td style="font-family:Arial,Helvetica,sans-serif;font-size:20px;color:#ffffff;float:right;vertical-align:middle;padding:40px 20px 0px 0px" align="right"> 

        

        <a href="{{ route('users.orders.get')}}" style="color:#ffffff;text-decoration:none" target="_blank">Your <span class="il">Orders</span> </a> |

        <a href="{{ route('pages.index') }}" style="color:#ffffff;text-decoration:none" target="_blank">bueno </a></td>

      </tr>

    </tbody></table></td>

  </tr>

  <tr>

    <td><table border="0" cellpadding="0" cellspacing="0" width="100%">

      <tbody><tr>

        <td style="width:420px;padding-left:20px" align="left" valign="top"><table border="0" cellpadding="0" cellspacing="0" width="100%">

          <tbody><tr>

            <td style="font-family:roboto,Arial,Helvetica,sans-serif;font-size:38px;color:#231f20;text-transform:uppercase;border-bottom:1px dashed #000;padding:10px 0px"><span class="il">Order</span> <span class="il">Confirmation</span></td>

          </tr>

          <tr>

            <td style="font-family:roboto,Arial,Helvetica,sans-serif;font-size:16px;color:#231f20;padding:20px 0px 20px 0px">{{ $order->user->full_name }} <br>

                <br>

               Your Order is on its way! <br><br>

              Your delicious Bueno meal has been packed and is out for delivery.<br />
                                    The food is freshly made in our kitchen. Please consume it within one hour of delivery.<br/>
                <br>

              <br>



              Enjoy your meal! <br>

              From all of us at<br>

              Bueno. </td>

          </tr>

          <tr>

            <td style="font-family:roboto,Arial,Helvetica,sans-serif;font-size:16px;color:#231f20;padding:20px 0px 0px 0px;border-top:1px dashed #231f20">For feedback or enquiries: Call us on: <a href="tel:+911139586767" style="color:#231f20;text-decoration:none" target="_blank"><b> 01139586767  </b></a> | Email us on: <a href="mailto:info@bueno.kitchen" style="color:#231f20;text-decoration:none" target="_blank"><b> info@bueno.kitchen </b></a></td>

          </tr>

        </tbody></table></td>

        <td style="padding-left:20px;padding-right:20px" align="left" valign="top"><table border="0" cellpadding="0" cellspacing="0" width="100%">

          <tbody><tr>

            <td style="height:108px;border-bottom:1px dashed #231f20">&nbsp;</td>

          </tr>

          <tr>

            <td style="font-family:roboto,Arial,Helvetica,sans-serif;font-size:16px;color:#231f20;padding:20px 0px 0px 0px">

<b>Delivery Address:</b><br>



{{ $order->user->full_name }}<br>

<br>

{{ $order->delivery_address }}<br>

<br>

{{ $order->area->name }}<br>

</td>

   </tr>

            <tr>

            <td>&nbsp;</td>

          </tr>

          <tr>

            <td>&nbsp;</td>

          </tr>

        </tbody></table></td>

      </tr>

    </tbody></table></td>

  </tr><tr><td><br>

  <h3><u><span class="il">Order</span>: {{ $order->order_no }} (<span><span>{{ $order->created_at->format('d M Y H:i:s') }}</span></span>)</u></h3>

                <p style="font-family:roboto,Arial,Helvetica,sans-serif;font-size:16px">Below is your invoice:</p><p>



                </p><table border="0" cellpadding="0" cellspacing="0" width="100%">

                    <tbody>

                        <tr style="background-color:#231f20;color:#fff">

                            <td style="font-family:roboto,Arial,Helvetica,sans-serif;font-size:16px;padding:10px" width="60%">Dish</td>

                            <td style="font-family:roboto,Arial,Helvetica,sans-serif;font-size:16px;padding:10px" width="20%">Quantity</td>

                            <td style="font-family:roboto,Arial,Helvetica,sans-serif;font-size:16px;padding:10px" align="right">Amount</td>

                        </tr>
                        @foreach($order->orderItems as $item)
                            <tr>

                            <td style="font-family:roboto,Arial,Helvetica,sans-serif;font-size:16px;padding:10px">{{ $item->itemable->name }}</td>

                            <td style="font-family:roboto,Arial,Helvetica,sans-serif;font-size:16px;padding:10px" align="center">{{ $item->pivot->quantity }}</td>

                            <td style="font-family:roboto,Arial,Helvetica,sans-serif;font-size:16px;padding:10px" align="right">{{ $item->pivot->unit_price * $item->pivot->quantity }}</td>

                        </tr>

                        @endforeach
                          <tr>

                            <td colspan="3" style="background-color:#666" height="2"></td>

                        </tr>

                        <tr>

                            <td style="font-family:roboto,Arial,Helvetica,sans-serif;font-size:16px;padding:10px">Total</td>

                            <td style="font-family:roboto,Arial,Helvetica,sans-serif;font-size:16px;padding:10px" align="center">{{ $order->orderItems->sum(function($item){ return $item->pivot->quantity; }) }}</td>

                            <td style="font-family:roboto,Arial,Helvetica,sans-serif;font-size:16px;padding:10px" align="right">{{ $order->orderItems->sum(function($item){ return $item->pivot->quantity * $item->pivot->unit_price; }) }}</td>

                        </tr>

                                                <tr>
                          
                          @if($order->ngo)
                            <td colspan="2" style="font-family:roboto,Arial,Helvetica,sans-serif;font-size:16px;padding:10px">Donation - {{ $order->ngo->name }}</td>

                            <td style="font-family:roboto,Arial,Helvetica,sans-serif;font-size:16px;padding:10px" align="right">{{ $order->ngo->default_donation_amount }}</td>
                          @endif
                        </tr>

                                 <tr>

                                    <tr>
                          
                          @if($order->invoice->where('charge_for','Points Redeemed')->first()->amount)
                            <td colspan="2" style="font-family:roboto,Arial,Helvetica,sans-serif;font-size:16px;padding:10px">Credits Redeemed</td>

                            <td style="font-family:roboto,Arial,Helvetica,sans-serif;font-size:16px;padding:10px" align="right">{{$order->invoice->where('charge_for','Points Redeemed')->first()->amount}}</td>
                          @endif
                        </tr>

                                 <tr>
                          
                          @if($order->coupon)
                            <td colspan="2" style="font-family:roboto,Arial,Helvetica,sans-serif;font-size:16px;padding:10px">Discount — (Code: {{ $order->coupon->code }})</td>

                            <td style="font-family:roboto,Arial,Helvetica,sans-serif;font-size:16px;padding:10px" align="right">@if($order->invoice->where('charge_for','Discount')->first()->amount){{$order->invoice->where('charge_for','Discount')->first()->amount}}@endif</td>
                          @endif
                        </tr>

                                                <tr>

                            <td colspan="2" style="font-family:roboto,Arial,Helvetica,sans-serif;font-size:16px;padding:10px">Taxes</td>

                            <td style="font-family:roboto,Arial,Helvetica,sans-serif;font-size:16px;padding:10px" align="right">
                                {{ $order->invoice->where('charge_for','VAT')->first()->amount + 
                                    $order->invoice->where('charge_for','Packaging Charge')->first()->amount + 
                                    $order->invoice->where('charge_for','Service Charge')->first()->amount + 
                                    $order->invoice->where('charge_for','Delivery Charge')->first()->amount +
                                     $order->invoice->where('charge_for','Service Tax')->first()->amount 
                                }}</td>
                          
                        </tr>

                                                <tr>

                            <td colspan="3" style="background-color:#666" height="2"></td>

                        </tr>

                        <tr>

                            <td colspan="2" style="font-family:roboto,Arial,Helvetica,sans-serif;font-size:16px;padding:10px"><span class="il">Order</span> Total</td>

                            <td style="font-family:roboto,Arial,Helvetica,sans-serif;font-size:16px;padding:10px" align="right">{{ $order->invoice->where('charge_for','Total Amount')->first()->amount  }}</td>

                        </tr>

                    </tbody>

                </table>

                <h3 style="font-family:roboto,Arial,Helvetica,sans-serif;font-size:16px"><u>Payment Mode</u></h3>

                <p style="font-family:roboto,Arial,Helvetica,sans-serif;font-size:16px">{{ $order->paymentMode->name }}</p>

                <h3 style="font-family:roboto,Arial,Helvetica,sans-serif;font-size:16px"><u>Customer details</u></h3>

                <p style="font-family:roboto,Arial,Helvetica,sans-serif;font-size:16px">Email: <a href="mailto:{{ $order->user->email }}" target="_blank">{{ $order->user->email }}</a> <br>Tel: <a href="tel:{{ $order->user->phone }}" value="{{ $order->user->phone}}" target="_blank">{{ $order->user->phone }}</a>                </p> 

                                <h4 style="font-family:roboto,Arial,Helvetica,sans-serif;font-size:16px">We now deliver across Gurgaon all days <span class="aBn" data-term="goog_1405373293" tabindex="0"><span class="aQJ">9 AM to 4 AM</span></span>, Contact us on <a href="tel:01139586767" value="+911139586767" target="_blank">01139586767</a></h4>           

            <p></p><p></p></td>

        

        </tr><tr>

          

        </tr> 

         

   <tr>

    <td style="background-color:#d1d3d4"><table border="0" cellpadding="0" cellspacing="0" width="100%">
      <tbody><tr>

        <td style="width:398px" valign="top"><table border="0" cellpadding="0" cellspacing="0" width="100%">



          <tbody><tr>

            <td style="font-family:roboto,Arial,Helvetica,sans-serif;font-size:14px;color:#fff;background-color:#d1d3d4;padding:20px 20px;text-decoration:none">

This email was sent from a notification-only address that cannot accept incoming email. Please do not reply to this message.</td>

          </tr>

        </tbody></table></td>

      

      </tr>

    </tbody></table></td>

  </tr>

</tbody></table>

</body>
</html>



