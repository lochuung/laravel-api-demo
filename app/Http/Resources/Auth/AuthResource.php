<?php

namespace App\Http\Resources\Auth;

use App\Http\Resources\Users\UserResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AuthResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'user' => new UserResource($this->resource['user']) ?? null,
            'access_token' => $this->resource['access_token'] ?? null,
            'refresh_token' => $this->resource['refresh_token'] ?? null,
            'expires_in' => $this->resource['expires_in'] ?? null,
            'token_type' => $this->resource['token_type'] ?? 'Bearer',
            'message' => 'Login successful.',
            'status' => 'success',
            'code' => 200
        ];
    }
}
