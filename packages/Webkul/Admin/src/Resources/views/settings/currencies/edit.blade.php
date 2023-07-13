<x-admin::layouts>
    <div class="flex-1 h-full max-w-full px-[16px] pt-[11px] pb-[22px] pl-[275px] max-lg:px-[16px]">
        <form 
            method="POST" 
            action="{{ route('admin.currencies.update', $currency->id) }}" 
            enctype="multipart/form-data"
        >
            @csrf

            @method('PUT')

            <div class="flex justify-between items-center">
                <p class="text-[20px] text-gray-800 font-bold">
                    @lang('Add Currency')
                </p>

                <div class="flex gap-x-[10px] items-center">
                    <button 
                        type="submit" 
                        class="text-gray-50 font-semibold px-[12px] py-[6px] bg-blue-600 border border-blue-700 rounded-[6px] cursor-pointer"
                    >
                        @lang('Save Currency')
                    </button>
                </div>
            </div>

            {!! view_render_event('bagisto.admin.settings.currencies.create.before') !!}

            <x-admin::accordion 
                :is-active="true"
                class="mt-[14px]"
            >
                <x-slot:header>
                    <p class="text-gray-600 text-[16px] p-[10px] font-semibold">
                        @lang('General')
                    </p>
                </x-slot:header>

                <x-slot:content>
                    <div class=" flex flex-col gap-[8px] flex-1">
                        <div class="p-[16px] ">
                            <div class="mb-[10px]">
                                <label 
                                    class="block text-[12px]  text-gray-800 font-medium leading-[24px]" 
                                    for="code"
                                >
                                    @lang('Code')
                                </label>
    
                                <input 
                                    type="hidden"
                                    name="code"
                                    value="{{ $currency->code }}"
                                />

                                <input 
                                    class="text-[14px] text-gray-600 appearance-none border rounded-[6px] w-full py-2 px-3 transition-all hover:border-gray-400" 
                                    type="text" 
                                    name="code"
                                    value="{{ old('code') ?? $currency->code }}"
                                    id="code"
                                    placeholder="@lang('Code')"
                                    disabled="disabled"
                                >
                            </div>
    
                            <div class="mb-[10px]">
                                <label 
                                    class="block text-[12px]  text-gray-800 font-medium leading-[24px]" 
                                    for="name"
                                >
                                    @lang('Name')
                                </label>
    
                                <input 
                                    class="text-[14px] text-gray-600 appearance-none border rounded-[6px] w-full py-2 px-3 transition-all hover:border-gray-400" 
                                    type="text" 
                                    name="name"
                                    value="{{ old('name') ?? $currency->name }}"
                                    id="name"
                                    placeholder="@lang('Name')"
                                >
                            </div>

                            <div class="mb-[10px]">
                                <label 
                                    class="block text-[12px]  text-gray-800 font-medium leading-[24px]" 
                                    for="symbol"
                                >
                                    @lang('Symbol')
                                </label>
    
                                <input 
                                    class="text-[14px] text-gray-600 appearance-none border rounded-[6px] w-full py-2 px-3 transition-all hover:border-gray-400" 
                                    type="text" 
                                    name="symbol"
                                    value="{{ old('symbol') ?? $currency->symbol }}"
                                    id="symbol"
                                    placeholder="@lang('Symbol')"
                                >
                            </div>

                            <div class="mb-[10px]">
                                <label 
                                    class="block text-[12px]  text-gray-800 font-medium leading-[24px]" 
                                    for="decimal"
                                >
                                    @lang('Decimal')
                                </label>
    
                                <input 
                                    class="text-[14px] text-gray-600 appearance-none border rounded-[6px] w-full py-2 px-3 transition-all hover:border-gray-400" 
                                    type="text" 
                                    name="decimal"
                                    value="{{ old('decimal') ?? $currency->decimal }}"
                                    id="decimal"
                                    placeholder="@lang('Decimal')"
                                >
                            </div>
                        </div>
                    </div>
                </x-slot:content>
            </x-admin::accordion>

            {!! view_render_event('bagisto.admin.settings.currencies.create.after') !!}
        </form>
    </div>
</x-admin::layouts>