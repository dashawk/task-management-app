<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\BaseApiController;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Http\Resources\TaskCollection;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use App\Repositories\Contracts\TaskRepositoryInterface;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TaskController extends BaseApiController
{
    use AuthorizesRequests;

    protected TaskRepositoryInterface $taskRepository;

    public function __construct(TaskRepositoryInterface $taskRepository)
    {
        $this->taskRepository = $taskRepository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $perPage = (int) $request->get('per_page', 15);
            $date = $request->get('date');
            $userId = $request->user()->id;

            if ($date) {
                $tasks = $this->taskRepository->getByUserAndDate($userId, $date, $perPage);
            } else {
                $tasks = $this->taskRepository->getByUser($userId, $perPage);
            }
        } catch (\Throwable $e) {
            Log::error('Failed to retrieve tasks', ['exception' => $e]);
            return $this->errorResponse('Failed to retrieve tasks', 500);
        }

        return $this->successResponse(
            new TaskCollection($tasks),
            'Tasks retrieved successfully'
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTaskRequest $request): JsonResponse
    {
        try {
            $task = $this->taskRepository->create($request->validated());
        } catch (QueryException $e) {
            Log::error('Failed to create task (DB)', ['exception' => $e]);
            return $this->errorResponse('Failed to create task', 500);
        } catch (\Throwable $e) {
            Log::error('Failed to create task', ['exception' => $e]);
            return $this->errorResponse('Failed to create task', 500);
        }

        return $this->successResponse(
            new TaskResource($task),
            'Task created successfully',
            201
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task): JsonResponse
    {
        $this->authorize('view', $task);

        return $this->successResponse(
            new TaskResource($task),
            'Task retrieved successfully'
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTaskRequest $request, Task $task): JsonResponse
    {
        $this->authorize('update', $task);

        try {
            $updatedTask = $this->taskRepository->update($task, $request->validated());
        } catch (QueryException $e) {
            Log::error('Failed to update task (DB)', ['exception' => $e]);
            return $this->errorResponse('Failed to update task', 500);
        } catch (\Throwable $e) {
            Log::error('Failed to update task', ['exception' => $e]);
            return $this->errorResponse('Failed to update task', 500);
        }

        return $this->successResponse(
            new TaskResource($updatedTask),
            'Task updated successfully'
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task): JsonResponse
    {
        $this->authorize('delete', $task);

        try {
            $this->taskRepository->delete($task);
        } catch (\Throwable $e) {
            Log::error('Failed to delete task', ['exception' => $e]);
            return $this->errorResponse('Failed to delete task', 500);
        }

        return $this->successResponse(
            null,
            'Task deleted successfully'
        );
    }

    /**
     * Toggle task completion status.
     */
    public function toggleCompletion(Task $task): JsonResponse
    {
        $this->authorize('update', $task);

        try {
            $updatedTask = $this->taskRepository->toggleCompletion($task);
        } catch (\Throwable $e) {
            Log::error('Failed to toggle task completion', ['exception' => $e]);
            return $this->errorResponse('Failed to toggle task completion', 500);
        }

        return $this->successResponse(
            new TaskResource($updatedTask),
            'Task completion status updated successfully'
        );
    }
}
