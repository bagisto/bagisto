<?php $selectedOption = old($attribute->code) ?: $product[$attribute->code] ?>

<label class="switch">
    <input type="checkbox" class="control" id="{{ $attribute->code }}" name="{{ $attribute->code }}" data-vv-as="&quot;{{ $attribute->admin_name }}&quot;" {{ $selectedOption ? 'checked' : ''}} value="1">
    <span class="slider round"></span>
</label>