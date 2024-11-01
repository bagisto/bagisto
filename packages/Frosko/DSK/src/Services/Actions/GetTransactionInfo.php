<?php

declare(strict_types=1);

namespace Frosko\DSK\Services\Actions;

class GetTransactionInfo extends AbstractDskAction
{
    public function execute(string $orderId): array
    {
        $response = $this->http->post('getOrderStatusExtended.do', [
            ...$this->config->getCredentials(),
            'orderId' => $orderId,
        ]);

        return $this->handleResponse($response);
    }
}
