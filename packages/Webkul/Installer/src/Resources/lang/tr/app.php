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

        'sample-categories' => [
            'category-translation' => [
                '2' => [
                    'description'      => 'Erkek Kategori Açıklaması',
                    'meta-description' => 'Erkek Kategori Meta Açıklaması',
                    'meta-keywords'    => 'Erkek Kategori Meta Anahtar Kelimeleri',
                    'meta-title'       => 'Erkek Kategori Meta Başlığı',
                    'name'             => 'Erkekler',
                    'slug'             => 'erkekler',
                ],

                '3' => [
                    'description'      => 'Kış Giyim Kategori Açıklaması',
                    'meta-description' => 'Kış Giyim Kategori Meta Açıklaması',
                    'meta-keywords'    => 'Kış Giyim Kategori Meta Anahtar Kelimeleri',
                    'meta-title'       => 'Kış Giyim Kategori Meta Başlığı',
                    'name'             => 'Kış giysisi',
                    'slug'             => 'kış giysisi',
                ],
            ],
        ],

        'sample-products' => [
            'product-flat' => [
                '1' => [
                    'description'       => 'Arctic Cozy Örme Unisex Bere, soğuk aylarda sıcak, rahat ve şık kalmanız için ideal bir çözümdür. Akrilik örgü malzemeden üretilen bu bere, sıcak ve sıkı bir uyum sağlamak için tasarlanmıştır. Klasik tasarımı sayesinde hem erkekler hem de kadınlar için uygun olan bu bere, çeşitli stillere uyum sağlayan çok yönlü bir aksesuardır. Şehirde rahat bir gün geçirirken veya doğayla iç içe olurken, bu bere, kıyafetinize biraz konfor ve sıcaklık katıyor. Yumuşak ve nefes alabilen malzeme, tarzınızdan ödün vermeden sıcak kalmanızı sağlar. Arctic Cozy Örme Unisex Bere sadece bir aksesuar değil, kış modasının bir ifadesidir. Basitliği, farklı kıyafetlerle kolayca eşleştirilebilmesini sağlar ve kış gardırobunuzun vazgeçilmez bir parçası haline gelir. Hediye olarak veya kendinizi şımartmak için düşünülen bu bere, her kış kombinine düşünülen bir ek olarak düşünülebilir. Sıcaklık ile zamansız bir moda anlayışını mükemmel bir şekilde birleştiren bu klasik aksesuarla kış gardırobunuzu yükseltin.',
                    'meta-description'  => 'meta açıklama',
                    'meta-keywords'     => 'meta1, meta2, meta3',
                    'meta-title'        => 'Meta Başlık',
                    'name'              => 'Arctic Cozy Örme Unisex Bere',
                    'short-description' => 'Arctic Cozy Örme Bere ile soğuk günlerde şıklığı kucaklayın. Akrilikten yapılan bu klasik bere, sıcaklık ve çok yönlülük sunar. Hem erkekler hem de kadınlar için uygun olan bu bere, rahat veya açık hava giyimi için ideal bir aksesuardır. Kış gardırobunuzu yükseltin veya özel birine hediye olarak verin bu temel bere şapka ile.',
                ],

                '2' => [
                    'description'       => 'Arctic Bliss Kış Şalı, sadece soğuk hava aksesuarı değil, kış sezonu için sıcaklık, konfor ve stil ifadesidir. Akrilik ve yünün lüks bir karışımından özenle üretilen bu şal, en soğuk sıcaklıklarda bile sizi sıcak ve rahat tutmak için tasarlanmıştır. Yumuşak ve peluş dokusu, sadece soğuğa karşı yalıtım sağlamakla kalmaz, aynı zamanda kış gardırobunuza bir dokunuş lüks katar. Arctic Bliss Kış Şalı\'nın tasarımı hem şık hem de çok yönlüdür, bu da onu çeşitli kış kıyafetlerine mükemmel bir ek yapar. Özel bir etkinlik için giyinirken veya günlük görünümünüze şık bir katman eklerken, bu şal tarzınızı mükemmel bir şekilde tamamlar. Şalın ekstra uzunluğu, özelleştirilebilir stil seçenekleri sunar. Sıcaklık için sarın, gevşek bir şekilde sallayın veya farklı düğümlerle deney yapın ve benzersiz stilinizi ifade edin. Bu çok yönlülük, kış sezonu için olmazsa olmaz bir aksesuardır. Mükemmel bir hediye mi arıyorsunuz? Arctic Bliss Kış Şalı ideal bir seçimdir. Sevdiklerinizi şaşırtıyor veya kendinizi şımartıyorsanız, bu şal, kış ayları boyunca değerli bir hediye olacaktır. Arctic Bliss Kış Şalı ile kışı kucaklayın, sıcaklık stil ile mükemmel bir uyum içinde buluşur. Kış gardırobunuzu bu temel aksesuarla yükseltin, sadece sizi sıcak tutmakla kalmaz, aynı zamanda soğuk hava kıyafetinize biraz sofistike bir dokunuş katar.',
                    'meta-description'  => 'meta açıklama',
                    'meta-keywords'     => 'meta1, meta2, meta3',
                    'meta-title'        => 'Meta Başlık',
                    'name'              => 'Arctic Bliss Şık Kış Şalı',
                    'short-description' => 'Arctic Bliss Kış Şalı ile sıcaklık ve stilin kucaklaşmasını deneyimleyin. Akrilik ve yünden yapılan bu rahat şal, en soğuk günlerde sizi sıcak tutmak için tasarlanmıştır. Şık ve çok yönlü tasarımı, ekstra uzunluğuyla özelleştirilebilir stil seçenekleri sunar. Kış gardırobunuzu yükseltin veya özel birine hediye olarak verin bu temel kış aksesuarı ile.',
                ],

                '3' => [
                    'description'       => 'Arctic Dokunmatik Kış Eldivenleri ile sıcaklık, stil ve bağlantı, kış deneyiminizi artırmak için buluşuyor. Yüksek kaliteli akrilikten üretilen bu eldivenler, olağanüstü sıcaklık ve dayanıklılık sağlamak için tasarlanmıştır. Dokunmatik uyumlu parmak uçları sayesinde ellerinizi soğuğa maruz bırakmadan bağlantıda kalabilirsiniz. Aramaları yanıtlayın, mesaj gönderin ve cihazlarınızı kolayca kullanın, tüm bunları ellerinizi sıcak tutarken yapın. Yalıtımlı astar ekstra bir rahatlık katmanı ekler ve bu eldivenleri kış soğuğuyla yüzleşmek için tercih edeceğiniz seçenek haline getirir. İşe gidip gelirken, işleri hallederken veya açık hava etkinliklerinin tadını çıkarırken, bu eldivenler ihtiyacınız olan sıcaklık ve korumayı sağlar. Elastik manşetler, güvenli bir uyum sağlayarak soğuk hava akımlarını önler ve günlük aktiviteleriniz sırasında eldivenleri yerinde tutar. Şık tasarım, kış kıyafetinize bir dokunuş katarken, bu eldivenleri işlevsel olduğu kadar modaya uygun hale getirir. Hediye olarak veya kendinizi şımartmak için ideal olan Arctic Dokunmatik Kış Eldivenleri, modern birey için olmazsa olmaz bir aksesuardır. Eldivenlerinizi çıkarmadan cihazlarınızı kullanma zahmetinden kurtulun ve sıcaklık, stil ve bağlantının sorunsuz bir şekilde birleştiği bu eldivenlerle kalın. Arctic Dokunmatik Kış Eldivenleri ile bağlantıda kalın, sıcak kalın ve şık kalın - kış mevsimini özgüvenle yenmek için güvenilir bir arkadaşınız.',
                    'meta-description'  => 'meta açıklama',
                    'meta-keywords'     => 'meta1, meta2, meta3',
                    'meta-title'        => 'Meta Başlık',
                    'name'              => 'Arctic Dokunmatik Kış Eldivenleri',
                    'short-description' => 'Arctic Dokunmatik Kış Eldivenleri ile bağlantıda kalın ve sıcak kalın. Bu eldivenler sadece sıcaklık ve dayanıklılık için yüksek kaliteli akrilikten üretilmiyor, aynı zamanda dokunmatik uyumlu bir tasarıma sahip. Yalıtımlı astar, güvenli bir uyum için elastik manşetler ve şık bir görünüm ile bu eldivenler, soğuk koşullarda günlük giyim için mükemmel bir seçenektir.',
                ],

                '4' => [
                    'description'       => 'Arctic Sıcaklık Yün Karışımı Çorapları ile daha sıcak ve rahat ayaklarınızın vazgeçilmez arkadaşı olun. Merino yünü, akrilik, naylon ve spandex\'in birinci sınıf bir karışımından üretilen bu çoraplar, eşsiz sıcaklık ve konfor sağlamak için tasarlanmıştır. Yün karışımı, ayaklarınızın en soğuk sıcaklıklarda bile sıcak kalmasını sağlar, bu da bu çorapları kış maceraları için mükemmel bir seçenek veya sadece evde rahatlıkla kalmanızı sağlar. Çorapların yumuşak ve rahat dokusu, cildinizle lüks bir his sunar. Bu yün karışımı çoraplarla soğuk ayaklara veda edin ve sunulan yün karışımının sunduğu lüks sıcaklığı kucaklayın. Dayanıklılık için tasarlanan çoraplar, takviyeli topuk ve burun ile yüksek aşınma bölgelerine ekstra dayanıklılık sağlar. Bu, çoraplarınızın zamanın testini geçmesini ve uzun süreli konfor ve rahatlık sağlamasını sağlar. Malzemenin nefes alabilir yapısı aşırı ısınmayı önler, böylece ayaklarınız gün boyunca rahat ve kuru kalır. Kış yürüyüşü için dışarı çıkıyor veya içeride dinleniyorsanız, bu çoraplar sıcaklık ve nefes alabilirlik arasında mükemmel bir denge sunar. Çeşitli durumlar için uygun ve şık olan bu yün karışımı çorapları, favori botlarınızla şık bir kış görünümü için eşleştirin veya evde rahatlık için giyin. Kış gardırobunuzu yükseltin ve konforu önceliklendirin Arctic Sıcaklık Yün Karışımı Çorapları ile. Ayaklarınızı hak ettikleri lüksle şımartın ve tüm mevsim boyunca süren bir rahatlık dünyasına adım atın.',
                    'meta-description'  => 'meta açıklama',
                    'meta-keywords'     => 'meta1, meta2, meta3',
                    'meta-title'        => 'Meta Başlık',
                    'name'              => 'Arctic Sıcaklık Yün Karışımı Çorapları',
                    'short-description' => 'Arctic Sıcaklık Yün Karışımı Çoraplarımızın eşsiz sıcaklık ve konforunu deneyimleyin. Merino yünü, akrilik, naylon ve spandex\'in bir karışımından üretilen bu çoraplar, soğuk havalarda en üst düzeyde rahatlık sunar. Dayanıklılık için takviyeli topuk ve burun ile çok yönlü ve şık olan bu çoraplar, çeşitli durumlar için mükemmeldir.',
                ],

                '5' => [
                    'description'       => 'Arctic Frost Kış Aksesuarları Paketi ile soğuk kış günlerinde sıcak, şık ve bağlantılı kalmanın çözümünü sunuyoruz. Bu özenle seçilmiş set, dört temel kış aksesuarını bir araya getirerek uyumlu bir bütün oluşturur. Akrilik ve yünden dokunan lüks şal, sadece bir katman sıcaklık katmakla kalmaz, aynı zamanda kış gardırobunuza bir dokunuş elegans katar. Özenle tasarlanmış yumuşak örme bere, sizi sıcak tutmayı vaat ederken görünümünüze moda bir hava katar. Ama burada bitmiyor - setimiz ayrıca dokunmatik uyumlu eldivenler içeriyor. Cihazlarınızı kolayca kullanırken sıcaklıktan ödün vermeden bağlantıda kalın. Telefonunuzda çağrıları yanıtlarken, mesajlar gönderirken veya kış anılarınızı yakalarken, bu eldivenler stilinizden ödün vermeden kolaylık sağlar. Çorapların yumuşak ve rahat dokusu cildinizde lüks bir his sunar. Yün karışımı çorapların sağladığı peluş sıcaklık ile soğuk ayaklara veda edin. Arctic Frost Kış Aksesuarları Paketi sadece işlevsellikle ilgili değildir; kış modasının bir ifadesidir. Her parça sadece soğuktan korunmanızı sağlamakla kalmaz, aynı zamanda buzlu mevsimde stilinizi yükseltmek için tasarlanmıştır. Bu paket için seçilen malzemeler dayanıklılık ve konforu önceliklendirir, böylece kış cennetinin tadını stil sahibi bir şekilde çıkarabilirsiniz. Kendinizi şımartmak veya mükemmel bir hediye arayışında olun, Arctic Frost Kış Aksesuarları Paketi çok yönlü bir seçenektir. Tatil sezonunda özel birini mutlu et veya kendi kış gardırobunu bu şık ve işlevsel setle yükselt. Mükemmel aksesuarlara sahip olduğunuzdan emin olarak dondurucu soğuğa güvenle kucak açın.',
                    'meta-description'  => 'meta açıklama',
                    'meta-keywords'     => 'meta1, meta2, meta3',
                    'meta-title'        => 'Meta Başlık',
                    'name'              => 'Arctic Frost Kış Aksesuarları',
                    'short-description' => 'Arctic Frost Kış Aksesuarları Paketi ile kışın soğuğunu kucaklayın. Bu özenle seçilmiş set, lüks bir şal, rahat bir bere, dokunmatik uyumlu eldivenler ve yün karışımı çoraplardan oluşur. Şık ve işlevsel olan bu set, dayanıklılık ve konfor sağlayan yüksek kaliteli malzemelerden üretilmiştir. Kış gardırobunuzu yükseltin veya mükemmel bir hediye seçeneğiyle özel birini mutlu edin.',
                ],

                '6' => [
                    'description'       => 'Arctic Frost Kış Aksesuarları Paketi ile soğuk kış günlerinde sıcak, şık ve bağlantılı kalmanın çözümünü sunuyoruz. Bu özenle seçilmiş set, dört temel kış aksesuarını bir araya getirerek uyumlu bir bütün oluşturur. Akrilik ve yünden dokunan lüks şal, sadece bir katman sıcaklık katmakla kalmaz, aynı zamanda kış gardırobunuza bir dokunuş elegans katar. Özenle tasarlanmış yumuşak örme bere, sizi sıcak tutmayı vaat ederken görünümünüze moda bir hava katar. Ama burada bitmiyor - setimiz ayrıca dokunmatik uyumlu eldivenler içeriyor. Cihazlarınızı kolayca kullanırken sıcaklıktan ödün vermeden bağlantıda kalın. Telefonunuzda çağrıları yanıtlarken, mesajlar gönderirken veya kış anılarınızı yakalarken, bu eldivenler stilinizden ödün vermeden kolaylık sağlar. Çorapların yumuşak ve rahat dokusu cildinizde lüks bir his sunar. Yün karışımı çorapların sağladığı peluş sıcaklık ile soğuk ayaklara veda edin. Arctic Frost Kış Aksesuarları Paketi sadece işlevsellikle ilgili değildir; kış modasının bir ifadesidir. Her parça sadece soğuktan korunmanızı sağlamakla kalmaz, aynı zamanda buzlu mevsimde stilinizi yükseltmek için tasarlanmıştır. Bu paket için seçilen malzemeler dayanıklılık ve konforu önceliklendirir, böylece kış cennetinin tadını stil sahibi bir şekilde çıkarabilirsiniz. Kendinizi şımartmak veya mükemmel bir hediye arayışında olun, Arctic Frost Kış Aksesuarları Paketi çok yönlü bir seçenektir. Tatil sezonunda özel birini mutlu et veya kendi kış gardırobunu bu şık ve işlevsel setle yükselt. Mükemmel aksesuarlara sahip olduğunuzdan emin olarak dondurucu soğuğa güvenle kucak açın.',
                    'meta-description'  => 'meta açıklama',
                    'meta-keywords'     => 'meta1, meta2, meta3',
                    'meta-title'        => 'Meta Başlık',
                    'name'              => 'Arctic Frost Kış Aksesuarları Paketi',
                    'short-description' => 'Arctic Frost Kış Aksesuarları Paketi ile kışın soğuğunu kucaklayın. Bu özenle seçilmiş set, lüks bir şal, rahat bir bere, dokunmatik uyumlu eldivenler ve yün karışımı çoraplardan oluşur. Şık ve işlevsel olan bu set, dayanıklılık ve konfor sağlayan yüksek kaliteli malzemelerden üretilmiştir. Kış gardırobunuzu yükseltin veya mükemmel bir hediye seçeneğiyle özel birini mutlu edin.',
                ],

                '7' => [
                    'description'       => 'OmniHeat Erkeklerin Düz Kapüşonlu Puf Montu\'nu tanıtıyoruz, daha soğuk mevsimlerde sıcak ve modaya uygun kalmanız için ideal çözüm. Bu mont, dayanıklılık ve sıcaklık göz önünde bulundurularak tasarlanmış olup güvenilir bir arkadaşınız olacak. Kapüşonlu tasarım sadece bir stil dokunuşu eklemekle kalmaz, aynı zamanda ekstra sıcaklık sağlayarak soğuk rüzgarlardan ve hava koşullarından korur. Tam kollu tasarım, omuzdan bileğe kadar tam kapsama sağlayarak sıcak kalmanızı sağlar. İçe yerleştirilebilir cepleriyle bu puf mont, eşyalarınızı taşımak veya ellerinizi sıcak tutmak için kolaylık sağlar. Yalıtımlı sentetik dolgu, soğuk günler ve gecelerle mücadele etmek için geliştirilmiş sıcaklık sunar. Dayanıklı polyester dış yüzey ve astardan yapılan bu mont, dayanıklı ve hava koşullarına dayanıklıdır. 5 çekici renkte mevcut olan bu montu stilinize ve tercihinize uygun olanı seçebilirsiniz. Çok yönlü ve işlevsel olan OmniHeat Erkeklerin Düz Kapüşonlu Puf Montu, işe gitmek, rahat bir gezintiye çıkmak veya açık hava etkinliğine katılmak için uygundur. OmniHeat Erkeklerin Düz Kapüşonlu Puf Montu ile stil, konfor ve işlevselliğin mükemmel bir karışımını deneyimleyin. Kış gardırobunuzu yükseltin ve dışarıyı kucaklarken sıcacık kalın. Soğuğa tarzla meydan okuyun ve bu temel parça ile bir stil açıklaması yapın.',
                    'meta-description'  => 'meta açıklama',
                    'meta-keywords'     => 'meta1, meta2, meta3',
                    'meta-title'        => 'Meta Başlık',
                    'name'              => 'OmniHeat Erkeklerin Düz Kapüşonlu Puf Montu',
                    'short-description' => 'OmniHeat Erkeklerin Düz Kapüşonlu Puf Montu ile sıcak ve şık kalın. Bu mont, sıcaklık sağlamak ve ekstra kolaylık için içe yerleştirilebilir ceplere sahip olacak şekilde tasarlanmıştır. Yalıtımlı malzeme, soğuk havalarda sıcak kalmanızı sağlar. 5 çekici renkte mevcut olup çeşitli durumlar için çok yönlü bir seçenektir.',
                ],

                '8' => [
                    'description'       => 'OmniHeat Erkeklerin Düz Kapüşonlu Puf Montu ile sıcak ve şık kalın. Bu mont, sıcaklık sağlamak ve ekstra kolaylık için içe yerleştirilebilir ceplere sahip olacak şekilde tasarlanmıştır. Yalıtımlı malzeme, soğuk havalarda sıcak kalmanızı sağlar. 5 çekici renkte mevcut olup çeşitli durumlar için çok yönlü bir seçenektir.',
                    'meta-description'  => 'meta açıklama',
                    'meta-keywords'     => 'meta1, meta2, meta3',
                    'meta-title'        => 'Meta Başlık',
                    'name'              => 'OmniHeat Erkeklerin Düz Kapüşonlu Puf Montu-Mavi-Sarı-M',
                    'short-description' => 'OmniHeat Erkeklerin Düz Kapüşonlu Puf Montu ile sıcak ve şık kalın. Bu mont, sıcaklık sağlamak ve ekstra kolaylık için içe yerleştirilebilir ceplere sahip olacak şekilde tasarlanmıştır. Yalıtımlı malzeme, soğuk havalarda sıcak kalmanızı sağlar. 5 çekici renkte mevcut olup çeşitli durumlar için çok yönlü bir seçenektir.',
                ],

                '9' => [
                    'description'       => 'OmniHeat Erkeklerin Düz Kapüşonlu Puf Montu ile sıcak ve şık kalın. Bu mont, sıcaklık sağlamak ve ekstra kolaylık için içe yerleştirilebilir ceplere sahip olacak şekilde tasarlanmıştır. Yalıtımlı malzeme, soğuk havalarda sıcak kalmanızı sağlar. 5 çekici renkte mevcut olup çeşitli durumlar için çok yönlü bir seçenektir.',
                    'meta-description'  => 'meta açıklama',
                    'meta-keywords'     => 'meta1, meta2, meta3',
                    'meta-title'        => 'Meta Başlık',
                    'name'              => 'OmniHeat Erkeklerin Düz Kapüşonlu Puf Montu-Mavi-Sarı-L',
                    'short-description' => 'OmniHeat Erkeklerin Düz Kapüşonlu Puf Montu ile sıcak ve şık kalın. Bu mont, sıcaklık sağlamak ve ekstra kolaylık için içe yerleştirilebilir ceplere sahip olacak şekilde tasarlanmıştır. Yalıtımlı malzeme, soğuk havalarda sıcak kalmanızı sağlar. 5 çekici renkte mevcut olup çeşitli durumlar için çok yönlü bir seçenektir.',
                ],

                '10' => [
                    'description'       => 'OmniHeat Erkeklerin Düz Kapüşonlu Puf Montu ile sıcak ve şık kalın. Bu mont, sıcaklık sağlamak ve ekstra kolaylık için içe yerleştirilebilir ceplere sahip olacak şekilde tasarlanmıştır. Yalıtımlı malzeme, soğuk havalarda sıcak kalmanızı sağlar. 5 çekici renkte mevcut olup çeşitli durumlar için çok yönlü bir seçenektir.',
                    'meta-description'  => 'meta açıklama',
                    'meta-keywords'     => 'meta1, meta2, meta3',
                    'meta-title'        => 'Meta Başlık',
                    'name'              => 'OmniHeat Erkeklerin Düz Kapüşonlu Puf Montu-Mavi-Yeşil-M',
                    'short-description' => 'OmniHeat Erkeklerin Düz Kapüşonlu Puf Montu ile sıcak ve şık kalın. Bu mont, sıcaklık sağlamak ve ekstra kolaylık için içe yerleştirilebilir ceplere sahip olacak şekilde tasarlanmıştır. Yalıtımlı malzeme, soğuk havalarda sıcak kalmanızı sağlar. 5 çekici renkte mevcut olup çeşitli durumlar için çok yönlü bir seçenektir.',
                ],

                '11' => [
                    'description'       => 'OmniHeat Erkeklerin Düz Kapüşonlu Puf Montu ile soğuk mevsimlerde sıcak ve şık kalmanın çözümünü keşfedin. Bu mont, dayanıklılık ve sıcaklık göz önünde bulundurularak tasarlanmış olup güvenilir bir arkadaşınız olacak. Kapüşonlu tasarım sadece bir stil dokunuşu eklemekle kalmaz, aynı zamanda soğuk rüzgarlardan ve hava koşullarından korunmanızı sağlar. Tam kollu tasarım tam kapsama sağlar, böylece omuzdan bileğe kadar rahat kalmanızı sağlar. İçe yerleştirilebilir ceplerle donatılmış olan bu puf mont, eşyalarınızı taşımak veya ellerinizi sıcak tutmak için kolaylık sağlar. Yalıtımlı sentetik dolgu, soğuk günler ve gecelerle mücadele etmek için artırılmış sıcaklık sunar. Dayanıklı polyester dış kabuk ve astardan yapılan bu mont, uzun ömürlü ve hava koşullarına dayanıklıdır. 5 çekici renkte mevcut olup, tarzınıza ve tercihinize uygun olanı seçebilirsiniz. Çok yönlü ve işlevsel olan OmniHeat Erkeklerin Düz Kapüşonlu Puf Montu, işe giderken, rahat bir geziye çıkarken veya açık havada bir etkinliğe katılırken çeşitli durumlar için uygundur. Tarz, konfor ve işlevselliğin mükemmel bir karışımını OmniHeat Erkeklerin Düz Kapüşonlu Puf Montu ile deneyimleyin. Kış gardırobunuzu yükseltin ve dışarıyı kucaklarken sıcacık kalın. Soğuğa tarzla meydan okuyun ve bu temel parça ile bir açıklama yapın.',
                    'meta-description'  => 'meta açıklama',
                    'meta-keywords'     => 'meta1, meta2, meta3',
                    'meta-title'        => 'Meta Başlık',
                    'name'              => 'OmniHeat Erkeklerin Düz Kapüşonlu Puf Montu-Mavi-Yeşil-L',
                    'short-description' => 'OmniHeat Erkeklerin Düz Kapüşonlu Puf Montu ile sıcak ve şık kalın. Bu mont, sıcaklık sağlamak ve ekstra kolaylık için içe yerleştirilebilir ceplere sahip olacak şekilde tasarlanmıştır. Yalıtımlı malzeme, soğuk havalarda sıcak kalmanızı sağlar. 5 çekici renkte mevcut olup çeşitli durumlar için çok yönlü bir seçenektir.',
                ],
            ],

            'product-attribute-values' => [
                '1' => [
                    'description'      => 'Arctic Cozy Örme Unisex Bere, soğuk aylarda sıcak, rahat ve şık kalmanız için ideal bir çözümdür. Akrilik örgüden yapılan bu bere, sıcak ve sıkı bir uyum sağlamak için tasarlanmıştır. Klasik tasarımı, hem erkekler hem de kadınlar için uygun olan çeşitli stilleri tamamlayan çok yönlü bir aksesuar sunar. Şehirde rahat bir gün geçirirken veya doğayla iç içe olurken, bu bere, kıyafetinize biraz rahatlık ve sıcaklık katmanın bir yoludur. Yumuşak ve nefes alabilen malzeme, tarzınızdan ödün vermeden sıcak kalmanızı sağlar. Arctic Cozy Örme Unisex Bere sadece bir aksesuar değil, kış modasının bir ifadesidir. Basitliği, farklı kıyafetlerle kolayca eşleştirilebilir olmasını sağlar ve kış gardırobunuzun vazgeçilmez bir parçası haline gelir. Hediye olarak veya kendinizi şımartmak için ideal olan bu bere, kış kombinlerine düşünce dolu bir eklemektir. Fonksiyonelliğin ötesine geçen, sıcaklık ve stilin birleştiği çok yönlü bir aksesuardır. Arctic Cozy Örme Unisex Bere ile kışın ruhunu kucaklayın. Rahat bir gün geçirirken veya zorlu hava koşullarıyla karşılaşırken, bu bere sizi rahatlık ve stil için bir arkadaşınız olsun. Kış gardırobunuzu, sıcaklık ile zamansız bir moda anlayışını mükemmel bir şekilde birleştiren bu klasik aksesuarla yükseltin.',
                    'meta-description' => 'meta açıklama',
                    'meta-keywords'    => 'meta1, meta2, meta3',
                    'meta-title'       => 'Meta Başlık',
                    'name'             => 'Arctic Cozy Örme Unisex Bere',
                    'sort-description' => 'Arctic Cozy Örme Bere ile şık bir şekilde soğuk günlerin tadını çıkarın. Akrilikten yapılan bu klasik bere, sıcaklık ve çok yönlülük sunar. Hem erkekler hem de kadınlar için uygun olan bu bere, rahat veya açık hava giyimi için ideal bir aksesuardır. Kış gardırobunuzu yükseltin veya birisini mutlu etmek için bu temel bere şapkasıyla hediye verin.',
                ],

                '2' => [
                    'description'      => 'Arctic Bliss Kış Şalı, sadece soğuk hava aksesuarı değil, kış mevsimi için sıcaklık, konfor ve stilin bir ifadesidir. Akrilik ve yünün lüks bir karışımından özenle yapılan bu şal, en soğuk sıcaklıklarda bile sizi sıcak ve rahat tutmak için tasarlanmıştır. Yumuşak ve peluş dokusu, sadece soğuğa karşı yalıtım sağlamakla kalmaz, aynı zamanda kış gardırobunuza biraz lüks katmaktadır. Arctic Bliss Kış Şalı\'nın tasarımı hem şık hem de çok yönlüdür, bu da onu çeşitli kış kıyafetlerine mükemmel bir ek yapar. Özel bir durum için giyinirken veya günlük görünümünüze şık bir katman eklerken, bu şal tarzınızı mükemmel bir şekilde tamamlar. Şalın ekstra uzunluğu, özelleştirilebilir stil seçenekleri sunar. Sıcaklık için sarın, rahat bir görünüm için gevşek bir şekilde sarkıtın veya farklı düğümlerle deney yaparak benzersiz stilinizi ifade edin. Bu çok yönlülük, kış mevsimi için bir zorunlu aksesuar yapar. Mükemmel bir hediye mi arıyorsunuz? Arctic Bliss Kış Şalı ideal bir seçimdir. Sevdiklerinizi şaşırtıyor veya kendinizi şımartıyorsanız, bu şal, kış ayları boyunca değerli bir şekilde saklanacak zamansız ve pratik bir hediye olacaktır. Arctic Bliss Kış Şalı ile kışa, sıcaklık stil ile mükemmel bir uyum içinde kucak açın. Sizi sıcak tutmanın yanı sıra soğuk hava kıyafetinize biraz sofistike bir dokunuş ekleyen bu temel aksesuarla kış gardırobunuzu yükseltin.',
                    'meta-description' => 'meta açıklama',
                    'meta-keywords'    => 'meta1, meta2, meta3',
                    'meta-title'       => 'Meta Başlık',
                    'name'             => 'Arctic Bliss Şık Kış Şalı',
                    'sort-description' => 'Arctic Bliss Kış Şalı ile sıcaklık ve stilin kucaklaşmasını deneyimleyin. Akrilik ve yünden yapılan bu rahat şal, en soğuk günlerde sizi sıcak tutmak için tasarlanmıştır. Şık ve çok yönlü tasarımı, ekstra uzunluğuyla birlikte özelleştirilebilir stil seçenekleri sunar. Kış gardırobunuzu yükseltin veya birisini mutlu etmek için bu temel kış aksesuarıyla sevindirin.',
                ],

                '3' => [
                    'description'      => 'Arctic Dokunmatik Ekran Kış Eldivenleri, sıcaklık, stil ve bağlantının bir araya geldiği kış deneyiminizi geliştirmek için tasarlanmıştır. Yüksek kaliteli akrilikten yapılan bu eldivenler, olağanüstü sıcaklık ve dayanıklılık sağlamak için tasarlanmıştır. Dokunmatik ekran uyumlu parmak uçları, ellerinizi soğuğa maruz bırakmadan bağlantıda kalmanızı sağlar. Aramaları yanıtlayın, mesajlar gönderin ve cihazlarınızı kolayca gezinin, ellerinizi sıcak tutarken hepsini yapın. Yalıtımlı astar ekstra bir sıcaklık katmanı ekler, bu da bu eldivenleri günlük aktiviteleriniz sırasında ihtiyaç duyduğunuz sıcaklık ve korumayı sağlar. Elastik manşetler, soğuk hava akımlarını önleyerek eldivenleri günlük aktiviteleriniz sırasında yerinde tutar. Şık tasarım, kış kombininize biraz tarz ekler ve işlevsel olduğu kadar moda da olan bu eldivenler, mükemmel bir aksesuardır. Hediye olarak veya kendinizi şımartmak için Arctic Dokunmatik Ekran Kış Eldivenleri kesinlikle sahip olmanız gereken bir aksesuardır. Eldivenleri çıkarmadan cihazlarınızı kullanma zahmetinden kurtulun ve sıcaklık, stil ve bağlantının sorunsuz bir şekilde birleştiği bu eldivenlerle bağlantıda kalın. Arctic Dokunmatik Ekran Kış Eldivenleri ile bağlantıda kalın, sıcak kalın ve şık kalın - kış mevsimini özgüvenle yenmek için güvenilir bir arkadaşınız.',
                    'meta-description' => 'meta açıklama',
                    'meta-keywords'    => 'meta1, meta2, meta3',
                    'meta-title'       => 'Meta Başlık',
                    'name'             => 'Arctic Dokunmatik Ekran Kış Eldivenleri',
                    'sort-description' => 'Arctic Dokunmatik Ekran Kış Eldivenleri ile bağlantıda kalın ve sıcak kalın. Bu eldivenler sadece sıcaklık ve dayanıklılık için yüksek kaliteli akrilikten yapılmış olup aynı zamanda dokunmatik ekran uyumlu bir tasarıma sahiptir. Yalıtımlı astar, elastik manşetler ve şık bir görünümle birlikte günlük soğuk koşullarda giymek için mükemmeldir.',
                ],

                '4' => [
                    'description'      => 'Arctic Warmth Wool Blend Socks, soğuk mevsimlerde sıcak ve rahat ayaklarınızın vazgeçilmez arkadaşıdır. Premium bir karışım olan Merino yünü, akrilik, naylon ve spandex ile üretilen bu çoraplar eşsiz sıcaklık ve konfor sağlamak için tasarlanmıştır. Yün karışımı, çorapların en soğuk sıcaklıklarda bile ayaklarınızın sıcak kalmasını sağlar, bu da bu çorapları kış maceraları için mükemmel bir seçenek veya evde rahatlıkla kullanmanızı sağlar. Çorapların yumuşak ve rahat dokusu cildinizde lüks bir his yaratır. Bu yün karışımı çorapların sağladığı peluş sıcaklık ile soğuk ayaklara veda edin. Dayanıklılık için tasarlanan çoraplar, takviyeli topuk ve burun ile yüksek aşınma bölgelerine ekstra dayanıklılık sağlar. Bu, çoraplarınızın zamanın testine dayanmasını ve uzun süreli konfor ve rahatlık sağlamasını sağlar. Malzemenin nefes alabilir yapısı aşırı ısınmayı önler, böylece ayaklarınız gün boyunca rahat ve kuru kalır. Kış yürüyüşü için dışarı çıkıyor veya içeride rahatlığın tadını çıkarıyorsanız, bu çoraplar sıcaklık ve nefes alabilirlik arasında mükemmel bir denge sunar. Çeşitli durumlar için uygun ve şık olan bu yün karışımı çorapları favori botlarınızla kombinleyerek kışın modaya uygun bir görünüm elde edebilir veya evde en üst düzeyde rahatlık için giyebilirsiniz. Kış gardırobunuzu yükseltin ve Arctic Warmth Wool Blend Socks ile konforu önceliklendirin. Ayaklarınıza hak ettikleri lüksü sunun ve tüm mevsim boyunca süren bir rahatlık dünyasına adım atın.',
                    'meta-description' => 'meta açıklama',
                    'meta-keywords'    => 'meta1, meta2, meta3',
                    'meta-title'       => 'Meta Başlık',
                    'name'             => 'Arctic Warmth Wool Blend Socks',
                    'sort-description' => 'Arctic Warmth Wool Blend Socks\'un eşsiz sıcaklık ve konforunu deneyimleyin. Merino yünü, akrilik, naylon ve spandex karışımından üretilen bu çoraplar soğuk havalarda en üst düzeyde rahatlık sunar. Dayanıklılık için takviyeli topuk ve buruna sahip olan bu çok yönlü ve şık çoraplar çeşitli durumlar için mükemmeldir.',
                ],

                '5' => [
                    'description'      => 'Arctic Frost Winter Accessories Bundle\'ı tanıtıyoruz, soğuk kış günlerinde sıcak, şık ve bağlantılı kalmak için ideal çözümünüz. Bu özenle seçilmiş set, uyumlu bir bütün oluşturmak için dört temel kış aksesuarını bir araya getirir. Akrilik ve yün karışımından dokunan lüks şal, sadece bir katman sıcaklık katmakla kalmaz, aynı zamanda kış gardırobunuza bir dokunuş zarafet getirir. Özenle tasarlanmış yumuşak örme bere, sizi sıcak tutmayı vaat ederken görünümünüze moda bir hava katar. Ama burada bitmiyor - setimiz ayrıca dokunmatik uyumlu eldivenler içeriyor. Cihazlarınızı kolayca kullanırken sıcaklıktan ödün vermeden bağlantıda kalın. Akıllı telefonunuzda çağrı cevaplarken, mesaj gönderirken veya kış anılarını yakalarken, bu eldivenler stilinizi bozmadan kolaylık sağlar. Çorapların yumuşak ve rahat dokusu cildinizde lüks bir his yaratır. Bu yün karışımı çorapların sağladığı peluş sıcaklık ile soğuk ayaklara veda edin. Arctic Frost Winter Accessories Bundle, sadece işlevsellikle ilgili değildir; aynı zamanda kış modasının bir ifadesidir. Her parça, sizi soğuktan korumanın yanı sıra buzlu mevsimde stilinizi yükseltmek için tasarlanmıştır. Bu paket için seçilen malzemeler dayanıklılık ve konforu önceliklendirir, böylece kış cennetinin tadını stil içinde çıkarabilirsiniz. Kendinizi şımartmak veya mükemmel bir hediye arıyorsanız, Arctic Frost Winter Accessories Bundle çok yönlü bir seçenektir. Tatil sezonunda özel birini mutlu et veya kendi kış gardırobunu bu şık ve işlevsel setle yükselt. Mükemmel aksesuarlara sahip olduğunuzdan emin olarak buzla güvenle kucaklaşın ve sıcak ve şık kalın.',
                    'meta-description' => 'meta açıklama',
                    'meta-keywords'    => 'meta1, meta2, meta3',
                    'meta-title'       => 'Meta Başlık',
                    'name'             => 'Arctic Frost Winter Accessories',
                    'sort-description' => 'Arctic Frost Winter Accessories Bundle ile kışın soğuğunu kucaklayın. Bu özenle seçilmiş set, lüks bir şal, rahat bir bere, dokunmatik uyumlu eldivenler ve yün karışımı çorapları içerir. Şık ve işlevsel olan bu set, dayanıklılık ve konforu sağlamak için yüksek kaliteli malzemelerden üretilmiştir. Kış gardırobunuzu yükseltin veya mükemmel bir hediye seçeneğiyle özel birini mutlu edin.',
                ],

                '6' => [
                    'description'      => 'Arctic Frost Winter Accessories Bundle\'ı tanıtıyoruz, soğuk kış günlerinde sıcak, şık ve bağlantılı kalmak için ideal çözümünüz. Bu özenle seçilmiş set, uyumlu bir bütün oluşturmak için dört temel kış aksesuarını bir araya getirir. Akrilik ve yün karışımından dokunan lüks şal, sadece bir katman sıcaklık katmakla kalmaz, aynı zamanda kış gardırobunuza bir dokunuş zarafet getirir. Özenle tasarlanmış yumuşak örme bere, sizi sıcak tutmayı vaat ederken görünümünüze moda bir hava katar. Ama burada bitmiyor - setimiz ayrıca dokunmatik uyumlu eldivenler içeriyor. Cihazlarınızı kolayca kullanırken sıcaklıktan ödün vermeden bağlantıda kalın. Akıllı telefonunuzda çağrı cevaplarken, mesaj gönderirken veya kış anılarını yakalarken, bu eldivenler stilinizi bozmadan kolaylık sağlar. Çorapların yumuşak ve rahat dokusu cildinizde lüks bir his yaratır. Bu yün karışımı çorapların sağladığı peluş sıcaklık ile soğuk ayaklara veda edin. Arctic Frost Winter Accessories Bundle, sadece işlevsellikle ilgili değildir; aynı zamanda kış modasının bir ifadesidir. Her parça, sizi soğuktan korumanın yanı sıra buzlu mevsimde stilinizi yükseltmek için tasarlanmıştır. Bu paket için seçilen malzemeler dayanıklılık ve konforu önceliklendirir, böylece kış cennetinin tadını stil içinde çıkarabilirsiniz. Kendinizi şımartmak veya mükemmel bir hediye arıyorsanız, Arctic Frost Winter Accessories Bundle çok yönlü bir seçenektir. Tatil sezonunda özel birini mutlu et veya kendi kış gardırobunu bu şık ve işlevsel setle yükselt. Mükemmel aksesuarlara sahip olduğunuzdan emin olarak buzla güvenle kucaklaşın ve sıcak ve şık kalın.',
                    'meta-description' => 'meta açıklama',
                    'meta-keywords'    => 'meta1, meta2, meta3',
                    'meta-title'       => 'Meta Başlık',
                    'name'             => 'Arctic Frost Winter Accessories Bundle',
                    'sort-description' => 'Arctic Frost Winter Accessories Bundle ile kışın soğuğunu kucaklayın. Bu özenle seçilmiş set, lüks bir şal, rahat bir bere, dokunmatik uyumlu eldivenler ve yün karışımı çorapları içerir. Şık ve işlevsel olan bu set, dayanıklılık ve konforu sağlamak için yüksek kaliteli malzemelerden üretilmiştir. Kış gardırobunuzu yükseltin veya mükemmel bir hediye seçeneğiyle özel birini mutlu edin.',
                ],

                '7' => [
                    'description'      => 'OmniHeat Men\'s Solid Hooded Puffer Jacket\'ı tanıtıyoruz, soğuk mevsimlerde sıcak ve şık kalmak için güvenilir bir çözüm. Bu ceket dayanıklılık ve sıcaklık göz önünde bulundurularak tasarlanmıştır, böylece güvendiğiniz bir arkadaşınız haline gelir. Kapüşonlu tasarım sadece bir stil dokunuşu değil, aynı zamanda ekstra sıcaklık sağlar, soğuk rüzgarlardan ve hava koşullarından korur. Tam kollu tasarım, omuzdan bileğe kadar tam kapsama sağlar, böylece sıcak kalırsınız. Eklem cepleri ile donatılan bu puffer ceket, eşyalarınızı taşımak veya ellerinizi sıcak tutmak için kolaylık sağlar. Yalıtımlı sentetik dolgu artırılmış sıcaklık sunar, bu da soğuk günler ve geceler için idealdir. Dayanıklı polyester dış yüzey ve astar ile yapılan bu ceket, dayanıklı ve hava koşullarına dayanıklıdır. 5 çekici renkte mevcut olan bu ceketten stilinize ve tercihinize uygun olanı seçebilirsiniz. Çok yönlü ve işlevsel olan OmniHeat Men\'s Solid Hooded Puffer Jacket, işe gitmek, rahat bir geziye çıkmak veya açık hava etkinliğine katılmak için uygundur. OmniHeat Men\'s Solid Hooded Puffer Jacket ile stil, konfor ve işlevselliğin mükemmel bir karışımını deneyimleyin. Kış gardırobunuzu yükseltin ve dışarıyı kucaklarken sıcak ve rahat kalın. Soğuğa karşı stil sahibi olun ve bu önemli parça ile bir açıklama yapın.',
                    'meta-description' => 'meta açıklama',
                    'meta-keywords'    => 'meta1, meta2, meta3',
                    'meta-title'       => 'Meta Başlık',
                    'name'             => 'OmniHeat Men\'s Solid Hooded Puffer Jacket',
                    'sort-description' => 'OmniHeat Men\'s Solid Hooded Puffer Jacket ile sıcak ve şık kalın. Bu ceket, eklem cepleriyle birlikte maksimum sıcaklık sağlamak için tasarlanmıştır. Yalıtımlı malzeme, soğuk havalarda sizi sıcak tutar. 5 çekici renkte mevcut olması, çeşitli durumlar için çok yönlü bir seçenek yapar.',
                ],

                '8' => [
                    'description'      => 'OmniHeat Erkeklerin Düz Kapüşonlu Puf Montu, soğuk mevsimlerde sıcak ve şık kalmanız için ideal bir çözümdür. Bu mont, dayanıklılık ve sıcaklık gözetilerek tasarlanmış olup güvenilir bir arkadaşınız haline gelir. Kapüşonlu tasarım sadece bir stil dokunuşu eklemekle kalmaz, aynı zamanda ekstra sıcaklık sağlar, soğuk rüzgarlardan ve hava koşullarından sizi korur. Tam kollu tasarımı, omuzdan bileğe kadar sizi sıcacık tutar. Eklem cepleriyle donatılmış olan bu puf mont, eşyalarınızı taşımak veya ellerinizi sıcak tutmak için kolaylık sağlar. Yalıtımlı sentetik dolgu, soğuk günler ve geceler için daha fazla sıcaklık sunar. Dayanıklı polyester dış kabuk ve astardan yapılan bu mont, uzun ömürlü ve hava koşullarına dayanıklıdır. 5 çekici renkte mevcut olması, tarzınıza ve tercihinize uygun olanı seçmenizi sağlar. Çok yönlü ve işlevsel olan OmniHeat Erkeklerin Düz Kapüşonlu Puf Montu, işe giderken, rahat bir geziye çıkarken veya açık havada bir etkinliğe katılırken uygun bir seçenektir. Stil, konfor ve işlevselliğin mükemmel bir karışımını OmniHeat Erkeklerin Düz Kapüşonlu Puf Montu ile deneyimleyin. Kış gardırobunuzu yükseltin ve dışarıyı kucaklarken sıcacık kalın. Soğuğa karşı stil sahibi olun ve bu temel parça ile bir açıklama yapın.',
                    'meta-description' => 'meta açıklama',
                    'meta-keywords'    => 'meta1, meta2, meta3',
                    'meta-title'       => 'Meta Başlık',
                    'name'             => 'OmniHeat Erkeklerin Düz Kapüşonlu Puf Montu-Mavi-Sarı-M',
                    'sort-description' => 'OmniHeat Erkeklerin Düz Kapüşonlu Puf Montu ile sıcak ve şık kalın. Bu mont, ultimate sıcaklık sağlamak ve eklem cepleriyle donatılmış olup ekstra kolaylık sağlamak için tasarlanmıştır. Yalıtımlı malzeme, soğuk havalarda sizi sıcak tutar. 5 çekici renkte mevcut olması, çeşitli durumlar için çok yönlü bir seçenek yapar.',
                ],

                '9' => [
                    'description'      => 'OmniHeat Erkeklerin Düz Kapüşonlu Puf Montu ile soğuk mevsimlerde sıcak ve şık kalmanız için ideal bir çözüm sunuyoruz. Bu mont, dayanıklılık ve sıcaklık gözetilerek tasarlanmış olup güvenilir bir arkadaşınız haline gelir. Kapüşonlu tasarım sadece bir stil dokunuşu eklemekle kalmaz, aynı zamanda ekstra sıcaklık sağlar, soğuk rüzgarlardan ve hava koşullarından sizi korur. Tam kollu tasarımı, omuzdan bileğe kadar sizi sıcacık tutar. Eklem cepleriyle donatılmış olan bu puf mont, eşyalarınızı taşımak veya ellerinizi sıcak tutmak için kolaylık sağlar. Yalıtımlı sentetik dolgu, soğuk günler ve geceler için daha fazla sıcaklık sunar. Dayanıklı polyester dış kabuk ve astardan yapılan bu mont, uzun ömürlü ve hava koşullarına dayanıklıdır. 5 çekici renkte mevcut olması, tarzınıza ve tercihinize uygun olanı seçmenizi sağlar. Çok yönlü ve işlevsel olan OmniHeat Erkeklerin Düz Kapüşonlu Puf Montu, işe giderken, rahat bir geziye çıkarken veya açık havada bir etkinliğe katılırken uygun bir seçenektir. Stil, konfor ve işlevselliğin mükemmel bir karışımını OmniHeat Erkeklerin Düz Kapüşonlu Puf Montu ile deneyimleyin. Kış gardırobunuzu yükseltin ve dışarıyı kucaklarken sıcacık kalın. Soğuğa karşı stil sahibi olun ve bu temel parça ile bir açıklama yapın.',
                    'meta-description' => 'meta açıklama',
                    'meta-keywords'    => 'meta1, meta2, meta3',
                    'meta-title'       => 'Meta Başlık',
                    'name'             => 'OmniHeat Erkeklerin Düz Kapüşonlu Puf Montu-Mavi-Sarı-L',
                    'sort-description' => 'OmniHeat Erkeklerin Düz Kapüşonlu Puf Montu ile sıcak ve şık kalın. Bu mont, ultimate sıcaklık sağlamak ve eklem cepleriyle donatılmış olup ekstra kolaylık sağlamak için tasarlanmıştır. Yalıtımlı malzeme, soğuk havalarda sizi sıcak tutar. 5 çekici renkte mevcut olması, çeşitli durumlar için çok yönlü bir seçenek yapar.',
                ],

                '10' => [
                    'description'      => 'OmniHeat Erkeklerin Düz Kapüşonlu Puf Montu, soğuk mevsimlerde sıcak ve şık kalmanız için ideal bir çözümdür. Bu mont, dayanıklılık ve sıcaklık gözetilerek tasarlanmış olup güvenilir bir arkadaşınız haline gelir. Kapüşonlu tasarım sadece bir stil dokunuşu eklemekle kalmaz, aynı zamanda ekstra sıcaklık sağlar, soğuk rüzgarlardan ve hava koşullarından sizi korur. Tam kollu tasarımı, omuzdan bileğe kadar sizi sıcacık tutar. Eklem cepleriyle donatılmış olan bu puf mont, eşyalarınızı taşımak veya ellerinizi sıcak tutmak için kolaylık sağlar. Yalıtımlı sentetik dolgu, soğuk günler ve geceler için daha fazla sıcaklık sunar. Dayanıklı polyester dış kabuk ve astardan yapılan bu mont, uzun ömürlü ve hava koşullarına dayanıklıdır. 5 çekici renkte mevcut olması, tarzınıza ve tercihinize uygun olanı seçmenizi sağlar. Çok yönlü ve işlevsel olan OmniHeat Erkeklerin Düz Kapüşonlu Puf Montu, işe giderken, rahat bir geziye çıkarken veya açık havada bir etkinliğe katılırken uygun bir seçenektir. Stil, konfor ve işlevselliğin mükemmel bir karışımını OmniHeat Erkeklerin Düz Kapüşonlu Puf Montu ile deneyimleyin. Kış gardırobunuzu yükseltin ve dışarıyı kucaklarken sıcacık kalın. Soğuğa karşı stil sahibi olun ve bu temel parça ile bir açıklama yapın.',
                    'meta-description' => 'meta açıklama',
                    'meta-keywords'    => 'meta1, meta2, meta3',
                    'meta-title'       => 'Meta Başlık',
                    'name'             => 'OmniHeat Erkeklerin Düz Kapüşonlu Puf Montu-Mavi-Yeşil-M',
                    'sort-description' => 'OmniHeat Erkeklerin Düz Kapüşonlu Puf Montu ile sıcak ve şık kalın. Bu mont, ultimate sıcaklık sağlamak ve eklem cepleriyle donatılmış olup ekstra kolaylık sağlamak için tasarlanmıştır. Yalıtımlı malzeme, soğuk havalarda sizi sıcak tutar. 5 çekici renkte mevcut olması, çeşitli durumlar için çok yönlü bir seçenek yapar.',
                ],

                '11' => [
                    'description'      => 'OmniHeat Erkeklerin Düz Kapüşonlu Puf Montu ile sıcak ve şık kalın. Bu mont, soğuk mevsimlerde sıcak ve şık kalmanız için ideal bir çözümdür. Dayanıklılık ve sıcaklık gözetilerek tasarlanmış olup güvenilir bir arkadaşınız haline gelir. Kapüşonlu tasarım sadece bir stil dokunuşu eklemekle kalmaz, aynı zamanda ekstra sıcaklık sağlar, soğuk rüzgarlardan ve hava koşullarından sizi korur. Tam kollu tasarımı, omuzdan bileğe kadar sizi sıcacık tutar. Eklem cepleriyle donatılmış olan bu puf mont, eşyalarınızı taşımak veya ellerinizi sıcak tutmak için kolaylık sağlar. Yalıtımlı sentetik dolgu, soğuk günler ve geceler için daha fazla sıcaklık sunar. Dayanıklı polyester dış kabuk ve astardan yapılan bu mont, uzun ömürlü ve hava koşullarına dayanıklıdır. 5 çekici renkte mevcut olması, tarzınıza ve tercihinize uygun olanı seçmenizi sağlar. Çok yönlü ve işlevsel olan OmniHeat Erkeklerin Düz Kapüşonlu Puf Montu, işe giderken, rahat bir geziye çıkarken veya açık havada bir etkinliğe katılırken uygun bir seçenektir. Stil, konfor ve işlevselliğin mükemmel bir karışımını OmniHeat Erkeklerin Düz Kapüşonlu Puf Montu ile deneyimleyin. Kış gardırobunuzu yükseltin ve dışarıyı kucaklarken sıcacık kalın. Soğuğa karşı stil sahibi olun ve bu temel parça ile bir açıklama yapın.',
                    'meta-description' => 'meta açıklama',
                    'meta-keywords'    => 'meta1, meta2, meta3',
                    'meta-title'       => 'Meta Başlık',
                    'name'             => 'OmniHeat Erkeklerin Düz Kapüşonlu Puf Montu-Mavi-Yeşil-L',
                    'sort-description' => 'OmniHeat Erkeklerin Düz Kapüşonlu Puf Montu ile sıcak ve şık kalın. Bu mont, ultimate sıcaklık sağlamak ve eklem cepleriyle donatılmış olup ekstra kolaylık sağlamak için tasarlanmıştır. Yalıtımlı malzeme, soğuk havalarda sizi sıcak tutar. 5 çekici renkte mevcut olması, çeşitli durumlar için çok yönlü bir seçenek yapar.',
                ],
            ],

            'product-bundle-option-translations' => [
                '1' => [
                    'label' => 'Paket Seçeneği 1',
                ],

                '2' => [
                    'label' => 'Paket Seçeneği 1',
                ],

                '3' => [
                    'label' => 'Paket Seçeneği 2',
                ],

                '4' => [
                    'label' => 'Paket Seçeneği 2',
                ],
            ],
        ],
    ],

    'installer' => [
        'index' => [
            'create-administrator' => [
                'admin'            => 'Yönetici',
                'bagisto'          => 'Bagisto',
                'confirm-password' => 'Şifreyi Onayla',
                'email'            => 'E-posta',
                'email-address'    => 'admin@ornek.com',
                'password'         => 'Şifre',
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
                'warning-message'             => 'Dikkat! Varsayılan sistem dili ve varsayılan para birimi ayarları kalıcıdır ve bir kez ayarlandığında değiştirilemez.',
                'zambian-kwacha'              => 'Zambiya Kvaçası (ZMW)',
            ],

            'sample-products' => [
                'download-sample' => 'örnek indir',
                'no'              => 'Hayır',
                'sample-products' => 'Örnek Ürünler',
                'title'           => 'Örnek Ürünler',
                'yes'             => 'Evet',
            ],

            'installation-processing' => [
                'bagisto'      => 'Bagisto Kurulumu',
                'bagisto-info' => 'Veritabanı tabloları oluşturuluyor, bu birkaç dakika sürebilir',
                'title'        => 'Kurulum',
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
                'welcome-title' => 'Bagisto\'ya hoş geldiniz',
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
            'installation-description' => 'Bagisto kurulumu genellikle birkaç adım içerir. İşte Bagisto\'nun kurulum sürecine genel bir bakış',
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
