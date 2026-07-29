<?php

return [
    'importers' => [
        'customers' => [
            'title' => 'Klienci',

            'validation' => [
                'errors' => [
                    'duplicate-email' => 'Email : \'%s\' występuje więcej niż raz w pliku importu.',
                    'duplicate-phone' => 'Telefon : \'%s\' występuje więcej niż raz w pliku importu.',
                    'email-not-found' => 'Email : \'%s\' nie zostało znalezione w systemie.',
                    'invalid-customer-group' => 'Grupa klientów jest nieprawidłowa lub nieobsługiwana',
                ],
            ],
        ],

        'products' => [
            'title' => 'Produkty',

            'validation' => [
                'errors' => [
                    'duplicate-url-key' => 'Klucz URL: \'%s\' został już wygenerowany dla produktu o SKU: \'%s\'.',
                    'image-not-file' => 'Obraz: \'%s\' jest adresem internetowym, a ten import ma pobierać obrazy z plików. Wybierz źródło \'Odnośniki do obrazów w pliku\' lub zastąp wartość nazwą pliku.',
                    'image-not-found' => 'Obraz: \'%s\' nie został znaleziony tam, gdzie ten import oczekuje swoich obrazów.',
                    'image-not-url' => 'Obraz: \'%s\' nie jest adresem internetowym, a ten import ma pobierać obrazy z odnośników w pliku. Wybierz inne źródło obrazów lub zastąp wartość pełnym adresem https://.',
                    'invalid-attribute-family' => 'Nieprawidłowa wartość dla kolumny rodziny atrybutów (rodzina atrybutów nie istnieje?)',
                    'invalid-type' => 'Typ produktu jest nieprawidłowy lub nieobsługiwany',
                    'sku-not-found' => 'Produkt o podanym SKU nie został znaleziony',
                    'super-attribute-not-found' => 'Superatrybut o kodzie \'%s\' nie został znaleziony lub nie należy do rodziny atrybutów: \'%s\'',
                ],
            ],
        ],

        'tax-rates' => [
            'title' => 'Stawki podatkowe',

            'validation' => [
                'errors' => [
                    'duplicate-identifier' => 'Identyfikator: \'%s\' został znaleziony więcej niż jeden raz w pliku importu.',
                    'identifier-not-found' => 'Identyfikator: \'%s\' nie został znaleziony w systemie.',
                ],
            ],
        ],
    ],

    'validation' => [
        'errors' => [
            'column-empty-headers' => 'Kolumny numer "%s" mają puste nagłówki.',
            'column-name-invalid' => 'Nieprawidłowe nazwy kolumn: "%s".',
            'column-not-found' => 'Nie znaleziono wymaganych kolumn: %s.',
            'column-numbers' => 'Liczba kolumn nie odpowiada liczbie wierszy w nagłówku.',
            'invalid-attribute' => 'Nagłówek zawiera nieprawidłowe atrybuty: "%s".',
            'more-issues' => 'oraz :count innych problemów — pobierz pełny raport, aby zobaczyć całą listę.',
            'more-rows' => '(+:count więcej wierszy)',
            'system' => 'Wystąpił nieoczekiwany błąd systemu.',
            'wrong-quotes' => 'Użyto znaków pojedynczych cudzysłowów zamiast prostych cudzysłowów.',
        ],
    ],
];
