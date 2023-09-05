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

    'accepted'             => ':attribute को स्वीकार किया जाना चाहिए।',
    'active_url'           => ':attribute एक मान्य URL नहीं है।',
    'after'                => 'द :attribute :डेट के बाद की तारीख होनी चाहिए।',
    'after_or_equal'       => 'द :attribute :डेट के बाद या उसके बराबर की तारीख होनी चाहिए।',
    'alpha'                => ':attribute में केवल अक्षर हो सकते हैं।',
    'alpha_dash'           => ':attribute में केवल अक्षर, संख्याएं, डैश और अंडरस्कोर हो सकते हैं।',
    'alpha_num'            => ':attribute में केवल अक्षर और संख्याएँ हो सकती हैं।',
    'array'                => ':attribute एक सरणी होनी चाहिए।',
    'before'               => 'द :attribute :डेट से पहले की तारीख होनी चाहिए।',
    'before_or_equal'      => 'द :attribute :डेट से पहले या उसके बराबर की तारीख होनी चाहिए।',

    'between'              => [
        'numeric' => ': विशेषता :min और :max के बीच होनी चाहिए।',
        'file'    => ': विशेषता :min और :max किलोबाइट्स के बीच होनी चाहिए।',
        'string'  => ': विशेषता :min और :max वर्णों के बीच होनी चाहिए।',
        'array'   => ': विशेषता :min और :max आइटम के बीच होनी चाहिए।',
    ],

    'boolean'              => ':attribute क्षेत्र सही या गलत होना चाहिए।',
    'confirmed'            => ':attribute पुष्टि मेल नहीं खाती।',
    'date'                 => ':attribute मान्य तिथि नहीं है।',
    'date_format'          => ':attribute प्रारूप :format से मेल नहीं खाती।',
    'different'            => ':attribute और :अन्य अलग-अलग होने चाहिए।',
    'digits'               => ':attribute :अंक अंक होना चाहिए।',
    'digits_between'       => ':attribute :मिनट और :अधिकतम अंकों के बीच होनी चाहिए।',
    'dimensions'           => ':attribute में अमान्य छवि आयाम हैं।',
    'distinct'             => ':attribute फ़ील्ड में एक डुप्लिकेट मान होता है।',
    'email'                => ':attribute एक मान्य ईमेल पता होना चाहिए।',
    'exists'               => 'चयनित :attribute अमान्य है।',
    'file'                 => ':attribute एक फ़ाइल होनी चाहिए।',
    'filled'               => ':attribute फ़ील्ड में एक मान होना चाहिए।',

    'gt'                   => [
        'numeric' => ':attribute :value से बड़ी होनी चाहिए।',
        'file'    => 'द :attribute :value किलोबाइट्स से बड़ा होना चाहिए।',
        'string'  => 'द :attribute :value कैरेक्टर्स से बड़ा होना चाहिए।',
        'array'   => 'द :attribute में :value आइटम्स से अधिक होना चाहिए।',
    ],

    'gte'                  => [
        'numeric' => ':attribute :value से अधिक या बराबर होनी चाहिए।',
        'file'    => ':attribute से बड़ा या बराबर होना चाहिए:value किलोबाइट्स।',
        'string'  => ': attribute इससे बड़ा या बराबर होना चाहिए:value वर्ण।',
        'array'   => 'द :attribute में :value आइटम्स या अधिक होना चाहिए।',
    ],

    'image'                => ':attribute एक छवि होनी चाहिए।',
    'in'                   => 'चयनित :attribute अमान्य है।',
    'in_array'             => "द :attribute फील्ड :other' में मौजूद नहीं है।",
    'integer'              => ':attribute एक पूर्णांक होना चाहिए।',
    'ip'                   => ':attribute एक वैध आईपी पता होना चाहिए।',
    'ipv4'                 => ':attribute एक मान्य IPv4 पता होना चाहिए।',
    'ipv6'                 => ':attribute एक मान्य IPv6 पता होना चाहिए।',
    'json'                 => ':attribute एक वैध JSON स्ट्रिंग होनी चाहिए।',

    'lt'                   => [
        'numeric' => ':attribute :value से कम होनी चाहिए।',
        'file'    => ':attribute :value किलोबाइट से कम होनी चाहिए।',
        'string'  => ':attribute :value वर्णों से कम होनी चाहिए।',
        'array'   => ':attribute में :value आइटम से कम होना चाहिए।',
    ],

    'lte'                  => [
        'numeric' => ': attribute कम या बराबर होनी चाहिए: मान।',
        'file'    => ': attribute कम या बराबर होनी चाहिए: मान किलोबाइट्स।',
        'string'  => ': attribute कम या बराबर होनी चाहिए: मान वर्ण।',
        'array'   => ': attribute में :value आइटम्स से अधिक नहीं होना चाहिए।',
    ],

    'max'                  => [
        'numeric' => ":attribute :max' से अधिक नहीं हो सकती है।",
        'file'    => ':attribute :max किलोबाइट से अधिक नहीं हो सकती है।',
        'string'  => ':attribute :max वर्णों से अधिक नहीं हो सकती है।',
        'array'   => ':attribute में :max आइटम्स से अधिक नहीं हो सकते हैं।',
    ],

    'mimes'                => ':attribute एक प्रकार की फ़ाइल होनी चाहिए::value',
    'mimetypes'            => ':attribute एक प्रकार की फ़ाइल होनी चाहिए::value',

    'min'                  => [
        'numeric' => ':attribute कम से कम :min होनी चाहिए।',
        'file'    => ':attribute कम से कम :min किलोबाइट्स होनी चाहिए।',
        'string'  => ':attribute कम से कम :min कैरेक्टर का होना चाहिए।',
        'array'   => ':attribute में कम से कम :min आइटम होना चाहिए।',
    ],

    'not_in'               => 'चयनित :attribute अमान्य है।',
    'not_regex'            => ':attribute प्रारूप अमान्य है।',
    'numeric'              => ':attribute एक संख्या होनी चाहिए।',
    'present'              => ':attribute फ़ील्ड मौजूद होना चाहिए।',
    'regex'                => ':attribute प्रारूप अमान्य है।',
    'required'             => ':attribute फ़ील्ड आवश्यक है।',
    'required_if'          => ':attribute फ़ील्ड आवश्यक है जब:other है: value',
    'required_unless'      => ':attribute फ़ील्ड आवश्यक है जब तक कि :other :values में न हो।',
    'required_with'        => ':attribute फ़ील्ड आवश्यक है जब:value मौजूद है।',
    'required_with_all'    => ':attribute फ़ील्ड आवश्यक है जब:value मौजूद है।',
    'required_without'     => ':attribute फ़ील्ड आवश्यक है जब:value मौजूद नहीं है।',
    'required_without_all' => ':attribute फ़ील्ड आवश्यक है जब कोई भी: value मौजूद नहीं है।',
    'same'                 => 'द :attribute और :other मैच होना चाहिए।',

    'size'                 => [
        'numeric' => ':attribute होना चाहिए:size',
        'file'    => ':attribute होना चाहिए:size किलोबाइट्स।',
        'string'  => ':attribute होना चाहिए:size वर्ण।',
        'array'   => 'इस विशेषता में :size के आइटम शामिल होने चाहिए।',
    ],

    'string'               => ':attribute एक स्ट्रिंग होनी चाहिए।',
    'timezone'             => ':attribute एक मान्य क्षेत्र होना चाहिए।',
    'unique'               => ':attribute पहले ही ली जा चुकी है।',
    'uploaded'             => ':attribute अपलोड करने में विफल रही।',
    'url'                  => ':attribute प्रारूप अमान्य है।',

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
            'rule-name' => 'सीमा शुल्क संदेश',
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
