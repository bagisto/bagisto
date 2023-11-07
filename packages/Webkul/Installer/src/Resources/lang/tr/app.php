<?php

return [
    'seeders' => [
        'attribute' => [
            'attribute-families' => [
                'default' => 'Varsayılan',
            ],

            'attribute-groups' => [
                'description'       => 'Açıklama',
                'general'           => 'Genel',
                'inventories'       => 'Envanterler',
                'meta-description'  => 'Meta Açıklama',
                'price'             => 'Fiyat',
                'shipping'          => 'Nakliye',
                'settings'          => 'Ayarlar',
            ],

            'attributes' => [
                'brand'                => 'Marka',
                'color'                => 'Renk',
                'cost'                 => 'Maliyet',
                'description'          => 'Açıklama',
                'featured'             => 'Öne Çıkan',
                'guest-checkout'       => 'Misafir Ödeme',
                'height'               => 'Yükseklik',
                'length'               => 'Uzunluk',
                'meta-title'           => 'Meta Başlık',
                'meta-keywords'        => 'Meta Anahtar Kelimeler',
                'meta-description'     => 'Meta Açıklama',
                'manage-stock'         => 'Stoğu Yönet',
                'new'                  => 'Yeni',
                'name'                 => 'Ad',
                'product-number'       => 'Ürün Numarası',
                'price'                => 'Fiyat',
                'sku'                  => 'Stok Kodu',
                'status'               => 'Durum',
                'short-description'    => 'Kısa Açıklama',
                'special-price'        => 'Özel Fiyat',
                'special-price-from'   => 'Özel Fiyat Başlangıç',
                'special-price-to'     => 'Özel Fiyat Bitiş',
                'size'                 => 'Boyut',
                'tax-category'         => 'Vergi Kategorisi',
                'url-key'              => 'URL Anahtarı',
                'visible-individually' => 'Tek Tek Görünür',
                'width'                => 'Genişlik',
                'weight'               => 'Ağırlık',
            ],

            'attribute-options' => [
                'black'  => 'Siyah',
                'green'  => 'Yeşil',
                'l'      => 'L',
                'm'      => 'M',
                'red'    => 'Kırmızı',
                's'      => 'S',
                'white'  => 'Beyaz',
                'xl'     => 'XL',
                'yellow' => 'Sarı',
            ],
        ],

        'category' => [
            'categories' => [
                'description' => 'Kök Kategori Açıklaması',
                'name'        => 'Kök',
            ],
        ],

        'cms' => [
            'pages' => [
                'about-us' => [
                    'content' => 'Hakkımızda Sayfası İçeriği',
                    'title'   => 'Hakkımızda',
                ],

                'refund-policy' => [
                    'content' => 'İade Politikası Sayfa İçeriği',
                    'title'   => 'İade Politikası',
                ],

                'return-policy' => [
                    'content' => 'İade Politikası Sayfa İçeriği',
                    'title'   => 'İade Politikası',
                ],

                'terms-conditions' => [
                    'content' => 'Şartlar ve Koşullar Sayfa İçeriği',
                    'title'   => 'Şartlar ve Koşullar',
                ],

                'terms-of-use' => [
                    'content' => 'Kullanım Koşulları Sayfa İçeriği',
                    'title'   => 'Kullanım Koşulları',
                ],

                'contact-us' => [
                    'content' => 'Bize Ulaşın Sayfa İçeriği',
                    'title'   => 'Bize Ulaşın',
                ],

                'customer-service' => [
                    'content' => 'Müşteri Hizmetleri Sayfa İçeriği',
                    'title'   => 'Müşteri Hizmetleri',
                ],

                'whats-new' => [
                    'content' => 'Yenilikler Sayfa İçeriği',
                    'title'   => 'Yenilikler',
                ],

                'payment-policy' => [
                    'content' => 'Ödeme Politikası Sayfa İçeriği',
                    'title'   => 'Ödeme Politikası',
                ],

                'shipping-policy' => [
                    'content' => 'Kargo Politikası Sayfa İçeriği',
                    'title'   => 'Kargo Politikası',
                ],

                'privacy-policy' => [
                    'content' => 'Gizlilik Politikası Sayfa İçeriği',
                    'title'   => 'Gizlilik Politikası',
                ],
            ],
        ],

        'core' => [
            'channels' => [
                'meta-title'       => 'Demo Mağaza',
                'meta-keywords'    => 'Demo Mağaza Meta Anahtar Kelimeler',
                'meta-description' => 'Demo Mağaza Meta Açıklama',
                'name'             => 'Varsayılan',
            ],

            'currencies' => [
                'CNY' => 'Çin Yuanı',
                'AED' => 'Dirhem',
                'EUR' => 'EURO',
                'INR' => 'Hindistan Rupisi',
                'IRR' => 'İran Riyali',
                'ILS' => 'İsrail Şekeli',
                'JPY' => 'Japon Yeni',
                'GBP' => 'İngiliz Sterlini',
                'RUB' => 'Rus Rublesi',
                'SAR' => 'Suudi Riyal',
                'TRY' => 'Türk Lirası',
                'USD' => 'ABD Doları',
                'UAH' => 'Ukrayna Grivnası',
            ],

            'locales' => [
                'ar'    => 'Arapça',
                'bn'    => 'Bengali',
                'de'    => 'Almanca',
                'es'    => 'İspanyolca',
                'en'    => 'İngilizce',
                'fr'    => 'Fransızca',
                'fa'    => 'Farsça',
                'he'    => 'İbranice',
                'hi_IN' => 'Hintçe',
                'it'    => 'İtalyanca',
                'ja'    => 'Japonca',
                'nl'    => 'Felemenkçe',
                'pl'    => 'Lehçe',
                'pt_BR' => 'Brezilya Portekizcesi',
                'ru'    => 'Rusça',
                'sin'   => 'Sinhala',
                'tr'    => 'Türkçe',
                'uk'    => 'Ukraynaca',
                'zh_CN' => 'Çince',
            ],
        ],

        'customer' => [
            'customer-groups' => [
                'guest'     => 'Misafir',
                'general'   => 'Genel',
                'wholesale' => 'Toptan',
            ],
        ],

        'inventory' => [
            'inventory-sources' => [
                'name' => 'Varsayılan',
            ],
        ],

        'shop' => [
            'theme-customizations' => [
                'image-carousel' => [
                    'name' => 'Image Carousel',

                    'sliders' => [
                        'title' => 'Get Ready For New Collection',
                    ],
                ],

                'offer-information' => [
                    'name' => 'Offer Information',

                    'content' => [
                        'title' => 'Get UP TO 40% OFF on your 1st order SHOP NOW',
                    ],
                ],

                'categories-collections' => [
                    'name' => 'Categories Collections',
                ],

                'new-products' => [
                    'name' => 'New Products',

                    'options' => [
                        'title' => 'New Products',
                    ],
                ],

                'top-collections' => [
                    'name' => 'Top Collections',

                    'content' => [
                        'sub-title-1' => 'Our Collections',
                        'sub-title-2' => 'Our Collections',
                        'sub-title-3' => 'Our Collections',
                        'sub-title-4' => 'Our Collections',
                        'sub-title-5' => 'Our Collections',
                        'sub-title-6' => 'Our Collections',
                        'title'       => 'The game with our new additions!',
                    ],
                ],

                'bold-collections' => [
                    'name' => 'Bold Collections',

                    'content' => [
                        'btn-title'   => 'View All',
                        'description' => 'Introducing Our New Bold Collections! Elevate your style with daring designs and vibrant statements. Explore striking patterns and bold colors that redefine your wardrobe. Get ready to embrace the extraordinary!',
                        'title'       => 'Get Ready for our new Bold Collections!',
                    ],
                ],

                'featured-collections' => [
                    'name' => 'Featured Collections',

                    'options' => [
                        'title' => 'Featured Products',
                    ],
                ],

                'game-container' => [
                    'name' => 'Game Container',

                    'content' => [
                        'sub-title-1' => 'Our Collections',
                        'sub-title-2' => 'Our Collections',
                        'title'       => 'The game with our new additions!',
                    ],
                ],

                'all-products' => [
                    'name' => 'All Products',

                    'options' => [
                        'title' => 'All Products',
                    ],
                ],

                'footer-links' => [
                    'name' => 'Footer Links',

                    'options' => [
                        'about-us'         => 'About Us',
                        'contact-us'       => 'Contact Us',
                        'customer-service' => 'Customer Service',
                        'privacy-policy'   => 'Privacy Policy',
                        'payment-policy'   => 'Payment Policy',
                        'return-policy'    => 'Return Policy',
                        'refund-policy'    => 'Refund Policy',
                        'shipping-policy'  => 'Shipping Policy',
                        'terms-of-use'     => 'Terms of Use',
                        'terms-conditions' => 'Terms & Conditions',
                        'whats-new'        => 'What\'s New',
                    ],
                ],
            ],
        ],

        'user' => [
            'users' => [
                'name' => 'Example',
            ],

            'roles' => [
                'description' => 'This role users will have all the access',
                'name'        => 'Administrator',
            ],
        ],
    ],

    'installer' => [
        'index' => [
            'server-requirements' => [
                'calendar'    => 'Takvim',
                'ctype'       => 'cType',
                'curl'        => 'cURL',
                'dom'         => 'dom',
                'fileinfo'    => 'fileInfo',
                'filter'      => 'Filtre',
                'gd'          => 'GD',
                'hash'        => 'Hash',
                'intl'        => 'intl',
                'json'        => 'JSON',
                'mbstring'    => 'mbstring',
                'openssl'     => 'openssl',
                'php'         => 'PHP',
                'php-version' => '8.1 veya daha yüksek',
                'pcre'        => 'pcre',
                'pdo'         => 'pdo',
                'session'     => 'oturum',
                'title'       => 'Sunucu Gereksinimleri',
                'tokenizer'   => 'tokenizer',
                'xml'         => 'XML',
            ],

            'environment-configuration' => [
                'application-name'    => 'Uygulama Adı',
                'arabic'              => 'Arapça',
                'bagisto'             => 'Bagisto',
                'bengali'             => 'Bengalce',
                'chinese-yuan'        => 'Çin Yuanı (CNY)',
                'chinese'             => 'Çince',
                'dirham'              => 'Dirhem (AED)',
                'default-url'         => 'Varsayılan URL',
                'default-url-link'    => 'https://localhost',
                'default-currency'    => 'Varsayılan Para Birimi',
                'default-timezone'    => 'Varsayılan Zaman Dilimi',
                'default-locale'      => 'Varsayılan Yerel Ayar',
                'dutch'               => 'Hollandaca',
                'database-connection' => 'Veritabanı Bağlantısı',
                'database-hostname'   => 'Veritabanı Sunucu Adı',
                'database-port'       => 'Veritabanı Portu',
                'database-name'       => 'Veritabanı Adı',
                'database-username'   => 'Veritabanı Kullanıcı Adı',
                'database-prefix'     => 'Veritabanı Öneki',
                'database-password'   => 'Veritabanı Parolası',
                'euro'                => 'Euro (EUR)',
                'english'             => 'İngilizce',
                'french'              => 'Fransızca',
                'hebrew'              => 'İbranice',
                'hindi'               => 'Hintçe',
                'iranian'             => 'İran Riyali (IRR)',
                'israeli'             => 'İsrail Şekeli (ILS)',
                'italian'             => 'İtalyanca',
                'japanese-yen'        => 'Japon Yeni (JPY)',
                'japanese'            => 'Japonca',
                'mysql'               => 'Mysql',
                'pgsql'               => 'pgSQL',
                'pound'               => 'Pound Sterling (GBP)',
                'persian'             => 'Farsça',
                'polish'              => 'Lehçe',
                'portuguese'          => 'Brezilya Portekizcesi',
                'rupee'               => 'Hindistan Rupisi (INR)',
                'russian-ruble'       => 'Rus Rublesi (RUB)',
                'russian'             => 'Rusça',
                'sqlsrv'              => 'SQLSRV',
                'saudi'               => 'Suudi Riyali (SAR)',
                'spanish'             => 'İspanyolca',
                'sinhala'             => 'Sinhala',
                'title'               => 'Ortam Yapılandırması',
                'turkish-lira'        => 'Türk Lirası (TRY)',
                'turkish'             => 'Türkçe',
                'usd'                 => 'ABD Doları (USD)',
                'ukrainian-hryvnia'   => 'Ukrayna Grivnası (UAH)',
                'ukrainian'           => 'Ukraynaca',
            ],

            'ready-for-installation' => [
                'create-database-table'   => 'Veritabanı tablosu oluştur',
                'install'                 => 'Kurulum',
                'install-info'            => 'Kurulum İçin Bagisto',
                'install-info-button'     => 'Aşağıdaki düğmeye tıklayarak',
                'populate-database-table' => 'Veritabanı tablolarını doldur',
                'start-installation'      => 'Kurulumu Başlat',
                'title'                   => 'Kurulum İçin Hazır',
            ],

            'installation-processing' => [
                'bagisto'          => 'Bagisto Kurulumu',
                'bagisto-info'     => 'Veritabanı tabloları oluşturuluyor, bu birkaç dakika sürebilir',
                'title'            => 'Kurulum',
            ],

            'create-administrator' => [
                'admin'            => 'Yönetici',
                'bagisto'          => 'Bagisto',
                'confirm-password' => 'Parolayı Onayla',
                'email'            => 'E-posta',
                'email-address'    => 'admin@example.com',
                'password'         => 'Parola',
                'title'            => 'Yönetici Oluştur',
            ],

            'email-configuration' => [
                'encryption'           => 'Şifreleme',
                'enter-username'       => 'Kullanıcı Adı Girin',
                'enter-password'       => 'Parola Girin',
                'outgoing-mail-server' => 'Giden E-posta Sunucusu',
                'outgoing-email'       => 'smpt.mailtrap.io',
                'password'             => 'Parola',
                'store-email'          => 'Mağaza E-posta Adresi',
                'enter-store-email'    => 'Mağaza E-posta Adresini Girin',
                'server-port'          => 'Sunucu Portu',
                'server-port-code'     => '3306',
                'title'                => 'E-posta Yapılandırma',
                'username'             => 'Kullanıcı Adı',
            ],

            'installation-completed' => [
                'admin-panel'                => 'Yönetici Paneli',
                'bagisto-forums'             => 'Bagisto Forumu',
                'customer-panel'             => 'Müşteri Paneli',
                'explore-bagisto-extensions' => 'Bagisto Uzantılarını Keşfedin',
                'title'                      => 'Kurulum Tamamlandı',
                'title-info'                 => 'Bagisto sisteminize başarıyla kuruldu.',
            ],

            'bagisto-logo'             => 'Bagisto Logosu',
            'back'                     => 'Geri',
            'bagisto-info'             => 'Topluluk Projesi',
            'bagisto'                  => 'Bagisto',
            'continue'                 => 'Devam et',
            'installation-title'       => 'Kurulum\'a Hoş Geldiniz',
            'installation-info'        => 'Sizi burada görmekten mutluluk duyuyoruz!',
            'installation-description' => 'Bagisto kurulumu genellikle birkaç adım içerir. İşte Bagisto kurulum sürecinin genel bir taslağı:',
            'skip'                     => 'Atla',
            'save-configuration'       => 'Yapılandırmayı Kaydet',
            'title'                    => 'Bagisto Kurulum Aracı',
            'webkul'                   => 'Webkul',
        ],
    ],
];
