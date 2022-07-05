@foreach($orderItems as $product)
@foreach($product->orderItems as $item)
{{ $item->quantity }}x {{ $product->name }}
@if($item->notes)
    _(notes: {{ $item->notes }})_
@endif
@endforeach
@endforeach
