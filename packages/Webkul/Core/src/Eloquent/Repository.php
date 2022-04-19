<?php

namespace Webkul\Core\Eloquent;

use Prettus\Repository\Contracts\CacheableInterface;
use Prettus\Repository\Eloquent\BaseRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Container\Container as App;
use Prettus\Repository\Traits\CacheableRepository;


abstract class Repository extends BaseRepository implements CacheableInterface {

    use CacheableRepository;

    /**
     * Find data by field and value
     *
     * @param  string  $field
     * @param  string  $value
     * @param  array  $columns
     * @return mixed
     */
    public function findOneByField($field, $value = null, $columns = ['*'])
    {
        $model = $this->findByField($field, $value, $columns = ['*']);

        return $model->first();
    }

    /**
     * Find data by field and value
     *
     * @param  string  $field
     * @param  string  $value
     * @param  array  $columns
     * @return mixed
     */
    public function findOneWhere(array $where, $columns = ['*'])
    {
        $model = $this->findWhere($where, $columns);

        return $model->first();
    }

    /**
     * Find data by id
     *
     * @param  int  $id
     * @param  array  $columns
     * @return mixed
     */
    public function find($id, $columns = ['*'])
    {
        if (!$this->allowedCache('find') || $this->isSkippedCache()) {
            $this->applyCriteria();
            $this->applyScope();
            $model = $this->model->find($id, $columns);
            $this->resetModel();

            return $this->parserResult($model);
        }

        $key = $this->getCacheKey('find', func_get_args());
        $time = $this->getCacheTime();
        $value = $this->getCacheRepository()->remember($key, $time, function () use ($id, $columns) {
            $this->applyCriteria();
            $this->applyScope();
            $model = $this->model->find($id, $columns);
            $this->resetModel();

            return $this->parserResult($model);
        });

        $this->resetModel();
        $this->resetScope();
        return $value;
    }

    /**
     * Find data by id
     *
     * @param  int  $id
     * @param  array  $columns
     * @return mixed
     */
    public function findOrFail($id, $columns = ['*'])
    {
        if (!$this->allowedCache('find') || $this->isSkippedCache()) {
            $this->applyCriteria();
            $this->applyScope();
            $model = $this->model->findOrFail($id, $columns);
            $this->resetModel();

            return $this->parserResult($model);
        }

        $key = $this->getCacheKey('find', func_get_args());
        $time = $this->getCacheTime();
        $value = $this->getCacheRepository()->remember($key, $time, function () use ($id, $columns) {
            $this->applyCriteria();
            $this->applyScope();
            $model = $this->model->findOrFail($id, $columns);
            $this->resetModel();

            return $this->parserResult($model);
        });

        $this->resetModel();
        $this->resetScope();
        return $value;
    }

     /**
     * Count results of repository
     *
     * @param  array  $where
     * @param  string  $columns
     * @return int
     */
    public function count(array $where = [], $columns = '*')
    {
        if (!$this->allowedCache('count') || $this->isSkippedCache()) {
            return parent::count($where, $columns);
        }

        $key = $this->getCacheKey('count', func_get_args());
        $time = $this->getCacheTime();
        $value = $this->getCacheRepository()->remember($key, $time, function () use ($where, $columns) {
            return parent::count($where, $columns);
        });

        $this->resetModel();
        $this->resetScope();
        return $value;

    }

    /**
     * @param  string  $columns
     * @return mixed
     */
    public function sum($columns)
    {
        if (!$this->allowedCache('sum') || $this->isSkippedCache()) {
            $this->applyCriteria();
            $this->applyScope();

            $sum = $this->model->sum($columns);
            $this->resetModel();

            return $sum;
        }

        $key = $this->getCacheKey('sum', func_get_args());
        $time = $this->getCacheTime();
        $value = $this->getCacheRepository()->remember($key, $time, function () use ($columns) {
            $this->applyCriteria();
            $this->applyScope();

            $sum = $this->model->sum($columns);
            $this->resetModel();

            return $sum;
        });

        $this->resetModel();
        $this->resetScope();
        return $value;
    }

    /**
     * @param  string  $columns
     * @return mixed
     */
    public function avg($columns)
    {
        if (!$this->allowedCache('sum') || $this->isSkippedCache()) {
            $this->applyCriteria();
            $this->applyScope();

            $avg = $this->model->avg($columns);
            $this->resetModel();

            return $avg;
        }

        $key = $this->getCacheKey('sum', func_get_args());
        $time = $this->getCacheTime();
        $value = $this->getCacheRepository()->remember($key, $time, function () use ($columns) {
            $this->applyCriteria();
            $this->applyScope();

            $avg = $this->model->avg($columns);
            $this->resetModel();

            return $avg;
        });

        $this->resetModel();
        $this->resetScope();
        return $value;
    }

    /**
     * @return mixed
     */
    public function getModel()
    {
        return $this->model;
    }
}
