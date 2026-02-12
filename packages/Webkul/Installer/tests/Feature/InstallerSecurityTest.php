<?php

use function Pest\Laravel\get;
use function Pest\Laravel\post;

it('should block access to installer index page when application is already installed', function () {
    // Act and Assert.
    get(route('installer.index'))
        ->assertRedirect(route('shop.home.index'));
});

it('should block access to run migration endpoint when application is already installed', function () {
    // Act and Assert.
    post(route('installer.run_migration'), [
        'db_hostname' => 'localhost',
        'db_port' => '3306',
        'db_name' => 'test_db',
        'db_username' => 'root',
        'db_password' => '',
    ])
        ->assertRedirect(route('shop.home.index'));
});

it('should block access to run seeder endpoint when application is already installed', function () {
    // Act and Assert.
    post(route('installer.run_seeder'), [
        'selectedParameters' => [
            'allowed_locales' => ['en'],
            'allowed_currencies' => ['USD'],
        ],
        'allParameters' => [
            'app_locale' => 'en',
            'app_currency' => 'USD',
        ],
    ])
        ->assertRedirect(route('shop.home.index'));
});

it('should block access to create admin user endpoint when application is already installed', function () {
    // Act and Assert.
    post(route('installer.create_admin_user'), [
        'admin' => 'Admin User',
        'email' => 'admin@example.com',
        'password' => 'admin123',
    ])
        ->assertRedirect(route('shop.home.index'));
});

it('should block access to seed sample products endpoint when application is already installed', function () {
    // Act and Assert.
    post(route('installer.seed_sample_products'), [
        'selectedLocales' => ['en'],
        'selectedCurrencies' => ['USD'],
    ])
        ->assertRedirect(route('shop.home.index'));
});

it('should return 403 for ajax request to run migration endpoint when already installed', function () {
    // Act and Assert.
    post(route('installer.run_migration'), [
        'db_hostname' => 'localhost',
        'db_port' => '3306',
        'db_name' => 'test_db',
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

it('should return 403 for ajax request to run seeder endpoint when already installed', function () {
    // Act and Assert.
    post(route('installer.run_seeder'), [
        'selectedParameters' => [
            'allowed_locales' => ['en'],
            'allowed_currencies' => ['USD'],
        ],
        'allParameters' => [
            'app_locale' => 'en',
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

it('should return 403 for ajax request to create admin user endpoint when already installed', function () {
    // Act and Assert.
    post(route('installer.create_admin_user'), [
        'admin' => 'Admin User',
        'email' => 'admin@example.com',
        'password' => 'admin123',
    ], [
        'X-Requested-With' => 'XMLHttpRequest',
    ])
        ->assertStatus(403)
        ->assertJson([
            'message' => trans('installer::app.installer.middleware.already-installed'),
        ]);
});

it('should return 403 for ajax request to seed sample products endpoint when already installed', function () {
    // Act and Assert.
    post(route('installer.seed_sample_products'), [
        'selectedLocales' => ['en'],
        'selectedCurrencies' => ['USD'],
    ], [
        'X-Requested-With' => 'XMLHttpRequest',
    ])
        ->assertStatus(403)
        ->assertJson([
            'message' => trans('installer::app.installer.middleware.already-installed'),
        ]);
});
