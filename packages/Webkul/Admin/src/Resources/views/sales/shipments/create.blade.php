<v-create-shipment>
    <div
        class="inline-flex gap-x-[8px] items-center justify-between w-full max-w-max px-[4px] py-[6px] text-gray-600 font-semibold text-center cursor-pointer transition-all hover:bg-gray-200 hover:rounded-[6px]"
    >
        <span class="icon-ship text-[24px]"></span> 

        @lang('admin::app.sales.orders.view.ship')     
    </div>
</v-create-shipment>

@pushOnce('scripts')
    <script type="text/x-template" id="v-create-shipment-template">
    <div
        class="inline-flex gap-x-[8px] items-center justify-between w-full max-w-max px-[4px] py-[6px] text-gray-600 font-semibold text-center cursor-pointer transition-all hover:bg-gray-200 hover:rounded-[6px]"
        @click="$refs.shipment.open()"
    >
        <span class="icon-ship text-[24px]"></span> 

        @lang('admin::app.sales.orders.view.ship')     
    </div>

    <!-- Shipment Create Drawer -->
    <x-admin::form  
        method="POST"
        :action="route('admin.sales.shipments.store', $order->id)"
    >
        <x-admin::drawer ref="shipment">
            <!-- Drawer Header -->
            <x-slot:header>
                <div class="grid gap-[12px]">
                    <div class="flex justify-between items-center">
                        <p class="text-[20px] font-medium">
                            @lang('admin::app.sales.shipments.create.title')
                        </p>

                        <button
                            type="submit"
                            class="mr-[45px] px-[12px] py-[6px] bg-blue-600 border border-blue-700 rounded-[6px] text-gray-50 font-semibold cursor-pointer"
                        >
                            @lang('admin::app.sales.shipments.create.create-btn')
                        </button>
                    </div>
                </div>
            </x-slot:header>

            <!-- Drawer Content -->
            <x-slot:content class="!p-0">
                <div class="grid">
                    <div class="p-[16px] !pt-0">
                        <div class="grid grid-cols-2 gap-x-[20px]">
                            <x-admin::form.control-group>
                                <x-admin::form.control-group.label>
                                    @lang('admin::app.sales.shipments.create.carrier-name')
                                </x-admin::form.control-group.label>

                                <x-admin::form.control-group.control
                                    type="text"
                                    name="shipment[carrier_title]" 
                                    id="shipment[carrier_title]" 
                                    :label="trans('admin::app.sales.shipments.create.carrier-name')"
                                    :placeholder="trans('admin::app.sales.shipments.create.carrier-name')"
                                >
                                </x-admin::form.control-group.control>

                                <x-admin::form.control-group.error
                                    control-name="carrier_name"
                                >
                                </x-admin::form.control-group.error>
                            </x-admin::form.control-group>

                            <x-admin::form.control-group>
                                <x-admin::form.control-group.label>
                                    @lang('admin::app.sales.shipments.create.tracking-number')
                                </x-admin::form.control-group.label>

                                <x-admin::form.control-group.control
                                    type="text"
                                    name="shipment[track_number]"
                                    id="shipment[track_number]"
                                    :label="trans('admin::app.sales.shipments.create.tracking-number')"
                                    :placeholder="trans('admin::app.sales.shipments.create.tracking-number')"
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
                                @lang('admin::app.sales.shipments.create.source')
                            </x-admin::form.control-group.label>

                            <x-admin::form.control-group.control
                                type="select"
                                name="shipment[source]" 
                                id="shipment[source]" 
                                rules="required"
                                :label="trans('admin::app.sales.shipments.create.source')"
                                :placeholder="trans('admin::app.sales.shipments.create.source')"
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
        
                                                <p class="text-gray-600">@lang('admin::app.sales.orders.view.sku') - {{ $item->sku }}</p>
        
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
                                        @foreach ($order->channel->inventory_sources as $inventorySource)
                                            <div class="grid gap-[10px]">
                                                <p class="text-[16x] text-gray-800 font-semibold">
                                                    {{ $inventorySource->name }}
                                                </p>

                                                <p class="text-gray-600">
                                                    @lang('admin::app.sales.shipments.create.qty-available') :                  

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
                                                    @lang('admin::app.sales.shipments.create.qty-to-ship')
                                                </x-admin::form.control-group.label>

                                                <x-admin::form.control-group class="!mb-0">
                                                    <x-admin::form.control-group.control
                                                        type="text"
                                                        :name="$inputName" 
                                                        :id="$inputName" 
                                                        :value="$item->qty_to_ship"
                                                        :rules="'required|numeric|min_value:0|max_value:' . $item->qty_ordered"
                                                        class="!w-[100px]"
                                                        :label="trans('admin::app.sales.shipments.create.qty-to-ship')"
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