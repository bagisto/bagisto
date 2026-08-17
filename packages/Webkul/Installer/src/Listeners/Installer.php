<?php

namespace Webkul\Installer\Listeners;

use GuzzleHttp\Client;
use Webkul\Core\Helpers\InstalledPackages;
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
    public function __construct(
        protected AdminRepository $adminRepository,
        protected InstalledPackages $installedPackages
    ) {}

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
     * Send the installation payload to the tracker, along with the packages this
     * installation is running.
     *
     * The field keeps the `modules` name the tracker has always read, so an
     * installation on an older release goes on reporting into the same place. The
     * tracker is what decides which of the packages are core and which were
     * installed on top.
     *
     * @return void
     */
    protected function track()
    {
        $admin = $this->adminRepository->first();

        $httpClient = new Client;

        $packages = $this->installedPackages->all();

        try {
            $httpClient->request('POST', self::API_ENDPOINT, [
                'headers' => [
                    'Accept' => 'application/json',
                ],
                'json' => [
                    'domain' => config('app.url'),
                    'email' => $admin?->email,
                    'name' => $admin?->name,
                    'country_code' => config('app.default_country') ?? 'IN',
                    'modules' => $packages ?: null,
                ],
            ]);
        } catch (\Exception $e) {
        }
    }
}
