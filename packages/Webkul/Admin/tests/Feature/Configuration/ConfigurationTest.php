<?php

use function Pest\Laravel\get;

it('should return the configuration page of a known section', function () {
    // Act and Assert
    $this->loginAsAdmin();

    get(route('admin.configuration.index', ['general', 'design']))
        ->assertOk();
});

it('should not found a section whose group is unknown', function () {
    // Act and Assert
    $this->loginAsAdmin();

    get(route('admin.configuration.index', ['general-test', 'general']))
        ->assertNotFound();
});

it('should not found a section unknown within a known group', function () {
    // Act and Assert
    $this->loginAsAdmin();

    get(route('admin.configuration.index', ['general', 'general-test']))
        ->assertNotFound();
});

it('should list every section when no section is asked for', function () {
    // Act and Assert
    $this->loginAsAdmin();

    get(route('admin.configuration.index'))
        ->assertOk()
        ->assertSeeText(trans('admin::app.configuration.index.title'));
});
