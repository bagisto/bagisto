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

        'sample-categories' => [
            'category-translation' => [
                '2' => [
                    'description'      => 'Descripción de la categoría de hombres',
                    'meta-description' => 'Meta descripción de la categoría de hombres',
                    'meta-keywords'    => 'Meta palabras clave de la categoría de hombres',
                    'meta-title'       => 'Meta título de la categoría de hombres',
                    'name'             => 'Hombres',
                    'slug'             => 'hombres',
                ],

                '3' => [
                    'description'      => 'Descripción de la categoría de ropa de invierno',
                    'meta-description' => 'Meta descripción de la categoría de ropa de invierno',
                    'meta-keywords'    => 'Meta palabras clave de la categoría de ropa de invierno',
                    'meta-title'       => 'Meta título de la categoría de ropa de invierno',
                    'name'             => 'Ropa de invierno',
                    'slug'             => 'ropa-de-invierno',
                ],
            ],
        ],

        'sample-products' => [
            'product-flat' => [
                '1' => [
                    'description'       => 'El Gorro de Punto Cómodo del Ártico es tu solución para mantenerte abrigado, cómodo y con estilo durante los meses más fríos. Elaborado con una mezcla suave y duradera de acrílico, este gorro está diseñado para proporcionar un ajuste acogedor y ceñido. Su diseño clásico lo hace adecuado tanto para hombres como para mujeres, ofreciendo un accesorio versátil que complementa varios estilos. Ya sea que salgas para un día casual en la ciudad o disfrutes del aire libre, este gorro añade un toque de comodidad y calidez a tu conjunto. El material suave y transpirable asegura que te mantengas abrigado sin sacrificar el estilo. El Gorro de Punto Cómodo del Ártico no es solo un accesorio; es una declaración de moda invernal. Su simplicidad hace que sea fácil de combinar con diferentes outfits, convirtiéndolo en un básico en tu guardarropa de invierno. Ideal para regalar o como un capricho para ti mismo, este gorro es una adición considerada a cualquier conjunto invernal. Es un accesorio versátil que va más allá de la funcionalidad, añadiendo un toque de calidez y estilo a tu look. Abraza la esencia del invierno con el Gorro de Punto Cómodo del Ártico. Ya sea que disfrutes de un día casual o te enfrentes a los elementos, deja que este gorro sea tu compañero de comodidad y estilo. Eleva tu guardarropa de invierno con este accesorio clásico que combina sin esfuerzo la calidez con un sentido atemporal de la moda.',
                    'meta-description'  => 'descripción meta',
                    'meta-keywords'     => 'meta1, meta2, meta3',
                    'meta-title'        => 'Título Meta',
                    'name'              => 'Gorro de Punto Unisex Cómodo del Ártico',
                    'short-description' => 'Abraza los días fríos con estilo con nuestro Gorro de Punto Cómodo del Ártico. Elaborado con una mezcla suave y duradera de acrílico, este gorro clásico ofrece calidez y versatilidad. Adecuado tanto para hombres como para mujeres, es el accesorio ideal para uso casual o al aire libre. Eleva tu guardarropa de invierno o regala a alguien especial este gorro esencial.',
                ],

                '2' => [
                    'description'       => 'La Bufanda de Invierno Arctic Bliss es más que un accesorio para el clima frío; es una declaración de calidez, comodidad y estilo para la temporada de invierno. Elaborada con cuidado a partir de una lujosa mezcla de acrílico y lana, esta bufanda está diseñada para mantenerte abrigado y cómodo incluso en las temperaturas más frías. La textura suave y mullida no solo proporciona aislamiento contra el frío, sino que también añade un toque de lujo a tu guardarropa de invierno. El diseño de la Bufanda de Invierno Arctic Bliss es elegante y versátil, lo que la convierte en una adición perfecta a una variedad de outfits invernales. Ya sea que te vistas para una ocasión especial o añadas una capa elegante a tu look diario, esta bufanda complementa tu estilo sin esfuerzo. La longitud extra larga de la bufanda ofrece opciones de estilo personalizables. Envuélvela para mayor calidez, déjala suelta para un look casual o experimenta con diferentes nudos para expresar tu estilo único. Esta versatilidad la convierte en un accesorio imprescindible para la temporada de invierno. ¿Buscas el regalo perfecto? La Bufanda de Invierno Arctic Bliss es una elección ideal. Ya sea que sorprendas a un ser querido o te des un capricho, esta bufanda es un regalo atemporal y práctico que será apreciado durante los meses de invierno. Abraza el invierno con la Bufanda de Invierno Arctic Bliss, donde la calidez se encuentra con el estilo en perfecta armonía. Eleva tu guardarropa de invierno con este accesorio esencial que no solo te mantiene abrigado, sino que también añade un toque de sofisticación a tu conjunto para el clima frío.',
                    'meta-description'  => 'descripción meta',
                    'meta-keywords'     => 'meta1, meta2, meta3',
                    'meta-title'        => 'Título Meta',
                    'name'              => 'Bufanda de Invierno Elegante Arctic Bliss',
                    'short-description' => 'Experimenta el abrazo de la calidez y el estilo con nuestra Bufanda de Invierno Arctic Bliss. Elaborada con una lujosa mezcla de acrílico y lana, esta bufanda acogedora está diseñada para mantenerte abrigado durante los días más fríos. Su diseño elegante y versátil, combinado con una longitud extra larga, ofrece opciones de estilo personalizables. Eleva tu guardarropa de invierno o deleita a alguien especial con este accesorio esencial para el invierno.',
                ],

                '3' => [
                    'description'       => 'Presentamos los Guantes de Invierno Arctic con Pantalla Táctil, donde la calidez, el estilo y la conectividad se unen para mejorar tu experiencia invernal. Elaborados con acrílico de alta calidad, estos guantes están diseñados para proporcionar una calidez y durabilidad excepcionales. Las puntas compatibles con pantallas táctiles te permiten estar conectado sin exponer tus manos al frío. Contesta llamadas, envía mensajes y navega por tus dispositivos sin esfuerzo, todo mientras mantienes tus manos abrigadas. El forro aislante añade una capa adicional de comodidad, haciendo de estos guantes tu elección ideal para enfrentar el frío del invierno. Ya sea que estés viajando, haciendo recados o disfrutando de actividades al aire libre, estos guantes te brindan la calidez y protección que necesitas. Los puños elásticos aseguran un ajuste seguro, evitando corrientes de aire frío y manteniendo los guantes en su lugar durante tus actividades diarias. El diseño elegante añade un toque de estilo a tu conjunto invernal, haciendo que estos guantes sean tan fashion como funcionales. Ideales para regalar o como un capricho para ti mismo, los Guantes de Invierno Arctic con Pantalla Táctil son un accesorio imprescindible para el individuo moderno. Di adiós a la molestia de quitarte los guantes para usar tus dispositivos y abraza la combinación perfecta de calidez, estilo y conectividad. Mantente conectado, mantente abrigado y mantente a la moda con los Guantes de Invierno Arctic con Pantalla Táctil, tu compañero confiable para conquistar la temporada de invierno con confianza.',
                    'meta-description'  => 'descripción meta',
                    'meta-keywords'     => 'meta1, meta2, meta3',
                    'meta-title'        => 'Título Meta',
                    'name'              => 'Guantes de Invierno con Pantalla Táctil Arctic',
                    'short-description' => 'Mantente conectado y abrigado con nuestros Guantes de Invierno con Pantalla Táctil Arctic. Estos guantes no solo están elaborados con acrílico de alta calidad para brindar calidez y durabilidad, sino que también cuentan con un diseño compatible con pantallas táctiles. Con un forro aislante, puños elásticos para un ajuste seguro y un aspecto elegante, estos guantes son perfectos para el uso diario en condiciones frías.',
                ],

                '4' => [
                    'description'       => 'Presentamos los Calcetines de Mezcla de Lana Arctic Warmth, tu compañero esencial para pies acogedores y cómodos durante las estaciones más frías. Elaborados con una mezcla premium de lana Merino, acrílico, nylon y spandex, estos calcetines están diseñados para brindar un calor y comodidad incomparables. La mezcla de lana asegura que tus pies se mantengan calientes incluso en las temperaturas más frías, convirtiendo estos calcetines en la elección perfecta para aventuras invernales o simplemente para estar cómodo en casa. La textura suave y acogedora de los calcetines ofrece una sensación de lujo en tu piel. Di adiós a los pies fríos mientras disfrutas del cálido abrazo proporcionado por estos calcetines de mezcla de lana. Diseñados para ser duraderos, los calcetines cuentan con un refuerzo en el talón y la punta, brindando mayor resistencia en las áreas de mayor desgaste. Esto asegura que tus calcetines resistirán el paso del tiempo, brindando comodidad y calidez duraderas. La naturaleza transpirable del material evita el sobrecalentamiento, permitiendo que tus pies se mantengan cómodos y secos durante todo el día. Ya sea que te dirijas al aire libre para una caminata invernal o te relajes en casa, estos calcetines ofrecen el equilibrio perfecto entre calor y transpirabilidad. Versátiles y elegantes, estos calcetines de mezcla de lana son adecuados para diversas ocasiones. Combínalos con tus botas favoritas para un look invernal de moda o úsalos en casa para una comodidad máxima. Eleva tu guardarropa de invierno y prioriza la comodidad con los Calcetines de Mezcla de Lana Arctic Warmth. Consiente a tus pies con el lujo que se merecen y sumérgete en un mundo de comodidad que dura toda la temporada.',
                    'meta-description'  => 'descripción meta',
                    'meta-keywords'     => 'meta1, meta2, meta3',
                    'meta-title'        => 'Título Meta',
                    'name'              => 'Calcetines de Mezcla de Lana Arctic Warmth',
                    'short-description' => 'Experimenta el calor y la comodidad incomparables de nuestros Calcetines de Mezcla de Lana Arctic Warmth. Elaborados con una mezcla de lana Merino, acrílico, nylon y spandex, estos calcetines ofrecen una comodidad definitiva para el clima frío. Con un refuerzo en el talón y la punta para mayor durabilidad, estos calcetines versátiles y elegantes son perfectos para diversas ocasiones.',
                ],

                '5' => [
                    'description'       => 'Presentamos el Conjunto de Accesorios de Invierno Arctic Frost, tu solución para mantenerte abrigado, elegante y conectado durante los días fríos de invierno. Este conjunto cuidadosamente seleccionado reúne cuatro accesorios esenciales de invierno para crear un conjunto armonioso. La lujosa bufanda, tejida con una mezcla de acrílico y lana, no solo agrega una capa de calidez, sino que también aporta un toque de elegancia a tu guardarropa de invierno. El gorro de punto suave, elaborado con cuidado, promete mantenerte acogedor y agregar un toque de moda a tu look. Pero eso no es todo, nuestro conjunto también incluye guantes compatibles con pantallas táctiles. Mantente conectado sin sacrificar el calor mientras navegas por tus dispositivos sin esfuerzo. Ya sea que estés contestando llamadas, enviando mensajes o capturando momentos invernales en tu teléfono inteligente, estos guantes garantizan comodidad sin comprometer el estilo. La textura suave y acogedora de los calcetines ofrece una sensación de lujo en tu piel. Di adiós a los pies fríos mientras disfrutas del cálido abrazo proporcionado por estos calcetines de mezcla de lana. El Conjunto de Accesorios de Invierno Arctic Frost no se trata solo de funcionalidad; es una declaración de moda invernal. Cada pieza está diseñada no solo para protegerte del frío, sino también para elevar tu estilo durante la temporada helada. Los materiales elegidos para este conjunto priorizan tanto la durabilidad como la comodidad, asegurando que puedas disfrutar del paisaje invernal con estilo. Ya sea que te estés dando un gusto o buscando el regalo perfecto, el Conjunto de Accesorios de Invierno Arctic Frost es una elección versátil. Deleita a alguien especial durante la temporada navideña o eleva tu propio guardarropa de invierno con este conjunto elegante y funcional. Acepta el frío con confianza, sabiendo que tienes los accesorios perfectos para mantenerte abrigado y elegante.',
                    'meta-description'  => 'descripción meta',
                    'meta-keywords'     => 'meta1, meta2, meta3',
                    'meta-title'        => 'Título Meta',
                    'name'              => 'Conjunto de Accesorios de Invierno Arctic Frost',
                    'short-description' => 'Acepta el frío del invierno con nuestro Conjunto de Accesorios de Invierno Arctic Frost. Este conjunto seleccionado incluye una lujosa bufanda, un gorro acogedor, guantes compatibles con pantallas táctiles y calcetines de mezcla de lana. Elegante y funcional, este conjunto está elaborado con materiales de alta calidad, asegurando tanto durabilidad como comodidad. Eleva tu guardarropa de invierno o deleita a alguien especial con esta opción de regalo perfecta.',
                ],

                '6' => [
                    'description'       => 'Presentamos el Conjunto de Accesorios de Invierno Arctic Frost, tu solución para mantenerte abrigado, elegante y conectado durante los días fríos de invierno. Este conjunto cuidadosamente seleccionado reúne cuatro accesorios esenciales de invierno para crear un conjunto armonioso. La lujosa bufanda, tejida con una mezcla de acrílico y lana, no solo agrega una capa de calidez, sino que también aporta un toque de elegancia a tu guardarropa de invierno. El gorro de punto suave, elaborado con cuidado, promete mantenerte acogedor y agregar un toque de moda a tu look. Pero eso no es todo, nuestro conjunto también incluye guantes compatibles con pantallas táctiles. Mantente conectado sin sacrificar el calor mientras navegas por tus dispositivos sin esfuerzo. Ya sea que estés contestando llamadas, enviando mensajes o capturando momentos invernales en tu teléfono inteligente, estos guantes garantizan comodidad sin comprometer el estilo. La textura suave y acogedora de los calcetines ofrece una sensación de lujo en tu piel. Di adiós a los pies fríos mientras disfrutas del cálido abrazo proporcionado por estos calcetines de mezcla de lana. El Conjunto de Accesorios de Invierno Arctic Frost no se trata solo de funcionalidad; es una declaración de moda invernal. Cada pieza está diseñada no solo para protegerte del frío, sino también para elevar tu estilo durante la temporada helada. Los materiales elegidos para este conjunto priorizan tanto la durabilidad como la comodidad, asegurando que puedas disfrutar del paisaje invernal con estilo. Ya sea que te estés dando un gusto o buscando el regalo perfecto, el Conjunto de Accesorios de Invierno Arctic Frost es una elección versátil. Deleita a alguien especial durante la temporada navideña o eleva tu propio guardarropa de invierno con este conjunto elegante y funcional. Acepta el frío con confianza, sabiendo que tienes los accesorios perfectos para mantenerte abrigado y elegante.',
                    'meta-description'  => 'descripción meta',
                    'meta-keywords'     => 'meta1, meta2, meta3',
                    'meta-title'        => 'Título Meta',
                    'name'              => 'Conjunto de Accesorios de Invierno Arctic Frost',
                    'short-description' => 'Acepta el frío del invierno con nuestro Conjunto de Accesorios de Invierno Arctic Frost. Este conjunto seleccionado incluye una lujosa bufanda, un gorro acogedor, guantes compatibles con pantallas táctiles y calcetines de mezcla de lana. Elegante y funcional, este conjunto está elaborado con materiales de alta calidad, asegurando tanto durabilidad como comodidad. Eleva tu guardarropa de invierno o deleita a alguien especial con esta opción de regalo perfecta.',
                ],

                '7' => [
                    'description'       => 'Presentamos la Chaqueta Acolchada con Capucha OmniHeat para Hombre, tu solución ideal para mantenerte abrigado y a la moda durante las estaciones más frías. Esta chaqueta está diseñada pensando en la durabilidad y el calor, asegurando que se convierta en tu compañera de confianza. El diseño con capucha no solo agrega un toque de estilo, sino que también proporciona calor adicional, protegiéndote de los vientos fríos y el clima. Las mangas completas ofrecen cobertura completa, asegurando que te mantengas cómodo desde el hombro hasta la muñeca. Equipada con bolsillos insertados, esta chaqueta acolchada brinda comodidad para llevar tus objetos esenciales o mantener tus manos calientes. El relleno sintético aislante ofrece mayor calidez, lo que la hace ideal para combatir los días y noches fríos. Hecha de una resistente carcasa y forro de poliéster, esta chaqueta está construida para durar y resistir los elementos. Disponible en 5 atractivos colores, puedes elegir el que se adapte a tu estilo y preferencia. Versátil y funcional, la Chaqueta Acolchada con Capucha OmniHeat para Hombre es adecuada para diversas ocasiones, ya sea que vayas al trabajo, salgas de manera informal o asistas a un evento al aire libre. Experimenta la combinación perfecta de estilo, comodidad y funcionalidad con la Chaqueta Acolchada con Capucha OmniHeat para Hombre. Eleva tu guardarropa de invierno y mantente abrigado mientras disfrutas del aire libre. Vence el frío con estilo y haz una declaración con esta pieza esencial.',
                    'meta-description'  => 'descripción meta',
                    'meta-keywords'     => 'meta1, meta2, meta3',
                    'meta-title'        => 'Título Meta',
                    'name'              => 'Chaqueta Acolchada con Capucha OmniHeat para Hombre',
                    'short-description' => 'Mantente abrigado y a la moda con nuestra Chaqueta Acolchada con Capucha OmniHeat para Hombre. Esta chaqueta está diseñada para proporcionar calor máximo y cuenta con bolsillos insertados para mayor comodidad. El material aislante asegura que te mantengas cómodo en climas fríos. Disponible en 5 atractivos colores, lo que la convierte en una opción versátil para diversas ocasiones.',
                ],

                '8' => [
                    'description'       => 'Presentamos la Chaqueta Acolchada con Capucha OmniHeat para Hombre, tu solución ideal para mantenerte abrigado y a la moda durante las estaciones más frías. Esta chaqueta está diseñada pensando en la durabilidad y el calor, asegurando que se convierta en tu compañera de confianza. El diseño con capucha no solo agrega un toque de estilo, sino que también proporciona calor adicional, protegiéndote de los vientos fríos y el clima. Las mangas completas ofrecen cobertura completa, asegurando que te mantengas cómodo desde el hombro hasta la muñeca. Equipada con bolsillos insertados, esta chaqueta acolchada brinda comodidad para llevar tus objetos esenciales o mantener tus manos calientes. El relleno sintético aislante ofrece mayor calidez, lo que la hace ideal para combatir los días y noches fríos. Hecha de una resistente carcasa y forro de poliéster, esta chaqueta está construida para durar y resistir los elementos. Disponible en 5 atractivos colores, puedes elegir el que se adapte a tu estilo y preferencia. Versátil y funcional, la Chaqueta Acolchada con Capucha OmniHeat para Hombre es adecuada para diversas ocasiones, ya sea que vayas al trabajo, salgas de manera informal o asistas a un evento al aire libre. Experimenta la combinación perfecta de estilo, comodidad y funcionalidad con la Chaqueta Acolchada con Capucha OmniHeat para Hombre. Eleva tu guardarropa de invierno y mantente abrigado mientras disfrutas del aire libre. Vence el frío con estilo y haz una declaración con esta pieza esencial.',
                    'meta-description'  => 'descripción meta',
                    'meta-keywords'     => 'meta1, meta2, meta3',
                    'meta-title'        => 'Título Meta',
                    'name'              => 'Chaqueta Acolchada con Capucha OmniHeat para Hombre-Azul-Amarillo-M',
                    'short-description' => 'Mantente abrigado y a la moda con nuestra Chaqueta Acolchada con Capucha OmniHeat para Hombre. Esta chaqueta está diseñada para proporcionar calor máximo y cuenta con bolsillos insertados para mayor comodidad. El material aislante asegura que te mantengas cómodo en climas fríos. Disponible en 5 atractivos colores, lo que la convierte en una opción versátil para diversas ocasiones.',
                ],

                '9' => [
                    'description'       => 'Presentamos la Chaqueta Acolchada con Capucha OmniHeat para Hombre, tu solución ideal para mantenerte abrigado y a la moda durante las estaciones más frías. Esta chaqueta está diseñada pensando en la durabilidad y el calor, asegurando que se convierta en tu compañera de confianza. El diseño con capucha no solo agrega un toque de estilo, sino que también proporciona calor adicional, protegiéndote de los vientos fríos y el clima. Las mangas completas ofrecen cobertura completa, asegurando que te mantengas cómodo desde el hombro hasta la muñeca. Equipada con bolsillos insertados, esta chaqueta acolchada brinda comodidad para llevar tus objetos esenciales o mantener tus manos calientes. El relleno sintético aislante ofrece mayor calidez, lo que la hace ideal para combatir los días y noches fríos. Hecha de una resistente carcasa y forro de poliéster, esta chaqueta está construida para durar y resistir los elementos. Disponible en 5 atractivos colores, puedes elegir el que se adapte a tu estilo y preferencia. Versátil y funcional, la Chaqueta Acolchada con Capucha OmniHeat para Hombre es adecuada para diversas ocasiones, ya sea que vayas al trabajo, salgas de manera informal o asistas a un evento al aire libre. Experimenta la combinación perfecta de estilo, comodidad y funcionalidad con la Chaqueta Acolchada con Capucha OmniHeat para Hombre. Eleva tu guardarropa de invierno y mantente abrigado mientras disfrutas del aire libre. Vence el frío con estilo y haz una declaración con esta pieza esencial.',
                    'meta-description'  => 'descripción meta',
                    'meta-keywords'     => 'meta1, meta2, meta3',
                    'meta-title'        => 'Título Meta',
                    'name'              => 'Chaqueta Acolchada con Capucha OmniHeat para Hombre-Azul-Amarillo-L',
                    'short-description' => 'Mantente abrigado y a la moda con nuestra Chaqueta Acolchada con Capucha OmniHeat para Hombre. Esta chaqueta está diseñada para proporcionar calor máximo y cuenta con bolsillos insertados para mayor comodidad. El material aislante asegura que te mantengas cómodo en climas fríos. Disponible en 5 atractivos colores, lo que la convierte en una opción versátil para diversas ocasiones.',
                ],

                '10' => [
                    'description'       => 'Presentamos la Chaqueta Acolchada con Capucha OmniHeat para Hombre, tu solución ideal para mantenerte abrigado y a la moda durante las estaciones más frías. Esta chaqueta está diseñada con durabilidad y calidez en mente, asegurando que se convierta en tu compañera de confianza. El diseño con capucha no solo agrega un toque de estilo, sino que también proporciona calor adicional, protegiéndote de los vientos fríos y el clima. Las mangas completas ofrecen cobertura completa, asegurando que te mantengas cómodo desde el hombro hasta la muñeca. Equipada con bolsillos insertados, esta chaqueta acolchada brinda comodidad para llevar tus elementos esenciales o mantener tus manos calientes. El relleno sintético aislante ofrece mayor calidez, lo que la hace ideal para combatir los días y noches frías. Hecha de una resistente carcasa y forro de poliéster, esta chaqueta está construida para durar y resistir los elementos. Disponible en 5 atractivos colores, puedes elegir el que se adapte a tu estilo y preferencia. Versátil y funcional, la Chaqueta Acolchada con Capucha OmniHeat para Hombre es adecuada para diversas ocasiones, ya sea que vayas al trabajo, salgas de manera informal o asistas a un evento al aire libre. Experimenta la combinación perfecta de estilo, comodidad y funcionalidad con la Chaqueta Acolchada con Capucha OmniHeat para Hombre. Eleva tu guardarropa de invierno y mantente abrigado mientras disfrutas del aire libre. Vence el frío con estilo y haz una declaración con esta pieza esencial.',
                    'meta-description'  => 'descripción meta',
                    'meta-keywords'     => 'meta1, meta2, meta3',
                    'meta-title'        => 'Título Meta',
                    'name'              => 'Chaqueta Acolchada con Capucha OmniHeat para Hombre-Azul-Verde-M',
                    'short-description' => 'Mantente abrigado y a la moda con nuestra Chaqueta Acolchada con Capucha OmniHeat para Hombre. Esta chaqueta está diseñada para proporcionar calidez máxima y cuenta con bolsillos insertados para mayor comodidad. El material aislante asegura que te mantengas cómodo en clima frío. Disponible en 5 atractivos colores, lo que la convierte en una opción versátil para diversas ocasiones.',
                ],

                '11' => [
                    'description'       => 'Presentamos la Chaqueta Acolchada con Capucha OmniHeat para Hombre, tu solución ideal para mantenerte abrigado y a la moda durante las estaciones más frías. Esta chaqueta está diseñada con durabilidad y calidez en mente, asegurando que se convierta en tu compañera de confianza. El diseño con capucha no solo agrega un toque de estilo, sino que también proporciona calor adicional, protegiéndote de los vientos fríos y el clima. Las mangas completas ofrecen cobertura completa, asegurando que te mantengas cómodo desde el hombro hasta la muñeca. Equipada con bolsillos insertados, esta chaqueta acolchada brinda comodidad para llevar tus elementos esenciales o mantener tus manos calientes. El relleno sintético aislante ofrece mayor calidez, lo que la hace ideal para combatir los días y noches frías. Hecha de una resistente carcasa y forro de poliéster, esta chaqueta está construida para durar y resistir los elementos. Disponible en 5 atractivos colores, puedes elegir el que se adapte a tu estilo y preferencia. Versátil y funcional, la Chaqueta Acolchada con Capucha OmniHeat para Hombre es adecuada para diversas ocasiones, ya sea que vayas al trabajo, salgas de manera informal o asistas a un evento al aire libre. Experimenta la combinación perfecta de estilo, comodidad y funcionalidad con la Chaqueta Acolchada con Capucha OmniHeat para Hombre. Eleva tu guardarropa de invierno y mantente abrigado mientras disfrutas del aire libre. Vence el frío con estilo y haz una declaración con esta pieza esencial.',
                    'meta-description'  => 'descripción meta',
                    'meta-keywords'     => 'meta1, meta2, meta3',
                    'meta-title'        => 'Título Meta',
                    'name'              => 'Chaqueta Acolchada con Capucha OmniHeat para Hombre-Azul-Verde-L',
                    'short-description' => 'Mantente abrigado y a la moda con nuestra Chaqueta Acolchada con Capucha OmniHeat para Hombre. Esta chaqueta está diseñada para proporcionar calidez máxima y cuenta con bolsillos insertados para mayor comodidad. El material aislante asegura que te mantengas cómodo en clima frío. Disponible en 5 atractivos colores, lo que la convierte en una opción versátil para diversas ocasiones.',
                ],
            ],

            'product-attribute-values' => [
                '1' => [
                    'description'      => 'El Gorro de Punto Cómodo del Ártico es tu solución para mantenerte abrigado, cómodo y con estilo durante los meses más fríos. Elaborado con una mezcla suave y duradera de acrílico, este gorro está diseñado para proporcionar un ajuste acogedor y ceñido. Su diseño clásico lo hace adecuado tanto para hombres como para mujeres, ofreciendo un accesorio versátil que complementa varios estilos. Ya sea que salgas para un día casual en la ciudad o disfrutes del aire libre, este gorro añade un toque de comodidad y calidez a tu conjunto. El material suave y transpirable garantiza que te mantengas abrigado sin sacrificar el estilo. El Gorro de Punto Cómodo del Ártico no es solo un accesorio; es una declaración de moda invernal. Su simplicidad hace que sea fácil de combinar con diferentes outfits, convirtiéndolo en un básico en tu guardarropa de invierno. Ideal para regalar o como un capricho para ti mismo, este gorro es una adición considerada a cualquier conjunto invernal. Es un accesorio versátil que va más allá de la funcionalidad, añadiendo un toque de calidez y estilo a tu look. Acepta la esencia del invierno con el Gorro de Punto Cómodo del Ártico. Ya sea que disfrutes de un día casual o te enfrentes a los elementos, deja que este gorro sea tu compañero de comodidad y estilo. Eleva tu guardarropa de invierno con este accesorio clásico que combina sin esfuerzo la calidez con un sentido atemporal de la moda.',
                    'meta-description' => 'descripción meta',
                    'meta-keywords'    => 'meta1, meta2, meta3',
                    'meta-title'       => 'Título Meta',
                    'name'             => 'Gorro de Punto Cómodo del Ártico Unisex',
                    'sort-description' => 'Acepta los días fríos con estilo con nuestro Gorro de Punto Cómodo del Ártico. Elaborado con una mezcla suave y duradera de acrílico, este gorro clásico ofrece calidez y versatilidad. Adecuado tanto para hombres como para mujeres, es el accesorio ideal para uso casual o al aire libre. Eleva tu guardarropa de invierno o regala a alguien especial esta gorra esencial.',
                ],

                '2' => [
                    'description'      => 'La Bufanda de Invierno Arctic Bliss es más que un accesorio para el clima frío; es una declaración de calidez, comodidad y estilo para la temporada de invierno. Elaborada con cuidado a partir de una lujosa mezcla de acrílico y lana, esta bufanda está diseñada para mantenerte abrigado y cómodo incluso en las temperaturas más frías. La textura suave y mullida no solo proporciona aislamiento contra el frío, sino que también añade un toque de lujo a tu guardarropa de invierno. El diseño de la Bufanda de Invierno Arctic Bliss es elegante y versátil, lo que la convierte en una adición perfecta a una variedad de outfits invernales. Ya sea que te vistas para una ocasión especial o añadas una capa elegante a tu look diario, esta bufanda complementa tu estilo sin esfuerzo. La longitud extra larga de la bufanda ofrece opciones de estilo personalizables. Envuélvela para mayor calidez, déjala suelta para un look casual o experimenta con diferentes nudos para expresar tu estilo único. Esta versatilidad la convierte en un accesorio imprescindible para la temporada de invierno. ¿Buscas el regalo perfecto? La Bufanda de Invierno Arctic Bliss es una elección ideal. Ya sea que sorprendas a un ser querido o te des un capricho, esta bufanda es un regalo atemporal y práctico que será apreciado durante los meses de invierno. Acepta el invierno con la Bufanda de Invierno Arctic Bliss, donde la calidez se encuentra con el estilo en perfecta armonía. Eleva tu guardarropa de invierno con este accesorio esencial que no solo te mantiene abrigado, sino que también añade un toque de sofisticación a tu conjunto para el clima frío.',
                    'meta-description' => 'descripción meta',
                    'meta-keywords'    => 'meta1, meta2, meta3',
                    'meta-title'       => 'Título Meta',
                    'name'             => 'Bufanda de Invierno Elegante Arctic Bliss',
                    'sort-description' => 'Experimenta el abrazo de la calidez y el estilo con nuestra Bufanda de Invierno Arctic Bliss. Elaborada a partir de una lujosa mezcla de acrílico y lana, esta bufanda acogedora está diseñada para mantenerte abrigado durante los días más fríos. Su diseño elegante y versátil, combinado con una longitud extra larga, ofrece opciones de estilo personalizables. Eleva tu guardarropa de invierno o deleita a alguien especial con este accesorio esencial para el invierno.',
                ],

                '3' => [
                    'description'      => 'Presentamos los Guantes de Invierno Arctic con Pantalla Táctil, donde la calidez, el estilo y la conectividad se unen para mejorar tu experiencia invernal. Elaborados con acrílico de alta calidad, estos guantes están diseñados para proporcionar una calidez y durabilidad excepcionales. Las puntas compatibles con pantallas táctiles te permiten estar conectado sin exponer tus manos al frío. Contesta llamadas, envía mensajes y navega por tus dispositivos sin esfuerzo, todo mientras mantienes tus manos abrigadas. El forro aislante añade una capa adicional de comodidad, haciendo de estos guantes tu elección ideal para enfrentar el frío del invierno. Ya sea que estés viajando, haciendo recados o disfrutando de actividades al aire libre, estos guantes te brindan la calidez y protección que necesitas. Los puños elásticos garantizan un ajuste seguro, evitando corrientes de aire frío y manteniendo los guantes en su lugar durante tus actividades diarias. El diseño elegante añade un toque de estilo a tu conjunto invernal, haciendo que estos guantes sean tan fashion como funcionales. Ideales para regalar o como un capricho para ti mismo, los Guantes de Invierno Arctic con Pantalla Táctil son un accesorio imprescindible para el individuo moderno. Di adiós a la molestia de quitarte los guantes para usar tus dispositivos y acepta la perfecta combinación de calidez, estilo y conectividad. Mantente conectado, mantente abrigado y mantente a la moda con los Guantes de Invierno Arctic con Pantalla Táctil, tu compañero confiable para conquistar la temporada de invierno con confianza.',
                    'meta-description' => 'descripción meta',
                    'meta-keywords'    => 'meta1, meta2, meta3',
                    'meta-title'       => 'Título Meta',
                    'name'             => 'Guantes de Invierno con Pantalla Táctil Arctic',
                    'sort-description' => 'Mantente conectado y abrigado con nuestros Guantes de Invierno con Pantalla Táctil Arctic. Estos guantes no solo están elaborados con acrílico de alta calidad para brindar calidez y durabilidad, sino que también cuentan con un diseño compatible con pantallas táctiles. Con un forro aislante, puños elásticos para un ajuste seguro y un aspecto elegante, estos guantes son perfectos para uso diario en condiciones frías.',
                ],

                '4' => [
                    'description'      => 'Presentamos los calcetines de mezcla de lana Arctic Warmth: tu compañero esencial para unos pies acogedores y cómodos durante las estaciones más frías. Elaborados con una mezcla premium de lana merino, acrílico, nylon y spandex, estos calcetines están diseñados para proporcionar un calor y una comodidad incomparables. La mezcla de lana garantiza que tus pies se mantengan calientes incluso en las temperaturas más frías, lo que los convierte en la elección perfecta para aventuras invernales o simplemente para estar cómodo en casa. La textura suave y acogedora de los calcetines ofrece una sensación de lujo en tu piel. Di adiós a los pies fríos mientras disfrutas del cálido abrazo proporcionado por estos calcetines de mezcla de lana. Diseñados para ser duraderos, los calcetines cuentan con un refuerzo en el talón y la punta, lo que les brinda mayor resistencia en las áreas de mayor desgaste. Esto garantiza que tus calcetines resistirán el paso del tiempo, brindando comodidad y calidez duraderas. La naturaleza transpirable del material evita el sobrecalentamiento, permitiendo que tus pies se mantengan cómodos y secos durante todo el día. Ya sea que te dirijas al aire libre para una caminata invernal o te relajes en casa, estos calcetines ofrecen el equilibrio perfecto entre calor y transpirabilidad. Versátiles y elegantes, estos calcetines de mezcla de lana son adecuados para diversas ocasiones. Combínalos con tus botas favoritas para un look invernal de moda o úsalos en casa para una comodidad absoluta. Eleva tu guardarropa de invierno y prioriza la comodidad con los calcetines de mezcla de lana Arctic Warmth. Mima tus pies con el lujo que se merecen y sumérgete en un mundo de comodidad que dura toda la temporada.',
                    'meta-description' => 'meta descripción',
                    'meta-keywords'    => 'meta1, meta2, meta3',
                    'meta-title'       => 'Título Meta',
                    'name'             => 'Calcetines de mezcla de lana Arctic Warmth',
                    'sort-description' => 'Experimenta el calor y la comodidad incomparables de nuestros calcetines de mezcla de lana Arctic Warmth. Elaborados con una mezcla de lana merino, acrílico, nylon y spandex, estos calcetines ofrecen una comodidad suprema para el clima frío. Con un refuerzo en el talón y la punta para mayor durabilidad, estos calcetines versátiles y elegantes son perfectos para diversas ocasiones.',
                ],

                '5' => [
                    'description'      => 'Presentamos el conjunto de accesorios de invierno Arctic Frost, tu solución ideal para mantenerte abrigado, elegante y conectado durante los días fríos de invierno. Este conjunto cuidadosamente seleccionado reúne cuatro accesorios esenciales de invierno para crear un conjunto armonioso. La lujosa bufanda, tejida con una mezcla de acrílico y lana, no solo agrega una capa de calor, sino que también aporta un toque de elegancia a tu guardarropa de invierno. El gorro de punto suave, elaborado con cuidado, promete mantenerte abrigado mientras añade un toque de moda a tu look. Pero eso no es todo: nuestro conjunto también incluye guantes compatibles con pantallas táctiles. Mantente conectado sin sacrificar el calor mientras navegas por tus dispositivos sin esfuerzo. Ya sea que estés contestando llamadas, enviando mensajes o capturando momentos de invierno en tu teléfono inteligente, estos guantes garantizan comodidad sin comprometer el estilo. La textura suave y acogedora de los calcetines ofrece una sensación de lujo en tu piel. Di adiós a los pies fríos mientras disfrutas del cálido abrazo proporcionado por estos calcetines de mezcla de lana. El conjunto de accesorios de invierno Arctic Frost no se trata solo de funcionalidad; es una declaración de moda invernal. Cada pieza está diseñada no solo para protegerte del frío, sino también para elevar tu estilo durante la temporada helada. Los materiales elegidos para este conjunto priorizan tanto la durabilidad como la comodidad, asegurando que puedas disfrutar del paisaje invernal con estilo. Ya sea que te estés dando un capricho o buscando el regalo perfecto, el conjunto de accesorios de invierno Arctic Frost es una elección versátil. Deleita a alguien especial durante la temporada navideña o eleva tu propio guardarropa de invierno con este conjunto elegante y funcional. Atrévete con la helada con confianza, sabiendo que tienes los accesorios perfectos para mantenerte abrigado y elegante.',
                    'meta-description' => 'meta descripción',
                    'meta-keywords'    => 'meta1, meta2, meta3',
                    'meta-title'       => 'Título Meta',
                    'name'             => 'Conjunto de accesorios de invierno Arctic Frost',
                    'sort-description' => 'Atrévete con el frío invierno con nuestro conjunto de accesorios de invierno Arctic Frost. Este conjunto incluye una lujosa bufanda, un gorro acogedor, guantes compatibles con pantallas táctiles y calcetines de mezcla de lana. Elegante y funcional, este conjunto está elaborado con materiales de alta calidad, garantizando tanto durabilidad como comodidad. Eleva tu guardarropa de invierno o deleita a alguien especial con esta opción de regalo perfecta.',
                ],

                '6' => [
                    'description'      => 'Presentamos el conjunto de accesorios de invierno Arctic Frost, tu solución ideal para mantenerte abrigado, elegante y conectado durante los días fríos de invierno. Este conjunto cuidadosamente seleccionado reúne cuatro accesorios esenciales de invierno para crear un conjunto armonioso. La lujosa bufanda, tejida con una mezcla de acrílico y lana, no solo agrega una capa de calor, sino que también aporta un toque de elegancia a tu guardarropa de invierno. El gorro de punto suave, elaborado con cuidado, promete mantenerte abrigado mientras añade un toque de moda a tu look. Pero eso no es todo: nuestro conjunto también incluye guantes compatibles con pantallas táctiles. Mantente conectado sin sacrificar el calor mientras navegas por tus dispositivos sin esfuerzo. Ya sea que estés contestando llamadas, enviando mensajes o capturando momentos de invierno en tu teléfono inteligente, estos guantes garantizan comodidad sin comprometer el estilo. La textura suave y acogedora de los calcetines ofrece una sensación de lujo en tu piel. Di adiós a los pies fríos mientras disfrutas del cálido abrazo proporcionado por estos calcetines de mezcla de lana. El conjunto de accesorios de invierno Arctic Frost no se trata solo de funcionalidad; es una declaración de moda invernal. Cada pieza está diseñada no solo para protegerte del frío, sino también para elevar tu estilo durante la temporada helada. Los materiales elegidos para este conjunto priorizan tanto la durabilidad como la comodidad, asegurando que puedas disfrutar del paisaje invernal con estilo. Ya sea que te estés dando un capricho o buscando el regalo perfecto, el conjunto de accesorios de invierno Arctic Frost es una elección versátil. Deleita a alguien especial durante la temporada navideña o eleva tu propio guardarropa de invierno con este conjunto elegante y funcional. Atrévete con la helada con confianza, sabiendo que tienes los accesorios perfectos para mantenerte abrigado y elegante.',
                    'meta-description' => 'meta descripción',
                    'meta-keywords'    => 'meta1, meta2, meta3',
                    'meta-title'       => 'Título Meta',
                    'name'             => 'Conjunto de accesorios de invierno Arctic Frost',
                    'sort-description' => 'Atrévete con el frío invierno con nuestro conjunto de accesorios de invierno Arctic Frost. Este conjunto incluye una lujosa bufanda, un gorro acogedor, guantes compatibles con pantallas táctiles y calcetines de mezcla de lana. Elegante y funcional, este conjunto está elaborado con materiales de alta calidad, garantizando tanto durabilidad como comodidad. Eleva tu guardarropa de invierno o deleita a alguien especial con esta opción de regalo perfecta.',
                ],

                '7' => [
                    'description'      => 'Presentamos la chaqueta acolchada con capucha OmniHeat para hombre, tu solución ideal para mantenerte abrigado y a la moda durante las estaciones más frías. Esta chaqueta está diseñada con durabilidad y calidez en mente, asegurando que se convierta en tu compañera de confianza. El diseño con capucha no solo agrega un toque de estilo, sino que también proporciona calor adicional, protegiéndote de los vientos fríos y el clima. Las mangas completas ofrecen cobertura completa, asegurando que te mantengas abrigado desde el hombro hasta la muñeca. Equipada con bolsillos interiores, esta chaqueta acolchada proporciona comodidad para llevar tus objetos esenciales o mantener tus manos calientes. El relleno sintético aislante ofrece mayor calidez, lo que la hace ideal para combatir los días y las noches frías. Fabricada con una resistente carcasa y forro de poliéster, esta chaqueta está construida para resistir los elementos y perdurar en el tiempo. Disponible en 5 colores atractivos, puedes elegir el que se adapte a tu estilo y preferencia. Versátil y funcional, la chaqueta acolchada con capucha OmniHeat para hombre es adecuada para diversas ocasiones, ya sea que vayas al trabajo, salgas de forma casual o asistas a un evento al aire libre. Experimenta la combinación perfecta de estilo, comodidad y funcionalidad con la chaqueta acolchada con capucha OmniHeat para hombre. Eleva tu guardarropa de invierno y mantente abrigado mientras disfrutas del aire libre. Vence el frío con estilo y haz una declaración con esta pieza esencial.',
                    'meta-description' => 'meta descripción',
                    'meta-keywords'    => 'meta1, meta2, meta3',
                    'meta-title'       => 'Título Meta',
                    'name'             => 'Chaqueta acolchada con capucha OmniHeat para hombre',
                    'sort-description' => 'Mantente abrigado y a la moda con nuestra chaqueta acolchada con capucha OmniHeat para hombre. Esta chaqueta está diseñada para proporcionar calor máximo y cuenta con bolsillos interiores para mayor comodidad. El material aislante asegura que te mantengas abrigado en clima frío. Disponible en 5 colores atractivos, lo que la convierte en una opción versátil para diversas ocasiones.',
                ],

                '8' => [
                    'description'      => 'Presentamos la chaqueta acolchada con capucha OmniHeat para hombre, tu solución ideal para mantenerte abrigado y a la moda durante las estaciones más frías. Esta chaqueta está diseñada con durabilidad y calidez en mente, asegurando que se convierta en tu compañera de confianza. El diseño con capucha no solo agrega un toque de estilo, sino que también proporciona calor adicional, protegiéndote de los vientos fríos y el clima. Las mangas completas ofrecen cobertura completa, asegurando que te mantengas abrigado desde el hombro hasta la muñeca. Equipada con bolsillos interiores, esta chaqueta acolchada proporciona comodidad para llevar tus objetos esenciales o mantener tus manos calientes. El relleno sintético aislante ofrece mayor calidez, lo que la hace ideal para combatir los días y las noches frías. Fabricada con una resistente carcasa y forro de poliéster, esta chaqueta está construida para resistir los elementos y perdurar en el tiempo. Disponible en 5 colores atractivos, puedes elegir el que se adapte a tu estilo y preferencia. Versátil y funcional, la chaqueta acolchada con capucha OmniHeat para hombre es adecuada para diversas ocasiones, ya sea que vayas al trabajo, salgas de forma casual o asistas a un evento al aire libre. Experimenta la combinación perfecta de estilo, comodidad y funcionalidad con la chaqueta acolchada con capucha OmniHeat para hombre. Eleva tu guardarropa de invierno y mantente abrigado mientras disfrutas del aire libre. Vence el frío con estilo y haz una declaración con esta pieza esencial.',
                    'meta-description' => 'meta descripción',
                    'meta-keywords'    => 'meta1, meta2, meta3',
                    'meta-title'       => 'Título Meta',
                    'name'             => 'Chaqueta acolchada con capucha OmniHeat para hombre - Azul-Amarillo-M',
                    'sort-description' => 'Mantente abrigado y a la moda con nuestra chaqueta acolchada con capucha OmniHeat para hombre. Esta chaqueta está diseñada para proporcionar calor máximo y cuenta con bolsillos interiores para mayor comodidad. El material aislante asegura que te mantengas abrigado en clima frío. Disponible en 5 colores atractivos, lo que la convierte en una opción versátil para diversas ocasiones.',
                ],

                '9' => [
                    'description'      => 'Presentamos la Chaqueta Acolchada con Capucha OmniHeat para Hombre, tu solución ideal para mantenerte abrigado y a la moda durante las estaciones más frías. Esta chaqueta está diseñada con durabilidad y calidez en mente, asegurando que se convierta en tu compañera de confianza. El diseño con capucha no solo agrega un toque de estilo, sino que también proporciona calor adicional, protegiéndote de los vientos fríos y el clima. Las mangas largas ofrecen cobertura completa, asegurando que te mantengas abrigado desde el hombro hasta la muñeca. Equipada con bolsillos insertados, esta chaqueta acolchada brinda comodidad para llevar tus elementos esenciales o mantener tus manos calientes. El relleno sintético aislante ofrece mayor calidez, lo que la hace ideal para enfrentar días y noches frías. Hecha de una resistente carcasa y forro de poliéster, esta chaqueta está construida para durar y resistir los elementos. Disponible en 5 colores atractivos, puedes elegir el que se adapte a tu estilo y preferencia. Versátil y funcional, la Chaqueta Acolchada con Capucha OmniHeat para Hombre es adecuada para diversas ocasiones, ya sea que vayas al trabajo, salgas de manera informal o asistas a un evento al aire libre. Experimenta la combinación perfecta de estilo, comodidad y funcionalidad con la Chaqueta Acolchada con Capucha OmniHeat para Hombre. Eleva tu guardarropa de invierno y mantente abrigado mientras disfrutas del aire libre. Vence el frío con estilo y haz una declaración con esta pieza esencial.',
                    'meta-description' => 'descripción meta',
                    'meta-keywords'    => 'meta1, meta2, meta3',
                    'meta-title'       => 'Título Meta',
                    'name'             => 'Chaqueta Acolchada con Capucha OmniHeat para Hombre-Azul-Amarillo-L',
                    'sort-description' => 'Mantente abrigado y a la moda con nuestra Chaqueta Acolchada con Capucha OmniHeat para Hombre. Esta chaqueta está diseñada para proporcionar calor máximo y cuenta con bolsillos insertados para mayor comodidad. El material aislante asegura que te mantengas abrigado en clima frío. Disponible en 5 colores atractivos, lo que la convierte en una opción versátil para diversas ocasiones.',
                ],

                '10' => [
                    'description'      => 'Presentamos la Chaqueta Acolchada con Capucha OmniHeat para Hombre, tu solución ideal para mantenerte abrigado y a la moda durante las estaciones más frías. Esta chaqueta está diseñada con durabilidad y calidez en mente, asegurando que se convierta en tu compañera de confianza. El diseño con capucha no solo agrega un toque de estilo, sino que también proporciona calor adicional, protegiéndote de los vientos fríos y el clima. Las mangas largas ofrecen cobertura completa, asegurando que te mantengas abrigado desde el hombro hasta la muñeca. Equipada con bolsillos insertados, esta chaqueta acolchada brinda comodidad para llevar tus elementos esenciales o mantener tus manos calientes. El relleno sintético aislante ofrece mayor calidez, lo que la hace ideal para enfrentar días y noches frías. Hecha de una resistente carcasa y forro de poliéster, esta chaqueta está construida para durar y resistir los elementos. Disponible en 5 colores atractivos, puedes elegir el que se adapte a tu estilo y preferencia. Versátil y funcional, la Chaqueta Acolchada con Capucha OmniHeat para Hombre es adecuada para diversas ocasiones, ya sea que vayas al trabajo, salgas de manera informal o asistas a un evento al aire libre. Experimenta la combinación perfecta de estilo, comodidad y funcionalidad con la Chaqueta Acolchada con Capucha OmniHeat para Hombre. Eleva tu guardarropa de invierno y mantente abrigado mientras disfrutas del aire libre. Vence el frío con estilo y haz una declaración con esta pieza esencial.',
                    'meta-description' => 'descripción meta',
                    'meta-keywords'    => 'meta1, meta2, meta3',
                    'meta-title'       => 'Título Meta',
                    'name'             => 'Chaqueta Acolchada con Capucha OmniHeat para Hombre-Azul-Verde-M',
                    'sort-description' => 'Mantente abrigado y a la moda con nuestra Chaqueta Acolchada con Capucha OmniHeat para Hombre. Esta chaqueta está diseñada para proporcionar calor máximo y cuenta con bolsillos insertados para mayor comodidad. El material aislante asegura que te mantengas abrigado en clima frío. Disponible en 5 colores atractivos, lo que la convierte en una opción versátil para diversas ocasiones.',
                ],

                '11' => [
                    'description'      => 'Presentamos la Chaqueta Acolchada con Capucha OmniHeat para Hombre, tu solución ideal para mantenerte abrigado y a la moda durante las estaciones más frías. Esta chaqueta está diseñada con durabilidad y calidez en mente, asegurando que se convierta en tu compañera de confianza. El diseño con capucha no solo agrega un toque de estilo, sino que también proporciona calor adicional, protegiéndote de los vientos fríos y el clima. Las mangas largas ofrecen cobertura completa, asegurando que te mantengas abrigado desde el hombro hasta la muñeca. Equipada con bolsillos insertados, esta chaqueta acolchada brinda comodidad para llevar tus elementos esenciales o mantener tus manos calientes. El relleno sintético aislante ofrece mayor calidez, lo que la hace ideal para enfrentar días y noches frías. Hecha de una resistente carcasa y forro de poliéster, esta chaqueta está construida para durar y resistir los elementos. Disponible en 5 colores atractivos, puedes elegir el que se adapte a tu estilo y preferencia. Versátil y funcional, la Chaqueta Acolchada con Capucha OmniHeat para Hombre es adecuada para diversas ocasiones, ya sea que vayas al trabajo, salgas de manera informal o asistas a un evento al aire libre. Experimenta la combinación perfecta de estilo, comodidad y funcionalidad con la Chaqueta Acolchada con Capucha OmniHeat para Hombre. Eleva tu guardarropa de invierno y mantente abrigado mientras disfrutas del aire libre. Vence el frío con estilo y haz una declaración con esta pieza esencial.',
                    'meta-description' => 'descripción meta',
                    'meta-keywords'    => 'meta1, meta2, meta3',
                    'meta-title'       => 'Título Meta',
                    'name'             => 'Chaqueta Acolchada con Capucha OmniHeat para Hombre-Azul-Verde-L',
                    'sort-description' => 'Mantente abrigado y a la moda con nuestra Chaqueta Acolchada con Capucha OmniHeat para Hombre. Esta chaqueta está diseñada para proporcionar calor máximo y cuenta con bolsillos insertados para mayor comodidad. El material aislante asegura que te mantengas abrigado en clima frío. Disponible en 5 colores atractivos, lo que la convierte en una opción versátil para diversas ocasiones.',
                ],
            ],

            'product-bundle-option-translations' => [
                '1' => [
                    'label' => 'Opción de Paquete 1',
                ],

                '2' => [
                    'label' => 'Opción de Paquete 1',
                ],

                '3' => [
                    'label' => 'Opción de Paquete 2',
                ],

                '4' => [
                    'label' => 'Opción de Paquete 2',
                ],
            ],
        ],
    ],

    'installer' => [
        'index' => [
            'create-administrator' => [
                'admin'            => 'Administrador',
                'bagisto'          => 'Bagisto',
                'confirm-password' => 'Confirmar Contraseña',
                'email'            => 'Correo Electrónico',
                'email-address'    => 'admin@example.com',
                'password'         => 'Contraseña',
                'title'            => 'Crear Administrador',
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
                'warning-message'             => '¡Cuidado! Los ajustes de su idioma del sistema predeterminado y la moneda predeterminada son permanentes y no se pueden cambiar una vez establecidos.',
                'zambian-kwacha'              => 'Kwacha de Zambia (ZMW)',
            ],

            'sample-products' => [
                'download-sample' => 'descargar muestra',
                'no'              => 'No',
                'sample-products' => 'Productos de muestra',
                'title'           => 'Productos de muestra',
                'yes'             => 'Sí',
            ],

            'installation-processing' => [
                'bagisto'      => 'Instalación de Bagisto',
                'bagisto-info' => 'Creando las tablas de la base de datos, esto puede tomar algunos momentos',
                'title'        => 'Instalación',
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
                'welcome-title' => 'Bienvenido a Bagisto',
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
            'installation-description' => 'La instalación de Bagisto generalmente implica varios pasos. Aquí hay un esquema general del proceso de instalación para Bagisto',
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
