<?php

return [
    'admin' => [
        'catalog' => [
            'products' => [
                'edit'  => [
                    'booking' => [
                        'appointment' => [
                            'break-duration'         => 'කාල කාලය අතර (මිනිත්තු)',
                            'slot-duration'          => 'ස්ලොට් කාලය (මිනිත්තු)',

                            'same-slot-for-all-days' => [
                                'no'    => 'නෑ',
                                'title' => 'සියල්ල දින සඳහා එකක් ස්ලොට්',
                                'yes'   => 'ඔව්',
                            ],
                        ],

                        'default'     => [
                            'break-duration' => 'කාල කාලය අතර (මිනිත්තු)',
                            'close'          => 'වසා දමන්න',
                            'delete'         => 'මකන්න',
                            'description'    => 'ක්‍රියාවලිය තොරතුරු',
                            'edit'           => 'සංස්කරණය කරන්න',
                            'many'           => 'බොහෝ',

                            'modal'          => [
                                'slot' => [
                                    'close'      => 'වසා දමන්න',
                                    'day'        => 'දිනය',
                                    'edit-title' => 'ස්ලොට් සංස්කරණය කරන්න',
                                    'friday'     => 'ඉරිදා',
                                    'from-day'   => 'සිට දිනය',
                                    'from'       => 'සිට',
                                    'monday'     => 'සඳුදා',
                                    'open'       => 'විවෘත',
                                    'saturday'   => 'සෙනසුරාදා',
                                    'save'       => 'ස්ලොට් සුරකින්න',
                                    'select'     => 'තේරීම',
                                    'status'     => 'ස්ථිර',
                                    'sunday'     => 'ඉරිදා',
                                    'thursday'   => 'බ්රහස්පතින්දා',
                                    'time'       => 'වේලාව',
                                    'title'      => 'ස්ලොට් එකතු කිරීම',
                                    'to'         => 'සිට',
                                    'tuesday'    => 'අඟහරුවාදා',
                                    'wednesday'  => 'බුදුන්දා',
                                    'week'       => ':දින',
                                ],
                            ],

                            'one'            => 'එකයි',
                            'open'           => 'විවෘත',
                            'slot-duration'  => 'ස්ලොට් කාලය (මිනිත්තු)',
                            'title'          => 'සාමාන්‍ය',
                        ],

                        'event'       => [
                            'add'                => 'ක්ෂේත්‍ර එක් කරන්න',
                            'delete'             => 'මකන්න',
                            'description-info'   => 'කිසිවක් ඉතිරි ලකුණු නැත.',
                            'description'        => 'විස්තරය',
                            'edit'               => 'සංස්කරණය කරන්න',

                            'modal'              => [
                                'ticket' => [
                                    'save' => 'ක්ෂේත්‍ර සුරකින්න',
                                ],
                            ],

                            'name'               => 'නම',
                            'price'              => 'මිල',
                            'qty'                => 'ප්‍රමාණය',
                            'special-price-from' => 'විශේෂ මිල සිට',
                            'special-price-to'   => 'විශේෂ මිල දක්වා',
                            'special-price'      => 'විශේෂ මිල',
                            'title'              => 'ක්ෂේත්‍රයන්',
                        ],

                        'empty-info'  => [
                            'tickets' => [
                                'add' => 'ක්ෂේත්‍ර එක් කරන්න',
                            ],

                            'slots'   => [
                                'add'         => 'ස්ලොට් එක් කරන්න',
                                'delete'      => 'මකන්න',
                                'description' => 'සලකුණු ඉල්ලීමේ දිනය සමගින් ලකුණු සහිතව.',
                            ],
                        ],

                        'rental'      => [
                            'daily_hourly'           => 'දිනයේ සහ විකාශනයේ තිබෙනවා (දිනවල සහ විකාශනයවල)',
                            'daily-price'            => 'දින මිල',
                            'daily'                  => 'දින පටන් ප්‍රමාණයට',
                            'hourly-price'           => 'පැය මිල',
                            'hourly'                 => 'පැය පටන් ප්‍රමාණයට',

                            'same-slot-for-all-days' => [
                                'no'    => 'නෑ',
                                'title' => 'සියලු දින සඳහා එකක් ස්ලොට්',
                                'yes'   => 'ඔව්',
                            ],

                            'title'                  => 'බලාගැනීමේ වර්ගය',
                        ],

                        'slots'       => [
                            'add'              => 'ස්ලොට් එක් කරන්න',
                            'delete'           => 'මකන්න',
                            'description-info' => 'ස්ලොට් කාලය සමග ලකුණු සහිතව.',
                            'description'      => 'ස්ලොට් නොමැත.',
                            'edit'             => 'සංස්කරණය කරන්න',

                            'modal'            => [
                                'slot' => [
                                    'friday'    => 'ඉරිදා',
                                    'from'      => 'සිට',
                                    'monday'    => 'සඳුදා',
                                    'saturday'  => 'සෙනසුරාදා',
                                    'sunday'    => 'ඉරිදා',
                                    'thursday'  => 'බ්රහස්පතින්දා',
                                    'to'        => 'සිට',
                                    'tuesday'   => 'අඟහරුවාදා',
                                    'wednesday' => 'බුදුන්දා',
                                ],
                            ],

                            'save'             => 'සුරකින්න',
                            'title'            => 'ස්ලොට්',
                        ],

                        'table'       => [
                            'break-duration'            => 'ස්ලොට් කාලය අතර පිටවන කාලය (මිනිත්තු)',

                            'charged-per'               => [
                                'guest' => 'පුදුමාකාරයා',
                                'table' => 'වගාව',
                                'title' => 'වර්ගය අනුව',
                            ],

                            'guest-capacity'            => 'පුදුමැති පිහිටිය',
                            'guest-limit'               => 'පුදුමැති දේශපාලන සීමාව පිහිටිය',
                            'prevent-scheduling-before' => 'අවලංගුයින් වලංගු දිනයකට පෙර වාර්තා කිරීම වැරදිය',
                            'slot-duration'             => 'ස්ලොට් කාලය (මිනිත්තු)',

                            'same-slot-for-all-days'    => [
                                'no'    => 'නෑ',
                                'title' => 'සියලුම දින සඳහා එකක් ස්ලොට්',
                                'yes'   => 'ඔව්',
                            ],
                        ],
                    ],

                    'types'   => [
                        'booking' => [
                            'available-from'       => 'ලබන්නාගේ දිනය',
                            'available-to'         => 'ලබන්නාගේ දිනය',

                            'available-every-week' => [
                                'no'    => 'නෑ',
                                'title' => 'සෑම සතියකම ලබා දීම',
                                'yes'   => 'ඔව්',
                            ],

                            'location'             => 'ස්ථානය',
                            'qty'                  => 'ප්‍රමාණය',

                            'type'                 => [
                                'appointment' => 'ගිනුම් ලකුණු',
                                'default'     => 'පෙරනිමි',
                                'event'       => 'සිද්ධිය',
                                'many'        => 'බොහෝ',
                                'one'         => 'එක්',
                                'rental'      => 'ක්ෂේත්‍රය',
                                'table'       => 'වගාව',
                                'title'       => 'වර්ගය',
                            ],

                            'title'                => 'බුකින් වර්ගය',
                        ],
                    ],
                ],

                'index' => [
                    'booking' => 'බුකින්',
                ],
            ],
        ],

        'sales'   => [
            'bookings' => [
                'index' => [
                    'datagrid' => [
                        'created-date' => 'නිර්මාණය කළ දිනය',
                        'from'         => 'සිට',
                        'id'           => 'හැඳුනුම්පත් අංකය',
                        'order-id'     => 'ඇණවුම් අංකය',
                        'qty'          => 'ප්‍රමාණය',
                        'to'           => 'දක්වා',
                    ],

                    'title'    => 'ගිනුම් ප්‍රවේශ නිර්මාණය',
                ],

                'title' => 'ගිනුම් ප්‍රවේශ නිර්මාණය',
            ],
        ],
    ],

    'shop'  => [
        'products' => [
            'booking' => [
                'closed'           => 'වසා දමා ඇත',

                'cart'             => [
                    'booking-from' => 'ගිනුම් කිරීම දිනය',
                    'booking-till' => 'ගිනුම් කිරීම කැපවූ දිනය',
                    'daily'        => 'දිනපතා',
                    'event-from'   => 'සිද්ධිය කිරීමේ දිනය',
                    'event-ticket' => 'සිද්ධිය ප්‍රවේශය',
                    'event-till'   => 'සිද්ධිය කැපවූ දිනය',

                    'integrity'    => [
                        'missing_options'        => 'මෙම නිෂ්පාදනය සඳහා විකල්ප නැත.',
                        'select_hourly_duration' => 'එක් පැයක් පිහිටුවාවක් තෝරන්න.',
                    ],

                    'rent-from'    => 'විකිණීම කිරීම දිනය',
                    'rent-till'    => 'විකිණීම කැපවූ දිනය',
                    'rent-type'    => 'විකිණීම ක්‍රමය',
                    'renting_type' => 'විකිණීම ක්‍රමය',
                    'special-note' => 'විශේෂ ඉල්ලීම්/සටහන්',
                ],

                'per-ticket-price' => ':price ප්‍රවේශයට සාමාන්‍යයි',
            ],

            'view'    => [
                'types'   => [
                    'booking' => [
                        'event-on'                 => 'සිද්ධිය',
                        'location'                 => 'ස්ථානය',
                        'slot-duration-in-minutes' => ':minutes මිනිත්තු',
                        'slot-duration'            => 'ස්ලොට් කාලය',
                        'view-on-map'              => 'මාප් දර්ශනය කරන්න',
                    ],
                ],

                'booking' => [
                    'appointment' => [
                        'closed'             => 'වසා දමා ඇත',
                        'today-availability' => 'අද ලබාදීම්',
                    ],

                    'event'       => [
                        'book-your-ticket' => 'ඔබගේ ප්‍රවේශය නිර්මාණය කරන්න',
                    ],

                    'rental'      => [
                        'choose-rent-option' => 'ක්ෂේත්‍රය තෝරන්න',
                        'daily-basis'        => 'දිනපතා මත',
                        'from'               => 'සිට',
                        'hourly-basis'       => 'පැය මත',
                        'rent-an-item'       => 'අයිතමය ක්ෂේත්‍රයට එක් කරන්න',
                        'select-date'        => 'දිනය තෝරන්න',
                        'select-rent-time'   => 'ක්ෂේත්‍ර කාලය තෝරන්න',
                        'select-slot'        => 'ස්ලොට් තෝරන්න',
                        'slot'               => 'ස්ලොට්',
                        'to'                 => 'දක්වා',
                    ],

                    'slots'       => [
                        'book-an-appointment' => 'ගිනුම් ලකුණු නිර්මාණය කරන්න',
                        'date'                => 'දිනය',
                        'no-slots-available'  => 'ලකුණු නොමැත',
                        'title'               => 'ස්ලොට්',
                    ],

                    'table'       => [
                        'book-a-table'       => 'වගාව නිර්මාණය කරන්න',
                        'closed'             => 'වසා දමා ඇත',
                        'slots-for-all-days' => 'සියල්ල දින සඳහා පෙන්වන්න',
                        'special-notes'      => 'විශේෂ ඉල්ලීම්/සටහන්',
                        'today-availability' => 'අද ලබාදීම්',
                    ],
                ],
            ],
        ],
    ],
];
