@php $order = $refund->order; @endphp

<x-admin::layouts>
    <!-- Page Title -->
    <x-slot:title>
        @lang('admin::app.sales.refunds.view.title', ['refund_id' => $refund->id])
    </x-slot>

    <!-- Page Header -->
    <div class="grid pt-3">
        <div class="flex  gap-4 justify-between items-center max-sm:flex-wrap">
            <p class="text-xl text-gray-800 dark:text-white font-bold leading-6">
                @lang('admin::app.sales.refunds.view.title', ['refund_id' => $refund->id])
            </p>

            <!-- Cancel Button -->
            <div class="flex gap-x-2.5 items-center">
                <a
                    href="{{ route('admin.sales.refunds.index') }}"
                    class="transparent-button hover:bg-gray-200 dark:hover:bg-gray-800 dark:text-white"
                >
                    @lang('admin::app.account.edit.back-btn')
                </a>
            </div>
        </div>
    </div>

    <!-- Body Content -->
    <div class="flex gap-2.5 mt-3.5 max-xl:flex-wrap">
        <!-- Left sub-component -->
        <div class="flex flex-col gap-2 flex-1 max-xl:flex-auto">
            <!-- General -->
            <div class="bg-white dark:bg-gray-900 rounded box-shadow">
                <p class="text-base text-gray-800 dark:text-white font-semibold mb-4 p-4">
                    @lang('admin::app.sales.refunds.view.product-ordered') ({{ $refund->items->count() ?? 0 }})
                </p>

                <!-- Products List -->
                <div class="grid">
                    @foreach ($refund->items as $item)
                        <div class="flex gap-2.5 justify-between px-4 py-6 border-b border-slate-300 dark:border-gray-800">
                            <div class="flex gap-2.5">
                                @if ($item->product?->base_image_url)
                                    <img
                                        class="w-full h-[60px] max-w-[60px] max-h-[60px] relative rounded"
                                        src="{{ $item->product->base_image_url }}"
                                    >
                                @else
                                    <div class="w-full h-[60px] max-w-[60px] max-h-[60px] relative border border-dashed border-gray-300 dark:border-gray-800 rounded dark:invert dark:mix-blend-exclusion">
                                        <img src="{{ bagisto_asset('images/product-placeholders/front.svg') }}">
                                        
                                        <p class="absolute w-full bottom-1.5 text-[6px] text-gray-400 text-center font-semibold"> 
                                            @lang('admin::app.sales.invoices.view.product-image') 
                                        </p>
                                    </div>
                                @endif

                                <!-- Product Name -->
                                <div class="grid gap-1.5 place-content-start">
                                    <p class="text-base text-gray-800 dark:text-white font-semibold">
                                        {{ $item->name }}
                                    </p>

                                    <!-- Product Attribute Detailes -->
                                    <div class="flex flex-col gap-1.5 place-items-start">
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
                            <div class="grid gap-1 place-content-start">
                                <div class="">
                                    <p class="flex items-center gap-x-1 justify-end text-base text-gray-800 dark:text-white font-semibold">
                                        {{ core()->formatBasePrice($item->base_total + $item->base_tax_amount - $item->base_discount_amount) }}
                                    </p>
                                </div>

                                <div class="flex flex-col gap-1.5 items-end place-items-start">
                                    <!-- Base Total -->
                                    <p class="text-gray-600 dark:text-gray-300">
                                        @lang('admin::app.sales.refunds.view.price', ['price' => core()->formatBasePrice($item->base_total)])
                                    </p>

                                    <!-- Base Tax Amount -->
                                    <p class="text-gray-600 dark:text-gray-300">
                                        @lang('admin::app.sales.refunds.view.tax-amount', ['tax_amount' => core()->formatBasePrice($item->base_tax_amount)])
                                    </p>

                                    <!-- Base Discount Amount -->
                                    <p class="text-gray-600 dark:text-gray-300">
                                        @lang('admin::app.sales.refunds.view.base-discounted-amount', ['base_discounted_amount' => core()->formatBasePrice($item->base_discount_amount)])
                                    </p>

                                    <!-- Base Discount Amount -->
                                    <p class="text-gray-600 dark:text-gray-300">
                                        @lang('admin::app.sales.refunds.view.discounted-amount', ['discounted_amount' => core()->formatBasePrice($item->base_total + $item->base_tax_amount - $item->base_discount_amount)])
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Subtotal / Grand Total od the page -->
                <div class="flex w-full gap-2.5 justify-end mt-4 p-4">
                    <div class="flex flex-col gap-y-1.5">
                        <p class="text-gray-600 dark:text-gray-300 font-semibold !leading-5">
                            @lang('admin::app.sales.refunds.view.sub-total')
                        </p>

                        @if ($refund->base_shipping_amount > 0)
                            <p class="text-gray-600 dark:text-gray-300 !leading-5">
                                @lang('admin::app.sales.refunds.view.shipping-handling')
                            </p>
                        @endif

                        @if ($refund->base_tax_amount > 0)
                            <p class="text-gray-600 dark:text-gray-300 !leading-5">
                                @lang('admin::app.sales.refunds.view.tax')
                            </p>
                        @endif

                        @if ($refund->base_discount_amount > 0)
                            <p class="text-gray-600 dark:text-gray-300 !leading-5">
                                @lang('admin::app.sales.refunds.view.discounted-amount')
                            </p>
                        @endif

                        <p class="text-gray-600 dark:text-gray-300 !leading-5">
                            @lang('admin::app.sales.refunds.view.adjustment-refund')
                        </p>

                        <p class="text-gray-600 dark:text-gray-300 !leading-5">
                            @lang('admin::app.sales.refunds.view.adjustment-fee')
                        </p>

                        <p class="text-base text-gray-800 dark:text-white font-semibold !leading-5">
                            @lang('admin::app.sales.refunds.view.grand-total')
                        </p>
                    </div>

                    <div class="flex  flex-col gap-y-1.5">
                        <!-- Base Sub Total -->
                        <p class="text-gray-600 dark:text-gray-300 font-semibold !leading-5">
                            {{ core()->formatBasePrice($refund->base_sub_total) }}
                        </p>

                        <!-- Base Shipping Amount -->
                        @if ($refund->base_shipping_amount > 0)
                            <p class="text-gray-600 dark:text-gray-300 !leading-5">
                                {{ core()->formatBasePrice($refund->base_shipping_amount) }}
                            </p>
                        @endif

                        <!-- Base Tax Amount -->
                        @if ($refund->base_tax_amount > 0)
                            <p class="text-gray-600 dark:text-gray-300 !leading-5">
                                {{ core()->formatBasePrice($refund->base_tax_amount) }}
                            </p>
                        @endif

                        <!-- Base Discount Amouont -->
                        @if ($refund->base_discount_amount > 0)
                            <p class="text-gray-600 dark:text-gray-300 !leading-5">
                                {{ core()->formatBasePrice($refund->base_discount_amount) }}
                            </p>
                        @endif

                        <!-- Base Adjustment Refund -->
                        <p class="text-gray-600 dark:text-gray-300 !leading-5">
                            {{ core()->formatBasePrice($refund->base_adjustment_refund) }}
                        </p>

                        <!-- Base Adjustment Fee -->
                        <p class="text-gray-600 dark:text-gray-300 !leading-5">
                            {{ core()->formatBasePrice($refund->base_adjustment_fee) }}
                        </p>

                        <!-- Base Grand Total -->
                        <p class="text-base text-gray-800 dark:text-white font-semibold !leading-5">
                            {{ core()->formatBasePrice($refund->base_grand_total) }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right sub-component -->
        <div class="flex flex-col gap-2 w-[360px] max-w-full max-sm:w-full">
            <!-- Account Information -->
            @if (
                $order->billing_address
                || $order->shipping_address
            )
                <x-admin::accordion>
                    <x-slot:header>
                        <p class="text-gray-600 dark:text-gray-300 text-base  p-2.5 font-semibold">
                            @lang('admin::app.sales.refunds.view.account-information')
                        </p>
                    </x-slot>
                
                    <x-slot:content>
                        <!-- Account Info -->
                        <div class="flex flex-col pb-4">
                            <!-- Customer Full Name -->
                            <p class="text-gray-800 font-semibold dark:text-white">
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
                                <p class="text-gray-600 dark:text-gray-300 text-base  py-4 font-semibold">
                                    @lang('admin::app.sales.refunds.view.billing-address')
                                </p>
                            </div>
        
                            @include ('admin::sales.address', ['address' => $order->billing_address])
                        @endif

                        <!-- Shipping Address -->
                        @if ($order->shipping_address)
                            <span class="block w-full mt-4 border-b dark:border-gray-800"></span>

                            <div class="flex items-center justify-between">
                                <p class="text-gray-600 dark:text-gray-300  text-base  py-4 font-semibold">
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
                    <p class="text-gray-600 dark:text-gray-300 text-base  p-2.5 font-semibold">
                        @lang('admin::app.sales.refunds.view.order-information')
                    </p>
                </x-slot>
            
                <x-slot:content>
                    <div class="flex w-full gap-2.5">
                        <!-- Order Info Left Section  -->
                        <div class="flex flex-col gap-y-1.5">
                            @foreach (['order-id', 'order-date', 'order-status', 'order-channel'] as $item)
                                <p class="text-gray-600 dark:text-gray-300 font-semibold">
                                    @lang('admin::app.sales.refunds.view.' . $item)
                                </p>
                            @endforeach    
                        </div>

                        <!-- Order Info Right Section  -->
                        <div class="flex flex-col gap-y-1.5">
                            <p class="text-gray-600 dark:text-gray-300 font-semibold">
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
                    <p class="text-gray-600 dark:text-gray-300 text-base  p-2.5 font-semibold">
                        @lang('admin::app.sales.refunds.view.payment-information')
                    </p>
                </x-slot>
            
                <x-slot:content>
                    <div class="flex w-full gap-2.5">
                        <!-- Payment Information Left Section  -->
                        <div class="flex flex-col gap-y-1.5">
                            @foreach (['payment-method', 'shipping-method', 'currency', 'shipping-price'] as $item)
                                <p class="text-gray-600 dark:text-gray-300 font-semibold">
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