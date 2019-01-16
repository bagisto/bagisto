<datetime>
    <input type="text" name="{{ $attribute->code }}" class="control" {{ $attribute->is_required ? "v-validate='required'" : '' }} value="{{  old($attribute->code) ?: $product[$attribute->code]}}" data-vv-as="&quot;{{ $attribute->admin_name }}&quot;" {{ $disabled ? 'disabled' : '' }}>
</datetime>