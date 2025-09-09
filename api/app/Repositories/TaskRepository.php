<?php

namespace App\Repositories;

use App\Models\Task;
use App\Repositories\Contracts\TaskRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class TaskRepository extends BaseRepository implements TaskRepositoryInterface
{
    public function __construct(Task $task)
    {
        parent::__construct($task);
    }

    /**
     * Get tasks for a specific user
     *
     * @param int $userId
     * @param int|null $perPage
     * @return LengthAwarePaginator|Collection
     */
    public function getByUser(int $userId, ?int $perPage = null): LengthAwarePaginator|Collection
    {
        $query = $this->model->where('user_id', $userId)
            ->orderBy('due_date', 'asc')
            ->orderBy('created_at', 'desc');

        if ($perPage) {
            return $query->paginate($perPage);
        }

        return $query->get();
    }

    /**
     * Get tasks for a specific user and date
     *
     * @param int $userId
     * @param string $date
     * @param int|null $perPage
     * @return LengthAwarePaginator|Collection
     */
    public function getByUserAndDate(int $userId, string $date, ?int $perPage = null): LengthAwarePaginator|Collection
    {
        $query = $this->model->where('user_id', $userId)
            ->whereDate('due_date', $date)
            ->orderBy('created_at', 'desc');

        if ($perPage) {
            return $query->paginate($perPage);
        }

        return $query->get();
    }

    /**
     * Get tasks for a specific user within a date range
     *
     * @param int $userId
     * @param string $startDate
     * @param string $endDate
     * @param int|null $perPage
     * @return LengthAwarePaginator|Collection
     */
    public function getByUserAndDateRange(int $userId, string $startDate, string $endDate, ?int $perPage = null): LengthAwarePaginator|Collection
    {
        $query = $this->model->where('user_id', $userId)
            ->whereBetween('due_date', [$startDate, $endDate])
            ->orderBy('due_date', 'asc')
            ->orderBy('created_at', 'desc');

        if ($perPage) {
            return $query->paginate($perPage);
        }

        return $query->get();
    }

    /**
     * Toggle task completion status
     *
     * @param Task $task
     * @return Task
     */
    public function toggleCompletion(Task $task): Task
    {
        $task->completed = !$task->completed;
        $task->save();

        return $task;
    }

    /**
     * Get completed tasks for a user
     *
     * @param int $userId
     * @param int|null $perPage
     * @return LengthAwarePaginator|Collection
     */
    public function getCompletedByUser(int $userId, ?int $perPage = null): LengthAwarePaginator|Collection
    {
        $query = $this->model->where('user_id', $userId)
            ->where('completed', true)
            ->orderBy('updated_at', 'desc');

        if ($perPage) {
            return $query->paginate($perPage);
        }

        return $query->get();
    }

    /**
     * Get pending tasks for a user
     *
     * @param int $userId
     * @param int|null $perPage
     * @return LengthAwarePaginator|Collection
     */
    public function getPendingByUser(int $userId, ?int $perPage = null): LengthAwarePaginator|Collection
    {
        $query = $this->model->where('user_id', $userId)
            ->where('completed', false)
            ->orderBy('due_date', 'asc')
            ->orderBy('created_at', 'desc');

        if ($perPage) {
            return $query->paginate($perPage);
        }

        return $query->get();
    }
}
