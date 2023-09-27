{!! view_render_event('bagisto.admin.catalog.product.edit.form.inventories.controls.before', ['product' => $product]) !!}

<v-inventories>
    {{-- Panel Content --}}
    <div class="mb-[20px] text-[14px] text-gray-600 dark:text-gray-300">
        <div class="flex  items-center relative mb-[10px]">
            <span class="inline-block ltr:mr-[5px] rtl:ml-[5px] p-[5px] bg-yellow-500 rounded-full"></span>

            @lang('admin::app.catalog.products.edit.inventories.pending-ordered-qty', [
                'qty' => $product->ordered_inventories->pluck('qty')->first() ?? 0,
            ])
            
            <i class="icon-information text-[18px] ltr:ml-[10px] rtl:mr-[10px] font-bold text-white rounded-full bg-gray-700 transition-all hover:bg-gray-800 peer"></i>

            <div class="hidden absolute bottom-[25px] p-[10px] bg-black opacity-80 rounded-[8px] text-[14px] italic text-white peer-hover:block">
                @lang('admin::app.catalog.products.edit.inventories.pending-ordered-qty-info')
            </div>
        </div>
    </div>

    @foreach ($inventorySources as $inventorySource)
        @php
            $qty = old('inventories[' . $inventorySource->id . ']')
                ?: ($product->inventories->where('inventory_source_id', $inventorySource->id)->pluck('qty')->first() ?? 0);
        @endphp

        <x-admin::form.control-group>
            <x-admin::form.control-group.label>
                {{ $inventorySource->name }}
            </x-admin::form.control-group.label>

            <x-admin::form.control-group.control
                type="text"
                :name="'inventories[' . $inventorySource->id . ']'"
                :rules="'numeric|min:0'"
                :label="$inventorySource->name"
                :value="$qty"
            >
            </x-admin::form.control-group.control>

            <x-admin::form.control-group.error :control-name="'inventories[' . $inventorySource->id . ']'"></x-admin::form.control-group.error>
        </x-admin::form.control-group>
    @endforeach
</v-inventories>

{!! view_render_event('bagisto.admin.catalog.product.edit.form.inventories.controls.after', ['product' => $product]) !!}


@pushOnce('scripts')
    <script type="text/x-template" id="v-inventories-template">
        <div v-show="manageStock">
            <slot></slot>
        </div>
    </script>

    <script type="module">
        app.component('v-inventories', {
            template: '#v-inventories-template',

            data() {
                return {
                    manageStock: "{{ (boolean) $product->manage_stock }}",
                }
            },

            mounted: function() {
                let self = this;

                document.getElementById('manage_stock').addEventListener('change', function(e) {
                    self.manageStock = e.target.checked;
                });
            }
        });
    </script>
@endpushOnce