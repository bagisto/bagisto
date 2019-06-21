@if(request()->is('checkout/onepage'))
    <script src="https://js.stripe.com/v3/"></script>

    <!-- JQ is needed to get the multiple document on ready instances and the rest part for the stripe payments integration works swiftly, plain js was creating delay and blocking of events on the ui which was hindering all the required code to be executed -->
    <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>

    <script>
        var stripeModalIsOpen = false;
        var stripeTokenReceived = false;
        var savedCardSelected = false;
        var savedCardSelectedId = null;
        var rememberCard = false;
        var data = {};

        eventBus.$on('after-checkout-payment-section-added', function() {
            // this part in this ready function will be executed on the basis of the event fired from the payment section's mounted hook and it will inject this code in to the window's event bus and the rest part of the code will be fired after that
            $(document).ready(function() {
                $('.cp-spinner').css({'display': 'none'});
                //this part of the script will be executed when payment method clicked and inner portion of this script is will identify if the payment method is stripe
                $('#open-stripe-modal').on('click', function(e) {
                    e.preventDefault();

                    stripeModalIsOpen = true;

                    $('.stripe-block-modal').removeClass('close');
                    $('.modal-overlay').css('display', 'block');
                    $('#stripe-cards').addClass("modal-container");

                    //Stripe modal ends
                    // the dynamic configuration of the stripe module will be loaded up and some dynamic part to enable stripe on checkout will be used on the start of the payments load page and it will identify the integration of this script into the payments page
                    // this statement below will check if the module is in testing mode or not and accordingly it will load the key for testing and live purposes.

                    @if(env('STRIPE_ENABLE_TESTING'))
                        var stripe = Stripe('{{ env('STRIPE_TEST_PUBLISHABLE_KEY') }}');
                    @else
                        var stripe = Stripe('{{ env('STRIPE_LIVE_PUBLISHABLE_KEY') }}');
                    @endif

                    // Create an instance of Elements.
                    // this object below is responsible for the stripe css styles needed for showing text and validation message from stripe and some resources such as font will be used here
                    // in the later releases of this module their will be a provision to override this object so that the users will be able to easily style their stripe elements according to their needs
                    var elements = stripe.elements({
                        fonts: [
                            {
                                cssSrc: 'https://fonts.googleapis.com/css?family=Montserrat',
                            }
                        ]
                    });

                    var styles = {
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
                    };

                    var elementClasses = {
                        focus: 'focus',
                        empty: 'empty',
                        invalid: 'invalid',
                    };

                    //mount the elements to stripe payment form
                    var cardNumber = elements.create('cardNumber', { style: styles });
                    cardNumber.mount('#card-number');
                    var cardExpiry = elements.create('cardExpiry', { style: styles });
                    cardExpiry.mount('#card-expiry');
                    var cardCvc = elements.create('cardCvc', { style: styles });
                    cardCvc.mount('#card-cvc');

                    // Handle real-time validation errors from the card number.
                    cardNumber.addEventListener('change', function(event) {
                        var displayError = document.getElementById('card-number-error');

                        if (event.error) {
                            displayError.textContent = event.error.message;
                        } else {
                            displayError.textContent = '';
                        }
                    });

                    // Handle real-time validation errors from the card expiry.
                    cardExpiry.addEventListener('change', function(event) {
                        var displayError = document.getElementById('card-expiration-error');

                        if (event.error) {
                            displayError.textContent = event.error.message;
                        } else {
                            displayError.textContent = '';
                        }
                    });

                    // Submit the form with the token ID and disables all other payment methods and disabling adding new card information also
                    function stripeTokenHandler(token) {
                        // Insert the token ID into the form so it gets submitted to the server
                        var form = document.getElementById('stripe-payment-form');
                        var hiddenInput = document.createElement('input');

                        hiddenInput.setAttribute('type', 'hidden');
                        hiddenInput.setAttribute('name', 'stripeToken');
                        hiddenInput.setAttribute('value', token.id);
                        form.appendChild(hiddenInput);

                        //UX behavior
                        $('.modal-overlay').css('display', 'none');
                        $('.stripe-block-modal').addClass('close');
                        $('#saved-cards').css('display', 'none');
                        $('#add-card').css('display', 'none');

                        //checkout button remove disabled attribute
                        $('#checkout-continue-button-payment').removeAttr('disabled');

                        //disable all other radio buttons other than stripe code here
                        disableAllMethodsExceptStripe();

                        //remove disabled attribute from checkout continue button
                        $('#checkout-payment-continue-button').removeAttr('disabled');

                        if (token.id.length > 0) {
                            stripeTokenReceived = true;

                            data.stripeToken = token.id;
                            data.stripeReturn = token.card;
                            data._token = '{{ csrf_token() }}';

                            if($('#remember-card')) {
                                rememberCard = $('#remember-card').prop('checked');

                                if(rememberCard) {
                                    data.last4 = token.card.last4;
                                }
                            }
                        }
                    }

                    $('#stripe-payment-form').submit(function(event) {
                        event.preventDefault();
                        $('.stripe-block-modal').css({'z-index': '11'});
                        $('.modal-overlay').css({'z-index': '10'});
                        $('.cp-spinner').css({'display': '', 'z-index': '12', 'bottom': '120px'});

                        var result = stripe.createToken(cardNumber);

                        result.then(function(result) {
                            if (result.error) {
                                // Inform the user if there was an error.
                                var errorElement = document.getElementById('card-errors');

                                errorElement.textContent = result.error.message;

                                return false;
                            } else {
                                stripeTokenHandler(result.token);
                            }
                        });
                    });
                });

                // this part of the script will be executed when close button on the modal is clicked
                $('#close-stripe-modal').on('click', function() {
                    $('.modal-overlay').css('display', 'none');
                    $('.stripe-block-modal').addClass('close');

                    stripeModalIsOpen = false;
                });

                // this part of the script will be executed when stripe method is not selected to reset the stripe related UI compoenents
                $('input[type="radio"]').not('#saved-cards input[type="radio"]').on('click', function() {
                    if ($(this).attr('id') == 'stripe') {
                        $('.add-card').css('display', 'block');
                        $('.stripe-cards-block').css('display', 'block');
                        $('#checkout-payment-continue-button').attr('disabled', 'disabled');
                    } else {
                        $('.add-card').css('display', 'none');
                        $('.modal-overlay').css('display', 'none');
                        $('.stripe-block-modal').addClass('close');
                        $('.stripe-cards-block').css('display', 'none');
                        $('#checkout-payment-continue-button').removeAttr('disabled');

                        stripeModalIsOpen = false;

                        if (savedCardSelected) {
                            $('.card-info > .radio-container > input[type="radio"]').each(function() {
                                $(this).prop('checked', false);

                                savedCardSelected = false;
                                savedCardSelectedId = null;
                            });
                        }
                    }
                });

                $('#checkout-payment-continue-button').on('click', function() {
                    //Submit the form
                    frm = $('#stripe-payment-form');
                    console.log(frm);
                    if(savedCardSelected) {
                        data._token = '{{ csrf_token() }}';
                        data.useSavedCard = savedCardSelected;
                        data.savedCardId = savedCardSelectedId;
                    }

                    $.ajax({
                        type: frm.attr('method'),
                        url: frm.attr('action'),
                        data: data,

                        success: function (data) {
                            console.log('request success');
                        },
                        error: function (data) {
                            console.log('request failed');
                        }
                    });
                });

                $('.card-info > .radio-container > input[type="radio"]').on('click', function() {
                    savedCardSelected = true;

                    savedCardSelectedId = $('.card-info > .radio-container > input[type="radio"]').attr('id');
                });

                $('#delete-card').on('click', function() {
                    var deleteId = $(this).data('id');

                    $.ajax({
                        type: 'GET',
                        url: '{{ route('stripe.delete.saved.cart') }}',
                        data: {
                            id: deleteId
                        },

                        success: function (data) {
                            if(data == 1) {
                                removeSavedCardNode(deleteId);
                            }
                        },
                        error: function (data) {
                            console.log(data);
                        }
                    });
                });
            });

            // this disables all the methods when the stripe token is generated
            function disableAllMethodsExceptStripe() {
                // $(".line-one .radio-container input:radio").not("#stripe-payment-form input:radio").not('#payment-form #stripe').attr('disabled', true);
                $(".line-one .radio-container input:radio").not('.line-one .radio-container #stripe').attr('disabled', true);

                $('.stripe-cards-block').css('display', 'none');

                $('.add-card').remove();
            }

            function removeSavedCardNode(deleteId) {
                nodeId = $('.card-info').each(function() {
                    if($(this).attr('id') == deleteId) {
                        $(this).remove();
                    }
                });
            }
        });
    </script>
@endif