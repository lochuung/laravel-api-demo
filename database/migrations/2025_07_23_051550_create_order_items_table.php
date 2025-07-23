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
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreign('order_id')->references('id')
                ->on('orders')
                ->cascadeOnDelete();
            $table->foreign('product_id')->references('id')
                ->on('products')
                ->cascadeOnDelete();
            $table->unsignedBigInteger('order_id');
            $table->unsignedBigInteger('product_id');
            $table->integer('quantity')->default(1);
            $table->decimal('price', 10, 2);
            $table->decimal('total', 10, 2);
            $table->string('note')->nullable(); // Additional note for the order item
            $table->string('sku')->nullable(); // Stock Keeping Unit for the product
            $table->string('product_name'); // Name of the product
            $table->string('product_image')->nullable(); // URL or path to the product image
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
