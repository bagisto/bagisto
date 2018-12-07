<select v-validate="'{{$validations}}'" class="control" id="{{ $name }}" name="{{ $name }}" data-vv-as="&quot;{{ $fieldDetail['name'] }}&quot;" >

    @foreach($fieldDetail['options'] as $option)

        <?php
            if($option['value']) {
                $value = 1;
            }else {
                $value = 0;
            }

            $selectedOption = $configData['value'] ? $configData['value'] : '';
        ?>

        <option value="{{ $value }}" {{ $value == $selectedOption ? 'selected' : ''}}>
            {{ $option['title'] }}
        </option>
    @endforeach

</select>





