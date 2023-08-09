<x-admin::layouts>
    <x-slot:title>
        {{-- @lang('admin::app.sales.orders.view-title', ['order_id' => $order->increment_id]) --}}
        @lang('Order') #{{ $order->increment_id }}
    </x-slot:title>

    {{-- Header --}}
    <div class="grid">
        <div class="flex gap-[16px] justify-between items-center max-sm:flex-wrap">
            <p class="text-[20px] text-gray-800 font-bold leading-[24px]">
                @lang('Order') #{{ $order->increment_id }}
                <span class="label-pending text-[14px] mx-[5px]"> {{ $order->status }} </span>
            </p>
            
            <div class="flex gap-x-[10px] items-center">
                <div
                    class="inline-flex gap-x-[4px] items-center justify-between text-gray-600 p-[6px] text-center w-full max-w-max rounded-[6px] border border-transparent cursor-pointer transition-all hover:bg-gray-200">
                    <span class="icon-arrow-right text-[24px]"></span>
                </div>
                <div
                    class="inline-flex gap-x-[4px] items-center justify-between text-gray-600 p-[6px] text-center w-full max-w-max rounded-[6px] border border-transparent cursor-pointer transition-all hover:bg-gray-200">
                    <span class="icon-arrow-left text-[24px]"></span>
                </div>
            </div>
        </div>
    </div>

    <div class="flex justify-between gap-x-[4px] gap-y-[8px] items-center flex-wrap mt-[28px]">
        <div>
            <div
                class="inline-flex gap-x-[8px] items-center justify-between w-full max-w-max px-[4px] py-[6px] text-gray-600 font-semibold text-center cursor-pointer transition-all hover:bg-gray-200 hover:rounded-[6px"
                onclick="window.print()"
            >
                <span class="icon-printer text-[24px]"></span> 
                @lang('Print')
            </div>

            <div class="inline-flex gap-x-[8px] items-center justify-between w-full max-w-max px-[4px] py-[6px] text-gray-600 font-semibold text-center cursor-pointer transition-all hover:bg-gray-200 hover:rounded-[6px]">
                <span class="icon-mail text-[24px]"></span>
                @lang('Email')
            </div>

            <div class="inline-flex gap-x-[8px] items-center justify-between w-full max-w-max px-[4px] py-[6px] text-gray-600 font-semibold text-center cursor-pointer transition-all hover:bg-gray-200 hover:rounded-[6px]">
                <span class="icon-cancel text-[24px]"></span>
                @lang('Cancel')
            </div>

            <div
                class="inline-flex gap-x-[8px] items-center justify-between w-full max-w-max px-[4px] py-[6px] text-gray-600 font-semibold text-center cursor-pointer transition-all hover:bg-gray-200 hover:rounded-[6px]">
                <span class="icon-mail text-[24px]"></span> 
                @lang('Invoice')
            </div>

            <div class="inline-flex gap-x-[8px] items-center justify-between w-full max-w-max px-[4px] py-[6px] text-gray-600 font-semibold text-center cursor-pointer transition-all hover:bg-gray-200 hover:rounded-[6px]">
                <span class="icon-ship text-[24px]"></span> 
                @lang('Ship')
            </div>
        </div>

        <div class="flex gap-x-[10px] items-center">
            <button
                type="button"
                class="text-gray-50 font-semibold px-[12px] py-[6px] bg-blue-600 border border-blue-700 rounded-[6px] cursor-pointer"
            >
                @lang('Refund')    
            </button>
        </div>
    </div>

    {{-- Order details --}}
    <div class="flex gap-[10px] mt-[14px] max-xl:flex-wrap">
        <div class="flex flex-col gap-[8px] flex-1 max-xl:flex-auto">
            <div class="p-[16px] bg-white rounded-[4px] box-shadow">
                <div class="flex justify-between">
                    <p class="text-[16px] text-gray-800 font-semibold mb-[16px]">@lang('Order Items') ({{ count($order->items) }})</p>

                    <p class="text-[16px] text-gray-800 font-semibold">
                        @lang('Grand Total') - {{ core()->formatBasePrice($order->base_grand_total) }}
                    </p>
                </div>

                {{-- Order items --}}
                <div class="grid">
                    @foreach ($order->items as $item)
                        <div class="flex gap-[10px] justify-between px-[16px] py-[24px] border-b-[1px] border-slate-300">
                            <div class="flex gap-[10px]">
                                <div class="grid gap-[4px] content-center justify-items-center min-w-[60px] h-[60px] px-[6px] border border-dashed border-gray-300 rounded-[4px]">
                                    <img class="w-[20px]" src="http://192.168.15.62/bagisto-admin-panel/resources/images/placeholder/next-angle-top.png">
                                </div>

                                <div class="grid gap-[6px] place-content-start">
                                    <p class="text-[16x] text-gray-800 font-semibold">{{ $item->name }}</p>

                                    <div class="flex flex-col gap-[6px] place-items-start">
                                        <p class="text-gray-600">
                                            {{ core()->formatBasePrice($item->base_price) }} 
                                            @lang('per unit') x {{ $item->qty_ordered }} @lang('Quantity')
                                        </p>

                                        @if (isset($item->additional['attributes']))
                                            <p class="text-gray-600">
                                                @foreach ($item->additional['attributes'] as $attribute)
                                                    {{ $attribute['attribute_name'] }} : {{ $attribute['option_label'] }}
                                                @endforeach
                                            </p>
                                        @endif

                                        <p class="text-gray-600">@lang('Ordered') {{ $item->qty_ordered }}, @lang('Invoiced') {{ $item->qty_invoiced }}, @lang('Shipped') {{ $item->qty_shipped }}</p>
                                    </div>

                                    <p class="text-gray-600">@lang('SKU') - {{ $item->sku }}</p>
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
                                    <p class="text-gray-600">@lang('Total') - {{ core()->formatBasePrice($item->base_total + $item->base_tax_amount - $item->base_discount_amount) }}</p>
                                    <p class="text-gray-600">{{ $item->tax_percent }}% Tax - {{ core()->formatBasePrice($item->base_tax_amount) }}</p>
                                    <p class="text-gray-600">@lang('Sub Total') - {{ core()->formatBasePrice($item->base_total) }}</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="flex w-full gap-[10px] justify-end mt-[16px]">
                    <div class="flex flex-col gap-y-[6px]">
                        <p class="text-gray-600 font-semibold">@lang('Subtotal')</p>
                        <p class="text-gray-600">@lang('Tax')</p>

                        @if ($haveStockableItems = $order->haveStockableItems())
                            <p class="text-gray-600">@lang('Shipping and Handling')</p>
                        @endif

                        <p class="text-[16px] text-gray-800 font-semibold">@lang('Grand Total')</p>
                        <p class="text-gray-600">@lang('Total Paid')</p>
                        <p class="text-gray-600">@lang('Total Refund')</p>
                        <p class="text-gray-600">@lang('Total Due')</p>

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
                    @lang('Customers')
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
                                    label="Comment"
                                    placeholder="Write your comment"
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
                                    @lang('Notify Customer')
                                </p>
                            </label>
                            
                            <button
                                type="submit"
                                class="text-blue-600 font-semibold whitespace-nowrap px-[12px] py-[5px] bg-white border-[2px] border-blue-600 rounded-[6px] cursor-pointer"
                            >
                                @lang('Submit Comment')
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
                                @lang(core()->formatDate($comment->created_at, 'Y-m-d H:i:s a') . ' | Customer <b>Notified</b>')
                            @else
                                @lang(core()->formatDate($comment->created_at, 'Y-m-d H:i:s a') . ' | Customer <b>Not Notified</b>')
                            @endif
                        </p>
                    </div>

                    <span class="block w-full border-b-[1px] border-gray-300"></span>
                @endforeach
            </div>
        </div>

        <div class="flex flex-col gap-[8px] w-[360px] max-w-full max-sm:w-full">
            {{-- Billing and shipping address accordion --}}
            @if (
                $order->billing_address
                || $order->shipping_address
            )
                <x-admin::accordion>
                    <x-slot:header>
                        <p class="text-gray-600 text-[16px] p-[10px] font-semibold">Marketing Time</p>
                    </x-slot:header>

                    <x-slot:content>
                        <div class="pb-[16px]">
                            <div class="flex flex-col">
                                <p class="text-gray-800 font-semibold">{{ $order->customer_full_name }}</p>
                                <p class="text-gray-600">{{ $order->customer_email }}</p>
                                <p class="text-gray-600">@lang('Customer Group') : {{ $order->customer->group->name ?? '' }}</p>
                            </div>
                        </div>
                        
                        <span class="block w-full border-b-[1px] border-gray-300"></span>
                        
                        {{-- Billing Address --}}
                        @if ($order->billing_address)
                            <div class="pb-[16px]">
                                <div class="flex items-center justify-between">
                                    <p class="text-gray-600 text-[16px] py-[16px] font-semibold">@lang('Billing Address')</p>
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
                                    <p class="text-gray-600 text-[16px] py-[16px] font-semibold">@lang('Shipping Address')</p>
                                </div>

                                @include ('admin::sales.address', ['address' => $order->shipping_address])

                                {!! view_render_event('sales.order.shipping_address.after', ['order' => $order]) !!}
                            </div>
                        @endif
                    </x-slot:content>
                </x-admin::accordion> 
            @endif

            {{-- Invoice Information--}}    
            <x-admin::accordion>
                <x-slot:header>
                    <p class="text-gray-600 text-[16px] p-[10px] font-semibold">@lang('Invoices') ({{ count($order->invoices) }})</p>
                </x-slot:header>

                <x-slot:content>
                    @foreach ($order->invoices as $invoice)
                        <div class="grid gap-y-[10px] pb-[16px]">
                            <div>
                                <p class="text-gray-800 font-semibold">@lang('Invoice') #{{ $invoice->increment_id ?? $invoice->id }}</p>
                                <p class="text-gray-600">{{ core()->formatDate($invoice->created_at, 'd M, Y H:i:s a') }}</p>
                            </div>

                            <div class="flex gap-[10px]">
                                <a
                                    href="{{ route('admin.sales.invoices.view', $invoice->id) }}"
                                    class="text-blue-600"
                                >
                                    @lang('View')
                                </a>

                                <a
                                    href="{{ route('admin.sales.invoices.print', $invoice->id) }}"
                                    class="text-blue-600"
                                >
                                    @lang('Download Pdf')
                                </a>
                                
                            </div>
                        </div>
                    @endforeach
                </x-slot:content>
            </x-admin::accordion> 

            {{-- Invoice Information--}}    
            <x-admin::accordion>
                <x-slot:header>
                    <p class="text-gray-600 text-[16px] p-[10px] font-semibold">@lang('Shipments') ({{ count($order->shipments) }})</p>
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
                                    @lang('View')
                                </a>
                            </div>
                        </div>
                    @empty 
                        <p class="text-gray-600">@lang('No shipment found')</p>
                    @endforelse
                </x-slot:content>
            </x-admin::accordion> 
        </div>
    </div>
</x-admin::layouts>