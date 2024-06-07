<?php

return [
    'seeders' => [
        'attribute' => [
            'attribute-families' => [
                'default' => 'डिफ़ॉल्ट',
            ],

            'attribute-groups' => [
                'description'      => 'विवरण',
                'general'          => 'सामान्य',
                'inventories'      => 'इन्वेंटरी',
                'meta-description' => 'मेटा विवरण',
                'price'            => 'मूल्य',
                'settings'         => 'सेटिंग्स',
                'shipping'         => 'शिपिंग',
            ],

            'attributes' => [
                'brand'                => 'ब्रांड',
                'color'                => 'रंग',
                'cost'                 => 'लागत',
                'description'          => 'विवरण',
                'featured'             => 'लोकप्रिय',
                'guest-checkout'       => 'मेहमान चेकआउट',
                'height'               => 'ऊँचाई',
                'length'               => 'लंबाई',
                'manage-stock'         => 'स्टॉक प्रबंधन',
                'meta-description'     => 'मेटा विवरण',
                'meta-keywords'        => 'मेटा कीवर्ड्स',
                'meta-title'           => 'मेटा शीर्षक',
                'name'                 => 'नाम',
                'new'                  => 'नया',
                'price'                => 'मूल्य',
                'product-number'       => 'उत्पाद संख्या',
                'short-description'    => 'संक्षेप विवरण',
                'size'                 => 'साइज़',
                'sku'                  => 'SKU',
                'special-price'        => 'विशेष मूल्य',
                'special-price-from'   => 'विशेष मूल्य से',
                'special-price-to'     => 'विशेष मूल्य तक',
                'status'               => 'स्थिति',
                'tax-category'         => 'कर श्रेणी',
                'url-key'              => 'URL कुंजी',
                'visible-individually' => 'व्यक्तिगत रूप से दिखाएं',
                'weight'               => 'वजन',
                'width'                => 'चौड़ाई',
            ],

            'attribute-options' => [
                'black'  => 'काला',
                'green'  => 'हरा',
                'l'      => 'L',
                'm'      => 'M',
                'red'    => 'लाल',
                's'      => 'S',
                'white'  => 'सफेद',
                'xl'     => 'XL',
                'yellow' => 'पीला',
            ],
        ],

        'category' => [
            'categories' => [
                'description' => 'रूख श्रेणी विवरण',
                'name'        => 'रूख',
            ],
        ],

        'cms' => [
            'pages' => [
                'about-us' => [
                    'content' => 'हमारे बारे में पृष्ठ सामग्री',
                    'title'   => 'हमारे बारे में',
                ],

                'contact-us' => [
                    'content' => 'हमसे संपर्क करें पृष्ठ सामग्री',
                    'title'   => 'हमसे संपर्क करें',
                ],

                'customer-service' => [
                    'content' => 'ग्राहक सेवा पृष्ठ सामग्री',
                    'title'   => 'ग्राहक सेवा',
                ],

                'payment-policy' => [
                    'content' => 'भुगतान नीति पृष्ठ सामग्री',
                    'title'   => 'भुगतान नीति',
                ],

                'privacy-policy' => [
                    'content' => 'गोपनीयता नीति पृष्ठ सामग्री',
                    'title'   => 'गोपनीयता नीति',
                ],

                'refund-policy' => [
                    'content' => 'वापसी नीति पृष्ठ सामग्री',
                    'title'   => 'वापसी नीति',
                ],

                'return-policy' => [
                    'content' => 'वापसी नीति पृष्ठ सामग्री',
                    'title'   => 'वापसी नीति',
                ],

                'shipping-policy' => [
                    'content' => 'शिपिंग नीति पृष्ठ सामग्री',
                    'title'   => 'शिपिंग नीति',
                ],

                'terms-conditions' => [
                    'content' => 'नियम और शर्तों पृष्ठ सामग्री',
                    'title'   => 'नियम और शर्तें',
                ],

                'terms-of-use' => [
                    'content' => 'उपयोग की शर्तें पृष्ठ सामग्री',
                    'title'   => 'उपयोग की शर्तें',
                ],

                'whats-new' => [
                    'content' => 'नई चीजें पृष्ठ सामग्री',
                    'title'   => 'नई चीजें',
                ],
            ],
        ],

        'core' => [
            'channels' => [
                'name'             => 'डिफ़ॉल्ट',
                'meta-title'       => 'डेमो स्टोर',
                'meta-keywords'    => 'डेमो स्टोर मेटा कीवर्ड',
                'meta-description' => 'डेमो स्टोर मेटा विवरण',
            ],

            'currencies' => [
                'AED' => 'संयुक्त अरब अमीरात दिरहम',
                'ARS' => 'अर्जेंटीनी पेसो',
                'AUD' => 'ऑस्ट्रेलियाई डॉलर',
                'BDT' => 'बांगलादेशी टाका',
                'BRL' => 'ब्राज़ीली रियाल',
                'CAD' => 'कनाडाई डॉलर',
                'CHF' => 'स्विस फ़्रैंक',
                'CLP' => 'चिली पेसो',
                'CNY' => 'चीनी युआन',
                'COP' => 'कोलंबियाई पेसो',
                'CZK' => 'चेक कोरुना',
                'DKK' => 'डेनिश क्रोन',
                'DZD' => 'अल्जीरियाई दिनार',
                'EGP' => 'मिस्री पाउंड',
                'EUR' => 'यूरो',
                'FJD' => 'फ़िजी डॉलर',
                'GBP' => 'ब्रिटिश पाउंड स्टर्लिंग',
                'HKD' => 'हांगकांग डॉलर',
                'HUF' => 'हंगेरियन फ़ोरिंट',
                'IDR' => 'इंडोनेशियाई रुपिया',
                'ILS' => 'इज़राइली नया शेकेल',
                'INR' => 'भारतीय रुपया',
                'JOD' => 'जॉर्डनियाई दिनार',
                'JPY' => 'जापानी येन',
                'KRW' => 'दक्षिण कोरियाई वॉन',
                'KWD' => 'कुवैती दिनार',
                'KZT' => 'कज़ाख़ी तेंगे',
                'LBP' => 'लेबनानी पाउंड',
                'LKR' => 'श्रीलंकाई रुपया',
                'LYD' => 'लीबियाई दिनार',
                'MAD' => 'मोरक्को दिरहम',
                'MUR' => 'मॉरिशियन रुपया',
                'MXN' => 'मैक्सिकन पेसो',
                'MYR' => 'मलेशियाई रिंगिट',
                'NGN' => 'नाइजीरियाई नायरा',
                'NOK' => 'नॉर्वेजियाई क्रोन',
                'NPR' => 'नेपाली रुपया',
                'NZD' => 'न्यूज़ीलैंड डॉलर',
                'OMR' => 'ओमानी रियाल',
                'PAB' => 'पनामेनियाई बालबोआ',
                'PEN' => 'पेरूवियाई नया सोल',
                'PHP' => 'फ़िलिपीनी पेसो',
                'PKR' => 'पाकिस्तानी रुपया',
                'PLN' => 'पोलिश ज़्लॉटी',
                'PYG' => 'पैराग्वियाई ग्वारानी',
                'QAR' => 'क़तरी रियाल',
                'RON' => 'रोमानियाई ल्यू',
                'RUB' => 'रूसी रूबल',
                'SAR' => 'सउदी रियाल',
                'SEK' => 'स्वीडिश क्रोना',
                'SGD' => 'सिंगापुर डॉलर',
                'THB' => 'थाई बाह्ट',
                'TND' => 'ट्यूनीशियाई दिनार',
                'TRY' => 'तुर्की लीरा',
                'TWD' => 'नया ताइवानी डॉलर',
                'UAH' => 'यूक्रेनियन ह्रीवनिया',
                'USD' => 'अमेरिकी डॉलर',
                'UZS' => 'उज़्बेकिस्तानी सोम',
                'VEF' => 'वेनेज़ुएलाई बोलिवर',
                'VND' => 'वियतनामी डॉंग',
                'XAF' => 'सीएफए फ्रैंक बीईएसईए',
                'XOF' => 'सीएफए फ्रैंक बीसीईएओ',
                'ZAR' => 'दक्षिण अफ़्रीकी रैंड',
                'ZMW' => 'ज़ाम्बियाई क्वाचा',
            ],

            'locales'    => [
                'ar'    => 'अरबी',
                'bn'    => 'बंगाली',
                'de'    => 'जर्मन',
                'en'    => 'अंग्रेज़ी',
                'es'    => 'स्पेनिश',
                'fa'    => 'फारसी',
                'fr'    => 'फ्रेंच',
                'he'    => 'हिब्रू',
                'hi_IN' => 'हिंदी',
                'it'    => 'इटैलियन',
                'ja'    => 'जैपनी',
                'nl'    => 'डच',
                'pl'    => 'पोलिश',
                'pt_BR' => 'ब्राज़ीलियाई पुर्तगाली',
                'ru'    => 'रूसी',
                'sin'   => 'सिंहला',
                'tr'    => 'तुर्की',
                'uk'    => 'यूक्रेनियन',
                'zh_CN' => 'चीनी',
            ],
        ],

        'customer' => [
            'customer-groups' => [
                'general'   => 'सामान्य',
                'guest'     => 'अतिथि',
                'wholesale' => 'थोक',
            ],
        ],

        'inventory' => [
            'inventory-sources' => [
                'name' => 'डिफ़ॉल्ट',
            ],
        ],

        'shop' => [
            'theme-customizations' => [
                'all-products' => [
                    'name' => 'सभी उत्पाद',

                    'options' => [
                        'title' => 'सभी उत्पाद',
                    ],
                ],

                'bold-collections' => [
                    'content' => [
                        'btn-title'   => 'संग्रह देखें',
                        'description' => 'हमारी नई बोल्ड संग्रह का परिचय! साहसी डिज़ाइन और जीवंत कथनों के साथ अपनी शैली को उन्नत करें. हरित पैटर्न और बोल्ड रंगों की खोज करें जो आपके वस्त्र को पुनर्निर्भर कर देते हैं. असाधारण को ग्रहण करने के लिए तैयार हो जाइए!',
                        'title'       => 'हमारे नए बोल्ड संग्रह के लिए तैयार हो जाइए!',
                    ],

                    'name' => 'बोल्ड संग्रह',
                ],

                'categories-collections' => [
                    'name' => 'श्रेणियाँ संग्रह',
                ],

                'featured-collections'   => [
                    'name' => 'विशेष संग्रह',

                    'options' => [
                        'title' => 'विशेष उत्पाद',
                    ],
                ],

                'footer-links' => [
                    'name' => 'फ़ूटर लिंक्स',

                    'options' => [
                        'about-us'         => 'हमारे बारे में',
                        'contact-us'       => 'हमसे संपर्क करें',
                        'customer-service' => 'ग्राहक सेवा',
                        'payment-policy'   => 'भुगतान नीति',
                        'privacy-policy'   => 'गोपनीयता नीति',
                        'refund-policy'    => 'धन वापसी नीति',
                        'return-policy'    => 'वापसी नीति',
                        'shipping-policy'  => 'शिपिंग नीति',
                        'terms-conditions' => 'शर्तें और गोपनीयता',
                        'terms-of-use'     => 'उपयोग की शर्तें',
                        'whats-new'        => 'नई चीजें',
                    ],
                ],

                'game-container' => [
                    'content' => [
                        'sub-title-1' => 'हमारी संग्रह',
                        'sub-title-2' => 'हमारी संग्रह',
                        'title'       => 'हमारे नए योगदान के साथ खेल!',
                    ],

                    'name' => 'खेल संदूक',
                ],

                'image-carousel' => [
                    'name' => 'चित्र स्लाइडर',

                    'sliders' => [
                        'title' => 'नई संग्रह के लिए तैयार रहें',
                    ],
                ],

                'new-products' => [
                    'name' => 'नई उत्पाद',

                    'options' => [
                        'title' => 'नई उत्पाद',
                    ],
                ],

                'offer-information' => [
                    'content' => [
                        'title' => 'अपने पहले आर्डर पर 40% तक की छूट पाएं, अब खरीदें',
                    ],

                    'name' => 'ऑफ़र जानकारी',
                ],

                'services-content' => [
                    'description' => [
                        'emi-available-info'   => 'सभी प्रमुख क्रेडिट कार्डों पर बिना लागत EMI उपलब्ध',
                        'free-shipping-info'   => 'सभी आदेशों पर मुफ्त शिपिंग का आनंद लें',
                        'product-replace-info' => 'आसान उत्पाद बदलाव उपलब्ध!',
                        'time-support-info'    => 'चैट और ईमेल के माध्यम से समर्पित 24/7 समर्थन',
                    ],

                    'name' => 'सेवाओं की सामग्री',

                    'title' => [
                        'emi-available'   => 'EMI उपलब्ध',
                        'free-shipping'   => 'मुफ्त शिपिंग',
                        'product-replace' => 'उत्पाद बदलें',
                        'time-support'    => '24/7 समर्थन',
                    ],
                ],

                'top-collections' => [
                    'content' => [
                        'sub-title-1' => 'हमारी संग्रह',
                        'sub-title-2' => 'हमारी संग्रह',
                        'sub-title-3' => 'हमारी संग्रह',
                        'sub-title-4' => 'हमारी संग्रह',
                        'sub-title-5' => 'हमारी संग्रह',
                        'sub-title-6' => 'हमारी संग्रह',
                        'title'       => 'हमारे नए योगदान के साथ खेल!',
                    ],

                    'name' => 'शीर्ष संग्रह',
                ],
            ],
        ],

        'user' => [
            'roles' => [
                'description' => 'इस भूमिका वाले उपयोगकर्ताओं को सभी पहुंच होगी',
                'name'        => 'प्रशासक',
            ],

            'users' => [
                'name' => 'उदाहरण',
            ],
        ],
    ],

    'installer' => [
        'index' => [
            'create-administrator' => [
                'admin'            => 'व्यवस्थापक',
                'bagisto'          => 'बैगिस्टो',
                'confirm-password' => 'पासवर्ड की पुष्टि करें',
                'download-sample'  => 'नमूना डाउनलोड करें',
                'email'            => 'ईमेल',
                'email-address'    => 'admin@example.com',
                'password'         => 'पासवर्ड',
                'sample-products'  => 'नमूना उत्पाद',
                'title'            => 'व्यवस्थापक बनाएं',
            ],

            'environment-configuration' => [
                'algerian-dinar'              => 'अल्जीरियाई दीनार (DZD)',
                'allowed-currencies'          => 'अनुमति प्राप्त मुद्राएँ',
                'allowed-locales'             => 'अनुमति प्राप्त स्थान',
                'application-name'            => 'एप्लिकेशन का नाम',
                'argentine-peso'              => 'अर्जेंटीनी पेसो (ARS)',
                'australian-dollar'           => 'ऑस्ट्रेलियाई डॉलर (AUD)',
                'bagisto'                     => 'बैगिस्टो',
                'bangladeshi-taka'            => 'बांगलादेशी टाका (BDT)',
                'brazilian-real'              => 'ब्राज़ीली रियाल (BRL)',
                'british-pound-sterling'      => 'ब्रिटिश पाउंड स्टर्लिंग (GBP)',
                'canadian-dollar'             => 'कनाडाई डॉलर (CAD)',
                'cfa-franc-bceao'             => 'सीएफए फ्रांक BCEAO (XOF)',
                'cfa-franc-beac'              => 'सीएफए फ्रांक BEAC (XAF)',
                'chilean-peso'                => 'चिली पेसो (CLP)',
                'chinese-yuan'                => 'चीनी युआन (CNY)',
                'colombian-peso'              => 'कोलंबियाई पेसो (COP)',
                'czech-koruna'                => 'चेक कोरुना (CZK)',
                'danish-krone'                => 'डेनिश क्रोन (DKK)',
                'database-connection'         => 'डेटाबेस कनेक्शन',
                'database-hostname'           => 'डेटाबेस होस्टनेम',
                'database-name'               => 'डेटाबेस का नाम',
                'database-password'           => 'डेटाबेस पासवर्ड',
                'database-port'               => 'डेटाबेस पोर्ट',
                'database-prefix'             => 'डेटाबेस प्रीफ़िक्स',
                'database-username'           => 'डेटाबेस उपयोगकर्ता नाम',
                'default-currency'            => 'डिफ़ॉल्ट मुद्रा',
                'default-locale'              => 'डिफ़ॉल्ट स्थान',
                'default-timezone'            => 'डिफ़ॉल्ट समय क्षेत्र',
                'default-url'                 => 'डिफ़ॉल्ट URL',
                'default-url-link'            => 'https://localhost',
                'egyptian-pound'              => 'मिस्री पाउंड (EGP)',
                'euro'                        => 'यूरो (EUR)',
                'fijian-dollar'               => 'फ़िजी डॉलर (FJD)',
                'hong-kong-dollar'            => 'हांगकांग डॉलर (HKD)',
                'hungarian-forint'            => 'हंगेरियन फ़ोरिंट (HUF)',
                'indian-rupee'                => 'भारतीय रुपया (INR)',
                'indonesian-rupiah'           => 'इंडोनेशियाई रुपिया (IDR)',
                'israeli-new-shekel'          => 'इज़राइली नया शेकेल (ILS)',
                'japanese-yen'                => 'जापानी येन (JPY)',
                'jordanian-dinar'             => 'जॉर्डनी दीनार (JOD)',
                'kazakhstani-tenge'           => 'कज़ाख़स्तानी टेंज़ (KZT)',
                'kuwaiti-dinar'               => 'कुवैती दीनार (KWD)',
                'lebanese-pound'              => 'लेबनानी पाउंड (LBP)',
                'libyan-dinar'                => 'लीबियाई दीनार (LYD)',
                'malaysian-ringgit'           => 'मलेशियाई रिंगित (MYR)',
                'mauritian-rupee'             => 'मॉरिशियन रुपया (MUR)',
                'mexican-peso'                => 'मैक्सिकन पेसो (MXN)',
                'moroccan-dirham'             => 'मोरक्कन दिरहम (MAD)',
                'mysql'                       => 'Mysql',
                'nepalese-rupee'              => 'नेपाली रुपया (NPR)',
                'new-taiwan-dollar'           => 'नया ताइवान डॉलर (TWD)',
                'new-zealand-dollar'          => 'न्यूज़ीलैंड डॉलर (NZD)',
                'nigerian-naira'              => 'नाइजीरियाई नायरा (NGN)',
                'norwegian-krone'             => 'नॉर्वेजियन क्रोन (NOK)',
                'omani-rial'                  => 'ओमानी रियाल (OMR)',
                'pakistani-rupee'             => 'पाकिस्तानी रुपया (PKR)',
                'panamanian-balboa'           => 'पनामा बाल्बोआ (PAB)',
                'paraguayan-guarani'          => 'पैराग्वेयन ग्वारानी (PYG)',
                'peruvian-nuevo-sol'          => 'पेरूवियन नया सोल (PEN)',
                'pgsql'                       => 'pgSQL',
                'philippine-peso'             => 'फिलीपीनी पेसो (PHP)',
                'polish-zloty'                => 'पोलिश ज़्लॉटी (PLN)',
                'qatari-rial'                 => 'क़तारी रियाल (QAR)',
                'romanian-leu'                => 'रोमानियाई ल्यू (RON)',
                'russian-ruble'               => 'रूसी रूबल (RUB)',
                'saudi-riyal'                 => 'सऊदी रियाल (SAR)',
                'select-timezone'             => 'समय क्षेत्र का चयन करें',
                'singapore-dollar'            => 'सिंगापुर डॉलर (SGD)',
                'south-african-rand'          => 'दक्षिण अफ़्रीकी रैंड (ZAR)',
                'south-korean-won'            => 'दक्षिण कोरियाई वॉन (KRW)',
                'sqlsrv'                      => 'SQLSRV',
                'sri-lankan-rupee'            => 'श्रीलंकाई रुपया (LKR)',
                'swedish-krona'               => 'स्वीडिश क्रोना (SEK)',
                'swiss-franc'                 => 'स्विस फ़्रैंक (CHF)',
                'thai-baht'                   => 'थाई बाहत (THB)',
                'title'                       => 'स्टोर कॉन्फ़िगरेशन',
                'tunisian-dinar'              => 'ट्यूनीशियाई दीनार (TND)',
                'turkish-lira'                => 'तुर्की लीरा (TRY)',
                'ukrainian-hryvnia'           => 'यूक्रेनी ह्रीवनिया (UAH)',
                'united-arab-emirates-dirham' => 'संयुक्त अरब अमीरात दिरहम (AED)',
                'united-states-dollar'        => 'संयुक्त राज्य अमेरिकी डॉलर (USD)',
                'uzbekistani-som'             => 'उज़्बेकिस्तानी सोम (UZS)',
                'venezuelan-bolívar'          => 'वेनेज़ुएलाई बोलिवार (VEF)',
                'vietnamese-dong'             => 'वियतनामी डॉंग (VND)',
                'warning-message'             => 'सावधान रहें! आपके डिफ़ॉल्ट सिस्टम भाषाओं और डिफ़ॉल्ट मुद्रा की सेटिंग्स स्थायी हैं और इसे फिर से कभी नहीं बदला जा सकता है।',
                'zambian-kwacha'              => 'ज़ाम्बियाई क्वाचा (ZMW)',
            ],

            'installation-processing' => [
                'bagisto'          => 'बैगिस्टो स्थापना',
                'bagisto-info'     => 'डेटाबेस तालिकाएँ बनाने का प्रक्रियाण, इसमें कुछ क्षण लग सकते हैं',
                'title'            => 'स्थापना',
            ],

            'installation-completed' => [
                'admin-panel'                => 'व्यवस्थापक पैनल',
                'bagisto-forums'             => 'Bagisto फ़ोरम',
                'customer-panel'             => 'ग्राहक पैनल',
                'explore-bagisto-extensions' => 'Bagisto एक्सटेंशन अन्वेषण करें',
                'title'                      => 'स्थापना पूर्ण',
                'title-info'                 => 'बैगिस्टो को आपके सिस्टम पर सफलतापूर्वक स्थापित किया गया है।',
            ],

            'ready-for-installation' => [
                'create-database-table'   => 'डेटाबेस तालिका बनाएं',
                'install'                 => 'स्थापना',
                'install-info'            => 'स्थापना के लिए Bagisto',
                'install-info-button'     => 'नीचे दिए गए बटन पर क्लिक करें',
                'populate-database-table' => 'डेटाबेस तालिकाओं को पॉप्युलेट करें',
                'start-installation'      => 'स्थापना शुरू करें',
                'title'                   => 'स्थापना के लिए तैयार',
            ],

            'start' => [
                'locale'        => 'स्थान',
                'main'          => 'शुरू',
                'select-locale' => 'स्थान चुनें',
                'title'         => 'आपका Bagisto स्थापित करें',
                'welcome-title' => 'Bagisto 2.0 में आपका स्वागत है।',
            ],

            'server-requirements' => [
                'calendar'    => 'कैलेंडर',
                'ctype'       => 'सीटाइप',
                'curl'        => 'सीयूआरएल',
                'dom'         => 'डॉम',
                'fileinfo'    => 'फ़ाइलइन्फो',
                'filter'      => 'फ़िल्टर',
                'gd'          => 'जीडी',
                'hash'        => 'हैश',
                'intl'        => 'Intl',
                'json'        => 'जेसन',
                'mbstring'    => 'एमबीस्ट्रिंग',
                'openssl'     => 'ओपनएसएसएल',
                'pcre'        => 'पीसीआरई',
                'pdo'         => 'पीडीओ',
                'php'         => 'पीएचपी',
                'php-version' => '8.1 या उच्च',
                'session'     => 'सत्र',
                'title'       => 'सर्वर आवश्यकताएँ',
                'tokenizer'   => 'टोकनाइज़र',
                'xml'         => 'एक्सएमएल',
            ],

            'back'                     => 'वापस',
            'arabic'                   => 'अरबी',
            'bagisto'                  => 'बैगिस्टो',
            'bagisto-info'             => 'एक सामुदायिक परियोजना द्वारा',
            'bagisto-logo'             => 'बैगिस्टो लोगो',
            'bengali'                  => 'बंगाली',
            'chinese'                  => 'चीनी',
            'continue'                 => 'जारी रखें',
            'dutch'                    => 'डच',
            'english'                  => 'अंग्रेज़ी',
            'french'                   => 'फ्रेंच',
            'german'                   => 'जर्मन',
            'hebrew'                   => 'हिब्रू',
            'hindi'                    => 'हिंदी',
            'installation-description' => 'बैगिस्टो स्थापना आमतौर पर कई कदमों में होती है। यहां बैगिस्टो के लिए स्थापना प्रक्रिया की सामान्य रूपरेखा है:',
            'installation-info'        => 'हमें यहाँ आपको खुश देखकर अच्छा लग रहा है!',
            'installation-title'       => 'स्थापना में आपका स्वागत है',
            'italian'                  => 'इतालवी',
            'japanese'                 => 'जापानी',
            'persian'                  => 'फारसी',
            'polish'                   => 'पोलिश',
            'portuguese'               => 'ब्राजीलियाई पुर्तगाली',
            'russian'                  => 'रूसी',
            'sinhala'                  => 'सिंहला',
            'spanish'                  => 'स्पेनिश',
            'title'                    => 'बैगिस्टो स्थापक',
            'turkish'                  => 'तुर्की',
            'ukrainian'                => 'यूक्रेनी',
            'webkul'                   => 'वेबकुल',
        ],
    ],
];
