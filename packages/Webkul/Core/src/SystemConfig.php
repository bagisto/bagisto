<?php

namespace Webkul\Core;

use Webkul\Core\Repositories\CoreConfigRepository;

class SystemConfig
{
    /**
     * Create a new class instance.
     *
     * @return void
     */
    public function __construct(protected CoreConfigRepository $coreConfigRepository)
    {
        $this->coreConfigRepository = $coreConfigRepository;
    }

    /**
     * Prepare configuration items.
     */
    public function getConfigurationItems(): array
    {
        static $items;

        if ($items) {
            return $items;
        }


        collect(config('core'))->each(function ($item) use (&$items) {
            $item['children'] = [];

            $children = str_replace('.', '.children.', $item['key']);

            core()->array_set($items, $children, $item);
        });

        $items = core()->sortItems($items);

        return $items;
    }

    /**
     * Get active configuration item.
     */
    public function getActiveConfigurationItem(): ?array
    {
        if (! $slug = request()->route('slug')) {
            return null;
        }

        $activeItem = $this->getConfigurationItems()[$slug] ?? null;

        if (! $activeItem) {
            return null;
        }

        if ($slug2 = request()->route('slug2')) {
            $activeItem = $activeItem['children'][$slug2] ?? null;
        }

        return $activeItem;
    }

    /**
     * Get group of active configuration.
     */
    public function getGroupOfActiveConfiguration(): array
    {
        $activeItem = $this->getActiveConfigurationItem();

        return $activeItem['children'] ?? [];
    }

    /**
     * Get group of active configuration.
     */
    public function getNameField(?string $nameKey = null): string
    {
        if (! $nameKey) {
            return '';
        }

        return $this->coreConfigRepository->getNameField($nameKey);
    }

    /**
     * Get depend the field name.
     */
    public function getDependFieldName(array $field, array $item): string
    {
        if (empty($field['depends'])) {
            return '';
        }

        $dependNameKey = $item['key'].'.'.collect(explode(':', $field['depends']))->first();

        return $this->getNameField($dependNameKey);
    }

    /**
     * Get the validations for the field.
     */
    public function getFieldValidations(array $field): string
    {
        if (empty($field)) {
            return '';
        }

        return $this->coreConfigRepository->getValidations($field);
    }

    /**
     * Get the channel locale info.
     */
    public function getChannelLocaleInfo(array $field, string $channelCode, string $localeCode): string
    {
        if (empty($field)) {
            return '';
        }

        return $this->coreConfigRepository->getChannelLocaleInfo($field, $channelCode, $localeCode);
    }

    /**
     * Get the mapped field.
     */
    public function getMappedField(?array $field = []): array
    {
        return collect([
            ...$field,
            'isVisible' => true,
        ])->map(function ($value, $key) {
            if ($key == 'options') {
                return collect($this->coreConfigRepository->getOptions($value))->map(fn ($option) => [
                    'title' => trans($option['title']),
                    'value' => $option['value'],
                ])->toArray();
            }

            return $value;
        })->toArray();
    }
}
