<x-admin::layouts>
    {{-- Title of the page --}}
    <x-slot:title>
        @lang('admin::app.settings.exchange-rates.edit.title')
    </x-slot:title>

    <x-admin::form 
        :action="route('admin.exchange_rates.update', $exchangeRate->id)"
        enctype="multipart/form-data"
        method="PUT"
    >
        <div class="flex gap-[16px] justify-between items-center max-sm:flex-wrap">
            <p class="text-[20px] text-gray-800 font-bold">
                @lang('admin::app.settings.exchange-rates.edit.title')
            </p>

            <div class="flex gap-x-[10px] items-center">
                {{-- Cancel Button --}}
                <a href="{{ route('admin.exchange_rates.index') }}">
                    <span class="px-[12px] py-[6px] border-[2px] border-transparent rounded-[6px] text-gray-600 font-semibold whitespace-nowrap transition-all hover:bg-gray-100 cursor-pointer">
                        @lang('admin::app.settings.exchange-rates.edit.back-btn')
                    </span>
                </a>
                    
                {{-- Save Button --}}
                <div class="flex gap-x-[10px] items-center">
                    <button 
                        type="submit"
                        class="px-[12px] py-[6px] bg-blue-600 border border-blue-700 rounded-[6px] text-gray-50 font-semibold cursor-pointer"
                    >
                        @lang('admin::app.settings.exchange-rates.edit.save-btn')
                    </button>
                </div>
            </div>
        </div>

        {!! view_render_event('bagisto.admin.settings.exchangerate.edit.before') !!}
    
        {{-- Full Pannel --}}
        <div class="flex gap-[10px] mt-[14px] max-xl:flex-wrap">
            <div class="flex flex-col gap-[8px] flex-1 max-xl:flex-auto">
                <div class="p-[16px] bg-white box-shadow rounded-[4px]">
                    {{-- Base Currency Code --}}
                    <x-admin::form.control-group class="mb-[10px]">
                        <x-admin::form.control-group.label>
                            @lang('admin::app.settings.exchange-rates.edit.source-currency')
                        </x-admin::form.control-group.label>
                        
                        <x-admin::form.control-group.control
                            type="text"
                            name="getBaseCurrencyCode"
                            :value="core()->getBaseCurrencyCode()"
                            disabled
                        >
                        </x-admin::form.control-group.control>
                    </x-admin::form.control-group>
    
                    {{-- Target Currencies --}}
                    <x-admin::form.control-group class="mb-[10px]">
                        <x-admin::form.control-group.label class="required">
                            @lang('admin::app.settings.exchange-rates.edit.target-currency')
                        </x-admin::form.control-group.label>
                        
                        <x-admin::form.control-group.control
                            type="select"
                            name="target_currency"
                            rules="required"
                            label="{{ trans('admin::app.settings.exchange-rates.edit.target-currency') }}"
                        >
                            @foreach ($currencies as $currency)
                                @if (is_null($currency->exchange_rate))
                                    <option
                                        value="{{ $currency->id }}"
                                        {{ $exchangeRate->target_currency == $currency->id ? 'selected' : '' }}
                                    >
                                        {{ $currency->name }}
                                    </option>
                                @endif
                            @endforeach
                        </x-admin::form.control-group.control>
    
                        <x-admin::form.control-group.error
                            control-name="target_currency"
                        >
                        </x-admin::form.control-group.error>
                    </x-admin::form.control-group>
    
                    {{-- Rate --}}
                    <x-admin::form.control-group class="mb-[10px]">
                        <x-admin::form.control-group.label class="required">
                            @lang('admin::app.settings.exchange-rates.edit.rate')
                        </x-admin::form.control-group.label>
    
                        <x-admin::form.control-group.control
                            type="text"
                            name="rate"
                            id="rate"
                            value="{{ old('rate') ?: $exchangeRate->rate }}"
                            rules="required"
                            label="{{ trans('admin::app.settings.exchange-rates.edit.rate') }}"
                            placeholder="{{ trans('admin::app.settings.exchange-rates.edit.rate') }}"
                        >
                        </x-admin::form.control-group.control>
    
                        <x-admin::form.control-group.error
                            control-name="rate"
                        >
                        </x-admin::form.control-group.error>
                    </x-admin::form.control-group>
                </div>
            </div>
        </div>

        {!! view_render_event('bagisto.admin.settings.exchangerate.edit.after') !!}
    </x-admin::form>
</x-admin::layouts>