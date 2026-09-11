<?php

namespace Webkul\Admin\Tests\Fixtures\Sections;

use Webkul\Theme\Sections\ProductCarousel;

class DealsCarousel extends ProductCarousel
{
    /**
     * Code the section is stored under.
     */
    protected string $code = 'deals_carousel';

    /**
     * Translation key of the name the editor shows.
     */
    protected ?string $title = 'admin::app.appearance.sections.edit.featured';

    /**
     * Icon class drawn on the type's tile in the editor.
     */
    protected string $icon = 'icon-sales';

    /**
     * The product filters, plus the one this theme adds.
     */
    protected function filterKeys(): array
    {
        return [
            ...parent::filterKeys(),
            ['value' => 'on_sale', 'label' => 'On Sale', 'options' => []],
        ];
    }
}
