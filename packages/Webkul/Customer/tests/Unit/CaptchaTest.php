<?php

use Illuminate\Support\Facades\Http;
use Webkul\Core\Facades\Core;
use Webkul\Customer\Captcha;

beforeEach(function () {
    Core::shouldReceive('getConfigData')
        ->with('customer.captcha.credentials.project_id')
        ->andReturn('test-project')
        ->byDefault();

    Core::shouldReceive('getConfigData')
        ->with('customer.captcha.credentials.api_key')
        ->andReturn('test-api-key')
        ->byDefault();

    Core::shouldReceive('getConfigData')
        ->with('customer.captcha.credentials.site_key')
        ->andReturn('test-site-key')
        ->byDefault();

    Core::shouldReceive('getConfigData')
        ->with('customer.captcha.credentials.score_threshold')
        ->andReturn(0.5)
        ->byDefault();

    Core::shouldReceive('getConfigData')
        ->with('customer.captcha.credentials.status')
        ->andReturn(true)
        ->byDefault();
});

// ============================================================================
// Configuration
// ============================================================================

it('should return true when captcha is active', function () {
    Core::shouldReceive('getConfigData')
        ->with('customer.captcha.credentials.status')
        ->andReturn(true);

    $captcha = new Captcha;
    $isActive = $captcha->isActive();

    expect($isActive)->toBeTrue();
});

it('should return false when captcha is inactive', function () {
    Core::shouldReceive('getConfigData')
        ->with('customer.captcha.credentials.status')
        ->andReturn(false);

    $captcha = new Captcha;
    $isActive = $captcha->isActive();

    expect($isActive)->toBeFalse();
});

it('should return the project id from configuration', function () {
    $expectedProjectId = 'test-project-123';

    Core::shouldReceive('getConfigData')
        ->with('customer.captcha.credentials.project_id')
        ->andReturn($expectedProjectId);

    $captcha = new Captcha;
    $projectId = $captcha->getProjectId();

    expect($projectId)->toBe($expectedProjectId);
});

it('should return the api key from configuration', function () {
    $expectedApiKey = 'test-api-key-123';

    Core::shouldReceive('getConfigData')
        ->with('customer.captcha.credentials.api_key')
        ->andReturn($expectedApiKey);

    $captcha = new Captcha;
    $apiKey = $captcha->getApiKey();

    expect($apiKey)->toBe($expectedApiKey);
});

it('should return the site key from configuration', function () {
    $expectedSiteKey = 'test-site-key-123';

    Core::shouldReceive('getConfigData')
        ->with('customer.captcha.credentials.site_key')
        ->andReturn($expectedSiteKey);

    $captcha = new Captcha;
    $siteKey = $captcha->getSiteKey();

    expect($siteKey)->toBe($expectedSiteKey);
});

it('should return the score threshold from configuration', function () {
    $expectedThreshold = 0.7;

    Core::shouldReceive('getConfigData')
        ->with('customer.captcha.credentials.score_threshold')
        ->andReturn($expectedThreshold);

    $captcha = new Captcha;
    $threshold = $captcha->getScoreThreshold();

    expect($threshold)->toBe($expectedThreshold);
});

it('should return the default score threshold when none is configured', function () {
    Core::shouldReceive('getConfigData')
        ->with('customer.captcha.credentials.score_threshold')
        ->andReturn(null);

    $captcha = new Captcha;
    $threshold = $captcha->getScoreThreshold();

    expect($threshold)->toBe(0.0);
});

it('should return the client endpoint', function () {
    $expected = 'https://www.google.com/recaptcha/enterprise.js';

    $captcha = new Captcha;
    $endpoint = $captcha->getClientEndpoint();

    expect($endpoint)->toBe($expected);
});

it('should return the site verify endpoint for the project id', function () {
    $projectId = 'test-project-123';

    Core::shouldReceive('getConfigData')
        ->with('customer.captcha.credentials.project_id')
        ->andReturn($projectId);

    $captcha = new Captcha;
    $endpoint = $captcha->getSiteVerifyEndpoint();

    expect($endpoint)->toBe("https://recaptchaenterprise.googleapis.com/v1/projects/{$projectId}/assessments");
});

// ============================================================================
// Rendering
// ============================================================================

it('should render an empty string when captcha is inactive', function () {
    Core::shouldReceive('getConfigData')
        ->with('customer.captcha.credentials.status')
        ->andReturn(false);

    $captcha = new Captcha;
    $rendered = $captcha->render();

    expect($rendered)->toBe('');
});

it('should render the captcha view when captcha is active', function () {
    Core::shouldReceive('getConfigData')
        ->with('customer.captcha.credentials.status')
        ->andReturn(true);

    $captcha = new Captcha;
    $rendered = $captcha->render();

    expect($rendered)->toBeString()
        ->not->toBe('');
});

it('should render an empty string for the script when captcha is inactive', function () {
    Core::shouldReceive('getConfigData')
        ->with('customer.captcha.credentials.status')
        ->andReturn(false);

    $captcha = new Captcha;
    $rendered = $captcha->renderJS();

    expect($rendered)->toBe('');
});

it('should render the script when captcha is active', function () {
    Core::shouldReceive('getConfigData')
        ->with('customer.captcha.credentials.status')
        ->andReturn(true);

    $captcha = new Captcha;
    $rendered = $captcha->renderJS();

    expect($rendered)->toBeString()
        ->not->toBe('');
});

// ============================================================================
// Response Validation
// ============================================================================

it('should return false when validating an empty response', function () {
    $captcha = new Captcha;
    $result = $captcha->validateResponse('');

    expect($result)->toBeFalse();
});

it('should return false when validating a null response', function () {
    $captcha = new Captcha;
    $result = $captcha->validateResponse(null);

    expect($result)->toBeFalse();
});

it('should return false when the api key is not configured', function () {
    Core::shouldReceive('getConfigData')
        ->with('customer.captcha.credentials.api_key')
        ->andReturn(null);

    $captcha = new Captcha;
    $result = $captcha->validateResponse('test-token');

    expect($result)->toBeFalse();
});

it('should return false when the project id is not configured', function () {
    Core::shouldReceive('getConfigData')
        ->with('customer.captcha.credentials.project_id')
        ->andReturn(null);

    $captcha = new Captcha;
    $result = $captcha->validateResponse('test-token');

    expect($result)->toBeFalse();
});

it('should return false when the site key is not configured', function () {
    Core::shouldReceive('getConfigData')
        ->with('customer.captcha.credentials.site_key')
        ->andReturn(null);

    $captcha = new Captcha;
    $result = $captcha->validateResponse('test-token');

    expect($result)->toBeFalse();
});

it('should return true when validation succeeds with a score above the threshold', function () {
    Http::fake([
        '*' => Http::response([
            'tokenProperties' => ['valid' => true],
            'riskAnalysis' => ['score' => 0.9],
        ], 200),
    ]);

    $captcha = new Captcha;
    $result = $captcha->validateResponse('test-token');

    expect($result)->toBeTrue();
});

it('should return false when validation fails with a score below the threshold', function () {
    Http::fake([
        '*' => Http::response([
            'tokenProperties' => ['valid' => true],
            'riskAnalysis' => ['score' => 0.3],
        ], 200),
    ]);

    $captcha = new Captcha;
    $result = $captcha->validateResponse('test-token');

    expect($result)->toBeFalse();
});

it('should validate a score exactly at the threshold', function () {
    Http::fake([
        '*' => Http::response([
            'tokenProperties' => ['valid' => true],
            'riskAnalysis' => ['score' => 0.5],
        ], 200),
    ]);

    $captcha = new Captcha;
    $result = $captcha->validateResponse('test-token');

    expect($result)->toBeTrue();
});

it('should return false when the token is invalid', function () {
    Http::fake([
        '*' => Http::response([
            'tokenProperties' => ['valid' => false],
            'riskAnalysis' => ['score' => 0.9],
        ], 200),
    ]);

    $captcha = new Captcha;
    $result = $captcha->validateResponse('test-token');

    expect($result)->toBeFalse();
});

it('should return false when the api request fails', function () {
    Http::fake([
        '*' => Http::response([], 500),
    ]);

    $captcha = new Captcha;
    $result = $captcha->validateResponse('test-token');

    expect($result)->toBeFalse();
});

it('should return false when the response structure is invalid', function () {
    Http::fake([
        '*' => Http::response([
            'invalid' => 'response',
        ], 200),
    ]);

    $captcha = new Captcha;
    $result = $captcha->validateResponse('test-token');

    expect($result)->toBeFalse();
});

it('should return false when an exception occurs during validation', function () {
    Http::fake(function () {
        throw new Exception('API Error');
    });

    $captcha = new Captcha;
    $result = $captcha->validateResponse('test-token');

    expect($result)->toBeFalse();
});

it('should send the correct payload to the google api', function () {
    Http::fake([
        '*' => Http::response([
            'tokenProperties' => ['valid' => true],
            'riskAnalysis' => ['score' => 0.9],
        ], 200),
    ]);

    $captcha = new Captcha;
    $captcha->validateResponse('test-token-value');

    Http::assertSent(function ($request) {
        $data = $request->data();
        $url = $request->url();

        return str_contains($url, '?key=test-api-key')
            && isset($data['event']['token'])
            && $data['event']['token'] === 'test-token-value'
            && isset($data['event']['siteKey'])
            && $data['event']['siteKey'] === 'test-site-key'
            && isset($data['event']['expectedAction'])
            && $data['event']['expectedAction'] === 'submit';
    });
});

// ============================================================================
// Form Validation
// ============================================================================

it('should add the captcha validation rule when active', function () {
    Core::shouldReceive('getConfigData')
        ->with('customer.captcha.credentials.status')
        ->andReturn(true);

    $existingRules = [
        'email' => 'required|email',
    ];

    $captcha = new Captcha;
    $rules = $captcha->getValidations($existingRules);

    expect($rules)->toHaveKey('recaptcha_token')
        ->and($rules['recaptcha_token'])->toBe('required|captcha')
        ->and($rules['email'])->toBe('required|email');
});

it('should not add the captcha validation rule when inactive', function () {
    Core::shouldReceive('getConfigData')
        ->with('customer.captcha.credentials.status')
        ->andReturn(false);

    $existingRules = [
        'email' => 'required|email',
    ];

    $captcha = new Captcha;
    $rules = $captcha->getValidations($existingRules);

    expect($rules)->not->toHaveKey('recaptcha_token')
        ->and($rules['email'])->toBe('required|email');
});

it('should add the captcha validation messages when active', function () {
    Core::shouldReceive('getConfigData')
        ->with('customer.captcha.credentials.status')
        ->andReturn(true);

    $existingMessages = [
        'email.required' => 'Email is required',
    ];

    $captcha = new Captcha;
    $messages = $captcha->getValidationMessages($existingMessages);

    expect($messages)->toHaveKey('recaptcha_token.required')
        ->toHaveKey('recaptcha_token.captcha')
        ->and($messages['email.required'])->toBe('Email is required');
});

it('should not add the captcha validation messages when inactive', function () {
    Core::shouldReceive('getConfigData')
        ->with('customer.captcha.credentials.status')
        ->andReturn(false);

    $existingMessages = [
        'email.required' => 'Email is required',
    ];

    $captcha = new Captcha;
    $messages = $captcha->getValidationMessages($existingMessages);

    expect($messages)->not->toHaveKey('recaptcha_token.required')
        ->not->toHaveKey('recaptcha_token.captcha')
        ->and($messages['email.required'])->toBe('Email is required');
});

it('should return no validation rules when captcha is inactive', function () {
    Core::shouldReceive('getConfigData')
        ->with('customer.captcha.credentials.status')
        ->andReturn(false);

    $captcha = new Captcha;
    $rules = $captcha->getValidations([]);

    expect($rules)->toBe([]);
});

it('should return no validation messages when captcha is inactive', function () {
    Core::shouldReceive('getConfigData')
        ->with('customer.captcha.credentials.status')
        ->andReturn(false);

    $captcha = new Captcha;
    $messages = $captcha->getValidationMessages([]);

    expect($messages)->toBe([]);
});
