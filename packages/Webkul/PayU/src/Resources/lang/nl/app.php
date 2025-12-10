<?php

return [
    'description' => 'Betaal veilig met creditcard, debetkaart, online bankieren, UPI en portemonnees via PayU',
    'title'       => 'PayU',

    'redirect' => [
        'click-if-not-redirected' => 'Klik hier om door te gaan',
        'please-wait'             => 'Even geduld terwijl we u doorverwijzen naar de betaalgateway...',
        'redirect-message'        => 'Als u niet automatisch wordt doorverwezen, klik dan op de knop hieronder.',
        'redirecting'             => 'Doorverwijzen naar PayU...',
        'redirecting-to-payment'  => 'Doorverwijzen naar PayU-betaling',
        'secure-payment'          => 'Veilige betaalgateway',
    ],

    'response' => [
        'cart-not-found'            => 'Winkelwagen niet gevonden. Probeer het opnieuw.',
        'hash-mismatch'             => 'Betalingsverificatie mislukt. Hash komt niet overeen.',
        'invalid-transaction'       => 'Ongeldige transactie. Probeer het opnieuw.',
        'order-creation-failed'     => 'Bestelling aanmaken mislukt. Neem contact op met de ondersteuning.',
        'payment-already-processed' => 'Betaling is al verwerkt.',
        'payment-cancelled'         => 'Betaling is geannuleerd. U kunt het opnieuw proberen.',
        'payment-failed'            => 'Betaling mislukt. Probeer het opnieuw.',
        'payment-success'           => 'Betaling succesvol voltooid!',
        'provide-credentials'       => 'Configureer de PayU Merchant Key en Salt in het beheerpaneel.',
    ],
];
