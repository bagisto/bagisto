<?php

return [
    'configurations' => [
        'settings-title'      => 'Ustawienia',
        'settings-title-info' => 'Ustawienia odnoszą się do konfigurowalnych opcji kontrolujących zachowanie systemu, aplikacji lub urządzenia, dostosowane do preferencji i wymagań użytkownika.',
    ],

    'customers' => [
        'forgot-password' => [
            'title'                => 'Odzyskaj hasło',
            'email'                => 'Email',
            'forgot-password-text' => 'Jeśli zapomniałeś hasła, odzyskaj je, wpisując swój adres e-mail.',
            'submit'               => 'Zresetuj hasło',
            'page-title'           => 'Zapomniałeś hasła?',
            'back'                 => 'Powrót do logowania?',
            'sign-in-button'       => 'Zaloguj się',
            'footer'               => '© Prawa autorskie 2010 - 2022, Webkul Software (zarejestrowane w Indiach). Wszelkie prawa zastrzeżone.',
        ],

        'reset-password' => [
            'title'            => 'Zresetuj hasło',
            'email'            => 'Zarejestrowany adres e-mail',
            'password'         => 'Hasło',
            'confirm-password' => 'Potwierdź hasło',
            'back-link-title'  => 'Powrót do logowania',
            'submit-btn-title' => 'Zresetuj hasło',
            'footer'           => '© Prawa autorskie 2010 - 2022, Webkul Software (zarejestrowane w Indiach). Wszelkie prawa zastrzeżone.',
        ],

        'login-form' => [
            'page-title'          => 'Logowanie klienta',
            'form-login-text'     => 'Jeśli masz konto, zaloguj się za pomocą swojego adresu e-mail.',
            'show-password'       => 'Pokaż hasło',
            'title'               => 'Zaloguj się',
            'email'               => 'Email',
            'password'            => 'Hasło',
            'forgot-pass'         => 'Zapomniałeś hasła?',
            'button-title'        => 'Zaloguj się',
            'new-customer'        => 'Nowy klient?',
            'create-your-account' => 'Stwórz swoje konto',
            'footer'              => '© Prawa autorskie 2010 - 2022, Webkul Software (zarejestrowane w Indiach). Wszelkie prawa zastrzeżone.',
            'invalid-credentials' => 'Sprawdź swoje dane logowania i spróbuj ponownie.',
            'not-activated'       => 'Twoja aktywacja oczekuje na zatwierdzenie administratora',
            'verify-first'        => 'Najpierw zweryfikuj swoje konto e-mail.',
        ],

        'signup-form' => [
            'page-title'                  => 'Zostań użytkownikiem',
            'form-signup-text'            => 'Jeśli jesteś nowym klientem w naszym sklepie, cieszymy się, że jesteś naszym członkiem.',
            'sign-in-button'              => 'Zaloguj się',
            'first-name'                  => 'Imię',
            'last-name'                   => 'Nazwisko',
            'email'                       => 'Email',
            'password'                    => 'Hasło',
            'confirm-pass'                => 'Potwierdź hasło',
            'subscribe-to-newsletter'     => 'Zapisz się na newsletter',
            'button-title'                => 'Zarejestruj',
            'account-exists'              => 'Masz już konto?',
            'footer'                      => '© Prawa autorskie 2010 - 2022, Webkul Software (zarejestrowane w Indiach). Wszelkie prawa zastrzeżone.',
            'success-verify'              => 'Konto zostało pomyślnie utworzone, na Twój adres e-mail została wysłana wiadomość w celu weryfikacji.',
            'success-verify-email-unsent' => 'Konto zostało pomyślnie utworzone, ale wiadomość weryfikacyjna nie została wysłana.',
            'success'                     => 'Konto zostało pomyślnie utworzone.',
            'verified'                    => 'Twoje konto zostało zweryfikowane, spróbuj teraz zalogować się.',
            'verify-failed'               => 'Nie możemy zweryfikować Twojego konta e-mail.',
            'verification-not-sent'       => 'Błąd! Problem z wysłaniem wiadomości weryfikacyjnej, spróbuj ponownie później.',
            'verification-sent'           => 'Wysłano wiadomość weryfikacyjną',
        ],

        'account' => [
            'home'      => 'Strona główna',
            'profile'   => [
                'title'                   => 'Profil',
                'first-name'              => 'Imię',
                'last-name'               => 'Nazwisko',
                'gender'                  => 'Płeć',
                'dob'                     => 'Data urodzenia',
                'email'                   => 'Email',
                'delete-profile'          => 'Usuń profil',
                'edit-profile'            => 'Edytuj profil',
                'edit'                    => 'Edytuj',
                'phone'                   => 'Telefon',
                'current-password'        => 'Obecne hasło',
                'new-password'            => 'Nowe hasło',
                'confirm-password'        => 'Potwierdź hasło',
                'delete-success'          => 'Klient został pomyślnie usunięty',
                'wrong-password'          => 'Błędne hasło!',
                'delete-failed'           => 'Wystąpił błąd podczas usuwania klienta.',
                'order-pending'           => 'Nie można usunąć konta klienta, ponieważ niektóre zamówienia są w trakcie realizacji lub oczekujące na zatwierdzenie.',
                'subscribe-to-newsletter' => 'Zapisz się na newsletter',
                'delete'                  => 'Usuń',
                'enter-password'          => 'Wprowadź swoje hasło',
                'male'                    => 'Mężczyzna',
                'female'                  => 'Kobieta',
                'other'                   => 'Inna',
                'save'                    => 'Zapisz',
            ],

            'addresses' => [
                'title'            => 'Adres',
                'edit'             => 'Edytuj',
                'edit-address'     => 'Edytuj adres',
                'delete'           => 'Usuń',
                'set-as-default'   => 'Ustaw jako domyślny',
                'add-address'      => 'Dodaj adres',
                'company-name'     => 'Nazwa firmy',
                'vat-id'           => 'NIP',
                'address-1'        => 'Adres 1',
                'address-2'        => 'Adres 2',
                'city'             => 'Miasto',
                'state'            => 'Województwo',
                'select-country'   => 'Wybierz kraj',
                'country'          => 'Kraj',
                'default-address'  => 'Domyślny adres',
                'first-name'       => 'Imię',
                'last-name'        => 'Nazwisko',
                'phone'            => 'Telefon',
                'street-address'   => 'Adres ulicy',
                'post-code'        => 'Kod pocztowy',
                'empty-address'    => 'Nie dodałeś jeszcze adresu do swojego konta.',
                'create-success'   => 'Adres został pomyślnie dodany.',
                'edit-success'     => 'Adres został pomyślnie zaktualizowany.',
                'default-delete'   => 'Nie można zmienić domyślnego adresu.',
                'delete-success'   => 'Adres został pomyślnie usunięty',
                'save'             => 'Zapisz',
                'security-warning' => 'Wykryto podejrzaną aktywność!',
            ],

            'orders' => [
                'title'      => 'Zamówienia',
                'order-id'   => 'Identyfikator zamówienia',
                'order'      => 'Zamówienie',
                'order-date' => 'Data zamówienia',
                'total'      => 'Suma',

                'status' => [
                    'title' => 'Status',

                    'options' => [
                        'processing'      => 'Przetwarzanie',
                        'completed'       => 'Zakończono',
                        'canceled'        => 'Anulowano',
                        'closed'          => 'Zamknięto',
                        'pending'         => 'Oczekujące',
                        'pending-payment' => 'Oczekujące na płatność',
                        'fraud'           => 'Oszustwo',
                    ],
                ],

                'action'      => 'Akcja',
                'empty-order' => 'Nie zamówiłeś jeszcze żadnego produktu',

                'view' => [
                    'title'              => 'Zobacz',
                    'page-title'         => 'Zamówienie #:order_id',
                    'total'              => 'Suma',
                    'shipping-address'   => 'Adres dostawy',
                    'billing-address'    => 'Adres rozliczeniowy',
                    'shipping-method'    => 'Metoda dostawy',
                    'payment-method'     => 'Metoda płatności',
                    'cancel-btn-title'   => 'Anuluj',
                    'cancel-confirm-msg' => 'Czy na pewno chcesz anulować to zamówienie?',
                    'cancel-success'     => 'Twoje zamówienie zostało anulowane',
                    'cancel-error'       => 'Nie można anulować zamówienia.',

                    'information' => [
                        'info'              => 'Informacje',
                        'placed-on'         => 'Zamówione dnia',
                        'sku'               => 'SKU',
                        'product-name'      => 'Nazwa',
                        'price'             => 'Cena',
                        'item-status'       => 'Status produktu',
                        'subtotal'          => 'Suma częściowa',
                        'tax-percent'       => 'Procent podatku',
                        'tax-amount'        => 'Kwota podatku',
                        'tax'               => 'Podatek',
                        'grand-total'       => 'Łącznie',
                        'item-ordered'      => 'Zamówione (:qty_ordered)',
                        'item-invoice'      => 'Faktury (:qty_invoiced)',
                        'item-shipped'      => 'Wysłane (:qty_shipped)',
                        'item-canceled'     => 'Anulowane (:qty_canceled)',
                        'item-refunded'     => 'Zwrócone (:qty_refunded)',
                        'shipping-handling' => 'Dostawa i obsługa',
                        'discount'          => 'Rabat',
                        'total-paid'        => 'Łącznie zapłacono',
                        'total-refunded'    => 'Łącznie zwrócono',
                        'total-due'         => 'Łącznie do zapłaty',
                    ],

                    'invoices'  => [
                        'invoices'           => 'Faktury',
                        'individual-invoice' => 'Faktura #:invoice_id',
                        'sku'                => 'SKU',
                        'product-name'       => 'Nazwa',
                        'price'              => 'Cena',
                        'products-ordered'   => 'Zamówione produkty',
                        'qty'                => 'Ilość',
                        'subtotal'           => 'Suma częściowa',
                        'tax-amount'         => 'Kwota podatku',
                        'grand-total'        => 'Łącznie',
                        'shipping-handling'  => 'Dostawa i obsługa',
                        'discount'           => 'Rabat',
                        'tax'                => 'Podatek',
                        'print'              => 'Drukuj',
                    ],

                    'shipments' => [
                        'shipments'           => 'Wysyłki',
                        'tracking-number'     => 'Numer przesyłki',
                        'individual-shipment' => 'Wysyłka #:shipment_id',
                        'sku'                 => 'SKU',
                        'product-name'        => 'Nazwa',
                        'qty'                 => 'Ilość',
                        'subtotal'            => 'Suma częściowa',
                    ],

                    'refunds'  => [
                        'refunds'            => 'Zwroty',
                        'individual-refund'  => 'Zwrot #:refund_id',
                        'sku'                => 'SKU',
                        'product-name'       => 'Nazwa',
                        'price'              => 'Cena',
                        'qty'                => 'Ilość',
                        'tax-amount'         => 'Kwota podatku',
                        'subtotal'           => 'Suma częściowa',
                        'grand-total'        => 'Łącznie',
                        'no-result-found'    => 'Nie znaleziono rekordów.',
                        'shipping-handling'  => 'Dostawa i obsługa',
                        'discount'           => 'Rabat',
                        'tax'                => 'Podatek',
                        'adjustment-refund'  => 'Zwrot korekty',
                        'adjustment-fee'     => 'Opłata za korektę',
                    ],
                ],
            ],

            'reviews'    => [
                'title'        => 'Recenzje',
                'empty-review' => 'Nie napisałeś jeszcze recenzji żadnego produktu',
            ],

            'downloadable-products'  => [
                'name'                => 'Produkty do pobrania',
                'orderId'             => 'Identyfikator zamówienia',
                'title'               => 'Tytuł',
                'date'                => 'Data',
                'status'              => 'Status',
                'remaining-downloads' => 'Pozostałe pobrania',
                'records-found'       => 'Liczba rekordów',
                'empty-product'       => 'Nie masz produktu do pobrania',
                'download-error'      => 'Link do pobrania wygasł.',
                'payment-error'       => 'Nie dokonano płatności za to pobieranie.',
            ],

            'wishlist' => [
                'page-title'         => 'Lista życzeń',
                'title'              => 'Lista życzeń',
                'color'              => 'Kolor',
                'remove'             => 'Usuń',
                'delete-all'         => 'Usuń wszystko',
                'empty'              => 'Na liście życzeń nie dodano jeszcze żadnych produktów.',
                'move-to-cart'       => 'Przenieś do koszyka',
                'profile'            => 'Profil',
                'removed'            => 'Produkt pomyślnie usunięty z listy życzeń',
                'remove-fail'        => 'Produkt nie może zostać usunięty z listy życzeń',
                'moved'              => 'Produkt został pomyślnie przeniesiony do koszyka',
                'product-removed'    => 'Produkt nie jest już dostępny, ponieważ został usunięty przez administratora',
                'remove-all-success' => 'Wszystkie przedmioty z Twojej listy życzeń zostały usunięte',
                'see-details'        => 'Zobacz szczegóły',
            ],
        ],
    ],

    'components' => [
        'layouts' => [
            'header' => [
                'title'         => 'Konto',
                'welcome'       => 'Witaj',
                'welcome-guest' => 'Witaj, Gościu',
                'dropdown-text' => 'Zarządzaj Koszykiem, Zamówieniami i Listą Życzeń',
                'sign-in'       => 'Zaloguj się',
                'sign-up'       => 'Zarejestruj się',
                'account'       => 'Konto',
                'cart'          => 'Koszyk',
                'profile'       => 'Profil',
                'wishlist'      => 'Lista Życzeń',
                'compare'       => 'Porównaj',
                'orders'        => 'Zamówienia',
                'cart'          => 'Koszyk',
                'logout'        => 'Wyloguj się',
                'search-text'   => 'Szukaj produktów tutaj',
                'search'        => 'Szukaj',
            ],

            'footer' => [
                'newsletter-text'        => 'Przygotuj się na nasz zabawny biuletyn!',
                'subscribe-stay-touch'   => 'Zapisz się, aby być na bieżąco.',
                'subscribe-newsletter'   => 'Zapisz się na biuletyn',
                'subscribe'              => 'Zapisz się',
                'footer-text'            => '© Prawa autorskie 2010 - 2023, Webkul Software (zarejestrowane w Indiach). Wszelkie prawa zastrzeżone.',
                'locale'                 => 'Lokalizacja',
                'currency'               => 'Waluta',
                'about-us'               => 'O nas',
                'customer-service'       => 'Obsługa klienta',
                'whats-new'              => 'Co nowego',
                'contact-us'             => 'Skontaktuj się z nami',
                'order-return'           => 'Zamówienie i zwroty',
                'payment-policy'         => 'Polityka płatności',
                'shipping-policy'        => 'Polityka wysyłki',
                'privacy-cookies-policy' => 'Polityka prywatności i plików cookie',
            ],
        ],

        'datagrid' => [
            'toolbar' => [
                'mass-actions' => [
                    'select-action' => 'Wybierz działanie',
                    'select-option' => 'Wybierz opcję',
                    'submit'        => 'Wyślij',
                ],

                'filter' => [
                    'title' => 'Filtr',
                ],

                'search' => [
                    'title' => 'Szukaj',
                ],
            ],

            'filters' => [
                'title' => 'Zastosuj filtry',

                'custom-filters' => [
                    'title'     => 'Filtry niestandardowe',
                    'clear-all' => 'Wyczyść wszystko',
                ],

                'date-options' => [
                    'today'             => 'Dziś',
                    'yesterday'         => 'Wczoraj',
                    'this-week'         => 'W tym tygodniu',
                    'this-month'        => 'W tym miesiącu',
                    'last-month'        => 'W zeszłym miesiącu',
                    'last-three-months' => 'W ostatnich 3 miesiącach',
                    'last-six-months'   => 'W ostatnich 6 miesiącach',
                    'this-year'         => 'W tym roku',
                ],
            ],

            'table' => [
                'actions'              => 'Działania',
                'no-records-available' => 'Brak dostępnych rekordów.',
            ],
        ],

        'products'   => [
            'card' => [
                'new'                => 'Nowy',
                'sale'               => 'Wyprzedaż',
                'review-description' => 'Bądź pierwszym, który oceni ten produkt',
                'add-to-compare'     => 'Produkt został pomyślnie dodany do listy porównań.',
                'already-in-compare' => 'Produkt jest już dodany do listy porównań.',
                'add-to-cart'        => 'Dodaj do koszyka',
            ],

            'carousel' => [
                'view-all' => 'Zobacz wszystko',
            ],
        ],

        'range-slider' => [
            'range' => 'Zakres:',
        ],
    ],

    'products'  => [
        'reviews'                => 'Recenzje',
        'add-to-cart'            => 'Dodaj do koszyka',
        'add-to-compare'         => 'Produkt dodany do porównania.',
        'already-in-compare'     => 'Produkt jest już dodany do porównania.',
        'buy-now'                => 'Kup teraz',
        'compare'                => 'Porównaj',
        'rating'                 => 'Ocena',
        'title'                  => 'Tytuł',
        'comment'                => 'Komentarz',
        'submit-review'          => 'Prześlij recenzję',
        'customer-review'        => 'Opinie klientów',
        'write-a-review'         => 'Napisz recenzję',
        'stars'                  => 'Gwiazdki',
        'share'                  => 'Udostępnij',
        'empty-review'           => 'Brak recenzji, bądź pierwszym, który oceni ten produkt',
        'was-this-helpful'       => 'Czy ta recenzja była pomocna?',
        'load-more'              => 'Wczytaj więcej',
        'add-image'              => 'Dodaj obraz',
        'description'            => 'Opis',
        'additional-information' => 'Dodatkowe informacje',
        'submit-success'         => 'Przesłano pomyślnie',
        'something-went-wrong'   => 'Coś poszło nie tak',
        'in-stock'               => 'Dostępny',
        'available-for-order'    => 'Dostępny na zamówienie',
        'out-of-stock'           => 'Niedostępny',
        'related-product-title'  => 'Powiązane produkty',
        'up-sell-title'          => 'Znaleźliśmy inne produkty, które mogą Cię zainteresować!',
        'new'                    => 'Nowy',
        'as-low-as'              => 'Już od',
        'starting-at'            => 'Począwszy od',
        'name'                   => 'Nazwa',
        'qty'                    => 'Ilość',
        'offers'                 => 'Kup :qty za :price każdy i zaoszczędź :discount%',

        'sort-by'                => [
            'title'   => 'Sortuj według',
            'options' => [
                'from-a-z'        => 'Od A do Z',
                'from-z-a'        => 'Od Z do A',
                'latest-first'    => 'Najnowsze najpierw',
                'oldest-first'    => 'Najstarsze najpierw',
                'cheapest-first'  => 'Najtańsze najpierw',
                'expensive-first' => 'Najdroższe najpierw',
            ],
        ],

        'view' => [
            'type' => [
                'configurable' => [
                    'select-options'       => 'Proszę wybrać opcję',
                    'select-above-options' => 'Proszę wybrać powyższe opcje',
                ],

                'bundle' => [
                    'none' => 'Brak',
                ],

                'downloadable' => [
                    'samples' => 'Próbki',
                    'links'   => 'Linki',
                    'sample'  => 'Próbka',
                ],

                'grouped' => [
                    'name' => 'Nazwa',
                ],
            ],

            'reviews' => [
                'cancel'      => 'Anuluj',
                'success'     => 'Recenzja została pomyślnie przesłana.',
                'attachments' => 'Załączniki',
            ],
        ],

        'configurations' => [
            'compare_options'  => 'Opcje porównywania',
            'wishlist-options' => 'Opcje listy życzeń',
        ],
    ],

    'categories' => [
        'filters' => [
            'filters'   => 'Filtry:',
            'filter'    => 'Filtr',
            'sort'      => 'Sortuj',
            'clear-all' => 'Wyczyść wszystko',
        ],

        'toolbar' => [
            'show' => 'Pokaż',
        ],

        'view' => [
            'empty'     => 'Brak dostępnych produktów w tej kategorii',
            'load-more' => 'Wczytaj więcej',
        ],
    ],

    'search' => [
        'title'          => 'Wyniki wyszukiwania dla : :query',
        'configurations' => [
            'image-search-option' => 'Opcja wyszukiwania obrazem',
        ],
    ],

    'compare'  => [
        'product-compare'    => 'Porównanie produktów',
        'delete-all'         => 'Usuń wszystko',
        'empty-text'         => 'Nie masz żadnych pozycji na liście porównań',
        'title'              => 'Porównanie produktów',
        'already-added'      => 'Produkt jest już dodany do listy porównań',
        'item-add-success'   => 'Produkt został pomyślnie dodany do listy porównań',
        'remove-success'     => 'Produkt został pomyślnie usunięty',
        'remove-all-success' => 'Wszystkie produkty zostały pomyślnie usunięte',
        'remove-error'       => 'Coś poszło nie tak, spróbuj ponownie później',
    ],

    'checkout' => [
        'success' => [
            'title'         => 'Zamówienie zostało pomyślnie złożone',
            'thanks'        => 'Dziękujemy za zamówienie!',
            'order-id-info' => 'Twój numer zamówienia to #:order_id',
            'info'          => 'Prześlemy Ci e-mail z danymi zamówienia i informacjami o śledzeniu.',
        ],

        'cart' => [
            'item-add-to-cart'          => 'Produkt został dodany pomyślnie',
            'return-to-shop'            => 'Powrót do sklepu',
            'continue-to-checkout'      => 'Przejdź do zamówienia',
            'rule-applied'              => 'Zastosowano regułę koszyka',
            'minimum-order-message'     => 'Minimalna wartość zamówienia wynosi :amount',
            'suspended-account-message' => 'Twoje konto zostało zawieszone.',
            'missing-fields'            => 'Brakujące wymagane pola dla tego produktu.',
            'missing-options'           => 'Brak opcji dla tego produktu.',
            'missing-links'             => 'Brakujące linki do pobrania dla tego produktu.',
            'select-hourly-duration'    => 'Wybierz długość okresu na godzinę.',
            'qty-missing'               => 'Co najmniej jeden produkt powinien mieć ilość większą niż 1.',
            'success-remove'            => 'Produkt został pomyślnie usunięty z koszyka.',
            'inventory-warning'         => 'Żądana ilość nie jest dostępna, spróbuj ponownie później.',
            'illegal'                   => 'Ilość nie może być mniejsza niż jeden.',
            'inactive'                  => 'Produkt został dezaktywowany i usunięty z koszyka.',

            'index' => [
                'home'                     => 'Strona główna',
                'cart'                     => 'Koszyk',
                'view-cart'                => 'Zobacz koszyk',
                'product-name'             => 'Nazwa produktu',
                'remove'                   => 'Usuń',
                'quantity'                 => 'Ilość',
                'price'                    => 'Cena',
                'tax'                      => 'Podatek',
                'total'                    => 'Suma',
                'continue-shopping'        => 'Kontynuuj zakupy',
                'update-cart'              => 'Aktualizuj koszyk',
                'move-to-wishlist-success' => 'Wybrane produkty zostały pomyślnie przeniesione na listę życzeń.',
                'remove-selected-success'  => 'Wybrane produkty zostały pomyślnie usunięte z koszyka.',
                'empty-product'            => 'Nie masz produktów w koszyku.',
                'quantity-update'          => 'Aktualizacja ilości zakończona sukcesem',
                'see-details'              => 'Zobacz szczegóły',
                'move-to-wishlist'         => 'Przenieś do listy życzeń',
            ],

            'coupon' => [
                'code'            => 'Kod kuponu',
                'applied'         => 'Kupon zastosowany',
                'apply'           => 'Zastosuj kupon',
                'error'           => 'Coś poszło nie tak',
                'remove'          => 'Usuń kupon',
                'invalid'         => 'Kod kuponu jest nieprawidłowy.',
                'discount'        => 'Zniżka kuponu',
                'apply-issue'     => 'Nie można zastosować kodu kuponu.',
                'success-apply'   => 'Kod kuponu został pomyślnie zastosowany.',
                'already-applied' => 'Kod kuponu został już zastosowany.',
                'enter-your-code' => 'Wprowadź swój kod',
                'subtotal'        => 'Suma częściowa',
                'button-title'    => 'Zastosuj',
            ],

            'mini-cart' => [
                'see-details'          => 'Zobacz szczegóły',
                'shopping-cart'        => 'Koszyk',
                'offer-on-orders'      => 'Otrzymaj do 30% RABATU przy pierwszym zamówieniu',
                'remove'               => 'Usuń',
                'empty-cart'           => 'Twój koszyk jest pusty',
                'subtotal'             => 'Suma częściowa',
                'continue-to-checkout' => 'Przejdź do zamówienia',
                'view-cart'            => 'Zobacz koszyk',
            ],

            'summary' => [
                'cart-summary'        => 'Podsumowanie koszyka',
                'sub-total'           => 'Suma częściowa',
                'tax'                 => 'Podatek',
                'delivery-charges'    => 'Koszty dostawy',
                'discount-amount'     => 'Kwota rabatu',
                'grand-total'         => 'Razem',
                'place-order'         => 'Złóż zamówienie',
                'proceed-to-checkout' => 'Przejdź do zamówienia',
            ],
        ],

        'onepage' => [
            'addresses' => [
                'billing' => [
                    'billing-address'      => 'Adres rozliczeniowy',
                    'add-new-address'      => 'Dodaj nowy adres',
                    'same-billing-address' => 'Adres jest taki sam jak mój adres rozliczeniowy',
                    'back'                 => 'Wróć',
                    'company-name'         => 'Nazwa firmy',
                    'first-name'           => 'Imię',
                    'last-name'            => 'Nazwisko',
                    'email'                => 'E-mail',
                    'street-address'       => 'Adres ulicy',
                    'country'              => 'Kraj',
                    'state'                => 'Stan',
                    'select-state'         => 'Wybierz stan',
                    'city'                 => 'Miasto',
                    'postcode'             => 'Kod pocztowy',
                    'telephone'            => 'Telefon',
                    'save-address'         => 'Zapisz ten adres',
                    'confirm'              => 'Potwierdź',
                ],

                'index' => [
                    'confirm' => 'Potwierdź',
                ],

                'shipping' => [
                    'shipping-address' => 'Adres dostawy',
                    'add-new-address'  => 'Dodaj nowy adres',
                    'back'             => 'Wróć',
                    'company-name'     => 'Nazwa firmy',
                    'first-name'       => 'Imię',
                    'last-name'        => 'Nazwisko',
                    'email'            => 'E-mail',
                    'street-address'   => 'Adres ulicy',
                    'country'          => 'Kraj',
                    'state'            => 'Stan',
                    'select-state'     => 'Wybierz stan',
                    'select-country'   => 'Wybierz kraj',
                    'city'             => 'Miasto',
                    'postcode'         => 'Kod pocztowy',
                    'telephone'        => 'Telefon',
                    'save-address'     => 'Zapisz ten adres',
                    'confirm'          => 'Potwierdź',
                ],
            ],

            'coupon' => [
                'discount'        => 'Zniżka kuponu',
                'code'            => 'Kod kuponu',
                'applied'         => 'Kupon zastosowany',
                'applied-coupon'  => 'Zastosowany kupon',
                'apply'           => 'Zastosuj kupon',
                'remove'          => 'Usuń kupon',
                'apply-issue'     => 'Nie można zastosować kodu kuponu.',
                'sub-total'       => 'Suma częściowa',
                'button-title'    => 'Zastosuj',
                'enter-your-code' => 'Wprowadź swój kod',
                'subtotal'        => 'Suma częściowa',
            ],

            'index' => [
                'home'     => 'Strona główna',
                'checkout' => 'Zamówienie',
            ],

            'payment' => [
                'payment-method' => 'Metoda płatności',
            ],

            'shipping' => [
                'shipping-method' => 'Metoda dostawy',
            ],

            'summary' => [
                'cart-summary'     => 'Podsumowanie koszyka',
                'sub-total'        => 'Suma częściowa',
                'tax'              => 'Podatek',
                'delivery-charges' => 'Koszty dostawy',
                'discount-amount'  => 'Kwota rabatu',
                'grand-total'      => 'Razem',
                'place-order'      => 'Złóż zamówienie',
            ],
        ],
    ],

    'home' => [
        'index' => [
            'offer'               => 'Zyskaj DO 40% RABATU przy pierwszym zamówieniu. KUP TERAZ',
            'verify-email'        => 'Zweryfikuj swoje konto e-mail',
            'resend-verify-email' => 'Ponownie prześlij e-mail weryfikacyjny',
        ],
    ],

    'errors' => [
        'go-to-home' => 'Przejdź na stronę główną',

        '404' => [
            'title'       => '404 Strona nie znaleziona',
            'description' => 'Ups! Strona, którą próbujesz odnaleźć, jest na wakacjach. Wygląda na to, że nie mogliśmy znaleźć tego, czego szukasz.',
        ],

        '401' => [
            'title'       => '401 Nieautoryzowany dostęp',
            'description' => 'Ups! Wygląda na to, że nie masz uprawnień do dostępu do tej strony. Wydaje się, że brakuje Ci niezbędnych poświadczeń.',
        ],

        '403' => [
            'title'       => '403 Dostęp zabroniony',
            'description' => 'Ups! Ta strona jest ograniczona. Wydaje się, że nie masz odpowiednich uprawnień do przeglądania tej zawartości.',
        ],

        '500' => [
            'title'       => '500 Wewnętrzny błąd serwera',
            'description' => 'Ups! Coś poszło nie tak. Wydaje się, że mamy problem z załadowaniem strony, której szukasz.',
        ],

        '503' => [
            'title'       => '503 Usługa niedostępna',
            'description' => 'Ups! Wygląda na to, że jesteśmy chwilowo niedostępni z powodu prac konserwacyjnych. Proszę wrócić za chwilę.',
        ],
    ],

    'layouts' => [
        'my-account'            => 'Moje konto',
        'profile'               => 'Profil',
        'address'               => 'Adres',
        'reviews'               => 'Recenzje',
        'wishlist'              => 'Lista życzeń',
        'orders'                => 'Zamówienia',
        'downloadable-products' => 'Produkty do pobrania',
    ],

    'subscription' => [
        'already'             => 'Jesteś już zapisany do naszego newslettera.',
        'subscribe-success'   => 'Zostałeś pomyślnie zapisany do naszego newslettera.',
        'unsubscribe-success' => 'Zostałeś pomyślnie wypisany z naszego newslettera.',
    ],

    'emails' => [
        'dear'   => 'Szanowny/a :customer_name',
        'thanks' => 'Jeśli potrzebujesz pomocy, skontaktuj się z nami pod adresem <a href=":link" style=":style">:email</a>.<br/>Dziękujemy!',

        'customers' => [
            'registration' => [
                'subject'     => 'Rejestracja nowego klienta',
                'greeting'    => 'Witamy i dziękujemy za rejestrację!',
                'description' => 'Twoje konto zostało pomyślnie utworzone, i możesz zalogować się, używając swojego adresu e-mail oraz hasła. Po zalogowaniu będziesz mógł/mogła korzystać z innych usług, takich jak przeglądanie poprzednich zamówień, lista życzeń oraz edycja informacji o koncie.',
                'sign-in'     => 'Zaloguj się',
            ],

            'forgot-password' => [
                'subject'        => 'E-mail resetowania hasła',
                'greeting'       => 'Zapomniałeś hasła!',
                'description'    => 'Otrzymujesz ten e-mail, ponieważ otrzymaliśmy prośbę o zresetowanie hasła dla Twojego konta.',
                'reset-password' => 'Resetuj hasło',
            ],

            'update-password' => [
                'subject'     => 'Aktualizacja hasła',
                'greeting'    => 'Hasło zostało zaktualizowane!',
                'description' => 'Otrzymujesz ten e-mail, ponieważ zaktualizowałeś/aś swoje hasło.',
            ],

            'verification' => [
                'subject'        => 'E-mail weryfikacyjny konta',
                'greeting'       => 'Witaj!',
                'description'    => 'Proszę kliknij przycisk poniżej, aby zweryfikować swój adres e-mail.',
                'verify-email'   => 'Zweryfikuj adres e-mail',
            ],

            'commented' => [
                'subject'     => 'Nowy komentarz został dodany',
                'description' => 'Notatka: :note',
            ],

            'subscribed' => [
                'subject'     => 'Zapisz się do naszego newslettera',
                'greeting'    => 'Witamy w naszej społeczności newslettera!',
                'description' => 'Gratulacje i witamy Cię w naszej społeczności newslettera! Jesteśmy podekscytowani, że jesteś z nami i będziemy Cię na bieżąco informować o najnowszych wiadomościach, trendach i ekskluzywnych ofertach.',
                'unsubscribe' => 'Wypisz się',
            ],
        ],

        'orders' => [
            'created' => [
                'subject'  => 'Potwierdzenie nowego zamówienia',
                'title'    => 'Potwierdzenie zamówienia!',
                'greeting' => 'Dziękujemy za Twoje zamówienie :order_id złożone dnia :created_at',
                'summary'  => 'Podsumowanie zamówienia',
            ],

            'invoiced' => [
                'subject'  => 'Potwierdzenie nowej faktury',
                'title'    => 'Potwierdzenie faktury!',
                'greeting' => 'Twoja faktura #:invoice_id dla zamówienia :order_id utworzona dnia :created_at',
                'summary'  => 'Podsumowanie faktury',
            ],

            'shipped' => [
                'subject'  => 'Potwierdzenie wysłania zamówienia',
                'title'    => 'Zamówienie wysłane!',
                'greeting' => 'Twoje zamówienie :order_id złożone dnia :created_at zostało wysłane',
                'summary'  => 'Podsumowanie przesyłki',
            ],

            'refunded' => [
                'subject'  => 'Potwierdzenie zwrotu pieniędzy',
                'title'    => 'Zamówienie zwrócone!',
                'greeting' => 'Zwrot środków został zainicjowany dla zamówienia :order_id złożonego dnia :created_at',
                'summary'  => 'Podsumowanie zwrotu',
            ],

            'canceled' => [
                'subject'  => 'Potwierdzenie anulowania zamówienia',
                'title'    => 'Zamówienie anulowane!',
                'greeting' => 'Twoje zamówienie :order_id złożone dnia :created_at zostało anulowane',
                'summary'  => 'Podsumowanie zamówienia',
            ],

            'commented' => [
                'subject' => 'Nowy komentarz został dodany',
                'title'   => 'Dodano nowy komentarz do Twojego zamówienia :order_id złożonego dnia :created_at',
            ],

            'shipping-address'  => 'Adres dostawy',
            'carrier'           => 'Przewoźnik',
            'tracking-number'   => 'Numer przesyłki',
            'billing-address'   => 'Adres rozliczeniowy',
            'contact'           => 'Kontakt',
            'shipping'          => 'Dostawa',
            'payment'           => 'Płatność',
            'sku'               => 'SKU',
            'name'              => 'Nazwa',
            'price'             => 'Cena',
            'qty'               => 'Ilość',
            'subtotal'          => 'Suma częściowa',
            'shipping-handling' => 'Opłaty za dostawę i obsługę',
            'tax'               => 'Podatek',
            'discount'          => 'Rabat',
            'grand-total'       => 'Razem',
        ],
    ],
];
