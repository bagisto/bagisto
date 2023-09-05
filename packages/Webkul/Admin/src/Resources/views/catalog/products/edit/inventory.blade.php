{!! view_render_event('bagisto.admin.catalog.product.edit.form.inventories.before', ['product' => $product]) !!}

{{-- Panel --}}
<div class="p-[16px] bg-white rounded-[4px] box-shadow">
    {{-- Panel Header --}}
    <p class="flex justify-between text-[16px] text-gray-800 font-semibold mb-[16px]">
        @lang('admin::app.catalog.products.edit.inventories.title')
    </p>

    {!! view_render_event('bagisto.admin.catalog.product.edit.form.inventories.controls.before', ['product' => $product]) !!}

    {{-- Panel Content --}}
    <div class="mb-[20px] text-[14px] text-gray-600">
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

    {!! view_render_event('bagisto.admin.catalog.product.edit.form.inventories.controls.after', ['product' => $product]) !!}
</div>

{!! view_render_event('bagisto.admin.catalog.product.edit.form.inventories.after', ['product' => $product]) !!}