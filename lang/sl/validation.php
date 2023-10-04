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

    'accepted'             => '":attribute" mora biti sprejet.',
    'active_url'           => '":attribute" ni veljaven URL naslov.',
    'after'                => '":attribute" mora biti datum po :date.',
    'after_or_equal'       => '":attribute" mora biti datum, enak ali kasnejši kot :date.',
    'alpha'                => '":attribute" lahko vsebuje le črke.',
    'alpha_dash'           => '":attribute" lahko vsebuje le črke, številke in pomišljaje.',
    'alpha_num'            => '":attribute" lahko vsebuje le črke in številke.',
    'array'                => '":attribute" mora biti niz elementov.',
    'before'               => '":attribute" mora biti datum pred :date.',
    'before_or_equal'      => '":attribute" mora biti datum pred ali enak datumu :date.',

    'between'              => [
        'numeric' => '":attribute" mora biti med :min in :max.',
        'file'    => '":attribute" mora biti med :min in max: kB.',
        'string'  => '":attribute" mora vsebovati med :min in :max znakov.',
        'array'   => '":attribute" mora vsebovati med :min in :max elementov.',
    ],

    'boolean'              => '":attribute" mora biti Da (true) ali Ne (false).',
    'confirmed'            => 'Potrditev polja ":attribute" se ne ujema.',
    'date'                 => '":attribute" ni veljaven datum.',
    'date_format'          => '":attribute" se ne ujema s formatom :format.',
    'different'            => '":attribute" in :other morata biti različna.',
    'digits'               => '":attribute" mora vsebovati :digits številk.',
    'digits_between'       => '":attribute" mora vsebovati med :min in :max številk.',
    'dimensions'           => '":attribute" vsebuje neveljavne dimenzije slike.',
    'distinct'             => '":attribute" polje ima podvojeno vrednost.',
    'email'                => '":attribute" mora biti veljaven e-poštni naslov.',
    'exists'               => 'Izbran element ":attribute" je neveljaven.',
    'file'                 => '":attribute" mora biti datoteka.',
    'filled'               => '":attribute" polje mora vsebovati vrednost.',

    'gt'                   => [
        'numeric' => ':attribute mora biti večji od :value.',
        'file'    => ':attribute mora biti večji od :value kB.',
        'string'  => ':attribute mora vsebovati več kot :value znakov.',
        'array'   => ':attribute mora vsebovati več kot :value elementov.',
    ],

    'gte'                  => [
        'numeric' => ':attribute mora biti večji ali enak kot :value.',
        'file'    => ':attribute mora biti večji ali enak kot :value kB.',
        'string'  => ':attribute mora vsebovati vsaj ali več kot :value znakov.',
        'array'   => ':attribute mora vsebovati vsaj :value elementov ali več.',
    ],

    'image'                => '":attribute" mora biti slika.',
    'in'                   => 'Izbran element ":attribute" je neveljaven.',
    'in_array'             => 'Element ":attribute" ne obstaja v ":other".',
    'integer'              => '":attribute" mora biti celo število.',
    'ip'                   => '":attribute" mora biti veljaven IP naslov.',
    'ipv4'                 => '":attribute" mora biti veljaven IPv4 naslov.',
    'ipv6'                 => '":attribute" mora biti veljaven IPv6 naslov.',
    'json'                 => '":attribute" mora biti veljaven JSON format.',

    'lt'                   => [
        'numeric' => ':attribute mora biti manjši od :value.',
        'file'    => ':attribute mora biti manjši od :value kB.',
        'string'  => ':attribute mora vsebovati manj kot :value znakov.',
        'array'   => ':attribute mora vsebovati manj kot :value elementov.',
    ],

    'lte'                  => [
        'numeric' => ':attribute mora biti manjši ali enak kot :value.',
        'file'    => ':attribute mora biti manjši ali enak kot :value kB.',
        'string'  => ':attribute mora vsebovati manj ali enako kot :value znakov.',
        'array'   => ':attribute ne sme vsebovati več kot :value elementov.',
    ],

    'max'                  => [
        'numeric' => ':attribute ne sme biti večji od :max.',
        'file'    => ':attribute ne sme biti večji od :max kB.',
        'string'  => ':attribute ne sme biti večji od :max znakov.',
        'array'   => ':attribute ne sme vsebovati več kot :max elementov.',
    ],

    'mimes'                => ':attribute mora biti datoteka tipa :values.',
    'mimetypes'            => ':attribute mora biti datoteka tipa :values.',

    'min'                  => [
        'numeric' => ':attribute mora biti vsaj :min.',
        'file'    => ':attribute mora imeti vsaj :min kB.',
        'string'  => ':attribute mora imeti vsaj :min znakov.',
        'array'   => ':attribute mora vsebovati vsaj :min elementov.',
    ],

    'not_in'               => 'Izbrani element ":attribute" je neveljaven.',
    'not_regex'            => 'Format za ":attribute" je neveljaven.',
    'numeric'              => '":attribute" mora biti število.',
    'present'              => 'Polje za ":attribute" mora obstajati.',
    'regex'                => 'Format za ":attribute" je neveljaven.',
    'required'             => 'Polje za ":attribute" je obvezno.',
    'required_if'          => 'Polje za ":attribute" je obvezno, kadar ima :other vrednost :value.',
    'required_unless'      => 'Polje za ":attribute" je obvezno, razen kadar ima :other vrednosti :values.',
    'required_with'        => 'Polje za ":attribute" je obvezno, kadar :values obstaja.',
    'required_with_all'    => 'Polje za ":attribute" je obvezno, kadar :values obstaja.',
    'required_without'     => 'Polje za ":attribute" je obvezno, kadar :values ne obstaja.',
    'required_without_all' => 'Polje za ":attribute" je obvezno, kadar nobeden od :values ne obstaja.',
    'same'                 => '":attribute" in ":other" se morata ujemati.',

    'size'                 => [
        'numeric' => ':attribute mora biti :size.',
        'file'    => ':attribute mora imeti :size kB.',
        'string'  => ':attribute mora vsebovati :size znakov.',
        'array'   => ':attribute mora vsebovati :size elementov.',
    ],

    'string'               => '":attribute" mora biti veljaven znakovni niz.',
    'timezone'             => '":attribute" mora biti veljavno območje.',
    'unique'               => 'Element ":attribute" je že zaseden.',
    'uploaded'             => 'Elementa ":attribute" ni bilo mogoče naložiti.',
    'url'                  => 'Format za ":attribute" je neveljaven.',

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
