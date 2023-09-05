@switch($attribute->type)
    @case('text')
        <v-field
            type="text"
            name="{{ $attribute->code }}"
            :rules="{{ $attribute->validations }}"
            label="{{ $attribute->admin_name }}"
            value="{{ old($attribute->code) ?: $product[$attribute->code] }}"
            v-slot="{ field }"
        >
            <input
                type="text"
                name="{{ $attribute->code }}"
                id="{{ $attribute->code }}"
                v-bind="field"
                :class="[errors['{{ $attribute->code }}'] ? 'border border-red-600 hover:border-red-600' : '']"
                class="flex w-full min-h-[39px] py-2 px-3 border rounded-[6px] text-[14px] text-gray-600 transition-all hover:border-gray-400 focus:border-gray-400"
                @if ($attribute->code == 'url_key') v-slugify @endif
                @if ($attribute->code == 'name') v-slugify-target:url_key @endif
            >
        </v-field>

        @break

    @case('price')
        <div class="relative">
            <span class="absolute ltr:left-[15px] rtl:right-[15px] top-[50%] -translate-y-[50%] text-gray-500 {{ $attribute->code == 'price' ? 'text-[20px]' : '' }}">
                {{ core()->currencySymbol(core()->getBaseCurrencyCode()) }}
            </span>

            <x-admin::form.control-group.control
                type="text"
                :name="$attribute->code"
                :id="$attribute->code"
                ::rules="{{ $attribute->validations }}"
                :label="$attribute->admin_name"
                value="{{ old($attribute->code) ?: $product[$attribute->code] }}"
                :class="'ltr:pl-[30px] rtl:pr-[30px] ' . ($attribute->code == 'price' ? 'py-2 bg-gray-50 text-[20px] font-bold' : '')"
            >
            </x-admin::form.control-group.control>
        </div>

        @break

    @case('textarea')
        <x-admin::form.control-group.control
            type="textarea"
            :name="$attribute->code"
            :id="$attribute->code"
            ::rules="{{ $attribute->validations }}"
            :label="$attribute->admin_name"
            value="{{ old($attribute->code) ?: $product[$attribute->code] }}"
            :tinymce="(bool) $attribute->enable_wysiwyg"
        >
        </x-admin::form.control-group.control>

        @break

    @case('date')
        <x-admin::form.control-group.control
            type="date"
            :name="$attribute->code"
            :id="$attribute->code"
            ::rules="{{ $attribute->validations }}"
            :label="$attribute->admin_name"
            value="{{ old($attribute->code) ?: $product[$attribute->code] }}"
        >
        </x-admin::form.control-group.control>

        @break

    @case('datetime')
        <x-admin::form.control-group.control
            type="datetime"
            :name="$attribute->code"
            ::rules="{{ $attribute->validations }}"
            :label="$attribute->admin_name"
            value="{{ old($attribute->code) ?: $product[$attribute->code] }}"
        >
        </x-admin::form.control-group.control>

        @break

    @case('select')
        <x-admin::form.control-group.control
            type="select"
            :name="$attribute->code"
            :id="$attribute->code"
            ::rules="{{ $attribute->validations }}"
            :label="$attribute->admin_name"
            :value="old($attribute->code) ?: $product[$attribute->code]"
        >
            @php
                $selectedOption = old($attribute->code) ?: $product[$attribute->code];

                if ($attribute->code != 'tax_category_id') {
                    $options = $attribute->options()->orderBy('sort_order')->get();
                } else {
                    $options = app('Webkul\Tax\Repositories\TaxCategoryRepository')->all();
                }
            @endphp

            @foreach ($options as $option)
                <option
                    value="{{ $option->id }}"
                    {{ $selectedOption == $option->id ? 'selected' : '' }}
                >
                    {{ $option->admin_name ?? $option->name }}
                </option>
            @endforeach
        </x-admin::form.control-group.control>

        @break

    @case('multiselect')
        <x-admin::form.control-group.control
            type="multiselect"
            :name="$attribute->code . '[]'"
            :id="$attribute->code . '[]'"
            ::rules="{{ $attribute->validations }}"
            :label="$attribute->admin_name"
        >
            @php
                $selectedOption = old($attribute->code) ?: explode(',', $product[$attribute->code]);
            @endphp

            @foreach ($attribute->options()->orderBy('sort_order')->get() as $option)
                <option
                    value="{{ $option->id }}"
                    {{ in_array($option->id, $selectedOption) ? 'selected' : ''}}
                >
                    {{ $option->admin_name }}
                </option>
            @endforeach
        </x-admin::form.control-group.control>

        @break

    @case('checkbox')
        @php
            $selectedOption = old($attribute->code) ?: explode(',', $product[$attribute->code]);
        @endphp

        @foreach ($attribute->options as $option)
            <div class="flex gap-[10px] py-[6px] items-center">
                <x-admin::form.control-group.control
                    type="checkbox"
                    :name="$attribute->code . '[]'"
                    :value="$option->id"
                    :id="$attribute->code . '_' . $option->id"
                    :for="$attribute->code . '_' . $option->id"
                    ::rules="{{ $attribute->validations }}"
                    :label="$attribute->admin_name"
                    :checked="in_array($option->id, $selectedOption)"
                >
                </x-admin::form.control-group.control>

                <p class="text-gray-600 font-semibold">
                    {{ $option->admin_name }}
                </p>
            </div>
        @endforeach

        @break

    @case('boolean')
        @php $selectedValue = old($attribute->code) ?: $product[$attribute->code] @endphp

        <x-admin::form.control-group.control
            type="switch"
            :name="$attribute->code"
            :id="$attribute->code"
            :value="1"
            :label="$attribute->admin_name"
            :checked="(boolean) $selectedValue"
        >
        </x-admin::form.control-group.control>

        @break

@endswitch
