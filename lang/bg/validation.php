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

    'accepted'        => ':attribute полето трябва да бъде прието.',
    'accepted_if'     => ':attribute полето трябва да бъде прието :other което e :value.',
    'active_url'      => ':attribute полето трябва да е валиден URL.',
    'after'           => ':attribute полето трябва да е дата след :date.',
    'after_or_equal'  => ':attribute полето трябва да е дата след или същата като :date.',
    'alpha'           => ':attribute полето трябва да съдържа само букви.',
    'alpha_dash'      => ':attribute полето трябва да съдържа само букви, цифри, тирета и долни черти.',
    'alpha_num'       => ':attribute полето трябва да съдържа само букви и цифри.',
    'array'           => ':attribute полето трябва да е масив.',
    'ascii'           => ':attribute полето трябва да съдържа само еднобайтови буквено-цифрови знаци и символи.',
    'before'          => ':attribute полето трябва да е дата преди :date.',
    'before_or_equal' => ':attribute полето трябва да е дата преди или същата като :date.',

    'between' => [
        'array'   => 'Полето  :attribute трябва да е между :min и :max предмета.',
        'file'    => 'Полето :attribute да е между :min и :max килобайта.',
        'numeric' => 'Полето :attribute да е между :min и :max.',
        'string'  => 'Полето :attribute да е между :min и :max символа.',
    ],

    'boolean'           => 'Полето :attribute трябва да е вярно или грешно.',
    'can'               => 'Полето :attribute съдържа непозволена стойност.',
    'confirmed'         => 'Полето :attribute за потвърждение не съвпада.',
    'current_password'  => 'Паролата е неправилна.',
    'date'              => 'Полето :attribute трябва да е валидна дата.',
    'date_equals'       => 'Полето :attribute трябва да е дата, равна на :date.',
    'date_format'       => 'Полето :attribute трябва да съответства на формата :format.',
    'decimal'           => 'Полето :attribute трябва да има :decimal десетични знаци.',
    'declined'          => 'Полето :attribute трябва да се отхвърли.',
    'declined_if'       => 'Полето :attribute трябва да се отхвърли, когато :other е :value.',
    'different'         => 'Полето :attribute и :other тряжва да бъдат различни.',
    'digits'            => 'Полето :attribute трябва да бъде :digits цифри.',
    'digits_between'    => 'Полето :attribute трябва да е между :min и :max цифри.',
    'dimensions'        => 'Полето :attribute има невалидни размери на изображението.',
    'distinct'          => 'Полето :attribute има дуплираща се стойност.',
    'doesnt_end_with'   => 'Полето :attribute не трябва да завършва с едно от следните: :values.',
    'doesnt_start_with' => 'Полето :attribute не трябва да започва с едно от следните: :values.',
    'email'             => 'Полето :attribute трябва да е валиден имейл адрес.',
    'ends_with'         => 'Полето :attribute трябва да завършва с едно от следните: :values.',
    'enum'              => 'Избрания :attribute е невалиден.',
    'exists'            => 'Избрания :attribute е невалиден',
    'extensions'        => 'Полето :attribute трябва да има едно от следните разширения: :values.',
    'file'              => 'Полето :attribute трябва да бъде файл.',
    'filled'            => 'Полето :attribute трябва да има стойност.',

    'gt' => [
        'array'   => 'Полето :attribute трябва да съдържа повече от :value елемнта.',
        'file'    => 'Полето :attribute трябва да е по-голямо от :value килобайта.',
        'numeric' => 'Полето :attribute трябва да е по-голямо от :value.',
        'string'  => 'Полето :attribute трябва да е по-голямо от :value символа.',
    ],

    'gte' => [
        'array'   => 'Полето :attribute трябва да съдържа :value елемента или повече.',
        'file'    => 'Полето :attributeтрябва да е по-голямо или равно на :value килобайта.',
        'numeric' => 'Полето :attribute трябва да е по-голямо или равно на :value.',
        'string'  => 'Полето :attribute трябва да е по-голямо или равно на :value символа.',
    ],

    'hex_color' => 'Полето :attribute трябва да е с валиден hexadecimal цвят.',
    'image'     => 'Полето :attribute трябва да е изображение.',
    'in'        => 'Избрания  :attribute е невалиден.',
    'in_array'  => 'Полето :attribute трябва да съществува в :other.',
    'integer'   => 'Полето :attribute трябва да е цяло число.',
    'ip'        => 'Полето :attribute трябва да е валиден IP адрес.',
    'ipv4'      => 'Полето :attribute трябва да е валиден IPv4 адрес.',
    'ipv6'      => 'Полето :attribute трябва да е валиден IPv6 адрес.',
    'json'      => 'Полето :attribute трябва да е валиден JSON string.',
    'lowercase' => 'Полето :attribute трябва да е с малки букви',

    'lt' => [
        'array'   => 'Полето :attribute трябва да има по-малко от :value елемента.',
        'file'    => 'Полето :attribute трябва да е по-малко от :value килобайта.',
        'numeric' => 'Полето :attribute трябва да е по-малко от :value.',
        'string'  => 'Полето :attribute трябва да е по-малко от :value символа.',
    ],

    'lte' => [
        'array'   => 'Полето :attribute не трябва да има повече от :value елемента.',
        'file'    => 'Полето :attribute трябва да е по-малко или равно на :value килобайта.',
        'numeric' => 'Полето :attribute трябва да е по-малко или равно на :value.',
        'string'  => 'Полето :attribute трябва да е по-малко или равно на :value символа.',
    ],

    'mac_address' => 'Полето :attribute трябва да е валиден MAC адрес.',

    'max' => [
        'array'   => 'Полето :attribute не трябва да съдържа повече от :max елемента.',
        'file'    => 'Полето :attribute не трябва да е по-голямо от :max килобайта.',
        'numeric' => 'The :attribute не трябва да е по-голямо от :max.',
        'string'  => 'Полето :attribute не трябва да е по-голямо от :max символа.',
    ],

    'max_digits' => 'Полето :attribute не трябва да има повече от :max цифри.',
    'mimes'      => 'Полето :attribute трябва да е файл от тип: :values.',
    'mimetypes'  => 'Полето :attribute трябва да е файл от тип: :values.',

    'min' => [
        'array'   => 'Полето :attribute трябва да има поне :min елемента.',
        'file'    => 'Полето :attribute трябва да бъде поне :min килобайта.',
        'numeric' => 'Полето :attribute трябва да бъде поне :min.',
        'string'  => 'Полето :attribute трябва да бъде поне :min символа.',
    ],

    'min_digits'       => 'Полето :attribute трябва да има поне :min цифри.',
    'missing'          => 'Полето :attribute трябва да лиспва.',
    'missing_if'       => 'Полето :attribute трябва да липсва, освен ако :other е :value.',
    'missing_with'     => 'Полето :attribute трябва да липсва, когато :values присъства.',
    'missing_with_all' => 'Полето :attribute трябва да липсва, когато :values присъстват',
    'multiple_of'      => 'Полето :attribute трябва да е кратно на :value.',
    'not_in'           => 'Избрания :attribute е невалиден.',
    'not_regex'        => 'Полето :attribute е с невалиден формат.',
    'numeric'          => 'Полето :attribute трябва да е номер.',

    'password' => [
        'letters'       => 'Полето :attribute трябва да съдържа поне една буква.',
        'mixed'         => 'Полето :attribute трябва да съдържа поне една главна и една малка буква.',
        'numbers'       => 'Полето :attribute трябва да съдържа поне една цифра.',
        'symbols'       => 'Полето :attribute трябва да съдържа поне един символ.',
        'uncompromised' => 'Зададения :attribute се появи при изтичане на данни. Моля, изберете нов :attribute.',
    ],

    'present'              => 'Полето :attribute трябва да присъства.',
    'present_if'           => 'Полето :attribute трябва да присъства, когато :other е :value.',
    'present_unless'       => 'Полето :attribute трябва да присъства, освен ако :other е :value.',
    'present_with'         => 'Полето :attribute трябва да присъства, когато :values присъства.',
    'present_with_all'     => 'Полето :attribute трябва да присъства, когато :values присъстват.',
    'prohibited'           => 'Полето :attribute е забранено',
    'prohibited_if'        => 'Полето :attribute е забранено, когато :other е :value.',
    'prohibited_unless'    => 'Полето :attribute е забранено освен :other е в :values.',
    'prohibits'            => 'Полето :attribute забранява :other да присъства',
    'regex'                => 'Полето :attribute е с невалиден формат.',
    'required'             => 'Полето :attribute е задължително.',
    'required_array_keys'  => 'Полето :attribute трябва да съдържа записи за: :values.',
    'required_if'          => 'Полето :attribute се изисква, когато  :other е :value.',
    'required_if_accepted' => 'Полето :attribute се изисква, когато :other е прието.',
    'required_unless'      => 'Полето :attribute се изисква освен, когато :other е в :values.',
    'required_with'        => 'Полето :attribute се изисква, когато :values присъстват.',
    'required_with_all'    => 'Полето :attribute се изисква, когато :values присъстват.',
    'required_without'     => 'Полето :attribute се изисква, когато :values не присъстват.',
    'required_without_all' => 'Полето :attribute се изисква, когато никой от :values присъстват.',
    'same'                 => 'Полето :attribute трябва да съвпада с :other.',

    'size' => [
        'array'   => 'Полето :attribute трябва да съдържа :size елемента.',
        'file'    => 'Полето :attribute трябва да бъде :size килобайта.',
        'numeric' => 'Полето :attribute трябва да бъде :size.',
        'string'  => 'Полето :attribute трябва да бъде :size символа.',
    ],

    'starts_with' => 'Полето :attribute трябва да започва с едно от следните: :values.',
    'string'      => 'Полето :attribute трябва да е string.',
    'timezone'    => 'Полето :attribute трябва да е валидна часова зона.',
    'unique'      => ' :attribute е вече зает.',
    'uploaded'    => 'Неуспшено качване на :attribute .',
    'uppercase'   => 'Полето :attribute трябва да е с главни букви.',
    'url'         => 'Полето :attribute да е валиден URL.',
    'ulid'        => 'Полето :attribute да е валиден ULID.',
    'uuid'        => 'Полето :attribute да е валиден UUID.',

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
    | The following language lines are used to swap our attribute placeholder
    | with something more reader friendly such as "E-Mail Address" instead
    | of "email". This simply helps us make our message more expressive.
    |
    */

    'attributes' => [],

];
