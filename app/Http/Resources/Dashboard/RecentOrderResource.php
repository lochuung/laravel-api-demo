<?php

namespace App\Http\Resources\Dashboard;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

class RecentOrderResource extends JsonResource
{
    public int $id;
    public string $order_number;
    public float $total_amount;
    public ?string $status;
    public ?Carbon $created_at;
    public ?User $user;

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'order_number' => $this->order_number,
            'total' => $this->total_amount,
            'user_name' => optional($this->user)->name,
            'status' => $this->status,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
        ];
    }
}
