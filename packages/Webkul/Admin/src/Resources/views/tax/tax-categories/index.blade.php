<x-admin::layouts>
    {{-- Page Title --}}
    <x-slot:title>
        @lang('admin::app.settings.taxes.tax-categories.index.title')
    </x-slot:title>

    <div class="flex justify-between items-center">
        <p class="text-[20px] text-gray-800 font-bold">
            @lang('admin::app.settings.taxes.tax-categories.index.title')
        </p>
        
        <div class="flex gap-x-[10px] items-center">
            <div class="flex gap-x-[10px] items-center">
                {{-- Create Tax Category Vue Component --}}
                <v-create-tax-category></v-create-tax-category>
            </div>
        </div>
    </div>
    
    {{-- datagrid will be here --}}

    @pushOnce('scripts')
        <script type="text/x-template" id="v-create-tax-category-template">
            <div>
                <!-- Create Tax Category Button -->
                @if (bouncer()->hasPermission('settings.taxes.tax-categories.create'))
                    <button
                        type="button"
                        class="px-[12px] py-[6px] bg-blue-600 border border-blue-700 rounded-[6px] text-gray-50 font-semibold cursor-pointer"
                        @click="$refs.taxCategory.toggle()"
                    >
                        @lang('admin::app.settings.taxes.tax-categories.create.add-title')
                    </button>
                @endif

                <!-- Model Form -->
                <x-admin::form
                    v-slot="{ meta, errors, handleSubmit }"
                    as="div"
                >
                    <form @submit="handleSubmit($event, store)">
                        <x-admin::modal ref="taxCategory">
                            <x-slot:header>
                                <p class="text-[18px] text-gray-800 font-bold">
                                    @lang('admin::app.settings.taxes.tax-categories.create.add-title')
                                </p>
                            </x-slot:header>

                            <x-slot:content>
                                <div class="px-[16px] py-[10px] border-b-[1px] border-gray-300">
                                    <!-- Code -->
                                    <x-admin::form.control-group class="mb-[10px]">
                                        <x-admin::form.control-group.label>
                                            @lang('admin::app.settings.taxes.tax-categories.create.code')
                                        </x-admin::form.control-group.label>

                                        <x-admin::form.control-group.control
                                            type="text"
                                            name="code"
                                            :value="old('code')"
                                            id="code"
                                            rules="required"
                                            :label="trans('admin::app.settings.taxes.tax-categories.create.code')"
                                            :placeholder="trans('admin::app.settings.taxes.tax-categories.create.code')"
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
                                            @lang('admin::app.settings.taxes.tax-categories.create.name')
                                        </x-admin::form.control-group.label>

                                        <x-admin::form.control-group.control
                                            type="text"
                                            name="name"
                                            :value="old('name')"
                                            id="name"
                                            rules="required"
                                            :label="trans('admin::app.settings.taxes.tax-categories.create.name')"
                                            :placeholder="trans('admin::app.settings.taxes.tax-categories.create.name')"
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
                                            @lang('admin::app.settings.taxes.tax-categories.create.description')
                                        </x-admin::form.control-group.label>

                                        <x-admin::form.control-group.control
                                            type="textarea"
                                            name="description"
                                            :value="old('description')"
                                            id="description"
                                            rules="required"
                                            :label="trans('admin::app.settings.taxes.tax-categories.create.description')"
                                            :placeholder="trans('admin::app.settings.taxes.tax-categories.create.description')"
                                        >
                                        </x-admin::form.control-group.control>

                                        <x-admin::form.control-group.error
                                            control-name="description"
                                        >
                                        </x-admin::form.control-group.error>
                                    </x-admin::form.control-group>

                                    @php 
                                        $selectedOptions = old('taxrates') ?: [] 
                                    @endphp

                                    <!-- Select Tax Rates -->
                                    <x-admin::form.control-group>
                                        <x-admin::form.control-group.label>
                                            @lang('admin::app.settings.taxes.tax-categories.create.select-tax-rates')
                                        </x-admin::form.control-group.label>

                                        <x-admin::form.control-group.control
                                            type="select"
                                            name="taxrates" 
                                            id="taxrates"
                                            :label="trans('admin::app.settings.taxes.tax-categories.create.select-tax-rates')"
                                            :placeholder="trans('admin::app.settings.taxes.tax-categories.create.select-tax-rates')"
                                            multiple
                                        >
                                            @foreach ($taxRates as $taxRate)
                                                <option value="{{ $taxRate->id }}" {{ in_array($taxRate['id'], $selectedOptions) ? 'selected' : '' }}>
                                                    {{ $taxRate['identifier'] }}
                                                </option>
                                            @endforeach
                                        </x-admin::form.control-group.control>

                                        <x-admin::form.control-group.error
                                            control-name="taxrates"
                                        >
                                        </x-admin::form.control-group.error>
                                    </x-admin::form.control-group>

                                </div>
                            </x-slot:content>

                            <x-slot:footer>
                                <div class="flex gap-x-[10px] items-center">
                                    <!-- Save Button -->
                                    <button 
                                        type="submit"
                                        class="px-[12px] py-[6px] bg-blue-600 border border-blue-700 rounded-[6px] text-gray-50 font-semibold cursor-pointer"
                                    >
                                        @lang('admin::app.settings.taxes.tax-categories.create.save-btn-title')
                                    </button>
                                </div>
                            </x-slot:footer>
                        </x-admin::modal>
                    </form>
                </x-admin::form>
            </div>
        </script>

        <script type="module">
            app.component('v-create-tax-category', {
                template: '#v-create-tax-category-template',
                
                methods: {
                    store(params, { resetForm, setErrors }) {
                    
                        this.$axios.post('{{ route('admin.tax_categories.store') }}', params)
                            .then((response) => {
                                this.$refs.taxCategory.toggle();

                                resetForm();
                            })
                            .catch((error) =>{
                                if (error.response.status == 422) {
                                    setErrors(error.response.data.errors);
                                }
                            });
                    },
                },
            });
        </script>
    @endPushOnce
</x-admin::layouts>