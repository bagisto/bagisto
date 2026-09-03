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

    'accepted' => 'Sehemu ya :attribute lazima ikubaliwe.',
    'accepted_if' => 'Sehemu ya :attribute lazima ikubaliwe pale :other ikiwa :value.',
    'active_url' => 'Sehemu ya :attribute lazima iwe anwani halali ya tovuti (URL).',
    'after' => 'Sehemu ya :attribute lazima iwe tarehe baada ya :date.',
    'after_or_equal' => 'Sehemu ya :attribute lazima iwe tarehe baada ya au sawa na :date.',
    'alpha' => 'Sehemu ya :attribute lazima iwe na herufi pekee.',
    'alpha_dash' => 'Sehemu ya :attribute lazima iwe na herufi, namba, mistari ya kufupisha na mistari ya chini pekee.',
    'alpha_num' => 'Sehemu ya :attribute lazima iwe na herufi na namba pekee.',
    'array' => 'Sehemu ya :attribute lazima iwe safu (array).',
    'ascii' => 'Sehemu ya :attribute lazima iwe na herufi na namba za baiti moja pamoja na alama pekee.',
    'before' => 'Sehemu ya :attribute lazima iwe tarehe kabla ya :date.',
    'before_or_equal' => 'Sehemu ya :attribute lazima iwe tarehe kabla ya au sawa na :date.',

    'between' => [
        'array' => 'Sehemu ya :attribute lazima iwe na vipengee kati ya :min na :max.',
        'file' => 'Sehemu ya :attribute lazima iwe kati ya kilobaiti :min na :max.',
        'numeric' => 'Sehemu ya :attribute lazima iwe kati ya :min na :max.',
        'string' => 'Sehemu ya :attribute lazima iwe na herufi kati ya :min na :max.',
    ],

    'boolean' => 'Sehemu ya :attribute lazima iwe kweli au si kweli.',
    'can' => 'Sehemu ya :attribute ina thamani isiyoruhusiwa.',
    'confirmed' => 'Uthibitisho wa sehemu ya :attribute haulingani.',
    'current_password' => 'Nenosiri si sahihi.',
    'date' => 'Sehemu ya :attribute lazima iwe tarehe sahihi.',
    'date_equals' => 'Sehemu ya :attribute lazima iwe tarehe sawa na :date.',
    'date_format' => 'Sehemu ya :attribute lazima ifuatane na muundo wa :format.',
    'decimal' => 'Sehemu ya :attribute lazima iwe na sehemu za desimali :decimal.',
    'declined' => 'Sehemu ya :attribute lazima ikataliwe.',
    'declined_if' => 'Sehemu ya :attribute lazima ikataliwe pale :other ikiwa :value.',
    'different' => 'Sehemu ya :attribute na :other lazima zitofautane.',
    'digits' => 'Sehemu ya :attribute lazima iwe na tarakimu :digits.',
    'digits_between' => 'Sehemu ya :attribute lazima iwe na tarakimu kati ya :min na :max.',
    'dimensions' => 'Sehemu ya :attribute ina vipimo vya picha visivyo sahihi.',
    'distinct' => 'Sehemu ya :attribute ina thamani inayojirudia.',
    'doesnt_end_with' => 'Sehemu ya :attribute isiheshe kwa mmojawapo wa haya: :values.',
    'doesnt_start_with' => 'Sehemu ya :attribute isianze kwa mmojawapo wa haya: :values.',
    'email' => 'Sehemu ya :attribute lazima iwe barua pepe sahihi.',
    'ends_with' => 'Sehemu ya :attribute lazima iheshe kwa mmojawapo wa haya: :values.',
    'enum' => ':attribute iliyochaguliwa si sahihi.',
    'exists' => ':attribute iliyochaguliwa si sahihi.',
    'extensions' => 'Sehemu ya :attribute lazima iwe na kiambishi cha mmojawapo wa haya: :values.',
    'file' => 'Sehemu ya :attribute lazima iwe faili.',
    'filled' => 'Sehemu ya :attribute lazima iwe na thamani.',

    'gt' => [
        'array' => 'Sehemu ya :attribute lazima iwe na vipengee zaidi ya :value.',
        'file' => 'Sehemu ya :attribute lazima iwe zaidi ya kilobaiti :value.',
        'numeric' => 'Sehemu ya :attribute lazima iwe kubwa kuliko :value.',
        'string' => 'Sehemu ya :attribute lazima iwe na herufi zaidi ya :value.',
    ],

    'gte' => [
        'array' => 'Sehemu ya :attribute lazima iwe na vipengee :value au zaidi.',
        'file' => 'Sehemu ya :attribute lazima iwe na angalau kilobaiti :value.',
        'numeric' => 'Sehemu ya :attribute lazima iwe kubwa kuliko au sawa na :value.',
        'string' => 'Sehemu ya :attribute lazima iwe na angalau herufi :value.',
    ],

    'hex_color' => 'Sehemu ya :attribute lazima iwe rangi halali ya hexadecimal.',
    'image' => 'Sehemu ya :attribute lazima iwe picha.',
    'in' => ':attribute iliyochaguliwa si sahihi.',
    'in_array' => 'Sehemu ya :attribute lazima iwepo ndani ya :other.',
    'integer' => 'Sehemu ya :attribute lazima iwe namba kamili.',
    'ip' => 'Sehemu ya :attribute lazima iwe anwani halali ya IP.',
    'ipv4' => 'Sehemu ya :attribute lazima iwe anwani halali ya IPv4.',
    'ipv6' => 'Sehemu ya :attribute lazima iwe anwani halali ya IPv6.',
    'json' => 'Sehemu ya :attribute lazima iwe mnyororo halali wa JSON.',
    'lowercase' => 'Sehemu ya :attribute lazima iwe kwa herufi ndogo.',

    'lt' => [
        'array' => 'Sehemu ya :attribute lazima iwe na vipengee pungufu ya :value.',
        'file' => 'Sehemu ya :attribute lazima iwe chini ya kilobaiti :value.',
        'numeric' => 'Sehemu ya :attribute lazima iwe ndogo kuliko :value.',
        'string' => 'Sehemu ya :attribute lazima iwe na herufi pungufu ya :value.',
    ],

    'lte' => [
        'array' => 'Sehemu ya :attribute lazima isiwe na vipengee zaidi ya :value.',
        'file' => 'Sehemu ya :attribute lazima iwe ndogo kuliko au sawa na kilobaiti :value.',
        'numeric' => 'Sehemu ya :attribute lazima iwe ndogo kuliko au sawa na :value.',
        'string' => 'Sehemu ya :attribute lazima isiwe na herufi zaidi ya :value.',
    ],

    'mac_address' => 'Sehemu ya :attribute lazima iwe anwani halali ya MAC.',

    'max' => [
        'array' => 'Sehemu ya :attribute lazima isiwe na vipengee zaidi ya :max.',
        'file' => 'Sehemu ya :attribute lazima isizidi kilobaiti :max.',
        'numeric' => 'Sehemu ya :attribute lazima isiwe kubwa kuliko :max.',
        'string' => 'Sehemu ya :attribute lazima isiwe na herufi zaidi ya :max.',
    ],

    'max_digits' => 'Sehemu ya :attribute lazima isiwe na tarakimu zaidi ya :max.',
    'mimes' => 'Sehemu ya :attribute lazima iwe faili la aina ifuatayo: :values.',
    'mimetypes' => 'Sehemu ya :attribute lazima iwe faili la aina ifuatayo: :values.',

    'min' => [
        'array' => 'Sehemu ya :attribute lazima iwe na vipengee angalau :min.',
        'file' => 'Sehemu ya :attribute lazima iwe na angalau kilobaiti :min.',
        'numeric' => 'Sehemu ya :attribute lazima iwe angalau :min.',
        'string' => 'Sehemu ya :attribute lazima iwe na angalau herufi :min.',
    ],

    'min_digits' => 'Sehemu ya :attribute lazima iwe na angalau tarakimu :min.',
    'missing' => 'Sehemu ya :attribute lazima ikosekane.',
    'missing_if' => 'Sehemu ya :attribute lazima ikosekane pale :other ikiwa :value.',
    'missing_unless' => 'Sehemu ya :attribute lazima ikosekane isipokuwa :other ikiwa :value.',
    'missing_with' => 'Sehemu ya :attribute lazima ikosekane pale :values ipo.',
    'missing_with_all' => 'Sehemu ya :attribute lazima ikosekane pale :values vyote vipo.',
    'multiple_of' => 'Sehemu ya :attribute lazima iweze kugawanywa kwa :value bila kibaki.',
    'not_in' => ':attribute iliyochaguliwa si sahihi.',
    'not_regex' => 'Muundo wa sehemu ya :attribute si sahihi.',
    'numeric' => 'Sehemu ya :attribute lazima iwe namba.',

    'password' => [
        'letters' => 'Sehemu ya :attribute lazima iwe na angalau herufi moja.',
        'mixed' => 'Sehemu ya :attribute lazima iwe na angalau herufi kubwa moja na herufi ndogo moja.',
        'numbers' => 'Sehemu ya :attribute lazima iwe na angalau namba moja.',
        'symbols' => 'Sehemu ya :attribute lazima iwe na angalau alama maalum moja.',
        'uncompromised' => ':attribute uliyotolewa umewahi kuonekana kwenye uvujaji wa data. Tafadhali chagua :attribute tofauti.',
    ],

    'present' => 'Sehemu ya :attribute lazima iwepo.',
    'present_if' => 'Sehemu ya :attribute lazima iwepo pale :other ikiwa :value.',
    'present_unless' => 'Sehemu ya :attribute lazima iwepo isipokuwa :other ikiwa :value.',
    'present_with' => 'Sehemu ya :attribute lazima iwepo pale :values ipo.',
    'present_with_all' => 'Sehemu ya :attribute lazima iwepo pale :values vyote vipo.',
    'prohibited' => 'Sehemu ya :attribute imekatazwa.',
    'prohibited_if' => 'Sehemu ya :attribute imekatazwa pale :other ikiwa :value.',
    'prohibited_unless' => 'Sehemu ya :attribute imekatazwa isipokuwa :other liko ndani ya :values.',
    'prohibits' => 'Sehemu ya :attribute inazuia :other kuwepo.',
    'regex' => 'Muundo wa sehemu ya :attribute si sahihi.',
    'required' => 'Sehemu ya :attribute ni ya lazima.',
    'required_array_keys' => 'Sehemu ya :attribute lazima iwe na ingizo kwa: :values.',
    'required_if' => 'Sehemu ya :attribute ni ya lazima pale :other ikiwa :value.',
    'required_if_accepted' => 'Sehemu ya :attribute ni ya lazima pale :other inapokubaliwa.',
    'required_unless' => 'Sehemu ya :attribute ni ya lazima isipokuwa :other liko ndani ya :values.',
    'required_with' => 'Sehemu ya :attribute ni ya lazima pale :values ipo.',
    'required_with_all' => 'Sehemu ya :attribute ni ya lazima pale :values vyote vipo.',
    'required_without' => 'Sehemu ya :attribute ni ya lazima pale :values haipo.',
    'required_without_all' => 'Sehemu ya :attribute ni ya lazima pale :values zote hazipo.',
    'same' => 'Sehemu ya :attribute lazima ilingane na :other.',

    'size' => [
        'array' => 'Sehemu ya :attribute lazima iwe na vipengee :size.',
        'file' => 'Sehemu ya :attribute lazima iwe kilobaiti :size.',
        'numeric' => 'Sehemu ya :attribute lazima iwe :size.',
        'string' => 'Sehemu ya :attribute lazima iwe na herufi :size.',
    ],

    'starts_with' => 'Sehemu ya :attribute lazima ianze kwa mmojawapo wa haya: :values.',
    'string' => 'Sehemu ya :attribute lazima iwe mnyororo wa herufi.',
    'timezone' => 'Sehemu ya :attribute lazima iwe eneo halali la saa.',
    'unique' => ':attribute imeshatumika.',
    'uploaded' => ':attribute imeshindikana kupakiwa.',
    'uppercase' => 'Sehemu ya :attribute lazima iwe kwa herufi kubwa.',
    'url' => 'Sehemu ya :attribute lazima iwe anwani halali ya tovuti (URL).',
    'ulid' => 'Sehemu ya :attribute lazima iwe ULID halali.',
    'uuid' => 'Sehemu ya :attribute lazima iwe UUID halali.',

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
    | The following language lines are used to swap our attribute placeholder
    | with something more reader friendly such as "E-Mail Address" instead
    | of "email". This simply helps us make our message more expressive.
    |
    */

    'attributes' => [],

];
