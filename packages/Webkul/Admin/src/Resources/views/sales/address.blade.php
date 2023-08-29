<div class="flex flex-col">
    <p class="text-gray-800 font-semibold">
        {{ $address->company_name ?? '' }}
    </p>

    <p class="text-gray-800 font-semibold">
        {{ $address->name }}
    </p>
    
    <p class="text-gray-600">
        {{ $address->address1 }}<br>

        {{ $address->postcode }} {{ $address->city }}<br>

        {{ $address->state }}<br>

        {{ core()->country_name($address->country) }}<br>

        {{ __('admin::app.sales.orders.view.contact') }} : {{ $address->phone }}
    </p>
</div>