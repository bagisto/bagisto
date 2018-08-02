<div class="control-group" :class="[errors.has('{{ $attribute->code }}') ? 'has-error' : '']">
    <label for="{{ $attribute->code }}" {{ $attribute->is_required ? 'class=required' : '' }}>
        {{ $attribute->admin_name }}
    </label>

    <textarea v-validate="'{{$validations}}'" class="control" id="{{ $attribute->code }}" name="{{ $attribute->code }}">
        {{ $product[$attribute->code]}}
    </textarea>

    <span class="control-error" v-if="errors.has('{{ $attribute->code }}')">@{{ errors.first('{!! $attribute->code !!}') }}</span>
</div>