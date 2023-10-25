<x-admin::layouts>
    {{-- Page Title --}}
    <x-slot:title>
        @lang('admin::app.settings.taxes.categories.index.title')
    </x-slot:title>

    <v-tax-categories>
        <div class="flex justify-between items-center">
            <p class="text-[20px] text-gray-800 dark:text-white font-bold">
                @lang('admin::app.settings.taxes.categories.index.title')
            </p>
            
            <div class="flex gap-x-[10px] items-center">
                <div class="flex gap-x-[10px] items-center">
                    {{-- Create Tax Category Button --}}
                    @if (bouncer()->hasPermission('settings.taxes.tax-categories.create'))
                        <button
                            type="button"
                            class="primary-button"
                        >
                            @lang('admin::app.settings.taxes.categories.index.create.title')
                        </button>
                    @endif
                </div>
            </div>
        </div>

        {{-- DataGrid Shimmer --}}
        <x-admin::shimmer.datagrid/>
    </v-tax-categories>
    
    @pushOnce('scripts')
        <script type="text/x-template" id="v-tax-categories-template">
            <div class="flex justify-between items-center">
                <p class="text-[20px] text-gray-800 dark:text-white font-bold">
                    @lang('admin::app.settings.taxes.categories.index.title')
                </p>
                
                <div class="flex gap-x-[10px] items-center">
                    <div class="flex gap-x-[10px] items-center">
                        <!-- Create Tax Category Button -->
                        @if (bouncer()->hasPermission('settings.taxes.tax-categories.create'))
                            <button
                                type="button"
                                class="primary-button"
                                @click="selectedTaxRates = {}; $refs.taxCategory.toggle()"
                            >
                                @lang('admin::app.settings.taxes.categories.index.create.title')
                            </button>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Datagrid -->
            <x-admin::datagrid
                :src="route('admin.settings.taxes.categories.index')"
                ref="datagrid"
            >
                @php
                    $hasPermission = bouncer()->hasPermission('settings.taxes.tax-categories.edit') || bouncer()->hasPermission('settings.taxes.tax-categories.delete');
                @endphp

                <!-- DataGrid Header -->
                <template #header="{ columns, records, sortPage, applied}">
                    <div class="row grid grid-cols-{{ $hasPermission ? '4' : '3' }} grid-rows-1 gap-[10px] items-center px-[16px] py-[10px] border-b-[1px] dark:border-gray-800 text-gray-600 dark:text-gray-300 bg-gray-50 dark:bg-gray-900 font-semibold">
                        <div
                            class="flex gap-[10px] cursor-pointer"
                            v-for="(columnGroup, index) in ['id', 'code', 'name']"
                        >
                            <p class="text-gray-600 dark:text-gray-300">
                                <span class="[&>*]:after:content-['_/_']">
                                    <span
                                        class="after:content-['/'] last:after:content-['']"
                                        :class="{
                                            'text-gray-800 dark:text-white font-medium': applied.sort.column == columnGroup,
                                            'cursor-pointer hover:text-gray-800 dark:hover:text-white': columns.find(columnTemp => columnTemp.index === columnGroup)?.sortable,
                                        }"
                                        @click="
                                            columns.find(columnTemp => columnTemp.index === columnGroup)?.sortable ? sortPage(columns.find(columnTemp => columnTemp.index === columnGroup)): {}
                                        "
                                    >
                                        @{{ columns.find(columnTemp => columnTemp.index === columnGroup)?.label }}
                                    </span>
                                </span>

                                <!-- Filter Arrow Icon -->
                                <i
                                    class="ltr:ml-[5px] rtl:mr-[5px] text-[16px] text-gray-800 dark:text-white align-text-bottom"
                                    :class="[applied.sort.order === 'asc' ? 'icon-down-stat': 'icon-up-stat']"
                                    v-if="columnGroup.includes(applied.sort.column)"
                                ></i>
                            </p>
                        </div>

                        <!-- Actions -->
                        @if ($hasPermission)
                            <p class="flex gap-[10px] justify-end">
                                @lang('admin::app.components.datagrid.table.actions')
                            </p>
                        @endif
                    </div>
                </template>

                <!-- DataGrid Body -->
                <template #body="{ columns, records, performAction }">
                    <div
                        v-for="record in records"
                        class="row grid gap-[10px] items-center px-[16px] py-[16px] border-b-[1px] dark:border-gray-800 text-gray-600 dark:text-gray-300 transition-all hover:bg-gray-50 dark:hover:bg-gray-950"
                        :style="'grid-template-columns: repeat(' + (record.actions.length ? 4 : 3) + ', 1fr);'"
                    >
                        <!-- Id -->
                        <p v-text="record.id"></p>

                        <!-- Name -->
                        <p v-text="record.name"></p>

                        <!-- Code -->
                        <p v-text="record.code"></p>

                        <!-- Actions -->
                        <div class="flex justify-end">
                            <a @click="id=1; editModal(record.actions.find(action => action.title === 'Edit')?.url)">
                                <span
                                    :class="record.actions.find(action => action.title === 'Edit')?.icon"
                                    class="cursor-pointer rounded-[6px] p-[6px] text-[24px] transition-all hover:bg-gray-200 dark:hover:bg-gray-800 max-sm:place-self-center"
                                >
                                </span>
                            </a>

                            <a @click="performAction(record.actions.find(action => action.method === 'DELETE'))">
                                <span
                                    :class="record.actions.find(action => action.method === 'DELETE')?.icon"
                                    class="cursor-pointer rounded-[6px] p-[6px] text-[24px] transition-all hover:bg-gray-200 dark:hover:bg-gray-800 max-sm:place-self-center"
                                >
                                </span>
                            </a>
                        </div>
                    </div>
                </template>
            </x-admin::datagrid>

            <!-- Model Form -->
            <x-admin::form
                v-slot="{ meta, errors, handleSubmit }"
                as="div"
                ref="modalForm"
            >
                <form
                    @submit="handleSubmit($event, updateOrCreate)"
                    ref="taxCategoryCreateForm"
                >
                    <x-admin::modal ref="taxCategory">
                        <x-slot:header>
                            <p class="text-[18px] text-gray-800 dark:text-white font-bold">
                                <span v-if="id">
                                    @lang('admin::app.settings.taxes.categories.index.edit.title')
                                </span>

                                <span v-else>
                                    @lang('admin::app.settings.taxes.categories.index.create.title')
                                </span>
                            </p>
                        </x-slot:header>

                        <x-slot:content>
                            <div class="px-[16px] py-[10px] border-b-[1px] dark:border-gray-800">
                                <!-- Code -->
                                <x-admin::form.control-group class="mb-[10px]">
                                    <x-admin::form.control-group.label class="required">
                                        @lang('admin::app.settings.taxes.categories.index.create.code')
                                    </x-admin::form.control-group.label>
                                    
                                    <x-admin::form.control-group.control
                                        type="hidden"
                                        name="id"
                                        v-model="selectedTaxRates.id"
                                    >
                                    </x-admin::form.control-group.control>

                                    <x-admin::form.control-group.control
                                        type="text"
                                        name="code"
                                        id="code"
                                        rules="required"
                                        :label="trans('admin::app.settings.taxes.categories.index.create.code')"
                                        :placeholder="trans('admin::app.settings.taxes.categories.index.create.code')"
                                        v-model="selectedTaxRates.code"
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
                                        @lang('admin::app.settings.taxes.categories.index.create.name')
                                    </x-admin::form.control-group.label>

                                    <x-admin::form.control-group.control
                                        type="text"
                                        name="name"
                                        id="name"
                                        rules="required"
                                        :label="trans('admin::app.settings.taxes.categories.index.create.name')"
                                        :placeholder="trans('admin::app.settings.taxes.categories.index.create.name')"
                                        v-model="selectedTaxRates.name"
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
                                        @lang('admin::app.settings.taxes.categories.index.create.description')
                                    </x-admin::form.control-group.label>

                                    <x-admin::form.control-group.control
                                        type="textarea"
                                        name="description"
                                        id="description"
                                        rules="required"
                                        :label="trans('admin::app.settings.taxes.categories.index.create.description')"
                                        :placeholder="trans('admin::app.settings.taxes.categories.index.create.description')"
                                        v-model="selectedTaxRates.description"
                                    >
                                    </x-admin::form.control-group.control>

                                    <x-admin::form.control-group.error
                                        control-name="description"
                                    >
                                    </x-admin::form.control-group.error>
                                </x-admin::form.control-group>

                                <!-- Select Tax Rates -->
                                <p class="required block leading-[24px] text-gray-800 dark:text-white font-medium">
                                    @lang('admin::app.settings.taxes.categories.index.create.tax-rates')
                                </p>
                                
                                <x-admin::form.control-group 
                                    class="flex gap-[10px] !mb-0 p-[6px]"
                                >
                                    <v-field
                                        name="taxrates[]" 
                                        rules="required"
                                        label="@lang('admin::app.settings.taxes.categories.index.create.tax-rates')"
                                        v-model="selectedTaxRates.tax_rates"
                                        multiple
                                    >
                                        <select
                                            name="taxrates[]" 
                                            class="flex w-full min-h-[39px] py-2 px-3 border rounded-[6px] text-[14px] text-gray-600 dark:text-gray-300 transition-all hover:border-gray-400 dark:hover:border-gray-400 focus:border-gray-400 dark:focus:border-gray-400 dark:bg-gray-900 dark:border-gray-800"
                                            :class="[errors['options[sort]'] ? 'border border-red-600 hover:border-red-600' : '']"
                                            multiple
                                            v-model="selectedTaxRates.tax_rates"
                                        >
                                            <option value="" disabled>@lang('admin::app.settings.taxes.categories.index.create.select')</option>

                                            <option 
                                                v-for="taxRate in taxRates"
                                                :value="taxRate.id"
                                                :text="taxRate.identifier"
                                            >
                                            </option>
                                        </select>
                                    </v-field>
                                        
                                    <x-admin::form.control-group.label 
                                        class="!text-[14px] !text-gray-600 cursor-pointer"
                                    >
                                    </x-admin::form.control-group.label>

                                </x-admin::form.control-group>

                                <x-admin::form.control-group.error
                                    control-name="taxrates[]"
                                >
                                </x-admin::form.control-group.error>
                            </div>
                        </x-slot:content>

                        <x-slot:footer>
                            <div class="flex gap-x-[10px] items-center">
                                <!-- Save Button -->
                                <button 
                                    type="submit"
                                    class="primary-button"
                                >
                                    @lang('admin::app.settings.taxes.categories.index.create.save-btn')
                                </button>
                            </div>
                        </x-slot:footer>
                    </x-admin::modal>
                </form>
            </x-admin::form>
        </script>

        <script type="module">
            app.component('v-tax-categories', {
                template: '#v-tax-categories-template',

                data() {
                    return {
                        taxRates: @json($taxRates),

                        selectedTaxRates: {},
                    }
                },

                methods: {
                    updateOrCreate(params, { resetForm, setErrors }) {
                        let formData = new FormData(this.$refs.taxCategoryCreateForm);

                        if (params.id) {
                            formData.append('_method', 'put');
                        }

                        this.$axios.post(params.id ? "{{ route('admin.settings.taxes.categories.update') }}" : "{{ route('admin.settings.taxes.categories.store') }}", formData,{
                            headers: {
                                'Content-Type': 'multipart/form-data'
                                }
                            })
                            .then((response) => {
                                this.$refs.taxCategory.toggle();

                                this.$refs.datagrid.get();
                                
                                this.$emitter.emit('add-flash', { type: 'success', message: response.data.message });

                                this.selectedTaxRates = {};
                            })
                            .catch((error) =>{
                                if (error.response.status == 422) {
                                    setErrors(error.response.data.errors);
                                }
                            });
                    },

                    editModal(url) {
                        this.$axios.get(url)
                            .then(response => {
                                this.selectedTaxRates = response.data.data;

                                this.$refs.taxCategory.toggle();
                            })
                            .catch(error => [
                                this.$emitter.emit('add-flash', { type: 'error', message: error.response.data.message })
                            ]);
                    },
                },
            });
        </script>
    @endPushOnce
</x-admin::layouts>