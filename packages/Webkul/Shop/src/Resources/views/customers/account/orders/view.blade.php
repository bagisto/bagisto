<x-shop::layouts.account>
    {{-- Page Title --}}
    <x-slot:title>
        @lang('shop::app.customers.account.orders.view.page-title', ['order_id' => $order->increment_id])
    </x-slot:title>
    
    {{-- Breadcrumbs --}}
    @section('breadcrumbs')
        <x-shop::breadcrumbs name="orders.view" :entity="$order"></x-shop::breadcrumbs>
    @endSection

    <div class="flex justify-between items-center">
        <div class="">
            <h2 class="text-[26px] font-medium">
                @lang('shop::app.customers.account.orders.view.page-title', ['order_id' => $order->increment_id])
            </h2>
        </div>

        @if ($order->canCancel())
            <form
                method="POST"
                ref="cancelOrderForm"
                action="{{ route('shop.customers.account.orders.cancel', $order->id) }}"
            >
                @csrf
            </form>

            <a
                class="secondary-button flex items-center gap-x-[10px] py-[12px] px-[20px] border-[#E9E9E9] font-normal"
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

    {!! view_render_event('bagisto.shop.customers.account.orders.view.before', ['order' => $order]) !!}

    {{-- Order view tabs --}}
    <div>
        <x-shop::tabs class="mt-5">
            <x-shop::tabs.item
                class="!px-0"
                :title="trans('shop::app.customers.account.orders.view.information.info')"
                :is-selected="true"
            >
                <div class="text-[15px] font-medium">
                    <label>
                        @lang('shop::app.customers.account.orders.view.information.placed-on')
                    </label>

                    <span>
                        {{ core()->formatDate($order->created_at, 'd M Y') }}
                    </span>
                </div>

                <div class="relative overflow-x-auto mt-[30px] border rounded-[12px]">
                    <table class="w-full text-sm text-left ">
                        <thead class="bg-[#F5F5F5] border-b-[1px] border-[#E9E9E9] text-[14px] text-black">
                            <tr>
                                <th
                                    scope="col"
                                    class="px-6 py-[16px] font-medium"
                                >
                                    @lang('shop::app.customers.account.orders.view.information.sku')
                                </th>

                                <th
                                    scope="col"
                                    class="px-6 py-[16px] font-medium"
                                >
                                    @lang('shop::app.customers.account.orders.view.information.product-name')
                                </th>

                                <th
                                    scope="col"
                                    class="px-6 py-[16px] font-medium"
                                >
                                    @lang('shop::app.customers.account.orders.view.information.price')
                                </th>

                                <th
                                    scope="col"
                                    class="px-6 py-[16px] font-medium"
                                >
                                    @lang('shop::app.customers.account.orders.view.information.item-status')
                                </th>

                                <th
                                    scope="col"
                                    class="px-6 py-[16px] font-medium"
                                >
                                    @lang('shop::app.customers.account.orders.view.information.subtotal')
                                </th>

                                <th
                                    scope="col"
                                    class="px-6 py-[16px] font-medium"
                                >
                                    @lang('shop::app.customers.account.orders.view.information.tax-percent')
                                </th>

                                <th
                                    scope="col"
                                    class="px-6 py-[16px] font-medium"
                                >
                                    @lang('shop::app.customers.account.orders.view.information.tax-amount')
                                </th>

                                <th
                                    scope="col"
                                    class="px-6 py-[16px] font-medium"
                                >
                                    @lang('shop::app.customers.account.orders.view.information.grand-total')
                                </th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($order->items as $item)
                                <tr class="bg-white border-b">
                                    <td
                                        class="px-6 py-[16px] text-black font-medium"
                                        data-value="@lang('shop::app.customers.account.orders.view.information.sku')"
                                    >
                                        {{ $item->getTypeInstance()->getOrderedItem($item)->sku }}
                                    </td>

                                    <td
                                        class="px-6 py-[16px] text-black font-medium"
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
                                        class="px-6 py-[16px] text-black font-medium"
                                        data-value="@lang('shop::app.customers.account.orders.view.information.price')"
                                    >
                                        {{ core()->formatPrice($item->price, $order->order_currency_code) }}
                                    </td>

                                    <td
                                        class="px-6 py-[16px] text-black font-medium"
                                        data-value= "@lang('shop::app.customers.account.orders.view.information.item-status')"
                                    >
                                        <span >
                                           {{__('shop::app.customers.account.orders.view.information.item-ordered', ['qty_ordered' => $item->qty_ordered])}}
                                        </span>

                                        <span>
                                            {{ $item->qty_invoiced ? __('shop::app.customers.account.orders.view.information.item-invoice', ['qty_invoiced' => $item->qty_invoiced]) : '' }}
                                        </span>

                                        <span>
                                            {{ $item->qty_shipped ? __('shop::app.customers.account.orders.view.information.item-shipped', ['qty_shipped' => $item->qty_shipped]) : '' }}
                                        </span>

                                        <span>
                                            {{ $item->qty_refunded ? __('shop::app.customers.account.orders.view.information.item-refunded', ['qty_refunded' => $item->qty_refunded]) : '' }}
                                        </span>

                                        <span>
                                            {{ $item->qty_canceled ? __('shop::app.customers.account.orders.view.information.item-canceled', ['qty_canceled' => $item->qty_canceled]) : '' }}
                                        </span>
                                    </td>

                                    <td
                                        class="px-6 py-[16px] text-black font-medium"
                                        data-value="@lang('shop::app.customers.account.orders.view.information.subtotal')"
                                    >
                                        {{ core()->formatPrice($item->total, $order->order_currency_code) }}
                                    </td>

                                    <td
                                        class="px-6 py-[16px] text-black font-medium"
                                        data-value="@lang('shop::app.customers.account.orders.view.information.tax-percent')"
                                    >
                                        {{ number_format($item->tax_percent, 2) }}%
                                    </td>

                                    <td
                                        class="px-6 py-[16px] text-black font-medium"
                                        data-value="@lang('shop::app.customers.account.orders.view.information.tax-amount')"
                                    >
                                        {{ core()->formatPrice($item->tax_amount, $order->order_currency_code) }}
                                    </td>

                                    <td
                                        class="px-6 py-[16px] text-black font-medium"
                                        data-value="@lang('shop::app.customers.account.orders.view.information.grand-total')"
                                    >
                                        {{ core()->formatPrice($item->total + $item->tax_amount - $item->discount_amount, $order->order_currency_code) }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="flex gap-[40px] items-start mt-[30px] max-lg:gap-[20px] max-md:grid">
                    <div class="flex-auto max-md:mt-[30px]">
                        <div class="flex justify-end">
                            <div class="grid gap-[8px] max-w-max">
                                <div class="flex gap-x-[20px] justify-between w-full">
                                    <p class="text-[14px]">
                                        @lang('shop::app.customers.account.orders.view.information.subtotal')
                                    </p>

                                    <div class="flex gap-x-[20px]">
                                        <p class="text-[14px]">-</p>

                                        <p class="text-[14px]">
                                            {{ core()->formatPrice($order->sub_total, $order->order_currency_code) }}
                                        </p>
                                    </div>
                                </div>

                                @if ($order->haveStockableItems())
                                    <div class="flex w-full gap-x-[20px] justify-between">
                                        <p class="text-[14px]">
                                            @lang('shop::app.customers.account.orders.view.information.shipping-handling')
                                        </p>

                                        <div class="flex gap-x-[20px]">
                                            <p class="text-[14px]">-</p>

                                            <p class="text-[14px]">
                                                {{ core()->formatPrice($order->shipping_amount, $order->order_currency_code) }}
                                            </p>
                                        </div>
                                    </div>
                                @endif

                                @if ($order->base_discount_amount > 0)
                                    <div class="flex gap-x-[20px] justify-between w-full">
                                        <p class="text-[14px]">
                                            @lang('shop::app.customers.account.orders.view.information.discount')

                                            @if ($order->coupon_code)
                                                ({{ $order->coupon_code }})
                                            @endif
                                        </p>

                                        <div class="flex gap-x-[20px]">
                                            <p class="text-[14px]">-</p>

                                            <p class="text-[14px]">
                                                {{ core()->formatPrice($order->discount_amount, $order->order_currency_code) }}
                                            </p>
                                        </div>
                                    </div>
                                @endif

                                <div class="flex gap-x-[20px] justify-between w-full">
                                    <p class="text-[14px]">
                                        @lang('shop::app.customers.account.orders.view.information.tax')
                                    </p>

                                    <div class="flex gap-x-[20px]">
                                        <p class="text-[14px]">-</p>

                                        <p class="text-[14px]">
                                            {{ core()->formatPrice($order->tax_amount, $order->order_currency_code) }}
                                        </p>
                                    </div>
                                </div>

                                <div class="flex gap-x-[20px] justify-between w-full">
                                    <p class="text-[14px]">
                                        @lang('shop::app.customers.account.orders.view.information.grand-total')
                                    </p>

                                    <div class="flex gap-x-[20px]">
                                        <p class="text-[14px]">-</p>

                                        <p class="text-[14px]">
                                            {{ core()->formatPrice($order->grand_total, $order->order_currency_code) }}
                                        </p>
                                    </div>
                                </div>

                                <div class="flex gap-x-[20px] justify-between w-full">
                                    <p class="text-[14px]">
                                        @lang('shop::app.customers.account.orders.view.information.total-paid')
                                    </p>

                                    <div class="flex gap-x-[20px]">
                                        <p class="text-[14px]">-</p>

                                        <p class="text-[14px]">
                                            {{ core()->formatPrice($order->grand_total_invoiced, $order->order_currency_code) }}
                                        </p>
                                    </div>
                                </div>

                                <div class="flex gap-x-[20px] justify-between w-full">
                                    <p class="text-[14px]">
                                        @lang('shop::app.customers.account.orders.view.information.total-refunded')
                                    </p>

                                    <div class="flex gap-x-[20px]">
                                        <p class="text-[14px]">-</p>

                                        <p class="text-[14px]">
                                            {{ core()->formatPrice($order->grand_total_refunded, $order->order_currency_code) }}
                                        </p>
                                    </div>
                                </div>
                                <div class="flex gap-x-[20px] justify-between w-full">
                                    <p class="text-[14px]">
                                        @lang('shop::app.customers.account.orders.view.information.total-due')
                                    </p>

                                    <div class="flex gap-x-[20px]">
                                        <p class="text-[14px]">-</p>

                                        <p class="text-[14px]">
                                            @if($order->status !== 'canceled')
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
                </div>
            </x-shop::tabs.item>

            @if ($order->invoices->count())
                <x-shop::tabs.item  :title="trans('shop::app.customers.account.orders.view.invoices.invoices')">

                    @foreach ($order->invoices as $invoice)
                        <div class="flex justify-between items-center">
                            <div class="">
                                <p class="text-[15px] font-medium">
                                    @lang('shop::app.customers.account.orders.view.invoices.individual-invoice', ['invoice_id' => $invoice->increment_id ?? $invoice->id])
                                </p>
                            </div>
                            <div class="secondary-button flex gap-x-[10px] items-center py-[12px] px-[20px] border-[#E9E9E9] font-normal">
                                <a href="{{ route('shop.customers.account.orders.print-invoice', $invoice->id) }}">
                                        @lang('shop::app.customers.account.orders.view.invoices.print')
                                </a>
                            </div>
                        </div>

                        <div class="relative overflow-x-auto mt-[30px] border rounded-[12px]">
                            <table class="w-full text-sm text-left">
                                <thead class="bg-[#F5F5F5] border-b-[1px] border-[#E9E9E9] text-[14px] text-black">
                                    <tr>
                                        <th
                                            scope="col"
                                            class="px-6 py-[16px] font-medium"
                                        >
                                            @lang('shop::app.customers.account.orders.view.invoices.sku')
                                        </th>

                                        <th
                                            scope="col"
                                            class="px-6 py-[16px] font-medium"
                                        >
                                            @lang('shop::app.customers.account.orders.view.invoices.product-name')
                                        </th>

                                        <th
                                            scope="col"
                                            class="px-6 py-[16px] font-medium"
                                        >
                                            @lang('shop::app.customers.account.orders.view.invoices.price')
                                        </th>

                                        <th
                                            scope="col"
                                            class="px-6 py-[16px] font-medium"
                                        >
                                            @lang('shop::app.customers.account.orders.view.invoices.qty')
                                        </th>

                                        <th
                                            scope="col"
                                            class="px-6 py-[16px] font-medium"
                                        >
                                            @lang('shop::app.customers.account.orders.view.invoices.subtotal')
                                        </th>

                                        <th
                                            scope="col"
                                            class="px-6 py-[16px] font-medium"
                                        >
                                            @lang('shop::app.customers.account.orders.view.invoices.tax-amount')
                                        </th>

                                        <th
                                            scope="col"
                                            class="px-6 py-[16px] font-medium"
                                        >
                                            @lang('shop::app.customers.account.orders.view.invoices.grand-total')
                                        </th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach ($invoice->items as $item)
                                        <tr class="bg-white border-b">
                                            <td
                                                class="px-6 py-[16px] text-black font-medium"
                                                data-value="@lang('shop::app.customers.account.orders.view.invoices.sku')"
                                            >
                                                {{ $item->getTypeInstance()->getOrderedItem($item)->sku }}
                                            </td>

                                            <td
                                                class="px-6 py-[16px] text-black font-medium"
                                                data-value="@lang('shop::app.customers.account.orders.view.invoices.product-name')"
                                            >
                                                {{ $item->name }}
                                            </td>

                                            <td
                                                class="px-6 py-[16px] text-black font-medium"
                                                data-value="@lang('shop::app.customers.account.orders.view.invoices.price')"
                                            >
                                                {{ core()->formatPrice($item->price, $order->order_currency_code) }}
                                            </td>

                                            <td
                                                class="px-6 py-[16px] text-black font-medium"
                                                data-value="@lang('shop::app.customers.account.orders.view.invoices.qty')"
                                            >
                                                {{ $item->qty }}
                                            </td>

                                            <td
                                                class="px-6 py-[16px] text-black font-medium"
                                                data-value="@lang('shop::app.customers.account.orders.view.invoices.subtotal')"
                                            >
                                                {{ core()->formatPrice($item->total, $order->order_currency_code) }}
                                            </td>

                                            <td
                                                class="px-6 py-[16px] text-black font-medium"
                                                data-value="@lang('shop::app.customers.account.orders.view.invoices.tax-amount')"
                                            >
                                                {{ core()->formatPrice($item->tax_amount, $order->order_currency_code) }}
                                            </td>

                                            <td
                                                class="px-6 py-[16px] text-black font-medium"
                                                data-value="@lang('shop::app.customers.account.orders.view.invoices.grand-total')"
                                            >
                                                {{ core()->formatPrice($item->total + $item->tax_amount, $order->order_currency_code) }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="flex gap-[40px] items-start mt-[30px] max-lg:gap-[20px] max-md:grid">
                            <div class="flex-auto max-md:mt-[30px]">
                                <div class="flex justify-end">
                                    <div class="grid gap-[8px] max-w-max">
                                        <div class="flex gap-x-[20px] justify-between w-full">
                                            <p class="text-[14px]">
                                                @lang('shop::app.customers.account.orders.view.invoices.subtotal')
                                            </p>

                                            <div class="flex gap-x-[20px]">
                                                <p class="text-[14px]">-</p>

                                                <p class="text-[14px]">
                                                    {{ core()->formatPrice($invoice->sub_total, $order->order_currency_code) }}
                                                </p>
                                            </div>
                                        </div>

                                        <div class="flex gap-x-[20px] justify-between w-full">
                                            <p class="text-[14px]">
                                                @lang('shop::app.customers.account.orders.view.invoices.shipping-handling')
                                            </p>

                                            <div class="flex gap-x-[20px]">
                                                <p class="text-[14px]">-</p>

                                                <p class="text-[14px]">
                                                    {{ core()->formatPrice($invoice->shipping_amount, $order->order_currency_code) }}
                                                </p>
                                            </div>
                                        </div>

                                        @if ($invoice->base_discount_amount > 0)
                                            <div class="flex gap-x-[20px] justify-between w-full">
                                                <p class="text-[14px]">
                                                    @lang('shop::app.customers.account.orders.view.invoices.discount')
                                                </p>

                                                <div class="flex gap-x-[20px]">
                                                    <p class="text-[14px]">-</p>

                                                    <p class="text-[14px]">
                                                        {{ core()->formatPrice($invoice->discount_amount, $order->order_currency_code) }}
                                                    </p>
                                                </div>
                                            </div>
                                        @endif

                                        <div class="flex gap-x-[20px] justify-between w-full">
                                            <p class="text-[14px]">
                                                @lang('shop::app.customers.account.orders.view.invoices.tax')
                                            </p>

                                            <div class="flex gap-x-[20px]">
                                                <p class="text-[14px]">-</p>

                                                <p class="text-[14px]">
                                                    {{ core()->formatPrice($invoice->tax_amount, $order->order_currency_code) }}
                                                </p>
                                            </div>
                                        </div>

                                        <div class="flex gap-x-[20px] justify-between w-full">
                                            <p class="text-[14px]">
                                                @lang('shop::app.customers.account.orders.view.invoices.grand-total')
                                            </p>

                                            <div class="flex gap-x-[20px]">
                                                <p class="text-[14px]">-</p>

                                                <p class="text-[14px]">
                                                    {{ core()->formatPrice($invoice->grand_total, $order->order_currency_code) }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach

                </x-shop::tabs.item >
            @endif

            @if ($order->shipments->count())
                <x-shop::tabs.item title="{{__('shop::app.customers.account.orders.view.shipments.shipments') }}">

                    @foreach ($order->shipments as $shipment)
                        <div>
                            <label class="text-[15px] font-medium">
                                @lang('shop::app.customers.account.orders.view.shipments.tracking-number')
                            </label>

                            <span>
                                {{  $shipment->track_number }}
                            </span>
                        </div>

                        <div class="text-[15px] font-medium">
                            <span>
                                @lang('shop::app.customers.account.orders.view.shipments.individual-shipment', ['shipment_id' => $shipment->id])
                            </span>
                        </div>

                        <div class="relative overflow-x-auto border rounded-[12px] mt-[30px]">
                            <table class="w-full text-sm text-left">
                                <thead class="text-[14px] text-black bg-[#F5F5F5] border-b-[1px] border-[#E9E9E9]  ">
                                    <tr>
                                        <th
                                            scope="col"
                                            class="px-6 py-[16px] font-medium"
                                        >
                                            @lang('shop::app.customers.account.orders.view.shipments.sku')
                                        </th>

                                        <th
                                            scope="col"
                                            class="px-6 py-[16px] font-medium"
                                        >
                                            @lang('shop::app.customers.account.orders.view.shipments.product-name')
                                        </th>

                                        <th
                                            scope="col"
                                            class="px-6 py-[16px] font-medium"
                                        >
                                            @lang('shop::app.customers.account.orders.view.shipments.qty')
                                        </th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach ($shipment->items as $item)
                                        <tr class="bg-white border-b">
                                            <td
                                                class="px-6 py-[16px] text-black font-medium"
                                                data-value="@lang('shop::app.customers.account.orders.view.shipments.sku')"
                                            >
                                                {{ $item->sku }}
                                            </td>

                                            <td
                                                class="px-6 py-[16px] text-black font-medium"
                                                data-value="@lang('shop::app.customers.account.orders.view.shipments.product-name')"
                                            >
                                                {{ $item->name }}
                                            </td>

                                            <td
                                                class="px-6 py-[16px] text-black font-medium"
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
                <x-shop::tabs.item
                    title="@lang('shop::app.customers.account.orders.view.refunds.refunds') }}"
                >
                    @foreach ($order->refunds as $refund)
                        <div class="text-[15px] font-medium">
                            <span>
                                @lang('shop::app.customers.account.orders.view.refunds.individual-refund', ['refund_id' => $refund->id])
                            </span>
                        </div>

                        <div class="relative overflow-x-auto mt-[30px] border rounded-[12px]">
                            <table class="w-full text-sm text-left">
                                <thead class="text-[14px] text-black bg-[#F5F5F5] border-b-[1px] border-[#E9E9E9]  ">
                                    <tr>
                                        <th
                                            scope="col"
                                            class="px-6 py-[16px] font-medium"
                                        >
                                            @lang('shop::app.customers.account.orders.view.refunds.sku')
                                        </th>

                                        <th
                                            scope="col"
                                            class="px-6 py-[16px] font-medium"
                                        >
                                            @lang('shop::app.customers.account.orders.view.refunds.product-name')
                                        </th>

                                        <th
                                            scope="col"
                                            class="px-6 py-[16px] font-medium"
                                        >
                                            @lang('shop::app.customers.account.orders.view.refunds.price')
                                        </th>

                                        <th
                                            scope="col"
                                            class="px-6 py-[16px] font-medium"
                                        >
                                            @lang('shop::app.customers.account.orders.view.refunds.qty')
                                        </th>

                                        <th
                                            scope="col"
                                            class="px-6 py-[16px] font-medium"
                                        >
                                            @lang('shop::app.customers.account.orders.view.refunds.subtotal')
                                        </th>

                                        <th
                                            scope="col"
                                            class="px-6 py-[16px] font-medium"
                                        >
                                            @lang('shop::app.customers.account.orders.view.refunds.tax-amount')
                                        </th>

                                        <th
                                            scope="col"
                                            class="px-6 py-[16px] font-medium"
                                        >
                                            @lang('shop::app.customers.account.orders.view.refunds.grand-total')
                                        </th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach ($refund->items as $item)
                                        <tr class="bg-white border-b">
                                            <td
                                                class="px-6 py-[16px] text-black font-medium"
                                                data-value="@lang('shop::app.customers.account.orders.view.refunds.sku')"
                                            >
                                                {{ $item->child ? $item->child->sku : $item->sku }}
                                            </td>

                                            <td
                                                class="px-6 py-[16px] text-black font-medium"
                                                data-value="@lang('shop::app.customers.account.orders.view.refunds.product-name')"
                                            >
                                                {{ $item->name }}
                                            </td>

                                            <td
                                                class="px-6 py-[16px] text-black font-medium"
                                                data-value="@lang('shop::app.customers.account.orders.view.refunds.price')"
                                            >
                                                {{ core()->formatPrice($item->price, $order->order_currency_code) }}
                                            </td>

                                            <td
                                                class="px-6 py-[16px] text-black font-medium"
                                                data-value="@lang('shop::app.customers.account.orders.view.refunds.qty')"
                                            >
                                                {{ $item->qty }}
                                            </td>

                                            <td
                                                class="px-6 py-[16px] text-black font-medium"
                                                data-value="@lang('shop::app.customers.account.orders.view.refunds.subtotal')"
                                            >
                                                {{ core()->formatPrice($item->total, $order->order_currency_code) }}
                                            </td>

                                            <td
                                                class="px-6 py-[16px] text-black font-medium"
                                                data-value="@lang('shop::app.customers.account.orders.view.refunds.tax-amount')"
                                            >
                                                {{ core()->formatPrice($item->tax_amount, $order->order_currency_code) }}
                                            </td>

                                            <td
                                                class="px-6 py-[16px] text-black font-medium"
                                                data-value="@lang('shop::app.customers.account.orders.view.refunds.grand-total')"
                                            >
                                                {{ core()->formatPrice($item->total + $item->tax_amount, $order->order_currency_code) }}
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

                        <div class="flex gap-[40px] items-start mt-[30px] max-lg:gap-[20px] max-md:grid">
                            <div class="flex-auto max-md:mt-[30px]">
                                <div class="flex justify-end">
                                    <div class="grid gap-[8px] max-w-max">
                                        <div class="flex gap-x-[20px] justify-between w-full">
                                            <p class="text-[14px]">
                                                @lang('shop::app.customers.account.orders.view.refunds.subtotal')
                                            </p>

                                            <div class="flex gap-x-[20px]">
                                                <p class="text-[14px]">-</p>

                                                <p class="text-[14px]">
                                                    {{ core()->formatPrice($refund->sub_total, $order->order_currency_code) }}
                                                </p>
                                            </div>
                                        </div>

                                        <div class="flex gap-x-[20px] justify-between w-full">
                                            <p class="text-[14px]">
                                                @lang('shop::app.customers.account.orders.view.refunds.shipping-handling')
                                            </p>

                                            <div class="flex gap-x-[20px]">
                                                <p class="text-[14px]">-</p>

                                                <p class="text-[14px]">
                                                    {{ core()->formatPrice($refund->shipping_amount, $order->order_currency_code) }}
                                                </p>
                                            </div>
                                        </div>

                                        @if ($refund->discount_amount > 0)
                                            <div class="flex gap-x-[20px] justify-between w-full">
                                                <p class="text-[14px]">
                                                    @lang('shop::app.customers.account.orders.view.refunds.discount')
                                                </p>

                                                <div class="flex gap-x-[20px]">
                                                    <p class="text-[14px]">-</p>

                                                    <p class="text-[14px]">
                                                        {{ core()->formatPrice($order->discount_amount, $order->order_currency_code) }}
                                                    </p>
                                                </div>
                                            </div>
                                        @endif

                                        @if ($refund->tax_amount > 0)
                                            <div class="flex gap-x-[20px] justify-between w-full">
                                                <p class="text-[14px]">
                                                    @lang('shop::app.customers.account.orders.view.refunds.tax')
                                                </p>

                                                <div class="flex gap-x-[20px]">
                                                    <p class="text-[14px]">-</p>

                                                    <p class="text-[14px]">
                                                        {{ core()->formatPrice($refund->tax_amount, $order->order_currency_code) }}
                                                    </p>
                                                </div>
                                            </div>
                                        @endif

                                        <div class="flex gap-x-[20px] justify-between w-full">
                                            <p class="text-[14px]">
                                                @lang('shop::app.customers.account.orders.view.refunds.adjustment-refund')
                                            </p>

                                            <div class="flex gap-x-[20px]">
                                                <p class="text-[14px]">-</p>

                                                <p class="text-[14px]">
                                                    {{ core()->formatPrice($refund->adjustment_refund, $order->order_currency_code) }}
                                                </p>
                                            </div>
                                        </div>

                                        <div class="flex gap-x-[20px] justify-between w-full">
                                            <p class="text-[14px]">
                                                @lang('shop::app.customers.account.orders.view.refunds.adjustment-fee')
                                            </p>

                                            <div class="flex gap-x-[20px]">
                                                <p class="text-[14px]">-</p>

                                                <p class="text-[14px]">
                                                    {{ core()->formatPrice($refund->adjustment_fee, $order->order_currency_code) }}
                                                </p>
                                            </div>
                                        </div>

                                        <div class="flex gap-x-[20px] justify-between w-full">
                                            <p class="text-[14px]">
                                                @lang('shop::app.customers.account.orders.view.refunds.grand-total')
                                            </p>

                                            <div class="flex gap-x-[20px]">
                                                <p class="text-[14px]">-</p>

                                                <p class="text-[14px]">
                                                    {{ core()->formatPrice($refund->grand_total, $order->order_currency_code) }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach

                </x-shop::tabs.item >
            @endif
        </x-shop::tabs>

        <div class="flex flex-wrap gap-x-[64px] gap-y-[30px] justify-between mt-[42px] pt-[26px] border-t-[1px] border-[#E9E9E9]">
            {{-- Biiling Address --}}
            @if ($order->billing_address)
                <div class="grid gap-[15px] max-w-[200px] max-868:w-full max-868:max-w-full max-md:max-w-[200px] max-sm:max-w-full">
                    <p class="text-[16px] text-[#6E6E6E]">
                        @lang('shop::app.customers.account.orders.view.billing-address')
                    </p>

                    <div class="grid gap-[10px]">
                        <p class="text-[14px]">
                            @include ('admin::sales.address', ['address' => $order->billing_address])
                        </p>

                        {!! view_render_event('bagisto.shop.customers.account.orders.view.billing_address.after', ['order' => $order]) !!}
                    </div>
                </div>
            @endif

            {{-- Shipping Address --}}
            @if ($order->shipping_address)
                <div class="grid gap-[15px] max-w-[200px] max-868:w-full max-868:max-w-full max-md:max-w-[200px] max-sm:max-w-full">
                    <p class="text-[16px] text-[#6E6E6E]">
                        @lang('shop::app.customers.account.orders.view.shipping-address')
                    </p>

                    <div class="grid gap-[10px]">
                        <p class="text-[14px]">
                            @include ('admin::sales.address', ['address' => $order->shipping_address])

                            {!! view_render_event('bagisto.shop.customers.account.orders.view.shipping_address.after', ['order' => $order]) !!}
                        </p>
                    </div>
                </div>

                {{-- Shipping Method --}}
                <div class="grid gap-[15px] max-w-[200px] place-content-baseline max-868:w-full max-868:max-w-full max-md:max-w-[200px] max-sm:max-w-full">
                    <p class="text-[16px] text-[#6E6E6E]">
                        @lang('shop::app.customers.account.orders.view.shipping-method')
                    </p>

                    <p class="text-[14px]">
                        {{ $order->shipping_title }}
                    </p>
                </div>

                {!! view_render_event('bagisto.shop.customers.account.orders.view.shipping_method.after', ['order' => $order]) !!}

            @endif

            {{-- Billing Method --}}
            <div class="grid gap-[15px] place-content-baseline max-w-[200px] max-868:w-full max-868:max-w-full max-md:max-w-[200px] max-sm:max-w-full">
                <p class="text-[16px] text-[#6E6E6E]">
                    @lang('shop::app.customers.account.orders.view.payment-method')
                </p>

                <p class="text-[14px]">
                    {{ core()->getConfigData('sales.payment_methods.' . $order->payment->method . '.title') }}
                </p>

                @if (! empty($additionalDetails))
                    <div class="instructions">
                        <label>{{ $additionalDetails['title'] }}</label>
                    </div>
                @endif

                {!! view_render_event('bagisto.shop.customers.account.orders.view.payment_method.after', ['order' => $order]) !!}
            </div>
        </div>
    </div>

    {!! view_render_event('bagisto.shop.customers.account.orders.view.after', ['order' => $order]) !!}
</x-shop::layouts.account>
