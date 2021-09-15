<span id="product-{{ $product->id }}-quantity">
    <a href="javascript:void(0);" onclick="showEditQuantityForm('{{ $product->id }}')">{{ $totalQuantity }}</a>
</span>

<span id="edit-product-{{ $product->id }}-quantity-form-block" style="display: none;">
    <form id="edit-product-{{ $product->id }}-quantity-form" action="javascript:void(0);">
        @csrf

        @foreach ($inventorySources as $inventorySource)
            @php
                $qty = 0;

                foreach ($product->inventories as $inventory) {
                    if ($inventory->inventory_source_id == $inventorySource->id) {
                        $qty = $inventory->qty;
                        break;
                    }
                }

                $qty = old('inventories[' . $inventorySource->id . ']') ?: $qty;
            @endphp

            <div class="control-group" :class="[errors.has('inventories[{{ $inventorySource->id }}]') ? 'has-error' : '']">
                <label>{{ $inventorySource->name }}</label>

                <input type="text" v-validate="'numeric|min:0'" name="inventories[{{ $inventorySource->id }}]" class="control" value="{{ $qty }}" data-vv-as="&quot;{{ $inventorySource->name }}&quot;"/>

                <span class="control-error" v-if="errors.has('inventories[{{ $inventorySource->id }}]')">@{{ errors.first('inventories[{!! $inventorySource->id !!}]') }}</span>
            </div>
        @endforeach

        <button class="btn btn-primary" onclick="saveEditQuantityForm('{{ $product->id }}')">Save</button>
        <button class="btn btn-danger" onclick="cancelEditQuantityForm('{{ $product->id }}')">Cancel</button>
    </form>
</span>