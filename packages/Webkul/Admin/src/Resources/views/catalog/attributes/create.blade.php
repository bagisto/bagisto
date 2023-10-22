@php
    $allLocales = app('Webkul\Core\Repositories\LocaleRepository')->all();
@endphp

<x-admin::layouts>
    {{-- Title of the page --}}
    <x-slot:title>
        @lang('admin::app.catalog.attributes.create.title')
    </x-slot:title>

    {{-- Create Attributes Vue Components --}}
    <v-create-attributes :all-locales="{{ $allLocales->toJson() }}"></v-create-attributes>

    @pushOnce('scripts')
        <script
            type="text/x-template"
            id="v-create-attributes-template"
        >

            {!! view_render_event('bagisto.admin.catalog.attributes.create.before') !!}

            <!-- Input Form -->
            <x-admin::form
                :action="route('admin.catalog.attributes.store')"
                enctype="multipart/form-data"
            >

                {!! view_render_event('bagisto.admin.catalog.attributes.create.create_form_controls.before') !!}

                <!-- actions buttons -->
                <div class="flex justify-between items-center">
                    <p class="text-[20px] text-gray-800 dark:text-white font-bold">
                        @lang('admin::app.catalog.attributes.create.title')
                    </p>
        
                    <div class="flex gap-x-[10px] items-center">
                        <!-- Cancel Button -->
                        <a
                            href="{{ route('admin.catalog.attributes.index') }}"
                            class="transparent-button hover:bg-gray-200 dark:hover:bg-gray-800 dark:text-white"
                        >
                            @lang('admin::app.catalog.attributes.create.back-btn')
                        </a>

                        <!-- Save Button -->
                        <button
                            type="submit"
                            class="primary-button"
                        >
                            @lang('admin::app.catalog.attributes.create.save-btn')
                        </button>
                    </div>
                </div>

                <!-- body content -->
                <div class="flex gap-[10px] mt-[14px]">

                    {!! view_render_event('bagisto.admin.catalog.attributes.create.card.label.before') !!}

                    <!-- Left sub Component -->
                    <div class="flex flex-col gap-[8px] flex-1 overflow-auto">
                        <!-- Label -->
                        <div class="p-[16px] bg-white dark:bg-gray-900 box-shadow rounded-[4px]">
                            <p class="mb-[16px] text-[16px] text-gray-800 dark:text-white font-semibold">
                                @lang('admin::app.catalog.attributes.create.label')
                            </p>

                            <!-- Admin name -->
                            <x-admin::form.control-group class="mb-[10px]">
                                <x-admin::form.control-group.label class="required">
                                    @lang('admin::app.catalog.attributes.create.admin')
                                </x-admin::form.control-group.label>

                                <x-admin::form.control-group.control
                                    type="text"
                                    name="admin_name"
                                    :value="old('admin_name')"
                                    rules="required"
                                    :label="trans('admin::app.catalog.attributes.create.admin')"
                                    :placeholder="trans('admin::app.catalog.attributes.create.admin')"
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
                                        :value="old($locale->code . '[name]')"
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

                        <!-- Options -->
                        <div 
                            class="p-[16px] bg-white dark:bg-gray-900 box-shadow rounded-[4px]"
                            v-if="swatchAttribute && (
                                    attributeType == 'select' 
                                    || attributeType == 'multiselect' 
                                    || attributeType == 'price' 
                                    || attributeType == 'checkbox'
                                )"
                        >
                            <div class="flex justify-between items-center mb-3">
                                <p class="mb-[16px] text-[16px] text-gray-800 dark:text-white font-semibold">
                                    @lang('admin::app.catalog.attributes.create.title')
                                </p>

                                <!-- Add Row Button -->
                                <div
                                    class="secondary-button text-[14px]"
                                    @click="$refs.addOptionsRow.toggle()"
                                >
                                    @lang('admin::app.catalog.attributes.create.add-row')
                                </div>
                            </div>

                            <!-- For Attribute Options If Data Exist -->
                            <template v-if="this.options?.length">
                                <div class="flex gap-[16px] max-sm:flex-wrap">
                                    <x-admin::form.control-group class="w-full mb-[10px]">
                                        <x-admin::form.control-group.label>
                                            @lang('admin::app.catalog.attributes.create.input-options')
                                        </x-admin::form.control-group.label>

                                        <x-admin::form.control-group.control
                                            type="select"
                                            name="swatch_type"
                                            id="swatchType"
                                            :value="old('swatch_type')"
                                            v-model="swatchType"
                                            @change="showSwatch=true"
                                        >
                                            @foreach (['dropdown', 'color', 'image', 'text'] as $type)
                                                <option value="{{ $type }}">
                                                    @lang('admin::app.catalog.attributes.create.option.' . $type)
                                                </option>
                                            @endforeach
                                        </x-admin::form.control-group.control>

                                        <x-admin::form.control-group.error
                                            class="mt-3"
                                            control-name="admin"
                                        >
                                        </x-admin::form.control-group.error>
                                    </x-admin::form.control-group>

                                    <div class="w-full mb-[10px]">
                                        <!-- checkbox -->
                                        <x-admin::form.control-group.label class="invisible">
                                            @lang('admin::app.catalog.attributes.create.input-options')
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
                                            />

                                            <label
                                                for="empty_option"
                                                class="icon-uncheckbox text-[24px] rounded-[6px] cursor-pointer peer-checked:icon-checked peer-checked:text-blue-600"
                                            >
                                            </label>

                                            <label
                                                for="empty_option"
                                                class="text-[14px] text-gray-600 dark:text-gray-300 font-semibold cursor-pointer"
                                            >
                                                @lang('admin::app.catalog.attributes.create.create-empty-option')
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
                                                    @lang('admin::app.catalog.attributes.create.swatch')
                                                </x-admin::table.th>

                                                <!-- Admin tables heading -->
                                                <x-admin::table.th>
                                                    @lang('admin::app.catalog.attributes.create.admin-name')
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
                                            :list="options"
                                            item-key="id"
                                        >
                                            <template #item="{ element, index }">
                                                <x-admin::table.thead.tr class="hover:bg-gray-50 dark:hover:bg-gray-950">
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
                                                                src="{{ bagisto_asset('images/product-placeholders/front.svg') }}"
                                                                :ref="'image_' + element.params.id"
                                                                class="h-[50px] w-[50px] dark:invert dark:mix-blend-exclusion"
                                                            />

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
                                                                :style="{ background: element.params.swatch_value }"
                                                            >
                                                            </div>
                    
                                                            <input
                                                                type="hidden"
                                                                :name="'options[' + element.id + '][swatch_value]'"
                                                                v-model="element.params.swatch_value"
                                                            />
                                                        </div>
                                                    </x-admin::table.td>

                                                    <!-- Admin-->
                                                    <x-admin::table.td>
                                                        <p
                                                            class="dark:text-white"
                                                            v-text="element.params.admin_name"
                                                        >
                                                        </p>

                                                        <input
                                                            type="hidden"
                                                            :name="'options[' + element.id + '][admin_name]'"
                                                            v-model="element.params.admin_name"
                                                        />
                                                    </x-admin::table.td>

                                                    <x-admin::table.td v-for="locale in allLocales">
                                                        <p
                                                            class="dark:text-white"
                                                            v-text="element.params[locale.code]"
                                                        >
                                                        </p>

                                                        <input
                                                            type="hidden"
                                                            :name="'options[' + element.id + '][' + locale.code + '][label]'"
                                                            v-model="element.params[locale.code]"
                                                        />
                                                    </x-admin::table.td>

                                                    <!-- Actions button -->
                                                    <x-admin::table.td class="!px-0">
                                                        <span
                                                            class="icon-edit p-[6px] rounded-[6px] text-[24px] cursor-pointer transition-all hover:bg-gray-200 dark:hover:bg-gray-800 max-sm:place-self-center"
                                                            @click="editModal(element)"
                                                        >
                                                        </span> 

                                                        <span
                                                            class="icon-delete p-[6px] rounded-[6px] text-[24px] cursor-pointer transition-all hover:bg-gray-200 dark:hover:bg-gray-800 max-sm:place-self-center"
                                                            @click="removeOption(element.id)"
                                                        >
                                                        </span> 
                                                    </x-admin::table.td>
                                                </x-admin::table.thead.tr>
                                            </template>
                                        </draggable>
                                    </x-admin::table>
                                </div>
                            </template>

                            <!-- For Empty Attribute Options -->
                            <template v-else>
                                <div class="grid gap-[14px] justify-items-center py-[40px] px-[10px]">
                                    <!-- Attribute Option Image -->
                                    <img 
                                        class="w-[120px] h-[120px]" 
                                        src="{{ bagisto_asset('images/icon-add-product.svg') }}" 
                                        alt="@lang('admin::app.catalog.attributes.create.add-attribute-options')"
                                    />
                                    
                                    <!-- Add Attribute Options Information -->
                                    <div class="flex flex-col items-center">
                                        <p class="text-[16px] text-gray-400 font-semibold">
                                            @lang('admin::app.catalog.attributes.create.add-attribute-options')
                                        </p>

                                        <p class="text-gray-400">
                                            @lang('admin::app.catalog.attributes.create.add-options-info')
                                        </p>
                                    </div>

                                    <!-- Add Row Button -->
                                    <div 
                                        class="secondary-button text-[14px]"
                                        @click="$refs.addOptionsRow.toggle()"
                                    >
                                        @lang('admin::app.catalog.attributes.create.add-row')
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>

                    {!! view_render_event('bagisto.admin.catalog.attributes.create.card.label.after') !!}

                    {!! view_render_event('bagisto.admin.catalog.attributes.create.card.general.before') !!}

                    <!-- Right sub-component -->
                    <div class="flex flex-col gap-[8px] w-[360px] max-w-full">
                        <!-- General -->
                        <div class="bg-white dark:bg-gray-900 box-shadow rounded-[4px]">
                            <div class="flex justify-between items-center p-[6px]">
                                <p class="p-[10px] text-gray-800 dark:text-white text-[16px] font-semibold">
                                    @lang('admin::app.catalog.attributes.create.general')
                                </p>
                            </div>

                            <div class="px-[16px] pb-[16px]">
                                <!-- Attribute Code -->
                                <x-admin::form.control-group class="mb-[10px]">
                                    <x-admin::form.control-group.label class="required">
                                        @lang('admin::app.catalog.attributes.create.code')
                                    </x-admin::form.control-group.label>

                                    <v-field
                                        type="text"
                                        name="code"
                                        value="{{ old('code') }}"
                                        label="{{ trans('admin::app.catalog.attributes.create.code') }}"
                                        rules="required"
                                        v-slot="{ field }"
                                    >
                                        <input
                                            type="text"
                                            name="slug"
                                            id="code"
                                            v-bind="field"
                                            :class="[errors['{{ 'code' }}'] ? 'border border-red-600 hover:border-red-600' : '']"
                                            class="flex w-full min-h-[39px] py-2 px-3 border rounded-[6px] text-[14px] text-gray-600 dark:text-gray-300 transition-all hover:border-gray-400 dark:hover:border-gray-400 dark:focus:border-gray-400 focus:border-gray-400 dark:bg-gray-900 dark:border-gray-800"
                                            placeholder="{{ trans('admin::app.catalog.attributes.create.code') }}"
                                            v-code
                                        >
                                    </v-field>

                                    <x-admin::form.control-group.error
                                        control-name="code"
                                    >
                                    </x-admin::form.control-group.error>
                                </x-admin::form.control-group>

                                <!-- Attribute Type -->
                                <x-admin::form.control-group class="mb-[10px]">
                                    <x-admin::form.control-group.label class="required">
                                        @lang('admin::app.catalog.attributes.create.type')
                                    </x-admin::form.control-group.label>

                                    <x-admin::form.control-group.control
                                        type="select"
                                        name="type"
                                        rules="required"
                                        :value="old('type')"
                                        id="type"
                                        class="cursor-pointer"
                                        :label="trans('admin::app.catalog.attributes.create.type')"
                                        v-model="attributeType"
                                        @change="swatchAttribute=true"
                                    >
                                        <!-- Here! All Needed types are defined -->
                                        @foreach(['text', 'textarea', 'price', 'boolean', 'select', 'multiselect', 'datetime', 'date', 'image', 'file', 'checkbox'] as $type)
                                            <option
                                                value="{{ $type }}"
                                                {{ $type === 'text' ? "selected" : '' }}
                                            >
                                                @lang('admin::app.catalog.attributes.create.'. $type)
                                            </option>
                                        @endforeach
                                    </x-admin::form.control-group.control>

                                    <x-admin::form.control-group.error
                                        control-name="type"
                                    >
                                    </x-admin::form.control-group.error>
                                </x-admin::form.control-group>

                                <!-- Textarea Switcher -->
                                <x-admin::form.control-group v-show="swatchAttribute && (attributeType == 'textarea')">
                                    <x-admin::form.control-group.label>
                                        @lang('admin::app.catalog.attributes.create.enable-wysiwyg')
                                    </x-admin::form.control-group.label>

                                    <x-admin::form.control-group.control
                                        type="switch"
                                        name="enable_wysiwyg"
                                        value="1"
                                        :label="trans('admin::app.catalog.attributes.create.enable-wysiwyg')"
                                    >
                                    </x-admin::form.control-group.control>
                                </x-admin::form.control-group>

                                <!-- Default Value -->
                                <x-admin::form.control-group>
                                    <x-admin::form.control-group.label>
                                        @lang('admin::app.catalog.attributes.create.default-value')
                                    </x-admin::form.control-group.label>

                                    <x-admin::form.control-group.control
                                        type="text"
                                        name="default_value"
                                        :label="trans('admin::app.catalog.attributes.create.default-value')"
                                    >
                                    </x-admin::form.control-group.control>
                                </x-admin::form.control-group>
                            </div>
                        </div>

                        <!-- Validations -->
                        <x-admin::accordion>
                            <x-slot:header>
                                <p class="p-[10px] text-gray-800 dark:text-white text-[16px] font-semibold">
                                    @lang('admin::app.catalog.attributes.create.validations')
                                </p>
                            </x-slot:header>
                        
                            <x-slot:content>
                                <!-- Input Validation -->
                                <x-admin::form.control-group
                                    v-if="swatchAttribute && (attributeType == 'text')"
                                    class="mb-[10px]"
                                >
                                    <x-admin::form.control-group.label>
                                        @lang('admin::app.catalog.attributes.create.input-validation')
                                    </x-admin::form.control-group.label>

                                    <x-admin::form.control-group.control
                                        type="select"
                                        name="validation"
                                        :value="old('validation')"
                                        id="validation"
                                        class="cursor-pointer"
                                        refs="validation"
                                        :label="trans('admin::app.catalog.attributes.create.input-validation')"
                                        v-model="validationType"
                                        @change="inputValidation=true"
                                    >
                                        <!-- Here! All Needed types are defined -->
                                        @foreach(['number', 'email', 'decimal', 'url', 'regex'] as $type)
                                            <option value="{{ $type }}">
                                                @lang('admin::app.catalog.attributes.create.' . $type)
                                            </option>
                                        @endforeach
                                    </x-admin::form.control-group.control>

                                    <x-admin::form.control-group.error
                                        control-name="validation"
                                    >
                                    </x-admin::form.control-group.error>
                                </x-admin::form.control-group>

                                <!-- REGEX -->
                                <x-admin::form.control-group
                                    class="mb-[10px]"
                                    v-show="inputValidation && (validationType == 'regex')"
                                >
                                    <x-admin::form.control-group.label>
                                        @lang('admin::app.catalog.attributes.create.regex')
                                    </x-admin::form.control-group.label>

                                    <x-admin::form.control-group.control
                                        type="text"
                                        name="regex"
                                        :value="old('regex')"
                                        :placeholder="trans('admin::app.catalog.attributes.create.regex')"
                                    >
                                    </x-admin::form.control-group.control>

                                    <x-admin::form.control-group.error
                                        control-name="regex"
                                    >
                                    </x-admin::form.control-group.error>
                                </x-admin::form.control-group>

                                <!-- Is Required -->
                                 <x-admin::form.control-group class="flex gap-[10px] w-max !mb-0 p-[6px] cursor-pointer select-none">
                                    <x-admin::form.control-group.control
                                        type="checkbox"
                                        name="is_required"
                                        id="is_required"
                                        for="is_required"
                                        value="1"
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
                                <x-admin::form.control-group class="flex gap-[10px] w-max !mb-0 p-[6px] cursor-pointer select-none">
                                    <x-admin::form.control-group.control
                                        type="checkbox"
                                        name="is_unique"
                                        id="is_unique"
                                        for="is_unique"
                                        value="1"
                                    >
                                    </x-admin::form.control-group.control>

                                    <x-admin::form.control-group.label
                                        for="is_unique"
                                        class="!text-[14px] !font-semibold !text-gray-600 dark:!text-gray-300 cursor-pointer"
                                    >
                                        @lang('admin::app.catalog.attributes.edit.is-unique')
                                    </x-admin::form.control-group.label>
                                </x-admin::form.control-group>
                            </x-slot:content>
                        </x-admin::accordion>

                        <!-- Configurations -->
                        <x-admin::accordion>
                            <x-slot:header>
                                <p class="p-[10px] text-gray-800 dark:text-white text-[16px] font-semibold">
                                    @lang('admin::app.catalog.attributes.create.configuration')
                                </p>
                            </x-slot:header>
                        
                            <x-slot:content>
                                <!-- Value Per Locale -->
                                <x-admin::form.control-group class="flex gap-[10px] w-max !mb-0 p-[6px] cursor-pointer select-none">
                                    <x-admin::form.control-group.control
                                        type="checkbox"
                                        name="value_per_locale"
                                        id="value_per_locale"
                                        for="value_per_locale"
                                        value="1"
                                    >
                                    </x-admin::form.control-group.control>

                                    <x-admin::form.control-group.label
                                        for="value_per_locale"
                                        class="!text-[14px] !font-semibold !text-gray-600 dark:!text-gray-300 cursor-pointer"
                                    >
                                        @lang('admin::app.catalog.attributes.edit.value-per-locale')
                                    </x-admin::form.control-group.label>
                                </x-admin::form.control-group>

                                <!-- Value Per Channel -->
                                <x-admin::form.control-group class="flex gap-[10px] w-max !mb-0 p-[6px] cursor-pointer select-none">
                                    <x-admin::form.control-group.control
                                        type="checkbox"
                                        name="value_per_channel"
                                        id="value_per_channel"
                                        for="value_per_channel"
                                        value="1"
                                    >
                                    </x-admin::form.control-group.control>

                                    <x-admin::form.control-group.label
                                        for="value_per_channel"
                                        class="!text-[14px] !font-semibold !text-gray-600 dark:!text-gray-300 cursor-pointer"
                                    >
                                        @lang('admin::app.catalog.attributes.edit.value-per-channel')
                                    </x-admin::form.control-group.label>
                                </x-admin::form.control-group>

                                <!-- Use to create configuable product -->
                                <x-admin::form.control-group class="flex gap-[10px] w-max !mb-0 p-[6px] cursor-pointer select-none">
                                    <x-admin::form.control-group.control
                                        type="checkbox"
                                        name="is_configurable"
                                        id="is_configurable"
                                        for="is_configurable"
                                        value="1"
                                    >
                                    </x-admin::form.control-group.control>

                                    <x-admin::form.control-group.label
                                        for="is_configurable"
                                        class="!text-[14px] !font-semibold !text-gray-600 dark:!text-gray-300 cursor-pointer"
                                    >
                                        @lang('admin::app.catalog.attributes.edit.is-configurable')
                                    </x-admin::form.control-group.label>
                                </x-admin::form.control-group>

                                <!-- Visible On Product View Page On Fornt End -->
                                <x-admin::form.control-group class="flex gap-[10px] w-max !mb-0 p-[6px] cursor-pointer select-none">
                                    <x-admin::form.control-group.control
                                        type="checkbox"
                                        name="is_visible_on_front"
                                        id="is_visible_on_front"
                                        for="is_visible_on_front"
                                        value="1"
                                    >
                                    </x-admin::form.control-group.control>

                                    <x-admin::form.control-group.label
                                        for="is_visible_on_front"
                                        class="!text-[14px] !font-semibold !text-gray-600 dark:!text-gray-300 cursor-pointer"
                                    >
                                        @lang('admin::app.catalog.attributes.edit.is-visible-on-front')
                                    </x-admin::form.control-group.label>
                                </x-admin::form.control-group>

                                <!-- Attribute is Comparable -->
                                <x-admin::form.control-group class="flex gap-[10px] w-max !mb-0 p-[6px] cursor-pointer select-none">
                                    <x-admin::form.control-group.control
                                        type="checkbox"
                                        name="is_comparable"
                                        id="is_comparable"
                                        for="is_comparable"
                                        value="1"
                                    >
                                    </x-admin::form.control-group.control>

                                    <x-admin::form.control-group.label
                                        for="is_comparable"
                                        class="!text-[14px] !font-semibold !text-gray-600 dark:!text-gray-300 cursor-pointer"
                                    >
                                        @lang('admin::app.catalog.attributes.edit.is-comparable')
                                    </x-admin::form.control-group.label>
                                </x-admin::form.control-group>

                                <!-- Use in Layered -->
                                <label
                                    for="is_filterable" 
                                    class="flex gap-[10px] items-center w-max p-[6px] cursor-pointer select-none"
                                >
                                    <input
                                        type="checkbox"
                                        class="hidden peer"
                                        id="is_filterable"
                                        name="is_filterable"
                                        value="1"
                                        :disabled="attributeType == 'price' ||  attributeType == 'checkbox'
                                            || attributeType == 'select' || attributeType == 'multiselect' 
                                            ? false : true
                                        "
                                    />
            
                                    <span class="icon-uncheckbox rounded-[6px] text-[24px] cursor-pointer peer-checked:icon-checked peer-checked:text-blue-600"></span>

                                    <div class="text-[14px] text-gray-600 dark:text-gray-300 font-semibold cursor-pointer">
                                        @lang('admin::app.catalog.attributes.create.is-filterable')
                                    </div>
                                </label>
                            </x-slot:content>
                        </x-admin::accordion>
                    </div>

                    {!! view_render_event('bagisto.admin.catalog.attributes.create.card.general.after') !!}

                </div>

                {!! view_render_event('bagisto.admin.catalog.attributes.create_form_controls.after') !!}
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
                    ref="createOptionsForm"
                >
                    <x-admin::modal
                        @toggle="listenModal"
                        ref="addOptionsRow"
                    >
                        <x-slot:header>
                            <p class="text-[18px] text-gray-800 dark:text-white font-bold">
                                @lang('admin::app.catalog.attributes.create.add-option')
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
                                        @lang('admin::app.catalog.attributes.create.image')
                                    </x-admin::form.control-group.label>

                                    <x-admin::form.control-group.control
                                        type="image"
                                        name="swatch_value"
                                        :placeholder="trans('admin::app.catalog.attributes.create.image')"
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
                                        @lang('admin::app.catalog.attributes.create.color')
                                    </x-admin::form.control-group.label>

                                    <x-admin::form.control-group.control
                                        type="color"
                                        name="swatch_value"
                                        :placeholder="trans('admin::app.catalog.attributes.create.color')"
                                    >
                                    </x-admin::form.control-group.control>

                                    <x-admin::form.control-group.error
                                        control-name="swatch_value"
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
                                
                                <!-- Admin Input -->
                                <x-admin::form.control-group class="w-full mb-[10px]">
                                    <x-admin::form.control-group.label ::class="{ 'required' : ! isNullOptionChecked }">
                                        @lang('admin::app.catalog.attributes.create.admin')
                                    </x-admin::form.control-group.label>

                                    <x-admin::form.control-group.control
                                        type="text"
                                        name="admin_name"
                                        ::rules="{ 'required' : ! isNullOptionChecked }"
                                        :label="trans('admin::app.catalog.attributes.create.admin')"
                                        :placeholder="trans('admin::app.catalog.attributes.create.admin')"
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
                                        <x-admin::form.control-group.label ::class="{ '{{core()->getDefaultChannelLocaleCode() == $locale->code ? 'required' : ''}}' : ! isNullOptionChecked }">
                                            {{ $locale->name }} ({{ strtoupper($locale->code) }})
                                        </x-admin::form.control-group.label>

                                        <x-admin::form.control-group.control
                                            type="text"
                                            :name="$locale->code"
                                            ::rules="{ '{{core()->getDefaultChannelLocaleCode() == $locale->code ? 'required' : ''}}' : ! isNullOptionChecked }"
                                            :label="$locale->name"
                                            :placeholder="$locale->name"
                                        >
                                        </x-admin::form.control-group.control>
            
                                        <x-admin::form.control-group.error
                                            :control-name="$locale->code"
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
                                @lang('admin::app.catalog.attributes.create.option.save-btn')
                            </button>
                        </x-slot:footer>
                    </x-admin::modal>
                </form>
            </x-admin::form>

            {!! view_render_event('bagisto.admin.catalog.attributes.create.after') !!}

        </script>

        <script type="module">
            app.component('v-create-attributes', {
                template: '#v-create-attributes-template',

                props: ['allLocales'],

                data() {
                    return {
                        optionRowCount: 1,

                        attributeType: '',

                        validationType: '',

                        inputValidation: false,

                        swatchType: '',

                        swatchAttribute: false,

                        showSwatch: false,

                        isNullOptionChecked: false,

                        options: [],
                    }
                },

                methods: {
                    storeOptions(params, { resetForm }) {
                        if (params.id) {
                            let foundIndex = this.options.findIndex(item => item.id === params.id);

                            this.options.splice(foundIndex, 1, {
                                ...this.options[foundIndex],
                                params: {   
                                    ...this.options[foundIndex].params,
                                    ...params,
                                }
                            });
                        } else {
                            this.options.push({
                                id: 'option_' + this.optionRowCount++,
                                params
                            });
                        }

                        let formData = new FormData(this.$refs.createOptionsForm);

                        const sliderImage = formData.get("swatch_value[]");

                        params.swatch_value = sliderImage;

                        this.$refs.addOptionsRow.toggle();

                        if (params.swatch_value instanceof File) {
                            this.setFile(params);
                        }

                        resetForm();
                    },

                    editModal(values) {
                        values.params.id = values.id;

                        this.$refs.modelForm.setValues(values.params);

                        this.$refs.addOptionsRow.toggle();
                    },

                    removeOption(id) {
                        this.options = this.options.filter(option => option.id !== id);
                    },

                    listenModal(event) {
                        if (! event.isActive) {
                            this.isNullOptionChecked = false;
                        }
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