<x-admin::layouts>
    {{-- Page Title --}}
    <x-slot:title>
        @lang('admin::app.settings.taxes.tax-categories.edit.title')
    </x-slot:title>

    <!-- Input Form -->
    <x-admin::form
        :action="route('admin.tax_categories.update', $taxCategory->id)"
        method="PUT"
    >
        <div class="flex gap-[16px] justify-between items-center max-sm:flex-wrap">
            <p class="text-[20px] text-gray-800 font-bold">
                @lang('admin::app.settings.taxes.tax-categories.edit.title')
            </p>

            <div class="flex gap-x-[10px] items-center">
                <!-- Cancel Button -->
                <a href="{{ route('admin.tax_categories.index') }}">
                    <span class="text-gray-600 leading-[24px]">
                        @lang('admin::app.settings.taxes.tax-categories.edit.cancel-btn')
                    </span>
                </a>

                <!-- Save Button -->
                <button 
                    type="submit" 
                    class="py-[6px] px-[12px] bg-blue-600 border border-blue-700 rounded-[6px] text-gray-50 font-semibold cursor-pointer"
                >
                    @lang('admin::app.settings.taxes.tax-categories.edit.save-btn')
                </button>
            </div>
        </div>

        <!-- Tax Rates Informations -->
        <div class="flex gap-[10px] mt-[14px] max-xl:flex-wrap">
            <!-- Left component -->
            <div class=" flex flex-col gap-[8px] flex-1 max-xl:flex-auto">
                <div class="p-[16px] bg-white rounded-[4px] box-shadow">
                        <!-- Code -->
                        <x-admin::form.control-group class="mb-[10px]">
                        <x-admin::form.control-group.label>
                            @lang('admin::app.settings.taxes.tax-categories.edit.code')
                        </x-admin::form.control-group.label>

                        <x-admin::form.control-group.control
                            type="text"
                            name="code"
                            value="{{ old('code') ?: $taxCategory->code }}"
                            id="code"
                            rules="required"
                            :label="trans('admin::app.settings.taxes.tax-categories.edit.code')"
                            :placeholder="trans('admin::app.settings.taxes.tax-categories.edit.code')"
                        >
                        </x-admin::form.control-group.control>

                        <x-admin::form.control-group.error
                            control-name="code"
                        >
                        </x-admin::form.control-group.error>
                    </x-admin::form.control-group>

                    <!-- Name -->
                    <x-admin::form.control-group class="mb-[10px]">
                        <x-admin::form.control-group.label>
                            @lang('admin::app.settings.taxes.tax-categories.edit.name')
                        </x-admin::form.control-group.label>

                        <x-admin::form.control-group.control
                            type="text"
                            name="name"
                            value="{{ old('name') ?: $taxCategory->name }}"
                            id="name"
                            rules="required"
                            :label="trans('admin::app.settings.taxes.tax-categories.edit.name')"
                            :placeholder="trans('admin::app.settings.taxes.tax-categories.edit.name')"
                        >
                        </x-admin::form.control-group.control>

                        <x-admin::form.control-group.error
                            control-name="name"
                        >
                        </x-admin::form.control-group.error>
                    </x-admin::form.control-group>

                    <!-- Description -->
                    <x-admin::form.control-group class="mb-[10px]">
                        <x-admin::form.control-group.label>
                            @lang('admin::app.settings.taxes.tax-categories.edit.description')
                        </x-admin::form.control-group.label>

                        <x-admin::form.control-group.control
                            type="textarea"
                            name="description"
                            :value="old('description')"
                            value="{{ old('description') ?: $taxCategory->description }}"
                            id="description"
                            rules="required"
                            :label="trans('admin::app.settings.taxes.tax-categories.edit.description')"
                            :placeholder="trans('admin::app.settings.taxes.tax-categories.edit.description')"
                        >
                        </x-admin::form.control-group.control>

                        <x-admin::form.control-group.error
                            control-name="description"
                        >
                        </x-admin::form.control-group.error>
                    </x-admin::form.control-group>
                </div>
            </div>
        
            <!-- Right sub-component -->
            <div class="flex flex-col gap-[8px] w-[360px] max-w-full max-sm:w-full">
                <!-- Basic Settings -->
                <x-admin::accordion>
                    <x-slot:header>
                        <p class="p-[10px] text-gray-600 text-[16px] font-semibold">
                            @lang('admin::app.settings.taxes.tax-rates.edit.basic-settings')
                        </p>
                    </x-slot:header>
                
                    <x-slot:content>
                        @php 
                            $selectedOptions = old('taxrates') ?: $taxCategory->tax_rates()->pluck('tax_rates.id')->toArray();
                        @endphp

                        @inject('taxRates', 'Webkul\Tax\Repositories\TaxRateRepository')

                        <!-- Select Tax Rates -->
                        <x-admin::form.control-group>
                            <x-admin::form.control-group.label>
                                @lang('admin::app.settings.taxes.tax-categories.edit.select-tax-rates')
                            </x-admin::form.control-group.label>

                            <x-admin::form.control-group.control
                                type="select"
                                name="taxrates[]"
                                id="taxrates"
                                :label="trans('admin::app.settings.taxes.tax-categories.edit.select-tax-rates')"
                                :placeholder="trans('admin::app.settings.taxes.tax-categories.edit.select-tax-rates')"
                                multiple
                            >
                                @foreach ($taxRates->all() as $taxRate)
                                    <option
                                        value="{{ $taxRate->id }}"
                                        {{ in_array($taxRate->id, $selectedOptions) ? 'selected' : '' }}
                                    >
                                        {{ $taxRate['identifier'] }}
                                    </option>
                                @endforeach
                            </x-admin::form.control-group.control>

                            <x-admin::form.control-group.error
                                control-name="taxrates"
                            >
                            </x-admin::form.control-group.error>
                        </x-admin::form.control-group>
                    </x-slot:content>
                </x-admin::accordion>
            </div>
        </div>
    </x-admin::form>
</x-admin::layouts>