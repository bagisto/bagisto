<?php

return [
    'seeders' => [
        'attribute' => [
            'attribute-families' => [
                'default' => 'Varsayılan',
            ],

            'attribute-groups' => [
                'description'      => 'Açıklama',
                'general'          => 'Genel',
                'inventories'      => 'Envanterler',
                'meta-description' => 'Meta Açıklama',
                'price'            => 'Fiyat',
                'settings'         => 'Ayarlar',
                'shipping'         => 'Nakliye',
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
                'special-price'        => 'Özel Fiyat',
                'special-price-from'   => 'Özel Fiyat Başlangıç',
                'special-price-to'     => 'Özel Fiyat Bitiş',
                'status'               => 'Durum',
                'tax-category'         => 'Vergi Kategorisi',
                'url-key'              => 'URL Anahtarı',
                'visible-individually' => 'Tek Tek Görünür',
                'weight'               => 'Ağırlık',
                'width'                => 'Genişlik',
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

                'contact-us' => [
                    'content' => 'Bize Ulaşın Sayfa İçeriği',
                    'title'   => 'Bize Ulaşın',
                ],

                'customer-service' => [
                    'content' => 'Müşteri Hizmetleri Sayfa İçeriği',
                    'title'   => 'Müşteri Hizmetleri',
                ],

                'payment-policy' => [
                    'content' => 'Ödeme Politikası Sayfa İçeriği',
                    'title'   => 'Ödeme Politikası',
                ],

                'privacy-policy' => [
                    'content' => 'Gizlilik Politikası Sayfa İçeriği',
                    'title'   => 'Gizlilik Politikası',
                ],

                'refund-policy' => [
                    'content' => 'İade Politikası Sayfa İçeriği',
                    'title'   => 'İade Politikası',
                ],

                'return-policy' => [
                    'content' => 'İade Politikası Sayfa İçeriği',
                    'title'   => 'İade Politikası',
                ],

                'shipping-policy' => [
                    'content' => 'Kargo Politikası Sayfa İçeriği',
                    'title'   => 'Kargo Politikası',
                ],

                'terms-conditions' => [
                    'content' => 'Şartlar ve Koşullar Sayfa İçeriği',
                    'title'   => 'Şartlar ve Koşullar',
                ],

                'terms-of-use' => [
                    'content' => 'Kullanım Koşulları Sayfa İçeriği',
                    'title'   => 'Kullanım Koşulları',
                ],

                'whats-new' => [
                    'content' => 'Yenilikler Sayfa İçeriği',
                    'title'   => 'Yenilikler',
                ],
            ],
        ],

        'core' => [
            'channels' => [
                'name'             => 'Varsayılan',
                'meta-title'       => 'Demo Mağaza',
                'meta-keywords'    => 'Demo Mağaza Meta Anahtar Kelimeler',
                'meta-description' => 'Demo Mağaza Meta Açıklama',
            ],

            'currencies' => [
                'AED' => 'Birleşik Arap Emirlikleri Dirhemi',
                'ARS' => 'Arjantin Pesosu',
                'AUD' => 'Avustralya Doları',
                'BDT' => 'Bangladeş Takası',
                'BRL' => 'Brezilya Reali',
                'CAD' => 'Kanada Doları',
                'CHF' => 'İsviçre Frangı',
                'CLP' => 'Şili Pesosu',
                'CNY' => 'Çin Yuanı',
                'COP' => 'Kolombiya Pesosu',
                'CZK' => 'Çek Korunası',
                'DKK' => 'Danimarka Kronu',
                'DZD' => 'Cezayir Dinarı',
                'EGP' => 'Mısır Lirası',
                'EUR' => 'Euro',
                'FJD' => 'Fiji Doları',
                'GBP' => 'İngiliz Sterlini',
                'HKD' => 'Hong Kong Doları',
                'HUF' => 'Macar Forinti',
                'IDR' => 'Endonezya Rupisi',
                'ILS' => 'Yeni İsrail Şekeli',
                'INR' => 'Hindistan Rupisi',
                'JOD' => 'Ürdün Dinarı',
                'JPY' => 'Japon Yeni',
                'KRW' => 'Güney Kore Wonu',
                'KWD' => 'Kuveyt Dinarı',
                'KZT' => 'Kazak Tengesi',
                'LBP' => 'Lübnan Lirası',
                'LKR' => 'Sri Lanka Rupisi',
                'LYD' => 'Libya Dinarı',
                'MAD' => 'Fas Dirhemi',
                'MUR' => 'Mauritius Rupisi',
                'MXN' => 'Meksika Pesosu',
                'MYR' => 'Malezya Ringgiti',
                'NGN' => 'Nijerya Nairası',
                'NOK' => 'Norveç Kronu',
                'NPR' => 'Nepal Rupisi',
                'NZD' => 'Yeni Zelanda Doları',
                'OMR' => 'Umman Riyali',
                'PAB' => 'Panama Balboası',
                'PEN' => 'Peru Nuevo Solü',
                'PHP' => 'Filipin Pesosu',
                'PKR' => 'Pakistan Rupisi',
                'PLN' => 'Polonya Zlotisi',
                'PYG' => 'Paraguay Guaranisi',
                'QAR' => 'Katar Riyali',
                'RON' => 'Romanya Leyi',
                'RUB' => 'Rus Rublesi',
                'SAR' => 'Suudi Arabistan Riyali',
                'SEK' => 'İsveç Kronu',
                'SGD' => 'Singapur Doları',
                'THB' => 'Tayland Bahtı',
                'TND' => 'Tunus Dinarı',
                'TRY' => 'Türk Lirası',
                'TWD' => 'Yeni Tayvan Doları',
                'UAH' => 'Ukrayna Grivnası',
                'USD' => 'Amerikan Doları',
                'UZS' => 'Özbek Somu',
                'VEF' => 'Venezuela Bolivarı',
                'VND' => 'Vietnam Dongu',
                'XAF' => 'CFA Frangı BEAC',
                'XOF' => 'CFA Frangı BCEAO',
                'ZAR' => 'Güney Afrika Randı',
                'ZMW' => 'Zambiya Kvaçası',
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

        'customer' => [
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

        'shop' => [
            'theme-customizations' => [
                'all-products' => [
                    'name' => 'Tüm Ürünler',

                    'options' => [
                        'title' => 'Tüm Ürünler',
                    ],
                ],

                'bold-collections' => [
                    'content' => [
                        'btn-title'   => 'Koleksiyonlara Göz At',
                        'description' => 'Yeni Cesur Koleksiyonlarımızı Tanıtıyoruz! Cesur tasarımlar ve canlı ifadelerle tarzınızı yükseltin. Gardırobunuzu yeniden tanımlayan çarpıcı desenler ve cesur renklere keşfedin. Olağanüstüye hazır olun!',
                        'title'       => 'Yeni Cesur Koleksiyonlarımıza Hazır Olun!',
                    ],

                    'name' => 'Cesur Koleksiyonlar',
                ],

                'categories-collections' => [
                    'name' => 'Kategori Koleksiyonları',
                ],

                'featured-collections' => [
                    'name' => 'Öne Çıkan Koleksiyonlar',

                    'options' => [
                        'title' => 'Öne Çıkan Ürünler',
                    ],
                ],

                'footer-links' => [
                    'name' => 'Footer Links',

                    'options' => [
                        'about-us'         => 'Hakkımızda',
                        'contact-us'       => 'Bize Ulaşın',
                        'customer-service' => 'Müşteri Hizmetleri',
                        'payment-policy'   => 'Ödeme Politikası',
                        'privacy-policy'   => 'Gizlilik Politikası',
                        'refund-policy'    => 'İade Politikası',
                        'return-policy'    => 'İade Politikası',
                        'shipping-policy'  => 'Kargo Politikası',
                        'terms-conditions' => 'Şartlar ve Koşullar',
                        'terms-of-use'     => 'Kullanım Koşulları',
                        'whats-new'        => 'Yenilikler',
                    ],
                ],

                'game-container' => [
                    'content' => [
                        'sub-title-1' => 'Koleksiyonlarımız',
                        'sub-title-2' => 'Koleksiyonlarımız',
                        'title'       => 'Yeni eklemelerimizle oyun!',
                    ],

                    'name' => 'Oyun Konteyneri',
                ],

                'image-carousel' => [
                    'name' => 'Resim Karuseli',

                    'sliders' => [
                        'title' => 'Yeni Koleksiyona Hazır Olun',
                    ],
                ],

                'new-products' => [
                    'name' => 'Yeni Ürünler',

                    'options' => [
                        'title' => 'Yeni Ürünler',
                    ],
                ],

                'offer-information' => [
                    'content' => [
                        'title' => 'İlk siparişinizde %40\'a kadar İNDİRİM alın HEMEN ALIŞVERİŞ YAPIN',
                    ],

                    'name' => 'Teklif Bilgisi',
                ],

                'services-content' => [
                    'description' => [
                        'emi-available-info'   => 'Tüm ana kredi kartlarında ücretsiz EMI mevcut',
                        'free-shipping-info'   => 'Tüm siparişlerde ücretsiz kargo keyfini çıkarın',
                        'product-replace-info' => 'Kolay ürün değiştirme mevcut!',
                        'time-support-info'    => 'Sohbet ve e-posta yoluyla adanmış 7/24 destek',
                    ],

                    'name' => 'Hizmet İçeriği',

                    'title' => [
                        'emi-available'   => 'EMI Mevcut',
                        'free-shipping'   => 'Ücretsiz Kargo',
                        'product-replace' => 'Ürün Değiştirme',
                        'time-support'    => '7/24 Destek',
                    ],
                ],

                'top-collections' => [
                    'content' => [
                        'sub-title-1' => 'Koleksiyonlarımız',
                        'sub-title-2' => 'Koleksiyonlarımız',
                        'sub-title-3' => 'Koleksiyonlarımız',
                        'sub-title-4' => 'Koleksiyonlarımız',
                        'sub-title-5' => 'Koleksiyonlarımız',
                        'sub-title-6' => 'Koleksiyonlarımız',
                        'title'       => 'Yeni eklemelerimizle oyun!',
                    ],

                    'name' => 'En İyi Koleksiyonlar',
                ],
            ],
        ],

        'user' => [
            'roles' => [
                'description' => 'Bu rol kullanıcılara tüm erişimi sağlar',
                'name'        => 'Yönetici',
            ],

            'users' => [
                'name' => 'Örnek',
            ],
        ],
    ],

    'installer' => [
        'index' => [
            'create-administrator' => [
                'admin'            => 'Yönetici',
                'bagisto'          => 'Bagisto',
                'confirm-password' => 'Parolayı Onayla',
                'email'            => 'E-posta',
                'email-address'    => 'admin@example.com',
                'password'         => 'Parola',
                'title'            => 'Yönetici Oluştur',
            ],

            'environment-configuration' => [
                'algerian-dinar'              => 'Cezayir Dinarı (DZD)',
                'allowed-currencies'          => 'İzin Verilen Para Birimleri',
                'allowed-locales'             => 'İzin Verilen Lokaller',
                'application-name'            => 'Uygulama Adı',
                'argentine-peso'              => 'Arjantin Pezosu (ARS)',
                'australian-dollar'           => 'Avustralya Doları (AUD)',
                'bagisto'                     => 'Bagisto',
                'bangladeshi-taka'            => 'Bangladeş Takası (BDT)',
                'brazilian-real'              => 'Brezilya Reali (BRL)',
                'british-pound-sterling'      => 'İngiliz Sterlini (GBP)',
                'canadian-dollar'             => 'Kanada Doları (CAD)',
                'cfa-franc-bceao'             => 'CFA Frank BCEAO (XOF)',
                'cfa-franc-beac'              => 'CFA Frank BEAC (XAF)',
                'chilean-peso'                => 'Şili Pesosu (CLP)',
                'chinese-yuan'                => 'Çin Yuanı (CNY)',
                'colombian-peso'              => 'Kolombiya Pesosu (COP)',
                'czech-koruna'                => 'Çek Korunası (CZK)',
                'danish-krone'                => 'Danimarka Kronu (DKK)',
                'database-connection'         => 'Veritabanı Bağlantısı',
                'database-hostname'           => 'Veritabanı Sunucu Adı',
                'database-name'               => 'Veritabanı Adı',
                'database-password'           => 'Veritabanı Parolası',
                'database-port'               => 'Veritabanı Bağlantı Noktası',
                'database-prefix'             => 'Veritabanı Öneki',
                'database-username'           => 'Veritabanı Kullanıcı Adı',
                'default-currency'            => 'Varsayılan Para Birimi',
                'default-locale'              => 'Varsayılan Lokal',
                'default-timezone'            => 'Varsayılan Zaman Dilimi',
                'default-url'                 => 'Varsayılan URL',
                'default-url-link'            => 'https://localhost',
                'egyptian-pound'              => 'Mısır Lirası (EGP)',
                'euro'                        => 'Euro (EUR)',
                'fijian-dollar'               => 'Fiji Doları (FJD)',
                'hong-kong-dollar'            => 'Hong Kong Doları (HKD)',
                'hungarian-forint'            => 'Macar Forinti (HUF)',
                'indian-rupee'                => 'Hint Rupisi (INR)',
                'indonesian-rupiah'           => 'Endonezya Rupisi (IDR)',
                'israeli-new-shekel'          => 'İsrail Yeni Şekeli (ILS)',
                'japanese-yen'                => 'Japon Yeni (JPY)',
                'jordanian-dinar'             => 'Ürdün Dinarı (JOD)',
                'kazakhstani-tenge'           => 'Kazakistan Tengesi (KZT)',
                'kuwaiti-dinar'               => 'Kuveyt Dinarı (KWD)',
                'lebanese-pound'              => 'Lübnan Lirası (LBP)',
                'libyan-dinar'                => 'Libya Dinarı (LYD)',
                'malaysian-ringgit'           => 'Malezya Ringiti (MYR)',
                'mauritian-rupee'             => 'Mauritius Rupisi (MUR)',
                'mexican-peso'                => 'Meksika Pesosu (MXN)',
                'moroccan-dirham'             => 'Fas Dirhemi (MAD)',
                'mysql'                       => 'MySQL',
                'nepalese-rupee'              => 'Nepal Rupisi (NPR)',
                'new-taiwan-dollar'           => 'Yeni Tayvan Doları (TWD)',
                'new-zealand-dollar'          => 'Yeni Zelanda Doları (NZD)',
                'nigerian-naira'              => 'Nijerya Nairası (NGN)',
                'norwegian-krone'             => 'Norveç Kronu (NOK)',
                'omani-rial'                  => 'Umman Riyali (OMR)',
                'pakistani-rupee'             => 'Pakistan Rupisi (PKR)',
                'panamanian-balboa'           => 'Panama Balboası (PAB)',
                'paraguayan-guarani'          => 'Paraguay Guaranisi (PYG)',
                'peruvian-nuevo-sol'          => 'Peru Nuevo Solu (PEN)',
                'pgsql'                       => 'PgSQL',
                'philippine-peso'             => 'Filipinler Pesosu (PHP)',
                'polish-zloty'                => 'Polonya Zlotisi (PLN)',
                'qatari-rial'                 => 'Katar Riyali (QAR)',
                'romanian-leu'                => 'Romanya Leyi (RON)',
                'russian-ruble'               => 'Rus Rublesi (RUB)',
                'saudi-riyal'                 => 'Suudi Riyali (SAR)',
                'select-timezone'             => 'Zaman Dilimi Seç',
                'singapore-dollar'            => 'Singapur Doları (SGD)',
                'south-african-rand'          => 'Güney Afrika Randı (ZAR)',
                'south-korean-won'            => 'Güney Kore Wonu (KRW)',
                'sqlsrv'                      => 'SQLSRV',
                'sri-lankan-rupee'            => 'Sri Lanka Rupisi (LKR)',
                'swedish-krona'               => 'İsveç Kronu (SEK)',
                'swiss-franc'                 => 'İsviçre Frangı (CHF)',
                'thai-baht'                   => 'Tayland Bahtı (THB)',
                'title'                       => 'Mağaza Yapılandırması',
                'tunisian-dinar'              => 'Tunus Dinarı (TND)',
                'turkish-lira'                => 'Türk Lirası (TRY)',
                'ukrainian-hryvnia'           => 'Ukrayna Grivnası (UAH)',
                'united-arab-emirates-dirham' => 'Birleşik Arap Emirlikleri Dirhemi (AED)',
                'united-states-dollar'        => 'Amerikan Doları (USD)',
                'uzbekistani-som'             => 'Özbekistan Somu (UZS)',
                'venezuelan-bolívar'          => 'Venezuela Bolivarı (VEF)',
                'vietnamese-dong'             => 'Vietnam Dongu (VND)',
                'warning-message'             => 'Dikkat! Varsayılan sistem dili ve varsayılan para birimi ayarlarınız kalıcıdır ve bir daha asla değiştirilemez.',
                'zambian-kwacha'              => 'Zambiya Kvaçası (ZMW)',
            ],

            'installation-processing' => [
                'bagisto'          => 'Bagisto Kurulumu',
                'bagisto-info'     => 'Veritabanı tabloları oluşturuluyor, bu birkaç dakika sürebilir',
                'title'            => 'Kurulum',
            ],

            'installation-completed' => [
                'admin-panel'                => 'Yönetici Paneli',
                'bagisto-forums'             => 'Bagisto Forumu',
                'customer-panel'             => 'Müşteri Paneli',
                'explore-bagisto-extensions' => 'Bagisto Uzantılarını Keşfedin',
                'title'                      => 'Kurulum Tamamlandı',
                'title-info'                 => 'Bagisto sisteminize başarıyla kuruldu.',
            ],

            'ready-for-installation' => [
                'create-databsae-table'   => 'Veritabanı tablosu oluştur',
                'install'                 => 'Yükleme',
                'install-info'            => 'Kurulum için Bagisto',
                'install-info-button'     => 'Aşağıdaki düğmeye tıklayın',
                'populate-database-table' => 'Veritabanı tablolarını doldur',
                'start-installation'      => 'Kurulumu Başlat',
                'title'                   => 'Kurulum için Hazır',
            ],

            'start' => [
                'locale'        => 'Yerel',
                'main'          => 'Başlangıç',
                'select-locale' => 'Yerel Seçin',
                'title'         => 'Bagisto kurulumunuz',
                'welcome-title' => 'Bagisto 2.0\'a hoş geldiniz.',
            ],

            'server-requirements' => [
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
                'php'         => 'PHP',
                'php-version' => '8.1 veya üstü',
                'session'     => 'oturum',
                'title'       => 'Sunucu Gereksinimleri',
                'tokenizer'   => 'tokenizer',
                'xml'         => 'XML',
            ],

            'arabic'                   => 'Arapça',
            'back'                     => 'Geri',
            'bagisto'                  => 'Bagisto',
            'bagisto-info'             => 'Webkul tarafından geliştirilen bir Topluluk Projesi',
            'bagisto-logo'             => 'Bagisto Logosu',
            'bengali'                  => 'Bengalce',
            'chinese'                  => 'Çince',
            'continue'                 => 'Devam Et',
            'dutch'                    => 'Hollandaca',
            'english'                  => 'İngilizce',
            'french'                   => 'Fransızca',
            'german'                   => 'Almanca',
            'hebrew'                   => 'İbranice',
            'hindi'                    => 'Hintçe',
            'installation-description' => 'Bagisto kurulumu genellikle birkaç adım içerir. İşte Bagisto kurulum sürecinin genel bir taslağı:',
            'installation-info'        => 'Sizi burada görmekten mutluluk duyuyoruz!',
            'installation-title'       => 'Kurulum\'a Hoş Geldiniz',
            'italian'                  => 'İtalyanca',
            'japanese'                 => 'Japonca',
            'persian'                  => 'Farsça',
            'polish'                   => 'Lehçe',
            'portuguese'               => 'Brezilya Portekizcesi',
            'russian'                  => 'Rusça',
            'sinhala'                  => 'Sinhala',
            'spanish'                  => 'İspanyolca',
            'title'                    => 'Bagisto Kurulum Sihirbazı',
            'turkish'                  => 'Türkçe',
            'ukrainian'                => 'Ukraynaca',
            'webkul'                   => 'Webkul',
        ],
    ],
];
