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

    'accepted'             => ':attribute должно быть принято.',
    'active_url'           => ':attribute не является допустимым URL.',
    'after'                => ':attribute должно быть датой после :date.',
    'after_or_equal'       => ':attribute должно быть датой после или равной :date.',
    'alpha'                => ':attribute может содержать только буквы.',
    'alpha_dash'           => ':attribute может содержать только буквы, цифры, дефисы и подчеркивания.',
    'alpha_num'            => ':attribute может содержать только буквы и цифры.',
    'array'                => ':attribute должен быть массивом.',
    'before'               => ':attribute должно быть датой до :date.',
    'before_or_equal'      => ':attribute должно быть датой до или равной :date.',

    'between'              => [
        'numeric' => ':attribute должно быть между :min и :max.',
        'file'    => ':attribute должно быть между :min и :max килобайт.',
        'string'  => ':attribute должно быть между :min и :max символов.',
        'array'   => ':attribute должно иметь между :min и :max элементами.',
    ],

    'boolean'              => ':attribute поле должно быть true или false.',
    'confirmed'            => 'Подтверждение :attribute не совпадает.',
    'date'                 => ':attribute не является допустимой датой.',
    'date_format'          => ':attribute не соответствует формату :format.',
    'different'            => ':attribute и :other должны различаться.',
    'digits'               => ':attribute должно быть :digits цифр.',
    'digits_between'       => ':attribute должно быть между :min и :max цифрами.',
    'dimensions'           => ':attribute имеет недопустимые размеры изображения.',
    'distinct'             => ':attribute поле имеет дублирующее значение.',
    'email'                => ':attribute должно быть действительным адресом электронной почты.',
    'exists'               => 'Выбранное :attribute недействительно.',
    'file'                 => ':attribute должно быть файлом.',
    'filled'               => ':attribute поле должно иметь значение.',

    'gt'                   => [
        'numeric' => ':attribute должно быть больше чем :value.',
        'file'    => ':attribute должно быть больше чем :value килобайт.',
        'string'  => ':attribute должно быть больше чем :value символов.',
        'array'   => ':attribute должно иметь больше чем :value элементов.',
    ],

    'gte'                  => [
        'numeric' => ':attribute должно быть больше или равно :value.',
        'file'    => ':attribute должно быть больше или равно :value килобайт.',
        'string'  => ':attribute должно быть больше или равно :value символов.',
        'array'   => ':attribute должно иметь :value элементов или больше.',
    ],

    'image'                => ':attribute должно быть изображением.',
    'in'                   => 'Выбранное :attribute недействительно.',
    'in_array'             => ':attribute поле не существует в :other.',
    'integer'              => ':attribute должно быть целым числом.',
    'ip'                   => ':attribute должно быть действительным IP-адресом.',
    'ipv4'                 => ':attribute должно быть действительным IPv4-адресом.',
    'ipv6'                 => ':attribute должно быть действительным IPv6-адресом.',
    'json'                 => ':attribute должно быть допустимой JSON строкой.',

    'lt'                   => [
        'numeric' => ':attribute должно быть меньше чем :value.',
        'file'    => ':attribute должно быть меньше чем :value килобайт.',
        'string'  => ':attribute должно быть меньше чем :value символов.',
        'array'   => ':attribute должно иметь меньше чем :value элементов.',
    ],

    'lte'                  => [
        'numeric' => ':attribute должно быть меньше или равно :value.',
        'file'    => ':attribute должно быть меньше или равно :value килобайт.',
        'string'  => ':attribute должно быть меньше или равно :value символов.',
        'array'   => ':attribute не должно иметь больше чем :value элементов.',
    ],

    'max'                  => [
        'numeric' => ':attribute не может быть больше чем :max.',
        'file'    => ':attribute не может быть больше чем :max килобайт.',
        'string'  => ':attribute не может быть больше чем :max символов.',
        'array'   => ':attribute не может иметь больше чем :max элементов.',
    ],

    'mimes'                => ':attribute должно быть файлом типа: :values.',
    'mimetypes'            => ':attribute должно быть файлом типа: :values.',

    'min'                  => [
        'numeric' => ':attribute должно быть как минимум :min.',
        'file'    => ':attribute должно быть как минимум :min килобайт.',
        'string'  => ':attribute должно быть как минимум :min символов.',
        'array'   => ':attribute должно иметь как минимум :min элементов.',
    ],

    'not_in'               => 'Выбранное :attribute недействительно.',
    'not_regex'            => 'Формат :attribute недействителен.',
    'numeric'              => ':attribute должно быть числом.',
    'present'              => ':attribute поле должно присутствовать.',
    'regex'                => 'Формат :attribute недействителен.',
    'required'             => ':attribute поле обязательно для заполнения.',
    'required_if'          => ':attribute поле обязательно, когда :other равно :value.',
    'required_unless'      => ':attribute поле обязательно, если :other не в :values.',
    'required_with'        => ':attribute поле обязательно, когда :values присутствует.',
    'required_with_all'    => ':attribute поле обязательно, когда :values присутствует.',
    'required_without'     => ':attribute поле обязательно, когда :values отсутствует.',
    'required_without_all' => ':attribute поле обязательно, когда ни одно из :values не присутствует.',
    'same'                 => ':attribute и :other должны совпадать.',

    'size'                 => [
        'numeric' => ':attribute должно быть размером :size.',
        'file'    => ':attribute должно быть размером :size килобайт.',
        'string'  => ':attribute должно быть размером :size символов.',
        'array'   => ':attribute должно содержать :size элементов.',
    ],

    'string'               => ':attribute должно быть строкой.',
    'timezone'             => ':attribute должно быть допустимой временной зоной.',
    'unique'               => ':attribute уже было использовано.',
    'uploaded'             => ':attribute не удалось загрузить.',
    'url'                  => 'Формат :attribute недействителен.',


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
            'rule-name' => 'пользовательское-сообщение',
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
