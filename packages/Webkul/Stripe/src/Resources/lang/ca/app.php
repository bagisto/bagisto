<?php

return [
    'description' => 'Pagueu de manera segura amb la vostra targeta de crèdit/dèbit a través de Stripe.',
    'title' => 'Stripe',

    'line-items' => [
        'shipping' => 'Enviament',
        'tax' => 'Impostos',
    ],

    'response' => [
        'cart-changed' => 'La cistella ha canviat després d\'iniciar el pagament, així que no s\'ha creat cap comanda. Poseu-vos en contacte amb nosaltres pel pagament.',
        'cart-not-found' => 'Carret   no trobat o invàlid.',
        'cart-processed' => 'Aquest carretó ja ha estat processat.',
        'invalid-session' => 'La sessió de pagament no és vàlida.',
        'payment-cancelled' => 'El pagament ha estat cancel·lat.',
        'payment-failed' => 'El pagament ha fallat.',
        'payment-success' => 'Pagament completat amb èxit.',
        'provide-credentials' => 'Si us plau, proporcioneu credencials vàlides de Stripe.',
        'session-invalid' => 'La sessió de pagament ha expirat o no és vàlida.',
        'session-not-found' => 'Sessió de pagament no trobada.',
        'verification-failed' => 'La verificació del pagament ha fallat.',
    ],

    'webhook' => [
        'dispute-closed' => 'La disputa de Stripe :id s\'ha tancat amb el resultat: :status.',
        'dispute-opened' => 'El client ha disputat :amount d\'aquest pagament a Stripe (:id), pel motiu: :reason.',
        'refund-recorded' => 'S\'ha registrat un reemborsament de :amount fet a Stripe; en total s\'han reemborsat :total a Stripe.',
    ],
];
