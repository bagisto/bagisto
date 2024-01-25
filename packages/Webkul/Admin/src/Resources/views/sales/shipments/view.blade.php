<x-admin::layouts>
    <!-- Title of the page -->
    <x-slot:title>
        @lang('admin::app.sales.shipments.view.title', ['shipment_id' => $shipment->id])  
    </x-slot>

    @php $order = $shipment->order; @endphp

    <div class="grid">
        <div class="flex  gap-4 justify-between items-center max-sm:flex-wrap">
            <p class="text-xl text-gray-800 dark:text-white font-bold leading-6">
                @lang('admin::app.sales.shipments.view.title', ['shipment_id' => $shipment->id])
            </p>

            <div class="flex gap-x-2.5 items-center">
                <!-- Back Button -->
                <a
                    href="{{ route('admin.sales.shipments.index') }}"
                    class="transparent-button hover:bg-gray-200 dark:hover:bg-gray-800 dark:text-white"
                >
                    @lang('admin::app.account.edit.back-btn')
                </a>
            </div>
        </div>
    </div>

    <!-- body content -->
    <div class="flex gap-2.5 mt-3.5 max-xl:flex-wrap">
        <!-- Left sub-component -->
        <div class="flex flex-col gap-2 flex-1 max-xl:flex-auto">
            <!-- General -->
            <div class="bg-white dark:bg-gray-900 rounded box-shadow">
                <p class="text-base text-gray-800 dark:text-white font-semibold mb-4 p-4">
                    @lang('admin::app.sales.shipments.view.ordered-items') ({{count($shipment->items)}})
                </p>

                <div class="grid">
                    <!-- Shipment Items -->
                    @foreach ($shipment->items as $index => $item)
                        <div class="flex gap-2.5 justify-between px-4 py-6">
                            <div class="flex gap-2.5">
                                <!-- Image -->
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

                                <div class="grid gap-1.5 place-content-start">
                                    <p class="text-base text-gray-800 dark:text-white font-semibold">
                                        {{ $item->name }}
                                    </p>

                                    <div class="flex flex-col gap-1.5 place-items-start">
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
                            <span class="block w-full border-b dark:border-gray-800"></span>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Right sub-component -->
        <div class="flex flex-col gap-2 w-[360px] max-w-full max-sm:w-full">
            <!-- component 1 -->
            <x-admin::accordion>
                <x-slot:header>
                    <p class="text-gray-600 dark:text-gray-300 text-base  p-2.5 font-semibold">
                        @lang('admin::app.sales.shipments.view.customer')
                    </p>
                </x-slot>

                <x-slot:content>
                    <div class="flex flex-col pb-4">
                        <!-- Customer Full Name -->
                        <p class="text-gray-800 font-semibold dark:text-white">
                            {{ $shipment->order->customer_full_name }}
                        </p>

                        <!-- Customer Email -->
                        <p class="text-gray-600 dark:text-gray-300">
                            @lang('admin::app.sales.shipments.view.email', ['email' =>  $shipment->order->customer_email ])
                        </p>
                    </div>

                    <span class="block w-full border-b dark:border-gray-800"></span>

                    @if ($order->billing_address || $order->shipping_address)
                        <!-- Billing Address -->
                        @if ($order->billing_address)
                            <div class="flex items-center justify-between">
                                <p class="text-gray-600 dark:text-gray-300 text-base  py-4 font-semibold">
                                    @lang('admin::app.sales.shipments.view.billing-address')
                                </p>
                            </div>

                            @include ('admin::sales.address', ['address' => $order->billing_address])
                            
                        @endif

                        <!-- Shipping Address -->
                        @if ($order->shipping_address)
                            <span class="block w-full mt-4 border-b dark:border-gray-800"></span>

                            <div class="flex items-center justify-between">
                                <p class="text-gray-600 dark:text-gray-300 text-base  py-4 font-semibold">
                                    @lang('admin::app.sales.shipments.view.shipping-address')
                                </p>
                            </div>

                            @include ('admin::sales.address', ['address' => $order->shipping_address])

                        @endif
                    @endif
                </x-slot>
            </x-admin::accordion> 
         
            <!-- component 2 -->
            <x-admin::accordion>
                <x-slot:header>
                    <p class="text-gray-600 dark:text-gray-300 text-base  p-2.5 font-semibold">
                        @lang('admin::app.sales.shipments.view.order-information')
                    </p>
                </x-slot>

                <x-slot:content>
                    <div class="flex w-full gap-5 justify-start">
                        <div class="flex flex-col gap-y-1.5">
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

                        <div class="flex flex-col gap-y-1.5">
                            <!-- Order Id -->
                            <p class="text-blue-600 font-semibold">
                                <a href="{{ route('admin.sales.orders.view', $order->id) }}">
                                    #{{ $order->increment_id }}
                                </a>
                            </p>

                            <!-- Order Date -->
                            <p class="text-gray-600 dark:text-gray-300">
                                {{ core()->formatDate($order->created_at) }}
                            </p>

                            <!-- Order Status -->
                            <p class="text-gray-600 dark:text-gray-300">
                                {{ $order->status_label }}
                            </p>

                            <!-- Order Channel -->
                            <p class="text-gray-600 dark:text-gray-300">
                                {{ $order->channel_name }}
                            </p>
                        </div>
                    </div>
                </x-slot>
            </x-admin::accordion>

            <!-- Component 3 -->
            <x-admin::accordion>
                <x-slot:header>
                    <p class="text-gray-600 dark:text-gray-300 text-base  p-2.5 font-semibold">
                        @lang('admin::app.sales.shipments.view.payment-and-shipping')
                    </p>
                </x-slot>

                <x-slot:content>
                    <div class="pb-4">
                        <!-- Payment method -->
                        <p class="text-gray-800 font-semibold dark:text-white">
                            {{ core()->getConfigData('sales.payment_methods.' . $order->payment->method . '.title') }}
                        </p>

                        <p class="text-gray-600 dark:text-gray-300">
                            @lang('admin::app.sales.shipments.view.payment-method')
                        </p>

                        <!-- Currency Code -->
                        <p class="pt-4 text-gray-800 dark:text-white font-semibold">  
                            {{ $order->order_currency_code }}
                        </p>

                        <p class="text-gray-600 dark:text-gray-300">
                            @lang('admin::app.sales.shipments.view.currency')
                        </p>
                    </div>

                    <!-- Horizontal Line -->
                    <span class="block w-full border-b dark:border-gray-800"></span>
                
                    <div class="pt-4">
                        <!-- Shipping Menthod -->
                        <p class="text-gray-800 font-semibold dark:text-white">
                            {{ $order->shipping_title }}
                        </p>

                        <p class="text-gray-600 dark:text-gray-300">
                            @lang('admin::app.sales.shipments.view.shipping-method')
                        </p>

                        <!-- Inventory Source -->
                        <p class="pt-4 text-gray-800 dark:text-white font-semibold">
                            {{ core()->formatBasePrice($order->base_shipping_amount) }}
                        </p>

                        <p class="text-gray-600 dark:text-gray-300">
                            @lang('admin::app.sales.shipments.view.shipping-price')
                        </p>

                        @if (
                            $shipment->inventory_source
                            || $shipment->inventory_source_name
                        )
                            <p class="pt-4 text-gray-800 dark:text-white font-semibold">
                                {{ $shipment->inventory_source ? $shipment->inventory_source->name : $shipment->inventory_source_name }}
                            </p>

                            <p class="text-gray-600 dark:text-gray-300">
                                @lang('admin::app.sales.shipments.view.inventory-source')
                            </p>
                        @endif

                        @if ($shipment->carrier_title)
                            <p class="pt-4 text-gray-800 dark:text-white font-semibold">
                                {{ $shipment->carrier_title }}
                            </p>
                            
                            <p class="text-gray-600 dark:text-gray-300">
                                @lang('admin::app.sales.shipments.view.carrier-title')
                            </p>
                        @endif

                        @if ($shipment->track_number)
                            <p class="pt-4 text-gray-800 dark:text-white font-semibold">
                                {{ $shipment->track_number }}
                            </p>

                            <p class="text-gray-600 dark:text-gray-300">
                                @lang('admin::app.sales.shipments.view.tracking-number')
                            </p>
                        @endif
                    </div>
                </x-slot>
            </x-admin::accordion> 
        </div>
    </div>
</x-admin::layouts>
