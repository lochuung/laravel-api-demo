<?php

namespace App\Models;

use Database\Factories\ProductFactory;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int|null $user_id
 * @property string $name
 * @property string $base_sku
 * @property int|null $base_unit_id
 * @property string $base_unit
 * @property int|null $category_id
 * @property string|null $description
 * @property numeric $price
 * @property numeric $cost
 * @property numeric $stock
 * @property int $min_stock
 * @property string|null $base_barcode
 * @property Carbon|null $expiry_date
 * @property string|null $image
 * @property bool $is_active
 * @property string $status
 * @property int $is_featured
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property string|null $deleted_at_marker
 * @property-read Collection<int, ProductUnit> $baseUnit
 * @property-read int|null $base_unit_count
 * @property-read Category|null $category
 * @property-read Collection<int, InventoryTransaction> $inventoryTransactions
 * @property-read int|null $inventory_transactions_count
 * @property-read Collection<int, OrderItem> $orderItems
 * @property-read int|null $order_items_count
 * @property-read User|null $owner
 * @property-read Collection<int, ProductUnit> $units
 * @property-read int|null $units_count
 * @method static ProductFactory factory($count = null, $state = [])
 * @method static Builder<static>|Product newModelQuery()
 * @method static Builder<static>|Product newQuery()
 * @method static Builder<static>|Product onlyTrashed()
 * @method static Builder<static>|Product query()
 * @method static Builder<static>|Product whereBaseBarcode($value)
 * @method static Builder<static>|Product whereBaseSku($value)
 * @method static Builder<static>|Product whereBaseUnit($value)
 * @method static Builder<static>|Product whereBaseUnitId($value)
 * @method static Builder<static>|Product whereCategoryId($value)
 * @method static Builder<static>|Product whereCost($value)
 * @method static Builder<static>|Product whereCreatedAt($value)
 * @method static Builder<static>|Product whereDeletedAt($value)
 * @method static Builder<static>|Product whereDeletedAtMarker($value)
 * @method static Builder<static>|Product whereDescription($value)
 * @method static Builder<static>|Product whereExpiryDate($value)
 * @method static Builder<static>|Product whereId($value)
 * @method static Builder<static>|Product whereImage($value)
 * @method static Builder<static>|Product whereIsActive($value)
 * @method static Builder<static>|Product whereIsFeatured($value)
 * @method static Builder<static>|Product whereMinStock($value)
 * @method static Builder<static>|Product whereName($value)
 * @method static Builder<static>|Product wherePrice($value)
 * @method static Builder<static>|Product whereStatus($value)
 * @method static Builder<static>|Product whereStock($value)
 * @method static Builder<static>|Product whereUpdatedAt($value)
 * @method static Builder<static>|Product whereUserId($value)
 * @method static Builder<static>|Product withTrashed(bool $withTrashed = true)
 * @method static Builder<static>|Product withoutTrashed()
 * @mixin Eloquent
 */
class Product extends BaseModel
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'name',
        'base_sku',
        'base_unit',
        'base_unit_id',
        'base_barcode',
        'category_id',
        'description',
        'price',
        'cost',
        'stock',
        'min_stock',
        'expiry_date',
        'image',
        'is_active',
        'status',
        'user_id'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'cost' => 'decimal:2',
        'stock' => 'decimal:4',
        'min_stock' => 'integer',
        'is_active' => 'boolean',
        'expiry_date' => 'date',
        'updated_at' => 'datetime',
        'created_at' => 'datetime',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function inventoryTransactions(): HasMany
    {
        return $this->hasMany(InventoryTransaction::class);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function units(): HasMany
    {
        return $this->hasMany(ProductUnit::class);
    }

    public function baseUnit(): HasMany
    {
        return $this->hasMany(ProductUnit::class)->where('is_base_unit', 1);
    }


}
