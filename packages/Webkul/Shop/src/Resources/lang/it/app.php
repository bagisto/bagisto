<?php

return [
    'customers' => [
        'forgot-password' => [
            'already-sent'         => 'E-mail di reimpostazione password già inviata.',
            'back'                 => 'Torna al Login ?',
            'bagisto'              => 'Bagisto',
            'email'                => 'Email',
            'email-not-exist'      => 'Non possiamo trovare un utente con quell\'indirizzo email.',
            'footer'               => '© Copyright 2010 - :current_year, Webkul Software (Registrato in India). Tutti i diritti riservati.',
            'forgot-password-text' => 'Se hai dimenticato la tua password, recuperala inserendo il tuo indirizzo email.',
            'page-title'           => 'Hai dimenticato la tua password ?',
            'reset-link-sent'      => 'Abbiamo inviato il link per il ripristino della password al tuo indirizzo email.',
            'sign-in-button'       => 'Accedi',
            'submit'               => 'Reimposta la Password',
            'title'                => 'Recupera la Password',
        ],

        'reset-password' => [
            'back-link-title'  => 'Torna al Login',
            'bagisto'          => 'Bagisto',
            'confirm-password' => 'Conferma Password',
            'email'            => 'Email Registrata',
            'footer'           => '© Copyright 2010 - :current_year, Webkul Software (Registrato in India). Tutti i diritti riservati.',
            'password'         => 'Password',
            'submit-btn-title' => 'Reimposta la Password',
            'title'            => 'Reimposta la Password',
        ],

        'login-form' => [
            'bagisto'             => 'Bagisto',
            'button-title'        => 'Accedi',
            'create-your-account' => 'Crea il tuo account',
            'email'               => 'Email',
            'footer'              => '© Copyright 2010 - :current_year, Webkul Software (Registrato in India). Tutti i diritti riservati.',
            'forgot-pass'         => 'Hai dimenticato la password?',
            'form-login-text'     => 'Se hai un account, accedi con il tuo indirizzo email.',
            'invalid-credentials' => 'Controlla le tue credenziali e riprova.',
            'new-customer'        => 'Nuovo cliente?',
            'not-activated'       => 'La tua attivazione richiede l’approvazione dell’amministratore',
            'page-title'          => 'Accesso Cliente',
            'password'            => 'Password',
            'show-password'       => 'Mostra Password',
            'title'               => 'Accedi',
            'verify-first'        => 'Verifica prima il tuo account email.',
        ],

        'signup-form' => [
            'account-exists'              => 'Hai già un account ?',
            'bagisto'                     => 'Bagisto',
            'button-title'                => 'Registrati',
            'confirm-pass'                => 'Conferma Password',
            'email'                       => 'Email',
            'first-name'                  => 'Nome',
            'footer'                      => '© Copyright 2010 - :current_year, Webkul Software (Registrato in India). Tutti i diritti riservati.',
            'form-signup-text'            => 'Se sei nuovo nel nostro negozio, siamo felici di averti come membro.',
            'last-name'                   => 'Cognome',
            'page-title'                  => 'Diventa Utente',
            'password'                    => 'Password',
            'sign-in-button'              => 'Accedi',
            'subscribe-to-newsletter'     => 'Iscriviti alla newsletter',
            'success'                     => 'Account creato con successo.',
            'success-verify'              => 'Account creato con successo, è stato inviato un’email di verifica.',
            'success-verify-email-unsent' => 'Account creato con successo, ma email di verifica non inviata.',
            'verification-not-sent'       => 'Errore! Problema nell’invio dell’email di verifica, riprova più tardi.',
            'verification-sent'           => 'Email di verifica inviata',
            'verified'                    => 'Il tuo account è stato verificato, prova ad effettuare il login ora.',
            'verify-failed'               => 'Non riusciamo a verificare il tuo account email.',
        ],

        'account' => [
            'home' => 'Home',

            'profile' => [
                'index' => [
                    'delete'         => 'Elimina',
                    'delete-failed'  => 'Errore durante l\'eliminazione del cliente.',
                    'delete-profile' => 'Elimina Profilo',
                    'delete-success' => 'Cliente eliminato con successo',
                    'dob'            => 'Data di nascita',
                    'edit'           => 'Modifica',
                    'edit-success'   => 'Profilo aggiornato con successo',
                    'email'          => 'Email',
                    'enter-password' => 'Inserisci la tua password',
                    'first-name'     => 'Nome',
                    'gender'         => 'Genere',
                    'last-name'      => 'Cognome',
                    'order-pending'  => 'Impossibile eliminare l\'account cliente perché alcuni ordini sono in sospeso o in stato di elaborazione.',
                    'title'          => 'Profilo',
                    'unmatched'      => 'La vecchia password non corrisponde.',
                    'wrong-password' => 'Password errata!',
                ],

                'edit' => [
                    'confirm-password'        => 'Conferma Password',
                    'current-password'        => 'Password Attuale',
                    'dob'                     => 'Data di nascita',
                    'edit'                    => 'Modifica',
                    'edit-profile'            => 'Modifica Profilo',
                    'email'                   => 'Email',
                    'female'                  => 'Femmina',
                    'first-name'              => 'Nome',
                    'gender'                  => 'Genere',
                    'last-name'               => 'Cognome',
                    'male'                    => 'Maschio',
                    'new-password'            => 'Nuova Password',
                    'other'                   => 'Altro',
                    'phone'                   => 'Telefono',
                    'save'                    => 'Salva',
                    'subscribe-to-newsletter' => 'Iscriviti alla newsletter',
                ],
            ],

            'addresses' => [
                'index' => [
                    'add-address'      => 'Aggiungi Indirizzo',
                    'create-success'   => 'L\'indirizzo è stato aggiunto con successo.',
                    'default-address'  => 'Indirizzo Predefinito',
                    'default-delete'   => 'L\'indirizzo predefinito non può essere modificato.',
                    'delete'           => 'Elimina',
                    'delete-success'   => 'Indirizzo eliminato con successo',
                    'edit'             => 'Modifica',
                    'edit-success'     => 'Indirizzo aggiornato con successo.',
                    'empty-address'    => 'Non hai ancora aggiunto un indirizzo al tuo account.',
                    'security-warning' => 'Attività sospetta rilevata!!!',
                    'set-as-default'   => 'Imposta come Predefinito',
                    'title'            => 'Indirizzo',
                    'update-success'   => 'L\'indirizzo è stato aggiornato con successo.',
                ],

                'create' => [
                    'add-address'    => 'Aggiungi Indirizzo',
                    'city'           => 'Città',
                    'company-name'   => 'Nome Azienda',
                    'country'        => 'Paese',
                    'email'          => 'Email',
                    'first-name'     => 'Nome',
                    'last-name'      => 'Cognome',
                    'phone'          => 'Telefono',
                    'post-code'      => 'Codice Postale',
                    'save'           => 'Salva',
                    'select-country' => 'Seleziona Paese',
                    'set-as-default' => 'Imposta come Predefinito',
                    'state'          => 'Stato',
                    'street-address' => 'Indirizzo',
                    'title'          => 'Indirizzo',
                    'vat-id'         => 'Partita IVA',
                ],

                'edit' => [
                    'city'           => 'Città',
                    'company-name'   => 'Nome Azienda',
                    'country'        => 'Paese',
                    'edit'           => 'Modifica',
                    'email'          => 'Email',
                    'first-name'     => 'Nome',
                    'last-name'      => 'Cognome',
                    'phone'          => 'Telefono',
                    'post-code'      => 'Codice Postale',
                    'select-country' => 'Seleziona Paese',
                    'state'          => 'Stato',
                    'street-address' => 'Indirizzo',
                    'title'          => 'Indirizzo',
                    'update-btn'     => 'Aggiorna',
                    'vat-id'         => 'Partita IVA',
                ],
            ],

            'orders' => [
                'action'      => 'Azione',
                'action-view' => 'Visualizza',
                'empty-order' => 'Non hai ancora ordinato alcun prodotto',
                'order'       => 'Ordine',
                'order-date'  => 'Data dell\'ordine',
                'order-id'    => 'ID ordine',
                'subtotal'    => 'Subtotale',
                'title'       => 'Ordini',
                'total'       => 'Totale',

                'status' => [
                    'title' => 'Stato',

                    'options' => [
                        'canceled'        => 'Annullato',
                        'closed'          => 'Chiuso',
                        'completed'       => 'Completato',
                        'fraud'           => 'Frode',
                        'pending'         => 'In Sospeso',
                        'pending-payment' => 'Pagamento in Sospeso',
                        'processing'      => 'In Elaborazione',
                    ],
                ],

                'view' => [
                    'billing-address'      => 'Indirizzo di fatturazione',
                    'cancel-btn-title'     => 'Annulla',
                    'cancel-confirm-msg'   => 'Sei sicuro di voler annullare questo ordine?',
                    'cancel-error'         => 'Impossibile annullare il tuo ordine.',
                    'cancel-success'       => 'Il tuo ordine è stato annullato',
                    'contact'              => 'Contatto',
                    'item-invoiced'        => 'Articolo fatturato',
                    'item-refunded'        => 'Articolo rimborsato',
                    'item-shipped'         => 'Articolo spedito',
                    'item-ordered'         => 'Articolo ordinato',
                    'order-id'             => 'ID ordine',
                    'page-title'           => 'Ordine #:order_id',
                    'payment-method'       => 'Metodo di pagamento',
                    'reorder-btn-title'    => 'Riordina',
                    'shipping-address'     => 'Indirizzo di spedizione',
                    'shipping-method'      => 'Metodo di spedizione',
                    'shipping-and-payment' => 'Dettagli di spedizione e pagamento',
                    'status'               => 'Stato',
                    'title'                => 'Visualizza',
                    'total'                => 'Totale',

                    'information' => [
                        'discount'                   => 'Sconto',
                        'excl-tax'                   => 'Escl. IVA:',
                        'grand-total'                => 'Totale generale',
                        'info'                       => 'Informazioni',
                        'item-canceled'              => 'Annullato (:qty_canceled)',
                        'item-refunded'              => 'Rimborsato (:qty_refunded)',
                        'invoiced-item'              => 'Fatturato (:qty_invoiced)',
                        'item-shipped'               => 'spedito (:qty_shipped)',
                        'item-status'                => 'Stato articolo',
                        'ordered-item'               => 'Ordinato (:qty_ordered)',
                        'placed-on'                  => 'Effettuato il',
                        'price'                      => 'Prezzo',
                        'product-name'               => 'Nome',
                        'shipping-handling'          => 'Spedizione e gestione',
                        'shipping-handling-excl-tax' => 'Spedizione e gestione (Escl. IVA)',
                        'shipping-handling-incl-tax' => 'Spedizione e gestione (Incl. IVA)',
                        'sku'                        => 'SKU',
                        'subtotal'                   => 'Subtotale',
                        'subtotal-excl-tax'          => 'Subtotale (Escl. IVA)',
                        'subtotal-incl-tax'          => 'Subtotale (Incl. IVA)',
                        'order-summary'              => 'Riepilogo ordine',
                        'tax'                        => 'IVA',
                        'tax-amount'                 => 'Importo IVA',
                        'tax-percent'                => 'Percentuale IVA',
                        'total-due'                  => 'Totale dovuto',
                        'total-paid'                 => 'Totale pagato',
                        'total-refunded'             => 'Totale rimborsato',
                    ],

                    'invoices' => [
                        'discount'                   => 'Sconto',
                        'excl-tax'                   => 'Escl. IVA:',
                        'grand-total'                => 'Totale generale',
                        'individual-invoice'         => 'Fattura #:invoice_id',
                        'invoices'                   => 'Fatture',
                        'price'                      => 'Prezzo',
                        'print'                      => 'Stampa',
                        'product-name'               => 'Nome',
                        'products-ordered'           => 'Prodotti ordinati',
                        'qty'                        => 'Qtà',
                        'shipping-handling-excl-tax' => 'Spedizione e gestione (Escl. IVA)',
                        'shipping-handling-incl-tax' => 'Spedizione e gestione (Incl. IVA)',
                        'shipping-handling'          => 'Spedizione e gestione',
                        'sku'                        => 'SKU',
                        'subtotal-excl-tax'          => 'Subtotale (Escl. IVA)',
                        'subtotal-incl-tax'          => 'Subtotale (Incl. IVA)',
                        'subtotal'                   => 'Subtotale',
                        'tax'                        => 'IVA',
                        'tax-amount'                 => 'Importo IVA',
                    ],

                    'shipments' => [
                        'individual-shipment' => 'Spedizione #:shipment_id',
                        'product-name'        => 'Nome',
                        'qty'                 => 'Qtà',
                        'shipments'           => 'Spedizioni',
                        'sku'                 => 'SKU',
                        'subtotal'            => 'Subtotale',
                        'tracking-number'     => 'Numero di tracciamento',
                    ],

                    'refunds' => [
                        'adjustment-fee'             => 'Commissione di aggiustamento',
                        'adjustment-refund'          => 'Rimborso di aggiustamento',
                        'discount'                   => 'Sconto',
                        'grand-total'                => 'Totale generale',
                        'individual-refund'          => 'Rimborso #:refund_id',
                        'no-result-found'            => 'Non sono stati trovati risultati.',
                        'order-summary'              => 'Riepilogo ordine',
                        'price'                      => 'Prezzo',
                        'product-name'               => 'Nome',
                        'qty'                        => 'Qtà',
                        'refunds'                    => 'Rimborsi',
                        'shipping-handling'          => 'Spedizione e gestione',
                        'shipping-handling-excl-tax' => 'Spedizione e gestione (Escl. IVA)',
                        'shipping-handling-incl-tax' => 'Spedizione e gestione (Incl. IVA)',
                        'sku'                        => 'SKU',
                        'subtotal'                   => 'Subtotale',
                        'subtotal-excl-tax'          => 'Subtotale (Escl. IVA)',
                        'subtotal-incl-tax'          => 'Subtotale (Incl. IVA)',
                        'tax'                        => 'IVA',
                        'tax-amount'                 => 'Importo IVA',
                    ],
                ],

                'invoice-pdf' => [
                    'bank-details'               => 'Dettagli Bancari',
                    'bill-to'                    => 'Fatturato a',
                    'contact-number'             => 'Numero di Contatto',
                    'contact'                    => 'Contatto',
                    'date'                       => 'Data Fattura',
                    'discount'                   => 'Sconto',
                    'excl-tax'                   => 'Escl. Tasse:',
                    'grand-total'                => 'Totale Generale',
                    'invoice-id'                 => 'ID Fattura',
                    'invoice'                    => 'Fattura',
                    'order-date'                 => 'Data Ordine',
                    'order-id'                   => 'ID Ordine',
                    'payment-method'             => 'Metodo di Pagamento',
                    'payment-terms'              => 'Termini di Pagamento',
                    'price'                      => 'Prezzo',
                    'product-name'               => 'Nome Prodotto',
                    'qty'                        => 'Quantità',
                    'ship-to'                    => 'Spedisci a',
                    'shipping-handling-excl-tax' => 'Spedizione e Gestione (Escl. Tasse)',
                    'shipping-handling-incl-tax' => 'Spedizione e Gestione (Incl. Tasse)',
                    'shipping-handling'          => 'Spedizione e Gestione',
                    'shipping-method'            => 'Metodo di Spedizione',
                    'sku'                        => 'SKU',
                    'subtotal-excl-tax'          => 'Subtotale (Escl. Tasse)',
                    'subtotal-incl-tax'          => 'Subtotale (Incl. Tasse)',
                    'subtotal'                   => 'Subtotale',
                    'tax-amount'                 => 'Importo Imposta',
                    'tax'                        => 'Imposta',
                    'vat-number'                 => 'Numero di Partita IVA',
                ],
            ],

            'reviews' => [
                'empty-review' => 'Non hai ancora recensito alcun prodotto',
                'title'        => 'Recensioni',
            ],

            'downloadable-products' => [
                'available'           => 'Disponibile',
                'completed'           => 'Completato',
                'date'                => 'Data',
                'download-error'      => 'Il link per il download è scaduto.',
                'expired'             => 'Scaduto',
                'empty-product'       => 'Non hai un prodotto da scaricare',
                'name'                => 'Prodotti Scaricabili',
                'orderId'             => 'ID Ordine',
                'pending'             => 'In attesa di',
                'payment-error'       => 'Il pagamento non è stato effettuato per questo download.',
                'records-found'       => 'Record Trovati',
                'remaining-downloads' => 'Download Rimasti',
                'status'              => 'Stato',
                'title'               => 'Titolo',
            ],

            'wishlist' => [
                'color'              => 'Colore',
                'delete-all'         => 'Elimina Tutto',
                'empty'              => 'Nessun prodotto è stato aggiunto alla lista dei desideri.',
                'move-to-cart'       => 'Sposta Nel Carrello',
                'moved'              => 'Articolo spostato con successo nel carrello',
                'moved-success'      => 'Prodotto spostato con successo nel carrello',
                'page-title'         => 'Lista dei Desideri',
                'product-removed'    => 'Il prodotto non è più disponibile poiché è stato rimosso dall\'amministratore',
                'profile'            => 'Profilo',
                'remove'             => 'Rimuovi',
                'remove-all-success' => 'Tutti gli articoli dalla tua lista dei desideri sono stati rimossi',
                'remove-fail'        => 'Impossibile rimuovere l\'articolo dalla lista dei desideri',
                'removed'            => 'Articolo Rimosso Dalla Lista dei Desideri con Successo',
                'see-details'        => 'Vedi Dettagli',
                'success'            => 'Articolo Aggiunto con Successo Alla Lista dei Desideri',
                'title'              => 'Lista dei Desideri',
            ],
        ],
    ],

    'components' => [
        'accordion' => [
            'default-content' => 'Contenuto predefinito',
            'default-header'  => 'Intestazione predefinita',
        ],

        'drawer' => [
            'default-toggle' => 'Attivazione predefinita',
        ],

        'media' => [
            'add-attachments' => 'Aggiungi allegati',
        ],

        'layouts' => [
            'header' => [
                'account'           => 'Account',
                'bagisto'           => 'Bagisto',
                'cart'              => 'Carrello',
                'compare'           => 'Confronta',
                'dropdown-text'     => 'Gestisci carrello, ordini e lista dei desideri',
                'logout'            => 'Esci',
                'no-category-found' => 'Nessuna categoria trovata.',
                'orders'            => 'Ordini',
                'profile'           => 'Profilo',
                'search'            => 'Cerca',
                'search-text'       => 'Cerca prodotti qui',
                'sign-in'           => 'Accedi',
                'sign-up'           => 'Registrati',
                'submit'            => 'Invia',
                'title'             => 'Account',
                'welcome'           => 'Benvenuto',
                'welcome-guest'     => 'Benvenuto Ospite',
                'wishlist'          => 'Lista dei desideri',

                'desktop' => [
                    'top' => [
                        'default-locale' => 'Lingua predefinita',
                    ],
                ],

                'mobile' => [
                    'currencies' => 'Valute',
                    'locales'    => 'Lingue',
                ],
            ],

            'footer' => [
                'about-us'               => 'Chi siamo',
                'contact-us'             => 'Contattaci',
                'currency'               => 'Valuta',
                'customer-service'       => 'Servizio clienti',
                'email'                  => 'Email',
                'footer-content'         => 'Contenuto del piè di pagina',
                'footer-text'            => '© Copyright 2010 - :current_year, Webkul Software (registrato in India). Tutti i diritti riservati.',
                'locale'                 => 'Lingua',
                'newsletter-text'        => 'Iscriviti alla nostra divertente newsletter!',
                'order-return'           => 'Ordini e resi',
                'payment-policy'         => 'Politica di pagamento',
                'privacy-cookies-policy' => 'Politica sulla privacy e sui cookie',
                'shipping-policy'        => 'Politica di spedizione',
                'subscribe'              => 'Iscriviti',
                'subscribe-newsletter'   => 'Iscriviti alla newsletter',
                'subscribe-stay-touch'   => 'Iscriviti per rimanere in contatto.',
                'whats-new'              => 'Novità',
            ],
        ],

        'datagrid' => [
            'toolbar' => [
                'length-of' => ':length di',
                'results'   => ':total Risultati',
                'selected'  => ':total Selezionati',

                'mass-actions' => [
                    'must-select-a-mass-action'        => 'Devi selezionare un\'azione di massa.',
                    'must-select-a-mass-action-option' => 'Devi selezionare un\'opzione di azione di massa.',
                    'no-records-selected'              => 'Nessun record selezionato.',
                    'select-action'                    => 'Seleziona Azione',
                ],

                'search' => [
                    'title' => 'Cerca',
                ],

                'filter' => [
                    'apply-filter' => 'Applica Filtri',
                    'title'        => 'Filtro',

                    'dropdown' => [
                        'select' => 'Seleziona',

                        'searchable' => [
                            'at-least-two-chars' => 'Digita almeno 2 caratteri...',
                            'no-results'         => 'Nessun risultato trovato...',
                        ],
                    ],

                    'custom-filters' => [
                        'clear-all' => 'Cancella Tutto',
                    ],
                ],
            ],

            'table' => [
                'actions'              => 'Azioni',
                'next-page'            => 'Pagina Successiva',
                'no-records-available' => 'Nessun Record Disponibile.',
                'of'                   => 'di :total voci',
                'page-navigation'      => 'Navigazione Pagina',
                'page-number'          => 'Numero Pagina',
                'previous-page'        => 'Pagina Precedente',
                'showing'              => 'Mostrando :firstItem',
                'to'                   => 'a :lastItem',
            ],
        ],

        'modal' => [
            'default-content' => 'Contenuto predefinito',
            'default-header'  => 'Intestazione predefinita',

            'confirm' => [
                'agree-btn'    => 'Concordo',
                'disagree-btn' => 'Non concordo',
                'message'      => 'Sei sicuro di voler eseguire questa azione?',
                'title'        => 'Sei sicuro?',
            ],
        ],

        'products' => [
            'card' => [
                'add-to-cart'            => 'Aggiungi al carrello',
                'add-to-compare'         => 'Aggiungi al confronto',
                'add-to-compare-success' => 'Elemento aggiunto con successo alla lista di confronto.',
                'add-to-wishlist'        => 'Aggiungi alla lista dei desideri',
                'already-in-compare'     => 'L\'elemento è già presente nella lista di confronto.',
                'new'                    => 'Nuovo',
                'review-description'     => 'Sii il primo a recensire questo prodotto',
                'sale'                   => 'Sconto',
            ],

            'carousel' => [
                'next'     => 'Successivo',
                'previous' => 'Precedente',
                'view-all' => 'Visualizza tutto',
            ],

            'ratings' => [
                'title' => 'Valutazioni',
            ],
        ],

        'range-slider' => [
            'max-range' => 'Intervallo massimo',
            'min-range' => 'Intervallo minimo',
            'range'     => 'Intervallo:',
        ],

        'carousel' => [
            'image-slide' => 'Diapositiva immagine',
            'next'        => 'Successivo',
            'previous'    => 'Precedente',
        ],

        'quantity-changer' => [
            'decrease-quantity' => 'Diminuisci quantità',
            'increase-quantity' => 'Aumenta quantità',
        ],
    ],

    'products' => [
        'prices' => [
            'grouped' => [
                'starting-at' => 'A partire da',
            ],

            'configurable' => [
                'as-low-as' => 'A partire da',
            ],
        ],

        'sort-by' => [
            'title'   => 'Ordina per',
        ],

        'view' => [
            'type' => [
                'configurable' => [
                    'select-options'       => 'Seleziona un\'opzione',
                    'select-above-options' => 'Seleziona le opzioni sopra',
                ],

                'bundle' => [
                    'none'         => 'Nessuno',
                    'total-amount' => 'Importo totale',
                ],

                'downloadable' => [
                    'links'   => 'Link',
                    'sample'  => 'Esempio',
                    'samples' => 'Esempi',
                ],

                'grouped' => [
                    'name' => 'Nome',
                ],
            ],

            'gallery' => [
                'product-image'   => 'Immagine del prodotto',
                'thumbnail-image' => 'Immagine miniatura',
            ],

            'reviews' => [
                'attachments'      => 'Allegati',
                'cancel'           => 'Annulla',
                'comment'          => 'Commento',
                'customer-review'  => 'Recensioni dei clienti',
                'empty-review'     => 'Nessuna recensione trovata, sii il primo a recensire questo prodotto',
                'failed-to-upload' => 'Impossibile caricare l\'immagine',
                'load-more'        => 'Carica altro',
                'name'             => 'Nome',
                'rating'           => 'Valutazione',
                'ratings'          => 'Giudizi',
                'submit-review'    => 'Invia recensione',
                'success'          => 'Recensione inviata con successo.',
                'title'            => 'Titolo',
                'translate'        => 'Traduci',
                'translating'      => 'Traduzione in corso...',
                'write-a-review'   => 'Scrivi una recensione',
            ],

            'add-to-cart'            => 'Aggiungi al carrello',
            'add-to-compare'         => 'Prodotto aggiunto al confronto.',
            'add-to-wishlist'        => 'Aggiungi alla lista dei desideri',
            'additional-information' => 'Informazioni aggiuntive',
            'already-in-compare'     => 'Il prodotto è già stato aggiunto al confronto.',
            'buy-now'                => 'Acquista ora',
            'compare'                => 'Confronta',
            'description'            => 'Descrizione',
            'related-product-title'  => 'Prodotti correlati',
            'review'                 => 'Recensioni',
            'tax-inclusive'          => 'Iva inclusa',
            'up-sell-title'          => 'Abbiamo trovato altri prodotti che potrebbero interessarti!',
        ],

        'type' => [
            'abstract' => [
                'offers' => 'Acquista :qty per :price ciascuno e risparmia :discount',
            ],
        ],
    ],

    'categories' => [
        'filters' => [
            'clear-all' => 'Cancella tutto',
            'filter'    => 'Filtro',
            'filters'   => 'Filtri:',
            'sort'      => 'Ordina',
        ],

        'toolbar' => [
            'grid' => 'Griglia',
            'list' => 'Lista',
            'show' => 'Mostra',
        ],

        'view' => [
            'empty'     => 'Nessun prodotto disponibile in questa categoria',
            'load-more' => 'Carica altro',
        ],
    ],

    'search' => [
        'title'   => 'Risultati della ricerca: :query',
        'results' => 'Risultati della ricerca',

        'images' => [
            'index' => [
                'only-images-allowed'  => 'Sono ammesse solo immagini (.jpeg, .jpg, .png, ..).',
                'search'               => 'Cerca',
                'size-limit-error'     => 'Errore di limite di dimensione',
                'something-went-wrong' => 'Qualcosa è andato storto, si prega di riprovare più tardi.',
            ],

            'results' => [
                'analyzed-keywords' => 'Parole chiave analizzate:',
            ],
        ],
    ],

    'compare' => [
        'already-added'      => 'L\'articolo è già stato aggiunto all\'elenco di confronto',
        'delete-all'         => 'Cancella tutto',
        'empty-text'         => 'Non hai articoli nell\'elenco di confronto',
        'item-add-success'   => 'Articolo aggiunto con successo all\'elenco di confronto',
        'product-compare'    => 'Confronto prodotti',
        'remove-all-success' => 'Tutti gli articoli rimossi con successo.',
        'remove-error'       => 'Qualcosa è andato storto, riprova più tardi.',
        'remove-success'     => 'Articolo rimosso con successo.',
        'title'              => 'Confronto prodotti',
    ],

    'checkout' => [
        'success' => [
            'info'          => 'Ti invieremo via email i dettagli del tuo ordine e le informazioni sul tracking',
            'order-id-info' => 'Il tuo numero d\'ordine è #:order_id',
            'thanks'        => 'Grazie per il tuo ordine!',
            'title'         => 'Ordine effettuato con successo',
        ],

        'cart' => [
            'continue-to-checkout'      => 'Continua al pagamento',
            'illegal'                   => 'La quantità non può essere inferiore a uno.',
            'inactive-add'              => 'L\'articolo inattivo non può essere aggiunto al carrello.',
            'inactive'                  => 'L\'articolo è stato disattivato e rimosso dal carrello.',
            'inventory-warning'         => 'La quantità richiesta non è disponibile, riprova più tardi.',
            'item-add-to-cart'          => 'Articolo aggiunto con successo',
            'minimum-order-message'     => 'L\'importo minimo dell\'ordine è',
            'missing-fields'            => 'Mancano alcuni campi obbligatori per questo prodotto.',
            'missing-options'           => 'Mancano opzioni per questo prodotto.',
            'paypal-payment-cancelled'  => 'Il pagamento Paypal è stato annullato.',
            'qty-missing'               => 'Almeno un prodotto deve avere una quantità maggiore di 1.',
            'return-to-shop'            => 'Torna allo shopping',
            'rule-applied'              => 'Regola del carrello applicata',
            'select-hourly-duration'    => 'Seleziona una durata oraria.',
            'success-remove'            => 'Articolo rimosso con successo dal carrello.',
            'suspended-account-message' => 'Il tuo account è stato sospeso.',

            'index' => [
                'bagisto'                  => 'Bagisto',
                'cart'                     => 'Carrello',
                'continue-shopping'        => 'Continua a fare acquisti',
                'empty-product'            => 'Il carrello è vuoto.',
                'excl-tax'                 => 'Escl. IVA:',
                'home'                     => 'Home',
                'items-selected'           => ':count Articoli Selezionati',
                'move-to-wishlist'         => 'Sposta nella lista dei desideri',
                'move-to-wishlist-success' => 'Gli articoli selezionati sono stati spostati con successo nella lista dei desideri.',
                'price'                    => 'Prezzo',
                'product-name'             => 'Nome del prodotto',
                'quantity'                 => 'Quantità',
                'quantity-update'          => 'Quantità aggiornata con successo',
                'remove'                   => 'Rimuovi',
                'remove-selected-success'  => 'Gli articoli selezionati sono stati rimossi con successo dal carrello.',
                'see-details'              => 'Visualizza dettagli',
                'select-all'               => 'Seleziona tutto',
                'select-cart-item'         => 'Seleziona elemento del carrello',
                'tax'                      => 'Imposta',
                'total'                    => 'Totale',
                'update-cart'              => 'Aggiorna carrello',
                'view-cart'                => 'Vedi carrello',

                'cross-sell' => [
                    'title' => 'Maggiori scelte',
                ],
            ],

            'mini-cart' => [
                'continue-to-checkout' => 'Continua al pagamento',
                'empty-cart'           => 'Il tuo carrello è vuoto',
                'excl-tax'             => 'Escl. IVA:',
                'offer-on-orders'      => 'Ottieni fino al 30% di sconto sul tuo primo ordine',
                'remove'               => 'Rimuovi',
                'see-details'          => 'Visualizza dettagli',
                'shopping-cart'        => 'Carrello della spesa',
                'subtotal'             => 'Subtotale',
                'view-cart'            => 'Visualizza carrello',
            ],

            'summary' => [
                'cart-summary'              => 'Riepilogo Carrello',
                'delivery-charges'          => 'Spese di consegna',
                'delivery-charges-excl-tax' => 'Spese di consegna (escl. IVA)',
                'delivery-charges-incl-tax' => 'Spese di consegna (incl. IVA)',
                'discount-amount'           => 'Importo Sconto',
                'grand-total'               => 'Totale',
                'place-order'               => 'Effettua Ordine',
                'proceed-to-checkout'       => 'Procedi al Checkout',
                'sub-total'                 => 'Subtotale',
                'sub-total-excl-tax'        => 'Subtotale (escl. IVA)',
                'sub-total-incl-tax'        => 'Subtotale (incl. IVA)',
                'tax'                       => 'Imposta',

                'estimate-shipping' => [
                    'country'        => 'Paese',
                    'info'           => 'Inserisci la tua destinazione per ottenere una stima di spedizione e imposta.',
                    'postcode'       => 'CAP',
                    'select-country' => 'Seleziona Paese',
                    'select-state'   => 'Seleziona Regione',
                    'state'          => 'Regione',
                    'title'          => 'Calcola Spedizione e Imposta',
                ],
            ],
        ],

        'onepage' => [
            'address' => [
                'add-new'                => 'Aggiungi nuovo indirizzo',
                'add-new-address'        => 'Aggiungi nuovo indirizzo',
                'back'                   => 'Indietro',
                'billing-address'        => 'Indirizzo di fatturazione',
                'check-billing-address'  => 'Indirizzo di fatturazione mancante.',
                'check-shipping-address' => 'Indirizzo di spedizione mancante.',
                'city'                   => 'Città',
                'company-name'           => 'Nome azienda',
                'confirm'                => 'Conferma',
                'country'                => 'Paese',
                'email'                  => 'Email',
                'first-name'             => 'Nome',
                'last-name'              => 'Cognome',
                'postcode'               => 'CAP',
                'proceed'                => 'Procedi',
                'same-as-billing'        => 'Usare lo stesso indirizzo per la spedizione?',
                'save'                   => 'Salva',
                'save-address'           => 'Salva in rubrica',
                'select-country'         => 'Seleziona Paese',
                'select-state'           => 'Seleziona Regione',
                'shipping-address'       => 'Indirizzo di spedizione',
                'state'                  => 'Regione',
                'street-address'         => 'Indirizzo',
                'telephone'              => 'Telefono',
                'title'                  => 'Indirizzo',
            ],

            'index' => [
                'checkout' => 'Pagamento',
                'home'     => 'Home',
            ],

            'payment' => [
                'payment-method' => 'Metodo di pagamento',
            ],

            'shipping' => [
                'shipping-method' => 'Metodo di spedizione',
            ],

            'summary' => [
                'cart-summary'              => 'Riepilogo Carrello',
                'delivery-charges'          => 'Spese di consegna',
                'delivery-charges-excl-tax' => 'Spese di consegna (Escl. IVA)',
                'delivery-charges-incl-tax' => 'Spese di consegna (Incl. IVA)',
                'discount-amount'           => 'Importo Sconto',
                'excl-tax'                  => 'Escl. IVA:',
                'grand-total'               => 'Totale',
                'place-order'               => 'Effettua Ordine',
                'price_&_qty'               => ':price × :qty',
                'processing'                => 'Elaborazione',
                'sub-total'                 => 'Subtotale',
                'sub-total-excl-tax'        => 'Subtotale (Escl. IVA)',
                'sub-total-incl-tax'        => 'Subtotale (Incl. IVA)',
                'tax'                       => 'Imposta',
            ],
        ],

        'coupon' => [
            'already-applied' => 'Codice del coupon già applicato.',
            'applied'         => 'Coupon applicato',
            'apply'           => 'Applica coupon',
            'apply-issue'     => 'Il codice del coupon non può essere applicato.',
            'button-title'    => 'Applica',
            'code'            => 'Codice coupon',
            'discount'        => 'Sconto del coupon',
            'enter-your-code' => 'Inserisci il tuo codice',
            'error'           => 'Qualcosa è andato storto',
            'invalid'         => 'Codice coupon non valido.',
            'remove'          => 'Rimuovi coupon',
            'subtotal'        => 'Subtotale',
            'success-apply'   => 'Codice coupon applicato con successo.',
        ],

        'login' => [
            'email'    => 'Email',
            'password' => 'Password',
            'title'    => 'Accedi',
        ],
    ],

    'home' => [
        'contact' => [
            'about'         => 'Scrivici una nota e ti risponderemo il prima possibile',
            'desc'          => 'Cosa hai in mente?',
            'describe-here' => 'Descrivi qui',
            'email'         => 'Email',
            'message'       => 'Messaggio',
            'name'          => 'Nome',
            'phone-number'  => 'Numero di telefono',
            'submit'        => 'Invia',
            'title'         => 'Contattaci',
        ],

        'index' => [
            'offer'               => 'Ottieni FINO AL 40% DI SCONTO sul tuo primo ordine ACQUISTA ORA',
            'resend-verify-email' => 'Rispedisci l\'email di verifica',
            'verify-email'        => 'Verifica il tuo account email',
        ],

        'thanks-for-contact' => 'Grazie per averci contattato con i tuoi commenti e domande. Ti risponderemo molto presto.',
    ],

    'partials' => [
        'pagination' => [
            'pagination-showing' => 'Visualizzazione da :firstItem a :lastItem di :total voci',
        ],
    ],

    'errors' => [
        'go-to-home' => 'Vai alla Home',

        '404' => [
            'description' => 'Oops! La pagina che stai cercando è in vacanza. Sembra che non siamo riusciti a trovare quello che cercavi.',
            'title'       => '404 Pagina non trovata',
        ],

        '401' => [
            'description' => 'Oops! Sembra che tu non abbia il permesso di accedere a questa pagina. Sembra che ti manchino le credenziali necessarie.',
            'title'       => '401 Non autorizzato',
        ],

        '403' => [
            'description' => 'Oops! Questa pagina è off-limits. Sembra che tu non abbia i permessi necessari per visualizzare questo contenuto.',
            'title'       => '403 Vietato',
        ],

        '500' => [
            'description' => 'Oops! Qualcosa è andato storto. Sembra che stiamo avendo problemi a caricare la pagina che stai cercando.',
            'title'       => '500 Errore interno del server',
        ],

        '503' => [
            'description' => 'Oops! Sembra che siamo temporaneamente offline per manutenzione. Torna tra un po\'.',
            'title'       => '503 Servizio non disponibile',
        ],
    ],

    'layouts' => [
        'address'               => 'Indirizzo',
        'downloadable-products' => 'Prodotti scaricabili',
        'my-account'            => 'Il mio account',
        'orders'                => 'Ordini',
        'profile'               => 'Profilo',
        'reviews'               => 'Recensioni',
        'wishlist'              => 'Lista dei desideri',
    ],

    'subscription' => [
        'already'             => 'Sei già iscritto alla nostra newsletter.',
        'subscribe-success'   => 'Ti sei iscritto con successo alla nostra newsletter.',
        'unsubscribe-success' => 'Ti sei disiscritto con successo dalla nostra newsletter.',
    ],

    'emails' => [
        'dear'   => 'Caro :customer_name',
        'thanks' => 'Se hai bisogno di assistenza, contattaci a <a href=":link" style=":style">:email</a>.<br/>Grazie!',

        'customers' => [
            'registration' => [
                'credentials-description' => 'Il tuo account è stato creato. I dettagli del tuo account sono riportati di seguito:',
                'description'             => 'Il tuo account è stato creato con successo e puoi effettuare il login utilizzando il tuo indirizzo email e le tue credenziali password. Una volta effettuato il login, potrai accedere ad altri servizi, tra cui la revisione degli ordini passati, la lista dei desideri e la modifica delle informazioni del tuo account.',
                'greeting'                => 'Benvenuto e grazie per esserti registrato con noi!',
                'password'                => 'Password',
                'sign-in'                 => 'Accedi',
                'subject'                 => 'Nuova registrazione cliente',
                'username-email'          => 'Nome utente/Email',
            ],

            'forgot-password' => [
                'description'    => 'Stai ricevendo questa email perché abbiamo ricevuto una richiesta di reset password per il tuo account.',
                'greeting'       => 'Password dimenticata!',
                'reset-password' => 'Reimposta password',
                'subject'        => 'Email di reset password',
            ],

            'update-password' => [
                'description' => 'Stai ricevendo questa email perché hai aggiornato la tua password.',
                'greeting'    => 'Password aggiornata!',
                'subject'     => 'Password aggiornata',
            ],

            'verification' => [
                'description'  => 'Clicca il pulsante qui sotto per verificare il tuo indirizzo email.',
                'greeting'     => 'Benvenuto!',
                'subject'      => 'Email di verifica dell\'account',
                'verify-email' => 'Verifica indirizzo email',
            ],

            'commented' => [
                'description' => 'Nota - :note',
                'subject'     => 'Nuovo commento aggiunto',
            ],

            'subscribed' => [
                'description' => 'Congratulazioni e benvenuto nella nostra community di newsletter! Siamo entusiasti di averti a bordo e di tenerti aggiornato con le ultime notizie, tendenze e offerte esclusive.',
                'greeting'    => 'Benvenuto nella nostra newsletter!',
                'subject'     => 'Ti sei iscritto alla nostra newsletter!',
                'unsubscribe' => 'Annulla iscrizione',
            ],
        ],

        'contact-us' => [
            'contact-from'    => 'tramite modulo di contatto del sito web',
            'reply-to-mail'   => 'si prega di rispondere a questa email.',
            'reach-via-phone' => 'In alternativa, puoi contattarci telefonicamente al',
            'inquiry-from'    => 'Richiesta da',
            'to'              => 'Per contattare',
        ],

        'orders' => [
            'created' => [
                'greeting' => 'Grazie per il tuo ordine :order_id effettuato il :created_at',
                'subject'  => 'Nuova conferma ordine',
                'summary'  => 'Riepilogo dell\'ordine',
                'title'    => 'Conferma ordine!',
            ],

            'invoiced' => [
                'greeting' => 'La tua fattura #:invoice_id per l\'ordine :order_id creato il :created_at',
                'subject'  => 'Nuova conferma fattura',
                'summary'  => 'Riepilogo della fattura',
                'title'    => 'Conferma fattura!',
            ],

            'shipped' => [
                'greeting' => 'Il tuo ordine :order_id effettuato il :created_at è stato spedito',
                'subject'  => 'Nuova conferma spedizione',
                'summary'  => 'Riepilogo della spedizione',
                'title'    => 'Ordine spedito!',
            ],

            'refunded' => [
                'greeting' => 'Il rimborso è stato avviato per l\'ordine :order_id effettuato il :created_at',
                'subject'  => 'Nuova conferma rimborso',
                'summary'  => 'Riepilogo del rimborso',
                'title'    => 'Ordine rimborsato!',
            ],

            'canceled' => [
                'greeting' => 'Il tuo ordine :order_id effettuato il :created_at è stato annullato',
                'subject'  => 'Nuova conferma annullamento ordine',
                'summary'  => 'Riepilogo dell\'ordine',
                'title'    => 'Ordine annullato!',
            ],

            'commented' => [
                'subject' => 'Nuovo commento aggiunto',
                'title'   => 'Nuovo commento aggiunto al tuo ordine :order_id effettuato il :created_at',
            ],

            'billing-address'            => 'Indirizzo di fatturazione',
            'carrier'                    => 'Corriere',
            'contact'                    => 'Contatto',
            'discount'                   => 'Sconto',
            'excl-tax'                   => 'Escl. IVA: ',
            'grand-total'                => 'Totale',
            'name'                       => 'Nome',
            'payment'                    => 'Pagamento',
            'price'                      => 'Prezzo',
            'qty'                        => 'Quantità',
            'shipping'                   => 'Spedizione',
            'shipping-address'           => 'Indirizzo di spedizione',
            'shipping-handling'          => 'Spedizione e gestione',
            'shipping-handling-excl-tax' => 'Spedizione e gestione (Escl. IVA)',
            'shipping-handling-incl-tax' => 'Spedizione e gestione (Incl. IVA)',
            'sku'                        => 'SKU',
            'subtotal'                   => 'Subtotale',
            'subtotal-excl-tax'          => 'Subtotale (Escl. IVA)',
            'subtotal-incl-tax'          => 'Subtotale (Incl. IVA)',
            'tax'                        => 'IVA',
            'tracking-number'            => 'Numero di tracciamento: :tracking_number',
        ],
    ],
];
