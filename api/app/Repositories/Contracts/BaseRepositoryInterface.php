<?php

namespace App\Repositories\Contracts;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

interface BaseRepositoryInterface
{
    /**
     * Get all records with optional pagination
     *
     * @param int|null $perPage
     * @return LengthAwarePaginator|Collection
     */
    public function all(?int $perPage = null): LengthAwarePaginator|Collection;

    /**
     * Find a record by ID
     *
     * @param int $id
     * @return Model|null
     */
    public function find(int $id): ?Model;

    /**
     * Find a record by ID or fail
     *
     * @param int $id
     * @return Model
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function findOrFail(int $id): Model;

    /**
     * Create a new record
     *
     * @param array $data
     * @return Model
     */
    public function create(array $data): Model;

    /**
     * Update a record
     *
     * @param Model $model
     * @param array $data
     * @return Model
     */
    public function update(Model $model, array $data): Model;

    /**
     * Delete a record
     *
     * @param Model $model
     * @return bool
     */
    public function delete(Model $model): bool;

    /**
     * Get records with conditions
     *
     * @param array $conditions
     * @param int|null $perPage
     * @return LengthAwarePaginator|Collection
     */
    public function where(array $conditions, ?int $perPage = null): LengthAwarePaginator|Collection;
}
