<?php

return [
    'invalid_vat_format' => 'The given vat id has a wrong format',
    'security-warning' => 'Suspicious activity found!!!',
    'nothing-to-delete' => 'Nothing to delete',

    'layouts' => [
        'my-account' => 'Minha Conta',
        'profile' => 'Perfil',
        'address' => 'Endereço',
        'reviews' => 'Avaliação',
        'wishlist' => 'Lista de Desejos',
        'orders' => 'Pedidos',
        'downloadable-products' => 'Produtos para download'
    ],

    'common' => [
        'error' => 'Algo deu errado, por favor, tente novamente mais tarde.',
        'no-result-found' => 'We could not find any records.'
    ],

    'home' => [
        'page-title' => config('app.name') . ' - Home',
        'featured-products' => 'Produtos em Destaque',
        'new-products' => 'Novos Produtos',
        'verify-email' => 'Verifique sua Conta de E-mail',
        'resend-verify-email' => 'Reenviar Email de Verificação'
    ],

    'header' => [
        'title' => 'Conta',
        'dropdown-text' => 'Gerenciar Carrinho, Pedidos & Lista de Desejos',
        'sign-in' => 'Entrar',
        'sign-up' => 'Criar Conta',
        'account' => 'Conta',
        'cart' => 'Carrinho',
        'profile' => 'Perfil',
        'wishlist' => 'Lista de Desejos',
        'cart' => 'Carrinho',
        'logout' => 'Sair',
        'search-text' => 'Pesquisar produtos aqui'
    ],

    'minicart' => [
        'view-cart' => 'Visualizar Carrinho',
        'checkout' => 'Finalizar Compra',
        'cart' => 'Carrinho',
        'zero' => '0'
    ],

    'footer' => [
        'subscribe-newsletter' => 'Assinar Newsletter',
        'subscribe' => 'Assinar',
        'locale' => 'Idioma',
        'currency' => 'Moeda',
    ],

    'subscription' => [
        'unsubscribe' => 'Cancelar Inscrição',
        'subscribe' => 'Inscrever',
        'subscribed' => 'Você está agora inscrito nos e-mails de newsletter',
        'not-subscribed' => 'Você não pode se inscrever, tente novamente após algum tempo',
        'already' => 'Você já está inscrito em nossa lista de assinaturas',
        'unsubscribed' => 'Você não está inscrito em nossa lista de assinaturas',
        'already-unsub' => 'Você não está mais inscrito em nossa lista de assinaturas',
        'not-subscribed' => 'Erro! Email não pode ser enviado, por favor, tente novamente mais tarde'
    ],

    'search' => [
        'no-results' => 'Nenhum resultado encontrado',
        'page-title' => 'Buscar',
        'found-results' => 'Resultados da pesquisa encontrados',
        'found-result' => 'Resultado da pesquisa encontrado'
    ],

    'reviews' => [
        'title' => 'Título',
        'add-review-page-title' => 'Adicionar Avaliação',
        'write-review' => 'Escreva uma avaliação',
        'review-title' => 'Dê um título a sua avaliação',
        'product-review-page-title' => 'Avaliação do Produto',
        'rating-reviews' => 'Notas & Avaliação',
        'submit' => 'ENVIAR',
        'delete-all' => 'Todas Avaliações foram excluídas com sucesso',
        'ratingreviews' => ':rating Nota & :review Avaliação',
        'star' => 'Estrela',
        'percentage' => ':percentage %',
        'id-star' => 'estrela',
        'name' => 'Nome'
    ],

    'customer' => [
        'signup-text' => [
            'account_exists' => 'Já tem uma conta',
            'title' => 'Entrar'
        ],

        'signup-form' => [
            'page-title' => 'Cliente - Formulário de Cadastro',
            'title' => 'Cadastrar',
            'firstname' => 'Nome',
            'lastname' => 'Sobrenome',
            'email' => 'Email',
            'password' => 'Senha',
            'confirm_pass' => 'Confirmar Senha',
            'button_title' => 'Cadastrar',
            'agree' => 'Concordo',
            'terms' => 'Termos',
            'conditions' => 'Condições',
            'using' => 'usando este site',
            'agreement' => 'Acordo',
            'success' => 'Conta criado com sucesso, um e-mail foi enviado para sua verificação de conta',
            'success-verify-email-not-sent' => 'Conta criada com sucesso, mas o email de verificação não foi enviado',
            'failed' => 'Erro! Não é possível criar sua conta, tente novamente mais tarde',
            'already-verified' => 'Sua conta já foi confirmada ou tente enviar novamente novo de e-mail de confirmação',
            'verification-not-sent' => 'Erro! Problema ao enviar e-mail de verificação, tente novamente mais tarde',
            'verification-sent' => 'E-mail de verificação enviado',
            'verified' => 'Sua Conta Foi Verificada, Tente Entrar Agora',
            'verify-failed' => 'Não podemos verificar sua conta de e-mail.',
            'dont-have-account' => 'Você não tem conta conosco',
            'customer-registration' => 'Cliente Cadastrado com Sucesso'
        ],

        'login-text' => [
            'no_account' => 'Não tem conta',
            'title' => 'Cadastrar',
        ],

        'login-form' => [
            'page-title' => 'Cliente - Login',
            'title' => 'Entrar',
            'email' => 'Email',
            'password' => 'Senha',
            'forgot_pass' => 'Esqueceu sua Senha?',
            'button_title' => 'Entrar',
            'remember' => 'Lembrar de mim',
            'footer' => '© Copyright :year Webkul Software, Todos os direitos reservados',
            'invalid-creds' => 'Por favor, verifique suas credenciais e tente novamente',
            'verify-first' => 'Verifique seu e-mail primeiro',
            'resend-verification' => 'Reenviar email de verificação novamente'
        ],

        'forgot-password' => [
            'title' => 'Recuperar Senha',
            'email' => 'Email',
            'submit' => 'Enviar',
            'page_title' => 'Esqueci minha Senha'
        ],

        'reset-password' => [
            'title' => 'Redefinir Senha',
            'email' => 'Email registrado',
            'password' => 'Senha',
            'confirm-password' => 'Confirmar Senha',
            'back-link-title' => 'Voltar para Login',
            'submit-btn-title' => 'Redefinir Senha'
        ],

        'account' => [
            'dashboard' => 'Cliente - Perfil',
            'menu' => 'Menu',

            'profile' => [
                'index' => [
                    'page-title' => 'Cliente - Perfil',
                    'title' => 'Perfil',
                    'edit' => 'Editar',
                ],

                'edit-success' => 'Perfil Atualizado com Sucesso',
                'edit-fail' => 'Erro! O perfil não pode ser atualizado, por favor, tente novamente mais tarde',
                'unmatch' => 'A senha antiga não corresponde',

                'fname' => 'Nome',
                'lname' => 'Sobrenome',
                'gender' => 'Gênero',
                'other' => 'De outros',
                'male' => 'Masculino',
                'female' => 'Fêmeo',
                'dob' => 'Data de Nascimento',
                'phone' => 'Telefone',
                'email' => 'Email',
                'opassword' => 'Senha antiga',
                'password' => 'Senha',
                'cpassword' => 'Confirmar Senha',
                'submit' => 'Atualizar Perfil',

                'edit-profile' => [
                    'title' => 'Editar Perfil',
                    'page-title' => 'Cliente - Editar Perfil'
                ]
            ],

            'address' => [
                'index' => [
                    'page-title' => 'Cliente - Endereço',
                    'title' => 'Endereço',
                    'add' => 'Adicionar Endereço',
                    'edit' => 'Editar',
                    'empty' => 'Você não tem nenhum endereço salvo aqui, por favor tente criá-lo clicando no link abaixo',
                    'create' => 'Criar Endereço',
                    'delete' => 'Deletar',
                    'make-default' => 'Definir como Padrão',
                    'default' => 'Padrão',
                    'contact' => 'Contato',
                    'confirm-delete' =>  'Você realmente deseja excluir este endereço?',
                    'default-delete' => 'O endereço padrão não pode ser alterado',
                    'enter-password' => 'Enter Your Password.',
                ],

                'create' => [
                    'page-title' => 'Cliente - Adicionar Endereço',
                    'title' => 'Novo Endereço',
                    'company_name' => 'Nome da empresa',
                    'first_name' => 'Primeiro nome',
                    'last_name' => 'Último nome',
                    'vat_id' => 'ID do IVA',
                    'address1' => 'Endereço Linha 1',
                    'street-address' => 'Endereço',
                    'country' => 'País',
                    'state' => 'Estado',
                    'select-state' => 'Select a region, state or province',
                    'city' => 'Cidade',
                    'postcode' => 'CEP',
                    'phone' => 'Telefone',
                    'submit' => 'Salvar Endereço',
                    'success' => 'Endereço foi adicionado com sucesso.',
                    'error' => 'Endereço não pode ser adicionado.'
                ],

                'edit' => [
                    'page-title' => 'Cliente - Editar Endereço',
                    'title' => 'Editar Endereço',
                    'submit' => 'Salvar Endereço',
                    'success' => 'Endereço Atualizado com sucesso.'
                ],
                'delete' => [
                    'success' => 'Endereço Excluído com sucesso',
                    'failure' => 'Endereço não pode ser adicionado',
                    'wrong-password' => 'Wrong Password !'
                ]
            ],

            'order' => [
                'index' => [
                    'page-title' => 'Cliente - Pedidos',
                    'title' => 'Pedidos',
                    'order_id' => 'Pedido ID',
                    'date' => 'Data',
                    'status' => 'Status',
                    'total' => 'Total',
                    'order_number' => 'Número do Pedido',
                    'processing' => 'Precessando',
                    'completed' => 'Completo',
                    'canceled' => 'Cancelado',
                    'closed' => 'Fechado',
                    'pending' => 'Pendente',
                    'pending-payment' => 'Pagamento Pendente',
                    'fraud' => 'Fraude'
                ],

                'view' => [
                    'page-tile' => 'Pedido #:order_id',
                    'info' => 'Informação',
                    'placed-on' => 'Criado em',
                    'products-ordered' => 'Produtos Pedidos',
                    'invoices' => 'Faturas',
                    'shipments' => 'Entregas',
                    'SKU' => 'SKU',
                    'product-name' => 'Nome',
                    'qty' => 'Qtd',
                    'item-status' => 'Item Status',
                    'item-ordered' => 'Pedidos (:qty_ordered)',
                    'item-invoice' => 'Faturados (:qty_invoiced)',
                    'item-shipped' => 'enviados (:qty_shipped)',
                    'item-canceled' => 'Cancelados (:qty_canceled)',
                    'item-refunded' => 'Refunded (:qty_refunded)',
                    'price' => 'Preço',
                    'total' => 'Total',
                    'subtotal' => 'Subtotal',
                    'shipping-handling' => 'Entrega & Manuseio',
                    'tax' => 'Imposto',
                    'discount' => 'Discount',
                    'tax-percent' => 'Percentagem de imposto',
                    'tax-amount' => 'Valor de Imposto',
                    'discount-amount' => 'Valor de Desconto',
                    'grand-total' => 'Total',
                    'total-paid' => 'Total Pago',
                    'total-refunded' => 'Total Estornado',
                    'total-due' => 'Total Devido',
                    'shipping-address' => 'Endereço de Entrega',
                    'billing-address' => 'Endereço de Cobrança',
                    'shipping-method' => 'Método de Entrega',
                    'payment-method' => 'Método de Pagamento',
                    'individual-invoice' => 'Fatura #:invoice_id',
                    'individual-shipment' => 'Entrega #:shipment_id',
                    'print' => 'Imprimir',
                    'invoice-id' => 'Fatura Id',
                    'order-id' => 'Pedido Id',
                    'order-date' => 'Pedido Date',
                    'bill-to' => 'Cobrança de',
                    'ship-to' => 'Enviar para',
                    'contact' => 'Contato',
                    'refunds' => 'Refunds',
                    'individual-refund' => 'Refund #:refund_id',
                    'adjustment-refund' => 'Adjustment Refund',
                    'adjustment-fee' => 'Adjustment Fee',
                ]
            ],

            'wishlist' => [
                'page-title' => 'Lista de Desejos',
                'title' => 'Lista de Desejos',
                'deleteall' => 'Excluir Tudo',
                'moveall' => 'Adicionar todos ao Carrinho',
                'move-to-cart' => 'Adicionar ao Carrinho',
                'error' => 'Não é possível adicionar o produto a lista de Desejos devido a problemas desconhecidos, por favor tente mais tarde',
                'add' => 'Item adicionado com sucesso a Lista de Desejos',
                'remove' => 'Item removido com sucesso da Lista de Desejos',
                'moved' => 'Item movido com sucesso para Lista de Desejos',
                'option-missing' => 'As opções do produto estão ausentes, portanto, o item não pode ser movido para a lista de desejos.',
                'move-error' => 'Item não pode ser movido para Lista de Desejos, por favor, tente novamente mais tarde',
                'success' => 'Item adicionado com sucesso a Lista de Desejos',
                'failure' => 'Item não pode ser adicionado à Lista de Desejos, por favor, tente novamente mais tarde',
                'already' => 'Item já presente em sua lista de desejos',
                'removed' => 'Item removido com sucesso da Lista de Desejos',
                'remove-fail' => 'Item não pode ser removido da lista de desejos, por favor, tente novamente mais tarde',
                'empty' => 'Você não tem nenhum item em sua Lista de Desejos',
                'remove-all-success' => 'Todos os itens da sua lista de desejos foram removidos',
            ],

            'downloadable_products' => [
                'title' => 'Produtos para download',
                'order-id' => 'ID do pedido',
                'date' => 'Encontro',
                'name' => 'Título',
                'status' => 'Status',
                'pending' => 'Pendente',
                'available' => 'acessível',
                'expired' => 'Expirado',
                'remaining-downloads' => 'Downloads restantes',
                'unlimited' => 'Ilimitado',
                'download-error' => 'O link para download expirou.'
            ],

            'review' => [
                'index' => [
                    'title' => 'Avaliação',
                    'page-title' => 'Cliente - Avaliação'
                ],

                'view' => [
                    'page-tile' => 'Avaliação #:id',
                ]
            ]
        ]
    ],

    'products' => [
        'layered-nav-title' => 'Compre por',
        'price-label' => 'Tão baixo quanto',
        'remove-filter-link-title' => 'Limpar Todos',
        'sort-by' => 'Ordernar por',
        'from-a-z' => 'De A-Z',
        'from-z-a' => 'De Z-A',
        'newest-first' => 'Novos Primeiros',
        'oldest-first' => 'Antigos Primeiros',
        'cheapest-first' => 'Mais baratos primeiros',
        'expensive-first' => 'Mas caros primeiros',
        'show' => 'Visualiar',
        'pager-info' => 'Mostrando :showing de um :total de Itens',
        'description' => 'Descrição',
        'specification' => 'Especificação',
        'total-reviews' => ':total Avaliação',
        'total-rating' => ':total_rating Notas & :total_reviews Avaliações',
        'by' => 'Por :name',
        'up-sell-title' => 'Encontramos outros produtos que você pode gostar!',
        'related-product-title' => 'Produtos Relacionados',
        'cross-sell-title' => 'Mais escolhas',
        'reviews-title' => 'Classificações & Avaliação',
        'write-review-btn' => 'Escreva uma Avaliação',
        'choose-option' => 'Escolha uma opção',
        'sale' => 'Promoção',
        'new' => 'Novo',
        'empty' => 'Nenhum produto disponível nesta categoria',
        'add-to-cart' => 'Adicionar ao Carrinho',
        'buy-now' => 'Comprar Agora',
        'whoops' => 'Oppss!',
        'quantity' => 'Quantidade',
        'in-stock' => 'Em Estoque',
        'out-of-stock' => 'Fora de Estoque',
        'view-all' => 'Ver Tudo',
        'select-above-options' => 'Por favor, selecione as opções acima primeiro.',
        'less-quantity' => 'A quantidade não pode ser menor que um.',
        'starting-at' => 'Começando às',
        'customize-options' => 'Personalizar opções',
        'choose-selection' => 'Escolha uma seleção',
        'your-customization' => 'Sua personalização',
        'total-amount' => 'Valor total',
        'none' => 'Nenhum'
    ],

    // 'reviews' => [
    //     'empty' => 'Você ainda não avaliou qualquer produto'
    // ]

    'buynow' => [
        'no-options' => 'Por favor, selecione as opções antes de comprar este produto'
    ],


    'checkout' => [
        'cart' => [
            'integrity' => [
                'missing_fields' =>'Violação de integridade do sistema de carrinho, alguns campos obrigatórios ausentes',
                'missing_options' =>'Violação de Integridade do Sistema de Carrinho, Faltam Opções para o Produto Configurável',
                'missing_links' => 'Faltam links para download para este produto.',
                'qty_missing' => 'Pelo menos um produto deve ter mais de 1 quantidade.',
                'qty_impossible' => 'Não é possível adicionar mais do que um desse produto ao carrinho.'
            ],

            'create-error' => 'Encontrou algum problema ao fazer a instância do carrinho',
            'title' => 'Carrinho de Compras',
            'empty' => 'Seu carrinho de compras está vazio',
            'update-cart' => 'Atualizar Carrinho',
            'continue-shopping' => 'Continuar Comprando',
            'proceed-to-checkout' => 'Finalizar Compra',
            'remove' => 'Remover',
            'remove-link' => 'Remover',
            'move-to-wishlist' => 'Mover para Lista de Desejos',
            'move-to-wishlist-success' => 'Item Movido para Lista de Desejos',
            'move-to-wishlist-error' => 'Não foi possivel Mover Item para Lista de Desejos, Por favor, tente novamente mais tarde',
            'add-config-warning' => 'Por favor, selecione a opção antes de adicionar ao carrinho',
            'quantity' => [
                'quantity' => 'Quantidade',
                'success' => 'Carrinho Item(s) Atualizados com Sucesso!',
                'illegal' => 'Quantidade não pode ser menor que um',
                'inventory_warning' => 'A quantidade solicitada não está disponível, por favor, tente novamente mais tarde',
                'error' => 'Não é possível atualizar o item(s) no momento, por favor, tente novamente mais tarde'
            ],

            'item' => [
                'error_remove' => 'Nenhum item para remover do carrinho',
                'success' => 'Item foi adicionado com sucesso ao carrinho',
                'success-remove' => 'Item foi removido com sucesso do carrinho',
                'error-add' => 'Item não pode ser adicionado ao carrinho, por favor, tente novamente mais tarde',
            ],
            'quantity-error' => 'Quantidade solicitada não está disponível',
            'cart-subtotal' => 'Subtotal do carrinho',
            'cart-remove-action' => 'Você realmente quer fazer isso ?',
            'partial-cart-update' => 'Only some of the product(s) were updated',
            'link-missing' => ''
        ],

        'onepage' => [
            'title' => 'Finalização Compra',
            'information' => 'Informação',
            'shipping' => 'Entrega',
            'payment' => 'Pagamento',
            'complete' => 'Completo',
            'billing-address' => 'Endereço de Cobrança',
            'sign-in' => 'Entrar',
            'first-name' => 'Nome',
            'last-name' => 'Sobrenome',
            'email' => 'E-mail',
            'address1' => 'Endereço',
            'address2' => 'Endereço 2',
            'city' => 'Cidade',
            'state' => 'Estado',
            'select-state' => 'Selecione uma região, estado e província',
            'postcode' => 'CEP',
            'phone' => 'Telefone',
            'country' => 'País',
            'order-summary' => 'Resumo do Pedido',
            'shipping-address' => 'Endereço de Entrega',
            'use_for_shipping' => 'Enviar para esse endereço',
            'continue' => 'Continuar',
            'shipping-method' => 'Selecione o Método de Entrega',
            'payment-methods' => 'Selecione o Método de Pagamento',
            'payment-method' => 'Método de Pagamento',
            'summary' => 'Resumo do Pedido',
            'price' => 'Preço',
            'quantity' => 'Quantidade',
            'billing-address' => 'Endereço de Cobrança',
            'shipping-address' => 'Endereço de Entrega',
            'contact' => 'Contato',
            'place-order' => 'Enviar Pedido',
            'new-address' => 'Add Novo Endereço',
            'save_as_address' => 'Salvar Endereço',
            'apply-coupon' => 'Aplicar Cupom',
            'enter-coupon-code' => 'Digite aqui o seu Cupom'
        ],

        'total' => [
            'order-summary' => 'Resumo do Pedido',
            'sub-total' => 'Itens',
            'grand-total' => 'Total',
            'delivery-charges' => 'Taxas de Entrega',
            'tax' => 'Imposto',
            'discount' => 'Desconto',
            'price' => 'preço',
            'disc-amount' => 'Valor descontado',
            'new-grand-total' => 'Novo Total',
            'coupon' => 'Cupom',
            'coupon-applied' => 'Cupom Aplicado',
            'remove-coupon' => 'Remover Cupom',
            'cannot-apply-coupon' => 'Não foi possível aplicar esse Cupom',
            'invalid-coupon' => 'Código do Cupom é inválido.',
            'success-coupon' => 'Cupom aplicado com sucesso.',
            'coupon-apply-issue' => 'Não foi possível aplicar esse Cupom'
        ],

        'success' => [
            'title' => 'Pedido enviado com sucesso!',
            'thanks' => 'Obrigado pelo seu pedido!',
            'order-id-info' => 'Seu ID do Pedido é #:order_id',
            'info' => 'Nós lhe enviaremos por e-mail, detalhes do seu pedido e informações de rastreamento'
        ]
    ],

    'mail' => [
        'order' => [
            'subject' => 'Confirmação de Novo Pedido',
            'heading' => 'Confirmação de Pedido!',
            'dear' => 'Caro :customer_name',
            'dear-admin' => 'Caro :admin_name',
            'greeting' => 'Obrigado pelo seu Pedido :order_id realizado em :created_at',
            'summary' => 'Resumo do Pedido',
            'shipping-address' => 'Endereço de Entrega',
            'billing-address' => 'Endereço de Cobrança',
            'contact' => 'Contato',
            'shipping' => 'Entrega',
            'payment' => 'Pagamento',
            'price' => 'Preço',
            'quantity' => 'Quantidade',
            'subtotal' => 'Subtotal',
            'shipping-handling' => 'Envio & Manuseio',
            'tax' => 'Imposto',
            'discount' => 'Discount',
            'grand-total' => 'Total',
            'final-summary' => 'Obrigado por mostrar o seu interesse em nossa loja nós lhe enviaremos o número de rastreamento assim que for despachado',
            'help' => 'Se você precisar de algum tipo de ajuda, por favor entre em contato conosco :support_email',
            'thanks' => 'Muito Obrigado!',
            'cancel' => [
                'subject' => 'Confirmação de Cancelamento de Pedido',
                'heading' => 'Pedido Cancelado',
                'dear' => 'Caro :customer_name',
                'greeting' => 'Seu Pedido com o ID #:order_id finalizado em :created_at foi cancelado',
                'summary' => 'Resumo do Pedido',
                'shipping-address' => 'Endereço de Entrega',
                'billing-address' => 'Endereço de Faturamento',
                'contact' => 'Contato',
                'shipping' => 'Método de Envio',
                'payment' => 'Método de Pagamento',
                'subtotal' => 'Subtotal',
                'shipping-handling' => 'Entrega & Manuseio',
                'tax' => 'Taxa',
                'discount' => 'Desconto',
                'grand-total' => 'Total',
                'final-summary' => 'Obrigado por mostrar interesse em nosa Loja',
                'help' => 'Caso precise de qualquer tipo de ajuda entre em contato conosco :support_email',
                'thanks' => 'Obrigado!',
            ]
        ],

        'invoice' => [
            'heading' => 'Sua Fatura #:invoice_id do Pedido #:order_id',
            'subject' => 'Fatura do seu pedido #:order_id',
            'summary' => 'Resumo da Fatura',
        ],

        'refund' => [
            'heading' => 'Your Refund #:refund_id for Order #:order_id',
            'subject' => 'Refund for your order #:order_id',
            'summary' => 'Resumo do reembolso',
            'adjustment-refund' => 'Reembolso de ajuste',
            'adjustment-fee' => 'Taxa de ajuste'
        ],

        'shipment' => [
            'heading' => 'Sua Entrega #:shipment_id do Pedido #:order_id',
            'subject' => 'Entrega do seu pedido #:order_id',
            'summary' => 'Resumo da Entrega',
            'carrier' => 'Transportadora',
            'tracking-number' => 'Código de Rastreio'
        ],

        'forget-password' => [
            'subject' => 'Recuperação de Senha',
            'dear' => 'Caro :name',
            'info' => 'Você está recebendo este e-mail porque recebemos uma solicitação de redefinição de senha para sua conta',
            'reset-password' => 'Redefinir Senha',
            'final-summary' => 'Se você não solicitou uma redefinição de senha, nenhuma ação adicional é necessária',
            'thanks' => 'Obrigado!'
        ],

        'customer' => [
            'new' => [
                'dear' => 'Caro :customer_name',
                'username-email' => 'Nome de usuário/Email',
                'subject' => 'Nova Conta',
                'password' => 'Senha',
                'summary' => 'Sua conta foi criada.
                Abaixo está suas informaços: ',
                'thanks' => 'Obrigado!',
            ],

            'registration' => [
                'subject' => 'Novo Cliente',
                'customer-registration' => 'Cliente cadastrado com Sucesso',
                'dear' => 'Caro :customer_name',
                'greeting' => 'Bem vindo e obrigado por se cadastrar conosco!',
                'summary' => 'Sua conta foi criada com sucesso e agora você pode entrar utilizando seu endereço de email e senha. Ao fazer login, você poderá acessar outros serviços, incluindo a revisão de pedidos anteriores, listas de desejos e a edição das informações da sua conta..',
                'thanks' => 'Obrigado!',
            ],

            'verification' => [
                'heading' => config('app.name') . ' - Verificação de Email',
                'subject' => 'Verificação de Email',
                'verify' => 'Confirme sua conta',
                'summary' => 'Esse email é para confirmar que esse endereço de e-mail é válido e pertence a você.
                Por favor, clique no botão Confirme sua conta abaixo para verificar sua conta. '
            ],

            'subscription' => [
                'subject' => 'Email de Inscrição',
                'greeting' => ' Bem vindo a ' . config('app.name') . ' - Incrição de Email',
                'unsubscribe' => 'Unsubscribe',
                'summary' => 'Obrigado por me colocar na sua caixa de entrada. Já faz um tempo desde que você leu ' . config('app.name') . ' e-mail e não queremos sobrecarregar sua caixa de entrada. Se você ainda não deseja receber
                as últimas notícias de email marketing e, com certeza, clique no botão abaixo.'
            ]
        ]
    ],

    'webkul' => [
        'copy-right' => '© Copyright :year Webkul Software, Todos os Direitos Reservados',
    ],

    'response' => [
        'create-success' => ':name criado com sucesso.',
        'update-success' => ':name atualizado com sucesso.',
        'delete-success' => ':name excluído com sucesso.',
        'submit-success' => ':name enviado com sucesso.'
    ],
];