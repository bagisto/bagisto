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

    'accepted'             => ':attribute කළ යුතු ය.',
    'active_url'           => ':attribute වලංගු URL නොවන ය.',
    'after'                => ':attribute :date පසු දිනයක් වලංගු වී යුතුය.',
    'after_or_equal'       => ':attribute :date පසු හෝ සම්බන්ධ වී යුතුය.',
    'alpha'                => ':attribute නම් සඳහා සංඛ්‍යාවක් පමණක් සහිතව සහිත විය හැක.',
    'alpha_dash'           => ':attribute නම් සඳහා සංඛ්‍යා, අංක, ඩෑෂ්, හා යුන්නර් එකක් පමණක් සහිතව සහිත විය හැක.',
    'alpha_num'            => ':attribute නම් සඳහා සංඛ්‍යාවක් සහිතව සහිත විය හැක.',
    'array'                => ':attribute පේළියක් විය හැක.',
    'before'               => ':attribute :date පෙරක් වලංගු දිනයක් වී යුතුය.',
    'before_or_equal'      => ':attribute :date පෙරක් හෝ සම්බන්ධ වී යුතුය.',

    'between'              => [
        'numeric' => ':attribute :min සහ :max පමණක් විය හැක.',
        'file'    => ':attribute :min සහ :max කිලෝබයිට් කිලෝබයිට් වලංගු පෙරනිමි.',
        'string'  => ':attribute :min සහ :max අකුරු සංඛ්‍යා වලංගු පෙරනිමි.',
        'array'   => ':attribute :min සහ :max ක්ලටරයක් වලංගු පෙරනිමි.',
    ],

    'boolean'              => ':attribute ක්වය සහිත වී යුතුය හෝ නැත වී යුතුය.',
    'confirmed'            => ':attribute තහවුරු සමුදාය වෙබ්බාගේ සම්බන්ධ වී නැත.',
    'date'                 => ':attribute වලංගු දිනයක් නොවේ.',
    'date_format'          => ':attribute :format ආකෘතියට නොවක් වේ.',
    'different'            => ':attribute සහ :other වෙබ්බා සම්බන්ධ වී යුතුයි.',
    'digits'               => ':attribute :digits සංඛ්‍යානුකූල වෙයි.',
    'digits_between'       => ':attribute :min සහ :max කූල වෙයි.',
    'dimensions'           => ':attribute වේලකාභයේ වලංගු රූපලාවන් නොවේ.',
    'distinct'             => ':attribute ක්වයක් පමණි.',
    'email'                => ':attribute වලංගු වර්ගයේ වලංගු ඊ-තැපැල් ලිපිනය වෙයි.',
    'exists'               => 'තෝරාගත් :attribute වලංගු වර්ගයක් නොවේ.',
    'file'                 => ':attribute ගොනුව වෙනස් කර ඇත.',
    'filled'               => ':attribute ක්වය අවක්ෂා කර ඇත.',

    'gt'                   => [
        'numeric' => ':attribute :value වලට වැඩි වී යුතුය.',
        'file'    => ':attribute :value කිලෝබයිට් කිලෝබයිට් වලට වැඩි වී යුතුය.',
        'string'  => ':attribute :value අකුරු සංඛ්‍යාවක් වලට වැඩි වී යුතුය.',
        'array'   => ':attribute :value ක්ලටරයක් වලට වැඩි වී යුතුය.',
    ],

    'gte'                  => [
        'numeric' => ':attribute :value වලට සහිත වී යුතුය.',
        'file'    => ':attribute :value කිලෝබයිට් කිලෝබයිට් වලට සහිත වී යුතුය.',
        'string'  => ':attribute :value අකුරු සංඛ්‍යාවක් වලට සහිත වී යුතුය.',
        'array'   => ':attribute :value ක්ලටරයක් වලට සහිත වී යුතුය.',
    ],

    'image'                => ':attribute වේලකාභයේ වලංගු රූපලාවන් වෙයි.',
    'in'                   => 'තෝරාගත් :attribute වලංගු වර්ගයක් නොවේ.',
    'in_array'             => ':attribute ක්වය :other වල නොවේ.',
    'integer'              => ':attribute වලංගු සංඛ්‍යානුකූල වෙයි.',
    'ip'                   => ':attribute වලංගු වර්ගයේ වලංගු IP ලිපිනයක් වෙයි.',
    'ipv4'                 => ':attribute වලංගු වර්ගයේ වලංගු IPv4 ලිපිනයක් වෙයි.',
    'ipv6'                 => ':attribute වලංගු වර්ගයේ වලංගු IPv6 ලිපිනයක් වෙයි.',
    'json'                 => ':attribute වලංගු වර්ගයේ වලංගු JSON අකුරුක්ෂණයක් වෙයි.',

    'lt'                   => [
        'numeric' => ':attribute :value වලට අඩු වී යුතුය.',
        'file'    => ':attribute :value කිලෝබයිට් කිලෝබයිට් වලට අඩු වී යුතුය.',
        'string'  => ':attribute :value අකුරු සංඛ්‍යාවක් වලට අඩු වී යුතුය.',
        'array'   => ':attribute :value ක්ලටරයක් වලට අඩු වී යුතුය.',
    ],

    'lte'                  => [
        'numeric' => ':attribute :value වලට සහිත වී යුතුය.',
        'file'    => ':attribute :value කිලෝබයිට් කිලෝබයිට් වලට සහිත වී යුතුය.',
        'string'  => ':attribute :value අකුරු සංඛ්‍යාවක් වලට සහිත වී යුතුය.',
        'array'   => ':attribute :value ක්ලටරයක් වලට සහිත වී යුතුය.',
    ],

    'max'                  => [
        'numeric' => ':attribute :max වලට වඩා වැඩි වී යුතුය.',
        'file'    => ':attribute :max කිලෝබයිට් කිලෝබයිට් වලට වඩා වැඩි වී යුතුය.',
        'string'  => ':attribute :max අකුරු වලට වඩා වැඩි වී යුතුය.',
        'array'   => ':attribute :max ක්ලටරයක් වලට වඩා වැඩි වී යුතුය.',
    ],

    'mimes'                => ':attribute :values වලංගු වෙනස් කළ හැක.',
    'mimetypes'            => ':attribute :values වලංගු වෙනස් කළ හැක.',

    'min'                  => [
        'numeric' => ':attribute වලංගු :min වනස් වී යුතුය.',
        'file'    => ':attribute :min කිලෝබයිට් කිලෝබයිට් වලට වනස් වී යුතුය.',
        'string'  => ':attribute :min අකුරු වලට වනස් වී යුතුය.',
        'array'   => ':attribute :min ක්ලටරයක් වලට වනස් වී යුතුය.',
    ],

    'not_in'               => 'තෝරාගත් :attribute වලංගු වර්ගයක් වේ.',
    'not_regex'            => ':attribute ආකෘතිය වලංගු වර්ගයක් වේ.',
    'numeric'              => ':attribute වලංගු සංඛ්‍යාවක් වෙයි.',
    'present'              => ':attribute ක්වය වෛර වී යුතුය.',
    'regex'                => ':attribute ආකෘතිය වලංගු වර්ගයක් වෙයි.',
    'required'             => ':attribute ක්වය අවශ්‍යයි.',
    'required_if'          => ':attribute ක්වය :other :value වලට ඇතුලත් වී යුතුය.',
    'required_unless'      => ':attribute ක්වය :other :values වෙයි.',
    'required_with'        => ':values වෙයි සහ :attribute ක්වය අවශ්‍යයි.',
    'required_with_all'    => ':values වෙයි සහ :attribute ක්වය අවශ්‍යයි.',
    'required_without'     => ':values වෙයි සහ :attribute ක්වය අවශ්‍ය නොවේ.',
    'required_without_all' => ':values වෙයි සහ :attribute ක්වය අවශ්‍ය වෙයි.',
    'same'                 => ':attribute සහ :other ගෙන් ගෙන් සුබහ වී යුතුය.',

    'size'                 => [
        'numeric' => ':attribute :size වනස් වී යුතුය.',
        'file'    => ':attribute :size කිලෝබයිට් කිලෝබයිට් වලට වනස් වී යුතුය.',
        'string'  => ':attribute :size අකුරු වලට වනස් වී යුතුය.',
        'array'   => ':attribute :size ක්ලටරයක් වලට වනස් වී යුතුය.',
    ],

    'string'               => ':attribute කුකුලයක් වෙයි.',
    'timezone'             => ':attribute වලංගු ස්ථානයක් වෙයි.',
    'unique'               => ':attribute වේලකාභයේ වලංගු වෙයි.',
    'uploaded'             => ':attribute උඩුගත කර නොමැත.',
    'url'                  => ':attribute ෆෝමැට් වලංගු වෙනස් කර ඇත.',


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
            'rule-name' => 'විස්තර-පනත',
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
