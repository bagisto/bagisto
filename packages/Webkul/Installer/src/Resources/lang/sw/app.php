<?php

return [
    'seeders' => [
        'attribute' => [
            'attribute-families' => [
                'default' => 'Chaguomsingi',
            ],

            'attribute-groups' => [
                'description' => 'Maelezo',
                'general' => 'Kuu',
                'inventories' => 'Inventori',
                'meta-description' => 'Maelezo ya Meta',
                'price' => 'Bei',
                'rma' => 'RMA',
                'settings' => 'Mipangilio',
                'shipping' => 'Usafirishaji',
            ],

            'attributes' => [
                'allow-rma' => 'Ruhusu RMA',
                'brand' => 'Chapa',
                'color' => 'Rangi',
                'cost' => 'Gharama',
                'description' => 'Maelezo',
                'featured' => 'Zilizoangaziwa',
                'guest-checkout' => 'Ununuzi wa Mgeni',
                'height' => 'Kimo',
                'length' => 'Urefu',
                'manage-stock' => 'Dhibiti Stoo',
                'meta-description' => 'Maelezo ya Meta',
                'meta-keywords' => 'Maneno Muhimu ya Meta',
                'meta-title' => 'Kichwa cha Meta',
                'name' => 'Jina',
                'new' => 'Mpya',
                'price' => 'Bei',
                'product-number' => 'Nambari ya Bidhaa',
                'rma-rules' => 'Sheria za RMA',
                'short-description' => 'Maelezo Mafupi',
                'size' => 'Saizi',
                'sku' => 'SKU',
                'special-price' => 'Bei ya Kipekee',
                'special-price-from' => 'Bei ya Kipekee Kutoka',
                'special-price-to' => 'Bei ya Kipekee Hadi',
                'status' => 'Hali',
                'tax-category' => 'Kundi la Ushuru',
                'url-key' => 'Ufunguo wa URL',
                'visible-individually' => 'Inaonekana Peke Yake',
                'weight' => 'Uzito',
                'width' => 'Upana',
            ],

            'attribute-options' => [
                'black' => 'Nyeusi',
                'green' => 'Kijani',
                'l' => 'L',
                'm' => 'M',
                'red' => 'Nyekundu',
                's' => 'S',
                'white' => 'Nyeupe',
                'xl' => 'XL',
                'yellow' => 'Njano',
            ],
        ],

        'category' => [
            'categories' => [
                'description' => 'Maelezo ya Kundi la Mizizi',
                'name' => 'Mzizi',
            ],
        ],

        'cms' => [
            'pages' => [
                'about-us' => [
                    'content' => 'Maudhui ya Ukurasa wa Kuhusu Sisi',
                    'title' => 'Kuhusu Sisi',
                ],

                'contact-us' => [
                    'content' => 'Maudhui ya Ukurasa wa Wasiliana Nasi',
                    'title' => 'Wasiliana Nasi',
                ],

                'customer-service' => [
                    'content' => 'Maudhui ya Ukurasa wa Huduma kwa Wateja',
                    'title' => 'Huduma kwa Wateja',
                ],

                'payment-policy' => [
                    'content' => 'Maudhui ya Ukurasa wa Sera ya Malipo',
                    'title' => 'Sera ya Malipo',
                ],

                'privacy-policy' => [
                    'content' => 'Maudhui ya Ukurasa wa Sera ya Faragha',
                    'title' => 'Sera ya Faragha',
                ],

                'refund-policy' => [
                    'content' => 'Maudhui ya Ukurasa wa Sera ya Kurudisha Fedha',
                    'title' => 'Sera ya Kurudisha Fedha',
                ],

                'return-policy' => [
                    'content' => 'Maudhui ya Ukurasa wa Sera ya Kurejesha Bidhaa',
                    'title' => 'Sera ya Kurejesha Bidhaa',
                ],

                'shipping-policy' => [
                    'content' => 'Maudhui ya Ukurasa wa Sera ya Usafirishaji',
                    'title' => 'Sera ya Usafirishaji',
                ],

                'terms-conditions' => [
                    'content' => 'Maudhui ya Ukurasa wa Masharti na Vigezo',
                    'title' => 'Masharti na Vigezo',
                ],

                'terms-of-use' => [
                    'content' => 'Maudhui ya Ukurasa wa Masharti ya Matumizi',
                    'title' => 'Masharti ya Matumizi',
                ],

                'whats-new' => [
                    'content' => 'Maudhui ya ukurasa wa Yaliyo Mpya',
                    'title' => 'Yaliyo Mpya',
                ],
            ],
        ],

        'core' => [
            'channels' => [
                'meta-description' => 'Maelezo ya meta ya duka la mfano',
                'meta-keywords' => 'Maneno muhimu ya meta ya duka la mfano',
                'meta-title' => 'Duka la Mfano',
                'name' => 'Chaguomsingi',
            ],

            'currencies' => [
                'AED' => 'Dirham ya Falme za Kiarabu',
                'ARS' => 'Peso ya Ajentina',
                'AUD' => 'Dola ya Australia',
                'BDT' => 'Taka ya Bangladesh',
                'BHD' => 'Dinari ya Bahareni',
                'BRL' => 'Real ya Brazil',
                'CAD' => 'Dola ya Kanada',
                'CHF' => 'Faranga ya Uswisi',
                'CLP' => 'Peso ya Chile',
                'CNY' => 'Yuan ya China',
                'COP' => 'Peso ya Kolombia',
                'CZK' => 'Koruna ya Cheki',
                'DKK' => 'Krone ya Denmark',
                'DZD' => 'Dinari ya Aljeria',
                'EGP' => 'Pauni ya Misri',
                'EUR' => 'Euro',
                'FJD' => 'Dola ya Fiji',
                'GBP' => 'Pauni ya Uingereza',
                'HKD' => 'Dola ya Hong Kong',
                'HUF' => 'Forinti ya Hungaria',
                'IDR' => 'Rupiah ya Indonesia',
                'ILS' => 'Shekeli Mpya ya Israeli',
                'INR' => 'Rupia ya India',
                'JOD' => 'Dinari ya Yordani',
                'JPY' => 'Yeni ya Japani',
                'KRW' => 'Won ya Korea Kusini',
                'KWD' => 'Dinari ya Kuwait',
                'KZT' => 'Tenge ya Kazakhstan',
                'LBP' => 'Pauni ya Lebanoni',
                'LKR' => 'Rupia ya Sri Lanka',
                'LYD' => 'Dinari ya Libya',
                'MAD' => 'Dirham ya Moroko',
                'MUR' => 'Rupia ya Mauritius',
                'MXN' => 'Peso ya Meksiko',
                'MYR' => 'Ringgit ya Malaysia',
                'NGN' => 'Naira ya Nigeria',
                'NOK' => 'Krone ya Norwei',
                'NPR' => 'Rupia ya Nepal',
                'NZD' => 'Dola ya Nyuzilandi',
                'OMR' => 'Riali ya Omani',
                'PAB' => 'Balboa ya Panama',
                'PEN' => 'Nuevo Sol ya Peru',
                'PHP' => 'Peso ya Ufilipino',
                'PKR' => 'Rupia ya Pakistan',
                'PLN' => 'Zloti ya Polandi',
                'PYG' => 'Guarani ya Paragwai',
                'QAR' => 'Riali ya Qatar',
                'RON' => 'Leu ya Romania',
                'RUB' => 'Ruble ya Urusi',
                'SAR' => 'Riyal ya Saudi',
                'SEK' => 'Krona ya Uswidi',
                'SGD' => 'Dola ya Singapore',
                'THB' => 'Baht ya Tailandi',
                'TND' => 'Dinari ya Tunisia',
                'TRY' => 'Lira ya Uturuki',
                'TWD' => 'Dola Mpya ya Taiwan',
                'UAH' => 'Hryvnia ya Ukraini',
                'USD' => 'Dola ya Marekani',
                'UZS' => 'Som ya Uzbekistan',
                'VES' => 'Bolivar ya Venezuela',
                'VND' => 'Dong ya Vietnam',
                'XAF' => 'Faranga ya CFA BEAC',
                'XOF' => 'Faranga ya CFA BCEAO',
                'ZAR' => 'Randi ya Afrika Kusini',
                'ZMW' => 'Kwacha ya Zambia',
            ],

            'locales' => [
                'ar' => 'Kiarabu',
                'bn' => 'Kibengali',
                'ca' => 'Kikatalani',
                'de' => 'Kijerumani',
                'en' => 'Kiingereza',
                'es' => 'Kihispania',
                'fa' => 'Kiajemi',
                'fr' => 'Kifaransa',
                'he' => 'Kiebrania',
                'hi_IN' => 'Kihindi',
                'id' => 'Kiindonesia',
                'it' => 'Kiitaliano',
                'ja' => 'Kijapani',
                'nl' => 'Kiholanzi',
                'pl' => 'Kipolandi',
                'pt_BR' => 'Kireno cha Brazil',
                'ro' => 'Kiromania',
                'ru' => 'Kirusi',
                'sin' => 'Kisinhala',
                'sw' => 'Kiswahili',
                'tr' => 'Kituruki',
                'uk' => 'Kiukraini',
                'zh_CN' => 'Kichina',
            ],
        ],

        'customer' => [
            'customer-groups' => [
                'general' => 'Kuu',
                'guest' => 'Mgeni',
                'wholesale' => 'Lelemama',
            ],
        ],

        'inventory' => [
            'inventory-sources' => [
                'name' => 'Chaguomsingi',
            ],
        ],

        'shop' => [
            'theme-customizations' => [
                'bold-collections' => [
                    'content' => [
                        'btn-title' => 'Tazama Mikusanyiko',
                        'description' => 'Karibu na Mikusanyiko yetu mipya ya Jasiri! Pandisha mtindo wako kwa michoro ya kujitosheleza na tamko lenye rangi angavu. Chunguza mifumo ya kuvutia na rangi za jasiri zinazobadilisha mtindo wako. Jiandae kukumbatia ya kipekee!',
                        'title' => 'Jiandae na Mikusanyiko yetu mipya ya Jasiri!',
                    ],

                    'name' => 'Mikusanyiko ya Jasiri',
                ],

                'bold-collections-2' => [
                    'content' => [
                        'btn-title' => 'Tazama Mikusanyiko',
                        'description' => 'Mikusanyiko yetu ya Jasiri imefika kubadilisha mtindo wako kwa michoro ya kujiamini na rangi angavu za kupendeza. Kutoka kwa mifumo ya kujitosheleza hadi vivuli yenye nguvu, hii ni fursa yako ya kutoka kwenye ya kawaida na kwenda kwenye ya kipekee.',
                        'title' => 'Onyesha Ujasiri wako na Mkusanyiko wetu mpya!',
                    ],

                    'name' => 'Mikusanyiko ya Jasiri',
                ],

                'book-tickets' => [
                    'name' => 'Weka Tiketi',

                    'options' => [
                        'title' => 'Weka Tiketi',
                    ],
                ],

                'categories-collections' => [
                    'name' => 'Mikusanyiko ya Makundi',
                ],

                'footer-links' => [
                    'name' => 'Viungo vya Chini',

                    'options' => [
                        'about-us' => 'Kuhusu Sisi',
                        'contact-us' => 'Wasiliana Nasi',
                        'customer-service' => 'Huduma kwa Wateja',
                        'payment-policy' => 'Sera ya Malipo',
                        'privacy-policy' => 'Sera ya Faragha',
                        'refund-policy' => 'Sera ya Kurudisha Fedha',
                        'return-policy' => 'Sera ya Kurejesha Bidhaa',
                        'shipping-policy' => 'Sera ya Usafirishaji',
                        'terms-conditions' => 'Masharti na Vigezo',
                        'terms-of-use' => 'Masharti ya Matumizi',
                        'whats-new' => 'Yaliyo Mpya',
                    ],
                ],

                'game-container' => [
                    'content' => [
                        'sub-title-1' => 'Mikusanyiko Yetu',
                        'sub-title-2' => 'Mikusanyiko Yetu',
                        'title' => 'Mchezo wenye nyongeza zetu mpya!',
                    ],

                    'name' => 'Konteina la Mchezo',
                ],

                'image-carousel' => [
                    'name' => 'Karuseli ya Picha',

                    'sliders' => [
                        'title' => 'Jiandae kwa Mkusanyiko Mpya',
                    ],
                ],

                'kids-collection' => [
                    'name' => 'Mkusanyiko wa Watoto',

                    'options' => [
                        'title' => 'Mkusanyiko wa Watoto',
                    ],
                ],

                'mens-collection' => [
                    'name' => 'Mkusanyiko wa Wanaume',

                    'options' => [
                        'title' => 'Mkusanyiko wa Wanaume',
                    ],
                ],

                'offer-information' => [
                    'content' => [
                        'title' => 'PATA hadi 40% ODA kwenye oda yako ya kwanza NUNUA SASA',
                    ],

                    'name' => 'Taarifa ya Ofa',
                ],

                'services-content' => [
                    'description' => [
                        'emi-available-info' => 'EMI bila gharama inapatikana kwenye kadi zote kuu za mkopo',
                        'free-shipping-info' => 'Furahia usafirishaji bure kwenye oda zote',
                        'product-replace-info' => 'Ubadilishaji wa Bidhaa kwa Urahisi Unapatikana!',
                        'time-support-info' => 'Msaada maalum wa saa 24/siku 7 kupitia mazungumzo na barua pepe',
                    ],

                    'name' => 'Maudhui ya Huduma',

                    'title' => [
                        'emi-available' => 'EMI Inapatikana',
                        'free-shipping' => 'Usafirishaji Bure',
                        'product-replace' => 'Ubadilishaji wa Bidhaa',
                        'time-support' => 'Msaada wa 24/7',
                    ],
                ],

                'top-collections' => [
                    'content' => [
                        'sub-title-1' => 'Mikusanyiko Yetu',
                        'sub-title-2' => 'Mikusanyiko Yetu',
                        'sub-title-3' => 'Mikusanyiko Yetu',
                        'sub-title-4' => 'Mikusanyiko Yetu',
                        'sub-title-5' => 'Mikusanyiko Yetu',
                        'sub-title-6' => 'Mkusanyiko Yetu',
                        'title' => 'Mchezo wenye nyongeza zetu mpya!',
                    ],

                    'name' => 'Mikusanyiko Bora',
                ],

                'womens-collection' => [
                    'name' => 'Mkusanyiko wa Wanawake',

                    'options' => [
                        'title' => 'Mkusanyiko wa Wanawake',
                    ],
                ],
            ],
        ],

        'user' => [
            'roles' => [
                'description' => 'Watumiaji wenye jukumu hili watakuwa na ufikiaji wote',
                'name' => 'Msimamizi',
            ],

            'users' => [
                'name' => 'Mfano',
            ],
        ],

        'sample-categories' => [
            'category-translation' => [
                '2' => [
                    'description' => '<p>Wanaume</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Wanaume',
                    'slug' => 'mens',
                    'url-path' => 'men',
                ],

                '3' => [
                    'description' => '<p>Watoto</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Watoto',
                    'slug' => 'kids',
                    'url-path' => 'kids',
                ],

                '4' => [
                    'description' => '<p>Wanawake</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Wanawake',
                    'slug' => 'womens',
                    'url-path' => 'woman',
                ],

                '5' => [
                    'description' => '<p>Mavazi ya Rasmi</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Mavazi ya Rasmi',
                    'slug' => 'formal-wear-men',
                    'url-path' => 'men/formal-wear-men',
                ],

                '6' => [
                    'description' => '<p>Mavazi ya Kawaida</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Mavazi ya Kawaida',
                    'slug' => 'casual-wear-men',
                    'url-path' => 'men/casual-wear-men',
                ],

                '7' => [
                    'description' => '<p>Mavazi ya Mazoezi</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Mavazi ya Mazoezi',
                    'slug' => 'active-wear',
                    'url-path' => 'men/active-wear',
                ],

                '8' => [
                    'description' => '<p>Viatu</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Viatu',
                    'slug' => 'footwear',
                    'url-path' => 'men/footwear',
                ],

                '9' => [
                    'description' => '<p><span>Mavazi ya Rasmi</span></p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Mavazi ya Rasmi',
                    'slug' => 'formal-wear-female',
                    'url-path' => 'woman/formal-wear-female',
                ],

                '10' => [
                    'description' => '<p>Mavazi ya Kawaida</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Mavazi ya Kawaida',
                    'slug' => 'casual-wear-female',
                    'url-path' => 'woman/casual-wear-female',
                ],

                '11' => [
                    'description' => '<p>Mazoezi</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Mavazi ya Mazoezi',
                    'slug' => 'active-wear-female',
                    'url-path' => 'woman/active-wear-female',
                ],

                '12' => [
                    'description' => '<p>Viatu</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Viatu',
                    'slug' => 'footwear-female',
                    'url-path' => 'woman/footwear-female',
                ],

                '13' => [
                    'description' => '<p>Nguo za Wasichana</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => 'Nguo za Wasichana',
                    'name' => 'Nguo za Wasichana',
                    'slug' => 'girls-clothing',
                    'url-path' => 'kids/girls-clothing',
                ],

                '14' => [
                    'description' => '<p>Nguo za Wavulana</p>',
                    'meta-description' => 'Mitindo ya Wavulana',
                    'meta-keywords' => '',
                    'meta-title' => 'Nguo za Wavulana',
                    'name' => 'Nguo za Wavulana',
                    'slug' => 'boys-clothing',
                    'url-path' => 'kids/boys-clothing',
                ],

                '15' => [
                    'description' => '<p>Viatu vya Wasichana</p>',
                    'meta-description' => 'Mkusanyiko wa Viatu vya Mitindo kwa Wasichana',
                    'meta-keywords' => '',
                    'meta-title' => 'Viatu vya Wasichana',
                    'name' => 'Viatu vya Wasichana',
                    'slug' => 'girls-footwear',
                    'url-path' => 'kids/girls-footwear',
                ],

                '16' => [
                    'description' => '<p>Viatu vya Wavulana</p>',
                    'meta-description' => 'Mkusanyiko wa Viatu vya Mitindo kwa Wavulana',
                    'meta-keywords' => '',
                    'meta-title' => 'Viatu vya Wavulana',
                    'name' => 'Viatu vya Wavulana',
                    'slug' => 'boys-footwear',
                    'url-path' => 'kids/boys-footwear',
                ],

                '17' => [
                    'description' => '<p>Utunzaji wa Afya</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Afya',
                    'slug' => 'wellness',
                    'url-path' => 'wellness',
                ],

                '18' => [
                    'description' => '<p>Kozi ya Yoga Inayopakuliwa</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Kozi ya Yoga Inayopakuliwa',
                    'slug' => 'downloadable-yoga-tutorial',
                    'url-path' => 'wellness/downloadable-yoga-tutorial',
                ],

                '19' => [
                    'description' => '<p>Mkusanyiko wa Vitabu</p>',
                    'meta-description' => 'Mkusanyiko wa Vitabu',
                    'meta-keywords' => '',
                    'meta-title' => 'Mkusanyiko wa Vitabu',
                    'name' => 'Vitabu vya Kielektroniki',
                    'slug' => 'e-books',
                    'url-path' => 'wellness/e-books',
                ],

                '20' => [
                    'description' => '<p>Pass ya Sinema</p>',
                    'meta-description' => 'Furahia uchawi wa sinema 10 kila mwezi bila malipo ya ziada. Pass hii ni halali kote nchini bila vikomo vya tarehe, inatoa faida za kipekee na punguzo za ukumbi, na kuifanya kuwa lazima kwa wapenzi wa sinema.',
                    'meta-keywords' => '',
                    'meta-title' => 'Pass ya Sinema ya Kila Mwezi ya CineXperience',
                    'name' => 'Pass ya Sinema',
                    'slug' => 'movie-pass',
                    'url-path' => 'wellness/movie-pass',
                ],

                '21' => [
                    'description' => '<p>Dhibiti na uuze kwa urahisi bidhaa zako zinazotegemea uwekaji nafasi kwa kutumia mfumo wetu uliobarabika wa kuweka nafasi. Iwe unatoa miadi, makodisho, matukio, au nafasi za kuweka, suluhisho letu linahakikisha uzoefu mzuri kwa biashara na wateja pia. Kwa upatikanaji wa papo hapo, ratiba inayobadilika, na arifa za kiotomatiki, unaweza kurahisisha mchakato wako wa kuweka nafasi bila jitihada kubwa. Boresha urahisi kwa wateja na ongeza mauzo yako kwa suluhisho letu yenye nguvu la bidhaa za uwekaji nafasi!</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Uwekaji Nafasi',
                    'slug' => 'bookings',
                    'url-path' => '',
                ],

                '22' => [
                    'description' => '<p>Uwekaji wa miadi huwapa wateja nafasi ya kupanga muda wa huduma au ushauri na biashara au wataalamu. Mfumo huu hutumika sana katika sekta kama afya, urembo, elimu, na huduma za kibinafsi. Husaidia kurahisisha kupanga ratiba, kupunguza muda wa kusubiri, na kuboresha kuridhika kwa wateja kwa kutoa nafasi rahisi zenye muda maalum.</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Uwekaji wa Miadi',
                    'slug' => 'appointment-booking',
                    'url-path' => '',
                ],

                '23' => [
                    'description' => '<p>Uwekaji wa matukio huruhusu watu binafsi au makundi kujiandikisha au kutenga nafasi katika matukio ya umma au binafsi kama tamasha, warsha, mikutano, au sherehe. Huwa pamoja na chaguo za kuchagua tarehe, aina za kukaa, na makundi ya tiketi, huku ukawapa waandaaji usimamizi bora wa wahudhuriaji na kuhakikisha mchakato wa kuingia unapita kwa laini.</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Uwekaji wa Matukio',
                    'slug' => 'event-booking',
                    'url-path' => '',
                ],

                '24' => [
                    'description' => '<p>Uwekaji wa ukumbi wa jamii huwawezesha watu binafsi, mashirika, au makundi kutenga nafasi za jamii kwa matukio mbalimbali kama harusi, mikutano, programu za kitamaduni, au makusanyo ya kijamii. Mfumo huu husaidia kusimamia upatikanaji, kupanga uwekaji nafasi, na kushughulikia mambo ya kimazingira kama uwezo, huduma zinazopatikana, na muda wa kukodi. Unahakikisha matumizi bora ya vikumbi vya umma au binafsi huku ukitoa njia rahisi ya watumiaji kupanga matukio yao.</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Uwekaji wa Vikumbi vya Jamii',
                    'slug' => 'community-hall-bookings',
                    'url-path' => '',
                ],

                '25' => [
                    'description' => '<p>Uwekaji wa meza huwawezesha wateja kutenga meza mapema katika migahawa, cafe, au maeneo mengine ya kula. Husaidia kusimamia idadi ya wateja wanaokaa, kupunguza muda wa kusubiri, na kutoa uzoefu bora wa kula. Mfumo huu ni muhimu hasa wakati wa saa za msongamano, matukio maalum, au kukidhi makundi makubwa yenye mahitaji maalum.</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Uwekaji wa Meza',
                    'slug' => 'table-booking',
                    'url-path' => '',
                ],

                '26' => [
                    'description' => '<p>Uwekaji wa makodisho hurahisisha kutenga vitu au mali ya matumizi ya muda mfupi, kama magari, vifaa, nyumba za likizo, au maeneo ya mikutano. Huwa na vipengele vya kuchagua vipindi vya kukodi, kuangalia upatikanaji, na kusimamia malipo. Mfumo huu unaunga mkono kodisho la muda mfupi na muda mrefu, na kuboresha urahisi kwa watoa huduma na wakodishi.</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Uwekaji wa Makodisho',
                    'slug' => 'rental-booking',
                    'url-path' => '',
                ],

                '27' => [
                    'description' => '<p>Chunguza teknolojia mpya za kielektroniki za matumizi ya kila siku, zilizoundwa kukuweka ukiungana, ukitenda kazi, na ukifurahia. Ukiwa unapandisha vifaa vyako au unatafuta suluhisho mahiri, tuna kila kitu unachohitaji.</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Elektroniki',
                    'slug' => 'electronics',
                    'url-path' => '',
                ],

                '28' => [
                    'description' => '<p>Gundua simu janja, chaja, mifuko ya kulinda, na mahitaji mengine muhimu ya kukuunganisha ukiwa safarini.</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Simu za Mkononi & Vifaa vya Ziada',
                    'slug' => 'mobile-phones-accessories',
                    'url-path' => '',
                ],

                '29' => [
                    'description' => '<p>Pata laptopu zenye nguvu na kompyuta kibao za kubebeka kwa kazi, masomo, na burudani.</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Laptopu & Kompyuta Kibao',
                    'slug' => 'laptops-tablets',
                    'url-path' => '',
                ],

                '30' => [
                    'description' => '<p>Nunua vipaza masikio, earbud, na vipaza sauti ili kufurahia sauti safi kabisa na uzoefu wa kusikia wa kina.</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Vifaa vya Sauti',
                    'slug' => 'audio-devices',
                    'url-path' => '',
                ],

                '31' => [
                    'description' => '<p>Rahisisha maisha yako kwa taa janja, vifaa vya udhibiti wa joto, mifumo ya usalama, na mengineyo.</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Nyumba Janji & Uendeshaji wa Kiotomatiki',
                    'slug' => 'smart-home-automation',
                    'url-path' => '',
                ],

                '32' => [
                    'description' => '<p>Boresha nafasi yako ya kuishi kwa mahitaji ya nyumba na jikoni yenye manufaa na mtindo. Kutoka kupika hadi kusafisha, pata bidhaa zinazoongeza faraja na ufanisi.</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Vitu vya Nyumbani',
                    'slug' => 'household',
                    'url-path' => '',
                ],

                '33' => [
                    'description' => '<p>Tazama blenda, air fryer, vifaa vya kutengeneza kahawa, na mengineyo ili kurahisisha maandalizi ya vyakula.</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Vifaa vya Jikoni',
                    'slug' => 'kitchen-appliances',
                    'url-path' => '',
                ],

                '34' => [
                    'description' => '<p>Chunguza seti za vyungu vya kupikia, vyombo, sahani, na vyombo vya kutumikia kwa mahitaji yako ya mapishi.</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Vyombo vya Kupikia & Kula',
                    'slug' => 'cookware-dining',
                    'url-path' => '',
                ],

                '35' => [
                    'description' => '<p>Ongeza faraja na urembo kwa sofa, meza, sanaa za ukutani, na vipodozi vya nyumbani.</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Samani & Mapambo',
                    'slug' => 'furniture-decor',
                    'url-path' => '',
                ],

                '36' => [
                    'description' => '<p>Iweke nafasi yako safi kabisa kwa vitufe vya kunyonya vumbi, dawa za kunyunyizia usafi, ufagio, na vifaa vya kupanga.</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Vifaa vya Usafi',
                    'slug' => 'cleaning-supplies',
                    'url-path' => '',
                ],

                '37' => [
                    'description' => '<p>Hamasa fikra zako au panga eneo lako la kazi kwa mchanganyiko mkubwa wa vitabu na vifaa vya kuandikia. Ni bora kwa wasomaji, wanafunzi, wataalamu, na wasanii.</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Vitabu & Vifaa vya Kuandikia',
                    'slug' => 'books-stationery',
                    'url-path' => '',
                ],

                '38' => [
                    'description' => '<p>Zama katika riwaya maarufu, wasifu, vitabu vya kujitegemea, na mengineyo.</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Vitabu vya Kubuni na vya Kweli',
                    'slug' => 'fiction-non-fiction-books',
                    'url-path' => '',
                ],

                '39' => [
                    'description' => '<p>Pata vitabu vya kiada, vifaa vya marejeleo, na nyenzo za kusoma kwa umri wote.</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Elimu & Kiada',
                    'slug' => 'educational-academic',
                    'url-path' => '',
                ],

                '40' => [
                    'description' => '<p>Nunua kalamu, daftari, vipanga-ratiba, na mahitaji mengine ya ofisi kwa ufanisi wa kazi.</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Vifaa vya Ofisi',
                    'slug' => 'office-supplies',
                    'url-path' => '',
                ],

                '41' => [
                    'description' => '<p>Chunguza rangi, brashi, daftari michoroni, na seti za sanaa za DIY kwa watu wenye ubunifu.</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Vifaa vya Sanaa & Ufundi',
                    'slug' => 'art-craft-materials',
                    'url-path' => '',
                ],
            ],
        ],
    ],

    'installer' => [
        'middleware' => [
            'already-installed' => 'Programu tayari imesakinishwa.',
        ],

        'index' => [
            'create-administrator' => [
                'admin' => 'Msimamizi',
                'bagisto' => 'Bagisto',
                'confirm-password' => 'Thibitisha Nenosiri',
                'email' => 'Barua Pepe',
                'email-address' => 'admin@example.com',
                'password' => 'Nenosiri',
                'title' => 'Unda Msimamizi',
            ],

            'environment-configuration' => [
                'algerian-dinar' => 'Dinari ya Aljeria (DZD)',
                'allowed-currencies' => 'Sarafu Zinazoruhusiwa',
                'allowed-locales' => 'Lugha Zinazoruhusiwa',
                'application-name' => 'Jina la Programu',
                'argentine-peso' => 'Peso ya Ajentina (ARS)',
                'australian-dollar' => 'Dola ya Australia (AUD)',
                'bagisto' => 'Bagisto',
                'bangladeshi-taka' => 'Taka ya Bangladesh (BDT)',
                'bahraini-dinar' => 'Dinari ya Bahareni (BHD)',
                'brazilian-real' => 'Real ya Brazil (BRL)',
                'british-pound-sterling' => 'Pauni ya Uingereza (GBP)',
                'canadian-dollar' => 'Dola ya Kanada (CAD)',
                'cfa-franc-bceao' => 'Faranga ya CFA BCEAO (XOF)',
                'cfa-franc-beac' => 'Faranga ya CFA BEAC (XAF)',
                'chilean-peso' => 'Peso ya Chile (CLP)',
                'chinese-yuan' => 'Yuan ya China (CNY)',
                'colombian-peso' => 'Peso ya Kolombia (COP)',
                'czech-koruna' => 'Koruna ya Cheki (CZK)',
                'danish-krone' => 'Krone ya Denmark (DKK)',
                'database-connection' => 'Muunganisho wa Hifadhidata',
                'database-hostname' => 'Jina la Seva ya Hifadhidata',
                'database-name' => 'Jina la Hifadhidata',
                'database-password' => 'Nenosiri la Hifadhidata',
                'database-port' => 'Bandari ya Hifadhidata',
                'database-prefix' => 'Kiambishi Awali cha Hifadhidata',
                'database-prefix-help' => 'Kiambishi awali kinapaswa kuwa na herufi 4 na kinaweza kuwa na herufi, nambari, na mistari ya chini tu.',
                'database-username' => 'Jina la Mtumiaji la Hifadhidata',
                'default-currency' => 'Sarafu Chaguomsingi',
                'default-locale' => 'Lugha Chaguomsingi',
                'default-timezone' => 'Ukanda wa Wakati Chaguomsingi',
                'default-url' => 'URL Chaguomsingi',
                'default-url-link' => 'https://localhost',
                'egyptian-pound' => 'Pauni ya Misri (EGP)',
                'euro' => 'Euro (EUR)',
                'fijian-dollar' => 'Dola ya Fiji (FJD)',
                'hong-kong-dollar' => 'Dola ya Hong Kong (HKD)',
                'hungarian-forint' => 'Forinti ya Hungaria (HUF)',
                'indian-rupee' => 'Rupia ya India (INR)',
                'indonesian-rupiah' => 'Rupiah ya Indonesia (IDR)',
                'israeli-new-shekel' => 'Shekeli Mpya ya Israeli (ILS)',
                'japanese-yen' => 'Yeni ya Japani (JPY)',
                'jordanian-dinar' => 'Dinari ya Yordani (JOD)',
                'kazakhstani-tenge' => 'Tenge ya Kazakhstan (KZT)',
                'kuwaiti-dinar' => 'Dinari ya Kuwait (KWD)',
                'lebanese-pound' => 'Pauni ya Lebanoni (LBP)',
                'libyan-dinar' => 'Dinari ya Libya (LYD)',
                'malaysian-ringgit' => 'Ringgit ya Malaysia (MYR)',
                'mauritian-rupee' => 'Rupia ya Mauritius (MUR)',
                'mexican-peso' => 'Peso ya Meksiko (MXN)',
                'moroccan-dirham' => 'Dirham ya Moroko (MAD)',
                'mysql' => 'Mysql',
                'mariadb' => 'MariaDB',
                'nepalese-rupee' => 'Rupia ya Nepal (NPR)',
                'new-taiwan-dollar' => 'Dola Mpya ya Taiwan (TWD)',
                'new-zealand-dollar' => 'Dola ya Nyuzilandi (NZD)',
                'nigerian-naira' => 'Naira ya Nigeria (NGN)',
                'norwegian-krone' => 'Krone ya Norwei (NOK)',
                'omani-rial' => 'Riali ya Omani (OMR)',
                'pakistani-rupee' => 'Rupia ya Pakistan (PKR)',
                'panamanian-balboa' => 'Balboa ya Panama (PAB)',
                'paraguayan-guarani' => 'Guarani ya Paragwai (PYG)',
                'peruvian-nuevo-sol' => 'Nuevo Sol ya Peru (PEN)',
                'pgsql' => 'pgSQL',
                'philippine-peso' => 'Peso ya Ufilipino (PHP)',
                'polish-zloty' => 'Zloti ya Polandi (PLN)',
                'qatari-rial' => 'Riali ya Qatar (QAR)',
                'romanian-leu' => 'Leu ya Romania (RON)',
                'russian-ruble' => 'Ruble ya Urusi (RUB)',
                'saudi-riyal' => 'Riyal ya Saudi (SAR)',
                'select-timezone' => 'Chagua Ukanda wa Wakati',
                'singapore-dollar' => 'Dola ya Singapore (SGD)',
                'south-african-rand' => 'Randi ya Afrika Kusini (ZAR)',
                'south-korean-won' => 'Won ya Korea Kusini (KRW)',
                'sqlsrv' => 'SQLSRV',
                'sri-lankan-rupee' => 'Rupia ya Sri Lanka (LKR)',
                'swedish-krona' => 'Krona ya Uswidi (SEK)',
                'swiss-franc' => 'Faranga ya Uswisi (CHF)',
                'thai-baht' => 'Baht ya Tailandi (THB)',
                'title' => 'Usanidi wa Duka',
                'tunisian-dinar' => 'Dinari ya Tunisia (TND)',
                'turkish-lira' => 'Lira ya Uturuki (TRY)',
                'ukrainian-hryvnia' => 'Hryvnia ya Ukraini (UAH)',
                'united-arab-emirates-dirham' => 'Dirham ya Falme za Kiarabu (AED)',
                'united-states-dollar' => 'Dola ya Marekani (USD)',
                'uzbekistani-som' => 'Som ya Uzbekistan (UZS)',
                'venezuelan-bolívar' => 'Bolivar ya Venezuela (VEF)',
                'vietnamese-dong' => 'Dong ya Vietnam (VND)',
                'warning-message' => 'Angalia! Mipangilio ya lugha chaguomsingi ya mfumo wako na sarafu chaguomsingi ni ya kudumu na haiwezi kubadilishwa mara imewekwa.',
                'zambian-kwacha' => 'Kwacha ya Zambia (ZMW)',
            ],

            'sample-products' => [
                'no' => 'Hapana',
                'note' => 'Angalizo: Muda wa uwekaji faharasa unategemea idadi ya lugha ulizochagua. Mchakato huu unaweza kuchukua dakika 2 hadi ukamilike. Ukiongeza lugha zaidi, jaribu kuongeza muda wa juu wa utekelezaji (max execution time) katika mipangilio ya seva yako na PHP, au unaweza kutumia kisakinishi chetu cha CLI kuepuka muda wa oda kuisha (request timeout).',
                'sample-products' => 'Bidhaa za Mfano',
                'title' => 'Bidhaa za Mfano',
                'yes' => 'Ndiyo',
            ],

            'installation-processing' => [
                'bagisto' => 'Usakinishaji wa Bagisto',
                'bagisto-info' => 'Inatengeneza majedwali ya hifadhidata, hii inaweza kuchukua muda mfupi',
                'title' => 'Usakinishaji',
            ],

            'installation-completed' => [
                'admin-panel' => 'Paneli ya Msimamizi',
                'bagisto-forums' => 'Jukwaa la Bagisto',
                'customer-panel' => 'Paneli ya Wateja',
                'explore-bagisto-extensions' => 'Chunguza Viendelezi vya Bagisto',
                'title' => 'Usakinishaji Umekamilika',
                'title-info' => 'Bagisto imesakinishwa kwa mafanikio kwenye mfumo wako.',
            ],

            'ready-for-installation' => [
                'create-database-tables' => 'Tengeneza majedwali ya hifadhidata',
                'drop-existing-tables' => 'Futa majedwali yoyote yaliyopo',
                'install' => 'Usakinishaji',
                'install-info' => 'Bagisto Kwa Usakinishaji',
                'install-info-button' => 'Bofya kitufe kilicho chini ili',
                'populate-database-tables' => 'Jaza majedwali ya hifadhidata',
                'start-installation' => 'Anza Usakinishaji',
                'title' => 'Tayari kusakinisha',
            ],

            'start' => [
                'language' => 'Lugha ya Kisakinishi',
                'locale' => 'Lugha',
                'main' => 'Anza',
                'select-locale' => 'Chagua Lugha',
                'title' => 'Usakinishaji wako wa Bagisto',
                'welcome-title' => 'Karibu Bagisto',
            ],

            'server-requirements' => [
                'calendar' => 'Kalenda',
                'ctype' => 'cType',
                'curl' => 'cURL',
                'dom' => 'dom',
                'fileinfo' => 'fileInfo',
                'filter' => 'Chuja',
                'gd' => 'GD',
                'hash' => 'Hash',
                'intl' => 'intl',
                'json' => 'JSON',
                'mbstring' => 'mbstring',
                'openssl' => 'openssl',
                'pcre' => 'pcre',
                'pdo' => 'pdo',
                'php' => 'PHP',
                'php-version' => ':version au toleo jipya zaidi',
                'session' => 'session',
                'title' => 'Mahitaji ya Mfumo',
                'tokenizer' => 'tokenizer',
                'xml' => 'XML',
            ],

            'arabic' => 'Kiarabu',
            'back' => 'Rudi',
            'bagisto' => 'Bagisto',
            'bagisto-info' => 'Mradi wa Jamii kutoka',
            'bagisto-logo' => 'Nembo ya Bagisto',
            'bengali' => 'Kibengali',
            'catalan' => 'Kikatalani',
            'chinese' => 'Kichina',
            'continue' => 'Endelea',
            'dutch' => 'Kiholanzi',
            'english' => 'Kiingereza',
            'french' => 'Kifaransa',
            'german' => 'Kijerumani',
            'hebrew' => 'Kiebrania',
            'hindi' => 'Kihindi',
            'indonesian' => 'Kiindonesia',
            'installation-description' => 'Usakinishaji wa Bagisto kwa kawaida huhusisha hatua kadhaa. Hapa kuna muhtasari wa jumla wa mchakato wa usakinishaji wa Bagisto',
            'installation-info' => 'Tunafuraha kukuona hapa!',
            'installation-title' => 'Karibu kwa Usakinishaji',
            'italian' => 'Kiitaliano',
            'japanese' => 'Kijapani',
            'persian' => 'Kiajemi',
            'polish' => 'Kipolandi',
            'portuguese' => 'Kireno cha Brazil',
            'romanian' => 'Kiromania',
            'russian' => 'Kirusi',
            'sinhala' => 'Kisinhala',
            'swahili' => 'Kiswahili',
            'spanish' => 'Kihispania',
            'title' => 'Kisakinishi cha Bagisto',
            'turkish' => 'Kituruki',
            'ukrainian' => 'Kiukraini',
            'webkul' => 'Webkul',
        ],
    ],
];
