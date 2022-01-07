<?php

namespace Webkul\Customer;

use Webkul\Customer\Contracts\Captcha as CaptchaContract;

class Captcha implements CaptchaContract
{
    /**
     * Site key.
     *
     * @var string
     */
    protected $siteKey;

    /**
     * Secret key.
     *
     * @var string
     */
    protected $secretKey;

    /**
     * Create a new instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->siteKey = $this->getSiteKey();

        $this->secretKey = $this->getSecretKey();
    }

    /**
     * Check whether captcha is active or not.
     *
     * @return bool
     */
    public function isActive(): bool
    {
        return (bool) core()->getConfigData('customer.captcha.credentials.status');
    }

    /**
     * Get site key from the core config.
     *
     * @return null|string
     */
    public function getSiteKey(): ?string
    {
        return core()->getConfigData('customer.captcha.credentials.site_key');
    }

    /**
     * Get secret key from the core config.
     *
     * @return null|string
     */
    public function getSecretKey(): ?string
    {
        return core()->getConfigData('customer.captcha.credentials.secret_key');
    }

    /**
     * Get client endpoint.
     *
     * @return string
     */
    public function getClientEndpoint(): string
    {
        return static::CLIENT_ENDPOINT;
    }

    /**
     * Get site verify endpoint.
     *
     * @return string
     */
    public function getSiteVerifyEndpoint(): string
    {
        return static::SITE_VERIFY_ENDPOINT;
    }

    /**
     * Render JS.
     *
     * @return string
     */
    public function renderJS(): string
    {
        return $this->isActive()
            ? $this->getCaptchaJSView()
            : '';
    }

    /**
     * Render Captcha.
     *
     * @return string
     */
    public function render(): string
    {
        return $this->isActive()
            ? $this->getCaptchaView()
            : '';
    }

    /**
     * Validate response.
     *
     * @return bool
     */
    public function validateResponse($response): bool
    {
        $client = new \GuzzleHttp\Client();

        $response = $client->post($this->getSiteVerifyEndpoint(), [
            'query' => [
                'secret' => $this->secretKey,
                'response' => $response
            ]
        ]);

        return json_decode($response->getBody())->success;
    }

    /**
     * Get or merge existing validations with your captcha validations.
     *
     * @return array
     */
    public function getValidations($rules = []): array
    {
        return $this->isActive()
            ? array_merge($rules, ['g-recaptcha-response' => 'required|captcha'])
            : $rules;
    }

    /**
     * Get or merge existing validation messages with your captcha validation messages.
     *
     * @return array
     */
    public function getValidationMessages($messages = []): array
    {
        return $this->isActive()
            ? array_merge($messages, [
                'g-recaptcha-response.required' => __('customer::app.admin.system.captcha.validations.required'),
                'g-recaptcha-response.captcha' => __('customer::app.admin.system.captcha.validations.captcha')
            ])
            : $messages;
    }

    /**
     * Get attributes.
     *
     * @return array
     */
    protected function getAttributes(): array
    {
        return [
            'class' => 'g-recaptcha',
            'data-sitekey' => $this->siteKey,
        ];
    }

    /**
     * Build attributes.
     *
     * @param array $attributes
     * @return string
     */
    protected function buildHTMLAttributes(array $attributes): string
    {
        $htmlAttributes = [];

        foreach ($attributes as $key => $value) {
            $htmlAttributes[] =  "{$key}=\"{$value}\"";
        }

        return count($htmlAttributes)
            ? implode(' ', $htmlAttributes)
            : '';
    }

    /**
     * Get captcha view.
     *
     * @return string
     */
    protected function getCaptchaView()
    {
        $htmlAttributes = $this->buildHTMLAttributes($this->getAttributes());

        return view('customer::captcha.view', [
            'htmlAttributes' => $htmlAttributes
        ])->render();
    }

    /**
     * Get captcha script view.
     *
     * @return string
     */
    protected function getCaptchaJSView()
    {
        return view('customer::captcha.scripts', [
            'clientEndPoint' => $this->getClientEndpoint()
        ])->render();
    }
}
