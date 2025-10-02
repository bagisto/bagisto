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

    'accepted'        => 'A(z) :attribute mezőt el kell fogadni.',
    'accepted_if'     => 'A(z) :attribute mezőt el kell fogadni, ha a(z) :other értéke :value.',
    'active_url'      => 'A(z) :attribute mezőnek érvényes URL-nek kell lennie.',
    'after'           => 'A(z) :attribute mezőnek :date utáni dátumnak kell lennie.',
    'after_or_equal'  => 'A(z) :attribute mezőnek :date dátummal egyező vagy későbbi dátumnak kell lennie.',
    'alpha'           => 'A(z) :attribute mező csak betűket tartalmazhat.',
    'alpha_dash'      => 'A(z) :attribute mező csak betűket, számokat, kötőjeleket és aláhúzásjeleket tartalmazhat.',
    'alpha_num'       => 'A(z) :attribute mező csak betűket és számokat tartalmazhat.',
    'array'           => 'A(z) :attribute mező tömbnek kell lennie.',
    'ascii'           => 'A(z) :attribute mező csak egyszavas ASCII karaktereket és szimbólumokat tartalmazhat.',
    'before'          => 'A(z) :attribute mezőnek :date előtti dátumnak kell lennie.',
    'before_or_equal' => 'A(z) :attribute mezőnek :date-nél korábbi vagy azzal megegyező dátumnak kell lennie.',

    'between' => [
        'array'   => 'A(z) :attribute mezőnek :min és :max elem között kell lennie.',
        'file'    => 'A(z) :attribute mezőnek :min és :max kilobájt között kell lennie.',
        'numeric' => 'A(z) :attribute mező értéke :min és :max között kell legyen.',
        'string'  => 'A(z) :attribute mezőnek :min és :max karakter között kell lennie.',
    ],

    'boolean'   => 'A(z) :attribute mező értéke igaz vagy hamis lehet.',
    'can'       => 'A(z) :attribute mező jogosulatlan értéket tartalmaz.',
    'confirmed' => 'A(z) :attribute megerősítése nem egyezik.',
    'current_password' => 'A megadott jelszó helytelen.',
    'date'      => 'A(z) :attribute mezőnek érvényes dátumnak kell lennie.',
    'date_equals' => 'A(z) :attribute mezőnek :date dátummal megegyezőnek kell lennie.',
    'date_format' => 'A(z) :attribute mező nem egyezik a következő formátummal: :format.',
    'decimal' => 'A(z) :attribute mezőnek :decimal tizedesjegyet kell tartalmaznia.',
    'declined' => 'A(z) :attribute mezőt el kell utasítani.',
    'declined_if' => 'A(z) :attribute mezőt el kell utasítani, ha a(z) :other értéke :value.',
    'different' => 'A(z) :attribute és a(z) :other mező különböző kell legyen.',
    'digits' => 'A(z) :attribute mezőnek :digits számjegyből kell állnia.',
    'digits_between' => 'A(z) :attribute mezőnek :min és :max számjegy között kell lennie.',
    'dimensions' => 'A(z) :attribute mező érvénytelen képméretekkel rendelkezik.',
    'distinct' => 'A(z) :attribute mező ismétlődő értéket tartalmaz.',
    'doesnt_end_with' => 'A(z) :attribute mező nem végződhet a következők egyikére: :values.',
    'doesnt_start_with' => 'A(z) :attribute mező nem kezdődhet a következők egyikével: :values.',
    'email' => 'A(z) :attribute mezőnek érvényes e-mail címnek kell lennie.',
    'ends_with' => 'A(z) :attribute mezőnek a következők egyikével kell végződnie: :values.',
    'enum' => 'A kiválasztott :attribute érvénytelen.',
    'exists' => 'A kiválasztott :attribute érvénytelen.',
    'extensions' => 'A(z) :attribute mezőnek a következő kiterjesztések egyikével kell rendelkeznie: :values.',
    'file' => 'A(z) :attribute mező fájlnak kell legyen.',
    'filled' => 'A(z) :attribute mezőt ki kell tölteni.',

    'gt' => [
        'array'   => 'A(z) :attribute mezőnek több mint :value elemet kell tartalmaznia.',
        'file'    => 'A(z) :attribute mezőnek nagyobbnak kell lennie, mint :value kilobájt.',
        'numeric' => 'A(z) :attribute mező értéke nagyobb kell legyen, mint :value.',
        'string'  => 'A(z) :attribute mezőnek több mint :value karaktert kell tartalmaznia.',
    ],

    'gte' => [
        'array'   => 'A(z) :attribute mezőnek legalább :value elemet kell tartalmaznia.',
        'file'    => 'A(z) :attribute mező értéke legalább :value kilobájt kell legyen.',
        'numeric' => 'A(z) :attribute mező értéke legalább :value kell legyen.',
        'string'  => 'A(z) :attribute mezőnek legalább :value karakter hosszúnak kell lennie.',
    ],

    'hex_color' => 'A(z) :attribute mezőnek érvényes hexadecimális színkódnak kell lennie.',
    'image'     => 'A(z) :attribute mezőnek képnek kell lennie.',
    'in'        => 'A kiválasztott :attribute érvénytelen.',
    'in_array'  => 'A(z) :attribute mező nem található meg a(z) :other mezőben.',
    'integer'   => 'A(z) :attribute mezőnek egész számnak kell lennie.',
    'ip'        => 'A(z) :attribute mezőnek érvényes IP-címnek kell lennie.',
    'ipv4'      => 'A(z) :attribute mezőnek érvényes IPv4-címnek kell lennie.',
    'ipv6'      => 'A(z) :attribute mezőnek érvényes IPv6-címnek kell lennie.',
    'json'      => 'A(z) :attribute mezőnek érvényes JSON szövegnek kell lennie.',
    'lowercase' => 'A(z) :attribute mezőnek kisbetűsnek kell lennie.',

    'lt' => [
        'array'   => 'A(z) :attribute mező nem tartalmazhat több mint :value elemet.',
        'file'    => 'A(z) :attribute mező kevesebb kell legyen, mint :value kilobájt.',
        'numeric' => 'A(z) :attribute mező kevesebb kell legyen, mint :value.',
        'string'  => 'A(z) :attribute mező kevesebb kell legyen, mint :value karakter.',
    ],

    'lte' => [
        'array'   => 'A(z) :attribute mező nem tartalmazhat több, mint :value elemet.',
        'file'    => 'A(z) :attribute mező nem lehet nagyobb, mint :value kilobájt.',
        'numeric' => 'A(z) :attribute mező nem lehet nagyobb, mint :value.',
        'string'  => 'A(z) :attribute mező nem lehet hosszabb, mint :value karakter.',
    ],

    'mac_address' => 'A(z) :attribute mezőnek érvényes MAC-címnek kell lennie.',

    'max' => [
        'array'   => 'A(z) :attribute mező nem tartalmazhat több mint :max elemet.',
        'file'    => 'A(z) :attribute mező nem lehet nagyobb, mint :max kilobájt.',
        'numeric' => 'A(z) :attribute mező értéke nem lehet nagyobb, mint :max.',
        'string'  => 'A(z) :attribute mező nem lehet hosszabb, mint :max karakter.',
    ],

    'max_digits' => 'A(z) :attribute mező nem tartalmazhat több mint :max számjegyet.',
    'mimes'      => 'A(z) :attribute mező fájltípusának a következők egyikének kell lennie: :values.',
    'mimetypes'  => 'A(z) :attribute mező fájltípusának a következők egyikének kell lennie: :values.',

    'min' => [
        'array'   => 'A(z) :attribute mező legalább :min elemet kell tartalmazzon.',
        'file'    => 'A(z) :attribute mezőnek legalább :min kilobájtnak kell lennie.',
        'numeric' => 'A(z) :attribute mező értéke nem lehet kisebb, mint :min.',
        'string'  => 'A(z) :attribute mezőnek legalább :min karakter hosszúnak kell lennie.',
    ],

    'min_digits'       => 'A(z) :attribute mező legalább :min számjegyet kell tartalmazzon.',
    'missing'          => 'A(z) :attribute mező hiányzó kell legyen.',
    'missing_if'       => 'A(z) :attribute mező hiányzó kell legyen, ha a(z) :other értéke :value.',
    'missing_unless'   => 'A(z) :attribute mező hiányzó kell legyen, hacsak :other nem :value.',
    'missing_with'     => 'A(z) :attribute mező hiányzó kell legyen, ha :values jelen van.',
    'missing_with_all' => 'A(z) :attribute mező hiányzó kell legyen, ha a következők jelen vannak: :values.',
    'multiple_of'      => 'A(z) :attribute mezőnek :value többszörösének kell lennie.',
    'not_in'           => 'A kiválasztott :attribute érvénytelen.',
    'not_regex'        => 'A(z) :attribute mező formátuma érvénytelen.',
    'numeric'          => 'A(z) :attribute mezőnek számnak kell lennie.',

    'password' => [
        'letters'       => 'A(z) :attribute mezőnek tartalmaznia kell legalább egy betűt.',
        'mixed'         => 'A(z) :attribute mezőnek tartalmaznia kell legalább egy kis- és egy nagybetűt.',
        'numbers'       => 'A(z) :attribute mezőnek tartalmaznia kell legalább egy számot.',
        'symbols'       => 'A(z) :attribute mezőnek tartalmaznia kell legalább egy szimbólumot.',
        'uncompromised' => 'A megadott :attribute szerepel egy adatvédelmi incidensben. Kérlek válassz másikat.',
    ],

    'present'              => 'A(z) :attribute mezőnek jelen kell lennie.',
    'present_if'           => 'A(z) :attribute mezőnek jelen kell lennie, ha a(z) :other értéke :value.',
    'present_unless'       => 'A(z) :attribute mezőnek jelen kell lennie, kivéve, ha a(z) :other értéke :value.',
    'present_with'         => 'A(z) :attribute mezőnek jelen kell lennie, ha a(z) :values jelen van.',
    'present_with_all'     => 'A(z) :attribute mezőnek jelen kell lennie, ha a következők jelen vannak: :values.',
    'prohibited'           => 'A(z) :attribute mező megadása tiltott.',
    'prohibited_if'        => 'A(z) :attribute mező megadása tiltott, ha a(z) :other értéke :value.',
    'prohibited_unless'    => 'A(z) :attribute mező megadása tiltott, kivéve ha :other értéke a következők egyike: :values.',
    'prohibits'            => 'A(z) :attribute mező megakadályozza, hogy a(z) :other jelen legyen.',
    'regex'                => 'A(z) :attribute mező formátuma érvénytelen.',
    'required'             => 'A(z) :attribute mező kitöltése kötelező.',
    'required_array_keys'  => 'A(z) :attribute mezőnek tartalmaznia kell a következő elemeket: :values.',
    'required_if'          => 'A(z) :attribute mező kitöltése kötelező, ha a(z) :other értéke :value.',
    'required_if_accepted' => 'A(z) :attribute mező kitöltése kötelező, ha a(z) :other el van fogadva.',
    'required_unless'      => 'A(z) :attribute mező kitöltése kötelező, kivéve, ha a(z) :other értéke szerepel a következők között: :values.',
    'required_with'        => 'A(z) :attribute mező kitöltése kötelező, ha :values jelen van.',
    'required_with_all'    => 'A(z) :attribute mező kitöltése kötelező, ha a következők jelen vannak: :values.',
    'required_without'     => 'A(z) :attribute mező kitöltése kötelező, ha :values nincs jelen.',
    'required_without_all' => 'A(z) :attribute mező kitöltése kötelező, ha egyik sem található meg a következők közül: :values.',
    'same'                 => 'A(z) :attribute és :other mezőnek egyeznie kell.',

    'size' => [
        'array'   => 'A(z) :attribute mezőnek :size elemet kell tartalmaznia.',
        'file'    => 'A(z) :attribute mezőnek :size kilobájtnak kell lennie.',
        'numeric' => 'A(z) :attribute mező értéke :size kell legyen.',
        'string'  => 'A(z) :attribute mezőnek :size karakter hosszúnak kell lennie.',
    ],

    'starts_with' => 'A(z) :attribute mezőnek a következők egyikével kell kezdődnie: :values.',
    'string'      => 'A(z) :attribute mezőnek karakterláncnak kell lennie.',
    'timezone'    => 'A(z) :attribute mezőnek érvényes időzónának kell lennie.',
    'unique'      => 'A(z) :attribute már foglalt.',
    'uploaded'    => 'A(z) :attribute feltöltése sikertelen volt.',
    'uppercase'   => 'A(z) :attribute mezőnek nagybetűsnek kell lennie.',
    'url'         => 'A(z) :attribute mezőnek érvényes URL-nek kell lennie.',
    'ulid'        => 'A(z) :attribute mezőnek érvényes ULID-nek kell lennie.',
    'uuid'        => 'A(z) :attribute mezőnek érvényes UUID-nek kell lennie.',

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
            'rule-name' => 'egyedi hibaüzenet',
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
