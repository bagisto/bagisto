<!-- Refund Vue Component -->
<v-create-refund>
    <div class="transparent-button px-1 py-1.5 hover:bg-gray-200 dark:text-white dark:hover:bg-gray-800">
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
            <div
                class="transparent-button px-1 py-1.5 hover:bg-gray-200 dark:text-white dark:hover:bg-gray-800"
                @click="$refs.refund.open()"
            >
                <span
                    class="icon-cancel text-2xl"
                    role="presentation"
                    tabindex="0"
                >
                </span>

                @lang('admin::app.sales.orders.view.refund')
            </div>

            <!-- refund Create Drawer -->
            <x-admin::form
                method="POST"
                :action="route('admin.sales.refunds.store', $order->id)"
                ref="refundForm"
            >
                <x-admin::drawer ref="refund">
                    <!-- Drawer Header -->
                    <x-slot:header>
                        <div class="grid h-8 gap-3">
                            <div class="flex items-center justify-between">
                                <p class="text-xl font-medium dark:text-white">
                                    @lang('admin::app.sales.refunds.create.title')
                                </p>

                                <div class="flex gap-x-2.5">
                                    <!-- Update Quantity Button -->

                                    @if (bouncer()->hasPermission('sales.refunds.create'))
                                        <div 
                                            class="transparent-button hover:bg-gray-200 dark:hover:bg-gray-800"
                                            @click="updateTotals"
                                        >
                                            @lang('admin::app.sales.refunds.create.update-totals-btn')
                                        </div>

                                        <!-- Refund Submit Button -->
                                        <button
                                            type="submit"
                                            class="primary-button ltr:mr-11 rtl:ml-11"
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
                                        <div class="flex justify-between gap-2.5 py-4">
                                            <div class="flex gap-2.5">
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
                                                    <!-- Item Additional Attributes -->
                                                    <p class="break-all text-base font-semibold text-gray-800 dark:text-white">
                                                        {{ $item->name }}
                                                    </p>
                
                                                    <div class="flex flex-col place-items-start gap-1.5">
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

                                        <div class="justify-between gap-2.5 border-b border-slate-300 pb-4 dark:border-gray-800">
                                            <!-- Information -->
                                            <div class="flex justify-between">
                                                <!-- Quantity to Refund -->
                                                <div>
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
                                                <div class="item flex w-full justify-end gap-5">
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

                                                        <p class="font-semibold text-gray-600 dark:text-gray-300">
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

                                                        <p class="font-semibold text-gray-600 dark:text-gray-300">
                                                            {{ core()->formatBasePrice($item->base_total + $item->base_tax_amount - $item->base_discount_amount) }}
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            </div>

                            <div
                                v-if="totals"
                                class="mt-2.5 grid grid-cols-3 gap-x-5"
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
                                        v-model="refund.shipping"
                                        :rules="'required|min_value:0|max_value:' . $order->base_shipping_invoiced - $order->base_shipping_refunded"
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
                                        v-model="refund.adjustment_refund"
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
                                        v-model="refund.adjustment_fee"
                                        rules="required|min_value:0"
                                        :label="trans('admin::app.sales.refunds.create.adjustment-fee')"
                                    />

                                    <x-admin::form.control-group.error control-name="refund[adjustment_fee]" />
                                </x-admin::form.control-group>
                            </div>

                            <!-- Order Summary -->
                            <div class="flex w-full justify-end gap-5">
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

                                    <p class="font-semibold text-gray-600 dark:text-gray-300">
                                        @lang('admin::app.sales.refunds.create.grand-total')
                                    </p>
                                </div>

                                <div class="flex flex-col gap-y-1.5">
                                    <p class="text-gray-600 dark:text-gray-300">
                                        @{{ totals.subtotal.formatted_price }}
                                    </p>

                                    <p class="text-gray-600 dark:text-gray-300">
                                        @{{ totals.discount.formatted_price }}
                                    </p>

                                    <p class="text-gray-600 dark:text-gray-300">
                                        @{{ totals.tax.formatted_price }}
                                    </p>

                                    <p class="text-gray-600 dark:text-gray-300">
                                        @{{ totals.grand_total.formatted_price }}
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

                        shipping: "{{ $order->base_shipping_invoiced - $order->base_shipping_refunded - $order->base_shipping_discount_amount }}",

                        adjustment_refund: 0,
                        
                        adjustment_fee: 0,
                    },

                    totals: null,
                };
            },

            mounted() {
                @foreach ($order->items as $item)
                    this.refund.items[{{$item->id}}] = {{ $item->qty_to_refund }};
                @endforeach
                
                this.updateTotals();
            },

            methods: {
                updateTotals() {
                    var self = this;
                    
                    this.$axios.post("{{ route('admin.sales.refunds.update_totals', $order->id) }}", this.refund)
                        .then((response) => {
                            this.totals = response.data;
                        })
                        .catch((error) => {
                            self.$emitter.emit('add-flash', { type: 'warning', message: error.response.data.message });
                        })
                }
            },
        });
    </script>
@endPushOnce
