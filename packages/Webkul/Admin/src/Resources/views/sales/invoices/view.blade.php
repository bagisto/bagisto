<x-admin::layouts>
    {{-- Title of the page --}}
    <x-slot:title>
        Invoice #{{ $invoice->increment_id ?? $invoice->id }}
    </x-slot:title>

    @php
        $order = $invoice->order;
    @endphp

    {{-- Main Body --}}
    <div class="grid">
        <div class="flex  gap-[16px] justify-between items-center max-sm:flex-wrap">
            {!! view_render_event('sales.invoice.title.before', ['order' => $order]) !!}

            <p class="text-[20px] text-gray-800 font-bold leading-[24px]">
                Invoice #{{ $invoice->increment_id ?? $invoice->id }}
            </p>

            {!! view_render_event('sales.invoice.title.after', ['order' => $order]) !!}
        </div>
    </div>

    {{-- Filter row --}}
    <div class="flex  gap-[16px] justify-between items-center mt-[28px] max-md:flex-wrap">
        <div class="flex gap-x-[4px] gap-y-[8px] items-center flex-wrap">
            {!! view_render_event('sales.invoice.page_action.before', ['order' => $order]) !!}

            <a
                href="{{ route('admin.sales.invoices.print', $invoice->id) }}"
                class="inline-flex gap-x-[8px] items-center justify-between w-full max-w-max px-[4px] py-[6px] text-gray-600 font-semibold text-center  cursor-pointer transition-all hover:bg-gray-200 hover:rounded-[6px]">
                <span class="icon-printer text-[24px] "></span> Print
            </a>

            {{-- Send Duplicate Invoice Modal --}}

            <div>
                <button
                    type="button"
                    class="inline-flex gap-x-[8px] items-center justify-between w-full max-w-max px-[4px] py-[6px] text-gray-600 font-semibold text-center cursor-pointer transition-all hover:bg-gray-200 hover:rounded-[6px]"
                    @click="$refs.groupCreateModal.open()"
                >
                    <span class="icon-cart text-[24px] "></span> Send Duplicate invoice
                </button>

                <x-admin::form :action="route('admin.sales.invoices.send_duplicate', $invoice->id)">
                        <!-- Create Group Modal -->
                        <x-admin::modal ref="groupCreateModal">          
                            <x-slot:header>
                                <!-- Modal Header -->
                                <p class="text-[18px] text-gray-800 font-bold">
                                    Send Duplicate Invoice
                                </p>   
                            </x-slot:header>
            
                            <x-slot:content>
                                <!-- Modal Content -->
                                <div class="px-[16px] py-[10px] border-b-[1px] border-gray-300">
                                    <x-admin::form.control-group>
                                        <x-admin::form.control-group.label class="required">
                                            Email
                                        </x-admin::form.control-group.label>
            
                                        <x-admin::form.control-group.control
                                            type="email"
                                            name="email"
                                            id="email"
                                            rules="required|email"
                                            :value="$invoice->order->customer_email"
                                            label="Email"
                                        >
                                        </x-admin::form.control-group.control>
            
                                        <x-admin::form.control-group.error
                                            control-name="email"
                                        >
                                        </x-admin::form.control-group.error>
                                    </x-admin::form.control-group>
                                </div>
                            </x-slot:content>
            
                            <x-slot:footer>
                                <!-- Modal Submission -->
                                <div class="flex gap-x-[10px] items-center">
                                    <button 
                                        type="submit"
                                        class="px-[12px] py-[6px] bg-blue-600 border border-blue-700 rounded-[6px] text-gray-50 font-semibold cursor-pointer"
                                    >
                                        Send
                                    </button>
                                </div>
                            </x-slot:footer>
                        </x-admin::modal>
                </x-admin::form>
            </div>

            {!! view_render_event('sales.invoice.page_action.after', ['order' => $order]) !!}

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
                                <div class="grid gap-[4px] content-center justify-items-center min-w-[60px] h-[60px] px-[6px] border border-dashed border-gray-300 rounded-[4px]">
                                    <img 
                                        src="{{ bagisto_asset('images/product-placeholders/top-angle.svg') }}" 
                                        class="w-[20px]"
                                    >
                                    <p class="text-[6px] text-gray-400 font-semibold">
                                        Product Image
                                    </p>
                                </div>
                                
                                <div class="grid gap-[6px] place-content-start">
                                    {{-- Item Name --}}
                                    <p class="text-[16x] text-gray-800 font-semibold">
                                        {{ $item->name }}
                                    </p>

                                    <p class="text-gray-600">
                                        {{core()->formatBasePrice($item->base_price) }}
                                        @lang('per unit') x {{ $item->qty}}@lang('Quantity')
                                    </p>

                                    <div class="flex flex-col gap-[6px] place-items-start">
                                        @if (isset($item->additional['attributes']))
                                            {{-- Item Additional Details --}} 
                                            <p class="text-gray-600">
                                                @foreach ($item->additional['attributes'] as $attribute)
                                                    {{ $attribute['attribute_name'] }} : {{ $attribute['option_label'] }}
                                                @endforeach
                                            </p>
                                        @endif

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
                            Customer
                    </p>
                </x-slot:header>
                <x-slot:content>
                    <div class="flex flex-col pb-[16px]">
                        <p class="text-gray-800 font-semibold">
                            {{ $invoice->order->customer_full_name }}
                        </p>

                        {!! view_render_event('sales.invoice.customer_name.after', ['order' => $order]) !!}

                        <p class="text-gray-600">
                            Email - {{ $invoice->order->customer_email }}
                        </p>

                        {!! view_render_event('sales.invoice.customer_email.after', ['order' => $order]) !!}
                    </div>

                    <span class="block w-full border-b-[1px] border-gray-300"></span>

                    @if ($order->billing_address || $order->shipping_address)
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
                    @endif
                </x-slot:content>
            </x-admin::accordion> 
            
            {{-- component 2 --}}
        
            <x-admin::accordion>
                <x-slot:header>
                    <p class="text-gray-600 text-[16px] p-[10px] font-semibold">
                        Order Information
                    </p>
                </x-slot:header>

                <x-slot:content>
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

                            {!! view_render_event('sales.invoice.increment_id.after', ['order' => $order]) !!}

                            {{-- Order Date --}}
                            <p class="text-gray-600">
                                {{ core()->formatDate($order->created_at) }}
                            </p>

                            {!! view_render_event('sales.invoice.created_at.after', ['order' => $order]) !!}

                            {{-- Order Status --}}
                            <p class="text-gray-600">
                                {{ $order->status_label }}
                            </p>

                            {!! view_render_event('sales.invoice.status_label.after', ['order' => $order]) !!}

                            {{-- Invoice Status --}}
                            <p class="text-gray-600">
                                {{ $invoice->status_label }}
                            </p>

                            {{-- Order Channel --}}
                            <p class="text-gray-600">
                                {{ $order->channel_name }}
                            </p>

                            {!! view_render_event('sales.invoice.channel_name.after', ['order' => $order]) !!}

                        </div>
                    </div>
                </x-slot:content>
            </x-admin::accordion>
        </div>
    </div>
</x-admin::layouts>
