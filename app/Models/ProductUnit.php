<?php

namespace App\Models;

use App\Observers\ProductUnitObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @mixin \Eloquent
 */
#[ObservedBy([ProductUnitObserver::class])]
class ProductUnit extends Model
{
    use HasFactory, SoftDeletes;

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
