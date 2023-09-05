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
            <p class="pt-[6px] text-[20px] text-gray-800 font-bold leading-[24px]">
                @lang('admin::app.dashboard.index.user-name', ['user_name' => $admin->name])
            </p>

            <p class="text-gray-600">
                @lang('admin::app.dashboard.index.user-info')
            </p>
        </div>
    </div>

    {{-- Body Component --}}
    <div class="flex gap-[10px] mt-[14px] max-xl:flex-wrap">
        {{-- Left Component --}}
        <div class=" flex flex-col gap-[30px] flex-1 max-xl:flex-auto">
            {{-- Overall Detailes --}}
            <div class="flex flex-col gap-[8px] ">
                <p class="text-[16px] text-gray-600 font-semibold">
                    @lang('admin::app.dashboard.index.overall-details')
                </p>

                <div class="p-[16px] bg-white rounded-[4px] box-shadow">
                    <div class="flex gap-[16px] flex-wrap ">
                        {{-- Total Sales --}}
                        <div class="flex gap-[10px] flex-1 min-w-[200px]">
                            <div class="w-full h-[60px] max-w-[60px] max-h-[60px]">
                                <img
                                    src="{{ bagisto_asset('images/total-sales.svg')}}"
                                    title="{{ trans('admin::app.dashboard.index.total-sales') }}"
                                >
                            </div>

                            {{-- Sales Stats --}}
                            <div class="grid gap-[4px] place-content-start">
                                <p class="text-[16px] text-gray-800 font-semibold">
                                    {{ core()->formatBasePrice($statistics['total_sales']['current']) }}
                                </p>

                                <p class="text-[12px] text-gray-600 font-semibold">
                                    @lang('admin::app.dashboard.index.total-sales')
                                </p>

                                {{-- Sales Percentage --}}
                                <div class="flex gap-[2px] items-center">
                                    @if ($statistics['total_sales']['progress'] < 0)
                                        <span class="icon-down-stat text-[16px] text-red-500"></span>

                                        <p class="text-[12px] text-red-500 font-semibold">
                                            @lang('admin::app.dashboard.index.decreased', [
                                                'progress' => -number_format($statistics['total_sales']['progress'], 1),
                                            ])
                                        </p>
                                    @else
                                        <span class="icon-up-stat text-[16px] text-emerald-500"></span>

                                        <p class="text-[12px] text-emerald-500 font-semibold">
                                            @lang('admin::app.dashboard.index.increased', [
                                                'progress' => number_format($statistics['total_sales']['progress'], 1),
                                            ])
                                        </p>
                                    @endif
                                </div>
                            </div>
                        </div>

                        {{-- Total Orders --}}
                        <div class="flex gap-[10px] flex-1 min-w-[200px]">
                            <div class="w-full h-[60px] max-w-[60px] max-h-[60px]">
                                <img
                                    src="{{ bagisto_asset('images/total-orders.svg')}}"
                                    title="{{ trans('admin::app.dashboard.index.total-orders') }}"
                                >
                            </div>

                            {{-- Orders Stats --}}
                            <div class="grid gap-[4px] place-content-start">
                                <p class="text-[16px] text-gray-800 font-semibold">
                                    {{ $statistics['total_orders']['current'] }}
                                </p>

                                <p class="text-[12px] text-gray-600 font-semibold">
                                    @lang('admin::app.dashboard.index.total-orders')
                                </p>

                                {{-- Order Percentage --}}
                                <div class="flex gap-[2px] items-center">
                                    @if ($statistics['total_orders']['progress'] < 0)
                                        <span class="icon-down-stat text-[16px] text-red-500"></span>

                                        <p class="text-[12px] text-red-500 font-semibold">
                                            @lang('admin::app.dashboard.index.decreased', [
                                                'progress' => -number_format($statistics['total_orders']['progress'], 1),
                                            ])
                                        </p>
                                    @else
                                        <span class="icon-up-stat text-[16px] text-emerald-500"></span>

                                        <p class="text-[12px] text-emerald-500 font-semibold">
                                            @lang('admin::app.dashboard.index.increased', [
                                                'progress' => number_format($statistics['total_orders']['progress'], 1),
                                            ])
                                        </p>
                                    @endif
                                </div>
                            </div>
                        </div>

                        {{-- Total Customers --}}
                        <div class="flex gap-[10px] flex-1 min-w-[200px]">
                            <div class="w-full h-[60px] max-w-[60px] max-h-[60px]">
                                <img
                                    src="{{ bagisto_asset('images/customer.svg')}}"
                                    title="{{ trans('admin::app.dashboard.index.total-customers') }}"
                                >
                            </div>

                            {{-- Customers Stats --}}
                            <div class="grid gap-[4px] place-content-start">
                                <p class="text-[16px] text-gray-800 font-semibold">
                                    {{ $statistics['total_customers']['current'] }}
                                </p>

                                <p class="text-[12px] text-gray-600 font-semibold">
                                    @lang('admin::app.dashboard.index.total-customers')
                                </p>

                                {{-- Customers Percentage --}}
                                <div class="flex gap-[2px] items-center">
                                    @if ($statistics['total_customers']['progress'] < 0)
                                        <span class="icon-down-stat text-[16px] text-red-500"></span>

                                        <p class="text-[12px] text-red-500 font-semibold">
                                            @lang('admin::app.dashboard.index.decreased', [
                                                    'progress' => -number_format($statistics['total_customers']['progress'], 1)
                                            ])
                                        </p>
                                    @else
                                        <span class="icon-up-stat text-[16px] text-emerald-500"></span>

                                        <p class="text-[12px] text-emerald-500 font-semibold">
                                            @lang('admin::app.dashboard.index.increased', [
                                                'progress' => number_format($statistics['total_customers']['progress'], 1)
                                            ])
                                        </p>
                                    @endif
                                </div>
                            </div>
                        </div>

                        {{-- Average sales --}}
                        <div class="flex gap-[10px] flex-1 min-w-[200px]">
                            <div class="w-full h-[60px] max-w-[60px] max-h-[60px]">
                                <img
                                    src="{{ bagisto_asset('images/average-order.svg')}}"
                                    title="{{ trans('admin::app.dashboard.index.average-sale') }}"
                                >
                            </div>

                            {{-- Sales Stats --}}
                            <div class="grid gap-[4px] place-content-start">
                                <p class="text-[16px] text-gray-800 font-semibold">
                                    {{ core()->formatBasePrice($statistics['avg_sales']['current']) }}
                                </p>

                                <p class="text-[12px] text-gray-600 font-semibold">
                                    @lang('admin::app.dashboard.index.average-sale')
                                </p>

                                {{-- Sales Percentage --}}
                                <div class="flex gap-[2px] items-center">
                                    @if ($statistics['avg_sales']['progress'] < 0)
                                        <span class="icon-down-stat text-[16px] text-red-500"></span>

                                        <p class="text-[12px] text-red-500 font-semibold">
                                            @lang('admin::app.dashboard.index.decreased', [
                                                'progress' => -number_format($statistics['avg_sales']['progress'], 1),
                                            ])
                                        </p>
                                    @else
                                        <span class="icon-up-stat text-[16px] text-emerald-500"></span>
                                        <p class="text-[12px] text-emerald-500 font-semibold">
                                            @lang('admin::app.dashboard.index.increased', [
                                                'progress' => number_format($statistics['avg_sales']['progress'], 1),
                                            ])
                                        </p>
                                    @endif
                                </div>
                            </div>
                        </div>

                        {{-- Unpaid Invoices --}}
                        <div class="flex gap-[10px] flex-1 min-w-[200px]">
                            <div class="w-full h-[60px] max-w-[60px] max-h-[60px]">
                                <img
                                    src="{{ bagisto_asset('images/unpaid-invoice.svg')}}"
                                    title="{{ trans('admin::app.dashboard.index.total-unpaid-invoices') }}"
                                >
                            </div>

                            <div class="grid gap-[4px] place-content-start">
                                <p class="text-[16px] text-gray-800 font-semibold">
                                    {{ core()->formatBasePrice($statistics['total_unpaid_invoices']) }}
                                </p>

                                <p class="text-[12px] text-gray-600 font-semibold">
                                    @lang('admin::app.dashboard.index.total-unpaid-invoices')
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Todays Deatiles --}}
            <div class="flex flex-col gap-[8px]">
                <p class="text-[16px] text-gray-600 font-semibold">
                    @lang('admin::app.dashboard.index.today-details')
                </p>

                <div class="bg-white rounded-[4px] box-shadow">
                    <div class="flex gap-[16px] flex-wrap p-[16px] border-b-[1px] border-gray-300">
                        {{-- Today's Sales --}}
                        <div class="flex gap-[10px] flex-1">
                            <img
                                class="w-full h-[60px] max-w-[60px] max-h-[60px]"
                                src="{{ bagisto_asset('images/total-sales.svg')}}"
                                title="{{ trans('admin::app.dashboard.index.today-sales') }}"
                            >

                            {{-- Sales Stats --}}
                            <div class="grid gap-[4px] place-content-start">
                                <p class="text-[16px] text-gray-800 font-semibold">
                                    {{ core()->formatBasePrice($statistics['today_details']['today_sales']['current']) }}
                                </p>

                                <p class="text-[12px] text-gray-600 font-semibold">
                                    @lang('admin::app.dashboard.index.today-sales')
                                </p>

                                {{-- Percentage Of Sales --}}
                                <div class="flex gap-[2px] items-center">
                                    @if ($statistics['today_details']['today_sales']['progress'] < 0)
                                        <span class="icon-down-stat text-[16px] text-red-500"></span>

                                        <p class="text-[12px] text-red-500 font-semibold">
                                            @lang('admin::app.dashboard.index.decreased', [
                                                'progress' => -number_format($statistics['today_details']['today_sales']['progress'], 1),
                                            ])
                                        </p>
                                    @else
                                        <span class="icon-up-stat text-[16px] text-emerald-500"></span>

                                        <p class="text-[12px] text-emerald-500 font-semibold">
                                            @lang('admin::app.dashboard.index.increased', [
                                                'progress' => number_format($statistics['today_details']['today_sales']['progress'], 1),
                                            ])
                                        </p>
                                    @endif
                                </div>
                            </div>
                        </div>

                        {{-- Today's Orders --}}
                        <div class="flex gap-[10px] flex-1">
                            <img
                                class="w-full h-[60px] max-w-[60px] max-h-[60px]"
                                src="{{ bagisto_asset('images/total-orders.svg')}}"
                                title="{{ trans('admin::app.dashboard.index.today-orders') }}"
                            >

                            {{-- Orders Stats --}}
                            <div class="grid gap-[4px] place-content-start">
                                <p class="text-[16px] text-gray-800 font-semibold">
                                    {{ $statistics['today_details']['today_orders']['current']->count() }}
                                </p>

                                <p class="text-[12px] text-gray-600 font-semibold">
                                    @lang('admin::app.dashboard.index.today-orders')
                                </p>

                                {{-- Orders Percentage --}}
                                <div class="flex gap-[2px] items-center">
                                    @if ($statistics['today_details']['today_orders']['progress'] < 0)
                                        <span class="icon-down-stat text-[16px] text-red-500"></span>

                                        <p class="text-[12px] text-red-500 font-semibold">
                                            @lang('admin::app.dashboard.index.decreased', [
                                                'progress' => -number_format($statistics['today_details']['today_orders']['progress'], 1),
                                            ])
                                        </p>
                                    @else
                                        <span class="icon-up-stat text-[16px] text-emerald-500"></span>

                                        <p class="text-[12px] text-emerald-500 font-semibold">
                                            @lang('admin::app.dashboard.index.increased', [
                                                'progress' => number_format($statistics['today_details']['today_orders']['progress'], 1),
                                            ])
                                        </p>
                                    @endif
                                </div>
                            </div>
                        </div>

                        {{-- Today's Customers --}}
                        <div class="flex gap-[10px] flex-1">
                            <img
                                class="w-full h-[60px] max-w-[60px] max-h-[60px]"
                                src="{{ bagisto_asset('images/customer.svg')}}"
                                title="{{ trans('admin::app.dashboard.index.today-customers') }}"
                            >

                            {{-- Customers Stats --}}
                            <div class="grid gap-[4px] place-content-start">
                                <p class="text-[16px] text-gray-800 font-semibold">
                                    {{ $statistics['today_details']['today_customers']['current'] }}
                                </p>

                                <p class="text-[12px] text-gray-600 font-semibold">
                                    @lang('admin::app.dashboard.index.today-customers')
                                </p>

                                {{-- Customers Percentage --}}
                                <div class="flex gap-[2px] items-center">
                                    @if ($statistics['today_details']['today_customers']['progress'] < 0)
                                        <span class="icon-down-stat text-[16px] text-red-500"></span>

                                        <p class="text-[12px] text-red-500 font-semibold">
                                            @lang('admin::app.dashboard.index.decreased', [
                                                'progress' => -number_format($statistics['today_details']['today_customers']['progress'], 1),
                                            ])
                                        </p>
                                    @else
                                        <span class="icon-up-stat text-[16px] text-emerald-500"></span>

                                        <p class="text-[12px] text-emerald-500 font-semibold">
                                            @lang('admin::app.dashboard.index.increased', [
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
                        <div class="row grid grid-cols-4  gap-y-[24px] p-[16px] border-b-[1px] border-gray-300 transition-all hover:bg-gray-100 max-1580:grid-cols-3 max-sm:grid-cols-1">
                            {{-- Order ID, Status, Created --}}
                            <div class="flex gap-[10px]">
                                <div class="flex flex-col gap-[6px]">
                                    {{-- Order Id --}}
                                    <p class="text-[16px] text-gray-800 font-semibold">
                                        @lang('admin::app.dashboard.index.order-id', ['id' => $item->id])
                                    </p>

                                    <p class="text-gray-600">
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
                                <p class="text-[16px] text-gray-800 font-semibold">
                                    {{ core()->formatBasePrice($item->grand_total)}}
                                </p>

                                {{-- Payment Mode --}}
                                <p class="text-gray-600">
                                    @lang('admin::app.dashboard.index.pay-by', ['method' => core()->getConfigData('sales.paymentmethods.' . $item->payment->method . '.title')])
                                </p>

                                {{-- Channel Name --}}
                                <p class="text-gray-600">
                                    {{ $item->channel_name }}
                                </p>
                            </div>

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
                                                @if ($orderItem->product->base_image_url)
                                                    <img
                                                        class="min-h-[65px] min-w-[65px] max-h-[65px] max-w-[65px] rounded-[4px]"
                                                        src="{{ $orderItem->product->base_image_url }}"
                                                    />
                                                @else
                                                    <div class="w-full h-[65px] max-w-[65px] max-h-[65px] relative border border-dashed border-gray-300 rounded-[4px] overflow-hidden">
                                                        <img src="{{ bagisto_asset('images/product-placeholders/front.svg')}}">

                                                        <p class="w-full absolute bottom-[5px] text-[6px] text-gray-400 text-center font-semibold">
                                                            @lang('admin::app.dashboard.index.product-image')
                                                        </p>
                                                    </div>
                                                @endif


                                                <span class="absolute bottom-[1px] ltr:left-[1px] rtl:right-[1px] text-[12px] font-bold text-white bg-darkPink rounded-full px-[6px]">
                                                    {{ $orderItem->product->images->count() }}
                                                </span>
                                            </div>
                                        @endforeach

                                        {{-- Count of Rest Images --}}
                                        @if (
                                            $item->items->count() - 3 
                                            && $item->items->count() > 4
                                        )
                                            <a href="{{ route('admin.sales.orders.view', $item->id) }}">
                                                <div class="flex items-center w-[65px] h-[65px] bg-gray-50 rounded-[4px]">
                                                    <p class="text-[12px] text-gray-600 text-center font-bold px-[6px] py-[6px]">
                                                        @lang('admin::app.dashboard.index.more-products', ['product_count' => $item->items->count() - 3 ])
                                                    </p>
                                                </div>
                                            </a>
                                        @endif
                                    </div>

                                    {{-- View More Icon --}}
                                    <a href="{{ route('admin.sales.orders.view', $item->id) }}">
                                        <span class="icon-sort-right text-[24px] ltr:ml-[4px] rtl:mr-[4px] p-[6px] cursor-pointer hover:bg-gray-200 hover:rounded-[6px]"></span>
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
                    @lang('admin::app.dashboard.index.stock-threshold')
                </p>

                {{-- Products List --}}
                <div class="bg-white rounded-[4px] box-shadow">
                    @foreach ($statistics['stock_threshold'] as $item)
                        <!-- Single Product -->
                        <div class="relative">
                            <div class="row grid grid-cols-2 gap-y-[24px] p-[16px] border-b-[1px] border-gray-300 transition-all hover:bg-gray-100 max-sm:grid-cols-[1fr_auto]">
                                <div class="flex gap-[10px]">
                                    @if ($item->product->base_image_url)
                                        <div class="">
                                            <img
                                                class="min-h-[65px] min-w-[65px] max-h-[65px] max-w-[65px] rounded-[4px]"
                                                src="{{ $item->product->base_image_url }}"
                                            >
                                        </div>
                                    @else
                                        <div class="w-full h-[65px] max-w-[65px] max-h-[65px] relative border border-dashed border-gray-300 rounded-[4px] overflow-hidden">
                                            <img src="{{ bagisto_asset('images/product-placeholders/front.svg')}}">

                                            <p class="w-full absolute bottom-[5px] text-[6px] text-gray-400 text-center font-semibold">
                                                @lang('admin::app.dashboard.index.product-image')
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
                                            @lang('admin::app.dashboard.index.sku', ['sku' => $item->product->sku])
                                        </p>

                                        {{-- Product Number --}}
                                        <p class="text-gray-600">
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
                                        <p class="text-[16px] text-gray-800 font-semibold">
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
                                        <span class="icon-sort-right text-[24px] ltr:ml-[4px] rtl:mr-[4px] p-[6px] cursor-pointer hover:bg-gray-200 hover:rounded-[6px]"></span>
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
                <div class="bg-white rounded-[4px] box-shadow box-shadow">
                    <!-- Total Sales Shimmer -->
                    <template v-if="isLoading">
                        <x-admin::shimmer.dashboard.right.total-sales/>
                    </template>

                    <template v-else>
                        <!-- Date Filter -->
                        <div class="flex gap-[6px] px-[16px] py-[8px] border-b border-gray-300">
                            <!-- Start Date Filter -->
                            <x-admin::form.control-group class="flex-1 mb-[10px]">
                                <x-admin::form.control-group.label class="!text-gray-800 font-medium">
                                    @lang('admin::app.dashboard.index.start-date')
                                </x-admin::form.control-group.label>
    
                                <x-admin::form.control-group.control
                                    type="date"
                                    name="startDate" 
                                    class="cursor-pointer"
                                    :value="$startDate->format('Y-m-d')"
                                    :placeholder="trans('admin::app.dashboard.index.start-date')"
                                    @change="applyFilter('start', $event)"
                                >
                                </x-admin::form.control-group.control>
                            </x-admin::form.control-group>
    
                            <!-- End Date Filter -->
                            <x-admin::form.control-group class="flex-1 mb-[10px]">
                                <x-admin::form.control-group.label class="!text-gray-800 font-medium">
                                    @lang('admin::app.dashboard.index.end-date')
                                </x-admin::form.control-group.label>
    
                                <x-admin::form.control-group.control
                                    type="date"
                                    name="endDate" 
                                    class="cursor-pointer"
                                    :value="$endDate->format('Y-m-d')"
                                    :placeholder="trans('admin::app.dashboard.index.end-date')"
                                    @change="applyFilter('end', $event)"
                                >
                                </x-admin::form.control-group.control>
                            </x-admin::form.control-group>
                        </div>
    
                        <!-- Total Sales Detailes -->
                        <div class="grid gap-[16px] px-[16px] py-[8px] border-b border-gray-300">
                            <div class="flex gap-[8px] justify-between">
                                <div class="flex flex-col gap-[4px] justify-between">
                                    <p class="text-[12px] text-gray-600 font-semibold">
                                        @lang('admin::app.dashboard.index.total-sales')
                                    </p>
    
                                    <!-- Total Order Revenue -->
                                    <p class="text-[18px] text-gray-800 font-bold">
                                        @{{ statistics.total_sales?.formatted_total }}
                                    </p>
                                </div>
    
                                <div class="flex flex-col gap-[4px] justify-between">
                                    <!-- Orders Time Duration -->
                                    <p class="text-[12px] text-gray-400 font-semibold text-right">
                                        @{{ "@lang('admin::app.dashboard.index.date-duration')".replace(':start', formatStart ?? 0).replace(':end', formatEnd ?? 0) }}
                                    </p>
    
                                    <!-- Total Orders -->
                                    <p class="text-[12px] text-gray-400 font-semibold text-right">
                                        @{{ "@lang('admin::app.dashboard.index.order')".replace(':total_orders', statistics.total_orders?.current ?? 0) }}
                                    </p>
                                </div>
                            </div>
    
                            <!-- Sales Graph -->
                            <canvas
                                id="myChart"
                                style="width: 100%; height: 230px"
                            >
                            </canvas>
                        </div>
                    </template>

                    <!-- Top Selling Products -->
                    <div class="border-b border-gray-300">
                        <div class="flex items-center justify-between p-[16px]">
                            <p class="text-gray-600 text-[16px] font-semibold">
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
                                v-if="statistics?.top_selling_products?.length"
                            >
                                <a
                                    :href="`{{route('admin.catalog.products.edit', '')}}/${item.product_id}`"
                                    class="flex gap-[10px] p-[16px] border-b-[1px] border-gray-300 last:border-b-0 transition-all hover:bg-gray-100"
                                    v-for="item in statistics.top_selling_products"
                                >
                                    <!-- Product Item -->
                                    <img
                                        v-if="item?.product?.images.length"
                                        class="w-full h-[65px] max-w-[65px] max-h-[65px] relative rounded-[4px] overflow-hidden"
                                        :src="item?.product?.images[0]?.url"
                                    />

                                    <div
                                        v-else
                                        class="w-full h-[65px] max-w-[65px] max-h-[65px] relative border border-dashed border-gray-300 rounded-[4px] overflow-hidden"
                                    >
                                        <img src="{{ bagisto_asset('images/product-placeholders/front.svg')}}">
                                        
                                        <p class="w-full absolute bottom-[5px] text-[6px] text-gray-400 text-center font-semibold">
                                            @lang('admin::app.dashboard.index.product-image')
                                        </p>
                                    </div>

                                    <!-- Product Detailes -->
                                    <div class="flex flex-col gap-[6px] w-full">
                                        <p
                                            class="text-gray-600"
                                            v-text="item.name"
                                        >
                                        </p>

                                        <div class="flex justify-between">
                                            <p
                                                class="text-gray-600 font-semibold"
                                                v-text="item.formatted_price"
                                            >
                                            </p>

                                            <p
                                                class="text-[16px] text-gray-800 font-semibold"
                                                v-text="item.formatted_total"
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
                                        class="w-[80px] h-[80px]"
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
                        <p class="text-gray-600 text-[16px] font-semibold">
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
                            class="flex flex-col gap-[32px] p-[16px] border-b-[1px] border-gray-300 last:border-b-0 transition-all hover:bg-gray-100"
                            v-if="statistics?.customer_with_most_sales?.length"
                            v-for="item in statistics.customer_with_most_sales"
                        >
                            <a
                                :href="`{{ route('admin.customers.customers.view', '') }}/${item.customer_id}`"
                                v-if="item.customer_id"
                            >
                                <div class="flex justify-between gap-[6px]">
                                    <div class="flex flex-col">
                                        <p
                                            class="text-gray-600 font-semibold"
                                            v-text="item.customer_first_name + ' ' + item.customer_last_name"
                                        >
                                        </p>

                                        <p
                                            class="text-gray-600"
                                            v-text="item.customer_email ?? item.customer_address_email"
                                        >
                                        </p>
                                    </div>

                                    <div class="flex flex-col">
                                        <p
                                            class="text-gray-800 font-semibold"
                                            v-text="item.formatted_total_base_grand_total"
                                        >
                                        </p>

                                        <p class="text-gray-600" v-if="item.order_count">
                                            @{{ "@lang('admin::app.dashboard.index.order-count')".replace(':count', item.order_count) }}
                                        </p>
                                    </div>
                                </div>
                            </a>

                            <div
                                v-else
                                class="flex justify-between gap-[6px]"
                            >
                                <div class="flex flex-col">
                                    <p
                                        class="text-gray-600 font-semibold"
                                        v-text="item.customer_first_name + ' ' + item.customer_last_name"
                                    >
                                    </p>

                                    <p
                                        class="text-gray-600"
                                        v-text="item.customer_email ?? item.customer_address_email"
                                    >
                                    </p>
                                </div>

                                <div class="flex flex-col">
                                    <p
                                        class="text-gray-800 font-semibold"
                                        v-text="item.formatted_total_base_grand_total"
                                    >
                                    </p>

                                    <p class="text-gray-600" v-if="item.order_count">
                                        @{{ "@lang('admin::app.dashboard.index.order-count')".replace(':count', item.order_count) }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div
                            class="flex flex-col gap-[32px] p-[16px]"
                            v-else
                        >
                            <div class="grid gap-[14px] justify-center justify-items-center py-[10px]">
                                <!-- Placeholder Image -->
                                <img
                                    src="{{ bagisto_asset('images/icon-add-product.svg') }}"
                                    class="w-[80px] h-[80px]"
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

                        start: "{{ $startDate->format('Y-m-d') }}",

                        formatStart: "",

                        end: "{{ $endDate->format('Y-m-d') }}",

                        formatEnd: "",

                        statistics: {},
                    }
                },

                mounted() {
                    this.$axios.get("{{ route('admin.dashboard.index') }}")
                        .then((response) => {
                            this.formatStart = response.data.startDate;

                            this.formatEnd = response.data.endDate;

                            this.statistics = response.data.statistics;

                            this.isLoading = ! this.isLoading;

                            setTimeout(() => {
                                this.graphChart();
                            }, 0);

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
                                this.formatStart = response.data.startDate;

                                this.formatEnd = response.data.endDate;

                                this.statistics = response.data.statistics;
                            })
                            .catch(error => {
                                if (error.response.status == 422) {
                                    setErrors(error.response.data.errors);
                                }
                            });
                    },

                    graphChart: function () {
                        const ctx = document.getElementById('myChart');
                
                        var data = this.statistics.sale_graph;
                    
                        new Chart(ctx, {
                            type: 'bar',
                            data: {
                                labels: data['label'],
                                datasets: [{
                                    data: data['total'],
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
                }
            });
        </script>   
    @endpush
</x-admin::layouts>
