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

    'accepted'        => 'Het veld :attribute moet worden geaccepteerd.',
    'accepted_if'     => 'Het veld :attribute moet worden geaccepteerd wanneer :other :value is.',
    'active_url'      => 'Het veld :attribute moet een geldige URL zijn.',
    'after'           => 'Het veld :attribute moet een datum zijn na :date.',
    'after_or_equal'  => 'Het veld :attribute moet een datum zijn na of gelijk aan :date.',
    'alpha'           => 'Het veld :attribute mag alleen letters bevatten.',
    'alpha_dash'      => 'Het veld :attribute mag alleen letters, cijfers, streepjes en underscores bevatten.',
    'alpha_num'       => 'Het veld :attribute mag alleen letters en cijfers bevatten.',
    'array'           => 'Het veld :attribute moet een array zijn.',
    'ascii'           => 'Het veld :attribute mag alleen enkel-byte alfanumerieke tekens en symbolen bevatten.',
    'before'          => 'Het veld :attribute moet een datum zijn voor :date.',
    'before_or_equal' => 'Het veld :attribute moet een datum zijn voor of gelijk aan :date.',

    'between' => [
        'array'   => 'Het veld :attribute moet tussen :min en :max items bevatten.',
        'file'    => 'Het veld :attribute moet tussen :min en :max kilobytes zijn.',
        'numeric' => 'Het veld :attribute moet tussen :min en :max zijn.',
        'string'  => 'Het veld :attribute moet tussen :min en :max karakters zijn.',
    ],

    'boolean'           => 'Het veld :attribute moet waar of onwaar zijn.',
    'can'               => 'Het veld :attribute bevat een ongeautoriseerde waarde.',
    'confirmed'         => 'De bevestiging van het veld :attribute komt niet overeen.',
    'current_password'  => 'Het wachtwoord is onjuist.',
    'date'              => 'Het veld :attribute moet een geldige datum zijn.',
    'date_equals'       => 'Het veld :attribute moet een datum zijn gelijk aan :date.',
    'date_format'       => 'Het veld :attribute moet overeenkomen met het formaat :format.',
    'decimal'           => 'Het veld :attribute moet :decimal decimalen hebben.',
    'declined'          => 'Het veld :attribute moet worden afgewezen.',
    'declined_if'       => 'Het veld :attribute moet worden afgewezen wanneer :other :value is.',
    'different'         => 'Het veld :attribute en :other moeten verschillend zijn.',
    'digits'            => 'Het veld :attribute moet :digits cijfers zijn.',
    'digits_between'    => 'Het veld :attribute moet tussen :min en :max cijfers zijn.',
    'dimensions'        => 'Het veld :attribute heeft ongeldige afbeeldingsafmetingen.',
    'distinct'          => 'Het veld :attribute heeft een dubbele waarde.',
    'doesnt_end_with'   => 'Het veld :attribute mag niet eindigen met een van de volgende waarden: :values.',
    'doesnt_start_with' => 'Het veld :attribute mag niet beginnen met een van de volgende waarden: :values.',
    'email'             => 'Het veld :attribute moet een geldig e-mailadres zijn.',
    'ends_with'         => 'Het veld :attribute moet eindigen met een van de volgende waarden: :values.',
    'enum'              => 'De geselecteerde :attribute is ongeldig.',
    'exists'            => 'De geselecteerde :attribute is ongeldig.',
    'extensions'        => 'Het veld :attribute moet een van de volgende extensies hebben: :values.',
    'file'              => 'Het veld :attribute moet een bestand zijn.',
    'filled'            => 'Het veld :attribute moet een waarde hebben.',

    'gt' => [
        'array'   => 'Het veld :attribute moet meer dan :value items bevatten.',
        'file'    => 'Het veld :attribute moet groter zijn dan :value kilobytes.',
        'numeric' => 'Het veld :attribute moet groter zijn dan :value.',
        'string'  => 'Het veld :attribute moet meer dan :value karakters bevatten.',
    ],

    'gte' => [
        'array'   => 'Het veld :attribute moet :value items of meer bevatten.',
        'file'    => 'Het veld :attribute moet groter dan of gelijk aan :value kilobytes zijn.',
        'numeric' => 'Het veld :attribute moet groter dan of gelijk aan :value zijn.',
        'string'  => 'Het veld :attribute moet groter dan of gelijk aan :value karakters zijn.',
    ],

    'hex_color' => 'Het veld :attribute moet een geldige hexadecimale kleur zijn.',
    'image'     => 'Het veld :attribute moet een afbeelding zijn.',
    'in'        => 'De geselecteerde :attribute is ongeldig.',
    'in_array'  => 'Het veld :attribute moet bestaan in :other.',
    'integer'   => 'Het veld :attribute moet een geheel getal zijn.',
    'ip'        => 'Het veld :attribute moet een geldig IP-adres zijn.',
    'ipv4'      => 'Het veld :attribute moet een geldig IPv4-adres zijn.',
    'ipv6'      => 'Het veld :attribute moet een geldig IPv6-adres zijn.',
    'json'      => 'Het veld :attribute moet een geldige JSON-string zijn.',
    'lowercase' => 'Het veld :attribute moet in kleine letters zijn.',

    'lt' => [
        'array'   => 'Het veld :attribute moet minder dan :value items bevatten.',
        'file'    => 'Het veld :attribute moet kleiner zijn dan :value kilobytes.',
        'numeric' => 'Het veld :attribute moet kleiner zijn dan :value.',
        'string'  => 'Het veld :attribute moet minder dan :value karakters bevatten.',
    ],

    'lte' => [
        'array'   => 'Het veld :attribute mag niet meer dan :value items bevatten.',
        'file'    => 'Het veld :attribute moet kleiner dan of gelijk aan :value kilobytes zijn.',
        'numeric' => 'Het veld :attribute moet kleiner dan of gelijk aan :value zijn.',
        'string'  => 'Het veld :attribute moet kleiner dan of gelijk aan :value karakters zijn.',
    ],

    'mac_address' => 'Het veld :attribute moet een geldig MAC-adres zijn.',

    'max' => [
        'array'   => 'Het veld :attribute mag niet meer dan :max items bevatten.',
        'file'    => 'Het veld :attribute mag niet groter zijn dan :max kilobytes.',
        'numeric' => 'Het veld :attribute mag niet groter zijn dan :max.',
        'string'  => 'Het veld :attribute mag niet groter zijn dan :max karakters.',
    ],

    'max_digits' => 'Het veld :attribute mag niet meer dan :max cijfers bevatten.',
    'mimes'      => 'Het veld :attribute moet een bestand zijn van het type: :values.',
    'mimetypes'  => 'Het veld :attribute moet een bestand zijn van het type: :values.',

    'min' => [
        'array'   => 'Het veld :attribute moet ten minste :min items bevatten.',
        'file'    => 'Het veld :attribute moet ten minste :min kilobytes zijn.',
        'numeric' => 'Het veld :attribute moet ten minste :min zijn.',
        'string'  => 'Het veld :attribute moet ten minste :min karakters bevatten.',
    ],

    'min_digits'       => 'Het veld :attribute moet minimaal :min cijfers bevatten.',
    'missing'          => 'Het veld :attribute moet ontbreken.',
    'missing_if'       => 'Het veld :attribute moet ontbreken wanneer :other :value is.',
    'missing_unless'   => 'Het veld :attribute moet ontbreken tenzij :other :value is.',
    'missing_with'     => 'Het veld :attribute moet ontbreken wanneer :values aanwezig is.',
    'missing_with_all' => 'Het veld :attribute moet ontbreken wanneer :values aanwezig zijn.',
    'multiple_of'      => 'Het veld :attribute moet een veelvoud zijn van :value.',
    'not_in'           => 'De geselecteerde :attribute is ongeldig.',
    'not_regex'        => 'Het formaat van het veld :attribute is ongeldig.',
    'numeric'          => 'Het veld :attribute moet een nummer zijn.',

    'password' => [
        'letters'       => 'Het veld :attribute moet minimaal één letter bevatten.',
        'mixed'         => 'Het veld :attribute moet minimaal één hoofdletter en één kleine letter bevatten.',
        'numbers'       => 'Het veld :attribute moet minimaal één nummer bevatten.',
        'symbols'       => 'Het veld :attribute moet minimaal één symbool bevatten.',
        'uncompromised' => 'Het opgegeven :attribute is verschenen in een datalek. Kies alstublieft een ander :attribute.',
    ],

    'present'              => 'Het veld :attribute moet aanwezig zijn.',
    'present_if'           => 'Het veld :attribute moet aanwezig zijn wanneer :other :value is.',
    'present_unless'       => 'Het veld :attribute moet aanwezig zijn tenzij :other :value is.',
    'present_with'         => 'Het veld :attribute moet aanwezig zijn wanneer :values aanwezig is.',
    'present_with_all'     => 'Het veld :attribute moet aanwezig zijn wanneer :values aanwezig zijn.',
    'prohibited'           => 'Het veld :attribute is verboden.',
    'prohibited_if'        => 'Het veld :attribute is verboden wanneer :other :value is.',
    'prohibited_unless'    => 'Het veld :attribute is verboden tenzij :other :values is.',
    'prohibits'            => 'Het veld :attribute verbiedt :other om aanwezig te zijn.',
    'regex'                => 'Het formaat van het veld :attribute is ongeldig.',
    'required'             => 'Het veld :attribute is verplicht.',
    'required_array_keys'  => 'Het veld :attribute moet invoergegevens bevatten voor: :values.',
    'required_if'          => 'Het veld :attribute is verplicht wanneer :other :value is.',
    'required_if_accepted' => 'Het veld :attribute is verplicht wanneer :other is geaccepteerd.',
    'required_unless'      => 'Het veld :attribute is verplicht tenzij :other in :values is.',
    'required_with'        => 'Het veld :attribute is verplicht wanneer :values aanwezig is.',
    'required_with_all'    => 'Het veld :attribute is verplicht wanneer :values aanwezig zijn.',
    'required_without'     => 'Het veld :attribute is verplicht wanneer :values niet aanwezig is.',
    'required_without_all' => 'Het veld :attribute is verplicht wanneer geen van :values aanwezig is.',
    'same'                 => 'Het veld :attribute moet overeenkomen met :other.',

    'size' => [
        'array'   => 'Het veld :attribute moet :size items bevatten.',
        'file'    => 'Het veld :attribute moet :size kilobytes zijn.',
        'numeric' => 'Het veld :attribute moet :size zijn.',
        'string'  => 'Het veld :attribute moet :size karakters bevatten.',
    ],

    'starts_with' => ':attribute moet beginnen met een van de volgende waarden: :values.',
    'string'      => ':attribute moet een string zijn.',
    'timezone'    => ':attribute moet een geldige tijdzone zijn.',
    'unique'      => ':attribute is al in gebruik.',
    'uploaded'    => 'Het uploaden van :attribute is mislukt.',
    'uppercase'   => ':attribute moet hoofdletters bevatten.',
    'url'         => ':attribute moet een geldige URL zijn.',
    'ulid'        => ':attribute moet een geldige ULID zijn.',
    'uuid'        => ':attribute moet een geldige UUID zijn.',

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
            'rule-name' => 'aangepast bericht',
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
