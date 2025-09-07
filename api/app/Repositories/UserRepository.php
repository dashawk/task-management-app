<?php

namespace App\Repositories;

use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Hash;

class UserRepository extends BaseRepository implements UserRepositoryInterface
{
    public function __construct(User $user)
    {
        parent::__construct($user);
    }

    /**
     * Find a user by email
     *
     * @param string $email
     * @return User|null
     */
    public function findByEmail(string $email): ?User
    {
        return $this->model->where('email', $email)->first();
    }

    /**
     * Create a new user
     *
     * @param array $data
     * @return User
     */
    public function create(array $data): User
    {
        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }

        return parent::create($data);
    }

    /**
     * Update a user
     *
     * @param User $user
     * @param array $data
     * @return User
     */
    public function update(\Illuminate\Database\Eloquent\Model $user, array $data): \Illuminate\Database\Eloquent\Model
    {
        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }

        return parent::update($user, $data);
    }

    /**
     * Search users by name or email
     *
     * @param string $query
     * @param int|null $perPage
     * @return LengthAwarePaginator|Collection
     */
    public function search(string $query, ?int $perPage = null): LengthAwarePaginator|Collection
    {
        $queryBuilder = $this->model->where('name', 'LIKE', "%{$query}%")
            ->orWhere('email', 'LIKE', "%{$query}%");

        if ($perPage) {
            return $queryBuilder->paginate($perPage);
        }

        return $queryBuilder->get();
    }

    /**
     * Get all users with optional pagination
     *
     * @param int|null $perPage
     * @return LengthAwarePaginator|Collection
     */
    public function all(?int $perPage = null): LengthAwarePaginator|Collection
    {
        if ($perPage) {
            return $this->model->paginate($perPage);
        }

        return $this->model->all();
    }

    /**
     * Find a user by ID
     *
     * @param int $id
     * @return User|null
     */
    public function find(int $id): ?User
    {
        return $this->model->find($id);
    }

    /**
     * Delete a user
     *
     * @param User $user
     * @return bool
     */
    public function delete(\Illuminate\Database\Eloquent\Model $user): bool
    {
        return parent::delete($user);
    }
}
