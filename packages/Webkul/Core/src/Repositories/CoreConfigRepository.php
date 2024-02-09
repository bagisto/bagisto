<?php

namespace Webkul\Core\Repositories;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Webkul\Core\Eloquent\Repository;
use Webkul\Core\Traits\CoreConfigField;

class CoreConfigRepository extends Repository
{
    use CoreConfigField;

    /**
     * Specify model class name.
     */
    public function model(): string
    {
        return 'Webkul\Core\Contracts\CoreConfig';
    }

    /**
     * Create.
     *
     * @return \Webkul\Core\Contracts\CoreConfig
     */
    public function create(array $data)
    {
        Event::dispatch('core.configuration.save.before');

        if (
            $data['locale']
            || $data['channel']
        ) {
            $locale = $data['locale'];
            $channel = $data['channel'];

            unset($data['locale']);
            unset($data['channel']);
        }

        foreach ($data as $method => $fieldData) {
            $recursiveData = $this->recursiveArray($fieldData, $method);

            foreach ($recursiveData as $fieldName => $value) {
                $field = core()->getConfigField($fieldName);

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
                    $value = request()->file($fieldName)->store('configuration');
                }

                if (! count($coreConfigValue)) {
                    parent::create([
                        'code'         => $fieldName,
                        'value'        => $value,
                        'locale_code'  => $localeBased ? $locale : null,
                        'channel_code' => $channelBased ? $channel : null,
                    ]);
                } else {
                    foreach ($coreConfigValue as $coreConfig) {
                        if (request()->hasFile($fieldName)) {
                            Storage::delete($coreConfig['value']);
                        }

                        if (isset($value['delete'])) {
                            parent::delete($coreConfig['id']);
                        } else {
                            parent::update([
                                'code'         => $fieldName,
                                'value'        => $value,
                                'locale_code'  => $localeBased ? $locale : null,
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
     * @param  string  $searchTerm
     * @return array
     */
    public function search($items, $searchTerm, $path = [])
    {
        $results = [];

        foreach ($items as $configuration) {
            $title = trans($configuration['title'] ?? ($configuration['name'] ?? ''));

            if (
                stripos($title, $searchTerm) !== false
                && count($path)
            ) {
                if (isset($path[1])) {
                    $queryParam = $path[1]['key'];
                } else {
                    $queryParam = $configuration['key'];
                }

                $results[] = [
                    'title' => implode(' > ', [...Arr::pluck($path, 'title'), $title]),
                    'url'   => route('admin.configuration.index', Str::replace('.', '/', $queryParam)),
                ];
            }

            if (
                ! empty($configuration['children'])
                || ! empty($configuration['fields'])
            ) {
                $children = ! empty($configuration['children'])
                    ? $configuration['children']
                    : $configuration['fields'];

                $tempPath = array_merge($path, [[
                    'key'   => $configuration['key'] ?? null,
                    'title' => trans($configuration['name'] ?? ''),
                ]]);

                $results = array_merge($results, $this->search($children, $searchTerm, $tempPath));
            }
        }

        return $results;
    }

    /**
     * Recursive array.
     *
     * @param  string  $method
     * @return array
     */
    public function recursiveArray(array $formData, $method)
    {
        static $data = [];

        static $recursiveArrayData = [];

        foreach ($formData as $form => $formValue) {
            $value = $method.'.'.$form;

            if (is_array($formValue)) {
                $dim = $this->countDim($formValue);

                if ($dim > 1) {
                    $this->recursiveArray($formValue, $value);
                } elseif ($dim == 1) {
                    $data[$value] = $formValue;
                }
            }
        }

        foreach ($data as $key => $value) {
            $field = core()->getConfigField($key);

            if ($field) {
                $recursiveArrayData[$key] = $value;
            } else {
                foreach ($value as $key1 => $val) {
                    $recursiveArrayData[$key.'.'.$key1] = $val;
                }
            }
        }

        return $recursiveArrayData;
    }

    /**
     * Return dimension of the array.
     *
     * @param  array  $array
     * @return int
     */
    public function countDim($array)
    {
        if (is_array(reset($array))) {
            $return = $this->countDim(reset($array)) + 1;
        } else {
            $return = 1;
        }

        return $return;
    }
}
