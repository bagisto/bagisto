<?php

namespace Webkul\Core\Console\Commands;

use Illuminate\Console\Command;

class BagistoPublish extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bagisto:publish { --force : Overwrite any existing files }';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Publish the available assets';

    /**
     * List of providers.
     *
     * @var array
     */
    protected $providers = [
        /**
         * Bagisto providers.
         */
        [
            'name'     => 'Admin',
            'provider' => \Webkul\Admin\Providers\AdminServiceProvider::class,
        ],
        [
            'name'     => 'UI',
            'provider' => \Webkul\Ui\Providers\UiServiceProvider::class,
        ],
        [
            'name'     => 'Core',
            'provider' => \Webkul\Core\Providers\CoreServiceProvider::class,
        ],
        [
            'name'     => 'Shop',
            'provider' => \Webkul\Shop\Providers\ShopServiceProvider::class,
        ],
        [
            'name'     => 'Product',
            'provider' => \Webkul\Product\Providers\ProductServiceProvider::class,
        ],
        [
            'name'     => 'Velocity',
            'provider' => \Webkul\Velocity\Providers\VelocityServiceProvider::class,
        ],
        [
            'name'     => 'Booking Product',
            'provider' => \Webkul\BookingProduct\Providers\BookingProductServiceProvider::class,
        ],
        [
            'name'     => 'Social',
            'provider' => \Webkul\SocialLogin\Providers\SocialLoginServiceProvider::class,
        ],
    ];

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $this->publishAllPackages();
    }

    /**
     * Publish all packages.
     *
     * @return void
     */
    public function publishAllPackages(): void
    {
        collect($this->providers)->each(function ($provider) {
            $this->publishPackage($provider);
        });
    }

    /**
     * Publish package.
     *
     * @param  array  $provider
     * @return void
     */
    public function publishPackage(array $provider): void
    {
        $this->line('');
        $this->line('-----------------------------------------');
        $this->info('Publishing ' . $provider['name']);
        $this->line('-----------------------------------------');

        $this->call('vendor:publish', [
            '--provider' => $provider['provider'],
            '--force'    => $this->option('force'),
        ]);
    }
}
