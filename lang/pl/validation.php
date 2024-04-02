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

    'accepted'        => 'Pole :attribute musi być zaakceptowane.',
    'accepted_if'     => 'Pole :attribute musi być zaakceptowane, gdy :other wynosi :value.',
    'active_url'      => 'Pole :attribute musi być poprawnym adresem URL.',
    'after'           => 'Pole :attribute musi być datą po :date.',
    'after_or_equal'  => 'Pole :attribute musi być datą po lub równą :date.',
    'alpha'           => 'Pole :attribute może zawierać tylko litery.',
    'alpha_dash'      => 'Pole :attribute może zawierać tylko litery, cyfry, myślniki i podkreślenia.',
    'alpha_num'       => 'Pole :attribute może zawierać tylko litery i cyfry.',
    'array'           => 'Pole :attribute musi być tablicą.',
    'ascii'           => 'Pole :attribute może zawierać tylko jednobajtowe znaki alfanumeryczne i symbole.',
    'before'          => 'Pole :attribute musi być datą przed :date.',
    'before_or_equal' => 'Pole :attribute musi być datą przed lub równą :date.',

    'between' => [
        'array'   => 'Pole :attribute musi zawierać od :min do :max elementów.',
        'file'    => 'Plik :attribute musi mieć rozmiar między :min a :max kilobajtów.',
        'numeric' => 'Pole :attribute musi zawierać się między :min a :max.',
        'string'  => 'Pole :attribute musi mieć od :min do :max znaków.',
    ],

    'boolean'           => 'Pole :attribute musi mieć wartość prawda lub fałsz.',
    'can'               => 'Pole :attribute zawiera niedozwoloną wartość.',
    'confirmed'         => 'Potwierdzenie pola :attribute nie pasuje.',
    'current_password'  => 'Hasło jest nieprawidłowe.',
    'date'              => 'Pole :attribute musi być prawidłową datą.',
    'date_equals'       => 'Pole :attribute musi być datą równą :date.',
    'date_format'       => 'Pole :attribute musi być w formacie :format.',
    'decimal'           => 'Pole :attribute musi mieć :decimal miejsc po przecinku.',
    'declined'          => 'Pole :attribute musi być odrzucone.',
    'declined_if'       => 'Pole :attribute musi być odrzucone, gdy :other wynosi :value.',
    'different'         => 'Pole :attribute i :other muszą być różne.',
    'digits'            => 'Pole :attribute musi składać się z :digits cyfr.',
    'digits_between'    => 'Pole :attribute musi mieć od :min do :max cyfr.',
    'dimensions'        => 'Pole :attribute ma nieprawidłowe wymiary obrazu.',
    'distinct'          => 'Pole :attribute ma zduplikowaną wartość.',
    'doesnt_end_with'   => 'Pole :attribute nie może kończyć się jednym z następujących: :values.',
    'doesnt_start_with' => 'Pole :attribute nie może zaczynać się jednym z następujących: :values.',
    'email'             => 'Pole :attribute musi być prawidłowym adresem e-mail.',
    'ends_with'         => 'Pole :attribute musi kończyć się jednym z następujących: :values.',
    'enum'              => 'Wybrany :attribute jest nieprawidłowy.',
    'exists'            => 'Wybrany :attribute jest nieprawidłowy.',
    'extensions'        => 'Pole :attribute musi mieć jedno z następujących rozszerzeń: :values.',
    'file'              => 'Pole :attribute musi być plikiem.',
    'filled'            => 'Pole :attribute musi mieć wartość.',

    'gt' => [
        'array'   => 'Pole :attribute musi zawierać więcej niż :value elementów.',
        'file'    => 'Pole :attribute musi być większe niż :value kilobajtów.',
        'numeric' => 'Pole :attribute musi być większe niż :value.',
        'string'  => 'Pole :attribute musi być dłuższe niż :value znaków.',
    ],

    'gte' => [
        'array'   => 'Pole :attribute musi zawierać :value elementów lub więcej.',
        'file'    => 'Pole :attribute musi być większe lub równe :value kilobajtów.',
        'numeric' => 'Pole :attribute musi być większe lub równe :value.',
        'string'  => 'Pole :attribute musi być dłuższe lub równe :value znaków.',
    ],

    'hex_color' => 'Pole :attribute musi być prawidłowym kodem koloru szesnastkowego.',
    'image'     => 'Pole :attribute musi być obrazem.',
    'in'        => 'Wybrany :attribute jest nieprawidłowy.',
    'in_array'  => 'Pole :attribute musi istnieć w :other.',
    'integer'   => 'Pole :attribute musi być liczbą całkowitą.',
    'ip'        => 'Pole :attribute musi być prawidłowym adresem IP.',
    'ipv4'      => 'Pole :attribute musi być prawidłowym adresem IPv4.',
    'ipv6'      => 'Pole :attribute musi być prawidłowym adresem IPv6.',
    'json'      => 'Pole :attribute musi być prawidłowym ciągiem JSON.',
    'lowercase' => 'Pole :attribute musi być pisane małymi literami.',

    'lt' => [
        'array'   => 'Pole :attribute musi zawierać mniej niż :value elementów.',
        'file'    => 'Pole :attribute musi być mniejsze niż :value kilobajtów.',
        'numeric' => 'Pole :attribute musi być mniejsze niż :value.',
        'string'  => 'Pole :attribute musi być krótsze niż :value znaków.',
    ],

    'lte' => [
        'array'   => 'Pole :attribute nie może zawierać więcej niż :value elementów.',
        'file'    => 'Pole :attribute musi być mniejsze lub równe :value kilobajtów.',
        'numeric' => 'Pole :attribute musi być mniejsze lub równe :value.',
        'string'  => 'Pole :attribute musi być krótsze lub równe :value znaków.',
    ],

    'mac_address' => 'Pole :attribute musi być prawidłowym adresem MAC.',

    'max' => [
        'array'   => 'Pole :attribute nie może zawierać więcej niż :max elementów.',
        'file'    => 'Pole :attribute nie może być większe niż :max kilobajtów.',
        'numeric' => 'Pole :attribute nie może być większe niż :max.',
        'string'  => 'Pole :attribute nie może być dłuższe niż :max znaków.',
    ],

    'max_digits' => 'Pole :attribute nie może zawierać więcej niż :max cyfr.',
    'mimes'      => 'Pole :attribute musi być plikiem typu: :values.',
    'mimetypes'  => 'Pole :attribute musi być plikiem typu: :values.',

    'min' => [
        'array'   => 'Pole :attribute musi zawierać co najmniej :min elementów.',
        'file'    => 'Pole :attribute musi mieć co najmniej :min kilobajtów.',
        'numeric' => 'Pole :attribute musi być co najmniej :min.',
        'string'  => 'Pole :attribute musi mieć co najmniej :min znaków.',
    ],

    'min_digits'       => 'Pole :attribute musi zawierać co najmniej :min cyfr.',
    'missing'          => 'Pole :attribute musi być nieobecne.',
    'missing_if'       => 'Pole :attribute musi być nieobecne, gdy :other ma wartość :value.',
    'missing_unless'   => 'Pole :attribute musi być nieobecne, chyba że :other ma wartość :value.',
    'missing_with'     => 'Pole :attribute musi być nieobecne, gdy :values są obecne.',
    'missing_with_all' => 'Pole :attribute musi być nieobecne, gdy :values są obecne.',
    'multiple_of'      => 'Pole :attribute musi być wielokrotnością :value.',
    'not_in'           => 'Wybrany :attribute jest nieprawidłowy.',
    'not_regex'        => 'Format pola :attribute jest nieprawidłowy.',
    'numeric'          => 'Pole :attribute musi być liczbą.',

    'password' => [
        'letters'       => 'Pole :attribute musi zawierać przynajmniej jedną literę.',
        'mixed'         => 'Pole :attribute musi zawierać przynajmniej jedną wielką literę i jedną małą literę.',
        'numbers'       => 'Pole :attribute musi zawierać przynajmniej jedną liczbę.',
        'symbols'       => 'Pole :attribute musi zawierać przynajmniej jeden symbol.',
        'uncompromised' => 'Podane :attribute pojawiło się w wycieku danych. Proszę wybrać inne :attribute.',
    ],

    'present'              => 'Pole :attribute musi być obecne.',
    'present_if'           => 'Pole :attribute musi być obecne, gdy :other ma wartość :value.',
    'present_unless'       => 'Pole :attribute musi być obecne, chyba że :other ma wartość :value.',
    'present_with'         => 'Pole :attribute musi być obecne, gdy :values są obecne.',
    'present_with_all'     => 'Pole :attribute musi być obecne, gdy :values są obecne.',
    'prohibited'           => 'Pole :attribute jest zabronione.',
    'prohibited_if'        => 'Pole :attribute jest zabronione, gdy :other ma wartość :value.',
    'prohibited_unless'    => 'Pole :attribute jest zabronione, chyba że :other jest w :values.',
    'prohibits'            => 'Pole :attribute zabrania obecności :other.',
    'regex'                => 'Format pola :attribute jest nieprawidłowy.',
    'required'             => 'Pole :attribute jest wymagane.',
    'required_array_keys'  => 'Pole :attribute musi zawierać wpisy dla: :values.',
    'required_if'          => 'Pole :attribute jest wymagane, gdy :other ma wartość :value.',
    'required_if_accepted' => 'Pole :attribute jest wymagane, gdy :other jest zaakceptowane.',
    'required_unless'      => 'Pole :attribute jest wymagane, chyba że :other jest w :values.',
    'required_with'        => 'Pole :attribute jest wymagane, gdy :values są obecne.',
    'required_with_all'    => 'Pole :attribute jest wymagane, gdy :values są obecne.',
    'required_without'     => 'Pole :attribute jest wymagane, gdy :values nie są obecne.',
    'required_without_all' => 'Pole :attribute jest wymagane, gdy żadne z :values nie są obecne.',
    'same'                 => 'Pole :attribute musi być takie samo jak :other.',

    'size' => [
        'array'   => 'Pole :attribute musi zawierać :size elementów.',
        'file'    => 'Pole :attribute musi mieć :size kilobajtów.',
        'numeric' => 'Pole :attribute musi mieć :size.',
        'string'  => 'Pole :attribute musi mieć :size znaków.',
    ],

    'starts_with' => 'Pole :attribute musi zaczynać się od jednego z następujących: :values.',
    'string'      => 'Pole :attribute musi być ciągiem znaków.',
    'timezone'    => 'Pole :attribute musi być prawidłową strefą czasową.',
    'unique'      => ':attribute jest już zajęty.',
    'uploaded'    => 'Wystąpił błąd podczas przesyłania pliku :attribute.',
    'uppercase'   => 'Pole :attribute musi być zapisane wielkimi literami.',
    'url'         => 'Pole :attribute musi być prawidłowym adresem URL.',
    'ulid'        => 'Pole :attribute musi być prawidłowym ULID.',
    'uuid'        => 'Pole :attribute musi być prawidłowym UUID.',

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
            'rule-name' => 'Niestandardowa wiadomość',
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
