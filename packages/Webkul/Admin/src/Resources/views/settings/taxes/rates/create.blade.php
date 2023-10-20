<x-admin::layouts>
    {{-- Title of the page --}}
    <x-slot:title>
        @lang('admin::app.settings.taxes.rates.create.title')
    </x-slot:title>

    <v-create-taxrate></v-create-taxrate>

    @pushOnce('scripts')
        <script
            type="text/x-template"
            id="v-create-taxrate-template"
        >

            {!! view_render_event('bagisto.admin.settings.taxes.rates.create.before') !!}

            <x-admin::form :action="route('admin.settings.taxes.rates.store')">

                {!! view_render_event('admin.settings.taxes.rates.create.create_form_controls.before') !!}

                <div class="flex justify-between items-center">
                    <p class="text-[20px] text-gray-800 dark:text-white font-bold">
                        @lang('admin::app.settings.taxes.rates.create.title')
                    </p>

                    <!-- Cancel Button -->
                    <div class="flex gap-x-[10px] items-center">
                        <a
                            href="{{ route('admin.settings.taxes.rates.index') }}"
                            class="transparent-button hover:bg-gray-200 dark:hover:bg-gray-800 dark:text-white"
                        >
                            @lang('admin::app.settings.taxes.rates.create.back-btn')
                        </a>

                        <!-- Save Button -->
                        <button 
                            type="submit" 
                            class="py-[6px] px-[12px] bg-blue-600 border border-blue-700 rounded-[6px] text-gray-50 font-semibold cursor-pointer"
                        >
                            @lang('admin::app.settings.taxes.rates.create.save-btn')
                        </button>
                    </div>
                </div>

                <!-- Tax Rates Informations -->
                <div class="flex gap-[10px] mt-[14px] max-xl:flex-wrap">
                    <!-- Left Component -->
                    <div class=" flex flex-col gap-[8px] flex-1 max-xl:flex-auto">
                        <div class="p-[16px] bg-white dark:bg-gray-900 rounded-[4px] box-shadow">
                            <p class="text-[16px] text-gray-800 dark:text-white font-semibold mb-[16px]">
                                @lang('admin::app.settings.taxes.rates.create.general')
                            </p>

                            <div class="mb-[10px]">
                                <!-- Identifier -->
                                <x-admin::form.control-group class="mb-[10px]">
                                    <x-admin::form.control-group.label class="required">
                                        @lang('admin::app.settings.taxes.rates.create.identifier')
                                    </x-admin::form.control-group.label>

                                    <x-admin::form.control-group.control
                                        type="text"
                                        name="identifier"
                                        :value="old('identifier')"
                                        rules="required"
                                        :label="trans('admin::app.settings.taxes.rates.create.identifier')"
                                        :placeholder="trans('admin::app.settings.taxes.rates.create.identifier')"
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
                                        @lang('admin::app.settings.taxes.rates.create.country')
                                    </x-admin::form.control-group.label>
                    
                                    <x-admin::form.control-group.control
                                        type="select"
                                        name="country"
                                        :value="old('country')"
                                        rules="required"
                                        :label="trans('admin::app.settings.taxes.rates.create.country')"
                                        :placeholder="trans('admin::app.settings.taxes.rates.create.country')"
                                        v-model="country"
                                    >
                                        <!-- Default Option -->
                                        <option value="">
                                            @lang('admin::app.settings.taxes.rates.create.select-country')
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
                                <template v-if="haveStates()">
                                    <x-admin::form.control-group class="mb-[10px]">
                                        <x-admin::form.control-group.label class="required">
                                            @lang('admin::app.settings.taxes.rates.create.state')
                                        </x-admin::form.control-group.label>
                        
                                        <x-admin::form.control-group.control
                                            type="select"
                                            name="state"
                                            :value="old('state')"
                                            :label="trans('admin::app.settings.taxes.rates.create.state')"
                                            :placeholder="trans('admin::app.settings.taxes.rates.create.state')"
                                            v-model="state"
                                        >
                                            <option v-for='(state, index) in countryStates[country]' :value="state.code">
                                                @{{ state.default_name }}
                                            </option>
                                        </x-admin::form.control-group.control>
                                    </x-admin::form.control-group>
                                </template>

                                <template v-if="! haveStates()">
                                    <x-admin::form.control-group class="mb-[10px]">
                                        <x-admin::form.control-group.label>
                                            @lang('admin::app.settings.taxes.rates.create.state')
                                        </x-admin::form.control-group.label>
                        
                                        <x-admin::form.control-group.control
                                            type="text"
                                            name="state"
                                            :value="old('state')"
                                            :label="trans('admin::app.settings.taxes.rates.create.state')"
                                            :placeholder="trans('admin::app.settings.taxes.rates.create.state')"
                                            v-model="state"
                                        >
                                        </x-admin::form.control-group.control>
                                    </x-admin::form.control-group>
                                </template>

                                <!-- Tax Rate -->
                                <x-admin::form.control-group class="mb-[10px]">
                                    <x-admin::form.control-group.label class="required">
                                        @lang('admin::app.settings.taxes.rates.create.tax-rate')
                                    </x-admin::form.control-group.label>

                                    <x-admin::form.control-group.control
                                        type="text"
                                        name="tax_rate"
                                        :value="old('tax_rate')"
                                        rules="required"
                                        :label="trans('admin::app.settings.taxes.rates.create.tax-rate')"
                                        :placeholder="trans('admin::app.settings.taxes.rates.create.tax-rate')"
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

                    <!-- Right Component -->
                    <div class="flex flex-col gap-[8px] w-[360px] max-w-full max-md:w-full">
                        <x-admin::accordion>
                            <x-slot:header>
                                <p class="p-[10px] text-gray-600 dark:text-gray-300 text-[16px] font-semibold">
                                    @lang('admin::app.settings.taxes.rates.create.settings')
                                </p>
                            </x-slot:header>
                        
                            <x-slot:content>
                                <!-- Enable Zip Range -->
                                <x-admin::form.control-group class="mb-[10px]">
                                    <x-admin::form.control-group.label>
                                        @lang('admin::app.settings.taxes.rates.create.is-zip')
                                    </x-admin::form.control-group.label>

                                    <x-admin::form.control-group.control
                                        type="switch"
                                        name="is_zip"
                                        :value="1"
                                        :label="trans('admin::app.settings.taxes.rates.create.is-zip')"
                                        :placeholder="trans('admin::app.settings.taxes.rates.create.is-zip')"
                                        v-model="is_zip"
                                    >
                                    </x-admin::form.control-group.control>

                                    <x-admin::form.control-group.error
                                        control-name="is_zip"
                                    >
                                    </x-admin::form.control-group.error>
                                </x-admin::form.control-group>

                                <!-- Zip Code -->
                                <x-admin::form.control-group 
                                    v-if="! is_zip"
                                    class="mb-[10px]" 
                                >
                                    <x-admin::form.control-group.label>
                                        @lang('admin::app.settings.taxes.rates.create.zip-code')
                                    </x-admin::form.control-group.label>

                                    <x-admin::form.control-group.control
                                        type="text"
                                        name="zip_code"
                                        :value="old('zip_code')"
                                        :label="trans('admin::app.settings.taxes.rates.create.zip-code')"
                                        :placeholder="trans('admin::app.settings.taxes.rates.create.zip-code')"
                                    >
                                    </x-admin::form.control-group.control>

                                    <x-admin::form.control-group.error
                                        control-name="zip_code"
                                    >
                                    </x-admin::form.control-group.error>
                                </x-admin::form.control-group>

                                <div v-if="is_zip">
                                    <!-- Zip From -->
                                    <x-admin::form.control-group class="mb-[10px]">
                                        <x-admin::form.control-group.label class="required">
                                            @lang('admin::app.settings.taxes.rates.create.zip-from')
                                        </x-admin::form.control-group.label>

                                        <x-admin::form.control-group.control
                                            type="text"
                                            name="zip_from"
                                            :value="old('zip_from')"
                                            rules="required"
                                            :label="trans('admin::app.settings.taxes.rates.create.zip-from')"
                                            :placeholder="trans('admin::app.settings.taxes.rates.create.zip-from')"
                                        >
                                        </x-admin::form.control-group.control>

                                        <x-admin::form.control-group.error
                                            control-name="zip_from"
                                        >
                                        </x-admin::form.control-group.error>
                                    </x-admin::form.control-group>

                                    <!-- Zip To -->
                                    <x-admin::form.control-group class="mb-[10px]">
                                        <x-admin::form.control-group.label class="required">
                                            @lang('admin::app.settings.taxes.rates.create.zip-to')
                                        </x-admin::form.control-group.label>

                                        <x-admin::form.control-group.control
                                            type="text"
                                            name="zip_to"
                                            :value="old('zip_to')"
                                            rules="required"
                                            :label="trans('admin::app.settings.taxes.rates.create.zip-to')"
                                            :placeholder="trans('admin::app.settings.taxes.rates.create.zip-to')"
                                        >
                                        </x-admin::form.control-group.control>

                                        <x-admin::form.control-group.error
                                            control-name="zip_to"
                                        >
                                        </x-admin::form.control-group.error>
                                    </x-admin::form.control-group>
                                </div>
                            </x-slot:content>
                        </x-admin::accordion>
                    </div>
                </div>

                {!! view_render_event('admin.settings.taxes.rates.create.create_form_controls.after') !!}

            </x-admin::form>

            {!! view_render_event('bagisto.admin.settings.taxes.rates.create.after') !!}

        </script>

        <script type="module">
            app.component('v-create-taxrate', {
                template: '#v-create-taxrate-template',

                data() {
                    return {
                        is_zip: false,

                        country: "{{ old('country')  }}",

                        state: "{{ old('state')  }}",

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