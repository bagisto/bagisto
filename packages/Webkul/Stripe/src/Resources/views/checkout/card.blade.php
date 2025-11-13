<x-shop::layouts
    :has-header="false"
    :has-feature="false"
    :has-footer="false">

    <?php $cart = cart()->getCart(); ?>

    @if ($cart)

        @if (request()->is('checkout/redirect/stripe'))
            <x-slot:title>
                @lang('admin::app.configuration.index.sales.payment-methods.stripePayment')
            </x-slot>

            @bagistoVite(['src/Resources/assets/css/app.css', 'src/Resources/assets/js/app.js'], 'shop')

            @pushOnce('meta')
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
            @endPushOnce

            @pushOnce('scripts')
                <script src="https://js.stripe.com/v3/"></script>

                <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>

                <link
                    rel="stylesheet"
                    href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"
                    integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm"
                    crossorigin="anonymous"
                >

                <link rel="preload" as="image" href="{{ url('cache/logo/bagisto.png') }}"
                    integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous">
                </script>

                <style>
                    #main {
                        background-color: #eeeeee !important;
                        min-height: 100vh;
                        padding: 5vw;
                    }

                    #dropdown-menu {
                        display: none;
                    }

                    #dropdown:hover #dropdown-menu {
                        display: block;
                    }

                    /* Media query should be outside of the body selector */
                </style>
            @endPushOnce

            <v-stripe-form></v-stripe-form>

            @pushOnce('scripts')
                <script type="text/x-template" id="v-stripe-form-template">
                    <div class="w-full mx-auto overflow-hidden">
                        <div class="px-2 flex max-md:flex-wrap">
                            <div class="w-full md:w-2/5 px-2 py-2 pr-5">
                                <div aria-label="Close" class="flex gap-3 items-center cursor-pointer" @click="paymentCancel()">
                                    <span aria-hidden="true" class="icon-arrow-left-stylish text-xl text-gray-600 hover:text-gray-400 transition"></span>

                                    <div class="rounded-xl bg-white p-1 inline-block flex items-center">
                                        <span aria-hidden="true" class="icon-orders text-lg cursor-pointer text-gray-600 hover:text-gray-400 transition"></span>
                                    </div>

                                    <div class="text-gray-700 text-xs">
                                        {{ config('app.name') }}
                                    </div>

                                    @if (core()->getConfigData('sales.payment_methods.stripe.debug'))
                                        <div class="rounded-md py-1 px-2 text-xs font-normal text-[#92400e] bg-[#ffe28a]">
                                            @lang('stripe::app.shop.checkout.card.test-mode')
                                        </div>
                                    @endif
                                </div>

                                <div class="py-3 flex flex-col gap-3">
                                    <div class="text-gray-500 text-xs">
                                        @lang('stripe::app.shop.checkout.card.pay') {{ config('app.name') }}
                                    </div>

                                    <div class="text-black text-3xl">
                                        {{ core()->currency($cart->base_grand_total) }}
                                    </div>

                                    <table class="mt-3 mb-10">
                                        <tr>
                                            <td>
                                                @lang('stripe::app.shop.checkout.card.subtotal')
                                            </td>

                                            <td class="text-black text-right">
                                                {{ core()->currency($cart->base_sub_total) }}
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>
                                                @lang('stripe::app.shop.checkout.card.tax')
                                            </td>

                                            <td class="text-black text-right">
                                                {{ core()->currency($cart->base_tax_total) }}
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>
                                                @lang('stripe::app.shop.checkout.card.shipping-cost')
                                            </td>

                                            <td class="text-black text-right">
                                                {{ core()->currency($cart->base_shipping_amount) }}
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>
                                                @lang('stripe::app.shop.checkout.card.discount')
                                            </td>

                                            <td class="text-black text-right">
                                                {{ core()->currency($cart->base_discount_amount) }}
                                            </td>
                                        </tr>

                                        <tr>
                                            <td class="border-gray-300 border-t pt-3">
                                                @lang('stripe::app.shop.checkout.card.grand-total')
                                            </td>

                                            <td class="text-black text-right border-gray-300 border-t pt-3">
                                                {{ core()->currency($cart->base_grand_total) }}
                                            </td>
                                        </tr>
                                    </table>

                                    <div class="flex justify-center gap-2 text-gray-500 text-xs">
                                        @lang('stripe::app.shop.checkout.card.powered-by')
                                        <div class="text-gray-500 font-bold">@lang('stripe::app.shop.checkout.card.powered-by')</div>

                                        <u class="cursor-pointer">@lang('stripe::app.shop.checkout.card.terms')</u>

                                        <u class="cursor-pointer">@lang('stripe::app.shop.checkout.card.privacy')</u>
                                    </div>
                                </div>
                            </div>

                            <div class="w-full bg-white rounded-xl md:w-3/5 p-3 flex flex-col">
                                <div class="text-black text-2xl font-medium mb-3">
                                   @lang('stripe::app.shop.checkout.card.card-details')
                                </div>

                                <x-shop::form
                                    v-slot="{ meta, errors, handleSubmit }"
                                    as="div"
                                    id="stripe-payment-form"
                                    class="new-card"
                                >
                                    <form @submit="handleSubmit($event, submitForm)" class="border p-3 rounded-xl">
                                        <div id="payment-request-button" class="mb-6"></div>

                                        <x-shop::form.control-group>
                                            <x-shop::form.control-group.label class="required">
                                                @lang('stripe::app.stripe-form.card-number')
                                            </x-shop::form.control-group.label>

                                            <div id="card-number" class="mb-2 w-full rounded-md border px-4 py-2 text-sm text-gray-700 transition-all hover:border-gray-400 focus:border-gray-400"></div>

                                            <div id="card-number-error" class="text-red-500 text-xs italic mt-1"></div>
                                        </x-shop::form.control-group>

                                        <div class="flex gap-4">
                                            <x-shop::form.control-group class="w-full md:w-1/2">
                                                <x-shop::form.control-group.label class="required">
                                                    @lang('stripe::app.stripe-form.expiration-date')
                                                </x-shop::form.control-group.label>

                                                <div id="card-expiry" class="w-full rounded-md border px-3 py-2 text-sm text-gray-700 transition-all hover:border-gray-400 focus:border-gray-400"></div>

                                                <div class="stripe-errors text-red-500 text-xs italic mt-1" id="card-expiration-error" role="alert"></div>
                                            </x-shop::form.control-group>

                                            <x-shop::form.control-group class="w-full md:w-1/2">
                                                <x-shop::form.control-group.label class="required">
                                                    @lang('stripe::app.stripe-form.security-code')
                                                </x-shop::form.control-group.label>

                                                <div id="card-cvc" class="w-full rounded-md border px-3 py-2 text-sm text-gray-700 transition-all hover:border-gray-400 focus:border-gray-400"></div>
                                            </x-shop::form.control-group>
                                        </div>

                                        <x-shop::form.control-group class="w-full flex items-center gap-2">
                                            <x-shop::form.control-group.control
                                                type="checkbox"
                                                v-if="isUser"
                                                name="isSavedCard"
                                                id="isSavedCard"
                                                for="isSavedCard"
                                                value="true"
                                                @change="isSavedCard = $event.target.checked ? true : false;"
                                            />

                                            <label
                                                class="cursor-pointer text-zinc-500 max-sm:text-sm"
                                                for="isSavedCard"
                                            >
                                                @lang('stripe::app.shop.checkout.card.save-card-info')
                                            </label>
                                        </x-shop::form.control-group>

                                        <div class="flex max-md:flex-col justify-around items-center md:gap-5 py-2 w-full sticky top-full">
                                            <button
                                                class="primary-button flex-block items-center gap-2 text-sm w-1/2 max-md:w-full max-w-full"
                                                id="stripe-pay-button"
                                            >
                                                <svg
                                                    class="animate-spin hidden h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg"
                                                    fill="none"
                                                    viewBox="0 0 24 24"
                                                >
                                                    <circle
                                                        class="opacity-25"
                                                        cx="12"
                                                        cy="12"
                                                        r="10"
                                                        stroke="currentColor"
                                                        stroke-width="4"
                                                    ></circle>
                                                    <path
                                                        class="opacity-75"
                                                        fill="currentColor"
                                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
                                                    ></path>
                                                </svg>
                                                @lang('stripe::app.pay-now') ({{ core()->currency($cart->base_grand_total) }})
                                            </button>

                                            @php
                                                $cards = collect();
                                                if (auth()->guard('customer')->check()) {
                                                    $customer_id = auth()->guard('customer')->user()->id;
                                                    $cards = app('Webkul\Stripe\Repositories\StripeRepository')->findWhere(['customer_id' => $customer_id]);
                                                }
                                            @endphp

                                            <template v-if="cards.length">
                                                or

                                                <span
                                                    class="secondary-button flex-block items-center gap-2 text-sm w-1/2 max-md:w-full max-w-full"
                                                    @click="savedCardPayment"
                                                >
                                                    @lang('stripe::app.pay-with-saved-card')
                                                </span>
                                            </template>
                                        </div>
                                    </form>
                                </x-shop::form>

                                @if (auth()->guard('customer')->check())
                                    <div id="saved-cards" class="saved-old-card hidden h-full">
                                        <x-shop::form.control-group>
                                            <div class="flex flex-col">
                                                <div
                                                    v-for="card in cards"
                                                    class="stripe-card-info flex items-center justify-between gap-4 w-full p-3 border rounded-lg shadow-sm mb-2"
                                                    :id="card.id"
                                                >
                                                    <label class="radio-container flex mb-0 gap-2 items-center w-full">
                                                        <x-shop::form.control-group.control
                                                            type="radio"
                                                            name="saved-card"
                                                            ::class="'saved-card-list mb-0'"
                                                            ::id="card.id"
                                                            ::for="card.id"
                                                            ::value="card.id"
                                                        />

                                                        <span class="card-last-four text-sm font-medium">**** **** **** @{{ card.last_four }}</span>
                                                    </label>

                                                    <div id="dropdown" class="relative cursor-pointer">
                                                        ︙

                                                        <ul id="dropdown-menu" class="absolute right-0 bg-white shadow-lg rounded-md top-full">
                                                            <li>
                                                                <span
                                                                    id="delete-card"
                                                                    class="icon-bin whitespace-no-wrap flex gap-1.5 rounded-b px-4 py-2 hover:bg-gray-100"
                                                                    @click="deleteCard(card.id)"
                                                                    :data-id="card.id"
                                                                >
                                                                    @lang('stripe::app.delete')
                                                                </span>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </x-shop::form.control-group>

                                        <div class="w-full sticky top-full px-3 pb-3">
                                            <div class="flex max-md:flex-col justify-around items-center md:gap-5 py-2 w-full">
                                                <button
                                                    class="primary-button old-stripe-button flex-block items-center gap-2 text-sm w-1/2 max-md:w-full max-w-full"
                                                    @click="oldStripeButton"
                                                >
                                                    <svg
                                                        class="animate-spin hidden h-5 w-5 text-white"
                                                        xmlns="http://www.w3.org/2000/svg"
                                                        fill="none"
                                                        viewBox="0 0 24 24"
                                                    >
                                                        <circle
                                                            class="opacity-25"
                                                            cx="12"
                                                            cy="12"
                                                            r="10"
                                                            stroke="currentColor"
                                                            stroke-width="4"
                                                        ></circle>
                                                        <path
                                                            class="opacity-75"
                                                            fill="currentColor"
                                                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
                                                        ></path>
                                                    </svg>
                                                    @lang('stripe::app.pay-now') ({{ core()->currency($cart->base_grand_total) }})
                                                </button>

                                                @lang('stripe::app.or')

                                                <span
                                                    class="secondary-button flex-block items-center gap-2 text-sm w-1/2 max-md:w-full max-w-full"
                                                    @click="oldCard()"
                                                >
                                                    @lang('stripe::app.pay-with-new-card')
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </script>

                <script type="module">
                    app.component('v-stripe-form', {

                        template: '#v-stripe-form-template',

                        data() {
                            return {
                                stripe: Stripe('{{ core()->getConfigData("sales.payment_methods.stripe.debug") ? core()->getConfigData("sales.payment_methods.stripe.api_test_publishable_key") : core()->getConfigData("sales.payment_methods.stripe.api_publishable_key") }}'),

                                isUser: '{{ auth()->guard("customer")->check() ? true : false }}',

                                stripeModalIsOpen: false,

                                stripeTokenReceived: false,

                                savedCardSelected: false,

                                savedCardSelectedId: null,

                                rememberCard: false,

                                data: {},

                                stripeGlobal: 'stripe',

                                isSavedCard: false,

                                styles: {
                                    base: {
                                        color: '#333333',
                                        fontWeight: 600,
                                        fontFamily: 'Montserrat, sans-serif',
                                        fontSize: '16px',
                                        fontSmoothing: 'antialiased',
                                        ':focus': {
                                            color: '#0d0d0d',
                                        },
                                        '::placeholder': {
                                            color: '#C7C7C7',
                                        },
                                        ':focus::placeholder': {
                                            color: '#666666',
                                        },
                                    },
                                    invalid: {
                                        color: '#333333',
                                        ':focus': {
                                            color: '#FF5252',
                                        },
                                        '::placeholder': {
                                            color: '#FF5252',
                                        },
                                    },
                                },

                                elementClasses: {
                                    focus: 'focus',
                                    empty: 'empty',
                                    invalid: 'invalid',
                                },

                                elements: null,

                                cardNumber: null,

                                cardExpiry: null,

                                cardCvc: null,

                                currency: '{{ strtolower(core()->getBaseCurrencyCode()) }}',

                                country: '{{ $cart->billing_address->country }}',

                                amount: parseInt('{{ round($cart->base_grand_total, 2)}}'),

                                paymentRequest: null,

                                prButton: null,

                                cards: @json($cards),
                            }
                        },

                        mounted() {
                            this.elements = this.stripe.elements({
                                fonts: [{
                                    cssSrc: 'https://fonts.googleapis.com/css?family=Montserrat',
                                }]
                            });

                            this.cardNumber = this.elements.create('cardNumber', {
                                style: this.styles
                            });

                            this.cardNumber.mount('#card-number');

                            this.cardExpiry = this.elements.create('cardExpiry', {
                                style: this.styles
                            });

                            this.cardExpiry.mount('#card-expiry');

                            this.cardCvc = this.elements.create('cardCvc', {
                                style: this.styles
                            });

                            this.cardCvc.mount('#card-cvc');

                            this.paymentRequest = this.stripe.paymentRequest({
                                country: this.country,

                                currency: this.currency,

                                total: {
                                    label: 'stripe',
                                    amount: this.amount,
                                },

                                requestPayerName: true,

                                requestPayerEmail: true,
                            });

                            let paymentRequest = this.paymentRequest;

                            this.prButton = this.elements.create('paymentRequestButton', {
                                paymentRequest,
                            });

                            paymentRequest.canMakePayment().then((result) => {
                                if (result) {
                                    this.prButton.mount('#payment-request-button');
                                } else {
                                    document.getElementById('payment-request-button').style.display = 'none';
                                }
                            });

                            paymentRequest.on('paymentmethod', async (ev) => {
                                try {
                                    const response = await axios.get("{{ route('stripe.payment.element.intent') }}");

                                    const clientSecret = response.data.success.client_secret;

                                    const {
                                        paymentIntent,
                                        error: confirmError
                                    } = await this.stripe.confirmCardPayment(
                                        clientSecret, {
                                            payment_method: ev.paymentMethod.id
                                        }, {
                                            handleActions: false
                                        }
                                    );

                                    if (confirmError) {
                                        ev.complete('fail');
                                        this.paymentCancel();
                                    } else {
                                        ev.complete('success');
                                        this.saveOrder();
                                    }
                                } catch (error) {
                                    console.error("Error during payment method processing:", error);
                                    ev.complete('fail');
                                    this.paymentCancel();
                                }
                            });

                            this.cardNumber.addEventListener('change', (event) => {

                                const displayError = document.getElementById('card-number-error');

                                displayError.textContent = event.error ? event.error.message : '';
                            });

                            this.cardExpiry.addEventListener('change', (event) => {

                                const displayError = document.getElementById('card-expiration-error');

                                displayError.textContent = event.error ? event.error.message : '';
                            });
                        },

                        methods: {
                            async saveCard(result, token, paymentMethodId, isSavedCard) {

                                try {
                                    const response = await axios.post("{{ route('stripe.save.card') }}", {
                                        result: result,

                                        _token: '{{ csrf_token() }}',

                                        stripetoken: token,

                                        paymentMethodId: paymentMethodId,

                                        isSavedCard: isSavedCard
                                    });

                                    if (response.data.message) {
                                        window.location.href = "{{ route('shop.checkout.cart.index') }}";
                                    } else {
                                        this.paymentIntent();
                                    }
                                } catch (error) {
                                    console.error("Error saving card:", error);
                                }
                            },

                            async paymentIntent() {
                                try {
                                    const response = await axios.get("{{ route('stripe.get.token') }}");

                                    if (response.data.success != 'false') {
                                        const client_secret = response.data.client_secret;

                                        const result = await this.stripe.handleCardPayment(client_secret, this.cardNumber);

                                        if (result.error) {
                                            this.paymentCancel();
                                        } else {
                                            this.saveOrder();
                                        }
                                    } else {
                                        location.href = "{{ route('shop.checkout.cart.index') }}";
                                    }
                                } catch (error) {
                                    console.error("Error getting payment token:", error);
                                }
                            },

                            async paymentCancel() {
                                try {
                                    const response = await axios.get("{{ route('stripe.payment.cancel') }}");
                                    location.href = response.data.data.route;
                                } catch (error) {
                                    console.error("Error canceling payment:", error);
                                }
                            },

                            async saveOrder() {
                                try {
                                    const response = await axios.get("{{ route('stripe.make.payment') }}");
                                    window.location.href = response.data.data.route;
                                } catch (error) {
                                    console.error("Error saving order:", error);
                                }
                            },

                            async submitForm(params, {
                                setErrors
                            }) {
                                try {
                                    const result = await this.stripe.createPaymentMethod('card', this.cardNumber);

                                    if (result.error) {
                                        if (result.error.type == 'validation_error') {
                                            console.log('validation error');
                                        } else {
                                            this.paymentCancel();
                                        }
                                    } else {
                                        const paymentMethodId = result.paymentMethod.id;
                                        const tokenResult = await this.stripe.createToken(this.cardNumber);

                                        if (tokenResult.error) {
                                            const errorElement = document.getElementById('card-errors');
                                            errorElement.textContent = tokenResult.error.message;
                                            return false;
                                        } else {
                                            document.querySelector('.animate-spin').style.display = 'block';
                                            document.getElementById("stripe-pay-button").disabled = true;

                                            this.saveCard(result, tokenResult.token.id, paymentMethodId, this.isSavedCard);
                                        }
                                    }
                                } catch (error) {
                                    console.error("Error during form submission:", error);
                                }
                            },

                            disableAllMethodsExceptStripe() {
                                // Disable all radio buttons except the one with ID 'stripe'
                                document.querySelectorAll(".line-one .radio-container input:radio:not(#stripe)").forEach(radio => {
                                    radio.disabled = true;
                                });

                                // Hide elements with the class 'stripe-cards-block'
                                document.querySelectorAll('.stripe-cards-block').forEach(element => {
                                    element.style.display = 'none';
                                });

                                // Remove elements with the class 'add-card'
                                document.querySelectorAll('.add-card').forEach(element => {
                                    element.remove();
                                });
                            },

                            savedCardPayment() {
                                // Select all elements with the class 'new-card'
                                document.querySelectorAll('.new-card').forEach(function(element) {
                                    element.style.display = 'none';
                                });

                                // Select all elements with the class 'old-card'
                                document.querySelectorAll('.old-card').forEach(function(element) {
                                    element.style.display = 'block';
                                });

                                // Select all elements with the class 'saved-old-card'
                                document.querySelectorAll('.saved-old-card').forEach(function(element) {
                                    element.style.display = 'block';
                                });

                            },

                            removeSavedCardNode(deleteId) {
                                let self = this;

                                self.cards = self.cards.filter(item => item.id !== deleteId);

                                if (!self.cards.length) {
                                    self.oldCard();
                                }
                            },

                            async deleteCard(deleteId) {
                                let result = confirm({!! json_encode(trans('stripe::app.shop.checkout.card.want-delete-card')) !!});

                                let self = this;
                                if (result) {
                                    await axios.get("{{ route('stripe.delete.saved.cart') }}", {
                                        params: {
                                            id: deleteId,
                                        }
                                    }).then(response => {
                                        if (response.data == 1) {
                                            self.removeSavedCardNode(deleteId);
                                        }
                                    }).catch(error => {
                                        console.log(error);
                                    });
                                }
                            },

                            oldCard() {
                                document.querySelectorAll('.new-card').forEach(function(element) {
                                    element.style.display = 'block';
                                });

                                document.querySelectorAll('.old-card').forEach(function(element) {
                                    element.style.display = 'none';
                                });

                                document.querySelectorAll('.saved-old-card').forEach(function(element) {
                                    element.style.display = 'none';
                                });
                            },

                            async oldStripeButton() {
                                document.querySelector('.old-stripe-button').disabled = true;

                                let self = this;

                                await axios.post("{{ route('stripe.saved.card.payment') }}", {
                                    _token: '{{ csrf_token() }}',
                                    savedCardSelectedId: document.querySelector('.stripe-card-info > .radio-container > input[type="radio"][name=saved-card]:checked').id
                                }).then(response => {
                                    if (response.data.success == 'false') {
                                        self.paymentCancel();
                                    } else {
                                        self.paymentUsingSavedCard(response.data.savedCardPayment.client_secret, response.data.payment_method_id);
                                    }
                                }).catch(error => {
                                    console.log(error);
                                });
                            },

                            paymentUsingSavedCard(clientSecret, paymentMethodId) {
                                let self = this;

                                self.stripe.confirmCardPayment(clientSecret, {
                                    payment_method: paymentMethodId
                                }).then(function(result) {
                                    if (result.error) {
                                        // Show error to your customer
                                        self.paymentCancel();
                                    } else {
                                        if (result.paymentIntent.status === 'succeeded') {
                                            $('.animate-spin').css({
                                                'display': 'block',
                                            });
                                            self.saveOrder();
                                        }
                                    }
                                });
                            }

                        }
                    });
                </script>
            @endpushOnce
        @endif
    @endif
</x-shop::layouts>
