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

    <div class="mx-4 flex-auto max-md:mx-6 max-sm:mx-4">
        <div class="flex items-center justify-between">
            <div class="max-md:flex max-md:items-center">
                <!-- Back Button For mobile view -->
                <a
                    class="grid md:hidden"
                    href="{{ route('shop.customers.account.orders.index') }}"
                >
                    <span class="icon-arrow-left rtl:icon-arrow-right text-2xl"></span>
                </a>
    
                <h2 class="text-2xl font-medium max-md:text-xl max-sm:text-base ltr:ml-2.5 md:ltr:ml-0 rtl:mr-2.5 md:rtl:mr-0">
                    @lang('shop::app.customers.account.orders.view.page-title', ['order_id' => $order->increment_id])
                </h2>
            </div>

            <div class="flex gap-1.5">
                {!! view_render_event('bagisto.shop.customers.account.orders.reorder_button.before', ['order' => $order]) !!}

                @if (
                    $order->canReorder()
                    && core()->getConfigData('sales.order_settings.reorder.shop')
                )
                    <a
                        href="{{ route('shop.customers.account.orders.reorder', $order->id) }}"
                        class="secondary-button border-zinc-200 px-5 py-3 font-normal max-md:hidden"
                    >
                        @lang('shop::app.customers.account.orders.view.reorder-btn-title')
                    </a>
                @endif

                {!! view_render_event('bagisto.shop.customers.account.orders.reorder_button.after', ['order' => $order]) !!}

                {!! view_render_event('bagisto.shop.customers.account.orders.cancel_button.before', ['order' => $order]) !!}

                @if ($order->canCancel())
                    <form
                        method="POST"
                        ref="cancelOrderForm"
                        action="{{ route('shop.customers.account.orders.cancel', $order->id) }}"
                    >
                        @csrf
                    </form>

                    <a
                        class="secondary-button border-zinc-200 px-5 py-3 font-normal max-md:hidden"
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

                {!! view_render_event('bagisto.shop.customers.account.orders.cancel_button.after', ['order' => $order]) !!}
            </div>
        </div>

        {!! view_render_event('bagisto.shop.customers.account.orders.view.before', ['order' => $order]) !!}

        <!-- Order view tabs -->
        <div class="mt-8 max-md:mt-5 max-md:grid max-md:gap-4">
            <x-shop::tabs>
                <x-shop::tabs.item
                    class="!px-0 max-md:pb-0 max-md:pt-2"
                    :title="trans('shop::app.customers.account.orders.view.information.info')"
                    :is-selected="true"
                >
                    <!-- For Desktop -->
                    <div class="max-md:hidden">
                        <div class="text-base font-medium">
                            @lang('shop::app.customers.account.orders.view.information.placed-on')
    
                            {{ core()->formatDate($order->created_at, 'd M Y') }}
                        </div>

                        <div class="relative mt-8 overflow-x-auto rounded-xl border">
                            <table class="w-full text-left">
                                <thead class="border-b border-zinc-200 bg-zinc-100 text-sm text-black">
                                    <tr class="[&>*]:font-medium [&>*]:px-6 [&>*]:py-4">
                                        <th scope="col">
                                            @lang('shop::app.customers.account.orders.view.information.sku')
                                        </th>
    
                                        <th scope="col">
                                            @lang('shop::app.customers.account.orders.view.information.product-name')
                                        </th>
    
                                        <th scope="col">
                                            @lang('shop::app.customers.account.orders.view.information.price')
                                        </th>
    
                                        <th scope="col">
                                            @lang('shop::app.customers.account.orders.view.information.item-status')
                                        </th>
    
                                        <th scope="col">
                                            @lang('shop::app.customers.account.orders.view.information.subtotal')
                                        </th>
                                    </tr>
                                </thead>
    
                                <tbody>
                                    @foreach ($order->items as $item)
                                        <tr class="border-b bg-white align-top font-medium [&>*]:px-6 [&>*]:py-4">
                                            <td data-value="@lang('shop::app.customers.account.orders.view.information.sku')">
                                                {{ $item->getTypeInstance()->getOrderedItem($item)->sku }}
                                            </td>
    
                                            <td data-value="@lang('shop::app.customers.account.orders.view.information.product-name')">
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
                                                class="flex flex-col"
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
    
                                            <td data-value="@lang('shop::app.customers.account.orders.view.information.item-status')">
                                                @if($item->qty_ordered)
                                                    @lang('shop::app.customers.account.orders.view.information.ordered-item', ['qty_ordered' => $item->qty_ordered])
                                                @endif
    
                                                @if($item->qty_invoiced)
                                                    @lang('shop::app.customers.account.orders.view.information.invoiced-item', ['qty_invoiced' => $item->qty_invoiced])
                                                @endif
    
                                                @if($item->qty_shipped)
                                                    @lang('shop::app.customers.account.orders.view.information.item-shipped', ['qty_shipped' => $item->qty_shipped])
                                                @endif
    
                                                @if($item->qty_refunded)
                                                    @lang('shop::app.customers.account.orders.view.information.item-refunded', ['qty_refunded' => $item->qty_refunded])
                                                @endif
    
                                                @if($item->qty_canceled)
                                                    @lang('shop::app.customers.account.orders.view.information.item-canceled', ['qty_canceled' => $item->qty_canceled])
                                                @endif
                                            </td>
    
                                            <td
                                                class="flex flex-col"
                                                data-value="@lang('shop::app.customers.account.orders.view.information.subtotal')"
                                            >
                                                @if (core()->getConfigData('sales.taxes.sales.display_prices') == 'including_tax')
                                                    {{ core()->formatPrice($item->total_incl_tax, $order->order_currency_code) }}
                                                @elseif (core()->getConfigData('sales.taxes.sales.display_prices') == 'both')
                                                    {{ core()->formatPrice($item->total_incl_tax, $order->order_currency_code) }}
    
                                                    <span class="whitespace-nowrap text-xs font-normal">
                                                        @lang('shop::app.customers.account.orders.view.information.excl-tax')
                                                        
                                                        {{ core()->formatPrice($item->total, $order->order_currency_code) }}
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

                        <div class="mt-8 flex items-start gap-10 max-lg:gap-5">
                            <div class="flex-auto">
                                <div class="flex justify-end">
                                    <div class="grid max-w-max gap-2 text-sm">
                                        @if (core()->getConfigData('sales.taxes.sales.display_subtotal') == 'including_tax')
                                            <div class="flex w-full justify-between gap-x-5">
                                                @lang('shop::app.customers.account.orders.view.information.subtotal')
    
                                                <p>
                                                    {{ core()->formatPrice($order->sub_total_incl_tax, $order->order_currency_code) }}
                                                </p>
                                            </div>
                                        @elseif (core()->getConfigData('sales.taxes.sales.display_subtotal') == 'both')
                                            <div class="flex w-full justify-between gap-x-5">
                                                @lang('shop::app.customers.account.orders.view.information.subtotal-excl-tax')
    
                                                <p>
                                                    {{ core()->formatPrice($order->sub_total, $order->order_currency_code) }}
                                                </p>
                                            </div>
                                            
                                            <div class="flex w-full justify-between gap-x-5">
                                                @lang('shop::app.customers.account.orders.view.information.subtotal-incl-tax')
    
                                                <p>
                                                    {{ core()->formatPrice($order->sub_total_incl_tax, $order->order_currency_code) }}
                                                </p>
                                            </div>
                                        @else
                                            <div class="flex w-full justify-between gap-x-5">
                                                @lang('shop::app.customers.account.orders.view.information.subtotal')
    
                                                <p>
                                                    {{ core()->formatPrice($order->sub_total, $order->order_currency_code) }}
                                                </p>
                                            </div>
                                        @endif
    
                                        @if ($order->haveStockableItems())
                                            @if (core()->getConfigData('sales.taxes.sales.display_shipping_amount') == 'including_tax')
                                                <div class="flex w-full justify-between gap-x-5">
                                                    @lang('shop::app.customers.account.orders.view.information.shipping-handling')
    
                                                    <p>
                                                        {{ core()->formatPrice($order->shipping_amount_incl_tax, $order->order_currency_code) }}
                                                    </p>
                                                </div>
                                            @elseif (core()->getConfigData('sales.taxes.sales.display_shipping_amount') == 'both')
                                                <div class="flex w-full justify-between gap-x-5">
                                                    @lang('shop::app.customers.account.orders.view.information.shipping-handling-excl-tax')
    
                                                    <p>
                                                        {{ core()->formatPrice($order->shipping_amount, $order->order_currency_code) }}
                                                    </p>
                                                </div>
                                                
                                                <div class="flex w-full justify-between gap-x-5">
                                                    @lang('shop::app.customers.account.orders.view.information.shipping-handling-incl-tax')
    
                                                    <p>
                                                        {{ core()->formatPrice($order->shipping_amount_incl_tax, $order->order_currency_code) }}
                                                    </p>
                                                </div>
                                            @else
                                                <div class="flex w-full justify-between gap-x-5">
                                                    @lang('shop::app.customers.account.orders.view.information.shipping-handling')
    
                                                    <p>
                                                        {{ core()->formatPrice($order->shipping_amount, $order->order_currency_code) }}
                                                    </p>
                                                </div>
                                            @endif
                                        @endif
    
                                        <div class="flex w-full justify-between gap-x-5">
                                            @lang('shop::app.customers.account.orders.view.information.tax')
    
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
                                            @lang('shop::app.customers.account.orders.view.information.grand-total')
    
                                            <p>
                                                {{ core()->formatPrice($order->grand_total, $order->order_currency_code) }}
                                            </p>
                                        </div>
    
                                        <div class="flex w-full justify-between gap-x-5">
                                            @lang('shop::app.customers.account.orders.view.information.total-paid')
    
                                            <p>
                                                {{ core()->formatPrice($order->grand_total_invoiced, $order->order_currency_code) }}
                                            </p>
                                        </div>
    
                                        <div class="flex w-full justify-between gap-x-5">
                                            @lang('shop::app.customers.account.orders.view.information.total-refunded')
    
                                            <p>
                                                {{ core()->formatPrice($order->grand_total_refunded, $order->order_currency_code) }}
                                            </p>
                                        </div>
                                        
                                        <div class="flex w-full justify-between gap-x-5">
                                            @lang('shop::app.customers.account.orders.view.information.total-due')
    
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
                    </div>

                    <!-- For Mobile View -->
                    <div class="grid gap-4 md:hidden">
                        <div class="rounded-lg border">
                            <div class="grid gap-1.5 px-4 py-2.5 text-xs font-medium text-zinc-500 [&>*]:flex [&>*]:justify-between">
                                <div>
                                    @lang('shop::app.customers.account.orders.view.order-id'):

                                    <p class="text-black">#{{ $order->increment_id }}</p>
                                </div>
    
                                <div>
                                    @lang('shop::app.customers.account.orders.view.information.placed-on'):

                                    <p class="text-black">{{ core()->formatDate($order->created_at, 'd M Y') }}</p>
                                </div>

                                <div class="items-center">
                                    @lang('shop::app.customers.account.orders.view.status')

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

                            <div class="flex w-full justify-center rounded-b-lg border-t text-center">
                                @if ($order->canReorder())
                                    <a
                                        href="{{ route('shop.customers.account.orders.reorder', $order->id) }}"
                                        class="mx-auto w-full py-3 text-sm font-medium text-navyBlue hover:bg-zinc-100 max-sm:py-2"
                                    >
                                        @lang('shop::app.customers.account.orders.view.reorder-btn-title')
                                    </a>
                                @endif

                                @if ($order->canCancel())
                                    <!-- Seperator -->
                                    <span class="my-auto h-5 w-0.5 bg-zinc-200 py-3"></span>

                                    <a
                                        href="javascript:void(0);"
                                        class="mx-auto w-full py-3 text-sm font-medium hover:bg-zinc-100 max-sm:py-2"
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

                        <!-- Item Ordered -->
                        <x-shop::accordion
                            :is-active="true"
                            class="overflow-hidden rounded-lg !border-none !bg-gray-100"
                        >
                            <x-slot:header class="bg-gray-100 !px-4 py-3 text-sm font-medium max-sm:py-2">
                               @lang('shop::app.customers.account.orders.view.item-ordered')
                            </x-slot>
                
                            <x-slot:content class="grid gap-2.5 !bg-gray-100 !p-0">
                                @foreach ($order->items as $item)
                                    <div class="rounded-md rounded-t-none border border-t-0 bg-white px-4 py-2">
                                        <p class="pb-2 text-sm font-medium">
                                            {{ $item->name }}
    
                                            @if (isset($item->additional['attributes']))
                                                <div>
                                                    @foreach ($item->additional['attributes'] as $attribute)
                                                        <b  class="max-sm:!font-semibold">{{ $attribute['attribute_name'] }} : </b>{{ $attribute['option_label'] }}<br>
                                                    @endforeach
                                                </div>
                                            @endif
                                        </p>

                                        <div class="grid gap-1.5 text-xs font-medium">
                                            <!-- SKU -->
                                            <div class="flex justify-between">
                                                <span class="text-zinc-500">
                                                    @lang('shop::app.customers.account.orders.view.information.sku'): 
                                                </span>

                                                <span>
                                                    {{ $item->getTypeInstance()->getOrderedItem($item)->sku }}
                                                </span>
                                            </div>

                                            <!-- Quantity -->
                                            <div class="flex justify-between">
                                                <span class="text-zinc-500">
                                                    @lang('shop::app.customers.account.orders.view.information.item-status')
                                                </span>

                                                <div class="[&>*]:text-right">
                                                    @if($item->qty_ordered)
                                                        <p>
                                                            @lang('shop::app.customers.account.orders.view.information.ordered-item', ['qty_ordered' => $item->qty_ordered])
                                                        </p>
                                                    @endif

                                                    @if($item->qty_invoiced)
                                                        <p>
                                                            @lang('shop::app.customers.account.orders.view.information.invoiced-item', ['qty_invoiced' => $item->qty_invoiced])
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

                                            <!-- Price -->
                                            <div class="flex justify-between">
                                                <span class="text-zinc-500">
                                                    @lang('shop::app.customers.account.orders.view.information.price'):
                                                </span>

                                                <span class="[&>*]:text-right">
                                                    @if (core()->getConfigData('sales.taxes.sales.display_prices') == 'including_tax')
                                                        {{ core()->formatPrice($item->price_incl_tax, $order->order_currency_code) }}
                                                    @elseif (core()->getConfigData('sales.taxes.sales.display_prices') == 'both')
                                                        <p>
                                                            {{ core()->formatPrice($item->price_incl_tax, $order->order_currency_code) }}
                                                        </p>

                                                        <p class="whitespace-nowrap text-xs font-normal">
                                                            @lang('shop::app.customers.account.orders.view.information.excl-tax')
                                                            
                                                            <span class="font-medium">
                                                                {{ core()->formatPrice($item->price, $order->order_currency_code) }}
                                                            </span>
                                                        </p>
                                                    @else
                                                        {{ core()->formatPrice($item->price, $order->order_currency_code) }}
                                                    @endif
                                                </span>
                                            </div>

                                            <!-- Sub Total -->
                                            <div class="flex justify-between">
                                                <span class="text-zinc-500">
                                                    @lang('shop::app.customers.account.orders.view.invoices.subtotal'): 
                                                </span>

                                                <span class="[&>*]:text-right">
                                                    @if (core()->getConfigData('sales.taxes.sales.display_prices') == 'including_tax')
                                                        {{ core()->formatPrice($item->total_incl_tax, $order->order_currency_code) }}
                                                    @elseif (core()->getConfigData('sales.taxes.sales.display_prices') == 'both')
                                                        <p>
                                                            {{ core()->formatPrice($item->total_incl_tax, $order->order_currency_code) }}
                                                        </p>

                                                        <p class="whitespace-nowrap text-xs font-normal">
                                                            @lang('shop::app.customers.account.orders.view.invoices.excl-tax')
                                                            
                                                            <span class="font-medium">
                                                                {{ core()->formatPrice($item->total, $order->order_currency_code) }}
                                                            </span>
                                                        </p>
                                                    @else
                                                        {{ core()->formatPrice($item->total, $order->order_currency_code) }}
                                                    @endif
                                                </span>
                                            </div>

                                            <!-- Tax Percent -->
                                            <div class="flex justify-between">                                                
                                                <span class="text-zinc-500">
                                                    @lang('shop::app.customers.account.orders.view.information.tax-percent')
                                                </span>

                                                <p>
                                                    {{ $item->tax_percent }}
                                                </p>                                            
                                            </div>

                                            <!-- Tax Amount -->
                                            <div class="flex justify-between">
                                                <span class="text-zinc-500">
                                                    @lang('shop::app.customers.account.orders.view.information.tax-amount')
                                                </span>
        
                                                <p>
                                                    {{ $item->tax_amount }}
                                                </p>                                            
                                            </div>

                                            <!-- Grand Total -->
                                            <div class="flex justify-between">
                                                <span class="text-zinc-500">
                                                    @lang('shop::app.customers.account.orders.view.information.grand-total')
                                                </span>
        
                                                <p>
                                                    {{ core()->formatPrice($order->grand_total, $order->order_currency_code) }}
                                                </p>                                            
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </x-slot>
                        </x-shop::accordion>

                        <!--Summary -->
                        <div class="w-full rounded-md bg-gray-100">
                            <div class="rounded-t-md border-none !px-4 py-3 text-sm font-medium max-sm:py-2">
                                @lang('shop::app.customers.account.orders.view.information.order-summary')
                            </div>

                            <div class="grid gap-1.5 rounded-md rounded-t-none border border-t-0 bg-white px-4 py-3 text-xs font-medium">
                                @if (core()->getConfigData('sales.taxes.sales.display_subtotal') == 'including_tax')
                                    <div class="flex w-full justify-between gap-x-5">
                                        <p class="text-zinc-500">
                                            @lang('shop::app.customers.account.orders.view.information.subtotal')                                            
                                        </p>

                                        <p>
                                            {{ core()->formatPrice($order->sub_total_incl_tax, $order->order_currency_code) }}
                                        </p>
                                    </div>
                                @elseif (core()->getConfigData('sales.taxes.sales.display_subtotal') == 'both')
                                    <div class="flex w-full justify-between gap-x-5">
                                        <p class="text-zinc-500">
                                            @lang('shop::app.customers.account.orders.view.information.subtotal-excl-tax')
                                        </p>

                                        <p>
                                            {{ core()->formatPrice($order->sub_total, $order->order_currency_code) }}
                                        </p>
                                    </div>
                                    
                                    <div class="flex w-full justify-between gap-x-5">
                                        <p class="text-zinc-500">
                                        
                                            @lang('shop::app.customers.account.orders.view.information.subtotal-incl-tax')
                                        </p>

                                        <p>
                                            {{ core()->formatPrice($order->sub_total_incl_tax, $order->order_currency_code) }}
                                        </p>
                                    </div>
                                @else
                                    <div class="flex w-full justify-between gap-x-5">
                                        <p class="text-zinc-500">
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
                                            <p class="text-zinc-500">
                                                @lang('shop::app.customers.account.orders.view.information.shipping-handling')
                                            </p>

                                            <p>
                                                {{ core()->formatPrice($order->shipping_amount_incl_tax, $order->order_currency_code) }}
                                            </p>
                                        </div>
                                    @elseif (core()->getConfigData('sales.taxes.sales.display_shipping_amount') == 'both')
                                        <div class="flex w-full justify-between gap-x-5">
                                            <p class="text-zinc-500">
                                                @lang('shop::app.customers.account.orders.view.information.shipping-handling-excl-tax')
                                            </p>

                                            <p>
                                                {{ core()->formatPrice($order->shipping_amount, $order->order_currency_code) }}
                                            </p>
                                        </div>
                                        
                                        <div class="flex w-full justify-between gap-x-5">
                                            <p class="text-zinc-500">
                                                @lang('shop::app.customers.account.orders.view.information.shipping-handling-incl-tax')
                                            </p>

                                            <p>
                                                {{ core()->formatPrice($order->shipping_amount_incl_tax, $order->order_currency_code) }}
                                            </p>
                                        </div>
                                    @else
                                        <div class="flex w-full justify-between gap-x-5">
                                            <p class="text-zinc-500">
                                                @lang('shop::app.customers.account.orders.view.information.shipping-handling')
                                            </p>

                                            <p>
                                                {{ core()->formatPrice($order->shipping_amount, $order->order_currency_code) }}
                                            </p>
                                        </div>
                                    @endif
                                @endif

                                <!-- Tax Informations -->
                                <div class="flex w-full justify-between gap-x-5">
                                    <p class="text-zinc-500">
                                        @lang('shop::app.customers.account.orders.view.information.tax')
                                    </p>

                                    <p>
                                        {{ core()->formatPrice($order->tax_amount, $order->order_currency_code) }}
                                    </p>
                                </div>

                                @if ($order->base_discount_amount > 0)
                                    <div class="flex w-full justify-between gap-x-5">
                                        <p class="text-zinc-500">
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

                                <!-- Grand Total -->
                                <div class="flex w-full justify-between gap-x-5 font-semibold">
                                    <p class="text-zinc-500">
                                        @lang('shop::app.customers.account.orders.view.information.grand-total')
                                    </p>

                                    <p>
                                        {{ core()->formatPrice($order->grand_total, $order->order_currency_code) }}
                                    </p>
                                </div>

                                <!-- Total Paid -->
                                <div class="flex w-full justify-between gap-x-5">
                                    <p class="text-zinc-500">
                                        @lang('shop::app.customers.account.orders.view.information.total-paid')
                                    </p>

                                    <p>
                                        {{ core()->formatPrice($order->grand_total_invoiced, $order->order_currency_code) }}
                                    </p>
                                </div>

                                <!-- Refund Details -->
                                <div class="flex w-full justify-between gap-x-5">
                                    <p class="text-zinc-500">
                                        @lang('shop::app.customers.account.orders.view.information.total-refunded')
                                    </p>

                                    <p>
                                        {{ core()->formatPrice($order->grand_total_refunded, $order->order_currency_code) }}
                                    </p>
                                </div>

                                <!-- Total Due -->
                                <div class="flex w-full justify-between gap-x-5">
                                    <p class="text-zinc-500">
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
                </x-shop::tabs.item>

                <!-- Invoices tab -->
                @if ($order->invoices->count())
                    <x-shop::tabs.item
                        class="max-md:!px-0 max-md:pb-0 max-md:pt-2"
                        :title="trans('shop::app.customers.account.orders.view.invoices.invoices')"
                    >
                        @foreach ($order->invoices as $invoice)
                            <!-- For Mobile View -->
                            <div class="grid gap-4 md:hidden">
                                <div class="rounded-lg border">
                                    <div class="grid gap-1.5 px-4 py-2.5 text-xs font-medium text-zinc-500 [&>*]:flex [&>*]:justify-between">
                                        <div class="flex justify-between">
                                            @lang('shop::app.customers.account.orders.view.invoices.individual-invoice', ['invoice_id' => $invoice->increment_id ?? $invoice->id])
            
                                            <a href="{{ route('shop.customers.account.orders.print-invoice', $invoice->id) }}">
                                                <div class="flex items-center gap-1 font-medium text-black">
                                                    <span class="icon-download text-sm font-semibold"></span>

                                                    @lang('shop::app.customers.account.orders.view.invoices.print')
                                                </div>
                                            </a>
                                        </div>
                                    </div>
                                </div>

                                <!-- Item  Invoiced -->
                                <x-shop::accordion
                                    :is-active="true"
                                    class="overflow-hidden rounded-lg !border-none !bg-gray-100"
                                >
                                    <x-slot:header class="!mb-0 rounded-t-md bg-gray-100 !px-4 py-3 text-sm font-medium max-sm:py-2">
                                        @lang('shop::app.customers.account.orders.view.item-invoiced')
                                    </x-slot>
                        
                                    <x-slot:content class="grid gap-2.5 !bg-gray-100 !p-0">
                                        @foreach ($invoice->items as $item)
                                            <div class="rounded-md rounded-t-none border border-t-0 bg-white px-4 py-2">
                                                <p class="pb-2 text-sm font-medium">
                                                    {{ $item->name }}
                                                </p>

                                                <div class="grid gap-1.5 text-xs font-medium">
                                                    <!-- SKU -->
                                                    <div class="flex justify-between">
                                                        <span class="text-zinc-500">
                                                            @lang('shop::app.customers.account.orders.view.invoices.sku'):
                                                        </span>

                                                        <span>
                                                            {{ $item->getTypeInstance()->getOrderedItem($item)->sku }}
                                                        </span>
                                                    </div>

                                                    <!-- Price -->
                                                    <div class="flex justify-between">
                                                        <span class="text-zinc-500">
                                                            @lang('shop::app.customers.account.orders.view.invoices.price'):
                                                        </span>

                                                        <span class="[&>*]:text-right">
                                                            @if (core()->getConfigData('sales.taxes.sales.display_prices') == 'including_tax')
                                                                {{ core()->formatPrice($item->price_incl_tax, $order->order_currency_code) }}
                                                            @elseif (core()->getConfigData('sales.taxes.sales.display_prices') == 'both')
                                                                <p>
                                                                    {{ core()->formatPrice($item->price_incl_tax, $order->order_currency_code) }}
                                                                </p>    
    
                                                                <p class="whitespace-nowrap text-xs font-normal">
                                                                    @lang('shop::app.customers.account.orders.view.information.excl-tax')
                                                                    
                                                                    <span class="font-medium">
                                                                        {{ core()->formatPrice($item->price, $order->order_currency_code) }}
                                                                    </span>
                                                                </p>
                                                            @else
                                                                {{ core()->formatPrice($item->price, $order->order_currency_code) }}
                                                            @endif
                                                        </span>
                                                    </div>

                                                    <!-- Quantity -->
                                                    <div class="flex justify-between">
                                                        <span class="text-zinc-500">
                                                            @lang('shop::app.customers.account.orders.view.invoices.qty')
                                                        </span>

                                                        <span>
                                                            {{ $item->qty }}
                                                        </span>
                                                    </div>

                                                    <!-- Sub Total -->
                                                    <div class="flex justify-between">
                                                        <span class="text-zinc-500">
                                                            @lang('shop::app.customers.account.orders.view.invoices.subtotal'): 
                                                        </span>

                                                        <span class="[&>*]:text-right">
                                                            @if (core()->getConfigData('sales.taxes.sales.display_prices') == 'including_tax')
                                                                {{ core()->formatPrice($item->total_incl_tax, $order->order_currency_code) }}
                                                            @elseif (core()->getConfigData('sales.taxes.sales.display_prices') == 'both')
                                                                <p>
                                                                    {{ core()->formatPrice($item->total_incl_tax, $order->order_currency_code) }}
                                                                </p>    

                                                                <p class="whitespace-nowrap text-xs font-normal">
                                                                    @lang('shop::app.customers.account.orders.view.invoices.excl-tax')
                                                                    
                                                                    <span class="font-medium">
                                                                        {{ core()->formatPrice($item->total, $order->order_currency_code) }}
                                                                    </span>
                                                                </p>
                                                            @else
                                                                {{ core()->formatPrice($item->total, $order->order_currency_code) }}
                                                            @endif
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </x-slot>
                                </x-shop::accordion>

                                <!--Summary -->
                                <div class="w-full rounded-md bg-gray-100">
                                    <div class="rounded-t-md border-none !px-4 py-3 text-sm font-medium max-sm:py-2">
                                        @lang('Order Summary')
                                    </div>

                                    <div class="grid gap-1.5 rounded-md rounded-t-none border border-t-0 bg-white px-4 py-3 text-xs font-medium">
                                        @if (core()->getConfigData('sales.taxes.sales.display_subtotal') == 'including_tax')
                                            <div class="flex w-full justify-between gap-x-5">
                                                <p class="text-zinc-500">
                                                    @lang('shop::app.customers.account.orders.view.invoices.subtotal')
                                                </p>

                                                <p>
                                                    {{ core()->formatPrice($invoice->sub_total_incl_tax, $order->order_currency_code) }}
                                                </p>
                                            </div>
                                        @elseif (core()->getConfigData('sales.taxes.sales.display_subtotal') == 'both')
                                            <div class="flex w-full justify-between gap-x-5">
                                                <p class="text-zinc-500">
                                                    @lang('shop::app.customers.account.orders.view.invoices.subtotal-excl-tax')
                                                </p>

                                                <p>
                                                    {{ core()->formatPrice($invoice->sub_total, $order->order_currency_code) }}
                                                </p>
                                            </div>
                                        @else
                                            <div class="flex w-full justify-between gap-x-5">
                                                <p class="text-zinc-500">
                                                    @lang('shop::app.customers.account.orders.view.invoices.subtotal')
                                                </p>

                                                <p>
                                                    {{ core()->formatPrice($invoice->sub_total, $order->order_currency_code) }}
                                                </p>
                                            </div>
                                        @endif

                                        @if (core()->getConfigData('sales.taxes.sales.display_shipping_amount') == 'including_tax')
                                            <div class="flex w-full justify-between gap-x-5">
                                                <p class="text-zinc-500">
                                                    @lang('shop::app.customers.account.orders.view.information.shipping-handling')
                                                </p>

                                                <p>
                                                    {{ core()->formatPrice($invoice->shipping_amount_incl_tax, $order->order_currency_code) }}
                                                </p>
                                            </div>
                                        @elseif (core()->getConfigData('sales.taxes.sales.display_shipping_amount') == 'both')
                                            <div class="flex w-full justify-between gap-x-5">
                                                <p class="text-zinc-500">
                                                    @lang('shop::app.customers.account.orders.view.invoices.shipping-handling-excl-tax')
                                                </p>

                                                <p>
                                                    {{ core()->formatPrice($invoice->shipping_amount, $order->order_currency_code) }}
                                                </p>
                                            </div>

                                            <div class="flex w-full justify-between gap-x-5">
                                                <p class="text-zinc-500">
                                                    @lang('shop::app.customers.account.orders.view.invoices.shipping-handling-incl-tax')
                                                </p>

                                                <p>
                                                    {{ core()->formatPrice($invoice->shipping_amount_incl_tax, $order->order_currency_code) }}
                                                </p>
                                            </div>
                                        @else
                                            <div class="flex w-full justify-between gap-x-5">
                                                <p class="text-zinc-500">
                                                    @lang('shop::app.customers.account.orders.view.invoices.shipping-handling')
                                                </p>

                                                <p>
                                                    {{ core()->formatPrice($invoice->shipping_amount, $order->order_currency_code) }}
                                                </p>
                                            </div>
                                        @endif

                                        @if ($invoice->base_discount_amount > 0)
                                            <div class="flex w-full justify-between gap-x-5">
                                                <p class="text-zinc-500">
                                                    @lang('shop::app.customers.account.orders.view.invoices.discount')
                                                </p>

                                                <p>
                                                    {{ core()->formatPrice($invoice->discount_amount, $order->order_currency_code) }}
                                                </p>
                                            </div>
                                        @endif

                                        <div class="flex w-full justify-between gap-x-5">
                                            <p class="text-zinc-500">
                                                @lang('shop::app.customers.account.orders.view.invoices.tax')
                                            </p>

                                            <p>
                                                {{ core()->formatPrice($invoice->tax_amount, $order->order_currency_code) }}
                                            </p>
                                        </div>

                                        <div class="flex w-full justify-between gap-x-5 font-semibold">
                                            <p class="text-zinc-500">
                                                @lang('shop::app.customers.account.orders.view.invoices.grand-total')
                                            </p>

                                            <p>
                                                {{ core()->formatPrice($invoice->grand_total, $order->order_currency_code) }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- For Desktop View -->
                            <div class="max-md:hidden">
                                <div class="flex justify-between">
                                    <label class="text-base font-medium">
                                        @lang('shop::app.customers.account.orders.view.invoices.individual-invoice', ['invoice_id' => $invoice->increment_id ?? $invoice->id])
                                    </label>
    
                                    <a href="{{ route('shop.customers.account.orders.print-invoice', $invoice->id) }}">
                                        <div class="flex items-center gap-1 font-semibold">
                                            <span class="icon-download text-2xl"></span>

                                            @lang('shop::app.customers.account.orders.view.invoices.print')
                                        </div>
                                    </a>
                                </div>
                                
                                <div class="relative mt-8 overflow-x-auto rounded-xl border">
                                    <table class="w-full text-left">
                                        <thead class="border-b border-zinc-200 bg-zinc-100 text-sm text-black">
                                            <tr class="[&>*]:font-medium [&>*]:px-6 [&>*]:py-4">
                                                <th scope="col">
                                                    @lang('shop::app.customers.account.orders.view.invoices.sku')
                                                </th>
    
                                                <th scope="col">
                                                    @lang('shop::app.customers.account.orders.view.invoices.product-name')
                                                </th>
    
                                                <th scope="col">
                                                    @lang('shop::app.customers.account.orders.view.invoices.price')
                                                </th>
    
                                                <th scope="col">
                                                    @lang('shop::app.customers.account.orders.view.invoices.qty')
                                                </th>
    
                                                <th scope="col">
                                                    @lang('shop::app.customers.account.orders.view.invoices.subtotal')
                                                </th>
                                            </tr>
                                        </thead>
    
                                        <tbody>
                                            @foreach ($invoice->items as $item)
                                                <tr class="border-b bg-white text-black [&>*]:font-medium [&>*]:px-6 [&>*]:py-4">
                                                    <td data-value="@lang('shop::app.customers.account.orders.view.invoices.sku')">
                                                        {{ $item->getTypeInstance()->getOrderedItem($item)->sku }}
                                                    </td>
    
                                                    <td data-value="@lang('shop::app.customers.account.orders.view.invoices.product-name')">
                                                        {{ $item->name }}
                                                    </td>
    
                                                    <td
                                                        class="flex flex-col"
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
    
                                                    <td data-value="@lang('shop::app.customers.account.orders.view.invoices.qty')">
                                                        {{ $item->qty }}
                                                    </td>
    
                                                    <td
                                                        class="flex flex-col"
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
    
                                <div class="mt-8 flex items-start gap-10 max-lg:gap-5">
                                    <div class="flex flex-auto justify-end">
                                        <div class="grid max-w-max gap-2 text-sm">
                                            @if (core()->getConfigData('sales.taxes.sales.display_subtotal') == 'including_tax')
                                                <div class="flex w-full justify-between gap-x-5">
                                                    @lang('shop::app.customers.account.orders.view.invoices.subtotal')
    
                                                    <p>
                                                        {{ core()->formatPrice($invoice->sub_total_incl_tax, $order->order_currency_code) }}
                                                    </p>
                                                </div>
                                            @elseif (core()->getConfigData('sales.taxes.sales.display_subtotal') == 'both')
                                                <div class="flex w-full justify-between gap-x-5">
                                                    @lang('shop::app.customers.account.orders.view.invoices.subtotal-excl-tax')
    
                                                    <p>
                                                        {{ core()->formatPrice($invoice->sub_total, $order->order_currency_code) }}
                                                    </p>
                                                </div>
                                                
                                                <div class="flex w-full justify-between gap-x-5">
                                                    @lang('shop::app.customers.account.orders.view.invoices.subtotal-incl-tax')
    
                                                    <p>
                                                        {{ core()->formatPrice($invoice->sub_total_incl_tax, $order->order_currency_code) }}
                                                    </p>
                                                </div>
                                            @else
                                                <div class="flex w-full justify-between gap-x-5">
                                                    @lang('shop::app.customers.account.orders.view.invoices.subtotal')
    
                                                    <p>
                                                        {{ core()->formatPrice($invoice->sub_total, $order->order_currency_code) }}
                                                    </p>
                                                </div>
                                            @endif
    
                                            @if (core()->getConfigData('sales.taxes.sales.display_shipping_amount') == 'including_tax')
                                                <div class="flex w-full justify-between gap-x-5">
                                                    @lang('shop::app.customers.account.orders.view.invoices.shipping-handling')
    
                                                    <p>
                                                        {{ core()->formatPrice($invoice->shipping_amount_incl_tax, $order->order_currency_code) }}
                                                    </p>
                                                </div>
                                            @elseif (core()->getConfigData('sales.taxes.sales.display_shipping_amount') == 'both')
                                                <div class="flex w-full justify-between gap-x-5">
                                                    @lang('shop::app.customers.account.orders.view.invoices.shipping-handling-excl-tax')
    
                                                    <p>
                                                        {{ core()->formatPrice($invoice->shipping_amount, $order->order_currency_code) }}
                                                    </p>
                                                </div>
                                                
                                                <div class="flex w-full justify-between gap-x-5">
                                                    @lang('shop::app.customers.account.orders.view.invoices.shipping-handling-incl-tax')
    
                                                    <p>
                                                        {{ core()->formatPrice($invoice->shipping_amount_incl_tax, $order->order_currency_code) }}
                                                    </p>
                                                </div>
                                            @else
                                                <div class="flex w-full justify-between gap-x-5">
                                                    @lang('shop::app.customers.account.orders.view.invoices.shipping-handling')
    
                                                    <p>
                                                        {{ core()->formatPrice($invoice->shipping_amount, $order->order_currency_code) }}
                                                    </p>
                                                </div>
                                            @endif
    
                                            @if ($invoice->base_discount_amount > 0)
                                                <div class="flex w-full justify-between gap-x-5">
                                                    @lang('shop::app.customers.account.orders.view.invoices.discount')
    
                                                    <p>
                                                        {{ core()->formatPrice($invoice->discount_amount, $order->order_currency_code) }}
                                                    </p>
                                                </div>
                                            @endif
    
                                            <div class="flex w-full justify-between gap-x-5">
                                                @lang('shop::app.customers.account.orders.view.invoices.tax')
    
                                                <p>
                                                    {{ core()->formatPrice($invoice->tax_amount, $order->order_currency_code) }}
                                                </p>
                                            </div>
    
                                            <div class="flex w-full justify-between gap-x-5 font-semibold">
                                                @lang('shop::app.customers.account.orders.view.invoices.grand-total')
    
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
                    <x-shop::tabs.item 
                        class="max-md:!px-0 max-md:py-1.5"
                        title="{{ trans('shop::app.customers.account.orders.view.shipments.shipments') }}"
                    >
                        @foreach ($order->shipments as $shipment)
                            <!-- For Desktop View -->
                            <div class="max-md:hidden">
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

                                <!-- Table of Contents -->
                                <div class="relative mt-8 overflow-x-auto rounded-xl border max-md:hidden">
                                    <table class="w-full text-left text-sm">
                                        <thead class="border-b border-zinc-200 bg-zinc-100 text-sm text-black">
                                            <tr class="[&>*]:font-medium [&>*]:px-6 [&>*]:py-4">
                                                <th scope="col">
                                                    @lang('shop::app.customers.account.orders.view.shipments.sku')
                                                </th>
    
                                                <th scope="col">
                                                    @lang('shop::app.customers.account.orders.view.shipments.product-name')
                                                </th>
    
                                                <th scope="col">
                                                    @lang('shop::app.customers.account.orders.view.shipments.qty')
                                                </th>
                                            </tr>
                                        </thead>
    
                                        <tbody>
                                            @foreach ($shipment->items as $item)
                                                <tr class="border-b bg-white [&>*]:font-medium [&>*]:px-6 [&>*]:py-4 [&>*]:text-black">
                                                    <td data-value="@lang('shop::app.customers.account.orders.view.shipments.sku')">
                                                        {{ $item->sku }}
                                                    </td>
    
                                                    <td data-value="@lang('shop::app.customers.account.orders.view.shipments.product-name')">
                                                        {{ $item->name }}
                                                    </td>
    
                                                    <td data-value="@lang('shop::app.customers.account.orders.view.shipments.qty')">
                                                        {{ $item->qty }}
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- For Mobile view -->
                            <div class="grid gap-4 md:hidden">
                                <div class="rounded-lg border">
                                    <div class="grid gap-1.5 px-4 py-2.5 text-xs font-medium text-zinc-500 [&>*]:flex [&>*]:justify-between">
                                        <div class="flex justify-between">
                                            @lang('shop::app.customers.account.orders.view.shipments.tracking-number'):
            
                                            <span>
                                                {{  $shipment->track_number }}
                                            </span>
                                        </div>

                                        @lang('shop::app.customers.account.orders.view.shipments.individual-shipment', ['shipment_id' => $shipment->id])
                                    </div>
                                </div>

                                <x-shop::accordion
                                    :is-active="true"
                                    class="overflow-hidden rounded-lg !border-none !bg-gray-100"
                                >
                                    <x-slot:header class="!mb-0 rounded-t-md bg-gray-100 !px-4 py-3 text-sm font-medium max-sm:py-2">
                                        @lang('shop::app.customers.account.orders.view.item-shipped')
                                    </x-slot>
                        
                                    <x-slot:content class="grid gap-2.5 !bg-gray-100 !p-0">
                                        @foreach ($shipment->items as $item)
                                            <div class="rounded-md rounded-t-none border border-t-0 bg-white px-4 py-2">
                                                <p class="pb-2 text-sm font-medium">
                                                    {{ $item->name }}
                                                </p>

                                                <div class="grid gap-1.5 text-xs font-medium">
                                                    <!-- SKU -->
                                                    <div class="flex justify-between">
                                                        <span class="text-zinc-500">
                                                            @lang('shop::app.customers.account.orders.view.shipments.sku'):
                                                        </span>

                                                        <span>
                                                            {{ $item->sku }}
                                                        </span>
                                                    </div>

                                                    <!-- Quantity -->
                                                    <div class="flex justify-between">
                                                        <span class="text-zinc-500">
                                                            @lang('shop::app.customers.account.orders.view.shipments.qty'):
                                                        </span>

                                                        <span>{{ $item->qty }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </x-slot>
                                </x-shop::accordion>
                            </div>
                        @endforeach
                    </x-shop::tabs.item>
                @endif

                <!-- Refund Tab -->
                @if ($order->refunds->count())
                    <x-shop::tabs.item
                        class="max-md:!px-0 max-md:py-1.5"
                        :title="trans('shop::app.customers.account.orders.view.refunds.refunds')"
                    >
                        @foreach ($order->refunds as $refund)
                            <!-- For Desktop View -->
                            <div class="max-md:hidden">
                                <div class="text-base font-medium">
                                    <span>
                                        @lang('shop::app.customers.account.orders.view.refunds.individual-refund', ['refund_id' => $refund->id])
                                    </span>
                                </div>

                                <div class="relative mt-8 overflow-x-auto rounded-xl border">
                                    <table class="w-full text-left text-sm">
                                        <thead class="border-b border-zinc-200 bg-zinc-100 text-sm text-black">
                                            <tr class="[&>*]:font-medium [&>*]:px-6 [&>*]:py-4">
                                                <th scope="col">
                                                    @lang('shop::app.customers.account.orders.view.refunds.sku')
                                                </th>

                                                <th scope="col">
                                                    @lang('shop::app.customers.account.orders.view.refunds.product-name')
                                                </th>

                                                <th scope="col">
                                                    @lang('shop::app.customers.account.orders.view.refunds.price')
                                                </th>

                                                <th scope="col">
                                                    @lang('shop::app.customers.account.orders.view.refunds.qty')
                                                </th>

                                                <th scope="col">
                                                    @lang('shop::app.customers.account.orders.view.refunds.subtotal')
                                                </th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            @foreach ($refund->items as $item)
                                                <tr class="border-b bg-white [&>*]:font-medium [&>*]:px-6 [&>*]:py-4 [&>*]:text-black">
                                                    <td data-value="@lang('shop::app.customers.account.orders.view.refunds.sku')">
                                                        {{ $item->child ? $item->child->sku : $item->sku }}
                                                    </td>

                                                    <td data-value="@lang('shop::app.customers.account.orders.view.refunds.product-name')">
                                                        {{ $item->name }}
                                                    </td>

                                                    <td
                                                        class="flex flex-col"
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

                                                    <td data-value="@lang('shop::app.customers.account.orders.view.refunds.qty')">
                                                        {{ $item->qty }}
                                                    </td>

                                                    <td
                                                        class="flex flex-col"
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
                            </div>

                            <!-- For Mobile View -->
                            <div class="grid gap-4 md:hidden">
                                <div class="rounded-lg border">
                                    <div class="grid gap-1.5 px-4 py-2.5 text-xs font-medium text-zinc-500 [&>*]:flex [&>*]:justify-between">
                                        @lang('shop::app.customers.account.orders.view.refunds.individual-refund', ['refund_id' => $refund->id])
                                    </div>
                                </div>

                                <x-shop::accordion
                                    :is-active="true"
                                    class="overflow-hidden rounded-lg !border-none !bg-gray-100"
                                >
                                    <x-slot:header class="!mb-0 rounded-t-md bg-gray-100 !px-4 py-3 text-sm font-medium max-sm:py-2">
                                        @lang('shop::app.customers.account.orders.view.item-refunded')
                                    </x-slot>
                        
                                    <x-slot:content class="grid gap-2.5 !bg-gray-100 !p-0">
                                        @foreach ($invoice->items as $item)
                                            <div class="rounded-md rounded-t-none border border-t-0 bg-white px-4 py-2">
                                                <p class="pb-2 text-sm font-medium">
                                                    {{ $item->name }}
                                                </p>

                                                <div class="grid gap-1.5 text-xs font-medium">
                                                    <!-- SKU -->
                                                    <div class="flex justify-between">
                                                        <span class="text-zinc-500">
                                                            @lang('shop::app.customers.account.orders.view.refunds.sku'):
                                                        </span>

                                                        <span>
                                                            {{ $item->child ? $item->child->sku : $item->sku }}
                                                        </span>
                                                    </div>

                                                    <!-- Price -->
                                                    <div class="flex justify-between">
                                                        <span class="text-zinc-500">
                                                            @lang('shop::app.customers.account.orders.view.refunds.price'):
                                                        </span>

                                                        <span class="[&>*]:text-right">
                                                            @if (core()->getConfigData('sales.taxes.sales.display_prices') == 'including_tax')
                                                                {{ core()->formatPrice($item->price_incl_tax, $order->order_currency_code) }}
                                                            @elseif (core()->getConfigData('sales.taxes.sales.display_prices') == 'both')
                                                                <p>
                                                                    {{ core()->formatPrice($item->price_incl_tax, $order->order_currency_code) }}
                                                                </p>
        
                                                                <p class="whitespace-nowrap text-xs font-normal">
                                                                    @lang('shop::app.customers.account.orders.view.information.excl-tax')
                                                                    
                                                                    <span class="font-medium">
                                                                        {{ core()->formatPrice($item->price, $order->order_currency_code) }}
                                                                    </span>
                                                                </p>
                                                            @else
                                                                {{ core()->formatPrice($item->price, $order->order_currency_code) }}
                                                            @endif
                                                        </span>
                                                    </div>

                                                    <!-- Quantity -->
                                                    <div class="flex justify-between">
                                                        <span class="text-zinc-500">
                                                            @lang('shop::app.customers.account.orders.view.refunds.qty')
                                                        </span>

                                                        <span>
                                                            {{ $item->qty }}
                                                        </span>
                                                    </div>

                                                    <!-- Sub Total -->
                                                    <div class="flex justify-between">
                                                        <span class="text-zinc-500">
                                                            @lang('shop::app.customers.account.orders.view.refunds.subtotal'): 
                                                        </span>

                                                        <span class="[&>*]:text-right">
                                                            @if (core()->getConfigData('sales.taxes.sales.display_prices') == 'including_tax')
                                                                {{ core()->formatPrice($item->total_incl_tax, $order->order_currency_code) }}
                                                            @elseif (core()->getConfigData('sales.taxes.sales.display_prices') == 'both')
                                                                <p>
                                                                    {{ core()->formatPrice($item->total_incl_tax, $order->order_currency_code) }}
                                                                </p>

                                                                <p class="whitespace-nowrap text-xs font-normal">
                                                                    @lang('shop::app.customers.account.orders.view.information.excl-tax')
                                                                    
                                                                    <span class="font-medium">
                                                                        {{ core()->formatPrice($item->total, $order->order_currency_code) }}
                                                                    </span>
                                                                </p>
                                                            @else
                                                                {{ core()->formatPrice($item->total, $order->order_currency_code) }}
                                                            @endif
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </x-slot>
                                </x-shop::accordion>

                                <!-- Summary -->
                                <div class="w-full rounded-md bg-gray-100">
                                    <div class="rounded-t-md border-none !px-4 py-3 text-sm font-medium max-sm:py-2">
                                        @lang('shop::app.customers.account.orders.view.refunds.order-summary')
                                    </div>

                                    <div class="grid gap-1.5 rounded-md rounded-t-none border border-t-0 bg-white px-4 py-3 text-xs font-medium">
                                        @if (core()->getConfigData('sales.taxes.sales.display_subtotal') == 'including_tax')
                                            <div class="flex w-full justify-between gap-x-5">
                                                <p class="text-zinc-500">
                                                    @lang('shop::app.customers.account.orders.view.refunds.subtotal')                                          
                                                </p>
        
                                                <p>
                                                    {{ core()->formatPrice($refund->sub_total_incl_tax, $order->order_currency_code) }}
                                                </p>
                                            </div>
                                        @elseif (core()->getConfigData('sales.taxes.sales.display_subtotal') == 'both')
                                            <div class="flex w-full justify-between gap-x-5">
                                                <p class="text-zinc-500">
                                                    @lang('shop::app.customers.account.orders.view.refunds.subtotal-excl-tax')
                                                </p>
        
                                                <p>
                                                    {{ core()->formatPrice($refund->sub_total, $order->order_currency_code) }}
                                                </p>
                                            </div>
                                            
                                            <div class="flex w-full justify-between gap-x-5">
                                                <p class="text-zinc-500">
                                                
                                                    @lang('shop::app.customers.account.orders.view.refunds.subtotal-incl-tax')
                                                </p>
        
                                                <p>
                                                    {{ core()->formatPrice($refund->sub_total_incl_tax, $order->order_currency_code) }}
                                                </p>
                                            </div>
                                        @else
                                            <div class="flex w-full justify-between gap-x-5">
                                                <p class="text-zinc-500">
                                                    @lang('shop::app.customers.account.orders.view.refunds.subtotal')
                                                </p>
        
                                                <p>
                                                    {{ core()->formatPrice($refund->sub_total, $order->order_currency_code) }}
                                                </p>
                                            </div>
                                        @endif
        
                                        @if (core()->getConfigData('sales.taxes.sales.display_shipping_amount') == 'including_tax')
                                            <div class="flex w-full justify-between gap-x-5">
                                                <p class="text-zinc-500">
                                                    @lang('shop::app.customers.account.orders.view.refunds.shipping-handling')
                                                </p>
    
                                                <p>
                                                    {{ core()->formatPrice($refund->shipping_amount_incl_tax, $order->order_currency_code) }}
                                                </p>
                                            </div>
                                        @elseif (core()->getConfigData('sales.taxes.sales.display_shipping_amount') == 'both')
                                            <div class="flex w-full justify-between gap-x-5">
                                                <p class="text-zinc-500">
                                                    @lang('shop::app.customers.account.orders.view.refunds.shipping-handling-excl-tax')
                                                </p>
    
                                                <p>
                                                    {{ core()->formatPrice($refund->shipping_amount, $order->order_currency_code) }}
                                                </p>
                                            </div>
                                            
                                            <div class="flex w-full justify-between gap-x-5">
                                                <p class="text-zinc-500">
                                                    @lang('shop::app.customers.account.orders.view.refunds.shipping-handling-incl-tax')
                                                </p>
    
                                                <p>
                                                    {{ core()->formatPrice($refund->shipping_amount_incl_tax, $order->order_currency_code) }}
                                                </p>
                                            </div>
                                        @else
                                            <div class="flex w-full justify-between gap-x-5">
                                                <p class="text-zinc-500">
                                                    @lang('shop::app.customers.account.orders.view.refunds.shipping-handling')
                                                </p>
    
                                                <p>
                                                    {{ core()->formatPrice($refund->shipping_amount, $order->order_currency_code) }}
                                                </p>
                                            </div>
                                        @endif

                                        <!-- Discount -->
                                        @if ($refund->discount_amount > 0)
                                            <div class="flex w-full justify-between gap-x-5">
                                                @lang('shop::app.customers.account.orders.view.refunds.discount')

                                                <p>
                                                    {{ core()->formatPrice($order->discount_amount, $order->order_currency_code) }}
                                                </p>
                                            </div>
                                        @endif

                                        <!-- Refund Tax -->
                                        @if ($refund->tax_amount > 0)
                                            <div class="flex w-full justify-between gap-x-5">
                                                <p class="text-zinc-500">
                                                    @lang('shop::app.customers.account.orders.view.refunds.tax')
                                                </p>

                                                <p>
                                                    {{ core()->formatPrice($refund->tax_amount, $order->order_currency_code) }}
                                                </p>
                                            </div>
                                        @endif

                                        <!-- Adjustments Refund -->
                                        <div class="flex w-full justify-between gap-x-5">
                                            <p class="text-zinc-500">
                                                @lang('shop::app.customers.account.orders.view.refunds.adjustment-refund')
                                            </p>

                                            <p>
                                                {{ core()->formatPrice($refund->adjustment_refund, $order->order_currency_code) }}
                                            </p>
                                        </div>

                                        <!-- Adjustment fee -->
                                        <div class="flex w-full justify-between gap-x-5">
                                            <p class="text-zinc-500">
                                                @lang('shop::app.customers.account.orders.view.refunds.adjustment-fee')
                                            </p>

                                            <p>
                                                {{ core()->formatPrice($refund->adjustment_fee, $order->order_currency_code) }}
                                            </p>
                                        </div>

                                        <!-- Grand Total -->
                                        <div class="flex w-full justify-between gap-x-5 font-semibold">
                                            <p class="text-zinc-500">
                                                @lang('shop::app.customers.account.orders.view.refunds.grand-total')
                                            </p>

                                            <p>
                                                {{ core()->formatPrice($refund->grand_total, $order->order_currency_code) }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Summary -->
                            <div class="mt-8 flex items-start gap-10 max-lg:gap-5 max-md:hidden">
                                <div class="flex flex-auto justify-end">
                                    <div class="grid max-w-max gap-2 text-sm">
                                        @if (core()->getConfigData('sales.taxes.sales.display_subtotal') == 'including_tax')
                                            <div class="flex w-full justify-between gap-x-5 text-sm">
                                                @lang('shop::app.customers.account.orders.view.refunds.subtotal')

                                                <p>
                                                    {{ core()->formatPrice($refund->sub_total_incl_tax, $order->order_currency_code) }}
                                                </p>
                                            </div>
                                        @elseif (core()->getConfigData('sales.taxes.sales.display_subtotal') == 'both')
                                            <div class="flex w-full justify-between gap-x-5 text-sm">
                                                @lang('shop::app.customers.account.orders.view.refunds.subtotal-excl-tax')

                                                <p>
                                                    {{ core()->formatPrice($refund->sub_total, $order->order_currency_code) }}
                                                </p>
                                            </div>
                                            
                                            <div class="flex w-full justify-between gap-x-5 text-sm">
                                                @lang('shop::app.customers.account.orders.view.refunds.subtotal-incl-tax')

                                                <p>
                                                    {{ core()->formatPrice($refund->sub_total_incl_tax, $order->order_currency_code) }}
                                                </p>
                                            </div>
                                        @else
                                            <div class="flex w-full justify-between gap-x-5 text-sm">
                                                @lang('shop::app.customers.account.orders.view.refunds.subtotal')

                                                <p>
                                                    {{ core()->formatPrice($refund->sub_total, $order->order_currency_code) }}
                                                </p>
                                            </div>
                                        @endif

                                        @if (core()->getConfigData('sales.taxes.sales.display_shipping_amount') == 'including_tax')
                                            <div class="flex w-full justify-between gap-x-5">
                                                @lang('shop::app.customers.account.orders.view.refunds.shipping-handling')

                                                <p>
                                                    {{ core()->formatPrice($refund->shipping_amount_incl_tax, $order->order_currency_code) }}
                                                </p>
                                            </div>
                                        @elseif (core()->getConfigData('sales.taxes.sales.display_shipping_amount') == 'both')
                                            <div class="flex w-full justify-between gap-x-5">
                                                @lang('shop::app.customers.account.orders.view.refunds.shipping-handling-excl-tax')

                                                <p>
                                                    {{ core()->formatPrice($refund->shipping_amount, $order->order_currency_code) }}
                                                </p>
                                            </div>
                                            
                                            <div class="flex w-full justify-between gap-x-5">
                                                @lang('shop::app.customers.account.orders.view.refunds.shipping-handling-incl-tax')

                                                <p>
                                                    {{ core()->formatPrice($refund->shipping_amount_incl_tax, $order->order_currency_code) }}
                                                </p>
                                            </div>
                                        @else
                                            <div class="flex w-full justify-between gap-x-5">
                                                @lang('shop::app.customers.account.orders.view.refunds.shipping-handling')

                                                <p>
                                                    {{ core()->formatPrice($refund->shipping_amount, $order->order_currency_code) }}
                                                </p>
                                            </div>
                                        @endif

                                        @if ($refund->discount_amount > 0)
                                            <div class="flex w-full justify-between gap-x-5">
                                                @lang('shop::app.customers.account.orders.view.refunds.discount')

                                                <p>
                                                    {{ core()->formatPrice($order->discount_amount, $order->order_currency_code) }}
                                                </p>
                                            </div>
                                        @endif

                                        @if ($refund->tax_amount > 0)
                                            <div class="flex w-full justify-between gap-x-5">
                                                @lang('shop::app.customers.account.orders.view.refunds.tax')

                                                <p>
                                                    {{ core()->formatPrice($refund->tax_amount, $order->order_currency_code) }}
                                                </p>
                                            </div>
                                        @endif

                                        <div class="flex w-full justify-between gap-x-5">
                                            @lang('shop::app.customers.account.orders.view.refunds.adjustment-refund')

                                            <p>
                                                {{ core()->formatPrice($refund->adjustment_refund, $order->order_currency_code) }}
                                            </p>
                                        </div>

                                        <div class="flex w-full justify-between gap-x-5">
                                            @lang('shop::app.customers.account.orders.view.refunds.adjustment-fee')

                                            <p>
                                                {{ core()->formatPrice($refund->adjustment_fee, $order->order_currency_code) }}
                                            </p>
                                        </div>

                                        <div class="flex w-full justify-between gap-x-5 font-semibold">
                                            @lang('shop::app.customers.account.orders.view.refunds.grand-total')

                                            <p>
                                                {{ core()->formatPrice($refund->grand_total, $order->order_currency_code) }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </x-shop::tabs.item>
                @endif
            </x-shop::tabs>

            <!-- Shipping Address and Payment methods for mobile view -->
            <div class="w-full rounded-md bg-gray-100 md:hidden">
                <div class="rounded-t-md border-none !px-4 py-3 text-sm font-medium max-sm:py-2">
                    @lang('shop::app.customers.account.orders.view.shipping-and-payment')
                </div>

                <div class="grid gap-1.5 rounded-md rounded-t-none border border-t-0 bg-white px-4 py-3 text-xs font-medium">
                    <!-- Shipping Address -->
                    @if ($order->shipping_address)
                        <div class="text-sm font-medium text-zinc-500">
                            @lang('shop::app.customers.account.orders.view.shipping-address')

                            <div class="mt-1 grid gap-2 text-xs text-black">
                                <div class="grid gap-2.5 max-md:gap-0">
                                    @include ('shop::customers.account.orders.view.address', ['address' => $order->shipping_address])
                                </div>
                                
                                {!! view_render_event('bagisto.shop.customers.account.orders.view.shipping_address_details.after', ['order' => $order]) !!}
                            </div>
                            
                            {!! view_render_event('bagisto.shop.customers.account.orders.view.shipping_address.after', ['order' => $order]) !!}

                        </div>
                    @endif

                    <!-- Biiling Address -->
                    @if ($order->billing_address)
                        <div class="text-sm font-medium text-zinc-500">
                            @lang('shop::app.customers.account.orders.view.billing-address')

                            <div class="mt-1 grid gap-2 text-xs text-gray-800">
                                <div class="grid gap-2.5 max-md:gap-0">
                                    @include ('shop::customers.account.orders.view.address', ['address' => $order->billing_address])
                                </div>
                                
                                {!! view_render_event('bagisto.shop.customers.account.orders.view.billing_address_details.after', ['order' => $order]) !!}

                            </div>
        
                            {!! view_render_event('bagisto.shop.customers.account.orders.view.billing_address.after', ['order' => $order]) !!}
        
                        </div>
                    @endif

                    <!-- Shipping Method -->
                    @if ($order->shipping_address)
                        <div class="text-sm font-medium text-zinc-500">
                            @lang('shop::app.customers.account.orders.view.shipping-method')
                            
                            <div class="mt-1 grid gap-2.5 text-xs text-gray-800">
                                {{ $order->shipping_title }}
        
                                {!! view_render_event('bagisto.shop.customers.account.orders.view.shipping_method_details.after', ['order' => $order]) !!}
                            </div>
        
                            {!! view_render_event('bagisto.shop.customers.account.orders.view.shipping_method.after', ['order' => $order]) !!}

                        </div>
                    @endif

                    <!-- Payment Method -->
                    <div class="text-sm font-medium text-zinc-500">
                        @lang('shop::app.customers.account.orders.view.payment-method')

                        <div class="mt-1 grid gap-2.5 text-xs text-black">
                            {{ core()->getConfigData('sales.payment_methods.' . $order->payment->method . '.title') }}

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
            </div>

            <!-- Desktop View -->
            <div class="mt-11 flex flex-wrap justify-between gap-x-11 gap-y-8 border-t border-zinc-200 pt-7 max-md:hidden">
                <!-- Biiling Address -->
                @if ($order->billing_address)
                    <div class="grid max-w-[200px] gap-4 max-868:w-full max-868:max-w-full max-md:max-w-full max-md:gap-2">
                        <p class="text-base text-zinc-500 max-md:text-lg max-md:text-black">
                            @lang('shop::app.customers.account.orders.view.billing-address')
                        </p>

                        <div class="grid gap-2.5 max-md:gap-0">
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
                    <div class="grid max-w-[200px] gap-4 max-868:w-full max-868:max-w-full max-md:max-w-full max-md:gap-2">
                        <p class="text-base text-zinc-500 max-md:text-lg max-md:text-black">
                            @lang('shop::app.customers.account.orders.view.shipping-address')
                        </p>

                        <div class="grid gap-2.5 max-md:gap-0">
                            <p class="text-sm">
                                @include ('shop::customers.account.orders.view.address', ['address' => $order->shipping_address])
                            </p>
                        </div>
                        
                        {!! view_render_event('bagisto.shop.customers.account.orders.view.shipping_address_details.after', ['order' => $order]) !!}
                    </div>
                    
                    {!! view_render_event('bagisto.shop.customers.account.orders.view.shipping_address.after', ['order' => $order]) !!}

                    <!-- Shipping Method -->
                    <div class="grid max-w-[200px] place-content-baseline gap-4 max-868:w-full max-868:max-w-full max-md:max-w-full max-md:gap-2">
                        <p class="text-base text-zinc-500 max-md:text-lg max-md:text-black">
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
                <div class="grid max-w-[200px] place-content-baseline gap-4 max-868:w-full max-868:max-w-full max-md:max-w-full max-md:gap-2">
                    <p class="text-base text-zinc-500 max-md:text-lg max-md:text-black">
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
