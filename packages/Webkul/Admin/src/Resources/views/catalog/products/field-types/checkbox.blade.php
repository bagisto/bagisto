<div class="control-group" style="margin-top: 5px;">

    @foreach ($attribute->options as $option)
        <span class="checkbox" style="margin-top: 5px;">
            <input  type="checkbox" name="{{ $attribute->code }}[]" value="{{ $option->id }}" {{ in_array($option->id, explode(',', $product[$attribute->code])) ? 'checked' : ''}}>

            <label class="checkbox-view"></label>
            {{ $option->admin_name }}
        </span>
    @endforeach

</div>