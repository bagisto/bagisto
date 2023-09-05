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

    'accepted'             => 'يجب أن تقوم بقبول :attribute.',
    'active_url'           => ':attribute ليس رابط صالح.',
    'after'                => ':attribute يجب أن يكون بعد :date.',
    'after_or_equal'       => ':attribute يجب أن تكون مساوية أو بعد تاريخ :date.',
    'alpha'                => ':attribute لا يجوز أن تحتوي إلا على حروف.',
    'alpha_dash'           => ':attribute لا يجوز أن تحتوي إلا على حروف وأرقام وشرطات وشرطات سفلية.',
    'alpha_num'            => ':attribute لا يجوز أن تحتوي إلا على أحرف وأرقام.',
    'array'                => ':attribute يجب أن تكون مصفوفة/قائمة.',
    'before'               => ':attribute يجب أن يكون قبل :date.',
    'before_or_equal'      => ':attribute يجب أن تكون مساوية أو قبل تاريخ :date.',

    'between'              => [
        'numeric' => ':attribute يجب أن يكون من :min إلى :max.',
        'file'    => ':attribute يجب أن يكون من :min إلى :max كيلو بايت.',
        'string'  => ':attribute يجب أن يكون من :min إلى :max حرف.',
        'array'   => ':attribute يجب أن يكون من :min إلى :max عنصر.',
    ],

    'boolean'              => ':attribute يجب أن تكون true أو false.',
    'confirmed'            => 'تأكيد :attribute لا يتطابق.',
    'date'                 => ':attribute ليس تاريخ صالح.',
    'date_format'          => ':attribute لا يطابق الفورمات/الترتيب :format',
    'different'            => ':attribute و :other يجب أن يكونوا مختلفين.',
    'digits'               => ':attribute يجب أن يكون :digits أرقام.',
    'digits_between'       => ':attribute يجب أن يكون رقم من :min إلى :max.',
    'dimensions'           => ':attribute ليس لديها أبعاد صورة صحيحة.',
    'distinct'             => ':attribute يحتوي على قيمة مكررة.',
    'email'                => ':attribute يجب أن يكون بريد إلكتروني صالح.',
    'exists'               => ':attribute المختار هذا غير صالح.',
    'file'                 => ':attribute يجب أن يكون ملف.',
    'filled'               => ':attribute يجب أن يحتوي على قيمة.',

    'gt'                   => [
        'numeric' => ':attribute يجب أن يكون أكبر من :value.',
        'file'    => ':attribute يجب أن يكون أكبر من :value كيلو بايت.',
        'string'  => ':attribute يجب أن يكون أكبر من :value حروف.',
        'array'   => ':attribute يجب أن يكون لديه أكبر من :value عناصر.',
    ],

    'gte'                  => [
        'numeric' => ':attribute يجب أن يكون أكبر من أو يساوي :value.',
        'file'    => ':attribute يجب أن يكون أكبر من أو يساوي :value كيلو بايت.',
        'string'  => ':attribute يجب أن يكون أكبر من أو يساوي :value حروف.',
        'array'   => ':attribute يجب أن يكون لدية :value عناصر أو أكثر.',
    ],

    'image'                => ':attribute يجب أن تكون صورة.',
    'in'                   => ':attribute المحدد هذا غير صالح.',
    'in_array'             => 'حقل :attribute ليس موجود في :other.',
    'integer'              => ':attribute يجب أن يكون رقم صحيح.',
    'ip'                   => ':attribute يجب أن يكون عنوان IP صالح.',
    'ipv4'                 => ':attribute يجب أن يكون عنوان IPv4 صالح.',
    'ipv6'                 => ':attribute يجب أن يكون عنوان IPv6 صالح.',
    'json'                 => ':attribute يجب أن يكون نص JSON صالح.',

    'lt'                   => [
        'numeric' => ':attribute يجب أن يكون أقل من :value.',
        'file'    => ':attribute يجب أن يكون أقل من :value كيلو بايت.',
        'string'  => ':attribute يجب أن يكون أقل من :value حروف.',
        'array'   => ':attribute يجب أن يكون لديه أقل من :value عناصر.',
    ],

    'lte'                  => [
        'numeric' => ':attribute يجب أن يكون أقل من أو يساوي :value.',
        'file'    => ':attribute يجب أن يكون أقل من أو يساوي :value كيلو بايت.',
        'string'  => ':attribute يجب أن يكون أقل من أو يساوي :value حروف.',
        'array'   => ':attribute يجب أن يكون لدية :value عناصر أو أقل.',
    ],

    'max'                  => [
        'numeric' => ':attribute يجب أن لا تكون أكبر من :max.',
        'file'    => ':attribute يجب أن لا تكون أكبر من :max كيلو بايت.',
        'string'  => ':attribute يجب أن لا تكون أكبر من :max حرووف.',
        'array'   => ':attribute يجب أن لا تحتوى على أكبر من :max عنصر.',
    ],

    'mimes'                => ':attribute يجب أن يكون ملف بواحد من هذه الأمتدادات :values.',
    'mimetypes'            => ':attribute يجب أن يكون ملف بواحد من هذه الأمتدادات :values.',

    'min'                  => [
        'numeric' => ':attribute يجب أن يكون علي الأقل :min.',
        'file'    => ':attribute يجب أن يكون علي الأقل :min كيلو بايت.',
        'string'  => ':attribute يجب أن يكون علي الأقل :min حروف.',
        'array'   => ':attribute يجب أن يكون علي الأقل :min عنصر.',
    ],

    'not_in'               => ':attribute المختارة غير صالحة.',
    'not_regex'            => 'تنسيق :attribute هذا غير صالح.',
    'numeric'              => ':attribute يجب أن يكون رقم.',
    'present'              => 'حقل :attribute مطلوب.',
    'regex'                => 'تنسيق :attribute هذا غير صالح.',
    'required'             => 'حقل :attribute مطلوب.',
    'required_if'          => 'حقل :attribute مطلوب عندما تكون قيمة :other هي :value.',
    'required_unless'      => 'حقل :attribute مطلوب ما لم يكن :other في :values.',
    'required_with'        => 'حقل :attribute مطلوب في وجود :values.',
    'required_with_all'    => 'حقل :attribute مطلوب في وجود :values.',
    'required_without'     => 'حقل :attribute مطلوب في عدم وجود :values.',
    'required_without_all' => 'حقل :attribute مطلوب عند عدم وجود أياً من :values.',
    'same'                 => ':attribute و :other يجب أن يكونوا متساويين.',

    'size'                 => [
        'numeric' => ':attribute يجب أن يكون :size.',
        'file'    => ':attribute يجب أن يكون :size كيلو بايت.',
        'string'  => ':attribute يجب أن يكون :size حروف.',
        'array'   => ':attribute يجب أن تحتوي على :size عنصر.',
    ],

    'string'               => ':attribute يجب أن يكون نص.',
    'timezone'             => ':attribute يجب أن تكون منطقة صالحة.',
    'unique'               => ':attribute مسجل مسبقاً.',
    'uploaded'             => 'فشلت عملية رفع :attribute.',
    'url'                  => 'تنسيق :attribute غير صالح.',

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
            'rule-name' => 'custom-message',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap attribute place-holders
    | with something more reader friendly such as E-Mail Address instead
    | of "email". This simply helps us make messages a little cleaner.
    |
    */

    'attributes' => [
        'name'                  => 'الأسم',
        'username'              => 'اسم المستخدم',
        'email'                 => 'البريد الألكتروني',
        'first_name'            => 'الأسم الأول',
        'last_name'             => 'الأسم الأخير',
        'password'              => 'كلمة المرور',
        'password_confirmation' => 'تكرار كلمة المرور',
        'city'                  => 'المدينة',
        'country'               => 'الدولة',
        'address'               => 'العنوان',
        'phone'                 => 'رقم الهاتف',
        'mobile'                => 'رقم الهاتف النقال',
        'age'                   => 'السن',
        'sex'                   => 'الجنس',
        'gender'                => 'النوع',
        'day'                   => 'اليوم',
        'month'                 => 'الشهر',
        'year'                  => 'السنة',
        'hour'                  => 'الساعة',
        'minute'                => 'الدقيقة',
        'second'                => 'الثانية',
        'title'                 => 'العنوان',
        'text'                  => 'النص',
        'content'               => 'المحتوى',
        'description'           => 'الوصف',
        'excerpt'               => 'المقتطفات',
        'date'                  => 'التاريخ',
        'time'                  => 'الوقت',
        'available'             => 'موجود',
        'size'                  => 'الحجم',
        'terms'                 => 'المصطلحات',
        'province'              => 'المحافظة',
    ],

];
