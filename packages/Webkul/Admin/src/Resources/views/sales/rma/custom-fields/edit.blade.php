<x-admin::layouts>
    <!-- Title of the page -->
    <x-slot:title>
        @lang('admin::app.sales.rma.custom-field.create.create-title')
    </x-slot>

    {!! view_render_event('bagisto.admin.catalog.rma.custom-field.list.before') !!}

    <v-rma-custom-field></v-rma-custom-field>

    {!! view_render_event('bagisto.admin.catalog.rma.custom-field.list.after') !!}

    @pushOnce('scripts')
        <script
            type="text/x-template"
            id="v-rma-custom-field-template"
        >
            <x-admin::form
                :action="route('admin.sales.rma.custom-field.update', $rmaData->id)"
                enctype="multipart/form-data"
            >
                <div class="flex gap-2.5 mt-3.5">
                    <!-- Input Form -->
                    <div class="flex flex-col gap-2 flex-1 overflow-auto">
                        <div class="flex justify-between items-center">
                            <p class="text-xl text-gray-800 dark:text-white font-bold">
                                @lang('admin::app.sales.rma.custom-field.edit.edit-title')
                            </p>

                            <div class="flex gap-x-2.5 items-center">
                                <!-- Cancel Button -->
                                <a
                                    href="{{ route('admin.sales.rma.custom-field.index') }}"
                                    class="transparent-button hover:bg-gray-200 dark:hover:bg-gray-800 dark:text-white"
                                >
                                    @lang('admin::app.catalog.attributes.create.back-btn')
                                </a>

                                <!-- Save Button -->
                                <button
                                    type="submit"
                                    class="primary-button"
                                >
                                    @lang('admin::app.sales.rma.all-rma.view.save-btn')
                                </button>
                            </div>
                        </div>

                        @php
                            $channels = core()->getAllChannels();

                            $currentChannel = core()->getRequestedChannel();

                            $currentLocale = core()->getRequestedLocale();
                        @endphp

                        <!-- Channel and Locale Switcher -->
                        <div class="mt-7 flex items-center justify-between gap-4 max-md:flex-wrap">
                            <div class="flex items-center gap-x-1">
                                <!-- Channel Switcher -->
                                <x-admin::dropdown :class="$channels->count() <= 1 ? 'hidden' : ''">
                                    <!-- Dropdown Toggler -->
                                    <x-slot:toggle>
                                        <button
                                            type="button"
                                            class="transparent-button px-1 py-1.5 hover:bg-gray-200 focus:bg-gray-200 dark:text-white dark:hover:bg-gray-800 dark:focus:bg-gray-800"
                                        >
                                            <span class="icon-store text-2xl"></span>

                                            {{ $currentChannel->name }}

                                            <input
                                                type="hidden"
                                                name="channel"
                                                value="{{ $currentChannel->code }}"
                                            />

                                            <span class="icon-sort-down text-2xl"></span>
                                        </button>
                                    </x-slot>

                                    <!-- Dropdown Content -->
                                    <x-slot:content class="!p-0">
                                        @foreach ($channels as $channel)
                                            <a
                                                href="?{{ Arr::query(['channel' => $channel->code, 'locale' => $currentLocale->code]) }}"
                                                class="flex cursor-pointer gap-2.5 px-5 py-2 text-base hover:bg-gray-100 dark:text-white dark:hover:bg-gray-950"
                                            >
                                                {{ $channel->name }}
                                            </a>
                                        @endforeach
                                    </x-slot>
                                </x-admin::dropdown>

                                <!-- Locale Switcher -->
                                <x-admin::dropdown :class="$currentChannel->locales->count() <= 1 ? 'hidden' : ''">
                                    <!-- Dropdown Toggler -->
                                    <x-slot:toggle>
                                        <button
                                            type="button"
                                            class="transparent-button px-1 py-1.5 hover:bg-gray-200 focus:bg-gray-200 dark:text-white dark:hover:bg-gray-800 dark:focus:bg-gray-800"
                                        >
                                            <span class="icon-language text-2xl"></span>

                                            {{ $currentLocale->name }}

                                            <input
                                                type="hidden"
                                                name="locale"
                                                value="{{ $currentLocale->code }}"
                                            />

                                            <span class="icon-sort-down text-2xl"></span>
                                        </button>
                                    </x-slot>

                                    <!-- Dropdown Content -->
                                    <x-slot:content class="!p-0">
                                        @foreach ($currentChannel->locales->sortBy('name') as $locale)
                                            <a
                                                href="?{{ Arr::query(['channel' => $currentChannel->code, 'locale' => $locale->code]) }}"
                                                class="flex gap-2.5 px-5 py-2 text-base cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-950 dark:text-white {{ $locale->code == $currentLocale->code ? 'bg-gray-100 dark:bg-gray-950' : ''}}"
                                            >
                                                {{ $locale->name }}
                                            </a>
                                        @endforeach
                                    </x-slot>
                                </x-admin::dropdown>
                            </div>
                        </div>

                        <!-- General -->
                        <div class="bg-white dark:bg-gray-900 box-shadow rounded">
                            <div class="flex justify-between items-center p-1.5">
                                <p class="p-2.5 text-gray-800 dark:text-white text-base font-semibold">
                                    @lang('admin::app.catalog.attributes.create.general')
                                </p>
                            </div>

                            <div class="px-4 pb-4">
                                @php $selectedValue = old('status') ?: $rmaData->status @endphp

                                <!-- Status -->
                                <x-admin::form.control-group class="!mb-0">
                                    <x-admin::form.control-group.label>
                                        @lang('admin::app.marketing.promotions.cart-rules.create.status')
                                    </x-admin::form.control-group.label>

                                    <x-admin::form.control-group.control
                                        type="switch"
                                        name="status"
                                        value="1"
                                        :label="trans('admin::app.marketing.promotions.cart-rules.create.status')"
                                        :checked="(boolean) $selectedValue"
                                    />

                                    <x-admin::form.control-group.error control-name="status" />
                                </x-admin::form.control-group>

                                <!-- Label -->
                                <x-admin::form.control-group>
                                    <x-admin::form.control-group.label class="required">
                                        @lang('admin::app.catalog.attributes.create.label')
                                    </x-admin::form.control-group.label>

                                    <x-admin::form.control-group.control
                                        type="text"
                                        name="label"
                                        rules="required"
                                        value="{{ $rmaData->label ?? old('label') }}"
                                        :label="trans('admin::app.catalog.attributes.create.label')"
                                        :placeholder="trans('admin::app.catalog.attributes.create.label')"
                                    />

                                    <x-admin::form.control-group.error control-name="label" />
                                </x-admin::form.control-group>

                                <!-- Code -->
                                <x-admin::form.control-group>
                                    <x-admin::form.control-group.label class="required">
                                        @lang('admin::app.catalog.attributes.index.datagrid.code')
                                    </x-admin::form.control-group.label>

                                    <v-field
                                        type="text"
                                        name="code"
                                        rules="required"
                                        value="{{ $rmaData->code ?? old('code') }}"
                                        v-slot="{ field }"
                                        label="{{ trans('admin::app.catalog.attributes.index.datagrid.code') }}"
                                    >
                                        <input
                                            type="text"
                                            id="code"
                                            class="flex w-full min-h-[39px] py-2 px-3 border rounded-md text-sm text-gray-600 dark:text-gray-300 transition-all hover:border-gray-400 dark:hover:border-gray-400 dark:focus:border-gray-400 focus:border-gray-400 dark:bg-gray-900 dark:border-gray-800"
                                            name="slug"
                                            v-bind="field"
                                            placeholder="{{ trans('admin::app.catalog.attributes.index.datagrid.code') }}"
                                            v-code
                                        >
                                    </v-field>

                                    <x-admin::form.control-group.error control-name="code" />
                                </x-admin::form.control-group>

                                <!-- Position -->
                                <x-admin::form.control-group>
                                    <x-admin::form.control-group.label class="required">
                                        @lang('admin::app.catalog.attributes.create.position')
                                    </x-admin::form.control-group.label>

                                    <x-admin::form.control-group.control
                                        type="text"
                                        name="position"
                                        rules="required"
                                         value="{{ $rmaData->position ?? old('position') }}"
                                        :label="trans('admin::app.catalog.attributes.create.position')"
                                        :placeholder="trans('admin::app.catalog.attributes.create.position')"
                                    />

                                    <x-admin::form.control-group.error control-name="position" />
                                </x-admin::form.control-group>

                                @php $selectedTypeValue = old('type') ?? $rmaData->type @endphp
                                <!-- Type -->
                                <x-admin::form.control-group>
                                    <x-admin::form.control-group.label class="required">
                                        @lang('admin::app.catalog.attributes.index.datagrid.type')
                                    </x-admin::form.control-group.label>

                                    <x-admin::form.control-group.control
                                        type="select"
                                        id="type"
                                        class="cursor-pointer"
                                        name="type"
                                        rules="required"
                                        v-model="attributeType"
                                        :value="$selectedTypeValue"
                                        :value="old('type')"
                                        :label="trans('admin::app.catalog.attributes.index.datagrid.type')"
                                    >
                                        <!-- Here! All Needed types are defined -->
                                        @foreach(['text', 'textarea', 'date', 'select', 'multiselect', 'checkbox', 'radio'] as $type)
                                            @if ($type == 'radio')
                                                <option value="radio">
                                                    @lang('admin::app.catalog.products.edit.types.bundle.update-create.radio')
                                                </option>
                                            @else
                                                <option
                                                    value="{{ $type }}"
                                                    {{ $type === 'text' ? "selected" : '' }}
                                                >
                                                    @lang('admin::app.catalog.attributes.create.'. $type)
                                                </option>
                                            @endif
                                        @endforeach
                                    </x-admin::form.control-group.control>

                                    <x-admin::form.control-group.error control-name="type" />
                                </x-admin::form.control-group>

                                <hr v-if="attributeType == 'select' || attributeType == 'multiselect' || attributeType == 'checkbox' || attributeType == 'radio'"/>

                                <div v-if="attributeType == 'select' || attributeType == 'multiselect' || attributeType == 'checkbox' || attributeType == 'radio'" class="gap-4 mt-2">

                                    <div
                                        v-for="(option, index) in options"
                                        :key="index"
                                        class="flex gap-4"
                                    >
                                        <!-- Option Input -->
                                        <x-admin::form.control-group class="w-full">
                                            <x-admin::form.control-group.label class="required">
                                                @lang('admin::app.catalog.attributes.create.options')
                                            </x-admin::form.control-group.label>

                                            <v-field
                                                type="text"
                                                :name="'options[' + index + ']'"
                                                rules="required"
                                                v-model="option.option"
                                                :value="option.name"
                                                v-slot="{ field }"
                                                label="{{ trans('admin::app.catalog.attributes.create.options') }}"
                                            >
                                                <input
                                                    type="text"
                                                    :id="'options[' + index + ']'"
                                                    :class="[errors['{{ 'name' }}'] ? 'border border-red-600 hover:border-red-600' : '']"
                                                    class="flex w-full min-h-[39px] py-2 px-3 border rounded-md text-sm text-gray-600 dark:text-gray-300 transition-all hover:border-gray-400 dark:hover:border-gray-400 focus:border-gray-400 dark:focus:border-gray-400 dark:bg-gray-900 dark:border-gray-800"
                                                    :name="'options[' + index + ']'"
                                                    v-bind="field"
                                                    placeholder="{{ trans('admin::app.catalog.attributes.create.options') }}"
                                                >
                                            </v-field>

                                            <v-error-message
                                                :name="'options[' + index + ']'"
                                                v-slot="{ message }"
                                            >
                                                <p
                                                    class="mt-1 text-red-600 text-xs italic"
                                                    v-text="message"
                                                >
                                                </p>
                                            </v-error-message>
                                        </x-admin::form.control-group>

                                        <!-- Value Input -->
                                        <x-admin::form.control-group class="w-full">
                                            <x-admin::form.control-group.label class="required">
                                                @lang('admin::app.settings.themes.edit.value-input')
                                            </x-admin::form.control-group.label>

                                            <v-field
                                                type="text"
                                                :name="'value[' + index + ']'"
                                                rules="required"
                                                v-model="option.value"
                                                :value="option.value"
                                                v-slot="{ field }"
                                                label="{{ trans('admin::app.settings.themes.edit.value-input') }}"
                                            >
                                                <input
                                                    type="text"
                                                    :id="'value[' + index + ']'"
                                                    :class="[errors['{{ 'name' }}'] ? 'border border-red-600 hover:border-red-600' : '']"
                                                    class="flex w-full min-h-[39px] py-2 px-3 border rounded-md text-sm text-gray-600 dark:text-gray-300 transition-all hover:border-gray-400 dark:hover:border-gray-400 focus:border-gray-400 dark:focus:border-gray-400 dark:bg-gray-900 dark:border-gray-800"
                                                    :name="'value[' + index + ']'"
                                                    v-bind="field"
                                                    placeholder="{{ trans('admin::app.settings.themes.edit.value-input') }}"
                                                >
                                            </v-field>

                                            <v-error-message
                                                :name="'value[' + index + ']'"
                                                v-slot="{ message }"
                                            >
                                                <p
                                                    class="mt-1 text-red-600 text-xs italic"
                                                    v-text="message"
                                                >
                                                </p>
                                            </v-error-message>
                                        </x-admin::form.control-group>

                                        <span
                                            class="icon-delete text-2xl mt-8 cursor-pointer"
                                            @click="removeOption(index)"
                                            v-if="options.length > 1"
                                        >
                                        </span>
                                    </div>
                                </div>

                                <!-- Add Option Button -->
                                <div v-if="attributeType == 'select' || attributeType == 'multiselect' || attributeType == 'checkbox' || attributeType == 'radio'" class="flex justify-start">
                                    <button
                                        type="button"
                                        class="secondary-button"
                                        @click="addOption()"
                                    >
                                        @lang('admin::app.catalog.products.edit.types.bundle.add-btn')
                                    </button>
                                </div>
                            </div>

                            <hr/>

                            <!-- Validation -->
                            <div class="flex justify-between items-center p-1.5">
                                <p class="p-2.5 text-gray-800 dark:text-white text-base font-semibold">
                                    @lang('admin::app.catalog.attributes.create.validations')
                                </p>
                            </div>

                            <div class="px-4 pb-4">
                                <!-- Input Validation -->
                                <div v-if="attributeType == 'text'">
                                    <x-admin::form.control-group>
                                        <x-admin::form.control-group.label class="required">
                                            @lang('admin::app.catalog.attributes.create.input-validation')
                                        </x-admin::form.control-group.label>

                                        <x-admin::form.control-group.control
                                            type="select"
                                            id="input_validation"
                                            class="cursor-pointer"
                                            name="input_validation"
                                            rules="required"
                                            :value="$rmaData->input_validation ?? old('input_validation')"
                                            :label="trans('admin::app.catalog.attributes.create.input-validation')"
                                        >
                                            @foreach(['numeric', 'email', 'decimal', 'url'] as $type)
                                                <option value="{{ $type }}">
                                                    @lang('admin::app.catalog.attributes.create.' . $type)
                                                </option>
                                            @endforeach
                                        </x-admin::form.control-group.control>

                                        <x-admin::form.control-group.error control-name="input_validation" />
                                    </x-admin::form.control-group>
                                </div>

                                @php $isRequired = old('is_required') ?? $rmaData->is_required; @endphp

                                <!-- Is Required -->
                                <x-admin::form.control-group class="flex gap-2.5 items-center !mb-2">
                                    <x-admin::form.control-group.control
                                        type="checkbox"
                                        id="is_required"
                                        name="is_required"
                                        value="1"
                                        for="is_required"
                                        :checked="(boolean) $isRequired"
                                    />

                                    <label
                                        class="text-xs text-gray-600 dark:text-gray-300 font-medium cursor-pointer"
                                        for="is_required"
                                    >
                                        @lang('admin::app.catalog.attributes.edit.is-required')
                                    </label>
                                </x-admin::form.control-group>
                            </div>
                        </div>
                    </div>
                </div>
            </x-admin::form>
        </script>

        <script type="module">
            app.component('v-rma-custom-field', {
                template: '#v-rma-custom-field-template',

                data() {
                    return {
                        attributeType: "{{ $rmaData->type ?? old('type') }}",
                        options: @json($rmaData->options ?? []) || [{
                            option: '',
                            value: ''
                        }]
                    }
                },

                methods: {
                    addOption() {
                        this.options.push({
                            option: '',
                            value: ''
                        });
                    },

                    removeOption(index) {
                        if (this.options.length > 1) {
                            this.options.splice(index, 1);
                        }
                    },
                }
            });
        </script>
    @endPushOnce
</x-admin::layouts>
