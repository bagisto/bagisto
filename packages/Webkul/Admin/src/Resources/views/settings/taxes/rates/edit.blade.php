<x-admin::layouts>
    <!-- Page Title -->
    <x-slot:title>
        @lang('admin::app.settings.taxes.rates.edit.title')
    </x-slot>

    {!! view_render_event('bagisto.admin.settings.taxes.rates.edit.before', ['taxRate' => $taxRate]) !!}

     <!-- Input Form -->
     <x-admin::form
        :action="route('admin.settings.taxes.rates.update', $taxRate->id)"
        method="PUT"
    >
        <div class="flex items-center justify-between gap-4 max-sm:flex-wrap">
            <p class="text-xl font-bold text-gray-800 dark:text-white">
                @lang('admin::app.settings.taxes.rates.edit.title')
            </p>

            <div class="flex items-center gap-x-2.5">
                <!-- Back Button -->
                <a
                    href="{{ route('admin.settings.taxes.rates.index') }}"
                    class="transparent-button hover:bg-gray-200 dark:text-white dark:hover:bg-gray-800"
                >
                    @lang('admin::app.settings.taxes.rates.edit.back-btn')
                </a>

                <!-- Save Button -->
                <button 
                    type="submit" 
                    class="primary-button"
                >
                    @lang('admin::app.settings.taxes.rates.edit.save-btn')
                </button>
            </div>
        </div>

        <v-edit-taxrate>
            <!-- Shimmer Effect -->
            <x-admin::shimmer.settings.taxes.rates />
        </v-edit-taxrate>
    </x-admin::form>

    {!! view_render_event('bagisto.admin.settings.taxes.rates.edit.after', ['taxRate' => $taxRate]) !!}

    @pushOnce('scripts')
        <script
            type="text/x-template"
            id="v-edit-taxrate-template"
        >

            {!! view_render_event('bagisto.admin.settings.taxes.rates.edit.edit_form_controls.before', ['taxRate' => $taxRate]) !!}

            <!-- Tax Rates Information's -->
            <div class="mt-3.5 flex gap-2.5 max-xl:flex-wrap">
                <!-- Left component -->
                <div class="flex flex-1 flex-col gap-2 max-xl:flex-auto">
                    <div class="box-shadow rounded bg-white p-4 dark:bg-gray-900">
                        <p class="mb-4 text-base font-semibold text-gray-800 dark:text-white">
                            @lang('admin::app.settings.taxes.rates.create.general')
                        </p>

                        <!-- Identifier -->
                        <x-admin::form.control-group>
                            <x-admin::form.control-group.label class="required">
                                @lang('admin::app.settings.taxes.rates.edit.identifier')
                            </x-admin::form.control-group.label>

                            <x-admin::form.control-group.control
                                type="text"
                                name="identifier"
                                rules="required"
                                value="{{ old('identifier') ?: $taxRate->identifier }}"
                                :label="trans('admin::app.settings.taxes.rates.edit.identifier')"
                                :placeholder="trans('admin::app.settings.taxes.rates.edit.identifier')"
                            />

                            <x-admin::form.control-group.error control-name="identifier" />
                        </x-admin::form.control-group>

                        <!-- Country -->
                        <x-admin::form.control-group>
                            <x-admin::form.control-group.label class="required">
                                @lang('admin::app.settings.taxes.rates.edit.country')
                            </x-admin::form.control-group.label>
            
                            <x-admin::form.control-group.control
                                type="select"
                                name="country"
                                rules="required"
                                value="{{ old('country') }}"
                                v-model="country"
                                :label="trans('admin::app.settings.taxes.rates.edit.country')"
                                :placeholder="trans('admin::app.settings.taxes.rates.edit.country')"
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
            
                            <x-admin::form.control-group.error control-name="country" />
                        </x-admin::form.control-group>
            
                        <!-- State -->
                        <x-admin::form.control-group>
                            <!-- Country Have States -->
                            <template v-if="haveStates()">
                                <x-admin::form.control-group.label>
                                    @lang('admin::app.settings.taxes.rates.edit.state')
                                </x-admin::form.control-group.label>

                                    <x-admin::form.control-group.control
                                        type="select"
                                        name="state"
                                        value="{{ old('state') }}"
                                        v-model="state"
                                        :label="trans('admin::app.settings.taxes.rates.edit.state')"
                                        :placeholder="trans('admin::app.settings.taxes.rates.edit.state')"
                                    >
                                        <option value="">
                                            @lang('admin::app.settings.taxes.rates.edit.select-state')
                                        </option>
                    
                                        <option 
                                            v-for='(state, index) in countryStates[country]' 
                                            :value="state.code"
                                        >
                                            @{{ state.default_name }}
                                        </option>
                                    </x-admin::form.control-group.control>

                                <x-admin::form.control-group.error control-name="state" />
                            </template>

                            <!-- Country Have not States -->
                            <template v-else>
                                <x-admin::form.control-group.label>
                                    @lang('admin::app.settings.taxes.rates.edit.state')
                                </x-admin::form.control-group.label>

                                <x-admin::form.control-group.control
                                    type="text"
                                    name="state"
                                    value="{{ old('state') }}"
                                    :label="trans('admin::app.settings.taxes.rates.edit.state')"
                                    :placeholder="trans('admin::app.settings.taxes.rates.edit.state')"
                                />

                                <x-admin::form.control-group.error control-name="state" />
                            </template>
                        </x-admin::form.control-group>

                        <!-- Tax Rate -->
                        <x-admin::form.control-group class="!mb-0">
                            <x-admin::form.control-group.label class="required">
                                @lang('admin::app.settings.taxes.rates.edit.tax-rate')
                            </x-admin::form.control-group.label>

                            <x-admin::form.control-group.control
                                type="text"
                                name="tax_rate"
                                rules="required|decimal|min:0|max:100"
                                value="{{ old('tax_rate') ?: $taxRate->tax_rate }}"
                                :label="trans('admin::app.settings.taxes.rates.edit.tax-rate')"
                                :placeholder="trans('admin::app.settings.taxes.rates.edit.tax-rate')"
                            />

                            <x-admin::form.control-group.error control-name="tax_rate" />
                        </x-admin::form.control-group>
                    </div>
                </div>
            
                <!-- Right sub-component -->
                <div class="flex w-[360px] max-w-full flex-col gap-2 max-sm:w-full">

                    {!! view_render_event('bagisto.admin.settings.taxes.rates.edit.card.accordion.basic_settings.before', ['taxRate' => $taxRate]) !!}

                    <!-- Basic Settings -->
                    <x-admin::accordion>
                        <x-slot:header>
                            <p class="p-2.5 text-base font-semibold text-gray-800 dark:text-white">
                                @lang('admin::app.settings.taxes.rates.edit.settings')
                            </p>
                        </x-slot>
                    
                        <x-slot:content>
                            @if ($taxRate->is_zip)
                                <!-- Is Zip -->
                                <x-admin::form.control-group.control
                                    type="hidden"
                                    name="is_zip"
                                    value="{{ $taxRate->is_zip }}"
                                />

                                <!-- Zip From -->
                                <x-admin::form.control-group>
                                    <x-admin::form.control-group.label class="required">
                                        @lang('admin::app.settings.taxes.rates.edit.zip-from')
                                    </x-admin::form.control-group.label>

                                    <x-admin::form.control-group.control
                                        type="text"
                                        name="zip_from"
                                        rules="required"
                                        value="{{ old('zip_form') ?: $taxRate->zip_from }}"
                                        :label="trans('admin::app.settings.taxes.rates.edit.zip-from')"
                                        :placeholder="trans('admin::app.settings.taxes.rates.edit.zip-from')"
                                    />

                                    <x-admin::form.control-group.error
                                        class="mt-1"
                                        control-name="zip_from"
                                    />
                                </x-admin::form.control-group>

                                <!-- Zip To -->
                                <x-admin::form.control-group>
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
                                    />

                                    <x-admin::form.control-group.error
                                        class="mt-1"
                                        control-name="zip_to"
                                    />
                                </x-admin::form.control-group>

                            @else
                                <!-- Zip Code -->
                                <x-admin::form.control-group class="!mb-0">
                                    <x-admin::form.control-group.label>
                                        @lang('admin::app.settings.taxes.rates.edit.zip-code')
                                    </x-admin::form.control-group.label>

                                    <x-admin::form.control-group.control
                                        type="text"
                                        name="zip_code"
                                        value="{{ old('zip_code') ?: $taxRate->zip_code }}"
                                        :label="trans('admin::app.settings.taxes.rates.edit.zip-code')"
                                        :placeholder="trans('admin::app.settings.taxes.rates.edit.zip-code')"
                                    />

                                    <x-admin::form.control-group.error control-name="zip_code" />
                                </x-admin::form.control-group>
                            @endif
                        </x-slot>
                    </x-admin::accordion>

                    {!! view_render_event('bagisto.admin.settings.taxes.rates.edit.card.accordion.basic_settings.after', ['taxRate' => $taxRate]) !!}

                </div>
            </div>

            {!! view_render_event('bagisto.admin.settings.taxes.rates.edit.edit_form_controls.after', ['taxRate' => $taxRate]) !!}

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