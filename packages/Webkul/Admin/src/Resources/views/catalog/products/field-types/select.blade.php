<select v-validate="'{{$validations}}'" class="control" id="{{ $attribute->code }}" name="{{ $attribute->code }}" {{ $disabled ? 'disabled' : '' }}>

    <?php $selectedOption = old($attribute->code) ?: $product[$attribute->code] ?>

    @foreach($attribute->options as $option)
        <option value="{{ $option->id }}" {{ $option->id == $selectedOption ? 'selected' : ''}}>
            {{ $option->admin_name }}
        </option>
    @endforeach

</select>