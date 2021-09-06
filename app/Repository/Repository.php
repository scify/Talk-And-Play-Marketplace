<?php

namespace App\Repository;

use Illuminate\Container\Container as App;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;

abstract class Repository implements RepositoryInterface {

    /**
     * @var App
     */
    private $app;

    /**
     * Query builder for this model
     * @var
     */
    protected $modelInstance;

    /**
     * @param App $app
     * @throws RepositoryException|BindingResolutionException
     */
    public function __construct(App $app) {
        $this->app = $app;
        $this->makeModelInstance();
    }

    /**
     * Specify Model class name
     *
     * @return string
     */
    abstract function getModelClassName();

    public function all($columns = array('*'), $orderColumn = null, $order = null, $withRelationships = []) {
        $query = $this->modelInstance;

        if ($orderColumn)
            $query = $query->orderBy($orderColumn, $order ? $order : 'asc');
        if (count($withRelationships) > 0)
            $query = $query->with($withRelationships);

        return $query->get($columns);
    }

    public function allWhere(array $whereArray, $columns = array('*'), $orderColumn = null, $order = null, $withRelationships = []) {
        $query = $this->modelInstance->where($whereArray);

        if ($orderColumn)
            $query = $query->orderBy($orderColumn, $order ? $order : 'asc');
        if (count($withRelationships) > 0)
            $query = $query->with($withRelationships);

        return $query->get($columns);
    }

    /**
     * @param int $perPage
     * @param array $columns
     * @return mixed
     */
    public function paginate($perPage = 15, $columns = array('*')) {
        return $this->modelInstance->orderBy('updated_at', 'desc')->paginate($perPage, $columns);
    }

    /**
     * @param array $data
     * @return mixed
     */
    public function create(array $data) {
        return $this->modelInstance->create($data);
    }

    /**
     * @param array $data
     * @param $id
     * @param string $attribute
     * @return mixed
     */
    public function update(array $data, $id, $attribute = "id") {
        $this->modelInstance->where($attribute, '=', $id)->update($this->onlyFillable($data));
        return $this->find($id);
    }

    protected function onlyFillable(array $items) {
        if (sizeof($this->modelInstance->getFillable()) === 0)
            return $items;

        $qualified = array();
        foreach ($items as $key => $val) {
            if (in_array($key, $this->modelInstance->getFillable()))
                $qualified[$key] = $val;
        }
        return $qualified;
    }

    /**
     * @param $id
     * @return mixed
     */
    public function delete($id) {
        return $this->modelInstance->destroy($id);
    }

    /**
     * @param $id
     * @param array $columns
     * @return mixed
     */
    public function find($id, $columns = array('*')) {
        return $this->modelInstance->findOrFail($id, $columns);
    }

    public function updateOrCreate($criteria, $data) {
        return $this->modelInstance->updateOrCreate(
            $criteria,
            $data
        );
    }

    public function updateOrCreateCaseInsensitive($criteria, $data, $caseInsensitiveColumnName = null) {
        if ($caseInsensitiveColumnName && isset($criteria[$caseInsensitiveColumnName])) {
            // should look for case-insensitive
            $val = str_replace("'", "\'", $criteria[$caseInsensitiveColumnName]);
            $model = $this->modelInstance->whereRaw("LOWER(`" . $caseInsensitiveColumnName . "`) LIKE '" .
                strtolower($val) . "'")->first();

            if ($model)
                return $this->update($data, $model->id);
            return $this->create($data);
        } else
            return $this->modelInstance->updateOrCreate(
                $criteria,
                $data
            );
    }

    public function firstOrCreate($criteria, $data) {
        return $this->modelInstance->firstOrCreate(
            $criteria,
            $data
        );
    }


    public function findBy($attribute, $value, $columns = array('*'), $caseInsensitive = false, $withRelationships = []) {
        if ($caseInsensitive)
            $query = $this->modelInstance->whereRaw("LOWER(`" . $attribute . "`) LIKE '" .
                strtolower($value) . "'");
        else
            $query = $this->modelInstance->where($attribute, '=', $value);

        if (count($withRelationships) > 0)
            $query = $query->with($withRelationships);

        $model = $query->first();

        if (!$model)
            throw new ModelNotFoundException("Model with criteria: '" . $attribute . "' equal to '" . $value . "' was not found.");
        return $model;
    }

    public function where(array $whereArray, array $columns = array('*'), $withRelationships = []) {
        return $this->modelInstance->where($whereArray)->with($withRelationships)->first($columns);
    }

    /**
     * @return Model
     * @throws RepositoryException
     * @throws BindingResolutionException
     */
    private function makeModelInstance(): Model {
        $tryToCreateModel = $this->app->make($this->getModelClassName());

        if (!$tryToCreateModel instanceof Model)
            throw new RepositoryException("Class {$this->getModelClassName()} must be an instance of Illuminate\\Database\\Eloquent\\Model");

        return $this->modelInstance = $tryToCreateModel;
    }

}
