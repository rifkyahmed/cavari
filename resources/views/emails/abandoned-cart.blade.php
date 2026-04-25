<x-mail::message>
# Hello, {{ $userName }}

We noticed you left some exquisite pieces in your bag. At **{{ config('app.name') }}**, every creation is a testament to earth's rarest beauty, and we would hate for you to miss out on yours.

### Your Selection:
@foreach($cartItems as $item)
* **{{ $item['name'] }}** ({{ $item['quantity'] }}x) - {{ \App\Helpers\CurrencyHelper::format($item['price'] * $item['quantity']) }}
@endforeach

**Total: {{ \App\Helpers\CurrencyHelper::format($total) }}**

<x-mail::button :url="route('cart.index')">
Return to Your Bag
</x-mail::button>

If you have any questions or require a personal consultation regarding these pieces, our concierge team is always available to assist you.

With elegance,<br>
The {{ config('app.name') }} Team
</x-mail::message>
