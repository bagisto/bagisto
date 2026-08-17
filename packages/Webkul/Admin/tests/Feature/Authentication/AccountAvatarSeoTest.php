<?php

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Webkul\User\Models\Admin;

use function Pest\Laravel\get;
use function Pest\Laravel\putJson;

function makeAdminWithAvatar(bool $withImage = true): Admin
{
    $admin = Admin::factory()->create([
        'password' => bcrypt('admin123'),
    ]);

    if ($withImage) {
        $admin->image = 'admins/'.$admin->id.'/ow92mdlq.png';

        $admin->save();

        Storage::put($admin->image, 'avatar-contents');
    }

    return $admin;
}

function accountUpdatePayload(Admin $admin, array $extra = []): array
{
    return array_merge([
        'name' => $admin->name,
        'email' => $admin->email,
        'current_password' => 'admin123',
        'password' => null,
        'password_confirmation' => null,
    ], $extra);
}

it('should render the seo drawer on the account page', function () {
    $this->loginAsAdmin();

    get(route('admin.account.edit'))
        ->assertOk()
        ->assertSee('image_meta');
});

it('should rename the admin avatar while keeping its extension', function () {
    Storage::fake();

    $admin = makeAdminWithAvatar();

    $this->loginAsAdmin($admin);

    putJson(route('admin.account.update'), accountUpdatePayload($admin, [
        'image' => ['image' => ''],
        'image_meta' => ['image' => ['file_name' => 'Jane Doe Avatar']],
    ]))->assertRedirect();

    $expected = 'admins/'.$admin->id.'/jane-doe-avatar.png';

    expect($admin->fresh()->image)->toBe($expected);

    Storage::assertExists($expected);
});

it('should name a newly uploaded avatar after the requested file name', function () {
    Storage::fake();

    $admin = makeAdminWithAvatar(withImage: false);

    $this->loginAsAdmin($admin);

    putJson(route('admin.account.update'), accountUpdatePayload($admin, [
        'image' => ['image_0' => UploadedFile::fake()->image('DSC_0005.png', 20, 20)],
        'image_meta' => ['image_0' => ['file_name' => 'Jane Doe Avatar']],
    ]))->assertRedirect();

    expect($admin->fresh()->image)->toBe('admins/'.$admin->id.'/jane-doe-avatar.png');

    Storage::assertExists($admin->fresh()->image);
});

it('should delete the avatar file when the image is removed', function () {
    Storage::fake();

    $admin = makeAdminWithAvatar();

    $originalPath = $admin->image;

    $this->loginAsAdmin($admin);

    putJson(route('admin.account.update'), accountUpdatePayload($admin))->assertRedirect();

    expect($admin->fresh()->image)->toBeNull();

    Storage::assertMissing($originalPath);
});
