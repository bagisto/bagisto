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

    'accepted'             => ':attributeは受け入れる必要があります。',
    'active_url'           => ':attributeは有効なURLではありません。',
    'after'                => ':attributeは:dateより後の日付である必要があります。',
    'after_or_equal'       => ':attributeは:date以降の日付である必要があります。',
    'alpha'                => ':attributeは英字のみが使用できます。',
    'alpha_dash'           => ':attributeは英数字、ダッシュ、アンダースコアのみが使用できます。',
    'alpha_num'            => ':attributeは英数字のみが使用できます。',
    'array'                => ':attributeは配列である必要があります。',
    'before'               => ':attributeは:dateより前の日付である必要があります。',
    'before_or_equal'      => ':attributeは:date以前の日付であるか、同じ日付である必要があります。',

    'between'              => [
        'numeric' => ':attributeは:minから:maxの間である必要があります。',
        'file'    => ':attributeは:minから:maxキロバイトの間である必要があります。',
        'string'  => ':attributeは:minから:max文字の間である必要があります。',
        'array'   => ':attributeは:minから:max個のアイテムを持つ必要があります。',
    ],

    'boolean'              => ':attributeフィールドはtrueまたはfalseである必要があります。',
    'confirmed'            => ':attributeの確認が一致しません。',
    'date'                 => ':attributeは有効な日付ではありません。',
    'date_format'          => ':attributeはフォーマット:formatと一致しません。',
    'different'            => ':attributeと:otherは異なる必要があります。',
    'digits'               => ':attributeは:digits桁である必要があります。',
    'digits_between'       => ':attributeは:minから:max桁の間である必要があります。',
    'dimensions'           => ':attributeは無効な画像サイズです。',
    'distinct'             => ':attributeフィールドには重複した値があります。',
    'email'                => ':attributeは有効なメールアドレスである必要があります。',
    'exists'               => '選択した:attributeは無効です。',
    'file'                 => ':attributeはファイルである必要があります。',
    'filled'               => ':attributeフィールドには値が必要です。',

    'gt'                   => [
        'numeric' => ':attributeは:valueより大きい必要があります。',
        'file'    => ':attributeは:valueキロバイトより大きい必要があります。',
        'string'  => ':attributeは:value文字より大きい必要があります。',
        'array'   => ':attributeは:value個以上のアイテムを持つ必要があります。',
    ],

    'gte'                  => [
        'numeric' => ':attributeは:value以上である必要があります。',
        'file'    => ':attributeは:valueキロバイト以上である必要があります。',
        'string'  => ':attributeは:value文字以上である必要があります。',
        'array'   => ':attributeは:value個以上のアイテムを持つ必要があります。',
    ],

    'image'                => ':attributeは画像である必要があります。',
    'in'                   => '選択した:attributeは無効です。',
    'in_array'             => ':attributeフィールドは:otherに存在しません。',
    'integer'              => ':attributeは整数である必要があります。',
    'ip'                   => ':attributeは有効なIPアドレスである必要があります。',
    'ipv4'                 => ':attributeは有効なIPv4アドレスである必要があります。',
    'ipv6'                 => ':attributeは有効なIPv6アドレスである必要があります。',
    'json'                 => ':attributeは有効なJSON文字列である必要があります。',

    'lt'                   => [
        'numeric' => ':attributeは:valueより小さくなければなりません。',
        'file'    => ':attributeは:valueキロバイトより小さくなければなりません。',
        'string'  => ':attributeは:value文字より小さくなければなりません。',
        'array'   => ':attributeは:value個未満のアイテムを持つ必要があります。',
    ],

    'lte'                  => [
        'numeric' => ':attributeは:value以下である必要があります。',
        'file'    => ':attributeは:valueキロバイト以下である必要があります。',
        'string'  => ':attributeは:value文字以下である必要があります。',
        'array'   => ':attributeは:value個以下のアイテムを持つ必要があります。',
    ],

    'max'                  => [
        'numeric' => ':attributeは:max以下である必要があります。',
        'file'    => ':attributeは:maxキロバイト以下である必要があります。',
        'string'  => ':attributeは:max文字以下である必要があります。',
        'array'   => ':attributeは:max個以下のアイテムを持つ必要があります。',
    ],

    'mimes'                => ':attributeは:valuesタイプのファイルである必要があります。',
    'mimetypes'            => ':attributeは:valuesタイプのファイルである必要があります。',

    'min'                  => [
        'numeric' => ':attributeは:min以上である必要があります。',
        'file'    => ':attributeは:minキロバイト以上である必要があります。',
        'string'  => ':attributeは:min文字以上である必要があります。',
        'array'   => ':attributeは:min個以上のアイテムを持つ必要があります。',
    ],

    'not_in'               => '選択した:attributeは無効です。',
    'not_regex'            => ':attributeのフォーマットが無効です。',
    'numeric'              => ':attributeは数字である必要があります。',
    'present'              => ':attributeフィールドは存在する必要があります。',
    'regex'                => ':attributeのフォーマットが無効です。',
    'required'             => ':attributeフィールドは必須です。',
    'required_if'          => ':otherが:valueの場合、:attributeフィールドは必須です。',
    'required_unless'      => ':otherが:valuesにない限り、:attributeフィールドは必須です。',
    'required_with'        => ':valuesが存在する場合、:attributeフィールドは必須です。',
    'required_with_all'    => ':valuesが存在する場合、:attributeフィールドは必須です。',
    'required_without'     => ':valuesが存在しない場合、:attributeフィールドは必須です。',
    'required_without_all' => ':valuesがすべて存在しない場合、:attributeフィールドは必須です。',
    'same'                 => ':attributeと:otherは一致する必要があります。',

    'size'                 => [
        'numeric' => ':attributeは:sizeである必要があります。',
        'file'    => ':attributeは:sizeキロバイトである必要があります。',
        'string'  => ':attributeは:size文字である必要があります。',
        'array'   => ':attributeは:size個のアイテムを含む必要があります。',
    ],

    'string'               => ':attributeは文字列である必要があります。',
    'timezone'             => ':attributeは有効なタイムゾーンである必要があります。',
    'unique'               => ':attributeはすでに存在します。',
    'uploaded'             => ':attributeのアップロードに失敗しました。',
    'url'                  => ':attributeは無効なURLフォーマットです。',


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
            'rule-name' => 'カスタムメッセージ',
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
