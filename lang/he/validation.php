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

    'accepted'        => 'שדה :attribute חייב להתקבל.',
    'accepted_if'     => 'שדה :attribute חייב להתקבל כאשר :other הוא :value.',
    'active_url'      => 'שדה :attribute חייב להיות כתובת URL תקפה.',
    'after'           => 'שדה :attribute חייב להיות תאריך אחרי :date.',
    'after_or_equal'  => 'שדה :attribute חייב להיות תאריך אחרי או שווה ל־:date.',
    'alpha'           => 'שדה :attribute יכול להכיל רק אותיות.',
    'alpha_dash'      => 'שדה :attribute יכול להכיל רק אותיות, מספרים, מקפים וקווים תחתונים.',
    'alpha_num'       => 'שדה :attribute יכול להכיל רק אותיות ומספרים.',
    'array'           => 'שדה :attribute חייב להיות מערך.',
    'ascii'           => 'שדה :attribute יכול להכיל רק תווים אלפאנומריים וסימנים בתצורה יחידתית.',
    'before'          => 'שדה :attribute חייב להיות תאריך לפני :date.',
    'before_or_equal' => 'שדה :attribute חייב להיות תאריך לפני או שווה ל־:date.',

    'between' => [
        'array'   => 'שדה :attribute חייב להכיל בין :min ל־:max פריטים.',
        'file'    => 'שדה :attribute חייב להיות בין :min ל־:max קילובייטים.',
        'numeric' => 'שדה :attribute חייב להיות בין :min ל־:max.',
        'string'  => 'שדה :attribute חייב להיות בין :min ל־:max תווים.',
    ],

    'boolean'           => 'שדה :attribute חייב להיות אמת או שקר.',
    'can'               => 'שדה :attribute מכיל ערך לא מורשה.',
    'confirmed'         => 'אישור השדה :attribute אינו תואם.',
    'current_password'  => 'הסיסמה שגויה.',
    'date'              => 'שדה :attribute חייב להיות תאריך תקף.',
    'date_equals'       => 'שדה :attribute חייב להיות תאריך שווה ל־:date.',
    'date_format'       => 'שדה :attribute אינו תואם את הפורמט :format.',
    'decimal'           => 'שדה :attribute חייב להיות עם :decimal ספרות אחרי הנקודה.',
    'declined'          => 'השדה :attribute חייב להיות מדווח כמסריר.',
    'declined_if'       => 'השדה :attribute חייב להיות מדווח כמסריר כאשר :other הוא :value.',
    'different'         => 'השדה :attribute ו־:other חייבים להיות שונים.',
    'digits'            => 'שדה :attribute חייב להיות בעל :digits ספרות.',
    'digits_between'    => 'שדה :attribute חייב להיות בין :min ל־:max ספרות.',
    'dimensions'        => 'התמונה בשדה :attribute יש מימדים לא תקינים.',
    'distinct'          => 'שדה :attribute מכיל ערך כפול.',
    'doesnt_end_with'   => 'שדה :attribute חייב לא להסתיים באחד מהערכים הבאים: :values.',
    'doesnt_start_with' => 'שדה :attribute חייב לא להתחיל באחד מהערכים הבאים: :values.',
    'email'             => 'שדה :attribute חייב להיות כתובת דוא"ל תקינה.',
    'ends_with'         => 'שדה :attribute חייב להסתיים באחד מהערכים הבאים: :values.',
    'enum'              => 'הערך שנבחר עבור :attribute אינו תקין.',
    'exists'            => 'הערך שנבחר עבור :attribute אינו תקין.',
    'extensions'        => 'שדה :attribute חייב להכיל אחת מהסיומות הבאות: :values.',
    'file'              => 'שדה :attribute חייב להיות קובץ.',
    'filled'            => 'שדה :attribute חייב להכיל ערך.',

    'gt' => [
        'array'   => 'שדה :attribute חייב להכיל יותר מ־:value פריטים.',
        'file'    => 'שדה :attribute חייב להיות גדול מ־:value קילובייטים.',
        'numeric' => 'שדה :attribute חייב להיות גדול מ־:value.',
        'string'  => 'שדה :attribute חייב להיות גדול מ־:value תווים.',
    ],

    'gte' => [
        'array'   => 'שדה :attribute חייב להכיל :value פריטים או יותר.',
        'file'    => 'שדה :attribute חייב להיות גדול או שווה ל־:value קילובייטים.',
        'numeric' => 'שדה :attribute חייב להיות גדול או שווה ל־:value.',
        'string'  => 'שדה :attribute חייב להיות גדול או שווה ל־:value תווים.',
    ],

    'hex_color' => 'שדה :attribute חייב להיות קוד צבע הקסדצימלי תקין.',
    'image'     => 'שדה :attribute חייב להיות תמונה.',
    'in'        => 'הערך שנבחר עבור :attribute אינו תקין.',
    'in_array'  => 'שדה :attribute חייב להיות קיים ב־:other.',
    'integer'   => 'שדה :attribute חייב להיות מספר שלם.',
    'ip'        => 'שדה :attribute חייב להיות כתובת IP תקינה.',
    'ipv4'      => 'שדה :attribute חייב להיות כתובת IPv4 תקינה.',
    'ipv6'      => 'שדה :attribute חייב להיות כתובת IPv6 תקינה.',
    'json'      => 'שדה :attribute חייב להיות מחרוזת JSON תקינה.',
    'lowercase' => 'שדה :attribute חייב להיות באותיות קטנות בלבד.',

    'lt' => [
        'array'   => 'שדה :attribute חייב להכיל פחות מ־:value פריטים.',
        'file'    => 'שדה :attribute חייב להיות פחות מ־:value קילובייטים.',
        'numeric' => 'שדה :attribute חייב להיות פחות מ־:value.',
        'string'  => 'שדה :attribute חייב להיות פחות מ־:value תווים.',
    ],

    'lte' => [
        'array'   => 'שדה :attribute חייב להכיל :value פריטים או פחות.',
        'file'    => 'שדה :attribute חייב להיות קטן או שווה ל־:value קילובייטים.',
        'numeric' => 'שדה :attribute חייב להיות קטן או שווה ל־:value.',
        'string'  => 'שדה :attribute חייב להיות קטן או שווה ל־:value תווים.',
    ],

    'mac_address' => 'שדה :attribute חייב להיות כתובת MAC תקינה.',

    'max' => [
        'array'   => 'שדה :attribute חייב להכיל לא יותר מ־:max פריטים.',
        'file'    => 'שדה :attribute חייב להיות קטן מ־:max קילובייטים.',
        'numeric' => 'שדה :attribute חייב להיות קטן מ־:max.',
        'string'  => 'שדה :attribute חייב להיות קטן מ־:max תווים.',
    ],

    'max_digits' => 'שדה :attribute חייב להיות עד :max ספרות.',
    'mimes'      => 'שדה :attribute חייב להיות קובץ מסוג: :values.',
    'mimetypes'  => 'שדה :attribute חייב להיות קובץ מסוג: :values.',

    'min' => [
        'array'   => 'שדה :attribute חייב להכיל לפחות :min פריטים.',
        'file'    => 'שדה :attribute חייב להיות לפחות :min קילובייטים.',
        'numeric' => 'שדה :attribute חייב להיות לפחות :min.',
        'string'  => 'שדה :attribute חייב להיות לפחות :min תווים.',
    ],

    'min_digits'       => 'שדה :attribute חייב להיות לפחות בעל :min ספרות.',
    'missing'          => 'שדה :attribute חייב להיות חסר.',
    'missing_if'       => 'שדה :attribute חייב להיות חסר כאשר :other הוא :value.',
    'missing_unless'   => 'שדה :attribute חייב להיות חסר אלא אם :other הוא :value.',
    'missing_with'     => 'שדה :attribute חייב להיות חסר כאשר :values נמצאים.',
    'missing_with_all' => 'שדה :attribute חייב להיות חסר כאשר :values נמצאים.',
    'multiple_of'      => 'שדה :attribute חייב להיות מרובה של :value.',
    'not_in'           => 'הערך שנבחר עבור :attribute אינו תקין.',
    'not_regex'        => 'פורמט שדה :attribute אינו תקין.',
    'numeric'          => 'שדה :attribute חייב להיות מספר.',

    'password' => [
        'letters'       => 'שדה :attribute חייב לכלול לפחות אות אחת.',
        'mixed'         => 'שדה :attribute חייב לכלול לפחות אות אחת באותיות גדולות ואחת באותיות קטנות.',
        'numbers'       => 'שדה :attribute חייב לכלול לפחות מספר אחד.',
        'symbols'       => 'שדה :attribute חייב לכלול לפחות סמל אחד.',
        'uncompromised' => 'ה־:attribute שנבחר הופיע בפרצת מידע. יש לבחור :attribute אחר.',
    ],

    'present'              => 'שדה :attribute חייב להיות נוכח.',
    'present_if'           => 'שדה :attribute חייב להיות נוכח כאשר :other הוא :value.',
    'present_unless'       => 'שדה :attribute חייב להיות נוכח אלא אם :other הוא :value.',
    'present_with'         => 'שדה :attribute חייב להיות נוכח כאשר :values נמצאים.',
    'present_with_all'     => 'שדה :attribute חייב להיות נוכח כאשר :values נמצאים.',
    'prohibited'           => 'שדה :attribute אסור.',
    'prohibited_if'        => 'שדה :attribute אסור כאשר :other הוא :value.',
    'prohibited_unless'    => 'שדה :attribute אסור אלא אם :other הוא ב־:values.',
    'prohibits'            => 'שדה :attribute אסור מלהכיל את :other.',
    'regex'                => 'פורמט השדה :attribute אינו תקין.',
    'required'             => 'שדה :attribute הוא חובה.',
    'required_array_keys'  => 'שדה :attribute חייב להכיל ערכים עבור: :values.',
    'required_if'          => 'שדה :attribute נדרש כאשר :other הוא :value.',
    'required_if_accepted' => 'שדה :attribute נדרש כאשר :other מתקבל.',
    'required_unless'      => 'שדה :attribute נדרש אלא אם :other הוא ב־:values.',
    'required_with'        => 'שדה :attribute נדרש כאשר :values נמצאים.',
    'required_with_all'    => 'שדה :attribute נדרש כאשר :values נמצאים.',
    'required_without'     => 'שדה :attribute נדרש כאשר :values לא נמצאים.',
    'required_without_all' => 'שדה :attribute נדרש כאשר אף אחד מהערכים :values לא נמצאים.',
    'same'                 => 'שדה :attribute חייב להתאים ל־:other.',

    'size' => [
        'array'   => 'שדה :attribute חייב להכיל :size פריטים.',
        'file'    => 'שדה :attribute חייב להיות בגודל של :size קילובייט.',
        'numeric' => 'שדה :attribute חייב להיות בגודל של :size.',
        'string'  => 'שדה :attribute חייב להיות בגודל של :size תווים.',
    ],

    'starts_with' => 'שדה :attribute חייב להתחיל באחד מהערכים הבאים: :values.',
    'string'      => 'שדה :attribute חייב להיות מחרוזת.',
    'timezone'    => 'שדה :attribute חייב להיות איזור זמן תקין.',
    'unique'      => 'ה־:attribute כבר תפוס.',
    'uploaded'    => 'העלאת :attribute נכשלה.',
    'uppercase'   => 'שדה :attribute חייב להיות באותיות גדולות בלבד.',
    'url'         => 'שדה :attribute חייב להיות כתובת URL תקינה.',
    'ulid'        => 'שדה :attribute חייב להיות ULID תקין.',
    'uuid'        => 'שדה :attribute חייב להיות UUID תקין.',

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
    | The following language lines are used to swap our attribute placeholder
    | with something more reader friendly such as "E-Mail Address" instead
    | of "email". This simply helps us make our message more expressive.
    |
    */

    'attributes' => [],

];
