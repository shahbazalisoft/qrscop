<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('menu_orders', function (Blueprint $table) {
            $table->decimal('discount', 10, 2)->default(0)->after('subtotal');
            $table->decimal('delivery_fee', 10, 2)->default(0)->after('discount');
        });
    }

    public function down(): void
    {
        Schema::table('menu_orders', function (Blueprint $table) {
            $table->dropColumn(['discount', 'delivery_fee']);
        });
    }
};
