<?php

namespace Webkul\Core\Repositories;

use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Webkul\Core\Contracts\CoreConfig;
use Webkul\Core\Eloquent\Repository;
use Webkul\Core\Traits\Sanitizer;

class CoreConfigRepository extends Repository
{
    use Sanitizer;

    /**
     * Specify model class name.
     */
    public function model(): string
    {
        return CoreConfig::class;
    }

    /**
     * Create core configuration.
     */
    public function create(array $data)
    {
        Event::dispatch('core.configuration.save.before');

        $locale = Arr::pull($data, 'locale') ?: core()->getRequestedLocaleCode();

        $channel = Arr::pull($data, 'channel') ?: core()->getRequestedChannelCode();

        foreach ($data as $group => $fieldData) {
            $configValues = $this->flattenToConfigValues($fieldData, $group);

            foreach ($configValues as $fieldName => $value) {
                $field = core()->getConfigField($fieldName);

                if (! $this->dependencyIsMet($fieldName, $field ?? [], $configValues)) {
                    continue;
                }

                $channelBased = ! empty($field['channel_based']);

                $localeBased = ! empty($field['locale_based']);

                if (
                    gettype($value) == 'array'
                    && ! isset($value['delete'])
                ) {
                    $value = implode(',', $value);
                }

                if (! empty($field['channel_based'])) {
                    if (! empty($field['locale_based'])) {
                        $coreConfigValue = $this->model
                            ->where('code', $fieldName)
                            ->where('locale_code', $locale)
                            ->where('channel_code', $channel)
                            ->get();
                    } else {
                        $coreConfigValue = $this->model
                            ->where('code', $fieldName)
                            ->where('channel_code', $channel)
                            ->get();
                    }
                } else {
                    if (! empty($field['locale_based'])) {
                        $coreConfigValue = $this->model
                            ->where('code', $fieldName)
                            ->where('locale_code', $locale)
                            ->get();
                    } else {
                        $coreConfigValue = $this->model
                            ->where('code', $fieldName)
                            ->get();
                    }
                }

                if (request()->hasFile($fieldName)) {
                    $file = request()->file($fieldName);

                    $value = $file->store('configurations');

                    $this->sanitizeSVG($value, $file->getMimeType());
                }

                if (! count($coreConfigValue)) {
                    parent::create([
                        'code' => $fieldName,
                        'value' => $value,
                        'locale_code' => $localeBased ? $locale : null,
                        'channel_code' => $channelBased ? $channel : null,
                    ]);
                } else {
                    foreach ($coreConfigValue as $coreConfig) {
                        if (request()->hasFile($fieldName)) {
                            Storage::delete($coreConfig['value']);
                        }

                        if (isset($value['delete'])) {
                            if (in_array($field['type'] ?? '', ['image', 'file'])) {
                                Storage::delete($coreConfig['value']);
                            }

                            parent::delete($coreConfig['id']);
                        } else {
                            parent::update([
                                'code' => $fieldName,
                                'value' => $value,
                                'locale_code' => $localeBased ? $locale : null,
                                'channel_code' => $channelBased ? $channel : null,
                            ], $coreConfig->id);
                        }
                    }
                }
            }
        }

        Event::dispatch('core.configuration.save.after');
    }

    /**
     * Search configuration.
     *
     * @param  array  $items
     */
    public function search(Collection $items, string $searchTerm, array $path = []): array
    {
        $results = [];

        foreach ($items as $configuration) {
            $title = $this->getTranslatedTitle($configuration);

            if (
                stripos($title, $searchTerm) !== false
                && count($path)
            ) {
                $queryParam = $path[1]['key'] ?? $configuration->getKey();

                $results[] = [
                    'title' => implode(' > ', [...Arr::pluck($path, 'title'), $title]),
                    'url' => route('admin.configuration.index', Str::replace('.', '/', $queryParam)),
                ];
            }

            $this->getChildrenAndFields($configuration, $searchTerm, $path, $results);
        }

        return $results;
    }

    /**
     * Flatten the nested array a configuration form submits into the value each setting is saved
     * under, keyed by its dotted configuration code.
     */
    public function flattenToConfigValues(array $formData, string $prefix, array &$sections = [], array &$values = []): array
    {
        foreach ($formData as $key => $value) {
            if (! is_array($value)) {
                continue;
            }

            $code = $prefix.'.'.$key;

            $depth = $this->depthOf($value);

            if ($depth > 1) {
                $this->flattenToConfigValues($value, $code, $sections, $values);
            } elseif ($depth === 1) {
                $sections[$code] = $value;
            }
        }

        foreach ($sections as $code => $fields) {
            if (core()->getConfigField($code)) {
                $values[$code] = $fields;

                continue;
            }

            foreach ($fields as $name => $value) {
                $values[$code.'.'.$name] = $value;
            }
        }

        return $values;
    }

    /**
     * How many levels of array the given array nests, counting itself as the first.
     */
    public function depthOf(array $array): int
    {
        return is_array(reset($array))
            ? $this->depthOf(reset($array)) + 1
            : 1;
    }

    /**
     * Whether a field's depend condition is met by the values being saved, leaving a save that
     * carries no value for the depended-on field to write what it does carry.
     */
    protected function dependencyIsMet(string $fieldName, array $field, array $values): bool
    {
        if (empty($field['depends'])) {
            return true;
        }

        [$name, $expected] = array_pad(explode(':', $field['depends'], 2), 2, '');

        $sibling = Str::beforeLast($fieldName, '.').'.'.$name;

        if (! array_key_exists($sibling, $values)) {
            return true;
        }

        return in_array(
            (string) $values[$sibling],
            explode(',', $expected),
            true
        );
    }

    /**
     * Get the configuration title.
     */
    protected function getTranslatedTitle(mixed $configuration): string
    {
        if (
            method_exists($configuration, 'getTitle')
            && ! is_null($configuration->getTitle())
        ) {
            return trans($configuration->getTitle());
        }

        if (
            method_exists($configuration, 'getName')
            && ! is_null($configuration->getName())
        ) {
            return trans($configuration->getName());
        }

        return '';
    }

    /**
     * Get children and fields.
     */
    protected function getChildrenAndFields(mixed $configuration, string $searchTerm, array $path, array &$results): void
    {
        if (
            method_exists($configuration, 'getChildren')
            || method_exists($configuration, 'getFields')
        ) {
            $children = $configuration->haveChildren()
                ? $configuration->getChildren()
                : $configuration->getFields();

            $tempPath = array_merge($path, [[
                'key' => $configuration->getKey() ?? null,
                'title' => $this->getTranslatedTitle($configuration),
            ]]);

            $results = array_merge($results, $this->search($children, $searchTerm, $tempPath));
        }
    }
}
