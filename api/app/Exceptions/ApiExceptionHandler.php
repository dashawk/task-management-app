<?php

namespace App\Exceptions;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class ApiExceptionHandler extends ExceptionHandler
{
    /**
     * Render an exception into an HTTP response.
     */
    public function render($request, Throwable $e): Response
    {
        if ($request->expectsJson() || $request->is('api/*')) {
            return $this->handleApiException($request, $e);
        }

        return parent::render($request, $e);
    }

    /**
     * Handle API exceptions
     */
    protected function handleApiException(Request $request, Throwable $e): JsonResponse
    {
        return match (true) {
            $e instanceof ValidationException => $this->handleValidationException($e),
            $e instanceof ModelNotFoundException => $this->handleModelNotFoundException($e),
            $e instanceof NotFoundHttpException => $this->handleNotFoundHttpException($e),
            $e instanceof AuthenticationException => $this->handleAuthenticationException($e),
            $e instanceof AccessDeniedHttpException => $this->handleAccessDeniedHttpException($e),
            $e instanceof ThrottleRequestsException => $this->handleThrottleRequestsException($e),
            $e instanceof QueryException => $this->handleQueryException($e),
            default => $this->handleGenericException($e),
        };
    }

    /**
     * Handle validation exceptions
     */
    protected function handleValidationException(ValidationException $e): JsonResponse
    {
        Log::warning('Validation failed', [
            'errors' => $e->errors(),
            'exception' => $e,
        ]);

        return response()->json([
            'success' => false,
            'message' => 'Validation failed',
            'errors' => $e->errors(),
        ], 422);
    }

    /**
     * Handle model not found exceptions
     */
    protected function handleModelNotFoundException(ModelNotFoundException $e): JsonResponse
    {
        $model = class_basename($e->getModel());

        Log::warning('Model not found', [
            'model' => $model,
            'exception' => $e,
        ]);

        return response()->json([
            'success' => false,
            'message' => "{$model} not found",
        ], 404);
    }

    /**
     * Handle not found HTTP exceptions
     */
    protected function handleNotFoundHttpException(NotFoundHttpException $e): JsonResponse
    {
        Log::warning('Route/resource not found', ['exception' => $e]);

        return response()->json([
            'success' => false,
            'message' => 'Resource not found',
        ], 404);
    }

    /**
     * Handle authentication exceptions
     */
    protected function handleAuthenticationException(AuthenticationException $e): JsonResponse
    {
        Log::warning('Unauthenticated access', ['exception' => $e]);

        return response()->json([
            'success' => false,
            'message' => 'Unauthenticated',
        ], 401);
    }

    /**
     * Handle access denied exceptions
     */
    protected function handleAccessDeniedHttpException(AccessDeniedHttpException $e): JsonResponse
    {
        Log::warning('Access denied', ['exception' => $e]);

        return response()->json([
            'success' => false,
            'message' => 'Access denied',
        ], 403);
    }

    /**
     * Handle rate limit exceptions
     */
    protected function handleThrottleRequestsException(ThrottleRequestsException $e): JsonResponse
    {
        Log::warning('Too many requests', ['exception' => $e]);

        $headers = $e->getHeaders();
        $retryAfter = $headers['Retry-After'] ?? null;

        $payload = [
            'success' => false,
            'message' => 'Too Many Requests',
        ];

        if ($retryAfter !== null) {
            $payload['retry_after'] = (int) $retryAfter;
        }

        return response()->json($payload, 429, $headers);
    }

    /**
     * Handle database query exceptions
     */
    protected function handleQueryException(QueryException $e): JsonResponse
    {
        // SQLSTATE codes for integrity/unique violations
        $sqlState = $e->errorInfo[0] ?? null; // e.g., 23000 (MySQL/SQLite), 23505 (Postgres)
        $driverCode = $e->errorInfo[1] ?? null; // e.g., 1062 (MySQL), 19 (SQLite)

        Log::error('Database query exception', [
            'sql_state' => $sqlState,
            'driver_code' => $driverCode,
            'message' => $e->getMessage(),
            'exception' => $e,
        ]);

        if (in_array($sqlState, ['23000', '23505'], true) || in_array((int) $driverCode, [1062, 19], true)) {
            // Likely a unique constraint violation
            return response()->json([
                'success' => false,
                'message' => 'Resource conflict (duplicate value)',
            ], 409);
        }

        $response = [
            'success' => false,
            'message' => 'Database error',
        ];

        if (config('app.debug')) {
            $response['debug'] = [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ];
        }

        return response()->json($response, 500);
    }

    /**
     * Handle generic exceptions
     */
    protected function handleGenericException(Throwable $e): JsonResponse
    {
        $statusCode = $e instanceof HttpExceptionInterface ? $e->getStatusCode() : 500;

        Log::error('Unhandled exception', [
            'status' => $statusCode,
            'message' => $e->getMessage(),
            'exception' => $e,
        ]);

        $response = [
            'success' => false,
            'message' => 'An error occurred',
        ];

        if (config('app.debug')) {
            $response['debug'] = [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ];
        }

        return response()->json($response, $statusCode);
    }
}
