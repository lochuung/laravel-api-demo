<?php

namespace App\Http\Resources\Users;

use App\Http\Resources\Orders\OrderResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'role' => $this->role,
            'profile_picture' => $this->profile_picture,
            'is_active' => $this->is_active,
            'email_verified_at' => $this->email_verified_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'orders_count' => $this->orders_count ?? 0,
            'total_spent' => $this->total_spent ?? 0,
            'orders' => OrderResource::collection($this->whenLoaded('orders')),
            'address' => $this->address ?? '',
            'phone_number' => $this->phone_number ?? '',
        ];
    }
}
