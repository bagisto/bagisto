<?php

declare(strict_types=1);

namespace Frosko\DSK\Services\Actions;

class CreateRefund extends AbstractDskAction
{
    public function execute(string $orderId, int $amount): array
    {
        $response = $this->http->post('refund.do', [
            ...$this->config->getCredentials(),
            'orderId' => $orderId,
            'amount'  => $amount,
        ]);

        return $this->handleResponse($response);
    }
}
