<select v-validate="'{{$validations}}'" class="control" id="{{ $attribute->code }}" name="{{ $attribute->code }}" {{ $disabled ? 'disabled' : '' }} data-vv-as="&quot;{{ $attribute->admin_name }}&quot;">

    <?php $selectedOption = old($attribute->code) ?: $product[$attribute->code] ?>

    <option value="0" {{ $selectedOption ? '' : 'selected'}}>
        {{ $attribute->code == 'status' ? __('admin::app.catalog.products.disabled') : __('admin::app.catalog.products.no') }}
    </option>
    <option value="1" {{ $selectedOption ? 'selected' : ''}}>
        {{ $attribute->code == 'status' ? __('admin::app.catalog.products.enabled') : __('admin::app.catalog.products.yes') }}
    </option>
    
</select>