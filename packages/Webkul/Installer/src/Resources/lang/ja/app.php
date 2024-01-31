<?php

return [
    'seeders'   => [
        'attribute' => [
            'attribute-families' => [
                'default' => 'デフォルト',
            ],

            'attribute-groups'   => [
                'description'       => '説明',
                'general'           => '一般',
                'inventories'       => '在庫',
                'meta-description'  => 'メタ説明',
                'price'             => '価格',
                'settings'          => '設定',
                'shipping'          => '配送',
            ],

            'attributes'         => [
                'brand'                => 'ブランド',
                'color'                => '色',
                'cost'                 => 'コスト',
                'description'          => '説明',
                'featured'             => '注目',
                'guest-checkout'       => 'ゲストチェックアウト',
                'height'               => '高さ',
                'length'               => '長さ',
                'manage-stock'         => '在庫管理',
                'meta-description'     => 'メタ説明',
                'meta-keywords'        => 'メタキーワード',
                'meta-title'           => 'メタタイトル',
                'name'                 => '名前',
                'new'                  => '新規',
                'price'                => '価格',
                'product-number'       => '製品番号',
                'short-description'    => '短い説明',
                'size'                 => 'サイズ',
                'sku'                  => 'SKU',
                'special-price-from'   => '特別価格 開始',
                'special-price-to'     => '特別価格 終了',
                'special-price'        => '特別価格',
                'status'               => 'ステータス',
                'tax-category'         => '税カテゴリー',
                'url-key'              => 'URLキー',
                'visible-individually' => '個別に表示',
                'weight'               => '重さ',
                'width'                => '幅',
            ],

            'attribute-options'  => [
                'black'  => '黒',
                'green'  => '緑',
                'l'      => 'L',
                'm'      => 'M',
                'red'    => '赤',
                's'      => 'S',
                'white'  => '白',
                'xl'     => 'XL',
                'yellow' => '黄色',
            ],
        ],

        'category'  => [
            'categories' => [
                'description' => 'ルートカテゴリの説明',
                'name'        => 'ルート',
            ],
        ],

        'cms'       => [
            'pages' => [
                'about-us'         => [
                    'content' => '会社概要ページのコンテンツ',
                    'title'   => '会社概要',
                ],

                'contact-us'       => [
                    'content' => 'お問い合わせページのコンテンツ',
                    'title'   => 'お問い合わせ',
                ],

                'customer-service' => [
                    'content' => 'カスタマーサービスページのコンテンツ',
                    'title'   => 'カスタマーサービス',
                ],

                'payment-policy'   => [
                    'content' => '支払いポリシー ページのコンテンツ',
                    'title'   => '支払いポリシー',
                ],

                'privacy-policy'   => [
                    'content' => 'プライバシーポリシー ページのコンテンツ',
                    'title'   => 'プライバシーポリシー',
                ],

                'refund-policy'    => [
                    'content' => '返品ポリシー ページのコンテンツ',
                    'title'   => '返品ポリシー',
                ],

                'return-policy'    => [
                    'content' => '返却ポリシー ページのコンテンツ',
                    'title'   => '返却ポリシー',
                ],

                'shipping-policy'  => [
                    'content' => '配送ポリシー ページのコンテンツ',
                    'title'   => '配送ポリシー',
                ],

                'terms-conditions' => [
                    'content' => '利用規約ページのコンテンツ',
                    'title'   => '利用規約',
                ],

                'terms-of-use'     => [
                    'content' => '利用規約ページのコンテンツ',
                    'title'   => '利用規約',
                ],

                'whats-new'        => [
                    'content' => '新着情報ページのコンテンツ',
                    'title'   => '新着情報',
                ],
            ],
        ],

        'core'      => [
            'channels'   => [
                'meta-description' => 'デモストアのメタ説明',
                'meta-keywords'    => 'デモストアのメタキーワード',
                'meta-title'       => 'デモストア',
                'name'             => 'デフォルト',
            ],

            'currencies' => [
                'AED' => 'ディルハム',
                'AFN' => 'イスラエルシェケル',
                'CNY' => '中国元',
                'EUR' => 'ユーロ',
                'GBP' => 'ポンドスターリング',
                'INR' => 'インドルピー',
                'IRR' => 'イランリアル',
                'JPY' => '日本円',
                'RUB' => 'ロシアルーブル',
                'SAR' => 'サウジリヤル',
                'TRY' => 'トルコリラ',
                'UAH' => 'ウクライナフリブニ',
                'USD' => '米ドル',
            ],

            'locales'    => [
                'ar'    => 'アラビア語',
                'bn'    => 'ベンガル語',
                'de'    => 'ドイツ語',
                'en'    => '英語',
                'es'    => 'スペイン語',
                'fa'    => 'ペルシャ語',
                'fr'    => 'フランス語',
                'he'    => 'ヘブライ語',
                'hi_IN' => 'ヒンディー語',
                'it'    => 'イタリア語',
                'ja'    => '日本語',
                'nl'    => 'オランダ語',
                'pl'    => 'ポーランド語',
                'pt_BR' => 'ブラジルポルトガル語',
                'ru'    => 'ロシア語',
                'sin'   => 'シンハラ語',
                'tr'    => 'トルコ語',
                'uk'    => 'ウクライナ語',
                'zh_CN' => '中国語',
            ],
        ],

        'customer'  => [
            'customer-groups' => [
                'general'   => '一般',
                'guest'     => 'ゲスト',
                'wholesale' => '卸売',
            ],
        ],

        'inventory' => [
            'inventory-sources' => [
                'name' => 'デフォルト',
            ],
        ],

        'shop'      => [
            'theme-customizations' => [
                'all-products'           => [
                    'name'    => 'すべての製品',

                    'options' => [
                        'title' => 'すべての製品',
                    ],
                ],

                'bold-collections'       => [
                    'content' => [
                        'btn-title'   => 'すべて表示',
                        'description' => '新しいボールドコレクションを紹介します！ 大胆なデザインと鮮やかなステートメントでスタイルを引き立てましょう。 ワードローブを再定義する印象的なパターンと大胆な色を探索します。 途方もないものを受け入れる準備をしましょう！',
                        'title'       => '新しいボールドコレクションに備えて',
                    ],

                    'name'    => 'ボールドコレクション',
                ],

                'categories-collections' => [
                    'name' => 'カテゴリーコレクション',
                ],

                'featured-collections'   => [
                    'name'    => '特集コレクション',

                    'options' => [
                        'title' => '注目製品',
                    ],
                ],

                'footer-links'           => [
                    'name'    => 'フッターリンク',

                    'options' => [
                        'about-us'         => '当社について',
                        'contact-us'       => 'お問い合わせ',
                        'customer-service' => 'カスタマーサービス',
                        'payment-policy'   => '支払いポリシー',
                        'privacy-policy'   => 'プライバシーポリシー',
                        'refund-policy'    => '返金ポリシー',
                        'return-policy'    => '返品ポリシー',
                        'shipping-policy'  => '配送ポリシー',
                        'terms-conditions' => '利用条件',
                        'terms-of-use'     => '利用規約',
                        'whats-new'        => '新着情報',
                    ],
                ],

                'game-container'         => [
                    'content' => [
                        'sub-title-1' => '私たちのコレクション',
                        'sub-title-2' => '私たちのコレクション',
                        'title'       => '新アイテムでゲームを楽しむ！',
                    ],

                    'name'    => 'ゲームコンテナ',
                ],

                'image-carousel'         => [
                    'name'    => 'イメージカルーセル',

                    'sliders' => [
                        'title' => '新コレクションに備えて',
                    ],
                ],

                'new-products'           => [
                    'name'    => '新製品',

                    'options' => [
                        'title' => '新製品',
                    ],
                ],

                'offer-information'      => [
                    'content' => [
                        'title' => '初回注文で最大40％OFF ショッピングを今すぐ開始',
                    ],

                    'name'   => '特売情報',
                ],

                'services-content'       => [
                    'name'  => 'サービスコンテンツ',

                    'title' => [
                        'free-shipping'   => '送料無料',
                        'product-replace' => '製品の交換',
                        'emi-available'   => 'EMI利用可能',
                        'time-support'    => '24/7サポート',
                    ],

                    'description' => [
                        'free-shipping-info'   => 'すべての注文で送料無料をお楽しみください',
                        'product-replace-info' => '簡単な製品交換が可能です！',
                        'emi-available-info'   => 'すべての主要クレジットカードで費用のかからないEMIが利用可能です',
                        'time-support-info'    => 'チャットやメールでの専用24/7サポート',
                    ],
                ],

                'top-collections'        => [
                    'content' => [
                        'sub-title-1' => '私たちのコレクション',
                        'sub-title-2' => '私たちのコレクション',
                        'sub-title-3' => '私たちのコレクション',
                        'sub-title-4' => '私たちのコレクション',
                        'sub-title-5' => '私たちのコレクション',
                        'sub-title-6' => '私たちのコレクション',
                        'title'       => '新アイテムでゲームを楽しむ！',
                    ],

                    'name'    => 'トップコレクション',
                ],
            ],
        ],

        'user'      => [
            'roles' => [
                'description' => 'このロールのユーザーにはすべてのアクセス権があります',
                'name'        => '管理者',
            ],

            'users' => [
                'name' => '例',
            ],
        ],
    ],

    'installer' => [
        'index' => [
            'create-administrator'      => [
                'admin'            => '管理者',
                'bagisto'          => 'Bagisto',
                'confirm-password' => 'パスワードの確認',
                'email-address'    => 'admin@example.com',
                'email'            => 'メール',
                'password'         => 'パスワード',
                'title'            => '管理者の作成',
            ],

            'environment-configuration' => [
                'allowed-currencies'  => '許可された通貨',
                'allowed-locales'     => '許可されたロケール',
                'application-name'    => 'アプリケーション名',
                'bagisto'             => 'Bagisto',
                'chinese-yuan'        => '中国元（CNY）',
                'database-connection' => 'データベース接続',
                'database-hostname'   => 'データベースホスト名',
                'database-name'       => 'データベース名',
                'database-password'   => 'データベースパスワード',
                'database-port'       => 'データベースポート',
                'database-prefix'     => 'データベースプレフィックス',
                'database-username'   => 'データベースユーザー名',
                'default-currency'    => 'デフォルト通貨',
                'default-locale'      => 'デフォルトロケール',
                'default-timezone'    => 'デフォルトタイムゾーン',
                'default-url-link'    => 'https://localhost',
                'default-url'         => 'デフォルトURL',
                'dirham'              => 'ディルハム（AED）',
                'euro'                => 'ユーロ（EUR）',
                'iranian'             => 'イランリアル（IRR）',
                'israeli'             => 'イスラエルシェケル（AFN）',
                'japanese-yen'        => '日本円（JPY）',
                'mysql'               => 'Mysql',
                'pgsql'               => 'pgSQL',
                'pound'               => 'ポンドスターリング（GBP）',
                'rupee'               => 'インドルピー（INR）',
                'russian-ruble'       => 'ロシアルーブル（RUB）',
                'saudi'               => 'サウジリヤル（SAR）',
                'select-timezone'     => 'タイムゾーンの選択',
                'sqlsrv'              => 'SQLSRV',
                'title'               => '環境設定',
                'turkish-lira'        => 'トルコリラ（TRY）',
                'ukrainian-hryvnia'   => 'ウクライナフリブナ（UAH）',
                'usd'                 => '米ドル（USD）',
                'warning-message'     => '警告！デフォルトのシステム言語設定およびデフォルト通貨設定は恒久的で、二度と変更できません。',
            ],

            'installation-processing'   => [
                'bagisto-info'     => 'データベーステーブルの作成中、これには数分かかることがあります',
                'bagisto'          => 'Bagistoのインストール',
                'title'            => 'インストール',
            ],

            'installation-completed'    => [
                'admin-panel'                => '管理パネル',
                'bagisto-forums'             => 'Bagistoフォーラム',
                'customer-panel'             => '顧客パネル',
                'explore-bagisto-extensions' => 'Bagisto拡張機能の探索',
                'title-info'                 => 'Bagistoがシステムに正常にインストールされました。',
                'title'                      => 'インストールが完了しました',
            ],

            'ready-for-installation'    => [
                'create-databsae-table'   => 'データベーステーブルを作成する',
                'install-info-button'     => '以下のボタンをクリックしてください',
                'install-info'            => 'インストール用のBagisto',
                'install'                 => 'インストール',
                'populate-database-table' => 'データベーステーブルを作成する',
                'start-installation'      => 'インストールを開始',
                'title'                   => 'インストール準備完了',
            ],

            'start'                     => [
                'locale'        => 'ロケール',
                'main'          => '開始',
                'select-locale' => 'ロケールを選択',
                'title'         => 'Bagistoのインストール',
                'welcome-title' => 'Bagisto 2.0へようこそ。',
            ],

            'server-requirements'       => [
                'calendar'    => 'カレンダー',
                'ctype'       => 'cType',
                'curl'        => 'cURL',
                'dom'         => 'dom',
                'fileinfo'    => 'fileInfo',
                'filter'      => 'フィルター',
                'gd'          => 'GD',
                'hash'        => 'ハッシュ',
                'intl'        => 'intl',
                'json'        => 'JSON',
                'mbstring'    => 'mbstring',
                'openssl'     => 'OpenSSL',
                'pcre'        => 'pcre',
                'pdo'         => 'pdo',
                'php-version' => '8.1以上',
                'php'         => 'PHP',
                'session'     => 'セッション',
                'title'       => 'サーバーの要件',
                'tokenizer'   => 'トークン生成',
                'xml'         => 'XML',
            ],

            'arabic'                    => 'アラビア語',
            'back'                      => '戻る',
            'bagisto-info'              => 'コミュニティプロジェクト by',
            'bagisto-logo'              => 'Bagistoロゴ',
            'bagisto'                   => 'Bagisto',
            'bengali'                   => 'ベンガル語',
            'chinese'                   => '中国語',
            'continue'                  => '続行',
            'dutch'                     => 'オランダ語',
            'english'                   => '英語',
            'french'                    => 'フランス語',
            'german'                    => 'ドイツ語',
            'hebrew'                    => 'ヘブライ語',
            'hindi'                     => 'ヒンディー語',
            'installation-description'  => '通常、Bagistoのインストールにはいくつかのステップが含まれます。ここにBagistoのインストールプロセスの概要を示します:',
            'installation-info'         => 'ここにいてくれてうれしいです！',
            'installation-title'        => 'Bagistoインストールへようこそ',
            'italian'                   => 'イタリア語',
            'japanese'                  => '日本語',
            'persian'                   => 'ペルシャ語',
            'polish'                    => 'ポーランド語',
            'portuguese'                => 'ブラジルポルトガル語',
            'russian'                   => 'ロシア語',
            'save-configuration'        => '設定を保存',
            'sinhala'                   => 'シンハラ語',
            'skip'                      => 'スキップ',
            'spanish'                   => 'スペイン語',
            'title'                     => 'Bagistoインストーラ',
            'turkish'                   => 'トルコ語',
            'ukrainian'                 => 'ウクライナ語',
            'webkul'                    => 'Webkul',
        ],
    ],
];
