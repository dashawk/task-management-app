<?php

namespace App\Repositories\Contracts;

use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface UserRepositoryInterface
{
    /**
     * Get all users with optional pagination
     *
     * @param int|null $perPage
     * @return LengthAwarePaginator|Collection
     */
    public function all(?int $perPage = null): LengthAwarePaginator|Collection;

    /**
     * Find a user by ID
     *
     * @param int $id
     * @return User|null
     */
    public function find(int $id): ?User;

    /**
     * Find a user by email
     *
     * @param string $email
     * @return User|null
     */
    public function findByEmail(string $email): ?User;

    /**
     * Create a new user
     *
     * @param array $data
     * @return User
     */
    public function create(array $data): User;

    /**
     * Update a user
     *
     * @param User $user
     * @param array $data
     * @return User
     */
    public function update(\Illuminate\Database\Eloquent\Model $user, array $data): \Illuminate\Database\Eloquent\Model;

    /**
     * Delete a user
     *
     * @param User $user
     * @return bool
     */
    public function delete(\Illuminate\Database\Eloquent\Model $user): bool;

    /**
     * Search users by name or email
     *
     * @param string $query
     * @param int|null $perPage
     * @return LengthAwarePaginator|Collection
     */
    public function search(string $query, ?int $perPage = null): LengthAwarePaginator|Collection;
}
