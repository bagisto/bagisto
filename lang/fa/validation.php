<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted'        => 'فیلد :attribute باید پذیرفته شود.',
    'accepted_if'     => 'فیلد :attribute باید پذیرفته شود زمانی که :other برابر با :value است.',
    'active_url'      => 'فیلد :attribute باید یک URL معتبر باشد.',
    'after'           => 'فیلد :attribute باید یک تاریخ پس از :date باشد.',
    'after_or_equal'  => 'فیلد :attribute باید یک تاریخ بعد یا مساوی با :date باشد.',
    'alpha'           => 'فیلد :attribute فقط باید شامل حروف باشد.',
    'alpha_dash'      => 'فیلد :attribute فقط باید شامل حروف، اعداد، خط تیره و زیرخط باشد.',
    'alpha_num'       => 'فیلد :attribute فقط باید شامل حروف و اعداد باشد.',
    'array'           => 'فیلد :attribute باید یک آرایه باشد.',
    'ascii'           => 'فیلد :attribute فقط باید شامل کاراکترهای الفبایی و عددی یک بایتی باشد.',
    'before'          => 'فیلد :attribute باید یک تاریخ پیش از :date باشد.',
    'before_or_equal' => 'فیلد :attribute باید یک تاریخ قبل یا مساوی با :date باشد.',

    'between' => [
        'array'   => 'فیلد :attribute باید بین :min و :max آیتم باشد.',
        'file'    => 'فیلد :attribute باید بین :min و :max کیلوبایت باشد.',
        'numeric' => 'فیلد :attribute باید بین :min و :max باشد.',
        'string'  => 'فیلد :attribute باید بین :min و :max کاراکتر باشد.',
    ],

    'boolean'           => 'فیلد :attribute باید true یا false باشد.',
    'can'               => 'مقدار :attribute شامل مقدار غیرمجاز است.',
    'confirmed'         => 'تأییدیه فیلد :attribute با مطابقت ندارد.',
    'current_password'  => 'رمز عبور اشتباه است.',
    'date'              => 'فیلد :attribute باید یک تاریخ معتبر باشد.',
    'date_equals'       => 'فیلد :attribute باید یک تاریخ مساوی با :date باشد.',
    'date_format'       => 'فیلد :attribute باید با فرمت :format مطابقت داشته باشد.',
    'decimal'           => 'فیلد :attribute باید :decimal رقم اعشار داشته باشد.',
    'declined'          => 'فیلد :attribute باید رد شود.',
    'declined_if'       => 'فیلد :attribute باید رد شود زمانی که :other برابر با :value است.',
    'different'         => 'فیلد :attribute و :other باید متفاوت باشند.',
    'digits'            => 'فیلد :attribute باید :digits رقم باشد.',
    'digits_between'    => 'فیلد :attribute باید بین :min و :max رقم باشد.',
    'dimensions'        => 'فیلد :attribute ابعاد تصویر نامعتبری دارد.',
    'distinct'          => 'فیلد :attribute دارای مقدار تکراری است.',
    'doesnt_end_with'   => 'فیلد :attribute نباید با یکی از موارد زیر پایان یابد: :values.',
    'doesnt_start_with' => 'فیلد :attribute نباید با یکی از موارد زیر شروع شود: :values.',
    'email'             => 'فیلد :attribute باید یک آدرس ایمیل معتبر باشد.',
    'ends_with'         => 'فیلد :attribute باید با یکی از موارد زیر پایان یابد: :values.',
    'enum'              => 'مقدار انتخابی :attribute معتبر نیست.',
    'exists'            => 'مقدار انتخابی :attribute معتبر نیست.',
    'extensions'        => 'فیلد :attribute باید دارای یکی از پسوندهای زیر باشد: :values.',
    'file'              => 'فیلد :attribute باید یک فایل باشد.',
    'filled'            => 'فیلد :attribute باید دارای مقدار باشد.',

    'gt' => [
        'array'   => 'فیلد :attribute باید بیشتر از :value آیتم داشته باشد.',
        'file'    => 'اندازه فایل :attribute باید بیشتر از :value کیلوبایت باشد.',
        'numeric' => 'مقدار :attribute باید بیشتر از :value باشد.',
        'string'  => 'طول رشته :attribute باید بیشتر از :value کاراکتر باشد.',
    ],

    'gte' => [
        'array'   => 'فیلد :attribute باید حداقل شامل :value آیتم باشد.',
        'file'    => 'اندازه فایل :attribute باید حداقل :value کیلوبایت باشد.',
        'numeric' => 'مقدار :attribute باید حداقل :value باشد.',
        'string'  => 'طول رشته :attribute باید حداقل :value کاراکتر باشد.',
    ],

    'hex_color' => 'فیلد :attribute باید یک رنگ شش‌زمینه‌ای معتبر باشد.',
    'image'     => 'فیلد :attribute باید یک تصویر باشد.',
    'in'        => 'مقدار انتخابی :attribute معتبر نیست.',
    'in_array'  => 'فیلد :attribute باید در :other وجود داشته باشد.',
    'integer'   => 'فیلد :attribute باید یک عدد صحیح باشد.',
    'ip'        => 'فیلد :attribute باید یک آدرس IP معتبر باشد.',
    'ipv4'      => 'فیلد :attribute باید یک آدرس IPv4 معتبر باشد.',
    'ipv6'      => 'فیلد :attribute باید یک آدرس IPv6 معتبر باشد.',
    'json'      => 'فیلد :attribute باید یک رشته JSON معتبر باشد.',
    'lowercase' => 'فیلد :attribute باید شامل حروف کوچک باشد.',

    'lt' => [
        'array'   => 'فیلد :attribute باید کمتر از :value آیتم داشته باشد.',
        'file'    => 'اندازه فایل :attribute باید کمتر از :value کیلوبایت باشد.',
        'numeric' => 'مقدار :attribute باید کمتر از :value باشد.',
        'string'  => 'طول رشته :attribute باید کمتر از :value کاراکتر باشد.',
    ],

    'lte' => [
        'array'   => 'فیلد :attribute نباید بیشتر از :value آیتم داشته باشد.',
        'file'    => 'اندازه فایل :attribute باید کمتر یا مساوی با :value کیلوبایت باشد.',
        'numeric' => 'مقدار :attribute باید کمتر یا مساوی با :value باشد.',
        'string'  => 'طول رشته :attribute باید کمتر یا مساوی با :value کاراکتر باشد.',
    ],

    'mac_address' => 'فیلد :attribute باید یک آدرس MAC معتبر باشد.',

    'max' => [
        'array'   => 'فیلد :attribute نباید بیشتر از :max آیتم داشته باشد.',
        'file'    => 'اندازه فایل :attribute نباید بیشتر از :max کیلوبایت باشد.',
        'numeric' => 'مقدار :attribute نباید بیشتر از :max باشد.',
        'string'  => 'طول رشته :attribute نباید بیشتر از :max کاراکتر باشد.',
    ],

    'max_digits' => 'فیلد :attribute نباید بیشتر از :max رقم داشته باشد.',
    'mimes'      => 'فیلد :attribute باید یک فایل از نوع :values باشد.',
    'mimetypes'  => 'فیلد :attribute باید یک فایل از نوع :values باشد.',

    'min' => [
        'array'   => 'فیلد :attribute باید حداقل دارای :min آیتم باشد.',
        'file'    => 'اندازه فایل :attribute باید حداقل :min کیلوبایت باشد.',
        'numeric' => 'مقدار :attribute باید حداقل :min باشد.',
        'string'  => 'طول رشته :attribute باید حداقل :min کاراکتر باشد.',
    ],

    'min_digits'       => 'فیلد :attribute باید حداقل دارای :min رقم باشد.',
    'missing'          => 'فیلد :attribute باید موجود باشد.',
    'missing_if'       => 'فیلد :attribute باید موجود باشد زمانی که :other برابر با :value است.',
    'missing_unless'   => 'فیلد :attribute باید موجود باشد مگر اینکه :other برابر با :value باشد.',
    'missing_with'     => 'فیلد :attribute باید موجود باشد زمانی که :values موجود است.',
    'missing_with_all' => 'فیلد :attribute باید موجود باشد زمانی که :values موجود است.',
    'multiple_of'      => 'فیلد :attribute باید یک ضریب از :value باشد.',
    'not_in'           => 'مقدار انتخابی :attribute معتبر نیست.',
    'not_regex'        => 'فرمت فیلد :attribute نامعتبر است.',
    'numeric'          => 'فیلد :attribute باید یک عدد باشد.',

    'password' => [
        'letters'       => 'فیلد :attribute باید حداقل شامل یک حرف باشد.',
        'mixed'         => 'فیلد :attribute باید حداقل شامل یک حرف بزرگ و یک حرف کوچک باشد.',
        'numbers'       => 'فیلد :attribute باید حداقل شامل یک عدد باشد.',
        'symbols'       => 'فیلد :attribute باید حداقل شامل یک نماد باشد.',
        'uncompromised' => 'مقدار داده شده برای :attribute در یک نشست داده به نظر می‌رسد. لطفاً مقدار دیگری را انتخاب کنید.',
    ],

    'present'              => 'فیلد :attribute باید موجود باشد.',
    'present_if'           => 'فیلد :attribute باید موجود باشد زمانی که :other برابر با :value است.',
    'present_unless'       => 'فیلد :attribute باید موجود باشد مگر اینکه :other برابر با :value باشد.',
    'present_with'         => 'فیلد :attribute باید موجود باشد زمانی که :values موجود است.',
    'present_with_all'     => 'فیلد :attribute باید موجود باشد زمانی که :values موجود است.',
    'prohibited'           => 'فیلد :attribute ممنوع است.',
    'prohibited_if'        => 'فیلد :attribute ممنوع است زمانی که :other برابر با :value است.',
    'prohibited_unless'    => 'فیلد :attribute ممنوع است مگر اینکه :other در :values وجود داشته باشد.',
    'prohibits'            => 'فیلد :attribute از حضور :other منع می‌کند.',
    'regex'                => 'فرمت فیلد :attribute نامعتبر است.',
    'required'             => 'فیلد :attribute الزامی است.',
    'required_array_keys'  => 'فیلد :attribute باید شامل موارد: :values باشد.',
    'required_if'          => 'فیلد :attribute الزامی است زمانی که :other برابر با :value باشد.',
    'required_if_accepted' => 'فیلد :attribute الزامی است زمانی که :other پذیرفته شود.',
    'required_unless'      => 'فیلد :attribute الزامی است مگر اینکه :other در :values وجود داشته باشد.',
    'required_with'        => 'فیلد :attribute الزامی است زمانی که :values موجود است.',
    'required_with_all'    => 'فیلد :attribute الزامی است زمانی که :values موجود است.',
    'required_without'     => 'فیلد :attribute الزامی است زمانی که :values موجود نیست.',
    'required_without_all' => 'فیلد :attribute الزامی است زمانی که هیچ یک از :values موجود نیست.',
    'same'                 => 'فیلد :attribute باید با :other همخوانی داشته باشد.',

    'size' => [
        'array'   => 'فیلد :attribute باید شامل :size مورد باشد.',
        'file'    => 'اندازه فایل :attribute باید :size کیلوبایت باشد.',
        'numeric' => 'مقدار :attribute باید :size باشد.',
        'string'  => 'طول رشته :attribute باید :size کاراکتر باشد.',
    ],

    'starts_with' => 'فیلد :attribute باید با یکی از موارد زیر شروع شود: :values.',
    'string'      => 'فیلد :attribute باید یک رشته باشد.',
    'timezone'    => 'فیلد :attribute باید یک منطقه زمانی معتبر باشد.',
    'unique'      => 'مقدار :attribute قبلاً انتخاب شده است.',
    'uploaded'    => 'آپلود فایل :attribute با شکست مواجه شد.',
    'uppercase'   => 'فیلد :attribute باید با حروف بزرگ نوشته شود.',
    'url'         => 'فیلد :attribute باید یک URL معتبر باشد.',
    'ulid'        => 'فیلد :attribute باید یک ULID معتبر باشد.',
    'uuid'        => 'فیلد :attribute باید یک UUID معتبر باشد.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'پیام سفارشی',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap our attribute placeholder
    | with something more reader friendly such as "E-Mail Address" instead
    | of "email". This simply helps us make our message more expressive.
    |
    */

    'attributes' => [],

];
