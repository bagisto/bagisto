<?php
    $validations = [];
    $disabled = false;

    if (isset($field['validation'])) {
        array_push($validations, $field['validation']);
    }

    $validations = implode('|', array_filter($validations));

    $name = $field['name'];
?>
<div class="control-group {{ $field['type'] }}" :class="[errors.has('{{ $name }}') ? 'has-error' : '']">
    <label for="{{ $name }}" {{ !isset($field['validation']) || strpos('required', $field['validation']) < 0 ? '' : 'class=required' }}>

        {{ $field['title'] }}

        <?php
            $channel_locale = [];

            if(isset($field['channel_based']) && $field['channel_based'])
            {
                array_push($channel_locale, $channel);
            }

            if(isset($field['locale_based']) && $field['locale_based']) {
                array_push($channel_locale, $locale);
            }
        ?>

        @if(count($channel_locale))
            <span class="locale">[{{ implode(' - ', $channel_locale) }}]</span>
        @endif

    </label>

    <?php
        $configData = core()->getConfigData($name, current($channel_locale),  next($channel_locale));
    ?>

    @if ($field['type'] == 'text')

        <input type="text" v-validate="'{{ $validations }}'" class="control" id="{{ $name }}" name="{{ $name }}" value="{{ old($name) ?: core()->getConfigData($name) }}" data-vv-as="&quot;{{ $field['name'] }}&quot;">

    @elseif ($field['type'] == 'textarea')

        <textarea v-validate="'{{ $validations }}'" class="control" id="{{ $name }}" name="{{ $name }}" data-vv-as="&quot;{{ $field['name'] }}&quot;">{{ old($name) ?: core()->getConfigData($name) }}</textarea>

    @elseif ($field['type'] == 'select')

        <select v-validate="'{{ $validations }}'" class="control" id="{{ $name }}" name="{{ $name }}" data-vv-as="&quot;{{ $field['name'] }}&quot;" >

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

    @endif

    <span class="control-error" v-if="errors.has('{{ $name }}')">@{{ errors.first('{!! $name !!}') }}</span>
</div>