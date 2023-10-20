@php
    $allLocales = app('Webkul\Core\Repositories\LocaleRepository')->all();
@endphp

<x-admin::layouts>
    {{-- Title of the page --}}
    <x-slot:title>
        @lang('admin::app.catalog.attributes.edit.title')
    </x-slot:title>

    {{-- Edit Attributes Vue Components --}}
    <v-edit-attributes :all-locales="{{ $allLocales->toJson() }}"></v-edit-attributes>

    @pushOnce('scripts')
        <script
            type="text/x-template"
            id="v-edit-attributes-template"
        >

            {!! view_render_event('bagisto.admin.catalog.attributes.edit.before') !!}

            <!-- Input Form -->
            <x-admin::form
                :action="route('admin.catalog.attributes.update', $attribute->id)"
                enctype="multipart/form-data"
                method="PUT"
            >
                <div class="flex justify-between items-center">
                    <p class="text-[20px] text-gray-800 dark:text-white font-bold">
                        @lang('admin::app.catalog.attributes.edit.title')
                    </p>
        
                    <div class="flex gap-x-[10px] items-center">
                        <!-- Cancel Button -->
                        <a
                            href="{{ route('admin.catalog.attributes.index') }}"
                            class="transparent-button hover:bg-gray-200 dark:hover:bg-gray-800 dark:text-white "
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

                <!-- body content -->
                <div class="flex gap-[10px] mt-[14px]">
                    <!-- Left sub Component -->
                    <div class="flex flex-col flex-1 gap-[8px] overflow-auto">

                        {!! view_render_event('bagisto.admin.catalog.attributes.edit.card.label.before', ['attribute' => $attribute]) !!}

                        <!-- Label -->
                        <div class="p-[16px] bg-white dark:bg-gray-900 box-shadow rounded-[4px]">
                            <p class="mb-[16px] text-[16px] text-gray-800 dark:text-white font-semibold">
                                @lang('admin::app.catalog.attributes.edit.label')
                            </p>

                            <!-- Admin name -->
                            <x-admin::form.control-group class="mb-[10px]">
                                <x-admin::form.control-group.label class="required">
                                    @lang('admin::app.catalog.attributes.edit.admin')
                                </x-admin::form.control-group.label>

                                <x-admin::form.control-group.control
                                    type="text"
                                    name="admin_name"
                                    :value="old('admin_name') ?: $attribute->admin_name"
                                    rules="required"
                                    :label="trans('admin::app.catalog.attributes.edit.admin')"
                                    :placeholder="trans('admin::app.catalog.attributes.edit.admin')"
                                >
                                </x-admin::form.control-group.control>

                                <x-admin::form.control-group.error
                                    control-name="admin_name"
                                >
                                </x-admin::form.control-group.error>
                            </x-admin::form.control-group>

                            <!-- Locales Inputs -->
                            @foreach ($allLocales as $locale)
                                <x-admin::form.control-group class="mb-[10px]">
                                    <x-admin::form.control-group.label>
                                        {{ $locale->name . ' (' . strtoupper($locale->code) . ')' }}
                                    </x-admin::form.control-group.label>

                                    <x-admin::form.control-group.control
                                        type="text"
                                        :name="$locale->code . '[name]'"
                                        :value="old($locale->code)['name'] ?? ($attribute->translate($locale->code)->name ?? '')"
                                        :placeholder="$locale->name"
                                    >
                                    </x-admin::form.control-group.control>

                                    <x-admin::form.control-group.error
                                        :control-name="$locale->code . '[name]'"
                                    >
                                    </x-admin::form.control-group.error>
                                </x-admin::form.control-group>    
                            @endforeach
                        </div>

                        {!! view_render_event('bagisto.admin.catalog.attributes.edit.card.label.after', ['attribute' => $attribute]) !!}

                        <!-- Options -->
                        <div
                            class="p-[16px] bg-white dark:bg-gray-900 box-shadow rounded-[4px] {{ in_array($attribute->type, ['select', 'multiselect', 'checkbox', 'price']) ?: 'hidden' }}"
                            v-if="showSwatch"
                        >
                            <div class="flex justify-between items-center mb-3">
                                <p class="mb-[16px] text-[16px] text-gray-800 dark:text-white font-semibold">
                                    @lang('admin::app.catalog.attributes.edit.title')
                                </p>

                                <!-- Add Row Button -->
                                <div
                                    class="secondary-button text-[14px]"
                                    @click="$refs.addOptionsRow.toggle()"
                                >
                                    @lang('admin::app.catalog.attributes.edit.add-row')
                                </div>
                            </div>

                            <!-- For Attribute Options If Data Exist -->
                            @if (
                                $attribute->type == 'select'
                                || $attribute->type == 'multiselect'
                                || $attribute->type == 'checkbox'
                                || $attribute->type == 'price'
                            )
                                <div class="flex gap-[16px] max-sm:flex-wrap">
                                    <!-- Input Options -->
                                    <x-admin::form.control-group
                                        class="w-full mb-[10px]"
                                        v-if="this.showSwatch"
                                    >
                                        <x-admin::form.control-group.label for="swatchType">
                                            @lang('admin::app.catalog.attributes.edit.input-options')
                                        </x-admin::form.control-group.label>

                                        <x-admin::form.control-group.control
                                            type="select"
                                            name="swatch_type"
                                            id="swatchType"
                                            v-model="swatchType"
                                            @change="showSwatch=true"
                                        >
                                            @foreach (['dropdown', 'color', 'image', 'text'] as $type)
                                                <option value="{{ $type }}">
                                                    @lang('admin::app.catalog.attributes.edit.option.' . $type)
                                                </option>
                                            @endforeach
                                        </x-admin::form.control-group.control>

                                        <x-admin::form.control-group.error
                                            control-name="admin"
                                        >
                                        </x-admin::form.control-group.error>
                                    </x-admin::form.control-group>

                                    <div class="w-full mb-[10px]">
                                        <!-- checkbox -->
                                        <x-admin::form.control-group.label class="invisible">
                                            @lang('admin::app.catalog.attributes.edit.input-options')
                                        </x-admin::form.control-group.label>

                                        <div class="flex gap-[10px] w-max !mb-0 p-[6px] cursor-pointer select-none">
                                            <input 
                                                type="checkbox"
                                                name="empty_option"
                                                id="empty_option"
                                                for="empty_option"
                                                class="hidden peer"
                                                v-model="isNullOptionChecked"
                                                @click="$refs.addOptionsRow.toggle()"
                                            >

                                            <label
                                                for="empty_option"
                                                class="icon-uncheckbox text-[24px] rounded-[6px] cursor-pointer peer-checked:icon-checked peer-checked:text-blue-600 "
                                            >
                                            </label>

                                            <label
                                                for="empty_option"
                                                class="text-[14px] text-gray-600 dark:text-gray-300 font-semibold cursor-pointer"
                                            >
                                                @lang('admin::app.catalog.attributes.edit.create-empty-option')
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <!-- Table Information -->
                                <div class="mt-[15px] overflow-x-auto">
                                    <x-admin::table>
                                        <x-admin::table.thead class="text-[14px] font-medium dark:bg-gray-800">
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

                                                <!-- Loacles tables heading -->
                                                @foreach ($allLocales as $locale)
                                                    <x-admin::table.th>
                                                        {{ $locale->name . ' (' . $locale->code . ')' }}
                                                    </x-admin::table.th>
                                                @endforeach

                                                <!-- Action tables heading -->
                                                <x-admin::table.th></x-admin::table.th>
                                            </x-admin::table.thead.tr>
                                        </x-admin::table.thead>

                                        <!-- Draggable Component -->
                                        <draggable
                                            tag="tbody"
                                            ghost-class="draggable-ghost"
                                            v-bind="{animation: 200}"
                                            :list="optionsData"
                                            item-key="id"
                                        >
                                            <template #item="{ element, index }" v-show="! element.isDelete">
                                                <x-admin::table.thead.tr class="text-center hover:bg-gray-50 dark:hover:bg-gray-950">
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
                                                    <x-admin::table.td class="!px-0">
                                                        <i class="icon-drag text-[20px] transition-all group-hover:text-gray-700"></i>

                                                        <input
                                                            type="hidden"
                                                            :name="'options[' + element.id + '][position]'"
                                                            :value="index"
                                                        />
                                                    </x-admin::table.td>
 
                                                    <!-- Swatch Type Image / Color -->
                                                    <x-admin::table.td v-if="showSwatch && (swatchType == 'color' || swatchType == 'image')">
                                                        <!-- Swatch Image -->
                                                        <div v-if="swatchType == 'image'">
                                                            <img 
                                                                :src="element.swatch_value_url"
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
                                                                class="w-[25px] h-[25px] mx-auto rounded-[5px]"
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
                                                        <p
                                                            class="dark:text-white"
                                                            v-text="element.admin_name"
                                                        >
                                                        </p>

                                                        <input
                                                            type="hidden"
                                                            :name="'options[' + element.id + '][admin_name]'"
                                                            v-model="element.admin_name"
                                                        />
                                                    </x-admin::table.td>

                                                    <!-- Loacles -->
                                                     <x-admin::table.td v-for="locale in allLocales">
                                                        <p
                                                            class="dark:text-white"
                                                            v-text="element['locales'][locale.code]"
                                                        >
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
                                                            class="icon-edit p-[6px] rounded-[6px] text-[24px] cursor-pointer transition-all hover:bg-gray-200 dark:hover:bg-gray-800 max-sm:place-self-center"
                                                            @click="editOptions(element)"
                                                        >
                                                        </span>
                                                        
                                                        <span
                                                            class="icon-delete p-[6px] rounded-[6px] text-[24px] cursor-pointer transition-all hover:bg-gray-100 dark:hover:bg-gray-800  max-sm:place-self-center"
                                                            @click="removeOption(element.id)"
                                                        >
                                                        </span>
                                                    </x-admin::table.td>
                                                </x-admin::table.thead.tr>
                                            </template>
                                        </draggable>
                                    </x-admin::table>
                                </div>
                            @else
                                <!-- For Empty Attribute Options -->
                                <template>
                                    <div class="grid gap-[14px] justify-items-center py-[40px] px-[10px]">
                                        <!-- Attribute Option Image -->
                                        <img 
                                            class="w-[120px] h-[120px] border border-dashed border-gray-300 dark:border-gray-800 rounded-[4px]" 
                                            src="{{ bagisto_asset('images/icon-add-product.svg') }}" 
                                            alt="{{ trans('admin::app.catalog.attributes.edit.add-attribute-options') }}"
                                        >

                                        <!-- Add Attribute Options Information -->
                                        <div class="flex flex-col items-center">
                                            <p class="text-[16px] text-gray-400 font-semibold">
                                                @lang('admin::app.catalog.attributes.edit.add-attribute-options')
                                            </p>

                                            <p class="text-gray-400">
                                                @lang('admin::app.catalog.attributes.edit.add-options-info')
                                            </p>
                                        </div>

                                        <!-- Add Row Button -->
                                        <div 
                                            class="secondary-button text-[14px]"
                                            @click="$refs.addOptionsRow.toggle()"
                                        >
                                            @lang('admin::app.catalog.attributes.edit.add-row')
                                        </div>
                                    </div>
                                </template>
                            @endif
                        </div>
                    </div>

                    <!-- Right sub-component -->
                    <div class="flex flex-col gap-[8px] w-[360px] max-w-full">

                        {!! view_render_event('bagisto.admin.catalog.attributes.edit.card.accordian.general.before', ['attribute' => $attribute]) !!}

                        <!-- General -->
                        <div class="bg-white dark:bg-gray-900 box-shadow rounded-[4px]">
                            <div class="flex justify-between items-center p-[6px]">
                                <p class="p-[10px] text-gray-800 dark:text-white text-[16px] font-semibold">
                                    @lang('admin::app.catalog.attributes.edit.general')
                                </p>
                            </div>

                            <div class="px-[16px] pb-[16px]">
                                <!-- Attribute Code -->
                                <x-admin::form.control-group class="mb-[10px]">
                                    <x-admin::form.control-group.label class="required">
                                        @lang('admin::app.catalog.attributes.edit.code')
                                    </x-admin::form.control-group.label>

                                    @php
                                        $selectedOption = old('type') ?: $attribute->code;
                                    @endphp

                                    <x-admin::form.control-group.control
                                        type="text"
                                        name="code"
                                        :value="$selectedOption"
                                        class="cursor-not-allowed"
                                        rules="required"
                                        :disabled="(boolean) $selectedOption"
                                        readonly
                                        :label="trans('admin::app.catalog.attributes.edit.code')"
                                        :placeholder="trans('admin::app.catalog.attributes.edit.code')"
                                    >
                                    </x-admin::form.control-group.control>

                                    <x-admin::form.control-group.control
                                        type="hidden"
                                        name="code"
                                        :value="$selectedOption"
                                    >
                                    </x-admin::form.control-group.control>

                                    <x-admin::form.control-group.error
                                        control-name="code"
                                    >
                                    </x-admin::form.control-group.error>
                                </x-admin::form.control-group>

                                <!-- Attribute Type -->
                                <x-admin::form.control-group class="mb-[10px]">
                                    <x-admin::form.control-group.label class="required">
                                        @lang('admin::app.catalog.attributes.edit.type')
                                    </x-admin::form.control-group.label>

                                    @php
                                        $selectedOption = old('type') ?: $attribute->type;
                                    @endphp

                                    <x-admin::form.control-group.control
                                        type="select"
                                        name="type"
                                        rules="required"
                                        id="type"
                                        class="cursor-not-allowed"
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
                                    >
                                    </x-admin::form.control-group.control>

                                    <x-admin::form.control-group.error
                                        control-name="type"
                                    >
                                    </x-admin::form.control-group.error>
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
                                        >
                                        </x-admin::form.control-group.control>
                                    </x-admin::form.control-group>
                                @endif

                                <!-- Default Value -->
                                <x-admin::form.control-group>
                                    <x-admin::form.control-group.label>
                                        @lang('admin::app.catalog.attributes.edit.default-value')
                                    </x-admin::form.control-group.label>

                                    <x-admin::form.control-group.control
                                        type="text"
                                        name="default_value"
                                        value="{{ old('default_value') ?: $attribute->default_value }}"
                                        :label="trans('admin::app.catalog.attributes.edit.default-value')"
                                    >
                                    </x-admin::form.control-group.control>

                                    <x-admin::form.control-group.error
                                        control-name="default_value"
                                    >
                                    </x-admin::form.control-group.error>
                                </x-admin::form.control-group>
                            </div>
                        </div>

                        {!! view_render_event('bagisto.admin.catalog.attributes.edit.card.accordian.general.after', ['attribute' => $attribute]) !!}

                        {!! view_render_event('bagisto.admin.catalog.attributes.edit.card.accordian.validations.before', ['attribute' => $attribute]) !!}

                        <!-- Validations -->
                        <x-admin::accordion>
                            <x-slot:header>
                                <p class="p-[10px] text-gray-800 dark:text-white text-[16px] font-semibold">
                                    @lang('admin::app.catalog.attributes.edit.validations')
                                </p>
                            </x-slot:header>
                        
                            <x-slot:content>
                                <!-- Input Validation -->
                                @if($attribute->type == 'text')
                                    <x-admin::form.control-group class="mb-[10px]">
                                        <x-admin::form.control-group.label>
                                            @lang('admin::app.catalog.attributes.edit.input-validation')
                                        </x-admin::form.control-group.label>

                                        <x-admin::form.control-group.control
                                            type="select"
                                            name="validation"
                                            :value="$attribute->validation"
                                            class="cursor-pointer"
                                            v-model="validationType"
                                        >
                                            <!-- Here! All Needed types are defined -->
                                            @foreach(['number', 'email', 'decimal', 'url', 'regex'] as $type)
                                            <option value="{{ $type }}">
                                                @lang('admin::app.catalog.attributes.edit.' . $type)
                                            </option>
                                        @endforeach
                                        </x-admin::form.control-group.control>
                                    </x-admin::form.control-group>
                                @endif

                                <!-- REGEX -->
                                <x-admin::form.control-group
                                    class="mb-[10px]"
                                    v-if="validationType === 'regex'"
                                >
                                    <x-admin::form.control-group.label>
                                        @lang('admin::app.catalog.attributes.create.regex')
                                    </x-admin::form.control-group.label>

                                    <x-admin::form.control-group.control
                                        type="text"
                                        name="regex"
                                        v-model="regexValue"
                                    >
                                    </x-admin::form.control-group.control>

                                    <x-admin::form.control-group.error
                                        control-name="regex"
                                    >
                                    </x-admin::form.control-group.error>
                                </x-admin::form.control-group>

                                <!-- Is Required -->
                                <x-admin::form.control-group class="flex gap-[10px] w-max !mb-0 p-[6px] select-none">
                                    @php
                                        $selectedOption = old('is_required') ?? $attribute->is_required
                                    @endphp

                                    <x-admin::form.control-group.control
                                        type="hidden"
                                        name="is_required"
                                        :value="(boolean) $selectedOption"
                                    >
                                    </x-admin::form.control-group.control>

                                    <x-admin::form.control-group.control
                                        type="checkbox"
                                        name="is_required"
                                        id="is_required"
                                        for="is_required"
                                        value="1"
                                        :checked="(boolean) $selectedOption"
                                    >
                                    </x-admin::form.control-group.control>

                                    <x-admin::form.control-group.label
                                        for="is_required"
                                        class="!text-[14px] !font-semibold !text-gray-600 dark:!text-gray-300 cursor-pointer"
                                    >
                                        @lang('admin::app.catalog.attributes.edit.is-required')
                                    </x-admin::form.control-group.label>
                                </x-admin::form.control-group>

                                <!-- Is Unique -->
                                <x-admin::form.control-group class="flex gap-[10px] w-max !mb-0 p-[6px] opacity-70 select-none">
                                    <x-admin::form.control-group.control
                                        type="checkbox"
                                        name="is_unique"
                                        id="is_unique"
                                        for="is_unique"
                                        value="1"
                                        :checked="(boolean) $attribute->is_unique"
                                        :disabled="(boolean) $attribute->is_unique"
                                    >
                                    </x-admin::form.control-group.control>

                                    <x-admin::form.control-group.label class="!text-[14px] !font-semibold !text-gray-600 dark:!text-gray-300 cursor-not-allowed">
                                        @lang('admin::app.catalog.attributes.edit.is-unique')
                                    </x-admin::form.control-group.label>

                                    <x-admin::form.control-group.control
                                        type="hidden"
                                        :name="$type"
                                        :value="$attribute->is_unique"
                                    >
                                    </x-admin::form.control-group.control>
                                </x-admin::form.control-group>
                            </x-slot:content>
                        </x-admin::accordion>

                        {!! view_render_event('bagisto.admin.catalog.attributes.edit.card.accordian.validations.after', ['attribute' => $attribute]) !!}

                        {!! view_render_event('bagisto.admin.catalog.attributes.edit.card.accordian.configuration.before', ['attribute' => $attribute]) !!}

                        <!-- Configurations -->
                        <x-admin::accordion>
                            <x-slot:header>
                                <p class="p-[10px] text-gray-800 dark:text-white text-[16px] font-semibold">
                                    @lang('admin::app.catalog.attributes.edit.configuration')
                                </p>
                            </x-slot:header>
                        
                            <x-slot:content>
                                <!-- Value Per Locale -->
                                <x-admin::form.control-group class="flex gap-[10px] w-max !mb-0 p-[6px] opacity-70 select-none">
                                    @php
                                        $selectedOption = old('value_per_locale') ?? $attribute->value_per_locale
                                    @endphp

                                    <x-admin::form.control-group.control
                                        type="checkbox"
                                        name="value_per_locale"
                                        id="value_per_locale"
                                        :checked="(boolean) $selectedOption"
                                        :disabled="(boolean) $selectedOption"
                                    >
                                    </x-admin::form.control-group.control>

                                    <x-admin::form.control-group.label class="!text-[14px] !font-semibold !text-gray-600 dark:!text-gray-300 cursor-not-allowed">
                                        @lang('admin::app.catalog.attributes.edit.value-per-locale')
                                    </x-admin::form.control-group.label>

                                    <x-admin::form.control-group.control
                                        type="hidden"
                                        name="value_per_locale"
                                        :value="(boolean) $selectedOption"
                                    >
                                    </x-admin::form.control-group.control>
                                </x-admin::form.control-group>

                                <!-- Value Per Channel -->
                                <x-admin::form.control-group class="flex gap-[10px] w-max !mb-0 p-[6px] opacity-70 select-none">
                                    @php
                                        $selectedOption = old('value_per_channel') ?? $attribute->value_per_channel
                                    @endphp

                                    <x-admin::form.control-group.control
                                        type="checkbox"
                                        name="value_per_channel"
                                        id="value_per_channel"
                                        :checked="(boolean) $selectedOption"
                                        :disabled="(boolean) $selectedOption"
                                    >
                                    </x-admin::form.control-group.control>

                                    <x-admin::form.control-group.label class="!text-[14px] !font-semibold !text-gray-600 dark:!text-gray-300 cursor-not-allowed">
                                        @lang('admin::app.catalog.attributes.edit.value-per-channel')
                                    </x-admin::form.control-group.label>

                                    <x-admin::form.control-group.control
                                        type="hidden"
                                        name="value_per_channel"
                                        :value="(boolean) $selectedOption"
                                    >
                                    </x-admin::form.control-group.control>
                                </x-admin::form.control-group>

                                <!-- Use in Layered -->
                                <x-admin::form.control-group class="flex gap-[10px] w-max !mb-0 p-[6px] cursor-pointer select-none">
                                    @php
                                        $selectedOption = $attribute->is_filterable ?? old('is_filterable')
                                    @endphp

                                    <x-admin::form.control-group.control
                                        type="checkbox"
                                        id="is_filterable"
                                        name="is_filterable"
                                        for="is_filterable"
                                        value="1"
                                        :checked="(boolean) $selectedOption"
                                    >
                                    </x-admin::form.control-group.control>

                                    <x-admin::form.control-group.label
                                        for="is_filterable"
                                        class="!text-[14px] !font-semibold !text-gray-600 dark:!text-gray-300 cursor-pointer" 
                                    >
                                        @lang('admin::app.catalog.attributes.edit.is-filterable')
                                    </x-admin::form.control-group.label>

                                    <x-admin::form.control-group.control
                                        type="hidden"
                                        name="is_filterable"
                                        :value="(boolean) $selectedOption"
                                    >
                                    </x-admin::form.control-group.control>
                                </x-admin::form.control-group>

                                <!-- Use to create configuable product -->
                                <x-admin::form.control-group class="flex gap-[10px] w-max !mb-0 p-[6px] cursor-pointer select-none">
                                    @php
                                        $selectedOption = $attribute->is_configurable ?? old('is_configurable')
                                    @endphp

                                    <x-admin::form.control-group.control
                                        type="checkbox"
                                        id="is_configurable"
                                        name="is_configurable"
                                        for="is_configurable"
                                        value="1"
                                        :checked="(boolean) $selectedOption"
                                    >
                                    </x-admin::form.control-group.control>

                                    <x-admin::form.control-group.label
                                        for="is_configurable"
                                        class="!text-[14px] !font-semibold !text-gray-600 dark:!text-gray-300 cursor-pointer" 
                                    >
                                        @lang('admin::app.catalog.attributes.edit.is-configurable')
                                    </x-admin::form.control-group.label>

                                    <x-admin::form.control-group.control
                                        type="hidden"
                                        name="is_configurable"
                                        :value="(boolean) $selectedOption"
                                    >
                                    </x-admin::form.control-group.control>
                                </x-admin::form.control-group>

                                <!-- Visible On Product View Page On Fornt End -->
                                <x-admin::form.control-group class="flex gap-[10px] w-max !mb-0 p-[6px] cursor-pointer select-none">
                                    @php
                                        $selectedOption = $attribute->is_visible_on_front ?? old('is_visible_on_front');
                                    @endphp

                                    <x-admin::form.control-group.control
                                        type="checkbox"
                                        id="is_visible_on_front"
                                        name="is_visible_on_front"
                                        for="is_visible_on_front"
                                        value="1"
                                        :checked="(boolean) $selectedOption"
                                    >
                                    </x-admin::form.control-group.control>

                                    <x-admin::form.control-group.label
                                        for="is_visible_on_front"
                                        class="!text-[14px] !font-semibold !text-gray-600 dark:!text-gray-300 cursor-pointer" 
                                    >
                                        @lang('admin::app.catalog.attributes.edit.is-visible-on-front')
                                    </x-admin::form.control-group.label>

                                    <x-admin::form.control-group.control
                                        type="hidden"
                                        name="is_visible_on_front"
                                        :value="(boolean) $selectedOption"
                                    >
                                    </x-admin::form.control-group.control>
                                </x-admin::form.control-group>

                                <!-- Attribute is Comparable -->
                                <x-admin::form.control-group class="flex gap-[10px] w-max !mb-0 p-[6px] cursor-pointer select-none">
                                    @php
                                        $selectedOption = old('is_comparable') ?? $attribute->is_comparable
                                    @endphp

                                    <x-admin::form.control-group.control
                                        type="checkbox"
                                        id="is_comparable"
                                        name="is_comparable"
                                        for="is_comparable"
                                        value="1"
                                        :checked="(boolean) $selectedOption"
                                    >
                                    </x-admin::form.control-group.control>

                                    <x-admin::form.control-group.label
                                        for="is_comparable"
                                        class="!text-[14px] !font-semibold !text-gray-600 dark:!text-gray-300 cursor-pointer" 
                                    >
                                        @lang('admin::app.catalog.attributes.edit.is-comparable')
                                    </x-admin::form.control-group.label>

                                    <x-admin::form.control-group.control
                                        type="hidden"
                                        name="is_comparable"
                                        :value="(boolean) $selectedOption"
                                    >
                                    </x-admin::form.control-group.control>
                                </x-admin::form.control-group>
                            </x-slot:content>
                        </x-admin::accordion>

                        {!! view_render_event('bagisto.admin.catalog.attributes.edit.card.accordian.configuration.configuration.after', ['attribute' => $attribute]) !!}
                    </div>
                </div>
            </x-admin::form>

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
                        <x-slot:header>
                            <p class="text-[18px] text-gray-800 dark:text-white font-bold">
                                @lang('admin::app.catalog.attributes.edit.add-option')
                            </p>
                        </x-slot:header>

                        <x-slot:content>
                            <div class="grid grid-cols-3 px-[16px] py-[10px]">
                                <!-- Image Input -->
                                <x-admin::form.control-group
                                    class="w-full"
                                    v-if="swatchType == 'image'"
                                >
                                    <x-admin::form.control-group.label>
                                        @lang('admin::app.catalog.attributes.edit.image')
                                    </x-admin::form.control-group.label>

                                    <x-admin::form.control-group.control
                                        type="image"
                                        name="swatch_value"
                                        :placeholder="trans('admin::app.catalog.attributes.edit.image')"
                                    >
                                    </x-admin::form.control-group.control>

                                    <x-admin::form.control-group.error
                                        control-name="swatch_value"
                                    >
                                    </x-admin::form.control-group.error>
                                </x-admin::form.control-group>

                                <!-- Color Input -->
                                <x-admin::form.control-group
                                    class="w-full"
                                    v-if="swatchType == 'color'"
                                >
                                    <x-admin::form.control-group.label>
                                        @lang('admin::app.catalog.attributes.edit.color')
                                    </x-admin::form.control-group.label>

                                    <x-admin::form.control-group.control
                                        type="color"
                                        name="swatch_value[]"
                                        :placeholder="trans('admin::app.catalog.attributes.edit.color')"
                                    >
                                    </x-admin::form.control-group.control>

                                    <x-admin::form.control-group.error
                                        control-name="swatch_value[]"
                                    >
                                    </x-admin::form.control-group.error>
                                </x-admin::form.control-group>
                            </div>

                            <div class="grid grid-cols-3 gap-[16px] px-[16px] py-[10px] border-b-[1px] dark:border-gray-800  ">
                                <!-- Hidden Id Input -->
                                <x-admin::form.control-group.control
                                    type="hidden"
                                    name="id"
                                >
                                </x-admin::form.control-group.control>

                                <x-admin::form.control-group.control
                                    type="hidden"
                                    name="isNew"
                                    ::value="optionIsNew"
                                >
                                </x-admin::form.control-group.control>

                                <!-- Admin Input -->
                                <x-admin::form.control-group class="w-full mb-[10px]">
                                    <x-admin::form.control-group.label ::class="{ 'required' : ! isNullOptionChecked }">
                                        @lang('admin::app.catalog.attributes.edit.admin')
                                    </x-admin::form.control-group.label>

                                    <x-admin::form.control-group.control
                                        type="text"
                                        name="admin_name"
                                        ::rules="{ 'required' : ! isNullOptionChecked }"
                                        ref="inputAdmin"
                                        :label="trans('admin::app.catalog.attributes.edit.admin')"
                                        :placeholder="trans('admin::app.catalog.attributes.edit.admin')"
                                    >
                                    </x-admin::form.control-group.control>
        
                                    <x-admin::form.control-group.error
                                        control-name="admin_name"
                                    >
                                    </x-admin::form.control-group.error>
                                </x-admin::form.control-group>

                                <!-- Locales Input -->
                                @foreach ($allLocales as $locale)
                                    <x-admin::form.control-group class="w-full mb-[10px]">
                                        <x-admin::form.control-group.label ::class="{ '{{ core()->getDefaultChannelLocaleCode() == $locale->code ? 'required' : '' }}' : ! isNullOptionChecked }">
                                            {{ $locale->name }} ({{ strtoupper($locale->code) }})
                                        </x-admin::form.control-group.label>

                                        <x-admin::form.control-group.control
                                            type="text"
                                            name="locales.{{ $locale->code }}"
                                            ::rules="{ '{{ core()->getDefaultChannelLocaleCode() == $locale->code ? 'required' : '' }}' : ! isNullOptionChecked }"
                                            :label="$locale->name"
                                            :placeholder="$locale->name"
                                        >
                                        </x-admin::form.control-group.control>
            
                                        <x-admin::form.control-group.error
                                            control-name="locales.{{ $locale->code }}"
                                        >
                                        </x-admin::form.control-group.error>
                                    </x-admin::form.control-group>
                                @endforeach
                            </div>
                        </x-slot:content>
                        
                        <x-slot:footer>
                            <!-- Save Button -->
                            <button
                                type="submit" 
                                class="primary-button"
                            >
                                @lang('admin::app.catalog.attributes.edit.option.save-btn')
                            </button>
                        </x-slot:footer>
                    </x-admin::modal>
                </form>
            </x-admin::form>

            {!! view_render_event('bagisto.admin.catalog.attributes.edit.after') !!}

        </script>

        <script type="module">
            app.component('v-edit-attributes', {
                template: '#v-edit-attributes-template',

                props: ['allLocales'],

                data: function() {
                    return {
                        optionRowCount: 1,

                        showSwatch: {{ in_array($attribute->type, ['select', 'checkbox', 'price', 'multiselect']) ? 'true' : 'false' }},

                        swatchType: "{{ $attribute->swatch_type == '' ? 'dropdown' : $attribute->swatch_type }}",

                        validationType: "{{ $attribute->validation }}",

                        regexValue: '{{ $attribute->validation }}',

                        isNullOptionChecked: false,

                        optionsData: [],

                        optionIsNew: true,

                        optionId: 0,

                        src: "{{ route('admin.catalog.attributes.options', $attribute->id) }}",
                    }
                },

                created: function () {
                    this.getAttributesOption();
                },

                methods: {
                    storeOptions(params, { resetForm, setValues }) {
                        if (! params.id) {
                            params.id = 'option_' + this.optionId;
                            this.optionId++;
                        }

                        let foundIndex = this.optionsData.findIndex(item => item.id === params.id);

                        if (foundIndex !== -1) {
                            this.optionsData.splice(foundIndex, 1, params);
                        } else {
                            this.optionsData.push(params);
                        }

                        let formData = new FormData(this.$refs.editOptionsForm);

                        const sliderImage = formData.get("swatch_value[]");

                        params.swatch_value = sliderImage;

                        this.$refs.addOptionsRow.toggle();

                        if (params.swatch_value instanceof File) {
                            this.setFile(params);
                        }

                        resetForm();
                    },

                    editOptions(value) {
                        this.optionIsNew = false;

                        this.$refs.modelForm.setValues(value);

                        this.$refs.addOptionsRow.toggle();
                    },

                    removeOption(id) {
                        let foundIndex = this.optionsData.findIndex(item => item.id === id);

                        if (foundIndex !== -1) {
                            this.optionsData.splice(foundIndex, 1);
                        }
                    },

                    listenModel(event) {
                        if (! event.isActive) {
                            this.isNullOptionChecked = false;                            
                        }
                    },

                    getAttributesOption() {
                        this.$axios.get(`${this.src}`)
                            .then(response => {
                                let options = response.data;
                                options.forEach((option) => {
                                    this.optionRowCount++;

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

                    setFile(event) {
                        let dataTransfer = new DataTransfer();

                        dataTransfer.items.add(event.swatch_value);

                        // use settimeout because need to wait for render dom before set the src or get the ref value
                        setTimeout(() => {
                            this.$refs['image_' + event.id].src =  URL.createObjectURL(event.swatch_value);
                        }, 0);

                        this.$refs['imageInput_' + event.id].files = dataTransfer.files;
                    }
                },
            });
        </script>
    @endPushOnce
</x-admin::layouts>