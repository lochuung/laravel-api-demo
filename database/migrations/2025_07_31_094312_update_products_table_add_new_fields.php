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
        Schema::table('products', function (Blueprint $table) {
            $table->string('code')->unique()->after('name');
            $table->foreignId('category_id')->nullable()->after('code')->constrained()->onDelete('set null');
            $table->decimal('cost', 10, 2)->default(0.00)->after('price');
            $table->string('barcode')->unique()->after('stock');
            $table->date('expiry_date')->nullable()->after('barcode');
            $table->index(['code', 'category_id', 'barcode', 'expiry_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign(['category_id']);
            $table->dropColumn(['code', 'category_id', 'cost', 'barcode', 'expiry_date']);
        });
    }
};
