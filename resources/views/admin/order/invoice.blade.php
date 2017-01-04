<div style="width: 288px;font-size: 14px;font-weight: small;font-family: Arial">
  <table cellpadding="2" cellspacing="2" style= "font-size: inherit">
  <tr class="center">
      <td colspan="1" align="center">
            <img src="/img/logo.png" height="30"/>
        </td>
  </tr>
                <tr>
        
            <td colspan="3" align="center" style= "padding-top: 17px">
                <img src="data:image/png;base64,{{ DNS1D::getBarcodePNG($order->order_no, "C39E+",1.3,35) }} " alt="barcode"   />
                <span style="padding-left: 10px;">{{$order->order_no}}</span>
            </td>
          </tr>
        </table>
        </div>
<div style="width: 288px;font-size: 12px;font-weight: small;font-family: Arial">
    <table cellpadding="2" cellspacing="2" style= "font-size: inherit">
       <tr>
            <td colspan="4"><hr/></td>
        </tr>
        <tr>
            <td width="300"><strong>Meal</strong></td>
            <td width="60" align="right"><strong>Price</strong></td>
            <td width="0" align="center"><strong>Qty</strong></td>
            <td width="30" align="right"><strong>Total(Rs)</strong></td>
        </tr>   
        <tr>
            <td colspan="4"><hr/></td>
        </tr>
        @foreach($order->items as $item)
        <tr>
            <td>{{$item->itemable->name}}</td>
            <td align="right" style="padding-right: 0px;">{{$item->pivot->unit_price}}</td>
            <td align="center" width="60">{{$item->pivot->quantity}}</td>
            <td align="right">{{$item->pivot->unit_price * $item->pivot->quantity}}</td>
        </tr>
        @endforeach
                                <tr>
            <td colspan="4"><hr/></td>
        </tr>
        <tr>
            <td><strong>Total</strong></td>
            <td align="right"></td>
            <td align="center">{{ $order->orderItems->sum(function($item){ return $item->pivot->quantity; }) }}</td>
            <td align="right">{{$order->invoice->where('charge_for','Order Amount')->first()->amount}}</td>
        </tr>
        @if($order->invoice->where('charge_for','Discount')->first()->amount)
        <tr>
            <td><strong>Discount</strong></td>
            <td align="right"></td>
            <td align="center"></td>
            <td align="right"> - {{$order->invoice->where('charge_for','Discount')->first()->amount}}</td>
        </tr>
        @endif
        @if($order->invoice->where('charge_for','Points Redeemed')->first()->amount)
        <tr>
            <td><strong>Credits Redeem</strong></td>
            <td align="right"></td>
            <td align="center"></td>
            <td align="right"> - {{$order->invoice->where('charge_for','Points Redeemed')->first()->amount}}</td>
        </tr>
        @endif
        @if($order->invoice->where('charge_for','VAT')->first()->amount)
        <tr>
            <td><strong>VAT</strong></td>
            <td align="right"></td>
            <td align="center"></td>
            <td align="right">{{$order->invoice->where('charge_for','VAT')->first()->amount}}</td>
        </tr>
        @endif
        @if($order->invoice->where('charge_for','Service Tax')->first()->amount)
        <tr>
            <td><strong>Service Tax</strong></td>
            <td align="right"></td>
            <td align="center"></td>
            <td align="right">{{$order->invoice->where('charge_for','Service Tax')->first()->amount}}</td>
        </tr>
        @endif
        @if($order->invoice->where('charge_for','Service Charge')->first()->amount)
            <tr>
                <td><strong>Service Charge</strong></td>
                <td align="right"></td>
                <td align="center"></td>
                <td align="right">{{$order->invoice->where('charge_for','Service Charge')->first()->amount}}</td>
            </tr>
        @endif
        @if($order->invoice->where('charge_for','Packaging Charge')->first()->amount)
        <tr>
            <td><strong>Packaging Charge</strong></td>
            <td align="right"></td>
            <td align="center"></td>
            <td align="right">{{$order->invoice->where('charge_for','Packaging Charge')->first()->amount}}</td>
        </tr>
        @endif
        @if($order->invoice->where('charge_for','Donation Amount')->first()->amount)
        <tr>
            <td><strong>Donation Amount</strong></td>
            <td align="right"></td>
            <td align="center"></td>
            <td align="right">{{$order->invoice->where('charge_for','Donation Amount')->first()->amount}}</td>
        </tr>
        @endif
        <tr>
            <td><strong>Grand Total</strong></td>
            <td align="right"></td>
            <td align="center"></td>
            <td align="right">{{$order->invoice->where('charge_for','Total Amount')->first()->amount}}</td>
        </tr>
        <tr>
            <td colspan="4"><hr/></td>
        </tr>
        <tr>
            <td><strong>Order No.</strong></td>                            
            <td colspan="3">{{$order->order_no}}</td>
            
        </tr>
        <tr>
            <td><strong>Ordered on</strong></td>
            <td colspan="3">{{$order->source->name}}</td>
        </tr>
        @if($order->coupon)
        <tr>
            <td><strong>Coupon Applied</strong></td>
            <td colspan="3">{{$order->coupon->code}}</td>
        </tr>
        @endif
        <tr>
            <td><strong>Order Date</strong></td>                            
            <td colspan="3">
               {{$order->created_at->format('H:i d F,Y')}} </td>
            
        </tr>
        <tr>
            <td><strong>Total Amount</strong></td>                            
            <td colspan="3">{{$order->paymentInfo->amount}}</td>
            
        </tr>
        <tr>
            <td><strong>Payment Mode</strong></td>                            
            <td colspan="3">{{$order->paymentMode->name}}</td>

        </tr>
        <tr>
            <td colspan="4"><hr/></td>
        </tr>
        <tr>
            <td><strong>Name</strong></td>
            <td colspan="3">{{$order->user->full_name}}</td>
            
        </tr>
        @if($order->user->email)
                <tr>
            <td><strong>Email</strong></td>
            <td colspan="3">{{$order->user->email}}</td>
            
        </tr>
        @endif
        <tr>
            <td><strong>Mobile</strong></td>
            <td colspan="3">@if($order->user_phone){{$order->user_phone}}@else{{$order->user->phone}} @endif</td>
        </tr>
        @if($order->user->membership->min > 0)
            <tr>
                <td><strong>User Level</strong></td>
                <td colspan="3">{{$order->user->membership->name}}</td>
            </tr>
        @endif
        <!--<tr>
            <td><strong>Locality</strong></td>
            <td colspan="3">{{$order->area->name}}</td>
        </tr>-->
        <tr>
            <td><strong>Address</strong></td>
            <td colspan="3">{{$order->delivery_address}}</td>
        @if($order->instruction)
        <tr>
            <td><strong>Special Instructions</strong></td>
            <td colspan="3"><span id="ord_special_instruction">{{$order->instruction}}</span></td>                                
        </tr>
        @endif
        @if($order->deliveryBoy)
        <tr>
            <td><strong>Delivery Boy</strong></td>
            <td colspan="3">
                {{$order->deliveryBoy->full_name}} </td>

        </tr>
        @endif

        
        <tr>
            <td colspan="4"><hr/></td>
        </tr>

    </table>
    @if($invoice_text->value)
    <p style="border-style: dotted; padding: 10px; border-radius: 10px; text-align: center">{{$invoice_text->value}}</p>
    <tr>
        <td colspan="4"><hr/></td>
    </tr>
    @endif
    <strong>&nbsp; Bueno Foods Pvt. Ltd.
            </strong><br/>
    &nbsp; Regd. office 115, Vasant Enclave,
            <br/>
    &nbsp;  New Delhi 110057<br/>
    &nbsp; <strong>TIN :</strong> 06201834988<br/>
    &nbsp; <strong>CIN :</strong> U55101DL2012PTC240388<br/>
        <tr>
            <td colspan="4"><hr/></td>
        </tr>
    <strong style="margin-left:50px">Thank you! Enjoy your meal.</strong>
    <tr>
        <td colspan="4"><hr/></td>
    </tr>
    <strong>
        @if($order->paymentInfo->cashback_buff!=0)
            Your would earn {{ $order->paymentInfo->cashback_buff }} rewards points within 24 hours of delivery.<br/>
        @endif
            Refer your friends and earn rewards. Share your code {{$order->user->referral_code}}
    </strong>
</div>


<script>
    window.print();
</script>

