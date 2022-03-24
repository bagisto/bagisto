<date>
    <input type="text" name="{{ $attribute->code }}" class="control" v-validate="{{ $attribute->is_required ? 'required' : '' }}" value="{{ old($attribute->code) ?: $product[$attribute->code] }}" data-vv-as="&quot;{{ $attribute->admin_name }}&quot;"/>
</date>