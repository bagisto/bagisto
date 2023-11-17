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
                'ILS' => 'Shekel Israelense',
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
                        'free-shipping'     => 'Frete grátis',
                        'product-replace'   => 'Substituição de produto',
                        'emi-available'     => 'EMI disponível',
                        'time-support'      => 'Suporte 24/7',
                    ],

                    'description' => [
                        'free-shipping-info'     => 'Aproveite o frete grátis em todos os pedidos',
                        'product-replace-info'   => 'Substituição fácil de produto disponível!',
                        'emi-available-info'     => 'EMI sem custo disponível em todos os principais cartões de crédito',
                        'time-support-info'      => 'Suporte dedicado 24/7 via chat e e-mail',
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
            'server-requirements' => [
                'calendar'    => 'Calendário',
                'ctype'       => 'ctype',
                'curl'        => 'cURL',
                'dom'         => 'DOM',
                'fileinfo'    => 'Informações do arquivo',
                'filter'      => 'Filtro',
                'gd'          => 'GD',
                'hash'        => 'Hash',
                'intl'        => 'Intl',
                'json'        => 'JSON',
                'mbstring'    => 'mbstring',
                'openssl'     => 'OpenSSL',
                'php'         => 'PHP',
                'php-version' => '8.1 ou superior',
                'pcre'        => 'PCRE',
                'pdo'         => 'PDO',
                'session'     => 'Sessão',
                'title'       => 'Requisitos do Servidor',
                'tokenizer'   => 'Tokenizer',
                'xml'         => 'XML',
            ],

            'environment-configuration' => [
                'application-name'    => 'Nome da Aplicação',
                'arabic'              => 'Árabe',
                'bagisto'             => 'Bagisto',
                'bengali'             => 'Bengali',
                'chinese-yuan'        => 'Yuan Chinês (CNY)',
                'chinese'             => 'Chinês',
                'dirham'              => 'Dirham (AED)',
                'default-url'         => 'URL Padrão',
                'default-url-link'    => 'https://localhost',
                'default-currency'    => 'Moeda Padrão',
                'default-timezone'    => 'Fuso Horário Padrão',
                'default-locale'      => 'Localização Padrão',
                'dutch'               => 'Holandês',
                'database-connection' => 'Conexão com o Banco de Dados',
                'database-hostname'   => 'Nome do Host do Banco de Dados',
                'database-port'       => 'Porta do Banco de Dados',
                'database-name'       => 'Nome do Banco de Dados',
                'database-username'   => 'Nome de Usuário do Banco de Dados',
                'database-prefix'     => 'Prefixo do Banco de Dados',
                'database-password'   => 'Senha do Banco de Dados',
                'euro'                => 'Euro (EUR)',
                'english'             => 'Inglês',
                'french'              => 'Francês',
                'hebrew'              => 'Hebraico',
                'hindi'               => 'Hindi',
                'iranian'             => 'Rial Iraniano (IRR)',
                'israeli'             => 'Shekel Israelense (ILS)',
                'italian'             => 'Italiano',
                'japanese-yen'        => 'Iene Japonês (JPY)',
                'japanese'            => 'Japonês',
                'mysql'               => 'Mysql',
                'pgsql'               => 'pgSQL',
                'pound'               => 'Libra Esterlina (GBP)',
                'persian'             => 'Persa',
                'polish'              => 'Polonês',
                'portuguese'          => 'Português (Brasileiro)',
                'rupee'               => 'Rúpia Indiana (INR)',
                'russian-ruble'       => 'Rublo Russo (RUB)',
                'russian'             => 'Russo',
                'sqlsrv'              => 'SQLSRV',
                'saudi'               => 'Rial Saudita (SAR)',
                'spanish'             => 'Espanhol',
                'sinhala'             => 'Cingalês',
                'title'               => 'Configuração do Ambiente',
                'turkish-lira'        => 'Lira Turca (TRY)',
                'turkish'             => 'Turco',
                'usd'                 => 'Dólar dos EUA (USD)',
                'ukrainian-hryvnia'   => 'Hryvnia Ucraniano (UAH)',
                'ukrainian'           => 'Ucraniano',
            ],

            'ready-for-installation' => [
                'create-databsae-table'   => 'Criar tabela no banco de dados',
                'install'                 => 'Instalação',
                'install-info'            => 'Bagisto para Instalação',
                'install-info-button'     => 'Clique no botão abaixo para',
                'populate-database-table' => 'Preencher tabelas do banco de dados',
                'start-installation'      => 'Iniciar a Instalação',
                'title'                   => 'Pronto para Instalação',
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

            'email-configuration' => [
                'encryption'           => 'Criptografia',
                'enter-username'       => 'Digite o Nome de Usuário',
                'enter-password'       => 'Digite a Senha',
                'outgoing-mail-server' => 'Servidor de Email de Saída',
                'outgoing-email'       => 'smpt.mailtrap.io',
                'password'             => 'Senha',
                'store-email'          => 'Endereço de E-mail da Loja',
                'enter-store-email'    => 'Digite o Endereço de E-mail da Loja',
                'server-port'          => 'Porta do Servidor',
                'server-port-code'     => '3306',
                'title'                => 'Configuração de E-mail',
                'username'             => 'Nome de Usuário',
            ],

            'installation-completed' => [
                'admin-panel'                => 'Painel de Administração',
                'bagisto-forums'             => 'Fórum Bagisto',
                'customer-panel'             => 'Painel do Cliente',
                'explore-bagisto-extensions' => 'Explorar Extensões Bagisto',
                'title'                      => 'Instalação Concluída',
                'title-info'                 => 'O Bagisto foi instalado com sucesso no seu sistema.',
            ],

            'bagisto-logo'             => 'Logotipo Bagisto',
            'back'                     => 'Voltar',
            'bagisto-info'             => 'Um Projeto da Comunidade',
            'bagisto'                  => 'Bagisto',
            'continue'                 => 'Continuar',
            'installation-title'       => 'Bem-vindo à Instalação',
            'installation-info'        => 'Ficamos felizes em vê-lo aqui!',
            'installation-description' => 'A instalação do Bagisto geralmente envolve várias etapas. Aqui está um resumo geral do processo de instalação do Bagisto:',
            'skip'                     => 'Pular',
            'save-configuration'       => 'Salvar Configuração',
            'title'                    => 'Instalador Bagisto',
            'webkul'                   => 'Webkul',
        ],
    ],
];
