<?php

return [
    'seeders'   => [
        'attribute' => [
            'attribute-families' => [
                'default' => 'Mặc định',
            ],

            'attribute-groups'   => [
                'description'       => 'Mô tả',
                'general'           => 'Tổng quan',
                'inventories'       => 'Tồn kho',
                'meta-description'  => 'Mô tả Meta',
                'price'             => 'Giá',
                'settings'          => 'Cài đặt',
                'shipping'          => 'Vận chuyển',
            ],

            'attributes'         => [
                'brand'                => 'Thương hiệu',
                'color'                => 'Màu sắc',
                'cost'                 => 'Giá thành',
                'description'          => 'Mô tả',
                'featured'             => 'Nổi bật',
                'guest-checkout'       => 'Thanh toán khách',
                'height'               => 'Chiều cao',
                'length'               => 'Chiều dài',
                'manage-stock'         => 'Quản lý tồn kho',
                'meta-description'     => 'Mô tả Meta',
                'meta-keywords'        => 'Từ khóa Meta',
                'meta-title'           => 'Tiêu đề Meta',
                'name'                 => 'Tên',
                'new'                  => 'Mới',
                'price'                => 'Giá',
                'product-number'       => 'Số sản phẩm',
                'short-description'    => 'Mô tả ngắn',
                'size'                 => 'Kích thước',
                'sku'                  => 'SKU',
                'special-price-from'   => 'Giá đặc biệt từ',
                'special-price-to'     => 'Giá đặc biệt đến',
                'special-price'        => 'Giá đặc biệt',
                'status'               => 'Trạng thái',
                'tax-category'         => 'Danh mục thuế',
                'url-key'              => 'Khóa URL',
                'visible-individually' => 'Hiển thị riêng lẻ',
                'weight'               => 'Trọng lượng',
                'width'                => 'Chiều rộng',
            ],

            'attribute-options'  => [
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

        'category'  => [
            'categories' => [
                'description' => 'Mô tả Danh mục Gốc',
                'name'        => 'Gốc',
            ],
        ],

        'cms'       => [
            'pages' => [
                'about-us'         => [
                    'content' => 'Nội dung Trang Về chúng tôi',
                    'title'   => 'Về chúng tôi',
                ],

                'contact-us'       => [
                    'content' => 'Nội dung Trang Liên hệ',
                    'title'   => 'Liên hệ',
                ],

                'customer-service' => [
                    'content' => 'Nội dung Trang Dịch vụ Khách hàng',
                    'title'   => 'Dịch vụ Khách hàng',
                ],

                'payment-policy'   => [
                    'content' => 'Nội dung Trang Chính sách thanh toán',
                    'title'   => 'Chính sách thanh toán',
                ],

                'privacy-policy'   => [
                    'content' => 'Nội dung Trang Chính sách bảo mật',
                    'title'   => 'Chính sách bảo mật',
                ],

                'refund-policy'    => [
                    'content' => 'Nội dung Trang Chính sách hoàn trả',
                    'title'   => 'Chính sách hoàn trả',
                ],

                'return-policy'    => [
                    'content' => 'Nội dung Trang Chính sách đổi trả',
                    'title'   => 'Chính sách đổi trả',
                ],

                'shipping-policy'  => [
                    'content' => 'Nội dung Trang Chính sách vận chuyển',
                    'title'   => 'Chính sách vận chuyển',
                ],

                'terms-conditions' => [
                    'content' => 'Nội dung Trang Điều khoản & Điều kiện',
                    'title'   => 'Điều khoản & Điều kiện',
                ],

                'terms-of-use'     => [
                    'content' => 'Nội dung Trang Điều khoản sử dụng',
                    'title'   => 'Điều khoản sử dụng',
                ],

                'whats-new'        => [
                    'content' => 'Nội dung Trang Có gì mới',
                    'title'   => 'Có gì mới',
                ],
            ],
        ],

        'core'      => [
            'channels'   => [
                'meta-title'       => 'Cửa hàng minh họa',
                'meta-keywords'    => 'Từ khóa meta của cửa hàng minh họa',
                'meta-description' => 'Mô tả meta của cửa hàng minh họa',
                'name'             => 'Mặc định',
            ],

            'currencies' => [
                'AED' => 'Đirham',
                'AFN' => 'Đồng tiền Israel',
                'CNY' => 'Nhân dân tệ',
                'EUR' => 'Euro',
                'GBP' => 'Bảng Anh',
                'INR' => 'Rupee Ấn Độ',
                'IRR' => 'Rial Iran',
                'JPY' => 'Yên Nhật',
                'RUB' => 'Rúp Nga',
                'SAR' => 'Riyal Ả Rập Saudi',
                'TRY' => 'Lira Thổ Nhĩ Kỳ',
                'UAH' => 'Hryvnia Ukraina',
                'VND' => 'Việt Nam Đồng',
                'USD' => 'Đô la Mỹ',
            ],

            'locales'    => [
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
                'zh_CN' => 'Tiếng Trung Quốc (giản thể)',
            ],
        ],

        'customer'  => [
            'customer-groups' => [
                'general'   => 'Chung',
                'guest'     => 'Khách',
                'wholesale' => 'Bán buôn',
            ],
        ],

        'inventory' => [
            'inventory-sources' => [
                'name' => 'Mặc định',
            ],
        ],

        'shop'      => [
            'theme-customizations' => [
                'all-products'           => [
                    'name'    => 'Tất cả Sản phẩm',

                    'options' => [
                        'title' => 'Tất cả Sản phẩm',
                    ],
                ],

                'bold-collections'       => [
                    'content' => [
                        'btn-title'   => 'Xem Tất cả',
                        'description' => 'Giới thiệu Bộ Sưu Tập Mới của chúng tôi! Nâng cao phong cách của bạn với các thiết kế táo bạo và tuyên bố sôi động. Khám phá các mẫu sắc nét và màu sắc táo bạo làm mới lại tủ quần áo của bạn. Hãy sẵn sàng để chào đón điều phi thường!',
                        'title'       => 'Hãy Sẵn Sàng cho các Bộ Sưu Tập Mới của chúng tôi!',
                    ],

                    'name'    => 'Bộ Sưu Tập Táo Bạo',
                ],

                'categories-collections' => [
                    'name' => 'Bộ Sưu Tập Danh mục',
                ],

                'featured-collections'   => [
                    'name'    => 'Bộ Sưu Tập Nổi bật',

                    'options' => [
                        'title' => 'Sản phẩm Nổi bật',
                    ],
                ],

                'footer-links'           => [
                    'name'    => 'Liên kết Footer',

                    'options' => [
                        'about-us'         => 'Về chúng tôi',
                        'contact-us'       => 'Liên hệ',
                        'customer-service' => 'Dịch vụ khách hàng',
                        'payment-policy'   => 'Chính sách thanh toán',
                        'privacy-policy'   => 'Chính sách bảo mật',
                        'refund-policy'    => 'Chính sách hoàn trả',
                        'return-policy'    => 'Chính sách đổi trả',
                        'shipping-policy'  => 'Chính sách vận chuyển',
                        'terms-conditions' => 'Điều khoản & Điều kiện',
                        'terms-of-use'     => 'Điều khoản sử dụng',
                        'whats-new'        => 'Có gì mới',
                    ],
                ],

                'game-container'         => [
                    'content' => [
                        'sub-title-1' => 'Các Bộ Sưu Tập của chúng tôi',
                        'sub-title-2' => 'Các Bộ Sưu Tập của chúng tôi',
                        'title'       => 'Trò chơi với các sản phẩm mới của chúng tôi!',
                    ],

                    'name'    => 'Bộ Sưu Tập Trò Chơi',
                ],

                'image-carousel'         => [
                    'name'    => 'Hình Ảnh Carousel',

                    'sliders' => [
                        'title' => 'Hãy Sẵn Sàng cho Bộ Sưu Tập Mới',
                    ],
                ],

                'new-products'           => [
                    'name'    => 'Sản phẩm Mới',

                    'options' => [
                        'title' => 'Sản phẩm Mới',
                    ],
                ],


                'offer-information'      => [
                    'content' => [
                        'title' => 'Nhận GIẢM ĐẾN 40% CHO ĐƠN HÀNG ĐẦU TIÊN CỦA BẠN MUA NGAY',
                    ],

                    'name' => 'Thông Tin Khuyến Mãi',
                ],

                'services-content'       => [
                    'description' => [
                        'emi-available-info'   => 'Không chi phí EMI có sẵn trên tất cả các thẻ tín dụng lớn',
                        'free-shipping-info'   => 'Miễn phí vận chuyển cho tất cả các đơn hàng',
                        'product-replace-info' => 'Dễ dàng Thay thế Sản phẩm có sẵn!',
                        'time-support-info'    => 'Hỗ trợ 24/7 dành riêng qua trò chuyện và email',
                    ],

                    'name'        => 'Nội dung Dịch vụ',

                    'title'       => [
                        'emi-available'   => 'EMI Có sẵn',
                        'free-shipping'   => 'Vận chuyển Miễn phí',
                        'product-replace' => 'Thay Thế Sản phẩm',
                        'time-support'    => 'Hỗ trợ 24/7',
                    ],
                ],

                'top-collections'        => [
                    'content' => [
                        'sub-title-1' => 'Các Bộ Sưu Tập của chúng tôi',
                        'sub-title-2' => 'Các Bộ Sưu Tập của chúng tôi',
                        'sub-title-3' => 'Các Bộ Sưu Tập của chúng tôi',
                        'sub-title-4' => 'Các Bộ Sưu Tập của chúng tôi',
                        'sub-title-5' => 'Các Bộ Sưu Tập của chúng tôi',
                        'sub-title-6' => 'Các Bộ Sưu Tập của chúng tôi',
                        'title'       => 'Trò chơi với các sản phẩm mới của chúng tôi!',
                    ],

                    'name'    => 'Các Bộ Sưu Tập hàng đầu',
                ],

            ],
        ],

        'user'      => [
            'roles' => [
                'description' => 'Người dùng với vai trò này sẽ có quyền truy cập tất cả các tính năng',
                'name'        => 'Quản trị viên',
            ],

            'users' => [
                'name' => 'Ví dụ',
            ],

        ],
    ],

    'installer' => [
        'index' => [
            'create-administrator'      => [
                'admin'            => 'Quản trị viên',
                'bagisto'          => 'Bagisto',
                'confirm-password' => 'Xác nhận Mật khẩu',
                'email-address'    => 'admin@example.com',
                'email'            => 'Email',
                'password'         => 'Mật khẩu',
                'title'            => 'Tạo Quản trị viên',
            ],

            'environment-configuration' => [
                'allowed-currencies'  => 'Tiền tệ được phép',
                'allowed-locales'     => 'Ngôn ngữ được phép',
                'application-name'    => 'Tên Ứng dụng',
                'bagisto'             => 'Bagisto',
                'chinese-yuan'        => 'Đô la Trung Quốc (CNY)',
                'database-connection' => 'Kết nối Cơ sở dữ liệu',
                'database-hostname'   => 'Tên máy chủ Cơ sở dữ liệu',
                'database-name'       => 'Tên Cơ sở dữ liệu',
                'database-password'   => 'Mật khẩu Cơ sở dữ liệu',
                'database-port'       => 'Cổng Cơ sở dữ liệu',
                'database-prefix'     => 'Tiền tố Cơ sở dữ liệu',
                'database-username'   => 'Tên người dùng Cơ sở dữ liệu',
                'default-currency'    => 'Tiền tệ mặc định',
                'default-locale'      => 'Ngôn ngữ mặc định',
                'default-timezone'    => 'Múi giờ mặc định',
                'default-url-link'    => 'https://localhost',
                'default-url'         => 'URL mặc định',
                'dirham'              => 'Dirham (AED)',
                'euro'                => 'Euro (EUR)',
                'iranian'             => 'Rial Iran (IRR)',
                'israeli'             => 'Đồng tiền Israel (AFN)',
                'japanese-yen'        => 'Yên Nhật (JPY)',
                'mysql'               => 'MySQL',
                'pgsql'               => 'PgSQL',
                'pound'               => 'Bảng Anh (GBP)',
                'rupee'               => 'Rupee Ấn Độ (INR)',
                'russian-ruble'       => 'Rúp Nga (RUB)',
                'saudi'               => 'Riyal Ả Rập Saudi (SAR)',
                'select-timezone'     => 'Chọn Múi giờ',
                'sqlsrv'              => 'SQLSRV',
                'title'               => 'Cấu hình Cửa hàng',
                'turkish-lira'        => 'Lira Thổ Nhĩ Kỳ (TRY)',
                'ukrainian-hryvnia'   => 'Hryvnia Ukraina (UAH)',
                'usd'                 => 'Đô la Mỹ (USD)',
                'vietnamese-dong'     => 'Việt Nam Đồng (VND)',
                'warning-message'     => 'Cảnh báo! Các cài đặt cho ngôn ngữ hệ thống mặc định cũng như tiền tệ mặc định là cố định và không thể thay đổi bao giờ nữa.',
            ],

            'installation-processing'   => [
                'bagisto'          => 'Cài đặt Bagisto',
                'bagisto-info'     => 'Đang tạo các bảng cơ sở dữ liệu, điều này có thể mất vài phút',
                'title'            => 'Cài đặt',
            ],

            'installation-completed'    => [
                'admin-panel'                => 'Bảng Quản trị',
                'bagisto-forums'             => 'Diễn đàn Bagisto',
                'customer-panel'             => 'Bảng điều khiển Khách hàng',
                'explore-bagisto-extensions' => 'Khám phá Các phần mở rộng Bagisto',
                'title-info'                 => 'Bagisto đã được cài đặt thành công trên hệ thống của bạn.',
                'title'                      => 'Cài đặt Hoàn tất',
            ],

            'ready-for-installation'    => [
                'create-databsae-table'   => 'Tạo bảng cơ sở dữ liệu',
                'install-info-button'     => 'Nhấn nút bên dưới để',
                'install-info'            => 'Cài đặt Bagisto',
                'install'                 => 'Cài đặt',
                'populate-database-table' => 'Điền vào bảng cơ sở dữ liệu',
                'start-installation'      => 'Bắt đầu cài đặt',
                'title'                   => 'Sẵn sàng cho việc cài đặt',
            ],

            'start'                     => [
                'locale'        => 'Ngôn ngữ',
                'main'          => 'Bắt đầu',
                'select-locale' => 'Chọn Ngôn ngữ',
                'title'         => 'Cài đặt Bagisto của bạn',
                'welcome-title' => 'Chào mừng bạn đến với Bagisto 2.0.',
            ],

            'server-requirements'       => [
                'calendar'    => 'Lịch',
                'ctype'       => 'cType',
                'curl'        => 'cURL',
                'dom'         => 'dom',
                'fileinfo'    => 'fileInfo',
                'filter'      => 'Bộ lọc',
                'gd'          => 'GD',
                'hash'        => 'Hash',
                'intl'        => 'intl',
                'json'        => 'JSON',
                'mbstring'    => 'mbstring',
                'openssl'     => 'openssl',
                'pcre'        => 'pcre',
                'pdo'         => 'pdo',
                'php-version' => '8.1 hoặc cao hơn',
                'php'         => 'PHP',
                'session'     => 'session',
                'title'       => 'Yêu cầu Hệ thống',
                'tokenizer'   => 'Tokenizer',
                'xml'         => 'XML',
            ],

            'arabic'                    => 'Tiếng Ả Rập',
            'back'                      => 'Quay lại',
            'bagisto-info'              => 'Dự án Cộng đồng của',
            'bagisto-logo'              => 'Logo Bagisto',
            'bagisto'                   => 'Bagisto',
            'bengali'                   => 'Tiếng Bengali',
            'chinese'                   => 'Tiếng Trung',
            'continue'                  => 'Tiếp tục',
            'dutch'                     => 'Tiếng Hà Lan',
            'english'                   => 'Tiếng Anh',
            'french'                    => 'Tiếng Pháp',
            'german'                    => 'Tiếng Đức',
            'hebrew'                    => 'Tiếng Hebrew',
            'hindi'                     => 'Tiếng Hindi',
            'installation-description'  => 'Cài đặt Bagisto thường bao gồm một số bước. Dưới đây là một phác thảo chung về quy trình cài đặt Bagisto:',
            'installation-info'         => 'Chúng tôi rất vui khi thấy bạn ở đây!',
            'installation-title'        => 'Chào mừng bạn đến với Cài đặt',
            'italian'                   => 'Tiếng Ý',
            'japanese'                  => 'Tiếng Nhật',
            'persian'                   => 'Tiếng Ba Tư',
            'polish'                    => 'Tiếng Ba Lan',
            'portuguese'                => 'Tiếng Bồ Đào Nha (Brazil)',
            'russian'                   => 'Tiếng Nga',
            'save-configuration'        => 'Lưu cấu hình',
            'sinhala'                   => 'Tiếng Sinhala',
            'skip'                      => 'Bỏ qua',
            'spanish'                   => 'Tiếng Tây Ban Nha',
            'title'                     => 'Trình cài đặt Bagisto',
            'turkish'                   => 'Tiếng Thổ Nhĩ Kỳ',
            'ukrainian'                 => 'Tiếng Ukraina',
            'webkul'                    => 'Webkul',
            'vietnamese'                => 'Tiếng Việt',
        ],
    ],
];
