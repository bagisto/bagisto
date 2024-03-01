<!-- SEO Meta Content -->
@push('meta')
    <meta name="description" content="@lang('shop::app.checkout.onepage.index.checkout')"/>

    <meta name="keywords" content="@lang('shop::app.checkout.onepage.index.checkout')"/>
@endPush

<x-shop::layouts
    :has-header="false"
    :has-feature="false"
    :has-footer="false"
>
    <!-- Page Title -->
    <x-slot:title>
        @lang('shop::app.checkout.onepage.index.checkout')
    </x-slot>

    {!! view_render_event('bagisto.shop.checkout.onepage.header.before') !!}

    <!-- Page Header -->
    <div class="lex flex-wrap">
        <div class="w-full flex justify-between px-[60px] py-4 border border-t-0 border-b border-l-0 border-r-0 max-lg:px-8 max-sm:px-4">
            <div class="flex items-center gap-x-14 max-[1180px]:gap-x-9">
                <a
                    href="{{ route('shop.home.index') }}"
                    class="flex min-h-[30px]"
                    aria-label="@lang('shop::checkout.onepage.index.bagisto')"
                >
                    <img
                        src="{{ core()->getCurrentChannel()->logo_url ?? bagisto_asset('images/logo.svg') }}"
                        alt="{{ config('app.name') }}"
                        width="131"
                        height="29"
                    >
                </a>
            </div>
        </div>
    </div>

    {!! view_render_event('bagisto.shop.checkout.onepage.header.after') !!}

    <div class="container px-[60px] max-lg:px-8 max-sm:px-4">

        {!! view_render_event('bagisto.shop.checkout.onepage.breadcrumbs.before') !!}

        <!-- Breadcrumbs -->
        <x-shop::breadcrumbs name="checkout" />

        {!! view_render_event('bagisto.shop.checkout.onepage.breadcrumbs.after') !!}

        <v-checkout>
            <!-- Shimmer Effect -->
            <x-shop::shimmer.checkout.onepage />
        </v-checkout>
    </div>

    @pushOnce('scripts')
        <script
            type="text/x-template"
            id="v-checkout-template"
        >
            <div class="grid grid-cols-[1fr_auto] gap-8 max-lg:grid-cols-[1fr]">
                <div
                    class="overflow-y-auto"
                    id="scrollBottom"
                >
                    {!! view_render_event('bagisto.shop.checkout.onepage.addresses.before') !!}

                    @include('shop::checkout.onepage.addresses.index')

                    {!! view_render_event('bagisto.shop.checkout.onepage.addresses.after') !!}

                    {!! view_render_event('bagisto.shop.checkout.onepage.shipping_method.before') !!}

                    @include('shop::checkout.onepage.shipping')

                    {!! view_render_event('bagisto.shop.checkout.onepage.shipping_method.after') !!}

                    {!! view_render_event('bagisto.shop.checkout.onepage.payment_method.before') !!}

                    @include('shop::checkout.onepage.payment')

                    {!! view_render_event('bagisto.shop.checkout.onepage.payment_method.before') !!}
                </div>

                @include('shop::checkout.onepage.summary')
            </div>
        </script>

        <script type="module">
            app.component('v-checkout', {
                template: '#v-checkout-template',

                data() {
                    return {
                        cart: null,

                        isCartLoading: true,
                    }
                },

                mounted() {
                    this.getOrderSummary();

                    this.$emitter.on('update-cart-summary', this.getOrderSummary);
                },

                methods: {
                    getOrderSummary() {
                        this.$axios.get("{{ route('shop.checkout.onepage.summary') }}")
                            .then(response => {
                                this.cart = response.data.data;

                                this.isCartLoading = false;

                                let container = document.getElementById('scrollBottom');

                                if (container) {
                                    container.scrollIntoView({
                                        behavior: 'smooth',
                                        block: 'end'
                                    });
                                }
                            })
                            .catch(error => {});
                    },
                },
            });
        </script>
    @endPushOnce
</x-shop::layouts>
