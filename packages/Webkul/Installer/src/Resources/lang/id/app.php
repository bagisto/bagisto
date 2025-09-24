<?php

return [
    'seeders' => [
        'attribute' => [
            'attribute-families' => [
                'default' => 'Default',
            ],

            'attribute-groups' => [
                'description'       => 'Deskripsi',
                'general'           => 'Umum',
                'inventories'       => 'Stok',
                'meta-description'  => 'Meta Deskripsi',
                'price'             => 'Harga',
                'settings'          => 'Pengaturan',
                'shipping'          => 'Pengiriman',
            ],

            'attributes' => [
                'brand'                => 'Merek',
                'color'                => 'Warna',
                'cost'                 => 'Biaya',
                'description'          => 'Deskripsi',
                'featured'             => 'Unggulan',
                'guest-checkout'       => 'Checkout Tamu',
                'height'               => 'Tinggi',
                'length'               => 'Panjang',
                'manage-stock'         => 'Kelola Stok',
                'meta-description'     => 'Meta Deskripsi',
                'meta-keywords'        => 'Meta Kata Kunci',
                'meta-title'           => 'Meta Judul',
                'name'                 => 'Nama',
                'new'                  => 'Baru',
                'price'                => 'Harga',
                'product-number'       => 'Nomor Produk',
                'short-description'    => 'Deskripsi Singkat',
                'size'                 => 'Ukuran',
                'sku'                  => 'SKU',
                'special-price'        => 'Harga Spesial',
                'special-price-from'   => 'Harga Spesial Mulai',
                'special-price-to'     => 'Harga Spesial Sampai',
                'status'               => 'Status',
                'tax-category'         => 'Kategori Pajak',
                'url-key'              => 'Kunci URL',
                'visible-individually' => 'Terlihat Secara Individual',
                'weight'               => 'Berat',
                'width'                => 'Lebar',
            ],

            'attribute-options' => [
                'black'  => 'Hitam',
                'green'  => 'Hijau',
                'l'      => 'L',
                'm'      => 'M',
                'red'    => 'Merah',
                's'      => 'S',
                'white'  => 'Putih',
                'xl'     => 'XL',
                'yellow' => 'Kuning',
            ],
        ],

        'category' => [
            'categories' => [
                'description' => 'Deskripsi Kategori Induk',
                'name'        => 'Induk',
            ],
        ],

        'cms' => [
            'pages' => [
                'about-us' => [
                    'content' => 'Konten Halaman Tentang Kami',
                    'title'   => 'Tentang Kami',
                ],

                'contact-us' => [
                    'content' => 'Konten Halaman Hubungi Kami',
                    'title'   => 'Hubungi Kami',
                ],

                'customer-service' => [
                    'content' => 'Konten Halaman Layanan Pelanggan',
                    'title'   => 'Layanan Pelanggan',
                ],

                'payment-policy' => [
                    'content' => 'Konten Halaman Kebijakan Pembayaran',
                    'title'   => 'Kebijakan Pembayaran',
                ],

                'privacy-policy' => [
                    'content' => 'Konten Halaman Kebijakan Privasi',
                    'title'   => 'Kebijakan Privasi',
                ],

                'refund-policy' => [
                    'content' => 'Konten Halaman Kebijakan Pengembalian Dana',
                    'title'   => 'Kebijakan Pengembalian Dana',
                ],

                'return-policy' => [
                    'content' => 'Konten Halaman Kebijakan Pengembalian Barang',
                    'title'   => 'Kebijakan Pengembalian Barang',
                ],

                'shipping-policy' => [
                    'content' => 'Konten Halaman Kebijakan Pengiriman',
                    'title'   => 'Kebijakan Pengiriman',
                ],

                'terms-conditions' => [
                    'content' => 'Konten Halaman Syarat & Ketentuan',
                    'title'   => 'Syarat & Ketentuan',
                ],

                'terms-of-use' => [
                    'content' => 'Konten Halaman Ketentuan Penggunaan',
                    'title'   => 'Ketentuan Penggunaan',
                ],

                'whats-new' => [
                    'content' => 'Konten Halaman Apa yang Baru',
                    'title'   => 'Apa yang Baru',
                ],
            ],
        ],

        'core' => [
            'channels' => [
                'name'             => 'Default',
                'meta-title'       => 'Toko demo',
                'meta-keywords'    => 'Kata kunci meta toko demo',
                'meta-description' => 'Deskripsi meta toko demo',
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
                'ar'    => 'Arab',
                'bn'    => 'Bengali',
                'ca'    => 'Katalan',
                'de'    => 'Jerman',
                'en'    => 'Inggris',
                'es'    => 'Spanyol',
                'fa'    => 'Persia',
                'fr'    => 'Prancis',
                'he'    => 'Ibrani',
                'hi_IN' => 'Hindi',
                'id'    => 'Indonesia',
                'it'    => 'Italia',
                'ja'    => 'Jepang',
                'nl'    => 'Belanda',
                'pl'    => 'Polandia',
                'pt_BR' => 'Portugis Brasil',
                'ru'    => 'Rusia',
                'sin'   => 'Sinhala',
                'tr'    => 'Turki',
                'uk'    => 'Ukraina',
                'zh_CN' => 'Tionghoa',
            ],
        ],

        'customer' => [
            'customer-groups' => [
                'general'   => 'Umum',
                'guest'     => 'Tamu',
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
                        'btn-title'   => 'Lihat Koleksi',
                        'description' => 'Perkenalkan Koleksi Berani Kami yang Baru! Tingkatkan gayamu dengan desain mencolok dan pernyataan penuh warna. Temukan pola yang mencuri perhatian dan warna-warna yang berani yang mendefinisikan ulang lemari pakaianmu. Bersiaplah untuk tampil luar biasa!',
                        'title'       => 'Bersiaplah untuk Koleksi Berani terbaru kami!',
                    ],

                    'name' => 'Koleksi Berani',
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
                        'about-us'         => 'Tentang Kami',
                        'contact-us'       => 'Hubungi Kami',
                        'customer-service' => 'Layanan Pelanggan',
                        'payment-policy'   => 'Kebijakan Pembayaran',
                        'privacy-policy'   => 'Kebijakan Privasi',
                        'refund-policy'    => 'Kebijakan Pengembalian Dana',
                        'return-policy'    => 'Kebijakan Pengembalian Barang',
                        'shipping-policy'  => 'Kebijakan Pengiriman',
                        'terms-conditions' => 'Syarat & Ketentuan',
                        'terms-of-use'     => 'Ketentuan Penggunaan',
                        'whats-new'        => 'Apa yang Baru',
                    ],
                ],

                'game-container' => [
                    'content' => [
                        'sub-title-1' => 'Koleksi Kami',
                        'sub-title-2' => 'Koleksi Kami',
                        'title'       => 'Serunya dengan tambahan baru kami!',
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
                        'emi-available-info'   => 'Tersedia cicilan tanpa bunga untuk semua kartu kredit utama',
                        'free-shipping-info'   => 'Nikmati pengiriman gratis untuk semua pesanan',
                        'product-replace-info' => 'Tersedia penggantian produk dengan mudah!',
                        'time-support-info'    => 'Layanan dukungan 24/7 via chat dan email',
                    ],

                    'name' => 'Konten Layanan',

                    'title' => [
                        'emi-available'   => 'Cicilan Tersedia',
                        'free-shipping'   => 'Pengiriman Gratis',
                        'product-replace' => 'Penggantian Produk',
                        'time-support'    => 'Dukungan 24/7',
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
                        'title'       => 'Serunya dengan tambahan baru kami!',
                    ],

                    'name' => 'Koleksi Terbaik',
                ],
            ],
        ],

        'user' => [
            'roles' => [
                'description' => 'Pengguna dengan peran ini memiliki akses penuh',
                'name'        => 'Administrator',
            ],

            'users' => [
                'name' => 'Contoh',
            ],
        ],

        'sample-categories' => [
            'category-translation' => [
                '2' => [
                    'description'      => 'Deskripsi Kategori Pria',
                    'meta-description' => 'Meta Deskripsi Kategori Pria',
                    'meta-keywords'    => 'Meta Keyword Kategori Pria',
                    'meta-title'       => 'Meta Judul Kategori Pria',
                    'name'             => 'Pria',
                    'slug'             => 'pria',
                ],

                '3' => [
                    'description'      => 'Deskripsi Kategori Pakaian Musim Dingin',
                    'meta-description' => 'Meta Deskripsi Kategori Pakaian Musim Dingin',
                    'meta-keywords'    => 'Meta Keyword Kategori Pakaian Musim Dingin',
                    'meta-title'       => 'Meta Judul Kategori Pakaian Musim Dingin',
                    'name'             => 'Pakaian Musim Dingin',
                    'slug'             => 'pakaian-musim-dingin',
                ],
            ],
        ],

        'sample-products' => [
            'product-flat' => [
                '1' => [
                    'description'       => 'Kupluk Rajut Arctic Cozy adalah solusi andalan Anda untuk tetap hangat, nyaman, dan bergaya selama bulan-bulan yang lebih dingin. Dibuat dari campuran rajutan akrilik yang lembut dan tahan lama, kupluk ini dirancang untuk memberikan kenyamanan dan pas di kepala. Desain klasiknya cocok untuk pria maupun wanita, menawarkan aksesori serbaguna yang melengkapi berbagai gaya. Baik Anda bepergian untuk hari santai di kota atau menjelajahi alam bebas, kupluk ini menambahkan sentuhan kenyamanan dan kehangatan pada penampilan Anda. Bahan yang lembut dan breathable memastikan Anda tetap nyaman tanpa mengorbankan gaya. Kupluk Rajut Arctic Cozy bukan hanya aksesori; ini adalah pernyataan mode musim dingin. Kesederhanaannya membuatnya mudah dipadukan dengan berbagai pakaian, menjadikannya item pokok dalam lemari pakaian musim dingin Anda. Ideal sebagai hadiah atau untuk memanjakan diri sendiri, kupluk ini adalah tambahan yang bijaksana untuk setiap ansambel musim dingin. Ini adalah aksesori serbaguna yang lebih dari sekadar fungsionalitas, menambahkan sentuhan kehangatan dan gaya pada penampilan Anda. Rangkullah esensi musim dingin dengan Kupluk Rajut Arctic Cozy. Baik Anda menikmati hari santai di luar atau menghadapi berbagai elemen cuaca, biarkan kupluk ini menjadi teman Anda untuk kenyamanan dan gaya. Tingkatkan koleksi pakaian musim dingin Anda dengan aksesori klasik ini yang dengan mudah memadukan kehangatan dengan selera mode yang tak lekang oleh waktu.',
                    'meta-description'  => 'deskripsi meta',
                    'meta-keywords'     => 'meta1, meta2, meta3',
                    'meta-title'        => 'Judul Meta',
                    'name'              => 'Kupluk Rajut Unisex Arctic Cozy',
                    'short-description' => 'Hadapi hari-hari dingin dengan penuh gaya bersama Kupluk Rajut Arctic Cozy kami. Dibuat dari campuran akrilik yang lembut dan tahan lama, kupluk klasik ini menawarkan kehangatan dan keserbagunaan. Cocok untuk pria maupun wanita, ini adalah aksesori ideal untuk pakaian kasual atau luar ruangan. Tingkatkan koleksi pakaian musim dingin Anda atau berikan hadiah kepada seseorang yang istimewa dengan kupluk esensial ini.',
                ],

                '2' => [
                    'description'       => 'Syal Musim Dingin Arctic Bliss lebih dari sekadar aksesori cuaca dingin; ini adalah pernyataan kehangatan, kenyamanan, dan gaya untuk musim dingin. Dibuat dengan cermat dari campuran mewah akrilik dan wol, syal ini dirancang untuk membuat Anda tetap nyaman dan hangat bahkan dalam suhu terdingin sekalipun. Tekstur yang lembut dan mewah tidak hanya memberikan insulasi terhadap dingin tetapi juga menambahkan sentuhan kemewahan pada koleksi pakaian musim dingin Anda. Desain Syal Musim Dingin Arctic Bliss bergaya dan serbaguna, menjadikannya tambahan yang sempurna untuk berbagai pakaian musim dingin. Baik Anda berdandan untuk acara khusus atau menambahkan lapisan chic pada penampilan sehari-hari Anda, syal ini melengkapi gaya Anda dengan mudah. Panjang ekstra syal menawarkan pilihan gaya yang dapat disesuaikan. Lilitkan untuk kehangatan tambahan, sampirkan secara longgar untuk tampilan kasual, atau bereksperimenlah dengan berbagai simpul untuk mengekspresikan gaya unik Anda. Keserbagunaan ini menjadikannya aksesori wajib untuk musim dingin. Mencari hadiah yang sempurna? Syal Musim Dingin Arctic Bliss adalah pilihan ideal. Baik Anda mengejutkan orang terkasih atau memanjakan diri sendiri, syal ini adalah hadiah abadi dan praktis yang akan dihargai sepanjang bulan-bulan musim dingin. Sambut musim dingin dengan Syal Musim Dingin Arctic Bliss, di mana kehangatan bertemu gaya dalam harmoni yang sempurna. Tingkatkan koleksi pakaian musim dingin Anda dengan aksesori esensial ini yang tidak hanya membuat Anda tetap hangat tetapi juga menambahkan sentuhan kecanggihan pada ansambel cuaca dingin Anda.',
                    'meta-description'  => 'deskripsi meta',
                    'meta-keywords'     => 'meta1, meta2, meta3',
                    'meta-title'        => 'Judul Meta',
                    'name'              => 'Syal Musim Dingin Arctic Bliss Penuh Gaya',
                    'short-description' => 'Rasakan dekapan kehangatan dan gaya dengan Syal Musim Dingin Arctic Bliss kami. Dibuat dari campuran mewah akrilik dan wol, syal yang nyaman ini dirancang untuk membuat Anda tetap hangat selama hari-hari terdingin. Desainnya yang penuh gaya dan serbaguna, dikombinasikan dengan panjang ekstra, menawarkan pilihan gaya yang dapat disesuaikan. Tingkatkan koleksi pakaian musim dingin Anda atau bahagiakan seseorang yang istimewa dengan aksesori musim dingin esensial ini.',
                ],

                '3' => [
                    'description'       => 'Memperkenalkan Sarung Tangan Musim Dingin Layar Sentuh Arctic – tempat kehangatan, gaya, dan konektivitas bertemu untuk meningkatkan pengalaman musim dingin Anda. Dibuat dari akrilik berkualitas tinggi, sarung tangan ini dirancang untuk memberikan kehangatan dan daya tahan yang luar biasa. Ujung jari yang kompatibel dengan layar sentuh memungkinkan Anda tetap terhubung tanpa memaparkan tangan Anda pada dingin. Jawab panggilan, kirim pesan, dan navigasikan perangkat Anda dengan mudah, semuanya sambil menjaga tangan Anda tetap hangat. Lapisan berinsulasi menambahkan lapisan kenyamanan ekstra, menjadikan sarung tangan ini pilihan utama Anda untuk menghadapi dinginnya musim dingin. Baik Anda sedang dalam perjalanan, melakukan berbagai urusan, atau menikmati aktivitas luar ruangan, sarung tangan ini memberikan kehangatan dan perlindungan yang Anda butuhkan. Manset elastis memastikan sarung tangan pas dan aman, mencegah masuknya angin dingin dan menjaga sarung tangan tetap di tempatnya selama aktivitas harian Anda. Desain yang penuh gaya menambahkan sentuhan elegan pada ansambel musim dingin Anda, menjadikan sarung tangan ini modis sekaligus fungsional. Ideal sebagai hadiah atau untuk memanjakan diri sendiri, Sarung Tangan Musim Dingin Layar Sentuh Arctic adalah aksesori wajib bagi individu modern. Ucapkan selamat tinggal pada ketidaknyamanan melepas sarung tangan Anda untuk menggunakan perangkat dan rangkullah perpaduan sempurna antara kehangatan, gaya, dan konektivitas. Tetap terhubung, tetap hangat, dan tetap bergaya dengan Sarung Tangan Musim Dingin Layar Sentuh Arctic – teman andal Anda untuk menaklukkan musim dingin dengan percaya diri.',
                    'meta-description'  => 'deskripsi meta',
                    'meta-keywords'     => 'meta1, meta2, meta3',
                    'meta-title'        => 'Judul Meta',
                    'name'              => 'Sarung Tangan Musim Dingin Arctic Layar Sentuh',
                    'short-description' => 'Tetap terhubung dan hangat dengan Sarung Tangan Musim Dingin Layar Sentuh Arctic kami. Sarung tangan ini tidak hanya dibuat dari akrilik berkualitas tinggi untuk kehangatan dan daya tahan tetapi juga dilengkapi desain yang kompatibel dengan layar sentuh. Dengan lapisan berinsulasi, manset elastis agar pas dan aman, serta tampilan yang penuh gaya, sarung tangan ini sempurna untuk penggunaan sehari-hari dalam kondisi dingin.',
                ],

                '4' => [
                    'description'       => 'Memperkenalkan Kaus Kaki Campuran Wol Arctic Warmth – teman penting Anda untuk kaki yang nyaman dan hangat selama musim dingin. Dibuat dari campuran premium wol Merino, akrilik, nilon, dan spandeks, kaus kaki ini dirancang untuk memberikan kehangatan dan kenyamanan tak tertandingi. Campuran wol memastikan kaki Anda tetap hangat bahkan dalam suhu terdingin, menjadikan kaus kaki ini pilihan sempurna untuk petualangan musim dingin atau sekadar bersantai di rumah. Tekstur kaus kaki yang lembut dan nyaman menawarkan sensasi mewah di kulit Anda. Ucapkan selamat tinggal pada kaki yang dingin saat Anda merasakan kehangatan mewah yang diberikan oleh kaus kaki campuran wol ini. Didesain untuk daya tahan, kaus kaki ini memiliki tumit dan ujung kaki yang diperkuat, menambahkan kekuatan ekstra pada area yang sering aus. Ini memastikan kaus kaki Anda tahan lama, memberikan kenyamanan dan kehangatan yang awet. Sifat bahan yang breathable mencegah panas berlebih, memungkinkan kaki Anda tetap nyaman dan kering sepanjang hari. Baik Anda pergi ke luar untuk pendakian musim dingin atau bersantai di dalam ruangan, kaus kaki ini menawarkan keseimbangan sempurna antara kehangatan dan sirkulasi udara. Serbaguna dan penuh gaya, kaus kaki campuran wol ini cocok untuk berbagai kesempatan. Padukan dengan sepatu bot favorit Anda untuk tampilan musim dingin yang modis atau kenakan di sekitar rumah untuk kenyamanan maksimal. Tingkatkan koleksi pakaian musim dingin Anda dan prioritaskan kenyamanan dengan Kaus Kaki Campuran Wol Arctic Warmth. Manjakan kaki Anda dengan kemewahan yang layak mereka dapatkan dan masuki dunia kenyamanan yang bertahan sepanjang musim.',
                    'meta-description'  => 'deskripsi meta',
                    'meta-keywords'     => 'meta1, meta2, meta3',
                    'meta-title'        => 'Judul Meta',
                    'name'              => 'Kaus Kaki Campuran Wol Arctic Warmth',
                    'short-description' => 'Rasakan kehangatan dan kenyamanan tak tertandingi dari Kaus Kaki Campuran Wol Arctic Warmth kami. Dibuat dari campuran wol Merino, akrilik, nilon, dan spandeks, kaus kaki ini menawarkan kenyamanan tertinggi untuk cuaca dingin. Dengan tumit dan ujung kaki yang diperkuat untuk daya tahan, kaus kaki serbaguna dan penuh gaya ini sempurna untuk berbagai kesempatan.',
                ],

                '5' => [
                    'description'       => 'Memperkenalkan Paket Aksesori Musim Dingin Arctic Frost, solusi andalan Anda untuk tetap hangat, bergaya, dan terhubung selama hari-hari musim dingin yang dingin. Set yang dikurasi dengan cermat ini menyatukan empat aksesori musim dingin esensial untuk menciptakan ansambel yang harmonis. Syal mewah, ditenun dari campuran akrilik dan wol, tidak hanya menambah lapisan kehangatan tetapi juga menghadirkan sentuhan elegan pada koleksi pakaian musim dingin Anda. Kupluk rajut lembut, dibuat dengan hati-hati, menjanjikan untuk membuat Anda tetap nyaman sambil menambahkan sentuhan modis pada penampilan Anda. Tapi tidak berhenti di situ – paket kami juga menyertakan sarung tangan yang kompatibel dengan layar sentuh. Tetap terhubung tanpa mengorbankan kehangatan saat Anda menavigasi perangkat Anda dengan mudah. Baik Anda menjawab panggilan, mengirim pesan, atau mengabadikan momen musim dingin di ponsel cerdas Anda, sarung tangan ini memastikan kenyamanan tanpa mengurangi gaya. Tekstur kaus kaki yang lembut dan nyaman menawarkan sensasi mewah di kulit Anda. Ucapkan selamat tinggal pada kaki yang dingin saat Anda merasakan kehangatan mewah yang diberikan oleh kaus kaki campuran wol ini. Paket Aksesori Musim Dingin Arctic Frost bukan hanya tentang fungsionalitas; ini adalah pernyataan mode musim dingin. Setiap item dirancang tidak hanya untuk melindungi Anda dari dingin tetapi juga untuk meningkatkan gaya Anda selama musim dingin. Bahan yang dipilih untuk paket ini memprioritaskan daya tahan dan kenyamanan, memastikan Anda dapat menikmati keindahan musim dingin dengan penuh gaya. Baik Anda memanjakan diri sendiri atau mencari hadiah yang sempurna, Paket Aksesori Musim Dingin Arctic Frost adalah pilihan serbaguna. Bahagiakan seseorang yang istimewa selama musim liburan atau tingkatkan koleksi pakaian musim dingin Anda sendiri dengan ansambel yang bergaya dan fungsional ini. Hadapi udara dingin dengan percaya diri, mengetahui bahwa Anda memiliki aksesori yang sempurna untuk membuat Anda tetap hangat dan chic.',
                    'meta-description'  => 'deskripsi meta',
                    'meta-keywords'     => 'meta1, meta2, meta3',
                    'meta-title'        => 'Judul Meta',
                    'name'              => 'Paket Aksesori Musim Dingin Arctic Frost',
                    'short-description' => 'Hadapi dinginnya musim dingin dengan Paket Aksesori Musim Dingin Arctic Frost kami. Set pilihan ini mencakup syal mewah, kupluk yang nyaman, sarung tangan yang kompatibel dengan layar sentuh, dan Kaus Kaki Campuran Wol. Penuh gaya dan fungsional, ansambel ini dibuat dari bahan berkualitas tinggi, memastikan daya tahan dan kenyamanan. Tingkatkan koleksi pakaian musim dingin Anda atau bahagiakan seseorang yang istimewa dengan pilihan hadiah yang sempurna ini.',
                ],

                '6' => [
                    'description'       => 'Memperkenalkan Paket Aksesori Musim Dingin Arctic Frost, solusi andalan Anda untuk tetap hangat, bergaya, dan terhubung selama hari-hari musim dingin yang dingin. Set yang dikurasi dengan cermat ini menyatukan empat aksesori musim dingin esensial untuk menciptakan ansambel yang harmonis. Syal mewah, ditenun dari campuran akrilik dan wol, tidak hanya menambah lapisan kehangatan tetapi juga menghadirkan sentuhan elegan pada koleksi pakaian musim dingin Anda. Kupluk rajut lembut, dibuat dengan hati-hati, menjanjikan untuk membuat Anda tetap nyaman sambil menambahkan sentuhan modis pada penampilan Anda. Tapi tidak berhenti di situ – paket kami juga menyertakan sarung tangan yang kompatibel dengan layar sentuh. Tetap terhubung tanpa mengorbankan kehangatan saat Anda menavigasi perangkat Anda dengan mudah. Baik Anda menjawab panggilan, mengirim pesan, atau mengabadikan momen musim dingin di ponsel cerdas Anda, sarung tangan ini memastikan kenyamanan tanpa mengurangi gaya. Tekstur kaus kaki yang lembut dan nyaman menawarkan sensasi mewah di kulit Anda. Ucapkan selamat tinggal pada kaki yang dingin saat Anda merasakan kehangatan mewah yang diberikan oleh kaus kaki campuran wol ini. Paket Aksesori Musim Dingin Arctic Frost bukan hanya tentang fungsionalitas; ini adalah pernyataan mode musim dingin. Setiap item dirancang tidak hanya untuk melindungi Anda dari dingin tetapi juga untuk meningkatkan gaya Anda selama musim dingin. Bahan yang dipilih untuk paket ini memprioritaskan daya tahan dan kenyamanan, memastikan Anda dapat menikmati keindahan musim dingin dengan penuh gaya. Baik Anda memanjakan diri sendiri atau mencari hadiah yang sempurna, Paket Aksesori Musim Dingin Arctic Frost adalah pilihan serbaguna. Bahagiakan seseorang yang istimewa selama musim liburan atau tingkatkan koleksi pakaian musim dingin Anda sendiri dengan ansambel yang bergaya dan fungsional ini. Hadapi udara dingin dengan percaya diri, mengetahui bahwa Anda memiliki aksesori yang sempurna untuk membuat Anda tetap hangat dan chic.',
                    'meta-description'  => 'deskripsi meta',
                    'meta-keywords'     => 'meta1, meta2, meta3',
                    'meta-title'        => 'Judul Meta',
                    'name'              => 'Paket Aksesori Musim Dingin Arctic Frost', // Nama produknya sama dengan ID 5
                    'short-description' => 'Hadapi dinginnya musim dingin dengan Paket Aksesori Musim Dingin Arctic Frost kami. Set pilihan ini mencakup syal mewah, kupluk yang nyaman, sarung tangan yang kompatibel dengan layar sentuh, dan Kaus Kaki Campuran Wol. Penuh gaya dan fungsional, ansambel ini dibuat dari bahan berkualitas tinggi, memastikan daya tahan dan kenyamanan. Tingkatkan koleksi pakaian musim dingin Anda atau bahagiakan seseorang yang istimewa dengan pilihan hadiah yang sempurna ini.',
                ],

                '7' => [
                    'description'       => 'Memperkenalkan Jaket Puffer Pria OmniHeat Solid Bertudung, solusi andalan Anda untuk tetap hangat dan modis selama musim dingin. Jaket ini dibuat dengan mengutamakan daya tahan dan kehangatan, memastikannya menjadi teman tepercaya Anda. Desain bertudung tidak hanya menambah sentuhan gaya tetapi juga memberikan kehangatan tambahan, melindungi Anda dari angin dingin dan cuaca. Lengan panjang menawarkan cakupan penuh, memastikan Anda tetap nyaman dari bahu hingga pergelangan tangan. Dilengkapi dengan saku samping, jaket puffer ini memberikan kemudahan untuk membawa barang-barang penting Anda atau menjaga tangan tetap hangat. Isian sintetis berinsulasi menawarkan kehangatan yang ditingkatkan, menjadikannya ideal untuk melawan hari dan malam yang dingin. Terbuat dari lapisan luar dan dalam poliester yang tahan lama, jaket ini dibuat agar awet dan tahan terhadap berbagai elemen cuaca. Tersedia dalam 5 warna menarik, Anda dapat memilih yang sesuai dengan gaya dan preferensi Anda. Serbaguna dan fungsional, Jaket Puffer Pria OmniHeat Solid Bertudung cocok untuk berbagai kesempatan, baik Anda pergi bekerja, jalan-jalan santai, atau menghadiri acara di luar ruangan. Rasakan perpaduan sempurna antara gaya, kenyamanan, dan fungsionalitas dengan Jaket Puffer Pria OmniHeat Solid Bertudung. Tingkatkan koleksi pakaian musim dingin Anda dan tetap nyaman saat beraktivitas di luar ruangan. Taklukkan dingin dengan gaya dan buat pernyataan dengan item penting ini.',
                    'meta-description'  => 'deskripsi meta',
                    'meta-keywords'     => 'meta1, meta2, meta3',
                    'meta-title'        => 'Judul Meta',
                    'name'              => 'Jaket Puffer Pria OmniHeat Solid Bertudung',
                    'short-description' => 'Tetap hangat dan bergaya dengan Jaket Puffer Pria OmniHeat Solid Bertudung kami. Jaket ini dirancang untuk memberikan kehangatan maksimal dan dilengkapi saku samping untuk kenyamanan tambahan. Bahan berinsulasi memastikan Anda tetap nyaman di cuaca dingin. Tersedia dalam 5 warna menarik, menjadikannya pilihan serbaguna untuk berbagai acara.',
                ],

                '8' => [
                    'description'       => 'Memperkenalkan Jaket Puffer Pria OmniHeat Solid Bertudung, solusi andalan Anda untuk tetap hangat dan modis selama musim dingin. Jaket ini dibuat dengan mengutamakan daya tahan dan kehangatan, memastikannya menjadi teman tepercaya Anda. Desain bertudung tidak hanya menambah sentuhan gaya tetapi juga memberikan kehangatan tambahan, melindungi Anda dari angin dingin dan cuaca. Lengan panjang menawarkan cakupan penuh, memastikan Anda tetap nyaman dari bahu hingga pergelangan tangan. Dilengkapi dengan saku samping, jaket puffer ini memberikan kemudahan untuk membawa barang-barang penting Anda atau menjaga tangan tetap hangat. Isian sintetis berinsulasi menawarkan kehangatan yang ditingkatkan, menjadikannya ideal untuk melawan hari dan malam yang dingin. Terbuat dari lapisan luar dan dalam poliester yang tahan lama, jaket ini dibuat agar awet dan tahan terhadap berbagai elemen cuaca. Tersedia dalam 5 warna menarik, Anda dapat memilih yang sesuai dengan gaya dan preferensi Anda. Serbaguna dan fungsional, Jaket Puffer Pria OmniHeat Solid Bertudung cocok untuk berbagai kesempatan, baik Anda pergi bekerja, jalan-jalan santai, atau menghadiri acara di luar ruangan. Rasakan perpaduan sempurna antara gaya, kenyamanan, dan fungsionalitas dengan Jaket Puffer Pria OmniHeat Solid Bertudung. Tingkatkan koleksi pakaian musim dingin Anda dan tetap nyaman saat beraktivitas di luar ruangan. Taklukkan dingin dengan gaya dan buat pernyataan dengan item penting ini.',
                    'meta-description'  => 'deskripsi meta',
                    'meta-keywords'     => 'meta1, meta2, meta3',
                    'meta-title'        => 'Judul Meta',
                    'name'              => 'Jaket Puffer Pria OmniHeat Solid Bertudung-Biru-Kuning-M',
                    'short-description' => 'Tetap hangat dan bergaya dengan Jaket Puffer Pria OmniHeat Solid Bertudung kami. Jaket ini dirancang untuk memberikan kehangatan maksimal dan dilengkapi saku samping untuk kenyamanan tambahan. Bahan berinsulasi memastikan Anda tetap nyaman di cuaca dingin. Tersedia dalam 5 warna menarik, menjadikannya pilihan serbaguna untuk berbagai acara.',
                ],

                '9' => [
                    'description'       => 'Memperkenalkan Jaket Puffer Pria OmniHeat Solid Bertudung, solusi andalan Anda untuk tetap hangat dan modis selama musim dingin. Jaket ini dibuat dengan mengutamakan daya tahan dan kehangatan, memastikannya menjadi teman tepercaya Anda. Desain bertudung tidak hanya menambah sentuhan gaya tetapi juga memberikan kehangatan tambahan, melindungi Anda dari angin dingin dan cuaca. Lengan panjang menawarkan cakupan penuh, memastikan Anda tetap nyaman dari bahu hingga pergelangan tangan. Dilengkapi dengan saku samping, jaket puffer ini memberikan kemudahan untuk membawa barang-barang penting Anda atau menjaga tangan tetap hangat. Isian sintetis berinsulasi menawarkan kehangatan yang ditingkatkan, menjadikannya ideal untuk melawan hari dan malam yang dingin. Terbuat dari lapisan luar dan dalam poliester yang tahan lama, jaket ini dibuat agar awet dan tahan terhadap berbagai elemen cuaca. Tersedia dalam 5 warna menarik, Anda dapat memilih yang sesuai dengan gaya dan preferensi Anda. Serbaguna dan fungsional, Jaket Puffer Pria OmniHeat Solid Bertudung cocok untuk berbagai kesempatan, baik Anda pergi bekerja, jalan-jalan santai, atau menghadiri acara di luar ruangan. Rasakan perpaduan sempurna antara gaya, kenyamanan, dan fungsionalitas dengan Jaket Puffer Pria OmniHeat Solid Bertudung. Tingkatkan koleksi pakaian musim dingin Anda dan tetap nyaman saat beraktivitas di luar ruangan. Taklukkan dingin dengan gaya dan buat pernyataan dengan item penting ini. Deskripsi 9', // Ada tambahan "Description 9" di akhir teks asli, saya ikutkan.
                    'meta-description'  => 'deskripsi meta',
                    'meta-keywords'     => 'meta1, meta2, meta3',
                    'meta-title'        => 'Judul Meta',
                    'name'              => 'Jaket Puffer Pria OmniHeat Solid Bertudung-Biru-Kuning-L',
                    'short-description' => 'Tetap hangat dan bergaya dengan Jaket Puffer Pria OmniHeat Solid Bertudung kami. Jaket ini dirancang untuk memberikan kehangatan maksimal dan dilengkapi saku samping untuk kenyamanan tambahan. Bahan berinsulasi memastikan Anda tetap nyaman di cuaca dingin. Tersedia dalam 5 warna menarik, menjadikannya pilihan serbaguna untuk berbagai acara.',
                ],

                '10' => [
                    'description'       => 'Memperkenalkan Jaket Puffer Pria OmniHeat Solid Bertudung, solusi andalan Anda untuk tetap hangat dan modis selama musim dingin. Jaket ini dibuat dengan mengutamakan daya tahan dan kehangatan, memastikannya menjadi teman tepercaya Anda. Desain bertudung tidak hanya menambah sentuhan gaya tetapi juga memberikan kehangatan tambahan, melindungi Anda dari angin dingin dan cuaca. Lengan panjang menawarkan cakupan penuh, memastikan Anda tetap nyaman dari bahu hingga pergelangan tangan. Dilengkapi dengan saku samping, jaket puffer ini memberikan kemudahan untuk membawa barang-barang penting Anda atau menjaga tangan tetap hangat. Isian sintetis berinsulasi menawarkan kehangatan yang ditingkatkan, menjadikannya ideal untuk melawan hari dan malam yang dingin. Terbuat dari lapisan luar dan dalam poliester yang tahan lama, jaket ini dibuat agar awet dan tahan terhadap berbagai elemen cuaca. Tersedia dalam 5 warna menarik, Anda dapat memilih yang sesuai dengan gaya dan preferensi Anda. Serbaguna dan fungsional, Jaket Puffer Pria OmniHeat Solid Bertudung cocok untuk berbagai kesempatan, baik Anda pergi bekerja, jalan-jalan santai, atau menghadiri acara di luar ruangan. Rasakan perpaduan sempurna antara gaya, kenyamanan, dan fungsionalitas dengan Jaket Puffer Pria OmniHeat Solid Bertudung. Tingkatkan koleksi pakaian musim dingin Anda dan tetap nyaman saat beraktivitas di luar ruangan. Taklukkan dingin dengan gaya dan buat pernyataan dengan item penting ini.',
                    'meta-description'  => 'deskripsi meta',
                    'meta-keywords'     => 'meta1, meta2, meta3',
                    'meta-title'        => 'Judul Meta',
                    'name'              => 'Jaket Puffer Pria OmniHeat Solid Bertudung-Biru-Hijau-M',
                    'short-description' => 'Tetap hangat dan bergaya dengan Jaket Puffer Pria OmniHeat Solid Bertudung kami. Jaket ini dirancang untuk memberikan kehangatan maksimal dan dilengkapi saku samping untuk kenyamanan tambahan. Bahan berinsulasi memastikan Anda tetap nyaman di cuaca dingin. Tersedia dalam 5 warna menarik, menjadikannya pilihan serbaguna untuk berbagai acara.',
                ],

                '11' => [
                    'description'       => 'Memperkenalkan Jaket Puffer Pria OmniHeat Solid Bertudung, solusi andalan Anda untuk tetap hangat dan modis selama musim dingin. Jaket ini dibuat dengan mengutamakan daya tahan dan kehangatan, memastikannya menjadi teman tepercaya Anda. Desain bertudung tidak hanya menambah sentuhan gaya tetapi juga memberikan kehangatan tambahan, melindungi Anda dari angin dingin dan cuaca. Lengan panjang menawarkan cakupan penuh, memastikan Anda tetap nyaman dari bahu hingga pergelangan tangan. Dilengkapi dengan saku samping, jaket puffer ini memberikan kemudahan untuk membawa barang-barang penting Anda atau menjaga tangan tetap hangat. Isian sintetis berinsulasi menawarkan kehangatan yang ditingkatkan, menjadikannya ideal untuk melawan hari dan malam yang dingin. Terbuat dari lapisan luar dan dalam poliester yang tahan lama, jaket ini dibuat agar awet dan tahan terhadap berbagai elemen cuaca. Tersedia dalam 5 warna menarik, Anda dapat memilih yang sesuai dengan gaya dan preferensi Anda. Serbaguna dan fungsional, Jaket Puffer Pria OmniHeat Solid Bertudung cocok untuk berbagai kesempatan, baik Anda pergi bekerja, jalan-jalan santai, atau menghadiri acara di luar ruangan. Rasakan perpaduan sempurna antara gaya, kenyamanan, dan fungsionalitas dengan Jaket Puffer Pria OmniHeat Solid Bertudung. Tingkatkan koleksi pakaian musim dingin Anda dan tetap nyaman saat beraktivitas di luar ruangan. Taklukkan dingin dengan gaya dan buat pernyataan dengan item penting ini.',
                    'meta-description'  => 'deskripsi meta',
                    'meta-keywords'     => 'meta1, meta2, meta3',
                    'meta-title'        => 'Judul Meta',
                    'name'              => 'Jaket Puffer Pria OmniHeat Solid Bertudung-Biru-Hijau-L',
                    'short-description' => 'Tetap hangat dan bergaya dengan Jaket Puffer Pria OmniHeat Solid Bertudung kami. Jaket ini dirancang untuk memberikan kehangatan maksimal dan dilengkapi saku samping untuk kenyamanan tambahan. Bahan berinsulasi memastikan Anda tetap nyaman di cuaca dingin. Tersedia dalam 5 warna menarik, menjadikannya pilihan serbaguna untuk berbagai acara.',
                ],
            ],

            'product-attribute-values' => [
                '1' => [
                    'description'      => 'Topi Kupluk Rajut Nyaman Arctic adalah pilihan utama Anda untuk tetap hangat, nyaman, dan bergaya selama bulan-bulan dingin. Dibuat dari campuran rajutan akrilik yang lembut dan tahan lama, topi kupluk ini dirancang untuk memberikan kenyamanan dan pas yang pas. Desain klasiknya cocok untuk pria dan wanita, menawarkan aksesori serbaguna yang melengkapi berbagai gaya. Baik Anda akan keluar untuk hari santai di kota atau menikmati alam bebas, topi kupluk ini menambah sentuhan kenyamanan dan kehangatan pada penampilan Anda. Bahan yang lembut dan mudah bernapas memastikan Anda tetap nyaman tanpa mengorbankan gaya. Topi Kupluk Rajut Nyaman Arctic bukan hanya aksesori; ini adalah pernyataan mode musim dingin. Kesederhanaannya membuatnya mudah dipadukan dengan berbagai pakaian, menjadikannya barang wajib di lemari pakaian musim dingin Anda. Ideal untuk hadiah atau sebagai hadiah untuk diri sendiri, topi kupluk ini adalah tambahan yang berarti untuk setiap pakaian musim dingin. Ini adalah aksesori serbaguna yang melampaui fungsionalitas, menambahkan sentuhan kehangatan dan gaya pada penampilan Anda. Rangkullah esensi musim dingin dengan Topi Kupluk Rajut Nyaman Arctic. Baik Anda menikmati hari santai di luar atau menghadapi elemen, biarkan topi kupluk ini menjadi teman Anda untuk kenyamanan dan gaya. Tingkatkan lemari pakaian musim dingin Anda dengan aksesori klasik ini yang dengan mudah memadukan kehangatan dengan selera mode yang tak lekang oleh waktu.',
                    'meta-description' => 'deskripsi meta',
                    'meta-keywords'    => 'meta1, meta2, meta3',
                    'meta-title'       => 'Judul Meta',
                    'name'             => 'Topi Kupluk Rajut Nyaman Unisex Arctic',
                    'sort-description' => 'Hadapi hari-hari yang dingin dengan gaya bersama Topi Kupluk Rajut Nyaman Arctic kami. Dibuat dari campuran akrilik yang lembut dan tahan lama, topi kupluk klasik ini menawarkan kehangatan dan keserbagunaan. Cocok untuk pria dan wanita, ini adalah aksesori ideal untuk pakaian kasual atau luar ruangan. Tingkatkan lemari pakaian musim dingin Anda atau berikan hadiah kepada seseorang yang spesial dengan topi kupluk esensial ini.',
                ],

                '2' => [
                    'description'      => 'Syal Musim Dingin Arctic Bliss lebih dari sekadar aksesori cuaca dingin; ini adalah pernyataan kehangatan, kenyamanan, dan gaya untuk musim dingin. Dibuat dengan cermat dari perpaduan mewah akrilik dan wol, syal ini dirancang untuk membuat Anda tetap nyaman dan hangat bahkan di suhu terdingin sekalipun. Teksturnya yang lembut dan mewah tidak hanya memberikan insulasi terhadap dingin tetapi juga menambah sentuhan kemewahan pada lemari pakaian musim dingin Anda. Desain Syal Musim Dingin Arctic Bliss stylish dan serbaguna, menjadikannya tambahan yang sempurna untuk berbagai pakaian musim dingin. Baik Anda berdandan untuk acara khusus atau menambahkan lapisan chic pada tampilan sehari-hari, syal ini melengkapi gaya Anda dengan mudah. Panjang syal yang ekstra memberikan pilihan gaya yang dapat disesuaikan. Lingkarkan untuk kehangatan ekstra, biarkan menjuntai longgar untuk tampilan kasual, atau bereksperimen dengan berbagai simpul untuk mengekspresikan gaya unik Anda. Keserbagunaan ini menjadikannya aksesori yang harus dimiliki untuk musim dingin. Mencari hadiah yang sempurna? Syal Musim Dingin Arctic Bliss adalah pilihan yang ideal. Baik Anda mengejutkan orang yang dicintai atau memanjakan diri sendiri, syal ini adalah hadiah abadi dan praktis yang akan dihargai sepanjang bulan-bulan musim dingin. Rangkullah musim dingin dengan Syal Musim Dingin Arctic Bliss, di mana kehangatan bertemu gaya dalam harmoni yang sempurna. Tingkatkan lemari pakaian musim dingin Anda dengan aksesori penting ini yang tidak hanya membuat Anda tetap hangat tetapi juga menambah sentuhan kecanggihan pada pakaian cuaca dingin Anda.',
                    'meta-description' => 'deskripsi meta',
                    'meta-keywords'    => 'meta1, meta2, meta3',
                    'meta-title'       => 'Judul Meta',
                    'name'             => 'Syal Musim Dingin Stylish Arctic Bliss',
                    'sort-description' => 'Rasakan kehangatan dan kenyamanan tak tertandingi dari Syal Musim Dingin Arctic Bliss kami. Dibuat dari campuran mewah akrilik dan wol, syal nyaman ini dirancang untuk membuat Anda tetap hangat selama hari-hari terdingin. Desainnya yang stylish dan serbaguna, dikombinasikan dengan panjang ekstra, menawarkan pilihan gaya yang dapat disesuaikan. Tingkatkan lemari pakaian musim dingin Anda atau bahagiakan seseorang yang spesial dengan aksesori musim dingin penting ini.',
                ],

                '3' => [
                    'description'      => 'Memperkenalkan Sarung Tangan Musim Dingin Layar Sentuh Arctic – di mana kehangatan, gaya, dan konektivitas bertemu untuk meningkatkan pengalaman musim dingin Anda. Dibuat dari akrilik berkualitas tinggi, sarung tangan ini dirancang untuk memberikan kehangatan dan daya tahan yang luar biasa. Ujung jari yang kompatibel dengan layar sentuh memungkinkan Anda tetap terhubung tanpa memaparkan tangan Anda pada dingin. Jawab panggilan, kirim pesan, dan navigasikan perangkat Anda dengan mudah, sambil tetap menjaga tangan Anda tetap hangat. Lapisan berinsulasi menambahkan lapisan kenyamanan ekstra, menjadikan sarung tangan ini pilihan utama Anda untuk menghadapi dinginnya musim dingin. Baik Anda bepergian, melakukan tugas, atau menikmati aktivitas di luar ruangan, sarung tangan ini memberikan kehangatan dan perlindungan yang Anda butuhkan. Manset elastis memastikan pas yang aman, mencegah angin dingin dan menjaga sarung tangan tetap di tempat selama aktivitas harian Anda. Desain yang stylish menambah sentuhan gaya pada pakaian musim dingin Anda, membuat sarung tangan ini modis sekaligus fungsional. Ideal untuk hadiah atau sebagai hadiah untuk diri sendiri, Sarung Tangan Musim Dingin Layar Sentuh Arctic adalah aksesori yang harus dimiliki untuk individu modern. Ucapkan selamat tinggal pada ketidaknyamanan melepas sarung tangan Anda untuk menggunakan perangkat Anda dan rangkul perpaduan kehangatan, gaya, dan konektivitas yang mulus. Tetap terhubung, tetap hangat, dan tetap stylish dengan Sarung Tangan Musim Dingin Layar Sentuh Arctic – teman terpercaya Anda untuk menaklukkan musim dingin dengan percaya diri.',
                    'meta-description' => 'deskripsi meta',
                    'meta-keywords'    => 'meta1, meta2, meta3',
                    'meta-title'       => 'Judul Meta',
                    'name'             => 'Sarung Tangan Musim Dingin Layar Sentuh Arctic',
                    'sort-description' => 'Tetap terhubung dan hangat dengan Sarung Tangan Musim Dingin Layar Sentuh Arctic kami. Sarung tangan ini tidak hanya dibuat dari akrilik berkualitas tinggi untuk kehangatan dan daya tahan, tetapi juga memiliki desain yang kompatibel dengan layar sentuh. Dengan lapisan berinsulasi, manset elastis untuk pas yang aman, dan tampilan yang stylish, sarung tangan ini sempurna untuk dipakai sehari-hari dalam kondisi dingin.',
                ],

                '4' => [
                    'description'      => 'Memperkenalkan Kaos Kaki Campuran Wol Kehangatan Arctic – teman penting Anda untuk kaki yang nyaman dan hangat selama musim dingin. Dibuat dari perpaduan premium wol Merino, akrilik, nilon, dan spandeks, kaos kaki ini dirancang untuk memberikan kehangatan dan kenyamanan yang tak tertandingi. Perpaduan wol memastikan kaki Anda tetap hangat bahkan di suhu terdingin, menjadikan kaos kaki ini pilihan sempurna untuk petualangan musim dingin atau sekadar berdiam diri di rumah. Tekstur kaos kaki yang lembut dan nyaman menawarkan sentuhan mewah pada kulit Anda. Ucapkan selamat tinggal pada kaki yang dingin saat Anda merangkul kehangatan mewah yang disediakan oleh kaos kaki campuran wol ini. Dirancang untuk daya tahan, kaos kaki ini dilengkapi tumit dan jari kaki yang diperkuat, menambah kekuatan ekstra pada area yang sering aus. Ini memastikan kaos kaki Anda tahan uji waktu, memberikan kenyamanan dan kehangatan yang tahan lama. Sifat bahan yang mudah bernapas mencegah panas berlebih, memungkinkan kaki Anda tetap nyaman dan kering sepanjang hari. Baik Anda akan keluar untuk mendaki di musim dingin atau bersantai di dalam ruangan, kaos kaki ini menawarkan keseimbangan sempurna antara kehangatan dan kemampuan bernapas. Serbaguna dan stylish, kaos kaki campuran wol ini cocok untuk berbagai kesempatan. Padukan dengan sepatu bot favorit Anda untuk tampilan musim dingin yang modis atau kenakan di sekitar rumah untuk kenyamanan maksimal. Tingkatkan lemari pakaian musim dingin Anda dan prioritaskan kenyamanan dengan Kaos Kaki Campuran Wol Kehangatan Arctic. Manjakan kaki Anda dengan kemewahan yang layak mereka dapatkan dan masuki dunia kenyamanan yang bertahan sepanjang musim.',
                    'meta-description' => 'deskripsi meta',
                    'meta-keywords'    => 'meta1, meta2, meta3',
                    'meta-title'       => 'Judul Meta',
                    'name'             => 'Kaos Kaki Campuran Wol Kehangatan Arctic',
                    'sort-description' => 'Rasakan kehangatan dan kenyamanan tak tertandingi dari Kaos Kaki Campuran Wol Kehangatan Arctic kami. Dibuat dari campuran wol Merino, akrilik, nilon, dan spandeks, kaos kaki ini menawarkan kenyamanan maksimal untuk cuaca dingin. Dengan tumit dan jari kaki yang diperkuat untuk daya tahan, kaos kaki serbaguna dan stylish ini sempurna untuk berbagai kesempatan.',
                ],

                '5' => [
                    'description'      => 'Memperkenalkan Paket Aksesori Musim Dingin Arctic Frost, solusi utama Anda untuk tetap hangat, stylish, dan terhubung selama hari-hari musim dingin yang dingin. Set yang dikurasi dengan cermat ini menyatukan empat aksesori musim dingin penting untuk menciptakan ansambel yang harmonis. Syal mewah, ditenun dari campuran akrilik dan wol, tidak hanya menambah lapisan kehangatan tetapi juga membawa sentuhan elegan pada lemari pakaian musim dingin Anda. Topi kupluk rajut lembut, dibuat dengan cermat, berjanji untuk membuat Anda tetap nyaman sambil menambahkan sentuhan modis pada penampilan Anda. Tapi itu belum semuanya – paket kami juga mencakup sarung tangan yang kompatibel dengan layar sentuh. Tetap terhubung tanpa mengorbankan kehangatan saat Anda menavigasi perangkat Anda dengan mudah. Baik Anda menjawab panggilan, mengirim pesan, atau mengabadikan momen musim dingin di ponsel pintar Anda, sarung tangan ini memastikan kenyamanan tanpa mengorbankan gaya. Tekstur kaos kaki yang lembut dan nyaman menawarkan sentuhan mewah pada kulit Anda. Ucapkan selamat tinggal pada kaki yang dingin saat Anda merangkul kehangatan mewah yang disediakan oleh kaos kaki campuran wol ini. Paket Aksesori Musim Dingin Arctic Frost bukan hanya tentang fungsionalitas; ini adalah pernyataan mode musim dingin. Setiap bagian dirancang tidak hanya untuk melindungi Anda dari dingin tetapi juga untuk meningkatkan gaya Anda selama musim dingin yang membeku. Bahan yang dipilih untuk paket ini mengutamakan daya tahan dan kenyamanan, memastikan Anda dapat menikmati negeri ajaib musim dingin dengan gaya. Baik Anda memanjakan diri sendiri atau mencari hadiah yang sempurna, Paket Aksesori Musim Dingin Arctic Frost adalah pilihan serbaguna. Bahagiakan seseorang yang spesial selama musim liburan atau tingkatkan lemari pakaian musim dingin Anda sendiri dengan ansambel yang stylish dan fungsional ini. Rangkullah dinginnya dengan percaya diri, mengetahui bahwa Anda memiliki aksesori yang sempurna untuk membuat Anda tetap hangat dan chic.',
                    'meta-description' => 'deskripsi meta',
                    'meta-keywords'    => 'meta1, meta2, meta3',
                    'meta-title'       => 'Judul Meta',
                    'name'             => 'Aksesori Musim Dingin Arctic Frost',
                    'sort-description' => 'Hadapi dinginnya musim dingin dengan Paket Aksesori Musim Dingin Arctic Frost kami. Set yang dikurasi ini mencakup syal mewah, topi kupluk nyaman, sarung tangan yang kompatibel dengan layar sentuh, dan Kaos Kaki Campuran Wol. Stylish dan fungsional, ansambel ini dibuat dari bahan berkualitas tinggi, memastikan daya tahan dan kenyamanan. Tingkatkan lemari pakaian musim dingin Anda atau bahagiakan seseorang yang spesial dengan pilihan hadiah sempurna ini.',
                ],

                '6' => [
                    'description'      => 'Memperkenalkan Paket Aksesori Musim Dingin Arctic Frost, solusi utama Anda untuk tetap hangat, stylish, dan terhubung selama hari-hari musim dingin yang dingin. Set yang dikurasi dengan cermat ini menyatukan empat aksesori musim dingin penting untuk menciptakan ansambel yang harmonis. Syal mewah, ditenun dari campuran akrilik dan wol, tidak hanya menambah lapisan kehangatan tetapi juga membawa sentuhan elegan pada lemari pakaian musim dingin Anda. Topi kupluk rajut lembut, dibuat dengan cermat, berjanji untuk membuat Anda tetap nyaman sambil menambahkan sentuhan modis pada penampilan Anda. Tapi itu belum semuanya – paket kami juga mencakup sarung tangan yang kompatibel dengan layar sentuh. Tetap terhubung tanpa mengorbankan kehangatan saat Anda menavigasi perangkat Anda dengan mudah. Baik Anda menjawab panggilan, mengirim pesan, atau mengabadikan momen musim dingin di ponsel pintar Anda, sarung tangan ini memastikan kenyamanan tanpa mengorbankan gaya. Tekstur kaos kaki yang lembut dan nyaman menawarkan sentuhan mewah pada kulit Anda. Ucapkan selamat tinggal pada kaki yang dingin saat Anda merangkul kehangatan mewah yang disediakan oleh kaos kaki campuran wol ini. Paket Aksesori Musim Dingin Arctic Frost bukan hanya tentang fungsionalitas; ini adalah pernyataan mode musim dingin. Setiap bagian dirancang tidak hanya untuk melindungi Anda dari dingin tetapi juga untuk meningkatkan gaya Anda selama musim dingin yang membeku. Bahan yang dipilih untuk paket ini mengutamakan daya tahan dan kenyamanan, memastikan Anda dapat menikmati negeri ajaib musim dingin dengan gaya. Baik Anda memanjakan diri sendiri atau mencari hadiah yang sempurna, Paket Aksesori Musim Dingin Arctic Frost adalah pilihan serbaguna. Bahagiakan seseorang yang spesial selama musim liburan atau tingkatkan lemari pakaian musim dingin Anda sendiri dengan ansambel yang stylish dan fungsional ini. Rangkullah dinginnya dengan percaya diri, mengetahui bahwa Anda memiliki aksesori yang sempurna untuk membuat Anda tetap hangat dan chic.',
                    'meta-description' => 'deskripsi meta',
                    'meta-keywords'    => 'meta1, meta2, meta3',
                    'meta-title'       => 'Judul Meta',
                    'name'             => 'Paket Aksesori Musim Dingin Arctic Frost',
                    'sort-description' => 'Hadapi dinginnya musim dingin dengan Paket Aksesori Musim Dingin Arctic Frost kami. Set yang dikurasi ini mencakup syal mewah, topi kupluk nyaman, sarung tangan yang kompatibel dengan layar sentuh, dan Kaos Kaki Campuran Wol. Stylish dan fungsional, ansambel ini dibuat dari bahan berkualitas tinggi, memastikan daya tahan dan kenyamanan. Tingkatkan lemari pakaian musim dingin Anda atau bahagiakan seseorang yang spesial dengan pilihan hadiah sempurna ini.',
                ],

                '7' => [
                    'description'      => 'Memperkenalkan Jaket Puffer Berkerudung Solid Pria OmniHeat, solusi utama Anda untuk tetap hangat dan modis selama musim dingin. Jaket ini dibuat dengan mempertimbangkan daya tahan dan kehangatan, memastikan jaket ini menjadi teman terpercaya Anda. Desain berkerudung tidak hanya menambah sentuhan gaya tetapi juga memberikan kehangatan tambahan, melindungi Anda dari angin dingin dan cuaca. Lengan penuh menawarkan cakupan lengkap, memastikan Anda tetap nyaman dari bahu hingga pergelangan tangan. Dilengkapi dengan saku sisipan, jaket puffer ini memberikan kenyamanan untuk membawa barang-barang penting Anda atau menjaga tangan Anda tetap hangat. Isian sintetis berinsulasi menawarkan kehangatan yang ditingkatkan, menjadikannya ideal untuk melawan hari dan malam yang dingin. Terbuat dari cangkang dan lapisan poliester yang tahan lama, jaket ini dibangun untuk bertahan lama dan tahan terhadap elemen. Tersedia dalam 5 warna menarik, Anda dapat memilih yang sesuai dengan gaya dan preferensi Anda. Serbaguna dan fungsional, Jaket Puffer Berkerudung Solid Pria OmniHeat cocok untuk berbagai kesempatan, baik Anda akan bekerja, pergi untuk jalan-jalan santai, atau menghadiri acara di luar ruangan. Rasakan perpaduan sempurna antara gaya, kenyamanan, dan fungsionalitas dengan Jaket Puffer Berkerudung Solid Pria OmniHeat. Tingkatkan lemari pakaian musim dingin Anda dan tetap hangat saat menikmati alam bebas. Kalahkan dinginnya dengan gaya dan buat pernyataan dengan pakaian penting ini.',
                    'meta-description' => 'deskripsi meta',
                    'meta-keywords'    => 'meta1, meta2, meta3',
                    'meta-title'       => 'Judul Meta',
                    'name'             => 'Jaket Puffer Berkerudung Solid Pria OmniHeat',
                    'sort-description' => 'Tetap hangat dan stylish dengan Jaket Puffer Berkerudung Solid Pria OmniHeat kami. Jaket ini dirancang untuk memberikan kehangatan maksimal dan dilengkapi saku sisipan untuk kenyamanan tambahan. Bahan berinsulasi memastikan Anda tetap nyaman dalam cuaca dingin. Tersedia dalam 5 warna menarik, menjadikannya pilihan serbaguna untuk berbagai kesempatan.',
                ],

                '8' => [
                    'description'      => 'Memperkenalkan Jaket Puffer Berkerudung Solid Pria OmniHeat, solusi utama Anda untuk tetap hangat dan modis selama musim dingin. Jaket ini dibuat dengan mempertimbangkan daya tahan dan kehangatan, memastikan jaket ini menjadi teman terpercaya Anda. Desain berkerudung tidak hanya menambah sentuhan gaya tetapi juga memberikan kehangatan tambahan, melindungi Anda dari angin dingin dan cuaca. Lengan penuh menawarkan cakupan lengkap, memastikan Anda tetap nyaman dari bahu hingga pergelangan tangan. Dilengkapi dengan saku sisipan, jaket puffer ini memberikan kenyamanan untuk membawa barang-barang penting Anda atau menjaga tangan Anda tetap hangat. Isian sintetis berinsulasi menawarkan kehangatan yang ditingkatkan, menjadikannya ideal untuk melawan hari dan malam yang dingin. Terbuat dari cangkang dan lapisan poliester yang tahan lama, jaket ini dibangun untuk bertahan lama dan tahan terhadap elemen. Tersedia dalam 5 warna menarik, Anda dapat memilih yang sesuai dengan gaya dan preferensi Anda. Serbaguna dan fungsional, Jaket Puffer Berkerudung Solid Pria OmniHeat cocok untuk berbagai kesempatan, baik Anda akan bekerja, pergi untuk jalan-jalan santai, atau menghadiri acara di luar ruangan. Rasakan perpaduan sempurna antara gaya, kenyamanan, dan fungsionalitas dengan Jaket Puffer Berkerudung Solid Pria OmniHeat. Tingkatkan lemari pakaian musim dingin Anda dan tetap hangat saat menikmati alam bebas. Kalahkan dinginnya dengan gaya dan buat pernyataan dengan pakaian penting ini.',
                    'meta-description' => 'deskripsi meta',
                    'meta-keywords'    => 'meta1, meta2, meta3',
                    'meta-title'       => 'Judul Meta',
                    'name'             => 'Jaket Puffer Berkerudung Solid Pria OmniHeat-Biru-Kuning-M',
                    'sort-description' => 'Tetap hangat dan stylish dengan Jaket Puffer Berkerudung Solid Pria OmniHeat kami. Jaket ini dirancang untuk memberikan kehangatan maksimal dan dilengkapi saku sisipan untuk kenyamanan tambahan. Bahan berinsulasi memastikan Anda tetap nyaman dalam cuaca dingin. Tersedia dalam 5 warna menarik, menjadikannya pilihan serbaguna untuk berbagai kesempatan.',
                ],

                '9' => [
                    'description'      => 'Memperkenalkan Jaket Puffer Berkerudung Solid Pria OmniHeat, solusi utama Anda untuk tetap hangat dan modis selama musim dingin. Jaket ini dibuat dengan mempertimbangkan daya tahan dan kehangatan, memastikan jaket ini menjadi teman terpercaya Anda. Desain berkerudung tidak hanya menambah sentuhan gaya tetapi juga memberikan kehangatan tambahan, melindungi Anda dari angin dingin dan cuaca. Lengan penuh menawarkan cakupan lengkap, memastikan Anda tetap nyaman dari bahu hingga pergelangan tangan. Dilengkapi dengan saku sisipan, jaket puffer ini memberikan kenyamanan untuk membawa barang-barang penting Anda atau menjaga tangan Anda tetap hangat. Isian sintetis berinsulasi menawarkan kehangatan yang ditingkatkan, menjadikannya ideal untuk melawan hari dan malam yang dingin. Terbuat dari cangkang dan lapisan poliester yang tahan lama, jaket ini dibangun untuk bertahan lama dan tahan terhadap elemen. Tersedia dalam 5 warna menarik, Anda dapat memilih yang sesuai dengan gaya dan preferensi Anda. Serbaguna dan fungsional, Jaket Puffer Berkerudung Solid Pria OmniHeat cocok untuk berbagai kesempatan, baik Anda akan bekerja, pergi untuk jalan-jalan santai, atau menghadiri acara di luar ruangan. Rasakan perpaduan sempurna antara gaya, kenyamanan, dan fungsionalitas dengan Jaket Puffer Berkerudung Solid Pria OmniHeat. Tingkatkan lemari pakaian musim dingin Anda dan tetap hangat saat menikmati alam bebas. Kalahkan dinginnya dengan gaya dan buat pernyataan dengan pakaian penting ini.',
                    'meta-description' => 'deskripsi meta',
                    'meta-keywords'    => 'meta1, meta2, meta3',
                    'meta-title'       => 'Judul Meta',
                    'name'             => 'Jaket Puffer Berkerudung Solid Pria OmniHeat-Biru-Kuning-L',
                    'sort-description' => 'Tetap hangat dan stylish dengan Jaket Puffer Berkerudung Solid Pria OmniHeat kami. Jaket ini dirancang untuk memberikan kehangatan maksimal dan dilengkapi saku sisipan untuk kenyamanan tambahan. Bahan berinsulasi memastikan Anda tetap nyaman dalam cuaca dingin. Tersedia dalam 5 warna menarik, menjadikannya pilihan serbaguna untuk berbagai kesempatan.',
                ],

                '10' => [
                    'description'      => 'Memperkenalkan Jaket Puffer Berkerudung Solid Pria OmniHeat, solusi utama Anda untuk tetap hangat dan modis selama musim dingin. Jaket ini dibuat dengan mempertimbangkan daya tahan dan kehangatan, memastikan jaket ini menjadi teman terpercaya Anda. Desain berkerudung tidak hanya menambah sentuhan gaya tetapi juga memberikan kehangatan tambahan, melindungi Anda dari angin dingin dan cuaca. Lengan penuh menawarkan cakupan lengkap, memastikan Anda tetap nyaman dari bahu hingga pergelangan tangan. Dilengkapi dengan saku sisipan, jaket puffer ini memberikan kenyamanan untuk membawa barang-barang penting Anda atau menjaga tangan Anda tetap hangat. Isian sintetis berinsulasi menawarkan kehangatan yang ditingkatkan, menjadikannya ideal untuk melawan hari dan malam yang dingin. Terbuat dari cangkang dan lapisan poliester yang tahan lama, jaket ini dibangun untuk bertahan lama dan tahan terhadap elemen. Tersedia dalam 5 warna menarik, Anda dapat memilih yang sesuai dengan gaya dan preferensi Anda. Serbaguna dan fungsional, Jaket Puffer Berkerudung Solid Pria OmniHeat cocok untuk berbagai kesempatan, baik Anda akan bekerja, pergi untuk jalan-jalan santai, atau menghadiri acara di luar ruangan. Rasakan perpaduan sempurna antara gaya, kenyamanan, dan fungsionalitas dengan Jaket Puffer Berkerudung Solid Pria OmniHeat. Tingkatkan lemari pakaian musim dingin Anda dan tetap hangat saat menikmati alam bebas. Kalahkan dinginnya dengan gaya dan buat pernyataan dengan pakaian penting ini.',
                    'meta-description' => 'deskripsi meta',
                    'meta-keywords'    => 'meta1, meta2, meta3',
                    'meta-title'       => 'Judul Meta',
                    'name'             => 'Jaket Puffer Berkerudung Solid Pria OmniHeat-Biru-Hijau-M',
                    'sort-description' => 'Tetap hangat dan stylish dengan Jaket Puffer Berkerudung Solid Pria OmniHeat kami. Jaket ini dirancang untuk memberikan kehangatan maksimal dan dilengkapi saku sisipan untuk kenyamanan tambahan. Bahan berinsulasi memastikan Anda tetap nyaman dalam cuaca dingin. Tersedia dalam 5 warna menarik, menjadikannya pilihan serbaguna untuk berbagai kesempatan.',
                ],

                '11' => [
                    'description'      => 'Memperkenalkan Jaket Puffer Berkerudung Solid Pria OmniHeat, solusi utama Anda untuk tetap hangat dan modis selama musim dingin. Jaket ini dibuat dengan mempertimbangkan daya tahan dan kehangatan, memastikan jaket ini menjadi teman terpercaya Anda. Desain berkerudung tidak hanya menambah sentuhan gaya tetapi juga memberikan kehangatan tambahan, melindungi Anda dari angin dingin dan cuaca. Lengan penuh menawarkan cakupan lengkap, memastikan Anda tetap nyaman dari bahu hingga pergelangan tangan. Dilengkapi dengan saku sisipan, jaket puffer ini memberikan kenyamanan untuk membawa barang-barang penting Anda atau menjaga tangan Anda tetap hangat. Isian sintetis berinsulasi menawarkan kehangatan yang ditingkatkan, menjadikannya ideal untuk melawan hari dan malam yang dingin. Terbuat dari cangkang dan lapisan poliester yang tahan lama, jaket ini dibangun untuk bertahan lama dan tahan terhadap elemen. Tersedia dalam 5 warna menarik, Anda dapat memilih yang sesuai dengan gaya dan preferensi Anda. Serbaguna dan fungsional, Jaket Puffer Berkerudung Solid Pria OmniHeat cocok untuk berbagai kesempatan, baik Anda akan bekerja, pergi untuk jalan-jalan santai, atau menghadiri acara di luar ruangan. Rasakan perpaduan sempurna antara gaya, kenyamanan, dan fungsionalitas dengan Jaket Puffer Berkerudung Solid Pria OmniHeat. Tingkatkan lemari pakaian musim dingin Anda dan tetap hangat saat menikmati alam bebas. Kalahkan dinginnya dengan gaya dan buat pernyataan dengan pakaian penting ini.',
                    'meta-description' => 'deskripsi meta',
                    'meta-keywords'    => 'meta1, meta2, meta3',
                    'meta-title'       => 'Judul Meta',
                    'name'             => 'Jaket Puffer Berkerudung Solid Pria OmniHeat-Biru-Hijau-L',
                    'sort-description' => 'Tetap hangat dan stylish dengan Jaket Puffer Berkerudung Solid Pria OmniHeat kami. Jaket ini dirancang untuk memberikan kehangatan maksimal dan dilengkapi saku sisipan untuk kenyamanan tambahan. Bahan berinsulasi memastikan Anda tetap nyaman dalam cuaca dingin. Tersedia dalam 5 warna menarik, menjadikannya pilihan serbaguna untuk berbagai kesempatan.',
                ],
            ],

            'product-bundle-option-translations' => [
                '1' => [
                    'label' => 'Opsi Paket 1',
                ],

                '2' => [
                    'label' => 'Opsi Paket 1',
                ],

                '3' => [
                    'label' => 'Opsi Paket 2',
                ],

                '4' => [
                    'label' => 'Opsi Paket 2',
                ],
            ],
        ],
    ],

    'installer' => [
        'index' => [
            'create-administrator' => [
                'admin'            => 'Admin',
                'bagisto'          => 'Bagisto',
                'confirm-password' => 'Konfirmasi Kata Sandi',
                'email'            => 'Email',
                'email-address'    => 'admin@example.com',
                'password'         => 'Kata Sandi',
                'title'            => 'Buat Administrator',
            ],

            'environment-configuration' => [
                'algerian-dinar'              => 'Dinar Algeria (DZD)',
                'allowed-currencies'          => 'Mata Uang yang Diperbolehkan',
                'allowed-locales'             => 'Locale yang Diperbolehkan',
                'application-name'            => 'Nama Aplikasi',
                'argentine-peso'              => 'Peso Argentina (ARS)',
                'australian-dollar'           => 'Dolar Australia (AUD)',
                'bagisto'                     => 'Bagisto',
                'bangladeshi-taka'            => 'Taka Bangladesh (BDT)',
                'bahraini-dinar'              => 'Dinar Bahrain (BHD)',
                'brazilian-real'              => 'Real Brasil (BRL)',
                'british-pound-sterling'      => 'Pound Sterling Inggris (GBP)',
                'canadian-dollar'             => 'Dolar Kanada (CAD)',
                'cfa-franc-bceao'             => 'Franc CFA BCEAO (XOF)',
                'cfa-franc-beac'              => 'Franc CFA BEAC (XAF)',
                'chilean-peso'                => 'Peso Chili (CLP)',
                'chinese-yuan'                => 'Yuan Tiongkok (CNY)',
                'colombian-peso'              => 'Peso Kolombia (COP)',
                'czech-koruna'                => 'Koruna Ceko (CZK)',
                'danish-krone'                => 'Krone Denmark (DKK)',
                'database-connection'         => 'Koneksi Database',
                'database-hostname'           => 'Nama Host Database',
                'database-name'               => 'Nama Database',
                'database-password'           => 'Kata Sandi Database',
                'database-port'               => 'Port Database',
                'database-prefix'             => 'Prefiks Database',
                'database-prefix-help'        => 'Prefiks harus sepanjang 4 karakter dan hanya dapat berisi huruf, angka, dan garis bawah.',
                'database-username'           => 'Nama Pengguna Database',
                'default-currency'            => 'Mata Uang Default',
                'default-locale'              => 'Locale Default',
                'default-timezone'            => 'Zona Waktu Default',
                'default-url'                 => 'URL Default',
                'default-url-link'            => 'https://localhost',
                'egyptian-pound'              => 'Pound Mesir (EGP)',
                'euro'                        => 'Euro (EUR)',
                'fijian-dollar'               => 'Dolar Fiji (FJD)',
                'hong-kong-dollar'            => 'Dolar Hong Kong (HKD)',
                'hungarian-forint'            => 'Forint Hongaria (HUF)',
                'indian-rupee'                => 'Rupee India (INR)',
                'indonesian-rupiah'           => 'Rupiah Indonesia (IDR)',
                'israeli-new-shekel'          => 'Shekel Baru Israel (ILS)',
                'japanese-yen'                => 'Yen Jepang (JPY)',
                'jordanian-dinar'             => 'Dinar Yordania (JOD)',
                'kazakhstani-tenge'           => 'Tenge Kazakhstan (KZT)',
                'kuwaiti-dinar'               => 'Dinar Kuwait (KWD)',
                'lebanese-pound'              => 'Pound Lebanon (LBP)',
                'libyan-dinar'                => 'Dinar Libya (LYD)',
                'malaysian-ringgit'           => 'Ringgit Malaysia (MYR)',
                'mauritian-rupee'             => 'Rupee Mauritius (MUR)',
                'mexican-peso'                => 'Peso Meksiko (MXN)',
                'moroccan-dirham'             => 'Dirham Maroko (MAD)',
                'mysql'                       => 'Mysql',
                'nepalese-rupee'              => 'Rupee Nepal (NPR)',
                'new-taiwan-dollar'           => 'Dolar Taiwan Baru (TWD)',
                'new-zealand-dollar'          => 'Dolar Selandia Baru (NZD)',
                'nigerian-naira'              => 'Naira Nigeria (NGN)',
                'norwegian-krone'             => 'Krone Norwegia (NOK)',
                'omani-rial'                  => 'Rial Oman (OMR)',
                'pakistani-rupee'             => 'Rupee Pakistan (PKR)',
                'panamanian-balboa'           => 'Balboa Panama (PAB)',
                'paraguayan-guarani'          => 'Guarani Paraguay (PYG)',
                'peruvian-nuevo-sol'          => 'Nuevo Sol Peru (PEN)',
                'pgsql'                       => 'pgSQL',
                'philippine-peso'             => 'Peso Filipina (PHP)',
                'polish-zloty'                => 'Zloty Polandia (PLN)',
                'qatari-rial'                 => 'Rial Qatar (QAR)',
                'romanian-leu'                => 'Leu Rumania (RON)',
                'russian-ruble'               => 'Rubel Rusia (RUB)',
                'saudi-riyal'                 => 'Riyal Saudi (SAR)',
                'select-timezone'             => 'Pilih Zona Waktu',
                'singapore-dollar'            => 'Dolar Singapura (SGD)',
                'south-african-rand'          => 'Rand Afrika Selatan (ZAR)',
                'south-korean-won'            => 'Won Korea Selatan (KRW)',
                'sqlsrv'                      => 'SQLSRV',
                'sri-lankan-rupee'            => 'Rupee Sri Lanka (LKR)',
                'swedish-krona'               => 'Krona Swedia (SEK)',
                'swiss-franc'                 => 'Franc Swiss (CHF)',
                'thai-baht'                   => 'Baht Thailand (THB)',
                'title'                       => 'Konfigurasi Toko',
                'tunisian-dinar'              => 'Dinar Tunisia (TND)',
                'turkish-lira'                => 'Lira Turki (TRY)',
                'ukrainian-hryvnia'           => 'Hryvnia Ukraina (UAH)',
                'united-arab-emirates-dirham' => 'Dirham Uni Emirat Arab (AED)',
                'united-states-dollar'        => 'Dolar Amerika Serikat (USD)',
                'uzbekistani-som'             => 'Som Uzbekistan (UZS)',
                'venezuelan-bolívar'          => 'Bolívar Venezuela (VEF)',
                'vietnamese-dong'             => 'Dong Vietnam (VND)',
                'warning-message'             => 'Perhatian! Pengaturan bahasa sistem default dan mata uang default bersifat permanen dan tidak dapat diubah setelah disetel.',
                'zambian-kwacha'              => 'Kwacha Zambia (ZMW)',
            ],

            'sample-products' => [
                'download-sample' => 'Unduh Sampel',
                'no'              => 'Tidak',
                'sample-products' => 'Produk Sampel',
                'title'           => 'Produk Sampel',
                'yes'             => 'Ya',
            ],

            'installation-processing' => [
                'bagisto'      => 'Instalasi Bagisto',
                'bagisto-info' => 'Membuat tabel database, ini dapat memakan waktu beberapa saat',
                'title'        => 'Instalasi',
            ],

            'installation-completed' => [
                'admin-panel'                => 'Panel Admin',
                'bagisto-forums'             => 'Forum Bagisto',
                'customer-panel'             => 'Panel Pelanggan',
                'explore-bagisto-extensions' => 'Jelajahi Ekstensi Bagisto',
                'title'                      => 'Instalasi Selesai',
                'title-info'                 => 'Bagisto berhasil diinstal di sistem Anda.',
            ],

            'ready-for-installation' => [
                'create-databsae-table'   => 'Buat tabel database',
                'install'                 => 'Instalasi',
                'install-info'            => 'Bagisto untuk Instalasi',
                'install-info-button'     => 'Klik tombol di bawah untuk',
                'populate-database-table' => 'Isi tabel database',
                'start-installation'      => 'Mulai Instalasi',
                'title'                   => 'Siap untuk Instalasi',
            ],

            'start' => [
                'locale'        => 'Locale',
                'main'          => 'Mulai',
                'select-locale' => 'Pilih Locale',
                'title'         => 'Instalasi Bagisto Anda',
                'welcome-title' => 'Selamat datang di Bagisto',
            ],

            'server-requirements' => [
                'calendar'    => 'Calendar',
                'ctype'       => 'cType',
                'curl'        => 'cURL',
                'dom'         => 'dom',
                'fileinfo'    => 'fileInfo',
                'filter'      => 'Filter',
                'gd'          => 'GD',
                'hash'        => 'Hash',
                'intl'        => 'intl',
                'json'        => 'JSON',
                'mbstring'    => 'mbstring',
                'openssl'     => 'openssl',
                'pcre'        => 'pcre',
                'pdo'         => 'pdo',
                'php'         => 'PHP',
                'php-version' => '8.1 atau yang lebih tinggi',
                'session'     => 'session',
                'title'       => 'Persyaratan Sistem',
                'tokenizer'   => 'tokenizer',
                'xml'         => 'XML',
            ],

            'arabic'                   => 'Arab',
            'back'                     => 'Kembali',
            'bagisto'                  => 'Bagisto',
            'bagisto-info'             => 'Proyek Komunitas oleh',
            'bagisto-logo'             => 'Logo Bagisto',
            'bengali'                  => 'Bengali',
            'catalan'                  => 'Katalan',
            'chinese'                  => 'Cina',
            'continue'                 => 'Lanjutkan',
            'dutch'                    => 'Belanda',
            'english'                  => 'Inggris',
            'french'                   => 'Perancis',
            'german'                   => 'Jerman',
            'hebrew'                   => 'Ibrani',
            'hindi'                    => 'Hindi',
            'indonesian'               => 'Indonesia',
            'installation-description' => 'Instalasi Bagisto umumnya melibatkan beberapa langkah. Berikut adalah gambaran umum proses instalasi untuk Bagisto',
            'installation-info'        => 'Kami senang melihat Anda di sini!',
            'installation-title'       => 'Selamat datang di Instalasi',
            'italian'                  => 'Italia',
            'japanese'                 => 'Jepang',
            'persian'                  => 'Persia',
            'polish'                   => 'Polandia',
            'portuguese'               => 'Portugis Brasil',
            'russian'                  => 'Rusia',
            'sinhala'                  => 'Sinhala',
            'spanish'                  => 'Spanyol',
            'title'                    => 'Pemasang Bagisto',
            'turkish'                  => 'Turki',
            'ukrainian'                => 'Ukraina',
            'webkul'                   => 'Webkul',
        ],
    ],
];
