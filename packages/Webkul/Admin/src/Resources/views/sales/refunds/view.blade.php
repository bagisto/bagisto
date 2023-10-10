@php $order = $refund->order; @endphp

<x-admin::layouts>
    {{-- Page Title --}}
    <x-slot:title>
        @lang('admin::app.sales.refunds.view.title', ['refund_id' => $refund->id])
    </x-slot:title>

    {{-- Page Header --}}
    <div class="grid pt-[11px]">
        <div class="flex  gap-[16px] justify-between items-center max-sm:flex-wrap">
            <p class="text-[20px] text-gray-800 dark:text-white font-bold leading-[24px]">
                @lang('admin::app.sales.refunds.view.title', ['refund_id' => $refund->id])
            </p>

            {{-- Cancel Button --}}
            <div class="flex gap-x-[10px] items-center">
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
    <div class="flex gap-[10px] mt-[14px] max-xl:flex-wrap">
        <!-- Left sub-component -->
        <div class=" flex flex-col gap-[8px] flex-1 max-xl:flex-auto">
            <!-- General -->
            <div class=" bg-white dark:bg-gray-900 rounded-[4px] box-shadow">
                <p class="text-[16px] text-gray-800 dark:text-white font-semibold mb-[16px] p-[16px]">
                    @lang('admin::app.sales.refunds.view.product-ordered') ({{ $refund->items->count() ?? 0 }})
                </p>

                {{-- Products List --}}
                <div class="grid">
                    @foreach ($refund->items as $item)
                        <div class="flex gap-[10px] justify-between px-[16px] py-[24px] border-b-[1px] border-slate-300 dark:border-gray-800">
                            <div class="flex gap-[10px]">
                                @if ($item->product?->base_image_url)
                                    <img
                                        class="w-full h-[60px] max-w-[60px] max-h-[60px] relative rounded-[4px]"
                                        src="{{ $item->product->base_image_url }}"
                                    >
                                @else
                                    <div class="w-full h-[60px] max-w-[60px] max-h-[60px] relative border border-dashed border-gray-300 dark:border-gray-800 rounded-[4px] dark:invert dark:mix-blend-exclusion">
                                        <img src="{{ bagisto_asset('images/product-placeholders/front.svg') }}">
                                        
                                        <p class="absolute w-full bottom-[5px] text-[6px] text-gray-400 text-center font-semibold"> 
                                            @lang('admin::app.sales.invoices.view.product-image') 
                                        </p>
                                    </div>
                                @endif

                                {{-- Product Name --}}
                                <div class="grid gap-[6px] place-content-start">
                                    <p class="text-[16x] text-gray-800 dark:text-white font-semibold">
                                        {{ $item->name }}
                                    </p>

                                    {{-- Product Attribute Detailes --}}
                                    <div class="flex flex-col gap-[6px] place-items-start">
                                        @if (isset($item->additional['attributes']))
                                            @foreach ($item->additional['attributes'] as $attribute)
                                                <p class="text-gray-600 dark:text-gray-300">
                                                    {{ $attribute['attribute_name'] }} : {{ $attribute['option_label'] }}
                                                </p>
                                            @endforeach
                                        @endif
                                    </div>

                                    {{-- Product SKU --}}
                                    <p class="text-gray-600 dark:text-gray-300">
                                        @lang('admin::app.sales.refunds.view.sku', ['sku' => $item->child ? $item->child->sku : $item->sku])
                                    </p>

                                    {{-- Product QTY --}}
                                    <p class="text-gray-600 dark:text-gray-300">
                                        @lang('admin::app.sales.refunds.view.qty', ['qty' => $item->qty])
                                    </p>
                                </div>
                            </div>

                            {{-- Product Price Section --}}
                            <div class="grid gap-[4px] place-content-start">
                                <div class="">
                                    <p class="flex items-center gap-x-[4px] justify-end text-[16px] text-gray-800 dark:text-white font-semibold">
                                        {{ core()->formatBasePrice($item->base_total + $item->base_tax_amount - $item->base_discount_amount) }}
                                    </p>
                                </div>

                                <div class="flex flex-col gap-[6px] items-end place-items-start">
                                    {{-- Base Total --}}
                                    <p class="text-gray-600 dark:text-gray-300">
                                        @lang('admin::app.sales.refunds.view.price', ['price' => core()->formatBasePrice($item->base_total)])
                                    </p>

                                    {{-- Base Tax Amount --}}
                                    <p class="text-gray-600 dark:text-gray-300">
                                        @lang('admin::app.sales.refunds.view.tax-amount', ['tax_amount' => core()->formatBasePrice($item->base_tax_amount)])
                                    </p>

                                    {{-- Base Discount Amount --}}
                                    <p class="text-gray-600 dark:text-gray-300">
                                        @lang('admin::app.sales.refunds.view.base-discounted-amount', ['base_discounted_amount' => core()->formatBasePrice($item->base_discount_amount)])
                                    </p>

                                    {{-- Base Discount Amount --}}
                                    <p class="text-gray-600 dark:text-gray-300">
                                        @lang('admin::app.sales.refunds.view.discounted-amount', ['discounted_amount' => core()->formatBasePrice($item->base_total + $item->base_tax_amount - $item->base_discount_amount)])
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Subtotal / Grand Total od the page --}}
                <div class="flex w-full gap-[10px] justify-end mt-[16px] p-[16px]">
                    <div class="flex flex-col gap-y-[6px]">
                        <p class="text-gray-600 dark:text-gray-300 font-semibold">
                            @lang('admin::app.sales.refunds.view.sub-total')
                        </p>

                        @if ($refund->base_shipping_amount > 0)
                            <p class="text-gray-600 dark:text-gray-300">
                                @lang('admin::app.sales.refunds.view.shipping-handling')
                            </p>
                        @endif

                        @if ($refund->base_tax_amount > 0)
                            <p class="text-gray-600 dark:text-gray-300">
                                @lang('admin::app.sales.refunds.view.tax')
                            </p>
                        @endif

                        @if ($refund->base_discount_amount > 0)
                            <p class="text-gray-600 dark:text-gray-300">
                                @lang('admin::app.sales.refunds.view.discounted-amount')
                            </p>
                        @endif

                        <p class="text-gray-600 dark:text-gray-300">
                            @lang('admin::app.sales.refunds.view.adjustment-refund')
                        </p>

                        <p class="text-gray-600 dark:text-gray-300">
                            @lang('admin::app.sales.refunds.view.adjustment-fee')
                        </p>

                        <p class="text-[16px] text-gray-800 dark:text-white font-semibold">
                            @lang('admin::app.sales.refunds.view.grand-total')
                        </p>
                    </div>

                    <div class="flex  flex-col gap-y-[6px]">
                        {{-- Base Sub Total --}}
                        <p class="text-gray-600 dark:text-gray-300 font-semibold">
                            {{ core()->formatBasePrice($refund->base_sub_total) }}
                        </p>

                        {{-- Base Shipping Amount --}}
                        @if ($refund->base_shipping_amount > 0)
                            <p class="text-gray-600 dark:text-gray-300">
                                {{ core()->formatBasePrice($refund->base_shipping_amount) }}
                            </p>
                        @endif

                        {{-- Base Tax Amount --}}
                        @if ($refund->base_tax_amount > 0)
                            <p class="text-gray-600 dark:text-gray-300">
                                {{ core()->formatBasePrice($refund->base_tax_amount) }}
                            </p>
                        @endif

                        {{-- Base Discount Amouont --}}
                        @if ($refund->base_discount_amount > 0)
                            <p class="text-gray-600 dark:text-gray-300">
                                {{ core()->formatBasePrice($refund->base_discount_amount) }}
                            </p>
                        @endif

                        {{-- Base Adjustment Refund --}}
                        <p class="text-gray-600 dark:text-gray-300">
                            {{ core()->formatBasePrice($refund->base_adjustment_refund) }}
                        </p>

                        {{-- Base Adjustment Fee --}}
                        <p class="text-gray-600 dark:text-gray-300">
                            {{ core()->formatBasePrice($refund->base_adjustment_fee) }}
                        </p>

                        {{-- Base Grand Total --}}
                        <p class="text-[16px] text-gray-800 dark:text-white font-semibold">
                            {{ core()->formatBasePrice($refund->base_grand_total) }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right sub-component -->
        <div class="flex flex-col gap-[8px] w-[360px] max-w-full max-sm:w-full">
            {{-- Account Information --}}
            @if (
                $order->billing_address
                || $order->shipping_address
            )
                <x-admin::accordion>
                    <x-slot:header>
                        <p class="text-gray-600 dark:text-gray-300 text-[16px] p-[10px] font-semibold">
                            @lang('admin::app.sales.refunds.view.account-information')
                        </p>
                    </x-slot:header>
                
                    <x-slot:content>
                        {{-- Account Info --}}
                        <div class="flex flex-col pb-[16px]">
                            {{-- Customer Full Name --}}
                            <p class="text-gray-800 font-semibold dark:text-white">
                                {{ $refund->order->customer_full_name }}
                            </p>

                            {{-- Customer Email --}}
                            <p class="text-gray-600 dark:text-gray-300">
                                {{ $refund->order->customer_email }}
                            </p>
                        </div>

                        {{-- Billing Address --}}
                        @if ($order->billing_address)
                            <span class="block w-full border-b-[1px] dark:border-gray-800"></span>

                            {{-- Billing Address --}}
                            <div class="flex items-center justify-between">
                                <p class="text-gray-600 dark:text-gray-300 text-[16px] py-[16px] font-semibold">
                                    @lang('admin::app.sales.refunds.view.billing-address')
                                </p>
                            </div>
        
                            @include ('admin::sales.address', ['address' => $order->billing_address])
                        @endif

                        {{-- Shipping Address --}}
                        @if ($order->shipping_address)
                            <span class="block w-full mt-[16px] border-b-[1px] dark:border-gray-800"></span>

                            <div class="flex items-center justify-between">
                                <p class="text-gray-600 dark:text-gray-300  text-[16px] py-[16px] font-semibold">
                                    @lang('admin::app.sales.refunds.view.shipping-address')
                                </p>
                            </div>

                            @include ('admin::sales.address', ['address' => $order->shipping_address])
                        @endif
                    </x-slot:content>
                </x-admin::accordion>
            @endif
            
            {{-- Order Information --}}
            <x-admin::accordion>
                <x-slot:header>
                    <p class="text-gray-600 dark:text-gray-300 text-[16px] p-[10px] font-semibold">
                        @lang('admin::app.sales.refunds.view.order-information')
                    </p>
                </x-slot:header>
            
                <x-slot:content>
                    <div class="flex w-full gap-[10px]">
                        {{-- Order Info Left Section  --}}
                        <div class="flex flex-col gap-y-[6px]">
                            @foreach (['order-id', 'order-date', 'order-status', 'order-channel'] as $item)
                                <p class="text-gray-600 dark:text-gray-300 font-semibold">
                                    @lang('admin::app.sales.refunds.view.' . $item)
                                </p>
                            @endforeach    
                        </div>

                        {{-- Order Info Right Section  --}}
                        <div class="flex flex-col gap-y-[6px]">
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
                </x-slot:content>
            </x-admin::accordion>

             {{-- Payment Information --}}
             <x-admin::accordion>
                <x-slot:header>
                    <p class="text-gray-600 dark:text-gray-300 text-[16px] p-[10px] font-semibold">
                        @lang('admin::app.sales.refunds.view.payment-information')
                    </p>
                </x-slot:header>
            
                <x-slot:content>
                    <div class="flex w-full gap-[10px]">
                        {{-- Payment Information Left Section  --}}
                        <div class="flex flex-col gap-y-[6px]">
                            @foreach (['payment-method', 'shipping-method', 'currency', 'shipping-price'] as $item)
                                <p class="text-gray-600 dark:text-gray-300 font-semibold">
                                    @lang('admin::app.sales.refunds.view.' . $item)
                                </p>
                            @endforeach
                        </div>

                        {{-- Payment Information Right Section  --}}
                        <div class="flex flex-col gap-y-[6px]">
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
                </x-slot:content>
            </x-admin::accordion>
        </div>
    </div>
</x-admin::layouts>