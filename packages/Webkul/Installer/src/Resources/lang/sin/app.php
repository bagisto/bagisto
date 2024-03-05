<?php

return [
    'seeders'   => [
        'attribute' => [
            'attribute-families' => [
                'default' => 'පෙරනිමවේ',
            ],

            'attribute-groups'   => [
                'description'       => 'විස්තර',
                'general'           => 'සාමානේඉ',
                'inventories'       => 'වෘක්ත',
                'meta-description'  => 'මැටා වෘක්ත',
                'price'             => 'මිල',
                'settings'          => 'සැකසොක්ක',
                'shipping'          => 'නාශීලන',
            ],

            'attributes'         => [
                'brand'                => 'සීනික',
                'color'                => 'වර්ණ',
                'cost'                 => 'පිරතූ',
                'description'          => 'විස්තර',
                'featured'             => 'විභඟය',
                'guest-checkout'       => 'මෘදු වට්ටක්',
                'height'               => 'උඹට',
                'length'               => 'දුරක්කට',
                'manage-stock'         => 'වෘක්ත කළමනාක්ක',
                'meta-description'     => 'මැටා වෘක්ත',
                'meta-keywords'        => 'මැටා මාළු වල',
                'meta-title'           => 'මැටා මාළු',
                'name'                 => 'නම',
                'new'                  => 'නව',
                'price'                => 'මිල',
                'product-number'       => 'නිෂ්කල අංකය',
                'short-description'    => 'කොදු විස්තර',
                'size'                 => 'ප්රමාණක්',
                'sku'                  => 'SKU කේඑ',
                'special-price-from'   => 'විාළු මිල සිට',
                'special-price-to'     => 'විාළු මිල දක්වා',
                'special-price'        => 'විාළු මිල',
                'status'               => 'තත්ත්වක්',
                'tax-category'         => 'බද කටක්',
                'url-key'              => 'URL යතුර',
                'visible-individually' => 'ප්රභේදනවක්',
                'weight'               => 'බර',
                'width'                => 'පළල',
            ],

            'attribute-options'  => [
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

        'category'  => [
            'categories' => [
                'description' => 'මූලාශ්ර විස්තර',
                'name'        => 'මූලා',
            ],
        ],

        'cms'       => [
            'pages' => [
                'about-us'         => [
                    'content' => 'අපි අපිටාළ විස්තර',
                    'title'   => 'අපි',
                ],

                'contact-us'       => [
                    'content' => 'අපි සීමා විස්තර',
                    'title'   => 'අපි',
                ],

                'customer-service' => [
                    'content' => 'පොක්කභොක්කභොක්ක විස්තර',
                    'title'   => 'පොක්කභොක්කභොක්ක',
                ],

                'payment-policy'   => [
                    'content' => 'ණපොක් පොක්ක විස්තර',
                    'title'   => 'ණපොක් පොක්ක',
                ],

                'privacy-policy'   => [
                    'content' => 'පුරුෂනා පොක්කොදත්',
                    'title'   => 'පුරුෂනා',
                ],

                'refund-policy'    => [
                    'content' => 'ණපොක් පොක්ක විස්තර',
                    'title'   => 'ණපොක් පොක්ක',
                ],

                'return-policy'    => [
                    'content' => 'පිාක්ක පොක්ක විස්තර',
                    'title'   => 'පිාක්ක පොක්ක',
                ],

                'shipping-policy'  => [
                    'content' => 'නාශීලන පොක්ක විස්තර',
                    'title'   => 'නාශීලන පොක්ක',
                ],

                'terms-conditions' => [
                    'content' => 'භොක්ක සහේ කොකේකොදත්',
                    'title'   => 'භොක්ක සහේ කොකේකොදත්',
                ],

                'terms-of-use'     => [
                    'content' => 'භොක්ක සහේ භොක්කේකොදත්',
                    'title'   => 'භොක්ක සහේ භොක්කේකොදත්',
                ],

                'whats-new'        => [
                    'content' => 'නොක්කොදත් පොක්කොදත්',
                    'title'   => 'නොක්ක',
                ],
            ],
        ],

        'core'      => [
            'channels'   => [
                'meta-description' => 'පෙරනිමවේ සෙදවොව මෙටා වෘක්ත',
                'meta-keywords'    => 'පෙරනිමවේ සෙදවොව මෙටා මාලු',
                'meta-title'       => 'පෙරනිමවේ සෙදවොව',
                'name'             => 'පෙරනිමවේ',
            ],

            'currencies' => [
                'AED' => 'ඩිර්හඑම්',
                'AFN' => 'ඉස්රායායෙකල්',
                'CNY' => 'චීනීස් යුවන්',
                'EUR' => 'යුරෝ',
                'GBP' => 'පවන්ඩ් ස්ටර්ලිං',
                'INR' => 'ඉන්දියාන රුපිය',
                'IRR' => 'ඉරානියන් රියාල්',
                'JPY' => 'ජපනීස් යෙන්',
                'RUB' => 'රුසියන් රූබල්',
                'SAR' => 'සවෂවෙක් රියාල්',
                'TRY' => 'ටර්කිෂ් ලීරා',
                'UAH' => 'යුක්රෙයාන් හ්රිවීනියා',
                'USD' => 'එක්සරා ඩොලර්',
            ],

            'locales'    => [
                'ar'    => 'අරාබි',
                'bn'    => 'බෙංගාලි',
                'de'    => 'ජර්මන්',
                'en'    => 'ඉංග්රීස්',
                'es'    => 'ස්පෙය්බ්',
                'fa'    => 'පජිස්',
                'fr'    => 'ප්‍රංශ්',
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

        'customer'  => [
            'customer-groups' => [
                'general'   => 'පෙරනිමවේ',
                'guest'     => 'මාදී',
                'wholesale' => 'බිලිකාරෙකා',
            ],
        ],

        'inventory' => [
            'inventory-sources' => [
                'name' => 'පෙරනිමවේ',
            ],
        ],

        'shop'      => [
            'theme-customizations' => [
                'all-products'           => [
                    'name'    => 'සියලුම භාණයන්',

                    'options' => [
                        'title' => 'සියලුම වෙළඳ සංයලුම',
                    ],
                ],

                'bold-collections'       => [
                    'content' => [
                        'btn-title'   => 'සියලුම',
                        'description' => 'අපේ නව තරුණු භාණයම එකට පහළ රචන්ඩ හා ආදාන ශතුර්අංග සහත්දන ආදානයන් සඳහා පරාස්වයක් අනුබැඳතුවනු. රුපුරේව වර්ණ රහුලක් සහ සුවද නැවත ඔබේ ඉඟිපයම නැවත රහුලා කිරීමට හොඳින් ආදානයක්.',
                        'title'       => 'අපේ නව බෝල්ඩිටම සංයලුමට අයිකරනු ලැබේ!',
                    ],

                    'name'    => 'විකාශකභාපකභාපක.',
                ],

                'categories-collections' => [
                    'name' => 'ප්රෘදාආභර',
                ],

                'featured-collections'   => [
                    'name'    => 'ප්‍රකාශ සංයලුම',

                    'options' => [
                        'title' => 'ප්‍රකාශ නිළ භාණයන්',
                    ],
                ],

                'footer-links'           => [
                    'name'    => 'පිටු සබැඳු',

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

                'game-container'         => [
                    'content' => [
                        'sub-title-1' => 'අපේ සංයලුම',
                        'sub-title-2' => 'අපේ සංයලුම',
                        'title'       => 'අපේ නව එකට මෙහෙම හේතුව!',
                    ],

                    'name'    => 'ගේම් කොන්ටේනර්',
                ],

                'image-carousel'         => [
                    'name'    => 'රූපය උත්තම් සෙදවොව',

                    'sliders' => [
                        'title' => 'නව සංරච්ඡාතය සදහා වෙරි වෙරොදක්',
                    ],
                ],

                'new-products'           => [
                    'name'    => 'නව නිෂ්කල',

                    'options' => [
                        'title' => 'නව නිෂ්කල',
                    ],
                ],

                'offer-information'      => [
                    'content' => [
                        'title' => 'ඔබගේ 1 වොහුලේ මාරු කළඹාක් සහා කරනවො',
                    ],

                    'name'    => 'පිලිකත් වොදත්',
                ],

                'services-content'       => [
                    'description' => [
                        'emi-available-info'   => 'මූලික ණය නොමැති EMI සහාය සිදු ලබා ගත හැකිය සියලුම ප්‍රමාණයේ ඉතිරි ක්‍රෙඩිට් කාඩ්පත් පමණි',
                        'free-shipping-info'   => 'සියලුම ඇණවුම් මගින් නොමිලේ බෙදාහැරීම ලබා ගත හැකිය',
                        'product-replace-info' => 'ප්‍රතිශතයක් මාරු කිරීම සුරකින්නේ පහසුවෙන් ලබා ගත හැකිය!',
                        'time-support-info'    => 'චැට් හා ඊමේල් මගින් ප්‍රමාණය සහාය ලබා ගත හැකි ප්‍රතිශතය 24/7',
                    ],

                    'name'        => 'සේවාවෙන් අනුමත ප්‍රමාණය',

                    'title'       => [
                        'emi-available'   => 'EMI ලබා ගත හැකිය',
                        'free-shipping'   => 'නොමිලේ බෙදාහැරීම',
                        'product-replace' => 'නිෂ්පාදනය ප්‍රතිශතයක් මාරු කිරීම',
                        'time-support'    => '24/7 සහාය',
                    ],
                ],

                'top-collections'        => [
                    'name'   => 'ඉහල ආභර',

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
            ],
        ],

        'user'      => [
            'roles' => [
                'description' => 'මෙම භාවිතයට සියල්ලන්ට ප්‍රවේශයට හෝ වෙනස් සහායකට සහාය ඇත',
                'name'        => 'පරිකෛටවර',
            ],

            'users' => [
                'name' => 'උදාවර',
            ],
        ],
    ],

    'installer' => [
        'index' => [
            'create-administrator'      => [
                'admin'            => 'පරිපාල',
                'bagisto'          => 'Bagisto',
                'confirm-password' => 'මහපදය තහවුරු කරන්න',
                'email-address'    => 'admin@example.com',
                'email'            => 'E-mail',
                'password'         => 'මහපදය',
                'title'            => 'පරිපාලකරු නිපුණ',
            ],

            'environment-configuration' => [
                'allowed-currencies'  => 'ඉඩ දෙන මුදල්',
                'allowed-locales'     => 'ඉඩ දෙන භාෂා',
                'application-name'    => 'යෙදුමේ නම',
                'bagisto'             => 'බැගිස්ටෝ',
                'chinese-yuan'        => 'චීන යුආන් (CNY)',
                'database-connection' => 'දත්ත සමාපනය',
                'database-hostname'   => 'දත්ත සරාංගය නම',
                'database-name'       => 'දත්ත නම',
                'database-password'   => 'දත්ත මුරපදය',
                'database-port'       => 'දත්ත තැරණ',
                'database-prefix'     => 'දත්ත උපවේශ්කය',
                'database-username'   => 'දත්ත පරිශීලක නම',
                'default-currency'    => 'ස්වභාවය වාන්වේ',
                'default-locale'      => 'ස්වභාවය පෙර දේශ',
                'default-timezone'    => 'ස්වභාවය කාල කථ',
                'default-url-link'    => 'https://localhost',
                'default-url'         => 'ස්වභාවය URL',
                'dirham'              => 'ඩිරහාම් (AED)',
                'euro'                => 'යුරෝ (EUR)',
                'iranian'             => 'ඉරාන රියාල් (IRR)',
                'israeli'             => 'ඉස්රායාල් ශෙකල් (AFN)',
                'japanese-yen'        => 'ජපනීස් යෙන් (JPY)',
                'mysql'               => 'Mysql',
                'pgsql'               => 'pgSQL',
                'pound'               => 'බ්‍රිටිස් වෙල්ටින (GBP)',
                'rupee'               => 'ඉන්දියාන රුපිය (INR)',
                'russian-ruble'       => 'රුසියාන් රූබල් (RUB)',
                'saudi'               => 'සෞඩී රියාල් (SAR)',
                'select-timezone'     => 'කාල කලාපය තෝරන්න',
                'sqlsrv'              => 'SQLSRV',
                'title'               => 'පරාමිතාව විකාශනය',
                'turkish-lira'        => 'තර්කිෂ් ලීර (TRY)',
                'ukrainian-hryvnia'   => 'යුක්රේනියාන් හ්‍රිව්නිය (UAH)',
                'usd'                 => 'ඇමෙරිකානු ඩොලර් (USD)',
                'warning-message'     => 'සක්රියයෙන් දැක්කම්! ඔබේ පෙරනිමි පද්ධතියේ භාෂා ගැන්වීමේ සැකසීම් සහ පෙරනිමි වෙනස් කළ නොහැකි වෙනස්කම් ස්ථාපනය වේ.',
            ],

            'installation-processing'   => [
                'bagisto-info'     => 'දත්ත වගකාගකක් සාදනු ඇති වී, මෙමගේ කුම වේදයන්ද කතාමතා වේදිය',
                'bagisto'          => 'Bagisto ස්තුරාකුල',
                'title'            => 'ස්තුරාකුල',
            ],

            'installation-completed'    => [
                'admin-panel'                => 'පරිපාල පැනල්',
                'bagisto-forums'             => 'Bagisto සංස්ථාගත',
                'customer-panel'             => 'ප්‍රභාණ්ඩ පැනල්',
                'explore-bagisto-extensions' => 'Bagisto ප්‍රභාණ ව්‍යාකරණ',
                'title-info'                 => 'Bagisto ඔබගේ පද්ධතියට සහාභයාගීත.',
                'title'                      => 'ස්තුරාකුල සම්තුදාව',
            ],

            'ready-for-installation'    => [
                'create-databsae-table'   => 'දත්ත වගකාගකක් තථා කරනවා',
                'install-info-button'     => 'පහත ඇතුලත් කිරීමට ක්රම කරන්න',
                'install-info'            => 'උත්සහාභාවයක් සඳහා Bagisto',
                'install'                 => 'ස්තුරාකුල',
                'populate-database-table' => 'දත්ත වගකාගකක් සිටුවනවා',
                'start-installation'      => 'ස්තුරාකුල ආරම්භය',
                'title'                   => 'ස්තුරාකුල සූදාංශයට',
            ],

            'start'                     => [
                'locale'        => 'දේශීය',
                'main'          => 'ආරම්භක',
                'select-locale' => 'Locale තෝරන්න',
                'title'         => 'ඔබේ Bagisto ස්ථාපනය',
                'welcome-title' => 'Bagisto 2.0 වෙත සාදරයෙන් පිළිගනිමු.',
            ],

            'server-requirements'       => [
                'calendar'    => 'දාර්තකය',
                'ctype'       => 'ctype',
                'curl'        => 'cURL',
                'dom'         => 'DOM',
                'fileinfo'    => 'ගොනු තොරතුරු',
                'filter'      => 'පෙරභාවන',
                'gd'          => 'GD',
                'hash'        => 'ඉතුරු',
                'intl'        => 'Intl',
                'json'        => 'JSON',
                'mbstring'    => 'mbstring',
                'openssl'     => 'OpenSSL',
                'pcre'        => 'pcre',
                'pdo'         => 'pdo',
                'php-version' => '8.1 හෝ ඉහළ',
                'php'         => 'PHP',
                'session'     => 'සැසුවේ',
                'title'       => 'සේවාකාරී අැතශීරී',
                'tokenizer'   => 'Tokenizer',
                'xml'         => 'XML',
            ],

            'arabic'                    => 'අරාබි',
            'back'                      => 'ආපනය',
            'bagisto-info'              => 'සමුදුසමින් ව්‍යාක්රණය',
            'bagisto-logo'              => 'Bagisto ලෝගො',
            'bagisto'                   => 'බැගිස්ටෝ',
            'bengali'                   => 'බෙංගාලි',
            'chinese'                   => 'චීන',
            'continue'                  => 'ඉදිරිපත්',
            'dutch'                     => 'ලන්දේසි',
            'english'                   => 'ඉංග්රීසි',
            'french'                    => 'ප්රංශ',
            'german'                    => 'ජර්මානු',
            'hebrew'                    => 'හෙබ්රෙව්',
            'hindi'                     => 'හින්දි',
            'installation-description'  => 'Bagisto ස්තුරාකුල සහාභයට මෙතා දිගේ ස්තුරාකුල ප්‍රකාරයට වර්ගය වෙනවා:',
            'installation-info'         => 'අපට ඔබ මෙතා බලන්නේ සුමුදුසමින්!',
            'installation-title'        => 'ස්තුරාකුල සහාභ',
            'italian'                   => 'ඉතාලි',
            'japanese'                  => 'ජපන්',
            'persian'                   => 'පර්සියානු',
            'polish'                    => 'පෝලන්ත',
            'portuguese'                => 'බ්රසීලියානු පෘතුගීසි',
            'russian'                   => 'රුසියානු',
            'save-configuration'        => 'සැකසුම සුරක්ෂා',
            'sinhala'                   => 'සිංහල',
            'skip'                      => 'අඩභාගනනක්',
            'spanish'                   => 'ස්පාඤ්ඤ',
            'title'                     => 'Bagisto ස්තුරාකුල',
            'turkish'                   => 'තුර්කි',
            'ukrainian'                 => 'යුක්රේනියානු',
            'webkul'                    => 'වෙබ්කුල්',
        ],
    ],
];
