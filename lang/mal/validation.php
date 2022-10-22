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

    'accepted'             => ':attribute സ്വീകരിക്കണം.',
    'active_url'           => ':attribute ഒരു സാധുവായ URL അല്ല.',
    'after'                => ':attribute :date പിന്നീടുള്ള തീയതി ആയിരിക്കണം.',
    'after_or_equal'       => ':attribute :date തീയതിക്ക് ശേഷമോ തുല്യമോ ആയിരിക്കണം.',
    'alpha'                => ':attribute അക്ഷരങ്ങൾ മാത്രം അടങ്ങിയിരിക്കാം.',
    'alpha_dash'           => ':attribute അക്ഷരങ്ങൾ, അക്കങ്ങൾ, ഡാഷുകൾ, അടിവരകൾ എന്നിവ മാത്രമേ ഉൾക്കൊള്ളാൻ കഴിയൂ.',
    'alpha_num'            => ':attribute അക്ഷരങ്ങളും അക്കങ്ങളും മാത്രം അടങ്ങിയിരിക്കാം.',
    'array'                => ':attribute ഒരു അറേ ആയിരിക്കണം.',
    'before'               => ':attribute :date ന് മുമ്പുള്ള തീയതി ആയിരിക്കണം',
    'before_or_equal'      => ':attribute :date ന് മുമ്പുള്ളതോ തുല്യമായതോ ആയ തീയതി ആയിരിക്കണം',
    'between'              => [
        'numeric' => ':attribute :min ഒപ്പം :max ഇടയിലായിരിക്കണം',
        'file'    => ':attribute :min ഒപ്പം :max കിലോബൈറ്റുകൾക്കിടയിലായിരിക്കണം.',
        'string'  => ':attribute :min ഒപ്പം :max കഥാപാത്രങ്ങൾക്കിടയിലായിരിക്കണം.',
        'array'   => ':attribute :min ഒപ്പം :max ഇനങ്ങൾക്കിടയിൽ ആയിരിക്കണം.',
    ],
    'boolean'              => ':attribute ഫീൽഡ് ശരിയോ തെറ്റോ ആയിരിക്കണം.',
    'confirmed'            => ':attribute സ്ഥിരീകരണം പൊരുത്തപ്പെടുന്നില്ല.',
    'date'                 => ':attribute സാധുവായ തീയതിയല്ല.',
    'date_format'          => ':attribute തീയതി :format ചേരുന്നില്ല.',
    'different'            => ':attribute കൂടാതെ :other വ്യത്യസ്തമായിരിക്കണം.',
    'digits'               => ':attribute :digits നമ്പർ ആയിരിക്കണം.',
    'digits_between'       => ':attribute :min ഒപ്പം :max അക്കങ്ങൾക്കിടയിലായിരിക്കണം.',
    'dimensions'           => ':attribute അസാധുവായ ഇമേജ് അളവുകൾ ഉണ്ട്',
    'distinct'             => ':attribute ഫീൽഡിൽ ഒരു ഡ്യൂപ്ലിക്കേറ്റ് മൂല്യം അടങ്ങിയിരിക്കുന്നു.',
    'email'                => ':attribute ഒരു സാധുവായ ഇ - മെയിൽ വിലാസം ആയിരിക്കണം.',
    'exists'               => 'തിരഞ്ഞെടുത്തു :attribute അസാധുവാണ്.',
    'file'                 => ':attribute ഒരു ഫയൽ ഉണ്ടായിരിക്കണം.',
    'filled'               => ':attribute ഫീൽഡിൽ ഒരു മൂല്യം ഉണ്ടായിരിക്കണം.',
    'gt'                   => [
        'numeric' => ':attribute :value എന്നതിനേക്കാൾ വലുതായിരിക്കണം',
        'file'    => ':attribute :value കിലോബൈറ്റിനേക്കാൾ വലുതായിരിക്കണം.',
        'string'  => ':attribute :value കഥാപാത്രങ്ങളേക്കാൾ വലുതായിരിക്കണം',
        'array'   => ':attribute ഇൻ :value ഇനങ്ങൾ ഇതിലും വലുതായിരിക്കണം',
    ], 
    'gte'                  => [
        'numeric' => ':attribute :value ഇതിനേക്കാൾ വലുതോ തുല്യമോ ആയിരിക്കണം.', 
        'file'    => ':attribute ഇതിനേക്കാൾ വലുതോ തുല്യമോ ആയിരിക്കണം :value കിലോബൈറ്റുകൾ.',
        'string'  => ': attribute ഇതിനേക്കാൾ വലുതോ തുല്യമോ ആയിരിക്കണം :value സ്വഭാവം.',
        'array'   => ':attribute ഇൻ :value ഇനങ്ങൾ അല്ലെങ്കിൽ കൂടുതൽ.',
    ],
    'image'                => ':attribute ഒരു ചിത്രം ഉണ്ടായിരിക്കണം.',
    'in'                   => 'തിരഞ്ഞെടുത്തു :attribute അസാധുവാണ്.',
    'in_array'             => ":attribute ഫീൽഡ് :other'ൽ നിലവിലില്ല",
    'integer'              => ':attribute ഒരു പൂർണ്ണസംഖ്യയായിരിക്കണം.',
    'ip'                   => ':attribute ഒരു സാധുവായ IP വിലാസം ഉണ്ടായിരിക്കണം.',
    'ipv4'                 => ':attribute ഒരു സാധുവായ IPv4 അറിഞ്ഞിരിക്കണം.',
    'ipv6'                 => ':attribute ഒരു സാധുവായ IPv6 അറിഞ്ഞിരിക്കണം.',
    'json'                 => ':attribute ഒരു സാധുവായ JSON സ്ട്രിംഗ് ആയിരിക്കണം.',
    'lt'                   => [
        'numeric' => ':attribute :value യിൽ കുറവായിരിക്കണം',
        'file'    => ':attribute :value കിലോബൈറ്റിലും കുറവായിരിക്കണം.',
        'string'  => ':attribute :value പ്രതീകങ്ങളേക്കാൾ കുറവായിരിക്കണം.',
        'array'   => ':attribute ഇൻ :value ഇനം കുറവായിരിക്കണം',
    ],
    'lte'                  => [
        'numeric' => ': attribute ഇതിനേക്കാൾ കുറവോ തുല്യമോ ആയിരിക്കണം :value ',
        'file'    => ': attribute അതിൽ കുറവോ തുല്യമോ ആയിരിക്കണം :value കിലോബൈറ്റുകൾ.',
        'string'  => ': attribute അതിൽ കുറവോ തുല്യമോ ആയിരിക്കണം :value കഥാപാത്രങ്ങൾ.',
        'array'   => ': attribute ഇൻ :value ഇനങ്ങൾ കവിയാൻ പാടില്ല',
    ],
    'max'                  => [
        'numeric' => ":attribute :max' കവിയാൻ കഴിയില്ല.",
        'file'    => ':attribute :max കിലോബൈറ്റ് കവിയാൻ പാടില്ല.',
        'string'  => ':attribute :max പ്രതീകങ്ങൾ കവിയാൻ പാടില്ല.',
        'array'   => ':attribute ഇൻ :max ഇനങ്ങൾ കവിയാൻ പാടില്ല.',
    ],
    'mimes'                => ':attribute ഒരു തരത്തിലുള്ള ഫയൽ ആയിരിക്കണം::value',
    'mimetypes'            => ':attribute ഒരു തരത്തിലുള്ള ഫയൽ ആയിരിക്കണം::value',
    'min'                  => [
        'numeric' => ':attribute കുറഞ്ഞത് :min ആയിരിക്കണം',
        'file'    => ':attribute കുറഞ്ഞത് :min കിലോബൈറ്റ് ആയിരിക്കണം.',
        'string'  => ':attribute കുറഞ്ഞത് :min സ്വഭാവം ഉണ്ടായിരിക്കണം.',
        'array'   => ':attribute കുറഞ്ഞത് :min ഇനം ഉണ്ടായിരിക്കണം.',
    ],
    'not_in'               => 'തിരഞ്ഞെടുത്തു :attribute അസാധുവാണ്.',
    'not_regex'            => ':attribute ഫോർമാറ്റ് അസാധുവാണ്.',
    'numeric'              => ':attribute ഒരു സംഖ്യ ആയിരിക്കണം.',
    'present'              => ':attribute ഫീൽഡ് നിലനിൽക്കണം.',
    'regex'                => ':attribute ഫോർമാറ്റ് അസാധുവാണ്.',
    'required'             => ':attribute ഫീൽഡ് ആവശ്യമാണ്',
    'required_if'          => ':attribute ഫീൽഡ് ആവശ്യമാണ് എപ്പോൾ :other ആണ് :value',
    'required_unless'      => ':attribute ഫീൽഡ് ആവശ്യമാണ് എന്നാൽ ഇല്ലെങ്കിൽ :other അകത്തുണ്ട് :values',
    'required_with'        => ':attribute ഫീൽഡ് ആവശ്യമാണ് :value നിലവിലുണ്ട്.',
    'required_with_all'    => ':attribute ഫീൽഡ് ആവശ്യമാണ് :value നിലവിലുണ്ട്.',
    'required_without'     => ':attribute ഫീൽഡ് ആവശ്യമാണ് :value ലഭ്യമല്ല.',
    'required_without_all' => ':attribute ഫീൽഡ് ആവശ്യമാണ് :value ലഭ്യമല്ല.',
    'same'                 => ':attribute ഒപ്പം :other ഒരു പൊരുത്തം ഉണ്ടായിരിക്കണം.',
    'size'                 => [
        'numeric' => ':attribute ആയിരിക്കണം :size',
        'file'    => ':attribute ആയിരിക്കണം :size കിലോബൈറ്റുകൾ.',
        'string'  => ':attribute ആയിരിക്കണം :size സ്വഭാവം.',
        'array'   => 'ഈ സവിശേഷതയിൽ :size ഇനങ്ങൾ ഉൾപ്പെടുത്തണം.',
    ],
    'string'               => ':attribute ഒരു സ്ട്രിംഗ് ആയിരിക്കണം.',
    'timezone'             => ':attribute സാധുതയുള്ള ഒരു ഫീൽഡ് ആയിരിക്കണം.',
    'unique'               => ':attribute ഇതിനകം എടുത്തുകഴിഞ്ഞു.',
    'uploaded'             => ':attribute അപ്‌ലോഡ് ചെയ്യുന്നതിൽ പരാജയപ്പെട്ടു.',
    'url'                  => ':attribute ഫോർമാറ്റ് അസാധുവാണ്.',

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
            'rule-name' => 'ഇഷ്ടാനുസൃത സന്ദേശം',
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
