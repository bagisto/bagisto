<select v-validate="'{{$validations}}'" class="control" id="{{ $attribute->code }}" name="{{ $attribute->code }}" data-vv-as="&quot;{{ $attribute->admin_name }}&quot;" multiple {{ $disabled ? 'disabled' : '' }}>

    @foreach ($attribute->options as $option)
        <option value="{{ $option->id }}" {{ in_array($option->id, explode(',', $attribute[$attribute->code])) ? 'selected' : ''}}>
            {{ $option->admin_name }}
        </option>
    @endforeach

</select>
