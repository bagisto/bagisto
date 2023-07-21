<x-admin::layouts>
    <x-admin::form :action="route('admin.roles.store')">
        <div class="flex justify-between items-center">
            <p class="text-[20px] text-gray-800 font-bold">
                Create Role
            </p>

            <div class="flex gap-x-[10px] items-center">
                <a href="{{ route('admin.roles.index') }}">
                    <span class="text-gray-600 leading-[24px]">
                        @lang('cancel')
                    </span>
                </a>

                <button 
                    type="submit" 
                    class="py-[6px] px-[12px] bg-blue-600 border border-blue-700 rounded-[6px] text-gray-50 font-semibold cursor-pointer"
                >
                    @lang('save')
                </button>
            </div>
        </div>

        <div class="flex gap-[10px] mt-[14px] max-xl:flex-wrap">
            <div class=" flex flex-col gap-[8px] flex-1 max-xl:flex-auto">
                <div class="p-[16px] bg-white rounded-[4px] box-shadow">
                    <p class="text-[16px] text-gray-800 font-semibold mb-[16px]">
                        @lang('General')
                    </p>

                    <div class="mb-[10px]">
                        <x-admin::form.control-group class="mb-[10px]">
                            <x-admin::form.control-group.label>
                                @lang('Name' )
                            </x-admin::form.control-group.label>

                            <x-admin::form.control-group.control
                                type="text"
                                name="name"
                                value="{{ old('name') }}"
                                id="name"
                                rules="required"
                                :label="trans('name')"
                                :placeholder="trans('name')"
                            >
                            </x-admin::form.control-group.control>

                            <x-admin::form.control-group.error
                                control-name="name"
                            >
                            </x-admin::form.control-group.error>
                        </x-admin::form.control-group>
                    
                        <x-admin::form.control-group class="mb-[10px]">
                            <x-admin::form.control-group.label>
                                @lang('description')
                            </x-admin::form.control-group.label>

                            <x-admin::form.control-group.control
                                type="textarea"
                                name="description"
                                :value="old('description')"
                                id="description"
                                rules="required"
                                :label="trans('description')"
                                :placeholder="trans('description')"
                            >
                            </x-admin::form.control-group.control>

                            <x-admin::form.control-group.error
                                control-name="description"
                            >
                            </x-admin::form.control-group.error>
                        </x-admin::form.control-group>
                    </div>
                </div>

                <div class="p-[16px] bg-white rounded-[4px] box-shadow">
                    <p class="text-[16px] text-gray-800 font-semibold mb-[16px]">
                        @lang('Access Control')
                    </p>

                    <div class="mb-[10px]">
                        <x-admin::form.control-group class="mb-[10px]">
                            <x-admin::form.control-group.label>
                                @lang('permissions')
                            </x-admin::form.control-group.label>

                            <x-admin::form.control-group.control
                                type="select"
                                name="permission_type" 
                                id="permission_type"
                                :label="trans('permission')"
                                :placeholder="trans('permission')"
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

                    <div class="mb-[10px]">
                        <tree-view value-field="key" id-field="key" items='@json($acl->items)' fallback-locale="{{ config('app.fallback_locale') }}"></tree-view>
                    </div>
                    @{{permission_type}}

                </div>
            </div>
        </div>
    </x-admin::form>

    @pushOnce('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                let permissionTypeSelect = document.getElementById('permission_type');
                let treeContainer = document.querySelector('.tree-container');
        
                permissionTypeSelect.addEventListener('change', function (e) {
                    if (e.target.value === 'custom') {
                        treeContainer.classList.remove('hide');
                    } else {
                        treeContainer.classList.add('hide');
                    }
                });
            });
        </script>
    @endPushOnce
</x-admin::layouts>