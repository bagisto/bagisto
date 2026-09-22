<?php

return [
    'description' => 'Paga in sicurezza con la tua carta di credito/debito tramite Stripe.',
    'title' => 'Stripe',

    'line-items' => [
        'shipping' => 'Spedizione',
        'tax' => 'Imposte',
    ],

    'response' => [
        'cart-changed' => 'Il carrello è cambiato dopo l\'avvio del pagamento, quindi non è stato creato alcun ordine. Contattaci in merito al pagamento.',
        'cart-not-found' => 'Carrello non trovato o non valido.',
        'cart-processed' => 'Questo carrello è già stato elaborato.',
        'invalid-session' => 'La sessione di pagamento non è valida.',
        'payment-cancelled' => 'Il pagamento è stato annullato.',
        'payment-failed' => 'Pagamento fallito.',
        'payment-success' => 'Pagamento completato con successo.',
        'provide-credentials' => 'Si prega di fornire credenziali Stripe valide.',
        'session-invalid' => 'La sessione di pagamento è scaduta o non è valida.',
        'session-not-found' => 'Sessione di pagamento non trovata.',
        'verification-failed' => 'Verifica del pagamento fallita.',
    ],

    'webhook' => [
        'dispute-closed' => 'La contestazione Stripe :id è stata chiusa con esito: :status.',
        'dispute-opened' => 'Il cliente ha contestato :amount di questo pagamento su Stripe (:id), per il motivo: :reason.',
        'refund-recorded' => 'È stato registrato un rimborso di :amount effettuato su Stripe; in totale sono stati rimborsati :total su Stripe.',
    ],
];
