<?php

return [
    'seeders' => [
        'attribute' => [
            'attribute-families' => [
                'default' => 'Padrão',
            ],

            'attribute-groups' => [
                'description'       => 'Descrição',
                'general'           => 'Geral',
                'inventories'       => 'Inventários',
                'meta-description'  => 'Meta Descrição',
                'price'             => 'Preço',
                'shipping'          => 'Envio',
                'settings'          => 'Configurações',
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
                'meta-title'           => 'Meta Título',
                'meta-keywords'        => 'Meta Palavras-chave',
                'meta-description'     => 'Meta Descrição',
                'manage-stock'         => 'Gerenciar Estoque',
                'new'                  => 'Novo',
                'name'                 => 'Nome',
                'product-number'       => 'Número do Produto',
                'price'                => 'Preço',
                'sku'                  => 'SKU',
                'status'               => 'Status',
                'short-description'    => 'Descrição Curta',
                'special-price'        => 'Preço Especial',
                'special-price-from'   => 'Preço Especial De',
                'special-price-to'     => 'Preço Especial Até',
                'size'                 => 'Tamanho',
                'tax-category'         => 'Categoria de Imposto',
                'url-key'              => 'Chave de URL',
                'visible-individually' => 'Visível Individualmente',
                'width'                => 'Largura',
                'weight'               => 'Peso',
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

                'refund-policy' => [
                    'content' => 'Conteúdo da Página de Reembolso',
                    'title'   => 'Política de Reembolso',
                ],

                'return-policy' => [
                    'content' => 'Conteúdo da Página de Devolução',
                    'title'   => 'Política de Devolução',
                ],

                'terms-conditions' => [
                    'content' => 'Conteúdo da Página de Termos e Condições',
                    'title'   => 'Termos e Condições',
                ],

                'terms-of-use' => [
                    'content' => 'Conteúdo da Página de Termos de Uso',
                    'title'   => 'Termos de Uso',
                ],

                'contact-us' => [
                    'content' => 'Conteúdo da Página de Contato',
                    'title'   => 'Contato',
                ],

                'customer-service' => [
                    'content' => 'Conteúdo da Página de Atendimento ao Cliente',
                    'title'   => 'Atendimento ao Cliente',
                ],

                'whats-new' => [
                    'content' => 'Conteúdo da Página de Novidades',
                    'title'   => 'Novidades',
                ],

                'payment-policy' => [
                    'content' => 'Conteúdo da Página de Política de Pagamento',
                    'title'   => 'Política de Pagamento',
                ],

                'shipping-policy' => [
                    'content' => 'Conteúdo da Página de Política de Envio',
                    'title'   => 'Política de Envio',
                ],

                'privacy-policy' => [
                    'content' => 'Conteúdo da Página de Política de Privacidade',
                    'title'   => 'Política de Privacidade',
                ],
            ],
        ],

        'core' => [
            'channels' => [
                'meta-title'       => 'Loja de Demonstração',
                'meta-keywords'    => 'Palavras-chave de Meta da Loja de Demonstração',
                'meta-description' => 'Descrição de Meta da Loja de Demonstração',
                'name'             => 'Padrão',
            ],

            'currencies' => [
                'CNY' => 'Yuan Chinês',
                'AED' => 'Dirham',
                'EUR' => 'EURO',
                'INR' => 'Rupia Indiana',
                'IRR' => 'Rial Iraniano',
                'AFN' => 'Shekel Israelense',
                'JPY' => 'Iene Japonês',
                'GBP' => 'Libra Esterlina',
                'RUB' => 'Rublo Russo',
                'SAR' => 'Riyal Saudita',
                'TRY' => 'Lira Turca',
                'USD' => 'Dólar Americano',
                'UAH' => 'Hryvnia Ucraniano',
            ],

            'locales' => [
                'ar'    => 'Árabe',
                'bn'    => 'Bengali',
                'de'    => 'Alemão',
                'es'    => 'Espanhol',
                'en'    => 'Inglês',
                'fr'    => 'Francês',
                'fa'    => 'Persa',
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
                'guest'     => 'Visitante',
                'general'   => 'Geral',
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
                'image-carousel' => [
                    'name'  => 'Carrossel de Imagens',

                    'sliders' => [
                        'title' => 'Prepare-se para a Nova Coleção',
                    ],
                ],

                'offer-information' => [
                    'name' => 'Informações de Oferta',

                    'content' => [
                        'title' => 'GANHE ATÉ 40% DE DESCONTO no seu 1º pedido COMPRE AGORA',
                    ],
                ],

                'categories-collections' => [
                    'name' => 'Coleções de Categorias',
                ],

                'new-products' => [
                    'name' => 'Novos Produtos',

                    'options' => [
                        'title' => 'Novos Produtos',
                    ],
                ],

                'top-collections' => [
                    'name' => 'Melhores Coleções',

                    'content' => [
                        'sub-title-1' => 'Nossas Coleções',
                        'sub-title-2' => 'Nossas Coleções',
                        'sub-title-3' => 'Nossas Coleções',
                        'sub-title-4' => 'Nossas Coleções',
                        'sub-title-5' => 'Nossas Coleções',
                        'sub-title-6' => 'Nossas Coleções',
                        'title'       => 'O jogo com nossas novas adições!',
                    ],
                ],

                'bold-collections' => [
                    'name' => 'Coleções Audaciosas',

                    'content' => [
                        'btn-title'   => 'Ver Todos',
                        'description' => 'Apresentamos nossas Novas Coleções Audaciosas! Eleve seu estilo com designs ousados e declarações vibrantes. Explore padrões impressionantes e cores audaciosas que redefinem seu guarda-roupa. Prepare-se para abraçar o extraordinário!',
                        'title'       => 'Prepare-se para as nossas novas Coleções Audaciosas!',
                    ],
                ],

                'featured-collections' => [
                    'name' => 'Coleções em Destaque',

                    'options' => [
                        'title' => 'Produtos em Destaque',
                    ],
                ],

                'game-container' => [
                    'name' => 'Contêiner de Jogo',

                    'content' => [
                        'sub-title-1' => 'Nossas Coleções',
                        'sub-title-2' => 'Nossas Coleções',
                        'title'       => 'O jogo com nossas novas adições!',
                    ],
                ],

                'all-products' => [
                    'name' => 'Todos os Produtos',

                    'options' => [
                        'title' => 'Todos os Produtos',
                    ],
                ],

                'footer-links' => [
                    'name' => 'Links do Rodapé',

                    'options' => [
                        'about-us'         => 'Sobre Nós',
                        'contact-us'       => 'Fale Conosco',
                        'customer-service' => 'Atendimento ao Cliente',
                        'privacy-policy'   => 'Política de Privacidade',
                        'payment-policy'   => 'Política de Pagamento',
                        'return-policy'    => 'Política de Devolução',
                        'refund-policy'    => 'Política de Reembolso',
                        'shipping-policy'  => 'Política de Envio',
                        'terms-of-use'     => 'Termos de Uso',
                        'terms-conditions' => 'Termos e Condições',
                        'whats-new'        => 'O que há de Novo',
                    ],
                ],

                'services-content' => [
                    'name'  => 'Conteúdo de serviços',

                    'title' => [
                        'free-shipping'   => 'Frete grátis',
                        'product-replace' => 'Substituição de produto',
                        'emi-available'   => 'EMI disponível',
                        'time-support'    => 'Suporte 24/7',
                    ],

                    'description' => [
                        'free-shipping-info'   => 'Aproveite o frete grátis em todos os pedidos',
                        'product-replace-info' => 'Substituição fácil de produto disponível!',
                        'emi-available-info'   => 'EMI sem custo disponível em todos os principais cartões de crédito',
                        'time-support-info'    => 'Suporte dedicado 24/7 via chat e e-mail',
                    ],
                ],
            ],
        ],

        'user' => [
            'users' => [
                'name' => 'Exemplo',
            ],

            'roles' => [
                'description' => 'Este papel concede a todos os usuários acesso total',
                'name'        => 'Administrador',
            ],
        ],
    ],

    'installer' => [
        'index' => [
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
                'php'         => 'PHP',
                'php-version' => '8.1 of hoger',
                'pcre'        => 'PCRE',
                'pdo'         => 'PDO',
                'session'     => 'Sessie',
                'title'       => 'Serververeisten',
                'tokenizer'   => 'tokenizer',
                'xml'         => 'XML',
            ],

            'environment-configuration' => [
                'allowed-locales'     => 'Idiomas permitidos',
                'allowed-currencies'  => 'Moedas permitidas',
                'application-name'    => 'Toepassingsnaam',
                'bagisto'             => 'Bagisto',
                'chinese-yuan'        => 'Chinese Yuan (CNY)',
                'dirham'              => 'Dirham (AED)',
                'default-url'         => 'Standaard URL',
                'default-url-link'    => 'https://localhost',
                'default-currency'    => 'Standaard Valuta',
                'default-timezone'    => 'Standaard Tijdzone',
                'default-locale'      => 'Standaard Locatie',
                'database-connection' => 'Databaseverbinding',
                'database-hostname'   => 'Database Hostnaam',
                'database-port'       => 'Database Poort',
                'database-name'       => 'Databasenaam',
                'database-username'   => 'Database Gebruikersnaam',
                'database-prefix'     => 'Database Voorvoegsel',
                'database-password'   => 'Database Wachtwoord',
                'euro'                => 'Euro (EUR)',
                'iranian'             => 'Iraanse Rial (IRR)',
                'israeli'             => 'Israëlische Sjekel (AFN)',
                'japanese-yen'        => 'Japanse Yen (JPY)',
                'mysql'               => 'Mysql',
                'pgsql'               => 'pgSQL',
                'pound'               => 'Brits Pond (GBP)',
                'rupee'               => 'Indiase Roepie (INR)',
                'russian-ruble'       => 'Russische Roebel (RUB)',
                'sqlsrv'              => 'SQLSRV',
                'saudi'               => 'Saoedi-Riyal (SAR)',
                'title'               => 'Omgevingsconfiguratie',
                'turkish-lira'        => 'Turkse Lira (TRY)',
                'usd'                 => 'Amerikaanse Dollar (USD)',
                'ukrainian-hryvnia'   => 'Oekraïense Hryvnia (UAH)',
                'warning-message'     => 'Atenção! As configurações para os idiomas padrão do seu sistema, bem como a moeda padrão, são permanentes e não podem ser alteradas novamente.',
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

            'installation-processing' => [
                'bagisto'          => 'Instalação do Bagisto',
                'bagisto-info'     => 'A criação de tabelas no banco de dados pode levar alguns momentos',
                'title'            => 'Instalação',
            ],

            'create-administrator' => [
                'admin'            => 'Administrador',
                'bagisto'          => 'Bagisto',
                'confirm-password' => 'Confirmar Senha',
                'email'            => 'E-mail',
                'email-address'    => 'admin@example.com',
                'password'         => 'Senha',
                'title'            => 'Criar Administrador',
            ],

            'installation-completed' => [
                'admin-panel'                => 'Painel de Administração',
                'bagisto-forums'             => 'Fórum Bagisto',
                'customer-panel'             => 'Painel do Cliente',
                'explore-bagisto-extensions' => 'Explorar Extensões Bagisto',
                'title'                      => 'Instalação Concluída',
                'title-info'                 => 'O Bagisto foi instalado com sucesso no seu sistema.',
            ],

            'arabic'                   => 'Arabski',
            'bengali'                  => 'Bengalski',
            'bagisto-logo'             => 'Logo Bagisto',
            'back'                     => 'Wstecz',
            'bagisto-info'             => 'Projekt społecznościowy',
            'bagisto'                  => 'Bagisto',
            'chinese'                  => 'Chiński',
            'continue'                 => 'Kontynuuj',
            'dutch'                    => 'Holenderski',
            'english'                  => 'Angielski',
            'french'                   => 'Francuski',
            'german'                   => 'Niemiecki',
            'hebrew'                   => 'Hebrajski',
            'hindi'                    => 'Hinduski',
            'installation-title'       => 'Witaj w instalacji',
            'installation-info'        => 'Cieszymy się, że tu jesteś!',
            'installation-description' => 'Instalacja Bagisto zazwyczaj obejmuje kilka kroków. Oto ogólny zarys procesu instalacji Bagisto:',
            'italian'                  => 'Włoski',
            'japanese'                 => 'Japoński',
            'persian'                  => 'Perski',
            'polish'                   => 'Polski',
            'portuguese'               => 'Portugalski (Brazylijski)',
            'russian'                  => 'Rosyjski',
            'spanish'                  => 'Hiszpański',
            'sinhala'                  => 'Syngaleski',
            'skip'                     => 'Pomiń',
            'save-configuration'       => 'Zapisz konfigurację',
            'title'                    => 'Instalator Bagisto',
            'turkish'                  => 'Turecki',
            'ukrainian'                => 'Ukraiński',
            'webkul'                   => 'Webkul',
        ],
    ],
];
