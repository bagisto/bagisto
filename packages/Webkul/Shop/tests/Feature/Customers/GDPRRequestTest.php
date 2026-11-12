<?php

use Illuminate\Support\Facades\Exceptions;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\Mailer\Exception\TransportException;
use Webkul\GDPR\Models\GDPRDataRequest;
use Webkul\Shop\Mail\Customer\GDPR\NewRequestNotification;

use function Pest\Laravel\postJson;

beforeEach(function () {
    $this->setConfig('general.gdpr.settings.enabled', true);
});

// ============================================================================
// Creating A Request
// ============================================================================

it('should create the request and send the customer a receipt', function () {
    Mail::fake();

    $customer = $this->loginAsCustomer();

    postJson(route('shop.customers.account.gdpr.store'), [
        'type' => 'delete',
        'message' => 'Please delete my data.',
    ], ['X-Requested-With' => 'XMLHttpRequest'])
        ->assertOk()
        ->assertJsonPath('message', trans('shop::app.customers.account.gdpr.create-success'));

    expect(GDPRDataRequest::query()->where('customer_id', $customer->id)->where('type', 'delete')->exists())->toBeTrue();

    Mail::assertQueued(NewRequestNotification::class, fn (NewRequestNotification $mail) => $mail->gdprRequest->email === $customer->email);
});

it('should report a receipt that cannot be sent without warning the customer', function () {
    Exceptions::fake();

    Mail::shouldReceive('queue')->andThrow(new TransportException('Connection refused.'));

    $customer = $this->loginAsCustomer();

    postJson(route('shop.customers.account.gdpr.store'), [
        'type' => 'update',
        'message' => 'Please correct my address.',
    ], ['X-Requested-With' => 'XMLHttpRequest'])
        ->assertOk()
        ->assertSessionHas('success', trans('shop::app.customers.account.gdpr.create-success'))
        ->assertSessionMissing('warning');

    expect(GDPRDataRequest::query()->where('customer_id', $customer->id)->where('type', 'update')->exists())->toBeTrue();

    Exceptions::assertReported(TransportException::class);
});
