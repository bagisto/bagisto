<x-admin::layouts>
    <x-slot:title>
        @lang('admin::app.catalog.families.edit.title')
    </x-slot>

    <!-- Input Form -->
    <x-admin::form
        method="PUT"
        :action="route('admin.catalog.families.update', $attributeFamily->id)"
    >

        {!! view_render_event('bagisto.admin.catalog.families.edit.edit_form_control.before', ['attributeFamily' => $attributeFamily]) !!}

        <!-- Page Header -->
        <div class="flex items-center justify-between gap-4 max-sm:flex-wrap">
            <p class="text-xl font-bold text-gray-800 dark:text-white">
                @lang('admin::app.catalog.families.edit.title')
            </p>

            <div class="flex items-center gap-x-2.5">
                <a
                    href="{{ route('admin.catalog.families.index') }}"
                    class="transparent-button hover:bg-gray-200 dark:text-white dark:hover:bg-gray-800"
                >
                    @lang('admin::app.catalog.families.edit.back-btn')
                </a>

                <button 
                    type="submit" 
                    class="primary-button"
                >
                    @lang('admin::app.catalog.families.edit.save-btn')
                </button>
            </div>
        </div>

        <!-- Container -->
        <div class="mt-3.5 flex gap-2.5 max-xl:flex-wrap">
            <!-- Left Container -->

            {!! view_render_event('bagisto.admin.catalog.families.edit.card.attributes-panel.before', ['attributeFamily' => $attributeFamily]) !!}

            <div class="box-shadow flex min-w-0 flex-1 flex-col rounded-sm bg-white dark:bg-gray-900 max-xl:flex-auto">
                <x-admin::catalog.families.attributes-panel
                    :family="$attributeFamily"
                    :custom-attributes="$customAttributes"
                    translation="admin::app.catalog.families.edit"
                />
            </div>

            {!! view_render_event('bagisto.admin.catalog.families.edit.card.attributes-panel.after', ['attributeFamily' => $attributeFamily]) !!}

            {!! view_render_event('bagisto.admin.catalog.families.edit.card.accordion.general.before', ['attributeFamily' => $attributeFamily]) !!}

            <!-- Right Container -->
            <div class="flex w-90 max-w-full select-none flex-col gap-2">
                <!-- General Panel -->
                <x-admin::accordion>
                    <!-- Panel Header -->
                    <x-slot:header>
                        <p class="p-2.5 text-base font-semibold text-gray-800 dark:text-white">
                            @lang('admin::app.catalog.families.edit.general')
                        </p>
                    </x-slot>

                    <!-- Panel Content -->
                    <x-slot:content>
                        <x-admin::form.control-group>
                            <x-admin::form.control-group.label class="text-gray-800! dark:text-white!">
                                @lang('admin::app.catalog.families.edit.code')
                            </x-admin::form.control-group.label>

                            <x-admin::form.control-group.control
                                type="text"
                                name="code"
                                rules="required"
                                value="{{ old('code') ?? $attributeFamily->code }}"
                                disabled="disabled"
                                :label="trans('admin::app.catalog.families.create.code')"
                                :placeholder="trans('admin::app.catalog.families.edit.enter-code')"
                            />

                            <input
                                type="hidden"
                                name="code"
                                value="{{ $attributeFamily->code }}"
                            />

                            <x-admin::form.control-group.error control-name="code" />
                        </x-admin::form.control-group>

                        <!-- Name -->
                        <x-admin::form.control-group class="mb-0!">
                            <x-admin::form.control-group.label class="text-gray-800! dark:text-white!">
                                @lang('admin::app.catalog.families.edit.name')
                            </x-admin::form.control-group.label>

                            <x-admin::form.control-group.control
                                type="text"
                                name="name"
                                rules="required"
                                value="{{ old('name') ?? $attributeFamily->name }}"
                                :label="trans('admin::app.catalog.families.create.name')"
                                :placeholder="trans('admin::app.catalog.families.edit.enter-name')"
                            />

                            <x-admin::form.control-group.error control-name="name" />
                        </x-admin::form.control-group>
                    </x-slot>
                </x-admin::accordion>
            </div>

            {!! view_render_event('bagisto.admin.catalog.families.edit.card.accordion.general.after', ['attributeFamily' => $attributeFamily]) !!}
        </div>

        {!! view_render_event('bagisto.admin.catalog.families.edit.edit_form_control.after', ['attributeFamily' => $attributeFamily]) !!}

    </x-admin::form>
</x-admin::layouts>
