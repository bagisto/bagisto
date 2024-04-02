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

    'accepted'        => ':attribute alanı kabul edilmelidir.',
    'accepted_if'     => ':other :value olduğunda :attribute alanı kabul edilmelidir.',
    'active_url'      => ':attribute alanı geçerli bir URL olmalıdır.',
    'after'           => ':attribute alanı :date tarihinden sonra bir tarih olmalıdır.',
    'after_or_equal'  => ':attribute alanı :date tarihinden sonra veya aynı tarih olmalıdır.',
    'alpha'           => ':attribute alanı sadece harfler içermelidir.',
    'alpha_dash'      => ':attribute alanı sadece harfler, rakamlar, tireler ve alt çizgiler içermelidir.',
    'alpha_num'       => ':attribute alanı sadece harfler ve rakamlar içermelidir.',
    'array'           => ':attribute alanı bir dizi olmalıdır.',
    'ascii'           => ':attribute alanı yalnızca tek baytlık alfasayısal karakterler ve semboller içermelidir.',
    'before'          => ':attribute alanı :date tarihinden önce bir tarih olmalıdır.',
    'before_or_equal' => ':attribute alanı :date tarihinden önce veya aynı tarih olmalıdır.',

    'between' => [
        'array'   => ':attribute alanı :min ile :max arasında öğe içermelidir.',
        'file'    => ':attribute alanı :min ile :max kilobayt arasında olmalıdır.',
        'numeric' => ':attribute alanı :min ile :max arasında olmalıdır.',
        'string'  => ':attribute alanı :min ile :max karakter arasında olmalıdır.',
    ],

    'boolean'           => ':attribute alanı true veya false olmalıdır.',
    'can'               => ':attribute alanı yetkisiz bir değer içeriyor.',
    'confirmed'         => ':attribute alanı onayı eşleşmiyor.',
    'current_password'  => 'Şifre yanlış.',
    'date'              => ':attribute alanı geçerli bir tarih olmalıdır.',
    'date_equals'       => ':attribute alanı :date ile aynı tarihte olmalıdır.',
    'date_format'       => ':attribute alanı :format formatına uygun olmalıdır.',
    'decimal'           => ':attribute alanı :decimal ondalık basamağa sahip olmalıdır.',
    'declined'          => ':attribute alanı reddedilmelidir.',
    'declined_if'       => ':other :value olduğunda :attribute alanı reddedilmelidir.',
    'different'         => ':attribute alanı ve :other farklı olmalıdır.',
    'digits'            => ':attribute alanı :digits basamaklı olmalıdır.',
    'digits_between'    => ':attribute alanı :min ile :max arasında basamak olmalıdır.',
    'dimensions'        => ':attribute alanı geçersiz resim boyutlarına sahiptir.',
    'distinct'          => ':attribute alanı tekrarlanan bir değere sahiptir.',
    'doesnt_end_with'   => ':attribute alanı aşağıdakilerden biriyle bitmemelidir: :values.',
    'doesnt_start_with' => ':attribute alanı aşağıdakilerden biriyle başlamamalıdır: :values.',
    'email'             => ':attribute alanı geçerli bir e-posta adresi olmalıdır.',
    'ends_with'         => ':attribute alanı aşağıdakilerden biriyle bitmelidir: :values.',
    'enum'              => 'Seçilen :attribute geçersiz.',
    'exists'            => 'Seçilen :attribute geçersiz.',
    'extensions'        => ':attribute alanı şu uzantılardan birine sahip olmalıdır: :values.',
    'file'              => ':attribute alanı bir dosya olmalıdır.',
    'filled'            => ':attribute alanı bir değere sahip olmalıdır.',

    'gt' => [
        'array'   => ':attribute alanı :value öğeden daha fazla olmalıdır.',
        'file'    => ':attribute alanı :value kilobayttan büyük olmalıdır.',
        'numeric' => ':attribute alanı :value\'dan büyük olmalıdır.',
        'string'  => ':attribute alanı :value karakterden büyük olmalıdır.',
    ],

    'gte' => [
        'array'   => ':attribute alanı :value öğeden veya daha fazlasına sahip olmalıdır.',
        'file'    => ':attribute alanı :value kilobayttan büyük veya eşit olmalıdır.',
        'numeric' => ':attribute alanı :value\'dan büyük veya eşit olmalıdır.',
        'string'  => ':attribute alanı :value karakterden büyük veya eşit olmalıdır.',
    ],

    'hex_color' => ':attribute alanı geçerli bir onaltılık renk olmalıdır.',
    'image'     => ':attribute alanı bir resim olmalıdır.',
    'in'        => 'Seçilen :attribute geçersizdir.',
    'in_array'  => ':attribute alanı :other\'da mevcut olmalıdır.',
    'integer'   => ':attribute alanı bir tam sayı olmalıdır.',
    'ip'        => ':attribute alanı geçerli bir IP adresi olmalıdır.',
    'ipv4'      => ':attribute alanı geçerli bir IPv4 adresi olmalıdır.',
    'ipv6'      => ':attribute alanı geçerli bir IPv6 adresi olmalıdır.',
    'json'      => ':attribute alanı geçerli bir JSON dizisi olmalıdır.',
    'lowercase' => ':attribute alanı küçük harf olmalıdır.',

    'lt' => [
        'array'   => ':attribute alanı :value öğeden daha az olmalıdır.',
        'file'    => ':attribute alanı :value kilobayttan küçük olmalıdır.',
        'numeric' => ':attribute alanı :value\'dan küçük olmalıdır.',
        'string'  => ':attribute alanı :value karakterden az olmalıdır.',
    ],

    'lte' => [
        'array'   => ':attribute alanı :value öğeden fazla olmamalıdır.',
        'file'    => ':attribute alanı :value kilobayttan küçük veya eşit olmalıdır.',
        'numeric' => ':attribute alanı :value\'dan küçük veya eşit olmalıdır.',
        'string'  => ':attribute alanı :value karakterden az veya eşit olmalıdır.',
    ],

    'mac_address' => ':attribute alanı geçerli bir MAC adresi olmalıdır.',

    'max' => [
        'array'   => ':attribute alanı :max öğeden fazla olmamalıdır.',
        'file'    => ':attribute alanı :max kilobayttan büyük olmamalıdır.',
        'numeric' => ':attribute alanı :max\'dan büyük olmamalıdır.',
        'string'  => ':attribute alanı :max karakterden fazla olmamalıdır.',
    ],

    'max_digits' => ':attribute alanı :max basamaktan fazla olmamalıdır.',
    'mimes'      => ':attribute alanı şu türde bir dosya olmalıdır: :values.',
    'mimetypes'  => ':attribute alanı şu türde bir dosya olmalıdır: :values.',

    'min' => [
        'array'   => ':attribute alanı en az :min öğe içermelidir.',
        'file'    => ':attribute alanı en az :min kilobayt olmalıdır.',
        'numeric' => ':attribute alanı en az :min olmalıdır.',
        'string'  => ':attribute alanı en az :min karakter olmalıdır.',
    ],

    'min_digits'       => ':attribute alanı en az :min basamağa sahip olmalıdır.',
    'missing'          => ':attribute alanı eksik olmalıdır.',
    'missing_if'       => ':other :value olduğunda :attribute alanı eksik olmalıdır.',
    'missing_unless'   => ':other :value olmadığında :attribute alanı eksik olmalıdır.',
    'missing_with'     => ':values mevcut olduğunda :attribute alanı eksik olmalıdır.',
    'missing_with_all' => ':values mevcut olduğunda :attribute alanı eksik olmalıdır.',
    'multiple_of'      => ':attribute alanı :value\'nin katı olmalıdır.',
    'not_in'           => 'Seçilen :attribute geçersizdir.',
    'not_regex'        => ':attribute alanı formatı geçersizdir.',
    'numeric'          => ':attribute alanı bir sayı olmalıdır.',

    'password' => [
        'letters'       => ':attribute alanı en az bir harf içermelidir.',
        'mixed'         => ':attribute alanı en az bir büyük harf ve bir küçük harf içermelidir.',
        'numbers'       => ':attribute alanı en az bir rakam içermelidir.',
        'symbols'       => ':attribute alanı en az bir sembol içermelidir.',
        'uncompromised' => 'Verilen :attribute bir veri sızıntısında göründü. Lütfen farklı bir :attribute seçin.',
    ],

    'present'              => ':attribute alanı mevcut olmalıdır.',
    'present_if'           => ':other :value olduğunda :attribute alanı mevcut olmalıdır.',
    'present_unless'       => ':other :value olmadığında :attribute alanı mevcut olmalıdır.',
    'present_with'         => ':values mevcut olduğunda :attribute alanı mevcut olmalıdır.',
    'present_with_all'     => ':values mevcut olduğunda :attribute alanı mevcut olmalıdır.',
    'prohibited'           => ':attribute alanı yasaktır.',
    'prohibited_if'        => ':other :value olduğunda :attribute alanı yasaktır.',
    'prohibited_unless'    => ':other :values içinde olmadığında :attribute alanı yasaktır.',
    'prohibits'            => ':attribute alanı :other\'in mevcut olmasını engeller.',
    'regex'                => ':attribute alanı formatı geçersizdir.',
    'required'             => ':attribute alanı gereklidir.',
    'required_array_keys'  => ':values için :attribute alanı girişler içermelidir.',
    'required_if'          => ':other :value olduğunda :attribute alanı gereklidir.',
    'required_if_accepted' => ':other kabul edildiğinde :attribute alanı gereklidir.',
    'required_unless'      => ':other :values içinde olmadığında :attribute alanı gereklidir.',
    'required_with'        => ':values mevcut olduğunda :attribute alanı gereklidir.',
    'required_with_all'    => ':values mevcut olduğunda :attribute alanı gereklidir.',
    'required_without'     => ':values mevcut olmadığında :attribute alanı gereklidir.',
    'required_without_all' => ':values mevcut olmadığında :attribute alanı gereklidir.',
    'same'                 => ':attribute alanı :other ile eşleşmelidir.',

    'size' => [
        'array'   => ':attribute alanı :size öğe içermelidir.',
        'file'    => ':attribute alanı :size kilobayt olmalıdır.',
        'numeric' => ':attribute alanı :size olmalıdır.',
        'string'  => ':attribute alanı :size karakter olmalıdır.',
    ],

    'starts_with' => ':attribute alanı şu değerlerden biriyle başlamalıdır: :values.',
    'string'      => ':attribute alanı bir dize olmalıdır.',
    'timezone'    => ':attribute alanı geçerli bir saat dilimi olmalıdır.',
    'unique'      => ':attribute zaten alınmıştır.',
    'uploaded'    => ':attribute yüklenemedi.',
    'uppercase'   => ':attribute alanı büyük harf olmalıdır.',
    'url'         => ':attribute alanı geçerli bir URL olmalıdır.',
    'ulid'        => ':attribute alanı geçerli bir ULID olmalıdır.',
    'uuid'        => ':attribute alanı geçerli bir UUID olmalıdır.',

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
            'rule-name' => 'özel mesaj',
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
