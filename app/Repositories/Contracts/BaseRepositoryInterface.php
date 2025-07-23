<?php

namespace App\Repositories\Contracts;


use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

interface BaseRepositoryInterface
{
    public function all(): Collection;

    public function find(int $id): ?Model;

    public function create(array $data): Model;

    public function update(int $id, array $data): bool;

    public function delete(int $id): bool;

    public function findBy(string $field, $value): ?Model;

    public function findWhere(array $conditions): Collection;

    public function paginate(int $perPage = 15): LengthAwarePaginator;

    public function exists(array $conditions): bool;

    public function count(): int;

}
