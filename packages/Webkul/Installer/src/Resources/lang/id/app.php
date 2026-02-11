<?php

return [
    'seeders' => [
        'attribute' => [
            'attribute-families' => [
                'default' => 'Default',
            ],

            'attribute-groups' => [
                'description' => 'Deskripsi',
                'general' => 'Umum',
                'inventories' => 'Stok',
                'meta-description' => 'Meta Deskripsi',
                'price' => 'Harga',
                'rma' => 'RMA',
                'settings' => 'Pengaturan',
                'shipping' => 'Pengiriman',
            ],

            'attributes' => [
                'allow-rma' => 'Izinkan RMA',
                'brand' => 'Merek',
                'color' => 'Warna',
                'cost' => 'Biaya',
                'description' => 'Deskripsi',
                'featured' => 'Unggulan',
                'guest-checkout' => 'Checkout Tamu',
                'height' => 'Tinggi',
                'length' => 'Panjang',
                'manage-stock' => 'Kelola Stok',
                'meta-description' => 'Meta Deskripsi',
                'meta-keywords' => 'Meta Kata Kunci',
                'meta-title' => 'Meta Judul',
                'name' => 'Nama',
                'new' => 'Baru',
                'price' => 'Harga',
                'product-number' => 'Nomor Produk',
                'rma-rules' => 'Aturan RMA',
                'short-description' => 'Deskripsi Singkat',
                'size' => 'Ukuran',
                'sku' => 'SKU',
                'special-price' => 'Harga Spesial',
                'special-price-from' => 'Harga Spesial Mulai',
                'special-price-to' => 'Harga Spesial Sampai',
                'status' => 'Status',
                'tax-category' => 'Kategori Pajak',
                'url-key' => 'Kunci URL',
                'visible-individually' => 'Terlihat Secara Individual',
                'weight' => 'Berat',
                'width' => 'Lebar',
            ],

            'attribute-options' => [
                'black' => 'Hitam',
                'green' => 'Hijau',
                'l' => 'L',
                'm' => 'M',
                'red' => 'Merah',
                's' => 'S',
                'white' => 'Putih',
                'xl' => 'XL',
                'yellow' => 'Kuning',
            ],
        ],

        'category' => [
            'categories' => [
                'description' => 'Deskripsi Kategori Induk',
                'name' => 'Induk',
            ],
        ],

        'cms' => [
            'pages' => [
                'about-us' => [
                    'content' => 'Konten Halaman Tentang Kami',
                    'title' => 'Tentang Kami',
                ],

                'contact-us' => [
                    'content' => 'Konten Halaman Hubungi Kami',
                    'title' => 'Hubungi Kami',
                ],

                'customer-service' => [
                    'content' => 'Konten Halaman Layanan Pelanggan',
                    'title' => 'Layanan Pelanggan',
                ],

                'payment-policy' => [
                    'content' => 'Konten Halaman Kebijakan Pembayaran',
                    'title' => 'Kebijakan Pembayaran',
                ],

                'privacy-policy' => [
                    'content' => 'Konten Halaman Kebijakan Privasi',
                    'title' => 'Kebijakan Privasi',
                ],

                'refund-policy' => [
                    'content' => 'Konten Halaman Kebijakan Pengembalian Dana',
                    'title' => 'Kebijakan Pengembalian Dana',
                ],

                'return-policy' => [
                    'content' => 'Konten Halaman Kebijakan Pengembalian Barang',
                    'title' => 'Kebijakan Pengembalian Barang',
                ],

                'shipping-policy' => [
                    'content' => 'Konten Halaman Kebijakan Pengiriman',
                    'title' => 'Kebijakan Pengiriman',
                ],

                'terms-conditions' => [
                    'content' => 'Konten Halaman Syarat & Ketentuan',
                    'title' => 'Syarat & Ketentuan',
                ],

                'terms-of-use' => [
                    'content' => 'Konten Halaman Ketentuan Penggunaan',
                    'title' => 'Ketentuan Penggunaan',
                ],

                'whats-new' => [
                    'content' => 'Konten Halaman Apa yang Baru',
                    'title' => 'Apa yang Baru',
                ],
            ],
        ],

        'core' => [
            'channels' => [
                'meta-description' => 'Deskripsi meta toko demo',
                'meta-keywords' => 'Kata kunci meta toko demo',
                'meta-title' => 'Toko demo',
                'name' => 'Default',
            ],

            'currencies' => [
                'AED' => 'Dirham Uni Emirat Arab',
                'ARS' => 'Peso Argentina',
                'AUD' => 'Dolar Australia',
                'BDT' => 'Taka Bangladesh',
                'BHD' => 'Dinar Bahrain',
                'BRL' => 'Real Brasil',
                'CAD' => 'Dolar Kanada',
                'CHF' => 'Franc Swiss',
                'CLP' => 'Peso Cile',
                'CNY' => 'Yuan Tiongkok',
                'COP' => 'Peso Kolombia',
                'CZK' => 'Koruna Ceko',
                'DKK' => 'Krone Denmark',
                'DZD' => 'Dinar Aljazair',
                'EGP' => 'Pound Mesir',
                'EUR' => 'Euro',
                'FJD' => 'Dolar Fiji',
                'GBP' => 'Pound Sterling Inggris',
                'HKD' => 'Dolar Hong Kong',
                'HUF' => 'Forint Hungaria',
                'IDR' => 'Rupiah Indonesia',
                'ILS' => 'Shekel Baru Israel',
                'INR' => 'Rupee India',
                'JOD' => 'Dinar Yordania',
                'JPY' => 'Yen Jepang',
                'KRW' => 'Won Korea Selatan',
                'KWD' => 'Dinar Kuwait',
                'KZT' => 'Tenge Kazakhstan',
                'LBP' => 'Pound Lebanon',
                'LKR' => 'Rupee Sri Lanka',
                'LYD' => 'Dinar Libya',
                'MAD' => 'Dirham Maroko',
                'MUR' => 'Rupee Mauritius',
                'MXN' => 'Peso Meksiko',
                'MYR' => 'Ringgit Malaysia',
                'NGN' => 'Naira Nigeria',
                'NOK' => 'Krone Norwegia',
                'NPR' => 'Rupee Nepal',
                'NZD' => 'Dolar Selandia Baru',
                'OMR' => 'Rial Oman',
                'PAB' => 'Balboa Panama',
                'PEN' => 'Sol Peru',
                'PHP' => 'Peso Filipina',
                'PKR' => 'Rupee Pakistan',
                'PLN' => 'Zloty Polandia',
                'PYG' => 'Guarani Paraguay',
                'QAR' => 'Rial Qatar',
                'RON' => 'Leu Rumania',
                'RUB' => 'Rubel Rusia',
                'SAR' => 'Riyal Saudi',
                'SEK' => 'Krona Swedia',
                'SGD' => 'Dolar Singapura',
                'THB' => 'Baht Thailand',
                'TND' => 'Dinar Tunisia',
                'TRY' => 'Lira Turki',
                'TWD' => 'Dolar Taiwan Baru',
                'UAH' => 'Hryvnia Ukraina',
                'USD' => 'Dolar Amerika Serikat',
                'UZS' => 'Som Uzbekistan',
                'VEF' => 'Bolívar Venezuela',
                'VND' => 'Dong Vietnam',
                'XAF' => 'Franc CFA BEAC',
                'XOF' => 'Franc CFA BCEAO',
                'ZAR' => 'Rand Afrika Selatan',
                'ZMW' => 'Kwacha Zambia',
            ],

            'locales' => [
                'ar' => 'Arab',
                'bn' => 'Bengali',
                'ca' => 'Katalan',
                'de' => 'Jerman',
                'en' => 'Inggris',
                'es' => 'Spanyol',
                'fa' => 'Persia',
                'fr' => 'Prancis',
                'he' => 'Ibrani',
                'hi_IN' => 'Hindi',
                'id' => 'Indonesia',
                'it' => 'Italia',
                'ja' => 'Jepang',
                'nl' => 'Belanda',
                'pl' => 'Polandia',
                'pt_BR' => 'Portugis Brasil',
                'ru' => 'Rusia',
                'sin' => 'Sinhala',
                'tr' => 'Turki',
                'uk' => 'Ukraina',
                'zh_CN' => 'Tionghoa',
            ],
        ],

        'customer' => [
            'customer-groups' => [
                'general' => 'Umum',
                'guest' => 'Tamu',
                'wholesale' => 'Grosir',
            ],
        ],

        'inventory' => [
            'inventory-sources' => [
                'name' => 'Default',
            ],
        ],

        'shop' => [
            'theme-customizations' => [
                'all-products' => [
                    'name' => 'Semua Produk',

                    'options' => [
                        'title' => 'Semua Produk',
                    ],
                ],

                'bold-collections' => [
                    'content' => [
                        'btn-title' => 'Lihat Koleksi',
                        'description' => 'Perkenalkan Koleksi Berani Kami yang Baru! Tingkatkan gayamu dengan desain mencolok dan pernyataan penuh warna. Temukan pola yang mencuri perhatian dan warna-warna yang berani yang mendefinisikan ulang lemari pakaianmu. Bersiaplah untuk tampil luar biasa!',
                        'title' => 'Bersiaplah untuk Koleksi Berani terbaru kami!',
                    ],

                    'name' => 'Koleksi Berani',
                ],

                'bold-collections-2' => [
                    'content' => [
                        'btn-title' => 'Lihat Koleksi',
                        'description' => 'Koleksi Berani kami hadir untuk mendefinisikan ulang lemari pakaian Anda dengan desain tak kenal takut dan warna-warna cerah yang mencolok. Dari pola berani hingga nuansa kuat, ini adalah kesempatan Anda untuk keluar dari kebiasaan dan masuk ke luar biasa.',
                        'title' => 'Keluarkan Keberanian Anda dengan Koleksi Baru Kami!',
                    ],

                    'name' => 'Koleksi Berani',
                ],

                'booking-products' => [
                    'name' => 'Produk Pemesanan',

                    'options' => [
                        'title' => 'Pesan Tiket',
                    ],
                ],

                'categories-collections' => [
                    'name' => 'Koleksi Kategori',
                ],

                'featured-collections' => [
                    'name' => 'Koleksi Unggulan',

                    'options' => [
                        'title' => 'Produk Unggulan',
                    ],
                ],

                'footer-links' => [
                    'name' => 'Tautan Footer',

                    'options' => [
                        'about-us' => 'Tentang Kami',
                        'contact-us' => 'Hubungi Kami',
                        'customer-service' => 'Layanan Pelanggan',
                        'payment-policy' => 'Kebijakan Pembayaran',
                        'privacy-policy' => 'Kebijakan Privasi',
                        'refund-policy' => 'Kebijakan Pengembalian Dana',
                        'return-policy' => 'Kebijakan Pengembalian Barang',
                        'shipping-policy' => 'Kebijakan Pengiriman',
                        'terms-conditions' => 'Syarat & Ketentuan',
                        'terms-of-use' => 'Ketentuan Penggunaan',
                        'whats-new' => 'Apa yang Baru',
                    ],
                ],

                'game-container' => [
                    'content' => [
                        'sub-title-1' => 'Koleksi Kami',
                        'sub-title-2' => 'Koleksi Kami',
                        'title' => 'Serunya dengan tambahan baru kami!',
                    ],

                    'name' => 'Game Container',
                ],

                'image-carousel' => [
                    'name' => 'Karousel Gambar',

                    'sliders' => [
                        'title' => 'Bersiap untuk Koleksi Terbaru',
                    ],
                ],

                'new-products' => [
                    'name' => 'Produk Baru',

                    'options' => [
                        'title' => 'Produk Baru',
                    ],
                ],

                'offer-information' => [
                    'content' => [
                        'title' => 'Diskon HINGGA 40% untuk pesanan pertama kamu BELANJA SEKARANG',
                    ],

                    'name' => 'Informasi Penawaran',
                ],

                'services-content' => [
                    'description' => [
                        'emi-available-info' => 'Tersedia cicilan tanpa bunga untuk semua kartu kredit utama',
                        'free-shipping-info' => 'Nikmati pengiriman gratis untuk semua pesanan',
                        'product-replace-info' => 'Tersedia penggantian produk dengan mudah!',
                        'time-support-info' => 'Layanan dukungan 24/7 via chat dan email',
                    ],

                    'name' => 'Konten Layanan',

                    'title' => [
                        'emi-available' => 'Cicilan Tersedia',
                        'free-shipping' => 'Pengiriman Gratis',
                        'product-replace' => 'Penggantian Produk',
                        'time-support' => 'Dukungan 24/7',
                    ],
                ],

                'top-collections' => [
                    'content' => [
                        'sub-title-1' => 'Koleksi Kami',
                        'sub-title-2' => 'Koleksi Kami',
                        'sub-title-3' => 'Koleksi Kami',
                        'sub-title-4' => 'Koleksi Kami',
                        'sub-title-5' => 'Koleksi Kami',
                        'sub-title-6' => 'Koleksi Kami',
                        'title' => 'Serunya dengan tambahan baru kami!',
                    ],

                    'name' => 'Koleksi Terbaik',
                ],
            ],
        ],

        'user' => [
            'roles' => [
                'description' => 'Pengguna dengan peran ini memiliki akses penuh',
                'name' => 'Administrator',
            ],

            'users' => [
                'name' => 'Contoh',
            ],
        ],

        'sample-categories' => [
            'category-translation' => [
                '2' => [
                    'description' => '<p>Pria</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Pria',
                    'slug' => 'mens',
                    'url-path' => 'men',
                ],

                '3' => [
                    'description' => '<p>Anak-anak</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Anak-anak',
                    'slug' => 'kids',
                    'url-path' => 'kids',
                ],

                '4' => [
                    'description' => '<p>Wanita</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Wanita',
                    'slug' => 'womens',
                    'url-path' => 'woman',
                ],

                '5' => [
                    'description' => '<p>Pakaian Formal</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Pakaian Formal',
                    'slug' => 'formal-wear-men',
                    'url-path' => 'men/formal-wear-men',
                ],

                '6' => [
                    'description' => '<p>Pakaian Kasual</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Pakaian Kasual',
                    'slug' => 'casual-wear-men',
                    'url-path' => 'men/casual-wear-men',
                ],

                '7' => [
                    'description' => '<p>Pakaian Olahraga</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Pakaian Olahraga',
                    'slug' => 'active-wear',
                    'url-path' => 'men/active-wear',
                ],

                '8' => [
                    'description' => '<p>Alas Kaki</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Alas Kaki',
                    'slug' => 'footwear',
                    'url-path' => 'men/footwear',
                ],

                '9' => [
                    'description' => '<p>Pakaian Anak Perempuan</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Pakaian Anak Perempuan',
                    'slug' => 'formal-wear-female',
                    'url-path' => 'woman/formal-wear-female',
                ],

                '10' => [
                    'description' => '<p>Pakaian Anak Laki-laki</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Pakaian Anak Laki-laki',
                    'slug' => 'casual-wear-female',
                    'url-path' => 'woman/casual-wear-female',
                ],

                '11' => [
                    'description' => '<p>Sepatu Anak Perempuan</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Sepatu Anak Perempuan',
                    'slug' => 'active-wear-female',
                    'url-path' => 'woman/active-wear-female',
                ],

                '12' => [
                    'description' => '<p>Sepatu Anak Laki-laki</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Sepatu Anak Laki-laki',
                    'slug' => 'footwear-female',
                    'url-path' => 'woman/footwear-female',
                ],

                '13' => [
                    'description' => '<p>Pakaian Formal</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => 'Girls Clothing',
                    'name' => 'Pakaian Formal',
                    'slug' => 'girls-clothing',
                    'url-path' => 'kids/girls-clothing',
                ],

                '14' => [
                    'description' => '<p>Pakaian Kasual</p>',
                    'meta-description' => 'Boys Fashion',
                    'meta-keywords' => '',
                    'meta-title' => 'Boys Clothing',
                    'name' => 'Pakaian Kasual',
                    'slug' => 'boys-clothing',
                    'url-path' => 'kids/boys-clothing',
                ],

                '15' => [
                    'description' => '<p>Pakaian Olahraga</p>',
                    'meta-description' => 'Girls Fashionable Footwear Collection',
                    'meta-keywords' => '',
                    'meta-title' => 'Girls Footwear',
                    'name' => 'Pakaian Olahraga',
                    'slug' => 'girls-footwear',
                    'url-path' => 'kids/girls-footwear',
                ],

                '16' => [
                    'description' => '<p>Alas Kaki</p>',
                    'meta-description' => 'Boys Stylish Footwear Collection',
                    'meta-keywords' => '',
                    'meta-title' => 'Boys Footwear',
                    'name' => 'Alas Kaki',
                    'slug' => 'boys-footwear',
                    'url-path' => 'kids/boys-footwear',
                ],

                '17' => [
                    'description' => '<p>Kesehatan</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Kesehatan',
                    'slug' => 'wellness',
                    'url-path' => 'wellness',
                ],

                '18' => [
                    'description' => '<p>Tutorial Yoga yang Dapat Diunduh</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Tutorial Yoga yang Dapat Diunduh',
                    'slug' => 'downloadable-yoga-tutorial',
                    'url-path' => 'wellness/downloadable-yoga-tutorial',
                ],

                '19' => [
                    'description' => '<p>E-Book</p>',
                    'meta-description' => 'Books Collection',
                    'meta-keywords' => '',
                    'meta-title' => 'Books Collection',
                    'name' => 'E-Book',
                    'slug' => 'e-books',
                    'url-path' => 'wellness/e-books',
                ],

                '20' => [
                    'description' => '<p>Tiket Film</p>',
                    'meta-description' => 'Immerse yourself in the magic of 10 movies each month without extra charges. Valid nationwide with no blackout dates, this pass offers exclusive perks and concession discounts, making it a must-have for movie enthusiasts.',
                    'meta-keywords' => '',
                    'meta-title' => 'CineXperience Monthly Movie Pass',
                    'name' => 'Tiket Film',
                    'slug' => 'movie-pass',
                    'url-path' => 'wellness/movie-pass',
                ],

                '21' => [
                    'description' => '<p>Pemesanan</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Pemesanan',
                    'slug' => 'bookings',
                    'url-path' => '',
                ],

                '22' => [
                    'description' => '<p>Pemesanan Janji</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Pemesanan Janji',
                    'slug' => 'appointment-booking',
                    'url-path' => '',
                ],

                '23' => [
                    'description' => '<p>Pemesanan Acara</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Pemesanan Acara',
                    'slug' => 'event-booking',
                    'url-path' => '',
                ],

                '24' => [
                    'description' => '<p>Pemesanan Balai Komunitas</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Pemesanan Balai Komunitas',
                    'slug' => 'community-hall-bookings',
                    'url-path' => '',
                ],

                '25' => [
                    'description' => '<p>Pemesanan Meja</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Pemesanan Meja',
                    'slug' => 'table-booking',
                    'url-path' => '',
                ],

                '26' => [
                    'description' => '<p>Pemesanan Sewa</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Pemesanan Sewa',
                    'slug' => 'rental-booking',
                    'url-path' => '',
                ],

                '27' => [
                    'description' => '<p>Elektronik</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Elektronik',
                    'slug' => 'electronics',
                    'url-path' => '',
                ],

                '28' => [
                    'description' => '<p>Ponsel & Aksesori</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Ponsel & Aksesori',
                    'slug' => 'mobile-phones-accessories',
                    'url-path' => '',
                ],

                '29' => [
                    'description' => '<p>Laptop & Tablet</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Laptop & Tablet',
                    'slug' => 'laptops-tablets',
                    'url-path' => '',
                ],

                '30' => [
                    'description' => '<p>Perangkat Audio</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Perangkat Audio',
                    'slug' => 'audio-devices',
                    'url-path' => '',
                ],

                '31' => [
                    'description' => '<p>Rumah Pintar & Otomatisasi</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Rumah Pintar & Otomatisasi',
                    'slug' => 'smart-home-automation',
                    'url-path' => '',
                ],

                '32' => [
                    'description' => '<p>Rumah Tangga</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Rumah Tangga',
                    'slug' => 'household',
                    'url-path' => '',
                ],

                '33' => [
                    'description' => '<p>Peralatan Dapur</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Peralatan Dapur',
                    'slug' => 'kitchen-appliances',
                    'url-path' => '',
                ],

                '34' => [
                    'description' => '<p>Peralatan Masak & Makan</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Peralatan Masak & Makan',
                    'slug' => 'cookware-dining',
                    'url-path' => '',
                ],

                '35' => [
                    'description' => '<p>Furnitur & Dekorasi</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Furnitur & Dekorasi',
                    'slug' => 'furniture-decor',
                    'url-path' => '',
                ],

                '36' => [
                    'description' => '<p>Perlengkapan Kebersihan</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Perlengkapan Kebersihan',
                    'slug' => 'cleaning-supplies',
                    'url-path' => '',
                ],

                '37' => [
                    'description' => '<p>Buku & Alat Tulis</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Buku & Alat Tulis',
                    'slug' => 'books-stationery',
                    'url-path' => '',
                ],

                '38' => [
                    'description' => '<p>Fiksi & Non-Fiksi</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Fiksi & Non-Fiksi',
                    'slug' => 'fiction-non-fiction-books',
                    'url-path' => '',
                ],

                '39' => [
                    'description' => '<p>Pendidikan & Akademik</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Pendidikan & Akademik',
                    'slug' => 'educational-academic',
                    'url-path' => '',
                ],

                '40' => [
                    'description' => '<p>Perlengkapan Kantor</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Perlengkapan Kantor',
                    'slug' => 'office-supplies',
                    'url-path' => '',
                ],

                '41' => [
                    'description' => '<p>Bahan Seni & Kerajinan</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Bahan Seni & Kerajinan',
                    'slug' => 'art-craft-materials',
                    'url-path' => '',
                ],
            ],
        ],
    ],

    'installer' => [
        'middleware' => [
            'already-installed' => 'Aplikasi sudah terinstall.',
        ],

        'index' => [
            'create-administrator' => [
                'admin' => 'Admin',
                'bagisto' => 'Bagisto',
                'confirm-password' => 'Konfirmasi Kata Sandi',
                'email' => 'Email',
                'email-address' => 'admin@example.com',
                'password' => 'Kata Sandi',
                'title' => 'Buat Administrator',
            ],

            'environment-configuration' => [
                'algerian-dinar' => 'Dinar Algeria (DZD)',
                'allowed-currencies' => 'Mata Uang yang Diperbolehkan',
                'allowed-locales' => 'Locale yang Diperbolehkan',
                'application-name' => 'Nama Aplikasi',
                'argentine-peso' => 'Peso Argentina (ARS)',
                'australian-dollar' => 'Dolar Australia (AUD)',
                'bagisto' => 'Bagisto',
                'bangladeshi-taka' => 'Taka Bangladesh (BDT)',
                'bahraini-dinar' => 'Dinar Bahrain (BHD)',
                'brazilian-real' => 'Real Brasil (BRL)',
                'british-pound-sterling' => 'Pound Sterling Inggris (GBP)',
                'canadian-dollar' => 'Dolar Kanada (CAD)',
                'cfa-franc-bceao' => 'Franc CFA BCEAO (XOF)',
                'cfa-franc-beac' => 'Franc CFA BEAC (XAF)',
                'chilean-peso' => 'Peso Chili (CLP)',
                'chinese-yuan' => 'Yuan Tiongkok (CNY)',
                'colombian-peso' => 'Peso Kolombia (COP)',
                'czech-koruna' => 'Koruna Ceko (CZK)',
                'danish-krone' => 'Krone Denmark (DKK)',
                'database-connection' => 'Koneksi Database',
                'database-hostname' => 'Nama Host Database',
                'database-name' => 'Nama Database',
                'database-password' => 'Kata Sandi Database',
                'database-port' => 'Port Database',
                'database-prefix' => 'Prefiks Database',
                'database-prefix-help' => 'Prefiks harus sepanjang 4 karakter dan hanya dapat berisi huruf, angka, dan garis bawah.',
                'database-username' => 'Nama Pengguna Database',
                'default-currency' => 'Mata Uang Default',
                'default-locale' => 'Locale Default',
                'default-timezone' => 'Zona Waktu Default',
                'default-url' => 'URL Default',
                'default-url-link' => 'https://localhost',
                'egyptian-pound' => 'Pound Mesir (EGP)',
                'euro' => 'Euro (EUR)',
                'fijian-dollar' => 'Dolar Fiji (FJD)',
                'hong-kong-dollar' => 'Dolar Hong Kong (HKD)',
                'hungarian-forint' => 'Forint Hongaria (HUF)',
                'indian-rupee' => 'Rupee India (INR)',
                'indonesian-rupiah' => 'Rupiah Indonesia (IDR)',
                'israeli-new-shekel' => 'Shekel Baru Israel (ILS)',
                'japanese-yen' => 'Yen Jepang (JPY)',
                'jordanian-dinar' => 'Dinar Yordania (JOD)',
                'kazakhstani-tenge' => 'Tenge Kazakhstan (KZT)',
                'kuwaiti-dinar' => 'Dinar Kuwait (KWD)',
                'lebanese-pound' => 'Pound Lebanon (LBP)',
                'libyan-dinar' => 'Dinar Libya (LYD)',
                'malaysian-ringgit' => 'Ringgit Malaysia (MYR)',
                'mauritian-rupee' => 'Rupee Mauritius (MUR)',
                'mexican-peso' => 'Peso Meksiko (MXN)',
                'moroccan-dirham' => 'Dirham Maroko (MAD)',
                'mysql' => 'Mysql',
                'nepalese-rupee' => 'Rupee Nepal (NPR)',
                'new-taiwan-dollar' => 'Dolar Taiwan Baru (TWD)',
                'new-zealand-dollar' => 'Dolar Selandia Baru (NZD)',
                'nigerian-naira' => 'Naira Nigeria (NGN)',
                'norwegian-krone' => 'Krone Norwegia (NOK)',
                'omani-rial' => 'Rial Oman (OMR)',
                'pakistani-rupee' => 'Rupee Pakistan (PKR)',
                'panamanian-balboa' => 'Balboa Panama (PAB)',
                'paraguayan-guarani' => 'Guarani Paraguay (PYG)',
                'peruvian-nuevo-sol' => 'Nuevo Sol Peru (PEN)',
                'pgsql' => 'pgSQL',
                'philippine-peso' => 'Peso Filipina (PHP)',
                'polish-zloty' => 'Zloty Polandia (PLN)',
                'qatari-rial' => 'Rial Qatar (QAR)',
                'romanian-leu' => 'Leu Rumania (RON)',
                'russian-ruble' => 'Rubel Rusia (RUB)',
                'saudi-riyal' => 'Riyal Saudi (SAR)',
                'select-timezone' => 'Pilih Zona Waktu',
                'singapore-dollar' => 'Dolar Singapura (SGD)',
                'south-african-rand' => 'Rand Afrika Selatan (ZAR)',
                'south-korean-won' => 'Won Korea Selatan (KRW)',
                'sqlsrv' => 'SQLSRV',
                'sri-lankan-rupee' => 'Rupee Sri Lanka (LKR)',
                'swedish-krona' => 'Krona Swedia (SEK)',
                'swiss-franc' => 'Franc Swiss (CHF)',
                'thai-baht' => 'Baht Thailand (THB)',
                'title' => 'Konfigurasi Toko',
                'tunisian-dinar' => 'Dinar Tunisia (TND)',
                'turkish-lira' => 'Lira Turki (TRY)',
                'ukrainian-hryvnia' => 'Hryvnia Ukraina (UAH)',
                'united-arab-emirates-dirham' => 'Dirham Uni Emirat Arab (AED)',
                'united-states-dollar' => 'Dolar Amerika Serikat (USD)',
                'uzbekistani-som' => 'Som Uzbekistan (UZS)',
                'venezuelan-bolívar' => 'Bolívar Venezuela (VEF)',
                'vietnamese-dong' => 'Dong Vietnam (VND)',
                'warning-message' => 'Perhatian! Pengaturan bahasa sistem default dan mata uang default bersifat permanen dan tidak dapat diubah setelah disetel.',
                'zambian-kwacha' => 'Kwacha Zambia (ZMW)',
            ],

            'sample-products' => [
                'no' => 'Tidak',
                'note' => 'Catatan: Waktu pengindeksan tergantung pada jumlah lokal yang dipilih. Proses ini dapat memakan waktu hingga 2 menit untuk diselesaikan.',
                'sample-products' => 'Produk Sampel',
                'title' => 'Produk Sampel',
                'yes' => 'Ya',
            ],

            'installation-processing' => [
                'bagisto' => 'Instalasi Bagisto',
                'bagisto-info' => 'Membuat tabel database, ini dapat memakan waktu beberapa saat',
                'title' => 'Instalasi',
            ],

            'installation-completed' => [
                'admin-panel' => 'Panel Admin',
                'bagisto-forums' => 'Forum Bagisto',
                'customer-panel' => 'Panel Pelanggan',
                'explore-bagisto-extensions' => 'Jelajahi Ekstensi Bagisto',
                'title' => 'Instalasi Selesai',
                'title-info' => 'Bagisto berhasil diinstal di sistem Anda.',
            ],

            'ready-for-installation' => [
                'create-database-tables' => 'Buat tabel database',
                'drop-existing-tables' => 'Hapus tabel yang ada',
                'install' => 'Instalasi',
                'install-info' => 'Bagisto untuk Instalasi',
                'install-info-button' => 'Klik tombol di bawah untuk',
                'populate-database-tables' => 'Isi tabel database',
                'start-installation' => 'Mulai Instalasi',
                'title' => 'Siap untuk Instalasi',
            ],

            'start' => [
                'locale' => 'Locale',
                'main' => 'Mulai',
                'select-locale' => 'Pilih Locale',
                'title' => 'Instalasi Bagisto Anda',
                'welcome-title' => 'Selamat datang di Bagisto',
            ],

            'server-requirements' => [
                'calendar' => 'Calendar',
                'ctype' => 'cType',
                'curl' => 'cURL',
                'dom' => 'dom',
                'fileinfo' => 'fileInfo',
                'filter' => 'Filter',
                'gd' => 'GD',
                'hash' => 'Hash',
                'intl' => 'intl',
                'json' => 'JSON',
                'mbstring' => 'mbstring',
                'openssl' => 'openssl',
                'pcre' => 'pcre',
                'pdo' => 'pdo',
                'php' => 'PHP',
                'php-version' => ':version atau yang lebih tinggi',
                'session' => 'session',
                'title' => 'Persyaratan Sistem',
                'tokenizer' => 'tokenizer',
                'xml' => 'XML',
            ],

            'arabic' => 'Arab',
            'back' => 'Kembali',
            'bagisto' => 'Bagisto',
            'bagisto-info' => 'Proyek Komunitas oleh',
            'bagisto-logo' => 'Logo Bagisto',
            'bengali' => 'Bengali',
            'catalan' => 'Katalan',
            'chinese' => 'Cina',
            'continue' => 'Lanjutkan',
            'dutch' => 'Belanda',
            'english' => 'Inggris',
            'french' => 'Perancis',
            'german' => 'Jerman',
            'hebrew' => 'Ibrani',
            'hindi' => 'Hindi',
            'indonesian' => 'Indonesia',
            'installation-description' => 'Instalasi Bagisto umumnya melibatkan beberapa langkah. Berikut adalah gambaran umum proses instalasi untuk Bagisto',
            'installation-info' => 'Kami senang melihat Anda di sini!',
            'installation-title' => 'Selamat datang di Instalasi',
            'italian' => 'Italia',
            'japanese' => 'Jepang',
            'persian' => 'Persia',
            'polish' => 'Polandia',
            'portuguese' => 'Portugis Brasil',
            'russian' => 'Rusia',
            'sinhala' => 'Sinhala',
            'spanish' => 'Spanyol',
            'title' => 'Pemasang Bagisto',
            'turkish' => 'Turki',
            'ukrainian' => 'Ukraina',
            'webkul' => 'Webkul',
        ],
    ],
];
