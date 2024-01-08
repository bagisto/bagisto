<?php

return [
    'admin' => [
        'catalog' => [
            'products' => [
                'edit' => [
                    'booking' => [
                        'appointment' => [
                            'break-duration'         => 'Перерыв между слотами (мин)',
                            'slot-duration'          => 'Длительность слота (мин)',

                            'same-slot-for-all-days' => [
                                'no'    => 'Нет',
                                'title' => 'Тот же слот на все дни',
                                'yes'   => 'Да',
                            ],
                        ],

                        'default'     => [
                            'break-duration' => 'Перерыв между слотами (мин)',
                            'close'          => 'Закрыть',
                            'delete'         => 'Удалить',
                            'description'    => 'Информация о бронировании',
                            'edit'           => 'Редактировать',
                            'many'           => 'Много',

                            'modal'          => [
                                'slot' => [
                                    'close'      => 'Закрыть',
                                    'day'        => 'День',
                                    'edit-title' => 'Редактировать слоты',
                                    'friday'     => 'Пятница',
                                    'from-day'   => 'С дня',
                                    'from'       => 'С',
                                    'monday'     => 'Понедельник',
                                    'open'       => 'Открыть',
                                    'saturday'   => 'Суббота',
                                    'save'       => 'Сохранить слот',
                                    'select'     => 'Выбрать',
                                    'status'     => 'Статус',
                                    'sunday'     => 'Воскресенье',
                                    'thursday'   => 'Четверг',
                                    'time'       => 'Время',
                                    'title'      => 'Добавить слоты',
                                    'to'         => 'До',
                                    'tuesday'    => 'Вторник',
                                    'wednesday'  => 'Среда',
                                    'week'       => ':day',
                                ],
                            ],

                            'one'            => 'Один',
                            'open'           => 'Открыть',
                            'slot-duration'  => 'Длительность слота (мин)',
                            'title'          => 'По умолчанию',
                        ],

                        'event'       => [
                            'add'               => 'Добавить билеты',
                            'delete'            => 'Удалить',
                            'description-info'  => 'Нет доступных билетов.',
                            'description'       => 'Описание',
                            'edit'              => 'Редактировать',

                            'modal'             => [
                                'ticket' => [
                                    'save' => 'Сохранить билеты',
                                ],
                            ],

                            'name'               => 'Имя',
                            'price'              => 'Цена',
                            'qty'                => 'Количество',
                            'special-price-from' => 'Спец. цена от',
                            'special-price-to'   => 'Спец. цена до',
                            'special-price'      => 'Спец. цена',
                            'title'              => 'Билеты',
                        ],

                        'empty-info'  => [
                            'tickets' => [
                                'add' => 'Добавить билеты',
                            ],

                            'slots'   => [
                                'add'         => 'Добавить слоты',
                                'delete'      => 'Удалить',
                                'description' => 'Доступные слоты с продолжительностью времени.',
                            ],
                        ],

                        'rental'      => [
                            'daily_hourly'           => 'Избранный (ежедневно и почасово)',
                            'daily-price'            => 'Цена за день',
                            'daily'                  => 'Ежедневно',
                            'hourly-price'           => 'Цена за час',
                            'hourly'                 => 'Почасово',

                            'same-slot-for-all-days' => [
                                'no'    => 'Нет',
                                'title' => 'Тот же слот на все дни',
                                'yes'   => 'Да',
                            ],

                            'title'                  => 'Тип аренды',
                        ],

                        'slots'       => [
                            'add'              => 'Добавить слоты',
                            'delete'           => 'Удалить',
                            'description-info' => 'Доступные слоты с продолжительностью времени.',
                            'description'      => 'Нет доступных слотов.',
                            'edit'             => 'Редактировать',

                            'modal' => [
                                'slot' => [
                                    'friday'    => 'Пятница',
                                    'from'      => 'От',
                                    'monday'    => 'Понедельник',
                                    'saturday'  => 'Суббота',
                                    'sunday'    => 'Воскресенье',
                                    'thursday'  => 'Четверг',
                                    'to'        => 'До',
                                    'tuesday'   => 'Вторник',
                                    'wednesday' => 'Среда',
                                ],
                            ],

                            'save'  => 'Сохранить',
                            'title' => 'Слоты',
                        ],

                        'table'       => [
                            'break-duration'            => 'Перерыв между слотами (мин)',

                            'charged-per'               => [
                                'guest' => 'Гость',
                                'table' => 'Стол',
                                'title' => 'Оплачивается за',
                            ],

                            'guest-capacity'            => 'Вместимость гостей',
                            'guest-limit'               => 'Лимит гостей за столом',
                            'prevent-scheduling-before' => 'Предотвратить запись до',
                            'slot-duration'             => 'Длительность слота (мин)',

                            'same-slot-for-all-days'    => [
                                'no'    => 'Нет',
                                'title' => 'Тот же слот на все дни',
                                'yes'   => 'Да',
                            ],
                        ],
                    ],

                    'types'   => [
                        'booking' => [
                            'available-from'       => 'Доступно от',
                            'available-to'         => 'Доступно до',

                            'available-every-week' => [
                                'no'    => 'Нет',
                                'title' => 'Доступно каждую неделю',
                                'yes'   => 'Да',
                            ],

                            'location'             => 'Местоположение',
                            'qty'                  => 'Количество',

                            'type'                 => [
                                'appointment' => 'Прием',
                                'default'     => 'По умолчанию',
                                'event'       => 'Событие',
                                'many'        => 'Много',
                                'one'         => 'Один',
                                'rental'      => 'Аренда',
                                'table'       => 'Стол',
                                'title'       => 'Тип',
                            ],

                            'title'                => 'Тип бронирования',
                        ],
                    ],

                    'index'   => [
                        'booking' => 'Бронирование',
                    ],
                ],
            ],
        ],

        'sales'   => [
            'bookings' => [
                'index' => [
                    'datagrid' => [
                        'created-date' => 'Дата создания',
                        'from'         => 'От',
                        'id'           => 'ИД',
                        'order-id'     => 'Номер заказа',
                        'qty'          => 'Кол-во',
                        'to'           => 'До',
                    ],

                    'title' => 'Товары для бронирования',
                ],

                'title' => 'Товары для бронирования',
            ],
        ],
    ],

    'shop'  => [
        'products' => [
            'booking' => [
                'closed'           => 'Закрыто',

                'cart'             => [
                    'booking-from' => 'Бронирование от',
                    'booking-till' => 'Бронирование до',
                    'daily'        => 'Ежедневно',
                    'event-from'   => 'Событие с',
                    'event-ticket' => 'Билет на событие',
                    'event-till'   => 'Событие до',

                    'integrity'    => [
                        'missing_options'        => 'Отсутствуют опции для этого продукта.',
                        'select_hourly_duration' => 'Выберите продолжительность слота в один час.',
                    ],

                    'rent-from'    => 'Аренда от',
                    'rent-till'    => 'Аренда до',
                    'rent-type'    => 'Тип аренды',
                    'renting_type' => 'Тип аренды',
                    'special-note' => 'Особые пожелания/заметки',
                ],

                'per-ticket-price' => ':price За билет',
            ],

            'view'    => [
                'types'   => [
                    'booking' => [
                        'event-on'                 => 'Событие на',
                        'location'                 => 'Местоположение',
                        'slot-duration-in-minutes' => ':minutes Минут',
                        'slot-duration'            => 'Продолжительность слота',
                        'view-on-map'              => 'Посмотреть на карте',
                    ],
                ],

                'booking' => [
                    'appointment' => [
                        'closed'             => 'Закрыто',
                        'today-availability' => 'Доступность сегодня',
                    ],

                    'event'       => [
                        'book-your-ticket' => 'Забронируйте свой билет',
                    ],

                    'rental'      => [
                        'choose-rent-option' => 'Выберите вариант аренды',
                        'daily-basis'        => 'Ежедневно',
                        'from'               => 'От',
                        'hourly-basis'       => 'Почасовая оплата',
                        'rent-an-item'       => 'Арендовать предмет',
                        'select-date'        => 'Выбрать дату',
                        'select-rent-time'   => 'Выбрать время аренды',
                        'select-slot'        => 'Выбрать слот',
                        'slot'               => 'Слот',
                        'to'                 => 'К',
                    ],

                    'slots'       => [
                        'book-an-appointment' => 'Записаться на прием',
                        'date'                => 'Дата',
                        'no-slots-available'  => 'Нет доступных слотов',
                        'title'               => 'Слот',
                    ],

                    'table'       => [
                        'book-a-table'       => 'Забронировать стол',
                        'closed'             => 'Закрыто',
                        'slots-for-all-days' => 'Показать для всех дней',
                        'special-notes'      => 'Особые пожелания/заметки',
                        'today-availability' => 'Доступность сегодня',
                    ],
                ],
            ],
        ],
    ],
];
