<x-admin::layouts>
    <!-- Page Title -->
    <x-slot:title>
        @lang('admin::app.settings.data-transfer.imports.edit.title')
    </x-slot>

    {!! view_render_event('bagisto.admin.settings.data_transfer.imports.create.before') !!}

    <x-admin::form
        :action="route('admin.settings.data_transfer.imports.update', $import->id)"
        method="PUT"
        enctype="multipart/form-data"
    >
        {!! view_render_event('bagisto.admin.settings.data_transfer.imports.create.create_form_controls.before') !!}

        <!-- Page Header -->
        <div class="flex justify-between items-center">
            <p class="text-xl text-gray-800 dark:text-white font-bold">
                @lang('admin::app.settings.data-transfer.imports.edit.title')
            </p>

            <div class="flex gap-x-2.5 items-center">
                <!-- Cancel Button -->
                <a
                    href="{{ route('admin.settings.data_transfer.imports.index') }}"
                    class="transparent-button hover:bg-gray-200 dark:hover:bg-gray-800 dark:text-white"
                >
                    @lang('admin::app.settings.data-transfer.imports.edit.back-btn')
                </a>

                <!-- Save Button -->
                <button
                    type="submit"
                    class="primary-button"
                >
                    @lang('admin::app.settings.data-transfer.imports.edit.save-btn')
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
                        @lang('admin::app.settings.data-transfer.imports.edit.general')
                    </p>

                    <!-- Type -->
                    <x-admin::form.control-group>
                        <x-admin::form.control-group.label class="required">
                            @lang('admin::app.settings.data-transfer.imports.edit.type')
                        </x-admin::form.control-group.label>

                        <x-admin::form.control-group.control
                            type="select"
                            name="type"
                            id="type"
                            :value="old('type') ?? $import->type"
                            ref="importType"
                            rules="required"
                            :label="trans('admin::app.settings.data-transfer.imports.edit.type')"
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
                            class="text-sm text-blue-600 cursor-pointer transition-all hover:underline mt-1"
                        >
                            @lang('admin::app.settings.data-transfer.imports.edit.download-sample')
                        </a>

                        <x-admin::form.control-group.error control-name="type" />
                    </x-admin::form.control-group>

                    <!-- Images Directory Path -->
                    <x-admin::form.control-group>
                        <x-admin::form.control-group.label>
                            @lang('admin::app.settings.data-transfer.imports.edit.file')
                        </x-admin::form.control-group.label>

                        <x-admin::form.control-group.control
                            type="file"
                            name="file"
                            :label="trans('admin::app.settings.data-transfer.imports.edit.file')"
                        />

                        <x-admin::form.control-group.error control-name="file" />
                    </x-admin::form.control-group>

                    <!-- Images Directory Path -->
                    <x-admin::form.control-group class="!mb-0">
                        <x-admin::form.control-group.label>
                            @lang('admin::app.settings.data-transfer.imports.edit.images-directory')
                        </x-admin::form.control-group.label>

                        <x-admin::form.control-group.control
                            type="text"
                            name="images_directory_path"
                            :value="old('images_directory_path') ?? $import->images_directory_path"
                            :placeholder="trans('admin::app.settings.data-transfer.imports.edit.images-directory')"
                        />

                        <p class="mt-2 text-xs text-gray-600 dark:text-gray-300">
                            @lang('admin::app.settings.data-transfer.imports.edit.file-info')
                        </p>

                        <p class="mt-2 text-xs text-gray-600 dark:text-gray-300">
                            @lang('admin::app.settings.data-transfer.imports.edit.file-info-example')
                        </p>
                    </x-admin::form.control-group>
                </div>

                {!! view_render_event('bagisto.admin.settings.data_transfer.imports.create.card.general.after') !!}
            </div>

            <!-- Right Container -->
            <div class="flex flex-col gap-2 w-[360px] max-w-full max-sm:w-full">
                {!! view_render_event('bagisto.admin.settings.data_transfer.imports.create.card.accordion.settings.before') !!}

                <!-- Settings Panel -->
                <x-admin::accordion>
                    <x-slot:header>
                        <div class="flex items-center justify-between">
                            <p class="p-2.5 text-base text-gray-800 dark:text-white font-semibold">
                                @lang('admin::app.settings.data-transfer.imports.edit.settings')
                            </p>
                        </div>
                    </x-slot>

                    <x-slot:content>
                        <!-- Action -->
                        <x-admin::form.control-group>
                            <x-admin::form.control-group.label class="required">
                                @lang('admin::app.settings.data-transfer.imports.edit.action')
                            </x-admin::form.control-group.label>

                            <x-admin::form.control-group.control
                                type="select"
                                name="action"
                                id="action"
                                :value="old('action') ?? $import->action"
                                rules="required"
                                :label="trans('admin::app.settings.data-transfer.imports.edit.action')"
                            >
                                <option value="append">@lang('admin::app.settings.data-transfer.imports.edit.create-update')</option>
                                <option value="delete">@lang('admin::app.settings.data-transfer.imports.edit.delete')</option>
                            </x-admin::form.control-group.control>

                            <x-admin::form.control-group.error control-name="action" />
                        </x-admin::form.control-group>

                        <!-- Validation Strategy -->
                        <x-admin::form.control-group>
                            <x-admin::form.control-group.label class="required">
                                @lang('admin::app.settings.data-transfer.imports.edit.validation-strategy')
                            </x-admin::form.control-group.label>

                            <x-admin::form.control-group.control
                                type="select"
                                name="validation_strategy"
                                id="validation_strategy"
                                :value="old('validation_strategy') ?? $import->validation_strategy"
                                rules="required"
                                :label="trans('admin::app.settings.data-transfer.imports.edit.validation-strategy')"
                            >
                                <option value="stop-on-errors">@lang('admin::app.settings.data-transfer.imports.edit.stop-on-errors')</option>
                                <option value="skip-erros">@lang('admin::app.settings.data-transfer.imports.edit.skip-errors')</option>
                            </x-admin::form.control-group.control>

                            <x-admin::form.control-group.error control-name="validation_strategy" />
                        </x-admin::form.control-group>

                        <!-- Allowed Errors -->
                        <x-admin::form.control-group>
                            <x-admin::form.control-group.label class="required">
                                @lang('admin::app.settings.data-transfer.imports.edit.allowed-errors')
                            </x-admin::form.control-group.label>

                            <x-admin::form.control-group.control
                                type="text"
                                name="allowed_errors"
                                :value="old('allowed_errors') ?? $import->allowed_errors"
                                rules="required"
                                :label="trans('admin::app.settings.data-transfer.imports.edit.allowed-errors')"
                                :placeholder="trans('admin::app.settings.data-transfer.imports.edit.allowed-errors')"
                            />

                            <x-admin::form.control-group.error control-name="allowed_errors" />
                        </x-admin::form.control-group>

                        <!-- CSV Field Separator -->
                        <x-admin::form.control-group>
                            <x-admin::form.control-group.label class="required">
                                @lang('admin::app.settings.data-transfer.imports.edit.field-separator')
                            </x-admin::form.control-group.label>

                            <x-admin::form.control-group.control
                                type="text"
                                name="field_separator"
                                :value="old('field_separator') ?? $import->field_separator"
                                rules="required"
                                :label="trans('admin::app.settings.data-transfer.imports.edit.field-separator')"
                                :placeholder="trans('admin::app.settings.data-transfer.imports.edit.field-separator')"
                            />

                            <x-admin::form.control-group.error control-name="field_separator" />
                        </x-admin::form.control-group>

                        <!-- Process In Queue -->
                        <x-admin::form.control-group class="!mb-0">
                            <x-admin::form.control-group.label class="required">
                                @lang('admin::app.settings.data-transfer.imports.edit.process-in-queue')
                            </x-admin::form.control-group.label>

                            <x-admin::form.control-group.control
                                type="switch"
                                name="process_in_queue"
                                :value="1"
                                :checked="(boolean) $import->process_in_queue"
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
