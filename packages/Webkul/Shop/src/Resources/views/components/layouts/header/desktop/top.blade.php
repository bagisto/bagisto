<div class="flex justify-between items-center w-full border border-t-0 border-b-[1px] border-l-0 border-r-0 py-[11px] px-16">
    {{-- Currency Switcher --}}
    <x-shop::dropdown>
        <!-- Dropdown Toggler -->
        <x-slot:toggle>
            <div class="flex gap-[10px] cursor-pointer">
                <span>
                    {{ core()->getCurrentCurrency()->symbol . ' ' . core()->getCurrentCurrencyCode() }}
                </span>

                <span class="text-[24px] icon-arrow-down"></span>
            </div>
        </x-slot:toggle>

        <!-- Dropdown Content -->
        <x-slot:content class="!p-[0px]">
            <v-currency-switcher></v-currency-switcher>
        </x-slot:content>
    </x-shop::dropdown>

    <p class="text-xs font-medium">Get UPTO 40% OFF on your 1st order <a href="#" class="underline">SHOP NOW</a></p>

    {{-- Locales Switcher --}}
    <x-shop::dropdown position="bottom-right">
        <x-slot:toggle>
            {{-- Dropdown Toggler --}}
            <div class="flex gap-[10px] cursor-pointer">
                @if (! empty(core()->getCurrentLocale()->image_url))
                    <img 
                        src="{{ core()->getCurrentLocale()->image_url }}"
                        alt="Default locale"
                        width="20"
                        height="20"
                    />
                @else
                    <img 
                        src="{{ asset('/themes/velocity/assets/images/flags/default-locale-image.png') }}"
                        alt="Default locale" 
                        width="20"
                        height="20"
                    />
                @endif
                
                <span>
                    {{ core()->getCurrentChannel()->locales()->orderBy('name')->where('code', app()->getLocale())->value('name') }}
                </span>

                <span class="text-[24px] icon-arrow-down"></span>
            </div>
        </x-slot:toggle>
    
        <!-- Dropdown Content -->
        <x-slot:content class="!p-[0px]">
            <v-locale-switcher></v-locale-switcher>
        </x-slot:content>
    </x-shop::dropdown>
</div>

@pushOnce('scripts')
    <script type="text/x-template" id="v-currency-switcher-template">
        <div class="grid gap-[4px] mt-[10px] pb-[10px]">
            <span
                class="text-[16px] px-5 py-2 cursor-pointer hover:bg-gray-100"
                v-for="currency in currencies"
                :class="{'bg-gray-100': currency.code == '{{ core()->getCurrentCurrencyCode() }}'}"
                @click="change(currency)"
            >
                @{{ currency.symbol + ' ' + currency.code }}
            </span>
        </div>
    </script>

    <script type="text/x-template" id="v-locale-switcher-template">
        <div class="grid gap-[4px] mt-[10px] pb-[10px]">
            <span
                class="flex gap-[10px] text-[16px] px-5 py-2 cursor-pointer hover:bg-gray-100"
                v-for="locale in locales"
                :class="{'bg-gray-100': locale.code == '{{ app()->getLocale() }}'}"
                @click="change(locale)"                  
            >
                <img 
                    :src="locale.image_url"
                    width="20"
                    height="20"
                />

                @{{ locale.name }}
            </span>
        </div>
    </script>

    <script type="module">
        app.component('v-currency-switcher', {
            template: '#v-currency-switcher-template',

            data() {
                return {
                    currencies: @json(core()->getCurrentChannel()->currencies),
                };
            },

            methods: {
                change(currency) {
                    let url = new URL(window.location.href);

                    url.searchParams.set('currency', currency.code);

                    window.location.href = url.href;
                }
            }
        });

        app.component('v-locale-switcher', {
            template: '#v-locale-switcher-template',

            data() {
                return {
                    locales: @json(core()->getCurrentChannel()->locales()->orderBy('name')->get()),
                };
            },

            methods: {
                change(locale) {
                    let url = new URL(window.location.href);

                    url.searchParams.set('locale', locale.code);

                    window.location.href = url.href;
                }
            }
        });
    </script>
@endPushOnce
