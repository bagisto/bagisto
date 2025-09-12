{!! view_render_event('bagisto.shop.layout.header.before') !!}

@if(core()->getCurrentChannel()->locales()->count() > 1 || core()->getCurrentChannel()->currencies()->count() > 1 )
    <div class="max-lg:hidden">
        <x-shop::layouts.header.desktop.top />
    </div>
@endif

<header class="shadow-gray sticky top-0 z-10 bg-white shadow-sm max-lg:shadow-none">
    <v-header-switcher></v-header-switcher>
</header>

{!! view_render_event('bagisto.shop.layout.header.after') !!}


{{-- Vue template --}}
@pushOnce('scripts')
    <script type="text/x-template" id="v-header-switcher-template">
        <component :is="currentHeader"></component>
    </script>

    <script type="module">
        app.component('v-header-switcher', {
            template: '#v-header-switcher-template',

            data() {
                return {
                    currentHeader: window.innerWidth >= 1024 ? 'v-desktop-header' : 'v-mobile-header'
                }
            },

            mounted() {
                const media = window.matchMedia('(min-width: 1024px)');
                this.handleMedia(media);
                media.addEventListener('change', this.handleMedia);
            },

            beforeUnmount() {
                window.matchMedia('(min-width: 1024px)')
                     .removeEventListener('change', this.handleMedia);
            },

            methods: {
                handleMedia(e) {
                    this.currentHeader = e.matches ? 'v-desktop-header' : 'v-mobile-header';
                }
            }
        });

        // Desktop header component
        app.component('v-desktop-header', {
            template: '#v-desktop-header-template'
        });

        // Mobile header component
        app.component('v-mobile-header', {
            template: '#v-mobile-header-template'
        });
    </script>

    {{-- Desktop template --}}
    <script type="text/x-template" id="v-desktop-header-template">
        <x-shop::layouts.header.desktop />
    </script>

    {{-- Mobile template --}}
    <script type="text/x-template" id="v-mobile-header-template">
        <x-shop::layouts.header.mobile />
    </script>
@endPushOnce
