<?php

namespace App\Models;

use Database\Factories\InventoryTransactionFactory;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $product_id
 * @property string $type
 * @property int $quantity
 * @property numeric $price
 * @property Carbon $date
 * @property int|null $order_id
 * @property string|null $notes
 * @property int|null $unit_id
 * @property int|null $unit_quantity Quantity in the specified unit
 * @property bool $is_adjustment Whether this is an inventory adjustment
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read string $formatted_type
 * @property-read Order|null $order
 * @property-read Product $product
 * @property-read ProductUnit|null $unit
 * @method static InventoryTransactionFactory factory($count = null, $state = [])
 * @method static Builder<static>|InventoryTransaction newModelQuery()
 * @method static Builder<static>|InventoryTransaction newQuery()
 * @method static Builder<static>|InventoryTransaction query()
 * @method static Builder<static>|InventoryTransaction whereCreatedAt($value)
 * @method static Builder<static>|InventoryTransaction whereDate($value)
 * @method static Builder<static>|InventoryTransaction whereId($value)
 * @method static Builder<static>|InventoryTransaction whereIsAdjustment($value)
 * @method static Builder<static>|InventoryTransaction whereNotes($value)
 * @method static Builder<static>|InventoryTransaction whereOrderId($value)
 * @method static Builder<static>|InventoryTransaction wherePrice($value)
 * @method static Builder<static>|InventoryTransaction whereProductId($value)
 * @method static Builder<static>|InventoryTransaction whereQuantity($value)
 * @method static Builder<static>|InventoryTransaction whereType($value)
 * @method static Builder<static>|InventoryTransaction whereUnitId($value)
 * @method static Builder<static>|InventoryTransaction whereUnitQuantity($value)
 * @method static Builder<static>|InventoryTransaction whereUpdatedAt($value)
 * @mixin Eloquent
 */
class InventoryTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'type',
        'quantity',
        'price',
        'date',
        'order_id',
        'notes',
        'unit_id',
        'unit_quantity',
        'is_adjustment',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'quantity' => 'integer',
        'unit_quantity' => 'integer',
        'date' => 'datetime',
        'is_adjustment' => 'boolean',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function unit(): BelongsTo
    {
        return $this->belongsTo(ProductUnit::class, 'unit_id');
    }

    /**
     * Get formatted transaction type
     */
    public function getFormattedTypeAttribute(): string
    {
        return match ($this->type) {
            'import' => 'Import',
            'export' => 'Export',
            default => ucfirst($this->type)
        };
    }
}
