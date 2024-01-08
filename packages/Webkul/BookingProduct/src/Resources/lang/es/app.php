<?php

return [
    'admin' => [
        'catalog' => [
            'products' => [
                'edit'  => [
                    'booking' => [
                        'appointment' => [
                            'break-duration'         => 'Tiempo de descanso entre espacios (minutos)',
                            'slot-duration'          => 'Duración del espacio (minutos)',

                            'same-slot-for-all-days' => [
                                'no'    => 'No',
                                'title' => 'Mismo espacio para todos los días',
                                'yes'   => 'Sí',
                            ],
                        ],

                        'default'     => [
                            'break-duration' => 'Tiempo de descanso entre espacios (minutos)',
                            'close'          => 'Cerrar',
                            'delete'         => 'Eliminar',
                            'description'    => 'Información de reserva',
                            'edit'           => 'Editar',
                            'many'           => 'Muchos',

                            'modal'          => [
                                'slot' => [
                                    'close'      => 'Cerrar',
                                    'day'        => 'Día',
                                    'edit-title' => 'Editar espacios',
                                    'friday'     => 'Viernes',
                                    'from-day'   => 'Desde el día',
                                    'from'       => 'Desde',
                                    'monday'     => 'Lunes',
                                    'open'       => 'Abrir',
                                    'saturday'   => 'Sábado',
                                    'save'       => 'Guardar espacio',
                                    'select'     => 'Seleccionar',
                                    'status'     => 'Estado',
                                    'sunday'     => 'Domingo',
                                    'thursday'   => 'Jueves',
                                    'time'       => 'Hora',
                                    'title'      => 'Agregar espacios',
                                    'to'         => 'Hasta',
                                    'tuesday'    => 'Martes',
                                    'wednesday'  => 'Miércoles',
                                    'week'       => ':día',
                                ],
                            ],

                            'one'            => 'Uno',
                            'open'           => 'Abrir',
                            'slot-duration'  => 'Duración del espacio (minutos)',
                            'title'          => 'Por defecto',
                        ],

                        'event'       => [
                            'add'                => 'Agregar entradas',
                            'delete'             => 'Eliminar',
                            'description-info'   => 'No hay entradas disponibles.',
                            'description'        => 'Descripción',
                            'edit'               => 'Editar',

                            'modal'              => [
                                'ticket' => [
                                    'save' => 'Guardar entradas',
                                ],
                            ],

                            'name'               => 'Nombre',
                            'price'              => 'Precio',
                            'qty'                => 'Cantidad',
                            'special-price-from' => 'Precio especial desde',
                            'special-price-to'   => 'Precio especial hasta',
                            'special-price'      => 'Precio especial',
                            'title'              => 'Entradas',
                        ],

                        'empty-info'  => [
                            'tickets' => [
                                'add' => 'Agregar entradas',
                            ],

                            'slots'   => [
                                'add'         => 'Agregar espacios',
                                'delete'      => 'Eliminar',
                                'description' => 'Espacios disponibles con duración de tiempo.',
                            ],
                        ],

                        'rental'      => [
                            'daily_hourly'           => 'Ambos (Diario y por hora)',
                            'daily-price'            => 'Precio diario',
                            'daily'                  => 'Por día',
                            'hourly-price'           => 'Precio por hora',
                            'hourly'                 => 'Por hora',

                            'same-slot-for-all-days' => [
                                'no'    => 'No',
                                'title' => 'Mismo espacio para todos los días',
                                'yes'   => 'Sí',
                            ],

                            'title'                  => 'Tipo de alquiler',
                        ],

                        'slots'       => [
                            'add'              => 'Agregar espacios',
                            'delete'           => 'Eliminar',
                            'description-info' => 'Espacios disponibles con duración de tiempo.',
                            'description'      => 'No hay espacios disponibles.',
                            'edit'             => 'Editar',

                            'modal'            => [
                                'slot' => [
                                    'friday'     => 'Viernes',
                                    'from'       => 'Desde',
                                    'monday'     => 'Lunes',
                                    'saturday'   => 'Sábado',
                                    'sunday'     => 'Domingo',
                                    'thursday'   => 'Jueves',
                                    'to'         => 'Hasta',
                                    'tuesday'    => 'Martes',
                                    'wednesday'  => 'Miércoles',
                                ],
                            ],

                            'save'             => 'Guardar',
                            'title'            => 'Espacios',
                        ],

                        'table'       => [
                            'break-duration'            => 'Tiempo de descanso entre espacios (minutos)',

                            'charged-per'               => [
                                'guest'  => 'Invitado',
                                'table'  => 'Mesa',
                                'title'  => 'Cobrado por',
                            ],

                            'guest-capacity'            => 'Capacidad de invitados',
                            'guest-limit'               => 'Límite de invitados por mesa',
                            'prevent-scheduling-before' => 'Prevenir programación antes de',
                            'slot-duration'             => 'Duración del espacio (minutos)',

                            'same-slot-for-all-days'    => [
                                'no'    => 'No',
                                'title' => 'Mismo espacio para todos los días',
                                'yes'   => 'Sí',
                            ],
                        ],
                    ],

                    'types'   => [
                        'booking' => [
                            'available-from' => 'Disponible Desde',
                            'available-to'   => 'Disponible Hasta',

                            'available-every-week'      => [
                                'no'    => 'No',
                                'title' => 'Disponible Todos los Días',
                                'yes'   => 'Sí',
                            ],

                            'location' => 'Ubicación',
                            'qty'      => 'Cantidad',

                            'type' => [
                                'appointment' => 'Cita',
                                'default'     => 'Predeterminado',
                                'event'       => 'Evento',
                                'many'        => 'Muchos',
                                'one'         => 'Uno',
                                'rental'      => 'Alquiler',
                                'table'       => 'Mesa',
                                'title'       => 'Tipo',
                            ],

                            'title' => 'Tipo de Reserva',
                        ],
                    ],
                ],

                'index' => [
                    'booking' => 'Reserva',
                ],
            ],
        ],

        'sales'   => [
            'bookings' => [
                'index' => [
                    'datagrid' => [
                        'created-date' => 'Fecha de creación',
                        'from'         => 'Desde',
                        'id'           => 'ID',
                        'order-id'     => 'ID de orden',
                        'qty'          => 'Cant.',
                        'to'           => 'Hasta',
                    ],

                    'title'    => 'Producto de Reservas',
                ],

                'title' => 'Producto de Reservas',
            ],
        ],
    ],

    'shop'  => [
        'products' => [
            'booking' => [
                'closed'           => 'Cerrado',

                'cart'             => [
                    'booking-from' => 'Desde la reserva',
                    'booking-till' => 'Hasta la reserva',
                    'daily'        => 'Diario',
                    'event-from'   => 'Desde el evento',
                    'event-ticket' => 'Boleto de evento',
                    'event-till'   => 'Hasta el evento',

                    'integrity'    => [
                        'missing_options'        => 'Faltan opciones para este producto.',
                        'select_hourly_duration' => 'Seleccione una duración de ranura de una hora.',
                    ],

                    'rent-from'    => 'Alquiler desde',
                    'rent-till'    => 'Alquiler hasta',
                    'rent-type'    => 'Tipo de alquiler',
                    'renting_type' => 'Tipo de alquiler',
                    'special-note' => 'Solicitud especial/Notas',
                ],

                'per-ticket-price' => ':price Por Ticket',
            ],

            'view'    => [
                'types'   => [
                    'booking' => [
                        'event-on'                 => 'Evento En',
                        'location'                 => 'Ubicación',
                        'slot-duration-in-minutes' => ':minutes Minutos',
                        'slot-duration'            => 'Duración de la Ranura',
                        'view-on-map'              => 'Ver en el Mapa',
                    ],
                ],

                'booking' => [
                    'appointment' => [
                        'closed'             => 'Cerrado',
                        'today-availability' => 'Disponibilidad de Hoy',
                    ],

                    'event'       => [
                        'book-your-ticket' => 'Reserve su Boleto',
                    ],

                    'rental'      => [
                        'choose-rent-option' => 'Elegir Opción de Renta',
                        'daily-basis'        => 'Base Diaria',
                        'from'               => 'Desde',
                        'hourly-basis'       => 'Base por Hora',
                        'rent-an-item'       => 'Rentar un Artículo',
                        'select-date'        => 'Seleccionar Fecha',
                        'select-rent-time'   => 'Seleccionar Hora de Renta',
                        'select-slot'        => 'Seleccionar Ranura',
                        'slot'               => 'Ranura',
                        'to'                 => 'Hasta',
                    ],

                    'slots'       => [
                        'book-an-appointment' => 'Reservar una Cita',
                        'date'                => 'Fecha',
                        'no-slots-available'  => 'No hay ranuras disponibles',
                        'title'               => 'Ranura',
                    ],

                    'table'       => [
                        'book-a-table'       => 'Reservar una Mesa',
                        'closed'             => 'Cerrado',
                        'slots-for-all-days' => 'Mostrar para todos los días',
                        'special-notes'      => 'Solicitudes Especiales/Notas',
                        'today-availability' => 'Disponibilidad de Hoy',
                    ],
                ],
            ],
        ],
    ],
];
