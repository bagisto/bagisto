<?php

return [
    'seeders'   => [
        'attribute' => [
            'attribute-families' => [
                'default' => 'डिफ़ॉल्ट',
            ],

            'attribute-groups'   => [
                'description'       => 'विवरण',
                'general'           => 'सामान्य',
                'inventories'       => 'इन्वेंटरी',
                'meta-description'  => 'मेटा विवरण',
                'price'             => 'मूल्य',
                'settings'          => 'सेटिंग्स',
                'shipping'          => 'शिपिंग',
            ],

            'attributes'         => [
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
                'special-price-from'   => 'विशेष मूल्य से',
                'special-price-to'     => 'विशेष मूल्य तक',
                'special-price'        => 'विशेष मूल्य',
                'status'               => 'स्थिति',
                'tax-category'         => 'कर श्रेणी',
                'url-key'              => 'URL कुंजी',
                'visible-individually' => 'व्यक्तिगत रूप से दिखाएं',
                'weight'               => 'वजन',
                'width'                => 'चौड़ाई',
            ],

            'attribute-options'  => [
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

        'category'  => [
            'categories' => [
                'description' => 'रूख श्रेणी विवरण',
                'name'        => 'रूख',
            ],
        ],

        'cms'       => [
            'pages' => [
                'about-us'         => [
                    'content' => 'हमारे बारे में पृष्ठ सामग्री',
                    'title'   => 'हमारे बारे में',
                ],

                'contact-us'       => [
                    'content' => 'हमसे संपर्क करें पृष्ठ सामग्री',
                    'title'   => 'हमसे संपर्क करें',
                ],

                'customer-service' => [
                    'content' => 'ग्राहक सेवा पृष्ठ सामग्री',
                    'title'   => 'ग्राहक सेवा',
                ],

                'payment-policy'   => [
                    'content' => 'भुगतान नीति पृष्ठ सामग्री',
                    'title'   => 'भुगतान नीति',
                ],

                'privacy-policy'   => [
                    'content' => 'गोपनीयता नीति पृष्ठ सामग्री',
                    'title'   => 'गोपनीयता नीति',
                ],

                'refund-policy'    => [
                    'content' => 'वापसी नीति पृष्ठ सामग्री',
                    'title'   => 'वापसी नीति',
                ],

                'return-policy'    => [
                    'content' => 'वापसी नीति पृष्ठ सामग्री',
                    'title'   => 'वापसी नीति',
                ],

                'shipping-policy'  => [
                    'content' => 'शिपिंग नीति पृष्ठ सामग्री',
                    'title'   => 'शिपिंग नीति',
                ],

                'terms-conditions' => [
                    'content' => 'नियम और शर्तों पृष्ठ सामग्री',
                    'title'   => 'नियम और शर्तें',
                ],

                'terms-of-use'     => [
                    'content' => 'उपयोग की शर्तें पृष्ठ सामग्री',
                    'title'   => 'उपयोग की शर्तें',
                ],

                'whats-new'        => [
                    'content' => 'नई चीजें पृष्ठ सामग्री',
                    'title'   => 'नई चीजें',
                ],
            ],
        ],

        'core'      => [
            'channels'   => [
                'meta-description' => 'डेमो स्टोर मेटा विवरण',
                'meta-keywords'    => 'डेमो स्टोर मेटा कीवर्ड',
                'meta-title'       => 'डेमो स्टोर',
                'name'             => 'डिफ़ॉल्ट',
            ],

            'currencies' => [
                'AED' => 'दिर्हम',
                'AFN' => 'इज़राइली शेकेल',
                'CNY' => 'चीनी युआन',
                'EUR' => 'यूरो',
                'GBP' => 'पौंड स्टर्लिंग',
                'INR' => 'भारतीय रुपया',
                'IRR' => 'ईरानी रियाल',
                'JPY' => 'जापानी येन',
                'RUB' => 'रूसी रूबल',
                'SAR' => 'सउदी रियाल',
                'TRY' => 'तुर्की लीरा',
                'UAH' => 'यूक्रेनियन ह्रिव्निया',
                'USD' => 'यूएस डॉलर',
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

        'customer'  => [
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

        'shop'      => [
            'theme-customizations' => [
                'all-products'           => [
                    'name'    => 'सभी उत्पाद',

                    'options' => [
                        'title' => 'सभी उत्पाद',
                    ],
                ],

                'bold-collections'       => [
                    'content' => [
                        'btn-title'   => 'सभी देखें',
                        'description' => 'हमारी नई बोल्ड संग्रह का परिचय! साहसी डिज़ाइन और जीवंत कथनों के साथ अपनी शैली को उन्नत करें. हरित पैटर्न और बोल्ड रंगों की खोज करें जो आपके वस्त्र को पुनर्निर्भर कर देते हैं. असाधारण को ग्रहण करने के लिए तैयार हो जाइए!',
                        'title'       => 'हमारे नए बोल्ड संग्रह के लिए तैयार हो जाइए!',
                    ],

                    'name'    => 'बोल्ड संग्रह',
                ],

                'categories-collections' => [
                    'name' => 'श्रेणियाँ संग्रह',
                ],

                'featured-collections'   => [
                    'name'    => 'विशेष संग्रह',

                    'options' => [
                        'title' => 'विशेष उत्पाद',
                    ],
                ],

                'footer-links'           => [
                    'name'    => 'फ़ूटर लिंक्स',

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

                'game-container'         => [
                    'content' => [
                        'sub-title-1' => 'हमारी संग्रह',
                        'sub-title-2' => 'हमारी संग्रह',
                        'title'       => 'हमारे नए योगदान के साथ खेल!',
                    ],

                    'name'    => 'खेल संदूक',
                ],

                'image-carousel'         => [
                    'name'    => 'चित्र स्लाइडर',

                    'sliders' => [
                        'title' => 'नई संग्रह के लिए तैयार रहें',
                    ],
                ],

                'new-products'           => [
                    'name'    => 'नई उत्पाद',

                    'options' => [
                        'title' => 'नई उत्पाद',
                    ],
                ],

                'offer-information'      => [
                    'content' => [
                        'title' => 'अपने पहले आर्डर पर 40% तक की छूट पाएं, अब खरीदें',
                    ],

                    'name'    => 'ऑफ़र जानकारी',
                ],

                'services-content'       => [
                    'description' => [
                        'emi-available-info'   => 'सभी प्रमुख क्रेडिट कार्डों पर बिना लागत EMI उपलब्ध',
                        'free-shipping-info'   => 'सभी आदेशों पर मुफ्त शिपिंग का आनंद लें',
                        'product-replace-info' => 'आसान उत्पाद बदलाव उपलब्ध!',
                        'time-support-info'    => 'चैट और ईमेल के माध्यम से समर्पित 24/7 समर्थन',
                    ],

                    'name'        => 'सेवाओं की सामग्री',

                    'title'       => [
                        'emi-available'   => 'EMI उपलब्ध',
                        'free-shipping'   => 'मुफ्त शिपिंग',
                        'product-replace' => 'उत्पाद बदलें',
                        'time-support'    => '24/7 समर्थन',
                    ],
                ],

                'top-collections'        => [
                    'content' => [
                        'sub-title-1' => 'हमारी संग्रह',
                        'sub-title-2' => 'हमारी संग्रह',
                        'sub-title-3' => 'हमारी संग्रह',
                        'sub-title-4' => 'हमारी संग्रह',
                        'sub-title-5' => 'हमारी संग्रह',
                        'sub-title-6' => 'हमारी संग्रह',
                        'title'       => 'हमारे नए योगदान के साथ खेल!',
                    ],

                    'name'    => 'शीर्ष संग्रह',
                ],
            ],
        ],

        'user'      => [
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
            'create-administrator'      => [
                'admin'            => 'व्यवस्थापक',
                'bagisto'          => 'बैगिस्टो',
                'confirm-password' => 'पासवर्ड की पुष्टि करें',
                'email-address'    => 'admin@example.com',
                'email'            => 'ईमेल',
                'password'         => 'पासवर्ड',
                'title'            => 'प्रबंधक बनाएं',
            ],

            'environment-configuration' => [
                'allowed-currencies'  => 'अनुमत मुद्राएँ',
                'allowed-locales'     => 'अनुमत भाषाएँ',
                'application-name'    => 'एप्लिकेशन का नाम',
                'bagisto'             => 'Bagisto',
                'chinese-yuan'        => 'चीनी युआन (CNY)',
                'database-connection' => 'डेटाबेस कनेक्शन',
                'database-hostname'   => 'डेटाबेस होस्टनाम',
                'database-name'       => 'डेटाबेस नाम',
                'database-password'   => 'डेटाबेस पासवर्ड',
                'database-port'       => 'डेटाबेस पोर्ट',
                'database-prefix'     => 'डेटाबेस प्रीफ़िक्स',
                'database-username'   => 'डेटाबेस उपयोगकर्ता नाम',
                'default-currency'    => 'डिफ़ॉल्ट मुद्रा',
                'default-locale'      => 'डिफ़ॉल्ट स्थान',
                'default-timezone'    => 'डिफ़ॉल्ट समय क्षेत्र',
                'default-url-link'    => 'https://localhost',
                'default-url'         => 'डिफ़ॉल्ट URL',
                'dirham'              => 'दिर्हम (AED)',
                'euro'                => 'यूरो (EUR)',
                'iranian'             => 'ईरानी रियाल (IRR)',
                'israeli'             => 'इस्राइली शेकेल (AFN)',
                'japanese-yen'        => 'जापानी येन (JPY)',
                'mysql'               => 'Mysql',
                'pgsql'               => 'pgSQL',
                'pound'               => 'पाउंड स्टर्लिंग (GBP)',
                'rupee'               => 'भारतीय रुपया (INR)',
                'russian-ruble'       => 'रूसी रूबल (RUB)',
                'saudi'               => 'सउदी रियाल (SAR)',
                'select-timezone'     => 'समयक्षेत्र चुनें',
                'sqlsrv'              => 'SQLSRV',
                'title'               => 'परिवेश विन्यास',
                'turkish-lira'        => 'तुर्की लीरा (TRY)',
                'ukrainian-hryvnia'   => 'यूक्रेनियाई ह्रीवनिया (UAH)',
                'usd'                 => 'US डॉलर (USD)',
                'warning-message'     => 'सावधान! आपकी डिफ़ॉल्ट सिस्टम भाषाओं और डिफ़ॉल्ट मुद्रा की सेटिंग्स स्थायी हैं और कभी भी फिर से बदली नहीं जा सकतीं।',
            ],

            'installation-processing'   => [
                'bagisto-info'     => 'डेटाबेस तालिकाएँ बनाने का प्रक्रियाण, इसमें कुछ क्षण लग सकते हैं',
                'bagisto'          => 'बैगिस्टो स्थापना',
                'title'            => 'स्थापना',
            ],

            'installation-completed'    => [
                'admin-panel'                => 'व्यवस्थापक पैनल',
                'bagisto-forums'             => 'Bagisto फ़ोरम',
                'customer-panel'             => 'ग्राहक पैनल',
                'explore-bagisto-extensions' => 'Bagisto एक्सटेंशन अन्वेषण करें',
                'title-info'                 => 'बैगिस्टो को आपके सिस्टम पर सफलतापूर्वक स्थापित किया गया है।',
                'title'                      => 'स्थापना पूर्ण',
            ],

            'ready-for-installation'    => [
                'create-database-table'   => 'डेटाबेस तालिका बनाएं',
                'install-info-button'     => 'नीचे दिए गए बटन पर क्लिक करें',
                'install-info'            => 'स्थापना के लिए Bagisto',
                'install'                 => 'स्थापना',
                'populate-database-table' => 'डेटाबेस तालिकाओं को पॉप्युलेट करें',
                'start-installation'      => 'स्थापना शुरू करें',
                'title'                   => 'स्थापना के लिए तैयार',
            ],

            'start'                     => [
                'locale'        => 'स्थान',
                'main'          => 'शुरू',
                'select-locale' => 'स्थान चुनें',
                'title'         => 'आपका Bagisto स्थापित करें',
                'welcome-title' => 'Bagisto 2.0 में आपका स्वागत है।',
            ],

            'server-requirements'       => [
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
                'php-version' => '8.1 या उच्च',
                'php'         => 'पीएचपी',
                'session'     => 'सत्र',
                'title'       => 'सर्वर आवश्यकताएँ',
                'tokenizer'   => 'टोकनाइज़र',
                'xml'         => 'एक्सएमएल',
            ],

            'arabic'                    => 'अरबी',
            'back'                      => 'वापस',
            'bagisto-info'              => 'एक सामुदायिक परियोजना द्वारा',
            'bagisto-logo'              => 'बैगिस्टो लोगो',
            'bagisto'                   => 'बैगिस्टो',
            'bengali'                   => 'बंगाली',
            'chinese'                   => 'चीनी',
            'continue'                  => 'जारी रखें',
            'dutch'                     => 'डच',
            'english'                   => 'अंग्रेज़ी',
            'french'                    => 'फ्रेंच',
            'german'                    => 'जर्मन',
            'hebrew'                    => 'हिब्रू',
            'hindi'                     => 'हिंदी',
            'installation-description'  => 'बैगिस्टो स्थापना आमतौर पर कई कदमों में होती है। यहां बैगिस्टो के लिए स्थापना प्रक्रिया की सामान्य रूपरेखा है:',
            'installation-info'         => 'हमें यहाँ आपको खुश देखकर अच्छा लग रहा है!',
            'installation-title'        => 'स्थापना में आपका स्वागत है',
            'italian'                   => 'इतालवी',
            'japanese'                  => 'जापानी',
            'persian'                   => 'फारसी',
            'polish'                    => 'पोलिश',
            'portuguese'                => 'ब्राजीलियाई पुर्तगाली',
            'russian'                   => 'रूसी',
            'save-configuration'        => 'कॉन्फ़िगरेशन सहेजें',
            'sinhala'                   => 'सिंहला',
            'skip'                      => 'छोड़ें',
            'spanish'                   => 'स्पेनिश',
            'title'                     => 'बैगिस्टो स्थापक',
            'turkish'                   => 'तुर्की',
            'ukrainian'                 => 'यूक्रेनी',
            'webkul'                    => 'वेबकुल',
        ],
    ],
];
