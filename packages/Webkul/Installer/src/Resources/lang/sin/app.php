<?php

return [
    'seeders' => [
        'attribute' => [
            'attribute-families' => [
                'default' => 'පෙරනිමවේ',
            ],

            'attribute-groups' => [
                'description'       => 'විස්තර',
                'general'           => 'සාමානේඉ',
                'inventories'       => 'වෘක්ත',
                'meta-description'  => 'මැටා වෘක්ත',
                'price'             => 'මිල',
                'shipping'          => 'නාශීලන',
                'settings'          => 'සැකසොක්ක',
            ],

            'attributes' => [
                'brand'                => 'සීනික',
                'color'                => 'වර්ණ',
                'cost'                 => 'පිරතූ',
                'description'          => 'විස්තර',
                'featured'             => 'විභඟය',
                'guest-checkout'       => 'මෘදු වට්ටක්',
                'height'               => 'උඹට',
                'length'               => 'දුරක්කට',
                'meta-title'           => 'මැටා මාළු',
                'meta-keywords'        => 'මැටා මාළු වල',
                'meta-description'     => 'මැටා වෘක්ත',
                'manage-stock'         => 'වෘක්ත කළමනාක්ක',
                'new'                  => 'නව',
                'name'                 => 'නම',
                'product-number'       => 'නිෂ්කල අංකය',
                'price'                => 'මිල',
                'sku'                  => 'SKU කේඑ',
                'status'               => 'තත්ත්වක්',
                'short-description'    => 'කොදු විස්තර',
                'special-price'        => 'විාළු මිල',
                'special-price-from'   => 'විාළු මිල සිට',
                'special-price-to'     => 'විාළු මිල දක්වා',
                'size'                 => 'ප්රමාණක්',
                'tax-category'         => 'බද කටක්',
                'url-key'              => 'URL යතුර',
                'visible-individually' => 'ප්රභේදනවක්',
                'width'                => 'පළල',
                'weight'               => 'බර',
            ],

            'attribute-options' => [
                'black'  => 'කළු',
                'green'  => 'කොළ',
                'l'      => 'L',
                'm'      => 'M',
                'red'    => 'රතු',
                's'      => 'S',
                'white'  => 'සුව',
                'xl'     => 'XL',
                'yellow' => 'කහු',
            ],
        ],

        'category' => [
            'categories' => [
                'description' => 'මූලාශ්ර විස්තර',
                'name'        => 'මූලා',
            ],
        ],

        'cms' => [
            'pages' => [
                'about-us' => [
                    'content' => 'අපි අපිටාළ විස්තර',
                    'title'   => 'අපි',
                ],

                'refund-policy' => [
                    'content' => 'ණපොක් පොක්ක විස්තර',
                    'title'   => 'ණපොක් පොක්ක',
                ],

                'return-policy' => [
                    'content' => 'පිාක්ක පොක්ක විස්තර',
                    'title'   => 'පිාක්ක පොක්ක',
                ],

                'terms-conditions' => [
                    'content' => 'භොක්ක සහේ කොකේකොදත්',
                    'title'   => 'භොක්ක සහේ කොකේකොදත්',
                ],

                'terms-of-use' => [
                    'content' => 'භොක්ක සහේ භොක්කේකොදත්',
                    'title'   => 'භොක්ක සහේ භොක්කේකොදත්',
                ],

                'contact-us' => [
                    'content' => 'අපි සීමා විස්තර',
                    'title'   => 'අපි',
                ],

                'customer-service' => [
                    'content' => 'පොක්කභොක්කභොක්ක විස්තර',
                    'title'   => 'පොක්කභොක්කභොක්ක',
                ],

                'whats-new' => [
                    'content' => 'නොක්කොදත් පොක්කොදත්',
                    'title'   => 'නොක්ක',
                ],

                'payment-policy' => [
                    'content' => 'ණපොක් පොක්ක විස්තර',
                    'title'   => 'ණපොක් පොක්ක',
                ],

                'shipping-policy' => [
                    'content' => 'නාශීලන පොක්ක විස්තර',
                    'title'   => 'නාශීලන පොක්ක',
                ],

                'privacy-policy' => [
                    'content' => 'පුරුෂනා පොක්කොදත්',
                    'title'   => 'පුරුෂනා',
                ],
            ],
        ],

        'core' => [
            'channels' => [
                'meta-title'       => 'පෙරනිමවේ සෙදවොව',
                'meta-keywords'    => 'පෙරනිමවේ සෙදවොව මෙටා මාලු',
                'meta-description' => 'පෙරනිමවේ සෙදවොව මෙටා වෘක්ත',
                'name'             => 'පෙරනිමවේ',
            ],

            'currencies' => [
                'CNY' => 'චීනීස් යුවන්',
                'AED' => 'ඩිර්හඑම්',
                'EUR' => 'යුරෝ',
                'INR' => 'ඉන්දියාන රුපිය',
                'IRR' => 'ඉරානියන් රියාල්',
                'ILS' => 'ඉස්රායායෙකල්',
                'JPY' => 'ජපනීස් යෙන්',
                'GBP' => 'පවන්ඩ් ස්ටර්ලිං',
                'RUB' => 'රුසියන් රූබල්',
                'SAR' => 'සවෂවෙක් රියාල්',
                'TRY' => 'ටර්කිෂ් ලීරා',
                'USD' => 'එක්සරා ඩොලර්',
                'UAH' => 'යුක්රෙයාන් හ්රිවීනියා',
            ],

            'locales' => [
                'ar'    => 'අරාබි',
                'bn'    => 'බෙංගාලි',
                'de'    => 'ජර්මන්',
                'es'    => 'ස්පෙය්බ්',
                'en'    => 'ඉංග්රීස්',
                'fr'    => 'ප්‍රංශ්',
                'fa'    => 'පජිස්',
                'he'    => 'හීබෲ',
                'hi_IN' => 'හින්දි',
                'it'    => 'ඉතලි',
                'ja'    => 'ජපනීස්',
                'nl'    => 'ලංදු',
                'pl'    => 'පොලිෂ්',
                'pt_BR' => 'බ්‍රසිලියන් පෙරෙසියන්',
                'ru'    => 'රුසියන්',
                'sin'   => 'සිංහල',
                'tr'    => 'තුර්කි',
                'uk'    => 'යුක්රේනියා',
                'zh_CN' => 'චීන අච්චේම',
            ],
        ],

        'customer' => [
            'customer-groups' => [
                'guest'     => 'මාදී',
                'general'   => 'පෙරනිමවේ',
                'wholesale' => 'බිලිකාරෙකා',
            ],
        ],

        'inventory' => [
            'inventory-sources' => [
                'name' => 'පෙරනිමවේ',
            ],
        ],

        'shop' => [
            'theme-customizations' => [
                'image-carousel' => [
                    'name'  => 'රූපය උත්තම් සෙදවොව',

                    'sliders' => [
                        'title' => 'නව සංරච්ඡාතය සදහා වෙරි වෙරොදක්',
                    ],
                ],

                'offer-information' => [
                    'name' => 'පිලිකත් වොදත්',

                    'content' => [
                        'title' => 'ඔබගේ 1 වොහුලේ මාරු කළඹාක් සහා කරනවො',
                    ],
                ],

                'categories-collections' => [
                    'name' => 'ප්රෘදාආභර',

                ],

                'new-products' => [
                    'name' => 'නව නිෂ්කල',

                    'options' => [
                        'title' => 'නව නිෂ්කල',
                    ],
                ],

                'top-collections' => [
                    'name' => 'ඉහල ආභර',

                    'content' => [
                        'sub-title-1' => 'අපේ ආභර',
                        'sub-title-2' => 'අපේ ආභර',
                        'sub-title-3' => 'අපේ ආභර',
                        'sub-title-4' => 'අපේ ආභර',
                        'sub-title-5' => 'අපේ ආභර',
                        'sub-title-6' => 'අපේ ආභර',
                        'title'       => 'අපේ නව එම්මොාසා සහා කරනවො',
                    ],
                ],

                'bold-collections' => [
                    'name' => 'විකාශකභාපකභාපක.',

                    'content' => [
                        'btn-title'   => 'සියලුම',
                        'description' => 'අපේ නව තරුණු භාණයම එකට පහළ රචන්ඩ හා ආදාන ශතුර්අංග සහත්දන ආදානයන් සඳහා පරාස්වයක් අනුබැඳතුවනු. රුපුරේව වර්ණ රහුලක් සහ සුවද නැවත ඔබේ ඉඟිපයම නැවත රහුලා කිරීමට හොඳින් ආදානයක්.',
                        'title'       => 'අපේ නව බෝල්ඩිටම සංයලුමට අයිකරනු ලැබේ!',
                    ],
                ],

                'featured-collections' => [
                    'name' => 'ප්‍රකාශ සංයලුම',

                    'options' => [
                        'title' => 'ප්‍රකාශ නිළ භාණයන්',
                    ],
                ],

                'game-container' => [
                    'name' => 'ගේම් කොන්ටේනර්',

                    'content' => [
                        'sub-title-1' => 'අපේ සංයලුම',
                        'sub-title-2' => 'අපේ සංයලුම',
                        'title'       => 'අපේ නව එකට මෙහෙම හේතුව!',
                    ],
                ],

                'all-products' => [
                    'name' => 'සියලුම භාණයන්',

                    'options' => [
                        'title' => 'සියලුම වෙළඳ සංයලුම',
                    ],
                ],

                'footer-links' => [
                    'name' => 'පිටු සබැඳු',

                    'options' => [
                        'about-us'         => 'අප පිළිබදව',
                        'contact-us'       => 'සමස්ත කරනවා',
                        'customer-service' => 'ප්‍රකාශ සේවා',
                        'privacy-policy'   => 'පෞද්ගලිකත්ව පෞද්ගලිකත්ව',
                        'payment-policy'   => 'ගොණ පෞද්ගලිකත්ව',
                        'return-policy'    => 'ආපසුම පෞද්ගලිකත්ව.',
                        'refund-policy'    => 'හරිනායකින් පෞද්ගලිකත්ව.',
                        'shipping-policy'  => 'පෞද්ගලිකත්වකයක්ෂපෙරක්ෂ',
                        'terms-of-use'     => 'භාවිත භාවිත භාවිත',
                        'terms-conditions' => 'භාවිත සෞවිත',
                        'whats-new'        => 'අලුත් කුමුගුණපෙරක්ෂ',
                    ],
                ],
            ],
        ],

        'users' => [
            'name' => 'උදාවර',
        ],

        'roles' => [
            'description' => 'මෙම භාවිතයට සියල්ලන්ට ප්‍රවේශයට හෝ වෙනස් සහායකට සහාය ඇත',
            'name'        => 'පරිකෛටවර',
        ],
    ],
];
