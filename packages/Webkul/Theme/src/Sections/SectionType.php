<?php

namespace Webkul\Theme\Sections;

use Illuminate\Support\Str;
use Stevebauman\Purify\Facades\Purify;
use Webkul\Category\Repositories\CategoryRepository;

abstract class SectionType
{
    /**
     * Code the section is stored under, unique among the types a theme offers.
     */
    protected string $code;

    /**
     * Translation key of the name the editor shows, or null to derive one from the code.
     */
    protected ?string $title = null;

    /**
     * Icon class drawn on the type's tile in the editor.
     */
    protected string $icon = 'icon-cms';

    /**
     * Whether a channel may hold only one section of this type.
     */
    protected bool $singleton = false;

    /**
     * Whether the section is fixed to the bottom of the page rather than dragged into place.
     */
    protected bool $pinned = false;

    /**
     * Whether the layout draws the section on every page rather than on the home page alone.
     */
    protected bool $layout = false;

    /**
     * Get the code the section is stored under.
     */
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * Get the name the editor shows for the type.
     */
    public function getTitle(): string
    {
        return $this->title
            ? trans($this->title)
            : Str::headline($this->code);
    }

    /**
     * Get the icon class drawn on the type's tile.
     */
    public function getIcon(): string
    {
        return $this->icon;
    }

    /**
     * Whether a channel may hold only one section of this type.
     */
    public function isSingleton(): bool
    {
        return $this->singleton;
    }

    /**
     * Whether the section is fixed to the bottom of the page.
     */
    public function isPinned(): bool
    {
        return $this->pinned;
    }

    /**
     * Whether the layout draws the section on every page.
     */
    public function rendersInLayout(): bool
    {
        return $this->layout;
    }

    /**
     * Get the fields the editor draws, described with the `SectionSchema` field kinds.
     */
    public function getFields(): array
    {
        return [];
    }

    /**
     * Clean the options before they are stored or rendered.
     */
    public function sanitize(array $options): array
    {
        return $options;
    }

    /**
     * Shape the stored options the way the editor's fields read them.
     */
    public function prepareForEditor(array $options): array
    {
        return $options;
    }

    /**
     * Shape the options the editor posts the way the storefront reads them.
     */
    public function prepareForStorage(array $options): array
    {
        return $options;
    }

    /**
     * Get the type as the editor lists it.
     */
    public function toArray(): array
    {
        return [
            'code' => $this->getCode(),
            'title' => $this->getTitle(),
            'icon' => $this->getIcon(),
            'is_singleton' => $this->isSingleton(),
            'is_pinned' => $this->isPinned(),
        ];
    }

    /**
     * Get the translated label of a core editor field.
     */
    protected function label(string $key): string
    {
        return trans('admin::app.appearance.sections.edit.'.$key);
    }

    /**
     * Strip what author supplied markup must not carry onto the storefront.
     */
    protected function sanitizeHtml(?string $html): string
    {
        return Purify::config([
            'HTML.Allowed' => null,
            'HTML.ForbiddenElements' => 'script,iframe,form',
            'CSS.AllowedProperties' => null,
        ])->clean((string) $html);
    }

    /**
     * Strip what would let author supplied css break out of the style block it is written into.
     */
    protected function sanitizeCss(?string $css): string
    {
        $css = str_replace("\0", '', (string) $css);

        return str_ireplace('</style', '<\/style', $css);
    }

    /**
     * Every category a filter can point at, labelled with its path so namesakes can be told apart.
     *
     * @return list<array{value: string, label: string}>
     */
    protected function categoryOptions(): array
    {
        $categories = app(CategoryRepository::class)->all();

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
     * How many items a carousel may show.
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
     *
     * @return list<array{value: string, label: string}>
     */
    protected function yesNo(): array
    {
        return [
            ['value' => '0', 'label' => $this->label('no')],
            ['value' => '1', 'label' => $this->label('yes')],
        ];
    }
}
