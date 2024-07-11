<?php

return [
    'seeders' => [
        'attribute' => [
            'attribute-families' => [
                'default' => 'Padrão',
            ],

            'attribute-groups' => [
                'description'      => 'Descrição',
                'general'          => 'Geral',
                'inventories'      => 'Inventários',
                'meta-description' => 'Meta Descrição',
                'price'            => 'Preço',
                'settings'         => 'Configurações',
                'shipping'         => 'Envio',
            ],

            'attributes' => [
                'brand'                => 'Marca',
                'color'                => 'Cor',
                'cost'                 => 'Custo',
                'description'          => 'Descrição',
                'featured'             => 'Destaque',
                'guest-checkout'       => 'Check-out de Visitante',
                'height'               => 'Altura',
                'length'               => 'Comprimento',
                'manage-stock'         => 'Gerenciar Estoque',
                'meta-description'     => 'Meta Descrição',
                'meta-keywords'        => 'Meta Palavras-chave',
                'meta-title'           => 'Meta Título',
                'name'                 => 'Nome',
                'new'                  => 'Novo',
                'price'                => 'Preço',
                'product-number'       => 'Número do Produto',
                'short-description'    => 'Descrição Curta',
                'size'                 => 'Tamanho',
                'sku'                  => 'SKU',
                'special-price'        => 'Preço Especial',
                'special-price-from'   => 'Preço Especial De',
                'special-price-to'     => 'Preço Especial Até',
                'status'               => 'Status',
                'tax-category'         => 'Categoria de Imposto',
                'url-key'              => 'Chave de URL',
                'visible-individually' => 'Visível Individualmente',
                'weight'               => 'Peso',
                'width'                => 'Largura',
            ],

            'attribute-options' => [
                'black'  => 'Preto',
                'green'  => 'Verde',
                'l'      => 'L',
                'm'      => 'M',
                'red'    => 'Vermelho',
                's'      => 'S',
                'white'  => 'Branco',
                'xl'     => 'XL',
                'yellow' => 'Amarelo',
            ],
        ],

        'category' => [
            'categories' => [
                'description' => 'Descrição da Categoria Raiz',
                'name'        => 'Raiz',
            ],
        ],

        'cms' => [
            'pages' => [
                'about-us' => [
                    'content' => 'Conteúdo da Página Sobre Nós',
                    'title'   => 'Sobre Nós',
                ],

                'contact-us' => [
                    'content' => 'Conteúdo da Página de Contato',
                    'title'   => 'Contato',
                ],

                'customer-service' => [
                    'content' => 'Conteúdo da Página de Atendimento ao Cliente',
                    'title'   => 'Atendimento ao Cliente',
                ],

                'payment-policy' => [
                    'content' => 'Conteúdo da Página de Política de Pagamento',
                    'title'   => 'Política de Pagamento',
                ],

                'privacy-policy' => [
                    'content' => 'Conteúdo da Página de Política de Privacidade',
                    'title'   => 'Política de Privacidade',
                ],

                'refund-policy' => [
                    'content' => 'Conteúdo da Página de Reembolso',
                    'title'   => 'Política de Reembolso',
                ],

                'return-policy' => [
                    'content' => 'Conteúdo da Página de Devolução',
                    'title'   => 'Política de Devolução',
                ],

                'shipping-policy' => [
                    'content' => 'Conteúdo da Página de Política de Envio',
                    'title'   => 'Política de Envio',
                ],

                'terms-conditions' => [
                    'content' => 'Conteúdo da Página de Termos e Condições',
                    'title'   => 'Termos e Condições',
                ],

                'terms-of-use' => [
                    'content' => 'Conteúdo da Página de Termos de Uso',
                    'title'   => 'Termos de Uso',
                ],

                'whats-new' => [
                    'content' => 'Conteúdo da Página de Novidades',
                    'title'   => 'Novidades',
                ],
            ],
        ],

        'core' => [
            'channels' => [
                'name'             => 'Padrão',
                'meta-title'       => 'Loja de Demonstração',
                'meta-keywords'    => 'Palavras-chave de Meta da Loja de Demonstração',
                'meta-description' => 'Descrição de Meta da Loja de Demonstração',
            ],

            'currencies' => [
                'AED' => 'Dirham dos Emirados Árabes Unidos',
                'ARS' => 'Peso Argentino',
                'AUD' => 'Dólar Australiano',
                'BDT' => 'Taka de Bangladesh',
                'BRL' => 'Real Brasileiro',
                'CAD' => 'Dólar Canadense',
                'CHF' => 'Franco Suíço',
                'CLP' => 'Peso Chileno',
                'CNY' => 'Yuan Chinês',
                'COP' => 'Peso Colombiano',
                'CZK' => 'Coroa Tcheca',
                'DKK' => 'Coroa Dinamarquesa',
                'DZD' => 'Dinar Argelino',
                'EGP' => 'Libra Egípcia',
                'EUR' => 'Euro',
                'FJD' => 'Dólar de Fiji',
                'GBP' => 'Libra Esterlina Britânica',
                'HKD' => 'Dólar de Hong Kong',
                'HUF' => 'Florim Húngaro',
                'IDR' => 'Rupia Indonésia',
                'ILS' => 'Novo Shekel Israelense',
                'INR' => 'Rupia Indiana',
                'JOD' => 'Dinar Jordaniano',
                'JPY' => 'Iene Japonês',
                'KRW' => 'Won Sul-Coreano',
                'KWD' => 'Dinar Kuwaitiano',
                'KZT' => 'Tenge Cazaque',
                'LBP' => 'Libra Libanesa',
                'LKR' => 'Rupia do Sri Lanka',
                'LYD' => 'Dinar Líbio',
                'MAD' => 'Dirham Marroquino',
                'MUR' => 'Rupia Mauriciana',
                'MXN' => 'Peso Mexicano',
                'MYR' => 'Ringgit Malaio',
                'NGN' => 'Naira Nigeriana',
                'NOK' => 'Coroa Norueguesa',
                'NPR' => 'Rupia Nepalesa',
                'NZD' => 'Dólar Neozelandês',
                'OMR' => 'Rial Omanense',
                'PAB' => 'Balboa Panamenho',
                'PEN' => 'Novo Sol Peruano',
                'PHP' => 'Peso Filipino',
                'PKR' => 'Rupia Paquistanesa',
                'PLN' => 'Zloty Polonês',
                'PYG' => 'Guarani Paraguaio',
                'QAR' => 'Rial Catarense',
                'RON' => 'Leu Romeno',
                'RUB' => 'Rublo Russo',
                'SAR' => 'Riyal Saudita',
                'SEK' => 'Coroa Sueca',
                'SGD' => 'Dólar de Singapura',
                'THB' => 'Baht Tailandês',
                'TND' => 'Dinar Tunisiano',
                'TRY' => 'Lira Turca',
                'TWD' => 'Novo Dólar Taiwanês',
                'UAH' => 'Hryvnia Ucraniana',
                'USD' => 'Dólar dos Estados Unidos',
                'UZS' => 'Som Uzbeque',
                'VEF' => 'Bolívar Venezuelano',
                'VND' => 'Dong Vietnamita',
                'XAF' => 'Franco CFA BEAC',
                'XOF' => 'Franco CFA BCEAO',
                'ZAR' => 'Rand Sul-Africano',
                'ZMW' => 'Kwacha Zambiano',
            ],

            'locales' => [
                'ar'    => 'Árabe',
                'bn'    => 'Bengali',
                'de'    => 'Alemão',
                'en'    => 'Inglês',
                'es'    => 'Espanhol',
                'fa'    => 'Persa',
                'fr'    => 'Francês',
                'he'    => 'Hebraico',
                'hi_IN' => 'Hindi',
                'it'    => 'Italiano',
                'ja'    => 'Japonês',
                'nl'    => 'Holandês',
                'pl'    => 'Polonês',
                'pt_BR' => 'Português do Brasil',
                'ru'    => 'Russo',
                'sin'   => 'Cingalês',
                'tr'    => 'Turco',
                'uk'    => 'Ucraniano',
                'zh_CN' => 'Chinês',
            ],
        ],

        'customer' => [
            'customer-groups' => [
                'general'   => 'Geral',
                'guest'     => 'Visitante',
                'wholesale' => 'Atacado',
            ],
        ],

        'inventory' => [
            'inventory-sources' => [
                'name' => 'Padrão',
            ],
        ],

        'shop' => [
            'theme-customizations' => [
                'all-products' => [
                    'name'    => 'Todos os Produtos',

                    'options' => [
                        'title' => 'Todos os Produtos',
                    ],
                ],

                'bold-collections' => [
                    'content' => [
                        'btn-title'   => 'Ver Coleções',
                        'description' => 'Apresentamos nossas Novas Coleções Audaciosas! Eleve seu estilo com designs ousados e declarações vibrantes. Explore padrões impressionantes e cores audaciosas que redefinem seu guarda-roupa. Prepare-se para abraçar o extraordinário!',
                        'title'       => 'Prepare-se para as nossas novas Coleções Audaciosas!',
                    ],

                    'name' => 'Coleções Audaciosas',
                ],

                'categories-collections' => [
                    'name' => 'Coleções de Categorias',
                ],

                'featured-collections' => [
                    'name' => 'Coleções em Destaque',

                    'options' => [
                        'title' => 'Produtos em Destaque',
                    ],
                ],

                'footer-links' => [
                    'name' => 'Links do Rodapé',

                    'options' => [
                        'about-us'         => 'Sobre Nós',
                        'contact-us'       => 'Fale Conosco',
                        'customer-service' => 'Atendimento ao Cliente',
                        'payment-policy'   => 'Política de Pagamento',
                        'privacy-policy'   => 'Política de Privacidade',
                        'refund-policy'    => 'Política de Reembolso',
                        'return-policy'    => 'Política de Devolução',
                        'shipping-policy'  => 'Política de Envio',
                        'terms-conditions' => 'Termos e Condições',
                        'terms-of-use'     => 'Termos de Uso',
                        'whats-new'        => 'O que há de Novo',
                    ],
                ],

                'game-container' => [
                    'content' => [
                        'sub-title-1' => 'Nossas Coleções',
                        'sub-title-2' => 'Nossas Coleções',
                        'title'       => 'O jogo com nossas novas adições!',
                    ],

                    'name' => 'Contêiner de Jogo',
                ],

                'image-carousel' => [
                    'name' => 'Carrossel de Imagens',

                    'sliders' => [
                        'title' => 'Prepare-se para a Nova Coleção',
                    ],
                ],

                'new-products' => [
                    'name' => 'Novos Produtos',

                    'options' => [
                        'title' => 'Novos Produtos',
                    ],
                ],

                'offer-information' => [
                    'content' => [
                        'title' => 'GANHE ATÉ 40% DE DESCONTO no seu 1º pedido COMPRE AGORA',
                    ],

                    'name' => 'Informações de Oferta',
                ],

                'services-content' => [
                    'description' => [
                        'emi-available-info'   => 'EMI sem custo disponível em todos os principais cartões de crédito',
                        'free-shipping-info'   => 'Aproveite o frete grátis em todos os pedidos',
                        'product-replace-info' => 'Substituição fácil de produto disponível!',
                        'time-support-info'    => 'Suporte dedicado 24/7 via chat e e-mail',
                    ],

                    'name' => 'Conteúdo de serviços',

                    'title' => [
                        'free-shipping'   => 'Frete grátis',
                        'product-replace' => 'Substituição de produto',
                        'emi-available'   => 'EMI disponível',
                        'time-support'    => 'Suporte 24/7',
                    ],
                ],

                'top-collections' => [
                    'content' => [
                        'sub-title-1' => 'Nossas Coleções',
                        'sub-title-2' => 'Nossas Coleções',
                        'sub-title-3' => 'Nossas Coleções',
                        'sub-title-4' => 'Nossas Coleções',
                        'sub-title-5' => 'Nossas Coleções',
                        'sub-title-6' => 'Nossas Coleções',
                        'title'       => 'O jogo com nossas novas adições!',
                    ],

                    'name' => 'Melhores Coleções',
                ],
            ],
        ],

        'user' => [
            'roles' => [
                'description' => 'Este papel concede a todos os usuários acesso total',
                'name'        => 'Administrador',
            ],

            'users' => [
                'name' => 'Exemplo',
            ],
        ],

        'sample-categories' => [
            'category-translation' => [
                '2' => [
                    'description'      => 'Descrição da categoria masculina',
                    'meta-description' => 'Meta descrição da categoria masculina',
                    'meta-keywords'    => 'Meta palavras-chave da categoria masculina',
                    'meta-title'       => 'Meta título da categoria masculina',
                    'name'             => 'Homens',
                    'slug'             => 'homens',
                ],

                '3' => [
                    'description'      => 'Descrição da categoria de roupas de inverno',
                    'meta-description' => 'Meta descrição da categoria de roupas de inverno',
                    'meta-keywords'    => 'Meta palavras-chave da categoria de roupas de inverno',
                    'meta-title'       => 'Meta título da categoria de roupas de inverno',
                    'name'             => 'Roupa de inverno',
                    'slug'             => 'roupa de inverno',
                ],
            ],
        ],

        'sample-products' => [
            'product-flat' => [
                '1' => [
                    'description'       => 'O Arctic Cozy Knit Beanie é a sua solução para se manter quente, confortável e elegante durante os meses mais frios. Feito de uma mistura macia e durável de acrílico, este gorro foi projetado para proporcionar um ajuste aconchegante. O design clássico o torna adequado tanto para homens quanto para mulheres, oferecendo um acessório versátil que combina com vários estilos. Seja para um dia casual na cidade ou para aproveitar o ar livre, este gorro adiciona um toque de conforto e calor ao seu visual. O material macio e respirável garante que você fique aconchegante sem sacrificar o estilo. O Arctic Cozy Knit Beanie não é apenas um acessório; é uma declaração de moda de inverno. Sua simplicidade facilita a combinação com diferentes roupas, tornando-o um item essencial em seu guarda-roupa de inverno. Ideal para presentear ou se presentear, este gorro é uma adição atenciosa a qualquer conjunto de inverno. É um acessório versátil que vai além da funcionalidade, adicionando um toque de calor e estilo ao seu visual. Abraçe a essência do inverno com o Arctic Cozy Knit Beanie. Seja para um dia casual ou para enfrentar os elementos, deixe este gorro ser seu companheiro de conforto e estilo. Eleve seu guarda-roupa de inverno com este acessório clássico que combina calor com um senso atemporal de moda.',
                    'meta-description'  => 'meta descrição',
                    'meta-keywords'     => 'meta1, meta2, meta3',
                    'meta-title'        => 'Título Meta',
                    'name'              => 'Gorro Unissex Arctic Cozy Knit',
                    'short-description' => 'Abrace os dias frios com estilo com o nosso Gorro Arctic Cozy Knit. Feito de uma mistura macia e durável de acrílico, este gorro clássico oferece calor e versatilidade. Adequado tanto para homens quanto para mulheres, é o acessório ideal para uso casual ou ao ar livre. Eleve seu guarda-roupa de inverno ou presenteie alguém especial com este gorro essencial.',
                ],

                '2' => [
                    'description'       => 'A Cachecol Arctic Bliss Winter é mais do que um acessório para o clima frio; é uma declaração de calor, conforto e estilo para a temporada de inverno. Feito com cuidado a partir de uma luxuosa mistura de acrílico e lã, este cachecol foi projetado para mantê-lo aconchegante e confortável mesmo nas temperaturas mais frias. A textura macia e felpuda não apenas proporciona isolamento contra o frio, mas também adiciona um toque de luxo ao seu guarda-roupa de inverno. O design do Cachecol Arctic Bliss Winter é elegante e versátil, tornando-o um complemento perfeito para uma variedade de roupas de inverno. Seja para se vestir para uma ocasião especial ou adicionar uma camada elegante ao seu visual diário, este cachecol complementa seu estilo sem esforço. O comprimento extra longo do cachecol oferece opções de estilo personalizáveis. Enrole-o para mais calor, deixe-o solto para um visual casual ou experimente diferentes nós para expressar seu estilo único. Essa versatilidade o torna um acessório indispensável para a temporada de inverno. Procurando o presente perfeito? O Cachecol Arctic Bliss Winter é uma escolha ideal. Seja surpreendendo alguém especial ou se presenteando, este cachecol é um presente atemporal e prático que será valorizado durante os meses de inverno. Abraçe o inverno com o Cachecol Arctic Bliss Winter, onde calor encontra estilo em perfeita harmonia. Eleve seu guarda-roupa de inverno com este acessório essencial que não apenas mantém você aquecido, mas também adiciona um toque de sofisticação ao seu conjunto para o clima frio.',
                    'meta-description'  => 'meta descrição',
                    'meta-keywords'     => 'meta1, meta2, meta3',
                    'meta-title'        => 'Título Meta',
                    'name'              => 'Cachecol Estiloso Arctic Bliss Winter',
                    'short-description' => 'Experimente o abraço de calor e estilo com o nosso Cachecol Arctic Bliss Winter. Feito de uma luxuosa mistura de acrílico e lã, este cachecol aconchegante foi projetado para mantê-lo confortável durante os dias mais frios. Seu design elegante e versátil, combinado com um comprimento extra longo, oferece opções de estilo personalizáveis. Eleve seu guarda-roupa de inverno ou presenteie alguém especial com este acessório de inverno essencial.',
                ],

                '3' => [
                    'description'       => 'Apresentamos as Luvas de Tela Sensível ao Toque Ártica - onde calor, estilo e conectividade se encontram para melhorar sua experiência no inverno. Feitas de acrílico de alta qualidade, essas luvas são projetadas para fornecer calor excepcional e durabilidade. As pontas compatíveis com tela sensível ao toque permitem que você fique conectado sem expor as mãos ao frio. Atenda chamadas, envie mensagens e navegue em seus dispositivos sem esforço, mantendo as mãos aconchegantes. O revestimento isolante adiciona uma camada extra de conforto, tornando essas luvas a escolha ideal para enfrentar o frio do inverno. Seja para se deslocar, fazer recados ou desfrutar de atividades ao ar livre, essas luvas fornecem o calor e a proteção de que você precisa. Os punhos elásticos garantem um ajuste seguro, evitando correntes de ar frio e mantendo as luvas no lugar durante suas atividades diárias. O design elegante adiciona um toque de estilo ao seu conjunto de inverno, tornando essas luvas tão fashion quanto funcionais. Ideal para presentear ou como um mimo para você mesmo, as Luvas de Tela Sensível ao Toque Ártica são um acessório indispensável para o indivíduo moderno. Diga adeus ao incômodo de remover as luvas para usar seus dispositivos e abrace a combinação perfeita de calor, estilo e conectividade. Fique conectado, fique aquecido e fique elegante com as Luvas de Tela Sensível ao Toque Ártica - seu companheiro confiável para enfrentar a temporada de inverno com confiança.',
                    'meta-description'  => 'descrição meta',
                    'meta-keywords'     => 'meta1, meta2, meta3',
                    'meta-title'        => 'Título Meta',
                    'name'              => 'Luvas de Tela Sensível ao Toque Ártica',
                    'short-description' => 'Fique conectado e aquecido com nossas Luvas de Tela Sensível ao Toque Ártica. Essas luvas são feitas não apenas de acrílico de alta qualidade para calor e durabilidade, mas também possuem um design compatível com tela sensível ao toque. Com um revestimento isolante, punhos elásticos para um ajuste seguro e um visual elegante, essas luvas são perfeitas para uso diário em condições frias.',
                ],

                '4' => [
                    'description'       => 'Apresentamos as Meias de Lã Arctic Warmth Blend - seu companheiro essencial para pés aconchegantes e confortáveis durante as estações mais frias. Feitas de uma mistura premium de lã Merino, acrílico, nylon e spandex, essas meias são projetadas para fornecer calor e conforto incomparáveis. A mistura de lã garante que seus pés fiquem quentes mesmo nas temperaturas mais frias, tornando essas meias a escolha perfeita para aventuras de inverno ou simplesmente para ficar aconchegante em casa. A textura macia e aconchegante das meias oferece uma sensação luxuosa contra a pele. Diga adeus aos pés frios enquanto você abraça o calor macio fornecido por essas meias de lã. Projetadas para durabilidade, as meias possuem um calcanhar e dedo do pé reforçados, adicionando resistência extra às áreas de maior desgaste. Isso garante que suas meias resistam ao teste do tempo, proporcionando conforto e aconchego duradouros. A natureza respirável do material evita o superaquecimento, permitindo que seus pés fiquem confortáveis e secos ao longo do dia. Seja para uma caminhada de inverno ao ar livre ou para relaxar em casa, essas meias oferecem o equilíbrio perfeito entre calor e respirabilidade. Versáteis e estilosas, essas meias de lã são adequadas para várias ocasiões. Combine-as com suas botas favoritas para um visual de inverno fashion ou use-as em casa para o máximo conforto. Eleve seu guarda-roupa de inverno e priorize o conforto com as Meias de Lã Arctic Warmth Blend. Mime seus pés com o luxo que eles merecem e mergulhe em um mundo de aconchego que dura a temporada inteira.',
                    'meta-description'  => 'descrição meta',
                    'meta-keywords'     => 'meta1, meta2, meta3',
                    'meta-title'        => 'Título Meta',
                    'name'              => 'Meias de Lã Arctic Warmth Blend',
                    'short-description' => 'Experimente o calor e o conforto incomparáveis de nossas Meias de Lã Arctic Warmth Blend. Feitas de uma mistura de lã Merino, acrílico, nylon e spandex, essas meias oferecem aconchego máximo para o clima frio. Com um calcanhar e dedo do pé reforçados para durabilidade, essas meias versáteis e estilosas são perfeitas para várias ocasiões.',
                ],

                '5' => [
                    'description'       => 'Apresentamos o Conjunto de Acessórios de Inverno Arctic Frost, a solução ideal para se manter aquecido, elegante e conectado nos dias frios de inverno. Este conjunto cuidadosamente selecionado reúne quatro acessórios essenciais de inverno para criar um conjunto harmonioso. O cachecol luxuoso, tecido a partir de uma mistura de acrílico e lã, não apenas adiciona uma camada de calor, mas também traz um toque de elegância ao seu guarda-roupa de inverno. O gorro de malha macia, feito com cuidado, promete mantê-lo aconchegante enquanto adiciona um toque de moda ao seu visual. Mas não para por aí - nosso conjunto também inclui luvas compatíveis com tela sensível ao toque. Mantenha-se conectado sem sacrificar o calor enquanto navega em seus dispositivos com facilidade. Seja atendendo chamadas, enviando mensagens ou capturando momentos de inverno em seu smartphone, essas luvas garantem conveniência sem comprometer o estilo. A textura macia e aconchegante das meias oferece uma sensação luxuosa contra a pele. Diga adeus aos pés frios enquanto desfruta do calor proporcionado por essas meias de lã. O Conjunto de Acessórios de Inverno Arctic Frost não é apenas funcional; é uma declaração de moda de inverno. Cada peça é projetada não apenas para protegê-lo do frio, mas também para elevar seu estilo durante a estação gelada. Os materiais escolhidos para este conjunto priorizam tanto a durabilidade quanto o conforto, garantindo que você possa desfrutar da maravilha do inverno com estilo. Seja para presentear a si mesmo ou procurar o presente perfeito, o Conjunto de Acessórios de Inverno Arctic Frost é uma escolha versátil. Deleite alguém especial durante a temporada de festas ou eleve seu próprio guarda-roupa de inverno com este conjunto elegante e funcional. Abraçe a geada com confiança, sabendo que você tem os acessórios perfeitos para mantê-lo aquecido e elegante.',
                    'meta-description'  => 'descrição meta',
                    'meta-keywords'     => 'meta1, meta2, meta3',
                    'meta-title'        => 'Título Meta',
                    'name'              => 'Conjunto de Acessórios de Inverno Arctic Frost',
                    'short-description' => 'Abraçe o frio do inverno com nosso Conjunto de Acessórios de Inverno Arctic Frost. Este conjunto selecionado inclui um cachecol luxuoso, um gorro aconchegante, luvas compatíveis com tela sensível ao toque e meias de lã. Estiloso e funcional, este conjunto é fabricado com materiais de alta qualidade, garantindo durabilidade e conforto. Eleve seu guarda-roupa de inverno ou alegre alguém especial com esta opção de presente perfeita.',
                ],

                '6' => [
                    'description'       => 'Apresentamos o Conjunto de Acessórios de Inverno Arctic Frost, a solução ideal para se manter aquecido, elegante e conectado nos dias frios de inverno. Este conjunto cuidadosamente selecionado reúne quatro acessórios essenciais de inverno para criar um conjunto harmonioso. O cachecol luxuoso, tecido a partir de uma mistura de acrílico e lã, não apenas adiciona uma camada de calor, mas também traz um toque de elegância ao seu guarda-roupa de inverno. O gorro de malha macia, feito com cuidado, promete mantê-lo aconchegante enquanto adiciona um toque de moda ao seu visual. Mas não para por aí - nosso conjunto também inclui luvas compatíveis com tela sensível ao toque. Mantenha-se conectado sem sacrificar o calor enquanto navega em seus dispositivos com facilidade. Seja atendendo chamadas, enviando mensagens ou capturando momentos de inverno em seu smartphone, essas luvas garantem conveniência sem comprometer o estilo. A textura macia e aconchegante das meias oferece uma sensação luxuosa contra a pele. Diga adeus aos pés frios enquanto desfruta do calor proporcionado por essas meias de lã. O Conjunto de Acessórios de Inverno Arctic Frost não é apenas funcional; é uma declaração de moda de inverno. Cada peça é projetada não apenas para protegê-lo do frio, mas também para elevar seu estilo durante a estação gelada. Os materiais escolhidos para este conjunto priorizam tanto a durabilidade quanto o conforto, garantindo que você possa desfrutar da maravilha do inverno com estilo. Seja para presentear a si mesmo ou procurar o presente perfeito, o Conjunto de Acessórios de Inverno Arctic Frost é uma escolha versátil. Deleite alguém especial durante a temporada de festas ou eleve seu próprio guarda-roupa de inverno com este conjunto elegante e funcional. Abraçe a geada com confiança, sabendo que você tem os acessórios perfeitos para mantê-lo aquecido e elegante.',
                    'meta-description'  => 'descrição meta',
                    'meta-keywords'     => 'meta1, meta2, meta3',
                    'meta-title'        => 'Título Meta',
                    'name'              => 'Conjunto de Acessórios de Inverno Arctic Frost',
                    'short-description' => 'Abraçe o frio do inverno com nosso Conjunto de Acessórios de Inverno Arctic Frost. Este conjunto selecionado inclui um cachecol luxuoso, um gorro aconchegante, luvas compatíveis com tela sensível ao toque e meias de lã. Estiloso e funcional, este conjunto é fabricado com materiais de alta qualidade, garantindo durabilidade e conforto. Eleve seu guarda-roupa de inverno ou alegre alguém especial com esta opção de presente perfeita.',
                ],

                '7' => [
                    'description'       => 'Apresentamos a Jaqueta Acolchoada com Capuz OmniHeat Masculina, sua solução para se manter aquecido e na moda durante as estações mais frias. Esta jaqueta é projetada com durabilidade e calor em mente, garantindo que ela se torne sua companheira confiável. O design com capuz não apenas adiciona um toque de estilo, mas também proporciona calor adicional, protegendo-o dos ventos frios e do clima. As mangas compridas oferecem cobertura completa, garantindo que você fique confortável do ombro ao pulso. Equipada com bolsos internos, esta jaqueta acolchoada oferece conveniência para transportar seus itens essenciais ou manter as mãos aquecidas. O enchimento sintético isolante oferece calor aprimorado, tornando-a ideal para enfrentar dias e noites frios. Feita de um resistente casaco e forro de poliéster, esta jaqueta é construída para durar e resistir aos elementos. Disponível em 5 cores atraentes, você pode escolher aquela que combina com seu estilo e preferência. Versátil e funcional, a Jaqueta Acolchoada com Capuz OmniHeat Masculina é adequada para várias ocasiões, seja para o trabalho, para um passeio casual ou para um evento ao ar livre. Experimente a combinação perfeita de estilo, conforto e funcionalidade com a Jaqueta Acolchoada com Capuz OmniHeat Masculina. Eleve seu guarda-roupa de inverno e fique confortável enquanto aproveita o ar livre. Enfrente o frio com estilo e faça uma declaração com esta peça essencial.',
                    'meta-description'  => 'descrição meta',
                    'meta-keywords'     => 'meta1, meta2, meta3',
                    'meta-title'        => 'Título Meta',
                    'name'              => 'Jaqueta Acolchoada com Capuz OmniHeat Masculina',
                    'short-description' => 'Mantenha-se aquecido e estiloso com a nossa Jaqueta Acolchoada com Capuz OmniHeat Masculina. Esta jaqueta é projetada para fornecer calor máximo e possui bolsos internos para maior conveniência. O material isolante garante que você fique confortável em clima frio. Disponível em 5 cores atraentes, tornando-a uma escolha versátil para várias ocasiões.',
                ],

                '8' => [
                    'description'       => 'Apresentamos a Jaqueta Acolchoada com Capuz OmniHeat Masculina, sua solução para se manter aquecido e na moda durante as estações mais frias. Esta jaqueta é projetada com durabilidade e calor em mente, garantindo que ela se torne sua companheira confiável. O design com capuz não apenas adiciona um toque de estilo, mas também proporciona calor adicional, protegendo-o dos ventos frios e do clima. As mangas compridas oferecem cobertura completa, garantindo que você fique confortável do ombro ao pulso. Equipada com bolsos internos, esta jaqueta acolchoada oferece conveniência para transportar seus itens essenciais ou manter as mãos aquecidas. O enchimento sintético isolante oferece calor aprimorado, tornando-a ideal para enfrentar dias e noites frios. Feita de um resistente casaco e forro de poliéster, esta jaqueta é construída para durar e resistir aos elementos. Disponível em 5 cores atraentes, você pode escolher aquela que combina com seu estilo e preferência. Versátil e funcional, a Jaqueta Acolchoada com Capuz OmniHeat Masculina é adequada para várias ocasiões, seja para o trabalho, para um passeio casual ou para um evento ao ar livre. Experimente a combinação perfeita de estilo, conforto e funcionalidade com a Jaqueta Acolchoada com Capuz OmniHeat Masculina. Eleve seu guarda-roupa de inverno e fique confortável enquanto aproveita o ar livre. Enfrente o frio com estilo e faça uma declaração com esta peça essencial.',
                    'meta-description'  => 'descrição meta',
                    'meta-keywords'     => 'meta1, meta2, meta3',
                    'meta-title'        => 'Título Meta',
                    'name'              => 'Jaqueta Acolchoada com Capuz OmniHeat Masculina-Azul-Amarelo-M',
                    'short-description' => 'Mantenha-se aquecido e estiloso com a nossa Jaqueta Acolchoada com Capuz OmniHeat Masculina. Esta jaqueta é projetada para fornecer calor máximo e possui bolsos internos para maior conveniência. O material isolante garante que você fique confortável em clima frio. Disponível em 5 cores atraentes, tornando-a uma escolha versátil para várias ocasiões.',
                ],

                '9' => [
                    'description'       => 'Apresentamos a Jaqueta Acolchoada com Capuz OmniHeat Masculina, sua solução para se manter aquecido e na moda durante as estações mais frias. Esta jaqueta é projetada com durabilidade e calor em mente, garantindo que ela se torne sua companheira confiável. O design com capuz não apenas adiciona um toque de estilo, mas também oferece calor adicional, protegendo-o dos ventos frios e do clima. As mangas compridas oferecem cobertura completa, garantindo que você fique confortável do ombro ao pulso. Equipada com bolsos internos, esta jaqueta acolchoada oferece conveniência para carregar seus itens essenciais ou manter as mãos aquecidas. O enchimento sintético isolante oferece calor aprimorado, tornando-a ideal para enfrentar dias e noites frios. Feita de um resistente casaco e forro de poliéster, esta jaqueta é construída para durar e resistir aos elementos. Disponível em 5 cores atraentes, você pode escolher aquela que combina com seu estilo e preferência. Versátil e funcional, a Jaqueta Acolchoada com Capuz OmniHeat Masculina é adequada para várias ocasiões, seja para o trabalho, um passeio casual ou um evento ao ar livre. Experimente a combinação perfeita de estilo, conforto e funcionalidade com a Jaqueta Acolchoada com Capuz OmniHeat Masculina. Eleve seu guarda-roupa de inverno e fique confortável enquanto aproveita o ar livre. Enfrente o frio com estilo e faça uma declaração com esta peça essencial.',
                    'meta-description'  => 'descrição meta',
                    'meta-keywords'     => 'meta1, meta2, meta3',
                    'meta-title'        => 'Título Meta',
                    'name'              => 'Jaqueta Acolchoada com Capuz OmniHeat Masculina-Azul-Amarelo-L',
                    'short-description' => 'Mantenha-se aquecido e estiloso com a nossa Jaqueta Acolchoada com Capuz OmniHeat Masculina. Esta jaqueta é projetada para fornecer calor máximo e possui bolsos internos para maior conveniência. O material isolante garante que você fique confortável em clima frio. Disponível em 5 cores atraentes, tornando-a uma escolha versátil para várias ocasiões.',
                ],

                '10' => [
                    'description'       => 'Apresentamos a Jaqueta Acolchoada com Capuz OmniHeat Masculina, sua solução para se manter aquecido e na moda durante as estações mais frias. Esta jaqueta é projetada com durabilidade e calor em mente, garantindo que ela se torne sua companheira confiável. O design com capuz não apenas adiciona um toque de estilo, mas também oferece calor adicional, protegendo-o dos ventos frios e do clima. As mangas compridas oferecem cobertura completa, garantindo que você fique confortável do ombro ao pulso. Equipada com bolsos internos, esta jaqueta acolchoada oferece conveniência para carregar seus itens essenciais ou manter as mãos aquecidas. O enchimento sintético isolante oferece calor aprimorado, tornando-a ideal para enfrentar dias e noites frios. Feita de um resistente casaco e forro de poliéster, esta jaqueta é construída para durar e resistir aos elementos. Disponível em 5 cores atraentes, você pode escolher aquela que combina com seu estilo e preferência. Versátil e funcional, a Jaqueta Acolchoada com Capuz OmniHeat Masculina é adequada para várias ocasiões, seja para o trabalho, um passeio casual ou um evento ao ar livre. Experimente a combinação perfeita de estilo, conforto e funcionalidade com a Jaqueta Acolchoada com Capuz OmniHeat Masculina. Eleve seu guarda-roupa de inverno e fique confortável enquanto aproveita o ar livre. Enfrente o frio com estilo e faça uma declaração com esta peça essencial.',
                    'meta-description'  => 'descrição meta',
                    'meta-keywords'     => 'meta1, meta2, meta3',
                    'meta-title'        => 'Título Meta',
                    'name'              => 'Jaqueta Acolchoada com Capuz OmniHeat Masculina-Azul-Verde-M',
                    'short-description' => 'Mantenha-se aquecido e estiloso com a nossa Jaqueta Acolchoada com Capuz OmniHeat Masculina. Esta jaqueta é projetada para fornecer calor máximo e possui bolsos internos para maior conveniência. O material isolante garante que você fique confortável em clima frio. Disponível em 5 cores atraentes, tornando-a uma escolha versátil para várias ocasiões.',
                ],

                '11' => [
                    'description'       => 'Apresentamos a Jaqueta Acolchoada com Capuz OmniHeat Masculina Sólida, sua solução para se manter aquecido e na moda durante as estações mais frias. Esta jaqueta é projetada com durabilidade e calor em mente, garantindo que ela se torne sua companheira confiável. O design com capuz não apenas adiciona um toque de estilo, mas também oferece calor adicional, protegendo-o dos ventos frios e do clima. As mangas compridas oferecem cobertura completa, garantindo que você fique confortável do ombro ao pulso. Equipada com bolsos internos, esta jaqueta acolchoada oferece conveniência para carregar seus itens essenciais ou manter as mãos aquecidas. O enchimento sintético isolante oferece calor aprimorado, tornando-a ideal para enfrentar dias e noites frios. Feita de um resistente casaco e forro de poliéster, esta jaqueta é construída para durar e resistir aos elementos. Disponível em 5 cores atraentes, você pode escolher aquela que combina com seu estilo e preferência. Versátil e funcional, a Jaqueta Acolchoada com Capuz OmniHeat Masculina Sólida é adequada para várias ocasiões, seja para o trabalho, um passeio casual ou um evento ao ar livre. Experimente a combinação perfeita de estilo, conforto e funcionalidade com a Jaqueta Acolchoada com Capuz OmniHeat Masculina Sólida. Eleve seu guarda-roupa de inverno e fique confortável enquanto aproveita o ar livre. Enfrente o frio com estilo e faça uma declaração com esta peça essencial.',
                    'meta-description'  => 'descrição meta',
                    'meta-keywords'     => 'meta1, meta2, meta3',
                    'meta-title'        => 'Título Meta',
                    'name'              => 'Jaqueta Acolchoada com Capuz OmniHeat Masculina-Azul-Verde-L',
                    'short-description' => 'Mantenha-se aquecido e estiloso com a nossa Jaqueta Acolchoada com Capuz OmniHeat Masculina Sólida. Esta jaqueta é projetada para fornecer calor máximo e possui bolsos internos para maior conveniência. O material isolante garante que você fique confortável em clima frio. Disponível em 5 cores atraentes, tornando-a uma escolha versátil para várias ocasiões.',
                ],
            ],

            'product-attribute-values' => [
                '1' => [
                    'description'      => 'O Arctic Cozy Knit Beanie é a sua solução para se manter aquecido, confortável e estiloso durante os meses mais frios. Feito de uma mistura macia e durável de acrílico, este gorro foi projetado para proporcionar um ajuste aconchegante. O design clássico o torna adequado tanto para homens quanto para mulheres, oferecendo um acessório versátil que combina com diversos estilos. Seja para um dia casual na cidade ou para aproveitar o ar livre, este gorro adiciona um toque de conforto e calor ao seu visual. O material macio e respirável garante que você fique aconchegante sem sacrificar o estilo. O Arctic Cozy Knit Beanie não é apenas um acessório; é uma declaração de moda de inverno. Sua simplicidade facilita a combinação com diferentes roupas, tornando-o um item essencial no seu guarda-roupa de inverno. Ideal para presentear ou se presentear, este gorro é uma adição atenciosa a qualquer conjunto de inverno. É um acessório versátil que vai além da funcionalidade, adicionando um toque de calor e estilo ao seu visual. Abraçe a essência do inverno com o Arctic Cozy Knit Beanie. Seja para um dia casual ou para enfrentar os elementos, deixe este gorro ser seu companheiro de conforto e estilo. Eleve seu guarda-roupa de inverno com este acessório clássico que combina calor com um senso atemporal de moda.',
                    'meta-description' => 'meta descrição',
                    'meta-keywords'    => 'meta1, meta2, meta3',
                    'meta-title'       => 'Título Meta',
                    'name'             => 'Gorro Unissex Arctic Cozy Knit',
                    'sort-description' => 'Abraçe os dias frios com estilo com o nosso Gorro Arctic Cozy Knit. Feito de uma mistura macia e durável de acrílico, este gorro clássico oferece calor e versatilidade. Adequado tanto para homens quanto para mulheres, é o acessório ideal para uso casual ou ao ar livre. Eleve seu guarda-roupa de inverno ou presenteie alguém especial com este gorro essencial.',
                ],

                '2' => [
                    'description'      => 'A Cachecol Arctic Bliss Winter é mais do que um simples acessório para o frio; é uma declaração de calor, conforto e estilo para a temporada de inverno. Feito com cuidado a partir de uma luxuosa mistura de acrílico e lã, este cachecol foi projetado para mantê-lo aconchegante mesmo nas temperaturas mais frias. A textura macia e felpuda não apenas proporciona isolamento contra o frio, mas também adiciona um toque de luxo ao seu guarda-roupa de inverno. O design do Cachecol Arctic Bliss Winter é elegante e versátil, tornando-o um complemento perfeito para uma variedade de roupas de inverno. Seja para se vestir para uma ocasião especial ou adicionar uma camada elegante ao seu visual diário, este cachecol complementa seu estilo sem esforço. O comprimento extra longo do cachecol oferece opções de estilo personalizáveis. Enrole-o para mais calor, deixe-o solto para um visual casual ou experimente diferentes nós para expressar seu estilo único. Essa versatilidade o torna um acessório indispensável para a temporada de inverno. Procurando o presente perfeito? O Cachecol Arctic Bliss Winter é uma escolha ideal. Surpreenda alguém especial ou se presenteie com este cachecol atemporal e prático que será apreciado durante os meses de inverno. Abraçe o inverno com o Cachecol Arctic Bliss Winter, onde calor encontra estilo em perfeita harmonia. Eleve seu guarda-roupa de inverno com este acessório essencial que não apenas mantém você aquecido, mas também adiciona um toque de sofisticação ao seu conjunto para o clima frio.',
                    'meta-description' => 'meta descrição',
                    'meta-keywords'    => 'meta1, meta2, meta3',
                    'meta-title'       => 'Título Meta',
                    'name'             => 'Cachecol Estiloso Arctic Bliss Winter',
                    'sort-description' => 'Experimente o abraço de calor e estilo com o nosso Cachecol Arctic Bliss Winter. Feito de uma luxuosa mistura de acrílico e lã, este cachecol aconchegante foi projetado para mantê-lo confortável durante os dias mais frios. Seu design estiloso e versátil, combinado com um comprimento extra longo, oferece opções de estilo personalizáveis. Eleve seu guarda-roupa de inverno ou presenteie alguém especial com este acessório de inverno essencial.',
                ],

                '3' => [
                    'description'      => 'Apresentamos as Luvas de Inverno Arctic Touchscreen - onde calor, estilo e conectividade se encontram para aprimorar sua experiência no inverno. Feitas de acrílico de alta qualidade, essas luvas foram projetadas para proporcionar calor e durabilidade excepcionais. As pontas compatíveis com tela sensível ao toque permitem que você fique conectado sem expor as mãos ao frio. Atenda chamadas, envie mensagens e navegue em seus dispositivos sem esforço, mantendo as mãos aquecidas. O forro isolante adiciona uma camada extra de conforto, tornando essas luvas a escolha ideal para enfrentar o frio. Seja para ir ao trabalho, fazer compras ou desfrutar de atividades ao ar livre, essas luvas oferecem o calor e a proteção de que você precisa. Os punhos elásticos garantem um ajuste seguro, evitando correntes de ar frio e mantendo as luvas no lugar durante suas atividades diárias. O design estiloso adiciona um toque de elegância ao seu conjunto de inverno, tornando essas luvas tão fashion quanto funcionais. Ideal para presentear ou se presentear, as Luvas de Inverno Arctic Touchscreen são um acessório indispensável para o indivíduo moderno. Diga adeus ao incômodo de tirar as luvas para usar seus dispositivos e abrace a combinação perfeita de calor, estilo e conectividade. Fique conectado, fique aquecido e fique estiloso com as Luvas de Inverno Arctic Touchscreen - seu companheiro confiável para enfrentar a temporada de inverno com confiança.',
                    'meta-description' => 'meta descrição',
                    'meta-keywords'    => 'meta1, meta2, meta3',
                    'meta-title'       => 'Título Meta',
                    'name'             => 'Luvas de Inverno Arctic Touchscreen',
                    'sort-description' => 'Fique conectado e aquecido com as nossas Luvas de Inverno Arctic Touchscreen. Essas luvas não apenas são feitas de acrílico de alta qualidade para proporcionar calor e durabilidade, mas também possuem um design compatível com tela sensível ao toque. Com um forro isolante, punhos elásticos para um ajuste seguro e um visual estiloso, essas luvas são perfeitas para uso diário em condições frias.',
                ],

                '4' => [
                    'description'      => 'Apresentamos as Meias de Lã Arctic Warmth - seu companheiro essencial para pés aconchegantes e confortáveis durante as estações mais frias. Feitas de uma mistura premium de lã Merino, acrílico, nylon e spandex, essas meias foram projetadas para proporcionar calor e conforto incomparáveis. A mistura de lã garante que seus pés fiquem quentes mesmo nas temperaturas mais frias, tornando essas meias a escolha perfeita para aventuras de inverno ou simplesmente para se manter aconchegante em casa. A textura macia e aconchegante das meias oferece uma sensação luxuosa contra a pele. Diga adeus aos pés frios enquanto você aproveita o calor macio proporcionado por essas meias de lã. Projetadas para durabilidade, as meias possuem calcanhar e ponta reforçados, adicionando resistência extra às áreas de maior desgaste. Isso garante que suas meias resistam ao teste do tempo, proporcionando conforto e aconchego duradouros. A natureza respirável do material evita o superaquecimento, permitindo que seus pés fiquem confortáveis e secos ao longo do dia. Seja para uma caminhada de inverno ao ar livre ou para relaxar em casa, essas meias oferecem o equilíbrio perfeito entre calor e respirabilidade. Versáteis e estilosas, essas meias de lã são adequadas para várias ocasiões. Combine-as com suas botas favoritas para um visual de inverno fashion ou use-as em casa para um conforto máximo. Eleve seu guarda-roupa de inverno e priorize o conforto com as Meias de Lã Arctic Warmth. Mime seus pés com o luxo que eles merecem e mergulhe em um mundo de aconchego que dura a temporada inteira.',
                    'meta-description' => 'meta descrição',
                    'meta-keywords'    => 'meta1, meta2, meta3',
                    'meta-title'       => 'Título Meta',
                    'name'             => 'Meias de Lã Arctic Warmth',
                    'sort-description' => 'Experimente o calor e o conforto incomparáveis das nossas Meias de Lã Arctic Warmth. Feitas de uma mistura de lã Merino, acrílico, nylon e spandex, essas meias oferecem aconchego máximo para o clima frio. Com calcanhar e ponta reforçados para durabilidade, essas meias versáteis e estilosas são perfeitas para várias ocasiões.',
                ],

                '5' => [
                    'description'      => 'Apresentando o Conjunto de Acessórios de Inverno Arctic Frost, sua solução para se manter aquecido, elegante e conectado nos dias frios de inverno. Este conjunto cuidadosamente selecionado reúne quatro acessórios essenciais de inverno para criar um conjunto harmonioso. O luxuoso cachecol, tecido a partir de uma mistura de acrílico e lã, não apenas adiciona uma camada de calor, mas também traz um toque de elegância ao seu guarda-roupa de inverno. O gorro de malha macia, feito com cuidado, promete mantê-lo aconchegante enquanto adiciona um toque de moda ao seu visual. Mas não para por aí - nosso conjunto também inclui luvas compatíveis com tela sensível ao toque. Fique conectado sem sacrificar o calor enquanto navega em seus dispositivos com facilidade. Seja atendendo chamadas, enviando mensagens ou capturando momentos de inverno em seu smartphone, essas luvas garantem conveniência sem comprometer o estilo. A textura macia e aconchegante das meias oferece uma sensação luxuosa contra a pele. Diga adeus aos pés frios enquanto você abraça o calor proporcionado por essas meias de lã. O Conjunto de Acessórios de Inverno Arctic Frost não é apenas sobre funcionalidade; é uma declaração de moda de inverno. Cada peça é projetada não apenas para protegê-lo do frio, mas também para elevar seu estilo durante a estação gelada. Os materiais escolhidos para este conjunto priorizam tanto a durabilidade quanto o conforto, garantindo que você possa desfrutar da paisagem de inverno com estilo. Seja para presentear alguém especial durante a temporada de festas ou para elevar seu próprio guarda-roupa de inverno com este conjunto elegante e funcional. Abraçe a geada com confiança, sabendo que você tem os acessórios perfeitos para mantê-lo aquecido e elegante.',
                    'meta-description' => 'meta descrição',
                    'meta-keywords'    => 'meta1, meta2, meta3',
                    'meta-title'       => 'Título Meta',
                    'name'             => 'Conjunto de Acessórios de Inverno Arctic Frost',
                    'sort-description' => 'Abraçe o frio do inverno com nosso Conjunto de Acessórios de Inverno Arctic Frost. Este conjunto selecionado inclui um cachecol luxuoso, um gorro aconchegante, luvas compatíveis com tela sensível ao toque e meias de lã. Estiloso e funcional, este conjunto é feito com materiais de alta qualidade, garantindo durabilidade e conforto. Eleve seu guarda-roupa de inverno ou presenteie alguém especial com esta opção de presente perfeita.',
                ],

                '6' => [
                    'description'      => 'Apresentando o Conjunto de Acessórios de Inverno Arctic Frost, sua solução para se manter aquecido, elegante e conectado nos dias frios de inverno. Este conjunto cuidadosamente selecionado reúne quatro acessórios essenciais de inverno para criar um conjunto harmonioso. O luxuoso cachecol, tecido a partir de uma mistura de acrílico e lã, não apenas adiciona uma camada de calor, mas também traz um toque de elegância ao seu guarda-roupa de inverno. O gorro de malha macia, feito com cuidado, promete mantê-lo aconchegante enquanto adiciona um toque de moda ao seu visual. Mas não para por aí - nosso conjunto também inclui luvas compatíveis com tela sensível ao toque. Fique conectado sem sacrificar o calor enquanto navega em seus dispositivos com facilidade. Seja atendendo chamadas, enviando mensagens ou capturando momentos de inverno em seu smartphone, essas luvas garantem conveniência sem comprometer o estilo. A textura macia e aconchegante das meias oferece uma sensação luxuosa contra a pele. Diga adeus aos pés frios enquanto você abraça o calor proporcionado por essas meias de lã. O Conjunto de Acessórios de Inverno Arctic Frost não é apenas sobre funcionalidade; é uma declaração de moda de inverno. Cada peça é projetada não apenas para protegê-lo do frio, mas também para elevar seu estilo durante a estação gelada. Os materiais escolhidos para este conjunto priorizam tanto a durabilidade quanto o conforto, garantindo que você possa desfrutar da paisagem de inverno com estilo. Seja para presentear alguém especial durante a temporada de festas ou para elevar seu próprio guarda-roupa de inverno com este conjunto elegante e funcional. Abraçe a geada com confiança, sabendo que você tem os acessórios perfeitos para mantê-lo aquecido e elegante.',
                    'meta-description' => 'meta descrição',
                    'meta-keywords'    => 'meta1, meta2, meta3',
                    'meta-title'       => 'Título Meta',
                    'name'             => 'Conjunto de Acessórios de Inverno Arctic Frost',
                    'sort-description' => 'Abraçe o frio do inverno com nosso Conjunto de Acessórios de Inverno Arctic Frost. Este conjunto selecionado inclui um cachecol luxuoso, um gorro aconchegante, luvas compatíveis com tela sensível ao toque e meias de lã. Estiloso e funcional, este conjunto é feito com materiais de alta qualidade, garantindo durabilidade e conforto. Eleve seu guarda-roupa de inverno ou presenteie alguém especial com esta opção de presente perfeita.',
                ],

                '7' => [
                    'description'      => 'Apresentando a Jaqueta Acolchoada com Capuz OmniHeat para Homens, sua solução para se manter aquecido e fashion nas estações mais frias. Esta jaqueta é projetada com durabilidade e calor em mente, garantindo que ela se torne sua companheira confiável. O design com capuz não apenas adiciona um toque de estilo, mas também proporciona calor adicional, protegendo-o dos ventos frios e do clima. As mangas compridas oferecem cobertura completa, garantindo que você fique aconchegante do ombro ao pulso. Equipada com bolsos internos, esta jaqueta acolchoada oferece conveniência para carregar seus itens essenciais ou manter as mãos aquecidas. O enchimento sintético isolado oferece calor aprimorado, tornando-a ideal para enfrentar dias e noites frios. Feita de um resistente casaco e forro de poliéster, esta jaqueta é construída para durar e resistir aos elementos. Disponível em 5 cores atraentes, você pode escolher aquela que combina com seu estilo e preferência. Versátil e funcional, a Jaqueta Acolchoada com Capuz OmniHeat para Homens é adequada para várias ocasiões, seja para o trabalho, um passeio casual ou um evento ao ar livre. Experimente a combinação perfeita de estilo, conforto e funcionalidade com a Jaqueta Acolchoada com Capuz OmniHeat para Homens. Eleve seu guarda-roupa de inverno e fique confortável enquanto aproveita o ar livre. Enfrente o frio com estilo e faça uma declaração com esta peça essencial.',
                    'meta-description' => 'meta descrição',
                    'meta-keywords'    => 'meta1, meta2, meta3',
                    'meta-title'       => 'Título Meta',
                    'name'             => 'Jaqueta Acolchoada com Capuz OmniHeat para Homens',
                    'sort-description' => 'Mantenha-se aquecido e estiloso com nossa Jaqueta Acolchoada com Capuz OmniHeat para Homens. Esta jaqueta é projetada para fornecer calor máximo e possui bolsos internos para maior conveniência. O material isolado garante que você fique aconchegante em clima frio. Disponível em 5 cores atraentes, tornando-a uma escolha versátil para várias ocasiões.',
                ],

                '8' => [
                    'description'      => 'Apresentando a Jaqueta Acolchoada com Capuz OmniHeat para Homens, sua solução para se manter aquecido e fashion nas estações mais frias. Esta jaqueta é projetada com durabilidade e calor em mente, garantindo que ela se torne sua companheira confiável. O design com capuz não apenas adiciona um toque de estilo, mas também proporciona calor adicional, protegendo-o dos ventos frios e do clima. As mangas compridas oferecem cobertura completa, garantindo que você fique aconchegante do ombro ao pulso. Equipada com bolsos internos, esta jaqueta acolchoada oferece conveniência para carregar seus itens essenciais ou manter as mãos aquecidas. O enchimento sintético isolado oferece calor aprimorado, tornando-a ideal para enfrentar dias e noites frios. Feita de um resistente casaco e forro de poliéster, esta jaqueta é construída para durar e resistir aos elementos. Disponível em 5 cores atraentes, você pode escolher aquela que combina com seu estilo e preferência. Versátil e funcional, a Jaqueta Acolchoada com Capuz OmniHeat para Homens é adequada para várias ocasiões, seja para o trabalho, um passeio casual ou um evento ao ar livre. Experimente a combinação perfeita de estilo, conforto e funcionalidade com a Jaqueta Acolchoada com Capuz OmniHeat para Homens. Eleve seu guarda-roupa de inverno e fique confortável enquanto aproveita o ar livre. Enfrente o frio com estilo e faça uma declaração com esta peça essencial.',
                    'meta-description' => 'meta descrição',
                    'meta-keywords'    => 'meta1, meta2, meta3',
                    'meta-title'       => 'Título Meta',
                    'name'             => 'Jaqueta Acolchoada com Capuz OmniHeat para Homens - Azul-Amarelo-M',
                    'sort-description' => 'Mantenha-se aquecido e estiloso com nossa Jaqueta Acolchoada com Capuz OmniHeat para Homens. Esta jaqueta é projetada para fornecer calor máximo e possui bolsos internos para maior conveniência. O material isolado garante que você fique aconchegante em clima frio. Disponível em 5 cores atraentes, tornando-a uma escolha versátil para várias ocasiões.',
                ],

                '9' => [
                    'description'      => 'Apresentamos a Jaqueta Acolchoada com Capuz OmniHeat para Homens, sua solução para se manter aquecido e na moda durante as estações mais frias. Esta jaqueta é projetada com durabilidade e calor em mente, garantindo que ela se torne sua companheira confiável. O design com capuz não apenas adiciona um toque de estilo, mas também proporciona calor adicional, protegendo-o dos ventos frios e do clima. As mangas compridas oferecem cobertura completa, garantindo que você fique aconchegante do ombro ao pulso. Equipada com bolsos internos, esta jaqueta acolchoada oferece conveniência para carregar seus itens essenciais ou manter as mãos aquecidas. O enchimento sintético isolado oferece calor aprimorado, tornando-a ideal para enfrentar dias e noites frios. Feita de um resistente casaco e forro de poliéster, esta jaqueta é construída para durar e resistir aos elementos. Disponível em 5 cores atraentes, você pode escolher aquela que combina com seu estilo e preferência. Versátil e funcional, a Jaqueta Acolchoada com Capuz OmniHeat para Homens é adequada para várias ocasiões, seja para o trabalho, para um passeio casual ou para um evento ao ar livre. Experimente a combinação perfeita de estilo, conforto e funcionalidade com a Jaqueta Acolchoada com Capuz OmniHeat para Homens. Eleve seu guarda-roupa de inverno e fique confortável enquanto aproveita o ar livre. Enfrente o frio com estilo e faça uma declaração com esta peça essencial.',
                    'meta-description' => 'descrição meta',
                    'meta-keywords'    => 'meta1, meta2, meta3',
                    'meta-title'       => 'Título Meta',
                    'name'             => 'Jaqueta Acolchoada com Capuz OmniHeat para Homens-Azul-Amarelo-L',
                    'sort-description' => 'Mantenha-se aquecido e estiloso com nossa Jaqueta Acolchoada com Capuz OmniHeat para Homens. Esta jaqueta é projetada para fornecer calor máximo e possui bolsos internos para maior conveniência. O material isolado garante que você fique aconchegante em clima frio. Disponível em 5 cores atraentes, tornando-a uma escolha versátil para várias ocasiões.',
                ],

                '10' => [
                    'description'      => 'Apresentamos a Jaqueta Acolchoada com Capuz OmniHeat para Homens, sua solução para se manter aquecido e na moda durante as estações mais frias. Esta jaqueta é projetada com durabilidade e calor em mente, garantindo que ela se torne sua companheira confiável. O design com capuz não apenas adiciona um toque de estilo, mas também proporciona calor adicional, protegendo-o dos ventos frios e do clima. As mangas compridas oferecem cobertura completa, garantindo que você fique aconchegante do ombro ao pulso. Equipada com bolsos internos, esta jaqueta acolchoada oferece conveniência para carregar seus itens essenciais ou manter as mãos aquecidas. O enchimento sintético isolado oferece calor aprimorado, tornando-a ideal para enfrentar dias e noites frios. Feita de um resistente casaco e forro de poliéster, esta jaqueta é construída para durar e resistir aos elementos. Disponível em 5 cores atraentes, você pode escolher aquela que combina com seu estilo e preferência. Versátil e funcional, a Jaqueta Acolchoada com Capuz OmniHeat para Homens é adequada para várias ocasiões, seja para o trabalho, para um passeio casual ou para um evento ao ar livre. Experimente a combinação perfeita de estilo, conforto e funcionalidade com a Jaqueta Acolchoada com Capuz OmniHeat para Homens. Eleve seu guarda-roupa de inverno e fique confortável enquanto aproveita o ar livre. Enfrente o frio com estilo e faça uma declaração com esta peça essencial.',
                    'meta-description' => 'descrição meta',
                    'meta-keywords'    => 'meta1, meta2, meta3',
                    'meta-title'       => 'Título Meta',
                    'name'             => 'Jaqueta Acolchoada com Capuz OmniHeat para Homens-Azul-Verde-M',
                    'sort-description' => 'Mantenha-se aquecido e estiloso com nossa Jaqueta Acolchoada com Capuz OmniHeat para Homens. Esta jaqueta é projetada para fornecer calor máximo e possui bolsos internos para maior conveniência. O material isolado garante que você fique aconchegante em clima frio. Disponível em 5 cores atraentes, tornando-a uma escolha versátil para várias ocasiões.',
                ],

                '11' => [
                    'description'      => 'Apresentamos a Jaqueta Acolchoada com Capuz OmniHeat para Homens, sua solução para se manter aquecido e na moda durante as estações mais frias. Esta jaqueta é projetada com durabilidade e calor em mente, garantindo que ela se torne sua companheira confiável. O design com capuz não apenas adiciona um toque de estilo, mas também proporciona calor adicional, protegendo-o dos ventos frios e do clima. As mangas compridas oferecem cobertura completa, garantindo que você fique aconchegante do ombro ao pulso. Equipada com bolsos internos, esta jaqueta acolchoada oferece conveniência para carregar seus itens essenciais ou manter as mãos aquecidas. O enchimento sintético isolado oferece calor aprimorado, tornando-a ideal para enfrentar dias e noites frios. Feita de um resistente casaco e forro de poliéster, esta jaqueta é construída para durar e resistir aos elementos. Disponível em 5 cores atraentes, você pode escolher aquela que combina com seu estilo e preferência. Versátil e funcional, a Jaqueta Acolchoada com Capuz OmniHeat para Homens é adequada para várias ocasiões, seja para o trabalho, para um passeio casual ou para um evento ao ar livre. Experimente a combinação perfeita de estilo, conforto e funcionalidade com a Jaqueta Acolchoada com Capuz OmniHeat para Homens. Eleve seu guarda-roupa de inverno e fique confortável enquanto aproveita o ar livre. Enfrente o frio com estilo e faça uma declaração com esta peça essencial.',
                    'meta-description' => 'descrição meta',
                    'meta-keywords'    => 'meta1, meta2, meta3',
                    'meta-title'       => 'Título Meta',
                    'name'             => 'Jaqueta Acolchoada com Capuz OmniHeat para Homens-Azul-Verde-L',
                    'sort-description' => 'Mantenha-se aquecido e estiloso com nossa Jaqueta Acolchoada com Capuz OmniHeat para Homens. Esta jaqueta é projetada para fornecer calor máximo e possui bolsos internos para maior conveniência. O material isolado garante que você fique aconchegante em clima frio. Disponível em 5 cores atraentes, tornando-a uma escolha versátil para várias ocasiões.',
                ],
            ],

            'product-bundle-option-translations' => [
                '1' => [
                    'label' => 'Opção de pacote 1',
                ],

                '2' => [
                    'label' => 'Opção de pacote 1',
                ],

                '3' => [
                    'label' => 'Opção de pacote 2',
                ],

                '4' => [
                    'label' => 'Opção de pacote 2',
                ],
            ],
        ],
    ],

    'installer' => [
        'index' => [
            'create-administrator' => [
                'admin'            => 'Administrador',
                'bagisto'          => 'Bagisto',
                'confirm-password' => 'Confirmar Senha',
                'email'            => 'E-mail',
                'email-address'    => 'admin@example.com',
                'password'         => 'Senha',
                'title'            => 'Criar Administrador',
            ],

            'environment-configuration' => [
                'algerian-dinar'              => 'Dinar Argelino (DZD)',
                'allowed-currencies'          => 'Moedas Permitidas',
                'allowed-locales'             => 'Locais Permitidos',
                'application-name'            => 'Nome da Aplicação',
                'argentine-peso'              => 'Peso Argentino (ARS)',
                'australian-dollar'           => 'Dólar Australiano (AUD)',
                'bagisto'                     => 'Bagisto',
                'bangladeshi-taka'            => 'Taka Bangladês (BDT)',
                'brazilian-real'              => 'Real Brasileiro (BRL)',
                'british-pound-sterling'      => 'Libra Esterlina Britânica (GBP)',
                'canadian-dollar'             => 'Dólar Canadense (CAD)',
                'cfa-franc-bceao'             => 'Franco CFA BCEAO (XOF)',
                'cfa-franc-beac'              => 'Franco CFA BEAC (XAF)',
                'chilean-peso'                => 'Peso Chileno (CLP)',
                'chinese-yuan'                => 'Yuan Chinês (CNY)',
                'colombian-peso'              => 'Peso Colombiano (COP)',
                'czech-koruna'                => 'Coroa Tcheca (CZK)',
                'danish-krone'                => 'Coroa Dinamarquesa (DKK)',
                'database-connection'         => 'Conexão com Banco de Dados',
                'database-hostname'           => 'Nome do Host do Banco de Dados',
                'database-name'               => 'Nome do Banco de Dados',
                'database-password'           => 'Senha do Banco de Dados',
                'database-port'               => 'Porta do Banco de Dados',
                'database-prefix'             => 'Prefixo do Banco de Dados',
                'database-username'           => 'Nome de Usuário do Banco de Dados',
                'default-currency'            => 'Moeda Padrão',
                'default-locale'              => 'Localidade Padrão',
                'default-timezone'            => 'Fuso Horário Padrão',
                'default-url'                 => 'URL Padrão',
                'default-url-link'            => 'https://localhost',
                'egyptian-pound'              => 'Libra Egípcia (EGP)',
                'euro'                        => 'Euro (EUR)',
                'fijian-dollar'               => 'Dólar Fijiano (FJD)',
                'hong-kong-dollar'            => 'Dólar de Hong Kong (HKD)',
                'hungarian-forint'            => 'Forint Húngaro (HUF)',
                'indian-rupee'                => 'Rúpia Indiana (INR)',
                'indonesian-rupiah'           => 'Rúpia Indonésia (IDR)',
                'israeli-new-shekel'          => 'Novo Shekel Israelense (ILS)',
                'japanese-yen'                => 'Iene Japonês (JPY)',
                'jordanian-dinar'             => 'Dinar Jordaniano (JOD)',
                'kazakhstani-tenge'           => 'Tenge Cazaque (KZT)',
                'kuwaiti-dinar'               => 'Dinar Kuwaitiano (KWD)',
                'lebanese-pound'              => 'Libra Libanesa (LBP)',
                'libyan-dinar'                => 'Dinar Líbio (LYD)',
                'malaysian-ringgit'           => 'Ringgit Malaio (MYR)',
                'mauritian-rupee'             => 'Rúpia Mauriciana (MUR)',
                'mexican-peso'                => 'Peso Mexicano (MXN)',
                'moroccan-dirham'             => 'Dirham Marroquino (MAD)',
                'mysql'                       => 'MySQL',
                'nepalese-rupee'              => 'Rúpia Nepalesa (NPR)',
                'new-taiwan-dollar'           => 'Novo Dólar Taiwanês (TWD)',
                'new-zealand-dollar'          => 'Dólar Neozelandês (NZD)',
                'nigerian-naira'              => 'Naira Nigeriana (NGN)',
                'norwegian-krone'             => 'Coroa Norueguesa (NOK)',
                'omani-rial'                  => 'Rial Omanense (OMR)',
                'pakistani-rupee'             => 'Rúpia Paquistanesa (PKR)',
                'panamanian-balboa'           => 'Balboa Panamenha (PAB)',
                'paraguayan-guarani'          => 'Guarani Paraguaio (PYG)',
                'peruvian-nuevo-sol'          => 'Novo Sol Peruano (PEN)',
                'pgsql'                       => 'PgSQL',
                'philippine-peso'             => 'Peso Filipino (PHP)',
                'polish-zloty'                => 'Zloti Polonês (PLN)',
                'qatari-rial'                 => 'Rial Catariano (QAR)',
                'romanian-leu'                => 'Leu Romeno (RON)',
                'russian-ruble'               => 'Rublo Russo (RUB)',
                'saudi-riyal'                 => 'Rial Saudita (SAR)',
                'select-timezone'             => 'Selecionar Fuso Horário',
                'singapore-dollar'            => 'Dólar de Singapura (SGD)',
                'south-african-rand'          => 'Rand Sul-Africano (ZAR)',
                'south-korean-won'            => 'Won Sul-Coreano (KRW)',
                'sqlsrv'                      => 'SQLSRV',
                'sri-lankan-rupee'            => 'Rúpia do Sri Lanka (LKR)',
                'swedish-krona'               => 'Coroa Sueca (SEK)',
                'swiss-franc'                 => 'Franco Suíço (CHF)',
                'thai-baht'                   => 'Baht Tailandês (THB)',
                'title'                       => 'Configuração da Loja',
                'tunisian-dinar'              => 'Dinar Tunisiano (TND)',
                'turkish-lira'                => 'Lira Turca (TRY)',
                'ukrainian-hryvnia'           => 'Hryvnia Ucraniana (UAH)',
                'united-arab-emirates-dirham' => 'Dirham dos Emirados Árabes Unidos (AED)',
                'united-states-dollar'        => 'Dólar Americano (USD)',
                'uzbekistani-som'             => 'Som Uzbeque (UZS)',
                'venezuelan-bolívar'          => 'Bolívar Venezuelano (VEF)',
                'vietnamese-dong'             => 'Dong Vietnamita (VND)',
                'warning-message'             => 'Atenção! As configurações do idioma padrão do sistema e da moeda padrão são permanentes e não podem ser alteradas uma vez definidas.',
                'zambian-kwacha'              => 'Kwacha Zambiano (ZMW)',
            ],

            'sample-products' => [
                'download-sample' => 'baixar amostra',
                'no'              => 'Não',
                'sample-products' => 'Produtos de amostra',
                'title'           => 'Produtos de amostra',
                'yes'             => 'Sim',
            ],

            'installation-processing' => [
                'bagisto'          => 'Instalação do Bagisto',
                'bagisto-info'     => 'A criação de tabelas no banco de dados pode levar alguns momentos',
                'title'            => 'Instalação',
            ],

            'installation-completed' => [
                'admin-panel'                => 'Painel de Administração',
                'bagisto-forums'             => 'Fórum Bagisto',
                'customer-panel'             => 'Painel do Cliente',
                'explore-bagisto-extensions' => 'Explorar Extensões Bagisto',
                'title'                      => 'Instalação Concluída',
                'title-info'                 => 'O Bagisto foi instalado com sucesso no seu sistema.',
            ],

            'ready-for-installation' => [
                'create-databsae-table'   => 'Maak de databasetabel aan',
                'install'                 => 'Installatie',
                'install-info'            => 'Bagisto Voor Installatie',
                'install-info-button'     => 'Klik op de knop hieronder om',
                'populate-database-table' => 'Vul de databasetabellen',
                'start-installation'      => 'Start Installatie',
                'title'                   => 'Klaar voor Installatie',
            ],

            'start' => [
                'locale'        => 'Locatie',
                'main'          => 'Iniciar',
                'select-locale' => 'Selecteer Locatie',
                'title'         => 'Uw Bagisto-installatie',
                'welcome-title' => 'Welkom bij Bagisto',
            ],

            'server-requirements' => [
                'calendar'    => 'Kalender',
                'ctype'       => 'cType',
                'curl'        => 'cURL',
                'dom'         => 'DOM',
                'fileinfo'    => 'Bestandsinformatie',
                'filter'      => 'Filter',
                'gd'          => 'GD',
                'hash'        => 'Hash',
                'intl'        => 'Intl',
                'json'        => 'JSON',
                'mbstring'    => 'mbstring',
                'openssl'     => 'OpenSSL',
                'pcre'        => 'PCRE',
                'pdo'         => 'PDO',
                'php'         => 'PHP',
                'php-version' => '8.1 of hoger',
                'session'     => 'Sessie',
                'title'       => 'Serververeisten',
                'tokenizer'   => 'tokenizer',
                'xml'         => 'XML',
            ],

            'arabic'                   => 'Arabski',
            'back'                     => 'Wstecz',
            'bagisto'                  => 'Bagisto',
            'bagisto-info'             => 'Projekt społecznościowy',
            'bagisto-logo'             => 'Logo Bagisto',
            'bengali'                  => 'Bengalski',
            'chinese'                  => 'Chiński',
            'continue'                 => 'Kontynuuj',
            'dutch'                    => 'Holenderski',
            'english'                  => 'Angielski',
            'french'                   => 'Francuski',
            'german'                   => 'Niemiecki',
            'hebrew'                   => 'Hebrajski',
            'hindi'                    => 'Hinduski',
            'installation-description' => 'A instalação do Bagisto geralmente envolve várias etapas. Aqui está uma visão geral do processo de instalação do Bagisto',
            'installation-info'        => 'Cieszymy się, że tu jesteś!',
            'installation-title'       => 'Witaj w instalacji',
            'italian'                  => 'Włoski',
            'japanese'                 => 'Japoński',
            'persian'                  => 'Perski',
            'polish'                   => 'Polski',
            'portuguese'               => 'Portugalski (Brazylijski)',
            'russian'                  => 'Rosyjski',
            'sinhala'                  => 'Syngaleski',
            'spanish'                  => 'Hiszpański',
            'title'                    => 'Instalator Bagisto',
            'turkish'                  => 'Turecki',
            'ukrainian'                => 'Ukraiński',
            'webkul'                   => 'Webkul',
        ],
    ],
];
