<div class="flex flex-col">
    <p class="text-gray-800 font-semibold leading-6 dark:text-white">
        {{ $address->company_name ?? '' }}
    </p>

    <p class="text-gray-800 font-semibold leading-6 dark:text-white">
        {{ $address->name }}
    </p>
    
    <p class="text-gray-600 dark:text-gray-300 !leading-6">
        {{ $address->address }}<br>

        {{ $address->city }}<br>

        {{ $address->state }}<br>

        {{ core()->country_name($address->country) }} @if ($address->postcode) ({{ $address->postcode }}) @endif<br>

        {{ __('admin::app.sales.orders.view.contact') }} : {{ $address->phone }}
    </p>
</div>