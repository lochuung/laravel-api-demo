<?php

namespace App\Repositories;

namespace App\Repositories;

use App\Repositories\Contracts\BaseRepositoryInterface;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

abstract class BaseRepository implements BaseRepositoryInterface
{
    protected Model $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function all(): Collection
    {
        return $this->model->all();
    }

    public function find(int $id): ?Model
    {
        return $this->model->find($id);
    }

    public function create(array $data): Model
    {
        return $this->model->create($data);
    }

    /**
     * @throws Exception
     */
    public function update(int $id, array $data): Model
    {
        $record = $this->find($id);

        if (!$record) {
            throw new Exception("Record with ID {$id} not found.");
        }
        $record->fill($data);
        $record->save();
        return $record;
    }

    public function delete(int $id): bool
    {
        $record = $this->find($id);

        if (!$record) {
            return false;
        }

        return $record->delete();
    }

    public function findBy(string $field, $value): ?Model
    {
        return $this->model->where($field, $value)->first();
    }

    public function findWhere(array $conditions): Collection
    {
        $query = $this->model->newQuery();

        foreach ($conditions as $key => $condition) {
            if (is_array($condition) && is_numeric($key)) {
                // Handle ['field', 'operator', 'value'] format
                [$field, $operator, $value] = $condition;
                $query->where($field, $operator, $value);
            } elseif (is_array($condition)) {
                // Handle whereIn: 'field' => ['value1', 'value2']
                $query->whereIn($key, $condition);
            } else {
                // Simple where: 'field' => 'value'
                $query->where($key, $condition);
            }
        }

        return $query->get();
    }

    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return $this->model->paginate($perPage);
    }

    public function exists(array $conditions): bool
    {
        $query = $this->model->newQuery();

        foreach ($conditions as $field => $value) {
            $query->where($field, $value);
        }

        return $query->exists();
    }

    public function count(): int
    {
        return $this->model->count();
    }

    /**
     * Get fresh instance of model
     */
    public function getModel(): Model
    {
        return $this->model;
    }

    /**
     * Set model
     */
    public function setModel(Model $model): void
    {
        $this->model = $model;
    }

    /**
     * Get new query builder instance
     */
    public function newQuery()
    {
        return $this->model->newQuery();
    }

    /**
     * Apply complex conditions
     */
    public function whereHas(string $relation, callable $callback): Collection
    {
        return $this->model->whereHas($relation, $callback)->get();
    }

    /**
     * Get records with relations
     */
    public function with(array $relations): Collection
    {
        return $this->model->with($relations)->get();
    }

    /**
     * Order by field
     */
    public function orderBy(string $field, string $direction = 'asc'): Collection
    {
        return $this->model->orderBy($field, $direction)->get();
    }
}

