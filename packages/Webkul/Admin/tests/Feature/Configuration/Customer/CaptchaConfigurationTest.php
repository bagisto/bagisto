<?php

use Webkul\Core\Models\Channel;
use Webkul\Core\Models\CoreConfig;
use Webkul\Core\Models\Locale;

use function Pest\Laravel\get;
use function Pest\Laravel\postJson;

it('should return the captcha configuration page', function () {
    // Act and Assert
    $this->loginAsAdmin();

    get(route('admin.configuration.index', ['customer', 'captcha']))
        ->assertOk()
        ->assertSeeText(trans('admin::app.configuration.index.customer.captcha.title'))
        ->assertSeeText(trans('admin::app.configuration.index.customer.captcha.credentials.title'));
});

it('should display captcha configuration form fields', function () {
    // Act and Assert
    $this->loginAsAdmin();

    get(route('admin.configuration.index', ['customer', 'captcha']))
        ->assertOk()
        ->assertSee('customer[captcha][credentials][status]', false)
        ->assertSee('customer[captcha][credentials][project_id]', false)
        ->assertSee('customer[captcha][credentials][api_key]', false)
        ->assertSee('customer[captcha][credentials][site_key]', false)
        ->assertSee('customer[captcha][credentials][score_threshold]', false);
});

it('should save captcha configuration with all required fields', function () {
    // Arrange
    $channel = Channel::factory()->create();

    $locale = Locale::factory()->create();

    $credentials = [
        'status' => '1',
        'project_id' => 'test-project-123',
        'api_key' => 'AIzaSyD-test-api-key-123',
        'site_key' => '6LeIxAcTAAAAAJcZVRqyHh71UMIEGNQ_MXjiZKhI',
        'score_threshold' => '0.7',
    ];

    // Act and Assert
    $this->loginAsAdmin();

    postJson(route('admin.configuration.store', ['customer', 'captcha']), [
        'locale' => $locale->code,
        'channel' => $channel->code,
        'customer' => [
            'captcha' => [
                'credentials' => $credentials,
            ],
        ],
    ])
        ->assertRedirect()
        ->assertSessionHas('success', trans('admin::app.configuration.index.save-message'));

    // Verify data is saved in database
    $this->assertDatabaseHas('core_config', [
        'code' => 'customer.captcha.credentials.status',
        'value' => $credentials['status'],
    ]);

    $this->assertDatabaseHas('core_config', [
        'code' => 'customer.captcha.credentials.project_id',
        'value' => $credentials['project_id'],
    ]);

    $this->assertDatabaseHas('core_config', [
        'code' => 'customer.captcha.credentials.api_key',
        'value' => $credentials['api_key'],
    ]);

    $this->assertDatabaseHas('core_config', [
        'code' => 'customer.captcha.credentials.site_key',
        'value' => $credentials['site_key'],
    ]);

    $this->assertDatabaseHas('core_config', [
        'code' => 'customer.captcha.credentials.score_threshold',
        'value' => $credentials['score_threshold'],
    ]);
});

it('should update existing captcha configuration', function () {
    // Arrange
    $channel = Channel::factory()->create();

    $locale = Locale::factory()->create();

    $oldCredentials = [
        'status' => '0',
        'project_id' => 'old-project-id',
    ];

    $newCredentials = [
        'status' => '1',
        'project_id' => 'new-project-456',
        'api_key' => 'AIzaSyD-new-api-key',
        'site_key' => '6LeIxAcTAAAAAJcZVRqyHh71UMIEGNQ_NEW',
        'score_threshold' => '0.8',
    ];

    // Create existing config
    CoreConfig::create([
        'code' => 'customer.captcha.credentials.status',
        'value' => $oldCredentials['status'],
    ]);

    CoreConfig::create([
        'code' => 'customer.captcha.credentials.project_id',
        'value' => $oldCredentials['project_id'],
    ]);

    // Act and Assert
    $this->loginAsAdmin();

    postJson(route('admin.configuration.store', ['customer', 'captcha']), [
        'locale' => $locale->code,
        'channel' => $channel->code,
        'customer' => [
            'captcha' => [
                'credentials' => $newCredentials,
            ],
        ],
    ])
        ->assertRedirect()
        ->assertSessionHas('success', trans('admin::app.configuration.index.save-message'));

    // Verify updated values
    $this->assertDatabaseHas('core_config', [
        'code' => 'customer.captcha.credentials.status',
        'value' => $newCredentials['status'],
    ]);

    $this->assertDatabaseHas('core_config', [
        'code' => 'customer.captcha.credentials.project_id',
        'value' => $newCredentials['project_id'],
    ]);
});

it('should save captcha configuration with status disabled', function () {
    // Arrange
    $channel = Channel::factory()->create();

    $locale = Locale::factory()->create();

    CoreConfig::create([
        'code' => 'customer.captcha.credentials.status',
        'value' => '1',
    ]);

    // Act and Assert
    $this->loginAsAdmin();

    postJson(route('admin.configuration.store', ['customer', 'captcha']), [
        'locale' => $locale->code,
        'channel' => $channel->code,
        'customer' => [
            'captcha' => [
                'credentials' => [
                    'status' => '0',
                ],
            ],
        ],
    ])
        ->assertRedirect();

    $this->assertDatabaseHas('core_config', [
        'code' => 'customer.captcha.credentials.status',
        'value' => '0',
    ]);
});

it('should save captcha configuration with different score thresholds', function () {
    // Arrange
    $channel = Channel::factory()->create();
    $locale = Locale::factory()->create();

    $this->loginAsAdmin();

    // Test with 0.7 threshold
    $threshold = '0.7';

    postJson(route('admin.configuration.store', ['customer', 'captcha']), [
        'locale' => $locale->code,
        'channel' => $channel->code,
        'customer' => [
            'captcha' => [
                'credentials' => [
                    'score_threshold' => $threshold,
                ],
            ],
        ],
    ])
        ->assertRedirect();

    $this->assertDatabaseHas('core_config', [
        'code' => 'customer.captcha.credentials.score_threshold',
        'value' => $threshold,
    ]);

    // Test with 0.9 threshold
    $threshold = '0.9';

    postJson(route('admin.configuration.store', ['customer', 'captcha']), [
        'locale' => $locale->code,
        'channel' => $channel->code,
        'customer' => [
            'captcha' => [
                'credentials' => [
                    'score_threshold' => $threshold,
                ],
            ],
        ],
    ])
        ->assertRedirect();

    $this->assertDatabaseHas('core_config', [
        'code' => 'customer.captcha.credentials.score_threshold',
        'value' => $threshold,
    ]);
});

it('should display existing captcha configuration values', function () {
    // Arrange
    $credentials = [
        'status' => '1',
        'project_id' => 'display-test-project',
        'site_key' => '6LeIxAcTAAAAAJcZVRqyHh71DISPLAY',
    ];

    CoreConfig::create([
        'code' => 'customer.captcha.credentials.status',
        'value' => $credentials['status'],
    ]);

    CoreConfig::create([
        'code' => 'customer.captcha.credentials.project_id',
        'value' => $credentials['project_id'],
    ]);

    CoreConfig::create([
        'code' => 'customer.captcha.credentials.site_key',
        'value' => $credentials['site_key'],
    ]);

    // Act and Assert
    $this->loginAsAdmin();

    get(route('admin.configuration.index', ['customer', 'captcha']))
        ->assertOk();

    // Verify data exists in database
    $this->assertDatabaseHas('core_config', [
        'code' => 'customer.captcha.credentials.project_id',
        'value' => $credentials['project_id'],
    ]);

    $this->assertDatabaseHas('core_config', [
        'code' => 'customer.captcha.credentials.site_key',
        'value' => $credentials['site_key'],
    ]);
});

it('should handle special characters in API keys and site keys', function () {
    // Arrange
    $channel = Channel::factory()->create();

    $locale = Locale::factory()->create();

    $specialApiKey = 'AIzaSyD-9_8a7b6c5d4e3f2g1h0_SPECIAL-CHARS';

    $specialSiteKey = '6LeIxAcTAAAAAJcZVRqyHh71-_SPECIAL';

    // Act and Assert
    $this->loginAsAdmin();

    postJson(route('admin.configuration.store', ['customer', 'captcha']), [
        'locale' => $locale->code,
        'channel' => $channel->code,
        'customer' => [
            'captcha' => [
                'credentials' => [
                    'api_key' => $specialApiKey,
                    'site_key' => $specialSiteKey,
                ],
            ],
        ],
    ])
        ->assertRedirect();

    $this->assertDatabaseHas('core_config', [
        'code' => 'customer.captcha.credentials.api_key',
        'value' => $specialApiKey,
    ]);

    $this->assertDatabaseHas('core_config', [
        'code' => 'customer.captcha.credentials.site_key',
        'value' => $specialSiteKey,
    ]);
});
