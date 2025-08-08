<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->renameColumn('code', 'base_sku');
            $table->renameColumn('barcode', 'base_barcode');
        });

        // Now modify base_barcode to be nullable
        Schema::table('products', function (Blueprint $table) {
            $table->string('base_barcode')->nullable()->change();
        });

        Schema::table('products', function (Blueprint $table) {
            $table->unsignedBigInteger('base_unit_id')->nullable()->after('base_sku');
            $table->foreign('base_unit_id')->references('id')->on('product_units')->onDelete('set null');

            $table->string('min_stock', 50)->default('0')->after('stock');
            $table->string('base_unit', 50)->default('cái')->after('base_unit_id');
            $table->enum('status', ['active', 'inactive'])->default('active')->after('is_active');

            $table->unique('base_sku', 'unique_base_sku');
            $table->index('status', 'idx_products_status');

            $table->unsignedBigInteger('user_id')->nullable()->after('id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
        });

        // Update existing data
        DB::statement(
            "
        UPDATE products SET
            base_sku = CONCAT(base_sku, '-BASE'),
            base_unit = 'cái',
            status = CASE WHEN is_active = 1 THEN 'active' ELSE 'inactive' END
    "
        );
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropUnique('unique_base_sku');
            $table->dropIndex('idx_products_status');
            $table->renameColumn('base_sku', 'code');
            $table->renameColumn('base_barcode', 'barcode');
            $table->dropColumn(['base_unit_id', 'base_unit', 'status']);
        });
    }
};
