<?php

namespace Webkul\Theme;

use Webkul\Category\Repositories\CategoryRepository;
use Webkul\Theme\Models\Section;

class SectionSchema
{
    /**
     * A single line of text.
     */
    public const TEXT = 'text';

    /**
     * A longer free text value.
     */
    public const TEXTAREA = 'textarea';

    /**
     * An uploaded image, stored as a path.
     */
    public const IMAGE = 'image';

    /**
     * Source code edited in a highlighted editor.
     */
    public const CODE = 'code';

    /**
     * A whole number, such as a position or a count.
     */
    public const NUMBER = 'number';

    /**
     * A repeating list of field groups, stored as a list.
     */
    public const REPEATER = 'repeater';

    /**
     * A free key and value map, used by the carousel filters.
     */
    public const FILTERS = 'filters';

    /**
     * Create a new schema instance.
     */
    public function __construct(protected CategoryRepository $categoryRepository) {}

    /**
     * Field schema for every section type, keyed by the stored type.
     */
    public function all(): array
    {
        return [
            Section::IMAGE_CAROUSEL => $this->imageCarousel(),
            Section::PRODUCT_CAROUSEL => $this->productCarousel(),
            Section::CATEGORY_CAROUSEL => $this->categoryCarousel(),
            Section::FOOTER_LINKS => $this->footerLinks(),
            Section::STATIC_CONTENT => $this->staticContent(),
            Section::SERVICES_CONTENT => $this->servicesContent(),
        ];
    }

    /**
     * Field schema for one section type, or an empty schema for an unknown one.
     */
    public function for(string $type): array
    {
        return $this->all()[$type] ?? [];
    }

    /**
     * Keys a section type stores at the top level of its options.
     */
    public function keysFor(string $type): array
    {
        return array_column($this->for($type), 'key');
    }

    /**
     * Slides, each with an image, a heading and where it links to.
     */
    protected function imageCarousel(): array
    {
        return [
            [
                'key' => 'images',
                'type' => self::REPEATER,
                'label' => $this->label('slider'),
                'add_label' => $this->label('slider-add-btn'),
                'fields' => [
                    ['key' => 'image', 'type' => self::IMAGE, 'label' => $this->label('slider-image')],
                    ['key' => 'title', 'type' => self::TEXT, 'label' => $this->label('image-title')],
                    ['key' => 'link', 'type' => self::TEXT, 'label' => $this->label('link')],
                ],
            ],
        ];
    }

    /**
     * A titled strip of products, chosen by filters.
     */
    protected function productCarousel(): array
    {
        return [
            ['key' => 'title', 'type' => self::TEXT, 'label' => $this->label('filter-title')],
            [
                'key' => 'filters',
                'type' => self::FILTERS,
                'label' => $this->label('filters'),
                'add_label' => $this->label('add-filter-btn'),
                'keys' => [
                    [
                        'value' => 'sort',
                        'label' => $this->label('sort'),
                        'options' => product_toolbar()->getAvailableOrders()
                            ->map(fn ($order) => ['value' => $order['value'], 'label' => $order['title']])
                            ->values()
                            ->all(),
                    ],
                    ['value' => 'limit', 'label' => $this->label('limit'), 'options' => $this->limitOptions()],
                    [
                        'value' => 'category_id',
                        'label' => $this->label('category-id'),
                        'options' => $this->categoryOptions(),
                    ],
                    ['value' => 'featured', 'label' => $this->label('featured'), 'options' => $this->yesNo()],
                    ['value' => 'new', 'label' => $this->label('new'), 'options' => $this->yesNo()],
                ],
            ],
        ];
    }

    /**
     * A strip of categories, chosen by filters.
     */
    protected function categoryCarousel(): array
    {
        return [
            [
                'key' => 'filters',
                'type' => self::FILTERS,
                'label' => $this->label('filters'),
                'add_label' => $this->label('add-filter-btn'),
                'keys' => [
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
                ],
            ],
        ];
    }

    /**
     * Two columns of footer links.
     */
    protected function footerLinks(): array
    {
        return collect([1, 2])
            ->map(fn ($column) => [
                'key' => 'column_'.$column,
                'type' => self::REPEATER,
                'label' => $this->label('column').' '.$column,
                'add_label' => $this->label('add-link'),
                'fields' => [
                    ['key' => 'title', 'type' => self::TEXT, 'label' => $this->label('footer-title')],
                    ['key' => 'url', 'type' => self::TEXT, 'label' => $this->label('url')],
                ],
            ])
            ->all();
    }

    /**
     * Free markup with its own stylesheet.
     */
    protected function staticContent(): array
    {
        return [
            ['key' => 'html', 'type' => self::CODE, 'language' => 'html', 'label' => $this->label('html')],
            ['key' => 'css', 'type' => self::CODE, 'language' => 'css', 'label' => $this->label('css')],
        ];
    }

    /**
     * The icon and copy for each service promise.
     */
    protected function servicesContent(): array
    {
        return [
            [
                'key' => 'services',
                'type' => self::REPEATER,
                'label' => $this->label('services-content.services'),
                'add_label' => $this->label('services-content.add-btn'),
                'fields' => [
                    ['key' => 'service_icon', 'type' => self::TEXT, 'label' => $this->label('services-content.service-icon')],
                    ['key' => 'title', 'type' => self::TEXT, 'label' => $this->label('services-content.title')],
                    ['key' => 'description', 'type' => self::TEXTAREA, 'label' => $this->label('services-content.description')],
                ],
            ],
        ];
    }

    /**
     * Every category a filter can point at, labelled with its path so that two categories
     * sharing a name can be told apart. Roots are included, because a carousel of the
     * top level categories points at one.
     *
     * @return list<array{value: string, label: string}>
     */
    protected function categoryOptions(): array
    {
        $categories = $this->categoryRepository->all();

        $names = $categories->pluck('name', 'id');

        $parents = $categories->pluck('parent_id', 'id');

        return $categories
            ->map(function ($category) use ($names, $parents) {
                $path = [];

                for ($id = $category->id; $id && isset($names[$id]); $id = $parents[$id] ?? null) {
                    array_unshift($path, $names[$id]);
                }

                return [
                    'value' => (string) $category->id,
                    'label' => count($path) > 1 ? implode(' / ', array_slice($path, 1)) : reset($path),
                ];
            })
            ->sortBy('label')
            ->values()
            ->all();
    }

    /**
     * How many items a carousel shows.
     *
     * @return list<array{value: string, label: string}>
     */
    protected function limitOptions(): array
    {
        return collect(product_toolbar()->getAvailableLimits())
            ->map(fn ($limit) => ['value' => (string) $limit, 'label' => (string) $limit])
            ->all();
    }

    /**
     * The yes and no a boolean filter is stored as.
     */
    protected function yesNo(): array
    {
        return [
            ['value' => '0', 'label' => $this->label('no')],
            ['value' => '1', 'label' => $this->label('yes')],
        ];
    }

    /**
     * Translated label for a field, reusing the keys the existing forms already ship.
     */
    protected function label(string $key): string
    {
        return trans('admin::app.appearance.sections.edit.'.$key);
    }
}
