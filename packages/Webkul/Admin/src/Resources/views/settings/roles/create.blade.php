<x-admin::layouts>
    <!-- Page Title -->
    <x-slot:title>
        @lang('admin::app.settings.roles.create.title')
    </x-slot>

    {!! view_render_event('bagisto.admin.settings.roles.create.before') !!}

    <!-- Create Role for -->
    <v-create-user-role></v-create-user-role>

    {!! view_render_event('bagisto.admin.settings.roles.create.after') !!}

    @pushOnce('scripts')
        <script
            type="text/x-template"
            id="v-create-user-role-template"
        >
            <div>
                <x-admin::form :action="route('admin.settings.roles.store')">
                    {!! view_render_event('bagisto.admin.settings.roles.create.create_form_controls.before') !!}

                    <div class="flex justify-between items-center">
                        <p class="text-xl text-gray-800 dark:text-white font-bold">
                            @lang('admin::app.settings.roles.create.title')
                        </p>

                        <div class="flex gap-x-2.5 items-center">
                            <!-- Cancel Button -->
                            <a
                                href="{{ route('admin.settings.roles.index') }}"
                                class="transparent-button hover:bg-gray-200 dark:hover:bg-gray-800 dark:text-white"
                            >
                                @lang('admin::app.settings.roles.create.back-btn')
                            </a>

                            <!-- Save Button -->
                            <button
                                type="submit"
                                class="primary-button"
                            >
                                @lang('admin::app.settings.roles.create.save-btn')
                            </button>
                        </div>
                    </div>

                    <!-- body content -->
                    <div class="flex gap-2.5 mt-3.5 max-xl:flex-wrap">
                        <!-- Left sub-component -->
                        <div class="flex flex-col gap-2 flex-1 max-xl:flex-auto">

                            {!! view_render_event('bagisto.admin.settings.roles.create.card.access_control.before') !!}

                            <!-- Access Control Input Fields -->
                            <div class="p-4 bg-white dark:bg-gray-900 rounded box-shadow">
                                <p class="text-base text-gray-800 dark:text-white font-semibold mb-4">
                                    @lang('admin::app.settings.roles.create.access-control')
                                </p>

                                <!-- Permission Type -->
                                <x-admin::form.control-group>
                                    <x-admin::form.control-group.label class="required">
                                        @lang('admin::app.settings.roles.create.permissions')
                                    </x-admin::form.control-group.label>

                                    <x-admin::form.control-group.control
                                        type="select"
                                        name="permission_type"
                                        id="permission_type"
                                        rules="required"
                                        :label="trans('admin::app.settings.roles.create.permissions')"
                                        :placeholder="trans('admin::app.settings.roles.create.permissions')"
                                        v-model="permission_type"
                                    >
                                        <option value="custom">
                                            @lang('admin::app.settings.roles.create.custom')
                                        </option>

                                        <option value="all">
                                            @lang('admin::app.settings.roles.create.all')
                                        </option>
                                    </x-admin::form.control-group.control>

                                    <x-admin::form.control-group.error control-name="permission_type" />
                                </x-admin::form.control-group>

                                <div v-if="permission_type == 'custom'">
                                    <x-admin::tree.view
                                        input-type="checkbox"
                                        value-field="key"
                                        id-field="key"
                                        :items="json_encode($acl->items)"
                                        :fallback-locale="config('app.fallback_locale')"
                                    />
                                </div>
                            </div>

                            {!! view_render_event('bagisto.admin.settings.roles.create.card.access_control.after') !!}

                        </div>
                        <!-- Right sub-component -->
                        <div class="flex flex-col gap-2 w-[360px] max-w-full max-sm:w-full">

                            {!! view_render_event('bagisto.admin.settings.roles.create.card.accordion.general.before') !!}

                            <x-admin::accordion>
                                <x-slot:header>
                                    <div class="flex items-center justify-between">
                                        <p class="p-2.5 text-base text-gray-800 dark:text-white font-semibold">
                                            @lang('admin::app.settings.roles.create.general')
                                        </p>
                                    </div>
                                </x-slot>

                                <x-slot:content>
                                    <!-- Name -->
                                    <x-admin::form.control-group>
                                        <x-admin::form.control-group.label class="required">
                                            @lang('admin::app.settings.roles.create.name')
                                        </x-admin::form.control-group.label>

                                        <x-admin::form.control-group.control
                                            type="text"
                                            id="name"
                                            name="name"
                                            rules="required"
                                            value="{{ old('name') }}"
                                            :label="trans('admin::app.settings.roles.create.name')"
                                            :placeholder="trans('admin::app.settings.roles.create.name')"
                                        />

                                        <x-admin::form.control-group.error control-name="name" />
                                    </x-admin::form.control-group>

                                    <!-- Description -->
                                    <x-admin::form.control-group class="!mb-0">
                                        <x-admin::form.control-group.label class="required">
                                            @lang('admin::app.settings.roles.create.description')
                                        </x-admin::form.control-group.label>

                                        <x-admin::form.control-group.control
                                            type="textarea"
                                            id="description"
                                            name="description"
                                            rules="required"
                                            :value="old('description')"
                                            :label="trans('admin::app.settings.roles.create.description')"
                                            :placeholder="trans('admin::app.settings.roles.create.description')"
                                        />

                                        <x-admin::form.control-group.error control-name="description" />
                                    </x-admin::form.control-group>
                                </x-slot>
                            </x-admin::accordion>

                            {!! view_render_event('bagisto.admin.settings.roles.create.card.accordion.general.after') !!}

                        </div>
                    </div>

                    {!! view_render_event('bagisto.admin.settings.roles.create.create_form_controls.after') !!}

                </x-admin::form>
            </div>
        </script>

        <script type="module">
            app.component('v-create-user-role', {
                template: '#v-create-user-role-template',

                data() {
                    return {
                        permission_type: 'custom'
                    };
                }
            })
        </script>
    @endPushOnce
</x-admin::layouts>
