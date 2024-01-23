<x-admin::layouts>
    <!-- Title of the page -->
    <x-slot:title>
        @lang('admin::app.sales.invoices.view.title', ['invoice_id' => $invoice->increment_id ?? $invoice->id])
    </x-slot>

    @php
        $order = $invoice->order;
    @endphp

    <!-- Main Body -->
    <div class="grid">
        <div class="flex gap-4 justify-between items-center max-sm:flex-wrap">
            {!! view_render_event('sales.invoice.title.before', ['order' => $order]) !!}

            <p class="text-xl text-gray-800 dark:text-white font-bold leading-6">
                @lang('admin::app.sales.invoices.view.title', ['invoice_id' => $invoice->increment_id ?? $invoice->id])

                <span class="label-active text-sm mx-1.5">
                    {{ $invoice->status_label }}
                </span>
            </p>

            {!! view_render_event('sales.invoice.title.after', ['order' => $order]) !!}

            <div class="flex gap-x-2.5 items-center">
                <!-- Back Button -->
                <a
                    href="{{ route('admin.sales.invoices.index') }}"
                    class="transparent-button hover:bg-gray-200 dark:hover:bg-gray-800 dark:text-white"
                >
                    @lang('admin::app.account.edit.back-btn')
                </a>
            </div>
        </div>
    </div>

    <!-- Filter row -->
    <div class="flex  gap-4 justify-between items-center mt-7 max-md:flex-wrap">
        <div class="flex gap-x-1 gap-y-2 items-center flex-wrap">
            {!! view_render_event('sales.invoice.page_action.before', ['order' => $order]) !!}

            <a
                href="{{ route('admin.sales.invoices.print', $invoice->id) }}"
                class="inline-flex gap-x-2 items-center justify-between w-full max-w-max px-1 py-1.5 text-gray-600 dark:text-gray-300 font-semibold text-center cursor-pointer transition-all hover:bg-gray-200 dark:hover:bg-gray-800 hover:rounded-md"
            >
                <span class="icon-printer text-2xl"></span> 

                @lang('admin::app.sales.invoices.view.print')
            </a>

            <!-- Send Duplicate Invoice Modal -->
            <div>
                <button
                    type="button"
                    class="inline-flex gap-x-2 items-center justify-between w-full max-w-max px-1 py-1.5 text-gray-600 dark:text-gray-300 font-semibold text-center cursor-pointer transition-all hover:bg-gray-200 dark:hover:bg-gray-800 hover:rounded-md"
                    @click="$refs.groupCreateModal.open()"
                >
                    <span class="icon-mail text-2xl"></span>

                    @lang('admin::app.sales.invoices.view.send-duplicate-invoice')
                </button>

                <x-admin::form :action="route('admin.sales.invoices.send_duplicate_email', $invoice->id)">
                    <!-- Create Group Modal -->
                    <x-admin::modal ref="groupCreateModal">
                        <!-- Modal Header -->
                        <x-slot:header>
                            <p class="text-lg text-gray-800 dark:text-white font-bold">
                                @lang('admin::app.sales.invoices.view.send-duplicate-invoice')
                            </p>
                        </x-slot>

                        <!-- Modal Content -->
                        <x-slot:content>
                            <x-admin::form.control-group>
                                <x-admin::form.control-group.label class="required">
                                    @lang('admin::app.sales.invoices.view.email')
                                </x-admin::form.control-group.label>
    
                                <x-admin::form.control-group.control
                                    type="email"
                                    id="email"
                                    name="email"
                                    rules="required|email"
                                    :value="$invoice->order->customer_email"
                                    :label="trans('admin::app.sales.invoices.view.email')"
                                />
    
                                <x-admin::form.control-group.error control-name="email" />
                            </x-admin::form.control-group>
                        </x-slot>
        
                        <!-- Modal Footer -->
                        <x-slot:footer>
                            <div class="flex gap-x-2.5 items-center">
                                <button 
                                    type="submit"
                                    class="primary-button"
                                >
                                    @lang('admin::app.sales.invoices.view.send')
                                </button>
                            </div>
                        </x-slot>
                    </x-admin::modal>
                </x-admin::form>
            </div>

            {!! view_render_event('sales.invoice.page_action.after', ['order' => $order]) !!}

        </div>
    </div>

    <!-- body content -->
    <div class="flex gap-2.5 mt-3.5 max-xl:flex-wrap">
        <!-- Left sub-component -->
        <div class="flex flex-col gap-2 flex-1 max-xl:flex-auto">
            <!-- Invoice Item Section -->
            <div class="bg-white dark:bg-gray-900 rounded box-shadow">
                <p class="text-base text-gray-800 dark:text-white font-semibold mb-4 p-4">
                    @lang('admin::app.sales.invoices.view.invoice-items') ({{ count($invoice->items) }})
                </p>

                <div class="grid">
                    <!-- Invoice Item Details-->
                    @foreach($invoice->items as $item)
                        <div class="flex gap-2.5 justify-between px-4 py-6 border-b border-slate-300 dark:border-gray-800">
                            <div class="flex gap-2.5">
                                <!-- Product Image -->
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
                                    <!-- Item Name -->
                                    <p class="text-base text-gray-800 dark:text-white font-semibold">
                                        {{ $item->name }}
                                    </p>

                                    <p class="text-gray-600 dark:text-gray-300">
                                        @lang('admin::app.sales.invoices.view.amount-per-unit', [
                                            'amount' => core()->formatBasePrice($item->base_price),
                                            'qty'    => $item->qty,
                                            ])
                                    </p>

                                    <div class="flex flex-col gap-1.5 place-items-start">
                                        @if (isset($item->additional['attributes']))
                                            <!-- Item Additional Details -->
                                            <p class="text-gray-600 dark:text-gray-300">
                                                @foreach ($item->additional['attributes'] as $attribute)
                                                    {{ $attribute['attribute_name'] }} : {{ $attribute['option_label'] }}
                                                @endforeach
                                            </p>
                                        @endif

                                        <!--SKU -->
                                        <p class="text-gray-600 dark:text-gray-300">
                                             @lang('admin::app.sales.invoices.view.sku', ['sku' => $item->getTypeInstance()->getOrderedItem($item)->sku])
                                        </p>

                                        <!-- Quantity -->
                                        <p class="text-gray-600 dark:text-gray-300">
                                            @lang('admin::app.sales.invoices.view.qty', ['qty' => $item->qty])
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div class="grid gap-1 place-content-start">
                                <!-- Item Grand Total -->
                                <p class="flex items-center gap-x-1 justify-end text-base text-gray-800 dark:text-white font-semibold">
                                    {{ core()->formatBasePrice($item->base_total + $item->base_tax_amount - $item->base_discount_amount) }}
                                </p>

                                <!-- Item Base Price -->
                                <div class="flex flex-col gap-1.5 items-end place-items-start">
                                    <p class="text-gray-600 dark:text-gray-300">
                                        @lang('admin::app.sales.invoices.view.price', ['price' => core()->formatBasePrice($item->base_price)])
                                    </p>

                                    <!-- Item Tax Amount -->
                                    <p class="text-gray-600 dark:text-gray-300">
                                        @lang('admin::app.sales.invoices.view.tax', ['tax' => core()->formatBasePrice($item->base_tax_amount)])
                                    </p>

                                    <!-- Item Discount -->
                                    @if ($invoice->base_discount_amount > 0)
                                        <p class="text-gray-600 dark:text-gray-300">
                                            @lang('admin::app.sales.invoices.view.discount', ['discount' => core()->formatBasePrice($item->base_discount_amount)])
                                        </p>
                                    @endif

                                    <!-- Item Sub-Total -->
                                    <p class="text-gray-600 dark:text-gray-300">
                                        @lang('admin::app.sales.invoices.view.sub-total', ['sub_total' => core()->formatBasePrice($item->base_total)])
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!--Sale Summary -->
                <div class="flex w-full gap-2.5 justify-end mt-4 p-4">
                    <div class="flex flex-col gap-y-1.5">
                        <p class="text-gray-600 dark:text-gray-300 font-semibold !leading-5">
                            @lang('admin::app.sales.invoices.view.sub-total-summary')
                        </p>

                        <p class="text-gray-600 dark:text-gray-300 !leading-5">
                            @lang('admin::app.sales.invoices.view.shipping-and-handling')                    
                        </p>

                        <p class="text-gray-600 dark:text-gray-300 !leading-5">
                            @lang('admin::app.sales.invoices.view.summary-tax')    
                        </p>

                        @if ($invoice->base_discount_amount > 0)
                            <p class="text-gray-600 dark:text-gray-300 !leading-5">
                                @lang('admin::app.sales.invoices.view.summary-discount')    
                            </p>
                        @endif

                        <p class="text-base text-gray-800 dark:text-white font-semibold !leading-5">
                            @lang('admin::app.sales.invoices.view.grand-total')   
                        </p>
                    </div>

                    <div class="flex flex-col gap-y-1.5">
                        <!-- Subtotal -->
                        <p class="text-gray-600 dark:text-gray-300 font-semibold !leading-5">
                            {{ core()->formatBasePrice($invoice->base_sub_total) }}
                        </p>

                        <!-- Shipping and Handling -->
                        <p class="text-gray-600 dark:text-gray-300 !leading-5">
                            {{ core()->formatBasePrice($invoice->base_shipping_amount) }}
                        </p>

                        <!-- Tax -->
                        <p class="text-gray-600 dark:text-gray-300 !leading-5">
                            {{ core()->formatBasePrice($invoice->base_tax_amount) }}
                        </p>

                        <!-- Discount -->
                        @if ($invoice->base_discount_amount > 0)
                            <p class="text-gray-600 dark:text-gray-300 !leading-5">
                                {{ core()->formatBasePrice($invoice->base_discount_amount) }}
                            </p>
                        @endif
                        
                        <!-- Grand Total -->
                        <p class="text-base text-gray-800 dark:text-white font-semibold !leading-5">
                            {{ core()->formatBasePrice($invoice->base_grand_total) }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right sub-component -->
        <div class="flex flex-col gap-2 w-[360px] max-w-full max-sm:w-full">
            <!-- component 1 -->
            <x-admin::accordion>
                <x-slot:header>
                    <p class="text-gray-600 dark:text-gray-300 text-base  p-2.5 font-semibold">
                        @lang('admin::app.sales.invoices.view.customer')
                    </p>
                </x-slot>

                <x-slot:content>
                    <div class="flex flex-col {{ $order->billing_address ? 'pb-4' : ''}}">
                        <p class="text-gray-800 font-semibold dark:text-white">
                            {{ $invoice->order->customer_full_name }}
                        </p>

                        {!! view_render_event('sales.invoice.customer_name.after', ['order' => $order]) !!}

                        <p class="text-gray-600 dark:text-gray-300">
                            @lang('admin::app.sales.invoices.view.customer-email', ['email' => $invoice->order->customer_email])
                        </p>

                        {!! view_render_event('sales.invoice.customer_email.after', ['order' => $order]) !!}
                    </div>

                    @if ($order->billing_address || $order->shipping_address)
                        <!-- Billing Address -->
                        @if ($order->billing_address)
                            <div class="{{ $order->shipping_address ? 'pb-4' : '' }}">
                                <span class="block w-full border-b dark:border-gray-800"></span>

                                <div class="flex items-center justify-between">
                                    <p class="text-gray-600 dark:text-gray-300 text-base  py-4 font-semibold">
                                        @lang('Billing Address')
                                    </p>
                                </div>

                                @include ('admin::sales.address', ['address' => $order->billing_address])

                                {!! view_render_event('sales.invoice.billing_address.after', ['order' => $order]) !!}
                            </div>
                        @endif

                        <!-- Shipping Address -->
                        @if ($order->shipping_address)
                            <span class="block w-full border-b dark:border-gray-800"></span>

                            <div class="flex items-center justify-between">
                                <p class="text-gray-600 dark:text-gray-300 text-base  py-4 font-semibold">
                                    @lang('Shipping Address')
                                </p>
                            </div>

                            @include ('admin::sales.address', ['address' => $order->shipping_address])

                            {!! view_render_event('sales.invoice.shipping_address.after', ['order' => $order]) !!}
                        @endif
                    @endif
                </x-slot>
            </x-admin::accordion> 
            
            <!-- component 2 -->
            <x-admin::accordion>
                <x-slot:header>
                    <p class="text-gray-600 dark:text-gray-300 text-base  p-2.5 font-semibold">
                        @lang('admin::app.sales.invoices.view.order-information') 
                    </p>
                </x-slot>

                <x-slot:content>
                    <div class="flex w-full gap-5 justify-start">
                        <div class="flex flex-col gap-y-1.5">
                            @foreach (['order-id', 'order-date', 'order-status', 'invoice-status', 'channel'] as $item)
                                <p class="text-gray-600 dark:text-gray-300">
                                    @lang('admin::app.sales.invoices.view.' . $item) 
                                </p>    
                            @endforeach
                        </div>

                        <div class="flex  flex-col gap-y-1.5">
                            <!-- Order Id -->
                            <p class="text-blue-600 font-semibold transition-all hover:underline">
                                <a href="{{ route('admin.sales.orders.view', $order->id) }}">#{{ $order->increment_id }}</a>
                            </p>

                            {!! view_render_event('sales.invoice.increment_id.after', ['order' => $order]) !!}

                            <!-- Order Date -->
                            <p class="text-gray-600 dark:text-gray-300">
                                {{ core()->formatDate($order->created_at) }}
                            </p>

                            {!! view_render_event('sales.invoice.created_at.after', ['order' => $order]) !!}

                            <!-- Order Status -->
                            <p class="text-gray-600 dark:text-gray-300">
                                {{ $order->status_label }}
                            </p>

                            {!! view_render_event('sales.invoice.status_label.after', ['order' => $order]) !!}

                            <!-- Invoice Status -->
                            <p class="text-gray-600 dark:text-gray-300">
                                {{ $invoice->status_label }}
                            </p>

                            <!-- Order Channel -->
                            <p class="text-gray-600 dark:text-gray-300">
                                {{ $order->channel_name }}
                            </p>

                            {!! view_render_event('sales.invoice.channel_name.after', ['order' => $order]) !!}
                        </div>
                    </div>
                </x-slot>
            </x-admin::accordion>
        </div>
    </div>
</x-admin::layouts>
