<?php

return [
    'description' => 'PayU üzerinden kredi kartı, banka kartı, internet bankacılığı, UPI ve cüzdanlar kullanarak güvenli ödeme yapın',
    'title'       => 'PayU Money',

    'redirect' => [
        'click-if-not-redirected' => 'Devam etmek için buraya tıklayın',
        'please-wait'             => 'Sizi ödeme ağ geçidine yönlendirirken lütfen bekleyin...',
        'redirect-message'        => 'Otomatik olarak yönlendirilmediyseniz, aşağıdaki düğmeye tıklayın.',
        'redirecting'             => 'PayU\'ya yönlendiriliyor...',
        'redirecting-to-payment'  => 'PayU Ödeme\'ye yönlendiriliyor',
        'secure-payment'          => 'Güvenli ödeme ağ geçidi',
    ],

    'response' => [
        'cart-not-found'            => 'Sepet bulunamadı. Lütfen tekrar deneyin.',
        'hash-mismatch'             => 'Ödeme doğrulaması başarısız oldu. Hash uyuşmazlığı.',
        'invalid-transaction'       => 'Geçersiz işlem. Lütfen tekrar deneyin.',
        'order-creation-failed'     => 'Sipariş oluşturulamadı. Lütfen destekle iletişime geçin.',
        'payment-already-processed' => 'Ödeme zaten işlendi.',
        'payment-cancelled'         => 'Ödeme iptal edildi. Tekrar deneyebilirsiniz.',
        'payment-failed'            => 'Ödeme başarısız oldu. Lütfen tekrar deneyin.',
        'payment-success'           => 'Ödeme başarıyla tamamlandı!',
        'provide-credentials'       => 'Lütfen yönetici panelinde PayU Satıcı Anahtarını ve Salt\'ı yapılandırın.',
    ],
];
