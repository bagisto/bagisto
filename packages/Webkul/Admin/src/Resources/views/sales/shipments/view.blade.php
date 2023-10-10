<x-admin::layouts>
    {{-- Title of the page --}}
    <x-slot:title>
        @lang('admin::app.sales.shipments.view.title', ['shipment_id' => $shipment->id])
        
    </x-slot:title>

    @php $order = $shipment->order; @endphp

    <div class="grid">
        <div class="flex  gap-[16px] justify-between items-center max-sm:flex-wrap">
            <p class="text-[20px] text-gray-800 dark:text-white font-bold leading-[24px]">
                @lang('admin::app.sales.shipments.view.title', ['shipment_id' => $shipment->id])
            </p>

            <div class="flex gap-x-[10px] items-center">
                {{-- Cancel Button --}}
                <a
                    href="{{ route('admin.sales.shipments.index') }}"
                    class="transparent-button hover:bg-gray-200 dark:hover:bg-gray-800 dark:text-white"
                >
                    @lang('admin::app.account.edit.back-btn')
                </a>
            </div>
        </div>
    </div>
    {{-- body content --}}
    <div class="flex gap-[10px] mt-[14px] max-xl:flex-wrap">
        {{-- Left sub-component --}}
        <div class=" flex flex-col gap-[8px] flex-1 max-xl:flex-auto">
            {{-- General --}}
            <div class="bg-white dark:bg-gray-900 rounded-[4px] box-shadow">
                <p class="text-[16px] text-gray-800 dark:text-white font-semibold mb-[16px] p-[16px]">
                    @lang('admin::app.sales.shipments.view.ordered-items') ({{count($shipment->items)}})
                </p>

                <div class="grid">
                    {{-- Shipment Items --}}
                    @foreach ($shipment->items as $index => $item)
                        <div class="flex gap-[10px] justify-between px-[16px] py-[24px]">
                            <div class="flex gap-[10px]">
                                <!-- Image -->
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

                                <div class="grid gap-[6px] place-content-start">
                                    <p class="text-[16x] text-gray-800 dark:text-white font-semibold">{{ $item->name }}
                                    </p>
                                    <div class="flex flex-col gap-[6px] place-items-start">

                                        @if (isset($item->additional['attributes']))
                                            <p class="text-gray-600 dark:text-gray-300">
                                                @foreach ($item->additional['attributes'] as $attribute)
                                                    {{ $attribute['attribute_name'] }} : {{ $attribute['option_label'] }}
                                                @endforeach
                                            </p>
                                        @endif

                                        <p class="text-gray-600 dark:text-gray-300">
                                            @lang('admin::app.sales.shipments.view.sku', ['sku' =>  $item->sku ])
                                        </p>
                                        
                                        <p class="text-gray-600 dark:text-gray-300">
                                            @lang('admin::app.sales.shipments.view.qty', ['qty' =>  $item->qty ])
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @if ($index < count($shipment->items) - 1)
                            <span class="block w-full border-b-[1px] dark:border-gray-800"></span>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Right sub-component --}}
        <div class="flex flex-col gap-[8px] w-[360px] max-w-full max-sm:w-full">
            {{-- component 1 --}}
            <x-admin::accordion>
                <x-slot:header>
                    <p class="text-gray-600 dark:text-gray-300 text-[16px] p-[10px] font-semibold">
                        @lang('admin::app.sales.shipments.view.customer')
                    </p>
                </x-slot:header>

                <x-slot:content>
                    <div class="flex flex-col pb-[16px]">
                        {{-- Customer Full Name --}}
                        <p class="text-gray-800 font-semibold dark:text-white">
                            {{ $shipment->order->customer_full_name }}
                        </p>

                        {{-- Customer Email --}}
                        <p class="text-gray-600 dark:text-gray-300">
                            @lang('admin::app.sales.shipments.view.email', ['email' =>  $shipment->order->customer_email ])
                        </p>
                    </div>

                    <span class="block w-full border-b-[1px] dark:border-gray-800"></span>

                    @if ($order->billing_address || $order->shipping_address)
                        {{-- Billing Address --}}
                        @if ($order->billing_address)
                            <div class="flex items-center justify-between">
                                <p class="text-gray-600 dark:text-gray-300 text-[16px] py-[16px] font-semibold">
                                    @lang('admin::app.sales.shipments.view.billing-address')
                                </p>
                            </div>

                            @include ('admin::sales.address', ['address' => $order->billing_address])
                            
                        @endif

                        {{-- Shipping Address --}}
                        @if ($order->shipping_address)
                            <span class="block w-full mt-[16px] border-b-[1px] dark:border-gray-800"></span>

                            <div class="flex items-center justify-between">
                                <p class="text-gray-600 dark:text-gray-300 text-[16px] py-[16px] font-semibold">
                                    @lang('admin::app.sales.shipments.view.shipping-address')
                                </p>
                            </div>

                            @include ('admin::sales.address', ['address' => $order->shipping_address])

                        @endif
                    @endif
                </x-slot:content>
            </x-admin::accordion> 
         
            {{-- component 2 --}}
            <x-admin::accordion>
                <x-slot:header>
                    <p class="text-gray-600 dark:text-gray-300 text-[16px] p-[10px] font-semibold">
                        @lang('admin::app.sales.shipments.view.order-information')
                    </p>
                </x-slot:header>

                <x-slot:content>
                    <div class="flex w-full gap-[20px] justify-start">
                        <div class="flex flex-col gap-y-[6px]">
                            <p class="text-gray-600 dark:text-gray-300">
                                @lang('admin::app.sales.shipments.view.order-id')     
                            </p>

                            <p class="text-gray-600 dark:text-gray-300">
                                @lang('admin::app.sales.shipments.view.order-date')     
                           </p>

                            <p class="text-gray-600 dark:text-gray-300">
                                @lang('admin::app.sales.shipments.view.order-status')        
                            </p>

                            <p class="text-gray-600 dark:text-gray-300">
                                @lang('admin::app.sales.shipments.view.channel')                     
                            </p>
                        </div>

                        <div class="flex  flex-col gap-y-[6px]">
                            {{-- Order Id --}}
                            <p class="text-blue-600 font-semibold">
                                <a href="{{ route('admin.sales.orders.view', $order->id) }}">#{{ $order->increment_id }}</a>
                            </p>

                            {{-- Order Date --}}
                            <p class="text-gray-600 dark:text-gray-300">
                                {{ core()->formatDate($order->created_at) }}
                            </p>

                            {{-- Order Status --}}
                            <p class="text-gray-600 dark:text-gray-300">
                                {{ $order->status_label }}
                            </p>

                            {{-- Order Channel --}}
                            <p class="text-gray-600 dark:text-gray-300">
                                {{ $order->channel_name }}
                            </p>
                        </div>
                    </div>
                </x-slot:content>
            </x-admin::accordion>

            {{-- Component 3 --}}
            <x-admin::accordion>
                <x-slot:header>
                    <p class="text-gray-600 dark:text-gray-300 text-[16px] p-[10px] font-semibold">
                        @lang('admin::app.sales.shipments.view.payment-and-shipping')
                    </p>
                </x-slot:header>

                <x-slot:content>
                    <div class="pb-[16px]">
                        {{-- Payment method --}}
                        <p class="text-gray-800 font-semibold dark:text-white">
                            {{ core()->getConfigData('sales.payment_methods.' . $order->payment->method . '.title') }}
                        </p>

                        <p class="text-gray-600 dark:text-gray-300">
                            @lang('admin::app.sales.shipments.view.payment-method')
                        </p>

                        {{-- Currency Code --}}
                        <p class="pt-[16px] text-gray-800 dark:text-white font-semibold">  
                            {{ $order->order_currency_code }}
                        </p>

                        <p class="text-gray-600 dark:text-gray-300">
                            @lang('admin::app.sales.shipments.view.currency')
                        </p>
                    </div>

                    {{-- Horizontal Line --}}
                    <span class="block w-full border-b-[1px] dark:border-gray-800"></span>
                
                    <div class="pt-[16px]">
                        {{-- Shipping Menthod --}}
                        <p class="text-gray-800 font-semibold dark:text-white">
                            {{ $order->shipping_title }}
                        </p>

                        <p class="text-gray-600 dark:text-gray-300">
                            @lang('admin::app.sales.shipments.view.shipping-method')
                        </p>

                        {{-- Inventory Source --}}
                        <p class="pt-[16px] text-gray-800 dark:text-white font-semibold">
                            {{ core()->formatBasePrice($order->base_shipping_amount) }}
                        </p>

                        <p class="text-gray-600 dark:text-gray-300">
                            @lang('admin::app.sales.shipments.view.shipping-price')
                        </p>

                        @if (
                            $shipment->inventory_source
                            || $shipment->inventory_source_name
                        )
                            <p class="pt-[16px] text-gray-800 dark:text-white font-semibold">
                                {{ $shipment->inventory_source ? $shipment->inventory_source->name : $shipment->inventory_source_name }}
                            </p>

                            <p class="text-gray-600 dark:text-gray-300">
                                @lang('admin::app.sales.shipments.view.inventory-source')
                            </p>
                        @endif

                        @if ($shipment->carrier_title)
                            <p class="pt-[16px] text-gray-800 dark:text-white font-semibold">
                                {{ $shipment->carrier_title }}
                            </p>
                            
                            <p class="text-gray-600 dark:text-gray-300">
                                @lang('admin::app.sales.shipments.view.carrier-title')
                            </p>
                        @endif

                        @if ($shipment->track_number)
                            <p class="pt-[16px] text-gray-800 dark:text-white font-semibold">
                                {{ $shipment->track_number }}
                            </p>

                            <p class="text-gray-600 dark:text-gray-300">
                                @lang('admin::app.sales.shipments.view.tracking-number')
                            </p>
                        @endif
                    </div>
                </x-slot:content>
            </x-admin::accordion> 
        </div>
    </div>
</x-admin::layouts>
