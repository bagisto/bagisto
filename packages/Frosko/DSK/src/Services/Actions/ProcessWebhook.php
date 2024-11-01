<?php

declare(strict_types=1);

namespace Frosko\DSK\Services\Actions;

class ProcessWebhook extends AbstractDskAction
{
    public function execute(string $orderId, string $orderNumber): array
    {
        $response = $this->http->post('getOrderStatus.do', [
            ...$this->config->getCredentials(),
            'orderId'     => $orderId,
            'orderNumber' => $orderNumber,
        ]);

        return $this->handleResponse($response);
    }
}
