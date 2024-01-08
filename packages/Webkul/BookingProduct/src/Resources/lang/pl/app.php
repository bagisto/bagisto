<?php

return [
    'admin' => [
        'catalog' => [
            'products' => [
                'edit' => [
                    'booking' => [
                        'appointment' => [
                            'break-duration'         => 'Czas przerwy między slotami (minuty)',
                            'slot-duration'          => 'Czas trwania slotu (minuty)',

                            'same-slot-for-all-days' => [
                                'no'    => 'Nie',
                                'title' => 'Ten sam slot dla wszystkich dni',
                                'yes'   => 'Tak',
                            ],
                        ],

                        'default'     => [
                            'break-duration' => 'Czas przerwy między slotami (minuty)',
                            'close'          => 'Zamknij',
                            'delete'         => 'Usuń',
                            'description'    => 'Informacje o rezerwacji',
                            'edit'           => 'Edytuj',
                            'many'           => 'Wiele',

                            'modal'          => [
                                'slot' => [
                                    'close'      => 'Zamknij',
                                    'day'        => 'Dzień',
                                    'edit-title' => 'Edytuj sloty',
                                    'friday'     => 'Piątek',
                                    'from-day'   => 'Od dnia',
                                    'from'       => 'Od',
                                    'monday'     => 'Poniedziałek',
                                    'open'       => 'Otwórz',
                                    'saturday'   => 'Sobota',
                                    'save'       => 'Zapisz slot',
                                    'select'     => 'Wybierz',
                                    'status'     => 'Status',
                                    'sunday'     => 'Niedziela',
                                    'thursday'   => 'Czwartek',
                                    'time'       => 'Czas',
                                    'title'      => 'Dodaj sloty',
                                    'to'         => 'Do',
                                    'tuesday'    => 'Wtorek',
                                    'wednesday'  => 'Środa',
                                    'week'       => ':day',
                                ],
                            ],

                            'one'            => 'Jeden',
                            'open'           => 'Otwórz',
                            'slot-duration'  => 'Czas trwania slotu (minuty)',
                            'title'          => 'Domyślny',
                        ],

                        'event'       => [
                            'add'                => 'Dodaj bilety',
                            'delete'             => 'Usuń',
                            'description-info'   => 'Brak dostępnych biletów.',
                            'description'        => 'Opis',
                            'edit'               => 'Edytuj',

                            'modal'              => [
                                'ticket' => [
                                    'save' => 'Zapisz bilety',
                                ],
                            ],

                            'name'               => 'Nazwa',
                            'price'              => 'Cena',
                            'qty'                => 'Ilość',
                            'special-price-from' => 'Specjalna cena od',
                            'special-price-to'   => 'Specjalna cena do',
                            'special-price'      => 'Specjalna cena',
                            'title'              => 'Bilety',
                        ],

                        'empty-info'  => [
                            'tickets' => [
                                'add' => 'Dodaj bilety',
                            ],

                            'slots'   => [
                                'add'         => 'Dodaj sloty',
                                'delete'      => 'Usuń',
                                'description' => 'Dostępne sloty z czasem trwania.',
                            ],
                        ],

                        'rental'      => [
                            'daily_hourly'           => 'Oba (dzienny i godzinowy)',
                            'daily-price'            => 'Cena dzienna',
                            'daily'                  => 'Dzienna podstawa',
                            'hourly-price'           => 'Cena za godzinę',
                            'hourly'                 => 'Godzinowa podstawa',

                            'same-slot-for-all-days' => [
                                'no'    => 'Nie',
                                'title' => 'Ten sam slot dla wszystkich dni',
                                'yes'   => 'Tak',
                            ],

                            'title'                  => 'Typ wynajmu',
                        ],

                        'slots'       => [
                            'add'              => 'Dodaj sloty',
                            'delete'           => 'Usuń',
                            'description-info' => 'Dostępne sloty z czasem trwania.',
                            'description'      => 'Brak dostępnych slotów.',
                            'edit'             => 'Edytuj',

                            'modal'            => [
                                'slot' => [
                                    'friday'    => 'Piątek',
                                    'from'      => 'Od',
                                    'monday'    => 'Poniedziałek',
                                    'saturday'  => 'Sobota',
                                    'sunday'    => 'Niedziela',
                                    'thursday'  => 'Czwartek',
                                    'to'        => 'Do',
                                    'tuesday'   => 'Wtorek',
                                    'wednesday' => 'Środa',
                                ],
                            ],

                            'save'             => 'Zapisz',
                            'title'            => 'Sloty',
                        ],

                        'table'       => [
                            'break-duration'            => 'Czas przerwy między slotami (minuty)',

                            'charged-per'               => [
                                'guest' => 'Gość',
                                'table' => 'Stół',
                                'title' => 'Obciążone za',
                            ],

                            'guest-capacity'            => 'Pojemność gości',
                            'guest-limit'               => 'Limit gości na stół',
                            'prevent-scheduling-before' => 'Zapobiegaj planowaniu przed',
                            'slot-duration'             => 'Czas trwania slotu (minuty)',

                            'same-slot-for-all-days'    => [
                                'no'    => 'Nie',
                                'title' => 'Ten sam slot dla wszystkich dni',
                                'yes'   => 'Tak',
                            ],
                        ],
                    ],

                    'types'   => [
                        'booking' => [
                            'available-from'       => 'Dostępne od',
                            'available-to'         => 'Dostępne do',

                            'available-every-week' => [
                                'no'    => 'Nie',
                                'title' => 'Dostępne każdego tygodnia',
                                'yes'   => 'Tak',
                            ],

                            'location'             => 'Lokalizacja',
                            'qty'                  => 'Ilość',

                            'type'                 => [
                                'appointment' => 'Spotkanie',
                                'default'     => 'Domyślny',
                                'event'       => 'Wydarzenie',
                                'many'        => 'Wiele',
                                'one'         => 'Jeden',
                                'rental'      => 'Wynajem',
                                'table'       => 'Stół',
                                'title'       => 'Typ',
                            ],

                            'title'                => 'Typ rezerwacji',
                        ],
                    ],

                    'index'   => [
                        'booking' => 'Rezerwacja',
                    ],
                ],
            ],
        ],

        'sales'   => [
            'bookings' => [
                'index' => [
                    'datagrid' => [
                        'created-date' => 'Data utworzenia',
                        'from'         => 'Od',
                        'id'           => 'ID',
                        'order-id'     => 'ID zamówienia',
                        'qty'          => 'Ilość',
                        'to'           => 'Do',
                    ],

                    'title'    => 'Produkt rezerwacji',
                ],

                'title' => 'Produkt rezerwacji',
            ],
        ],
    ],

    'shop'  => [
        'products' => [
            'booking' => [
                'closed'           => 'Zamknięte',

                'cart'             => [
                    'booking-from' => 'Rezerwacja Od',
                    'booking-till' => 'Rezerwacja Do',
                    'daily'        => 'Codziennie',
                    'event-from'   => 'Wydarzenie Od',
                    'event-ticket' => 'Bilet na Wydarzenie',
                    'event-till'   => 'Wydarzenie Do',

                    'integrity'    => [
                        'missing_options'        => 'Brak opcji dla tego produktu.',
                        'select_hourly_duration' => 'Wybierz czas trwania jednej godziny.',
                    ],

                    'rent-from'    => 'Wynajem Od',
                    'rent-till'    => 'Wynajem Do',
                    'rent-type'    => 'Typ Wynajmu',
                    'renting_type' => 'Typ Wynajmu',
                    'special-note' => 'Specjalne Życzenia/Uwagi',
                ],

                'per-ticket-price' => ':price Za Bilet',
            ],

            'view'    => [
                'types'   => [
                    'booking' => [
                        'event-on'                 => 'Wydarzenie Na',
                        'location'                 => 'Lokalizacja',
                        'slot-duration-in-minutes' => ':minutes Minut',
                        'slot-duration'            => 'Czas Trwania Slotu',
                        'view-on-map'              => 'Zobacz na Mapie',
                    ],
                ],

                'booking' => [
                    'appointment' => [
                        'closed'             => 'Zamknięte',
                        'today-availability' => 'Dostępność Dzisiaj',
                    ],

                    'event'       => [
                        'book-your-ticket' => 'Zarezerwuj Swój Bilet',
                    ],

                    'rental'      => [
                        'choose-rent-option' => 'Wybierz Opcję Wynajmu',
                        'daily-basis'        => 'Podstawowa Dzienna',
                        'from'               => 'Od',
                        'hourly-basis'       => 'Podstawowa Godzinowa',
                        'rent-an-item'       => 'Wynajmij przedmiot',
                        'select-date'        => 'Wybierz datę',
                        'select-rent-time'   => 'Wybierz Czas Wynajmu',
                        'select-slot'        => 'Wybierz Slot',
                        'slot'               => 'Slot',
                        'to'                 => 'Do',
                    ],

                    'slots'       => [
                        'book-an-appointment' => 'Zarezerwuj Spotkanie',
                        'date'                => 'Data',
                        'no-slots-available'  => 'Brak dostępnych slotów',
                        'title'               => 'Sloty',
                    ],

                    'table'       => [
                        'book-a-table'       => 'Zarezerwuj Stolik',
                        'closed'             => 'Zamknięte',
                        'slots-for-all-days' => 'Pokaż dla wszystkich dni',
                        'special-notes'      => 'Specjalne Życzenia/Uwagi',
                        'today-availability' => 'Dostępność Dzisiaj',
                    ],
                ],
            ],
        ],
    ],
];
