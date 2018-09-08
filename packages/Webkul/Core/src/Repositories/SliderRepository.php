<?php

namespace Webkul\Core\Repositories;

use Webkul\Core\Eloquent\Repository;

/**
 * Slider Reposotory
 *
 * @author    Prashant Singh <prashant.singh852@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class SliderRepository extends Repository
{
    /**
     * Specify Model class name
     *
     * @return mixed
     */
    function model()
    {
        return 'Webkul\Core\Models\Slider';
    }

    /**
     * @param array $data
     * @return mixed
     */
    public function create(array $data)
    {
        $image = request()->file('image');

        $image_name = uniqid(20).'.'.$image->getClientOriginalExtension();

        $destinationPath = public_path('/vendor/webkul/shop/assets/images/slider');
        $path = $image->move($destinationPath, $image_name);
        $path= 'vendor/webkul/shop/assets/images/slider/'.$image_name;
        $data['path'] = $path;
        $this->model->create($data);
    }

}