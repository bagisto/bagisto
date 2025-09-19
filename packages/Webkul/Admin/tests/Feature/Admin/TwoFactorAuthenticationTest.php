<?php

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use PragmaRX\Google2FA\Google2FA;
use Webkul\Admin\Mail\Admin\BackupCodesNotification;
use Webkul\User\Models\Admin;
use Webkul\User\Repositories\AdminRepository;

beforeEach(function () {
    $this->admin = Admin::factory()->create([
        'email'    => 'admin@test.com',
        'password' => Hash::make('password123'),
        'status'   => 1,
    ]);

    $this->google2fa = new Google2FA;
    Mail::fake();
});

describe('2FA Setup Endpoint', function () {
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
        $this->mock(AdminRepository::class, function ($mock) {
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

describe('2FA Enable Endpoint', function () {
    beforeEach(function () {
        // Arrange (setup secret before each test)
        $this->secret = $this->google2fa->generateSecretKey();

        $this->admin->update([
            'two_factor_secret' => encrypt($this->secret),
        ]);
    });

    it('allows an admin to enable 2FA with a valid code', function () {
        // Arrange
        $validCode = $this->google2fa->getCurrentOtp($this->secret);

        // Act
        $response = $this->actingAs($this->admin, 'admin')
            ->post(route('admin.twofactor.enable'), [
                'code' => $validCode,
            ]);

        // Assert
        $response->assertRedirect();

        $this->admin->refresh();
        expect($this->admin->two_factor_enabled)->toBeTrue();
        expect($this->admin->two_factor_verified_at)->not()->toBeNull();

        // Assert (mail notification sent)
        Mail::assertSent(BackupCodesNotification::class, fn ($mail) => $mail->hasTo($this->admin->email)
        );
    });

    it('prevents an admin from enabling 2FA with an invalid code', function () {
        // Act
        $response = $this->actingAs($this->admin, 'admin')
            ->post(route('admin.twofactor.enable'), [
                'code' => '123456',
            ]);

        // Assert
        $response->assertRedirect();

        $this->admin->refresh();
        expect($this->admin->two_factor_enabled)->toBeFalse();
        expect($this->admin->two_factor_verified_at)->toBeNull();

        // Assert (no mail sent)
        Mail::assertNotSent(BackupCodesNotification::class);
    });

    it('requires a 6-digit code to enable 2FA', function () {
        // Act
        $response = $this->actingAs($this->admin, 'admin')
            ->post(route('admin.twofactor.enable'), [
                'code' => '123',
            ]);

        // Assert
        $response->assertSessionHasErrors('code');
    });

    it('requires the code parameter when enabling 2FA', function () {
        // Act
        $response = $this->actingAs($this->admin, 'admin')
            ->post(route('admin.twofactor.enable'), []);

        // Assert
        $response->assertSessionHasErrors('code');
    });

    it('sets a session flag when 2FA is successfully enabled', function () {
        // Arrange
        $validCode = $this->google2fa->getCurrentOtp($this->secret);

        // Act
        $this->actingAs($this->admin, 'admin')
            ->post(route('admin.twofactor.enable'), [
                'code' => $validCode,
            ]);

        // Assert
        expect(session('two_factor_passed'))->toBeTrue();
    });
});

describe('Two Factor Authentication Disable', function () {

    beforeEach(function () {
        // Arrange
        $this->admin->update([
            'two_factor_secret'       => encrypt($this->google2fa->generateSecretKey()),
            'two_factor_enabled'      => true,
            'two_factor_backup_codes' => ['123456', '789012'],
            'two_factor_verified_at'  => now(),
        ]);
    });

    it('admin can disable 2FA', function () {
        // Act
        $response = $this->actingAs($this->admin, 'admin')
            ->get(route('admin.twofactor.disable'));

        // Assert
        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => trans('admin::app.account.messages.disabled_success'),
            ]);

        // Assert
        $this->admin->refresh();
        expect($this->admin->two_factor_secret)->toBeNull();
        expect($this->admin->two_factor_enabled)->toBeFalse();
        expect($this->admin->two_factor_backup_codes)->toBeNull();
        expect($this->admin->two_factor_verified_at)->toBeNull();
    });

    it('unauthenticated user cannot disable 2FA', function () {
        // Act
        $response = $this->get(route('admin.twofactor.disable'));

        // Assert
        $response->assertStatus(401);
    });
});

describe('Two Factor Authentication Login Verification', function () {

    beforeEach(function () {
        // Arrange
        $this->secret = $this->google2fa->generateSecretKey();
        $this->admin->update([
            'two_factor_secret'       => encrypt($this->secret),
            'two_factor_enabled'      => true,
            'two_factor_backup_codes' => ['670089', '569097'],
            'two_factor_verified_at'  => now(),
        ]);
    });

    it('admin can verify with valid TOTP code', function () {
        // Arrange
        $validCode = $this->google2fa->getCurrentOtp($this->secret);

        // Act
        $response = $this->actingAs($this->admin, 'admin')
            ->post(route('admin.twofactor.verifyTwoFactorCode'), [
                'code' => $validCode,
            ]);

        // Assert
        $response->assertRedirect(route('admin.dashboard.index'));
        expect(session('two_factor_passed'))->toBeTrue();
    });

    it('admin can verify with valid backup code', function () {
        // Act
        $response = $this->actingAs($this->admin, 'admin')
            ->post(route('admin.twofactor.verifyTwoFactorCode'), [
                'code' => '670089',
            ]);

        // Assert
        $response->assertRedirect(route('admin.dashboard.index'));
        expect(session('two_factor_passed'))->toBeTrue();

        // Verify backup code was removed
        $this->admin->refresh();
        expect($this->admin->two_factor_backup_codes)->not()->toContain('670089');
        expect($this->admin->two_factor_backup_codes)->toContain('569097');
    });

    it('verification fails with invalid code', function () {
        // Act
        $response = $this->actingAs($this->admin, 'admin')
            ->post(route('admin.twofactor.verifyTwoFactorCode'), [
                'code' => '999999',
            ]);

        // Assert
        $response->assertRedirect()
            ->assertSessionHasErrors('code');

        expect(session('two_factor_passed'))->toBeNull();
    });

    it('verification requires 6-digit code', function () {
        // Act
        $response = $this->actingAs($this->admin, 'admin')
            ->post(route('admin.twofactor.verifyTwoFactorCode'), [
                'code' => '123',
            ]);

        // Assert
        $response->assertSessionHasErrors('code');
    });

    it('verification requires code parameter', function () {
        // Act
        $response = $this->actingAs($this->admin, 'admin')
            ->post(route('admin.twofactor.verifyTwoFactorCode'), []);

        // Assert
        $response->assertSessionHasErrors('code');
    });

    it('backup code can only be used once', function () {
        // Act
        $this->actingAs($this->admin, 'admin')
            ->post(route('admin.twofactor.verifyTwoFactorCode'), [
                'code' => '670089',
            ]);

        // Act
        $response = $this->actingAs($this->admin, 'admin')
            ->post(route('admin.twofactor.verifyTwoFactorCode'), [
                'code' => '670089',
            ]);

        // Assert
        $response->assertRedirect()
            ->assertSessionHasErrors('code');
    });
});

describe('Two Factor Authentication Repository Methods', function () {

    it('generates QR code data correctly', function () {
        // Arrange
        $repository = app(AdminRepository::class);
        $secret = $this->google2fa->generateSecretKey();

        // Act
        $qrData = $repository->generateTwoFactorQrCodeData($this->admin, $secret);

        // Assert
        expect($qrData)->toHaveKeys(['qrCodeSvg', 'qrCodeUrl']);
        expect($qrData['qrCodeSvg'])->toContain('<svg');
        expect($qrData['qrCodeUrl'])->toContain('otpauth://totp/');
    });

    it('cleans SVG string properly', function () {
        // Arrange
        $repository = app(AdminRepository::class);
        $reflection = new \ReflectionClass($repository);
        $method = $reflection->getMethod('cleanSvgString');
        $method->setAccessible(true);

        $dirtySvg = "<svg>\x00invalid\0content</svg>";

        // Act
        $cleanSvg = $method->invoke($repository, $dirtySvg);

        // Assert
        expect($cleanSvg)->toBe('<svg>invalidcontent</svg>');
    });
});
