<?php

return [
    'configuration' => [
        'checkout-title'   => 'Razorpay Checkout',
        'client-id'        => 'ID cliente',
        'client-secret'    => 'Segreto cliente',
        'description'      => 'Descrizione',
        'info'             => 'Razorpay è una piattaforma tecnologica finanziaria che aiuta le aziende ad accettare, elaborare e distribuire pagamenti.',
        'merchant_desc'    => 'Descrizione della transazione (da mostrare nel modulo di pagamento)',
        'merchant_name'    => 'Nome del commerciante (da mostrare nel modulo di pagamento)',
        'name'             => 'Razorpay',
        'production-only'  => 'Solo per la produzione.', 
        'sandbox-only'     => 'Solo per il sandbox.', 
        'status'           => 'Stato',
        'test-mode-id'     => 'ID cliente modalità test',
        'test-mode-secret' => 'Segreto cliente modalità test',
        'title'            => 'Titolo',
    ],

    'response' => [
        'credentials-missing'  => 'Le credenziali di Razorpay sono mancanti!',
        'error-message'        => 'Si è verificato un errore durante il caricamento del gateway di pagamento. Riprova.',
        'razorpay-cancelled'   => 'Il pagamento Razorpay è stato annullato.',
        'something-went-wrong' => 'Qualcosa è andato storto',
    ],
];