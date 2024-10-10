<?php

namespace Webkul\Product\Providers;

use Webkul\Core\Providers\CoreModuleServiceProvider;

class ModuleServiceProvider extends CoreModuleServiceProvider
{
    /**
     * Models.
     *
     * @var array
     */
    protected $models = [
        \Webkul\Product\Models\Product::class,
        \Webkul\Product\Models\ProductAttributeValue::class,
        \Webkul\Product\Models\ProductBundleOption::class,
        \Webkul\Product\Models\ProductBundleOptionProduct::class,
        \Webkul\Product\Models\ProductBundleOptionTranslation::class,
        \Webkul\Product\Models\ProductCustomerGroupPrice::class,
        \Webkul\Product\Models\ProductDownloadableLink::class,
        \Webkul\Product\Models\ProductDownloadableSample::class,
        \Webkul\Product\Models\ProductFlat::class,
        \Webkul\Product\Models\ProductGroupedProduct::class,
        \Webkul\Product\Models\ProductImage::class,
        \Webkul\Product\Models\ProductInventory::class,
        \Webkul\Product\Models\ProductInventoryIndex::class,
        \Webkul\Product\Models\ProductOrderedInventory::class,
        \Webkul\Product\Models\ProductPriceIndex::class,
        \Webkul\Product\Models\ProductReview::class,
        \Webkul\Product\Models\ProductReviewAttachment::class,
        \Webkul\Product\Models\ProductSalableInventory::class,
        \Webkul\Product\Models\ProductVideo::class,
    ];
}
