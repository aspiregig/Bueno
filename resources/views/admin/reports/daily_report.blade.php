Report Day : {{$data['start']->format('l')}}<br>
Report Date : {{$data['start']->format('d-m-Y')}}<br>
No of Orders : {{$data['todays_orders']}}

{{$data['start']->format('g:ia \o\n l jS F Y')}}

{{$data['end']->format('g:ia \o\n l jS F Y')}}