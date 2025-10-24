<?php

return [
    'configuration' => [
        'checkout-title'   => 'Razorpay Checkout',
        'client-id'        => 'ID do Cliente',
        'client-secret'    => 'Segredo do Cliente',
        'description'      => 'Descrição',
        'info'             => 'O Razorpay é uma plataforma de tecnologia financeira que ajuda empresas a aceitar, processar e distribuir pagamentos.',
        'merchant_desc'    => 'Descrição da transação (a ser exibida no formulário de pagamento)',
        'merchant_name'    => 'Nome do comerciante (a ser exibido no formulário de pagamento)',
        'name'             => 'Razorpay',
        'production-only'  => 'Apenas para produção.',
        'sandbox-only'     => 'Apenas para sandbox.',
        'status'           => 'Status',
        'test-mode-id'     => 'ID do Cliente no Modo de Teste',
        'test-mode-secret' => 'Segredo do Cliente no Modo de Teste',
        'title'            => 'Título',
    ],

    'response' => [
        'credentials-missing'  => 'As credenciais do Razorpay estão ausentes!',
        'error-message'        => 'Ocorreu um erro ao carregar o gateway de pagamento. Por favor, tente novamente.',
        'razorpay-cancelled'   => 'O pagamento do Razorpay foi cancelado.',
        'something-went-wrong' => 'Algo deu errado',
    ],
];