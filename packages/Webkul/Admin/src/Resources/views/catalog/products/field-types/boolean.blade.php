<div class="control-group" :class="[errors.has('{{ $attribute->code }}') ? 'has-error' : '']">
    <label for="{{ $attribute->code }}" {{ $attribute->is_required ? 'class=required' : '' }}>
        {{ $attribute->admin_name }}
    </label>

    <select v-validate="'{{$validations}}'" class="control" id="{{ $attribute->code }}" name="{{ $attribute->code }}">
        <option value="0" {{ !$product[$attribute->code] ? 'selected' : ''}}>
            {{ __('admin::app.catalog.products.no') }}
        </option>
        <option value="1" {{ $product[$attribute->code] ? 'selected' : ''}}>
            {{ __('admin::app.catalog.products.yes') }}
        </option>
    </select>

    <span class="control-error" v-if="errors.has('{{ $attribute->code }}')">@{{ errors.first('{!! $attribute->code !!}') }}</span>
</div>