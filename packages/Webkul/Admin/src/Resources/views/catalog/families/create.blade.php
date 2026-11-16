<x-admin::layouts>
    <x-slot:title>
        @lang('admin::app.catalog.families.create.title')
    </x-slot>

    <!-- Input Form -->
    <x-admin::form :action="route('admin.catalog.families.store')">

        {!! view_render_event('bagisto.admin.catalog.families.create.create_form_controls.before') !!}

        <!-- Page Header -->
        <div class="flex items-center justify-between gap-4 max-sm:flex-wrap">
            <p class="text-xl font-bold text-gray-800 dark:text-white">
                @lang('admin::app.catalog.families.create.title')
            </p>

            <div class="flex items-center gap-x-2.5">
                <a
                    href="{{ route('admin.catalog.families.index') }}"
                    class="transparent-button hover:bg-gray-200 dark:text-white dark:hover:bg-gray-800"
                >
                    @lang('admin::app.catalog.families.create.back-btn')
                </a>

                <button
                    type="submit"
                    class="primary-button"
                >
                    @lang('admin::app.catalog.families.create.save-btn')
                </button>
            </div>
        </div>

        <!-- Container -->
        <div class="mt-3.5 flex gap-2.5 max-xl:flex-wrap">
            <!-- Left Container -->
            <div class="box-shadow flex min-w-0 flex-1 flex-col rounded-sm bg-white dark:bg-gray-900 max-xl:flex-auto">
                <x-admin::catalog.families.attributes-panel
                    :family="$attributeFamily"
                    :custom-attributes="$customAttributes"
                    translation="admin::app.catalog.families.create"
                />
            </div>

            <!-- Right Container -->
            <div class="flex w-90 max-w-full select-none flex-col gap-2">
                <!-- General Panel -->
                <x-admin::accordion>
                    <!-- Panel Header -->
                    <x-slot:header>
                        <p class="p-2.5 text-base font-semibold text-gray-800 dark:text-white">
                            @lang('admin::app.catalog.families.create.general')
                        </p>
                    </x-slot>

                    <!-- Panel Content -->
                    <x-slot:content>
                        <!-- Code -->
                        <x-admin::form.control-group>
                            <x-admin::form.control-group.label class="required text-gray-800! dark:text-white!">
                                @lang('admin::app.catalog.families.create.code')
                            </x-admin::form.control-group.label>

                            <x-admin::form.control-group.control
                                type="text"
                                name="code"
                                rules="required"
                                :value="old('code')"
                                :label="trans('admin::app.catalog.families.create.code')"
                                :placeholder="trans('admin::app.catalog.families.create.enter-code')"
                            />

                            <x-admin::form.control-group.error control-name="code" />
                        </x-admin::form.control-group>

                        <!-- Name -->
                        <x-admin::form.control-group class="mb-0!">
                            <x-admin::form.control-group.label class="required text-gray-800! dark:text-white!">
                                @lang('admin::app.catalog.families.create.name')
                            </x-admin::form.control-group.label>

                            <x-admin::form.control-group.control
                                type="text"
                                name="name"
                                rules="required"
                                :value="old('name')"
                                :label="trans('admin::app.catalog.families.create.name')"
                                :placeholder="trans('admin::app.catalog.families.create.enter-name')"
                            />

                            <x-admin::form.control-group.error control-name="name" />
                        </x-admin::form.control-group>
                    </x-slot>
                </x-admin::accordion>
            </div>
        </div>

        {!! view_render_event('bagisto.admin.catalog.families.create.create_form_controls.after') !!}

    </x-admin::form>
</x-admin::layouts>
