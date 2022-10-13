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

    'accepted'             => 'Το :attribute πρέπει να γίνει αποδεκτό.',
    'active_url'           => 'Το :attribute δεν είναι έγκυρο URL.',
    'after'                => 'Το :attribute πρέπει να είναι ημερομηνία μετά από :date.',
    'after_or_equal'       => 'Το :attribute πρέπει να είναι ημερομηνία μετά από ή ίση με :date.',
    'alpha'                => 'Το :attribute μπορεί να περιέχει μονάχα γράμματα.',
    'alpha_dash'           => 'Το :attribute μπορεί να περιέχει μονάχα γράμματα, αριθμούς, και παύλες.',
    'alpha_num'            => 'Το :attribute μπορεί να περιέχει μονάχα γράμματα κι αριθμούς.',
    'array'                => 'Το :attribute πρέπει να είναι πίνακας.',
    'before'               => 'Το :attribute πρέπει να είναι ημερομηνία πριν από :date.',
    'before_or_equal'      => 'Το :attribute πρέπει να είναι ημερομηνία πριν από ή ίση με :date.',
    'between'              => [
        'numeric' => 'Το :attribute πρέπει να είναι μεταξύ :min και :max.',
        'file'    => 'Το :attribute πρέπει να είναι μεταξύ :min και :max kilobytes.',
        'string'  => 'Το :attribute πρέπει να είναι μεταξύ :min και :max χαρακτήρες.',
        'array'   => 'Το :attribute πρέπει να είναι μεταξύ :min και :max στοιχεία.',
    ],
    'boolean'              => 'Το πεδίο :attribute πρέπει ναι είναι αληθές ή ψευδές.',
    'confirmed'            => 'Το :attribute επιβεβαίωσης δεν ταιρίαζει.',
    'date'                 => 'Το :attribute δεν είναι έγκυρη ημερομηνία.',
    'date_format'          => 'Το :attribute δεν ταιρίαζει με την μορφή :format.',
    'different'            => 'Το :attribute και :other πρέπει να είναι διαφορετικά.',
    'digits'               => 'Το :attribute πρέπει να είναι :digits ψηφία.',
    'digits_between'       => 'Το :attribute πρέπει να είναι μεταξύ :min και :max ψηφία.',
    'dimensions'           => 'Το :attribute έχει μη έγκυρες διαστάσεις εικόνας.',
    'distinct'             => 'Το :attribute πεδίο έχει διπλότυπη τιμή.',
    'email'                => 'Το :attribute πρέπει να είναι μια έγκυρη διεύθυνση email.',
    'exists'               => 'Το επιλεγμένο :attribute δεν είναι έγκυρο.',
    'file'                 => 'Το :attribute πρέπει να είναι αρχείο.',
    'filled'               => 'Το :attribute πεδίο πρέπει να έχει μια τιμή.',
    'gt'                   => [
        'numeric' => 'Το :attribute πρέπει να είναι μεγαλύτερο από :value.',
        'file'    => 'Το :attribute πρέπει να είναι μεγαλύτερο από :value kilobytes.',
        'string'  => 'Το :attribute πρέπει να είναι μεγαλύτερο από :value χαρακτήρες.',
        'array'   => 'Το :attribute πρέπει να έχει περισσότερα από :value στοιχεία.',
    ],
    'gte'                  => [
        'numeric' => 'Το :attribute πρέπει να είναι μεγαλύτερο από ή ίσο με :value.',
        'file'    => 'Το :attribute πρέπει να είναι μεγαλύτερο από ή ίσο με :value kilobytes.',
        'string'  => 'Το :attribute πρέπει να είναι μεγαλύτερο από ή ίσο με :value χαρακτήρες.',
        'array'   => 'Το :attribute πρέπει να έχει :value στοιχεία ή περισσότερα.',
    ],
    'image'                => 'Το :attribute πρέπει να είναι εικόνα.',
    'in'                   => 'Το επιλεγμένο :attribute δεν είναι έγκυρο.',
    'in_array'             => 'Το :attribute πεδίο δεν υπάρχει στο :other.',
    'integer'              => 'Το :attribute πρέπει να είναι ακέραιος.',
    'ip'                   => 'Το :attribute πρέπει να είναι μια έγκυρη διεύθυνση IP.',
    'ipv4'                 => 'Το :attribute πρέπει να είναι μια έγκυρη διεύθυνση IPv4.',
    'ipv6'                 => 'Το :attribute πρέπει να είναι μια έγκυρη διεύθυνση IPv6.',
    'json'                 => 'Το :attribute πρέπει να είναι μια έγκυρη συμβολοσειρά JSON.',
    'lt'                   => [
        'numeric' => 'To :attribute πρέπει να είναι μικρότερο από :value.',
        'file'    => 'To :attribute πρέπει να είναι μικρότερο από :value kilobytes.',
        'string'  => 'To :attribute πρέπει να είναι μικρότερο από :value χαρακτήρες.',
        'array'   => 'To :attribute πρέπει να έχει λιγότερα από :value στοιχεία.',
    ],
    'lte'                  => [
        'numeric' => 'Το :attribute πρέπει να είναι μικρότερο από ή ίσο με :value.',
        'file'    => 'Το :attribute πρέπει να είναι μικρότερο από ή ίσο με :value kilobytes.',
        'string'  => 'Το :attribute πρέπει να είναι μικρότερο από ή ίσο με :value χαρακτήρες.',
        'array'   => 'Το :attribute δεν πρέπει να έχει περισσότερα από :value στοιχεία.',
    ],
    'max'                  => [
        'numeric' => 'Το :attribute δεν μπορεί να είναι μεγαλύτερο από :max.',
        'file'    => 'Το :attribute δεν μπορεί να είναι μεγαλύτερο από :max kilobytes.',
        'string'  => 'Το :attribute δεν μπορεί να είναι μεγαλύτερο από :max χαρακτήρες.',
        'array'   => 'Το :attribute δεν μπορεί να έχει περισσότερα από :max στοιχεία.',
    ],
    'mimes'                => 'Το :attribute πρέπει να είναι ένα αρχείο τύπου : :values.',
    'mimetypes'            => 'Το :attribute πρέπει να είναι ένα αρχείο τύπου : :values.',
    'min'                  => [
        'numeric' => 'Το :attribute πρέπει να είναι τουλάχιστον :min.',
        'file'    => 'Το :attribute πρέπει να είναι τουλάχιστον :min kilobytes.',
        'string'  => 'Το :attribute πρέπει να είναι τουλάχιστον :min χαρακτήρες.',
        'array'   => 'Το :attribute πρέπει να έχει τουλάχιστον :min στοιχεία.',
    ],
    'not_in'               => 'Το επιλεγμένο :attribute δεν είναι έγκυρο.',
    'not_regex'            => 'Η μορφή :attribute δεν είναι έγκυρη.',
    'numeric'              => 'Το :attribute πρέπει να είναι αριθμός.',
    'present'              => 'Το πεδίο :attribute πρέπει να υπάρχει.',
    'regex'                => 'Η μορφή :attribute δεν είναι έγκυρη.',
    'required'             => 'Το :attribute πεδίο απαιτείται.',
    'required_if'          => 'Το :attribute πεδίο απαιτείται όταν το :other είναι :value.',
    'required_unless'      => 'Το :attribute πεδίο απαιτείται εκτός αν το :other είναι σε :values.',
    'required_with'        => 'Το :attribute πεδίο απαιτείται όταν υπάρχει το :values.',
    'required_with_all'    => 'Το :attribute πεδίο απαιτείται όταν υπάρχει το :values.',
    'required_without'     => 'Το :attribute πεδίο απαιτείται όταν δεν υπάρχει το :values.',
    'required_without_all' => 'Το :attribute πεδίο απαιτείται όταν δεν υπάρχει κανένα από τα :values.',
    'same'                 => 'Το :attribute και :other πρέπει να ταυτίζονται.',
    'size'                 => [
        'numeric' => 'Το :attribute πρέπει να είναι :size.',
        'file'    => 'Το :attribute πρέπει να είναι :size kilobytes.',
        'string'  => 'Το :attribute πρέπει να είναι :size χαρακτήρες.',
        'array'   => 'Το :attribute πρέπει να περιέχει :size στοιχεία.',
    ],
    'string'               => 'Το :attribute πρέπει να είναι μια συμβολοσειρά.',
    'timezone'             => 'Το :attribute πρέπει να είναι μια έγκυρη ζώνη.',
    'unique'               => 'Το :attribute υπάρχει ήδη.',
    'uploaded'             => 'Το :attribute απέτυχε να μεταφορτωθεί.',
    'url'                  => 'Η μορφή :attribute δεν είναι έγκυρη.',

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
