<x-admin::layouts>
    @php
        $order = $invoice->order;
    @endphp
    
        {{-- Main Body --}}
        <div class="grid">
            <div class="flex  gap-[16px] justify-between items-center max-sm:flex-wrap">
                <p class="text-[20px] text-gray-800 font-bold leading-[24px]">
                    Invoice #{{$invoice->id}}
                </p>
            </div>
        </div>
        {{-- Filter row --}}
        <div class="flex  gap-[16px] justify-between items-center mt-[28px] max-md:flex-wrap">
            <div class="flex gap-x-[4px] gap-y-[8px] items-center flex-wrap">
                <div
                    class="inline-flex gap-x-[8px] items-center justify-between w-full max-w-max px-[4px] py-[6px] text-gray-600 font-semibold text-center  cursor-pointer transition-all hover:bg-gray-200 hover:rounded-[6px]">
                    <span class="icon-printer text-[24px] "></span> Print
                </div>
                <div
                    class="inline-flex gap-x-[8px] items-center justify-between w-full max-w-max px-[4px] py-[6px] text-gray-600 font-semibold text-center  cursor-pointer transition-all hover:bg-gray-200 hover:rounded-[6px]">
                    <span class="icon-mail text-[24px] "></span> Email
                </div>
                <div
                    class="inline-flex gap-x-[8px] items-center justify-between w-full max-w-max px-[4px] py-[6px] text-gray-600 font-semibold text-center  cursor-pointer transition-all hover:bg-gray-200 hover:rounded-[6px]">
                    <span class="icon-cart text-[24px] "></span> Send Duplicate invoice
                </div>

                {{--Send Duplicate Invoice Modal--}}
            </div>
        </div>
        {{-- body content --}}
        <div class="flex gap-[10px] mt-[14px] max-xl:flex-wrap">
            {{-- Left sub-component --}}
            <div class=" flex flex-col gap-[8px] flex-1 max-xl:flex-auto">
                {{-- Invoice Item Section --}}
                <div class="p-[16px] bg-white rounded-[4px] box-shadow">
                    <p class="text-[16px] text-gray-800 font-semibold mb-[16px]">Invoice Items ({{ count($invoice->items) }})</p>
                    <div class="grid">
                        {{-- Invoice Item Details--}}
                        @foreach($invoice->items as $item)
                            <div class="flex gap-[10px] justify-between px-[16px] py-[24px] border-b-[1px] border-slate-300">
                                <div class="flex gap-[10px]">
                                    {{-- Product Image --}}
                                    <div
                                        class="grid gap-[4px] content-center justify-items-center min-w-[60px] h-[60px] px-[6px] border border-dashed border-gray-300 rounded-[4px]">
                                        <img class="w-[20px]" src="{{ bagisto_asset('images/media-image.png') }}">
                                    </div>
                                    
                                    <div class="grid gap-[6px] place-content-start">
                                        {{-- Item Name --}}
                                        <p class="text-[16x] text-gray-800 font-semibold">
                                            {{ $item->name }}
                                        </p>
                                        <div class="flex flex-col gap-[6px] place-items-start">
                                            <p class="text-gray-600">
                                                {{-- Item Additional Details --}} 
                                                @if (isset($item->additional['attributes']))
                                                    <div>
                                                        @foreach ($item->additional['attributes'] as $attribute)
                                                            <b>{{ $attribute['attribute_name'] }} : </b>{{ $attribute['option_label'] }}
                                                        @endforeach
                                                    </div>
                                                @endif
                                            </p>

                                            {{--SKU --}}
                                            <p class="text-gray-600">
                                                SKU - {{ $item->getTypeInstance()->getOrderedItem($item)->sku }}
                                            </p>

                                            {{-- Quantity --}}
                                            <p class="text-gray-600">
                                                Qty - {{ $item->qty }}
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <div class="grid gap-[4px] place-content-start">
                                    <div class="">
                                        {{-- Item Grand Total --}}
                                        <p class="flex items-center gap-x-[4px] justify-end text-[16px] text-gray-800 font-semibold">
                                            {{ core()->formatBasePrice($item->base_total + $item->base_tax_amount - $item->base_discount_amount) }}
                                            <span 
                                                class="icon-sort-up text-[24px] p-[6px] rounded-[6px] cursor-pointer transition-all hover:bg-gray-100"
                                            >
                                            </span>
                                        </p>
                                    </div>

                                    {{-- Item Base Price --}}
                                    <div class="flex flex-col gap-[6px] items-end place-items-start">
                                        <p class="text-gray-600">
                                            Price - {{ core()->formatBasePrice($item->base_price) }}
                                        </p>

                                        {{-- Item Tax Amount --}}
                                        <p class="text-gray-600">
                                            Tax Amount- {{ core()->formatBasePrice($item->base_tax_amount) }}
                                        </p>

                                        {{-- Item Discount --}}
                                        @if ($invoice->base_discount_amount > 0)
                                            <p class="text-gray-600">
                                                Discount - {{ core()->formatBasePrice($item->base_discount_amount) }}
                                            </p>
                                        @endif

                                        {{-- Item Sub-Total --}}
                                        <p class="text-gray-600">
                                            Sub Total - {{ core()->formatBasePrice($item->base_total) }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    {{--Sale Summary --}}
                    <div class="flex w-full gap-[10px] justify-end mt-[16px]">
                        <div class="flex flex-col gap-y-[6px]">
                            <p class="text-gray-600 font-semibold">
                                Subtotal
                            </p>

                            <p class="text-gray-600">
                                Shipping and Handling
                            </p>

                            <p class="text-gray-600">
                                Tax
                            </p>

                            @if ($invoice->base_discount_amount > 0)
                                <p class="text-gray-600">Discount</p>
                            @endif

                            <p class="text-[16px] text-gray-800 font-semibold">
                                Grand Total
                            </p>
                        </div>

                        <div class="flex  flex-col gap-y-[6px]">
                            {{-- Subtotal --}}
                            <p class="text-gray-600 font-semibold">
                                {{ core()->formatBasePrice($invoice->base_sub_total) }}
                            </p>

                            {{-- Shipping and Handling --}}
                            <p class="text-gray-600">
                                {{ core()->formatBasePrice($invoice->base_shipping_amount) }}
                            </p>

                            {{-- Tax --}}
                            <p class="text-gray-600">
                                {{ core()->formatBasePrice($invoice->base_tax_amount) }}
                            </p>

                            {{-- Discount --}}
                            @if ($invoice->base_discount_amount > 0)
                                <p class="text-gray-600">
                                    {{ core()->formatBasePrice($invoice->base_discount_amount) }}
                                </p>
                            @endif
                            
                            {{-- Grand Total --}}
                            <p class="text-[16px] text-gray-800 font-semibold">
                                {{ core()->formatBasePrice($invoice->base_grand_total) }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            {{-- Right sub-component --}}
            <div class="flex flex-col gap-[8px] w-[360px] max-w-full max-sm:w-full">
                {{-- component 1 --}}
                <x-admin::accordion>
                    <x-slot:header>
                        <p class="text-gray-600 text-[16px] p-[10px] font-semibold">
                             Account and Order 
                        </p>
                    </x-slot:header>
                    <x-slot:content>
                        <div class="flex flex-col pb-[16px]">
                            <p class="text-gray-800 font-semibold">{{ $invoice->order->customer_full_name }}</p>
                            <p class="text-gray-600">Email - {{ $invoice->order->customer_email }}</p>
                        </div>

                        <span class="block w-full border-b-[1px] border-gray-300"></span>

                        <div class="">
                            <p class="text-gray-600 text-[16px] py-[16px] font-semibold">Order Information</p>

                            <div class="flex w-full gap-[20px] justify-start">
                                <div class="flex flex-col gap-y-[6px]">
                                    <p class="text-gray-600">
                                        Order Id
                                    </p>

                                    <p class="text-gray-600">
                                        Order Date
                                    </p>

                                    <p class="text-gray-600">
                                        Order Status
                                    </p>

                                    <p class="text-gray-600">
                                        Invoice Status
                                    </p>

                                    <p class="text-gray-600">
                                        Channel
                                    </p>
                                </div>
        
                                <div class="flex  flex-col gap-y-[6px]">
                                    {{-- Order Id --}}
                                    <p class="text-blue-600 font-semibold">
                                        <a href="{{ route('admin.sales.orders.view', $order->id) }}">#{{ $order->increment_id }}</a>
                                    </p>
        
                                    {{-- Order Date --}}
                                    <p class="text-gray-600">
                                        {{ core()->formatDate($order->created_at) }}
                                    </p>
        
                                    {{-- Order Status --}}
                                    <p class="text-gray-600">
                                        {{ $order->status_label }}
                                    </p>
        
                                    {{-- Invoice Status --}}
                                    <p class="text-gray-600">
                                        {{ $invoice->status_label }}
                                    </p>
                                    
                                    {{-- Order Channel --}}
                                    <p class="text-gray-600">
                                        {{ $order->channel_name }}
                                    </p>
                                </div>
                            </div>
                        </div>
                        
                    </x-slot:content>
                </x-admin::accordion> 
                
                {{-- component 2 --}}
                @if ($order->billing_address || $order->shipping_address)
                    {{-- Billing and shipping address accordion --}}
                    <x-admin::accordion>
                        <x-slot:header>
                            <p class="text-gray-600 text-[16px] p-[10px] font-semibold">
                                Addresses 
                            </p>
                        </x-slot:header>
                        <x-slot:content>
                            {{-- Billing Address --}}
                            @if ($order->billing_address)
                                <div class="pb-[16px]">
                                    <div class="flex items-center justify-between">
                                        <p class="text-gray-600 text-[16px] py-[16px] font-semibold">@lang('Billing Address')</p>
                                    </div>

                                    @include ('admin::sales.address', ['address' => $order->billing_address])

                                    {!! view_render_event('sales.invoice.billing_address.after', ['order' => $order]) !!}
                                </div>

                                <span class="block w-full border-b-[1px] border-gray-300"></span>
                            @endif

                            {{-- Shipping Address --}}
                            @if ($order->shipping_address)
                                <div class="pb-[16px]">
                                    <div class="flex items-center justify-between">
                                        <p class="text-gray-600 text-[16px] py-[16px] font-semibold">@lang('Shipping Address')</p>
                                    </div>

                                    @include ('admin::sales.address', ['address' => $order->shipping_address])

                                    {!! view_render_event('sales.invoice.shipping_address.after', ['order' => $order]) !!}
                                </div>
                            @endif
                        </x-slot:content>
                    </x-admin::accordion>
                @endif
            </div>
        </div>
</x-admin::layouts>
