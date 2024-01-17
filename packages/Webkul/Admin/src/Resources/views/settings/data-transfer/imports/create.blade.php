<x-admin::layouts>
    <!-- Page Title -->
    <x-slot:title>
        @lang('admin::app.settings.data-transfer.imports.create.title')
    </x-slot>

    {!! view_render_event('bagisto.admin.settings.data_transfer.imports.create.before') !!}

    <!-- Import Vue Compontent -->
    <v-create-import />

    {!! view_render_event('bagisto.admin.settings.data_transfer.imports.create.after') !!}

    @pushOnce('scripts')
        <script type="text/x-template" id="v-create-import-template">
            <x-admin::form
                v-slot="{ meta, errors, handleSubmit }"
                as="div"
            >
                <form @submit="handleSubmit($event, create)">
                    {!! view_render_event('admin.settings.roles.create.create_form_controls.before') !!}

                    <!-- Page Header -->
                    <div class="flex justify-between items-center">
                        <p class="text-xl text-gray-800 dark:text-white font-bold">
                            @lang('admin::app.settings.data-transfer.imports.create.title')
                        </p>

                        <div class="flex gap-x-2.5 items-center">
                            <!-- Cancel Button -->
                            <a
                                href="{{ route('admin.settings.data_transfer.imports.index') }}"
                                class="transparent-button hover:bg-gray-200 dark:hover:bg-gray-800 dark:text-white"
                            >
                                @lang('admin::app.settings.data-transfer.imports.create.back-btn')
                            </a>

                            <!-- Save Button -->
                            <button
                                type="submit"
                                class="primary-button"
                            >
                                @lang('admin::app.settings.data-transfer.imports.create.validate-btn')
                            </button>
                        </div>
                    </div>

                    <!-- Body Content -->
                    <div class="flex gap-2.5 mt-3.5 max-xl:flex-wrap">
                        <!-- Left Container -->
                        <div class="flex flex-col gap-2 flex-1 max-xl:flex-auto">
                            {!! view_render_event('bagisto.admin.settings.data_transfer.imports.create.card.general.before') !!}

                            <!-- Setup Import Panel -->
                            <div class="p-4 bg-white dark:bg-gray-900 rounded box-shadow">
                                <p class="text-base text-gray-800 dark:text-white font-semibold mb-4">
                                    @lang('admin::app.settings.data-transfer.imports.create.general')
                                </p>

                                <!-- Type -->
                                <x-admin::form.control-group>
                                    <x-admin::form.control-group.label class="required">
                                        @lang('admin::app.settings.data-transfer.imports.create.type')
                                    </x-admin::form.control-group.label>

                                    <x-admin::form.control-group.control
                                        type="select"
                                        name="type"
                                        id="type"
                                        value="products"
                                        rules="required"
                                        :label="trans('admin::app.settings.data-transfer.imports.create.type')"
                                    >
                                        <option value="product">@lang('admin::app.settings.data-transfer.imports.create.products')</option>
                                        <option value="category">@lang('admin::app.settings.data-transfer.imports.create.categories')</option>
                                        <option value="customer">@lang('admin::app.settings.data-transfer.imports.create.customers')</option>
                                    </x-admin::form.control-group.control>

                                    <x-admin::form.control-group.error control-name="type" />
                                </x-admin::form.control-group>

                                <!-- Images Directory Path -->
                                <x-admin::form.control-group>
                                    <x-admin::form.control-group.label class="required">
                                        @lang('admin::app.settings.data-transfer.imports.create.file')
                                    </x-admin::form.control-group.label>

                                    <x-admin::form.control-group.control
                                        type="file"
                                        name="file"
                                        rules="required|mimes:text/csv"
                                        :label="trans('admin::app.settings.data-transfer.imports.create.file')"
                                    >
                                    </x-admin::form.control-group.control>

                                    <x-admin::form.control-group.error control-name="file" />
                                </x-admin::form.control-group>

                                <!-- Images Directory Path -->
                                <x-admin::form.control-group class="!mb-0">
                                    <x-admin::form.control-group.label>
                                        @lang('admin::app.settings.data-transfer.imports.create.images-directory')
                                    </x-admin::form.control-group.label>

                                    <x-admin::form.control-group.control
                                        type="text"
                                        name="images_directory_path"
                                        :placeholder="trans('admin::app.settings.data-transfer.imports.create.images-directory')"
                                    >
                                    </x-admin::form.control-group.control>

                                    <p class="mt-2 text-xs text-gray-600 dark:text-gray-300">
                                        @lang('admin::app.settings.data-transfer.imports.create.file-info')
                                    </p>

                                    <p class="mt-2 text-xs text-gray-600 dark:text-gray-300">
                                        @lang('admin::app.settings.data-transfer.imports.create.file-info-example')
                                    </p>
                                </x-admin::form.control-group>
                            </div>

                            {!! view_render_event('bagisto.admin.settings.data_transfer.imports.create.card.general.after') !!}


                            {!! view_render_event('bagisto.admin.settings.data_transfer.imports.create.card.results.before') !!}

                            <!-- Results Panel -->
                            <div class="p-4 bg-white dark:bg-gray-900 rounded box-shadow">
                                <p class="text-base text-gray-800 dark:text-white font-semibold mb-4">
                                    @lang('admin::app.settings.data-transfer.imports.create.results')
                                </p>
                            </div>

                            {!! view_render_event('bagisto.admin.settings.data_transfer.imports.create.card.results.after') !!}
                        </div>

                        <!-- Right Container -->
                        <div class="flex flex-col gap-2 w-[360px] max-w-full max-sm:w-full">
                            {!! view_render_event('bagisto.admin.settings.data_transfer.imports.create.card.accordion.settings.before') !!}

                            <!-- Settings Panel -->
                            <x-admin::accordion>
                                <x-slot:header>
                                    <div class="flex items-center justify-between">
                                        <p class="p-2.5 text-base text-gray-800 dark:text-white font-semibold">
                                            @lang('admin::app.settings.data-transfer.imports.create.settings')
                                        </p>
                                    </div>
                                </x-slot>

                                <x-slot:content>
                                    <!-- Action -->
                                    <x-admin::form.control-group>
                                        <x-admin::form.control-group.label class="required">
                                            @lang('admin::app.settings.data-transfer.imports.create.action')
                                        </x-admin::form.control-group.label>

                                        <x-admin::form.control-group.control
                                            type="select"
                                            name="action"
                                            id="action"
                                            value="append"
                                            rules="required"
                                            :label="trans('admin::app.settings.data-transfer.imports.create.action')"
                                        >
                                            <option value="append">@lang('admin::app.settings.data-transfer.imports.create.create-update')</option>
                                            <option value="update">@lang('admin::app.settings.data-transfer.imports.create.replace')</option>
                                            <option value="replace">@lang('admin::app.settings.data-transfer.imports.create.delete')</option>
                                        </x-admin::form.control-group.control>

                                        <x-admin::form.control-group.error control-name="action" />
                                    </x-admin::form.control-group>

                                    <!-- Validation Strategy -->
                                    <x-admin::form.control-group>
                                        <x-admin::form.control-group.label class="required">
                                            @lang('admin::app.settings.data-transfer.imports.create.validation-strategy')
                                        </x-admin::form.control-group.label>

                                        <x-admin::form.control-group.control
                                            type="select"
                                            name="validation_strategy"
                                            id="validation_strategy"
                                            value="stop-on-errors"
                                            rules="required"
                                            :label="trans('admin::app.settings.data-transfer.imports.create.validation-strategy')"
                                        >
                                            <option value="stop-on-errors">@lang('admin::app.settings.data-transfer.imports.create.stop-on-errors')</option>
                                            <option value="skip-erros">@lang('admin::app.settings.data-transfer.imports.create.skip-errors')</option>
                                        </x-admin::form.control-group.control>

                                        <x-admin::form.control-group.error control-name="validation_strategy" />
                                    </x-admin::form.control-group>

                                    <!-- Allowed Errors -->
                                    <x-admin::form.control-group>
                                        <x-admin::form.control-group.label class="required">
                                            @lang('admin::app.settings.data-transfer.imports.create.allowed-errors')
                                        </x-admin::form.control-group.label>

                                        <x-admin::form.control-group.control
                                            type="text"
                                            name="allowed_errors"
                                            value="10"
                                            rules="required"
                                            :label="trans('admin::app.settings.data-transfer.imports.create.allowed-errors')"
                                            :placeholder="trans('admin::app.settings.data-transfer.imports.create.allowed-errors')"
                                        >
                                        </x-admin::form.control-group.control>

                                        <x-admin::form.control-group.error control-name="allowed_errors" />
                                    </x-admin::form.control-group>

                                    <!-- CSV Field Separator -->
                                    <x-admin::form.control-group class="!mb-0">
                                        <x-admin::form.control-group.label class="required">
                                            @lang('admin::app.settings.data-transfer.imports.create.field-separator')
                                        </x-admin::form.control-group.label>

                                        <x-admin::form.control-group.control
                                            type="text"
                                            name="field_separator"
                                            value=","
                                            rules="required"
                                            :label="trans('admin::app.settings.data-transfer.imports.create.field-separator')"
                                            :placeholder="trans('admin::app.settings.data-transfer.imports.create.field-separator')"
                                        >
                                        </x-admin::form.control-group.control>

                                        <x-admin::form.control-group.error control-name="field_separator" />
                                    </x-admin::form.control-group>
                                </x-slot>
                            </x-admin::accordion>

                            {!! view_render_event('bagisto.admin.settings.data_transfer.imports.create.card.accordion.settings.after') !!}
                        </div>
                    </div>

                    {!! view_render_event('admin.settings.roles.create.create_form_controls.after') !!}
                </form>
            </x-admin::form>
        </script>

        <script type="module">
            app.component('v-create-import', {
                template: '#v-create-import-template',

                data() {
                    return {
                    };
                },

                methods: {
                    create(params, { resetForm, resetField, setErrors }) {
                        this.$axios.post("{{ route('admin.settings.data_transfer.imports.store') }}", params, {
                            headers: {
                                'Content-Type': 'multipart/form-data'
                            }
                        })
                            .then((response) => {
                                
                            })
                            .catch(error => {
                                if (error.response.status == 422) {
                                    setErrors(error.response.data.errors);
                                }
                            });
                    }
                }
            })
        </script>
    @endPushOnce
</x-admin::layouts>
