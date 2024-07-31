<?php

return [
    'customers' => [
        'forgot-password' => [
            'already-sent'         => 'E-mail de redefinição de senha já enviado.',
            'back'                 => 'Voltar para Entrar?',
            'bagisto'              => 'Bagisto',
            'email'                => 'E-mail',
            'email-not-exist'      => 'Não conseguimos encontrar um usuário com esse endereço de e-mail.',
            'footer'               => '© Copyright 2010 - :current_year, Webkul Software (Registrado na Índia). Todos os direitos reservados.',
            'forgot-password-text' => 'Se você esqueceu sua senha, recupere-a inserindo seu endereço de e-mail.',
            'page-title'           => 'Esqueceu sua senha?',
            'reset-link-sent'      => 'Enviamos o link de redefinição de senha para o seu e-mail.',
            'sign-in-button'       => 'Entrar',
            'submit'               => 'Redefinir Senha',
            'title'                => 'Recuperar Senha',
        ],

        'reset-password' => [
            'back-link-title'  => 'Voltar para Entrar',
            'bagisto'          => 'Bagisto',
            'confirm-password' => 'Confirmar Senha',
            'email'            => 'E-mail Registrado',
            'footer'           => '© Copyright 2010 - :current_year, Webkul Software (Registrado na Índia). Todos os direitos reservados.',
            'password'         => 'Senha',
            'submit-btn-title' => 'Redefinir Senha',
            'title'            => 'Redefinir Senha',
        ],

        'login-form' => [
            'bagisto'             => 'Bagisto',
            'button-title'        => 'Entrar',
            'create-your-account' => 'Crie sua conta',
            'email'               => 'E-mail',
            'footer'              => '© Copyright 2010 - :current_year, Webkul Software (Registrado na Índia). Todos os direitos reservados.',
            'forgot-pass'         => 'Esqueceu a Senha?',
            'form-login-text'     => 'Se você tem uma conta, faça login com seu endereço de e-mail.',
            'invalid-credentials' => 'Verifique suas credenciais e tente novamente.',
            'new-customer'        => 'Novo cliente?',
            'not-activated'       => 'Sua ativação aguarda aprovação do administrador',
            'page-title'          => 'Login do Cliente',
            'password'            => 'Senha',
            'show-password'       => 'Mostrar Senha',
            'title'               => 'Entrar',
            'verify-first'        => 'Verifique primeiro sua conta de e-mail.',
        ],

        'signup-form' => [
            'account-exists'              => 'Já tem uma conta?',
            'bagisto'                     => 'Bagisto',
            'button-title'                => 'Registrar',
            'confirm-pass'                => 'Confirmar Senha',
            'email'                       => 'E-mail',
            'first-name'                  => 'Primeiro Nome',
            'footer'                      => '© Copyright 2010 - :current_year, Webkul Software (Registrado na Índia). Todos os direitos reservados.',
            'form-signup-text'            => 'Se você é novo em nossa loja, ficamos felizes em tê-lo como membro.',
            'last-name'                   => 'Sobrenome',
            'page-title'                  => 'Tornar-se Usuário',
            'password'                    => 'Senha',
            'sign-in-button'              => 'Entrar',
            'subscribe-to-newsletter'     => 'Inscrever-se na newsletter',
            'success'                     => 'Conta criada com sucesso.',
            'success-verify'              => 'Conta criada com sucesso, um e-mail foi enviado para verificação.',
            'success-verify-email-unsent' => 'Conta criada com sucesso, mas o e-mail de verificação não foi enviado.',
            'verification-not-sent'       => 'Erro! Problema ao enviar e-mail de verificação, tente novamente mais tarde.',
            'verification-sent'           => 'E-mail de verificação enviado',
            'verified'                    => 'Sua conta foi verificada, tente fazer login agora.',
            'verify-failed'               => 'Não foi possível verificar sua conta de e-mail.',
        ],

        'account' => [
            'home' => 'Página Inicial',

            'profile' => [
                'index' => [
                    'delete'         => 'Excluir',
                    'delete-failed'  => 'Erro encontrado ao excluir o cliente.',
                    'delete-profile' => 'Excluir Perfil',
                    'delete-success' => 'Cliente excluído com sucesso',
                    'dob'            => 'Data de Nascimento',
                    'edit'           => 'Editar',
                    'edit-success'   => 'Perfil atualizado com sucesso',
                    'email'          => 'E-mail',
                    'enter-password' => 'Digite sua senha',
                    'first-name'     => 'Nome',
                    'gender'         => 'Gênero',
                    'last-name'      => 'Sobrenome',
                    'order-pending'  => 'Não é possível excluir a conta do cliente porque há pedido(s) pendente(s) ou em estado de processamento.',
                    'title'          => 'Perfil',
                    'unmatched'      => 'A senha antiga não corresponde.',
                    'wrong-password' => 'Senha incorreta!',
                ],

                'edit' => [
                    'confirm-password'        => 'Confirmar Senha',
                    'current-password'        => 'Senha Atual',
                    'dob'                     => 'Data de Nascimento',
                    'edit'                    => 'Editar',
                    'edit-profile'            => 'Editar Perfil',
                    'email'                   => 'E-mail',
                    'female'                  => 'Feminino',
                    'first-name'              => 'Nome',
                    'gender'                  => 'Gênero',
                    'last-name'               => 'Sobrenome',
                    'male'                    => 'Masculino',
                    'new-password'            => 'Nova Senha',
                    'other'                   => 'Outro',
                    'phone'                   => 'Telefone',
                    'save'                    => 'Salvar',
                    'subscribe-to-newsletter' => 'Inscrever-se para receber boletins informativos',
                ],
            ],

            'addresses' => [
                'index' => [
                    'add-address'      => 'Adicionar Endereço',
                    'create-success'   => 'Endereço adicionado com sucesso.',
                    'default-address'  => 'Endereço Padrão',
                    'default-delete'   => 'O endereço padrão não pode ser alterado.',
                    'delete'           => 'Excluir',
                    'delete-success'   => 'Endereço excluído com sucesso',
                    'edit'             => 'Editar',
                    'edit-success'     => 'Endereço atualizado com sucesso.',
                    'empty-address'    => 'Você ainda não adicionou um endereço à sua conta.',
                    'security-warning' => 'Atividade suspeita encontrada!!!',
                    'set-as-default'   => 'Definir como Padrão',
                    'title'            => 'Endereço',
                    'update-success'   => 'Endereço atualizado com sucesso.',
                ],

                'create' => [
                    'add-address'    => 'Adicionar Endereço',
                    'city'           => 'Cidade',
                    'company-name'   => 'Nome da Empresa',
                    'country'        => 'País',
                    'email'          => 'E-mail',
                    'first-name'     => 'Nome',
                    'last-name'      => 'Sobrenome',
                    'phone'          => 'Telefone',
                    'post-code'      => 'CEP',
                    'save'           => 'Salvar',
                    'select-country' => 'Selecionar País',
                    'set-as-default' => 'Definir como Padrão',
                    'state'          => 'Estado',
                    'street-address' => 'Endereço',
                    'title'          => 'Endereço',
                    'vat-id'         => 'ID do IVA',
                ],

                'edit' => [
                    'city'           => 'Cidade',
                    'company-name'   => 'Nome da Empresa',
                    'country'        => 'País',
                    'edit'           => 'Editar',
                    'email'          => 'E-mail',
                    'first-name'     => 'Nome',
                    'last-name'      => 'Sobrenome',
                    'phone'          => 'Telefone',
                    'post-code'      => 'CEP',
                    'select-country' => 'Selecionar País',
                    'state'          => 'Estado',
                    'street-address' => 'Endereço',
                    'title'          => 'Endereço',
                    'update-btn'     => 'Atualizar',
                    'vat-id'         => 'ID do IVA',
                ],
            ],

            'orders' => [
                'action'      => 'Ação',
                'action-view' => 'Ver',
                'empty-order' => 'Você ainda não pediu nenhum produto',
                'order'       => 'Pedido',
                'order-date'  => 'Data do pedido',
                'order-id'    => 'ID do pedido',
                'subtotal'    => 'Subtotal',
                'title'       => 'Pedidos',
                'total'       => 'Total',

                'status' => [
                    'title' => 'Status',

                    'options' => [
                        'canceled'        => 'Cancelado',
                        'closed'          => 'Fechado',
                        'completed'       => 'Concluído',
                        'fraud'           => 'Fraude',
                        'pending'         => 'Pendente',
                        'pending-payment' => 'Pagamento Pendente',
                        'processing'      => 'Em Processamento',
                    ],
                ],

                'view' => [
                    'billing-address'      => 'Endereço de Cobrança',
                    'cancel-btn-title'     => 'Cancelar',
                    'cancel-confirm-msg'   => 'Tem certeza de que deseja cancelar este pedido?',
                    'cancel-error'         => 'Seu pedido não pode ser cancelado.',
                    'cancel-success'       => 'Seu pedido foi cancelado',
                    'contact'              => 'Contato',
                    'item-invoiced'        => 'Item Faturado',
                    'item-refunded'        => 'Item Reembolsado',
                    'item-shipped'         => 'Item Enviado',
                    'item-ordered'         => 'Item Pedido',
                    'order-id'             => 'ID do Pedido',
                    'page-title'           => 'Pedido #:order_id',
                    'payment-method'       => 'Método de Pagamento',
                    'reorder-btn-title'    => 'Refazer Pedido',
                    'shipping-address'     => 'Endereço de Envio',
                    'shipping-method'      => 'Método de Envio',
                    'shipping-and-payment' => 'Detalhes de Envio e Pagamento',
                    'status'               => 'Status',
                    'title'                => 'Visualizar',
                    'total'                => 'Total',

                    'information' => [
                        'discount'                   => 'Desconto',
                        'excl-tax'                   => 'Excl. Imposto:',
                        'grand-total'                => 'Total Geral',
                        'info'                       => 'Informação',
                        'item-canceled'              => 'Cancelado (:qty_canceled)',
                        'item-refunded'              => 'Reembolsado (:qty_refunded)',
                        'invoiced-item'              => 'Faturado (:qty_invoiced)',
                        'item-shipped'               => 'Enviado (:qty_shipped)',
                        'item-status'                => 'Status do Item',
                        'ordered-item'               => 'Pedido (:qty_ordered)',
                        'placed-on'                  => 'Realizado em',
                        'price'                      => 'Preço',
                        'product-name'               => 'Nome do Produto',
                        'shipping-handling'          => 'Envio e Manuseio',
                        'shipping-handling-excl-tax' => 'Envio e Manuseio (Excl. Imposto)',
                        'shipping-handling-incl-tax' => 'Envio e Manuseio (Incl. Imposto)',
                        'sku'                        => 'SKU',
                        'subtotal'                   => 'Subtotal',
                        'subtotal-excl-tax'          => 'Subtotal (Excl. Imposto)',
                        'subtotal-incl-tax'          => 'Subtotal (Incl. Imposto)',
                        'order-summary'              => 'Resumo do Pedido',
                        'tax'                        => 'Imposto',
                        'tax-amount'                 => 'Valor do Imposto',
                        'tax-percent'                => 'Percentual do Imposto',
                        'total-due'                  => 'Total Devido',
                        'total-paid'                 => 'Total Pago',
                        'total-refunded'             => 'Total Reembolsado',
                    ],

                    'invoices' => [
                        'discount'                   => 'Desconto',
                        'excl-tax'                   => 'Excl. Imposto:',
                        'grand-total'                => 'Total Geral',
                        'individual-invoice'         => 'Fatura #:invoice_id',
                        'invoices'                   => 'Faturas',
                        'price'                      => 'Preço',
                        'print'                      => 'Imprimir',
                        'product-name'               => 'Nome do Produto',
                        'products-ordered'           => 'Produtos Pedidos',
                        'qty'                        => 'Quantidade',
                        'shipping-handling-excl-tax' => 'Envio e Manuseio (Excl. Imposto)',
                        'shipping-handling-incl-tax' => 'Envio e Manuseio (Incl. Imposto)',
                        'shipping-handling'          => 'Envio e Manuseio',
                        'sku'                        => 'SKU',
                        'subtotal-excl-tax'          => 'Subtotal (Excl. Imposto)',
                        'subtotal-incl-tax'          => 'Subtotal (Incl. Imposto)',
                        'subtotal'                   => 'Subtotal',
                        'tax'                        => 'Imposto',
                        'tax-amount'                 => 'Valor do Imposto',
                    ],

                    'shipments' => [
                        'individual-shipment' => 'Remessa #:shipment_id',
                        'product-name'        => 'Nome do Produto',
                        'qty'                 => 'Quantidade',
                        'shipments'           => 'Remessas',
                        'sku'                 => 'SKU',
                        'subtotal'            => 'Subtotal',
                        'tracking-number'     => 'Número de Rastreamento',
                    ],

                    'refunds' => [
                        'adjustment-fee'             => 'Taxa de Ajuste',
                        'adjustment-refund'          => 'Reembolso de Ajuste',
                        'discount'                   => 'Desconto',
                        'grand-total'                => 'Total Geral',
                        'individual-refund'          => 'Reembolso #:refund_id',
                        'no-result-found'            => 'Não encontramos nenhum registro.',
                        'order-summary'              => 'Resumo do Pedido',
                        'price'                      => 'Preço',
                        'product-name'               => 'Nome do Produto',
                        'qty'                        => 'Quantidade',
                        'refunds'                    => 'Reembolsos',
                        'shipping-handling'          => 'Envio e Manuseio',
                        'shipping-handling-excl-tax' => 'Envio e Manuseio (Excl. Imposto)',
                        'shipping-handling-incl-tax' => 'Envio e Manuseio (Incl. Imposto)',
                        'sku'                        => 'SKU',
                        'subtotal'                   => 'Subtotal',
                        'subtotal-excl-tax'          => 'Subtotal (Excl. Imposto)',
                        'subtotal-incl-tax'          => 'Subtotal (Incl. Imposto)',
                        'tax'                        => 'Imposto',
                        'tax-amount'                 => 'Valor do Imposto',
                    ],
                ],

                'invoice-pdf' => [
                    'bank-details'               => 'Dettagli Bancari',
                    'bill-to'                    => 'Fatturato a',
                    'contact-number'             => 'Numero di Contatto',
                    'contact'                    => 'Contatto',
                    'date'                       => 'Data Fattura',
                    'discount'                   => 'Sconto',
                    'excl-tax'                   => 'Escl. Tasse:',
                    'grand-total'                => 'Totale Generale',
                    'invoice-id'                 => 'ID Fattura',
                    'invoice'                    => 'Fattura',
                    'order-date'                 => 'Data Ordine',
                    'order-id'                   => 'ID Ordine',
                    'payment-method'             => 'Metodo di Pagamento',
                    'payment-terms'              => 'Termini di Pagamento',
                    'price'                      => 'Prezzo',
                    'product-name'               => 'Nome Prodotto',
                    'qty'                        => 'Quantità',
                    'ship-to'                    => 'Spedisci a',
                    'shipping-handling-excl-tax' => 'Spedizione e Gestione (Escl. Tasse)',
                    'shipping-handling-incl-tax' => 'Spedizione e Gestione (Incl. Tasse)',
                    'shipping-handling'          => 'Spedizione e Gestione',
                    'shipping-method'            => 'Metodo di Spedizione',
                    'sku'                        => 'SKU',
                    'subtotal-excl-tax'          => 'Subtotale (Escl. Tasse)',
                    'subtotal-incl-tax'          => 'Subtotale (Incl. Tasse)',
                    'subtotal'                   => 'Subtotale',
                    'tax-amount'                 => 'Importo Imposta',
                    'tax'                        => 'Imposta',
                    'vat-number'                 => 'Numero di Partita IVA',
                ],
            ],

            'reviews' => [
                'empty-review' => 'Você ainda não avaliou nenhum produto',
                'title'        => 'Avaliações',
            ],

            'downloadable-products' => [
                'available'           => 'Disponível',
                'completed'           => 'Concluído',
                'date'                => 'Data',
                'download-error'      => 'O link de download expirou.',
                'expired'             => 'Expirado',
                'empty-product'       => 'Você não possui um produto para download',
                'name'                => 'Produtos para Download',
                'orderId'             => 'ID do Pedido',
                'pending'             => 'Pendente',
                'payment-error'       => 'O pagamento não foi feito para este download.',
                'records-found'       => 'Registro(s) encontrado(s)',
                'remaining-downloads' => 'Downloads Restantes',
                'status'              => 'Status',
                'title'               => 'Título',
            ],

            'wishlist' => [
                'color'              => 'Cor',
                'delete-all'         => 'Excluir Tudo',
                'empty'              => 'Nenhum produto foi adicionado à página de lista de desejos.',
                'move-to-cart'       => 'Mover Para o Carrinho',
                'moved'              => 'Item movido com sucesso para o carrinho',
                'moved-success'      => 'Item movido com sucesso para o carrinho',
                'page-title'         => 'Lista de Desejos',
                'product-removed'    => 'O produto não está mais disponível, pois foi removido pelo administrador',
                'profile'            => 'Perfil',
                'remove'             => 'Remover',
                'remove-all-success' => 'Todos os itens da sua lista de desejos foram removidos',
                'remove-fail'        => 'O item não pode ser removido da lista de desejos',
                'removed'            => 'Item removido com sucesso da lista de desejos',
                'see-details'        => 'Ver Detalhes',
                'success'            => 'Item adicionado com sucesso à lista de desejos',
                'title'              => 'Lista de Desejos',
            ],
        ],
    ],

    'components' => [
        'accordion' => [
            'default-content' => 'Conteúdo Padrão',
            'default-header'  => 'Cabeçalho Padrão',
        ],

        'drawer' => [
            'default-toggle' => 'Alternar Padrão',
        ],

        'media' => [
            'index' => [
                'add-attachments' => 'Adicionar Anexos',
                'add-image'       => 'Adicionar imagem',
            ],
        ],

        'layouts' => [
            'header' => [
                'account'           => 'Conta',
                'bagisto'           => 'Bagisto',
                'cart'              => 'Carrinho',
                'compare'           => 'Comparar',
                'dropdown-text'     => 'Gerenciar Carrinho, Pedidos e Lista de Desejos',
                'logout'            => 'Sair',
                'no-category-found' => 'Nenhuma categoria encontrada.',
                'orders'            => 'Pedidos',
                'profile'           => 'Perfil',
                'search'            => 'Pesquisar',
                'search-text'       => 'Pesquise produtos aqui',
                'sign-in'           => 'Entrar',
                'sign-up'           => 'Registrar',
                'submit'            => 'Enviar',
                'title'             => 'Conta',
                'welcome'           => 'Bem-vindo',
                'welcome-guest'     => 'Bem-vindo, Visitante',
                'wishlist'          => 'Lista de Desejos',

                'desktop' => [
                    'top' => [
                        'default-locale' => 'Idioma padrão',
                    ],
                ],

                'mobile' => [
                    'currencies' => 'Moedas',
                    'locales'    => 'Localizações',
                    'login'      => 'Inscrever-se ou Entrar',
                ],
            ],

            'footer' => [
                'about-us'               => 'Sobre Nós',
                'contact-us'             => 'Fale Conosco',
                'currency'               => 'Moeda',
                'customer-service'       => 'Atendimento ao Cliente',
                'email'                  => 'Email',
                'footer-content'         => 'Conteúdo do Rodapé',
                'footer-text'            => '© Direitos autorais 2010 - :current_year, Webkul Software (registrado na Índia). Todos os direitos reservados.',
                'locale'                 => 'Localização',
                'newsletter-text'        => 'Prepare-se para nossa Newsletter divertida!',
                'order-return'           => 'Pedido e Devoluções',
                'payment-policy'         => 'Política de Pagamento',
                'privacy-cookies-policy' => 'Política de Privacidade e Cookies',
                'shipping-policy'        => 'Política de Entrega',
                'subscribe'              => 'Inscrever-se',
                'subscribe-newsletter'   => 'Inscreva-se na Newsletter',
                'subscribe-stay-touch'   => 'Inscreva-se para ficar em contato.',
                'whats-new'              => 'O Que Há de Novo',
            ],
        ],

        'datagrid' => [
            'toolbar' => [
                'length-of' => ':length de',
                'results'   => ':total Resultados',
                'selected'  => ':total Selecionados',

                'mass-actions' => [
                    'must-select-a-mass-action'        => 'Você deve selecionar uma ação em massa.',
                    'must-select-a-mass-action-option' => 'Você deve selecionar uma opção de ação em massa.',
                    'no-records-selected'              => 'Nenhum registro foi selecionado.',
                    'select-action'                    => 'Selecionar Ação',
                ],

                'search' => [
                    'title' => 'Pesquisar',
                ],

                'filter' => [
                    'apply-filter' => 'Aplicar Filtros',
                    'title'        => 'Filtro',

                    'dropdown' => [
                        'select' => 'Selecionar',

                        'searchable' => [
                            'at-least-two-chars' => 'Digite pelo menos 2 caracteres...',
                            'no-results'         => 'Nenhum resultado encontrado...',
                        ],
                    ],

                    'custom-filters' => [
                        'clear-all' => 'Limpar Tudo',
                    ],
                ],
            ],

            'table' => [
                'actions'              => 'Ações',
                'next-page'            => 'Próxima Página',
                'no-records-available' => 'Nenhum registro disponível.',
                'of'                   => 'de :total entradas',
                'page-navigation'      => 'Navegação de Página',
                'page-number'          => 'Número da Página',
                'previous-page'        => 'Página Anterior',
                'showing'              => 'Mostrando :firstItem',
                'to'                   => 'a :lastItem',
            ],
        ],

        'modal' => [
            'default-content' => 'Conteúdo Padrão',
            'default-header'  => 'Cabeçalho Padrão',

            'confirm' => [
                'agree-btn'    => 'Concordar',
                'disagree-btn' => 'Discordar',
                'message'      => 'Tem certeza de que deseja realizar esta ação?',
                'title'        => 'Você tem certeza?',
            ],
        ],

        'products' => [
            'card' => [
                'add-to-cart'            => 'Adicionar ao Carrinho',
                'add-to-compare'         => 'Adicionar à Lista de Comparação',
                'add-to-compare-success' => 'Item adicionado com sucesso à lista de comparação.',
                'add-to-wishlist'        => 'Adicionar à Lista de Desejos',
                'already-in-compare'     => 'O item já está na lista de comparação.',
                'new'                    => 'Novo',
                'review-description'     => 'Seja o primeiro a avaliar este produto',
                'sale'                   => 'Venda',
            ],

            'carousel' => [
                'next'     => 'Próximo',
                'previous' => 'Anterior',
                'view-all' => 'Ver Todos',
            ],

            'ratings' => [
                'title' => 'Avaliações',
            ],
        ],

        'range-slider' => [
            'max-range' => 'Faixa Máxima',
            'min-range' => 'Faixa Mínima',
            'range'     => 'Faixa:',
        ],

        'carousel' => [
            'image-slide' => 'Slide de Imagem',
            'next'        => 'Próximo',
            'previous'    => 'Anterior',
        ],

        'quantity-changer' => [
            'decrease-quantity' => 'Diminuir Quantidade',
            'increase-quantity' => 'Aumentar Quantidade',
        ],
    ],

    'products' => [
        'prices' => [
            'grouped' => [
                'starting-at' => 'A partir de',
            ],

            'configurable' => [
                'as-low-as' => 'A partir de',
            ],
        ],

        'sort-by' => [
            'title'   => 'Ordenar Por',
        ],

        'view' => [
            'type' => [
                'configurable' => [
                    'select-options'       => 'Por favor, selecione uma opção',
                    'select-above-options' => 'Por favor, selecione as opções acima',
                ],

                'bundle' => [
                    'none'         => 'Nenhum',
                    'total-amount' => 'Valor Total',
                ],

                'downloadable' => [
                    'links'   => 'Links',
                    'sample'  => 'Amostra',
                    'samples' => 'Amostras',
                ],

                'grouped' => [
                    'name' => 'Nome',
                ],
            ],

            'gallery' => [
                'product-image'   => 'Imagem do Produto',
                'thumbnail-image' => 'Imagem Miniatura',
            ],

            'reviews' => [
                'attachments'      => 'Anexos',
                'cancel'           => 'Cancelar',
                'comment'          => 'Comentário',
                'customer-review'  => 'Avaliações de Clientes',
                'empty-review'     => 'Nenhuma avaliação encontrada, seja o primeiro a avaliar este produto',
                'failed-to-upload' => 'A imagem falhou ao carregar',
                'load-more'        => 'Carregar Mais',
                'name'             => 'Nome',
                'rating'           => 'Avaliação',
                'ratings'          => 'Avaliações',
                'submit-review'    => 'Enviar Avaliação',
                'success'          => 'Avaliação enviada com sucesso.',
                'title'            => 'Título',
                'translate'        => 'Traduzir',
                'translating'      => 'Traduzindo...',
                'write-a-review'   => 'Escrever uma Avaliação',
            ],

            'add-to-cart'            => 'Adicionar ao Carrinho',
            'add-to-compare'         => 'Produto adicionado à comparação.',
            'add-to-wishlist'        => 'Adicionar à Lista de Desejos',
            'additional-information' => 'Informações Adicionais',
            'already-in-compare'     => 'Produto já adicionado à comparação.',
            'buy-now'                => 'Comprar Agora',
            'compare'                => 'Comparar',
            'description'            => 'Descrição',
            'related-product-title'  => 'Produtos Relacionados',
            'review'                 => 'Avaliações',
            'tax-inclusive'          => 'Inclusivo de todos os impostos',
            'up-sell-title'          => 'Encontramos outros produtos que você pode gostar!',
        ],

        'type' => [
            'abstract' => [
                'offers' => 'Compre :qty por :price cada e economize :discount',
            ],
        ],
    ],

    'categories' => [
        'filters' => [
            'clear-all' => 'Limpar Tudo',
            'filter'    => 'Filtro',
            'filters'   => 'Filtros:',
            'sort'      => 'Ordenar',
        ],

        'toolbar' => [
            'grid' => 'Grade',
            'list' => 'Lista',
            'show' => 'Mostrar',
        ],

        'view' => [
            'empty'     => 'Nenhum produto disponível nesta categoria',
            'load-more' => 'Carregar Mais',
        ],
    ],

    'search' => [
        'title'   => 'Resultados da pesquisa para : :query',
        'results' => 'Resultados da pesquisa',

        'images' => [
            'index' => [
                'only-images-allowed'  => 'Apenas imagens (.jpeg, .jpg, .png, ..) são permitidas.',
                'search'               => 'Pesquisar',
                'size-limit-error'     => 'Erro de Limite de Tamanho',
                'something-went-wrong' => 'Algo deu errado, por favor, tente novamente mais tarde.',
            ],

            'results' => [
                'analyzed-keywords' => 'Palavras-chave Analisadas:',
            ],
        ],
    ],

    'compare' => [
        'already-added'      => 'Item já adicionado à lista de comparação',
        'delete-all'         => 'Excluir Todos',
        'empty-text'         => 'Você não tem itens na sua lista de comparação',
        'item-add-success'   => 'Item adicionado com sucesso à lista de comparação',
        'product-compare'    => 'Comparação de Produtos',
        'remove-all-success' => 'Todos os itens foram removidos com sucesso.',
        'remove-error'       => 'Algo deu errado, por favor, tente novamente mais tarde.',
        'remove-success'     => 'Item removido com sucesso.',
        'title'              => 'Comparação de Produtos',
    ],

    'checkout' => [
        'success' => [
            'info'          => 'Enviaremos por e-mail os detalhes do seu pedido e informações de rastreamento.',
            'order-id-info' => 'Seu ID de pedido é #:order_id',
            'thanks'        => 'Obrigado pelo seu pedido!',
            'title'         => 'Pedido realizado com sucesso',
        ],

        'cart' => [
            'continue-to-checkout'      => 'Continuar para o Checkout',
            'illegal'                   => 'A quantidade não pode ser menor que um.',
            'inactive-add'              => 'Item inativo não pode ser adicionado ao carrinho.',
            'inactive'                  => 'O item foi desativado e, portanto, removido do carrinho.',
            'inventory-warning'         => 'A quantidade solicitada não está disponível, por favor, tente novamente mais tarde.',
            'item-add-to-cart'          => 'Item Adicionado com Sucesso',
            'minimum-order-message'     => 'O valor mínimo do pedido é de',
            'missing-fields'            => 'Alguns campos obrigatórios estão faltando para este produto.',
            'missing-options'           => 'Opções estão faltando para este produto.',
            'paypal-payment-cancelled'  => 'O pagamento Paypal foi cancelado.',
            'qty-missing'               => 'Pelo menos um produto deve ter mais de 1 quantidade.',
            'return-to-shop'            => 'Voltar para a Loja',
            'rule-applied'              => 'Regra do carrinho aplicada',
            'select-hourly-duration'    => 'Selecione uma duração de slot de uma hora.',
            'success-remove'            => 'Item removido com sucesso do carrinho.',
            'suspended-account-message' => 'Sua conta foi suspensa.',

            'index' => [
                'bagisto'                  => 'Bagisto',
                'cart'                     => 'Carrinho',
                'continue-shopping'        => 'Continuar Comprando',
                'empty-product'            => 'Você não tem um produto no carrinho.',
                'excl-tax'                 => 'Excl. Imposto:',
                'home'                     => 'Página Inicial',
                'items-selected'           => ':count Itens Selecionados',
                'move-to-wishlist'         => 'Mover para a Lista de Desejos',
                'move-to-wishlist-success' => 'Itens selecionados movidos com sucesso para a lista de desejos.',
                'price'                    => 'Preço',
                'product-name'             => 'Nome do Produto',
                'quantity'                 => 'Quantidade',
                'quantity-update'          => 'Quantidade atualizada com sucesso',
                'remove'                   => 'Remover',
                'remove-selected-success'  => 'Itens selecionados removidos com sucesso do carrinho.',
                'see-details'              => 'Ver Detalhes',
                'select-all'               => 'Selecionar tudo',
                'select-cart-item'         => 'Selecionar item do carrinho',
                'tax'                      => 'Imposto',
                'total'                    => 'Total',
                'update-cart'              => 'Atualizar Carrinho',
                'view-cart'                => 'Ver Carrinho',

                'cross-sell' => [
                    'title' => 'Mais escolhas',
                ],
            ],

            'mini-cart' => [
                'continue-to-checkout' => 'Continuar para o Checkout',
                'empty-cart'           => 'Seu carrinho está vazio',
                'excl-tax'             => 'Excl. Imposto:',
                'offer-on-orders'      => 'Receba até 30% de DESCONTO no seu 1º pedido',
                'remove'               => 'Remover',
                'see-details'          => 'Ver Detalhes',
                'shopping-cart'        => 'Carrinho de Compras',
                'subtotal'             => 'Subtotal',
                'view-cart'            => 'Ver Carrinho',
            ],

            'summary' => [
                'cart-summary'              => 'Resumo do Carrinho',
                'delivery-charges-excl-tax' => 'Taxas de Entrega (Excl. Imposto)',
                'delivery-charges-incl-tax' => 'Taxas de Entrega (Incl. Imposto)',
                'delivery-charges'          => 'Taxas de Entrega',
                'discount-amount'           => 'Valor do Desconto',
                'grand-total'               => 'Total Geral',
                'place-order'               => 'Finalizar Pedido',
                'proceed-to-checkout'       => 'Prosseguir para o Checkout',
                'sub-total-excl-tax'        => 'Subtotal (Excl. Imposto)',
                'sub-total-incl-tax'        => 'Subtotal (Incl. Imposto)',
                'sub-total'                 => 'Subtotal',
                'tax'                       => 'Imposto',

                'estimate-shipping' => [
                    'country'        => 'País',
                    'info'           => 'Digite o destino para obter uma estimativa de frete e imposto.',
                    'postcode'       => 'CEP',
                    'select-country' => 'Selecionar País',
                    'select-state'   => 'Selecionar Estado',
                    'state'          => 'Estado',
                    'title'          => 'Estimar Frete e Imposto',
                ],
            ],
        ],

        'onepage' => [
            'address' => [
                'add-new'                => 'Adicionar novo endereço',
                'add-new-address'        => 'Adicionar novo endereço',
                'back'                   => 'Voltar',
                'billing-address'        => 'Endereço de faturamento',
                'check-billing-address'  => 'Endereço de faturação em falta.',
                'check-shipping-address' => 'Endereço de envio em falta.',
                'city'                   => 'Cidade',
                'company-name'           => 'Nome da empresa',
                'confirm'                => 'Confirmar',
                'country'                => 'País',
                'email'                  => 'E-mail',
                'first-name'             => 'Nome',
                'last-name'              => 'Sobrenome',
                'postcode'               => 'CEP',
                'proceed'                => 'Prosseguir',
                'same-as-billing'        => 'Usar o mesmo endereço para envio?',
                'save'                   => 'Salvar',
                'save-address'           => 'Salvar no livro de endereços',
                'select-country'         => 'Selecionar país',
                'select-state'           => 'Selecionar estado',
                'shipping-address'       => 'Endereço de envio',
                'state'                  => 'Estado',
                'street-address'         => 'Endereço',
                'telephone'              => 'Telefone',
                'title'                  => 'Endereço',
            ],

            'index' => [
                'checkout' => 'Checkout',
                'home'     => 'Página Inicial',
            ],

            'payment' => [
                'payment-method' => 'Método de Pagamento',
            ],

            'shipping' => [
                'shipping-method' => 'Método de Entrega',
            ],

            'summary' => [
                'cart-summary'              => 'Resumo do Carrinho',
                'delivery-charges-excl-tax' => 'Taxas de Entrega (Excl. Imposto)',
                'delivery-charges-incl-tax' => 'Taxas de Entrega (Incl. Imposto)',
                'delivery-charges'          => 'Taxas de Entrega',
                'discount-amount'           => 'Valor do Desconto',
                'excl-tax'                  => 'Excl. Imposto:',
                'grand-total'               => 'Total Geral',
                'place-order'               => 'Finalizar Pedido',
                'price_&_qty'               => ':price × :qty',
                'processing'                => 'Processando',
                'sub-total-excl-tax'        => 'Subtotal (Excl. Imposto)',
                'sub-total-incl-tax'        => 'Subtotal (Incl. Imposto)',
                'sub-total'                 => 'Subtotal',
                'tax'                       => 'Imposto',
            ],
        ],

        'coupon' => [
            'already-applied' => 'Código do cupom já aplicado.',
            'applied'         => 'Cupom aplicado',
            'apply'           => 'Aplicar Cupom',
            'apply-issue'     => 'O código do cupom não pode ser aplicado.',
            'button-title'    => 'Aplicar',
            'code'            => 'Código do Cupom',
            'discount'        => 'Desconto do Cupom',
            'enter-your-code' => 'Digite seu código',
            'error'           => 'Algo deu errado',
            'invalid'         => 'Código do cupom é inválido.',
            'remove'          => 'Remover Cupom',
            'subtotal'        => 'Subtotal',
            'success-apply'   => 'Código do cupom aplicado com sucesso.',
        ],

        'login' => [
            'email'    => 'E-mail',
            'password' => 'Senha',
            'title'    => 'Entrar',
        ],
    ],

    'home' => [
        'contact' => [
            'about'         => 'Deixe-nos uma mensagem e entraremos em contato o mais rápido possível',
            'desc'          => 'No que você está pensando?',
            'describe-here' => 'Descreva aqui',
            'email'         => 'E-mail',
            'message'       => 'Mensagem',
            'name'          => 'Nome',
            'phone-number'  => 'Número de telefone',
            'submit'        => 'Enviar',
            'title'         => 'Entre em contato',
        ],

        'index' => [
            'offer'               => 'GANHE ATÉ 40% DE DESCONTO no seu 1º pedido. COMPRE AGORA',
            'resend-verify-email' => 'Reenviar E-mail de Verificação',
            'verify-email'        => 'Verifique sua conta de e-mail',
        ],

        'thanks-for-contact' => 'Obrigado por entrar em contato conosco com seus comentários e perguntas. Responderemos a você em breve.',
    ],

    'partials' => [
        'pagination' => [
            'pagination-showing' => 'Mostrando :firstItem a :lastItem de :total entradas',
        ],
    ],

    'errors' => [
        'go-to-home' => 'Ir para a Página Inicial',

        '404' => [
            'description' => 'Ops! A página que você está procurando está em férias. Parece que não conseguimos encontrar o que você estava procurando.',
            'title'       => '404 Página Não Encontrada',
        ],

        '401' => [
            'description' => 'Ops! Parece que você não tem permissão para acessar esta página. Parece que você está sem as credenciais necessárias.',
            'title'       => '401 Não Autorizado',
        ],

        '403' => [
            'description' => 'Ops! Esta página está fora dos limites. Parece que você não tem as permissões necessárias para visualizar este conteúdo.',
            'title'       => '403 Proibido',
        ],

        '500' => [
            'description' => 'Ops! Algo deu errado. Parece que estamos tendo problemas para carregar a página que você está procurando.',
            'title'       => '500 Erro Interno do Servidor',
        ],

        '503' => [
            'description' => 'Ops! Parece que estamos temporariamente fora do ar para manutenção. Por favor, volte em breve.',
            'title'       => '503 Serviço Indisponível',
        ],
    ],

    'layouts' => [
        'address'               => 'Endereço',
        'downloadable-products' => 'Produtos para Download',
        'my-account'            => 'Minha Conta',
        'orders'                => 'Pedidos',
        'profile'               => 'Perfil',
        'reviews'               => 'Avaliações',
        'wishlist'              => 'Lista de Desejos',
    ],

    'subscription' => [
        'already'             => 'Você já está inscrito na nossa newsletter.',
        'subscribe-success'   => 'Você se inscreveu com sucesso na nossa newsletter.',
        'unsubscribe-success' => 'Você cancelou com sucesso a inscrição na nossa newsletter.',
    ],

    'emails' => [
        'dear'   => 'Prezado(a) :customer_name',
        'thanks' => 'Se você precisar de qualquer tipo de ajuda, entre em contato conosco em <a href=":link" style=":style">:email</a>.<br/>Obrigado!',

        'customers' => [
            'registration' => [
                'credentials-description' => 'Sua conta foi criada. Os detalhes da sua conta estão abaixo:',
                'description'             => 'Sua conta foi criada com sucesso e você pode fazer login usando seu e-mail e senha. Ao fazer o login, você poderá acessar outros serviços, incluindo a revisão de pedidos anteriores, listas de desejos e a edição das informações da sua conta.',
                'greeting'                => 'Bem-vindo e obrigado por se registrar conosco!',
                'password'                => 'Senha',
                'sign-in'                 => 'Entrar',
                'subject'                 => 'Novo Registro de Cliente',
                'username-email'          => 'Nome de usuário/Email',
            ],

            'forgot-password' => [
                'description'    => 'Você está recebendo este e-mail porque recebemos uma solicitação de redefinição de senha para a sua conta.',
                'greeting'       => 'Esqueceu a Senha!',
                'reset-password' => 'Redefinir Senha',
                'subject'        => 'E-mail de Redefinição de Senha',
            ],

            'update-password' => [
                'description' => 'Você está recebendo este e-mail porque você atualizou sua senha.',
                'greeting'    => 'Senha Atualizada!',
                'subject'     => 'Senha Atualizada',
            ],

            'verification' => [
                'description'  => 'Por favor, clique no botão abaixo para verificar seu endereço de e-mail.',
                'greeting'     => 'Bem-vindo!',
                'subject'      => 'E-mail de Verificação de Conta',
                'verify-email' => 'Verificar Endereço de E-mail',
            ],

            'commented' => [
                'description' => 'Nota é - :note',
                'subject'     => 'Novo Comentário Adicionado',
            ],

            'subscribed' => [
                'description' => 'Parabéns e bem-vindo à nossa comunidade de newsletter! Estamos animados por tê-lo a bordo e mantê-lo atualizado com as últimas notícias, tendências e ofertas exclusivas.',
                'greeting'    => 'Bem-vindo à nossa newsletter!',
                'subject'     => 'Você! Inscreva-se na Nossa Newsletter',
                'unsubscribe' => 'Cancelar Inscrição',
            ],
        ],

        'contact-us' => [
            'contact-from'    => 'via Formulário de Contato do Website',
            'reply-to-mail'   => 'por favor, responda a este e-mail.',
            'reach-via-phone' => 'Alternativamente, você pode nos contatar por telefone em',
            'inquiry-from'    => 'Consulta de',
            'to'              => 'Para entrar em contato',
        ],

        'orders' => [
            'created' => [
                'greeting' => 'Obrigado pelo seu Pedido :order_id feito em :created_at',
                'subject'  => 'Confirmação de Novo Pedido',
                'summary'  => 'Resumo do Pedido',
                'title'    => 'Confirmação de Pedido!',
            ],

            'invoiced' => [
                'greeting' => 'Sua fatura #:invoice_id para o Pedido :order_id criado em :created_at',
                'subject'  => 'Confirmação de Nova Fatura',
                'summary'  => 'Resumo da Fatura',
                'title'    => 'Confirmação de Fatura!',
            ],

            'shipped' => [
                'greeting' => 'Seu Pedido :order_id feito em :created_at foi enviado',
                'subject'  => 'Confirmação de Novo Envio',
                'summary'  => 'Resumo do Envio',
                'title'    => 'Pedido Enviado!',
            ],

            'refunded' => [
                'greeting' => 'O reembolso foi iniciado para o Pedido :order_id feito em :created_at',
                'subject'  => 'Confirmação de Novo Reembolso',
                'summary'  => 'Resumo do Reembolso',
                'title'    => 'Pedido Reembolsado!',
            ],

            'canceled' => [
                'greeting' => 'Seu Pedido :order_id feito em :created_at foi cancelado',
                'subject'  => 'Novo Pedido Cancelado',
                'summary'  => 'Resumo do Pedido',
                'title'    => 'Pedido Cancelado!',
            ],

            'commented' => [
                'subject' => 'Novo Comentário Adicionado',
                'title'   => 'Novo comentário adicionado ao seu Pedido :order_id feito em :created_at',
            ],

            'billing-address'            => 'Endereço de Cobrança',
            'carrier'                    => 'Transportadora',
            'contact'                    => 'Contato',
            'discount'                   => 'Desconto',
            'excl-tax'                   => 'Excl. Imposto: ',
            'grand-total'                => 'Total Geral',
            'name'                       => 'Nome',
            'payment'                    => 'Pagamento',
            'price'                      => 'Preço',
            'qty'                        => 'Quantidade',
            'shipping-address'           => 'Endereço de Envio',
            'shipping-handling-excl-tax' => 'Frete e Manuseio (Excl. Imposto)',
            'shipping-handling-incl-tax' => 'Frete e Manuseio (Incl. Imposto)',
            'shipping-handling'          => 'Frete e Manuseio',
            'shipping'                   => 'Envio',
            'sku'                        => 'SKU',
            'subtotal-excl-tax'          => 'Subtotal (Excl. Imposto)',
            'subtotal-incl-tax'          => 'Subtotal (Incl. Imposto)',
            'subtotal'                   => 'Subtotal',
            'tax'                        => 'Imposto',
            'tracking-number'            => 'Número de Rastreamento: :tracking_number',
        ],
    ],
];
