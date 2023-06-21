<x-shop::layouts
    :has-feature="false"
    :has-footer="false"
>
    {{-- Checkout component --}}
    {{-- Todo (@suraj-webkul): need change translation of this page.  --}}
    {{-- @translations --}}
    <v-checkout>
        <x-shop::shimmer.checkout.onepage></x-shop::shimmer.checkout.onepage>
    </v-checkout>

    @pushOnce('scripts')
        <script type="text/x-template" id="v-checkout-template">
            <div class="container px-[60px] max-lg:px-[30px] max-sm:px-[15px]">
                {{-- Breadcrumb --}}
                <div class="flex justify-start mt-[30px] max-lg:hidden">
                    <div class="flex gap-x-[14px] items-center">
                        <p class="flex items-center gap-x-[14px] text-[16px] font-medium">
                            {{-- @translations --}}
                            @lang('Home')
                            <span class="icon-arrow-right text-[24px]"></span>
                        </p>
                        <p class="text-[#7D7D7D] text-[12px] flex items-center gap-x-[16px] font-medium  after:content[' '] after:bg-[position:-7px_-41px] after:bs-main-sprite after:w-[9px] after:h-[20px] after:last:hidden">
                            {{-- @translations --}}
                            @lang('Checkout')
                        </p>
                    </div>
                </div>

                <div class="grid grid-cols-[1fr_auto] gap-[30px] max-lg:grid-cols-[1fr]">
                    <div class="grid gap-[30px] mt-[30px]">
                        @include('shop::checkout.onepage.address')

                        @include('shop::checkout.onepage.shipping')

                        @include('shop::checkout.onepage.payment')
                    </div>
                    
                    @include('shop::checkout.onepage.cart-summary')
                </div>
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
                        this.$axios.get("{{ route('shop.checkout.summary') }}")
                            .then(response => {
                                this.cart = response.data.data;

                                this.isCartLoading = false;
                            })
                            .catch(error => console.log(error))
                    }
                }
            });
        </script>
    @endPushOnce
</x-shop::layouts>
