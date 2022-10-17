<span id="product-{{ $product->id }}-quantity">
    <a id="product-{{ $product->id }}-quantity-anchor" href="javascript:void(0);" onclick="showEditQuantityForm('{{ $product->id }}')">{{ $totalQuantity }}</a>
</span>

<span id="edit-product-{{ $product->id }}-quantity-form-block" style="display: none;">
    <form id="edit-product-{{ $product->id }}-quantity-form" action="javascript:void(0);">
        @csrf

        @method('PUT')

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

                <input
                    type="text"
                    name="inventories[{{ $inventorySource->id }}]"
                    class="control" value="{{ $qty }}"
                    onkeyup="document.getElementById('inventoryErrors{{ $product->id }}').innerHTML = ''" />
            </div>
        @endforeach

        <div class="control-group has-error">
            <span class="control-error" id="inventoryErrors{{ $product->id }}"></span>
        </div>

        <button class="btn btn-primary" onclick="saveEditQuantityForm('{{ route('admin.catalog.products.update_inventories', $product->id) }}', '{{ $product->id }}')">{{ __('admin::app.catalog.products.save') }}</button>

        <button class="btn btn-danger" onclick="cancelEditQuantityForm('{{ $product->id }}')">{{ __('admin::app.catalog.products.cancel') }}</button>
    </form>
</span>
