<?php

use Illuminate\Support\Facades\Storage;
use Webkul\Core\Models\CoreConfig;

use function Pest\Laravel\get;

it('should return the configuration page of a known section', function () {
    $this->loginAsAdmin();

    get(route('admin.configuration.index', ['general', 'design']))
        ->assertOk();
});

it('should not find a section whose group is unknown', function () {
    $this->loginAsAdmin();

    get(route('admin.configuration.index', ['general-test', 'general']))
        ->assertNotFound();
});

it('should not find a section unknown within a known group', function () {
    $this->loginAsAdmin();

    get(route('admin.configuration.index', ['general', 'general-test']))
        ->assertNotFound();
});

it('should list every section when no section is asked for', function () {
    $this->loginAsAdmin();

    get(route('admin.configuration.index'))
        ->assertOk()
        ->assertSeeText(trans('admin::app.configuration.index.title'));
});

it('should download an uploaded configuration file', function () {
    Storage::fake();

    Storage::put('configuration/logo.png', 'file-contents');

    CoreConfig::factory()->create([
        'code' => 'general.design.admin_logo.logo_image',
        'value' => 'configuration/logo.png',
    ]);

    $this->loginAsAdmin();

    get(route('admin.configuration.download', ['general', 'design', 'logo.png']))
        ->assertOk()
        ->assertDownload('logo.png');
});

it('should not find a configuration file that was never uploaded', function () {
    $this->loginAsAdmin();

    get(route('admin.configuration.download', ['general', 'general', 'wffe']))
        ->assertNotFound();
});

it('should not find a configuration file missing from the disk', function () {
    Storage::fake();

    CoreConfig::factory()->create([
        'code' => 'general.design.admin_logo.logo_image',
        'value' => 'configuration/missing.png',
    ]);

    $this->loginAsAdmin();

    get(route('admin.configuration.download', ['general', 'design', 'missing.png']))
        ->assertNotFound();
});
