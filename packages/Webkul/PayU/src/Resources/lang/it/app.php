<?php

return [
    'description' => 'Paga in sicurezza utilizzando carta di credito, carta di debito, online banking, UPI e portafogli tramite PayU',
    'title'       => 'PayU',

    'redirect' => [
        'click-if-not-redirected' => 'Clicca qui per continuare',
        'please-wait'             => 'Attendere mentre ti reindirizziamo al gateway di pagamento...',
        'redirect-message'        => 'Se non vieni reindirizzato automaticamente, fai clic sul pulsante qui sotto.',
        'redirecting'             => 'Reindirizzamento a PayU...',
        'redirecting-to-payment'  => 'Reindirizzamento al pagamento PayU',
        'secure-payment'          => 'Gateway di pagamento sicuro',
    ],

    'response' => [
        'cart-not-found'            => 'Carrello non trovato. Per favore riprova.',
        'hash-mismatch'             => 'Verifica del pagamento fallita. Errore hash.',
        'invalid-transaction'       => 'Transazione non valida. Per favore riprova.',
        'order-creation-failed'     => 'Creazione dell\'ordine fallita. Si prega di contattare il supporto.',
        'payment-already-processed' => 'Il pagamento è già stato elaborato.',
        'payment-cancelled'         => 'Il pagamento è stato annullato. Puoi riprovare.',
        'payment-failed'            => 'Pagamento fallito. Per favore riprova.',
        'payment-success'           => 'Pagamento completato con successo!',
        'provide-credentials'       => 'Si prega di configurare la chiave commerciante e Salt di PayU nel pannello di amministrazione.',
    ],
];
