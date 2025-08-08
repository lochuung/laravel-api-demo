<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
