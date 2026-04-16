<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kitchen_staff', function (Blueprint $table) {
            $table->id();
            $table->string('f_name', 100);
            $table->string('l_name', 100)->nullable();
            $table->string('email')->unique();
            $table->string('phone', 20)->nullable();
            $table->string('password');
            $table->unsignedBigInteger('store_id');
            $table->unsignedBigInteger('vendor_id');
            $table->boolean('status')->default(true);
            $table->boolean('is_logged_in')->default(false);
            $table->string('login_remember_token')->nullable();
            $table->rememberToken();
            $table->timestamps();

            $table->foreign('store_id')->references('id')->on('stores')->onDelete('cascade');
            $table->foreign('vendor_id')->references('id')->on('vendors')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kitchen_staff');
    }
};
