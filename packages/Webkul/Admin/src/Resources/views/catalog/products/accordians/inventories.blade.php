{!! view_render_event('bagisto.admin.catalog.product.edit_form_accordian.inventories.before', ['product' => $product]) !!}

<accordian title="{{ __('admin::app.catalog.products.inventories') }}" :active="false">
    <div slot="body">

        {!! view_render_event('bagisto.admin.catalog.product.edit_form_accordian.inventories.controls.before', ['product' => $product]) !!}

        <div class="control-group">
            @php
                $orderedQty = $product->ordered_inventories->pluck('qty')->first() ?? 0;
            @endphp

            <div style="margin-bottom: 10px">
                <span class="badge badge-sm badge-warning" style="display: inline-block; padding: 5px;"></span>

                {{ __('admin::app.catalog.products.pending-ordered-qty', ['qty' => $orderedQty]) }}
            </div>

            <span class="control-info">{{ __('admin::app.catalog.products.pending-ordered-qty-info') }}</span>
        </div>

        @foreach ($inventorySources as $inventorySource)
            @php
                $qty = old('inventories[' . $inventorySource->id . ']')
                    ?: (
                        $product->inventories->where('inventory_source_id', $inventorySource->id)->pluck('qty')->first()
                        ?? 0
                    );
            @endphp

            <div class="control-group" :class="[errors.has('inventories[{{ $inventorySource->id }}]') ? 'has-error' : '']">
                <label>{{ $inventorySource->name }}</label>

                <input type="text" v-validate="'numeric|min:0'" name="inventories[{{ $inventorySource->id }}]" class="control" value="{{ $qty }}" data-vv-as="&quot;{{ $inventorySource->name }}&quot;"/>
                
                <span class="control-error" v-if="errors.has('inventories[{{ $inventorySource->id }}]')">@{{ errors.first('inventories[{!! $inventorySource->id !!}]') }}</span>
            </div>
        
        @endforeach

        {!! view_render_event('bagisto.admin.catalog.product.edit_form_accordian.inventories.controls.after', ['product' => $product]) !!}

    </div>
</accordian>

{!! view_render_event('bagisto.admin.catalog.product.edit_form_accordian.inventories.after', ['product' => $product]) !!}