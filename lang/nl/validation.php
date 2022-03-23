<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages.
    |
    */

    'accepted'             => ':attribute moet geaccepteerd zijn.',
    'active_url'           => ':attribute is geen geldige URL.',
    'after'                => ':attribute moet een datum na :date zijn.',
    'after_or_equal'       => ':attribute moet een datum na of gelijk aan :date zijn.',
    'alpha'                => ':attribute mag alleen letters bevatten.',
    'alpha_dash'           => ':attribute mag alleen letters, nummers, underscores (_) en streepjes (-) bevatten.',
    'alpha_num'            => ':attribute mag alleen letters en nummers bevatten.',
    'array'                => ':attribute moet geselecteerde elementen bevatten.',
    'before'               => ':attribute moet een datum voor :date zijn.',
    'before_or_equal'      => ':attribute moet een datum voor of gelijk aan :date zijn.',
    'between'              => [
        'numeric' => ':attribute moet tussen :min en :max zijn.',
        'file'    => ':attribute moet tussen :min en :max kilobytes zijn.',
        'string'  => ':attribute moet tussen :min en :max karakters zijn.',
        'array'   => ':attribute moet tussen :min en :max items bevatten.',
    ],
    'boolean'              => ':attribute moet ja of nee zijn.',
    'confirmed'            => ':attribute bevestiging komt niet overeen.',
    'date'                 => ':attribute moet een datum bevatten.',
    'date_format'          => ':attribute moet een geldig datum formaat bevatten.',
    'different'            => ':attribute en :other moeten verschillend zijn.',
    'digits'               => ':attribute moet bestaan uit :digits cijfers.',
    'digits_between'       => ':attribute moet bestaan uit minimaal :min en maximaal :max cijfers.',
    'dimensions'           => ':attribute heeft geen geldige afmetingen voor afbeeldingen.',
    'distinct'             => ':attribute heeft een dubbele waarde.',
    'email'                => ':attribute is geen geldig e-mailadres.',
    'exists'               => ':attribute bestaat niet.',
    'file'                 => ':attribute moet een bestand zijn.',
    'filled'               => ':attribute is verplicht.',
    'gt'                   => [
        'numeric' => 'De :attribute moet groter zijn dan :value.',
        'file'    => 'De :attribute moet groter zijn dan :value kilobytes.',
        'string'  => 'De :attribute moet meer dan :value tekens bevatten.',
        'array'   => 'De :attribute moet meer dan :value waardes bevatten.',
    ],
    'gte'                  => [
        'numeric' => 'De :attribute moet groter of gelijk zijn aan :value.',
        'file'    => 'De :attribute moet groter of gelijk zijn aan :value kilobytes.',
        'string'  => 'De :attribute moet minimaal :value tekens bevatten.',
        'array'   => 'De :attribute moet :value waardes of meer bevatten.',
    ],
    'image'                => ':attribute moet een afbeelding zijn.',
    'in'                   => ':attribute is ongeldig.',
    'in_array'             => ':attribute bestaat niet in :other.',
    'integer'              => ':attribute moet een getal zijn.',
    'ip'                   => ':attribute moet een geldig IP-adres zijn.',
    'ipv4'                 => ':attribute moet een geldig IPv4-adres zijn.',
    'ipv6'                 => ':attribute moet een geldig IPv6-adres zijn.',
    'json'                 => ':attribute moet een geldige JSON-string zijn.',
    'lt'                   => [
        'numeric' => 'De :attribute moet kleiner zijn dan :value.',
        'file'    => 'De :attribute moet kleiner zijn dan :value kilobytes.',
        'string'  => 'De :attribute moet minder dan :value tekens bevatten.',
        'array'   => 'De :attribute moet minder dan :value waardes bevatten.',
    ],
    'lte'                  => [
        'numeric' => 'De :attribute moet kleiner of gelijk zijn aan :value.',
        'file'    => 'De :attribute moet kleiner of gelijk zijn aan :value kilobytes.',
        'string'  => 'De :attribute moet maximaal :value tekens bevatten.',
        'array'   => 'De :attribute moet :value waardes of minder bevatten.',
    ],
    'max'                  => [
        'numeric' => ':attribute mag niet hoger dan :max zijn.',
        'file'    => ':attribute mag niet meer dan :max kilobytes zijn.',
        'string'  => ':attribute mag niet uit meer dan :max tekens bestaan.',
        'array'   => ':attribute mag niet meer dan :max items bevatten.',
    ],
    'mimes'                => ':attribute moet een bestand zijn van het bestandstype :values.',
    'mimetypes'            => ':attribute moet een bestand zijn van het bestandstype :values.',
    'min'                  => [
        'numeric' => ':attribute moet minimaal :min zijn.',
        'file'    => ':attribute moet minimaal :min kilobytes zijn.',
        'string'  => ':attribute moet minimaal :min tekens zijn.',
        'array'   => ':attribute moet minimaal :min items bevatten.',
    ],
    'not_in'               => 'Het formaat van :attribute is ongeldig.',
    'not_regex'            => 'De :attribute formaat is ongeldig.',
    'numeric'              => ':attribute moet een nummer zijn.',
    'present'              => ':attribute moet bestaan.',
    'regex'                => ':attribute formaat is ongeldig.',
    'required'             => ':attribute is verplicht.',
    'required_if'          => ':attribute is verplicht indien :other gelijk is aan :value.',
    'required_unless'      => ':attribute is verplicht tenzij :other gelijk is aan :values.',
    'required_with'        => ':attribute is verplicht i.c.m. :values',
    'required_with_all'    => ':attribute is verplicht i.c.m. :values',
    'required_without'     => ':attribute is verplicht als :values niet ingevuld is.',
    'required_without_all' => ':attribute is verplicht als :values niet ingevuld zijn.',
    'same'                 => ':attribute en :other moeten overeenkomen.',
    'size'                 => [
        'numeric' => ':attribute moet :size zijn.',
        'file'    => ':attribute moet :size kilobyte zijn.',
        'string'  => ':attribute moet :size tekens zijn.',
        'array'   => ':attribute moet :size items bevatten.',
    ],
    'starts_with'          => ':attribute moet starten met een van de volgende: :values',
    'string'               => ':attribute moet een tekst zijn.',
    'timezone'             => ':attribute moet een geldige tijdzone zijn.',
    'unique'               => ':attribute is al in gebruik.',
    'uploaded'             => 'Het uploaden van :attribute is mislukt.',
    'url'                  => ':attribute moet een geldig URL zijn.',
    'uuid'                 => ':attribute moet een geldig UUID zijn.',

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

    'attributes' => [
        'address'               => 'adres',
        'age'                   => 'leeftijd',
        'available'             => 'beschikbaar',
        'city'                  => 'stad',
        'content'               => 'inhoud',
        'country'               => 'land',
        'date'                  => 'datum',
        'day'                   => 'dag',
        'description'           => 'omschrijving',
        'email'                 => 'e-mailadres',
        'excerpt'               => 'uittreksel',
        'first_name'            => 'voornaam',
        'gender'                => 'geslacht',
        'hour'                  => 'uur',
        'last_name'             => 'achternaam',
        'message'               => 'boodschap',
        'minute'                => 'minuut',
        'mobile'                => 'mobiel',
        'month'                 => 'maand',
        'name'                  => 'naam',
        'password'              => 'wachtwoord',
        'password_confirmation' => 'wachtwoordbevestiging',
        'phone'                 => 'telefoonnummer',
        'second'                => 'seconde',
        'sex'                   => 'geslacht',
        'size'                  => 'grootte',
        'subject'               => 'onderwerp',
        'time'                  => 'tijd',
        'title'                 => 'titel',
        'username'              => 'gebruikersnaam',
        'year'                  => 'jaar',
    ],
];
