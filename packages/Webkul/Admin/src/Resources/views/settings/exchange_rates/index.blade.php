<x-admin::layouts>
    <div class="flex justify-between items-center">
        <p class="text-[20px] text-gray-800 font-bold">
            @lang('admin::app.settings.exchange-rates.index.title')
        </p>

        <div class="flex gap-x-[10px] items-center">
            <!-- Create new Exchange Rates -->
            @include('admin::settings.exchange_rates.create')
        </div>
    </div>
</x-admin::layouts>