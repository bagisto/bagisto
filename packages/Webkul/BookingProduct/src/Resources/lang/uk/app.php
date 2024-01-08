<?php

return [
    'admin' => [
        'catalog' => [
            'products' => [
                'edit'  => [
                    'booking' => [
                        'appointment' => [
                            'break-duration'         => 'Час перерви між слотами (хв)',
                            'slot-duration'          => 'Тривалість слоту (хв)',

                            'same-slot-for-all-days' => [
                                'no'    => 'Ні',
                                'title' => 'Той самий слот щодня',
                                'yes'   => 'Так',
                            ],
                        ],

                        'default'     => [
                            'break-duration' => 'Час перерви між слотами (хв)',
                            'close'          => 'Закрити',
                            'delete'         => 'Видалити',
                            'description'    => 'Інформація про бронювання',
                            'edit'           => 'Редагувати',
                            'many'           => 'Багато',

                            'modal'          => [
                                'slot' => [
                                    'close'      => 'Закрити',
                                    'day'        => 'День',
                                    'edit-title' => 'Редагувати слоти',
                                    'friday'     => 'Пʼятниця',
                                    'from-day'   => 'З дня',
                                    'from'       => 'З',
                                    'monday'     => 'Понеділок',
                                    'open'       => 'Відкрити',
                                    'saturday'   => 'Субота',
                                    'save'       => 'Зберегти слот',
                                    'select'     => 'Вибрати',
                                    'status'     => 'Статус',
                                    'sunday'     => 'Неділя',
                                    'thursday'   => 'Четвер',
                                    'time'       => 'Час',
                                    'title'      => 'Додати слоти',
                                    'to'         => 'До',
                                    'tuesday'    => 'Вівторок',
                                    'wednesday'  => 'Середа',
                                    'week'       => ':day',
                                ],
                            ],

                            'one'            => 'Один',
                            'open'           => 'Відкрити',
                            'slot-duration'  => 'Тривалість слоту (хв)',
                            'title'          => 'За замовчуванням',
                        ],

                        'event'       => [
                            'add'                => 'Додати квитки',
                            'delete'             => 'Видалити',
                            'description-info'   => 'Доступних квитків немає.',
                            'description'        => 'Опис',
                            'edit'               => 'Редагувати',

                            'modal'              => [
                                'ticket' => [
                                    'save' => 'Зберегти квитки',
                                ],
                            ],

                            'name'               => 'Назва',
                            'price'              => 'Ціна',
                            'qty'                => 'Кількість',
                            'special-price-from' => 'Спеціальна ціна від',
                            'special-price-to'   => 'Спеціальна ціна до',
                            'special-price'      => 'Спеціальна ціна',
                            'title'              => 'Квитки',
                        ],

                        'empty-info'  => [
                            'tickets' => [
                                'add' => 'Додати квитки',
                            ],

                            'slots'   => [
                                'add'         => 'Додати слоти',
                                'delete'      => 'Видалити',
                                'description' => 'Наявні слоти з часовими інтервалами.',
                            ],
                        ],

                        'rental'      => [
                            'daily_hourly'           => 'Обидва (щоденно та за годину)',
                            'daily-price'            => 'Ціна за день',
                            'daily'                  => 'Щоденна оплата',
                            'hourly-price'           => 'Ціна за годину',
                            'hourly'                 => 'Оплата за годину',

                            'same-slot-for-all-days' => [
                                'no'    => 'Ні',
                                'title' => 'Той самий слот щодня',
                                'yes'   => 'Так',
                            ],

                            'title'                  => 'Тип оренди',
                        ],

                        'slots'       => [
                            'add'              => 'Додати слоти',
                            'delete'           => 'Видалити',
                            'description-info' => 'Наявні слоти з часовими інтервалами.',
                            'description'      => 'Слоти відсутні.',
                            'edit'             => 'Редагувати',

                            'modal'            => [
                                'slot' => [
                                    'friday'     => 'Пʼятниця',
                                    'from'       => 'З',
                                    'monday'     => 'Понеділок',
                                    'saturday'   => 'Субота',
                                    'sunday'     => 'Неділя',
                                    'thursday'   => 'Четвер',
                                    'to'         => 'До',
                                    'tuesday'    => 'Вівторок',
                                    'wednesday'  => 'Середа',
                                ],
                            ],

                            'save'             => 'Зберегти',
                            'title'            => 'Слоти',
                        ],

                        'table'       => [
                            'break-duration'            => 'Час перерви між слотами (хв)',

                            'charged-per'               => [
                                'guest'  => 'Гість',
                                'table'  => 'Стіл',
                                'title'  => 'Розраховано за',
                            ],

                            'guest-capacity'            => 'Місткість гостей',
                            'guest-limit'               => 'Ліміт гостей за столом',
                            'prevent-scheduling-before' => 'Заборона резервування до',
                            'slot-duration'             => 'Тривалість слоту (хв)',

                            'same-slot-for-all-days'    => [
                                'no'    => 'Ні',
                                'title' => 'Той самий слот щодня',
                                'yes'   => 'Так',
                            ],
                        ],

                        'types'   => [
                            'booking' => [
                                'available-from'            => 'Доступно з',
                                'available-to'              => 'Доступно до',

                                'available-every-week'      => [
                                    'no'    => 'Ні',
                                    'title' => 'Доступно щотижня',
                                    'yes'   => 'Так',
                                ],

                                'location'                  => 'Місце розташування',
                                'qty'                       => 'Кількість',

                                'type'                      => [
                                    'appointment' => 'Запис на прийом',
                                    'default'     => 'За замовчуванням',
                                    'event'       => 'Подія',
                                    'many'        => 'Багато',
                                    'one'         => 'Один',
                                    'rental'      => 'Оренда',
                                    'table'       => 'Стіл',
                                    'title'       => 'Тип',
                                ],

                                'title'                     => 'Тип бронювання',
                            ],
                        ],
                    ],
                ],

                'index' => [
                    'booking' => 'Бронювання',
                ],
            ],
        ],

        'sales'   => [
            'bookings' => [
                'index' => [
                    'datagrid' => [
                        'created-date' => 'Дата створення',
                        'from'         => 'Від',
                        'id'           => 'ID',
                        'order-id'     => 'ID замовлення',
                        'qty'          => 'Кількість',
                        'to'           => 'До',
                    ],

                    'title'    => 'Продукт бронювань',
                ],

                'title' => 'Продукт бронювань',
            ],
        ],
    ],

    'shop'  => [
        'products' => [
            'booking' => [
                'closed'           => 'Closed',

                'cart'             => [
                    'booking-from' => 'Booking From',
                    'booking-till' => 'Booking Till',
                    'daily'        => 'Daily',
                    'event-from'   => 'Event From',
                    'event-ticket' => 'Event Ticket',
                    'event-till'   => 'Event Till',

                    'integrity'    => [
                        'missing_options'        => 'Options are missing for this product.',
                        'select_hourly_duration' => 'Select a slot duration of one hour.',
                    ],

                    'rent-from'    => 'Rent From',
                    'rent-till'    => 'Rent Till',
                    'rent-type'    => 'Rent Type',
                    'renting_type' => 'Rent Type',
                    'special-note' => 'Special Request/Notes',
                ],

                'per-ticket-price' => ':price Per Ticket',
            ],

            'view'    => [
                'types'   => [
                    'booking' => [
                        'event-on'                 => 'Event On',
                        'location'                 => 'Location',
                        'slot-duration-in-minutes' => ':minutes Minutes',
                        'slot-duration'            => 'Slot Duration',
                        'view-on-map'              => 'View on Map',
                    ],
                ],

                'booking' => [
                    'appointment' => [
                        'closed'             => 'Closed',
                        'today-availability' => 'Today Availability',
                    ],

                    'event'       => [
                        'book-your-ticket' => 'Book Your Ticket',
                    ],

                    'rental'      => [
                        'choose-rent-option'       => 'Choose Rent Option',
                        'daily-basis'              => 'Daily Basis',
                        'from'                     => 'From',
                        'hourly-basis'             => 'Hourly Basis',
                        'rent-an-item'             => 'Rent an Item',
                        'select-date'              => 'Select date',
                        'select-rent-time'         => 'Select Rent Time',
                        'select-slot'              => 'Select Slot',
                        'slot'                     => 'Slot',
                        'to'                       => 'To',
                    ],

                    'slots'       => [
                        'book-an-appointment'       => 'Book an Appointment',
                        'date'                      => 'Date',
                        'no-slots-available'        => 'No slots available',
                        'title'                     => 'Slot',
                    ],

                    'table'       => [
                        'book-a-table'             => 'Book a Table',
                        'closed'                   => 'Closed',
                        'slots-for-all-days'       => 'Show for all days',
                        'special-notes'            => 'Special Request/Notes',
                        'today-availability'       => 'Today Availability',
                    ],
                ],
            ],
        ],
    ],
];
