<x-shop::layouts.account>
    <div class="flex justify-between items-center">
        <div class="">
            <div class="flex gap-x-[4px] items-center mb-[10px]">
                <p class="flex items-center gap-x-[4px] text-[#7D7D7D] text-[16px] after:content-['/']">
                    @lang('shop::app.customers.account.title')
                </p>

                <p class="flex items-center gap-x-[4px] text-[#7D7D7D] text-[16px] after:content-['/'] after:last:hidden">
                    @lang('shop::app.customers.account.orders.title')
                </p>

                <p class="flex items-center gap-x-[4px] text-[#7D7D7D] text-[16px] after:content-['/'] after:last:hidden">
                    @lang('shop::app.customers.account.orders.view.title')
                </p>
            </div>

            <h2 class="text-[26px] font-medium">
                @lang('shop::app.customers.account.orders.title')
                 #{{ $order->id }}
            </h2>
        </div>

        <div class="flex items-center gap-x-[10px] border border-[#E9E9E9] rounded-[12px] py-[12px] px-[20px] cursor-pointer">
            @lang('shop::app.customer.account.order.view.cancel-btn-title')
        </div>
    </div>

    {!! view_render_event('bagisto.shop.customers.account.orders.view.before', ['order' => $order]) !!}

    <div>
        <x-shop::tabs>
            <x-shop::tabs.item 
                title="{{ __('shop::app.customers.account.orders.view.info') }}" 
                :is-selected="true"
            >
                <div>
                    <label>
                        @lang('shop::app.customers.account.orders.view.placed-on')
                    </label>

                    <span>
                        {{ core()->formatDate($order->created_at, 'd M Y') }}
                    </span>
                </div>

                <div class="relative overflow-x-auto border rounded-[12px] mt-[30px]">
                    <table class="w-full text-sm text-left ">
                        <thead class="text-[14px] text-black bg-[#F5F5F5] border-b-[1px] border-[#E9E9E9]  ">
                            <tr>
                                <th 
                                    scope="col" 
                                    class="px-6 py-[16px] font-medium"
                                >
                                    @lang('shop::app.customers.account.orders.view.SKU')
                                </th>

                                <th 
                                    scope="col" 
                                    class="px-6 py-[16px] font-medium"
                                >
                                    @lang('shop::app.customers.account.orders.view.product-name')
                                </th>

                                <th 
                                    scope="col" 
                                    class="px-6 py-[16px] font-medium"
                                >
                                    @lang('shop::app.customers.account.orders.view.price')
                                </th>

                                <th 
                                    scope="col" 
                                    class="px-6 py-[16px] font-medium"
                                >
                                    @lang('shop::app.customers.account.orders.view.item-status')
                                </th>

                                <th 
                                    scope="col" 
                                    class="px-6 py-[16px] font-medium"
                                >
                                    @lang('shop::app.customers.account.orders.view.subtotal')	
                                </th>

                                <th 
                                    scope="col" 
                                    class="px-6 py-[16px] font-medium"
                                >
                                    @lang('shop::app.customers.account.orders.view.tax-percent') 
                                </th>

                                <th 
                                    scope="col" 
                                    class="px-6 py-[16px] font-medium"
                                >
                                    @lang('shop::app.customers.account.orders.view.tax-amount')
                                </th>

                                <th 
                                    scope="col" 
                                    class="px-6 py-[16px] font-medium"
                                >
                                    @lang('shop::app.customers.account.orders.view.grand-total')
                                </th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($order->items as $item)
                                <tr class="bg-white border-b">
                                    <td 
                                        class="px-6 py-[16px] text-black font-medium"
                                        data-value="@lang('shop::app.customers.account.orders.view.SKU')"
                                    >
                                        {{ $item->getTypeInstance()->getOrderedItem($item)->sku }}
                                    </td>

                                    <td 
                                        class="px-6 py-[16px] text-black font-medium"
                                        data-value="@lang('shop::app.customers.account.orders.view.product-name')"
                                    >
                                        {{ $item->name }}

                                        @if (isset($item->additional['attributes']))
                                            <div>
                                                @foreach ($item->additional['attributes'] as $attribute)
                                                    <b>{{ $attribute['attribute_name'] }} : </b>{{ $attribute['option_label'] }}</br>
                                                @endforeach
                                            </div>
                                        @endif
                                    </td>

                                    <td 
                                        class="px-6 py-[16px] text-black font-medium"
                                        data-value="@lang('shop::app.customers.account.orders.view.price')"
                                    > 
                                        {{ core()->formatPrice($item->price, $order->order_currency_code) }}
                                    </td>

                                    <td 
                                        class="px-6 py-[16px] text-black font-medium"
                                        data-value= "@lang('shop::app.customers.account.orders.view.price')"
                                    > 
                                        <span >
                                           {{__('shop::app.customers.account.orders.view.item-ordered', ['qty_ordered' => $item->qty_ordered])}}
                                        </span>

                                        <span>
                                            {{ $item->qty_invoiced ? __('shop::app.customers.account.orders.view.item-invoice', ['qty_invoiced' => $item->qty_invoiced]) : '' }}
                                        </span>

                                        <span>
                                            {{ $item->qty_shipped ? __('shop::app.customers.account.orders.view.item-shipped', ['qty_shipped' => $item->qty_shipped]) : '' }}
                                        </span>

                                        <span>
                                            {{ $item->qty_refunded ? __('shop::app.customers.account.orders.view.item-refunded', ['qty_refunded' => $item->qty_refunded]) : '' }}
                                        </span>

                                        <span>
                                            {{ $item->qty_canceled ? __('shop::app.customers.account.orders.view.item-canceled', ['qty_canceled' => $item->qty_canceled]) : '' }}
                                        </span>
                                    </td>

                                    <td 
                                        class="px-6 py-[16px] text-black font-medium"
                                        data-value="@lang('shop::app.customers.account.orders.view.subtotal')"
                                    > 
                                        {{ core()->formatPrice($item->total, $order->order_currency_code) }}
                                    </td>

                                    <td 
                                        class="px-6 py-[16px] text-black font-medium"
                                        data-value="@lang('shop::app.customers.account.orders.view.tax-percent')"
                                    > 
                                        {{ number_format($item->tax_percent, 2) }}%
                                    </td>

                                    <td 
                                        class="px-6 py-[16px] text-black font-medium"
                                        data-value="@lang('shop::app.customers.account.orders.view.tax-amount')"
                                    > 
                                        {{ core()->formatPrice($item->tax_amount, $order->order_currency_code) }}
                                    </td>

                                    <td 
                                        class="px-6 py-[16px] text-black font-medium"
                                        data-value="@lang('shop::app.customers.account.orders.view.grand-total')"
                                    > 
                                        {{ core()->formatPrice($item->total + $item->tax_amount - $item->discount_amount, $order->order_currency_code) }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="flex gap-[40px] mt-[30px] items-start max-lg:gap-[20px] max-md:grid">
                    <div class="flex-auto max-md:mt-[30px]">
                        <div class="flex justify-end">
                            <div class="grid gap-[8px] max-w-max">
                                <div class="flex w-full gap-x-[20px] justify-between">
                                    <p class="text-[14px]">
                                        @lang('shop::app.customers.account.orders.view.subtotal')
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
                                            @lang('shop::app.customers.account.orders.view.shipping-handling')
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
                                    <div class="flex w-full gap-x-[20px] justify-between">
                                        <p class="text-[14px]">
                                            @lang('shop::app.customers.account.orders.view.discount')

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

                                <div class="flex w-full gap-x-[20px] justify-between">
                                    <p class="text-[14px]">
                                        @lang('shop::app.customers.account.orders.view.tax')
                                    </p>

                                    <div class="flex gap-x-[20px]">
                                        <p class="text-[14px]">-</p>

                                        <p class="text-[14px]">
                                            {{ core()->formatPrice($order->tax_amount, $order->order_currency_code) }}
                                        </p>
                                    </div>
                                </div>
                                 
                                <div class="flex w-full gap-x-[20px] justify-between">
                                    <p class="text-[14px]">
                                        @lang('shop::app.customers.account.orders.view.grand-total')
                                    </p>

                                    <div class="flex gap-x-[20px]">
                                        <p class="text-[14px]">-</p>

                                        <p class="text-[14px]">
                                            {{ core()->formatPrice($order->grand_total, $order->order_currency_code) }}
                                        </p>
                                    </div>
                                </div>

                                <div class="flex w-full gap-x-[20px] justify-between">
                                    <p class="text-[14px]">
                                        @lang('shop::app.customers.account.orders.view.total-paid')
                                    </p>

                                    <div class="flex gap-x-[20px]">
                                        <p class="text-[14px]">-</p>

                                        <p class="text-[14px]">
                                            {{ core()->formatPrice($order->grand_total_invoiced, $order->order_currency_code) }}
                                        </p>
                                    </div>
                                </div>
                                
                                <div class="flex w-full gap-x-[20px] justify-between">
                                    <p class="text-[14px]">
                                        @lang('shop::app.customers.account.orders.view.total-refunded')
                                    </p>

                                    <div class="flex gap-x-[20px]">
                                        <p class="text-[14px]">-</p>

                                        <p class="text-[14px]">
                                            {{ core()->formatPrice($order->grand_total_refunded, $order->order_currency_code) }}
                                        </p>
                                    </div>
                                </div>
                                <div class="flex w-full gap-x-[20px] justify-between">
                                    <p class="text-[14px]">
                                        @lang('shop::app.customers.account.orders.view.total-due')
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
                <x-shop::tabs.item  title="{{ __('shop::app.customers.account.orders.view.invoices') }}">

                    @foreach ($order->invoices as $invoice)
                        <div>
                            <label>
                                <span>{{ __('shop::app.customers.account.orders.view.individual-invoice', ['invoice_id' => $invoice->increment_id ?? $invoice->id]) }}</span>
                            </label>
                        </div>

                        <div class="relative overflow-x-auto border rounded-[12px] mt-[30px]">
                            <table class="w-full text-sm text-left">
                                <thead class="text-[14px] text-black bg-[#F5F5F5] border-b-[1px] border-[#E9E9E9]  ">
                                    <tr>
                                        <th 
                                            scope="col" 
                                            class="px-6 py-[16px] font-medium"
                                        >
                                            @lang('shop::app.customers.account.orders.view.SKU')
                                        </th>

                                        <th 
                                            scope="col" 
                                            class="px-6 py-[16px] font-medium"
                                        >
                                            @lang('shop::app.customers.account.orders.view.product-name')
                                        </th>

                                        <th 
                                            scope="col" 
                                            class="px-6 py-[16px] font-medium"
                                        >
                                            @lang('shop::app.customers.account.orders.view.price')
                                        </th>

                                        <th 
                                            scope="col" 
                                            class="px-6 py-[16px] font-medium"
                                        >
                                            @lang('shop::app.customers.account.orders.view.qty')
                                        </th>

                                        <th 
                                            scope="col" 
                                            class="px-6 py-[16px] font-medium"
                                        >
                                            @lang('shop::app.customers.account.orders.view.subtotal')	
                                        </th>

                                        <th 
                                            scope="col" 
                                            class="px-6 py-[16px] font-medium"
                                        >
                                            @lang('shop::app.customers.account.orders.view.tax-amount')
                                        </th>

                                        <th 
                                            scope="col" 
                                            class="px-6 py-[16px] font-medium"
                                        >
                                            @lang('shop::app.customers.account.orders.view.grand-total')
                                        </th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach ($invoice->items as $item)
                                        <tr class="bg-white border-b">
                                            <td 
                                                class="px-6 py-[16px] text-black font-medium"
                                                data-value="@lang('shop::app.customers.account.orders.view.SKU')"
                                            >
                                                {{ $item->getTypeInstance()->getOrderedItem($item)->sku }}
                                            </td>

                                            <td 
                                                class="px-6 py-[16px] text-black font-medium"
                                                data-value="@lang('shop::app.customers.account.orders.view.product-name')"
                                            >
                                                {{ $item->name }}
                                            </td>

                                            <td 
                                                class="px-6 py-[16px] text-black font-medium"
                                                data-value="@lang('shop::app.customers.account.orders.view.price')"
                                            > 
                                                {{ core()->formatPrice($item->price, $order->order_currency_code) }}
                                            </td>

                                            <td 
                                                class="px-6 py-[16px] text-black font-medium"
                                                data-value="@lang('shop::app.customers.account.orders.view.qty')"
                                            > 
                                                {{ $item->qty }}
                                            </td>

                                            <td 
                                                class="px-6 py-[16px] text-black font-medium"
                                                data-value="@lang('shop::app.customers.account.orders.view.subtotal')"
                                            > 
                                                {{ core()->formatPrice($item->total, $order->order_currency_code) }}
                                            </td>

                                            <td 
                                                class="px-6 py-[16px] text-black font-medium"
                                                data-value="@lang('shop::app.customers.account.orders.view.tax-amount')"
                                            > 
                                                {{ core()->formatPrice($item->tax_amount, $order->order_currency_code) }}
                                            </td>

                                            <td 
                                                class="px-6 py-[16px] text-black font-medium"
                                                data-value="@lang('shop::app.customers.account.orders.view.grand-total')"
                                            > 
                                                {{ core()->formatPrice($item->total + $item->tax_amount, $order->order_currency_code) }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="flex gap-[40px] mt-[30px] items-start max-lg:gap-[20px] max-md:grid">
                            <div class="flex-auto max-md:mt-[30px]">
                                <div class="flex justify-end">
                                    <div class="grid gap-[8px] max-w-max">
                                        <div class="flex w-full gap-x-[20px] justify-between">
                                            <p class="text-[14px]">
                                                @lang('shop::app.customers.account.orders.view.subtotal')
                                            </p>
        
                                            <div class="flex gap-x-[20px]">
                                                <p class="text-[14px]">-</p>

                                                <p class="text-[14px]">
                                                    {{ core()->formatPrice($invoice->sub_total, $order->order_currency_code) }}
                                                </p>
                                            </div>
                                        </div>

                                        <div class="flex w-full gap-x-[20px] justify-between">
                                            <p class="text-[14px]">
                                                @lang('shop::app.customers.account.orders.view.shipping-handling')
                                            </p>

                                            <div class="flex gap-x-[20px]">
                                                <p class="text-[14px]">-</p>

                                                <p class="text-[14px]">
                                                    {{ core()->formatPrice($invoice->shipping_amount, $order->order_currency_code) }}
                                                </p>
                                            </div>
                                        </div>
                                    
                                        @if ($invoice->base_discount_amount > 0)
                                            <div class="flex w-full gap-x-[20px] justify-between">
                                                <p class="text-[14px]">
                                                    @lang('shop::app.customers.account.orders.view.discount')
                                                </p>
        
                                                <div class="flex gap-x-[20px]">
                                                    <p class="text-[14px]">-</p>

                                                    <p class="text-[14px]">
                                                        {{ core()->formatPrice($invoice->discount_amount, $order->order_currency_code) }}
                                                    </p>
                                                </div>
                                            </div>
                                        @endif  
        
                                        <div class="flex w-full gap-x-[20px] justify-between">
                                            <p class="text-[14px]">
                                                @lang('shop::app.customers.account.orders.view.tax')
                                            </p>
        
                                            <div class="flex gap-x-[20px]">
                                                <p class="text-[14px]">-</p>

                                                <p class="text-[14px]">
                                                    {{ core()->formatPrice($invoice->tax_amount, $order->order_currency_code) }}
                                                </p>
                                            </div>
                                        </div>
                                         
                                        <div class="flex w-full gap-x-[20px] justify-between">
                                            <p class="text-[14px]">
                                                @lang('shop::app.customers.account.orders.view.grand-total')
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
                <x-shop::tabs.item title="{{__('shop::app.customers.account.orders.view.shipments') }}">

                    @foreach ($order->shipments as $shipment)
                        <div>
                            <label>
                                @lang('shop::app.customers.account.orders.view.tracking-number')
                            </label>

                            <span>
                                {{  $shipment->track_number }}
                            </span>
                        </div>
        
                        <div>
                            <span>{{ __('shop::app.customers.account.orders.view.individual-shipment', ['shipment_id' => $shipment->id]) }}</span>
                        </div>
             
                        <div class="relative overflow-x-auto border rounded-[12px] mt-[30px]">
                            <table class="w-full text-sm text-left">
                                <thead class="text-[14px] text-black bg-[#F5F5F5] border-b-[1px] border-[#E9E9E9]  ">
                                    <tr>
                                        <th 
                                            scope="col" 
                                            class="px-6 py-[16px] font-medium"
                                        >
                                            @lang('shop::app.customers.account.orders.view.SKU')
                                        </th>

                                        <th 
                                            scope="col" 
                                            class="px-6 py-[16px] font-medium"
                                        >
                                            @lang('shop::app.customers.account.orders.view.product-name')
                                        </th>

                                        <th 
                                            scope="col" 
                                            class="px-6 py-[16px] font-medium"
                                        >
                                            @lang('shop::app.customers.account.orders.view.qty')
                                        </th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach ($shipment->items as $item)
                                        <tr class="bg-white border-b">
                                            <td 
                                                class="px-6 py-[16px] text-black font-medium"
                                                data-value="@lang('shop::app.customers.account.orders.view.SKU')"
                                            >
                                                {{ $item->sku }}
                                            </td>

                                            <td 
                                                class="px-6 py-[16px] text-black font-medium"
                                                data-value="@lang('shop::app.customers.account.orders.view.product-name')"
                                            >
                                                {{ $item->name }}
                                            </td>

                                            <td 
                                                class="px-6 py-[16px] text-black font-medium"
                                                data-value="@lang('shop::app.customers.account.orders.view.qty')"
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
                    title="{{ __('shop::app.customers.account.orders.view.refunds') }}"
                >
                    @foreach ($order->refunds as $refund)
                        <div>
                            <label>
                                <span>
                                    {{ __('shop::app.customers.account.orders.view.individual-refund', ['refund_id' => $refund->id]) }}
                                </span>
                            </label>
                        </div>

                        <div class="relative overflow-x-auto border rounded-[12px] mt-[30px]">
                            <table class="w-full text-sm text-left">
                                <thead class="text-[14px] text-black bg-[#F5F5F5] border-b-[1px] border-[#E9E9E9]  ">
                                    <tr>
                                        <th 
                                            scope="col" 
                                            class="px-6 py-[16px] font-medium"
                                        >
                                            @lang('shop::app.customers.account.orders.view.SKU')
                                        </th>

                                        <th 
                                            scope="col" 
                                            class="px-6 py-[16px] font-medium"
                                        >
                                            @lang('shop::app.customers.account.orders.view.product-name')
                                        </th>

                                        <th 
                                            scope="col" 
                                            class="px-6 py-[16px] font-medium"
                                        >
                                            @lang('shop::app.customers.account.orders.view.price')
                                        </th>

                                        <th 
                                            scope="col" 
                                            class="px-6 py-[16px] font-medium"
                                        >
                                            @lang('shop::app.customers.account.orders.view.qty')
                                        </th>

                                        <th 
                                            scope="col" 
                                            class="px-6 py-[16px] font-medium"
                                        >
                                            @lang('shop::app.customers.account.orders.view.subtotal')	
                                        </th>

                                        <th 
                                            scope="col" 
                                            class="px-6 py-[16px] font-medium"
                                        >
                                            @lang('shop::app.customers.account.orders.view.tax-amount')
                                        </th>

                                        <th 
                                            scope="col" 
                                            class="px-6 py-[16px] font-medium"
                                        >
                                            @lang('shop::app.customers.account.orders.view.grand-total')
                                        </th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach ($refund->items as $item)
                                        <tr class="bg-white border-b">
                                            <td 
                                                class="px-6 py-[16px] text-black font-medium"
                                                data-value="@lang('shop::app.customers.account.orders.view.SKU')"
                                            >
                                                {{ $item->child ? $item->child->sku : $item->sku }}
                                            </td>

                                            <td 
                                                class="px-6 py-[16px] text-black font-medium"
                                                data-value="@lang('shop::app.customers.account.orders.view.product-name')"
                                            >
                                                {{ $item->name }}
                                            </td>

                                            <td 
                                                class="px-6 py-[16px] text-black font-medium"
                                                data-value="@lang('shop::app.customers.account.orders.view.price')"
                                            > 
                                                {{ core()->formatPrice($item->price, $order->order_currency_code) }}
                                            </td>

                                            <td 
                                                class="px-6 py-[16px] text-black font-medium"
                                                data-value="@lang('shop::app.customers.account.orders.view.qty')"
                                            > 
                                                {{ $item->qty }}
                                            </td>

                                            <td 
                                                class="px-6 py-[16px] text-black font-medium"
                                                data-value="@lang('shop::app.customers.account.orders.view.subtotal')"
                                            > 
                                                {{ core()->formatPrice($item->total, $order->order_currency_code) }}
                                            </td>

                                            <td 
                                                class="px-6 py-[16px] text-black font-medium"
                                                data-value="@lang('shop::app.customers.account.orders.view.tax-amount')"
                                            > 
                                                {{ core()->formatPrice($item->tax_amount, $order->order_currency_code) }}
                                            </td>

                                            <td 
                                                class="px-6 py-[16px] text-black font-medium"
                                                data-value="@lang('shop::app.customers.account.orders.view.grand-total')"
                                            > 
                                                {{ core()->formatPrice($item->total + $item->tax_amount, $order->order_currency_code) }}
                                            </td>
                                        </tr>
                                    @endforeach

                                    @if (! $refund->items->count())
                                        <tr>
                                            <td>@lang('shop::app.common.no-result-found')</td>
                                        <tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>

                        <div class="flex gap-[40px] mt-[30px] items-start max-lg:gap-[20px] max-md:grid">
                            <div class="flex-auto max-md:mt-[30px]">
                                <div class="flex justify-end">
                                    <div class="grid gap-[8px] max-w-max">
                                        <div class="flex w-full gap-x-[20px] justify-between">
                                            <p class="text-[14px]">
                                                @lang('shop::app.customers.account.orders.view.subtotal')
                                            </p>
        
                                            <div class="flex gap-x-[20px]">
                                                <p class="text-[14px]">-</p>

                                                <p class="text-[14px]">
                                                    {{ core()->formatPrice($refund->sub_total, $order->order_currency_code) }}
                                                </p>
                                            </div>
                                        </div>

                                        <div class="flex w-full gap-x-[20px] justify-between">
                                            <p class="text-[14px]">
                                                @lang('shop::app.customers.account.orders.view.shipping-handling')
                                            </p>

                                            <div class="flex gap-x-[20px]">
                                                <p class="text-[14px]">-</p>

                                                <p class="text-[14px]">
                                                    {{ core()->formatPrice($refund->shipping_amount, $order->order_currency_code) }}
                                                </p>
                                            </div>
                                        </div>
                                    
                                        @if ($refund->discount_amount > 0)
                                            <div class="flex w-full gap-x-[20px] justify-between">
                                                <p class="text-[14px]">
                                                    @lang('shop::app.customers.account.orders.view.discount')
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
                                            <div class="flex w-full gap-x-[20px] justify-between">
                                                <p class="text-[14px]">
                                                    @lang('shop::app.customers.account.orders.view.tax')
                                                </p>
            
                                                <div class="flex gap-x-[20px]">
                                                    <p class="text-[14px]">-</p>

                                                    <p class="text-[14px]">
                                                        {{ core()->formatPrice($refund->tax_amount, $order->order_currency_code) }}
                                                    </p>
                                                </div>
                                            </div>
                                        @endif
                                        
                                        <div class="flex w-full gap-x-[20px] justify-between">
                                            <p class="text-[14px]">
                                                @lang('shop::app.customers.account.orders.view.adjustment-refund')
                                            </p>
        
                                            <div class="flex gap-x-[20px]">
                                                <p class="text-[14px]">-</p>

                                                <p class="text-[14px]">
                                                    {{ core()->formatPrice($refund->adjustment_refund, $order->order_currency_code) }}
                                                </p>
                                            </div>
                                        </div>

                                        <div class="flex w-full gap-x-[20px] justify-between">
                                            <p class="text-[14px]">
                                                @lang('shop::app.customers.account.orders.view.adjustment-fee')
                                            </p>
        
                                            <div class="flex gap-x-[20px]">
                                                <p class="text-[14px]">-</p>

                                                <p class="text-[14px]">
                                                    {{ core()->formatPrice($refund->adjustment_fee, $order->order_currency_code) }}
                                                </p>
                                            </div>
                                        </div>

                                        <div class="flex w-full gap-x-[20px] justify-between">
                                            <p class="text-[14px]">
                                                @lang('shop::app.customers.account.orders.view.grand-total')
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

        <div class="flex gap-x-[64px] gap-y-[30px] flex-wrap justify-between pt-[26px] border-t-[1px] border-[#E9E9E9] mt-[42px]">

            {{-- Biiling Address --}}
            @if ($order->billing_address)
                <div class="grid gap-[15px] max-w-[200px] max-868:w-full max-868:max-w-full max-md:max-w-[200px] max-sm:max-w-full">
                    <p class="text-[16px] text-[#7D7D7D]">
                        @lang('shop::app.customers.account.orders.view.billing-address')
                    </p>

                    <div class="grid gap-[10px]">
                        <p class="text-[14px]">
                            @include ('admin::sales.address', ['address' => $order->billing_address])
                        </p>
                
                        {!! view_render_event('bagisto.shop.customers.account.orders.view.billing-address.after', ['order' => $order]) !!}
                    </div>
                </div>
            @endif

            {{-- Shipping Address --}}
            @if ($order->shipping_address)
                <div class="grid gap-[15px] max-w-[200px] max-868:w-full max-868:max-w-full max-md:max-w-[200px] max-sm:max-w-full">
                    <p class="text-[16px] text-[#7D7D7D]">
                        @lang('shop::app.customers.account.orders.view.shipping-address')
                    </p>

                    <div class="grid gap-[10px]">
                        <p class="text-[14px]">
                            @include ('admin::sales.address', ['address' => $order->shipping_address])

                            {!! view_render_event('bagisto.shop.customers.account.orders.view.shipping-address.after', ['order' => $order]) !!}
                        </p>
                    </div>
                </div>
            
                {{-- Shipping Method --}}
                <div class="grid gap-[15px] max-w-[200px] place-content-baseline max-868:w-full max-868:max-w-full max-md:max-w-[200px] max-sm:max-w-full">
                    <p class="text-[16px] text-[#7D7D7D]">
                        @lang('shop::app.customers.account.orders.view.shipping-method')
                    </p>

                    <p class="text-[14px]">
                        {{ $order->shipping_title }}
                    </p>
                </div>

                {!! view_render_event('bagisto.shop.customers.account.orders.view.shipping-method.after', ['order' => $order]) !!}

            @endif

            {{-- Billing Method --}}
            <div class="grid gap-[15px] max-w-[200px] place-content-baseline max-868:w-full max-868:max-w-full max-md:max-w-[200px] max-sm:max-w-full">
                <p class="text-[16px] text-[#7D7D7D]">
                    @lang('shop::app.customers.account.orders.view.payment-method')
                </p>

                <p class="text-[14px]">
                    {{ core()->getConfigData('sales.paymentmethods.' . $order->payment->method . '.title') }}
                </p>

                @if (! empty($additionalDetails))
                    <div class="instructions">
                        <label>{{ $additionalDetails['title'] }}</label>
                    </div>
                @endif

                {!! view_render_event('bagisto.shop.customers.account.orders.view.payment-method.after', ['order' => $order]) !!}

            </div>
        </div>
    </div>
</x-shop::layouts.account>
