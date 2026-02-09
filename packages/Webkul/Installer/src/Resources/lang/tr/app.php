<?php

return [
    'seeders' => [
        'attribute' => [
            'attribute-families' => [
                'default' => 'Varsayılan',
            ],

            'attribute-groups' => [
                'description' => 'Açıklama',
                'general' => 'Genel',
                'inventories' => 'Envanterler',
                'meta-description' => 'Meta Açıklama',
                'price' => 'Fiyat',
                'rma' => 'RMA',
                'settings' => 'Ayarlar',
                'shipping' => 'Nakliye',
            ],

            'attributes' => [
                'allow-rma' => 'RMA\'ya izin ver',
                'brand' => 'Marka',
                'color' => 'Renk',
                'cost' => 'Maliyet',
                'description' => 'Açıklama',
                'featured' => 'Öne Çıkan',
                'guest-checkout' => 'Misafir Ödeme',
                'height' => 'Yükseklik',
                'length' => 'Uzunluk',
                'manage-stock' => 'Stoğu Yönet',
                'meta-description' => 'Meta Açıklama',
                'meta-keywords' => 'Meta Anahtar Kelimeler',
                'meta-title' => 'Meta Başlık',
                'name' => 'Ad',
                'new' => 'Yeni',
                'price' => 'Fiyat',
                'product-number' => 'Ürün Numarası',
                'rma-rules' => 'RMA kuralları',
                'short-description' => 'Kısa Açıklama',
                'size' => 'Boyut',
                'sku' => 'Stok Kodu',
                'special-price' => 'Özel Fiyat',
                'special-price-from' => 'Özel Fiyat Başlangıç',
                'special-price-to' => 'Özel Fiyat Bitiş',
                'status' => 'Durum',
                'tax-category' => 'Vergi Kategorisi',
                'url-key' => 'URL Anahtarı',
                'visible-individually' => 'Tek Tek Görünür',
                'weight' => 'Ağırlık',
                'width' => 'Genişlik',
            ],

            'attribute-options' => [
                'black' => 'Siyah',
                'green' => 'Yeşil',
                'l' => 'L',
                'm' => 'M',
                'red' => 'Kırmızı',
                's' => 'S',
                'white' => 'Beyaz',
                'xl' => 'XL',
                'yellow' => 'Sarı',
            ],
        ],

        'category' => [
            'categories' => [
                'description' => 'Kök Kategori Açıklaması',
                'name' => 'Kök',
            ],
        ],

        'cms' => [
            'pages' => [
                'about-us' => [
                    'content' => 'Hakkımızda Sayfası İçeriği',
                    'title' => 'Hakkımızda',
                ],

                'contact-us' => [
                    'content' => 'Bize Ulaşın Sayfa İçeriği',
                    'title' => 'Bize Ulaşın',
                ],

                'customer-service' => [
                    'content' => 'Müşteri Hizmetleri Sayfa İçeriği',
                    'title' => 'Müşteri Hizmetleri',
                ],

                'payment-policy' => [
                    'content' => 'Ödeme Politikası Sayfa İçeriği',
                    'title' => 'Ödeme Politikası',
                ],

                'privacy-policy' => [
                    'content' => 'Gizlilik Politikası Sayfa İçeriği',
                    'title' => 'Gizlilik Politikası',
                ],

                'refund-policy' => [
                    'content' => 'İade Politikası Sayfa İçeriği',
                    'title' => 'İade Politikası',
                ],

                'return-policy' => [
                    'content' => 'İade Politikası Sayfa İçeriği',
                    'title' => 'İade Politikası',
                ],

                'shipping-policy' => [
                    'content' => 'Kargo Politikası Sayfa İçeriği',
                    'title' => 'Kargo Politikası',
                ],

                'terms-conditions' => [
                    'content' => 'Şartlar ve Koşullar Sayfa İçeriği',
                    'title' => 'Şartlar ve Koşullar',
                ],

                'terms-of-use' => [
                    'content' => 'Kullanım Koşulları Sayfa İçeriği',
                    'title' => 'Kullanım Koşulları',
                ],

                'whats-new' => [
                    'content' => 'Yenilikler Sayfa İçeriği',
                    'title' => 'Yenilikler',
                ],
            ],
        ],

        'core' => [
            'channels' => [
                'meta-description' => 'Demo Mağaza Meta Açıklama',
                'meta-keywords' => 'Demo Mağaza Meta Anahtar Kelimeler',
                'meta-title' => 'Demo Mağaza',
                'name' => 'Varsayılan',
            ],

            'currencies' => [
                'AED' => 'Birleşik Arap Emirlikleri Dirhemi',
                'ARS' => 'Arjantin Pesosu',
                'AUD' => 'Avustralya Doları',
                'BDT' => 'Bangladeş Takası',
                'BHD' => 'Bahreyn Dinarı',
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

            'locales' => [
                'ar' => 'Arapça',
                'bn' => 'Bengali',
                'ca' => 'Katalanca',
                'de' => 'Almanca',
                'en' => 'İngilizce',
                'es' => 'İspanyolca',
                'fa' => 'Farsça',
                'fr' => 'Fransızca',
                'he' => 'İbranice',
                'hi_IN' => 'Hintçe',
                'id' => 'Endonezyaca',
                'it' => 'İtalyanca',
                'ja' => 'Japonca',
                'nl' => 'Felemenkçe',
                'pl' => 'Lehçe',
                'pt_BR' => 'Brezilya Portekizcesi',
                'ru' => 'Rusça',
                'sin' => 'Sinhala',
                'tr' => 'Türkçe',
                'uk' => 'Ukraynaca',
                'zh_CN' => 'Çince',
            ],
        ],

        'customer' => [
            'customer-groups' => [
                'general' => 'Genel',
                'guest' => 'Misafir',
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
                        'btn-title' => 'Koleksiyonlara Göz At',
                        'description' => 'Yeni Cesur Koleksiyonlarımızı Tanıtıyoruz! Cesur tasarımlar ve canlı ifadelerle tarzınızı yükseltin. Gardırobunuzu yeniden tanımlayan çarpıcı desenler ve cesur renklere keşfedin. Olağanüstüye hazır olun!',
                        'title' => 'Yeni Cesur Koleksiyonlarımıza Hazır Olun!',
                    ],

                    'name' => 'Cesur Koleksiyonlar',
                ],

                'bold-collections-2' => [
                    'content' => [
                        'btn-title' => 'Koleksiyonları Görüntüle',
                        'description' => 'Cesur Koleksiyonlarımız, gardırobunuzu korkusuz tasarımlar ve çarpıcı, canlı renklerle yeniden tanımlamak için burada. Cesur desenlerden güçlü tonlara, bu sıradanlıktan kopup olağanüstü olana adım atma şansınız.',
                        'title' => 'Yeni Koleksiyonumuzla Cesaretinizi Ortaya Çıkarın!',
                    ],

                    'name' => 'Cesur Koleksiyonlar',
                ],

                'booking-products' => [
                    'name' => 'Rezervasyon Ürünleri',

                    'options' => [
                        'title' => 'Bilet Rezervasyonu',
                    ],
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
                        'about-us' => 'Hakkımızda',
                        'contact-us' => 'Bize Ulaşın',
                        'customer-service' => 'Müşteri Hizmetleri',
                        'payment-policy' => 'Ödeme Politikası',
                        'privacy-policy' => 'Gizlilik Politikası',
                        'refund-policy' => 'İade Politikası',
                        'return-policy' => 'İade Politikası',
                        'shipping-policy' => 'Kargo Politikası',
                        'terms-conditions' => 'Şartlar ve Koşullar',
                        'terms-of-use' => 'Kullanım Koşulları',
                        'whats-new' => 'Yenilikler',
                    ],
                ],

                'game-container' => [
                    'content' => [
                        'sub-title-1' => 'Koleksiyonlarımız',
                        'sub-title-2' => 'Koleksiyonlarımız',
                        'title' => 'Yeni eklemelerimizle oyun!',
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
                        'emi-available-info' => 'Tüm ana kredi kartlarında ücretsiz EMI mevcut',
                        'free-shipping-info' => 'Tüm siparişlerde ücretsiz kargo keyfini çıkarın',
                        'product-replace-info' => 'Kolay ürün değiştirme mevcut!',
                        'time-support-info' => 'Sohbet ve e-posta yoluyla adanmış 7/24 destek',
                    ],

                    'name' => 'Hizmet İçeriği',

                    'title' => [
                        'emi-available' => 'EMI Mevcut',
                        'free-shipping' => 'Ücretsiz Kargo',
                        'product-replace' => 'Ürün Değiştirme',
                        'time-support' => '7/24 Destek',
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
                        'title' => 'Yeni eklemelerimizle oyun!',
                    ],

                    'name' => 'En İyi Koleksiyonlar',
                ],
            ],
        ],

        'user' => [
            'roles' => [
                'description' => 'Bu rol kullanıcılara tüm erişimi sağlar',
                'name' => 'Yönetici',
            ],

            'users' => [
                'name' => 'Örnek',
            ],
        ],

        'sample-categories' => [
            'category-translation' => [
                '2' => [
                    'description' => '<p>Erkek</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Erkek',
                    'slug' => 'mens',
                    'url-path' => 'men',
                ],

                '3' => [
                    'description' => '<p>Çocuk</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Çocuk',
                    'slug' => 'kids',
                    'url-path' => 'kids',
                ],

                '4' => [
                    'description' => '<p>Kadın</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Kadın',
                    'slug' => 'womens',
                    'url-path' => 'woman',
                ],

                '5' => [
                    'description' => '<p>Resmi Giyim</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Resmi Giyim',
                    'slug' => 'formal-wear-men',
                    'url-path' => 'men/formal-wear-men',
                ],

                '6' => [
                    'description' => '<p>Günlük Giyim</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Günlük Giyim',
                    'slug' => 'casual-wear-men',
                    'url-path' => 'men/casual-wear-men',
                ],

                '7' => [
                    'description' => '<p>Spor Giyim</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Spor Giyim',
                    'slug' => 'active-wear',
                    'url-path' => 'men/active-wear',
                ],

                '8' => [
                    'description' => '<p>Ayakkabı</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Ayakkabı',
                    'slug' => 'footwear',
                    'url-path' => 'men/footwear',
                ],

                '9' => [
                    'description' => '<p><span>Resmi Giyim</span></p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Resmi Giyim',
                    'slug' => 'formal-wear-female',
                    'url-path' => 'woman/formal-wear-female',
                ],

                '10' => [
                    'description' => '<p>Günlük Giyim</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Günlük Giyim',
                    'slug' => 'casual-wear-female',
                    'url-path' => 'woman/casual-wear-female',
                ],

                '11' => [
                    'description' => '<p>Spor Giyim</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Spor Giyim',
                    'slug' => 'active-wear-female',
                    'url-path' => 'woman/active-wear-female',
                ],

                '12' => [
                    'description' => '<p>Ayakkabı</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Ayakkabı',
                    'slug' => 'footwear-female',
                    'url-path' => 'woman/footwear-female',
                ],

                '13' => [
                    'description' => '<p>Kız Giyim</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => 'Kız Giyim',
                    'name' => 'Kız Giyim',
                    'slug' => 'girls-clothing',
                    'url-path' => 'kids/girls-clothing',
                ],

                '14' => [
                    'description' => '<p>Erkek Çocuk Giyim</p>',
                    'meta-description' => 'Erkek Çocuk Modası',
                    'meta-keywords' => '',
                    'meta-title' => 'Erkek Çocuk Giyim',
                    'name' => 'Erkek Çocuk Giyim',
                    'slug' => 'boys-clothing',
                    'url-path' => 'kids/boys-clothing',
                ],

                '15' => [
                    'description' => '<p>Kız Ayakkabı</p>',
                    'meta-description' => 'Kız Modası Ayakkabı Koleksiyonu',
                    'meta-keywords' => '',
                    'meta-title' => 'Kız Ayakkabı',
                    'name' => 'Kız Ayakkabı',
                    'slug' => 'girls-footwear',
                    'url-path' => 'kids/girls-footwear',
                ],

                '16' => [
                    'description' => '<p>Erkek Çocuk Ayakkabı</p>',
                    'meta-description' => 'Erkek Çocuk Şık Ayakkabı Koleksiyonu',
                    'meta-keywords' => '',
                    'meta-title' => 'Erkek Çocuk Ayakkabı',
                    'name' => 'Erkek Çocuk Ayakkabı',
                    'slug' => 'boys-footwear',
                    'url-path' => 'kids/boys-footwear',
                ],

                '17' => [
                    'description' => '<p>Sağlık</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Sağlık',
                    'slug' => 'wellness',
                    'url-path' => 'wellness',
                ],

                '18' => [
                    'description' => '<p>İndirilebilir Yoga Eğitimi</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'İndirilebilir Yoga Eğitimi',
                    'slug' => 'downloadable-yoga-tutorial',
                    'url-path' => 'wellness/downloadable-yoga-tutorial',
                ],

                '19' => [
                    'description' => '<p>E-Kitap Koleksiyonu</p>',
                    'meta-description' => 'E-Kitap Koleksiyonu',
                    'meta-keywords' => '',
                    'meta-title' => 'E-Kitap Koleksiyonu',
                    'name' => 'E-Kitaplar',
                    'slug' => 'e-books',
                    'url-path' => 'wellness/e-books',
                ],

                '20' => [
                    'description' => '<p>Sinema Kartı</p>',
                    'meta-description' => 'Ek ücret olmadan ayda 10 filmin büyüsüne dalın.',
                    'meta-keywords' => '',
                    'meta-title' => 'CineXperience Aylık Sinema Kartı',
                    'name' => 'Sinema Kartı',
                    'slug' => 'movie-pass',
                    'url-path' => 'wellness/movie-pass',
                ],

                '21' => [
                    'description' => '<p>Sorunsuz rezervasyon sistemimizle rezervasyona dayalı ürünlerinizi kolayca yönetin ve satın.</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Rezervasyonlar',
                    'slug' => 'bookings',
                    'url-path' => '',
                ],

                '22' => [
                    'description' => '<p>Randevu rezervasyonu müşterilerin işletmeler veya profesyonellerle hizmetler veya danışmanlıklar için zaman dilimleri planlamasını sağlar.</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Randevu Rezervasyonu',
                    'slug' => 'appointment-booking',
                    'url-path' => '',
                ],

                '23' => [
                    'description' => '<p>Etkinlik rezervasyonu bireylerin veya grupların halka açık veya özel etkinlikler için kayıt olmasını veya yer ayırtmasını sağlar.</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Etkinlik Rezervasyonu',
                    'slug' => 'event-booking',
                    'url-path' => '',
                ],

                '24' => [
                    'description' => '<p>Toplantı salonu rezervasyonu bireylerin, kuruluşların veya grupların çeşitli etkinlikler için toplum alanlarını ayırtmasını sağlar.</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Toplantı Salonu Rezervasyonları',
                    'slug' => 'community-hall-bookings',
                    'url-path' => '',
                ],

                '25' => [
                    'description' => '<p>Masa rezervasyonu müşterilerin restoranlarda, kafelerde veya yeme-içme mekanlarında önceden masa ayırtmasını sağlar.</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Masa Rezervasyonu',
                    'slug' => 'table-booking',
                    'url-path' => '',
                ],

                '26' => [
                    'description' => '<p>Kiralama rezervasyonu geçici kullanım için eşya veya mülk ayırtmayı kolaylaştırır.</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Kiralama Rezervasyonu',
                    'slug' => 'rental-booking',
                    'url-path' => '',
                ],

                '27' => [
                    'description' => '<p>Sizi bağlı, üretken ve eğlenceli tutmak için tasarlanmış en son tüketici elektroniğini keşfedin.</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Elektronik',
                    'slug' => 'electronics',
                    'url-path' => '',
                ],

                '28' => [
                    'description' => '<p>Hareket halindeyken bağlı kalmak için akıllı telefonları, şarj cihazlarını, kılıfları ve diğer temel ürünleri keşfedin.</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Cep Telefonları ve Aksesuarlar',
                    'slug' => 'mobile-phones-accessories',
                    'url-path' => '',
                ],

                '29' => [
                    'description' => '<p>İş, eğitim ve eğlence için güçlü dizüstü bilgisayarlar ve taşınabilir tabletler bulun.</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Dizüstü Bilgisayarlar ve Tabletler',
                    'slug' => 'laptops-tablets',
                    'url-path' => '',
                ],

                '30' => [
                    'description' => '<p>Kristal netliğinde ses için kulaklıklar, kulak içi kulaklıklar ve hoparlörler satın alın.</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Ses Cihazları',
                    'slug' => 'audio-devices',
                    'url-path' => '',
                ],

                '31' => [
                    'description' => '<p>Akıllı aydınlatma, termostatlar, güvenlik sistemleri ve daha fazlasıyla hayatı kolaylaştırın.</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Akıllı Ev ve Otomasyon',
                    'slug' => 'smart-home-automation',
                    'url-path' => '',
                ],

                '32' => [
                    'description' => '<p>Yaşam alanınızı işlevsel ve şık ev ve mutfak ürünleriyle geliştirin.</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Ev Eşyaları',
                    'slug' => 'household',
                    'url-path' => '',
                ],

                '33' => [
                    'description' => '<p>Yemek hazırlamayı basitleştirmek için blenderları, airfryerları, kahve makinelerini ve daha fazlasını inceleyin.</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Mutfak Aletleri',
                    'slug' => 'kitchen-appliances',
                    'url-path' => '',
                ],

                '34' => [
                    'description' => '<p>Mutfak ihtiyaçlarınız için pişirme setleri, mutfak gereçleri, yemek takımları ve servis ürünlerini keşfedin.</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Pişirme ve Yemek',
                    'slug' => 'cookware-dining',
                    'url-path' => '',
                ],

                '35' => [
                    'description' => '<p>Kanepeler, masalar, duvar sanatı ve ev aksesuarlarıyla konfor ve cazibe ekleyin.</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Mobilya ve Dekorasyon',
                    'slug' => 'furniture-decor',
                    'url-path' => '',
                ],

                '36' => [
                    'description' => '<p>Elektrikli süpürgeler, temizlik spreyleri, süpürgeler ve düzenleyicilerle alanınızı tertemiz tutun.</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Temizlik Malzemeleri',
                    'slug' => 'cleaning-supplies',
                    'url-path' => '',
                ],

                '37' => [
                    'description' => '<p>Geniş bir kitap ve kırtasiye seçkisiyle hayal gücünüzü ateşleyin veya çalışma alanınızı düzenleyin.</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Kitaplar ve Kırtasiye',
                    'slug' => 'books-stationery',
                    'url-path' => '',
                ],

                '38' => [
                    'description' => '<p>Çok satan romanlar, biyografiler, kişisel gelişim ve daha fazlasına dalın.</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Kurgu ve Kurgu Dışı Kitaplar',
                    'slug' => 'fiction-non-fiction-books',
                    'url-path' => '',
                ],

                '39' => [
                    'description' => '<p>Tüm yaşlar için ders kitapları, referans materyalleri ve çalışma kılavuzları bulun.</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Eğitim ve Akademik',
                    'slug' => 'educational-academic',
                    'url-path' => '',
                ],

                '40' => [
                    'description' => '<p>Verimlilik için kalemler, defterler, planlamacılar ve ofis malzemeleri satın alın.</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Ofis Malzemeleri',
                    'slug' => 'office-supplies',
                    'url-path' => '',
                ],

                '41' => [
                    'description' => '<p>Yaratıcılar için boyalar, fırçalar, eskiz defterleri ve DIY el sanatları kitleri keşfedin.</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Sanat ve El Sanatları Malzemeleri',
                    'slug' => 'art-craft-materials',
                    'url-path' => '',
                ],
            ],
        ],
    ],

    'installer' => [
        'middleware' => [
            'already-installed' => 'Uygulama zaten yüklü.',
        ],

        'index' => [
            'create-administrator' => [
                'admin' => 'Yönetici',
                'bagisto' => 'Bagisto',
                'confirm-password' => 'Şifreyi Onayla',
                'email' => 'E-posta',
                'email-address' => 'admin@ornek.com',
                'password' => 'Şifre',
                'title' => 'Yönetici Oluştur',
            ],

            'environment-configuration' => [
                'algerian-dinar' => 'Cezayir Dinarı (DZD)',
                'allowed-currencies' => 'İzin Verilen Para Birimleri',
                'allowed-locales' => 'İzin Verilen Lokaller',
                'application-name' => 'Uygulama Adı',
                'argentine-peso' => 'Arjantin Pezosu (ARS)',
                'australian-dollar' => 'Avustralya Doları (AUD)',
                'bagisto' => 'Bagisto',
                'bangladeshi-taka' => 'Bangladeş Takası (BDT)',
                'bahraini-dinar' => 'Bahreyn Dinarı (BHD)',
                'brazilian-real' => 'Brezilya Reali (BRL)',
                'british-pound-sterling' => 'İngiliz Sterlini (GBP)',
                'canadian-dollar' => 'Kanada Doları (CAD)',
                'cfa-franc-bceao' => 'CFA Frank BCEAO (XOF)',
                'cfa-franc-beac' => 'CFA Frank BEAC (XAF)',
                'chilean-peso' => 'Şili Pesosu (CLP)',
                'chinese-yuan' => 'Çin Yuanı (CNY)',
                'colombian-peso' => 'Kolombiya Pesosu (COP)',
                'czech-koruna' => 'Çek Korunası (CZK)',
                'danish-krone' => 'Danimarka Kronu (DKK)',
                'database-connection' => 'Veritabanı Bağlantısı',
                'database-hostname' => 'Veritabanı Sunucu Adı',
                'database-name' => 'Veritabanı Adı',
                'database-password' => 'Veritabanı Parolası',
                'database-port' => 'Veritabanı Bağlantı Noktası',
                'database-prefix' => 'Veritabanı Öneki',
                'database-prefix-help' => 'Öneki 4 karakter uzunluğunda olmalı ve yalnızca harfler, sayılar ve alt çizgi içerebilir.',
                'database-username' => 'Veritabanı Kullanıcı Adı',
                'default-currency' => 'Varsayılan Para Birimi',
                'default-locale' => 'Varsayılan Lokal',
                'default-timezone' => 'Varsayılan Zaman Dilimi',
                'default-url' => 'Varsayılan URL',
                'default-url-link' => 'https://localhost',
                'egyptian-pound' => 'Mısır Lirası (EGP)',
                'euro' => 'Euro (EUR)',
                'fijian-dollar' => 'Fiji Doları (FJD)',
                'hong-kong-dollar' => 'Hong Kong Doları (HKD)',
                'hungarian-forint' => 'Macar Forinti (HUF)',
                'indian-rupee' => 'Hint Rupisi (INR)',
                'indonesian-rupiah' => 'Endonezya Rupisi (IDR)',
                'israeli-new-shekel' => 'İsrail Yeni Şekeli (ILS)',
                'japanese-yen' => 'Japon Yeni (JPY)',
                'jordanian-dinar' => 'Ürdün Dinarı (JOD)',
                'kazakhstani-tenge' => 'Kazakistan Tengesi (KZT)',
                'kuwaiti-dinar' => 'Kuveyt Dinarı (KWD)',
                'lebanese-pound' => 'Lübnan Lirası (LBP)',
                'libyan-dinar' => 'Libya Dinarı (LYD)',
                'malaysian-ringgit' => 'Malezya Ringiti (MYR)',
                'mauritian-rupee' => 'Mauritius Rupisi (MUR)',
                'mexican-peso' => 'Meksika Pesosu (MXN)',
                'moroccan-dirham' => 'Fas Dirhemi (MAD)',
                'mysql' => 'MySQL',
                'nepalese-rupee' => 'Nepal Rupisi (NPR)',
                'new-taiwan-dollar' => 'Yeni Tayvan Doları (TWD)',
                'new-zealand-dollar' => 'Yeni Zelanda Doları (NZD)',
                'nigerian-naira' => 'Nijerya Nairası (NGN)',
                'norwegian-krone' => 'Norveç Kronu (NOK)',
                'omani-rial' => 'Umman Riyali (OMR)',
                'pakistani-rupee' => 'Pakistan Rupisi (PKR)',
                'panamanian-balboa' => 'Panama Balboası (PAB)',
                'paraguayan-guarani' => 'Paraguay Guaranisi (PYG)',
                'peruvian-nuevo-sol' => 'Peru Nuevo Solu (PEN)',
                'pgsql' => 'PgSQL',
                'philippine-peso' => 'Filipinler Pesosu (PHP)',
                'polish-zloty' => 'Polonya Zlotisi (PLN)',
                'qatari-rial' => 'Katar Riyali (QAR)',
                'romanian-leu' => 'Romanya Leyi (RON)',
                'russian-ruble' => 'Rus Rublesi (RUB)',
                'saudi-riyal' => 'Suudi Riyali (SAR)',
                'select-timezone' => 'Zaman Dilimi Seç',
                'singapore-dollar' => 'Singapur Doları (SGD)',
                'south-african-rand' => 'Güney Afrika Randı (ZAR)',
                'south-korean-won' => 'Güney Kore Wonu (KRW)',
                'sqlsrv' => 'SQLSRV',
                'sri-lankan-rupee' => 'Sri Lanka Rupisi (LKR)',
                'swedish-krona' => 'İsveç Kronu (SEK)',
                'swiss-franc' => 'İsviçre Frangı (CHF)',
                'thai-baht' => 'Tayland Bahtı (THB)',
                'title' => 'Mağaza Yapılandırması',
                'tunisian-dinar' => 'Tunus Dinarı (TND)',
                'turkish-lira' => 'Türk Lirası (TRY)',
                'ukrainian-hryvnia' => 'Ukrayna Grivnası (UAH)',
                'united-arab-emirates-dirham' => 'Birleşik Arap Emirlikleri Dirhemi (AED)',
                'united-states-dollar' => 'Amerikan Doları (USD)',
                'uzbekistani-som' => 'Özbekistan Somu (UZS)',
                'venezuelan-bolívar' => 'Venezuela Bolivarı (VEF)',
                'vietnamese-dong' => 'Vietnam Dongu (VND)',
                'warning-message' => 'Dikkat! Varsayılan sistem dili ve varsayılan para birimi ayarları kalıcıdır ve bir kez ayarlandığında değiştirilemez.',
                'zambian-kwacha' => 'Zambiya Kvaçası (ZMW)',
            ],

            'sample-products' => [
                'download-sample' => 'örnek indir',
                'no' => 'Hayır',
                'sample-products' => 'Örnek Ürünler',
                'title' => 'Örnek Ürünler',
                'yes' => 'Evet',
            ],

            'installation-processing' => [
                'bagisto' => 'Bagisto Kurulumu',
                'bagisto-info' => 'Veritabanı tabloları oluşturuluyor, bu birkaç dakika sürebilir',
                'title' => 'Kurulum',
            ],

            'installation-completed' => [
                'admin-panel' => 'Yönetici Paneli',
                'bagisto-forums' => 'Bagisto Forumu',
                'customer-panel' => 'Müşteri Paneli',
                'explore-bagisto-extensions' => 'Bagisto Uzantılarını Keşfedin',
                'title' => 'Kurulum Tamamlandı',
                'title-info' => 'Bagisto sisteminize başarıyla kuruldu.',
            ],

            'ready-for-installation' => [
                'create-database-table' => 'Veritabanı tablosu oluştur',
                'install' => 'Yükleme',
                'install-info' => 'Kurulum için Bagisto',
                'install-info-button' => 'Aşağıdaki düğmeye tıklayın',
                'populate-database-table' => 'Veritabanı tablolarını doldur',
                'start-installation' => 'Kurulumu Başlat',
                'title' => 'Kurulum için Hazır',
            ],

            'start' => [
                'locale' => 'Yerel',
                'main' => 'Başlangıç',
                'select-locale' => 'Yerel Seçin',
                'title' => 'Bagisto kurulumunuz',
                'welcome-title' => 'Bagisto\'ya hoş geldiniz',
            ],

            'server-requirements' => [
                'calendar' => 'Takvim',
                'ctype' => 'cType',
                'curl' => 'cURL',
                'dom' => 'dom',
                'fileinfo' => 'Dosya Bilgisi',
                'filter' => 'Filtre',
                'gd' => 'GD',
                'hash' => 'Hash',
                'intl' => 'intl',
                'json' => 'JSON',
                'mbstring' => 'mbstring',
                'openssl' => 'openssl',
                'pcre' => 'pcre',
                'pdo' => 'pdo',
                'php' => 'PHP',
                'php-version' => '8.1 veya üstü',
                'session' => 'oturum',
                'title' => 'Sunucu Gereksinimleri',
                'tokenizer' => 'tokenizer',
                'xml' => 'XML',
            ],

            'arabic' => 'Arapça',
            'back' => 'Geri',
            'bagisto' => 'Bagisto',
            'bagisto-info' => 'Webkul tarafından geliştirilen bir Topluluk Projesi',
            'bagisto-logo' => 'Bagisto Logosu',
            'bengali' => 'Bengalce',
            'catalan' => 'Katalanca',
            'chinese' => 'Çince',
            'continue' => 'Devam Et',
            'dutch' => 'Hollandaca',
            'english' => 'İngilizce',
            'french' => 'Fransızca',
            'german' => 'Almanca',
            'hebrew' => 'İbranice',
            'hindi' => 'Hintçe',
            'indonesian' => 'İndonezyaca',
            'installation-description' => 'Bagisto kurulumu genellikle birkaç adım içerir. İşte Bagisto\'nun kurulum sürecine genel bir bakış',
            'installation-info' => 'Sizi burada görmekten mutluluk duyuyoruz!',
            'installation-title' => 'Kurulum\'a Hoş Geldiniz',
            'italian' => 'İtalyanca',
            'japanese' => 'Japonca',
            'persian' => 'Farsça',
            'polish' => 'Lehçe',
            'portuguese' => 'Brezilya Portekizcesi',
            'russian' => 'Rusça',
            'sinhala' => 'Sinhala',
            'spanish' => 'İspanyolca',
            'title' => 'Bagisto Kurulum Sihirbazı',
            'turkish' => 'Türkçe',
            'ukrainian' => 'Ukraynaca',
            'webkul' => 'Webkul',
        ],
    ],
];
