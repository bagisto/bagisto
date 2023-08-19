<v-create-refund></v-create-refund>

@pushOnce('scripts')
    <script type="text/x-template" id="v-create-refund-template">
    <div
        class="inline-flex gap-x-[8px] items-center justify-between w-full max-w-max px-[4px] py-[6px] text-gray-600 font-semibold text-center cursor-pointer transition-all hover:bg-gray-200 hover:rounded-[6px]"
        @click="$refs.refund.open()"
    >
        <span class="icon-cancel text-[24px]"></span> 

        @lang('admin::app.sales.orders.view.refund')     
    </div>

    <!-- refund Create Drawer -->
    <x-admin::form  
        method="POST"
        :action="route('admin.sales.refunds.store', $order->id)"
    >
        <x-admin::drawer ref="refund">
            <!-- Drawer Header -->
            <x-slot:header>
                <div class="grid gap-[12px]">
                    <div class="flex justify-between items-center">
                        <p class="text-[20px] font-medium">
                            Create Refund
                        </p>

                        <div 
                            class="px-[12px] py-[6px] text-red-600 font-semibold cursor-pointer"
                            @click="updateQty"
                        >
                            Update Quantity
                        </div>

                        <button
                            type="submit"
                            class="mr-[45px] px-[12px] py-[6px] bg-blue-600 border border-blue-700 rounded-[6px] text-gray-50 font-semibold cursor-pointer"
                        >
                            Refund
                        </button>
                    </div>
                </div>
            </x-slot:header>

            <!-- Drawer Content -->
            <x-slot:content class="!p-0">
                <div class="grid">
                    <div class="p-[16px] !pt-0">
                        <div class="grid">
                            @foreach ($order->items as $item)
                                <div class="flex gap-[10px] justify-between py-[16px]">
                                    <div class="flex gap-[10px]">
                                        @if ($item->product)
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
                                                    @lang('admin::app.sales.shipments.create.amount-per-unit', [
                                                        'amount' => core()->formatBasePrice($item->base_price),
                                                        'qty'    => $item->qty_ordered,
                                                    ])
                                                </p>
        
                                                @if (isset($item->additional['attributes']))
                                                    <p class="text-gray-600">
                                                        @foreach ($item->additional['attributes'] as $attribute)
                                                            {{ $attribute['attribute_name'] }} : {{ $attribute['option_label'] }}
                                                        @endforeach
                                                    </p>
                                                @endif
        
                                                <p class="text-gray-600">@lang('admin::app.sales.orders.view.sku') - {{ Webkul\Product\Helpers\ProductType::hasVariants($item->type) ? $item->child->sku : $item->sku }}</p>
        
                                                <p class="text-gray-600">
                                                    {{ $item->qty_ordered ? trans('admin::app.sales.shipments.create.item-ordered', ['qty_ordered' => $item->qty_ordered]) : '' }}

                                                    {{ $item->qty_invoiced ? trans('admin::app.sales.shipments.create.item-invoice', ['qty_invoiced' => $item->qty_invoiced]) : '' }}

                                                    {{ $item->qty_shipped ? trans('admin::app.sales.shipments.create.item-shipped', ['qty_shipped' => $item->qty_shipped]) : '' }}

                                                    {{ $item->qty_refunded ? trans('admin::app.sales.shipments.create.item-refunded', ['qty_refunded' => $item->qty_refunded]) : '' }}

                                                    {{ $item->qty_canceled ? trans('admin::app.sales.shipments.create.item-canceled', ['qty_canceled' => $item->qty_canceled]) : '' }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="gap-[10px] justify-between pb-[16px] border-b-[1px] border-slate-300">
                                    <!-- Information -->
                                    <div class="flex justify-between">
                                            <div class="">
                                                <x-admin::form.control-group.label class="required">
                                                    @lang('Quantity to Refund')
                                                </x-admin::form.control-group.label>

                                                <x-admin::form.control-group class="!mb-0">
                                                    <x-admin::form.control-group.control
                                                        type="text"
                                                        name="refund[items][{{ $item->id }}]"
                                                        id="refund[items][{{ $item->id }}]"
                                                        :rules="'required|numeric|min_value:0|max_value:' . $item->qty_ordered"
                                                        label="Quantity to Refund"
                                                        v-model="refund.items[{{ $item->id }}]"
                                                    >
                                                    </x-admin::form.control-group.control>
                        
                                                    <x-admin::form.control-group.error
                                                        control-name="refund[items][{{ $item->id }}]"
                                                    >
                                                    </x-admin::form.control-group.error>
                                                </x-admin::form.control-group>
                                            </div>

                                            <div class="flex w-full gap-[20px] justify-end">
                                                <div class="flex flex-col gap-y-[6px]">
                                                    <p class="text-gray-600"> Price </p>
                                                    <p class="text-gray-600"> Subtotal </p>
                                                    <p class="text-gray-600"> Tax amount </p>
                                                    @if ($order->base_discount_amount > 0)
                                                        <p class="text-gray-600"> discount amount </p>
                                                    @endif
                                                    <p class="text-gray-600 font-semibold"> Grand Total </p>

                                                </div><div class="flex flex-col gap-y-[6px]">
                                                    <p class="text-gray-600"> {{ core()->formatBasePrice($item->base_price) }} </p>
                                                    <p class="text-gray-600"> {{ core()->formatBasePrice($item->base_total) }} </p>
                                                    <p class="text-gray-600"> {{ core()->formatBasePrice($item->base_tax_amount) }} </p>
                                                    <p class="text-gray-600"> 
                                                        @if ($order->base_discount_amount > 0)
                                                            {{ core()->formatBasePrice($item->base_discount_amount) }}
                                                        @endif 
                                                    </p>
                                                    <p class="text-gray-600"> {{ core()->formatBasePrice($item->base_total + $item->base_tax_amount - $item->base_discount_amount) }} </p>
                                                </div>
                                            </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div v-if="refund.summary" class="grid grid-cols-3 gap-x-[20px] mt-[10px]">
                            <x-admin::form.control-group>
                                <x-admin::form.control-group.label class="required">
                                    Refund Shipping
                                </x-admin::form.control-group.label>

                                <x-admin::form.control-group.control
                                    type="text"
                                    name="refund[shipping]" 
                                    id="refund[shipping]"
                                    :rules="'required|min_value:0|max_value:' . $order->base_shipping_invoiced - $order->base_shipping_refunded"
                                    v-model="refund.summary.shipping.price"
                                    label="Refund Shipping"
                                >
                                </x-admin::form.control-group.control>

                                <x-admin::form.control-group.error
                                    control-name="refund[shipping]"
                                >
                                </x-admin::form.control-group.error>
                            </x-admin::form.control-group>

                            <x-admin::form.control-group>
                                <x-admin::form.control-group.label class="required">
                                   Adjustment Refund
                                </x-admin::form.control-group.label>

                                <x-admin::form.control-group.control
                                    type="text"
                                    name="refund[adjustment_refund]"
                                    id="refund[adjustment_refund]" 
                                    value="0"
                                    rules="required|min_value:0"
                                    label="Adjustment Refund"
                                >
                                </x-admin::form.control-group.control>

                                <x-admin::form.control-group.error
                                    control-name="refund[adjustment_refund]"
                                >
                                </x-admin::form.control-group.error>
                            </x-admin::form.control-group>

                            <x-admin::form.control-group>
                                <x-admin::form.control-group.label class="required">
                                    Adjustment Fee
                                </x-admin::form.control-group.label>

                                <x-admin::form.control-group.control
                                    type="text"
                                    name="refund[adjustment_fee]"
                                    id="refund[adjustment_fee]" 
                                    value="0"
                                    rules="required|min_value:0"
                                    label="Adjustment Fee"
                                >
                                </x-admin::form.control-group.control>

                                <x-admin::form.control-group.error
                                    control-name="refund[adjustment_fee]"
                                >
                                </x-admin::form.control-group.error>
                            </x-admin::form.control-group>
                        </div>
                        <div class="flex w-full gap-[20px] justify-end">
                            <div class="flex flex-col gap-y-[6px]">
                                <p class="text-gray-600"> Subtotal </p>
                                <p class="text-gray-600"> Discount </p>
                                <p class="text-gray-600"> Tax Amount </p>
                                <p class="text-gray-600 font-semibold"> Grand Total </p>

                            </div><div class="flex flex-col gap-y-[6px]">
                                <p class="text-gray-600"> @{{ refund.summary.subtotal.formatted_price }} </p>
                                <p class="text-gray-600"> @{{ refund.summary.discount.formatted_price }} </p>
                                <p class="text-gray-600"> @{{ refund.summary.tax.formatted_price }} </p>
                                <p class="text-gray-600"> @{{ refund.summary.grand_total.formatted_price }} </p>
                            </div>
                        </div>
                    </div>
                </div>
            </x-slot:content>
        </x-admin::drawer>
    </x-admin::form>
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

            mounted(){
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
                                alert('admin::app.sales.refunds.invalid-qty');

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
