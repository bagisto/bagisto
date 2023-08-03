<x-admin::layouts>

    {{-- Page Title --}}
    <x-slot:title>
        @lang('admin::app.users.roles.edit.title')
    </x-slot:title>
    
    {{-- Edit Role for  --}}
    <v-create-user-role></v-create-user-role>

    @pushOnce('scripts')
        <script type="text/x-template" id="v-create-user-role-template">
            <div>
                <x-admin::form 
                    method="PUT" 
                    :action="route('admin.roles.update', $role->id)"
                >
                    <div class="flex justify-between items-center">
                        <p class="text-[20px] text-gray-800 font-bold">
                            @lang('admin::app.users.roles.edit.title')
                        </p>

                        <div class="flex gap-x-[10px] items-center">
                            <a href="{{ route('admin.roles.index') }}">
                                <span class="text-gray-600 leading-[24px]">
                                    @lang('admin::app.users.roles.edit.cancel-btn')
                                </span>
                            </a>

                            <button 
                                type="submit" 
                                class="py-[6px] px-[12px] bg-blue-600 border border-blue-700 rounded-[6px] text-gray-50 font-semibold cursor-pointer"
                            >
                                @lang('admin::app.users.roles.edit.save-btn-title')
                            </button>
                        </div>
                    </div>

                    <div class="flex gap-[10px] mt-[14px] max-xl:flex-wrap">
                        <div class=" flex flex-col gap-[8px] flex-1 max-xl:flex-auto">
                            <div class="p-[16px] bg-white rounded-[4px] box-shadow">
                                <p class="text-[16px] text-gray-800 font-semibold mb-[16px]">
                                    @lang('admin::app.users.roles.edit.general')
                                </p>

                                <!-- General Input Fields -->
                                <div class="mb-[10px]">
                                    <x-admin::form.control-group class="mb-[10px]">
                                        <x-admin::form.control-group.label>
                                            @lang('admin::app.users.roles.edit.name')
                                        </x-admin::form.control-group.label>

                                        <x-admin::form.control-group.control
                                            type="text"
                                            name="name"
                                            value="{{ old('name') ?: $role->name }}"
                                            id="name"
                                            rules="required"
                                            :label="trans('admin::app.users.roles.edit.name')"
                                            :placeholder="trans('admin::app.users.roles.edit.name')"
                                        >
                                        </x-admin::form.control-group.control>

                                        <x-admin::form.control-group.error
                                            control-name="name"
                                        >
                                        </x-admin::form.control-group.error>
                                    </x-admin::form.control-group>
                                
                                    <x-admin::form.control-group class="mb-[10px]">
                                        <x-admin::form.control-group.label>
                                            @lang('admin::app.users.roles.edit.description')
                                        </x-admin::form.control-group.label>

                                        <x-admin::form.control-group.control
                                            type="textarea"
                                            name="description"
                                            value="{{ old('description') ?: $role->description }}"
                                            id="description"
                                            rules="required"
                                            :label="trans('admin::app.users.roles.edit.description')"
                                            :placeholder="trans('admin::app.users.roles.edit.description')"
                                        >
                                        </x-admin::form.control-group.control>

                                        <x-admin::form.control-group.error
                                            control-name="description"
                                        >
                                        </x-admin::form.control-group.error>
                                    </x-admin::form.control-group>
                                </div>
                            </div>

                            <!-- Access Control Input Fields -->
                            <div class="p-[16px] bg-white rounded-[4px] box-shadow">
                                <p class="text-[16px] text-gray-800 font-semibold mb-[16px]">
                                    @lang('admin::app.users.roles.edit.access-control')
                                </p>

                                <div class="mb-[10px]">
                                    <x-admin::form.control-group class="mb-[10px]">
                                        <x-admin::form.control-group.label>
                                            @lang('admin::app.users.roles.edit.permissions')
                                        </x-admin::form.control-group.label>
                                        <x-admin::form.control-group.control
                                            type="select"
                                            name="permission_type" 
                                            id="permission_type"
                                            :label="trans('admin::app.users.roles.edit.permissions')"
                                            :placeholder="trans('admin::app.users.roles.edit.permissions')"
                                            v-model="permission_type"
                                        >
                                            <option value="custom">Custom</option>
                                            <option value="all">All</option>
                                        </x-admin::form.control-group.control>

                                        <x-admin::form.control-group.error
                                            control-name="permission_type"
                                        >
                                        </x-admin::form.control-group.error>
                                    </x-admin::form.control-group>
                                </div>
                                
                                <!-- Tree structure -->
                                <div 
                                    class="mb-[10px]"
                                    v-if="permission_type == 'custom'"
                                >
                                    <v-tree-view 
                                        value-field="key"
                                        id-field="key"
                                        items='@json($acl->items)'
                                        value='@json($role->permissions)' 
                                        fallback-locale="{{ config('app.fallback_locale') }}"
                                    >
                                    </v-tree-view>
                                </div>

                            </div>
                        </div>
                    </div>
                </x-admin::form>
            </div>
        </script>

        <script type="module">
            app.component('v-create-user-role', {
                template: '#v-create-user-role-template',

                data() {
                    return {
                        permission_type: "{{ $role->permission_type }}"
                    };
                }
            })
        </script>

        {{-- v tree view --}}
        @include('admin::tree.view')

        {{-- v tree item --}}
        @include('admin::tree.item')

        {{-- v tree checkbox --}}
        @include('admin::tree.item')

        {{-- v tree checkbox --}}
        @include('admin::tree.checkbox')

        {{-- v tree radio --}}
        @include('admin::tree.radio')
    @endPushOnce
</x-admin::layouts>