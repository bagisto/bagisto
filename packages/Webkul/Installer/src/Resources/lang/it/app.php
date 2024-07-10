<?php

return [
    'seeders' => [
        'attribute' => [
            'attribute-families' => [
                'default' => 'Predefinito',
            ],

            'attribute-groups' => [
                'description'      => 'Descrizione',
                'general'          => 'Generale',
                'inventories'      => 'Inventario',
                'meta-description' => 'Meta Descrizione',
                'price'            => 'Prezzo',
                'settings'         => 'Impostazioni',
                'shipping'         => 'Spedizione',
            ],

            'attributes' => [
                'brand'                => 'Marca',
                'color'                => 'Colore',
                'cost'                 => 'Costo',
                'description'          => 'Descrizione',
                'featured'             => 'In primo piano',
                'guest-checkout'       => 'Check-out Ospite',
                'height'               => 'Altezza',
                'length'               => 'Lunghezza',
                'manage-stock'         => 'Gestisci Scorte',
                'meta-description'     => 'Meta Descrizione',
                'meta-keywords'        => 'Meta Parole chiave',
                'meta-title'           => 'Meta Titolo',
                'name'                 => 'Nome',
                'new'                  => 'Nuovo',
                'price'                => 'Prezzo',
                'product-number'       => 'Numero del Prodotto',
                'short-description'    => 'Breve Descrizione',
                'size'                 => 'Taglia',
                'sku'                  => 'SKU',
                'special-price'        => 'Prezzo Speciale',
                'special-price-from'   => 'Prezzo Speciale Da',
                'special-price-to'     => 'Prezzo Speciale A',
                'status'               => 'Stato',
                'tax-category'         => 'Categoria Fiscale',
                'url-key'              => 'Chiave URL',
                'visible-individually' => 'Visibile Individualmente',
                'weight'               => 'Peso',
                'width'                => 'Larghezza',
            ],

            'attribute-options' => [
                'black'  => 'Nero',
                'green'  => 'Verde',
                'l'      => 'L',
                'm'      => 'M',
                'red'    => 'Rosso',
                's'      => 'S',
                'white'  => 'Bianco',
                'xl'     => 'XL',
                'yellow' => 'Giallo',
            ],
        ],

        'category' => [
            'categories' => [
                'description' => 'Descrizione della Categoria Radice',
                'name'        => 'Radice',
            ],
        ],

        'cms' => [
            'pages' => [
                'about-us' => [
                    'content' => 'Contenuto della Pagina Chi siamo',
                    'title'   => 'Chi siamo',
                ],

                'contact-us' => [
                    'content' => 'Contenuto della Pagina Contattaci',
                    'title'   => 'Contattaci',
                ],

                'customer-service' => [
                    'content' => 'Contenuto della Pagina Assistenza Clienti',
                    'title'   => 'Assistenza Clienti',
                ],

                'payment-policy'   => [
                    'content' => 'Contenuto della Pagina Politica di Pagamento',
                    'title'   => 'Politica di Pagamento',
                ],

                'privacy-policy' => [
                    'content' => 'Contenuto della Pagina Politica sulla Privacy',
                    'title'   => 'Politica sulla Privacy',
                ],

                'refund-policy' => [
                    'content' => 'Contenuto della Pagina Politica di Rimborso',
                    'title'   => 'Politica di Rimborso',
                ],

                'return-policy' => [
                    'content' => 'Contenuto della Pagina Politica di Reso',
                    'title'   => 'Politica di Reso',
                ],

                'shipping-policy' => [
                    'content' => 'Contenuto della Pagina Politica di Spedizione',
                    'title'   => 'Politica di Spedizione',
                ],

                'terms-conditions' => [
                    'content' => 'Contenuto della Pagina Termini e Condizioni',
                    'title'   => 'Termini e Condizioni',
                ],

                'terms-of-use' => [
                    'content' => 'Contenuto della Pagina Termini d\'Uso',
                    'title'   => 'Termini d\'Uso',
                ],

                'whats-new' => [
                    'content' => 'Contenuto della Pagina Novità',
                    'title'   => 'Novità',
                ],
            ],
        ],

        'core' => [
            'channels' => [
                'name'             => 'Predefinito',
                'meta-title'       => 'Negozio Dimostrativo',
                'meta-keywords'    => 'Parole chiave meta del Negozio Dimostrativo',
                'meta-description' => 'Descrizione meta del Negozio Dimostrativo',
            ],

            'currencies' => [
                'AED' => 'Dirham degli Emirati Arabi Uniti',
                'ARS' => 'Peso Argentino',
                'AUD' => 'Dollaro Australiano',
                'BDT' => 'Taka Bangladese',
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

            'locales'    => [
                'ar'    => 'Arabo',
                'bn'    => 'Bengalese',
                'de'    => 'Tedesco',
                'en'    => 'Inglese',
                'es'    => 'Spagnolo',
                'fa'    => 'Persiano',
                'fr'    => 'Francese',
                'he'    => 'Ebraico',
                'hi_IN' => 'Hindi',
                'it'    => 'Italiano',
                'ja'    => 'Giapponese',
                'nl'    => 'Olandese',
                'pl'    => 'Polacco',
                'pt_BR' => 'Portoghese Brasiliano',
                'ru'    => 'Russo',
                'sin'   => 'Sinhala',
                'tr'    => 'Turco',
                'uk'    => 'Ucraino',
                'zh_CN' => 'Cinese',
            ],
        ],

        'customer' => [
            'customer-groups' => [
                'general'   => 'Generale',
                'guest'     => 'Ospite',
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
                        'btn-title'   => 'Visualizza Collezioni',
                        'description' => 'Presentiamo le nostre nuove Collezioni Audaci! Elevalo il tuo stile con design audaci e dichiarazioni vibranti. Esplora modelli straordinari e colori audaci che ridefiniscono il tuo guardaroba. Preparati ad abbracciare l\'straordinario!',
                        'title'       => 'Preparati per le nostre nuove Collezioni Audaci!',
                    ],

                    'name' => 'Collezioni Audaci',
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
                        'about-us'         => 'Chi Siamo',
                        'contact-us'       => 'Contattaci',
                        'customer-service' => 'Servizio Clienti',
                        'payment-policy'   => 'Politica di Pagamento',
                        'privacy-policy'   => 'Informativa sulla Privacy',
                        'refund-policy'    => 'Politica di Rimborso',
                        'return-policy'    => 'Politica di Reso',
                        'shipping-policy'  => 'Politica di Spedizione',
                        'terms-conditions' => 'Termini e Condizioni',
                        'terms-of-use'     => 'Termini di Utilizzo',
                        'whats-new'        => 'Novità',
                    ],
                ],

                'game-container' => [
                    'content' => [
                        'sub-title-1' => 'Le Nostre Collezioni',
                        'sub-title-2' => 'Le Nostre Collezioni',
                        'title'       => 'Il gioco con le nostre nuove aggiunte!',
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
                        'emi-available-info'   => 'EMI senza costi disponibile su tutte le principali carte di credito',
                        'free-shipping-info'   => 'Goditi la spedizione gratuita su tutti gli ordini',
                        'product-replace-info' => 'Sostituzione facile del prodotto disponibile!',
                        'time-support-info'    => 'Supporto dedicato 24/7 tramite chat ed e-mail',
                    ],

                    'name' => 'Contenuto dei servizi',

                    'title' => [
                        'emi-available'   => 'EMI disponibile',
                        'free-shipping'   => 'Spedizione gratuita',
                        'product-replace' => 'Sostituzione del prodotto',
                        'time-support'    => 'Supporto 24/7',
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
                        'title'       => 'Il gioco con le nostre nuove aggiunte!',
                    ],

                    'name' => 'Collezioni Top',
                ],
            ],
        ],

        'user' => [
            'roles' => [
                'description' => 'Questo ruolo consentirà agli utenti di avere tutti gli accessi',
                'name'        => 'Amministratore',
            ],

            'users' => [
                'name' => 'Esempio',
            ],
        ],

        'sample-categories' => [
            'category-translation' => [
                '2' => [
                    'description'      => 'Descrizione della categoria Uomo',
                    'meta-description' => 'Meta descrizione della categoria Uomo',
                    'meta-keywords'    => 'Meta parole chiave della categoria Uomo',
                    'meta-title'       => 'Meta titolo della categoria Uomo',
                    'name'             => 'Uomini',
                    'slug'             => 'uomini',
                ],

                '3' => [
                    'description'      => 'Descrizione della categoria Abbigliamento Invernale',
                    'meta-description' => 'Meta descrizione della categoria Abbigliamento Invernale',
                    'meta-keywords'    => 'Meta parole chiave della categoria Abbigliamento Invernale',
                    'meta-title'       => 'Meta titolo della categoria Abbigliamento Invernale',
                    'name'             => 'Abbigliamento invernale',
                    'slug'             => 'abbigliamento invernale',
                ],
            ],
        ],

        'sample-products' => [
            'product-flat' => [
                '1' => [
                    'description'       => 'L\'Arctic Cozy Knit Beanie è la soluzione ideale per rimanere al caldo, comodi e alla moda durante i mesi più freddi. Realizzato in una morbida e resistente miscela di maglia acrilica, questo berretto è progettato per offrire una vestibilità calda e avvolgente. Il design classico lo rende adatto sia per uomini che per donne, offrendo un accessorio versatile che si adatta a vari stili. Che tu stia uscendo per una giornata informale in città o che tu stia abbracciando la natura, questo berretto aggiunge un tocco di comfort e calore al tuo abbigliamento. Il materiale morbido e traspirante garantisce che tu rimanga al caldo senza sacrificare lo stile. L\'Arctic Cozy Knit Beanie non è solo un accessorio; è una dichiarazione di moda invernale. La sua semplicità lo rende facile da abbinare a diversi outfit, rendendolo un capo indispensabile nel tuo guardaroba invernale. Ideale come regalo o come coccola per te stesso, questo berretto è un\'aggiunta premurosa a qualsiasi ensemble invernale. È un accessorio versatile che va oltre la funzionalità, aggiungendo un tocco di calore e stile al tuo look. Abbraccia l\'essenza dell\'inverno con l\'Arctic Cozy Knit Beanie. Che tu stia godendo di una giornata informale o affrontando gli elementi, lascia che questo berretto sia il tuo compagno di comfort e stile. Eleva il tuo guardaroba invernale con questo accessorio classico che combina senza sforzo calore con un senso senza tempo della moda.',
                    'meta-description'  => 'meta description',
                    'meta-keywords'     => 'meta1, meta2, meta3',
                    'meta-title'        => 'Meta Title',
                    'name'              => 'Berretto unisex Arctic Cozy Knit',
                    'short-description' => 'Abbraccia le giornate fredde con stile con il nostro Arctic Cozy Knit Beanie. Realizzato in una morbida e resistente miscela di acrilico, questo classico berretto offre calore e versatilità. Adatto sia per uomini che per donne, è l\'accessorio ideale per l\'abbigliamento casual o outdoor. Eleva il tuo guardaroba invernale o regala qualcosa di speciale con questo berretto essenziale.',
                ],

                '2' => [
                    'description'       => 'L\'Arctic Bliss Winter Scarf è più di un semplice accessorio per il freddo; è una dichiarazione di calore, comfort e stile per la stagione invernale. Realizzata con cura in una lussuosa miscela di acrilico e lana, questa sciarpa è progettata per tenerti al caldo e al sicuro anche nelle temperature più fredde. La texture morbida e peluche non solo offre isolamento dal freddo, ma aggiunge anche un tocco di lusso al tuo guardaroba invernale. Il design dell\'Arctic Bliss Winter Scarf è sia elegante che versatile, rendendolo un perfetto complemento a una varietà di outfit invernali. Che tu ti stia vestendo per un\'occasione speciale o aggiungendo uno strato chic al tuo look quotidiano, questa sciarpa si adatta al tuo stile senza sforzo. La lunghezza extra lunga della sciarpa offre opzioni di styling personalizzabili. Avvolgila per un calore aggiuntivo, lasciala cadere liberamente per un look casual o sperimenta con diversi nodi per esprimere il tuo stile unico. Questa versatilità la rende un accessorio indispensabile per la stagione invernale. Cerchi il regalo perfetto? L\'Arctic Bliss Winter Scarf è una scelta ideale. Che tu stia sorprendendo una persona cara o facendoti un regalo, questa sciarpa è un regalo senza tempo e pratico che sarà apprezzato durante i mesi invernali. Abbraccia l\'inverno con l\'Arctic Bliss Winter Scarf, dove calore e stile si incontrano in perfetta armonia. Eleva il tuo guardaroba invernale con questo accessorio essenziale che non solo ti tiene al caldo, ma aggiunge anche un tocco di sofisticazione al tuo ensemble per il freddo.',
                    'meta-description'  => 'meta description',
                    'meta-keywords'     => 'meta1, meta2, meta3',
                    'meta-title'        => 'Meta Title',
                    'name'              => 'Sciarpa invernale elegante Arctic Bliss',
                    'short-description' => 'Vivi l\'abbraccio di calore e stile con la nostra Arctic Bliss Winter Scarf. Realizzata in una lussuosa miscela di acrilico e lana, questa sciarpa accogliente è progettata per tenerti al caldo durante i giorni più freddi. Il suo design elegante e versatile, combinato con una lunghezza extra lunga, offre opzioni di styling personalizzabili. Eleva il tuo guardaroba invernale o delizia qualcuno di speciale con questo accessorio invernale essenziale.',
                ],

                '3' => [
                    'description'       => 'Presentiamo i guanti invernali Arctic Touchscreen, dove calore, stile e connettività si incontrano per migliorare la tua esperienza invernale. Realizzati in acrilico di alta qualità, questi guanti sono progettati per offrire calore e resistenza eccezionali. Le punte compatibili con lo schermo touch consentono di rimanere connessi senza esporre le mani al freddo. Rispondi alle chiamate, invia messaggi e naviga tra i tuoi dispositivi senza sforzo, mantenendo le mani al caldo. L\'imbottitura isolante aggiunge un ulteriore strato di comfort, rendendo questi guanti la scelta ideale per affrontare il freddo invernale. Che tu stia andando al lavoro, facendo commissioni o godendoti attività all\'aperto, questi guanti offrono il calore e la protezione di cui hai bisogno. I polsini elastici assicurano una vestibilità sicura, evitando correnti d\'aria fredde e mantenendo i guanti al loro posto durante le tue attività quotidiane. Il design elegante aggiunge un tocco di stile al tuo ensemble invernale, rendendo questi guanti tanto alla moda quanto funzionali. Ideali come regalo o come coccola per te stesso, i guanti invernali Arctic Touchscreen sono un accessorio indispensabile per l\'individuo moderno. Dì addio all\'inconveniente di dover togliere i guanti per usare i tuoi dispositivi e abbraccia la perfetta combinazione di calore, stile e connettività. Resta connesso, rimani al caldo e mantieni lo stile con i guanti invernali Arctic Touchscreen: il tuo compagno affidabile per conquistare la stagione invernale con fiducia.',
                    'meta-description'  => 'meta description',
                    'meta-keywords'     => 'meta1, meta2, meta3',
                    'meta-title'        => 'Meta Title',
                    'name'              => 'Guanti invernali Arctic Touchscreen',
                    'short-description' => 'Resta connesso e al caldo con i nostri guanti invernali Arctic Touchscreen. Questi guanti non solo sono realizzati in acrilico di alta qualità per calore e resistenza, ma presentano anche un design compatibile con lo schermo touch. Con un rivestimento isolante, polsini elastici per una vestibilità sicura e un look elegante, questi guanti sono perfetti per l\'uso quotidiano in condizioni fredde.',
                ],

                '4' => [
                    'description'       => 'Presentiamo i Calzini in Lana Arctic Warmth - il tuo compagno essenziale per piedi caldi e confortevoli durante le stagioni più fredde. Realizzati con una miscela premium di lana Merino, acrilico, nylon e spandex, questi calzini sono progettati per offrire un calore e un comfort senza pari. La miscela di lana assicura che i tuoi piedi rimangano caldi anche nelle temperature più fredde, rendendo questi calzini la scelta perfetta per le avventure invernali o semplicemente per stare comodi a casa. La texture morbida e accogliente dei calzini offre una sensazione lussuosa sulla pelle. Dì addio ai piedi freddi mentre abbracci il calore morbido offerto da questi calzini in lana. Progettati per la durata nel tempo, i calzini presentano un rinforzo sul tallone e sulla punta, aggiungendo resistenza alle zone soggette a maggiore usura. Ciò assicura che i tuoi calzini resistano alla prova del tempo, offrendo comfort e accoglienza a lungo. La natura traspirante del materiale previene il surriscaldamento, consentendo ai tuoi piedi di rimanere comodi e asciutti durante tutto il giorno. Che tu stia andando all\'aperto per un\'escursione invernale o rilassandoti in casa, questi calzini offrono il perfetto equilibrio tra calore e traspirabilità. Versatili e alla moda, questi calzini in lana sono adatti a diverse occasioni. Abbinali ai tuoi stivali preferiti per un look invernale alla moda o indossali in casa per un comfort assoluto. Eleva il tuo guardaroba invernale e dai priorità al comfort con i Calzini in Lana Arctic Warmth. Coccola i tuoi piedi con il lusso che meritano e immergiti in un mondo di accoglienza che dura per tutta la stagione.',
                    'meta-description'  => 'meta description',
                    'meta-keywords'     => 'meta1, meta2, meta3',
                    'meta-title'        => 'Titolo Meta',
                    'name'              => 'Calzini in Lana Arctic Warmth',
                    'short-description' => 'Sperimenta il calore e il comfort senza pari dei nostri Calzini in Lana Arctic Warmth. Realizzati con una miscela di lana Merino, acrilico, nylon e spandex, questi calzini offrono un comfort assoluto per il clima freddo. Con un tallone e una punta rinforzati per la durata nel tempo, questi calzini versatili e alla moda sono perfetti per diverse occasioni.',
                ],

                '5' => [
                    'description'       => 'Presentiamo il Bundle di Accessori Invernali Arctic Frost, la tua soluzione ideale per rimanere caldo, alla moda e connesso durante le giornate invernali più fredde. Questo set attentamente curato unisce quattro accessori invernali essenziali per creare un ensemble armonioso. La lussuosa sciarpa, intrecciata con una miscela di acrilico e lana, non solo aggiunge un ulteriore strato di calore, ma conferisce anche un tocco di eleganza al tuo guardaroba invernale. Il morbido berretto a maglia, realizzato con cura, promette di mantenerti al caldo aggiungendo un tocco di moda al tuo look. Ma non finisce qui: il nostro bundle include anche guanti compatibili con lo schermo touch. Rimani connesso senza sacrificare il calore mentre navighi tra i tuoi dispositivi senza sforzo. Che tu stia rispondendo alle chiamate, inviando messaggi o catturando momenti invernali sul tuo smartphone, questi guanti garantiscono comodità senza compromettere lo stile. La texture morbida e accogliente dei calzini offre una sensazione lussuosa sulla pelle. Dì addio ai piedi freddi mentre abbracci il calore morbido offerto da questi calzini in lana. Il Bundle di Accessori Invernali Arctic Frost non riguarda solo la funzionalità; è una dichiarazione di moda invernale. per rimanere caldo e chic.',
                    'meta-description'  => 'meta description',
                    'meta-keywords'     => 'meta1, meta2, meta3',
                    'meta-title'        => 'Titolo Meta',
                    'name'              => 'Bundle di Accessori Invernali Arctic Frost',
                    'short-description' => 'Abbraccia il freddo invernale con il nostro Bundle di Accessori Invernali Arctic Frost. Questo set curato include una lussuosa sciarpa, un berretto accogliente, guanti compatibili con lo schermo touch e calzini in lana. Stiloso e funzionale, questo ensemble è realizzato con materiali di alta qualità, garantendo sia durata che comfort. Eleva il tuo guardaroba invernale o delizia qualcuno di speciale con questa opzione regalo perfetta.',
                ],

                '6' => [
                    'description'       => 'Presentiamo il Bundle di Accessori Invernali Arctic Frost, la tua soluzione ideale per rimanere caldo, alla moda e connesso durante le giornate invernali più fredde. Questo set attentamente curato unisce quattro accessori invernali essenziali per creare un ensemble armonioso. La lussuosa sciarpa, intrecciata con una miscela di acrilico e lana, non solo aggiunge un ulteriore strato di calore, ma conferisce anche un tocco di eleganza al tuo guardaroba invernale. Il morbido berretto a maglia, realizzato con cura, promette di mantenerti al caldo aggiungendo un tocco di moda al tuo look. Ma non finisce qui: il nostro bundle include anche guanti compatibili con lo schermo touch. Rimani connesso senza sacrificare il calore mentre navighi tra i tuoi dispositivi senza sforzo. Che tu stia rispondendo alle chiamate, inviando messaggi o catturando momenti invernali sul tuo smartphone, questi guanti garantiscono comodità senza compromettere lo stile. La texture morbida e accogliente dei calzini offre una sensazione lussuosa sulla pelle. Dì addio ai piedi freddi mentre abbracci il calore morbido offerto da questi calzini in lana. Il Bundle di Accessori Invernali Arctic Frost non riguarda solo la funzionalità; è una dichiarazione di moda invernale. Ogni pezzo è progettato non solo per proteggerti dal freddo, ma anche per elevare il tuo stile durante la stagione gelida. I materiali scelti per questo bundle danno priorità sia alla durata nel tempo che al comfort, garantendo che tu possa goderti il paesaggio invernale con stile. Che tu stia facendo un regalo a te stesso o cercando il regalo perfetto, il Bundle di Accessori Invernali Arctic Frost è una scelta versatile. Delizia qualcuno di speciale durante la stagione delle vacanze o eleva il tuo guardaroba invernale con questo ensemble elegante e funzionale. Abbraccia il gelo con fiducia, sapendo di avere gli accessori perfetti per rimanere caldo e chic.',
                    'meta-description'  => 'meta description',
                    'meta-keywords'     => 'meta1, meta2, meta3',
                    'meta-title'        => 'Titolo Meta',
                    'name'              => 'Bundle di Accessori Invernali Arctic Frost',
                    'short-description' => 'Abbraccia il freddo invernale con il nostro Bundle di Accessori Invernali Arctic Frost. Questo set curato include una lussuosa sciarpa, un berretto accogliente, guanti compatibili con lo schermo touch e calzini in lana. Stiloso e funzionale, questo ensemble è realizzato con materiali di alta qualità, garantendo sia durata che comfort. Eleva il tuo guardaroba invernale o delizia qualcuno di speciale con questa opzione regalo perfetta.',
                ],

                '7' => [
                    'description'       => 'Presentiamo la giacca imbottita con cappuccio OmniHeat da uomo, la soluzione ideale per rimanere al caldo e alla moda durante le stagioni più fredde. Questa giacca è realizzata con resistenza e calore in mente, garantendo che diventi il tuo compagno fidato. Il design con cappuccio non solo aggiunge un tocco di stile, ma offre anche un calore aggiuntivo, proteggendoti dai venti freddi e dal maltempo. Le maniche lunghe offrono una copertura completa, assicurando che tu rimanga comodo dalla spalla al polso. Dotata di tasche interne, questa giacca imbottita offre comodità per trasportare gli essenziali o mantenere le mani calde. Il riempimento sintetico isolante offre un calore migliorato, rendendola ideale per affrontare giornate e notti fredde. Realizzata in resistente poliestere per il guscio e la fodera, questa giacca è costruita per durare e resistere agli agenti atmosferici. Disponibile in 5 colori attraenti, puoi scegliere quello che si adatta al tuo stile e alle tue preferenze. Versatile e funzionale, la giacca imbottita con cappuccio OmniHeat da uomo è adatta a diverse occasioni, che tu stia andando al lavoro, facendo una gita informale o partecipando a un evento all\'aperto. Sperimenta la perfetta combinazione di stile, comfort e funzionalità con la giacca imbottita con cappuccio OmniHeat da uomo. Eleva il tuo guardaroba invernale e rimani al caldo mentre abbracci la natura. Sconfiggi il freddo con stile e fai una dichiarazione con questo capo essenziale.',
                    'meta-description'  => 'meta description',
                    'meta-keywords'     => 'meta1, meta2, meta3',
                    'meta-title'        => 'Titolo Meta',
                    'name'              => 'Giacca imbottita con cappuccio OmniHeat da uomo',
                    'short-description' => 'Rimani al caldo e alla moda con la nostra giacca imbottita con cappuccio OmniHeat da uomo. Questa giacca è progettata per offrire un calore eccezionale e dispone di tasche interne per una maggiore comodità. Il materiale isolante garantisce che tu rimanga comodo in condizioni di freddo. Disponibile in 5 colori attraenti, rendendola una scelta versatile per varie occasioni.',
                ],

                '8' => [
                    'description'       => 'Presentiamo la giacca imbottita con cappuccio OmniHeat da uomo, la soluzione ideale per rimanere al caldo e alla moda durante le stagioni più fredde. Questa giacca è realizzata con resistenza e calore in mente, garantendo che diventi il tuo compagno fidato. Il design con cappuccio non solo aggiunge un tocco di stile, ma offre anche un calore aggiuntivo, proteggendoti dai venti freddi e dal maltempo. Le maniche lunghe offrono una copertura completa, assicurando che tu rimanga comodo dalla spalla al polso. Dotata di tasche interne, questa giacca imbottita offre comodità per trasportare gli essenziali o mantenere le mani calde. Il riempimento sintetico isolante offre un calore migliorato, rendendola ideale per affrontare giornate e notti fredde. Realizzata in resistente poliestere per il guscio e la fodera, questa giacca è costruita per durare e resistere agli agenti atmosferici. Disponibile in 5 colori attraenti, puoi scegliere quello che si adatta al tuo stile e alle tue preferenze. Versatile e funzionale, la giacca imbottita con cappuccio OmniHeat da uomo è adatta a diverse occasioni, che tu stia andando al lavoro, facendo una gita informale o partecipando a un evento all\'aperto. Sperimenta la perfetta combinazione di stile, comfort e funzionalità con la giacca imbottita con cappuccio OmniHeat da uomo. Eleva il tuo guardaroba invernale e rimani al caldo mentre abbracci la natura. Sconfiggi il freddo con stile e fai una dichiarazione con questo capo essenziale.',
                    'meta-description'  => 'meta description',
                    'meta-keywords'     => 'meta1, meta2, meta3',
                    'meta-title'        => 'Titolo Meta',
                    'name'              => 'Giacca imbottita con cappuccio OmniHeat da uomo-Blu-Giallo-M',
                    'short-description' => 'Rimani al caldo e alla moda con la nostra giacca imbottita con cappuccio OmniHeat da uomo. Questa giacca è progettata per offrire un calore eccezionale e dispone di tasche interne per una maggiore comodità. Il materiale isolante garantisce che tu rimanga comodo in condizioni di freddo. Disponibile in 5 colori attraenti, rendendola una scelta versatile per varie occasioni.',
                ],

                '9' => [
                    'description'       => 'Presentiamo la giacca imbottita con cappuccio OmniHeat da uomo, la soluzione ideale per rimanere al caldo e alla moda durante le stagioni più fredde. Questa giacca è realizzata con resistenza e calore in mente, garantendo che diventi il tuo compagno fidato. Il design con cappuccio non solo aggiunge un tocco di stile, ma offre anche un calore aggiuntivo, proteggendoti dai venti freddi e dal maltempo. Le maniche lunghe offrono una copertura completa, assicurando che tu rimanga comodo dalla spalla al polso. Dotata di tasche interne, questa giacca imbottita offre comodità per trasportare gli essenziali o mantenere le mani calde. Il riempimento sintetico isolante offre un calore migliorato, rendendola ideale per affrontare giornate e notti fredde. Realizzata in resistente poliestere per il guscio e la fodera, questa giacca è costruita per durare e resistere agli agenti atmosferici. Disponibile in 5 colori attraenti, puoi scegliere quello che si adatta al tuo stile e alle tue preferenze. Versatile e funzionale, la giacca imbottita con cappuccio OmniHeat da uomo è adatta a diverse occasioni, che tu stia andando al lavoro, facendo una gita informale o partecipando a un evento all\'aperto. Sperimenta la perfetta combinazione di stile, comfort e funzionalità con la giacca imbottita con cappuccio OmniHeat da uomo. Eleva il tuo guardaroba invernale e rimani al caldo mentre abbracci la natura. Sconfiggi il freddo con stile e fai una dichiarazione con questo capo essenziale.',
                    'meta-description'  => 'meta description',
                    'meta-keywords'     => 'meta1, meta2, meta3',
                    'meta-title'        => 'Titolo Meta',
                    'name'              => 'Giacca imbottita con cappuccio OmniHeat da uomo-Blu-Giallo-L',
                    'short-description' => 'Rimani al caldo e alla moda con la nostra giacca imbottita con cappuccio OmniHeat da uomo. Questa giacca è progettata per offrire un calore eccezionale e dispone di tasche interne per una maggiore comodità. Il materiale isolante garantisce che tu rimanga comodo in condizioni di freddo. Disponibile in 5 colori attraenti, rendendola una scelta versatile per varie occasioni.',
                ],

                '10' => [
                    'description'       => 'Presentiamo la giacca imbottita con cappuccio OmniHeat da uomo, la soluzione ideale per rimanere al caldo e alla moda durante le stagioni più fredde. Questa giacca è realizzata con resistenza e calore in mente, garantendo che diventi il tuo compagno fidato. Il design con cappuccio non solo aggiunge un tocco di stile, ma offre anche calore aggiuntivo, proteggendoti dai venti freddi e dal maltempo. Le maniche lunghe offrono una copertura completa, assicurando che tu rimanga comodo dalla spalla al polso. Dotata di tasche interne, questa giacca imbottita offre comodità per trasportare i tuoi oggetti essenziali o mantenere le mani calde. Il materiale isolante sintetico offre calore migliorato, rendendola ideale per affrontare giornate e notti fredde. Realizzata in un resistente guscio e fodera in poliestere, questa giacca è costruita per durare e resistere agli elementi. Disponibile in 5 colori attraenti, puoi scegliere quello che si adatta al tuo stile e alle tue preferenze. Versatile e funzionale, la giacca imbottita con cappuccio OmniHeat da uomo è adatta a varie occasioni, che tu stia andando al lavoro, facendo una gita informale o partecipando a un evento all\'aperto. Sperimenta la perfetta combinazione di stile, comfort e funzionalità con la giacca imbottita con cappuccio OmniHeat da uomo. Eleva il tuo guardaroba invernale e rimani al caldo mentre abbracci la natura. Sconfiggi il freddo con stile e fai una dichiarazione con questo capo essenziale.',
                    'meta-description'  => 'meta description',
                    'meta-keywords'     => 'meta1, meta2, meta3',
                    'meta-title'        => 'Titolo Meta',
                    'name'              => 'Giacca imbottita con cappuccio OmniHeat da uomo-Blu-Verde-M',
                    'short-description' => 'Rimani al caldo e alla moda con la nostra giacca imbottita con cappuccio OmniHeat da uomo. Questa giacca è progettata per offrire calore supremo e presenta tasche interne per maggiore comodità. Il materiale isolante garantisce che tu rimanga comodo in condizioni di freddo. Disponibile in 5 colori attraenti, rendendola una scelta versatile per varie occasioni.',
                ],

                '11' => [
                    'description'       => 'Presentiamo la giacca imbottita con cappuccio OmniHeat da uomo, la soluzione ideale per rimanere al caldo e alla moda durante le stagioni più fredde. Questa giacca è realizzata con resistenza e calore in mente, garantendo che diventi il tuo compagno fidato. Il design con cappuccio non solo aggiunge un tocco di stile, ma offre anche calore aggiuntivo, proteggendoti dai venti freddi e dal maltempo. Le maniche lunghe offrono una copertura completa, assicurando che tu rimanga comodo dalla spalla al polso. Dotata di tasche interne, questa giacca imbottita offre comodità per trasportare i tuoi oggetti essenziali o mantenere le mani calde. Il materiale isolante sintetico offre calore migliorato, rendendola ideale per affrontare giornate e notti fredde. Realizzata in un resistente guscio e fodera in poliestere, questa giacca è costruita per durare e resistere agli elementi. Disponibile in 5 colori attraenti, puoi scegliere quello che si adatta al tuo stile e alle tue preferenze. Versatile e funzionale, la giacca imbottita con cappuccio OmniHeat da uomo è adatta a varie occasioni, che tu stia andando al lavoro, facendo una gita informale o partecipando a un evento all\'aperto. Sperimenta la perfetta combinazione di stile, comfort e funzionalità con la giacca imbottita con cappuccio OmniHeat da uomo. Eleva il tuo guardaroba invernale e rimani al caldo mentre abbracci la natura. Sconfiggi il freddo con stile e fai una dichiarazione con questo capo essenziale.',
                    'meta-description'  => 'meta descrizione',
                    'meta-keywords'     => 'meta1, meta2, meta3',
                    'meta-title'        => 'Titolo Meta',
                    'name'              => 'Giacca imbottita con cappuccio OmniHeat da uomo-Blu-Verde-L',
                    'short-description' => 'Rimani al caldo e alla moda con la nostra giacca imbottita con cappuccio OmniHeat da uomo. Questa giacca è progettata per offrire calore supremo e presenta tasche interne per maggiore comodità. Il materiale isolante garantisce che tu rimanga comodo in condizioni di freddo. Disponibile in 5 colori attraenti, rendendola una scelta versatile per varie occasioni.',
                ],
            ],

            'product-attribute-values' => [
                '1' => [
                    'description'      => 'Il Berretto di Piuma Artico Cozy è la tua soluzione ideale per rimanere caldo, comodo e stile durante i mesi più freddi. Realizzato con un blend morbido e duraturo di piuma di acrylic, questo berretto è progettato per offrire un fit comodo e stretto. Il design classico lo rende adatto sia per uomini che donne, offrendo un accessorio versatile che si adatta a diversi stili. Se stai uscendo per un giorno casuale in città o stai affrontando la natura, questo berretto aggiunge un tocco di comfort e calore al tuo abbigliamento. Il materiale morbido e respirabile assicura che tu resti caldo senza sacrificare lo stile. Il Berretto di Piuma Artico Cozy non è solo un accessorio; è un\'affermazione di moda invernale. La sua semplicità lo rende facile da abbinare a diversi outfit, rendendolo un capo fondamentale nella tua wardrobe invernale. Ideale come regalo o come trattamento per te stesso, questo berretto è un\'aggiunta pensata per qualsiasi ensemble invernale. È un accessorio versatile che va oltre la funzionalità, aggiungendo un tocco di calore e stile al tuo look. Abbraccia l\'essenza dell\'inverno con il Berretto di Piuma Artico Cozy. Se stai godendoti un giorno casuale fuori o stai affrontando gli elementi, lascia che questo berretto sia il tuo compagno per comfort e stile. Eleva la tua wardrobe invernale con questo accessorio classico che combina facilmente calore con un senso di moda eterno.',
                    'meta-description' => 'meta description',
                    'meta-keywords'    => 'meta1, meta2, meta3',
                    'meta-title'       => 'Meta Title',
                    'name'             => 'Berretto di Piuma Artico Unisex',
                    'sort-description' => 'Abbraccia i giorni freddi in stile con il nostro Berretto di Piuma Artico Cozy. Realizzato con un blend morbido e duraturo di acrylic, questo berretto classico offre calore e versatilità. Adatto sia per uomini che donne, è l\'accessorio ideale per il vestiario casuale o all\'aperto. Eleva la tua wardrobe invernale o regala qualcuno speciale con questo berretto capo essenziale.',
                ],

                '2' => [
                    'description'      => 'Il Fazzoletto di Stile Artico Bliss è più che un accessorio per il tempo freddo; è un\'affermazione di calore, comfort e stile per la stagione invernale. Realizzato con cura da un blend lussuoso di acrylic e lana, questo fazzoletto è progettato per tenerti caldo e stretto anche nelle temperature più fredde. Il testo morbido e soffice non solo fornisce isolamento contro il freddo ma anche aggiunge un tocco di lusso alla tua wardrobe invernale. Il design del Fazzoletto di Stile Artico Bliss è sia stile che versatile, rendendolo un\'aggiunta perfetta a diversi outfit invernali. Se stai vestendo per un\'occasione speciale o aggiungendo un layer chic al tuo look quotidiano, questo fazzoletto si adatta facilmente al tuo stile. La lunghezza extra del fazzoletto offre opzioni di stile personalizzabili. Avvolgielo per aggiungere calore, lascialo fluttuare per un look casuale o sperimenta diversi nodi per esprimere il tuo stile unico. Questa versatilità lo rende un accessorio essenziale per la stagione invernale. Cerchi il regalo perfetto? Il Fazzoletto di Stile Artico Bliss è una scelta ideale. Se stai sorprendendo qualcuno speciale o trattandoti te stesso, questo fazzoletto è un regalo eterno e pratico che sarà apprezzato durante i mesi invernali. Abbraccia l\'inverno con il Fazzoletto di Stile Artico Bliss, dove calore e stile si incontrano in armonia perfetta. Eleva la tua wardrobe invernale con questo accessorio essenziale che non solo ti tiene caldo ma anche aggiunge un tocco di sofisticazione al tuo ensemble invernale.',
                    'meta-description' => 'meta description',
                    'meta-keywords'    => 'meta1, meta2, meta3',
                    'meta-title'       => 'Meta Title',
                    'name'             => 'Fazzoletto di Stile Artico Bliss',
                    'sort-description' => 'Esperienza l\'abbraccio del calore e dello stile con il nostro Fazzoletto di Stile Artico Bliss. Realizzato con un blend lussuoso di acrylic e lana, questo fazzoletto è progettato per tenerti stretto durante i giorni più freddi. Il suo design stile e versatile, combinato con una lunghezza extra, offre opzioni di stile personalizzabili. Eleva la tua wardrobe invernale o regala qualcuno speciale con questo accessorio essenziale invernale.',
                ],

                '3' => [
                    'description'      => 'Presentiamo i Guanti di Inverno Artico Touchscreen – dove calore, stile e connettività si incontrano per migliorare la tua esperienza invernale. Realizzati con un acrylic di alta qualità, questi guanti sono progettati per offrire un calore eccezionale e durata. I polpastrelli touchscreen compatibili ti permettono di restare connesso senza esporre le tue mani al freddo. Rispondi alle chiamate, invia messaggi e naviga i tuoi dispositivi senza problemi, mantenendo le tue mani calde e strette. La fodera isolante aggiunge un livello extra di comodità, rendendo questi guanti la tua scelta ideale per affrontare il freddo invernale. Se stai andando al lavoro, facendo compere o godendoti attività all\'aperto, questi guanti forniscono il calore e la protezione che ti servono. I cuffi elastici assicurano un fit sicuro, impedendo i venti freddi e mantenendo i guanti al loro posto durante le tue attività quotidiane. Il design stile aggiunge un tocco di fascino al tuo ensemble invernale, rendendoli altrettanto funzionali che eleganti. Ideali come regalo o come trattamento per te stesso, i Guanti di Inverno Artico Touchscreen sono un accessorio essenziale per l\'individuo moderno. Saluta l\'inconveniente di dover rimuovere i guanti per utilizzare i tuoi dispositivi e abbraccia la fusione armoniosa di calore, stile e connettività. Resta connesso, resta caldo e resta stile con i Guanti di Inverno Artico Touchscreen – il tuo compagno fido per affrontare la stagione invernale con fiducia.',
                    'meta-description' => 'meta description',
                    'meta-keywords'    => 'meta1, meta2, meta3',
                    'meta-title'       => 'Meta Title',
                    'name'             => 'Guanti di Inverno Artico Touchscreen',
                    'sort-description' => 'Resta connesso e caldo con i nostri Guanti di Inverno Artico Touchscreen. Questi guanti non solo sono realizzati con un acrylic di alta qualità per calore e durata, ma anche presentano un design touchscreen compatibile. Con una fodera isolante, cuffi elastici per un fit sicuro e un aspetto stile, questi guanti sono perfetti per il vestiario quotidiano in condizioni fredde.',
                ],

                '4' => [
                    'description'      => 'Presentiamo i Calzini di Lana Artico Warmth – il tuo compagno essenziale per piedi caldi e comodi durante le stagioni più fredde. Realizzati con un blend premium di lana Merino, acrylic, nylon e spandex, questi calzini sono progettati per offrire un calore e una comodità ineguagliabili. Il blend di lana assicura che i tuoi piedi restino tosti anche nelle temperature più fredde, rendendoli la scelta ideale per avventure invernali o semplicemente per rimanere caldo a casa. Il testo morbido e soffice offre un tocco di lusso contro la pelle. Saluta i piedi freddi e abbraccia il calore morbido dei calzini di lana. Progettati per durata, i calzini presentano un tallone e un dito rinforzati, aggiungendo forza alle aree più indotte. Questo assicura che i tuoi calzini resistano al tempo, offrendo una comodità e un calore che durano tutta la stagione. La natura respirabile del materiale impedisce di surriscaldare, mantenendo i tuoi piedi comodi e asciutti durante il giorno. Se stai andando all\'aperto per una passeggiata invernale o rilassandoti a casa, questi calzini offrono il bilanciamento perfetto di calore e respirabilità. Versatili e stile, questi calzini sono adatti a diverse occasioni. Pariarli con i tuoi stivali preferiti per un look invernale elegante o indossarli a casa per la massima comodità. Eleva la tua wardrobe invernale e priorizza la comodità con i Calzini di Lana Artico Warmth. Tratta i tuoi piedi al lusso che meritano e entra in un mondo di calore che dura tutta la stagione.',
                    'meta-description' => 'meta description',
                    'meta-keywords'    => 'meta1, meta2, meta3',
                    'meta-title'       => 'Meta Title',
                    'name'             => 'Calzini di Lana Artico Warmth',
                    'sort-description' => 'Esperienza il calore e la comodità ineguagliabili dei nostri Calzini di Lana Artico Warmth. Realizzati con un blend premium di lana Merino, acrylic, nylon e spandex, questi calzini offrono la comodità perfetta per il tempo freddo. Con un tallone e un dito rinforzati per durata e un aspetto stile, questi calzini sono perfetti per diverse occasioni.',
                ],

                '5' => [
                    'description'      => 'Presentiamo il Bundle di Accessori Invernali Artico Frost – la tua soluzione ideale per restare caldo, stile e connesso durante i giorni invernali freddi. Questo set curato comprende quattro accessori invernali essenziali per creare un ensemble armonioso. Il fazzoletto lussuoso, intrecciato da un blend di acrylic e lana, non solo aggiunge un livello di calore ma anche un tocco di eleganza al tuo vestiario invernale. Il berretto morbido, realizzato con cura, promette di tenerti caldo mentre aggiunge un tocco di fascino al tuo look. Ma non finisce qui – il nostro bundle include anche guanti touchscreen compatibili. Resta connesso senza sacrificare il calore mentre navighi i tuoi dispositivi con facilità. Se stai rispondendo alle chiamate, inviando messaggi o catturando momenti invernali sul tuo smartphone, questi guanti assicurano la comodità senza compromettere lo stile. I calzini di lana offrono un tocco di lusso contro la pelle. Saluta i piedi freddi e abbraccia il calore morbido dei calzini. Il Bundle di Accessori Invernali Artico Frost non è solo funzionale; è un\'affermazione di moda invernale. Ogni pezzo è progettato non solo per proteggerti dal freddo ma anche per elevare il tuo stile durante la stagione fredda. I materiali scelti per questo bundle priorizzano sia la durata che la comodità, assicurando che tu possa goderti il mondo invernale in stile. Se stai trattandoti o cercando il regalo perfetto, il Bundle di Accessori Invernali Artico Frost è una scelta versatile. Delizia qualcuno speciale durante le feste o eleva la tua wardrobe invernale con questo ensemble stile e funzionale. Abbraccia il freddo con fiducia, sapendo che hai gli accessori perfetti per restare caldo e chic.',
                    'meta-description' => 'meta description',
                    'meta-keywords'    => 'meta1, meta2, meta3',
                    'meta-title'       => 'Meta Title',
                    'name'             => 'Bundle di Accessori Invernali Artico Frost',
                    'sort-description' => 'Abbraccia il freddo con il nostro Bundle di Accessori Invernali Artico Frost. Questo set curato comprende un fazzoletto lussuoso, un berretto morbido, guanti touchscreen compatibili e calzini di lana. Stile e funzionale, questo ensemble è realizzato con materiali di alta qualità, assicurando sia durata che comodità. Eleva la tua wardrobe invernale o delizia qualcuno speciale con questo regalo perfetto.',
                ],

                '6' => [
                    'description'      => 'Presentiamo il Bundle di Accessori Invernali Artico Frost – la tua soluzione ideale per restare caldo, stile e connesso durante i giorni invernali freddi. Questo set curato comprende quattro accessori invernali essenziali per creare un ensemble armonioso. Il fazzoletto lussuoso, intrecciato da un blend di acrylic e lana, non solo aggiunge un livello di calore ma anche un tocco di eleganza al tuo vestiario invernale. Il berretto morbido, realizzato con cura, promette di tenerti caldo mentre aggiunge un tocco di fascino al tuo look. Ma non finisce qui – il nostro bundle include anche guanti touchscreen compatibili. Resta connesso senza sacrificare il calore mentre navighi i tuoi dispositivi con facilità. Se stai rispondendo alle chiamate, inviando messaggi o catturando momenti invernali sul tuo smartphone, questi guanti assicurano la comodità senza compromettere lo stile. I calzini di lana offrono un tocco di lusso contro la pelle. Saluta i piedi freddi e abbraccia il calore morbido dei calzini. Il Bundle di Accessori Invernali Artico Frost non è solo funzionale; è un\'affermazione di moda invernale. Ogni pezzo è progettato non solo per proteggerti dal freddo ma anche per elevare il tuo stile durante la stagione fredda. I materiali scelti per questo bundle priorizzano sia la durata che la comodità, assicurando che tu possa goderti il mondo invernale in stile. Se stai trattandoti o cercando il regalo perfetto, il Bundle di Accessori Invernali Artico Frost è una scelta versatile. Delizia qualcuno speciale durante le feste o eleva la tua wardrobe invernale con questo ensemble stile e funzionale. Abbraccia il freddo con fiducia, sapendo che hai gli accessori perfetti per restare caldo e chic.',
                    'meta-description' => 'meta description',
                    'meta-keywords'    => 'meta1, meta2, meta3',
                    'meta-title'       => 'Meta Title',
                    'name'             => 'Bundle di Accessori Invernali Artico Frost',
                    'sort-description' => 'Abbraccia il freddo con il nostro Bundle di Accessori Invernali Artico Frost. Questo set curato comprende un fazzoletto lussuoso, un berretto morbido, guanti touchscreen compatibili e calzini di lana. Stile e funzionale, questo ensemble è realizzato con materiali di alta qualità, assicurando sia durata che comodità. Eleva la tua wardrobe invernale o delizia qualcuno speciale con questo regalo perfetto.',
                ],

                '7' => [
                    'description'      => 'Presentiamo il Giacca di Piuma OmniHeat Uomo Solid Hooded – la tua soluzione ideale per restare caldo e fashion durante le stagioni più fredde. Questa giacca è realizzata con durata e calore in mente, assicurando che diventi il tuo compagno fido. Il design con cappuccio non solo aggiunge un tocco di stile ma anche un livello di calore aggiuntivo, proteggendoti dalle correnti fredde e dal tempo. Le maniche complete offrono copertura completa, assicurando che tu resti caldo da spalla a polso. Dotata di tasche insert, questa giacca di piuma offre comodità per portare i tuoi essenziali o mantenere le tue mani calde. Il riempimento sintetico isolante offre un calore aumentato, rendendola ideale per affrontare i giorni e le notti fredde. Realizzata con una confezione di polyester resistente e interna, questa giacca è costruita per durare e resistere agli elementi. Disponibile in 5 colori attraenti, puoi scegliere il colore che si adatta meglio al tuo stile e preferenza. Versatile e funzionale, la Giacca di Piuma OmniHeat Uomo Solid Hooded è adatta a diverse occasioni, sia tu stia andando al lavoro, facendo un\'uscita casuale o partecipando a un evento all\'aperto. Esperienza il perfetto blend di stile, comfort e funzionalità con la Giacca di Piuma OmniHeat Uomo Solid Hooded. Eleva la tua wardrobe invernale e resta caldo mentre goditi il mondo all\'aperto. Sconfiggi il freddo in stile e fai una dichiarazione con questo pezzo essenziale.',
                    'meta-description' => 'meta description',
                    'meta-keywords'    => 'meta1, meta2, meta3',
                    'meta-title'       => 'Meta Title',
                    'name'             => 'Giacca di Piuma OmniHeat Uomo Solid Hooded',
                    'sort-description' => 'Resta caldo e stile con la nostra Giacca di Piuma OmniHeat Uomo Solid Hooded. Questa giacca è progettata per offrire calore e funzionalità. Le tasche insert offrono comodità, e il riempimento sintetico isolante assicura che tu resti caldo in condizioni fredde. Disponibile in 5 colori attraenti, è una scelta versatile per diverse occasioni.',
                ],

                '8' => [
                    'description'      => 'Presentiamo la Giacca di Piuma OmniHeat Uomo Solid Hooded – la tua soluzione ideale per restare caldo e fashion durante le stagioni più fredde. Questa giacca è realizzata con durata e calore in mente, assicurando che diventi il tuo compagno fido. Il design con cappuccio non solo aggiunge un tocco di stile ma anche un livello di calore aggiuntivo, proteggendoti dalle correnti fredde e dal tempo. Le maniche complete offrono copertura completa, assicurando che tu resti caldo da spalla a polso. Dotata di tasche insert, questa giacca di piuma offre comodità per portare i tuoi essenziali o mantenere le tue mani calde. Il riempimento sintetico isolante offre un calore aumentato, rendendola ideale per affrontare i giorni e le notti fredde. Realizzata con una confezione di polyester resistente e interna, questa giacca è costruita per durare e resistere agli elementi. Disponibile in 5 colori attraenti, puoi scegliere il colore che si adatta meglio al tuo stile e preferenza. Versatile e funzionale, la Giacca di Piuma OmniHeat Uomo Solid Hooded è adatta a diverse occasioni, sia tu stia andando al lavoro, facendo un\'uscita casuale o partecipando a un evento all\'aperto. Esperienza il perfetto blend di stile, comfort e funzionalità con la Giacca di Piuma OmniHeat Uomo Solid Hooded. Eleva la tua wardrobe invernale e resta caldo mentre goditi il mondo all\'aperto. Sconfiggi il freddo in stile e fai una dichiarazione con questo pezzo essenziale.',
                    'meta-description' => 'meta description',
                    'meta-keywords'    => 'meta1, meta2, meta3',
                    'meta-title'       => 'Meta Title',
                    'name'             => 'Giacca di Piuma OmniHeat Uomo Solid Hooded',
                    'sort-description' => 'Resta caldo e stile con la nostra Giacca di Piuma OmniHeat Uomo Solid Hooded. Questa giacca è progettata per offrire calore e funzionalità. Le tasche insert offrono comodità, e il riempimento sintetico isolante assicura che tu resti caldo in condizioni fredde. Disponibile in 5 colori attraenti, è una scelta versatile per diverse occasioni.',
                ],

                '9' => [
                    'description'      => 'DescIntroducing the OmniHeat Men\'s Solid Hooded Puffer Jacket, your go-to solution for staying warm and fashionable during colder seasons. This jacket is crafted with durability and warmth in mind, ensuring it becomes your trusted companion. The hooded design not only adds a touch of style but also provides additional warmth, shielding you from cold winds and weather. The full sleeves offer complete coverage, ensuring you stay cozy from shoulder to wrist. Equipped with insert pockets, this puffer jacket provides convenience for carrying your essentials or keeping your hands warm. The insulated synthetic filling offers enhanced warmth, making it ideal for battling chilly days and nights. Made from a durable polyester shell and lining, this jacket is built to last and endure the elements. Available in 5 attractive colors, you can choose the one that suits your style and preference. Versatile and functional, the OmniHeat Men\'s Solid Hooded Puffer Jacket is suitable for various occasions, whether you\'re heading to work, going for a casual outing, or attending an outdoor event. Experience the perfect blend of style, comfort, and functionality with OmniHeat Men\'s Solid Hooded Puffer Jacket. Elevate your winter wardrobe and stay snug while embracing the outdoors. Beat the cold in style and make a statement with this essential piece.',
                    'meta-description' => 'meta description',
                    'meta-keywords'    => 'meta1, meta2, meta3',
                    'meta-title'       => 'Meta Title',
                    'name'             => 'Giacca di Piuma OmniHeat Uomo Solid Hooded',
                    'sort-description' => 'Resta caldo e stile con la nostra Giacca di Piuma OmniHeat Uomo Solid Hooded. Questa giacca è progettata per offrire calore e funzionalità. Le tasche insert offrono comodità, e il riempimento sintetico isolante assicura che tu resti caldo in condizioni fredde. Disponibile in 5 colori attraenti, è una scelta versatile per diverse occasioni.',
                ],

                '10' => [
                    'description'      => 'Presentiamo la Giacca di Piuma OmniHeat Uomo Solid Hooded – la tua soluzione ideale per restare caldo e fashion durante le stagioni più fredde. Questa giacca è realizzata con durata e calore in mente, assicurando che diventi il tuo compagno fido. Il design con cappuccio non solo aggiunge un tocco di stile ma anche un livello di calore aggiuntivo, proteggendoti dalle correnti fredde e dal tempo. Le maniche complete offrono copertura completa, assicurando che tu resti caldo da spalla a polso. Dotata di tasche insert, questa giacca di piuma offre comodità per portare i tuoi essenziali o mantenere le tue mani calde. Il riempimento sintetico isolante offre un calore aumentato, rendendola ideale per affrontare i giorni e le notti fredde. Realizzata con una confezione di polyester resistente e interna, questa giacca è costruita per durare e resistere agli elementi. Disponibile in 5 colori attraenti, puoi scegliere il colore che si adatta meglio al tuo stile e preferenza. Versatile e funzionale, la Giacca di Piuma OmniHeat Uomo Solid Hooded è adatta a diverse occasioni, sia tu stia andando al lavoro, facendo un\'uscita casuale o partecipando a un evento all\'aperto. Esperienza il perfetto blend di stile, comfort e funzionalità con la Giacca di Piuma OmniHeat Uomo Solid Hooded. Eleva la tua wardrobe invernale e resta caldo mentre goditi il mondo all\'aperto. Sconfiggi il freddo in stile e fai una dichiarazione con questo pezzo essenziale.',
                    'meta-description' => 'meta description',
                    'meta-keywords'    => 'meta1, meta2, meta3',
                    'meta-title'       => 'Meta Title',
                    'name'             => 'Giacca di Piuma OmniHeat Uomo Solid Hooded',
                    'sort-description' => 'Resta caldo e stile con la nostra Giacca di Piuma OmniHeat Uomo Solid Hooded. Questa giacca è progettata per offrire calore e funzionalità. Le tasche insert offrono comodità, e il riempimento sintetico isolante assicura che tu resti caldo in condizioni fredde. Disponibile in 5 colori attraenti, è una scelta versatile per diverse occasioni.',
                ],

                '11' => [
                    'description'      => 'Presentiamo la giacca OmniHeat con cappuccio per uomo, la soluzione ideale per rimanere al caldo e alla moda durante le stagioni più fredde. Questa giacca è realizzata con resistenza e calore in mente, garantendo che diventi il tuo compagno di fiducia. Il design con cappuccio non solo aggiunge un tocco di stile, ma offre anche calore aggiuntivo, proteggendoti dai venti freddi e dal maltempo. Le maniche lunghe offrono una copertura completa, assicurando che tu rimanga comodo dalla spalla al polso. Dotata di tasche inseribili, questa giacca piumino offre comodità per trasportare gli essenziali o mantenere le mani calde. Il riempimento sintetico isolante offre calore extra, rendendola ideale per affrontare giornate e notti fredde. Realizzata con un resistente guscio e fodera in poliestere, questa giacca è costruita per durare e resistere agli agenti atmosferici. Disponibile in 5 colori attraenti, puoi scegliere quello che si adatta al tuo stile e alle tue preferenze. Versatile e funzionale, la giacca OmniHeat con cappuccio per uomo è adatta a diverse occasioni, che tu stia andando al lavoro, facendo una gita informale o partecipando a un evento all\'aperto. Sperimenta la perfetta combinazione di stile, comfort e funzionalità con la giacca OmniHeat con cappuccio per uomo. Eleva il tuo guardaroba invernale e rimani al caldo mentre ti godi il ​​freddo. Sconfiggi il freddo con stile e fai una dichiarazione con questo capo essenziale.',
                    'meta-title'       => 'Titolo Meta\',tion',
                    'name'             => 'OmniHeat Men\'s Solid Hooded Puffer Jacket-Blue-Green-L',
                    'sort-description' => 'Rimani al caldo e alla moda con la nostra giacca OmniHeat con cappuccio per uomo. Questa giacca è progettata per offrire calore estremo e dispone di tasche inseribili per una maggiore comodità. Il materiale isolante assicura che tu rimanga comodo in condizioni di freddo. Disponibile in 5 colori attraenti, rendendola una scelta versatile per varie occasioni.',
                ],
            ],

            'product-bundle-option-translations' => [
                '1' => [
                    'label' => 'Opzione pacchetto 1',
                ],

                '2' => [
                    'label' => 'Opzione pacchetto 1',
                ],

                '3' => [
                    'label' => 'Opzione pacchetto 2',
                ],

                '4' => [
                    'label' => 'Opzione pacchetto 2',
                ],
            ],
        ],
    ],

    'installer' => [
        'index' => [
            'create-administrator' => [
                'admin'            => 'Amministratore',
                'bagisto'          => 'Bagisto',
                'confirm-password' => 'Conferma Password',
                'download-sample'  => 'Scarica Esempio',
                'email'            => 'Email',
                'email-address'    => 'admin@example.com',
                'password'         => 'Password',
                'sample-products'  => 'Prodotti di Esempio',
                'title'            => 'Crea Amministratore',
            ],

            'environment-configuration' => [
                'algerian-dinar'              => 'Dinaro algerino (DZD)',
                'allowed-currencies'          => 'Valute consentite',
                'allowed-locales'             => 'Lingue consentite',
                'application-name'            => 'Nome dell\'applicazione',
                'argentine-peso'              => 'Peso argentino (ARS)',
                'australian-dollar'           => 'Dollaro australiano (AUD)',
                'bagisto'                     => 'Bagisto',
                'bangladeshi-taka'            => 'Taka bangladese (BDT)',
                'brazilian-real'              => 'Real brasiliano (BRL)',
                'british-pound-sterling'      => 'Sterlina britannica (GBP)',
                'canadian-dollar'             => 'Dollaro canadese (CAD)',
                'cfa-franc-bceao'             => 'Franco CFA BCEAO (XOF)',
                'cfa-franc-beac'              => 'Franco CFA BEAC (XAF)',
                'chilean-peso'                => 'Peso cileno (CLP)',
                'chinese-yuan'                => 'Yuan cinese (CNY)',
                'colombian-peso'              => 'Peso colombiano (COP)',
                'czech-koruna'                => 'Corona ceca (CZK)',
                'danish-krone'                => 'Corona danese (DKK)',
                'database-connection'         => 'Connessione al database',
                'database-hostname'           => 'Nome host del database',
                'database-name'               => 'Nome del database',
                'database-password'           => 'Password del database',
                'database-port'               => 'Porta del database',
                'database-prefix'             => 'Prefisso del database',
                'database-username'           => 'Nome utente del database',
                'default-currency'            => 'Valuta predefinita',
                'default-locale'              => 'Lingua predefinita',
                'default-timezone'            => 'Fuso orario predefinito',
                'default-url'                 => 'URL predefinito',
                'default-url-link'            => 'https://localhost',
                'egyptian-pound'              => 'Sterlina egiziana (EGP)',
                'euro'                        => 'Euro (EUR)',
                'fijian-dollar'               => 'Dollaro delle Figi (FJD)',
                'hong-kong-dollar'            => 'Dollaro di Hong Kong (HKD)',
                'hungarian-forint'            => 'Fiorino ungherese (HUF)',
                'indian-rupee'                => 'Rupia indiana (INR)',
                'indonesian-rupiah'           => 'Rupia indonesiana (IDR)',
                'israeli-new-shekel'          => 'Nuovo siclo israeliano (ILS)',
                'japanese-yen'                => 'Yen giapponese (JPY)',
                'jordanian-dinar'             => 'Dinaro giordano (JOD)',
                'kazakhstani-tenge'           => 'Tenge kazako (KZT)',
                'kuwaiti-dinar'               => 'Dinaro kuwaitiano (KWD)',
                'lebanese-pound'              => 'Sterlina libanese (LBP)',
                'libyan-dinar'                => 'Dinaro libico (LYD)',
                'malaysian-ringgit'           => 'Ringgit malese (MYR)',
                'mauritian-rupee'             => 'Rupia mauriziana (MUR)',
                'mexican-peso'                => 'Peso messicano (MXN)',
                'moroccan-dirham'             => 'Dirham marocchino (MAD)',
                'mysql'                       => 'Mysql',
                'nepalese-rupee'              => 'Rupia nepalese (NPR)',
                'new-taiwan-dollar'           => 'Nuovo dollaro taiwanese (TWD)',
                'new-zealand-dollar'          => 'Dollaro neozelandese (NZD)',
                'nigerian-naira'              => 'Naira nigeriana (NGN)',
                'norwegian-krone'             => 'Corona norvegese (NOK)',
                'omani-rial'                  => 'Rial omanita (OMR)',
                'pakistani-rupee'             => 'Rupia pakistana (PKR)',
                'panamanian-balboa'           => 'Balboa panamense (PAB)',
                'paraguayan-guarani'          => 'Guaraní paraguayano (PYG)',
                'peruvian-nuevo-sol'          => 'Nuevo sol peruviano (PEN)',
                'pgsql'                       => 'pgSQL',
                'philippine-peso'             => 'Peso filippino (PHP)',
                'polish-zloty'                => 'Złoty polacco (PLN)',
                'qatari-rial'                 => 'Rial qatariota (QAR)',
                'romanian-leu'                => 'Leu rumeno (RON)',
                'russian-ruble'               => 'Rublo russo (RUB)',
                'saudi-riyal'                 => 'Riyal saudita (SAR)',
                'select-timezone'             => 'Seleziona fuso orario',
                'singapore-dollar'            => 'Dollaro di Singapore (SGD)',
                'south-african-rand'          => 'Rand sudafricano (ZAR)',
                'south-korean-won'            => 'Won sudcoreano (KRW)',
                'sqlsrv'                      => 'SQLSRV',
                'sri-lankan-rupee'            => 'Rupia dello Sri Lanka (LKR)',
                'swedish-krona'               => 'Corona svedese (SEK)',
                'swiss-franc'                 => 'Franco svizzero (CHF)',
                'thai-baht'                   => 'Baht thailandese (THB)',
                'title'                       => 'Configurazione del negozio',
                'tunisian-dinar'              => 'Dinaro tunisino (TND)',
                'turkish-lira'                => 'Lira turca (TRY)',
                'ukrainian-hryvnia'           => 'Grivnia ucraina (UAH)',
                'united-arab-emirates-dirham' => 'Dirham degli Emirati Arabi Uniti (AED)',
                'united-states-dollar'        => 'Dollaro statunitense (USD)',
                'uzbekistani-som'             => 'Som uzbeko (UZS)',
                'venezuelan-bolívar'          => 'Bolívar venezuelano (VEF)',
                'vietnamese-dong'             => 'Dong vietnamita (VND)',
                'warning-message'             => 'Attenzione! Le impostazioni della lingua di sistema predefinita e della valuta predefinita sono permanenti e non possono essere modificate una volta impostate.',
                'zambian-kwacha'              => 'Kwacha zambiano (ZMW)',
            ],

            'sample-products' => [
                'download-sample' => 'scarica campione',
                'no'              => 'No',
                'sample-products' => 'Prodotti campione',
                'title'           => 'Prodotti campione',
                'yes'             => 'Sì',
            ],

            'installation-processing' => [
                'bagisto'          => 'Installazione Bagisto',
                'bagisto-info'     => 'Creazione delle tabelle del Database, questo potrebbe richiedere qualche momento',
                'title'            => 'Installazione',
            ],

            'installation-completed' => [
                'admin-panel'                => 'Pannello di Amministrazione',
                'bagisto-forums'             => 'Forum di Bagisto',
                'customer-panel'             => 'Pannello del Cliente',
                'explore-bagisto-extensions' => 'Esplora le Estensioni di Bagisto',
                'title'                      => 'Installazione Completata',
                'title-info'                 => 'Bagisto è stato installato con successo sul tuo sistema.',
            ],

            'ready-for-installation' => [
                'create-databsae-table'   => 'Crea la tabella del database',
                'install'                 => 'Installazione',
                'install-info'            => 'Bagisto per l\'installazione',
                'install-info-button'     => 'Clicca il pulsante sottostante per',
                'populate-database-table' => 'Popola le tabelle del database',
                'start-installation'      => 'Avvia l\'installazione',
                'title'                   => 'Pronto per l\'installazione',
            ],

            'start' => [
                'locale'        => 'Località',
                'main'          => 'Inizio',
                'select-locale' => 'Seleziona la località',
                'title'         => 'La tua installazione di Bagisto',
                'welcome-title' => 'Benvenuto in Bagisto',
            ],

            'server-requirements' => [
                'calendar'    => 'Calendario',
                'ctype'       => 'cType',
                'curl'        => 'cURL',
                'dom'         => 'dom',
                'fileinfo'    => 'fileInfo',
                'filter'      => 'Filtro',
                'gd'          => 'GD',
                'hash'        => 'Hash',
                'intl'        => 'intl',
                'json'        => 'JSON',
                'mbstring'    => 'mbstring',
                'openssl'     => 'openssl',
                'pcre'        => 'pcre',
                'pdo'         => 'pdo',
                'php'         => 'PHP',
                'php-version' => '8.1 o superiore',
                'session'     => 'sessione',
                'title'       => 'Requisiti del server',
                'tokenizer'   => 'tokenizer',
                'xml'         => 'XML',
            ],

            'arabic'                   => 'Arabo',
            'back'                     => 'Indietro',
            'bagisto'                  => 'Bagisto',
            'bagisto-info'             => 'Un progetto della comunità di',
            'bagisto-logo'             => 'Logo Bagisto',
            'bengali'                  => 'Bengalese',
            'chinese'                  => 'Cinese',
            'continue'                 => 'Continua',
            'dutch'                    => 'Olandese',
            'english'                  => 'Inglese',
            'french'                   => 'Francese',
            'german'                   => 'Tedesco',
            'hebrew'                   => 'Ebraico',
            'hindi'                    => 'Hindi',
            'installation-description' => 'L\'installazione di Bagisto generalmente prevede diversi passaggi. Ecco una panoramica generale del processo di installazione per Bagisto',
            'installation-info'        => 'Siamo felici di vederti qui!',
            'installation-title'       => 'Benvenuti all\'installazione',
            'italian'                  => 'Italiano',
            'japanese'                 => 'Giapponese',
            'persian'                  => 'Persiano',
            'polish'                   => 'Polacco',
            'portuguese'               => 'Portoghese brasiliano',
            'russian'                  => 'Russo',
            'sinhala'                  => 'Singalese',
            'spanish'                  => 'Spagnolo',
            'title'                    => 'Installazione di Bagisto',
            'turkish'                  => 'Turco',
            'ukrainian'                => 'Ucraino',
            'webkul'                   => 'Webkul',
        ],
    ],
];
