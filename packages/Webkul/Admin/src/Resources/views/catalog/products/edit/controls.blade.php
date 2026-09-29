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
        @php
            $selectedOption = old($attribute->code) ?: $product[$attribute->code];

            if ($attribute->code === 'tax_category_id') {
                $options = app(\Webkul\Tax\Repositories\TaxCategoryRepository::class)->all();
            } else if ($attribute->code === 'rma_rule_id') {
                $rmaRuleRepository = app(\Webkul\RMA\Repositories\RMARuleRepository::class);

                /**
                 * Only active RMA rules should be assignable to a product.
                 */
                $options = $rmaRuleRepository->getActiveRules();

                /**
                 * Safety Net: if this product already has a rule that has since been
                 * deactivated, append it to the options list so editing the product
                 * does not silently drop the existing assignment. The admin can then
                 * choose to switch to an active rule on save.
                 */
                if (
                    $selectedOption
                    && ! $options->contains('id', $selectedOption)
                ) {
                    $currentRule = $rmaRuleRepository->find($selectedOption);

                    if ($currentRule) {
                        $options->push($currentRule);
                    }
                }
            } else {
                $options = $attribute->options()->orderBy('sort_order')->get();
            }

            $selectOptions = collect($options)
                ->map(fn ($option) => ['id' => (string) $option->id, 'label' => $option->admin_name ?? $option->name])
                ->values();
        @endphp

        <x-admin::form.control-group.advance.select
            :name="$attribute->code"
            :options="$selectOptions"
            :value="(string) $selectedOption"
            :placeholder="$attribute->admin_name"
            :label="$attribute->admin_name"
            ::clearable="{{ $attribute->is_required ? 'false' : 'true' }}"
            ::rules="{{ $attribute->validations }}"
        />

        @break
    @case('multiselect')
        @php
            $selectedOption = old($attribute->code) ?: array_filter(explode(',', $product[$attribute->code] ?? ''), fn ($id) => $id !== '');

            $multiselectOptions = $attribute->options()->orderBy('sort_order')->get()
                ->map(fn ($option) => ['id' => (string) $option->id, 'label' => $option->admin_name])
                ->values();
        @endphp

        <x-admin::form.control-group.advance.multiselect
            :name="$attribute->code . '[]'"
            :options="$multiselectOptions"
            :value="array_values((array) $selectedOption)"
            :placeholder="$attribute->admin_name"
            :label="$attribute->admin_name"
            ::rules="{{ $attribute->validations }}"
        />

        @break
    @case('checkbox')
        @php
            $selectedOption = old($attribute->code) ?: explode(',', $product[$attribute->code]);
        @endphp

        @foreach ($attribute->options as $option)
            <div class="mb-2 flex items-center gap-2.5 last:mb-0!">
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
                    v-pre
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
        @php
            $storedMedia = $product[$attribute->code];

            $mediaRules = $storedMedia
                ? preg_replace('/required:\s*true\s*,?\s*/', '', $attribute->validations)
                : $attribute->validations;

            $mediaSource = $storedMedia && Storage::exists($storedMedia)
                ? Storage::url($storedMedia)
                : '';

            $mediaDownloadUrl = $storedMedia
                ? route('admin.catalog.products.file.download', [$product->id, $attribute->id])
                : '';
        @endphp

        <x-admin::media.upload
            name="{{ $attribute->code }}"
            type="{{ $attribute->type }}"
            value="{{ $storedMedia }}"
            src="{{ $mediaSource }}"
            download-url="{{ $mediaDownloadUrl }}"
            ::rules="{{ $mediaRules }}"
            extensions="{{ $attribute->type == 'image' ? 'bmp, jpeg, jpg, png, webp' : '' }}"
            label="{{ $attribute->admin_name }}"
            removable="{{ $attribute->is_required ? '0' : '1' }}"
        />

        @break
@endswitch
