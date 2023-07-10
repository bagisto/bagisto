<x-shop::layouts
    :has-header="false"
    :has-feature="false"
    :has-footer="false"
>
    <div class="bs-dekstop-menu flex flex-wrap max-lg:hidden">
        <div
            class="w-full flex justify-between px-[60px] border border-t-0 border-b-[1px] border-l-0 border-r-0 pb-[5px] pt-[17px]"
        >
            <div class="flex items-center gap-x-[54px] max-[1180px]:gap-x-[35px]">
                <a
                    href="{{ route('shop.home.index') }}" 
                    class="bs-logo bg-[position:-5px_-3px] bs-main-sprite w-[131px] h-[29px] inline-block mb-[16px]"
                >
                </a>
            </div>
        </div>
    </div>

    <div class="bs-mobile-menu flex-wrap hidden max-lg:flex px-[15px] gap-[15px] max-lg:mb-[15px]">
        <div class="bs-mobile-menu flex-wrap hidden max-lg:flex px-[15px] pt-[25px] gap-[15px] max-lg:mb-[15px]">
            <div class="w-full flex justify-between items-center px-[6px]">
                <div class="flex  items-center gap-x-[5px]">
                    <x-shop::drawer
                        position="left"
                        width="300"
                    >
                        <x-slot:toggle>
                            <span class="icon-hamburger text-[24px] cursor-pointer"></span>
                        </x-slot:toggle>

                        <x-slot:header>
                            <div class="flex justify-between p-[20px] items-center">
                                <a 
                                    href="{{ route('shop.home.index') }}"
                                    class=""
                                >
                                    <img src="{{ bagisto_asset('images/logo.png') }}">
                                </a>
                            </div>
                        </x-slot:header>

                        <x-slot:content>
                            <a href="{{ route('shop.customer.session.create') }}">
                                <div class="rounded-[12px] border border-[#f3f3f5] p-[10px] relative mb-[40px]">
                                    <div class="flex items-center gap-[15px]  max-w-[calc(100%-20px)]">
                                        <img 
                                            class="rounded-[12px] w-[64px] h-[65px]"
                                            src="{{ bagisto_asset('images/thank-you.png') }}"
                                        >

                                        <div>
                                            <p class="text-[16px] font-medium">
                                                @lang('Sign up or Login')
                                            </p>

                                            <p class="text-[12px] mt-[10px]">
                                                @lang('Get UPTO 40% OFF')
                                            </p>
                                        </div>
                                    </div>

                                    <span class="absolute right-[10px] top-[50%] -translate-y-[50%] bg-[position:-146px_-65px] bs-main-sprite w-[18px] h-[20px] inline-block cursor-pointer"></span>
                                </div>
                            </a>

                            {{-- Mobile category view --}}
                            <v-mobile-category></v-mobile-category>

                        </x-slot:content>

                        <x-slot:footer></x-slot:footer>
                    </x-shop::drawer>

                    <a 
                        href="{{ route('shop.home.index') }}" 
                        class="h-[36px] inline-block cusor-pointer"
                    >
                        <img src="{{ bagisto_asset('images/logo.png') }}">
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="container px-[60px] max-lg:px-[30px] max-sm:px-[15px]">
        {{-- Breadcrumbs --}}
        <x-shop::breadcrumbs name="checkout"></x-shop::breadcrumbs>

        <v-checkout>
            {{-- Shimmer Effect --}}
            <x-shop::shimmer.checkout.onepage></x-shop::shimmer.checkout.onepage>
        </v-checkout>
    </div>

    @pushOnce('scripts')
        <script type="text/x-template" id="v-checkout-template">
            <div class="grid grid-cols-[1fr_auto] gap-[30px] max-lg:grid-cols-[1fr]">
                <div    
                    class="overflow-y-auto"
                    ref="scrollBottom"
                >
                    @include('shop::checkout.onepage.addresses.index')

                    @include('shop::checkout.onepage.shipping')

                    @include('shop::checkout.onepage.payment')

                </div>
                
                @include('shop::checkout.onepage.summary')
            </div>
        </script>

        <script type="module">
            app.component('v-checkout', {
                template: '#v-checkout-template',

                data() {
                    return {
                        cart: {},

                        isCartLoading: true,
                    }
                },

                created() {
                    this.getOrderSummary();
                }, 

                methods: {
                    getOrderSummary() {
                        this.$axios.get("{{ route('shop.checkout.onepage.summary') }}")
                            .then(response => {
                                this.cart = response.data.data;

                                this.isCartLoading = false;

                                let container = this.$refs.scrollBottom;

                                if (container) {
                                    container.scrollIntoView({
                                        behavior: 'smooth',
                                        block: 'end'
                                    });
                                }
                            })
                            .catch(error => console.log(error));
                    },
                },
            });
        </script>
    @endPushOnce
</x-shop::layouts>
