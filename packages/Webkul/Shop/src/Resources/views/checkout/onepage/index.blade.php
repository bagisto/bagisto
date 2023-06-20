<x-shop::layouts
    :has-header="false"
    :has-feature="false"
    :has-footer="false"
>
    {{-- Checkout component --}}
    {{-- Todo (@suraj-webkul): need change translation of this page.  --}}
    {{-- @translations --}}
    <v-checkout></v-checkout>

    @pushOnce('scripts')
        <script type="text/x-template" id="v-checkout-template">
            <div class="container px-[60px] max-lg:px-[30px] max-sm:px-[15px]">
                <div class="grid grid-cols-[1fr_auto] gap-[30px] max-lg:grid-cols-[1fr]">
                    <div class="grid gap-[30px] mt-[30px]">
                        @include('shop::checkout.onepage.address')

                        @include('shop::checkout.onepage.shipping')

                        @include('shop::checkout.onepage.payment')

                        @include('shop::checkout.onepage.review-summary')
                    </div>
                    
                    {{-- @include('shop::checkout.onepage.cart-summary') --}}
                </div>
            </div>
        </script>

        <script type="module">
            app.component('v-checkout', {
                template: '#v-checkout-template',

                data() {
                    return {
                        cart: {}
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
                            })
                            .catch(error => console.log(error))
                    }
                }
            });
        </script>
    @endPushOnce
</x-shop::layouts>
