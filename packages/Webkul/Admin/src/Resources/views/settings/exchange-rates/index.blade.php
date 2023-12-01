<x-admin::layouts>
    <!-- Title of the page -->
    <x-slot:title>
        @lang('admin::app.settings.exchange-rates.index.title')
    </x-slot:title>

    {!! view_render_event('bagisto.admin.settings.exchange_rates.create.before') !!}

    <v-exchange-rates>
        <div class="flex justify-between items-center">
            <p class="text-[20px] text-gray-800 dark:text-white font-bold">
                @lang('admin::app.settings.exchange-rates.index.title')
            </p>

            <div class="flex gap-x-[10px] items-center">
                <!-- Update Exchange Rate Button -->
                <a
                    href="{{ route('admin.settings.exchange_rates.update_rates') }}"
                    class="primary-button"
                >
                    @lang('admin::app.settings.exchange-rates.index.update-rates')
                </a>

                 <!-- Create Button -->
                @if (bouncer()->hasPermission('settings.exchange_rates.create'))
                    <button
                        type="button"
                        class="primary-button"
                    >
                        @lang('admin::app.settings.exchange-rates.index.create-btn')
                    </button>
                @endif
            </div>
        </div>

        <!-- DataGrid Shimmer -->
        <x-admin::shimmer.datagrid/>
    </v-exchange-rates>

    {!! view_render_event('bagisto.admin.settings.exchange_rates.create.after') !!}

    @pushOnce('scripts')
        <script
            type="text/x-template"
            id="v-exchange-rates-template"
        >
            <div class="flex justify-between items-center">
                <p class="text-[20px] text-gray-800 dark:text-white font-bold">
                    @lang('admin::app.settings.exchange-rates.index.title')
                </p>

                <div class="flex gap-x-[10px] items-center">
                    <!-- Update Exchange Rate Button -->
                    <a href="{{ route('admin.settings.exchange_rates.update_rates') }}" class="primary-button">
                        @lang('admin::app.settings.exchange-rates.index.update-rates')
                    </a>

                     <!-- Create Button -->
                    @if (bouncer()->hasPermission('settings.exchange_rates.create'))
                        <button
                            type="button"
                            class="primary-button"
                            @click="selectedExchangeRates=0;resetForm();$refs.exchangeRateUpdateOrCreateModal.toggle()"
                        >
                            @lang('admin::app.settings.exchange-rates.index.create-btn')
                        </button>
                    @endif
                </div>
            </div>

            <x-admin::datagrid
                :src="route('admin.settings.exchange_rates.index')"
                ref="datagrid"
            >
                <!-- DataGrid Body -->
                <template #body="{ columns, records, performAction }">
                    <div
                        v-for="record in records"
                        class="row grid gap-[10px] items-center px-[16px] py-[16px] border-b-[1px] dark:border-gray-800 text-gray-600 dark:text-gray-300 transition-all hover:bg-gray-50 dark:hover:bg-gray-950"
                        :style="`grid-template-columns: repeat(${gridsCount}, minmax(0, 1fr))`"
                    >
                        <!-- Id -->
                        <p v-text="record.currency_exchange_id"></p>

                        <!-- Status -->
                        <p v-text="record.currency_name"></p>

                        <!-- Email -->
                        <p v-text="record.currency_rate"></p>

                        <!-- Actions -->
                        @if (
                            bouncer()->hasPermission('settings.exchange_rates.edit') 
                            || bouncer()->hasPermission('settings.exchange_rates.delete')
                        )
                            <div class="flex justify-end">
                                @if (bouncer()->hasPermission('settings.exchange_rates.edit'))
                                    <a @click="selectedExchangeRates=1; editModal(record.actions.find(action => action.title === 'Edit')?.url)">
                                        <span
                                            :class="record.actions.find(action => action.title === 'Edit')?.icon"
                                            class="cursor-pointer rounded-[6px] p-[6px] text-[24px] transition-all hover:bg-gray-200 dark:hover:bg-gray-800 max-sm:place-self-center"
                                        >
                                        </span>
                                    </a>
                                @endif

                                @if (bouncer()->hasPermission('settings.exchange_rates.delete'))
                                    <a @click="performAction(record.actions.find(action => action.method === 'DELETE'))">
                                        <span
                                            :class="record.actions.find(action => action.method === 'DELETE')?.icon"
                                            class="cursor-pointer rounded-[6px] p-[6px] text-[24px] transition-all hover:bg-gray-200 dark:hover:bg-gray-800 max-sm:place-self-center"
                                        >
                                        </span>
                                    </a>
                                @endif
                            </div>
                        @endif
                    </div>
                </template>
            </x-admin::datagrid>

            <!-- Exchange Rate Create Form -->
            <x-admin::form
                v-slot="{ meta, errors, handleSubmit }"
                as="div"
                ref="modalForm"
            >
                <form
                    @submit="handleSubmit($event, updateOrCreate)"
                    ref="exchangeRateCreateForm"
                >
                    <!-- Modal -->
                    <x-admin::modal ref="exchangeRateUpdateOrCreateModal">
                        <x-slot:header>
                            <!-- Modal Header -->
                            <p class="text-[18px] text-gray-800 dark:text-white font-bold">
                                <span v-if="selectedExchangeRates">
                                    @lang('admin::app.settings.exchange-rates.index.edit.title')
                                </span>

                                <span v-else>
                                    @lang('admin::app.settings.exchange-rates.index.create.title')
                                </span>
                            </p>
                        </x-slot:header>

                        <x-slot:content>
                            <!-- Modal Content -->
                            <div class="px-[16px] py-[10px] border-b-[1px] dark:border-gray-800">
                                {!! view_render_event('bagisto.admin.settings.exchangerate.create.before') !!}

                                <x-admin::form.control-group.control
                                    type="hidden"
                                    name="id"
                                    v-model="selectedExchangeRate.id"
                                >
                                </x-admin::form.control-group.control>

                                <!-- Currency Code -->
                                <x-admin::form.control-group class="mb-[10px]">
                                    <x-admin::form.control-group.label>
                                        @lang('admin::app.settings.exchange-rates.index.create.source-currency')
                                    </x-admin::form.control-group.label>

                                    <x-admin::form.control-group.control
                                        type="text"
                                        name="base_currency"
                                        disabled
                                        :value="core()->getBaseCurrencyCode()"
                                    >
                                    </x-admin::form.control-group.control>
                                </x-admin::form.control-group>

                                <!-- Target Currency -->
                                <x-admin::form.control-group class="mb-[10px]">
                                    <x-admin::form.control-group.label class="required">
                                        @lang('admin::app.settings.exchange-rates.index.create.target-currency')
                                    </x-admin::form.control-group.label>

                                    <x-admin::form.control-group.control
                                        type="select"
                                        name="target_currency"
                                        rules="required"
                                        v-model="selectedExchangeRate.target_currency"
                                        :label="trans('admin::app.settings.exchange-rates.index.create.target-currency')"
                                    >
                                        <!-- Default Option -->
                                        <option value="">
                                            @lang('admin::app.settings.exchange-rates.index.create.select-target-currency')
                                        </option>

                                        <option
                                            v-for="currency in currencies"
                                            :value="currency.id"
                                            :selected="currency.id == selectedExchangeRate.target_currency"
                                        >
                                            @{{ currency.name }}
                                        </option>

                                    </x-admin::form.control-group.control>

                                    <x-admin::form.control-group.error
                                        control-name="target_currency"
                                    >
                                    </x-admin::form.control-group.error>
                                </x-admin::form.control-group>

                                <!-- Rate -->
                                <x-admin::form.control-group class="mb-[10px]">
                                    <x-admin::form.control-group.label class="required">
                                        @lang('admin::app.settings.exchange-rates.index.create.rate')
                                    </x-admin::form.control-group.label>

                                    <x-admin::form.control-group.control
                                        type="text"
                                        name="rate"
                                        :value="old('rate')"
                                        rules="required"
                                        v-model="selectedExchangeRate.rate"
                                        :label="trans('admin::app.settings.exchange-rates.index.create.rate')"
                                        :placeholder="trans('admin::app.settings.exchange-rates.index.create.rate')"
                                    >
                                    </x-admin::form.control-group.control>

                                    <x-admin::form.control-group.error
                                        control-name="rate"
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
                                    class="primary-button"
                                >
                                    @lang('admin::app.settings.exchange-rates.index.create.save-btn')
                                </button>
                            </div>
                        </x-slot:footer>
                    </x-admin::modal>
                </form>
            </x-admin::form>
        </script>

        <script type="module">
            app.component('v-exchange-rates', {
                template: '#v-exchange-rates-template',


                data() {
                    return {
                        selectedExchangeRate: {},

                        selectedExchangeRates: 0,

                        currencies: @json($currencies),
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
                        let formData = new FormData(this.$refs.exchangeRateCreateForm);

                        if (params.id) {
                            formData.append('_method', 'put');
                        }

                        this.$axios.post(params.id ? "{{ route('admin.settings.exchange_rates.update')  }}" : "{{ route('admin.settings.exchange_rates.store')  }}", formData)
                            .then((response) => {
                                this.$emitter.emit('add-flash', { type: 'success', message: response.data.message });

                                this.$refs.exchangeRateUpdateOrCreateModal.close();

                                this.$refs.datagrid.get();

                                resetForm();
                            })
                            .catch(error => {
                                if (error.response.status == 422) {
                                    setErrors(error.response.data.errors);
                                }
                            });
                    },

                    editModal(url) {
                        this.$axios.get(url)
                            .then((response) => {
                                this.selectedExchangeRate = response.data.data.exchangeRate;

                                this.$refs.exchangeRateUpdateOrCreateModal.toggle();
                            })
                            .catch(error => [
                                this.$emitter.emit('add-flash', { type: 'error', message: error.response.data.message })
                            ]);
                    },

                    resetForm() {
                        this.selectedExchangeRate = {};
                    }
                }
            })
        </script>
    @endPushOnce
</x-admin::layouts>
