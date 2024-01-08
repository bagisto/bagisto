<?php

return [
    'admin' => [
        'catalog' => [
            'products' => [
                'edit' => [
                    'booking' => [
                        'appointment' => [
                            'break-duration' => 'Tempo di pausa tra gli slot (minuti)',
                            'slot-duration'  => 'Durata dello slot (minuti)',

                            'same-slot-for-all-days' => [
                                'no'    => 'No',
                                'title' => 'Stesso slot per tutti i giorni',
                                'yes'   => 'Sì',
                            ],
                        ],

                        'default' => [
                            'break-duration' => 'Tempo di pausa tra gli slot (minuti)',
                            'close'          => 'Chiudi',
                            'delete'         => 'Elimina',
                            'description'    => 'Informazioni prenotazione',
                            'edit'           => 'Modifica',
                            'many'           => 'Molti',

                            'modal' => [
                                'slot' => [
                                    'close'      => 'Chiudi',
                                    'day'        => 'Giorno',
                                    'edit-title' => 'Modifica slot',
                                    'friday'     => 'Venerdì',
                                    'from-day'   => 'Dal giorno',
                                    'from'       => 'Da',
                                    'monday'     => 'Lunedì',
                                    'open'       => 'Aperto',
                                    'saturday'   => 'Sabato',
                                    'save'       => 'Salva slot',
                                    'select'     => 'Seleziona',
                                    'status'     => 'Stato',
                                    'sunday'     => 'Domenica',
                                    'thursday'   => 'Giovedì',
                                    'time'       => 'Ora',
                                    'title'      => 'Aggiungi slot',
                                    'to'         => 'A',
                                    'tuesday'    => 'Martedì',
                                    'wednesday'  => 'Mercoledì',
                                    'week'       => ':giorno',
                                ],
                            ],

                            'one'           => 'Uno',
                            'open'          => 'Apri',
                            'slot-duration' => 'Durata dello slot (minuti)',
                            'title'         => 'Predefinito',
                        ],

                        'event' => [
                            'add'              => 'Aggiungi biglietti',
                            'delete'           => 'Elimina',
                            'description-info' => 'Non ci sono biglietti disponibili.',
                            'description'      => 'Descrizione',
                            'edit'             => 'Modifica',

                            'modal' => [
                                'ticket' => [
                                    'save' => 'Salva biglietti',
                                ],
                            ],

                            'name'               => 'Nome',
                            'price'              => 'Prezzo',
                            'qty'                => 'Quantità',
                            'special-price-from' => 'Prezzo speciale da',
                            'special-price-to'   => 'Prezzo speciale a',
                            'special-price'      => 'Prezzo speciale',
                            'title'              => 'Biglietti',
                        ],

                        'empty-info' => [
                            'tickets' => [
                                'add' => 'Aggiungi biglietti',
                            ],

                            'slots' => [
                                'add'         => 'Aggiungi slot',
                                'delete'      => 'Elimina',
                                'description' => 'Slot disponibili con durata temporale.',
                            ],
                        ],

                        'rental' => [
                            'daily_hourly' => 'Entrambi (Giornaliero ed orario)',
                            'daily-price'  => 'Prezzo giornaliero',
                            'daily'        => 'Base giornaliera',
                            'hourly-price' => 'Prezzo orario',
                            'hourly'       => 'Base oraria',

                            'same-slot-for-all-days' => [
                                'no'    => 'No',
                                'title' => 'Stesso slot per tutti i giorni',
                                'yes'   => 'Sì',
                            ],

                            'title' => 'Tipo di noleggio',
                        ],

                        'slots' => [
                            'add'              => 'Aggiungi slot',
                            'delete'           => 'Elimina',
                            'description-info' => 'Slot disponibili con durata temporale.',
                            'description'      => 'Non ci sono slot disponibili.',
                            'edit'             => 'Modifica',

                            'modal' => [
                                'slot' => [
                                    'friday'    => 'Venerdì',
                                    'from'      => 'Da',
                                    'monday'    => 'Lunedì',
                                    'saturday'  => 'Sabato',
                                    'sunday'    => 'Domenica',
                                    'thursday'  => 'Giovedì',
                                    'to'        => 'A',
                                    'tuesday'   => 'Martedì',
                                    'wednesday' => 'Mercoledì',
                                ],
                            ],

                            'save'  => 'Salva',
                            'title' => 'Slot',
                        ],

                        'table' => [
                            'break-duration' => 'Tempo di pausa tra gli slot (minuti)',

                            'charged-per' => [
                                'guest' => 'Ospite',
                                'table' => 'Tavolo',
                                'title' => 'Addebitato per',
                            ],

                            'guest-capacity'            => 'Capacità ospiti',
                            'guest-limit'               => 'Limite ospiti per tavolo',
                            'prevent-scheduling-before' => 'Evita programmazione prima di',
                            'slot-duration'             => 'Durata dello slot (minuti)',

                            'same-slot-for-all-days' => [
                                'no'    => 'No',
                                'title' => 'Stesso slot per tutti i giorni',
                                'yes'   => 'Sì',
                            ],
                        ],
                    ],

                    'types' => [
                        'booking' => [
                            'available-from' => 'Disponibile da',
                            'available-to'   => 'Disponibile fino a',

                            'available-every-week' => [
                                'no'    => 'No',
                                'title' => 'Disponibile ogni settimana',
                                'yes'   => 'Sì',
                            ],

                            'location' => 'Posizione',
                            'qty'      => 'Quantità',

                            'type' => [
                                'appointment' => 'Appuntamento',
                                'default'     => 'Predefinito',
                                'event'       => 'Evento',
                                'many'        => 'Molti',
                                'one'         => 'Uno',
                                'rental'      => 'Noleggio',
                                'table'       => 'Tavolo',
                                'title'       => 'Tipo',
                            ],

                            'title' => 'Tipo di prenotazione',
                        ],
                    ],

                    'index' => [
                        'booking' => 'Prenotazione',
                    ],
                ],
            ],
        ],

        'sales'   => [
            'bookings' => [
                'index' => [
                    'datagrid' => [
                        'created-date' => 'Data di Creazione',
                        'from'         => 'Da',
                        'id'           => 'ID',
                        'order-id'     => 'ID Ordine',
                        'qty'          => 'Quantità',
                        'to'           => 'A',
                    ],

                    'title' => 'Prodotto Prenotazioni',
                ],

                'title' => 'Prodotto Prenotazioni',
            ],
        ],
    ],

    'shop'  => [
        'products' => [
            'booking' => [
                'closed'           => 'Chiuso',

                'cart'             => [
                    'booking-from' => 'Prenotazione Da',
                    'booking-till' => 'Prenotazione Fino A',
                    'daily'        => 'Giornaliero',
                    'event-from'   => 'Evento Da',
                    'event-ticket' => 'Biglietto Evento',
                    'event-till'   => 'Evento Fino A',

                    'integrity'    => [
                        'missing_options'        => 'Opzioni mancanti per questo prodotto.',
                        'select_hourly_duration' => 'Seleziona una durata dello slot di un\'ora.',
                    ],

                    'rent-from'    => 'Affitto Da',
                    'rent-till'    => 'Affitto Fino A',
                    'rent-type'    => 'Tipo di Affitto',
                    'renting_type' => 'Tipo di Affitto',
                    'special-note' => 'Richieste Speciali/Note',
                ],

                'per-ticket-price' => ':price Per Biglietto',
            ],

            'view'    => [
                'types'   => [
                    'booking' => [
                        'event-on'                 => 'Evento Il',
                        'location'                 => 'Posizione',
                        'slot-duration-in-minutes' => ':minutes Minuti',
                        'slot-duration'            => 'Durata dello Slot',
                        'view-on-map'              => 'Visualizza sulla Mappa',
                    ],
                ],

                'booking' => [
                    'appointment' => [
                        'closed'             => 'Chiuso',
                        'today-availability' => 'Disponibilità Oggi',
                    ],

                    'event'       => [
                        'book-your-ticket' => 'Prenota Il Tuo Biglietto',
                    ],

                    'rental'      => [
                        'choose-rent-option' => 'Scegli Opzione di Affitto',
                        'daily-basis'        => 'Base Giornaliera',
                        'from'               => 'Da',
                        'hourly-basis'       => 'Base Oraria',
                        'rent-an-item'       => 'Affitta un Oggetto',
                        'select-date'        => 'Seleziona data',
                        'select-rent-time'   => 'Seleziona Orario Affitto',
                        'select-slot'        => 'Seleziona Slot',
                        'slot'               => 'Slot',
                        'to'                 => 'A',
                    ],

                    'slots'       => [
                        'book-an-appointment' => 'Prenota un Appuntamento',
                        'date'                => 'Data',
                        'no-slots-available'  => 'Nessuno slot disponibile',
                        'title'               => 'Slot',
                    ],

                    'table'       => [
                        'book-a-table'       => 'Prenota un Tavolo',
                        'closed'             => 'Chiuso',
                        'slots-for-all-days' => 'Mostra per tutti i giorni',
                        'special-notes'      => 'Richieste Speciali/Note',
                        'today-availability' => 'Disponibilità Oggi',
                    ],
                ],
            ],
        ],
    ],
];
