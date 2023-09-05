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

    'accepted'        => 'Das Attribut :attribute muss akzeptiert werden.',
    'active_url'      => 'Das :attribute ist keine gültige URL.',
    'after'           => 'Das Attribut :attribute muss ein Datum nach :date sein.',
    'after_or_equal'  => 'Das Attribut :attribute muss ein Datum nach oder gleich :date sein.',
    'alpha'           => 'Das Attribut :attribute darf nur Buchstaben enthalten.',
    'alpha_dash'      => 'Das Attribut :darf nur Buchstaben, Zahlen, Striche und Unterstriche enthalten.',
    'alpha_num'       => 'Das Attribut :attribute darf nur Buchstaben und Zahlen enthalten.',
    'array'           => 'Das :attribute muss ein Array sein.',
    'before'          => 'Das Attribut :attribute muss ein Datum vor :date sein.',
    'before_or_equal' => 'Das Attribut :attribute muss ein Datum vor oder gleich :date sein.',

    'between'         => [
        'numeric' => 'Das Attribut :attribute muss zwischen :min und :max liegen.',
        'file'    => 'Das Attribut :attribute muss zwischen :min und :max Kilobyte liegen.',
        'string'  => 'Das Attribut :attribute muss zwischen :min und :max Zeichen liegen.',
        'array'   => 'Das Attribut :attribute muss zwischen :min und :max Elemente haben.',
    ],

    'boolean'        => 'Das Feld :attribute muss wahr oder falsch sein.',
    'confirmed'      => 'Die Bestätigung des :attribute stimmt nicht überein.',
    'date'           => 'Das Attribut :attribute ist kein gültiges Datum.',
    'date_format'    => 'Das Attribut :attribute entspricht nicht dem Format :format.',
    'different'      => 'Das Attribut :attribute und :other müssen unterschiedlich sein.',
    'digits'         => 'Das Attribut :attribute muss aus Ziffern bestehen.',
    'digits_between' => 'Das Attribut :attribute muss zwischen :min und :max Ziffern liegen.',
    'dimensions'     => 'Das Attribut :attribute hat ungültige Bildabmessungen.',
    'distinct'       => 'Das Feld :attribute hat einen doppelten Wert.',
    'email'          => 'Das Attribut :attribute muss eine gültige E-Mail-Adresse sein.',
    'exists'         => 'Das ausgewählte :attribute ist ungültig.',
    'file'           => 'Das Attribut :attribute muss eine Datei sein.',
    'filled'         => 'Das Feld :attribute muss einen Wert haben.',

    'gt'             => [
        'numeric' => 'Das Attribut :attribute muss größer als :value sein.',
        'file'    => 'Das Attribut :attribute muss größer als der Wert :value kilobytes sein.',
        'string'  => 'Das Attribut :attribute muss größer als :value Zeichen sein.',
        'array'   => 'Das Attribut :attribute muss mehr als :value items haben.',
    ],

    'gte' => [
        'numeric' => 'Das Attribut :attribute muss größer oder gleich :value sein.',
        'file'    => 'Das Attribut :attribute muss größer oder gleich :value kilobytes sein.',
        'string'  => 'Das Attribut :attribute muss größer oder gleich :value Zeichen sein.',
        'array'   => 'Das Attribut :attribute muss :value items oder mehr haben.',
    ],

    'image'    => 'Das Attribut :attribute muss ein Bild sein.',
    'in'       => 'Das ausgewählte :attribute ist ungültig.',
    'in_array' => 'Das Feld :attribute existiert nicht in :other.',
    'integer'  => 'Das Attribut :attribute muss eine ganze Zahl sein.',
    'ip'       => 'Das Attribut :attribute muss eine gültige IP-Adresse sein.',
    'ipv4'     => 'Das :attribute muss eine gültige IPv4-Adresse sein.',
    'ipv6'     => 'Das :attribute muss eine gültige IPv6-Adresse sein.',
    'json'     => 'Das :attribute muss ein gültiger JSON-String sein.',

    'lt'       => [
        'numeric' => 'Das Attribut :attribute muss kleiner als :value sein.',
        'file'    => 'Das Attribut :attribute muss kleiner als der Wert :value kilobytes sein.',
        'string'  => 'Das Attribut :attribute muss kleiner als :value Zeichen sein.',
        'array'   => 'Das Attribut :attribute muss kleiner als :value items sein.',
    ],

    'lte' => [
        'numeric' => 'Das Attribut :attribute muss kleiner oder gleich :value sein.',
        'file'    => 'Das Attribut :attribute muss kleiner oder gleich :value kilobytes sein.',
        'string'  => 'Das Attribut :attribute muss kleiner oder gleich :value Zeichen sein.',
        'array'   => 'Das Attribut :attribute darf nicht mehr als :value items haben.',
    ],

    'max' => [
        'numeric' => 'Das Attribut :attribute darf nicht größer als :max sein.',
        'file'    => 'Das Attribut :attribute darf nicht größer als :max Kilobytes sein.',
        'string'  => 'Das Attribut :attribute darf nicht größer als :max Zeichen sein.',
        'array'   => 'Das Attribut :attribute darf nicht mehr als :max Elemente enthalten.',
    ],

    'mimes'     => 'Das Attribut :attribute muss eine Datei vom Typ: :values sein.',
    'mimetypes' => 'Das Attribut :attribute muss eine Datei vom Typ: :values sein.',

    'min'       => [
        'numeric' => 'Das Attribut :attribute muss mindestens :min. sein.',
        'file'    => 'Das Attribut :attribute muss mindestens :min Kilobytes betragen.',
        'string'  => 'Das Attribut :attribute muss aus mindestens :min Zeichen bestehen.',
        'array'   => 'Das Attribut :attribute muss mindestens :min Elemente enthalten.',
    ],

    'not_in'               => 'Das ausgewählte :attribute ist ungültig.',
    'not_regex'            => 'Das Format des :attribute ist ungültig.',
    'numeric'              => 'Das Attribut :attribute muss eine Zahl sein.',
    'present'              => 'Das Feld :attribute muss vorhanden sein.',
    'regex'                => 'Das Format des :attribute ist ungültig.',
    'required'             => 'Das Feld :attribute ist erforderlich.',
    'required_if'          => 'Das Feld :attribute wird benötigt, wenn :other der Wert :value ist.',
    'required_unless'      => 'Das Feld :attribute ist erforderlich, es sei denn, :other steht in :values.',
    'required_with'        => 'Das Feld :attribute ist erforderlich, wenn :values vorhanden ist.',
    'required_with_all'    => 'Das Feld :attribute ist erforderlich, wenn :values vorhanden ist.',
    'required_without'     => 'Das Attributfeld :attribute ist erforderlich, wenn :values nicht vorhanden ist.',
    'required_without_all' => 'Das Attributfeld :attribute wird benötigt, wenn keiner der :values vorhanden ist.',
    'same'                 => 'Das Attribut :attribute und :other müssen übereinstimmen.',

    'size'                 => [
        'numeric' => 'Das Attribut :attribute muss :size sein.',
        'file'    => 'Das Attribut :attribute muss :size kilobytes sein.',
        'string'  => 'Das Attribut :attribute muss :size Zeichen sein.',
        'array'   => 'Das Attribut :attribute muss :size Elemente enthalten.',
    ],

    'string'   => 'Das :attribute muss eine Zeichenkette sein.',
    'timezone' => 'Das Attribut :attribute muss eine gültige Zone sein.',
    'unique'   => 'Das Attribut :attribute wurde bereits vergeben.',
    'uploaded' => 'Das :attribute konnte nicht hochgeladen werden.',
    'url'      => 'Das Format des :attribute ist ungültig.',

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
            'rule-name' => 'kundenspezifische Nachricht',
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
