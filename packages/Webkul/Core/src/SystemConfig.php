<?php

namespace Webkul\Core;

use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Webkul\Core\Repositories\CoreConfigRepository;
use Webkul\Core\SystemConfig\SystemConfigItem;

class SystemConfig
{
    /**
     * Items array.
     */
    public array $items = [];

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
     * Add Item.
     */
    public function addItem(SystemConfigItem $item): void
    {
        $this->items[] = $item;
    }

    /**
     * Get all configuration items.
     */
    public function getItems(): Collection
    {
        if (! $this->items) {
            $this->prepareConfigurationItems();
        }

        return collect($this->items)
            ->sortBy('sort');
    }

    /**
     * Prepare configuration items.
     */
    public function prepareConfigurationItems()
    {
        $configWithDotNotation = [];

        foreach (config('core') as $item) {
            $configWithDotNotation[$item['key']] = $item;
        }

        $configs = Arr::undot(Arr::dot($configWithDotNotation));

        foreach ($configs as $configItem) {
            $subConfigItems = $this->processSubConfigItems($configItem);

            $this->addItem(new SystemConfigItem(
                children: $subConfigItems,
                fields: $configItem['fields'] ?? null,
                icon: $configItem['icon'] ?? null,
                info: trans($configItem['info']) ?? null,
                key: $configItem['key'],
                name: trans($configItem['name']),
                route: $configItem['route'] ?? null,
                sort: $configItem['sort'],
            ));
        }
    }

    /**
     * Process sub config items.
     */
    private function processSubConfigItems($configItem): Collection
    {
        return collect($configItem)
            ->sortBy('sort')
            ->filter(fn ($value) => is_array($value) && isset($value['name']))
            ->map(function ($subConfigItem) {
                $configItemChildren = $this->processSubConfigItems($subConfigItem);

                return new SystemConfigItem(
                    children: $configItemChildren,
                    fields: $subConfigItem['fields'] ?? null,
                    icon: $subConfigItem['icon'] ?? null,
                    info: trans($subConfigItem['info']) ?? null,
                    key: $subConfigItem['key'],
                    name: trans($subConfigItem['name']),
                    route: $subConfigItem['route'] ?? null,
                    sort: $subConfigItem['sort'] ?? null,
                );
            });
    }

    /**
     * Get active configuration item.
     */
    public function getActiveConfigurationItem(): ?SystemConfigItem
    {
        if (! $slug = request()->route('slug')) {
            return null;
        }

        $activeItem = $this->getItems()->where('key', $slug)->first() ?? null;

        if (! $activeItem) {
            return null;
        }

        if ($slug2 = request()->route('slug2')) {
            $activeItem = $activeItem->getChildren()[$slug2];
        }

        return $activeItem;
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
    public function getDependFieldName(array $field, SystemConfigItem $item): string
    {
        if (empty($field['depends'])) {
            return '';
        }

        $dependNameKey = $item->getKey().'.'.collect(explode(':', $field['depends']))->first();

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
     * Get the config data.
     */
    public function getConfigData(string $nameKey, ?string $currentChannelCode = null, ?string $currentLocaleCode = null): ?string
    {
        return core()->getConfigData($nameKey, $currentChannelCode, $currentLocaleCode);
    }
}
