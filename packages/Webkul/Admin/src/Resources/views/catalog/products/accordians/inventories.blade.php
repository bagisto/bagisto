{!! view_render_event('bagisto.admin.catalog.product.edit_form_accordian.inventories.before', ['product' => $product]) !!}

<accordian :title="'{{ __('admin::app.catalog.products.inventories') }}'" :active="false">
    <div slot="body">

        {!! view_render_event('bagisto.admin.catalog.product.edit_form_accordian.inventories.controls.before', ['product' => $product]) !!}

        @foreach ($inventorySources as $inventorySource)
            <?php

                $qty = 0;
                foreach ($product->inventories as $inventory) {
                    if ($inventory->inventory_source_id == $inventorySource->id) {
                        $qty = $inventory->qty;
                        break;
                    }
                }

                $qty = old('inventories[' . $inventorySource->id . ']') ?: $qty;

            ?>
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