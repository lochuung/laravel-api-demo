<?php

namespace App\Models;

use Database\Factories\OrderFactory;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $user_id
 * @property int|null $customer_id
 * @property string $order_number
 * @property Carbon $order_date
 * @property string $total_amount
 * @property string $status
 * @property string|null $shipping_address
 * @property string|null $billing_address
 * @property Carbon|null $ordered_at
 * @property Carbon|null $shipped_at
 * @property Carbon|null $delivered_at
 * @property Carbon|null $canceled_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Customer|null $customer
 * @property-read Collection<int, InventoryTransaction> $inventoryTransactions
 * @property-read int|null $inventory_transactions_count
 * @property-read Collection<int, OrderItem> $items
 * @property-read int|null $items_count
 * @property-read User $user
 * @method static OrderFactory factory($count = null, $state = [])
 * @method static Builder<static>|Order newModelQuery()
 * @method static Builder<static>|Order newQuery()
 * @method static Builder<static>|Order query()
 * @method static Builder<static>|Order whereBillingAddress($value)
 * @method static Builder<static>|Order whereCanceledAt($value)
 * @method static Builder<static>|Order whereCreatedAt($value)
 * @method static Builder<static>|Order whereCustomerId($value)
 * @method static Builder<static>|Order whereDeliveredAt($value)
 * @method static Builder<static>|Order whereId($value)
 * @method static Builder<static>|Order whereOrderDate($value)
 * @method static Builder<static>|Order whereOrderNumber($value)
 * @method static Builder<static>|Order whereOrderedAt($value)
 * @method static Builder<static>|Order whereShippedAt($value)
 * @method static Builder<static>|Order whereShippingAddress($value)
 * @method static Builder<static>|Order whereStatus($value)
 * @method static Builder<static>|Order whereTotalAmount($value)
 * @method static Builder<static>|Order whereUpdatedAt($value)
 * @method static Builder<static>|Order whereUserId($value)
 * @mixin Eloquent
 */
class Order extends Model
{
    //
    use HasFactory;

    protected $fillable = [
        'user_id',
        'customer_id',
        'order_number',
        'order_date',
        'total_amount',
        'status',
        'shipping_address',
        'billing_address',
        'ordered_at',
        'shipped_at',
        'delivered_at',
        'canceled_at'
    ];

    protected $casts = [
        'order_date' => 'datetime',
        'ordered_at' => 'datetime',
        'shipped_at' => 'datetime',
        'delivered_at' => 'datetime',
        'canceled_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function inventoryTransactions(): HasMany
    {
        return $this->hasMany(InventoryTransaction::class);
    }
}
