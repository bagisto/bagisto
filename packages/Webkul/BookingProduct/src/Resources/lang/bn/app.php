<?php

return [
    'admin' => [
        'catalog' => [
            'products' => [
                'edit'  => [
                    'booking' => [
                        'appointment' => [
                            'break-duration'         => 'স্লটের মধ্যে বিরতি সময় (মিনিট)',
                            'slot-duration'          => 'স্লট সময়কাল (মিনিট)',

                            'same-slot-for-all-days' => [
                                'no'    => 'না',
                                'title' => 'সমস্ত দিনের জন্য একই স্লট',
                                'yes'   => 'হ্যাঁ',
                            ],
                        ],

                        'default'     => [
                            'break-duration' => 'স্লটের মধ্যে বিরতি সময় (মিনিট)',
                            'close'          => 'বন্ধ',
                            'delete'         => 'মুছে ফেলুন',
                            'description'    => 'বুকিং তথ্য',
                            'edit'           => 'সম্পাদনা করুন',
                            'many'           => 'অনেক',

                            'modal'          => [
                                'slot' => [
                                    'close'      => 'বন্ধ',
                                    'day'        => 'দিন',
                                    'edit-title' => 'স্লট সম্পাদনা করুন',
                                    'friday'     => 'শুক্রবার',
                                    'from-day'   => 'থেকে দিন',
                                    'from'       => 'থেকে',
                                    'monday'     => 'সোমবার',
                                    'open'       => 'খোলা',
                                    'saturday'   => 'শনিবার',
                                    'save'       => 'স্লট সংরক্ষণ করুন',
                                    'select'     => 'নির্বাচন করুন',
                                    'status'     => 'অবস্থা',
                                    'sunday'     => 'রবিবার',
                                    'thursday'   => 'বৃহস্পতিবার',
                                    'time'       => 'সময়',
                                    'title'      => 'স্লট যোগ করুন',
                                    'to'         => 'পর্যন্ত',
                                    'tuesday'    => 'মঙ্গলবার',
                                    'wednesday'  => 'বুধবার',
                                    'week'       => ':day',
                                ],
                            ],

                            'one'            => 'এক',
                            'open'           => 'খোলা',
                            'slot-duration'  => 'স্লট সময়কাল (মিনিট)',
                            'title'          => 'ডিফল্ট',
                        ],

                        'event'       => [
                            'add'                => 'টিকেট যোগ করুন',
                            'delete'             => 'মুছে ফেলুন',
                            'description-info'   => 'কোন টিকেট উপলব্ধ নেই।',
                            'description'        => 'বর্ণনা',
                            'edit'               => 'সম্পাদনা করুন',

                            'modal' => [
                                'ticket' => [
                                    'save' => 'টিকেট সংরক্ষণ করুন',
                                ],
                            ],

                            'name'               => 'নাম',
                            'price'              => 'মূল্য',
                            'qty'                => 'পরিমাণ',
                            'special-price-from' => 'বিশেষ মূল্য থেকে',
                            'special-price-to'   => 'বিশেষ মূল্য পর্যন্ত',
                            'special-price'      => 'বিশেষ মূল্য',
                            'title'              => 'টিকেট',
                        ],

                        'empty-info'  => [
                            'tickets' => [
                                'add' => 'টিকেট যোগ করুন',
                            ],

                            'slots' => [
                                'add'         => 'স্লট যোগ করুন',
                                'delete'      => 'মুছে ফেলুন',
                                'description' => 'সময় মেয়াদ সহ উপলব্ধ স্লট।',
                            ],
                        ],

                        'rental'      => [
                            'daily_hourly'           => 'দৈনিক এবং প্রতি ঘণ্টায় উভয়',
                            'daily-price'            => 'দৈনিক মূল্য',
                            'daily'                  => 'দৈনিক ভিত্তিতে',
                            'hourly-price'           => 'প্রতি ঘণ্টার মূল্য',
                            'hourly'                 => 'প্রতি ঘণ্টা ভিত্তিতে',

                            'same-slot-for-all-days' => [
                                'no'    => 'না',
                                'title' => 'সমস্ত দিনের জন্য একই স্লট',
                                'yes'   => 'হ্যাঁ',
                            ],

                            'title' => 'ভাড়ার ধরণ',
                        ],

                        'slots'       => [
                            'add'              => 'স্লট যোগ করুন',
                            'delete'           => 'মুছে ফেলুন',
                            'description-info' => 'সময় মেয়াদ সহ উপলব্ধ স্লট।',
                            'description'      => 'কোন স্লট উপলব্ধ নেই।',
                            'edit'             => 'সম্পাদনা করুন',

                            'modal' => [
                                'slot' => [
                                    'friday'     => 'শুক্রবার',
                                    'from'       => 'থেকে',
                                    'monday'     => 'সোমবার',
                                    'saturday'   => 'শনিবার',
                                    'sunday'     => 'রবিবার',
                                    'thursday'   => 'বৃহস্পতিবার',
                                    'to'         => 'পর্যন্ত',
                                    'tuesday'    => 'মঙ্গলবার',
                                    'wednesday'  => 'বুধবার',
                                ],
                            ],

                            'save'  => 'সংরক্ষণ করুন',
                            'title' => 'স্লট',
                        ],

                        'table'       => [
                            'break-duration'            => 'স্লটের মধ্যে বিরতি সময় (মিনিট)',

                            'charged-per'               => [
                                'guest' => 'অতিথি',
                                'table' => 'টেবিল',
                                'title' => 'প্রতি চার্জ',
                            ],

                            'guest-capacity'            => 'অতিথি ধারণক্ষমতা',
                            'guest-limit'               => 'প্রতি টেবিলে অতিথির সীমা',
                            'prevent-scheduling-before' => 'পূর্বে শিডিউলিং প্রতিরোধ করুন',
                            'slot-duration'             => 'স্লট সময়কাল (মিনিট)',

                            'same-slot-for-all-days'    => [
                                'no'    => 'না',
                                'title' => 'সমস্ত দিনের জন্য একই স্লট',
                                'yes'   => 'হ্যাঁ',
                            ],
                        ],
                    ],

                    'types'   => [
                        'booking' => [
                            'available-from'       => 'থেকে উপলব্ধ',
                            'available-to'         => 'পর্যন্ত উপলব্ধ',

                            'available-every-week' => [
                                'no'    => 'না',
                                'title' => 'প্রতি সপ্তাহে উপলব্ধ',
                                'yes'   => 'হ্যাঁ',
                            ],

                            'location'             => 'লোকেশন',
                            'qty'                  => 'পরিমাণ',

                            'type'                 => [
                                'appointment' => 'অ্যাপয়েন্টমেন্ট',
                                'default'     => 'ডিফল্ট',
                                'event'       => 'ইভেন্ট',
                                'many'        => 'বহু',
                                'one'         => 'এক',
                                'rental'      => 'ভাড়া',
                                'table'       => 'টেবিল',
                                'title'       => 'ধরণ',
                            ],

                            'title'                => 'বুকিং প্রকার',
                        ],
                    ],
                ],

                'index' => [
                    'booking' => 'বুকিং',
                ],
            ],
        ],

        'sales'   => [
            'bookings' => [
                'index' => [
                    'datagrid' => [
                        'created-date' => 'তৈরির তারিখ',
                        'from'         => 'থেকে',
                        'id'           => 'আইডি',
                        'order-id'     => 'অর্ডার আইডি',
                        'qty'          => 'পরিমাণ',
                        'to'           => 'পর্যন্ত',
                    ],

                    'title'    => 'বুকিং প্রোডাক্ট',
                ],

                'title' => 'বুকিং প্রোডাক্ট',
            ],
        ],
    ],

    'shop'  => [
        'products' => [
            'booking' => [
                'closed' => 'বন্ধ',

                'cart' => [
                    'booking-from' => 'থেকে বুকিং',
                    'booking-till' => 'পর্যন্ত বুকিং',
                    'daily'        => 'দৈনিক',
                    'event-from'   => 'ইভেন্ট থেকে',
                    'event-ticket' => 'ইভেন্ট টিকিট',
                    'event-till'   => 'ইভেন্ট পর্যন্ত',

                    'integrity'    => [
                        'missing_options'        => 'এই পণ্যের জন্য অপশন অনুপস্থিত।',
                        'select_hourly_duration' => 'এক ঘন্টা মেয়াদের স্লট নির্বাচন করুন।',
                    ],

                    'rent-from'    => 'থেকে ভাড়া',
                    'rent-till'    => 'পর্যন্ত ভাড়া',
                    'rent-type'    => 'ভাড়ার ধরণ',
                    'renting_type' => 'ভাড়ার ধরণ',
                    'special-note' => 'বিশেষ অনুরোধ/নোট',
                ],

                'per-ticket-price' => ':price প্রতি টিকিট',
            ],

            'view'    => [
                'types'   => [
                    'booking' => [
                        'event-on'                 => 'ইভেন্ট অন',
                        'location'                 => 'লোকেশন',
                        'slot-duration-in-minutes' => ':minutes মিনিট',
                        'slot-duration'            => 'স্লট সময়কাল',
                        'view-on-map'              => 'মানচিত্রে দেখুন',
                    ],
                ],

                'booking' => [
                    'appointment' => [
                        'closed'             => 'বন্ধ',
                        'today-availability' => 'আজকের উপলব্ধতা',
                    ],

                    'event'       => [
                        'book-your-ticket' => 'আপনার টিকিট বুক করুন',
                    ],

                    'rental'      => [
                        'choose-rent-option'       => 'ভাড়ার অপশন নির্বাচন করুন',
                        'daily-basis'              => 'দৈনিক ভিত্তিতে',
                        'from'                     => 'থেকে',
                        'hourly-basis'             => 'প্রতি ঘন্টায়',
                        'rent-an-item'             => 'একটি আইটেম ভাড়া নিন',
                        'select-date'              => 'তারিখ নির্বাচন করুন',
                        'select-rent-time'         => 'ভাড়ার সময় নির্বাচন করুন',
                        'select-slot'              => 'স্লট নির্বাচন করুন',
                        'slot'                     => 'স্লট',
                        'to'                       => 'পর্যন্ত',
                    ],

                    'slots'       => [
                        'book-an-appointment' => 'একটি অ্যাপয়েন্টমেন্ট বুক করুন',
                        'date'                => 'তারিখ',
                        'no-slots-available'  => 'কোনও স্লট উপলব্ধ নেই',
                        'title'               => 'স্লট',
                    ],

                    'table'       => [
                        'book-a-table'       => 'একটি টেবিল বুক করুন',
                        'closed'             => 'বন্ধ',
                        'slots-for-all-days' => 'সমস্ত দিনের জন্য দেখান',
                        'special-notes'      => 'বিশেষ অনুরোধ/নোট',
                        'today-availability' => 'আজকের উপলব্ধতা',
                    ],
                ],
            ],
        ],
    ],
];
