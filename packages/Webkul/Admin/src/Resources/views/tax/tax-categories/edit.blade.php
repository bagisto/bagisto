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
                    <span class="px-[12px] py-[6px] border-[2px] border-transparent rounded-[6px] text-gray-600 font-semibold whitespace-nowrap transition-all hover:bg-gray-100 cursor-pointer">
                        @lang('admin::app.settings.taxes.tax-categories.edit.back-btn')
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
                        <x-admin::form.control-group.label class="required">
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
                        <x-admin::form.control-group.label class="required">
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
                        <x-admin::form.control-group.label class="required">
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
               
                       @inject('taxRates', 'Webkul\Tax\Repositories\TaxRateRepository')

                       @php 
                            $selectedOptions = old('taxrates') ?: $taxCategory->tax_rates()->pluck('tax_rates.id')->toArray();
                        @endphp

                        <!-- Select Tax Rates -->
                        <p class="block leading-[24px] text-gray-800 font-medium">
                            @lang('admin::app.settings.taxes.tax-categories.edit.select-tax-rates')
                        </p>

                        @foreach ($taxRates->all() as $taxRate)
                            <x-admin::form.control-group class="flex gap-[10px] !mb-0 p-[6px]">
                                <x-admin::form.control-group.control
                                    type="checkbox"
                                    name="taxrates[]"
                                    :value="$taxRate->id"
                                    :id="'taxrates_' . $taxRate->id"
                                    :for="'taxrates_' . $taxRate->id"
                                    rules="required"
                                    :label="trans('admin::app.settings.taxes.tax-categories.edit.select-tax-rates')"
                                    :checked="in_array($taxRate->id, $selectedOptions)"
                                >
                                </x-admin::form.control-group.control>
                                    
                                <x-admin::form.control-group.label 
                                    :for="'taxrates_' . $taxRate->id"
                                    class="!text-[14px] !text-gray-600 font-semibold cursor-pointer"
                                >
                                    {{ $taxRate['identifier'] }}
                                </x-admin::form.control-group.label>
                            </x-admin::form.control-group>
                        @endforeach 

                        <x-admin::form.control-group.error
                            control-name="taxrates[]"
                        >
                        </x-admin::form.control-group.error>
                    </x-slot:content>
                </x-admin::accordion>
            </div>
        </div>
    </x-admin::form>
</x-admin::layouts>