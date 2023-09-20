<?php

return [
    'customers' => [
        'forgot-password' => [
            'title'                => '找回密码',
            'email'                => '电子邮件',
            'forgot-password-text' => '如果您忘记了密码，请通过输入您的电子邮件地址来找回密码。',
            'submit'               => '重置密码',
            'page-title'           => '忘记密码？',
            'back'                 => '返回登录？',
            'sign-in-button'       => '登录',
            'footer'               => '© 版权所有 2010 - 2022，Webkul Software（注册于印度）。保留所有权利。',
        ],

        'reset-password' => [
            'title'            => '重设密码',
            'email'            => '注册电子邮件',
            'password'         => '密码',
            'confirm-password' => '确认密码',
            'back-link-title'  => '返回登录',
            'submit-btn-title' => '重设密码',
            'footer'           => '© 版权 2010 - 2022，Webkul Software（注册于印度）。保留所有权利。',
        ],

        'login-form' => [
            'page-title'          => '用户登录',
            'form-login-text'     => '如果您已经有帐户，请使用您的电子邮件登录。',
            'show-password'       => '显示密码',
            'title'               => '登录',
            'email'               => '电子邮件',
            'password'            => '密码',
            'forgot-pass'         => '忘记密码？',
            'button-title'        => '登录',
            'new-customer'        => '新用户？',
            'create-your-account' => '创建您的帐户',
            'footer'              => '© 版权 2010 - 2022，Webkul Software（注册于印度）。保留所有权利。',
            'invalid-credentials' => '请检查您的凭据并重试。',
            'not-activated'       => '您的帐户请求已提交，等待管理员批准。',
            'verify-first'        => '请先验证您的电子邮件。',
        ],

        'signup-form' => [
            'page-title'                  => '成为用户',
            'form-signup-text'            => '如果您是我们商店的新用户，我们很高兴有您作为会员。',
            'sign-in-button'              => '登录',
            'first-name'                  => '名字',
            'last-name'                   => '姓氏',
            'email'                       => '电子邮件',
            'password'                    => '密码',
            'confirm-pass'                => '确认密码',
            'subscribe-to-newsletter'     => '订阅通讯',
            'button-title'                => '注册',
            'account-exists'              => '已经有账户？',
            'footer'                      => '© 版权所有 2010 - 2022，Webkul Software（注册于印度）。保留所有权利。',
            'success-verify'              => '成功创建账户，已发送验证电子邮件。',
            'success-verify-email-unsent' => '成功创建账户，但未发送验证电子邮件。',
            'success'                     => '成功创建账户。',
            'verified'                    => '您的账户已验证，请尝试登录。',
            'verify-failed'               => '我们无法验证您的电子邮件帐户。',
            'verification-not-sent'       => '错误！发送验证电子邮件时出现问题，请稍后再试。',
            'verification-sent'           => '已发送验证电子邮件',
        ],

        'account' => [
            'home'      => '主页',
            'profile'   => [
                'title'                   => '个人资料',
                'first-name'              => '名字',
                'last-name'               => '姓氏',
                'gender'                  => '性别',
                'dob'                     => '出生日期',
                'email'                   => '电子邮件',
                'delete-profile'          => '删除个人资料',
                'edit-profile'            => '编辑个人资料',
                'edit'                    => '编辑',
                'edit-success'            => '成功更新个人资料',
                'phone'                   => '电话',
                'current-password'        => '当前密码',
                'new-password'            => '新密码',
                'confirm-password'        => '确认密码',
                'delete-success'          => '成功删除客户',
                'wrong-password'          => '密码错误！',
                'delete-failed'           => '删除客户时出现错误。',
                'order-pending'           => '无法删除客户帐户，因为有未完成或处理中的订单。',
                'subscribe-to-newsletter' => '订阅通讯',
                'delete'                  => '删除',
                'enter-password'          => '输入您的密码',
                'male'                    => '男性',
                'female'                  => '女性',
                'other'                   => '其他',
                'save'                    => '保存',
                'unmatch'                 => '旧密码不匹配。',
            ],

            'addresses' => [
                'title'            => '地址',
                'edit'             => '编辑',
                'edit-address'     => '编辑地址',
                'delete'           => '删除',
                'set-as-default'   => '设为默认',
                'add-address'      => '添加地址',
                'company-name'     => '公司名称',
                'vat-id'           => '增值税号',
                'address-1'        => '地址1',
                'address-2'        => '地址2',
                'city'             => '城市',
                'state'            => '州/省',
                'select-country'   => '选择国家',
                'country'          => '国家',
                'default-address'  => '默认地址',
                'first-name'       => '名字',
                'last-name'        => '姓氏',
                'phone'            => '电话',
                'street-address'   => '街道地址',
                'post-code'        => '邮政编码',
                'empty-address'    => '您尚未将地址添加到您的帐户。',
                'create-success'   => '地址已成功添加。',
                'edit-success'     => '地址已成功更新。',
                'default-delete'   => '无法更改默认地址。',
                'delete-success'   => '地址已成功删除',
                'save'             => '保存',
                'security-warning' => '发现可疑活动！',
            ],

            'orders' => [
                'title'      => '订单',
                'order-id'   => '订单号',
                'order'      => '订单',
                'order-date' => '订单日期',
                'total'      => '总计',

                'status'        => [
                    'title' => '状态',

                    'options' => [
                        'processing'      => '处理中',
                        'completed'       => '已完成',
                        'canceled'        => '已取消',
                        'closed'          => '已关闭',
                        'pending'         => '待处理',
                        'pending-payment' => '待付款',
                        'fraud'           => '欺诈',
                    ],
                ],

                'action'      => '操作',
                'empty-order' => '您尚未订购任何产品',

                'view' => [
                    'title'              => '查看',
                    'page-title'         => '订单 #:order_id',
                    'total'              => '总计',
                    'shipping-address'   => '送货地址',
                    'billing-address'    => '账单地址',
                    'shipping-method'    => '送货方式',
                    'payment-method'     => '付款方式',
                    'cancel-btn-title'   => '取消',
                    'cancel-confirm-msg' => '您确定要取消此订单吗？',
                    'cancel-success'     => '您的订单已取消',
                    'cancel-error'       => '无法取消您的订单',

                    'information' => [
                        'info'              => '信息',
                        'placed-on'         => '下单时间',
                        'sku'               => 'SKU',
                        'product-name'      => '产品名称',
                        'price'             => '价格',
                        'item-status'       => '商品状态',
                        'subtotal'          => '小计',
                        'tax-percent'       => '税率',
                        'tax-amount'        => '税额',
                        'tax'               => '税金',
                        'grand-total'       => '总金额',
                        'item-ordered'      => '已订购 (:qty_ordered)',
                        'item-invoice'      => '已开票 (:qty_invoiced)',
                        'item-shipped'      => '已发货 (:qty_shipped)',
                        'item-canceled'     => '已取消 (:qty_canceled)',
                        'item-refunded'     => '已退款 (:qty_refunded)',
                        'shipping-handling' => '运输与处理',
                        'discount'          => '折扣',
                        'total-paid'        => '总付款',
                        'total-refunded'    => '总退款',
                        'total-due'         => '总待付款',
                    ],

                    'invoices'  => [
                        'invoices'           => '发票',
                        'individual-invoice' => '发票 #:invoice_id',
                        'sku'                => 'SKU',
                        'product-name'       => '产品名称',
                        'price'              => '价格',
                        'products-ordered'   => '订购的产品',
                        'qty'                => '数量',
                        'subtotal'           => '小计',
                        'tax-amount'         => '税额',
                        'grand-total'        => '总金额',
                        'shipping-handling'  => '运输与处理',
                        'discount'           => '折扣',
                        'tax'                => '税金',
                        'print'              => '打印',
                    ],

                    'shipments' => [
                        'shipments'           => '发货',
                        'tracking-number'     => '跟踪编号',
                        'individual-shipment' => '发货 #:shipment_id',
                        'sku'                 => 'SKU',
                        'product-name'        => '产品名称',
                        'qty'                 => '数量',
                        'subtotal'            => '小计',
                    ],

                    'refunds'  => [
                        'refunds'           => '退款',
                        'individual-refund' => '退款 #:refund_id',
                        'sku'               => 'SKU',
                        'product-name'      => '产品名称',
                        'price'             => '价格',
                        'qty'               => '数量',
                        'tax-amount'        => '税额',
                        'subtotal'          => '小计',
                        'grand-total'       => '总金额',
                        'no-result-found'   => '未找到任何记录',
                        'shipping-handling' => '运输与处理',
                        'discount'          => '折扣',
                        'tax'               => '税金',
                        'adjustment-refund' => '调整退款',
                        'adjustment-fee'    => '调整费用',
                    ],
                ],
            ],

            'reviews' => [
                'title'        => '评价',
                'empty-review' => '您还没有对任何商品进行评价',
            ],

            'downloadable-products' => [
                'name'                => '可下载商品',
                'orderId'             => '订单ID',
                'title'               => '名称',
                'date'                => '日期',
                'status'              => '状态',
                'remaining-downloads' => '剩余下载次数',
                'records-found'       => '找到记录',
                'empty-product'       => '您没有可下载的商品',
                'download-error'      => '下载链接已过期。',
                'payment-error'       => '该下载商品尚未付款。',
            ],

            'wishlist' => [
                'page-title'         => '愿望清单',
                'title'              => '愿望清单',
                'color'              => '颜色',
                'remove'             => '移除',
                'delete-all'         => '全部删除',
                'empty'              => '愿望清单中没有任何商品。',
                'move-to-cart'       => '移到购物车',
                'moved-success'      => '商品成功移入購物車',    
                'profile'            => '个人资料',
                'removed'            => '商品已成功从愿望清单中移除',
                'remove-fail'        => '商品无法从愿望清单中移除',
                'moved'              => '商品已成功移至购物车',
                'product-removed'    => '该商品已不再可用，因为管理员已将其删除',
                'remove-all-success' => '已成功从愿望清单中删除所有商品',
                'see-details'        => '查看详情',
            ],
        ],
    ],

    'components' => [
        'layouts' => [
            'header' => [
                'title'         => '帐户',
                'welcome'       => '欢迎',
                'welcome-guest' => '欢迎，访客',
                'dropdown-text' => '管理购物车、订单和愿望清单',
                'sign-in'       => '登录',
                'sign-up'       => '注册',
                'account'       => '帐户',
                'cart'          => '购物车',
                'profile'       => '个人资料',
                'wishlist'      => '愿望清单',
                'compare'       => '比较',
                'orders'        => '订单',
                'logout'        => '注销',
                'search-text'   => '在此处搜索产品',
                'search'        => '搜索',
            ],

            'footer' => [
                'newsletter-text'        => '准备好迎接我们的有趣通讯！',
                'subscribe-stay-touch'   => '订阅以保持联系。',
                'subscribe-newsletter'   => '订阅通讯',
                'subscribe'              => '订阅',
                'footer-text'            => '© 版权所有 2010 - 2023，Webkul Software（注册于印度）。保留所有权利。',
                'locale'                 => '区域设置',
                'currency'               => '货币',
                'about-us'               => '关于我们',
                'customer-service'       => '客户服务',
                'whats-new'              => '新动态',
                'contact-us'             => '联系我们',
                'order-return'           => '订单和退货',
                'payment-policy'         => '付款政策',
                'shipping-policy'        => '运输政策',
                'privacy-cookies-policy' => '隐私和Cookie政策',
            ],
        ],

        'datagrid' => [
            'toolbar' => [
                'mass-actions' => [
                    'select-action' => '选择操作',
                    'select-option' => '选择选项',
                    'submit'        => '提交',
                ],

                'filter' => [
                    'title' => '筛选',
                ],

                'search' => [
                    'title' => '搜索',
                ],
            ],

            'filters' => [
                'title' => '应用筛选',

                'custom-filters' => [
                    'title'     => '自定义筛选',
                    'clear-all' => '清除所有',
                ],

                'date-options' => [
                    'today'             => '今天',
                    'yesterday'         => '昨天',
                    'this-week'         => '本周',
                    'this-month'        => '本月',
                    'last-month'        => '上个月',
                    'last-three-months' => '过去3个月',
                    'last-six-months'   => '过去6个月',
                    'this-year'         => '今年',
                ],
            ],

            'table' => [
                'actions'              => '操作',
                'no-records-available' => '没有可用记录。',
            ],
        ],

        'products' => [
            'card' => [
                'new'                => '新品',
                'sale'               => '特价',
                'review-description' => '成为第一个评价该产品的人',
                'add-to-compare'     => '成功添加到比较列表。',
                'already-in-compare' => '该商品已经添加到比较列表中。',
                'add-to-cart'        => '加入购物车',
            ],

            'carousel' => [
                'view-all' => '查看全部',
            ],
        ],

        'range-slider' => [
            'range' => '范围：',
        ],
    ],

    'products'  => [
        'reviews'                => '评论',
        'add-to-cart'            => '加入购物车',
        'add-to-compare'         => '商品已添加到比较列表。',
        'already-in-compare'     => '商品已经在比较列表中。',
        'buy-now'                => '立即购买',
        'compare'                => '比较',
        'rating'                 => '评分',
        'title'                  => '标题',
        'comment'                => '评论',
        'submit-review'          => '提交评论',
        'customer-review'        => '客户评论',
        'write-a-review'         => '撰写评论',
        'stars'                  => '星级',
        'share'                  => '分享',
        'empty-review'           => '暂无评论，成为第一个评论该商品的人',
        'was-this-helpful'       => '此评论是否有帮助？',
        'load-more'              => '加载更多',
        'add-image'              => '添加图片',
        'description'            => '描述',
        'additional-information' => '附加信息',
        'submit-success'         => '提交成功',
        'something-went-wrong'   => '出现问题',
        'in-stock'               => '有库存',
        'available-for-order'    => '可供订购',
        'out-of-stock'           => '缺货',
        'related-product-title'  => '相关商品',
        'up-sell-title'          => '我们还为您推荐',
        'new'                    => '新',
        'as-low-as'              => '最低',
        'starting-at'            => '起价',
        'name'                   => '名称',
        'qty'                    => '数量',
        'offers'                 => '购买 :qty 件，每件 :price，可节省 :discount%',
        'tax-inclusive'          => '含所有税费',

        'sort-by'                => [
            'title'   => '排序方式',
            'options' => [
                'from-a-z'        => '从A到Z',
                'from-z-a'        => '从Z到A',
                'latest-first'    => '最新优先',
                'oldest-first'    => '最旧优先',
                'cheapest-first'  => '价格最低优先',
                'expensive-first' => '价格最高优先',
            ],
        ],

        'view' => [
            'type' => [
                'configurable' => [
                    'select-options'       => '请选择选项',
                    'select-above-options' => '请选择上述选项',
                ],

                'bundle' => [
                    'none' => '无',
                ],

                'downloadable' => [
                    'samples' => '示例',
                    'links'   => '链接',
                    'sample'  => '示例',
                ],

                'grouped' => [
                    'name' => '名称',
                ],
            ],

            'reviews' => [
                'cancel'      => '取消',
                'success'     => '评论提交成功。',
                'attachments' => '附件',
            ],
        ],
    ],

    'categories' => [
        'filters' => [
            'filters'   => '筛选：',
            'filter'    => '筛选',
            'sort'      => '排序',
            'clear-all' => '清除所有',
        ],

        'toolbar' => [
            'show' => '显示',
        ],

        'view' => [
            'empty'     => '该类别中没有可用商品',
            'load-more' => '加载更多',
        ],
    ],

    'search' => [
        'title' => '搜索结果：:query',
    ],

    'compare'  => [
        'product-compare'    => '产品比较',
        'delete-all'         => '删除所有',
        'empty-text'         => '您的比较列表中没有任何商品',
        'title'              => '产品比较',
        'already-added'      => '商品已经添加到比较列表',
        'item-add-success'   => '商品成功添加到比较列表',
        'remove-success'     => '商品成功移除。',
        'remove-all-success' => '所有商品成功移除。',
        'remove-error'       => '发生错误，请稍后再试。',
    ],

    'checkout' => [
        'success' => [
            'title'         => '订单已成功下单',
            'thanks'        => '感谢您的订单！',
            'order-id-info' => '您的订单号是 #:order_id',
            'info'          => '我们将通过电子邮件向您发送订单详情和跟踪信息。',
        ],

        'cart' => [
            'item-add-to-cart'          => '成功添加商品',
            'return-to-shop'            => '返回商店',
            'continue-to-checkout'      => '继续结账',
            'rule-applied'              => '购物车规则已应用',
            'minimum-order-message'     => '最低订单金额为 :amount',
            'suspended-account-message' => '您的帐户已被暂停。',
            'missing-fields'            => '此产品缺少一些必填字段。',
            'missing-options'           => '此产品缺少选项。',
            'missing-links'             => '此产品缺少可下载链接。',
            'select-hourly-duration'    => '选择一个小时的时段。',
            'qty-missing'               => '至少有一个产品的数量应大于1。',
            'success-remove'            => '商品已成功从购物车中移除。',
            'inventory-warning'         => '请求的数量不可用，请稍后再试。',
            'illegal'                   => '数量不能少于1。',
            'inactive'                  => '此商品已被停用，已从购物车中删除。',

            'index' => [
                'home'                     => '主页',
                'cart'                     => '购物车',
                'view-cart'                => '查看购物车',
                'product-name'             => '产品名称',
                'remove'                   => '移除',
                'quantity'                 => '数量',
                'price'                    => '价格',
                'tax'                      => '税',
                'total'                    => '总计',
                'continue-shopping'        => '继续购物',
                'update-cart'              => '更新购物车',
                'move-to-wishlist-success' => '所选商品已成功移至愿望清单。',
                'remove-selected-success'  => '所选商品已成功从购物车中移除。',
                'empty-product'            => '您的购物车中没有产品。',
                'quantity-update'          => '数量已成功更新',
                'see-details'              => '查看详情',
                'move-to-wishlist'         => '移至愿望清单',
                'items-selected'           => '已选择 :count 项',
            ],

            'coupon'   => [
                'code'            => '优惠码',
                'applied'         => '优惠券已应用',
                'apply'           => '应用优惠券',
                'error'           => '出现错误',
                'remove'          => '移除优惠券',
                'invalid'         => '优惠码无效。',
                'discount'        => '优惠券折扣',
                'apply-issue'     => '无法应用优惠码。',
                'success-apply'   => '优惠码已成功应用。',
                'already-applied' => '优惠码已经应用。',
                'enter-your-code' => '输入您的代码',
                'subtotal'        => '小计',
                'button-title'    => '应用',
            ],

            'mini-cart' => [
                'see-details'          => '查看详情',
                'shopping-cart'        => '购物车',
                'offer-on-orders'      => '首单立减30%',
                'remove'               => '移除',
                'empty-cart'           => '您的购物车是空的',
                'subtotal'             => '小计',
                'continue-to-checkout' => '继续结账',
                'view-cart'            => '查看购物车',
            ],

            'summary' => [
                'cart-summary'        => '购物车摘要',
                'sub-total'           => '小计',
                'tax'                 => '税',
                'delivery-charges'    => '运费',
                'discount-amount'     => '折扣金额',
                'grand-total'         => '总计',
                'place-order'         => '下订单',
                'proceed-to-checkout' => '继续结账',
            ],
        ],

        'onepage' => [
            'addresses' => [
                'billing' => [
                    'billing-address'      => '账单地址',
                    'add-new-address'      => '添加新地址',
                    'same-billing-address' => '地址与我的账单地址相同',
                    'back'                 => '返回',
                    'company-name'         => '公司名称',
                    'first-name'           => '名字',
                    'last-name'            => '姓氏',
                    'email'                => '电子邮件',
                    'street-address'       => '街道地址',
                    'country'              => '国家',
                    'state'                => '州/省',
                    'select-state'         => '选择州/省',
                    'city'                 => '城市',
                    'postcode'             => '邮政编码',
                    'telephone'            => '电话',
                    'save-address'         => '保存此地址',
                    'confirm'              => '确认',
                ],

                'index' => [
                    'confirm' => '确认',
                ],

                'shipping' => [
                    'shipping-address' => '送货地址',
                    'add-new-address'  => '添加新地址',
                    'back'             => '返回',
                    'company-name'     => '公司名称',
                    'first-name'       => '名字',
                    'last-name'        => '姓氏',
                    'email'            => '电子邮件',
                    'street-address'   => '街道地址',
                    'country'          => '国家',
                    'state'            => '州/省',
                    'select-state'     => '选择州/省',
                    'select-country'   => '选择国家',
                    'city'             => '城市',
                    'postcode'         => '邮政编码',
                    'telephone'        => '电话',
                    'save-address'     => '保存此地址',
                    'confirm'          => '确认',
                ],
            ],

            'coupon' => [
                'discount'        => '优惠券折扣',
                'code'            => '优惠码',
                'applied'         => '优惠券已应用',
                'applied-coupon'  => '已应用优惠券',
                'apply'           => '应用优惠券',
                'remove'          => '移除优惠券',
                'apply-issue'     => '无法应用优惠码。',
                'sub-total'       => '小计',
                'button-title'    => '应用',
                'enter-your-code' => '输入您的代码',
                'subtotal'        => '小计',
            ],

            'index' => [
                'home'     => '主页',
                'checkout' => '结账',
            ],

            'payment' => [
                'payment-method' => '付款方式',
            ],

            'shipping' => [
                'shipping-method' => '送货方式',
            ],

            'summary' => [
                'cart-summary'     => '购物车摘要',
                'sub-total'        => '小计',
                'tax'              => '税',
                'delivery-charges' => '运费',
                'discount-amount'  => '折扣金额',
                'grand-total'      => '总计',
                'place-order'      => '下订单',
                'processing'       => '处理中',
            ],
        ],
    ],

    'home' => [
        'index' => [
            'offer'               => '首次下单即可享受高达40%的折扣 立即购物',
            'verify-email'        => '验证您的电子邮件帐户',
            'resend-verify-email' => '重新发送验证电子邮件',
        ],
    ],

    'errors' => [
        'go-to-home' => '返回首页',

        '404' => [
            'title'       => '404 页面未找到',
            'description' => '糟糕！您要查找的页面正在度假。看起来我们找不到您要搜索的内容。',
        ],

        '401' => [
            'title'       => '401 未经授权',
            'description' => '糟糕！看起来您无权访问此页面。看来您缺少必要的凭据。',
        ],

        '403' => [
            'title'       => '403 禁止访问',
            'description' => '糟糕！此页面已受限制。看来您没有查看此内容所需的权限。',
        ],

        '500' => [
            'title'       => '500 服务器内部错误',
            'description' => '糟糕！出了些问题。看起来我们在加载您要查找的页面时遇到了麻烦。',
        ],

        '503' => [
            'title'       => '503 服务不可用',
            'description' => '糟糕！看起来我们暂时关闭以进行维护。请稍后再来查看。',
        ],
    ],

    'layouts' => [
        'my-account'            => '我的帐户',
        'profile'               => '个人资料',
        'address'               => '地址',
        'reviews'               => '评论',
        'wishlist'              => '愿望清单',
        'orders'                => '订单',
        'downloadable-products' => '可下载产品',
    ],

    'subscription' => [
        'already'             => '您已成功订阅我们的通讯。',
        'subscribe-success'   => '您已成功订阅我们的通讯。',
        'unsubscribe-success' => '您已成功取消订阅我们的通讯。',
    ],

    'emails' => [
        'dear'   => '尊敬的 :customer_name',
        'thanks' => '如果您需要任何帮助，请联系我们：<a href=":link" style=":style">:email</a>。<br/>谢谢！',

        'customers' => [
            'registration' => [
                'subject'     => '新客户注册',
                'greeting'    => '欢迎注册并感谢您选择我们！',
                'description' => '您的帐户已成功创建，您可以使用您的电子邮件地址和密码凭据登录。登录后，您将能够访问其他服务，包括查看过去的订单、愿望清单和编辑您的帐户信息。',
                'sign-in'     => '登录',
            ],

            'forgot-password' => [
                'subject'        => '重置密码电子邮件',
                'greeting'       => '忘记密码！',
                'description'    => '您收到此电子邮件是因为我们收到了您的帐户的密码重置请求。',
                'reset-password' => '重置密码',
            ],

            'update-password' => [
                'subject'     => '密码已更新',
                'greeting'    => '密码已更新！',
                'description' => '您收到此电子邮件是因为您已更新您的密码。',
            ],

            'verification' => [
                'subject'      => '帐户验证电子邮件',
                'greeting'     => '欢迎！',
                'description'  => '请点击下面的按钮以验证您的电子邮件地址。',
                'verify-email' => '验证电子邮件地址',
            ],

            'commented' => [
                'subject'     => '添加新评论',
                'description' => '备注 - :note',
            ],

            'subscribed' => [
                'subject'     => '您已订阅我们的通讯',
                'greeting'    => '欢迎加入我们的通讯社区！',
                'description' => '祝贺您加入我们的通讯社区！我们很高兴有您的加入，并将随时向您提供最新的新闻、趋势和独家优惠。',
                'unsubscribe' => '取消订阅',
            ],
        ],

        'orders' => [
            'created' => [
                'subject'  => '新订单确认',
                'title'    => '订单确认！',
                'greeting' => '感谢您的订单 #:order_id，下单时间为 :created_at',
                'summary'  => '订单摘要',
            ],

            'invoiced' => [
                'subject'  => '新发票确认',
                'title'    => '发票确认！',
                'greeting' => '您的订单 #:order_id，创建于 :created_at 的发票 #:invoice_id',
                'summary'  => '发票摘要',
            ],

            'shipped' => [
                'subject'  => '新发货确认',
                'title'    => '订单已发货！',
                'greeting' => '您的订单 #:order_id，下单时间为 :created_at，已经发货',
                'summary'  => '发货摘要',
            ],

            'refunded' => [
                'subject'  => '新退款确认',
                'title'    => '订单已退款！',
                'greeting' => '订单 #:order_id，下单时间为 :created_at 的退款已启动',
                'summary'  => '退款摘要',
            ],

            'canceled' => [
                'subject'  => '新订单已取消',
                'title'    => '订单已取消！',
                'greeting' => '您的订单 #:order_id，下单时间为 :created_at，已被取消',
                'summary'  => '订单摘要',
            ],

            'commented' => [
                'subject' => '添加新评论',
                'title'   => '新评论已添加到您的订单 #:order_id，下单时间为 :created_at',
            ],

            'shipping-address'  => '收货地址',
            'carrier'           => '承运人',
            'tracking-number'   => '追踪编号：:tracking_number',
            'billing-address'   => '账单地址',
            'contact'           => '联系方式',
            'shipping'          => '运输',
            'payment'           => '付款',
            'sku'               => 'SKU',
            'name'              => '名称',
            'price'             => '价格',
            'qty'               => '数量',
            'subtotal'          => '小计',
            'shipping-handling' => '运输和处理',
            'tax'               => '税',
            'discount'          => '折扣',
            'grand-total'       => '总计',
        ],
    ],
];
