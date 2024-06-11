{!! view_render_event('bagisto.admin.catalog.product.edit.form.inventories.controls.before', ['product' => $product]) !!}

<v-inventories>
    <!-- Panel Content -->
    <div class="mb-5 text-sm text-gray-600 dark:text-gray-300">
        <div class="relative mb-2.5 flex items-center">
            <span class="inline-block rounded-full bg-yellow-500 p-1.5 ltr:mr-1.5 rtl:ml-1.5"></span>

            @lang('admin::app.catalog.products.edit.inventories.pending-ordered-qty', [
                'qty' => $product->ordered_inventories->pluck('qty')->first() ?? 0,
            ])
            
            <i class="icon-information peer rounded-full bg-gray-700 text-lg font-bold text-white transition-all hover:bg-gray-800 ltr:ml-2.5 rtl:mr-2.5"></i>

            <div class="absolute bottom-6 hidden rounded-lg bg-black p-2.5 text-sm italic text-white opacity-80 peer-hover:block">
                @lang('admin::app.catalog.products.edit.inventories.pending-ordered-qty-info')
            </div>
        </div>
    </div>

    @foreach (app(\Webkul\Inventory\Repositories\InventorySourceRepository::class)->findWhere(['status' => 1]) as $inventorySource)
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