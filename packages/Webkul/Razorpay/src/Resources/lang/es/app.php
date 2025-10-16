<?php

return [
    'configuration' => [
        'checkout-title'   => 'Pago de Razorpay',
        'client-id'        => 'ID del Cliente',
        'client-secret'    => 'Secreto del Cliente',
        'description'      => 'Descripción',
        'info'             => 'Razorpay es una plataforma de tecnología financiera que ayuda a las empresas a aceptar, procesar y distribuir pagos.',
        'merchant_desc'    => 'Descripción de la Transacción (Se mostrará en el formulario de pago)',
        'merchant_name'    => 'Nombre del Comerciante (Se mostrará en el formulario de pago)',
        'name'             => 'Razorpay',
        'production-only'  => 'Solo para producción.', 
        'sandbox-only'     => 'Solo para entorno de pruebas.', 
        'status'           => 'Estado',
        'test-mode-id'     => 'ID de Cliente en Modo de Prueba',
        'test-mode-secret' => 'Secreto de Cliente en Modo de Prueba',
        'title'            => 'Título',
    ],

    'response' => [
        'credentials-missing'  => '¡Faltan las credenciales de Razorpay!',
        'error-message'        => 'Ocurrió un error al cargar la pasarela de pago. Por favor, inténtelo de nuevo.',
        'razorpay-cancelled'   => 'El pago de Razorpay ha sido cancelado.',
        'something-went-wrong' => 'Algo salió mal',
    ],
];