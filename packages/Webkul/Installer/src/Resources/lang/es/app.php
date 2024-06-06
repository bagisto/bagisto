<?php

return [
    'seeders' => [
        'attribute' => [
            'attribute-families' => [
                'default' => 'Predeterminado',
            ],

            'attribute-groups' => [
                'description'      => 'Descripción',
                'general'          => 'General',
                'inventories'      => 'Inventarios',
                'meta-description' => 'Meta Descripción',
                'price'            => 'Precio',
                'settings'         => 'Configuraciones',
                'shipping'         => 'Envío',
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
                'manage-stock'         => 'Gestionar Stock',
                'meta-description'     => 'Meta Descripción',
                'meta-keywords'        => 'Meta Palabras Clave',
                'meta-title'           => 'Meta Título',
                'name'                 => 'Nombre',
                'new'                  => 'Nuevo',
                'price'                => 'Precio',
                'product-number'       => 'Número de Producto',
                'short-description'    => 'Descripción Corta',
                'size'                 => 'Tamaño',
                'sku'                  => 'SKU',
                'special-price'        => 'Precio Especial',
                'special-price-from'   => 'Precio Especial Desde',
                'special-price-to'     => 'Precio Especial Hasta',
                'status'               => 'Estado',
                'tax-category'         => 'Categoría de Impuestos',
                'url-key'              => 'Clave de URL',
                'visible-individually' => 'Visible Individualmente',
                'weight'               => 'Peso',
                'width'                => 'Ancho',
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

                'contact-us' => [
                    'content' => 'Contenido de la Página Contáctenos',
                    'title'   => 'Contáctenos',
                ],

                'customer-service' => [
                    'content' => 'Contenido de la Página Servicio al Cliente',
                    'title'   => 'Servicio al Cliente',
                ],

                'payment-policy' => [
                    'content' => 'Contenido de la Página Política de Pago',
                    'title'   => 'Política de Pago',
                ],

                'privacy-policy' => [
                    'content' => 'Contenido de la Página Política de Privacidad',
                    'title'   => 'Política de Privacidad',
                ],

                'refund-policy' => [
                    'content' => 'Contenido de la Página Política de Devolución',
                    'title'   => 'Política de Devolución',
                ],

                'return-policy' => [
                    'content' => 'Contenido de la Página Política de Retorno',
                    'title'   => 'Política de Retorno',
                ],

                'shipping-policy' => [
                    'content' => 'Contenido de la Página Política de Envío',
                    'title'   => 'Política de Envío',
                ],

                'terms-conditions' => [
                    'content' => 'Contenido de la Página Términos y Condiciones',
                    'title'   => 'Términos y Condiciones',
                ],

                'terms-of-use' => [
                    'content' => 'Contenido de la Página Términos de Uso',
                    'title'   => 'Términos de Uso',
                ],

                'whats-new' => [
                    'content' => 'Contenido de la Página Novedades',
                    'title'   => 'Novedades',
                ],
            ],
        ],

        'core' => [
            'channels' => [
                'name'             => 'Predeterminado',
                'meta-title'       => 'Tienda de Demostración',
                'meta-keywords'    => 'Palabras Clave de Meta de la Tienda de Demostración',
                'meta-description' => 'Descripción de Meta de la Tienda de Demostración',
            ],

            'currencies' => [
                'AED' => 'Dirham de los Emiratos Árabes Unidos',
                'ARS' => 'Peso Argentino',
                'AUD' => 'Dólar Australiano',
                'BDT' => 'Taka de Bangladesh',
                'BRL' => 'Real Brasileño',
                'CAD' => 'Dólar Canadiense',
                'CHF' => 'Franco Suizo',
                'CLP' => 'Peso Chileno',
                'CNY' => 'Yuan Chino',
                'COP' => 'Peso Colombiano',
                'CZK' => 'Corona Checa',
                'DKK' => 'Corona Danesa',
                'DZD' => 'Dinar Argelino',
                'EGP' => 'Libra Egipcia',
                'EUR' => 'Euro',
                'FJD' => 'Dólar Fiyiano',
                'GBP' => 'Libra Esterlina',
                'HKD' => 'Dólar de Hong Kong',
                'HUF' => 'Forinto Húngaro',
                'IDR' => 'Rupia Indonesia',
                'ILS' => 'Nuevo Shekel Israelí',
                'INR' => 'Rupia India',
                'JOD' => 'Dinar Jordano',
                'JPY' => 'Yen Japonés',
                'KRW' => 'Won Surcoreano',
                'KWD' => 'Dinar Kuwaití',
                'KZT' => 'Tenge Kazajo',
                'LBP' => 'Libra Libanesa',
                'LKR' => 'Rupia de Sri Lanka',
                'LYD' => 'Dinar Libio',
                'MAD' => 'Dirham Marroquí',
                'MUR' => 'Rupia Mauriciana',
                'MXN' => 'Peso Mexicano',
                'MYR' => 'Ringgit Malayo',
                'NGN' => 'Naira Nigeriano',
                'NOK' => 'Corona Noruega',
                'NPR' => 'Rupia Nepalí',
                'NZD' => 'Dólar Neozelandés',
                'OMR' => 'Rial Omaní',
                'PAB' => 'Balboa Panameño',
                'PEN' => 'Nuevo Sol Peruano',
                'PHP' => 'Peso Filipino',
                'PKR' => 'Rupia Pakistaní',
                'PLN' => 'Zloty Polaco',
                'PYG' => 'Guaraní Paraguayo',
                'QAR' => 'Rial de Qatar',
                'RON' => 'Leu Rumano',
                'RUB' => 'Rublo Ruso',
                'SAR' => 'Riyal Saudí',
                'SEK' => 'Corona Sueca',
                'SGD' => 'Dólar de Singapur',
                'THB' => 'Baht Tailandés',
                'TND' => 'Dinar Tunecino',
                'TRY' => 'Lira Turca',
                'TWD' => 'Nuevo Dólar de Taiwán',
                'UAH' => 'Hryvnia Ucraniano',
                'USD' => 'Dólar Estadounidense',
                'UZS' => 'Som Uzbeko',
                'VEF' => 'Bolívar Venezolano',
                'VND' => 'Dong Vietnamita',
                'XAF' => 'Franco CFA BEAC',
                'XOF' => 'Franco CFA BCEAO',
                'ZAR' => 'Rand Sudafricano',
                'ZMW' => 'Kwacha Zambiano',
            ],

            'locales' => [
                'ar'    => 'Árabe',
                'bn'    => 'Bengalí',
                'de'    => 'Alemán',
                'en'    => 'Inglés',
                'es'    => 'Español',
                'fa'    => 'Persa',
                'fr'    => 'Francés',
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
                'general'   => 'General',
                'guest'     => 'Invitado',
                'wholesale' => 'Mayorista',
            ],
        ],

        'inventory' => [
            'inventory-sources' => [
                'name' => 'Predeterminado',
            ],
        ],

        'shop' => [
            'theme-customizations' => [
                'all-products' => [
                    'name' => 'Todos los Productos',

                    'options' => [
                        'title' => 'Todos los Productos',
                    ],
                ],

                'bold-collections' => [
                    'content' => [
                        'btn-title'   => 'Ver Colecciones',
                        'description' => '¡Presentamos Nuestras Nuevas Colecciones Audaces! Eleva tu estilo con diseños atrevidos y declaraciones vibrantes. Explora patrones llamativos y colores audaces que redefinen tu armario. ¡Prepárate para abrazar lo extraordinario!',
                        'title'       => '¡Prepárate para nuestras nuevas Colecciones Audaces!',
                    ],

                    'name' => 'Colecciones Audaces',
                ],

                'categories-collections' => [
                    'name' => 'Colecciones de Categorías',
                ],

                'featured-collections' => [
                    'name' => 'Colecciones Destacadas',

                    'options' => [
                        'title' => 'Productos Destacados',
                    ],
                ],

                'footer-links' => [
                    'name' => 'Enlaces del Pie de Página',

                    'options' => [
                        'about-us'         => 'Acerca de Nosotros',
                        'contact-us'       => 'Contáctenos',
                        'customer-service' => 'Servicio al Cliente',
                        'payment-policy'   => 'Política de Pago',
                        'privacy-policy'   => 'Política de Privacidad',
                        'refund-policy'    => 'Política de Devolución',
                        'return-policy'    => 'Política de Retorno',
                        'shipping-policy'  => 'Política de Envío',
                        'terms-conditions' => 'Términos y Condiciones',
                        'terms-of-use'     => 'Términos de Uso',
                        'whats-new'        => 'Novedades',
                    ],
                ],

                'game-container' => [
                    'content' => [
                        'sub-title-1' => 'Nuestras Colecciones',
                        'sub-title-2' => 'Nuestras Colecciones',
                        'title'       => '¡El juego con nuestras nuevas adiciones!',
                    ],

                    'name' => 'Contenedor de Juegos',
                ],

                'image-carousel' => [
                    'name' => 'Carrusel de Imágenes',

                    'sliders' => [
                        'title' => 'Prepárate para la Nueva Colección',
                    ],
                ],

                'new-products' => [
                    'name' => 'Nuevos Productos',

                    'options' => [
                        'title' => 'Nuevos Productos',
                    ],
                ],

                'offer-information' => [
                    'content' => [
                        'title' => '¡Hasta un 40% DE DESCUENTO en tu primer pedido COMPRA AHORA!',
                    ],

                    'name' => 'Información de Oferta',
                ],

                'services-content' => [
                    'description' => [
                        'emi-available-info'   => 'EMI sin costo disponible en todas las tarjetas de crédito comunes',
                        'free-shipping-info'   => 'Envío gratuito en todos los pedidos',
                        'product-replace-info' => '¡Reemplazo de producto sencillo disponible!',
                        'time-support-info'    => 'Soporte dedicado 24/7 por chat y correo electrónico',
                    ],

                    'name' => 'Contenido de Servicios',

                    'title' => [
                        'emi-available'   => 'EMI disponible',
                        'free-shipping'   => 'Envío gratuito',
                        'product-replace' => 'Reemplazo de producto',
                        'time-support'    => 'Soporte 24/7',
                    ],
                ],

                'top-collections' => [
                    'content' => [
                        'sub-title-1' => 'Nuestras Colecciones',
                        'sub-title-2' => 'Nuestras Colecciones',
                        'sub-title-3' => 'Nuestras Colecciones',
                        'sub-title-4' => 'Nuestras Colecciones',
                        'sub-title-5' => 'Nuestras Colecciones',
                        'sub-title-6' => 'Nuestras Colecciones',
                        'title'       => '¡El juego con nuestras nuevas adiciones!',
                    ],

                    'name' => 'Mejores Colecciones',
                ],
            ],
        ],

        'user' => [
            'roles' => [
                'description' => 'Los usuarios con este rol tendrán acceso a todo',
                'name'        => 'Administrador',
            ],

            'users' => [
                'name' => 'Ejemplo',
            ],
        ],
    ],

    'installer' => [
        'index' => [
            'create-administrator' => [
                'admin'            => 'Administrador',
                'bagisto'          => 'Bagisto',
                'confirm-password' => 'Confirmar contraseña',
                'email-address'    => 'admin@example.com',
                'email'            => 'Correo electrónico',
                'password'         => 'Contraseña',
                'title'            => 'Crear administrador',
            ],

            'environment-configuration' => [
                'algerian-dinar'              => 'Dinar Argelino (DZD)',
                'allowed-currencies'          => 'Monedas Permitidas',
                'allowed-locales'             => 'Idiomas Permitidos',
                'application-name'            => 'Nombre de la Aplicación',
                'argentine-peso'              => 'Peso Argentino (ARS)',
                'australian-dollar'           => 'Dólar Australiano (AUD)',
                'bagisto'                     => 'Bagisto',
                'bangladeshi-taka'            => 'Taka de Bangladesh (BDT)',
                'brazilian-real'              => 'Real Brasileño (BRL)',
                'british-pound-sterling'      => 'Libra Esterlina Británica (GBP)',
                'canadian-dollar'             => 'Dólar Canadiense (CAD)',
                'cfa-franc-bceao'             => 'Franco CFA BCEAO (XOF)',
                'cfa-franc-beac'              => 'Franco CFA BEAC (XAF)',
                'chilean-peso'                => 'Peso Chileno (CLP)',
                'chinese-yuan'                => 'Yuan Chino (CNY)',
                'colombian-peso'              => 'Peso Colombiano (COP)',
                'czech-koruna'                => 'Corona Checa (CZK)',
                'danish-krone'                => 'Corona Danesa (DKK)',
                'database-connection'         => 'Conexión de Base de Datos',
                'database-hostname'           => 'Nombre de Host de la Base de Datos',
                'database-name'               => 'Nombre de la Base de Datos',
                'database-password'           => 'Contraseña de la Base de Datos',
                'database-port'               => 'Puerto de la Base de Datos',
                'database-prefix'             => 'Prefijo de la Base de Datos',
                'database-username'           => 'Nombre de Usuario de la Base de Datos',
                'default-currency'            => 'Moneda Predeterminada',
                'default-locale'              => 'Idioma Predeterminado',
                'default-timezone'            => 'Zona Horaria Predeterminada',
                'default-url'                 => 'URL Predeterminada',
                'default-url-link'            => 'https://localhost',
                'egyptian-pound'              => 'Libra Egipcia (EGP)',
                'euro'                        => 'Euro (EUR)',
                'fijian-dollar'               => 'Dólar Fiyiano (FJD)',
                'hong-kong-dollar'            => 'Dólar de Hong Kong (HKD)',
                'hungarian-forint'            => 'Forint Húngaro (HUF)',
                'indian-rupee'                => 'Rupia India (INR)',
                'indonesian-rupiah'           => 'Rupia Indonesia (IDR)',
                'israeli-new-shekel'          => 'Nuevo Shekel Israelí (ILS)',
                'japanese-yen'                => 'Yen Japonés (JPY)',
                'jordanian-dinar'             => 'Dinar Jordano (JOD)',
                'kazakhstani-tenge'           => 'Tenge Kazajo (KZT)',
                'kuwaiti-dinar'               => 'Dinar Kuwaití (KWD)',
                'lebanese-pound'              => 'Libra Libanesa (LBP)',
                'libyan-dinar'                => 'Dinar Libio (LYD)',
                'malaysian-ringgit'           => 'Ringgit Malayo (MYR)',
                'mauritian-rupee'             => 'Rupia Mauriciana (MUR)',
                'mexican-peso'                => 'Peso Mexicano (MXN)',
                'moroccan-dirham'             => 'Dirham Marroquí (MAD)',
                'mysql'                       => 'Mysql',
                'nepalese-rupee'              => 'Rupia Nepalí (NPR)',
                'new-taiwan-dollar'           => 'Dólar de Taiwán (TWD)',
                'new-zealand-dollar'          => 'Dólar de Nueva Zelanda (NZD)',
                'nigerian-naira'              => 'Naira Nigeriano (NGN)',
                'norwegian-krone'             => 'Corona Noruega (NOK)',
                'omani-rial'                  => 'Rial Omaní (OMR)',
                'pakistani-rupee'             => 'Rupia Pakistaní (PKR)',
                'panamanian-balboa'           => 'Balboa Panameño (PAB)',
                'paraguayan-guarani'          => 'Guaraní Paraguayo (PYG)',
                'peruvian-nuevo-sol'          => 'Nuevo Sol Peruano (PEN)',
                'pgsql'                       => 'pgSQL',
                'philippine-peso'             => 'Peso Filipino (PHP)',
                'polish-zloty'                => 'Zloty Polaco (PLN)',
                'qatari-rial'                 => 'Rial de Qatar (QAR)',
                'romanian-leu'                => 'Leu Rumano (RON)',
                'russian-ruble'               => 'Rublo Ruso (RUB)',
                'saudi-riyal'                 => 'Riyal Saudí (SAR)',
                'select-timezone'             => 'Seleccionar Zona Horaria',
                'singapore-dollar'            => 'Dólar de Singapur (SGD)',
                'south-african-rand'          => 'Rand Sudafricano (ZAR)',
                'south-korean-won'            => 'Won Surcoreano (KRW)',
                'sqlsrv'                      => 'SQLSRV',
                'sri-lankan-rupee'            => 'Rupia de Sri Lanka (LKR)',
                'swedish-krona'               => 'Corona Sueca (SEK)',
                'swiss-franc'                 => 'Franco Suizo (CHF)',
                'thai-baht'                   => 'Baht Tailandés (THB)',
                'title'                       => 'Configuración de la Tienda',
                'tunisian-dinar'              => 'Dinar Tunecino (TND)',
                'turkish-lira'                => 'Lira Turca (TRY)',
                'ukrainian-hryvnia'           => 'Grivna Ucraniana (UAH)',
                'united-arab-emirates-dirham' => 'Dirham de los Emiratos Árabes Unidos (AED)',
                'united-states-dollar'        => 'Dólar Estadounidense (USD)',
                'uzbekistani-som'             => 'Som Uzbeko (UZS)',
                'venezuelan-bolívar'          => 'Bolívar Venezolano (VEF)',
                'vietnamese-dong'             => 'Dong Vietnamita (VND)',
                'warning-message'             => '¡Cuidado! La configuración de los idiomas y la moneda predeterminada del sistema son permanentes y no se pueden cambiar.',
                'zambian-kwach'               => 'Kwacha de Zambia (ZMW)',
            ],

            'installation-processing' => [
                'bagisto'          => 'Instalación de Bagisto',
                'bagisto-info'     => 'Creando las tablas de la base de datos, esto puede tomar algunos momentos',
                'title'            => 'Instalación',
            ],

            'installation-completed' => [
                'admin-panel'                => 'Panel de administración',
                'bagisto-forums'             => 'Foro de Bagisto',
                'customer-panel'             => 'Panel de clientes',
                'explore-bagisto-extensions' => 'Explorar extensiones de Bagisto',
                'title'                      => 'Instalación completada',
                'title-info'                 => 'Bagisto se ha instalado correctamente en su sistema.',
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
                'pcre'        => 'pcre',
                'pdo'         => 'pdo',
                'php'         => 'PHP',
                'php-version' => '8.1 o superior',
                'session'     => 'Sesión',
                'title'       => 'Requisitos del servidor',
                'tokenizer'   => 'Tokenizer',
                'xml'         => 'XML',
            ],

            'arabic'                   => 'Árabe',
            'back'                     => 'Atrás',
            'bagisto'                  => 'Bagisto',
            'bagisto-info'             => 'Un proyecto comunitario por',
            'bagisto-logo'             => 'Logo de Bagisto',
            'bengali'                  => 'Bengalí',
            'chinese'                  => 'Chino',
            'continue'                 => 'Continuar',
            'dutch'                    => 'Holandés',
            'english'                  => 'Inglés',
            'french'                   => 'Francés',
            'german'                   => 'Alemán',
            'hebrew'                   => 'Hebreo',
            'hindi'                    => 'Hindi',
            'installation-description' => 'La instalación de Bagisto generalmente implica varios pasos. Aquí hay un resumen  general del proceso de instalación para Bagisto:',
            'installation-info'        => '¡Nos alegra verte aquí!',
            'installation-title'       => 'Bienvenido a la Instalación',
            'italian'                  => 'Italiano',
            'japanese'                 => 'Japonés',
            'persian'                  => 'Persa',
            'polish'                   => 'Polaco',
            'portuguese'               => 'Portugués brasileño',
            'russian'                  => 'Ruso',
            'sinhala'                  => 'Cingalés',
            'spanish'                  => 'Español',
            'title'                    => 'Instalador de Bagisto',
            'turkish'                  => 'Turco',
            'ukrainian'                => 'Ucraniano',
            'webkul'                   => 'Webkul',
        ],
    ],
];
