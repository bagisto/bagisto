<select v-validate="'{{$validations}}'" class="control" id="{{ $attribute->code }}" name="{{ $attribute->code }}" data-vv-as="&quot;{{ $attribute->admin_name }}&quot;" {{ $disabled ? 'disabled' : '' }}>

    <?php $selectedOption = old($attribute->code) ?: $product[$attribute->code] ?>

    @if ($attribute->code != 'tax_category_id')

        @foreach ($attribute->options as $option)
            <option value="{{ $option->id }}" {{ $option->id == $selectedOption ? 'selected' : ''}}>
                {{ $option->admin_name }}
            </option>
        @endforeach

    @else

        <option value=""></option>

        @foreach (app('Webkul\Tax\Repositories\TaxCategoryRepository')->all() as $taxCategory)
            <option value="{{ $taxCategory->id }}" {{ $taxCategory->id == $selectedOption ? 'selected' : ''}}>
                {{ $taxCategory->name }}
            </option>
        @endforeach

    @endif

</select>