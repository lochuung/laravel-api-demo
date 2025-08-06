<?php

namespace App\Events;

use App\Models\InventoryTransaction;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class InventoryLowStock
{
    use Dispatchable, InteractsWithSockets, SerializesModels;


    private InventoryTransaction $inventoryTransaction;

    /**
     * @param InventoryTransaction $inventoryTransaction
     */
    public function __construct(InventoryTransaction $inventoryTransaction)
    {
        $this->inventoryTransaction = $inventoryTransaction;
    }
    /**
     * Create a new event instance.
     */


    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('channel-name'),
        ];
    }

    public function getInventoryTransaction(): InventoryTransaction
    {
        return $this->inventoryTransaction;
    }

    public function setInventoryTransaction(InventoryTransaction $inventoryTransaction): void
    {
        $this->inventoryTransaction = $inventoryTransaction;
    }
}
