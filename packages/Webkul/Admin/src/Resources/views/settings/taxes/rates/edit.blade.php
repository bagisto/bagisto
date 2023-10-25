<x-admin::layouts>
    {{-- Page Title --}}
    <x-slot:title>
        @lang('admin::app.settings.taxes.rates.edit.title')
    </x-slot:title>

    <v-edit-taxrate></v-edit-taxrate>

    @pushOnce('scripts')
        <script type="text/x-template" id="v-edit-taxrate-template">

            {!! view_render_event('bagisto.admin.settings.taxes.rates.edit.before') !!}

            <!-- Input Form -->
            <x-admin::form
                :action="route('admin.settings.taxes.rates.update', $taxRate->id)"
                method="PUT"
            >

                {!! view_render_event('admin.settings.taxes.rates.edit.edit_form_controls.before') !!}

                <div class="flex gap-[16px] justify-between items-center max-sm:flex-wrap">
                    <p class="text-[20px] text-gray-800 dark:text-white font-bold">
                        @lang('admin::app.settings.taxes.rates.edit.title')
                    </p>
    
                    <div class="flex gap-x-[10px] items-center">
                        <!-- Cancel Button -->
                        <a
                            href="{{ route('admin.settings.taxes.rates.index') }}"
                            class="transparent-button hover:bg-gray-200 dark:hover:bg-gray-800 dark:text-white"
                        >
                            @lang('admin::app.settings.taxes.rates.edit.back-btn')
                        </a>
    
                        <!-- Save Button -->
                        <button 
                            type="submit" 
                            class="py-[6px] px-[12px] bg-blue-600 border border-blue-700 rounded-[6px] text-gray-50 font-semibold cursor-pointer"
                        >
                            @lang('admin::app.settings.taxes.rates.edit.save-btn')
                        </button>
                    </div>
                </div>
    
                <!-- Tax Rates Informations -->
                <div class="flex gap-[10px] mt-[14px] max-xl:flex-wrap">
                    <!-- Left component -->
                    <div class=" flex flex-col gap-[8px] flex-1 max-xl:flex-auto">
                        <div class="p-[16px] bg-white dark:bg-gray-900 rounded-[4px] box-shadow">
                            <div class="mb-[10px]">
                                <!-- Identifier -->
                                <x-admin::form.control-group class="mb-[10px]">
                                    <x-admin::form.control-group.label class="required">
                                        @lang('admin::app.settings.taxes.rates.edit.identifier')
                                    </x-admin::form.control-group.label>
    
                                    <x-admin::form.control-group.control
                                        type="text"
                                        name="identifier"
                                        value="{{ old('identifier') ?: $taxRate->identifier }}"
                                        rules="required"
                                        :label="trans('admin::app.settings.taxes.rates.edit.identifier')"
                                        :placeholder="trans('admin::app.settings.taxes.rates.edit.identifier')"
                                    >
                                    </x-admin::form.control-group.control>
    
                                    <x-admin::form.control-group.error
                                        control-name="identifier"
                                    >
                                    </x-admin::form.control-group.error>
                                </x-admin::form.control-group>
    
                                <!-- Country -->
                                <x-admin::form.control-group class="mb-[10px]">
                                    <x-admin::form.control-group.label class="required">
                                        @lang('admin::app.settings.taxes.rates.edit.country')
                                    </x-admin::form.control-group.label>
                    
                                    <x-admin::form.control-group.control
                                        type="select"
                                        name="country"
                                        value="{{ old('country') }}"
                                        rules="required"
                                        :label="trans('admin::app.settings.taxes.rates.edit.country')"
                                        :placeholder="trans('admin::app.settings.taxes.rates.edit.country')"
                                        v-model="country"
                                    >
                                        <option value="">
                                            @lang('admin::app.settings.taxes.rates.edit.select-country')
                                        </option>
                    
                                        @foreach (core()->countries() as $country)
                                            <option value="{{ $country->code }}">
                                                {{ $country->name }}
                                            </option>
                                        @endforeach
                                    </x-admin::form.control-group.control>
                    
                                    <x-admin::form.control-group.error
                                        control-name="country"
                                    >
                                    </x-admin::form.control-group.error>
                                </x-admin::form.control-group>
                    
                                <!-- State -->
                                <x-admin::form.control-group class="mb-[10px]">
                                    <x-admin::form.control-group.label class="required">
                                        @lang('admin::app.settings.taxes.rates.edit.state')
                                    </x-admin::form.control-group.label>
                                    
                                    <template v-if="haveStates()">
                                        <x-admin::form.control-group.control
                                            type="select"
                                            name="state"
                                            value="{{ old('state') }}"
                                            :label="trans('admin::app.settings.taxes.rates.edit.state')"
                                            :placeholder="trans('admin::app.settings.taxes.rates.edit.state')"
                                            v-model="state"
                                        >
                                            <option value="">
                                                @lang('admin::app.settings.taxes.rates.edit.select-state')
                                            </option>
                        
                                            <option v-for='(state, index) in countryStates[country]' :value="state.code">
                                                @{{ state.default_name }}
                                            </option>
                                        </x-admin::form.control-group.control>
                                    </template>

                                    <template v-else>
                                        <x-admin::form.control-group.control
                                            type="text"
                                            name="state"
                                            value="{{ old('state') }}"
                                            :label="trans('admin::app.settings.taxes.rates.edit.state')"
                                            :placeholder="trans('admin::app.settings.taxes.rates.edit.state')"
                                            v-model="state"
                                        >
                                            <option value="">
                                                @lang('admin::app.settings.taxes.rates.edit.select-state')
                                            </option>
                        
                                            <option v-for='(state, index) in countryStates[country]' :value="state.code">
                                                @{{ state.default_name }}
                                            </option>
                                        </x-admin::form.control-group.control>
                                    </template>
                                </x-admin::form.control-group>
    
                                <!-- Tax Rate -->
                                <x-admin::form.control-group class="mb-[10px]">
                                    <x-admin::form.control-group.label class="required">
                                        @lang('admin::app.settings.taxes.rates.edit.tax-rate')
                                    </x-admin::form.control-group.label>
    
                                    <x-admin::form.control-group.control
                                        type="text"
                                        name="tax_rate"
                                        value="{{ old('tax_rate') ?: $taxRate->tax_rate }}"
                                        rules="required"
                                        :label="trans('admin::app.settings.taxes.rates.edit.tax-rate')"
                                        :placeholder="trans('admin::app.settings.taxes.rates.edit.tax-rate')"
                                    >
                                    </x-admin::form.control-group.control>
    
                                    <x-admin::form.control-group.error
                                        control-name="tax_rate"
                                    >
                                    </x-admin::form.control-group.error>
                                </x-admin::form.control-group>
                            </div>
                        </div>
                    </div>
                
                    <!-- Right sub-component -->
                    <div class="flex flex-col gap-[8px] w-[360px] max-w-full max-sm:w-full">

                        {!! view_render_event('admin.settings.taxes.rates.edit.card.accordion.basic_settings.before') !!}

                        <!-- Basic Settings -->
                        <x-admin::accordion>
                            <x-slot:header>
                                <p class="p-[10px] text-gray-600 dark:text-gray-300 text-[16px] font-semibold">
                                    @lang('admin::app.settings.taxes.rates.edit.basic-settings')
                                </p>
                            </x-slot:header>
                        
                            <x-slot:content>
                                @if ($taxRate->is_zip)
                                    <!-- Is Zip -->
                                    <x-admin::form.control-group.control
                                        type="hidden"
                                        name="is_zip"
                                        value="{{ $taxRate->is_zip }}"
                                    >
                                    </x-admin::form.control-group.control>
    
                                    <!-- Zip From -->
                                    <x-admin::form.control-group class="mb-[10px]">
                                        <x-admin::form.control-group.label class="required">
                                            @lang('admin::app.settings.taxes.rates.edit.zip-from')
                                        </x-admin::form.control-group.label>
    
                                        <x-admin::form.control-group.control
                                            type="text"
                                            name="zip_from"
                                            value="{{ old('zip_form') ?: $taxRate->zip_from }}"
                                            rules="required"
                                            :label="trans('admin::app.settings.taxes.rates.edit.zip-from')"
                                            :placeholder="trans('admin::app.settings.taxes.rates.edit.zip-from')"
                                        >
                                        </x-admin::form.control-group.control>
    
                                        <x-admin::form.control-group.error
                                            class="mt-1"
                                            control-name="zip_from"
                                        >
                                        </x-admin::form.control-group.error>
                                    </x-admin::form.control-group>
    
                                    <!-- Zip To -->
                                    <x-admin::form.control-group class="mb-[10px]">
                                        <x-admin::form.control-group.label class="required">
                                            @lang('admin::app.settings.taxes.rates.edit.zip-to')
                                        </x-admin::form.control-group.label>
    
                                        <x-admin::form.control-group.control
                                            type="text"
                                            name="zip_to"
                                            value="{{ old('zip_form') ?: $taxRate->zip_to }}"
                                            rules="required"
                                            :label="trans('admin::app.settings.taxes.rates.edit.zip-to')"
                                            :placeholder="trans('admin::app.settings.taxes.rates.edit.zip-to')"
                                        >
                                        </x-admin::form.control-group.control>
    
                                        <x-admin::form.control-group.error
                                            class="mt-1"
                                            control-name="zip_to"
                                        >
                                        </x-admin::form.control-group.error>
                                    </x-admin::form.control-group>
    
                                @else
                                    <!-- Zip Code -->
                                    <x-admin::form.control-group class="mb-[10px]">
                                        <x-admin::form.control-group.label>
                                            @lang('admin::app.settings.taxes.rates.edit.zip-code')
                                        </x-admin::form.control-group.label>
    
                                        <x-admin::form.control-group.control
                                            type="text"
                                            name="zip_code"
                                            value="{{ old('zip_code') ?: $taxRate->zip_code }}"
                                            :label="trans('admin::app.settings.taxes.rates.edit.zip-code')"
                                            :placeholder="trans('admin::app.settings.taxes.rates.edit.zip-code')"
                                        >
                                        </x-admin::form.control-group.control>
    
                                        <x-admin::form.control-group.error
                                            control-name="zip_code"
                                        >
                                        </x-admin::form.control-group.error>
                                    </x-admin::form.control-group>
                                @endif
                            </x-slot:content>
                        </x-admin::accordion>

                        {!! view_render_event('admin.settings.taxes.rates.edit.card.accordion.basic_settings.after') !!}

                    </div>
                </div>

                {!! view_render_event('admin.settings.taxes.rates.edit.edit_form_controls.after') !!}

            </x-admin::form>

            {!! view_render_event('bagisto.admin.settings.taxes.rates.edit.after') !!}

        </script>

        <script type="module">
            app.component('v-edit-taxrate', {
                template: '#v-edit-taxrate-template',

                data() {
                    return {
                        country: "{{ old('country') ?? $taxRate->country  }}",

                        state: "{{ old('state') ?? $taxRate->state  }}",

                        countryStates: @json(core()->groupedStatesByCountries())
                    }
                },

                methods: {
                    haveStates: function () {
                        /*
                        * The double negation operator is used to convert the value to a boolean. 
                        * It ensures that the final result is a boolean value, 
                        * true if the array has a length greater than 0, and otherwise false.
                        */
                        return !!this.countryStates[this.country]?.length;

                    },
                }
            });
        </script>
    @endPushOnce
</x-admin::layouts>