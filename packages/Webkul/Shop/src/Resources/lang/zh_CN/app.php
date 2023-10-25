<?php

return [
    'customers' => [
        'forgot-password' => [
            'back'                 => '返回登录？',
            'email-not-exist'      => '我们找不到使用该电子邮件地址的用户。',
            'email'                => '电子邮件',
            'forgot-password-text' => '如果您忘记了密码，请通过输入您的电子邮件地址来找回密码。',
            'footer'               => '© 版权所有 2010 - :current_year，Webkul Software（注册于印度）。保留所有权利。',
            'page-title'           => '忘记密码？',
            'reset-link-sent'      => '我们已将重置密码链接发送到您的电子邮件。',
            'submit'               => '重置密码',
            'sign-in-button'       => '登录',
            'title'                => '找回密码',
        ],

        'reset-password' => [
            'back-link-title'  => '返回登录',
            'confirm-password' => '确认密码',
            'email'            => '注册电子邮件',
            'footer'           => '© 版权 2010 - :current_year，Webkul Software（注册于印度）。保留所有权利。',
            'password'         => '密码',
            'submit-btn-title' => '重设密码',
            'title'            => '重设密码',
        ],

        'login-form' => [
            'button-title'        => '登录',
            'create-your-account' => '创建您的帐户',
            'email'               => '电子邮件',
            'form-login-text'     => '如果您已经有帐户，请使用您的电子邮件登录。',
            'footer'              => '© 版权 2010 - :current_year，Webkul Software（注册于印度）。保留所有权利。',
            'forgot-pass'         => '忘记密码？',
            'invalid-credentials' => '请检查您的凭据并重试。',
            'not-activated'       => '您的帐户请求已提交，等待管理员批准。',
            'new-customer'        => '新用户？',
            'page-title'          => '用户登录',
            'password'            => '密码',
            'show-password'       => '显示密码',
            'title'               => '登录',
            'verify-first'        => '请先验证您的电子邮件。',
        ],

        'signup-form' => [
            'account-exists'              => '已经有账户？',
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
                        'sku'                => '存貨單位',
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

                'invoice-pdf' => [
                    'bank-details'      => '银行信息',
                    'bill-to'           => '账单给',
                    'contact'           => '联系方式',
                    'contact-number'    => '联系电话',
                    'date'              => '发票日期',
                    'discount'          => '折扣',
                    'grand-total'       => '总计',
                    'invoice'           => '发票',
                    'invoice-id'        => '发票编号',
                    'order-date'        => '订单日期',
                    'order-id'          => '订单编号',
                    'payment-method'    => '付款方式',
                    'payment-terms'     => '付款条款',
                    'price'             => '价格',
                    'product-name'      => '产品名称',
                    'qty'               => '数量',
                    'ship-to'           => '发货至',
                    'shipping-handling' => '运输和处理',
                    'shipping-method'   => '发货方式',
                    'sku'               => '存货单位',
                    'subtotal'          => '小计',
                    'tax'               => '税',
                    'tax-amount'        => '税额',
                    'vat-number'        => 'VAT号码',
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
                'color'              => '颜色',
                'delete-all'         => '全部删除',
                'empty'              => '愿望清单中没有任何商品。',
                'move-to-cart'       => '移到购物车',
                'moved-success'      => '商品成功移入購物車',
                'moved'              => '商品已成功移至购物车',
                'page-title'         => '愿望清单',
                'profile'            => '个人资料',
                'product-removed'    => '该商品已不再可用，因为管理员已将其删除',
                'remove'             => '移除',
                'removed'            => '商品已成功从愿望清单中移除',
                'remove-fail'        => '商品无法从愿望清单中移除',
                'remove-all-success' => '已成功从愿望清单中删除所有商品',
                'success'            => '商品成功添加到愿望清单',
                'see-details'        => '查看详情',
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
            'default-toggle'  => '默认切换',
        ],

        'media' => [
            'add-attachments' => '添加附件',
        ],

        'layouts' => [
            'header' => [
                'account'       => '账户',
                'compare'       => '比较',
                'cart'          => '购物车',
                'dropdown-text' => '管理购物车、订单和心愿单',
                'logout'        => '登出',
                'orders'        => '订单',
                'profile'       => '个人资料',
                'sign-in'       => '登录',
                'sign-up'       => '注册',
                'search-text'   => '在此搜索产品',
                'search'        => '搜索',
                'title'         => '账户',
                'welcome'       => '欢迎',
                'welcome-guest' => '欢迎访客',
                'wishlist'      => '心愿单',
            ],

            'footer' => [
                'about-us'               => '关于我们',
                'customer-service'       => '客户服务',
                'contact-us'             => '联系我们',
                'currency'               => '货币',
                'footer-text'            => '© 版权所有 2010 - 2023，Webkul Software（印度注册）。 保留所有权利。',
                'locale'                 => '语言',
                'newsletter-text'        => '准备好我们有趣的新闻通讯！',
                'order-return'           => '订单和退货',
                'payment-policy'         => '付款政策',
                'privacy-cookies-policy' => '隐私和 Cookie 政策',
                'subscribe-stay-touch'   => '订阅以保持联系。',
                'subscribe-newsletter'   => '订阅新闻通讯',
                'subscribe'              => '订阅',
                'shipping-policy'        => '运输政策',
                'whats-new'              => '新消息',
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

                'dropdown' => [
                    'searchable' => [
                        'atleast-two-chars' => '至少输入 2 个字符...',
                        'no-results'        => '未找到结果...',
                    ],
                ],

                'custom-filters' => [
                    'clear-all' => '清除所有',
                    'title'     => '自定义筛选',
                ],

                'date-options' => [
                    'last-six-months'   => '过去 6 个月',
                    'last-month'        => '上个月',
                    'last-three-months' => '过去 3 个月',
                    'today'             => '今天',
                    'this-week'         => '本周',
                    'this-month'        => '本月',
                    'this-year'         => '今年',
                    'yesterday'         => '昨天',
                ],
            ],

            'table' => [
                'actions'              => '操作',
                'no-records-available' => '没有可用记录。',
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
                'add-to-compare'     => '成功添加到比较列表。',
                'already-in-compare' => '该项目已添加到比较列表。',
                'add-to-cart'        => '加入购物车',
                'new'                => '新',
                'review-description' => '成为第一个评论此产品的人',
                'sale'               => '折扣',
            ],

            'carousel' => [
                'view-all' => '查看全部',
            ],
        ],

        'range-slider' => [
            'range' => '范围：',
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
            'title'   => '排序方式',
            'options' => [
                'cheapest-first'  => '最便宜的优先',
                'expensive-first' => '最贵的优先',
                'from-a-z'        => 'A 到 Z',
                'from-z-a'        => 'Z 到 A',
                'latest-first'    => '最新的优先',
                'oldest-first'    => '最旧的优先',
            ],
        ],

        'view' => [
            'type' => [
                'configurable' => [
                    'select-options'       => '请选择一个选项',
                    'select-above-options' => '请选择以上选项',
                ],

                'bundle' => [
                    'none' => '无',
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
                'comment'          => '评论',
                'customer-review'  => '客户评论',
                'cancel'           => '取消',
                'empty-review'     => '未找到评论，成为第一个评论此产品的人',
                'failed-to-upload' => '图片上传失败',
                'load-more'        => '加载更多',
                'name'             => '名称',
                'rating'           => '评分',
                'success'          => '评论成功提交。',
                'submit-review'    => '提交评论',
                'title'            => '标题',
                'write-a-review'   => '写评论',
            ],

            'add-to-cart'            => '加入购物车',
            'add-to-compare'         => '已添加到比较。',
            'already-in-compare'     => '产品已经添加到比较列表中。',
            'additional-information' => '附加信息',
            'buy-now'                => '立即购买',
            'compare'                => '比较',
            'description'            => '描述',
            'review'                 => '评论',
            'related-product-title'  => '相关产品',
            'tax-inclusive'          => '含税',
            'up-sell-title'          => '我们发现您可能喜欢的其他产品！',
        ],

        'type' => [
            'abstract' => [
                'offers' => '购买 :qty 件，每件 :price，节省 :discount%',
            ],
        ],
    ],

    'categories' => [
        'filters' => [
            'clear-all' => '清除所有',
            'filters'   => '筛选条件：',
            'filter'    => '筛选',
            'sort'      => '排序',
        ],

        'toolbar' => [
            'show' => '显示',
        ],

        'view' => [
            'empty'     => '此类别中没有可用产品',
            'load-more' => '加载更多',
        ],
    ],

    'search' => [
        'title' => '搜索结果：:query',

        'images' => [
            'index' => [
                'something-went-wrong' => '出了点问题，请稍后重试。',
                'size-limit-error'     => '尺寸限制错误',
                'only-images-allowed'  => '仅允许图片（.jpeg、.jpg、.png 等）。',
            ],

            'results' => [
                'analysed-keywords' => '分析关键字：',
            ],
        ],
    ],

    'compare' => [
        'already-added'      => '产品已经添加到比较列表中',
        'delete-all'         => '删除所有',
        'empty-text'         => '您的比较列表中没有任何项目',
        'item-add-success'   => '产品成功添加到比较列表。',
        'product-compare'    => '产品比较',
        'remove-success'     => '项目已成功移除。',
        'remove-all-success' => '所有项目已成功移除。',
        'remove-error'       => '出了点问题，请稍后再试。',
        'title'              => '产品比较',
    ],

    'checkout' => [
        'success' => [
            'info'          => '我们将通过电子邮件发送您的订单详细信息和跟踪信息',
            'order-id-info' => '您的订单号码是：#:order_id',
            'title'         => '订单已成功下达',
            'thanks'        => '感谢您的订单！',
        ],

        'cart' => [
            'continue-to-checkout'      => '继续结帐',
            'item-add-to-cart'          => '已成功添加项目',
            'inventory-warning'         => '请求的数量不可用，请稍后再试。',
            'illegal'                   => '数量不能少于一。',
            'inactive'                  => '该项目已停用，随后从购物车中移除。',
            'missing-fields'            => '此产品缺少一些必填字段。',
            'missing-options'           => '此产品缺少选项。',
            'missing-links'             => '此产品缺少可下载链接。',
            'minimum-order-message'     => '最低订单金额为 :amount',
            'qty-missing'               => '至少有一个产品应该有超过 1 个数量。',
            'return-to-shop'            => '返回商店',
            'rule-applied'              => '购物车规则已应用',
            'suspended-account-message' => '您的帐户已被暂停。',
            'select-hourly-duration'    => '选择一小时的时段。',
            'success-remove'            => '项目已成功从购物车中移除。',

            'index'                     => [
                'cart'                     => '购物车',
                'continue-shopping'        => '继续购物',
                'empty-product'            => '您的购物车中没有产品。',
                'home'                     => '首页',
                'items-selected'           => ':count 个已选择的项目',
                'move-to-wishlist-success' => '已成功将选定的项目移至心愿单。',
                'move-to-wishlist'         => '移至心愿单',
                'product-name'             => '产品名称',
                'price'                    => '价格',
                'quantity'                 => '数量',
                'quantity-update'          => '数量已成功更新',
                'remove'                   => '删除',
                'remove-selected-success'  => '已成功从购物车中删除选定的项目。',
                'see-details'              => '查看详情',
                'tax'                      => '税',
                'total'                    => '总计',
                'update-cart'              => '更新购物车',
                'view-cart'                => '查看购物车',
            ],

            'coupon' => [
                'applied'         => '已应用优惠券',
                'apply'           => '应用优惠券',
                'apply-issue'     => '无法应用优惠券码。',
                'already-applied' => '优惠券码已应用。',
                'button-title'    => '应用',
                'code'            => '优惠券码',
                'discount'        => '优惠券折扣',
                'error'           => '出了点问题',
                'enter-your-code' => '输入您的代码',
                'invalid'         => '优惠券码无效。',
                'remove'          => '删除优惠券',
                'success-apply'   => '优惠券码已成功应用。',
                'subtotal'        => '小计',
            ],

            'mini-cart' => [
                'continue-to-checkout' => '继续结帐',
                'empty-cart'           => '您的购物车是空的',
                'offer-on-orders'      => '首次下单立减30%',
                'remove'               => '删除',
                'see-details'          => '查看详情',
                'shopping-cart'        => '购物车',
                'subtotal'             => '小计',
                'view-cart'            => '查看购物车',
            ],

            'summary' => [
                'cart-summary'        => '购物车摘要',
                'delivery-charges'    => '运费',
                'discount-amount'     => '折扣金额',
                'grand-total'         => '总计',
                'place-order'         => '下订单',
                'proceed-to-checkout' => '继续结帐',
                'sub-total'           => '小计',
                'tax'                 => '税',
            ],
        ],

        'onepage' => [
            'addresses' => [
                'billing' => [
                    'add-new-address'      => '添加新地址',
                    'billing-address'      => '账单地址',
                    'back'                 => '返回',
                    'company-name'         => '公司名称',
                    'country'              => '国家',
                    'city'                 => '城市',
                    'confirm'              => '确认',
                    'email'                => '电子邮件',
                    'first-name'           => '名字',
                    'last-name'            => '姓氏',
                    'postcode'             => '邮政编码',
                    'same-billing-address' => '地址与我的账单地址相同',
                    'street-address'       => '街道地址',
                    'state'                => '州',
                    'select-state'         => '选择状态',
                    'save-address'         => '保存此地址',
                    'telephone'            => '电话',
                ],

                'index' => [
                    'confirm' => '确认',
                ],

                'shipping' => [
                    'add-new-address'  => '添加新地址',
                    'back'             => '返回',
                    'company-name'     => '公司名称',
                    'country'          => '国家',
                    'city'             => '城市',
                    'confirm'          => '确认',
                    'email'            => '电子邮件',
                    'first-name'       => '名字',
                    'last-name'        => '姓氏',
                    'postcode'         => '邮政编码',
                    'street-address'   => '街道地址',
                    'state'            => '州',
                    'select-state'     => '选择州',
                    'select-country'   => '选择国家',
                    'save-address'     => '保存此地址',
                    'shipping-address' => '送货地址',
                    'telephone'        => '电话',
                ],
            ],

            'coupon' => [
                'applied'         => '优惠券已应用',
                'applied-coupon'  => '应用的优惠券',
                'apply'           => '应用优惠券',
                'apply-issue'     => '无法应用优惠券码。',
                'button-title'    => '应用',
                'code'            => '优惠券代码',
                'discount'        => '优惠券折扣',
                'enter-your-code' => '输入您的代码',
                'remove'          => '删除优惠券',
                'sub-total'       => '小计',
                'subtotal'        => '小计',
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
                'cart-summary'     => '购物车摘要',
                'delivery-charges' => '运费',
                'discount-amount'  => '折扣金额',
                'grand-total'      => '总计',
                'place-order'      => '下订单',
                'processing'       => '处理中',
                'sub-total'        => '小计',
                'tax'              => '税',
            ],
        ],
    ],

    'home' => [
        'index' => [
            'offer'               => '首次下单立减40%，现在开始购物',
            'resend-verify-email' => '重新发送验证电子邮件',
            'verify-email'        => '验证您的电子邮件帐户',
        ],
    ],

    'errors' => [
        'go-to-home'   => '转到主页',

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
                'description' => '您的帐户已成功创建，您可以使用电子邮件地址和密码凭据登录。登录后，您将能够访问其他服务，包括查看过去的订单、愿望清单和编辑您的帐户信息。',
                'greeting'    => '欢迎并感谢您注册我们的网站！',
                'subject'     => '新客户注册',
                'sign-in'     => '登录',
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

            'billing-address'   => '账单地址',
            'contact'           => '联系方式',
            'carrier'           => '运输公司',
            'discount'          => '折扣',
            'grand-total'       => '总计',
            'name'              => '名称',
            'payment'           => '支付',
            'price'             => '价格',
            'qty'               => '数量',
            'shipping-address'  => '送货地址',
            'shipping'          => '送货',
            'sku'               => 'SKU',
            'subtotal'          => '小计',
            'shipping-handling' => '运输和处理',
            'tracking-number'   => '跟踪号码：:tracking_number',
            'tax'               => '税',
        ],
    ],
];
