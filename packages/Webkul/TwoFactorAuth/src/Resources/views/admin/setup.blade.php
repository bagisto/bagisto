@pushOnce('scripts')
    <script type="text/x-template" id="v-twofactor-setup-template">
        <div>
            <x-admin::form.control-group class="mb-2.5">
                <x-admin::form.control-group.label>
                    @lang('two_factor_auth::app.configuration.index.general.two_factor_auth.settings.enabled')
                </x-admin::form.control-group.label>

                <x-admin::form.control-group.control
                    type="switch"
                    name="two_factor_auth_enable"
                    :value="1"
                    v-model="two_factor_auth_enable"
                    ::checked="two_factor_auth_enable"
                    @change="change()"
                />
                
                <x-admin::form.control-group.error control-name="two_factor_auth_enable" />
            </x-admin::form.control-group>

            <x-admin::modal ref="twoFactorSetupModal">
                <!-- Modal Header -->
                <x-slot:header>
                    <p class="text-lg font-bold text-gray-800 dark:text-white">
                        @lang('two_factor_auth::app.setup.title')
                    </p>
                </x-slot>

                <!-- Modal Content -->
                <x-slot:content>
                    <div class="flex flex-col items-center gap-4">
                        <!-- Loading State -->
                        <div v-if="isLoading" class="flex flex-col items-center">
                            <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
                        </div>

                        <!-- QR Code Content -->
                        <div v-else>
                            <p class="text-sm text-gray-600 dark:text-gray-300 text-center">
                                @lang('two_factor_auth::app.setup.scan_qr')
                            </p>

                            <div class="flex justify-center mt-4" v-if="qrCodeSvg">
                                <div v-html="qrCodeSvg"></div>
                            </div>

                            <x-admin::form :action="route('admin.twofactor.enable')" method="POST" class="w-full mt-4">
                                <x-admin::form.control-group>
                                    <x-admin::form.control-group.label class="required">
                                        @lang('two_factor_auth::app.setup.code_label')
                                    </x-admin::form.control-group.label>

                                    <x-admin::form.control-group.control
                                        type="text"
                                        class="w-full"
                                        id="code"
                                        name="code"
                                        rules="required|numeric|digits:6"
                                        :label="__('Verification Code')"
                                        :placeholder="__('Enter 6-digit code')"
                                    />

                                    <x-admin::form.control-group.error control-name="code" />
                                </x-admin::form.control-group>

                                <div class="mt-4 flex justify-end gap-4">
                                    <a
                                        class="transparent-button hover:bg-gray-200 px-3.5 py-1.5 font-semibold dark:text-white dark:hover:bg-gray-800"
                                        @click="closeModal()"
                                    >
                                        @lang('two_factor_auth::app.setup.back')
                                    </a>

                                    <button
                                        class="cursor-pointer rounded-md border border-blue-700 bg-blue-600 px-3.5 py-1.5 font-semibold text-gray-50"
                                        aria-label="{{ __('Verify & Enable') }}"
                                    >
                                        @lang('two_factor_auth::app.setup.verify_enable')
                                    </button>
                                </div>
                            </x-admin::form>
                        </div>
                    </div>
                </x-slot>
            </x-admin::modal>
        </div>
    </script>

    <script type="module">
        app.component('v-twofactor-setup', {
            template: '#v-twofactor-setup-template',
            data() {
                return {
                    two_factor_auth_enable: false,
                    qrCodeSvg: '',
                };
            },

            methods: {
                change() {
                    if (this.two_factor_auth_enable) {
                        this.$refs.twoFactorSetupModal.open();
                    
                        this.$axios.get(`{{ route('admin.twofactor.setup') }}`)
                            .then(response => {
                                this.qrCodeSvg = response.data.qrCodeSvg;
                            })
                            .catch(error => {
                                this.$emitter.emit('add-flash', {
                                    type: 'error',
                                    message: error.response?.data?.message
                                });
                            });
                    }
                },        

                closeModal() {
                    this.two_factor_auth_enable = false;
                    this.$refs.twoFactorSetupModal.close();
                }
            }
        });
    </script>
@endPushOnce