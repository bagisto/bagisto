<?php

return [
    'description' => 'Sicher bezahlen mit Ihrer Kredit-/Debitkarte über Stripe.',
    'title' => 'Stripe',

    'line-items' => [
        'shipping' => 'Versand',
        'tax' => 'Steuer',
    ],

    'response' => [
        'cart-changed' => 'Ihr Warenkorb hat sich nach Beginn der Zahlung geändert, daher wurde keine Bestellung aufgegeben. Bitte kontaktieren Sie uns wegen Ihrer Zahlung.',
        'cart-not-found' => 'Warenkorb nicht gefunden oder ungültig.',
        'cart-processed' => 'Dieser Warenkorb wurde bereits verarbeitet.',
        'invalid-session' => 'Zahlungssitzung ist ungültig.',
        'payment-cancelled' => 'Zahlung wurde abgebrochen.',
        'payment-failed' => 'Zahlung fehlgeschlagen.',
        'payment-success' => 'Zahlung erfolgreich abgeschlossen.',
        'provide-credentials' => 'Bitte geben Sie gültige Stripe-Anmeldedaten an.',
        'session-invalid' => 'Zahlungssitzung ist abgelaufen oder ungültig.',
        'session-not-found' => 'Zahlungssitzung nicht gefunden.',
        'verification-failed' => 'Zahlungsverifizierung fehlgeschlagen.',
    ],

    'webhook' => [
        'dispute-closed' => 'Stripe-Streitfall :id wurde mit dem Ergebnis abgeschlossen: :status.',
        'dispute-opened' => 'Der Kunde hat :amount dieser Zahlung in Stripe angefochten (:id), Grund: :reason.',
        'refund-recorded' => 'Eine in Stripe vorgenommene Erstattung von :amount wurde erfasst; insgesamt wurden in Stripe :total erstattet.',
    ],
];
