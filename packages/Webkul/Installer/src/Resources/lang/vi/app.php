<?php

return [
    'seeders' => [
        'attribute' => [
            'attribute-families' => [
                'default' => 'Mặc định',
            ],

            'attribute-groups' => [
                'description'       => 'Mô tả',
                'general'           => 'Chung',
                'inventories'       => 'Tồn kho',
                'meta-description'  => 'Mô tả Meta',
                'price'             => 'Giá',
                'settings'          => 'Cài đặt',
                'shipping'          => 'Vận chuyển',
            ],

            'attributes' => [
                'brand'                => 'Thương hiệu',
                'color'                => 'Màu sắc',
                'cost'                 => 'Chi phí',
                'description'          => 'Mô tả',
                'featured'             => 'Nổi bật',
                'guest-checkout'       => 'Thanh toán với tư cách khách',
                'height'               => 'Chiều cao',
                'length'               => 'Chiều dài',
                'manage-stock'         => 'Quản lý tồn kho',
                'meta-description'     => 'Mô tả Meta',
                'meta-keywords'        => 'Từ khóa Meta',
                'meta-title'           => 'Tiêu đề Meta',
                'name'                 => 'Tên',
                'new'                  => 'Mới',
                'price'                => 'Giá',
                'product-number'       => 'Mã sản phẩm',
                'short-description'    => 'Mô tả ngắn',
                'size'                 => 'Kích thước',
                'sku'                  => 'SKU',
                'special-price'        => 'Giá đặc biệt',
                'special-price-from'   => 'Giá đặc biệt từ',
                'special-price-to'     => 'Giá đặc biệt đến',
                'status'               => 'Trạng thái',
                'tax-category'         => 'Danh mục thuế',
                'url-key'              => 'Khóa URL',
                'visible-individually' => 'Hiển thị riêng lẻ',
                'weight'               => 'Trọng lượng',
                'width'                => 'Chiều rộng',
            ],

            'attribute-options' => [
                'black'  => 'Đen',
                'green'  => 'Xanh lá',
                'l'      => 'L',
                'm'      => 'M',
                'red'    => 'Đỏ',
                's'      => 'S',
                'white'  => 'Trắng',
                'xl'     => 'XL',
                'yellow' => 'Vàng',
            ],
        ],

        'category' => [
            'categories' => [
                'description' => 'Mô tả danh mục gốc',
                'name'        => 'Gốc',
            ],
        ],

        'cms' => [
            'pages' => [
                'about-us' => [
                    'content' => 'Nội dung trang Giới thiệu',
                    'title'   => 'Giới thiệu',
                ],

                'contact-us' => [
                    'content' => 'Nội dung trang Liên hệ',
                    'title'   => 'Liên hệ',
                ],

                'customer-service' => [
                    'content' => 'Nội dung trang Dịch vụ khách hàng',
                    'title'   => 'Dịch vụ khách hàng',
                ],

                'payment-policy' => [
                    'content' => 'Nội dung trang Chính sách thanh toán',
                    'title'   => 'Chính sách thanh toán',
                ],

                'privacy-policy' => [
                    'content' => 'Nội dung trang Chính sách bảo mật',
                    'title'   => 'Chính sách bảo mật',
                ],

                'refund-policy' => [
                    'content' => 'Nội dung trang Chính sách hoàn tiền',
                    'title'   => 'Chính sách hoàn tiền',
                ],

                'return-policy' => [
                    'content' => 'Nội dung trang Chính sách đổi trả',
                    'title'   => 'Chính sách đổi trả',
                ],

                'shipping-policy' => [
                    'content' => 'Nội dung trang Chính sách vận chuyển',
                    'title'   => 'Chính sách vận chuyển',
                ],

                'terms-conditions' => [
                    'content' => 'Nội dung trang Điều khoản & Điều kiện',
                    'title'   => 'Điều khoản & Điều kiện',
                ],

                'terms-of-use' => [
                    'content' => 'Nội dung trang Điều khoản sử dụng',
                    'title'   => 'Điều khoản sử dụng',
                ],

                'whats-new' => [
                    'content' => 'Nội dung trang Có gì mới',
                    'title'   => 'Có gì mới',
                ],
            ],
        ],

        'core' => [
            'channels' => [
                'name'             => 'Mặc định',
                'meta-title'       => 'Cửa hàng Demo',
                'meta-keywords'    => 'Từ khóa meta cửa hàng demo',
                'meta-description' => 'Mô tả meta cửa hàng demo',
            ],

            'currencies' => [
                'AED' => 'Dirham UAE',
                'ARS' => 'Peso Argentina',
                'AUD' => 'Đô la Úc',
                'BDT' => 'Taka Bangladesh',
                'BRL' => 'Real Brazil',
                'CAD' => 'Đô la Canada',
                'CHF' => 'Franc Thụy Sĩ',
                'CLP' => 'Peso Chile',
                'CNY' => 'Nhân dân tệ Trung Quốc',
                'COP' => 'Peso Colombia',
                'CZK' => 'Koruna Séc',
                'DKK' => 'Krone Đan Mạch',
                'DZD' => 'Dinar Algeria',
                'EGP' => 'Bảng Ai Cập',
                'EUR' => 'Euro',
                'FJD' => 'Đô la Fiji',
                'GBP' => 'Bảng Anh',
                'HKD' => 'Đô la Hồng Kông',
                'HUF' => 'Forint Hungary',
                'IDR' => 'Rupiah Indonesia',
                'ILS' => 'Shekel Israel',
                'INR' => 'Rupee Ấn Độ',
                'JOD' => 'Dinar Jordan',
                'JPY' => 'Yên Nhật',
                'KRW' => 'Won Hàn Quốc',
                'KWD' => 'Dinar Kuwait',
                'KZT' => 'Tenge Kazakhstan',
                'LBP' => 'Bảng Lebanon',
                'LKR' => 'Rupee Sri Lanka',
                'LYD' => 'Dinar Libya',
                'MAD' => 'Dirham Morocco',
                'MUR' => 'Rupee Mauritius',
                'MXN' => 'Peso Mexico',
                'MYR' => 'Ringgit Malaysia',
                'NGN' => 'Naira Nigeria',
                'NOK' => 'Krone Na Uy',
                'NPR' => 'Rupee Nepal',
                'NZD' => 'Đô la New Zealand',
                'OMR' => 'Rial Oman',
                'PAB' => 'Balboa Panama',
                'PEN' => 'Nuevo Sol Peru',
                'PHP' => 'Peso Philippines',
                'PKR' => 'Rupee Pakistan',
                'PLN' => 'Zloty Ba Lan',
                'PYG' => 'Guarani Paraguay',
                'QAR' => 'Rial Qatar',
                'RON' => 'Leu Romania',
                'RUB' => 'Ruble Nga',
                'SAR' => 'Riyal Ả Rập Xê Út',
                'SEK' => 'Krona Thụy Điển',
                'SGD' => 'Đô la Singapore',
                'THB' => 'Baht Thái Lan',
                'TND' => 'Dinar Tunisia',
                'TRY' => 'Lira Thổ Nhĩ Kỳ',
                'TWD' => 'Đô la Đài Loan mới',
                'UAH' => 'Hryvnia Ukraina',
                'USD' => 'Đô la Mỹ',
                'UZS' => 'Som Uzbekistan',
                'VEF' => 'Bolívar Venezuela',
                'VND' => 'Đồng Việt Nam',
                'XAF' => 'Franc CFA BEAC',
                'XOF' => 'Franc CFA BCEAO',
                'ZAR' => 'Rand Nam Phi',
                'ZMW' => 'Kwacha Zambia',
            ],

            'locales' => [
                'ar'    => 'Tiếng Ả Rập',
                'bn'    => 'Tiếng Bengal',
                'de'    => 'Tiếng Đức',
                'en'    => 'Tiếng Anh',
                'es'    => 'Tiếng Tây Ban Nha',
                'fa'    => 'Tiếng Ba Tư',
                'fr'    => 'Tiếng Pháp',
                'he'    => 'Tiếng Do Thái',
                'hi_IN' => 'Tiếng Hindi',
                'it'    => 'Tiếng Ý',
                'ja'    => 'Tiếng Nhật',
                'nl'    => 'Tiếng Hà Lan',
                'pl'    => 'Tiếng Ba Lan',
                'pt_BR' => 'Tiếng Bồ Đào Nha (Brazil)',
                'ru'    => 'Tiếng Nga',
                'sin'   => 'Tiếng Sinhala',
                'tr'    => 'Tiếng Thổ Nhĩ Kỳ',
                'uk'    => 'Tiếng Ukraina',
                'vi'    => 'Tiếng Việt',
                'zh_CN' => 'Tiếng Trung Quốc',
            ],
        ],

        'customer' => [
            'customer-groups' => [
                'general'   => 'Chung',
                'guest'     => 'Khách',
                'wholesale' => 'Bán sỉ',
            ],
        ],

        'inventory' => [
            'inventory-sources' => [
                'name' => 'Mặc định',
            ],
        ],

        'shop' => [
            'theme-customizations' => [
                'all-products' => [
                    'name' => 'Tất cả sản phẩm',

                    'options' => [
                        'title' => 'Tất cả sản phẩm',
                    ],
                ],

                'bold-collections' => [
                    'content' => [
                        'btn-title'   => 'Xem bộ sưu tập',
                        'description' => 'Giới thiệu Bộ sưu tập Đậm chất mới của chúng tôi! Nâng tầm phong cách của bạn với những thiết kế táo bạo và tuyên bố mạnh mẽ. Khám phá các họa tiết ấn tượng và màu sắc nổi bật để làm mới tủ đồ của bạn. Hãy sẵn sàng đón nhận sự khác biệt!',
                        'title'       => 'Hãy sẵn sàng cho Bộ sưu tập Đậm chất mới!',
                    ],

                    'name' => 'Bộ sưu tập Đậm chất',
                ],

                'categories-collections' => [
                    'name' => 'Bộ sưu tập danh mục',
                ],

                'featured-collections' => [
                    'name' => 'Bộ sưu tập nổi bật',

                    'options' => [
                        'title' => 'Sản phẩm nổi bật',
                    ],
                ],

                'footer-links' => [
                    'name' => 'Liên kết chân trang',

                    'options' => [
                        'about-us'         => 'Về chúng tôi',
                        'contact-us'       => 'Liên hệ',
                        'customer-service' => 'Dịch vụ khách hàng',
                        'payment-policy'   => 'Chính sách thanh toán',
                        'privacy-policy'   => 'Chính sách bảo mật',
                        'refund-policy'    => 'Chính sách hoàn tiền',
                        'return-policy'    => 'Chính sách đổi trả',
                        'shipping-policy'  => 'Chính sách vận chuyển',
                        'terms-conditions' => 'Điều khoản & điều kiện',
                        'terms-of-use'     => 'Điều khoản sử dụng',
                        'whats-new'        => 'Có gì mới',
                    ],
                ],

                'game-container' => [
                    'content' => [
                        'sub-title-1' => 'Bộ sưu tập của chúng tôi',
                        'sub-title-2' => 'Bộ sưu tập của chúng tôi',
                        'title'       => 'Trải nghiệm với những sản phẩm mới!',
                    ],

                    'name' => 'Hộp trò chơi',
                ],

                'image-carousel' => [
                    'name' => 'Băng chuyền hình ảnh',

                    'sliders' => [
                        'title' => 'Sẵn sàng cho bộ sưu tập mới',
                    ],
                ],

                'new-products' => [
                    'name' => 'Sản phẩm mới',

                    'options' => [
                        'title' => 'Sản phẩm mới',
                    ],
                ],

                'offer-information' => [
                    'content' => [
                        'title' => 'Giảm giá lên đến 40% cho đơn hàng đầu tiên - MUA NGAY',
                    ],

                    'name' => 'Thông tin khuyến mãi',
                ],

                'services-content' => [
                    'description' => [
                        'emi-available-info'   => 'Trả góp không lãi suất với các thẻ tín dụng lớn',
                        'free-shipping-info'   => 'Miễn phí vận chuyển cho mọi đơn hàng',
                        'product-replace-info' => 'Dễ dàng đổi sản phẩm!',
                        'time-support-info'    => 'Hỗ trợ 24/7 qua chat và email',
                    ],

                    'name' => 'Nội dung dịch vụ',

                    'title' => [
                        'emi-available'   => 'Trả góp',
                        'free-shipping'   => 'Miễn phí vận chuyển',
                        'product-replace' => 'Đổi trả sản phẩm',
                        'time-support'    => 'Hỗ trợ 24/7',
                    ],
                ],

                'top-collections' => [
                    'content' => [
                        'sub-title-1' => 'Bộ sưu tập của chúng tôi',
                        'sub-title-2' => 'Bộ sưu tập của chúng tôi',
                        'sub-title-3' => 'Bộ sưu tập của chúng tôi',
                        'sub-title-4' => 'Bộ sưu tập của chúng tôi',
                        'sub-title-5' => 'Bộ sưu tập của chúng tôi',
                        'sub-title-6' => 'Bộ sưu tập của chúng tôi',
                        'title'       => 'Trải nghiệm với những sản phẩm mới!',
                    ],

                    'name' => 'Bộ sưu tập hàng đầu',
                ],
            ],
        ],

        'user' => [
            'roles' => [
                'description' => 'Người dùng có vai trò này sẽ có toàn quyền truy cập',
                'name'        => 'Quản trị viên',
            ],

            'users' => [
                'name' => 'Ví dụ',
            ],
        ],

        'sample-categories' => [
            'category-translation' => [
                '2' => [
                    'description'      => 'Mô tả danh mục Nam',
                    'meta-description' => 'Mô tả meta danh mục Nam',
                    'meta-keywords'    => 'Từ khóa meta danh mục Nam',
                    'meta-title'       => 'Tiêu đề meta danh mục Nam',
                    'name'             => 'Nam',
                    'slug'             => 'nam',
                ],

                '3' => [
                    'description'      => 'Mô tả danh mục Trang phục mùa đông',
                    'meta-description' => 'Mô tả meta danh mục Trang phục mùa đông',
                    'meta-keywords'    => 'Từ khóa meta danh mục Trang phục mùa đông',
                    'meta-title'       => 'Tiêu đề meta danh mục Trang phục mùa đông',
                    'name'             => 'Trang phục mùa đông',
                    'slug'             => 'trang-phuc-mua-dong',
                ],
            ],
        ],

        'sample-products' => [
            'product-flat' => [
                '1' => [
                    'description'       => 'Mũ len Arctic Cozy Knit Beanie là giải pháp hoàn hảo để giữ ấm, thoải mái và phong cách trong những tháng lạnh. Được làm từ hỗn hợp acrylic mềm mại và bền bỉ, chiếc mũ này mang đến cảm giác ấm áp và vừa vặn. Thiết kế cổ điển phù hợp cho cả nam và nữ, là phụ kiện linh hoạt bổ sung cho nhiều phong cách khác nhau. Dù bạn ra ngoài dạo phố hay khám phá thiên nhiên, chiếc mũ len này sẽ mang đến sự thoải mái và ấm áp cho trang phục của bạn. Chất liệu mềm mại và thoáng khí giúp bạn luôn cảm thấy dễ chịu mà không ảnh hưởng đến phong cách. Mũ len Arctic Cozy Knit Beanie không chỉ là một phụ kiện; nó còn là tuyên ngôn thời trang mùa đông. Sự đơn giản của nó giúp bạn dễ dàng kết hợp với nhiều trang phục khác nhau, trở thành món đồ không thể thiếu trong tủ đồ mùa đông của bạn. Đây cũng là một lựa chọn quà tặng ý nghĩa hoặc một món quà đặc biệt dành cho bản thân. Đón nhận mùa đông cùng với Arctic Cozy Knit Beanie – người bạn đồng hành mang đến sự ấm áp và phong cách.',
                    'meta-description'  => 'mô tả meta',
                    'meta-keywords'     => 'meta1, meta2, meta3',
                    'meta-title'        => 'Tiêu đề Meta',
                    'name'              => 'Mũ Len Unisex Arctic Cozy Knit',
                    'short-description' => 'Chào đón những ngày lạnh với phong cách cùng Mũ Len Arctic Cozy Knit. Được làm từ hỗn hợp acrylic mềm mại và bền bỉ, chiếc mũ cổ điển này mang đến sự ấm áp và linh hoạt. Phù hợp cho cả nam và nữ, đây là phụ kiện lý tưởng cho trang phục thường ngày hoặc hoạt động ngoài trời.',
                ],

                '2' => [
                    'description'       => 'Khăn quàng cổ Arctic Bliss Winter không chỉ là một phụ kiện mùa đông, mà còn là tuyên ngôn của sự ấm áp, thoải mái và phong cách. Được chế tác tỉ mỉ từ hỗn hợp acrylic và len cao cấp, chiếc khăn này giúp bạn giữ ấm ngay cả trong thời tiết lạnh nhất. Kết cấu mềm mại và sang trọng không chỉ cách nhiệt chống lại cái lạnh mà còn mang đến nét tinh tế cho trang phục mùa đông của bạn. Thiết kế thời trang và linh hoạt, dễ dàng kết hợp với nhiều kiểu trang phục khác nhau. Dù bạn đang chuẩn bị cho một dịp đặc biệt hay chỉ đơn giản là muốn bổ sung một lớp phong cách cho trang phục hàng ngày, khăn Arctic Bliss Winter sẽ giúp bạn nổi bật. Chiều dài vượt trội của khăn cho phép bạn biến tấu nhiều kiểu dáng khác nhau, từ quấn chặt để giữ ấm đến buông lơi để tạo phong cách. Đây là món quà lý tưởng để tặng người thân hoặc tự thưởng cho bản thân. Đón nhận mùa đông cùng với Khăn Quàng Cổ Arctic Bliss Winter – nơi phong cách và sự ấm áp hòa quyện hoàn hảo.',
                    'meta-description'  => 'mô tả meta',
                    'meta-keywords'     => 'meta1, meta2, meta3',
                    'meta-title'        => 'Tiêu đề Meta',
                    'name'              => 'Khăn Quàng Cổ Thời Trang Arctic Bliss Winter',
                    'short-description' => 'Trải nghiệm sự ấm áp và phong cách với Khăn Quàng Cổ Arctic Bliss Winter. Được làm từ hỗn hợp acrylic và len cao cấp, chiếc khăn này giúp bạn giữ ấm trong những ngày lạnh giá. Thiết kế thời trang, linh hoạt với chiều dài vượt trội cho phép biến tấu nhiều phong cách khác nhau.',
                ],

                '3' => [
                    'description'       => 'Giới thiệu Găng Tay Mùa Đông Cảm Ứng Arctic – nơi sự ấm áp, phong cách và kết nối công nghệ hội tụ để nâng cao trải nghiệm mùa đông của bạn. Được làm từ acrylic cao cấp, những chiếc găng tay này mang đến sự ấm áp và bền bỉ vượt trội. Các đầu ngón tay cảm ứng cho phép bạn sử dụng điện thoại mà không cần tháo găng, giúp bạn dễ dàng trả lời cuộc gọi, gửi tin nhắn hoặc điều hướng thiết bị của mình ngay cả trong thời tiết lạnh giá. Lớp lót cách nhiệt mang lại cảm giác ấm áp tối ưu, lý tưởng để sử dụng khi đi làm, đi dạo phố hoặc tham gia các hoạt động ngoài trời. Cổ tay đàn hồi giúp giữ găng cố định, ngăn gió lạnh luồn vào. Thiết kế thời trang mang đến sự tinh tế cho trang phục mùa đông của bạn. Đây cũng là món quà hoàn hảo cho bản thân hoặc những người thân yêu. Hãy tạm biệt sự bất tiện khi tháo găng tay để sử dụng điện thoại và tận hưởng sự kết hợp hoàn hảo giữa công nghệ, sự ấm áp và phong cách. Giữ kết nối, giữ ấm và phong cách với Găng Tay Cảm Ứng Arctic – người bạn đồng hành đáng tin cậy của bạn trong mùa đông.',
                    'meta-description'  => 'mô tả meta',
                    'meta-keywords'     => 'meta1, meta2, meta3',
                    'meta-title'        => 'Tiêu đề Meta',
                    'name'              => 'Găng Tay Cảm Ứng Mùa Đông Arctic',
                    'short-description' => 'Giữ ấm và kết nối với Găng Tay Cảm Ứng Arctic. Được làm từ acrylic cao cấp, găng tay này có thiết kế tương thích cảm ứng, lớp lót cách nhiệt và cổ tay đàn hồi, mang đến sự ấm áp và phong cách tối đa.',
                ],

                '4' => [
                    'description'       => 'Giới thiệu Tất Len Pha Ấm Áp Arctic – người bạn đồng hành lý tưởng giúp đôi chân bạn luôn ấm áp và thoải mái trong những ngày lạnh. Được làm từ sự kết hợp cao cấp của len Merino, acrylic, nylon và spandex, tất này mang lại sự ấm áp vượt trội và cảm giác dễ chịu. Thành phần len giúp giữ ấm ngay cả trong nhiệt độ thấp nhất, là lựa chọn hoàn hảo cho các hoạt động ngoài trời hoặc thư giãn tại nhà. Chất liệu mềm mại mang lại cảm giác sang trọng khi tiếp xúc với da. Phần gót và mũi tất được gia cố giúp tăng độ bền, đảm bảo sử dụng lâu dài mà vẫn giữ nguyên sự thoải mái. Với khả năng thoáng khí, tất giúp đôi chân luôn khô ráo, không bị bí bách suốt cả ngày. Dù bạn đi bộ đường dài vào mùa đông hay chỉ ở nhà thư giãn, tất len này mang đến sự cân bằng hoàn hảo giữa sự ấm áp và thoáng mát. Kiểu dáng thời trang và đa năng giúp bạn dễ dàng phối với nhiều trang phục khác nhau, từ giày bốt đến giày thể thao hay đi chân trần trong nhà. Hãy nâng tầm tủ đồ mùa đông của bạn và tận hưởng sự thoải mái với Tất Len Pha Ấm Áp Arctic – một món quà hoàn hảo cho bản thân hoặc những người thân yêu.',
                    'meta-description'  => 'Tất len pha chất lượng cao mang đến sự ấm áp và thoải mái tối đa.',
                    'meta-keywords'     => 'tất len, tất giữ ấm, tất mùa đông, tất len Merino',
                    'meta-title'        => 'Tất Len Pha Ấm Áp Arctic',
                    'name'              => 'Tất Len Pha Ấm Áp Arctic',
                    'short-description' => 'Tất len pha với len Merino, acrylic, nylon và spandex mang đến sự ấm áp vượt trội, thoáng khí và bền bỉ, lý tưởng cho mùa đông.',
                ],

                '5' => [
                    'description'       => 'Giới thiệu Bộ Phụ Kiện Mùa Đông Arctic Frost – giải pháp hoàn hảo để giữ ấm, phong cách và tiện lợi trong những ngày lạnh giá. Bộ sưu tập này bao gồm bốn phụ kiện mùa đông thiết yếu giúp bạn luôn ấm áp và thời trang. Chiếc khăn len sang trọng, được dệt từ sự pha trộn giữa acrylic và len, không chỉ giữ ấm mà còn mang đến vẻ thanh lịch cho trang phục mùa đông của bạn. Chiếc mũ len mềm mại, được thiết kế tỉ mỉ, mang lại sự thoải mái tối đa và giúp bạn trông sành điệu hơn. Ngoài ra, bộ sản phẩm còn có găng tay cảm ứng, cho phép bạn sử dụng điện thoại mà không cần tháo găng, giúp giữ ấm tay trong mọi điều kiện. Cuối cùng, đôi tất len pha cao cấp sẽ giúp đôi chân bạn luôn ấm áp và dễ chịu. Bộ phụ kiện này không chỉ là vật dụng thiết yếu mà còn là tuyên ngôn thời trang mùa đông. Mỗi món đồ đều được lựa chọn kỹ lưỡng để mang lại sự bền bỉ, thoải mái và phù hợp với nhiều phong cách khác nhau. Đây là món quà tuyệt vời cho những người thân yêu vào mùa lễ hội hoặc là sự bổ sung hoàn hảo cho tủ đồ mùa đông của bạn. Hãy tự tin đón nhận mùa đông với Bộ Phụ Kiện Mùa Đông Arctic Frost – bộ sản phẩm giúp bạn luôn ấm áp và phong cách.',
                    'meta-description'  => 'Bộ phụ kiện mùa đông gồm khăn, mũ, găng tay cảm ứng và tất len giúp giữ ấm và phong cách.',
                    'meta-keywords'     => 'bộ phụ kiện mùa đông, khăn len, mũ len, găng tay cảm ứng, tất len',
                    'meta-title'        => 'Bộ Phụ Kiện Mùa Đông Arctic Frost',
                    'name'              => 'Bộ Phụ Kiện Mùa Đông Arctic Frost',
                    'short-description' => 'Bộ phụ kiện mùa đông gồm khăn len, mũ len, găng tay cảm ứng và tất len pha giúp giữ ấm, bền bỉ và thời trang.',
                ],

                '6' => [
                    'description'       => 'Giới thiệu Bộ Phụ Kiện Mùa Đông Arctic Frost – giải pháp hoàn hảo để giữ ấm, phong cách và tiện lợi trong những ngày lạnh giá. Bộ sưu tập này bao gồm bốn phụ kiện mùa đông thiết yếu giúp bạn luôn ấm áp và thời trang. Chiếc khăn len sang trọng, được dệt từ sự pha trộn giữa acrylic và len, không chỉ giữ ấm mà còn mang đến vẻ thanh lịch cho trang phục mùa đông của bạn. Chiếc mũ len mềm mại, được thiết kế tỉ mỉ, mang lại sự thoải mái tối đa và giúp bạn trông sành điệu hơn. Ngoài ra, bộ sản phẩm còn có găng tay cảm ứng, cho phép bạn sử dụng điện thoại mà không cần tháo găng, giúp giữ ấm tay trong mọi điều kiện. Cuối cùng, đôi tất len pha cao cấp sẽ giúp đôi chân bạn luôn ấm áp và dễ chịu. Bộ phụ kiện này không chỉ là vật dụng thiết yếu mà còn là tuyên ngôn thời trang mùa đông. Mỗi món đồ đều được lựa chọn kỹ lưỡng để mang lại sự bền bỉ, thoải mái và phù hợp với nhiều phong cách khác nhau. Đây là món quà tuyệt vời cho những người thân yêu vào mùa lễ hội hoặc là sự bổ sung hoàn hảo cho tủ đồ mùa đông của bạn. Hãy tự tin đón nhận mùa đông với Bộ Phụ Kiện Mùa Đông Arctic Frost – bộ sản phẩm giúp bạn luôn ấm áp và phong cách.',
                    'meta-description'  => 'Bộ phụ kiện mùa đông gồm khăn, mũ, găng tay cảm ứng và tất len giúp giữ ấm và phong cách.',
                    'meta-keywords'     => 'bộ phụ kiện mùa đông, khăn len, mũ len, găng tay cảm ứng, tất len',
                    'meta-title'        => 'Bộ Phụ Kiện Mùa Đông Arctic Frost',
                    'name'              => 'Bộ Phụ Kiện Mùa Đông Arctic Frost',
                    'short-description' => 'Bộ phụ kiện mùa đông gồm khăn len, mũ len, găng tay cảm ứng và tất len pha giúp giữ ấm, bền bỉ và thời trang.',
                ],

                '7' => [
                    'description'       => 'Giới thiệu Áo Puffer Có Mũ OmniHeat Nam – giải pháp hoàn hảo giúp bạn luôn ấm áp và thời trang trong những ngày lạnh. Chiếc áo này được thiết kế với độ bền cao và khả năng giữ ấm vượt trội, trở thành người bạn đồng hành tin cậy của bạn. Thiết kế có mũ không chỉ tạo điểm nhấn thời trang mà còn tăng cường khả năng giữ ấm, bảo vệ bạn khỏi gió lạnh và thời tiết khắc nghiệt. Tay áo dài giúp che phủ hoàn toàn, giữ ấm từ vai đến cổ tay. Được trang bị túi chèn tiện lợi, áo khoác này giúp bạn dễ dàng mang theo các vật dụng cần thiết hoặc giữ ấm tay. Lớp lót cách nhiệt tổng hợp mang lại sự ấm áp tối ưu, lý tưởng để chống chọi với những ngày và đêm giá lạnh. Với vỏ ngoài và lớp lót làm từ polyester bền bỉ, áo khoác này được thiết kế để sử dụng lâu dài và chống chịu thời tiết. Có sẵn 5 màu sắc đẹp mắt, bạn có thể lựa chọn màu phù hợp với phong cách và sở thích của mình. Đa năng và tiện dụng, Áo Puffer Có Mũ OmniHeat Nam phù hợp cho nhiều dịp khác nhau, từ đi làm, đi chơi đến tham gia các sự kiện ngoài trời. Trải nghiệm sự kết hợp hoàn hảo giữa phong cách, sự thoải mái và tính năng với Áo Puffer Có Mũ OmniHeat Nam. Nâng tầm tủ đồ mùa đông của bạn và giữ ấm trong khi tận hưởng không gian ngoài trời. Đánh bại cái lạnh với phong cách và tạo dấu ấn với chiếc áo khoác thiết yếu này.',
                    'meta-description'  => 'Áo puffer có mũ giữ ấm với thiết kế phong cách và tiện dụng.',
                    'meta-keywords'     => 'áo khoác nam, áo puffer, áo giữ ấm, áo mùa đông',
                    'meta-title'        => 'Áo Puffer Có Mũ OmniHeat Nam',
                    'name'              => 'Áo Puffer Có Mũ OmniHeat Nam',
                    'short-description' => 'Giữ ấm và thời trang với Áo Puffer Có Mũ OmniHeat Nam. Được thiết kế với chất liệu cách nhiệt, túi tiện lợi và có sẵn 5 màu sắc đẹp mắt, phù hợp cho nhiều dịp.',
                ],

                '8' => [
                    'description'       => 'Giới thiệu Áo Puffer Có Mũ OmniHeat Nam – giải pháp hoàn hảo giúp bạn luôn ấm áp và thời trang trong những ngày lạnh. Chiếc áo này được thiết kế với độ bền cao và khả năng giữ ấm vượt trội, trở thành người bạn đồng hành tin cậy của bạn. Thiết kế có mũ không chỉ tạo điểm nhấn thời trang mà còn tăng cường khả năng giữ ấm, bảo vệ bạn khỏi gió lạnh và thời tiết khắc nghiệt. Tay áo dài giúp che phủ hoàn toàn, giữ ấm từ vai đến cổ tay. Được trang bị túi chèn tiện lợi, áo khoác này giúp bạn dễ dàng mang theo các vật dụng cần thiết hoặc giữ ấm tay. Lớp lót cách nhiệt tổng hợp mang lại sự ấm áp tối ưu, lý tưởng để chống chọi với những ngày và đêm giá lạnh. Với vỏ ngoài và lớp lót làm từ polyester bền bỉ, áo khoác này được thiết kế để sử dụng lâu dài và chống chịu thời tiết. Có sẵn 5 màu sắc đẹp mắt, bạn có thể lựa chọn màu phù hợp với phong cách và sở thích của mình. Đa năng và tiện dụng, Áo Puffer Có Mũ OmniHeat Nam phù hợp cho nhiều dịp khác nhau, từ đi làm, đi chơi đến tham gia các sự kiện ngoài trời. Trải nghiệm sự kết hợp hoàn hảo giữa phong cách, sự thoải mái và tính năng với Áo Puffer Có Mũ OmniHeat Nam. Nâng tầm tủ đồ mùa đông của bạn và giữ ấm trong khi tận hưởng không gian ngoài trời. Đánh bại cái lạnh với phong cách và tạo dấu ấn với chiếc áo khoác thiết yếu này.',
                    'meta-description'  => 'Áo puffer có mũ giữ ấm với thiết kế phong cách và tiện dụng.',
                    'meta-keywords'     => 'áo khoác nam, áo puffer, áo giữ ấm, áo mùa đông',
                    'meta-title'        => 'Áo Puffer Có Mũ OmniHeat Nam - Xanh Dương - Vàng - M',
                    'name'              => 'Áo Puffer Có Mũ OmniHeat Nam - Xanh Dương - Vàng - M',
                    'short-description' => 'Giữ ấm và thời trang với Áo Puffer Có Mũ OmniHeat Nam. Được thiết kế với chất liệu cách nhiệt, túi tiện lợi và có sẵn 5 màu sắc đẹp mắt, phù hợp cho nhiều dịp.',
                ],

                '9' => [
                    'description'       => 'Giới thiệu Áo Puffer Có Mũ OmniHeat Nam – giải pháp hoàn hảo giúp bạn luôn ấm áp và thời trang trong những ngày lạnh. Chiếc áo này được thiết kế với độ bền cao và khả năng giữ ấm vượt trội, trở thành người bạn đồng hành tin cậy của bạn. Thiết kế có mũ không chỉ tạo điểm nhấn thời trang mà còn tăng cường khả năng giữ ấm, bảo vệ bạn khỏi gió lạnh và thời tiết khắc nghiệt. Tay áo dài giúp che phủ hoàn toàn, giữ ấm từ vai đến cổ tay. Được trang bị túi chèn tiện lợi, áo khoác này giúp bạn dễ dàng mang theo các vật dụng cần thiết hoặc giữ ấm tay. Lớp lót cách nhiệt tổng hợp mang lại sự ấm áp tối ưu, lý tưởng để chống chọi với những ngày và đêm giá lạnh. Với vỏ ngoài và lớp lót làm từ polyester bền bỉ, áo khoác này được thiết kế để sử dụng lâu dài và chống chịu thời tiết. Có sẵn 5 màu sắc đẹp mắt, bạn có thể lựa chọn màu phù hợp với phong cách và sở thích của mình. Đa năng và tiện dụng, Áo Puffer Có Mũ OmniHeat Nam phù hợp cho nhiều dịp khác nhau, từ đi làm, đi chơi đến tham gia các sự kiện ngoài trời. Trải nghiệm sự kết hợp hoàn hảo giữa phong cách, sự thoải mái và tính năng với Áo Puffer Có Mũ OmniHeat Nam. Nâng tầm tủ đồ mùa đông của bạn và giữ ấm trong khi tận hưởng không gian ngoài trời. Đánh bại cái lạnh với phong cách và tạo dấu ấn với chiếc áo khoác thiết yếu này.',
                    'meta-description'  => 'Áo puffer có mũ giữ ấm với thiết kế phong cách và tiện dụng.',
                    'meta-keywords'     => 'áo khoác nam, áo puffer, áo giữ ấm, áo mùa đông',
                    'meta-title'        => 'Áo Puffer Có Mũ OmniHeat Nam - Xanh Dương - Vàng - L',
                    'name'              => 'Áo Puffer Có Mũ OmniHeat Nam - Xanh Dương - Vàng - L',
                    'short-description' => 'Giữ ấm và thời trang với Áo Puffer Có Mũ OmniHeat Nam. Được thiết kế với chất liệu cách nhiệt, túi tiện lợi và có sẵn 5 màu sắc đẹp mắt, phù hợp cho nhiều dịp.',
                ],

                '10' => [
                    'description'       => 'Giới thiệu Áo Puffer Có Mũ OmniHeat Nam – giải pháp hoàn hảo giúp bạn luôn ấm áp và thời trang trong những ngày lạnh. Chiếc áo này được thiết kế với độ bền cao và khả năng giữ ấm vượt trội, trở thành người bạn đồng hành tin cậy của bạn. Thiết kế có mũ không chỉ tạo điểm nhấn thời trang mà còn tăng cường khả năng giữ ấm, bảo vệ bạn khỏi gió lạnh và thời tiết khắc nghiệt. Tay áo dài giúp che phủ hoàn toàn, giữ ấm từ vai đến cổ tay. Được trang bị túi chèn tiện lợi, áo khoác này giúp bạn dễ dàng mang theo các vật dụng cần thiết hoặc giữ ấm tay. Lớp lót cách nhiệt tổng hợp mang lại sự ấm áp tối ưu, lý tưởng để chống chọi với những ngày và đêm giá lạnh. Với vỏ ngoài và lớp lót làm từ polyester bền bỉ, áo khoác này được thiết kế để sử dụng lâu dài và chống chịu thời tiết. Có sẵn 5 màu sắc đẹp mắt, bạn có thể lựa chọn màu phù hợp với phong cách và sở thích của mình. Đa năng và tiện dụng, Áo Puffer Có Mũ OmniHeat Nam phù hợp cho nhiều dịp khác nhau, từ đi làm, đi chơi đến tham gia các sự kiện ngoài trời. Trải nghiệm sự kết hợp hoàn hảo giữa phong cách, sự thoải mái và tính năng với Áo Puffer Có Mũ OmniHeat Nam. Nâng tầm tủ đồ mùa đông của bạn và giữ ấm trong khi tận hưởng không gian ngoài trời. Đánh bại cái lạnh với phong cách và tạo dấu ấn với chiếc áo khoác thiết yếu này.',
                    'meta-description'  => 'Áo puffer có mũ giữ ấm với thiết kế phong cách và tiện dụng.',
                    'meta-keywords'     => 'áo khoác nam, áo puffer, áo giữ ấm, áo mùa đông',
                    'meta-title'        => 'Áo Puffer Có Mũ OmniHeat Nam - Xanh Dương - Xanh Lá - M',
                    'name'              => 'Áo Puffer Có Mũ OmniHeat Nam - Xanh Dương - Xanh Lá - M',
                    'short-description' => 'Giữ ấm và thời trang với Áo Puffer Có Mũ OmniHeat Nam. Được thiết kế với chất liệu cách nhiệt, túi tiện lợi và có sẵn 5 màu sắc đẹp mắt, phù hợp cho nhiều dịp.',
                ],

                '11' => [
                    'description'       => 'Giới thiệu Áo Puffer Có Mũ OmniHeat Nam – giải pháp hoàn hảo giúp bạn luôn ấm áp và thời trang trong những ngày lạnh. Chiếc áo này được thiết kế với độ bền cao và khả năng giữ ấm vượt trội, trở thành người bạn đồng hành tin cậy của bạn. Thiết kế có mũ không chỉ tạo điểm nhấn thời trang mà còn tăng cường khả năng giữ ấm, bảo vệ bạn khỏi gió lạnh và thời tiết khắc nghiệt. Tay áo dài giúp che phủ hoàn toàn, giữ ấm từ vai đến cổ tay. Được trang bị túi chèn tiện lợi, áo khoác này giúp bạn dễ dàng mang theo các vật dụng cần thiết hoặc giữ ấm tay. Lớp lót cách nhiệt tổng hợp mang lại sự ấm áp tối ưu, lý tưởng để chống chọi với những ngày và đêm giá lạnh. Với vỏ ngoài và lớp lót làm từ polyester bền bỉ, áo khoác này được thiết kế để sử dụng lâu dài và chống chịu thời tiết. Có sẵn 5 màu sắc đẹp mắt, bạn có thể lựa chọn màu phù hợp với phong cách và sở thích của mình. Đa năng và tiện dụng, Áo Puffer Có Mũ OmniHeat Nam phù hợp cho nhiều dịp khác nhau, từ đi làm, đi chơi đến tham gia các sự kiện ngoài trời. Trải nghiệm sự kết hợp hoàn hảo giữa phong cách, sự thoải mái và tính năng với Áo Puffer Có Mũ OmniHeat Nam. Nâng tầm tủ đồ mùa đông của bạn và giữ ấm trong khi tận hưởng không gian ngoài trời. Đánh bại cái lạnh với phong cách và tạo dấu ấn với chiếc áo khoác thiết yếu này.',
                    'meta-description'  => 'Áo puffer có mũ giữ ấm với thiết kế phong cách và tiện dụng.',
                    'meta-keywords'     => 'áo khoác nam, áo puffer, áo giữ ấm, áo mùa đông',
                    'meta-title'        => 'Áo Puffer Có Mũ OmniHeat Nam - Xanh Dương - Xanh Lá - L',
                    'name'              => 'Áo Puffer Có Mũ OmniHeat Nam - Xanh Dương - Xanh Lá - L',
                    'short-description' => 'Giữ ấm và thời trang với Áo Puffer Có Mũ OmniHeat Nam. Được thiết kế với chất liệu cách nhiệt, túi tiện lợi và có sẵn 5 màu sắc đẹp mắt, phù hợp cho nhiều dịp.',
                ],
            ],

            'product-attribute-values' => [
                '1' => [
                    'description'      => 'Mũ len Arctic Cozy Knit là giải pháp hoàn hảo giúp bạn luôn ấm áp, thoải mái và phong cách trong những tháng lạnh. Được làm từ sợi acrylic mềm mại và bền bỉ, chiếc mũ này mang đến cảm giác ôm sát và ấm áp. Thiết kế cổ điển phù hợp cho cả nam và nữ, trở thành một phụ kiện linh hoạt có thể kết hợp với nhiều phong cách khác nhau. Dù bạn ra ngoài dạo phố hay khám phá thiên nhiên, chiếc mũ này sẽ bổ sung sự thoải mái và ấm áp cho trang phục của bạn. Chất liệu mềm mại và thoáng khí giúp bạn luôn cảm thấy dễ chịu mà không làm mất đi phong cách. Mũ len Arctic Cozy Knit không chỉ là một phụ kiện mà còn là một tuyên ngôn thời trang mùa đông. Sự đơn giản của nó giúp bạn dễ dàng phối với nhiều loại trang phục khác nhau, trở thành một món đồ không thể thiếu trong tủ đồ mùa đông của bạn. Đây cũng là một món quà tuyệt vời hoặc là một lựa chọn lý tưởng để tự thưởng cho bản thân. Chiếc mũ này không chỉ mang tính tiện dụng mà còn giúp bạn thể hiện phong cách riêng với sự ấm áp và thanh lịch. Hãy tận hưởng mùa đông với mũ len Arctic Cozy Knit. Dù bạn đi dạo hay đối mặt với thời tiết khắc nghiệt, chiếc mũ này sẽ là người bạn đồng hành lý tưởng mang đến sự thoải mái và thời trang. Nâng cấp tủ đồ mùa đông của bạn với chiếc mũ cổ điển kết hợp hoàn hảo giữa sự ấm áp và phong cách vượt thời gian.',
                    'meta-description' => 'Mũ len unisex mềm mại, phong cách và ấm áp cho mùa đông.',
                    'meta-keywords'    => 'mũ len, mũ mùa đông, mũ unisex, phụ kiện thời trang',
                    'meta-title'       => 'Mũ Len Arctic Cozy Knit Unisex',
                    'name'             => 'Mũ Len Arctic Cozy Knit Unisex',
                    'sort-description' => 'Tận hưởng những ngày lạnh với phong cách cùng Mũ Len Arctic Cozy Knit. Được làm từ sợi acrylic mềm mại và bền bỉ, chiếc mũ này mang đến sự ấm áp và linh hoạt. Phù hợp cho cả nam và nữ, đây là phụ kiện lý tưởng cho các hoạt động thường ngày hoặc ngoài trời. Hoàn hảo để nâng cấp tủ đồ mùa đông hoặc làm quà tặng ý nghĩa.',
                ],

                '2' => [
                    'description'      => 'Khăn quàng mùa đông Arctic Bliss không chỉ là một phụ kiện giữ ấm mà còn là biểu tượng của sự thoải mái, phong cách và ấm áp trong mùa lạnh. Được chế tác tỉ mỉ từ sự kết hợp sang trọng giữa sợi acrylic và len, chiếc khăn này mang đến cảm giác ấm áp và dễ chịu ngay cả trong những ngày giá rét nhất. Kết cấu mềm mại và mịn màng không chỉ giúp cách nhiệt khỏi cái lạnh mà còn tạo thêm nét sang trọng cho trang phục mùa đông của bạn. Thiết kế của khăn quàng Arctic Bliss vừa phong cách vừa linh hoạt, khiến nó trở thành một lựa chọn hoàn hảo để kết hợp với nhiều trang phục mùa đông khác nhau. Dù bạn đang diện đồ cho một dịp đặc biệt hay chỉ muốn tạo điểm nhấn cho trang phục hàng ngày, chiếc khăn này sẽ giúp bạn thể hiện phong cách một cách tự nhiên. Độ dài vượt trội của chiếc khăn cho phép bạn tùy chỉnh nhiều kiểu quàng khác nhau. Bạn có thể quấn chặt để giữ ấm tối đa, buông lỏng để tạo vẻ ngoài giản dị, hoặc thử nghiệm với các kiểu thắt sáng tạo để thể hiện phong cách cá nhân. Chính sự linh hoạt này khiến nó trở thành một phụ kiện không thể thiếu trong mùa đông. Bạn đang tìm kiếm một món quà hoàn hảo? Khăn quàng mùa đông Arctic Bliss là lựa chọn lý tưởng. Dù là dành tặng người thân yêu hay tự thưởng cho bản thân, đây là món quà thiết thực và tinh tế sẽ được trân trọng suốt cả mùa đông. Đón chào mùa đông với khăn quàng Arctic Bliss, nơi sự ấm áp hòa quyện hoàn hảo với phong cách. Nâng tầm tủ đồ mùa đông của bạn với phụ kiện thiết yếu này – không chỉ giữ ấm mà còn tăng thêm nét thanh lịch cho trang phục mùa lạnh.',
                    'meta-description' => 'Khăn quàng cổ ấm áp, sang trọng và phong cách cho mùa đông.',
                    'meta-keywords'    => 'khăn quàng, khăn len, phụ kiện mùa đông, khăn sang trọng',
                    'meta-title'       => 'Khăn Quàng Mùa Đông Arctic Bliss Sang Trọng',
                    'name'             => 'Khăn Quàng Mùa Đông Arctic Bliss Sang Trọng',
                    'sort-description' => 'Trải nghiệm sự ấm áp và phong cách với Khăn Quàng Mùa Đông Arctic Bliss. Được làm từ sự kết hợp sang trọng giữa sợi acrylic và len, chiếc khăn này giúp bạn luôn ấm áp trong những ngày lạnh giá. Thiết kế thanh lịch, linh hoạt với độ dài vượt trội mang đến nhiều cách phối độc đáo. Nâng tầm phong cách mùa đông của bạn hoặc tặng quà ý nghĩa với phụ kiện không thể thiếu này.',
                ],

                '3' => [
                    'description'      => 'Giới thiệu Găng Tay Mùa Đông Cảm Ứng Arctic – nơi sự ấm áp, phong cách và kết nối hòa quyện để nâng cao trải nghiệm mùa đông của bạn. Được chế tác từ chất liệu acrylic cao cấp, đôi găng tay này mang lại sự ấm áp vượt trội và độ bền cao. Đầu ngón tay cảm ứng giúp bạn dễ dàng sử dụng thiết bị mà không cần tháo găng, giúp bạn có thể trả lời cuộc gọi, gửi tin nhắn và điều hướng thiết bị một cách thuận tiện ngay cả trong thời tiết lạnh giá. Lớp lót cách nhiệt tăng thêm sự ấm áp, biến đôi găng tay này thành lựa chọn lý tưởng để chống chọi với mùa đông. Dù bạn đang đi làm, mua sắm hay tham gia các hoạt động ngoài trời, đôi găng tay này mang đến sự bảo vệ và ấm áp mà bạn cần. Cổ tay co giãn đảm bảo độ ôm vừa vặn, ngăn chặn gió lạnh và giữ găng tay cố định trong suốt các hoạt động hàng ngày. Thiết kế thời trang giúp đôi găng tay này không chỉ tiện lợi mà còn nâng tầm phong cách mùa đông của bạn. Đây cũng là món quà hoàn hảo dành tặng người thân hoặc một sự đầu tư tuyệt vời cho bản thân. Hãy nói lời tạm biệt với sự bất tiện khi phải tháo găng tay để sử dụng thiết bị và tận hưởng sự kết hợp hoàn hảo giữa sự ấm áp, phong cách và công nghệ. Giữ ấm, kết nối và phong cách cùng Găng Tay Mùa Đông Cảm Ứng Arctic – người bạn đồng hành đáng tin cậy giúp bạn chinh phục mùa đông đầy tự tin.',
                    'meta-description' => 'Găng tay mùa đông cảm ứng tiện lợi, giữ ấm và phong cách.',
                    'meta-keywords'    => 'găng tay cảm ứng, găng tay mùa đông, phụ kiện mùa đông, găng tay thời trang',
                    'meta-title'       => 'Găng Tay Cảm Ứng Mùa Đông Arctic – Ấm Áp & Thời Trang',
                    'name'             => 'Găng Tay Cảm Ứng Mùa Đông Arctic',
                    'sort-description' => 'Giữ kết nối và ấm áp cùng Găng Tay Mùa Đông Cảm Ứng Arctic. Được làm từ chất liệu acrylic cao cấp, đôi găng tay này không chỉ bền bỉ và ấm áp mà còn tích hợp tính năng cảm ứng tiện lợi. Lớp lót cách nhiệt, cổ tay co giãn giúp giữ nhiệt tối đa, mang lại sự thoải mái và phong cách cho những ngày lạnh giá.',
                ],

                '4' => [
                    'description'      => 'Giới thiệu Tất Len Pha Ấm Áp Arctic Warmth – người bạn đồng hành không thể thiếu để giữ cho đôi chân của bạn luôn ấm áp và thoải mái trong mùa lạnh. Được làm từ sự kết hợp cao cấp của len Merino, acrylic, nylon và spandex, đôi tất này mang đến sự ấm áp và mềm mại vượt trội. Chất liệu len giúp giữ nhiệt hiệu quả ngay cả trong điều kiện lạnh giá nhất, là lựa chọn hoàn hảo cho những chuyến phiêu lưu mùa đông hoặc đơn giản là thư giãn tại nhà. Cảm giác mềm mại, êm ái mang lại sự sang trọng cho đôi chân bạn. Thiết kế bền bỉ với gót chân và mũi tất gia cố, giúp tăng cường độ bền tại những khu vực dễ mài mòn. Ngoài ra, chất liệu thoáng khí giúp ngăn ngừa cảm giác bí bách, giữ cho đôi chân luôn thoải mái và khô ráo suốt cả ngày. Dù bạn đang đi bộ đường dài hay chỉ muốn thư giãn trong nhà, đôi tất này mang đến sự cân bằng hoàn hảo giữa sự ấm áp và thoáng mát. Hãy nâng cấp tủ đồ mùa đông của bạn với Tất Len Pha Ấm Áp Arctic Warmth – bước vào thế giới của sự thoải mái kéo dài cả mùa đông.',
                    'meta-description' => 'Tất len pha cao cấp Arctic Warmth – giữ ấm, mềm mại và bền bỉ.',
                    'meta-keywords'    => 'tất len ấm, tất mùa đông, tất len Merino, phụ kiện mùa lạnh',
                    'meta-title'       => 'Tất Len Pha Ấm Áp Arctic Warmth – Giữ Ấm & Thoải Mái',
                    'name'             => 'Tất Len Pha Ấm Áp Arctic Warmth',
                    'sort-description' => 'Trải nghiệm sự ấm áp vượt trội với Tất Len Pha Arctic Warmth. Được làm từ len Merino pha trộn với acrylic, nylon và spandex, mang lại sự mềm mại, thoải mái và bền bỉ. Gót chân và mũi tất gia cố giúp tăng độ bền, lý tưởng cho nhiều hoàn cảnh khác nhau.',
                ],

                '5' => [
                    'description'      => 'Giới thiệu Bộ Phụ Kiện Mùa Đông Arctic Frost – sự lựa chọn hoàn hảo để giữ ấm, phong cách và kết nối trong những ngày đông lạnh giá. Bộ sưu tập tinh tế này bao gồm bốn món phụ kiện không thể thiếu, tạo nên một tổng thể hài hòa. Chiếc khăn choàng sang trọng, được dệt từ sự pha trộn giữa len và acrylic, không chỉ giữ ấm mà còn mang lại vẻ thanh lịch cho trang phục mùa đông của bạn. Mũ len mềm mại giúp bạn luôn ấm áp và tạo điểm nhấn phong cách. Không dừng lại ở đó, bộ sản phẩm còn bao gồm găng tay cảm ứng tiện lợi, giúp bạn sử dụng điện thoại mà không cần tháo găng. Cuối cùng, tất len pha giữ nhiệt tối đa, mang lại sự mềm mại và ấm áp tuyệt vời. Bộ Phụ Kiện Mùa Đông Arctic Frost không chỉ mang tính thực tiễn mà còn là tuyên ngôn thời trang mùa đông. Các chất liệu được chọn lọc kỹ lưỡng để đảm bảo cả độ bền và sự thoải mái, giúp bạn tự tin tận hưởng mùa đông. Dù là món quà hoàn hảo dành tặng người thân hay một sự đầu tư cho bản thân, đây là lựa chọn lý tưởng để vừa giữ ấm vừa thể hiện phong cách trong mùa lạnh.',
                    'meta-description' => 'Bộ phụ kiện mùa đông Arctic Frost – phong cách, ấm áp và tiện lợi.',
                    'meta-keywords'    => 'phụ kiện mùa đông, khăn len, mũ len, găng tay cảm ứng, tất len',
                    'meta-title'       => 'Bộ Phụ Kiện Mùa Đông Arctic Frost – Ấm Áp & Phong Cách',
                    'name'             => 'Bộ Phụ Kiện Mùa Đông Arctic Frost',
                    'sort-description' => 'Sẵn sàng cho mùa đông với Bộ Phụ Kiện Arctic Frost gồm khăn choàng len, mũ len, găng tay cảm ứng và tất len pha cao cấp. Bộ sản phẩm được thiết kế để mang lại sự ấm áp tối đa mà vẫn đảm bảo phong cách. Hoàn hảo để tự thưởng hoặc làm quà tặng mùa đông.',
                ],

                '6' => [
                    'description'      => 'Giới thiệu Bộ Phụ Kiện Mùa Đông Arctic Frost – giải pháp hoàn hảo giúp bạn luôn ấm áp, phong cách và kết nối trong những ngày đông lạnh giá. Bộ sưu tập này gồm bốn món phụ kiện thiết yếu, tạo nên một tổng thể hoàn chỉnh. Chiếc khăn choàng cao cấp, được dệt từ sự pha trộn giữa len và acrylic, mang lại sự ấm áp và sang trọng. Mũ len mềm mại giữ ấm tuyệt đối đồng thời tôn lên phong cách cá nhân. Ngoài ra, bộ sản phẩm còn có găng tay cảm ứng, cho phép bạn sử dụng điện thoại mà không cần tháo găng, đảm bảo tiện lợi mà vẫn giữ ấm đôi tay. Cuối cùng, tất len pha cao cấp giúp đôi chân bạn luôn mềm mại và ấm áp. Bộ Phụ Kiện Mùa Đông Arctic Frost không chỉ hữu ích mà còn là tuyên ngôn thời trang mùa đông. Với chất liệu bền bỉ và thoải mái, bạn có thể tự tin tận hưởng mùa đông một cách trọn vẹn. Dù là một món quà ý nghĩa dành cho người thân hay một sự đầu tư cho bản thân, đây chính là lựa chọn hoàn hảo để vừa giữ ấm vừa thể hiện phong cách.',
                    'meta-description' => 'Bộ phụ kiện mùa đông Arctic Frost – sự kết hợp hoàn hảo giữa phong cách và tiện ích.',
                    'meta-keywords'    => 'phụ kiện mùa đông, khăn len, mũ len, găng tay cảm ứng, tất len',
                    'meta-title'       => 'Bộ Phụ Kiện Mùa Đông Arctic Frost – Ấm Áp & Phong Cách',
                    'name'             => 'Bộ Phụ Kiện Mùa Đông Arctic Frost',
                    'sort-description' => 'Sẵn sàng cho mùa đông với Bộ Phụ Kiện Arctic Frost gồm khăn len, mũ len, găng tay cảm ứng và tất len pha cao cấp. Được thiết kế với chất liệu bền bỉ và phong cách thời thượng, đây là lựa chọn hoàn hảo cho mùa lạnh.',
                ],

                '7' => [
                    'description'      => 'Giới thiệu Áo Khoác Puffer Nam OmniHeat có Mũ – sự kết hợp hoàn hảo giữa phong cách, độ bền và khả năng giữ ấm vượt trội. Được thiết kế với lớp cách nhiệt tổng hợp, chiếc áo khoác này giúp bạn giữ nhiệt tối đa, bảo vệ cơ thể khỏi những cơn gió lạnh mùa đông. Thiết kế có mũ không chỉ tạo điểm nhấn thời trang mà còn tăng cường sự ấm áp, giúp bạn đối phó với thời tiết khắc nghiệt. Tay áo dài giúp che chắn toàn bộ cánh tay, mang lại sự thoải mái tối đa. Với túi bên tiện lợi, bạn có thể giữ ấm tay hoặc cất giữ các vật dụng cá nhân một cách an toàn. Lớp vỏ ngoài và lót trong bằng polyester cao cấp giúp áo bền bỉ, chống thấm nhẹ và chịu được tác động của thời tiết. Áo có sẵn 5 màu sắc hiện đại, giúp bạn dễ dàng chọn lựa phong cách phù hợp. Dù bạn đi làm, dạo phố hay tham gia các hoạt động ngoài trời, Áo Khoác Puffer Nam OmniHeat có Mũ sẽ là lựa chọn lý tưởng. Hãy trải nghiệm sự kết hợp hoàn hảo giữa thời trang và chức năng với chiếc áo khoác không thể thiếu trong mùa đông này.',
                    'meta-description' => 'Áo khoác Puffer nam OmniHeat – giữ ấm tối đa, phong cách và tiện lợi.',
                    'meta-keywords'    => 'áo khoác puffer, áo khoác nam mùa đông, áo khoác có mũ, thời trang nam',
                    'meta-title'       => 'Áo Khoác Puffer Nam OmniHeat có Mũ – Giữ Ấm & Phong Cách',
                    'name'             => 'Áo Khoác Puffer Nam OmniHeat có Mũ',
                    'sort-description' => 'Giữ ấm và phong cách với Áo Khoác Puffer Nam OmniHeat có Mũ. Thiết kế cách nhiệt giúp bảo vệ cơ thể khỏi lạnh giá, túi tiện lợi và chất liệu polyester bền bỉ. Có sẵn 5 màu sắc để bạn lựa chọn, phù hợp cho mọi dịp.',
                ],

                '8' => [
                    'description'      => 'Giới thiệu Áo Khoác Puffer Nam OmniHeat có Mũ – phiên bản màu Xanh-Vàng (Size M), một sự kết hợp hoàn hảo giữa phong cách, độ bền và khả năng giữ ấm vượt trội. Thiết kế có mũ giúp bảo vệ bạn khỏi gió lạnh, mang lại sự ấm áp tối đa. Tay áo dài giúp che chắn toàn bộ cánh tay, mang lại sự thoải mái tối đa. Với túi bên tiện lợi, bạn có thể giữ ấm tay hoặc cất giữ các vật dụng cá nhân một cách an toàn. Lớp vỏ ngoài và lót trong bằng polyester cao cấp giúp áo bền bỉ, chống thấm nhẹ và chịu được tác động của thời tiết. Được cách nhiệt bằng lớp tổng hợp, chiếc áo này giữ nhiệt hiệu quả, đảm bảo bạn luôn ấm áp ngay cả trong những ngày lạnh nhất. Với phiên bản màu Xanh-Vàng nổi bật, đây là sự lựa chọn hoàn hảo cho những ai yêu thích sự trẻ trung và năng động.',
                    'meta-description' => 'Áo khoác Puffer nam OmniHeat màu Xanh-Vàng (Size M) – giữ ấm tối đa, phong cách hiện đại.',
                    'meta-keywords'    => 'áo khoác puffer, áo khoác nam mùa đông, áo khoác có mũ, thời trang nam, xanh vàng',
                    'meta-title'       => 'Áo Khoác Puffer Nam OmniHeat có Mũ - Xanh Vàng (Size M)',
                    'name'             => 'Áo Khoác Puffer Nam OmniHeat có Mũ - Xanh Vàng (Size M)',
                    'sort-description' => 'Giữ ấm và phong cách với Áo Khoác Puffer Nam OmniHeat có Mũ (Màu Xanh-Vàng, Size M). Thiết kế cách nhiệt giúp bảo vệ cơ thể khỏi lạnh giá, túi tiện lợi và chất liệu polyester bền bỉ.',
                ],

                '9' => [
                    'description'      => 'Giới thiệu Áo Khoác Puffer Nam OmniHeat có Mũ – phiên bản màu Xanh-Vàng (Size L), một lựa chọn hoàn hảo cho mùa đông lạnh giá. Với lớp cách nhiệt tổng hợp, chiếc áo khoác này giúp giữ nhiệt tối đa, bảo vệ bạn khỏi những cơn gió lạnh. Thiết kế có mũ giúp tăng cường khả năng giữ ấm, mang lại sự thoải mái tuyệt đối. Tay áo dài che chắn toàn bộ cánh tay, mang lại cảm giác ấm áp từ vai đến cổ tay. Túi bên được tích hợp giúp bạn dễ dàng mang theo các vật dụng cá nhân hoặc giữ ấm đôi tay. Được làm từ chất liệu polyester cao cấp, áo không chỉ bền bỉ mà còn có khả năng chống thấm nhẹ, phù hợp cho các hoạt động ngoài trời. Phiên bản màu Xanh-Vàng mang đến phong cách trẻ trung và năng động, giúp bạn nổi bật trong những ngày đông lạnh.',
                    'meta-description' => 'Áo khoác Puffer nam OmniHeat màu Xanh-Vàng (Size L) – giữ ấm tối đa, phong cách hiện đại.',
                    'meta-keywords'    => 'áo khoác puffer, áo khoác nam mùa đông, áo khoác có mũ, thời trang nam, xanh vàng',
                    'meta-title'       => 'Áo Khoác Puffer Nam OmniHeat có Mũ - Xanh Vàng (Size L)',
                    'name'             => 'Áo Khoác Puffer Nam OmniHeat có Mũ - Xanh Vàng (Size L)',
                    'sort-description' => 'Giữ ấm và phong cách với Áo Khoác Puffer Nam OmniHeat có Mũ (Màu Xanh-Vàng, Size L). Thiết kế cách nhiệt giúp bảo vệ cơ thể khỏi lạnh giá, túi tiện lợi và chất liệu polyester bền bỉ.',
                ],

                '10' => [
                    'description'      => 'Giới thiệu Áo Khoác Puffer Nam OmniHeat có Mũ – phiên bản màu Xanh-Lục (Size M), mang đến sự kết hợp hoàn hảo giữa phong cách, độ bền và khả năng giữ ấm tối ưu. Với thiết kế có mũ, áo giúp bảo vệ đầu và cổ khỏi gió lạnh, mang lại sự thoải mái tuyệt đối. Tay áo dài đảm bảo che phủ toàn bộ cánh tay, giữ ấm từ vai đến cổ tay. Túi bên tiện lợi giúp bạn dễ dàng mang theo vật dụng cá nhân hoặc giữ ấm tay. Lớp cách nhiệt tổng hợp giữ nhiệt hiệu quả, bảo vệ bạn khỏi thời tiết khắc nghiệt. Chất liệu polyester cao cấp giúp áo bền bỉ, chống thấm nhẹ và phù hợp cho nhiều hoạt động ngoài trời. Phiên bản màu Xanh-Lục độc đáo mang đến sự trẻ trung và phong cách hiện đại.',
                    'meta-description' => 'Áo khoác Puffer nam OmniHeat màu Xanh-Lục (Size M) – giữ ấm tối đa, phong cách hiện đại.',
                    'meta-keywords'    => 'áo khoác puffer, áo khoác nam mùa đông, áo khoác có mũ, thời trang nam, xanh lục',
                    'meta-title'       => 'Áo Khoác Puffer Nam OmniHeat có Mũ - Xanh Lục (Size M)',
                    'name'             => 'Áo Khoác Puffer Nam OmniHeat có Mũ - Xanh Lục (Size M)',
                    'sort-description' => 'Giữ ấm và phong cách với Áo Khoác Puffer Nam OmniHeat có Mũ (Màu Xanh-Lục, Size M). Thiết kế cách nhiệt giúp bảo vệ cơ thể khỏi lạnh giá, túi tiện lợi và chất liệu polyester bền bỉ.',
                ],

                '11' => [
                    'description'      => 'Giới thiệu Áo Khoác Puffer Nam OmniHeat có Mũ – phiên bản màu Xanh-Lục (Size L), lựa chọn lý tưởng cho những ngày đông lạnh giá. Thiết kế có mũ giúp bảo vệ đầu và cổ khỏi gió lạnh, mang lại sự ấm áp tối đa. Với lớp cách nhiệt tổng hợp, chiếc áo khoác này giúp giữ nhiệt hiệu quả, đảm bảo bạn luôn thoải mái ngay cả trong thời tiết khắc nghiệt. Tay áo dài che chắn toàn bộ cánh tay, tạo sự bảo vệ hoàn hảo. Túi bên được tích hợp giúp bạn dễ dàng mang theo các vật dụng cá nhân hoặc giữ ấm đôi tay. Chất liệu polyester cao cấp giúp áo bền bỉ, chống thấm nhẹ và phù hợp cho nhiều hoạt động ngoài trời. Phiên bản màu Xanh-Lục nổi bật mang đến phong cách thời thượng và năng động.',
                    'meta-description' => 'Áo khoác Puffer nam OmniHeat màu Xanh-Lục (Size L) – giữ ấm tối đa, phong cách hiện đại.',
                    'meta-keywords'    => 'áo khoác puffer, áo khoác nam mùa đông, áo khoác có mũ, thời trang nam, xanh lục',
                    'meta-title'       => 'Áo Khoác Puffer Nam OmniHeat có Mũ - Xanh Lục (Size L)',
                    'name'             => 'Áo Khoác Puffer Nam OmniHeat có Mũ - Xanh Lục (Size L)',
                    'sort-description' => 'Giữ ấm và phong cách với Áo Khoác Puffer Nam OmniHeat có Mũ (Màu Xanh-Lục, Size L). Thiết kế cách nhiệt giúp bảo vệ cơ thể khỏi lạnh giá, túi tiện lợi và chất liệu polyester bền bỉ.',
                ],
            ],

            'product-bundle-option-translations' => [
                '1' => [
                    'label' => 'Tùy chọn Gói 1',
                ],

                '2' => [
                    'label' => 'Tùy chọn Gói 1',
                ],

                '3' => [
                    'label' => 'Tùy chọn Gói 2',
                ],

                '4' => [
                    'label' => 'Tùy chọn Gói 2',
                ],
            ],
        ],
    ],

    'installer' => [
        'index' => [
            'create-administrator' => [
                'admin'            => 'Quản trị viên',
                'bagisto'          => 'Bagisto',
                'confirm-password' => 'Xác nhận Mật khẩu',
                'email'            => 'Email',
                'email-address'    => 'admin@example.com',
                'password'         => 'Mật khẩu',
                'title'            => 'Tạo Quản trị viên',
            ],

            'environment-configuration' => [
                'algerian-dinar'              => 'Dinar Algeria (DZD)',
                'allowed-currencies'          => 'Các đơn vị tiền tệ được phép',
                'allowed-locales'             => 'Các ngôn ngữ được phép',
                'application-name'            => 'Tên ứng dụng',
                'argentine-peso'              => 'Peso Argentina (ARS)',
                'australian-dollar'           => 'Đô la Úc (AUD)',
                'bagisto'                     => 'Bagisto',
                'bangladeshi-taka'            => 'Taka Bangladesh (BDT)',
                'brazilian-real'              => 'Real Brazil (BRL)',
                'british-pound-sterling'      => 'Bảng Anh (GBP)',
                'canadian-dollar'             => 'Đô la Canada (CAD)',
                'cfa-franc-bceao'             => 'Franc CFA BCEAO (XOF)',
                'cfa-franc-beac'              => 'Franc CFA BEAC (XAF)',
                'chilean-peso'                => 'Peso Chile (CLP)',
                'chinese-yuan'                => 'Nhân dân tệ Trung Quốc (CNY)',
                'colombian-peso'              => 'Peso Colombia (COP)',
                'czech-koruna'                => 'Koruna Séc (CZK)',
                'danish-krone'                => 'Krone Đan Mạch (DKK)',
                'database-connection'         => 'Kết nối cơ sở dữ liệu',
                'database-hostname'           => 'Tên máy chủ cơ sở dữ liệu',
                'database-name'               => 'Tên cơ sở dữ liệu',
                'database-password'           => 'Mật khẩu cơ sở dữ liệu',
                'database-port'               => 'Cổng cơ sở dữ liệu',
                'database-prefix'             => 'Tiền tố cơ sở dữ liệu',
                'database-username'           => 'Tên người dùng cơ sở dữ liệu',
                'default-currency'            => 'Đơn vị tiền tệ mặc định',
                'default-locale'              => 'Ngôn ngữ mặc định',
                'default-timezone'            => 'Múi giờ mặc định',
                'default-url'                 => 'URL mặc định',
                'default-url-link'            => 'https://localhost',
                'egyptian-pound'              => 'Bảng Ai Cập (EGP)',
                'euro'                        => 'Euro (EUR)',
                'fijian-dollar'               => 'Đô la Fiji (FJD)',
                'hong-kong-dollar'            => 'Đô la Hồng Kông (HKD)',
                'hungarian-forint'            => 'Forint Hungary (HUF)',
                'indian-rupee'                => 'Rupee Ấn Độ (INR)',
                'indonesian-rupiah'           => 'Rupiah Indonesia (IDR)',
                'israeli-new-shekel'          => 'Shekel Israel (ILS)',
                'japanese-yen'                => 'Yên Nhật (JPY)',
                'jordanian-dinar'             => 'Dinar Jordan (JOD)',
                'kazakhstani-tenge'           => 'Tenge Kazakhstan (KZT)',
                'kuwaiti-dinar'               => 'Dinar Kuwait (KWD)',
                'lebanese-pound'              => 'Bảng Liban (LBP)',
                'libyan-dinar'                => 'Dinar Libya (LYD)',
                'malaysian-ringgit'           => 'Ringgit Malaysia (MYR)',
                'mauritian-rupee'             => 'Rupee Mauritius (MUR)',
                'mexican-peso'                => 'Peso Mexico (MXN)',
                'moroccan-dirham'             => 'Dirham Maroc (MAD)',
                'mysql'                       => 'Mysql',
                'nepalese-rupee'              => 'Rupee Nepal (NPR)',
                'new-taiwan-dollar'           => 'Đô la Đài Loan mới (TWD)',
                'new-zealand-dollar'          => 'Đô la New Zealand (NZD)',
                'nigerian-naira'              => 'Naira Nigeria (NGN)',
                'norwegian-krone'             => 'Krone Na Uy (NOK)',
                'omani-rial'                  => 'Rial Oman (OMR)',
                'pakistani-rupee'             => 'Rupee Pakistan (PKR)',
                'panamanian-balboa'           => 'Balboa Panama (PAB)',
                'paraguayan-guarani'          => 'Guarani Paraguay (PYG)',
                'peruvian-nuevo-sol'          => 'Nuevo Sol Peru (PEN)',
                'pgsql'                       => 'pgSQL',
                'philippine-peso'             => 'Peso Philippines (PHP)',
                'polish-zloty'                => 'Zloty Ba Lan (PLN)',
                'qatari-rial'                 => 'Rial Qatar (QAR)',
                'romanian-leu'                => 'Leu Romania (RON)',
                'russian-ruble'               => 'Rúp Nga (RUB)',
                'saudi-riyal'                 => 'Riyal Ả Rập Xê Út (SAR)',
                'select-timezone'             => 'Chọn múi giờ',
                'singapore-dollar'            => 'Đô la Singapore (SGD)',
                'south-african-rand'          => 'Rand Nam Phi (ZAR)',
                'south-korean-won'            => 'Won Hàn Quốc (KRW)',
                'sqlsrv'                      => 'SQLSRV',
                'sri-lankan-rupee'            => 'Rupee Sri Lanka (LKR)',
                'swedish-krona'               => 'Krona Thụy Điển (SEK)',
                'swiss-franc'                 => 'Franc Thụy Sĩ (CHF)',
                'thai-baht'                   => 'Baht Thái Lan (THB)',
                'title'                       => 'Cấu hình cửa hàng',
                'tunisian-dinar'              => 'Dinar Tunisia (TND)',
                'turkish-lira'                => 'Lira Thổ Nhĩ Kỳ (TRY)',
                'ukrainian-hryvnia'           => 'Hryvnia Ukraina (UAH)',
                'united-arab-emirates-dirham' => 'Dirham UAE (AED)',
                'united-states-dollar'        => 'Đô la Mỹ (USD)',
                'uzbekistani-som'             => 'Som Uzbekistan (UZS)',
                'venezuelan-bolívar'          => 'Bolívar Venezuela (VEF)',
                'vietnamese-dong'             => 'Đồng Việt Nam (VND)',
                'warning-message'             => 'Cảnh báo! Cài đặt ngôn ngữ hệ thống mặc định và tiền tệ mặc định là cố định và không thể thay đổi sau khi thiết lập.',
                'zambian-kwacha'              => 'Kwacha Zambia (ZMW)',
            ],

            'sample-products' => [
                'download-sample' => 'Tải xuống mẫu',
                'no'              => 'Không',
                'sample-products' => 'Sản phẩm mẫu',
                'title'           => 'Sản phẩm mẫu',
                'yes'             => 'Có',
            ],

            'installation-processing' => [
                'bagisto'      => 'Cài đặt Bagisto',
                'bagisto-info' => 'Đang tạo các bảng cơ sở dữ liệu, quá trình này có thể mất một vài phút',
                'title'        => 'Cài đặt',
            ],

            'installation-completed' => [
                'admin-panel'                => 'Bảng điều khiển quản trị',
                'bagisto-forums'             => 'Diễn đàn Bagisto',
                'customer-panel'             => 'Bảng điều khiển khách hàng',
                'explore-bagisto-extensions' => 'Khám phá tiện ích mở rộng Bagisto',
                'title'                      => 'Cài đặt hoàn tất',
                'title-info'                 => 'Bagisto đã được cài đặt thành công trên hệ thống của bạn.',
            ],

            'ready-for-installation' => [
                'create-databsae-table'   => 'Tạo bảng cơ sở dữ liệu',
                'install'                 => 'Cài đặt',
                'install-info'            => 'Bagisto đã sẵn sàng để cài đặt',
                'install-info-button'     => 'Nhấn vào nút bên dưới để',
                'populate-database-table' => 'Điền dữ liệu vào bảng cơ sở dữ liệu',
                'start-installation'      => 'Bắt đầu cài đặt',
                'title'                   => 'Sẵn sàng cài đặt',
            ],

            'start' => [
                'locale'        => 'Ngôn ngữ',
                'main'          => 'Bắt đầu',
                'select-locale' => 'Chọn ngôn ngữ',
                'title'         => 'Cài đặt Bagisto của bạn',
                'welcome-title' => 'Chào mừng đến với Bagisto',
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
                'php-version' => '8.1 hoặc cao hơn',
                'session'     => 'session',
                'title'       => 'Yêu cầu hệ thống',
                'tokenizer'   => 'tokenizer',
                'xml'         => 'XML',
            ],

            'arabic'                   => 'Tiếng Ả Rập',
            'back'                     => 'Trước',
            'bagisto'                  => 'Bagisto',
            'bagisto-info'             => 'một dự án cộng đồng của',
            'bagisto-logo'             => 'Logo Bagisto',
            'bengali'                  => 'Tiếng Bengal',
            'chinese'                  => 'Tiếng Trung',
            'continue'                 => 'Tiếp tục',
            'dutch'                    => 'Tiếng Hà Lan',
            'english'                  => 'Tiếng Anh',
            'french'                   => 'Tiếng Pháp',
            'german'                   => 'Tiếng Đức',
            'hebrew'                   => 'Tiếng Hebrew',
            'hindi'                    => 'Tiếng Hindi',
            'installation-description'  => 'Việc cài đặt Bagisto thường bao gồm một số bước. Dưới đây là tổng quan về quá trình cài đặt Bagisto',
            'installation-info'         => 'Chúng tôi rất vui khi gặp bạn ở đây!',
            'installation-title'        => 'Chào mừng đến với cài đặt',
            'italian'                  => 'Tiếng Ý',
            'japanese'                 => 'Tiếng Nhật',
            'persian'                  => 'Tiếng Ba Tư',
            'polish'                   => 'Tiếng Ba Lan',
            'portuguese'               => 'Tiếng Bồ Đào Nha (Brazil)',
            'russian'                  => 'Tiếng Nga',
            'sinhala'                  => 'Tiếng Sinhala',
            'spanish'                  => 'Tiếng Tây Ban Nha',
            'title'                    => 'Trình cài đặt Bagisto',
            'turkish'                  => 'Tiếng Thổ Nhĩ Kỳ',
            'ukrainian'                => 'Tiếng Ukraina',
            'vietnamese'               => 'Tiếng Việt',
            'webkul'                   => 'Webkul',
        ],
    ],
];
