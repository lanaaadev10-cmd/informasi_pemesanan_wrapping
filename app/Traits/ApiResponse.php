<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;

/**
 * Trait ApiResponse
 * 
 * Standardized API response format across all API controllers.
 * Ensures consistent JSON structure for success and error responses.
 * 
 * Usage:
 *   $this->success($data, 'Message', 200);
 *   $this->error('Error message', 400);
 *   $this->paginated($items, $pagination, 'Message');
 */
trait ApiResponse
{
    /**
     * Success Response with Data
     * 
     * @param mixed $data Response data
     * @param string|null $message Optional success message
     * @param int $statusCode HTTP status code
     * @return JsonResponse
     */
    public function success($data = null, ?string $message = null, int $statusCode = 200): JsonResponse
    {
        return response()->json([
            'status' => 'success',
            'message' => $message,
            'data' => $data,
        ], $statusCode);
    }

    /**
     * Error Response
     * 
     * @param string $message Error message
     * @param int $statusCode HTTP status code
     * @param mixed $data Optional error data (e.g., validation errors)
     * @return JsonResponse
     */
    public function error(string $message, int $statusCode = 400, $data = null): JsonResponse
    {
        return response()->json([
            'status' => 'error',
            'message' => $message,
            'data' => $data,
        ], $statusCode);
    }

    /**
     * Paginated Response
     * 
     * @param mixed $items Paginated collection (Laravel Paginator instance)
     * @param string|null $message Optional message
     * @param int $statusCode HTTP status code
     * @return JsonResponse
     */
    public function paginated($items, ?string $message = null, int $statusCode = 200): JsonResponse
    {
        return response()->json([
            'status' => 'success',
            'message' => $message,
            'data' => $items->items(),
            'pagination' => [
                'current_page' => $items->currentPage(),
                'per_page' => $items->perPage(),
                'total' => $items->total(),
                'last_page' => $items->lastPage(),
            ],
        ], $statusCode);
    }

    /**
     * Unauthorized Response
     * 
     * @param string|null $message Custom message
     * @return JsonResponse
     */
    public function unauthorized(?string $message = null): JsonResponse
    {
        return $this->error(
            $message ?? 'Unauthorized. You do not have permission to access this resource.',
            401
        );
    }

    /**
     * Forbidden Response
     * 
     * @param string|null $message Custom message
     * @return JsonResponse
     */
    public function forbidden(?string $message = null): JsonResponse
    {
        return $this->error(
            $message ?? 'Forbidden. Access denied.',
            403
        );
    }

    /**
     * Not Found Response
     * 
     * @param string|null $message Custom message
     * @return JsonResponse
     */
    public function notFound(?string $message = null): JsonResponse
    {
        return $this->error(
            $message ?? 'Resource not found.',
            404
        );
    }

    /**
     * Validation Failed Response
     * 
     * @param array $errors Validation errors
     * @return JsonResponse
     */
    public function validationFailed(array $errors): JsonResponse
    {
        return response()->json([
            'status' => 'error',
            'message' => 'Validation failed.',
            'data' => ['errors' => $errors],
        ], 422);
    }
}
