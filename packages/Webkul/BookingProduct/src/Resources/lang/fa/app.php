<?php

return [
    'admin' => [
        'catalog' => [
            'products' => [
                'edit'  => [
                    'booking' => [
                        'appointment' => [
                            'break-duration'         => 'زمان استراحت بین بازه‌ها (دقیقه)',
                            'slot-duration'          => 'مدت زمان بازه (دقیقه)',

                            'same-slot-for-all-days' => [
                                'no'    => 'نه',
                                'title' => 'بازه یکسان برای همه روزها',
                                'yes'   => 'بله',
                            ],
                        ],

                        'default'     => [
                            'break-duration' => 'زمان استراحت بین بازه‌ها (دقیقه)',
                            'close'          => 'بستن',
                            'delete'         => 'حذف',
                            'description'    => 'اطلاعات رزرو',
                            'edit'           => 'ویرایش',
                            'many'           => 'بسیاری',

                            'modal'          => [
                                'slot' => [
                                    'close'      => 'بستن',
                                    'day'        => 'روز',
                                    'edit-title' => 'ویرایش بازه‌ها',
                                    'friday'     => 'جمعه',
                                    'from-day'   => 'از روز',
                                    'from'       => 'از',
                                    'monday'     => 'دوشنبه',
                                    'open'       => 'باز',
                                    'saturday'   => 'شنبه',
                                    'save'       => 'ذخیره بازه',
                                    'select'     => 'انتخاب',
                                    'status'     => 'وضعیت',
                                    'sunday'     => 'یکشنبه',
                                    'thursday'   => 'پنج‌شنبه',
                                    'time'       => 'زمان',
                                    'title'      => 'افزودن بازه‌ها',
                                    'to'         => 'تا',
                                    'tuesday'    => 'سه‌شنبه',
                                    'wednesday'  => 'چهارشنبه',
                                    'week'       => ':day',
                                ],
                            ],

                            'one'            => 'یکی',
                            'open'           => 'باز',
                            'slot-duration'  => 'مدت زمان بازه (دقیقه)',
                            'title'          => 'پیش‌فرض',
                        ],

                        'event'       => [
                            'add'                => 'افزودن بلیت‌ها',
                            'delete'             => 'حذف',
                            'description-info'   => 'هیچ بلیتی موجود نیست.',
                            'description'        => 'توضیحات',
                            'edit'               => 'ویرایش',

                            'modal'              => [
                                'ticket' => [
                                    'save' => 'ذخیره بلیت‌ها',
                                ],
                            ],

                            'name'               => 'نام',
                            'price'              => 'قیمت',
                            'qty'                => 'تعداد',
                            'special-price-from' => 'قیمت ویژه از',
                            'special-price-to'   => 'قیمت ویژه تا',
                            'special-price'      => 'قیمت ویژه',
                            'title'              => 'بلیت‌ها',
                        ],

                        'empty-info'  => [
                            'tickets' => [
                                'add' => 'افزودن بلیت‌ها',
                            ],

                            'slots'   => [
                                'add'         => 'افزودن بازه‌ها',
                                'delete'      => 'حذف',
                                'description' => 'بازه‌های موجود با مدت زمان.',
                            ],
                        ],

                        'rental'      => [
                            'daily_hourly'           => 'هر دو (روزانه و ساعتی)',
                            'daily-price'            => 'قیمت روزانه',
                            'daily'                  => 'روزانه',
                            'hourly-price'           => 'قیمت ساعتی',
                            'hourly'                 => 'ساعتی',

                            'same-slot-for-all-days' => [
                                'no'    => 'نه',
                                'title' => 'بازه یکسان برای همه روزها',
                                'yes'   => 'بله',
                            ],

                            'title'                  => 'نوع اجاره',
                        ],

                        'slots'       => [
                            'add'              => 'افزودن بازه‌ها',
                            'delete'           => 'حذف',
                            'description-info' => 'بازه‌های موجود با مدت زمان.',
                            'description'      => 'هیچ بازه‌ای موجود نیست.',
                            'edit'             => 'ویرایش',

                            'modal'            => [
                                'slot' => [
                                    'friday'     => 'جمعه',
                                    'from'       => 'از',
                                    'monday'     => 'دوشنبه',
                                    'saturday'   => 'شنبه',
                                    'sunday'     => 'یکشنبه',
                                    'thursday'   => 'پنج‌شنبه',
                                    'to'         => 'تا',
                                    'tuesday'    => 'سه‌شنبه',
                                    'wednesday'  => 'چهارشنبه',
                                ],
                            ],

                            'save'             => 'ذخیره',
                            'title'            => 'بازه‌ها',
                        ],

                        'table'       => [
                            'break-duration'            => 'زمان استراحت بین بازه‌ها (دقیقه)',

                            'charged-per'               => [
                                'guest'  => 'مهمان',
                                'table'  => 'میز',
                                'title'  => 'شارژ شده به ازای',
                            ],

                            'guest-capacity'            => 'ظرفیت مهمان',
                            'guest-limit'               => 'محدودیت مهمان در هر میز',
                            'prevent-scheduling-before' => 'جلوگیری از زمان‌بندی قبل از',
                            'slot-duration'             => 'مدت زمان بازه (دقیقه)',

                            'same-slot-for-all-days'    => [
                                'no'    => 'نه',
                                'title' => 'بازه یکسان برای همه روزها',
                                'yes'   => 'بله',
                            ],
                        ],
                    ],

                    'types'   => [
                        'booking' => [
                            'available-from'       => 'موجود از',
                            'available-to'         => 'موجود تا',

                            'available-every-week' => [
                                'no'    => 'نه',
                                'title' => 'هر هفته موجود است',
                                'yes'   => 'بله',
                            ],

                            'location'             => 'موقعیت',
                            'qty'                  => 'تعداد',

                            'type'                 => [
                                'appointment' => 'قرار ملاقات',
                                'default'     => 'پیش‌فرض',
                                'event'       => 'رویداد',
                                'many'        => 'بسیاری',
                                'one'         => 'یک',
                                'rental'      => 'اجاره',
                                'table'       => 'میز',
                                'title'       => 'نوع',
                            ],

                            'title'                => 'نوع رزرو',
                        ],
                    ],
                ],

                'index' => [
                    'booking' => 'رزرو',
                ],
            ],
        ],

        'sales'   => [
            'bookings' => [
                'index' => [
                    'datagrid' => [
                        'created-date' => 'تاریخ ایجاد',
                        'from'         => 'از',
                        'id'           => 'شناسه',
                        'order-id'     => 'شناسه سفارش',
                        'qty'          => 'تعداد',
                        'to'           => 'تا',
                    ],

                    'title' => 'محصول رزروها',
                ],

                'title' => 'محصول رزروها',
            ],
        ],
    ],

    'shop'  => [
        'products' => [
            'booking' => [
                'closed'           => 'بسته شده',

                'cart'             => [
                    'booking-from' => 'رزرو از',
                    'booking-till' => 'رزرو تا',
                    'daily'        => 'روزانه',
                    'event-from'   => 'رویداد از',
                    'event-ticket' => 'بلیط رویداد',
                    'event-till'   => 'رویداد تا',

                    'integrity'    => [
                        'missing_options'        => 'گزینه‌ها برای این محصول وجود ندارند.',
                        'select_hourly_duration' => 'یک مدت زمان شکاف از یک ساعت انتخاب کنید.',
                    ],

                    'rent-from'    => 'اجاره از',
                    'rent-till'    => 'اجاره تا',
                    'rent-type'    => 'نوع اجاره',
                    'renting_type' => 'نوع اجاره',
                    'special-note' => 'درخواست‌های ویژه/یادداشت‌ها',
                ],

                'per-ticket-price' => ':price در هر بلیط',
            ],

            'view' => [
                'types' => [
                    'booking' => [
                        'event-on'                 => 'رویداد در',
                        'location'                 => 'مکان',
                        'slot-duration-in-minutes' => ':minutes دقیقه',
                        'slot-duration'            => 'مدت زمان شکاف',
                        'view-on-map'              => 'نمایش در نقشه',
                    ],
                ],

                'booking' => [
                    'appointment' => [
                        'closed'             => 'بسته شده',
                        'today-availability' => 'دسترسی امروز',
                    ],

                    'event' => [
                        'book-your-ticket' => 'رزرو بلیط خود را',
                    ],

                    'rental' => [
                        'choose-rent-option' => 'انتخاب گزینه اجاره',
                        'daily-basis'        => 'بر اساس روزانه',
                        'from'               => 'از',
                        'hourly-basis'       => 'بر اساس ساعتی',
                        'rent-an-item'       => 'اجاره یک مورد',
                        'select-date'        => 'انتخاب تاریخ',
                        'select-rent-time'   => 'انتخاب زمان اجاره',
                        'select-slot'        => 'انتخاب شکاف',
                        'slot'               => 'شکاف',
                        'to'                 => 'تا',
                    ],

                    'slots' => [
                        'book-an-appointment' => 'رزرو یک جلسه',
                        'date'                => 'تاریخ',
                        'no-slots-available'  => 'هیچ شکافی در دسترس نیست',
                        'title'               => 'شکاف',
                    ],

                    'table' => [
                        'book-a-table'       => 'رزرو یک میز',
                        'closed'             => 'بسته شده',
                        'slots-for-all-days' => 'نمایش برای تمام روزها',
                        'special-notes'      => 'درخواست‌های ویژه/یادداشت‌ها',
                        'today-availability' => 'دسترسی امروز',
                    ],
                ],
            ],
        ],
    ],
];
