<x-admin::layouts>
    {{-- Title of the page --}}
    <x-slot:title>
        @lang('admin::app.settings.exchange-rates.index.title')
    </x-slot:title>

    <v-exchange-rates>
        <div class="flex justify-between items-center">
            <p class="text-[20px] text-gray-800 font-bold">
                @lang('admin::app.settings.exchange-rates.index.title')
            </p>

            <div class="flex gap-x-[10px] items-center">
                 <!-- Create Button -->
                <button
                    type="button"
                    class="px-[12px] py-[6px] bg-blue-600 border border-blue-700 rounded-[6px] text-gray-50 font-semibold cursor-pointer"
                >
                    @lang('admin::app.settings.exchange-rates.index.create-btn')
                </button>
            </div>
        </div>

        {{-- Added For Shimmer --}}
        <x-admin::datagrid></x-admin::datagrid>
    </v-exchange-rates>

    @pushOnce('scripts')
        <script
            type="text/x-template"
            id="v-exchange-rates-template"
        >
            <div class="flex justify-between items-center">
                <p class="text-[20px] text-gray-800 font-bold">
                    @lang('admin::app.settings.exchange-rates.index.title')
                </p>

                <div class="flex gap-x-[10px] items-center">
                     <!-- Create Button -->
                    @if (bouncer()->hasPermission('settings.exchange_rates.create'))
                        <button
                            type="button"
                            class="px-[12px] py-[6px] bg-blue-600 border border-blue-700 rounded-[6px] text-gray-50 font-semibold cursor-pointer"
                            @click="id=0; $refs.exchangeRate.toggle()"
                        >
                            @lang('admin::app.settings.exchange-rates.index.create-btn')
                        </button>
                    @endif
                </div>
            </div>

            <x-admin::datagrid
                src="{{ route('admin.exchange_rates.index') }}"
                ref="datagrid"
            >
                <!-- DataGrid Header -->
                <template #header="{ columns, records, sortPage}">
                    <div class="row grid grid-cols-4 grid-rows-1 gap-[10px] items-center px-[16px] py-[10px] border-b-[1px] text-gray-600 bg-gray-50 font-semibold">
                        <div
                            class="cursor-pointer"
                            @click="sortPage(columns.find(column => column.index === 'currency_exchange_id'))"
                        >
                            <div class="flex gap-[10px]">
                                <p class="text-gray-600">ID</p>
                            </div>
                        </div>
        
                        <div
                            class="cursor-pointer"
                            @click="sortPage(columns.find(column => column.index === 'currency_name'))"
                        >
                            <p class="text-gray-600">Currency Name</p>
                        </div>
        
                        <div
                            class="cursor-pointer"
                            @click="sortPage(columns.find(column => column.index === 'currency_rate'))"
                        >
                            <p class="text-gray-600">Exchange Rate</p>
                        </div>
        
                        <div class="cursor-pointer flex justify-end">
                            <p class="text-gray-600">Actions</p>
                        </div>
                    </div>
                </template>

                <!-- DataGrid Body -->
                <template #body="{ columns, records }">
                    <div
                        v-for="record in records"
                        class="row grid gap-[10px] items-center px-[16px] py-[16px] border-b-[1px] border-gray-300 text-gray-600 transition-all hover:bg-gray-100"
                        style="grid-template-columns: repeat(4, 1fr);"
                    >
                        <!-- Id -->
                        <p v-text="record.currency_exchange_id"></p>
        
                        <!-- Status -->
                        <p v-text="record.currency_name"></p>
        
                        <!-- Email -->
                        <p v-text="record.currency_rate"></p>
        
                        <!-- Actions -->
                        <div class="flex justify-end">
                            <a @click="id=1; editModal(record.currency_exchange_id)">
                                <span
                                    :class="record.actions['0'].icon"
                                    class="cursor-pointer rounded-[6px] p-[6px] text-[24px] transition-all hover:bg-gray-100 max-sm:place-self-center"
                                    :title="record.actions['0'].title"
                                >
                                </span>
                            </a>
        
                            <a @click="deleteModal(record.actions['1']?.url)">
                                <span
                                    :class="record.actions['1'].icon"
                                    class="cursor-pointer rounded-[6px] p-[6px] text-[24px] transition-all hover:bg-gray-100 max-sm:place-self-center"
                                    :title="record.actions['1'].title"
                                >
                                </span>
                            </a>
                        </div>
                    </div>
                </template>
            </x-admin::datagrid>

            <!-- Exchange Rate Create Form -->
            <x-admin::form
                v-slot="{ meta, errors, handleSubmit }"
                as="div"
                ref="modalForm"
            >
                <form @submit="handleSubmit($event, create)">
                    <!-- Modal -->
                    <x-admin::modal ref="exchangeRate">
                        <x-slot:header>
                            <!-- Modal Header -->
                            <p class="text-[18px] text-gray-800 font-bold">
                                <span v-if="id">
                                    @lang('admin::app.settings.exchange-rates.index.edit.title')
                                </span>

                                <span v-else>
                                    @lang('admin::app.settings.exchange-rates.index.create.title')
                                </span>
                            </p>
                        </x-slot:header>

                        <x-slot:content>
                            <!-- Modal Content -->
                            <div class="px-[16px] py-[10px] border-b-[1px] border-gray-300">
                                {!! view_render_event('bagisto.admin.settings.exchangerate.create.before') !!}
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
                                        :label="trans('admin::app.settings.exchange-rates.index.create.target-currency')"
                                    >
                                        @foreach ($currencies as $currency)
                                            @if (is_null($currency->exchange_rate))
                                                <option value="{{ $currency->id }}">
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

                                <!-- Rate -->
                                <x-admin::form.control-group class="mb-[10px]">
                                    <x-admin::form.control-group.label class="required">
                                        @lang('admin::app.settings.exchange-rates.index.create.rate')
                                    </x-admin::form.control-group.label>

                                    <x-admin::form.control-group.control
                                        type="text"
                                        name="rate"
                                        id="rate"
                                        :value="old('rate')"
                                        rules="required"
                                        label="{{ trans('admin::app.settings.exchange-rates.index.create.rate') }}"
                                        placeholder="{{ trans('admin::app.settings.exchange-rates.index.create.rate') }}"
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
                                    class="px-[12px] py-[6px] bg-blue-600 border border-blue-700 rounded-[6px] text-gray-50 font-semibold cursor-pointer"
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
                        id: 0,
                    }
                },

                methods: {
                    create(params, { resetForm, setErrors }) {
                        if (params.id) {
                            this.$axios.post("{{ route('admin.exchange_rates.update')  }}", params)
                                .then((response) => {
                                    this.$refs.exchangeRate.close();
    
                                    this.$refs.datagrid.get();

                                    this.$emitter.emit('add-flash', { type: 'success', message: response.data.data.message });
        
                                    resetForm();
                                })
                                .catch(error => {
                                    if (error.response.status == 422) {
                                        setErrors(error.response.data.errors);
                                    }
                                });
                        } else {
                            this.$axios.post("{{ route('admin.exchange_rates.store')  }}", params)
                                .then((response) => {
                                    this.$emitter.emit('add-flash', { type: 'success', message: response.data.data.message });

                                    this.$refs.exchangeRate.close();

                                    this.$refs.datagrid.get();
    
                                    resetForm();
                                })
                                .catch(error => {
                                    if (error.response.status == 422) {
                                        setErrors(error.response.data.errors);
                                    }
                                });
                        }
                    },

                    editModal(id) {
                        this.$axios.get(`{{ route('admin.exchange_rates.edit', '') }}/${id}`)
                            .then((response) => {
                                let values = {
                                    id: response.data.data.exchangeRate.id,
                                    rate: response.data.data.exchangeRate.rate,
                                    target_currency: response.data.data.exchangeRate.target_currency,
                                };

                                this.$refs.exchangeRate.toggle();

                                this.$refs.modalForm.setValues(values);
                            })
                            .catch(error => {
                                if (error.response.status == 422) {
                                    setErrors(error.response.data.errors);
                                }
                            });
                        
                    },

                    deleteModal(url) {
                        if (! confirm('Are you sure, you want to perform this action?')) {
                            return;
                        }

                        this.$axios.post(url, {
                            '_method': 'DELETE'
                        })
                            .then((response) => {
                                this.$refs.datagrid.get();

                                this.$emitter.emit('add-flash', { type: 'success', message: response.data.data.message });
                            })
                            .catch(error => {
                                if (error.response.status ==422) {
                                    setErrors(error.response.data.errors);
                                }
                            });
                    }
                }
            })
        </script>
    @endPushOnce
</x-admin::layouts>
