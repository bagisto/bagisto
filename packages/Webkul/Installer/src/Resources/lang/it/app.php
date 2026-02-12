<?php

return [
    'seeders' => [
        'attribute' => [
            'attribute-families' => [
                'default' => 'Predefinito',
            ],

            'attribute-groups' => [
                'description' => 'Descrizione',
                'general' => 'Generale',
                'inventories' => 'Inventario',
                'meta-description' => 'Meta Descrizione',
                'price' => 'Prezzo',
                'rma' => 'RMA',
                'settings' => 'Impostazioni',
                'shipping' => 'Spedizione',
            ],

            'attributes' => [
                'allow-rma' => 'Consenti RMA',
                'brand' => 'Marca',
                'color' => 'Colore',
                'cost' => 'Costo',
                'description' => 'Descrizione',
                'featured' => 'In primo piano',
                'guest-checkout' => 'Check-out Ospite',
                'height' => 'Altezza',
                'length' => 'Lunghezza',
                'manage-stock' => 'Gestisci Scorte',
                'meta-description' => 'Meta Descrizione',
                'meta-keywords' => 'Meta Parole chiave',
                'meta-title' => 'Meta Titolo',
                'name' => 'Nome',
                'new' => 'Nuovo',
                'price' => 'Prezzo',
                'product-number' => 'Numero del Prodotto',
                'rma-rules' => 'Regole RMA',
                'short-description' => 'Breve Descrizione',
                'size' => 'Taglia',
                'sku' => 'SKU',
                'special-price' => 'Prezzo Speciale',
                'special-price-from' => 'Prezzo Speciale Da',
                'special-price-to' => 'Prezzo Speciale A',
                'status' => 'Stato',
                'tax-category' => 'Categoria Fiscale',
                'url-key' => 'Chiave URL',
                'visible-individually' => 'Visibile Individualmente',
                'weight' => 'Peso',
                'width' => 'Larghezza',
            ],

            'attribute-options' => [
                'black' => 'Nero',
                'green' => 'Verde',
                'l' => 'L',
                'm' => 'M',
                'red' => 'Rosso',
                's' => 'S',
                'white' => 'Bianco',
                'xl' => 'XL',
                'yellow' => 'Giallo',
            ],
        ],

        'category' => [
            'categories' => [
                'description' => 'Descrizione della Categoria Radice',
                'name' => 'Radice',
            ],
        ],

        'cms' => [
            'pages' => [
                'about-us' => [
                    'content' => 'Contenuto della Pagina Chi siamo',
                    'title' => 'Chi siamo',
                ],

                'contact-us' => [
                    'content' => 'Contenuto della Pagina Contattaci',
                    'title' => 'Contattaci',
                ],

                'customer-service' => [
                    'content' => 'Contenuto della Pagina Assistenza Clienti',
                    'title' => 'Assistenza Clienti',
                ],

                'payment-policy' => [
                    'content' => 'Contenuto della Pagina Politica di Pagamento',
                    'title' => 'Politica di Pagamento',
                ],

                'privacy-policy' => [
                    'content' => 'Contenuto della Pagina Politica sulla Privacy',
                    'title' => 'Politica sulla Privacy',
                ],

                'refund-policy' => [
                    'content' => 'Contenuto della Pagina Politica di Rimborso',
                    'title' => 'Politica di Rimborso',
                ],

                'return-policy' => [
                    'content' => 'Contenuto della Pagina Politica di Reso',
                    'title' => 'Politica di Reso',
                ],

                'shipping-policy' => [
                    'content' => 'Contenuto della Pagina Politica di Spedizione',
                    'title' => 'Politica di Spedizione',
                ],

                'terms-conditions' => [
                    'content' => 'Contenuto della Pagina Termini e Condizioni',
                    'title' => 'Termini e Condizioni',
                ],

                'terms-of-use' => [
                    'content' => 'Contenuto della Pagina Termini d\'Uso',
                    'title' => 'Termini d\'Uso',
                ],

                'whats-new' => [
                    'content' => 'Contenuto della Pagina Novità',
                    'title' => 'Novità',
                ],
            ],
        ],

        'core' => [
            'channels' => [
                'meta-description' => 'Descrizione meta del Negozio Dimostrativo',
                'meta-keywords' => 'Parole chiave meta del Negozio Dimostrativo',
                'meta-title' => 'Negozio Dimostrativo',
                'name' => 'Predefinito',
            ],

            'currencies' => [
                'AED' => 'Dirham degli Emirati Arabi Uniti',
                'ARS' => 'Peso Argentino',
                'AUD' => 'Dollaro Australiano',
                'BDT' => 'Taka Bangladese',
                'BHD' => 'Dinaro del Bahrein',
                'BRL' => 'Real Brasiliano',
                'CAD' => 'Dollaro Canadese',
                'CHF' => 'Franco Svizzero',
                'CLP' => 'Peso Cileno',
                'CNY' => 'Yuan Cinese',
                'COP' => 'Peso Colombiano',
                'CZK' => 'Corona Ceca',
                'DKK' => 'Corona Danese',
                'DZD' => 'Dinaro Algerino',
                'EGP' => 'Sterlina Egiziana',
                'EUR' => 'Euro',
                'FJD' => 'Dollaro delle Figi',
                'GBP' => 'Sterlina Britannica',
                'HKD' => 'Dollaro di Hong Kong',
                'HUF' => 'Fiorino Ungherese',
                'IDR' => 'Rupia Indonesiana',
                'ILS' => 'Nuovo Shekel Israeliano',
                'INR' => 'Rupia Indiana',
                'JOD' => 'Dinaro Giordano',
                'JPY' => 'Yen Giapponese',
                'KRW' => 'Won Sudcoreano',
                'KWD' => 'Dinaro Kuwaitiano',
                'KZT' => 'Tenge Kazako',
                'LBP' => 'Sterlina Libanese',
                'LKR' => 'Rupia dello Sri Lanka',
                'LYD' => 'Dinaro Libico',
                'MAD' => 'Dirham Marocchino',
                'MUR' => 'Rupia Mauriziana',
                'MXN' => 'Peso Messicano',
                'MYR' => 'Ringgit Malese',
                'NGN' => 'Naira Nigeriana',
                'NOK' => 'Corona Norvegese',
                'NPR' => 'Rupia Nepalese',
                'NZD' => 'Dollaro Neozelandese',
                'OMR' => 'Rial Omanita',
                'PAB' => 'Balboa Panamense',
                'PEN' => 'Nuevo Sol Peruviano',
                'PHP' => 'Peso Filippino',
                'PKR' => 'Rupia Pakistana',
                'PLN' => 'Złoty Polacco',
                'PYG' => 'Guaraní Paraguaiano',
                'QAR' => 'Rial del Qatar',
                'RON' => 'Leu Rumeno',
                'RUB' => 'Rublo Russo',
                'SAR' => 'Riyal Saudita',
                'SEK' => 'Corona Svedese',
                'SGD' => 'Dollaro di Singapore',
                'THB' => 'Baht Tailandese',
                'TND' => 'Dinaro Tunisino',
                'TRY' => 'Lira Turca',
                'TWD' => 'Nuovo Dollaro di Taiwan',
                'UAH' => 'Grivnia Ucraina',
                'USD' => 'Dollaro Statunitense',
                'UZS' => 'Som Uzbeko',
                'VEF' => 'Bolívar Venezuelano',
                'VND' => 'Dong Vietnamita',
                'XAF' => 'Franco CFA BEAC',
                'XOF' => 'Franco CFA BCEAO',
                'ZAR' => 'Rand Sudafricano',
                'ZMW' => 'Kwacha Zambiano',
            ],

            'locales' => [
                'ar' => 'Arabo',
                'bn' => 'Bengalese',
                'ca' => 'Catalano',
                'de' => 'Tedesco',
                'en' => 'Inglese',
                'es' => 'Spagnolo',
                'fa' => 'Persiano',
                'fr' => 'Francese',
                'he' => 'Ebraico',
                'hi_IN' => 'Hindi',
                'id' => 'Indonesiano',
                'it' => 'Italiano',
                'ja' => 'Giapponese',
                'nl' => 'Olandese',
                'pl' => 'Polacco',
                'pt_BR' => 'Portoghese Brasiliano',
                'ru' => 'Russo',
                'sin' => 'Sinhala',
                'tr' => 'Turco',
                'uk' => 'Ucraino',
                'zh_CN' => 'Cinese',
            ],
        ],

        'customer' => [
            'customer-groups' => [
                'general' => 'Generale',
                'guest' => 'Ospite',
                'wholesale' => 'Ingrosso',
            ],
        ],

        'inventory' => [
            'inventory-sources' => [
                'name' => 'Predefinito',
            ],
        ],

        'shop' => [
            'theme-customizations' => [
                'all-products' => [
                    'name' => 'Tutti i Prodotti',

                    'options' => [
                        'title' => 'Tutti i Prodotti',
                    ],
                ],

                'bold-collections' => [
                    'content' => [
                        'btn-title' => 'Visualizza Collezioni',
                        'description' => 'Presentiamo le nostre nuove Collezioni Audaci! Elevalo il tuo stile con design audaci e dichiarazioni vibranti. Esplora modelli straordinari e colori audaci che ridefiniscono il tuo guardaroba. Preparati ad abbracciare l\'straordinario!',
                        'title' => 'Preparati per le nostre nuove Collezioni Audaci!',
                    ],

                    'name' => 'Collezioni Audaci',
                ],

                'bold-collections-2' => [
                    'content' => [
                        'btn-title' => 'Visualizza Collezioni',
                        'description' => 'Le nostre Collezioni Audaci sono qui per ridefinire il tuo guardaroba con design audaci e colori vivaci e sorprendenti. Dai pattern audaci alle tonalità potenti, questa è la tua occasione per uscire dall\'ordinario ed entrare nello straordinario.',
                        'title' => 'Libera la Tua Audacia con la Nostra Nuova Collezione!',
                    ],

                    'name' => 'Collezioni Audaci',
                ],

                'booking-products' => [
                    'name' => 'Prodotti Prenotazione',

                    'options' => [
                        'title' => 'Prenota Biglietti',
                    ],
                ],

                'categories-collections' => [
                    'name' => 'Collezioni per Categorie',
                ],

                'featured-collections' => [
                    'name' => 'Prodotti in Primo Piano',

                    'options' => [
                        'title' => 'Prodotti in Primo Piano',
                    ],
                ],

                'footer-links' => [
                    'name' => 'Link del Piede',

                    'options' => [
                        'about-us' => 'Chi Siamo',
                        'contact-us' => 'Contattaci',
                        'customer-service' => 'Servizio Clienti',
                        'payment-policy' => 'Politica di Pagamento',
                        'privacy-policy' => 'Informativa sulla Privacy',
                        'refund-policy' => 'Politica di Rimborso',
                        'return-policy' => 'Politica di Reso',
                        'shipping-policy' => 'Politica di Spedizione',
                        'terms-conditions' => 'Termini e Condizioni',
                        'terms-of-use' => 'Termini di Utilizzo',
                        'whats-new' => 'Novità',
                    ],
                ],

                'game-container' => [
                    'content' => [
                        'sub-title-1' => 'Le Nostre Collezioni',
                        'sub-title-2' => 'Le Nostre Collezioni',
                        'title' => 'Il gioco con le nostre nuove aggiunte!',
                    ],

                    'name' => 'Contenitore del Gioco',
                ],

                'image-carousel' => [
                    'name' => 'Carosello delle Immagini',

                    'sliders' => [
                        'title' => 'Preparati per la Nuova Collezione',
                    ],
                ],

                'new-products' => [
                    'name' => 'Nuovi Prodotti',

                    'options' => [
                        'title' => 'Nuovi Prodotti',
                    ],
                ],

                'offer-information' => [
                    'content' => [
                        'title' => 'Fino al 40% di SCONTO sul tuo primo ordine ACQUISTA ORA',
                    ],

                    'name' => 'Informazioni sull\'Offerta',
                ],

                'services-content' => [
                    'description' => [
                        'emi-available-info' => 'EMI senza costi disponibile su tutte le principali carte di credito',
                        'free-shipping-info' => 'Goditi la spedizione gratuita su tutti gli ordini',
                        'product-replace-info' => 'Sostituzione facile del prodotto disponibile!',
                        'time-support-info' => 'Supporto dedicato 24/7 tramite chat ed e-mail',
                    ],

                    'name' => 'Contenuto dei servizi',

                    'title' => [
                        'emi-available' => 'EMI disponibile',
                        'free-shipping' => 'Spedizione gratuita',
                        'product-replace' => 'Sostituzione del prodotto',
                        'time-support' => 'Supporto 24/7',
                    ],
                ],

                'top-collections' => [
                    'content' => [
                        'sub-title-1' => 'Le Nostre Collezioni',
                        'sub-title-2' => 'Le Nostre Collezioni',
                        'sub-title-3' => 'Le Nostre Collezioni',
                        'sub-title-4' => 'Le Nostre Collezioni',
                        'sub-title-5' => 'Le Nostre Collezioni',
                        'sub-title-6' => 'Le Nostre Collezioni',
                        'title' => 'Il gioco con le nostre nuove aggiunte!',
                    ],

                    'name' => 'Collezioni Top',
                ],
            ],
        ],

        'user' => [
            'roles' => [
                'description' => 'Questo ruolo consentirà agli utenti di avere tutti gli accessi',
                'name' => 'Amministratore',
            ],

            'users' => [
                'name' => 'Esempio',
            ],
        ],

        'sample-categories' => [
            'category-translation' => [
                '2' => [
                    'description' => '<p>Uomo</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Uomo',
                    'slug' => 'mens',
                    'url-path' => 'men',
                ],

                '3' => [
                    'description' => '<p>Bambini</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Bambini',
                    'slug' => 'kids',
                    'url-path' => 'kids',
                ],

                '4' => [
                    'description' => '<p>Donna</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Donna',
                    'slug' => 'womens',
                    'url-path' => 'woman',
                ],

                '5' => [
                    'description' => '<p>Abbigliamento Formale</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Abbigliamento Formale',
                    'slug' => 'formal-wear-men',
                    'url-path' => 'men/formal-wear-men',
                ],

                '6' => [
                    'description' => '<p>Abbigliamento Casual</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Abbigliamento Casual',
                    'slug' => 'casual-wear-men',
                    'url-path' => 'men/casual-wear-men',
                ],

                '7' => [
                    'description' => '<p>Abbigliamento Sportivo</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Abbigliamento Sportivo',
                    'slug' => 'active-wear',
                    'url-path' => 'men/active-wear',
                ],

                '8' => [
                    'description' => '<p>Calzature</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Calzature',
                    'slug' => 'footwear',
                    'url-path' => 'men/footwear',
                ],

                '9' => [
                    'description' => '<p><span>Abbigliamento Formale</span></p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Abbigliamento Formale',
                    'slug' => 'formal-wear-female',
                    'url-path' => 'woman/formal-wear-female',
                ],

                '10' => [
                    'description' => '<p>Abbigliamento Casual</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Abbigliamento Casual',
                    'slug' => 'casual-wear-female',
                    'url-path' => 'woman/casual-wear-female',
                ],

                '11' => [
                    'description' => '<p>Abbigliamento Sportivo</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Abbigliamento Sportivo',
                    'slug' => 'active-wear-female',
                    'url-path' => 'woman/active-wear-female',
                ],

                '12' => [
                    'description' => '<p>Calzature</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Calzature',
                    'slug' => 'footwear-female',
                    'url-path' => 'woman/footwear-female',
                ],

                '13' => [
                    'description' => '<p>Abbigliamento Bambina</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => 'Abbigliamento Bambina',
                    'name' => 'Abbigliamento Bambina',
                    'slug' => 'girls-clothing',
                    'url-path' => 'kids/girls-clothing',
                ],

                '14' => [
                    'description' => '<p>Abbigliamento Bambino</p>',
                    'meta-description' => 'Moda Bambino',
                    'meta-keywords' => '',
                    'meta-title' => 'Abbigliamento Bambino',
                    'name' => 'Abbigliamento Bambino',
                    'slug' => 'boys-clothing',
                    'url-path' => 'kids/boys-clothing',
                ],

                '15' => [
                    'description' => '<p>Calzature Bambina</p>',
                    'meta-description' => 'Collezione Calzature Moda Bambina',
                    'meta-keywords' => '',
                    'meta-title' => 'Calzature Bambina',
                    'name' => 'Calzature Bambina',
                    'slug' => 'girls-footwear',
                    'url-path' => 'kids/girls-footwear',
                ],

                '16' => [
                    'description' => '<p>Calzature Bambino</p>',
                    'meta-description' => 'Collezione Calzature Eleganti Bambino',
                    'meta-keywords' => '',
                    'meta-title' => 'Calzature Bambino',
                    'name' => 'Calzature Bambino',
                    'slug' => 'boys-footwear',
                    'url-path' => 'kids/boys-footwear',
                ],

                '17' => [
                    'description' => '<p>Benessere</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Benessere',
                    'slug' => 'wellness',
                    'url-path' => 'wellness',
                ],

                '18' => [
                    'description' => '<p>Tutorial Yoga Scaricabile</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Tutorial Yoga Scaricabile',
                    'slug' => 'downloadable-yoga-tutorial',
                    'url-path' => 'wellness/downloadable-yoga-tutorial',
                ],

                '19' => [
                    'description' => '<p>Collezione E-Book</p>',
                    'meta-description' => 'Collezione E-Book',
                    'meta-keywords' => '',
                    'meta-title' => 'Collezione E-Book',
                    'name' => 'E-Book',
                    'slug' => 'e-books',
                    'url-path' => 'wellness/e-books',
                ],

                '20' => [
                    'description' => '<p>Pass Cinema</p>',
                    'meta-description' => 'Immergiti nella magia di 10 film al mese senza costi aggiuntivi.',
                    'meta-keywords' => '',
                    'meta-title' => 'Pass Cinema Mensile CineXperience',
                    'name' => 'Pass Cinema',
                    'slug' => 'movie-pass',
                    'url-path' => 'wellness/movie-pass',
                ],

                '21' => [
                    'description' => '<p>Gestisci e vendi facilmente i tuoi prodotti basati su prenotazioni con il nostro sistema di prenotazione integrato.</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Prenotazioni',
                    'slug' => 'bookings',
                    'url-path' => '',
                ],

                '22' => [
                    'description' => '<p>La prenotazione appuntamenti consente ai clienti di programmare fasce orarie per servizi o consulenze con aziende o professionisti.</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Prenotazione Appuntamento',
                    'slug' => 'appointment-booking',
                    'url-path' => '',
                ],

                '23' => [
                    'description' => '<p>La prenotazione eventi consente a individui o gruppi di registrarsi o prenotare posti per eventi pubblici o privati.</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Prenotazione Evento',
                    'slug' => 'event-booking',
                    'url-path' => '',
                ],

                '24' => [
                    'description' => '<p>La prenotazione sale comunali consente a individui, organizzazioni o gruppi di prenotare spazi comunitari per vari eventi.</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Prenotazioni Sale Comunali',
                    'slug' => 'community-hall-bookings',
                    'url-path' => '',
                ],

                '25' => [
                    'description' => '<p>La prenotazione tavolo consente ai clienti di prenotare tavoli in ristoranti, caffè o locali di ristorazione in anticipo.</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Prenotazione Tavolo',
                    'slug' => 'table-booking',
                    'url-path' => '',
                ],

                '26' => [
                    'description' => '<p>La prenotazione noleggio facilita la prenotazione di articoli o proprietà per uso temporaneo.</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Prenotazione Noleggio',
                    'slug' => 'rental-booking',
                    'url-path' => '',
                ],

                '27' => [
                    'description' => '<p>Esplora le ultime novità in elettronica di consumo, progettate per tenerti connesso, produttivo e intrattenuto.</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Elettronica',
                    'slug' => 'electronics',
                    'url-path' => '',
                ],

                '28' => [
                    'description' => '<p>Scopri smartphone, caricatori, custodie e altri elementi essenziali per rimanere connesso in movimento.</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Telefoni Cellulari e Accessori',
                    'slug' => 'mobile-phones-accessories',
                    'url-path' => '',
                ],

                '29' => [
                    'description' => '<p>Trova laptop potenti e tablet portatili per lavoro, studio e svago.</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Laptop e Tablet',
                    'slug' => 'laptops-tablets',
                    'url-path' => '',
                ],

                '30' => [
                    'description' => '<p>Acquista cuffie, auricolari e altoparlanti per goderti un suono cristallino.</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Dispositivi Audio',
                    'slug' => 'audio-devices',
                    'url-path' => '',
                ],

                '31' => [
                    'description' => '<p>Semplifica la vita con illuminazione intelligente, termostati, sistemi di sicurezza e altro.</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Casa Intelligente e Automazione',
                    'slug' => 'smart-home-automation',
                    'url-path' => '',
                ],

                '32' => [
                    'description' => '<p>Migliora il tuo spazio abitativo con elementi essenziali funzionali e stilosi per casa e cucina.</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Casa',
                    'slug' => 'household',
                    'url-path' => '',
                ],

                '33' => [
                    'description' => '<p>Sfoglia frullatori, friggitrici ad aria, macchine da caffè e altro per semplificare la preparazione dei pasti.</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Elettrodomestici da Cucina',
                    'slug' => 'kitchen-appliances',
                    'url-path' => '',
                ],

                '34' => [
                    'description' => '<p>Esplora set di pentole, utensili, stoviglie e servizi per le tue esigenze culinarie.</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Pentole e Articoli per la Tavola',
                    'slug' => 'cookware-dining',
                    'url-path' => '',
                ],

                '35' => [
                    'description' => '<p>Aggiungi comfort e charme con divani, tavoli, arte murale e accenti decorativi.</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Mobili e Decorazioni',
                    'slug' => 'furniture-decor',
                    'url-path' => '',
                ],

                '36' => [
                    'description' => '<p>Mantieni il tuo spazio impeccabile con aspirapolvere, spray detergenti, scope e organizer.</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Prodotti per la Pulizia',
                    'slug' => 'cleaning-supplies',
                    'url-path' => '',
                ],

                '37' => [
                    'description' => '<p>Accendi la tua immaginazione o organizza il tuo spazio di lavoro con un\'ampia selezione di libri e cancelleria.</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Libri e Cancelleria',
                    'slug' => 'books-stationery',
                    'url-path' => '',
                ],

                '38' => [
                    'description' => '<p>Immergiti in romanzi bestseller, biografie, auto-aiuto e altro.</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Narrativa e Saggistica',
                    'slug' => 'fiction-non-fiction-books',
                    'url-path' => '',
                ],

                '39' => [
                    'description' => '<p>Trova libri di testo, materiali di riferimento e guide di studio per tutte le età.</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Educativo e Accademico',
                    'slug' => 'educational-academic',
                    'url-path' => '',
                ],

                '40' => [
                    'description' => '<p>Acquista penne, quaderni, agende e articoli essenziali per l\'ufficio per la produttività.</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Forniture per Ufficio',
                    'slug' => 'office-supplies',
                    'url-path' => '',
                ],

                '41' => [
                    'description' => '<p>Esplora colori, pennelli, album da disegno e kit fai-da-te per creativi.</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Materiali per Arte e Artigianato',
                    'slug' => 'art-craft-materials',
                    'url-path' => '',
                ],
            ],
        ],
    ],

    'installer' => [
        'middleware' => [
            'already-installed' => 'L\'applicazione è già installata.',
        ],

        'index' => [
            'create-administrator' => [
                'admin' => 'Amministratore',
                'bagisto' => 'Bagisto',
                'confirm-password' => 'Conferma Password',
                'email' => 'Email',
                'email-address' => 'admin@example.com',
                'password' => 'Password',
                'title' => 'Crea Amministratore',
            ],

            'environment-configuration' => [
                'algerian-dinar' => 'Dinaro algerino (DZD)',
                'allowed-currencies' => 'Valute consentite',
                'allowed-locales' => 'Lingue consentite',
                'application-name' => 'Nome dell\'applicazione',
                'argentine-peso' => 'Peso argentino (ARS)',
                'australian-dollar' => 'Dollaro australiano (AUD)',
                'bagisto' => 'Bagisto',
                'bangladeshi-taka' => 'Taka bangladese (BDT)',
                'bahraini-dinar' => 'Dinaro del Bahrein (BHD)',
                'brazilian-real' => 'Real brasiliano (BRL)',
                'british-pound-sterling' => 'Sterlina britannica (GBP)',
                'canadian-dollar' => 'Dollaro canadese (CAD)',
                'cfa-franc-bceao' => 'Franco CFA BCEAO (XOF)',
                'cfa-franc-beac' => 'Franco CFA BEAC (XAF)',
                'chilean-peso' => 'Peso cileno (CLP)',
                'chinese-yuan' => 'Yuan cinese (CNY)',
                'colombian-peso' => 'Peso colombiano (COP)',
                'czech-koruna' => 'Corona ceca (CZK)',
                'danish-krone' => 'Corona danese (DKK)',
                'database-connection' => 'Connessione al database',
                'database-hostname' => 'Nome host del database',
                'database-name' => 'Nome del database',
                'database-password' => 'Password del database',
                'database-port' => 'Porta del database',
                'database-prefix' => 'Prefisso del database',
                'database-prefix-help' => 'La prefixazione ha di avere 4 caratteri e può contenere solo lettere, numeri e trattini bassi.',
                'database-username' => 'Nome utente del database',
                'default-currency' => 'Valuta predefinita',
                'default-locale' => 'Lingua predefinita',
                'default-timezone' => 'Fuso orario predefinito',
                'default-url' => 'URL predefinito',
                'default-url-link' => 'https://localhost',
                'egyptian-pound' => 'Sterlina egiziana (EGP)',
                'euro' => 'Euro (EUR)',
                'fijian-dollar' => 'Dollaro delle Figi (FJD)',
                'hong-kong-dollar' => 'Dollaro di Hong Kong (HKD)',
                'hungarian-forint' => 'Fiorino ungherese (HUF)',
                'indian-rupee' => 'Rupia indiana (INR)',
                'indonesian-rupiah' => 'Rupia indonesiana (IDR)',
                'israeli-new-shekel' => 'Nuovo siclo israeliano (ILS)',
                'japanese-yen' => 'Yen giapponese (JPY)',
                'jordanian-dinar' => 'Dinaro giordano (JOD)',
                'kazakhstani-tenge' => 'Tenge kazako (KZT)',
                'kuwaiti-dinar' => 'Dinaro kuwaitiano (KWD)',
                'lebanese-pound' => 'Sterlina libanese (LBP)',
                'libyan-dinar' => 'Dinaro libico (LYD)',
                'malaysian-ringgit' => 'Ringgit malese (MYR)',
                'mauritian-rupee' => 'Rupia mauriziana (MUR)',
                'mexican-peso' => 'Peso messicano (MXN)',
                'moroccan-dirham' => 'Dirham marocchino (MAD)',
                'mysql' => 'Mysql',
                'nepalese-rupee' => 'Rupia nepalese (NPR)',
                'new-taiwan-dollar' => 'Nuovo dollaro taiwanese (TWD)',
                'new-zealand-dollar' => 'Dollaro neozelandese (NZD)',
                'nigerian-naira' => 'Naira nigeriana (NGN)',
                'norwegian-krone' => 'Corona norvegese (NOK)',
                'omani-rial' => 'Rial omanita (OMR)',
                'pakistani-rupee' => 'Rupia pakistana (PKR)',
                'panamanian-balboa' => 'Balboa panamense (PAB)',
                'paraguayan-guarani' => 'Guaraní paraguayano (PYG)',
                'peruvian-nuevo-sol' => 'Nuevo sol peruviano (PEN)',
                'pgsql' => 'pgSQL',
                'philippine-peso' => 'Peso filippino (PHP)',
                'polish-zloty' => 'Złoty polacco (PLN)',
                'qatari-rial' => 'Rial qatariota (QAR)',
                'romanian-leu' => 'Leu rumeno (RON)',
                'russian-ruble' => 'Rublo russo (RUB)',
                'saudi-riyal' => 'Riyal saudita (SAR)',
                'select-timezone' => 'Seleziona fuso orario',
                'singapore-dollar' => 'Dollaro di Singapore (SGD)',
                'south-african-rand' => 'Rand sudafricano (ZAR)',
                'south-korean-won' => 'Won sudcoreano (KRW)',
                'sqlsrv' => 'SQLSRV',
                'sri-lankan-rupee' => 'Rupia dello Sri Lanka (LKR)',
                'swedish-krona' => 'Corona svedese (SEK)',
                'swiss-franc' => 'Franco svizzero (CHF)',
                'thai-baht' => 'Baht thailandese (THB)',
                'title' => 'Configurazione del negozio',
                'tunisian-dinar' => 'Dinaro tunisino (TND)',
                'turkish-lira' => 'Lira turca (TRY)',
                'ukrainian-hryvnia' => 'Grivnia ucraina (UAH)',
                'united-arab-emirates-dirham' => 'Dirham degli Emirati Arabi Uniti (AED)',
                'united-states-dollar' => 'Dollaro statunitense (USD)',
                'uzbekistani-som' => 'Som uzbeko (UZS)',
                'venezuelan-bolívar' => 'Bolívar venezuelano (VEF)',
                'vietnamese-dong' => 'Dong vietnamita (VND)',
                'warning-message' => 'Attenzione! Le impostazioni della lingua di sistema predefinita e della valuta predefinita sono permanenti e non possono essere modificate una volta impostate.',
                'zambian-kwacha' => 'Kwacha zambiano (ZMW)',
            ],

            'sample-products' => [
                'no' => 'No',
                'note' => 'Nota: il tempo di indicizzazione dipende dal numero di lingue selezionate. Questo processo può richiedere fino a 2 minuti per essere completato. Se aggiungi altre lingue, prova ad aumentare il tempo massimo di esecuzione nelle impostazioni del server e di PHP, oppure puoi utilizzare il nostro programma di installazione CLI per evitare il timeout della richiesta.',
                'sample-products' => 'Prodotti campione',
                'title' => 'Prodotti campione',
                'yes' => 'Sì',
            ],

            'installation-processing' => [
                'bagisto' => 'Installazione Bagisto',
                'bagisto-info' => 'Creazione delle tabelle del Database, questo potrebbe richiedere qualche momento',
                'title' => 'Installazione',
            ],

            'installation-completed' => [
                'admin-panel' => 'Pannello di Amministrazione',
                'bagisto-forums' => 'Forum di Bagisto',
                'customer-panel' => 'Pannello del Cliente',
                'explore-bagisto-extensions' => 'Esplora le Estensioni di Bagisto',
                'title' => 'Installazione Completata',
                'title-info' => 'Bagisto è stato installato con successo sul tuo sistema.',
            ],

            'ready-for-installation' => [
                'create-database-tables' => 'Crea le tabelle del database',
                'drop-existing-tables' => 'Elimina eventuali tabelle esistenti',
                'install' => 'Installazione',
                'install-info' => 'Bagisto per l\'installazione',
                'install-info-button' => 'Clicca il pulsante sottostante per',
                'populate-database-tables' => 'Popola le tabelle del database',
                'start-installation' => 'Avvia l\'installazione',
                'title' => 'Pronto per l\'installazione',
            ],

            'start' => [
                'locale' => 'Località',
                'main' => 'Inizio',
                'select-locale' => 'Seleziona la località',
                'title' => 'La tua installazione di Bagisto',
                'welcome-title' => 'Benvenuto in Bagisto',
            ],

            'server-requirements' => [
                'calendar' => 'Calendario',
                'ctype' => 'cType',
                'curl' => 'cURL',
                'dom' => 'dom',
                'fileinfo' => 'fileInfo',
                'filter' => 'Filtro',
                'gd' => 'GD',
                'hash' => 'Hash',
                'intl' => 'intl',
                'json' => 'JSON',
                'mbstring' => 'mbstring',
                'openssl' => 'openssl',
                'pcre' => 'pcre',
                'pdo' => 'pdo',
                'php' => 'PHP',
                'php-version' => ':version o superiore',
                'session' => 'sessione',
                'title' => 'Requisiti del server',
                'tokenizer' => 'tokenizer',
                'xml' => 'XML',
            ],

            'arabic' => 'Arabo',
            'back' => 'Indietro',
            'bagisto' => 'Bagisto',
            'bagisto-info' => 'Un progetto della comunità di',
            'bagisto-logo' => 'Logo Bagisto',
            'bengali' => 'Bengalese',
            'catalan' => 'Catalano',
            'chinese' => 'Cinese',
            'continue' => 'Continua',
            'dutch' => 'Olandese',
            'english' => 'Inglese',
            'french' => 'Francese',
            'german' => 'Tedesco',
            'hebrew' => 'Ebraico',
            'hindi' => 'Hindi',
            'indonesian' => 'Indonesiano',
            'installation-description' => 'L\'installazione di Bagisto generalmente prevede diversi passaggi. Ecco una panoramica generale del processo di installazione per Bagisto',
            'installation-info' => 'Siamo felici di vederti qui!',
            'installation-title' => 'Benvenuti all\'installazione',
            'italian' => 'Italiano',
            'japanese' => 'Giapponese',
            'persian' => 'Persiano',
            'polish' => 'Polacco',
            'portuguese' => 'Portoghese brasiliano',
            'russian' => 'Russo',
            'sinhala' => 'Singalese',
            'spanish' => 'Spagnolo',
            'title' => 'Installazione di Bagisto',
            'turkish' => 'Turco',
            'ukrainian' => 'Ucraino',
            'webkul' => 'Webkul',
        ],
    ],
];
