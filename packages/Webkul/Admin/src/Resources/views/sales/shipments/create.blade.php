<!-- Shipment Vue Components -->
<v-create-shipment>
    <div
        class="inline-flex gap-x-2 items-center justify-between w-full max-w-max px-1 py-1.5 text-gray-600 dark:text-gray-300 font-semibold text-center cursor-pointer transition-all hover:bg-gray-200 dark:hover:bg-gray-800 hover:rounded-md"
    >
        <span class="icon-ship text-2xl"></span> 

        @lang('admin::app.sales.orders.view.ship')     
    </div>
</v-create-shipment>

@pushOnce('scripts')
    <script
        type="text/x-template"
        id="v-create-shipment-template"
    >
        <div>
            <div
                class="inline-flex gap-x-2 items-center justify-between w-full max-w-max px-1 py-1.5 text-gray-600 dark:text-gray-300 font-semibold text-center cursor-pointer transition-all hover:bg-gray-200 dark:hover:bg-gray-800 hover:rounded-md"
                @click="$refs.shipment.open()"
            >
                <span
                    class="icon-ship text-2xl"
                    role="button"
                    tabindex="0"
                >
                </span> 

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
                        <div class="grid gap-3 h-8">
                            <div class="flex justify-between items-center">
                                <p class="text-xl font-medium dark:text-white">
                                    @lang('admin::app.sales.shipments.create.title')
                                </p>

                                @if (bouncer()->hasPermission('sales.invoices.create'))
                                    <button
                                        type="submit"
                                        class="ltr:mr-11 rtl:ml-11 primary-button"
                                    >
                                        @lang('admin::app.sales.shipments.create.create-btn')
                                    </button>
                                @endif
                            </div>
                        </div>
                    </x-slot>

                    <!-- Drawer Content -->
                    <x-slot:content class="!p-0">
                        <div class="grid p-4 pt-2">
                            <div class="grid grid-cols-2 gap-x-5">
                                <!-- Carrier Name -->
                                <x-admin::form.control-group>
                                    <x-admin::form.control-group.label>
                                        @lang('admin::app.sales.shipments.create.carrier-name')
                                    </x-admin::form.control-group.label>

                                    <x-admin::form.control-group.control
                                        type="text"
                                        id="shipment[carrier_title]" 
                                        name="shipment[carrier_title]" 
                                        :label="trans('admin::app.sales.shipments.create.carrier-name')"
                                        :placeholder="trans('admin::app.sales.shipments.create.carrier-name')"
                                    />

                                    <x-admin::form.control-group.error control-name="carrier_name" />
                                </x-admin::form.control-group>

                                <!-- Tracking Number -->
                                <x-admin::form.control-group>
                                    <x-admin::form.control-group.label>
                                        @lang('admin::app.sales.shipments.create.tracking-number')
                                    </x-admin::form.control-group.label>

                                    <x-admin::form.control-group.control
                                        type="text"
                                        id="shipment[track_number]"
                                        name="shipment[track_number]"
                                        :label="trans('admin::app.sales.shipments.create.tracking-number')"
                                        :placeholder="trans('admin::app.sales.shipments.create.tracking-number')"
                                    />

                                    <x-admin::form.control-group.error control-name="shipment[track_number]" />
                                </x-admin::form.control-group>
                            </div>
                            
                            <!-- Resource -->
                            <x-admin::form.control-group>
                                <x-admin::form.control-group.label class="required">
                                    @lang('admin::app.sales.shipments.create.source')
                                </x-admin::form.control-group.label>

                                <x-admin::form.control-group.control
                                    type="select"
                                    id="shipment[source]" 
                                    name="shipment[source]" 
                                    rules="required"
                                    v-model="source"
                                    :label="trans('admin::app.sales.shipments.create.source')"
                                    :placeholder="trans('admin::app.sales.shipments.create.source')"
                                    @change="onSourceChange"
                                >
                                    @foreach ($order->channel->inventory_sources as $inventorySource)
                                        <option value="{{ $inventorySource->id }}">
                                            {{ $inventorySource->name }}
                                        </option>
                                    @endforeach
                                </x-admin::form.control-group.control>

                                <x-admin::form.control-group.error control-name="shipment[source]" />
                            </x-admin::form.control-group>

                            <div class="grid">
                                <!-- Item Listing -->
                                @foreach ($order->items as $item)
                                    @if (
                                        $item->qty_to_ship > 0
                                        && $item->product
                                    )
                                        <div class="flex gap-2.5 justify-between py-4">
                                            <div class="flex gap-2.5">
                                                @if ($item->product?->base_image_url)
                                                    <img
                                                        class="w-full h-[60px] max-w-[60px] max-h-[60px] relative rounded"
                                                        src="{{ $item->product?->base_image_url }}"
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
                
                                                    <div class="flex flex-col gap-1.5 place-items-start">
                                                        <p class="text-gray-600 dark:text-gray-300">
                                                            @lang('admin::app.sales.shipments.create.amount-per-unit', [
                                                                'amount' => core()->formatBasePrice($item->base_price),
                                                                'qty'    => $item->qty_ordered,
                                                            ])
                                                        </p>
                
                                                        <!--Additional Attributes -->
                                                        @if (isset($item->additional['attributes']))
                                                            <p class="text-gray-600 dark:text-gray-300">
                                                                @foreach ($item->additional['attributes'] as $attribute)
                                                                    {{ $attribute['attribute_name'] }} : {{ $attribute['option_label'] }}
                                                                @endforeach
                                                            </p>
                                                        @endif

                                                        <!-- Item SKU -->
                                                        <p class="text-gray-600 dark:text-gray-300">
                                                            @lang('admin::app.sales.shipments.create.sku', ['sku' => $item->sku])
                                                        </p>

                                                        <!--Item Status -->
                                                        <p class="text-gray-600 dark:text-gray-300">
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

                                        <!-- Information -->
                                        @foreach ($order->channel->inventory_sources as $inventorySource)
                                            <div class="flex gap-2.5 justify-between pb-2.5 mt-2.5 border-b border-slate-300 dark:border-gray-800">
                                                <div class="grid gap-2.5">
                                                    <!--Inventory Source -->
                                                    <p class="text-base text-gray-800 dark:text-white font-semibold">
                                                        {{ $inventorySource->name }}
                                                    </p>

                                                    <!-- Available Quantity -->
                                                    <p class="text-gray-600 dark:text-gray-300">
                                                        @lang('admin::app.sales.shipments.create.qty-available') :                  

                                                        @php
                                                            $product = $item->getTypeInstance()->getOrderedItem($item)->product;

                                                            $sourceQty = $product?->type == 'bundle' ? $item->qty_ordered : $product?->inventory_source_qty($inventorySource->id);
                                                        @endphp

                                                        {{ $sourceQty }}
                                                    </p>
                                                </div>

                                                <div class="flex gap-2.5 items-center">
                                                    @php
                                                        $inputName = "shipment[items][$item->id][$inventorySource->id]";
                                                    @endphp

                                                    <!-- Quantity  To Ship -->
                                                    <x-admin::form.control-group.label class="required">
                                                        @lang('admin::app.sales.shipments.create.qty-to-ship')
                                                    </x-admin::form.control-group.label>

                                                    <x-admin::form.control-group class="!mb-0">
                                                        <x-admin::form.control-group.control
                                                            type="text"
                                                            class="!w-[100px]"
                                                            :id="$inputName" 
                                                            :name="$inputName" 
                                                            :rules="'required|numeric|min_value:0|max_value:' . $item->qty_ordered"
                                                            :value="$item->qty_to_ship"
                                                            :label="trans('admin::app.sales.shipments.create.qty-to-ship')"
                                                            data-original-quantity="{{ $item->qty_to_ship }}"
                                                            ::disabled="'{{ empty($sourceQty) }}' || source != '{{ $inventorySource->id }}'"
                                                            :ref="$inputName"
                                                        />
                            
                                                        <x-admin::form.control-group.error :control-name="$inputName" />
                                                    </x-admin::form.control-group>
                                                </div>
                                            </div>
                                        @endforeach
                                    @endif    
                                @endforeach
                            </div>
                        </div>
                    </x-slot>
                </x-admin::drawer>
            </x-admin::form>
        </div>
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
                    let data = Object.assign({}, element.dataset);
                    
                    element.value = data.originalQuantity;
                });
            }
        },
    });
    </script>
@endPushOnce