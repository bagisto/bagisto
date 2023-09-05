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

    'accepted'             => ':attribute חייב להתקבל.',
    'active_url'           => ':attribute אינו כתובת URL תקנית.',
    'after'                => ':attribute חייב להיות תאריך לאחר :date.',
    'after_or_equal'       => ':attribute חייב להיות תאריך אחרי או שווה ל־:date.',
    'alpha'                => ':attribute יכול להכיל רק אותיות.',
    'alpha_dash'           => ':attribute יכול להכיל רק אותיות, מספרים, מקפים וקווים תחתות.',
    'alpha_num'            => ':attribute יכול להכיל רק אותיות ומספרים.',
    'array'                => ':attribute חייב להיות מערך.',
    'before'               => ':attribute חייב להיות תאריך לפני :date.',
    'before_or_equal'      => ':attribute חייב להיות תאריך לפני או שווה ל־:date.',

    'between'              => [
        'numeric' => ':attribute חייב להיות בין :min ל־:max.',
        'file'    => ':attribute חייב להיות בין :min ל־:max קילובייטים.',
        'string'  => ':attribute חייב להיות בין :min ל־:max תווים.',
        'array'   => ':attribute חייב להכיל בין :min ל־:max פריטים.',
    ],

    'boolean'              => 'שדה :attribute חייב להיות אמת או שקר.',
    'confirmed'            => 'האימות של :attribute אינו תואם.',
    'date'                 => ':attribute אינו תאריך תקני.',
    'date_format'          => ':attribute אינו תואם לפורמט :format.',
    'different'            => ':attribute ו־:other חייבים להיות שונים.',
    'digits'               => ':attribute חייב להיות בעל :digits ספרות.',
    'digits_between'       => ':attribute חייב להיות בין :min ל־:max ספרות.',
    'dimensions'           => 'התמונה של :attribute מכילה מידות לא חוקיות.',
    'distinct'             => 'שדה :attribute מכיל ערך משוכפל.',
    'email'                => ':attribute חייב להיות כתובת אימייל תקנית.',
    'exists'               => ':attribute אינו חוקי.',
    'file'                 => ':attribute חייב להיות קובץ.',
    'filled'               => 'שדה :attribute הינו שדה חובה.',

    'gt'                   => [
        'numeric' => ':attribute חייב להיות גדול מ־:value.',
        'file'    => ':attribute חייב להיות גדול מ־:value קילובייטים.',
        'string'  => ':attribute חייב להיות גדול מ־:value תווים.',
        'array'   => ':attribute חייב להכיל יותר מ־:value פריטים.',
    ],

    'gte'                  => [
        'numeric' => ':attribute חייב להיות גדול או שווה ל־:value.',
        'file'    => ':attribute חייב להיות גדול או שווה ל־:value קילובייטים.',
        'string'  => ':attribute חייב להיות גדול או שווה ל־:value תווים.',
        'array'   => ':attribute חייב להכיל :value פריטים או יותר.',
    ],

    'image'                => ':attribute חייב להיות תמונה.',
    'in'                   => ':attribute אינו חוקי.',
    'in_array'             => 'השדה :attribute אינו קיים ב־:other.',
    'integer'              => ':attribute חייב להיות מספר שלם.',
    'ip'                   => ':attribute חייב להיות כתובת IP תקנית.',
    'ipv4'                 => ':attribute חייב להיות כתובת IPv4 תקנית.',
    'ipv6'                 => ':attribute חייב להיות כתובת IPv6 תקנית.',
    'json'                 => ':attribute חייב להיות מחרוזת JSON תקנית.',

    'lt'                   => [
        'numeric' => ':attribute חייב להיות פחות מ־:value.',
        'file'    => ':attribute חייב להיות פחות מ־:value קילובייטים.',
        'string'  => ':attribute חייב להיות פחות מ־:value תווים.',
        'array'   => ':attribute חייב להכיל פחות מ־:value פריטים.',
    ],

    'lte'                  => [
        'numeric' => ':attribute חייב להיות פחות או שווה ל־:value.',
        'file'    => ':attribute חייב להיות פחות או שווה ל־:value קילובייטים.',
        'string'  => ':attribute חייב להיות פחות או שווה ל־:value תווים.',
        'array'   => ':attribute יכול להכיל עד :value פריטים.',
    ],

    'max'                  => [
        'numeric' => ':attribute לא יכול להיות גדול מ־:max.',
        'file'    => ':attribute לא יכול להיות גדול מ־:max קילובייטים.',
        'string'  => ':attribute לא יכול להיות גדול מ־:max תווים.',
        'array'   => ':attribute לא יכול להכיל יותר מ־:max פריטים.',
    ],

    'mimes'                => ':attribute חייב להיות קובץ מסוג: :values.',
    'mimetypes'            => ':attribute חייב להיות קובץ מסוג: :values.',

    'min'                  => [
        'numeric' => ':attribute חייב להיות לפחות :min.',
        'file'    => ':attribute חייב להיות לפחות :min קילובייטים.',
        'string'  => ':attribute חייב להיות לפחות :min תווים.',
        'array'   => ':attribute חייב להכיל לפחות :min פריטים.',
    ],

    'not_in'               => ':attribute אינו חוקי.',
    'not_regex'            => 'הפורמט של :attribute אינו חוקי.',
    'numeric'              => ':attribute חייב להיות מספר.',
    'present'              => 'השדה :attribute חייב להיות נוכח.',
    'regex'                => 'הפורמט של :attribute אינו חוקי.',
    'required'             => 'שדה :attribute הוא שדה חובה.',
    'required_if'          => 'שדה :attribute נדרש כאשר :other הוא :value.',
    'required_unless'      => 'שדה :attribute נדרש אלא אם :other הוא בין הערכים :values.',
    'required_with'        => 'שדה :attribute נדרש כאשר קיים :values.',
    'required_with_all'    => 'שדה :attribute נדרש כאשר קיימים :values.',
    'required_without'     => 'שדה :attribute נדרש כאשר אין :values.',
    'required_without_all' => 'שדה :attribute נדרש כאשר אין אף אחד מהערכים :values.',
    'same'                 => ':attribute ו־:other חייבים להיות תואמים.',

    'size'                 => [
        'numeric' => ':attribute חייב להיות בגודל של :size.',
        'file'    => ':attribute חייב להיות בגודל של :size קילובייטים.',
        'string'  => ':attribute חייב להיות בעל :size תווים.',
        'array'   => ':attribute חייב להכיל :size פריטים.',
    ],

    'string'               => ':attribute חייב להיות מחרוזת.',
    'timezone'             => ':attribute חייב להיות אזור תקני.',
    'unique'               => ':attribute כבר נמצא בשימוש.',
    'uploaded'             => 'הטעינה של :attribute נכשלה.',
    'url'                  => ':attribute אינו כתובת URL תקנית.',

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
            'rule-name' => 'הודעה מותאמת אישית',
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

    'attributes' => [],

];
