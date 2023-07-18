<x-admin::layouts>
    <x-shop::form 
        :action="route('admin.currencies.update', $currency->id)"
        enctype="multipart/form-data"
    >
        @method('PUT')

        <div class="flex gap-[16px] justify-between items-center max-sm:flex-wrap">
            <p class="text-[20px] text-gray-800 font-bold">
                @lang('Edit Currency')
            </p>

            <div class="flex gap-x-[10px] items-center">
                <button 
                    type="submit"
                    class="px-[12px] py-[6px] bg-blue-600 border border-blue-700 rounded-[6px] text-gray-50 font-semibold cursor-pointer"
                >
                    @lang('Save Currency')
                </button>
            </div>
        </div>

        <div class="flex gap-[10px] mt-[14px] max-xl:flex-wrap">
            <div class="flex flex-col gap-[8px] flex-1 max-xl:flex-auto">
                <div class="p-[16px] bg-white rounded-[4px] box-shadow">

                    {!! view_render_event('bagisto.admin.settings.currencies.edit.before') !!}

                    <p class="text-[16px] text-gray-800 font-semibold mb-[16px]">
                        @lang('General')
                    </p>

                    <x-admin::form.control-group.control
                        type="hidden"
                        name="code"
                        :value="old('code') ?? $currency->code"
                    >
                    </x-admin::form.control-group.control>

                    <x-admin::form.control-group class="mb-[10px]">
                        <x-admin::form.control-group.label>
                            Code
                        </x-admin::form.control-group.label>

                        <x-admin::form.control-group.control
                            type="text"
                            name="code"
                            :value="old('code') ?? $currency->code"
                            id="code"
                            label="Code"
                            :placeholder="trans('Code')"
                            disabled="disabled"
                        >
                        </x-admin::form.control-group.control>

                        <x-admin::form.control-group.error
                            control-name="code"
                        >
                        </x-admin::form.control-group.error>
                    </x-admin::form.control-group>

                    <x-admin::form.control-group class="mb-[10px]">
                        <x-admin::form.control-group.label>
                            Name
                        </x-admin::form.control-group.label>

                        <x-admin::form.control-group.control
                            type="text"
                            name="name"
                            :value="old('name') ?? $currency->name"
                            id="name"
                            rules="required"
                            label="name"
                            :placeholder="trans('Name')"
                        >
                        </x-admin::form.control-group.control>

                        <x-admin::form.control-group.error
                            control-name="name"
                        >
                        </x-admin::form.control-group.error>
                    </x-admin::form.control-group>

                    <x-admin::form.control-group class="mb-[10px]">
                        <x-admin::form.control-group.label>
                            Symbol
                        </x-admin::form.control-group.label>

                        <x-admin::form.control-group.control
                            type="text"
                            name="symbol"
                            :value="old('symbol') ?? $currency->symbol"
                            id="symbol"
                            label="symbol"
                            :placeholder="trans('Symbol')"
                        >
                        </x-admin::form.control-group.control>

                        <x-admin::form.control-group.error
                            control-name="symbol"
                        >
                        </x-admin::form.control-group.error>
                    </x-admin::form.control-group>

                    <x-admin::form.control-group class="mb-[10px]">
                        <x-admin::form.control-group.label>
                            Decimal
                        </x-admin::form.control-group.label>

                        <x-admin::form.control-group.control
                            type="text"
                            name="decimal"
                            :value="old('decimal') ?? $currency->decimal"
                            id="decimal"
                            label="decimal"
                            :placeholder="trans('Decimal')"
                        >
                        </x-admin::form.control-group.control>

                        <x-admin::form.control-group.error
                            control-name="decimal"
                        >
                        </x-admin::form.control-group.error>
                    </x-admin::form.control-group>

                    {!! view_render_event('bagisto.admin.settings.currencies.edit.after') !!}
                </div>
            </div>
        </div>
    </x-admin::form>
</x-admin::layouts>