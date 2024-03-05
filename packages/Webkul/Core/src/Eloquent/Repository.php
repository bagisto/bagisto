<?php

namespace Webkul\Core\Eloquent;

use Prettus\Repository\Contracts\CacheableInterface;
use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Traits\CacheableRepository;

abstract class Repository extends BaseRepository implements CacheableInterface
{
    use CacheableRepository;

    /**
     * @var bool
     */
    protected $cacheEnabled = false;

    /**
     * @return bool
     */
    public function allowedClean()
    {
        if (! isset($this->cleanEnabled)) {
            return config('repository.cache.clean.enabled', true);
        }

        return $this->cleanEnabled;
    }

    /**
     * @return bool
     */
    protected function allowedCache($method)
    {
        $className = get_class($this);

        $cacheEnabled = config("repository.cache.repositories.{$className}.enabled", config('repository.cache.enabled', true));

        if (! $cacheEnabled) {
            return false;
        }

        $cacheOnly = isset($this->cacheOnly) ? $this->cacheOnly : config("repository.cache.repositories.{$className}.allowed.only", config('repository.cache.allowed.only', null));
        $cacheExcept = isset($this->cacheExcept) ? $this->cacheExcept : config("repository.cache.repositories.{$className}.allowed.except", config('repository.cache.allowed.only', null));

        if (is_array($cacheOnly)) {
            return in_array($method, $cacheOnly);
        }

        if (is_array($cacheExcept)) {
            return ! in_array($method, $cacheExcept);
        }

        if (is_null($cacheOnly) && is_null($cacheExcept)) {
            return true;
        }

        return false;
    }

    /**
     * @throws RepositoryException
     */
    public function resetModel()
    {
        $this->makeModel();

        return $this;
    }

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
        $this->applyCriteria();
        $this->applyScope();
        $model = $this->model->find($id, $columns);
        $this->resetModel();

        return $this->parserResult($model);
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
        $this->applyCriteria();
        $this->applyScope();
        $model = $this->model->findOrFail($id, $columns);
        $this->resetModel();

        return $this->parserResult($model);
    }

    /**
     * Count results of repository
     *
     * @param  string  $columns
     * @return int
     */
    public function count(array $where = [], $columns = '*')
    {
        $this->applyCriteria();
        $this->applyScope();

        if ($where) {
            $this->applyConditions($where);
        }

        $result = $this->model->count($columns);
        $this->resetModel();
        $this->resetScope();

        return $result;
    }

    /**
     * @param  string  $columns
     * @return mixed
     */
    public function sum($columns)
    {
        $this->applyCriteria();
        $this->applyScope();

        $sum = $this->model->sum($columns);
        $this->resetModel();

        return $sum;
    }

    /**
     * @param  string  $columns
     * @return mixed
     */
    public function avg($columns)
    {
        $this->applyCriteria();
        $this->applyScope();

        $avg = $this->model->avg($columns);
        $this->resetModel();

        return $avg;
    }

    /**
     * @return mixed
     */
    public function getModel()
    {
        return $this->model;
    }
}
