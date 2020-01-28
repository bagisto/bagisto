<input type="text" v-validate="'{{$validations}}'" class="control" id="{{ $attribute->code }}" name="{{ $attribute->code }}" value="{{ old($attribute->code) ?: $product[$attribute->code] }}" {{ in_array($attribute->code, ['sku', 'url_key']) ? 'v-slugify' : '' }} data-vv-as="&quot;{{ $attribute->admin_name }}&quot;" {{ $attribute->code == 'name' ? 'v-slugify-target=\'url_key\'' : ''  }} />



