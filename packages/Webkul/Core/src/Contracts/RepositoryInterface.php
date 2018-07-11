<?php 

namespace Webkul\Core\Contracts;

/**
 * Reposotory Interface
 *
 * @author    Jitendra Singh <jitendra@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
interface RepositoryInterface {

    public function all($columns = ['*']);
 
    public function paginate($perPage = 15, $columns = ['*']);
 
    public function create(array $data);
 
    public function update(array $data, $id);
 
    public function delete($id);
 
    public function find($id, $columns = ['*']);
 
    public function findBy($field, $value, $columns = ['*']);

}