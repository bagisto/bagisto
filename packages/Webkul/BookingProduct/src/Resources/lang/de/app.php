<?php

return [
    'admin' => [
        'catalog' => [
            'products' => [
                'edit'  => [
                    'booking' => [
                        'appointment' => [
                            'break-duration'         => 'Pause zwischen Slots (Minuten)',
                            'slot-duration'          => 'Slot-Dauer (Minuten)',

                            'same-slot-for-all-days' => [
                                'no'    => 'Nein',
                                'title' => 'Gleicher Slot für alle Tage',
                                'yes'   => 'Ja',
                            ],
                        ],

                        'default'     => [
                            'break-duration' => 'Pause zwischen Slots (Minuten)',
                            'close'          => 'Schließen',
                            'delete'         => 'Löschen',
                            'description'    => 'Buchungsinformationen',
                            'edit'           => 'Bearbeiten',
                            'many'           => 'Viele',

                            'modal'          => [
                                'slot' => [
                                    'close'      => 'Schließen',
                                    'day'        => 'Tag',
                                    'edit-title' => 'Slots bearbeiten',
                                    'friday'     => 'Freitag',
                                    'from-day'   => 'Ab Tag',
                                    'from'       => 'Von',
                                    'monday'     => 'Montag',
                                    'open'       => 'Öffnen',
                                    'saturday'   => 'Samstag',
                                    'save'       => 'Slot speichern',
                                    'select'     => 'Auswählen',
                                    'status'     => 'Status',
                                    'sunday'     => 'Sonntag',
                                    'thursday'   => 'Donnerstag',
                                    'time'       => 'Zeit',
                                    'title'      => 'Slots hinzufügen',
                                    'to'         => 'Bis',
                                    'tuesday'    => 'Dienstag',
                                    'wednesday'  => 'Mittwoch',
                                    'week'       => ':day',
                                ],
                            ],

                            'one'            => 'Eins',
                            'open'           => 'Öffnen',
                            'slot-duration'  => 'Slot-Dauer (Minuten)',
                            'title'          => 'Standard',
                        ],

                        'event'       => [
                            'add'                => 'Tickets hinzufügen',
                            'delete'             => 'Löschen',
                            'description-info'   => 'Keine Tickets verfügbar.',
                            'description'        => 'Beschreibung',
                            'edit'               => 'Bearbeiten',

                            'modal'             => [
                                'ticket' => [
                                    'save' => 'Tickets speichern',
                                ],
                            ],

                            'name'               => 'Name',
                            'price'              => 'Preis',
                            'qty'                => 'Menge',
                            'special-price-from' => 'Sonderpreis von',
                            'special-price-to'   => 'Sonderpreis bis',
                            'special-price'      => 'Sonderpreis',
                            'title'              => 'Tickets',
                        ],

                        'empty-info'  => [
                            'tickets' => [
                                'add' => 'Tickets hinzufügen',
                            ],

                            'slots'   => [
                                'add'         => 'Slots hinzufügen',
                                'delete'      => 'Löschen',
                                'description' => 'Verfügbare Slots mit Zeitdauer.',
                            ],
                        ],

                        'rental'      => [
                            'daily_hourly'           => 'Beides (täglich und stündlich)',
                            'daily-price'            => 'Tagespreis',
                            'daily'                  => 'Tägliche Basis',
                            'hourly-price'           => 'Stundenpreis',
                            'hourly'                 => 'Stündliche Basis',

                            'same-slot-for-all-days' => [
                                'no'    => 'Nein',
                                'title' => 'Gleicher Slot für alle Tage',
                                'yes'   => 'Ja',
                            ],

                            'title'                  => 'Vermietungstyp',
                        ],

                        'slots'       => [
                            'add'              => 'Slots hinzufügen',
                            'delete'           => 'Löschen',
                            'description-info' => 'Verfügbare Slots mit Zeitdauer.',
                            'description'      => 'Keine Slots verfügbar.',
                            'edit'             => 'Bearbeiten',

                            'modal'            => [
                                'slot' => [
                                    'friday'     => 'Freitag',
                                    'from'       => 'Von',
                                    'monday'     => 'Montag',
                                    'saturday'   => 'Samstag',
                                    'sunday'     => 'Sonntag',
                                    'thursday'   => 'Donnerstag',
                                    'to'         => 'Bis',
                                    'tuesday'    => 'Dienstag',
                                    'wednesday'  => 'Mittwoch',
                                ],
                            ],

                            'save'             => 'Speichern',
                            'title'            => 'Slots',
                        ],

                        'table'       => [
                            'break-duration'            => 'Pause zwischen Slots (Minuten)',

                            'charged-per'               => [
                                'guest' => 'Gast',
                                'table' => 'Tisch',
                                'title' => 'Pro Gebühr',
                            ],

                            'guest-capacity'            => 'Gastkapazität',
                            'guest-limit'               => 'Gastlimit pro Tisch',
                            'prevent-scheduling-before' => 'Vorzeitige Terminplanung verhindern',
                            'slot-duration'             => 'Slot-Dauer (Minuten)',

                            'same-slot-for-all-days'    => [
                                'no'    => 'Nein',
                                'title' => 'Gleicher Slot für alle Tage',
                                'yes'   => 'Ja',
                            ],
                        ],
                    ],

                    'types'   => [
                        'booking' => [
                            'available-from'       => 'Verfügbar ab',
                            'available-to'         => 'Verfügbar bis',

                            'available-every-week' => [
                                'no'    => 'Nein',
                                'title' => 'Jede Woche verfügbar',
                                'yes'   => 'Ja',
                            ],

                            'location'             => 'Ort',
                            'qty'                  => 'Menge',

                            'type'                 => [
                                'appointment' => 'Termin',
                                'default'     => 'Standard',
                                'event'       => 'Veranstaltung',
                                'many'        => 'Viele',
                                'one'         => 'Ein',
                                'rental'      => 'Vermietung',
                                'table'       => 'Tisch',
                                'title'       => 'Typ',
                            ],

                            'title'                => 'Buchungstyp',
                        ],
                    ],
                ],

                'index' => [
                    'booking' => 'Buchung',
                ],
            ],
        ],

        'sales'   => [
            'bookings' => [
                'index' => [
                    'datagrid' => [
                        'created-date' => 'Erstellungsdatum',
                        'from'         => 'Von',
                        'id'           => 'ID',
                        'order-id'     => 'Bestell-ID',
                        'qty'          => 'Menge',
                        'to'           => 'Bis',
                    ],

                    'title'    => 'Buchungsprodukt',
                ],

                'title' => 'Buchungsprodukt',
            ],
        ],
    ],

    'shop'  => [
        'products' => [
            'booking' => [
                'closed'           => 'Geschlossen',

                'cart'             => [
                    'booking-from' => 'Buchung von',
                    'booking-till' => 'Buchung bis',
                    'daily'        => 'Täglich',
                    'event-from'   => 'Event von',
                    'event-ticket' => 'Event-Ticket',
                    'event-till'   => 'Event bis',

                    'integrity'    => [
                        'missing_options'        => 'Optionen fehlen für dieses Produkt.',
                        'select_hourly_duration' => 'Wählen Sie eine Zeitdauer von einer Stunde.',
                    ],

                    'rent-from'    => 'Vermietung von',
                    'rent-till'    => 'Vermietung bis',
                    'rent-type'    => 'Mietart',
                    'renting_type' => 'Mietart',
                    'special-note' => 'Besondere Anfragen/Notizen',
                ],

                'per-ticket-price' => ':price pro Ticket',
            ],

            'view' => [
                'types'   => [
                    'booking' => [
                        'event-on'                 => 'Event am',
                        'location'                 => 'Ort',
                        'slot-duration-in-minutes' => ':minutes Minuten',
                        'slot-duration'            => 'Zeitspanne',
                        'view-on-map'              => 'Auf Karte anzeigen',
                    ],
                ],

                'booking' => [
                    'appointment' => [
                        'closed'             => 'Geschlossen',
                        'today-availability' => 'Heutige Verfügbarkeit',
                    ],

                    'event'       => [
                        'book-your-ticket' => 'Buchen Sie Ihr Ticket',
                    ],

                    'rental'      => [
                        'choose-rent-option' => 'Wählen Sie die Mietoption',
                        'daily-basis'        => 'Tägliche Basis',
                        'from'               => 'Von',
                        'hourly-basis'       => 'Stündliche Basis',
                        'rent-an-item'       => 'Artikel mieten',
                        'select-date'        => 'Datum wählen',
                        'select-rent-time'   => 'Mietzeit auswählen',
                        'select-slot'        => 'Slot auswählen',
                        'slot'               => 'Slot',
                        'to'                 => 'Bis',
                    ],

                    'slots'       => [
                        'book-an-appointment' => 'Termin buchen',
                        'date'                => 'Datum',
                        'no-slots-available'  => 'Keine Slots verfügbar',
                        'title'               => 'Slot',
                    ],

                    'table'       => [
                        'book-a-table'       => 'Tisch buchen',
                        'closed'             => 'Geschlossen',
                        'slots-for-all-days' => 'Für alle Tage anzeigen',
                        'special-notes'      => 'Besondere Anfragen/Notizen',
                        'today-availability' => 'Heutige Verfügbarkeit',
                    ],
                ],
            ],
        ],
    ],
];
