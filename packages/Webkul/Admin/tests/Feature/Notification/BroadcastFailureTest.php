<?php

use Illuminate\Broadcasting\BroadcastException;
use Illuminate\Contracts\Broadcasting\Broadcaster;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Log;
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
        'queue.default' => 'sync',
    ]);
});

// ============================================================================
// Unreachable Broadcaster
// ============================================================================

it('should still record the notification when the broadcaster is unreachable', function () {
    $order = $this->createOrder();

    Event::dispatch('checkout.order.save.after', $order);

    expect(Notification::query()->where('type', 'order.placed')->get()->contains(fn ($notification) => $notification->data['order_id'] === $order->id))->toBeTrue();
});

it('should not let an unreachable broadcaster fail what raised the notification', function () {
    notifier()->notify('order.placed', $this->createOrder());
})->throwsNoExceptions();

it('should log the reason a notification was not broadcast', function () {
    Log::shouldReceive('error')
        ->once()
        ->withArgs(fn ($message) => str_contains($message, 'The broadcaster is unreachable.'));

    notifier()->notify('order.placed', $this->createOrder());
});
