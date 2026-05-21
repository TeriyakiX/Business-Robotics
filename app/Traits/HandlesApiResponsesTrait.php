<?php

declare(strict_types=1);

namespace App\Traits;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;
use Throwable;

trait HandlesApiResponsesTrait
{
    protected function executeAction(Closure $action, string $successMessageKey, int $successStatus = Response::HTTP_OK): JsonResponse
    {
        try {
            $data = $action();

            return response()->json([
                'success' => true,
                'message' => __("responses.{$successMessageKey}"),
                'data' => $data,
            ], $successStatus);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Ошибка валидации',
                'errors' => $e->errors(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch (Throwable $e) {
            $statusCode = $e->getCode() >= 100 && $e->getCode() < 600 ? $e->getCode() : Response::HTTP_INTERNAL_SERVER_ERROR;

            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], $statusCode);
        }
    }

    protected function executeVoidAction(Closure $action, string $successMessageKey, int $successStatus = Response::HTTP_OK): JsonResponse
    {
        try {
            $action();

            return response()->json([
                'success' => true,
                'message' => __("responses.{$successMessageKey}"),
            ], $successStatus);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Ошибка валидации',
                'errors' => $e->errors(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch (Throwable $e) {
            $statusCode = $e->getCode() >= 100 && $e->getCode() < 600 ? $e->getCode() : Response::HTTP_INTERNAL_SERVER_ERROR;

            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], $statusCode);
        }
    }
}
