<?php

return [
    'admin' => [
        'catalog' => [
            'products' => [
                'edit'  => [
                    'booking' => [
                        'appointment' => [
                            'break-duration'         => 'स्लॉट्स के बीच ब्रेक समय (मिनट)',
                            'slot-duration'          => 'स्लॉट अवधि (मिनट)',

                            'same-slot-for-all-days' => [
                                'no'    => 'नहीं',
                                'title' => 'सभी दिनों के लिए समान स्लॉट',
                                'yes'   => 'हाँ',
                            ],
                        ],

                        'default'     => [
                            'break-duration' => 'स्लॉट्स के बीच ब्रेक समय (मिनट)',
                            'close'          => 'बंद करें',
                            'delete'         => 'हटाएं',
                            'description'    => 'बुकिंग सूचना',
                            'edit'           => 'संपादित करें',
                            'many'           => 'बहुत सारे',

                            'modal'          => [
                                'slot' => [
                                    'close'      => 'बंद करें',
                                    'day'        => 'दिन',
                                    'edit-title' => 'स्लॉट संपादित करें',
                                    'friday'     => 'शुक्रवार',
                                    'from-day'   => 'दिन से',
                                    'from'       => 'से',
                                    'monday'     => 'सोमवार',
                                    'open'       => 'खुला',
                                    'saturday'   => 'शनिवार',
                                    'save'       => 'स्लॉट सहेजें',
                                    'select'     => 'चुनें',
                                    'status'     => 'स्थिति',
                                    'sunday'     => 'रविवार',
                                    'thursday'   => 'गुरूवार',
                                    'time'       => 'समय',
                                    'title'      => 'स्लॉट जोड़ें',
                                    'to'         => 'तक',
                                    'tuesday'    => 'मंगलवार',
                                    'wednesday'  => 'बुधवार',
                                    'week'       => ':day',
                                ],
                            ],

                            'one'            => 'एक',
                            'open'           => 'खुला',
                            'slot-duration'  => 'स्लॉट अवधि (मिनट)',
                            'title'          => 'डिफ़ॉल्ट',
                        ],

                        'event'       => [
                            'add'                => 'टिकट जोड़ें',
                            'delete'             => 'हटाएं',
                            'description-info'   => 'कोई टिकट उपलब्ध नहीं है।',
                            'description'        => 'विवरण',
                            'edit'               => 'संपादित करें',

                            'modal'              => [
                                'ticket' => [
                                    'save' => 'टिकट सहेजें',
                                ],
                            ],

                            'name'               => 'नाम',
                            'price'              => 'मूल्य',
                            'qty'                => 'मात्रा',
                            'special-price-from' => 'विशेष मूल्य से',
                            'special-price-to'   => 'विशेष मूल्य तक',
                            'special-price'      => 'विशेष मूल्य',
                            'title'              => 'टिकट',
                        ],

                        'empty-info'  => [
                            'tickets' => [
                                'add' => 'टिकट जोड़ें',
                            ],

                            'slots'   => [
                                'add'         => 'स्लॉट जोड़ें',
                                'delete'      => 'हटाएं',
                                'description' => 'समय अवधि के साथ उपलब्ध स्लॉट।',
                            ],
                        ],

                        'rental'      => [
                            'daily_hourly'           => 'दोनों (दैनिक और प्रति घंटा आधार)',
                            'daily-price'            => 'दैनिक मूल्य',
                            'daily'                  => 'दैनिक आधार',
                            'hourly-price'           => 'प्रति घंटा मूल्य',
                            'hourly'                 => 'प्रति घंटा आधार',

                            'same-slot-for-all-days' => [
                                'no'    => 'नहीं',
                                'title' => 'सभी दिनों के लिए समान स्लॉट',
                                'yes'   => 'हाँ',
                            ],

                            'title'                  => 'किराया प्रकार',
                        ],

                        'slots'       => [
                            'add'              => 'स्लॉट जोड़ें',
                            'delete'           => 'हटाएं',
                            'description-info' => 'समय अवधि के साथ उपलब्ध स्लॉट।',
                            'description'      => 'कोई स्लॉट उपलब्ध नहीं है।',
                            'edit'             => 'संपादित करें',

                            'modal'            => [
                                'slot' => [
                                    'friday'     => 'शुक्रवार',
                                    'from'       => 'से',
                                    'monday'     => 'सोमवार',
                                    'saturday'   => 'शनिवार',
                                    'sunday'     => 'रविवार',
                                    'thursday'   => 'गुरूवार',
                                    'to'         => 'तक',
                                    'tuesday'    => 'मंगलवार',
                                    'wednesday'  => 'बुधवार',
                                ],
                            ],

                            'save'             => 'सहेजें',
                            'title'            => 'स्लॉट्स',
                        ],

                        'table'       => [
                            'break-duration'            => 'स्लॉट्स के बीच ब्रेक समय (मिनट)',

                            'charged-per'               => [
                                'guest'  => 'मेहमान',
                                'table'  => 'तालिका',
                                'title'  => 'प्रति शुल्क',
                            ],

                            'guest-capacity'            => 'मेहमान क्षमता',
                            'guest-limit'               => 'प्रति तालिका मेहमान सीमा',
                            'prevent-scheduling-before' => 'पहले निर्धारित करें',
                            'slot-duration'             => 'स्लॉट अवधि (मिनट)',

                            'same-slot-for-all-days'    => [
                                'no'    => 'नहीं',
                                'title' => 'सभी दिनों के लिए समान स्लॉट',
                                'yes'   => 'हाँ',
                            ],
                        ],
                    ],

                    'types'   => [
                        'booking' => [
                            'available-from' => 'यहाँ से उपलब्ध',
                            'available-to'   => 'यहाँ तक उपलब्ध',

                            'available-every-week'      => [
                                'no'    => 'नहीं',
                                'title' => 'हर हफ्ते उपलब्ध',
                                'yes'   => 'हाँ',
                            ],

                            'location' => 'स्थान',
                            'qty'      => 'मात्रा',

                            'type' => [
                                'appointment' => 'अपॉइंटमेंट',
                                'default'     => 'डिफ़ॉल्ट',
                                'event'       => 'इवेंट',
                                'many'        => 'बहुत',
                                'one'         => 'एक',
                                'rental'      => 'किराया',
                                'table'       => 'तालिका',
                                'title'       => 'प्रकार',
                            ],

                            'title' => 'बुकिंग प्रकार',
                        ],
                    ],
                ],

                'index' => [
                    'booking' => 'बुकिंग',
                ],
            ],
        ],

        'sales'   => [
            'bookings' => [
                'index' => [
                    'datagrid' => [
                        'created-date' => 'बनाया गया तिथि',
                        'from'         => 'से',
                        'id'           => 'आईडी',
                        'order-id'     => 'आदेश आईडी',
                        'qty'          => 'मात्रा',
                        'to'           => 'तक',
                    ],

                    'title'    => 'बुकिंग्स प्रोडक्ट',
                ],

                'title' => 'बुकिंग्स प्रोडक्ट',
            ],
        ],
    ],

    'shop'  => [
        'products' => [
            'booking' => [
                'closed'           => 'बंद',

                'cart'             => [
                    'booking-from' => 'बुकिंग से',
                    'booking-till' => 'बुकिंग तक',
                    'daily'        => 'दैनिक',
                    'event-from'   => 'इवेंट से',
                    'event-ticket' => 'इवेंट टिकट',
                    'event-till'   => 'इवेंट तक',

                    'integrity'    => [
                        'missing_options'        => 'इस प्रोडक्ट के लिए विकल्प गायब हैं।',
                        'select_hourly_duration' => 'एक घंटे की स्लॉट अवधि का चयन करें।',
                    ],

                    'rent-from'    => 'किराया से',
                    'rent-till'    => 'किराया तक',
                    'rent-type'    => 'किराया प्रकार',
                    'renting_type' => 'किराया प्रकार',
                    'special-note' => 'विशेष अनुरोध/नोट्स',
                ],

                'per-ticket-price' => ':price प्रति टिकट',
            ],

            'view'    => [
                'types'   => [
                    'booking' => [
                        'event-on'                 => 'इवेंट पर',
                        'location'                 => 'स्थान',
                        'slot-duration-in-minutes' => ':minutes मिनट',
                        'slot-duration'            => 'स्लॉट अवधि',
                        'view-on-map'              => 'नक्शे पर देखें',
                    ],
                ],

                'booking' => [
                    'appointment' => [
                        'closed'             => 'बंद',
                        'today-availability' => 'आज की उपलब्धता',
                    ],

                    'event'       => [
                        'book-your-ticket' => 'अपना टिकट बुक करें',
                    ],

                    'rental'      => [
                        'choose-rent-option' => 'किराया विकल्प चुनें',
                        'daily-basis'        => 'दैनिक आधार',
                        'from'               => 'से',
                        'hourly-basis'       => 'प्रति घंटा आधार',
                        'rent-an-item'       => 'वस्त्र किराए पर लें',
                        'select-date'        => 'तारीख चुनें',
                        'select-rent-time'   => 'किराया समय चुनें',
                        'select-slot'        => 'स्लॉट चुनें',
                        'slot'               => 'स्लॉट',
                        'to'                 => 'तक',
                    ],

                    'slots'       => [
                        'book-an-appointment' => 'अपॉइंटमेंट बुक करें',
                        'date'                => 'तारीख',
                        'no-slots-available'  => 'कोई स्लॉट उपलब्ध नहीं है',
                        'title'               => 'स्लॉट',
                    ],

                    'table'       => [
                        'book-a-table'       => 'तालिका बुक करें',
                        'closed'             => 'बंद',
                        'slots-for-all-days' => 'सभी दिनों के लिए दिखाएं',
                        'special-notes'      => 'विशेष अनुरोध/नोट्स',
                        'today-availability' => 'आज की उपलब्धता',
                    ],
                ],
            ],
        ],
    ],
];
