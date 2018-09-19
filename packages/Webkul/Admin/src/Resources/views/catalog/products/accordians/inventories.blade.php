@if ($product->type != 'configurable')
<accordian :title="'{{ __($accordian['name']) }}'" :active="true">
    <div slot="body">

        @foreach ($inventorySources as $inventorySource)
            <?php

                $qty = 0;
                foreach ($product->inventories as $inventory) {
                    if($inventory->inventory_source_id == $inventorySource->id) {
                        $qty = $inventory->qty;
                        break;
                    }
                }

                $qty = old('inventories[' . $inventorySource->id . ']') ?: $qty;

            ?>
            <div class="control-group" :class="[errors.has('inventories[{{ $inventorySource->id }}]') ? 'has-error' : '']">
                <label>{{ $inventorySource->name }}</label>

                <input type="text" v-validate="'numeric|min:0'" name="inventories[{{ $inventorySource->id }}]" class="control" value="{{ $qty }}"/>
                
                <span class="control-error" v-if="errors.has('inventories[{{ $inventorySource->id }}]')">@{{ errors.first('inventories[{!! $inventorySource->id !!}]') }}</span>
            </div>
        
        @endforeach

    </div>
</accordian>
@endif