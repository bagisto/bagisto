<datetime>
    <input type="text" name="{{ $attribute->code }}" class="control" {{ $attribute->is_required ? "v-validate='required'" : '' }} value="{{  old($attribute->code) ?: $product[$attribute->code]}}"  {{ $disabled ? 'disabled' : '' }}>
</datetime>