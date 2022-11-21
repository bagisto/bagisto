<?php

namespace Webkul\Velocity\Helpers;

use Webkul\Product\Facades\ProductImage;
use Webkul\Product\Helpers\Review;
use Webkul\Attribute\Repositories\AttributeOptionRepository;
use Webkul\Product\Repositories\ProductRepository;
use Webkul\Product\Repositories\ProductReviewRepository;
use Webkul\Velocity\Repositories\OrderBrandsRepository;
use Webkul\Velocity\Repositories\VelocityMetadataRepository;

class Helper extends Review
{
    /**
     * Create a helper instance.
     *
     * @param  \Webkul\Attribute\Repositories\AttributeOptionRepository  $attributeOptionRepository
     * @param  \Webkul\Velocity\Repositories\OrderBrandsRepository  $productRepository
     * @param  \Webkul\Product\Repositories\ProductReviewRepository  $productReviewRepository
     * @param  \Webkul\Product\Repositories\ProductRepository  $orderBrands
     * @param  \Webkul\Velocity\Repositories\VelocityMetadataRepository  $velocityMetadataRepository
     * @return void
     */
    public function __construct(
        protected AttributeOptionRepository $attributeOptionRepository,
        protected ProductRepository $productRepository,
        protected ProductReviewRepository $productReviewRepository,
        protected OrderBrandsRepository $orderBrandsRepository,
        protected VelocityMetadataRepository $velocityMetadataRepository
    )
    {
    }

    /**
     * Top brand.
     *
     * @param  \Webkul\Sales\Contracts\Order  $order
     * @return void
     */
    public function topBrand($order)
    {
        foreach ($order->items as $orderItem) {
            try {
                $this->orderBrandsRepository->create([
                    'order_item_id' => $orderItem->id,
                    'order_id'      => $orderItem->order_id,
                    'product_id'    => $orderItem->product_id,
                    'brand'         => $orderItem->product->brand,
                ]);
            } catch (\Exception $exception) {
            }
        }
    }

    /**
     * Get brands with categories.
     *
     * @return \Illuminate\Support\Collection|null
     */
    public function getBrandsWithCategories()
    {
        $orderBrands = $this->orderBrandsRepository->get()->toArray();

        if (empty($orderBrands)) {
            return;
        }
        
        foreach ($orderBrands as $product) {
            $productCategories = $this->productRepository->with('categories')->findWhereIn('id', [$product['product_id']])->toArray();
        }

        $categoryNames = [];

        foreach ($productCategories as $totalData) {
            $brand = $this->attributeOptionRepository->findOneWhere(['id' => $totalData['brand']]);

            foreach ($totalData['categories'] as $category) {
                foreach ($category['translations'] as $categoryTranslation) {
                    if (! isset($brand->admin_name)) {
                        continue;
                    }

                    $categoryNames[] = $brandData[$brand->admin_name][] = $categoryTranslation['name'];
                }
            }
        }

        $brandNames = [];

        $uniqueCategoryNames = array_unique($categoryNames);

        foreach ($uniqueCategoryNames as $categoryNameValue) {
            foreach ($brandData as $brandDataKey => $brandDataValue) {
                if (! in_array($categoryNameValue, $brandDataValue)) {
                    continue;
                }

                $brandNames[$categoryNameValue][] = $brandDataKey;
            }
        }

        $brands = [];

        foreach ($brandNames as $brandKey => $brandValue) {
            $brands[$brandKey][] = implode(' | ', array_map('ucfirst', $brandValue));
        }

        return $brands;
    }

    /**
     * Returns the count rating of the product.
     *
     * @return \Webkul\Velocity\Repositories\VelocityMetadataRepository
     */
    public function getVelocityMetaData($locale = null, $channel = null, $default = true)
    {
        static $metaData;

        if ($metaData) {
            return $metaData;
        }

        if (! $locale) {
            $locale = core()->getRequestedLocaleCode();
        }

        if (! $channel) {
            $channel = core()->getRequestedChannelCode();
        }

        try {
            $metaData = $this->velocityMetadataRepository->findOneWhere([
                'locale'  => $locale,
                'channel' => $channel,
            ]);

            if (
                ! $metaData
                && $default
            ) {
                $metaData = $this->velocityMetadataRepository->findOneWhere([
                    'locale'  => 'en',
                    'channel' => 'default',
                ]);
            }

            return $metaData;
        } catch (\Exception $exception) {
        }
    }

    /**
     * Get shop recent views.
     *
     * @param  int  $reviewCount
     * @return \Illuminate\Support\Collection
     */
    public function getShopRecentReviews($reviewCount = 4)
    {
        $reviews = $this->productReviewRepository
            ->getModel()
            ->orderBy('id', 'desc')
            ->where('status', 'approved')
            ->take($reviewCount)->get();

        return $reviews;
    }

    /**
     * Get messages from session.
     *
     * @return void
     */
    public function getMessage()
    {
        $message = [
            'message'      => '',
            'messageType'  => '',
            'messageLabel' => '',
        ];

        if ($message['message'] = session('success')) {
            $message['messageType'] = 'alert-success';
            $message['messageLabel'] = __('velocity::app.shop.general.alert.success');
        } elseif ($message['message'] = session('warning')) {
            $message['messageType'] = 'alert-warning';
            $message['messageLabel'] = __('velocity::app.shop.general.alert.warning');
        } elseif ($message['message'] = session('error')) {
            $message['messageType'] = 'alert-danger';
            $message['messageLabel'] = __('velocity::app.shop.general.alert.error');
        } elseif ($message['message'] = session('info')) {
            $message['messageType'] = 'alert-info';
            $message['messageLabel'] = __('velocity::app.shop.general.alert.info');
        }

        return $message;
    }

    /**
     * Get json translations.
     *
     * @return array
     */
    public function jsonTranslations()
    {
        $currentLocale = app()->getLocale();

        $path = __DIR__ . "/../Resources/lang/$currentLocale/app.php";

        if (is_string($path) && is_readable($path)) {
            return include $path;
        } else {
            $currentLocale = 'en';

            $path = __DIR__ . "/../Resources/lang/$currentLocale/app.php";

            return include $path;
        }
    }

    /**
     * Format cart item.
     *
     * @param  \Webkul\Checkout\Contracts\CartItem  $item
     * @return array
     */
    public function formatCartItem($item)
    {
        $product = $item->product;

        $images = $product->getTypeInstance()->getBaseImage($item);

        return [
            'images'    => $images,
            'itemId'    => $item->id,
            'name'      => $item->name,
            'quantity'  => $item->quantity,
            'url_key'   => $product->url_key,
            'baseTotal' => core()->currency($item->base_total),
        ];
    }

    /**
     * Format product.
     *
     * @param  \Webkul\Product\Contracts\Product  $product
     * @param  bool                               $list
     * @param  array                              $metaInformation
     * @return array
     */
    public function formatProduct($product, $list = false, $metaInformation = [])
    {
        $reviewHelper = app('Webkul\Product\Helpers\Review');

        $galleryImages = ProductImage::getGalleryImages($product);
        $productImage = ProductImage::getProductBaseImage($product, $galleryImages)['medium_image_url'];

        $largeProductImageName = 'large-product-placeholder.png';
        $mediumProductImageName = 'medium-product-placeholder.png';

        if (strpos($productImage, $mediumProductImageName) > -1) {
            $productImageNameCollection = explode('/', $productImage);
            $productImageName = $productImageNameCollection[sizeof($productImageNameCollection) - 1];

            if ($productImageName == $mediumProductImageName) {
                $productImage = str_replace($mediumProductImageName, $largeProductImageName, $productImage);
            }
        }

        $priceHTML = view('shop::products.price', ['product' => $product])->render();

        $isProductNew = ($product->new && strpos($priceHTML, 'sticker sale') === false) ? __('shop::app.products.new') : false;

        return [
            'priceHTML'        => $priceHTML,
            'avgRating'        => ceil($reviewHelper->getAverageRating($product)),
            'totalReviews'     => $reviewHelper->getTotalReviews($product),
            'image'            => $productImage,
            'new'              => $isProductNew,
            'galleryImages'    => $galleryImages,
            'name'             => $product->name,
            'slug'             => $product->url_key,
            'description'      => $product->description,
            'shortDescription' => $product->short_description,
            'firstReviewText'  => trans('velocity::app.products.be-first-review'),
            'addToCartHtml'    => view('shop::products.add-to-cart', [
                'product'          => $product,
                'addWishlistClass' => '',

                'showCompare' => (bool) core()->getConfigData('general.content.shop.compare_option'),

                'btnText' => $metaInformation['btnText'] ?? null,

                'moveToCart' => $metaInformation['moveToCart'] ?? null,

                'addToCartBtnClass' => empty($list) ? 'small-padding' : '',
            ])->render(),
        ];
    }

    /**
     * Returns the count rating of the product.
     *
     * @param $items
     * @param $separator
     * @return array
     */
    public function fetchProductCollection($items, $moveToCart = false, $separator = '&')
    {
        $productIds = collect(explode($separator, $items));

        return $productIds->map(function ($productId) use ($moveToCart) {
            $product = $this->productRepository->find($productId);

            if ($product) {
                $formattedProduct = $this->formatProduct($product, false, [
                    'moveToCart' => $moveToCart,
                    'btnText'    => $moveToCart ? trans('shop::app.customer.account.wishlist.move-to-cart') : null,
                ]);

                return array_merge($product->toArray(), [
                    'slug'          => $product->url_key,
                    'product_image' => $formattedProduct['image'],
                    'priceHTML'     => $formattedProduct['priceHTML'],
                    'new'           => $formattedProduct['new'],
                    'addToCartHtml' => $formattedProduct['addToCartHtml'],
                    'galleryImages' => $formattedProduct['galleryImages'],
                ]);
            }
        })->toArray();
    }
}
