<?php

return [
    'invalid_vat_format' => 'The given vat id has a wrong format',
    'security-warning' => 'Suspicious activity found!!!',
    'nothing-to-delete' => 'Nothing to delete',

    'layouts' => [
        'my-account' => 'Mijn account',
        'profile' => 'Profiel',
        'address' => 'Mijn adressen',
        'reviews' => 'Reviews',
        'wishlist' => 'Verlanglijst',
        'orders' => 'Bestellingen',
        'downloadable-products' => 'Downloadbare producten'
    ],

    'common' => [
        'error' => 'Something went wrong, please try again later.',
        'no-result-found' => 'We could not find any records.'
    ],

    'home' => [
        'page-title' => config('app.name') . ' - Home',
        'featured-products' => 'Aanbevolen producten',
        'new-products' => 'Nieuwe producten',
        'verify-email' => 'Verifieer uw e-mailaccount',
        'resend-verify-email' => 'Verificatie-e-mail opnieuw verzenden'
    ],

    'header' => [
        'title' => 'Account',
        'dropdown-text' => 'Manage Cart, Orders & Wishlist',
        'sign-in' => 'Aanmelden',
        'sign-up' => 'Registreren',
        'account' => 'Account',
        'cart' => 'Winkelwagen',
        'profile' => 'Profiel',
        'wishlist' => 'Verlanglijst',
        'logout' => 'Afmelden',
        'search-text' => 'Zoek producten hier'
    ],

    'minicart' => [
        'view-cart' => 'Bekijk winkelwagen',
        'checkout' => 'Afrekenen',
        'cart' => 'Winkelwagen',
        'zero' => '0'
    ],

    'footer' => [
        'subscribe-newsletter' => 'Inschrijven nieuwsbrief',
        'subscribe' => 'Inschrijven',
        'locale' => 'Taal',
        'currency' => 'Valuta',
    ],

    'subscription' => [
        'unsubscribe' => 'Afmelden',
        'subscribe' => 'Subscribe',
        'subscribed' => 'You are now subscribed to subscription emails.',
        'not-subscribed' => 'You can not be subscribed to subscription emails, please try again later.',
        'already' => 'You are already subscribed to our subscription list.',
        'unsubscribed' => 'You are unsubscribed from subscription mails.',
        'already-unsub' => 'You are already unsubscribed.',
        'not-subscribed' => 'Error! Mail can not be sent currently, please try again later.'
    ],

    'search' => [
        'no-results' => 'No Results Found',
        'page-title' => config('app.name') . ' - Search',
        'found-results' => 'Search Results Found',
        'found-result' => 'Search Result Found'
    ],

    'reviews' => [
        'title' => 'Titel',
        'add-review-page-title' => 'Review toevoegen',
        'write-review' => 'Schrijf een review',
        'review-title' => 'Geef uw review een titel',
        'product-review-page-title' => 'Product Review',
        'rating-reviews' => 'Rating & Reviews',
        'submit' => 'SUBMIT',
        'delete-all' => 'Alle reviews zijn verwijderd',
        'ratingreviews' => ':rating Ratings & :review Reviews',
        'star' => 'Ster',
        'percentage' => ':percentage %',
        'id-star' => 'star',
        'name' => 'Name',
    ],

    'customer' => [
        'signup-text' => [
            'account_exists' => 'Heb je al een account?',
            'title' => 'Aanmelden'
        ],

        'signup-form' => [
            'page-title' => 'Maak een nieuw klantaccount aan',
            'title' => 'Registreren',
            'firstname' => 'Voornaam',
            'lastname' => 'Naam',
            'email' => 'Email',
            'password' => 'Wachtwoord',
            'confirm_pass' => 'Wachtwoord bevestigen',
            'button_title' => 'Registreren',
            'agree' => 'Agree',
            'terms' => 'Terms',
            'conditions' => 'Conditions',
            'using' => 'by using this website',
            'agreement' => 'Agreement',
            'success' => 'Account succesvol aangemaakt.',
            'success-verify' => 'Account created successfully, an e-mail has been sent for verification.',
            'success-verify-email-unsent' => 'Account created successfully, but verification e-mail unsent.',
            'failed' => 'Error! Can not create your account, pleae try again later.',
            'already-verified' => 'Your account is already verified Or please try sending a new verification email again.',
            'verification-not-sent' => 'Error! Problem in sending verification email, please try again later.',
            'verification-sent' => 'Verification email sent',
            'verified' => 'Uw account is geverifieerd, probeer nu in te loggen.',
            'verify-failed' => 'We cannot verify your mail account.',
            'dont-have-account' => 'U heeft geen account bij ons.',
            'success' => 'Account Created Successfully',
            'failed' => 'Error! Cannot Create Your Account, Try Again Later',
            'already-verified' => 'Your Account is already verified Or Please Try Sending A New Verification Email Again',
            'verification-not-sent' => 'Error! Problem In Sending Verification Email, Try Again Later',
            'verify-failed' => 'We Cannot Verify Your Mail Account',
            'dont-have-account' => 'You Do Not Have Account With Us',
            'customer-registration' => 'Customer Registered Successfully'
        ],

        'login-text' => [
            'no_account' => 'Do not have account',
            'title' => 'Sign Up',
        ],

        'login-form' => [
            'page-title' => 'Aanmelden',
            'title' => 'Aanmelden',
            'email' => 'Email',
            'password' => 'Wachtwoord',
            'forgot_pass' => 'Wachtwoord vergeten?',
            'button_title' => 'Aanmelden',
            'remember' => 'Remember Me',
            'footer' => '© Copyright :year Webkul Software, All rights reserved',
            'invalid-creds' => 'Please check your credentials and try again.',
            'verify-first' => 'Verify your email account first.',
            'not-activated' => 'Your activation seeks admin approval',
            'resend-verification' => 'Resend verification mail again'
        ],

        'forgot-password' => [
            'title' => 'Recover Password',
            'email' => 'Email',
            'submit' => 'Send Password Reset Email',
            'page_title' => 'Forgot your password ?'
        ],

        'reset-password' => [
            'title' => 'Wachtwoord herstellen',
            'email' => 'Uw email-adres',
            'password' => 'Wachtwoord',
            'confirm-password' => 'Wachtwoord bevestigen',
            'back-link-title' => 'Back to Sign In',
            'submit-btn-title' => 'Wachtwoord herstellen'
        ],

        'account' => [
            'dashboard' => 'Edit Profile',
            'menu' => 'Menu',

            'profile' => [
                'index' => [
                    'page-title' => 'Profiel',
                    'title' => 'Profiel',
                    'edit' => 'Wijzig',
                ],

                'edit-success' => 'Profile updated successfully.',
                'edit-fail' => 'Error! Profile cannot be updated, please try again later.',
                'unmatch' => 'The old password does not match.',

                'fname' => 'Voornaam',
                'lname' => 'Naam',
                'gender' => 'Geslacht',
                'other' => 'Anders',
                'male' => 'Man',
                'female' => 'Vrouw',
                'dob' => 'Geboortedatum',
                'phone' => 'Telefoonnummer',
                'email' => 'Email',
                'opassword' => 'Huidig wachtwoord',
                'password' => 'Wachtwoord',
                'cpassword' => 'Wachtwoord bevestigen',
                'submit' => 'Profiel wijzigen',

                'edit-profile' => [
                    'title' => 'Profiel wijzigen',
                    'page-title' => 'Profiel wijzigen'
                ]
            ],

            'address' => [
                'index' => [
                    'page-title' => 'Adres',
                    'title' => 'Mijn adressen',
                    'add' => 'Adres toevoegen',
                    'edit' => 'Wijzigen',
                    'empty' => 'U heeft hier geen opgeslagen adressen. Voeg minstens één adres toe door op de onderstaande link te klikken',
                    'create' => 'Address toevoegen',
                    'delete' => 'Verwijderen',
                    'make-default' => 'Maak standaard',
                    'default' => 'Standaard',
                    'contact' => 'Contact',
                    'confirm-delete' =>  'Wilt u dit adres echt verwijderen?',
                    'default-delete' => 'Standaardadres kan niet worden gewijzigd.',
                    'enter-password' => 'Voer uw wachtwoord in.',
                ],

                'create' => [
                    'page-title' => 'Add Address Form',
                    'company_name' => 'Bedrijfsnaam',
                    'first_name' => 'Voornaam',
                    'last_name' => 'Naam',
                    'vat_id' => 'BTW nummer',
                    'vat_help_note' => '[bv. BE01234567891]',
                    'title' => 'Adres toevoegen',
                    'street-address' => 'Adres',
                    'country' => 'Land',
                    'state' => 'Staat / Provincie',
                    'select-state' => 'Selecteer een regio, staat of provincie',
                    'city' => 'Gemeente',
                    'postcode' => 'Postcode',
                    'phone' => 'Telefoonnummer',
                    'submit' => 'Adres bewaren',
                    'success' => 'Adres is succesvol toegevoegd.',
                    'error' => 'Adres kan niet worden toegevoegd.'
                ],

                'edit' => [
                    'page-title' => 'Edit Address',
                    'company_name' => 'Company name',
                    'first_name' => 'Voornaam',
                    'last_name' => 'Naam',
                    'vat_id' => 'BTW nummer',
                    'title' => 'Adres wijzigen',
                    'street-address' => 'Adres',
                    'submit' => 'Opslaan',
                    'success' => 'Adres succesvol bijgewerkt..',
                ],
                'delete' => [
                    'success' => 'Adres succesvol verwijderd.',
                    'failure' => 'Adres kan niet verwijderd worden.',
                    'wrong-password' => 'Verkeerd wachtwoord !'
                ]
            ],

            'order' => [
                'index' => [
                    'page-title' => 'Bestellingen',
                    'title' => 'Bestellingen',
                    'order_id' => 'Bestelnummer',
                    'date' => 'Datum',
                    'status' => 'Status',
                    'total' => 'Totaal',
                    'order_number' => 'Bestelnummer'
                ],

                'view' => [
                    'page-tile' => 'Order #:order_id',
                    'info' => 'Information',
                    'placed-on' => 'Placed On',
                    'products-ordered' => 'Products Ordered',
                    'invoices' => 'Facturen',
                    'shipments' => 'Verzendingen',
                    'SKU' => 'SKU',
                    'product-name' => 'Naam',
                    'qty' => 'Aantal',
                    'item-status' => 'Item Status',
                    'item-ordered' => 'Ordered (:qty_ordered)',
                    'item-invoice' => 'Invoiced (:qty_invoiced)',
                    'item-shipped' => 'shipped (:qty_shipped)',
                    'item-canceled' => 'Canceled (:qty_canceled)',
                    'item-refunded' => 'Refunded (:qty_refunded)',
                    'price' => 'Prijs',
                    'total' => 'Total',
                    'subtotal' => 'Subtotal',
                    'shipping-handling' => 'Shipping & Handling',
                    'tax' => 'Tax',
                    'discount' => 'Discount',
                    'tax-percent' => 'Tax Percent',
                    'tax-amount' => 'Tax Amount',
                    'discount-amount' => 'Discount Amount',
                    'grand-total' => 'Grand Total',
                    'total-paid' => 'Totaal betaald',
                    'total-refunded' => 'Total Refunded',
                    'total-due' => 'Total Due',
                    'shipping-address' => 'Shipping Address',
                    'billing-address' => 'Billing Address',
                    'shipping-method' => 'Shipping Method',
                    'payment-method' => 'Payment Method',
                    'individual-invoice' => 'Invoice #:invoice_id',
                    'individual-shipment' => 'Shipment #:shipment_id',
                    'print' => 'Print',
                    'invoice-id' => 'Invoice Id',
                    'order-id' => 'Order Id',
                    'order-date' => 'Order Date',
                    'bill-to' => 'Bill to',
                    'ship-to' => 'Ship to',
                    'contact' => 'Contact',
                    'refunds' => 'Refunds',
                    'individual-refund' => 'Refund #:refund_id',
                    'adjustment-refund' => 'Adjustment Refund',
                    'adjustment-fee' => 'Adjustment Fee',
                ]
            ],

            'wishlist' => [
                'page-title' => 'Verlanglijst',
                'title' => 'Verlanglijst',
                'deleteall' => 'Alles verwijderen',
                'moveall' => 'Move All Products To Cart',
                'move-to-cart' => 'Move To Cart',
                'error' => 'Cannot add product to wishlist due to unknown problems, please checkback later',
                'add' => 'Item successfully added to wishlist',
                'remove' => 'Item successfully removed from wishlist',
                'moved' => 'Item successfully moved To cart',
                'option-missing' => 'Product options are missing, so item can not be moved to the wishlist.',
                'move-error' => 'Item cannot be moved to wishlist, Please try again later',
                'success' => 'Item successfully added to wishlist',
                'failure' => 'Item cannot be added to wishlist, Please try again later',
                'already' => 'Item already present in your wishlist',
                'removed' => 'Item successfully removed from wishlist',
                'remove-fail' => 'Item cannot Be removed from wishlist, Please try again later',
                'empty' => 'You do not have any items in your wishlist',
                'remove-all-success' => 'All the items from your wishlist have been removed',
            ],

            'downloadable_products' => [
                'title' => 'Downloadbare producten',
                'order-id' => 'Bestelnummer',
                'date' => 'Datum',
                'name' => 'Titel',
                'status' => 'Status',
                'pending' => 'Pending',
                'available' => 'Beschikbaar',
                'expired' => 'Expired',
                'remaining-downloads' => 'Resterende downloads',
                'unlimited' => 'Onbeperkt',
                'download-error' => 'Downloadlink is verlopen.'
            ],

            'review' => [
                'index' => [
                    'title' => 'Reviews',
                    'page-title' => 'Reviews'
                ],

                'view' => [
                    'page-tile' => 'Review #:id',
                ]
            ]
        ]
    ],

    'products' => [
        'layered-nav-title' => 'Shop per',
        'price-label' => 'As low as',
        'remove-filter-link-title' => 'Clear All',
        'sort-by' => 'Sort By',
        'from-a-z' => 'From A-Z',
        'from-z-a' => 'From Z-A',
        'newest-first' => 'Newest First',
        'oldest-first' => 'Oldest First',
        'cheapest-first' => 'Cheapest First',
        'expensive-first' => 'Expensive First',
        'show' => 'Show',
        'pager-info' => 'Showing :showing of :total Items',
        'description' => 'Description',
        'specification' => 'Specification',
        'total-reviews' => ':total Reviews',
        'total-rating' => ':total_rating Ratings & :total_reviews Reviews',
        'by' => 'By :name',
        'up-sell-title' => 'We found other products you might like!',
        'related-product-title' => 'Related Products',
        'cross-sell-title' => 'More choices',
        'reviews-title' => 'Ratings & Reviews',
        'write-review-btn' => 'Write Review',
        'choose-option' => 'Choose an option',
        'sale' => 'Sale',
        'new' => 'New',
        'empty' => 'No products available in this category',
        'add-to-cart' => 'Voeg toe aan winkelkar',
        'buy-now' => 'Koop nu',
        'whoops' => 'Whoops!',
        'quantity' => 'Aantal',
        'in-stock' => 'Op voorraad',
        'out-of-stock' => 'Niet op voorraad',
        'view-all' => 'View All',
        'select-above-options' => 'Please select above options first.',
        'less-quantity' => 'Quantity can not be less than one.',
        'samples' => 'Voorbeelden',
        'links' => 'Links',
        'sample' => 'Voorbeeld',
        'name' => 'Naam',
        'qty' => 'Aantal',
        'starting-at' => 'Starting at',
        'customize-options' => 'Customize Options',
        'choose-selection' => 'Choose a selection',
        'your-customization' => 'Your Customization',
        'total-amount' => 'Total Amount',
        'none' => 'None'
    ],

    // 'reviews' => [
    //     'empty' => 'You Have Not Reviewed Any Of Product Yet'
    // ]

    'buynow' => [
        'no-options' => 'Please select options before buying this product.'
    ],

    'checkout' => [
        'cart' => [
            'integrity' => [
                'missing_fields' => 'Some required fields missing for this product.',
                'missing_options' => 'Options are missing for this product.',
                'missing_links' => 'Downloadable links are missing for this product.',
                'qty_missing' => 'Atleast one product should have more than 1 quantity.',
                'qty_impossible' => 'Cannot add more than one of these products to cart.'
            ],
            'create-error' => 'Encountered some issue while making cart instance.',
            'title' => 'Shopping Cart',
            'empty' => 'Your shopping cart is empty',
            'update-cart' => 'Update Cart',
            'continue-shopping' => 'Continue Shopping',
            'proceed-to-checkout' => 'Proceed To Checkout',
            'remove' => 'Remove',
            'remove-link' => 'Remove',
            'move-to-wishlist' => 'Move to Wishlist',
            'move-to-wishlist-success' => 'Item moved to wishlist successfully.',
            'move-to-wishlist-error' => 'Cannot move item to wishlist, please try again later.',
            'add-config-warning' => 'Please select option before adding to cart.',
            'quantity' => [
                'quantity' => 'Aantal',
                'success' => 'Cart Item(s) successfully updated.',
                'illegal' => 'Quantity cannot be lesser than one.',
                'inventory_warning' => 'The requested quantity is not available, please try again later.',
                'error' => 'Cannot update the item(s) at the moment, please try again later.'
            ],

            'item' => [
                'error_remove' => 'No items to remove from the cart.',
                'success' => 'Item was successfully added to cart.',
                'success-remove' => 'Item was removed successfully from the cart.',
                'error-add' => 'Item cannot be added to cart, please try again later.',
            ],
            'quantity-error' => 'Requested quantity is not available.',
            'cart-subtotal' => 'Subtotaal',
            'cart-remove-action' => 'Do you really want to do this ?',
            'partial-cart-update' => 'Only some of the product(s) were updated',
            'link-missing' => ''
        ],

        'onepage' => [
            'title' => 'Checkout',
            'information' => 'Informatie',
            'shipping' => 'Verzending',
            'payment' => 'Betaling',
            'complete' => 'Complete',
            'billing-address' => 'Factuur adres',
            'sign-in' => 'Sign In',
            'company-name' => 'Company Name',
            'first-name' => 'First Name',
            'last-name' => 'Last Name',
            'email' => 'Email',
            'address1' => 'Street Address',
            'city' => 'City',
            'state' => 'State',
            'select-state' => 'Select a region, state or province',
            'postcode' => 'Zip/Postcode',
            'phone' => 'Telephone',
            'country' => 'Country',
            'order-summary' => 'Order Summary',
            'shipping-address' => 'Shipping Address',
            'use_for_shipping' => 'Ship to this address',
            'continue' => 'Continue',
            'shipping-method' => 'Select Shipping Method',
            'payment-methods' => 'Select Payment Method',
            'payment-method' => 'Payment Method',
            'summary' => 'Order Summary',
            'price' => 'Prijs',
            'quantity' => 'Aantal',
            'billing-address' => 'Billing Address',
            'shipping-address' => 'Shipping Address',
            'contact' => 'Contact',
            'place-order' => 'Place Order',
            'new-address' => 'Add New Address',
            'save_as_address' => 'Save as Address',
            'apply-coupon' => 'Apply Coupon',
            'amt-payable' => 'Amount Payable',
            'got' => 'Got',
            'free' => 'Free',
            'coupon-used' => 'Coupon Used',
            'applied' => 'Applied',
            'back' => 'Back',
            'cash-desc' => 'Cash On Delivery',
            'money-desc' => 'Money Transfer',
            'paypal-desc' => 'Paypal Standard',
            'free-desc' => 'This is a free shipping',
            'flat-desc' => 'This is a flat rate',
            'password' => 'Wachtwoord',
            'login-exist-message' => 'You already have an account with us, Sign in or continue as guest.',
            'enter-coupon-code' => 'Enter Coupon Code'
        ],

        'total' => [
            'order-summary' => 'Overzicht van de bestelling',
            'sub-total' => 'Items',
            'grand-total' => 'Eindtotaal',
            'delivery-charges' => 'Verzendkosten',
            'tax' => 'BTW',
            'discount' => 'Korting',
            'price' => 'Prijs',
            'disc-amount' => 'Amount discounted',
            'new-grand-total' => 'New Grand Total',
            'coupon' => 'Coupon',
            'coupon-applied' => 'Applied Coupon',
            'remove-coupon' => 'Remove Coupon',
            'cannot-apply-coupon' => 'Cannot Apply Coupon',
            'invalid-coupon' => 'Coupon code is invalid.',
            'success-coupon' => 'Coupon code applied successfully.',
            'coupon-apply-issue' => 'Coupon code can\'t be applied.'
        ],

        'success' => [
            'title' => 'Order successfully placed',
            'thanks' => 'Thank you for your order!',
            'order-id-info' => 'Your order id is #:order_id',
            'info' => 'We will email you, your order details and tracking information'
        ]
    ],

    'mail' => [
        'order' => [
            'subject' => 'New Order Confirmation',
            'heading' => 'Order Confirmation!',
            'dear' => 'Dear :customer_name',
            'dear-admin' => 'Dear :admin_name',
            'greeting' => 'Thanks for your Order :order_id placed on :created_at',
            'greeting-admin' => 'Order Id :order_id placed on :created_at',
            'summary' => 'Summary of Order',
            'shipping-address' => 'Shipping Address',
            'billing-address' => 'Billing Address',
            'contact' => 'Contact',
            'shipping' => 'Shipping Method',
            'payment' => 'Payment Method',
            'price' => 'Prijs',
            'quantity' => 'Aantal',
            'subtotal' => 'Subtotal',
            'shipping-handling' => 'Shipping & Handling',
            'tax' => 'Tax',
            'discount' => 'Discount',
            'grand-total' => 'Grand Total',
            'final-summary' => 'Thanks for showing your interest in our store we will send you tracking number once it shipped',
            'help' => 'If you need any kind of help please contact us at :support_email',
            'thanks' => 'Thanks!',
            'cancel' => [
                'subject' => 'Order Cancel Confirmation',
                'heading' => 'Order Cancelled',
                'dear' => 'Dear :customer_name',
                'greeting' => 'You Order with order id #:order_id placed on :created_at has been cancelled',
                'summary' => 'Summary of Order',
                'shipping-address' => 'Shipping Address',
                'billing-address' => 'Billing Address',
                'contact' => 'Contact',
                'shipping' => 'Shipping Method',
                'payment' => 'Payment Method',
                'subtotal' => 'Subtotal',
                'shipping-handling' => 'Shipping & Handling',
                'tax' => 'Tax',
                'discount' => 'Discount',
                'grand-total' => 'Grand Total',
                'final-summary' => 'Thanks for showing your interest in our store',
                'help' => 'If you need any kind of help please contact us at :support_email',
                'thanks' => 'Thanks!',
            ]
        ],

        'invoice' => [
            'heading' => 'Your invoice #:invoice_id for Order #:order_id',
            'subject' => 'Invoice for your order #:order_id',
            'summary' => 'Summary of Invoice',
        ],

        'shipment' => [
            'heading' => 'Shipment #:shipment_id  has been generated for Order #:order_id',
            'inventory-heading' => 'New shipment #:shipment_id had been generated for Order #:order_id',
            'subject' => 'Shipment for your order #:order_id',
            'inventory-subject' => 'New shipment had been generated for Order #:order_id',
            'summary' => 'Summary of Shipment',
            'carrier' => 'Carrier',
            'tracking-number' => 'Tracking Number',
            'greeting' => 'An order :order_id has been placed on :created_at',
        ],

        'refund' => [
            'heading' => 'Your Refund #:refund_id for Order #:order_id',
            'subject' => 'Refund for your order #:order_id',
            'summary' => 'Summary of Refund',
            'adjustment-refund' => 'Adjustment Refund',
            'adjustment-fee' => 'Adjustment Fee'
        ],

        'forget-password' => [
            'subject' => 'Customer Reset Password',
            'dear' => 'Dear :name',
            'info' => 'You are receiving this email because we received a password reset request for your account',
            'reset-password' => 'Reset Password',
            'final-summary' => 'If you did not request a password reset, no further action is required',
            'thanks' => 'Thanks!'
        ],

        'customer' => [
            'new' => [
                'dear' => 'Dear :customer_name',
                'username-email' => 'UserName/Email',
                'subject' => 'New Customer Registration',
                'password' => 'Wachtwoord',
                'summary' => 'Your account has been created.
                Your account details are below: ',
                'thanks' => 'Thanks!',
            ],

            'registration' => [
                'subject' => 'New Customer Registration',
                'customer-registration' => 'Customer Registered Successfully',
                'dear' => 'Dear :customer_name',
                'greeting' => 'Welcome and thank you for registering with us!',
                'summary' => 'Your account has now been created successfully and you can login using your email address and password credentials. Upon logging in, you will be able to access other services including reviewing past orders, wishlists and editing your account information.',
                'thanks' => 'Thanks!',
            ],

            'verification' => [
                'heading' => config('app.name') . ' - Email Verification',
                'subject' => 'Verification Mail',
                'verify' => 'Verify Your Account',
                'summary' => 'This is the mail to verify that the email address you entered is yours.
                Kindly click the Verify Your Account button below to verify your account.'
            ],

            'subscription' => [
                'subject' => 'Subscription Email',
                'greeting' => ' Welcome to ' . config('app.name') . ' - Email Subscription',
                'unsubscribe' => 'Unsubscribe',
                'summary' => 'Thanks for putting me into your inbox. It’s been a while since you’ve read ' . config('app.name') . ' email, and we don’t want to overwhelm your inbox. If you still do not want to receive
                the latest email marketing news then for sure click the button below.'
            ]
        ]
    ],

    'webkul' => [
        'copy-right' => '© Copyright :year Webkul Software, All rights reserved',
    ],

    'response' => [
        'create-success' => ':name created successfully.',
        'update-success' => ':name updated successfully.',
        'delete-success' => ':name deleted successfully.',
        'submit-success' => ':name submitted successfully.'
    ],
];
