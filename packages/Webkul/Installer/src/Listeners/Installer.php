<?php

namespace Webkul\Installer\Listeners;

use Illuminate\Support\Facades\Cache;
use Webkul\User\Repositories\AdminRepository;
use Webkul\Installer\Jobs\UpdateNotification as UpdateNotificationJob;

class Installer
{
    /**
     * Create a new listener instance.
     *
     * @param  \Webkul\User\Repositories\AdminRepository  $adminRepository
     * @return void
     */
    public function __construct(protected AdminRepository $adminRepository)
    {
    }

    /**
     * After Bagisto is successfully installed
     *
     * @return void
     */
    public function installed()
    {
        $admin = $this->adminRepository->first();

        UpdateNotificationJob::dispatch([
            'api'    => 'install',
            'params' => [
                'shopDomain'       => config('app.url'),
                'shopDomainSsl'    => config('app.url'),
                'physicalUri'      => '/',
                'email'            => $admin->email,
                'firstname'        => $admin->name,
                'lastname'         => ' ',
                'countryCode'      => config('app.default_country') ?? 'in',
                'marketingConsent' => FALSE,
                'project'          => 'bagisto',
            ],
        ]);
    }

    /**
     * Get new updates
     *
     * @return void
     */
    public function getUpdates()
    {
        $alreadyUpdated = Cache::get('new-updates');

        if ($alreadyUpdated) {
            return;
        }
        
        Cache::put('new-updates', true, 60 * 60 * 24);

        $admin = auth()->guard('admin')->user();

        UpdateNotificationJob::dispatch([
            'api'    => 'update',
            'params' => [
                'shopDomain'       => config('app.url'),
                'shopDomainSsl'    => config('app.url'),
                'physicalUri'      => '/',
                'email'            => $admin->email,
                'firstname'        => $admin->name,
                'lastname'         => ' ',
                'countryCode'      => config('app.default_country') ?? 'in',
                'marketingConsent' => FALSE,
                'project'          => 'bagisto',
            ],
        ]);
    }
}