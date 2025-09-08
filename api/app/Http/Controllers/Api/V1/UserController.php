<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\BaseApiController;
use App\Http\Requests\User\StoreUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Http\Resources\UserCollection;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class UserController extends BaseApiController
{
    use AuthorizesRequests;

    protected UserRepositoryInterface $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $perPage = (int) $request->get('per_page', 15);
            $search = $request->get('search');

            if ($search) {
                $users = $this->userRepository->search($search, $perPage);
            } else {
                $users = $this->userRepository->all($perPage);
            }
        } catch (\Throwable $e) {
            Log::error('Failed to retrieve users', ['exception' => $e]);
            return $this->errorResponse('Failed to retrieve users', 500);
        }

        return $this->successResponse(
            new UserCollection($users),
            'Users retrieved successfully'
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request): JsonResponse
    {
        try {
            $user = $this->userRepository->create($request->validated());
        } catch (QueryException $e) {
            $sqlState = $e->errorInfo[0] ?? null;
            $driverCode = $e->errorInfo[1] ?? null;
            if (in_array($sqlState, ['23000', '23505'], true) || in_array((int) $driverCode, [1062, 19], true)) {
                return $this->errorResponse('The email has already been taken.', 409);
            }
            Log::error('Failed to create user (DB)', ['exception' => $e]);
            return $this->errorResponse('Failed to create user', 500);
        } catch (\Throwable $e) {
            Log::error('Failed to create user', ['exception' => $e]);
            return $this->errorResponse('Failed to create user', 500);
        }

        return $this->successResponse(
            new UserResource($user),
            'User created successfully',
            201
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user): JsonResponse
    {
        $this->authorize('view', $user);

        return $this->successResponse(
            new UserResource($user),
            'User retrieved successfully'
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, User $user): JsonResponse
    {
        try {
            $updatedUser = $this->userRepository->update($user, $request->validated());
        } catch (QueryException $e) {
            $sqlState = $e->errorInfo[0] ?? null;
            $driverCode = $e->errorInfo[1] ?? null;
            if (in_array($sqlState, ['23000', '23505'], true) || in_array((int) $driverCode, [1062, 19], true)) {
                return $this->errorResponse('The email has already been taken.', 409);
            }
            Log::error('Failed to update user (DB)', ['exception' => $e]);
            return $this->errorResponse('Failed to update user', 500);
        } catch (\Throwable $e) {
            Log::error('Failed to update user', ['exception' => $e]);
            return $this->errorResponse('Failed to update user', 500);
        }

        return $this->successResponse(
            new UserResource($updatedUser),
            'User updated successfully'
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user): JsonResponse
    {
        $this->authorize('delete', $user);

        try {
            $this->userRepository->delete($user);
        } catch (\Throwable $e) {
            Log::error('Failed to delete user', ['exception' => $e]);
            return $this->errorResponse('Failed to delete user', 500);
        }

        return $this->successResponse(
            null,
            'User deleted successfully'
        );
    }
}
