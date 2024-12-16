@php $order = $refund->order; @endphp

<x-admin::layouts>
    <!-- Page Title -->
    <x-slot:title>
        @lang('admin::app.sales.refunds.view.title', ['refund_id' => $refund->id])
    </x-slot>

    <!-- Page Header -->
    <div class="grid pt-3">
        <div class="flex items-center justify-between gap-4 max-sm:flex-wrap">
            <p class="text-xl font-bold leading-6 text-gray-800 dark:text-white">
                @lang('admin::app.sales.refunds.view.title', ['refund_id' => $refund->id])
            </p>

            <!-- Back Button -->
            <div class="flex items-center gap-x-2.5">
                <a
                    href="{{ route('admin.sales.refunds.index') }}"
                    class="transparent-button hover:bg-gray-200 dark:text-white dark:hover:bg-gray-800"
                >
                    @lang('admin::app.account.edit.back-btn')
                </a>
            </div>
        </div>
    </div>

    <!-- Body Content -->
    <div class="mt-3.5 flex gap-2.5 max-xl:flex-wrap">
        <!-- Left sub-component -->
        <div class="flex flex-1 flex-col gap-2 max-xl:flex-auto">
            <!-- General -->
            <div class="box-shadow rounded bg-white dark:bg-gray-900">
                <p class="mb-4 p-4 text-base font-semibold text-gray-800 dark:text-white">
                    @lang('admin::app.sales.refunds.view.product-ordered') ({{ $refund->items->count() ?? 0 }})
                </p>

                <!-- Products List -->
                <div class="grid">
                    @foreach ($refund->items as $item)
                        <div class="flex justify-between gap-2.5 border-b border-slate-300 px-4 py-6 dark:border-gray-800">
                            <div class="flex gap-2.5">
                                @if ($item->product?->base_image_url)
                                    <img
                                        class="relative h-[60px] max-h-[60px] w-full max-w-[60px] rounded"
                                        src="{{ $item->product->base_image_url }}"
                                    >
                                @else
                                    <div class="relative h-[60px] max-h-[60px] w-full max-w-[60px] rounded border border-dashed border-gray-300 dark:border-gray-800 dark:mix-blend-exclusion dark:invert">
                                        <img src="{{ bagisto_asset('images/product-placeholders/front.svg') }}">
                                        
                                        <p class="absolute bottom-1.5 w-full text-center text-[6px] font-semibold text-gray-400"> 
                                            @lang('admin::app.sales.invoices.view.product-image') 
                                        </p>
                                    </div>
                                @endif

                                <!-- Product Name -->
                                <div class="grid place-content-start gap-1.5">
                                    <p class="text-base font-semibold text-gray-800 dark:text-white">
                                        {{ $item->name }}
                                    </p>

                                    <!-- Product Attribute Details -->
                                    <div class="flex flex-col place-items-start gap-1.5">
                                        @if (isset($item->additional['attributes']))
                                            @foreach ($item->additional['attributes'] as $attribute)
                                                <p class="text-gray-600 dark:text-gray-300">
                                                    {{ $attribute['attribute_name'] }} : {{ $attribute['option_label'] }}
                                                </p>
                                            @endforeach
                                        @endif
                                    </div>

                                    <!-- Product SKU -->
                                    <p class="text-gray-600 dark:text-gray-300">
                                        @lang('admin::app.sales.refunds.view.sku', ['sku' => $item->child ? $item->child->sku : $item->sku])
                                    </p>

                                    <!-- Product QTY -->
                                    <p class="text-gray-600 dark:text-gray-300">
                                        @lang('admin::app.sales.refunds.view.qty', ['qty' => $item->qty])
                                    </p>
                                </div>
                            </div>

                            <!-- Product Price Section -->
                            <div class="grid place-content-start gap-1">
                                <div class="">
                                    <p class="flex items-center justify-end gap-x-1 text-base font-semibold text-gray-800 dark:text-white">
                                        {{ core()->formatBasePrice($item->base_total + $item->base_tax_amount - $item->base_discount_amount) }}
                                    </p>
                                </div>

                                <div class="flex flex-col place-items-start items-end gap-1.5">
                                    <!-- Base Total -->
                                    @if (core()->getConfigData('sales.taxes.sales.display_prices') == 'including_tax')
                                        <p class="text-gray-600 dark:text-gray-300">
                                            @lang('admin::app.sales.refunds.view.price', ['price' => core()->formatBasePrice($item->base_price_incl_tax)])
                                        </p>
                                    @elseif (core()->getConfigData('sales.taxes.sales.display_prices') == 'both')
                                        <p class="text-gray-600 dark:text-gray-300">
                                            @lang('admin::app.sales.refunds.view.price-excl-tax', ['price' => core()->formatBasePrice($item->base_price)])
                                        </p>
                                        
                                        <p class="text-gray-600 dark:text-gray-300">
                                            @lang('admin::app.sales.refunds.view.price-incl-tax', ['price' => core()->formatBasePrice($item->base_price_incl_tax)])
                                        </p>
                                    @else
                                        <p class="text-gray-600 dark:text-gray-300">
                                            @lang('admin::app.sales.refunds.view.price', ['price' => core()->formatBasePrice($item->base_price)])
                                        </p>
                                    @endif

                                    <!-- Base Tax Amount -->
                                    <p class="text-gray-600 dark:text-gray-300">
                                        @lang('admin::app.sales.refunds.view.tax-amount', ['tax_amount' => core()->formatBasePrice($item->base_tax_amount)])
                                    </p>

                                    <!-- Base Discount Amount -->
                                    <p class="text-gray-600 dark:text-gray-300">
                                        @lang('admin::app.sales.refunds.view.base-discounted-amount', ['base_discounted_amount' => core()->formatBasePrice($item->base_discount_amount)])
                                    </p>

                                    <!-- Base Sub Total Amount -->
                                    @if (core()->getConfigData('sales.taxes.sales.display_subtotal') == 'including_tax')
                                        <p class="text-gray-600 dark:text-gray-300">
                                            @lang('admin::app.sales.refunds.view.sub-total-amount', ['discounted_amount' => core()->formatBasePrice($item->base_total_incl_tax)])
                                        </p>
                                    @elseif (core()->getConfigData('sales.taxes.sales.display_subtotal') == 'both')
                                        <p class="text-gray-600 dark:text-gray-300">
                                            @lang('admin::app.sales.refunds.view.sub-total-amount-excl-tax', ['discounted_amount' => core()->formatBasePrice($item->base_total)])
                                        </p>
                                        
                                        <p class="text-gray-600 dark:text-gray-300">
                                            @lang('admin::app.sales.refunds.view.sub-total-amount-incl-tax', ['discounted_amount' => core()->formatBasePrice($item->base_total_incl_tax)])
                                        </p>
                                    @else
                                        <p class="text-gray-600 dark:text-gray-300">
                                            @lang('admin::app.sales.refunds.view.sub-total-amount', ['discounted_amount' => core()->formatBasePrice($item->base_total)])
                                        </p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Subtotal / Grand Total od the page -->
                <div class="mt-4 flex w-full justify-end gap-2.5 p-4">
                    <div class="flex flex-col gap-y-1.5">
                        @if (core()->getConfigData('sales.taxes.sales.display_subtotal') == 'both')
                            <p class="font-semibold !leading-5 text-gray-600 dark:text-gray-300">
                                @lang('admin::app.sales.refunds.view.sub-total-excl-tax')
                            </p>
                            
                            <p class="font-semibold !leading-5 text-gray-600 dark:text-gray-300">
                                @lang('admin::app.sales.refunds.view.sub-total-incl-tax')
                            </p>
                        @else
                            <p class="font-semibold !leading-5 text-gray-600 dark:text-gray-300">
                                @lang('admin::app.sales.refunds.view.sub-total')
                            </p>
                        @endif

                        @if ($refund->base_shipping_amount > 0)
                            @if (core()->getConfigData('sales.taxes.sales.display_shipping_amount') == 'both')
                                <p class="!leading-5 text-gray-600 dark:text-gray-300">
                                    @lang('admin::app.sales.refunds.view.shipping-handling-excl-tax')
                                </p>
                                
                                <p class="!leading-5 text-gray-600 dark:text-gray-300">
                                    @lang('admin::app.sales.refunds.view.shipping-handling-incl-tax')
                                </p>
                            @else
                                <p class="!leading-5 text-gray-600 dark:text-gray-300">
                                    @lang('admin::app.sales.refunds.view.shipping-handling')
                                </p>
                            @endif
                        @endif

                        @if ($refund->base_tax_amount > 0)
                            <p class="!leading-5 text-gray-600 dark:text-gray-300">
                                @lang('admin::app.sales.refunds.view.tax')
                            </p>
                        @endif

                        @if ($refund->base_discount_amount > 0)
                            <p class="!leading-5 text-gray-600 dark:text-gray-300">
                                @lang('admin::app.sales.refunds.view.discounted-amount')
                            </p>
                        @endif

                        <p class="!leading-5 text-gray-600 dark:text-gray-300">
                            @lang('admin::app.sales.refunds.view.adjustment-refund')
                        </p>

                        <p class="!leading-5 text-gray-600 dark:text-gray-300">
                            @lang('admin::app.sales.refunds.view.adjustment-fee')
                        </p>

                        <p class="text-base font-semibold !leading-5 text-gray-800 dark:text-white">
                            @lang('admin::app.sales.refunds.view.grand-total')
                        </p>
                    </div>

                    <div class="flex flex-col gap-y-1.5">
                        <!-- Base Sub Total -->
                        @if (core()->getConfigData('sales.taxes.sales.display_subtotal') == 'including_tax')
                            <p class="font-semibold !leading-5 text-gray-600 dark:text-gray-300">
                                {{ core()->formatBasePrice($refund->base_sub_total_incl_tax) }}
                            </p>
                        @elseif (core()->getConfigData('sales.taxes.sales.display_subtotal') == 'both')
                            <p class="font-semibold !leading-5 text-gray-600 dark:text-gray-300">
                                {{ core()->formatBasePrice($refund->base_sub_total) }}
                            </p>
                            
                            <p class="font-semibold !leading-5 text-gray-600 dark:text-gray-300">
                                {{ core()->formatBasePrice($refund->base_sub_total_incl_tax) }}
                            </p>
                        @else
                            <p class="font-semibold !leading-5 text-gray-600 dark:text-gray-300">
                                {{ core()->formatBasePrice($refund->base_sub_total) }}
                            </p>
                        @endif

                        <!-- Base Shipping Amount -->
                        @if ($refund->base_shipping_amount > 0)
                            @if (core()->getConfigData('sales.taxes.sales.display_shipping_amount') == 'including_tax')
                                <p class="!leading-5 text-gray-600 dark:text-gray-300">
                                    {{ core()->formatBasePrice($refund->base_shipping_amount_incl_tax) }}
                                </p>
                            @elseif (core()->getConfigData('sales.taxes.sales.display_shipping_amount') == 'both')
                                <p class="!leading-5 text-gray-600 dark:text-gray-300">
                                    {{ core()->formatBasePrice($refund->base_shipping_amount) }}
                                </p>
                                
                                <p class="!leading-5 text-gray-600 dark:text-gray-300">
                                    {{ core()->formatBasePrice($refund->base_shipping_amount_incl_tax) }}
                                </p>
                            @else
                                <p class="!leading-5 text-gray-600 dark:text-gray-300">
                                    {{ core()->formatBasePrice($refund->base_shipping_amount) }}
                                </p>
                            @endif
                        @endif

                        <!-- Base Tax Amount -->
                        @if ($refund->base_tax_amount > 0)
                            <p class="!leading-5 text-gray-600 dark:text-gray-300">
                                {{ core()->formatBasePrice($refund->base_tax_amount) }}
                            </p>
                        @endif

                        <!-- Base Discount Amount -->
                        @if ($refund->base_discount_amount > 0)
                            <p class="!leading-5 text-gray-600 dark:text-gray-300">
                                {{ core()->formatBasePrice($refund->base_discount_amount) }}
                            </p>
                        @endif

                        <!-- Base Adjustment Refund -->
                        <p class="!leading-5 text-gray-600 dark:text-gray-300">
                            {{ core()->formatBasePrice($refund->base_adjustment_refund) }}
                        </p>

                        <!-- Base Adjustment Fee -->
                        <p class="!leading-5 text-gray-600 dark:text-gray-300">
                            {{ core()->formatBasePrice($refund->base_adjustment_fee) }}
                        </p>

                        <!-- Base Grand Total -->
                        <p class="text-base font-semibold !leading-5 text-gray-800 dark:text-white">
                            {{ core()->formatBasePrice($refund->base_grand_total) }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right sub-component -->
        <div class="flex w-[360px] max-w-full flex-col gap-2 max-sm:w-full">
            <!-- Account Information -->
            @if (
                $order->billing_address
                || $order->shipping_address
            )
                <x-admin::accordion>
                    <x-slot:header>
                        <p class="p-2.5 text-base font-semibold text-gray-600 dark:text-gray-300">
                            @lang('admin::app.sales.refunds.view.account-information')
                        </p>
                    </x-slot>
                
                    <x-slot:content>
                        <!-- Account Info -->
                        <div class="flex flex-col pb-4">
                            <!-- Customer Full Name -->
                            <p class="font-semibold text-gray-800 dark:text-white">
                                {{ $refund->order->customer_full_name }}
                            </p>

                            <!-- Customer Email -->
                            <p class="text-gray-600 dark:text-gray-300">
                                {{ $refund->order->customer_email }}
                            </p>
                        </div>

                        <!-- Billing Address -->
                        @if ($order->billing_address)
                            <span class="block w-full border-b dark:border-gray-800"></span>

                            <!-- Billing Address -->
                            <div class="flex items-center justify-between">
                                <p class="py-4 text-base font-semibold text-gray-600 dark:text-gray-300">
                                    @lang('admin::app.sales.refunds.view.billing-address')
                                </p>
                            </div>
        
                            @include ('admin::sales.address', ['address' => $order->billing_address])
                        @endif

                        <!-- Shipping Address -->
                        @if ($order->shipping_address)
                            <span class="mt-4 block w-full border-b dark:border-gray-800"></span>

                            <div class="flex items-center justify-between">
                                <p class="py-4 text-base font-semibold text-gray-600 dark:text-gray-300">
                                    @lang('admin::app.sales.refunds.view.shipping-address')
                                </p>
                            </div>

                            @include ('admin::sales.address', ['address' => $order->shipping_address])
                        @endif
                    </x-slot>
                </x-admin::accordion>
            @endif
            
            <!-- Order Information -->
            <x-admin::accordion>
                <x-slot:header>
                    <p class="p-2.5 text-base font-semibold text-gray-600 dark:text-gray-300">
                        @lang('admin::app.sales.refunds.view.order-information')
                    </p>
                </x-slot>
            
                <x-slot:content>
                    <div class="flex w-full gap-2.5">
                        <!-- Order Info Left Section  -->
                        <div class="flex flex-col gap-y-1.5">
                            @foreach (['order-id', 'order-date', 'order-status', 'order-channel'] as $item)
                                <p class="font-semibold text-gray-600 dark:text-gray-300">
                                    @lang('admin::app.sales.refunds.view.' . $item)
                                </p>
                            @endforeach    
                        </div>

                        <!-- Order Info Right Section  -->
                        <div class="flex flex-col gap-y-1.5">
                            <p class="font-semibold text-gray-600 dark:text-gray-300">
                                <a
                                    href="{{ route('admin.sales.orders.view', $order->id) }}"
                                    class="text-blue-600"
                                >
                                    #{{ $order->increment_id }}
                                </a>
                            </p>

                            <p class="text-gray-600 dark:text-gray-300">
                                {{ core()->formatDate($order->created_at, 'Y-m-d H:i:s') }}
                            </p>

                            <p class="text-gray-600 dark:text-gray-300">
                                {{ $order->status_label }}
                            </p>

                            <p class="text-gray-600 dark:text-gray-300">
                                {{ $order->channel_name }}
                            </p>
                        </div>
                    </div>
                </x-slot>
            </x-admin::accordion>

             <!-- Payment Information -->
             <x-admin::accordion>
                <x-slot:header>
                    <p class="p-2.5 text-base font-semibold text-gray-600 dark:text-gray-300">
                        @lang('admin::app.sales.refunds.view.payment-information')
                    </p>
                </x-slot>
            
                <x-slot:content>
                    <div class="flex w-full gap-2.5">
                        <!-- Payment Information Left Section  -->
                        <div class="flex flex-col gap-y-1.5">
                            @foreach (['payment-method', 'shipping-method', 'currency', 'shipping-price'] as $item)
                                <p class="font-semibold text-gray-600 dark:text-gray-300">
                                    @lang('admin::app.sales.refunds.view.' . $item)
                                </p>
                            @endforeach
                        </div>

                        <!-- Payment Information Right Section  -->
                        <div class="flex flex-col gap-y-1.5">
                            <p class="text-gray-600 dark:text-gray-300">
                                <a href="{{ route('admin.sales.orders.view', $order->id) }}">
                                    {{ core()->getConfigData('sales.payment_methods.' . $order->payment->method . '.title') }}
                                </a>
                            </p>

                            <p class="text-gray-600 dark:text-gray-300">
                                {{ $order->shipping_title ?? 'N/A' }}
                            </p>

                            <p class="text-gray-600 dark:text-gray-300">
                                {{ $order->order_currency_code }}
                            </p>

                            <p class="text-gray-600 dark:text-gray-300">
                                {{ core()->formatBasePrice($order->base_shipping_amount) }}
                            </p>
                        </div>
                    </div>
                </x-slot>
            </x-admin::accordion>
        </div>
    </div>
</x-admin::layouts>