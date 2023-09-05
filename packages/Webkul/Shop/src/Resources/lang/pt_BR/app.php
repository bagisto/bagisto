<?php

return [
    'configurations' => [
        'settings-title'      => 'Configurações',
        'settings-title-info' => 'As configurações referem-se a escolhas configuráveis que controlam como um sistema, aplicativo ou dispositivo se comporta, adaptado às preferências e requisitos do usuário.',
    ],

    'customers' => [
        'forgot-password' => [
            'title'                => 'Recuperar Senha',
            'email'                => 'E-mail',
            'forgot-password-text' => 'Se você esqueceu sua senha, recupere-a inserindo seu endereço de e-mail.',
            'submit'               => 'Redefinir Senha',
            'page-title'           => 'Esqueceu sua senha?',
            'back'                 => 'Voltar para Entrar?',
            'sign-in-button'       => 'Entrar',
            'footer'               => '© Copyright 2010 - 2022, Webkul Software (Registrado na Índia). Todos os direitos reservados.',
        ],

        'reset-password' => [
            'title'            => 'Redefinir Senha',
            'email'            => 'E-mail Registrado',
            'password'         => 'Senha',
            'confirm-password' => 'Confirmar Senha',
            'back-link-title'  => 'Voltar para Entrar',
            'submit-btn-title' => 'Redefinir Senha',
            'footer'           => '© Copyright 2010 - 2022, Webkul Software (Registrado na Índia). Todos os direitos reservados.',
        ],

        'login-form' => [
            'page-title'          => 'Login do Cliente',
            'form-login-text'     => 'Se você tem uma conta, faça login com seu endereço de e-mail.',
            'show-password'       => 'Mostrar Senha',
            'title'               => 'Entrar',
            'email'               => 'E-mail',
            'password'            => 'Senha',
            'forgot-pass'         => 'Esqueceu a Senha?',
            'button-title'        => 'Entrar',
            'new-customer'        => 'Novo cliente?',
            'create-your-account' => 'Crie sua conta',
            'footer'              => '© Copyright 2010 - 2022, Webkul Software (Registrado na Índia). Todos os direitos reservados.',
            'invalid-credentials' => 'Verifique suas credenciais e tente novamente.',
            'not-activated'       => 'Sua ativação aguarda aprovação do administrador',
            'verify-first'        => 'Verifique primeiro sua conta de e-mail.',
        ],

        'signup-form' => [
            'page-title'                  => 'Tornar-se Usuário',
            'form-signup-text'            => 'Se você é novo em nossa loja, ficamos felizes em tê-lo como membro.',
            'sign-in-button'              => 'Entrar',
            'first-name'                  => 'Primeiro Nome',
            'last-name'                   => 'Sobrenome',
            'email'                       => 'E-mail',
            'password'                    => 'Senha',
            'confirm-pass'                => 'Confirmar Senha',
            'subscribe-to-newsletter'     => 'Inscrever-se na newsletter',
            'button-title'                => 'Registrar',
            'account-exists'              => 'Já tem uma conta?',
            'footer'                      => '© Copyright 2010 - 2022, Webkul Software (Registrado na Índia). Todos os direitos reservados.',
            'success-verify'              => 'Conta criada com sucesso, um e-mail foi enviado para verificação.',
            'success-verify-email-unsent' => 'Conta criada com sucesso, mas o e-mail de verificação não foi enviado.',
            'success'                     => 'Conta criada com sucesso.',
            'verified'                    => 'Sua conta foi verificada, tente fazer login agora.',
            'verify-failed'               => 'Não foi possível verificar sua conta de e-mail.',
            'verification-not-sent'       => 'Erro! Problema ao enviar e-mail de verificação, tente novamente mais tarde.',
            'verification-sent'           => 'E-mail de verificação enviado',
        ],

        'account' => [
            'home'      => 'Início',
            'profile'   => [
                'title'                   => 'Perfil',
                'first-name'              => 'Primeiro Nome',
                'last-name'               => 'Sobrenome',
                'gender'                  => 'Gênero',
                'dob'                     => 'Data de Nascimento',
                'email'                   => 'E-mail',
                'delete-profile'          => 'Excluir Perfil',
                'edit-profile'            => 'Editar Perfil',
                'edit'                    => 'Editar',
                'phone'                   => 'Telefone',
                'current-password'        => 'Senha Atual',
                'new-password'            => 'Nova Senha',
                'confirm-password'        => 'Confirmar Senha',
                'delete-success'          => 'Cliente excluído com sucesso',
                'wrong-password'          => 'Senha Incorreta!',
                'delete-failed'           => 'Erro encontrado ao excluir o cliente.',
                'order-pending'           => 'Não é possível excluir a conta do cliente porque há pedido(s) pendente(s) ou em estado de processamento.',
                'subscribe-to-newsletter' => 'Inscrever-se na newsletter',
                'delete'                  => 'Excluir',
                'enter-password'          => 'Digite sua senha',
                'male'                    => 'Masculino',
                'female'                  => 'Feminino',
                'other'                   => 'Outro',
                'save'                    => 'Salvar',
            ],

            'addresses' => [
                'title'            => 'Endereço',
                'edit'             => 'Editar',
                'edit-address'     => 'Editar Endereço',
                'delete'           => 'Excluir',
                'set-as-default'   => 'Definir como Padrão',
                'add-address'      => 'Adicionar Endereço',
                'company-name'     => 'Nome da Empresa',
                'vat-id'           => 'ID do IVA',
                'address-1'        => 'Endereço 1',
                'address-2'        => 'Endereço 2',
                'city'             => 'Cidade',
                'state'            => 'Estado',
                'select-country'   => 'Selecionar País',
                'country'          => 'País',
                'default-address'  => 'Endereço Padrão',
                'first-name'       => 'Primeiro Nome',
                'last-name'        => 'Sobrenome',
                'phone'            => 'Telefone',
                'street-address'   => 'Endereço de Rua',
                'post-code'        => 'Código Postal',
                'empty-address'    => 'Você ainda não adicionou um endereço à sua conta.',
                'create-success'   => 'Endereço adicionado com sucesso.',
                'edit-success'     => 'Endereço atualizado com sucesso.',
                'default-delete'   => 'O endereço padrão não pode ser alterado.',
                'delete-success'   => 'Endereço excluído com sucesso',
                'save'             => 'Salvar',
                'security-warning' => 'Atividade suspeita encontrada!!!',
            ],

            'orders' => [
                'title'      => 'Pedidos',
                'order-id'   => 'ID do Pedido',
                'order'      => 'Pedido',
                'order-date' => 'Data do Pedido',
                'total'      => 'Total',

                'status' => [
                    'title' => 'Status',

                    'options' => [
                        'processing'      => 'Em Processamento',
                        'completed'       => 'Concluído',
                        'canceled'        => 'Cancelado',
                        'closed'          => 'Fechado',
                        'pending'         => 'Pendente',
                        'pending-payment' => 'Pagamento Pendente',
                        'fraud'           => 'Fraude',
                    ],
                ],

                'action'      => 'Ação',
                'empty-order' => 'Você ainda não fez nenhum pedido',

                'view' => [
                    'title'              => 'Visualizar',
                    'page-title'         => 'Pedido #:order_id',
                    'total'              => 'Total',
                    'shipping-address'   => 'Endereço de Entrega',
                    'billing-address'    => 'Endereço de Cobrança',
                    'shipping-method'    => 'Método de Entrega',
                    'payment-method'     => 'Método de Pagamento',
                    'cancel-btn-title'   => 'Cancelar',
                    'cancel-confirm-msg' => 'Tem certeza de que deseja cancelar este pedido?',
                    'cancel-success'     => 'Seu pedido foi cancelado',
                    'cancel-error'       => 'Seu pedido não pode ser cancelado.',

                    'information' => [
                        'info'              => 'Informação',
                        'placed-on'         => 'Colocado em',
                        'sku'               => 'SKU',
                        'product-name'      => 'Nome',
                        'price'             => 'Preço',
                        'item-status'       => 'Status do Item',
                        'subtotal'          => 'Subtotal',
                        'tax-percent'       => 'Percentagem de Imposto',
                        'tax-amount'        => 'Valor do Imposto',
                        'tax'               => 'Imposto',
                        'grand-total'       => 'Total Geral',
                        'item-ordered'      => 'Encomendado (:qty_ordered)',
                        'item-invoice'      => 'Faturado (:qty_invoiced)',
                        'item-shipped'      => 'enviado (:qty_shipped)',
                        'item-canceled'     => 'Cancelado (:qty_canceled)',
                        'item-refunded'     => 'Reembolsado (:qty_refunded)',
                        'shipping-handling' => 'Envio e Manipulação',
                        'discount'          => 'Desconto',
                        'total-paid'        => 'Total Pago',
                        'total-refunded'    => 'Total Reembolsado',
                        'total-due'         => 'Total Devido',
                    ],

                    'invoices'  => [
                        'invoices'           => 'Faturas',
                        'individual-invoice' => 'Fatura #:invoice_id',
                        'sku'                => 'SKU',
                        'product-name'       => 'Nome',
                        'price'              => 'Preço',
                        'products-ordered'   => 'Produtos Encomendados',
                        'qty'                => 'Qtde',
                        'subtotal'           => 'Subtotal',
                        'tax-amount'         => 'Valor do Imposto',
                        'grand-total'        => 'Total Geral',
                        'shipping-handling'  => 'Envio & Manuseio',
                        'discount'           => 'Desconto',
                        'tax'                => 'Imposto',
                        'print'              => 'Imprimir',
                    ],

                    'shipments' => [
                        'shipments'           => 'Remessas',
                        'tracking-number'     => 'Número de Rastreamento',
                        'individual-shipment' => 'Remessa #:shipment_id',
                        'sku'                 => 'SKU',
                        'product-name'        => 'Nome',
                        'qty'                 => 'Qtde',
                        'subtotal'            => 'Subtotal',
                    ],

                    'refunds'  => [
                        'refunds'           => 'Reembolsos',
                        'individual-refund' => 'Reembolso #:refund_id',
                        'sku'               => 'SKU',
                        'product-name'      => 'Nome',
                        'price'             => 'Preço',
                        'qty'               => 'Qtde',
                        'tax-amount'        => 'Valor do Imposto',
                        'subtotal'          => 'Subtotal',
                        'grand-total'       => 'Total Geral',
                        'no-result-found'   => 'Não conseguimos encontrar nenhum registro.',
                        'shipping-handling' => 'Envio & Manuseio',
                        'discount'          => 'Desconto',
                        'tax'               => 'Imposto',
                        'adjustment-refund' => 'Reembolso de Ajuste',
                        'adjustment-fee'    => 'Taxa de Ajuste',
                    ],
                ],
            ],

            'reviews'    => [
                'title'        => 'Avaliações',
                'empty-review' => 'Você ainda não avaliou nenhum produto',
            ],

            'downloadable-products'  => [
                'name'                => 'Produtos para Download',
                'orderId'             => 'ID do Pedido',
                'title'               => 'Título',
                'date'                => 'Data',
                'status'              => 'Status',
                'remaining-downloads' => 'Downloads Restantes',
                'records-found'       => 'Registro(s) encontrado(s)',
                'empty-product'       => 'Você não tem nenhum produto para download',
                'download-error'      => 'O link de download expirou.',
                'payment-error'       => 'O pagamento não foi feito para este download.',
            ],

            'wishlist' => [
                'page-title'         => 'Lista de Desejos',
                'title'              => 'Lista de Desejos',
                'color'              => 'Cor',
                'remove'             => 'Remover',
                'delete-all'         => 'Excluir Todos',
                'empty'              => 'Nenhum produto foi adicionado à página de lista de desejos.',
                'move-to-cart'       => 'Mover para o Carrinho',
                'profile'            => 'Perfil',
                'removed'            => 'Item removido com sucesso da lista de desejos',
                'remove-fail'        => 'O item não pode ser removido da lista de desejos',
                'moved'              => 'Item movido com sucesso para o carrinho',
                'product-removed'    => 'O produto não está mais disponível, pois foi removido pelo administrador',
                'remove-all-success' => 'Todos os itens da sua lista de desejos foram removidos',
                'see-details'        => 'Ver Detalhes',
            ],
        ],
    ],

    'components' => [
        'layouts' => [
            'header' => [
                'title'         => 'Conta',
                'welcome'       => 'Bem-vindo',
                'welcome-guest' => 'Bem-vindo, Visitante',
                'dropdown-text' => 'Gerenciar Carrinho, Pedidos e Lista de Desejos',
                'sign-in'       => 'Entrar',
                'sign-up'       => 'Cadastrar-se',
                'account'       => 'Conta',
                'cart'          => 'Carrinho',
                'profile'       => 'Perfil',
                'wishlist'      => 'Lista de Desejos',
                'compare'       => 'Comparar',
                'orders'        => 'Pedidos',
                'cart'          => 'Carrinho',
                'logout'        => 'Sair',
                'search-text'   => 'Pesquise produtos aqui',
                'search'        => 'Pesquisar',
            ],

            'footer' => [
                'newsletter-text'        => 'Prepare-se para nossa newsletter divertida!',
                'subscribe-stay-touch'   => 'Inscreva-se para ficar em contato.',
                'subscribe-newsletter'   => 'Assinar Newsletter',
                'subscribe'              => 'Assinar',
                'footer-text'            => '© Copyright 2010 - 2023, Webkul Software (Registrado na Índia). Todos os direitos reservados.',
                'locale'                 => 'Localidade',
                'currency'               => 'Moeda',
                'about-us'               => 'Sobre Nós',
                'customer-service'       => 'Atendimento ao Cliente',
                'whats-new'              => 'O que há de novo',
                'contact-us'             => 'Fale Conosco',
                'order-return'           => 'Pedido e Devoluções',
                'payment-policy'         => 'Política de Pagamento',
                'shipping-policy'        => 'Política de Envio',
                'privacy-cookies-policy' => 'Política de Privacidade e Cookies',
            ],
        ],

        'datagrid' => [
            'toolbar' => [
                'mass-actions' => [
                    'select-action' => 'Selecionar Ação',
                    'select-option' => 'Selecionar Opção',
                    'submit'        => 'Enviar',
                ],

                'filter' => [
                    'title' => 'Filtrar',
                ],

                'search' => [
                    'title' => 'Pesquisar',
                ],
            ],

            'filters' => [
                'title' => 'Aplicar Filtros',

                'custom-filters' => [
                    'title'     => 'Filtros Personalizados',
                    'clear-all' => 'Limpar Tudo',
                ],

                'date-options' => [
                    'today'             => 'Hoje',
                    'yesterday'         => 'Ontem',
                    'this-week'         => 'Esta Semana',
                    'this-month'        => 'Este Mês',
                    'last-month'        => 'Mês Passado',
                    'last-three-months' => 'Últimos 3 Meses',
                    'last-six-months'   => 'Últimos 6 Meses',
                    'this-year'         => 'Este Ano',
                ],
            ],

            'table' => [
                'actions'              => 'Ações',
                'no-records-available' => 'Nenhum registro disponível.',
            ],
        ],

        'products'   => [
            'card' => [
                'new'                => 'Novo',
                'sale'               => 'Venda',
                'review-description' => 'Seja o primeiro a avaliar este produto',
                'add-to-compare'     => 'Item adicionado com sucesso à lista de comparação.',
                'already-in-compare' => 'O item já está na lista de comparação.',
                'add-to-cart'        => 'Adicionar ao Carrinho',
            ],

            'carousel' => [
                'view-all' => 'Ver Tudo',
            ],
        ],

        'range-slider' => [
            'range' => 'Intervalo:',
        ],
    ],

    'products' => [
        'reviews'                => 'Avaliações',
        'add-to-cart'            => 'Adicionar ao Carrinho',
        'add-to-compare'         => 'Produto adicionado na comparação.',
        'already-in-compare'     => 'O produto já está na comparação.',
        'buy-now'                => 'Comprar Agora',
        'compare'                => 'Comparar',
        'rating'                 => 'Avaliação',
        'title'                  => 'Título',
        'comment'                => 'Comentário',
        'submit-review'          => 'Enviar Avaliação',
        'customer-review'        => 'Avaliações de Clientes',
        'write-a-review'         => 'Escreva uma Avaliação',
        'stars'                  => 'Estrelas',
        'share'                  => 'Compartilhar',
        'empty-review'           => 'Nenhuma avaliação encontrada, seja o primeiro a avaliar este produto',
        'was-this-helpful'       => 'Esta avaliação foi útil?',
        'load-more'              => 'Carregar Mais',
        'add-image'              => 'Adicionar Imagem',
        'description'            => 'Descrição',
        'additional-information' => 'Informações Adicionais',
        'submit-success'         => 'Enviado com sucesso',
        'something-went-wrong'   => 'Algo deu errado',
        'in-stock'               => 'Em Estoque',
        'available-for-order'    => 'Disponível para Pedido',
        'out-of-stock'           => 'Sem Estoque',
        'related-product-title'  => 'Produtos Relacionados',
        'up-sell-title'          => 'Encontramos outros produtos que você pode gostar!',
        'new'                    => 'Novo',
        'as-low-as'              => 'A partir de',
        'starting-at'            => 'Começando em',
        'name'                   => 'Nome',
        'qty'                    => 'Quantidade',
        'offers'                 => 'Compre :qty por :price cada e economize :discount%',

        'sort-by'                => [
            'title'   => 'Ordenar Por',
            'options' => [
                'from-a-z'        => 'De A-Z',
                'from-z-a'        => 'De Z-A',
                'latest-first'    => 'Mais Recentes Primeiro',
                'oldest-first'    => 'Mais Antigos Primeiro',
                'cheapest-first'  => 'Mais Baratos Primeiro',
                'expensive-first' => 'Mais Caros Primeiro',
            ],
        ],

        'view' => [
            'type' => [
                'configurable' => [
                    'select-options'       => 'Por favor, selecione uma opção',
                    'select-above-options' => 'Por favor, selecione as opções acima',
                ],

                'bundle' => [
                    'none' => 'Nenhum',
                ],

                'downloadable' => [
                    'samples' => 'Amostras',
                    'links'   => 'Links',
                    'sample'  => 'Amostra',
                ],

                'grouped' => [
                    'name' => 'Nome',
                ],
            ],

            'reviews' => [
                'cancel'      => 'Cancelar',
                'success'     => 'Avaliação enviada com sucesso.',
                'attachments' => 'Anexos',
            ],
        ],

        'configurations' => [
            'compare_options'  => 'Opções de Comparação',
            'wishlist-options' => 'Opções de Lista de Desejos',
        ],
    ],

    'categories' => [
        'filters' => [
            'filters'   => 'Filtros:',
            'filter'    => 'Filtro',
            'sort'      => 'Ordenar',
            'clear-all' => 'Limpar Tudo',
        ],

        'toolbar' => [
            'show' => 'Mostrar',
        ],

        'view' => [
            'empty'     => 'Nenhum produto disponível nesta categoria',
            'load-more' => 'Carregar Mais',
        ],
    ],

    'search' => [
        'title'          => 'Resultados da pesquisa para : :query',
        'configurations' => [
            'image-search-option' => 'Opção de Pesquisa por Imagem',
        ],
    ],

    'compare'  => [
        'product-compare'    => 'Comparar Produtos',
        'delete-all'         => 'Excluir Todos',
        'empty-text'         => 'Você não tem itens na sua lista de comparação',
        'title'              => 'Comparar Produtos',
        'already-added'      => 'O item já foi adicionado à lista de comparação',
        'item-add-success'   => 'Item adicionado com sucesso à lista de comparação',
        'remove-success'     => 'Item removido com sucesso.',
        'remove-all-success' => 'Todos os itens removidos com sucesso.',
        'remove-error'       => 'Algo deu errado, por favor, tente novamente mais tarde.',
    ],

    'checkout' => [
        'success' => [
            'title'         => 'Pedido efetuado com sucesso',
            'thanks'        => 'Obrigado pelo seu pedido!',
            'order-id-info' => 'O seu número de pedido é #:order_id',
            'info'          => 'Vamos enviar-lhe um email com os detalhes do seu pedido e informações de rastreamento.',
        ],

        'cart' => [
            'item-add-to-cart'          => 'Item adicionado com sucesso',
            'return-to-shop'            => 'Voltar às Compras',
            'continue-to-checkout'      => 'Continuar para o Checkout',
            'rule-applied'              => 'Regra do carrinho aplicada',
            'minimum-order-message'     => 'O valor mínimo do pedido é de :amount',
            'suspended-account-message' => 'A sua conta foi suspensa.',
            'missing-fields'            => 'Alguns campos obrigatórios estão em falta para este produto.',
            'missing-options'           => 'Faltam opções para este produto.',
            'missing-links'             => 'Os links para download estão em falta para este produto.',
            'select-hourly-duration'    => 'Selecione uma duração de uma hora.',
            'qty-missing'               => 'Pelo menos um produto deve ter uma quantidade superior a 1.',
            'success-remove'            => 'O item foi removido com sucesso do carrinho.',
            'inventory-warning'         => 'A quantidade solicitada não está disponível, tente novamente mais tarde.',
            'illegal'                   => 'A quantidade não pode ser inferior a um.',
            'inactive'                  => 'O item foi desativado e removido do carrinho.',

            'index' => [
                'home'                     => 'Página Inicial',
                'cart'                     => 'Carrinho',
                'view-cart'                => 'Ver Carrinho',
                'product-name'             => 'Nome do Produto',
                'remove'                   => 'Remover',
                'quantity'                 => 'Quantidade',
                'price'                    => 'Preço',
                'tax'                      => 'Imposto',
                'total'                    => 'Total',
                'continue-shopping'        => 'Continuar a Comprar',
                'update-cart'              => 'Atualizar Carrinho',
                'move-to-wishlist-success' => 'Os itens selecionados foram movidos com sucesso para a lista de desejos.',
                'remove-selected-success'  => 'Os itens selecionados foram removidos com sucesso do carrinho.',
                'empty-product'            => 'Não tem nenhum produto no seu carrinho.',
                'quantity-update'          => 'Quantidade atualizada com sucesso',
                'see-details'              => 'Ver Detalhes',
                'move-to-wishlist'         => 'Mover para a Lista de Desejos',
            ],

            'coupon'   => [
                'code'            => 'Código do Cupão',
                'applied'         => 'Cupão aplicado',
                'apply'           => 'Aplicar Cupão',
                'error'           => 'Ocorreu um erro',
                'remove'          => 'Remover Cupão',
                'invalid'         => 'O código do cupão é inválido.',
                'discount'        => 'Desconto do Cupão',
                'apply-issue'     => 'Não foi possível aplicar o código do cupão.',
                'success-apply'   => 'O código do cupão foi aplicado com sucesso.',
                'already-applied' => 'O código do cupão já foi aplicado.',
                'enter-your-code' => 'Introduza o seu código',
                'subtotal'        => 'Subtotal',
                'button-title'    => 'Aplicar',
            ],

            'mini-cart' => [
                'see-details'          => 'Ver Detalhes',
                'shopping-cart'        => 'Carrinho de Compras',
                'offer-on-orders'      => 'Até 30% de DESCONTO na sua primeira encomenda',
                'remove'               => 'Remover',
                'empty-cart'           => 'O seu carrinho está vazio',
                'subtotal'             => 'Subtotal',
                'continue-to-checkout' => 'Continuar para o Checkout',
                'view-cart'            => 'Ver Carrinho',
            ],

            'summary' => [
                'cart-summary'        => 'Resumo do Carrinho',
                'sub-total'           => 'Subtotal',
                'tax'                 => 'Imposto',
                'delivery-charges'    => 'Portes de Envio',
                'discount-amount'     => 'Valor do Desconto',
                'grand-total'         => 'Total Geral',
                'place-order'         => 'Finalizar Pedido',
                'proceed-to-checkout' => 'Prosseguir para o Checkout',
            ],
        ],

        'onepage' => [
            'addresses' => [
                'billing' => [
                    'billing-address'      => 'Morada de Faturação',
                    'add-new-address'      => 'Adicionar nova morada',
                    'same-billing-address' => 'A morada é a mesma que a de faturação',
                    'back'                 => 'Voltar',
                    'company-name'         => 'Nome da Empresa',
                    'first-name'           => 'Primeiro Nome',
                    'last-name'            => 'Último Nome',
                    'email'                => 'Email',
                    'street-address'       => 'Morada',
                    'country'              => 'País',
                    'state'                => 'Estado',
                    'select-state'         => 'Selecionar Estado',
                    'city'                 => 'Cidade',
                    'postcode'             => 'Código Postal',
                    'telephone'            => 'Telefone',
                    'save-address'         => 'Guardar esta morada',
                    'confirm'              => 'Confirmar',
                ],

                'index' => [
                    'confirm' => 'Confirmar',
                ],

                'shipping' => [
                    'shipping-address' => 'Morada de Entrega',
                    'add-new-address'  => 'Adicionar nova morada',
                    'back'             => 'Voltar',
                    'company-name'     => 'Nome da Empresa',
                    'first-name'       => 'Primeiro Nome',
                    'last-name'        => 'Último Nome',
                    'email'            => 'Email',
                    'street-address'   => 'Morada',
                    'country'          => 'País',
                    'state'            => 'Estado',
                    'select-state'     => 'Selecionar Estado',
                    'select-country'   => 'Selecionar País',
                    'city'             => 'Cidade',
                    'postcode'         => 'Código Postal',
                    'telephone'        => 'Telefone',
                    'save-address'     => 'Guardar esta morada',
                    'confirm'          => 'Confirmar',
                ],
            ],

            'coupon' => [
                'discount'        => 'Desconto do Cupão',
                'code'            => 'Código do Cupão',
                'applied'         => 'Cupão Aplicado',
                'applied-coupon'  => 'Cupão Aplicado',
                'apply'           => 'Aplicar Cupão',
                'remove'          => 'Remover Cupão',
                'apply-issue'     => 'Não é possível aplicar o código do cupão.',
                'sub-total'       => 'Subtotal',
                'button-title'    => 'Aplicar',
                'enter-your-code' => 'Introduza o seu código',
                'subtotal'        => 'Subtotal',
            ],

            'index' => [
                'home'     => 'Página Inicial',
                'checkout' => 'Checkout',
            ],

            'payment' => [
                'payment-method' => 'Método de Pagamento',
            ],

            'shipping' => [
                'shipping-method' => 'Método de Envio',
            ],

            'summary' => [
                'cart-summary'     => 'Resumo do Carrinho',
                'sub-total'        => 'Subtotal',
                'tax'              => 'Imposto',
                'delivery-charges' => 'Portes de Envio',
                'discount-amount'  => 'Valor do Desconto',
                'grand-total'      => 'Total Geral',
                'place-order'      => 'Finalizar Pedido',
            ],
        ],
    ],

    'home' => [
        'index' => [
            'offer'               => 'Obtenha ATÉ 40% DE DESCONTO na sua 1ª compra COMPRE AGORA',
            'verify-email'        => 'Verifique a sua conta de email',
            'resend-verify-email' => 'Reenviar Email de Verificação',
        ],
    ],

    'errors' => [
        'go-to-home'   => 'Ir para a Página Inicial',

        '404' => [
            'title'       => 'Página 404 Não Encontrada',
            'description' => 'Ops! A página que procura está de férias. Parece que não conseguimos encontrar o que estava a procurar.',
        ],

        '401' => [
            'title'       => '401 Não Autorizado',
            'description' => 'Ops! Parece que não tem permissão para aceder a esta página. Parece que lhe faltam as credenciais necessárias.',
        ],

        '403' => [
            'title'       => '403 Proibido',
            'description' => 'Ops! Esta página está fora de limites. Parece que não tem as permissões necessárias para visualizar este conteúdo.',
        ],

        '500' => [
            'title'       => '500 Erro Interno do Servidor',
            'description' => 'Ops! Algo correu mal. Parece que estamos a ter dificuldades em carregar a página que procura.',
        ],

        '503' => [
            'title'       => '503 Serviço Indisponível',
            'description' => 'Ops! Parece que estamos temporariamente indisponíveis para manutenção. Por favor, volte mais tarde.',
        ],
    ],

    'layouts' => [
        'my-account'            => 'A Minha Conta',
        'profile'               => 'Perfil',
        'address'               => 'Endereço',
        'reviews'               => 'Avaliações',
        'wishlist'              => 'Lista de Desejos',
        'orders'                => 'Encomendas',
        'downloadable-products' => 'Produtos Descarregáveis',
    ],

    'subscription' => [
        'already'             => 'Já está subscrito na nossa newsletter.',
        'subscribe-success'   => 'Subscreveu com sucesso a nossa newsletter.',
        'unsubscribe-success' => 'Anulou com sucesso a subscrição da nossa newsletter.',
    ],

    'emails' => [
        'dear'   => 'Caro :customer_name',
        'thanks' => 'Se precisar de ajuda, por favor, contacte-nos em <a href=":link" style=":style">:email</a>.<br/>Obrigado!',

        'customers' => [
            'registration' => [
                'subject'     => 'Nova Registo de Cliente',
                'greeting'    => 'Bem-vindo e obrigado por se registar connosco!',
                'description' => 'A sua conta foi agora criada com sucesso e pode fazer login usando o seu endereço de email e credenciais de senha. Após o login, poderá aceder a outros serviços, incluindo a revisão de encomendas anteriores, listas de desejos e a edição das informações da sua conta.',
                'sign-in'     => 'Iniciar sessão',
            ],

            'forgot-password' => [
                'subject'        => 'Email de Redefinição de Senha',
                'greeting'       => 'Esqueceu a Senha!',
                'description'    => 'Está a receber este email porque recebemos um pedido de redefinição de senha para a sua conta.',
                'reset-password' => 'Redefinir Senha',
            ],

            'update-password' => [
                'subject'     => 'Senha Atualizada',
                'greeting'    => 'Senha Atualizada!',
                'description' => 'Está a receber este email porque atualizou a sua senha.',
            ],

            'verification' => [
                'subject'      => 'Email de Verificação de Conta',
                'greeting'     => 'Bem-vindo!',
                'description'  => 'Por favor, clique no botão abaixo para verificar o seu endereço de email.',
                'verify-email' => 'Verificar Endereço de Email',
            ],

            'commented' => [
                'subject'     => 'Novo Comentário Adicionado',
                'description' => 'Nota: :note',
            ],

            'subscribed' => [
                'subject'     => 'Subscreveu a Nossa Newsletter',
                'greeting'    => 'Bem-vindo à nossa newsletter!',
                'description' => 'Parabéns e bem-vindo à nossa comunidade de newsletter! Estamos entusiasmados por tê-lo a bordo e mantê-lo atualizado com as últimas notícias, tendências e ofertas exclusivas.',
                'unsubscribe' => 'Anular Subscrição',
            ],
        ],

        'orders' => [
            'created' => [
                'subject'  => 'Nova Confirmação de Encomenda',
                'title'    => 'Confirmação de Encomenda!',
                'greeting' => 'Obrigado pela sua Encomenda :order_id feita em :created_at',
                'summary'  => 'Resumo da Encomenda',
            ],

            'invoiced' => [
                'subject'  => 'Nova Confirmação de Fatura',
                'title'    => 'Confirmação de Fatura!',
                'greeting' => 'A sua fatura #:invoice_id para a Encomenda :order_id criada em :created_at',
                'summary'  => 'Resumo da Fatura',
            ],

            'shipped' => [
                'subject'  => 'Nova Confirmação de Envio',
                'title'    => 'Encomenda Enviada!',
                'greeting' => 'A sua encomenda :order_id feita em :created_at foi enviada',
                'summary'  => 'Resumo do Envio',
            ],

            'refunded' => [
                'subject'  => 'Nova Confirmação de Reembolso',
                'title'    => 'Encomenda Reembolsada!',
                'greeting' => 'O reembolso foi iniciado para a Encomenda :order_id feita em :created_at',
                'summary'  => 'Resumo do Reembolso',
            ],

            'canceled' => [
                'subject'  => 'Nova Encomenda Cancelada',
                'title'    => 'Encomenda Cancelada!',
                'greeting' => 'A sua Encomenda :order_id feita em :created_at foi cancelada',
                'summary'  => 'Resumo da Encomenda',
            ],

            'commented' => [
                'subject' => 'Novo Comentário Adicionado',
                'title'   => 'Novo comentário adicionado à sua encomenda :order_id feita em :created_at',
            ],

            'shipping-address'  => 'Endereço de Envio',
            'carrier'           => 'Transportadora',
            'tracking-number'   => 'Número de Rastreamento',
            'billing-address'   => 'Endereço de Faturação',
            'contact'           => 'Contacto',
            'shipping'          => 'Envio',
            'payment'           => 'Pagamento',
            'sku'               => 'SKU',
            'name'              => 'Nome',
            'price'             => 'Preço',
            'qty'               => 'Quantidade',
            'subtotal'          => 'Subtotal',
            'shipping-handling' => 'Envio e Manuseio',
            'tax'               => 'Imposto',
            'discount'          => 'Desconto',
            'grand-total'       => 'Total Geral',
        ],
    ],
];
