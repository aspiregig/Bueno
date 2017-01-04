<p>
    Hello Admin, Some of the items are in low stock :
</p>

@foreach($stocks->groupBy('kitchen_id') as $kitchen_stock)
    <h2>{{ $kitchen_stock->first()->kitchen->name }}</h2>
    @foreach($kitchen_stock as $stock)
        <li>{{ $stock->item->itemable->name }} : {{ $stock->value }}</li>
    @endforeach
@endforeach

