<?php

return [
    'description' => 'Lipa kwa usalama kwa kutumia kadi yako ya mkopo/ya benki kupitia Stripe.',
    'title' => 'Stripe',

    'line-items' => [
        'discount' => 'Punguzo',
        'shipping' => 'Usafirishaji',
        'tax' => 'Kodi',
    ],

    'response' => [
        'cart-changed' => 'Kikapu chako kilibadilika baada ya malipo kuanza, kwa hiyo hakuna oda iliyowekwa. Tafadhali wasiliana nasi kuhusu malipo yako.',
        'cart-not-found' => 'Kikapu hakikupatikana au si sahihi.',
        'cart-processed' => 'Kikapu hiki kimekwisha chakatwa.',
        'invalid-session' => 'Kipindi cha malipo si sahihi.',
        'payment-cancelled' => 'Malipo yameghairiwa.',
        'payment-failed' => 'Malipo yamefeli.',
        'payment-success' => 'Malipo yamekamilika kwa mafanikio.',
        'provide-credentials' => 'Tafadhali toa vitambulisho halali vya Stripe.',
        'session-invalid' => 'Kipindi cha malipo kimeisha muda wake au si sahihi.',
        'session-not-found' => 'Kipindi cha malipo hakikupatikana.',
        'verification-failed' => 'Uthibitisho wa malipo haukufaulu.',
    ],

    'webhook' => [
        'dispute-closed' => 'Mgogoro wa Stripe :id umefungwa kwa matokeo: :status.',
        'dispute-opened' => 'Mteja amepinga :amount ya malipo haya kwenye Stripe (:id), kwa sababu: :reason.',
        'refund-recorded' => 'Rejesho la :amount lililofanywa kwenye Stripe limerekodiwa; jumla ya :total imerejeshwa kwenye Stripe.',
    ],
];
