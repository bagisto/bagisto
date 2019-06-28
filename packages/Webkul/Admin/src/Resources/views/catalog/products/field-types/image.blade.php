@if ($product[$attribute->code])
    <a href="{{ Storage::url($product[$attribute->code]) }}" target="_blank">
        <img src="{{ Storage::url($product[$attribute->code]) }}" class="configuration-image"/>
    </a>
@endif

<input type="file" v-validate="'{{$validations}}'" class="control" id="{{ $attribute->code }}" name="{{ $attribute->code }}" value="{{ old($attribute->code) ?: $product[$attribute->code] }}" data-vv-as="&quot;{{ $attribute->admin_name }}&quot;" style="padding-top: 5px;"/>

@if ($product[$attribute->code])
    <div class="control-group" style="margin-top: 5px;">
        <span class="checkbox">
            <input type="checkbox" id="{{ $attribute->code }}[delete]"  name="{{ $attribute->code }}[delete]" value="1">

            <label class="checkbox-view" for="delete"></label>
                {{ __('admin::app.configuration.delete') }}
        </span>
    </div>
@endif