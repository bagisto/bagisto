<?php

return [
    'description' => 'Paga de forma segura utilizando tarjeta de crédito, tarjeta de débito, banca en línea, UPI y billeteras a través de PayU',
    'title'       => 'PayU Money',

    'redirect' => [
        'click-if-not-redirected' => 'Haz clic aquí para continuar',
        'please-wait'             => 'Por favor espera mientras te redirigimos a la pasarela de pago...',
        'redirect-message'        => 'Si no eres redirigido automáticamente, haz clic en el botón de abajo.',
        'redirecting'             => 'Redirigiendo a PayU...',
        'redirecting-to-payment'  => 'Redirigiendo al pago de PayU',
        'secure-payment'          => 'Pasarela de pago segura',
    ],

    'response' => [
        'cart-not-found'            => 'Carrito no encontrado. Por favor, inténtalo de nuevo.',
        'hash-mismatch'             => 'Verificación de pago fallida. Discordancia de hash.',
        'invalid-transaction'       => 'Transacción no válida. Por favor, inténtalo de nuevo.',
        'order-creation-failed'     => 'Error al crear el pedido. Por favor, contacta con soporte.',
        'payment-already-processed' => 'El pago ya ha sido procesado.',
        'payment-cancelled'         => 'El pago fue cancelado. Puedes intentarlo de nuevo.',
        'payment-failed'            => 'El pago falló. Por favor, inténtalo de nuevo.',
        'payment-success'           => '¡Pago completado exitosamente!',
        'provide-credentials'       => 'Por favor, configura la clave de comerciante y Salt de PayU en el panel de administración.',
    ],
];
