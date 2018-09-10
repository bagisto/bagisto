<?php

namespace Webkul\Core\Eloquent;

use Prettus\Repository\Eloquent\BaseRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Container\Container as App;

/**
 * Reposotory
 *
 * @author    Jitendra Singh <jitendra@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
abstract class Repository extends BaseRepository {

    /**
     * Find data by field and value
     *
     * @param       $field
     * @param       $value
     * @param array $columns
     *
     * @return mixed
     */
    public function findOneByField($field, $value = null, $columns = ['*'])
    {
        $model = parent::findByField($field, $value, $columns = ['*']);
        
        return $model->first();
    }

    /**
     * Find data by field and value
     *
     * @param       $field
     * @param       $value
     * @param array $columns
     *
     * @return mixed
     */
    public function findOneWhere(array $where, $columns = ['*'])
    {
        $model = parent::findWhere($where, $columns);
        
        return $model->first();
    }

    /**
     * @return mixed
     */
    public function getModel()
    {
        return $this->model;
    }
}