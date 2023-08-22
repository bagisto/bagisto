@php
    $admin = auth()->guard('admin')->user();
@endphp

<x-admin::layouts>
    {{-- User Detailes Section --}}
    <div class="flex gap-[16px] justify-between items-center mb-[20px] max-sm:flex-wrap">
        <div class="grid gap-[6px]">
            <p class="pt-[6px] text-[20px] text-gray-800 font-bold leading-[24px]">
                @lang('admin::app.dashboard.user-name', ['user_name' => $admin->name])
            </p>

            <p class="text-gray-600">
                @lang('admin::app.dashboard.user-info')
            </p>
        </div>

        <span class="icon-settings p-[6px] rounded-[6px] text-[24px]cursor-pointer transition-all hover:bg-gray-100"></span>
    </div>

    {{-- Body Component --}}
    <div class="flex gap-[10px] mt-[14px] max-xl:flex-wrap">
        {{-- Left Component --}}
        <div class=" flex flex-col gap-[30px] flex-1 max-xl:flex-auto">
            {{-- Overall Detailes --}}
            <div class="flex flex-col gap-[8px] ">
                <p class="text-[16px] text-gray-600 font-semibold">
                    @lang('admin::app.dashboard.overall-details')
                </p>

                <div class="p-[16px] border-[1px] border-gray-300 bg-white rounded-[4px] box-shadow">
                    <div class="flex gap-[16px] flex-wrap ">
                        {{-- Total Sales --}}
                        <div class="flex gap-[10px] flex-1">
                            <div class="w-full h-[60px] max-w-[60px] max-h-[60px] relative border border-dashed border-gray-300 rounded-[4px] overflow-hidden">
                                <img src="{{ bagisto_asset('images/product-placeholders/front.svg')}}">

                                <p class="w-full absolute bottom-[5px] text-[6px] text-gray-400 text-center font-semibold">
                                    @lang('admin::app.dashboard.total-sales')
                                </p>
                            </div>

                            {{-- Sales Stats --}}
                            <div class="grid gap-[4px] place-content-start">
                                <p class="text-[16px] text-gray-800 font-semibold">
                                    {{ core()->formatBasePrice($statistics['total_sales']['current']) }}
                                </p>

                                <p class="text-[12px] text-gray-600 font-semibold">
                                    @lang('admin::app.dashboard.total-sales')
                                </p>

                                {{-- Sales Percentage --}}
                                <div class="flex gap-[2px] items-center">
                                    @if ($statistics['total_sales']['progress'] < 0)
                                        <span class="icon-down-stat text-[16px] text-red-500"></span>

                                        <p class="text-[12px] text-red-500 font-semibold">
                                            @lang('admin::app.dashboard.decreased', [
                                                'progress' => number_format($statistics['total_sales']['progress'], 1),
                                            ])
                                        </p>
                                    @else
                                        <span class="icon-up-stat text-[16px] text-emerald-500"></span>

                                        <p class="text-[12px] text-emerald-500 font-semibold">
                                            @lang('admin::app.dashboard.increased', [
                                                'progress' => number_format($statistics['total_sales']['progress'], 1),
                                            ])
                                        </p>
                                    @endif
                                </div>
                            </div>
                        </div>

                        {{-- Total Orders --}}
                        <div class="flex gap-[10px] flex-1">
                            <div class="w-full h-[60px] max-w-[60px] max-h-[60px] relative border border-dashed border-gray-300 rounded-[4px] overflow-hidden">
                                <img src="{{ bagisto_asset('images/product-placeholders/front.svg')}}">

                                <p class="w-full absolute bottom-[5px] text-[6px] text-gray-400 text-center font-semibold">
                                    @lang('admin::app.dashboard.total-orders')
                                </p>
                            </div>

                            {{-- Orders Stats --}}
                            <div class="grid gap-[4px] place-content-start">
                                <p class="text-[16px] text-gray-800 font-semibold">
                                    {{ $statistics['total_orders']['current'] }}
                                </p>

                                <p class="text-[12px] text-gray-600 font-semibold">
                                    @lang('admin::app.dashboard.total-orders')
                                </p>

                                {{-- Order Percentage --}}
                                <div class="flex gap-[2px] items-center">
                                    @if ($statistics['total_orders']['progress'] < 0)
                                        <span class="icon-down-stat text-[16px] text-red-500"></span>

                                        <p class="text-[12px] text-red-500 font-semibold">
                                            @lang('admin::app.dashboard.decreased', [
                                                'progress' => number_format($statistics['total_orders']['progress'], 1),
                                            ])
                                        </p>
                                    @else
                                        <span class="icon-up-stat text-[16px] text-emerald-500"></span>

                                        <p class="text-[12px] text-emerald-500 font-semibold">
                                            @lang('admin::app.dashboard.increased', [
                                                'progress' => number_format($statistics['total_orders']['progress'], 1),
                                            ])
                                        </p>
                                    @endif
                                </div>
                            </div>
                        </div>

                        {{-- Total Customers --}}
                        <div class="flex gap-[10px] flex-1">
                            <div class="w-full h-[60px] max-w-[60px] max-h-[60px] relative border border-dashed border-gray-300 rounded-[4px] overflow-hidden">
                                <img src="{{ bagisto_asset('images/product-placeholders/front.svg')}}">

                                <p class="w-full absolute bottom-[5px] text-[6px] text-gray-400 text-center font-semibold">
                                    @lang('admin::app.dashboard.total-customers')
                                </p>
                            </div>

                            {{-- Customers Stats --}}
                            <div class="grid gap-[4px] place-content-start">
                                <p class="text-[16px] text-gray-800 font-semibold">
                                    {{ $statistics['total_customers']['current'] }}
                                </p>

                                <p class="text-[12px] text-gray-600 font-semibold">
                                    @lang('admin::app.dashboard.total-customers')
                                </p>

                                {{-- Customers Percentage --}}
                                <div class="flex gap-[2px] items-center">
                                    @if ($statistics['total_customers']['progress'] < 0)
                                        <span class="icon-down-stat text-[16px] text-red-500"></span>

                                        <p class="text-[12px] text-red-500 font-semibold">
                                            @lang('admin::app.dashboard.decreased', [
                                                    'progress' => number_format($statistics['total_customers']['progress'], 1)
                                            ])
                                        </p>
                                    @else
                                        <span class="icon-up-stat text-[16px] text-emerald-500"></span>

                                        <p class="text-[12px] text-emerald-500 font-semibold"
                                            @lang('admin::app.dashboard.increased', [
                                                'progress' => number_format($statistics['total_customers']['progress'], 1)
                                            ])
                                        </p>
                                    @endif
                                </div>
                            </div>
                        </div>

                        {{-- Average sales --}}
                        <div class="flex gap-[10px] flex-1">
                            <div class="w-full h-[60px] max-w-[60px] max-h-[60px] relative border border-dashed border-gray-300 rounded-[4px] overflow-hidden">
                                <img src="{{ bagisto_asset('images/product-placeholders/front.svg')}}">

                                <p class="w-full absolute bottom-[5px] text-[6px] text-gray-400 text-center font-semibold">
                                    @lang('Average Sales')
                                </p>
                            </div>

                            {{-- Sales Stats --}}
                            <div class="grid gap-[4px] place-content-start">
                                <p class="text-[16px] text-gray-800 font-semibold">
                                    {{ core()->formatBasePrice($statistics['avg_sales']['current']) }}
                                </p>

                                <p class="text-[12px] text-gray-600 font-semibold">
                                    @lang('admin::app.dashboard.average-sale')
                                </p>

                                {{-- Sales Percentage --}}
                                <div class="flex gap-[2px] items-center">
                                    @if ($statistics['avg_sales']['progress'] < 0)
                                        <span class="icon-down-stat text-[16px] text-red-500"></span>

                                        <p class="text-[12px] text-red-500 font-semibold">
                                            @lang('admin::app.dashboard.decreased', [
                                                'progress' => number_format($statistics['avg_sales']['progress'], 1),
                                            ])
                                        </p>
                                    @else
                                        <span class="icon-up-stat text-[16px] text-emerald-500"></span>
                                        <p class="text-[12px] text-emerald-500 font-semibold">
                                            @lang('admin::app.dashboard.increased', [
                                                'progress' => number_format($statistics['avg_sales']['progress'], 1),
                                            ])
                                        </p>
                                    @endif
                                </div>
                            </div>
                        </div>

                        {{-- Unpaid Invoices --}}
                        <div class="flex gap-[10px] flex-1">
                            <div class="w-full h-[60px] max-w-[60px] max-h-[60px] relative border border-dashed border-gray-300 rounded-[4px] overflow-hidden">
                                <img src="{{ bagisto_asset('images/product-placeholders/front.svg')}}">

                                <p class="w-full absolute bottom-[5px] text-[6px] text-gray-400 text-center font-semibold">
                                    @lang('Invoices')
                                </p>
                            </div>

                            <div class="grid gap-[4px] place-content-start">
                                <p class="text-[16px] text-gray-800 font-semibold">
                                    {{ core()->formatBasePrice($statistics['total_unpaid_invoices']) }}
                                </p>

                                <p class="text-[12px] text-gray-600 font-semibold">
                                    @lang('admin::app.dashboard.total-unpaid-invoices')
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Todays Deatiles --}}
            <div class="flex flex-col gap-[8px]">
                <p class="text-[16px] text-gray-600 font-semibold">
                    @lang('admin::app.dashboard.today-details')
                </p>

                <div class="border-[1px] border-gray-300 bg-white rounded-[4px] box-shadow">
                    <div class="flex gap-[16px] flex-wrap p-[16px] border-b-[1px] border-gray-300">
                        {{-- Today's Sales --}}
                        <div class="flex gap-[10px] flex-1">
                            <div class="w-full h-[60px] max-w-[60px] max-h-[60px] relative border border-dashed border-gray-300 rounded-[4px] overflow-hidden">
                                <img src="{{ bagisto_asset('images/product-placeholders/front.svg')}}">

                                <p class="w-full absolute bottom-[5px] text-[6px] text-gray-400 text-center font-semibold">
                                    @lang('admin::app.dashboard.product-image')
                                </p>
                            </div>

                            {{-- Sales Stats --}}
                            <div class="grid gap-[4px] place-content-start">
                                <p class="text-[16px] text-gray-800 font-semibold">
                                    {{ core()->formatBasePrice($statistics['today_details']['today_sales']['current']) }}
                                </p>

                                <p class="text-[12px] text-gray-600 font-semibold">
                                    @lang('admin::app.dashboard.today-sales')
                                </p>

                                {{-- Percentage Of Sales --}}
                                <div class="flex gap-[2px] items-center">
                                    @if ($statistics['today_details']['today_sales']['progress'] < 0)
                                        <span class="icon-down-stat text-[16px] text-red-500"></span>

                                        <p class="text-[12px] text-red-500 font-semibold">
                                            @lang('admin::app.dashboard.decreased', [
                                                'progress' => number_format($statistics['today_details']['today_sales']['progress'], 1),
                                            ])
                                        </p>
                                    @else
                                        <span class="icon-up-stat text-[16px] text-emerald-500"></span>

                                        <p class="text-[12px] text-emerald-500 font-semibold">
                                            @lang('admin::app.dashboard.increased', [
                                                'progress' => number_format($statistics['today_details']['today_sales']['progress'], 1),
                                            ])
                                        </p>
                                    @endif
                                </div>
                            </div>
                        </div>

                        {{-- Today's Orders --}}
                        <div class="flex gap-[10px] flex-1">
                            <div class="w-full h-[60px] max-w-[60px] max-h-[60px] relative border border-dashed border-gray-300 rounded-[4px] overflow-hidden">
                                <img src="{{ bagisto_asset('images/product-placeholders/front.svg')}}">

                                <p class="w-full absolute bottom-[5px] text-[6px] text-gray-400 text-center font-semibold">
                                    @lang('admin::app.dashboard.product-image')
                                </p>
                            </div>

                            {{-- Orders Stats --}}
                            <div class="grid gap-[4px] place-content-start">
                                <p class="text-[16px] text-gray-800 font-semibold">
                                    {{ count($statistics['today_details']['today_orders']['current']) }}
                                </p>

                                <p class="text-[12px] text-gray-600 font-semibold">
                                    @lang('admin::app.dashboard.today-orders')
                                </p>

                                {{-- Orders Percentage --}}
                                <div class="flex gap-[2px] items-center">
                                    @if ($statistics['today_details']['today_orders']['progress'] < 0)
                                        <span class="icon-down-stat text-[16px] text-red-500"></span>

                                        <p class="text-[12px] text-red-500 font-semibold">
                                            @lang('admin::app.dashboard.decreased', [
                                                'progress' => number_format($statistics['today_details']['today_orders']['progress'], 1),
                                            ])
                                        </p>
                                    @else
                                        <span class="icon-up-stat text-[16px] text-emerald-500"></span>

                                        <p class="text-[12px] text-emerald-500 font-semibold">
                                            @lang('admin::app.dashboard.increased', [
                                                'progress' => number_format($statistics['today_details']['today_orders']['progress'], 1),
                                            ])
                                        </p>
                                    @endif
                                </div>
                            </div>
                        </div>

                        {{-- Today's Customers --}}
                        <div class="flex gap-[10px] flex-1">
                            <div class="w-full h-[60px] max-w-[60px] max-h-[60px] relative border border-dashed border-gray-300 rounded-[4px] overflow-hidden">
                                <img src="{{ bagisto_asset('images/product-placeholders/front.svg')}}">

                                <p class="w-full absolute bottom-[5px] text-[6px] text-gray-400 text-center font-semibold">
                                    @lang('admin::app.dashboard.product-image')
                                </p>
                            </div>

                            {{-- Customers Stats --}}
                            <div class="grid gap-[4px] place-content-start">
                                <p class="text-[16px] text-gray-800 font-semibold">
                                    {{ $statistics['today_details']['today_customers']['current'] }}
                                </p>

                                <p class="text-[12px] text-gray-600 font-semibold">
                                    @lang('admin::app.dashboard.today-customers')
                                </p>

                                {{-- Customers Percentage --}}
                                <div class="flex gap-[2px] items-center">
                                    @if ($statistics['today_details']['today_customers']['progress'] < 0)
                                        <span class="icon-down-stat text-[16px] text-red-500"></span>

                                        <p class="text-[12px] text-red-500 font-semibold">
                                            @lang('admin::app.dashboard.decreased', [
                                                'progress' => number_format($statistics['today_details']['today_customers']['progress'], 1),
                                            ])
                                        </p>
                                    @else
                                        <span class="icon-up-stat text-[16px] text-emerald-500"></span>

                                        <p class="text-[12px] text-emerald-500 font-semibold">
                                            @lang('admin::app.dashboard.increased', [
                                                'progress' => number_format($statistics['today_details']['today_customers']['progress'], 1),
                                            ])
                                        </p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Today Orders Detailes -->
                    @foreach ($statistics['today_details']['today_orders']['current'] as $item)
                        <div class="row grid grid-cols-4  gap-y-[24px] p-[16px] border-b-[1px] border-gray-300 max-1580:grid-cols-3 max-sm:grid-cols-1">
                            <div class="">
                                {{-- Order ID, Status, Created --}}
                                <div class="flex gap-[10px]">
                                    <div class="flex flex-col gap-[6px]">
                                        <p class="text-[16px] text-gray-800 font-semibold">
                                            #{{ $item->id}}
                                        </p>

                                        <p class="text-gray-600">
                                            {{ $item->created_at}}
                                        </p>

                                        @switch($item->status)
                                            @case('processing')
                                                <p class="label-active">
                                                    {{ $item->status }}
                                                </p>
                                                @break

                                            @case('completed')
                                                <p class="label-active">
                                                    {{ $item->status }}
                                                </p>
                                                @break

                                            @case('pending')
                                                <p class="label-pending">
                                                    {{ $item->status }}
                                                </p>
                                                @break

                                            @case('canceled')
                                                <p class="label-cancelled">
                                                    {{ $item->status }}
                                                </p>
                                                @break

                                            @case('closed')
                                                <p class="label-closed">
                                                    {{ $item->status }}
                                                </p>
                                                @break

                                        @endswitch
                                    </div>
                                </div>
                            </div>
        
                            <div class="">
                                {{-- Payment And Channel Detailes --}}
                                <div class="flex flex-col gap-[6px]">
                                    <p class="text-[16px] text-gray-800 font-semibold">
                                        {{ core()->formatBasePrice($item->grand_total)}}
                                    </p>

                                    <p class="text-gray-600">
                                        @lang('admin::app.dashboard.pay-by', ['method' => $item->payment->method])
                                    </p>

                                    <p class="text-gray-600">
                                        {{ $item->channel_name }}
                                    </p>
                                </div>
                            </div>

                            <div class="">
                                <div class="flex flex-col gap-[6px]">
                                    {{-- Customer Detailes --}}
                                    <p class="text-[16px] text-gray-800">
                                        {{ $item->customer_first_name }} {{ $item->customer_last_name }}
                                    </p>

                                    <p class="text-gray-600">
                                        {{ $item->customer_email }}
                                    </p>

                                    {{-- Order Address --}}
                                    @foreach ($item->addresses as $address)
                                        @if ($address->address_type == 'order_billing')
                                            <p class="text-gray-600">
                                                @if (isset($address->country)) 
                                                    {{ $address->city }},
                                                @else 
                                                    {{ $address->city }}
                                                @endif

                                                {{ core()->country_name($address->country) }}
                                            </p>
                                        @endif
                                    @endforeach
                                </div>
                            </div>

                            {{-- Ordered Product Images --}}
                            <div class="max-1580:col-span-full">
                                <div class="flex gap-[6px] items-center justify-between">
                                    <div class="flex gap-[6px] items-center flex-wrap">
                                        {{-- Using Variable for image Numbering --}}
                                        @foreach ($item->items as $index => $orderItem)

                                            @if ($index >= 2 && count($item->items) >= 5)
                                                @break;
                                            @endif

                                            <div class="relative">
                                                @if ($orderItem->product->base_image_url)
                                                    <img
                                                        class="min-h-[65px] min-w-[65px] max-h-[65px] max-w-[65px] rounded-[4px]"
                                                        src="{{ $orderItem->product->base_image_url }}"
                                                    />
                                                @else
                                                    <div class="w-full h-[60px] max-w-[60px] max-h-[60px] relative border border-dashed border-gray-300 rounded-[4px] overflow-hidden">
                                                        <img src="{{ bagisto_asset('images/product-placeholders/front.svg')}}">

                                                        <p class="w-full absolute bottom-[5px] text-[6px] text-gray-400 text-center font-semibold">
                                                            @lang('admin::app.dashboard.product-image')
                                                        </p>
                                                    </div>
                                                @endif

                                                <span class="absolute bottom-[1px] left-[1px] text-[12px] font-bold text-white bg-darkPink rounded-full px-[6px]">
                                                    {{ count($orderItem->product->images) }}
                                                </span>
                                            </div>
                                        @endforeach

                                        {{-- Count of Rest Images --}}
                                        @if (count($item->items) - 2 && count($item->items) > 4)
                                            <div class="flex items-center w-[65px] h-[65px] bg-gray-50 rounded-[4px]">
                                                <p class="text-[12px] text-gray-600 text-center font-bold px-[6px] py-[6px]">
                                                    @lang('admin::app.dashboard.more-products', ['product_count' => count($item->items) - 2 ])
                                                </p>
                                            </div>
                                        @endif
                                    </div>

                                    {{-- View More Icon --}}
                                    <a href="{{ route('admin.sales.orders.view', $item->id) }}">
                                        <span class="icon-sort-right text-[24px] ml-[4px] cursor-pointer hover:bg-gray-100 hover:rounded-[6px]"></span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Stock Thereshold --}}
            <div class="flex flex-col gap-[8px]">
                <p class="text-[16px] text-gray-600 font-semibold">
                    @lang('admin::app.dashboard.stock-threshold')
                </p>

                <div class="border-[1px] border-gray-300 bg-white rounded-[4px] box-shadow">
                    @foreach ($statistics['stock_threshold'] as $item)
                        <!-- single row -->
                        <div class="relative">
                            <div class="row grid grid-cols-2  gap-y-[24px] p-[16px] border-b-[1px] border-gray-300 max-sm:grid-cols-[1fr_auto]">
                                <div class="flex gap-[10px]">
                                    @if ($item->product->base_image_url)
                                        <div class="">
                                            <img
                                                class="min-h-[65px] min-w-[65px] max-h-[65px] max-w-[65px] border border-dashed border-gray-300 rounded-[4px]"
                                                src="{{ $item->product->base_image_url }}"
                                            >
                                        </div>
                                    @else
                                        <div class="w-full h-[65px] max-w-[65px] max-h-[65px] relative border border-dashed border-gray-300 rounded-[4px] overflow-hidden">
                                            <img src="{{ bagisto_asset('images/product-placeholders/front.svg')}}">

                                            <p class="w-full absolute bottom-[5px] text-[6px] text-gray-400 text-center font-semibold">
                                                @lang('admin::app.dashboard.product-image')
                                            </p>
                                        </div>
                                    @endif

                                    <div class="flex flex-col gap-[6px]">
                                        {{-- Product Name --}}
                                        <p class="text-[16px] text-gray-800 font-semibold">
                                            @if (isset($item->product->name))
                                                {{ $item->product->name }}
                                            @endif
                                        </p>

                                        {{-- Product SKU --}}
                                        <p class="text-gray-600">
                                            @lang('admin::app.dashboard.sku', ['sku' => $item->product->sku])
                                        </p>

                                    {{-- Product Number --}}
                                        <p class="text-gray-600">
                                            @if (
                                                isset($item->product->product_number)
                                                && ! empty($item->product->product_number)
                                            )
                                                @lang('admin::app.dashboard.product-number', ['product_number' => $item->product->product_number])
                                            @endif
                                        </p>
                                    </div>
                                </div>

                                <div class="flex gap-[6px] items-center justify-between">
                                    <div class="flex flex-col gap-[6px]">
                                        {{-- Product Price --}}
                                        <p class="text-[16px] text-gray-800 font-semibold">
                                            @if (isset($item->product->price))
                                                {{ core()->formatBasePrice($item->product->price) }}
                                            @endif
                                        </p>

                                        {{-- Total Product Stock --}}
                                        <p class="{{ $item->total_qty > 10 ? 'text-emerald-500' : 'text-red-500' }} ">
                                            @lang('admin::app.dashboard.total-stock', ['total_stock' => $item->total_qty])
                                        </p>
                                    </div>

                                    {{-- View More Icon --}}
                                    <a href="{{ route('admin.catalog.products.edit', $item->product_id) }}">
                                        <span class="icon-sort-right text-[24px] ml-[4px] cursor-pointer hover:bg-gray-100 hover:rounded-[6px]"></span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Right Component --}}
        <div class="flex flex-col gap-[8px] w-[360px] max-w-full max-sm:w-full">
            {{-- First Component --}}
            <p class="text-[16px] text-gray-600 font-semibold">
                @lang('admin::app.dashboard.store-stats')
            </p>

            {{-- Store Stats --}}
            <date-filter></date-filter>
        </div>
    </div>
    
    @push('scripts')
        <script src="{{ bagisto_asset('js/chart.js') }}"></script>

        <script type="text/x-template" id="date-filter-template">
            <div>
                <x-admin::form
                    :action="route('admin.catalog.categories.store')"
                    enctype="multipart/form-data"
                >
                    <div class="bg-white rounded-[4px] box-shadow border-[1px] border-gray-300 box-shadow">
                        <div class="flex gap-[6px] px-[16px] py-[8px] border-b border-gray-300">
                            <div class="flex-1 ">
                                <x-admin::form.control-group class="mb-[10px]">
                                    <x-admin::form.control-group.label class="!text-gray-800 font-medium">
                                        @lang('Start Date')
                                    </x-admin::form.control-group.label>

                                    <x-admin::form.control-group.control
                                        type="date"
                                        name="startDate" 
                                        class="cursor-pointer"
                                        :value="$startDate->format('Y-m-d')"
                                        :label="trans('Start Date')"
                                        :placeholder="trans('Start Date')"
                                        @change="applyFilter('start', $event)"
                                    >
                                    </x-admin::form.control-group.control>
                                </x-admin::form.control-group>
                            </div>

                            <div class="flex-1">
                                <x-admin::form.control-group class="mb-[10px]">
                                    <x-admin::form.control-group.label class="!text-gray-800 font-medium">
                                        @lang('End Date')
                                    </x-admin::form.control-group.label>

                                    <x-admin::form.control-group.control
                                        type="date"
                                        name="endDate" 
                                        class="cursor-pointer"
                                        :value="$endDate->format('Y-m-d')"
                                        :label="trans('admin::app.catalog.categories.create.display-mode')"
                                        :placeholder="trans('End Date')"
                                        @change="applyFilter('end', $event)"
                                    >
                                    </x-admin::form.control-group.control>

                                </x-admin::form.control-group>
                            </div>
                        </div>

                        <!-- sale -->
                        <div class="grid gap-[16px] px-[16px] py-[8px] border-b border-gray-300">
                            <div class="flex gap-[8px] justify-between">
                                <div class="flex flex-col gap-[4px] justify-between">
                                    <p class="text-[12px] text-gray-600 font-semibold">
                                        @lang('admin::app.dashboard.total-sales')
                                    </p>

                                    {{-- Total Order Revenue --}}
                                    <p class="text-[18px] text-gray-800 font-bold">
                                        {{ core()->formatBasePrice($statistics['total_sales']['current']) }}
                                    </p>

                                    <canvas id="myChart"></canvas>

                                </div>

                                <div class="flex flex-col gap-[4px]justify-between">
                                    {{-- Orders Time Duration --}}
                                    <p class="text-[12px] text-gray-400 font-semibold">
                                        Apr 1-30
                                    </p>

                                    {{-- Total Orders --}}
                                    <p class="text-[12px] text-gray-400 font-semibold">
                                        {{-- @lang('admin::app.dashboard.order', ['total_orders' => $statistics['total_orders']['current']]) --}}
                                        @{{ statistics.total_orders?.current }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Top Products -->
                        <div class="border-b border-gray-300">
                            <div class="flex items-center justify-between p-[16px] pb-0">
                                <p class="text-gray-600 text-[16px] font-semibold">
                                    @lang('admin::app.dashboard.top-selling-products')
                                </p>

                                <p class="text-[12px] text-gray-400 font-semibold">
                                    Apr 1-30
                                </p>
                            </div>

                            <div class="flex flex-col gap-[32px] p-[16px]">
                                <a
                                    v-for="item in statistics.top_selling_products"
                                    :href="`{{route('admin.catalog.products.edit', '')}}/${item.product_id}`"
                                >
                                    <div class="flex gap-[10px]">

                                        <img
                                        />
                                        <div class="w-full h-[65px] max-w-[65px] max-h-[65px] relative border border-dashed border-gray-300 rounded-[4px] overflow-hidden">
                                            <img src="{{ bagisto_asset('images/product-placeholders/front.svg')}}">
                                            <p class="w-full absolute bottom-[5px] text-[6px] text-gray-400 text-center font-semibold">
                                                @lang('admin::app.dashboard.product-image')
                                            </p>
                                        </div>

                                        <div class="flex flex-col gap-[6px] w-full">
                                            <p class="text-gray-600" v-text="item.name">
                                            </p>

                                            <div class="flex justify-between">
                                                <p class="text-gray-600 font-semibold" v-text="item.price">
                                                </p>

                                                <p class="text-[16px] text-gray-800 font-semibold" v-text="item.total">
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>

                        <!-- Top Customers -->
                        <div class="">
                            <div class="flex items-center justify-between p-[16px] pb-0">
                                <p class="text-gray-600 text-[16px] font-semibold">
                                    @lang('admin::app.dashboard.customer-with-most-sales')
                                </p>

                                <p class="text-[12px] text-gray-400 font-semibold">
                                    Apr 1-30
                                </p>
                            </div>

                            {{-- Customers Lists --}}
                            <div
                                class="flex flex-col gap-[32px] p-[16px]"
                                v-for="item in statistics.customer_with_most_sales"
                            >
                                <a :href="`{{ route('admin.customer.view', '') }}/${item.customer_id}`">
                                    <div class="flex justify-between gap-[6px]">
                                        <div class="flex flex-col">
                                            <p class="text-gray-600 font-semibold" v-text="item.customer_full_name ?? item.first_name">
                                            </p>

                                            <p class="text-gray-600" v-text="item.customer_email ?? item.customer_address_email">
                                            </p>
                                        </div>

                                        <div class="flex flex-col">
                                            <p class="text-gray-800 font-semibold" v-text="item.total_base_grand_total">
                                            </p>

                                            <p class="text-gray-600">
                                                @{{ item.total_orders }}
                                            </p>
                                        </div>
                                    </div>
                                </a>
                            </div>

                            {{-- <div class="flex flex-col gap-[32px] p-[16px]">
                                @foreach ($statistics['customer_with_most_sales'] as $item)
                                    @if ($item->customer_id)
                                        <a href="{{ route('admin.customer.view', $item->customer_id) }}">
                                    @endif
                                        <div class="flex justify-between gap-[6px]">
                                            <div class="flex flex-col">
                                                <p class="text-gray-600 font-semibold">
                                                    {{ $item->customer_full_name ?? $item->first_name . ' ' . $item->last_name }}
                                                </p>

                                                <p class="text-gray-600">
                                                    {{ $item->customer_email ?? $item->customer_address_email }}
                                                </p>
                                            </div>

                                            <div class="flex flex-col">
                                                <p class="text-gray-800 font-semibold">
                                                    {{ core()->formatBasePrice($item->total_base_grand_total) }}
                                                </p>

                                                <p class="text-gray-600">
                                                    @lang('admin::app.dashboard.order-count', ['count' => $item->total_orders])
                                                </p>
                                            </div>
                                        </div>
                                    @if ($item->customer_id)
                                        </a>
                                    @endif
                                @endforeach
                            </div> --}}
                        </div>
                    </div>
                </x-admin::form>
            </div>
        </script>

        <script type="module">
            app.component('date-filter', {
                template: '#date-filter-template',

                data() {
                    return {
                        start: "{{ $startDate->format('Y-m-d') }}",

                        end: "{{ $endDate->format('Y-m-d') }}",

                        statistics: {},
                    }
                },

                mounted() {
                    this.$axios.get("{{ route('admin.dashboard.index') }}")
                        .then((response) => {
                            this.statistics = response.data.statistics;
                            console.log(this.statistics);
                        })
                        .catch(error => {
                            if (error.response.status == 422) {
                                setErrors(error.response.data.errors);
                            }
                        });
                },

                methods: {
                    applyFilter: function(field, date) {
                        this[field] = event.target.value;

                        const queryParams = `?start=${this.start}&end=${this.end}`;

                        this.$axios.get("{{ route('admin.dashboard.index') }}" + queryParams)
                            .then((response) => {
                                this.statistics = response.data.statistics;
                            })
                            .catch(error => {
                                if (error.response.status == 422) {
                                    setErrors(error.response.data.errors);
                                }
                            });
                    }
                }
            });
        </script>

        <script>
              window.addEventListener("DOMContentLoaded", function () {
                    const ctx = document.getElementById('myChart');
                
                    var data = @json($statistics['sale_graph']);
                
                    new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: data['label'],
                            datasets: [{
                                data: data['total'],
                                backgroundColor: 'rgba(34, 201, 93, 1)',
                                borderColor: 'rgba(34, 201, 93, 1)',
                                borderWidth: 1
                            }]
                        },
                
                        options: {
                            scales: {
                                y: {
                                    beginAtZero: true
                                }
                            }
                        }
                    });
                });
        </script>    
    @endpush
</x-admin::layouts>
