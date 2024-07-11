<?php

return [
    'seeders' => [
        'attribute' => [
            'attribute-families' => [
                'default' => 'Défaut',
            ],

            'attribute-groups' => [
                'description'      => 'Description',
                'general'          => 'Général',
                'inventories'      => 'Inventaires',
                'meta-description' => 'Méta-description',
                'price'            => 'Prix',
                'settings'         => 'Paramètres',
                'shipping'         => 'Expédition',
            ],

            'attributes' => [
                'brand'                => 'Marque',
                'color'                => 'Couleur',
                'cost'                 => 'Coût',
                'description'          => 'Description',
                'featured'             => 'En vedette',
                'guest-checkout'       => 'Commande en tant qu\'invité',
                'height'               => 'Hauteur',
                'length'               => 'Longueur',
                'manage-stock'         => 'Gestion des stocks',
                'meta-description'     => 'Méta-description',
                'meta-keywords'        => 'Mots-clés méta',
                'meta-title'           => 'Méta-titre',
                'name'                 => 'Nom',
                'new'                  => 'Nouveau',
                'price'                => 'Prix',
                'product-number'       => 'Numéro de produit',
                'short-description'    => 'Brève description',
                'size'                 => 'Taille',
                'sku'                  => 'SKU',
                'special-price'        => 'Prix spécial',
                'special-price-from'   => 'Prix spécial (à partir de)',
                'special-price-to'     => 'Prix spécial (jusqu\'à)',
                'status'               => 'Statut',
                'tax-category'         => 'Catégorie de taxe',
                'url-key'              => 'Clé d\'URL',
                'visible-individually' => 'Visible individuellement',
                'weight'               => 'Poids',
                'width'                => 'Largeur',
            ],

            'attribute-options' => [
                'black'  => 'Noir',
                'green'  => 'Vert',
                'l'      => 'L',
                'm'      => 'M',
                'red'    => 'Rouge',
                's'      => 'S',
                'white'  => 'Blanc',
                'xl'     => 'XL',
                'yellow' => 'Jaune',
            ],
        ],

        'category' => [
            'categories' => [
                'description' => 'Description de la catégorie racine',
                'name'        => 'Racine',
            ],
        ],

        'cms' => [
            'pages' => [
                'about-us' => [
                    'content' => 'Contenu de la page À propos de nous',
                    'title'   => 'À propos de nous',
                ],

                'contact-us' => [
                    'content' => 'Contenu de la page Contactez-nous',
                    'title'   => 'Contactez-nous',
                ],

                'customer-service' => [
                    'content' => 'Contenu de la page Service client',
                    'title'   => 'Service client',
                ],

                'payment-policy' => [
                    'content' => 'Contenu de la page Politique de paiement',
                    'title'   => 'Politique de paiement',
                ],

                'privacy-policy' => [
                    'content' => 'Contenu de la page Politique de confidentialité',
                    'title'   => 'Politique de confidentialité',
                ],

                'refund-policy' => [
                    'content' => 'Contenu de la page Politique de remboursement',
                    'title'   => 'Politique de remboursement',
                ],

                'return-policy' => [
                    'content' => 'Contenu de la page Politique de retour',
                    'title'   => 'Politique de retour',
                ],

                'shipping-policy' => [
                    'content' => 'Contenu de la page Politique d\'expédition',
                    'title'   => 'Politique d\'expédition',
                ],

                'terms-conditions' => [
                    'content' => 'Contenu de la page Termes et conditions',
                    'title'   => 'Termes et conditions',
                ],

                'terms-of-use' => [
                    'content' => 'Contenu de la page Conditions d\'utilisation',
                    'title'   => 'Conditions d\'utilisation',
                ],

                'whats-new' => [
                    'content' => 'Contenu de la page Quoi de neuf',
                    'title'   => 'Quoi de neuf',
                ],
            ],
        ],

        'core' => [
            'channels' => [
                'name'             => 'Défaut',
                'meta-title'       => 'Boutique de démonstration',
                'meta-keywords'    => 'Mots-clés méta de la boutique de démonstration',
                'meta-description' => 'Méta-description de la boutique de démonstration',
            ],

            'currencies' => [
                'AED' => 'Dirham des Émirats arabes unis',
                'ARS' => 'Peso argentin',
                'AUD' => 'Dollar australien',
                'BDT' => 'Taka bangladais',
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

            'locales'    => [
                'ar'    => 'Arabe',
                'bn'    => 'Bengali',
                'de'    => 'Allemand',
                'en'    => 'Anglais',
                'es'    => 'Espagnol',
                'fa'    => 'Persan',
                'fr'    => 'Français',
                'he'    => 'Hébreu',
                'hi_IN' => 'Hindi',
                'it'    => 'Italien',
                'ja'    => 'Japonais',
                'nl'    => 'Néerlandais',
                'pl'    => 'Polonais',
                'pt_BR' => 'Portugais brésilien',
                'ru'    => 'Russe',
                'sin'   => 'Sinhala',
                'tr'    => 'Turc',
                'uk'    => 'Ukrainien',
                'zh_CN' => 'Chinois',
            ],
        ],

        'customer' => [
            'customer-groups' => [
                'general'   => 'Général',
                'guest'     => 'Invité',
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
                        'btn-title'   => 'Voir les collections',
                        'description' => 'Découvrez nos nouvelles collections audacieuses ! Élevez votre style avec des designs audacieux et des déclarations vibrantes. Explorez des motifs saisissants et des couleurs audacieuses qui redéfinissent votre garde-robe. Préparez-vous à embrasser l\'extraordinaire !',
                        'title'       => 'Préparez-vous pour nos nouvelles collections audacieuses !',
                    ],

                    'name' => 'Collections audacieuses',
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
                        'about-us'         => 'À propos de nous',
                        'contact-us'       => 'Contactez-nous',
                        'customer-service' => 'Service client',
                        'payment-policy'   => 'Politique de paiement',
                        'privacy-policy'   => 'Politique de confidentialité',
                        'refund-policy'    => 'Politique de remboursement',
                        'return-policy'    => 'Politique de retour',
                        'shipping-policy'  => 'Politique d\'expédition',
                        'terms-conditions' => 'Termes et conditions',
                        'terms-of-use'     => 'Conditions d\'utilisation',
                        'whats-new'        => 'Nouveautés',
                    ],
                ],

                'game-container' => [
                    'content' => [
                        'sub-title-1' => 'Nos collections',
                        'sub-title-2' => 'Nos collections',
                        'title'       => 'Le jeu avec nos nouvelles additions !',
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
                        'emi-available-info'   => 'EMI sans frais disponible sur toutes les principales cartes de crédit',
                        'free-shipping-info'   => 'Profitez de la livraison gratuite sur toutes les commandes',
                        'product-replace-info' => 'Remplacement facile de produit disponible !',
                        'time-support-info'    => 'Support dédié 24/7 via chat et e-mail',
                    ],

                    'name' => 'Contenu des services',

                    'title' => [
                        'emi-available'   => 'EMI disponible',
                        'free-shipping'   => 'Livraison gratuite',
                        'product-replace' => 'Remplacement de produit',
                        'time-support'    => 'Support 24/7',
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
                        'title'       => 'Le jeu avec nos nouvelles additions !',
                    ],

                    'name' => 'Collections phares',
                ],
            ],
        ],

        'user' => [
            'roles' => [
                'description' => 'Les utilisateurs de ce rôle auront tous les accès',
                'name'        => 'Administrateur',
            ],

            'users' => [
                'name' => 'Exemple',
            ],
        ],

        'sample-categories' => [
            'category-translation' => [
                '2' => [
                    'description'      => 'Description de la catégorie Hommes',
                    'meta-description' => 'Méta-description de la catégorie Hommes',
                    'meta-keywords'    => 'Mots-clés méta de la catégorie Hommes',
                    'meta-title'       => 'Titre méta de la catégorie Hommes',
                    'name'             => 'Hommes',
                    'slug'             => 'hommes',
                ],

                '3' => [
                    'description'      => 'Description de la catégorie Vêtements d\'hiver',
                    'meta-description' => 'Méta-description de la catégorie Vêtements d\'hiver',
                    'meta-keywords'    => 'Mots-clés méta de la catégorie Vêtements d\'hiver',
                    'meta-title'       => 'Titre méta de la catégorie Vêtements d\'hiver',
                    'name'             => 'Vêtements d\'hiver',
                    'slug'             => 'vetements-hiver',
                ],
            ],
        ],

        'sample-products' => [
            'product-flat' => [
                '1' => [
                    'description'       => 'Le bonnet tricoté Arctic Cozy est votre solution pour rester au chaud, confortable et élégant pendant les mois les plus froids. Fabriqué à partir d\'un mélange doux et durable de tricot acrylique, ce bonnet est conçu pour offrir un ajustement confortable et snug. Son design classique le rend adapté aux hommes et aux femmes, offrant un accessoire polyvalent qui complète différents styles. Que vous sortiez pour une journée décontractée en ville ou que vous vous aventuriez en plein air, ce bonnet ajoute une touche de confort et de chaleur à votre tenue. Le matériau doux et respirant garantit que vous restez confortable sans sacrifier le style. Le bonnet tricoté Arctic Cozy n\'est pas seulement un accessoire; c\'est une déclaration de mode hivernale. Sa simplicité le rend facile à associer à différentes tenues, ce qui en fait un incontournable de votre garde-robe d\'hiver. Idéal pour offrir en cadeau ou pour vous faire plaisir, ce bonnet est un ajout réfléchi à toute tenue d\'hiver. C\'est un accessoire polyvalent qui va au-delà de la fonctionnalité, ajoutant une touche de chaleur et de style à votre look. Embrassez l\'essence de l\'hiver avec le bonnet tricoté Arctic Cozy. Que vous profitiez d\'une journée décontractée ou que vous affrontiez les éléments, laissez ce bonnet être votre compagnon de confort et de style. Rehaussez votre garde-robe d\'hiver avec cet accessoire classique qui combine parfaitement chaleur et intemporalité.',
                    'meta-description'  => 'meta description',
                    'meta-keywords'     => 'meta1, meta2, meta3',
                    'meta-title'        => 'Meta Title',
                    'name'              => 'Bonnet tricoté Arctic Cozy unisexe',
                    'short-description' => 'Embrassez les journées fraîches avec style grâce à notre bonnet tricoté Arctic Cozy. Fabriqué à partir d\'un mélange doux et durable d\'acrylique, ce bonnet classique offre chaleur et polyvalence. Convient aussi bien aux hommes qu\'aux femmes, c\'est l\'accessoire idéal pour une tenue décontractée ou en plein air. Rehaussez votre garde-robe d\'hiver ou offrez-le en cadeau à quelqu\'un de spécial avec ce bonnet essentiel.',
                ],

                '2' => [
                    'description'       => 'Le foulard d\'hiver Arctic Bliss est bien plus qu\'un accessoire de saison froide ; c\'est une déclaration de chaleur, de confort et de style pour l\'hiver. Fabriqué avec soin à partir d\'un mélange luxueux d\'acrylique et de laine, ce foulard est conçu pour vous garder confortable et douillet même dans les températures les plus froides. La texture douce et moelleuse offre non seulement une isolation contre le froid, mais ajoute également une touche de luxe à votre garde-robe d\'hiver. Le design du foulard d\hiver Arctic Bliss est à la fois élégant et polyvalent, ce qui en fait un ajout parfait à une variété de tenues d\'hiver. Que vous vous habilliez pour une occasion spéciale ou ajoutiez une couche chic à votre look quotidien, ce foulard complète votre style sans effort. La longueur extra-longue du foulard offre des options de style personnalisables. Enroulez-le pour plus de chaleur, laissez-le tomber librement pour un look décontracté, ou expérimentez avec différents nœuds pour exprimer votre style unique. Cette polyvalence en fait un accessoire indispensable pour la saison hivernale. Vous cherchez le cadeau parfait ? Le foulard d\'hiver Arctic Bliss est un choix idéal. Que vous surpreniez un être cher ou vous fassiez plaisir, ce foulard est un cadeau intemporel et pratique qui sera chéri tout au long des mois d\'hiver. Embrassez l\'hiver avec le foulard d\'hiver Arctic Bliss, où la chaleur rencontre le style en parfaite harmonie. Rehaussez votre garde-robe d\'hiver avec cet accessoire essentiel qui non seulement vous garde au chaud mais ajoute également une touche de sophistication à votre ensemble pour temps froid.',
                    'meta-description'  => 'méta description',
                    'meta-keywords'     => 'méta1, méta2, méta3',
                    'meta-title'        => 'Titre Méta',
                    'name'              => 'Foulard d\'hiver élégant Arctic Bliss',
                    'short-description' => 'Découvrez l\'étreinte de chaleur et de style avec notre foulard d\'hiver Arctic Bliss. Fabriqué à partir d\'un mélange luxueux d\'acrylique et de laine, ce foulard confortable est conçu pour vous garder bien au chaud pendant les jours les plus froids. Son design élégant et polyvalent, associé à une longueur extra-longue, offre des options de style personnalisables. Rehaussez votre garde-robe d\hiver ou faites plaisir à quelqu\'un de spécial avec cet accessoire d\'hiver essentiel.',
                ],

                '3' => [
                    'description'       => 'Présentation des gants d\'hiver Arctic Touchscreen - où la chaleur, le style et la connectivité se rencontrent pour améliorer votre expérience hivernale. Fabriqués à partir d\'acrylique de haute qualité, ces gants sont conçus pour offrir une chaleur et une durabilité exceptionnelles. Les bouts des doigts compatibles avec les écrans tactiles vous permettent de rester connecté sans exposer vos mains au froid. Répondez aux appels, envoyez des messages et naviguez sur vos appareils sans effort, tout en gardant vos mains bien au chaud. La doublure isolante ajoute une couche supplémentaire de confort, faisant de ces gants votre choix privilégié pour affronter le froid hivernal. Que vous soyez en déplacement, en train de faire des courses ou en train de profiter d\'activités de plein air, ces gants offrent la chaleur et la protection dont vous avez besoin. Les poignets élastiques assurent un ajustement sécurisé, empêchant les courants d\'air froid et maintenant les gants en place pendant vos activités quotidiennes. Le design élégant ajoute une touche de flair à votre ensemble d\'hiver, faisant de ces gants aussi mode que fonctionnels. Idéal pour offrir en cadeau ou comme petit plaisir pour vous-même, les gants d\'hiver Arctic Touchscreen sont un accessoire indispensable pour l\'individu moderne. Dites adieu à la gêne de devoir enlever vos gants pour utiliser vos appareils et adoptez le mélange harmonieux de chaleur, de style et de connectivité. Restez connecté, restez au chaud et restez stylé avec les gants d\'hiver Arctic Touchscreen - votre compagnon fiable pour conquérir la saison hivernale en toute confiance.',
                    'meta-description'  => 'méta description',
                    'meta-keywords'     => 'méta1, méta2, méta3',
                    'meta-title'        => 'Titre Méta',
                    'name'              => 'Gants d\'hiver Arctic Touchscreen',
                    'short-description' => 'Restez connecté et au chaud avec nos gants d\'hiver Arctic Touchscreen. Ces gants sont non seulement fabriqués à partir d\'acrylique de haute qualité pour la chaleur et la durabilité, mais présentent également un design compatible avec les écrans tactiles. Avec une doublure isolante, des poignets élastiques pour un ajustement sécurisé et un look stylé, ces gants sont parfaits pour une utilisation quotidienne par temps frais.',
                ],

                '4' => [
                    'description'       => 'Présentation des chaussettes en mélange de laine Arctic Warmth - votre compagnon essentiel pour des pieds confortables et confortables pendant les saisons plus froides. Fabriquées à partir d\'un mélange premium de laine mérinos, d\'acrylique, de nylon et d\'élasthanne, ces chaussettes sont conçues pour offrir une chaleur et un confort inégalés. Le mélange de laine garantit que vos pieds restent bien au chaud même par les températures les plus froides, faisant de ces chaussettes le choix parfait pour les aventures hivernales ou simplement pour rester bien au chaud à la maison. La texture douce et confortable des chaussettes offre une sensation luxueuse contre votre peau. Dites adieu aux pieds froids tout en embrassant la chaleur moelleuse offerte par ces chaussettes en mélange de laine. Conçues pour la durabilité, les chaussettes sont dotées d\'un talon et d\'un bout renforcés, ajoutant une force supplémentaire aux zones soumises à une usure élevée. Cela garantit que vos chaussettes résistent à l\'épreuve du temps, offrant un confort et une chaleur durables. La nature respirante du matériau empêche la surchauffe, permettant à vos pieds de rester confortables et secs tout au long de la journée. Que vous partiez en randonnée en hiver ou que vous vous détendiez à l\'intérieur, ces chaussettes offrent le parfait équilibre entre chaleur et respirabilité. Polyvalentes et élégantes, ces chaussettes en mélange de laine conviennent à différentes occasions. Associez-les à vos bottes préférées pour un look hivernal à la mode ou portez-les à la maison pour un confort ultime. Rehaussez votre garde-robe d\'hiver et privilégiez le confort avec les chaussettes en mélange de laine Arctic Warmth. Offrez à vos pieds le luxe qu\'ils méritent et plongez dans un monde de confort qui dure toute la saison.',
                    'meta-description'  => 'méta description',
                    'meta-keywords'     => 'méta1, méta2, méta3',
                    'meta-title'        => 'Titre Méta',
                    'name'              => 'Chaussettes en mélange de laine Arctic Warmth',
                    'short-description' => 'Découvrez la chaleur et le confort inégalés de nos chaussettes en mélange de laine Arctic Warmth. Fabriquées à partir d\'un mélange de laine mérinos, d\'acrylique, de nylon et d\'élasthanne, ces chaussettes offrent un confort ultime par temps froid. Avec un talon et un bout renforcés pour la durabilité, ces chaussettes polyvalentes et élégantes sont parfaites pour différentes occasions.',
                ],

                '5' => [
                    'description'       => 'Présentation du pack d\'accessoires d\'hiver Arctic Frost, votre solution incontournable pour rester au chaud, élégant et connecté pendant les journées fraîches de l\'hiver. Cet ensemble soigneusement sélectionné réunit quatre accessoires d\'hiver essentiels pour créer un ensemble harmonieux. Le foulard luxueux, tissé à partir d\'un mélange d\'acrylique et de laine, ajoute non seulement une couche de chaleur mais apporte également une touche d\'élégance à votre garde-robe d\'hiver. Le bonnet en tricot doux, fabriqué avec soin, promet de vous garder bien au chaud tout en ajoutant une touche de mode à votre look. Mais cela ne s\'arrête pas là - notre ensemble comprend également des gants compatibles avec les écrans tactiles. Restez connecté sans sacrifier la chaleur pendant que vous naviguez sur vos appareils sans effort. Que vous répondiez à des appels, envoyiez des messages ou capturiez des moments d\'hiver sur votre smartphone, ces gants assurent la commodité sans compromettre le style. La texture douce et confortable des chaussettes offre une sensation luxueuse contre votre peau. Dites adieu aux pieds froids tout en embrassant la chaleur moelleuse offerte par ces chaussettes en mélange de laine. Le pack d\'accessoires d\'hiver Arctic Frost ne concerne pas seulement la fonctionnalité ; c\'est une déclaration de mode hivernale. Chaque pièce est conçue non seulement pour vous protéger du froid mais aussi pour rehausser votre style pendant la saison glaciale. Les matériaux choisis pour cet ensemble privilégient à la fois la durabilité et le confort, garantissant que vous puissiez profiter du paysage hivernal avec style. Que vous vous fassiez plaisir ou recherchiez le cadeau parfait, le pack d\'accessoires d\'hiver Arctic Frost est un choix polyvalent. Faites plaisir à quelqu\'un de spécial pendant la saison des fêtes ou rehaussez votre propre garde-robe d\'hiver avec cet ensemble élégant et fonctionnel. Embrassez le gel avec confiance, sachant que vous avez les accessoires parfaits pour vous garder au chaud et chic.',
                    'meta-description'  => 'méta description',
                    'meta-keywords'     => 'méta1, méta2, méta3',
                    'meta-title'        => 'Titre Méta',
                    'name'              => 'Accessoires d\'hiver Arctic Frost',
                    'short-description' => 'Affrontez le froid hivernal avec notre pack d\'accessoires d\'hiver Arctic Frost. Cet ensemble sélectionné comprend un foulard luxueux, un bonnet confortable, des gants compatibles avec les écrans tactiles et des chaussettes en mélange de laine. Élégant et fonctionnel, cet ensemble est fabriqué à partir de matériaux de haute qualité, garantissant à la fois durabilité et confort. Rehaussez votre garde-robe d\'hiver ou faites plaisir à quelqu\'un de spécial avec cette option de cadeau parfaite.',
                ],

                '6' => [
                    'description'       => 'Présentation du pack d\'accessoires d\'hiver Arctic Frost, votre solution incontournable pour rester au chaud, élégant et connecté pendant les journées fraîches de l\'hiver. Cet ensemble soigneusement sélectionné réunit quatre accessoires d\'hiver essentiels pour créer un ensemble harmonieux. Le foulard luxueux, tissé à partir d\'un mélange d\'acrylique et de laine, ajoute non seulement une couche de chaleur mais apporte également une touche d\'élégance à votre garde-robe d\'hiver. Le bonnet en tricot doux, fabriqué avec soin, promet de vous garder bien au chaud tout en ajoutant une touche de mode à votre look. Mais cela ne s\'arrête pas là - notre ensemble comprend également des gants compatibles avec les écrans tactiles. Restez connecté sans sacrifier la chaleur pendant que vous naviguez sur vos appareils sans effort. Que vous répondiez à des appels, envoyiez des messages ou capturiez des moments d\'hiver sur votre smartphone, ces gants assurent la commodité sans compromettre le style. La texture douce et confortable des chaussettes offre une sensation luxueuse contre votre peau. Dites adieu aux pieds froids tout en embrassant la chaleur moelleuse offerte par ces chaussettes en mélange de laine. Le pack d\'accessoires d\'hiver Arctic Frost ne concerne pas seulement la fonctionnalité ; c\'est une déclaration de mode hivernale. Chaque pièce est conçue non seulement pour vous protéger du froid mais aussi pour rehausser votre style pendant la saison glaciale. Les matériaux choisis pour cet ensemble privilégient à la fois la durabilité et le confort, garantissant que vous puissiez profiter du paysage hivernal avec style. Que vous vous fassiez plaisir ou recherchiez le cadeau parfait, le pack d\'accessoires d\'hiver Arctic Frost est un choix polyvalent. Faites plaisir à quelqu\'un de spécial pendant la saison des fêtes ou rehaussez votre propre garde-robe d\'hiver avec cet ensemble élégant et fonctionnel. Embrassez le gel avec confiance, sachant que vous avez les accessoires parfaits pour vous garder au chaud et chic.',
                    'meta-description'  => 'méta description',
                    'meta-keywords'     => 'méta1, méta2, méta3',
                    'meta-title'        => 'Titre Méta',
                    'name'              => 'Pack d\'accessoires d\'hiver Arctic Frost',
                    'short-description' => 'Affrontez le froid hivernal avec notre pack d\'accessoires d\'hiver Arctic Frost. Cet ensemble sélectionné comprend un foulard luxueux, un bonnet confortable, des gants compatibles avec les écrans tactiles et des chaussettes en mélange de laine. Élégant et fonctionnel, cet ensemble est fabriqué à partir de matériaux de haute qualité, garantissant à la fois durabilité et confort. Rehaussez votre garde-robe d\'hiver ou faites plaisir à quelqu\'un de spécial avec cette option de cadeau parfaite.',
                ],

                '7' => [
                    'description'       => 'Présentation de la veste doudoune à capuche OmniHeat pour hommes, votre solution incontournable pour rester au chaud et à la mode pendant les saisons plus froides. Cette veste est conçue avec durabilité et chaleur à l\'esprit, garantissant qu\'elle devienne votre compagnon de confiance. La conception à capuche n\'ajoute pas seulement une touche de style mais offre également une chaleur supplémentaire, vous protégeant des vents froids et du mauvais temps. Les manches longues offrent une couverture complète, vous assurant de rester confortable de l\'épaule au poignet. Équipée de poches intérieures, cette veste doudoune offre une commodité pour transporter vos essentiels ou garder vos mains au chaud. Le rembourrage synthétique isolé offre une chaleur renforcée, en faisant l\'idéal pour affronter les journées et les nuits froides. Fabriquée à partir d\'une coque et d\'une doublure en polyester durable, cette veste est conçue pour durer et résister aux éléments. Disponible dans 5 couleurs attrayantes, vous pouvez choisir celle qui convient le mieux à votre style et à vos préférences. Polyvalente et fonctionnelle, la veste doudoune à capuche OmniHeat pour hommes est adaptée à différentes occasions, que vous vous rendiez au travail, que vous partiez pour une sortie décontractée ou que vous assistiez à un événement en plein air. Découvrez le parfait mélange de style, de confort et de fonctionnalité avec la veste doudoune à capuche OmniHeat pour hommes. Rehaussez votre garde-robe d\'hiver et restez bien au chaud tout en profitant de l\'extérieur. Affrontez le froid avec style et faites une déclaration avec cette pièce essentielle.',
                    'meta-description'  => 'méta description',
                    'meta-keywords'     => 'méta1, méta2, méta3',
                    'meta-title'        => 'Titre Méta',
                    'name'              => 'Veste doudoune à capuche OmniHeat pour hommes',
                    'short-description' => 'Restez au chaud et à la mode avec notre veste doudoune à capuche OmniHeat pour hommes. Cette veste est conçue pour offrir une chaleur ultime et est dotée de poches intérieures pour plus de commodité. Le matériau isolant assure que vous restiez confortable par temps froid. Disponible dans 5 couleurs attrayantes, en faisant un choix polyvalent pour différentes occasions.',
                ],

                '8' => [
                    'description'       => 'Présentation de la veste doudoune à capuche OmniHeat pour hommes, votre solution incontournable pour rester au chaud et à la mode pendant les saisons plus froides. Cette veste est conçue avec durabilité et chaleur à l\'esprit, garantissant qu\'elle devienne votre compagnon de confiance. La conception à capuche n\'ajoute pas seulement une touche de style mais offre également une chaleur supplémentaire, vous protégeant des vents froids et du mauvais temps. Les manches longues offrent une couverture complète, vous assurant de rester confortable de l\'épaule au poignet. Équipée de poches intérieures, cette veste doudoune offre une commodité pour transporter vos essentiels ou garder vos mains au chaud. Le rembourrage synthétique isolé offre une chaleur renforcée, en faisant l\'idéal pour affronter les journées et les nuits froides. Fabriquée à partir d\'une coque et d\'une doublure en polyester durable, cette veste est conçue pour durer et résister aux éléments. Disponible dans 5 couleurs attrayantes, vous pouvez choisir celle qui convient le mieux à votre style et à vos préférences. Polyvalente et fonctionnelle, la veste doudoune à capuche OmniHeat pour hommes est adaptée à différentes occasions, que vous vous rendiez au travail, que vous partiez pour une sortie décontractée ou que vous assistiez à un événement en plein air. Découvrez le parfait mélange de style, de confort et de fonctionnalité avec la veste doudoune à capuche OmniHeat pour hommes. Rehaussez votre garde-robe d\'hiver et restez bien au chaud tout en profitant de l\'extérieur. Affrontez le froid avec style et faites une déclaration avec cette pièce essentielle.',
                    'meta-description'  => 'méta description',
                    'meta-keywords'     => 'méta1, méta2, méta3',
                    'meta-title'        => 'Meta Title',
                    'name'              => 'Veste doudoune à capuche OmniHeat pour hommes - Bleu-Jaune - M',
                    'short-description' => 'Restez au chaud et à la mode avec notre veste doudoune à capuche OmniHeat pour hommes. Cette veste est conçue pour offrir une chaleur ultime et est dotée de poches intérieures pour plus de commodité. Le matériau isolant assure que vous restiez confortable par temps froid. Disponible dans 5 couleurs attrayantes, en faisant un choix polyvalent pour différentes occasions.',
                ],

                '9' => [
                    'description'       => 'Présentation de la veste doudoune à capuche OmniHeat pour hommes, votre solution incontournable pour rester au chaud et à la mode pendant les saisons plus froides. Cette veste est conçue avec durabilité et chaleur à l\'esprit, garantissant qu\'elle devienne votre compagnon de confiance. La conception à capuche n\'ajoute pas seulement une touche de style mais offre également une chaleur supplémentaire, vous protégeant des vents froids et du mauvais temps. Les manches longues offrent une couverture complète, vous assurant de rester confortable de l\'épaule au poignet. Équipée de poches intérieures, cette veste doudoune offre une commodité pour transporter vos essentiels ou garder vos mains au chaud. Le rembourrage synthétique isolé offre une chaleur renforcée, en faisant l\'idéal pour affronter les journées et les nuits froides. Fabriquée à partir d\'une coque et d\'une doublure en polyester durable, cette veste est conçue pour durer et résister aux éléments. Disponible dans 5 couleurs attrayantes, vous pouvez choisir celle qui convient le mieux à votre style et à vos préférences. Polyvalente et fonctionnelle, la veste doudoune à capuche OmniHeat pour hommes est adaptée à différentes occasions, que vous vous rendiez au travail, que vous partiez pour une sortie décontractée ou que vous assistiez à un événement en plein air. Découvrez le parfait mélange de style, de confort et de fonctionnalité avec la veste doudoune à capuche OmniHeat pour hommes. Rehaussez votre garde-robe d\'hiver et restez bien au chaud tout en profitant de l\'extérieur. Affrontez le froid avec style et faites une déclaration avec cette pièce essentielle.',
                    'meta-description'  => 'méta description',
                    'meta-keywords'     => 'méta1, méta2, méta3',
                    'meta-title'        => 'Meta Title',
                    'name'              => 'Veste doudoune à capuche OmniHeat pour hommes - Bleu-Jaune - L',
                    'short-description' => 'Restez au chaud et à la mode avec notre veste doudoune à capuche OmniHeat pour hommes. Cette veste est conçue pour offrir une chaleur ultime et est dotée de poches intérieures pour plus de commodité. Le matériau isolant assure que vous restiez confortable par temps froid. Disponible dans 5 couleurs attrayantes, en faisant un choix polyvalent pour différentes occasions.',
                ],

                '10' => [
                    'description'       => 'Présentation de la veste doudoune à capuche OmniHeat pour hommes, votre solution incontournable pour rester au chaud et à la mode pendant les saisons plus froides. Cette veste est conçue avec durabilité et chaleur à l\'esprit, garantissant qu\'elle devienne votre compagnon de confiance. La conception à capuche n\'ajoute pas seulement une touche de style mais offre également une chaleur supplémentaire, vous protégeant des vents froids et du mauvais temps. Les manches longues offrent une couverture complète, vous assurant de rester confortable de l\'épaule au poignet. Équipée de poches intérieures, cette veste doudoune offre une commodité pour transporter vos essentiels ou garder vos mains au chaud. Le rembourrage synthétique isolé offre une chaleur renforcée, en faisant l\'idéal pour affronter les journées et les nuits froides. Fabriquée à partir d\'une coque et d\'une doublure en polyester durable, cette veste est conçue pour durer et résister aux éléments. Disponible dans 5 couleurs attrayantes, vous pouvez choisir celle qui convient le mieux à votre style et à vos préférences. Polyvalente et fonctionnelle, la veste doudoune à capuche OmniHeat pour hommes est adaptée à différentes occasions, que vous vous rendiez au travail, que vous partiez pour une sortie décontractée ou que vous assistiez à un événement en plein air. Découvrez le parfait mélange de style, de confort et de fonctionnalité avec la veste doudoune à capuche OmniHeat pour hommes. Rehaussez votre garde-robe d\'hiver et restez bien au chaud tout en profitant de l\'extérieur. Affrontez le froid avec style et faites une déclaration avec cette pièce essentielle.',
                    'meta-description'  => 'méta description',
                    'meta-keywords'     => 'méta1, méta2, méta3',
                    'meta-title'        => 'Meta Title',
                    'name'              => 'Veste doudoune à capuche OmniHeat pour hommes - Bleu-Vert - M',
                    'short-description' => 'Restez au chaud et à la mode avec notre veste doudoune à capuche OmniHeat pour hommes. Cette veste est conçue pour offrir une chaleur ultime et est dotée de poches intérieures pour plus de commodité. Le matériau isolant assure que vous restiez confortable par temps froid. Disponible dans 5 couleurs attrayantes, en faisant un choix polyvalent pour différentes occasions.',
                ],

                '11' => [
                    'description'       => 'Présentation de la veste doudoune à capuche OmniHeat pour hommes, votre solution incontournable pour rester au chaud et à la mode pendant les saisons plus froides. Cette veste est conçue avec durabilité et chaleur à l\'esprit, garantissant qu\'elle devienne votre compagnon de confiance. La conception à capuche n\'ajoute pas seulement une touche de style mais offre également une chaleur supplémentaire, vous protégeant des vents froids et du mauvais temps. Les manches longues offrent une couverture complète, vous assurant de rester confortable de l\'épaule au poignet. Équipée de poches intérieures, cette veste doudoune offre une commodité pour transporter vos essentiels ou garder vos mains au chaud. Le rembourrage synthétique isolé offre une chaleur renforcée, en faisant l\'idéal pour affronter les journées et les nuits froides. Fabriquée à partir d\'une coque et d\'une doublure en polyester durable, cette veste est conçue pour durer et résister aux éléments. Disponible dans 5 couleurs attrayantes, vous pouvez choisir celle qui convient le mieux à votre style et à vos préférences. Polyvalente et fonctionnelle, la veste doudoune à capuche OmniHeat pour hommes est adaptée à différentes occasions, que vous vous rendiez au travail, que vous partiez pour une sortie décontractée ou que vous assistiez à un événement en plein air. Découvrez le parfait mélange de style, de confort et de fonctionnalité avec la veste doudoune à capuche OmniHeat pour hommes. Rehaussez votre garde-robe d\'hiver et restez bien au chaud tout en profitant de l\'extérieur. Affrontez le froid avec style et faites une déclaration avec cette pièce essentielle.',
                    'meta-description'  => 'méta description',
                    'meta-keywords'     => 'méta1, méta2, méta3',
                    'meta-title'        => 'Meta Title',
                    'name'              => 'Veste doudoune à capuche OmniHeat pour hommes - Bleu-Vert - L',
                    'short-description' => 'Restez au chaud et à la mode avec notre veste doudoune à capuche OmniHeat pour hommes. Cette veste est conçue pour offrir une chaleur ultime et est dotée de poches intérieures pour plus de commodité. Le matériau isolant assure que vous restiez confortable par temps froid. Disponible dans 5 couleurs attrayantes, en faisant un choix polyvalent pour différentes occasions.',
                ],
            ],

            'product-attribute-values' => [
                '1' => [
                    'description'      => 'Le bonnet tricoté Arctic Cozy est votre solution pour rester au chaud, confortable et élégant pendant les mois les plus froids. Fabriqué à partir d\'un mélange doux et durable de tricot acrylique, ce bonnet est conçu pour offrir un ajustement confortable et serré. Son design classique le rend adapté aux hommes et aux femmes, offrant un accessoire polyvalent qui complète différents styles. Que vous sortiez pour une journée décontractée en ville ou que vous vous aventuriez en plein air, ce bonnet ajoute une touche de confort et de chaleur à votre tenue. Le matériau doux et respirant garantit que vous restez confortable sans sacrifier le style. Le bonnet tricoté Arctic Cozy n\'est pas seulement un accessoire; c\'est une déclaration de mode hivernale. Sa simplicité le rend facile à associer à différentes tenues, ce qui en fait un incontournable de votre garde-robe d\'hiver. Idéal pour offrir en cadeau ou pour vous faire plaisir, ce bonnet est un ajout réfléchi à toute tenue d\'hiver. C\'est un accessoire polyvalent qui va au-delà de la fonctionnalité, ajoutant une touche de chaleur et de style à votre look. Embrassez l\'essence de l\'hiver avec le bonnet tricoté Arctic Cozy. Que vous profitiez d\'une journée décontractée ou que vous affrontiez les éléments, laissez ce bonnet être votre compagnon de confort et de style. Rehaussez votre garde-robe d\'hiver avec cet accessoire classique qui combine parfaitement chaleur et intemporalité.',
                    'meta-description' => 'méta description',
                    'meta-keywords'    => 'méta1, méta2, méta3',
                    'meta-title'       => 'Titre méta',
                    'name'             => 'Bonnet tricoté unisexe Arctic Cozy',
                    'sort-description' => 'Embrassez les jours froids avec style avec notre bonnet tricoté Arctic Cozy. Fabriqué à partir d\'un mélange doux et durable d\'acrylique, ce bonnet classique offre chaleur et polyvalence. Convient aux hommes et aux femmes, c\'est l\'accessoire idéal pour une tenue décontractée ou en extérieur. Rehaussez votre garde-robe d\'hiver ou offrez-le à quelqu\'un de spécial avec ce bonnet essentiel.',
                ],

                '2' => [
                    'description'      => 'L\'écharpe hivernale Arctic Bliss est bien plus qu\'un accessoire pour temps froid; c\'est une déclaration de chaleur, de confort et de style pour la saison hivernale. Fabriquée avec soin à partir d\'un luxueux mélange d\'acrylique et de laine, cette écharpe est conçue pour vous garder au chaud et à l\'aise même par les températures les plus froides. La texture douce et moelleuse offre non seulement une isolation contre le froid, mais ajoute également une touche de luxe à votre garde-robe d\'hiver. Le design de l\'écharpe Arctic Bliss est à la fois élégant et polyvalent, ce qui en fait un ajout parfait à une variété de tenues d\'hiver. Que vous vous habilliez pour une occasion spéciale ou que vous ajoutiez une couche chic à votre look quotidien, cette écharpe complète votre style sans effort. La longueur extra-longue de l\'écharpe offre des options de style personnalisables. Enroulez-la pour plus de chaleur, laissez-la pendre librement pour un look décontracté, ou expérimentez avec différents nœuds pour exprimer votre style unique. Cette polyvalence en fait un accessoire indispensable pour la saison hivernale. Vous recherchez le cadeau parfait? L\'écharpe hivernale Arctic Bliss est un choix idéal. Que vous surpreniez un être cher ou que vous vous fassiez plaisir, cette écharpe est un cadeau intemporel et pratique qui sera chéri tout au long des mois d\'hiver. Embrassez l\'hiver avec l\'écharpe hivernale Arctic Bliss, où chaleur et style se rencontrent en parfaite harmonie. Rehaussez votre garde-robe d\'hiver avec cet accessoire essentiel qui vous garde non seulement au chaud, mais ajoute également une touche de sophistication à votre ensemble par temps froid.',
                    'meta-description' => 'méta description',
                    'meta-keywords'    => 'méta1, méta2, méta3',
                    'meta-title'       => 'Titre méta',
                    'name'             => 'Écharpe hivernale élégante Arctic Bliss',
                    'sort-description' => 'Découvrez l\'étreinte de chaleur et de style avec notre écharpe hivernale Arctic Bliss. Fabriquée à partir d\'un luxueux mélange d\'acrylique et de laine, cette écharpe confortable est conçue pour vous garder au chaud pendant les jours les plus froids. Son design élégant et polyvalent, associé à une longueur extra-longue, offre des options de style personnalisables. Rehaussez votre garde-robe d\'hiver ou faites plaisir à quelqu\'un de spécial avec cet accessoire hivernal essentiel.',
                ],

                '3' => [
                    'description'      => 'Découvrez les gants d\'hiver Arctic Touchscreen - là où chaleur, style et connectivité se rencontrent pour améliorer votre expérience hivernale. Fabriqués à partir d\'acrylique de haute qualité, ces gants sont conçus pour offrir une chaleur et une durabilité exceptionnelles. Les bouts de doigts compatibles avec les écrans tactiles vous permettent de rester connecté sans exposer vos mains au froid. Répondez aux appels, envoyez des messages et naviguez sur vos appareils sans effort, tout en gardant vos mains bien au chaud. La doublure isolante ajoute une couche supplémentaire de confort, faisant de ces gants votre choix idéal pour affronter le froid de l\'hiver. Que vous vous déplaciez, fassiez des courses ou profitiez d\'activités de plein air, ces gants vous offrent la chaleur et la protection dont vous avez besoin. Les poignets élastiques assurent un ajustement sûr, empêchant les courants d\'air froids et maintenant les gants en place pendant vos activités quotidiennes. Le design élégant ajoute une touche de flair à votre ensemble hivernal, faisant de ces gants aussi à la mode que fonctionnels. Idéaux pour offrir en cadeau ou pour vous faire plaisir, les gants d\'hiver Arctic Touchscreen sont un accessoire indispensable pour l\'individu moderne. Dites adieu à l\'inconvénient de devoir enlever vos gants pour utiliser vos appareils et embrassez la fusion parfaite de chaleur, de style et de connectivité. Restez connecté, restez au chaud et restez élégant avec les gants d\'hiver Arctic Touchscreen - votre compagnon fiable pour conquérir la saison hivernale en toute confiance.',
                    'meta-description' => 'méta description',
                    'meta-keywords'    => 'méta1, méta2, méta3',
                    'meta-title'       => 'Titre méta',
                    'name'             => 'Gants d\'hiver Arctic Touchscreen',
                    'sort-description' => 'Restez connecté et au chaud avec nos gants d\'hiver Arctic Touchscreen. Ces gants sont non seulement fabriqués à partir d\'acrylique de haute qualité pour la chaleur et la durabilité, mais ils sont également dotés d\'un design compatible avec les écrans tactiles. Avec une doublure isolante, des poignets élastiques pour un ajustement sûr et un look élégant, ces gants sont parfaits pour une utilisation quotidienne par temps froid.',
                ],

                '4' => [
                    'description'      => 'Présentation des chaussettes en laine Arctic Warmth - votre compagnon essentiel pour des pieds confortables et douillets pendant les saisons froides. Fabriquées à partir d\'un mélange premium de laine mérinos, d\'acrylique, de nylon et d\'élasthanne, ces chaussettes sont conçues pour offrir une chaleur et un confort inégalés. Le mélange de laine assure que vos pieds restent chauds même par temps très froid, ce qui en fait le choix parfait pour les aventures hivernales ou tout simplement pour rester bien au chaud à la maison. La texture douce et douillette des chaussettes offre une sensation de luxe contre votre peau. Dites adieu aux pieds froids en embrassant la chaleur moelleuse offerte par ces chaussettes en laine. Conçues pour la durabilité, les chaussettes sont dotées d\'un talon et d\'un bout renforcés, ajoutant une résistance supplémentaire aux zones soumises à une usure élevée. Cela garantit que vos chaussettes résistent à l\'épreuve du temps, offrant un confort et une douceur durables. La nature respirante du matériau empêche la surchauffe, permettant à vos pieds de rester confortables et secs tout au long de la journée. Que vous vous aventuriez à l\'extérieur pour une randonnée hivernale ou que vous vous détendiez à l\'intérieur, ces chaussettes offrent le parfait équilibre entre chaleur et respirabilité. Polyvalentes et élégantes, ces chaussettes en laine sont adaptées à différentes occasions. Associez-les à vos bottes préférées pour un look hivernal à la mode ou portez-les à la maison pour un confort ultime. Rehaussez votre garde-robe d\'hiver et privilégiez le confort avec les chaussettes en laine Arctic Warmth. Offrez à vos pieds le luxe qu\'ils méritent et plongez dans un monde de douceur qui dure toute la saison.',
                    'meta-description' => 'méta description',
                    'meta-keywords'    => 'méta1, méta2, méta3',
                    'meta-title'       => 'Titre méta',
                    'name'             => 'Chaussettes en laine Arctic Warmth',
                    'sort-description' => 'Découvrez la chaleur et le confort inégalés de nos chaussettes en laine Arctic Warmth. Fabriquées à partir d\'un mélange de laine mérinos, d\'acrylique, de nylon et d\'élasthanne, ces chaussettes offrent une douceur ultime pour le temps froid. Avec un talon et un bout renforcés pour la durabilité, ces chaussettes polyvalentes et élégantes sont parfaites pour différentes occasions.',
                ],

                '5' => [
                    'description'      => 'Présentation du pack d\'accessoires d\'hiver Arctic Frost, votre solution incontournable pour rester au chaud, élégant et connecté pendant les journées froides de l\'hiver. Cet ensemble soigneusement sélectionné réunit quatre accessoires d\'hiver essentiels pour créer un ensemble harmonieux. L\'écharpe luxueuse, tissée à partir d\'un mélange d\'acrylique et de laine, ajoute non seulement une couche de chaleur, mais apporte également une touche d\'élégance à votre garde-robe d\'hiver. Le bonnet en tricot doux, fabriqué avec soin, promet de vous garder au chaud tout en ajoutant une touche de mode à votre look. Mais cela ne s\'arrête pas là - notre pack comprend également des gants compatibles avec les écrans tactiles. Restez connecté sans sacrifier la chaleur pendant que vous naviguez facilement sur vos appareils. Que vous répondiez à des appels, envoyiez des messages ou capturiez des moments d\'hiver sur votre smartphone, ces gants assurent la commodité sans compromettre le style. La texture douce et douillette des chaussettes offre une sensation de luxe contre votre peau. Dites adieu aux pieds froids en embrassant la chaleur moelleuse offerte par ces chaussettes en laine. Le pack d\'accessoires d\'hiver Arctic Frost ne se limite pas à la fonctionnalité ; c\'est une déclaration de mode hivernale. Chaque pièce est conçue non seulement pour vous protéger du froid, mais aussi pour rehausser votre style pendant la saison glaciale. Les matériaux choisis pour ce pack privilégient à la fois la durabilité et le confort, vous permettant de profiter du paysage hivernal avec style. Que vous vous fassiez plaisir ou que vous recherchiez le cadeau parfait, le pack d\'accessoires d\'hiver Arctic Frost est un choix polyvalent. Faites plaisir à quelqu\'un de spécial pendant la saison des fêtes ou rehaussez votre propre garde-robe d\'hiver avec cet ensemble élégant et fonctionnel. Embrassez le froid en toute confiance, sachant que vous avez les accessoires parfaits pour vous garder au chaud et chic.',
                    'meta-description' => 'méta description',
                    'meta-keywords'    => 'méta1, méta2, méta3',
                    'meta-title'       => 'Titre méta',
                    'name'             => 'Pack d\'accessoires d\'hiver Arctic Frost',
                    'sort-description' => 'Embrassez le froid de l\'hiver avec notre pack d\'accessoires d\'hiver Arctic Frost. Cet ensemble soigneusement sélectionné comprend une écharpe luxueuse, un bonnet douillet, des gants compatibles avec les écrans tactiles et des chaussettes en laine. À la fois élégant et fonctionnel, cet ensemble est fabriqué à partir de matériaux de haute qualité, garantissant à la fois durabilité et confort. Rehaussez votre garde-robe d\'hiver ou faites plaisir à quelqu\'un de spécial avec cette option de cadeau parfaite.',
                ],

                '6' => [
                    'description'      => 'Présentation du pack d\'accessoires d\'hiver Arctic Frost, votre solution incontournable pour rester au chaud, élégant et connecté pendant les journées froides de l\'hiver. Cet ensemble soigneusement sélectionné réunit quatre accessoires d\'hiver essentiels pour créer un ensemble harmonieux. L\'écharpe luxueuse, tissée à partir d\'un mélange d\'acrylique et de laine, ajoute non seulement une couche de chaleur, mais apporte également une touche d\'élégance à votre garde-robe d\'hiver. Le bonnet en tricot doux, fabriqué avec soin, promet de vous garder au chaud tout en ajoutant une touche de mode à votre look. Mais cela ne s\'arrête pas là - notre pack comprend également des gants compatibles avec les écrans tactiles. Restez connecté sans sacrifier la chaleur pendant que vous naviguez facilement sur vos appareils. Que vous répondiez à des appels, envoyiez des messages ou capturiez des moments d\'hiver sur votre smartphone, ces gants assurent la commodité sans compromettre le style. La texture douce et douillette des chaussettes offre une sensation de luxe contre votre peau. Dites adieu aux pieds froids en embrassant la chaleur moelleuse offerte par ces chaussettes en laine. Le pack d\'accessoires d\'hiver Arctic Frost ne se limite pas à la fonctionnalité ; c\'est une déclaration de mode hivernale. Chaque pièce est conçue non seulement pour vous protéger du froid, mais aussi pour rehausser votre style pendant la saison glaciale. Les matériaux choisis pour ce pack privilégient à la fois la durabilité et le confort, vous permettant de profiter du paysage hivernal avec style. Que vous vous fassiez plaisir ou que vous recherchiez le cadeau parfait, le pack d\'accessoires d\'hiver Arctic Frost est un choix polyvalent. Faites plaisir à quelqu\'un de spécial pendant la saison des fêtes ou rehaussez votre propre garde-robe d\'hiver avec cet ensemble élégant et fonctionnel. Embrassez le froid en toute confiance, sachant que vous avez les accessoires parfaits pour vous garder au chaud et chic.',
                    'meta-description' => 'méta description',
                    'meta-keywords'    => 'méta1, méta2, méta3',
                    'meta-title'       => 'Titre méta',
                    'name'             => 'Pack d\'accessoires d\'hiver Arctic Frost',
                    'sort-description' => 'Embrassez le froid de l\'hiver avec notre pack d\'accessoires d\'hiver Arctic Frost. Cet ensemble soigneusement sélectionné comprend une écharpe luxueuse, un bonnet douillet, des gants compatibles avec les écrans tactiles et des chaussettes en laine. À la fois élégant et fonctionnel, cet ensemble est fabriqué à partir de matériaux de haute qualité, garantissant à la fois durabilité et confort. Rehaussez votre garde-robe d\'hiver ou faites plaisir à quelqu\'un de spécial avec cette option de cadeau parfaite.',
                ],

                '7' => [
                    'description'      => 'Présentation de la veste matelassée à capuche OmniHeat pour hommes, votre solution incontournable pour rester au chaud et à la mode pendant les saisons plus froides. Cette veste est conçue avec durabilité et chaleur à l\'esprit, garantissant qu\'elle devienne votre compagnon de confiance. Le design à capuche ajoute non seulement une touche de style, mais offre également une chaleur supplémentaire, vous protégeant des vents froids et des intempéries. Les manches longues offrent une couverture complète, vous assurant de rester confortable de l\'épaule au poignet. Équipée de poches intérieures, cette veste matelassée offre une commodité pour transporter vos essentiels ou garder vos mains au chaud. Le rembourrage synthétique isolant offre une chaleur accrue, ce qui en fait l\'idéal pour affronter les journées et les nuits froides. Fabriquée à partir d\'une coque et d\'une doublure en polyester durables, cette veste est conçue pour durer et résister aux éléments. Disponible en 5 couleurs attrayantes, vous pouvez choisir celle qui correspond à votre style et à vos préférences. Polyvalente et fonctionnelle, la veste matelassée à capuche OmniHeat pour hommes convient à différentes occasions, que vous vous rendiez au travail, que vous fassiez une sortie décontractée ou que vous assistiez à un événement en plein air. Découvrez le mélange parfait de style, de confort et de fonctionnalité avec la veste matelassée à capuche OmniHeat pour hommes. Rehaussez votre garde-robe d\'hiver et restez bien au chaud tout en profitant de l\'extérieur. Affrontez le froid avec style et faites une déclaration avec cette pièce essentielle.',
                    'meta-description' => 'meta description',
                    'meta-keywords'    => 'meta1, meta2, meta3',
                    'meta-title'       => 'Titre méta',
                    'name'             => 'Veste matelassée à capuche OmniHeat pour hommes',
                    'sort-description' => 'Restez au chaud et à la mode avec notre veste matelassée à capuche OmniHeat pour hommes. Cette veste est conçue pour offrir une chaleur ultime et dispose de poches intérieures pour plus de commodité. Le matériau isolant vous assure de rester confortable par temps froid. Disponible en 5 couleurs attrayantes, ce qui en fait un choix polyvalent pour différentes occasions.',
                ],

                '8' => [
                    'description'      => 'Présentation de la veste matelassée à capuche OmniHeat pour hommes, votre solution incontournable pour rester au chaud et à la mode pendant les saisons plus froides. Cette veste est conçue avec durabilité et chaleur à l\'esprit, garantissant qu\'elle devienne votre compagnon de confiance. Le design à capuche ajoute non seulement une touche de style, mais offre également une chaleur supplémentaire, vous protégeant des vents froids et des intempéries. Les manches longues offrent une couverture complète, vous assurant de rester confortable de l\'épaule au poignet. Équipée de poches intérieures, cette veste matelassée offre une commodité pour transporter vos essentiels ou garder vos mains au chaud. Le rembourrage synthétique isolant offre une chaleur accrue, ce qui en fait l\'idéal pour affronter les journées et les nuits froides. Fabriquée à partir d\'une coque et d\'une doublure en polyester durables, cette veste est conçue pour durer et résister aux éléments. Disponible en 5 couleurs attrayantes, vous pouvez choisir celle qui correspond à votre style et à vos préférences. Polyvalente et fonctionnelle, la veste matelassée à capuche OmniHeat pour hommes convient à différentes occasions, que vous vous rendiez au travail, que vous fassiez une sortie décontractée ou que vous assistiez à un événement en plein air. Découvrez le mélange parfait de style, de confort et de fonctionnalité avec la veste matelassée à capuche OmniHeat pour hommes. Rehaussez votre garde-robe d\'hiver et restez bien au chaud tout en profitant de l\'extérieur. Affrontez le froid avec style et faites une déclaration avec cette pièce essentielle.',
                    'meta-description' => 'meta description',
                    'meta-keywords'    => 'meta1, meta2, meta3',
                    'meta-title'       => 'Titre méta',
                    'name'             => 'Veste matelassée à capuche OmniHeat pour hommes-Bleu-Jaune-M',
                    'sort-description' => 'Restez au chaud et à la mode avec notre veste matelassée à capuche OmniHeat pour hommes. Cette veste est conçue pour offrir une chaleur ultime et dispose de poches intérieures pour plus de commodité. Le matériau isolant vous assure de rester confortable par temps froid. Disponible en 5 couleurs attrayantes, ce qui en fait un choix polyvalent pour différentes occasions.',
                ],

                '9' => [
                    'description'      => 'Présentation de la veste matelassée à capuche OmniHeat pour hommes, votre solution incontournable pour rester au chaud et à la mode pendant les saisons plus froides. Cette veste est conçue avec durabilité et chaleur à l\'esprit, garantissant qu\'elle devienne votre compagnon de confiance. Le design à capuche ajoute non seulement une touche de style, mais offre également une chaleur supplémentaire, vous protégeant des vents froids et des intempéries. Les manches longues offrent une couverture complète, vous assurant de rester confortable de l\'épaule au poignet. Équipée de poches intérieures, cette veste matelassée offre une commodité pour transporter vos essentiels ou garder vos mains au chaud. Le rembourrage synthétique isolant offre une chaleur accrue, ce qui en fait l\'idéal pour affronter les journées et les nuits froides. Fabriquée à partir d\'une coque et d\'une doublure en polyester durables, cette veste est conçue pour durer et résister aux éléments. Disponible en 5 couleurs attrayantes, vous pouvez choisir celle qui correspond à votre style et à vos préférences. Polyvalente et fonctionnelle, la veste matelassée à capuche OmniHeat pour hommes convient à différentes occasions, que vous vous rendiez au travail, que vous fassiez une sortie décontractée ou que vous assistiez à un événement en plein air. Découvrez le mélange parfait de style, de confort et de fonctionnalité avec la veste matelassée à capuche OmniHeat pour hommes. Rehaus\sez votre garde-robe d\'hiver et restez bien au chaud tout en profitant de l\'extérieur. Affrontez le froid avec style et faites une déclaration avec cette pièce essentielle.',
                    'meta-description' => 'meta description',
                    'meta-keywords'    => 'meta1, meta2, meta3',
                    'meta-title'       => 'Titre méta',
                    'name'             => 'Veste matelassée à capuche OmniHeat pour hommes-Bleu-Jaune-L',
                    'sort-description' => 'Restez au chaud et à la mode avec notre veste matelassée à capuche OmniHeat pour hommes. Cette veste est conçue pour offrir une chaleur ultime et dispose de poches intérieures pour plus de commodité. Le matériau isolant vous assure de rester confortable par temps froid. Disponible en 5 couleurs attrayantes, ce qui en fait un choix polyvalent pour différentes occasions.',
                ],

                '10' => [
                    'description'      => 'Découvrez la veste matelassée à capuche OmniHeat pour hommes, votre solution incontournable pour rester au chaud et à la mode pendant les saisons plus froides. Cette veste est conçue avec durabilité et chaleur à l\'esprit, garantissant qu\'elle devienne votre compagnon de confiance. Le design à capuche ajoute non seulement une touche de style, mais offre également une chaleur supplémentaire, vous protégeant des vents froids et des intempéries. Les manches longues offrent une couverture complète, vous assurant de rester confortable de l\'épaule au poignet. Équipée de poches intérieures, cette veste matelassée offre une commodité pour transporter vos essentiels ou garder vos mains au chaud. Le rembourrage synthétique isolant offre une chaleur accrue, ce qui en fait un choix idéal pour affronter les journées et les nuits froides. Fabriquée à partir d\'une coque et d\'une doublure en polyester durable, cette veste est conçue pour durer et résister aux éléments. Disponible en 5 couleurs attrayantes, vous pouvez choisir celle qui correspond à votre style et à vos préférences. Polyvalente et fonctionnelle, la veste matelassée à capuche OmniHeat pour hommes convient à différentes occasions, que vous vous rendiez au travail, que vous fassiez une sortie décontractée ou que vous assistiez à un événement en plein air. Découvrez le mélange parfait de style, de confort et de fonctionnalité avec la veste matelassée à capuche OmniHeat pour hommes. Rehaussez votre garde-robe d\'hiver et restez bien au chaud tout en profitant de l\'extérieur. Affrontez le froid avec style et faites une déclaration avec cette pièce essentielle.',
                    'meta-description' => 'description méta',
                    'meta-keywords'    => 'méta1, méta2, méta3',
                    'meta-title'       => 'Titre méta',
                    'name'             => 'Veste matelassée à capuche OmniHeat pour hommes-Bleu-Vert-M',
                    'sort-description' => 'Restez au chaud et à la mode avec notre veste matelassée à capuche OmniHeat pour hommes. Cette veste est conçue pour offrir une chaleur ultime et dispose de poches intérieures pour plus de commodité. Le matériau isolant vous assure de rester confortable par temps froid. Disponible en 5 couleurs attrayantes, ce qui en fait un choix polyvalent pour différentes occasions.',
                ],

                '11' => [
                    'description'      => 'Découvrez la veste matelassée à capuche OmniHeat pour hommes, votre solution incontournable pour rester au chaud et à la mode pendant les saisons plus froides. Cette veste est conçue avec durabilité et chaleur à l\'esprit, garantissant qu\'elle devienne votre compagnon de confiance. Le design à capuche ajoute non seulement une touche de style, mais offre également une chaleur supplémentaire, vous protégeant des vents froids et des intempéries. Les manches longues offrent une couverture complète, vous assurant de rester confortable de l\'épaule au poignet. Équipée de poches intérieures, cette veste matelassée offre une commodité pour transporter vos essentiels ou garder vos mains au chaud. Le rembourrage synthétique isolant offre une chaleur accrue, ce qui en fait un choix idéal pour affronter les journées et les nuits froides. Fabriquée à partir d\'une coque et d\'une doublure en polyester durable, cette veste est conçue pour durer et résister aux éléments. Disponible en 5 couleurs attrayantes, vous pouvez choisir celle qui correspond à votre style et à vos préférences. Polyvalente et fonctionnelle, la veste matelassée à capuche OmniHeat pour hommes convient à différentes occasions, que vous vous rendiez au travail, que vous fassiez une sortie décontractée ou que vous assistiez à un événement en plein air. Découvrez le mélange parfait de style, de confort et de fonctionnalité avec la veste matelassée à capuche OmniHeat pour hommes. Rehaussez votre garde-robe d\'hiver et restez bien au chaud tout en profitant de l\'extérieur. Affrontez le froid avec style et faites une déclaration avec cette pièce essentielle.',
                    'meta-description' => 'description méta',
                    'meta-keywords'    => 'méta1, méta2, méta3',
                    'meta-title'       => 'Titre méta',
                    'name'             => 'Veste matelassée à capuche OmniHeat pour hommes-Bleu-Vert-L',
                    'sort-description' => 'Restez au chaud et à la mode avec notre veste matelassée à capuche OmniHeat pour hommes. Cette veste est conçue pour offrir une chaleur ultime et dispose de poches intérieures pour plus de commodité. Le matériau isolant vous assure de rester confortable par temps froid. Disponible en 5 couleurs attrayantes, ce qui en fait un choix polyvalent pour différentes occasions.',
                ],
            ],

            'product-bundle-option-translations' => [
                '1' => [
                    'label' => 'Option de pack 1',
                ],

                '2' => [
                    'label' => 'Option de pack 1',
                ],

                '3' => [
                    'label' => 'Option de pack 2',
                ],

                '4' => [
                    'label' => 'Option de pack 2',
                ],
            ],
        ],
    ],

    'installer' => [
        'index' => [
            'create-administrator' => [
                'admin'            => 'Admin',
                'bagisto'          => 'Bagisto',
                'confirm-password' => 'Confirmer le mot de passe',
                'email'            => 'Email',
                'email-address'    => 'admin@example.com',
                'password'         => 'Mot de passe',
                'title'            => 'Créer un administrateur',
            ],

            'environment-configuration' => [
                'algerian-dinar'              => 'Dinar algérien (DZD)',
                'allowed-currencies'          => 'Devises autorisées',
                'allowed-locales'             => 'Langues autorisées',
                'application-name'            => 'Nom de l\'application',
                'argentine-peso'              => 'Peso argentin (ARS)',
                'australian-dollar'           => 'Dollar australien (AUD)',
                'bagisto'                     => 'Bagisto',
                'bangladeshi-taka'            => 'Taka bangladais (BDT)',
                'brazilian-real'              => 'Réel brésilien (BRL)',
                'british-pound-sterling'      => 'Livre sterling britannique (GBP)',
                'canadian-dollar'             => 'Dollar canadien (CAD)',
                'cfa-franc-bceao'             => 'Franc CFA BCEAO (XOF)',
                'cfa-franc-beac'              => 'Franc CFA BEAC (XAF)',
                'chilean-peso'                => 'Peso chilien (CLP)',
                'chinese-yuan'                => 'Yuan chinois (CNY)',
                'colombian-peso'              => 'Peso colombien (COP)',
                'czech-koruna'                => 'Couronne tchèque (CZK)',
                'danish-krone'                => 'Couronne danoise (DKK)',
                'database-connection'         => 'Connexion à la base de données',
                'database-hostname'           => 'Nom d\'hôte de la base de données',
                'database-name'               => 'Nom de la base de données',
                'database-password'           => 'Mot de passe de la base de données',
                'database-port'               => 'Port de la base de données',
                'database-prefix'             => 'Préfixe de la base de données',
                'database-username'           => 'Nom d\'utilisateur de la base de données',
                'default-currency'            => 'Devise par défaut',
                'default-locale'              => 'Langue par défaut',
                'default-timezone'            => 'Fuseau horaire par défaut',
                'default-url'                 => 'URL par défaut',
                'default-url-link'            => 'https://localhost',
                'egyptian-pound'              => 'Livre égyptienne (EGP)',
                'euro'                        => 'Euro (EUR)',
                'fijian-dollar'               => 'Dollar fidjien (FJD)',
                'hong-kong-dollar'            => 'Dollar de Hong Kong (HKD)',
                'hungarian-forint'            => 'Forint hongrois (HUF)',
                'indian-rupee'                => 'Roupie indienne (INR)',
                'indonesian-rupiah'           => 'Roupie indonésienne (IDR)',
                'israeli-new-shekel'          => 'Nouveau shekel israélien (ILS)',
                'japanese-yen'                => 'Yen japonais (JPY)',
                'jordanian-dinar'             => 'Dinar jordanien (JOD)',
                'kazakhstani-tenge'           => 'Tenge kazakh (KZT)',
                'kuwaiti-dinar'               => 'Dinar koweïtien (KWD)',
                'lebanese-pound'              => 'Livre libanaise (LBP)',
                'libyan-dinar'                => 'Dinar libyen (LYD)',
                'malaysian-ringgit'           => 'Ringgit malaisien (MYR)',
                'mauritian-rupee'             => 'Roupie mauricienne (MUR)',
                'mexican-peso'                => 'Peso mexicain (MXN)',
                'moroccan-dirham'             => 'Dirham marocain (MAD)',
                'mysql'                       => 'Mysql',
                'nepalese-rupee'              => 'Roupie népalaise (NPR)',
                'new-taiwan-dollar'           => 'Dollar taïwanais (TWD)',
                'new-zealand-dollar'          => 'Dollar néo-zélandais (NZD)',
                'nigerian-naira'              => 'Naira nigérian (NGN)',
                'norwegian-krone'             => 'Couronne norvégienne (NOK)',
                'omani-rial'                  => 'Rial omanais (OMR)',
                'pakistani-rupee'             => 'Roupie pakistanaise (PKR)',
                'panamanian-balboa'           => 'Balboa panaméen (PAB)',
                'paraguayan-guarani'          => 'Guarani paraguayen (PYG)',
                'peruvian-nuevo-sol'          => 'Nuevo sol péruvien (PEN)',
                'pgsql'                       => 'pgSQL',
                'philippine-peso'             => 'Peso philippin (PHP)',
                'polish-zloty'                => 'Zloty polonais (PLN)',
                'qatari-rial'                 => 'Rial qatari (QAR)',
                'romanian-leu'                => 'Leu roumain (RON)',
                'russian-ruble'               => 'Rouble russe (RUB)',
                'saudi-riyal'                 => 'Riyal saoudien (SAR)',
                'select-timezone'             => 'Sélectionnez le fuseau horaire',
                'singapore-dollar'            => 'Dollar de Singapour (SGD)',
                'south-african-rand'          => 'Rand sud-africain (ZAR)',
                'south-korean-won'            => 'Won sud-coréen (KRW)',
                'sqlsrv'                      => 'SQLSRV',
                'sri-lankan-rupee'            => 'Roupie srilankaise (LKR)',
                'swedish-krona'               => 'Couronne suédoise (SEK)',
                'swiss-franc'                 => 'Franc suisse (CHF)',
                'thai-baht'                   => 'Baht thaïlandais (THB)',
                'title'                       => 'Configuration du magasin',
                'tunisian-dinar'              => 'Dinar tunisien (TND)',
                'turkish-lira'                => 'Livre turque (TRY)',
                'ukrainian-hryvnia'           => 'Hryvnia ukrainienne (UAH)',
                'united-arab-emirates-dirham' => 'Dirham des Émirats arabes unis (AED)',
                'united-states-dollar'        => 'Dollar américain (USD)',
                'uzbekistani-som'             => 'Som ouzbek (UZS)',
                'venezuelan-bolívar'          => 'Bolívar vénézuélien (VEF)',
                'vietnamese-dong'             => 'Dong vietnamien (VND)',
                'warning-message'             => 'Attention! Les paramètres de langue système par défaut et de devise par défaut sont permanents et ne peuvent pas être modifiés une fois définis.',
                'zambian-kwacha'              => 'Kwacha zambien (ZMW)',
            ],

            'sample-products' => [
                'download-sample' => 'télécharger l\'échantillon',
                'no'              => 'Non',
                'sample-products' => 'Produits d\'échantillon',
                'title'           => 'Produits d\'échantillon',
                'yes'             => 'Oui',
            ],

            'installation-processing' => [
                'bagisto'      => 'Installation de Bagisto',
                'bagisto-info' => 'Création des tables de la base de données, cela peut prendre quelques instants',
                'title'        => 'Installation',
            ],

            'installation-completed' => [
                'admin-panel'                => 'Panneau d\'administration',
                'bagisto-forums'             => 'Forum Bagisto',
                'customer-panel'             => 'Panneau du client',
                'explore-bagisto-extensions' => 'Explorer les extensions Bagisto',
                'title'                      => 'Installation terminée',
                'title-info'                 => 'Bagisto est installé avec succès sur votre système.',
            ],

            'ready-for-installation' => [
                'create-database-table'   => 'Créer la table de la base de données',
                'install'                 => 'Installation',
                'install-info'            => 'Bagisto pour l’installation',
                'install-info-button'     => 'Cliquez sur le bouton ci-dessous pour',
                'populate-database-table' => 'Remplir les tables de la base de données',
                'start-installation'      => 'Démarrer l’installation',
                'title'                   => 'Prêt pour l’installation',
            ],

            'start' => [
                'locale'        => 'Locale',
                'main'          => 'Début',
                'select-locale' => 'Sélectionner la langue',
                'title'         => 'Votre installation de Bagisto',
                'welcome-title' => 'Bienvenue sur Bagisto',
            ],

            'server-requirements' => [
                'calendar'    => 'Calendrier',
                'ctype'       => 'cType',
                'curl'        => 'cURL',
                'dom'         => 'DOM',
                'fileinfo'    => 'Fileinfo',
                'filter'      => 'Filtre',
                'gd'          => 'GD',
                'hash'        => 'Hachage',
                'intl'        => 'Intl',
                'json'        => 'JSON',
                'mbstring'    => 'mbstring',
                'openssl'     => 'OpenSSL',
                'pcre'        => 'PCRE',
                'pdo'         => 'PDO',
                'php'         => 'PHP',
                'php-version' => '8.1 ou supérieure',
                'session'     => 'Session',
                'title'       => 'Configuration requise du serveur',
                'tokenizer'   => 'Tokenizer',
                'xml'         => 'XML',
            ],

            'arabic'                   => 'Arabe',
            'back'                     => 'Retour',
            'bagisto'                  => 'Bagisto',
            'bagisto-info'             => 'Un projet communautaire par',
            'bagisto-logo'             => 'Logo de Bagisto',
            'bengali'                  => 'Bengali',
            'chinese'                  => 'Chinois',
            'continue'                 => 'Continuer',
            'dutch'                    => 'Néerlandais',
            'english'                  => 'Anglais',
            'french'                   => 'Français',
            'german'                   => 'Allemand',
            'hebrew'                   => 'Hébreu',
            'hindi'                    => 'Hindi',
            'installation-description' => 'L\'installation de Bagisto implique généralement plusieurs étapes. Voici un aperçu général du processus d\'installation pour Bagisto',
            'installation-info'        => 'Nous sommes heureux de vous voir ici !',
            'installation-title'       => 'Bienvenue dans l\'installation',
            'italian'                  => 'Italien',
            'japanese'                 => 'Japonais',
            'persian'                  => 'Persan',
            'polish'                   => 'Polonais',
            'portuguese'               => 'Portugais brésilien',
            'russian'                  => 'Russe',
            'sinhala'                  => 'Sinhala',
            'spanish'                  => 'Espagnol',
            'title'                    => 'Installeur de Bagisto',
            'turkish'                  => 'Turc',
            'ukrainian'                => 'Ukrainien',
            'webkul'                   => 'Webkul',
        ],
    ],
];
