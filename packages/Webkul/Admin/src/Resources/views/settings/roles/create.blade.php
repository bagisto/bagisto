<x-admin::layouts>

    {{-- Page Title --}}
    <x-slot:title>
        @lang('admin::app.settings.roles.create.title')
    </x-slot:title>
    
    {!! view_render_event('bagisto.admin.settings.roles.create.before') !!}

    {{-- Create Role for  --}}
    <v-create-user-role></v-create-user-role>

    {!! view_render_event('bagisto.admin.settings.roles.create.after') !!}

    @pushOnce('scripts')
        <script type="text/x-template" id="v-create-user-role-template">
            <div>
                <x-admin::form :action="route('admin.settings.roles.store')">
                    {!! view_render_event('admin.settings.roles.create.create_form_controls.before') !!}

                    <div class="flex justify-between items-center">
                        <p class="text-[20px] text-gray-800 dark:text-white font-bold">
                            @lang('admin::app.settings.roles.create.title')
                        </p>

                        <div class="flex gap-x-[10px] items-center">
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
                                class="py-[6px] px-[12px] bg-blue-600 border border-blue-700 rounded-[6px] text-gray-50 font-semibold cursor-pointer"
                            >
                                @lang('admin::app.settings.roles.create.save-btn')
                            </button>
                        </div>
                    </div>

                    <!-- body content -->
                    <div class="flex gap-[10px] mt-[14px] max-xl:flex-wrap">
                        <!-- Left sub-component -->
                        <div class=" flex flex-col gap-[8px] flex-1 max-xl:flex-auto">

                            {!! view_render_event('bagisto.admin.settings.roles.create.card.access_control.before') !!}

                            <!-- Access Control Input Fields -->
                            <div class="p-[16px] bg-white dark:bg-gray-900 rounded-[4px] box-shadow">
                                <p class="text-[16px] text-gray-800 dark:text-white font-semibold mb-[16px]">
                                    @lang('admin::app.settings.roles.create.access-control')
                                </p>

                                <div class="mb-[10px]">
                                    <x-admin::form.control-group class="mb-[10px]">
                                        <x-admin::form.control-group.label>
                                            @lang('admin::app.settings.roles.create.permissions')
                                        </x-admin::form.control-group.label>

                                        <x-admin::form.control-group.control
                                            type="select"
                                            name="permission_type" 
                                            id="permission_type"
                                            :label="trans('admin::app.settings.roles.create.permissions')"
                                            :placeholder="trans('admin::app.settings.roles.create.permissions')"
                                            v-model="permission_type"
                                        >
                                            <option value="custom">@lang('admin::app.settings.roles.create.custom')</option>
                                            <option value="all">@lang('admin::app.settings.roles.create.all')</option>
                                        </x-admin::form.control-group.control>

                                        <x-admin::form.control-group.error
                                            control-name="permission_type"
                                        >
                                        </x-admin::form.control-group.error>
                                    </x-admin::form.control-group>
                                </div>

                                <div 
                                    class="mb-[10px]"
                                    v-if="permission_type == 'custom'"
                                >
                                    <x-admin::tree.view
                                        input-type="checkbox"
                                        value-field="key"
                                        id-field="key"
                                        :items="json_encode($acl->items)"
                                        :fallback-locale="config('app.fallback_locale')"
                                    >
                                    </x-admin::tree.view>
                                </div>
                            </div>

                            {!! view_render_event('bagisto.admin.settings.roles.create.card.access_control.after') !!}

                        </div>
                        <!-- Right sub-component -->
                        <div class="flex flex-col gap-[8px] w-[360px] max-w-full max-sm:w-full">

                            {!! view_render_event('bagisto.admin.settings.roles.create.card.accordion.general.before') !!}

                            <x-admin::accordion>
                                <x-slot:header>
                                    <div class="flex items-center justify-between p-[6px]">
                                        <p class="p-[10px] text-gray-600 dark:text-gray-300 text-[16px] font-semibold">
                                            @lang('admin::app.settings.roles.create.general')
                                        </p>
                                    </div>
                                </x-slot:header>
                        
                                <x-slot:content>
                                    <div class="mb-[10px]">
                                        <x-admin::form.control-group class="mb-[10px]">
                                            <x-admin::form.control-group.label class="required">
                                                @lang('admin::app.settings.roles.create.name')
                                            </x-admin::form.control-group.label>
    
                                            <x-admin::form.control-group.control
                                                type="text"
                                                name="name"
                                                value="{{ old('name') }}"
                                                id="name"
                                                rules="required"
                                                :label="trans('admin::app.settings.roles.create.name')"
                                                :placeholder="trans('admin::app.settings.roles.create.name')"
                                            >
                                            </x-admin::form.control-group.control>
    
                                            <x-admin::form.control-group.error
                                                control-name="name"
                                            >
                                            </x-admin::form.control-group.error>
                                        </x-admin::form.control-group>
                                    
                                        <x-admin::form.control-group class="mb-[10px]">
                                            <x-admin::form.control-group.label class="required">
                                                @lang('admin::app.settings.roles.create.description')
                                            </x-admin::form.control-group.label>
    
                                            <x-admin::form.control-group.control
                                                type="textarea"
                                                name="description"
                                                :value="old('description')"
                                                id="description"
                                                rules="required"
                                                :label="trans('admin::app.settings.roles.create.description')"
                                                :placeholder="trans('admin::app.settings.roles.create.description')"
                                            >
                                            </x-admin::form.control-group.control>
    
                                            <x-admin::form.control-group.error
                                                control-name="description"
                                            >
                                            </x-admin::form.control-group.error>
                                        </x-admin::form.control-group>
                                    </div>
                                </x-slot:content>
                            </x-admin::accordion>

                            {!! view_render_event('bagisto.admin.settings.roles.create.card.accordion.general.after') !!}

                        </div>
                    </div>

                    {!! view_render_event('admin.settings.roles.create.create_form_controls.after') !!}

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