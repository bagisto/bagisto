<?php

return [
    'description' => 'Bayar dengan aman menggunakan Kartu Kredit, Kartu Debit, Internet Banking, UPI & Dompet melalui PayU',
    'title'       => 'PayU Money',

    'redirect' => [
        'click-if-not-redirected' => 'Klik di sini untuk melanjutkan',
        'please-wait'             => 'Harap tunggu sementara kami mengalihkan Anda ke gateway pembayaran...',
        'redirect-message'        => 'Jika Anda tidak dialihkan secara otomatis, klik tombol di bawah ini.',
        'redirecting'             => 'Mengalihkan ke PayU...',
        'redirecting-to-payment'  => 'Mengalihkan ke Pembayaran PayU',
        'secure-payment'          => 'Gateway Pembayaran Aman',
    ],

    'response' => [
        'cart-not-found'        => 'Keranjang tidak ditemukan. Silakan coba lagi.',
        'hash-mismatch'         => 'Verifikasi pembayaran gagal. Hash tidak cocok.',
        'invalid-transaction'   => 'Transaksi tidak valid. Silakan coba lagi.',
        'order-creation-failed' => 'Gagal membuat pesanan. Silakan hubungi dukungan.',
        'payment-cancelled'     => 'Pembayaran dibatalkan. Anda dapat mencoba lagi.',
        'payment-failed'        => 'Pembayaran gagal. Silakan coba lagi.',
        'payment-success'       => 'Pembayaran berhasil diselesaikan!',
        'provide-credentials'   => 'Harap konfigurasikan Kunci Merchant dan Salt PayU di panel admin.',
    ],
];
