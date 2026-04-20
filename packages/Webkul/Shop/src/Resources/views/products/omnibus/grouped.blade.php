<div class="omnibus-price-info mt-2 text-sm text-gray-500">
    <p class="flex items-center">
        <span class="icon-info text-gray-400"></span>

        <span>@lang('shop::app.products.omnibus.price-info')</span>
    </p>

    <div class="mt-2 space-y-1">
        @foreach ($items as $item)
            <div class="flex items-center justify-between gap-4">
                <span class="truncate text-gray-600">{{ $item['name'] }}</span>

                <strong class="font-medium whitespace-nowrap text-gray-700">{{ $item['price'] }}</strong>
            </div>
        @endforeach
    </div>
</div>
