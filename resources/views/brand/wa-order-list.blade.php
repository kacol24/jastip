@foreach($orderItems as $productName => $items)
{{ $productName }}
----------
@foreach($items as $item)
{{ $item->quantity }}x {{ $productName }}
@if($item->notes)
    _(notes: {{ $item->notes }})_
@endif
@endforeach
@endforeach
