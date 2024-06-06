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
                'venezuelan-bolívar'          => 'Bolívar Venezuelano (VEF)',
                'vietnamese-dong'             => 'Dong Vietnamita (VND)',
                'warning-message'             => 'Atenção! As configurações para os idiomas do sistema padrão, assim como a moeda padrão, são permanentes e não podem ser alteradas novamente.',
                'zambian-kwach'               => 'Kwacha Zambiano (ZMW)',
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
                'welcome-title' => 'Welkom bij Bagisto 2.0.',
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
            'installation-description' => 'Instalacja Bagisto zazwyczaj obejmuje kilka kroków. Oto ogólny zarys procesu instalacji Bagisto:',
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
