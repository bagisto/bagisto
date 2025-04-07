<x-admin::layouts>
    <!-- Page Title -->
    <x-slot:title>
        @lang('admin::app.sales.orders.view.title', ['order_id' => $order->increment_id])
    </x-slot>

    <!-- Header -->
    <div class="grid">
        <div class="flex gap-4 justify-between items-center max-sm:flex-wrap">
            {!! view_render_event('bagisto.admin.sales.order.title.before', ['order' => $order]) !!}
            
            <div class="flex gap-2.5 items-center">
                <p class="text-xl text-gray-800 dark:text-white font-bold leading-6">
                    @lang('admin::app.sales.orders.view.title', ['order_id' => $order->increment_id])
                </p>

                <!-- Order Status -->
            <span class="label-{{ $order->status }} text-sm mx-1.5">
                    @lang("admin::app.sales.orders.view.$order->status")
                </span>
            </div>

            {!! view_render_event('bagisto.admin.sales.order.title.after', ['order' => $order]) !!} 

            <!-- Back Button -->
            <a
                href="{{ route('admin.sales.orders.index') }}"
                class="transparent-button hover:bg-gray-200 dark:hover:bg-gray-800 dark:text-white"
            >
                @lang('admin::app.account.edit.back-btn')
            </a>
        </div>
    </div>

    <div class="justify-between gap-x-1 gap-y-2 items-center flex-wrap mt-5">
        <div class="flex gap-1.5">
            {!! view_render_event('bagisto.admin.sales.order.page_action.before', ['order' => $order]) !!}

            @if (
                $order->canCancel()
                && bouncer()->hasPermission('sales.orders.cancel')
            )
               <form
                    method="POST"
                    ref="cancelOrderForm"
                    action="{{ route('admin.sales.orders.cancel', $order->id) }}"
                >
                    @csrf
                </form>

                <div 
                    class="inline-flex gap-x-2 items-center justify-between w-full max-w-max px-1 py-1.5 text-gray-600 dark:text-gray-300 font-semibold text-center cursor-pointer transition-all hover:bg-gray-200 dark:hover:bg-gray-800 hover:rounded-md"
                    @click="$emitter.emit('open-confirm-modal', {
                        message: '@lang('admin::app.sales.orders.view.cancel-msg')',
                        agree: () => {
                            this.$refs['cancelOrderForm'].submit()
                        }
                    })"
                >
                    <span
                        class="icon-cancel text-2xl"
                        role="presentation"
                        tabindex="0"
                    >
                    </span>

                    <a
                        href="javascript:void(0);"
                    >
                        @lang('admin::app.sales.orders.view.cancel')    
                    </a>
                </div>
            @endif

            @if (
                $order->canInvoice()
                && bouncer()->hasPermission('sales.invoices.create')
                && $order->payment->method !== 'paypal_standard'
            )
                @include('admin::sales.invoices.create')
            @endif

            @if (
                $order->canShip()
                && bouncer()->hasPermission('sales.shipments.create')
            )
                @include('admin::sales.shipments.create')
            @endif

            @if (
                $order->canRefund()
                && bouncer()->hasPermission('sales.refunds.create')
            )
                @include('admin::sales.refunds.create')
            @endif

            {!! view_render_event('bagisto.admin.sales.order.page_action.after', ['order' => $order]) !!}
        </div>

        <!-- Order details -->
        <div class="flex gap-2.5 mt-3.5 max-xl:flex-wrap">
            <!-- Left Component -->
            <div class="flex flex-col gap-2 flex-1 max-xl:flex-auto">
                {!! view_render_event('bagisto.admin.sales.order.left_component.before', ['order' => $order]) !!}

                <div class="bg-white dark:bg-gray-900 rounded box-shadow">
                    <div class="flex justify-between p-4">
                        <p class="mb-4 text-base text-gray-800 dark:text-white font-semibold">
                            @lang('Order Items') ({{ count($order->items) }})
                        </p>

                        <p class="text-base text-gray-800 dark:text-white font-semibold">
                            @lang('admin::app.sales.orders.view.grand-total', ['grand_total' => core()->formatBasePrice($order->grand_total)])
                        </p>
                    </div>

                    <!-- Order items -->
                    <div class="grid">
                        @foreach ($order->items as $item)
                            {!! view_render_event('bagisto.admin.sales.order.list.before', ['order' => $order]) !!}

                            <div class="flex gap-2.5 justify-between px-4 py-6 border-b border-slate-300 dark:border-gray-800">
                                <div class="flex gap-2.5">
                                    @if($item?->product?->base_image_url)
                                        <img
                                            class="w-full h-[60px] max-w-[60px] max-h-[60px] relative rounded"
                                            src="{{ $item?->product->base_image_url }}"
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
                                            <p class="text-gray-600 dark:text-gray-300">
                                                @lang('admin::app.sales.orders.view.amount-per-unit', [
                                                    'amount' => core()->formatBasePrice($item->base_price),
                                                    'qty'    => $item->qty_ordered,
                                                ])
                                            </p>

                                            @if (isset($item->additional['attributes']))
                                                <p class="text-gray-600 dark:text-gray-300">
                                                    @foreach ($item->additional['attributes'] as $attribute)
                                                        {{ $attribute['attribute_name'] }} : {{ $attribute['option_label'] }}
                                                    @endforeach
                                                </p>
                                            @endif

                                            <p class="text-gray-600 dark:text-gray-300">
                                                @lang('admin::app.sales.orders.view.sku', ['sku' => $item->sku])
                                            </p>

                                            <p class="text-gray-600 dark:text-gray-300">
                                                {{ $item->qty_ordered ? trans('admin::app.sales.orders.view.item-ordered', ['qty_ordered' => $item->qty_ordered]) : '' }}

                                                {{ $item->qty_invoiced ? trans('admin::app.sales.orders.view.item-invoice', ['qty_invoiced' => $item->qty_invoiced]) : '' }}

                                                {{ $item->qty_shipped ? trans('admin::app.sales.orders.view.item-shipped', ['qty_shipped' => $item->qty_shipped]) : '' }}

                                                {{ $item->qty_refunded ? trans('admin::app.sales.orders.view.item-refunded', ['qty_refunded' => $item->qty_refunded]) : '' }}

                                                {{ $item->qty_canceled ? trans('admin::app.sales.orders.view.item-canceled', ['qty_canceled' => $item->qty_canceled]) : '' }}
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <div class="grid gap-1 place-content-start">
                                    <div class="">
                                        <p class="flex items-center gap-x-1 justify-end text-base text-gray-800 dark:text-white font-semibold">
                                            {{ core()->formatBasePrice($item->base_total + $item->base_tax_amount - $item->base_discount_amount) }}
                                        </p>
                                    </div>

                                    <div class="flex flex-col gap-1.5 items-end place-items-start">
                                        <p class="text-gray-600 dark:text-gray-300">
                                            @lang('admin::app.sales.orders.view.price', ['price' => core()->formatBasePrice($item->base_price)])
                                        </p>

                                        <p class="text-gray-600 dark:text-gray-300">
                                            {{ $item->tax_percent }}%
                                            @lang('admin::app.sales.orders.view.tax', ['tax' => core()->formatBasePrice($item->base_tax_amount)])
                                        </p>

                                        @if ($order->base_discount_amount > 0)
                                            <p class="text-gray-600 dark:text-gray-300">
                                                @lang('admin::app.sales.orders.view.discount', ['discount' => core()->formatBasePrice($item->base_grand_tol)])
                                            </p>
                                        @endif

                                        <p class="text-gray-600 dark:text-gray-300">
                                            @lang('admin::app.sales.orders.view.sub-total', ['sub_total' => core()->formatBasePrice($item->base_total)])
                                        </p>
                                    </div>
                                </div>
                            </div>

                            {!! view_render_event('bagisto.admin.sales.order.list.after', ['order' => $order]) !!}
                        @endforeach
                    </div>

                    <div class="flex w-full gap-2.5 justify-end mt-4 p-4">
                        <div class="flex flex-col gap-y-1.5">
                            <p class="text-gray-600 dark:text-gray-300 font-semibold !leading-5">
                                @lang('admin::app.sales.orders.view.summary-sub-total')
                            </p>

                            <p class="text-gray-600 dark:text-gray-300 !leading-5">
                                @lang('admin::app.sales.orders.view.summary-tax')
                            </p>

                            @if ($haveStockableItems = $order->haveStockableItems())
                                <p class="text-gray-600 dark:text-gray-300 !leading-5">
                                    @lang('admin::app.sales.orders.view.shipping-and-handling')
                                </p>
                            @endif

                            @if ($order->giftcard_amount > 0)
                                <p class="text-gray-600 dark:text-gray-300 !leading-5 text-sm">
                                    @lang('giftcard::app.giftcard.giftcard_amount')
                                </p>
                            @endif

                            @if (!empty($order->giftcard_number))
                                <p class="text-gray-600 dark:text-black-300 !leading-5 text-sm">
                                    @lang('giftcard::app.giftcard.giftcard_number')
                                </p>
                            @endif

                            <p class="text-base text-gray-800 dark:text-white font-semibold !leading-5">
                                @lang('admin::app.sales.orders.view.summary-grand-total')
                            </p>

                            <p class="text-gray-600 dark:text-gray-300 !leading-5">
                                @lang('admin::app.sales.orders.view.total-paid')
                            </p>

                            <p class="text-gray-600 dark:text-gray-300 !leading-5">
                                 @lang('admin::app.sales.orders.view.total-refund')
                            </p>

                            <p class="text-gray-600 dark:text-gray-300 !leading-5">
                                @lang('admin::app.sales.orders.view.total-due')
                            </p>
                        </div>

                        <div class="flex  flex-col gap-y-1.5">
                            <p class="text-gray-600 dark:text-gray-300 font-semibold !leading-5">
                                {{ core()->formatBasePrice($order->base_sub_total) }}
                            </p>

                            <p class="text-gray-600 dark:text-gray-300 !leading-5">
                                {{ core()->formatBasePrice($order->base_tax_amount) }}
                            </p>

                            @if ($haveStockableItems)
                                <p class="text-gray-600 dark:text-gray-300 !leading-5">
                                    {{ core()->formatBasePrice($order->base_shipping_amount) }}
                                </p>
                            @endif

                            @if ($order->giftcard_amount > 0)
                                <p class="text-gray-600 dark:text-gray-300 !leading-5">
                                -  {{ core()->formatBasePrice($order->giftcard_amount) }}
                                </p>
                            @endif

                            @if (!empty($order->giftcard_number))
                                <p class="text-gray-600 dark:text-gray-300 !leading-5">
                                    {{ ($order->giftcard_number) }}
                                </p>
                            @endif

                            <p class="text-base text-gray-800 dark:text-white font-semibold !leading-5">
                                {{ core()->formatBasePrice($order->base_grand_total) }}
                            </p>

                            <p class="text-gray-600 dark:text-gray-300 !leading-5">
                                {{ core()->formatBasePrice($order->base_grand_total_invoiced) }}
                            </p>
                            
                            <p class="text-gray-600 dark:text-gray-300 !leading-5">
                                {{ core()->formatBasePrice($order->base_grand_total_refunded) }}
                            </p>

                            @if($order->status !== 'canceled')
                                <p class="text-gray-600 dark:text-gray-300 !leading-5">
                                    {{ core()->formatBasePrice($order->base_total_due) }}
                                </p>
                            @else
                                <p class="text-gray-600 dark:text-gray-300 !leading-5">
                                    {{ core()->formatBasePrice(0.00) }}
                                </p>
                            @endif
                        </div>
                    </div>
                </div>
                
                <!-- Customer's comment form -->
                <div class="bg-white dark:bg-gray-900 rounded box-shadow">
                    <p class="p-4 pb-0 text-base text-gray-800 dark:text-white font-semibold">
                        @lang('admin::app.sales.orders.view.comments')
                    </p>

                    <x-admin::form action="{{ route('admin.sales.orders.comment', $order->id) }}">
                        <div class="p-4">
                            <div class="mb-2.5">
                                <x-admin::form.control-group>
                                    <x-admin::form.control-group.control
                                        type="textarea"
                                        id="comment"
                                        name="comment" 
                                        rules="required"
                                        :label="trans('admin::app.sales.orders.view.comments')"
                                        :placeholder="trans('admin::app.sales.orders.view.write-your-comment')"
                                        rows="3"
                                    />

                                    <x-admin::form.control-group.error control-name="comment" />
                                </x-admin::form.control-group>
                            </div>

                            <div class="flex justify-between items-center">
                                <label
                                    class="flex gap-1 w-max items-center p-1.5 cursor-pointer select-none"
                                    for="customer_notified"
                                >
                                    <input
                                        type="checkbox"
                                        name="customer_notified"
                                        id="customer_notified"
                                        value="1"
                                        class="hidden peer"
                                    >

                                    <span
                                        class="icon-uncheckbox rounded-md text-2xl cursor-pointer peer-checked:icon-checked peer-checked:text-blue-600"
                                        role="button"
                                        tabindex="0"
                                    >
                                    </span>
                        
                                    <p class="flex gap-x-1 items-center cursor-pointer text-gray-600 dark:text-gray-300 hover:text-gray-800 dark:hover:text-gray-100 font-semibold">
                                        @lang('admin::app.sales.orders.view.notify-customer')
                                    </p>
                                </label>
                                
                                <button
                                    type="submit"
                                    class="secondary-button"
                                    aria-label="{{ trans('admin::app.sales.orders.view.submit-comment') }}"
                                >
                                    @lang('admin::app.sales.orders.view.submit-comment')
                                </button>
                            </div>
                        </div>
                    </x-admin::form> 

                    <span class="block w-full border-b dark:border-gray-800"></span>

                    <!-- Comment List -->
                    @foreach ($order->comments()->orderBy('id', 'desc')->get() as $comment)
                        <div class="grid gap-1.5 p-4">
                            <p class="text-base text-gray-800 dark:text-white leading-6">
                                {{ $comment->comment }}
                            </p>

                            <!-- Notes List Title and Time -->
                            <p class="flex gap-2 text-gray-600 dark:text-gray-300 items-center">  
                                @if ($comment->customer_notified)
                                    <span class="h-fit text-2xl rounded-full icon-done text-blue-600 bg-blue-100"></span> 

                                    @lang('admin::app.sales.orders.view.customer-notified', ['date' => core()->formatDate($comment->created_at, 'Y-m-d H:i:s a')])
                                @else
                                    <span class="h-fit text-2xl rounded-full icon-cancel-1 text-red-600 bg-red-100"></span>

                                    @lang('admin::app.sales.orders.view.customer-not-notified', ['date' => core()->formatDate($comment->created_at, 'Y-m-d H:i:s a')])
                                @endif
                            </p>
                        </div>

                        <span class="block w-full border-b dark:border-gray-800"></span>
                    @endforeach
                </div>

                {!! view_render_event('bagisto.admin.sales.order.left_component.after', ['order' => $order]) !!}
            </div>

            <!-- Right Component -->
            <div class="flex flex-col gap-2 w-[360px] max-w-full max-sm:w-full">
                {!! view_render_event('bagisto.admin.sales.order.right_component.before', ['order' => $order]) !!}

                <!-- Customer and address information -->
                <x-admin::accordion>
                    <x-slot:header>
                        <p class="text-gray-600 dark:text-gray-300 text-base  p-2.5 font-semibold">
                            @lang('admin::app.sales.orders.view.customer')
                        </p>
                    </x-slot>

                    <x-slot:content>
                        <div class="{{ $order->billing_address ? 'pb-4' : '' }}">
                            <div class="flex flex-col gap-1.5">
                                <p class="text-gray-800 font-semibold dark:text-white">
                                    {{ $order->customer_full_name }}
                                </p>

                                {!! view_render_event('bagisto.admin.sales.order.customer_full_name.after', ['order' => $order]) !!}

                                <p class="text-gray-600 dark:text-gray-300">
                                    {{ $order->customer_email }}
                                </p>

                                {!! view_render_event('bagisto.admin.sales.order.customer_email.after', ['order' => $order]) !!}

                                <p class="text-gray-600 dark:text-gray-300">
                                    @lang('admin::app.sales.orders.view.customer-group') : {{ $order->is_guest ? core()->getGuestCustomerGroup()?->name : ($order->customer->group->name ?? '') }}
                                </p>

                                {!! view_render_event('bagisto.admin.sales.order.customer_group.after', ['order' => $order]) !!}
                            </div>
                        </div>
                        
                        <!-- Billing Address -->
                        @if ($order->billing_address)
                            <span class="block w-full border-b dark:border-gray-800"></span>

                            <div class="{{ $order->shipping_address ? 'pb-4' : '' }}">

                                <div class="flex items-center justify-between">
                                    <p class="text-gray-600 dark:text-gray-300  text-base  py-4 font-semibold">
                                        @lang('admin::app.sales.orders.view.billing-address')
                                    </p>
                                </div>

                                @include ('admin::sales.address', ['address' => $order->billing_address])

                                {!! view_render_event('bagisto.admin.sales.order.billing_address.after', ['order' => $order]) !!}
                            </div>
                        @endif

                        <!-- Shipping Address -->
                        @if ($order->shipping_address)
                            <span class="block w-full border-b dark:border-gray-800"></span>

                            <div class="flex items-center justify-between">
                                <p class="text-gray-600 dark:text-gray-300 text-base  py-4 font-semibold">
                                    @lang('admin::app.sales.orders.view.shipping-address')
                                </p>
                            </div>

                            @include ('admin::sales.address', ['address' => $order->shipping_address])

                            {!! view_render_event('bagisto.admin.sales.order.shipping_address.after', ['order' => $order]) !!}
                        @endif
                    </x-slot>
                </x-admin::accordion> 

                <!-- Order Information -->
                <x-admin::accordion>
                    <x-slot:header>
                        <p class="text-gray-600 dark:text-gray-300 text-base  p-2.5 font-semibold">
                            @lang('admin::app.sales.orders.view.order-information')
                        </p>
                    </x-slot>

                    <x-slot:content>
                        <div class="flex w-full gap-5 justify-start">
                            <div class="flex flex-col gap-y-1.5">
                                <p class="text-gray-600 dark:text-gray-300">
                                    @lang('admin::app.sales.orders.view.order-date')
                                </p>

                                <p class="text-gray-600 dark:text-gray-300">
                                    @lang('admin::app.sales.orders.view.order-status')
                                </p>

                                <p class="text-gray-600 dark:text-gray-300">
                                    @lang('admin::app.sales.orders.view.channel')
                                </p>
                            </div>
                    
                            <div class="flex flex-col gap-y-1.5">
                                {!! view_render_event('bagisto.admin.sales.order.created_at.before', ['order' => $order]) !!}

                                <!-- Order Date -->
                                <p class="text-gray-600 dark:text-gray-300">
                                    {{core()->formatDate($order->created_at) }}
                                </p>

                                {!! view_render_event('bagisto.admin.sales.order.created_at.after', ['order' => $order]) !!}
                            
                                <!-- Order Status -->
                                <p class="text-gray-600 dark:text-gray-300">
                                    {{$order->status_label}}
                                </p>
                            
                                {!! view_render_event('bagisto.admin.sales.order.created_at.after', ['order' => $order]) !!}

                                <!-- Order Channel -->
                                <p class="text-gray-600 dark:text-gray-300">
                                    {{$order->channel_name}}
                                </p>

                                {!! view_render_event('bagisto.admin.sales.order.channel_name.after', ['order' => $order]) !!}
                            </div>
                        </div>
                    </x-slot>
                </x-admin::accordion> 

                <!-- Payment and Shipping Information-->
                <x-admin::accordion>
                    <x-slot:header>
                        <p class="text-gray-600 dark:text-gray-300 text-base  p-2.5 font-semibold">
                            @lang('admin::app.sales.orders.view.payment-and-shipping')
                        </p>
                    </x-slot>

                    <x-slot:content>
                        <div>
                            <!-- Payment method -->
                            <p class="text-gray-800 font-semibold dark:text-white">
                                {{ core()->getConfigData('sales.payment_methods.' . $order->payment->method . '.title') }}
                            </p>

                            <p class="text-gray-600 dark:text-gray-300">
                                @lang('admin::app.sales.orders.view.payment-method')
                            </p>

                            <!-- Currency -->
                            <p class="pt-4 text-gray-800 dark:text-white font-semibold">
                                {{ $order->order_currency_code }}
                            </p>

                            <p class="text-gray-600 dark:text-gray-300">
                                @lang('admin::app.sales.orders.view.currency')
                            </p>

                            @php $additionalDetails = \Webkul\Payment\Payment::getAdditionalDetails($order->payment->method); @endphp

                            <!-- Addtional details -->
                            @if (! empty($additionalDetails))
                                <p class="pt-4 text-gray-800 dark:text-white font-semibold">
                                    {{ $additionalDetails['title'] }}
                                </p>

                                <p class="text-gray-600 dark:text-gray-300">
                                    {{ $additionalDetails['value'] }}
                                </p>
                            @endif

                            {!! view_render_event('bagisto.admin.sales.order.payment-method.after', ['order' => $order]) !!}
                        </div>

                        <!-- Shipping Method and Price Details -->
                        @if ($order->shipping_address)
                            <span class="block w-full mt-4 border-b dark:border-gray-800"></span>

                            <div class="pt-4">
                                <p class="text-gray-800 font-semibold dark:text-white">
                                    {{ $order->shipping_title }}
                                </p>

                                <p class="text-gray-600 dark:text-gray-300">
                                    @lang('admin::app.sales.orders.view.shipping-method')
                                </p>

                                <p class="pt-4 text-gray-800 dark:text-white font-semibold">
                                    {{ core()->formatBasePrice($order->base_shipping_amount) }}
                                </p>

                                <p class="text-gray-600 dark:text-gray-300">
                                    @lang('admin::app.sales.orders.view.shipping-price')
                                </p>
                            </div>

                            {!! view_render_event('bagisto.admin.sales.order.shipping-method.after', ['order' => $order]) !!}
                        @endif
                    </x-slot>
                </x-admin::accordion> 

                <!-- Invoice Information-->
                <x-admin::accordion>
                    <x-slot:header>
                        <p class="text-gray-600 dark:text-gray-300 text-base  p-2.5 font-semibold">
                            @lang('admin::app.sales.orders.view.invoices') ({{ count($order->invoices) }})
                        </p>
                    </x-slot>

                    <x-slot:content>
                        @forelse ($order->invoices as $index => $invoice)
                            <div class="grid gap-y-2.5">
                                <div>
                                    <p class="text-gray-800 font-semibold dark:text-white">
                                        @lang('admin::app.sales.orders.view.invoice-id', ['invoice' => $invoice->increment_id ?? $invoice->id])
                                    </p>

                                    <p class="text-gray-600 dark:text-gray-300">
                                        {{ core()->formatDate($invoice->created_at, 'd M, Y H:i:s a') }}
                                    </p>
                                </div>

                                <div class="flex gap-2.5">
                                    <a
                                        href="{{ route('admin.sales.invoices.view', $invoice->id) }}"
                                        class="text-sm text-blue-600 transition-all hover:underline"
                                    >
                                        @lang('admin::app.sales.orders.view.view')
                                    </a>

                                    <a
                                        href="{{ route('admin.sales.invoices.print', $invoice->id) }}"
                                        class="text-sm text-blue-600 transition-all hover:underline"
                                    >
                                        @lang('admin::app.sales.orders.view.download-pdf')
                                    </a>
                                </div>
                            </div>

                            @if ($index < count($order->invoices) - 1)
                                <span class="block w-full mb-4 mt-4 border-b dark:border-gray-800"></span>
                            @endif
                        @empty 
                            <p class="text-gray-600 dark:text-gray-300">
                                @lang('admin::app.sales.orders.view.no-invoice-found')
                            </p>
                        @endforelse
                    </x-slot>
                </x-admin::accordion> 

                <!-- Shipment Information-->
                <x-admin::accordion>
                    <x-slot:header>
                        <p class="text-gray-600 dark:text-gray-300 text-base  p-2.5 font-semibold">
                            @lang('admin::app.sales.orders.view.shipments') ({{ count($order->shipments) }})
                        </p>
                    </x-slot>

                    <x-slot:content>
                        @forelse ($order->shipments as $shipment)
                            <div class="grid gap-y-2.5">
                                <div>
                                    <!-- Shipment Id -->
                                    <p class="text-gray-800 font-semibold dark:text-white">
                                        @lang('admin::app.sales.orders.view.shipment', ['shipment' => $shipment->id])
                                    </p>

                                    <!-- Shipment Created -->
                                    <p class="text-gray-600 dark:text-gray-300">
                                        {{ core()->formatDate($shipment->created_at, 'd M, Y H:i:s a') }}
                                    </p>
                                </div>

                                <div class="flex gap-2.5">
                                    <a
                                        href="{{ route('admin.sales.shipments.view', $shipment->id) }}"
                                        class="text-sm text-blue-600 transition-all hover:underline"
                                    >
                                        @lang('admin::app.sales.orders.view.view')
                                    </a>
                                </div>
                            </div>
                        @empty 
                            <p class="text-gray-600 dark:text-gray-300">
                                @lang('admin::app.sales.orders.view.no-shipment-found')
                            </p>
                        @endforelse
                    </x-slot>
                </x-admin::accordion> 

                <!-- Refund Information-->
                <x-admin::accordion>
                    <x-slot:header>
                        <p class="text-gray-600 dark:text-gray-300 text-base  p-2.5 font-semibold">
                            @lang('admin::app.sales.orders.view.refund')
                        </p>
                    </x-slot>

                    <x-slot:content>
                        @forelse ($order->refunds as $refund)
                            <div class="grid gap-y-2.5">
                                <div>
                                    <p class="text-gray-800 font-semibold dark:text-white">
                                        @lang('admin::app.sales.orders.view.refund-id', ['refund' => $refund->id])
                                    </p>

                                    <p class="text-gray-600 dark:text-gray-300">
                                        {{ core()->formatDate($refund->created_at, 'd M, Y H:i:s a') }}
                                    </p>

                                    <p class="mt-4 text-gray-800 dark:text-white font-semibold">
                                        @lang('admin::app.sales.orders.view.name')
                                    </p>

                                    <p class="text-gray-600 dark:text-gray-300">
                                        {{ $refund->order->customer_full_name }}
                                    </p>

                                    <p class="mt-4 text-gray-800 dark:text-white font-semibold">
                                        @lang('admin::app.sales.orders.view.status')
                                    </p>

                                    <p class="text-gray-600 dark:text-gray-300">
                                        @lang('admin::app.sales.orders.view.refunded') 
                                        
                                        <span class="text-gray-800 font-semibold dark:text-white">
                                            {{ core()->formatBasePrice($refund->base_grand_total_refunded) }}
                                        </span>
                                    </p>
                                </div>

                                <div class="flex gap-2.5">
                                    <a
                                        href="{{ route('admin.sales.refunds.view', $refund->id) }}"
                                        class="text-sm text-blue-600 transition-all hover:underline"
                                    >
                                        @lang('admin::app.sales.orders.view.view')
                                    </a>
                                </div>
                            </div>
                        @empty 
                            <p class="text-gray-600 dark:text-gray-300">
                                @lang('admin::app.sales.orders.view.no-refund-found')
                            </p>
                        @endforelse
                    </x-slot>
                </x-admin::accordion>
                
                {!! view_render_event('bagisto.admin.sales.order.right_component.after', ['order' => $order]) !!}
            </div>
        </div>
    </div>
</x-admin::layouts>