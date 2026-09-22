<?php

return [
    'description' => 'Betaal veilig met uw credit-/debetkaart via Stripe.',
    'title' => 'Stripe',

    'line-items' => [
        'shipping' => 'Verzending',
        'tax' => 'Belasting',
    ],

    'response' => [
        'cart-changed' => 'Uw winkelwagen is gewijzigd nadat de betaling was gestart, dus er is geen bestelling geplaatst. Neem contact met ons op over uw betaling.',
        'cart-not-found' => 'Winkelwagen niet gevonden of ongeldig.',
        'cart-processed' => 'Deze winkelwagen is al verwerkt.',
        'invalid-session' => 'Betalingssessie is ongeldig.',
        'payment-cancelled' => 'Betaling werd geannuleerd.',
        'payment-failed' => 'Betaling mislukt.',
        'payment-success' => 'Betaling succesvol voltooid.',
        'provide-credentials' => 'Gelieve geldige Stripe-inloggegevens op te geven.',
        'session-invalid' => 'Betalingssessie is verlopen of ongeldig.',
        'session-not-found' => 'Betalingssessie niet gevonden.',
        'verification-failed' => 'Betalingsverificatie mislukt.',
    ],

    'webhook' => [
        'dispute-closed' => 'Stripe-geschil :id is gesloten met de uitkomst: :status.',
        'dispute-opened' => 'De klant heeft :amount van deze betaling in Stripe betwist (:id), om de reden: :reason.',
        'refund-recorded' => 'Een terugbetaling van :amount in Stripe is vastgelegd; in totaal is :total in Stripe terugbetaald.',
    ],
];
