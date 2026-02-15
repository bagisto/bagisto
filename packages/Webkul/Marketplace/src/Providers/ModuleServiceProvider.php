<?php

namespace Webkul\Marketplace\Providers;

use Webkul\Core\Providers\CoreModuleServiceProvider;

class ModuleServiceProvider extends CoreModuleServiceProvider
{
    /**
     * Models.
     *
     * @var array
     */
    protected $models = [
        \Webkul\Marketplace\Models\Seller::class,
        \Webkul\Marketplace\Models\SellerProduct::class,
        \Webkul\Marketplace\Models\SellerOrder::class,
        \Webkul\Marketplace\Models\SellerTransaction::class,
        \Webkul\Marketplace\Models\SellerReview::class,
    ];
}
