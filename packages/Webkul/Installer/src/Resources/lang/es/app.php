<?php

return [
    'seeders' => [
        'attribute' => [
            'attribute-families' => [
                'default' => 'Predeterminado',
            ],

            'attribute-groups' => [
                'description'       => 'Descripción',
                'general'           => 'General',
                'inventories'       => 'Inventarios',
                'meta-description'  => 'Meta Descripción',
                'price'             => 'Precio',
                'shipping'          => 'Envío',
                'settings'          => 'Configuraciones',
            ],

            'attributes' => [
                'brand'                => 'Marca',
                'color'                => 'Color',
                'cost'                 => 'Costo',
                'description'          => 'Descripción',
                'featured'             => 'Destacado',
                'guest-checkout'       => 'Compra de Invitado',
                'height'               => 'Altura',
                'length'               => 'Longitud',
                'meta-title'           => 'Meta Título',
                'meta-keywords'        => 'Meta Palabras Clave',
                'meta-description'     => 'Meta Descripción',
                'manage-stock'         => 'Gestionar Stock',
                'new'                  => 'Nuevo',
                'name'                 => 'Nombre',
                'product-number'       => 'Número de Producto',
                'price'                => 'Precio',
                'sku'                  => 'SKU',
                'status'               => 'Estado',
                'short-description'    => 'Descripción Corta',
                'special-price'        => 'Precio Especial',
                'special-price-from'   => 'Precio Especial Desde',
                'special-price-to'     => 'Precio Especial Hasta',
                'size'                 => 'Tamaño',
                'tax-category'         => 'Categoría de Impuestos',
                'url-key'              => 'Clave de URL',
                'visible-individually' => 'Visible Individualmente',
                'width'                => 'Ancho',
                'weight'               => 'Peso',
            ],

            'attribute-options' => [
                'black'  => 'Negro',
                'green'  => 'Verde',
                'l'      => 'L',
                'm'      => 'M',
                'red'    => 'Rojo',
                's'      => 'S',
                'white'  => 'Blanco',
                'xl'     => 'XL',
                'yellow' => 'Amarillo',
            ],
        ],

        'category' => [
            'categories' => [
                'description' => 'Descripción de la Categoría Raíz',
                'name'        => 'Raíz',
            ],
        ],

        'cms' => [
            'pages' => [
                'about-us' => [
                    'content' => 'Contenido de la Página Acerca de Nosotros',
                    'title'   => 'Acerca de Nosotros',
                ],

                'refund-policy' => [
                    'content' => 'Contenido de la Página Política de Devolución',
                    'title'   => 'Política de Devolución',
                ],

                'return-policy' => [
                    'content' => 'Contenido de la Página Política de Retorno',
                    'title'   => 'Política de Retorno',
                ],

                'terms-conditions' => [
                    'content' => 'Contenido de la Página Términos y Condiciones',
                    'title'   => 'Términos y Condiciones',
                ],

                'terms-of-use' => [
                    'content' => 'Contenido de la Página Términos de Uso',
                    'title'   => 'Términos de Uso',
                ],

                'contact-us' => [
                    'content' => 'Contenido de la Página Contáctenos',
                    'title'   => 'Contáctenos',
                ],

                'customer-service' => [
                    'content' => 'Contenido de la Página Servicio al Cliente',
                    'title'   => 'Servicio al Cliente',
                ],

                'whats-new' => [
                    'content' => 'Contenido de la Página Novedades',
                    'title'   => 'Novedades',
                ],

                'payment-policy' => [
                    'content' => 'Contenido de la Página Política de Pago',
                    'title'   => 'Política de Pago',
                ],

                'shipping-policy' => [
                    'content' => 'Contenido de la Página Política de Envío',
                    'title'   => 'Política de Envío',
                ],

                'privacy-policy' => [
                    'content' => 'Contenido de la Página Política de Privacidad',
                    'title'   => 'Política de Privacidad',
                ],
            ],
        ],

        'core' => [
            'channels' => [
                'meta-title'       => 'Tienda de Demostración',
                'meta-keywords'    => 'Palabras Clave de Meta de la Tienda de Demostración',
                'meta-description' => 'Descripción de Meta de la Tienda de Demostración',
                'name'             => 'Predeterminado',
            ],

            'currencies' => [
                'CNY' => 'Yuan Chino',
                'AED' => 'Dirham',
                'EUR' => 'EURO',
                'INR' => 'Rupia India',
                'IRR' => 'Rial Iraní',
                'AFN' => 'Shekel Israelí',
                'JPY' => 'Yen Japonés',
                'GBP' => 'Libra Esterlina',
                'RUB' => 'Rublo Ruso',
                'SAR' => 'Riyal Saudí',
                'TRY' => 'Lira Turca',
                'USD' => 'Dólar Estadounidense',
                'UAH' => 'Grivna Ucraniana',
            ],

            'locales' => [
                'ar'    => 'Árabe',
                'bn'    => 'Bengalí',
                'de'    => 'Alemán',
                'es'    => 'Español',
                'en'    => 'Inglés',
                'fr'    => 'Francés',
                'fa'    => 'Persa',
                'he'    => 'Hebreo',
                'hi_IN' => 'Hindi',
                'it'    => 'Italiano',
                'ja'    => 'Japonés',
                'nl'    => 'Holandés',
                'pl'    => 'Polaco',
                'pt_BR' => 'Portugués Brasileño',
                'ru'    => 'Ruso',
                'sin'   => 'Cingalés',
                'tr'    => 'Turco',
                'uk'    => 'Ucraniano',
                'zh_CN' => 'Chino',
            ],
        ],

        'customer' => [
            'customer-groups' => [
                'guest'     => 'Invitado',
                'general'   => 'General',
                'wholesale' => 'Mayorista',
            ],
        ],

        'inventory' => [
            'inventory-sources' => [
                'name' => 'Default',
            ],
        ],

        'shop' => [
            'theme-customizations' => [
                'image-carousel' => [
                    'name'  => 'Image Carousel',

                    'sliders' => [
                        'title' => 'Get Ready For New Collection',
                    ],
                ],

                'offer-information' => [
                    'name' => 'Offer Information',

                    'content' => [
                        'title' => 'Get UPTO 40% OFF on your 1st order SHOP NOW',
                    ],
                ],

                'categories-collections' => [
                    'name' => 'Categories Collections',
                ],

                'new-products' => [
                    'name' => 'New Products',

                    'options' => [
                        'title' => 'New Products',
                    ],
                ],

                'top-collections' => [
                    'name' => 'Top Collections',

                    'content' => [
                        'sub-title-1' => 'Our Collections',
                        'sub-title-2' => 'Our Collections',
                        'sub-title-3' => 'Our Collections',
                        'sub-title-4' => 'Our Collections',
                        'sub-title-5' => 'Our Collections',
                        'sub-title-6' => 'Our Collections',
                        'title'       => 'The game with our new additions!',
                    ],
                ],

                'bold-collections' => [
                    'name' => 'Bold Collections',

                    'content' => [
                        'btn-title'   => 'View All',
                        'description' => 'Introducing Our New Bold Collections! Elevate your style with daring designs and vibrant statements. Explore striking patterns and bold colors that redefine your wardrobe. Get ready to embrace the extraordinary!',
                        'title'       => 'Get Ready for our new Bold Collections!',
                    ],
                ],

                'featured-collections' => [
                    'name' => 'Featured Collections',

                    'options' => [
                        'title' => 'Featured Products',
                    ],
                ],

                'game-container' => [
                    'name' => 'Game Container',

                    'content' => [
                        'sub-title-1' => 'Our Collections',
                        'sub-title-2' => 'Our Collections',
                        'title'       => 'The game with our new additions!',
                    ],
                ],

                'all-products' => [
                    'name' => 'All Products',

                    'options' => [
                        'title' => 'All Products',
                    ],
                ],

                'footer-links' => [
                    'name' => 'Footer Links',

                    'options' => [
                        'about-us'         => 'About Us',
                        'contact-us'       => 'Contact Us',
                        'customer-service' => 'Customer Service',
                        'privacy-policy'   => 'Privacy Policy',
                        'payment-policy'   => 'Payment Policy',
                        'return-policy'    => 'Return Policy',
                        'refund-policy'    => 'Refund Policy',
                        'shipping-policy'  => 'Shipping Policy',
                        'terms-of-use'     => 'Terms of Use',
                        'terms-conditions' => 'Terms & Conditions',
                        'whats-new'        => 'What\'s New',
                    ],
                ],

                'services-content' => [
                    'name'  => 'Dienstleistungen Inhalt',

                    'title' => [
                        'free-shipping'   => 'Kostenloser Versand',
                        'product-replace' => 'Produkt ersetzen',
                        'emi-available'   => 'EMI verfügbar',
                        'time-support'    => '24/7 Support',
                    ],

                    'description' => [
                        'free-shipping-info'   => 'Kostenloser Versand bei allen Bestellungen',
                        'product-replace-info' => 'Einfacher Produktersatz verfügbar!',
                        'emi-available-info'   => 'Keine Kosten EMI auf allen gängigen Kreditkarten verfügbar',
                        'time-support-info'    => 'Dedizierter 24/7 Support per Chat und E-Mail',
                    ],
                ],
            ],
        ],

        'user' => [
            'users' => [
                'name' => 'Example',
            ],

            'roles' => [
                'description' => 'This role users will have all the access',
                'name'        => 'Administrator',
            ],
        ],
    ],

    'installer' => [
        'index' => [
            'start' => [
                'locale'        => 'Local',
                'main'          => 'Comienzo',
                'select-locale' => 'Seleccionar Local',
                'title'         => 'Tu instalación de Bagisto',
                'welcome-title' => 'Bienvenido a Bagisto 2.0.',
            ],

            'server-requirements' => [
                'calendar'    => 'Calendario',
                'ctype'       => 'cType',
                'curl'        => 'cURL',
                'dom'         => 'DOM',
                'fileinfo'    => 'Información del archivo',
                'filter'      => 'Filtro',
                'gd'          => 'GD',
                'hash'        => 'Hash',
                'intl'        => 'Intl',
                'json'        => 'JSON',
                'mbstring'    => 'mbstring',
                'openssl'     => 'OpenSSL',
                'php'         => 'PHP',
                'php-version' => '8.1 o superior',
                'pcre'        => 'pcre',
                'pdo'         => 'pdo',
                'session'     => 'Sesión',
                'title'       => 'Requisitos del servidor',
                'tokenizer'   => 'Tokenizer',
                'xml'         => 'XML',
            ],

            'environment-configuration' => [
                'allowed-locales'     => 'Idiomas permitidos',
                'allowed-currencies'  => 'Monedas permitidas',
                'application-name'    => 'Nombre de la aplicación',
                'bagisto'             => 'Bagisto',
                'chinese-yuan'        => 'Yuan chino (CNY)',
                'dirham'              => 'Dirham (AED)',
                'default-url'         => 'URL predeterminada',
                'default-url-link'    => 'https://localhost',
                'default-currency'    => 'Moneda predeterminada',
                'default-timezone'    => 'Zona horaria predeterminada',
                'default-locale'      => 'Configuración regional predeterminada',
                'database-connection' => 'Conexión de base de datos',
                'database-hostname'   => 'Nombre de host de la base de datos',
                'database-port'       => 'Puerto de la base de datos',
                'database-name'       => 'Nombre de la base de datos',
                'database-username'   => 'Nombre de usuario de la base de datos',
                'database-prefix'     => 'Prefijo de la base de datos',
                'database-password'   => 'Contraseña de la base de datos',
                'euro'                => 'Euro (EUR)',
                'iranian'             => 'Rial iraní (IRR)',
                'israeli'             => 'Shekel israelí (AFN)',
                'japanese-yen'        => 'Yen japonés (JPY)',
                'mysql'               => 'Mysql',
                'pgsql'               => 'pgSQL',
                'pound'               => 'Libra esterlina (GBP)',
                'rupee'               => 'Rupia india (INR)',
                'russian-ruble'       => 'Rublo ruso (RUB)',
                'sqlsrv'              => 'SQLSRV',
                'saudi'               => 'Riyal saudí (SAR)',
                'title'               => 'Configuración del entorno',
                'turkish-lira'        => 'Lira turca (TRY)',
                'usd'                 => 'Dólar estadounidense (USD)',
                'ukrainian-hryvnia'   => 'Grivna ucraniana (UAH)',
                'warning-message'     => '¡Cuidado! La configuración de los idiomas del sistema predeterminados y la moneda predeterminada son permanentes y no se pueden cambiar nunca más.',
            ],

            'ready-for-installation' => [
                'create-databsae-table'   => 'Crear la tabla de la base de datos',
                'install'                 => 'Instalación',
                'install-info'            => 'Bagisto para instalación',
                'install-info-button'     => 'Haz clic en el botón de abajo para',
                'populate-database-table' => 'Rellenar las tablas de la base de datos',
                'start-installation'      => 'Iniciar instalación',
                'title'                   => 'Listo para la instalación',
            ],

            'installation-processing' => [
                'bagisto'          => 'Instalación de Bagisto',
                'bagisto-info'     => 'Creando las tablas de la base de datos, esto puede tomar algunos momentos',
                'title'            => 'Instalación',
            ],

            'create-administrator' => [
                'admin'            => 'Administrador',
                'bagisto'          => 'Bagisto',
                'confirm-password' => 'Confirmar contraseña',
                'email'            => 'Correo electrónico',
                'email-address'    => 'admin@example.com',
                'password'         => 'Contraseña',
                'title'            => 'Crear administrador',
            ],

            'installation-completed' => [
                'admin-panel'                => 'Panel de administración',
                'bagisto-forums'             => 'Foro de Bagisto',
                'customer-panel'             => 'Panel de clientes',
                'explore-bagisto-extensions' => 'Explorar extensiones de Bagisto',
                'title'                      => 'Instalación completada',
                'title-info'                 => 'Bagisto se ha instalado correctamente en su sistema.',
            ],

            'arabic'                   => 'Árabe',
            'bengali'                  => 'Bengalí',
            'bagisto-logo'             => 'Logo de Bagisto',
            'back'                     => 'Atrás',
            'bagisto-info'             => 'Un proyecto comunitario por',
            'bagisto'                  => 'Bagisto',
            'chinese'                  => 'Chino',
            'continue'                 => 'Continuar',
            'dutch'                    => 'Holandés',
            'english'                  => 'Inglés',
            'french'                   => 'Francés',
            'german'                   => 'Alemán',
            'hebrew'                   => 'Hebreo',
            'hindi'                    => 'Hindi',
            'installation-title'       => 'Bienvenido a la Instalación',
            'installation-info'        => '¡Nos alegra verte aquí!',
            'installation-description' => 'La instalación de Bagisto generalmente implica varios pasos. Aquí hay un resumen general del proceso de instalación para Bagisto:',
            'italian'                  => 'Italiano',
            'japanese'                 => 'Japonés',
            'persian'                  => 'Persa',
            'polish'                   => 'Polaco',
            'portuguese'               => 'Portugués brasileño',
            'russian'                  => 'Ruso',
            'spanish'                  => 'Español',
            'sinhala'                  => 'Cingalés',
            'skip'                     => 'Saltar',
            'save-configuration'       => 'Guardar configuración',
            'title'                    => 'Instalador de Bagisto',
            'turkish'                  => 'Turco',
            'ukrainian'                => 'Ucraniano',
            'webkul'                   => 'Webkul',
        ],
    ],
];
