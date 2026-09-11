<?php

namespace Webkul\Theme\Enums;

use Webkul\Theme\Sections\CategoryCarousel;
use Webkul\Theme\Sections\FooterLinks;
use Webkul\Theme\Sections\ImageCarousel;
use Webkul\Theme\Sections\ProductCarousel;
use Webkul\Theme\Sections\ServicesContent;
use Webkul\Theme\Sections\StaticContent;

enum SectionTypeEnum: string
{
    /**
     * A slider of linked images.
     */
    case IMAGE_CAROUSEL = 'image_carousel';

    /**
     * A strip of products, chosen by filters.
     */
    case PRODUCT_CAROUSEL = 'product_carousel';

    /**
     * A strip of categories, chosen by filters.
     */
    case CATEGORY_CAROUSEL = 'category_carousel';

    /**
     * The link columns of the footer.
     */
    case FOOTER_LINKS = 'footer_links';

    /**
     * Author supplied markup and styles.
     */
    case STATIC_CONTENT = 'static_content';

    /**
     * The service promises drawn by the layout.
     */
    case SERVICES_CONTENT = 'services_content';

    /**
     * Get every core section type value.
     */
    public static function getValues(): array
    {
        return array_map(fn (self $case) => $case->value, self::cases());
    }

    /**
     * Get the class of every core section type, for a theme that offers them all.
     */
    public static function getClassNames(): array
    {
        return array_map(fn (self $case) => $case->getClassName(), self::cases());
    }

    /**
     * Get the class that implements the section type.
     */
    public function getClassName(): string
    {
        return match ($this) {
            self::IMAGE_CAROUSEL => ImageCarousel::class,
            self::PRODUCT_CAROUSEL => ProductCarousel::class,
            self::CATEGORY_CAROUSEL => CategoryCarousel::class,
            self::FOOTER_LINKS => FooterLinks::class,
            self::STATIC_CONTENT => StaticContent::class,
            self::SERVICES_CONTENT => ServicesContent::class,
        };
    }
}
