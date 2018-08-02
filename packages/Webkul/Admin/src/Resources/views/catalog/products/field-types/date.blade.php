<div class="control-group" :class="[errors.has('{{ $attribute->code }}') ? 'has-error' : '']">
    <label for="{{ $attribute->code }}" {{ $attribute->is_required ? 'class=required' : '' }}>
        {{ $attribute->admin_name }}
    </label>

    <date>
        <input type="text" name="{{ $attribute->code }}" class="control" v-validate="'required'" value="{{ $product[$attribute->code]}}" data-input>
    </date>

    <span class="control-error" v-if="errors.has('{{ $attribute->code }}')">@{{ errors.first('{!! $attribute->code !!}') }}</span>
</div>