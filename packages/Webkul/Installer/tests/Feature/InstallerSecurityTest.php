<?php

use function Pest\Laravel\get;
use function Pest\Laravel\post;

it('should block access to installer index page when application is already installed', function () {
    // Act and Assert.
    get(route('installer.index'))
        ->assertRedirect(route('shop.home.index'));
});

it('should block access to env file setup endpoint when application is already installed', function () {
    // Act and Assert.
    $response = post(route('installer.env_file_setup'), [
        'db_hostname' => 'localhost',
        'db_port'     => '3306',
        'db_name'     => 'test_db',
        'db_username' => 'root',
        'db_password' => '',
    ]);

    $response->assertRedirect(route('shop.home.index'));
});

it('should block access to run migration endpoint when application is already installed', function () {
    // Act and Assert.
    post(route('installer.run_migration'))
        ->assertRedirect(route('shop.home.index'));
});

it('should block access to run seeder endpoint when application is already installed', function () {
    // Act and Assert.
    post(route('installer.run_seeder'), [
        'selectedParameters' => [
            'allowed_locales'    => ['en'],
            'allowed_currencies' => ['USD'],
        ],
        'allParameters' => [
            'app_locale'   => 'en',
            'app_currency' => 'USD',
        ],
    ])
        ->assertRedirect(route('shop.home.index'));
});

it('should block access to download sample endpoint when application is already installed', function () {
    // Act and Assert.
    get(route('installer.download_sample'))
        ->assertRedirect(route('shop.home.index'));
});

it('should block access to admin config setup endpoint when application is already installed', function () {
    // Act and Assert.
    post(route('installer.admin_config_setup'), [
        'admin'    => 'Admin User',
        'email'    => 'admin@example.com',
        'password' => 'admin123',
    ])
        ->assertRedirect(route('shop.home.index'));
});

it('should block access to sample products setup endpoint when application is already installed', function () {
    // Act and Assert.
    post(route('installer.sample_products_setup'), [
        'selectedLocales'    => ['en'],
        'selectedCurrencies' => ['USD'],
    ])
        ->assertRedirect(route('shop.home.index'));
});

it('should return 403 for ajax request to env file setup endpoint when already installed', function () {
    // Act and Assert.
    post(route('installer.env_file_setup'), [
        'db_hostname' => 'localhost',
        'db_port'     => '3306',
        'db_name'     => 'test_db',
        'db_username' => 'root',
        'db_password' => '',
    ], [
        'X-Requested-With' => 'XMLHttpRequest',
    ])
        ->assertStatus(403)
        ->assertJson([
            'message' => trans('installer::app.installer.middleware.already-installed'),
        ]);
});

it('should return 403 for ajax request to run migration endpoint when already installed', function () {
    // Act and Assert.
    post(route('installer.run_migration'), [], [
        'X-Requested-With' => 'XMLHttpRequest',
    ])
        ->assertStatus(403)
        ->assertJson([
            'message' => trans('installer::app.installer.middleware.already-installed'),
        ]);
});

it('should return 403 for ajax request to run seeder endpoint when already installed', function () {
    // Act and Assert.
    post(route('installer.run_seeder'), [
        'selectedParameters' => [
            'allowed_locales'    => ['en'],
            'allowed_currencies' => ['USD'],
        ],
        'allParameters' => [
            'app_locale'   => 'en',
            'app_currency' => 'USD',
        ],
    ], [
        'X-Requested-With' => 'XMLHttpRequest',
    ])
        ->assertStatus(403)
        ->assertJson([
            'message' => trans('installer::app.installer.middleware.already-installed'),
        ]);
});

it('should return 403 for ajax request to download sample endpoint when already installed', function () {
    // Act and Assert.
    get(route('installer.download_sample'), [
        'X-Requested-With' => 'XMLHttpRequest',
    ])
        ->assertStatus(403)
        ->assertJson([
            'message' => trans('installer::app.installer.middleware.already-installed'),
        ]);
});

it('should return 403 for ajax request to admin config setup endpoint when already installed', function () {
    // Act and Assert.
    post(route('installer.admin_config_setup'), [
        'admin'    => 'Admin User',
        'email'    => 'admin@example.com',
        'password' => 'admin123',
    ], [
        'X-Requested-With' => 'XMLHttpRequest',
    ])
        ->assertStatus(403)
        ->assertJson([
            'message' => trans('installer::app.installer.middleware.already-installed'),
        ]);
});

it('should return 403 for ajax request to sample products setup endpoint when already installed', function () {
    // Act and Assert.
    post(route('installer.sample_products_setup'), [
        'selectedLocales'    => ['en'],
        'selectedCurrencies' => ['USD'],
    ], [
        'X-Requested-With' => 'XMLHttpRequest',
    ])
        ->assertStatus(403)
        ->assertJson([
            'message' => trans('installer::app.installer.middleware.already-installed'),
        ]);
});
