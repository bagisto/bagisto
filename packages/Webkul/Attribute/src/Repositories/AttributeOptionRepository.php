<?php

namespace Webkul\Attribute\Repositories;

use Webkul\Core\Eloquent\Repository;

/**
 * Attribute Option Reposotory
 *
 * @author    Jitendra Singh <jitendra@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class AttributeOptionRepository extends Repository
{

    /**
     * Specify Model class name
     *
     * @return mixed
     */
    function model()
    {
        return 'Webkul\Attribute\Contracts\AttributeOption';
    }

    /**
     * @param array $data
     * @return mixed
     */
    public function create(array $data)
    {
        $option = parent::create($data);

        $this->uploadSwatchImage($data, $option->id);

        return $option;
    }

    /**
     * @param array $data
     * @param $id
     * @param string $attribute
     * @return mixed
     */
    public function update(array $data, $id, $attribute = "id")
    {
        $option = parent::update($data, $id);

        $this->uploadSwatchImage($data, $id);

        return $option;
    }

    /**
     * @param array $data
     * @param mixed $optionId
     * @return mixed
     */
    public function uploadSwatchImage($data, $optionId)
    {
        if (! isset($data['swatch_value']) || ! $data['swatch_value'])
            return;

        if ($data['swatch_value'] instanceof \Illuminate\Http\UploadedFile) {
            parent::update([
                    'swatch_value' => $data['swatch_value']->store('attribute_option')
                ], $optionId);
        }
    }
}