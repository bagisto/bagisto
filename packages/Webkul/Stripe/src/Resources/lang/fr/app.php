<?php

return [
    'description' => 'Payez en toute sécurité avec votre carte de crédit/débit via Stripe.',
    'title' => 'Stripe',

    'line-items' => [
        'shipping' => 'Livraison',
        'tax' => 'Taxes',
    ],

    'response' => [
        'cart-changed' => 'Votre panier a changé après le début du paiement, aucune commande n\'a donc été passée. Veuillez nous contacter au sujet de votre paiement.',
        'cart-not-found' => 'Panier introuvable ou invalide.',
        'cart-processed' => 'Ce panier a déjà été traité.',
        'invalid-session' => 'La session de paiement est invalide.',
        'payment-cancelled' => 'Le paiement a été annulé.',
        'payment-failed' => 'Échec du paiement.',
        'payment-success' => 'Paiement effectué avec succès.',
        'provide-credentials' => 'Veuillez fournir des identifiants Stripe valides.',
        'session-invalid' => 'La session de paiement a expiré ou est invalide.',
        'session-not-found' => 'Session de paiement introuvable.',
        'verification-failed' => 'La vérification du paiement a échoué.',
    ],

    'webhook' => [
        'dispute-closed' => 'Le litige Stripe :id a été clôturé avec le résultat : :status.',
        'dispute-opened' => 'Le client a contesté :amount de ce paiement dans Stripe (:id), pour le motif : :reason.',
        'refund-recorded' => 'Un remboursement de :amount effectué dans Stripe a été enregistré ; :total a été remboursé dans Stripe au total.',
    ],
];
