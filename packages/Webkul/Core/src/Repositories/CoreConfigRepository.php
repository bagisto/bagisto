<?php

namespace Webkul\Core\Repositories;

use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Storage;
use Prettus\Repository\Traits\CacheableRepository;
use Webkul\Core\Contracts\CoreConfig;
use Webkul\Core\Eloquent\Repository;
use Webkul\Core\Traits\CoreConfigField;

class CoreConfigRepository extends Repository
{
    use CoreConfigField, CacheableRepository;

    /**
     * Specify model class name.
     *
     * @return string
     */
    public function model(): string
    {
        return CoreConfig::class;
    }

    /**
     * Create.
     *
     * @param  array  $data
     * @return \Webkul\Core\Contracts\CoreConfig
     */
    public function create(array $data)
    {
        Event::dispatch('core.configuration.save.before');

        if ($data['locale'] || $data['channel']) {
            $locale = $data['locale'];
            $channel = $data['channel'];

            unset($data['locale']);
            unset($data['channel']);
        }

        foreach ($data as $method => $fieldData) {
            $recurssiveData = $this->recuressiveArray($fieldData, $method);

            foreach ($recurssiveData as $fieldName => $value) {
                $field = core()->getConfigField($fieldName);

                $channelBased = isset($field['channel_based']) && $field['channel_based'] ? true : false;

                $localeBased = isset($field['locale_based']) && $field['locale_based'] ? true : false;

                if (getType($value) == 'array' && ! isset($value['delete'])) {
                    $value = implode(',', $value);
                }

                if (isset($field['channel_based']) && $field['channel_based']) {
                    if (isset($field['locale_based']) && $field['locale_based']) {
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
                    if (isset($field['locale_based']) && $field['locale_based']) {
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
                    $this->model->create([
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
                            $this->model->destroy($coreConfig['id']);
                        } else {
                            $coreConfig->update([
                                'code'         => $fieldName,
                                'value'        => $value,
                                'locale_code'  => $localeBased ? $locale : null,
                                'channel_code' => $channelBased ? $channel : null,
                            ]);
                        }
                    }
                }
            }
        }

        Event::dispatch('core.configuration.save.after');
    }

    /**
     * Recursive array.
     *
     * @param  array  $formData
     * @param  string  $method
     * @return array
     */
    public function recuressiveArray(array $formData, $method)
    {
        static $data = [];

        static $recuressiveArrayData = [];

        foreach ($formData as $form => $formValue) {
            $value = $method . '.' . $form;

            if (is_array($formValue)) {
                $dim = $this->countDim($formValue);

                if ($dim > 1) {
                    $this->recuressiveArray($formValue, $value);
                } elseif ($dim == 1) {
                    $data[$value] = $formValue;
                }
            }
        }

        foreach ($data as $key => $value) {
            $field = core()->getConfigField($key);

            if ($field) {
                $recuressiveArrayData[$key] = $value;
            } else {
                foreach ($value as $key1 => $val) {
                    $recuressiveArrayData[$key . '.' . $key1] = $val;
                }
            }
        }

        return $recuressiveArrayData;
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
