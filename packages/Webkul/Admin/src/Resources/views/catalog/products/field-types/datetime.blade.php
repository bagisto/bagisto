<datetime>
    <input
        type="text"
        name="{{ $attribute->code }}"
        value="{{  old($attribute->code) ?: $product[$attribute->code]}}"
        class="control"
        v-validate="'{{ $attribute->is_required ? 'required' : '' }}'"
        data-vv-as="&quot;{{ $attribute->admin_name }}&quot;"
    >
</datetime>
