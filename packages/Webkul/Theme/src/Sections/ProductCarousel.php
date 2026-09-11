<?php

namespace Webkul\Theme\Sections;

use Webkul\Theme\Enums\SectionTypeEnum;
use Webkul\Theme\SectionSchema;

class ProductCarousel extends SectionType
{
    /**
     * Code the section is stored under.
     */
    protected string $code = SectionTypeEnum::PRODUCT_CAROUSEL->value;

    /**
     * Translation key of the name the editor shows.
     */
    protected ?string $title = 'admin::app.appearance.sections.create.type.product-carousel';

    /**
     * Icon class drawn on the type's tile in the editor.
     */
    protected string $icon = 'icon-product';

    /**
     * A titled strip of products, chosen by filters.
     */
    public function getFields(): array
    {
        return [
            ['key' => 'title', 'type' => SectionSchema::TEXT, 'label' => $this->label('filter-title')],
            [
                'key' => 'filters',
                'type' => SectionSchema::FILTERS,
                'label' => $this->label('filters'),
                'add_label' => $this->label('add-filter-btn'),
                'keys' => $this->filterKeys(),
            ],
        ];
    }

    /**
     * The filters the product api is queried with, which a theme's own product type may extend.
     */
    protected function filterKeys(): array
    {
        return [
            [
                'value' => 'sort',
                'label' => $this->label('sort'),
                'options' => product_toolbar()->getAvailableOrders()
                    ->map(fn ($order) => ['value' => $order['value'], 'label' => $order['title']])
                    ->values()
                    ->all(),
            ],
            ['value' => 'limit', 'label' => $this->label('limit'), 'options' => $this->limitOptions()],
            ['value' => 'category_id', 'label' => $this->label('category-id'), 'options' => $this->categoryOptions()],
            ['value' => 'featured', 'label' => $this->label('featured'), 'options' => $this->yesNo()],
            ['value' => 'new', 'label' => $this->label('new'), 'options' => $this->yesNo()],
        ];
    }
}
