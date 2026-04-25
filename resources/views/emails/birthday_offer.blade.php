<x-mail::message>
# Happy Birthday, {{ $user->name }}! 🎉

As a cherished member of the Cavari family, we wanted to wish you a beautiful and luxurious birthday.

To celebrate your special day, we have reserved an exclusive gift just for you. Please use the following code at checkout to claim your birthday offer:

<x-mail::panel>
**{{ $coupon->code }}**

This exclusive code grants you {{ $coupon->discount_type === 'percentage' ? $coupon->discount_value . '%' : '$' . $coupon->discount_value }} off your next purchase and is valid until {{ $coupon->expiry_date->format('F jS, Y') }}.
</x-mail::panel>

<x-mail::button :url="route('home')">
Explore the Collection
</x-mail::button>

Wishing you a day as brilliant as our rarest gems.<br>
Warmly,<br>
{{ config('app.name') }}
</x-mail::message>
