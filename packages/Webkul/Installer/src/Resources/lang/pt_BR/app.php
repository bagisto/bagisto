<?php

return [
    'seeders'   => [
        'attribute' => [
            'attribute-families' => [
                'default' => 'Padrão',
            ],

            'attribute-groups'   => [
                'description'       => 'Descrição',
                'general'           => 'Geral',
                'inventories'       => 'Inventários',
                'meta-description'  => 'Meta Descrição',
                'price'             => 'Preço',
                'settings'          => 'Configurações',
                'shipping'          => 'Envio',
            ],

            'attributes'         => [
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
                'special-price-from'   => 'Preço Especial De',
                'special-price-to'     => 'Preço Especial Até',
                'special-price'        => 'Preço Especial',
                'status'               => 'Status',
                'tax-category'         => 'Categoria de Imposto',
                'url-key'              => 'Chave de URL',
                'visible-individually' => 'Visível Individualmente',
                'weight'               => 'Peso',
                'width'                => 'Largura',
            ],

            'attribute-options'  => [
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

        'category'  => [
            'categories' => [
                'description' => 'Descrição da Categoria Raiz',
                'name'        => 'Raiz',
            ],
        ],

        'cms'       => [
            'pages' => [
                'about-us'         => [
                    'content' => 'Conteúdo da Página Sobre Nós',
                    'title'   => 'Sobre Nós',
                ],

                'contact-us'       => [
                    'content' => 'Conteúdo da Página de Contato',
                    'title'   => 'Contato',
                ],

                'customer-service' => [
                    'content' => 'Conteúdo da Página de Atendimento ao Cliente',
                    'title'   => 'Atendimento ao Cliente',
                ],

                'payment-policy'   => [
                    'content' => 'Conteúdo da Página de Política de Pagamento',
                    'title'   => 'Política de Pagamento',
                ],

                'privacy-policy'   => [
                    'content' => 'Conteúdo da Página de Política de Privacidade',
                    'title'   => 'Política de Privacidade',
                ],

                'refund-policy'    => [
                    'content' => 'Conteúdo da Página de Reembolso',
                    'title'   => 'Política de Reembolso',
                ],

                'return-policy'    => [
                    'content' => 'Conteúdo da Página de Devolução',
                    'title'   => 'Política de Devolução',
                ],

                'shipping-policy'  => [
                    'content' => 'Conteúdo da Página de Política de Envio',
                    'title'   => 'Política de Envio',
                ],

                'terms-conditions' => [
                    'content' => 'Conteúdo da Página de Termos e Condições',
                    'title'   => 'Termos e Condições',
                ],

                'terms-of-use'     => [
                    'content' => 'Conteúdo da Página de Termos de Uso',
                    'title'   => 'Termos de Uso',
                ],

                'whats-new'        => [
                    'content' => 'Conteúdo da Página de Novidades',
                    'title'   => 'Novidades',
                ],
            ],
        ],

        'core'      => [
            'channels'   => [
                'meta-description' => 'Descrição de Meta da Loja de Demonstração',
                'meta-keywords'    => 'Palavras-chave de Meta da Loja de Demonstração',
                'meta-title'       => 'Loja de Demonstração',
                'name'             => 'Padrão',
            ],

            'currencies' => [
                'AED' => 'Dirham',
                'AFN' => 'Shekel Israelense',
                'CNY' => 'Yuan Chinês',
                'EUR' => 'EURO',
                'GBP' => 'Libra Esterlina',
                'INR' => 'Rupia Indiana',
                'IRR' => 'Rial Iraniano',
                'JPY' => 'Iene Japonês',
                'RUB' => 'Rublo Russo',
                'SAR' => 'Riyal Saudita',
                'TRY' => 'Lira Turca',
                'UAH' => 'Hryvnia Ucraniano',
                'USD' => 'Dólar Americano',
            ],

            'locales'    => [
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

        'customer'  => [
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

        'shop'      => [
            'theme-customizations' => [
                'all-products'           => [
                    'name'    => 'Todos os Produtos',

                    'options' => [
                        'title' => 'Todos os Produtos',
                    ],
                ],

                'bold-collections'       => [
                    'content' => [
                        'btn-title'   => 'Ver Todos',
                        'description' => 'Apresentamos nossas Novas Coleções Audaciosas! Eleve seu estilo com designs ousados e declarações vibrantes. Explore padrões impressionantes e cores audaciosas que redefinem seu guarda-roupa. Prepare-se para abraçar o extraordinário!',
                        'title'       => 'Prepare-se para as nossas novas Coleções Audaciosas!',
                    ],

                    'name'    => 'Coleções Audaciosas',
                ],

                'categories-collections' => [
                    'name' => 'Coleções de Categorias',
                ],

                'featured-collections'   => [
                    'name'    => 'Coleções em Destaque',

                    'options' => [
                        'title' => 'Produtos em Destaque',
                    ],
                ],

                'footer-links'           => [
                    'name'    => 'Links do Rodapé',

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

                'game-container'         => [
                    'content' => [
                        'sub-title-1' => 'Nossas Coleções',
                        'sub-title-2' => 'Nossas Coleções',
                        'title'       => 'O jogo com nossas novas adições!',
                    ],

                    'name'    => 'Contêiner de Jogo',
                ],

                'image-carousel'         => [
                    'name'    => 'Carrossel de Imagens',

                    'sliders' => [
                        'title' => 'Prepare-se para a Nova Coleção',
                    ],
                ],

                'new-products'           => [
                    'name'    => 'Novos Produtos',

                    'options' => [
                        'title' => 'Novos Produtos',
                    ],
                ],

                'offer-information'      => [
                    'content' => [
                        'title' => 'GANHE ATÉ 40% DE DESCONTO no seu 1º pedido COMPRE AGORA',
                    ],

                    'name'    => 'Informações de Oferta',
                ],

                'services-content'       => [
                    'description' => [
                        'emi-available-info'   => 'EMI sem custo disponível em todos os principais cartões de crédito',
                        'free-shipping-info'   => 'Aproveite o frete grátis em todos os pedidos',
                        'product-replace-info' => 'Substituição fácil de produto disponível!',
                        'time-support-info'    => 'Suporte dedicado 24/7 via chat e e-mail',
                    ],

                    'name'        => 'Conteúdo de serviços',

                    'title'       => [
                        'free-shipping'   => 'Frete grátis',
                        'product-replace' => 'Substituição de produto',
                        'emi-available'   => 'EMI disponível',
                        'time-support'    => 'Suporte 24/7',
                    ],
                ],

                'top-collections'        => [
                    'content' => [
                        'sub-title-1' => 'Nossas Coleções',
                        'sub-title-2' => 'Nossas Coleções',
                        'sub-title-3' => 'Nossas Coleções',
                        'sub-title-4' => 'Nossas Coleções',
                        'sub-title-5' => 'Nossas Coleções',
                        'sub-title-6' => 'Nossas Coleções',
                        'title'       => 'O jogo com nossas novas adições!',
                    ],

                    'name'    => 'Melhores Coleções',
                ],
            ],
        ],

        'user'      => [
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
            'create-administrator'      => [
                'admin'            => 'Administrador',
                'bagisto'          => 'Bagisto',
                'confirm-password' => 'Confirmar Senha',
                'email-address'    => 'admin@example.com',
                'email'            => 'E-mail',
                'password'         => 'Senha',
                'title'            => 'Criar Administrador',
            ],

            'environment-configuration' => [
                'allowed-currencies'  => 'Moedas permitidas',
                'allowed-locales'     => 'Idiomas permitidos',
                'application-name'    => 'Toepassingsnaam',
                'bagisto'             => 'Bagisto',
                'chinese-yuan'        => 'Chinese Yuan (CNY)',
                'database-connection' => 'Databaseverbinding',
                'database-hostname'   => 'Database Hostnaam',
                'database-name'       => 'Databasenaam',
                'database-password'   => 'Database Wachtwoord',
                'database-port'       => 'Database Poort',
                'database-prefix'     => 'Database Voorvoegsel',
                'database-username'   => 'Database Gebruikersnaam',
                'default-currency'    => 'Standaard Valuta',
                'default-locale'      => 'Standaard Locatie',
                'default-timezone'    => 'Standaard Tijdzone',
                'default-url-link'    => 'https://localhost',
                'default-url'         => 'Standaard URL',
                'dirham'              => 'Dirham (AED)',
                'euro'                => 'Euro (EUR)',
                'iranian'             => 'Iraanse Rial (IRR)',
                'israeli'             => 'Israëlische Sjekel (AFN)',
                'japanese-yen'        => 'Japanse Yen (JPY)',
                'mysql'               => 'Mysql',
                'pgsql'               => 'pgSQL',
                'pound'               => 'Brits Pond (GBP)',
                'rupee'               => 'Indiase Roepie (INR)',
                'russian-ruble'       => 'Russische Roebel (RUB)',
                'saudi'               => 'Saoedi-Riyal (SAR)',
                'select-timezone'     => 'Selecione o fuso horário',
                'sqlsrv'              => 'SQLSRV',
                'title'               => 'Omgevingsconfiguratie',
                'turkish-lira'        => 'Turkse Lira (TRY)',
                'ukrainian-hryvnia'   => 'Oekraïense Hryvnia (UAH)',
                'usd'                 => 'Amerikaanse Dollar (USD)',
                'warning-message'     => 'Atenção! As configurações para os idiomas padrão do seu sistema, bem como a moeda padrão, são permanentes e não podem ser alteradas novamente.',
            ],

            'installation-processing'   => [
                'bagisto-info'     => 'A criação de tabelas no banco de dados pode levar alguns momentos',
                'bagisto'          => 'Instalação do Bagisto',
                'title'            => 'Instalação',
            ],

            'installation-completed'    => [
                'admin-panel'                => 'Painel de Administração',
                'bagisto-forums'             => 'Fórum Bagisto',
                'customer-panel'             => 'Painel do Cliente',
                'explore-bagisto-extensions' => 'Explorar Extensões Bagisto',
                'title-info'                 => 'O Bagisto foi instalado com sucesso no seu sistema.',
                'title'                      => 'Instalação Concluída',
            ],

            'ready-for-installation'    => [
                'create-databsae-table'   => 'Maak de databasetabel aan',
                'install-info-button'     => 'Klik op de knop hieronder om',
                'install-info'            => 'Bagisto Voor Installatie',
                'install'                 => 'Installatie',
                'populate-database-table' => 'Vul de databasetabellen',
                'start-installation'      => 'Start Installatie',
                'title'                   => 'Klaar voor Installatie',
            ],

            'start'                     => [
                'locale'        => 'Locatie',
                'main'          => 'Iniciar',
                'select-locale' => 'Selecteer Locatie',
                'title'         => 'Uw Bagisto-installatie',
                'welcome-title' => 'Welkom bij Bagisto 2.0.',
            ],

            'server-requirements'       => [
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
                'php-version' => '8.1 of hoger',
                'php'         => 'PHP',
                'session'     => 'Sessie',
                'title'       => 'Serververeisten',
                'tokenizer'   => 'tokenizer',
                'xml'         => 'XML',
            ],

            'arabic'                    => 'Arabski',
            'back'                      => 'Wstecz',
            'bagisto-info'              => 'Projekt społecznościowy',
            'bagisto-logo'              => 'Logo Bagisto',
            'bagisto'                   => 'Bagisto',
            'bengali'                   => 'Bengalski',
            'chinese'                   => 'Chiński',
            'continue'                  => 'Kontynuuj',
            'dutch'                     => 'Holenderski',
            'english'                   => 'Angielski',
            'french'                    => 'Francuski',
            'german'                    => 'Niemiecki',
            'hebrew'                    => 'Hebrajski',
            'hindi'                     => 'Hinduski',
            'installation-description'  => 'Instalacja Bagisto zazwyczaj obejmuje kilka kroków. Oto ogólny zarys procesu instalacji Bagisto:',
            'installation-info'         => 'Cieszymy się, że tu jesteś!',
            'installation-title'        => 'Witaj w instalacji',
            'italian'                   => 'Włoski',
            'japanese'                  => 'Japoński',
            'persian'                   => 'Perski',
            'polish'                    => 'Polski',
            'portuguese'                => 'Portugalski (Brazylijski)',
            'russian'                   => 'Rosyjski',
            'save-configuration'        => 'Zapisz konfigurację',
            'sinhala'                   => 'Syngaleski',
            'skip'                      => 'Pomiń',
            'spanish'                   => 'Hiszpański',
            'title'                     => 'Instalator Bagisto',
            'turkish'                   => 'Turecki',
            'ukrainian'                 => 'Ukraiński',
            'webkul'                    => 'Webkul',
        ],
    ],
];
