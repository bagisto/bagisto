<x-admin::layouts>
    <!-- Page Title -->
    <x-slot:title>
        @lang('admin::app.settings.taxes.categories.index.title')
    </x-slot>

    <v-tax-categories>
        <div class="flex justify-between items-center">
            <p class="text-xl text-gray-800 dark:text-white font-bold">
                @lang('admin::app.settings.taxes.categories.index.title')
            </p>

            <div class="flex gap-x-2.5 items-center">
                <div class="flex gap-x-2.5 items-center">
                    <!-- Create Tax Category Button -->
                    @if (bouncer()->hasPermission('settings.taxes.tax_categories.create'))
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

        <!-- DataGrid Shimmer -->
        <x-admin::shimmer.datagrid />
    </v-tax-categories>

    @pushOnce('scripts')
        <script
            type="text/x-template"
            id="v-tax-categories-template"
        >
            <div class="flex justify-between items-center">
                <p class="text-xl text-gray-800 dark:text-white font-bold">
                    @lang('admin::app.settings.taxes.categories.index.title')
                </p>

                <div class="flex gap-x-2.5 items-center">
                    <div class="flex gap-x-2.5 items-center">
                        <!-- Create Tax Category Button -->
                        @if (bouncer()->hasPermission('settings.taxes.tax_categories.create'))
                            <button
                                type="button"
                                class="primary-button"
                                @click="selectedTaxRates={}; selectedTaxCategories=0; $refs.taxCategory.toggle()"
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
                <!-- DataGrid Body -->
                <template #body="{ columns, records, performAction }">
                    <div
                        v-for="record in records"
                        class="row grid gap-2.5 items-center px-4 py-4 border-b dark:border-gray-800 text-gray-600 dark:text-gray-300 transition-all hover:bg-gray-50 dark:hover:bg-gray-950"
                        :style="`grid-template-columns: repeat(${gridsCount}, minmax(0, 1fr))`"
                    >
                        <!-- Id -->
                        <p v-text="record.id"></p>

                        <!-- Name -->
                        <p v-text="record.name"></p>

                        <!-- Code -->
                        <p v-text="record.code"></p>

                        <!-- Actions -->
                        <div class="flex justify-end">
                            @if (bouncer()->hasPermission('settings.taxes.tax_categories.edit'))
                                <a @click="selectedTaxCategories=1; editModal(record.actions.find(action => action.index === 'edit')?.url)">
                                    <span
                                        :class="record.actions.find(action => action.index === 'edit')?.icon"
                                        class="cursor-pointer rounded-md p-1.5 text-2xl transition-all hover:bg-gray-200 dark:hover:bg-gray-800 max-sm:place-self-center"
                                    >
                                    </span>
                                </a>
                            @endif

                            @if (bouncer()->hasPermission('settings.taxes.tax_categories.delete'))
                                <a @click="performAction(record.actions.find(action => action.index === 'delete'))">
                                    <span
                                        :class="record.actions.find(action => action.index === 'delete')?.icon"
                                        class="cursor-pointer rounded-md p-1.5 text-2xl transition-all hover:bg-gray-200 dark:hover:bg-gray-800 max-sm:place-self-center"
                                    >
                                    </span>
                                </a>
                            @endif
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
                        <!-- Modal Header -->
                        <x-slot:header>
                            <p class="text-lg text-gray-800 dark:text-white font-bold">
                                <span v-if="selectedTaxCategories">
                                    @lang('admin::app.settings.taxes.categories.index.edit.title')
                                </span>

                                <span v-else>
                                    @lang('admin::app.settings.taxes.categories.index.create.title')
                                </span>
                            </p>
                        </x-slot>

                        <!-- Modal Content -->
                        <x-slot:content>
                            <!-- Code -->
                            <x-admin::form.control-group>
                                <x-admin::form.control-group.label class="required">
                                    @lang('admin::app.settings.taxes.categories.index.create.code')
                                </x-admin::form.control-group.label>

                                <x-admin::form.control-group.control
                                    type="hidden"
                                    name="id"
                                    v-model="selectedTaxRates.id"
                                />

                                <x-admin::form.control-group.control
                                    type="text"
                                    id="code"
                                    name="code"
                                    rules="required"
                                    v-model="selectedTaxRates.code"
                                    :label="trans('admin::app.settings.taxes.categories.index.create.code')"
                                    :placeholder="trans('admin::app.settings.taxes.categories.index.create.code')"
                                />

                                <x-admin::form.control-group.error control-name="code" />
                            </x-admin::form.control-group>

                            <!-- Name -->
                            <x-admin::form.control-group>
                                <x-admin::form.control-group.label class="required">
                                    @lang('admin::app.settings.taxes.categories.index.create.name')
                                </x-admin::form.control-group.label>

                                <x-admin::form.control-group.control
                                    type="text"
                                    id="name"
                                    name="name"
                                    rules="required"
                                    v-model="selectedTaxRates.name"
                                    :label="trans('admin::app.settings.taxes.categories.index.create.name')"
                                    :placeholder="trans('admin::app.settings.taxes.categories.index.create.name')"
                                />

                                <x-admin::form.control-group.error control-name="name" />
                            </x-admin::form.control-group>

                            <!-- Description -->
                            <x-admin::form.control-group class="!mb-0">
                                <x-admin::form.control-group.label class="required">
                                    @lang('admin::app.settings.taxes.categories.index.create.description')
                                </x-admin::form.control-group.label>

                                <x-admin::form.control-group.control
                                    type="textarea"
                                    id="description"
                                    name="description"
                                    rules="required"
                                    v-model="selectedTaxRates.description"
                                    :label="trans('admin::app.settings.taxes.categories.index.create.description')"
                                    :placeholder="trans('admin::app.settings.taxes.categories.index.create.description')"
                                />

                                <x-admin::form.control-group.error control-name="description" />
                            </x-admin::form.control-group>

                            <!-- Select Tax Rates -->
                            <x-admin::form.control-group>
                                <x-admin::form.control-group.label class="required">
                                    @lang('admin::app.settings.taxes.categories.index.create.tax-rates')
                                </x-admin::form.control-group.label>

                                <v-field
                                    name="taxrates[]"
                                    rules="required"
                                    label="@lang('admin::app.settings.taxes.categories.index.create.tax-rates')"
                                    v-model="selectedTaxRates.tax_rates"
                                    multiple
                                >
                                    <select
                                        name="taxrates[]"
                                        class="flex w-full min-h-[39px] py-2 px-3 border rounded-md text-sm text-gray-600 dark:text-gray-300 transition-all hover:border-gray-400 dark:hover:border-gray-400 focus:border-gray-400 dark:focus:border-gray-400 dark:bg-gray-900 dark:border-gray-800"
                                        :class="[errors['options[sort]'] ? 'border border-red-600 hover:border-red-600' : '']"
                                        multiple
                                        v-model="selectedTaxRates.tax_rates"
                                    >
                                        <option
                                            v-for="taxRate in taxRates"
                                            :value="taxRate.id"
                                            :text="taxRate.identifier"
                                        >
                                        </option>
                                    </select>
                                </v-field>

                                <x-admin::form.control-group.error
                                    control-name="taxrates[]"
                                >
                                </x-admin::form.control-group.error>
                            </x-admin::form.control-group>

                        </x-slot>

                        <!-- Modal Footer -->
                        <x-slot:footer>
                            <div class="flex gap-x-2.5 items-center">
                                <button
                                    type="submit"
                                    class="primary-button"
                                >
                                    @lang('admin::app.settings.taxes.categories.index.create.save-btn')
                                </button>
                            </div>
                        </x-slot>
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

                        selectedTaxCategories: 0,
                    }
                },

                computed: {
                    gridsCount() {
                        let count = this.$refs.datagrid.available.columns.length;

                        if (this.$refs.datagrid.available.actions.length) {
                            ++count;
                        }

                        if (this.$refs.datagrid.available.massActions.length) {
                            ++count;
                        }

                        return count;
                    },
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
                            .catch(error => this.$emitter.emit('add-flash', { 
                                type: 'error', message: error.response.data.message 
                            }));
                    },
                },
            });
        </script>
    @endPushOnce
</x-admin::layouts>
