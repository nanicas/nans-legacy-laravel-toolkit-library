<?php

namespace Nanicas\LegacyLaravelToolkit\Repositories;

use Nanicas\LegacyLaravelToolkit\Repositories\AbstractRepository;
use Nanicas\LegacyLaravelToolkit\Models\AbstractModel;

abstract class DatabaseRepository extends AbstractRepository
{
    const PAGINATE_MAX_ROWS = 15;

    public function getTable()
    {
        return $this->getModel()->getTable();
    }

    protected function paginate($query)
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

    public function update(AbstractModel $model)
    {
        return $model->save();
    }

    public function delete(AbstractModel $model)
    {
        return $model->delete();
    }

    public function getAllBySlug(int $slug)
    {
        $rows = $this->getModel()->where([
            'slug' => $slug
        ]);

        return $rows->get();
    }

    public function getByCondition(array $condition)
    {
        $rows = $this->getModel()->where($condition);

        return $rows->get();
    }

    public function getBySlug(int $slug)
    {
        $row = $this->getModel()->where([
            'slug' => $slug
        ]);

        return ($row->count() > 0) ? $row->first() : null;
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

    public function getByIdAndSlug(int $id, int $slug)
    {
        $row = $this->getModel()->where([
            'id' => $id,
            'slug' => $slug
        ]);

        return ($row->count() > 0) ? $row->first() : null;
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
}
