<div style="width:320px;font-size:16px; padding:5px; font-family: Arial;"> <center><img src="data:image/png;base64,{{ DNS1D::getBarcodePNG($order->order_no, "C39E+",1.3,63) }}" alt="barcode"   /> </center><div style="padding: 10px;"><center>{{$order->order_no}}</center></div>{{$order->created_at->format('Y-m-d   H:i')}}<br/><br/>
            @foreach($order->orderItems as $item)
                <div style="height:55px ;font-size:16px; padding-top:20px;font-family: Arial;">
                    {{$item->itemable->name}} ----- {{$item->pivot->quantity}}
                </div><br/><br/>
            @endforeach
            <script>
                window.print();
            </script>
        </div>
