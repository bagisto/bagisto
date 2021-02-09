<?php

return [
    'invalid_vat_format' => 'Girmiş olduğunuz vergi no hatalı',
    'security-warning' => 'Şüpheli etkinlik tespit edildi!!!',
    'nothing-to-delete' => 'Silinecek bir şey bulunmadı!',

    'layouts' => [
        'my-account' => 'Hesabım',
        'profile' => 'Profil',
        'address' => 'Adres',
        'reviews' => 'İncelemeler',
        'wishlist' => 'Dilek Listesi',
        'orders' => 'Siparişler',
        'downloadable-products' => 'İndirilebilir Ürünler'
    ],

    'common' => [
        'error' => 'Bir şeyler ters gitti, lütfen tekrar deneyin.',
        'image-upload-limit' => 'Maksimum resim yükleme boyutu 2 MB',
        'no-result-found' => 'Kayıt bulunamadı.'
    ],

    'home' => [
        'page-title' => config('app.name') . ' - Ana Sayfa',
        'featured-products' => 'Özel Ürünler',
        'new-products' => 'Yeni Ürünler',
        'verify-email' => 'Mail hesabınızı doğrulayınız',
        'resend-verify-email' => 'Doğrulama Maili Gönder'
    ],

    'header' => [
        'title' => 'Hesap',
        'dropdown-text' => 'Sepet, Sipariş & Dilek Listesini Yönet',
        'sign-in' => 'Giriş Yap',
        'sign-up' => 'Kaydol',
        'account' => 'Hesap',
        'cart' => 'Alışveriş Sepeti',
        'profile' => 'Profil',
        'wishlist' => 'Dilek Listesi',
        'logout' => 'Çıkış Yap',
        'search-text' => 'Ürün arayın...'
    ],

    'minicart' => [
        'view-cart' => 'Sepeti Görüntüle',
        'checkout' => 'Satın Al',
        'cart' => 'Sepet',
        'zero' => '0'
    ],

    'footer' => [
        'subscribe-newsletter' => 'Bültene Kaydol',
        'subscribe' => 'Abone Ol',
        'locale' => 'Dil',
        'currency' => 'Para Birimi',
    ],

    'subscription' => [
        'unsubscribe' => 'Bültenden Çık',
        'subscribe' => 'Abone Ol',
        'subscribed' => 'Abone kaydınız yapılmış durumda.',
        'not-subscribed' => 'Bülten aboneliğine kaydınız yapılamadı, lütfen tekrar deneyin.',
        'already' => 'Bülten aboneliğine kayıtlı durumdasınız.',
        'unsubscribed' => 'Bülten aboneliğinden çıkış yapıtınız.',
        'already-unsub' => 'Daha önceden bültenden çıkış yaptınız.'
    ],

    'search' => [
        'no-results' => 'Sonuç Bulunamadı',
        'page-title' => config('app.name') . ' - Arama',
        'found-results' => 'Arama Sonuçları',
        'found-result' => 'Arama Sonuçları',
        'image-search-option' => 'Image Search Option'
    ],

    'reviews' => [
        'title' => 'Başlık',
        'add-review-page-title' => 'İnceleme Ekle',
        'write-review' => 'İnceleme Yaz',
        'review-title' => 'İncelemeye bir başlık giriniz',
        'product-review-page-title' => 'Ürün İnceleme',
        'rating-reviews' => 'Oylama & İncelemeler',
        'submit' => 'GÖNDER',
        'delete-all' => 'Tüm incelemeler başarıyla silindi.',
        'ratingreviews' => ':rating Oylama & :review İnceleme',
        'star' => 'Yıldız',
        'percentage' => ':percentage %',
        'id-star' => 'star',
        'name' => 'Adı',
    ],

    'customer' => [
        'compare'           => [
            'text'                  => 'Karşılaştır',
            'compare_similar_items' => 'Benzer Ürünleri Karşılaştır',
            'add-tooltip'           => 'Karşılaştırma listesine ürün ekle',
            'added'                 => 'Ürün karşılaştırma listesine başarıyla eklendi.',
            'already_added'         => 'Ürün zaten karşılaştırma listesinde yer alıyor.',
            'removed'               => 'Ürün karşılaştırma listesinden başarıyla kaldırıldı.',
            'removed-all'           => 'Tüm ürünler, karşılaştırma listesinden başarıyla çıkarıldı.',
            'empty-text'            => "Karşılaştırma listenizde henüz ürün bulunmuyor.",
            'product_image'         => 'Ürün Görseli',
            'actions'               => 'Eylemler',
        ],

        'signup-text' => [
            'account_exists' => 'Hesabınız var mı?',
            'title' => 'Giriş Yapın'
        ],

        'signup-form' => [
            'page-title' => 'Yeni Müşteri Kaydı Oluşturun',
            'title' => 'Kaydol',
            'firstname' => 'Adınız',
            'lastname' => 'Soyadınız',
            'email' => 'E-Mail',
            'password' => 'Parola',
            'confirm_pass' => 'Parola (tekrar)',
            'button_title' => 'Kaydol',
            'agree' => 'Kabul',
            'terms' => 'Koşullar',
            'conditions' => 'Şartlar',
            'using' => 'bu web sitesini kullanarak',
            'agreement' => 'Anlaşma',
            'success' => 'Hesap başarıyla oluşturuldu.',
            'success-verify' => 'Hesap başarıyla oluşturuldu. Devam edebilmek için lütfen e-mail adresinizi doğrulayın.',
            'success-verify-email-unsent' => 'Hesap başarıyla oluşturuldu; ancak doğrulama maili gönderilemedi.',
            'failed' => 'Hata Oluştu! Hesabınız oluşturulamadı, lütfen tekrar deneyiniz.',
            'already-verified' => 'Hesabınız zaten doğrulanmış, lütfen yeni bir doğrulama maili talep edin.',
            'verification-not-sent' => 'Doğrulama maili gönderilirken hata oluştu, lütfen tekrar deneyin.',
            'verification-sent' => 'Doğrulama maili gönderildi',
            'verified' => 'Hesabınız başarıyla doğrulandı. Şimdi giriş yapabilirsiniz.',
            'verify-failed' => 'Hesabınızı doğrulayamadık.',
            'dont-have-account' => 'Kayıtlı hesabınız bulunmuyor.',
            'customer-registration' => 'Müşteri Kaydı Başarıyla Oluşturuldu.'
        ],

        'login-text' => [
            'no_account' => 'Hesabınız yok mu?',
            'title' => 'Kaydolun',
        ],

        'login-form' => [
            'page-title' => 'Müşteri Girişi',
            'title' => 'Giriş Yap',
            'email' => 'E-Mail',
            'password' => 'Parola',
            'forgot_pass' => 'Parolanızı mı unuttunuz?',
            'button_title' => 'Giriş Yap',
            'remember' => 'Beni Hatırla',
            'footer' => '© Copyright :year Webkul Software, Tüm hakları saklıdır.',
            'invalid-creds' => 'Lütfen bilgilerinizi kontrol edip tekrar deneyiniz.',
            'verify-first' => 'Öncelikle mail adresinizi doğrulayınız.',
            'not-activated' => 'Yönetici aktivasyonu gerekiyor.',
            'resend-verification' => 'Doğrulama mailini yeniden gönder'
        ],

        'forgot-password' => [
            'title' => 'Parolayı Sıfırla',
            'email' => 'E-Mail',
            'submit' => 'Parola Sıfırlama Maili Gönder',
            'page_title' => 'Parolanızı mı unuttunuz?'
        ],

        'reset-password' => [
            'title' => 'Parolayı Sıfırla',
            'email' => 'Kayıtlı Mail Adresi',
            'password' => 'Parola',
            'confirm-password' => 'Parola Doğrula',
            'back-link-title' => 'Giriş Sayfasına Dön',
            'submit-btn-title' => 'Parola Sıfırla'
        ],

        'account' => [
            'dashboard' => 'Profil Düzenle',
            'menu' => 'Menü',

            'general' => [
                'no' => 'Hayır',
                'yes' => 'Evet',
            ],

            'profile' => [
                'index' => [
                    'page-title' => 'Profil',
                    'title' => 'Profil',
                    'edit' => 'Düzenle',
                ],

                'edit-success' => 'Profil başarıyla güncellendi.',
                'edit-fail' => 'Profil güncellenirken hata oluştu, lütfen tekrar deneyin.',
                'unmatch' => 'Eski parolanız eşleşmiyor, lütfen tekrar deneyin.',

                'fname' => 'Adınız',
                'lname' => 'Soyadınız',
                'gender' => 'Cinsiyet',
                'other' => 'Diğer',
                'male' => 'Erkek',
                'female' => 'Kadın',
                'dob' => 'Doğum Tarihi',
                'phone' => 'Telefon',
                'email' => 'E-Mail',
                'opassword' => 'Önceki Parola',
                'password' => 'Parola',
                'cpassword' => 'Parola Doğrula',
                'submit' => 'Profil Güncelle',

                'edit-profile' => [
                    'title' => 'Profil Düzenle',
                    'page-title' => 'Profil Bilgilerini Düzenle'
                ]
            ],

            'address' => [
                'index' => [
                    'page-title' => 'Adres',
                    'title' => 'Adres',
                    'add' => 'Adres Ekle',
                    'edit' => 'Düzenşe',
                    'empty' => 'Henüz kayıtlı adresiniz bulunmuyor. Eklemek için lütfen aşağıdaki linki tıklayınız.',
                    'create' => 'Adres Ekle',
                    'delete' => 'Sil',
                    'make-default' => 'Varsayılan Yap',
                    'default' => 'Varsayılan',
                    'contact' => 'İletişim',
                    'confirm-delete' =>  'Bu adresi silmek istediğinizden emin misiniz?',
                    'default-delete' => 'Varsayılan adres değiştirilemez.',
                    'enter-password' => 'Parolanızı Giriniz',
                ],

                'create' => [
                    'page-title' => 'Adres Formu Ekle',
                    'company_name' => 'Şirket Adı',
                    'first_name' => 'Adınız',
                    'last_name' => 'Soyadınız',
                    'vat_id' => 'Vergi No',
                    'vat_help_note' => '[Not: Vergi no ile beraber ülke kodunu kullanın. Örn. INV01234567891]',
                    'title' => 'Adres Ekle',
                    'street-address' => 'Sokak Adresi',
                    'country' => 'Ülke',
                    'state' => 'Şehir',
                    'select-state' => 'Şehir seçiniz.',
                    'city' => 'İlçe',
                    'postcode' => 'Posta Kodu',
                    'phone' => 'Telefon',
                    'submit' => 'Adres Kaydet',
                    'success' => 'Adres başarıyla kaydedildi.',
                    'error' => 'Adres eklenirken hata oluştu!'
                ],

                'edit' => [
                    'page-title' => 'Adres Düzenle',
                    'company_name' => 'Şirket Adı',
                    'first_name' => 'Adınız',
                    'last_name' => 'Soyadınız',
                    'vat_id' => 'Vergi No',
                    'title' => 'Adres Düzenle',
                    'street-address' => 'Sokak Adresi',
                    'submit' => 'Adres Kaydet',
                    'success' => 'Adres Başarıyla Güncellendi.',
                ],
                'delete' => [
                    'success' => 'Adres başarıyla silindi.',
                    'failure' => 'Adres silinirken hata oluştu!',
                    'wrong-password' => 'Parolanızı hatalı girdiniz!'
                ]
            ],

            'order' => [
                'index' => [
                    'page-title' => 'Siparişler',
                    'title' => 'Siparişler',
                    'order_id' => 'Sipariş No',
                    'date' => 'Tarih',
                    'status' => 'Durum',
                    'total' => 'Toplam',
                    'order_number' => 'Sipariş No',
                    'processing' => 'İşleniyor',
                    'completed' => 'Tamamlandı',
                    'canceled' => 'İptal Edildi',
                    'closed' => 'Kapalı',
                    'pending' => 'Bekliyor',
                    'pending-payment' => 'Ödeme Bekliyor',
                    'fraud' => 'Geçersiz'
                ],

                'view' => [
                    'page-tile' => 'Sipariş #:order_id',
                    'info' => 'Bilgi',
                    'placed-on' => 'Sipariş Tarihi',
                    'products-ordered' => 'Sipariş Edilen Ürünler',
                    'invoices' => 'Faturalar',
                    'shipments' => 'Teslimatlar',
                    'SKU' => 'Barkod',
                    'product-name' => 'Ürün Adı',
                    'qty' => 'Miktar',
                    'item-status' => 'Ürün Durumu',
                    'item-ordered' => 'Sipariş Verildi (:qty_ordered)',
                    'item-invoice' => 'Fatura Oluşturuldu (:qty_invoiced)',
                    'item-shipped' => 'Kargoya Verildi (:qty_shipped)',
                    'item-canceled' => 'İptal Edildi (:qty_canceled)',
                    'item-refunded' => 'İade Edildi (:qty_refunded)',
                    'price' => 'Fiyat',
                    'total' => 'Toplam',
                    'subtotal' => 'Ara Toplam',
                    'shipping-handling' => 'Teslimat & Dağıtım',
                    'tax' => 'Vergi',
                    'discount' => 'İndirim',
                    'tax-percent' => 'Vergi Yüzdesi',
                    'tax-amount' => 'Vergi Miktarı',
                    'discount-amount' => 'İndirim Miktarı',
                    'grand-total' => 'Genel Toplam',
                    'total-paid' => 'Toplam Ödenen',
                    'total-refunded' => 'Toplam İade',
                    'total-due' => 'Toplam Kalan',
                    'shipping-address' => 'Teslimat Adresi',
                    'billing-address' => 'Fatura Adresi',
                    'shipping-method' => 'Teslimat Şekli',
                    'payment-method' => 'Ödeme Şekli',
                    'individual-invoice' => 'Fatura #:invoice_id',
                    'individual-shipment' => 'Teslimat #:shipment_id',
                    'print' => 'Yazdır',
                    'invoice-id' => 'Fatura No',
                    'order-id' => 'Sipariş No',
                    'order-date' => 'Sipariş Tarihi',
                    'bill-to' => 'Fatura Edilen',
                    'ship-to' => 'Teslim Edilen',
                    'contact' => 'İletişim',
                    'refunds' => 'İadeler',
                    'individual-refund' => 'İade #:refund_id',
                    'adjustment-refund' => 'İade Düzenlemesi',
                    'adjustment-fee' => 'Düzenleme Bedeli',
                    'cancel-btn-title' => 'İptal',
                    'tracking-number' => 'Takip No',
                    'cancel-confirm-msg' => 'Bu siparişi silmek istediğinizden emin misiniz?'
                ]
            ],

            'wishlist' => [
                'page-title' => 'Dilek Listesi',
                'title' => 'Dilek Listesi',
                'deleteall' => 'Tümünü Sil',
                'moveall' => 'Tüm Ürünleri Sepete Taşı',
                'move-to-cart' => 'Sepete Taşı',
                'error' => 'Ürün dilek listesine eklenemiyor, lütfen daha sonra tekrar deneyin.',
                'add' => 'Ürün dilek listesine başarıyla eklendi.',
                'remove' => 'Ürün dilek listesinden başarıyla kaldırıldı.',
                'add-wishlist-text'     => 'Ürünü dilek listenize ekleyin',
                'remove-wishlist-text'  => 'Ürünü dilek listenizden kaldırın',
                'moved' => 'Ürün alışveriş sepetine başarıyla taşındı.',
                'option-missing' => 'Ürün seçenekleri bulunamadı; bu yüzden dilek listesine taşınamadı.',
                'move-error' => 'Ürün dilek listesine taşınamadı, lütfen tekrar deneyin.',
                'success' => 'Ürün dilek listesine taşındı.',
                'failure' => 'Ürün dilek listesine taşınamadı, lütfen tekrar deneyin.',
                'already' => 'Ürün dilek listenizde yer alıyor.',
                'removed' => 'Ürün dilek listesinden kaldırıldı.',
                'remove-fail' => 'Ürün dilek listesinden kaldırılamadı, lütfen tekrar deneyin.',
                'empty' => 'Dilek listenizde ürün bulunmuyor.',
                'remove-all-success' => 'Dilek listenizdeki tüm ürünler kaldırıldı.',
            ],

            'downloadable_products' => [
                'title' => 'İndirilebilir Ürünler',
                'order-id' => 'Sipariş No',
                'date' => 'Tarih',
                'name' => 'Başlık',
                'status' => 'Durum',
                'pending' => 'Bekliyor',
                'available' => 'Hazır',
                'expired' => 'Süresi Doldu',
                'remaining-downloads' => 'Kalan İndirme',
                'unlimited' => 'Sınırsız',
                'download-error' => 'İndirme linki süresi doldu.',
                'payment-error' => 'Payment has not been done for this download.'
            ],

            'review' => [
                'index' => [
                    'title' => 'İncelemeler',
                    'page-title' => 'İncelemeler'
                ],

                'view' => [
                    'page-tile' => 'İnceleme #:id',
                ]
            ]
        ]
    ],

    'products' => [
        'layered-nav-title' => 'Ürün Filtrele',
        'price-label' => 'Olabildiğince Düşük',
        'remove-filter-link-title' => 'Tümünü Temizle',
        'filter-to' => ' - ',
        'sort-by' => 'Sıralama Şekli',
        'from-a-z' => 'A-Z',
        'from-z-a' => 'Z-A',
        'newest-first' => 'Yeniden Eskiye',
        'oldest-first' => 'Eskiden Yeniye',
        'cheapest-first' => 'Ucuzdan Pahallıya',
        'expensive-first' => 'Pahallıdan Ucuza',
        'show' => 'Göster',
        'pager-info' => 'Toplam :total üründen :showing adet ürün görüntüleniyor.',
        'description' => 'Açıklama',
        'specification' => 'Şartname',
        'total-reviews' => ':total İnceleme',
        'total-rating' => ':total_rating Oylama & :total_reviews İnceleme',
        'by' => ':name\'e göre',
        'up-sell-title' => 'İlginize çekebilecek başka ürünler bulduk!',
        'related-product-title' => 'Benzer Ürünler',
        'cross-sell-title' => 'Daha fazla seçenek',
        'reviews-title' => 'Oylama & İnceleme',
        'write-review-btn' => 'İnceleme Yaz',
        'choose-option' => 'Seçenek seçin',
        'sale' => 'Satışta',
        'new' => 'Yeni',
        'empty' => 'Bu kategoride ürün bulunamadı.',
        'add-to-cart' => 'Sepete Ekle',
        'book-now' => 'Hemen Ayırt',
        'buy-now' => 'Satın Al',
        'whoops' => 'Ops!',
        'quantity' => 'Miktar',
        'in-stock' => 'Stokta',
        'out-of-stock' => 'Stokta Yok',
        'view-all' => 'Tümünü Göster',
        'select-above-options' => 'Lütfen öncelikle seçenek seçin',
        'less-quantity' => 'Geçerli miktar giriniz.',
        'samples' => 'Örnekler',
        'links' => 'Linkler',
        'sample' => 'Örnek',
        'name' => 'Adı',
        'qty' => 'Miktar',
        'starting-at' => 'Başlangıç Fiyatı',
        'customize-options' => 'Seçenekleri Özelleştir',
        'choose-selection' => 'Seçim Yapın',
        'your-customization' => 'Özelleştirmeniz',
        'total-amount' => 'Toplam Miktar',
        'none' => 'Hiçbiri',
        'available-for-order' => 'Sipariş İçin Uygun',
        'settings' => 'Settings',
        'compare_options' => 'Compare Options',
        'wishlist-options' => 'Wishlist Options',
        'offers' => 'Buy :qty for :price each and save :discount%',
    ],

    // 'reviews' => [
    //     'empty' => 'You Have Not Reviewed Any Of Product Yet'
    // ]

    'buynow' => [
        'no-options' => 'Ürünü satın almadan önce lütfen seçenek seçin.'
    ],

    'checkout' => [
        'cart' => [
            'integrity' => [
                'missing_fields' => 'Bu ürün için zorunlu bazı alanlar girilmemiş.',
                'missing_options' => 'Bu ürün için seçenek girilmemiş.',
                'missing_links' => 'Bu ürün için indirilebilir linkler girilmemiş.',
                'qty_missing' => 'En az bir adet ürün girilmelidir.',
                'qty_impossible' => 'Bu üründen birden fazla adet girilemez.'
            ],
            'create-error' => 'Alışveriş sepeti oluşturulurken hata meydana geldi!',
            'title' => 'Alışveriş Sepeti',
            'empty' => 'Alışveriş sepetiniz boş',
            'update-cart' => 'Sepeti Güncelle',
            'continue-shopping' => 'Alışverişe Devam Et',
            'proceed-to-checkout' => 'Satın Al',
            'remove' => 'Kaldır',
            'remove-link' => 'Kaldır',
            'move-to-wishlist' => 'Dilek Listesine Ekle',
            'move-to-wishlist-success' => 'Dilek listesine başarıyla eklendi.',
            'move-to-wishlist-error' => 'Dilek listesine eklenirken hata oluştu, lütfen tekrar deneyin.',
            'add-config-warning' => 'Sepete eklemeden önce lütfen seçim yapınız.',
            'quantity' => [
                'quantity' => 'Miktar',
                'success' => 'Sepet başarıyla güncellendi!',
                'illegal' => 'Miktar en az 1 olmalıdır.',
                'inventory_warning' => 'Girilen miktar mevcut değil, lütfen yeniden deneyin.',
                'error' => 'Ürün güncellemesi yapılamıyor, lütfen tekrar deneyin.'
            ],

            'item' => [
                'error_remove' => 'Sepetten kaldırılacak ürün bulunamadı.',
                'success' => 'Ürün başarıyla sepete eklendi.',
                'success-remove' => 'Ürün sepetten başarıyla kaldırıldı.',
                'error-add' => 'Ürün sepete eklenirken hata oluştu, lütfen tekrar deneyin.',
                'inactive' => 'An item is inactive and was removed from cart.',
                'inactive-add' => 'Inactive item cannot be added to cart.',
            ],
            'quantity-error' => 'Girilen miktar mevcut değil.',
            'cart-subtotal' => 'Sepet Ara Toplam',
            'cart-remove-action' => 'Sepeti boşaltmak istediğinizden emin misiniz?',
            'partial-cart-update' => 'Sadece bazı ürünler güncellendi.',
            'link-missing' => '',
            'event' => [
                'expired' => 'Bu eylemin geçerliliği sona erdi.'
            ],
            'minimum-order-message' => 'Minimum order amount is :amount'
        ],

        'onepage' => [
            'title' => 'Satın Al',
            'information' => 'Bilgiler',
            'shipping' => 'Teslimat',
            'payment' => 'Ödeme',
            'complete' => 'Tamamla',
            'review' => 'İnceleme',
            'billing-address' => 'Fatura Adresi',
            'sign-in' => 'Giriş Yap',
            'company-name' => 'Şirket Adı',
            'first-name' => 'Ad',
            'last-name' => 'Soyad',
            'email' => 'E-Mail',
            'address1' => 'Adres',
            'city' => 'İlçe',
            'state' => 'Şehir',
            'select-state' => 'Şehir seçiniz',
            'postcode' => 'Posta Kodu',
            'phone' => 'Telefon',
            'country' => 'Ülke',
            'order-summary' => 'Sipariş Özeti',
            'use_for_shipping' => 'Teslimat adresi olarak kullan',
            'continue' => 'Devam et',
            'shipping-method' => 'Teslimat Türü Seç',
            'payment-methods' => 'Ödeme Türü Seç',
            'payment-method' => 'Ödeme Türü',
            'summary' => 'Sipariş Özeti',
            'price' => 'Fiyat',
            'quantity' => 'Miktar',
            'shipping-address' => 'Teslimat Adresi',
            'contact' => 'İletişim',
            'place-order' => 'Siparişi Oluştur',
            'new-address' => 'Yeni Adres Ekle',
            'save_as_address' => 'Adresi Kaydet',
            'apply-coupon' => 'Kuponu Uygula',
            'amt-payable' => 'Ödenecek Tutar',
            'got' => 'Alınan',
            'free' => 'Ücretsiz',
            'coupon-used' => 'Kullanılan Kupon',
            'applied' => 'Uygulanan',
            'back' => 'Geri',
            'cash-desc' => 'Kapıda Ödeme',
            'money-desc' => 'Havale/EFT',
            'paypal-desc' => 'Paypal',
            'free-desc' => 'Ücretsiz Kargo',
            'flat-desc' => 'Sabit Ücret',
            'password' => 'Parola',
            'login-exist-message' => 'Kayıtlı hesabınız bulunuyor, lütfen giriş yapınız ya da ziyaretçi olarak satın alın.',
            'enter-coupon-code' => 'Kupon Kodu Girin'
        ],

        'total' => [
            'order-summary' => 'Sipariş Özeti',
            'sub-total' => 'Ürünler',
            'grand-total' => 'Genel Toplam',
            'delivery-charges' => 'Kargo Ücreti',
            'tax' => 'Vergi',
            'discount' => 'İndirim',
            'price' => 'fiyat',
            'disc-amount' => 'İndirim Miktarı',
            'new-grand-total' => 'Yeni Genel Toplam',
            'coupon' => 'Kupon',
            'coupon-applied' => 'Uygulanan Kupon',
            'remove-coupon' => 'Kupon Kaldır',
            'cannot-apply-coupon' => 'Kupon Uygulanamaz',
            'invalid-coupon' => 'Kupon kodu geçersiz.',
            'success-coupon' => 'Kupon kodu başarıyla uygulandı.',
            'coupon-apply-issue' => 'Kupon kodu uygulanamaz.'
        ],

        'success' => [
            'title' => 'Sipariş başarıyla oluşturuldu.',
            'thanks' => 'Sipariş için teşekkür ederiz!',
            'order-id-info' => 'Sipariş numaranız #:order_id',
            'info' => 'Size sipariş detayları ve takip bilgilerini mail olarak ileteceğiz.'
        ]
    ],

    'mail' => [
        'order' => [
            'subject' => 'Yeni Sipariş Doğrulaması',
            'heading' => 'Sipariş Doğrulaması',
            'dear' => 'Sayın :customer_name',
            'dear-admin' => 'Sayın :admin_name',
            'greeting' => 'Sipariş verdiğiniz için teşekkür ederiz. :order_id nolu sipariş :created_at tarihinde oluşturuldu.',
            'greeting-admin' => ':order_id no\'lu sipariş :created_at tarihinde verildi.',
            'summary' => 'Sipariş Özeti',
            'shipping-address' => 'Teslimat Adresi',
            'billing-address' => 'Fatura Adresi',
            'contact' => 'İletişim',
            'shipping' => 'Teslimat Şekli',
            'payment' => 'Ödeme Şekli',
            'price' => 'Fiyat',
            'quantity' => 'Miktar',
            'subtotal' => 'Ara Toplam',
            'shipping-handling' => 'Teslimat & Dağıtım',
            'tax' => 'Vergi',
            'discount' => 'İndirim',
            'grand-total' => 'Genel Toplam',
            'final-summary' => 'Bizi tercih ettiğiniz için teşekkür ederiz. Ürün kargoya teslim edildikten sonra takip numarası iletilecektir.',
            'help' => 'Soru ve görüşleriniz için lütfen bizimle iletişime geçiniz: :support_email',
            'thanks' => 'Teşekkürler!',

            'comment' => [
                'subject' => 'Siparişinize #:order_id yeni yorum yapıldı.',
                'dear' => 'Sayın :customer_name',
                'final-summary' => 'Bizi tercih ettiğiniz için teşekkür ederiz.',
                'help' => 'Soru ve görüşleriniz için lütfen bizimle iletişime geçiniz: :support_email',
                'thanks' => 'Teşekkürler!',
            ],

            'cancel' => [
                'subject' => 'Sipariş İptal Doğrulaması',
                'heading' => 'Sipariş İptal Edildi',
                'dear' => 'Sayın :customer_name',
                'greeting' => '#:order_id no\'lu siparişiniz :created_at tarihinde iptal edilmiştir.',
                'summary' => 'Sipariş Özeti',
                'shipping-address' => 'Teslimat Adresi',
                'billing-address' => 'Fatura Adresi',
                'contact' => 'İletişim',
                'shipping' => 'Teslimat Şekli',
                'payment' => 'Ödeme Şekli',
                'subtotal' => 'Ara Toplam',
                'shipping-handling' => 'Teslimat & Dağıtım',
                'tax' => 'Vergi',
                'discount' => 'İndirim',
                'grand-total' => 'Genel Toplam',
                'final-summary' => 'Bizi tercih ettiğiniz için teşekkür ederiz.',
                'help' => 'Soru ve görüşleriniz için lütfen bizimle iletişime geçiniz: :support_email',
                'thanks' => 'Teşekkürler!',
            ]
        ],

        'invoice' => [
            'heading' => '#:order_id no\'lu siparişiniz için #:invoice_id no\'lu fatura oluşturuldu.',
            'subject' => '#:order_id no\'lu siparişinizin faturası',
            'summary' => 'Fatura Özeti',
        ],

        'shipment' => [
            'heading' => '#:order_id no\'lu siparişiniz için #:shipment_id no\'lu teslimat oluşturuldu.',
            'inventory-heading' => '#:order_id no\'lu siparişiniz için #:shipment_id no\'lu teslimat oluşturuldu.',
            'subject' => '#:order_id no\'lu siparişinizin teslimatı',
            'inventory-subject' => '#:order_id no\'lu siparişiniz için teslimat oluşturuldu.',
            'summary' => 'Teslimat Özeti',
            'carrier' => 'Kargo Şirketi',
            'tracking-number' => 'Takip Numarası',
            'greeting' => ':order_id no\'lu sipariş :created_at tarihinde oluşturuldu.',
        ],

        'refund' => [
            'heading' => '#:order_id siparişi için #:refund_id iadesi',
            'subject' => '#:order_id siparişi iadesi',
            'summary' => 'İade Özeti',
            'adjustment-refund' => 'İade Düzenlemesi',
            'adjustment-fee' => 'Düzenleme Bedeli'
        ],

        'forget-password' => [
            'subject' => 'Müşteri Parolası Sıfırlama',
            'dear' => 'Sayın :name',
            'info' => 'Parola sıfırlama talebinde bulunduğunuz için bu maili hesabınıza kayıtlı mail adresinize gönderdik.',
            'reset-password' => 'Parola Yenile',
            'final-summary' => 'Eğer parola yenileme talebinde bulunmadıysanız bu maili silebilirsiniz.',
            'thanks' => 'Teşekkürler!'
        ],

        'update-password' => [
            'subject' => 'Şifre güncellendi',
            'dear' => 'Sayın :name',
            'info' => 'Bu e-postayı, şifrenizi güncellediğiniz için alıyorsunuz.',
            'thanks' => 'Teşekkürler!'
        ],

        'customer' => [
            'new' => [
                'dear' => 'Sayın :customer_name',
                'username-email' => 'KullanıcıAdı/EMail',
                'subject' => 'Yeni Müşteri Kaydı',
                'password' => 'Parola',
                'summary' => 'Hesabınız başarıyla oluşturuldu. Detaylar aşağıda yer almaktadır:',
                'thanks' => 'Teşekkürler!',
            ],

            'registration' => [
                'subject' => 'Yeni Müşteri Kaydı',
                'customer-registration' => 'Müşteri Başarıyla Oluşturuldu',
                'dear' => 'Sayın :customer_name',
                'greeting' => 'Bizi tercih ettiğiniz için teşekkür ederiz. Aramıza hoşgeldiniz!',
                'summary' => 'Hesabınız başarıyla oluşturuldu; hemen kayıtlı bilgilerinizle giriş yapabilirsiniz. Giriş yaptıktan sonra, yaptığınız siparişleri inceleme, dilek listesine erişim ve hesap bilgilerini düzenleme gibi pek çok seçeneklere ulaşabilirsiniz.',
                'thanks' => 'Teşekkürler!',
            ],

            'verification' => [
                'heading' => config('app.name') . ' - Mail Doğrulaması',
                'subject' => 'Doğrulama Maili',
                'verify' => 'Hesabınızı Doğrulayın',
                'summary' => 'Bu mail adresi hesabınızı doğrulamanız için gönderildi.
                Bu mail adresinin hesabınızla ilişkisini tamamlamak için lütfen linke tıklayınız.'
            ],

            'subscription' => [
                'subject' => 'Bülten Maili',
                'greeting' => 'Aramıza Hoşgeldiniz: ' . config('app.name') . ' - Bülten Aboneliği',
                'unsubscribe' => 'Bültenden Çıkış',
                'summary' => 'Gelen kutunuzda yer verdiğiniz için teşekkür ederiz. ' . config('app.name') . ' maillerini almak istemezseniz bültenden çıkış yapabilirsiniz.'
            ]
        ]
    ],

    'webkul' => [
        'copy-right' => '© Copyright :year Webkul Software, Tüm Hakları Saklıdır.',
    ],

    'response' => [
        'create-success' => ':name başarıyla oluşturuldu.',
        'update-success' => ':name başarıyla güncellendi.',
        'delete-success' => ':name başarıyla silindi.',
        'submit-success' => ':name başarıyla iletildi.'
    ],
];
