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
                :action="route('admin.sales.rma.custom-field.store')"
                enctype="multipart/form-data"
            >
                <div class="flex gap-2.5 mt-3.5">
                    <!-- Input Form -->
                    <div class="flex flex-col gap-2 flex-1 overflow-auto">
                        <div class="flex justify-between items-center">
                            <p class="text-xl text-gray-800 dark:text-white font-bold">
                                @lang('admin::app.sales.rma.custom-field.create.create-title')
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
                        
                        <!-- General -->
                        <div class="bg-white dark:bg-gray-900 box-shadow rounded">
                            <div class="flex justify-between items-center p-1.5">
                                <p class="p-2.5 text-gray-800 dark:text-white text-base font-semibold">
                                    @lang('admin::app.catalog.attributes.create.general')
                                </p>
                            </div>

                            <div class="px-4 pb-4">
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
                                        :checked="(boolean) old('status')"
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
                                        value="{{ old('code') }}"
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
                                        :label="trans('admin::app.catalog.attributes.create.position')"
                                        :placeholder="trans('admin::app.catalog.attributes.create.position')"
                                    />

                                    <x-admin::form.control-group.error control-name="position" />
                                </x-admin::form.control-group>

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
                                        :value="old('type')"
                                        :label="trans('admin::app.catalog.attributes.index.datagrid.type')"
                                    >
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
                                            :value="old('input_validation')"
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

                                <!-- Is Required -->
                                <x-admin::form.control-group class="flex gap-2.5 items-center !mb-2">
                                    <x-admin::form.control-group.control
                                        type="checkbox"
                                        id="is_required"
                                        name="is_required"
                                        value="1"
                                        for="is_required"
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
                        attributeType: '',

                        options: [
                            {
                                option: '',
                                value: ''
                            }
                        ],
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