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

it('returns true when captcha is active', function () {
    Core::shouldReceive('getConfigData')
        ->with('customer.captcha.credentials.status')
        ->andReturn(true);

    $captcha = new Captcha;
    $isActive = $captcha->isActive();

    expect($isActive)->toBeTrue();
});

it('returns false when captcha is inactive', function () {
    Core::shouldReceive('getConfigData')
        ->with('customer.captcha.credentials.status')
        ->andReturn(false);

    $captcha = new Captcha;
    $isActive = $captcha->isActive();

    expect($isActive)->toBeFalse();
});

it('returns project id from configuration', function () {
    $expectedProjectId = 'test-project-123';

    Core::shouldReceive('getConfigData')
        ->with('customer.captcha.credentials.project_id')
        ->andReturn($expectedProjectId);

    $captcha = new Captcha;
    $projectId = $captcha->getProjectId();

    expect($projectId)->toBe($expectedProjectId);
});

it('returns api key from configuration', function () {
    $expectedApiKey = 'test-api-key-123';

    Core::shouldReceive('getConfigData')
        ->with('customer.captcha.credentials.api_key')
        ->andReturn($expectedApiKey);

    $captcha = new Captcha;
    $apiKey = $captcha->getApiKey();

    expect($apiKey)->toBe($expectedApiKey);
});

it('returns site key from configuration', function () {
    $expectedSiteKey = 'test-site-key-123';

    Core::shouldReceive('getConfigData')
        ->with('customer.captcha.credentials.site_key')
        ->andReturn($expectedSiteKey);

    $captcha = new Captcha;
    $siteKey = $captcha->getSiteKey();

    expect($siteKey)->toBe($expectedSiteKey);
});

it('returns score threshold from configuration', function () {
    $expectedThreshold = 0.7;

    Core::shouldReceive('getConfigData')
        ->with('customer.captcha.credentials.score_threshold')
        ->andReturn($expectedThreshold);

    $captcha = new Captcha;
    $threshold = $captcha->getScoreThreshold();

    expect($threshold)->toBe($expectedThreshold);
});

it('returns default score threshold when not configured', function () {
    Core::shouldReceive('getConfigData')
        ->with('customer.captcha.credentials.score_threshold')
        ->andReturn(null);

    $captcha = new Captcha;
    $threshold = $captcha->getScoreThreshold();

    expect($threshold)->toBe(0.0);
});

it('returns the client endpoint', function () {
    $expected = 'https://www.google.com/recaptcha/enterprise.js';

    $captcha = new Captcha;
    $endpoint = $captcha->getClientEndpoint();

    expect($endpoint)->toBe($expected);
});

it('returns the site verify endpoint with project id', function () {
    $projectId = 'test-project-123';

    Core::shouldReceive('getConfigData')
        ->with('customer.captcha.credentials.project_id')
        ->andReturn($projectId);

    $captcha = new Captcha;
    $endpoint = $captcha->getSiteVerifyEndpoint();

    expect($endpoint)->toBe("https://recaptchaenterprise.googleapis.com/v1/projects/{$projectId}/assessments");
});

it('renders empty string when captcha is inactive', function () {
    Core::shouldReceive('getConfigData')
        ->with('customer.captcha.credentials.status')
        ->andReturn(false);

    $captcha = new Captcha;
    $rendered = $captcha->render();

    expect($rendered)->toBe('');
});

it('renders captcha view when captcha is active', function () {
    Core::shouldReceive('getConfigData')
        ->with('customer.captcha.credentials.status')
        ->andReturn(true);

    $captcha = new Captcha;
    $rendered = $captcha->render();

    expect($rendered)->toBeString();
    expect($rendered)->not->toBe('');
});

it('renders empty string for js when captcha is inactive', function () {
    Core::shouldReceive('getConfigData')
        ->with('customer.captcha.credentials.status')
        ->andReturn(false);

    $captcha = new Captcha;
    $rendered = $captcha->renderJS();

    expect($rendered)->toBe('');
});

it('renders js script when captcha is active', function () {
    Core::shouldReceive('getConfigData')
        ->with('customer.captcha.credentials.status')
        ->andReturn(true);

    $captcha = new Captcha;
    $rendered = $captcha->renderJS();

    expect($rendered)->toBeString();
    expect($rendered)->not->toBe('');
});

it('returns false when validating empty response', function () {
    $captcha = new Captcha;
    $result = $captcha->validateResponse('');

    expect($result)->toBeFalse();
});

it('returns false when validating null response', function () {
    $captcha = new Captcha;
    $result = $captcha->validateResponse(null);

    expect($result)->toBeFalse();
});

it('returns false when api key is not configured', function () {
    Core::shouldReceive('getConfigData')
        ->with('customer.captcha.credentials.api_key')
        ->andReturn(null);

    $captcha = new Captcha;
    $result = $captcha->validateResponse('test-token');

    expect($result)->toBeFalse();
});

it('returns false when project id is not configured', function () {
    Core::shouldReceive('getConfigData')
        ->with('customer.captcha.credentials.project_id')
        ->andReturn(null);

    $captcha = new Captcha;
    $result = $captcha->validateResponse('test-token');

    expect($result)->toBeFalse();
});

it('returns false when site key is not configured', function () {
    Core::shouldReceive('getConfigData')
        ->with('customer.captcha.credentials.site_key')
        ->andReturn(null);

    $captcha = new Captcha;
    $result = $captcha->validateResponse('test-token');

    expect($result)->toBeFalse();
});

it('returns true when validation succeeds with score above threshold', function () {
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

it('returns false when validation fails with score below threshold', function () {
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

it('returns false when token is invalid', function () {
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

it('returns false when api request fails', function () {
    Http::fake([
        '*' => Http::response([], 500),
    ]);

    $captcha = new Captcha;
    $result = $captcha->validateResponse('test-token');

    expect($result)->toBeFalse();
});

it('returns false when response structure is invalid', function () {
    Http::fake([
        '*' => Http::response([
            'invalid' => 'response',
        ], 200),
    ]);

    $captcha = new Captcha;
    $result = $captcha->validateResponse('test-token');

    expect($result)->toBeFalse();
});

it('returns false when exception occurs during validation', function () {
    Http::fake(function () {
        throw new Exception('API Error');
    });

    $captcha = new Captcha;
    $result = $captcha->validateResponse('test-token');

    expect($result)->toBeFalse();
});

it('adds captcha validation rule when active', function () {
    Core::shouldReceive('getConfigData')
        ->with('customer.captcha.credentials.status')
        ->andReturn(true);

    $existingRules = [
        'email' => 'required|email',
    ];

    $captcha = new Captcha;
    $rules = $captcha->getValidations($existingRules);

    expect($rules)->toHaveKey('recaptcha_token');
    expect($rules['recaptcha_token'])->toBe('required|captcha');
    expect($rules['email'])->toBe('required|email');
});

it('does not add captcha validation rule when inactive', function () {
    Core::shouldReceive('getConfigData')
        ->with('customer.captcha.credentials.status')
        ->andReturn(false);

    $existingRules = [
        'email' => 'required|email',
    ];

    $captcha = new Captcha;
    $rules = $captcha->getValidations($existingRules);

    expect($rules)->not->toHaveKey('recaptcha_token');
    expect($rules['email'])->toBe('required|email');
});

it('adds captcha validation messages when active', function () {
    Core::shouldReceive('getConfigData')
        ->with('customer.captcha.credentials.status')
        ->andReturn(true);

    $existingMessages = [
        'email.required' => 'Email is required',
    ];

    $captcha = new Captcha;
    $messages = $captcha->getValidationMessages($existingMessages);

    expect($messages)->toHaveKey('recaptcha_token.required');
    expect($messages)->toHaveKey('recaptcha_token.captcha');
    expect($messages['email.required'])->toBe('Email is required');
});

it('does not add captcha validation messages when inactive', function () {
    Core::shouldReceive('getConfigData')
        ->with('customer.captcha.credentials.status')
        ->andReturn(false);

    $existingMessages = [
        'email.required' => 'Email is required',
    ];

    $captcha = new Captcha;
    $messages = $captcha->getValidationMessages($existingMessages);

    expect($messages)->not->toHaveKey('recaptcha_token.required');
    expect($messages)->not->toHaveKey('recaptcha_token.captcha');
    expect($messages['email.required'])->toBe('Email is required');
});

it('sends correct payload to google api', function () {
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

it('validates with exact threshold score', function () {
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

it('returns empty array for validations when captcha is inactive', function () {
    Core::shouldReceive('getConfigData')
        ->with('customer.captcha.credentials.status')
        ->andReturn(false);

    $captcha = new Captcha;
    $rules = $captcha->getValidations([]);

    expect($rules)->toBe([]);
});

it('returns empty array for validation messages when captcha is inactive', function () {
    Core::shouldReceive('getConfigData')
        ->with('customer.captcha.credentials.status')
        ->andReturn(false);

    $captcha = new Captcha;
    $messages = $captcha->getValidationMessages([]);

    expect($messages)->toBe([]);
});
