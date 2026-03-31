<?php

namespace App\Traits\Helpers;

use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

trait ResponseApiFunctions
{
    /**
     * Success Response.
     */
    public function successResponse(mixed $data, int $status = Response::HTTP_OK): JsonResponse
    {
        return new JsonResponse(data: $data, status: $status);
    }

    /**
     * Error Response.
     */
    public function errorResponse(
        mixed $data,
        string $message = '',
        int $status = Response::HTTP_INTERNAL_SERVER_ERROR
    ): JsonResponse {
        if (!$message) {
            $message = Response::$statusTexts[$status];
        }

        $data = [
            'message' => $message,
            'errors' => $data,
        ];

        return new JsonResponse(data: $data, status: $status);
    }

    /**
     * Error Response.
     */
    public function errorWithoutMessageResponse(
        mixed $data,
        int $status = Response::HTTP_INTERNAL_SERVER_ERROR
    ): JsonResponse {
        return new JsonResponse(data: $data, status: $status);
    }

    /**
     * Response with status code 200.
     */
    public function okResponse(mixed $data): JsonResponse
    {
        return $this->successResponse(data: $data);
    }

    /**
     * Response with status code 201.
     */
    public function createdResponse(mixed $data): JsonResponse
    {
        return $this->successResponse(data: $data, status: Response::HTTP_CREATED);
    }

    /**
     * Response with status code 204.
     */
    public function noContentResponse(): JsonResponse
    {
        return $this->successResponse(data: [], status: Response::HTTP_NO_CONTENT);
    }

    /**
     * Response with status code 400.
     */
    public function badRequestResponse(mixed $data, string $message = ''): JsonResponse
    {
        return $this->errorResponse(
            data: $data,
            message: $message,
            status: Response::HTTP_BAD_REQUEST
        );
    }

    /**
     * Response with status code 401.
     */
    public function unauthorizedResponse(mixed $data, string $message = ''): JsonResponse
    {
        return $this->errorResponse(
            data: $data,
            message: $message,
            status: Response::HTTP_UNAUTHORIZED
        );
    }

    /**
     * Response with status code 401.
     */
    public function unauthorizedWithoutDataResponse(): JsonResponse
    {
        return $this->errorWithoutMessageResponse(
            data: ['message' => Response::$statusTexts[Response::HTTP_UNAUTHORIZED]],
            status: Response::HTTP_UNAUTHORIZED
        );
    }

    /**
     * Response with status code 403.
     */
    public function forbiddenResponse(mixed $data, string $message = ''): JsonResponse
    {
        return $this->errorResponse(
            data: $data,
            message: $message,
            status: Response::HTTP_FORBIDDEN
        );
    }

    /**
     * Response with status code 404.
     */
    public function notFoundResponse(mixed $data, string $message = ''): JsonResponse
    {
        return $this->errorResponse(
            data: $data,
            message: $message,
            status: Response::HTTP_NOT_FOUND
        );
    }

    /**
     * Response with status code 404 by attribute.
     */
    public function notFoundWithAttributeResponse(string $attribute = '', string $message = ''): JsonResponse
    {
        $attribute = $attribute ?: array_key_first(request()->route()->parameters) ?? array_key_first(request()->all());

        $data = [$attribute => __('validation.exists', ['attribute' => $attribute])];

        return $this->errorResponse(
            data: $data,
            message: $message,
            status: Response::HTTP_NOT_FOUND
        );
    }

    /**
     * Response with status code 409.
     */
    public function conflictResponse(mixed $data, string $message = ''): JsonResponse
    {
        return $this->errorResponse(
            data: $data,
            message: $message,
            status: Response::HTTP_CONFLICT
        );
    }

    /**
     * Response with status code 422.
     */
    public function unprocessableResponse(mixed $data, string $message = ''): JsonResponse
    {
        return $this->errorResponse(
            data: $data,
            message: $message,
            status: Response::HTTP_UNPROCESSABLE_ENTITY
        );
    }

    /**
     * Response with status code 400.
     */
    public function badRequestWithoutDataResponse(): JsonResponse
    {
        return $this->errorWithoutMessageResponse(
            data: ['message' => Response::$statusTexts[Response::HTTP_BAD_REQUEST]],
            status: Response::HTTP_BAD_REQUEST
        );
    }

    /**
     * Response with status code 403.
     */
    public function forbiddenWithoutDataResponse(): JsonResponse
    {
        return $this->errorWithoutMessageResponse(
            data: ['message' => Response::$statusTexts[Response::HTTP_FORBIDDEN]],
            status: Response::HTTP_FORBIDDEN
        );
    }
}
