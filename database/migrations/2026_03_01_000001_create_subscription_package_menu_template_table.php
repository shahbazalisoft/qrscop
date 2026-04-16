<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subscription_package_menu_template', function (Blueprint $table) {
            $table->id();
            $table->foreignId('subscription_package_id');
            $table->foreignId('menu_template_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subscription_package_menu_template');
    }
};
