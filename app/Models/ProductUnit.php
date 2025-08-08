<?php

namespace App\Models;

use App\Observers\ProductUnitObserver;
use Eloquent;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $product_id
 * @property string $unit_name Tên đơn vị (thùng, hộp, gói)
 * @property string $sku SKU của đơn vị này
 * @property string|null $barcode Mã vạch của đơn vị này
 * @property numeric $conversion_rate Tỷ lệ quy đổi so với đơn vị cơ sở
 * @property numeric|null $selling_price Giá bán của đơn vị này
 * @property bool $is_base_unit Có phải đơn vị cơ sở không
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property string|null $deleted_at_marker
 * @property-read Product $product
 * @method static Builder<static>|ProductUnit newModelQuery()
 * @method static Builder<static>|ProductUnit newQuery()
 * @method static Builder<static>|ProductUnit onlyTrashed()
 * @method static Builder<static>|ProductUnit query()
 * @method static Builder<static>|ProductUnit whereBarcode($value)
 * @method static Builder<static>|ProductUnit whereConversionRate($value)
 * @method static Builder<static>|ProductUnit whereCreatedAt($value)
 * @method static Builder<static>|ProductUnit whereDeletedAt($value)
 * @method static Builder<static>|ProductUnit whereDeletedAtMarker($value)
 * @method static Builder<static>|ProductUnit whereId($value)
 * @method static Builder<static>|ProductUnit whereIsBaseUnit($value)
 * @method static Builder<static>|ProductUnit whereProductId($value)
 * @method static Builder<static>|ProductUnit whereSellingPrice($value)
 * @method static Builder<static>|ProductUnit whereSku($value)
 * @method static Builder<static>|ProductUnit whereUnitName($value)
 * @method static Builder<static>|ProductUnit whereUpdatedAt($value)
 * @method static Builder<static>|ProductUnit withTrashed(bool $withTrashed = true)
 * @method static Builder<static>|ProductUnit withoutTrashed()
 * @mixin Eloquent
 */
#[ObservedBy([ProductUnitObserver::class])]
class ProductUnit extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'product_id',
        'unit_name',
        'sku',
        'barcode',
        'conversion_rate',
        'selling_price',
        'is_base_unit',
    ];

    protected $casts = [
        'conversion_rate' => 'decimal:4',
        'selling_price' => 'decimal:2',
        'is_base_unit' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public static function normalize(string $name): string
    {
        $name = strtolower($name);
        $name = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $name); // Bỏ dấu
        $name = preg_replace('/[^A-Za-z0-9]/', '', $name);        // Loại ký tự lạ
        return strtoupper($name);
    }
}
