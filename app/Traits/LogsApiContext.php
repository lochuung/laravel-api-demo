<?php

namespace App\Traits;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

trait LogsApiContext
{
    protected function getUserIdSafe(): ?int
    {
        return optional(auth()->user())->id;
    }

    protected function logContext(Request $request, array $extra = []): array
    {
        return array_merge([
            'url' => $request->fullUrl(),
            'method' => $request->method(),
            'ip' => $request->ip(),
            'user_id' => $this->getUserIdSafe(),
            'user_agent' => $request->userAgent(),
        ], $extra);
    }

    protected function resolveLogLevel(int $status): string
    {
        return $status >= 500 ? 'error' : ($status >= 400 ? 'warning' : 'info');
    }

    protected function logApiError(string $title, int $status, array $context): void
    {
        Log::{$this->resolveLogLevel($status)}($title, $context);
    }
}
