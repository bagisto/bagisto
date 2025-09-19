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

describe('Two Factor Authentication Setup', function () {

    test('authenticated admin can access 2FA setup endpoint', function () {
        // Act
        $response = $this->actingAs($this->admin, 'admin')
            ->getJson(route('admin.twofactor.setup'));

        // Assert
        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'qrCodeSvg',
                'qrCodeUrl',
            ])
            ->assertJson(['success' => true]);
    });

    test('unauthenticated user cannot access 2FA setup', function () {
        // Act
        $response = $this->getJson(route('admin.twofactor.setup'));

        // Assert
        $response->assertStatus(401);
    });

    test('setup generates secret key for new admin', function () {
        // Arrange
        expect($this->admin->two_factor_secret)->toBeNull();

        // Act
        $this->actingAs($this->admin, 'admin')
            ->getJson(route('admin.twofactor.setup'));

        // Assert
        $this->admin->refresh();
        expect($this->admin->two_factor_secret)->not()->toBeNull();
    });

    test('setup returns existing secret for admin with 2FA already configured', function () {
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

    test('setup handles exceptions gracefully for AJAX requests', function () {
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
});
