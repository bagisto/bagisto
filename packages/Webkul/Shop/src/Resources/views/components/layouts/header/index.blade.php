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

@pushOnce('scripts')
    <script type="module">
        app.component('v-header-switcher', {
            data() {
                return {
                    isDesktop: window.innerWidth >= 1024
                }
            },
            mounted() {
                this.media = window.matchMedia('(min-width: 1024px)');
                this.media.addEventListener('change', this.handleMedia);
            },
            beforeUnmount() {
                this.media.removeEventListener('change', this.handleMedia);
            },
            methods: {
                handleMedia(e) {
                    this.isDesktop = e.matches;
                }
            },
            render() {
                console.log(this.isDesktop);
                return this.isDesktop
                    ? this.$h(this.$resolveComponent('v-desktop-header'))
                    : this.$h(this.$resolveComponent('v-mobile-header'));
            }
        });

        app.component('v-desktop-header', {
            template: '#v-desktop-header-template'
        });

        app.component('v-mobile-header', {
            template: '#v-mobile-header-template'
        });
    </script>

    <script type="text/x-template" id="v-desktop-header-template">
        <x-shop::layouts.header.desktop />
    </script>

    <script type="text/x-template" id="v-mobile-header-template">
        <x-shop::layouts.header.mobile />
    </script>
@endPushOnce
