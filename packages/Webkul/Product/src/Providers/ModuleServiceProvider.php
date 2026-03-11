<?php

namespace Webkul\Product\Providers;

use Webkul\Core\Providers\CoreModuleServiceProvider;
use Webkul\Product\Models\Product;
use Webkul\Product\Models\ProductAttributeValue;
use Webkul\Product\Models\ProductBundleOption;
use Webkul\Product\Models\ProductBundleOptionProduct;
use Webkul\Product\Models\ProductBundleOptionTranslation;
use Webkul\Product\Models\ProductCustomerGroupPrice;
use Webkul\Product\Models\ProductCustomizableOption;
use Webkul\Product\Models\ProductCustomizableOptionPrice;
use Webkul\Product\Models\ProductCustomizableOptionTranslation;
use Webkul\Product\Models\ProductDownloadableLink;
use Webkul\Product\Models\ProductDownloadableSample;
use Webkul\Product\Models\ProductFlat;
use Webkul\Product\Models\ProductGroupedProduct;
use Webkul\Product\Models\ProductImage;
use Webkul\Product\Models\ProductInventory;
use Webkul\Product\Models\ProductInventoryIndex;
use Webkul\Product\Models\ProductOrderedInventory;
use Webkul\Product\Models\ProductPriceIndex;
use Webkul\Product\Models\ProductReview;
use Webkul\Product\Models\ProductReviewAttachment;
use Webkul\Product\Models\ProductSalableInventory;
use Webkul\Product\Models\ProductVideo;

class ModuleServiceProvider extends CoreModuleServiceProvider
{
    /**
     * Models.
     *
     * @var array
     */
    protected $models = [
        Product::class,
        ProductAttributeValue::class,
        ProductBundleOption::class,
        ProductBundleOptionProduct::class,
        ProductBundleOptionTranslation::class,
        ProductCustomerGroupPrice::class,
        ProductCustomizableOption::class,
        ProductCustomizableOptionPrice::class,
        ProductCustomizableOptionTranslation::class,
        ProductDownloadableLink::class,
        ProductDownloadableSample::class,
        ProductFlat::class,
        ProductGroupedProduct::class,
        ProductImage::class,
        ProductInventory::class,
        ProductInventoryIndex::class,
        ProductOrderedInventory::class,
        ProductPriceIndex::class,
        ProductReview::class,
        ProductReviewAttachment::class,
        ProductSalableInventory::class,
        ProductVideo::class,
    ];
}
