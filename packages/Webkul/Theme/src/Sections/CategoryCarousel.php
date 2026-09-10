<?php

namespace Webkul\Theme\Sections;

use Webkul\Theme\Enums\SectionTypeEnum;
use Webkul\Theme\SectionSchema;

class CategoryCarousel extends SectionType
{
    /**
     * Code the section is stored under.
     */
    protected string $code = SectionTypeEnum::CATEGORY_CAROUSEL->value;

    /**
     * Translation key of the name the editor shows.
     */
    protected ?string $title = 'admin::app.appearance.sections.create.type.category-carousel';

    /**
     * Icon class drawn on the type's tile in the editor.
     */
    protected string $icon = 'icon-folder';

    /**
     * A strip of categories, chosen by filters.
     */
    public function getFields(): array
    {
        return [
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
     * The filters the category api is queried with, which a theme's own category type may extend.
     */
    protected function filterKeys(): array
    {
        return [
            [
                'value' => 'sort',
                'label' => $this->label('sort'),
                'options' => [
                    ['value' => 'asc', 'label' => $this->label('asc')],
                    ['value' => 'desc', 'label' => $this->label('desc')],
                ],
            ],
            ['value' => 'limit', 'label' => $this->label('limit'), 'options' => $this->limitOptions()],
            [
                'value' => 'parent_id',
                'label' => $this->label('parent-id'),
                'options' => $this->categoryOptions(),
                'multiple' => true,
            ],
        ];
    }
}
