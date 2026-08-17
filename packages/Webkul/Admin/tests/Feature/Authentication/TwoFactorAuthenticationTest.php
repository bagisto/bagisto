<?php

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use PragmaRX\Google2FA\Google2FA;
use Webkul\Admin\Mail\Admin\BackupCodesNotification;

beforeEach(function () {
    $this->admin = $this->loginAsAdmin();

    $this->google2fa = new Google2FA;

    Mail::fake();
});

describe('two factor authentication setup endpoint', function () {
    it('allows an authenticated admin to access the 2FA setup endpoint', function () {
        // Act
        $response = $this->getJson(route('admin.two_factor.setup'));

        // Assert
        $response->assertStatus(200)
            ->assertJsonStructure(['qrCodeSvg', 'qrCodeUrl']);
    });

    it('denies unauthenticated users from accessing 2FA setup', function () {
        // Arrange
        auth('admin')->logout();

        // Act
        $response = $this->getJson(route('admin.two_factor.setup'));

        // Assert
        $response->assertStatus(401);
    });

    it('generates a secret key for a new admin on setup', function () {
        // Arrange
        expect($this->admin->two_factor_secret)->toBeNull();

        // Act
        $response = $this->getJson(route('admin.two_factor.setup'));

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
        $response = $this->getJson(route('admin.two_factor.setup'));

        // Assert
        $response->assertStatus(200);

        $this->admin->refresh();

        expect(decrypt($this->admin->two_factor_secret))->toBe($originalSecret);
    });

    it('does not expose the existing secret to a session that has not passed verification', function () {
        // Arrange - 2FA is already enabled with a secret, but the session has NOT
        // passed verification, so the setup endpoint must not hand back the secret.
        $this->admin->update([
            'two_factor_secret' => encrypt($this->google2fa->generateSecretKey()),
            'two_factor_enabled' => true,
            'two_factor_verified_at' => now(),
        ]);

        // Act
        $response = $this->getJson(route('admin.two_factor.setup'));

        // Assert
        $response->assertStatus(401);
    });
});

describe('two factor authentication enable endpoint', function () {
    beforeEach(function () {
        // Arrange
        $this->secret = $this->google2fa->generateSecretKey();

        $this->admin->update([
            'two_factor_secret' => encrypt($this->secret),
        ]);
    });

    it('allows an admin to enable 2FA with a valid code', function () {
        // Arrange
        $validCode = $this->google2fa->getCurrentOtp($this->secret);

        // Act
        $response = $this->post(route('admin.two_factor.enable'), [
            'code' => $validCode,
        ]);

        // Assert - the endpoint returns the backup codes so they can be shown and downloaded.
        $response->assertOk()
            ->assertJsonStructure(['message', 'backup_codes']);

        $this->admin->refresh();

        expect($this->admin->two_factor_enabled)->toBeTrue();
        expect($this->admin->two_factor_verified_at)->not()->toBeNull();

        Mail::assertQueued(BackupCodesNotification::class, fn ($mail) => $mail->hasTo($this->admin->email));
    });

    it('prevents an admin from enabling 2FA with an invalid code', function () {
        // Act
        $response = $this->post(route('admin.two_factor.enable'), [
            'code' => '123456',
        ]);

        // Assert - an invalid code is rejected with a validation error response.
        $response->assertStatus(422);

        $this->admin->refresh();

        expect($this->admin->two_factor_enabled)->toBeFalse();
        expect($this->admin->two_factor_verified_at)->toBeNull();

        // Assert (no mail sent)
        Mail::assertNotSent(BackupCodesNotification::class);
    });

    it('requires a 6-digit code to enable 2FA', function () {
        // Act
        $response = $this->post(route('admin.two_factor.enable'), [
            'code' => '123',
        ]);

        // Assert
        $response->assertSessionHasErrors('code');
    });

    it('requires the code parameter when enabling 2FA', function () {
        // Act
        $response = $this->post(route('admin.two_factor.enable'), []);

        // Assert
        $response->assertSessionHasErrors('code');
    });

    it('sets a session flag when 2FA is successfully enabled', function () {
        // Arrange
        $validCode = $this->google2fa->getCurrentOtp($this->secret);

        // Act
        $this->post(route('admin.two_factor.enable'), [
            'code' => $validCode,
        ]);

        // Assert
        expect(session('two_factor_passed'))->toBeTrue();
    });
});

describe('two factor authentication disable', function () {
    beforeEach(function () {
        // Arrange
        $this->admin->update([
            'two_factor_secret' => encrypt($this->google2fa->generateSecretKey()),
            'two_factor_enabled' => true,
            'two_factor_backup_codes' => ['123456', '789012'],
            'two_factor_verified_at' => now(),
        ]);
    });

    it('admin can disable 2FA', function () {
        // Act - 2FA can only be disabled once the session has passed verification.
        $response = $this->withSession(['two_factor_passed' => true])
            ->post(route('admin.two_factor.disable'));

        // Assert
        $response->assertStatus(200)
            ->assertJson([
                'message' => trans('admin::app.account.messages.disabled-success'),
            ]);

        // Assert
        $this->admin->refresh();

        expect($this->admin->two_factor_secret)->toBeNull();
        expect($this->admin->two_factor_enabled)->toBeFalse();
        expect($this->admin->two_factor_backup_codes)->toBeNull();
        expect($this->admin->two_factor_verified_at)->toBeNull();
    });

    it('unauthenticated user cannot disable 2FA', function () {
        // Arrange - logout the admin
        auth('admin')->logout();

        // Act
        $response = $this->post(route('admin.two_factor.disable'));

        // Assert - the middleware redirects unauthenticated requests to the login page.
        $response->assertRedirect(route('admin.session.create'));
    });

    it('prevents a session that has not passed verification from disabling 2FA', function () {
        // Arrange - the admin is logged in with 2FA enabled but the session has NOT
        // passed verification (no `two_factor_passed`), i.e. the 2FA-bypass scenario.

        // Act
        $response = $this->post(route('admin.two_factor.disable'));

        // Assert - the request is redirected to verification and 2FA remains enabled.
        $response->assertRedirect(route('admin.two_factor.verify.form'));

        $this->admin->refresh();

        expect($this->admin->two_factor_enabled)->toBeTrue();
        expect($this->admin->two_factor_secret)->not()->toBeNull();
    });
});

describe('two factor authentication login verification', function () {
    beforeEach(function () {
        // Arrange
        $this->secret = $this->google2fa->generateSecretKey();

        $this->admin->update([
            'two_factor_secret' => encrypt($this->secret),
            'two_factor_enabled' => true,
            'two_factor_backup_codes' => [Hash::make('670089'), Hash::make('569097')],
            'two_factor_verified_at' => now(),
        ]);
    });

    it('admin can verify with valid TOTP code', function () {
        // Arrange
        $validCode = $this->google2fa->getCurrentOtp($this->secret);

        // Act
        $response = $this->post(route('admin.two_factor.verify.store'), [
            'code' => $validCode,
        ]);

        // Assert
        $response->assertRedirect(route('admin.dashboard.index'));
        expect(session('two_factor_passed'))->toBeTrue();
    });

    it('admin can verify with valid backup code', function () {
        // Act
        $response = $this->post(route('admin.two_factor.verify.store'), [
            'code' => '670089',
        ]);

        // Assert
        $response->assertRedirect(route('admin.dashboard.index'));
        expect(session('two_factor_passed'))->toBeTrue();

        $this->admin->refresh();

        // The used code is removed and the remaining code is still stored (hashed).
        $remaining = $this->admin->two_factor_backup_codes;

        expect(collect($remaining)->contains(fn ($hash) => Hash::check('670089', $hash)))->toBeFalse();
        expect(collect($remaining)->contains(fn ($hash) => Hash::check('569097', $hash)))->toBeTrue();
    });

    it('verification fails with invalid code', function () {
        // Act
        $response = $this->post(route('admin.two_factor.verify.store'), [
            'code' => '999999',
        ]);

        // Assert
        $response->assertRedirect()
            ->assertSessionHasErrors('code');

        expect(session('two_factor_passed'))->toBeNull();
    });

    it('verification requires 6-digit code', function () {
        // Act
        $response = $this->post(route('admin.two_factor.verify.store'), [
            'code' => '123',
        ]);

        // Assert
        $response->assertSessionHasErrors('code');
    });

    it('verification requires code parameter', function () {
        // Act
        $response = $this->post(route('admin.two_factor.verify.store'), []);

        // Assert
        $response->assertSessionHasErrors('code');
    });

    it('backup code can only be used once', function () {
        // Act
        $this->post(route('admin.two_factor.verify.store'), [
            'code' => '670089',
        ]);

        $response = $this->post(route('admin.two_factor.verify.store'), [
            'code' => '670089',
        ]);

        // Assert
        $response->assertRedirect()
            ->assertSessionHasErrors('code');
    });
});

describe('two factor authentication backup codes email notifications', function () {
    it('sends backup codes email on successful 2FA enable', function () {
        // Arrange
        $secret = $this->google2fa->generateSecretKey();

        $this->admin->update(['two_factor_secret' => encrypt($secret)]);

        $validCode = $this->google2fa->getCurrentOtp($secret);

        // Act
        $this->post(route('admin.two_factor.enable'), [
            'code' => $validCode,
        ]);

        // Assert
        Mail::assertQueued(BackupCodesNotification::class, function ($mail) {
            return $mail->hasTo($this->admin->email) && ! empty($this->admin->two_factor_backup_codes);
        });
    });

    it('handles email failure gracefully', function () {
        // Arrange
        Mail::shouldReceive('to->send')->andThrow(new Exception('Mail server error'));

        $secret = $this->google2fa->generateSecretKey();

        $this->admin->update(['two_factor_secret' => encrypt($secret)]);

        $validCode = $this->google2fa->getCurrentOtp($secret);

        // Act
        $response = $this->post(route('admin.two_factor.enable'), [
            'code' => $validCode,
        ]);

        // Assert - a failed email delivery must not block enabling; the backup
        // codes are still returned so they can be shown and downloaded on screen.
        $response->assertOk()
            ->assertJsonStructure(['message', 'backup_codes']);

        $this->admin->refresh();

        expect($this->admin->two_factor_enabled)->toBeTrue();
    });
});

describe('two factor authentication integration flow', function () {
    it('complete 2FA setup and login flow', function () {
        // Act: Step 1 Setup 2FA
        $setupResponse = $this->getJson(route('admin.two_factor.setup'));

        // Assert: Step 1 Setup Successful
        $setupResponse->assertJsonStructure([
            'qrCodeSvg',
            'qrCodeUrl',
        ]);

        // Arrange: Step 2 Prepare Valid OTP Using Generated Secret
        $this->admin->refresh();

        $secret = decrypt($this->admin->two_factor_secret);

        $validCode = $this->google2fa->getCurrentOtp($secret);

        // Act: Step 2 - Enable 2FA With Valid Code
        $enableResponse = $this->post(route('admin.two_factor.enable'), [
            'code' => $validCode,
        ]);

        // Assert: Step 2 - 2FA Enable Endpoint Returns Backup Codes
        $enableResponse->assertOk()
            ->assertJsonStructure(['message', 'backup_codes']);

        // Assert: Step 3 - 2FA is enabled in DB
        $this->admin->refresh();

        expect($this->admin->two_factor_enabled)->toBeTrue();

        // Arrange: Step 4 - Generate Fresh Valid OTP
        $newValidCode = $this->google2fa->getCurrentOtp($secret);

        // Act: Step 4 - Verify 2FA During Login
        $verifyResponse = $this->post(route('admin.two_factor.verify.store'), [
            'code' => $newValidCode,
        ]);

        // Assert: Step 4 - Redirect To Dashboard + Session Flag Set
        $verifyResponse->assertRedirect(route('admin.dashboard.index'));

        expect(session('two_factor_passed'))->toBeTrue();

        // Act: Step 5 - Disable 2FA
        $disableResponse = $this->post(route('admin.two_factor.disable'));

        $this->admin->refresh();

        expect($this->admin->two_factor_enabled)->toBeFalse();
    });
});
