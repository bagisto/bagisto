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
                class="w-full py-2.5 px-3 border rounded-md text-sm text-gray-600 dark:text-gray-300 transition-all hover:border-gray-400 dark:hover:border-gray-400 focus:border-gray-400 dark:focus:border-gray-400 dark:bg-gray-900 dark:border-gray-800"
                @if ($attribute->code == 'url_key') v-slugify @endif
                @if ($attribute->code == 'name') v-slugify-target:url_key="setValues" @endif
            >
        </v-field>

        @break

    @case('price')
        <x-admin::form.control-group.control
            type="price"
            :name="$attribute->code"
            :id="$attribute->code"
            ::rules="{{ $attribute->validations }}"
            :label="$attribute->admin_name"
            value="{{ old($attribute->code) ?: $product[$attribute->code] }}"
            :class="($attribute->code == 'price' ? 'py-2.5 bg-gray-50 text-xl font-bold' : '')"
        >
            <x-slot:currency :class="'dark:text-gray-300 ' . ($attribute->code == 'price' ? 'bg-gray-50 dark:bg-gray-900 text-xl' : '')">
                {{ core()->currencySymbol(core()->getBaseCurrencyCode()) }}
            </x-slot:currency>
        </x-admin::form.control-group.control>

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
            :prompt="core()->getConfigData('general.magic_ai.content_generation.product_' . $attribute->code . '_prompt')"
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
        @php
            $selectedOption = old($attribute->code) ?: explode(',', $product[$attribute->code]);
        @endphp

        <x-admin::form.control-group.control
            type="multiselect"
            :name="$attribute->code . '[]'"
            :id="$attribute->code . '[]'"
            ::rules="{{ $attribute->validations }}"
            :label="$attribute->admin_name"
        >
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
            <div class="flex gap-2.5 mb-2 last:!mb-0 items-center">
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

                <label
                    class="text-xs text-gray-600 dark:text-gray-300 font-medium cursor-pointer select-none"
                    for="{{ $attribute->code . '_' . $option->id }}"
                >
                    {{ $option->admin_name }}
                </label>
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

    @case('image')
    @case('file')
        <div class="flex gap-2.5">
            @if ($product[$attribute->code])
                <a
                    href="{{ route('admin.catalog.products.file.download', [$product->id, $attribute->id] )}}"
                    class="flex"
                >
                    @if ($attribute->type == 'image')
                        <img
                            src="{{ Storage::url($product[$attribute->code]) }}"
                            class="w-[45px] h-[45px] border dark:border-gray-800 rounded overflow-hidden hover:border-gray-400"
                        />
                    @else
                        <div class="inline-flex w-full max-w-max cursor-pointer appearance-none items-center justify-between gap-x-1 rounded-md border border-transparent p-1.5 text-center text-gray-600 dark:text-gray-300 transition-all marker:shadow hover:bg-gray-200 dark:hover:bg-gray-800 active:border-gray-300">
                            <i class="icon-down-stat text-2xl"></i>
                        </div>
                    @endif
                </a>

                <input type="hidden" name="{{ $attribute->code }}" value="{{ $product[$attribute->code] }}"/>
            @endif

            <v-field
                type="text"
                class="w-full"
                name="{{ $attribute->code }}"
                :rules="{{ $attribute->validations }}"
                label="{{ $attribute->admin_name }}"
                v-slot="{ handleChange, handleBlur }"
                value="{{ old($attribute->code) ?: $product[$attribute->code] }}"
            >
                <input
                    type="file"
                    name="{{ $attribute->code }}"
                    id="{{ $attribute->code }}"
                    :class="[errors['{{ $attribute->code }}'] ? 'border border-red-600 hover:border-red-600' : '']"
                    class="w-full py-2.5 px-3 border rounded-md text-sm text-gray-600 dark:text-gray-300 transition-all hover:border-gray-400 dark:hover:border-gray-400 dark:file:bg-gray-800 dark:file:dark:text-white focus:border-gray-400 dark:focus:border-gray-400 dark:border-gray-800"
                    @change="handleChange"
                    @blur="handleBlur"
                >
            </v-field>
        </div>

        @if ($product[$attribute->code])
            <div class="flex gap-2.5 items-center mt-2.5">
                <x-admin::form.control-group.control
                    type="checkbox"
                    :name="$attribute->code . '[delete]'"
                    value="1"
                    :id="$attribute->code . '_delete'"
                    :for="$attribute->code . '_delete'"
                >
                </x-admin::form.control-group.control>

                <p class="text-sm text-gray-600 dark:text-gray-300">
                    @lang('admin::app.catalog.products.edit.remove')
                </p>
            </div>
        @endif

        @break

@endswitch
