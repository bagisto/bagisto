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

    'accepted'             => 'Поле :attribute повинно бути прийнято.',
    'active_url'           => 'Поле :attribute має невірний URL.',
    'after'                => 'Поле :attribute має бути датою після :date.',
    'after_or_equal'       => 'Поле :attribute має бути датою після або рівною :date.',
    'alpha'                => 'Поле :attribute може містити лише букви.',
    'alpha_dash'           => 'Поле :attribute може містити лише букви, цифри, дефіси та підкреслення.',
    'alpha_num'            => 'Поле :attribute може містити лише букви та цифри.',
    'array'                => 'Поле :attribute повинно бути масивом.',
    'before'               => 'Поле :attribute має бути датою до :date.',
    'before_or_equal'      => 'Поле :attribute має бути датою до або рівною :date.',

    'between'              => [
        'numeric' => 'Поле :attribute повинно бути між :min та :max.',
        'file'    => 'Поле :attribute повинно бути між :min та :max кілобайтів.',
        'string'  => 'Поле :attribute повинно бути між :min та :max символів.',
        'array'   => 'Поле :attribute повинно містити від :min до :max елементів.',
    ],

    'boolean'              => 'Поле :attribute повинно бути true або false.',
    'confirmed'            => 'Підтвердження поля :attribute не відповідає.',
    'date'                 => 'Поле :attribute не є коректною датою.',
    'date_format'          => 'Поле :attribute не відповідає формату :format.',
    'different'            => 'Поля :attribute і :other повинні бути різними.',
    'digits'               => 'Поле :attribute повинно містити :digits цифр.',
    'digits_between'       => 'Поле :attribute повинно містити від :min до :max цифр.',
    'dimensions'           => 'Поле :attribute має невірні розміри зображення.',
    'distinct'             => 'Поле :attribute містить дубльоване значення.',
    'email'                => 'Поле :attribute повинно бути дійсною адресою електронної пошти.',
    'exists'               => 'Обране значення поля :attribute є недійсним.',
    'file'                 => 'Поле :attribute повинно бути файлом.',
    'filled'               => 'Поле :attribute повинно містити значення.',

    'gt'                   => [
        'numeric' => 'Поле :attribute повинно бути більше ніж :value.',
        'file'    => 'Поле :attribute повинно бути більше ніж :value кілобайтів.',
        'string'  => 'Поле :attribute повинно містити більше ніж :value символів.',
        'array'   => 'Поле :attribute повинно містити більше ніж :value елементів.',
    ],

    'gte'                  => [
        'numeric' => 'Поле :attribute повинно бути більше або рівним :value.',
        'file'    => 'Поле :attribute повинно бути більше або рівним :value кілобайтів.',
        'string'  => 'Поле :attribute повинно містити більше або рівно :value символів.',
        'array'   => 'Поле :attribute повинно містити :value елементів або більше.',
    ],

    'image'                => 'Поле :attribute повинно бути зображенням.',
    'in'                   => 'Обране значення поля :attribute є недійсним.',
    'in_array'             => 'Поле :attribute не існує в :other.',
    'integer'              => 'Поле :attribute повинно бути цілим числом.',
    'ip'                   => 'Поле :attribute повинно бути дійсною IP-адресою.',
    'ipv4'                 => 'Поле :attribute повинно бути дійсною IPv4-адресою.',
    'ipv6'                 => 'Поле :attribute повинно бути дійсною IPv6-адресою.',
    'json'                 => 'Поле :attribute повинно бути дійсним JSON-рядком.',

    'lt'                   => [
        'numeric' => 'Поле :attribute повинно бути менше ніж :value.',
        'file'    => 'Поле :attribute повинно бути менше ніж :value кілобайтів.',
        'string'  => 'Поле :attribute повинно містити менше ніж :value символів.',
        'array'   => 'Поле :attribute повинно містити менше ніж :value елементів.',
    ],

    'lte'                  => [
        'numeric' => 'Поле :attribute повинно бути менше або рівним :value.',
        'file'    => 'Поле :attribute повинно бути менше або рівним :value кілобайтів.',
        'string'  => 'Поле :attribute повинно містити менше або рівно :value символів.',
        'array'   => 'Поле :attribute повинно містити не більше ніж :value елементів.',
    ],

    'max'                  => [
        'numeric' => 'Поле :attribute не може бути більше ніж :max.',
        'file'    => 'Поле :attribute не може бути більше ніж :max кілобайтів.',
        'string'  => 'Поле :attribute не може бути більше ніж :max символів.',
        'array'   => 'Поле :attribute не може містити більше ніж :max елементів.',
    ],

    'mimes'                => 'Поле :attribute повинно бути файлом типу: :values.',
    'mimetypes'            => 'Поле :attribute повинно бути файлом типу: :values.',

    'min'                  => [
        'numeric' => 'Поле :attribute повинно бути принаймні :min.',
        'file'    => 'Поле :attribute повинно бути принаймні :min кілобайтів.',
        'string'  => 'Поле :attribute повинно містити принаймні :min символів.',
        'array'   => 'Поле :attribute повинно містити принаймні :min елементів.',
    ],

    'not_in'               => 'Обране значення поля :attribute є недійсним.',
    'not_regex'            => 'Формат поля :attribute є недійсним.',
    'numeric'              => 'Поле :attribute повинно бути числом.',
    'present'              => 'Поле :attribute повинно бути присутнім.',
    'regex'                => 'Формат поля :attribute є недійсним.',
    'required'             => 'Поле :attribute є обов’язковим.',
    'required_if'          => 'Поле :attribute є обов’язковим, коли :other є :value.',
    'required_unless'      => 'Поле :attribute є обов’язковим, якщо :other не є в :values.',
    'required_with'        => 'Поле :attribute є обов’язковим, коли :values присутні.',
    'required_with_all'    => 'Поле :attribute є обов’язковим, коли всі значення :values присутні.',
    'required_without'     => 'Поле :attribute є обов’язковим, коли значення :values відсутні.',
    'required_without_all' => 'Поле :attribute є обов’язковим, коли всі значення :values відсутні.',
    'same'                 => 'Поля :attribute і :other повинні співпадати.',

    'size'                 => [
        'numeric' => 'Поле :attribute повинно бути розміром :size.',
        'file'    => 'Поле :attribute повинно бути розміром :size кілобайтів.',
        'string'  => 'Поле :attribute повинно містити :size символів.',
        'array'   => 'Поле :attribute повинно містити :size елементів.',
    ],

    'string'               => 'Поле :attribute повинно бути рядком.',
    'timezone'             => 'Поле :attribute повинно бути дійсним часовим поясом.',
    'unique'               => 'Поле :attribute вже існує.',
    'uploaded'             => 'Поле :attribute не вдалося завантажити.',
    'url'                  => 'Формат поля :attribute є недійсним.',


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
            'rule-name' => 'власне-повідомлення',
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
