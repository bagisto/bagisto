<?php

return [
    'admin' => [
        'catalog' => [
            'products' => [
                'edit'  => [
                    'booking' => [
                        'appointment' => [
                            'break-duration'         => 'זמן המנוחה בין המקטעים (דקות)',
                            'slot-duration'          => 'משך המקטע (דקות)',

                            'same-slot-for-all-days' => [
                                'no'    => 'לא',
                                'title' => 'אותו מקטע לכל הימים',
                                'yes'   => 'כן',
                            ],
                        ],

                        'default'     => [
                            'break-duration' => 'זמן המנוחה בין המקטעים (דקות)',
                            'close'          => 'סגור',
                            'delete'         => 'מחק',
                            'description'    => 'מידע על ההזמנה',
                            'edit'           => 'ערוך',
                            'many'           => 'הרבה',

                            'modal'          => [
                                'slot' => [
                                    'close'      => 'סגור',
                                    'day'        => 'יום',
                                    'edit-title' => 'ערוך מקטעים',
                                    'friday'     => 'יום שישי',
                                    'from-day'   => 'מיום',
                                    'from'       => 'מתוך',
                                    'monday'     => 'יום שני',
                                    'open'       => 'פתח',
                                    'saturday'   => 'יום שבת',
                                    'save'       => 'שמור מקטע',
                                    'select'     => 'בחר',
                                    'status'     => 'מצב',
                                    'sunday'     => 'יום ראשון',
                                    'thursday'   => 'יום חמישי',
                                    'time'       => 'זמן',
                                    'title'      => 'הוסף מקטעים',
                                    'to'         => 'אל',
                                    'tuesday'    => 'יום שלישי',
                                    'wednesday'  => 'יום רביעי',
                                    'week'       => ':יום',
                                ],
                            ],

                            'one'            => 'אחד',
                            'open'           => 'פתוח',
                            'slot-duration'  => 'משך המקטע (דקות)',
                            'title'          => 'ברירת מחדל',
                        ],

                        'event'       => [
                            'add'                => 'הוסף כרטיסים',
                            'delete'             => 'מחק',
                            'description-info'   => 'אין כרטיסים זמינים.',
                            'description'        => 'תיאור',
                            'edit'               => 'ערוך',

                            'modal'              => [
                                'ticket' => [
                                    'save' => 'שמור כרטיסים',
                                ],
                            ],

                            'name'               => 'שם',
                            'price'              => 'מחיר',
                            'qty'                => 'כמות',
                            'special-price-from' => 'מחיר מיוחד מתאריך',
                            'special-price-to'   => 'מחיר מיוחד עד',
                            'special-price'      => 'מחיר מיוחד',
                            'title'              => 'כרטיסים',
                        ],

                        'empty-info'  => [
                            'tickets' => [
                                'add' => 'הוסף כרטיסים',
                            ],

                            'slots'   => [
                                'add'         => 'הוסף מקטעים',
                                'delete'      => 'מחק',
                                'description' => 'מקטעים זמינים עם משך זמן.',
                            ],
                        ],

                        'rental'      => [
                            'daily_hourly'           => 'שניים (יומי ולפי שעות)',
                            'daily-price'            => 'מחיר יומי',
                            'daily'                  => 'מדי יום',
                            'hourly-price'           => 'מחיר לשעה',
                            'hourly'                 => 'לפי שעות',

                            'same-slot-for-all-days' => [
                                'no'    => 'לא',
                                'title' => 'אותו מקטע לכל הימים',
                                'yes'   => 'כן',
                            ],

                            'title'                  => 'סוג השכרה',
                        ],

                        'slots'       => [
                            'add'              => 'הוסף מקטעים',
                            'delete'           => 'מחק',
                            'description-info' => 'מקטעים זמינים עם משך זמן.',
                            'description'      => 'אין מקטעים זמינים.',
                            'edit'             => 'ערוך',

                            'modal'            => [
                                'slot' => [
                                    'friday'     => 'יום שישי',
                                    'from'       => 'מתוך',
                                    'monday'     => 'יום שני',
                                    'saturday'   => 'יום שבת',
                                    'sunday'     => 'יום ראשון',
                                    'thursday'   => 'יום חמישי',
                                    'to'         => 'אל',
                                    'tuesday'    => 'יום שלישי',
                                    'wednesday'  => 'יום רביעי',
                                ],
                            ],

                            'save'             => 'שמור',
                            'title'            => 'מקטעים',
                        ],

                        'table'       => [
                            'break-duration'            => 'זמן המנוחה בין המקטעים (דקות)',

                            'charged-per'               => [
                                'guest'  => 'אורח',
                                'table'  => 'שולחן',
                                'title'  => 'מחויב ל',
                            ],

                            'guest-capacity'            => 'קיבולת אורחים',
                            'guest-limit'               => 'הגבלת אורחים לשולחן',
                            'prevent-scheduling-before' => 'מנע קביעת תור לפני',
                            'slot-duration'             => 'משך המקטע (דקות)',

                            'same-slot-for-all-days'    => [
                                'no'    => 'לא',
                                'title' => 'אותו מקטע לכל הימים',
                                'yes'   => 'כן',
                            ],
                        ],
                    ],

                    'types'   => [
                        'booking' => [
                            'available-from'       => 'זמין מתאריך',
                            'available-to'         => 'זמין עד',

                            'available-every-week' => [
                                'no'    => 'לא',
                                'title' => 'זמין כל שבוע',
                                'yes'   => 'כן',
                            ],

                            'location'             => 'מיקום',
                            'qty'                  => 'כמות',

                            'type'                 => [
                                'appointment' => 'פגישה',
                                'default'     => 'ברירת מחדל',
                                'event'       => 'אירוע',
                                'many'        => 'הרבה',
                                'one'         => 'אחד',
                                'rental'      => 'השכרה',
                                'table'       => 'שולחן',
                                'title'       => 'סוג',
                            ],

                            'title'                => 'סוג ההזמנה',
                        ],
                    ],
                ],

                'index' => [
                    'booking' => 'הזמנה',
                ],
            ],
        ],

        'sales'   => [
            'bookings' => [
                'index' => [
                    'datagrid' => [
                        'created-date' => 'תאריך יצירה',
                        'from'         => 'מ',
                        'id'           => 'מספר זיהוי',
                        'order-id'     => 'מספר הזמנה',
                        'qty'          => 'כמות',
                        'to'           => 'ל',
                    ],

                    'title'    => 'מוצרי הזמנה',
                ],

                'title' => 'מוצרי הזמנה',
            ],
        ],
    ],

    'shop' => [
        'products' => [
            'booking' => [
                'closed'           => 'סגור',

                'cart'             => [
                    'booking-from' => 'הזמנה מתאריך',
                    'booking-till' => 'הזמנה עד',
                    'daily'        => 'יומי',
                    'event-from'   => 'אירוע מתאריך',
                    'event-ticket' => 'כרטיס לאירוע',
                    'event-till'   => 'אירוע עד',

                    'integrity'    => [
                        'missing_options'        => 'אפשרויות חסרות עבור מוצר זה.',
                        'select_hourly_duration' => 'בחר משך זמן של שעה אחת.',
                    ],

                    'rent-from'    => 'השכר מתאריך',
                    'rent-till'    => 'השכר עד',
                    'rent-type'    => 'סוג השכרה',
                    'renting_type' => 'סוג השכרה',
                    'special-note' => 'בקשות מיוחדות/הערות',
                ],

                'per-ticket-price' => ':price לכרטיס',
            ],

            'view'    => [
                'types' => [
                    'booking' => [
                        'event-on'                 => 'אירוע בתאריך',
                        'location'                 => 'מיקום',
                        'slot-duration-in-minutes' => ':minutes דקות',
                        'slot-duration'            => 'משך המקטע',
                        'view-on-map'              => 'צפה במפה',
                    ],
                ],

                'booking' => [
                    'appointment' => [
                        'closed'             => 'סגור',
                        'today-availability' => 'זמינות היום',
                    ],

                    'event' => [
                        'book-your-ticket' => 'הזמן כרטיס',
                    ],

                    'rental' => [
                        'choose-rent-option'       => 'בחר אפשרות השכרה',
                        'daily-basis'              => 'על בסיס יומי',
                        'from'                     => 'מתאריך',
                        'hourly-basis'             => 'על בסיס שעתי',
                        'rent-an-item'             => 'השכר פריט',
                        'select-date'              => 'בחר תאריך',
                        'select-rent-time'         => 'בחר זמן השכרה',
                        'select-slot'              => 'בחר מקטע',
                        'slot'                     => 'מקטע',
                        'to'                       => 'עד',
                    ],

                    'slots' => [
                        'book-an-appointment'       => 'קבע פגישה',
                        'date'                      => 'תאריך',
                        'no-slots-available'        => 'אין מקטעים זמינים',
                        'title'                     => 'מקטע',
                    ],

                    'table' => [
                        'book-a-table'             => 'הזמן שולחן',
                        'closed'                   => 'סגור',
                        'slots-for-all-days'       => 'הצג לכל הימים',
                        'special-notes'            => 'בקשות מיוחדות/הערות',
                        'today-availability'       => 'זמינות היום',
                    ],
                ],
            ],
        ],
    ],
];
