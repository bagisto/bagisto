<?php

return [
    'description' => 'Bayar dengan aman menggunakan kartu kredit/debit Anda melalui Stripe.',
    'title' => 'Stripe',

    'line-items' => [
        'shipping' => 'Pengiriman',
        'tax' => 'Pajak',
    ],

    'response' => [
        'cart-changed' => 'Keranjang Anda berubah setelah pembayaran dimulai, sehingga tidak ada pesanan yang dibuat. Silakan hubungi kami terkait pembayaran Anda.',
        'cart-not-found' => 'Keranjang tidak ditemukan atau tidak valid.',
        'cart-processed' => 'Keranjang ini telah diproses.',
        'invalid-session' => 'Sesi pembayaran tidak valid.',
        'payment-cancelled' => 'Pembayaran dibatalkan.',
        'payment-failed' => 'Pembayaran gagal.',
        'payment-success' => 'Pembayaran berhasil diselesaikan.',
        'provide-credentials' => 'Harap berikan kredensial Stripe yang valid.',
        'session-invalid' => 'Sesi pembayaran kedaluwarsa atau tidak valid.',
        'session-not-found' => 'Sesi pembayaran tidak ditemukan.',
        'verification-failed' => 'Verifikasi pembayaran gagal.',
    ],

    'webhook' => [
        'dispute-closed' => 'Sengketa Stripe :id ditutup dengan hasil: :status.',
        'dispute-opened' => 'Pelanggan menyengketakan :amount dari pembayaran ini di Stripe (:id), dengan alasan: :reason.',
        'refund-recorded' => 'Pengembalian dana :amount yang dilakukan di Stripe telah dicatat; total :total telah dikembalikan di Stripe.',
    ],
];
