<?php

return [
    'seeders' => [
        'attribute' => [
            'attribute-families' => [
                'default' => 'डिफ़ॉल्ट',
            ],

            'attribute-groups' => [
                'description'       => 'विवरण',
                'general'           => 'सामान्य',
                'inventories'       => 'इन्वेंटरी',
                'meta-description'  => 'मेटा विवरण',
                'price'             => 'मूल्य',
                'shipping'          => 'शिपिंग',
                'settings'          => 'सेटिंग्स',
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
                'meta-title'           => 'मेटा शीर्षक',
                'meta-keywords'        => 'मेटा कीवर्ड्स',
                'meta-description'     => 'मेटा विवरण',
                'manage-stock'         => 'स्टॉक प्रबंधन',
                'new'                  => 'नया',
                'name'                 => 'नाम',
                'product-number'       => 'उत्पाद संख्या',
                'price'                => 'मूल्य',
                'sku'                  => 'SKU',
                'status'               => 'स्थिति',
                'short-description'    => 'संक्षेप विवरण',
                'special-price'        => 'विशेष मूल्य',
                'special-price-from'   => 'विशेष मूल्य से',
                'special-price-to'     => 'विशेष मूल्य तक',
                'size'                 => 'साइज़',
                'tax-category'         => 'कर श्रेणी',
                'url-key'              => 'URL कुंजी',
                'visible-individually' => 'व्यक्तिगत रूप से दिखाएं',
                'width'                => 'चौड़ाई',
                'weight'               => 'वजन',
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

                'refund-policy' => [
                    'content' => 'वापसी नीति पृष्ठ सामग्री',
                    'title'   => 'वापसी नीति',
                ],

                'return-policy' => [
                    'content' => 'वापसी नीति पृष्ठ सामग्री',
                    'title'   => 'वापसी नीति',
                ],

                'terms-conditions' => [
                    'content' => 'नियम और शर्तों पृष्ठ सामग्री',
                    'title'   => 'नियम और शर्तें',
                ],

                'terms-of-use' => [
                    'content' => 'उपयोग की शर्तें पृष्ठ सामग्री',
                    'title'   => 'उपयोग की शर्तें',
                ],

                'contact-us' => [
                    'content' => 'हमसे संपर्क करें पृष्ठ सामग्री',
                    'title'   => 'हमसे संपर्क करें',
                ],

                'customer-service' => [
                    'content' => 'ग्राहक सेवा पृष्ठ सामग्री',
                    'title'   => 'ग्राहक सेवा',
                ],

                'whats-new' => [
                    'content' => 'नई चीजें पृष्ठ सामग्री',
                    'title'   => 'नई चीजें',
                ],

                'payment-policy' => [
                    'content' => 'भुगतान नीति पृष्ठ सामग्री',
                    'title'   => 'भुगतान नीति',
                ],

                'shipping-policy' => [
                    'content' => 'शिपिंग नीति पृष्ठ सामग्री',
                    'title'   => 'शिपिंग नीति',
                ],

                'privacy-policy' => [
                    'content' => 'गोपनीयता नीति पृष्ठ सामग्री',
                    'title'   => 'गोपनीयता नीति',
                ],
            ],
        ],

        'core' => [
            'channels' => [
                'meta-title'       => 'डेमो स्टोर',
                'meta-keywords'    => 'डेमो स्टोर मेटा कीवर्ड',
                'meta-description' => 'डेमो स्टोर मेटा विवरण',
                'name'             => 'डिफ़ॉल्ट',
            ],

            'currencies' => [
                'CNY' => 'चीनी युआन',
                'AED' => 'दिर्हम',
                'EUR' => 'यूरो',
                'INR' => 'भारतीय रुपया',
                'IRR' => 'ईरानी रियाल',
                'ILS' => 'इज़राइली शेकेल',
                'JPY' => 'जापानी येन',
                'GBP' => 'पौंड स्टर्लिंग',
                'RUB' => 'रूसी रूबल',
                'SAR' => 'सउदी रियाल',
                'TRY' => 'तुर्की लीरा',
                'USD' => 'यूएस डॉलर',
                'UAH' => 'यूक्रेनियन ह्रिव्निया',
            ],

            'locales' => [
                'ar'    => 'अरबी',
                'bn'    => 'बंगाली',
                'de'    => 'जर्मन',
                'es'    => 'स्पेनिश',
                'en'    => 'अंग्रेज़ी',
                'fr'    => 'फ्रेंच',
                'fa'    => 'फारसी',
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
                'guest'     => 'अतिथि',
                'general'   => 'सामान्य',
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
                'image-carousel' => [
                    'name'  => 'चित्र स्लाइडर',

                    'sliders' => [
                        'title' => 'नई संग्रह के लिए तैयार रहें',
                    ],
                ],

                'offer-information' => [
                    'name' => 'ऑफ़र जानकारी',

                    'content' => [
                        'title' => 'अपने पहले आर्डर पर 40% तक की छूट पाएं, अब खरीदें',
                    ],
                ],

                'categories-collections' => [
                    'name' => 'श्रेणियाँ संग्रह',
                ],

                'new-products' => [
                    'name' => 'नई उत्पाद',

                    'options' => [
                        'title' => 'नई उत्पाद',
                    ],
                ],

                'top-collections' => [
                    'name' => 'शीर्ष संग्रह',

                    'content' => [
                        'sub-title-1' => 'हमारी संग्रह',
                        'sub-title-2' => 'हमारी संग्रह',
                        'sub-title-3' => 'हमारी संग्रह',
                        'sub-title-4' => 'हमारी संग्रह',
                        'sub-title-5' => 'हमारी संग्रह',
                        'sub-title-6' => 'हमारी संग्रह',
                        'title'       => 'हमारे नए योगदान के साथ खेल!',
                    ],
                ],

                'bold-collections' => [
                    'name' => 'बोल्ड संग्रह',

                    'content' => [
                        'btn-title'   => 'सभी देखें',
                        'description' => 'हमारी नई बोल्ड संग्रह का परिचय! साहसी डिज़ाइन और जीवंत कथनों के साथ अपनी शैली को उन्नत करें. हरित पैटर्न और बोल्ड रंगों की खोज करें जो आपके वस्त्र को पुनर्निर्भर कर देते हैं. असाधारण को ग्रहण करने के लिए तैयार हो जाइए!',
                        'title'       => 'हमारे नए बोल्ड संग्रह के लिए तैयार हो जाइए!',
                    ],
                ],

                'featured-collections' => [
                    'name' => 'विशेष संग्रह',

                    'options' => [
                        'title' => 'विशेष उत्पाद',
                    ],
                ],

                'game-container' => [
                    'name' => 'खेल संदूक',

                    'content' => [
                        'sub-title-1' => 'हमारी संग्रह',
                        'sub-title-2' => 'हमारी संग्रह',
                        'title'       => 'हमारे नए योगदान के साथ खेल!',
                    ],
                ],

                'all-products' => [
                    'name' => 'सभी उत्पाद',

                    'options' => [
                        'title' => 'सभी उत्पाद',
                    ],
                ],

                'footer-links' => [
                    'name' => 'फ़ूटर लिंक्स',

                    'options' => [
                        'about-us'         => 'हमारे बारे में',
                        'contact-us'       => 'हमसे संपर्क करें',
                        'customer-service' => 'ग्राहक सेवा',
                        'privacy-policy'   => 'गोपनीयता नीति',
                        'payment-policy'   => 'भुगतान नीति',
                        'return-policy'    => 'वापसी नीति',
                        'refund-policy'    => 'धन वापसी नीति',
                        'shipping-policy'  => 'शिपिंग नीति',
                        'terms-of-use'     => 'उपयोग की शर्तें',
                        'terms-conditions' => 'शर्तें और गोपनीयता',
                        'whats-new'        => 'नई चीजें',
                    ],
                ],

                'services-content' => [
                    'name'  => 'सेवाओं की सामग्री',

                    'title' => [
                        'free-shipping'     => 'मुफ्त शिपिंग',
                        'product-replace'   => 'उत्पाद बदलें',
                        'emi-available'     => 'EMI उपलब्ध',
                        'time-support'      => '24/7 समर्थन',
                    ],

                    'description' => [
                        'free-shipping-info'     => 'सभी आदेशों पर मुफ्त शिपिंग का आनंद लें',
                        'product-replace-info'   => 'आसान उत्पाद बदलाव उपलब्ध!',
                        'emi-available-info'     => 'सभी प्रमुख क्रेडिट कार्डों पर बिना लागत EMI उपलब्ध',
                        'time-support-info'      => 'चैट और ईमेल के माध्यम से समर्पित 24/7 समर्थन',
                    ],
                ],
            ],
        ],

        'user' => [
            'users' => [
                'name' => 'उदाहरण',
            ],

            'roles' => [
                'description' => 'इस भूमिका वाले उपयोगकर्ताओं को सभी पहुंच होगी',
                'name'        => 'प्रशासक',
            ],
        ],
    ],
];
