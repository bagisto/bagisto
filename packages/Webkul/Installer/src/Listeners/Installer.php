<?php

namespace Webkul\Installer\Listeners;

use GuzzleHttp\Client;
use Webkul\User\Repositories\AdminRepository;

class Installer
{
    /**
     * API Endpoint.
     *
     * @var string
     */
    protected const API_ENDPOINT = 'https://updates.bagisto.com/api/updates';

    /**
     * Create a new listener instance.
     *
     * @return void
     */
    public function __construct(protected AdminRepository $adminRepository) {}

    /**
     * After Bagisto is successfully installed.
     *
     * @return void
     */
    public function installed()
    {
        $this->track();
    }

    /**
     * After a module is installed on top of Bagisto, report it to the tracker so the
     * installation is recorded with the installed module(s).
     *
     * @param  string|array  $modules
     * @return void
     */
    public function moduleInstalled($modules)
    {
        $this->track([
            'modules' => (array) $modules,
        ]);
    }

    /**
     * Send the installation payload to the tracker, merging any extra fields (e.g. modules).
     *
     * @return void
     */
    protected function track(array $extra = [])
    {
        $admin = $this->adminRepository->first();

        $httpClient = new Client;

        try {
            $httpClient->request('POST', self::API_ENDPOINT, [
                'headers' => [
                    'Accept' => 'application/json',
                ],
                'json' => array_merge([
                    'domain' => config('app.url'),
                    'email' => $admin?->email,
                    'name' => $admin?->name,
                    'country_code' => config('app.default_country') ?? 'IN',
                ], $extra),
            ]);
        } catch (\Exception $e) {
        }
    }
}
