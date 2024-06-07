<?php

return [
    'seeders' => [
        'attribute' => [
            'attribute-families' => [
                'default' => 'පෙරනිමවේ',
            ],

            'attribute-groups' => [
                'description'      => 'විස්තර',
                'general'          => 'සාමානේඉ',
                'inventories'      => 'වෘක්ත',
                'meta-description' => 'මැටා වෘක්ත',
                'price'            => 'මිල',
                'settings'         => 'සැකසොක්ක',
                'shipping'         => 'නාශීලන',
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
                'special-price'        => 'විාළු මිල',
                'special-price-from'   => 'විාළු මිල සිට',
                'special-price-to'     => 'විාළු මිල දක්වා',
                'status'               => 'තත්ත්වක්',
                'tax-category'         => 'බද කටක්',
                'url-key'              => 'URL යතුර',
                'visible-individually' => 'ප්රභේදනවක්',
                'weight'               => 'බර',
                'width'                => 'පළල',
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

                'contact-us' => [
                    'content' => 'අපි සීමා විස්තර',
                    'title'   => 'අපි',
                ],

                'customer-service' => [
                    'content' => 'පොක්කභොක්කභොක්ක විස්තර',
                    'title'   => 'පොක්කභොක්කභොක්ක',
                ],

                'payment-policy' => [
                    'content' => 'ණපොක් පොක්ක විස්තර',
                    'title'   => 'ණපොක් පොක්ක',
                ],

                'privacy-policy' => [
                    'content' => 'පුරුෂනා පොක්කොදත්',
                    'title'   => 'පුරුෂනා',
                ],

                'refund-policy' => [
                    'content' => 'ණපොක් පොක්ක විස්තර',
                    'title'   => 'ණපොක් පොක්ක',
                ],

                'return-policy' => [
                    'content' => 'පිාක්ක පොක්ක විස්තර',
                    'title'   => 'පිාක්ක පොක්ක',
                ],

                'shipping-policy' => [
                    'content' => 'නාශීලන පොක්ක විස්තර',
                    'title'   => 'නාශීලන පොක්ක',
                ],

                'terms-conditions' => [
                    'content' => 'භොක්ක සහේ කොකේකොදත්',
                    'title'   => 'භොක්ක සහේ කොකේකොදත්',
                ],

                'terms-of-use' => [
                    'content' => 'භොක්ක සහේ භොක්කේකොදත්',
                    'title'   => 'භොක්ක සහේ භොක්කේකොදත්',
                ],

                'whats-new' => [
                    'content' => 'නොක්කොදත් පොක්කොදත්',
                    'title'   => 'නොක්ක',
                ],
            ],
        ],

        'core' => [
            'channels' => [
                'name'             => 'පෙරනිමවේ',
                'meta-title'       => 'පෙරනිමවේ සෙදවොව',
                'meta-keywords'    => 'පෙරනිමවේ සෙදවොව මෙටා මාලු',
                'meta-description' => 'පෙරනිමවේ සෙදවොව මෙටා වෘක්ත',
            ],

            'currencies' => [
                'AED' => 'එක්සත් අරාබි ඩිර්හෑම්',
                'ARS' => 'ආර්ජන්ටින් පොසෝ',
                'AUD' => 'ඕස්ට්‍රේලියානු ඩොලර්',
                'BDT' => 'බංග්ලාදේශිය ටාකා',
                'BRL' => 'බ්‍රසීල රියල්',
                'CAD' => 'කැනේඩියානු ඩොලර්',
                'CHF' => 'ස්විස් ෆ්රෑන්ක්',
                'CLP' => 'චිලියන් පැසෝ',
                'CNY' => 'චීන යුයාන්',
                'COP' => 'කොලොම්බියානු පැසෝ',
                'CZK' => 'චෙක් කොරුනා',
                'DKK' => 'ඩැනිෂ් ක්‍රෝන්',
                'DZD' => 'ඇල්ජීරියානු ඩිනාර්',
                'EGP' => 'ඊජිප්තු පවුම්',
                'EUR' => 'යුරෝ',
                'FJD' => 'ෆිජියන් ඩොලර්',
                'GBP' => 'බ්‍රිතාන්‍ය පවුම්',
                'HKD' => 'හොංකොං ඩොලර්',
                'HUF' => 'හන්ගේරියානු ෆොරින්ට්',
                'IDR' => 'ඉන්දුනීසියානු රුපියලිය',
                'ILS' => 'ඊශ්‍රායෙල් නව ශෙකල්',
                'INR' => 'ඉන්දියානු රුපියලිය',
                'JOD' => 'ජෝර්දාන් ඩිනාර්',
                'JPY' => 'ජපන් යෙන්',
                'KRW' => 'දකුණු කොරියානු වොන්',
                'KWD' => 'කුවේටි ඩිනාර්',
                'KZT' => 'කසකස්තානි ටෙන්ගෙ',
                'LBP' => 'ලෙබනීස් පවුම්',
                'LKR' => 'ශ්‍රී ලංකා රුපියලිය',
                'LYD' => 'ලිබියානු ඩිනාර්',
                'MAD' => 'මොරොක්කානු ඩිර්හෑම්',
                'MUR' => 'මුරුසියානු රුපියලිය',
                'MXN' => 'මෙක්සිකානු පෙසෝ',
                'MYR' => 'මැලේසියානු රින්ගිට්',
                'NGN' => 'නයිජීරියානු නයිරා',
                'NOK' => 'නෝර්වීජියානු ක්‍රෝන්',
                'NPR' => 'නේපාලිස් රුපියලිය',
                'NZD' => 'නවසීලන්ත ඩොලර්',
                'OMR' => 'ඕමාන් රියාල්',
                'PAB' => 'පැනමාන් බැල්බෝවා',
                'PEN' => 'පේරුවීනු නියුවෝ සොල්',
                'PHP' => 'පිලිපීන පොසෝ',
                'PKR' => 'පාකිස්තානි රුපියලිය',
                'PLN' => 'පොලිෂ් ස්ලොටි',
                'PYG' => 'පැරගුවේ ගුවරානි',
                'QAR' => 'කටාර් රියාල්',
                'RON' => 'රොමේනියානු ලෙව්',
                'RUB' => 'රුසියන් රූබල්',
                'SAR' => 'සවුදි රියාල්',
                'SEK' => 'ස්වීඩන් ක්‍රෝන්',
                'SGD' => 'සිංගප්පූරු ඩොලර්',
                'THB' => 'තායි බාත්',
                'TND' => 'ටියුනීසියානු ඩිනාර්',
                'TRY' => 'තුර්කි ලිරා',
                'TWD' => 'නව තායිවාන් ඩොලර්',
                'UAH' => 'යුක්රේනියානු හ්‍රිව්නියා',
                'USD' => 'එක්සත් ජනපදය',
                'UZS' => 'උස්බෙකිස්ථාන සොම්',
                'VEF' => 'වෙනිසියුලාන් බොලිවාර්',
                'VND' => 'වියට්නාමීසියානු ඩොන්',
                'XAF' => 'CFA ෆ්රෑන්ක් BEAC',
                'XOF' => 'CFA ෆ්රෑන්ක් BCEAO',
                'ZAR' => 'දකුණු අප්රිකානු රෑන්ඩ්',
                'ZMW' => 'සැම්බියානු ක්වාචා',
            ],

            'locales' => [
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

        'customer' => [
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

        'shop' => [
            'theme-customizations' => [
                'all-products' => [
                    'name' => 'සියලුම භාණයන්',

                    'options' => [
                        'title' => 'සියලුම වෙළඳ සංයලුම',
                    ],
                ],

                'bold-collections' => [
                    'content' => [
                        'btn-title'   => 'ආභර බලන්න',
                        'description' => 'අපේ නව තරුණු භාණයම එකට පහළ රචන්ඩ හා ආදාන ශතුර්අංග සහත්දන ආදානයන් සඳහා පරාස්වයක් අනුබැඳතුවනු. රුපුරේව වර්ණ රහුලක් සහ සුවද නැවත ඔබේ ඉඟිපයම නැවත රහුලා කිරීමට හොඳින් ආදානයක්.',
                        'title'       => 'අපේ නව බෝල්ඩිටම සංයලුමට අයිකරනු ලැබේ!',
                    ],

                    'name' => 'විකාශකභාපකභාපක.',
                ],

                'categories-collections' => [
                    'name' => 'ප්රෘදාආභර',
                ],

                'featured-collections' => [
                    'name' => 'ප්‍රකාශ සංයලුම',

                    'options' => [
                        'title' => 'ප්‍රකාශ නිළ භාණයන්',
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

                'game-container' => [
                    'content' => [
                        'sub-title-1' => 'අපේ සංයලුම',
                        'sub-title-2' => 'අපේ සංයලුම',
                        'title'       => 'අපේ නව එකට මෙහෙම හේතුව!',
                    ],

                    'name' => 'ගේම් කොන්ටේනර්',
                ],

                'image-carousel' => [
                    'name' => 'රූපය උත්තම් සෙදවොව',

                    'sliders' => [
                        'title' => 'නව සංරච්ඡාතය සදහා වෙරි වෙරොදක්',
                    ],
                ],

                'new-products' => [
                    'name' => 'නව නිෂ්කල',

                    'options' => [
                        'title' => 'නව නිෂ්කල',
                    ],
                ],

                'offer-information' => [
                    'content' => [
                        'title' => 'ඔබගේ 1 වොහුලේ මාරු කළඹාක් සහා කරනවො',
                    ],

                    'name' => 'පිලිකත් වොදත්',
                ],

                'services-content' => [
                    'description' => [
                        'emi-available-info'   => 'මූලික ණය නොමැති EMI සහාය සිදු ලබා ගත හැකිය සියලුම ප්‍රමාණයේ ඉතිරි ක්‍රෙඩිට් කාඩ්පත් පමණි',
                        'free-shipping-info'   => 'සියලුම ඇණවුම් මගින් නොමිලේ බෙදාහැරීම ලබා ගත හැකිය',
                        'product-replace-info' => 'ප්‍රතිශතයක් මාරු කිරීම සුරකින්නේ පහසුවෙන් ලබා ගත හැකිය!',
                        'time-support-info'    => 'චැට් හා ඊමේල් මගින් ප්‍රමාණය සහාය ලබා ගත හැකි ප්‍රතිශතය 24/7',
                    ],

                    'name' => 'සේවාවෙන් අනුමත ප්‍රමාණය',

                    'title' => [
                        'emi-available'   => 'EMI ලබා ගත හැකිය',
                        'free-shipping'   => 'නොමිලේ බෙදාහැරීම',
                        'product-replace' => 'නිෂ්පාදනය ප්‍රතිශතයක් මාරු කිරීම',
                        'time-support'    => '24/7 සහාය',
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
            ],
        ],

        'user' => [
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
            'create-administrator' => [
                'admin'            => 'පරිපාලක',
                'bagisto'          => 'බැගිස්ටෝ',
                'confirm-password' => 'මුරපදය තහවුරු කරන්න',
                'download-sample'  => 'උපකාරක භාණ්ඩ බාගත කරන්න',
                'email'            => 'ඊමේල්',
                'email-address'    => 'admin@example.com',
                'password'         => 'මුරපදය',
                'sample-products'  => 'උපකාරක නිෂ්පාදන',
                'title'            => 'පරිපාලකයකු සාදන්න',
            ],

            'environment-configuration' => [
                'algerian-dinar'              => 'ඇල්ජීරියානු ඩිනාරය (DZD)',
                'allowed-currencies'          => 'සමානව වල් මුදල්',
                'allowed-locales'             => 'සමානව පිහිටවන ස්ථාන',
                'application-name'            => 'යෙදුම් නම',
                'argentine-peso'              => 'ආර්ජෙන්ටින් පේසෝ (ARS)',
                'australian-dollar'           => 'ඕස්ට්‍රේලියානු ඩොලර (AUD)',
                'bagisto'                     => 'බැගිස්ටෝ',
                'bangladeshi-taka'            => 'බංග්ලාදේශ ටාකා (BDT)',
                'brazilian-real'              => 'බ්‍රසීලියානු රියල් (BRL)',
                'british-pound-sterling'      => 'බ්‍රිතාන්‍ය පවුම් (GBP)',
                'canadian-dollar'             => 'කැනඩියානු ඩොලර (CAD)',
                'cfa-franc-bceao'             => 'CFA ෆ්‍රෑන්ක් BCEAO (XOF)',
                'cfa-franc-beac'              => 'CFA ෆ්‍රෑන්ක් BEAC (XAF)',
                'chilean-peso'                => 'චිලීයානු පේසෝ (CLP)',
                'chinese-yuan'                => 'චීන යුආන් (CNY)',
                'colombian-peso'              => 'කොලොම්බියානු පේසෝ (COP)',
                'czech-koruna'                => 'චෙක් කොරුන (CZK)',
                'danish-krone'                => 'ඩැනිෂ් ක්රෝන් (DKK)',
                'database-connection'         => 'දත්ත අන්තර්ගතය',
                'database-hostname'           => 'දත්ත ඩාතමින් නම',
                'database-name'               => 'දත්ත නම',
                'database-password'           => 'දත්ත මුරපදය',
                'database-port'               => 'දත්ත අගය',
                'database-prefix'             => 'දත්ත උඩුගත ප්‍රාම්පදාය',
                'database-username'           => 'දත්ත පරිශීලක නම',
                'default-currency'            => 'ස්ථඳන වර්ගය',
                'default-locale'              => 'ස්ථඳන භාෂාව',
                'default-timezone'            => 'ස්ථඳන වේලාවේ කලාපය',
                'default-url'                 => 'ස්ථඳන URL',
                'default-url-link'            => 'https://localhost',
                'egyptian-pound'              => 'ඊජිප්තු පවුම් (EGP)',
                'euro'                        => 'යුරෝ (EUR)',
                'fijian-dollar'               => 'ෆීජියන් ඩොලර (FJD)',
                'hong-kong-dollar'            => 'හොංකොං ඩොලර (HKD)',
                'hungarian-forint'            => 'හන්ගේරියානු ෆෝරින්ට් (HUF)',
                'indian-rupee'                => 'ඉන්දියානු රුපියල් (INR)',
                'indonesian-rupiah'           => 'ඉන්දුනීසියානු රුපියාව (IDR)',
                'israeli-new-shekel'          => 'ඊශ්‍රායල නව ශෙකල් (ILS)',
                'japanese-yen'                => 'ජපන් යෙන් (JPY)',
                'jordanian-dinar'             => 'ජෝර්දාන් ඩිනාරය (JOD)',
                'kazakhstani-tenge'           => 'කසක් ටෙන්ග් (KZT)',
                'kuwaiti-dinar'               => 'කුවේට් ඩිනාරය (KWD)',
                'lebanese-pound'              => 'ලෙබනිස් පවුම් (LBP)',
                'libyan-dinar'                => 'ලිබියානු ඩිනාරය (LYD)',
                'malaysian-ringgit'           => 'මැලේසියානු රින්ගිට් (MYR)',
                'mauritian-rupee'             => 'මොරිෂියානු රුපියාව (MUR)',
                'mexican-peso'                => 'මෙක්සිකානු පේසෝ (MXN)',
                'moroccan-dirham'             => 'මොරොක්කානු ඩිර්හාම් (MAD)',
                'mysql'                       => 'MySQL',
                'nepalese-rupee'              => 'නේපාල් රුපියල් (NPR)',
                'new-taiwan-dollar'           => 'නව තායිවාන් ඩොලර (TWD)',
                'new-zealand-dollar'          => 'නවසීලන්ත ඩොලර (NZD)',
                'nigerian-naira'              => 'නයිජීරියානු නයිරා (NGN)',
                'norwegian-krone'             => 'නොර්වීජියානු ක්රෝන් (NOK)',
                'omani-rial'                  => 'ඔමානි රියාල් (OMR)',
                'pakistani-rupee'             => 'පාකිස්ථානා රුපියල් (PKR)',
                'panamanian-balboa'           => 'පැනමානියානු බැල්බෝවා (PAB)',
                'paraguayan-guarani'          => 'පැරගුවේනියානු ගුවාරනි (PYG)',
                'peruvian-nuevo-sol'          => 'පේරුවියානු නව සෝල් (PEN)',
                'pgsql'                       => 'PgSQL',
                'philippine-peso'             => 'පිලිපීන පේසෝ (PHP)',
                'polish-zloty'                => 'පොලන්ත ස්ලොටි (PLN)',
                'qatari-rial'                 => 'කටාර් රියාල් (QAR)',
                'romanian-leu'                => 'රුමේනියානු ලේ (RON)',
                'russian-ruble'               => 'රුසියානු රූබල් (RUB)',
                'saudi-riyal'                 => 'සවුදි රියාල් (SAR)',
                'select-timezone'             => 'වේලා කලාපය තෝරන්න',
                'singapore-dollar'            => 'සිංගප්පූරු ඩොලර (SGD)',
                'south-african-rand'          => 'දකුණු අප්රිකානු රැන්ඩ් (ZAR)',
                'south-korean-won'            => 'දකුණු කොරියානු වොන් (KRW)',
                'sqlsrv'                      => 'SQLSRV',
                'sri-lankan-rupee'            => 'ශ්‍රී ලංකා රුපියල් (LKR)',
                'swedish-krona'               => 'ස්වීඩන් ක්රෝන් (SEK)',
                'swiss-franc'                 => 'ස්විස් ෆ්රෑන්ක් (CHF)',
                'thai-baht'                   => 'තායි බාත් (THB)',
                'title'                       => 'සෙවුම් අතුරුදහන්',
                'tunisian-dinar'              => 'ටියුනීසියානු ඩිනාරය (TND)',
                'turkish-lira'                => 'තුර්කි ලිරා (TRY)',
                'ukrainian-hryvnia'           => 'යුක්රේනියානු හ්‍රිව්නියා (UAH)',
                'united-arab-emirates-dirham' => 'එක්සත් අරාබිය ඩිරුම් (AED)',
                'united-states-dollar'        => 'එක්සත් ජනපදය ඩොලර (USD)',
                'uzbekistani-som'             => 'උස්බෙකිස්ථාන සම් (UZS)',
                'venezuelan-bolívar'          => 'වෙනෙස්වීලාන් බොලිවාර් (VEF)',
                'vietnamese-dong'             => 'වියට්නාමි ඩොන් (VND)',
                'warning-message'             => 'සම්පූර්ණයෙන්! ඔබේ පිළිබඳ ප්‍රධාන වින්යාසය සහ ප්‍රධාන මුදල් ස්ථාපනයන් ඉක්මනින් ස්ථානය කළ නොහැක.',
                'zambian-kwacha'              => 'සැම්බියාව් ක්වාච් (ZMW)',
            ],

            'installation-processing' => [
                'bagisto'          => 'Bagisto ස්තුරාකුල',
                'bagisto-info'     => 'දත්ත වගකාගකක් සාදනු ඇති වී, මෙමගේ කුම වේදයන්ද කතාමතා වේදිය',
                'title'            => 'ස්තුරාකුල',
            ],

            'installation-completed' => [
                'admin-panel'                => 'පරිපාල පැනල්',
                'bagisto-forums'             => 'Bagisto සංස්ථාගත',
                'customer-panel'             => 'ප්‍රභාණ්ඩ පැනල්',
                'explore-bagisto-extensions' => 'Bagisto ප්‍රභාණ ව්‍යාකරණ',
                'title'                      => 'ස්තුරාකුල සම්තුදාව',
                'title-info'                 => 'Bagisto ඔබගේ පද්ධතියට සහාභයාගීත.',
            ],

            'ready-for-installation' => [
                'create-databsae-table'   => 'දත්ත වගකාගකක් තථා කරනවා',
                'install'                 => 'ස්තුරාකුල',
                'install-info'            => 'උත්සහාභාවයක් සඳහා Bagisto',
                'install-info-button'     => 'පහත ඇතුලත් කිරීමට ක්රම කරන්න',
                'populate-database-table' => 'දත්ත වගකාගකක් සිටුවනවා',
                'start-installation'      => 'ස්තුරාකුල ආරම්භය',
                'title'                   => 'ස්තුරාකුල සූදාංශයට',
            ],

            'start' => [
                'locale'        => 'දේශීය',
                'main'          => 'ආරම්භක',
                'select-locale' => 'Locale තෝරන්න',
                'title'         => 'ඔබේ Bagisto ස්ථාපනය',
                'welcome-title' => 'Bagisto 2.0 වෙත සාදරයෙන් පිළිගනිමු.',
            ],

            'server-requirements' => [
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
                'php'         => 'PHP',
                'php-version' => '8.1 හෝ ඉහළ',
                'session'     => 'සැසුවේ',
                'title'       => 'සේවාකාරී අැතශීරී',
                'tokenizer'   => 'Tokenizer',
                'xml'         => 'XML',
            ],

            'arabic'                   => 'අරාබි',
            'back'                     => 'ආපනය',
            'bagisto'                  => 'බැගිස්ටෝ',
            'bagisto-info'             => 'සමුදුසමින් ව්‍යාක්රණය',
            'bagisto-logo'             => 'Bagisto ලෝගො',
            'bengali'                  => 'බෙංගාලි',
            'chinese'                  => 'චීන',
            'continue'                 => 'ඉදිරිපත්',
            'dutch'                    => 'ලන්දේසි',
            'english'                  => 'ඉංග්රීසි',
            'french'                   => 'ප්රංශ',
            'german'                   => 'ජර්මානු',
            'hebrew'                   => 'හෙබ්රෙව්',
            'hindi'                    => 'හින්දි',
            'installation-description' => 'Bagisto ස්තුරාකුල සහාභයට මෙතා දිගේ ස්තුරාකුල ප්‍රකාරයට වර්ගය වෙනවා:',
            'installation-info'        => 'අපට ඔබ මෙතා බලන්නේ සුමුදුසමින්!',
            'installation-title'       => 'ස්තුරාකුල සහාභ',
            'italian'                  => 'ඉතාලි',
            'japanese'                 => 'ජපන්',
            'persian'                  => 'පර්සියානු',
            'polish'                   => 'පෝලන්ත',
            'portuguese'               => 'බ්රසීලියානු පෘතුගීසි',
            'russian'                  => 'රුසියානු',
            'sinhala'                  => 'සිංහල',
            'spanish'                  => 'ස්පාඤ්ඤ',
            'title'                    => 'Bagisto ස්තුරාකුල',
            'turkish'                  => 'තුර්කි',
            'ukrainian'                => 'යුක්රේනියානු',
            'webkul'                   => 'වෙබ්කුල්',
        ],
    ],
];
