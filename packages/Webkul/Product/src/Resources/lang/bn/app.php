<?php

return [
    'checkout' => [
        'cart' => [
            'integrity' => [
                'qty-missing'   => 'অন্তত একটি পণ্যের জন্য একটি পরিমাণের বেশি থাকতে হবে।',
            ],

            'inventory-warning' => 'অনুরোধকৃত পরিমাণ পাওয়া যায়নি, দয়া করে পরবর্তীতে আবার চেষ্টা করুন।',
            'missing-links'     => 'এই পণ্যের জন্য ডাউনলোডযোগ্য লিঙ্কগুলি অনুপস্থিত।',
            'missing-options'   => 'এই পণ্যের জন্য অপশনগুলি অনুপস্থিত।',
        ],
    ],

    'datagrid' => [
        'copy-of-slug'                  => ':value-এর কপি',
        'copy-of'                       => ':value-এর কপি',
        'variant-already-exist-message' => 'একই গুণাবলীর বৈশিষ্ট্য বিকল্প ইতিমধ্যে অস্তিত্বে আছে।',
    ],

    'response' => [
        'product-can-not-be-copied' => ':type প্রকারের পণ্যগুলি কপি করা যাবে না',
    ],

    'sort-by'  => [
        'options' => [
            'cheapest-first'  => 'সবচেয়ে কম মূল্যে',
            'expensive-first' => 'সবচেয়ে বেশি মূল্যে',
            'from-a-z'        => 'A-Z থেকে',
            'from-z-a'        => 'Z-A থেকে',
            'latest-first'    => 'নতুনতম প্রথম',
            'oldest-first'    => 'প্রাচীনতম প্রথম',
        ],
    ],

    'type'     => [
        'abstract'     => [
            'offers' => ':price প্রতি :qty কিনুন এবং :discount জন্য সেভ করুন',
        ],

        'bundle'       => 'বান্ডল',
        'configurable' => 'কনফিগারেবল',
        'downloadable' => 'ডাউনলোডযোগ্য',
        'grouped'      => 'গোষ্ঠীবদ্ধ',
        'simple'       => 'সাধারিত',
        'virtual'      => 'ভার্চুয়াল',
    ],
];
