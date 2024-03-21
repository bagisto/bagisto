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

    'accepted'        => 'الحقل :attribute يجب أن يتم قبوله.',
    'accepted_if'     => 'الحقل :attribute يجب أن يتم قبوله عندما يكون :value :other.',
    'active_url'      => 'الحقل :attribute يجب أن يكون رابط URL صالح.',
    'after'           => 'الحقل :attribute يجب أن يكون تاريخًا بعد :date.',
    'after_or_equal'  => 'الحقل :attribute يجب أن يكون تاريخًا بعد أو يساوي :date.',
    'alpha'           => 'الحقل :attribute يجب أن يحتوي على أحرف فقط.',
    'alpha_dash'      => 'الحقل :attribute يجب أن يحتوي على أحرف، أرقام، شرطات، وشرطات سفلية فقط.',
    'alpha_num'       => 'الحقل :attribute يجب أن يحتوي على أحرف وأرقام فقط.',
    'array'           => 'الحقل :attribute يجب أن يكون مصفوفة.',
    'ascii'           => 'الحقل :attribute يجب أن يحتوي على أحرف أبجدية وأرقام ورموز فقط.',
    'before'          => 'الحقل :attribute يجب أن يكون تاريخًا قبل :date.',
    'before_or_equal' => 'الحقل :attribute يجب أن يكون تاريخًا قبل أو يساوي :date.',

    'between' => [
        'array'   => 'الحقل :attribute يجب أن يحتوي بين :min و :max عنصر.',
        'file'    => 'الحقل :attribute يجب أن يكون بين :min و :max كيلوبايت.',
        'numeric' => 'الحقل :attribute يجب أن يكون بين :min و :max.',
        'string'  => 'الحقل :attribute يجب أن يكون بين :min و :max حرف.',
    ],

    'boolean'           => 'الحقل :attribute يجب أن يكون صحيح أو خطأ.',
    'can'               => 'الحقل :attribute يحتوي على قيمة غير مسموح بها.',
    'confirmed'         => 'تأكيد الحقل :attribute لا يتطابق.',
    'current_password'  => 'كلمة المرور غير صحيحة.',
    'date'              => 'الحقل :attribute يجب أن يكون تاريخًا صالحًا.',
    'date_equals'       => 'الحقل :attribute يجب أن يكون تاريخًا مساويًا ل :date.',
    'date_format'       => 'الحقل :attribute يجب أن يتطابق مع التنسيق :format.',
    'decimal'           => 'الحقل :attribute يجب أن يحتوي على :decimal أماكن عشرية.',
    'declined'          => 'الحقل :attribute يجب أن يكون مرفوضًا.',
    'declined_if'       => 'الحقل :attribute يجب أن يكون مرفوضًا عندما يكون :other :value.',
    'different'         => 'الحقل :attribute و :other يجب أن يكونا مختلفين.',
    'digits'            => 'الحقل :attribute يجب أن يكون :digits أرقام.',
    'digits_between'    => 'الحقل :attribute يجب أن يكون بين :min و :max أرقام.',
    'dimensions'        => 'الحقل :attribute يحتوي على أبعاد صورة غير صالحة.',
    'distinct'          => 'الحقل :attribute يحتوي على قيمة مكررة.',
    'doesnt_end_with'   => 'الحقل :attribute يجب ألا ينتهي بأحد القيم التالية: :values.',
    'doesnt_start_with' => 'الحقل :attribute يجب ألا يبدأ بأحد القيم التالية: :values.',
    'email'             => 'الحقل :attribute يجب أن يكون عنوان بريد إلكتروني صالح.',
    'ends_with'         => 'الحقل :attribute يجب أن ينتهي بأحد القيم التالية: :values.',
    'enum'              => 'القيمة المحددة :attribute غير صالحة.',
    'exists'            => 'القيمة المحددة :attribute غير صالحة.',
    'extensions'        => 'الحقل :attribute يجب أن يحتوي على أحد الامتدادات التالية: :values.',
    'file'              => 'الحقل :attribute يجب أن يكون ملفًا.',
    'filled'            => 'الحقل :attribute يجب أن يحتوي على قيمة.',

    'gt' => [
        'array'   => 'الحقل :attribute يجب أن يحتوي على أكثر من :value عنصر.',
        'file'    => 'الحقل :attribute يجب أن يكون أكبر من :value كيلوبايت.',
        'numeric' => 'الحقل :attribute يجب أن يكون أكبر من :value.',
        'string'  => 'الحقل :attribute يجب أن يكون أكبر من :value حرف.',
    ],

    'gte' => [
        'array'   => 'الحقل :attribute يجب أن يحتوي على :value عنصر أو أكثر.',
        'file'    => 'الحقل :attribute يجب أن يكون أكبر من أو يساوي :value كيلوبايت.',
        'numeric' => 'الحقل :attribute يجب أن يكون أكبر من أو يساوي :value.',
        'string'  => 'الحقل :attribute يجب أن يكون أكبر من أو يساوي :value حرف.',
    ],

    'hex_color' => 'الحقل :attribute يجب أن يكون لونًا سداسي عشريًا صالحًا.',
    'image'     => 'الحقل :attribute يجب أن يكون صورة.',
    'in'        => 'القيمة المحددة :attribute غير صالحة.',
    'in_array'  => 'الحقل :attribute يجب أن يكون موجودًا في :other.',
    'integer'   => 'الحقل :attribute يجب أن يكون عددًا صحيحًا.',
    'ip'        => 'الحقل :attribute يجب أن يكون عنوان IP صالحًا.',
    'ipv4'      => 'الحقل :attribute يجب أن يكون عنوان IPv4 صالحًا.',
    'ipv6'      => 'الحقل :attribute يجب أن يكون عنوان IPv6 صالحًا.',
    'json'      => 'الحقل :attribute يجب أن يكون سلسلة JSON صالحة.',
    'lowercase' => 'الحقل :attribute يجب أن يكون في حالة صغيرة.',

    'lt' => [
        'array'   => 'الحقل :attribute يجب أن يحتوي على أقل من :value عنصر.',
        'file'    => 'الحقل :attribute يجب أن يكون أصغر من :value كيلوبايت.',
        'numeric' => 'الحقل :attribute يجب أن يكون أصغر من :value.',
        'string'  => 'الحقل :attribute يجب أن يكون أصغر من :value حرف.',
    ],

    'lte' => [
        'array'   => 'الحقل :attribute يجب ألا يحتوي على أكثر من :value عنصر.',
        'file'    => 'الحقل :attribute يجب أن يكون أصغر من أو يساوي :value كيلوبايت.',
        'numeric' => 'الحقل :attribute يجب أن يكون أصغر من أو يساوي :value.',
        'string'  => 'الحقل :attribute يجب أن يكون أصغر من أو يساوي :value حرف.',
    ],

    'mac_address' => 'الحقل :attribute يجب أن يكون عنوان MAC صالحًا.',

    'max' => [
        'array'   => 'الحقل :attribute يجب ألا يحتوي على أكثر من :max عنصر.',
        'file'    => 'الحقل :attribute يجب ألا يكون أكبر من :max كيلوبايت.',
        'numeric' => 'الحقل :attribute يجب ألا يكون أكبر من :max.',
        'string'  => 'الحقل :attribute يجب ألا يكون أكبر من :max حرف.',
    ],

    'max_digits' => 'الحقل :attribute يجب ألا يحتوي على أكثر من :max أرقام.',
    'mimes'      => 'الحقل :attribute يجب أن يكون ملفًا من النوع: :values.',
    'mimetypes'  => 'الحقل :attribute يجب أن يكون ملفًا من النوع: :values.',

    'min' => [
        'array'   => 'الحقل :attribute يجب أن يحتوي على الأقل على :min عنصر.',
        'file'    => 'الحقل :attribute يجب أن يكون على الأقل :min كيلوبايت.',
        'numeric' => 'الحقل :attribute يجب أن يكون على الأقل :min.',
        'string'  => 'الحقل :attribute يجب أن يكون على الأقل :min حرف.',
    ],

    'min_digits'       => 'الحقل :attribute يجب أن يحتوي على الأقل :min أرقام.',
    'missing'          => 'الحقل :attribute يجب أن يكون مفقودًا.',
    'missing_if'       => 'الحقل :attribute يجب أن يكون مفقودًا عندما يكون :other :value.',
    'missing_unless'   => 'الحقل :attribute يجب أن يكون مفقودًا ما لم يكن :other :value.',
    'missing_with'     => 'الحقل :attribute يجب أن يكون مفقودًا عندما يكون :values موجودًا.',
    'missing_with_all' => 'الحقل :attribute يجب أن يكون مفقودًا عندما تكون :values موجودة.',
    'multiple_of'      => 'الحقل :attribute يجب أن يكون مضاعفًا لـ :value.',
    'not_in'           => 'القيمة المحددة :attribute غير صالحة.',
    'not_regex'        => 'تنسيق الحقل :attribute غير صالح.',
    'numeric'          => 'الحقل :attribute يجب أن يكون رقمًا.',

    'password' => [
        'letters'       => 'الحقل :attribute يجب أن يحتوي على حرف واحد على الأقل.',
        'mixed'         => 'الحقل :attribute يجب أن يحتوي على حرف كبير وحرف صغير على الأقل واحد من كل.',
        'numbers'       => 'الحقل :attribute يجب أن يحتوي على رقم واحد على الأقل.',
        'symbols'       => 'الحقل :attribute يجب أن يحتوي على رمز واحد على الأقل.',
        'uncompromised' => 'الـ :attribute المعطى قد ظهر في تسريب بيانات. يرجى اختيار :attribute مختلف.',
    ],

    'present'              => 'الحقل :attribute يجب أن يكون موجودًا.',
    'present_if'           => 'الحقل :attribute يجب أن يكون موجودًا عندما يكون :other :value.',
    'present_unless'       => 'الحقل :attribute يجب أن يكون موجودًا ما لم يكن :other :value.',
    'present_with'         => 'الحقل :attribute يجب أن يكون موجودًا عندما يكون :values موجودًا.',
    'present_with_all'     => 'الحقل :attribute يجب أن يكون موجودًا عندما تكون :values موجودة.',
    'prohibited'           => 'الحقل :attribute ممنوع.',
    'prohibited_if'        => 'الحقل :attribute ممنوع عندما يكون :other :value.',
    'prohibited_unless'    => 'الحقل :attribute ممنوع ما لم يكن :other في :values.',
    'prohibits'            => 'الحقل :attribute يمنع :other من الوجود.',
    'regex'                => 'تنسيق الحقل :attribute غير صالح.',
    'required'             => 'الحقل :attribute مطلوب.',
    'required_array_keys'  => 'الحقل :attribute يجب أن يحتوي على مفاتيح لـ :values.',
    'required_if'          => 'الحقل :attribute مطلوب عندما يكون :other :value.',
    'required_if_accepted' => 'الحقل :attribute مطلوب عندما يكون :other مقبولًا.',
    'required_unless'      => 'الحقل :attribute مطلوب ما لم يكن :other في :values.',
    'required_with'        => 'الحقل :attribute مطلوب عندما يكون :values موجودًا.',
    'required_with_all'    => 'الحقل :attribute مطلوب عندما تكون :values موجودة.',
    'required_without'     => 'الحقل :attribute مطلوب عندما لا تكون :values موجودة.',
    'required_without_all' => 'الحقل :attribute مطلوب عندما لا تكون أيًا من :values موجودة.',
    'same'                 => 'الحقل :attribute يجب أن يتطابق مع :other.',

    'size' => [
        'array'   => 'الحقل :attribute يجب أن يحتوي على :size عنصر.',
        'file'    => 'الحقل :attribute يجب أن يكون :size كيلوبايت.',
        'numeric' => 'الحقل :attribute يجب أن يكون :size.',
        'string'  => 'الحقل :attribute يجب أن يكون :size حرف.',
    ],

    'starts_with' => 'الحقل :attribute يجب أن يبدأ بأحد القيم التالية: :values.',
    'string'      => 'الحقل :attribute يجب أن يكون نصًا.',
    'timezone'    => 'الحقل :attribute يجب أن يكون مدخلًا صحيحًا للمنطقة الزمنية.',
    'unique'      => 'الـ :attribute تم اختياره بالفعل.',
    'uploaded'    => 'فشل في تحميل الـ :attribute.',
    'uppercase'   => 'الحقل :attribute يجب أن يكون في حالة كبيرة.',
    'url'         => 'الحقل :attribute يجب أن يكون رابط URL صالحًا.',
    'ulid'        => 'الحقل :attribute يجب أن يكون ULID صالحًا.',
    'uuid'        => 'الحقل :attribute يجب أن يكون UUID صالحًا.',

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
            'rule-name' => 'رسالة مخصصة',
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
