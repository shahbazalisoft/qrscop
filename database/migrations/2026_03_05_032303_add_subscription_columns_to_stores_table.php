<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('stores', function (Blueprint $table) {
            if (!Schema::hasColumn('stores', 'item_section')) {
                $table->tinyInteger('item_section')->default(1);
            }
            if (!Schema::hasColumn('stores', 'pos_system')) {
                $table->tinyInteger('pos_system')->default(1);
            }
            if (!Schema::hasColumn('stores', 'free_delivery')) {
                $table->tinyInteger('free_delivery')->default(0);
            }
            if (!Schema::hasColumn('stores', 'reviews_section')) {
                $table->tinyInteger('reviews_section')->default(1);
            }
        });
    }

    public function down(): void
    {
        Schema::table('stores', function (Blueprint $table) {
            $table->dropColumn(['item_section', 'pos_system', 'free_delivery', 'reviews_section']);
        });
    }
};
