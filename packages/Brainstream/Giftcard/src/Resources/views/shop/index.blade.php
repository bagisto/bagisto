<x-shop::layouts.account>
    <!-- Page Title -->
    <x-slot:title>
        @lang('giftcard::app.giftcard.title')
    </x-slot>

    <div class="flex-auto">
        <div class="max-md:max-w-full" >
            <h2 class="text-2xl font-medium" style="
                    margin-bottom: 30px;
                "> Giftcard
            </h2>

            <!-- Vue Gift Card Card Component -->
            <v-gift-card-card
                :gift-cards="[
                    {
                        name: 'Email Gift Card',
                    },
                ]"
            ></v-gift-card-card>
        </div>
    </div>

    @pushOnce('scripts')
        <!-- Gift Card Template -->
        <script
            type="text/x-template"
            id="v-gift-card-card-template"
        >
            <x-shop::form
                v-slot="{ meta, errors, handleSubmit }"
                as="div"
            >
                <form @submit.prevent ref="formData">
                    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-8">


                        <!-- Occasion Buttons -->
                        <div class="occasion-selection" v-if="!formVisible">
                            <button v-for="(image, occasion) in occasionImages"
                                    :key="occasion"
                                    @click="selectOccasion(occasion)"
                                    :class="{'selected-occasion': selectedOccasion === occasion}"
                                    type="button">
                                @{{ occasion }}
                            </button>
                        </div>

                        <!-- Gift Card Preview and Next Button -->
                        <div v-if="selectedOccasion && !formVisible" class="preview-and-next">
                            <div class="gift-card-preview">
                                <img :src="currentGiftCardImage" alt="Gift Card Preview" class="gift-card-image">
                            </div>
                            <x-shop::button
                                class="primary-button mt-4"
                                button-type="secondary-button"
                                :title="trans('giftcard::app.giftcard.next')"
                                ::loading="isStoring.showForm"
                            @click="showForm"
                        >
                        </x-shop::button>
                        </div>

                        <div
                            v-if="formVisible"
                            v-for="giftCard in giftCards"
                            :key="giftCard.name"
                            class="card card-interactive flex flex-col w-full max-w-sm"
                        >
                            <div class="card-body p-6 shadow-md rounded-lg bg-white flex flex-col gap-4">
                                <!-- Giftcard Amount Selection -->
                                <x-shop::form.control-group>
                                    <x-shop::form.control-group.label class="required">
                                        @lang('giftcard::app.giftcard.amount')
                                    </x-shop::form.control-group.label>

                                    <div class="amount-buttons flex gap-2">
                                        <button
                                            v-for="amountValue in [50, 60, 70, 80, 90, 100]"
                                            :key="amountValue"
                                            type="button"
                                            class="small-button"
                                            :class="{ 'selected': giftcard_amount === amountValue }"
                                            @click="selectAmount(amountValue)"
                                        >
                                            @{{ `$${amountValue}.00` }}
                                        </button>

                                        <button
                                            type="button"
                                            class="small-button"
                                            :class="{ 'selected': showOtherAmount }"
                                            @click="selectOtherAmount"
                                        >
                                            Other Amount...
                                        </button>
                                    </div>

                                    <x-shop::form.control-group.control
                                        v-if="showOtherAmount"
                                        type="number"
                                        name="giftcard_amount"
                                        rules="required|numeric|min_value:50|max_value:100"
                                        v-model="giftcard_amount"
                                        class="mt-4"
                                        placeholder="Amount Range between $50 - $100"
                                    />

                                    <x-shop::form.control-group.error control-name="giftcard_amount" />
                                </x-shop::form.control-group>

                                <!-- Sender Name -->
                                <x-shop::form.control-group>
                                    <x-shop::form.control-group.label class="required">
                                        @lang('giftcard::app.giftcard.sendername')
                                    </x-shop::form.control-group.label>

                                    <x-shop::form.control-group.control
                                        type="text"
                                        name="sendername"
                                        rules="required"
                                        v-model="sendername"
                                        placeholder="Enter the sender name"
                                    />

                                    <x-shop::form.control-group.error control-name="sendername" />
                                </x-shop::form.control-group>

                                <!-- Sender Email -->
                                <x-shop::form.control-group>
                                    <x-shop::form.control-group.label class="required">
                                        @lang('giftcard::app.giftcard.senderemail')
                                    </x-shop::form.control-group.label>

                                    <x-shop::form.control-group.control
                                        type="text"
                                        name="senderemail"
                                        rules="required|email"
                                        v-model="senderemail"
                                        placeholder="Enter the sender email"
                                    />

                                    <x-shop::form.control-group.error control-name="senderemail" />
                                </x-shop::form.control-group>

                                <!-- Giftcard Qty -->
                                <x-shop::form.control-group>
                                    <x-shop::form.control-group.label class="required">
                                        @lang('giftcard::app.giftcard.quantity')
                                    </x-shop::form.control-group.label>

                                    <x-shop::form.control-group.control
                                        type="number"
                                        name="quantity"
                                        rules="required|numeric|min_value:1|max_value:1"
                                        v-model="quantity"
                                        placeholder="Enter Giftcard Quantity"
                                    />

                                    <x-shop::form.control-group.error control-name="quantity" />
                                </x-shop::form.control-group>

                                <!-- Recipient Name -->
                                <x-shop::form.control-group>
                                    <x-shop::form.control-group.label class="required">
                                        @lang('giftcard::app.giftcard.recipientname')
                                    </x-shop::form.control-group.label>

                                    <x-shop::form.control-group.control
                                        type="text"
                                        name="recipientname"
                                        rules="required"
                                        v-model="recipientname"
                                        placeholder="Enter the recipient name"
                                    />

                                    <x-shop::form.control-group.error control-name="recipientname" />
                                </x-shop::form.control-group>

                                <!-- Recipient Email -->
                                <x-shop::form.control-group>
                                    <x-shop::form.control-group.label class="required">
                                        @lang('giftcard::app.giftcard.recipientemail')
                                    </x-shop::form.control-group.label>

                                    <x-shop::form.control-group.control
                                        type="text"
                                        name="recipientemail"
                                        rules="required|email"
                                        v-model="recipientemail"
                                        placeholder="Enter the recipient email"
                                    />

                                    <x-shop::form.control-group.error control-name="recipientemail" />
                                </x-shop::form.control-group>

                                <!-- Message -->
                                <x-shop::form.control-group>
                                    <x-shop::form.control-group.label class="required">
                                        @lang('giftcard::app.giftcard.message')
                                    </x-shop::form.control-group.label>

                                    <x-shop::form.control-group.control
                                        type="textarea"
                                        name="message"
                                        rules="required"
                                        v-model="message"
                                        placeholder="Enter your message"
                                    />

                                    <x-shop::form.control-group.error control-name="message" />
                                </x-shop::form.control-group>

                                <div class="flex gap-4">
                                    <x-shop::button
                                        v-if="!showPaymentMethods"
                                        class="primary-button"
                                        button-type="secondary-button"
                                        :title="trans('giftcard::app.giftcard.preview-giftcard')"
                                        ::loading="isStoring.buyNow"
                                        ::disabled="isStoring.buyNow"
                                        @click.prevent="handleSubmit($event, openModal)"
                                    >
                                    </x-shop::button>
                                </div>

                                <!-- Payment Methods Component -->
                                <v-payment-methods
                                    v-if="showPaymentMethods"
                                    :methods="paymentMethods"
                                    :method-selected="handleMethodSelection"
                                ></v-payment-methods>

                                 <!-- Place Order Button -->
                                 <div v-if="showPaymentMethods" class="flex mt-5">
                                    <x-shop::button
                                        class="primary-button w-max py-3 px-11 bg-navyBlue rounded-2xl max-sm:text-sm max-sm:px-6 max-sm:mb-10"
                                        :title="trans('giftcard::app.giftcard.place-order')"
                                        ::disabled="isPlacingOrder"
                                        ::loading="isPlacingOrder"
                                        @click="placeOrder"
                                    >
                                    </x-shop::button>
                                </div>
                                <div id="paypal-button-container"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Gift Card Preview Modal -->
                    <div v-if="showPreview" class="modal">
                        <div class="modal-content">
                            <span class="close" @click="showPreview = false">&times;</span>
                            <img :src="currentGiftCardImage" alt="Gift Card Image" class="preview-image" style="width: 100%; border-radius: 8px;">

                            <div class="gift-card-details">
                                <div class="gift-card-value">
                                    <label>Gift Card</label>
                                    <p>$@{{ giftcard_amount }}</p>
                                </div>
                                <div class="gift-card-info">
                                    <p><strong>From:</strong> @{{ sendername }}</p>
                                    <p><strong>To:</strong> @{{ recipientname }}</p>
                                    <p><strong>Message:</strong> @{{ message }}</p>
                                    <p><strong>Expiry Date:</strong> 1 Year From Today</p>
                                </div>
                            </div>

                            <x-shop::button
                                class="primary-button mt-4"
                                button-type="secondary-button"
                                :title="trans('giftcard::app.giftcard.buy-now')"
                                ::loading="isStoring.buyNow"
                                ::disabled="isStoring.buyNow"
                                @click="buyNow"
                            >
                            </x-shop::button>
                        </div>
                    </div>

                </form>
            </x-shop::form>
        </script>

        <script type="module">

            app.component('v-payment-methods', {
                template: '#v-payment-methods-template',
                props: {
                    methods: Array,
                    methodSelected: Function
                },
                data() {
                    return {
                        selectedMethod: '',
                    };
                },
                methods: {
                    handleMethodSelection(method) {
                        this.selectedMethod = method;
                        this.methodSelected(method);
                    }
                }
            });


            app.component('v-gift-card-card', {
                template: '#v-gift-card-card-template',
                props: ['giftCards'],

                data() {
                    return {
                        is_buy_now: 0,
                        showPreview: false,
                        previewGiftCardDetails: {},
                        showOtherAmount: false,
                        showPaymentMethods: false,
                        isPlacingOrder: false,
                        selectedOccasion: '',
                        currentGiftCardImage: '',
                        formVisible: false,
                        occasionImages: {
                            Welcome: '{{ asset('vendor/giftcard/assets/images/welcome.jpeg') }}',
                            Birthday: '{{ asset('vendor/giftcard/assets/images/birthday.jpeg') }}',
                            Christmas: '{{  asset('vendor/giftcard/assets/images/christmas.jpeg') }}',
                            Anniversary: '{{  asset('vendor/giftcard/assets/images/anniversary.jpeg') }}',
                            Pongal: '{{ asset('vendor/giftcard/assets/images/pongal.jpeg') }}',
                            Ramadan: '{{  asset('vendor/giftcard/assets/images/ramadan.png') }}',
                            Diwali: '{{  asset('vendor/giftcard/assets/images/diwali.jpeg') }}',
                            Engagement:  '{{  asset('vendor/giftcard/assets/images/engagement.jpeg') }}',
                            Farewell:  '{{  asset('vendor/giftcard/assets/images/farewell.jpeg') }}',
                            Navratri:  '{{  asset('vendor/giftcard/assets/images/navratri.jpeg') }}',
                            Rakshabandhan: '{{  asset('vendor/giftcard/assets/images/rakshabandhan.jpeg') }}',
                            Onam: '{{  asset('vendor/giftcard/assets/images/onam.jpeg') }}',
                            FathersDay:  '{{  asset('vendor/giftcard/assets/images/fathersday.jpeg') }}',
                            MothersDay:  '{{  asset('vendor/giftcard/assets/images/mothersday.jpeg') }}',
                            Congratulations: '{{  asset('vendor/giftcard/assets/images/congratulations.jpeg') }}',
                            Easter: '{{  asset('vendor/giftcard/assets/images/easter.jpeg') }}',
                            BestMom: '{{  asset('vendor/giftcard/assets/images/bestmom.jpeg') }}',
                            BestDad: '{{  asset('vendor/giftcard/assets/images/bestdad.png') }}',
                            BhaiDooj: '{{  asset('vendor/giftcard/assets/images/bhaidooj.jpeg') }}',
                            ThankYou: '{{ asset('vendor/giftcard/assets/images/thankyou.png') }}',
                            ValentinesDay: '{{  asset('vendor/giftcard/assets/images/valentineday.jpeg') }}',
                            Goodluck: '{{  asset('vendor/giftcard/assets/images/goodluck.png') }}',
                            GrandParents: '{{  asset('vendor/giftcard/assets/images/grandparentsday.jpeg') }}',
                        },

                        selectedOccasion: '',
                        isStoring: {
                            buyNow: false,
                        },

                        giftcard_amount: '',
                        giftcard_number: '',
                        sendername: '',
                        senderemail: '',
                        recipientname: '',
                        recipientemail: '',
                        message: '',
                        quantity: '',
                        paymentMethods: null,
                        selectedPaymentMethod: null,
                        paymentMethods: [
                            { value: 'paypal_standard', label: 'PayPal' },
                        ],
                        previewGiftCardDetails: {},
                    };
                },

                methods: {
                    selectOccasion(occasion) {
                        this.selectedOccasion = occasion;
                        this.currentGiftCardImage = this.occasionImages[occasion] || '';
                        const imagePath = this.occasionImages[occasion] || '';
                        const imageName = imagePath.substring(imagePath.lastIndexOf('/') + 1);
                        this.giftcardimage = imageName;
                    },
                    showForm() {
                        this.formVisible = true;  // Show form and hide image preview
                    },
                    openModal() {
                        this.previewGiftCardDetails = this.giftCards[0];
                        this.showPreview = true;
                    },

                    buyNow() {
                        this.isStoring.buyNow = true;

                        const postData = {
                            giftcard_amount: this.giftcard_amount,
                            giftcard_number: this.giftcard_number,
                            sendername: this.sendername,
                            senderemail: this.senderemail,
                            recipientname: this.recipientname,
                            recipientemail: this.recipientemail,
                            message: this.message,
                            quantity: this.quantity,
                            occasion_image: this.giftcardimage,
                            selectedOccasion: this.selectedOccasion,
                        };

                        this.$axios.post("{{ route('shop.api.gift-cards.purchase') }}", postData)
                        .then((response) => {
                            if (response.data.message) {
                                this.$emitter.emit('add-flash', {
                                    type: 'success',
                                    message: response.data.message,
                                });

                                this.paymentMethods = response.data.methods;
                                this.showPaymentMethods = true;
                                this.showPreview = false;
                                this.isStoring.buyNow = false;
                            } else {
                                this.$emitter.emit('add-flash', {
                                    type: 'warning',
                                    message: response.data.data.message,
                                });

                                this.isStoring.buyNow = false;
                            }
                        })
                        .catch((error) => {
                            this.$emitter.emit('add-flash', {
                                type: 'danger',
                                message: error.response.data.message,
                            });

                            this.isStoring.buyNow = false;
                        });
                    },

                    selectAmount(amountValue) {
                        this.giftcard_amount = amountValue;
                        this.showOtherAmount = false;
                    },

                    selectOtherAmount() {
                        this.giftcard_amount = '';
                        this.showOtherAmount = true;
                    },

                    mounted() {
                        setTimeout(() => {
                            this.loadPayPalScript();
                        }, 500); // Adjust the timeout as necessary
                    },
                    loadPayPalScript() {
                        const script = document.createElement('script');
                        script.src = "https://www.paypal.com/sdk/js?client-id=1&currency=USD";
                        script.onload = () => {
                            this.$nextTick(() => {
                                this.renderPayPalButton();
                            });
                        };
                        document.head.appendChild(script);
                    },
                    renderPayPalButton() {
                        if (window.paypal && document.getElementById('paypal-button-container')) {
                            paypal.Buttons({
                                style: {
                                    color:  'blue',
                                    shape:  'pill',
                                    label:  'pay',
                                    height: 40
                                },
                                createOrder: (data, actions) => {
                                    return actions.order.create({
                                        purchase_units: [{
                                            amount: {
                                                value: this.giftcard_amount * this.quantity, // Total amount based on quantity
                                            },
                                        }]
                                    });
                                },
                                onApprove: (data, actions) => {
                                    return actions.order.capture().then(details => {
                                        console.log("Transaction completed by", details.payer.name.given_name);

                                        const paymentDetails = {
                                            order_id: data.orderID,
                                            payment_id: details.id,
                                            payer_id: details.payer.payer_id,
                                            payer_email: details.payer.email_address,
                                            payment_data: JSON.stringify(details),
                                            payment_type: details.intent
                                        };

                                        this.$axios.post("{{ route('shop.api.gift-cards.placeorder') }}", {
                                            giftcard_amount: this.giftcard_amount,
                                            sendername: this.sendername,
                                            senderemail: this.senderemail,
                                            recipientname: this.recipientname,
                                            recipientemail: this.recipientemail,
                                            message: this.message,
                                            quantity: this.quantity,
                                            occasion_image: this.giftcardimage,
                                            selectedOccasion: this.selectedOccasion,
                                            ...paymentDetails,
                                        })
                                        .then((response) => {
                                            if (response.data.redirect_url) {
                                                window.location.href = response.data.redirect_url;
                                            } else {
                                                this.$emitter.emit('add-flash', {
                                                    type: 'success',
                                                    message: response.data.message || 'Order placed successfully!',
                                                });
                                            }
                                            this.isPlacingOrder = false;
                                        })
                                        .catch((error) => {
                                            this.$emitter.emit('add-flash', {
                                                type: 'danger',
                                                message: error.response.data.message || 'Something went wrong!',
                                            });
                                            this.isPlacingOrder = false;
                                        });
                                    });
                                }
                            }).render('#paypal-button-container');
                        } else {
                            console.error("PayPal SDK not loaded or container does not exist");
                        }
                    },
                    placeOrder() {

                        this.loadPayPalScript();
                        this.isPlacingOrder = true;

                        const postData = {
                            giftcard_amount: this.giftcard_amount,
                            giftcard_number: this.giftcard_number,
                            sendername: this.sendername,
                            senderemail: this.senderemail,
                            recipientname: this.recipientname,
                            recipientemail: this.recipientemail,
                            message: this.message,
                            quantity: this.quantity,
                            occasion_image: this.giftcardimagee,
                            selectedOccasion: this.selectedOccasion,
                        };

                        this.$axios.post("{{ route('shop.api.gift-cards.placeorder') }}", {
                            giftcard_amount: this.giftcard_amount,
                            sendername: this.sendername,
                            senderemail: this.senderemail,
                            recipientname: this.recipientname,
                            recipientemail: this.recipientemail,
                            message: this.message,
                            quantity: this.quantity,
                            occasion_image: this.giftcardimage,
                            selectedOccasion: this.selectedOccasion,
                        });
                    },
                }
            });

            app.component('v-payment-methods', {
                template: '#v-payment-methods-template',

                props: {
                    methods: {
                        type: Array,
                        required: true,
                        default: () => [],
                    },
                },

                emits: ['processing', 'processed', 'method-selected'],


                methods: {
                    store(selectedMethod) {
                        this.$emit('processing', 'review');

                        this.$axios.post("{{ route('shop.giftcard.store-payment-method') }}", {
                                payment: selectedMethod.payment
                            })
                            .then((response) => {
                                this.$emit('processed', response.data.cart);
                            })
                            .catch((error) => {
                                this.$emit('processing', 'payment');

                                if (error.response.data.redirect_url) {
                                    window.location.href = error.response.data.redirect_url;
                                }
                            });
                    }
                }
            });
        </script>

        <style>
            .card {
                margin: 20px auto;
            }

            .card-body {
                box-shadow: 0 2px 8px rgba(0,0,0,0.1);
                border-radius: 8px;
                padding: 16px;
                background-color: #ffffff;
            }

            .amount-buttons button {
                border: 1px solid #ddd;
                border-radius: 8px;
                padding: 8px 16px;
                background-color: #f0f0f0;
                cursor: pointer;
                transition: background-color 0.2s ease;
            }

            .amount-buttons button.selected {
                background-color: #007bff;
                color: white;
            }

            .modal {
                display: block;
                position: fixed;
                z-index: 1;
                left: 0;
                top: 0;
                width: 100%;
                height: 100%;
                overflow: auto;
                background-color: rgba(0,0,0,0.4);
            }

            .gift-card-image {
                max-width: 40%;
                max-height: 40%;
                object-fit: cover;
                border-radius: 8px;
            }

            .small-button {
                padding: 6px 12px;
                font-size: 0.875rem;
            }

            .required::after {
                content: " *";
                color: red;
            }

            .modal-content {
                background-color: #fefefe;
                margin: 15% auto;
                padding: 20px;
                border: 1px solid #888;
                width: 30%;
                border-radius: 8px;
            }

            .gift-card-details {
                margin-top: 20px;
            }

            .gift-card-value label {
                font-weight: bold;
                color: #333;
                font-size: 16px;
            }

            .gift-card-value p {
                color: #007bff;
                font-size: 20px;
                font-weight: bold;
                margin: 5px 0;
            }

            .gift-card-info p {
                font-size: 14px;
                color: #666;
                margin: 5px 0;
            }

            /* .primary-button {
                background-color: #007bff;
                color: white;
                padding: 10px 20px;
                border: none;
                border-radius: 5px;
                cursor: pointer;
            } */

            .close {
                float: right;
                font-size: 20px;
                color: #aaa;
            }

            .close:hover,.close:focus {
                color: #000;
            }

            .occasion-selection button {
                padding: 8px 16px;
                margin: 5px;
                border: 1px solid transparent;
                border-radius: 20px;
                background-color: #f8f8f8;
                cursor: pointer;
                transition: all 0.3s ease;
            }

            .occasion-selection .selected-occasion {
                background-color: #007bff;
                color: white;
                border-color: #0056b3;
            }

            .preview-and-next {
                text-align: center;
                margin-top: 20px;
            }

            .next-button {
                padding: 10px 20px;
                border-radius: 8px;
                background-color: #007bff;
                color: white;
                border: none;
                cursor: pointer;
                display: block;
                margin-top: 20px;
            }

            .gift-card-preview img {
                max-width: 100%;
                height: auto;
                border-radius: 8px;
                box-shadow: 0 2px 8px rgba(0,0,0,0.1);
                margin-bottom: 20px;
            }
        </style>
    @endPushOnce
</x-shop::layouts.account>
