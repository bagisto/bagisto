<x-shop::layouts.account>
    <!-- Page Title -->
    <x-slot:title>
        @lang('shop::app.customers.account.orders.view.page-title', ['order_id' => $order->increment_id])
    </x-slot>
    
    <!-- Breadcrumbs -->
    @section('breadcrumbs')
        <x-shop::breadcrumbs
            name="orders.view"
            :entity="$order"
        />
    @endSection

    <div class="max-md:hidden">
        <x-shop::layouts.account.navigation />
    </div>

    <div class="flex-auto">
        <div class="flex items-center justify-between">
            <div class="mb-8 flex items-center max-sm:mb-5">
                <!-- Back Button -->
                <a
                    class="grid md:hidden"
                    href="{{ route('shop.customers.account.orders.index') }}"
                >
                    <span class="icon-arrow-left rtl:icon-arrow-right text-2xl"></span>
                </a>
    
                <h2 class="text-2xl font-medium max-sm:text-xl ltr:ml-2.5 md:ltr:ml-0 rtl:mr-2.5 md:rtl:mr-0">
                    @lang('shop::app.customers.account.orders.view.page-title', ['order_id' => $order->increment_id])
                </h2>
            </div>

            <div class="flex gap-1.5">
                @if ($order->canCancel())
                    <a
                        href="{{ route('shop.customers.account.orders.reorder', $order->id) }}"
                        class="secondary-button flex items-center gap-x-2.5 border-zinc-200 px-5 py-3 font-normal"
                    >
                        @lang('shop::app.customers.account.orders.view.reorder-btn-title')
                    </a>
                @endif

                @if ($order->canCancel())
                    <form
                        method="POST"
                        ref="cancelOrderForm"
                        action="{{ route('shop.customers.account.orders.cancel', $order->id) }}"
                    >
                        @csrf
                    </form>

                    <a
                        class="secondary-button flex items-center gap-x-2.5 border-zinc-200 px-5 py-3 font-normal"
                        href="javascript:void(0);"
                        @click="$emitter.emit('open-confirm-modal', {
                            message: '@lang('shop::app.customers.account.orders.view.cancel-confirm-msg')',

                            agree: () => {
                                this.$refs['cancelOrderForm'].submit()
                            }
                        })"
                    >
                        @lang('shop::app.customers.account.orders.view.cancel-btn-title')
                    </a>
                @endif
            </div>
        </div>

        {!! view_render_event('bagisto.shop.customers.account.orders.view.before', ['order' => $order]) !!}

        <!-- Order view tabs -->
        <div class="mt-5 max-sm:mt-0">
            <x-shop::tabs>
                <x-shop::tabs.item
                    class="!px-0 py-2"
                    :title="trans('shop::app.customers.account.orders.view.information.info')"
                    :is-selected="true"
                >
                    <div class="text-base font-medium max-sm:hidden">
                        <label>
                            @lang('shop::app.customers.account.orders.view.information.placed-on')
                        </label>

                        <span>
                            {{ core()->formatDate($order->created_at, 'd M Y') }}
                        </span>
                    </div>

                    <!-- For Mobile View -->
                    <div class="grid gap-8 sm:hidden">
                        <div class="grid gap-1.5 rounded-lg border px-4 py-2.5 font-medium">
                            <div class="flex justify-between">
                                <p class="text-[#757575]">Order Id:</p>

                                <p>#{{ $order->increment_id }}</p>
                            </div>

                            <div class="flex justify-between">
                                <p class="text-[#757575]">@lang('shop::app.customers.account.orders.view.information.placed-on'):</p>

                                <p>{{ core()->formatDate($order->created_at, 'd M Y') }}</p>
                            </div>

                            <div class="flex justify-between">
                                <p class="text-[#757575]">Status</p>

                                @switch($order->status)
                                    @case('completed')
                                        <p class="label-completed">{{ ucfirst($order->status) }}</p>
                                        @break

                                    @case('pending')
                                        <p class="label-pending">{{ ucfirst($order->status) }}</p>
                                        @break

                                    @case('closed')
                                        <p class="label-closed">{{ ucfirst($order->status) }}</p>
                                        @break

                                    @case('processing')
                                        <p class="label-processing">{{ ucfirst($order->status) }}</p>
                                        @break

                                    @case('canceled')
                                        <p class="label-canceled">{{ ucfirst($order->status) }}</p>
                                        @break

                                    @default
                                        <p class="label-info">{{ ucfirst($order->status) }}</p>
                                @endswitch
                            </div>
                        </div>

                        <div>
                            <p class="mb-2.5 text-base font-medium">Item Ordered</p>

                            <div class="grid gap-3">
                                @foreach ($order->items as $item)
                                    <x-shop::accordion :is-active="false">
                                        <x-slot:header class="bg-gray-100 max-sm:mb-2">
                                            <p class="text-base font-medium 1180:hidden">
                                                {{ $item->name }}

                                                @if (isset($item->additional['attributes']))
                                                    <div>
                                                        @foreach ($item->additional['attributes'] as $attribute)
                                                            <b>{{ $attribute['attribute_name'] }} : </b>{{ $attribute['option_label'] }}<br>
                                                        @endforeach
                                                    </div>
                                                @endif
                                            </p>
                                        </x-slot>
                            
                                        <x-slot:content class="max-sm:px-0">
                                            <div class="mb-5 text-lg max-1180:text-sm max-sm:mb-0 max-sm:grid max-sm:gap-2.5 max-sm:text-sm max-sm:font-normal">
                                                <!-- SKU -->
                                                <div class="flex justify-between">
                                                    <span>
                                                        @lang('shop::app.customers.account.orders.view.information.sku'): 
                                                    </span>

                                                    <span>
                                                        {{ $item->getTypeInstance()->getOrderedItem($item)->sku }}
                                                    </span>
                                                </div>

                                                <!-- Name -->
                                                <div class="flex justify-between">
                                                    <span>
                                                        @lang('shop::app.customers.account.orders.view.invoices.product-name'):    
                                                    </span>

                                                    <span>
                                                        {{ $item->name }}
                                                    </span>
                                                </div>

                                                <!-- Price -->
                                                <div class="flex justify-between">
                                                    <span>
                                                        @lang('shop::app.customers.account.orders.view.information.price'):
                                                    </span>

                                                    <span>
                                                        @if (core()->getConfigData('sales.taxes.sales.display_prices') == 'including_tax')
                                                            {{ core()->formatPrice($item->price_incl_tax, $order->order_currency_code) }}
                                                        @elseif (core()->getConfigData('sales.taxes.sales.display_prices') == 'both')
                                                            {{ core()->formatPrice($item->price_incl_tax, $order->order_currency_code) }}
        
                                                            <span class="whitespace-nowrap text-xs font-normal">
                                                                @lang('shop::app.customers.account.orders.view.information.excl-tax')
                                                                
                                                                <span class="font-medium">
                                                                    {{ core()->formatPrice($item->price, $order->order_currency_code) }}
                                                                </span>
                                                            </span>
                                                        @else
                                                            {{ core()->formatPrice($item->price, $order->order_currency_code) }}
                                                        @endif
                                                    </span>
                                                </div>

                                                <!-- Quantity -->
                                                <div class="flex justify-between">
                                                    <span>
                                                        @lang('shop::app.customers.account.orders.view.information.item-status')
                                                    </span>

                                                    <div>
                                                        @if($item->qty_ordered)
                                                            <p>
                                                                @lang('shop::app.customers.account.orders.view.information.item-ordered', ['qty_ordered' => $item->qty_ordered])
                                                            </p>
                                                        @endif
        
                                                        @if($item->qty_invoiced)
                                                            <p>
                                                                @lang('shop::app.customers.account.orders.view.information.item-invoice', ['qty_invoiced' => $item->qty_invoiced])
                                                            </p>
                                                        @endif
        
                                                        @if($item->qty_shipped)
                                                            <p>
                                                                @lang('shop::app.customers.account.orders.view.information.item-shipped', ['qty_shipped' => $item->qty_shipped])
                                                            </p>
                                                        @endif
        
                                                        @if($item->qty_refunded)
                                                            <span>
                                                                @lang('shop::app.customers.account.orders.view.information.item-refunded', ['qty_refunded' => $item->qty_refunded])
                                                            </span>
                                                        @endif
        
                                                        @if($item->qty_canceled)
                                                            <p>
                                                                @lang('shop::app.customers.account.orders.view.information.item-canceled', ['qty_canceled' => $item->qty_canceled])
                                                                
                                                            </p>
                                                        @endif
                                                    </div>
                                                </div>

                                                <!-- Sub Total -->
                                                <div class="flex justify-between">
                                                    <span>
                                                        @lang('shop::app.customers.account.orders.view.invoices.subtotal'): 
                                                    </span>

                                                    <span>
                                                        @if (core()->getConfigData('sales.taxes.sales.display_prices') == 'including_tax')
                                                            {{ core()->formatPrice($item->total_incl_tax, $order->order_currency_code) }}
                                                        @elseif (core()->getConfigData('sales.taxes.sales.display_prices') == 'both')
                                                            {{ core()->formatPrice($item->total_incl_tax, $order->order_currency_code) }}

                                                            <span class="whitespace-nowrap text-xs font-normal">
                                                                @lang('shop::app.customers.account.orders.view.invoices.excl-tax')
                                                                
                                                                <span class="font-medium">
                                                                    {{ core()->formatPrice($item->total, $order->order_currency_code) }}
                                                                </span>
                                                            </span>
                                                        @else
                                                            {{ core()->formatPrice($item->total, $order->order_currency_code) }}
                                                        @endif
                                                    </span>
                                                </div>
                                            </div>
                                        </x-slot>
                                    </x-shop::accordion>
                                @endforeach
                            </div>
                        </div>

                    </div>

                    <div class="relative mt-8 overflow-x-auto rounded-xl border max-sm:hidden">
                        <table class="w-full text-left text-sm">
                            <thead class="border-b border-zinc-200 bg-zinc-100 text-sm text-black">
                                <tr>
                                    <th
                                        scope="col"
                                        class="px-6 py-4 font-medium"
                                    >
                                        @lang('shop::app.customers.account.orders.view.information.sku')
                                    </th>

                                    <th
                                        scope="col"
                                        class="px-6 py-4 font-medium"
                                    >
                                        @lang('shop::app.customers.account.orders.view.information.product-name')
                                    </th>

                                    <th
                                        scope="col"
                                        class="px-6 py-4 font-medium"
                                    >
                                        @lang('shop::app.customers.account.orders.view.information.price')
                                    </th>

                                    <th
                                        scope="col"
                                        class="px-6 py-4 font-medium"
                                    >
                                        @lang('shop::app.customers.account.orders.view.information.item-status')
                                    </th>

                                    <th
                                        scope="col"
                                        class="px-6 py-4 font-medium"
                                    >
                                        @lang('shop::app.customers.account.orders.view.information.subtotal')
                                    </th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($order->items as $item)
                                    <tr class="border-b bg-white align-top">
                                        <td
                                            class="px-6 py-4 font-medium text-black"
                                            data-value="@lang('shop::app.customers.account.orders.view.information.sku')"
                                        >
                                            {{ $item->getTypeInstance()->getOrderedItem($item)->sku }}
                                        </td>

                                        <td
                                            class="px-6 py-4 font-medium text-black"
                                            data-value="@lang('shop::app.customers.account.orders.view.information.product-name')"
                                        >
                                            {{ $item->name }}

                                            @if (isset($item->additional['attributes']))
                                                <div>
                                                    @foreach ($item->additional['attributes'] as $attribute)
                                                        <b>{{ $attribute['attribute_name'] }} : </b>{{ $attribute['option_label'] }}<br>
                                                    @endforeach
                                                </div>
                                            @endif
                                        </td>

                                        <td
                                            class="flex flex-col px-6 py-4 font-medium text-black"
                                            data-value="@lang('shop::app.customers.account.orders.view.information.price')"
                                        >
                                            @if (core()->getConfigData('sales.taxes.sales.display_prices') == 'including_tax')
                                                {{ core()->formatPrice($item->price_incl_tax, $order->order_currency_code) }}
                                            @elseif (core()->getConfigData('sales.taxes.sales.display_prices') == 'both')
                                                {{ core()->formatPrice($item->price_incl_tax, $order->order_currency_code) }}

                                                <span class="whitespace-nowrap text-xs font-normal">
                                                    @lang('shop::app.customers.account.orders.view.information.excl-tax')
                                                    
                                                    <span class="font-medium">
                                                        {{ core()->formatPrice($item->price, $order->order_currency_code) }}
                                                    </span>
                                                </span>
                                            @else
                                                {{ core()->formatPrice($item->price, $order->order_currency_code) }}
                                            @endif
                                        </td>

                                        <td
                                            class="px-6 py-4 font-medium text-black"
                                            data-value= "@lang('shop::app.customers.account.orders.view.information.item-status')"
                                        >
                                            <span>
                                                @if($item->qty_ordered)
                                                    @lang('shop::app.customers.account.orders.view.information.item-ordered', ['qty_ordered' => $item->qty_ordered])
                                                @endif
                                            </span>

                                            <span>
                                                @if($item->qty_invoiced)
                                                    @lang('shop::app.customers.account.orders.view.information.item-invoice', ['qty_invoiced' => $item->qty_invoiced])
                                                @endif
                                            </span>

                                            <span>
                                                @if($item->qty_shipped)
                                                    @lang('shop::app.customers.account.orders.view.information.item-shipped', ['qty_shipped' => $item->qty_shipped])
                                                @endif
                                            </span>

                                            <span>
                                                @if($item->qty_refunded)
                                                    @lang('shop::app.customers.account.orders.view.information.item-refunded', ['qty_refunded' => $item->qty_refunded])
                                                @endif
                                            </span>

                                            <span>
                                                @if($item->qty_canceled)
                                                    @lang('shop::app.customers.account.orders.view.information.item-canceled', ['qty_canceled' => $item->qty_canceled])
                                                @endif
                                            </span>
                                        </td>

                                        <td
                                            class="flex flex-col px-6 py-4 font-medium text-black"
                                            data-value="@lang('shop::app.customers.account.orders.view.information.subtotal')"
                                        >
                                            @if (core()->getConfigData('sales.taxes.sales.display_prices') == 'including_tax')
                                                {{ core()->formatPrice($item->total_incl_tax, $order->order_currency_code) }}
                                            @elseif (core()->getConfigData('sales.taxes.sales.display_prices') == 'both')
                                                {{ core()->formatPrice($item->total_incl_tax, $order->order_currency_code) }}

                                                <span class="whitespace-nowrap text-xs font-normal">
                                                    @lang('shop::app.customers.account.orders.view.information.excl-tax')
                                                    
                                                    <span class="font-medium">
                                                        {{ core()->formatPrice($item->total, $order->order_currency_code) }}
                                                    </span>
                                                </span>
                                            @else
                                                {{ core()->formatPrice($item->total, $order->order_currency_code) }}
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-8 flex items-start gap-10 max-lg:gap-5 max-md:grid max-sm:mt-4">
                        <div class="flex-auto">
                            <div class="flex justify-end">
                                <div class="grid max-w-max gap-2 text-sm">
                                    @if (core()->getConfigData('sales.taxes.sales.display_subtotal') == 'including_tax')
                                        <div class="flex w-full justify-between gap-x-5">
                                            <p>
                                                @lang('shop::app.customers.account.orders.view.information.subtotal')
                                            </p>

                                            <p>
                                                {{ core()->formatPrice($order->sub_total_incl_tax, $order->order_currency_code) }}
                                            </p>
                                        </div>
                                    @elseif (core()->getConfigData('sales.taxes.sales.display_subtotal') == 'both')
                                        <div class="flex w-full justify-between gap-x-5">
                                            <p>
                                                @lang('shop::app.customers.account.orders.view.information.subtotal-excl-tax')
                                            </p>

                                            <p>
                                                {{ core()->formatPrice($order->sub_total, $order->order_currency_code) }}
                                            </p>
                                        </div>
                                        
                                        <div class="flex w-full justify-between gap-x-5">
                                            <p>
                                                @lang('shop::app.customers.account.orders.view.information.subtotal-incl-tax')
                                            </p>

                                            <p>
                                                {{ core()->formatPrice($order->sub_total_incl_tax, $order->order_currency_code) }}
                                            </p>
                                        </div>
                                    @else
                                        <div class="flex w-full justify-between gap-x-5">
                                            <p>
                                                @lang('shop::app.customers.account.orders.view.information.subtotal')
                                            </p>

                                            <p>
                                                {{ core()->formatPrice($order->sub_total, $order->order_currency_code) }}
                                            </p>
                                        </div>
                                    @endif

                                    @if ($order->haveStockableItems())
                                        @if (core()->getConfigData('sales.taxes.sales.display_shipping_amount') == 'including_tax')
                                            <div class="flex w-full justify-between gap-x-5">
                                                <p>
                                                    @lang('shop::app.customers.account.orders.view.information.shipping-handling')
                                                </p>

                                                <p>
                                                    {{ core()->formatPrice($order->shipping_amount_incl_tax, $order->order_currency_code) }}
                                                </p>
                                            </div>
                                        @elseif (core()->getConfigData('sales.taxes.sales.display_shipping_amount') == 'both')
                                            <div class="flex w-full justify-between gap-x-5">
                                                <p>
                                                    @lang('shop::app.customers.account.orders.view.information.shipping-handling-excl-tax')
                                                </p>

                                                <p>
                                                    {{ core()->formatPrice($order->shipping_amount, $order->order_currency_code) }}
                                                </p>
                                            </div>
                                            
                                            <div class="flex w-full justify-between gap-x-5">
                                                <p>
                                                    @lang('shop::app.customers.account.orders.view.information.shipping-handling-incl-tax')
                                                </p>

                                                <p>
                                                    {{ core()->formatPrice($order->shipping_amount_incl_tax, $order->order_currency_code) }}
                                                </p>
                                            </div>
                                        @else
                                            <div class="flex w-full justify-between gap-x-5">
                                                <p>
                                                    @lang('shop::app.customers.account.orders.view.information.shipping-handling')
                                                </p>

                                                <p>
                                                    {{ core()->formatPrice($order->shipping_amount, $order->order_currency_code) }}
                                                </p>
                                            </div>
                                        @endif
                                    @endif

                                    <div class="flex w-full justify-between gap-x-5">
                                        <p>
                                            @lang('shop::app.customers.account.orders.view.information.tax')
                                        </p>

                                        <p>
                                            {{ core()->formatPrice($order->tax_amount, $order->order_currency_code) }}
                                        </p>
                                    </div>

                                    @if ($order->base_discount_amount > 0)
                                        <div class="flex w-full justify-between gap-x-5">
                                            <p>
                                                @lang('shop::app.customers.account.orders.view.information.discount')

                                                @if ($order->coupon_code)
                                                    ({{ $order->coupon_code }})
                                                @endif
                                            </p>

                                            <p>
                                                {{ core()->formatPrice($order->discount_amount, $order->order_currency_code) }}
                                            </p>
                                        </div>
                                    @endif

                                    <div class="flex w-full justify-between gap-x-5 font-semibold">
                                        <p>
                                            @lang('shop::app.customers.account.orders.view.information.grand-total')
                                        </p>

                                        <p>
                                            {{ core()->formatPrice($order->grand_total, $order->order_currency_code) }}
                                        </p>
                                    </div>

                                    <div class="flex w-full justify-between gap-x-5">
                                        <p>
                                            @lang('shop::app.customers.account.orders.view.information.total-paid')
                                        </p>

                                        <p>
                                            {{ core()->formatPrice($order->grand_total_invoiced, $order->order_currency_code) }}
                                        </p>
                                    </div>

                                    <div class="flex w-full justify-between gap-x-5">
                                        <p>
                                            @lang('shop::app.customers.account.orders.view.information.total-refunded')
                                        </p>

                                        <p>
                                            {{ core()->formatPrice($order->grand_total_refunded, $order->order_currency_code) }}
                                        </p>
                                    </div>
                                    
                                    <div class="flex w-full justify-between gap-x-5">
                                        <p>
                                            @lang('shop::app.customers.account.orders.view.information.total-due')
                                        </p>

                                        <p>
                                            @if($order->status !== \Webkul\Sales\Models\Order::STATUS_CANCELED)
                                                {{ core()->formatPrice($order->total_due, $order->order_currency_code) }}
                                            @else
                                                {{ core()->formatPrice(0.00, $order->order_currency_code) }}
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </x-shop::tabs.item>

                @if ($order->invoices->count())
                    <x-shop::tabs.item
                        class="!px-0 py-2"
                        :title="trans('shop::app.customers.account.orders.view.invoices.invoices')"
                    >
                        @foreach ($order->invoices as $invoice)
                            <div class="grid gap-8 sm:hidden">
                                <div class="grid gap-1.5 rounded-lg border px-4 py-2.5 font-medium">
                                    <div class="flex justify-between">
                                        @lang('shop::app.customers.account.orders.view.invoices.individual-invoice', ['invoice_id' => $invoice->increment_id ?? $invoice->id])
        
                                        <a href="{{ route('shop.customers.account.orders.print-invoice', $invoice->id) }}">
                                            <div class="flex items-center gap-1 font-normal">
                                                <span class="icon-download"></span>

                                                @lang('shop::app.customers.account.orders.view.invoices.print')
                                            </div>
                                        </a>
                                    </div>
                                </div>

                                <div>
                                    <p class="mb-2.5 text-base font-medium">Item Invoiced</p>

                                    <div class="grid gap-3">
                                        @foreach ($invoice->items as $item)
                                            <x-shop::accordion :is-active="false">
                                                <x-slot:header class="bg-gray-100 max-sm:mb-2">
                                                    <p class="text-base font-medium 1180:hidden">
                                                        {{ $item->name }}
                                                    </p>
                                                </x-slot>

                                                <x-slot:content class="max-sm:px-0">
                                                    <div class="mb-5 text-lg max-1180:text-sm max-sm:mb-0 max-sm:grid max-sm:gap-2.5 max-sm:px-5 max-sm:text-sm max-sm:font-normal">
                                                        <!-- SKU -->
                                                        <div class="flex justify-between">
                                                            <span>
                                                                @lang('shop::app.customers.account.orders.view.invoices.sku'):
                                                            </span>

                                                            <span>
                                                                {{ $item->getTypeInstance()->getOrderedItem($item)->sku }}
                                                            </span>
                                                        </div>

                                                        <!-- Name -->
                                                        <div class="flex justify-between">
                                                            <span>
                                                                @lang('shop::app.customers.account.orders.view.invoices.product-name'):    
                                                            </span>

                                                            <span>
                                                                {{ $item->name }}
                                                            </span>
                                                        </div>

                                                        <!-- Price -->
                                                        <div class="flex justify-between">
                                                            <span>@lang('shop::app.customers.account.orders.view.invoices.price'): </span>

                                                            <span>
                                                                @if (core()->getConfigData('sales.taxes.sales.display_prices') == 'including_tax')
                                                                    {{ core()->formatPrice($item->price_incl_tax, $order->order_currency_code) }}
                                                                @elseif (core()->getConfigData('sales.taxes.sales.display_prices') == 'both')
                                                                    {{ core()->formatPrice($item->price_incl_tax, $order->order_currency_code) }}
        
                                                                    <span class="whitespace-nowrap text-xs font-normal">
                                                                        @lang('shop::app.customers.account.orders.view.information.excl-tax')
                                                                        
                                                                        <span class="font-medium">
                                                                            {{ core()->formatPrice($item->price, $order->order_currency_code) }}
                                                                        </span>
                                                                    </span>
                                                                @else
                                                                    {{ core()->formatPrice($item->price, $order->order_currency_code) }}
                                                                @endif
                                                            </span>
                                                        </div>

                                                        <!-- Quantity -->
                                                        <div class="flex justify-between">
                                                            <span>
                                                                @lang('shop::app.customers.account.orders.view.invoices.qty'):  {{ $item->qty }}
                                                            </span>

                                                            <span>
                                                                {{ $item->qty }}
                                                            </span>
                                                        </div>

                                                        <!-- Sub Total -->
                                                        <div class="flex justify-between">
                                                            <span>
                                                                @lang('shop::app.customers.account.orders.view.invoices.subtotal'): 
                                                            </span>

                                                            <span>
                                                                @if (core()->getConfigData('sales.taxes.sales.display_prices') == 'including_tax')
                                                                    {{ core()->formatPrice($item->total_incl_tax, $order->order_currency_code) }}
                                                                @elseif (core()->getConfigData('sales.taxes.sales.display_prices') == 'both')
                                                                    {{ core()->formatPrice($item->total_incl_tax, $order->order_currency_code) }}

                                                                    <span class="whitespace-nowrap text-xs font-normal">
                                                                        @lang('shop::app.customers.account.orders.view.invoices.excl-tax')
                                                                        
                                                                        <span class="font-medium">
                                                                            {{ core()->formatPrice($item->total, $order->order_currency_code) }}
                                                                        </span>
                                                                    </span>
                                                                @else
                                                                    {{ core()->formatPrice($item->total, $order->order_currency_code) }}
                                                                @endif
                                                            </span>
                                                        </div>
                                                    </div>
                                                </x-slot>
                                            </x-shop::accordion>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            <div class="relative mt-8 overflow-x-auto rounded-xl border max-sm:hidden">
                                <table class="w-full text-left text-sm">
                                    <thead class="border-b border-zinc-200 bg-zinc-100 text-sm text-black">
                                        <tr>
                                            <th
                                                scope="col"
                                                class="px-6 py-4 font-medium"
                                            >
                                                @lang('shop::app.customers.account.orders.view.invoices.sku')
                                            </th>

                                            <th
                                                scope="col"
                                                class="px-6 py-4 font-medium"
                                            >
                                                @lang('shop::app.customers.account.orders.view.invoices.product-name')
                                            </th>

                                            <th
                                                scope="col"
                                                class="px-6 py-4 font-medium"
                                            >
                                                @lang('shop::app.customers.account.orders.view.invoices.price')
                                            </th>

                                            <th
                                                scope="col"
                                                class="px-6 py-4 font-medium"
                                            >
                                                @lang('shop::app.customers.account.orders.view.invoices.qty')
                                            </th>

                                            <th
                                                scope="col"
                                                class="px-6 py-4 font-medium"
                                            >
                                                @lang('shop::app.customers.account.orders.view.invoices.subtotal')
                                            </th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @foreach ($invoice->items as $item)
                                            <tr class="border-b bg-white">
                                                <td
                                                    class="px-6 py-4 font-medium text-black"
                                                    data-value="@lang('shop::app.customers.account.orders.view.invoices.sku')"
                                                >
                                                    {{ $item->getTypeInstance()->getOrderedItem($item)->sku }}
                                                </td>

                                                <td
                                                    class="px-6 py-4 font-medium text-black"
                                                    data-value="@lang('shop::app.customers.account.orders.view.invoices.product-name')"
                                                >
                                                    {{ $item->name }}
                                                </td>

                                                <td
                                                    class="flex flex-col px-6 py-4 font-medium text-black"
                                                    data-value="@lang('shop::app.customers.account.orders.view.invoices.price')"
                                                >
                                                    @if (core()->getConfigData('sales.taxes.sales.display_prices') == 'including_tax')
                                                        {{ core()->formatPrice($item->price_incl_tax, $order->order_currency_code) }}
                                                    @elseif (core()->getConfigData('sales.taxes.sales.display_prices') == 'both')
                                                        {{ core()->formatPrice($item->price_incl_tax, $order->order_currency_code) }}

                                                        <span class="whitespace-nowrap text-xs font-normal">
                                                            @lang('shop::app.customers.account.orders.view.information.excl-tax')
                                                            
                                                            <span class="font-medium">
                                                                {{ core()->formatPrice($item->price, $order->order_currency_code) }}
                                                            </span>
                                                        </span>
                                                    @else
                                                        {{ core()->formatPrice($item->price, $order->order_currency_code) }}
                                                    @endif
                                                </td>

                                                <td
                                                    class="px-6 py-4 font-medium text-black"
                                                    data-value="@lang('shop::app.customers.account.orders.view.invoices.qty')"
                                                >
                                                    {{ $item->qty }}
                                                </td>

                                                <td
                                                    class="flex flex-col px-6 py-4 font-medium text-black"
                                                    data-value="@lang('shop::app.customers.account.orders.view.invoices.subtotal')"
                                                >
                                                    @if (core()->getConfigData('sales.taxes.sales.display_prices') == 'including_tax')
                                                        {{ core()->formatPrice($item->total_incl_tax, $order->order_currency_code) }}
                                                    @elseif (core()->getConfigData('sales.taxes.sales.display_prices') == 'both')
                                                        {{ core()->formatPrice($item->total_incl_tax, $order->order_currency_code) }}

                                                        <span class="whitespace-nowrap text-xs font-normal">
                                                            @lang('shop::app.customers.account.orders.view.invoices.excl-tax')
                                                            
                                                            <span class="font-medium">
                                                                {{ core()->formatPrice($item->total, $order->order_currency_code) }}
                                                            </span>
                                                        </span>
                                                    @else
                                                        {{ core()->formatPrice($item->total, $order->order_currency_code) }}
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <div class="mt-8 flex items-start gap-10 max-lg:gap-5 max-md:grid max-sm:mt-4">
                                <div class="flex-auto">
                                    <div class="flex justify-end">
                                        <div class="grid max-w-max gap-2 text-sm">
                                            @if (core()->getConfigData('sales.taxes.sales.display_subtotal') == 'including_tax')
                                                <div class="flex w-full justify-between gap-x-5">
                                                    <p>
                                                        @lang('shop::app.customers.account.orders.view.invoices.subtotal')
                                                    </p>

                                                    <p>
                                                        {{ core()->formatPrice($invoice->sub_total_incl_tax, $order->order_currency_code) }}
                                                    </p>
                                                </div>
                                            @elseif (core()->getConfigData('sales.taxes.sales.display_subtotal') == 'both')
                                                <div class="flex w-full justify-between gap-x-5">
                                                    <p>
                                                        @lang('shop::app.customers.account.orders.view.invoices.subtotal-excl-tax')
                                                    </p>

                                                    <p>
                                                        {{ core()->formatPrice($invoice->sub_total, $order->order_currency_code) }}
                                                    </p>
                                                </div>
                                                
                                                <div class="flex w-full justify-between gap-x-5">
                                                    <p>
                                                        @lang('shop::app.customers.account.orders.view.invoices.subtotal-incl-tax')
                                                    </p>

                                                    <p>
                                                        {{ core()->formatPrice($invoice->sub_total_incl_tax, $order->order_currency_code) }}
                                                    </p>
                                                </div>
                                            @else
                                                <div class="flex w-full justify-between gap-x-5">
                                                    <p>
                                                        @lang('shop::app.customers.account.orders.view.invoices.subtotal')
                                                    </p>

                                                    <p>
                                                        {{ core()->formatPrice($invoice->sub_total, $order->order_currency_code) }}
                                                    </p>
                                                </div>
                                            @endif

                                            @if (core()->getConfigData('sales.taxes.sales.display_shipping_amount') == 'including_tax')
                                                <div class="flex w-full justify-between gap-x-5">
                                                    <p>
                                                        @lang('shop::app.customers.account.orders.view.invoices.shipping-handling')
                                                    </p>

                                                    <p>
                                                        {{ core()->formatPrice($invoice->shipping_amount_incl_tax, $order->order_currency_code) }}
                                                    </p>
                                                </div>
                                            @elseif (core()->getConfigData('sales.taxes.sales.display_shipping_amount') == 'both')
                                                <div class="flex w-full justify-between gap-x-5">
                                                    <p>
                                                        @lang('shop::app.customers.account.orders.view.invoices.shipping-handling-excl-tax')
                                                    </p>

                                                    <p>
                                                        {{ core()->formatPrice($invoice->shipping_amount, $order->order_currency_code) }}
                                                    </p>
                                                </div>
                                                
                                                <div class="flex w-full justify-between gap-x-5">
                                                    <p>
                                                        @lang('shop::app.customers.account.orders.view.invoices.shipping-handling-incl-tax')
                                                    </p>

                                                    <p>
                                                        {{ core()->formatPrice($invoice->shipping_amount_incl_tax, $order->order_currency_code) }}
                                                    </p>
                                                </div>
                                            @else
                                                <div class="flex w-full justify-between gap-x-5">
                                                    <p>
                                                        @lang('shop::app.customers.account.orders.view.invoices.shipping-handling')
                                                    </p>

                                                    <p>
                                                        {{ core()->formatPrice($invoice->shipping_amount, $order->order_currency_code) }}
                                                    </p>
                                                </div>
                                            @endif

                                            @if ($invoice->base_discount_amount > 0)
                                                <div class="flex w-full justify-between gap-x-5">
                                                    <p>
                                                        @lang('shop::app.customers.account.orders.view.invoices.discount')
                                                    </p>

                                                    <p>
                                                        {{ core()->formatPrice($invoice->discount_amount, $order->order_currency_code) }}
                                                    </p>
                                                </div>
                                            @endif

                                            <div class="flex w-full justify-between gap-x-5">
                                                <p>
                                                    @lang('shop::app.customers.account.orders.view.invoices.tax')
                                                </p>

                                                <p>
                                                    {{ core()->formatPrice($invoice->tax_amount, $order->order_currency_code) }}
                                                </p>
                                            </div>

                                            <div class="flex w-full justify-between gap-x-5 font-semibold">
                                                <p>
                                                    @lang('shop::app.customers.account.orders.view.invoices.grand-total')
                                                </p>

                                                <p>
                                                    {{ core()->formatPrice($invoice->grand_total, $order->order_currency_code) }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach

                    </x-shop::tabs.item>
                @endif

                <!-- Shipment tab -->
                @if ($order->shipments->count())
                    <x-shop::tabs.item title="{{ trans('shop::app.customers.account.orders.view.shipments.shipments') }}">
                        @foreach ($order->shipments as $shipment)
                            <div class="max-sm:hidden">
                                <label class="text-base font-medium">
                                    @lang('shop::app.customers.account.orders.view.shipments.tracking-number')
                                </label>

                                <span>
                                    {{  $shipment->track_number }}
                                </span>
                            </div>

                            <div class="text-base font-medium max-sm:hidden">
                                <span>
                                    @lang('shop::app.customers.account.orders.view.shipments.individual-shipment', ['shipment_id' => $shipment->id])
                                </span>
                            </div>

                            <div class="grid gap-1.5 rounded-lg border px-4 py-2.5 font-medium sm:hidden">
                                <span>
                                    @lang('shop::app.customers.account.orders.view.shipments.tracking-number'): {{  $shipment->track_number }}
                                </span>

                                <span>
                                    @lang('shop::app.customers.account.orders.view.shipments.individual-shipment', ['shipment_id' => $shipment->id])
                                </span>
                            </div>

                            <div class="relative mt-8 overflow-x-auto rounded-xl border">
                                <table class="w-full text-left text-sm">
                                    <thead class="border-b border-zinc-200 bg-zinc-100 text-sm text-black">
                                        <tr>
                                            <th
                                                scope="col"
                                                class="px-6 py-4 font-medium"
                                            >
                                                @lang('shop::app.customers.account.orders.view.shipments.sku')
                                            </th>

                                            <th
                                                scope="col"
                                                class="px-6 py-4 font-medium"
                                            >
                                                @lang('shop::app.customers.account.orders.view.shipments.product-name')
                                            </th>

                                            <th
                                                scope="col"
                                                class="px-6 py-4 font-medium"
                                            >
                                                @lang('shop::app.customers.account.orders.view.shipments.qty')
                                            </th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @foreach ($shipment->items as $item)
                                            <tr class="border-b bg-white">
                                                <td
                                                    class="px-6 py-4 font-medium text-black"
                                                    data-value="@lang('shop::app.customers.account.orders.view.shipments.sku')"
                                                >
                                                    {{ $item->sku }}
                                                </td>

                                                <td
                                                    class="px-6 py-4 font-medium text-black"
                                                    data-value="@lang('shop::app.customers.account.orders.view.shipments.product-name')"
                                                >
                                                    {{ $item->name }}
                                                </td>

                                                <td
                                                    class="px-6 py-4 font-medium text-black"
                                                    data-value="@lang('shop::app.customers.account.orders.view.shipments.qty')"
                                                >
                                                    {{ $item->qty }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endforeach
                    </x-shop::tabs.item>
                @endif

                <!-- Refund Tab -->
                @if ($order->refunds->count())
                    <x-shop::tabs.item :title="trans('shop::app.customers.account.orders.view.refunds.refunds')">

                        @foreach ($order->refunds as $refund)
                            <div class="text-base font-medium max-sm:hidden">
                                <span>
                                    @lang('shop::app.customers.account.orders.view.refunds.individual-refund', ['refund_id' => $refund->id])
                                </span>
                            </div>

                            <div class="grid gap-1.5 rounded-lg border px-4 py-2.5 font-medium sm:hidden">
                                @lang('shop::app.customers.account.orders.view.refunds.individual-refund', ['refund_id' => $refund->id])
                            </div>

                            <div class="sm:hidden">
                                <p class="mb-2.5 text-base font-medium">Item Refunded</p>

                                <div class="grid gap-3">
                                    @foreach ($invoice->items as $item)
                                        <x-shop::accordion :is-active="false">
                                            <x-slot:header class="bg-gray-100 max-sm:mb-2">
                                                <p class="text-base font-medium 1180:hidden">
                                                    {{ $item->name }}
                                                </p>
                                            </x-slot>

                                            <x-slot:content class="max-sm:px-0">
                                                <div class="mb-5 text-lg max-1180:text-sm max-sm:mb-0 max-sm:grid max-sm:gap-2.5 max-sm:px-5 max-sm:text-sm max-sm:font-normal">
                                                    <!-- SKU -->
                                                    <div class="flex justify-between">
                                                        <span>
                                                            @lang('shop::app.customers.account.orders.view.refunds.sku'):
                                                        </span>

                                                        <span>
                                                            {{ $item->child ? $item->child->sku : $item->sku }}
                                                        </span>
                                                    </div>

                                                    <!-- Name -->
                                                    <div class="flex justify-between">
                                                        <span>
                                                            @lang('shop::app.customers.account.orders.view.refunds.product-name'):    
                                                        </span>

                                                        <span>
                                                            {{ $item->name }}
                                                        </span>
                                                    </div>

                                                    <!-- Price -->
                                                    <div class="flex justify-between">
                                                        <span>@lang('shop::app.customers.account.orders.view.refunds.price'): </span>

                                                        <span>
                                                            @if (core()->getConfigData('sales.taxes.sales.display_prices') == 'including_tax')
                                                                {{ core()->formatPrice($item->price_incl_tax, $order->order_currency_code) }}
                                                            @elseif (core()->getConfigData('sales.taxes.sales.display_prices') == 'both')
                                                                {{ core()->formatPrice($item->price_incl_tax, $order->order_currency_code) }}
        
                                                                <span class="whitespace-nowrap text-xs font-normal">
                                                                    @lang('shop::app.customers.account.orders.view.information.excl-tax')
                                                                    
                                                                    <span class="font-medium">
                                                                        {{ core()->formatPrice($item->price, $order->order_currency_code) }}
                                                                    </span>
                                                                </span>
                                                            @else
                                                                {{ core()->formatPrice($item->price, $order->order_currency_code) }}
                                                            @endif
                                                        </span>
                                                    </div>

                                                    <!-- Quantity -->
                                                    <div class="flex justify-between">
                                                        <span>
                                                            @lang('shop::app.customers.account.orders.view.refunds.qty')
                                                        </span>

                                                        <span>
                                                            {{ $item->qty }}
                                                        </span>
                                                    </div>

                                                    <!-- Sub Total -->
                                                    <div class="flex justify-between">
                                                        <span>
                                                            @lang('shop::app.customers.account.orders.view.refunds.subtotal'): 
                                                        </span>

                                                        <span>
                                                            @if (core()->getConfigData('sales.taxes.sales.display_prices') == 'including_tax')
                                                                {{ core()->formatPrice($item->total_incl_tax, $order->order_currency_code) }}
                                                            @elseif (core()->getConfigData('sales.taxes.sales.display_prices') == 'both')
                                                                {{ core()->formatPrice($item->total_incl_tax, $order->order_currency_code) }}

                                                                <span class="whitespace-nowrap text-xs font-normal">
                                                                    @lang('shop::app.customers.account.orders.view.information.excl-tax')
                                                                    
                                                                    <span class="font-medium">
                                                                        {{ core()->formatPrice($item->total, $order->order_currency_code) }}
                                                                    </span>
                                                                </span>
                                                            @else
                                                                {{ core()->formatPrice($item->total, $order->order_currency_code) }}
                                                            @endif
                                                        </span>
                                                    </div>
                                                </div>
                                            </x-slot>
                                        </x-shop::accordion>
                                    @endforeach
                                </div>
                            </div>

                            <div class="relative mt-8 overflow-x-auto rounded-xl border max-sm:hidden">
                                <table class="w-full text-left text-sm">
                                    <thead class="border-b border-zinc-200 bg-zinc-100 text-sm text-black">
                                        <tr>
                                            <th
                                                scope="col"
                                                class="px-6 py-4 font-medium"
                                            >
                                                @lang('shop::app.customers.account.orders.view.refunds.sku')
                                            </th>

                                            <th
                                                scope="col"
                                                class="px-6 py-4 font-medium"
                                            >
                                                @lang('shop::app.customers.account.orders.view.refunds.product-name')
                                            </th>

                                            <th
                                                scope="col"
                                                class="px-6 py-4 font-medium"
                                            >
                                                @lang('shop::app.customers.account.orders.view.refunds.price')
                                            </th>

                                            <th
                                                scope="col"
                                                class="px-6 py-4 font-medium"
                                            >
                                                @lang('shop::app.customers.account.orders.view.refunds.qty')
                                            </th>

                                            <th
                                                scope="col"
                                                class="px-6 py-4 font-medium"
                                            >
                                                @lang('shop::app.customers.account.orders.view.refunds.subtotal')
                                            </th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @foreach ($refund->items as $item)
                                            <tr class="border-b bg-white">
                                                <td
                                                    class="px-6 py-4 font-medium text-black"
                                                    data-value="@lang('shop::app.customers.account.orders.view.refunds.sku')"
                                                >
                                                    {{ $item->child ? $item->child->sku : $item->sku }}
                                                </td>

                                                <td
                                                    class="px-6 py-4 font-medium text-black"
                                                    data-value="@lang('shop::app.customers.account.orders.view.refunds.product-name')"
                                                >
                                                    {{ $item->name }}
                                                </td>

                                                <td
                                                    class="flex flex-col px-6 py-4 font-medium text-black"
                                                    data-value="@lang('shop::app.customers.account.orders.view.refunds.price')"
                                                >
                                                    @if (core()->getConfigData('sales.taxes.sales.display_prices') == 'including_tax')
                                                        {{ core()->formatPrice($item->price_incl_tax, $order->order_currency_code) }}
                                                    @elseif (core()->getConfigData('sales.taxes.sales.display_prices') == 'both')
                                                        {{ core()->formatPrice($item->price_incl_tax, $order->order_currency_code) }}

                                                        <span class="whitespace-nowrap text-xs font-normal">
                                                            @lang('shop::app.customers.account.orders.view.information.excl-tax')
                                                            
                                                            <span class="font-medium">
                                                                {{ core()->formatPrice($item->price, $order->order_currency_code) }}
                                                            </span>
                                                        </span>
                                                    @else
                                                        {{ core()->formatPrice($item->price, $order->order_currency_code) }}
                                                    @endif
                                                </td>

                                                <td
                                                    class="px-6 py-4 font-medium text-black"
                                                    data-value="@lang('shop::app.customers.account.orders.view.refunds.qty')"
                                                >
                                                    {{ $item->qty }}
                                                </td>

                                                <td
                                                    class="flex flex-col px-6 py-4 font-medium text-black"
                                                    data-value="@lang('shop::app.customers.account.orders.view.refunds.subtotal')"
                                                >
                                                    @if (core()->getConfigData('sales.taxes.sales.display_prices') == 'including_tax')
                                                        {{ core()->formatPrice($item->total_incl_tax, $order->order_currency_code) }}
                                                    @elseif (core()->getConfigData('sales.taxes.sales.display_prices') == 'both')
                                                        {{ core()->formatPrice($item->total_incl_tax, $order->order_currency_code) }}

                                                        <span class="whitespace-nowrap text-xs font-normal">
                                                            @lang('shop::app.customers.account.orders.view.information.excl-tax')
                                                            
                                                            <span class="font-medium">
                                                                {{ core()->formatPrice($item->total, $order->order_currency_code) }}
                                                            </span>
                                                        </span>
                                                    @else
                                                        {{ core()->formatPrice($item->total, $order->order_currency_code) }}
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach

                                        @if (! $refund->items->count())
                                            <tr>
                                                <td>@lang('shop::app.customers.account.orders.view.refunds.no-result-found')</td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>

                            <div class="mt-8 flex items-start gap-10 max-lg:gap-5 max-md:grid">
                                <div class="flex-auto max-md:mt-8">
                                    <div class="flex justify-end">
                                        <div class="grid max-w-max gap-2 text-sm">
                                            @if (core()->getConfigData('sales.taxes.sales.display_subtotal') == 'including_tax')
                                                <div class="flex w-full justify-between gap-x-5 text-sm">
                                                    <p>
                                                        @lang('shop::app.customers.account.orders.view.refunds.subtotal')
                                                    </p>

                                                    <p>
                                                        {{ core()->formatPrice($refund->sub_total_incl_tax, $order->order_currency_code) }}
                                                    </p>
                                                </div>
                                            @elseif (core()->getConfigData('sales.taxes.sales.display_subtotal') == 'both')
                                                <div class="flex w-full justify-between gap-x-5 text-sm">
                                                    <p>
                                                        @lang('shop::app.customers.account.orders.view.refunds.subtotal-excl-tax')
                                                    </p>

                                                    <p>
                                                        {{ core()->formatPrice($refund->sub_total, $order->order_currency_code) }}
                                                    </p>
                                                </div>
                                                
                                                <div class="flex w-full justify-between gap-x-5 text-sm">
                                                    <p>
                                                        @lang('shop::app.customers.account.orders.view.refunds.subtotal-incl-tax')
                                                    </p>

                                                    <p>
                                                        {{ core()->formatPrice($refund->sub_total_incl_tax, $order->order_currency_code) }}
                                                    </p>
                                                </div>
                                            @else
                                                <div class="flex w-full justify-between gap-x-5 text-sm">
                                                    <p>
                                                        @lang('shop::app.customers.account.orders.view.refunds.subtotal')
                                                    </p>

                                                    <p>
                                                        {{ core()->formatPrice($refund->sub_total, $order->order_currency_code) }}
                                                    </p>
                                                </div>
                                            @endif

                                            @if (core()->getConfigData('sales.taxes.sales.display_shipping_amount') == 'including_tax')
                                                <div class="flex w-full justify-between gap-x-5">
                                                    <p>
                                                        @lang('shop::app.customers.account.orders.view.refunds.shipping-handling')
                                                    </p>

                                                    <p>
                                                        {{ core()->formatPrice($refund->shipping_amount_incl_tax, $order->order_currency_code) }}
                                                    </p>
                                                </div>
                                            @elseif (core()->getConfigData('sales.taxes.sales.display_shipping_amount') == 'both')
                                                <div class="flex w-full justify-between gap-x-5">
                                                    <p>
                                                        @lang('shop::app.customers.account.orders.view.refunds.shipping-handling-excl-tax')
                                                    </p>

                                                    <p>
                                                        {{ core()->formatPrice($refund->shipping_amount, $order->order_currency_code) }}
                                                    </p>
                                                </div>
                                                
                                                <div class="flex w-full justify-between gap-x-5">
                                                    <p>
                                                        @lang('shop::app.customers.account.orders.view.refunds.shipping-handling-incl-tax')
                                                    </p>

                                                    <p>
                                                        {{ core()->formatPrice($refund->shipping_amount_incl_tax, $order->order_currency_code) }}
                                                    </p>
                                                </div>
                                            @else
                                                <div class="flex w-full justify-between gap-x-5">
                                                    <p>
                                                        @lang('shop::app.customers.account.orders.view.refunds.shipping-handling')
                                                    </p>

                                                    <p>
                                                        {{ core()->formatPrice($refund->shipping_amount, $order->order_currency_code) }}
                                                    </p>
                                                </div>
                                            @endif

                                            @if ($refund->discount_amount > 0)
                                                <div class="flex w-full justify-between gap-x-5">
                                                    <p>
                                                        @lang('shop::app.customers.account.orders.view.refunds.discount')
                                                    </p>

                                                    <p>
                                                        {{ core()->formatPrice($order->discount_amount, $order->order_currency_code) }}
                                                    </p>
                                                </div>
                                            @endif

                                            @if ($refund->tax_amount > 0)
                                                <div class="flex w-full justify-between gap-x-5">
                                                    <p>
                                                        @lang('shop::app.customers.account.orders.view.refunds.tax')
                                                    </p>

                                                    <p>
                                                        {{ core()->formatPrice($refund->tax_amount, $order->order_currency_code) }}
                                                    </p>
                                                </div>
                                            @endif

                                            <div class="flex w-full justify-between gap-x-5">
                                                <p>
                                                    @lang('shop::app.customers.account.orders.view.refunds.adjustment-refund')
                                                </p>

                                                <p>
                                                    {{ core()->formatPrice($refund->adjustment_refund, $order->order_currency_code) }}
                                                </p>
                                            </div>

                                            <div class="flex w-full justify-between gap-x-5">
                                                <p>
                                                    @lang('shop::app.customers.account.orders.view.refunds.adjustment-fee')
                                                </p>

                                                <p>
                                                    {{ core()->formatPrice($refund->adjustment_fee, $order->order_currency_code) }}
                                                </p>
                                            </div>

                                            <div class="flex w-full justify-between gap-x-5 font-semibold">
                                                <p>
                                                    @lang('shop::app.customers.account.orders.view.refunds.grand-total')
                                                </p>

                                                <p>
                                                    {{ core()->formatPrice($refund->grand_total, $order->order_currency_code) }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach

                    </x-shop::tabs.item >
                @endif
            </x-shop::tabs>

            <div class="mt-11 flex flex-wrap justify-between gap-x-11 gap-y-8 border-t border-zinc-200 pt-7 max-sm:mt-3 max-sm:gap-y-4">
                <!-- Biiling Address -->
                @if ($order->billing_address)
                    <div class="grid max-w-[200px] gap-4 max-868:w-full max-868:max-w-full max-md:max-w-[200px] max-sm:max-w-full max-sm:gap-2">
                        <p class="text-base text-zinc-500 max-sm:text-lg max-sm:text-black">
                            @lang('shop::app.customers.account.orders.view.billing-address')
                        </p>

                        <div class="grid gap-2.5 max-sm:gap-0">
                            <p class="text-sm">
                                @include ('shop::customers.account.orders.view.address', ['address' => $order->billing_address])
                            </p>
                        </div>
                        
                        {!! view_render_event('bagisto.shop.customers.account.orders.view.billing_address_details.after', ['order' => $order]) !!}
                    </div>

                {!! view_render_event('bagisto.shop.customers.account.orders.view.billing_address.after', ['order' => $order]) !!}

                @endif

                <!-- Shipping Address -->
                @if ($order->shipping_address)
                    <div class="grid max-w-[200px] gap-4 max-868:w-full max-868:max-w-full max-md:max-w-[200px] max-sm:max-w-full max-sm:gap-2">
                        <p class="text-base text-zinc-500 max-sm:text-lg max-sm:text-black">
                            @lang('shop::app.customers.account.orders.view.shipping-address')
                        </p>

                        <div class="grid gap-2.5 max-sm:gap-0">
                            <p class="text-sm">
                                @include ('shop::customers.account.orders.view.address', ['address' => $order->shipping_address])
                            </p>
                        </div>
                        
                        {!! view_render_event('bagisto.shop.customers.account.orders.view.shipping_address_details.after', ['order' => $order]) !!}
                    </div>
                    
                    {!! view_render_event('bagisto.shop.customers.account.orders.view.shipping_address.after', ['order' => $order]) !!}

                    <!-- Shipping Method -->
                    <div class="grid max-w-[200px] place-content-baseline gap-4 max-868:w-full max-868:max-w-full max-md:max-w-[200px] max-sm:max-w-full max-sm:gap-2">
                        <p class="text-base text-zinc-500 max-sm:text-lg max-sm:text-black">
                            @lang('shop::app.customers.account.orders.view.shipping-method')
                        </p>

                        <p class="text-sm">
                            {{ $order->shipping_title }}
                        </p>

                        {!! view_render_event('bagisto.shop.customers.account.orders.view.shipping_method_details.after', ['order' => $order]) !!}
                    </div>

                    {!! view_render_event('bagisto.shop.customers.account.orders.view.shipping_method.after', ['order' => $order]) !!}

                @endif

                <!-- Payment Method -->
                <div class="grid max-w-[200px] place-content-baseline gap-4 max-868:w-full max-868:max-w-full max-md:max-w-[200px] max-sm:max-w-full max-sm:gap-2">
                    <p class="text-base text-zinc-500 max-sm:text-lg max-sm:text-black">
                        @lang('shop::app.customers.account.orders.view.payment-method')
                    </p>

                    <p class="text-sm">
                        {{ core()->getConfigData('sales.payment_methods.' . $order->payment->method . '.title') }}
                    </p>

                    @if (! empty($additionalDetails))
                        <div class="instructions">
                            <label>{{ $additionalDetails['title'] }}</label>
                        </div>
                    @endif
                    
                    {!! view_render_event('bagisto.shop.customers.account.orders.view.payment_method_details.after', ['order' => $order]) !!}
                </div>
                
                {!! view_render_event('bagisto.shop.customers.account.orders.view.payment_method.after', ['order' => $order]) !!}
            </div>
        </div>

        {!! view_render_event('bagisto.shop.customers.account.orders.view.after', ['order' => $order]) !!}

    </div>
</x-shop::layouts.account>
