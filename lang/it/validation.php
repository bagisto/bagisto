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

    'accepted'             => 'Il campo :attribute deve essere accettato.',
    'active_url'           => 'Il campo :attribute non è un\'URL valida.',
    'after'                => 'Il campo :attribute deve essere una data successiva al :date.',
    'after_or_equal'       => 'Il campo :attribute deve essere una data successiva o uguale al :date.',
    'alpha'                => 'Il campo :attribute può contenere solamente lettere.',
    'alpha_dash'           => 'Il campo :attribute può contenere solamente lettere, cifre, trattini o underscore.',
    'alpha_num'            => 'Il campo :attribute può contenere solo lettere o cifre.',
    'array'                => 'Il campo :attribute deve essere un array.',
    'before'               => 'Il campo :attribute deve essere una data precedente al :date.',
    'before_or_equal'      => 'Il campo :attribute deve essere una data precedente o uguale al :date.',
    'between'              => [
        'numeric' => 'Il campo :attribute deve essere fra :min e :max.',
        'file'    => 'Il campo :attribute deve essere fra :min e :max kilobyte.',
        'string'  => 'Il campo :attribute deve essere fra :min e :max caratteri.',
        'array'   => 'Il campo :attribute deve avere fra :min e :max valori.',
    ],
    'boolean'              => 'Il campo :attribute deve essere vero o falso.',
    'confirmed'            => 'La conferma del campo :attribute non corrisponde.',
    'date'                 => 'Il campo :attribute non è una data valida.',
    'date_format'          => 'Il campo :attribute non corrisponde al formato :format.',
    'different'            => 'Il campo :attribute e :other devono essere differenti.',
    'digits'               => 'Il campo :attribute deve avere :digits cifre.',
    'digits_between'       => 'Il campo :attribute deve avere fra le :min e le :max cifre.',
    'dimensions'           => 'Il campo :attribute ha dimensioni dell\'immagine invalide.',
    'distinct'             => 'Il campo :attribute ha un valore duplicato.',
    'email'                => 'Il campo :attribute deve essere un indirizzo e-mail valido.',
    'exists'               => 'Il campo selezionato :attribute non è valido.',
    'file'                 => 'Il campo :attribute deve essere un file.',
    'filled'               => 'Il campo :attribute deve avere un valore.',
    'gt'                   => [
        'numeric' => 'Il numero :attribute deve essere superiore a :value.',
        'file'    => 'Il file :attribute deve essere superiore a :value kilobyte.',
        'string'  => 'La stringa :attribute deve essere più lunga di :value caratteri.',
        'array'   => 'Il campo :attribute deve avere più di :value valori.',
    ],
    'gte'                  => [
        'numeric' => 'Il numero :attribute deve essere superiore o uguale a :value.',
        'file'    => 'Il file :attribute deve essere superiore o uguale a :value kilobyte.',
        'string'  => 'La stringa :attribute deve essere lunga almeno :value caratteri.',
        'array'   => 'Il campo :attribute deve avere :value valori o più.',
    ],
    'image'                => 'Il campo :attribute deve essere un\'immagine.',
    'in'                   => 'Il campo selezionato :attribute non è valido.',
    'in_array'             => 'Il campo :attribute non esiste in :other.',
    'integer'              => 'Il campo :attribute deve essere un intero.',
    'ip'                   => 'Il campo :attribute deve essere un indirizzo IP valido.',
    'ipv4'                 => 'Il campo :attribute deve essere un indirizzo IPv4 valido.',
    'ipv6'                 => 'Il campo :attribute deve essere un indirizzo IPv6 valido.',
    'json'                 => 'Il campo :attribute deve essere una stringa JSON valida.',
    'lt'                   => [
        'numeric' => 'Il numero :attribute deve essere inferiore a :value.',
        'file'    => 'Il file :attribute deve essere inferiore a :value kilobyte.',
        'string'  => 'La stringa :attribute deve essere più corta di :value caratteri.',
        'array'   => 'Il campo :attribute deve avere meno di :value valori.',
    ],
    'lte'                  => [
        'numeric' => 'Il numero :attribute deve essere inferiore o uguale a :value.',
        'file'    => 'Il file :attribute deve essere inferiore o uguale a :value kilobyte.',
        'string'  => 'La stringa :attribute deve essere lunga non più di :value caratteri.',
        'array'   => 'Il campo :attribute deve avere al massimo :value valori.',
    ],
    'max'                  => [
        'numeric' => 'Il numero :attribute deve essere al massimo :value.',
        'file'    => 'Il file :attribute deve essere al massimo :value kilobyte.',
        'string'  => 'La stringa :attribute deve essere al massimo di :value caratteri.',
        'array'   => 'Il campo :attribute deve avere al massimo :value valori.',
    ],
    'mimes'                => 'Il campo :attribute deve essere un file di tipo: :values.',
    'mimetypes'            => 'Il campo :attribute deve essere un file di tipo: :values.',
    'max'                  => [
        'numeric' => 'Il numero :attribute deve essere almeno :value.',
        'file'    => 'Il file :attribute deve essere almeno di :value kilobyte.',
        'string'  => 'La stringa :attribute deve essere almeno di :value caratteri.',
        'array'   => 'Il campo :attribute deve avere almeno :value valori.',
    ],
    'not_in'               => 'Il campo selezionato :attribute non è valido.',
    'not_regex'            => 'Il formato del campo :attribute non è valido .',
    'numeric'              => 'Il campo :attribute deve essere un numero.',
    'present'              => 'Il campo :attribute deve essere presente.',
    'regex'                => 'Il formato del campo :attribute non è valido.',
    'required'             => 'Il campo :attribute è obbligatorio.',
    'required_if'          => 'Il campo :attribute è obbligatorio quanto :other è :value.',
    'required_unless'      => 'Il campo :attribute è obbligatorio unless :other è in :values.',
    'required_with'        => 'Il campo :attribute è obbligatorio quanto :values è presente.',
    'required_with_all'    => 'Il campo :attribute è obbligatorio quanto :values è presente.',
    'required_without'     => 'Il campo :attribute è obbligatorio quanto :values non è presente.',
    'required_without_all' => 'Il campo :attribute è obbligatorio quanto nessuno di :values è presente.',
    'same'                 => 'Il campo :attribute e :other devono corrispondere.',
    'size'                 => [
        'numeric' => 'Il numero :attribute deve essere :value.',
        'file'    => 'Il file :attribute deve essere di :value kilobyte.',
        'string'  => 'La stringa :attribute deve essere di :value caratteri.',
        'array'   => 'Il campo :attribute deve avere :value valori.',
    ],
    'string'               => 'Il campo :attribute deve essere una stringa.',
    'timezone'             => 'Il campo :attribute deve essere una timezone valida.',
    'unique'               => 'Il campo :attribute è già stato usato.',
    'uploaded'             => 'Fallito upload di :attribute.',
    'url'                  => 'Il formato del campo :attribute non è valido.',

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

    'attributes' => [],

];
