<?php

return [
    'seeders'   => [
        'attribute' => [
            'attribute-families' => [
                'default' => 'Varsayılan',
            ],

            'attribute-groups'   => [
                'description'       => 'Açıklama',
                'general'           => 'Genel',
                'inventories'       => 'Envanterler',
                'meta-description'  => 'Meta Açıklama',
                'price'             => 'Fiyat',
                'settings'          => 'Ayarlar',
                'shipping'          => 'Nakliye',
            ],

            'attributes'         => [
                'brand'                => 'Marka',
                'color'                => 'Renk',
                'cost'                 => 'Maliyet',
                'description'          => 'Açıklama',
                'featured'             => 'Öne Çıkan',
                'guest-checkout'       => 'Misafir Ödeme',
                'height'               => 'Yükseklik',
                'length'               => 'Uzunluk',
                'manage-stock'         => 'Stoğu Yönet',
                'meta-description'     => 'Meta Açıklama',
                'meta-keywords'        => 'Meta Anahtar Kelimeler',
                'meta-title'           => 'Meta Başlık',
                'name'                 => 'Ad',
                'new'                  => 'Yeni',
                'price'                => 'Fiyat',
                'product-number'       => 'Ürün Numarası',
                'short-description'    => 'Kısa Açıklama',
                'size'                 => 'Boyut',
                'sku'                  => 'Stok Kodu',
                'special-price-from'   => 'Özel Fiyat Başlangıç',
                'special-price-to'     => 'Özel Fiyat Bitiş',
                'special-price'        => 'Özel Fiyat',
                'status'               => 'Durum',
                'tax-category'         => 'Vergi Kategorisi',
                'url-key'              => 'URL Anahtarı',
                'visible-individually' => 'Tek Tek Görünür',
                'weight'               => 'Ağırlık',
                'width'                => 'Genişlik',
            ],

            'attribute-options'  => [
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

        'category'  => [
            'categories' => [
                'description' => 'Kök Kategori Açıklaması',
                'name'        => 'Kök',
            ],
        ],

        'cms'       => [
            'pages' => [
                'about-us'         => [
                    'content' => 'Hakkımızda Sayfası İçeriği',
                    'title'   => 'Hakkımızda',
                ],

                'contact-us'       => [
                    'content' => 'Bize Ulaşın Sayfa İçeriği',
                    'title'   => 'Bize Ulaşın',
                ],

                'customer-service' => [
                    'content' => 'Müşteri Hizmetleri Sayfa İçeriği',
                    'title'   => 'Müşteri Hizmetleri',
                ],

                'payment-policy'   => [
                    'content' => 'Ödeme Politikası Sayfa İçeriği',
                    'title'   => 'Ödeme Politikası',
                ],

                'privacy-policy'   => [
                    'content' => 'Gizlilik Politikası Sayfa İçeriği',
                    'title'   => 'Gizlilik Politikası',
                ],

                'refund-policy'    => [
                    'content' => 'İade Politikası Sayfa İçeriği',
                    'title'   => 'İade Politikası',
                ],

                'return-policy'    => [
                    'content' => 'İade Politikası Sayfa İçeriği',
                    'title'   => 'İade Politikası',
                ],

                'shipping-policy'  => [
                    'content' => 'Kargo Politikası Sayfa İçeriği',
                    'title'   => 'Kargo Politikası',
                ],

                'terms-conditions' => [
                    'content' => 'Şartlar ve Koşullar Sayfa İçeriği',
                    'title'   => 'Şartlar ve Koşullar',
                ],

                'terms-of-use'     => [
                    'content' => 'Kullanım Koşulları Sayfa İçeriği',
                    'title'   => 'Kullanım Koşulları',
                ],

                'whats-new'        => [
                    'content' => 'Yenilikler Sayfa İçeriği',
                    'title'   => 'Yenilikler',
                ],
            ],
        ],

        'core'      => [
            'channels'   => [
                'meta-description' => 'Demo Mağaza Meta Açıklama',
                'meta-keywords'    => 'Demo Mağaza Meta Anahtar Kelimeler',
                'meta-title'       => 'Demo Mağaza',
                'name'             => 'Varsayılan',
            ],

            'currencies' => [
                'AED' => 'Dirhem',
                'AFN' => 'İsrail Şekeli',
                'CNY' => 'Çin Yuanı',
                'EUR' => 'EURO',
                'GBP' => 'İngiliz Sterlini',
                'INR' => 'Hindistan Rupisi',
                'IRR' => 'İran Riyali',
                'JPY' => 'Japon Yeni',
                'RUB' => 'Rus Rublesi',
                'SAR' => 'Suudi Riyal',
                'TRY' => 'Türk Lirası',
                'UAH' => 'Ukrayna Grivnası',
                'USD' => 'ABD Doları',
            ],

            'locales'    => [
                'ar'    => 'Arapça',
                'bn'    => 'Bengali',
                'de'    => 'Almanca',
                'en'    => 'İngilizce',
                'es'    => 'İspanyolca',
                'fa'    => 'Farsça',
                'fr'    => 'Fransızca',
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

        'customer'  => [
            'customer-groups' => [
                'general'   => 'Genel',
                'guest'     => 'Misafir',
                'wholesale' => 'Toptan',
            ],
        ],

        'inventory' => [
            'inventory-sources' => [
                'name' => 'Varsayılan',
            ],
        ],

        'shop'      => [
            'theme-customizations' => [
                'all-products'           => [
                    'name'    => 'All Products',

                    'options' => [
                        'title' => 'All Products',
                    ],
                ],

                'bold-collections'       => [
                    'content' => [
                        'btn-title'   => 'View All',
                        'description' => 'Introducing Our New Bold Collections! Elevate your style with daring designs and vibrant statements. Explore striking patterns and bold colors that redefine your wardrobe. Get ready to embrace the extraordinary!',
                        'title'       => 'Get Ready for our new Bold Collections!',
                    ],

                    'name'    => 'Bold Collections',
                ],

                'categories-collections' => [
                    'name' => 'Categories Collections',
                ],

                'featured-collections'   => [
                    'name'    => 'Featured Collections',

                    'options' => [
                        'title' => 'Featured Products',
                    ],
                ],

                'footer-links'           => [
                    'name'    => 'Footer Links',

                    'options' => [
                        'about-us'         => 'About Us',
                        'contact-us'       => 'Contact Us',
                        'customer-service' => 'Customer Service',
                        'payment-policy'   => 'Payment Policy',
                        'privacy-policy'   => 'Privacy Policy',
                        'refund-policy'    => 'Refund Policy',
                        'return-policy'    => 'Return Policy',
                        'shipping-policy'  => 'Shipping Policy',
                        'terms-conditions' => 'Terms & Conditions',
                        'terms-of-use'     => 'Terms of Use',
                        'whats-new'        => 'What\'s New',
                    ],
                ],

                'game-container'         => [
                    'content' => [
                        'sub-title-1' => 'Our Collections',
                        'sub-title-2' => 'Our Collections',
                        'title'       => 'The game with our new additions!',
                    ],

                    'name'    => 'Game Container',
                ],

                'image-carousel'         => [
                    'name'    => 'Image Carousel',

                    'sliders' => [
                        'title' => 'Get Ready For New Collection',
                    ],
                ],

                'new-products'           => [
                    'name'    => 'New Products',

                    'options' => [
                        'title' => 'New Products',
                    ],
                ],

                'offer-information'      => [
                    'content' => [
                        'title' => 'Get UP TO 40% OFF on your 1st order SHOP NOW',
                    ],

                    'name'    => 'Offer Information',
                ],

                'services-content'       => [
                    'description' => [
                        'emi-available-info'   => 'Tüm ana kredi kartlarında ücretsiz EMI mevcut',
                        'free-shipping-info'   => 'Tüm siparişlerde ücretsiz kargo keyfini çıkarın',
                        'product-replace-info' => 'Kolay ürün değiştirme mevcut!',
                        'time-support-info'    => 'Sohbet ve e-posta yoluyla adanmış 7/24 destek',
                    ],

                    'name'        => 'Hizmet İçeriği',

                    'title'       => [
                        'emi-available'   => 'EMI Mevcut',
                        'free-shipping'   => 'Ücretsiz Kargo',
                        'product-replace' => 'Ürün Değiştirme',
                        'time-support'    => '7/24 Destek',
                    ],
                ],

                'top-collections'        => [
                    'content' => [
                        'sub-title-1' => 'Our Collections',
                        'sub-title-2' => 'Our Collections',
                        'sub-title-3' => 'Our Collections',
                        'sub-title-4' => 'Our Collections',
                        'sub-title-5' => 'Our Collections',
                        'sub-title-6' => 'Our Collections',
                        'title'       => 'The game with our new additions!',
                    ],

                    'name'    => 'Top Collections',
                ],
            ],
        ],

        'user'      => [
            'roles' => [
                'description' => 'This role users will have all the access',
                'name'        => 'Administrator',
            ],

            'users' => [
                'name' => 'Example',
            ],
        ],
    ],

    'installer' => [
        'index' => [
            'create-administrator'      => [
                'admin'            => 'Yönetici',
                'bagisto'          => 'Bagisto',
                'confirm-password' => 'Parolayı Onayla',
                'email-address'    => 'admin@example.com',
                'email'            => 'E-posta',
                'password'         => 'Parola',
                'title'            => 'Yönetici Oluştur',
            ],

            'environment-configuration' => [
                'allowed-currencies'  => 'İzin verilen para birimleri',
                'allowed-locales'     => 'İzin verilen yerel ayarlar',
                'application-name'    => 'Uygulama Adı',
                'bagisto'             => 'Bagisto',
                'chinese-yuan'        => 'Çin Yuanı (CNY)',
                'database-connection' => 'Veritabanı Bağlantısı',
                'database-hostname'   => 'Veritabanı Ana Bilgisayarı Adı',
                'database-name'       => 'Veritabanı Adı',
                'database-password'   => 'Veritabanı Şifresi',
                'database-port'       => 'Veritabanı Bağlantı Noktası',
                'database-prefix'     => 'Veritabanı Öneki',
                'database-username'   => 'Veritabanı Kullanıcı Adı',
                'default-currency'    => 'Varsayılan Para Birimi',
                'default-locale'      => 'Varsayılan Yerel',
                'default-timezone'    => 'Varsayılan Zaman Dilimi',
                'default-url-link'    => 'https://localhost',
                'default-url'         => 'Varsayılan URL',
                'dirham'              => 'Dirham (AED)',
                'euro'                => 'Euro (EUR)',
                'iranian'             => 'İran Riyali (IRR)',
                'israeli'             => 'İsrail Şekeli (AFN)',
                'japanese-yen'        => 'Japon Yeni (JPY)',
                'mysql'               => 'Mysql',
                'pgsql'               => 'pgSQL',
                'pound'               => 'Sterlin (GBP)',
                'rupee'               => 'Hint Rupisi (INR)',
                'russian-ruble'       => 'Rus Rublesi (RUB)',
                'saudi'               => 'Suudi Riyali (SAR)',
                'select-timezone'     => 'Saat Dilimini Seçin',
                'sqlsrv'              => 'SQLSRV',
                'title'               => 'Ortam Yapılandırması',
                'turkish-lira'        => 'Türk Lirası (TRY)',
                'ukrainian-hryvnia'   => 'Ukrayna Grivnası (UAH)',
                'usd'                 => 'ABD Doları (USD)',
                'warning-message'     => 'Dikkat! Varsayılan sistem dilleri ve varsayılan para birimi ayarlarınız kalıcıdır ve bir daha asla değiştirilemez.',
            ],

            'installation-processing'   => [
                'bagisto-info'     => 'Veritabanı tabloları oluşturuluyor, bu birkaç dakika sürebilir',
                'bagisto'          => 'Bagisto Kurulumu',
                'title'            => 'Kurulum',
            ],

            'installation-completed'    => [
                'admin-panel'                => 'Yönetici Paneli',
                'bagisto-forums'             => 'Bagisto Forumu',
                'customer-panel'             => 'Müşteri Paneli',
                'explore-bagisto-extensions' => 'Bagisto Uzantılarını Keşfedin',
                'title-info'                 => 'Bagisto sisteminize başarıyla kuruldu.',
                'title'                      => 'Kurulum Tamamlandı',
            ],

            'ready-for-installation'    => [
                'create-databsae-table'   => 'Veritabanı tablosu oluştur',
                'install-info-button'     => 'Aşağıdaki düğmeye tıklayın',
                'install-info'            => 'Kurulum için Bagisto',
                'install'                 => 'Yükleme',
                'populate-database-table' => 'Veritabanı tablolarını doldur',
                'start-installation'      => 'Kurulumu Başlat',
                'title'                   => 'Kurulum için Hazır',
            ],

            'start'                     => [
                'locale'        => 'Yerel',
                'main'          => 'Başlangıç',
                'select-locale' => 'Yerel Seçin',
                'title'         => 'Bagisto kurulumunuz',
                'welcome-title' => 'Bagisto 2.0\'a hoş geldiniz.',
            ],

            'server-requirements'       => [
                'calendar'    => 'Takvim',
                'ctype'       => 'cType',
                'curl'        => 'cURL',
                'dom'         => 'dom',
                'fileinfo'    => 'Dosya Bilgisi',
                'filter'      => 'Filtre',
                'gd'          => 'GD',
                'hash'        => 'Hash',
                'intl'        => 'intl',
                'json'        => 'JSON',
                'mbstring'    => 'mbstring',
                'openssl'     => 'openssl',
                'pcre'        => 'pcre',
                'pdo'         => 'pdo',
                'php-version' => '8.1 veya üstü',
                'php'         => 'PHP',
                'session'     => 'oturum',
                'title'       => 'Sunucu Gereksinimleri',
                'tokenizer'   => 'tokenizer',
                'xml'         => 'XML',
            ],

            'arabic'                    => 'Arapça',
            'back'                      => 'Geri',
            'bagisto-info'              => 'Webkul tarafından geliştirilen bir Topluluk Projesi',
            'bagisto-logo'              => 'Bagisto Logosu',
            'bagisto'                   => 'Bagisto',
            'bengali'                   => 'Bengalce',
            'chinese'                   => 'Çince',
            'continue'                  => 'Devam Et',
            'dutch'                     => 'Hollandaca',
            'english'                   => 'İngilizce',
            'french'                    => 'Fransızca',
            'german'                    => 'Almanca',
            'hebrew'                    => 'İbranice',
            'hindi'                     => 'Hintçe',
            'installation-description'  => 'Bagisto kurulumu genellikle birkaç adım içerir. İşte Bagisto kurulum sürecinin genel bir taslağı:',
            'installation-info'         => 'Sizi burada görmekten mutluluk duyuyoruz!',
            'installation-title'        => 'Kurulum\'a Hoş Geldiniz',
            'italian'                   => 'İtalyanca',
            'japanese'                  => 'Japonca',
            'persian'                   => 'Farsça',
            'polish'                    => 'Lehçe',
            'portuguese'                => 'Brezilya Portekizcesi',
            'russian'                   => 'Rusça',
            'save-configuration'        => 'Yapılandırmayı Kaydet',
            'sinhala'                   => 'Sinhala',
            'skip'                      => 'Atla',
            'spanish'                   => 'İspanyolca',
            'title'                     => 'Bagisto Kurulum Sihirbazı',
            'turkish'                   => 'Türkçe',
            'ukrainian'                 => 'Ukraynaca',
            'webkul'                    => 'Webkul',
        ],
    ],
];
