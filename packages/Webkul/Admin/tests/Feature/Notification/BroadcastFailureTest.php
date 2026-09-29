<?php

use Illuminate\Broadcasting\BroadcastException;
use Illuminate\Contracts\Broadcasting\Broadcaster;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Log;
use Webkul\Notification\Listeners\Order as OrderListener;
use Webkul\Notification\Models\Notification;

beforeEach(function () {
    Broadcast::extend('failing', fn () => new class implements Broadcaster
    {
        public function auth($request) {}

        public function validAuthenticationResponse($request, $result) {}

        public function broadcast(array $channels, $event, array $payload = [])
        {
            throw new BroadcastException('The broadcaster is unreachable.');
        }
    });

    config([
        'broadcasting.default' => 'failing',
        'broadcasting.connections.failing' => ['driver' => 'failing'],
    ]);
});

// ============================================================================
// Unreachable Broadcaster
// ============================================================================

it('should still record the notification when the broadcaster is unreachable', function () {
    $order = $this->createOrder();

    app(OrderListener::class)->createOrder($order);

    expect(Notification::where('order_id', $order->id)->exists())->toBeTrue();
});

it('should not let an unreachable broadcaster fail order creation', function () {
    $order = $this->createOrder();

    app(OrderListener::class)->createOrder($order);
})->throwsNoExceptions();

it('should not let an unreachable broadcaster fail an order status update', function () {
    $order = $this->createOrder();

    app(OrderListener::class)->updateOrder($order);
})->throwsNoExceptions();

it('should log the reason a notification was not broadcast', function () {
    Log::shouldReceive('error')
        ->once()
        ->withArgs(fn ($message) => str_contains($message, 'The broadcaster is unreachable.'));

    app(OrderListener::class)->updateOrder($this->createOrder());
});
