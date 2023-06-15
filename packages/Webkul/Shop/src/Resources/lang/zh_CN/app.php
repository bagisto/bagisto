<?php

return [
    'invalid_vat_format' => '给定的增值税ID格式错误',
    'security-warning' => '发现可疑活动!!!',
    'nothing-to-delete' => '没有什么可删除的',

    'layouts' => [
        'my-account' => '我的帐户',
        'profile' => '简介',
        'address' => '地址',
        'reviews' => '评论',
        'wishlist' => '愿望清单',
        'orders' => '订单',
        'downloadable-products' => '可下载产品'
    ],

    'common' => [
        'error' => '发生错误。请稍后再试.',
        'image-upload-limit' => '图片最大上传大小为2MB',
        'no-result-found' => '我们找不到任何记录.',
    ],

    'home' => [
        'page-title' => config('app.name') . ' - 主页',
        'featured-products' => '特色产品',
        'new-products' => '新产品',
        'verify-email' => '验证您的电子邮件帐户',
        'resend-verify-email' => '重新发送验证电子邮件'
    ],

    'header' => [
        'title' => '帐户',
        'dropdown-text' => '管理购物车, 订单&愿望清单',
        'sign-in' => '登入',
        'sign-up' => '注册',
        'account' => '帐户',
        'cart' => '购物车',
        'profile' => '简介',
        'wishlist' => '愿望清单',
        'cart' => '购物车',
        'logout' => '登出',
        'search-text' => '在这里搜索产品'
    ],

    'minicart' => [
        'view-cart' => '查看购物车',
        'checkout' => '付款',
        'cart' => '购物车',
        'zero' => '0'
    ],

    'footer' => [
        'subscribe-newsletter' => '邮件订阅',
        'subscribe' => '订阅',
        'locale' => '多语言',
        'currency' => '货币',
    ],

    'subscription' => [
        'unsubscribe' => '取消订阅',
        'subscribe' => '订阅',
        'subscribed' => '您现在订阅了电子邮件订阅.',
        'not-subscribed' => '您无法订阅邮件订阅, 请稍后再试.',
        'already' => '您已经订阅了我们的订阅列表.',
        'unsubscribed' => '您已取消订阅邮件订阅.',
        'already-unsub' => '您已经退订.',
        'not-subscribed' => '错误! 目前无法发送邮件, 请稍后再试.'
    ],

    'search' => [
        'no-results' => '未找到结果',
        'page-title' => config('app.name') . ' - 搜索',
        'found-results' => '找到搜索结果',
        'found-result' => '找到搜索结果',
        'analysed-keywords' => '已分析关键字',
        'image-search-option' => '图片搜索选项'
    ],

    'reviews' => [
        'title' => '标题',
        'add-review-page-title' => '添加评论',
        'write-review' => '写评论',
        'review-title' => '给你的评论一个标题',
        'product-review-page-title' => '产品审核',
        'rating-reviews' => '评级&评论',
        'submit' => '提交',
        'delete-all' => '所有评论已经被成功删除',
        'ratingreviews' => ':rating 评级 & :review 评论',
        'star' => '星级',
        'percentage' => ':percentage %',
        'id-star' => '星',
        'name' => '名称',
        'login-to-review' => '请先登录以查看产品',
    ],

    'customer' => [
        'compare'           => [
            'text'                  => '比较',
            'compare_similar_items' => '比较类似项目',
            'add-tooltip'           => '将产品添加到比较列表',
            'added'                 => '项目成功添加到比较列表',
            'already_added'         => '项目已添加到比较列表',
            'removed'               => '项目成功从比较列表中删除',
            'removed-all'           => '从比较列表中删除所有成功的项目',
            'confirm-remove-all'    => '您确定要删除所有比较项目吗?',
            'empty-text'            => "您的比较列表中没有任何项目",
            'product_image'         => '产品图片',
            'actions'               => '行动',
        ],

        'signup-text' => [
            'account_exists' => '已有账号',
            'title' => '登入'
        ],

        'signup-form' => [
            'page-title' => '创建新的客户帐户',
            'title' => '注册',
            'firstname' => '名',
            'lastname' => '姓',
            'email' => '电子邮件',
            'password' => '密码',
            'confirm_pass' => '确认密码',
            'button_title' => '注册',
            'agree' => '同意',
            'terms' => '条款',
            'conditions' => '条件',
            'using' => '通过使用本网站',
            'agreement' => '协议',
            'subscribe-to-newsletter' => '订阅邮件列表',
            'success' => '帐户已创建成功.',
            'success-verify' => '帐户已创建成功, 已发送电子邮件进行验证.',
            'success-verify-email-unsent' => '帐户已成功, 但验证电子邮件未发送.',
            'failed' => '错误! 无法创建您的帐户, 请稍后再试.',
            'already-verified' => '您的帐户已通过验证或请尝试再次发送新的验证电子邮件.',
            'verification-not-sent' => '错误! 发送验证邮件时出现问题, 请稍后再试.',
            'verification-sent' => '已发送验证电子邮件',
            'verified' => '您的帐号已通过验证, 请立即尝试登录.',
            'verify-failed' => '我们无法验证您的邮件帐户.',
            'dont-have-account' => '您没有我们的账户.',
            'customer-registration' => '客户注册成功'
        ],

        'login-text' => [
            'no_account' => '没有帐号',
            'title' => '注册',
        ],

        'login-form' => [
            'page-title' => '客户登录',
            'title' => '登入',
            'email' => '电子邮件',
            'password' => '密码',
            'forgot_pass' => '忘记密码?',
            'button_title' => '登入',
            'remember' => '登入',
            'footer' => '© 版权 :year 网络库 软件, 版权所有',
            'invalid-creds' => '请检查您的凭据并重试.',
            'verify-first' => '首先验证您的电子邮件帐户.',
            'not-activated' => '您的激活需要管理员批准',
            'resend-verification' => '重新发送验证邮件',
            'show-password'       => '显示密码',
        ],

        'forgot-password' => [
            'title' => '恢复密码',
            'email' => '电子邮件',
            'submit' => '发送密码重置电子邮件',
            'page_title' => '忘记密码了吗?'
        ],

        'reset-password' => [
            'title' => '重置密码',
            'email' => '注册的电子邮件',
            'password' => '密码',
            'confirm-password' => '确认密码',
            'back-link-title' => '回到登入',
            'submit-btn-title' => '重置密码'
        ],

        'account' => [
            'dashboard' => '编辑 简介',
            'menu' => '菜单',

            'general' => [
                'no' => '不',
                'yes' => '是的',
            ],

            'profile' => [
                'index' => [
                    'page-title' => '简介',
                    'title' => '简介',
                    'edit' => '编辑',
                ],

                'edit-success' => '简介已更新成功.',
                'edit-fail' => '错误! 简介无法更新, 请稍后重试.',
                'unmatch' => '旧密码不匹配.',

                'fname' => '名',
                'lname' => '姓',
                'gender' => '性别',
                'other' => '其他',
                'male' => '男性',
                'female' => '女性',
                'dob' => '生日日期',
                'phone' => '电话',
                'email' => '电子邮件',
                'opassword' => '旧密码',
                'password' => '密码',
                'cpassword' => '确认密码',
                'submit' => '更新简介',

                'edit-profile' => [
                    'title' => '编辑简介',
                    'page-title' => '编辑简介'
                ]
            ],

            'address' => [
                'index' => [
                    'page-title' => '地址',
                    'title' => '地址',
                    'add' => '添加地址',
                    'edit' => '编辑',
                    'empty' => '您在这里没有任何保存的地址, 请尝试通过单击添加按钮创建它.',
                    'create' => '创建地址',
                    'delete' => '删除',
                    'make-default' => '使默认',
                    'default' => '默认',
                    'contact' => '联系',
                    'confirm-delete' => '你真的要删除这个地址吗?',
                    'default-delete' => '默认地址不能更改.',
                    'enter-password' => '输入你的密码.',
                ],

                'create' => [
                    'page-title' => '添加地址',
                    'company_name' => '公司名',
                    'first_name' => '名',
                    'last_name' => '姓',
                    'vat_id' => '增值税号',
                    'vat_help_note' => '[注意: 使用带增值税号的国家代码. 例如. INV01234567891]',
                    'title' => '添加地址',
                    'street-address' => '街道地址',
                    'country' => '国家',
                    'state' => '州',
                    'select-state' => '选择地区、州或省',
                    'city' => '城市',
                    'postcode' => '邮政编码',
                    'phone' => '电话',
                    'submit' => '保存地址',
                    'success' => '地址已经被成功添加.',
                    'error' => '地址不能被添加.'
                ],

                'edit' => [
                    'page-title' => '编辑地址',
                    'company_name' => '公司名',
                    'first_name' => '名',
                    'last_name' => '姓',
                    'vat_id' => '增值税号',
                    'title' => '编辑地址',
                    'street-address' => '街道地址',
                    'submit' => '保存地址',
                    'success' => '地址已更新成功.',
                ],

                'delete' => [
                    'success' => '地址成功删除',
                    'failure' => '地址无法删除',
                    'wrong-password' => '错误密码 !'
                ],

                'default-address' => '默认地址',
            ],

            'order' => [
                'index' => [
                    'page-title' => '订单',
                    'title' => '订单',
                    'order_id' => '订单ID',
                    'date' => '日期',
                    'status' => '状态',
                    'total' => '总计',
                    'order_number' => '订单号',
                    'processing' => '处理中',
                    'completed' => '已完成',
                    'canceled' => '取消',
                    'closed' => '关闭',
                    'pending' => '待批准',
                    'pending-payment' => '待批准支付',
                    'fraud' => '欺诈'
                ],

                'view' => [
                    'page-tile' => '订单 #:order_id',
                    'info' => '信息',
                    'placed-on' => '放在',
                    'products-ordered' => '产品已订购',
                    'invoices' => '发票',
                    'shipments' => '出货量',
                    'SKU' => 'SKU',
                    'product-name' => '名称',
                    'qty' => '数量',
                    'item-status' => '项目状态',
                    'item-ordered' => '已订购 (:qty_ordered)',
                    'item-invoice' => '已开票 (:qty_invoiced)',
                    'item-shipped' => '已快递 (:qty_shipped)',
                    'item-canceled' => '取消 (:qty_canceled)',
                    'item-refunded' => '已退款 (:qty_refunded)',
                    'price' => '价格',
                    'total' => '总计',
                    'subtotal' => '小计',
                    'shipping-handling' => '快递&处理',
                    'tax' => '税',
                    'discount' => '折扣',
                    'tax-percent' => '税百分比',
                    'tax-amount' => '税金额',
                    'discount-amount' => '折扣金额',
                    'grand-total' => '累计',
                    'total-paid' => '总计已支付',
                    'total-refunded' => '总计已退款',
                    'total-due' => '总计到期的',
                    'shipping-address' => '快递地址',
                    'billing-address' => '账单地址',
                    'shipping-method' => '快递方式',
                    'payment-method' => '支付方式',
                    'individual-invoice' => '发票 #:invoice_id',
                    'individual-shipment' => '快递 #:shipment_id',
                    'print' => '打印',
                    'invoice-id' => '发票Id',
                    'order-id' => '订单Id',
                    'order-date' => '订单日期',
                    'invoice-date' => '发票日期',
                    'payment-terms' => '支付协议',
                    'bill-to' => '记账到',
                    'ship-to' => '运输到',
                    'contact' => '联系',
                    'refunds' => '退款',
                    'individual-refund' => '退款 #:refund_id',
                    'adjustment-refund' => '调整退款',
                    'adjustment-fee' => '调整费',
                    'cancel-btn-title' => '取消',
                    'tracking-number' => '追踪号码',
                    'cancel-confirm-msg' => '您确定要取消此订单吗?'
                ]
            ],

            'wishlist' => [
                'page-title' => '愿望清单',
                'title' => '愿望清单',
                'deleteall' => '删除全部',
                'confirm-delete-all'   => '您确定要删除所有愿望清单吗?',
                'moveall' => '将所有产品移至购物车',
                'move-to-cart' => '移至购物车',
                'error' => '由于未知问题, 无法将产品添加到愿望清单, 请稍后再查看',
                'add' => '项目成功添加到愿望清单',
                'remove' => '项目成功从愿望清单中删除',
                'add-wishlist-text'     => '将产品添加到愿望清单',
                'remove-wishlist-text'  => '从愿望清单中删除产品',
                'moved' => '项目成功移动到购物车',
                'option-missing' => '缺少产品选项, 因此无法将项目移至愿望清单.',
                'move-error' => '商品无法移至愿望清单, 请稍后再试',
                'success' => '项目成功添加到愿望清单',
                'failure' => '项目无法添加到愿望清单, 请稍后再试',
                'already' => '您的愿望清单中已经存在的项目',
                'removed' => '项目成功从愿望清单中删除',
                'remove-fail' => '项目无法从愿望清单中删除, 请稍后再试',
                'empty' => '您的愿望清单中没有任何商品',
                'remove-all-success' => '您的愿望清单中的所有项目已被删除',
                'save'                 => '保存',
                'share'                => '分享',
                'share-wishlist'       => '分享愿望清单',
                'wishlist-sharing'     => '愿望清单分享',
                'shared-link'          => '已共享链接',
                'copy'                 => '复制',
                'visibility'           => '可见度',
                'public'               => '公开的',
                'private'              => '私有的',
                'enable'               => '启用',
                'disable'              => '禁用',
                'customer-name'        => ':name 的共享愿望清单',
                'enable-wishlist-info' => '启用愿望清单共享以获取链接.',
                'update-message'       => '已成功更新共享心愿单设置',
            ],

            'downloadable_products' => [
                'title' => '可下载产品',
                'order-id' => '订单ID',
                'date' => '日期',
                'name' => '标题',
                'status' => '状态',
                'pending' => '待批准',
                'available' => '可用的',
                'expired' => '已到期',
                'remaining-downloads' => '剩余下载',
                'unlimited' => '无限',
                'download-error' => '下载链接已过期.',
                'payment-error' => '此下载尚未付款.'
            ],

            'review' => [
                'index' => [
                    'title' => '评论',
                    'page-title' => '评论',

                ],

                'view' => [
                    'page-tile' => '评论 #:id',
                ],

                'delete' => [
                    'confirmation-message' => '您确定要删除此评论吗?',
                ],

                'delete-all' => [
                    'title' => '全部删除',
                    'confirmation-message' => '您确定要删除所有评论吗?',
                ],
            ]
        ]
    ],

    'products' => [
        'layered-nav-title' => '购物方式',
        'price-label' => '低至',
        'remove-filter-link-title' => '全部清除',
        'filter-to' => '到',
        'sort-by' => '排序方式',
        'from-a-z' => '从A到Z',
        'from-z-a' => '从Z到A',
        'newest-first' => '新的优先',
        'oldest-first' => '旧的优先',
        'cheapest-first' => '便宜优先',
        'expensive-first' => '贵的优先',
        'show' => '展示',
        'pager-info' => '显示 :showing 的 :total 项目',
        'description' => '描述',
        'specification' => '规格',
        'total-reviews' => ':total 评论',
        'total-rating' => ':total_rating 评级 & :total_reviews 评论',
        'by' => '由 :name',
        'up-sell-title' => '我们找到了您可能喜欢的其他产品!',
        'related-product-title' => '相关产品',
        'cross-sell-title' => '更多选择',
        'reviews-title' => '评级&评论',
        'write-review-btn' => '撰写评论',
        'choose-option' => '选择一个选项',
        'sale' => '销售',
        'new' => '新',
        'empty' => '此类别中没有可用的产品',
        'add-to-cart' => '添加到购物车',
        'book-now' => '现在预订',
        'buy-now' => '立即购买',
        'whoops' => '哎呀!',
        'quantity' => '数量',
        'in-stock' => '有现货',
        'out-of-stock' => '缺货',
        'view-all' => '查看全部',
        'select-above-options' => '请先选择以上选项.',
        'less-quantity' => '数量不能小于一个.',
        'samples' => '样品',
        'links' => '链接',
        'sample' => '样本',
        'name' => '名称',
        'qty' => '数量',
        'starting-at' => '开始于',
        'customize-options' => '自定义选项',
        'choose-selection' => '选择一个选项',
        'your-customization' => '您的定制',
        'total-amount' => '总计数量',
        'none' => '没有任何',
        'available-for-order' => '可订购',
        'settings' => '设置',
        'compare_options' => '比较选项',
        'wishlist-options' => '愿望清单选项',
        'offers' => '购买 :qty 为了 :price 每个和保存 :discount%',
        'tax-inclusive' => '包括所有税费'
    ],

    'buynow' => [
        'no-options' => '购买此产品前请选择选项.'
    ],

    'checkout' => [
        'cart' => [
            'integrity' => [
                'missing_fields' => '此产品缺少一些必填字段.',
                'missing_options' => '此产品缺少选项.',
                'missing_links' => '此产品缺少可下载链接.',
                'qty_missing' => '至少一种产品的数量应超过 1 个.',
                'qty_impossible' => '不能将这些产品中的一种以上添加到购物车.',
                'select_hourly_duration' => '選擇一小時的時隙持續時間。',
            ],
            'create-error' => '制作购物车实例时遇到一些问题.',
            'title' => '购物车',
            'empty' => '你的购物车是空的',
            'update-cart' => '更新购物车',
            'continue-shopping' => '继续购物',
            'proceed-to-checkout' => '进行结算',
            'remove' => '移除',
            'remove-link' => '删除链接',
            'remove-all-items'          => '删除所有项目',
            'confirm-action'           => '确认此操作？',
            'move-to-wishlist' => '移动到愿望清单',
            'move-to-wishlist-success' => '项目已成功移至愿望清单.',
            'move-to-wishlist-error' => '无法将商品移至愿望清单, 请稍后再试.',
            'add-config-warning' => '加入购物车前请选择选项.',
            'quantity' => [
                'quantity' => '数量',
                'success' => '购物车项目成功更新.',
                'illegal' => '数量不能小于一.',
                'inventory_warning' => '请求的数量不可用, 请稍后再试.',
                'error' => '暂时无法更新项目, 请稍后再试.'
            ],

            'item' => [
                'error_remove' => '没有要从购物车中删除的物品.',
                'success' => '商品已成功添加到购物车.',
                'success-remove' => '物品已成功从购物车中移除.',
                'success-all-remove' => '所有商品均已成功从购物车中移除。',
                'error-add' => '商品无法加入购物车, 请稍后再试.',
                'inactive' => '商品处于非活动状态并已从购物车中移除.',
                'inactive-add' => '不活跃商品无法添加到购物车.',
            ],
            'quantity-error' => '请求的数量不可用.',
            'cart-subtotal' => '购物车小计',
            'cart-remove-action' => '你真的想这样做吗?',
            'partial-cart-update' => '仅更新了部分产品',
            'link-missing' => '',
            'event' => [
                'expired' => '此活动已过期.'
            ],
            'minimum-order-message'     => '最低订购金额为 :amount',
            'suspended-account-message' => '您的帐户已被暂停.',
            'inactive-account-message' =>  '您的帐户一直处于非活动状态',
            'check-shipping-address'    => '请检查收货地址.',
            'check-billing-address'     => '请检查帐单地址.',
            'specify-shipping-method'   => '请指定送货方式.',
            'specify-payment-method'    => '请指定付款方式.',
            'rule-applied'              => '已应用购物车规则',
            'paypal-payment-canceled'   => 'Paypal 付款已被取消。',
        ],

        'onepage' => [
            'title' => '付款',
            'information' => '信息',
            'shipping' => '快递',
            'payment' => '支付',
            'complete' => '完成',
            'review' => '评论',
            'billing-address' => '账单地址',
            'sign-in' => '登入',
            'company-name' => '公司名',
            'first-name' => '名',
            'last-name' => '姓',
            'email' => '电子邮件',
            'address1' => '街道地址',
            'city' => '城市',
            'state' => '州',
            'select-state' => '选择地区、州或省',
            'postcode' => '邮政编码',
            'phone' => '电话',
            'country' => '国家',
            'order-summary' => '订单摘要',
            'shipping-address' => '快递地址',
            'use_for_shipping' => '运输到这个地址',
            'continue' => '继续',
            'shipping-method' => '选择快递方式',
            'payment-methods' => '选择支付方式',
            'payment-method' => '支付方式',
            'summary' => '订单摘要',
            'price' => '价格',
            'quantity' => '数量',
            'billing-address' => '账单地址',
            'shipping-address' => '快递地址',
            'contact' => '联系',
            'place-order' => '下订单',
            'new-address' => '添加新的地址',
            'save_as_address' => '保存这个地址',
            'apply-coupon' => '申请优惠券',
            'amt-payable' => '数量应付',
            'got' => '拿到',
            'free' => '免费',
            'coupon-used' => '使用的优惠券',
            'applied' => '应用',
            'back' => '后退',
            'cash-desc' => '货到付款',
            'money-desc' => '汇款',
            'paypal-desc' => 'Paypal标准',
            'free-desc' => '这是免费送货',
            'flat-desc' => '这是统一费率',
            'password' => '密码',
            'login-exist-message' => '您已经有我们的帐户, 请登录或以访客身份继续.',
            'enter-coupon-code' => '输入优惠码'
        ],

        'total' => [
            'order-summary' => '订单摘要',
            'sub-total' => '项目',
            'grand-total' => '累计',
            'delivery-charges' => '送货费',
            'tax' => '税',
            'discount' => '折扣',
            'price' => '价格',
            'disc-amount' => '数量打折',
            'new-grand-total' => '新累计',
            'coupon' => '优惠券',
            'coupon-applied' => '应用的优惠券',
            'remove-coupon' => '移除优惠券',
            'cannot-apply-coupon' => '不能应用优惠券',
            'invalid-coupon' => '优惠券代码无效.',
            'success-coupon' => '优惠券代码已应用成功.',
            'coupon-apply-issue' => '优惠券代码不能被应用.',
            'coupon-already-applied' => '優惠券代碼已應用',
        ],

        'success' => [
            'title' => '已成功下单',
            'thanks' => '谢谢您的订单!',
            'order-id-info' => '您的订单编号是 #:order_id',
            'info' => '我们将通过电子邮件向您发送您的订单详细信息和跟踪信息.'
        ]
    ],

    'mail' => [
        'order' => [
            'subject' => '新订单确认',
            'heading' => '订单确认!',
            'dear' => '亲爱的 :customer_name',
            'dear-admin' => '亲爱的 :admin_name',
            'greeting' => '感谢您的订单 :order_id 创建在 :created_at',
            'greeting-admin' => '订单ID :order_id 创建在 :created_at',
            'summary' => '订单摘要',
            'shipping-address' => '快递地址',
            'billing-address' => '账单地址',
            'contact' => '联系',
            'shipping' => '快递方式',
            'payment' => '支付方式',
            'price' => '价格',
            'quantity' => '数量',
            'subtotal' => '小计',
            'shipping-handling' => '快递 & 处理中',
            'tax' => '税',
            'discount' => '折扣',
            'grand-total' => '累计',
            'final-summary' => '感谢您对我们的商店表现出兴趣, 我们将在发货后向您发送跟踪号',
            'help' => '如果您需要任何帮助, 请联系我们 :support_email',
            'thanks' => '谢谢!',

            'comment' => [
                'subject' => '新评论添加到您的订单 #:order_id',
                'dear' => '亲爱的 :customer_name',
                'final-summary' => '感谢您对我们的商店表现出兴趣',
                'help' => '如果您需要任何帮助, 请联系我们 :support_email',
                'thanks' => '谢谢!',
            ],

            'cancel' => [
                'subject' => '订单取消确认',
                'heading' => '订单已取消',
                'dear' => '亲爱的 :customer_name',
                'greeting' => '您在 :created_at 上的订单, ID为 :order_id 的订单已被取消.',
                'summary' => '订单摘要',
                'shipping-address' => '快递地址',
                'billing-address' => '账单地址',
                'contact' => '联系',
                'shipping' => '快递方式',
                'payment' => '支付方式',
                'subtotal' => '小计',
                'shipping-handling' => '快递 & 处理中',
                'tax' => '税',
                'discount' => '折扣',
                'grand-total' => '累计',
                'final-summary' => '感谢您对我们的商店表现出兴趣',
                'help' => '如果您需要任何帮助, 请联系我们 :support_email',
                'thanks' => '谢谢!',
            ]
        ],

        'invoice' => [
            'heading' => '您的发票 #:invoice_id 订单 #:order_id',
            'subject' => '您的订单发票 #:order_id',
            'summary' => '发票摘要',
            'reminder' => [
                'subject'                                          => '发票提醒',
                'your-invoice-is-overdue'                          => '您的发票 :invoice 在 :time 已逾期.',
                'please-make-your-payment-as-soon-as-possible'     => '请尽快付款.',
                'if-you-ve-already-paid-just-disregard-this-email' => '如果您已经付款，请忽略此电子邮件.',
            ],
        ],

        'shipment' => [
            'heading' => '已为订单 #:order_id 生成快递单 #:shipment_id',
            'inventory-heading' => '新货件 #:shipment_id 已为订单生成 #:order_id',
            'subject' => '已为您的订单发货 #:order_id',
            'inventory-subject' => '已为订单生成新快递单 #:order_id',
            'summary' => '出货概要',
            'carrier' => '承运者',
            'tracking-number' => '追踪号码',
            'greeting' => '一个订单 :order_id 已经被创建在 :created_at',
        ],

        'refund' => [
            'heading' => '为您的订单 #:order_id, 退款 #:refund_id',
            'subject' => '为您的订单退款 #:order_id',
            'summary' => '退款的概要',
            'adjustment-refund' => '调整退款',
            'adjustment-fee' => '调整费用'
        ],

        'forget-password' => [
            'subject' => '客户重置密码',
            'dear' => '亲爱的 :name',
            'info' => '您收到这封电子邮件是因为我们收到了您帐户的密码重置请求',
            'reset-password' => '重置密码',
            'final-summary' => '如果您未请求重置密码, 则无需进一步操作',
            'thanks' => '谢谢!'
        ],

        'update-password' => [
            'subject' => '密码已更新',
            'dear' => '亲爱的 :name',
            'info' => '您收到这封电子邮件是因为您更新了密码.',
            'thanks' => '谢谢!'
        ],

        'customer' => [
            'new' => [
                'dear' => '亲爱的 :customer_name',
                'username-email' => '用户名/电子邮件',
                'subject' => '新客户注册',
                'password' => '密码',
                'summary' => '您的帐号已经建立.
                您的帐户详细信息如下: ',
                'thanks' => '谢谢!',
            ],

            'registration' => [
                'subject' => '新客户注册',
                'customer-registration' => '客户成功注册',
                'dear' => '亲爱的 :customer_name',
                'dear-admin' => '亲爱的 :admin_name',
                'greeting' => '欢迎并感谢您注册我们!',
                'greeting-admin' => '您有一个新客户注册.',
                'summary' => '您的帐户现已成功创建, 您可以使用您的电子邮件地址和密码凭据登录。 登录后, 您将能够访问其他服务, 包括查看过去的订单、愿望清单和编辑您的帐户信息.',
                'thanks' => '谢谢!',
            ],

            'verification' => [
                'heading' => config('app.name') . ' - 电子邮件确认',
                'subject' => '确认邮件',
                'verify' => '验证你的帐户',
                'summary' => '这是用于验证您输入的电子邮件地址是否属于您的邮件。
                请点击下方的验证您的帐户按钮以验证您的帐户.'
            ],

            'subscription' => [
                'subject' => '订阅电子邮件',
                'greeting' => '欢迎来到' . config('app.name') . ' - 电子邮件订阅',
                'unsubscribe' => '取消订阅',
                'summary' => '谢谢把我放进你的收件箱. 你已经有一段时间没有阅读了' . config('app.name') . ' 电子邮件, 我们不想让您的收件箱不堪重负。 如果您仍然不想收到
                最新的电子邮件营销新闻, 然后确定点击下面的按钮.'
            ]
        ]
    ],

    'webkul' => [
        'copy-right' => '© 版权 :year 网络库 软件，保留所有权利',
    ],

    'response' => [
        'create-success' => ':name 已创建成功.',
        'update-success' => ':name 已更新成功.',
        'delete-success' => ':name 已删除成功.',
        'submit-success' => ':name 已提交成功.'
    ],
];
