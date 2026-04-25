<x-mail::message>
# Order Confirmation

Thank you for your business! Your order **#{{ $order->id }}** has been placed successfully.

<x-mail::table>
| Item       | Qty         | Price  |
| :--------- | :---------- | -----: |
@foreach($order->items as $item)
| {{ $item->product ? $item->product->name : 'Unknown Product' }}       | {{ $item->quantity }}         | {{ \App\Helpers\CurrencyHelper::format($item->price * $item->quantity) }}  |
@endforeach
</x-mail::table>

<br>
**Subtotal:** {{ \App\Helpers\CurrencyHelper::format($order->total_price) }}<br>
**Total Due:** {{ \App\Helpers\CurrencyHelper::format($order->total_price) }}

You can view or download your invoice by clicking the button below:

<x-mail::button :url="route('orders.public.invoice', $order->payment_link_uuid)">
Download Invoice
</x-mail::button>

<x-mail::button :url="route('orders.public.show', $order->payment_link_uuid)">
View Order
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
