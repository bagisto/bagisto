<?php

return [
   /**
     * Giftcard.
    */
    [
        'key'        => 'giftcard',
        'name'       => 'Giftcard',
        'route'      => 'admin.giftcard.index',
        'sort'       => 10,
        'icon'       => 'icon-sales',
    ], [
        'key'        => 'giftcard.giftcards',
        'name'       => 'Giftcards',
        'route'      => 'admin.giftcard.index',
        'sort'       => 1,
        'parent'     => 'giftcard',
        'icon'       => '',
    ],[
        'key'        => 'giftcard.payments',
        'name'       => 'Giftcard Payment',
        'route'      => 'admin.giftcard.payment',
        'sort'       => 2,
        'parent'     => 'giftcard',
        'icon'       => '',
    ],[
        'key'        => 'giftcard.balances',
        'name'       => 'Giftcard Balances',
        'route'      => 'admin.giftcard.balance',
        'sort'       => 3,
        'parent'     => 'giftcard',
        'icon'       => '',
    ],
];