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

    'accepted'        => 'Das Feld :attribute muss akzeptiert werden.',
    'accepted_if'     => 'Das Feld :attribute muss akzeptiert werden, wenn :other :value ist.',
    'active_url'      => 'Das Feld :attribute muss eine gültige URL sein.',
    'after'           => 'Das Feld :attribute muss ein Datum nach :date sein.',
    'after_or_equal'  => 'Das Feld :attribute muss ein Datum nach oder gleich :date sein.',
    'alpha'           => 'Das Feld :attribute darf nur Buchstaben enthalten.',
    'alpha_dash'      => 'Das Feld :attribute darf nur Buchstaben, Zahlen, Bindestriche und Unterstriche enthalten.',
    'alpha_num'       => 'Das Feld :attribute darf nur Buchstaben und Zahlen enthalten.',
    'array'           => 'Das Feld :attribute muss ein Array sein.',
    'ascii'           => 'Das Feld :attribute darf nur ein Byte umfassende alphanumerische Zeichen und Symbole enthalten.',
    'before'          => 'Das Feld :attribute muss ein Datum vor :date sein.',
    'before_or_equal' => 'Das Feld :attribute muss ein Datum vor oder gleich :date sein.',

    'between' => [
        'array'   => 'Das Feld :attribute muss zwischen :min und :max Elemente haben.',
        'file'    => 'Das Feld :attribute muss zwischen :min und :max Kilobytes groß sein.',
        'numeric' => 'Das Feld :attribute muss zwischen :min und :max liegen.',
        'string'  => 'Das Feld :attribute muss zwischen :min und :max Zeichen lang sein.',
    ],

    'boolean'           => 'Das Feld :attribute muss true oder false sein.',
    'can'               => 'Das Feld :attribute enthält einen unautorisierten Wert.',
    'confirmed'         => 'Die Bestätigung des Feldes :attribute stimmt nicht überein.',
    'current_password'  => 'Das Passwort ist falsch.',
    'date'              => 'Das Feld :attribute muss ein gültiges Datum sein.',
    'date_equals'       => 'Das Feld :attribute muss ein Datum gleich :date sein.',
    'date_format'       => 'Das Feld :attribute muss das Format :format haben.',
    'decimal'           => 'Das Feld :attribute muss :decimal Dezimalstellen haben.',
    'declined'          => 'Das Feld :attribute muss abgelehnt werden.',
    'declined_if'       => 'Das Feld :attribute muss abgelehnt werden, wenn :other :value ist.',
    'different'         => 'Das Feld :attribute und :other müssen sich unterscheiden.',
    'digits'            => 'Das Feld :attribute muss :digits Ziffern haben.',
    'digits_between'    => 'Das Feld :attribute muss zwischen :min und :max Ziffern haben.',
    'dimensions'        => 'Das Feld :attribute hat ungültige Bildabmessungen.',
    'distinct'          => 'Das Feld :attribute hat einen doppelten Wert.',
    'doesnt_end_with'   => 'Das Feld :attribute darf nicht mit einem der folgenden enden: :values.',
    'doesnt_start_with' => 'Das Feld :attribute darf nicht mit einem der folgenden beginnen: :values.',
    'email'             => 'Das Feld :attribute muss eine gültige E-Mail-Adresse sein.',
    'ends_with'         => 'Das Feld :attribute muss mit einem der folgenden enden: :values.',
    'enum'              => 'Die ausgewählte :attribute ist ungültig.',
    'exists'            => 'Die ausgewählte :attribute ist ungültig.',
    'extensions'        => 'Das Feld :attribute muss eine der folgenden Erweiterungen haben: :values.',
    'file'              => 'Das Feld :attribute muss eine Datei sein.',
    'filled'            => 'Das Feld :attribute muss einen Wert haben.',

    'gt' => [
        'array'   => 'Das Feld :attribute muss mehr als :value Elemente haben.',
        'file'    => 'Das Feld :attribute muss größer als :value Kilobytes sein.',
        'numeric' => 'Das Feld :attribute muss größer als :value sein.',
        'string'  => 'Das Feld :attribute muss mehr als :value Zeichen haben.',
    ],

    'gte' => [
        'array'   => 'Das Feld :attribute muss :value Elemente oder mehr haben.',
        'file'    => 'Das Feld :attribute muss größer oder gleich :value Kilobytes sein.',
        'numeric' => 'Das Feld :attribute muss größer oder gleich :value sein.',
        'string'  => 'Das Feld :attribute muss größer oder gleich :value Zeichen haben.',
    ],

    'hex_color' => 'Das Feld :attribute muss eine gültige hexadezimale Farbe sein.',
    'image'     => 'Das Feld :attribute muss ein Bild sein.',
    'in'        => 'Die ausgewählte :attribute ist ungültig.',
    'in_array'  => 'Das Feld :attribute muss in :other existieren.',
    'integer'   => 'Das Feld :attribute muss eine ganze Zahl sein.',
    'ip'        => 'Das Feld :attribute muss eine gültige IP-Adresse sein.',
    'ipv4'      => 'Das Feld :attribute muss eine gültige IPv4-Adresse sein.',
    'ipv6'      => 'Das Feld :attribute muss eine gültige IPv6-Adresse sein.',
    'json'      => 'Das Feld :attribute muss ein gültiger JSON-String sein.',
    'lowercase' => 'Das Feld :attribute muss in Kleinbuchstaben sein.',

    'lt' => [
        'array'   => 'Das Feld :attribute muss weniger als :value Elemente haben.',
        'file'    => 'Das Feld :attribute muss kleiner als :value Kilobytes sein.',
        'numeric' => 'Das Feld :attribute muss kleiner als :value sein.',
        'string'  => 'Das Feld :attribute muss weniger als :value Zeichen haben.',
    ],

    'lte' => [
        'array'   => 'Das Feld :attribute darf nicht mehr als :value Elemente haben.',
        'file'    => 'Das Feld :attribute muss kleiner oder gleich :value Kilobytes sein.',
        'numeric' => 'Das Feld :attribute muss kleiner oder gleich :value sein.',
        'string'  => 'Das Feld :attribute muss kleiner oder gleich :value Zeichen haben.',
    ],

    'mac_address' => 'Das Feld :attribute muss eine gültige MAC-Adresse sein.',

    'max' => [
        'array'   => 'Das Feld :attribute darf nicht mehr als :max Elemente haben.',
        'file'    => 'Das Feld :attribute darf nicht größer als :max Kilobytes sein.',
        'numeric' => 'Das Feld :attribute darf nicht größer als :max sein.',
        'string'  => 'Das Feld :attribute darf nicht größer als :max Zeichen sein.',
    ],

    'max_digits' => 'Das Feld :attribute darf nicht mehr als :max Ziffern haben.',
    'mimes'      => 'Das Feld :attribute muss eine Datei vom Typ: :values sein.',
    'mimetypes'  => 'Das Feld :attribute muss eine Datei vom Typ: :values sein.',

    'min' => [
        'array'   => 'Das Feld :attribute muss mindestens :min Elemente haben.',
        'file'    => 'Das Feld :attribute muss mindestens :min Kilobytes groß sein.',
        'numeric' => 'Das Feld :attribute muss mindestens :min sein.',
        'string'  => 'Das Feld :attribute muss mindestens :min Zeichen haben.',
    ],

    'min_digits'       => 'Das Feld :attribute muss mindestens :min Ziffern haben.',
    'missing'          => 'Das Feld :attribute muss fehlen.',
    'missing_if'       => 'Das Feld :attribute muss fehlen, wenn :other :value ist.',
    'missing_unless'   => 'Das Feld :attribute muss fehlen, es sei denn, :other ist :value.',
    'missing_with'     => 'Das Feld :attribute muss fehlen, wenn :values vorhanden sind.',
    'missing_with_all' => 'Das Feld :attribute muss fehlen, wenn :values vorhanden sind.',
    'multiple_of'      => 'Das Feld :attribute muss ein Vielfaches von :value sein.',
    'not_in'           => 'Die ausgewählte :attribute ist ungültig.',
    'not_regex'        => 'Das Format des Feldes :attribute ist ungültig.',
    'numeric'          => 'Das Feld :attribute muss eine Zahl sein.',

    'password' => [
        'letters'       => 'Das Feld :attribute muss mindestens einen Buchstaben enthalten.',
        'mixed'         => 'Das Feld :attribute muss mindestens einen Großbuchstaben und einen Kleinbuchstaben enthalten.',
        'numbers'       => 'Das Feld :attribute muss mindestens eine Zahl enthalten.',
        'symbols'       => 'Das Feld :attribute muss mindestens ein Symbol enthalten.',
        'uncompromised' => 'Das angegebene :attribute ist in einem Datenleck aufgetreten. Bitte wählen Sie ein anderes :attribute.',
    ],

    'present'              => 'Das Feld :attribute muss vorhanden sein.',
    'present_if'           => 'Das Feld :attribute muss vorhanden sein, wenn :other :value ist.',
    'present_unless'       => 'Das Feld :attribute muss vorhanden sein, es sei denn, :other ist :value.',
    'present_with'         => 'Das Feld :attribute muss vorhanden sein, wenn :values vorhanden ist.',
    'present_with_all'     => 'Das Feld :attribute muss vorhanden sein, wenn :values vorhanden sind.',
    'prohibited'           => 'Das Feld :attribute ist verboten.',
    'prohibited_if'        => 'Das Feld :attribute ist verboten, wenn :other :value ist.',
    'prohibited_unless'    => 'Das Feld :attribute ist verboten, es sei denn, :other ist in :values.',
    'prohibits'            => 'Das Feld :attribute verbietet :other.',
    'regex'                => 'Das Format des Feldes :attribute ist ungültig.',
    'required'             => 'Das Feld :attribute ist erforderlich.',
    'required_array_keys'  => 'Das Feld :attribute muss Einträge für: :values enthalten.',
    'required_if'          => 'Das Feld :attribute ist erforderlich, wenn :other :value ist.',
    'required_if_accepted' => 'Das Feld :attribute ist erforderlich, wenn :other akzeptiert wird.',
    'required_unless'      => 'Das Feld :attribute ist erforderlich, es sei denn, :other ist in :values.',
    'required_with'        => 'Das Feld :attribute ist erforderlich, wenn :values vorhanden ist.',
    'required_with_all'    => 'Das Feld :attribute ist erforderlich, wenn :values vorhanden sind.',
    'required_without'     => 'Das Feld :attribute ist erforderlich, wenn :values nicht vorhanden ist.',
    'required_without_all' => 'Das Feld :attribute ist erforderlich, wenn keine der :values vorhanden ist.',
    'same'                 => 'Das Feld :attribute muss mit :other übereinstimmen.',

    'size' => [
        'array'   => 'Das Feld :attribute muss :size Elemente enthalten.',
        'file'    => 'Das Feld :attribute muss :size Kilobytes groß sein.',
        'numeric' => 'Das Feld :attribute muss :size sein.',
        'string'  => 'Das Feld :attribute muss :size Zeichen haben.',
    ],

    'starts_with' => 'Das Feld :attribute muss mit einem der folgenden beginnen: :values.',
    'string'      => 'Das Feld :attribute muss ein String sein.',
    'timezone'    => 'Das Feld :attribute muss eine gültige Zeitzone sein.',
    'unique'      => 'Das :attribute wurde bereits verwendet.',
    'uploaded'    => 'Das :attribute konnte nicht hochgeladen werden.',
    'uppercase'   => 'Das Feld :attribute muss großgeschrieben sein.',
    'url'         => 'Das Feld :attribute muss eine gültige URL sein.',
    'ulid'        => 'Das Feld :attribute muss eine gültige ULID sein.',
    'uuid'        => 'Das Feld :attribute muss eine gültige UUID sein.',

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
            'rule-name' => 'benutzerdefinierte Nachricht',
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
