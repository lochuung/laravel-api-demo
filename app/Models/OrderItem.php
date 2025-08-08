<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $order_id
 * @property int $product_id
 * @property int $quantity
 * @property numeric $price
 * @property numeric $total
 * @property string|null $note
 * @property string|null $sku
 * @property string $product_name
 * @property string|null $product_image
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Order $order
 * @property-read Product $product
 * @method static Builder<static>|OrderItem newModelQuery()
 * @method static Builder<static>|OrderItem newQuery()
 * @method static Builder<static>|OrderItem query()
 * @method static Builder<static>|OrderItem whereCreatedAt($value)
 * @method static Builder<static>|OrderItem whereId($value)
 * @method static Builder<static>|OrderItem whereNote($value)
 * @method static Builder<static>|OrderItem whereOrderId($value)
 * @method static Builder<static>|OrderItem wherePrice($value)
 * @method static Builder<static>|OrderItem whereProductId($value)
 * @method static Builder<static>|OrderItem whereProductImage($value)
 * @method static Builder<static>|OrderItem whereProductName($value)
 * @method static Builder<static>|OrderItem whereQuantity($value)
 * @method static Builder<static>|OrderItem whereSku($value)
 * @method static Builder<static>|OrderItem whereTotal($value)
 * @method static Builder<static>|OrderItem whereUpdatedAt($value)
 * @mixin Eloquent
 */
class OrderItem extends Model
{
    //
    protected $fillable = [
        'order_id',
        'product_id',
        'quantity',
        'price',
        'total',
        'note',
        'sku',
        'product_name',
        'product_image',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'price' => 'decimal:2',
        'total' => 'decimal:2',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
