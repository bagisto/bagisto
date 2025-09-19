<?php

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use PragmaRX\Google2FA\Google2FA;
use Webkul\Admin\Mail\Admin\BackupCodesNotification;
use Webkul\User\Models\Admin;

beforeEach(function () {
    $this->admin = Admin::factory()->create([
        'email'    => 'admin@test.com',
        'password' => Hash::make('password123'),
        'status'   => 1,
    ]);

    $this->google2fa = new Google2FA;
    Mail::fake();
});

it('allows an authenticated admin to access the 2FA setup endpoint', function () {
    // Act
    $response = $this->actingAs($this->admin, 'admin')
        ->getJson(route('admin.twofactor.setup'));

    // Assert
    $response->assertStatus(200)
        ->assertJsonStructure(['success', 'qrCodeSvg', 'qrCodeUrl'])
        ->assertJson(['success' => true]);
});

it('denies unauthenticated users from accessing 2FA setup', function () {
    // Act
    $response = $this->getJson(route('admin.twofactor.setup'));

    // Assert
    $response->assertStatus(401);
});

it('generates a secret key for a new admin on setup', function () {
    // Arrange
    expect($this->admin->two_factor_secret)->toBeNull();

    // Act
    $this->actingAs($this->admin, 'admin')
        ->getJson(route('admin.twofactor.setup'));

    // Assert
    $this->admin->refresh();
    expect($this->admin->two_factor_secret)->not()->toBeNull();
});

it('returns the existing secret if the admin has already configured 2FA', function () {
    // Arrange
    $originalSecret = $this->google2fa->generateSecretKey();
    $this->admin->update([
        'two_factor_secret' => encrypt($originalSecret),
    ]);

    // Act
    $response = $this->actingAs($this->admin, 'admin')
        ->getJson(route('admin.twofactor.setup'));

    // Assert
    $response->assertStatus(200)
        ->assertJson(['success' => true]);

    $this->admin->refresh();
    expect(decrypt($this->admin->two_factor_secret))->toBe($originalSecret);
});

it('handles exceptions gracefully for AJAX setup requests', function () {
    // Arrange
    $this->mock(\Webkul\User\Repositories\AdminRepository::class, function ($mock) {
        $mock->shouldReceive('getOrGenerateTwoFactorSecret')
            ->andThrow(new \Exception('Test exception'));
    });

    // Act
    $response = $this->actingAs($this->admin, 'admin')
        ->getJson(route('admin.twofactor.setup'));

    // Assert
    $response->assertStatus(500)
        ->assertJson([
            'success' => false,
            'error'   => 'Test exception',
        ]);
});
