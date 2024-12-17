<?php

return [
    'checkout' => [
        'cart' => [
            'integrity' => [
                'qty-missing'   => 'Au moins un produit doit avoir une quantité supérieure à 1.',
            ],

            'inventory-warning'        => 'La quantité demandée n\'est pas disponible, veuillez réessayer ultérieurement.',
            'missing-links'            => 'Les liens téléchargeables sont manquants pour ce produit.',
            'missing-options'          => 'Les options sont manquantes pour ce produit.',
            'selected-products-simple' => 'Les produits sélectionnés doivent être de type de produit simple.',
        ],
    ],

    'datagrid' => [
        'copy-of-slug'                  => 'copie-de-:value',
        'copy-of'                       => 'Copie de :value',
        'variant-already-exist-message' => 'Une variante avec les mêmes options d\'attributs existe déjà.',
    ],

    'response' => [
        'product-can-not-be-copied' => 'Les produits de type :type ne peuvent pas être copiés',
    ],

    'sort-by'  => [
        'options' => [
            'cheapest-first'  => 'Moins cher d\'abord',
            'expensive-first' => 'Plus cher d\'abord',
            'from-a-z'        => 'De A à Z',
            'from-z-a'        => 'De Z à A',
            'latest-first'    => 'Le plus récent d\'abord',
            'oldest-first'    => 'Le plus ancien d\'abord',
        ],
    ],

    'type'     => [
        'abstract'     => [
            'offers' => 'Achetez :qty pour :price chacun et économisez :discount',
        ],

        'bundle'       => 'Pack',
        'configurable' => 'Configurable',
        'downloadable' => 'Téléchargeable',
        'grouped'      => 'Regroupé',
        'simple'       => 'Simple',
        'virtual'      => 'Virtuel',
    ],
];
