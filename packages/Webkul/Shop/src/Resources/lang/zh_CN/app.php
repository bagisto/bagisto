<?php

return [
    'customers' => [
        'forgot-password' => [
            'already-sent'         => '密码重置邮件已发送。',
            'back'                 => '返回登录？',
            'bagisto'              => '巴基斯托',
            'email'                => '电子邮件',
            'email-not-exist'      => '我们找不到使用该电子邮件地址的用户。',
            'footer'               => '© 版权所有 2010 - :current_year，Webkul Software（注册于印度）。保留所有权利。',
            'forgot-password-text' => '如果您忘记了密码，请通过输入您的电子邮件地址来找回密码。',
            'page-title'           => '忘记密码？',
            'reset-link-sent'      => '我们已将重置密码链接发送到您的电子邮件。',
            'sign-in-button'       => '登录',
            'submit'               => '重置密码',
            'title'                => '找回密码',
        ],

        'reset-password' => [
            'back-link-title'  => '返回登录',
            'bagisto'          => '巴基斯托',
            'confirm-password' => '确认密码',
            'email'            => '注册电子邮件',
            'footer'           => '© 版权 2010 - :current_year，Webkul Software（注册于印度）。保留所有权利。',
            'password'         => '密码',
            'submit-btn-title' => '重设密码',
            'title'            => '重设密码',
        ],

        'login-form' => [
            'bagisto'             => '巴基斯托',
            'button-title'        => '登录',
            'create-your-account' => '创建您的帐户',
            'email'               => '电子邮件',
            'footer'              => '© 版权 2010 - :current_year，Webkul Software（注册于印度）。保留所有权利。',
            'forgot-pass'         => '忘记密码？',
            'form-login-text'     => '如果您已经有帐户，请使用您的电子邮件登录。',
            'invalid-credentials' => '请检查您的凭据并重试。',
            'new-customer'        => '新用户？',
            'not-activated'       => '您的帐户请求已提交，等待管理员批准。',
            'page-title'          => '用户登录',
            'password'            => '密码',
            'show-password'       => '显示密码',
            'title'               => '登录',
            'verify-first'        => '请先验证您的电子邮件。',
        ],

        'signup-form' => [
            'account-exists'              => '已经有账户？',
            'bagisto'                     => '巴基斯托',
            'button-title'                => '注册',
            'confirm-pass'                => '确认密码',
            'email'                       => '电子邮件',
            'first-name'                  => '名字',
            'footer'                      => '© 版权所有 2010 - :current_year，Webkul Software（注册于印度）。保留所有权利。',
            'form-signup-text'            => '如果您是我们商店的新用户，我们很高兴有您作为会员。',
            'last-name'                   => '姓氏',
            'page-title'                  => '成为用户',
            'password'                    => '密码',
            'sign-in-button'              => '登录',
            'subscribe-to-newsletter'     => '订阅通讯',
            'success'                     => '成功创建账户。',
            'success-verify'              => '成功创建账户，已发送验证电子邮件。',
            'success-verify-email-unsent' => '成功创建账户，但未发送验证电子邮件。',
            'verification-not-sent'       => '错误！发送验证电子邮件时出现问题，请稍后再试。',
            'verification-sent'           => '已发送验证电子邮件',
            'verified'                    => '您的账户已验证，请尝试登录。',
            'verify-failed'               => '我们无法验证您的电子邮件帐户。',
        ],

        'account' => [
            'home' => '主页',

            'profile' => [
                'index' => [
                    'delete'         => '删除',
                    'delete-failed'  => '删除客户时遇到错误。',
                    'delete-profile' => '删除个人资料',
                    'delete-success' => '客户删除成功',
                    'dob'            => '出生日期',
                    'edit'           => '编辑',
                    'edit-success'   => '个人资料更新成功',
                    'email'          => '邮箱',
                    'enter-password' => '输入您的密码',
                    'first-name'     => '名字',
                    'gender'         => '性别',
                    'last-name'      => '姓氏',
                    'order-pending'  => '无法删除客户帐户，因为有一些待处理或处理中的订单。',
                    'title'          => '个人资料',
                    'unmatched'      => '旧密码不匹配。',
                    'wrong-password' => '密码错误！',
                ],

                'edit' => [
                    'confirm-password'        => '确认密码',
                    'current-password'        => '当前密码',
                    'dob'                     => '出生日期',
                    'edit'                    => '编辑',
                    'edit-profile'            => '编辑个人资料',
                    'email'                   => '邮箱',
                    'female'                  => '女性',
                    'first-name'              => '名字',
                    'gender'                  => '性别',
                    'last-name'               => '姓氏',
                    'male'                    => '男性',
                    'new-password'            => '新密码',
                    'other'                   => '其他',
                    'phone'                   => '电话',
                    'save'                    => '保存',
                    'subscribe-to-newsletter' => '订阅通讯',
                ],
            ],

            'addresses' => [
                'index' => [
                    'add-address'      => '添加地址',
                    'create-success'   => '地址已成功添加。',
                    'default-address'  => '默认地址',
                    'default-delete'   => '默认地址无法更改。',
                    'delete'           => '删除',
                    'delete-success'   => '地址删除成功',
                    'edit'             => '编辑',
                    'edit-success'     => '地址更新成功。',
                    'empty-address'    => '您尚未添加地址到您的帐户。',
                    'security-warning' => '发现可疑活动！',
                    'set-as-default'   => '设为默认',
                    'title'            => '地址',
                    'update-success'   => '地址已成功更新。',
                ],

                'create' => [
                    'add-address'    => '添加地址',
                    'city'           => '城市',
                    'company-name'   => '公司名称',
                    'country'        => '国家',
                    'email'          => '邮箱',
                    'first-name'     => '名字',
                    'last-name'      => '姓氏',
                    'phone'          => '电话',
                    'post-code'      => '邮编',
                    'save'           => '保存',
                    'select-country' => '选择国家',
                    'set-as-default' => '设为默认',
                    'state'          => '州/省',
                    'street-address' => '街道地址',
                    'title'          => '地址',
                    'vat-id'         => 'VAT号码',
                ],

                'edit' => [
                    'city'           => '城市',
                    'company-name'   => '公司名称',
                    'country'        => '国家',
                    'edit'           => '编辑',
                    'email'          => '邮箱',
                    'first-name'     => '名字',
                    'last-name'      => '姓氏',
                    'phone'          => '电话',
                    'post-code'      => '邮编',
                    'select-country' => '选择国家',
                    'state'          => '州/省',
                    'street-address' => '街道地址',
                    'title'          => '地址',
                    'update-btn'     => '更新',
                    'vat-id'         => 'VAT号码',
                ],
            ],

            'orders' => [
                'action'      => '操作',
                'action-view' => '查看',
                'empty-order' => '您还没有订购任何产品',
                'order'       => '订单',
                'order-date'  => '订单日期',
                'order-id'    => '订单ID',
                'subtotal'    => '小计',
                'title'       => '订单',
                'total'       => '总计',

                'status' => [
                    'title' => '状态',

                    'options' => [
                        'canceled'        => '已取消',
                        'closed'          => '已关闭',
                        'completed'       => '已完成',
                        'fraud'           => '欺诈',
                        'pending'         => '待处理',
                        'pending-payment' => '待付款',
                        'processing'      => '处理中',
                    ],
                ],

                'view' => [
                    'billing-address'      => '账单地址',
                    'cancel-btn-title'     => '取消',
                    'cancel-confirm-msg'   => '确定要取消此订单吗？',
                    'cancel-error'         => '无法取消您的订单。',
                    'cancel-success'       => '您的订单已取消',
                    'contact'              => '联系方式',
                    'item-invoiced'        => '已开票商品',
                    'item-refunded'        => '已退款商品',
                    'item-shipped'         => '已发货商品',
                    'item-ordered'         => '已下单商品',
                    'order-id'             => '订单编号',
                    'page-title'           => '订单 #:order_id',
                    'payment-method'       => '付款方式',
                    'reorder-btn-title'    => '重新下单',
                    'shipping-address'     => '收货地址',
                    'shipping-method'      => '发货方式',
                    'shipping-and-payment' => '运输和付款详情',
                    'status'               => '状态',
                    'title'                => '查看',
                    'total'                => '总计',

                    'information' => [
                        'discount'                   => '折扣',
                        'excl-tax'                   => '不含税：',
                        'grand-total'                => '总计',
                        'info'                       => '信息',
                        'item-canceled'              => '已取消 (:qty_canceled)',
                        'item-refunded'              => '已退款 (:qty_refunded)',
                        'invoiced-item'              => '已开票 (:qty_invoiced)',
                        'item-shipped'               => '已发货 (:qty_shipped)',
                        'item-status'                => '商品状态',
                        'ordered-item'               => '已下单 (:qty_ordered)',
                        'placed-on'                  => '下单时间',
                        'price'                      => '价格',
                        'product-name'               => '商品名称',
                        'shipping-handling'          => '运输和处理',
                        'shipping-handling-excl-tax' => '运输和处理（不含税）',
                        'shipping-handling-incl-tax' => '运输和处理（含税）',
                        'sku'                        => 'SKU',
                        'subtotal'                   => '小计',
                        'subtotal-excl-tax'          => '小计（不含税）',
                        'subtotal-incl-tax'          => '小计（含税）',
                        'order-summary'              => '订单摘要',
                        'tax'                        => '税',
                        'tax-amount'                 => '税额',
                        'tax-percent'                => '税率',
                        'total-due'                  => '应付总额',
                        'total-paid'                 => '已付总额',
                        'total-refunded'             => '已退款总额',
                    ],

                    'invoices' => [
                        'discount'                   => '折扣',
                        'excl-tax'                   => '不含税：',
                        'grand-total'                => '总计',
                        'individual-invoice'         => '发票 #:invoice_id',
                        'invoices'                   => '发票',
                        'price'                      => '价格',
                        'print'                      => '打印',
                        'product-name'               => '商品名称',
                        'products-ordered'           => '已订购商品',
                        'qty'                        => '数量',
                        'shipping-handling-excl-tax' => '运输和处理（不含税）',
                        'shipping-handling-incl-tax' => '运输和处理（含税）',
                        'shipping-handling'          => '运输和处理',
                        'sku'                        => 'SKU',
                        'subtotal-excl-tax'          => '小计（不含税）',
                        'subtotal-incl-tax'          => '小计（含税）',
                        'subtotal'                   => '小计',
                        'tax'                        => '税',
                        'tax-amount'                 => '税额',
                    ],

                    'shipments' => [
                        'individual-shipment' => '发货 #:shipment_id',
                        'product-name'        => '商品名称',
                        'qty'                 => '数量',
                        'shipments'           => '发货',
                        'sku'                 => 'SKU',
                        'subtotal'            => '小计',
                        'tracking-number'     => '跟踪编号',
                    ],

                    'refunds' => [
                        'adjustment-fee'             => '调整费用',
                        'adjustment-refund'          => '调整退款',
                        'discount'                   => '折扣',
                        'grand-total'                => '总计',
                        'individual-refund'          => '退款 #:refund_id',
                        'no-result-found'            => '找不到任何记录。',
                        'order-summary'              => '订单摘要',
                        'price'                      => '价格',
                        'product-name'               => '商品名称',
                        'qty'                        => '数量',
                        'refunds'                    => '退款',
                        'shipping-handling'          => '运输和处理',
                        'shipping-handling-excl-tax' => '运输和处理（不含税）',
                        'shipping-handling-incl-tax' => '运输和处理（含税）',
                        'sku'                        => 'SKU',
                        'subtotal'                   => '小计',
                        'subtotal-excl-tax'          => '小计（不含税）',
                        'subtotal-incl-tax'          => '小计（含税）',
                        'tax'                        => '税',
                        'tax-amount'                 => '税额',
                    ],
                ],

                'invoice-pdf' => [
                    'bank-details'               => '银行详细信息',
                    'bill-to'                    => '账单给',
                    'contact-number'             => '联系电话',
                    'contact'                    => '联系人',
                    'date'                       => '发票日期',
                    'discount'                   => '折扣',
                    'excl-tax'                   => '不含税：',
                    'grand-total'                => '总计',
                    'invoice-id'                 => '发票ID',
                    'invoice'                    => '发票',
                    'order-date'                 => '订单日期',
                    'order-id'                   => '订单ID',
                    'payment-method'             => '付款方式',
                    'payment-terms'              => '付款条件',
                    'price'                      => '价格',
                    'product-name'               => '产品名称',
                    'qty'                        => '数量',
                    'ship-to'                    => '收货地址',
                    'shipping-handling-excl-tax' => '运输和处理（不含税）',
                    'shipping-handling-incl-tax' => '运输和处理（含税）',
                    'shipping-handling'          => '运输和处理',
                    'shipping-method'            => '运输方式',
                    'sku'                        => 'SKU',
                    'subtotal-excl-tax'          => '小计（不含税）',
                    'subtotal-incl-tax'          => '小计（含税）',
                    'subtotal'                   => '小计',
                    'tax-amount'                 => '税费金额',
                    'tax'                        => '税费',
                    'vat-number'                 => '增值税号码',
                ],
            ],

            'reviews' => [
                'title'        => '评价',
                'empty-review' => '您还没有对任何商品进行评价',
            ],

            'downloadable-products' => [
                'available'           => '可用的',
                'completed'           => '完全的',
                'date'                => '日期',
                'download-error'      => '下载链接已过期。',
                'expired'             => '已过期',
                'empty-product'       => '您没有可下载的商品',
                'name'                => '可下载商品',
                'orderId'             => '订单ID',
                'pending'             => '待办的',
                'payment-error'       => '该下载商品尚未付款。',
                'records-found'       => '找到记录',
                'remaining-downloads' => '剩余下载次数',
                'status'              => '状态',
                'title'               => '名称',
            ],

            'wishlist' => [
                'color'              => '颜色',
                'delete-all'         => '全部删除',
                'empty'              => '愿望清单中没有任何商品。',
                'move-to-cart'       => '移到购物车',
                'moved'              => '商品已成功移至购物车',
                'moved-success'      => '商品成功移入購物車',
                'page-title'         => '愿望清单',
                'product-removed'    => '该商品已不再可用，因为管理员已将其删除',
                'profile'            => '个人资料',
                'remove'             => '移除',
                'remove-all-success' => '已成功从愿望清单中删除所有商品',
                'remove-fail'        => '商品无法从愿望清单中移除',
                'removed'            => '商品已成功从愿望清单中移除',
                'see-details'        => '查看详情',
                'success'            => '商品成功添加到愿望清单',
                'title'              => '愿望清单',
            ],
        ],
    ],

    'components' => [
        'accordion' => [
            'default-content' => '默认内容',
            'default-header'  => '默认标题',
        ],

        'drawer' => [
            'default-toggle' => '默认切换',
        ],

        'media' => [
            'index' => [
                'add-attachments' => '添加附件',
                'add-image'       => '添加图片',
            ],
        ],

        'layouts' => [
            'header' => [
                'account'           => '账户',
                'bagisto'           => '巴基斯托',
                'cart'              => '购物车',
                'compare'           => '比较',
                'dropdown-text'     => '管理购物车、订单和心愿单',
                'logout'            => '登出',
                'no-category-found' => '未找到类别。',
                'orders'            => '订单',
                'profile'           => '个人资料',
                'search'            => '搜索',
                'search-text'       => '在此搜索产品',
                'sign-in'           => '登录',
                'sign-up'           => '注册',
                'submit'            => '提交',
                'title'             => '账户',
                'welcome'           => '欢迎',
                'welcome-guest'     => '欢迎访客',
                'wishlist'          => '心愿单',

                'desktop' => [
                    'top' => [
                        'default-locale' => '默认区域设置',
                    ],
                ],

                'mobile' => [
                    'currencies' => '货币',
                    'locales'    => '语言',
                    'login'      => '注册或登录',
                ],
            ],

            'footer' => [
                'about-us'               => '关于我们',
                'contact-us'             => '联系我们',
                'currency'               => '货币',
                'customer-service'       => '客户服务',
                'email'                  => '电子邮件',
                'footer-content'         => '页脚内容',
                'footer-text'            => '© 版权所有 2010 - :current_year，Webkul Software（印度注册）。保留所有权利。',
                'locale'                 => '语言',
                'newsletter-text'        => '准备好我们有趣的新闻通讯！',
                'order-return'           => '订单和退货',
                'payment-policy'         => '付款政策',
                'privacy-cookies-policy' => '隐私和 Cookie 政策',
                'shipping-policy'        => '运输政策',
                'subscribe'              => '订阅',
                'subscribe-newsletter'   => '订阅新闻通讯',
                'subscribe-stay-touch'   => '订阅以保持联系。',
                'whats-new'              => '新消息',
            ],
        ],

        'datagrid' => [
            'toolbar' => [
                'length-of' => ':length of',
                'results'   => ':total Results',
                'selected'  => ':total Selected',

                'mass-actions' => [
                    'must-select-a-mass-action'        => '您必须选择一个批量操作。',
                    'must-select-a-mass-action-option' => '您必须选择一个批量操作的选项。',
                    'no-records-selected'              => '未选择任何记录。',
                    'select-action'                    => '选择操作',
                ],

                'search' => [
                    'title' => '搜索',
                ],

                'filter' => [
                    'apply-filter' => '应用过滤器',
                    'title'        => '过滤器',

                    'dropdown' => [
                        'select' => '选择',

                        'searchable' => [
                            'at-least-two-chars' => '至少输入2个字符...',
                            'no-results'         => '未找到结果...',
                        ],
                    ],

                    'custom-filters' => [
                        'clear-all' => '清除所有',
                    ],
                ],
            ],

            'table' => [
                'actions'              => '操作',
                'next-page'            => '下一页',
                'no-records-available' => '没有可用的记录。',
                'of'                   => '共 :total 条记录',
                'page-navigation'      => '页面导航',
                'page-number'          => '页码',
                'previous-page'        => '上一页',
                'showing'              => '显示 :firstItem',
                'to'                   => '至 :lastItem',
            ],
        ],

        'modal' => [
            'default-content' => '默认内容',
            'default-header'  => '默认标题',

            'confirm' => [
                'agree-btn'    => '同意',
                'disagree-btn' => '不同意',
                'message'      => '您确定要执行此操作吗？',
                'title'        => '您确定吗？',
            ],
        ],

        'products' => [
            'card' => [
                'add-to-cart'            => '添加到购物车',
                'add-to-compare'         => '添加到比较列表',
                'add-to-compare-success' => '商品已成功添加到比较列表。',
                'add-to-wishlist'        => '添加到愿望清单',
                'already-in-compare'     => '商品已经在比较列表中。',
                'new'                    => '新品',
                'review-description'     => '成为第一个评价这个产品的人',
                'sale'                   => '特卖',
            ],

            'carousel' => [
                'next'     => '下一个',
                'previous' => '上一个',
                'view-all' => '查看全部',
            ],

            'ratings' => [
                'title' => '评分',
            ],
        ],

        'range-slider' => [
            'max-range' => '最大范围',
            'min-range' => '最小范围',
            'range'     => '范围：',
        ],

        'carousel' => [
            'image-slide' => '图片幻灯片',
            'next'        => '下一个',
            'previous'    => '上一个',
        ],

        'quantity-changer' => [
            'decrease-quantity' => '减少数量',
            'increase-quantity' => '增加数量',
        ],
    ],

    'products' => [
        'prices' => [
            'grouped' => [
                'starting-at' => '起价',
            ],

            'configurable' => [
                'as-low-as' => '低至',
            ],
        ],

        'sort-by' => [
            'title' => '排序方式',
        ],

        'view' => [
            'type' => [
                'configurable' => [
                    'select-options'       => '请选择一个选项',
                    'select-above-options' => '请选择以上选项',
                ],

                'bundle' => [
                    'none'         => '无',
                    'total-amount' => '总金额',
                ],

                'downloadable' => [
                    'links'   => '链接',
                    'sample'  => '样品',
                    'samples' => '样品',
                ],

                'grouped' => [
                    'name' => '名称',
                ],
            ],

            'gallery' => [
                'product-image'   => '产品图片',
                'thumbnail-image' => '缩略图',
            ],

            'reviews' => [
                'attachments'      => '附件',
                'cancel'           => '取消',
                'comment'          => '评论',
                'customer-review'  => '客户评论',
                'empty-review'     => '未找到评论，成为第一个评论此产品的人',
                'failed-to-upload' => '图片上传失败',
                'load-more'        => '加载更多',
                'name'             => '名称',
                'rating'           => '评分',
                'ratings'          => '评级',
                'submit-review'    => '提交评论',
                'success'          => '评论成功提交。',
                'title'            => '标题',
                'translate'        => '翻译',
                'translating'      => '翻译中...',
                'write-a-review'   => '写评论',
            ],

            'add-to-cart'            => '加入购物车',
            'add-to-compare'         => '已添加到比较。',
            'add-to-wishlist'        => '添加到愿望清单',
            'additional-information' => '附加信息',
            'already-in-compare'     => '产品已经添加到比较列表中。',
            'buy-now'                => '立即购买',
            'compare'                => '比较',
            'description'            => '描述',
            'related-product-title'  => '相关产品',
            'review'                 => '评论',
            'tax-inclusive'          => '含税',
            'up-sell-title'          => '我们发现您可能喜欢的其他产品！',
        ],

        'type' => [
            'abstract' => [
                'offers' => '购买 :qty 件，每件 :price，节省 :discount',
            ],
        ],
    ],

    'categories' => [
        'filters' => [
            'clear-all' => '清除所有',
            'filter'    => '筛选',
            'filters'   => '筛选条件：',
            'sort'      => '排序',
        ],

        'toolbar' => [
            'grid' => '网格',
            'list' => '列表',
            'show' => '显示',
        ],

        'view' => [
            'empty'     => '此类别中没有可用产品',
            'load-more' => '加载更多',
        ],
    ],

    'search' => [
        'title'   => '搜索结果为：:query',
        'results' => '搜索结果',

        'images' => [
            'index' => [
                'something-went-wrong' => '出了点问题，请稍后重试。',
                'size-limit-error'     => '大小限制错误',
                'search'               => '搜索',
                'only-images-allowed'  => '只允许图像（.jpeg，.jpg，.png，..）。',
            ],

            'results' => [
                'analyzed-keywords' => '分析关键词：',
            ],
        ],
    ],

    'compare' => [
        'already-added'      => '产品已经添加到比较列表中',
        'delete-all'         => '删除所有',
        'empty-text'         => '您的比较列表中没有任何项目',
        'item-add-success'   => '产品成功添加到比较列表。',
        'product-compare'    => '产品比较',
        'remove-all-success' => '所有项目已成功移除。',
        'remove-error'       => '出了点问题，请稍后再试。',
        'remove-success'     => '项目已成功移除。',
        'title'              => '产品比较',
    ],

    'checkout' => [
        'success' => [
            'info'          => '我们将通过电子邮件发送您的订单详细信息和跟踪信息',
            'order-id-info' => '您的订单号码是：#:order_id',
            'thanks'        => '感谢您的订单！',
            'title'         => '订单已成功下达',
        ],

        'cart' => [
            'continue-to-checkout'      => '继续结帐',
            'illegal'                   => '数量不能少于一。',
            'inactive-add'              => '无法将停用的项目添加到购物车。',
            'inactive'                  => '该项目已停用，随后从购物车中移除。',
            'inventory-warning'         => '请求的数量不可用，请稍后再试。',
            'item-add-to-cart'          => '已成功添加项目',
            'minimum-order-message'     => '最低订单金额为',
            'missing-fields'            => '此产品缺少一些必填字段。',
            'missing-options'           => '此产品缺少选项。',
            'paypal-payment-cancelled'  => 'Paypal付款已被取消。',
            'qty-missing'               => '至少有一个产品应该有超过 1 个数量。',
            'return-to-shop'            => '返回商店',
            'rule-applied'              => '购物车规则已应用',
            'select-hourly-duration'    => '选择一小时的时段。',
            'success-remove'            => '项目已成功从购物车中移除。',
            'suspended-account-message' => '您的帐户已被暂停。',

            'index' => [
                'bagisto'                  => '巴基斯托',
                'cart'                     => '购物车',
                'continue-shopping'        => '继续购物',
                'empty-product'            => '您的购物车中没有产品。',
                'excl-tax'                 => '不含税：',
                'home'                     => '首页',
                'items-selected'           => ':count 个已选择的项目',
                'move-to-wishlist'         => '移至心愿单',
                'move-to-wishlist-success' => '已成功将选定的项目移至心愿单。',
                'price'                    => '价格',
                'product-name'             => '产品名称',
                'quantity'                 => '数量',
                'quantity-update'          => '数量已成功更新',
                'remove'                   => '删除',
                'remove-selected-success'  => '已成功从购物车中删除选定的项目。',
                'see-details'              => '查看详情',
                'select-all'               => '全选',
                'select-cart-item'         => '选择购物车项目',
                'tax'                      => '税',
                'total'                    => '总计',
                'update-cart'              => '更新购物车',
                'view-cart'                => '查看购物车',

                'cross-sell' => [
                    'title' => '更多选择',
                ],
            ],

            'mini-cart' => [
                'continue-to-checkout' => '继续结帐',
                'empty-cart'           => '您的购物车是空的',
                'excl-tax'             => '不含税：',
                'offer-on-orders'      => '首次下单立减30%',
                'remove'               => '删除',
                'see-details'          => '查看详情',
                'shopping-cart'        => '购物车',
                'subtotal'             => '小计',
                'view-cart'            => '查看购物车',
            ],

            'summary' => [
                'cart-summary'              => '购物车摘要',
                'delivery-charges-excl-tax' => '运费（不含税）',
                'delivery-charges-incl-tax' => '运费（含税）',
                'delivery-charges'          => '运费',
                'discount-amount'           => '折扣金额',
                'grand-total'               => '总计',
                'place-order'               => '下单',
                'proceed-to-checkout'       => '继续结账',
                'sub-total-excl-tax'        => '小计（不含税）',
                'sub-total-incl-tax'        => '小计（含税）',
                'sub-total'                 => '小计',
                'tax'                       => '税费',

                'estimate-shipping' => [
                    'country'        => '国家',
                    'info'           => '输入您的目的地以获取运费和税费估计。',
                    'postcode'       => '邮政编码',
                    'select-country' => '选择国家',
                    'select-state'   => '选择省份',
                    'state'          => '省份',
                    'title'          => '估计运费和税费',
                ],
            ],
        ],

        'onepage' => [
            'address' => [
                'add-new'                => '添加新地址',
                'add-new-address'        => '添加新地址',
                'back'                   => '返回',
                'billing-address'        => '账单地址',
                'check-billing-address'  => '缺少账单地址。',
                'check-shipping-address' => '缺少送货地址。',
                'city'                   => '城市',
                'company-name'           => '公司名称',
                'confirm'                => '确认',
                'country'                => '国家',
                'email'                  => '电子邮件',
                'first-name'             => '名',
                'last-name'              => '姓',
                'postcode'               => '邮政编码',
                'proceed'                => '继续',
                'same-as-billing'        => '是否使用相同地址进行配送？',
                'save'                   => '保存',
                'save-address'           => '保存到地址簿',
                'select-country'         => '选择国家',
                'select-state'           => '选择省份',
                'shipping-address'       => '送货地址',
                'state'                  => '省份',
                'street-address'         => '街道地址',
                'telephone'              => '电话',
                'title'                  => '地址',
            ],

            'index' => [
                'checkout' => '结帐',
                'home'     => '主页',
            ],

            'payment' => [
                'payment-method' => '付款方法',
            ],

            'shipping' => [
                'shipping-method' => '送货方式',
            ],

            'summary' => [
                'cart-summary'              => '购物车摘要',
                'delivery-charges-excl-tax' => '运费（不含税）',
                'delivery-charges-incl-tax' => '运费（含税）',
                'delivery-charges'          => '运费',
                'discount-amount'           => '折扣金额',
                'excl-tax'                  => '不含税：',
                'grand-total'               => '总计',
                'place-order'               => '下单',
                'price_&_qty'               => ':price × :qty',
                'processing'                => '处理中',
                'sub-total-excl-tax'        => '小计（不含税）',
                'sub-total-incl-tax'        => '小计（含税）',
                'sub-total'                 => '小计',
                'tax'                       => '税费',
            ],
        ],

        'coupon' => [
            'already-applied' => '优惠券码已应用。',
            'applied'         => '已应用优惠券',
            'apply'           => '应用优惠券',
            'apply-issue'     => '无法应用优惠券码。',
            'button-title'    => '应用',
            'code'            => '优惠券码',
            'discount'        => '优惠券折扣',
            'enter-your-code' => '输入您的代码',
            'error'           => '出了点问题',
            'invalid'         => '优惠券码无效。',
            'remove'          => '删除优惠券',
            'subtotal'        => '小计',
            'success-apply'   => '优惠券码已成功应用。',
        ],

        'login' => [
            'email'    => '电子邮件',
            'password' => '密码',
            'title'    => '登录',
        ],
    ],

    'home' => [
        'contact' => [
            'about'         => '给我们留言，我们会尽快回复您',
            'desc'          => '您有什么问题？',
            'describe-here' => '在这里描述',
            'email'         => '电子邮件',
            'message'       => '留言',
            'name'          => '姓名',
            'phone-number'  => '电话号码',
            'submit'        => '提交',
            'title'         => '联系我们',
        ],

        'index' => [
            'offer'               => '首次下单立减40%，现在开始购物',
            'resend-verify-email' => '重新发送验证电子邮件',
            'verify-email'        => '验证您的电子邮件帐户',
        ],

        'thanks-for-contact' => '感谢您与我们联系，提供您的意见和问题。我们会尽快回复您。',
    ],

    'partials' => [
        'pagination' => [
            'pagination-showing' => '显示 :total 个条目中的 :firstItem 到 :lastItem',
        ],
    ],

    'errors' => [
        'go-to-home' => '转到主页',

        '404' => [
            'description' => '糟糕！您正在寻找的页面正在度假中。看来我们找不到您要搜索的内容。',
            'title'       => '404 页面未找到',
        ],

        '401' => [
            'description' => '糟糕！看来您无权访问此页面。似乎您缺少必要的凭据。',
            'title'       => '401 未经授权',
        ],

        '403' => [
            'description' => '糟糕！这个页面是禁止访问的。您似乎没有查看此内容所需的权限。',
            'title'       => '403 禁止访问',
        ],

        '500' => [
            'description' => '糟糕！出了些问题。看来我们在加载您正在寻找的页面时遇到了麻烦。',
            'title'       => '500 内部服务器错误',
        ],

        '503' => [
            'description' => '糟糕！这个页面不可用。请稍后再试。',
            'title'       => '503 服务不可用',
        ],
    ],

    'layouts' => [
        'address'               => '地址',
        'downloadable-products' => '可下载产品',
        'my-account'            => '我的帐户',
        'orders'                => '订单',
        'profile'               => '个人资料',
        'reviews'               => '评论',
        'wishlist'              => '愿望清单',
    ],

    'subscription' => [
        'already'             => '您已经订阅了我们的新闻通讯。',
        'subscribe-success'   => '您已成功订阅我们的新闻通讯。',
        'unsubscribe-success' => '您已成功取消订阅我们的新闻通讯。',
    ],

    'emails' => [
        'dear'   => '亲爱的 :customer_name',
        'thanks' => '如果您需要任何帮助，请联系我们：<a href=":link" style=":style">:email</a>。<br/>谢谢！',

        'customers' => [
            'registration' => [
                'credentials-description' => '您的账户已创建。您的账户详细信息如下：',
                'description'             => '您的帐户已成功创建，您可以使用电子邮件地址和密码凭据登录。登录后，您将能够访问其他服务，包括查看过去的订单、愿望清单和编辑您的帐户信息。',
                'greeting'                => '欢迎并感谢您注册我们的网站！',
                'password'                => '用户名/电子邮件',
                'sign-in'                 => '登录',
                'subject'                 => '新客户注册',
                'username-email'          => '密码',
            ],

            'forgot-password' => [
                'description'    => '您收到此电子邮件，因为我们收到了有关您帐户的密码重置请求。',
                'greeting'       => '忘记密码！',
                'reset-password' => '重置密码',
                'subject'        => '重置密码电子邮件',
            ],

            'update-password' => [
                'description' => '您正在收到此电子邮件，因为您已更新您的密码。',
                'greeting'    => '密码已更新！',
                'subject'     => '密码已更新',
            ],

            'verification' => [
                'description'  => '请单击下面的按钮以验证您的电子邮件地址。',
                'greeting'     => '欢迎！',
                'subject'      => '帐户验证电子邮件',
                'verify-email' => '验证电子邮件地址',
            ],

            'commented' => [
                'description' => '备注是 - :note',
                'subject'     => '新增评论',
            ],

            'subscribed' => [
                'description' => '祝贺您加入我们的新闻通讯社区！我们很高兴您加入我们，随时向您提供最新的新闻、趋势和独家优惠。',
                'greeting'    => '欢迎加入我们的新闻通讯！',
                'subject'     => '您！订阅我们的新闻通讯',
                'unsubscribe' => '取消订阅',
            ],
        ],

        'contact-us' => [
            'contact-from'    => '通过网站联系表格',
            'reply-to-mail'   => '请回复此电子邮件。',
            'reach-via-phone' => '或者，您可以通过电话联系我们：',
            'inquiry-from'    => '来自的查询',
            'to'              => '联系',
        ],

        'orders' => [
            'created' => [
                'greeting' => '感谢您的订单 :order_id，下单时间 :created_at',
                'subject'  => '新订单确认',
                'summary'  => '订单摘要',
                'title'    => '订单确认！',
            ],

            'invoiced' => [
                'greeting' => '您的发票号 #:invoice_id，订单号 :order_id，下单时间 :created_at',
                'subject'  => '新发票确认',
                'summary'  => '发票摘要',
                'title'    => '发票确认！',
            ],

            'shipped' => [
                'greeting' => '您的订单 :order_id，下单时间 :created_at，已发货',
                'subject'  => '新发货确认',
                'summary'  => '发货摘要',
                'title'    => '订单已发货！',
            ],

            'refunded' => [
                'greeting' => '已启动订单 :order_id，下单时间 :created_at 的退款',
                'subject'  => '新退款确认',
                'summary'  => '退款摘要',
                'title'    => '订单已退款！',
            ],

            'canceled' => [
                'greeting' => '您的订单 :order_id，下单时间 :created_at，已取消',
                'subject'  => '新订单已取消',
                'summary'  => '订单摘要',
                'title'    => '订单已取消！',
            ],

            'commented' => [
                'subject' => '新增评论',
                'title'   => '新增评论已添加到您的订单 :order_id，下单时间 :created_at',
            ],

            'billing-address'            => '账单地址',
            'carrier'                    => '承运人',
            'contact'                    => '联系人',
            'discount'                   => '折扣',
            'excl-tax'                   => '不含税：',
            'grand-total'                => '总计',
            'name'                       => '姓名',
            'payment'                    => '支付',
            'price'                      => '价格',
            'qty'                        => '数量',
            'shipping-address'           => '送货地址',
            'shipping-handling-excl-tax' => '运费（不含税）',
            'shipping-handling-incl-tax' => '运费（含税）',
            'shipping-handling'          => '运费',
            'shipping'                   => '运输',
            'sku'                        => 'SKU',
            'subtotal-excl-tax'          => '小计（不含税）',
            'subtotal-incl-tax'          => '小计（含税）',
            'subtotal'                   => '小计',
            'tax'                        => '税',
            'tracking-number'            => '跟踪号码：:tracking_number',
        ],
    ],
];
