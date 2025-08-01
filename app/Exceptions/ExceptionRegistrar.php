<?php

namespace App\Exceptions;

use App\Traits\FormatsApiResponse;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Http\Request;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class ExceptionRegistrar
{
    use FormatsApiResponse;

    public function handle(Exceptions $exceptions): void
    {
        $exceptions->renderable(function (BadRequestException $e, Request $request) {
            return $this->apiErrorResponse(
                $request,
                Response::HTTP_BAD_REQUEST,
                $e->getMessage(),
                'BAD_REQUEST',
                __('errors.bad_request'),
                ['message' => $e->getMessage()]
            );
        });

        $exceptions->renderable(function (AuthenticationException $e, Request $request) {
            return $this->apiErrorResponse(
                $request,
                Response::HTTP_UNAUTHORIZED,
                $e->getMessage(),
                'UNAUTHENTICATED',
                __('errors.unauthenticated')
            );
        });

        $exceptions->renderable(function (AuthorizationException $e, Request $request) {
            return $this->apiErrorResponse(
                $request,
                Response::HTTP_FORBIDDEN,
                $e->getMessage(),
                'FORBIDDEN',
                __('errors.forbidden')
            );
        });

        $exceptions->renderable(function (ValidationException $e, Request $request) {
            return $this->apiErrorResponse(
                $request,
                Response::HTTP_UNPROCESSABLE_ENTITY,
                $e->getMessage(),
                'VALIDATION_FAILED',
                __('errors.validation_failed'),
                [
                    'errors' => $e->errors(),
                    'input' => $request->except(['password', 'password_confirmation']),
                ]
            );
        });

        $exceptions->renderable(function (ModelNotFoundException $e, Request $request) {
            return $this->apiErrorResponse(
                $request,
                Response::HTTP_NOT_FOUND,
                $e->getMessage(),
                'RESOURCE_NOT_FOUND',
                __('errors.resource_not_found'),
                ['model' => $e->getModel()]
            );
        });

        $exceptions->renderable(function (NotFoundHttpException $e, Request $request) {
            return $this->apiErrorResponse(
                $request,
                Response::HTTP_NOT_FOUND,
                $e->getMessage(),
                'ENDPOINT_NOT_FOUND',
                __('errors.endpoint_not_found')
            );
        });

        $exceptions->renderable(function (MethodNotAllowedHttpException $e, Request $request) {
            return $this->apiErrorResponse(
                $request,
                Response::HTTP_METHOD_NOT_ALLOWED,
                $e->getMessage(),
                'METHOD_NOT_ALLOWED',
                __('errors.method_not_allowed'),
                ['allowed_methods' => $e->getHeaders()['Allow'] ?? null]
            );
        });

        // Access Denied (403)
        $exceptions->renderable(function (AccessDeniedHttpException $e, Request $request) {
            return $this->apiErrorResponse(
                $request,
                Response::HTTP_FORBIDDEN,
                $e->getMessage(),
                'FORBIDDEN',
                __('errors.forbidden')
            );
        });

        // Generic HttpException (e.g. 403, 419 from prepareException)
        $exceptions->renderable(function (HttpException $e, Request $request) {
            return $this->apiErrorResponse(
                $request,
                $e->getStatusCode(),
                $e->getMessage(),
                Response::$statusTexts[$e->getStatusCode()] ?? 'HTTP_ERROR',
                __('errors.http_error'),
                ['headers' => $e->getHeaders()]
            );
        });

        // TokenMismatchException -> 419
        $exceptions->renderable(function (TokenMismatchException $e, Request $request) {
            return $this->apiErrorResponse(
                $request,
                419,
                $e->getMessage(),
                'TOKEN_MISMATCH',
                __('errors.token_mismatch')
            );
        });

        // BadRequestHttpException
        $exceptions->renderable(function (BadRequestHttpException $e, Request $request) {
            return $this->apiErrorResponse(
                $request,
                400,
                $e->getMessage(),
                'BAD_REQUEST',
                __('errors.bad_request')
            );
        });

        // NotFoundHttpException (RecordNotFound, BackedEnumCaseNotFound, etc.)
        $exceptions->renderable(function (NotFoundHttpException $e, Request $request) {
            return $this->apiErrorResponse(
                $request,
                404,
                $e->getMessage(),
                'NOT_FOUND',
                __('errors.resource_not_found')
            );
        });

        $exceptions->renderable(function (Throwable $e, Request $request) {
            return $this->apiErrorResponse(
                $request,
                Response::HTTP_INTERNAL_SERVER_ERROR,
                __('errors.internal_error'),
                'INTERNAL_ERROR',
                __('errors.internal_error'),
                [
                    'exception' => $e,
                    'debug' => $this->formatDebug($e),
                ]
            );
        });
    }
}
