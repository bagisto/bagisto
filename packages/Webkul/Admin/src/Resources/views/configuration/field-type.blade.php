<?php
    $validations = [];
    $disabled = false;

    if (isset($field['validation'])) {
        array_push($validations, $field['validation']);
    }

    $validations = implode('|', array_filter($validations));

    $key = $item['key'];
    $key = explode(".", $key);
    $firstField = current($key);
    $secondField = next($key);
    $thirdField  = end($key);

    $name = $item['key'] . '.' . $field['name'];

    if (isset($field['repository'])) {
        $temp = explode("@", $field['repository']);
        $class = app(current($temp));
        $method = end($temp);
        $value = $class->$method();
        $selectedOption = core()->getConfigData($name) ?? '';
    }

    $channel_locale = [];

    if(isset($field['channel_based']) && $field['channel_based'])
    {
        array_push($channel_locale, $channel);
    }

    if(isset($field['locale_based']) && $field['locale_based']) {
        array_push($channel_locale, $locale);
    }
?>

    <div class="control-group {{ $field['type'] }}" :class="[errors.has('{{ $firstField }}[{{ $secondField }}][{{ $thirdField }}][{{ $field['name'] }}]') ? 'has-error' : '']">

        <label for="{{ $name }}" {{ !isset($field['validation']) || strpos('required', $field['validation']) < 0 ? '' : 'class=required' }}>

            {{ $field['title'] }}

            @if(count($channel_locale))
                <span class="locale">[{{ implode(' - ', $channel_locale) }}]</span>
            @endif

        </label>

        @if ($field['type'] == 'text')

            <input type="text" v-validate="'{{ $validations }}'" class="control" id="{{ $firstField }}[{{ $secondField }}][{{ $thirdField }}][{{ $field['name'] }}]" name="{{ $firstField }}[{{ $secondField }}][{{ $thirdField }}][{{ $field['name'] }}]" value="{{ old($name) ?: core()->getConfigData($name) }}" data-vv-as="&quot;{{ $field['name'] }}&quot;">

        @elseif ($field['type'] == 'textarea')

            <textarea v-validate="'{{ $validations }}'" class="control" id="{{ $firstField }}[{{ $secondField }}][{{ $thirdField }}][{{ $field['name'] }}]" name="{{ $firstField }}[{{ $secondField }}][{{ $thirdField }}][{{ $field['name'] }}]" data-vv-as="&quot;{{ $field['name'] }}&quot;">{{ old($name) ?: core()->getConfigData($name) }}</textarea>

        @elseif ($field['type'] == 'select')

            <select v-validate="'{{ $validations }}'" class="control" id="{{ $firstField }}[{{ $secondField }}][{{ $thirdField }}][{{ $field['name'] }}]" name="{{ $firstField }}[{{ $secondField }}][{{ $thirdField }}][{{ $field['name'] }}]" data-vv-as="&quot;{{ $field['name'] }}&quot;" >

                @if (isset($field['repository']))
                    @foreach($value as $option)
                        <option value="{{  $option['name'] }}" {{ $option['name'] ==            $selectedOption ? 'selected' : ''}}
                        {{ $option['name'] }}
                        </option>
                    @endforeach
                @else
                    @foreach($field['options'] as $option)
                        <?php
                            if($option['value'] == false) {
                                $value = 0;
                            } else {
                                $value = $option['value'];
                            }

                            $selectedOption = core()->getConfigData($name) ?? '';
                        ?>

                        <option value="{{ $value }}" {{ $value == $selectedOption ? 'selected' : ''}}>
                            {{ $option['title'] }}
                        </option>
                    @endforeach
                @endif

            </select>

        @elseif ($field['type'] == 'multiselect')

            <select v-validate="'{{ $validations }}'" class="control" id="{{ $firstField }}[{{ $secondField }}][{{ $thirdField }}][{{ $field['name'] }}]" name="{{ $firstField }}[{{ $secondField }}][{{ $thirdField }}][{{ $field['name'] }}][]" data-vv-as="&quot;{{ $field['name'] }}&quot;"  multiple>

                @if (isset($field['repository']))
                    @foreach($value as $option)
                        <option value="{{  $option['name'] }}" {{ in_array($option['name'], explode(',', $selectedOption)) ? 'selected' : ''}}>
                            {{ $option['name'] }}
                        </option>
                    @endforeach
                @else
                    @foreach($field['options'] as $option)

                        <?php
                            if($option['value'] == false) {
                                $value = 0;
                            } else {
                                $value = $option['value'];
                            }

                            $selectedOption = core()->getConfigData($name) ?? '';
                        ?>

                        <option value="{{ $value }}" {{ $value == $selectedOption ? 'selected' : ''}}>
                            {{ $option['title'] }}
                        </option>

                    @endforeach
                @endif

            </select>

        @elseif ($field['type'] == 'country')

            <select type="text" v-validate="'required'" class="control" id="country" name="{{ $firstField }}[{{ $secondField }}][{{ $thirdField }}][{{ $field['name'] }}]"  data-vv-as="&quot;{{ __('admin::app.customers.customers.country') }}&quot;" onchange="myFunction()">
                <option value=""></option>

                @foreach (core()->countries() as $country)

                    <option value="{{ $country->code }}">
                        {{ $country->name }}
                    </option>

                @endforeach
            </select>


        @endif

        <span class="control-error" v-if="errors.has('{{ $firstField }}[{{ $secondField }}][{{ $thirdField }}][{{ $field['name'] }}]')">@{{ errors.first('{!! $firstField !!}[{!! $secondField !!}][{!! $thirdField !!}][{!! $field['name'] !!}]') }}</span>

    </div>




@push('scripts')

<script>

    function myFunction() {
        var countryId = document.getElementById("country").value;
        var countryStates = <?php echo json_encode(core()->groupedStatesByCountries()) ;?>;

        for (var key in countryStates) {
            if(key == countryId){


                for(state in countryStates[key]) {
                    console.log(state);
                }
            }
        }

    }

</script>

@endpush


