<x-admin::layouts>
    <x-slot:title>
        @lang('admin::app.account.edit.title')
    </x-slot>

    <v-two-factor-setup></v-two-factor-setup>

    <!-- Input Form -->
    @pushOnce('scripts')
        <script type="text/x-template" id="v-two-factor-setup-template">
            <x-admin::form
                :action="route('admin.account.update')"
                enctype="multipart/form-data"
                method="PUT"
            >
                <div class="flex items-center justify-between gap-4 max-sm:flex-wrap">
                    <p class="text-xl font-bold text-gray-800 dark:text-white">
                        @lang('admin::app.account.edit.title')
                    </p>

                    <div class="flex items-center gap-x-2.5">
                        <!-- Back Button -->
                        <a
                            href="{{ route('admin.dashboard.index') }}"
                            class="transparent-button hover:bg-gray-200 dark:text-white dark:hover:bg-gray-800"
                        >
                            @lang('admin::app.account.edit.back-btn')
                        </a>

                        <!-- Save Button -->
                        <div class="flex items-center gap-x-2.5">
                            <button 
                                type="submit"
                                class="primary-button"
                            >
                                @lang('admin::app.account.edit.save-btn')
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Full Panel -->
                <div class="mt-3.5 flex gap-2.5 max-xl:flex-wrap">
                    <!-- Left sub Component -->
                    <div class="flex flex-1 flex-col gap-2">
                        <!-- General -->
                        <div class="box-shadow rounded bg-white p-4 dark:bg-gray-900">
                            <p class="mb-4 text-base font-semibold text-gray-800 dark:text-white">
                                @lang('admin::app.account.edit.general')
                            </p>

                            <!-- Image -->
                            <x-admin::form.control-group>
                                <x-admin::media.images
                                    name="image"
                                    :uploaded-images="$user->image ? [['id' => 'image', 'url' => $user->image_url]] : []"
                                />
                            </x-admin::form.control-group>

                            <p class="mb-4 text-xs text-gray-600 dark:text-gray-300">
                                @lang('admin::app.account.edit.upload-image-info')
                            </p>

                            <!-- Name -->
                            <x-admin::form.control-group>
                                <x-admin::form.control-group.label class="required">
                                    @lang('admin::app.account.edit.name')
                                </x-admin::form.control-group.label>

                                <x-admin::form.control-group.control
                                    type="text"
                                    name="name"
                                    rules="required"
                                    :value="old('name') ?: $user->name"
                                    :label="trans('admin::app.account.edit.name')"
                                    :placeholder="trans('admin::app.account.edit.name')"
                                />

                                <x-admin::form.control-group.error control-name="name" />
                            </x-admin::form.control-group>

                            <!-- Email -->
                            <x-admin::form.control-group class="!mb-0">
                                <x-admin::form.control-group.label class="required">
                                    @lang('admin::app.account.edit.email')
                                </x-admin::form.control-group.label>

                                <x-admin::form.control-group.control
                                    type="email"
                                    name="email"
                                    id="email"
                                    rules="required"
                                    :value="old('email') ?: $user->email"
                                    :label="trans('admin::app.account.edit.email')"
                                />

                                <x-admin::form.control-group.error control-name="email" />
                            </x-admin::form.control-group>
                        </div>
                    </div>

                    <!-- Right sub-component -->
                    <div class="flex w-[360px] max-w-full flex-col gap-2 max-md:w-full">
                        <x-admin::accordion>
                            <x-slot:header>
                                <p class="p-2.5 text-base font-semibold text-gray-800 dark:text-white">
                                    @lang('admin::app.account.edit.change-password')
                                </p>
                            </x-slot>

                            <!-- Change Account Password -->
                            <x-slot:content>
                                <!-- Current Password -->
                                <x-admin::form.control-group>
                                    <x-admin::form.control-group.label class="required">
                                        @lang('admin::app.account.edit.current-password')
                                    </x-admin::form.control-group.label>

                                    <x-admin::form.control-group.control
                                        type="password"
                                        name="current_password"
                                        rules="required|min:6"
                                        :label="trans('admin::app.account.edit.current-password')"
                                        :placeholder="trans('admin::app.account.edit.current-password')"
                                    />

                                    <x-admin::form.control-group.error control-name="current_password" />
                                </x-admin::form.control-group>

                                <!-- Password -->
                                <x-admin::form.control-group>
                                    <x-admin::form.control-group.label>
                                        @lang('admin::app.account.edit.password')
                                    </x-admin::form.control-group.label>

                                    <x-admin::form.control-group.control
                                        type="password"
                                        name="password"
                                        rules="min:6"
                                        :placeholder="trans('admin::app.account.edit.password')"
                                        ref="password"
                                    />

                                    <x-admin::form.control-group.error control-name="password" />
                                </x-admin::form.control-group>

                                <!-- Confirm Password -->
                                <x-admin::form.control-group class="!mb-0">
                                    <x-admin::form.control-group.label>
                                        @lang('admin::app.account.edit.confirm-password')
                                    </x-admin::form.control-group.label>

                                    <x-admin::form.control-group.control
                                        type="password"
                                        name="password_confirmation"
                                        rules="confirmed:@password"
                                        :label="trans('admin::app.account.edit.confirm-password')"
                                        :placeholder="trans('admin::app.account.edit.confirm-password')"
                                    />

                                    <x-admin::form.control-group.error control-name="password_confirmation" />
                                </x-admin::form.control-group>
                            
                            </x-slot>
                        </x-admin::accordion>
                        
                        <div class="flex w-[360px] max-w-full flex-col gap-2 max-md:w-full">
                            <x-admin::accordion>
                                <x-slot:header>
                                    <p class="p-2.5 text-base font-semibold text-gray-800 dark:text-white">
                                        @lang('admin::app.account.setup.title')
                                    </p>
                                </x-slot>
                                
                                <x-slot:content>
                                    <x-admin::form.control-group>
                                        <x-admin::form.control-group.label>
                                            @lang('admin::app.account.setup.enable')
                                        </x-admin::form.control-group.label>

                                        <x-admin::form.control-group.control
                                            type="switch"
                                            name="two_factor_auth_enable"
                                            :value="1"
                                            :checked="$user->two_factor_enabled ? true : false"
                                            @change="handleToggleChange($event)"
                                        />
                                        
                                        <x-admin::form.control-group.error control-name="two_factor_auth_enable" />
                                    </x-admin::form.control-group>
                                </x-slot>
                            </x-admin::accordion>
                        </div>
                    </div>
                </div>
            </x-admin::form>

            <div>
                <x-admin::modal 
                    ref="twoFactorSetupModal" 
                    @toggle="setupModalClose()"
                >
                    <x-slot:header>
                        <p class="text-lg font-bold text-gray-800 dark:text-white">
                            @lang('admin::app.account.setup.title')
                        </p>
                    </x-slot>

                    <x-slot:content>
                        <div class="flex flex-col items-center gap-4">
                            <div 
                                class="flex flex-col items-center"
                                v-if="isLoading" 
                            >
                                <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
                            </div>

                            <div v-else>
                                <p class="text-sm text-gray-600 dark:text-gray-300 text-center">
                                    @lang('admin::app.account.setup.scan-qr')
                                </p>

                                <div 
                                    class="flex justify-center mt-4" 
                                    v-if="qrCodeSvg"
                                >
                                    <div v-html="qrCodeSvg"></div>
                                </div>

                                <x-admin::form 
                                    :action="route('admin.two_factor.enable')" 
                                    method="POST" 
                                    class="w-full mt-4"
                                >
                                    <x-admin::form.control-group>
                                        <x-admin::form.control-group.label class="required">
                                            @lang('admin::app.account.setup.code-label')
                                        </x-admin::form.control-group.label>

                                        <x-admin::form.control-group.control
                                            type="text"
                                            class="w-full"
                                            id="code"
                                            name="code"
                                            rules="required|numeric|digits:6"
                                            :label="trans('admin::app.account.setup.code-label')"
                                            :placeholder="trans('admin::app.account.setup.code-placeholder')"
                                        />

                                        <x-admin::form.control-group.error control-name="code" />
                                    </x-admin::form.control-group>

                                    <div class="mt-4 flex justify-end gap-4">
                                        <button
                                            class="cursor-pointer rounded-md border border-blue-700 bg-blue-600 px-3.5 py-1.5 font-semibold text-gray-50"
                                            aria-label="{{ trans('admin::app.account.setup.verify-enable') }}"
                                        >
                                            @lang('admin::app.account.setup.verify-enable')
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
            app.component('v-two-factor-setup', {
                template: '#v-two-factor-setup-template',

                props: {
                    modelValue: {
                        type: Boolean,
                        default: false,
                    },
                },

                data() {
                    return {    
                        qrCodeSvg: '',

                        isLoading: false,

                        originalToggleState: {{ $user->two_factor_enabled ? 'true' : 'false' }},

                        currentToggleElement: null,
                    };
                },

                watch: {
                    modelValue(newVal) {
                        if (newVal) {
                            this.openSetupModal();
                        }
                    }
                },

                methods: {
                    setupModalClose() {
                        if (this.currentToggleElement && !this.originalToggleState) {
                            this.currentToggleElement.checked = this.originalToggleState;
                        }

                        this.$emit('update:modelValue', false);
                    },

                    handleToggleChange(event) {
                        this.currentToggleElement = event.target;
                        
                        if (event.target.checked) {
                            this.openSetupModal();
                        } else {
                            this.disableTwoFactor();
                        }
                    },

                    disableTwoFactor() {
                        this.$axios.get(`{{ route('admin.two_factor.disable') }}`)
                            .then(response => {
                                this.originalToggleState = false;

                                this.$emitter.emit('add-flash', {
                                    type: 'success',
                                    message: response.data?.message
                                });
                            })
                            .catch(error => {
                                this.$emitter.emit('add-flash', {
                                    type: 'error',
                                    message: error.response?.data?.message
                                });

                                if (this.currentToggleElement) {
                                    this.currentToggleElement.checked = this.originalToggleState;
                                }
                            });
                    },

                    openSetupModal() {
                        this.$refs.twoFactorSetupModal.open();

                        this.isLoading = true;

                        this.$axios.get(`{{ route('admin.two_factor.setup') }}`)
                            .then(response => {
                                this.qrCodeSvg = response.data.qrCodeSvg;
                            })
                            .catch(error => {
                                this.$emitter.emit('add-flash', {
                                    type: 'error',
                                    message: error.response?.data?.message
                                });
                               
                                if (this.currentToggleElement) {
                                    this.currentToggleElement.checked = this.originalToggleState;
                                }
                            })
                            .finally(() => {
                                this.isLoading = false;
                            });
                    },

                    onTwoFactorEnabled() {
                        this.originalToggleState = true;

                        this.$refs.twoFactorSetupModal.close();
                    },
                }
            });
        </script>
    @endPushOnce
</x-admin::layouts>