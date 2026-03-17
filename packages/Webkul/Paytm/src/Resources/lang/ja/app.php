<?php

return [
	'checkout' => [
		'onepage' => [
			'payment' => [
				'paytm' => [
					'redirect' => 'リダイレクト中...',
                    'redirecting' => '数秒後にPaytmへリダイレクトされます。',
                    'do-not-refresh' => 'このページを更新または閉じないでください。',
                    'cart-empty' => 'カートは空です。',
                    'general-error' => '問題が発生しました。再試行してください。',
                    'missing-cart-id' => 'カートIDがありません。',
                    'cart-not-found' => 'カートが見つかりません。',
                    'checksum-failed' => 'チェックサム検証に失敗しました。',
                    'payment-failed' => 'Paytm支払いが失敗またはキャンセルされました。',
                    'payment-success' => '注文が正常に完了しました',
                ],
			],
		],
	],
];
