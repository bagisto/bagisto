<?php

namespace Webkul\Core\Repositories;

use Webkul\Core\Eloquent\Repository;

/**
 * Core Config Reposotory
 *
 * @author    Jitendra Singh <jitendra@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class CoreConfigRepository extends Repository
{
    /**
     * Specify Model class name
     *
     * @return mixed
     */
    function model()
    {
        return 'Webkul\Core\Models\CoreConfig';
    }

    /**
     * @param array $data
     * @return mixed
     */
    public function create(array $data)
    {
        unset($data['_token']);

        if ($data['locale'] || $data['channel'])
        {
           $locale = $data['locale'];
           $channel = $data['channel'];
           unset($data['locale']);
           unset($data['channel']);
        }

        foreach ($data as $method => $value)
        {
            foreach ($value as $key => $formData)
            {
                foreach (array_keys($formData) as $title)
                {
                    $fieldName = $method . '.' . $key . '.' .$title;
                    $value = $formData[$title];
                    $field = core()->getConfigField($fieldName);

                    $channel_based = false;
                    $locale_based = false;

                    if (isset($field['channel_based']) && $field['channel_based']) {
                        $channel_based = true;
                    }

                    if (isset($field['locale_based']) && $field['locale_based']) {
                        $locale_based = true;
                    }

                    if (isset($field['channel_based']) && $field['channel_based'])
                    {
                        if (isset($field['locale_based']) && $field['locale_based'])
                        {
                            $coreConfigValue = $this->model
                                ->where('code', $fieldName)
                                ->where('locale_code', $locale)
                                ->where('channel_code', $channel)
                                ->get();
                        }
                        else
                        {
                            $coreConfigValue = $this->model
                                ->where('code', $fieldName)
                                ->where('channel_code', $channel)
                                ->get();
                        }
                    } else
                    {
                        if (isset($field['locale_based']) && $field['locale_based'])
                        {
                            $coreConfigValue = $this->model
                                ->where('code', $fieldName)
                                ->where('locale_code', $locale)
                                ->get();
                        }
                        else
                        {
                            $coreConfigValue = $this->model
                                ->where('code', $fieldName)
                                ->get();
                        }
                    }

                    if (!count($coreConfigValue) > 0)
                    {
                        $this->model->create([
                            'code' => $fieldName,
                            'value' => $value,
                            'locale_code' => $locale_based ? $locale : null,
                            'channel_code' => $channel_based ? $channel : null
                        ]);
                    } else
                    {
                        $updataData['code'] = $fieldName;
                        $updataData['value'] = $value;
                        $updataData['locale_code'] = $locale_based ? $locale : null;
                        $updataData['channel_code'] = $channel_based ? $channel : null;

                        foreach ($coreConfigValue as $coreConfig)
                        {
                            $coreConfig->update($updataData);
                        }
                    }
                }
            }
        }
    }
}