{!! view_render_event('bagisto.shop.checkout.onepage.address.customer.before') !!}

<!-- Customer Address Vue Component -->
<v-checkout-address.customer :cart="cart"></v-checkout-address.customer>

{!! view_render_event('bagisto.shop.checkout.onepage.address.customer.after') !!}

@pushOnce('scripts')
    <script
        type="text/x-template"
        id="v-checkout-address.customer-template"
    >
    </script>

    <script type="module">
        app.component('v-checkout-address.customer', {
            template: '#v-checkout-address.customer-template',

            props: ['cart'],

            data() {
                return {
                    isLoading: false,

                    customerSavedAddresses: [],
                }
            },

            mounted() {
                this.getCustomerSavedAddresses();
            },

            methods: {
                getCustomerSavedAddresses() {
                    this.$axios.get('{{ route('api.shop.customers.account.addresses.index') }}')
                        .then(response => {
                            this.customerSavedAddresses = response.data.data;

                            this.customer.isAddressLoading = false;
                        })
                        .catch(() => {});
                },
            }
        });
    </script>
@endPushOnce