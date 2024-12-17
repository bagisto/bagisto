<x-admin::layouts>
    <x-slot:title>
        @lang('admin::app.catalog.attributes.edit.title')
    </x-slot>

    {!! view_render_event('bagisto.admin.catalog.attributes.edit.before', ['attribute' => $attribute]) !!}

    <!-- Input Form -->
    <x-admin::form
        :action="route('admin.catalog.attributes.update', $attribute->id)"
        enctype="multipart/form-data"
        method="PUT"
    >
        <div class="flex items-center justify-between gap-4 max-sm:flex-wrap">
            <p class="text-xl font-bold text-gray-800 dark:text-white">
                @lang('admin::app.catalog.attributes.edit.title')
            </p>

            <div class="flex items-center gap-x-2.5">
                <!-- Back Button -->
                <a
                    href="{{ route('admin.catalog.attributes.index') }}"
                    class="transparent-button hover:bg-gray-200 dark:text-white dark:hover:bg-gray-800"
                >
                    @lang('admin::app.catalog.attributes.edit.back-btn')
                </a>

                <!-- Save Button -->
                <button
                    type="submit"
                    class="primary-button"
                >
                    @lang('admin::app.catalog.attributes.edit.save-btn')
                </button>
            </div>
        </div>

        <!-- Edit Attributes Vue Components -->
        <v-edit-attributes>
            <!-- Shimmer Effect -->
            <x-admin::shimmer.catalog.attributes />
        </v-edit-attributes>
    </x-admin::form>

    {!! view_render_event('bagisto.admin.catalog.attributes.edit.after', ['attribute' => $attribute]) !!}

    @pushOnce('scripts')
        <script
            type="text/x-template"
            id="v-edit-attributes-template"
        >
            <!-- body content -->
            <div class="mt-3.5 flex gap-2.5 max-xl:flex-wrap">
                <!-- Left sub Component -->
                <div class="flex flex-1 flex-col gap-2 overflow-auto max-xl:flex-auto">

                    {!! view_render_event('bagisto.admin.catalog.attributes.edit.card.label.before', ['attribute' => $attribute]) !!}

                    <!-- Label -->
                    <div class="box-shadow rounded bg-white p-4 dark:bg-gray-900">
                        <p class="mb-4 text-base font-semibold text-gray-800 dark:text-white">
                            @lang('admin::app.catalog.attributes.edit.label')
                        </p>

                        <!-- Admin name -->
                        <x-admin::form.control-group>
                            <x-admin::form.control-group.label class="required">
                                @lang('admin::app.catalog.attributes.edit.admin')
                            </x-admin::form.control-group.label>

                            <x-admin::form.control-group.control
                                type="text"
                                name="admin_name"
                                rules="required"
                                :value="old('admin_name') ?: $attribute->admin_name"
                                :label="trans('admin::app.catalog.attributes.edit.admin')"
                                :placeholder="trans('admin::app.catalog.attributes.edit.admin')"
                            />

                            <x-admin::form.control-group.error control-name="admin_name" />
                        </x-admin::form.control-group>

                        <!-- Locales Inputs -->
                        @foreach ($locales as $locale)
                            <x-admin::form.control-group class="last:!mb-0">
                                <x-admin::form.control-group.label>
                                    {{ $locale->name . ' (' . strtoupper($locale->code) . ')' }}
                                </x-admin::form.control-group.label>

                                <x-admin::form.control-group.control
                                    type="text"
                                    :name="$locale->code . '[name]'"
                                    :value="old($locale->code)['name'] ?? ($attribute->translate($locale->code)->name ?? '')"
                                    :placeholder="$locale->name"
                                />

                                <x-admin::form.control-group.error :control-name="$locale->code . '[name]'" />
                            </x-admin::form.control-group>
                        @endforeach
                    </div>

                    {!! view_render_event('bagisto.admin.catalog.attributes.edit.card.label.after', ['attribute' => $attribute]) !!}

                    <!-- Options -->
                    <div
                        class="box-shadow rounded bg-white p-4 dark:bg-gray-900 {{ in_array($attribute->type, ['select', 'multiselect', 'checkbox', 'price']) ?: 'hidden' }}"
                        v-if="showSwatch"
                    >
                        <div class="mb-3 flex items-center justify-between">
                            <p class="mb-4 text-base font-semibold text-gray-800 dark:text-white">
                                @lang('admin::app.catalog.attributes.edit.options')
                            </p>

                            <!-- Add Row Button -->
                            <div
                                class="secondary-button text-sm"
                                @click="$refs.addOptionsRow.toggle();swatchValue=''"
                            >
                                @lang('admin::app.catalog.attributes.edit.add-row')
                            </div>
                        </div>

                        <!-- Swatch Changer and Empty Field Section. -->
                        <div class="flex items-center gap-4 max-sm:flex-wrap">
                            <!-- Input Options -->
                            <x-admin::form.control-group
                                class="mb-2.5 w-full"
                                v-if="this.showSwatch"
                            >
                                <x-admin::form.control-group.label for="swatchType">
                                    @lang('admin::app.catalog.attributes.edit.input-options')
                                </x-admin::form.control-group.label>

                                <x-admin::form.control-group.control
                                    type="select"
                                    id="swatchType"
                                    name="swatch_type"
                                    v-model="swatchType"
                                    @change="showSwatch=true"
                                >
                                    @foreach (['dropdown', 'color', 'image', 'text'] as $type)
                                        <option value="{{ $type }}">
                                            @lang('admin::app.catalog.attributes.edit.option.' . $type)
                                        </option>
                                    @endforeach
                                </x-admin::form.control-group.control>

                                <x-admin::form.control-group.error control-name="admin" />
                            </x-admin::form.control-group>

                            <!-- checkbox -->
                            <div class="w-full">
                                <div class="!mb-0 flex w-max cursor-pointer select-none items-center gap-2.5">
                                    <input
                                        type="checkbox"
                                        name="empty_option"
                                        id="empty_option"
                                        for="empty_option"
                                        class="peer hidden"
                                        v-model="isNullOptionChecked"
                                        @click="$refs.addOptionsRow.toggle()"
                                    >

                                    <label
                                        for="empty_option"
                                        class="icon-uncheckbox peer-checked:icon-checked cursor-pointer rounded-md text-2xl peer-checked:text-blue-600"
                                    >
                                    </label>

                                    <label
                                        for="empty_option"
                                        class="cursor-pointer text-xs font-medium text-gray-600 dark:text-gray-300"
                                    >
                                        @lang('admin::app.catalog.attributes.edit.create-empty-option')
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- For Attribute Options If Data Exist -->
                        <div class="mt-4 overflow-x-auto">
                            <template v-if="optionsData?.length">
                                @if (
                                    $attribute->type == 'select'
                                    || $attribute->type == 'multiselect'
                                    || $attribute->type == 'checkbox'
                                    || $attribute->type == 'price'
                                )
                                    <!-- Table Information -->
                                    <x-admin::table>
                                        <x-admin::table.thead class="text-sm font-medium dark:bg-gray-800">
                                            <x-admin::table.thead.tr>
                                                <x-admin::table.th class="!p-0"></x-admin::table.th>

                                                <!-- Swatch Select -->
                                                <x-admin::table.th v-if="showSwatch && (swatchType == 'color' || swatchType == 'image')">
                                                    @lang('admin::app.catalog.attributes.edit.swatch')
                                                </x-admin::table.th>

                                                <!-- Admin tables heading -->
                                                <x-admin::table.th>
                                                    @lang('admin::app.catalog.attributes.edit.admin-name')
                                                </x-admin::table.th>

                                                <!-- Locales tables heading -->
                                                <x-admin::table.th v-for="locale in locales">
                                                    <x-admin::table.th>
                                                        @{{ locale.name + '(' + [locale.code] + ')' }}
                                                    </x-admin::table.th>
                                                </x-admin::table.th>

                                                <!-- Action tables heading -->
                                                <x-admin::table.th></x-admin::table.th>
                                            </x-admin::table.thead.tr>
                                        </x-admin::table.thead>

                                        <!-- Draggable Component -->
                                        <draggable
                                            tag="tbody"
                                            ghost-class="draggable-ghost"
                                            handle=".icon-drag"
                                            v-bind="{animation: 200}"
                                            :list="optionsData"
                                            item-key="id"
                                        >
                                            <template #item="{ element, index }">
                                                <x-admin::table.thead.tr
                                                    class="hover:bg-gray-50 dark:hover:bg-gray-950"
                                                    v-show="! element.isDelete"
                                                >
                                                    <input
                                                        type="hidden"
                                                        :name="'options[' + element.id + '][isNew]'"
                                                        :value="element.isNew"
                                                    >

                                                    <input
                                                        type="hidden"
                                                        :name="'options[' + element.id + '][isDelete]'"
                                                        :value="element.isDelete"
                                                    >

                                                    <!-- Draggable Icon -->
                                                    <x-admin::table.td class="!px-0 text-center">
                                                        <i class="icon-drag cursor-grab text-xl transition-all group-hover:text-gray-700"></i>

                                                        <input
                                                            type="hidden"
                                                            :name="'options[' + element.id + '][sort_order]'"
                                                            :value="index"
                                                        />
                                                    </x-admin::table.td>

                                                    <!-- Swatch Type Image / Color -->
                                                    <x-admin::table.td v-if="showSwatch && (swatchType == 'color' || swatchType == 'image')">
                                                        <!-- Swatch Image -->
                                                        <div v-if="swatchType == 'image'">
                                                            <img
                                                                :src="element.swatch_value_url || '{{ bagisto_asset('images/product-placeholders/front.svg') }}'"
                                                                :ref="'image_' + element.id"
                                                                class="h-[50px] w-[50px]"
                                                            >

                                                            <input
                                                                type="file"
                                                                :name="'options[' + element.id + '][swatch_value]'"
                                                                class="hidden"
                                                                :ref="'imageInput_' + element.id"
                                                            />
                                                        </div>

                                                        <!-- Swatch Color -->
                                                        <div v-if="swatchType == 'color'">
                                                            <div
                                                                class="h-[25px] w-[25px] rounded-md border border-gray-200 dark:border-gray-800"
                                                                :style="{ background: element.swatch_value }"
                                                            >
                                                            </div>

                                                            <input
                                                                type="hidden"
                                                                :name="'options[' + element.id + '][swatch_value]'"
                                                                v-model="element.swatch_value"
                                                            />
                                                        </div>
                                                    </x-admin::table.td>

                                                    <!-- Admin-->
                                                    <x-admin::table.td>
                                                        <p class="dark:text-white">
                                                            @{{ element.admin_name }}
                                                        </p>

                                                        <input
                                                            type="hidden"
                                                            :name="'options[' + element.id + '][admin_name]'"
                                                            v-model="element.admin_name"
                                                        />
                                                    </x-admin::table.td>

                                                    <!-- Locales -->
                                                    <x-admin::table.td v-for="locale in locales">
                                                        <p class="dark:text-white">
                                                            @{{ element['locales'][locale.code] }}
                                                        </p>

                                                        <input
                                                            type="hidden"
                                                            :name="'options[' + element.id + '][' + locale.code + '][label]'"
                                                            v-model="element['locales'][locale.code]"
                                                        />
                                                    </x-admin::table.td>

                                                    <!-- Actions Button -->
                                                    <x-admin::table.td class="!px-0">
                                                        <span
                                                            class="icon-edit cursor-pointer rounded-md p-1.5 text-2xl transition-all hover:bg-gray-200 dark:hover:bg-gray-800 max-sm:place-self-center"
                                                            @click="editOptions(element)"
                                                        >
                                                        </span>

                                                        <span
                                                            class="icon-delete cursor-pointer rounded-md p-1.5 text-2xl transition-all hover:bg-gray-100 dark:hover:bg-gray-800 max-sm:place-self-center"
                                                            @click="removeOption(element.id)"
                                                        >
                                                        </span>
                                                    </x-admin::table.td>
                                                </x-admin::table.thead.tr>
                                            </template>
                                        </draggable>
                                    </x-admin::table>
                                @endif
                            </template>

                            <!-- For Empty Attribute Options -->
                            <template v-else>
                                <div class="grid justify-items-center gap-3.5 px-2.5 py-10">
                                    <!-- Attribute Option Image -->
                                    <img
                                        class="h-[120px] w-[120px] dark:mix-blend-exclusion dark:invert"
                                        src="{{ bagisto_asset('images/icon-add-product.svg') }}"
                                        alt="{{ trans('admin::app.catalog.attributes.edit.add-attribute-options') }}"
                                    >

                                    <!-- Add Attribute Options Information -->
                                    <div class="flex flex-col items-center gap-1.5">
                                        <p class="text-base font-semibold text-gray-400">
                                            @lang('admin::app.catalog.attributes.edit.add-attribute-options')
                                        </p>

                                        <p class="text-gray-400">
                                            @lang('admin::app.catalog.attributes.edit.add-options-info')
                                        </p>
                                    </div>

                                    <!-- Add Row Button -->
                                    <div
                                        class="secondary-button text-sm"
                                        @click="$refs.addOptionsRow.toggle()"
                                    >
                                        @lang('admin::app.catalog.attributes.edit.add-row')
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>

                <!-- Right sub-component -->
                <div class="flex w-[360px] max-w-full flex-col gap-2 max-sm:w-full">
                    {!! view_render_event('bagisto.admin.catalog.attributes.edit.card.accordion.general.before', ['attribute' => $attribute]) !!}

                    <!-- General -->
                    <x-admin::accordion>
                        <x-slot:header>
                            <p class="p-2.5 text-base font-semibold text-gray-800 dark:text-white">
                                @lang('admin::app.catalog.attributes.edit.general')
                            </p>
                        </x-slot>

                        <x-slot:content>
                            <!-- Attribute Code -->
                            <x-admin::form.control-group>
                                <x-admin::form.control-group.label class="required">
                                    @lang('admin::app.catalog.attributes.edit.code')
                                </x-admin::form.control-group.label>

                                @php
                                    $selectedOption = old('type') ?: $attribute->code;
                                @endphp

                                <x-admin::form.control-group.control
                                    type="text"
                                    class="cursor-not-allowed"
                                    name="code"
                                    rules="required"
                                    :value="$selectedOption"
                                    :disabled="(boolean) $selectedOption"
                                    readonly
                                    :label="trans('admin::app.catalog.attributes.edit.code')"
                                    :placeholder="trans('admin::app.catalog.attributes.edit.code')"
                                />

                                <x-admin::form.control-group.control
                                    type="hidden"
                                    name="code"
                                    :value="$selectedOption"
                                />

                                <x-admin::form.control-group.error control-name="code" />
                            </x-admin::form.control-group>

                            <!-- Attribute Type -->
                            <x-admin::form.control-group>
                                <x-admin::form.control-group.label class="required">
                                    @lang('admin::app.catalog.attributes.edit.type')
                                </x-admin::form.control-group.label>

                                @php
                                    $selectedOption = old('type') ?: $attribute->type;
                                @endphp

                                <x-admin::form.control-group.control
                                    type="select"
                                    id="type"
                                    class="cursor-not-allowed"
                                    name="type"
                                    rules="required"
                                    :value="$selectedOption"
                                    :disabled="(boolean) $selectedOption"
                                    :label="trans('admin::app.catalog.attributes.edit.type')"
                                >
                                    <!-- Here! All Needed types are defined -->
                                    @foreach(['text', 'textarea', 'price', 'boolean', 'select', 'multiselect', 'datetime', 'date', 'image', 'file', 'checkbox'] as $type)
                                        <option
                                            value="{{ $type }}"
                                            {{ $selectedOption == $type ? 'selected' : '' }}
                                        >
                                            @lang('admin::app.catalog.attributes.edit.'. $type)
                                        </option>
                                    @endforeach
                                </x-admin::form.control-group.control>

                                <x-admin::form.control-group.control
                                    type="hidden"
                                    name="type"
                                    :value="$attribute->type"
                                />

                                <x-admin::form.control-group.error control-name="type" />
                            </x-admin::form.control-group>

                            <!-- Textarea Switcher -->
                            @if($attribute->type == 'textarea')
                                <x-admin::form.control-group>
                                    <x-admin::form.control-group.label>
                                        @lang('admin::app.catalog.attributes.edit.enable-wysiwyg')
                                    </x-admin::form.control-group.label>

                                    <input
                                        type="hidden"
                                        name="enable_wysiwyg"
                                        value="0"
                                    />

                                    @php $selectedOption = old('enable_wysiwyg') ?: $attribute->enable_wysiwyg @endphp

                                    <x-admin::form.control-group.control
                                        type="switch"
                                        name="enable_wysiwyg"
                                        value="1"
                                        :label="trans('admin::app.catalog.attributes.edit.enable-wysiwyg')"
                                        :checked="(bool) $selectedOption"
                                    />
                                </x-admin::form.control-group>
                            @endif

                            <!-- Default Value -->
                            <x-admin::form.control-group class="!mb-0">
                                <x-admin::form.control-group.label>
                                    @lang('admin::app.catalog.attributes.edit.default-value')
                                </x-admin::form.control-group.label>

                                <x-admin::form.control-group.control
                                    type="text"
                                    name="default_value"
                                    value="{{ old('default_value') ?: $attribute->default_value }}"
                                    :label="trans('admin::app.catalog.attributes.edit.default-value')"
                                />

                                <x-admin::form.control-group.error control-name="default_value" />
                            </x-admin::form.control-group>
                        </x-slot>
                    </x-admin::accordion>
                    
                    {!! view_render_event('bagisto.admin.catalog.attributes.edit.card.accordion.general.after', ['attribute' => $attribute]) !!}

                    {!! view_render_event('bagisto.admin.catalog.attributes.edit.card.accordion.validations.before', ['attribute' => $attribute]) !!}

                    <!-- Validations -->
                    <x-admin::accordion>
                        <x-slot:header>
                            <p class="p-2.5 text-base font-semibold text-gray-800 dark:text-white">
                                @lang('admin::app.catalog.attributes.edit.validations')
                            </p>
                        </x-slot>

                        <x-slot:content>
                            <!-- Input Validation -->
                            @if($attribute->type == 'text')
                                <x-admin::form.control-group>
                                    <x-admin::form.control-group.label>
                                        @lang('admin::app.catalog.attributes.edit.input-validation')
                                    </x-admin::form.control-group.label>

                                        <x-admin::form.control-group.control
                                            type="select"
                                            class="cursor-not-allowed"
                                            name="validation"
                                            :value="$attribute->validation"
                                            disabled="disabled"
                                        >
                                            <!-- Here! All Needed types are defined -->
                                            @foreach(['numeric', 'email', 'decimal', 'url', 'regex'] as $type)
                                                <option value="{{ $type }}" {{ $attribute->validation == $type ? 'selected' : '' }}>
                                                    @lang('admin::app.catalog.attributes.edit.' . $type)
                                                </option>
                                            @endforeach
                                        </x-admin::form.control-group.control>
                                    </x-admin::form.control-group>
                                @endif

                                <!-- REGEX -->
                                @if($attribute->validation == "regex")
                                    <x-admin::form.control-group>
                                        <x-admin::form.control-group.label>
                                            @lang('admin::app.catalog.attributes.create.regex')
                                        </x-admin::form.control-group.label>

                                        <v-field
                                            type="text"
                                            name="regex"
                                            :value="{{ json_encode($attribute->regex) }}"
                                            label="{{ trans('admin::app.catalog.attributes.create.regex') }}"
                                            v-slot="{ field }"
                                        >
                                            <input
                                                type="text"
                                                name="regex"
                                                id="regex"
                                                v-bind="field"
                                                :value="{{ json_encode($attribute->regex) }}"
                                                :class="[errors['{{ $attribute->regex }}'] ? 'border border-red-600 hover:border-red-600' : '']"
                                                class="flex min-h-[39px] w-full cursor-not-allowed rounded-md border px-3 py-2 text-sm text-gray-600 transition-all hover:border-gray-400 focus:border-gray-400 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300 dark:hover:border-gray-400 dark:focus:border-gray-400"
                                                placeholder="{{ trans('admin::app.catalog.attributes.create.regex') }}"
                                                disabled
                                            >
                                        </v-field>

                                        <!-- Regex Info -->
                                        <p class="mt-2 text-xs font-medium text-gray-500 dark:text-gray-300">
                                            @lang('admin::app.catalog.attributes.create.regex-info')
                                        </p>
                                    </x-admin::form.control-group>
                                @endif

                            <!-- Is Required -->
                            <x-admin::form.control-group class="!mb-2 flex select-none items-center gap-2.5">
                                @php
                                    $selectedOption = old('is_required') ?? $attribute->is_required
                                @endphp

                                <x-admin::form.control-group.control
                                    type="hidden"
                                    name="is_required"
                                    :value="(boolean) $selectedOption"
                                />

                                <x-admin::form.control-group.control
                                    type="checkbox"
                                    name="is_required"
                                    id="is_required"
                                    for="is_required"
                                    value="1"
                                    :checked="(boolean) $selectedOption"
                                />

                                <label
                                    class="cursor-pointer text-xs font-medium text-gray-600 dark:text-gray-300"
                                    for="is_required"
                                >
                                    @lang('admin::app.catalog.attributes.edit.is-required')
                                </label>
                            </x-admin::form.control-group>

                            <!-- Is Unique -->
                            <x-admin::form.control-group class="!mb-0 flex select-none items-center gap-2.5">
                                <x-admin::form.control-group.control
                                    type="checkbox"
                                    id="is_unique"
                                    name="is_unique"
                                    value="1"
                                    for="is_unique"
                                    :checked="(boolean) $attribute->is_unique"
                                    :disabled="(boolean) $attribute->is_unique"
                                />

                                <label
                                    class="cursor-pointer text-xs font-medium text-gray-600 dark:text-gray-300"
                                    for="is_unique"
                                >
                                    @lang('admin::app.catalog.attributes.edit.is-unique')
                                </label>    

                                <x-admin::form.control-group.control
                                    type="hidden"
                                    :name="$type"
                                    :value="$attribute->is_unique"
                                />
                            </x-admin::form.control-group>
                        </x-slot>
                    </x-admin::accordion>

                    {!! view_render_event('bagisto.admin.catalog.attributes.edit.card.accordion.validations.after', ['attribute' => $attribute]) !!}

                    {!! view_render_event('bagisto.admin.catalog.attributes.edit.card.accordion.configuration.before', ['attribute' => $attribute]) !!}

                    <!-- Configurations -->
                    <x-admin::accordion>
                        <x-slot:header>
                            <p class="p-2.5 text-base font-semibold text-gray-800 dark:text-white">
                                @lang('admin::app.catalog.attributes.edit.configuration')
                            </p>
                        </x-slot>

                        <x-slot:content>
                            <!-- Value Per Locale -->
                            <x-admin::form.control-group class="!mb-2 flex select-none items-center gap-2.5 opacity-70">
                                @php
                                    $valuePerLocale = old('value_per_locale') ?? $attribute->value_per_locale;
                                @endphp

                                <x-admin::form.control-group.control
                                    type="checkbox"
                                    id="value_per_locale"
                                    name="value_per_locale"
                                    value="1"
                                    :checked="(boolean) $valuePerLocale"
                                    :disabled="(boolean) $valuePerLocale"
                                />

                                <label
                                    class="cursor-not-allowed text-xs font-medium text-gray-600 dark:text-gray-300"
                                >
                                    @lang('admin::app.catalog.attributes.edit.value-per-locale')
                                </label>   

                                <x-admin::form.control-group.control
                                    type="hidden"
                                    name="value_per_locale"
                                    :value="(boolean) $valuePerLocale"
                                />
                            </x-admin::form.control-group>

                            <!-- Value Per Channel -->
                            <x-admin::form.control-group class="!mb-2 flex select-none items-center gap-2.5 opacity-70">
                                @php
                                    $valuePerChannel = old('value_per_channel') ?? $attribute->value_per_channel
                                @endphp

                                <x-admin::form.control-group.control
                                    type="checkbox"
                                    id="value_per_channel"
                                    name="value_per_channel"
                                    value="1"
                                    :checked="(boolean) $valuePerChannel"
                                    :disabled="(boolean) $valuePerChannel"
                                />

                                <label class="cursor-not-allowed text-xs font-medium text-gray-600 dark:text-gray-300">
                                    @lang('admin::app.catalog.attributes.edit.value-per-channel')
                                </label>   

                                <x-admin::form.control-group.control
                                    type="hidden"
                                    name="value_per_channel"
                                    :value="(boolean) $valuePerChannel"
                                />
                            </x-admin::form.control-group>

                            <!-- Use in Layered -->
                            <x-admin::form.control-group class="!mb-2 flex select-none items-center gap-2.5">
                                @php
                                    $isFilterable = old('is_filterable') ?? $attribute->is_filterable;
                                @endphp

                                <x-admin::form.control-group.control
                                    type="checkbox"
                                    id="is_filterable"
                                    name="is_filterable"
                                    value="1"
                                    for="is_filterable"
                                    :checked="(boolean) $isFilterable"
                                />

                                <label
                                    class="cursor-pointer text-xs font-medium text-gray-600 dark:text-gray-300"
                                    for="is_filterable"
                                >
                                    @lang('admin::app.catalog.attributes.edit.is-filterable')
                                </label> 

                                <x-admin::form.control-group.control
                                    type="hidden"
                                    name="is_filterable"
                                    :value="(boolean) $isFilterable"
                                />
                            </x-admin::form.control-group>

                            <!-- Use to create configuable product -->
                            <x-admin::form.control-group class="!mb-2 flex select-none items-center gap-2.5">
                                @php
                                    $isConfigurable = old('is_configurable') ?? $attribute->is_configurable;
                                @endphp

                                <x-admin::form.control-group.control
                                    type="checkbox"
                                    id="is_configurable"
                                    name="is_configurable"
                                    value="1"
                                    for="is_configurable"
                                    :checked="(boolean) $isConfigurable"
                                />

                                <label
                                    class="cursor-pointer text-xs font-medium text-gray-600 dark:text-gray-300"
                                    for="is_configurable"
                                >
                                    @lang('admin::app.catalog.attributes.edit.is-configurable')
                                </label> 

                                <x-admin::form.control-group.control
                                    type="hidden"
                                    name="is_configurable"
                                    :value="(boolean) $isConfigurable"
                                />
                            </x-admin::form.control-group>

                            <!-- Visible On Product View Page On Fornt End -->
                            <x-admin::form.control-group class="!mb-2 flex select-none items-center gap-2.5">
                                @php
                                    $isVisibleOnFront = old('is_visible_on_front') ?? $attribute->is_visible_on_front;
                                @endphp

                                <x-admin::form.control-group.control
                                    type="checkbox"
                                    id="is_visible_on_front"
                                    name="is_visible_on_front"
                                    for="is_visible_on_front"
                                    value="1"
                                    :checked="(boolean) $isVisibleOnFront"
                                />

                                <label
                                    class="cursor-pointer text-xs font-medium text-gray-600 dark:text-gray-300"
                                    for="is_visible_on_front"
                                >
                                    @lang('admin::app.catalog.attributes.edit.is-visible-on-front')
                                </label> 

                                <x-admin::form.control-group.control
                                    type="hidden"
                                    name="is_visible_on_front"
                                    :value="(boolean) $isVisibleOnFront"
                                />
                            </x-admin::form.control-group>

                            <!-- Attribute is Comparable -->
                            <x-admin::form.control-group class="!mb-0 flex select-none items-center gap-2.5">
                                @php
                                    $isComparable = old('is_comparable') ?? $attribute->is_comparable
                                @endphp

                                <x-admin::form.control-group.control
                                    type="checkbox"
                                    id="is_comparable"
                                    name="is_comparable"
                                    value="1"
                                    for="is_comparable"
                                    :checked="(boolean) $isComparable"
                                />

                                <label
                                    class="cursor-pointer text-xs font-medium text-gray-600 dark:text-gray-300"
                                    for="is_comparable"
                                >
                                    @lang('admin::app.catalog.attributes.edit.is-comparable')
                                </label> 

                                <x-admin::form.control-group.control
                                    type="hidden"
                                    name="is_comparable"
                                    :value="(boolean) $isComparable"
                                />
                            </x-admin::form.control-group>
                        </x-slot>
                    </x-admin::accordion>

                    {!! view_render_event('bagisto.admin.catalog.attributes.edit.card.accordion.configuration.configuration.after', ['attribute' => $attribute]) !!}
                </div>
            </div>

            <!-- Add Options Model Form -->
            <x-admin::form
                v-slot="{ meta, errors, handleSubmit }"
                as="div"
                ref="modelForm"
            >
                <form
                    @submit.prevent="handleSubmit($event, storeOptions)"
                    enctype="multipart/form-data"
                    ref="editOptionsForm"
                >
                    <x-admin::modal
                        @toggle="listenModel"
                        ref="addOptionsRow"
                    >
                        <!-- Modal Header -->
                        <x-slot:header>
                            <p class="text-lg font-bold text-gray-800 dark:text-white">
                                @lang('admin::app.catalog.attributes.edit.add-option')
                            </p>
                        </x-slot>

                        <!-- Modal Content -->
                        <x-slot:content>
                            <div class="grid">
                                <!-- Image Input -->
                                <x-admin::form.control-group
                                    class="w-full"
                                    v-if="swatchType == 'image'"
                                >
                                    <x-admin::form.control-group.label>
                                        @lang('admin::app.catalog.attributes.edit.image')
                                    </x-admin::form.control-group.label>

                                    <div class="hidden">
                                        <x-admin::media.images
                                            name="swatch_value[]"
                                            ::uploaded-images='swatchValue.image'
                                        />
                                    </div>

                                    <v-media-images
                                        name="swatch_value"
                                        :uploaded-images='swatchValue.image'
                                    >
                                    </v-media-images>

                                    <x-admin::form.control-group.error control-name="swatch_value" />
                                </x-admin::form.control-group>

                                <!-- Color Input -->
                                <x-admin::form.control-group
                                    class="w-2/6"
                                    v-if="swatchType == 'color'"
                                >
                                    <x-admin::form.control-group.label>
                                        @lang('admin::app.catalog.attributes.edit.color')
                                    </x-admin::form.control-group.label>

                                    <x-admin::form.control-group.control
                                        type="color"
                                        name="swatch_value"
                                        :placeholder="trans('admin::app.catalog.attributes.edit.color')"
                                    />

                                    <x-admin::form.control-group.error control-name="swatch_value[]" />
                                </x-admin::form.control-group>
                            </div>

                            <div class="grid grid-cols-3 gap-4">
                                <!-- Hidden Id Input -->
                                <x-admin::form.control-group.control
                                    type="hidden"
                                    name="id"
                                />

                                <!-- Hidden IsNew Input -->
                                <x-admin::form.control-group.control
                                    type="hidden"
                                    name="isNew"
                                    ::value="optionIsNew"
                                />

                                <!-- Admin Input -->
                                <x-admin::form.control-group class="mb-2.5 w-full">
                                    <x-admin::form.control-group.label ::class="{ 'required' : ! isNullOptionChecked }">
                                        @lang('admin::app.catalog.attributes.edit.admin')
                                    </x-admin::form.control-group.label>

                                    <x-admin::form.control-group.control
                                        type="text"
                                        name="admin_name"
                                        ::rules="{ 'required' : ! isNullOptionChecked }"
                                        :label="trans('admin::app.catalog.attributes.edit.admin')"
                                        :placeholder="trans('admin::app.catalog.attributes.edit.admin')"
                                        ref="inputAdmin"
                                    />

                                    <x-admin::form.control-group.error control-name="admin_name" />
                                </x-admin::form.control-group>

                                <!-- Locales Input -->
                                @foreach ($locales as $locale)
                                    <x-admin::form.control-group class="mb-2.5 w-full">
                                        <x-admin::form.control-group.label ::class="{ '{{ core()->getDefaultLocaleCodeFromDefaultChannel() == $locale->code ? 'required' : '' }}' : ! isNullOptionChecked }">
                                            {{ $locale->name }} ({{ strtoupper($locale->code) }})
                                        </x-admin::form.control-group.label>

                                        <x-admin::form.control-group.control
                                            type="text"
                                            name="locales.{{ $locale->code }}"
                                            ::rules="{ '{{ core()->getDefaultLocaleCodeFromDefaultChannel() == $locale->code ? 'required' : '' }}' : ! isNullOptionChecked }"
                                            :label="$locale->name"
                                            :placeholder="$locale->name"
                                        />

                                        <x-admin::form.control-group.error control-name="locales.{{ $locale->code }}" />
                                    </x-admin::form.control-group>
                                @endforeach
                            </div>
                        </x-slot>

                        <!-- Modal Footer -->
                        <x-slot:footer>
                            <!-- Save Button -->
                            <x-admin::button
                                button-type="button"
                                class="primary-button"
                                :title="trans('admin::app.catalog.attributes.edit.option.save-btn')"
                            />
                        </x-slot>
                    </x-admin::modal>
                </form>
            </x-admin::form>
        </script>

        <script type="module">
            app.component('v-edit-attributes', {
                template: '#v-edit-attributes-template',

                data() {
                    return {
                        showSwatch: {{ in_array($attribute->type, ['select', 'checkbox', 'price', 'multiselect']) ? 'true' : 'false' }},

                        swatchType: "{{ $attribute->swatch_type == '' ? 'dropdown' : $attribute->swatch_type }}",

                        isNullOptionChecked: false,

                        swatchValue: [
                            {
                                image: [],
                            }
                        ],

                        optionsData: [],

                        locales: @json($locales),

                        optionIsNew: true,

                        optionId: 0,
                    }
                },

                created () {
                    this.getAttributesOption();
                },

                methods: {
                    storeOptions(params, { resetForm, setValues }) {
                        const lastId = this.optionsData.map(item => item.id).pop() ?? 0 ;

                        if (! params.id) {
                            params.id = `options_${lastId + 1}`;

                            this.optionId++;
                        }

                        let foundIndex = this.optionsData.findIndex(item => item.id === params.id);

                        if (foundIndex !== -1) {
                            params.isNew = String(params.id).startsWith('options_');

                            this.optionsData.splice(foundIndex, 1, params);
                        } else {
                            this.optionsData.push(params);
                        }

                        let formData = new FormData(this.$refs.editOptionsForm);

                        const sliderImage = formData.get("swatch_value[]");

                        if (sliderImage) {
                            params.swatch_value = sliderImage;
                        }

                        this.$refs.addOptionsRow.toggle();

                        if (params.swatch_value instanceof File) {
                            this.setFile(sliderImage, params.id);
                        }

                        resetForm();
                    },

                    editOptions(value) {
                        this.optionIsNew = false;

                        this.swatchValue = {
                            image: value.swatch_value_url
                            ? [{ id: value.id, url: value.swatch_value_url }]
                            : [],
                        };

                        this.$refs.modelForm.setValues({
                            id: value.id,
                            admin_name: value.admin_name,
                            swatch_value: value.swatch_value,
                            swatch_value_url: value.swatch_value_url,
                            isNew: false,
                            locales: {
                                ...value.locales,
                            }
                        });

                        this.$refs.addOptionsRow.toggle();
                    },

                    removeOption(id) {
                        this.$emitter.emit('open-confirm-modal', {
                            agree: () => {
                                let foundIndex = this.optionsData.findIndex(item => item.id === id);

                                if (foundIndex !== -1) {
                                    if (this.optionsData[foundIndex].isNew) {
                                        this.optionsData.splice(foundIndex, 1);
                                    } else {
                                        this.optionsData[foundIndex].isDelete = true;
                                    }
                                }

                                this.$emitter.emit('add-flash', { type: 'success', message: "@lang('admin::app.catalog.attributes.edit.option-deleted')" });
                            }
                        });
                    },

                    listenModel(event) {
                        if (! event.isActive) {
                            this.isNullOptionChecked = false;
                        }
                    },

                    getAttributesOption() {
                        this.$axios.get(`{{ route('admin.catalog.attributes.options', $attribute->id) }}`)
                            .then(response => {
                                let options = response.data;

                                options.forEach((option) => {
                                    let row = {
                                        'id': option.id,
                                        'admin_name': option.admin_name,
                                        'sort_order': option.sort_order,
                                        'swatch_value': option.swatch_value,
                                        'swatch_value_url': option.swatch_value_url,
                                        'notRequired': '',
                                        'locales': {},
                                        'isNew': false,
                                        'isDelete': false,
                                    };

                                    if (! option.label) {
                                        this.isNullOptionChecked = true;
                                        this.idNullOption = option.id;
                                        row['notRequired'] = true;
                                    } else {
                                        row['notRequired'] = false;
                                    }

                                    option.translations.forEach((translation) => {
                                        row['locales'][translation.locale] = translation.label ?? '';
                                    });

                                    this.optionsData.push(row);
                                });
                            });
                    },

                    setFile(file, id) {
                        let dataTransfer = new DataTransfer();

                        dataTransfer.items.add(file);

                        // Use Set timeout because need to wait for render dom before set the src or get the ref value
                        setTimeout(() => {
                            this.$refs['image_' + id].src =  URL.createObjectURL(file);

                            this.$refs['imageInput_' + id].files = dataTransfer.files;
                        }, 0);
                    }
                },
            });
        </script>
    @endPushOnce
</x-admin::layouts>
