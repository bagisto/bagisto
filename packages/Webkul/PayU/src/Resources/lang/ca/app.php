<?php

return [
    'description' => 'Paga de forma segura utilitzant targeta de crèdit, targeta de dèbit, banca en línia, UPI i carteres a través de PayU',
    'title'       => 'PayU Money',

    'redirect' => [
        'click-if-not-redirected' => 'Fes clic aquí per continuar',
        'please-wait'             => 'Si us plau, espera mentre et redirigim a la passarel·la de pagament...',
        'redirect-message'        => 'Si no ets redirigit automàticament, fes clic al botó de sota.',
        'redirecting'             => 'Redirigint a PayU...',
        'redirecting-to-payment'  => 'Redirigint al pagament de PayU',
        'secure-payment'          => 'Passarel·la de pagament segura',
    ],

    'response' => [
        'cart-not-found'        => 'Cistell no trobat. Si us plau, torna-ho a provar.',
        'hash-mismatch'         => 'Verificació de pagament fallida. Discordança de hash.',
        'invalid-transaction'   => 'Transacció no vàlida. Si us plau, torna-ho a provar.',
        'order-creation-failed' => 'Error en crear la comanda. Si us plau, contacta amb el suport.',
        'payment-cancelled'     => 'El pagament ha estat cancel·lat. Pots tornar-ho a provar.',
        'payment-failed'        => 'El pagament ha fallat. Si us plau, torna-ho a provar.',
        'payment-success'       => 'Pagament completat amb èxit!',
        'provide-credentials'   => 'Si us plau, configura la clau de comerciant i Salt de PayU al panell d\'administració.',
    ],
];
