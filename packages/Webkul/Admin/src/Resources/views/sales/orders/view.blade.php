<x-admin::layouts>
    <x-slot:title>
        @lang('admin::app.sales.orders.view.title', ['order_id' => $order->increment_id])
    </x-slot:title>

    {{-- Header --}}
    <div class="grid">
        <div class="flex gap-[16px] justify-between items-center max-sm:flex-wrap">
            {!! view_render_event('sales.order.title.before', ['order' => $order]) !!}

            <p class="text-[20px] text-gray-800 font-bold leading-[24px]">
                @lang('admin::app.sales.orders.view.title', ['order_id' => $order->increment_id])

                @switch($order->status)
                    @case('processing')
                        <span class="label-processing text-[14px] mx-[5px]">{{ $order->status }}</span>

                        @break
                    @case('completed')
                        <span class="label-closed text-[14px] mx-[5px]">{{ $order->status }}</span>

                        @break
                    @case('pending')
                        <span class="label-pending text-[14px] mx-[5px]">{{ $order->status }}</span>

                        @break
                @endswitch
            </p>
            
            {!! view_render_event('sales.order.title.after', ['order' => $order]) !!}
        </div>
    </div>

    <div class="flex justify-between gap-x-[4px] gap-y-[8px] items-center flex-wrap mt-[28px]">
        <div>
            {!! view_render_event('sales.order.page_action.before', ['order' => $order]) !!}

            @if (
                $order->canCancel()
                && bouncer()->hasPermission('sales.orders.cancel')
            )
                <div class="inline-flex gap-x-[8px] items-center justify-between w-full max-w-max px-[4px] py-[6px] text-gray-600 font-semibold text-center cursor-pointer transition-all hover:bg-gray-200 hover:rounded-[6px]">
                    <span class="icon-cancel text-[24px]"></span>

                    <a
                        href="{{ route('admin.sales.orders.cancel', $order->id) }}"
                        onclick="return confirm('@lang('admin::app.sales.orders.view.cancel-msg')')"
                    >
                        @lang('admin::app.sales.orders.view.cancel')    
                    </a>
                </div>
            @endif

            @if (
                $order->canInvoice()
                && $order->payment->method !== 'paypal_standard'
            )
                <div class="inline-flex gap-x-[8px] items-center justify-between w-full max-w-max px-[4px] py-[6px] text-gray-600 font-semibold text-center cursor-pointer transition-all hover:bg-gray-200 hover:rounded-[6px]">
                    <span class="icon-mail text-[24px]"></span> 

                    <a
                        href="{{ route('admin.sales.invoices.create', $order->id) }}"
                    >
                        @lang('admin::app.sales.orders.view.invoice')     
                    </a>
                </div>
            @endif

            @if ($order->canShip())
                <v-create-shipment>
                    <div
                        class="inline-flex gap-x-[8px] items-center justify-between w-full max-w-max px-[4px] py-[6px] text-gray-600 font-semibold text-center cursor-pointer transition-all hover:bg-gray-200 hover:rounded-[6px]"
                    >
                        <span class="icon-ship text-[24px]"></span> 

                        @lang('admin::app.sales.orders.view.ship')     
                    </div>
                </v-create-shipment>
            @endif

            @if ($order->canRefund())
                <div class="inline-flex gap-x-[8px] items-center justify-between w-full max-w-max px-[4px] py-[6px] text-gray-600 font-semibold text-center cursor-pointer transition-all hover:bg-gray-200 hover:rounded-[6px]">
                    <span class="icon-cancel text-[24px]"></span> 

                    <a
                        href="{{ route('admin.sales.refunds.create', $order->id) }}"
                    >
                        @lang('admin::app.sales.orders.view.refund')     
                    </a>
                </div>
            @endif

            {!! view_render_event('sales.order.page_action.after', ['order' => $order]) !!}
        </div>
    </div>

    {{-- Order details --}}
    <div class="flex gap-[10px] mt-[14px] max-xl:flex-wrap">
        <div class="flex flex-col gap-[8px] flex-1 max-xl:flex-auto">
            <div class="p-[16px] bg-white rounded-[4px] box-shadow">
                <div class="flex justify-between">
                    <p class="text-[16px] text-gray-800 font-semibold mb-[16px]">@lang('Order Items') ({{ count($order->items) }})</p>

                    <p class="text-[16px] text-gray-800 font-semibold">
                        @lang('admin::app.sales.orders.view.grand-total') - {{ core()->formatBasePrice($order->base_grand_total) }}
                    </p>
                </div>

                {{-- Order items --}}
                <div class="grid">
                    @foreach ($order->items as $item)
                        <div class="flex gap-[10px] justify-between px-[16px] py-[24px] border-b-[1px] border-slate-300">
                            <div class="flex gap-[10px]">
                                @if($item->product)
                                    <div class="grid gap-[4px] content-center justify-items-center min-w-[60px] h-[60px] px-[6px] border border-dashed border-gray-300 rounded-[4px]">
                                        <img
                                            class="w-[20px]"
                                            src="{{ $item->product->base_image_url }}"
                                        >
                                    </div>
                                @endif

                                <div class="grid gap-[6px] place-content-start">
                                    <p class="text-[16x] text-gray-800 font-semibold">{{ $item->name }}</p>

                                    <div class="flex flex-col gap-[6px] place-items-start">
                                        <p class="text-gray-600">
                                            {{ core()->formatBasePrice($item->base_price) }} 

                                            @lang('admin::app.sales.orders.view.per-unit') x {{ $item->qty_ordered }} @lang('admin::app.sales.orders.view.quantity')
                                        </p>

                                        @if (isset($item->additional['attributes']))
                                            <p class="text-gray-600">
                                                @foreach ($item->additional['attributes'] as $attribute)
                                                    {{ $attribute['attribute_name'] }} : {{ $attribute['option_label'] }}
                                                @endforeach
                                            </p>
                                        @endif

                                        <p class="text-gray-600">@lang('admin::app.sales.orders.view.sku')  - {{ $item->sku }}</p>

                                        <p class="text-gray-600">
                                            @lang('admin::app.sales.orders.view.ordered') {{ $item->qty_ordered }},

                                            @lang('admin::app.sales.orders.view.invoiced') {{ $item->qty_invoiced }},

                                            @lang('admin::app.sales.orders.view.shipped') {{ $item->qty_shipped }}
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div class="grid gap-[4px] place-content-start">
                                <div class="">
                                    <p class="flex items-center gap-x-[4px] justify-end text-[16px] text-gray-800 font-semibold">
                                        {{ core()->formatBasePrice($item->base_total + $item->base_tax_amount - $item->base_discount_amount) }}

                                        <span class="icon-sort-up text-[24px] p-[6px] rounded-[6px] cursor-pointer transition-all hover:bg-gray-100"></span>
                                    </p>
                                </div>

                                <div class="flex flex-col gap-[6px] items-end place-items-start">
                                    <p class="text-gray-600">
                                        @lang('admin::app.sales.orders.view.price') - {{ core()->formatBasePrice($item->base_price) }}
                                    </p>

                                    <p class="text-gray-600">
                                        {{ $item->tax_percent }}% 
                                        @lang('admin::app.sales.orders.view.tax') - {{ core()->formatBasePrice($item->base_tax_amount) }}
                                    </p>

                                    @if (! $order->base_discount_amount)
                                        <p class="text-gray-600">
                                            @lang('admin::app.sales.orders.view.discount') - {{ core()->formatBasePrice($item->base_discount_amount) }}
                                        </p>
                                    @endif

                                    <p class="text-gray-600">
                                        @lang('admin::app.sales.orders.view.sub-total') - {{ core()->formatBasePrice($item->base_total) }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="flex w-full gap-[10px] justify-end mt-[16px]">
                    <div class="flex flex-col gap-y-[6px]">
                        <p class="text-gray-600 font-semibold">@lang('admin::app.sales.orders.view.sub-total')</p>
                        <p class="text-gray-600">@lang('admin::app.sales.orders.view.tax')</p>

                        @if ($haveStockableItems = $order->haveStockableItems())
                            <p class="text-gray-600">@lang('admin::app.sales.orders.view.shipping-and-handling')</p>
                        @endif

                        <p class="text-[16px] text-gray-800 font-semibold">@lang('admin::app.sales.orders.view.grand-total')</p>
                        <p class="text-gray-600">@lang('admin::app.sales.orders.view.total-paid')</p>
                        <p class="text-gray-600">@lang('admin::app.sales.orders.view.total-refund')</p>
                        <p class="text-gray-600">@lang('admin::app.sales.orders.view.total-due')</p>

                    </div>
                    <div class="flex  flex-col gap-y-[6px]">
                        <p class="text-gray-600 font-semibold">{{ core()->formatBasePrice($order->base_sub_total) }}</p>
                        <p class="text-gray-600">{{ core()->formatBasePrice($order->base_tax_amount) }}</p>

                        @if ($haveStockableItems)
                            <p class="text-gray-600">{{ core()->formatBasePrice($order->base_shipping_amount) }}</p>
                        @endif

                        <p class="text-[16px] text-gray-800 font-semibold">{{ core()->formatBasePrice($order->base_grand_total) }}</p>
                        <p class="text-gray-600">{{ core()->formatBasePrice($order->base_grand_total_invoiced) }}</p>
                        <p class="text-gray-600">{{ core()->formatBasePrice($order->base_grand_total_refunded) }}</p>

                        @if($order->status !== 'canceled')
                            <p class="text-gray-600">{{ core()->formatBasePrice($order->base_total_due) }}</p>
                        @else
                            <p class="text-gray-600">{{ core()->formatBasePrice(0.00) }}</p>
                        @endif
                    </div>
                </div>
            </div>
           
            {{-- Customer's comment form --}}
            <div class="bg-white rounded box-shadow">
                <p class="p-[16px] pb-0 text-[16px] text-gray-800 font-semibold">
                    @lang('admin::app.sales.orders.view.comments')
                </p>

                <x-admin::form action="{{ route('admin.sales.orders.comment', $order->id) }}">
                    <div class="p-[16px]">
                        <div class="mb-[10px]">
                            <x-admin::form.control-group>
                                <x-admin::form.control-group.control
                                    type="textarea"
                                    name="comment" 
                                    id="comment"
                                    rules="required"
                                    :label="trans('admin::app.sales.orders.view.comments')"
                                    :placeholder="trans('admin::app.sales.orders.view.write-your-comment')"
                                    rows="5"
                                >
                                </x-admin::form.control-group.control>

                                <x-admin::form.control-group.error
                                    control-name="comment"
                                >
                                </x-admin::form.control-group.error>
                            </x-admin::form.control-group>
                        </div>

                        <div class="flex justify-between items-center">
                            <label 
                                class="flex gap-[4px] w-max items-center p-[6px] cursor-pointer select-none"
                                for="customer_notified"
                            >
                                <input 
                                    type="checkbox" 
                                    name="customer_notified"
                                    id="customer_notified"
                                    value="1"
                                    class="hidden peer"
                                >
                    
                                <span class="icon-uncheckbox rounded-[6px] text-[24px] cursor-pointer peer-checked:icon-checked peer-checked:text-navyBlue"></span>
                    
                                <p class="flex gap-x-[4px] items-center cursor-pointer">
                                    @lang('admin::app.sales.orders.view.notify-customer')
                                </p>
                            </label>
                            
                            <button
                                type="submit"
                                class="text-blue-600 font-semibold whitespace-nowrap px-[12px] py-[5px] bg-white border-[2px] border-blue-600 rounded-[6px] cursor-pointer"
                            >
                                @lang('admin::app.sales.orders.view.submit-comment')
                            </button>
                        </div>
                    </div>
                </x-admin::form> 

                <span class="block w-full border-b-[1px] border-gray-300"></span>

                {{-- Comment List --}}
                @foreach ($order->comments()->orderBy('id', 'desc')->get() as $comment)
                    <div class="grid gap-[6px] p-[16px]">
                        <p class="text-[16px] text-gray-800">
                            {{ $comment->comment }}
                        </p>

                        <p class="text-gray-600">  
                            @if ($comment->customer_notified)
                                @lang('admin::app.sales.orders.view.customer-notified', ['date' => core()->formatDate($comment->created_at, 'Y-m-d H:i:s a')])
                            @else
                                @lang('admin::app.sales.orders.view.customer-not-notified', ['date' => core()->formatDate($comment->created_at, 'Y-m-d H:i:s a')])
                            @endif
                        </p>
                    </div>

                    <span class="block w-full border-b-[1px] border-gray-300"></span>
                @endforeach
            </div>
        </div>

        {!! view_render_event('sales.order.tabs.before', ['order' => $order]) !!}

        {{-- Customer and address information --}}
        <div class="flex flex-col gap-[8px] w-[360px] max-w-full max-sm:w-full">
            <x-admin::accordion>
                <x-slot:header>
                    <p class="text-gray-600 text-[16px] p-[10px] font-semibold">@lang('admin::app.sales.orders.view.customer')</p>
                </x-slot:header>

                <x-slot:content>
                    <div class="pb-[16px]">
                        <div class="flex flex-col">
                            <p class="text-gray-800 font-semibold">{{ $order->customer_full_name }}</p>

                            {!! view_render_event('sales.order.customer_full_name.after', ['order' => $order]) !!}

                            <p class="text-gray-600">{{ $order->customer_email }}</p>

                            {!! view_render_event('sales.order.customer_email.after', ['order' => $order]) !!}

                            <p class="text-gray-600">@lang('admin::app.sales.orders.view.customer-group') : {{ $order->customer->group->name ?? '' }}</p>

                            {!! view_render_event('sales.order.customer_group.after', ['order' => $order]) !!}
                        </div>
                    </div>
                    
                    <span class="block w-full border-b-[1px] border-gray-300"></span>

                    {{-- Billing Address --}}
                    @if ($order->billing_address)
                        <div class="pb-[16px]">
                            <div class="flex items-center justify-between">
                                <p class="text-gray-600 text-[16px] py-[16px] font-semibold">@lang('admin::app.sales.orders.view.billing-address')</p>
                            </div>

                            @include ('admin::sales.address', ['address' => $order->billing_address])

                            {!! view_render_event('sales.order.billing_address.after', ['order' => $order]) !!}
                        </div>

                        <span class="block w-full border-b-[1px] border-gray-300"></span>
                    @endif

                    {{-- Shipping Address --}}
                    @if ($order->shipping_address)
                        <div class="pb-[16px]">
                            <div class="flex items-center justify-between">
                                <p class="text-gray-600 text-[16px] py-[16px] font-semibold">@lang('admin::app.sales.orders.view.shipping-address')</p>
                            </div>

                            @include ('admin::sales.address', ['address' => $order->shipping_address])

                            {!! view_render_event('sales.order.shipping_address.after', ['order' => $order]) !!}
                        </div>
                    @endif

                </x-slot:content>
            </x-admin::accordion> 

            {{-- Order Information --}}
            <x-admin::accordion>
                <x-slot:header>
                    <p class="text-gray-600 text-[16px] p-[10px] font-semibold">@lang('admin::app.sales.orders.view.order-information')</p>
                </x-slot:header>

                <x-slot:content>
                    <div class="">
                        <div class="flex w-full gap-[20px] justify-start">
                            <div class="flex flex-col gap-y-[6px]">
                                <p class="text-gray-600">
                                    @lang('admin::app.sales.orders.view.order-date')
                                </p>

                                <p class="text-gray-600">
                                    @lang('admin::app.sales.orders.view.order-status')
                                </p>

                                <p class="text-gray-600">
                                    @lang('admin::app.sales.orders.view.channel')
                                </p>
                            </div>
                    
                            <div class="flex flex-col gap-y-[6px]">
                                {!! view_render_event('sales.order.created_at.before', ['order' => $order]) !!}

                                {{-- Order Date --}}
                                <p class="text-gray-600">
                                    {{core()->formatDate($order->created_at) }}
                                </p>

                                {!! view_render_event('sales.order.created_at.after', ['order' => $order]) !!}
                            
                                {{-- Order Status --}}
                                <p class="text-gray-600">
                                    {{$order->status_label}}
                                </p>
                            
                                {!! view_render_event('sales.order.status_label.after', ['order' => $order]) !!}

                                {{-- Order Channel --}}
                                <p class="text-gray-800">
                                    {{$order->channel_name}}
                                </p>

                                {!! view_render_event('sales.order.channel_name.after', ['order' => $order]) !!}
                            </div>
                        </div>
                    </div>
                </x-slot:content>
            </x-admin::accordion> 

            {{-- Invoice Information--}}    
            <x-admin::accordion>
                <x-slot:header>
                    <p class="text-gray-600 text-[16px] p-[10px] font-semibold"> @lang('admin::app.sales.orders.view.invoices') ({{ count($order->invoices) }})</p>
                </x-slot:header>

                <x-slot:content>
                    @forelse ($order->invoices as $invoice)
                        <div class="grid gap-y-[10px] pb-[16px]">
                            <div>
                                <p class="text-gray-800 font-semibold">@lang('admin::app.sales.orders.view.invoice') #{{ $invoice->increment_id ?? $invoice->id }}</p>
                                <p class="text-gray-600">{{ core()->formatDate($invoice->created_at, 'd M, Y H:i:s a') }}</p>
                            </div>

                            <div class="flex gap-[10px]">
                                <a
                                    href="{{ route('admin.sales.invoices.view', $invoice->id) }}"
                                    class="text-blue-600"
                                >
                                    @lang('admin::app.sales.orders.view.view')
                                </a>

                                <a
                                    href="{{ route('admin.sales.invoices.print', $invoice->id) }}"
                                    class="text-blue-600"
                                >
                                    @lang('admin::app.sales.orders.view.download-pdf')
                                </a>
                                
                            </div>
                        </div>
                    @empty 
                        <p class="text-gray-600">
                            @lang('admin::app.sales.orders.view.no-invoice-found')
                        </p>
                    @endforelse
                </x-slot:content>
            </x-admin::accordion> 

            {{-- Shipment Information--}}    
            <x-admin::accordion>
                <x-slot:header>
                    <p class="text-gray-600 text-[16px] p-[10px] font-semibold">@lang('admin::app.sales.orders.view.shipments') ({{ count($order->shipments) }})</p>
                </x-slot:header>

                <x-slot:content>
                    @forelse ($order->shipments as $shipment)
                        <div class="grid gap-y-[10px] pb-[16px]">
                            <div>
                                <p class="text-gray-800 font-semibold">@lang('Shipment') #{{ $shipment->id }}</p>
                                <p class="text-gray-600">{{ core()->formatDate($shipment->created_at, 'd M, Y H:i:s a') }}</p>
                            </div>

                            <div class="flex gap-[10px]">
                                <a
                                    href="{{ route('admin.sales.shipments.view', $shipment->id) }}"
                                    class="text-blue-600"
                                >
                                    @lang('admin::app.sales.orders.view.view')
                                </a>
                            </div>
                        </div>
                    @empty 
                        <p class="text-gray-600">
                            @lang('admin::app.sales.orders.view.no-shipment-found')
                        </p>
                    @endforelse
                </x-slot:content>
            </x-admin::accordion> 

            {{-- Payment Information--}}    
            <x-admin::accordion>
                <x-slot:header>
                    <p class="text-gray-600 text-[16px] p-[10px] font-semibold">@lang('admin::app.sales.orders.view.payment-and-shipping')</p>
                </x-slot:header>

                <x-slot:content>
                    <div class="pb-[16px]">
                        {{-- Payment method --}}
                        <p class="text-gray-800 font-semibold">{{ core()->getConfigData('sales.paymentmethods.' . $order->payment->method . '.title') }}</p>
                        <p class="text-gray-600">@lang('admin::app.sales.orders.view.payment-method')</p>

                        {{-- Currency --}}
                        <p class="pt-[16px] text-gray-800 font-semibold">{{ $order->order_currency_code }}</p>
                        <p class="text-gray-600">@lang('admin::app.sales.orders.view.currency')</p>

                        @php $additionalDetails = \Webkul\Payment\Payment::getAdditionalDetails($order->payment->method); @endphp

                        {{-- Addtional details --}}
                        @if (! empty($additionalDetails))
                            <p class="pt-[16px] text-gray-800 font-semibold">{{ $additionalDetails['title'] }}</p>

                            <p class="text-gray-600">{{ $additionalDetails['value'] }}</p>
                        @endif

                        {!! view_render_event('sales.order.payment-method.after', ['order' => $order]) !!}
                    </div>

                    <span class="block w-full border-b-[1px] border-gray-300"></span>
                    
                    @if ($order->shipping_address)
                        <div class="py-[16px]">
                            <p class="text-gray-800 font-semibold">{{ $order->shipping_title }}</p>
                            <p class="text-gray-600">@lang('admin::app.sales.orders.view.shipping-method')</p>

                            <p class="pt-[16px] text-gray-800 font-semibold">{{ core()->formatBasePrice($order->base_shipping_amount) }}</p>
                            <p class="text-gray-600">@lang('admin::app.sales.orders.view.shipping-price')</p>
                        </div>

                        {!! view_render_event('sales.order.shipping-method.after', ['order' => $order]) !!}
                    @endif
                </x-slot:content>
            </x-admin::accordion> 

             {{-- Refund Information--}}    
             <x-admin::accordion>
                <x-slot:header>
                    <p class="text-gray-600 text-[16px] p-[10px] font-semibold">@lang('admin::app.sales.orders.view.refund')</p>
                </x-slot:header>

                <x-slot:content>
                    @forelse ($order->refunds as $refund)
                        <div class="grid gap-y-[10px] pb-[16px]">
                            <div>
                                <p class="text-gray-800 font-semibold">@lang('admin::app.sales.orders.view.refund') #{{ $refund->id }}</p>
                                <p class="text-gray-600">{{ core()->formatDate($refund->created_at, 'd M, Y H:i:s a') }}</p>
                                <p class="mt-[16px] text-gray-800 font-semibold">@lang('admin::app.sales.orders.view.name')</p>
                                <p class="text-gray-600">{{ $refund->order->customer_full_name }}</p>
                                <p class="mt-[16px] text-gray-800 font-semibold">@lang('admin::app.sales.orders.view.status')</p>
                                <p class="text-gray-600">@lang('admin::app.sales.orders.view.refunded') <span class="text-gray-800 font-semibold">{{ core()->formatBasePrice($refund->base_grand_total) }}</span></p>
                            </div>

                            <div class="flex gap-[10px]">
                                <a
                                    href="{{ route('admin.sales.refunds.view', $refund->id) }}"
                                    class="text-blue-600"
                                >
                                    @lang('admin::app.sales.orders.view.view')
                                </a>
                            </div>
                        </div>
                    @empty 
                        <p class="text-gray-600">@lang('admin::app.sales.orders.view.no-refund-found')</p>
                    @endforelse
                </x-slot:content>
            </x-admin::accordion> 
        </div>

        {!! view_render_event('sales.order.tabs.after', ['order' => $order]) !!}
    </div>

    @pushOnce('scripts')
        <script type="text/x-template" id="v-create-shipment-template">
            <div
                class="inline-flex gap-x-[8px] items-center justify-between w-full max-w-max px-[4px] py-[6px] text-gray-600 font-semibold text-center cursor-pointer transition-all hover:bg-gray-200 hover:rounded-[6px]"
                @click="$refs.invoice.open()"
            >
                <span class="icon-ship text-[24px]"></span> 

                @lang('admin::app.sales.orders.view.ship')     
            </div>

            <x-admin::form  
                method="POST"
                :action="route('admin.sales.shipments.store', $order->id)"
            >

                <x-admin::drawer ref="invoice">
                    <!-- Drawer Header -->
                    <x-slot:header>
                        <div class="grid gap-[12px]">
                            <div class="flex justify-between items-center">
                                <p class="text-[20px] font-medium">
                                    @lang('New Shipment')
                                </p>
        
                                <button
                                    type="submit"
                                    class="mr-[45px] px-[12px] py-[6px] bg-blue-600 border border-blue-700 rounded-[6px] text-gray-50 font-semibold cursor-pointer"
                                >
                                    @lang('Create Shipment')
                                </button>
                            </div>
                        </div>
                        
                    </x-slot:header>

                    <!-- Drawer Content -->
                    <x-slot:content class="!p-0">
                        
                        <div class="grid">
                            <div class="p-[16px] !pt-0">
                               <div>
                                    <div class="grid grid-cols-2 gap-x-[20px]">
                                        <x-admin::form.control-group>
                                            <x-admin::form.control-group.label>
                                                @lang('Carrier Name')
                                            </x-admin::form.control-group.label>
        
                                            <x-admin::form.control-group.control
                                                type="text"
                                                name="shipment[carrier_title]" 
                                                id="shipment[carrier_title]" 
                                                :label="trans('Carrier Name')"
                                                :placeholder="trans('Carrier Name')"
                                            >
                                            </x-admin::form.control-group.control>
        
                                            <x-admin::form.control-group.error
                                                control-name="carrier_name"
                                            >
                                            </x-admin::form.control-group.error>
                                        </x-admin::form.control-group>
        
                                        <x-admin::form.control-group>
                                            <x-admin::form.control-group.label>
                                                @lang('Tracking Number')
                                            </x-admin::form.control-group.label>
        
                                            <x-admin::form.control-group.control
                                                type="text"
                                                name="shipment[track_number]"
                                                id="shipment[track_number]"
                                                :label="trans('Tracking Number')"
                                                :placeholder="trans('Tracking Number')"
                                            >
                                            </x-admin::form.control-group.control>
        
                                            <x-admin::form.control-group.error
                                                control-name="shipment[track_number]"
                                            >
                                            </x-admin::form.control-group.error>
                                        </x-admin::form.control-group>
                                    </div>
                                    
                                    <x-admin::form.control-group>
                                        <x-admin::form.control-group.label class="required">
                                            @lang('Source')
                                        </x-admin::form.control-group.label>

                                        <x-admin::form.control-group.control
                                            type="select"
                                            name="shipment[source]" 
                                            id="shipment[source]" 
                                            rules="required"
                                            :label="trans('Source')"
                                            :placeholder="trans('Source')"
                                            v-model="source"
                                            @change="onSourceChange"
                                        >
                                            @foreach ($order->channel->inventory_sources as $inventorySource)
                                                <option value="{{ $inventorySource->id }}">
                                                    {{ $inventorySource->name }}
                                                </option>
                                            @endforeach
                                        </x-admin::form.control-group.control>

                                        <x-admin::form.control-group.error
                                            control-name="shipment[source]"
                                        >
                                        </x-admin::form.control-group.error>
                                    </x-admin::form.control-group>
                               </div>

                                <div class="grid">
                                    @foreach ($order->items as $item)
                                        <div class="flex gap-[10px] justify-between py-[16px] ">
                                            <div class="flex gap-[10px]">
                                                @if($item->product)
                                                    <div class="grid gap-[4px] content-center justify-items-center min-w-[60px] h-[60px] px-[6px] border border-dashed border-gray-300 rounded-[4px]">
                                                        <img
                                                            class="w-[20px]"
                                                            src="{{ $item->product->base_image_url }}"
                                                        >
                                                    </div>
                                                @endif
                
                                                <div class="grid gap-[6px] place-content-start">
                                                    <p class="text-[16x] text-gray-800 font-semibold">{{ $item->name }}</p>
                
                                                    <div class="flex flex-col gap-[6px] place-items-start">
                                                        <p class="text-gray-600">
                                                            {{ core()->formatBasePrice($item->base_price) }} 
                
                                                            @lang('admin::app.sales.orders.view.per-unit') x {{ $item->qty_ordered }} @lang('admin::app.sales.orders.view.quantity')
                                                        </p>
                
                                                        @if (isset($item->additional['attributes']))
                                                            <p class="text-gray-600">
                                                                @foreach ($item->additional['attributes'] as $attribute)
                                                                    {{ $attribute['attribute_name'] }} : {{ $attribute['option_label'] }}
                                                                @endforeach
                                                            </p>
                                                        @endif
                
                                                        <p class="text-gray-600">@lang('admin::app.sales.orders.view.sku')  - {{ $item->sku }}</p>
                
                                                        <p class="text-gray-600">
                                                            @lang('admin::app.sales.orders.view.ordered') {{ $item->qty_ordered }},
                
                                                            @lang('admin::app.sales.orders.view.invoiced') {{ $item->qty_invoiced }},
                
                                                            @lang('admin::app.sales.orders.view.shipped') {{ $item->qty_shipped }}
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="gap-[10px] justify-between pb-[16px] border-b-[1px] border-slate-300">
                                            <!-- Information -->
                                            <div class="flex justify-between">
            
                                                @foreach ($order->channel->inventory_sources as $inventorySource)
                                                    <div class="grid gap-[10px]">
                                                        <p class="text-[16x] text-gray-800 font-semibold">
                                                            {{ $inventorySource->name }}
                                                        </p>
            
                                                        <p class="text-gray-600">
                                                            Qty. Available :                  
            
                                                            @php
                                                                $product = $item->getTypeInstance()->getOrderedItem($item)->product;
            
                                                                $sourceQty = $product->type == 'bundle' ? $item->qty_ordered : $product->inventory_source_qty($inventorySource->id);
                                                            @endphp
            
                                                            {{ $sourceQty }}
                                                        </p>
                                                    </div>
            
                                                    <div class="flex gap-[10px] items-center">
                                                        @php
                                                            $inputName = "shipment[items][$item->id][$inventorySource->id]";
                                                        @endphp
            
                                                        <x-admin::form.control-group.label class="required">
                                                            Qty to Ship
                                                        </x-admin::form.control-group.label>
            
                                                        <x-admin::form.control-group class="!mb-0">
                                                            <x-admin::form.control-group.control
                                                                type="text"
                                                                :name="$inputName" 
                                                                :id="$inputName" 
                                                                :value="$item->qty_to_ship"
                                                                :rules="'required|numeric|min_value:0|max_value:' . $item->qty_ordered"
                                                                class="!w-[100px]"
                                                                data-original-quantity="{{ $item->qty_to_ship }}"
                                                                ::disabled="'{{ empty($sourceQty) }}' || source != '{{ $inventorySource->id }}'"
                                                                :ref="$inputName"
                                                            >
                                                            </x-admin::form.control-group.control>
                                
                                                            <x-admin::form.control-group.error
                                                                :control-name="$inputName"
                                                            >
                                                            </x-admin::form.control-group.error>
                                                        </x-admin::form.control-group>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </x-slot:content>
                </x-admin::drawer>
            </x-admin::form>
        </script>

        <script type="module">
            app.component('v-create-shipment', {
                template: '#v-create-shipment-template',

                data() {
                    return {
                        source: "",
                    };
                },

                methods: {
                    onSourceChange() {
                        this.setOriginalQuantityToAllShipmentInputElements();
                    },

                    getAllShipmentInputElements() {
                        let allRefs = this.$refs;

                        let allInputElements = [];

                        Object.keys(allRefs).forEach((key) => {
                            if (key.startsWith('shipment')) {
                                allInputElements.push(allRefs[key]);
                            }
                        });

                        return allInputElements;
                    },

                    setOriginalQuantityToAllShipmentInputElements() {
                        this.getAllShipmentInputElements().forEach((element) => {
                            element.value = element.dataset.originalQuantity;
                        });
                    }
                },
            });
        </script>
    @endPushOnce
</x-admin::layouts>