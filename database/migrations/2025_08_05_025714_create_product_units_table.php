<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('product_units', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('product_id');
            $table->string('unit_name', 50)->comment('Tên đơn vị (thùng, hộp, gói)');
            $table->string('sku', 50)->comment('SKU của đơn vị này');
            $table->string('barcode', 50)->nullable()->comment('Mã vạch của đơn vị này');
            $table->decimal('conversion_rate', 10, 4)->comment('Tỷ lệ quy đổi so với đơn vị cơ sở');
            $table->decimal('selling_price', 12, 2)->nullable()->comment('Giá bán của đơn vị này');
            $table->boolean('is_base_unit')->default(false)->comment('Có phải đơn vị cơ sở không');
            $table->timestamps();

            $table->unique('sku', 'unique_sku');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->index(['product_id', 'unit_name'], 'idx_product_unit');
            $table->index('is_base_unit', 'idx_is_base_unit');
        });

        // Di chuyển dữ liệu từ bảng products sang product_units
        DB::statement(
            "
            INSERT INTO product_units (
                product_id, unit_name, sku, conversion_rate, selling_price, is_base_unit, created_at, updated_at
            )
            SELECT
                id,
                'cái',
                CONCAT(code, '-CAI'),
                1.0000,
                price,
                TRUE,
                created_at,
                updated_at
            FROM products
        "
        );
    }

    public function down(): void
    {
        Schema::dropIfExists('product_units');
    }
};
