<?php

use Webkul\Customer\Models\Customer;

use function Pest\Laravel\post;

beforeEach(function () {
    config([
        'broadcasting.default' => 'pusher',
        'broadcasting.connections.pusher.key' => 'test-key',
        'broadcasting.connections.pusher.secret' => 'test-secret',
        'broadcasting.connections.pusher.app_id' => 'test-id',
    ]);

    require dirname(__DIR__, 3).'/src/Routes/channels.php';

    $this->payload = [
        'channel_name' => 'private-admin.notifications',
        'socket_id' => '1234.5678',
    ];
});

// ============================================================================
// Notification Channel
// ============================================================================

it('should refuse a guest listening to the admin notification channel', function () {
    post('broadcasting/auth', $this->payload)->assertForbidden();
});

it('should refuse a signed in customer listening to the admin notification channel', function () {
    auth()->guard('customer')->login(Customer::factory()->create());

    post('broadcasting/auth', $this->payload)->assertForbidden();
});

it('should let a signed in admin listen to the admin notification channel', function () {
    $this->loginAsAdmin();

    post('broadcasting/auth', $this->payload)
        ->assertOk()
        ->assertJsonStructure(['auth']);
});
