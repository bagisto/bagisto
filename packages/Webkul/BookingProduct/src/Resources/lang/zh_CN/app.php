<?php

return [
    'admin' => [
        'catalog' => [
            'products' => [
                'edit' => [
                    'booking' => [
                        'appointment' => [
                            'break-duration'         => 'Slot之间的休息时间（分钟）',
                            'slot-duration'          => 'Slot持续时间（分钟）',

                            'same-slot-for-all-days' => [
                                'no'    => '否',
                                'title' => '所有天都相同的Slot',
                                'yes'   => '是',
                            ],
                        ],

                        'default'     => [
                            'break-duration' => 'Slot之间的休息时间（分钟）',
                            'close'          => '关闭',
                            'delete'         => '删除',
                            'description'    => '预订信息',
                            'edit'           => '编辑',
                            'many'           => '多',

                            'modal'          => [
                                'slot' => [
                                    'close'      => '关闭',
                                    'day'        => '天',
                                    'edit-title' => '编辑Slots',
                                    'friday'     => '星期五',
                                    'from-day'   => '从天开始',
                                    'from'       => '从',
                                    'monday'     => '星期一',
                                    'open'       => '打开',
                                    'saturday'   => '星期六',
                                    'save'       => '保存Slot',
                                    'select'     => '选择',
                                    'status'     => '状态',
                                    'sunday'     => '星期天',
                                    'thursday'   => '星期四',
                                    'time'       => '时间',
                                    'title'      => '添加Slots',
                                    'to'         => '到',
                                    'tuesday'    => '星期二',
                                    'wednesday'  => '星期三',
                                    'week'       => ':day',
                                ],
                            ],

                            'one'            => '一',
                            'open'           => '打开',
                            'slot-duration'  => 'Slot持续时间（分钟）',
                            'title'          => '默认',
                        ],

                        'event'       => [
                            'add'                => '添加门票',
                            'delete'             => '删除',
                            'description-info'   => '没有可用的门票。',
                            'description'        => '描述',
                            'edit'               => '编辑',

                            'modal'              => [
                                'ticket' => [
                                    'save' => '保存门票',
                                ],
                            ],

                            'name'               => '名称',
                            'price'              => '价格',
                            'qty'                => '数量',
                            'special-price-from' => '特价从',
                            'special-price-to'   => '特价到',
                            'special-price'      => '特价',
                            'title'              => '门票',
                        ],

                        'empty-info'  => [
                            'tickets' => [
                                'add' => '添加门票',
                            ],

                            'slots'   => [
                                'add'         => '添加Slots',
                                'delete'      => '删除',
                                'description' => '具有时间持续时间的可用Slots。',
                            ],
                        ],

                        'rental'      => [
                            'daily_hourly'           => '每日和每小时基础',
                            'daily-price'            => '每日价格',
                            'daily'                  => '每日基础',
                            'hourly-price'           => '每小时价格',
                            'hourly'                 => '每小时基础',

                            'same-slot-for-all-days' => [
                                'no'    => '否',
                                'title' => '所有天都相同的Slot',
                                'yes'   => '是',
                            ],

                            'title'                  => '租赁类型',
                        ],

                        'slots'       => [
                            'add'              => '添加Slots',
                            'delete'           => '删除',
                            'description-info' => '具有时间持续时间的可用Slots。',
                            'description'      => '没有可用的Slots。',
                            'edit'             => '编辑',

                            'modal'            => [
                                'slot' => [
                                    'friday'    => '星期五',
                                    'from'      => '从',
                                    'monday'    => '星期一',
                                    'saturday'  => '星期六',
                                    'sunday'    => '星期天',
                                    'thursday'  => '星期四',
                                    'to'        => '到',
                                    'tuesday'   => '星期二',
                                    'wednesday' => '星期三',
                                ],
                            ],

                            'save'             => '保存',
                            'title'            => 'Slots',
                        ],

                        'table'       => [
                            'break-duration'            => 'Slot之间的休息时间（分钟）',

                            'charged-per'               => [
                                'guest' => '客人',
                                'table' => '桌子',
                                'title' => '每个收费',
                            ],

                            'guest-capacity'            => '客人容量',
                            'guest-limit'               => '每桌客人限制',
                            'prevent-scheduling-before' => '在预订之前防止安排',
                            'slot-duration'             => 'Slot持续时间（分钟）',

                            'same-slot-for-all-days'    => [
                                'no'    => '否',
                                'title' => '所有天都相同的Slot',
                                'yes'   => '是',
                            ],
                        ],
                    ],

                    'types'   => [
                        'booking' => [
                            'available-from'       => '从可用',
                            'available-to'         => '到可用',

                            'available-every-week' => [
                                'no'    => '否',
                                'title' => '每周都可用',
                                'yes'   => '是',
                            ],

                            'location'             => '位置',
                            'qty'                  => '数量',

                            'type'                 => [
                                'appointment' => '预约',
                                'default'     => '默认',
                                'event'       => '事件',
                                'many'        => '多',
                                'one'         => '一',
                                'rental'      => '租赁',
                                'table'       => '桌子',
                                'title'       => '类型',
                            ],

                            'title'                => '预订类型',
                        ],
                    ],

                    'index'   => [
                        'booking' => '预订',
                    ],
                ],
            ],
        ],

        'sales'   => [
            'bookings' => [
                'index' => [
                    'datagrid' => [
                        'created-date' => '创建日期',
                        'from'         => '从',
                        'id'           => 'ID',
                        'order-id'     => '订单ID',
                        'qty'          => '数量',
                        'to'           => '到',
                    ],

                    'title'    => '预订产品',
                ],

                'title' => '预订产品',
            ],
        ],
    ],

    'shop'  => [
        'products' => [
            'booking' => [
                'closed'           => '已关闭',

                'cart'             => [
                    'booking-from' => '预订从',
                    'booking-till' => '预订至',
                    'daily'        => '每日',
                    'event-from'   => '活动开始于',
                    'event-ticket' => '活动门票',
                    'event-till'   => '活动结束于',

                    'integrity'    => [
                        'missing_options'        => '该产品缺少选项。',
                        'select_hourly_duration' => '选择一个小时的时间段。',
                    ],

                    'rent-from'    => '租赁从',
                    'rent-till'    => '租赁至',
                    'rent-type'    => '租赁类型',
                    'renting_type' => '租赁类型',
                    'special-note' => '特殊要求/备注',
                ],

                'per-ticket-price' => ':price 每张票',
            ],

            'view'    => [
                'types'   => [
                    'booking' => [
                        'event-on'                 => '活动时间',
                        'location'                 => '地点',
                        'slot-duration-in-minutes' => ':minutes 分钟',
                        'slot-duration'            => '时间段',
                        'view-on-map'              => '在地图上查看',
                    ],
                ],

                'booking' => [
                    'appointment' => [
                        'closed'             => '已关闭',
                        'today-availability' => '今日可预订',
                    ],

                    'event'       => [
                        'book-your-ticket' => '预订您的门票',
                    ],

                    'rental'      => [
                        'choose-rent-option' => '选择租赁选项',
                        'daily-basis'        => '按日计费',
                        'from'               => '从',
                        'hourly-basis'       => '按小时计费',
                        'rent-an-item'       => '租赁物品',
                        'select-date'        => '选择日期',
                        'select-rent-time'   => '选择租赁时间',
                        'select-slot'        => '选择时间段',
                        'slot'               => '时间段',
                        'to'                 => '至',
                    ],

                    'slots'       => [
                        'book-an-appointment' => '预订约会',
                        'date'                => '日期',
                        'no-slots-available'  => '没有可用时间段',
                        'title'               => '时间段',
                    ],

                    'table'       => [
                        'book-a-table'       => '预订桌位',
                        'closed'             => '已关闭',
                        'slots-for-all-days' => '显示所有日期',
                        'special-notes'      => '特殊要求/备注',
                        'today-availability' => '今日可用',
                    ],
                ],
            ],
        ],
    ],
];
