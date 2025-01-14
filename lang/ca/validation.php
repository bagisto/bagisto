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

    'accepted'        => 'El camp :attribute ha de ser acceptat.',
    'accepted_if'     => 'El camp :attribute ha de ser acceptat quan :other es :value.',
    'active_url'      => 'El camp :attribute ha de ser una URL vàlida.',
    'after'           => 'El camp :attribute ha de ser una data posterior a :date.',
    'after_or_equal'  => 'El camp :attribute ha de ser una data posterior o igual a :date.',
    'alpha'           => 'El camp :attribute només ha de contenir lletres.',
    'alpha_dash'      => 'El camp :attribute només ha de contenir lletres, números, guions y guions baixos.',
    'alpha_num'       => 'El camp :attribute només ha de contenir lletres y números.',
    'array'           => 'El camp :attribute ha de ser un array.',
    'ascii'           => 'El camp :attribute només ha de contenir caracters alfanumérics y símbols de només un byte.',
    'before'          => 'El camp :attribute ha de ser una data anterior a :date.',
    'before_or_equal' => 'El camp :attribute ha de ser una data anterior o igual a :date.',

    'between' => [
        'array'   => 'El camp :attribute ha de tenir entre :min y :max elements.',
        'file'    => 'El camp :attribute ha de tenir entre :min y :max kilobytes.',
        'numeric' => 'El camp :attribute ha d\'estar entre :min y :max.',
        'string'  => 'El camp :attribute ha de tenir entre :min y :max caràcters.',
    ],

    'boolean'           => 'El camp :attribute ha de ser vertader o falso.',
    'can'               => 'El camp :attribute conte un valor no autorizado.',
    'confirmed'         => 'La confirmació del camp :attribute no coincide.',
    'current_password'  => 'La contraseña es incorrecta.',
    'date'              => 'El camp :attribute ha de ser una data vàlida.',
    'date_equals'       => 'El camp :attribute ha de ser una data igual a :date.',
    'date_format'       => 'El camp :attribute ha de coincidir amb el format :format.',
    'decimal'           => 'El camp :attribute ha de tenir :decimal posicions decimals.',
    'declined'          => 'El camp :attribute ha de ser rebutjat.',
    'declined_if'       => 'El camp :attribute ha de ser rebutjat quan :other es :value.',
    'different'         => 'El camp :attribute y :other han de ser diferents.',
    'digits'            => 'El camp :attribute ha de tenir :digits dígits.',
    'digits_between'    => 'El camp :attribute ha de tenir entre :min y :max dígits.',
    'dimensions'        => 'El camp :attribute te dimensions d\'imatge no vàlides.',
    'distinct'          => 'El camp :attribute te un valor duplicat.',
    'doesnt_end_with'   => 'El camp :attribute no ha d\'acabar amb cap dels següents valors: :values.',
    'doesnt_start_with' => 'El camp :attribute no ha de començar amb cap dels següents valors: :values.',
    'email'             => 'El camp :attribute ha de ser una adreça de correu electrònic vàlida.',
    'ends_with'         => 'El camp :attribute ha d\'acabar amb un dels següents valors: :values.',
    'enum'              => 'L\'atribut :attribute sel·leccionat es invàlid.',
    'exists'            => 'L\'atribut :attribute sel·leccionat es invàlid.',
    'extensions'        => 'El camp :attribute ha de tenir una de las següents extensions: :values.',
    'file'              => 'El camp :attribute ha de ser un arxiu.',
    'filled'            => 'El camp :attribute ha de tenir un valor.',

    'gt' => [
        'array'   => 'El camp :attribute ha de tenir més de :value elements.',
        'file'    => 'El camp :attribute ha de ser més gran que :value kilobytes.',
        'numeric' => 'El camp :attribute ha de ser més gran que :value.',
        'string'  => 'El camp :attribute ha de tenir més de :value caràcters.',
    ],

    'gte' => [
        'array'   => 'El camp :attribute ha de tenir :value elements o més.',
        'file'    => 'El camp :attribute ha de ser més gran o igual que :value kilobytes.',
        'numeric' => 'El camp :attribute ha de ser més gran o igual que :value.',
        'string'  => 'El camp :attribute ha de tenir :value caràcters o més.',
    ],

    'hex_color' => 'El camp :attribute ha de ser un color hexadecimal válido.',
    'image'     => 'El camp :attribute ha de ser una  .',
    'in'        => 'El :attribute sel·leccionat es invàlid.',
    'in_array'  => 'El camp :attribute ha d\'existir en :other.',
    'integer'   => 'El camp :attribute ha de ser un número enter.',
    'ip'        => 'El camp :attribute ha de ser una adreça IP vàlida.',
    'ipv4'      => 'El camp :attribute ha de ser una adreça IPv4 vàlida.',
    'ipv6'      => 'El camp :attribute ha de ser una adreça IPv6 vàlida.',
    'json'      => 'El camp :attribute ha de ser una cadena JSON vàlida.',
    'lowercase' => 'El camp :attribute ha d\'estar en minúsculas.',

    'lt' => [
        'array'   => 'El camp :attribute ha de tenir menys de :value elements.',
        'file'    => 'El camp :attribute ha de ser menor que :value kilobytes.',
        'numeric' => 'El camp :attribute ha de ser menor que :value.',
        'string'  => 'El camp :attribute ha de tenir menys de :value caràcters.',
    ],

    'lte' => [
        'array'   => 'El camp :attribute no ha de tenir més de :value elements.',
        'file'    => 'El camp :attribute ha de ser menor o igual que :value kilobytes.',
        'numeric' => 'El camp :attribute ha de ser menor o igual que :value.',
        'string'  => 'El camp :attribute ha de ser menor o igual que :value caràcters.',
    ],

    'mac_address' => 'El camp :attribute ha de ser una adreça MAC vàlida.',

    'max' => [
        'array'   => 'El camp :attribute no ha de tenir més de :max elements.',
        'file'    => 'El camp :attribute no ha de ser més gran que :max kilobytes.',
        'numeric' => 'El camp :attribute no ha de ser més gran que :max.',
        'string'  => 'El camp :attribute no ha de ser més gran que :max caràcters.',
    ],

    'max_digits'       => 'El camp :attribute no ha de tenir més de :max dígitos.',
    'mimes'            => 'El camp :attribute ha de ser un archivo de tipo: :values.',
    'mimetypes'        => 'El camp :attribute ha de ser un archivo de tipo: :values.',

    'min' => [
        'array'   => 'El camp :attribute ha de tenir al menys :min elements.',
        'file'    => 'El camp :attribute ha de ser de al menys :min kilobytes.',
        'numeric' => 'El camp :attribute ha de ser de al menys :min.',
        'string'  => 'El camp :attribute ha de tenir al menys :min caràcters.',
    ],

    'min_digits'       => 'El camp :attribute ha de tenir al menys :min dígits.',
    'missing'          => 'El camp :attribute ha de faltar.',
    'missing_if'       => 'El camp :attribute ha de faltar quan :other es :value.',
    'missing_unless'   => 'El camp :attribute ha de faltar a menys que :other sea :value.',
    'missing_with'     => 'El camp :attribute ha de faltar quan :values està present.',
    'missing_with_all' => 'El camp :attribute ha de faltar quan :values estàn presents.',
    'multiple_of'      => 'El camp :attribute ha de ser un múltiple de :value.',
    'not_in'           => 'El :attribute sel·leccionat es invàlid.',
    'not_regex'        => 'El format del camp :attribute es invàlid.',
    'numeric'          => 'El camp :attribute ha de ser un número.',

    'password' => [
        'letters'       => 'El camp :attribute ha de contenir al menys una letra.',
        'mixed'         => 'El camp :attribute ha de contenir al menys una letra majscula y una minúscula.',
        'numbers'       => 'El camp :attribute ha de contenir al menys un número.',
        'symbols'       => 'El camp :attribute ha de contenir al menys un símbol.',
        'uncompromised' => 'L\'atribut :attribute donat ha aparegut en una filtració de dades. Si us plau, trii un :attribute diferent.',
    ],

    'present'              => 'El camp :attribute ha d\'estar present.',
    'present_if'           => 'El camp :attribute ha d\'estar present quan :other es :value.',
    'present_unless'       => 'El camp :attribute ha d\'estar present a menys que :other sea :value.',
    'present_with'         => 'El camp :attribute ha d\'estar present quan :values està present.',
    'present_with_all'     => 'El camp :attribute ha d\'estar present quan :values estàn presents.',
    'prohibited'           => 'El camp :attribute està prohibit.',
    'prohibited_if'        => 'El camp :attribute està prohibit quan :other es :value.',
    'prohibited_unless'    => 'El camp :attribute està prohibit a menys que :other esté en :values.',
    'prohibits'            => 'El camp :attribute prohíbe que :other esté present.',
    'regex'                => 'El formato del camp :attribute es invàlid.',
    'required'             => 'El camp :attribute es obligatòri.',
    'required_array_keys'  => 'El camp :attribute ha de contenir entrades para: :values.',
    'required_if'          => 'El camp :attribute es obligatòri quan :other es :value.',
    'required_if_accepted' => 'El camp :attribute es obligatòri quan :other es acceptat.',
    'required_unless'      => 'El camp :attribute es obligatòri a menys que :other esté en :values.',
    'required_with'        => 'El camp :attribute es obligatòri quan :values està present.',
    'required_with_all'    => 'El camp :attribute es obligatòri quan :values están presents.',
    'required_without'     => 'El camp :attribute es obligatòri quan :values no està present.',
    'required_without_all' => 'El camp :attribute es obligatòri quan cap dels :values està present.',
    'same'                 => 'El camp :attribute ha de coincidir amb :other.',

    'size' => [
        'array'   => 'El camp :attribute ha de contenir :size elements.',
        'file'    => 'El camp :attribute ha de tenir :size kilobytes.',
        'numeric' => 'El camp :attribute ha de ser :size.',
        'string'  => 'El camp :attribute ha de tenir :size caràcters.',
    ],

    'starts_with' => 'El camp :attribute ha de empezar amb uno dels següents valores: :values.',
    'string'      => 'El camp :attribute ha de ser una cadena de texto.',
    'timezone'    => 'El camp :attribute ha de ser una zona horaria vàlida.',
    'unique'      => 'L\'atribut :attribute ya ha sido tomado.',
    'uploaded'    => 'La càrrerga de l\'atribut :attribute falló.',
    'uppercase'   => 'El camp :attribute ha d\'estar en majúscules.',
    'url'         => 'El camp :attribute ha de ser una URL vàlida.',
    'ulid'        => 'El camp :attribute ha de ser un ULID válid.',
    'uuid'        => 'El camp :attribute ha de ser un UUID válid.',

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
            'rule-name' => 'missatge personalitzat',
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
