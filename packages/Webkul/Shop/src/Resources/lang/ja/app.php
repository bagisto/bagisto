<?php

return [
    'customers' => [
        'forgot-password' => [
            'already-sent'         => 'すでにパスワードリセットメールが送信されました。',
            'back'                 => 'サインインに戻る？',
            'bagisto'              => 'バギスト',
            'email'                => 'メール',
            'email-not-exist'      => 'そのメールアドレスを持つユーザーが見つかりません。',
            'footer'               => '© 2010 - :current_year、Webkul Software（インドで登録）。全著作権所有。',
            'forgot-password-text' => 'パスワードを忘れた場合は、メールアドレスを入力して回復してください。',
            'page-title'           => 'パスワードを忘れましたか？',
            'reset-link-sent'      => 'パスワードリセットリンクをメールで送信しました。',
            'sign-in-button'       => 'サインイン',
            'submit'               => 'パスワードをリセット',
            'title'                => 'パスワードの回復',
        ],

        'reset-password' => [
            'back-link-title'  => 'サインインに戻る',
            'bagisto'          => 'バギスト',
            'confirm-password' => 'パスワードの確認',
            'email'            => '登録済みのメール',
            'footer'           => '© 2010 - :current_year、Webkul Software（インドで登録）。全著作権所有。',
            'password'         => 'パスワード',
            'submit-btn-title' => 'パスワードをリセット',
            'title'            => 'パスワードをリセット',
        ],

        'login-form' => [
            'bagisto'             => 'バギスト',
            'button-title'        => 'サインイン',
            'create-your-account' => 'アカウントを作成',
            'email'               => 'メール',
            'footer'              => '© 2010 - :current_year、Webkul Software（インドで登録）。全著作権所有。',
            'forgot-pass'         => 'パスワードをお忘れですか？',
            'form-login-text'     => 'アカウントをお持ちの場合、メールアドレスでサインインしてください。',
            'invalid-credentials' => '資格情報を確認してもう一度お試しください。',
            'new-customer'        => '新しい顧客？',
            'not-activated'       => 'アクティベーションは管理者の承認が必要です',
            'page-title'          => '顧客ログイン',
            'password'            => 'パスワード',
            'show-password'       => 'パスワードを表示',
            'title'               => 'サインイン',
            'verify-first'        => 'まずメールアカウントを確認してください。',
        ],

        'signup-form' => [
            'account-exists'              => 'すでにアカウントをお持ちですか？',
            'bagisto'                     => 'バギスト',
            'button-title'                => '登録',
            'confirm-pass'                => 'パスワードの確認',
            'email'                       => 'メール',
            'first-name'                  => '名',
            'footer'                      => '© 2010 - :current_year、Webkul Software（インドで登録）。全著作権所有。',
            'form-signup-text'            => '当ストアが初めての場合、メンバーとしてお迎えできることを嬉しく思います。',
            'last-name'                   => '姓',
            'page-title'                  => 'ユーザーになる',
            'password'                    => 'パスワード',
            'sign-in-button'              => 'サインイン',
            'subscribe-to-newsletter'     => 'ニュースレターに登録',
            'success'                     => 'アカウントは正常に作成されました。',
            'success-verify'              => 'アカウントが正常に作成されました。確認のためのメールが送信されました。',
            'success-verify-email-unsent' => 'アカウントは正常に作成されましたが、確認メールは送信されませんでした。',
            'verification-not-sent'       => 'エラー！確認メールの送信に問題があります。後でもう一度お試しください。',
            'verification-sent'           => '確認メールが送信されました',
            'verified'                    => 'アカウントは確認されました。今すぐログインしてみてください。',
            'verify-failed'               => 'メールアカウントを確認できません。',
        ],

        'account' => [
            'home' => 'ホーム',

            'profile' => [
                'index' => [
                    'delete'         => '削除',
                    'delete-failed'  => '顧客の削除中にエラーが発生しました。',
                    'delete-profile' => 'プロフィールを削除',
                    'delete-success' => '顧客が正常に削除されました',
                    'dob'            => '生年月日',
                    'edit'           => '編集',
                    'edit-success'   => 'プロフィールが正常に更新されました',
                    'email'          => 'メール',
                    'enter-password' => 'パスワードを入力してください',
                    'first-name'     => '名',
                    'gender'         => '性別',
                    'last-name'      => '姓',
                    'order-pending'  => '注文が保留中または処理中の状態であるため、顧客アカウントを削除することはできません。',
                    'title'          => 'プロフィール',
                    'unmatched'      => '古いパスワードが一致しません。',
                    'wrong-password' => 'パスワードが間違っています！',
                ],

                'edit' => [
                    'confirm-password'        => 'パスワードを確認',
                    'current-password'        => '現在のパスワード',
                    'dob'                     => '生年月日',
                    'edit'                    => '編集',
                    'edit-profile'            => 'プロフィールを編集',
                    'email'                   => 'メール',
                    'female'                  => '女性',
                    'first-name'              => '名',
                    'gender'                  => '性別',
                    'last-name'               => '姓',
                    'male'                    => '男性',
                    'new-password'            => '新しいパスワード',
                    'other'                   => 'その他',
                    'phone'                   => '電話',
                    'save'                    => '保存',
                    'subscribe-to-newsletter' => 'ニュースレターに登録する',
                ],
            ],

            'addresses' => [
                'index' => [
                    'add-address'      => '住所を追加',
                    'create-success'   => '住所が正常に追加されました。',
                    'default-address'  => 'デフォルトの住所',
                    'default-delete'   => 'デフォルトの住所は変更できません。',
                    'delete'           => '削除',
                    'delete-success'   => '住所が正常に削除されました',
                    'edit'             => '編集',
                    'edit-success'     => '住所が正常に更新されました。',
                    'empty-address'    => 'アカウントにまだ住所が追加されていません。',
                    'security-warning' => '不審なアクティビティが検出されました！',
                    'set-as-default'   => 'デフォルトに設定',
                    'title'            => '住所',
                    'update-success'   => '住所が正常に更新されました。',
                ],

                'create' => [
                    'add-address'    => '住所を追加',
                    'city'           => '市区町村',
                    'company-name'   => '会社名',
                    'country'        => '国',
                    'email'          => 'メール',
                    'first-name'     => '名',
                    'last-name'      => '姓',
                    'phone'          => '電話',
                    'post-code'      => '郵便番号',
                    'save'           => '保存',
                    'select-country' => '国を選択',
                    'set-as-default' => 'デフォルトに設定',
                    'state'          => '都道府県',
                    'street-address' => '住所',
                    'title'          => '住所',
                    'vat-id'         => 'VAT番号',
                ],

                'edit' => [
                    'city'           => '市区町村',
                    'company-name'   => '会社名',
                    'country'        => '国',
                    'edit'           => '編集',
                    'email'          => 'メール',
                    'first-name'     => '名',
                    'last-name'      => '姓',
                    'phone'          => '電話',
                    'post-code'      => '郵便番号',
                    'select-country' => '国を選択',
                    'state'          => '都道府県',
                    'street-address' => '住所',
                    'title'          => '住所',
                    'update-btn'     => '更新する',
                    'vat-id'         => 'VAT番号',
                ],
            ],

            'orders' => [
                'action'      => 'アクション',
                'action-view' => '表示',
                'empty-order' => 'まだ製品を注文していません',
                'order'       => '注文',
                'order-date'  => '注文日',
                'order-id'    => '注文ID',
                'subtotal'    => '小計',
                'title'       => '注文',
                'total'       => '合計',

                'status' => [
                    'title' => 'ステータス',

                    'options' => [
                        'canceled'        => 'キャンセル',
                        'closed'          => 'クローズド',
                        'completed'       => '完了',
                        'fraud'           => '詐欺',
                        'pending'         => '保留中',
                        'pending-payment' => '支払い保留',
                        'processing'      => '処理中',
                    ],
                ],

                'view' => [
                    'billing-address'      => '請求先住所',
                    'cancel-btn-title'     => 'キャンセル',
                    'cancel-confirm-msg'   => 'この注文をキャンセルしてもよろしいですか？',
                    'cancel-error'         => '注文をキャンセルできません。',
                    'cancel-success'       => '注文がキャンセルされました',
                    'contact'              => '連絡先',
                    'item-invoiced'        => '請求済みアイテム',
                    'item-refunded'        => '返金済みアイテム',
                    'item-shipped'         => '出荷済みアイテム',
                    'item-ordered'         => '注文済みアイテム',
                    'order-id'             => '注文ID',
                    'page-title'           => '注文 #:order_id',
                    'payment-method'       => '支払い方法',
                    'reorder-btn-title'    => '再注文',
                    'shipping-address'     => '配送先住所',
                    'shipping-method'      => '配送方法',
                    'shipping-and-payment' => '配送と支払いの詳細',
                    'status'               => 'ステータス',
                    'title'                => '表示',
                    'total'                => '合計',

                    'information' => [
                        'discount'                   => '割引',
                        'excl-tax'                   => '税抜き：',
                        'grand-total'                => '総合計',
                        'info'                       => '情報',
                        'item-canceled'              => 'キャンセル済み（:qty_canceled）',
                        'item-refunded'              => '返金済み（:qty_refunded）',
                        'invoiced-item'              => '請求済み（:qty_invoiced）',
                        'item-shipped'               => '出荷済み（:qty_shipped）',
                        'item-status'                => 'アイテムステータス',
                        'ordered-item'               => '注文済み（:qty_ordered）',
                        'placed-on'                  => '注文日',
                        'price'                      => '価格',
                        'product-name'               => '商品名',
                        'shipping-handling'          => '送料および取扱料',
                        'shipping-handling-excl-tax' => '送料および取扱料（税抜き）',
                        'shipping-handling-incl-tax' => '送料および取扱料（税込み）',
                        'sku'                        => 'SKU',
                        'subtotal'                   => '小計',
                        'subtotal-excl-tax'          => '小計（税抜き）',
                        'subtotal-incl-tax'          => '小計（税込み）',
                        'order-summary'              => '注文概要',
                        'tax'                        => '税金',
                        'tax-amount'                 => '税額',
                        'tax-percent'                => '税率',
                        'total-due'                  => '合計請求額',
                        'total-paid'                 => '支払済み総額',
                        'total-refunded'             => '返金総額',
                    ],

                    'invoices' => [
                        'discount'                   => '割引',
                        'excl-tax'                   => '税抜き：',
                        'grand-total'                => '総合計',
                        'individual-invoice'         => '請求書 #:invoice_id',
                        'invoices'                   => '請求書',
                        'price'                      => '価格',
                        'print'                      => '印刷',
                        'product-name'               => '商品名',
                        'products-ordered'           => '注文商品',
                        'qty'                        => '数量',
                        'shipping-handling-excl-tax' => '送料および取扱料（税抜き）',
                        'shipping-handling-incl-tax' => '送料および取扱料（税込み）',
                        'shipping-handling'          => '送料および取扱料',
                        'sku'                        => 'SKU',
                        'subtotal-excl-tax'          => '小計（税抜き）',
                        'subtotal-incl-tax'          => '小計（税込み）',
                        'subtotal'                   => '小計',
                        'tax'                        => '税金',
                        'tax-amount'                 => '税額',
                    ],

                    'shipments' => [
                        'individual-shipment' => '出荷 #:shipment_id',
                        'product-name'        => '商品名',
                        'qty'                 => '数量',
                        'shipments'           => '出荷',
                        'sku'                 => 'SKU',
                        'subtotal'            => '小計',
                        'tracking-number'     => '追跡番号',
                    ],

                    'refunds' => [
                        'adjustment-fee'             => '調整手数料',
                        'adjustment-refund'          => '調整返金',
                        'discount'                   => '割引',
                        'grand-total'                => '総合計',
                        'individual-refund'          => '返金 #:refund_id',
                        'no-result-found'            => 'レコードが見つかりませんでした。',
                        'order-summary'              => '注文概要',
                        'price'                      => '価格',
                        'product-name'               => '商品名',
                        'qty'                        => '数量',
                        'refunds'                    => '返金',
                        'shipping-handling'          => '送料および取扱料',
                        'shipping-handling-excl-tax' => '送料および取扱料（税抜き）',
                        'shipping-handling-incl-tax' => '送料および取扱料（税込み）',
                        'sku'                        => 'SKU',
                        'subtotal'                   => '小計',
                        'subtotal-excl-tax'          => '小計（税抜き）',
                        'subtotal-incl-tax'          => '小計（税込み）',
                        'tax'                        => '税金',
                        'tax-amount'                 => '税額',
                    ],
                ],

                'invoice-pdf' => [
                    'bank-details'               => '銀行詳細',
                    'bill-to'                    => '請求先',
                    'contact-number'             => '連絡先番号',
                    'contact'                    => '連絡先',
                    'date'                       => '請求日',
                    'discount'                   => '割引',
                    'excl-tax'                   => '税抜き:',
                    'grand-total'                => '総合計',
                    'invoice-id'                 => '請求書ID',
                    'invoice'                    => '請求書',
                    'order-date'                 => '注文日',
                    'order-id'                   => '注文ID',
                    'payment-method'             => '支払方法',
                    'payment-terms'              => '支払条件',
                    'price'                      => '価格',
                    'product-name'               => '商品名',
                    'qty'                        => '数量',
                    'ship-to'                    => '配送先',
                    'shipping-handling-excl-tax' => '送料・手数料（税抜き）',
                    'shipping-handling-incl-tax' => '送料・手数料（税込み）',
                    'shipping-handling'          => '送料・手数料',
                    'shipping-method'            => '配送方法',
                    'sku'                        => 'SKU',
                    'subtotal-excl-tax'          => '小計（税抜き）',
                    'subtotal-incl-tax'          => '小計（税込み）',
                    'subtotal'                   => '小計',
                    'tax-amount'                 => '税額',
                    'tax'                        => '税金',
                    'vat-number'                 => 'VAT番号',
                ],
            ],

            'reviews' => [
                'empty-review' => 'まだ商品のレビューを投稿していません',
                'title'        => 'レビュー',
            ],

            'downloadable-products' => [
                'available'           => '利用可能',
                'completed'           => '完了',
                'date'                => '日付',
                'download-error'      => 'ダウンロードリンクの有効期限が切れています。',
                'expired'             => '期限切れ',
                'empty-product'       => 'ダウンロードする製品がありません',
                'name'                => 'ダウンロード可能な製品',
                'orderId'             => '注文ID',
                'pending'             => '保留中',
                'payment-error'       => 'このダウンロードのための支払いが行われていません。',
                'records-found'       => 'レコードが見つかりました',
                'remaining-downloads' => '残りのダウンロード回数',
                'status'              => 'ステータス',
                'title'               => 'タイトル',
            ],

            'wishlist' => [
                'color'              => '色',
                'delete-all'         => 'すべて削除',
                'empty'              => 'ウィッシュリストに商品が追加されていませんでした。',
                'move-to-cart'       => 'カートに移動',
                'moved'              => 'アイテムが正常にカートに移動しました',
                'moved-success'      => 'アイテムがカートに正常に移動しました',
                'page-title'         => 'ウィッシュリスト',
                'product-removed'    => '管理者によって製品が利用できなくなったため、製品はもう利用できません',
                'profile'            => 'プロフィール',
                'remove'             => '削除',
                'remove-all-success' => 'ウィッシュリストからすべてのアイテムが削除されました',
                'remove-fail'        => 'アイテムはウィッシュリストから削除できません',
                'removed'            => 'アイテムがウィッシュリストから正常に削除されました',
                'see-details'        => '詳細を見る',
                'success'            => 'アイテムが正常にウィッシュリストに追加されました',
                'title'              => 'ウィッシュリスト',
            ],
        ],
    ],

    'components' => [
        'accordion' => [
            'default-content' => 'デフォルトコンテンツ',
            'default-header'  => 'デフォルトヘッダー',
        ],

        'drawer' => [
            'default-toggle'  => 'デフォルトトグル',
        ],

        'media' => [
            'index' => [
                'add-attachments' => '添付ファイルを追加',
                'add-image'       => '画像を追加',
            ],
        ],

        'layouts' => [
            'header' => [
                'account'           => 'アカウント',
                'bagisto'           => 'バギスト',
                'cart'              => 'カート',
                'compare'           => '比較',
                'dropdown-text'     => 'カート、注文、ウィッシュリストの管理',
                'logout'            => 'ログアウト',
                'no-category-found' => 'カテゴリが見つかりませんでした。',
                'orders'            => '注文',
                'profile'           => 'プロフィール',
                'search'            => '検索',
                'search-text'       => 'ここで製品を検索',
                'sign-in'           => 'サインイン',
                'sign-up'           => '新規登録',
                'submit'            => '送信',
                'title'             => 'アカウント',
                'welcome'           => 'ようこそ',
                'welcome-guest'     => 'ゲストさん、ようこそ',
                'wishlist'          => 'ウィッシュリスト',

                'desktop' => [
                    'top' => [
                        'default-locale' => 'デフォルトのロケール',
                    ],
                ],

                'mobile' => [
                    'currencies' => '通貨',
                    'locales'    => 'ロケール',
                    'login'      => 'サインアップまたはログイン',
                ],
            ],

            'footer' => [
                'about-us'               => '当社について',
                'contact-us'             => 'お問い合わせ',
                'currency'               => '通貨',
                'customer-service'       => 'カスタマーサービス',
                'email'                  => 'Email',
                'footer-content'         => 'フッターコンテンツ',
                'footer-text'            => '© 著作権 2010 - :current_year, Webkul Software (インドで登録済み)。全ての権利を保有しています。',
                'locale'                 => 'ロケール',
                'newsletter-text'        => '楽しいニュースレターの準備をしてください！',
                'order-return'           => '注文と返品',
                'payment-policy'         => '支払いポリシー',
                'privacy-cookies-policy' => 'プライバシーとクッキーポリシー',
                'shipping-policy'        => '配送ポリシー',
                'subscribe'              => '登録する',
                'subscribe-newsletter'   => 'ニュースレターに登録する',
                'subscribe-stay-touch'   => 'お知らせにご登録いただくために。',
                'whats-new'              => '新着情報',
            ],
        ],

        'datagrid' => [
            'toolbar' => [
                'length-of' => ':length の',
                'results'   => ':total 件の結果',
                'selected'  => ':total 件選択済み',

                'mass-actions' => [
                    'must-select-a-mass-action'        => 'マスアクションを選択する必要があります。',
                    'must-select-a-mass-action-option' => 'マスアクションのオプションを選択する必要があります。',
                    'no-records-selected'              => 'レコードが選択されていません。',
                    'select-action'                    => 'アクションを選択',
                ],

                'search' => [
                    'title' => '検索',
                ],

                'filter' => [
                    'apply-filter' => 'フィルタを適用',
                    'title'        => 'フィルタ',

                    'dropdown' => [
                        'select' => '選択',

                        'searchable' => [
                            'at-least-two-chars' => '少なくとも2文字入力してください...',
                            'no-results'         => '結果が見つかりません...',
                        ],
                    ],

                    'custom-filters' => [
                        'clear-all' => 'すべてクリア',
                    ],
                ],
            ],

            'table' => [
                'actions'              => 'アクション',
                'next-page'            => '次のページ',
                'no-records-available' => '利用可能なレコードがありません。',
                'of'                   => '全 :total エントリー中',
                'page-navigation'      => 'ページナビゲーション',
                'page-number'          => 'ページ番号',
                'previous-page'        => '前のページ',
                'showing'              => ':firstItem 件を表示中',
                'to'                   => ':lastItem まで',
            ],
        ],

        'modal' => [
            'default-content' => 'デフォルトコンテンツ',
            'default-header'  => 'デフォルトヘッダー',

            'confirm' => [
                'agree-btn'    => '同意',
                'disagree-btn' => '同意しない',
                'message'      => 'このアクションを実行してもよろしいですか？',
                'title'        => '確認',
            ],
        ],

        'products' => [
            'card' => [
                'add-to-cart'            => 'カートに追加',
                'add-to-compare'         => '比較リストに追加',
                'add-to-compare-success' => 'アイテムが比較リストに追加されました。',
                'add-to-wishlist'        => 'ウィッシュリストに追加',
                'already-in-compare'     => 'アイテムはすでに比較リストに追加されています。',
                'new'                    => '新着',
                'review-description'     => 'この製品の最初のレビュアになる',
                'sale'                   => 'セール',
            ],

            'carousel' => [
                'next'     => '次へ',
                'previous' => '前へ',
                'view-all' => 'すべて表示',
            ],

            'ratings' => [
                'title' => '評価',
            ],
        ],

        'range-slider' => [
            'min-range' => '最小範囲',
            'max-range' => '最大範囲',
            'range'     => '範囲:',
        ],

        'carousel' => [
            'image-slide' => '画像スライド',
            'next'        => '次へ',
            'previous'    => '前へ',
        ],

        'quantity-changer' => [
            'decrease-quantity' => '数量を減らす',
            'increase-quantity' => '数量を増やす',
        ],
    ],

    'products' => [
        'prices' => [
            'grouped' => [
                'starting-at' => '最低価格',
            ],

            'configurable' => [
                'as-low-as' => '最低価格',
            ],
        ],

        'sort-by' => [
            'title' => '並び替え',
        ],

        'view' => [
            'type' => [
                'configurable' => [
                    'select-options'       => 'オプションを選択してください',
                    'select-above-options' => '上記のオプションを選択してください',
                ],

                'bundle' => [
                    'none'         => 'なし',
                    'total-amount' => '合計金額',
                ],

                'downloadable' => [
                    'links'   => 'リンク',
                    'sample'  => 'サンプル',
                    'samples' => 'サンプル',
                ],

                'grouped' => [
                    'name' => '名前',
                ],
            ],

            'gallery' => [
                'product-image'   => '商品画像',
                'thumbnail-image' => 'サムネイル画像',
            ],

            'reviews' => [
                'attachments'      => '添付ファイル',
                'cancel'           => 'キャンセル',
                'comment'          => 'コメント',
                'customer-review'  => 'カスタマーレビュー',
                'empty-review'     => 'レビューが見つかりません。最初のレビューを投稿してください',
                'failed-to-upload' => '画像のアップロードに失敗しました',
                'load-more'        => 'さらに読み込む',
                'name'             => '名前',
                'rating'           => '評価',
                'ratings'          => '評価',
                'submit-review'    => 'レビューを送信',
                'success'          => 'レビューが正常に送信されました。',
                'title'            => 'タイトル',
                'translate'        => '翻訳',
                'translating'      => '翻訳中...',
                'write-a-review'   => 'レビューを書く',
            ],

            'add-to-cart'            => 'カートに追加',
            'add-to-compare'         => '比較に追加',
            'add-to-wishlist'        => 'ウィッシュリストに追加',
            'additional-information' => '追加情報',
            'already-in-compare'     => '製品はすでに比較に追加されています。',
            'buy-now'                => '今すぐ購入',
            'compare'                => '比較',
            'description'            => '説明',
            'related-product-title'  => '関連製品',
            'review'                 => 'レビュー',
            'tax-inclusive'          => '税込み',
            'up-sell-title'          => '他にも気に入る製品が見つかりました！',
        ],

        'type' => [
            'abstract' => [
                'offers' => ':qty 個を :price で購入し、:discount 節約',
            ],
        ],
    ],

    'categories' => [
        'filters' => [
            'clear-all' => 'すべてクリア',
            'filter'    => 'フィルター',
            'filters'   => 'フィルター:',
            'sort'      => '並び替え',
        ],

        'toolbar' => [
            'grid' => 'グリッド',
            'list' => 'リスト',
            'show' => '表示',
        ],

        'view' => [
            'empty'     => 'このカテゴリには利用可能な製品がありません',
            'load-more' => 'さらに読み込む',
        ],
    ],

    'search' => [
        'title'   => ':queryの検索結果',
        'results' => '検索結果',

        'images' => [
            'index' => [
                'only-images-allowed'  => '画像のみ (.jpeg、.jpg、.png など) が許可されています。',
                'search'               => '検索',
                'size-limit-error'     => 'サイズ制限エラー',
                'something-went-wrong' => '問題が発生しました。後でもう一度お試しください。',
            ],

            'results' => [
                'analyzed-keywords' => '分析されたキーワード:',
            ],
        ],
    ],

    'compare' => [
        'already-added'      => 'アイテムはすでに比較リストに追加されています',
        'delete-all'         => 'すべて削除',
        'empty-text'         => '比較リストにアイテムがありません',
        'item-add-success'   => 'アイテムが比較リストに正常に追加されました',
        'product-compare'    => '製品比較',
        'remove-all-success' => 'すべてのアイテムが正常に削除されました。',
        'remove-error'       => '問題が発生しました。後でもう一度試してください。',
        'remove-success'     => 'アイテムが正常に削除されました。',
        'title'              => '製品比較',
    ],

    'checkout' => [
        'success' => [
            'info'          => '注文の詳細とトラッキング情報をメールでお送りします',
            'order-id-info' => '注文IDは #:order_id です',
            'thanks'        => 'ご注文いただき、ありがとうございます！',
            'title'         => '注文が正常に完了しました',
        ],

        'cart' => [
            'continue-to-checkout'      => 'チェックアウトに進む',
            'illegal'                   => '数量は1未満にできません。',
            'inactive-add'              => '非アクティブなアイテムはカートに追加できません。',
            'inactive'                  => 'アイテムは無効になり、カートから削除されました。',
            'inventory-warning'         => '要求された数量は利用できません。後でやり直してください。',
            'item-add-to-cart'          => 'アイテムが正常に追加されました',
            'minimum-order-message'     => '最小注文金額は です',
            'missing-fields'            => 'この製品にはいくつかの必須フィールドがありません。',
            'missing-options'           => 'この製品にはオプションがありません。',
            'paypal-payment-cancelled'  => 'PayPalの支払いがキャンセルされました。',
            'qty-missing'               => '少なくとも1つの製品は1以上の数量を持つ必要があります。',
            'return-to-shop'            => 'ショップに戻る',
            'rule-applied'              => 'カートルールが適用されました',
            'select-hourly-duration'    => '1時間のスロット期間を選択してください。',
            'success-remove'            => 'アイテムはカートから正常に削除されました。',
            'suspended-account-message' => 'アカウントは一時停止されました。',

            'index' => [
                'bagisto'                  => 'バギスト',
                'cart'                     => 'カート',
                'continue-shopping'        => 'ショッピングを続ける',
                'empty-product'            => 'カートに製品がありません。',
                'excl-tax'                 => '税抜き:',
                'home'                     => 'ホーム',
                'items-selected'           => ':count 個のアイテムが選択されました',
                'move-to-wishlist'         => 'ウィッシュリストに移動',
                'move-to-wishlist-success' => '選択したアイテムはウィッシュリストに正常に移動しました。',
                'price'                    => '価格',
                'product-name'             => '製品名',
                'quantity'                 => '数量',
                'quantity-update'          => '数量が正常に更新されました',
                'remove'                   => '削除',
                'remove-selected-success'  => '選択したアイテムはカートから正常に削除されました。',
                'see-details'              => '詳細を表示',
                'select-all'               => 'すべて選択',
                'select-cart-item'         => 'カートのアイテムを選択',
                'tax'                      => '税金',
                'total'                    => '合計',
                'update-cart'              => 'カートを更新',
                'view-cart'                => 'カートを表示',

                'cross-sell' => [
                    'title' => 'さらなる選択肢',
                ],
            ],

            'mini-cart' => [
                'continue-to-checkout' => 'チェックアウトに進む',
                'empty-cart'           => 'カートは空です',
                'excl-tax'             => '税抜き:',
                'offer-on-orders'      => '1回目の注文で最大30%割引',
                'remove'               => '削除',
                'see-details'          => '詳細を表示',
                'shopping-cart'        => 'ショッピングカート',
                'subtotal'             => '小計',
                'view-cart'            => 'カートを表示',
            ],

            'summary' => [
                'cart-summary'              => 'カートの概要',
                'delivery-charges-excl-tax' => '配送料（税抜き）',
                'delivery-charges-incl-tax' => '配送料（税込み）',
                'delivery-charges'          => '配送料',
                'discount-amount'           => '割引額',
                'grand-total'               => '合計金額',
                'place-order'               => '注文する',
                'proceed-to-checkout'       => 'チェックアウトに進む',
                'sub-total-excl-tax'        => '小計（税抜き）',
                'sub-total-incl-tax'        => '小計（税込み）',
                'sub-total'                 => '小計',
                'tax'                       => '税金',

                'estimate-shipping' => [
                    'country'        => '国',
                    'info'           => '配送先を入力して、配送料と税金の見積もりを取得してください。',
                    'postcode'       => '郵便番号',
                    'select-country' => '国を選択',
                    'select-state'   => '都道府県を選択',
                    'state'          => '都道府県',
                    'title'          => '配送料と税金の見積もり',
                ],
            ],
        ],

        'onepage' => [
            'address' => [
                'add-new'                => '新しい住所を追加',
                'add-new-address'        => '新しい住所を追加',
                'back'                   => '戻る',
                'billing-address'        => '請求先住所',
                'check-billing-address'  => '請求先住所がありません。',
                'check-shipping-address' => '配送先住所がありません。',
                'city'                   => '市町村',
                'company-name'           => '会社名',
                'confirm'                => '確認',
                'country'                => '国',
                'email'                  => 'メールアドレス',
                'first-name'             => '名',
                'last-name'              => '姓',
                'postcode'               => '郵便番号',
                'proceed'                => '進む',
                'same-as-billing'        => '配送のために同じ住所を使用しますか？',
                'save'                   => '保存',
                'save-address'           => 'アドレス帳に保存',
                'select-country'         => '国を選択',
                'select-state'           => '都道府県を選択',
                'shipping-address'       => '配送先住所',
                'state'                  => '都道府県',
                'street-address'         => '住所',
                'telephone'              => '電話番号',
                'title'                  => '住所',
            ],

            'index' => [
                'checkout' => 'チェックアウト',
                'home'     => 'ホーム',
            ],

            'payment' => [
                'payment-method' => '支払方法',
            ],

            'shipping' => [
                'shipping-method' => '配送方法',
            ],

            'summary' => [
                'cart-summary'              => 'カートの概要',
                'delivery-charges-excl-tax' => '配送料（税抜き）',
                'delivery-charges-incl-tax' => '配送料（税込み）',
                'delivery-charges'          => '配送料',
                'discount-amount'           => '割引額',
                'excl-tax'                  => '税抜き:',
                'grand-total'               => '合計金額',
                'place-order'               => '注文する',
                'price_&_qty'               => ':price × :qty',
                'processing'                => '処理中',
                'sub-total-excl-tax'        => '小計（税抜き）',
                'sub-total-incl-tax'        => '小計（税込み）',
                'sub-total'                 => '小計',
                'tax'                       => '税金',
            ],
        ],

        'coupon' => [
            'already-applied' => 'クーポンコードは既に適用されています。',
            'applied'         => 'クーポンが適用されました',
            'apply'           => 'クーポンを適用',
            'apply-issue'     => 'クーポンコードは適用できません。',
            'button-title'    => '適用',
            'code'            => 'クーポンコード',
            'discount'        => 'クーポン割引',
            'enter-your-code' => 'コードを入力してください',
            'error'           => '何か問題が発生しました',
            'invalid'         => 'クーポンコードが無効です。',
            'remove'          => 'クーポンを削除',
            'subtotal'        => '小計',
            'success-apply'   => 'クーポンコードが正常に適用されました。',
        ],

        'login' => [
            'email'    => 'メールアドレス',
            'password' => 'パスワード',
            'title'    => 'サインイン',
        ],
    ],

    'home' => [
        'contact' => [
            'about'         => '私たちについて',
            'desc'          => '何をお考えですか？',
            'describe-here' => 'ここに説明してください',
            'email'         => 'メール',
            'message'       => 'メッセージ',
            'name'          => '名前',
            'phone-number'  => '電話番号',
            'submit'        => '送信',
            'title'         => 'お問い合わせ',
        ],

        'index' => [
            'offer'               => '初めての注文で最大40%OFF 今すぐショッピング',
            'resend-verify-email' => '確認メールを再送信',
            'verify-email'        => 'メールアカウントを確認',
        ],

        'thanks-for-contact' => 'ご意見やご質問をお寄せいただきありがとうございます。返信いたしますので、しばらくお待ちください。',
    ],

    'partials' => [
        'pagination' => [
            'pagination-showing' => '合計 :total エントリのうち :firstItem から :lastItem を表示',
        ],
    ],

    'errors' => [
        'go-to-home' => 'ホームに戻る',

        '404' => [
            'description' => 'おっと！お探しのページは休暇中のようです。お探しのものが見つかりませんでした。',
            'title'       => '404 ページが見つかりません',
        ],

        '401' => [
            'description' => 'おっと！このページにアクセスする権限がありません。必要な資格情報が不足しているようです。',
            'title'       => '401 認証されていません',
        ],

        '403' => [
            'description' => 'おっと！このページは立ち入り禁止です。このコンテンツを表示するために必要な権限がありません。',
            'title'       => '403 禁止されています',
        ],

        '500' => [
            'description' => 'おっと！何か問題が発生しました。お探しのページの読み込みに問題が発生しているようです。',
            'title'       => '500 サーバーエラー',
        ],

        '503' => [
            'description' => 'おっと！一時的にメンテナンス中のようです。少し後で再度チェックしてください。',
            'title'       => '503 サービス利用不可',
        ],
    ],

    'layouts' => [
        'address'               => '住所',
        'downloadable-products' => 'ダウンロード可能な製品',
        'my-account'            => 'マイアカウント',
        'orders'                => '注文',
        'profile'               => 'プロフィール',
        'reviews'               => 'レビュー',
        'wishlist'              => 'ウィッシュリスト',
    ],

    'subscription' => [
        'already'             => '既にニュースレターに登録されています。',
        'subscribe-success'   => 'ニュースレターに正常に登録されました。',
        'unsubscribe-success' => 'ニュースレターの登録を解除しました。',
    ],

    'emails' => [
        'dear'   => '親愛なる :customer_name 様',
        'thanks' => '何かお手伝いが必要な場合は、<a href=":link" style=":style">:email</a> までお問い合わせください。<br/>ありがとうございます！',

        'customers' => [
            'registration' => [
                'credentials-description' => 'ご登録いただき、アカウントが作成されました。アカウントの詳細は以下の通りです：',
                'description'             => 'アカウントが正常に作成され、メールアドレスとパスワードの資格情報を使用してログインできるようになりました。ログインすると、過去の注文の確認、ウィッシュリストの表示、アカウント情報の編集など、他のサービスにアクセスできます。',
                'greeting'                => 'お買い物いただき、ありがとうございます！',
                'password'                => 'パスワード',
                'sign-in'                 => 'サインイン',
                'subject'                 => '新規顧客登録',
                'username-email'          => 'ユーザー名/メールアドレス',
            ],

            'forgot-password' => [
                'description'    => 'このメールは、アカウントのパスワードリセットリクエストを受けたためです。',
                'greeting'       => 'パスワードをお忘れですか？',
                'reset-password' => 'パスワードをリセット',
                'subject'        => 'パスワードリセットメール',
            ],

            'update-password' => [
                'description' => 'このメールは、パスワードが更新されたことをお知らせするためです。',
                'greeting'    => 'パスワードが更新されました！',
                'subject'     => 'パスワードが更新されました',
            ],

            'verification' => [
                'description'  => 'メールアドレスを確認するには、以下のボタンをクリックしてください。',
                'greeting'     => 'ようこそ！',
                'subject'      => 'アカウント確認メール',
                'verify-email' => 'メールアドレスを確認',
            ],

            'commented' => [
                'description' => 'ノートは - :note',
                'subject'     => '新しいコメントが追加されました',
            ],

            'subscribed' => [
                'description' => 'おめでとうございます！ニュースレターコミュニティへのご参加を歓迎します。最新のニュース、トレンド、独占オファーをお届けし、お楽しみいただけます。',
                'greeting'    => 'ニュースレターへようこそ！',
                'subject'     => 'ニュースレターに登録しました',
                'unsubscribe' => '登録解除',
            ],
        ],

        'contact-us' => [
            'contact-from'    => 'ウェブサイトのお問い合わせフォーム経由で',
            'reply-to-mail'   => 'このメールに返信してください。',
            'reach-via-phone' => 'または、電話でお問い合わせいただけます。',
            'inquiry-from'    => 'お問い合わせ元',
            'to'              => 'お問い合わせ先',
        ],

        'orders' => [
            'created' => [
                'greeting' => ':created_at に注文 :order_id をご注文いただき、ありがとうございます',
                'subject'  => '新規注文確認',
                'summary'  => '注文の要約',
                'title'    => '注文確認！',
            ],

            'invoiced' => [
                'greeting' => ':created_at に注文 :order_id の請求書 #:invoice_id が作成されました',
                'subject'  => '新規請求書確認',
                'summary'  => '請求書の要約',
                'title'    => '請求書確認！',
            ],

            'shipped' => [
                'greeting' => ':created_at に注文 :order_id が出荷されました',
                'subject'  => '新規出荷確認',
                'summary'  => '出荷の要約',
                'title'    => '出荷確認！',
            ],

            'refunded' => [
                'greeting' => ':created_at に注文 :order_id の返金が開始されました',
                'subject'  => '新規返金確認',
                'summary'  => '返金の要約',
                'title'    => '返金確認！',
            ],

            'canceled' => [
                'greeting' => ':created_at に注文 :order_id はキャンセルされました',
                'subject'  => '新規注文キャンセル確認',
                'summary'  => '注文の要約',
                'title'    => '注文キャンセル確認！',
            ],

            'commented' => [
                'subject' => '新しいコメントが追加されました',
                'title'   => ':created_at にご注文 :order_id に新しいコメントが追加されました',
            ],

            'billing-address'            => '請求先住所',
            'carrier'                    => 'キャリア',
            'contact'                    => '連絡先',
            'discount'                   => '割引',
            'excl-tax'                   => '税抜き: ',
            'grand-total'                => '総計',
            'name'                       => '名前',
            'payment'                    => '支払い',
            'price'                      => '価格',
            'qty'                        => '数量',
            'shipping-address'           => '配送先住所',
            'shipping-handling-excl-tax' => '送料・手数料（税抜き）',
            'shipping-handling-incl-tax' => '送料・手数料（税込み）',
            'shipping-handling'          => '送料・手数料',
            'shipping'                   => '配送',
            'sku'                        => 'SKU',
            'subtotal-excl-tax'          => '小計（税抜き）',
            'subtotal-incl-tax'          => '小計（税込み）',
            'subtotal'                   => '小計',
            'tax'                        => '税金',
            'tracking-number'            => '追跡番号: :tracking_number',
        ],
    ],
];
