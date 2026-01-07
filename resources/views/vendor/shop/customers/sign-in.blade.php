{{-- RAM Plaza - Customer Login --}}
{{-- Simplified OAuth-only login (like Google ecosystem) --}}
{{-- Override: resources/views/vendor/shop/customers/sign-in.blade.php --}}

@push('meta')
    <meta name="description" content="@lang('shop::app.customers.login-form.page-title')"/>
    <meta name="keywords" content="@lang('shop::app.customers.login-form.page-title')"/>
@endPush

@push('styles')
    <link rel="stylesheet" href="{{ asset('ram-assets/css/login.css') }}">
    <style>
        .ram-oauth-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            width: 100%;
            padding: 16px 24px;
            background: linear-gradient(135deg, #ff3e9a 0%, #ff66b6 100%);
            border: none;
            border-radius: 12px;
            color: white;
            font-size: 16px;
            font-weight: 600;
            text-decoration: none;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(255, 62, 154, 0.3);
        }
        .ram-oauth-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(255, 62, 154, 0.4);
            color: white;
        }
        .ram-oauth-btn svg {
            width: 24px;
            height: 24px;
        }
        .ram-welcome-subtitle {
            color: rgba(255, 255, 255, 0.7);
            font-size: 14px;
            margin-bottom: 32px;
            text-align: center;
        }
    </style>
@endpush

<x-shop::layouts
    :has-header="false"
    :has-feature="false"
    :has-footer="false"
>
    <x-slot:title>
        RAM Plaza - Iniciar Sesion
    </x-slot>

    <div class="ram-login-wrapper">
        {{-- Left Side - Branding --}}
        <div class="ram-login-left">
            <div class="ram-login-branding">
                <a href="https://redactivamexico.net" class="ram-logo">
                    <img src="https://redactivamexico.net/themes/sunshine/img/night-logo.png" alt="RAM Logo">
                </a>
                <h1><b>#</b>RAMPlaza</h1>
                <p class="ram-tagline">La <b>plaza en linea</b> de <b>RedActivaMexico</b></p>

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
                    <span>MX</span> Hecho en Mexico
                </div>
            </div>
        </div>

        {{-- Right Side - OAuth Login --}}
        <div class="ram-login-right">
            <div class="ram-login-form-container">
                {{-- Plaza Icon --}}
                <div class="ram-plaza-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path><line x1="3" y1="6" x2="21" y2="6"></line><path d="M16 10a4 4 0 0 1-8 0"></path></svg>
                </div>

                <p class="ram-welcome-title">Bienvenido a RAM Plaza</p>
                <p class="ram-welcome-subtitle">Inicia sesion con tu cuenta de RedActivaMexico</p>

                {{-- Single OAuth Button --}}
                <a href="{{ route('customer.social-login.index', 'ram') }}" class="ram-oauth-btn">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"></path>
                        <polyline points="10 17 15 12 10 7"></polyline>
                        <line x1="15" y1="12" x2="3" y2="12"></line>
                    </svg>
                    Iniciar sesion
                </a>

                {{-- Help text --}}
                <p class="ram-welcome-subtitle" style="margin-top: 24px; margin-bottom: 0; font-size: 13px;">
                    Si no tienes cuenta, podras crearla en el siguiente paso
                </p>
            </div>
        </div>
    </div>
</x-shop::layouts>
