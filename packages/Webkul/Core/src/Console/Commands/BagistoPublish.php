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
     * List of Bagisto's providers.
     *
     * @var array
     */
    protected $providers = [
        'Admin'           => "Webkul\Admin\Providers\AdminServiceProvider",
        'UI'              => "Webkul\Ui\Providers\UiServiceProvider",
        'Core'            => "Webkul\Core\Providers\CoreServiceProvider",
        'Shop'            => "Webkul\Shop\Providers\ShopServiceProvider",
        'Product'         => "Webkul\Product\Providers\ProductServiceProvider",
        'Velocity'        => "Webkul\Velocity\Providers\VelocityServiceProvider",
        'Booking Product' => "Webkul\BookingProduct\Providers\BookingProductServiceProvider",
        'Social'          => "Webkul\SocialLogin\Providers\SocialLoginServiceProvider",
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
        collect($this->providers)->each(function ($provider, $name) {
            $this->publishPackage($provider, $name);
        });
    }

    /**
     * Publish package.
     *
     * @param  string  $provider
     * @param  string  $name
     * @return void
     */
    public function publishPackage(string $provider, ?string $name = null): void
    {
        $this->line('');
        $this->line('-----------------------------------------');
        $this->info('Publishing ' . $name);
        $this->line('-----------------------------------------');

        $this->call('vendor:publish', [
            '--provider' => $provider,
            '--force'    => $this->option('force'),
        ]);
    }
}
