<x-admin::layouts>
    <x-slot:title>
        @lang('admin::app.settings.currencies.index.title')
    </x-slot:title>

    <div class="flex  gap-[16px] justify-between items-center max-sm:flex-wrap">
        <p class="text-[20px] text-gray-800 font-bold">
            @lang('admin::app.settings.currencies.index.title')
        </p>

        <div class="flex gap-x-[10px] items-center">
            <v-currencies-form ref="vCurrenciesForm"></v-currencies-form>
        </div>
    </div>
    
    {{-- Currencies Data grid vue component --}}
    <v-currency-datagrid ref="vCurrencyDataGrid"></v-currency-datagrid>

    @pushOnce('scripts')
        <script type="text/x-template" id="v-currency-datagrid-template">
            <!-- Blade component Datagrid. -->
            <x-admin::datagrid 
                ref="datagrid"
                :src="route('admin.currencies.index')"
                @toggle="toggleModalAndSetValues"
            >
            </x-admin::datagrid>
        </script>

        <script type="module">
            app.component('v-currency-datagrid', {
                template: '#v-currency-datagrid-template',

                methods: {
                    toggleModalAndSetValues(event) {
                        let data = JSON.parse(event.url);

                        this.$root.$refs.vCurrenciesForm.isEditing = true;
                        

                        this.$root.$refs.vCurrenciesForm.$refs.currencyModalForm.setValues(data)

                        this.$root.$refs.vCurrenciesForm.$refs.currencyModal.toggle();
                    },
                }
            })
        </script>
    @endPushOnce

    {{-- Currencies Create and edit Form --}}
    @pushOnce('scripts')
        <script type="text/x-template" id="v-currencies-form-template">
            <div>
                <!-- Create currency Button -->
                <button 
                    type="button"
                    class="px-[12px] py-[6px] bg-blue-600 border border-blue-700 rounded-[6px] text-gray-50 font-semibold cursor-pointer"
                    @click="$refs.currencyModal.toggle();isEditing = false;"
                >
                    @lang('admin::app.settings.currencies.create.create-btn')
                </button>

                <x-admin::form
                    v-slot="{ meta, errors, handleSubmit }"
                    as="div"
                    ref="currencyModalForm"
                >
                    <form @submit="handleSubmit($event, store)">
                        <x-admin::modal ref="currencyModal">
                            <x-slot:header>
                                <p  
                                    class="text-[18px] text-gray-800 font-bold"
                                    v-if="isEditing"
                                >
                                    @lang('admin::app.settings.currencies.edit.title')
                                </p>

                                <p
                                    class="text-[18px] text-gray-800 font-bold"
                                    v-else
                                >
                                    @lang('admin::app.settings.currencies.create.title')
                                </p>
                            </x-slot:header>

                            <x-slot:content>
                                <div class="px-[16px] py-[10px] border-b-[1px] border-gray-300">
                                    {!! view_render_event('bagisto.admin.settings.currencies.create.before') !!}

                                    <x-admin::form.control-group class="mb-[10px]">
                                        <x-admin::form.control-group.control
                                            type="hidden"
                                            name="id"
                                            id="id"
                                        >
                                        </x-admin::form.control-group.control>
                                    </x-admin::form.control-group>

                                    <x-admin::form.control-group class="mb-[10px]">
                                        <x-admin::form.control-group.label>
                                            @lang('admin::app.settings.currencies.create.code')
                                        </x-admin::form.control-group.label>

                                        <x-admin::form.control-group.control
                                            type="text"
                                            name="code"
                                            id="code"
                                            rules="required"
                                            :label="trans('admin::app.settings.currencies.create.code')"
                                            :placeholder="trans('admin::app.settings.currencies.create.code')"
                                        >
                                        </x-admin::form.control-group.control>

                                        <x-admin::form.control-group.error
                                            control-name="code"
                                        >
                                        </x-admin::form.control-group.error>
                                    </x-admin::form.control-group>

                                    <x-admin::form.control-group class="mb-[10px]">
                                        <x-admin::form.control-group.label>
                                            @lang('admin::app.settings.currencies.create.name')
                                        </x-admin::form.control-group.label>

                                        <x-admin::form.control-group.control
                                            type="text"
                                            name="name"
                                            id="name"
                                            rules="required"
                                            :label="trans('admin::app.settings.currencies.create.name')"
                                            :placeholder="trans('admin::app.settings.currencies.create.name')"
                                        >
                                        </x-admin::form.control-group.control>

                                        <x-admin::form.control-group.error
                                            control-name="name"
                                        >
                                        </x-admin::form.control-group.error>
                                    </x-admin::form.control-group>

                                    <x-admin::form.control-group class="mb-[10px]">
                                        <x-admin::form.control-group.label>
                                            @lang('admin::app.settings.currencies.create.symbol')
                                        </x-admin::form.control-group.label>

                                        <x-admin::form.control-group.control
                                            type="text"
                                            name="symbol"
                                            id="symbol"
                                            :label="trans('admin::app.settings.currencies.create.symbol')"
                                            :placeholder="trans('admin::app.settings.currencies.create.symbol')"
                                        >
                                        </x-admin::form.control-group.control>

                                        <x-admin::form.control-group.error
                                            control-name="symbol"
                                        >
                                        </x-admin::form.control-group.error>
                                    </x-admin::form.control-group>

                                    <x-admin::form.control-group class="mb-[10px]">
                                        <x-admin::form.control-group.label>
                                            @lang('admin::app.settings.currencies.create.decimal')
                                        </x-admin::form.control-group.label>

                                        <x-admin::form.control-group.control
                                            type="text"
                                            name="decimal"
                                            id="decimal"
                                            :label="trans('admin::app.settings.currencies.create.decimal')"
                                            :placeholder="trans('admin::app.settings.currencies.create.decimal')"
                                        >
                                        </x-admin::form.control-group.control>

                                        <x-admin::form.control-group.error
                                            control-name="decimal"
                                        >
                                        </x-admin::form.control-group.error>
                                    </x-admin::form.control-group>

                                    {!! view_render_event('bagisto.admin.settings.currencies.create.after') !!}
                                </div>
                            </x-slot:content>

                            <x-slot:footer>
                                <div class="flex gap-x-[10px] items-center">
                                    <button 
                                        type="submit"
                                        class="px-[12px] py-[6px] bg-blue-600 border border-blue-700 rounded-[6px] text-gray-50 font-semibold cursor-pointer"
                                        v-if="isEditing"
                                    >
                                        @lang('admin::app.settings.currencies.create.save-btn')
                                    </button>

                                    <button 
                                        type="submit"
                                        class="px-[12px] py-[6px] bg-blue-600 border border-blue-700 rounded-[6px] text-gray-50 font-semibold cursor-pointer"
                                        v-else
                                    >
                                        @lang('admin::app.settings.currencies.edit.save-btn')
                                    </button>
                                </div>
                            </x-slot:footer>
                        </x-admin::modal>
                    </form>
                </x-admin::form>
            </div>
        </script>

        <script type="module">
            app.component('v-currencies-form', {
                template: '#v-currencies-form-template',

                data() {
                    return {
                        isEditing: false,
                    }
                },
                
                methods: {
                    store(params, { resetForm, setErrors }) {
                        if (params.id) {
                            this.$axios.post('{{ route('admin.currencies.update', ':id') }}'.replace(':id', params.id), params)
                            .then((response) => {
                                this.$emitter.emit('add-flash', { type: 'success', message: response.data.data.message });

                                this.$refs.currencyModal.close();

                                this.$root.$refs.vCurrencyDataGrid.$refs.datagrid.get();

                                this.isEditing = false;

                                resetForm();
                            }).catch((error) =>{
                                if (error.response.status == 422) {
                                    setErrors(error.response.data.errors);
                                }
                            });

                            return;
                        }
                        
                        this.$axios.post('{{ route('admin.currencies.store') }}', params)
                            .then((response) => {
                                this.$emitter.emit('add-flash', { type: 'success', message: response.data.data.message });

                                this.$refs.currencyModal.close();

                                this.$root.$refs.vCurrencyDataGrid.$refs.datagrid.get();

                                resetForm();
                            }).catch((error) =>{
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