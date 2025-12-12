{{-- RAM Plaza - Customer Login --}}
{{-- Same design as Admin login (plaza.redactivamexico.net/admin/login) --}}
{{-- Override: resources/views/vendor/shop/customers/sign-in.blade.php --}}

@push('meta')
    <meta name="description" content="@lang('shop::app.customers.login-form.page-title')"/>
    <meta name="keywords" content="@lang('shop::app.customers.login-form.page-title')"/>
@endPush

@push('styles')
    <link rel="stylesheet" href="{{ asset('ram-assets/css/login.css') }}">
@endpush

<x-shop::layouts
    :has-header="false"
    :has-feature="false"
    :has-footer="false"
>
    <x-slot:title>
        RAM Plaza - Iniciar Sesión
    </x-slot>

    <div class="ram-login-wrapper">
        {{-- Left Side - Branding --}}
        <div class="ram-login-left">
            <div class="ram-login-branding">
                <a href="https://redactivamexico.net" class="ram-logo">
                    <img src="https://redactivamexico.net/themes/sunshine/img/night-logo.png" alt="RAM Logo">
                </a>
                <h1><b>#</b>RAMPlaza</h1>
                <p class="ram-tagline">La <b>plaza en línea</b> de <b>RedActivaMéxico</b></p>

                {{-- Customer Features --}}
                <div class="ram-features">
                    <div class="ram-feature">
                        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="21" r="1"></circle><circle cx="20" cy="21" r="1"></circle><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path></svg>
                        <span>Compra productos locales</span>
                    </div>
                    <div class="ram-feature">
                        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 12 20 22 4 22 4 12"></polyline><rect x="2" y="7" width="20" height="5"></rect><line x1="12" y1="22" x2="12" y2="7"></line><path d="M12 7H7.5a2.5 2.5 0 0 1 0-5C11 2 12 7 12 7z"></path><path d="M12 7h4.5a2.5 2.5 0 0 0 0-5C13 2 12 7 12 7z"></path></svg>
                        <span>Ofertas exclusivas</span>
                    </div>
                    <div class="ram-feature">
                        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                        <span>Conectado con tu cuenta RAM</span>
                    </div>
                </div>

                {{-- Mexican badge --}}
                <div class="ram-badge">
                    <span>🇲🇽</span> Hecho en México
                </div>
            </div>
        </div>

        {{-- Right Side - Login Form --}}
        <div class="ram-login-right">
            <div class="ram-login-form-container">
                {{-- Plaza Icon --}}
                <div class="ram-plaza-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path><line x1="3" y1="6" x2="21" y2="6"></line><path d="M16 10a4 4 0 0 1-8 0"></path></svg>
                </div>

                {!! view_render_event('bagisto.shop.customers.login.before') !!}

                <x-shop::form :action="route('shop.customer.session.create')">
                    <p class="ram-welcome-title">Iniciar Sesión</p>
                    <p class="ram-welcome-desc">Accede a tu cuenta en RAMPlaza</p>

                    {!! view_render_event('bagisto.shop.customers.login_form_controls.before') !!}

                    <div class="ram-form-fields">
                        {{-- Email --}}
                        <div class="ram-form-group">
                            <label for="email">Correo electrónico</label>
                            <x-shop::form.control-group.control
                                type="email"
                                class="ram-input"
                                id="email"
                                name="email"
                                rules="required|email"
                                :label="trans('shop::app.customers.login-form.email')"
                                placeholder="tu@correo.com"
                                aria-required="true"
                            />
                            <x-shop::form.control-group.error control-name="email" />
                        </div>

                        {{-- Password --}}
                        <div class="ram-form-group">
                            <label for="password">Contraseña</label>
                            <div class="ram-password-wrapper">
                                <x-shop::form.control-group.control
                                    type="password"
                                    class="ram-input"
                                    id="password"
                                    name="password"
                                    rules="required|min:6"
                                    :label="trans('shop::app.customers.login-form.password')"
                                    placeholder="••••••••"
                                    aria-required="true"
                                />
                                <span
                                    class="icon-view ram-eye-icon"
                                    onclick="switchVisibility()"
                                    id="visibilityIcon"
                                    role="presentation"
                                    tabindex="0"
                                ></span>
                            </div>
                            <x-shop::form.control-group.error control-name="password" />
                        </div>
                    </div>

                    {{-- Captcha --}}
                    @if (core()->getConfigData('customer.captcha.credentials.status'))
                        <x-shop::form.control-group class="mt-5">
                            {!! \Webkul\Customer\Facades\Captcha::render() !!}
                            <x-shop::form.control-group.error control-name="g-recaptcha-response" />
                        </x-shop::form.control-group>
                    @endif

                    <div class="ram-form-footer">
                        <a href="{{ route('shop.customers.forgot_password.create') }}" class="ram-forgot-link">
                            ¿Olvidaste tu contraseña?
                        </a>
                    </div>

                    <button type="submit" class="ram-submit-btn">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"></path><polyline points="10 17 15 12 10 7"></polyline><line x1="15" y1="12" x2="3" y2="12"></line></svg>
                        Iniciar sesión
                    </button>

                    {!! view_render_event('bagisto.shop.customers.login_form_controls.after') !!}
                </x-shop::form>

                {!! view_render_event('bagisto.shop.customers.login.after') !!}

                {{-- Footer --}}
                <div class="ram-login-footer">
                    ¿No tienes cuenta? <a href="{{ route('shop.customers.register.index') }}">Regístrate aquí</a>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        {!! \Webkul\Customer\Facades\Captcha::renderJS() !!}

        <script>
            function switchVisibility() {
                let passwordField = document.getElementById("password");
                let visibilityIcon = document.getElementById("visibilityIcon");
                passwordField.type = passwordField.type === "password" ? "text" : "password";
                visibilityIcon.classList.toggle("icon-view-close");
            }
        </script>
    @endpush
</x-shop::layouts>
