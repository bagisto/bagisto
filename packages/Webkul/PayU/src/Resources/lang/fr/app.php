<?php

return [
    'description' => 'Payez en toute sécurité par carte de crédit, carte de débit, services bancaires en ligne, UPI et portefeuilles via PayU',
    'title'       => 'PayU',

    'redirect' => [
        'click-if-not-redirected' => 'Cliquez ici pour continuer',
        'please-wait'             => 'Veuillez patienter pendant que nous vous redirigeons vers la passerelle de paiement...',
        'redirect-message'        => 'Si vous n\'êtes pas redirigé automatiquement, cliquez sur le bouton ci-dessous.',
        'redirecting'             => 'Redirection vers PayU...',
        'redirecting-to-payment'  => 'Redirection vers le paiement PayU',
        'secure-payment'          => 'Passerelle de paiement sécurisée',
    ],

    'response' => [
        'cart-not-found'            => 'Panier introuvable. Veuillez réessayer.',
        'hash-mismatch'             => 'Échec de la vérification du paiement. Incompatibilité de hash.',
        'invalid-transaction'       => 'Transaction invalide. Veuillez réessayer.',
        'order-creation-failed'     => 'Échec de la création de la commande. Veuillez contacter le support.',
        'payment-already-processed' => 'Le paiement a déjà été traité.',
        'payment-cancelled'         => 'Le paiement a été annulé. Vous pouvez réessayer.',
        'payment-failed'            => 'Le paiement a échoué. Veuillez réessayer.',
        'payment-success'           => 'Paiement effectué avec succès !',
        'provide-credentials'       => 'Veuillez configurer la clé marchande et le Salt PayU dans le panneau d\'administration.',
    ],
];
