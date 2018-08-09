<?php 

namespace Webkul\Core\Eloquent;

use Webkul\Core\Contracts\RepositoryInterface;
use Webkul\Core\Exceptions\RepositoryException;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Container\Container as App;

/**
 * Reposotory
 *
 * @author    Jitendra Singh <jitendra@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
abstract class Repository implements RepositoryInterface {

    /**
     * @var App
     */
    private $app;

    /**
     * @var \Illuminate\Database\Eloquent\Model
     */
    protected $model;

    /**
     * @param App $app
     * @throws \Webkul\Core\Exceptions\RepositoryException
     */
    public function __construct(App $app)
    {
        $this->app = $app;

        $this->makeModel();
    }

    /**
     * Specify Model class name
     *
     * @return mixed
     */
    public abstract function model();

    /**
     * @param array $columns
     * @return mixed
     */
    public function all($columns = ['*'], $with = [])
    {
        return $this->resetScope()->model->with($with)->get($columns);
    }
 
    /**
     * @param int $perPage
     * @param array $columns
     * @return mixed
     */
    public function paginate($perPage = 1, $columns = ['*'])
    {
        return $this->resetScope()->model->paginate($perPage, $columns);
    }

    /**
     * @param array $data
     * @return mixed
     */
    public function create(array $data)
    {
        return $this->resetScope()->model->create($data);
    }

    /**
     * @param array $data
     * @param $id
     * @param string $attribute
     * @return mixed
     */
    public function update(array $data, $id, $attribute = "id")
    {
        return $this->resetScope()->model->where($attribute, '=', $id)->first()->update($data);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function delete($id)
    {
        return $this->resetScope()->find($id)->delete();
    }

    /**
     * @param $id
     * @param array $columns->with($with)
     * @return mixed
     */
    public function find($id, $columns = ['*'], $with = [])
    {
        return $this->resetScope()->model->with($with)->find($id, $columns);
    }

    /**
     * @param $id
     * @param array $columns
     * @return mixed
     */
    public function findOrFail($id, $columns = ['*'], $with = [])
    {
        return $this->resetScope()->model->with($with)->findOrFail($id, $columns);
    }

    /**
     * @param $attribute
     * @param $value
     * @param array $columns
     * @return mixed
     */
    public function findBy($attribute, $value, $columns = ['*'], $with = [])
    {
        return $this->resetScope()->model->with($with)->where($attribute, '=', $value)->first($columns);
    }

    /**
     * @param $conditions
     * @param array $columns
     * @return mixed
     */
    public function findWhere($conditions, $columns = ['*'], $with = [])
    {
        $model = $this->resetScope()->model;

        foreach ($conditions as $column => $value) {
            if(is_array($value)) {
                list($column, $condition, $val) = $value;
                $this->model = $this->model->where($column, $condition, $val);
            } else {
                $model->where($column, $value);
            }

        }

        return $model->with($with)->get($columns);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Builder
     * @throws RepositoryException
     */
    public function makeModel()
    {
        $model = $this->app->make($this->model());

        if (!$model instanceof Model)
            throw new RepositoryException("Class {$this->model()} must be an instance of Illuminate\\Database\\Eloquent\\Model");

        return $this->model = $model->newQuery();
    }
    
    /**
     * @return $this
     */
    public function resetScope() {
        $this->makeModel();

        return $this;
    }
}