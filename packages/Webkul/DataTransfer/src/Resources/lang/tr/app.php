<?php

return [
    'importers' => [
        'customers' => [
            'title' => 'Müşteriler',

            'validation' => [
                'errors' => [
                    'duplicate-email' => 'E-posta: \'%s\' içe aktarma dosyasında birden fazla kez bulunuyor.',
                    'duplicate-phone' => 'Telefon: \'%s\' içe aktarma dosyasında birden fazla kez bulunuyor.',
                    'email-not-found' => 'E-posta: \'%s\' sistemde bulunamadı.',
                    'invalid-customer-group' => 'Müşteri grubu geçersiz veya desteklenmiyor',
                ],
            ],
        ],

        'products' => [
            'title' => 'Ürünler',

            'validation' => [
                'errors' => [
                    'duplicate-url-key' => 'URL anahtarı: \'%s\' zaten SKU: \'%s\' olan bir öğe için oluşturuldu.',
                    'image-not-file' => 'Görsel: \'%s\' bir web adresi, ancak bu içe aktarma görselleri dosyalardan alacak şekilde ayarlanmış. Görsel kaynağı olarak \'Dosyadaki görsel bağlantıları\' seçin veya değeri bir dosya adıyla değiştirin.',
                    'image-not-found' => 'Görsel: \'%s\' bu içe aktarmanın görsellerini beklediği yerde bulunamadı.',
                    'image-not-url' => 'Görsel: \'%s\' bir web adresi değil, ancak bu içe aktarma görselleri dosyadaki bağlantılardan almak üzere ayarlanmış. Başka bir görsel kaynağı seçin veya değeri tam bir https:// adresiyle değiştirin.',
                    'invalid-attribute-family' => 'Öznitelik ailesi sütunu için geçersiz değer (öznitelik ailesi mevcut değil mi?)',
                    'invalid-type' => 'Ürün türü geçersiz veya desteklenmiyor',
                    'sku-not-found' => 'Belirtilen SKU ile ürün bulunamadı',
                    'super-attribute-not-found' => 'Kod: \'%s\' ile süper öznitelik bulunamadı veya öznitelik ailesine ait değil: \'%s\'',
                ],
            ],
        ],

        'tax-rates' => [
            'title' => 'Vergi Oranları',

            'validation' => [
                'errors' => [
                    'duplicate-identifier' => 'Tanımlayıcı: \'%s\' ithalat dosyasında birden fazla bulundu.',
                    'identifier-not-found' => 'Tanımlayıcı: \'%s\' sistemde bulunamadı.',
                ],
            ],
        ],
    ],

    'validation' => [
        'errors' => [
            'column-empty-headers' => 'Sütun sayısı "%s" başlıkları boş.',
            'column-name-invalid' => 'Geçersiz sütun adları: "%s".',
            'column-not-found' => 'Gerekli sütunlar bulunamadı: %s.',
            'column-numbers' => 'Sütun sayısı başlık satırındaki satır sayısıyla eşleşmiyor.',
            'invalid-attribute' => 'Başlık öznitelik(ler) içerir: "%s".',
            'more-issues' => 've :count sorun daha — tam liste için raporun tamamını indirin.',
            'more-rows' => '(+:count satır daha)',
            'system' => 'Beklenmeyen bir sistem hatası oluştu.',
            'wrong-quotes' => 'Tırnak içinde doğru tırnaklar yerine eğik tırnaklar kullanıldı.',
        ],
    ],
];
