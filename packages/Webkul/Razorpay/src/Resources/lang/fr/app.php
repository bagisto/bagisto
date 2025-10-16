<?php

return [
    'configuration' => [
        'checkout-title'   => 'Paiement Razorpay',
        'client-id'        => "ID Client",
        'client-secret'    => "Secret Client",
        'description'      => 'Description',
        'info'             => 'Razorpay est une plateforme technologique financière qui aide les entreprises à accepter, traiter et distribuer les paiements.',
        'merchant_desc'    => 'Description de la transaction (affichée sur le formulaire de paiement)',
        'merchant_name'    => 'Nom du commerçant (affiché sur le formulaire de paiement)',
        'name'             => 'Razorpay',
        'production-only'  => 'Uniquement pour la production.', 
        'sandbox-only'     => 'Uniquement pour le bac à sable.', 
        'status'           => 'Statut',
        'test-mode-id'     => "ID Client en mode test",
        'test-mode-secret' => "Secret Client en mode test",
        'title'            => 'Titre',
    ],

    'response' => [
        'credentials-missing'  => 'Les identifiants Razorpay sont manquants !',
        'error-message'        => "Une erreur s'est produite lors du chargement de la passerelle de paiement. Veuillez réessayer.",
        'razorpay-cancelled'   => 'Le paiement Razorpay a été annulé.',
        'something-went-wrong' => 'Une erreur est survenue',
    ],
];