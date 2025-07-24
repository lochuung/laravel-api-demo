<?php

namespace App\Traits;

use Throwable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

trait FormatsApiResponse
{
    use LogsApiContext;

    protected function apiSuccessResponse(
        array  $data = [],
        string $message = 'Success',
        int    $status = 200
    ): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data,
        ], $status);
    }

    protected function apiErrorResponse(
        Request $request,
        int     $status,
        string  $message,
        string  $errorCode,
        string  $logTitle = 'API Exception',
        array   $extra = []
    ): ?JsonResponse
    {
        if (!$this->isApi($request)) {
            return null;
        }

        $context = $this->logContext($request, $extra);
        $this->logApiError($logTitle, $status, $context);

        $response = [
            'success' => false,
            'message' => $message,
            'error_code' => $errorCode,
        ];

        if (isset($extra['errors'])) {
            $response['errors'] = $extra['errors'];
        }

        if (isset($extra['debug']) && config('app.debug')) {
            $response['debug'] = $extra['debug'];
        }

        return response()->json($response, $status);
    }

    protected function formatDebug(Throwable $e): array
    {
        return [
            'message' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'trace' => explode("\n", $e->getTraceAsString()),
        ];
    }

    protected function isApi(Request $request): bool
    {
        return $request->expectsJson() || $request->is('api/*');
    }
}
