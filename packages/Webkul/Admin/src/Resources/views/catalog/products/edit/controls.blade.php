@switch($attribute->type)
    @case('text')
    @case('price')
        <x-admin::form.control-group.control
            type="text"
            :name="$attribute->code"
            :rules="$attribute->validations"
            :label="$attribute->admin_name"
        >
        </x-admin::form.control-group.control>

        @break

    @case('textarea')
        <x-admin::form.control-group.control
            :type="$attribute->enable_wysiwyg ? 'tinymce' : 'textarea'"
            :id="$attribute->code"
            :name="$attribute->code"
            :rules="$attribute->validations"
            :label="$attribute->admin_name"
        >
        </x-admin::form.control-group.control>

        @break
    
    @case('select')
        <x-admin::form.control-group.control
            type="select"
            :name="$attribute->code"
            :rules="$attribute->validations"
            :label="$attribute->admin_name"
        >
            @php $selectedOption = old($attribute->code) ?: $product[$attribute->code] @endphp

            @if ($attribute->code != 'tax_category_id')
                @foreach ($attribute->options()->orderBy('sort_order')->get() as $option)
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
        </x-admin::form.control-group.control>

        @break

    @case('multiselect')
        <x-admin::form.control-group.control
            type="select"
            :name="$attribute->code"
            :rules="$attribute->validations"
            :label="$attribute->admin_name"
            multiple
        >
            @foreach ($attribute->options()->orderBy('sort_order')->get() as $option)
                <option
                    value="{{ $option->id }}"
                    {{ in_array($option->id, explode(',', $product[$attribute->code])) ? 'selected' : ''}}
                >
                    {{ $option->admin_name }}
                </option>
            @endforeach
        </x-admin::form.control-group.control>

        @break
    
    @case('boolean')
        @php $selectedOption = old($attribute->code) ?: $product[$attribute->code] @endphp

        <x-admin::form.control-group.control
            type="switch"
            :name="$attribute->code"
            :label="$attribute->admin_name"
            :checked="$selectedOption"
        >
        </x-admin::form.control-group.control>
        
        @break

@endswitch