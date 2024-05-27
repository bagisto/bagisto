<div class="flex flex-col max-md:hidden">
    <p class="font-semibold leading-6 text-gray-800">
        {{ $address->company_name ?? '' }}
    </p>

    <p class="font-semibold leading-6 text-gray-800">
        {{ $address->name }}
    </p>
    
    <p class="!leading-6 text-gray-600">
        {{ $address->address }}<br>

        {{ $address->city }}<br>

        {{ $address->state }}<br>

        {{ core()->country_name($address->country) }} @if ($address->postcode) ({{ $address->postcode }}) @endif<br>

        {{ __('shop::app.customers.account.orders.view.contact') }} : {{ $address->phone }}
    </p>
</div>

<!-- For Mobile View -->
<div class="text-gray-800 md:hidden">
    <p class="font-semibold">
        {{ $address->company_name ?? '' }}
    </p>

    <p class="text-xs">
        {{ $address->name }}

        {{ $address->address }}

        {{ $address->city }}

        {{ $address->state }}

        {{ core()->country_name($address->country) }} @if ($address->postcode) ({{ $address->postcode }}) @endif <br>

        <span class="no-underline">
            {{ __('shop::app.customers.account.orders.view.contact') }} : {{ $address->phone }}
        </span>
    </p>
</div>