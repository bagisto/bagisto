<x-admin::layouts>
    <x-slot:title>
        @lang('admin::app.sales.invoices.view.title', ['invoice_id' => $invoice->increment_id ?? $invoice->id])
    </x-slot>

    @php
        $order = $invoice->order;
    @endphp

    <!-- Main Body -->
    <div class="grid">
        <div class="flex items-center justify-between gap-4 max-sm:flex-wrap">
            {!! view_render_event('bagisto.admin.sales.invoice.title.before', ['order' => $order]) !!}

            <p class="text-xl font-bold leading-6 text-gray-800 dark:text-white">
                @lang('admin::app.sales.invoices.view.title', ['invoice_id' => $invoice->increment_id ?? $invoice->id])

                <span class="label-active mx-1.5 text-sm">
                    {{ $invoice->status_label }}
                </span>
            </p>

            {!! view_render_event('bagisto.admin.sales.invoice.title.after', ['order' => $order]) !!}

            <div class="flex items-center gap-x-2.5">
                <!-- Back Button -->
                <a
                    href="{{ route('admin.sales.invoices.index') }}"
                    class="transparent-button hover:bg-gray-200 dark:text-white dark:hover:bg-gray-800"
                >
                    @lang('admin::app.account.edit.back-btn')
                </a>
            </div>
        </div>
    </div>

    <!-- Filter row -->
    <div class="mt-7 flex items-center justify-between gap-4 max-md:flex-wrap">
        <div class="flex flex-wrap items-center gap-x-1 gap-y-2">
            {!! view_render_event('bagisto.admin.sales.invoice.page_action.before', ['order' => $order]) !!}

            <a
                href="{{ route('admin.sales.invoices.print', $invoice->id) }}"
                class="inline-flex w-full max-w-max cursor-pointer items-center justify-between gap-x-2 px-1 py-1.5 text-center font-semibold text-gray-600 transition-all hover:rounded-md hover:bg-gray-200 dark:text-gray-300 dark:hover:bg-gray-800"
            >
                <span class="icon-printer text-2xl"></span>

                @lang('admin::app.sales.invoices.view.print')
            </a>

            <!-- Send Duplicate Invoice Modal -->
            <div>
                <button
                    type="button"
                    class="inline-flex w-full max-w-max cursor-pointer items-center justify-between gap-x-2 px-1 py-1.5 text-center font-semibold text-gray-600 transition-all hover:rounded-md hover:bg-gray-200 dark:text-gray-300 dark:hover:bg-gray-800"
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
                            <p class="text-lg font-bold text-gray-800 dark:text-white">
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
                            <!-- Save Button -->
                            <x-admin::button
                                button-type="button"
                                class="primary-button"
                                :title="trans('admin::app.sales.invoices.view.send')"
                            />
                        </x-slot>
                    </x-admin::modal>
                </x-admin::form>
            </div>

            {!! view_render_event('bagisto.admin.sales.invoice.page_action.after', ['order' => $order]) !!}

        </div>
    </div>

    <!-- body content -->
    <div class="mt-3.5 flex gap-2.5 max-xl:flex-wrap">
        <!-- Left sub-component -->
        <div class="flex flex-1 flex-col gap-2 max-xl:flex-auto">
            <!-- Invoice Item Section -->
            <div class="box-shadow rounded bg-white dark:bg-gray-900">
                <p class="mb-4 p-4 text-base font-semibold text-gray-800 dark:text-white">
                    @lang('admin::app.sales.invoices.view.invoice-items') ({{ count($invoice->items) }})
                </p>

                <div class="grid">
                    <!-- Invoice Item Details-->
                    @foreach($invoice->items as $item)
                        <div class="flex justify-between gap-2.5 border-b border-slate-300 px-4 py-6 dark:border-gray-800">
                            <div class="flex gap-2.5">
                                <!-- Product Image -->
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

                                <div class="grid place-content-start gap-1.5">
                                    <!-- Item Name -->
                                    <p class="break-all text-base font-semibold text-gray-800 dark:text-white">
                                        {{ $item->name }}
                                    </p>

                                    <p class="text-gray-600 dark:text-gray-300">
                                        @lang('admin::app.sales.invoices.view.amount-per-unit', [
                                            'amount' => core()->formatBasePrice($item->base_price),
                                            'qty'    => $item->qty,
                                            ])
                                    </p>

                                    <div class="flex flex-col place-items-start gap-1.5">
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

                            <div class="grid place-content-start gap-1">
                                <!-- Item Grand Total -->
                                <p class="flex items-center justify-end gap-x-1 text-base font-semibold text-gray-800 dark:text-white">
                                    {{ core()->formatBasePrice($item->base_total + $item->base_tax_amount - $item->base_discount_amount) }}
                                </p>

                                <!-- Item Base Price -->
                                <div class="flex flex-col place-items-start items-end gap-1.5">
                                    @if (core()->getConfigData('sales.taxes.sales.display_prices') == 'including_tax')
                                        <p class="text-gray-600 dark:text-gray-300">
                                            @lang('admin::app.sales.invoices.view.price', ['price' => core()->formatBasePrice($item->base_price_incl_tax)])
                                        </p>
                                    @elseif (core()->getConfigData('sales.taxes.sales.display_prices') == 'both')
                                        <p class="text-gray-600 dark:text-gray-300">
                                            @lang('admin::app.sales.invoices.view.price-excl-tax', ['price' => core()->formatBasePrice($item->base_price)])
                                        </p>
                                        
                                        <p class="text-gray-600 dark:text-gray-300">
                                            @lang('admin::app.sales.invoices.view.price-incl-tax', ['price' => core()->formatBasePrice($item->base_price_incl_tax)])
                                        </p>
                                    @else
                                        <p class="text-gray-600 dark:text-gray-300">
                                            @lang('admin::app.sales.invoices.view.price', ['price' => core()->formatBasePrice($item->base_price)])
                                        </p>
                                    @endif

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
                                    @if (core()->getConfigData('sales.taxes.sales.display_subtotal') == 'including_tax')
                                        <p class="text-gray-600 dark:text-gray-300">
                                            @lang('admin::app.sales.invoices.view.sub-total', ['sub_total' => core()->formatBasePrice($item->base_total_incl_tax)])
                                        </p>
                                    @elseif (core()->getConfigData('sales.taxes.sales.display_subtotal') == 'both')
                                        <p class="text-gray-600 dark:text-gray-300">
                                            @lang('admin::app.sales.invoices.view.sub-total-excl-tax', ['sub_total' => core()->formatBasePrice($item->base_total)])
                                        </p>
                                        
                                        <p class="text-gray-600 dark:text-gray-300">
                                            @lang('admin::app.sales.invoices.view.sub-total-incl-tax', ['sub_total' => core()->formatBasePrice($item->base_total_incl_tax)])
                                        </p>
                                    @else
                                        <p class="text-gray-600 dark:text-gray-300">
                                            @lang('admin::app.sales.invoices.view.sub-total', ['sub_total' => core()->formatBasePrice($item->base_total)])
                                        </p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!--Sale Summary -->
                <div class="mt-4 flex w-full justify-end gap-2.5 p-4">
                    <div class="flex flex-col gap-y-1.5">
                        @if (core()->getConfigData('sales.taxes.sales.display_subtotal') == 'both')
                            <p class="font-semibold !leading-5 text-gray-600 dark:text-gray-300">
                                @lang('admin::app.sales.invoices.view.sub-total-summary-excl-tax')
                            </p>
                            
                            <p class="font-semibold !leading-5 text-gray-600 dark:text-gray-300">
                                @lang('admin::app.sales.invoices.view.sub-total-summary-incl-tax')
                            </p>
                        @else
                            <p class="font-semibold !leading-5 text-gray-600 dark:text-gray-300">
                                @lang('admin::app.sales.invoices.view.sub-total-summary')
                            </p>
                        @endif

                        @if (core()->getConfigData('sales.taxes.sales.display_shipping_amount') == 'both')
                            <p class="!leading-5 text-gray-600 dark:text-gray-300">
                                @lang('admin::app.sales.invoices.view.shipping-and-handling-excl-tax')                    
                            </p>
                            
                            <p class="!leading-5 text-gray-600 dark:text-gray-300">
                                @lang('admin::app.sales.invoices.view.shipping-and-handling-incl-tax')                    
                            </p>
                        @else
                            <p class="!leading-5 text-gray-600 dark:text-gray-300">
                                @lang('admin::app.sales.invoices.view.shipping-and-handling')                    
                            </p>
                        @endif

                        <p class="!leading-5 text-gray-600 dark:text-gray-300">
                            @lang('admin::app.sales.invoices.view.summary-tax')
                        </p>
                        
                        @if ($invoice->base_discount_amount > 0)
                            <p class="!leading-5 text-gray-600 dark:text-gray-300">
                                @lang('admin::app.sales.invoices.view.summary-discount')
                            </p>
                        @endif

                        <p class="text-base font-semibold !leading-5 text-gray-800 dark:text-white">
                            @lang('admin::app.sales.invoices.view.grand-total')
                        </p>
                    </div>

                    <div class="flex flex-col gap-y-1.5">
                        <!-- Subtotal -->
                        @if (core()->getConfigData('sales.taxes.sales.display_subtotal') == 'including_tax')
                            <p class="font-semibold !leading-5 text-gray-600 dark:text-gray-300">
                                {{ core()->formatBasePrice($invoice->base_sub_total_incl_tax) }}
                            </p>
                        @elseif (core()->getConfigData('sales.taxes.sales.display_subtotal') == 'both')
                            <p class="font-semibold !leading-5 text-gray-600 dark:text-gray-300">
                                {{ core()->formatBasePrice($invoice->base_sub_total) }}
                            </p>
                            
                            <p class="font-semibold !leading-5 text-gray-600 dark:text-gray-300">
                                {{ core()->formatBasePrice($invoice->base_sub_total_incl_tax) }}
                            </p>
                        @else
                            <p class="font-semibold !leading-5 text-gray-600 dark:text-gray-300">
                                {{ core()->formatBasePrice($invoice->base_sub_total) }}
                            </p>
                        @endif

                        <!-- Shipping and Handling -->
                        @if (core()->getConfigData('sales.taxes.sales.display_shipping_amount') == 'including_tax')
                            <p class="!leading-5 text-gray-600 dark:text-gray-300">
                                {{ core()->formatBasePrice($invoice->base_shipping_amount_incl_tax) }}
                            </p>
                        @elseif (core()->getConfigData('sales.taxes.sales.display_shipping_amount') == 'both')
                            <p class="!leading-5 text-gray-600 dark:text-gray-300">
                                {{ core()->formatBasePrice($invoice->base_shipping_amount) }}
                            </p>
                            
                            <p class="!leading-5 text-gray-600 dark:text-gray-300">
                                {{ core()->formatBasePrice($invoice->base_shipping_amount_incl_tax) }}
                            </p>
                        @else
                            <p class="!leading-5 text-gray-600 dark:text-gray-300">
                                {{ core()->formatBasePrice($invoice->base_shipping_amount) }}
                            </p>
                        @endif

                        <!-- Tax -->
                        <p class="!leading-5 text-gray-600 dark:text-gray-300">
                            {{ core()->formatBasePrice($invoice->base_tax_amount) }}
                        </p>

                        <!-- Discount -->
                        @if ($invoice->base_discount_amount > 0)
                            <p class="!leading-5 text-gray-600 dark:text-gray-300">
                                {{ core()->formatBasePrice($invoice->base_discount_amount) }}
                            </p>
                        @endif

                        <!-- Grand Total -->
                        <p class="text-base font-semibold !leading-5 text-gray-800 dark:text-white">
                            {{ core()->formatBasePrice($invoice->base_grand_total) }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right sub-component -->
        <div class="flex w-[360px] max-w-full flex-col gap-2 max-sm:w-full">
            <!-- component 1 -->
            <x-admin::accordion>
                <x-slot:header>
                    <p class="p-2.5 text-base font-semibold text-gray-600 dark:text-gray-300">
                        @lang('admin::app.sales.invoices.view.customer')
                    </p>
                </x-slot>

                <x-slot:content>
                    <div class="flex flex-col {{ $order->billing_address ? 'pb-4' : ''}}">
                        <p class="font-semibold text-gray-800 dark:text-white">
                            {{ $invoice->order->customer_full_name }}
                        </p>

                        {!! view_render_event('bagisto.admin.sales.invoice.customer_name.after', ['order' => $order]) !!}

                        <p class="text-gray-600 dark:text-gray-300">
                            @lang('admin::app.sales.invoices.view.customer-email', ['email' => $invoice->order->customer_email])
                        </p>

                        {!! view_render_event('bagisto.admin.sales.invoice.customer_email.after', ['order' => $order]) !!}
                    </div>

                    @if ($order->billing_address || $order->shipping_address)
                        <!-- Billing Address -->
                        @if ($order->billing_address)
                            <div class="{{ $order->shipping_address ? 'pb-4' : '' }}">
                                <span class="block w-full border-b dark:border-gray-800"></span>

                                <div class="flex items-center justify-between">
                                    <p class="py-4 text-base font-semibold text-gray-600 dark:text-gray-300">
                                        @lang('Billing Address')
                                    </p>
                                </div>

                                @include ('admin::sales.address', ['address' => $order->billing_address])

                                {!! view_render_event('bagisto.admin.sales.invoice.billing_address.after', ['order' => $order]) !!}
                            </div>
                        @endif

                        <!-- Shipping Address -->
                        @if ($order->shipping_address)
                            <span class="block w-full border-b dark:border-gray-800"></span>

                            <div class="flex items-center justify-between">
                                <p class="py-4 text-base font-semibold text-gray-600 dark:text-gray-300">
                                    @lang('Shipping Address')
                                </p>
                            </div>

                            @include ('admin::sales.address', ['address' => $order->shipping_address])

                            {!! view_render_event('bagisto.admin.sales.invoice.shipping_address.after', ['order' => $order]) !!}
                        @endif
                    @endif
                </x-slot>
            </x-admin::accordion>

            <!-- component 2 -->
            <x-admin::accordion>
                <x-slot:header>
                    <p class="p-2.5 text-base font-semibold text-gray-600 dark:text-gray-300">
                        @lang('admin::app.sales.invoices.view.order-information')
                    </p>
                </x-slot>

                <x-slot:content>
                    <div class="flex w-full justify-start gap-5">
                        <div class="flex flex-col gap-y-1.5">
                            @foreach (['order-id', 'order-date', 'order-status', 'invoice-status', 'channel'] as $item)
                                <p class="text-gray-600 dark:text-gray-300">
                                    @lang('admin::app.sales.invoices.view.' . $item)
                                </p>
                            @endforeach
                        </div>

                        <div class="flex flex-col gap-y-1.5">
                            <!-- Order Id -->
                            <p class="font-semibold text-blue-600 transition-all hover:underline">
                                <a href="{{ route('admin.sales.orders.view', $order->id) }}">#{{ $order->increment_id }}</a>
                            </p>

                            {!! view_render_event('bagisto.admin.sales.invoice.increment_id.after', ['order' => $order]) !!}

                            <!-- Order Date -->
                            <p class="text-gray-600 dark:text-gray-300">
                                {{ core()->formatDate($order->created_at) }}
                            </p>

                            {!! view_render_event('bagisto.admin.sales.invoice.created_at.after', ['order' => $order]) !!}

                            <!-- Order Status -->
                            <p class="text-gray-600 dark:text-gray-300">
                                {{ $order->status_label }}
                            </p>

                            {!! view_render_event('bagisto.admin.sales.invoice.status_label.after', ['order' => $order]) !!}

                            <!-- Invoice Status -->
                            <p class="text-gray-600 dark:text-gray-300">
                                {{ $invoice->status_label }}
                            </p>

                            <!-- Order Channel -->
                            <p class="text-gray-600 dark:text-gray-300">
                                {{ $order->channel_name }}
                            </p>

                            {!! view_render_event('bagisto.admin.sales.invoice.channel_name.after', ['order' => $order]) !!}
                        </div>
                    </div>
                </x-slot>
            </x-admin::accordion>
        </div>
    </div>
</x-admin::layouts>
