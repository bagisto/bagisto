<x-shop::layouts>
    <!-- Page Title -->
    <x-slot:title>
        @lang('shop::app.components.layouts.cookie.consent.your-cookie-consent-preferences')
    </x-slot>

    {!! view_render_event('bagisto.shop.settings.gdpr.consent.before') !!}

    <v-cookie-consent ref="cookie-consent"></v-cookie-consent>

    {!! view_render_event('bagisto.shop.settings.gdpr.consent.after') !!}

    @pushOnce('scripts')
        <script
            type="text/x-template"
            id="v-cookie-consent-template"
        >
            {!! view_render_event('bagisto.shop.settings.gdpr.consent.form.before') !!}

            <x-shop::form
                v-slot="{ meta, errors, handleSubmit }"
                as="div"
            >
                <form @submit="handleSubmit($event, save)">
                    <div class="container mt-8 max-1180:px-5 max-md:mt-6 max-md:px-4">
                        <div class="m-auto w-full max-w-[870px] rounded-xl border border-zinc-200 p-16 px-[90px] max-md:px-8 max-md:py-8 max-sm:border-none max-sm:p-0">
                            <!-- Header -->
                            <div class="flex items-center justify-between gap-4 max-sm:flex-wrap">
                                <p class="text-xl font-bold">
                                    @lang('shop::app.components.layouts.cookie.consent.your-cookie-consent-preferences')
                                </p>

                                <x-shop::button
                                    class="primary-button"
                                    :title="trans('shop::app.components.layouts.cookie.consent.save-and-continue')"
                                />
                            </div>
                            
                            <div class="mt-14 rounded max-sm:mt-8">
                                <!-- Cookie Categories -->
                                @php
                                    $cookieConsentKeys = [
                                        'basic_interaction'      => 'basic-interactions',
                                        'experience_enhancement' => 'experience-enhancement',
                                        'measurements'           => 'measurements',
                                        'targeting_advertising'  => 'targeting-and-advertising'
                                    ];
                                @endphp

                                <!-- Strictly Necessary -->
                                <x-shop::form.control-group class="mb-4 border-b pb-4">
                                    <x-shop::form.control-group.label class="required font-semibold">
                                        @lang('shop::app.components.layouts.cookie.consent.strictly-necessary')
                                    </x-shop::form.control-group.label>

                                    <x-shop::form.control-group.control
                                        type="hidden"
                                        name="id"
                                        ::value="cookieId"
                                    />

                                    <div class="flex items-start gap-2">
                                        <x-shop::form.control-group.control
                                            type="checkbox"
                                            name="strictly_necessary"
                                            id="strictly_necessary"
                                            value="1"
                                            rules="required"
                                        />

                                        <label
                                            class="cursor-pointer select-none max-sm:text-sm"
                                            for="strictly_necessary"
                                        >
                                            {{ core()->getConfigData('general.gdpr.cookie_consent.strictly_necessary') }}
                                        </label>
                                    </div>

                                    <x-shop::form.control-group.error control-name="strictly_necessary" />
                                </x-shop::form.control-group> 
                                
                                @foreach ($cookieConsentKeys as $key => $label)
                                    <x-shop::form.control-group class="mb-4 border-b pb-4">
                                        <x-shop::form.control-group.label class="font-semibold">
                                            @lang('shop::app.components.layouts.cookie.consent.' . $label)
                                        </x-shop::form.control-group.label>

                                        <div class="flex items-start gap-2">
                                            <x-shop::form.control-group.control
                                                type="hidden"
                                                name="{{ $key }}"
                                                value="0"
                                            />

                                            <x-shop::form.control-group.control
                                                type="checkbox"
                                                name="{{ $key }}"
                                                id="{{ $key }}"
                                                value="1"
                                            />

                                            <label
                                                class="cursor-pointer select-none max-sm:text-sm"
                                                for="{{ $key }}"
                                            >
                                                {{ core()->getConfigData('general.gdpr.cookie_consent.' . $key) }}
                                            </label>
                                        </div>
                                    </x-shop::form.control-group>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </form>
            </x-shop::form>

            {!! view_render_event('bagisto.shop.settings.gdpr.consent.form.after') !!}
        </script>

        <script type="module">
            const COOKIE_DOMAIN = '{{ config('session.domain') ?? request()->getHost() }}';

            app.component('v-cookie-consent', {
                template: '#v-cookie-consent-template',

                data() {
                    return {
                        cookieKeys: [
                            'strictly_necessary',
                            'basic_interactions',
                            'experience_enhancement',
                            'measurements',
                            'targeting_advertising'
                        ],

                        cookieId: '',
                    };
                },

                mounted() {
                    this.loadSavedCookies();
                },

                methods: {
                    loadSavedCookies() {
                        const cookies = document.cookie.split(';').reduce((acc, cookie) => {
                            const [name, ...rest] = cookie.trim().split('=');
                            const value = rest.join('=');
                            acc[name] = decodeURIComponent(value);
                            return acc;
                        }, {});

                        this.cookieKeys.forEach(id => {
                            const element = document.getElementById(id);
                            if (element) {
                                element.checked = cookies[id] === "true";
                            }
                        });
                    },

                    save(params) {
                        this.$axios.post("{{ route('shop.customers.gdpr.cookie_consent_store') }}", params)
                            .then((response) => {
                                this.cookieId = response.data.data.id;

                                this.isLoading = false;

                                this.$emitter.emit('add-flash', { type: 'success', message: response.data.data.message });

                                this.cookieKeys.forEach(id => {
                                    const isChecked = document.getElementById(id)?.checked || false;

                                    this.setCookie(id, isChecked, 365 * 20);
                                });
                            })
                            .catch((error) => {
                                this.isLoading = false;

                                if (error.response.status == 422) {
                                    setErrors(error.response.data.errors);

                                    return;
                                }

                                this.$emitter.emit('add-flash', { type: 'error', message: error.response.data.message });
                            });
                    },

                    setCookie(name, value, expirationInDays) {
                        const date = new Date();
                        date.setTime(date.getTime() + expirationInDays * 24 * 60 * 60 * 1000);

                        let cookieString = `${name}=${value};expires=${date.toUTCString()};domain=${COOKIE_DOMAIN};path=/`;

                        if ({{ config('session.secure') ? 'true' : 'false' }}) {
                            cookieString += ';secure';
                        }

                        if ('{{ config('session.same_site') }}') {
                            cookieString += `;samesite={{ config('session.same_site') }}`;
                        }

                        document.cookie = cookieString;
                    }
                }
            });
        </script>
    @endPushOnce
</x-shop::layouts>
