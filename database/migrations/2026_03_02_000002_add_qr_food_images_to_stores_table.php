<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('stores', function (Blueprint $table) {
            $table->string('qr_food_image_1')->nullable()->after('qr_template');
            $table->string('qr_food_image_2')->nullable()->after('qr_food_image_1');
            $table->string('qr_food_image_3')->nullable()->after('qr_food_image_2');
            $table->string('qr_food_image_4')->nullable()->after('qr_food_image_3');
        });
    }

    public function down(): void
    {
        Schema::table('stores', function (Blueprint $table) {
            $table->dropColumn(['qr_food_image_1', 'qr_food_image_2', 'qr_food_image_3', 'qr_food_image_4']);
        });
    }
};
