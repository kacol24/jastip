Halo, {{ $customer->name }}!
Thank you udah titip oleh2 lewat *@keranjangoleh2*

@yield('intro')

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
--- *{{ $storeName }}* ---
@foreach($orderItems as $item)
{{ $item->quantity }}x {{ $item->name }}
@if($item->notes)
   _(notes: {{ $item->notes }})_
@endif
@if(isset($invoice) && $invoice)
   ({{ $item->formatted_price }} + {{ $item->formatted_fee }}) x {{ $item->quantity }} = {{ $item->formatted_line_total }}
@endif

@endforeach
@endforeach
@if(isset($invoice) && $invoice)
*INVOICE*
==========
Subtotal: Rp{{ number_format($order->subtotal, 0, ',', '.') }}
Shipping: Rp{{ number_format($order->shipping_fee, 0, ',', '.') }}
@if(!$order->deposit)
*TOTAL: Rp{{ number_format($order->grand_total, 0, ',', '.') }}*
@else
TOTAL: Rp{{ number_format($order->grand_total, 0, ',', '.') }}
_Deposit: (Rp{{ number_format($order->deposit, 0, ',', '.') }})_
----------
*TAGIHAN: Rp{{ number_format($order->amount_due, 0, ',', '.') }}*
@endif

Pembayaran dapat dilakukan lewat Bank Transfer ke rekening *BCA 087 127 3757* a.n Fernanda Putri. Kabarin ya kalau udah melakukan pembayaran.
    
@endif

Thank you and have a wonderful day :)

_PS: Jangan lupa follow IG kami ya @keranjangoleh2 https://www.instagram.com/keranjangoleh2_
