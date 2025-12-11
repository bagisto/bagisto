<?php

return [
    'description' => 'Pague com segurança usando cartão de crédito, cartão de débito, internet banking, UPI e carteiras via PayU',
    'title'       => 'PayU',

    'redirect' => [
        'click-if-not-redirected' => 'Clique aqui para continuar',
        'please-wait'             => 'Por favor, aguarde enquanto redirecionamos você para o gateway de pagamento...',
        'redirect-message'        => 'Se você não for redirecionado automaticamente, clique no botão abaixo.',
        'redirecting'             => 'Redirecionando para PayU...',
        'redirecting-to-payment'  => 'Redirecionando para pagamento PayU',
        'secure-payment'          => 'Gateway de pagamento seguro',
    ],

    'response' => [
        'cart-not-found'            => 'Carrinho não encontrado. Por favor, tente novamente.',
        'hash-mismatch'             => 'Falha na verificação do pagamento. Incompatibilidade de hash.',
        'invalid-transaction'       => 'Transação inválida. Por favor, tente novamente.',
        'order-creation-failed'     => 'Falha ao criar pedido. Entre em contato com o suporte.',
        'payment-already-processed' => 'O pagamento já foi processado.',
        'payment-cancelled'         => 'O pagamento foi cancelado. Você pode tentar novamente.',
        'payment-failed'            => 'Pagamento falhou. Por favor, tente novamente.',
        'payment-success'           => 'Pagamento concluído com sucesso!',
        'provide-credentials'       => 'Por favor, configure a chave de comerciante e Salt do PayU no painel de administração.',
    ],
];
