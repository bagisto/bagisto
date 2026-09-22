<?php

return [
    'description' => 'Pague com segurança com seu cartão de crédito/débito via Stripe.',
    'title' => 'Stripe',

    'line-items' => [
        'shipping' => 'Frete',
        'tax' => 'Impostos',
    ],

    'response' => [
        'cart-changed' => 'Seu carrinho mudou depois que o pagamento foi iniciado, então nenhum pedido foi feito. Entre em contato conosco sobre o seu pagamento.',
        'cart-not-found' => 'Carrinho não encontrado ou inválido.',
        'cart-processed' => 'Este carrinho já foi processado.',
        'invalid-session' => 'Sessão de pagamento  inválida.',
        'payment-cancelled' => 'Pagamento foi cancelado.',
        'payment-failed' => 'Pagamento falhou.',
        'payment-success' => 'Pagamento concluído com sucesso.',
        'provide-credentials' => 'Por favor, forneça credenciais Stripe válidas.',
        'session-invalid' => 'Sessão de pagamento expirou ou é inválida.',
        'session-not-found' => 'Sessão de pagamento não encontrada.',
        'verification-failed' => 'Verificação de pagamento falhou.',
    ],

    'webhook' => [
        'dispute-closed' => 'A disputa da Stripe :id foi encerrada com o resultado: :status.',
        'dispute-opened' => 'O cliente contestou :amount deste pagamento na Stripe (:id), pelo motivo: :reason.',
        'refund-recorded' => 'Um reembolso de :amount feito na Stripe foi registrado; no total, :total foi reembolsado na Stripe.',
    ],
];
