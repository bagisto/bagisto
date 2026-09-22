<?php

return [
    'description' => 'Kredi/banka kartınzla Stripe üzerinden güvenli ödeme yapın.',
    'title' => 'Stripe',

    'line-items' => [
        'shipping' => 'Kargo',
        'tax' => 'Vergi',
    ],

    'response' => [
        'cart-changed' => 'Ödeme başlatıldıktan sonra sepetiniz değişti, bu nedenle sipariş oluşturulmadı. Lütfen ödemeniz hakkında bizimle iletişime geçin.',
        'cart-not-found' => 'Sepet bulunamadı veya geçersiz.',
        'cart-processed' => 'Bu sepet zaten işlenmiş.',
        'invalid-session' => 'Ödeme oturumu geçersiz.',
        'payment-cancelled' => 'Ödeme iptal edildi.',
        'payment-failed' => 'Ödeme başarısız.',
        'payment-success' => 'Ödeme başarıyla tamamlandı.',
        'provide-credentials' => 'Lütfen geçerli Stripe kimlik bilgileri sağlayın.',
        'session-invalid' => 'Ödeme oturumu süresi dolmuş veya geçersiz.',
        'session-not-found' => 'Ödeme oturumu bulunamadı.',
        'verification-failed' => 'Ödeme doğrulaması başarısız.',
    ],

    'webhook' => [
        'dispute-closed' => 'Stripe itirazı :id şu sonuçla kapandı: :status.',
        'dispute-opened' => 'Müşteri bu ödemenin :amount tutarına Stripe\'da itiraz etti (:id), neden: :reason.',
        'refund-recorded' => 'Stripe\'da yapılan :amount tutarındaki iade kaydedildi; Stripe\'da toplam :total iade edildi.',
    ],
];
