<?php

return [
    'description' => 'Pague de forma segura con su tarjeta de crédito/débito a través de Stripe.',
    'title' => 'Stripe',

    'line-items' => [
        'shipping' => 'Envío',
        'tax' => 'Impuestos',
    ],

    'response' => [
        'cart-changed' => 'Tu carrito cambió después de iniciar el pago, así que no se creó ningún pedido. Ponte en contacto con nosotros sobre tu pago.',
        'cart-not-found' => 'Carrito no encontrado o inválido.',
        'cart-processed' => 'Este carrito ya ha sido procesado.',
        'invalid-session' => 'La sesión de pago es inválida.',
        'payment-cancelled' => 'El pago fue cancelado.',
        'payment-failed' => 'Error en el pago.',
        'payment-success' => 'Pago completado exitosamente.',
        'provide-credentials' => 'Por favor proporcione credenciales de Stripe válidas.',
        'session-invalid' => 'La sesión de pago ha expirado o es inválida.',
        'session-not-found' => 'Sesión de pago no encontrada.',
        'verification-failed' => 'La verificación del pago falló.',
    ],

    'webhook' => [
        'dispute-closed' => 'La disputa de Stripe :id se cerró con el resultado: :status.',
        'dispute-opened' => 'El cliente disputó :amount de este pago en Stripe (:id), por el motivo: :reason.',
        'refund-recorded' => 'Se registró un reembolso de :amount hecho en Stripe; en total se han reembolsado :total en Stripe.',
    ],
];
