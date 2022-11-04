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

    'accepted'             => ':attribute অবশ্যই গ্রহণ করতে হবে.',
    'active_url'           => ':attribute একটি বৈধ URL নয়।',
    'after'                => ':attribute অবশ্যই :date এর পরে একটি তারিখ হতে হবে৷',
    'after_or_equal'       => ':attribute অবশ্যই :date এর পরে বা তার সমান হতে হবে৷',
    'alpha'                => ':attribute শুধুমাত্র অক্ষর থাকতে পারে।',
    'alpha_dash'           => ':attribute শুধুমাত্র অক্ষর, সংখ্যা, ড্যাশ এবং আন্ডারস্কোর থাকতে পারে।',
    'alpha_num'            => ':attribute শুধুমাত্র অক্ষর এবং সংখ্যা থাকতে পারে।',
    'array'                => ':attribute একটি অ্যারে হতে হবে।',
    'before'               => ':attribute অবশ্যই :date এর আগে একটি তারিখ হতে হবে।',
    'before_or_equal'      => ':attribute টি অবশ্যই :date এর আগে বা সমান একটি তারিখ হতে হবে।',
    'between'              => [
        'numeric' => ':attribute অবশ্যই :min এবং :max এর মধ্যে হতে হবে।',
        'file'    => ':attribute অবশ্যই :min এবং :max কিলোবাইটের মধ্যে হতে হবে৷',
        'string'  => ':attribute অবশ্যই :min এবং :max অক্ষরের মধ্যে হতে হবে।',
        'array'   => ':attribute এর মধ্যে থাকতে হবে :min এবং :max আইটেম।',
    ],
    'boolean'              => ':attribute ক্ষেত্রটি সত্য বা মিথ্যা হতে হবে।',
    'confirmed'            => ':attribute নিশ্চিতকরণ মেলে না।',
    'date'                 => ':attribute একটি বৈধ তারিখ নয়।',
    'date_format'          => ':attribute টি :format ফর্ম্যাটের সাথে মেলে না।',
    'different'            => ':attribute এবং :other আলাদা হতে হবে।',
    'digits'               => ':attribute অবশ্যই :digits সংখ্যা হতে হবে।.',
    'digits_between'       => ':attribute অবশ্যই :min এবং :max সংখ্যার মধ্যে হতে হবে।',
    'dimensions'           => ':attribute অবৈধ চিত্রের মাত্রা রয়েছে।',
    'distinct'             => ':attribute ক্ষেত্রের একটি ডুপ্লিকেট মান আছে।',
    'email'                => ':attribute একটি বৈধ ইমেল ঠিকানা হতে হবে।',
    'exists'               => 'নির্বাচিত :attribute অবৈধ।',
    'file'                 => ':attribute একটি ফাইল হতে হবে।',
    'filled'               => ':attribute ক্ষেত্রের একটি মান থাকতে হবে।',
    'gt'                   => [
        'numeric' => ':attribute অবশ্যই :value এর থেকে বড় হতে হবে।',
        'file'    => ':attribute অবশ্যই :value কিলোবাইটের চেয়ে বেশি হতে হবে।',
        'string'  => ':attribute অবশ্যই :value অক্ষরের চেয়ে বড় হতে হবে।',
        'array'   => ':attribute-এ অবশ্যই :value আইটেম এর থেকে বেশি থাকতে হবে।',
    ],
    'gte'                  => [
        'numeric' => ':attribute অবশ্যই :value এর চেয়ে বড় বা সমান হতে হবে।',
        'file'    => ':attribute অবশ্যই :value কিলোবাইটের চেয়ে বড় বা সমান হতে হবে৷',
        'string'  => ':attribute অবশ্যই :value অক্ষরের চেয়ে বড় বা সমান হতে হবে।',
        'array'   => ':attribute এর অবশ্যই :value আইটেম বা তার বেশি থাকতে হবে।',
    ],
    'image'                => ':attribute একটি ছবি হতে হবে।',
    'in'                   => 'নির্বাচিত :attribute অবৈধ।',
    'in_array'             => ':attribute ক্ষেত্রটি :other মধ্যে বিদ্যমান নেই।',
    'integer'              => ':attribute একটি পূর্ণসংখ্যা হতে হবে।',
    'ip'                   => ':attribute একটি বৈধ আইপি ঠিকানা হতে হবে।',
    'ipv4'                 => ':attribute একটি বৈধ IPv4 ঠিকানা হতে হবে।',
    'ipv6'                 => ':attribute একটি বৈধ IPv6 ঠিকানা হতে হবে।',
    'json'                 => ':attribute একটি বৈধ JSON স্ট্রিং হতে হবে।',
    'lt'                   => [
        'numeric' => ':attribute অবশ্যই :value-এর চেয়ে কম হতে হবে।',
        'file'    => ':attribute অবশ্যই :value কিলোবাইটের চেয়ে কম হতে হবে।',
        'string'  => ':attribute অবশ্যই :value অক্ষরের চেয়ে কম হতে হবে।',
        'array'   => ':attribute এর থেকে কম :value আইটেম থাকতে হবে।',
    ],
    'lte'                  => [
        'numeric' => ':attribute অবশ্যই :value এর থেকে কম বা সমান হতে হবে।',
        'file'    => ':attribute অবশ্যই :value কিলোবাইটের থেকে কম বা সমান হতে হবে।',
        'string'  => ':attribute অবশ্যই :value অক্ষরের কম বা সমান হতে হবে।',
        'array'   => ':attribute অবশ্যই :value আইটেমের বেশি থাকা উচিত নয়।',
    ],
    'max'                  => [
        'numeric' => 'The :attribute may not be greater than :max.',
        'file'    => 'The :attribute may not be greater than :max kilobytes.',
        'string'  => 'The :attribute may not be greater than :max characters.',
        'array'   => 'The :attribute may not have more than :max items.',
    ],
    'mimes'                => 'The :attribute must be a file of type: :values.',
    'mimetypes'            => 'The :attribute must be a file of type: :values.',
    'min'                  => [
        'numeric' => 'The :attribute must be at least :min.',
        'file'    => 'The :attribute must be at least :min kilobytes.',
        'string'  => 'The :attribute must be at least :min characters.',
        'array'   => 'The :attribute must have at least :min items.',
    ],
    'not_in'               => 'The selected :attribute is invalid.',
    'not_regex'            => 'The :attribute format is invalid.',
    'numeric'              => 'The :attribute must be a number.',
    'present'              => 'The :attribute field must be present.',
    'regex'                => 'The :attribute format is invalid.',
    'required'             => 'The :attribute field is required.',
    'required_if'          => 'The :attribute field is required when :other is :value.',
    'required_unless'      => 'The :attribute field is required unless :other is in :values.',
    'required_with'        => 'The :attribute field is required when :values is present.',
    'required_with_all'    => 'The :attribute field is required when :values is present.',
    'required_without'     => 'The :attribute field is required when :values is not present.',
    'required_without_all' => ':value র কোনোটি উপস্থিত না থাকলে :attribute ক্ষেত্রের প্রয়োজন হয়৷',
    'same'                 => ':attribute এবং :other অবশ্যই মিলবে।',
    'size'                 => [
        'numeric' => ':attribute অবশ্যই :size হতে হবে।',
        'file'    => ':attribute অবশ্যই :size কিলোবাইট হতে হবে।',
        'string'  => ':attribute অবশ্যই :size অক্ষর হতে হবে।',
        'array'   => ':attribute এ অবশ্যই :size আইটেম থাকতে হবে।.',
    ],
    'string'               => ':attribute একটি স্ট্রিং হতে হবে।',
    'timezone'             => ':attribute অবশ্যই একটি বৈধ অঞ্চল হতে হবে।',
    'unique'               => ':attribute ইতিমধ্যে নেওয়া হয়েছে।',
    'uploaded'             => ':attribute আপলোড করতে ব্যর্থ হয়েছে।',
    'url'                  => ':attribute বিন্যাসটি অবৈধ।',

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
