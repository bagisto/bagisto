<?php

return [
    'seeders' => [
        'attribute' => [
            'attribute-families' => [
                'default' => 'Predeterminado',
            ],

            'attribute-groups' => [
                'description' => 'Descripción',
                'general' => 'General',
                'inventories' => 'Inventarios',
                'meta-description' => 'Meta Descripción',
                'price' => 'Precio',
                'rma' => 'RMA',
                'settings' => 'Configuraciones',
                'shipping' => 'Envío',
            ],

            'attributes' => [
                'allow-rma' => 'Permitir RMA',
                'brand' => 'Marca',
                'color' => 'Color',
                'cost' => 'Costo',
                'description' => 'Descripción',
                'featured' => 'Destacado',
                'guest-checkout' => 'Compra de Invitado',
                'height' => 'Altura',
                'length' => 'Longitud',
                'manage-stock' => 'Gestionar Stock',
                'meta-description' => 'Meta Descripción',
                'meta-keywords' => 'Meta Palabras Clave',
                'meta-title' => 'Meta Título',
                'name' => 'Nombre',
                'new' => 'Nuevo',
                'price' => 'Precio',
                'product-number' => 'Número de Producto',
                'rma-rules' => 'Reglas de RMA',
                'short-description' => 'Descripción Corta',
                'size' => 'Tamaño',
                'sku' => 'SKU',
                'special-price' => 'Precio Especial',
                'special-price-from' => 'Precio Especial Desde',
                'special-price-to' => 'Precio Especial Hasta',
                'status' => 'Estado',
                'tax-category' => 'Categoría de Impuestos',
                'url-key' => 'Clave de URL',
                'visible-individually' => 'Visible Individualmente',
                'weight' => 'Peso',
                'width' => 'Ancho',
            ],

            'attribute-options' => [
                'black' => 'Negro',
                'green' => 'Verde',
                'l' => 'L',
                'm' => 'M',
                'red' => 'Rojo',
                's' => 'S',
                'white' => 'Blanco',
                'xl' => 'XL',
                'yellow' => 'Amarillo',
            ],
        ],

        'category' => [
            'categories' => [
                'description' => 'Descripción de la Categoría Raíz',
                'name' => 'Raíz',
            ],
        ],

        'cms' => [
            'pages' => [
                'about-us' => [
                    'content' => 'Contenido de la Página Acerca de Nosotros',
                    'title' => 'Acerca de Nosotros',
                ],

                'contact-us' => [
                    'content' => 'Contenido de la Página Contáctenos',
                    'title' => 'Contáctenos',
                ],

                'customer-service' => [
                    'content' => 'Contenido de la Página Servicio al Cliente',
                    'title' => 'Servicio al Cliente',
                ],

                'payment-policy' => [
                    'content' => 'Contenido de la Página Política de Pago',
                    'title' => 'Política de Pago',
                ],

                'privacy-policy' => [
                    'content' => 'Contenido de la Página Política de Privacidad',
                    'title' => 'Política de Privacidad',
                ],

                'refund-policy' => [
                    'content' => 'Contenido de la Página Política de Devolución',
                    'title' => 'Política de Devolución',
                ],

                'return-policy' => [
                    'content' => 'Contenido de la Página Política de Retorno',
                    'title' => 'Política de Retorno',
                ],

                'shipping-policy' => [
                    'content' => 'Contenido de la Página Política de Envío',
                    'title' => 'Política de Envío',
                ],

                'terms-conditions' => [
                    'content' => 'Contenido de la Página Términos y Condiciones',
                    'title' => 'Términos y Condiciones',
                ],

                'terms-of-use' => [
                    'content' => 'Contenido de la Página Términos de Uso',
                    'title' => 'Términos de Uso',
                ],

                'whats-new' => [
                    'content' => 'Contenido de la Página Novedades',
                    'title' => 'Novedades',
                ],
            ],
        ],

        'core' => [
            'channels' => [
                'meta-description' => 'Descripción de Meta de la Tienda de Demostración',
                'meta-keywords' => 'Palabras Clave de Meta de la Tienda de Demostración',
                'meta-title' => 'Tienda de Demostración',
                'name' => 'Predeterminado',
            ],

            'currencies' => [
                'AED' => 'Dirham de los Emiratos Árabes Unidos',
                'ARS' => 'Peso Argentino',
                'AUD' => 'Dólar Australiano',
                'BDT' => 'Taka de Bangladesh',
                'BHD' => 'Dinar de Bahreiní',
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
                'ar' => 'Árabe',
                'bn' => 'Bengalí',
                'ca' => 'Catalán',
                'de' => 'Alemán',
                'en' => 'Inglés',
                'es' => 'Español',
                'fa' => 'Persa',
                'fr' => 'Francés',
                'he' => 'Hebreo',
                'hi_IN' => 'Hindi',
                'id' => 'Indonesio',
                'it' => 'Italiano',
                'ja' => 'Japonés',
                'nl' => 'Holandés',
                'pl' => 'Polaco',
                'pt_BR' => 'Portugués Brasileño',
                'ru' => 'Ruso',
                'sin' => 'Cingalés',
                'tr' => 'Turco',
                'uk' => 'Ucraniano',
                'zh_CN' => 'Chino',
            ],
        ],

        'customer' => [
            'customer-groups' => [
                'general' => 'General',
                'guest' => 'Invitado',
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
                        'btn-title' => 'Ver Colecciones',
                        'description' => '¡Presentamos Nuestras Nuevas Colecciones Audaces! Eleva tu estilo con diseños atrevidos y declaraciones vibrantes. Explora patrones llamativos y colores audaces que redefinen tu armario. ¡Prepárate para abrazar lo extraordinario!',
                        'title' => '¡Prepárate para nuestras nuevas Colecciones Audaces!',
                    ],

                    'name' => 'Colecciones Audaces',
                ],

                'bold-collections-2' => [
                    'content' => [
                        'btn-title' => 'Ver Colecciones',
                        'description' => 'Nuestras Colecciones Audaces están aquí para redefinir tu guardarropa con diseños atrevidos y colores vibrantes e impactantes. Desde patrones audaces hasta tonos poderosos, esta es tu oportunidad de romper con lo ordinario y entrar en lo extraordinario.',
                        'title' => '¡Libera tu Audacia con Nuestra Nueva Colección!',
                    ],

                    'name' => 'Colecciones Audaces',
                ],

                'booking-products' => [
                    'name' => 'Productos de Reserva',

                    'options' => [
                        'title' => 'Reservar Entradas',
                    ],
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
                        'about-us' => 'Acerca de Nosotros',
                        'contact-us' => 'Contáctenos',
                        'customer-service' => 'Servicio al Cliente',
                        'payment-policy' => 'Política de Pago',
                        'privacy-policy' => 'Política de Privacidad',
                        'refund-policy' => 'Política de Devolución',
                        'return-policy' => 'Política de Retorno',
                        'shipping-policy' => 'Política de Envío',
                        'terms-conditions' => 'Términos y Condiciones',
                        'terms-of-use' => 'Términos de Uso',
                        'whats-new' => 'Novedades',
                    ],
                ],

                'game-container' => [
                    'content' => [
                        'sub-title-1' => 'Nuestras Colecciones',
                        'sub-title-2' => 'Nuestras Colecciones',
                        'title' => '¡El juego con nuestras nuevas adiciones!',
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
                        'emi-available-info' => 'EMI sin costo disponible en todas las tarjetas de crédito comunes',
                        'free-shipping-info' => 'Envío gratuito en todos los pedidos',
                        'product-replace-info' => '¡Reemplazo de producto sencillo disponible!',
                        'time-support-info' => 'Soporte dedicado 24/7 por chat y correo electrónico',
                    ],

                    'name' => 'Contenido de Servicios',

                    'title' => [
                        'emi-available' => 'EMI disponible',
                        'free-shipping' => 'Envío gratuito',
                        'product-replace' => 'Reemplazo de producto',
                        'time-support' => 'Soporte 24/7',
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
                        'title' => '¡El juego con nuestras nuevas adiciones!',
                    ],

                    'name' => 'Mejores Colecciones',
                ],
            ],
        ],

        'user' => [
            'roles' => [
                'description' => 'Los usuarios con este rol tendrán acceso a todo',
                'name' => 'Administrador',
            ],

            'users' => [
                'name' => 'Ejemplo',
            ],
        ],

        'sample-categories' => [
            'category-translation' => [
                '2' => [
                    'description' => '<p>Hombres</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Hombres',
                    'slug' => 'mens',
                    'url-path' => 'men',
                ],

                '3' => [
                    'description' => '<p>Niños</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Niños',
                    'slug' => 'kids',
                    'url-path' => 'kids',
                ],

                '4' => [
                    'description' => '<p>Mujeres</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Mujeres',
                    'slug' => 'womens',
                    'url-path' => 'woman',
                ],

                '5' => [
                    'description' => '<p>Ropa Formal</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Ropa Formal',
                    'slug' => 'formal-wear-men',
                    'url-path' => 'men/formal-wear-men',
                ],

                '6' => [
                    'description' => '<p>Ropa Casual</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Ropa Casual',
                    'slug' => 'casual-wear-men',
                    'url-path' => 'men/casual-wear-men',
                ],

                '7' => [
                    'description' => '<p>Ropa Deportiva</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Ropa Deportiva',
                    'slug' => 'active-wear',
                    'url-path' => 'men/active-wear',
                ],

                '8' => [
                    'description' => '<p>Calzado</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Calzado',
                    'slug' => 'footwear',
                    'url-path' => 'men/footwear',
                ],

                '9' => [
                    'description' => '<p><span>Ropa Formal</span></p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Ropa Formal',
                    'slug' => 'formal-wear-female',
                    'url-path' => 'woman/formal-wear-female',
                ],

                '10' => [
                    'description' => '<p>Ropa Casual</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Ropa Casual',
                    'slug' => 'casual-wear-female',
                    'url-path' => 'woman/casual-wear-female',
                ],

                '11' => [
                    'description' => '<p>Ropa Deportiva</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Ropa Deportiva',
                    'slug' => 'active-wear-female',
                    'url-path' => 'woman/active-wear-female',
                ],

                '12' => [
                    'description' => '<p>Calzado</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Calzado',
                    'slug' => 'footwear-female',
                    'url-path' => 'woman/footwear-female',
                ],

                '13' => [
                    'description' => '<p>Ropa de Niñas</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => 'Ropa de Niñas',
                    'name' => 'Ropa de Niñas',
                    'slug' => 'girls-clothing',
                    'url-path' => 'kids/girls-clothing',
                ],

                '14' => [
                    'description' => '<p>Ropa de Niños</p>',
                    'meta-description' => 'Moda para Niños',
                    'meta-keywords' => '',
                    'meta-title' => 'Ropa de Niños',
                    'name' => 'Ropa de Niños',
                    'slug' => 'boys-clothing',
                    'url-path' => 'kids/boys-clothing',
                ],

                '15' => [
                    'description' => '<p>Calzado de Niñas</p>',
                    'meta-description' => 'Colección de Calzado de Moda para Niñas',
                    'meta-keywords' => '',
                    'meta-title' => 'Calzado de Niñas',
                    'name' => 'Calzado de Niñas',
                    'slug' => 'girls-footwear',
                    'url-path' => 'kids/girls-footwear',
                ],

                '16' => [
                    'description' => '<p>Calzado de Niños</p>',
                    'meta-description' => 'Colección de Calzado con Estilo para Niños',
                    'meta-keywords' => '',
                    'meta-title' => 'Calzado de Niños',
                    'name' => 'Calzado de Niños',
                    'slug' => 'boys-footwear',
                    'url-path' => 'kids/boys-footwear',
                ],

                '17' => [
                    'description' => '<p>Bienestar</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Bienestar',
                    'slug' => 'wellness',
                    'url-path' => 'wellness',
                ],

                '18' => [
                    'description' => '<p>Tutorial de Yoga Descargable</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Tutorial de Yoga Descargable',
                    'slug' => 'downloadable-yoga-tutorial',
                    'url-path' => 'wellness/downloadable-yoga-tutorial',
                ],

                '19' => [
                    'description' => '<p>Colección de Libros Electrónicos</p>',
                    'meta-description' => 'Colección de Libros Electrónicos',
                    'meta-keywords' => '',
                    'meta-title' => 'Colección de Libros Electrónicos',
                    'name' => 'Libros Electrónicos',
                    'slug' => 'e-books',
                    'url-path' => 'wellness/e-books',
                ],

                '20' => [
                    'description' => '<p>Pase de Cine</p>',
                    'meta-description' => 'Sumérgete en la magia de 10 películas cada mes sin cargos adicionales.',
                    'meta-keywords' => '',
                    'meta-title' => 'Pase Mensual de Cine CineXperience',
                    'name' => 'Pase de Cine',
                    'slug' => 'movie-pass',
                    'url-path' => 'wellness/movie-pass',
                ],

                '21' => [
                    'description' => '<p>Gestione y venda fácilmente sus productos basados en reservas con nuestro sistema de reservas integrado.</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Reservas',
                    'slug' => 'bookings',
                    'url-path' => '',
                ],

                '22' => [
                    'description' => '<p>La reserva de citas permite a los clientes programar franjas horarias para servicios o consultas con empresas o profesionales.</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Reserva de Citas',
                    'slug' => 'appointment-booking',
                    'url-path' => '',
                ],

                '23' => [
                    'description' => '<p>La reserva de eventos permite a individuos o grupos registrarse o reservar plazas para eventos públicos o privados.</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Reserva de Eventos',
                    'slug' => 'event-booking',
                    'url-path' => '',
                ],

                '24' => [
                    'description' => '<p>La reserva de salones comunitarios permite a individuos, organizaciones o grupos reservar espacios comunitarios para diversos eventos.</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Reservas de Salones Comunitarios',
                    'slug' => 'community-hall-bookings',
                    'url-path' => '',
                ],

                '25' => [
                    'description' => '<p>La reserva de mesas permite a los clientes reservar mesas en restaurantes, cafeterías o locales de comida con antelación.</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Reserva de Mesa',
                    'slug' => 'table-booking',
                    'url-path' => '',
                ],

                '26' => [
                    'description' => '<p>La reserva de alquiler facilita la reserva de artículos o propiedades para uso temporal.</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Reserva de Alquiler',
                    'slug' => 'rental-booking',
                    'url-path' => '',
                ],

                '27' => [
                    'description' => '<p>Explore lo último en electrónica de consumo, diseñada para mantenerle conectado, productivo y entretenido.</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Electrónica',
                    'slug' => 'electronics',
                    'url-path' => '',
                ],

                '28' => [
                    'description' => '<p>Descubra smartphones, cargadores, fundas y otros elementos esenciales para estar conectado en movimiento.</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Teléfonos Móviles y Accesorios',
                    'slug' => 'mobile-phones-accessories',
                    'url-path' => '',
                ],

                '29' => [
                    'description' => '<p>Encuentre portátiles potentes y tablets portátiles para trabajo, estudio y ocio.</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Portátiles y Tablets',
                    'slug' => 'laptops-tablets',
                    'url-path' => '',
                ],

                '30' => [
                    'description' => '<p>Compre auriculares, auriculares inalámbricos y altavoces para disfrutar de un sonido cristalino.</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Dispositivos de Audio',
                    'slug' => 'audio-devices',
                    'url-path' => '',
                ],

                '31' => [
                    'description' => '<p>Facilite la vida con iluminación inteligente, termostatos, sistemas de seguridad y más.</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Hogar Inteligente y Automatización',
                    'slug' => 'smart-home-automation',
                    'url-path' => '',
                ],

                '32' => [
                    'description' => '<p>Mejore su espacio vital con elementos esenciales funcionales y elegantes para el hogar y la cocina.</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Hogar',
                    'slug' => 'household',
                    'url-path' => '',
                ],

                '33' => [
                    'description' => '<p>Explore licuadoras, freidoras de aire, cafeteras y más para simplificar la preparación de comidas.</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Electrodomésticos de Cocina',
                    'slug' => 'kitchen-appliances',
                    'url-path' => '',
                ],

                '34' => [
                    'description' => '<p>Explore juegos de utensilios de cocina, utensilios, vajilla y servicio para sus necesidades culinarias.</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Utensilios de Cocina y Comedor',
                    'slug' => 'cookware-dining',
                    'url-path' => '',
                ],

                '35' => [
                    'description' => '<p>Añada comodidad y encanto con sofás, mesas, arte mural y acentos del hogar.</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Muebles y Decoración',
                    'slug' => 'furniture-decor',
                    'url-path' => '',
                ],

                '36' => [
                    'description' => '<p>Mantenga su espacio impecable con aspiradoras, sprays de limpieza, escobas y organizadores.</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Artículos de Limpieza',
                    'slug' => 'cleaning-supplies',
                    'url-path' => '',
                ],

                '37' => [
                    'description' => '<p>Encienda su imaginación u organice su espacio de trabajo con una amplia selección de libros y papelería.</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Libros y Papelería',
                    'slug' => 'books-stationery',
                    'url-path' => '',
                ],

                '38' => [
                    'description' => '<p>Sumérjase en novelas de éxito, biografías, autoayuda y más.</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Libros de Ficción y No Ficción',
                    'slug' => 'fiction-non-fiction-books',
                    'url-path' => '',
                ],

                '39' => [
                    'description' => '<p>Encuentre libros de texto, materiales de referencia y guías de estudio para todas las edades.</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Educativo y Académico',
                    'slug' => 'educational-academic',
                    'url-path' => '',
                ],

                '40' => [
                    'description' => '<p>Compre bolígrafos, cuadernos, agendas y elementos esenciales de oficina para la productividad.</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Material de Oficina',
                    'slug' => 'office-supplies',
                    'url-path' => '',
                ],

                '41' => [
                    'description' => '<p>Explore pinturas, pinceles, cuadernos de dibujo y kits de manualidades DIY para creativos.</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Materiales de Arte y Manualidades',
                    'slug' => 'art-craft-materials',
                    'url-path' => '',
                ],
            ],
        ],
    ],

    'installer' => [
        'middleware' => [
            'already-installed' => 'La aplicación ya está instalada.',
        ],

        'index' => [
            'create-administrator' => [
                'admin' => 'Administrador',
                'bagisto' => 'Bagisto',
                'confirm-password' => 'Confirmar Contraseña',
                'email' => 'Correo Electrónico',
                'email-address' => 'admin@example.com',
                'password' => 'Contraseña',
                'title' => 'Crear Administrador',
            ],

            'environment-configuration' => [
                'algerian-dinar' => 'Dinar Argelino (DZD)',
                'allowed-currencies' => 'Monedas Permitidas',
                'allowed-locales' => 'Idiomas Permitidos',
                'application-name' => 'Nombre de la Aplicación',
                'argentine-peso' => 'Peso Argentino (ARS)',
                'australian-dollar' => 'Dólar Australiano (AUD)',
                'bagisto' => 'Bagisto',
                'bangladeshi-taka' => 'Taka de Bangladesh (BDT)',
                'bahraini-dinar' => 'Dinar de Bahreiní (BHD)',
                'brazilian-real' => 'Real Brasileño (BRL)',
                'british-pound-sterling' => 'Libra Esterlina Británica (GBP)',
                'canadian-dollar' => 'Dólar Canadiense (CAD)',
                'cfa-franc-bceao' => 'Franco CFA BCEAO (XOF)',
                'cfa-franc-beac' => 'Franco CFA BEAC (XAF)',
                'chilean-peso' => 'Peso Chileno (CLP)',
                'chinese-yuan' => 'Yuan Chino (CNY)',
                'colombian-peso' => 'Peso Colombiano (COP)',
                'czech-koruna' => 'Corona Checa (CZK)',
                'danish-krone' => 'Corona Danesa (DKK)',
                'database-connection' => 'Conexión de Base de Datos',
                'database-hostname' => 'Nombre de Host de la Base de Datos',
                'database-name' => 'Nombre de la Base de Datos',
                'database-password' => 'Contraseña de la Base de Datos',
                'database-port' => 'Puerto de la Base de Datos',
                'database-prefix' => 'Prefijo de la Base de Datos',
                'database-prefix-help' => 'El prefijo debe tener 4 caracteres de longitud y solo puede contener letras, números y guiones bajos.',
                'database-username' => 'Nombre de Usuario de la Base de Datos',
                'default-currency' => 'Moneda Predeterminada',
                'default-locale' => 'Idioma Predeterminado',
                'default-timezone' => 'Zona Horaria Predeterminada',
                'default-url' => 'URL Predeterminada',
                'default-url-link' => 'https://localhost',
                'egyptian-pound' => 'Libra Egipcia (EGP)',
                'euro' => 'Euro (EUR)',
                'fijian-dollar' => 'Dólar Fiyiano (FJD)',
                'hong-kong-dollar' => 'Dólar de Hong Kong (HKD)',
                'hungarian-forint' => 'Forint Húngaro (HUF)',
                'indian-rupee' => 'Rupia India (INR)',
                'indonesian-rupiah' => 'Rupia Indonesia (IDR)',
                'israeli-new-shekel' => 'Nuevo Shekel Israelí (ILS)',
                'japanese-yen' => 'Yen Japonés (JPY)',
                'jordanian-dinar' => 'Dinar Jordano (JOD)',
                'kazakhstani-tenge' => 'Tenge Kazajo (KZT)',
                'kuwaiti-dinar' => 'Dinar Kuwaití (KWD)',
                'lebanese-pound' => 'Libra Libanesa (LBP)',
                'libyan-dinar' => 'Dinar Libio (LYD)',
                'malaysian-ringgit' => 'Ringgit Malayo (MYR)',
                'mauritian-rupee' => 'Rupia Mauriciana (MUR)',
                'mexican-peso' => 'Peso Mexicano (MXN)',
                'moroccan-dirham' => 'Dirham Marroquí (MAD)',
                'mysql' => 'Mysql',
                'nepalese-rupee' => 'Rupia Nepalí (NPR)',
                'new-taiwan-dollar' => 'Dólar de Taiwán (TWD)',
                'new-zealand-dollar' => 'Dólar de Nueva Zelanda (NZD)',
                'nigerian-naira' => 'Naira Nigeriano (NGN)',
                'norwegian-krone' => 'Corona Noruega (NOK)',
                'omani-rial' => 'Rial Omaní (OMR)',
                'pakistani-rupee' => 'Rupia Pakistaní (PKR)',
                'panamanian-balboa' => 'Balboa Panameño (PAB)',
                'paraguayan-guarani' => 'Guaraní Paraguayo (PYG)',
                'peruvian-nuevo-sol' => 'Nuevo Sol Peruano (PEN)',
                'pgsql' => 'pgSQL',
                'philippine-peso' => 'Peso Filipino (PHP)',
                'polish-zloty' => 'Zloty Polaco (PLN)',
                'qatari-rial' => 'Rial de Qatar (QAR)',
                'romanian-leu' => 'Leu Rumano (RON)',
                'russian-ruble' => 'Rublo Ruso (RUB)',
                'saudi-riyal' => 'Riyal Saudí (SAR)',
                'select-timezone' => 'Seleccionar Zona Horaria',
                'singapore-dollar' => 'Dólar de Singapur (SGD)',
                'south-african-rand' => 'Rand Sudafricano (ZAR)',
                'south-korean-won' => 'Won Surcoreano (KRW)',
                'sqlsrv' => 'SQLSRV',
                'sri-lankan-rupee' => 'Rupia de Sri Lanka (LKR)',
                'swedish-krona' => 'Corona Sueca (SEK)',
                'swiss-franc' => 'Franco Suizo (CHF)',
                'thai-baht' => 'Baht Tailandés (THB)',
                'title' => 'Configuración de la Tienda',
                'tunisian-dinar' => 'Dinar Tunecino (TND)',
                'turkish-lira' => 'Lira Turca (TRY)',
                'ukrainian-hryvnia' => 'Grivna Ucraniana (UAH)',
                'united-arab-emirates-dirham' => 'Dirham de los Emiratos Árabes Unidos (AED)',
                'united-states-dollar' => 'Dólar Estadounidense (USD)',
                'uzbekistani-som' => 'Som Uzbeko (UZS)',
                'venezuelan-bolívar' => 'Bolívar Venezolano (VEF)',
                'vietnamese-dong' => 'Dong Vietnamita (VND)',
                'warning-message' => '¡Cuidado! Los ajustes de su idioma del sistema predeterminado y la moneda predeterminada son permanentes y no se pueden cambiar una vez establecidos.',
                'zambian-kwacha' => 'Kwacha de Zambia (ZMW)',
            ],

            'sample-products' => [
                'no' => 'No',
                'note' => 'Nota: El tiempo de indexación depende del número de idiomas seleccionados. Este proceso puede tardar hasta 2 minutos en completarse. Si añades más idiomas, intenta aumentar el tiempo máximo de ejecución en la configuración de tu servidor y PHP, o puedes usar nuestro instalador CLI para evitar el tiempo de espera de la solicitud.',
                'sample-products' => 'Productos de muestra',
                'title' => 'Productos de muestra',
                'yes' => 'Sí',
            ],

            'installation-processing' => [
                'bagisto' => 'Instalación de Bagisto',
                'bagisto-info' => 'Creando las tablas de la base de datos, esto puede tomar algunos momentos',
                'title' => 'Instalación',
            ],

            'installation-completed' => [
                'admin-panel' => 'Panel de administración',
                'bagisto-forums' => 'Foro de Bagisto',
                'customer-panel' => 'Panel de clientes',
                'explore-bagisto-extensions' => 'Explorar extensiones de Bagisto',
                'title' => 'Instalación completada',
                'title-info' => 'Bagisto se ha instalado correctamente en su sistema.',
            ],

            'ready-for-installation' => [
                'create-database-tables' => 'Crear las tablas de la base de datos',
                'drop-existing-tables' => 'Eliminar cualquier tabla existente',
                'install' => 'Instalación',
                'install-info' => 'Bagisto para instalación',
                'install-info-button' => 'Haz clic en el botón de abajo para',
                'populate-database-tables' => 'Rellenar las tablas de la base de datos',
                'start-installation' => 'Iniciar instalación',
                'title' => 'Listo para la instalación',
            ],

            'start' => [
                'locale' => 'Local',
                'main' => 'Comienzo',
                'select-locale' => 'Seleccionar Local',
                'title' => 'Tu instalación de Bagisto',
                'welcome-title' => 'Bienvenido a Bagisto',
            ],

            'server-requirements' => [
                'calendar' => 'Calendario',
                'ctype' => 'cType',
                'curl' => 'cURL',
                'dom' => 'DOM',
                'fileinfo' => 'Información del archivo',
                'filter' => 'Filtro',
                'gd' => 'GD',
                'hash' => 'Hash',
                'intl' => 'Intl',
                'json' => 'JSON',
                'mbstring' => 'mbstring',
                'openssl' => 'OpenSSL',
                'pcre' => 'pcre',
                'pdo' => 'pdo',
                'php' => 'PHP',
                'php-version' => ':version o superior',
                'session' => 'Sesión',
                'title' => 'Requisitos del servidor',
                'tokenizer' => 'Tokenizer',
                'xml' => 'XML',
            ],

            'arabic' => 'Árabe',
            'back' => 'Atrás',
            'bagisto' => 'Bagisto',
            'bagisto-info' => 'Un proyecto comunitario por',
            'bagisto-logo' => 'Logo de Bagisto',
            'bengali' => 'Bengalí',
            'catalan' => 'Catalán',
            'chinese' => 'Chino',
            'continue' => 'Continuar',
            'dutch' => 'Holandés',
            'english' => 'Inglés',
            'french' => 'Francés',
            'german' => 'Alemán',
            'hebrew' => 'Hebreo',
            'hindi' => 'Hindi',
            'indonesian' => 'Indonesio',
            'installation-description' => 'La instalación de Bagisto generalmente implica varios pasos. Aquí hay un esquema general del proceso de instalación para Bagisto',
            'installation-info' => '¡Nos alegra verte aquí!',
            'installation-title' => 'Bienvenido a la Instalación',
            'italian' => 'Italiano',
            'japanese' => 'Japonés',
            'persian' => 'Persa',
            'polish' => 'Polaco',
            'portuguese' => 'Portugués brasileño',
            'russian' => 'Ruso',
            'sinhala' => 'Cingalés',
            'spanish' => 'Español',
            'title' => 'Instalador de Bagisto',
            'turkish' => 'Turco',
            'ukrainian' => 'Ucraniano',
            'webkul' => 'Webkul',
        ],
    ],
];
