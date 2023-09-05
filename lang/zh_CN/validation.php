<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | 这个 following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted'             => '这个 :attribute 必须接受.',
    'active_url'           => '这个 :attribute 不是有效的 URL.',
    'after'                => '这个 :attribute 必须是 :date 之后的日期.',
    'after_or_equal'       => '这个 :attribute 必须是晚于或等于 :date 的日期.',
    'alpha'                => '这个 :attribute 只能包含字母.',
    'alpha_dash'           => '这个 :attribute 只能包含字母、数字、破折号和下划线.',
    'alpha_num'            => '这个 :attribute 只能包含字母和数字.',
    'array'                => '这个 :attribute 必须是一个数组.',
    'before'               => '这个 :attribute 必须是 :date 之前的日期.',
    'before_or_equal'      => '这个 :attribute 必须是早于或等于 :date 的日期.',

    'between'              => [
        'numeric' => '这个 :attribute 必须介于 :min 和 :max.',
        'file'    => '这个 :attribute 必须介于 :min 和 :max 千字节.',
        'string'  => '这个 :attribute 必须介于 :min 和 :max 字符.',
        'array'   => '这个 :attribute 必须介于 :min 和 :max 项目.',
    ],

    'boolean'              => '这个 :attribute 字段必须为真或假.',
    'confirmed'            => '这个 :attribute 确认不匹配.',
    'date'                 => '这个 :attribute 不是有效日期.',
    'date_format'          => '这个 :attribute 与 :格式格式不匹配.',
    'different'            => '这个 :attribute 和 :other 必须不同.',
    'digits'               => '这个 :attribute 必须是 :digits 数字.',
    'digits_between'       => '这个 :attribute 必须介于 :min 和 :max 数字.',
    'dimensions'           => '这个 :attribute 图片尺寸无效.',
    'distinct'             => '这个 :attribute 字段具有重复值.',
    'email'                => '这个 :attribute 必须是一个有效的E-mail地址.',
    'exists'               => '这个 已选择的 :attribute 是无效的.',
    'file'                 => '这个 :attribute 必须是文件.',
    'filled'               => '这个 :attribute 字段必须有值.',

    'gt'                   => [
        'numeric' => '这个 :attribute 必须大于 :value.',
        'file'    => '这个 :attribute 必须大于 :value 千字节.',
        'string'  => '这个 :attribute 必须大于 :value 字符.',
        'array'   => '这个 :attribute 必须有超过 :value 项目.',
    ],

    'gte'                  => [
        'numeric' => '这个 :attribute 必须大于或等于 :value.',
        'file'    => '这个 :attribute 必须大于或等于 :value 千字节.',
        'string'  => '这个 :attribute 必须大于或等于 :value 字符.',
        'array'   => '这个 :attribute 必须有 :value 项目或者更多.',
    ],

    'image'                => '这个 :attribute 必须是一张图片.',
    'in'                   => '这个 已选择的 :attribute 是无效的.',
    'in_array'             => '这个 :attribute 字段不存在于 :other.',
    'integer'              => '这个 :attribute 必须是整数.',
    'ip'                   => '这个 :attribute 必须是有效的IP地址.',
    'ipv4'                 => '这个 :attribute 必须是有效的IPv4地址.',
    'ipv6'                 => '这个 :attribute 必须是有效的IPv6地址.',
    'json'                 => '这个 :attribute 必须是有效的JSON字符串.',

    'lt'                   => [
        'numeric' => '这个 :attribute 必须小于 :value.',
        'file'    => '这个 :attribute 必须小于 :value 千字节.',
        'string'  => '这个 :attribute 必须小于 :value 字符.',
        'array'   => '这个 :attribute 必须有少于 :value 项目.',
    ],

    'lte'                  => [
        'numeric' => '这个 :attribute 必须小于或等于 :value.',
        'file'    => '这个 :attribute 必须小于或等于 :value 千字节.',
        'string'  => '这个 :attribute 必须小于或等于 :value 字符.',
        'array'   => '这个 :attribute 不得超过 :value 项目.',
    ],

    'max'                  => [
        'numeric' => '这个 :attribute 不得大于 :max.',
        'file'    => '这个 :attribute 不得大于 :max 千字节.',
        'string'  => '这个 :attribute 不得大于 :max 字符.',
        'array'   => '这个 :attribute 可能不超过 :max 项目.',
    ],
    'mimes'                => '这个 :attribute 必须是文件类型: :values.',
    'mimetypes'            => '这个 :attribute 必须是文件类型: :values.',

    'min'                  => [
        'numeric' => '这个 :attribute 必须至少 :min.',
        'file'    => '这个 :attribute 必须至少 :min 千字节.',
        'string'  => '这个 :attribute 必须至少 :min 字符.',
        'array'   => '这个 :attribute 必须有至少 :min 项目.',
    ],

    'not_in'               => '这个 已选择的 :attribute 是无效的.',
    'not_regex'            => '这个 :attribute 格式是无效的.',
    'numeric'              => '这个 :attribute 必须是数字.',
    'present'              => '这个 :attribute 字段必须存在.',
    'regex'                => '这个 :attribute 格式是无效的.',
    'required'             => '这个 :attribute 字段是必需的.',
    'required_if'          => '这个 :attribute 字段是必需的当 :other 是 :value.',
    'required_unless'      => '这个 :attribute 字段是必需的除非 :other 是在 :values 中.',
    'required_with'        => '这个 :attribute 字段是必需的当 :values 是存在的.',
    'required_with_all'    => '这个 :attribute 字段是必需的当 :values 是存在的.',
    'required_without'     => '这个 :attribute 字段是必需的当 :values 是不存在的.',
    'required_without_all' => '这个 :attribute 字段是必需的当 没有 :values 是存在的.',
    'same'                 => '这个 :attribute 和 :other 必须匹配.',

    'size'                 => [
        'numeric' => '这个 :attribute 必须是 :size.',
        'file'    => '这个 :attribute 必须是 :size 千字节.',
        'string'  => '这个 :attribute 必须是 :size 字符.',
        'array'   => '这个 :attribute 必须包含 :size 项目.',
    ],

    'string'               => '这个 :attribute 必须是 一个字符串.',
    'timezone'             => '这个 :attribute 必须是 有效区域.',
    'unique'               => '这个 :attribute 已有人带走了.',
    'uploaded'             => '这个 :attribute 上传失败.',
    'url'                  => '这个 :attribute 格式是无效的.',

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
            'rule-name' => '自定义消息',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | 这个 following language lines are used to swap attribute place-holders
    | with something more reader friendly such as E-Mail Address instead
    | of "email". This simply helps us make messages a little cleaner.
    |
    */

    'attributes' => [],

];
