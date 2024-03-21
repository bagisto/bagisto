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

    'accepted'        => 'ফিল্ড :attribute অবশ্যই গ্রহণযোগ্য হতে হবে।',
    'accepted_if'     => 'যদি :other :value হয়, তবে :attribute ফিল্ড গ্রহণযোগ্য হতে হবে।',
    'active_url'      => 'ফিল্ড :attribute অবশ্যই একটি বৈধ URL হতে হবে।',
    'after'           => 'ফিল্ড :attribute অবশ্যই :date এর পরের একটি তারিখ হতে হবে।',
    'after_or_equal'  => 'ফিল্ড :attribute অবশ্যই :date এর পরে অথবা সমান হতে হবে।',
    'alpha'           => 'ফিল্ড :attribute অবশ্যই শুধুমাত্র অক্ষর ধারণ করতে হবে।',
    'alpha_dash'      => 'ফিল্ড :attribute অবশ্যই শুধুমাত্র অক্ষর, সংখ্যা, ড্যাশ, এবং আন্ডারস্কোর ধারণ করতে হবে।',
    'alpha_num'       => 'ফিল্ড :attribute অবশ্যই শুধুমাত্র অক্ষর এবং সংখ্যা ধারণ করতে হবে।',
    'array'           => 'ফিল্ড :attribute অবশ্যই একটি অ্যারে হতে হবে।',
    'ascii'           => 'ফিল্ড :attribute অবশ্যই একটি একক-বাইট বর্ণান্ত অক্ষরিক এবং চিহ্ন ধারণ করতে হবে।',
    'before'          => 'ফিল্ড :attribute অবশ্যই :date এর পূর্বের একটি তারিখ হতে হবে।',
    'before_or_equal' => 'ফিল্ড :attribute অবশ্যই :date এর পূর্বে অথবা সমান হতে হবে।',

    'between' => [
        'array'   => 'ফিল্ড :attribute অবশ্যই :min এবং :max আইটেম মধ্যে হতে হবে।',
        'file'    => 'ফিল্ড :attribute অবশ্যই :min এবং :max কিলোবাইট মধ্যে হতে হবে।',
        'numeric' => 'ফিল্ড :attribute অবশ্যই :min এবং :max মধ্যে হতে হবে।',
        'string'  => 'ফিল্ড :attribute অবশ্যই :min এবং :max অক্ষর মধ্যে হতে হবে।',
    ],

    'boolean'           => 'ফিল্ড :attribute অবশ্যই সত্য বা মিথ্যা হতে হবে।',
    'can'               => 'ফিল্ড :attribute অননুমোদিত মান ধারণ করছে।',
    'confirmed'         => 'ফিল্ড :attribute নিশ্চিতকরণের মান মেলেনি।',
    'current_password'  => 'পাসওয়ার্ড ভুল।',
    'date'              => 'ফিল্ড :attribute অবশ্যই একটি বৈধ তারিখ হতে হবে।',
    'date_equals'       => 'ফিল্ড :attribute অবশ্যই :date এর সমান তারিখ হতে হবে।',
    'date_format'       => 'ফিল্ড :attribute অবশ্যই :format ফর্ম্যাটের সাথে মেলে না।',
    'decimal'           => 'ফিল্ড :attribute অবশ্যই :decimal দশমিক স্থান হতে হবে।',
    'declined'          => 'ফিল্ড :attribute অবশ্যই প্রত্যাখ্যান করতে হবে।',
    'declined_if'       => 'যদি :other :value হয়, তবে :attribute ফিল্ড অবশ্যই প্রত্যাখ্যান করতে হবে।',
    'different'         => 'ফিল্ড :attribute এবং :other অবশ্যই আলাদা হতে হবে।',
    'digits'            => 'ফিল্ড :attribute অবশ্যই :digits সংখ্যার হতে হবে।',
    'digits_between'    => 'ফিল্ড :attribute অবশ্যই :min এবং :max সংখ্যার মধ্যে হতে হবে।',
    'dimensions'        => 'ফিল্ড :attribute অবশ্যই অবৈধ চিত্র মাত্রা ধারণ করে।',
    'distinct'          => 'ফিল্ড :attribute অননুকরণশীল মান ধারণ করে।',
    'doesnt_end_with'   => 'ফিল্ড :attribute অবশ্যই নিম্নলিখিত মধ্যে কোনটির সাথে শেষ হবে না: :values।',
    'doesnt_start_with' => 'ফিল্ড :attribute অবশ্যই নিম্নলিখিত মধ্যে কোনটি দিয়ে শুরু হবে না: :values।',
    'email'             => 'ফিল্ড :attribute অবশ্যই একটি বৈধ ইমেল ঠিকানা হতে হবে।',
    'ends_with'         => 'ফিল্ড :attribute অবশ্যই নিম্নলিখিত মধ্যে কোনটিতে শেষ হতে হবে: :values।',
    'enum'              => 'নির্বাচিত :attribute অবৈধ।',
    'exists'            => 'নির্বাচিত :attribute অবৈধ।',
    'extensions'        => 'ফিল্ড :attribute অবশ্যই নিম্নলিখিত একটি এক্সটেনশন হতে হবে: :values।',
    'file'              => 'ফিল্ড :attribute অবশ্যই একটি ফাইল হতে হবে।',
    'filled'            => 'ফিল্ড :attribute অবশ্যই একটি মান ধারণ করতে হবে।',

    'gt' => [
        'array'   => 'ফিল্ড :attribute অবশ্যই :value আইটেমের চেয়ে বেশি হতে হবে।',
        'file'    => 'ফিল্ড :attribute অবশ্যই :value কিলোবাইটের চেয়ে বড় হতে হবে।',
        'numeric' => 'ফিল্ড :attribute অবশ্যই :value এর চেয়ে বড় হতে হবে।',
        'string'  => 'ফিল্ড :attribute অবশ্যই :value অক্ষরের চেয়ে বেশি হতে হবে।',
    ],

    'gte' => [
        'array'   => 'ফিল্ড :attribute অবশ্যই :value আইটেম অথবা তার অধিক হতে হবে।',
        'file'    => 'ফিল্ড :attribute অবশ্যই :value কিলোবাইটের চেয়ে বড় অথবা সমান হতে হবে।',
        'numeric' => 'ফিল্ড :attribute অবশ্যই :value এর চেয়ে বড় অথবা সমান হতে হবে।',
        'string'  => 'ফিল্ড :attribute অবশ্যই :value অক্ষরের চেয়ে বড় অথবা সমান হতে হবে।',
    ],

    'hex_color' => 'ফিল্ড :attribute অবশ্যই একটি বৈধ হেক্সাডেসিমাল রঙ হতে হবে।',
    'image'     => 'ফিল্ড :attribute অবশ্যই একটি ছবি হতে হবে।',
    'in'        => 'নির্বাচিত :attribute অবৈধ।',
    'in_array'  => 'ফিল্ড :attribute অবশ্যই :other এর মধ্যে থাকতে হবে।',
    'integer'   => 'ফিল্ড :attribute অবশ্যই একটি পূর্ণসংখ্যা হতে হবে।',
    'ip'        => 'ফিল্ড :attribute অবশ্যই একটি বৈধ IP ঠিকানা হতে হবে।',
    'ipv4'      => 'ফিল্ড :attribute অবশ্যই একটি বৈধ IPv4 ঠিকানা হতে হবে।',
    'ipv6'      => 'ফিল্ড :attribute অবশ্যই একটি বৈধ IPv6 ঠিকানা হতে হবে।',
    'json'      => 'ফিল্ড :attribute অবশ্যই একটি বৈধ JSON স্ট্রিং হতে হবে।',
    'lowercase' => 'ফিল্ড :attribute অবশ্যই ছো',

    'lt' => [
        'array'   => 'ফিল্ড :attribute অবশ্যই :value আইটেমের চেয়ে কম হতে হবে।',
        'file'    => 'ফিল্ড :attribute অবশ্যই :value কিলোবাইটের চেয়ে ছোট হতে হবে।',
        'numeric' => 'ফিল্ড :attribute অবশ্যই :value এর চেয়ে ছোট হতে হবে।',
        'string'  => 'ফিল্ড :attribute অবশ্যই :value অক্ষরের চেয়ে কম হতে হবে।',
    ],

    'lte' => [
        'array'   => 'ফিল্ড :attribute অবশ্যই :value আইটেম অথবা তার কম হতে হবে।',
        'file'    => 'ফিল্ড :attribute অবশ্যই :value কিলোবাইটের চেয়ে ছোট অথবা সমান হতে হবে।',
        'numeric' => 'ফিল্ড :attribute অবশ্যই :value এর চেয়ে ছোট অথবা সমান হতে হবে।',
        'string'  => 'ফিল্ড :attribute অবশ্যই :value অক্ষরের চেয়ে কম অথবা সমান হতে হবে।',
    ],

    'mac_address' => 'ফিল্ড :attribute অবশ্যই একটি বৈধ MAC ঠিকানা হতে হবে।',

    'max' => [
        'array'   => 'ফিল্ড :attribute অবশ্যই :max আইটেমের বেশি হতে পারবে না।',
        'file'    => 'ফিল্ড :attribute অবশ্যই :max কিলোবাইটের বেশি হতে পারবে না।',
        'numeric' => 'ফিল্ড :attribute অবশ্যই :max এর বেশি হতে পারবে না।',
        'string'  => 'ফিল্ড :attribute অবশ্যই :max অক্ষরের বেশি হতে পারবে না।',
    ],

    'max_digits' => 'ফিল্ড :attribute অবশ্যই :max ডিজিটের বেশি হতে পারবে না।',
    'mimes'      => 'ফিল্ড :attribute অবশ্যই নিম্নলিখিত ধরণের ফাইল হতে হবে: :values।',
    'mimetypes'  => 'ফিল্ড :attribute অবশ্যই নিম্নলিখিত ধরণের ফাইল হতে হবে: :values।',

    'min' => [
        'array'   => 'ফিল্ড :attribute অবশ্যই অন্তত :min আইটেম থাকতে হবে।',
        'file'    => 'ফিল্ড :attribute অবশ্যই অন্তত :min কিলোবাইট থাকতে হবে।',
        'numeric' => 'ফিল্ড :attribute অবশ্যই অন্তত :min থাকতে হবে।',
        'string'  => 'ফিল্ড :attribute অবশ্যই অন্তত :min অক্ষর থাকতে হবে।',
    ],

    'min_digits'       => 'ফিল্ড :attribute অবশ্যই অন্তত :min ডিজিট থাকতে হবে।',
    'missing'          => 'ফিল্ড :attribute অনুপস্থিত হতে হবে।',
    'missing_if'       => ':other এর মান :value হলে ফিল্ড :attribute অনুপস্থিত হতে হবে।',
    'missing_unless'   => ':other এর মান :value না হলে ফিল্ড :attribute অনুপস্থিত হতে হবে।',
    'missing_with'     => ':values উপস্থিত হলে ফিল্ড :attribute অনুপস্থিত হতে হবে।',
    'missing_with_all' => ':values সবগুলি উপস্থিত হলে ফিল্ড :attribute অনুপস্থিত হতে হবে।',
    'multiple_of'      => 'ফিল্ড :attribute অবশ্যই :value এর একটি গুণফল হতে হবে।',
    'not_in'           => 'নির্বাচিত :attribute অবৈধ।',
    'not_regex'        => 'ফিল্ড :attribute ফরম্যাট অবৈধ।',
    'numeric'          => 'ফিল্ড :attribute অবশ্যই একটি সংখ্যা হতে হবে।',

    'password' => [
        'letters'       => 'ফিল্ড :attribute অবশ্যই কমপক্ষে একটি অক্ষর থাকতে হবে।',
        'mixed'         => 'ফিল্ড :attribute অবশ্যই কমপক্ষে একটি বৃহত্তম এবং একটি ছোট্তম অক্ষর থাকতে হবে।',
        'numbers'       => 'ফিল্ড :attribute অবশ্যই কমপক্ষে একটি সংখ্যা থাকতে হবে।',
        'symbols'       => 'ফিল্ড :attribute অবশ্যই কমপক্ষে একটি চিহ্ন থাকতে হবে।',
        'uncompromised' => 'প্রদত্ত :attribute একটি ডেটা লিকে প্রকাশিত হয়েছে। অনুগ্রহ করে একটি পৃথক :attribute নির্বাচন করুন।',
    ],

    'present'              => 'ফিল্ড :attribute অবশ্যই উপস্থিত হতে হবে।',
    'present_if'           => ':other এর মান :value হলে ফিল্ড :attribute অবশ্যই উপস্থিত হতে হবে।',
    'present_unless'       => ':other এর মান :value না হলে ফিল্ড :attribute অবশ্যই উপস্থিত হতে হবে।',
    'present_with'         => ':values উপস্থিত হলে ফিল্ড :attribute অবশ্যই উপস্থিত হতে হবে।',
    'present_with_all'     => ':values সবগুলি উপস্থিত হলে ফিল্ড :attribute অবশ্যই উপস্থিত হতে হবে।',
    'prohibited'           => 'ফিল্ড :attribute নিষিদ্ধ।',
    'prohibited_if'        => ':other এর মান :value হলে ফিল্ড :attribute নিষিদ্ধ।',
    'prohibited_unless'    => ':other অন্তত একটি :values হলে ফিল্ড :attribute নিষিদ্ধ।',
    'prohibits'            => 'ফিল্ড :attribute :other এর উপস্থিতিকে নিষিদ্ধ করে।',
    'regex'                => 'ফিল্ড :attribute ফরম্যাট অবৈধ।',
    'required'             => 'ফিল্ড :attribute প্রয়োজন।',
    'required_array_keys'  => 'ফিল্ড :attribute অন্তর্ভুক্ত না :values এর জন্য কী দরকার।',
    'required_if'          => ':other এর মান :value হলে ফিল্ড :attribute প্রয়োজন।',
    'required_if_accepted' => ':other এর মান গ্রহণ করা হলে ফিল্ড :attribute প্রয়োজন।',
    'required_unless'      => ':other এর মান :values এর মধ্যে না থাকলে ফিল্ড :attribute প্রয়োজন।',
    'required_with'        => ':values উপস্থিত হলে ফিল্ড :attribute প্রয়োজন।',
    'required_with_all'    => ':values সব উপস্থিত হলে ফিল্ড :attribute প্রয়োজন।',
    'required_without'     => ':values উপস্থিত না হলে ফিল্ড :attribute প্রয়োজন।',
    'required_without_all' => ':values কোনটি উপস্থিত না হলে ফিল্ড :attribute প্রয়োজন।',
    'same'                 => 'ফিল্ড :attribute অবশ্যই :other এর সাথে মেলে যাওয়া উচিত।',

    'size' => [
        'array'   => 'ফিল্ড :attribute অবশ্যই :size আইটেম থাকতে হবে।',
        'file'    => 'ফিল্ড :attribute অবশ্যই :size কিলোবাইট হতে হবে।',
        'numeric' => 'ফিল্ড :attribute অবশ্যই :size হতে হবে।',
        'string'  => 'ফিল্ড :attribute অবশ্যই :size অক্ষর হতে হবে।',
    ],

    'starts_with' => 'ফিল্ড :attribute অবশ্যই নিম্নলিখিত মধ্যে একটি দিয়ে শুরু হতে হবে: :values।',
    'string'      => 'ফিল্ড :attribute অবশ্যই একটি স্ট্রিং হতে হবে।',
    'timezone'    => 'ফিল্ড :attribute অবশ্যই একটি বৈধ সময় অঞ্চল হতে হবে।',
    'unique'      => 'ফিল্ড :attribute ইতিমধ্যে নেওয়া হয়েছে।',
    'uploaded'    => 'ফিল্ড :attribute আপলোড করা ব্যর্থ হয়েছে।',
    'uppercase'   => 'ফিল্ড :attribute অবশ্যই বড় অক্ষরে হতে হবে।',
    'url'         => 'ফিল্ড :attribute অবশ্যই একটি বৈধ URL হতে হবে।',
    'ulid'        => 'ফিল্ড :attribute অবশ্যই একটি বৈধ ULID হতে হবে।',
    'uuid'        => 'ফিল্ড :attribute অবশ্যই একটি বৈধ UUID হতে হবে।',

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
            'rule-name' => 'কাস্টম-বার্তা',
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
