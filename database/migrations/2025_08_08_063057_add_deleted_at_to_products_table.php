<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropUnique('products_sku_unique');       // UNIQUE(sku)
            $table->dropUnique('products_code_unique');      // UNIQUE(base_sku)
            $table->dropUnique('unique_base_sku');           // UNIQUE(base_sku)
            $table->dropUnique('products_barcode_unique');   // UNIQUE(base_barcode)
            $table->dropColumn('sku');                // sku

            $table->softDeletes();

            $table->dateTime('deleted_at_marker')
                ->storedAs("IF(`deleted_at` IS NULL, '1000-01-01 00:00:00', `deleted_at`)");

            $table->unique(['base_sku', 'deleted_at_marker'], 'uniq_base_sku_not_deleted');
            $table->unique(['base_barcode', 'deleted_at_marker'], 'uniq_base_barcode_not_deleted');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropUnique('uniq_base_sku_not_deleted');
            $table->dropUnique('uniq_base_barcode_not_deleted');

            $table->dropColumn('deleted_at_marker');
            $table->dropSoftDeletes();

            $table->string('sku', 64)
                ->after('base_sku')
                ->nullable()
                ->default(null)
                ->comment('Stock Keeping Unit');
            $table->unique('sku', 'products_sku_unique');
            $table->unique('base_sku', 'products_code_unique');
            $table->unique('base_sku', 'unique_base_sku');
            $table->unique('base_barcode', 'products_barcode_unique');
        });
    }
};
