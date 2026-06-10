<?php

return [
    [
        'key'    => 'sales.payment_methods.pagomovil',
        'name'   => 'Pago Móvil (Venezuela)',
        'info'   => 'Configuración de los datos de recepción para Pago Móvil.',
        'sort'   => 1,
        'fields' => [
            [
                'name'          => 'title',
                'title'         => 'Título',
                'type'          => 'text',
                'validation'    => 'required',
                'default_value' => 'Pago Móvil',
                'channel_based' => false,
                'locale_based'  => true,
            ], [
                'name'          => 'description',
                'title'         => 'Descripción',
                'type'          => 'textarea',
                'default_value' => 'Realiza tu pago vía Pago Móvil y reporta los datos de la transacción.',
                'channel_based' => false,
                'locale_based'  => true,
            ], [
                'name'          => 'bank',
                'title'         => 'Banco Receptor',
                'type'          => 'text',
                'validation'    => 'required',
                'channel_based' => true,
            ], [
                'name'          => 'phone',
                'title'         => 'Teléfono Receptor',
                'type'          => 'text',
                'validation'    => 'required',
                'channel_based' => true,
            ], [
                'name'          => 'id_number',
                'title'         => 'Cédula/RIF Receptor',
                'type'          => 'text',
                'validation'    => 'required',
                'channel_based' => true,
            ], [
                'name'          => 'active',
                'title'         => 'Estado',
                'type'          => 'boolean',
                'validation'    => 'required',
                'channel_based' => false,
                'locale_based'  => true,
            ], [
                'name'          => 'sort',
                'title'         => 'Orden de visualización',
                'type'          => 'text',
                'channel_based' => false,
                'locale_based'  => false,
            ]
        ]
    ]
];
