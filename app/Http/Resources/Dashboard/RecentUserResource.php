<?php

namespace App\Http\Resources\Dashboard;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

class RecentUserResource extends JsonResource
{
    public int $id;
    public string $name;
    public string $email;
    public ?string $profile_picture;
    public Carbon $created_at;

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
            'profile_picture' => $this->profile_picture ? asset($this->profile_picture) : null,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
        ];
    }
}
