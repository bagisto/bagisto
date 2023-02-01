<datetime>
    <input type="text" name="{{ $attribute->code }}" class="control" @if ($attribute->is_required) v-validate="'required'" @endif value="{{  old($attribute->code) ?: $product[$attribute->code]}}" data-vv-as="&quot;{{ $attribute->admin_name }}&quot;">
</datetime>