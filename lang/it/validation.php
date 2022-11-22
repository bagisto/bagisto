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

    'accepted'             => "L'attributo :attribute deve essere accettato.",
    'active_url'           => "L'attributo :attribute non è un URL valido.",
    'after'                => "L'attributo :attribute deve essere una data successiva a :date.",
    'after_or_equal'       => "L'attributo :attribute deve essere una data successiva o uguale a :date.",
    'alpha'                => "L'attributo :attribute può contenere solo lettere.",
    'alpha_dash'           => "L'attributo :attribute può contenere solo lettere, numeri, trattini e trattini bassi.",
    'alpha_num'            => "L'attributo :attribute può contenere solo lettere e numeri.",
    'array'                => "L'attributo :attribute deve essere un array.",
    'before'               => "L'attributo :attribute deve essere una data precedente a :date.",
    'before_or_equal'      => "L'attributo :attribute deve essere una data precedente o uguale a :date.",
    'between'              => [
        'numeric' => "L'attributo :attribute deve essere una data tra :min e :max.",
        'file'    => "L'attributo :attribute deve essere una data tra :min e :max kilobytes.",
        'string'  => "L'attributo :attribute deve essere una data tra :min e :max caratteri.",
        'array'   => "L'attributo :attribute deve essere una data tra :min e :max elementi.",
    ],
    'boolean'              => "Il campo :attribute deve essere vero o falso.",
    'confirmed'            => "La conferma di :attribute non coincide.",
    'date'                 => "L'attributo :attribute non è una data valida.",
    'date_format'          => "L'attributo :attribute non coincide col formato :format.",
    'different'            => ':attribute e :other devono essere diversi.',
    'digits'               => "L'attributo :attribute deve avere :digits numeri.",
    'digits_between'       => "L'attributo :attribute deve avere tra :min e :max numeri.",
    'dimensions'           => "L'attributo :attribute ha delle dimensioni immagine non valide.",
    'distinct'             => 'Il campo :attribute ha un valore duplicato.',
    'email'                => "L'attributo :attribute deve essere un indirizzo email valido.",
    'exists'               => "L'attributo :attribute selezionato non è valido.",
    'file'                 => "L'attributo :attribute deve essere un file.",
    'filled'               => 'Il campo :attribute deve avere un valore.',
    'gt'                   => [
        'numeric' => "L'attributo :attribute deve essere più grande di :value.",
        'file'    => "L'attributo :attribute deve essere più grande di :value kilobytes.",
        'string'  => "L'attributo :attribute deve essere più di :value caratteri.",
        'array'   => "L'attributo :attribute deve avere più di :value elementi.",
    ],
    'gte'                  => [
        'numeric' => "L'attributo :attribute deve essere più grande o uguale a :value.",
        'file'    => "L'attributo :attribute deve essere più grande o uguale a :value kilobytes.",
        'string'  => "L'attributo :attribute deve essere più grande o uguale a :value caratteri.",
        'array'   => "L'attributo :attribute deve avere :value elementi o più.",
    ],
    'image'                => "L'attributo :attribute deve essere un'immagine.",
    'in'                   => "L'attributo selezionato :attribute non è valido.",
    'in_array'             => 'Il campo :attribute non esiste in :other.',
    'integer'              => "L'attributo :attribute deve essere un intero.",
    'ip'                   => "L'attributo :attribute deve essere un indirizzo IP valido.",
    'ipv4'                 => "L'attributo :attribute deve essere un indirizzo IPv4 valido.",
    'ipv6'                 => "L'attributo :attribute deve essere un indirizzo IPv6 valido.",
    'json'                 => "L'attributo :attribute deve essere una stringa JSON valida.",
    'lt'                   => [
        'numeric' => "L'attributo :attribute deve essere minore di :value.",
        'file'    => "L'attributo :attribute deve essere minore di :value kilobytes.",
        'string'  => "L'attributo :attribute deve essere minore di :value caratteri.",
        'array'   => "L'attributo :attribute deve avere meno di :value elementi.",
    ],
    'lte'                  => [
        'numeric' => "L'attributo :attribute deve essere minore o uguale a :value.",
        'file'    => "L'attributo :attribute deve essere minore o uguale a :value kilobytes.",
        'string'  => "L'attributo :attribute deve essere minore o uguale a :value caratteri.",
        'array'   => "L'attributo :attribute non deve avere più di :value elementi.",
    ],
    'max'                  => [
        'numeric' => "L'attributo :attribute non deve essere più grande di :max.",
        'file'    => "L'attributo :attribute non deve essere più grande di :max kilobytes.",
        'string'  => "L'attributo :attribute non deve essere più grande di :max caratteri.",
        'array'   => "L'attributo :attribute non deve avere più di :max elementi.",
    ],
    'mimes'                => "L'attributo :attribute deve essere un file di tipo: :values.",
    'mimetypes'            => "L'attributo :attribute deve essere un file di tipo: :values.",
    'min'                  => [
        'numeric' => "L'attributo :attribute deve essere almeno :min.",
        'file'    => "L'attributo :attribute deve avere almeno :min kilobytes.",
        'string'  => "L'attributo :attribute deve essere almeno di :min caratteri.",
        'array'   => "L'attributo :attribute deve avere almento :min elementi.",
    ],
    'not_in'               => "L'attributo selezionato :attribute non è valido.",
    'not_regex'            => "Il formato dell'attributo :attribute non è valido.",
    'numeric'              => "L'attributo :attribute deve essere un numero.",
    'present'              => 'Il campo :attribute deve essere presente.',
    'regex'                => "Il formato dell'attributo :attribute non è valido.",
    'required'             => 'Il campo :attribute è richiesto.',
    'required_if'          => 'Il campo :attribute è richiesto quando :other è :value.',
    'required_unless'      => 'Il campo :attribute è richiesto se :other non è in :values.',
    'required_with'        => 'Il campo :attribute è richiesto se :values è presente.',
    'required_with_all'    => 'Il campo :attribute è richiesto se :values è presente.',
    'required_without'     => 'Il campo :attribute è richiesto se :values non è presente.',
    'required_without_all' => 'Il campo :attribute è richiesto se nessuno tra :values è presente.',
    'same'                 => 'Gli attributi :attribute e :other devono coincidere.',
    'size'                 => [
        'numeric' => "L'attributo :attribute deve essere :size.",
        'file'    => "L'attributo :attribute deve essere di :size kilobytes.",
        'string'  => "L'attributo :attribute deve essere di :size caratteri.",
        'array'   => "L'attributo :attribute deve contenere :size elementi.",
    ],
    'string'               => "L'attributo :attribute deve essere una stringa.",
    'timezone'             => "L'attributo :attribute deve essere una zona valida.",
    'unique'               => "L'attributo :attribute è già stato preso.",
    'uploaded'             => "L'attributo :attribute ha fallito il caricamento.",
    'url'                  => "Il formato dell'attributo :attribute non è valido.",

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
            'rule-name' => 'messaggio-personalizzato',
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
