@php
    $admin = auth()->guard('admin')->user();
@endphp

<x-admin::layouts>
    <x-slot:title>
        @lang('admin::app.dashboard.index.title')
    </x-slot:title>

    {{-- User Detailes Section --}}
    <div class="flex gap-[16px] justify-between items-center mb-[20px] max-sm:flex-wrap">
        <div class="grid gap-[6px]">
            <p class="pt-[6px] text-[20px] text-gray-800 dark:text-white font-bold leading-[24px]">
                @lang('admin::app.dashboard.index.user-name', ['user_name' => $admin->name])
            </p>

            <p class="text-gray-600 dark:text-gray-300">
                @lang('admin::app.dashboard.index.user-info')
            </p>
        </div>
    </div>

    {{-- Body Component --}}
    <div class="flex gap-[10px] mt-[14px] max-xl:flex-wrap">
        {{-- Left Section --}}
        <div class=" flex flex-col gap-[30px] flex-1 max-xl:flex-auto">
            {{-- Overall Detailes --}}
            <div class="flex flex-col gap-[8px]   ">
                <p class="text-[16px] text-gray-600 dark:text-gray-300 font-semibold">
                    @lang('admin::app.dashboard.index.overall-details')
                </p>

                <div class="p-[16px] rounded-[4px] bg-white dark:bg-gray-900 box-shadow">
                    <div class="flex gap-[16px] flex-wrap ">
                        {{-- Total Sales --}}
                        <div class="flex gap-[10px] flex-1 min-w-[200px]">
                            <div class="w-full h-[60px] max-w-[60px] max-h-[60px] dark:invert dark:mix-blend-exclusion">
                                <img
                                    src="{{ bagisto_asset('images/total-sales.svg')}}"
                                    title="@lang('admin::app.dashboard.index.total-sales')"
                                >
                            </div>

                            {{-- Sales Stats --}}
                            <div class="grid gap-[4px] place-content-start">
                                <p class="text-[16px] text-gray-800 dark:text-white font-semibold">
                                    {{ core()->formatBasePrice($statistics['over_all']['total_sales']['current']) }}
                                </p>

                                <p class="text-[12px] text-gray-600 dark:text-gray-300 font-semibold">
                                    @lang('admin::app.dashboard.index.total-sales')
                                </p>

                                {{-- Sales Percentage --}}
                                <div class="flex gap-[2px] items-center">
                                    @if ($statistics['over_all']['total_sales']['progress'] < 0)
                                        <span class="icon-down-stat text-[16px] text-red-500"></span>

                                        <p class="text-[12px] text-red-500 font-semibold">
                                            @lang('admin::app.dashboard.index.decreased', [
                                                'progress' => -number_format($statistics['over_all']['total_sales']['progress'], 1),
                                            ])
                                        </p>
                                    @else
                                        <span class="icon-up-stat text-[16px] text-emerald-500"></span>

                                        <p class="text-[12px] text-emerald-500 font-semibold">
                                            @lang('admin::app.dashboard.index.increased', [
                                                'progress' => number_format($statistics['over_all']['total_sales']['progress'], 1),
                                            ])
                                        </p>
                                    @endif
                                </div>
                            </div>
                        </div>

                        {{-- Total Orders --}}
                        <div class="flex gap-[10px] flex-1 min-w-[200px]">
                            <div class="w-full h-[60px] max-w-[60px] max-h-[60px] dark:invert dark:mix-blend-exclusion">
                                <img
                                    src="{{ bagisto_asset('images/total-orders.svg')}}"
                                    title="@lang('admin::app.dashboard.index.total-orders')"
                                >
                            </div>

                            {{-- Orders Stats --}}
                            <div class="grid gap-[4px] place-content-start">
                                <p class="text-[16px] text-gray-800 dark:text-white font-semibold">
                                    {{ $statistics['over_all']['total_orders']['current'] }}
                                </p>

                                <p class="text-[12px] text-gray-600 dark:text-gray-300 font-semibold">
                                    @lang('admin::app.dashboard.index.total-orders')
                                </p>

                                {{-- Order Percentage --}}
                                <div class="flex gap-[2px] items-center">
                                    @if ($statistics['over_all']['total_orders']['progress'] < 0)
                                        <span class="icon-down-stat text-[16px] text-red-500"></span>

                                        <p class="text-[12px] text-red-500 font-semibold">
                                            @lang('admin::app.dashboard.index.decreased', [
                                                'progress' => -number_format($statistics['over_all']['total_orders']['progress'], 1),
                                            ])
                                        </p>
                                    @else
                                        <span class="icon-up-stat text-[16px] text-emerald-500"></span>

                                        <p class="text-[12px] text-emerald-500 font-semibold">
                                            @lang('admin::app.dashboard.index.increased', [
                                                'progress' => number_format($statistics['over_all']['total_orders']['progress'], 1),
                                            ])
                                        </p>
                                    @endif
                                </div>
                            </div>
                        </div>

                        {{-- Total Customers --}}
                        <div class="flex gap-[10px] flex-1 min-w-[200px]">
                            <div class="w-full h-[60px] max-w-[60px] max-h-[60px] dark:invert dark:mix-blend-exclusion">
                                <img
                                    src="{{ bagisto_asset('images/customers.svg')}}"
                                    title="@lang('admin::app.dashboard.index.total-customers')"
                                >
                            </div>

                            {{-- Customers Stats --}}
                            <div class="grid gap-[4px] place-content-start">
                                <p class="text-[16px] text-gray-800 dark:text-white font-semibold">
                                    {{ $statistics['over_all']['total_customers']['current'] }}
                                </p>

                                <p class="text-[12px] text-gray-600 dark:text-gray-300 font-semibold">
                                    @lang('admin::app.dashboard.index.total-customers')
                                </p>

                                {{-- Customers Percentage --}}
                                <div class="flex gap-[2px] items-center">
                                    @if ($statistics['over_all']['total_customers']['progress'] < 0)
                                        <span class="icon-down-stat text-[16px] text-red-500"></span>

                                        <p class="text-[12px] text-red-500 font-semibold">
                                            @lang('admin::app.dashboard.index.decreased', [
                                                    'progress' => -number_format($statistics['over_all']['total_customers']['progress'], 1)
                                            ])
                                        </p>
                                    @else
                                        <span class="icon-up-stat text-[16px] text-emerald-500"></span>

                                        <p class="text-[12px] text-emerald-500 font-semibold">
                                            @lang('admin::app.dashboard.index.increased', [
                                                'progress' => number_format($statistics['over_all']['total_customers']['progress'], 1)
                                            ])
                                        </p>
                                    @endif
                                </div>
                            </div>
                        </div>

                        {{-- Average sales --}}
                        <div class="flex gap-[10px] flex-1 min-w-[200px]">
                            <div class="w-full h-[60px] max-w-[60px] max-h-[60px] dark:invert dark:mix-blend-exclusion">
                                <img
                                    src="{{ bagisto_asset('images/average-orders.svg')}}"
                                    title="@lang('admin::app.dashboard.index.average-sale')"
                                >
                            </div>

                            {{-- Sales Stats --}}
                            <div class="grid gap-[4px] place-content-start">
                                <p class="text-[16px] text-gray-800 dark:text-white font-semibold">
                                    {{ core()->formatBasePrice($statistics['over_all']['avg_sales']['current']) }}
                                </p>

                                <p class="text-[12px] text-gray-600 dark:text-gray-300 font-semibold">
                                    @lang('admin::app.dashboard.index.average-sale')
                                </p>

                                {{-- Sales Percentage --}}
                                <div class="flex gap-[2px] items-center">
                                    @if ($statistics['over_all']['avg_sales']['progress'] < 0)
                                        <span class="icon-down-stat text-[16px] text-red-500"></span>

                                        <p class="text-[12px] text-red-500 font-semibold">
                                            @lang('admin::app.dashboard.index.decreased', [
                                                'progress' => -number_format($statistics['over_all']['avg_sales']['progress'], 1),
                                            ])
                                        </p>
                                    @else
                                        <span class="icon-up-stat text-[16px] text-emerald-500"></span>
                                        <p class="text-[12px] text-emerald-500 font-semibold">
                                            @lang('admin::app.dashboard.index.increased', [
                                                'progress' => number_format($statistics['over_all']['avg_sales']['progress'], 1),
                                            ])
                                        </p>
                                    @endif
                                </div>
                            </div>
                        </div>

                        {{-- Unpaid Invoices --}}
                        <div class="flex gap-[10px] flex-1 min-w-[200px]">
                            <div class="w-full h-[60px] max-w-[60px] max-h-[60px] dark:invert dark:mix-blend-exclusion">
                                <img
                                    src="{{ bagisto_asset('images/unpaid-invoices.svg')}}"
                                    title="@lang('admin::app.dashboard.index.total-unpaid-invoices')"
                                >
                            </div>

                            <div class="grid gap-[4px] place-content-start">
                                <p class="text-[16px] text-gray-800 dark:text-white font-semibold">
                                    {{ core()->formatBasePrice($statistics['over_all']['total_unpaid_invoices']) }}
                                </p>

                                <p class="text-[12px] text-gray-600 dark:text-gray-300 font-semibold">
                                    @lang('admin::app.dashboard.index.total-unpaid-invoices')
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Todays Details --}}
            <div class="flex flex-col gap-[8px]  ">
                <p class="text-[16px] text-gray-600 dark:text-gray-300 font-semibold">
                    @lang('admin::app.dashboard.index.today-details')
                </p>

                <div class="rounded-[4px] box-shadow">
                    <div class="flex gap-[16px] flex-wrap p-[16px] bg-white dark:bg-gray-900 border-b-[1px] dark:border-gray-800">
                        {{-- Today's Sales --}}
                        <div class="flex gap-[10px] flex-1">
                            <img
                                class="w-full h-[60px] max-w-[60px] max-h-[60px] dark:invert dark:mix-blend-exclusion"
                                src="{{ bagisto_asset('images/total-sales.svg')}}"
                                title="@lang('admin::app.dashboard.index.today-sales')"
                            >

                            {{-- Sales Stats --}}
                            <div class="grid gap-[4px] place-content-start">
                                <p class="text-[16px] text-gray-800 dark:text-white font-semibold">
                                    {{ core()->formatBasePrice($statistics['today']['total_sales']['current']) }}
                                </p>

                                <p class="text-[12px] text-gray-600 dark:text-gray-300 font-semibold">
                                    @lang('admin::app.dashboard.index.today-sales')
                                </p>

                                {{-- Percentage Of Sales --}}
                                <div class="flex gap-[2px] items-center">
                                    @if ($statistics['today']['total_sales']['progress'] < 0)
                                        <span class="icon-down-stat text-[16px] text-red-500"></span>

                                        <p class="text-[12px] text-red-500 font-semibold">
                                            @lang('admin::app.dashboard.index.decreased', [
                                                'progress' => -number_format($statistics['today']['total_sales']['progress'], 1),
                                            ])
                                        </p>
                                    @else
                                        <span class="icon-up-stat text-[16px] text-emerald-500"></span>

                                        <p class="text-[12px] text-emerald-500 font-semibold">
                                            @lang('admin::app.dashboard.index.increased', [
                                                'progress' => number_format($statistics['today']['total_sales']['progress'], 1),
                                            ])
                                        </p>
                                    @endif
                                </div>
                            </div>
                        </div>

                        {{-- Today's Orders --}}
                        <div class="flex gap-[10px] flex-1">
                            <img
                                class="w-full h-[60px] max-w-[60px] max-h-[60px] dark:invert dark:mix-blend-exclusion"
                                src="{{ bagisto_asset('images/total-orders.svg')}}"
                                title="@lang('admin::app.dashboard.index.today-orders')"
                            >

                            {{-- Orders Stats --}}
                            <div class="grid gap-[4px] place-content-start">
                                <p class="text-[16px] text-gray-800 dark:text-white font-semibold">
                                    {{ $statistics['today']['total_orders']['current'] }}
                                </p>

                                <p class="text-[12px] text-gray-600 dark:text-gray-300 font-semibold">
                                    @lang('admin::app.dashboard.index.today-orders')
                                </p>

                                {{-- Orders Percentage --}}
                                <div class="flex gap-[2px] items-center">
                                    @if ($statistics['today']['total_orders']['progress'] < 0)
                                        <span class="icon-down-stat text-[16px] text-red-500"></span>

                                        <p class="text-[12px] text-red-500 font-semibold">
                                            @lang('admin::app.dashboard.index.decreased', [
                                                'progress' => -number_format($statistics['today']['total_orders']['progress'], 1),
                                            ])
                                        </p>
                                    @else
                                        <span class="icon-up-stat text-[16px] text-emerald-500"></span>

                                        <p class="text-[12px] text-emerald-500 font-semibold">
                                            @lang('admin::app.dashboard.index.increased', [
                                                'progress' => number_format($statistics['today']['total_orders']['progress'], 1),
                                            ])
                                        </p>
                                    @endif
                                </div>
                            </div>
                        </div>

                        {{-- Today's Customers --}}
                        <div class="flex gap-[10px] flex-1">
                            <img
                                class="w-full h-[60px] max-w-[60px] max-h-[60px] dark:invert dark:mix-blend-exclusion"
                                src="{{ bagisto_asset('images/customers.svg')}}"
                                title="@lang('admin::app.dashboard.index.today-customers')"
                            >

                            {{-- Customers Stats --}}
                            <div class="grid gap-[4px] place-content-start">
                                <p class="text-[16px] text-gray-800 dark:text-white font-semibold">
                                    {{ $statistics['today']['total_customers']['current'] }}
                                </p>

                                <p class="text-[12px] text-gray-600 dark:text-gray-300 font-semibold">
                                    @lang('admin::app.dashboard.index.today-customers')
                                </p>

                                {{-- Customers Percentage --}}
                                <div class="flex gap-[2px] items-center">
                                    @if ($statistics['today']['total_customers']['progress'] < 0)
                                        <span class="icon-down-stat text-[16px] text-red-500"></span>

                                        <p class="text-[12px] text-red-500 font-semibold">
                                            @lang('admin::app.dashboard.index.decreased', [
                                                'progress' => -number_format($statistics['today']['total_customers']['progress'], 1),
                                            ])
                                        </p>
                                    @else
                                        <span class="icon-up-stat text-[16px] text-emerald-500"></span>

                                        <p class="text-[12px] text-emerald-500 font-semibold">
                                            @lang('admin::app.dashboard.index.increased', [
                                                'progress' => number_format($statistics['today']['total_customers']['progress'], 1),
                                            ])
                                        </p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Today Orders Details -->
                    @foreach ($statistics['today']['orders'] as $item)
                        <div class="row grid grid-cols-4 gap-y-[24px] p-[16px] bg-white dark:bg-gray-900 border-b-[1px] dark:border-gray-800 transition-all hover:bg-gray-50 dark:hover:bg-gray-950 max-1580:grid-cols-3 max-sm:grid-cols-1">
                            {{-- Order ID, Status, Created --}}
                            <div class="flex gap-[10px]">
                                <div class="flex flex-col gap-[6px]">
                                    {{-- Order Id --}}
                                    <p class="text-[16px] text-gray-800 dark:text-white font-semibold">
                                        @lang('admin::app.dashboard.index.order-id', ['id' => $item->id])
                                    </p>

                                    <p class="text-gray-600 dark:text-gray-300">
                                        {{ $item->created_at}}
                                    </p>

                                    {{-- Order Status --}}
                                    @switch($item->status)
                                        @case('processing')
                                            <p class="label-active">
                                                {{ $item->status_label }}
                                            </p>
                                            @break

                                        @case('completed')
                                            <p class="label-active">
                                                {{ $item->status_label }}
                                            </p>
                                            @break

                                        @case('pending')
                                            <p class="label-pending">
                                                {{ $item->status_label }}
                                            </p>
                                            @break

                                        @case('canceled')
                                            <p class="label-cancelled">
                                                {{ $item->status_label }}
                                            </p>
                                            @break

                                        @case('closed')
                                            <p class="label-closed">
                                                {{ $item->status_label }}
                                            </p>
                                            @break

                                    @endswitch
                                </div>
                            </div>
        
                            {{-- Payment And Channel Detailes --}}
                            <div class="flex flex-col gap-[6px]">
                                {{-- Grand Total --}}
                                <p class="text-[16px] text-gray-800 dark:text-white font-semibold">
                                    {{ core()->formatBasePrice($item->grand_total)}}
                                </p>

                                {{-- Payment Mode --}}
                                <p class="text-gray-600 dark:text-gray-300">
                                    @lang('admin::app.dashboard.index.pay-by', ['method' => core()->getConfigData('sales.payment_methods.' . $item->payment->method . '.title')])
                                </p>

                                {{-- Channel Name --}}
                                <p class="text-gray-600 dark:text-gray-300">
                                    {{ $item->channel_name }}
                                </p>
                            </div>

                            <div class="flex flex-col gap-[6px]">
                                {{-- Customer Detailes --}}
                                <p class="text-[16px] text-gray-800 dark:text-white">
                                    {{ $item->customer_first_name }} {{ $item->customer_last_name }}
                                </p>

                                <p class="text-gray-600 dark:text-gray-300">
                                    {{ $item->customer_email }}
                                </p>

                                {{-- Order Address --}}
                                @foreach ($item->addresses as $address)
                                    @if ($address->address_type == 'order_billing')
                                        <p class="text-gray-600 dark:text-gray-300">
                                            {{ $address->city . ($address->country ? ', ' . core()->country_name($address->country) : '') }}
                                        </p>
                                    @endif
                                @endforeach
                            </div>

                            {{-- Ordered Product Images --}}
                            <div class="max-1580:col-span-full">
                                <div class="flex gap-[6px] items-center justify-between">
                                    <div class="flex gap-[6px] items-center flex-wrap">
                                        {{-- Using Variable for image Numbering --}}
                                        @foreach ($item->items as $index => $orderItem)

                                            @if ($index >= 3 && $item->items->count() >= 5)
                                                @break;
                                            @endif

                                            <div class="relative">
                                                @if ($orderItem->product?->base_image_url)
                                                    <img
                                                        class="min-h-[65px] min-w-[65px] max-h-[65px] max-w-[65px] rounded-[4px]"
                                                        src="{{ $orderItem->product->base_image_url }}"
                                                    />
                                                @else
                                                    <div class="w-full h-[65px] max-w-[65px] max-h-[65px] relative border border-dashed border-gray-300 dark:border-gray-800 rounded-[4px] overflow-hidden dark:invert dark:mix-blend-exclusion">
                                                        <img src="{{ bagisto_asset('images/product-placeholders/front.svg')}}">

                                                        <p class="w-full absolute bottom-[5px] text-[6px] text-gray-400 text-center font-semibold">
                                                            @lang('admin::app.dashboard.index.product-image')
                                                        </p>
                                                    </div>
                                                @endif


                                                <span class="absolute bottom-[1px] ltr:left-[1px] rtl:right-[1px] text-[12px] font-bold text-white bg-darkPink rounded-full px-[6px]">
                                                    {{ $orderItem->product?->images->count() }}
                                                </span>
                                            </div>
                                        @endforeach

                                        {{-- Count of Rest Images --}}
                                        @if (
                                            $item->items->count() - 3 
                                            && $item->items->count() > 4
                                        )
                                            <a href="{{ route('admin.sales.orders.view', $item->id) }}">
                                                <div class="flex items-center w-[65px] h-[65px] bg-gray-50 dark:bg-gray-800 rounded-[4px]">
                                                    <p class="text-[12px] text-gray-600 dark:text-gray-300 text-center font-bold px-[6px] py-[6px]">
                                                        @lang('admin::app.dashboard.index.more-products', ['product_count' => $item->items->count() - 3 ])
                                                    </p>
                                                </div>
                                            </a>
                                        @endif
                                    </div>

                                    {{-- View More Icon --}}
                                    <a href="{{ route('admin.sales.orders.view', $item->id) }}">
                                        <span class="icon-sort-right text-[24px] ltr:ml-[4px] rtl:mr-[4px] p-[6px] cursor-pointer hover:bg-gray-200 dark:hover:bg-gray-800 hover:rounded-[6px]"></span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Stock Thereshold --}}
            <div class="flex flex-col gap-[8px]  ">
                <p class="text-[16px] text-gray-600 dark:text-gray-300 font-semibold">
                    @lang('admin::app.dashboard.index.stock-threshold')
                </p>

                {{-- Products List --}}
                @if(! $statistics['stock_threshold']->isEmpty())
                    <div class="rounded-[4px] box-shadow">
                        @foreach ($statistics['stock_threshold'] as $item)
                            <!-- Single Product -->
                            <div class="relative">
                                <div class="row grid grid-cols-2 gap-y-[24px] p-[16px] bg-white dark:bg-gray-900 border-b-[1px] dark:border-gray-800 transition-all hover:bg-gray-50 dark:hover:bg-gray-950 max-sm:grid-cols-[1fr_auto]">
                                    <div class="flex gap-[10px]">
                                        @if ($item->product?->base_image_url)
                                            <div class="">
                                                <img
                                                    class="min-h-[65px] min-w-[65px] max-h-[65px] max-w-[65px] rounded-[4px]"
                                                    src="{{ $item->product->base_image_url }}"
                                                >
                                            </div>
                                        @else
                                            <div class="w-full h-[65px] max-w-[65px] max-h-[65px] relative border border-dashed border-gray-300 dark:border-gray-800 rounded-[4px] overflow-hidden dark:invert dark:mix-blend-exclusion">
                                                <img src="{{ bagisto_asset('images/product-placeholders/front.svg')}}">

                                                <p class="w-full absolute bottom-[5px] text-[6px] text-gray-400 text-center font-semibold">
                                                    @lang('admin::app.dashboard.index.product-image')
                                                </p>
                                            </div>
                                        @endif

                                        <div class="flex flex-col gap-[6px]">
                                            {{-- Product Name --}}
                                            <p class="text-[16px] text-gray-800 dark:text-white font-semibold">
                                                @if (isset($item->product->name))
                                                    {{ $item->product->name }}
                                                @endif
                                            </p>

                                            {{-- Product SKU --}}
                                            <p class="text-gray-600 dark:text-gray-300">
                                                @lang('admin::app.dashboard.index.sku', ['sku' => $item->product->sku])
                                            </p>

                                            {{-- Product Number --}}
                                            <p class="text-gray-600 dark:text-gray-300">
                                                @if (
                                                    isset($item->product->product_number)
                                                    && ! empty($item->product->product_number)
                                                )
                                                    @lang('admin::app.dashboard.index.product-number', ['product_number' => $item->product->product_number])
                                                @endif
                                            </p>
                                        </div>
                                    </div>

                                    <div class="flex gap-[6px] items-center justify-between">
                                        <div class="flex flex-col gap-[6px]">
                                            {{-- Product Price --}}
                                            <p class="text-[16px] text-gray-800 dark:text-white font-semibold">
                                                @if (isset($item->product->price))
                                                    {{ core()->formatBasePrice($item->product->price) }}
                                                @endif
                                            </p>

                                            {{-- Total Product Stock --}}
                                            <p class="{{ $item->total_qty > 10 ? 'text-emerald-500' : 'text-red-500' }} ">
                                                @lang('admin::app.dashboard.index.total-stock', ['total_stock' => $item->total_qty])
                                            </p>
                                        </div>

                                        {{-- View More Icon --}}
                                        <a href="{{ route('admin.catalog.products.edit', $item->product_id) }}">
                                            <span class="icon-sort-right text-[24px] ltr:ml-[4px] rtl:mr-[4px] p-[6px] cursor-pointer hover:bg-gray-200 dark:hover:bg-gray-800 hover:rounded-[6px]"></span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="rounded-[4px] box-shadow">
                        <div class="grid gap-[14px] justify-center justify-items-center py-[40px] px-[10px] ">
                            <img src="{{ bagisto_asset('images/icon-add-product.svg') }}" class="w-[80px] h-[80px] dark:invert dark:mix-blend-exclusion">
                            <div class="flex flex-col items-center">
                                <p class="text-[16px] text-gray-400 font-semibold">
                                    @lang('admin::app.dashboard.index.empty-threshold')
                                </p>
            
                                <p class="text-gray-400">
                                    @lang('admin::app.dashboard.index.empty-threshold-description')
                                </p>
                            </div>
                        </div>
                    </div>
                @endif    
            </div>
        </div>

        {{-- Right Section --}}
        <div class="flex flex-col gap-[8px] w-[360px] max-w-full   max-sm:w-full">
            {{-- First Component --}}
            <p class="text-[16px] text-gray-600 dark:text-gray-300 font-semibold">
                @lang('admin::app.dashboard.index.store-stats')
            </p>

            {{-- Store Stats --}}
            <v-store-stats>
                <x-admin::shimmer.dashboard.right/>
            </v-store-stats>
        </div>
    </div>
    
    @push('scripts')
        <script type="module" src="{{ bagisto_asset('js/chart.js') }}"></script>

        <script type="text/x-template" id="v-store-stats-template">
            <x-admin::form :action="route('admin.catalog.categories.store')">
                <div class="rounded-[4px] bg-white dark:bg-gray-900 box-shadow">
                    <!-- Total Sales Shimmer -->
                    <template v-if="isLoading">
                        <x-admin::shimmer.dashboard.right.date-filters/>

                        <x-admin::shimmer.dashboard.right.total-sales/>

                        <x-admin::shimmer.dashboard.right.total-sales/>
                    </template>

                    <template v-else>
                        <!-- Date Filter -->
                        <div class="flex gap-[6px] px-[16px] py-[8px] border-b dark:border-gray-800">
                            <!-- Start Date Filter -->
                            <x-admin::form.control-group class="flex-1 mb-[10px]">
                                <x-admin::form.control-group.label class="!text-gray-800 dark:!text-white font-medium">
                                    @lang('admin::app.dashboard.index.start-date')
                                </x-admin::form.control-group.label>
    
                                <x-admin::form.control-group.control
                                    type="date"
                                    name="startDate" 
                                    class="cursor-pointer"
                                    :placeholder="trans('admin::app.dashboard.index.start-date')"
                                    v-model="filters.start"
                                >
                                </x-admin::form.control-group.control>
                            </x-admin::form.control-group>
    
                            <!-- End Date Filter -->
                            <x-admin::form.control-group class="flex-1 mb-[10px]">
                                <x-admin::form.control-group.label class="!text-gray-800 dark:!text-white font-medium">
                                    @lang('admin::app.dashboard.index.end-date')
                                </x-admin::form.control-group.label>
    
                                <x-admin::form.control-group.control
                                    type="date"
                                    name="endDate" 
                                    class="cursor-pointer"
                                    :placeholder="trans('admin::app.dashboard.index.end-date')"
                                    v-model="filters.end"
                                >
                                </x-admin::form.control-group.control>
                            </x-admin::form.control-group>
                        </div>
    
                        <!-- Total Sales Detailes -->
                        <div class="grid gap-[16px] px-[16px] py-[8px] border-b dark:border-gray-800">
                            <div class="flex gap-[8px] justify-between">
                                <div class="flex flex-col gap-[4px] justify-between">
                                    <p class="text-[12px] text-gray-600 dark:text-gray-300 font-semibold">
                                        @lang('admin::app.dashboard.index.total-sales')
                                    </p>
    
                                    <!-- Total Order Revenue -->
                                    <p class="text-[18px] text-gray-800 dark:text-white font-bold">
                                        @{{ statistics.sales.total_sales?.formatted_total }}
                                    </p>
                                </div>
    
                                <div class="flex flex-col gap-[4px] justify-between">
                                    <!-- Orders Time Duration -->
                                    <p class="text-[12px] text-gray-400 font-semibold text-right dark:text-white">
                                        @{{ "@lang('admin::app.dashboard.index.date-duration')".replace(':start', formatStart ?? 0).replace(':end', formatEnd ?? 0) }}
                                    </p>
    
                                    <!-- Total Orders -->
                                    <p class="text-[12px] text-gray-400 font-semibold text-right dark:text-white">
                                        @{{ "@lang('admin::app.dashboard.index.order')".replace(':total_orders', statistics.sales.total_orders?.current ?? 0) }}
                                    </p>
                                </div>
                            </div>
    
                            <!-- Sales Graph -->
                            <canvas
                                id="totalSalesChart"
                                style="width: 100%; height: 230px"
                            >
                            </canvas>
                        </div>
    
                        <!-- Total Visitors Detailes -->
                        <div class="grid gap-[16px] px-[16px] py-[8px] border-b dark:border-gray-800">
                            <div class="flex gap-[8px] justify-between">
                                <div class="flex flex-col gap-[4px] justify-between">
                                    <p class="text-[12px] text-gray-600 dark:text-gray-300 font-semibold">
                                        @lang('admin::app.dashboard.index.visitors')
                                    </p>
    
                                    <!-- Total Order Revenue -->
                                    <p class="text-[18px] text-gray-800 dark:text-white font-bold">
                                        @{{ statistics.visitors.total.current }}
                                    </p>
                                </div>
    
                                <div class="flex flex-col gap-[4px] justify-between">
                                    <!-- Orders Time Duration -->
                                    <p class="text-[12px] text-gray-400 font-semibold text-right dark:text-white">
                                        @{{ "@lang('admin::app.dashboard.index.date-duration')".replace(':start', formatStart ?? 0).replace(':end', formatEnd ?? 0) }}
                                    </p>
    
                                    <!-- Total Orders -->
                                    <p class="text-[12px] text-gray-400 font-semibold text-right dark:text-white">
                                        @{{ "@lang('admin::app.dashboard.index.unique-visitors')".replace(':count', statistics.visitors.unique?.current ?? 0) }}
                                    </p>
                                </div>
                            </div>
    
                            <!-- Visitors Graph -->
                            <canvas
                                id="totalVisitorsChart"
                                style="width: 100%; height: 230px"
                            >
                            </canvas>
                        </div>
                    </template>

                    <!-- Top Selling Products -->
                    <div class="border-b dark:border-gray-800">
                        <div class="flex items-center justify-between p-[16px]">
                            <p class="text-gray-600 dark:text-gray-300 text-[16px] font-semibold">
                                @lang('admin::app.dashboard.index.top-selling-products')
                            </p>

                            <p class="text-[12px] text-gray-400 font-semibold">
                                @{{ "@lang('admin::app.dashboard.index.date-duration')".replace(':start', formatStart ?? 0).replace(':end', formatEnd ?? 0) }}
                            </p>
                        </div>

                        <!-- Top Selling Products Shimmer -->
                        <template v-if="isLoading">
                            <x-admin::shimmer.dashboard.right.top-selling/>
                        </template>

                        <!-- Top Selling Products Detailes -->
                        <template v-else>
                            <div
                                class="flex flex-col"
                                v-if="statistics?.products.length"
                            >
                                <a
                                    :href="`{{route('admin.catalog.products.edit', '')}}/${item.product_id}`"
                                    class="flex gap-[10px] p-[16px] border-b-[1px] dark:border-gray-800 last:border-b-0 transition-all hover:bg-gray-50 dark:hover:bg-gray-950"
                                    v-for="item in statistics.products"
                                >
                                    <!-- Product Item -->
                                    <img
                                        v-if="item?.product?.images.length"
                                        class="w-full h-[65px] max-w-[65px] max-h-[65px] relative rounded-[4px] overflow-hidden"
                                        :src="item?.product?.images[0]?.url"
                                    />

                                    <div
                                        v-else
                                        class="w-full h-[65px] max-w-[65px] max-h-[65px] relative border border-dashed border-gray-300 dark:border-gray-800 rounded-[4px] overflow-hidden dark:invert dark:mix-blend-exclusion"
                                    >
                                        <img src="{{ bagisto_asset('images/product-placeholders/front.svg')}}">
                                        
                                        <p class="w-full absolute bottom-[5px] text-[6px] text-gray-400 text-center font-semibold">
                                            @lang('admin::app.dashboard.index.product-image')
                                        </p>
                                    </div>

                                    <!-- Product Detailes -->
                                    <div class="flex flex-col gap-[6px] w-full">
                                        <p
                                            class="text-gray-600 dark:text-gray-300"
                                            v-text="item.name"
                                        >
                                        </p>

                                        <div class="flex justify-between">
                                            <p
                                                class="text-gray-600 dark:text-gray-300 font-semibold"
                                                v-text="item.formatted_price"
                                            >
                                            </p>

                                            <p
                                                class="text-[16px] text-gray-800 dark:text-white font-semibold"
                                                v-text="item.formatted_revenue"
                                            >
                                            </p>
                                        </div>
                                    </div>
                                </a>
                            </div>

                            <!-- Empty Product Design -->
                            <div
                                class="flex flex-col gap-[32px] p-[16px]"
                                v-else
                            >
                                <div class="grid gap-[14px] justify-center justify-items-center py-[10px]">
                                    <!-- Placeholder Image -->
                                    <img
                                        src="{{ bagisto_asset('images/icon-add-product.svg') }}"
                                        class="w-[80px] h-[80px] dark:invert dark:mix-blend-exclusion"
                                    >
    
                                    <!-- Add Variants Information -->
                                    <div class="flex flex-col items-center">
                                        <p class="text-[16px] text-gray-400 font-semibold">
                                            @lang('admin::app.dashboard.index.add-product')
                                        </p>
    
                                        <p class="text-gray-400">
                                            @lang('admin::app.dashboard.index.product-info')
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>

                    <!-- Top Customers -->
                    <div class="flex items-center justify-between p-[16px]">
                        <p class="text-gray-600 dark:text-gray-300 text-[16px] font-semibold">
                            @lang('admin::app.dashboard.index.customer-with-most-sales')
                        </p>

                        <p class="text-[12px] text-gray-400 font-semibold">
                            @{{ "@lang('admin::app.dashboard.index.date-duration')".replace(':start', formatStart ?? 0).replace(':end', formatEnd ?? 0) }}
                        </p>
                    </div>

                    <!-- Customers Shimmer -->
                    <template v-if="isLoading">
                        <x-admin::shimmer.dashboard.right.most-customer-sales/>
                    </template>

                    <template v-else>
                        <!-- Customers Lists -->
                        <div
                            class="flex flex-col gap-[32px] p-[16px] border-b-[1px] dark:border-gray-800 last:border-b-0 transition-all hover:bg-gray-50 dark:hover:bg-gray-950"
                            v-if="statistics?.customers.length"
                            v-for="customer in statistics.customers"
                        >
                            <a :href="customer.id ? `{{ route('admin.customers.customers.view', '') }}/${customer.id}` : '#'">
                                <div class="flex justify-between gap-[6px]">
                                    <div class="flex flex-col">
                                        <p
                                            class="text-gray-600 dark:text-gray-300 font-semibold"
                                            v-text="customer.full_name"
                                        >
                                        </p>

                                        <p
                                            class="text-gray-600 dark:text-gray-300"
                                            v-text="customer.email"
                                        >
                                        </p>
                                    </div>

                                    <div class="flex flex-col">
                                        <p
                                            class="text-gray-800 font-semibold dark:text-white"
                                            v-text="customer.formatted_total"
                                        >
                                        </p>

                                        <p class="text-gray-600 dark:text-gray-300" v-if="customer.orders">
                                            @{{ "@lang('admin::app.dashboard.index.order-count')".replace(':count', customer.orders) }}
                                        </p>
                                    </div>
                                </div>
                            </a>
                        </div>

                        <div
                            class="flex flex-col gap-[32px] p-[16px]"
                            v-else
                        >
                            <div class="grid gap-[14px] justify-center justify-items-center py-[10px]">
                                <!-- Placeholder Image -->
                                <img
                                    src="{{ bagisto_asset('images/icon-add-product.svg') }}"
                                    class="w-[80px] h-[80px] dark:invert dark:mix-blend-exclusion"
                                />

                                <!-- Add Variants Information -->
                                <div class="flex flex-col items-center">
                                    <p class="text-[16px] text-gray-400 font-semibold">
                                        @lang('admin::app.dashboard.index.add-customer')
                                    </p>

                                    <p class="text-gray-400">
                                        @lang('admin::app.dashboard.index.customer-info')
                                    </p>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
            </x-admin::form>
        </script>

        <script type="module">
            app.component('v-store-stats', {
                template: '#v-store-stats-template',

                data() {
                    return {
                        isLoading: true,

                        filters: {
                            start: "{{ $startDate->format('Y-m-d') }}",
                            end: "{{ $endDate->format('Y-m-d') }}",
                        },

                        formatStart: "",

                        formatEnd: "",

                        statistics: {},

                        salesChart: undefined,

                        visitorsChart: undefined,
                    }
                },

                mounted() {
                   this.get();
                },

                methods: {
                    get() {
                        this.$axios.get("{{ route('admin.dashboard.index') }}", {
                                params: this.filters
                            })
                            .then((response) => {
                                const { startDate, endDate, statistics } = response.data;

                                this.formatStart = startDate;

                                this.formatEnd = endDate;

                                this.statistics = statistics;

                                setTimeout(() => {
                                    this.salesGraphChart();

                                    this.visitorsGraphChart();
                                }, 0);

                                this.isLoading = false;
                            })
                            .catch(error => {
                                if (error.response.status == 422) {
                                    setErrors(error.response.data.errors);
                                }
                            });
                    },

                    salesGraphChart () {
                        const ctx = document.getElementById('totalSalesChart');

                        const data = this.statistics.sales.over_time;

                        if (this.salesChart) {
                           this.salesChart.destroy();
                        }

                        this.salesChart = new Chart(ctx, {
                            type: 'bar',
                            
                            data: {
                                labels: data.map(({ label }) => label),
                                datasets: [{
                                    data: data.map(({ total }) => total),
                                    barThickness: 6,
                                    backgroundColor: '#598de6',
                                }]
                            },
                    
                            options: {
                                plugins: {
                                    legend: {
                                        display: false
                                    }
                                },

                                scales: {
                                    x: {
                                        grid: {
                                            display: false
                                        },
                                    },
                                }
                            }
                        });
                    },

                    visitorsGraphChart () {
                        const ctx = document.getElementById('totalVisitorsChart');

                        const data = this.statistics.visitors.over_time;

                        if (this.visitorsChart) {
                           this.visitorsChart.destroy();
                        }

                        this.visitorsChart = new Chart(ctx, {
                            type: 'bar',
                            
                            data: {
                                labels: data.map(({ label }) => label),
                                datasets: [{
                                    data: data.map(({ total }) => total),
                                    barThickness: 6,
                                    backgroundColor: '#f87171',
                                }]
                            },
                    
                            options: {
                                plugins: {
                                    legend: {
                                        display: false
                                    }
                                },

                                scales: {
                                    x: {
                                        grid: {
                                            display: false
                                        },
                                    },
                                }
                            }
                        });
                    },
                },

                watch: {
                    filters: {
                        handler() {
                            this.get();
                        },

                        deep: true
                    }
                }
            });
        </script>   
    @endpush
</x-admin::layouts>
