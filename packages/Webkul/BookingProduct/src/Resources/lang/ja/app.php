<?php

return [
    'admin' => [
        'catalog' => [
            'products' => [
                'edit' => [
                    'booking' => [
                        'appointment' => [
                            'break-duration'         => 'スロット間の休憩時間（分）',
                            'slot-duration'          => 'スロットの期間（分）',

                            'same-slot-for-all-days' => [
                                'no'    => 'いいえ',
                                'title' => 'すべての日の同じスロット',
                                'yes'   => 'はい',
                            ],
                        ],

                        'default'     => [
                            'break-duration' => 'スロット間の休憩時間（分）',
                            'close'          => '閉じる',
                            'delete'         => '削除',
                            'description'    => '予約情報',
                            'edit'           => '編集',
                            'many'           => 'たくさん',

                            'modal'          => [
                                'slot' => [
                                    'close'      => '閉じる',
                                    'day'        => '日',
                                    'edit-title' => 'スロットを編集',
                                    'friday'     => '金曜日',
                                    'from-day'   => '開始日',
                                    'from'       => '開始',
                                    'monday'     => '月曜日',
                                    'open'       => 'オープン',
                                    'saturday'   => '土曜日',
                                    'save'       => 'スロットを保存',
                                    'select'     => '選択',
                                    'status'     => 'ステータス',
                                    'sunday'     => '日曜日',
                                    'thursday'   => '木曜日',
                                    'time'       => '時間',
                                    'title'      => 'スロットを追加',
                                    'to'         => '終了',
                                    'tuesday'    => '火曜日',
                                    'wednesday'  => '水曜日',
                                    'week'       => ':day',
                                ],
                            ],

                            'one'            => '1つ',
                            'open'           => 'オープン',
                            'slot-duration'  => 'スロットの期間（分）',
                            'title'          => 'デフォルト',
                        ],

                        'event'       => [
                            'add'                => 'チケットを追加',
                            'delete'             => '削除',
                            'description-info'   => '利用可能なチケットはありません。',
                            'description'        => '説明',
                            'edit'               => '編集',

                            'modal'              => [
                                'ticket' => [
                                    'save' => 'チケットを保存',
                                ],
                            ],

                            'name'               => '名前',
                            'price'              => '価格',
                            'qty'                => '数量',
                            'special-price-from' => '特別価格 開始',
                            'special-price-to'   => '特別価格 終了',
                            'special-price'      => '特別価格',
                            'title'              => 'チケット',
                        ],

                        'empty-info'  => [
                            'tickets' => [
                                'add' => 'チケットを追加',
                            ],

                            'slots'   => [
                                'add'         => 'スロットを追加',
                                'delete'      => '削除',
                                'description' => '利用可能な時間帯のスロット。',
                            ],
                        ],

                        'rental'      => [
                            'daily_hourly'           => '両方（日次および時間毎）',
                            'daily-price'            => '日次価格',
                            'daily'                  => '日次ベース',
                            'hourly-price'           => '時間毎価格',
                            'hourly'                 => '時間毎ベース',

                            'same-slot-for-all-days' => [
                                'no'    => 'いいえ',
                                'title' => 'すべての日の同じスロット',
                                'yes'   => 'はい',
                            ],

                            'title'                  => 'レンタルタイプ',
                        ],

                        'slots'       => [
                            'add'              => 'スロットを追加',
                            'delete'           => '削除',
                            'description-info' => '利用可能な時間帯のスロット。',
                            'description'      => '利用可能なスロットはありません。',
                            'edit'             => '編集',

                            'modal'            => [
                                'slot' => [
                                    'friday'    => '金曜日',
                                    'from'      => '開始',
                                    'monday'    => '月曜日',
                                    'saturday'  => '土曜日',
                                    'sunday'    => '日曜日',
                                    'thursday'  => '木曜日',
                                    'to'        => '終了',
                                    'tuesday'   => '火曜日',
                                    'wednesday' => '水曜日',
                                ],
                            ],

                            'save'             => '保存',
                            'title'            => 'スロット',
                        ],

                        'table'       => [
                            'break-duration'            => 'スロット間の休憩時間（分）',

                            'charged-per'               => [
                                'guest' => 'ゲスト',
                                'table' => 'テーブル',
                                'title' => '単価',
                            ],

                            'guest-capacity'            => 'ゲスト収容人数',
                            'guest-limit'               => 'テーブル毎のゲスト制限',
                            'prevent-scheduling-before' => 'スケジューリング前の制限',
                            'slot-duration'             => 'スロットの期間（分）',

                            'same-slot-for-all-days'    => [
                                'no'    => 'いいえ',
                                'title' => 'すべての日の同じスロット',
                                'yes'   => 'はい',
                            ],
                        ],
                    ],

                    'types'   => [
                        'booking' => [
                            'available-from'       => '利用可能開始日',
                            'available-to'         => '利用可能終了日',

                            'available-every-week' => [
                                'no'    => 'いいえ',
                                'title' => '毎週利用可能',
                                'yes'   => 'はい',
                            ],

                            'location'             => '場所',
                            'qty'                  => '数量',

                            'type'                 => [
                                'appointment' => '予約',
                                'default'     => 'デフォルト',
                                'event'       => 'イベント',
                                'many'        => 'たくさん',
                                'one'         => '1つ',
                                'rental'      => 'レンタル',
                                'table'       => 'テーブル',
                                'title'       => 'タイプ',
                            ],

                            'title'                => '予約タイプ',
                        ],
                    ],

                    'index'   => [
                        'booking' => '予約',
                    ],
                ],
            ],
        ],

        'sales'   => [
            'bookings' => [
                'index' => [
                    'datagrid' => [
                        'created-date' => '作成日',
                        'from'         => '開始',
                        'id'           => 'ID',
                        'order-id'     => '注文ID',
                        'qty'          => '数量',
                        'to'           => '終了',
                    ],

                    'title' => '予約製品',
                ],

                'title' => '予約製品',
            ],
        ],
    ],

    'shop'  => [
        'products' => [
            'booking' => [
                'closed'           => '閉じています',

                'cart'             => [
                    'booking-from' => '予約開始日時',
                    'booking-till' => '予約終了日時',
                    'daily'        => '毎日',
                    'event-from'   => 'イベント開始',
                    'event-ticket' => 'イベントチケット',
                    'event-till'   => 'イベント終了',

                    'integrity'    => [
                        'missing_options'        => 'この製品のオプションがありません。',
                        'select_hourly_duration' => '1時間のスロットの期間を選択してください。',
                    ],

                    'rent-from'    => 'レンタル開始',
                    'rent-till'    => 'レンタル終了',
                    'rent-type'    => 'レンタルタイプ',
                    'renting_type' => 'レンタルタイプ',
                    'special-note' => '特別リクエスト/メモ',
                ],

                'per-ticket-price' => ':price チケットごと',
            ],

            'view'    => [
                'types'   => [
                    'booking' => [
                        'event-on'                 => 'イベント日',
                        'location'                 => '場所',
                        'slot-duration-in-minutes' => ':minutes 分',
                        'slot-duration'            => 'スロット期間',
                        'view-on-map'              => 'マップで表示',
                    ],
                ],

                'booking' => [
                    'appointment' => [
                        'closed'             => '閉じています',
                        'today-availability' => '今日の利用可能性',
                    ],

                    'event'       => [
                        'book-your-ticket' => 'チケットを予約する',
                    ],

                    'rental'      => [
                        'choose-rent-option' => 'レンタルオプションを選択する',
                        'daily-basis'        => '日次ベース',
                        'from'               => '開始',
                        'hourly-basis'       => '時間単位ベース',
                        'rent-an-item'       => 'アイテムをレンタルする',
                        'select-date'        => '日付を選択する',
                        'select-rent-time'   => 'レンタル時間を選択する',
                        'select-slot'        => 'スロットを選択する',
                        'slot'               => 'スロット',
                        'to'                 => '終了',
                    ],

                    'slots'       => [
                        'book-an-appointment' => '予約する',
                        'date'                => '日付',
                        'no-slots-available'  => '利用可能なスロットはありません',
                        'title'               => 'スロット',
                    ],

                    'table'       => [
                        'book-a-table'       => 'テーブルを予約する',
                        'closed'             => '閉じています',
                        'slots-for-all-days' => 'すべての日にちを表示',
                        'special-notes'      => '特別リクエスト/メモ',
                        'today-availability' => '今日の利用可能性',
                    ],
                ],
            ],
        ],
    ],
];
