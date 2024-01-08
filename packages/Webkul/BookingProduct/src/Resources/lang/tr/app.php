<?php

return [
    'admin' => [
        'catalog' => [
            'products' => [
                'edit' => [
                    'booking' => [
                        'appointment' => [
                            'break-duration'         => 'Slotlar Arası Ara Süresi (Dakika)',
                            'slot-duration'          => 'Slot Süresi (Dakika)',

                            'same-slot-for-all-days' => [
                                'no'    => 'Hayır',
                                'title' => 'Tüm Günler için Aynı Slot',
                                'yes'   => 'Evet',
                            ],
                        ],

                        'default'     => [
                            'break-duration' => 'Slotlar Arası Ara Süresi (Dakika)',
                            'close'          => 'Kapat',
                            'delete'         => 'Sil',
                            'description'    => 'Rezervasyon Bilgisi',
                            'edit'           => 'Düzenle',
                            'many'           => 'Çoklu',

                            'modal'          => [
                                'slot' => [
                                    'close'      => 'Kapat',
                                    'day'        => 'Gün',
                                    'edit-title' => 'Slotları Düzenle',
                                    'friday'     => 'Cuma',
                                    'from-day'   => 'Başlangıç Günü',
                                    'from'       => 'Başlangıç',
                                    'monday'     => 'Pazartesi',
                                    'open'       => 'Açık',
                                    'saturday'   => 'Cumartesi',
                                    'save'       => 'Slotu Kaydet',
                                    'select'     => 'Seç',
                                    'status'     => 'Durum',
                                    'sunday'     => 'Pazar',
                                    'thursday'   => 'Perşembe',
                                    'time'       => 'Saat',
                                    'title'      => 'Slot Ekle',
                                    'to'         => 'Bitiş',
                                    'tuesday'    => 'Salı',
                                    'wednesday'  => 'Çarşamba',
                                    'week'       => ':day',
                                ],
                            ],

                            'one'            => 'Bir',
                            'open'           => 'Açık',
                            'slot-duration'  => 'Slot Süresi (Dakika)',
                            'title'          => 'Varsayılan',
                        ],

                        'event'       => [
                            'add'                => 'Bilet Ekle',
                            'delete'             => 'Sil',
                            'description-info'   => 'Mevcut bilet yok.',
                            'description'        => 'Açıklama',
                            'edit'               => 'Düzenle',

                            'modal'              => [
                                'ticket' => [
                                    'save' => 'Biletleri Kaydet',
                                ],
                            ],

                            'name'               => 'Ad',
                            'price'              => 'Fiyat',
                            'qty'                => 'Miktar',
                            'special-price-from' => 'Özel Fiyat Başlangıcı',
                            'special-price-to'   => 'Özel Fiyat Bitişi',
                            'special-price'      => 'Özel Fiyat',
                            'title'              => 'Biletler',
                        ],

                        'empty-info'  => [
                            'tickets' => [
                                'add' => 'Bilet Ekle',
                            ],

                            'slots' => [
                                'add'         => 'Slot Ekle',
                                'delete'      => 'Sil',
                                'description' => 'Zaman Süresi ile Mevcut Slotlar.',
                            ],
                        ],

                        'rental'      => [
                            'daily_hourly'           => 'Her İkisi (Günlük ve Saatlik Bazda)',
                            'daily-price'            => 'Günlük Fiyat',
                            'daily'                  => 'Günlük Bazda',
                            'hourly-price'           => 'Saatlik Fiyat',
                            'hourly'                 => 'Saatlik Bazda',

                            'same-slot-for-all-days' => [
                                'no'    => 'Hayır',
                                'title' => 'Tüm Günler için Aynı Slot',
                                'yes'   => 'Evet',
                            ],

                            'title'                  => 'Kiralama Türü',
                        ],

                        'slots'       => [
                            'add'              => 'Slot Ekle',
                            'delete'           => 'Sil',
                            'description-info' => 'Zaman Süresi ile Mevcut Slotlar.',
                            'description'      => 'Mevcut slot yok.',
                            'edit'             => 'Düzenle',

                            'modal'            => [
                                'slot' => [
                                    'friday'    => 'Cuma',
                                    'from'      => 'Başlangıç',
                                    'monday'    => 'Pazartesi',
                                    'saturday'  => 'Cumartesi',
                                    'sunday'    => 'Pazar',
                                    'thursday'  => 'Perşembe',
                                    'to'        => 'Bitiş',
                                    'tuesday'   => 'Salı',
                                    'wednesday' => 'Çarşamba',
                                ],
                            ],

                            'save'             => 'Kaydet',
                            'title'            => 'Slotlar',
                        ],

                        'table'       => [
                            'break-duration'            => 'Slotlar Arası Ara Süresi (Dakika)',

                            'charged-per'               => [
                                'guest' => 'Misafir',
                                'table' => 'Masa',
                                'title' => 'Başına Ücret Alınan',
                            ],

                            'guest-capacity'            => 'Misafir Kapasitesi',
                            'guest-limit'               => 'Masa Başına Misafir Limiti',
                            'prevent-scheduling-before' => 'Önceki Zamanlama Önleniyor',
                            'slot-duration'             => 'Slot Süresi (Dakika)',

                            'same-slot-for-all-days'    => [
                                'no'    => 'Hayır',
                                'title' => 'Tüm Günler için Aynı Slot',
                                'yes'   => 'Evet',
                            ],
                        ],

                        'types'       => [
                            'booking' => [
                                'available-from'       => 'Şuradan Mevcut',
                                'available-to'         => 'Şuraya Kadar Mevcut',

                                'available-every-week' => [
                                    'no'    => 'Hayır',
                                    'title' => 'Her Hafta Mevcut',
                                    'yes'   => 'Evet',
                                ],

                                'location'             => 'Konum',
                                'qty'                  => 'Adet',

                                'type'                 => [
                                    'appointment' => 'Randevu',
                                    'default'     => 'Varsayılan',
                                    'event'       => 'Etkinlik',
                                    'many'        => 'Çoklu',
                                    'one'         => 'Bir',
                                    'rental'      => 'Kiralama',
                                    'table'       => 'Masa',
                                    'title'       => 'Tür',
                                ],

                                'title'                => 'Rezervasyon Türü',
                            ],
                        ],

                        'index'       => [
                            'booking' => 'Rezervasyon',
                        ],
                    ],
                ],
            ],
        ],

        'sales'   => [
            'bookings' => [
                'index' => [
                    'datagrid' => [
                        'created-date' => 'Oluşturulma Tarihi',
                        'from'         => 'Başlangıç',
                        'id'           => 'ID',
                        'order-id'     => 'Sipariş ID',
                        'qty'          => 'MKT',
                        'to'           => 'Bitiş',
                    ],

                    'title'    => 'Rezervasyon Ürünü',
                ],

                'title' => 'Rezervasyon Ürünü',
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
                        'choose-rent-option' => 'Choose Rent Option',
                        'daily-basis'        => 'Daily Basis',
                        'from'               => 'From',
                        'hourly-basis'       => 'Hourly Basis',
                        'rent-an-item'       => 'Rent an Item',
                        'select-date'        => 'Select date',
                        'select-rent-time'   => 'Select Rent Time',
                        'select-slot'        => 'Select Slot',
                        'slot'               => 'Slot',
                        'to'                 => 'To',
                    ],

                    'slots'       => [
                        'book-an-appointment' => 'Book an Appointment',
                        'date'                => 'Date',
                        'no-slots-available'  => 'No slots available',
                        'title'               => 'Slot',
                    ],

                    'table'       => [
                        'book-a-table'       => 'Book a Table',
                        'closed'             => 'Closed',
                        'slots-for-all-days' => 'Show for all days',
                        'special-notes'      => 'Special Request/Notes',
                        'today-availability' => 'Today Availability',
                    ],
                ],
            ],
        ],
    ],
];
