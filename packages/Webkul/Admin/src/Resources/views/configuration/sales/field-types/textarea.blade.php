<textarea v-validate="'{{$validations}}'" class="control" id="{{ $name }}" name="{{ $name }}" data-vv-as="&quot;{{ $field['name'] }}&quot;">{{ old($name) ?: core()->getConfigData($name) }}</textarea>
