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

    'accepted'        => 'फ़ील्ड :attribute स्वीकृत होना चाहिए।',
    'accepted_if'     => 'जब :other :value हो, तो :attribute फ़ील्ड स्वीकृत होना चाहिए।',
    'active_url'      => 'फ़ील्ड :attribute एक मान्य URL होना चाहिए।',
    'after'           => 'फ़ील्ड :attribute :date के बाद की तारीख होनी चाहिए।',
    'after_or_equal'  => 'फ़ील्ड :attribute :date के बाद या उसके बराबर की तारीख होनी चाहिए।',
    'alpha'           => 'फ़ील्ड :attribute में केवल अक्षर होने चाहिए।',
    'alpha_dash'      => 'फ़ील्ड :attribute में केवल अक्षर, संख्या, डैश और अंडरस्कोर होने चाहिए।',
    'alpha_num'       => 'फ़ील्ड :attribute में केवल अक्षर और संख्याएँ होनी चाहिए।',
    'array'           => 'फ़ील्ड :attribute एक एरे होना चाहिए।',
    'ascii'           => 'फ़ील्ड :attribute केवल एकल-बाइट वाले अक्षरमाला और प्रतीक होने चाहिए।',
    'before'          => 'फ़ील्ड :attribute :date के पहले की तारीख होनी चाहिए।',
    'before_or_equal' => 'फ़ील्ड :attribute :date के पहले या उसके बराबर की तारीख होनी चाहिए।',

    'between' => [
        'array'   => 'फ़ील्ड :attribute में :min और :max आइटम्स होने चाहिए।',
        'file'    => 'फ़ील्ड :attribute :min और :max किलोबाइट्स के बीच होना चाहिए।',
        'numeric' => 'फ़ील्ड :attribute :min और :max के बीच होना चाहिए।',
        'string'  => 'फ़ील्ड :attribute :min और :max वर्णों के बीच होना चाहिए।',
    ],

    'boolean'           => 'फ़ील्ड :attribute सही या गलत होना चाहिए।',
    'can'               => 'फ़ील्ड :attribute अनधिकृत मान शामिल करता है।',
    'confirmed'         => 'फ़ील्ड :attribute पुष्टिकरण मेल नहीं खाता।',
    'current_password'  => 'पासवर्ड गलत है।',
    'date'              => 'फ़ील्ड :attribute में एक मान्य तारीख होनी चाहिए।',
    'date_equals'       => 'फ़ील्ड :attribute को :date के बराबर तारीख होनी चाहिए।',
    'date_format'       => 'फ़ील्ड :attribute को :format स्वरूप के साथ मेल नहीं खाता।',
    'decimal'           => 'फ़ील्ड :attribute में :decimal दशमलव स्थान होने चाहिए।',
    'declined'          => 'फ़ील्ड :attribute को अस्वीकार किया जाना चाहिए।',
    'declined_if'       => 'जब :other :value हो, तो :attribute फ़ील्ड को अस्वीकार किया जाना चाहिए।',
    'different'         => 'फ़ील्ड :attribute और :other अलग होना चाहिए।',
    'digits'            => 'फ़ील्ड :attribute में :digits अंक होने चाहिए।',
    'digits_between'    => 'फ़ील्ड :attribute में :min और :max अंकों के बीच होने चाहिए।',
    'dimensions'        => 'फ़ील्ड :attribute में अमान्य चित्र आयाम हैं।',
    'distinct'          => 'फ़ील्ड :attribute में एक से अधिक मान नहीं हो सकते।',
    'doesnt_end_with'   => 'फ़ील्ड :attribute को निम्नलिखित में से किसी एक के साथ समाप्त नहीं किया जाना चाहिए: :values।',
    'doesnt_start_with' => 'फ़ील्ड :attribute को निम्नलिखित में से किसी एक के साथ प्रारंभ नहीं किया जाना चाहिए: :values।',
    'email'             => 'फ़ील्ड :attribute एक मान्य ईमेल पता होना चाहिए।',
    'ends_with'         => 'फ़ील्ड :attribute को निम्नलिखित में से किसी एक के साथ समाप्त किया जाना चाहिए: :values।',
    'enum'              => 'चयनित :attribute अमान्य है।',
    'exists'            => 'चयनित :attribute अमान्य है।',
    'extensions'        => 'फ़ील्ड :attribute में निम्नलिखित में से कोई भी एक एक्सटेंशन होना चाहिए: :values।',
    'file'              => 'फ़ील्ड :attribute एक फ़ाइल होनी चाहिए।',
    'filled'            => 'फ़ील्ड :attribute का मान होना आवश्यक है।',

    'gt' => [
        'array'   => 'फ़ील्ड :attribute में :value आइटम्स से अधिक होने चाहिए।',
        'file'    => 'फ़ील्ड :attribute :value किलोबाइट्स से अधिक होना चाहिए।',
        'numeric' => 'फ़ील्ड :attribute :value से अधिक होना चाहिए।',
        'string'  => 'फ़ील्ड :attribute :value वर्णों से अधिक होना चाहिए।',
    ],

    'gte' => [
        'array'   => 'फ़ील्ड :attribute में :value आइटम्स या उससे अधिक होने चाहिए।',
        'file'    => 'फ़ील्ड :attribute :value किलोबाइट्स से अधिक या उसके बराबर होना चाहिए।',
        'numeric' => 'फ़ील्ड :attribute :value से अधिक या उसके बराबर होना चाहिए।',
        'string'  => 'फ़ील्ड :attribute :value वर्णों से अधिक या उसके बराबर होना चाहिए।',
    ],

    'hex_color'    => 'फ़ील्ड :attribute एक मान्य हेक्साडेसिमल रंग होना चाहिए।',
    'image'        => 'फ़ील्ड :attribute एक छवि होनी चाहिए।',
    'in'           => 'चयनित :attribute अमान्य है।',
    'in_array'     => 'फ़ील्ड :attribute :other में मौजूद नहीं है।',
    'integer'      => 'फ़ील्ड :attribute एक पूर्णांक होना चाहिए।',
    'ip'           => 'फ़ील्ड :attribute एक मान्य IP पता होना चाहिए।',
    'ipv4'         => 'फ़ील्ड :attribute एक मान्य IPv4 पता होना चाहिए।',
    'ipv6'         => 'फ़ील्ड :attribute एक मान्य IPv6 पता होना चाहिए।',
    'json'         => 'फ़ील्ड :attribute एक मान्य JSON स्ट्रिंग होनी चाहिए।',
    'lowercase'    => 'फ़ील्ड :attribute लोअरकेस होना चाहिए।',

    'lt' => [
        'array'   => 'फ़ील्ड :attribute में :value आइटम्स से कम होने चाहिए।',
        'file'    => 'फ़ील्ड :attribute :value किलोबाइट्स से कम होना चाहिए।',
        'numeric' => 'फ़ील्ड :attribute :value से कम होना चाहिए।',
        'string'  => 'फ़ील्ड :attribute :value वर्णों से कम होना चाहिए।',
    ],

    'lte' => [
        'array'   => 'फ़ील्ड :attribute में :value आइटम्स से अधिक नहीं होना चाहिए।',
        'file'    => 'फ़ील्ड :attribute :value किलोबाइट्स से अधिक या उसके बराबर होना चाहिए।',
        'numeric' => 'फ़ील्ड :attribute :value से अधिक या उसके बराबर होना चाहिए।',
        'string'  => 'फ़ील्ड :attribute :value वर्णों से अधिक नहीं होना चाहिए।',
    ],

    'mac_address' => 'फ़ील्ड :attribute एक मान्य MAC पता होना चाहिए।',

    'max' => [
        'array'   => 'फ़ील्ड :attribute :max आइटम्स से अधिक नहीं होना चाहिए।',
        'file'    => 'फ़ील्ड :attribute :max किलोबाइट्स से अधिक नहीं होना चाहिए।',
        'numeric' => 'फ़ील्ड :attribute :max से अधिक नहीं होना चाहिए।',
        'string'  => 'फ़ील्ड :attribute :max वर्णों से अधिक नहीं होना चाहिए।',
    ],

    'max_digits' => 'फ़ील्ड :attribute में :max अंकों से अधिक नहीं होना चाहिए।',
    'mimes'      => 'फ़ील्ड :attribute एक फ़ाइल के प्रकार :values होनी चाहिए।',
    'mimetypes'  => 'फ़ील्ड :attribute एक फ़ाइल के प्रकार :values होनी चाहिए।',

    'min' => [
        'array'   => 'फ़ील्ड :attribute में कम से कम :min आइटम्स होने चाहिए।',
        'file'    => 'फ़ील्ड :attribute कम से कम :min किलोबाइट्स होने चाहिए।',
        'numeric' => 'फ़ील्ड :attribute कम से कम :min होना चाहिए।',
        'string'  => 'फ़ील्ड :attribute कम से कम :min वर्ण होने चाहिए।',
    ],

    'min_digits'       => 'फ़ील्ड :attribute में कम से कम :min अंक होने चाहिए।',
    'missing'          => 'फ़ील्ड :attribute अनुपस्थित होना चाहिए।',
    'missing_if'       => 'फ़ील्ड :attribute :other :value होने पर अनुपस्थित होना चाहिए।',
    'missing_unless'   => 'फ़ील्ड :attribute :other :value नहीं होने पर अनुपस्थित होना चाहिए।',
    'missing_with'     => 'फ़ील्ड :attribute :values मौजूद होने पर अनुपस्थित होना चाहिए।',
    'missing_with_all' => 'फ़ील्ड :attribute :values मौजूद होने पर अनुपस्थित होना चाहिए।',
    'multiple_of'      => 'फ़ील्ड :attribute :value की एक गुणा होनी चाहिए।',
    'not_in'           => 'चयनित :attribute अमान्य है।',
    'not_regex'        => 'फ़ील्ड :attribute का प्रारूप अमान्य है।',
    'numeric'          => 'फ़ील्ड :attribute एक संख्या होनी चाहिए।',

    'password' => [
        'letters'       => 'फ़ील्ड :attribute में कम से कम एक अक्षर होना चाहिए।',
        'mixed'         => 'फ़ील्ड :attribute में कम से कम एक बड़ा और एक छोटा अक्षर होना चाहिए।',
        'numbers'       => 'फ़ील्ड :attribute में कम से कम एक संख्या होनी चाहिए।',
        'symbols'       => 'फ़ील्ड :attribute में कम से कम एक विशेष चिह्न होना चाहिए।',
        'uncompromised' => 'दी गई :attribute डेटा लीक में प्रकट हो चुका है। कृपया एक अलग :attribute चुनें।',
    ],

    'present'              => 'फ़ील्ड :attribute मौजूद होना चाहिए।',
    'present_if'           => 'फ़ील्ड :attribute मौजूद होना चाहिए जब :other :value हो।',
    'present_unless'       => 'फ़ील्ड :attribute मौजूद होना चाहिए जब तक :other :value नहीं है।',
    'present_with'         => 'फ़ील्ड :attribute मौजूद होना चाहिए जब :values मौजूद है।',
    'present_with_all'     => 'फ़ील्ड :attribute मौजूद होना चाहिए जब :values मौजूद हैं।',
    'prohibited'           => 'फ़ील्ड :attribute निषिद्ध है।',
    'prohibited_if'        => 'फ़ील्ड :attribute :other :value होने पर निषिद्ध है।',
    'prohibited_unless'    => 'फ़ील्ड :attribute :other :values में नहीं होने पर निषिद्ध है।',
    'prohibits'            => 'फ़ील्ड :attribute :other को मौजूद न होने देता है।',
    'regex'                => 'फ़ील्ड :attribute का प्रारूप अमान्य है।',
    'required'             => 'फ़ील्ड :attribute आवश्यक है।',
    'required_array_keys'  => 'फ़ील्ड :attribute में निम्नलिखित लिए दर्ज की गई आइटम्स होने चाहिए: :values।',
    'required_if'          => 'फ़ील्ड :attribute आवश्यक है जब :other :value हो।',
    'required_if_accepted' => 'फ़ील्ड :attribute आवश्यक है जब :other स्वीकृत हो।',
    'required_unless'      => 'फ़ील्ड :attribute आवश्यक है जब तक :other :values में नहीं है।',
    'required_with'        => 'फ़ील्ड :attribute आवश्यक है जब :values मौजूद है।',
    'required_with_all'    => 'फ़ील्ड :attribute आवश्यक है जब :values मौजूद हैं।',
    'required_without'     => 'फ़ील्ड :attribute आवश्यक है जब :values मौजूद नहीं है।',
    'required_without_all' => 'फ़ील्ड :attribute आवश्यक है जब :values में से कोई भी मौजूद नहीं हैं।',
    'same'                 => 'फ़ील्ड :attribute और :other मेल खाने चाहिए।',

    'size' => [
        'array'   => 'फ़ील्ड :attribute में :size आइटम होने चाहिए।',
        'file'    => 'फ़ील्ड :attribute :size किलोबाइट्स होना चाहिए।',
        'numeric' => 'फ़ील्ड :attribute का मान :size होना चाहिए।',
        'string'  => 'फ़ील्ड :attribute :size अक्षरों का होना चाहिए।',
    ],

    'starts_with' => 'फ़ील्ड :attribute निम्नलिखित में से किसी एक से शुरू होना चाहिए: :values।',
    'string'      => 'फ़ील्ड :attribute स्ट्रिंग होनी चाहिए।',
    'timezone'    => 'फ़ील्ड :attribute मान्य समय क्षेत्र होना चाहिए।',
    'unique'      => 'फ़ील्ड :attribute पहले से ही लिया गया है।',
    'uploaded'    => 'फ़ाइल :attribute अपलोड करने में विफल रही।',
    'uppercase'   => 'फ़ील्ड :attribute अपरकेस होना चाहिए।',
    'url'         => 'फ़ील्ड :attribute मान्य URL होना चाहिए।',
    'ulid'        => 'फ़ील्ड :attribute मान्य ULID होना चाहिए।',
    'uuid'        => 'फ़ील्ड :attribute मान्य UUID होना चाहिए।',

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
