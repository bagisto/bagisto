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
                            <div class="grid gap-[4px] content-center justify-items-center min-w-[60px] h-[60px] px-[6px]">
                                <img
                                    class="w-[20px]"
                                    src="{{ bagisto_asset('images/product-placeholders/front.svg')}}"
                                >

                                <p class="text-[6px] text-gray-400 font-semibold">
                                    @lang('admin::app.dashboard.total-sales')
                                </p>
                            </div>

                            <div class="grid gap-[4px] place-content-start">
                                <p class="text-[16px] text-gray-800 font-semibold">
                                    {{ core()->formatBasePrice($statistics['total_sales']['current']) }}
                                </p>

                                <p class="text-[12px] text-gray-600 font-semibold">
                                    @lang('admin::app.dashboard.total-sales')
                                </p>

                                <div class="flex gap-[2px] items-center">
                                    @if ($statistics['total_sales']['progress'] < 0)
                                        <span class="icon-down-stat text-[16px] text-red-500"></span>

                                        <p class="text-[12px] text-red-500 font-semibold">
                                            @lang('admin::app.dashboard.decreased', [
                                                'progress' => -number_format($statistics['total_sales']['progress'], 1),
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
                            <div class="grid gap-[4px] content-center jus   tify-items-center min-w-[60px] h-[60px] px-[6px]">
                                <img
                                    class="w-[20px]"
                                    src="{{ bagisto_asset('images/product-placeholders/front.svg')}}"
                                >
                                <p class="text-[6px] text-gray-400 font-semibold">
                                    @lang('admin::app.dashboard.total-orders')
                                </p>
                            </div>

                            <div class="grid gap-[4px] place-content-start">
                                <p class="text-[16px] text-gray-800 font-semibold">
                                    {{ $statistics['total_orders']['current'] }}
                                </p>

                                <p class="text-[12px] text-gray-600 font-semibold">
                                    @lang('admin::app.dashboard.total-orders')
                                </p>

                                <div class="flex gap-[2px] items-center">
                                    @if ($statistics['total_orders']['progress'] < 0)
                                        <span class="icon-down-stat text-[16px] text-red-500"></span>

                                        <p class="text-[12px] text-red-500 font-semibold">
                                            @lang('admin::app.dashboard.decreased', [
                                                'progress' => -number_format($statistics['total_orders']['progress'], 1),
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
                            <div class="grid gap-[4px] content-center justify-items-center min-w-[60px] h-[60px] px-[6px]">
                                <img
                                    class="w-[20px]"
                                    src="{{ bagisto_asset('images/product-placeholders/front.svg')}}"
                                >

                                <p class="text-[6px] text-gray-400 font-semibold">
                                    @lang('admin::app.dashboard.total-customers')
                                </p>
                            </div>

                            <div class="grid gap-[4px] place-content-start">
                                <p class="text-[16px] text-gray-800 font-semibold">
                                    {{ $statistics['total_customers']['current'] }}
                                </p>

                                <p class="text-[12px] text-gray-600 font-semibold">
                                    @lang('admin::app.dashboard.total-customers')
                                </p>

                                <div class="flex gap-[2px] items-center">
                                    @if ($statistics['total_customers']['progress'] < 0)
                                        <span class="icon-down-stat text-[16px] text-red-500"></span>

                                        <p class="text-[12px] text-red-500 font-semibold">
                                            @lang('admin::app.dashboard.decreased', [
                                                    'progress' => -number_format($statistics['total_customers']['progress'], 1)
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
                            <div class="grid gap-[4px] content-center justify-items-center min-w-[60px] h-[60px] px-[6px]">
                                <img
                                    class="w-[20px]"
                                    src="{{ bagisto_asset('images/product-placeholders/front.svg')}}"
                                >

                                <p class="text-[6px] text-gray-400 font-semibold">
                                    @lang('Average Sales')
                                </p>
                            </div>

                            <div class="grid gap-[4px] place-content-start">
                                <p class="text-[16px] text-gray-800 font-semibold">
                                    {{ core()->formatBasePrice($statistics['avg_sales']['current']) }}
                                </p>

                                <p class="text-[12px] text-gray-600 font-semibold">
                                    @lang('admin::app.dashboard.average-sale')
                                </p>

                                <div class="flex gap-[2px] items-center">
                                    {{-- @if ($statistics['avg_sales']['progress'] < 0)
                                        <span class="icon-down-stat text-[16px] text-red-500"></span>
                                        <p class="text-[12px] text-red-500 font-semibold">
                                            {{ __('admin::app.dashboard.decreased', [
                                                'progress' => -number_format($statistics['avg_sales']['progress'], 1),
                                            ]) }}
                                        </p>
                                    @else
                                        <span class="icon-up-stat text-[16px] text-emerald-500"></span>
                                        <p class="text-[12px] text-emerald-500 font-semibold"
                                            {{ __('admin::app.dashboard.increased', [
                                                'progress' => number_format($statistics['avg_sales']['progress'], 1),
                                            ]) }}
                                        </p>
                                    @endif --}}
                                </div>
                            </div>
                        </div>

                        {{-- Unpaid Invoices --}}
                        <div class="flex gap-[10px] flex-1">
                            <div class="grid gap-[4px] justify-items-center content-center min-w-[60px] h-[60px] px-[6px]">
                                <img
                                    class="w-[20px]"
                                    src="{{ bagisto_asset('images/product-placeholders/front.svg') }}"
                                />

                                <p class="text-[6px] text-gray-400 font-semibold">
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
                            <div class="grid gap-[4px] content-center justify-items-center min-w-[60px] h-[60px] px-[6px]">
                                <img
                                    class="w-[20px]"
                                    src="{{ bagisto_asset('images/product-placeholders/front.svg')}}"
                                >

                                <p class="text-[6px] text-gray-400 font-semibold">
                                    @lang('admin::app.dashboard.product-image')
                                </p>
                            </div>

                            <div class="grid gap-[4px] place-content-start">
                                <p class="text-[16px] text-gray-800 font-semibold">
                                    $387,820.85
                                </p>

                                <p class="text-[12px] text-gray-600 font-semibold">
                                    @lang('admin::app.dashboard.today-sales')
                                </p>

                                <div class="flex gap-[2px] items-center">
                                    <span class="icon-up-stat text-[16px] text-emerald-500"></span>
                                    <p class="text-[12px] text-emerald-500 font-semibold">25%</p>
                                </div>
                            </div>
                        </div>

                        {{-- Today's Orders --}}
                        <div class="flex gap-[10px] flex-1">
                            <div class="grid gap-[4px] content-center justify-items-center min-w-[60px] h-[60px] px-[6px]">
                                <img
                                    class="w-[20px]"
                                    src="{{ bagisto_asset('images/product-placeholders/front.svg')}}"
                                >

                                <p class="text-[6px] text-gray-400 font-semibold">
                                    @lang('admin::app.dashboard.product-image')
                                </p>
                            </div>

                            <div class="grid gap-[4px] place-content-start">
                                <p class="text-[16px] text-gray-800 font-semibold">
                                    $387,820.85
                                </p>

                                <p class="text-[12px] text-gray-600 font-semibold">
                                    @lang('admin::app.dashboard.today-orders')
                                </p>

                                <div class="flex gap-[2px] items-center">
                                    <span class="icon-down-stat text-[16px] text-red-500"></span>
                                    <p class="text-[12px] text-red-500 font-semibold">25%</p>
                                </div>
                            </div>
                        </div>

                        {{-- Today's Customers --}}
                        <div class="flex gap-[10px] flex-1">
                            <div class="grid gap-[4px] content-center justify-items-center min-w-[60px] h-[60px] px-[6px]">
                                <img
                                    class="w-[20px]"
                                    src="{{ bagisto_asset('images/product-placeholders/front.svg')}}"
                                >

                                <p class="text-[6px] text-gray-400 font-semibold">
                                    @lang('admin::app.dashboard.product-image')
                                </p>
                            </div>

                            <div class="grid gap-[4px] place-content-start">
                                <p class="text-[16px] text-gray-800 font-semibold">
                                    $387,820.85
                                </p>

                                <p class="text-[12px] text-gray-600 font-semibold">
                                    @lang('admin::app.dashboard.today-customers')
                                </p>

                                <div class="flex gap-[2px] items-center">
                                    <span class="icon-up-stat text-[16px] text-emerald-500"></span>
                                    <p class="text-[12px] text-emerald-500 font-semibold">25%</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- single row -->
                    <div class="row grid grid-cols-4  gap-y-[24px] p-[16px] border-b-[1px] border-gray-300 max-1580:grid-cols-3 max-sm:grid-cols-1">
                        <div class="">
                            <div class="flex gap-[10px]">
                                <div class="flex flex-col gap-[6px]">
                                    <p class="text-[16px] text-gray-800 font-semibold">#02153</p>
                                    <p class="text-gray-600">23 Mar 2023, 01:00:00</p>
                                    <p class="label-pending">Pending</p>
                                </div>
                            </div>
                        </div>
                        <div class="">
                            <div class="flex flex-col gap-[6px]">
                                <p class="text-[16px] text-gray-800 font-semibold">$75.00</p>
                                <p class="text-gray-600">Pay by - Cash on Delivery</p>
                                <p class="text-gray-600">Online Store</p>
                            </div>
                        </div>
                        <div class="">
                            <div class="flex flex-col gap-[6px]">
                                <p class="text-[16px] text-gray-800">John Doe</p>
                                <p class="text-gray-600">john@deo.com</p>
                                <p class="text-gray-600">Broadway, New York</p>
                            </div>
                        </div>
                        <div class="max-1580:col-span-full">
                            <div class="flex  gap-[6px] items-center justify-between">
                                <div class="flex gap-[6px] items-center flex-wrap">
                                    <div class="relative">
                                        <img class="min-h-[65px] min-w-[65px] max-h-[65px] max-w-[65px] rounded-[4px]" src="../images/order-1.png">
                                        <span class="absolute bottom-[1px] left-[1px] text-[12px] font-bold text-white bg-darkPink rounded-full px-[6px] ">1</span>
                                    </div>
                                    <div class="relative">
                                        <img class="min-h-[65px] min-w-[65px] max-h-[65px] max-w-[65px] rounded-[4px]" src="../images/order-1.png">
                                        <span class="absolute bottom-[1px] left-[1px] text-[12px] font-bold text-white bg-darkPink rounded-full px-[6px] ">2</span>
                                    </div>

                                    <div class="flex items-center w-[65px] h-[65px] bg-gray-50 rounded-[4px]">
                                        <p class="text-[12px] text-gray-600 text-center font-bold px-[6px] py-[6px]">2+ More Products </p>
                                    </div>
                                </div>
                                <span class="icon-sort-right text-[24px] ml-[4px] cursor-pointer"></span>
                            </div>
                        </div>
                    </div>
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
                        <a href="{{ route('admin.catalog.products.edit', $item->product_id) }}">
                            <div class="relative">
                                <div class="row grid grid-cols-2  gap-y-[24px] p-[16px] border-b-[1px] border-gray-300 max-sm:grid-cols-[1fr_auto]">
                                    <div class="flex gap-[10px]">
                                        <div class="">
                                            <img
                                                class="min-h-[65px] min-w-[65px] max-h-[65px] max-w-[65px] border border-dashed border-gray-300 rounded-[4px]"
                                                src="{{ bagisto_asset('images/product-placeholders/front.svg') }}"
                                            >
                                        </div>

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

                                    <div class="flex items-center">
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

                                        <span class="icon-sort-right text-[24px] ml-[4px] cursor-pointer pointer-events-none sm:absolute sm:top-[50%] sm:-translate-y-[50%] sm:right-[16px]"></span>
                                    </div>
                                </div>
                            </div>
                        </a>
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

            <div class="bg-white rounded-[4px] box-shadow">
                <div class="flex gap-[6px] px-[16px] py-[8px] border-b border-gray-300">
                    <div class="flex-1 ">
                        <label
                            class="block text-[12px] text-gray-800 font-medium leading-[24px]"
                            for="username"
                        >
                            Attribute Code*
                        </label>
                        
                        <div class="flex gap-x-[4px] items-center justify-between text-gray-600 text-[14px] font-normal py-[6px] px-[12px] text-center max-w-full bg-white border border-gray-300 rounded-[6px] cursor-pointer marker:shadow appearance-none focus:ring-2 focus:outline-none focus:ring-black transition-all hover:border-gray-400">
                            color
                            <span class="icon-sort-down text-[24px]"></span>
                        </div>
                    </div>

                    <div class="flex-1">
                        <label
                            class="block text-[12px] text-gray-800 font-medium leading-[24px]"
                            for="username"
                        >
                            Attribute Code*
                        </label>

                        <div class="flex gap-x-[4px] items-center justify-between text-gray-600 text-[14px] font-normal py-[6px] px-[12px] text-center max-w-full bg-white border border-gray-300 rounded-[6px] cursor-pointer marker:shadow appearance-none focus:ring-2 focus:outline-none focus:ring-black transition-all hover:border-gray-400">
                            color
                            <span class="icon-sort-down text-[24px]"></span>
                        </div>
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
                        </div>

                        <div class="flex flex-col gap-[4px]justify-between">
                            {{-- Orders Time Duration --}}
                            <p class="text-[12px] text-gray-400 font-semibold">
                                Apr 1-30
                            </p>

                            {{-- Total Orders --}}
                            <p class="text-[12px] text-gray-400 font-semibold">
                                @lang('admin::app.dashboard.order', ['total_orders' => $statistics['total_orders']['current']])
                            </p>
                        </div>
                    </div>
                </div>

                <!-- visitors -->
                <div class="grid gap-[16px] px-[16px] py-[8px] border-b border-gray-300">
                    <div class="flex gap-[8px] justify-between">
                        <div class="flex flex-col gap-[4px] justify-between">
                            <p class="text-[12px] text-gray-600 font-semibold">
                                @lang('visitor')
                            </p>

                            <p class="text-[18px] text-gray-800 font-bold">
                                249071
                            </p>
                        </div>

                        <div class="flex flex-col gap-[4px]justify-between">
                            <p class="text-[12px] text-gray-400 font-semibold">
                                Apr 1-30
                            </p>

                            <p class="text-[12px] text-gray-400 font-semibold">
                                216,119 unique
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
                        <!-- single product  -->
                        @foreach ($statistics['top_selling_products'] as $item)
                            <a href="{{ route('admin.catalog.products.edit', $item->product_id) }}">
                                <div class="flex gap-[10px]">
                                    <img
                                        class="min-h-[65px] min-w-[65px] max-h-[65px] max-w-[65px] rounded-[4px]"
                                        src="{{ bagisto_asset('images/product-placeholders/front.svg') }}"
                                    >
                                    <div class="flex flex-col gap-[6px]">
                                        <p class="text-gray-600">
                                            @if (isset($item->name))
                                                {{ $item->name }}
                                            @endif
                                        </p>

                                        <div class="flex  justify-between">
                                            <p class="text-gray-600 font-semibold">
                                                @if (isset($item->price))
                                                    {{ core()->formatBasePrice($item->price) }}
                                                @endif
                                            </p>

                                            <p class="text-[16px] text-gray-800 font-semibold">
                                                @if (isset($item->total))
                                                    {{ core()->formatBasePrice($item->total) }}
                                                @endif
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        @endforeach
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

                    <div class="flex flex-col gap-[32px] p-[16px]">
                        <!-- single product  -->
                        @foreach ($statistics['customer_with_most_sales'] as $item)
                            @if ($item->customer_id)
                                <a href="{{ route('admin.customer.view', $item->customer_id) }}">
                            @endif
                                <div class="flex justify-between gap-[6px]">
                                    <div class="flex flex-col">
                                        <p class="text-gray-600 font-semibold">
                                            {{ $item->customer_full_name }}
                                        </p>

                                        <p class="text-gray-600">
                                            {{ $item->customer_email }}
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
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-admin::layouts>
