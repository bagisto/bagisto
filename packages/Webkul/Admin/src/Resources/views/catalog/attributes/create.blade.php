<x-admin::layouts>
    {{-- Title of the page --}}
    <x-slot:title>
        @lang('admin::app.catalogs.attributes.create.title')
    </x-slot:title>

    {{-- Input Form --}}
    <x-admin::form
        :action="route('admin.catalog.attributes.store')"
        enctype="multipart/form-data"
    >
        <div class="flex justify-between items-center">
            <p class="text-[20px] text-gray-800 font-bold">
                @lang('admin::app.catalogs.attributes.create.title')
            </p>

            <div class="flex gap-x-[10px] items-center">
                <a href="{{ route('admin.catalog.attributes.index') }}">
                    <span class="text-gray-600 leading-[24px]">
                        @lang('admin::app.catalogs.attributes.create.cancel')
                    </span>
                </a>

                <button
                    type="submit"
                    class="px-[12px] py-[6px] bg-blue-600 border border-blue-700 rounded-[6px] text-gray-50 font-semibold cursor-pointer"
                >
                    @lang('admin::app.catalogs.attributes.create.save')
                </button>
            </div>
        </div>

        {{-- body content --}}
        <div class="flex gap-[10px] mt-[14px]">
            <div class="flex flex-col gap-[8px] flex-1">
                <!-- Label -->
                <div class="p-[16px] bg-white box-shadow rounded-[4px]">
                    <p class="mb-[16px] text-[16px] text-gray-800 font-semibold">
                        @lang('admin::app.catalogs.attributes.create.attribute-label')
                    </p>

                    {{-- Admin name --}}
                    <x-admin::form.control-group class="mb-[10px]">
                        <x-admin::form.control-group.label>
                            @lang('admin::app.catalogs.attributes.create.admin')
                        </x-admin::form.control-group.label>

                        <x-admin::form.control-group.control
                            type="text"
                            name="admin_name"
                            value="{{ old('admin_name') }}"
                            rules="required"
                            label="{{ trans('admin::app.catalogs.attributes.create.admin') }}"
                            placeholder="{{ trans('admin::app.catalogs.attributes.create.admin') }}"
                        >
                        </x-admin::form.control-group.control>

                        <x-admin::form.control-group.error
                            control-name="admin_name"
                        >
                        </x-admin::form.control-group.error>
                    </x-admin::form.control-group>

                    <!-- Locales Inputs -->
                    @foreach (app('Webkul\Core\Repositories\LocaleRepository')->all() as $locale)
                        <x-admin::form.control-group class="mb-[10px]">
                            <x-admin::form.control-group.label>
                                {{ $locale->name . ' (' . strtoupper($locale->code) . ')' }}
                            </x-admin::form.control-group.label>

                            <x-admin::form.control-group.control
                                type="text"
                                name="{{ $locale->code }}[name]"
                                value="{{ old($locale->code)['name'] ?? '' }}"
                                placeholder="{{ $locale->name }}"
                            >
                            </x-admin::form.control-group.control>

                            <x-admin::form.control-group.error
                                control-name="{{ $locale->code }}[name]"
                            >
                            </x-admin::form.control-group.error>
                        </x-admin::form.control-group>    
                    @endforeach
                </div>

                {{-- Options --}}
                <div 
                    class="w-[690px]" 
                    v-if="swatch_attribute && (attribute_type == 'select' || attribute_type == 'multiselect' || attribute_type == 'price' || attribute_type == 'checkbox')"
                >
                    <div class="p-[16px] bg-white box-shadow rounded-[4px]">
                        <!-- Vue Components -->
                        <v-option-wrapper></v-option-wrapper>
                    </div>
                </div>
            </div>

            {{-- Right sub-component --}}
            <div class="flex flex-col gap-[8px] w-[360px] max-w-full">
                {{-- General --}}
                <div class="bg-white box-shadow rounded-[4px]">
                    <div class="flex justify-between items-center p-[6px]">
                        <p class="p-[10px] text-gray-600 text-[16px] font-semibold">
                            @lang('admin::app.catalogs.attributes.create.general')
                        </p>
                    </div>

                    <div class="px-[16px] pb-[16px]">
                        {{-- Attribute Code --}}
                        <x-admin::form.control-group class="mb-[10px]">
                            <x-admin::form.control-group.label>
                                @lang('admin::app.catalogs.attributes.create.general')
                            </x-admin::form.control-group.label>

                            <x-admin::form.control-group.control
                                type="text"
                                name="code"
                                value="{{ old('code') }}"
                                class="!w-[284px]"
                                rules="required"
                                label="{{ trans('admin::app.catalogs.attributes.create.code') }}"
                                placeholder="{{ trans('admin::app.catalogs.attributes.create.code') }}"
                            >
                            </x-admin::form.control-group.control>

                            <x-admin::form.control-group.error
                                control-name="code"
                            >
                            </x-admin::form.control-group.error>
                        </x-admin::form.control-group>

                        {{-- Attribute Type --}}
                        <x-admin::form.control-group class="mb-[10px]">
                            <x-admin::form.control-group.label>
                                @lang('admin::app.catalogs.attributes.create.type')
                            </x-admin::form.control-group.label>

                            <x-admin::form.control-group.control
                                type="select"
                                name="type"
                                rules="required"
                                value="{{ old('type') }}"
                                id="type"
                                class="!w-[284px] cursor-pointer"
                                label="{{ trans('admin::app.catalogs.attributes.create.type') }}"
                                v-model="attribute_type"
                                @change="swatch_attribute=true"
                            >
                                {{-- Here! All Needed types are defined --}}
                                @foreach(['text', 'textarea', 'price', 'boolean', 'select', 'multiselect', 'datetime', 'date', 'image', 'file', 'checkbox'] as $type)
                                    <option value="{{ $type }}">
                                        @lang('admin::app.catalogs.attributes.create.'. $type)
                                    </option>
                                @endforeach
                            </x-admin::form.control-group.control>

                            <x-admin::form.control-group.error
                                control-name="type"
                            >
                            </x-admin::form.control-group.error>
                        </x-admin::form.control-group>

                        {{-- Textarea Switcher --}}
                        <x-admin::form.control-group v-if="swatch_attribute && (attribute_type == 'textarea')">
                            <x-admin::form.control-group.label>
                                @lang('admin::app.catalogs.attributes.create.enable-wysiwyg')
                            </x-admin::form.control-group.label>

                            <x-admin::form.control-group.control
                                type="switch"
                                name="enable_wysiwyg"
                                id="enable_wysiwyg"
                                class="cursor-pointer"
                                value="1"
                                label="{{ trans('admin::app.catalogs.attributes.create.enable-wysiwyg') }}"
                            >
                            </x-admin::form.control-group.control>
                        </x-admin::form.control-group>
                    </div>
                </div>

                {{-- Validations --}}
                <x-admin::accordion>
                    <x-slot:header>
                        <p class="p-[10px] text-gray-600 text-[16px] font-semibold">
                            @lang('admin::app.catalogs.attributes.create.validations')
                        </p>
                    </x-slot:header>
                
                    <x-slot:content>
                        {{-- Is Required --}}
                        <label
                            for="is_required"
                            class="flex gap-[10px] items-center w-max p-[6px] cursor-pointer select-none"
                        >
                            <input
                                type="checkbox"
                                id="is_required"
                                name="is_required"
                                class="hidden peer"
                                required
                                value="1"
                            >
    
                            <span class="icon-uncheckbox rounded-[6px] text-[24px] cursor-pointer peer-checked:icon-checked peer-checked:text-navyBlue"></span>
    
                            <div class="text-[14px] text-gray-600 cursor-pointer">
                                @lang('admin::app.catalogs.attributes.create.is_required')
                            </div>
                        </label>
    
                        {{-- Is Unique --}}
                        <label
                            for="is_unique" 
                            class="flex gap-[10px] items-center w-max p-[6px] cursor-pointer select-none"
                        >
                            <input 
                                type="checkbox" 
                                id="is_unique" 
                                name="is_unique" 
                                class="hidden peer"
                                required
                                value="1"
                            >

                            <span class="icon-uncheckbox rounded-[6px] text-[24px] cursor-pointer peer-checked:icon-checked peer-checked:text-navyBlue"></span>

                            <div class="text-[14px] text-gray-600 cursor-pointer">
                                @lang('admin::app.catalogs.attributes.create.is_unique')
                            </div>
                        </label>
                    </x-slot:content>
                </x-admin::accordion>

                {{-- Configurations --}}
                <x-admin::accordion>
                    <x-slot:header>
                        <p class="p-[10px] text-gray-600 text-[16px] font-semibold">
                            @lang('admin::app.catalogs.attributes.create.configuration')
                        </p>
                    </x-slot:header>
                
                    <x-slot:content>
                        {{-- value_per_locale --}}
                        <label
                            for="value_per_locale"
                            class="flex gap-[10px] items-center w-max p-[6px] cursor-pointer select-none"
                        >
                            <input
                                type="checkbox"
                                class="hidden peer"
                                id="value_per_locale"
                                name="value_per_locale"
                                value="1"
                                required
                            >

                            <span class="icon-uncheckbox rounded-[6px] text-[24px] cursor-pointer peer-checked:icon-checked peer-checked:text-navyBlue"></span>

                            <div class="text-[14px] text-gray-600 cursor-pointer">
                                @lang('admin::app.catalogs.attributes.create.value_per_locale')
                            </div>
                        </label>

                        {{-- value_per_channel --}}
                        <label
                            for="value_per_channel"
                            class="flex gap-[10px] items-center w-max p-[6px] cursor-pointer select-none"
                        >
                            <input
                                type="checkbox"
                                class="hidden peer"
                                id="value_per_channel"
                                name="value_per_channel"
                                value="1"
                                required
                            >

                            <span class="icon-uncheckbox rounded-[6px] text-[24px] cursor-pointer peer-checked:icon-checked peer-checked:text-navyBlue"></span>

                            <div class="text-[14px] text-gray-600 cursor-pointer">
                                @lang('admin::app.catalogs.attributes.create.value_per_channel')
                            </div>
                        </label>

                        {{-- Use in Layered --}}
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
                                :disabled="attribute_type === 'price' ||  attribute_type === 'checkbox' || attribute_type === 'select' || attribute_type === 'multiselect' ? disabled : '' "
                            >
    
                            <span class="icon-uncheckbox rounded-[6px] text-[24px] cursor-pointer peer-checked:icon-checked peer-checked:text-navyBlue"></span>
    
                            <div class="text-[14px] text-gray-600 cursor-pointer">
                                @lang('admin::app.catalogs.attributes.create.is_filterable')
                            </div>
                        </label>

                        {{-- is_configurable --}}
                        <label
                            for="is_configurable"
                            class="flex gap-[10px] items-center w-max p-[6px] cursor-pointer select-none"
                        >
                            <input
                                type="checkbox"
                                class="hidden peer"
                                id="is_configurable"
                                name="is_configurable"
                                value="1"
                                required
                            >
    
                            <span class="icon-uncheckbox rounded-[6px] text-[24px] cursor-pointer peer-checked:icon-checked peer-checked:text-navyBlue"></span>
    
                            <div class="text-[14px] text-gray-600 cursor-pointer">
                                @lang('admin::app.catalogs.attributes.create.is_configurable')
                            </div>
                        </label>

                        {{-- is_visible_on_front --}}
                        <label
                            for="is_visible_on_front" 
                            class="flex gap-[10px] items-center w-max p-[6px] cursor-pointer select-none"
                        >
                            <input
                                type="checkbox"
                                class="hidden peer"
                                id="is_visible_on_front"
                                name="is_visible_on_front"
                                value="1"
                                required
                            >
    
                            <span class="icon-uncheckbox rounded-[6px] text-[24px] cursor-pointer peer-checked:icon-checked peer-checked:text-navyBlue"></span>
    
                            <div class="text-[14px] text-gray-600 cursor-pointer">
                                @lang('admin::app.catalogs.attributes.create.is_visible_on_front')
                            </div>
                        </label>

                        {{-- use_in_flat --}}
                        <label
                            for="use_in_flat" 
                            class="flex gap-[10px] items-center w-max p-[6px] cursor-pointer select-none"
                        >
                            <input
                                type="checkbox"
                                class="hidden peer"
                                id="use_in_flat"
                                name="use_in_flat"
                                value="1"
                                required
                            >
    
                            <span class="icon-uncheckbox rounded-[6px] text-[24px] cursor-pointer peer-checked:icon-checked peer-checked:text-navyBlue"></span>
    
                            <div class="text-[14px] text-gray-600 cursor-pointer">
                                @lang('admin::app.catalogs.attributes.create.use_in_flat')
                            </div>
                        </label>

                        {{-- is_comparable --}}
                        <label
                            for="is_comparable"
                            class="flex gap-[10px] items-center w-max p-[6px] cursor-pointer select-none"
                        >
                            <input
                                type="checkbox"
                                class="hidden peer"
                                id="is_comparable"
                                name="is_comparable"
                                value="1"
                                required
                            >
    
                            <span class="icon-uncheckbox rounded-[6px] text-[24px] cursor-pointer peer-checked:icon-checked peer-checked:text-navyBlue"></span>
    
                            <div class="text-[14px] text-gray-600 cursor-pointer">
                                @lang('admin::app.catalogs.attributes.create.is_comparable')
                            </div>
                        </label>
                    </x-slot:content>
                </x-admin::accordion>
            </div>
        </div>
    {{-- </form> --}}
    </x-admin::form>
    @pushOnce('scripts')
        <script type="text/x-template" id="v-option-wrapper-template">
            <div>              
                <div class="flex justify-between items-center mb-3">
                    <p class="mb-[16px] text-[16px] text-gray-800 font-semibold">
                        @lang('admin::app.catalogs.attributes.create.title')
                    </p>

                    <x-admin::form
                        v-slot="{ meta, errors, handleSubmit }"
                        as="div"
                        ref="modelForm"
                    >
                        <form @submit.prevent="handleSubmit($event, storeOptions)" enctype="multipart/form-data">
                            <x-admin::modal ref="addOptionsRow">
                                <x-slot:toggle>
                                    <div class="max-w-max px-[12px] py-[5px] bg-white border-[2px] border-blue-600 rounded-[6px] text-[14px] text-blue-600 font-semibold whitespace-nowrap cursor-pointer">
                                        @lang('admin::app.catalogs.attributes.create.add-row')
                                    </div>
                                </x-slot:toggle>

                                <x-slot:header>
                                    <p class="text-[18px] text-gray-800 font-bold">
                                        @lang('admin::app.catalogs.attributes.create.add-option')
                                    </p>
                                </x-slot:header>

                                <x-slot:content>
                                    <div class="grid grid-cols-3 px-[16px] py-[10px]">
                                        <!-- Image Input -->
                                        <x-admin::form.control-group class="w-full" v-if="swatch_type == 'image'">
                                            <x-admin::form.control-group.label>
                                                @lang('admin::app.catalogs.attributes.create.image')
                                            </x-admin::form.control-group.label>

                                            <x-admin::form.control-group.control
                                                type="image"
                                                name="swatch_value"
                                                placeholder="{{ trans('admin::app.catalogs.attributes.create.image') }}"
                                            >
                                            </x-admin::form.control-group.control>

                                            <x-admin::form.control-group.error
                                                control-name="swatch_value"
                                            >
                                            </x-admin::form.control-group.error>
                                        </x-admin::form.control-group>

                                        <!-- Color Input -->
                                        <x-admin::form.control-group class="w-full" v-if="swatch_type == 'color'">
                                            <x-admin::form.control-group.label>
                                                @lang('admin::app.catalogs.attributes.create.color')
                                            </x-admin::form.control-group.label>

                                            <x-admin::form.control-group.control
                                                type="color"
                                                name="swatch_value"
                                                placeholder="{{ trans('admin::app.catalogs.attributes.create.color') }}"
                                            >
                                            </x-admin::form.control-group.control>

                                            <x-admin::form.control-group.error
                                                control-name="swatch_value"
                                            >
                                            </x-admin::form.control-group.error>
                                        </x-admin::form.control-group>
                                    </div>

                                    <div class="grid grid-cols-3 gap-[16px] px-[16px] py-[10px] border-b-[1px] border-gray-300">
                                        <!-- Hidden Id Input -->
                                        <x-admin::form.control-group.control
                                            type="hidden"
                                            name="id"
                                        >
                                        </x-admin::form.control-group.control>
                                        
                                        <!-- Admin Input -->
                                        <x-admin::form.control-group class="w-full mb-[10px]">
                                            <x-admin::form.control-group.label>
                                                @lang('admin::app.catalogs.attributes.create.admin')
                                            </x-admin::form.control-group.label>

                                            <x-admin::form.control-group.control
                                                type="text"
                                                name="admin"
                                                rules="required"
                                                label="{{ trans('admin::app.catalogs.attributes.create.admin') }}"
                                                placeholder="{{ trans('admin::app.catalogs.attributes.create.admin') }}"
                                            >
                                            </x-admin::form.control-group.control>
                
                                            <x-admin::form.control-group.error
                                                control-name="admin"
                                            >
                                            </x-admin::form.control-group.error>
                                        </x-admin::form.control-group>

                                        <!-- Locales Input -->
                                        @foreach (app('Webkul\Core\Repositories\LocaleRepository')->all() as $locale)
                                            <x-admin::form.control-group class="w-full mb-[10px]">
                                                <x-admin::form.control-group.label>
                                                    {{ $locale->name }} ({{ strtoupper($locale->code) }})
                                                </x-admin::form.control-group.label>

                                                <x-admin::form.control-group.control
                                                    type="text"
                                                    name="{{ $locale->code }}"
                                                    rules="{{ core()->getDefaultChannelLocaleCode() == $locale->code ? 'required' : '' }}"
                                                    label="{{ $locale->name }}"
                                                    placeholder="{{ $locale->name }}"
                                                >
                                                </x-admin::form.control-group.control>
                    
                                                <x-admin::form.control-group.error
                                                    control-name="{{ $locale->code }}"
                                                >
                                                </x-admin::form.control-group.error>
                                            </x-admin::form.control-group>
                                        @endforeach

                                        <!-- Position Input -->
                                        <x-admin::form.control-group class="w-full mb-[10px]">
                                            <x-admin::form.control-group.label>
                                                @lang('admin::app.catalogs.attributes.create.position')
                                            </x-admin::form.control-group.label>

                                            <x-admin::form.control-group.control
                                                type="number"
                                                name="sort_order"
                                                rules="required"
                                                label="{{ trans('admin::app.catalogs.attributes.create.position') }}"
                                                placeholder="{{ trans('admin::app.catalogs.attributes.create.position') }}"
                                            >
                                            </x-admin::form.control-group.control>
                
                                            <x-admin::form.control-group.error
                                                control-name="sort_order"
                                            >
                                            </x-admin::form.control-group.error>
                                        </x-admin::form.control-group>
                                    </div>
                                </x-slot:content>
                                
                                <x-slot:footer>
                                    <!-- Save Button -->
                                    <button
                                        type="submit" 
                                        class="px-[12px] py-[6px] bg-blue-600 border border-blue-700 rounded-[6px] text-gray-50 font-semibold cursor-pointer"
                                    >
                                        @lang('admin::app.marketing.email-marketing.events.create.save')
                                    </button>
                                </x-slot:footer>
                            </x-admin::modal>
                        </form>
                    </x-admin::form>
                </div>

                <x-admin::form :action="route('admin.catalog.attributes.store')">
                    <div class="flex gap-[16px] max-sm:flex-wrap">
                        <x-admin::form.control-group class="w-full mb-[10px]">
                            <x-admin::form.control-group.label>
                                @lang('admin::app.catalogs.attributes.create.input-options')
                            </x-admin::form.control-group.label>

                            <x-admin::form.control-group.control
                                type="select"
                                name="swatch_type"
                                id="swatch_type"
                                value="{{ old('swatch_type') }}"
                                v-model="swatch_type"
                                @change="show_swatch=true"
                            >
                                <option value="dropdown">
                                    @lang('admin::app.catalogs.attributes.create.dropdown')
                                </option>
            
                                <option value="color">
                                    @lang('admin::app.catalogs.attributes.create.color-swatch')
                                </option>
            
                                <option value="image">
                                    @lang('admin::app.catalogs.attributes.create.image-swatch')
                                </option>
            
                                <option value="text">
                                    @lang('admin::app.catalogs.attributes.create.text-swatch')
                                </option>
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
                                @lang('admin::app.catalogs.attributes.create.input-options')
                            </x-admin::form.control-group.label>

                            <label
                                for="empty_option"
                                class="flex gap-[10px] items-center w-max p-[6px] cursor-pointer select-none"
                            >
                                <input
                                    type="checkbox"
                                    name="empty_option"
                                    id="empty_option"
                                    class="hidden peer"
                                    value="1"
                                    v-model="isNullOptionChecked"
                                >
        
                                <span class="icon-uncheckbox rounded-[6px] text-[24px] cursor-pointer peer-checked:icon-checked peer-checked:text-navyBlue"></span>
        
                                <div class="text-[14px] text-gray-600 cursor-pointer">
                                    @lang('admin::app.catalogs.attributes.create.create-empty-option')
                                </div>
                            </label>
                        </div>
                    </div>
                </x-admin::form>

                <!-- Table Information -->
                <div class="mt-[15px] overflow-auto">    
                    <x-admin::table class="w-full text-left">
                        <x-admin::table.thead class="text-[14px] font-medium">
                            <x-admin::table.tr>
                                <x-admin::table.th class="!p-0"></x-admin::table.th>

                                <!-- Swatch Select -->
                                <x-admin::table.th v-if="show_swatch && (swatch_type == 'color' || swatch_type == 'image')">
                                    @lang('admin::app.catalogs.attributes.create.swatch')
                                </x-admin::table.th>

                                <!-- Admin tables heading -->
                                <x-admin::table.th>
                                    @lang('admin::app.catalogs.attributes.create.admin_name')
                                </x-admin::table.th>

                                <!-- Loacles tables heading -->
                                @foreach (app('Webkul\Core\Repositories\LocaleRepository')->all() as $locale)
                                    <x-admin::table.th>
                                        {{ $locale->name . ' (' . $locale->code . ')' }}
                                    </x-admin::table.th>
                                @endforeach                                

                                <!-- Positions tables heading -->
                                <x-admin::table.th>
                                    @lang('admin::app.catalogs.attributes.create.position')
                                </x-admin::table.th>

                                <!-- Action tables heading -->
                                <x-admin::table.th></x-admin::table.th>
                            </x-admin::table.tr>
                        </x-admin::table.thead>

                        <draggable
                            tag="tbody"
                            ghost-class="draggable-ghost"
                            :list="options"
                        >
                            <template #item="{ element, index }">
                                <x-admin::table.tr>
                                    <x-admin::table.td class="!px-0">
                                        <i class="icon-drag text-[20px] transition-all group-hover:text-gray-700"></i>
                                        <input type="hidden" :name="'options[' + element.id + '][position]'" :value="index"/>
                                    </x-admin::table.td>

                                    <!-- Swatch Type Image / Color -->
                                    <x-admin::table.td>
                                        <!-- Swatch Image -->
                                        <div v-if="swatch_type == 'image'">
                                            @{{ element.params.swatch_value?.name }}

                                            <input
                                                type="hidden"
                                                :name="'options[' + element.id + '][swatch_value]'"
                                                v-model="element.params.swatch_value"
                                            />    
                                        </div>

                                        <!-- Swatch Color -->
                                        <div v-if="swatch_type == 'color'">
                                            <div 
                                                class="w-[25px] h-[25px] mx-auto rounded-[5px]" 
                                                :style="{ background: element.params.swatch_value }"
                                            ></div>
    
                                            <input
                                                type="hidden"
                                                :name="'options[' + element.id + '][swatch_value]'"
                                                v-model="element.params.swatch_value"
                                            />
                                        </div>
                                    </x-admin::table.td>

                                    <!-- Admin-->
                                    <x-admin::table.td>
                                        <p v-text="element.params.admin"></p>

                                        <input
                                            type="hidden"
                                            :name="'options[' + element.id + '][admin]'"
                                            v-model="element.params.admin"
                                        />
                                    </x-admin::table.td>
    
                                    <!-- English Loacle -->
                                    <x-admin::table.td>
                                        <p v-text="element.params.en"></p>

                                        <input
                                            type="hidden"
                                            :name="'options[' + element.id + '][en]'"
                                            v-model="element.params.en"
                                        />
                                    </x-admin::table.td>

                                    <!-- French Loacle -->
                                    <x-admin::table.td>
                                        <p v-text="element.params.fr"></p>

                                        <input
                                            type="hidden"
                                            :name="'options[' + element.id + '][fr]'"
                                            v-model="element.params.fr"
                                        />
                                    </x-admin::table.td>

                                    <!-- Dutch Loacle -->
                                    <x-admin::table.td>
                                        <p v-text="element.params.nl"></p>

                                        <input
                                            type="hidden"
                                            :name="'options[' + element.id + '][nl]'"
                                            v-model="element.params.nl"
                                        />
                                    </x-admin::table.td>

                                    <!-- Turkce Loacle -->
                                    <x-admin::table.td>
                                        <p v-text="element.params.tr"></p>

                                        <input
                                            type="hidden"
                                            :name="'options[' + element.id + '][tr]'"
                                            v-model="element.params.tr"
                                        />
                                    </x-admin::table.td>

                                    <!-- Espanol Loacle -->
                                    <x-admin::table.td>
                                        <p v-text="element.params.es"></p>

                                        <input
                                            type="hidden"
                                            :name="'options[' + element.id + '][es]'"
                                            v-model="element.params.es"
                                        />
                                    </x-admin::table.td>

                                    <!-- Position Loacle -->
                                    <x-admin::table.td>
                                        <p v-text="element.params.sort_order"></p>

                                        <input
                                            type="hidden"
                                            :name="'options[' + element.id + '][sort_order]'"
                                            v-model="element.params.sort_order"
                                        />
                                    </x-admin::table.td>

                                    <!-- Actions Bustion -->
                                    <x-admin::table.td class="!px-0">
                                        <span
                                            class="icon-edit p-[6px] rounded-[6px] text-[24px] cursor-pointer transition-all hover:bg-gray-100 max-sm:place-self-center"
                                            @click="editModal(element.id)"
                                        ></span> 
                                    </x-admin::table.td>
                                </x-admin::table.tr>
                            </template>
                        </draggable>
                    </x-admin::table>
                </div>
            </div>
        </script>

        <script type="module">
            app.component('v-option-wrapper', {
                template: '#v-option-wrapper-template',

                data: function() {
                    return {
                        optionRowCount: 1,

                        show_swatch: false,

                        attribute_type: '',

                        swatch_attribute: false,

                        show_swatch: false,

                        isNullOptionChecked: false,

                        idNullOption: null,

                        options: [],
                    }
                },

                methods: {
                    storeOptions(params, {resetForm, setValues}) {
                        let foundIndex = this.options.findIndex(item => item.id === params.id);

                        if (foundIndex !== -1) {
                            let updatedObject = {
                                ...this.options[foundIndex],
                                params: {
                                    ...this.options[foundIndex].params,
                                    ...params,
                                }
                            };

                            this.options.splice(foundIndex, 1, updatedObject);
                        } else {
                            let rowCount = this.optionRowCount++;
                            let id = 'option_' + rowCount;
                            let row = {'id': id, params};
    
                            this.options.push(row);
                        }

                        this.$refs.addOptionsRow.toggle();

                        resetForm();
                    },

                    editModal(value) {
                        const foundData = this.options.find(item => item.id === value);
                        // For set value on edit form
                        this.$refs.modelForm.setValues(foundData.params);

                        this.$refs.modelForm.setValues(foundData);

                        this.$refs.addOptionsRow.toggle();
                    },
                },

                watch: {
                    isNullOptionChecked: function (val) {
                        /* 
                        *  Here else part code is useless 
                        *  Need to add code for When modal is closed after that input checkbox should unchecked
                        */ 
                        if (val) {
                            // For open existing model
                            if (! this.idNullOption) {
                                this.$refs.addOptionsRow.toggle();
                            }
                        } else if(this.idNullOption !== null && typeof this.idNullOption !== 'undefined') {
                            this.$refs.addOptionsRow.toggle()
                        }
                    },
                },
            });
        </script>
    @endPushOnce
</x-admin::layouts>