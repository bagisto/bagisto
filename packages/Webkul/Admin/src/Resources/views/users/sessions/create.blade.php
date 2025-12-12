<x-admin::layouts.anonymous>
    <!-- Page Title -->
    <x-slot:title>
        RAM Plaza - Admin
    </x-slot>

    <div class="ram-login-wrapper">
        <!-- Left Side - Branding -->
        <div class="ram-login-left">
            <div class="ram-login-branding">
                <a href="https://redactivamexico.net" class="ram-logo">
                    <img src="https://redactivamexico.net/themes/sunshine/img/night-logo.png" alt="RAM Logo">
                </a>
                <h1><b>#</b>RAMPlaza</h1>
                <p class="ram-tagline">El <b>e-commerce</b> de <b>RedActivaMéxico</b></p>

                <!-- E-commerce Features -->
                <div class="ram-features">
                    <div class="ram-feature">
                        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><line x1="3" y1="9" x2="21" y2="9"></line><line x1="9" y1="21" x2="9" y2="9"></line></svg>
                        <span>Gestiona tu tienda</span>
                    </div>
                    <div class="ram-feature">
                        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 12 20 22 4 22 4 12"></polyline><rect x="2" y="7" width="20" height="5"></rect><line x1="12" y1="22" x2="12" y2="7"></line><path d="M12 7H7.5a2.5 2.5 0 0 1 0-5C11 2 12 7 12 7z"></path><path d="M12 7h4.5a2.5 2.5 0 0 0 0-5C13 2 12 7 12 7z"></path></svg>
                        <span>Cupones y promociones</span>
                    </div>
                    <div class="ram-feature">
                        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                        <span>Conectado con RAM</span>
                    </div>
                </div>

                <!-- Badges -->
                <div class="ram-badge">
                    <span>🔒</span> Acceso Seguro
                </div>
                <div class="ram-badge">
                    <span>🇲🇽</span> Hecho en México
                </div>
            </div>
        </div>

        <!-- Right Side - Login Form -->
        <div class="ram-login-right">
            <div class="ram-login-form-container">
                <!-- Admin Icon - Gear/Settings -->
                <div class="ram-plaza-icon ram-plaza-icon-admin">
                    <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="3"></circle><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path></svg>
                </div>

                <x-admin::form :action="route('admin.session.store')">
                    <p class="ram-welcome-title">Panel de Administración</p>
                    <p class="ram-welcome-desc">Gestiona tu tienda en RAMPlaza</p>

                    <div class="ram-form-fields">
                        <!-- Email -->
                        <div class="ram-form-group">
                            <label for="email">Correo electrónico</label>
                            <x-admin::form.control-group.control
                                type="email"
                                class="ram-input"
                                id="email"
                                name="email"
                                rules="required|email"
                                :label="trans('admin::app.users.sessions.email')"
                                placeholder="tu@correo.com"
                            />
                            <x-admin::form.control-group.error control-name="email" />
                        </div>

                        <!-- Password -->
                        <div class="ram-form-group">
                            <label for="password">Contraseña</label>
                            <div class="ram-password-wrapper">
                                <x-admin::form.control-group.control
                                    type="password"
                                    class="ram-input"
                                    id="password"
                                    name="password"
                                    rules="required|min:6"
                                    :label="trans('admin::app.users.sessions.password')"
                                    placeholder="••••••••"
                                />
                                <span
                                    class="icon-view ram-eye-icon"
                                    onclick="switchVisibility()"
                                    id="visibilityIcon"
                                    role="presentation"
                                    tabindex="0"
                                ></span>
                            </div>
                            <x-admin::form.control-group.error control-name="password" />
                        </div>
                    </div>

                    <div class="ram-form-footer">
                        <a href="{{ route('admin.forget_password.create') }}" class="ram-forgot-link">
                            ¿Olvidaste tu contraseña?
                        </a>
                    </div>

                    <button type="submit" class="ram-submit-btn">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                        Acceder al panel
                    </button>
                </x-admin::form>

                <!-- Footer -->
                <div class="ram-login-footer">
                    <span>RAM Plaza</span> — e-commerce de
                    <a href="https://redactivamexico.net">RedActivaMéxico</a>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            function switchVisibility() {
                let passwordField = document.getElementById("password");
                let visibilityIcon = document.getElementById("visibilityIcon");
                passwordField.type = passwordField.type === "password" ? "text" : "password";
                visibilityIcon.classList.toggle("icon-view-close");
            }
        </script>
    @endpush
</x-admin::layouts.anonymous>
