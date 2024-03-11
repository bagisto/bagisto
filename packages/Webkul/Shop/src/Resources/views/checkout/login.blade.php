<!-- Checkout Login Vue JS Component -->
<v-checkout-login>
    <div class="flex items-center">
        <span class="text-base font-medium text-[#0A49A7] cursor-pointer">
            @lang('shop::app.checkout.login.title')
        </span>
    </div>
</v-checkout-login>

@pushOnce('scripts')
    <script
        type="text/x-template"
        id="v-checkout-login-template"
    >
        <div class="flex items-center">
            <span
                class="text-base font-medium text-[#0A49A7] cursor-pointer"
                role="button"
                @click="$refs.loginModel.open()"
            >
                @lang('shop::app.checkout.login.title')
            </span>

            <!-- Login Form -->
            <x-shop::form
                v-slot="{ meta, errors, handleSubmit }"
                as="div"
            >
                {!! view_render_event('bagisto.shop.checkout.login.before') !!}

                <!-- Login form -->
                <form @submit="handleSubmit($event, login)">
                    {!! view_render_event('bagisto.shop.checkout.login.form_controls.before') !!}

                    <!-- Login modal -->
                    <x-shop::modal ref="loginModel">
                        <!-- Modal Header -->
                        <x-slot:header>
                            <h2 class="text-2xl font-medium max-sm:text-xl">
                                @lang('shop::app.checkout.login.title')
                            </h2>
                        </x-slot>

                        <!-- Modal Content -->
                        <x-slot:content>
                            <x-shop::form.control-group>
                                <x-shop::form.control-group.label class="required">
                                    @lang('shop::app.checkout.login.email')
                                </x-shop::form.control-group.label>

                                <x-shop::form.control-group.control
                                    type="email"
                                    class="py-5 px-6"
                                    name="email"
                                    rules="required|email"
                                    :label="trans('shop::app.checkout.login.email')"
                                    placeholder="email@example.com"
                                    aria-label="@lang('shop::app.checkout.login.email')"
                                    aria-required="true"
                                />

                                <x-shop::form.control-group.error control-name="email" />
                            </x-shop::form.control-group>

                            <x-shop::form.control-group class="!mb-0">
                                <x-shop::form.control-group.label class="required">
                                    @lang('shop::app.checkout.login.password')
                                </x-shop::form.control-group.label>

                                <x-shop::form.control-group.control
                                    type="password"
                                    class="py-5 px-6"
                                    id="password"
                                    name="password"
                                    rules="required|min:6"
                                    :label="trans('shop::app.checkout.login.password')"
                                    :placeholder="trans('shop::app.checkout.login.password')"
                                    aria-label="@lang('shop::app.checkout.login.password')"
                                    aria-required="true"
                                />

                                <x-shop::form.control-group.error control-name="password" />
                            </x-shop::form.control-group>
                        </x-slot>

                        <!-- Modal Footer -->
                        <x-slot:footer>
                            <div class="flex items-center gap-4 flex-wrap">
                                <x-shop::button
                                    class="primary-button flex-auto max-w-none py-3 px-11 rounded-2xl"
                                    :title="trans('shop::app.checkout.login.title')"
                                    ::loading="isStoring"
                                    ::disabled="isStoring"
                                />
                            </div>
                        </x-slot>
                    </x-shop::modal>

                    {!! view_render_event('bagisto.shop.checkout.login.form_controls.after') !!}
                </form>
            </x-shop::form>

            {!! view_render_event('bagisto.shop.checkout.login.after') !!}
        </div>
    </script>

    <script type="module">
        app.component('v-checkout-login', {
            template: '#v-checkout-login-template',
            
            data() {
                return {
                    isStoring: false,
                }
            },

            methods: {
                login(params, { resetForm }) {
                    this.isStoring = true;

                    this.$axios.post("{{ route('shop.api.customers.session.create') }}", params)
                        .then((response) => {
                            this.isStoring = false;

                            window.location.reload();
                        })
                        .catch((error) => {
                            this.isStoring = false;

                            if (error.response.status == 422) {
                                setErrors(error.response.data.errors);

                                return;
                            }

                            this.$emitter.emit('add-flash', { type: 'error', message: error.response.data.message });
                        });
                },
            }
        })
    </script>
@endPushOnce