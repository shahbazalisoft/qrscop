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
        Schema::create('qr_scanners', function (Blueprint $table) {
            $table->id();
            $table->foreignId('store_id')->nullable();
            $table->string('qr_scanner');
            $table->boolean('status')->default(1)->comment('0-Inactive,1-Active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('qr_scanners');
    }
};
