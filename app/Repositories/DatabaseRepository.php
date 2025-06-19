<?php

namespace Nanicas\LegacyLaravelToolkit\Repositories;

use Nanicas\LegacyLaravelToolkit\Repositories\AbstractRepository;
use Nanicas\LegacyLaravelToolkit\Models\AbstractModel;
use Illuminate\Database\Eloquent\Model;

abstract class DatabaseRepository extends AbstractRepository
{
    const PAGINATE_MAX_ROWS = 15;

    protected object $model;

    public function getModel()
    {
        return $this->model;
    }

    public function getModelClass()
    {
        return get_class($this->model);
    }

    public function getTable()
    {
        return $this->getModel()->getTable();
    }

    protected function paginate(object $query)
    {
        return $query->paginate(static::PAGINATE_MAX_ROWS);
    }

    public function getDeletedAtColumn()
    {
        return $this->model::COLUMN_DELETED_AT;
    }

    public function store(array $data)
    {
        return $this->getModel()->create($data);
    }

    public function updateOrCreate(array $condition, array $data)
    {
        return $this->getModel()->updateOrCreate($condition, $data);
    }

    public function deleteByCondition(array $condition)
    {
        return $this->getModel()->where($condition)->delete();
    }

    public function insert(array $data)
    {
        return $this->getModel()->insert($data);
    }

    public function update(AbstractModel|Model $model)
    {
        return $model->save();
    }

    public function delete(AbstractModel|Model $model)
    {
        return $model->delete();
    }

    public function getByCondition(array $condition)
    {
        return $this->getModel()->where($condition)->get();
    }

    public function getAllActive()
    {
        return $this->getModel()->where([
            'active' => 1
        ])->get();
    }

    public function getAll()
    {
        return $this->getModel()->get();
    }

    public function getAllPaginate()
    {
        $query = $this->getModel();

        return $this->paginate($query);
    }

    public function destroy(int $id)
    {
        return $this->getModel()->destroy($id);
    }

    public function getById(int $id)
    {
        return $this->getModel()->find($id);
    }

    public function getByIds(array $ids)
    {
        return $this->getModel()->whereIn('id', $ids)->get();
    }

    public function getAllQuery()
    {
        return $this->getModel()->query();
    }

    public function exists(array $data)
    {
        $query = $this->getModel()->newQuery();
        $query = $query->where($data);

        return $query->exists();
    }

    public function filter(array $data = [])
    {
        $query = $this->getModel()->newQuery();

        if (isset($data['ids']) && is_array($data['ids'])) {
            $query = $query->whereIn($this->getModel()->getTable() . '.id', $data['ids']);
            unset($data['ids']);
        }

        if (!empty($data)) {
            $query = $query->where($data);
        }

        return $query->get();
    }

    protected function setModel(object $model): void
    {
        $this->model = $model;
    }
}
