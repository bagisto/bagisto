<?php

return [
    'admin'         => [
        'system'    => [
            'velocity' => [
               'general'  => 'Загальний',
               'category'  => 'Категорія',
               'settings'  => 'Налаштування',
               'extension_name' => 'Тема Velocity',
               'error-module-inactive' => 'Попередження: статус теми Velocity неактивен'
            ],

            'settings' => [
                'channels' => [
                    'subscription_bar' =>'Зміст панелі підписки'
                ],
            ],

            'general' => [
                'status' => 'Статус',
                'active' => 'Активний',
                'inactive' => 'Неактивно',
            ],
            'category' => [
                'all'               => 'Все',
                'left'              => 'Лишилось',
                'right'             => 'Вірно',
                'active'            => 'Активний',
                'custom'            => 'Користувацький',
                'inactive'          => 'Неактивний',
                'image-alignment'   => 'Вирівнювання зображення',
                'icon-status'       => 'Статус значка категорії',
                'image-status'      => 'Статус зображення категорії',
                'sub-category-show' => 'Показати підкатегорію',
                'image-height'      => 'Висота зображення [у пікселях]',
                'image-width'       => 'Ширина зображення [у пікселях]',
                'show-tooltip'      => 'Показати категорії',
                'num-sub-category'  => 'Номер підкатегорії',
            ]
        ],
        'layouts'   => [
            'velocity'       => 'Швидкість',
            'cms-pages'      => 'Сторінки CMS',
            'meta-data'      => 'Метадані',
            'category-menu'  => 'Меню категорій',
            'header-content' => 'Вміст заголовка',
        ],
        'contents'  => [
            'self'                  => 'Себе',
            'active'                => 'Активний',
            'new-tab'               => 'Нова вкладка',
            'inactive'              => 'Неактивний',
            'title'                 => 'Список контенту',
            'select'                => '-- Вибирати --',
            'add-title'             => 'Додати вміст',
            'btn-add-content'       => 'Додати вміст',
            'save-btn-title'        => 'Зберегти вміст',
            'autocomplete'          => '[Автозаповнення]',
            'no-result-found'       => 'Запис не знайдено.',
            'search-hint'           => 'Шукати продукт тут...',
            'mass-delete-success'   => 'Вибраний контент успішно видалено.',
            'mass-update-success'   => 'Вибраний контент успішно оновлено.',
            'tab' => [
                'page' => 'Налаштування сторінки',
                'content' => 'Налаштування контента',
                'meta_content' => 'Метадані'
            ],
            'page' => [
                'title' => 'Заголовок',
                'status' => 'Статус',
                'position' => 'Позиція',
            ],
            'content' => [
                'content-type'   => 'Тип контенту',
                'custom-title'   => 'Заголовок користувача',
                'category-slug'  => 'Ідентифікатор категорії',
                'link-target'    => 'Ціль лінки на сторінку',
                'custom-product' => 'Магазин продуктів',
                'custom-heading' => 'Заголовок користувача',
                'catalog-type'   => 'Тип каталога товарів',
                'static-description' => 'Опис контенту',
                'page-link' => 'Посилання на сторінку [наприклад, http://example.com/../../]',
            ],
            'datagrid' => [
                'id' => 'Id',
                'title' => 'Заголовок',
                'status' => 'Статус',
                'position' => 'Позиція',
                'content-type' => 'Тип контенту',
            ]
        ],
        'meta-data' => [
            'footer'                    => 'Нижній колонтитул',
            'title'                     => 'Метаданні швидкості',
            'activate-slider'           => 'Активувати повзунок',
            'home-page-content'         => 'Контент головної сторінки',
            'footer-left-content'       => 'Вміст нижнього лівого колонтитулу',
            'subscription-content'      => 'Вміст панелі передплати',
            'sidebar-categories'        => 'Категорії бічної панелі',
            'header_content_count'      => 'Лічильник змісту заголовка',
            'footer-left-raw-content'   => '<p>Нам подобається створювати програмне забезпечення та вирішувати реальні проблеми за допомогою двійкових файлів. Ми дуже віддані нашим цілям. Ми інвестуємо наші ресурси, щоб створити світclass easy to use softwares and applications for enterprise business with the top notch, on the edge technology expertise.</p>',
            'slider-path'               => 'Шлях слайдера',
            'category-logo'             => 'Логотип категорії',
            'product-policy'            => 'Продуктова політика',
            'update-meta-data'          => 'Оновлювати',
            'product-view-image'        => 'Перегляд продукту',
            'advertisement-two'         => 'Реклама Два зображення',
            'advertisement-one'         => 'Реклама одного зображення',
            'footer-middle-content'     => 'Середина нижнього колонтитулу',
            'advertisement-four'        => 'Реклама чотири зображення',
            'advertisement-three'       => 'Реклама Три зображення',
            'images'                    => 'Зображення',
            'general'                   => 'Загальний',
            'add-image-btn-title'       => 'Додати зображення',
            'image-four-resolution'     => 'Роздільна здатність першого зображення має бути приблизно 427px X 410px,
                                            Роздільна здатність другого зображення має бути приблизно 397px X 180px,
                                            Роздільна здатність третього зображення має бути приблизно 397px X 180px,
                                            Роздільна здатність четвертого зображення має бути приблизно 427px X 410px',
            'image-three-resolution'    => 'Роздільна здатність першого зображення має бути приблизно 635px X 465px,
                                            Роздільна здатність другого зображення має бути приблизно 620 x 225 пикселей.
                                            Роздільна здатність третього зображення має бути 620px X 225px',
            'image-two-resolution'      => 'Роздільна здатність першого зображення має бути приблизноо 953px X 447px,
                                            Разрешение второго изображения должно быть как 303px X 446px',
            'image-locale-resolution'   => 'Роздільна здатність першого зображення має бути 20 x 20 пикселей',
            'footer-middle' => [
                'about-us'  =>'О нас',
                'customer-service' => 'Обслуговування клиєнтів',
                'whats-new' => 'Що нового',
                'contact-us' => 'Поспілкуватися  з нами',
                'order-and-returns' => 'Заказ та повернення',
                'payment-policy' => 'Політика платежів»',
                'shipping-policy' => 'Політика доставки',
                'privacy-and-cookies-policy' => 'Політика конфіденційності та використання файлів cookie',
            ]
        ],
        'category'  => [
            'image-four-resolution'     => 'Роздільна здатність першого зображення має бути приблизно 427px X 410px,
                                            Роздільна здатність другого зображення має бути 397px X 180px,
                                            Роздільна здатність третього зображення має бути 397px X 180px,
                                            Роздільна здатність четвертого зображення має бути 427px X 410px',
            'image-three-resolution'    => 'Роздільна здатність першого зображення має бути приблизно 635px X 465px,
                                            Роздільна здатність другого зображення має бути приблизно 620 x 225 пикселей.
                                            Роздільна здатність третього зображення має бути 620px X 225px',
            'image-two-resolution'      => 'Роздільна здатність першого зображення має бути приблизно 953px X 447px,
                                            Роздільна здатність другого зображення має бути приблизно 303px X 446px',
            'image-locale-resolution'   => 'Роздільна здатність зображення має бути примерно 20 x 20 пикселей',
            'save-btn-title' => 'Зберегти меню',
            'title' => 'Список меню категорій',
            'add-title' => 'Додати вміст меню',
            'edit-title' => 'Редагувати вміст меню',
            'btn-add-category' => 'Додати вміст категорії',
            'datagrid' => [
                'category-id' => 'Ідентифікатор категорії',
                'category-name' => 'Ім\'я категорії',
                'category-icon' => 'Значок категорії',
                'category-status' => 'Статус',
            ],
            'tab' => [
                'general' => 'General',
            ],
            'status'   => 'Статус',
            'active'   => 'Активний',
            'inactive' => 'Неактивний',
            'select'   =>'-- Вибирати --',
            'icon-class' => 'Клас ікони',
            'select-category' => 'Виберіть категорію',
            'tooltip-content' => 'Вміст спливаючої підказки',
            'mass-delete-success' => 'Обрані категорії успішно видалені',
        ],
        'general'   => [
            'locale_logo' => 'Логотип регіону',
        ],
    ],

    'home'          => [
        'view-all'           => 'Подивитися все',
        'add-to-cart'        => 'Додати до кошика',
        'hot-categories'     => 'Гарячі категорії',
        'payment-methods'    => 'Способи оплати',
        'customer-reviews'   => 'Відгуки клієнтів',
        'shipping-methods'   => 'Методи доставки',
        'popular-categories' => 'Популярні категорії',
    ],

    'header'        => [
        'cart'              => 'Корзина',
        'cart'              => 'Корзина',
        'guest'             => 'Гість',
        'logout'            => 'Вийти',
        'title'             => 'Рахунок',
        'account'           => 'Рахунок',
        'profile'           => 'Профіль',
        'wishlist'          => 'список бажань',
        'all-categories'    => 'Всі категорії',
        'search-text'       => 'Шукати товари тут',
        'welcome-message'   => 'Ласкаво просимо, :customer_name',
        'dropdown-text'     => 'Керування кошиком, замовленнями та списком бажань',
    ],

    'menu-navbar'   => [
        'text-more'     => 'Більше',
        'text-category' => 'Пошук по категоріям',
    ],

    'minicart'      => [
        'cart'      => 'Корзина',
        'view-cart' => 'Подивитися корзину',
    ],

    'checkout'      => [
        'qty'       => 'кіл-ть',
        'checkout'  => 'Перевірити',
        'cart'      => [
            'view-cart'     => 'Подивитися корзину',
            'cart-summary'  => 'Сума корзини',
        ],
        'qty'       => 'кіл-ть',
        'items'     => 'Предмети',
        'subtotal'  => 'Проміжний підсумок',
        'sub-total' => 'Проміжний підсумок',
        'proceed'   => 'Перейти до оформлення замовлення',
    ],

    'customer'      => [
        'compare'           => [
            'text'                  => 'Порівняти',
            'compare_similar_items' => 'Порівняти схожі товари',
            'add-tooltip'           => 'Додати товар до списку порівняння',
            'added'                 => "Товар успішно додано до списку порівняння",
            'already_added'         => "Товар вже додано до списку порівняння",
            'removed'               => 'Елемент успішно видалено зі списку порівняння',
            'removed-all'           => 'Всі елементи успішно видалені зі списку порівняння',
            'empty-text'            => "У вашому списку порівняння немає товарів",
            'product_image'         => 'Зображення продукту',
            'actions'               => 'Дії',
        ],
        'login-form'        => [
            'sign-up'               => 'Зарегіструватися',
            'new-customer'          => 'Новий покупець',
            'customer-login'        => 'Вхід для клієнтів',
            'registered-user'       => 'Зареєстрований користувач',
            'your-email-address'    => 'Ваша електронна адреса',
            'form-login-text'       => 'Якщо у вас є обліковий запис, увійдіть, використовуючи свою адресу електронної пошти.',
        ],
        'signup-form'       => [
            'login'             => 'Авторизуватися',
            'become-user'       => 'Стати користувачем',
            'user-registration' => 'Регистрація користувача',
            'form-signup-text'  => 'Якщо ви новачок у нашому магазині, ми раді бачити вас',
        ],
        'forget-password'   => [
            'login'                  => 'Авторизуватися',
            'forgot-password'        => 'Забув пароль',
            'recover-password'       => 'Відновити пароль',
             'recover-password-text' => 'Якщо ви забули свій пароль, відновіть його, ввівши адресу електронної пошти.',
        ],
        'wishlist' => [
            'remove-all-success' => 'Всі предмети з списку бажань було видалено',
        ],
    ],

    'error'         => [
        'go-to-home'            => 'Додому',
        'page-lost-short'       => 'Сторінка втратила контент',
        'something_went_wrong'  => 'щось пішло не так',
        'page-lost-description' => "Сторінка, яку ви шукаєте, недоступна. Спробуйте виконати пошук ще раз або натисніть кнопку 'Повернутися' нижче.",
    ],

    'products'      => [
        'text'              => 'Продукти',
        'details'           => 'Подробиці',
        'reviews-title'     => 'Відгуки',
        'reviewed'          => "Просмотрено",
        'review-by'         => 'Відгук від',
        'quick-view'        => 'Швидкий перегляд',
        'not-available'     => 'Недоступно',
        'submit-review'     => 'Додати відгук',
        'ratings'           => ': рейтинги totalRatings',
        'reviews-count'     => ': totalReviews Відгуки',
        'customer-rating'   => 'Рейтинг клієнтів',
        'more-infomation'   => 'Більше інформації',
        'view-all-reviews'  => 'Переглянути всі відгуки',
        'write-your-review' => 'Напишіть свій відгук',
        'short-description' => 'Короткі описи',
        'recently-viewed'   => 'Нещодавно переглянуті продукти',
        'be-first-review'   => 'Будьте першим, хто залишить відгук',
        'tax-inclusive'     => 'Включаючи всі податки',
    ],

    'shop'          => [
        'gender'    => [
            'male'   => 'Чоловік',
            'other'  => 'Інший',
            'female' => 'Жіночий',
        ],
        'general'   => [
            'no'                     => 'Ні',
            'yes'                    => 'Так',
            'view'                   => 'Вид',
            'filter'                 => 'Фільтр',
            'orders'                 => 'Замовлення',
            'update'                 => 'Оновлювати',
            'reviews'                => 'Відгуки',
            'download'               => 'Завантажити',
            'currencies'             => 'Валюти',
            'addresses'              => 'Адреси',
            'top-brands'             => 'Кращі бренди',
            'new-password'           => 'Новий пароль',
            'no-file-available'      => 'Немає доступних файлів!',
            'downloadables'          => 'Завантажені продукти',
            'confirm-new-password'   => 'Підтвердіть новий пароль',
            'enter-current-password' => 'Введи свій поточний пароль',

            'alert' => [
                'info'      => 'Інформація',
                'error'     => 'Помилка',
                'success'   => 'Успіх',
                'warning'   => 'Попередження',
            ],
        ],
        'wishlist'  => [
            'add-wishlist-text'     => 'Додати товар в список бажань»',
            'remove-wishlist-text'  => 'Видалити товар з списку бажань'
        ],
        'overlay-loader' => [
            'message' => 'Під час виконання'
        ],
    ],

    'responsive'    => [
        'header' => [
            'done'      => 'Зроблено',
            'languages' => 'Мови',
            'greeting'  => 'Ласкаво просимо, :Customer !',
            'greeting-for-guest' => 'Ласкаво просимо, Гость'
        ]
    ],
];