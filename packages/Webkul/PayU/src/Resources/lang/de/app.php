<?php

return [
    'description' => 'Bezahlen Sie sicher mit Kreditkarte, Debitkarte, Online-Banking, UPI und Wallets über PayU',
    'title'       => 'PayU Money',

    'redirect' => [
        'click-if-not-redirected' => 'Klicken Sie hier, um fortzufahren',
        'please-wait'             => 'Bitte warten Sie, während wir Sie zum Zahlungsgateway weiterleiten...',
        'redirect-message'        => 'Wenn Sie nicht automatisch weitergeleitet werden, klicken Sie auf die Schaltfläche unten.',
        'redirecting'             => 'Weiterleitung zu PayU...',
        'redirecting-to-payment'  => 'Weiterleitung zur PayU-Zahlung',
        'secure-payment'          => 'Sicheres Zahlungsgateway',
    ],

    'response' => [
        'cart-not-found'        => 'Warenkorb nicht gefunden. Bitte versuchen Sie es erneut.',
        'hash-mismatch'         => 'Zahlungsüberprüfung fehlgeschlagen. Hash-Fehler.',
        'invalid-transaction'   => 'Ungültige Transaktion. Bitte versuchen Sie es erneut.',
        'order-creation-failed' => 'Bestellung konnte nicht erstellt werden. Bitte kontaktieren Sie den Support.',
        'payment-cancelled'     => 'Zahlung wurde abgebrochen. Sie können es erneut versuchen.',
        'payment-failed'        => 'Zahlung fehlgeschlagen. Bitte versuchen Sie es erneut.',
        'payment-success'       => 'Zahlung erfolgreich abgeschlossen!',
        'provide-credentials'   => 'Bitte konfigurieren Sie den PayU-Händlerschlüssel und Salt im Admin-Panel.',
    ],
];
