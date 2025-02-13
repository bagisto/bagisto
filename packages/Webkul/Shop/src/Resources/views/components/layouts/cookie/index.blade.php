
{!! view_render_event('bagisto.shop.settings.gdpr.modal.before') !!}

<v-cookie></v-cookie>

{!! view_render_event('bagisto.shop.settings.gdpr.modal.before') !!}

@pushOnce('scripts')
    <script
        type="text/x-template"
        id="v-cookie-template"
    >
        {!! view_render_event('bagisto.shop.settings.gdpr.modal.cookie.before') !!}

            @if (core()->getConfigData('general.gdpr.cookie.enabled'))
                @if (core()->getConfigData('general.gdpr.cookie.position') == 'bottom_left')
                    <div 
                        class="js-cookie-consent fixed bottom-9 z-[999] box-border hidden min-h-5 w-[420px] overflow-hidden rounded bg-black/90 p-7" 
                        style="opacity: 1; left:25px;"
                    >
                @else
                    <div 
                        class="js-cookie-consent fixed bottom-9 z-[999] box-border hidden min-h-5 w-[420px] overflow-hidden rounded bg-black/90 p-7" 
                        style="opacity: 1; right:25px;"
                    >
                @endif
                    <div class="cookieTitle">
                        <span class="mb-1.5 block font-sans text-xl leading-5 text-white">
                            {{ core()->getConfigData('general.gdpr.cookie.static_block_identifier') }}
                        </span>
                    </div>

                    <div class="cookieDesc cookie-consent__message">
                        <p class="mt-2.5 block font-sans text-sm leading-5 text-white">
                            {{ core()->getConfigData('general.gdpr.cookie.description') }}

                            <a 
                                class="text-white underline" 
                                href="{{ url('page/privacy-policy') }}"
                            >
                                @lang('shop::app.components.layouts.cookie.index.privacy-policy')
                            </a>
                        </p>
                    </div>

                    <div class="cookieButton">
                        <a 
                            class="mt-2.5 box-border inline-block w-full cursor-pointer rounded bg-blue-500 px-6 py-4 text-center font-sans text-sm font-bold text-white transition-colors duration-300 ease-in-out hover:bg-blue-400 hover:text-white"
                            @click="rejectCookie()"
                            style="width:49%;"
                        >
                            @lang('shop::app.components.layouts.cookie.index.reject')
                        </a>

                        <a 
                            class="mt-2.5 box-border inline-block w-full cursor-pointer rounded bg-blue-500 px-6 py-4 text-center font-sans text-sm font-bold text-white transition-colors duration-300 ease-in-out hover:bg-blue-400 hover:text-white"
                            @click="createCookie()"
                            style="width:49%; margin-left:1%;"
                        >
                            @lang('shop::app.components.layouts.cookie.index.accept')
                        </a>

                        <a 
                            class="mt-2.5 box-border inline-block w-full cursor-pointer rounded bg-blue-500 px-6 py-4 text-center font-sans text-sm font-bold text-white transition-colors duration-300 ease-in-out hover:bg-blue-400 hover:text-white"
                            href="{{ route('shop.customers.account.gdpr.cookie-consent') }}"
                        >
                            @lang('shop::app.components.layouts.cookie.index.learn-more-and-customize')
                        </a>
                    </div>
                </div>
            @endif

        {!! view_render_event('bagisto.shop.settings.gdpr.modal.cookie.before') !!}
    </script>

    <script type="module">
        var secureFlag = {!! json_encode(config('session.secure') ? ';secure' : '') !!};
        var sameSiteFlag = {!! json_encode(config('session.same_site') ? ';samesite=' . config('session.same_site') : '') !!};

        app.component('v-cookie', {
            template: '#v-cookie-template',

            data() {
                return {
                    cookieDomain: '{{ config('session.domain') ?? request()->getHost() }}',
                    cookieIp: "{{ $_SERVER['REMOTE_ADDR'] }}"
                };
            },

            mounted() {
                if (! this.cookieExists()) {
                    this.showCookieDialog();
                }
            },

            methods: {
                createCookie() {
                    this.consentWithCookies();

                    this.acceptAllConsentWithCookies();

                    this.hideCookieDialog();
                },

                rejectCookie() {
                    this.hideCookieDialog();
                },

                setCookie(name, value, expirationInDays) {
                    const date = new Date();

                    date.setTime(date.getTime() + (expirationInDays * 24 * 60 * 60 * 1000));

                    document.cookie = `${name}=${value};expires=${date.toUTCString()};path=/${secureFlag}${sameSiteFlag}`;
                },

                consentWithCookies() {
                    this.setCookie('cookie-consent', 1, 365 * 20);
                },

                acceptAllConsentWithCookies() {
                    this.setCookie('ip_address', this.cookieIp, 365 * 20);
                },

                cookieExists() {
                    return document.cookie.includes(`cookie-consent=${1}`);
                },

                hideCookieDialog() {
                    const cookieConsentElement = document.querySelector('.js-cookie-consent');

                    if (cookieConsentElement) {
                        cookieConsentElement.style.display = 'none';
                    }
                },

                showCookieDialog() {
                    const cookieConsentElement = document.querySelector('.js-cookie-consent');

                    if (cookieConsentElement) {
                        cookieConsentElement.style.display = 'block';
                    }
                },
            },
        });
        </script>
@endPushOnce
