<x-admin::layouts>
    <!-- Title of the page -->
    <x-slot:title>
        @lang('admin::app.customers.groups.edit.title')
    </x-slot:title>

    {{-- Input Form --}}
    <x-admin::form 
        :action="route('admin.groups.update', $group->id)"
        method="PUT"
    >
        <div class="flex justify-between items-center">
            <p class="text-[20px] text-gray-800 font-bold">
                @lang('admin::app.customers.groups.edit.title')
            </p>

            <div class="flex gap-x-[10px] items-center">
                {{-- Cancel Button --}}
                <a href="{{ route('admin.groups.index') }}">
                    <span class="px-[12px] py-[6px] border-[2px] border-transparent rounded-[6px] text-gray-600 font-semibold whitespace-nowrap transition-all hover:bg-gray-100 cursor-pointer">
                        @lang('admin::app.customers.groups.edit.cancel-btn')
                    </span>
                </a>

                {{-- Save Button --}}
                <button 
                    type="submit" 
                    class="py-[6px] px-[12px] bg-blue-600 border border-blue-700 rounded-[6px] text-gray-50 font-semibold cursor-pointer"
                >
                    @lang('admin::app.customers.groups.edit.save-btn')
                </button>
            </div>
        </div>

        {{-- Informations --}}
        <div class="flex gap-[10px] mt-[28px] mb-2">
            <div class="flex flex-col gap-[8px] flex-1">
                {{-- General Section --}}
                <div class="p-[16px] bg-white rounded-[4px] box-shadow">
                    <p class="mb-[16px] text-[16px] text-gray-800 font-semibold">
                        @lang('admin::app.customers.groups.edit.general')
                    </p>

                    <div class="mb-[10px]">
                        {{-- Code --}}
                        <x-admin::form.control-group class="mb-4">
                            <x-admin::form.control-group.label class="required">
                                @lang('admin::app.customers.groups.edit.code')
                            </x-admin::form.control-group.label>

                            <x-admin::form.control-group.control
                                type="text"
                                name="code"
                                value="{{ old('code') ?: $group->code }}"
                                rules="required"
                                class="mb-1"
                                label="{{ trans('admin::app.customers.groups.edit.code') }}"
                                placeholder="{{ trans('admin::app.customers.groups.edit.code') }}"
                                disabled
                            >
                            </x-admin::form.control-group.control>

                            <x-admin::form.control-group.control
                                type="hidden"
                                name="code"
                                value="{{ $group->code }}"
                            >
                            </x-admin::form.control-group.control>

                            <x-admin::form.control-group.error 
                                control-name="code"
                            >
                            </x-admin::form.control-group.error>
                        </x-admin::form.control-group>

                        {{-- Name --}}
                        <x-admin::form.control-group class="mb-4">
                            <x-admin::form.control-group.label class="required">
                                @lang('admin::app.customers.groups.edit.name')
                            </x-admin::form.control-group.label>

                            <x-admin::form.control-group.control
                                type="text"
                                name="name"
                                value="{{ old('name') ?: $group->name }}"
                                rules="required"
                                class="mb-1"
                                label="{{ trans('admin::app.customers.groups.edit.name') }}"
                                placeholder="{{ trans('admin::app.customers.groups.edit.name') }}"
                            >
                            </x-admin::form.control-group.control>

                            <x-admin::form.control-group.error
                                control-name="name"
                            >
                            </x-admin::form.control-group.error>
                        </x-admin::form.control-group>
                    </div>
                </div>
            </div>
        </div>
    </x-admin::form>
</x-admin::layouts>