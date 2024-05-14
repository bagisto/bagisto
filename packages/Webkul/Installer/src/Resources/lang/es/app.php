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
                'AED' => 'Dirham',
                'AFN' => 'Shekel Israelí',
                'CNY' => 'Yuan Chino',
                'EUR' => 'EURO',
                'GBP' => 'Libra Esterlina',
                'INR' => 'Rupia India',
                'IRR' => 'Rial Iraní',
                'JPY' => 'Yen Japonés',
                'RUB' => 'Rublo Ruso',
                'SAR' => 'Riyal Saudí',
                'TRY' => 'Lira Turca',
                'UAH' => 'Grivna Ucraniana',
                'USD' => 'Dólar Estadounidense',
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
                        'btn-title'   => 'Ver Todo',
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
                'allowed-currencies'  => 'Monedas permitidas',
                'allowed-locales'     => 'Idiomas permitidos',
                'application-name'    => 'Nombre de la aplicación',
                'bagisto'             => 'Bagisto',
                'chinese-yuan'        => 'Yuan chino (CNY)',
                'database-connection' => 'Conexión de base de datos',
                'database-hostname'   => 'Nombre de host de la base de datos',
                'database-name'       => 'Nombre de la base de datos',
                'database-password'   => 'Contraseña de la base de datos',
                'database-port'       => 'Puerto de la base de datos',
                'database-prefix'     => 'Prefijo de la base de datos',
                'database-username'   => 'Nombre de usuario de la base de datos',
                'default-currency'    => 'Moneda predeterminada',
                'default-locale'      => 'Configuración regional predeterminada',
                'default-timezone'    => 'Zona horaria predeterminada',
                'default-url'         => 'URL predeterminada',
                'default-url-link'    => 'https://localhost',
                'dirham'              => 'Dirham (AED)',
                'euro'                => 'Euro (EUR)',
                'iranian'             => 'Rial iraní (IRR)',
                'israeli'             => 'Shekel israelí (AFN)',
                'japanese-yen'        => 'Yen japonés (JPY)',
                'mysql'               => 'Mysql',
                'pgsql'               => 'pgSQL',
                'pound'               => 'Libra esterlina (GBP)',
                'rupee'               => 'Rupia india (INR)',
                'russian-ruble'       => 'Rublo ruso (RUB)',
                'saudi'               => 'Riyal saudí (SAR)',
                'select-timezone'     => 'Selecciona la zona horaria',
                'sqlsrv'              => 'SQLSRV',
                'title'               => 'Configuración del entorno',
                'turkish-lira'        => 'Lira turca (TRY)',
                'ukrainian-hryvnia'   => 'Grivna ucraniana (UAH)',
                'usd'                 => 'Dólar estadounidense (USD)',
                'warning-message'     => '¡Cuidado! La configuración de los idiomas del sistema predeterminados y la moneda predeterminada son permanentes y no se pueden cambiar nunca más.',
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
