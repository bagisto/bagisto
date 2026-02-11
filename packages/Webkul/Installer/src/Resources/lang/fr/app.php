<?php

return [
    'seeders' => [
        'attribute' => [
            'attribute-families' => [
                'default' => 'Défaut',
            ],

            'attribute-groups' => [
                'description' => 'Description',
                'general' => 'Général',
                'inventories' => 'Inventaires',
                'meta-description' => 'Méta-description',
                'price' => 'Prix',
                'rma' => 'RMA',
                'settings' => 'Paramètres',
                'shipping' => 'Expédition',
            ],

            'attributes' => [
                'allow-rma' => 'Autoriser le RMA',
                'brand' => 'Marque',
                'color' => 'Couleur',
                'cost' => 'Coût',
                'description' => 'Description',
                'featured' => 'En vedette',
                'guest-checkout' => 'Commande en tant qu\'invité',
                'height' => 'Hauteur',
                'length' => 'Longueur',
                'manage-stock' => 'Gestion des stocks',
                'meta-description' => 'Méta-description',
                'meta-keywords' => 'Mots-clés méta',
                'meta-title' => 'Méta-titre',
                'name' => 'Nom',
                'new' => 'Nouveau',
                'price' => 'Prix',
                'product-number' => 'Numéro de produit',
                'rma-rules' => 'Règles de RMA',
                'short-description' => 'Brève description',
                'size' => 'Taille',
                'sku' => 'SKU',
                'special-price' => 'Prix spécial',
                'special-price-from' => 'Prix spécial (à partir de)',
                'special-price-to' => 'Prix spécial (jusqu\'à)',
                'status' => 'Statut',
                'tax-category' => 'Catégorie de taxe',
                'url-key' => 'Clé d\'URL',
                'visible-individually' => 'Visible individuellement',
                'weight' => 'Poids',
                'width' => 'Largeur',
            ],

            'attribute-options' => [
                'black' => 'Noir',
                'green' => 'Vert',
                'l' => 'L',
                'm' => 'M',
                'red' => 'Rouge',
                's' => 'S',
                'white' => 'Blanc',
                'xl' => 'XL',
                'yellow' => 'Jaune',
            ],
        ],

        'category' => [
            'categories' => [
                'description' => 'Description de la catégorie racine',
                'name' => 'Racine',
            ],
        ],

        'cms' => [
            'pages' => [
                'about-us' => [
                    'content' => 'Contenu de la page À propos de nous',
                    'title' => 'À propos de nous',
                ],

                'contact-us' => [
                    'content' => 'Contenu de la page Contactez-nous',
                    'title' => 'Contactez-nous',
                ],

                'customer-service' => [
                    'content' => 'Contenu de la page Service client',
                    'title' => 'Service client',
                ],

                'payment-policy' => [
                    'content' => 'Contenu de la page Politique de paiement',
                    'title' => 'Politique de paiement',
                ],

                'privacy-policy' => [
                    'content' => 'Contenu de la page Politique de confidentialité',
                    'title' => 'Politique de confidentialité',
                ],

                'refund-policy' => [
                    'content' => 'Contenu de la page Politique de remboursement',
                    'title' => 'Politique de remboursement',
                ],

                'return-policy' => [
                    'content' => 'Contenu de la page Politique de retour',
                    'title' => 'Politique de retour',
                ],

                'shipping-policy' => [
                    'content' => 'Contenu de la page Politique d\'expédition',
                    'title' => 'Politique d\'expédition',
                ],

                'terms-conditions' => [
                    'content' => 'Contenu de la page Termes et conditions',
                    'title' => 'Termes et conditions',
                ],

                'terms-of-use' => [
                    'content' => 'Contenu de la page Conditions d\'utilisation',
                    'title' => 'Conditions d\'utilisation',
                ],

                'whats-new' => [
                    'content' => 'Contenu de la page Quoi de neuf',
                    'title' => 'Quoi de neuf',
                ],
            ],
        ],

        'core' => [
            'channels' => [
                'meta-description' => 'Méta-description de la boutique de démonstration',
                'meta-keywords' => 'Mots-clés méta de la boutique de démonstration',
                'meta-title' => 'Boutique de démonstration',
                'name' => 'Défaut',
            ],

            'currencies' => [
                'AED' => 'Dirham des Émirats arabes unis',
                'ARS' => 'Peso argentin',
                'AUD' => 'Dollar australien',
                'BDT' => 'Taka bangladais',
                'BHD' => 'Dinar bahreïni',
                'BRL' => 'Réel brésilien',
                'CAD' => 'Dollar canadien',
                'CHF' => 'Franc suisse',
                'CLP' => 'Peso chilien',
                'CNY' => 'Yuan chinois',
                'COP' => 'Peso colombien',
                'CZK' => 'Couronne tchèque',
                'DKK' => 'Couronne danoise',
                'DZD' => 'Dinar algérien',
                'EGP' => 'Livre égyptienne',
                'EUR' => 'Euro',
                'FJD' => 'Dollar fidjien',
                'GBP' => 'Livre sterling',
                'HKD' => 'Dollar de Hong Kong',
                'HUF' => 'Forint hongrois',
                'IDR' => 'Roupie indonésienne',
                'ILS' => 'Nouveau shekel israélien',
                'INR' => 'Roupie indienne',
                'JOD' => 'Dinar jordanien',
                'JPY' => 'Yen japonais',
                'KRW' => 'Won sud-coréen',
                'KWD' => 'Dinar koweïtien',
                'KZT' => 'Tenge kazakh',
                'LBP' => 'Livre libanaise',
                'LKR' => 'Roupie srilankaise',
                'LYD' => 'Dinar libyen',
                'MAD' => 'Dirham marocain',
                'MUR' => 'Roupie mauricienne',
                'MXN' => 'Peso mexicain',
                'MYR' => 'Ringgit malaisien',
                'NGN' => 'Naira nigérian',
                'NOK' => 'Couronne norvégienne',
                'NPR' => 'Roupie népalaise',
                'NZD' => 'Dollar néo-zélandais',
                'OMR' => 'Rial omanais',
                'PAB' => 'Balboa panaméen',
                'PEN' => 'Nuevo sol péruvien',
                'PHP' => 'Peso philippin',
                'PKR' => 'Roupie pakistanaise',
                'PLN' => 'Zloty polonais',
                'PYG' => 'Guarani paraguayen',
                'QAR' => 'Rial qatari',
                'RON' => 'Leu roumain',
                'RUB' => 'Rouble russe',
                'SAR' => 'Riyal saoudien',
                'SEK' => 'Couronne suédoise',
                'SGD' => 'Dollar de Singapour',
                'THB' => 'Baht thaïlandais',
                'TND' => 'Dinar tunisien',
                'TRY' => 'Livre turque',
                'TWD' => 'Nouveau dollar taïwanais',
                'UAH' => 'Hryvnia ukrainienne',
                'USD' => 'Dollar américain',
                'UZS' => 'Som ouzbek',
                'VEF' => 'Bolivar vénézuélien',
                'VND' => 'Dong vietnamien',
                'XAF' => 'Franc CFA BEAC',
                'XOF' => 'Franc CFA BCEAO',
                'ZAR' => 'Rand sud-africain',
                'ZMW' => 'Kwacha zambien',
            ],

            'locales' => [
                'ar' => 'Arabe',
                'bn' => 'Bengali',
                'ca' => 'Catalan',
                'de' => 'Allemand',
                'en' => 'Anglais',
                'es' => 'Espagnol',
                'fa' => 'Persan',
                'fr' => 'Français',
                'he' => 'Hébreu',
                'hi_IN' => 'Hindi',
                'id' => 'Indonésien',
                'it' => 'Italien',
                'ja' => 'Japonais',
                'nl' => 'Néerlandais',
                'pl' => 'Polonais',
                'pt_BR' => 'Portugais brésilien',
                'ru' => 'Russe',
                'sin' => 'Sinhala',
                'tr' => 'Turc',
                'uk' => 'Ukrainien',
                'zh_CN' => 'Chinois',
            ],
        ],

        'customer' => [
            'customer-groups' => [
                'general' => 'Général',
                'guest' => 'Invité',
                'wholesale' => 'En gros',
            ],
        ],

        'inventory' => [
            'inventory-sources' => [
                'name' => 'Par défaut',
            ],
        ],

        'shop' => [
            'theme-customizations' => [
                'all-products' => [
                    'name' => 'Tous les produits',

                    'options' => [
                        'title' => 'Tous les produits',
                    ],
                ],

                'bold-collections' => [
                    'content' => [
                        'btn-title' => 'Voir les collections',
                        'description' => 'Découvrez nos nouvelles collections audacieuses ! Élevez votre style avec des designs audacieux et des déclarations vibrantes. Explorez des motifs saisissants et des couleurs audacieuses qui redéfinissent votre garde-robe. Préparez-vous à embrasser l\'extraordinaire !',
                        'title' => 'Préparez-vous pour nos nouvelles collections audacieuses !',
                    ],

                    'name' => 'Collections audacieuses',
                ],

                'bold-collections-2' => [
                    'content' => [
                        'btn-title' => 'Voir les Collections',
                        'description' => 'Nos Collections Audacieuses sont là pour redéfinir votre garde-robe avec des designs intrépides et des couleurs vives et frappantes. Des motifs audacieux aux teintes puissantes, c\'est votre chance de vous libérer de l\'ordinaire et d\'entrer dans l\'extraordinaire.',
                        'title' => 'Libérez Votre Audace avec Notre Nouvelle Collection!',
                    ],

                    'name' => 'Collections Audacieuses',
                ],

                'booking-products' => [
                    'name' => 'Produits de Réservation',

                    'options' => [
                        'title' => 'Réserver des Billets',
                    ],
                ],

                'categories-collections' => [
                    'name' => 'Collections de catégories',
                ],

                'featured-collections' => [
                    'name' => 'Collections en vedette',

                    'options' => [
                        'title' => 'Produits en vedette',
                    ],
                ],

                'footer-links' => [
                    'name' => 'Liens du pied de page',

                    'options' => [
                        'about-us' => 'À propos de nous',
                        'contact-us' => 'Contactez-nous',
                        'customer-service' => 'Service client',
                        'payment-policy' => 'Politique de paiement',
                        'privacy-policy' => 'Politique de confidentialité',
                        'refund-policy' => 'Politique de remboursement',
                        'return-policy' => 'Politique de retour',
                        'shipping-policy' => 'Politique d\'expédition',
                        'terms-conditions' => 'Termes et conditions',
                        'terms-of-use' => 'Conditions d\'utilisation',
                        'whats-new' => 'Nouveautés',
                    ],
                ],

                'game-container' => [
                    'content' => [
                        'sub-title-1' => 'Nos collections',
                        'sub-title-2' => 'Nos collections',
                        'title' => 'Le jeu avec nos nouvelles additions !',
                    ],

                    'name' => 'Conteneur de jeu',
                ],

                'image-carousel' => [
                    'name' => 'Carrousel d\'images',

                    'sliders' => [
                        'title' => 'Préparez-vous pour la nouvelle collection',
                    ],
                ],

                'new-products' => [
                    'name' => 'Nouveaux produits',

                    'options' => [
                        'title' => 'Nouveaux produits',
                    ],
                ],

                'offer-information' => [
                    'content' => [
                        'title' => 'Jusqu\'à 40% de réduction sur votre 1ère commande, ACHETEZ MAINTENANT',
                    ],

                    'name' => 'Informations sur les offres',
                ],

                'services-content' => [
                    'description' => [
                        'emi-available-info' => 'EMI sans frais disponible sur toutes les principales cartes de crédit',
                        'free-shipping-info' => 'Profitez de la livraison gratuite sur toutes les commandes',
                        'product-replace-info' => 'Remplacement facile de produit disponible !',
                        'time-support-info' => 'Support dédié 24/7 via chat et e-mail',
                    ],

                    'name' => 'Contenu des services',

                    'title' => [
                        'emi-available' => 'EMI disponible',
                        'free-shipping' => 'Livraison gratuite',
                        'product-replace' => 'Remplacement de produit',
                        'time-support' => 'Support 24/7',
                    ],
                ],

                'top-collections' => [
                    'content' => [
                        'sub-title-1' => 'Nos collections',
                        'sub-title-2' => 'Nos collections',
                        'sub-title-3' => 'Nos collections',
                        'sub-title-4' => 'Nos collections',
                        'sub-title-5' => 'Nos collections',
                        'sub-title-6' => 'Nos collections',
                        'title' => 'Le jeu avec nos nouvelles additions !',
                    ],

                    'name' => 'Collections phares',
                ],
            ],
        ],

        'user' => [
            'roles' => [
                'description' => 'Les utilisateurs de ce rôle auront tous les accès',
                'name' => 'Administrateur',
            ],

            'users' => [
                'name' => 'Exemple',
            ],
        ],

        'sample-categories' => [
            'category-translation' => [
                '2' => [
                    'description' => '<p>Hommes</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Hommes',
                    'slug' => 'mens',
                    'url-path' => 'men',
                ],

                '3' => [
                    'description' => '<p>Enfants</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Enfants',
                    'slug' => 'kids',
                    'url-path' => 'kids',
                ],

                '4' => [
                    'description' => '<p>Femmes</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Femmes',
                    'slug' => 'womens',
                    'url-path' => 'woman',
                ],

                '5' => [
                    'description' => '<p>Tenue Formelle</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Tenue Formelle',
                    'slug' => 'formal-wear-men',
                    'url-path' => 'men/formal-wear-men',
                ],

                '6' => [
                    'description' => '<p>Tenue Décontractée</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Tenue Décontractée',
                    'slug' => 'casual-wear-men',
                    'url-path' => 'men/casual-wear-men',
                ],

                '7' => [
                    'description' => '<p>Vêtements de Sport</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Vêtements de Sport',
                    'slug' => 'active-wear',
                    'url-path' => 'men/active-wear',
                ],

                '8' => [
                    'description' => '<p>Chaussures</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Chaussures',
                    'slug' => 'footwear',
                    'url-path' => 'men/footwear',
                ],

                '9' => [
                    'description' => '<p><span>Tenue Formelle</span></p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Tenue Formelle',
                    'slug' => 'formal-wear-female',
                    'url-path' => 'woman/formal-wear-female',
                ],

                '10' => [
                    'description' => '<p>Tenue Décontractée</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Tenue Décontractée',
                    'slug' => 'casual-wear-female',
                    'url-path' => 'woman/casual-wear-female',
                ],

                '11' => [
                    'description' => '<p>Vêtements de Sport</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Vêtements de Sport',
                    'slug' => 'active-wear-female',
                    'url-path' => 'woman/active-wear-female',
                ],

                '12' => [
                    'description' => '<p>Chaussures</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Chaussures',
                    'slug' => 'footwear-female',
                    'url-path' => 'woman/footwear-female',
                ],

                '13' => [
                    'description' => '<p>Vêtements Filles</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => 'Vêtements Filles',
                    'name' => 'Vêtements Filles',
                    'slug' => 'girls-clothing',
                    'url-path' => 'kids/girls-clothing',
                ],

                '14' => [
                    'description' => '<p>Vêtements Garçons</p>',
                    'meta-description' => 'Mode Garçons',
                    'meta-keywords' => '',
                    'meta-title' => 'Vêtements Garçons',
                    'name' => 'Vêtements Garçons',
                    'slug' => 'boys-clothing',
                    'url-path' => 'kids/boys-clothing',
                ],

                '15' => [
                    'description' => '<p>Chaussures Filles</p>',
                    'meta-description' => 'Collection de Chaussures Mode Filles',
                    'meta-keywords' => '',
                    'meta-title' => 'Chaussures Filles',
                    'name' => 'Chaussures Filles',
                    'slug' => 'girls-footwear',
                    'url-path' => 'kids/girls-footwear',
                ],

                '16' => [
                    'description' => '<p>Chaussures Garçons</p>',
                    'meta-description' => 'Collection de Chaussures Élégantes Garçons',
                    'meta-keywords' => '',
                    'meta-title' => 'Chaussures Garçons',
                    'name' => 'Chaussures Garçons',
                    'slug' => 'boys-footwear',
                    'url-path' => 'kids/boys-footwear',
                ],

                '17' => [
                    'description' => '<p>Bien-être</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Bien-être',
                    'slug' => 'wellness',
                    'url-path' => 'wellness',
                ],

                '18' => [
                    'description' => '<p>Tutoriel Yoga Téléchargeable</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Tutoriel Yoga Téléchargeable',
                    'slug' => 'downloadable-yoga-tutorial',
                    'url-path' => 'wellness/downloadable-yoga-tutorial',
                ],

                '19' => [
                    'description' => '<p>Collection de Livres Numériques</p>',
                    'meta-description' => 'Collection de Livres Numériques',
                    'meta-keywords' => '',
                    'meta-title' => 'Collection de Livres Numériques',
                    'name' => 'Livres Numériques',
                    'slug' => 'e-books',
                    'url-path' => 'wellness/e-books',
                ],

                '20' => [
                    'description' => '<p>Pass Cinéma</p>',
                    'meta-description' => 'Plongez dans la magie de 10 films par mois sans frais supplémentaires.',
                    'meta-keywords' => '',
                    'meta-title' => 'Pass Cinéma Mensuel CineXperience',
                    'name' => 'Pass Cinéma',
                    'slug' => 'movie-pass',
                    'url-path' => 'wellness/movie-pass',
                ],

                '21' => [
                    'description' => '<p>Gérez et vendez facilement vos produits basés sur la réservation avec notre système de réservation intégré.</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Réservations',
                    'slug' => 'bookings',
                    'url-path' => '',
                ],

                '22' => [
                    'description' => '<p>La réservation de rendez-vous permet aux clients de planifier des créneaux horaires pour des services ou des consultations avec des entreprises ou des professionnels.</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Réservation de Rendez-vous',
                    'slug' => 'appointment-booking',
                    'url-path' => '',
                ],

                '23' => [
                    'description' => '<p>La réservation d\'événements permet aux individus ou groupes de s\'inscrire ou de réserver des places pour des événements publics ou privés.</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Réservation d\'Événement',
                    'slug' => 'event-booking',
                    'url-path' => '',
                ],

                '24' => [
                    'description' => '<p>La réservation de salles communautaires permet aux individus, organisations ou groupes de réserver des espaces communautaires pour divers événements.</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Réservations de Salles Communautaires',
                    'slug' => 'community-hall-bookings',
                    'url-path' => '',
                ],

                '25' => [
                    'description' => '<p>La réservation de table permet aux clients de réserver des tables dans les restaurants, cafés ou établissements de restauration à l\'avance.</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Réservation de Table',
                    'slug' => 'table-booking',
                    'url-path' => '',
                ],

                '26' => [
                    'description' => '<p>La réservation de location facilite la réservation d\'articles ou de propriétés pour un usage temporaire.</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Réservation de Location',
                    'slug' => 'rental-booking',
                    'url-path' => '',
                ],

                '27' => [
                    'description' => '<p>Explorez les dernières nouveautés en électronique grand public, conçues pour vous garder connecté, productif et diverti.</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Électronique',
                    'slug' => 'electronics',
                    'url-path' => '',
                ],

                '28' => [
                    'description' => '<p>Découvrez smartphones, chargeurs, coques et autres essentiels pour rester connecté en déplacement.</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Téléphones Mobiles et Accessoires',
                    'slug' => 'mobile-phones-accessories',
                    'url-path' => '',
                ],

                '29' => [
                    'description' => '<p>Trouvez des ordinateurs portables puissants et des tablettes pour le travail, les études et les loisirs.</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Ordinateurs Portables et Tablettes',
                    'slug' => 'laptops-tablets',
                    'url-path' => '',
                ],

                '30' => [
                    'description' => '<p>Achetez des casques, écouteurs et haut-parleurs pour profiter d\'un son cristallin.</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Appareils Audio',
                    'slug' => 'audio-devices',
                    'url-path' => '',
                ],

                '31' => [
                    'description' => '<p>Facilitez-vous la vie avec l\'éclairage intelligent, les thermostats, les systèmes de sécurité et plus encore.</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Maison Intelligente et Automatisation',
                    'slug' => 'smart-home-automation',
                    'url-path' => '',
                ],

                '32' => [
                    'description' => '<p>Améliorez votre espace de vie avec des essentiels fonctionnels et élégants pour la maison et la cuisine.</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Maison',
                    'slug' => 'household',
                    'url-path' => '',
                ],

                '33' => [
                    'description' => '<p>Parcourez blenders, friteuses à air, cafetières et plus pour simplifier la préparation des repas.</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Appareils de Cuisine',
                    'slug' => 'kitchen-appliances',
                    'url-path' => '',
                ],

                '34' => [
                    'description' => '<p>Explorez les ensembles d\'ustensiles de cuisine, ustensiles, vaisselle et articles de service pour vos besoins culinaires.</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Ustensiles de Cuisine et Arts de la Table',
                    'slug' => 'cookware-dining',
                    'url-path' => '',
                ],

                '35' => [
                    'description' => '<p>Ajoutez confort et charme avec canapés, tables, art mural et accents de décoration.</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Meubles et Décoration',
                    'slug' => 'furniture-decor',
                    'url-path' => '',
                ],

                '36' => [
                    'description' => '<p>Gardez votre espace impeccable avec aspirateurs, sprays nettoyants, balais et organisateurs.</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Produits d\'Entretien',
                    'slug' => 'cleaning-supplies',
                    'url-path' => '',
                ],

                '37' => [
                    'description' => '<p>Enflammez votre imagination ou organisez votre espace de travail avec une large sélection de livres et de papeterie.</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Livres et Papeterie',
                    'slug' => 'books-stationery',
                    'url-path' => '',
                ],

                '38' => [
                    'description' => '<p>Plongez dans les romans à succès, biographies, développement personnel et plus encore.</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Romans et Livres Documentaires',
                    'slug' => 'fiction-non-fiction-books',
                    'url-path' => '',
                ],

                '39' => [
                    'description' => '<p>Trouvez manuels scolaires, documents de référence et guides d\'étude pour tous les âges.</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Éducatif et Académique',
                    'slug' => 'educational-academic',
                    'url-path' => '',
                ],

                '40' => [
                    'description' => '<p>Achetez stylos, cahiers, agendas et essentiels de bureau pour la productivité.</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Fournitures de Bureau',
                    'slug' => 'office-supplies',
                    'url-path' => '',
                ],

                '41' => [
                    'description' => '<p>Explorez peintures, pinceaux, carnets de croquis et kits de loisirs créatifs DIY pour les créatifs.</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Matériel d\'Art et Loisirs Créatifs',
                    'slug' => 'art-craft-materials',
                    'url-path' => '',
                ],
            ],
        ],
    ],

    'installer' => [
        'middleware' => [
            'already-installed' => 'L\'application est déjà installée.',
        ],

        'index' => [
            'create-administrator' => [
                'admin' => 'Admin',
                'bagisto' => 'Bagisto',
                'confirm-password' => 'Confirmer le mot de passe',
                'email' => 'Email',
                'email-address' => 'admin@example.com',
                'password' => 'Mot de passe',
                'title' => 'Créer un administrateur',
            ],

            'environment-configuration' => [
                'algerian-dinar' => 'Dinar algérien (DZD)',
                'allowed-currencies' => 'Devises autorisées',
                'allowed-locales' => 'Langues autorisées',
                'application-name' => 'Nom de l\'application',
                'argentine-peso' => 'Peso argentin (ARS)',
                'australian-dollar' => 'Dollar australien (AUD)',
                'bagisto' => 'Bagisto',
                'bangladeshi-taka' => 'Taka bangladais (BDT)',
                'bahraini-dinar' => 'Dinar bahreïni (BHD)',
                'brazilian-real' => 'Réel brésilien (BRL)',
                'british-pound-sterling' => 'Livre sterling britannique (GBP)',
                'canadian-dollar' => 'Dollar canadien (CAD)',
                'cfa-franc-bceao' => 'Franc CFA BCEAO (XOF)',
                'cfa-franc-beac' => 'Franc CFA BEAC (XAF)',
                'chilean-peso' => 'Peso chilien (CLP)',
                'chinese-yuan' => 'Yuan chinois (CNY)',
                'colombian-peso' => 'Peso colombien (COP)',
                'czech-koruna' => 'Couronne tchèque (CZK)',
                'danish-krone' => 'Couronne danoise (DKK)',
                'database-connection' => 'Connexion à la base de données',
                'database-hostname' => 'Nom d\'hôte de la base de données',
                'database-name' => 'Nom de la base de données',
                'database-password' => 'Mot de passe de la base de données',
                'database-port' => 'Port de la base de données',
                'database-prefix' => 'Préfixe de la base de données',
                'database-prefix-help' => 'Le préfixe doit comporter 4 caractères et ne peut contenir que des lettres, des chiffres et des traits de soulignement.',
                'database-username' => 'Nom d\'utilisateur de la base de données',
                'default-currency' => 'Devise par défaut',
                'default-locale' => 'Langue par défaut',
                'default-timezone' => 'Fuseau horaire par défaut',
                'default-url' => 'URL par défaut',
                'default-url-link' => 'https://localhost',
                'egyptian-pound' => 'Livre égyptienne (EGP)',
                'euro' => 'Euro (EUR)',
                'fijian-dollar' => 'Dollar fidjien (FJD)',
                'hong-kong-dollar' => 'Dollar de Hong Kong (HKD)',
                'hungarian-forint' => 'Forint hongrois (HUF)',
                'indian-rupee' => 'Roupie indienne (INR)',
                'indonesian-rupiah' => 'Roupie indonésienne (IDR)',
                'israeli-new-shekel' => 'Nouveau shekel israélien (ILS)',
                'japanese-yen' => 'Yen japonais (JPY)',
                'jordanian-dinar' => 'Dinar jordanien (JOD)',
                'kazakhstani-tenge' => 'Tenge kazakh (KZT)',
                'kuwaiti-dinar' => 'Dinar koweïtien (KWD)',
                'lebanese-pound' => 'Livre libanaise (LBP)',
                'libyan-dinar' => 'Dinar libyen (LYD)',
                'malaysian-ringgit' => 'Ringgit malaisien (MYR)',
                'mauritian-rupee' => 'Roupie mauricienne (MUR)',
                'mexican-peso' => 'Peso mexicain (MXN)',
                'moroccan-dirham' => 'Dirham marocain (MAD)',
                'mysql' => 'Mysql',
                'nepalese-rupee' => 'Roupie népalaise (NPR)',
                'new-taiwan-dollar' => 'Dollar taïwanais (TWD)',
                'new-zealand-dollar' => 'Dollar néo-zélandais (NZD)',
                'nigerian-naira' => 'Naira nigérian (NGN)',
                'norwegian-krone' => 'Couronne norvégienne (NOK)',
                'omani-rial' => 'Rial omanais (OMR)',
                'pakistani-rupee' => 'Roupie pakistanaise (PKR)',
                'panamanian-balboa' => 'Balboa panaméen (PAB)',
                'paraguayan-guarani' => 'Guarani paraguayen (PYG)',
                'peruvian-nuevo-sol' => 'Nuevo sol péruvien (PEN)',
                'pgsql' => 'pgSQL',
                'philippine-peso' => 'Peso philippin (PHP)',
                'polish-zloty' => 'Zloty polonais (PLN)',
                'qatari-rial' => 'Rial qatari (QAR)',
                'romanian-leu' => 'Leu roumain (RON)',
                'russian-ruble' => 'Rouble russe (RUB)',
                'saudi-riyal' => 'Riyal saoudien (SAR)',
                'select-timezone' => 'Sélectionnez le fuseau horaire',
                'singapore-dollar' => 'Dollar de Singapour (SGD)',
                'south-african-rand' => 'Rand sud-africain (ZAR)',
                'south-korean-won' => 'Won sud-coréen (KRW)',
                'sqlsrv' => 'SQLSRV',
                'sri-lankan-rupee' => 'Roupie srilankaise (LKR)',
                'swedish-krona' => 'Couronne suédoise (SEK)',
                'swiss-franc' => 'Franc suisse (CHF)',
                'thai-baht' => 'Baht thaïlandais (THB)',
                'title' => 'Configuration du magasin',
                'tunisian-dinar' => 'Dinar tunisien (TND)',
                'turkish-lira' => 'Livre turque (TRY)',
                'ukrainian-hryvnia' => 'Hryvnia ukrainienne (UAH)',
                'united-arab-emirates-dirham' => 'Dirham des Émirats arabes unis (AED)',
                'united-states-dollar' => 'Dollar américain (USD)',
                'uzbekistani-som' => 'Som ouzbek (UZS)',
                'venezuelan-bolívar' => 'Bolívar vénézuélien (VEF)',
                'vietnamese-dong' => 'Dong vietnamien (VND)',
                'warning-message' => 'Attention! Les paramètres de langue système par défaut et de devise par défaut sont permanents et ne peuvent pas être modifiés une fois définis.',
                'zambian-kwacha' => 'Kwacha zambien (ZMW)',
            ],

            'sample-products' => [
                'no' => 'Non',
                'note' => 'Remarque : Le temps d\'indexation dépend du nombre de paramètres régionaux sélectionnés. Ce processus peut prendre jusqu\'à 2 minutes.',
                'sample-products' => 'Produits d\'échantillon',
                'title' => 'Produits d\'échantillon',
                'yes' => 'Oui',
            ],

            'installation-processing' => [
                'bagisto' => 'Installation de Bagisto',
                'bagisto-info' => 'Création des tables de la base de données, cela peut prendre quelques instants',
                'title' => 'Installation',
            ],

            'installation-completed' => [
                'admin-panel' => 'Panneau d\'administration',
                'bagisto-forums' => 'Forum Bagisto',
                'customer-panel' => 'Panneau du client',
                'explore-bagisto-extensions' => 'Explorer les extensions Bagisto',
                'title' => 'Installation terminée',
                'title-info' => 'Bagisto est installé avec succès sur votre système.',
            ],

            'ready-for-installation' => [
                'create-database-tables' => 'Créer les tables de la base de données',
                'drop-existing-tables' => 'Supprimer toutes les tables existantes',
                'install' => 'Installation',
                'install-info' => 'Bagisto pour l’installation',
                'install-info-button' => 'Cliquez sur le bouton ci-dessous pour',
                'populate-database-tables' => 'Remplir les tables de la base de données',
                'start-installation' => 'Démarrer l’installation',
                'title' => 'Prêt pour l’installation',
            ],

            'start' => [
                'locale' => 'Locale',
                'main' => 'Début',
                'select-locale' => 'Sélectionner la langue',
                'title' => 'Votre installation de Bagisto',
                'welcome-title' => 'Bienvenue sur Bagisto',
            ],

            'server-requirements' => [
                'calendar' => 'Calendrier',
                'ctype' => 'cType',
                'curl' => 'cURL',
                'dom' => 'DOM',
                'fileinfo' => 'Fileinfo',
                'filter' => 'Filtre',
                'gd' => 'GD',
                'hash' => 'Hachage',
                'intl' => 'Intl',
                'json' => 'JSON',
                'mbstring' => 'mbstring',
                'openssl' => 'OpenSSL',
                'pcre' => 'PCRE',
                'pdo' => 'PDO',
                'php' => 'PHP',
                'php-version' => ':version ou supérieure',
                'session' => 'Session',
                'title' => 'Configuration requise du serveur',
                'tokenizer' => 'Tokenizer',
                'xml' => 'XML',
            ],

            'arabic' => 'Arabe',
            'back' => 'Retour',
            'bagisto' => 'Bagisto',
            'bagisto-info' => 'Un projet communautaire par',
            'bagisto-logo' => 'Logo de Bagisto',
            'bengali' => 'Bengali',
            'catalan' => 'Catalan',
            'chinese' => 'Chinois',
            'continue' => 'Continuer',
            'dutch' => 'Néerlandais',
            'english' => 'Anglais',
            'french' => 'Français',
            'german' => 'Allemand',
            'hebrew' => 'Hébreu',
            'hindi' => 'Hindi',
            'indonesian' => 'Indonésien',
            'installation-description' => 'L\'installation de Bagisto implique généralement plusieurs étapes. Voici un aperçu général du processus d\'installation pour Bagisto',
            'installation-info' => 'Nous sommes heureux de vous voir ici !',
            'installation-title' => 'Bienvenue dans l\'installation',
            'italian' => 'Italien',
            'japanese' => 'Japonais',
            'persian' => 'Persan',
            'polish' => 'Polonais',
            'portuguese' => 'Portugais brésilien',
            'russian' => 'Russe',
            'sinhala' => 'Sinhala',
            'spanish' => 'Espagnol',
            'title' => 'Installeur de Bagisto',
            'turkish' => 'Turc',
            'ukrainian' => 'Ukrainien',
            'webkul' => 'Webkul',
        ],
    ],
];
