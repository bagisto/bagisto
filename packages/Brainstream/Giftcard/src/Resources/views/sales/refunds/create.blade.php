<!-- Refund Vue Component -->
<v-create-refund>
    <div class="inline-flex gap-x-2 items-center justify-between w-full max-w-max px-1 py-1.5 text-gray-600 dark:text-gray-300 font-semibold text-center cursor-pointer transition-all hover:bg-gray-200 dark:hover:bg-gray-800 hover:rounded-md">
        <span class="icon-cancel text-2xl"></span> 

        @lang('admin::app.sales.orders.view.refund')     
    </div>
</v-create-refund>

@pushOnce('scripts')
    <script
        type="text/x-template"
        id="v-create-refund-template"
    >
        <div>
            
            <!-- refund Create Drawer -->
            <x-admin::form  
                method="POST"
                :action="route('admin.sales.refunds.store', $order->id)"
            >
                <x-admin::drawer ref="refund">
                    <!-- Drawer Header -->
                    <x-slot:header>
                        <div class="grid gap-3 h-8">
                            <div class="flex justify-between items-center">
                                <p class="text-xl font-medium dark:text-white">
                                    @lang('admin::app.sales.refunds.create.title')
                                </p>

                                <div class="flex gap-x-2.5">
                                    <!-- Update Quantity Button -->
                                 
                                    @if (bouncer()->hasPermission('sales.refunds.create'))
                                        <div 
                                            class="transparent-button text-red-600 hover:bg-gray-200 dark:hover:bg-gray-800"
                                            @click="updateQty"
                                        >
                                            @lang('admin::app.sales.refunds.create.update-quantity-btn')
                                        </div>

                                        <!-- Refund Submit Button -->
                                        <button
                                            type="submit"
                                            class="ltr:mr-11 rtl:ml-11 primary-button"
                                        >
                                            @lang('admin::app.sales.refunds.create.refund-btn')
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </x-slot>

                    <!-- Drawer Content -->
                    <x-slot:content class="!p-0">
                        <div class="grid p-4 !pt-0">
                            <div class="grid">
                                <!-- Item Listing -->
                                @foreach ($order->items as $item)
                                    @if ($item->qty_to_refund)
                                        <div class="flex gap-2.5 justify-between py-4">
                                            <div class="flex gap-2.5">
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
                                                    <!-- Item Additional Attributes -->
                                                    <p class="text-base text-gray-800 dark:text-white font-semibold">
                                                        {{ $item->name }}
                                                    </p>
                
                                                    <div class="flex flex-col gap-1.5 place-items-start">
                                                        <p class="text-gray-600 dark:text-gray-300">
                                                            @lang('admin::app.sales.refunds.create.amount-per-unit', [
                                                                'amount' => core()->formatBasePrice($item->base_price),
                                                                'qty'    => $item->qty_ordered,
                                                            ])
                                                        </p>

                                                        <!-- Item Additional Attributes -->
                                                        @if (isset($item->additional['attributes']))
                                                            <p class="text-gray-600 dark:text-gray-300">
                                                                @foreach ($item->additional['attributes'] as $attribute)
                                                                    {{ $attribute['attribute_name'] }} : {{ $attribute['option_label'] }}
                                                                @endforeach
                                                            </p>
                                                        @endif
                
                                                        <!-- Item SKU -->
                                                        <p class="text-gray-600 dark:text-gray-300">
                                                            @lang('admin::app.sales.refunds.create.sku', ['sku' => Webkul\Product\Helpers\ProductType::hasVariants($item->type) ? $item->child->sku : $item->sku])
                                                        </p>

                                                        <!-- Item Status -->
                                                        <p class="text-gray-600 dark:text-gray-300">
                                                            {{ $item->qty_ordered ? trans('admin::app.sales.refunds.create.item-ordered', ['qty_ordered' => $item->qty_ordered]) : '' }}

                                                            {{ $item->qty_invoiced ? trans('admin::app.sales.refunds.create.item-invoice', ['qty_invoiced' => $item->qty_invoiced]) : '' }}

                                                            {{ $item->qty_shipped ? trans('admin::app.sales.refunds.create.item-shipped', ['qty_shipped' => $item->qty_shipped]) : '' }}

                                                            {{ $item->qty_refunded ? trans('admin::app.sales.refunds.create.item-refunded', ['qty_refunded' => $item->qty_refunded]) : '' }}

                                                            {{ $item->qty_canceled ? trans('admin::app.sales.refunds.create.item-canceled', ['qty_canceled' => $item->qty_canceled]) : '' }}
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="gap-2.5 justify-between pb-4 border-b border-slate-300 dark:border-gray-800">
                                            <!-- Information -->
                                            <div class="flex justify-between">
                                                <!-- Quantity to Refund -->
                                                <div class="">
                                                    <x-admin::form.control-group.label class="required">
                                                        @lang('admin::app.sales.refunds.create.qty-to-refund')
                                                    </x-admin::form.control-group.label>

                                                    <x-admin::form.control-group class="!mb-0">
                                                        <x-admin::form.control-group.control
                                                            type="text"
                                                            id="refund[items][{{ $item->id }}]"
                                                            name="refund[items][{{ $item->id }}]"
                                                            :rules="'required|numeric|min_value:0|max_value:' . $item->qty_ordered"
                                                            v-model="refund.items[{{ $item->id }}]"
                                                            :label="trans('admin::app.sales.refunds.create.qty-to-refund')"
                                                        />
                            
                                                        <x-admin::form.control-group.error control-name="refund[items][{{ $item->id }}]" />
                                                    </x-admin::form.control-group>
                                                </div>

                                                <!-- Item Order Summary -->
                                                <div class="flex w-full gap-5 justify-end item">
                                                    <div class="flex flex-col gap-y-1.5">
                                                        <p class="text-gray-600 dark:text-gray-300">
                                                            @lang('admin::app.sales.refunds.create.price')
                                                        </p>

                                                        <p class="text-gray-600 dark:text-gray-300">
                                                            @lang('admin::app.sales.refunds.create.subtotal')
                                                        </p>

                                                        <p class="text-gray-600 dark:text-gray-300">
                                                            @lang('admin::app.sales.refunds.create.tax-amount')
                                                        </p>

                                                        @if ($order->base_discount_amount > 0)
                                                            <p class="text-gray-600 dark:text-gray-300"> 
                                                                @lang('admin::app.sales.refunds.create.discount-amount')
                                                            </p>
                                                        @endif

                                                        <p class="text-gray-600 dark:text-gray-300 font-semibold">
                                                            @lang('admin::app.sales.refunds.create.grand-total')
                                                        </p>
                                                    </div>

                                                    <div class="flex flex-col gap-y-1.5">
                                                        <p class="text-gray-600 dark:text-gray-300">
                                                            {{ core()->formatBasePrice($item->base_price) }}
                                                        </p>

                                                        <p class="text-gray-600 dark:text-gray-300">
                                                            {{ core()->formatBasePrice($item->base_total) }} 
                                                        </p>

                                                        <p class="text-gray-600 dark:text-gray-300"> 
                                                            {{ core()->formatBasePrice($item->base_tax_amount) }} 
                                                        </p>

                                                        @if ($order->base_discount_amount > 0)
                                                            <p class="text-gray-600 dark:text-gray-300"> 
                                                                {{ core()->formatBasePrice($item->base_discount_amount) }}
                                                            </p>
                                                        @endif 

                                                        <p class="text-gray-600 dark:text-gray-300 font-semibold"> 
                                                            {{ core()->formatBasePrice($item->base_total + $item->base_tax_amount - $item->base_discount_amount - $order->giftcard_amount) }} 
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            </div>

                            <div
                                v-if="refund.summary"
                                class="grid grid-cols-3 gap-x-5 mt-2.5"
                            >
                                <!-- Refund Shipping -->
                                <x-admin::form.control-group>
                                    <x-admin::form.control-group.label class="required">
                                        @lang('admin::app.sales.refunds.create.refund-shipping')
                                    </x-admin::form.control-group.label>

                                    <x-admin::form.control-group.control
                                        type="text"
                                        id="refund[shipping]"
                                        name="refund[shipping]" 
                                        :rules="'required|min_value:0|max_value:' . $order->base_shipping_invoiced - $order->base_shipping_refunded"
                                        v-model="refund.summary.shipping.price"
                                        :label="trans('admin::app.sales.refunds.create.refund-shipping')"
                                    />

                                    <x-admin::form.control-group.error control-name="refund[shipping]" />
                                </x-admin::form.control-group>

                                <!-- Adjustment Refund -->
                                <x-admin::form.control-group>
                                    <x-admin::form.control-group.label class="required">
                                        @lang('admin::app.sales.refunds.create.adjustment-refund')
                                    </x-admin::form.control-group.label>

                                    <x-admin::form.control-group.control
                                        type="text"
                                        id="refund[adjustment_refund]" 
                                        name="refund[adjustment_refund]"
                                        value="0"
                                        rules="required|min_value:0"
                                        :label="trans('admin::app.sales.refunds.create.adjustment-refund')"
                                    />

                                    <x-admin::form.control-group.error control-name="refund[adjustment_refund]" />
                                </x-admin::form.control-group>

                                <!-- Adjustment Fee -->
                                <x-admin::form.control-group>
                                    <x-admin::form.control-group.label class="required">
                                        @lang('admin::app.sales.refunds.create.adjustment-fee')
                                    </x-admin::form.control-group.label>

                                    <x-admin::form.control-group.control
                                        type="text"
                                        id="refund[adjustment_fee]" 
                                        name="refund[adjustment_fee]"
                                        rules="required|min_value:0"
                                        value="0"
                                        :label="trans('admin::app.sales.refunds.create.adjustment-fee')"
                                    />

                                    <x-admin::form.control-group.error control-name="refund[adjustment_fee]" />
                                </x-admin::form.control-group>
                            </div>

                            <!-- Order Summary -->
                            <div class="flex w-full gap-5 justify-end">
                                <div class="flex flex-col gap-y-1.5">
                                    <p class="text-gray-600 dark:text-gray-300">
                                        @lang('admin::app.sales.refunds.create.subtotal')
                                    </p>

                                    <p class="text-gray-600 dark:text-gray-300"> 
                                        @lang('admin::app.sales.refunds.create.discount-amount')
                                    </p>

                                    <p class="text-gray-600 dark:text-gray-300">
                                        @lang('admin::app.sales.refunds.create.tax-amount')
                                    </p>

                                    <p class="text-gray-600 dark:text-gray-300">
                                        @lang('giftcard::app.giftcard.giftcard_amount')
                                    </p>

                                    <p class="text-gray-600 dark:text-gray-300">
                                        <p class="dark:text-black-300 !leading-5 text-sm">
                                            @lang('giftcard::app.giftcard.giftcard_number')
                                        </p>                                        
                                    </p>

                                    <p class="text-gray-600 dark:text-gray-300 font-semibold">
                                        @lang('admin::app.sales.refunds.create.grand-total')
                                    </p>
                                </div>

                                <div class="flex flex-col gap-y-1.5">
                                    <p
                                        class="text-gray-600 dark:text-gray-300"
                                        {{-- v-text="refund.summary.subtotal.formatted_price" --}}
                                    > {{ core()->formatBasePrice($order->sub_total) }}
                                    </p>

                                    <p
                                        class="text-gray-600 dark:text-gray-300"
                                        {{-- v-text="refund.summary.discount.formatted_price" --}}
                                    > {{ core()->formatBasePrice($order->discount_amount) }}
                                    </p>

                                    <p
                                        class="text-gray-600 dark:text-gray-300"
                                        {{-- v-text="refund.summary.tax.formatted_price" --}}
                                    > {{ core()->formatBasePrice($order->tax_amount) }}
                                    </p>

                                    <p
                                        class="text-gray-600 dark:text-gray-300"
                                    >  - {{ core()->formatBasePrice($order->giftcard_amount) }}
                                    </p>

                                    <p
                                        class="text-gray-600 dark:text-gray-300"
                                    > {{ $order->giftcard_number }}
                                    </p>

                                    <p
                                        class="text-gray-600 dark:text-gray-300"
                                        {{-- v-text="refund.summary.grand_total.formatted_price" --}}
                                    > {{ core()->formatBasePrice($order->grand_total) }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </x-slot>
                </x-admin::drawer>
            </x-admin::form>
        </div>
    </script>

    <script type="module">
        app.component('v-create-refund', {
            template: '#v-create-refund-template',

            data() {
                return {
                    refund: {
                        items: {},

                        summary: null
                    }
                };
            },

            mounted() {
                @foreach ($order->items as $item)
                    this.refund.items[{{$item->id}}] = {{ $item->qty_to_refund }};
                @endforeach
                
                this.updateQty();
            },

            methods: {
                updateQty() {
                    this.$axios.post("{{ route('admin.sales.refunds.update_qty', $order->id) }}", this.refund.items)
                        .then((response) => {

                            if (! response.data) {
                                this.$emitter.emit('add-flash', { type: 'warning', message: "@lang('admin::app.sales.refunds.invalid-qty')" });
                            } else {
                                this.refund.summary = response.data;
                            }
                        })
                        .catch((error) => {})
                }
            },
        });
    </script>
@endPushOnce
