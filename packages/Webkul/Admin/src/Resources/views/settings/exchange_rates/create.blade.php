<v-create-exchange-rates></v-create-exchange-rates>

@pushOnce('scripts')
    <script type="text/x-template" id="v-create-exchange-rates-template">
        <div>
            <x-admin::form
                v-slot="{ meta, errors, handleSubmit }"
                as="div"
            >
                <form @submit="handleSubmit($event, create)">
                    <!-- User Create Modal -->
                    <x-admin::modal ref="customerCreateModal">
                        <x-slot:toggle>
                            <!-- User Create Button -->
                            @if (bouncer()->hasPermission('settings.exchange_rates.create'))
                                <button 
                                    type="button"
                                    class="text-gray-50 font-semibold px-[12px] py-[6px] bg-blue-600 border border-blue-700 rounded-[6px] cursor-pointer"
                                >     
                                    @lang('admin::app.settings.exchange-rates.create.button-title')
                                </button>
                            @endif
                        </x-slot:toggle>
        
                        <x-slot:header>
                            <!-- Modal Header -->
                            <p class="text-[18px] text-gray-800 font-bold">
                                @lang('admin::app.settings.exchange-rates.create.title')
                            </p>    
                        </x-slot:header>
        
                        <x-slot:content>
                            <!-- Modal Content -->
                            <div class="px-[16px] py-[10px]  border-b-[1px] border-gray-300">
                                {!! view_render_event('bagisto.admin.settings.exchangerate.create.before') !!}

                                <div class="block leading-[24px] text-[12px] text-gray-800 font-medium mb-[10px]">
                                    @lang('admin::app.settings.exchange-rates.create.source-currency')

                                    <p class="text-[14px] text-gray-500 font-medium">
                                        {{ core()->getBaseCurrencyCode() }}
                                    </p>
                                </div>    
                                <x-admin::form.control-group class="mb-[10px]">
                                    <x-admin::form.control-group.label>
                                        @lang('admin::app.settings.exchange-rates.create.target-currency')
                                    </x-admin::form.control-group.label>
                                  
                                    <x-admin::form.control-group.control
                                        type="select"
                                        name="target_currency" 
                                        rules="required"
                                        label="Target Currency"
                                    >
                                        @foreach ($currencies as $currency)
                                            @if (is_null($currency->exchange_rate))
                                                <option value="{{ $currency->id }}">{{ $currency->name }}</option>
                                            @endif
                                        @endforeach
                                    </x-admin::form.control-group.control>
        
                                    <x-admin::form.control-group.error
                                        control-name="target_currency"
                                    >
                                    </x-admin::form.control-group.error>
                                </x-admin::form.control-group>

                                <x-admin::form.control-group class="mb-[10px]">
                                    <x-admin::form.control-group.label>
                                        @lang('admin::app.settings.exchange-rates.create.rate')
                                    </x-admin::form.control-group.label>
            
                                    <x-admin::form.control-group.control
                                        type="text"
                                        name="rate"
                                        id="rate"
                                        :value="old('rate')"
                                        rules="required"
                                        label="Rate"
                                        placeholder="Rate"
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
                            <!-- Modal Submission -->
                            <div class="flex gap-x-[10px] items-center">
                                <button 
                                    type="submit"
                                    class="px-[12px] py-[6px] bg-blue-600 border border-blue-700 rounded-[6px] text-gray-50 font-semibold cursor-pointer"
                                >
                                    @lang('admin::app.settings.exchange-rates.create.save')
                                </button>
                            </div>
                        </x-slot:footer>
                    </x-admin::modal>
                </form>
            </x-admin::form>
        </div>
    </script>

    <script type="module">
        app.component('v-create-exchange-rates', {
            template: '#v-create-exchange-rates-template',

            methods: {
                create(params, { resetForm, setErrors }) {
                   
                    this.$axios.post("{{ route('admin.exchange_rates.store')  }}", params)
                        .then((response) => {
                            this.$refs.customerCreateModal.toggle();

                            resetForm();
                        })
                        .catch(error => {
                            if (error.response.status == 422) {
                                setErrors(error.response.data.errors);
                            }
                        });
                }
            }
        })
    </script>
@endPushOnce