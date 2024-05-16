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

    <div class="flex items-center justify-between">
        <div class="">
            <h2 class="text-2xl font-medium">
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
    <div class="mt-5">
        <x-shop::tabs>
            <x-shop::tabs.item
                class="!px-0"
                :title="trans('shop::app.customers.account.orders.view.information.info')"
                :is-selected="true"
            >
                <div class="text-base font-medium">
                    <label>
                        @lang('shop::app.customers.account.orders.view.information.placed-on')
                    </label>

                    <span>
                        {{ core()->formatDate($order->created_at, 'd M Y') }}
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

                <div class="mt-8 flex items-start gap-10 max-lg:gap-5 max-md:grid">
                    <div class="flex-auto max-md:mt-8">
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
                <x-shop::tabs.item  :title="trans('shop::app.customers.account.orders.view.invoices.invoices')">
                    
                    @foreach ($order->invoices as $invoice)
                        <div class="flex items-center justify-between">
                            <div class="">
                                <p class="text-base font-medium">
                                    @lang('shop::app.customers.account.orders.view.invoices.individual-invoice', ['invoice_id' => $invoice->increment_id ?? $invoice->id])
                                </p>
                            </div>
                            
                            <a href="{{ route('shop.customers.account.orders.print-invoice', $invoice->id) }}">
                                <div class="secondary-button flex items-center gap-x-2.5 border-zinc-200 px-5 py-3 font-normal">
                                    @lang('shop::app.customers.account.orders.view.invoices.print')
                                </div>
                            </a>
                        </div>

                        <div class="relative mt-8 overflow-x-auto rounded-xl border">
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

                        <div class="mt-8 flex items-start gap-10 max-lg:gap-5 max-md:grid">
                            <div class="flex-auto max-md:mt-8">
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

                </x-shop::tabs.item >
            @endif

            @if ($order->shipments->count())
                <x-shop::tabs.item title="{{ trans('shop::app.customers.account.orders.view.shipments.shipments') }}">
                    @foreach ($order->shipments as $shipment)
                        <div>
                            <label class="text-base font-medium">
                                @lang('shop::app.customers.account.orders.view.shipments.tracking-number')
                            </label>

                            <span>
                                {{  $shipment->track_number }}
                            </span>
                        </div>

                        <div class="text-base font-medium">
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

            @if ($order->refunds->count())
                <x-shop::tabs.item :title="trans('shop::app.customers.account.orders.view.refunds.refunds')">

                    @foreach ($order->refunds as $refund)
                        <div class="text-base font-medium">
                            <span>
                                @lang('shop::app.customers.account.orders.view.refunds.individual-refund', ['refund_id' => $refund->id])
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

        <div class="mt-11 flex flex-wrap justify-between gap-x-11 gap-y-8 border-t border-zinc-200 pt-7">
            <!-- Biiling Address -->
            @if ($order->billing_address)
                <div class="grid max-w-[200px] gap-4 max-868:w-full max-868:max-w-full max-md:max-w-[200px] max-sm:max-w-full">
                    <p class="text-base text-zinc-500">
                        @lang('shop::app.customers.account.orders.view.billing-address')
                    </p>

                    <div class="grid gap-2.5">
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
                <div class="grid max-w-[200px] gap-4 max-868:w-full max-868:max-w-full max-md:max-w-[200px] max-sm:max-w-full">
                    <p class="text-base text-zinc-500">
                        @lang('shop::app.customers.account.orders.view.shipping-address')
                    </p>

                    <div class="grid gap-2.5">
                        <p class="text-sm">
                            @include ('shop::customers.account.orders.view.address', ['address' => $order->shipping_address])
                        </p>
                    </div>
                    
                    {!! view_render_event('bagisto.shop.customers.account.orders.view.shipping_address_details.after', ['order' => $order]) !!}
                </div>
                
               {!! view_render_event('bagisto.shop.customers.account.orders.view.shipping_address.after', ['order' => $order]) !!}

                <!-- Shipping Method -->
                <div class="grid max-w-[200px] place-content-baseline gap-4 max-868:w-full max-868:max-w-full max-md:max-w-[200px] max-sm:max-w-full">
                    <p class="text-base text-zinc-500">
                        @lang('shop::app.customers.account.orders.view.shipping-method')
                    </p>

                    <p class="text-sm">
                        {{ $order->shipping_title }}
                    </p>

                    {!! view_render_event('bagisto.shop.customers.account.orders.view.shipping_method_details.after', ['order' => $order]) !!}
                </div>

                {!! view_render_event('bagisto.shop.customers.account.orders.view.shipping_method.after', ['order' => $order]) !!}

            @endif

            <!-- Billing Method -->
            <div class="grid max-w-[200px] place-content-baseline gap-4 max-868:w-full max-868:max-w-full max-md:max-w-[200px] max-sm:max-w-full">
                <p class="text-base text-zinc-500">
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
</x-shop::layouts.account>
