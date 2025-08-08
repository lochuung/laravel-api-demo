<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('product_units', function (Blueprint $table) {
            $table->dropUnique('unique_sku');
            $table->softDeletes();
            $table->dateTime('deleted_at_marker')
                ->storedAs("IF(`deleted_at` IS NULL, '1000-01-01 00:00:00', `deleted_at`)");
            $table->unique(['sku', 'deleted_at_marker'], 'uniq_sku_not_deleted');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product_units', function (Blueprint $table) {
            $table->dropUnique('uniq_sku_not_deleted');
            $table->dropColumn('deleted_at_marker');
            $table->dropSoftDeletes();
            $table->unique('sku', 'unique_sku');
        });
    }
};
