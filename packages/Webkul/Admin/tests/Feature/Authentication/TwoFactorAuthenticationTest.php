<?php

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use PragmaRX\Google2FA\Google2FA;
use Webkul\Admin\Mail\Admin\BackupCodesNotification;
use Webkul\User\Models\Admin;

use function Pest\Laravel\getJson;
use function Pest\Laravel\post;

/**
 * Give an admin a fresh secret, the state the setup step leaves behind, and return it.
 */
function twoFactorSecretFor(Admin $admin): string
{
    $secret = (new Google2FA)->generateSecretKey();

    $admin->update(['two_factor_secret' => encrypt($secret)]);

    return $secret;
}

/**
 * Turn two factor authentication on for an admin with the given backup codes, and return its secret.
 */
function enableTwoFactorFor(Admin $admin, array $backupCodes = ['670089', '569097']): string
{
    $secret = (new Google2FA)->generateSecretKey();

    $admin->update([
        'two_factor_secret' => encrypt($secret),
        'two_factor_enabled' => true,
        'two_factor_backup_codes' => array_map(fn ($code) => Hash::make($code), $backupCodes),
        'two_factor_verified_at' => now(),
    ]);

    return $secret;
}

/**
 * The one time password an authenticator app shows right now for a secret.
 */
function currentOtp(string $secret): string
{
    return (new Google2FA)->getCurrentOtp($secret);
}

beforeEach(function () {
    $this->admin = $this->loginAsAdmin();

    Mail::fake();
});

// ============================================================================
// Setup
// ============================================================================

it('should hand an authenticated admin the 2FA setup', function () {
    getJson(route('admin.two_factor.setup'))
        ->assertOk()
        ->assertJsonStructure(['qrCodeSvg', 'qrCodeUrl']);
});

it('should deny a guest the 2FA setup', function () {
    auth('admin')->logout();

    getJson(route('admin.two_factor.setup'))
        ->assertUnauthorized();
});

it('should generate a secret for an admin setting up 2FA', function () {
    expect($this->admin->two_factor_secret)->toBeNull();

    getJson(route('admin.two_factor.setup'))->assertOk();

    expect($this->admin->fresh()->two_factor_secret)->not->toBeNull();
});

it('should keep the secret an admin already has on setup', function () {
    $secret = twoFactorSecretFor($this->admin);

    getJson(route('admin.two_factor.setup'))->assertOk();

    expect(decrypt($this->admin->fresh()->two_factor_secret))->toBe($secret);
});

it('should not expose the secret to a session that has not passed verification', function () {
    enableTwoFactorFor($this->admin);

    getJson(route('admin.two_factor.setup'))
        ->assertUnauthorized();
});

// ============================================================================
// Enable
// ============================================================================

it('should enable 2FA with a valid code and hand back the backup codes', function () {
    $secret = twoFactorSecretFor($this->admin);

    post(route('admin.two_factor.enable'), [
        'code' => currentOtp($secret),
    ])
        ->assertOk()
        ->assertJsonPath('message', trans('admin::app.account.messages.enabled-success'))
        ->assertJsonStructure(['message', 'backup_codes']);

    $this->admin->refresh();

    expect($this->admin->two_factor_enabled)->toBeTrue()
        ->and($this->admin->two_factor_verified_at)->not->toBeNull()
        ->and($this->admin->two_factor_backup_codes)->not->toBeEmpty()
        ->and(session('two_factor_passed'))->toBeTrue();

    Mail::assertQueued(BackupCodesNotification::class, fn ($mail) => $mail->hasTo($this->admin->email));
});

it('should not enable 2FA with an invalid code', function () {
    twoFactorSecretFor($this->admin);

    post(route('admin.two_factor.enable'), [
        'code' => '123456',
    ])
        ->assertUnprocessable()
        ->assertJsonValidationErrorFor('code');

    $this->admin->refresh();

    expect($this->admin->two_factor_enabled)->toBeFalse()
        ->and($this->admin->two_factor_verified_at)->toBeNull()
        ->and(session('two_factor_passed'))->toBeNull();

    Mail::assertNothingOutgoing();
});

it('should require a six digit code to enable 2FA', function (array $payload) {
    twoFactorSecretFor($this->admin);

    post(route('admin.two_factor.enable'), $payload)
        ->assertSessionHasErrors('code');

    expect($this->admin->fresh()->two_factor_enabled)->toBeFalse();
})->with([
    'too short' => [['code' => '123']],
    'missing' => [[]],
]);

it('should still enable 2FA when the backup codes email cannot be delivered', function () {
    Mail::shouldReceive('to->send')->andThrow(new Exception('Mail server error'));

    $secret = twoFactorSecretFor($this->admin);

    post(route('admin.two_factor.enable'), [
        'code' => currentOtp($secret),
    ])
        ->assertOk()
        ->assertJsonStructure(['message', 'backup_codes']);

    expect($this->admin->fresh()->two_factor_enabled)->toBeTrue();
});

// ============================================================================
// Disable
// ============================================================================

it('should disable 2FA for a session that has passed verification', function () {
    enableTwoFactorFor($this->admin);

    $this->withSession(['two_factor_passed' => true])
        ->post(route('admin.two_factor.disable'))
        ->assertOk()
        ->assertJsonPath('message', trans('admin::app.account.messages.disabled-success'));

    $this->admin->refresh();

    expect($this->admin->two_factor_secret)->toBeNull()
        ->and($this->admin->two_factor_enabled)->toBeFalse()
        ->and($this->admin->two_factor_backup_codes)->toBeNull()
        ->and($this->admin->two_factor_verified_at)->toBeNull();
});

it('should send a guest to the login page rather than disable 2FA', function () {
    auth('admin')->logout();

    post(route('admin.two_factor.disable'))
        ->assertRedirect(route('admin.session.create'));
});

it('should not let a session that has not passed verification disable 2FA', function () {
    enableTwoFactorFor($this->admin);

    post(route('admin.two_factor.disable'))
        ->assertRedirect(route('admin.two_factor.verify.form'));

    $this->admin->refresh();

    expect($this->admin->two_factor_enabled)->toBeTrue()
        ->and($this->admin->two_factor_secret)->not->toBeNull();
});

// ============================================================================
// Login Verification
// ============================================================================

it('should verify the login with a valid TOTP code', function () {
    $secret = enableTwoFactorFor($this->admin);

    post(route('admin.two_factor.verify.store'), [
        'code' => currentOtp($secret),
    ])
        ->assertRedirect(route('admin.dashboard.index'))
        ->assertSessionHas('success', trans('admin::app.account.messages.verified-success'));

    expect(session('two_factor_passed'))->toBeTrue();
});

it('should verify the login with a backup code and spend it', function () {
    enableTwoFactorFor($this->admin);

    post(route('admin.two_factor.verify.store'), [
        'code' => '670089',
    ])
        ->assertRedirect(route('admin.dashboard.index'));

    expect(session('two_factor_passed'))->toBeTrue();

    $remaining = collect($this->admin->fresh()->two_factor_backup_codes);

    expect($remaining->contains(fn ($hash) => Hash::check('670089', $hash)))->toBeFalse()
        ->and($remaining->contains(fn ($hash) => Hash::check('569097', $hash)))->toBeTrue();
});

it('should not verify the login with an invalid code', function () {
    enableTwoFactorFor($this->admin);

    post(route('admin.two_factor.verify.store'), [
        'code' => '999999',
    ])
        ->assertRedirect()
        ->assertSessionHasErrors('code');

    expect(session('two_factor_passed'))->toBeNull();
});

it('should require a six digit code to verify the login', function (array $payload) {
    enableTwoFactorFor($this->admin);

    post(route('admin.two_factor.verify.store'), $payload)
        ->assertSessionHasErrors('code');

    expect(session('two_factor_passed'))->toBeNull();
})->with([
    'too short' => [['code' => '123']],
    'missing' => [[]],
]);

it('should accept a backup code only once', function () {
    enableTwoFactorFor($this->admin);

    post(route('admin.two_factor.verify.store'), [
        'code' => '670089',
    ])
        ->assertRedirect(route('admin.dashboard.index'));

    post(route('admin.two_factor.verify.store'), [
        'code' => '670089',
    ])
        ->assertRedirect()
        ->assertSessionHasErrors('code');
});

// ============================================================================
// End To End
// ============================================================================

it('should walk an admin through setup, enabling, verification and disabling', function () {
    getJson(route('admin.two_factor.setup'))
        ->assertOk()
        ->assertJsonStructure(['qrCodeSvg', 'qrCodeUrl']);

    $secret = decrypt($this->admin->fresh()->two_factor_secret);

    post(route('admin.two_factor.enable'), [
        'code' => currentOtp($secret),
    ])
        ->assertOk()
        ->assertJsonStructure(['message', 'backup_codes']);

    expect($this->admin->fresh()->two_factor_enabled)->toBeTrue();

    post(route('admin.two_factor.verify.store'), [
        'code' => currentOtp($secret),
    ])
        ->assertRedirect(route('admin.dashboard.index'));

    expect(session('two_factor_passed'))->toBeTrue();

    post(route('admin.two_factor.disable'))
        ->assertOk();

    expect($this->admin->fresh()->two_factor_enabled)->toBeFalse();
});
