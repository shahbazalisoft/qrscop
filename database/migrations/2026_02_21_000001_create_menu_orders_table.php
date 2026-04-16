<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('menu_orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('store_id');
            $table->string('order_id', 20)->unique();
            $table->enum('order_type', ['dine-in', 'delivery'])->default('dine-in');
            $table->string('customer_name')->nullable();
            $table->string('customer_phone', 20)->nullable();
            $table->text('delivery_address')->nullable();
            $table->text('instructions')->nullable();
            $table->decimal('subtotal', 10, 2)->default(0);
            $table->decimal('total', 10, 2)->default(0);
            $table->enum('status', ['pending', 'confirmed', 'preparing', 'completed', 'cancelled'])->default('pending');
            $table->timestamps();
        });

        Schema::create('menu_order_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('menu_order_id');
            $table->string('item_name');
            $table->decimal('item_price', 10, 2);
            $table->integer('quantity')->default(1);
            $table->string('size')->default('default');
            $table->string('image')->nullable();
            $table->timestamps();

            $table->foreign('menu_order_id')->references('id')->on('menu_orders')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('menu_order_items');
        Schema::dropIfExists('menu_orders');
    }
};
