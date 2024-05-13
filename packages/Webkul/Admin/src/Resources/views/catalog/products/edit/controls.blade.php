@switch($attribute->type)
    @case('text')
        <v-field
            type="text"
            name="{{ $attribute->code }}"
            :rules="{{ $attribute->validations }}"
            value="{{ old($attribute->code) ?: $product[$attribute->code] }}"
            v-slot="{ field }"
            label="{{ $attribute->admin_name }}"
        >
            <input
                type="text"
                id="{{ $attribute->code }}"
                :class="[errors['{{ $attribute->code }}'] ? 'border border-red-600 hover:border-red-600' : '']"
                class="w-full rounded-md border px-3 py-2.5 text-sm text-gray-600 transition-all hover:border-gray-400 focus:border-gray-400 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300 dark:hover:border-gray-400 dark:focus:border-gray-400"
                name="{{ $attribute->code }}"
                v-bind="field"
                @if ($attribute->code == 'url_key') v-slugify @endif
                @if ($attribute->code == 'name') v-slugify-target:url_key="setValues" @endif
            >
        </v-field>

        @break
    @case('price')
        <x-admin::form.control-group.control
            type="price"
            :id="$attribute->code"
            :class="($attribute->code == 'price' ? 'py-2.5 bg-gray-50 text-xl font-bold' : '')"
            :name="$attribute->code"
            ::rules="{{ $attribute->validations }}"
            value="{{ old($attribute->code) ?: $product[$attribute->code] }}"
            :label="$attribute->admin_name"
        >
            <x-slot:currency :class="'dark:text-gray-300 ' . ($attribute->code == 'price' ? 'bg-gray-50 dark:bg-gray-900 text-xl' : '')">
                {{ core()->currencySymbol(core()->getBaseCurrencyCode()) }}
            </x-slot>
        </x-admin::form.control-group.control>

        @break
    @case('textarea')
        <x-admin::form.control-group.control
            type="textarea"
            :id="$attribute->code"
            :name="$attribute->code"
            ::rules="{{ $attribute->validations }}"
            value="{{ old($attribute->code) ?: $product[$attribute->code] }}"
            :label="$attribute->admin_name"
            :tinymce="(bool) $attribute->enable_wysiwyg"
            :prompt="core()->getConfigData('general.magic_ai.content_generation.product_' . $attribute->code . '_prompt')"
        />

        @break
    @case('date')
        <x-admin::form.control-group.control
            type="date"
            :id="$attribute->code"
            :name="$attribute->code"
            ::rules="{{ $attribute->validations }}"
            value="{{ old($attribute->code) ?: $product[$attribute->code] }}"
            :label="$attribute->admin_name"
        />

        @break
    @case('datetime')
        <x-admin::form.control-group.control
            type="datetime"
            :name="$attribute->code"
            ::rules="{{ $attribute->validations }}"
            value="{{ old($attribute->code) ?: $product[$attribute->code] }}"
            :label="$attribute->admin_name"
        />

        @break
    @case('select')
        <x-admin::form.control-group.control
            type="select"
            :id="$attribute->code"
            :name="$attribute->code"
            ::rules="{{ $attribute->validations }}"
            :value="old($attribute->code) ?: $product[$attribute->code]"
            :label="$attribute->admin_name"
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
            :id="$attribute->code . '[]'"
            :name="$attribute->code . '[]'"
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
            <div class="mb-2 flex items-center gap-2.5 last:!mb-0">
                <x-admin::form.control-group.control
                    type="checkbox"
                    :id="$attribute->code . '_' . $option->id"
                    :name="$attribute->code . '[]'"
                    ::rules="{{ $attribute->validations }}"
                    :value="$option->id"
                    :for="$attribute->code . '_' . $option->id"
                    :label="$attribute->admin_name"
                    :checked="in_array($option->id, $selectedOption)"
                />

                <label
                    class="cursor-pointer select-none text-xs font-medium text-gray-600 dark:text-gray-300"
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
            :id="$attribute->code"
            :name="$attribute->code"
            :value="1"
            :label="$attribute->admin_name"
            :checked="(boolean) $selectedValue"
        />

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
                        @if (Storage::exists($product[$attribute->code]))
                            <img
                                src="{{ Storage::url($product[$attribute->code]) }}"
                                class="h-[45px] w-[45px] overflow-hidden rounded border hover:border-gray-400 dark:border-gray-800"
                            />
                        @endif
                    @else
                        <div class="inline-flex w-full max-w-max cursor-pointer appearance-none items-center justify-between gap-x-1 rounded-md border border-transparent p-1.5 text-center text-gray-600 transition-all marker:shadow hover:bg-gray-200 active:border-gray-300 dark:text-gray-300 dark:hover:bg-gray-800">
                            <i class="icon-down-stat text-2xl"></i>
                        </div>
                    @endif
                </a>

                <input
                    type="hidden"
                    name="{{ $attribute->code }}"
                    value="{{ $product[$attribute->code] }}"
                />
            @endif

            <v-field
                type="file"
                class="w-full"
                name="{{ $attribute->code }}"
                :rules="{{ $attribute->validations }}"
                v-slot="{ handleChange, handleBlur }"
                label="{{ $attribute->admin_name }}"
            >
                <input
                    type="file"
                    id="{{ $attribute->code }}"
                    :class="[errors['{{ $attribute->code }}'] ? 'border border-red-600 hover:border-red-600' : '']"
                    class="w-full rounded-md border px-3 py-2.5 text-sm text-gray-600 transition-all hover:border-gray-400 focus:border-gray-400 dark:border-gray-800 dark:text-gray-300 dark:file:bg-gray-800 dark:file:dark:text-white dark:hover:border-gray-400 dark:focus:border-gray-400"
                    name="{{ $attribute->code }}"
                    @change="handleChange"
                    @blur="handleBlur"
                >
            </v-field>
        </div>

        @if ($product[$attribute->code])
            <div class="mt-2.5 flex items-center gap-2.5">
                <x-admin::form.control-group.control
                    type="checkbox"
                    :id="$attribute->code . '_delete'"
                    :name="$attribute->code . '[delete]'"
                    value="1"
                    :for="$attribute->code . '_delete'"
                />

                <label
                    for="{{ $attribute->code . '_delete' }}"
                    class="cursor-pointer select-none text-sm text-gray-600 dark:text-gray-300"
                >
                    @lang('admin::app.catalog.products.edit.remove')
                </label>
            </div>
        @endif

        @break
@endswitch
