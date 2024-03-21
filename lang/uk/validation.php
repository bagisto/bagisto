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

    'accepted'        => 'Поле :attribute повинно бути прийнято.',
    'accepted_if'     => 'Поле :attribute повинно бути прийнято, коли :other дорівнює :value.',
    'active_url'      => 'Поле :attribute повинно бути дійсним URL.',
    'after'           => 'Поле :attribute повинно бути датою після :date.',
    'after_or_equal'  => 'Поле :attribute повинно бути датою після або дорівнює :date.',
    'alpha'           => 'Поле :attribute повинно містити лише букви.',
    'alpha_dash'      => 'Поле :attribute може містити лише букви, цифри, тире та підкреслення.',
    'alpha_num'       => 'Поле :attribute може містити лише букви та цифри.',
    'array'           => 'Поле :attribute повинно бути масивом.',
    'ascii'           => 'Поле :attribute повинно містити лише однобайтові буквено-цифрові символи та символи.',
    'before'          => 'Поле :attribute повинно бути датою перед :date.',
    'before_or_equal' => 'Поле :attribute повинно бути датою перед або дорівнює :date.',

    'between' => [
        'array'   => 'Поле :attribute повинно містити від :min до :max елементів.',
        'file'    => 'Поле :attribute повинно бути від :min до :max кілобайт.',
        'numeric' => 'Поле :attribute повинно бути від :min до :max.',
        'string'  => 'Поле :attribute повинно бути від :min до :max символів.',
    ],

    'boolean'           => 'Поле :attribute повинно бути true або false.',
    'can'               => 'Поле :attribute містить несанкціоноване значення.',
    'confirmed'         => 'Поле :attribute підтвердження не співпадає.',
    'current_password'  => 'Пароль невірний.',
    'date'              => 'Поле :attribute повинно бути дійсною датою.',
    'date_equals'       => 'Поле :attribute повинно бути датою, рівною :date.',
    'date_format'       => 'Поле :attribute повинно відповідати формату :format.',
    'decimal'           => 'Поле :attribute повинно мати :decimal десяткових знаків.',
    'declined'          => 'Поле :attribute повинно бути відхилено.',
    'declined_if'       => 'Поле :attribute повинно бути відхилено, коли :other дорівнює :value.',
    'different'         => 'Поля :attribute та :other повинні бути різними.',
    'digits'            => 'Поле :attribute повинно мати :digits цифр.',
    'digits_between'    => 'Поле :attribute повинно бути від :min до :max цифр.',
    'dimensions'        => 'Поле :attribute має недійсні розміри зображення.',
    'distinct'          => 'Поле :attribute має значення, яке дублюється.',
    'doesnt_end_with'   => 'Поле :attribute не повинно закінчуватися на один із наступних: :values.',
    'doesnt_start_with' => 'Поле :attribute не повинно починатися з одного із наступних: :values.',
    'email'             => 'Поле :attribute повинно бути дійсною електронною адресою.',
    'ends_with'         => 'Поле :attribute повинно закінчуватися одним із наступних: :values.',
    'enum'              => 'Обране :attribute недійсне.',
    'exists'            => 'Обране :attribute недійсне.',
    'extensions'        => 'Поле :attribute повинно мати одне з наступних розширень: :values.',
    'file'              => 'Поле :attribute повинно бути файлом.',
    'filled'            => 'Поле :attribute повинно мати значення.',

    'gt' => [
        'array'   => 'Поле :attribute повинно містити більше ніж :value елементів.',
        'file'    => 'Поле :attribute повинно бути більше ніж :value кілобайт.',
        'numeric' => 'Поле :attribute повинно бути більше ніж :value.',
        'string'  => 'Поле :attribute повинно бути більше ніж :value символів.',
    ],

    'gte' => [
        'array'   => 'Поле :attribute повинно містити :value елементів або більше.',
        'file'    => 'Поле :attribute повинно бути більше або дорівнювати :value кілобайт.',
        'numeric' => 'Поле :attribute повинно бути більше або дорівнювати :value.',
        'string'  => 'Поле :attribute повинно бути більше або дорівнювати :value символів.',
    ],

    'hex_color' => 'Поле :attribute повинно бути дійсним шестнадцятковим кольором.',
    'image'     => 'Поле :attribute повинно бути зображенням.',
    'in'        => 'Вибране значення :attribute недійсне.',
    'in_array'  => 'Поле :attribute повинно існувати в :other.',
    'integer'   => 'Поле :attribute повинно бути цілим числом.',
    'ip'        => 'Поле :attribute повинно бути дійсною IP-адресою.',
    'ipv4'      => 'Поле :attribute повинно бути дійсною IPv4-адресою.',
    'ipv6'      => 'Поле :attribute повинно бути дійсною IPv6-адресою.',
    'json'      => 'Поле :attribute повинно бути дійсним JSON-рядком.',
    'lowercase' => 'Поле :attribute повинно бути в нижньому регістрі.',

    'lt' => [
        'array'   => 'Поле :attribute повинно містити менше ніж :value елементів.',
        'file'    => 'Поле :attribute повинно бути менше ніж :value кілобайт.',
        'numeric' => 'Поле :attribute повинно бути менше ніж :value.',
        'string'  => 'Поле :attribute повинно бути менше ніж :value символів.',
    ],

    'lte' => [
        'array'   => 'Поле :attribute не повинно містити більше ніж :value елементів.',
        'file'    => 'Поле :attribute повинно бути менше або дорівнювати :value кілобайт.',
        'numeric' => 'Поле :attribute повинно бути менше або дорівнювати :value.',
        'string'  => 'Поле :attribute повинно бути менше або дорівнювати :value символів.',
    ],

    'mac_address' => 'Поле :attribute повинно бути дійсною MAC-адресою.',

    'max' => [
        'array'   => 'Поле :attribute не повинно містити більше ніж :max елементів.',
        'file'    => 'Поле :attribute не повинно бути більше ніж :max кілобайт.',
        'numeric' => 'Поле :attribute не повинно бути більше ніж :max.',
        'string'  => 'Поле :attribute не повинно бути більше ніж :max символів.',
    ],

    'max_digits' => 'Поле :attribute не повинно містити більше ніж :max цифр.',
    'mimes'      => 'Поле :attribute повинно бути файлом типу: :values.',
    'mimetypes'  => 'Поле :attribute повинно бути файлом типу: :values.',

    'min' => [
        'array'   => 'Поле :attribute повинно містити принаймні :min елементів.',
        'file'    => 'Поле :attribute повинно бути принаймні :min кілобайт.',
        'numeric' => 'Поле :attribute повинно бути принаймні :min.',
        'string'  => 'Поле :attribute повинно бути принаймні :min символів.',
    ],

    'min_digits'       => 'Поле :attribute повинно містити принаймні :min цифри.',
    'missing'          => 'Поле :attribute повинно бути відсутнім.',
    'missing_if'       => 'Поле :attribute повинно бути відсутнім, коли :other є :value.',
    'missing_unless'   => 'Поле :attribute повинно бути відсутнім, якщо :other не є :value.',
    'missing_with'     => 'Поле :attribute повинно бути відсутнім, коли :values присутній.',
    'missing_with_all' => 'Поле :attribute повинно бути відсутнім, коли :values присутні.',
    'multiple_of'      => 'Поле :attribute повинно бути кратним :value.',
    'not_in'           => 'Вибране значення :attribute недійсне.',
    'not_regex'        => 'Формат поля :attribute недійсний.',
    'numeric'          => 'Поле :attribute повинно бути числом.',

    'password' => [
        'letters'       => 'Поле :attribute повинно містити принаймні одну літеру.',
        'mixed'         => 'Поле :attribute повинно містити принаймні одну велику та одну малу літеру.',
        'numbers'       => 'Поле :attribute повинно містити принаймні одну цифру.',
        'symbols'       => 'Поле :attribute повинно містити принаймні один символ.',
        'uncompromised' => 'Вказаний :attribute з\'явився у витоку даних. Будь ласка, виберіть інший :attribute.',
    ],

    'present'              => 'Поле :attribute повинно бути присутнім.',
    'present_if'           => 'Поле :attribute повинно бути присутнім, коли :other є :value.',
    'present_unless'       => 'Поле :attribute повинно бути присутнім, якщо :other не є :value.',
    'present_with'         => 'Поле :attribute повинно бути присутнім, коли :values присутній.',
    'present_with_all'     => 'Поле :attribute повинно бути присутнім, коли :values присутні.',
    'prohibited'           => 'Поле :attribute заборонено.',
    'prohibited_if'        => 'Поле :attribute заборонено, коли :other є :value.',
    'prohibited_unless'    => 'Поле :attribute заборонено, якщо :other знаходиться в :values.',
    'prohibits'            => 'Поле :attribute забороняє :other бути присутнім.',
    'regex'                => 'Формат поля :attribute недійсний.',
    'required'             => 'Поле :attribute є обов\'язковим.',
    'required_array_keys'  => 'Поле :attribute повинно містити записи для: :values.',
    'required_if'          => 'Поле :attribute є обов\'язковим, коли :other є :value.',
    'required_if_accepted' => 'Поле :attribute є обов\'язковим, коли :other прийнятий.',
    'required_unless'      => 'Поле :attribute є обов\'язковим, якщо :other не знаходиться в :values.',
    'required_with'        => 'Поле :attribute є обов\'язковим, коли :values присутній.',
    'required_with_all'    => 'Поле :attribute є обов\'язковим, коли :values присутні.',
    'required_without'     => 'Поле :attribute є обов\'язковим, коли :values відсутній.',
    'required_without_all' => 'Поле :attribute є обов\'язковим, коли ні одне з :values не присутнє.',
    'same'                 => 'Поле :attribute повинно збігатися з :other.',

    'size' => [
        'array'   => 'Поле :attribute повинно містити :size елементів.',
        'file'    => 'Поле :attribute повинно бути :size кілобайт.',
        'numeric' => 'Поле :attribute повинно бути :size.',
        'string'  => 'Поле :attribute повинно бути :size символів.',
    ],

    'starts_with' => 'Поле :attribute повинно починатися з одного з наступних: :values.',
    'string'      => 'Поле :attribute повинно бути рядком.',
    'timezone'    => 'Поле :attribute повинно бути дійсною часовою зоною.',
    'unique'      => 'Поле :attribute вже зайняте.',
    'uploaded'    => 'Не вдалося завантажити :attribute.',
    'uppercase'   => 'Поле :attribute повинно бути великими літерами.',
    'url'         => 'Поле :attribute повинно бути дійсною URL-адресою.',
    'ulid'        => 'Поле :attribute повинно бути дійсним ULID.',
    'uuid'        => 'Поле :attribute повинно бути дійсним UUID.',

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
