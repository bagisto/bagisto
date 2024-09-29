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

    'accepted'        => 'Полето ":attribute" трябва да бъде прието.',
    'accepted_if'     => 'Полето ":attribute" трябва да бъде прието, когато ":other" е ":value".',
    'active_url'      => 'Полето ":attribute" трябва да съдържа валиден URL адрес.',
    'after'           => 'Полето ":attribute" трябва да съдържа дата след :date.',
    'after_or_equal'  => 'Полето ":attribute" трябва да съдържа дата след или равна на :date.',
    'alpha'           => 'Полето ":attribute" трябва да съдържа само букви.',
    'alpha_dash'      => 'Полето ":attribute" трябва да съдържа само букви, цифри, тирета и долни черти.',
    'alpha_num'       => 'Полето ":attribute" трябва да съдържа само букви и цифри.',
    'array'           => 'Полето ":attribute" трябва да бъде масив.',
    'ascii'           => 'Полето ":attribute" трябва да съдържа само еднобайтови буквено-цифрови знаци и символи.',
    'before'          => 'Полето ":attribute" трябва да съдържа дата преди :date.',
    'before_or_equal' => 'Полето ":attribute" трябва да съдържа дата преди или равна на :date.',

    'between' => [
        'array'   => 'Полето ":attribute" трябва да съдържа между :min и :max елемента.',
        'file'    => 'Полето ":attribute" трябва да бъде между :min и :max килобайта.',
        'numeric' => 'Полето ":attribute" трябва да бъде между :min и :max.',
        'string'  => 'Полето ":attribute" трябва да съдържа между :min и :max символа.',
    ],

    'boolean'           => 'Полето ":attribute" трябва да има стойност "true" или "false".',
    'can'               => 'Полето ":attribute" съдържа неразрешена стойност.',
    'confirmed'         => 'Потвърждението на полето ":attribute" не съвпада.',
    'current_password'  => 'Паролата е некоректна.',
    'date'              => 'Полето ":attribute" трябва да съдържа валидна дата.',
    'date_equals'       => 'Полето ":attribute" трябва да съдържа дата, равна на :date.',
    'date_format'       => 'Полето ":attribute" трябва да отговаря на формата :format.',
    'decimal'           => 'Полето ":attribute" трябва да има :decimal десетични знака.',
    'declined'          => 'Полето ":attribute" трябва да бъде отхвърлено.',
    'declined_if'       => 'Полето ":attribute" трябва да бъде отхвърлено, когато ":other" е ":value".',
    'different'         => 'Полето ":attribute" и полето ":other" трябва да са различни.',
    'digits'            => 'Полето ":attribute" трябва да съдържа :digits цифри.',
    'digits_between'    => 'Полето ":attribute" трябва да съдържа между :min и :max цифри.',
    'dimensions'        => 'Полето ":attribute" съдържа невалидни размери на изображението.',
    'distinct'          => 'Полето ":attribute" съдържа дублирана стойност.',
    'doesnt_end_with'   => 'Полето ":attribute" не трябва да завършва с някой от следните символи: :values.',
    'doesnt_start_with' => 'Полето ":attribute" не трябва да започва с някой от следните символи: :values.',
    'email'             => 'Полето ":attribute" трябва да съдържа валиден имейл адрес.',
    'ends_with'         => 'Полето ":attribute" трябва да завършва с някой от следните символи: :values.',
    'enum'              => 'Избраният ":attribute" е невалиден.',
    'exists'            => 'Избраният ":attribute" е невалиден.',
    'extensions'        => 'Полето ":attribute" трябва да има едно от следните разширения: :values.',
    'file'              => 'Полето ":attribute" трябва да съдържа файл.',
    'filled'            => 'Полето ":attribute" трябва да има стойност.',

    'gt' => [
        'array'   => 'Полето ":attribute" трябва да съдържа повече от :value елемента.',
        'file'    => 'Полето ":attribute" трябва да бъде по-голямо от :value килобайта.',
        'numeric' => 'Полето ":attribute" трябва да бъде по-голямо от :value.',
        'string'  => 'Полето ":attribute" трябва да съдържа повече от :value символа.',
    ],

    'gte' => [
        'array'   => 'Полето ":attribute" трябва да съдържа :value елемента или повече.',
        'file'    => 'Полето ":attribute" трябва да бъде по-голямо или равно на :value килобайта.',
        'numeric' => 'Полето ":attribute" трябва да бъде по-голямо или равно на :value.',
        'string'  => 'Полето ":attribute" трябва да съдържа най-малко :value символа.',
    ],

    'hex_color' => 'Полето ":attribute" трябва да съдържа валиден шестнадесетичен код за цвят.',
    'image'     => 'Полето ":attribute" трябва да съдържа изображение.',
    'in'        => 'Избраният ":attribute" е невалиден.',
    'in_array'  => 'Полето ":attribute" трябва да съществува в ":other".',
    'integer'   => 'Полето ":attribute" трябва да съдържа цяло число.',
    'ip'        => 'Полето ":attribute" трябва да съдържа валиден IP адрес.',
    'ipv4'      => 'Полето ":attribute" трябва да съдържа валиден IPv4 адрес.',
    'ipv6'      => 'Полето ":attribute" трябва да съдържа валиден IPv6 адрес.',
    'json'      => 'Полето ":attribute" трябва да съдържа валиден JSON стринг.',
    'lowercase' => 'Полето ":attribute" трябва да съдържа само малки букви.',

    'lt' => [
        'array'   => 'Полето ":attribute" трябва да съдържа по-малко от :value елемента.',
        'file'    => 'Полето ":attribute" трябва да бъде по-малко от :value килобайта.',
        'numeric' => 'Полето ":attribute" трябва да бъде по-малко от :value.',
        'string'  => 'Полето ":attribute" трябва да съдържа по-малко от :value символа.',
    ],

    'lte' => [
        'array'   => 'Полето ":attribute" не трябва да съдържа повече от :value елемента.',
        'file'    => 'Полето ":attribute" трябва да бъде по-малко или равно на :value килобайта.',
        'numeric' => 'Полето ":attribute" трябва да бъде по-малко или равно на :value.',
        'string'  => 'Полето ":attribute" трябва да съдържа не повече от :value символа.',
    ],

    'mac_address' => 'Полето ":attribute" трябва да съдържа валиден MAC адрес.',

    'max' => [
        'array'   => 'Полето ":attribute" не трябва да съдържа повече от :max елемента.',
        'file'    => 'Полето ":attribute" не трябва да бъде по-голямо от :max килобайта.',
        'numeric' => 'Полето ":attribute" не трябва да бъде по-голямо от :max.',
        'string'  => 'Полето ":attribute" не трябва да съдържа повече от :max символа.',
    ],

    'max_digits' => 'Полето ":attribute" не трябва да съдържа повече от :max цифри.',
    'mimes'      => 'Полето ":attribute" трябва да бъде файл от тип: :values.',
    'mimetypes'  => 'Полето ":attribute" трябва да бъде файл от тип: :values.',

    'min' => [
        'array'   => 'Полето ":attribute" трябва да съдържа най-малко :min елемента.',
        'file'    => 'Полето ":attribute" трябва да бъде най-малко :min килобайта.',
        'numeric' => 'Полето ":attribute" трябва да бъде най-малко :min.',
        'string'  => 'Полето ":attribute" трябва да съдържа най-малко :min символа.',
    ],

    'min_digits'       => 'Полето ":attribute" трябва да съдържа най-малко :min цифри.',
    'missing'          => 'Полето ":attribute" трябва да липсва.',
    'missing_if'       => 'Полето ":attribute" трябва да липсва, когато ":other" е ":value".',
    'missing_unless'   => 'Полето ":attribute" трябва да липсва, освен ако ":other" е ":value".',
    'missing_with'     => 'Полето ":attribute" трябва да липсва, когато ":values" е налично.',
    'missing_with_all' => 'Полето ":attribute" трябва да липсва, когато ":values" са налични.',
    'multiple_of'      => 'Полето ":attribute" трябва да бъде кратно на :value.',
    'not_in'           => 'Избраният ":attribute" е невалиден.',
    'not_regex'        => 'Форматът на полето ":attribute" е невалиден.',
    'numeric'          => 'Полето ":attribute" трябва да съдържа число.',

    'password' => [
        'letters'       => 'Полето ":attribute" трябва да съдържа поне една буква.',
        'mixed'         => 'Полето ":attribute" трябва да съдържа поне една главна и една малка буква.',
        'numbers'       => 'Полето ":attribute" трябва да съдържа поне една цифра.',
        'symbols'       => 'Полето ":attribute" трябва да съдържа поне един символ.',
        'uncompromised' => 'Предоставеният :attribute се е появил в теч на данни. Моля, изберете различен :attribute.',
    ],

    'present'              => 'Полето ":attribute" трябва да бъде попълнено.',
    'present_if'           => 'Полето ":attribute" трябва да бъде попълнено, когато ":other" е ":value".',
    'present_unless'       => 'Полето ":attribute" трябва да бъде попълнено, освен ако ":other" е ":value".',
    'present_with'         => 'Полето ":attribute" трябва да бъде попълнено, когато ":values" е налично.',
    'present_with_all'     => 'Полето ":attribute" трябва да бъде попълнено, когато ":values" са налични.',
    'prohibited'           => 'Полето ":attribute" е забранено.',
    'prohibited_if'        => 'Полето ":attribute" е забранено, когато ":other" е ":value".',
    'prohibited_unless'    => 'Полето ":attribute" е забранено, освен ако ":other" е в :values.',
    'prohibits'            => 'Полето ":attribute" забранява присъствието на ":other".',
    'regex'                => 'Форматът на полето ":attribute" е невалиден.',
    'required'             => 'Полето ":attribute" е задължително.',
    'required_array_keys'  => 'Полето ":attribute" трябва да съдържа записи за: :values.',
    'required_if'          => 'Полето ":attribute" е задължително, когато ":other" е ":value".',
    'required_if_accepted' => 'Полето ":attribute" е задължително, когато ":other" е прието.',
    'required_unless'      => 'Полето ":attribute" е задължително, освен ако ":other" е в :values.',
    'required_with'        => 'Полето ":attribute" е задължително, когато ":values" е налично.',
    'required_with_all'    => 'Полето ":attribute" е задължително, когато ":values" са налични.',
    'required_without'     => 'Полето ":attribute" е задължително, когато ":values" не е налично.',
    'required_without_all' => 'Полето ":attribute" е задължително, когато нито една от опциите в ":values" не е избрана.',
    'same'                 => 'Полето ":attribute" трябва да съвпада с ":other".',

    'size' => [
        'array'   => 'Полето ":attribute" трябва да съдържа :size елемента.',
        'file'    => 'Полето ":attribute" трябва да бъде :size килобайта.',
        'numeric' => 'Полето ":attribute" трябва да бъде :size.',
        'string'  => 'Полето ":attribute" трябва да съдържа точно :size символа.',
    ],

    'starts_with' => 'Полето ":attribute" трябва да започва с някой от следните символи: :values.',
    'string'      => 'Полето ":attribute" трябва да съдържа стринг.',
    'timezone'    => 'Полето ":attribute" трябва да съдържа валидна часова зона.',
    'unique'      => '":attribute" вече е използвано.',
    'uploaded'    => '":attribute" не можа да бъде качен.',
    'uppercase'   => 'Полето ":attribute" трябва да съдържа само главни букви.',
    'url'         => 'Полето ":attribute" трябва да съдържа валиден URL адрес.',
    'ulid'        => 'Полето ":attribute" трябва да съдържа валиден ULID.',
    'uuid'        => 'Полето ":attribute" трябва да съдържа валиден UUID.',

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
