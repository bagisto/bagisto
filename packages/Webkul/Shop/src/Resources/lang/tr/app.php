<?php

return [
    'configurations' => [
        'settings-title'      => 'Ayarlar',
        'settings-title-info' => 'Ayarlar, kullanıcı tercihlerine ve gereksinimlerine uygun olarak nasıl davrandığını kontrol eden yapılandırılabilir seçenekleri ifade eder.',
    ],

    'customers' => [
        'forgot-password' => [
            'title'                => 'Parolayı Kurtar',
            'email'                => 'E-posta',
            'forgot-password-text' => 'Parolanızı unuttuysanız, e-posta adresinizi girerek kurtarabilirsiniz.',
            'submit'               => 'Parolayı Sıfırla',
            'page-title'           => 'Parolanızı mı unuttunuz?',
            'back'                 => 'Girişe geri dön?',
            'sign-in-button'       => 'Giriş Yap',
            'footer'               => '© Telif Hakkı 2010 - 2022, Webkul Software (Hindistan\'da kayıtlı). Tüm hakları saklıdır.',
        ],

        'reset-password' => [
            'title'            => 'Parolayı Sıfırla',
            'email'            => 'Kayıtlı E-posta',
            'password'         => 'Parola',
            'confirm-password' => 'Parolayı Onayla',
            'back-link-title'  => 'Girişe Geri Dön',
            'submit-btn-title' => 'Parolayı Sıfırla',
            'footer'           => '© Telif Hakkı 2010 - 2022, Webkul Software (Hindistan\'da kayıtlı). Tüm hakları saklıdır.',
        ],

        'login-form' => [
            'page-title'          => 'Müşteri Girişi',
            'form-login-text'     => 'Bir hesabınız varsa, e-posta adresinizle oturum açın.',
            'show-password'       => 'Parolayı Göster',
            'title'               => 'Oturum Aç',
            'email'               => 'E-posta',
            'password'            => 'Parola',
            'forgot-pass'         => 'Parolanızı mı unuttunuz?',
            'button-title'        => 'Oturum Aç',
            'new-customer'        => 'Yeni müşteri misiniz?',
            'create-your-account' => 'Hesabınızı oluşturun',
            'footer'              => '© Telif Hakkı 2010 - 2022, Webkul Software (Hindistan\'da kayıtlı). Tüm hakları saklıdır.',
            'invalid-credentials' => 'Kimlik bilgilerinizi kontrol edin ve yeniden deneyin.',
            'not-activated'       => 'Aktivasyonunuz admin onayı gerektiriyor',
            'verify-first'        => 'Önce e-posta hesabınızı doğrulayın.',
        ],

        'signup-form' => [
            'page-title'                  => 'Kullanıcı Ol',
            'form-signup-text'            => 'Mağazamıza yeniyseniz, sizi üye olarak görmekten mutluluk duyarız.',
            'sign-in-button'              => 'Giriş Yap',
            'first-name'                  => 'Ad',
            'last-name'                   => 'Soyad',
            'email'                       => 'E-posta',
            'password'                    => 'Parola',
            'confirm-pass'                => 'Parolayı Onayla',
            'subscribe-to-newsletter'     => 'Bültenimize abone olun',
            'button-title'                => 'Kaydol',
            'account-exists'              => 'Zaten bir hesabınız var mı?',
            'footer'                      => '© Telif Hakkı 2010 - 2022, Webkul Software (Hindistan\'da kayıtlı). Tüm hakları saklıdır.',
            'success-verify'              => 'Hesap başarıyla oluşturuldu, doğrulama için bir e-posta gönderildi.',
            'success-verify-email-unsent' => 'Hesap başarıyla oluşturuldu, ancak doğrulama e-postası gönderilemedi.',
            'success'                     => 'Hesap başarıyla oluşturuldu.',
            'verified'                    => 'Hesabınız doğrulandı, şimdi giriş yapmayı deneyin.',
            'verify-failed'               => 'E-posta hesabınızı doğrulayamıyoruz.',
            'verification-not-sent'       => 'Hata! Doğrulama e-postası gönderme sorunu, lütfen daha sonra tekrar deneyin.',
            'verification-sent'           => 'Doğrulama e-postası gönderildi',
        ],

        'account' => [
            'home'    => 'Anasayfa',
            'profile' => [
                'title'                   => 'Profil',
                'first-name'              => 'Ad',
                'last-name'               => 'Soyad',
                'gender'                  => 'Cinsiyet',
                'dob'                     => 'Doğum Tarihi',
                'email'                   => 'E-posta',
                'delete-profile'          => 'Profili Sil',
                'edit-profile'            => 'Profili Düzenle',
                'edit'                    => 'Düzenle',
                'phone'                   => 'Telefon',
                'current-password'        => 'Geçerli Parola',
                'new-password'            => 'Yeni Parola',
                'confirm-password'        => 'Parolayı Onayla',
                'delete-success'          => 'Müşteri başarıyla silindi',
                'wrong-password'          => 'Yanlış Parola !',
                'delete-failed'           => 'Müşteriyi silerken hata oluştu.',
                'order-pending'           => 'Bazı Sipariş(ler) bekliyor veya işleniyor durumunda olduğu için müşteri hesabı silinemez.',
                'subscribe-to-newsletter' => 'Bültenimize abone olun',
                'delete'                  => 'Sil',
                'enter-password'          => 'Parolanızı Girin',
                'male'                    => 'Erkek',
                'female'                  => 'Kadın',
                'other'                   => 'Diğer',
                'save'                    => 'Kaydet',
            ],

            'addresses' => [
                'title'            => 'Adres',
                'edit'             => 'Düzenle',
                'edit-address'     => 'Adresi Düzenle',
                'delete'           => 'Sil',
                'set-as-default'   => 'Varsayılan Olarak Ayarla',
                'add-address'      => 'Adres Ekle',
                'company-name'     => 'Şirket Adı',
                'vat-id'           => 'KDV Kimlik Numarası',
                'address-1'        => 'Adres 1',
                'address-2'        => 'Adres 2',
                'city'             => 'Şehir',
                'state'            => 'Eyalet',
                'select-country'   => 'Ülke Seç',
                'country'          => 'Ülke',
                'default-address'  => 'Varsayılan Adres',
                'first-name'       => 'Ad',
                'last-name'        => 'Soyad',
                'phone'            => 'Telefon',
                'street-address'   => 'Cadde Adresi',
                'post-code'        => 'Posta Kodu',
                'empty-address'    => 'Henüz hesabınıza bir adres eklemediniz.',
                'create-success'   => 'Adres başarıyla eklendi.',
                'edit-success'     => 'Adres başarıyla güncellendi.',
                'default-delete'   => 'Varsayılan adres değiştirilemez.',
                'delete-success'   => 'Adres başarıyla silindi',
                'save'             => 'Kaydet',
                'security-warning' => 'Şüpheli aktivite bulundu!!!',
            ],

            'orders' => [
                'title'      => 'Siparişler',
                'order-id'   => 'Sipariş Kimliği',
                'order'      => 'Sipariş',
                'order-date' => 'Sipariş Tarihi',
                'total'      => 'Toplam',

                'status' => [
                    'title' => 'Durum',

                    'options' => [
                        'processing'      => 'İşleniyor',
                        'completed'       => 'Tamamlandı',
                        'canceled'        => 'İptal Edildi',
                        'closed'          => 'Kapandı',
                        'pending'         => 'Beklemede',
                        'pending-payment' => 'Ödeme Bekleniyor',
                        'fraud'           => 'Dolandırıcılık',
                    ],
                ],

                'action'      => 'Eylem',
                'empty-order' => 'Henüz herhangi bir ürün sipariş etmediniz',

                'view' => [
                    'title'              => 'Görüntüle',
                    'page-title'         => 'Sipariş #:order_id',
                    'total'              => 'Toplam',
                    'shipping-address'   => 'Teslimat Adresi',
                    'billing-address'    => 'Fatura Adresi',
                    'shipping-method'    => 'Teslimat Yöntemi',
                    'payment-method'     => 'Ödeme Yöntemi',
                    'cancel-btn-title'   => 'İptal Et',
                    'cancel-confirm-msg' => 'Bu siparişi iptal etmek istediğinizden emin misiniz?',
                    'cancel-success'     => 'Siparişiniz iptal edildi',
                    'cancel-error'       => 'Siparişiniz iptal edilemez.',

                    'information' => [
                        'info'              => 'Bilgi',
                        'placed-on'         => 'Üzerine yerleştirildi',
                        'sku'               => 'Ürün Kodu',
                        'product-name'      => 'Ad',
                        'price'             => 'Fiyat',
                        'item-status'       => 'Ürün Durumu',
                        'subtotal'          => 'Ara Toplam',
                        'tax-percent'       => 'Vergi Oranı',
                        'tax-amount'        => 'Vergi Miktarı',
                        'tax'               => 'Vergi',
                        'grand-total'       => 'Genel Toplam',
                        'item-ordered'      => 'Sipariş Edilen (:qty_ordered)',
                        'item-invoice'      => 'Faturalandırıldı (:qty_invoiced)',
                        'item-shipped'      => 'gönderildi (:qty_shipped)',
                        'item-canceled'     => 'İptal Edildi (:qty_canceled)',
                        'item-refunded'     => 'İade Edildi (:qty_refunded)',
                        'shipping-handling' => 'Kargo ve Taşıma',
                        'discount'          => 'İndirim',
                        'total-paid'        => 'Toplam Ödenen',
                        'total-refunded'    => 'Toplam İade Edilen',
                        'total-due'         => 'Toplam Tutar',
                    ],

                    'invoices' => [
                        'invoices'           => 'Faturalar',
                        'individual-invoice' => 'Fatura #:invoice_id',
                        'sku'                => 'Ürün Kodu',
                        'product-name'       => 'Ad',
                        'price'              => 'Fiyat',
                        'products-ordered'   => 'Sipariş Edilen Ürünler',
                        'qty'                => 'Adet',
                        'subtotal'           => 'Ara Toplam',
                        'tax-amount'         => 'Vergi Miktarı',
                        'grand-total'        => 'Genel Toplam',
                        'shipping-handling'  => 'Kargo ve Taşıma',
                        'discount'           => 'İndirim',
                        'tax'                => 'Vergi',
                        'print'              => 'Yazdır',
                    ],

                    'shipments' => [
                        'shipments'           => 'Gönderimler',
                        'tracking-number'     => 'Takip Numarası',
                        'individual-shipment' => 'Gönderi #:shipment_id',
                        'sku'                 => 'Ürün Kodu',
                        'product-name'        => 'Ad',
                        'qty'                 => 'Adet',
                        'subtotal'            => 'Ara Toplam',
                    ],

                    'refunds' => [
                        'refunds'           => 'İadeler',
                        'individual-refund' => 'İade #:refund_id',
                        'sku'               => 'Ürün Kodu',
                        'product-name'      => 'Ad',
                        'price'             => 'Fiyat',
                        'qty'               => 'Adet',
                        'tax-amount'        => 'Vergi Miktarı',
                        'subtotal'          => 'Ara Toplam',
                        'grand-total'       => 'Genel Toplam',
                        'no-result-found'   => 'Herhangi bir kayıt bulunamadı.',
                        'shipping-handling' => 'Kargo ve Taşıma',
                        'discount'          => 'İndirim',
                        'tax'               => 'Vergi',
                        'adjustment-refund' => 'Düzeltilmiş İade',
                        'adjustment-fee'    => 'Düzeltilmiş Ücret',
                    ],
                ],
            ],

            'reviews' => [
                'title'        => 'Değerlendirmeler',
                'empty-review' => 'Henüz herhangi bir ürünü değerlendirmediğiniz',
            ],

            'downloadable-products' => [
                'name'                => 'İndirilebilir Ürünler',
                'orderId'             => 'Sipariş Kimliği',
                'title'               => 'Başlık',
                'date'                => 'Tarih',
                'status'              => 'Durum',
                'remaining-downloads' => 'Kalan İndirmeler',
                'records-found'       => 'Kayıt(lar) bulundu',
                'empty-product'       => 'İndirilecek bir ürününüz yok',
                'download-error'      => 'İndirme bağlantısı süresi dolmuş.',
                'payment-error'       => 'Bu indirme için ödeme yapılmadı.',
            ],

            'wishlist' => [
                'page-title'         => 'İstek Listesi',
                'title'              => 'İstek Listesi',
                'color'              => 'Renk',
                'remove'             => 'Kaldır',
                'delete-all'         => 'Tümünü Sil',
                'empty'              => 'İstek listesi sayfasına herhangi bir ürün eklenmedi.',
                'move-to-cart'       => 'Sepete Taşı',
                'profile'            => 'Profil',
                'removed'            => 'Ürün Başarıyla İstek Listesinden Kaldırıldı',
                'remove-fail'        => 'Ürün İstek Listesinden Kaldırılamıyor',
                'moved'              => 'Ürün başarıyla sepete taşındı',
                'product-removed'    => 'Ürün, Yönetici Tarafından Kaldırıldığı İçin Artık Mevcut Değil',
                'remove-all-success' => 'İstek listenizden tüm ürünler kaldırıldı',
                'see-details'        => 'Detayları Gör',
            ],
        ],
    ],

    'components' => [
        'layouts' => [
            'header' => [
                'title'         => 'Hesap',
                'welcome'       => 'Hoş geldiniz',
                'welcome-guest' => 'Hoş geldiniz Misafir',
                'dropdown-text' => 'Sepeti, Siparişleri ve İstek Listesini Yönet',
                'sign-in'       => 'Giriş Yap',
                'sign-up'       => 'Kaydol',
                'account'       => 'Hesap',
                'cart'          => 'Sepet',
                'profile'       => 'Profil',
                'wishlist'      => 'İstek Listesi',
                'compare'       => 'Karşılaştır',
                'orders'        => 'Siparişler',
                'cart'          => 'Sepet',
                'logout'        => 'Çıkış Yap',
                'search-text'   => 'Ürünleri burada arayın',
                'search'        => 'Ara',
            ],

            'footer' => [
                'newsletter-text'        => 'Eğlenceli Bültenimiz için hazır olun!',
                'subscribe-stay-touch'   => 'İletişimde kalmak için abone olun.',
                'subscribe-newsletter'   => 'Bülten Aboneliği',
                'subscribe'              => 'Abone Ol',
                'footer-text'            => '© Telif Hakkı 2010 - 2023, Webkul Software (Hindistan’da kayıtlı). Tüm hakları saklıdır.',
                'locale'                 => 'Yerel Ayar',
                'currency'               => 'Para Birimi',
                'about-us'               => 'Hakkımızda',
                'customer-service'       => 'Müşteri Hizmetleri',
                'whats-new'              => 'Yenilikler',
                'contact-us'             => 'Bize Ulaşın',
                'order-return'           => 'Sipariş ve İade',
                'payment-policy'         => 'Ödeme Politikası',
                'shipping-policy'        => 'Kargo Politikası',
                'privacy-cookies-policy' => 'Gizlilik ve Çerez Politikası',
            ],
        ],

        'datagrid' => [
            'toolbar' => [
                'mass-actions' => [
                    'select-action' => 'Eylem Seç',
                    'select-option' => 'Seçenek Seç',
                    'submit'        => 'Gönder',
                ],

                'filter' => [
                    'title' => 'Filtrele',
                ],

                'search' => [
                    'title' => 'Ara',
                ],
            ],

            'filters' => [
                'title' => 'Filtreleri Uygula',

                'custom-filters' => [
                    'title'     => 'Özel Filtreler',
                    'clear-all' => 'Hepsini Temizle',
                ],

                'date-options' => [
                    'today'             => 'Bugün',
                    'yesterday'         => 'Dün',
                    'this-week'         => 'Bu Hafta',
                    'this-month'        => 'Bu Ay',
                    'last-month'        => 'Geçen Ay',
                    'last-three-months' => 'Son 3 Ay',
                    'last-six-months'   => 'Son 6 Ay',
                    'this-year'         => 'Bu Yıl',
                ],
            ],

            'table' => [
                'actions'              => 'Eylemler',
                'no-records-available' => 'Kayıt Yok.',
            ],
        ],

        'products'   => [
            'card' => [
                'new'                => 'Yeni',
                'sale'               => 'Satış',
                'review-description' => 'Bu ürünü incelemek için ilk yorumu yapın',
                'add-to-compare'     => 'Ürün karşılaştırma listesine başarıyla eklendi.',
                'already-in-compare' => 'Ürün zaten karşılaştırma listesine eklenmiş.',
                'add-to-cart'        => 'Sepete Ekle',
            ],

            'carousel' => [
                'view-all' => 'Hepsini Görüntüle',
            ],
        ],

        'range-slider' => [
            'range' => 'Aralık:',
        ],
    ],

    'products' => [
        'reviews'                => 'İncelemeler',
        'add-to-cart'            => 'Sepete Ekle',
        'add-to-compare'         => 'Ürün karşılaştırmaya eklendi.',
        'already-in-compare'     => 'Ürün zaten karşılaştırmada.',
        'buy-now'                => 'Hemen Satın Al',
        'compare'                => 'Karşılaştır',
        'rating'                 => 'Puan',
        'title'                  => 'Başlık',
        'comment'                => 'Yorum',
        'submit-review'          => 'İncelemeyi Gönder',
        'customer-review'        => 'Müşteri İncelemeleri',
        'write-a-review'         => 'İnceleme Yaz',
        'stars'                  => 'Yıldızlar',
        'share'                  => 'Paylaş',
        'empty-review'           => 'Hiç inceleme bulunamadı, bu ürünü incelemek için ilk yorumu yapın',
        'was-this-helpful'       => 'Bu İnceleme Yardımcı Oldu mu?',
        'load-more'              => 'Daha Fazla Yükle',
        'add-image'              => 'Resim Ekle',
        'description'            => 'Açıklama',
        'additional-information' => 'Ek Bilgiler',
        'submit-success'         => 'Başarıyla Gönderildi',
        'something-went-wrong'   => 'Bir şeyler ters gitti',
        'in-stock'               => 'Stokta Var',
        'available-for-order'    => 'Sipariş İçin Uygun',
        'out-of-stock'           => 'Stokta Yok',
        'related-product-title'  => 'İlgili Ürünler',
        'up-sell-title'          => 'Beğenebileceğiniz Diğer Ürünleri Bulduk!',
        'new'                    => 'Yeni',
        'as-low-as'              => 'Şu fiyattan başlayan',
        'starting-at'            => 'Başlangıç fiyatı',
        'name'                   => 'Ad',
        'qty'                    => 'Adet',
        'offers'                 => ':qty adet alın, her biri :price ve %:discount indirim ile satın alın',

        'sort-by' => [
            'title'   => 'Sırala',
            'options' => [
                'from-a-z'        => 'A’dan Z’ye',
                'from-z-a'        => 'Z’den A’ya',
                'latest-first'    => 'En Yeni İlk',
                'oldest-first'    => 'En Eski İlk',
                'cheapest-first'  => 'En Ucuz İlk',
                'expensive-first' => 'En Pahalı İlk',
            ],
        ],

        'view' => [
            'type' => [
                'configurable' => [
                    'select-options'       => 'Lütfen bir seçenek seçin',
                    'select-above-options' => 'Lütfen yukarıdaki seçenekleri seçin',
                ],

                'bundle' => [
                    'none' => 'Hiçbiri',
                ],

                'downloadable' => [
                    'samples' => 'Örnekler',
                    'links'   => 'Bağlantılar',
                    'sample'  => 'Örnek',
                ],

                'grouped' => [
                    'name' => 'Ad',
                ],
            ],

            'reviews' => [
                'cancel'      => 'İptal Et',
                'success'     => 'İnceleme başarıyla gönderildi.',
                'attachments' => 'Ekler',
            ],
        ],

        'configurations' => [
            'compare_options'  => 'Karşılaştırma seçenekleri',
            'wishlist-options' => 'İstek listesi seçenekleri',
        ],
    ],

    'categories' => [
        'filters' => [
            'filters'   => 'Filtreler:',
            'filter'    => 'Filtre',
            'sort'      => 'Sırala',
            'clear-all' => 'Hepsini Temizle',
        ],

        'toolbar' => [
            'show' => 'Göster',
        ],

        'view' => [
            'empty'     => 'Bu kategoride hiç ürün bulunmuyor',
            'load-more' => 'Daha Fazla Yükle',
        ],
    ],

    'search' => [
        'title'          => ':query için arama sonuçları',
        'configurations' => [
            'image-search-option' => 'Resim Arama Seçeneği',
        ],
    ],

    'compare' => [
        'product-compare'    => 'Ürün Karşılaştır',
        'delete-all'         => 'Tümünü Sil',
        'empty-text'         => 'Karşılaştırma listenizde öğe bulunmuyor',
        'title'              => 'Ürün Karşılaştır',
        'already-added'      => 'Öğe zaten karşılaştırma listesine eklenmiş',
        'item-add-success'   => 'Öğe başarıyla karşılaştırma listesine eklendi',
        'remove-success'     => 'Öğe başarıyla kaldırıldı.',
        'remove-all-success' => 'Tüm öğeler başarıyla kaldırıldı.',
        'remove-error'       => 'Bir şeyler ters gitti, lütfen daha sonra tekrar deneyin.',
    ],

    'checkout' => [
        'success' => [
            'title'         => 'Sipariş başarıyla verildi',
            'thanks'        => 'Siparişiniz için teşekkür ederiz!',
            'order-id-info' => 'Sipariş numaranız: #:order_id',
            'info'          => 'Sipariş detaylarınızı ve takip bilgilerinizi e-posta ile göndereceğiz',
        ],

        'cart' => [
            'item-add-to-cart'          => 'Öğe Başarıyla Eklendi',
            'return-to-shop'            => 'Alışverişe Devam Et',
            'continue-to-checkout'      => 'Ödemeye Devam Et',
            'rule-applied'              => 'Sepet kuralı uygulandı',
            'minimum-order-message'     => 'Minimum sipariş tutarı: :amount',
            'suspended-account-message' => 'Hesabınız askıya alındı.',
            'missing-fields'            => 'Bu ürün için bazı zorunlu alanlar eksik.',
            'missing-options'           => 'Bu ürün için seçenekler eksik.',
            'missing-links'             => 'Bu ürün için indirilebilir bağlantılar eksik.',
            'select-hourly-duration'    => 'Bir saatlik bir süre seçin.',
            'qty-missing'               => 'En az bir ürünün 1’den fazla miktarı olmalıdır.',
            'success-remove'            => 'Öğe başarıyla sepetten kaldırıldı.',
            'inventory-warning'         => 'Talep edilen miktar mevcut değil, lütfen daha sonra tekrar deneyin.',
            'illegal'                   => 'Miktar bir olamaz.',
            'inactive'                  => 'Ürün devre dışı bırakıldı ve ardından sepetten kaldırıldı.',

            'index' => [
                'home'                     => 'Ana Sayfa',
                'cart'                     => 'Sepet',
                'view-cart'                => 'Sepeti Görüntüle',
                'product-name'             => 'Ürün Adı',
                'remove'                   => 'Kaldır',
                'quantity'                 => 'Miktar',
                'price'                    => 'Fiyat',
                'tax'                      => 'Vergi',
                'total'                    => 'Toplam',
                'continue-shopping'        => 'Alışverişe Devam Et',
                'update-cart'              => 'Sepeti Güncelle',
                'move-to-wishlist-success' => 'Seçilen öğeler başarıyla favorilere taşındı.',
                'remove-selected-success'  => 'Seçilen öğeler başarıyla sepetten kaldırıldı.',
                'empty-product'            => 'Sepetinizde ürün bulunmuyor.',
                'quantity-update'          => 'Miktar başarıyla güncellendi',
                'see-details'              => 'Detayları Gör',
                'move-to-wishlist'         => 'Favorilere Taşı',
            ],

            'coupon' => [
                'code'            => 'Kupon kodu',
                'applied'         => 'Kupon uygulandı',
                'apply'           => 'Kuponu Kullan',
                'error'           => 'Bir şeyler yanlış gitti',
                'remove'          => 'Kuponu Kaldır',
                'invalid'         => 'Kupon kodu geçersiz.',
                'discount'        => 'Kupon İndirimi',
                'apply-issue'     => 'Kupon kodu uygulanamıyor.',
                'success-apply'   => 'Kupon kodu başarıyla uygulandı.',
                'already-applied' => 'Kupon kodu zaten uygulandı.',
                'enter-your-code' => 'Kodunuzu girin',
                'subtotal'        => 'Ara Toplam',
                'button-title'    => 'Kullan',
            ],

            'mini-cart' => [
                'see-details'           => 'Detayları Gör',
                'shopping-cart'         => 'Alışveriş Sepeti',
                'offer-on-orders'       => '1. siparişinizde %30\'a kadar İNDİRİM alın',
                'remove'                => 'Kaldır',
                'empty-cart'            => 'Sepetiniz boş',
                'subtotal'              => 'Ara Toplam',
                'continue-to-checkout'  => 'Ödemeye Devam Et',
                'view-cart'             => 'Sepeti Görüntüle',
            ],

            'summary' => [
                'cart-summary'        => 'Sepet Özeti',
                'sub-total'           => 'Ara Toplam',
                'tax'                 => 'Vergi',
                'delivery-charges'    => 'Teslimat Ücretleri',
                'discount-amount'     => 'İndirim Tutarı',
                'grand-total'         => 'Genel Toplam',
                'place-order'         => 'Sipariş Ver',
                'proceed-to-checkout' => 'Ödemeye Devam Et',
            ],
        ],

        'onepage' => [
            'addresses' => [
                'billing' => [
                    'billing-address'       => 'Fatura Adresi',
                    'add-new-address'       => 'Yeni adres ekle',
                    'same-billing-address'  => 'Adresim fatura adresiyle aynıdır',
                    'back'                  => 'Geri',
                    'company-name'          => 'Şirket Adı',
                    'first-name'            => 'Ad',
                    'last-name'             => 'Soyad',
                    'email'                 => 'E-posta',
                    'street-address'        => 'Cadde Adresi',
                    'country'               => 'Ülke',
                    'state'                 => 'Eyalet',
                    'select-state'          => 'Eyalet Seçin',
                    'city'                  => 'Şehir',
                    'postcode'              => 'Posta Kodu',
                    'telephone'             => 'Telefon',
                    'save-address'          => 'Bu adresi kaydet',
                    'confirm'               => 'Onayla',
                ],

                'index' => [
                    'confirm' => 'Onayla',
                ],

                'shipping' => [
                    'shipping-address' => 'Teslimat Adresi',
                    'add-new-address'  => 'Yeni adres ekle',
                    'back'             => 'Geri',
                    'company-name'     => 'Şirket Adı',
                    'first-name'       => 'Ad',
                    'last-name'        => 'Soyad',
                    'email'            => 'E-posta',
                    'street-address'   => 'Cadde adresi',
                    'country'          => 'Ülke',
                    'state'            => 'Eyalet',
                    'select-state'     => 'Eyalet Seçin',
                    'select-country'   => 'Ülke Seçin',
                    'city'             => 'Şehir',
                    'postcode'         => 'Posta Kodu',
                    'telephone'        => 'Telefon',
                    'save-address'     => 'Bu adresi kaydet',
                    'confirm'          => 'Onayla',
                ],
            ],

            'coupon' => [
                'discount'        => 'Kupon İndirimi',
                'code'            => 'Kupon Kodu',
                'applied'         => 'Kupon Uygulandı',
                'applied-coupon'  => 'Uygulanan Kupon',
                'apply'           => 'Kupon Kullan',
                'remove'          => 'Kuponu Kaldır',
                'apply-issue'     => 'Kupon kodu uygulanamıyor.',
                'sub-total'       => 'Ara Toplam',
                'button-title'    => 'Kullan',
                'enter-your-code' => 'Kodunuzu girin',
                'subtotal'        => 'Ara Toplam',
            ],

            'index' => [
                'home'     => 'Ana Sayfa',
                'checkout' => 'Ödeme Sayfası',
            ],

            'payment' => [
                'payment-method' => 'Ödeme Yöntemi',
            ],

            'shipping' => [
                'shipping-method' => 'Teslimat Yöntemi',
            ],

            'summary' => [
                'cart-summary'     => 'Sepet Özeti',
                'sub-total'        => 'Ara Toplam',
                'tax'              => 'Vergi',
                'delivery-charges' => 'Teslimat Ücretleri',
                'discount-amount'  => 'İndirim Tutarı',
                'grand-total'      => 'Genel Toplam',
                'place-order'      => 'Sipariş Ver',
            ],
        ],
    ],

    'home' => [
        'index' => [
            'offer'               => '1. siparişinizde %40\'a kadar İNDİRİM alın ŞİMDİ ALIŞVERİŞE BAŞLA',
            'verify-email'        => 'E-posta hesabınızı doğrulayın',
            'resend-verify-email' => 'Doğrulama E-postası Gönder',
        ],
    ],

    'errors' => [
        'go-to-home' => 'Ana Sayfa\'ya Git',

        '404' => [
            'title'       => '404 Sayfa Bulunamadı',
            'description' => 'Hata! Aradığınız sayfa tatilde gibi görünüyor. Aradığınızı bulamadık gibi görünüyor.',
        ],

        '401' => [
            'title'       => '401 Yetkisiz',
            'description' => 'Hata! Bu sayfaya erişmeye yetkiniz yok gibi görünüyor. Gerekli kimlik bilgilerinizi eksik olduğu görünüyor.',
        ],

        '403' => [
            'title'       => '403 Yasak',
            'description' => 'Hata! Bu sayfa sınırlıdır. Görüntülemek için gerekli izinlere sahip olmadığınız görünüyor.',
        ],

        '500' => [
            'title'       => '500 İç Sunucu Hatası',
            'description' => 'Hata! Bir şeyler yanlış gitti gibi görünüyor. Aradığınız sayfayı yüklemekte sorun yaşıyoruz gibi görünüyor.',
        ],

        '503' => [
            'title'       => '503 Hizmet Kullanılamıyor',
            'description' => 'Hata! Geçici olarak bakım için kapalı gibi görünüyoruz. Lütfen biraz sonra tekrar kontrol edin.',
        ],
    ],

    'layouts' => [
        'my-account'            => 'Hesabım',
        'profile'               => 'Profil',
        'address'               => 'Adres',
        'reviews'               => 'Yorumlar',
        'wishlist'              => 'Dilek Listesi',
        'orders'                => 'Siparişler',
        'downloadable-products' => 'İndirilebilir Ürünler',
    ],

    'subscription' => [
        'already'             => 'Zaten bültenimize abonesiniz.',
        'subscribe-success'   => 'Bültenimize başarıyla abone oldunuz.',
        'unsubscribe-success' => 'Bülten aboneliğiniz başarıyla iptal edildi.',
    ],

    'emails' => [
        'dear'   => 'Sayın :customer_name',
        'thanks' => 'Herhangi bir yardıma ihtiyacınız varsa lütfen şu adresten bizimle iletişime geçin: <a href=":link" style=":style">:email</a>.<br/>Teşekkürler!',

        'customers' => [
            'registration' => [
                'subject'     => 'Yeni Müşteri Kaydı',
                'greeting'    => 'Hoş geldiniz ve kaydınız için teşekkür ederiz!',
                'description' => 'Hesabınız başarıyla oluşturuldu ve şimdi e-posta adresinizi ve şifre bilgilerinizi kullanarak giriş yapabilirsiniz. Giriş yaptıktan sonra, geçmiş siparişleri inceleme, dilek listelerini görüntüleme ve hesap bilgilerinizi düzenleme gibi diğer hizmetlere erişebileceksiniz.',
                'sign-in'     => 'Giriş Yap',
            ],

            'forgot-password' => [
                'subject'        => 'Şifre Sıfırlama E-postası',
                'greeting'       => 'Şifrenizi mi unuttunuz!',
                'description'    => 'Bu e-postayı hesabınız için bir şifre sıfırlama isteği aldığımız için alıyorsunuz.',
                'reset-password' => 'Şifreyi Sıfırla',
            ],

            'update-password' => [
                'subject'     => 'Şifre Güncellendi',
                'greeting'    => 'Şifre Güncellendi!',
                'description' => 'Bu e-postayı şifrenizi güncellediğiniz için alıyorsunuz.',
            ],

            'verification' => [
                'subject'        => 'Hesap Doğrulama E-postası',
                'greeting'       => 'Hoş geldiniz!',
                'description'    => 'E-posta adresinizi doğrulamak için lütfen aşağıdaki düğmeye tıklayın.',
                'verify-email'   => 'E-posta Adresini Doğrula',
            ],

            'commented' => [
                'subject'     => 'Yeni Yorum Eklendi',
                'description' => 'Not: :note',
            ],

            'subscribed' => [
                'subject'     => 'Bizim Bültenimize Abone Oldunuz',
                'greeting'    => 'Bültenimize hoş geldiniz!',
                'description' => 'Tebrikler ve bülten topluluğumuza hoş geldiniz! Sizi gemimize almak ve sizi en son haberler, trendler ve özel tekliflerle güncel tutmak için sabırsızlanıyoruz.',
                'unsubscribe' => 'Aboneliği İptal Et',
            ],
        ],

        'orders' => [
            'created' => [
                'subject'  => 'Yeni Sipariş Onayı',
                'title'    => 'Sipariş Onayı!',
                'greeting' => ':created_at tarihinde :order_id numaralı siparişiniz için teşekkür ederiz',
                'summary'  => 'Sipariş Özeti',
            ],

            'invoiced' => [
                'subject'  => 'Yeni Fatura Onayı',
                'title'    => 'Fatura Onayı!',
                'greeting' => ':created_at tarihinde oluşturulan :order_id numaralı faturanız #:invoice_id',
                'summary'  => 'Fatura Özeti',
            ],

            'shipped' => [
                'subject'  => 'Yeni Gönderi Onayı',
                'title'    => 'Sipariş Gönderildi!',
                'greeting' => ':created_at tarihinde verdiğiniz :order_id numaralı siparişiniz gönderildi',
                'summary'  => 'Gönderi Özeti',
            ],

            'refunded' => [
                'subject'  => 'Yeni İade Onayı',
                'title'    => 'Sipariş İadesi!',
                'greeting' => ':created_at tarihinde verdiğiniz :order_id numaralı sipariş için iade başlatıldı',
                'summary'  => 'İade Özeti',
            ],

            'canceled' => [
                'subject'  => 'Yeni Sipariş İptali',
                'title'    => 'Sipariş İptali!',
                'greeting' => ':created_at tarihinde verdiğiniz :order_id numaralı siparişiniz iptal edildi',
                'summary'  => 'Sipariş Özeti',
            ],

            'commented' => [
                'subject' => 'Yeni Yorum Eklendi',
                'title'   => 'Siparişinize Yeni Yorum Eklendi: :order_id tarihinde verdiğiniz',
            ],

            'shipping-address'  => 'Teslimat Adresi',
            'carrier'           => 'Taşıyıcı',
            'tracking-number'   => 'Takip Numarası',
            'billing-address'   => 'Fatura Adresi',
            'contact'           => 'İletişim',
            'shipping'          => 'Kargo',
            'payment'           => 'Ödeme',
            'sku'               => 'Ürün Kodu',
            'name'              => 'Adı',
            'price'             => 'Fiyat',
            'qty'               => 'Miktar',
            'subtotal'          => 'Ara Toplam',
            'shipping-handling' => 'Kargo İşlem Ücreti',
            'tax'               => 'Vergi',
            'discount'          => 'İndirim',
            'grand-total'       => 'Toplam Tutar',
        ],
    ],
];
