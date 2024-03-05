{!! view_render_event('bagisto.admin.catalog.product.edit.form.inventories.controls.before', ['product' => $product]) !!}

<v-inventories>
    <!-- Panel Content -->
    <div class="mb-5 text-sm text-gray-600 dark:text-gray-300">
        <div class="flex items-center relative mb-2.5">
            <span class="inline-block ltr:mr-1.5 rtl:ml-1.5 p-1.5 bg-yellow-500 rounded-full"></span>

            @lang('admin::app.catalog.products.edit.inventories.pending-ordered-qty', [
                'qty' => $product->ordered_inventories->pluck('qty')->first() ?? 0,
            ])
            
            <i class="icon-information text-lg ltr:ml-2.5 rtl:mr-2.5 font-bold text-white rounded-full bg-gray-700 transition-all hover:bg-gray-800 peer"></i>

            <div class="hidden absolute bottom-6 p-2.5 bg-black opacity-80 rounded-lg text-sm italic text-white peer-hover:block">
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
                :value="$qty"
                :label="$inventorySource->name"
            />

            <x-admin::form.control-group.error :control-name="'inventories[' . $inventorySource->id . ']'" />
        </x-admin::form.control-group>
    @endforeach
</v-inventories>

{!! view_render_event('bagisto.admin.catalog.product.edit.form.inventories.controls.after', ['product' => $product]) !!}

@pushOnce('scripts')
    <script
        type="text/x-template"
        id="v-inventories-template"
    >
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