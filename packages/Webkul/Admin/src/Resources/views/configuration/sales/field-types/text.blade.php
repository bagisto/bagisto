<input type="text" v-validate="'{{$validations}}'" class="control" id="{{ $name }}" name="{{ $name }}" value="{{ old($name) ?: core()->getConfigData($name) }}" data-vv-as="&quot;{{ $field['name'] }}&quot;">







