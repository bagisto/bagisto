<select v-validate="'{{$validations}}'" class="control" id="{{ $name }}" name="{{ $name }}" data-vv-as="&quot;{{ $field['name'] }}&quot;" >

    @foreach($field['options'] as $option)

        <?php
            if($option['value']) {
                $value = 1;
            }else {
                $value = 0;
            }

            $selectedOption = core()->getConfigData($name) ?? '';
        ?>

        <option value="{{ $value }}" {{ $value == $selectedOption ? 'selected' : ''}}>
            {{ $option['title'] }}
        </option>
    @endforeach

</select>
