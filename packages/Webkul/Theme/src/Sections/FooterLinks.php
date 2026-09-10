<?php

namespace Webkul\Theme\Sections;

use Webkul\Theme\Enums\SectionTypeEnum;
use Webkul\Theme\SectionSchema;

class FooterLinks extends SectionType
{
    /**
     * Code the section is stored under.
     */
    protected string $code = SectionTypeEnum::FOOTER_LINKS->value;

    /**
     * Translation key of the name the editor shows.
     */
    protected ?string $title = 'admin::app.appearance.sections.create.type.footer-links';

    /**
     * Icon class drawn on the type's tile in the editor.
     */
    protected string $icon = 'icon-list';

    /**
     * Whether a channel may hold only one section of this type.
     */
    protected bool $singleton = true;

    /**
     * Whether the section is fixed to the bottom of the page.
     */
    protected bool $pinned = true;

    /**
     * Whether the layout draws the section on every page.
     */
    protected bool $layout = true;

    /**
     * Most columns the theme's footer lays out, or null when it takes any number.
     */
    protected ?int $maxColumns = null;

    /**
     * Get the most columns the footer lays out.
     */
    public function getMaxColumns(): ?int
    {
        return $this->maxColumns;
    }

    /**
     * Columns of links, as many as the operator adds up to the theme's limit.
     */
    public function getFields(): array
    {
        return [
            array_filter([
                'key' => 'columns',
                'type' => SectionSchema::REPEATER,
                'label' => $this->label('columns'),
                'add_label' => $this->label('add-column'),
                'max' => $this->maxColumns,
                'fields' => [
                    [
                        'key' => 'links',
                        'type' => SectionSchema::REPEATER,
                        'label' => $this->label('footer-link'),
                        'add_label' => $this->label('add-link'),
                        'fields' => [
                            ['key' => 'title', 'type' => SectionSchema::TEXT, 'label' => $this->label('footer-title')],
                            ['key' => 'url', 'type' => SectionSchema::TEXT, 'label' => $this->label('url')],
                        ],
                    ],
                ],
            ], fn ($value) => ! is_null($value)),
        ];
    }

    /**
     * Read the numbered `column_N` keys the storefront renders as a list of columns.
     */
    public function prepareForEditor(array $options): array
    {
        if (array_key_exists('columns', $options)) {
            return $options;
        }

        return [
            'columns' => collect($this->storedColumns($options))
                ->map(fn ($links) => ['links' => array_values((array) $links)])
                ->values()
                ->all(),
        ];
    }

    /**
     * Write the edited columns back as the numbered `column_N` keys the storefront renders.
     */
    public function prepareForStorage(array $options): array
    {
        if (! array_key_exists('columns', $options)) {
            return $options;
        }

        return collect((array) $options['columns'])
            ->values()
            ->when($this->maxColumns, fn ($columns) => $columns->take($this->maxColumns))
            ->mapWithKeys(fn ($column, $index) => [
                'column_'.($index + 1) => array_values((array) ($column['links'] ?? [])),
            ])
            ->all();
    }

    /**
     * The stored columns, in their numbered order.
     */
    protected function storedColumns(array $options): array
    {
        $columns = array_filter(
            $options,
            fn ($key) => (bool) preg_match('/^column_\d+$/', (string) $key),
            ARRAY_FILTER_USE_KEY
        );

        uksort($columns, 'strnatcmp');

        return $columns;
    }
}
