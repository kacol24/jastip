Halo, {{ $customer->name }}!
Thank you udah titip oleh2 lewat *@keranjangoleh2*

Cek kembali detail pesanan kamu ya, pastikan udah benar dan sesuai...

*PENGIRIMAN*
==========
{{ $customer->name }}
@if($customer->phone)
{{ $customer->phone ? '0' . $customer->phone : '' }}
@endif
@if($customer->address)
{{ strtoupper($customer->address) }}
@endif

*ORDER*
==========
@foreach($storeOrders as $storeName => $orderItems)
*{{ $storeName }}*
@foreach($orderItems as $item)
{{ $item->quantity }}x {{ $item->name }}
@if($item->notes)
    _(notes: {{ $item->notes }})_
@endif

@endforeach
@endforeach

Thank you and have a wonderful day :)

_PS: Jangan lupa follow IG kami ya @keranjangoleh2 https://www.instagram.com/keranjangoleh2_
