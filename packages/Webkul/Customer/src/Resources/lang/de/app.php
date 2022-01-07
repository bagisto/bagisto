<?php

return [
    'wishlist' => [
        'success' => 'Artikel erfolgreich zur Wunschliste hinzugefügt',
        'failure' => 'Artikel kann nicht zur Wunschliste hinzugefügt werden',
        'already' => 'Artikel bereits in Ihrer Wunschliste vorhanden',
        'removed' => 'Artikel erfolgreich von der Wunschliste entfernt',
        'remove-fail' => 'Artikel kann nicht von der Wunschliste entfernt werden',
        'empty' => 'Sie haben keine Artikel auf Ihrer Wunschliste',
        'select-options' => 'Vor dem Hinzufügen zur Wunschliste müssen Optionen ausgewählt werden',
        'remove-all-success' => 'Alle Elemente von Ihrer Wunschliste wurden entfernt',
    ],

    'reviews' => [
        'empty' => 'Sie haben noch kein Produkt bewertet',
    ],

    'forget_password' => [
        'reset_link_sent' => 'Wir haben Ihren Link zum Zurücksetzen des Passworts per E-Mail gesendet.',
        'email_not_exist' => "Wir können keinen Benutzer mit dieser E-Mail-Adresse finden",
    ],

    'admin' => [
        'system' => [
            'captcha' => [
                'title' => 'Captcha',
                'credentials' => 'Referenzen',
                'site-key' => 'Site-Schlüssel',
                'secret-key' => 'Geheimer Schlüssel',
                'status' => 'Status',

                'validations' => [
                    'required' => 'Bitte wählen Sie CAPTCHA',
                    'captcha' => 'Etwas ist schief gelaufen! Bitte versuche es erneut.',
                ]
            ],
        ],
    ],
];
