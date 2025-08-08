<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('inventory_transactions', function (Blueprint $table) {
            $table->text('notes')->nullable()->after('order_id');
            $table->foreignId('unit_id')->nullable()->after('notes')->constrained('product_units')->onDelete('set null');
            $table->integer('unit_quantity')->nullable()->after('unit_id')->comment('Quantity in the specified unit');
            $table->boolean('is_adjustment')->default(false)->after('unit_quantity')->comment('Whether this is an inventory adjustment');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('inventory_transactions', function (Blueprint $table) {
            $table->dropForeign(['unit_id']);
            $table->dropColumn(['notes', 'unit_id', 'unit_quantity', 'is_adjustment']);
        });
    }
};
