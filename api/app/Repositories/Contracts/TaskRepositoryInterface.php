<?php

namespace App\Repositories\Contracts;

use App\Models\Task;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface TaskRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * Get tasks for a specific user
     *
     * @param int $userId
     * @param int|null $perPage
     * @return LengthAwarePaginator|Collection
     */
    public function getByUser(int $userId, ?int $perPage = null): LengthAwarePaginator|Collection;

    /**
     * Get tasks for a specific user and date
     *
     * @param int $userId
     * @param string $date
     * @param int|null $perPage
     * @return LengthAwarePaginator|Collection
     */
    public function getByUserAndDate(int $userId, string $date, ?int $perPage = null): LengthAwarePaginator|Collection;

    /**
     * Get tasks for a specific user within a date range
     *
     * @param int $userId
     * @param string $startDate
     * @param string $endDate
     * @param int|null $perPage
     * @return LengthAwarePaginator|Collection
     */
    public function getByUserAndDateRange(int $userId, string $startDate, string $endDate, ?int $perPage = null): LengthAwarePaginator|Collection;

    /**
     * Toggle task completion status
     *
     * @param Task $task
     * @return Task
     */
    public function toggleCompletion(Task $task): Task;

    /**
     * Get completed tasks for a user
     *
     * @param int $userId
     * @param int|null $perPage
     * @return LengthAwarePaginator|Collection
     */
    public function getCompletedByUser(int $userId, ?int $perPage = null): LengthAwarePaginator|Collection;

    /**
     * Get pending tasks for a user
     *
     * @param int $userId
     * @param int|null $perPage
     * @return LengthAwarePaginator|Collection
     */
    public function getPendingByUser(int $userId, ?int $perPage = null): LengthAwarePaginator|Collection;

    /**
     * Get tasks for a specific user by IDs
     *
     * @param int $userId
     * @param array $taskIds
     * @return Collection
     */
    public function getByUserAndIds(int $userId, array $taskIds): Collection;

    /**
     * Reorder tasks in bulk
     *
     * @param array $tasksData Array of ['id' => int, 'order' => int]
     * @return Collection
     */
    public function reorderTasks(array $tasksData): Collection;
}
