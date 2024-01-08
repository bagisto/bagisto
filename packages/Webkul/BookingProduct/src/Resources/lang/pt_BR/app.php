<?php

return [
    'admin' => [
        'catalog' => [
            'products' => [
                'edit' => [
                    'booking' => [
                        'appointment' => [
                            'break-duration'         => 'Tempo de Intervalo entre Slots (minutos)',
                            'slot-duration'          => 'Duração do Slot (minutos)',

                            'same-slot-for-all-days' => [
                                'no'    => 'Não',
                                'title' => 'Mesmo Slot Para Todos os Dias',
                                'yes'   => 'Sim',
                            ],
                        ],

                        'default'     => [
                            'break-duration' => 'Tempo de Intervalo entre Slots (minutos)',
                            'close'          => 'Fechar',
                            'delete'         => 'Apagar',
                            'description'    => 'Informações de Reserva',
                            'edit'           => 'Editar',
                            'many'           => 'Muitos',

                            'modal'          => [
                                'slot' => [
                                    'close'      => 'Fechar',
                                    'day'        => 'Dia',
                                    'edit-title' => 'Editar Slots',
                                    'friday'     => 'Sexta-feira',
                                    'from-day'   => 'Do Dia',
                                    'from'       => 'De',
                                    'monday'     => 'Segunda-feira',
                                    'open'       => 'Abrir',
                                    'saturday'   => 'Sábado',
                                    'save'       => 'Salvar Slot',
                                    'select'     => 'Selecionar',
                                    'status'     => 'Estado',
                                    'sunday'     => 'Domingo',
                                    'thursday'   => 'Quinta-feira',
                                    'time'       => 'Tempo',
                                    'title'      => 'Adicionar Slots',
                                    'to'         => 'Para',
                                    'tuesday'    => 'Terça-feira',
                                    'wednesday'  => 'Quarta-feira',
                                    'week'       => ':day',
                                ],
                            ],

                            'one'            => 'Um',
                            'open'           => 'Abrir',
                            'slot-duration'  => 'Duração do Slot (minutos)',
                            'title'          => 'Padrão',
                        ],

                        'event'       => [
                            'add'                => 'Adicionar Bilhetes',
                            'delete'             => 'Apagar',
                            'description-info'   => 'Não há bilhetes disponíveis.',
                            'description'        => 'Descrição',
                            'edit'               => 'Editar',

                            'modal'              => [
                                'ticket' => [
                                    'save' => 'Salvar Bilhetes',
                                ],
                            ],

                            'name'               => 'Nome',
                            'price'              => 'Preço',
                            'qty'                => 'Quantidade',
                            'special-price-from' => 'Preço Especial De',
                            'special-price-to'   => 'Preço Especial Até',
                            'special-price'      => 'Preço Especial',
                            'title'              => 'Bilhetes',
                        ],

                        'empty-info'  => [
                            'tickets' => [
                                'add' => 'Adicionar Bilhetes',
                            ],

                            'slots'   => [
                                'add'         => 'Adicionar Slots',
                                'delete'      => 'Apagar',
                                'description' => 'Slots Disponíveis com Duração do Tempo.',
                            ],
                        ],

                        'rental'      => [
                            'daily_hourly'           => 'Ambos (Diário e Por Hora)',
                            'daily-price'            => 'Preço Diário',
                            'daily'                  => 'Base Diária',
                            'hourly-price'           => 'Preço Por Hora',
                            'hourly'                 => 'Base Por Hora',

                            'same-slot-for-all-days' => [
                                'no'    => 'Não',
                                'title' => 'Mesmo Slot Para Todos os Dias',
                                'yes'   => 'Sim',
                            ],

                            'title'                  => 'Tipo de Aluguel',
                        ],

                        'slots'       => [
                            'add'              => 'Adicionar Slots',
                            'delete'           => 'Apagar',
                            'description-info' => 'Slots Disponíveis com Duração do Tempo.',
                            'description'      => 'Não há slots disponíveis.',
                            'edit'             => 'Editar',

                            'modal'            => [
                                'slot' => [
                                    'friday'    => 'Sexta-feira',
                                    'from'      => 'De',
                                    'monday'    => 'Segunda-feira',
                                    'saturday'  => 'Sábado',
                                    'sunday'    => 'Domingo',
                                    'thursday'  => 'Quinta-feira',
                                    'to'        => 'Para',
                                    'tuesday'   => 'Terça-feira',
                                    'wednesday' => 'Quarta-feira',
                                ],
                            ],

                            'save'             => 'Salvar',
                            'title'            => 'Slots',
                        ],

                        'table'       => [
                            'break-duration'            => 'Tempo de Intervalo entre Slots (minutos)',

                            'charged-per'               => [
                                'guest' => 'Hóspede',
                                'table' => 'Mesa',
                                'title' => 'Cobrado Por',
                            ],

                            'guest-capacity'            => 'Capacidade de Hóspedes',
                            'guest-limit'               => 'Limite de Hóspedes por Mesa',
                            'prevent-scheduling-before' => 'Impedir Agendamento Antes de',
                            'slot-duration'             => 'Duração do Slot (minutos)',

                            'same-slot-for-all-days'    => [
                                'no'    => 'Não',
                                'title' => 'Mesmo Slot Para Todos os Dias',
                                'yes'   => 'Sim',
                            ],
                        ],
                    ],

                    'types'   => [
                        'booking' => [
                            'available-from'       => 'Disponível De',
                            'available-to'         => 'Disponível Até',

                            'available-every-week' => [
                                'no'    => 'Não',
                                'title' => 'Disponível Todas as Semanas',
                                'yes'   => 'Sim',
                            ],

                            'location'             => 'Localização',
                            'qty'                  => 'Quantidade',

                            'type'                 => [
                                'appointment' => 'Agendamento',
                                'default'     => 'Padrão',
                                'event'       => 'Evento',
                                'many'        => 'Vários',
                                'one'         => 'Um',
                                'rental'      => 'Aluguel',
                                'table'       => 'Mesa',
                                'title'       => 'Tipo',
                            ],

                            'title' => 'Tipo de Reserva',
                        ],
                    ],

                    'index'   => [
                        'booking' => 'Reserva',
                    ],
                ],
            ],
        ],

        'sales'   => [
            'bookings' => [
                'index' => [
                    'datagrid' => [
                        'created-date' => 'Data de Criação',
                        'from'         => 'De',
                        'id'           => 'ID',
                        'order-id'     => 'ID do Pedido',
                        'qty'          => 'Quantidade',
                        'to'           => 'Para',
                    ],

                    'title' => 'Produto de Reservas',
                ],

                'title' => 'Produto de Reservas',
            ],
        ],
    ],

    'shop'  => [
        'products' => [
            'booking' => [
                'closed'           => 'Fechado',

                'cart'             => [
                    'booking-from' => 'Reserva De',
                    'booking-till' => 'Reserva Até',
                    'daily'        => 'Diário',
                    'event-from'   => 'Evento De',
                    'event-ticket' => 'Bilhete de Evento',
                    'event-till'   => 'Evento Até',

                    'integrity'    => [
                        'missing_options'        => 'Opções em falta para este produto.',
                        'select_hourly_duration' => 'Selecione uma duração do slot de uma hora.',
                    ],

                    'rent-from'    => 'Aluguer De',
                    'rent-till'    => 'Aluguer Até',
                    'rent-type'    => 'Tipo de Aluguer',
                    'renting_type' => 'Tipo de Aluguer',
                    'special-note' => 'Pedido Especial/Notas',
                ],

                'per-ticket-price' => ':price Por Bilhete',
            ],

            'view'    => [
                'types'   => [
                    'booking' => [
                        'event-on'                 => 'Evento Em',
                        'location'                 => 'Localização',
                        'slot-duration-in-minutes' => ':minutes Minutos',
                        'slot-duration'            => 'Duração do Slot',
                        'view-on-map'              => 'Ver no Mapa',
                    ],
                ],

                'booking' => [
                    'appointment' => [
                        'closed'             => 'Fechado',
                        'today-availability' => 'Disponibilidade Hoje',
                    ],

                    'event'       => [
                        'book-your-ticket' => 'Reserve o Seu Bilhete',
                    ],

                    'rental'      => [
                        'choose-rent-option' => 'Escolha a Opção de Aluguer',
                        'daily-basis'        => 'Base Diária',
                        'from'               => 'De',
                        'hourly-basis'       => 'Base Horária',
                        'rent-an-item'       => 'Alugar um Item',
                        'select-date'        => 'Selecionar Data',
                        'select-rent-time'   => 'Selecionar Tempo de Aluguer',
                        'select-slot'        => 'Selecionar Slot',
                        'slot'               => 'Slot',
                        'to'                 => 'Até',
                    ],

                    'slots'       => [
                        'book-an-appointment' => 'Marcar uma Consulta',
                        'date'                => 'Data',
                        'no-slots-available'  => 'Sem slots disponíveis',
                        'title'               => 'Slots',
                    ],

                    'table'     => [
                        'book-a-table'       => 'Reservar uma Mesa',
                        'closed'             => 'Fechado',
                        'slots-for-all-days' => 'Mostrar para todos os dias',
                        'special-notes'      => 'Pedido Especial/Notas',
                        'today-availability' => 'Disponibilidade Hoje',
                    ],
                ],
            ],
        ],
    ],
];
