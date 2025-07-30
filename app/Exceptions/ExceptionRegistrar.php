<?php

namespace App\Exceptions;

use App\Traits\FormatsApiResponse;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class ExceptionRegistrar
{
    use FormatsApiResponse;

    public function handle(Exceptions $exceptions): void
    {
        $exceptions->renderable(function (BadRequestException $e, Request $request) {
            return $this->apiErrorResponse($request, 400, $e->getMessage(), 'BAD_REQUEST', 'Invalid request', [
                'message' => $e->getMessage(),
            ]);
        });

        $exceptions->renderable(function (AuthenticationException $e, Request $request) {
            return $this->apiErrorResponse($request, 401, $e->getMessage(), 'UNAUTHENTICATED', 'Authentication failed');
        });

        $exceptions->renderable(function (AuthorizationException $e, Request $request) {
            return $this->apiErrorResponse($request, 403, $e->getMessage(), 'FORBIDDEN', 'Authorization failed');
        });

        $exceptions->renderable(function (ValidationException $e, Request $request) {
            return $this->apiErrorResponse($request, 422, $e->getMessage(), 'VALIDATION_FAILED', 'Validation failed', [
                'errors' => $e->errors(),
                'input' => $request->except(['password', 'password_confirmation']),
            ]);
        });

        $exceptions->renderable(function (ModelNotFoundException $e, Request $request) {
            return $this->apiErrorResponse($request, 404, $e->getMessage(), 'RESOURCE_NOT_FOUND', 'Model not found', [
                'model' => $e->getModel(),
            ]);
        });

        $exceptions->renderable(function (NotFoundHttpException $e, Request $request) {
            return $this->apiErrorResponse($request, 404, $e->getMessage(), 'ENDPOINT_NOT_FOUND', 'Route not found');
        });

        $exceptions->renderable(function (MethodNotAllowedHttpException $e, Request $request) {
            return $this->apiErrorResponse($request, 405, $e->getMessage(), 'METHOD_NOT_ALLOWED', 'Invalid method', [
                'allowed_methods' => $e->getHeaders()['Allow'] ?? null,
            ]);
        });

        $exceptions->renderable(function (Throwable $e, Request $request) {
            return $this->apiErrorResponse($request, 500, $e->getMessage(), 'INTERNAL_ERROR', 'API Error', [
                'exception' => $e,
                'debug' => $this->formatDebug($e),
            ]);
        });
    }
}
