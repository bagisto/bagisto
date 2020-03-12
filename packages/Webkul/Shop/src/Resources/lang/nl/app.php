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
                    'page-title' => 'verander adres',
                    'company_name' => 'Bedrijfsnaam',
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
                    'status' => 'Toestand',
                    'total' => 'Totaal',
                    'order_number' => 'Bestelnummer'
                ],

                'view' => [
                    'page-tile' => 'Bestellen #:order_id',
                    'info' => 'Informatie',
                    'placed-on' => 'Geplaatst op',
                    'products-ordered' => 'Bestelde producten',
                    'invoices' => 'Facturen',
                    'shipments' => 'Verzendingen',
                    'SKU' => 'SKU',
                    'product-name' => 'Naam',
                    'qty' => 'Aantal',
                    'item-status' => 'Artikelstatus',
                    'item-ordered' => 'Besteld (:qty_ordered)',
                    'item-invoice' => 'Gefactureerd (:qty_invoiced)',
                    'item-shipped' => 'verzonden (:qty_shipped)',
                    'item-canceled' => 'Geannuleerd (:qty_canceled)',
                    'item-refunded' => 'Terugbetaald (:qty_refunded)',
                    'price' => 'Prijs',
                    'total' => 'Total',
                    'subtotal' => 'Subtotaal',
                    'shipping-handling' => 'Verzending en afhandeling',
                    'tax' => 'Belasting',
                    'discount' => 'Korting',
                    'tax-percent' => 'Belastingpercentage',
                    'tax-amount' => 'Belastingbedrag',
                    'discount-amount' => 'Korting hoeveelheid',
                    'grand-total' => 'Eindtotaal',
                    'total-paid' => 'Totaal betaald',
                    'total-refunded' => 'Totaal terugbetaald ',
                    'total-due' => 'Totaal verschuldigd',
                    'shipping-address' => 'Verzendingsadres',
                    'billing-address' => 'factuur adres',
                    'shipping-method' => 'Verzendmethode',
                    'payment-method' => 'Betalingswijze',
                    'individual-invoice' => 'Factuur #:invoice_id',
                    'individual-shipment' => 'Verzending #:shipment_id',
                    'print' => 'Afdrukken',
                    'invoice-id' => 'Factuur-id',
                    'order-id' => 'Order ID',
                    'order-date' => 'Besteldatum',
                    'bill-to' => 'Rekening naar',
                    'ship-to' => 'Verzend naar',
                    'contact' => 'Contact',
                    'refunds' => 'Restituties',
                    'individual-refund' => 'Terugbetaling #:refund_id',
                    'adjustment-refund' => 'Aanpassing restitutie',
                    'adjustment-fee' => 'Aanpassingskosten',
                ]
            ],

            'wishlist' => [
                'page-title' => 'Verlanglijst',
                'title' => 'Verlanglijst',
                'deleteall' => 'Alles verwijderen',
                'moveall' => 'Verplaats alle producten naar winkelwagen',
                'move-to-cart' => 'Verplaatsen naar winkelwagen',
                'error' => 'Kan product niet toevoegen aan verlanglijstje vanwege onbekende problemen, kom later terug',
                'add' => 'Item succesvol toegevoegd aan verlanglijstje',
                'remove' => 'Item succesvol verwijderd van verlanglijstje',
                'moved' => 'Artikel succesvol verplaatst naar winkelwagen',
                'option-missing' => 'Productopties ontbreken, dus item kan niet naar de verlanglijst worden verplaatst.',
                'move-error' => 'Item kan niet naar de verlanglijst worden verplaatst. Probeer het later opnieuw',
                'success' => 'Item succesvol toegevoegd aan verlanglijstje',
                'failure' => 'Item kan niet worden toegevoegd aan verlanglijstje, probeer het later opnieuw',
                'already' => 'Item al aanwezig in uw verlanglijstje',
                'removed' => 'Item succesvol verwijderd van verlanglijstje',
                'remove-fail' => 'Item kan niet van de verlanglijst worden verwijderd. Probeer het later opnieuw',
                'empty' => 'U heeft geen artikelen op uw verlanglijstje',
                'remove-all-success' => 'Alle items van uw verlanglijst zijn verwijderd',
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
                    'title' => 'Recensies',
                    'page-title' => 'Recensies'
                ],

                'view' => [
                    'page-tile' => 'Recensies #:id',
                ]
            ]
        ]
    ],

    'products' => [
        'layered-nav-title' => 'Winkelen per ',
        'price-label' => 'Zo laag als',
        'remove-filter-link-title' => 'Wis alles',
        'sort-by' => 'Sorteer op',
        'from-a-z' => 'Van A-Z',
        'from-z-a' => 'Van Z-A',
        'newest-first' => 'Nieuwste eerst',
        'oldest-first' => 'Oudste eerst',
        'cheapest-first' => 'Goedkoopste eerst',
        'expensive-first' => 'Eerst duur',
        'show' => 'Tonen',
        'pager-info' => 'Weergegeven :showing van :total Artikelen',
        'description' => 'Beschrijving',
        'specification' => 'Specificatie',
        'total-reviews' => ':total Recensies',
        'total-rating' => ':total_rating Waarderingen & :total_reviews Recensies',
        'by' => 'Door :name',
        'up-sell-title' => 'We hebben andere producten gevonden die je misschien leuk vindt!',
        'related-product-title' => 'gerelateerde producten',
        'cross-sell-title' => 'Meer keuzes',
        'reviews-title' => 'Beoordelingen en recensies',
        'write-review-btn' => 'Schrijf recensie',
        'choose-option' => 'Kies een optie',
        'sale' => 'Uitverkoop',
        'new' => 'Nieuw',
        'empty' => 'Geen producten beschikbaar in deze categorie',
        'add-to-cart' => 'Voeg toe aan winkelkar',
        'buy-now' => 'Koop nu',
        'whoops' => 'Whoops!',
        'quantity' => 'Aantal',
        'in-stock' => 'Op voorraad',
        'out-of-stock' => 'Niet op voorraad',
        'view-all' => 'Bekijk alles',
        'select-above-options' => 'Selecteer eerst bovenstaande opties.',
        'less-quantity' => 'De hoeveelheid mag niet kleiner zijn dan één.',
        'samples' => 'Voorbeelden',
        'links' => 'Links',
        'sample' => 'Voorbeeld',
        'name' => 'Naam',
        'qty' => 'Aantal',
        'starting-at' => 'Beginnend bij',
        'customize-options' => 'Pas opties aan',
        'choose-selection' => 'Kies een selectie',
        'your-customization' => 'Uw maatwerk',
        'total-amount' => 'Totale hoeveelheid',
        'none' => 'Geen'
    ],

    // 'reviews' => [
    //     'empty' => 'You Have Not Reviewed Any Of Product Yet'
    // ]

    'buynow' => [
        'no-options' => 'Selecteer opties voordat u dit product koopt.'
    ],

    'checkout' => [
        'cart' => [
            'integrity' => [
                'missing_fields' => 'Sommige verplichte velden ontbreken voor dit product.',
                'missing_options' => 'Er ontbreken opties voor dit product.',
                'missing_links' => 'Downloadbare links ontbreken voor dit product.',
                'qty_missing' => 'Minstens één product moet meer dan 1 hoeveelheid bevatten.',
                'qty_impossible' => 'Kan niet meer dan één van deze producten toevoegen aan winkelwagen.'
            ],
            'create-error' => 'Er is een probleem opgetreden bij het maken van een winkelwageninstantie.',
            'title' => 'Winkelwagen',
            'empty' => 'Uw winkelwagen is leeg',
            'update-cart' => 'Winkelwagen bijwerken',
            'continue-shopping' => 'Doorgaan met winkelen',
            'proceed-to-checkout' => 'Ga naar de kassa',
            'remove' => 'Verwijderen',
            'remove-link' => 'Verwijderen',
            'move-to-wishlist' => 'Verplaats naar wens lijst',
            'move-to-wishlist-success' => 'Item is succesvol naar de verlanglijst verplaatst.',
            'move-to-wishlist-error' => 'Kan item niet naar verlanglijstje verplaatsen, probeer het later opnieuw.',
            'add-config-warning' => 'Selecteer een optie voordat u deze aan de winkelwagen toevoegt.',
            'quantity' => [
                'quantity' => 'Aantal',
                'success' => 'Winkelwagenitem (s) succesvol bijgewerkt.',
                'illegal' => 'Hoeveelheid kan niet kleiner zijn dan één.',
                'inventory_warning' => 'De gevraagde hoeveelheid is niet beschikbaar, probeer het later opnieuw.',
                'error' => 'Kan de item (s) momenteel niet updaten. Probeer het later opnieuw.'
            ],

            'item' => [
                'error_remove' => 'Geen items om uit de winkelwagen te verwijderen.',
                'success' => 'Artikel is succesvol toegevoegd aan winkelwagen.',
                'success-remove' => 'Item is met succes uit de winkelwagen verwijderd.',
                'error-add' => 'Item kan niet aan winkelwagen worden toegevoegd. Probeer het later opnieuw.',
            ],
            'quantity-error' => 'Gevraagde hoeveelheid is niet beschikbaar.',
            'cart-subtotal' => 'Subtotaal',
            'cart-remove-action' => 'Wil je dit echt doen?',
            'partial-cart-update' => 'Slechts enkele van de producten zijn bijgewerkt',
            'link-missing' => ''
        ],

        'onepage' => [
            'title' => 'Uitchecken',
            'information' => 'Informatie',
            'shipping' => 'Verzending',
            'payment' => 'Betaling',
            'complete' => 'Complete',
            'billing-address' => 'Factuur adres',
            'sign-in' => 'Inloggen',
            'company-name' => 'Bedrijfsnaam',
            'first-name' => 'Voornaam',
            'last-name' => 'Achternaam',
            'email' => 'E-mail',
            'address1' => 'Woonadres',
            'city' => 'stad',
            'state' => 'Staat',
            'select-state' => 'Selecteer een regio, staat of provincie',
            'postcode' => 'Zip/Postcode',
            'phone' => 'Telefoon',
            'country' => 'buiten',
            'order-summary' => 'overzicht van de bestelling',
            'shipping-address' => 'Verzendingsadres',
            'use_for_shipping' => 'Verzenden naar dit adres',
            'continue' => 'Doorgaan met',
            'shipping-method' => 'selecteer verzendmethode',
            'payment-methods' => 'Selecteer betaalmethode',
            'payment-method' => 'Betalingswijze',
            'summary' => 'overzicht van de bestelling',
            'price' => 'Prijs',
            'quantity' => 'Aantal',
            'billing-address' => 'factuur adres',
            'shipping-address' => 'Verzendingsadres',
            'contact' => 'Contact',
            'place-order' => 'Plaats bestelling',
            'new-address' => 'Nieuw adres toevoegen',
            'save_as_address' => 'Opslaan als adres',
            'apply-coupon' => 'gebruik coupon',
            'amt-payable' => 'Te betalen bedrag',
            'got' => 'Kreeg',
            'free' => 'Vrij',
            'coupon-used' => 'Coupon gebruikt',
            'applied' => 'Toegepast',
            'back' => 'Terug',
            'cash-desc' => 'Rembours',
            'money-desc' => 'Overschrijving',
            'paypal-desc' => 'Paypal Standard',
            'free-desc' => 'Dit is een gratis verzending',
            'flat-desc' => 'Dit is een vast tarief',
            'password' => 'Wachtwoord',
            'login-exist-message' => 'U heeft al een account bij ons, log in of ga verder als gast.',
            'enter-coupon-code' => 'Vul couponcode in'
        ],

        'total' => [
            'order-summary' => 'Overzicht van de bestelling',
            'sub-total' => 'Items',
            'grand-total' => 'Eindtotaal',
            'delivery-charges' => 'Verzendkosten',
            'tax' => 'BTW',
            'discount' => 'Korting',
            'price' => 'Prijs',
            'disc-amount' => 'Verdisconteerd bedrag',
            'new-grand-total' => 'Nieuw eindtotaal',
            'coupon' => 'Coupon',
            'coupon-applied' => 'Toegepaste coupon',
            'remove-coupon' => 'Coupon verwijderen',
            'cannot-apply-coupon' => 'Kan coupon niet toepassen',
            'invalid-coupon' => 'Couponcode is ongeldig.',
            'success-coupon' => 'Couponcode succesvol toegepast.',
            'coupon-apply-issue' => 'Coupon code can\'t be applied.'
        ],

        'success' => [
            'title' => 'Bestelling succesvol geplaatst',
            'thanks' => 'Bedankt voor je bestelling!',
            'order-id-info' => 'Uw bestellings-ID is #:order_id',
            'info' => 'We sturen u een e-mail met uw bestelgegevens en trackinginformatie'
        ]
    ],

    'mail' => [
        'order' => [
            'subject' => 'Nieuwe orderbevestiging',
            'heading' => 'Order bevestiging!',
            'dear' => 'Lieve :customer_name',
            'dear-admin' => 'Lieve :admin_name',
            'greeting' => 'Bedankt voor je bestelling :order_id geplaatst op :created_at',
            'greeting-admin' => 'Order Id :order_id geplaatst op :created_at',
            'summary' => 'Samenvatting van de bestelling',
            'shipping-address' => 'Verzendingsadres',
            'billing-address' => 'factuur adres',
            'contact' => 'Contact',
            'shipping' => 'Verzendmethode',
            'payment' => 'Betalingswijze',
            'price' => 'Prijs',
            'quantity' => 'Aantal',
            'subtotal' => 'Subtotal',
            'shipping-handling' => 'Verzending en afhandeling',
            'tax' => 'Belasting',
            'discount' => 'Korting',
            'grand-total' => 'Eindtotaal',
            'final-summary' => 'Bedankt voor het tonen van uw interesse in onze winkel.We sturen u een trackingnummer zodra het is verzonden',
            'help' => 'Als u hulp nodig heeft, neem dan contact met ons op via :support_email',
            'thanks' => 'Thanks!',
            'cancel' => [
                'subject' => 'Order Annuleren Bevestiging',
                'heading' => 'Bestelling geannuleerd',
                'dear' => 'Lieve :customer_name',
                'greeting' => 'U bestelt met order-ID #:order_id geplaatst op :created_at is geannuleerd',
                'summary' => 'Samenvatting van de bestelling',
                'shipping-address' => 'Verzendingsadres',
                'billing-address' => 'factuur adres',
                'contact' => 'Contact',
                'shipping' => 'Verzendmethode',
                'payment' => 'Betalingswijze',
                'subtotal' => 'Subtotaal',
                'shipping-handling' => 'Verzending en afhandeling',
                'tax' => 'Belasting',
                'discount' => 'Korting',
                'grand-total' => 'Eindtotaal',
                'final-summary' => 'Bedankt voor het tonen van uw interesse in onze winkel',
                'help' => 'Als u hulp nodig heeft, neem dan contact met ons op via :support_email',
                'thanks' => 'Bedankt!',
            ]
        ],

        'invoice' => [
            'heading' => 'Je factuur #:invoice_id voor bestelling #:order_id',
            'subject' => 'Factuur voor uw bestelling #:order_id',
            'summary' => 'Samenvatting van factuur',
        ],

        'shipment' => [
            'heading' => 'Verzending #:shipment_id  is gegenereerd voor Order #:order_id',
            'inventory-heading' => 'Nieuwe zending #:shipment_id was gegenereerd voor Order #:order_id',
            'subject' => 'Verzending voor uw bestelling #:order_id',
            'inventory-subject' => 'Er is een nieuwe zending gegenereerd voor de bestelling #:order_id',
            'summary' => 'Samenvatting van verzending',
            'carrier' => 'Drager',
            'tracking-number' => 'Volg nummer',
            'greeting' => 'Een bestelling :order_id is geplaatst op :created_at',
        ],

        'refund' => [
            'heading' => 'Uw terugbetaling #:refund_id voor bestelling #:order_id',
            'subject' => 'Restitutie voor uw bestelling #:order_id',
            'summary' => 'Samenvatting van terugbetaling',
            'adjustment-refund' => 'Aanpassing restitutie',
            'adjustment-fee' => 'Aanpassingskosten'
        ],

        'forget-password' => [
            'subject' => 'Klant reset wachtwoord',
            'dear' => 'Lieve :name',
            'info' => 'U ontvangt deze e-mail omdat we een verzoek voor het opnieuw instellen van uw wachtwoord voor uw account hebben ontvangen',
            'reset-password' => 'Wachtwoord opnieuw instellen',
            'final-summary' => 'Als u geen wachtwoordherstel hebt aangevraagd, is er geen verdere actie vereist',
            'thanks' => 'Bedankt!'
        ],

        'customer' => [
            'new' => [
                'dear' => 'Lieve :customer_name',
                'username-email' => 'UserName/Email',
                'subject' => 'Nieuwe klantenregistratie',
                'password' => 'Wachtwoord',
                'summary' => 'Uw account is aangemaakt.
                Uw accountgegevens zijn below: ',
                'thanks' => 'Thanks!',
            ],

            'registration' => [
                'subject' => 'Nieuwe klantenregistratie',
                'customer-registration' => 'Klant succesvol geregistreerd',
                'dear' => 'Lieve :customer_name',
                'greeting' => 'Welkom en bedankt voor uw registratie bij ons!',
                'summary' => 'Uw account is nu succesvol aangemaakt en u kunt inloggen met uw e-mailadres en wachtwoordgegevens. Na het inloggen hebt u toegang tot andere services, waaronder het bekijken van eerdere bestellingen, verlanglijstjes en het bewerken van uw accountgegevens.',
                'thanks' => 'Thanks!',
            ],

            'verification' => [
                'heading' => config('app.name') . ' - Email Verification',
                'subject' => 'Verificatiemail',
                'verify' => 'Verifieer uw account',
                'summary' => 'Dit is de e-mail om te verifiëren dat het door u ingevoerde e-mailadres van u is.
                Klik op de onderstaande knop Uw account verifiëren om uw account te verifiëren.'
            ],

            'subscription' => [
                'subject' => 'Abonnementse-mail',
                'greeting' => ' Welkom bij' . config('app.name') . ' - Email Subscription',
                'unsubscribe' => 'Afmelden',
                'summary' => 'Bedankt dat je me in je inbox hebt geplaatst. Het is een tijdje geleden dat je hebt gelezen' . config('app.name') . ' e-mail en we willen uw inbox niet overbelasten. Als je nog steeds niet wilt ontvangen het laatste nieuws over e-mailmarketing, klik dan op de onderstaande knop.'
            ]
        ]
    ],

    'webkul' => [
        'copy-right' => '© Copyright :year Webkul Software, All rights reserved',
    ],

    'response' => [
        'create-success' => ':name succesvol gemaakt.',
        'update-success' => ':name succesvol geupdatet.',
        'delete-success' => ':name met succes verwijderd.',
        'submit-success' => ':name succesvol ingediend.'
    ],
];
