<x-admin::layouts>
    <!-- Page Title -->
    <x-slot:title>
        @lang('admin::app.settings.data-transfer.imports.create.title')
    </x-slot>

    {!! view_render_event('bagisto.admin.settings.data_transfer.imports.create.before') !!}

    <x-admin::form
        :action="route('admin.settings.data_transfer.imports.store')"
        enctype="multipart/form-data"
    >
        {!! view_render_event('bagisto.admin.settings.data_transfer.imports.create.create_form_controls.before') !!}

        <!-- Page Header -->
        <div class="flex items-center justify-between">
            <p class="text-xl font-bold text-gray-800 dark:text-white">
                @lang('admin::app.settings.data-transfer.imports.create.title')
            </p>

            <div class="flex items-center gap-x-2.5">
                <!-- Back Button -->
                <a
                    href="{{ route('admin.settings.data_transfer.imports.index') }}"
                    class="transparent-button hover:bg-gray-200 dark:text-white dark:hover:bg-gray-800"
                >
                    @lang('admin::app.settings.data-transfer.imports.create.back-btn')
                </a>

                <!-- Save Button -->
                <button
                    type="submit"
                    class="primary-button"
                >
                    @lang('admin::app.settings.data-transfer.imports.create.save-btn')
                </button>
            </div>
        </div>

        <!-- Body Content -->
        <div class="mt-3.5 flex gap-2.5 max-xl:flex-wrap">
            <!-- Left Container -->
            <div class="flex flex-1 flex-col gap-2 max-xl:flex-auto">
                {!! view_render_event('bagisto.admin.settings.data_transfer.imports.create.card.general.before') !!}

                <!-- Setup Import Panel -->
                <div class="box-shadow rounded bg-white p-4 dark:bg-gray-900">
                    <p class="mb-4 text-base font-semibold text-gray-800 dark:text-white">
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
                            id="import-type"
                            :value="old('type') ?? 'products'"
                            ref="importType"
                            rules="required"
                            :label="trans('admin::app.settings.data-transfer.imports.create.type')"
                        >
                            @foreach (config('importers') as $code => $importer)
                                <option value="{{ $code }}">@lang($importer['title'])</option>
                            @endforeach
                        </x-admin::form.control-group.control>

                        <!-- Source Sample Download Links -->
                        <a
                            :href="'{{ route('admin.settings.data_transfer.imports.download_sample') }}/' + $refs['importType']?.value"
                            target="_blank"
                            id="source-sample-link"
                            class="mt-1 cursor-pointer text-sm text-blue-600 transition-all hover:underline"
                        >
                            @lang('admin::app.settings.data-transfer.imports.create.download-sample')
                        </a>

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
                            rules="required"
                            :label="trans('admin::app.settings.data-transfer.imports.create.file')"
                        />

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
                            :value="old('images_directory_path')"
                            :placeholder="trans('admin::app.settings.data-transfer.imports.create.images-directory')"
                        />

                        <p class="mt-2 text-xs text-gray-600 dark:text-gray-300">
                            @lang('admin::app.settings.data-transfer.imports.create.file-info')
                        </p>

                        <p class="mt-2 text-xs text-gray-600 dark:text-gray-300">
                            @lang('admin::app.settings.data-transfer.imports.create.file-info-example')
                        </p>
                    </x-admin::form.control-group>
                </div>

                {!! view_render_event('bagisto.admin.settings.data_transfer.imports.create.card.general.after') !!}
            </div>

            <!-- Right Container -->
            <div class="flex w-[360px] max-w-full flex-col gap-2 max-sm:w-full">
                {!! view_render_event('bagisto.admin.settings.data_transfer.imports.create.card.accordion.settings.before') !!}

                <!-- Settings Panel -->
                <x-admin::accordion>
                    <x-slot:header>
                        <div class="flex items-center justify-between">
                            <p class="p-2.5 text-base font-semibold text-gray-800 dark:text-white">
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
                                :value="old('action') ?? 'append'"
                                rules="required"
                                :label="trans('admin::app.settings.data-transfer.imports.create.action')"
                            >
                                <option value="append">@lang('admin::app.settings.data-transfer.imports.create.create-update')</option>
                                <option value="delete">@lang('admin::app.settings.data-transfer.imports.create.delete')</option>
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
                                :value="old('validation_strategy') ?? 'stop-on-errors'"
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
                                :value="old('allowed_errors') ?? 10"
                                rules="required"
                                :label="trans('admin::app.settings.data-transfer.imports.create.allowed-errors')"
                                :placeholder="trans('admin::app.settings.data-transfer.imports.create.allowed-errors')"
                            />

                            <x-admin::form.control-group.error control-name="allowed_errors" />
                        </x-admin::form.control-group>

                        <!-- CSV Field Separator -->
                        <x-admin::form.control-group>
                            <x-admin::form.control-group.label class="required">
                                @lang('admin::app.settings.data-transfer.imports.create.field-separator')
                            </x-admin::form.control-group.label>

                            <x-admin::form.control-group.control
                                type="text"
                                name="field_separator"
                                :value="old('field_separator') ?? ','"
                                rules="required"
                                :label="trans('admin::app.settings.data-transfer.imports.create.field-separator')"
                                :placeholder="trans('admin::app.settings.data-transfer.imports.create.field-separator')"
                            />

                            <x-admin::form.control-group.error control-name="field_separator" />
                        </x-admin::form.control-group>

                        <!-- Process In Queue -->
                        <x-admin::form.control-group class="!mb-0">
                            <x-admin::form.control-group.label class="required">
                                @lang('admin::app.settings.data-transfer.imports.create.process-in-queue')
                            </x-admin::form.control-group.label>

                            <x-admin::form.control-group.control
                                type="switch"
                                name="process_in_queue"
                                id="maintenance-mode-status"
                                :checked="false"
                            />

                            <x-admin::form.control-group.error control-name="process_in_queue" />
                        </x-admin::form.control-group>
                    </x-slot>
                </x-admin::accordion>

                {!! view_render_event('bagisto.admin.settings.data_transfer.imports.create.card.accordion.settings.after') !!}
            </div>
        </div>

        {!! view_render_event('bagisto.admin.settings.data_transfer.imports.create.create_form_controls.after') !!}
    </x-admin::form>
</x-admin::layouts>
