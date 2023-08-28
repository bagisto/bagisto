<div class="flex flex-col">
    <p class="text-gray-600">
        {{ $address->company_name ?? '' }}
    </p>

    <p class="text-gray-600">
        {{ $address->name }}
    </p>

    <p class="text-gray-600">
        {{ $address->address1 }}
    </p>

    <p class="text-gray-600">
        {{ $address->postcode }} {{ $address->city }}
    </p>

    <p class="text-gray-600">
        {{ $address->state }}
    </p>

    <p class="text-gray-600">
        {{ core()->country_name($address->country) }}
    </p>

    <p class="text-gray-600">
        @lang('admin::app.sales.orders.view.contact') : {{ $address->phone }}
    </p>
</div>