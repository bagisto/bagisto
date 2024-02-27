<x-admin::layouts>

    <!-- Page Title -->
    <x-slot:title>
        @lang('admin::app.settings.roles.edit.title')
    </x-slot>

    {!! view_render_event('bagisto.admin.settings.roles.edit.before') !!}

    <!-- Edit Role for  -->
    <v-edit-user-role></v-edit-user-role>

    {!! view_render_event('bagisto.admin.settings.roles.edit.after') !!}

    @pushOnce('scripts')
        <script
            type="text/x-template"
            id="v-edit-user-role-template"
        >
            <div>
                <x-admin::form
                    method="PUT"
                    :action="route('admin.settings.roles.update', $role->id)"
                >

                {!! view_render_event('bagisto.admin.settings.roles.edit.edit_form_controls.before') !!}

                <div class="flex justify-between items-center">
                    <p class="text-xl text-gray-800 dark:text-white font-bold">
                        @lang('admin::app.settings.roles.edit.title')
                    </p>

                    <div class="flex gap-x-2.5 items-center">
                        <!-- Cancel Button -->
                        <a
                            href="{{ route('admin.settings.roles.index') }}"
                            class="transparent-button hover:bg-gray-200 dark:hover:bg-gray-800 dark:text-white"
                        >
                            @lang('admin::app.settings.roles.edit.back-btn')
                        </a>

                        <!-- Save Button -->
                        <button
                            type="submit"
                            class="primary-button"
                        >
                            @lang('admin::app.settings.roles.edit.save-btn')
                        </button>
                    </div>
                </div>

                <!-- body content -->
                <div class="flex gap-2.5 mt-3.5 max-xl:flex-wrap">
                    <!-- Left sub-component -->
                    <div class="flex flex-col gap-2 flex-1 max-xl:flex-auto">

                        {!! view_render_event('bagisto.admin.settings.roles.edit.card.access-control.before') !!}

                        <!-- Access Control Input Fields -->
                        <div class="p-4 bg-white dark:bg-gray-900 rounded box-shadow">
                            <p class="text-base text-gray-800 dark:text-white font-semibold mb-4">
                                @lang('admin::app.settings.roles.edit.access-control')
                            </p>

                            <!-- Permission Type -->
                            <x-admin::form.control-group>
                                <x-admin::form.control-group.label>
                                    @lang('admin::app.settings.roles.edit.permissions')
                                </x-admin::form.control-group.label>

                                <x-admin::form.control-group.control
                                    type="select"
                                    id="permission_type"
                                    name="permission_type"
                                    v-model="permission_type"
                                    :label="trans('admin::app.settings.roles.edit.permissions')"
                                    :placeholder="trans('admin::app.settings.roles.edit.permissions')"
                                >
                                    <option value="custom">
                                        @lang('admin::app.settings.roles.edit.custom')
                                    </option>

                                    <option value="all">
                                        @lang('admin::app.settings.roles.edit.all')
                                    </option>
                                </x-admin::form.control-group.control>

                                <x-admin::form.control-group.error control-name="permission_type" />
                            </x-admin::form.control-group>
                            
                            <!-- Tree structure -->
                            <div v-if="permission_type == 'custom'">
                                <x-admin::tree.view
                                    input-type="checkbox"
                                    value-field="key"
                                    id-field="key"
                                    :items="json_encode($acl->items)"
                                    :value="json_encode($role->permissions)"
                                    :fallback-locale="config('app.fallback_locale')"
                                />
                            </div>
                        </div>

                        {!! view_render_event('bagisto.admin.settings.roles.edit.card.access-control.after') !!}

                    </div>

                    <!-- Right sub-component -->
                    <div class="flex flex-col gap-2 w-[360px] max-w-full max-sm:w-full">

                        {!! view_render_event('bagisto.admin.settings.roles.edit.card.accordion.general.before') !!}

                        <x-admin::accordion>
                            <x-slot:header>
                                <div class="flex items-center justify-between">
                                    <p class="p-2.5 text-base text-gray-800 dark:text-white font-semibold">
                                        @lang('admin::app.settings.roles.edit.general')
                                    </p>
                                </div>
                            </x-slot>

                            <x-slot:content>
                                <!-- Name -->
                                <x-admin::form.control-group>
                                    <x-admin::form.control-group.label class="required">
                                        @lang('admin::app.settings.roles.edit.name')
                                    </x-admin::form.control-group.label>

                                    <x-admin::form.control-group.control
                                        type="text"
                                        id="name"
                                        name="name"
                                        rules="required"
                                        value="{{ old('name') ?: $role->name }}"
                                        :label="trans('admin::app.settings.roles.edit.name')"
                                        :placeholder="trans('admin::app.settings.roles.edit.name')"
                                    />

                                    <x-admin::form.control-group.error control-name="name" />
                                </x-admin::form.control-group>

                                <!-- Description -->
                                <x-admin::form.control-group class="!mb-0">
                                    <x-admin::form.control-group.label class="required">
                                        @lang('admin::app.settings.roles.edit.description')
                                    </x-admin::form.control-group.label>

                                    <x-admin::form.control-group.control
                                        type="textarea"
                                        id="description"
                                        name="description"
                                        rules="required"
                                        value="{{ old('description') ?: $role->description }}"
                                        :label="trans('admin::app.settings.roles.edit.description')"
                                        :placeholder="trans('admin::app.settings.roles.edit.description')"
                                    />

                                    <x-admin::form.control-group.error control-name="description" />
                                </x-admin::form.control-group>
                            </x-slot>
                        </x-admin::accordion>

                        {!! view_render_event('bagisto.admin.settings.roles.edit.card.accordion.general.after') !!}

                    </div>
                </div>

                {!! view_render_event('bagisto.admin.settings.roles.edit.edit_form_controls.after') !!}

                </x-admin::form>
            </div>
        </script>

        <script type="module">
            app.component('v-edit-user-role', {
                template: '#v-edit-user-role-template',

                data() {
                    return {
                        permission_type: "{{ $role->permission_type }}"
                    };
                }
            })
        </script>
    @endPushOnce
</x-admin::layouts>
